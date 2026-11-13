@extends('layouts.app')

@section('title', 'Bulk Student Registration | Admin')

@section('content')
@php
    $defaultAcademicYear = date('Y') . '-' . (date('Y') + 1);
    $oldStudents = old('students', [[]]);
@endphp

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — BULK STUDENT REGISTRATION
    ========================================================= */

    .bulk-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .bulk-page *,
    .bulk-page *::before,
    .bulk-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .bulk-header {
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

    .bulk-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .bulk-breadcrumb {
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

    .bulk-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .bulk-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .bulk-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .bulk-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .bulk-title-icon {
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

    .bulk-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .bulk-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    /* ---------- BUTTONS ---------- */
    .btn-scheme-back,
    .btn-scheme-save,
    .btn-scheme-add,
    .btn-scheme-outline,
    .btn-scheme-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all .18s ease;
    }

    .btn-scheme-back {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .btn-scheme-back:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    .btn-scheme-save,
    .btn-scheme-add {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .btn-scheme-save:hover,
    .btn-scheme-add:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    .btn-scheme-outline {
        color: #34775a;
        background: #fffefa;
        border-color: #c5dcc8;
    }

    .btn-scheme-outline:hover {
        background: #eaf3e9;
        border-color: #83aa8a;
    }

    .btn-scheme-danger {
        color: #bd5148;
        background: #fffefa;
        border-color: #e0b8b2;
    }

    .btn-scheme-danger:hover {
        color: #a23d36;
        background: #fff1ed;
        border-color: #dba39a;
    }

    /* ---------- MAIN CARD ---------- */
    .registration-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        overflow: hidden;
    }

    /* ---------- TOOLBAR ---------- */
    .student-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        padding: 16px 22px;
        background: #faf8f0;
        border-bottom: 1px solid #e5e1d3;
    }

    .student-toolbar label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .student-selector {
        min-width: 260px;
        max-width: 100%;
        height: 40px;
        padding: 0 12px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .student-selector:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .student-count {
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

    .form-text-hint {
        margin-top: 5px;
        color: #7a857d;
        font-size: 10px;
    }

    /* ---------- TABS ---------- */
    .registration-tabs {
        display: flex;
        overflow-x: auto;
        background: #faf8f0;
        border-bottom: 1px solid #e5e1d3;
    }

    .registration-tab {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1 0 160px;
        min-width: 160px;
        padding: 14px 16px;
        border: 0;
        border-bottom: 3px solid transparent;
        background: transparent;
        text-align: left;
        color: #5c6b62;
        font-weight: 650;
        font-size: 11px;
        cursor: pointer;
        transition: color .18s ease, background .18s ease, border-color .18s ease;
    }

    .registration-tab:hover {
        color: #34775a;
        background: rgba(52, 119, 90, .05);
    }

    .registration-tab.active {
        background: #fffefa;
        border-bottom-color: #34775a;
        color: #245a43;
    }

    .tab-number {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #e9e5d9;
        color: #5c6b62;
        font-size: 11px;
        font-weight: 700;
    }

    .registration-tab.active .tab-number {
        background: #34775a;
        color: #fffefa;
    }

    .tab-label small {
        display: block;
        margin-top: 2px;
        color: #879087;
        font-size: 10px;
        font-weight: 400;
    }

    /* ---------- BODY ---------- */
    .registration-body {
        padding: 24px 22px;
    }

    .student-panel[hidden],
    .tab-content-section[hidden] {
        display: none !important;
    }

    .student-panel {
        border-bottom: 1px solid #e5e1d3;
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

    .heading-icon {
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
        margin: 0 0 3px;
        color: #263b35;
        font-size: 14px;
        font-weight: 750;
    }

    .section-heading p {
        margin: 0;
        color: #7a857d;
        font-size: 11px;
        line-height: 1.4;
    }

    /* ---------- FORM FIELDS ---------- */
    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .required-star {
        color: #bd5148;
    }

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
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .form-control:not(textarea),
    .form-select {
        height: 40px;
        padding: 0 12px;
    }

    textarea.form-control {
        min-height: 95px;
        padding: 10px 12px;
        resize: vertical;
        line-height: 1.5;
    }

    .form-control::placeholder {
        color: #a0a397;
        font-weight: 400;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #c5d1c2;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .form-control[readonly] {
        cursor: default;
        color: #69766d;
        background: #f5f3e9;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #bd5148;
        background: #fff8f5;
    }

    .invalid-feedback {
        display: block;
        margin-top: 4px;
        color: #bd5148;
        font-size: 10px;
    }

    /* ---------- PARENT CARDS ---------- */
    .parent-card {
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 16px;
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
    }

    .parent-card h6 {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e9e5d9;
        color: #263b35;
        font-size: 12px;
        font-weight: 750;
    }

    .parent-card h6 i {
        color: #34775a;
    }

    /* ---------- PHOTO BOX ---------- */
    .photo-box {
        border: 1px dashed #c5d1c2;
        border-radius: 12px;
        background: #faf8f0;
        padding: 16px;
        height: 100%;
    }

    .photo-preview {
        width: 100%;
        max-width: 180px;
        height: 160px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #d8e8d8;
        background: #fffefa;
        display: none;
        margin: 0 auto 12px;
        box-shadow: 0 3px 10px rgba(38, 59, 53, .1);
    }

    .photo-placeholder {
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #7a857d;
        gap: 8px;
        background: #f5f3e9;
        border-radius: 10px;
        border: 1px solid #e5e1d3;
        margin-bottom: 12px;
    }

    .photo-placeholder i {
        font-size: 36px;
        color: #34775a;
    }

    /* ---------- FOOTERS ---------- */
    .student-panel-footer,
    .main-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 22px;
        background: #faf8f0;
        border-top: 1px solid #e5e1d3;
    }

    .footer-note {
        color: #7a857d;
        font-size: 11px;
    }

    .footer-note .required-star {
        color: #bd5148;
    }

    /* ---------- ALERTS ---------- */
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

    .scheme-alert-warning {
        background: #f8eee0;
        border: 1px solid #d9b88f;
        border-left: 4px solid #a87543;
        color: #87582d;
        font-size: 11px;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 767.98px) {
        .bulk-header {
            padding: 18px 16px;
        }

        .bulk-title {
            font-size: 19px;
        }

        .bulk-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .bulk-header-content {
            flex-direction: column;
        }

        .registration-body {
            padding: 18px 15px;
        }

        .student-toolbar,
        .main-footer,
        .student-panel-footer {
            padding: 14px 15px;
        }

        .student-selector {
            width: 100%;
            min-width: 0;
        }

        .registration-tab {
            min-width: 140px;
            padding: 12px 12px;
            font-size: 10px;
        }

        .main-footer,
        .student-panel-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .main-footer .d-flex,
        .student-panel-footer .d-flex {
            width: 100%;
        }

        .btn-scheme-back,
        .btn-scheme-save,
        .btn-scheme-add,
        .btn-scheme-outline,
        .btn-scheme-danger {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bulk-page *,
        .bulk-page *::before,
        .bulk-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="bulk-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="bulk-header">
        <div class="bulk-header-content">
            <div>
                <div class="bulk-breadcrumb">
                    <a href="{{ route('admin.students.index') }}">Students</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Bulk Registration</span>
                </div>

                <div class="bulk-title-row">
                    <div class="bulk-title-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h1 class="bulk-title">Bulk Student Registration</h1>
                        <p class="bulk-subtitle">Register new admissions or update existing student records.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.students.index') }}" class="btn-scheme-back">
                <i class="bi bi-arrow-left"></i>
                Back to Students
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="scheme-alert scheme-alert-success">
            <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="scheme-alert scheme-alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="scheme-alert scheme-alert-danger">
            <strong class="d-block mb-2">Please correct the following errors:</strong>
            <ul class="mb-0 ps-3">
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
                    <label for="studentSelector">Select Student Form</label>
                    <select id="studentSelector" class="student-selector"></select>
                    <div class="form-text-hint">Switch between student forms to enter their details.</div>
                </div>

                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="student-count" id="studentCount">1 Student</span>
                    <button type="button" class="btn-scheme-add" id="addStudentBtn">
                        <i class="bi bi-plus-circle"></i>
                        Add Student
                    </button>
                </div>
            </div>

            <div id="studentPanels"></div>

            <div class="main-footer">
                <div class="footer-note">
                    <i class="bi bi-info-circle me-1"></i>
                    Required fields are marked with <span class="required-star">*</span>.
                    Maximum 100 students per submission.
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.students.index') }}" class="btn-scheme-back">Cancel</a>
                    <button type="submit" class="btn-scheme-save" id="saveAllBtn">
                        <i class="bi bi-check2-circle"></i>
                        Save All Students
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
                            <div class="form-text-hint mt-2">JPG, PNG, WEBP. Maximum 2 MB.</div>
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
                                <div class="form-text-hint">Can be entered manually.</div>
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
                    <h6><i class="bi bi-person"></i> Father's Information</h6>
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
                    <h6><i class="bi bi-person"></i> Mother's Information</h6>
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
                    <h6><i class="bi bi-person-badge"></i> Guardian Information</h6>
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

        {{-- ADDRESS --}}
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
                        <div class="scheme-alert scheme-alert-warning py-2 mb-0"
                             data-location-error hidden>
                            Unable to load locations. Check your location routes and API response.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="student-panel-footer">
            <button type="button" class="btn-scheme-danger" data-action="remove-student">
                <i class="bi bi-trash"></i> Remove Student
            </button>

            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn-scheme-outline" data-action="previous-tab">
                    <i class="bi bi-arrow-left"></i> Previous
                </button>
                <button type="button" class="btn-scheme-add" data-action="next-tab">
                    Next <i class="bi bi-arrow-right"></i>
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
        return panelsContainer.querySelector('.student-panel[data-index="' + index + '"]');
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

        if (!response.ok) throw new Error('Location request failed: HTTP ' + response.status);

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
            if (value === undefined || value === null || label === undefined || label === null) return;

            const option = document.createElement('option');
            option.value = String(value);
            option.textContent = String(label);
            option.dataset.locationId = String(item.id ?? value);
            option.dataset.pincode = String(item.pin_code ?? item.pincode ?? item.pinCode ?? '');
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
                const districts = await fetchLocationList(locationUrls.districts, 'state_id', stateId);
                fillLocationSelect(districtSelect, districts, 'district_name', 'district_name', 'Select District');
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
                const talukas = await fetchLocationList(locationUrls.talukas, 'district_id', districtId);
                fillLocationSelect(talukaSelect, talukas, 'id', 'tehsil_name', 'Select Taluka');
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
                const cities = await fetchLocationList(locationUrls.cities, 'tehsil_id', talukaId);
                fillLocationSelect(citySelect, cities, 'id', 'location_name', 'Select City / Village');
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
        ['aadhar_card_no', 'phone', 'father_phone', 'mother_phone', 'guardian_phone', 'pincode'].forEach(function (name) {
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
                    const limit = name === 'aadhar_card_no' ? 12 : name === 'pincode' ? 6 : 10;
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

            const name = [firstName.value.trim(), middleName.value.trim(), lastName.value.trim()]
                .filter(Boolean).join(' ');
            if (!name) return;

            const currentRequest = ++requestNumber;

            try {
                const url = 'https://inputtools.google.com/request?text=' +
                    encodeURIComponent(name) + '&itc=mr-t-i0-und&num=1';
                const response = await fetch(url);
                const data = await response.json();

                if (currentRequest === requestNumber && !manuallyEdited && !marathiName.value.trim() &&
                    data[0] === 'SUCCESS' && data[1]?.[0]?.[1]?.[0]) {
                    marathiName.value = data[1][0][1][0];
                }
            } catch (error) {
                // Manual entry is fine if transliteration fails.
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

        const oldState = values.state || '';
        if (oldState) {
            const stateSelect = field(newPanel, 'state');
            const stateOption = Array.from(stateSelect.options).find(option => option.value === oldState);
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

        const requiredNames = ['first_name', 'last_name', 'gender', 'academic_year', 'admission_class', 'section'];
        const parentPhoneNames = ['father_phone', 'mother_phone', 'guardian_phone'];

        panel.querySelectorAll('[data-name]').forEach(function (input) {
            if (!allFields && input.closest('.tab-content-section')?.hidden) return;

            clearFieldError(input);
            const name = input.dataset.name;
            const value = (input.value || '').trim();
            let message = '';

            if (requiredNames.includes(name) && !value) message = 'This field is required.';
            if (value && name === 'aadhar_card_no' && !/^\d{12}$/.test(value)) message = 'Aadhaar must contain exactly 12 digits.';
            if (value && name === 'phone' && !/^(?:\+91[\s-]?)?[6-9]\d{9}$/.test(value)) message = 'Enter a valid 10-digit mobile number, optionally with +91.';
            if (value && parentPhoneNames.includes(name) && !/^[6-9]\d{9}$/.test(value)) message = 'Enter a valid 10-digit mobile number.';
            if (value && name === 'pincode' && !/^\d{6}$/.test(value)) message = 'Pincode must contain exactly 6 digits.';
            if (value && name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) message = 'Enter a valid email address.';

            if (value && ['date_of_birth', 'admission_date'].includes(name)) {
                const date = new Date(value + 'T00:00:00');
                if (Number.isNaN(date.getTime())) message = 'Enter a valid date.';
                else if (name === 'date_of_birth' && date > new Date()) message = 'Date of birth cannot be in the future.';
            }

            if (input.type === 'file' && input.files?.[0]) {
                const file = input.files[0];
                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) message = 'Choose a JPG, PNG, or WEBP image.';
                else if (file.size > 2 * 1024 * 1024) message = 'Photo size must not exceed 2 MB.';
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
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving Students...';
    });

    if (Array.isArray(oldStudents) && oldStudents.length) {
        oldStudents.slice(0, maxStudents).forEach(function (student) {
            addStudent(student || {}, false);
        });
    }

    if (!getPanels().length) addStudent({}, false);

    activeIndex = Number(getPanels()[0].dataset.index);
    showActiveStudent();

    const firstErrorKey = Object.keys(serverErrors || {}).find(key => key.startsWith('students.'));
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