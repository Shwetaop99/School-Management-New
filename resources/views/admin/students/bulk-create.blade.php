
@extends('layouts.app')

@section('title', 'Bulk Student Registration')

@section('content')
@php
    $defaultAcademicYear = date('Y') . '-' . (date('Y') + 1);
    $oldStudents = old('students', [[]]);
@endphp

<style>
    .bulk-page {
        --primary: #1677f0;
        --primary-light: #eef5ff;
        --border: #e5eaf1;
        --muted: #718096;
        color: #263449;
    }

    .bulk-page .page-heading {
        display:flex; justify-content:space-between; align-items:center;
        flex-wrap:wrap; gap:15px; margin-bottom:24px;
    }

    .bulk-page .page-heading h2 {
        font-size:25px; font-weight:750; margin-bottom:5px;
    }

    .bulk-page .page-heading p { color:var(--muted); margin:0; }

    .bulk-page .registration-card {
        background:#fff; border:1px solid var(--border);
        border-radius:16px; box-shadow:0 5px 22px rgba(25,45,75,.045);
        overflow:hidden;
    }

    .bulk-page .student-toolbar {
        display:flex; justify-content:space-between; align-items:center;
        flex-wrap:wrap; gap:12px; padding:18px 24px;
        background:#fbfcfe; border-bottom:1px solid var(--border);
    }

    .bulk-page .student-toolbar label { font-weight:650; margin-bottom:6px; }

    .bulk-page .student-selector { min-width:250px; max-width:100%; }

    .bulk-page .student-count {
        background:var(--primary-light); color:var(--primary);
        padding:8px 13px; border-radius:9px;
        font-weight:700; font-size:13px;
    }

    .bulk-page .student-panel[hidden],
    .bulk-page .tab-content-section[hidden] { display:none !important; }

    .bulk-page .student-panel { border-bottom:1px solid var(--border); }

    .bulk-page .registration-tabs {
        display:flex; overflow-x:auto; background:#f8faff;
        border-bottom:1px solid var(--border);
    }

    .bulk-page .registration-tab {
        display:flex; align-items:center; gap:10px;
        flex:1 0 175px; min-width:175px; padding:16px 18px;
        border:0; border-bottom:3px solid transparent;
        background:transparent; text-align:left; color:#64748b;
        font-weight:650; font-size:13px;
    }

    .bulk-page .registration-tab.active {
        background:#fff; border-bottom-color:var(--primary);
        color:var(--primary);
    }

    .bulk-page .tab-number {
        width:31px; height:31px; flex-shrink:0; border-radius:50%;
        display:grid; place-items:center; background:#e9edf5; color:#64748b;
    }

    .bulk-page .registration-tab.active .tab-number {
        background:var(--primary); color:#fff;
    }

    .bulk-page .tab-label small {
        display:block; margin-top:3px; color:var(--muted);
        font-size:11px; font-weight:400;
    }

    .bulk-page .registration-body { padding:28px; }

    .bulk-page .section-heading {
        display:flex; align-items:center; gap:13px; margin-bottom:24px;
    }

    .bulk-page .heading-icon {
        width:44px; height:44px; display:grid; place-items:center;
        border-radius:12px; background:var(--primary-light);
        color:var(--primary); font-size:20px;
    }

    .bulk-page .section-heading h5 {
        font-size:17px; font-weight:750; margin:0 0 4px;
    }

    .bulk-page .section-heading p {
        color:var(--muted); font-size:12px; margin:0;
    }

    .bulk-page .form-label {
        font-size:13px; font-weight:650; color:#39475b; margin-bottom:7px;
    }

    .bulk-page .required-star { color:#dc3545; }

    .bulk-page .form-control,
    .bulk-page .form-select {
        min-height:43px; border:1px solid #dce3ec;
        border-radius:8px; font-size:13px;
    }

    .bulk-page textarea.form-control { min-height:95px; }

    .bulk-page .form-control:focus,
    .bulk-page .form-select:focus {
        border-color:var(--primary);
        box-shadow:0 0 0 3px rgba(22,119,240,.10);
    }

    .bulk-page .form-text { font-size:11px; color:var(--muted); }

    .bulk-page .parent-card {
        border:1px solid var(--border); border-radius:12px;
        padding:20px; margin-bottom:20px; background:#fff;
    }

    .bulk-page .parent-card h6 {
        font-size:14px; font-weight:750; margin-bottom:18px;
        color:#344256;
    }

    .bulk-page .photo-box {
        border:1px dashed #b8c9df; border-radius:12px;
        background:#fafcff; padding:16px; height:100%;
    }

    .bulk-page .photo-preview {
        width:100%; max-width:190px; height:165px;
        object-fit:cover; border-radius:10px;
        border:1px solid var(--border); background:#fff;
        display:none; margin:0 auto 12px;
    }

    .bulk-page .photo-placeholder {
        height:165px; display:flex; align-items:center;
        justify-content:center; flex-direction:column;
        color:#8190a5; gap:8px;
    }

    .bulk-page .photo-placeholder i { font-size:38px; }

    .bulk-page .student-panel-footer,
    .bulk-page .main-footer {
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:12px; padding:18px 28px;
        background:#fbfcfe; border-top:1px solid var(--border);
    }

    .bulk-page .main-footer { padding:20px 24px; }

    .bulk-page .btn {
        border-radius:8px; font-size:13px; font-weight:650; padding:10px 16px;
    }

    .bulk-page .btn-primary { background:var(--primary); border-color:var(--primary); }

    .bulk-page .invalid-feedback { font-size:11px; }

    .bulk-page .form-control.is-invalid,
    .bulk-page .form-select.is-invalid { border-color:#dc3545; }

    @media(max-width:767px) {
        .bulk-page .registration-body { padding:18px; }
        .bulk-page .student-toolbar,
        .bulk-page .main-footer,
        .bulk-page .student-panel-footer { padding:16px; }
        .bulk-page .student-selector { width:100%; }
    }
</style>

<div class="container-fluid py-4 bulk-page">

    <div class="page-heading">
        <div>
            <h2><i class="bi bi-people-fill text-primary me-2"></i>Bulk Student Registration</h2>
            <p>Register new admissions or update existing student records.</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Students
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">Please correct the following errors:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="bulkStudentForm"
          action="{{ route('admin.students.bulk-store') }}"
          method="POST"
          enctype="multipart/form-data"
          novalidate>
        @csrf

        <div class="registration-card">

            <div class="student-toolbar">
                <div>
                    <label for="studentSelector" class="d-block">Select Student Form</label>
                    <select id="studentSelector" class="form-select student-selector"></select>
                    <div class="form-text">Switch between student forms to enter their details.</div>
                </div>

                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="student-count" id="studentCount">1 Student</span>
                    <button type="button" class="btn btn-primary" id="addStudentBtn">
                        <i class="bi bi-plus-circle me-1"></i> Add Student
                    </button>
                </div>
            </div>

            <div id="studentPanels"></div>

            <div class="main-footer">
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    Required fields are marked with <span class="required-star">*</span>.
                    Maximum 100 students per submission.
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="saveAllBtn">
                        <i class="bi bi-check2-circle me-1"></i> Save All Students
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="studentPanelTemplate">
    <div class="student-panel" data-index="__INDEX__">

        <div class="registration-tabs" role="tablist">
            <button type="button" class="registration-tab active" data-tab="basic">
                <span class="tab-number">1</span>
                <span class="tab-label">Basic Information<small>Personal details</small></span>
            </button>
            <button type="button" class="registration-tab" data-tab="academic">
                <span class="tab-number">2</span>
                <span class="tab-label">Academic Information<small>Admission details</small></span>
            </button>
            <button type="button" class="registration-tab" data-tab="parents">
                <span class="tab-number">3</span>
                <span class="tab-label">Parents Data<small>Parent information</small></span>
            </button>
            <button type="button" class="registration-tab" data-tab="previous">
                <span class="tab-number">4</span>
                <span class="tab-label">Previous School<small>Previous education</small></span>
            </button>
            <button type="button" class="registration-tab" data-tab="address">
                <span class="tab-number">5</span>
                <span class="tab-label">Address<small>Location details</small></span>
            </button>
        </div>

        {{-- BASIC INFORMATION --}}
        <section class="tab-content-section" data-section="basic">
            <div class="registration-body">
                <div class="section-heading">
                    <div class="heading-icon"><i class="bi bi-person-vcard"></i></div>
                    <div>
                        <h5>Basic Information</h5>
                        <p>Enter personal and identification details.</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-4">
                        <div class="photo-box">
                            <label class="form-label d-block">Student Photograph</label>
                            <img class="photo-preview" data-field="imagePreview" alt="Student photo preview">
                            <div class="photo-placeholder" data-field="imagePlaceholder">
                                <i class="bi bi-person-bounding-box"></i>
                                <span class="small">Photo preview</span>
                            </div>
                            <input type="file" class="form-control" data-name="profile_image"
                                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            <div class="form-text mt-2">JPG, PNG, WEBP. Maximum 2 MB.</div>
                            <div class="invalid-feedback" data-error-for="profile_image"></div>
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-8">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Student ID</label>
                                <input type="text" class="form-control" data-name="student_id"
                                       placeholder="Existing ID, if available">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="required-star">*</span></label>
                                <input type="text" class="form-control" data-name="first_name" maxlength="100" required>
                                <div class="invalid-feedback" data-error-for="first_name"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" data-name="middle_name" maxlength="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name <span class="required-star">*</span></label>
                                <input type="text" class="form-control" data-name="last_name" maxlength="100" required>
                                <div class="invalid-feedback" data-error-for="last_name"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Name in Marathi</label>
                                <input type="text" class="form-control" data-name="marathi_name"
                                       maxlength="255" placeholder="मराठी नाव">
                                <div class="form-text">Can be entered manually.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gender <span class="required-star">*</span></label>
                                <select class="form-select" data-name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback" data-error-for="gender"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" data-name="date_of_birth">
                                <div class="invalid-feedback" data-error-for="date_of_birth"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Birth Place</label>
                                <input type="text" class="form-control" data-name="birth_place" maxlength="255">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" class="form-control" data-name="aadhar_card_no"
                                       inputmode="numeric" maxlength="12" placeholder="12-digit Aadhaar">
                                <div class="invalid-feedback" data-error-for="aadhar_card_no"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Student Mobile Number</label>
                                <input type="tel" class="form-control" data-name="phone"
                                       maxlength="16" placeholder="10-digit mobile">
                                <div class="invalid-feedback" data-error-for="phone"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" data-name="email" maxlength="255">
                                <div class="invalid-feedback" data-error-for="email"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ACADEMIC INFORMATION --}}
        <section class="tab-content-section" data-section="academic" hidden>
            <div class="registration-body">
                <div class="section-heading">
                    <div class="heading-icon"><i class="bi bi-mortarboard"></i></div>
                    <div>
                        <h5>Academic Information</h5>
                        <p>Admission, class, and academic record details.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Academic Year <span class="required-star">*</span></label>
                        <input type="text" class="form-control" data-name="academic_year"
                               value="{{ $defaultAcademicYear }}" maxlength="20" required>
                        <div class="invalid-feedback" data-error-for="academic_year"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Admission Class <span class="required-star">*</span></label>
                        <select class="form-select" data-name="admission_class" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->class_name }}">
                                    {{ $class->class_name }}
                                    @if(!empty($class->section))
                                        - {{ $class->section }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" data-error-for="admission_class"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Section <span class="required-star">*</span></label>
                        <input type="text" class="form-control" data-name="section"
                               maxlength="20" placeholder="e.g. A, B, C" required>
                        <div class="invalid-feedback" data-error-for="section"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Roll Number</label>
                        <input type="text" class="form-control" data-name="roll_number" maxlength="50">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Admission Date</label>
                        <input type="date" class="form-control" data-name="admission_date">
                        <div class="invalid-feedback" data-error-for="admission_date"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Register Number</label>
                        <input type="text" class="form-control" data-name="register_no" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Book Number</label>
                        <input type="text" class="form-control" data-name="book_no" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">APAAR ID</label>
                        <input type="text" class="form-control" data-name="appar_id" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">PEN Number</label>
                        <input type="text" class="form-control" data-name="pen_no" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Saral ID</label>
                        <input type="text" class="form-control" data-name="saral_id" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Medium</label>
                        <select class="form-select" data-name="medium">
                            <option value="">Select Medium</option>
                            <option value="Marathi">Marathi</option>
                            <option value="English">English</option>
                            <option value="Semi-English">Semi-English</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mother Tongue</label>
                        <input type="text" class="form-control" data-name="mother_tongue" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" data-name="nationality"
                               value="Indian" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Religion</label>
                        <input type="text" class="form-control" data-name="religion" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Caste / Category</label>
                        <input type="text" class="form-control" data-name="caste" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sub-Caste</label>
                        <input type="text" class="form-control" data-name="sub_caste" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Student Status</label>
                        <select class="form-select" data-name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- PARENTS DATA --}}
        <section class="tab-content-section" data-section="parents" hidden>
            <div class="registration-body">
                <div class="section-heading">
                    <div class="heading-icon"><i class="bi bi-people"></i></div>
                    <div>
                        <h5>Parents Data</h5>
                        <p>Parent and guardian details.</p>
                    </div>
                </div>

                <div class="parent-card">
                    <h6><i class="bi bi-person me-2 text-primary"></i>Father's Information</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" data-name="father_name" maxlength="150">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Father's Mobile Number</label>
                            <input type="tel" class="form-control" data-name="father_phone"
                                   maxlength="10" inputmode="numeric">
                            <div class="invalid-feedback" data-error-for="father_phone"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Father's Occupation</label>
                            <input type="text" class="form-control" data-name="father_occupation" maxlength="150">
                        </div>
                    </div>
                </div>

                <div class="parent-card">
                    <h6><i class="bi bi-person me-2 text-primary"></i>Mother's Information</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" class="form-control" data-name="mother_name" maxlength="150">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mother's Mobile Number</label>
                            <input type="tel" class="form-control" data-name="mother_phone"
                                   maxlength="10" inputmode="numeric">
                            <div class="invalid-feedback" data-error-for="mother_phone"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mother's Occupation</label>
                            <input type="text" class="form-control" data-name="mother_occupation" maxlength="150">
                        </div>
                    </div>
                </div>

                <div class="parent-card mb-0">
                    <h6><i class="bi bi-person-badge me-2 text-primary"></i>Guardian Information</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Guardian's Name</label>
                            <input type="text" class="form-control" data-name="guardian_name" maxlength="150">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Relationship with Student</label>
                            <input type="text" class="form-control" data-name="guardian_relation" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Guardian's Mobile Number</label>
                            <input type="tel" class="form-control" data-name="guardian_phone"
                                   maxlength="10" inputmode="numeric">
                            <div class="invalid-feedback" data-error-for="guardian_phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- PREVIOUS SCHOOL --}}
        <section class="tab-content-section" data-section="previous" hidden>
            <div class="registration-body">
                <div class="section-heading">
                    <div class="heading-icon"><i class="bi bi-building"></i></div>
                    <div>
                        <h5>Previous School Details</h5>
                        <p>Previous education details, if applicable.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Previous School Name</label>
                        <input type="text" class="form-control" data-name="previous_school_name" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Previous Class</label>
                        <input type="text" class="form-control" data-name="previous_school_class" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Previous School Board</label>
                        <input type="text" class="form-control" data-name="previous_school_board" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Previous School Medium</label>
                        <input type="text" class="form-control" data-name="previous_school_medium" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Previous Result / Percentage</label>
                        <input type="text" class="form-control" data-name="previous_school_result" maxlength="100">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Previous School Address</label>
                        <textarea class="form-control" data-name="previous_school_address"
                                  maxlength="500" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" data-name="previous_school_remarks"
                                  maxlength="1000" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </section>

        {{-- ADDRESS WITH LOCATION API --}}
        <section class="tab-content-section" data-section="address" hidden>
            <div class="registration-body">
                <div class="section-heading">
                    <div class="heading-icon"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <h5>Address Information</h5>
                        <p>Select the state, district, taluka, and village in sequence.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <select class="form-select" data-name="country">
                            <option value="India" selected>India</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <select class="form-select" data-name="state" data-location="state">
                            <option value="">Select State</option>
                            <option value="Maharashtra" data-state-id="1">Maharashtra</option>
                        </select>
                        <div class="invalid-feedback" data-error-for="state"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">District</label>
                        <select class="form-select" data-name="district" data-location="district" disabled>
                            <option value="">Select District</option>
                        </select>
                        <div class="invalid-feedback" data-error-for="district"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Taluka</label>
                        <select class="form-select" data-name="taluka" data-location="taluka" disabled>
                            <option value="">Select Taluka</option>
                        </select>
                        <div class="invalid-feedback" data-error-for="taluka"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">City / Village</label>
                        <select class="form-select" data-name="city_village" data-location="city" disabled>
                            <option value="">Select City / Village</option>
                        </select>
                        <div class="invalid-feedback" data-error-for="city_village"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pincode</label>
                        <input type="text" class="form-control" data-name="pincode"
                               maxlength="6" inputmode="numeric" placeholder="Auto-filled from village" readonly>
                        <div class="invalid-feedback" data-error-for="pincode"></div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Full Address</label>
                        <textarea class="form-control" data-name="address" maxlength="1000"
                                  rows="4" placeholder="House number, street, landmark, etc."></textarea>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-warning py-2 small mb-0"
                             data-location-error hidden>
                            Unable to load locations. Check your location routes and API response.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="student-panel-footer">
            <button type="button" class="btn btn-outline-danger" data-action="remove-student">
                <i class="bi bi-trash me-1"></i> Remove Student
            </button>

            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-primary" data-action="previous-tab">
                    <i class="bi bi-arrow-left me-1"></i> Previous
                </button>
                <button type="button" class="btn btn-primary" data-action="next-tab">
                    Next <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bulkStudentForm');
    const panelsContainer = document.getElementById('studentPanels');
    const template = document.getElementById('studentPanelTemplate');
    const selector = document.getElementById('studentSelector');
    const addButton = document.getElementById('addStudentBtn');
    const countLabel = document.getElementById('studentCount');
    const saveButton = document.getElementById('saveAllBtn');

    const maxStudents = 100;
    const tabs = ['basic', 'academic', 'parents', 'previous', 'address'];

    let nextIndex = 0;
    let activeIndex = 0;
    let isSubmitting = false;

    const oldStudents = @json($oldStudents);
    const serverErrors = @json($errors->messages());

    const locationUrls = {
        districts: @json(route('admin.locations.districts')),
        talukas: @json(route('admin.locations.tehsils')),
        cities: @json(route('admin.locations.locations'))
    };

    function getPanels() {
        return Array.from(panelsContainer.querySelectorAll('.student-panel'));
    }

    function getPanel(index) {
        return panelsContainer.querySelector(
            '.student-panel[data-index="' + index + '"]'
        );
    }

    function field(panel, name) {
        return panel.querySelector('[data-name="' + name + '"]');
    }

    function setFieldValue(panel, name, value) {
        const input = field(panel, name);
        if (!input || value === null || value === undefined || input.type === 'file') return;

        input.value = value;
    }

    function applyNames(panel, index) {
        panel.querySelectorAll('[data-name]').forEach(function (input) {
            input.name = 'students[' + index + '][' + input.dataset.name + ']';
        });
    }

    function clearFieldError(input) {
        input.classList.remove('is-invalid');

        const error = input.closest('.student-panel')?.querySelector(
            '[data-error-for="' + input.dataset.name + '"]'
        );

        if (error) error.textContent = '';
    }

    function showFieldError(input, message) {
        input.classList.add('is-invalid');

        const error = input.closest('.student-panel')?.querySelector(
            '[data-error-for="' + input.dataset.name + '"]'
        );

        if (error) error.textContent = message;
    }

    function activateTab(panel, tabName) {
        panel.querySelectorAll('.registration-tab').forEach(function (button) {
            button.classList.toggle('active', button.dataset.tab === tabName);
        });

        panel.querySelectorAll('.tab-content-section').forEach(function (section) {
            section.hidden = section.dataset.section !== tabName;
        });
    }

    function currentTabIndex(panel) {
        const activeTab = panel.querySelector('.registration-tab.active');
        return tabs.indexOf(activeTab?.dataset.tab || 'basic');
    }

    function updateSelector() {
        const panels = getPanels();
        selector.innerHTML = '';

        panels.forEach(function (panel, position) {
            const index = Number(panel.dataset.index);
            const first = field(panel, 'first_name')?.value?.trim() || '';
            const last = field(panel, 'last_name')?.value?.trim() || '';

            const option = document.createElement('option');
            option.value = String(index);
            option.textContent = 'Student ' + (position + 1);

            if (first || last) option.textContent += ' — ' + [first, last].filter(Boolean).join(' ');

            selector.appendChild(option);
        });

        countLabel.textContent = panels.length + (panels.length === 1 ? ' Student' : ' Students');

        if (getPanel(activeIndex)) selector.value = String(activeIndex);
    }

    function showActiveStudent() {
        getPanels().forEach(function (panel) {
            panel.hidden = Number(panel.dataset.index) !== activeIndex;
        });

        const panel = getPanel(activeIndex);

        if (panel) {
            const activeTab = panel.querySelector('.registration-tab.active');
            activateTab(panel, activeTab?.dataset.tab || 'basic');
        }

        updateSelector();
    }

    // LOCATION API HELPERS
    async function fetchLocationList(url, parameter, value) {
        const requestUrl = new URL(url, window.location.origin);
        requestUrl.searchParams.set(parameter, value);

        const response = await fetch(requestUrl.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Location request failed: HTTP ' + response.status);
        }

        const result = await response.json();

        if (Array.isArray(result)) return result;
        if (result && Array.isArray(result.data)) return result.data;
        if (result && Array.isArray(result.results)) return result.results;

        throw new Error(result?.message || 'Unexpected location API response.');
    }

    function resetLocationSelect(select, placeholder) {
        select.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);

        select.disabled = true;
    }

    function fillLocationSelect(select, items, valueKey, labelKey, placeholder) {
        select.innerHTML = '';

        const first = document.createElement('option');
        first.value = '';
        first.textContent = placeholder;
        select.appendChild(first);

        items.forEach(function (item) {
            const value = item[valueKey];
            const label = item[labelKey];

            if (value === undefined || value === null || label === undefined || label === null) {
                return;
            }

            const option = document.createElement('option');
            option.value = String(value);
            option.textContent = String(label);
            option.dataset.locationId = String(item.id ?? value);
            option.dataset.pincode = String(
                item.pin_code ?? item.pincode ?? item.pinCode ?? ''
            );

            select.appendChild(option);
        });

        select.disabled = items.length === 0;
    }

    function showLocationError(panel, message) {
        const alert = panel.querySelector('[data-location-error]');
        if (!alert) return;

        alert.textContent = message;
        alert.hidden = false;
    }

    function clearLocationError(panel) {
        const alert = panel.querySelector('[data-location-error]');
        if (!alert) return;

        alert.textContent = '';
        alert.hidden = true;
    }

    function attachLocationDropdowns(panel) {
        const stateSelect = panel.querySelector('[data-location="state"]');
        const districtSelect = panel.querySelector('[data-location="district"]');
        const talukaSelect = panel.querySelector('[data-location="taluka"]');
        const citySelect = panel.querySelector('[data-location="city"]');
        const pincodeInput = field(panel, 'pincode');

        stateSelect.addEventListener('change', async function () {
            const stateId = stateSelect.selectedOptions[0]?.dataset.stateId;

            resetLocationSelect(districtSelect, 'Select District');
            resetLocationSelect(talukaSelect, 'Select Taluka');
            resetLocationSelect(citySelect, 'Select City / Village');
            pincodeInput.value = '';
            clearLocationError(panel);

            clearFieldError(stateSelect);

            if (!stateId) return;

            try {
                const districts = await fetchLocationList(
                    locationUrls.districts, 'state_id', stateId
                );

                fillLocationSelect(
                    districtSelect, districts,
                    'district_name', 'district_name', 'Select District'
                );
            } catch (error) {
                console.error('District API error:', error);
                showLocationError(panel, 'Could not load districts. Check the districts route and API response.');
            }
        });

        districtSelect.addEventListener('change', async function () {
            const districtId = districtSelect.selectedOptions[0]?.dataset.locationId;

            resetLocationSelect(talukaSelect, 'Select Taluka');
            resetLocationSelect(citySelect, 'Select City / Village');
            pincodeInput.value = '';
            clearLocationError(panel);

            clearFieldError(districtSelect);

            if (!districtId) return;

            try {
                const talukas = await fetchLocationList(
                    locationUrls.talukas, 'district_id', districtId
                );

                fillLocationSelect(
                    talukaSelect, talukas,
                    'id', 'tehsil_name', 'Select Taluka'
                );
            } catch (error) {
                console.error('Taluka API error:', error);
                showLocationError(panel, 'Could not load talukas. Check the tehsils route and API response.');
            }
        });

        talukaSelect.addEventListener('change', async function () {
            const talukaId = talukaSelect.selectedOptions[0]?.dataset.locationId;

            resetLocationSelect(citySelect, 'Select City / Village');
            pincodeInput.value = '';
            clearLocationError(panel);

            clearFieldError(talukaSelect);

            if (!talukaId) return;

            try {
                const cities = await fetchLocationList(
                    locationUrls.cities, 'tehsil_id', talukaId
                );

                fillLocationSelect(
                    citySelect, cities,
                    'id', 'location_name', 'Select City / Village'
                );
            } catch (error) {
                console.error('City/village API error:', error);
                showLocationError(panel, 'Could not load cities or villages. Check the locations route and API response.');
            }
        });

        citySelect.addEventListener('change', function () {
            const selectedCity = citySelect.selectedOptions[0];

            pincodeInput.value = selectedCity?.dataset.pincode || '';
            clearLocationError(panel);
            clearFieldError(citySelect);
        });
    }

    function attachPhotoPreview(panel) {
        const input = field(panel, 'profile_image');
        const preview = panel.querySelector('[data-field="imagePreview"]');
        const placeholder = panel.querySelector('[data-field="imagePlaceholder"]');

        input.addEventListener('change', function () {
            clearFieldError(input);

            const file = input.files?.[0];

            if (!file) {
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
                preview.removeAttribute('src');
                return;
            }

            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                input.value = '';
                showFieldError(input, 'Choose a JPG, PNG, or WEBP image.');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                input.value = '';
                showFieldError(input, 'Photo size must not exceed 2 MB.');
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };

            reader.readAsDataURL(file);
        });
    }

    function attachNumericFiltering(panel) {
        [
            'aadhar_card_no', 'phone', 'father_phone',
            'mother_phone', 'guardian_phone', 'pincode'
        ].forEach(function (name) {
            const input = field(panel, name);

            input.addEventListener('input', function () {
                if (name === 'phone') {
                    let value = input.value.replace(/[^\d+]/g, '');

                    if (value.startsWith('+')) {
                        value = '+' + value.slice(1).replace(/\+/g, '').slice(0, 12);
                    } else {
                        value = value.replace(/\+/g, '').slice(0, 10);
                    }

                    input.value = value;
                } else {
                    const limit = name === 'aadhar_card_no' ? 12 :
                        name === 'pincode' ? 6 : 10;

                    input.value = input.value.replace(/\D/g, '').slice(0, limit);
                }

                clearFieldError(input);
            });
        });
    }

    function attachMarathiHelper(panel) {
        const firstName = field(panel, 'first_name');
        const middleName = field(panel, 'middle_name');
        const lastName = field(panel, 'last_name');
        const marathiName = field(panel, 'marathi_name');

        let manuallyEdited = Boolean(marathiName.value.trim());
        let requestNumber = 0;

        marathiName.addEventListener('input', function () {
            manuallyEdited = true;
        });

        async function generateMarathiName() {
            if (manuallyEdited || marathiName.value.trim()) return;

            const name = [
                firstName.value.trim(),
                middleName.value.trim(),
                lastName.value.trim()
            ].filter(Boolean).join(' ');

            if (!name) return;

            const currentRequest = ++requestNumber;

            try {
                const url = 'https://inputtools.google.com/request?text=' +
                    encodeURIComponent(name) +
                    '&itc=mr-t-i0-und&num=1';

                const response = await fetch(url);
                const data = await response.json();

                if (
                    currentRequest === requestNumber &&
                    !manuallyEdited &&
                    !marathiName.value.trim() &&
                    data[0] === 'SUCCESS' &&
                    data[1]?.[0]?.[1]?.[0]
                ) {
                    marathiName.value = data[1][0][1][0];
                }
            } catch (error) {
                // The Marathi name can be entered manually if transliteration fails.
            }
        }

        [firstName, middleName, lastName].forEach(function (input) {
            input.addEventListener('blur', generateMarathiName);
        });
    }

    function addStudent(values = {}, focusNewStudent = true) {
        if (getPanels().length >= maxStudents) {
            alert('You can register a maximum of ' + maxStudents + ' students at a time.');
            return null;
        }

        const index = nextIndex++;
        const fragment = template.content.cloneNode(true);
        const panel = fragment.querySelector('.student-panel');

        panel.dataset.index = String(index);
        applyNames(panel, index);

        Object.entries(values || {}).forEach(function ([name, value]) {
            setFieldValue(panel, name, value);
        });

        panelsContainer.appendChild(fragment);

        const newPanel = getPanel(index);

        if (!field(newPanel, 'academic_year').value) {
            field(newPanel, 'academic_year').value = @json($defaultAcademicYear);
        }

        if (!field(newPanel, 'country').value) field(newPanel, 'country').value = 'India';
        if (!field(newPanel, 'nationality').value) field(newPanel, 'nationality').value = 'Indian';
        if (!field(newPanel, 'status').value) field(newPanel, 'status').value = 'active';

        if (!field(newPanel, 'admission_date').value) {
            field(newPanel, 'admission_date').value = new Date().toISOString().slice(0, 10);
        }

        // Restore a previous address value when returning from server validation.
        // Location dropdowns must be reselected if their IDs were not returned.
        const oldState = values.state || '';

        if (oldState) {
            const stateSelect = field(newPanel, 'state');
            const stateOption = Array.from(stateSelect.options).find(
                option => option.value === oldState
            );

            if (stateOption) stateSelect.value = oldState;
        }

        activateTab(newPanel, 'basic');

        attachPhotoPreview(newPanel);
        attachNumericFiltering(newPanel);
        attachMarathiHelper(newPanel);
        attachLocationDropdowns(newPanel);

        activeIndex = index;
        updateSelector();

        if (focusNewStudent) {
            showActiveStudent();
            newPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        return newPanel;
    }

    function validatePanel(panel, allFields = false) {
        let firstInvalid = null;
        let valid = true;

        const requiredNames = [
            'first_name', 'last_name', 'gender',
            'academic_year', 'admission_class', 'section'
        ];

        const parentPhoneNames = ['father_phone', 'mother_phone', 'guardian_phone'];

        panel.querySelectorAll('[data-name]').forEach(function (input) {
            if (!allFields && input.closest('.tab-content-section')?.hidden) return;

            clearFieldError(input);

            const name = input.dataset.name;
            const value = (input.value || '').trim();
            let message = '';

            if (requiredNames.includes(name) && !value) {
                message = 'This field is required.';
            }

            if (value && name === 'aadhar_card_no' && !/^\d{12}$/.test(value)) {
                message = 'Aadhaar must contain exactly 12 digits.';
            }

            if (value && name === 'phone' &&
                !/^(?:\+91[\s-]?)?[6-9]\d{9}$/.test(value)) {
                message = 'Enter a valid 10-digit mobile number, optionally with +91.';
            }

            if (value && parentPhoneNames.includes(name) && !/^[6-9]\d{9}$/.test(value)) {
                message = 'Enter a valid 10-digit mobile number.';
            }

            if (value && name === 'pincode' && !/^\d{6}$/.test(value)) {
                message = 'Pincode must contain exactly 6 digits.';
            }

            if (value && name === 'email' &&
                !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                message = 'Enter a valid email address.';
            }

            if (value && ['date_of_birth', 'admission_date'].includes(name)) {
                const date = new Date(value + 'T00:00:00');

                if (Number.isNaN(date.getTime())) {
                    message = 'Enter a valid date.';
                } else if (name === 'date_of_birth' && date > new Date()) {
                    message = 'Date of birth cannot be in the future.';
                }
            }

            if (input.type === 'file' && input.files?.[0]) {
                const file = input.files[0];

                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    message = 'Choose a JPG, PNG, or WEBP image.';
                } else if (file.size > 2 * 1024 * 1024) {
                    message = 'Photo size must not exceed 2 MB.';
                }
            }

            if (message) {
                valid = false;
                showFieldError(input, message);
                if (!firstInvalid) firstInvalid = input;
            }
        });

        return { valid, firstInvalid };
    }

    function focusInvalid(panel, input) {
        if (!input) return;

        const section = input.closest('.tab-content-section');
        if (section) activateTab(panel, section.dataset.section);

        input.focus({ preventScroll: true });
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    selector.addEventListener('change', function () {
        activeIndex = Number(selector.value);
        showActiveStudent();
    });

    addButton.addEventListener('click', function () {
        addStudent({});
    });

    panelsContainer.addEventListener('click', function (event) {
        const panel = event.target.closest('.student-panel');
        if (!panel) return;

        const index = Number(panel.dataset.index);
        const tabButton = event.target.closest('.registration-tab');

        if (tabButton) {
            const currentResult = validatePanel(panel, false);

            if (!currentResult.valid) {
                focusInvalid(panel, currentResult.firstInvalid);
                return;
            }

            activateTab(panel, tabButton.dataset.tab);
            activeIndex = index;
            updateSelector();
            return;
        }

        const actionButton = event.target.closest('[data-action]');
        if (!actionButton) return;

        const action = actionButton.dataset.action;
        const tabIndex = currentTabIndex(panel);

        if (action === 'next-tab') {
            const result = validatePanel(panel, false);

            if (!result.valid) {
                focusInvalid(panel, result.firstInvalid);
                return;
            }

            if (tabIndex < tabs.length - 1) {
                activateTab(panel, tabs[tabIndex + 1]);
            } else {
                const panels = getPanels();
                const position = panels.indexOf(panel);

                if (position < panels.length - 1) {
                    activeIndex = Number(panels[position + 1].dataset.index);
                    showActiveStudent();
                } else {
                    addStudent({});
                }
            }
        }

        if (action === 'previous-tab') {
            if (tabIndex > 0) {
                activateTab(panel, tabs[tabIndex - 1]);
            } else {
                const panels = getPanels();
                const position = panels.indexOf(panel);

                if (position > 0) {
                    activeIndex = Number(panels[position - 1].dataset.index);
                    showActiveStudent();
                }
            }
        }

        if (action === 'remove-student') {
            if (getPanels().length === 1) {
                alert('At least one student form is required.');
                return;
            }

            if (!confirm('Remove this student form? Its entered details will be lost.')) return;

            panel.remove();
            activeIndex = Number(getPanels()[0].dataset.index);
            showActiveStudent();
        }
    });

    panelsContainer.addEventListener('input', function (event) {
        if (event.target.matches('[data-name]')) {
            clearFieldError(event.target);
            updateSelector();
        }
    });

    panelsContainer.addEventListener('change', function (event) {
        if (event.target.matches('[data-name]')) {
            clearFieldError(event.target);
            updateSelector();
        }
    });

    form.addEventListener('submit', function (event) {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        const panels = getPanels();

        if (!panels.length) {
            event.preventDefault();
            alert('Please add at least one student.');
            return;
        }

        let firstProblem = null;

        for (const panel of panels) {
            const result = validatePanel(panel, true);

            if (!result.valid && !firstProblem) {
                firstProblem = { panel, input: result.firstInvalid };
            }
        }

        if (firstProblem) {
            event.preventDefault();
            activeIndex = Number(firstProblem.panel.dataset.index);
            showActiveStudent();
            focusInvalid(firstProblem.panel, firstProblem.input);
            alert('Please correct the highlighted fields in all student forms.');
            return;
        }

        isSubmitting = true;
        saveButton.disabled = true;
        saveButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Saving Students...';
    });

    // Restore old form data after a validation failure.
    if (Array.isArray(oldStudents) && oldStudents.length) {
        oldStudents.slice(0, maxStudents).forEach(function (student) {
            addStudent(student || {}, false);
        });
    }

    if (!getPanels().length) addStudent({}, false);

    activeIndex = Number(getPanels()[0].dataset.index);
    showActiveStudent();

    // Focus the student and tab associated with the first server validation error.
    const firstErrorKey = Object.keys(serverErrors || {}).find(
        key => key.startsWith('students.')
    );

    if (firstErrorKey) {
        const match = firstErrorKey.match(/^students\.(\d+)\.([^.]*)/);

        if (match) {
            const index = Number(match[1]);
            const fieldName = match[2];
            const panel = getPanel(index);

            if (panel) {
                activeIndex = index;
                showActiveStudent();

                const input = field(panel, fieldName);

                if (input) {
                    const section = input.closest('.tab-content-section');
                    if (section) activateTab(panel, section.dataset.section);

                    showFieldError(input, serverErrors[firstErrorKey][0]);
                    focusInvalid(panel, input);
                }
            }
        }
    }
});
</script>
@endsection
