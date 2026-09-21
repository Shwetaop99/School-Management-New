@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')

<style>
    .student-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }

    .student-card {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
        background: #fff;
    }

    .student-header {
        padding: 22px 26px;
        background: linear-gradient(135deg, #1677f0, #125dcc);
        color: #fff;
    }

    .student-header h4 {
        margin: 0;
        font-weight: 700;
    }

    .student-header p {
        margin: 4px 0 0;
        opacity: .85;
    }

    .student-body {
        padding: 26px;
    }

    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 25px;
    }

    .nav-tabs .nav-link {
        border: 0;
        color: #6c757d;
        font-weight: 600;
        padding: 12px 18px;
    }

    .nav-tabs .nav-link.active {
        color: #1677f0;
        border-bottom: 3px solid #1677f0;
        background: transparent;
    }

    .tab-content-section {
        display: none;
    }

    .tab-content-section.active {
        display: block;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 22px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .section-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eaf3ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .section-heading h5 {
        margin: 0;
        font-weight: 700;
    }

    .section-heading p {
        margin: 3px 0 0;
        color: #6c757d;
        font-size: 13px;
    }

    .form-label {
        font-weight: 600;
        color: #344054;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border-radius: 8px;
        border-color: #d9dee7;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1677f0;
        box-shadow: 0 0 0 .2rem rgba(22, 119, 240, .12);
    }

    textarea.form-control {
        min-height: 100px;
    }

    .required {
        color: #dc3545;
    }

    .student-footer {
        border-top: 1px solid #eee;
        padding: 18px 26px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .profile-preview {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #ddd;
    }

    .field-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .loading-icon {
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @media(max-width: 768px) {
        .student-page {
            padding: 12px;
        }

        .student-body {
            padding: 16px;
        }

        .nav-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        .nav-tabs .nav-link {
            white-space: nowrap;
        }
    }
</style>

<div class="student-page">

    <div class="student-card">

        {{-- =====================================================
            HEADER
        ====================================================== --}}
        <div class="student-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4>
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Student
                    </h4>

                    <p>
                        Update student information
                    </p>
                </div>

                <span class="badge bg-light text-primary px-3 py-2">
                    {{ $student->student_id }}
                </span>
            </div>
        </div>


        {{-- =====================================================
            BODY
        ====================================================== --}}
        <div class="student-body">

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                </div>
            @endif


            {{-- =================================================
                TABS
            ================================================== --}}
            <ul class="nav nav-tabs" id="studentTabs">

                <li class="nav-item">
                    <button type="button"
                            class="nav-link active"
                            data-tab="basic">
                        <i class="bi bi-person me-1"></i>
                        Basic Information
                    </button>
                </li>

                <li class="nav-item">
                    <button type="button"
                            class="nav-link"
                            data-tab="academic">
                        <i class="bi bi-mortarboard me-1"></i>
                        Academic Information
                    </button>
                </li>

                <li class="nav-item">
                    <button type="button"
                            class="nav-link"
                            data-tab="parents">
                        <i class="bi bi-people me-1"></i>
                        Parents Data
                    </button>
                </li>

                <li class="nav-item">
                    <button type="button"
                            class="nav-link"
                            data-tab="previous">
                        <i class="bi bi-building me-1"></i>
                        Previous School
                    </button>
                </li>

                <li class="nav-item">
                    <button type="button"
                            class="nav-link"
                            data-tab="address">
                        <i class="bi bi-geo-alt me-1"></i>
                        Address
                    </button>
                </li>

            </ul>


            {{-- =================================================
                FORM
            ================================================== --}}
            <form action="{{ route('admin.students.update', $student->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="studentForm">

                @csrf
                @method('PUT')


                {{-- =================================================
                    TAB 1 : BASIC INFORMATION
                ================================================== --}}
                <div class="tab-content-section active"
                     id="tab-basic">

                    <div class="section-heading">

                        <div class="section-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div>
                            <h5>Basic Information</h5>
                            <p>
                                Enter student's personal information.
                            </p>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- FIRST NAME --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                First Name
                                <span class="required">*</span>
                            </label>

                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   class="form-control"
                                   value="{{ old('first_name', $student->first_name) }}"
                                   required>
                        </div>


                        {{-- MIDDLE NAME --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Middle Name
                            </label>

                            <input type="text"
                                   name="middle_name"
                                   id="middle_name"
                                   class="form-control"
                                   value="{{ old('middle_name', $student->middle_name) }}">
                        </div>


                        {{-- LAST NAME --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Last Name
                                <span class="required">*</span>
                            </label>

                            <input type="text"
                                   name="last_name"
                                   id="last_name"
                                   class="form-control"
                                   value="{{ old('last_name', $student->last_name) }}"
                                   required>
                        </div>


                        {{-- =================================================
                            MARATHI NAME
                            DATABASE COLUMN: marathi_name
                        ================================================== --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Marathi Name
                            </label>

                            <input type="text"
                                   name="marathi_name"
                                   id="marathi_name"
                                   class="form-control"
                                   value="{{ old('marathi_name', $student->marathi_name ?? '') }}"
                                   placeholder="Enter student's Marathi name">
                        </div>


                        {{-- GENDER --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Gender
                                <span class="required">*</span>
                            </label>

                            <select name="gender"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Gender
                                </option>

                                <option value="Male"
                                    {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>
                                    Male
                                </option>

                                <option value="Female"
                                    {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>
                                    Female
                                </option>

                                <option value="Other"
                                    {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>
                        </div>


                        {{-- DOB --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Date of Birth
                                <span class="required">*</span>
                            </label>

                            <input type="date"
                                   name="date_of_birth"
                                   class="form-control"
                                   value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}"
                                   required>
                        </div>


                        {{-- BIRTH PLACE --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Birth Place
                            </label>

                            <input type="text"
                                   name="birth_place"
                                   class="form-control"
                                   value="{{ old('birth_place', $student->birth_place ?? '') }}">
                        </div>


                        {{-- AADHAAR --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Aadhaar Number
                            </label>

                            <input type="text"
                                   name="aadhar_card_no"
                                   id="aadhar_card_no"
                                   class="form-control"
                                   maxlength="12"
                                   inputmode="numeric"
                                   value="{{ old('aadhar_card_no', $student->aadhar_card_no) }}">

                            <div id="aadharError"
                                 class="field-error"></div>
                        </div>


                        {{-- PHONE --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Phone
                            </label>

                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control"
                                   maxlength="10"
                                   inputmode="numeric"
                                   value="{{ old('phone', $student->phone) }}">

                            <div id="phoneError"
                                 class="field-error"></div>
                        </div>


                        {{-- EMAIL --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email', $student->email) }}">
                        </div>


                        {{-- PROFILE IMAGE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Profile Image
                            </label>

                            <input type="file"
                                   name="profile_image"
                                   id="profile_image"
                                   class="form-control"
                                   accept="image/*">

                            <div class="mt-2">

                                @if($student->profile_image)

                                    <img src="{{ $student->profile_image }}"
                                         id="imagePreview"
                                         class="profile-preview"
                                         alt="Student">

                                @else

                                    <img src=""
                                         id="imagePreview"
                                         class="profile-preview d-none"
                                         alt="Preview">

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TAB 2 : ACADEMIC INFORMATION
                ================================================== --}}
                <div class="tab-content-section"
                     id="tab-academic">

                    <div class="section-heading">

                        <div class="section-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>

                        <div>
                            <h5>Academic Information</h5>
                            <p>
                                Manage academic and admission information.
                            </p>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- ACADEMIC YEAR --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Academic Year
                            </label>

                            <input type="text"
                                   name="academic_year"
                                   id="academic_year"
                                   class="form-control"
                                   value="{{ old('academic_year', $student->academic_year) }}">
                        </div>


                        {{-- CLASS --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Class
                            </label>

                            <select name="class"
                                    id="class"
                                    class="form-select">

                                @foreach(['Nursery','LKG','UKG','1','2','3','4','5','6','7','8','9','10','11','12'] as $class)

                                    <option value="{{ $class }}"
                                        {{ old('class', $student->class) == $class ? 'selected' : '' }}>
                                        {{ $class }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- SECTION --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Section
                            </label>

                            <select name="section"
                                    id="section"
                                    class="form-select">

                                @foreach(['A','B','C','D','E','F'] as $section)

                                    <option value="{{ $section }}"
                                        {{ old('section', $student->section) == $section ? 'selected' : '' }}>
                                        {{ $section }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- ROLL NUMBER --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Roll Number
                            </label>

                            <input type="text"
                                   name="roll_number"
                                   id="roll_number"
                                   class="form-control"
                                   value="{{ old('roll_number', $student->roll_number) }}"
                                   readonly>
                        </div>


                        {{-- ADMISSION CLASS --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Admission Class
                            </label>

                            <select name="admission_class"
                                    class="form-select">

                                @foreach(['Nursery','LKG','UKG','1','2','3','4','5','6','7','8','9','10','11','12'] as $class)

                                    <option value="{{ $class }}"
                                        {{ old('admission_class', $student->admission_class) == $class ? 'selected' : '' }}>
                                        {{ $class }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- ADMISSION DATE --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Admission Date
                            </label>

                            <input type="date"
                                   name="admission_date"
                                   class="form-control"
                                   value="{{ old('admission_date', optional($student->admission_date)->format('Y-m-d')) }}">
                        </div>


                        {{-- REGISTER NO --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Register Number
                            </label>

                            <input type="text"
                                   name="register_no"
                                   class="form-control"
                                   value="{{ old('register_no', $student->register_no) }}"
                                   readonly>
                        </div>


                        {{-- BOOK NO --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Book Number
                            </label>

                            <input type="text"
                                   name="book_no"
                                   class="form-control"
                                   value="{{ old('book_no', $student->book_no) }}"
                                   readonly>
                        </div>


                        {{-- APAAR --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                APAAR ID
                            </label>

                            <input type="text"
                                   name="appar_id"
                                   class="form-control"
                                   value="{{ old('appar_id', $student->appar_id) }}"
                                   readonly>
                        </div>


                        {{-- PEN --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                PEN Number
                            </label>

                            <input type="text"
                                   name="pen_no"
                                   class="form-control"
                                   value="{{ old('pen_no', $student->pen_no) }}"
                                   readonly>
                        </div>


                        {{-- MEDIUM --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Medium
                            </label>

                            <select name="medium"
                                    class="form-select">

                                @foreach(['Marathi','English','Hindi'] as $medium)

                                    <option value="{{ $medium }}"
                                        {{ old('medium', $student->medium) == $medium ? 'selected' : '' }}>
                                        {{ $medium }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- MOTHER TONGUE --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Mother Tongue
                            </label>

                            <input type="text"
                                   name="mother_tongue"
                                   class="form-control"
                                   value="{{ old('mother_tongue', $student->mother_tongue) }}">
                        </div>


                        {{-- NATIONALITY --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Nationality
                            </label>

                            <input type="text"
                                   name="nationality"
                                   class="form-control"
                                   value="{{ old('nationality', $student->nationality ?? 'Indian') }}">
                        </div>


                        {{-- RELIGION --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Religion
                            </label>

                            <input type="text"
                                   name="religion"
                                   class="form-control"
                                   value="{{ old('religion', $student->religion) }}">
                        </div>


                        {{-- CASTE --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Caste
                            </label>

                            <input type="text"
                                   name="caste"
                                   class="form-control"
                                   value="{{ old('caste', $student->caste) }}">
                        </div>


                        {{-- SUB CASTE --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Sub Caste
                            </label>

                            <input type="text"
                                   name="sub_caste"
                                   class="form-control"
                                   value="{{ old('sub_caste', $student->sub_caste) }}">
                        </div>


                        {{-- STATUS --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="active"
                                    {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="inactive"
                                    {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TAB 3 : PARENTS
                ================================================== --}}
                <div class="tab-content-section"
                     id="tab-parents">

                    <div class="section-heading">

                        <div class="section-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <div>
                            <h5>Parents Data</h5>
                            <p>
                                Enter father, mother and guardian details.
                            </p>
                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Father Name</label>

                            <input type="text"
                                   name="father_name"
                                   class="form-control"
                                   value="{{ old('father_name', $student->father_name) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Father Phone</label>

                            <input type="text"
                                   name="father_phone"
                                   class="form-control parent-phone"
                                   maxlength="10"
                                   inputmode="numeric"
                                   value="{{ old('father_phone', $student->father_phone) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Father Occupation</label>

                            <input type="text"
                                   name="father_occupation"
                                   class="form-control"
                                   value="{{ old('father_occupation', $student->father_occupation) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Mother Name</label>

                            <input type="text"
                                   name="mother_name"
                                   class="form-control"
                                   value="{{ old('mother_name', $student->mother_name) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mother Phone</label>

                            <input type="text"
                                   name="mother_phone"
                                   class="form-control parent-phone"
                                   maxlength="10"
                                   inputmode="numeric"
                                   value="{{ old('mother_phone', $student->mother_phone) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mother Occupation</label>

                            <input type="text"
                                   name="mother_occupation"
                                   class="form-control"
                                   value="{{ old('mother_occupation', $student->mother_occupation) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Guardian Name</label>

                            <input type="text"
                                   name="guardian_name"
                                   class="form-control"
                                   value="{{ old('guardian_name', $student->guardian_name) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Guardian Relation</label>

                            <input type="text"
                                   name="guardian_relation"
                                   class="form-control"
                                   value="{{ old('guardian_relation', $student->guardian_relation) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Guardian Phone</label>

                            <input type="text"
                                   name="guardian_phone"
                                   class="form-control parent-phone"
                                   maxlength="10"
                                   inputmode="numeric"
                                   value="{{ old('guardian_phone', $student->guardian_phone) }}">
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TAB 4 : PREVIOUS SCHOOL
                ================================================== --}}
                <div class="tab-content-section"
                     id="tab-previous">

                    <div class="section-heading">

                        <div class="section-icon">
                            <i class="bi bi-building-fill"></i>
                        </div>

                        <div>
                            <h5>Previous School</h5>
                            <p>
                                Enter previous school information.
                            </p>
                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Previous School Name
                            </label>

                            <input type="text"
                                   name="previous_school_name"
                                   class="form-control"
                                   value="{{ old('previous_school_name', $student->previous_school_name) }}">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">
                                Previous School Class
                            </label>

                            <input type="text"
                                   name="previous_school_class"
                                   class="form-control"
                                   value="{{ old('previous_school_class', $student->previous_school_class) }}">
                        </div>


                        <div class="col-md-12">
                            <label class="form-label">
                                Previous School Address
                            </label>

                            <textarea name="previous_school_address"
                                      class="form-control">{{ old('previous_school_address', $student->previous_school_address) }}</textarea>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                Medium
                            </label>

                            <input type="text"
                                   name="previous_school_medium"
                                   class="form-control"
                                   value="{{ old('previous_school_medium', $student->previous_school_medium) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                Board
                            </label>

                            <input type="text"
                                   name="previous_school_board"
                                   class="form-control"
                                   value="{{ old('previous_school_board', $student->previous_school_board) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                Result
                            </label>

                            <input type="text"
                                   name="previous_school_result"
                                   class="form-control"
                                   value="{{ old('previous_school_result', $student->previous_school_result) }}">
                        </div>


                        <div class="col-md-12">
                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="previous_school_remarks"
                                      class="form-control">{{ old('previous_school_remarks', $student->previous_school_remarks) }}</textarea>
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TAB 5 : ADDRESS
                ================================================== --}}
                <div class="tab-content-section"
                     id="tab-address">

                    <div class="section-heading">

                        <div class="section-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>
                            <h5>Address Information</h5>

                            <p>
                                Select state, district, taluka and location.
                                District, taluka and location are loaded dynamically.
                            </p>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- COUNTRY --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Country
                            </label>

                            <select name="country"
                                    id="country"
                                    class="form-select">

                                <option value="India"
                                    {{ old('country', $student->country ?? 'India') == 'India' ? 'selected' : '' }}>
                                    India
                                </option>

                            </select>

                        </div>


                        {{-- STATE --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                State
                            </label>

                            <select name="state_id"
                                    id="state_id"
                                    class="form-select">

                                <option value="1"
                                    {{ old('state_id', 1) == 1 ? 'selected' : '' }}>
                                    Maharashtra
                                </option>

                            </select>

                            {{-- DATABASE STORES STATE NAME --}}
                            <input type="hidden"
                                   name="state"
                                   id="state"
                                   value="{{ old('state', $student->state ?? 'Maharashtra') }}">

                        </div>


                        {{-- DISTRICT --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                District
                            </label>

                            <select name="district_id"
                                    id="district_id"
                                    class="form-select">

                                <option value="">
                                    Loading districts...
                                </option>

                            </select>

                            {{-- Database stores district NAME --}}
                            <input type="hidden"
                                   name="district"
                                   id="district"
                                   value="{{ old('district', $student->district ?? '') }}">

                            <div id="districtLoading"
                                 class="small text-muted mt-1 d-none">
                                Loading districts...
                            </div>

                            <div id="districtError"
                                 class="small text-danger mt-1 d-none">
                            </div>

                        </div>


                        {{-- TALUKA --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Taluka / Tehsil
                            </label>

                            <select name="taluka_id"
                                    id="taluka_id"
                                    class="form-select">

                                <option value="">
                                    Select district first
                                </option>

                            </select>

                            {{-- Database stores taluka NAME --}}
                            <input type="hidden"
                                   name="taluka"
                                   id="taluka"
                                   value="{{ old('taluka', $student->taluka ?? '') }}">

                            <div id="talukaLoading"
                                 class="small text-muted mt-1 d-none">
                                Loading talukas...
                            </div>

                            <div id="talukaError"
                                 class="small text-danger mt-1 d-none">
                            </div>

                        </div>


                        {{-- CITY / VILLAGE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                City / Village
                            </label>

                            <select name="city_village"
                                    id="city_village"
                                    class="form-select">

                                <option value="">
                                    Select taluka first
                                </option>

                            </select>

                            <div id="locationLoading"
                                 class="small text-muted mt-1 d-none">
                                Loading locations...
                            </div>

                            <div id="locationError"
                                 class="small text-danger mt-1 d-none">
                            </div>

                        </div>


                        {{-- PINCODE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Pincode
                            </label>

                            <input type="text"
                                   name="pincode"
                                   id="pincode"
                                   class="form-control"
                                   value="{{ old('pincode', $student->pincode ?? '') }}"
                                   readonly>

                        </div>


                        {{-- ADDRESS --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                Full Address
                            </label>

                            <textarea name="address"
                                      id="address"
                                      class="form-control">{{ old('address', $student->address) }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    FOOTER
                ================================================== --}}
                <div class="student-footer mt-4">

                    <a href="{{ route('admin.students.show', $student->id) }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4">

                        <i class="bi bi-check-circle me-1"></i>
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

    const tabButtons =
        document.querySelectorAll('#studentTabs .nav-link');

    const tabSections =
        document.querySelectorAll('.tab-content-section');

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

            const targetSection =
                document.getElementById('tab-' + target);

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

            this.value =
                this.value.replace(/\D/g, '');

        });

    });


    /* =========================================================
       AADHAAR VALIDATION
    ========================================================== */

    const aadhar =
        document.getElementById('aadhar_card_no');

    const aadharError =
        document.getElementById('aadharError');

    if (aadhar) {

        aadhar.addEventListener('input', function () {

            const value = this.value.trim();

            if (
                value !== '' &&
                !/^\d{12}$/.test(value)
            ) {

                if (aadharError) {
                    aadharError.textContent =
                        'Aadhaar number must contain exactly 12 digits.';
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

    const phone =
        document.getElementById('phone');

    const phoneError =
        document.getElementById('phoneError');

    if (phone) {

        phone.addEventListener('input', function () {

            const value = this.value.trim();

            if (
                value !== '' &&
                !/^[6-9]\d{9}$/.test(value)
            ) {

                if (phoneError) {

                    phoneError.textContent =
                        'Enter a valid 10 digit Indian mobile number.';

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

    const profileImage =
        document.getElementById('profile_image');

    const imagePreview =
        document.getElementById('imagePreview');

    if (profileImage) {

        profileImage.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {

                this.value = '';

                alert(
                    'Please select a valid image file.'
                );

                return;
            }

            const reader =
                new FileReader();

            reader.onload =
                function (event) {

                    if (imagePreview) {

                        imagePreview.src =
                            event.target.result;

                        imagePreview.classList.remove(
                            'd-none'
                        );

                    }

                };

            reader.readAsDataURL(file);

        });

    }


    /* =========================================================
       LOCATION FIELDS
    ========================================================== */

    const stateField =
        document.getElementById('state_id');

    const districtField =
        document.getElementById('district_id');

    const talukaField =
        document.getElementById('taluka_id');

    const villageField =
        document.getElementById('city_village');

    const pincodeField =
        document.getElementById('pincode');


    /* =========================================================
       HIDDEN DATABASE TEXT FIELDS
    ========================================================== */

    const stateHidden =
        document.getElementById('state');

    const districtHidden =
        document.getElementById('district');

    const talukaHidden =
        document.getElementById('taluka');


    /* =========================================================
       LOADING / ERROR ELEMENTS
    ========================================================== */

    const districtLoading =
        document.getElementById('districtLoading');

    const districtError =
        document.getElementById('districtError');

    const talukaLoading =
        document.getElementById('talukaLoading');

    const talukaError =
        document.getElementById('talukaError');

    const locationLoading =
        document.getElementById('locationLoading');

    const locationError =
        document.getElementById('locationError');


    /* =========================================================
       CORRECT LARAVEL ROUTES
    ========================================================== */

    const LOCATION_URLS = {
        states: "{{ route('admin.locations.states') }}",
        districts: "{{ route('admin.locations.districts') }}",
        tehsils: "{{ route('admin.locations.tehsils') }}",
        locations: "{{ route('admin.locations.locations') }}"
    };


    /* =========================================================
       CURRENT DATABASE VALUES
    ========================================================== */

    const currentState =
        @json(old('state', $student->state ?? 'Maharashtra'));

    const currentDistrict =
        @json(old('district', $student->district ?? ''));

    const currentTaluka =
        @json(old('taluka', $student->taluka ?? ''));

    const currentVillage =
        @json(old('city_village', $student->city_village ?? ''));

    const currentPincode =
        @json(old('pincode', $student->pincode ?? ''));


    /* =========================================================
       FETCH JSON
    ========================================================== */

    async function fetchJson(url, type = 'API') {

        console.log('----------------------------------------');
        console.log(type + ' REQUEST:', url);

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        const text =
            await response.text();

        console.log(
            type + ' HTTP:',
            response.status
        );

        console.log(
            type + ' RESPONSE:',
            text
        );

        if (!response.ok) {

            let message =
                type +
                ' API request failed: HTTP ' +
                response.status;

            if (response.status === 404) {

                message +=
                    '. Laravel route was not found.';

            } else if (response.status === 401) {

                message +=
                    '. Authentication failed.';

            } else if (response.status === 403) {

                message +=
                    '. Access denied.';

            } else if (response.status >= 500) {

                message +=
                    '. Laravel/API server error.';

            }

            throw new Error(message);
        }

        if (!text.trim()) {

            throw new Error(
                type +
                ' API returned empty response.'
            );

        }

        try {

            return JSON.parse(text);

        } catch (error) {

            console.error(
                'Invalid JSON:',
                text
            );

            throw new Error(
                type +
                ' API returned invalid JSON.'
            );

        }

    }


    /* =========================================================
       EXTRACT ARRAY
    ========================================================== */

    function extractArray(
        response,
        possibleKeys = []
    ) {

        if (Array.isArray(response)) {
            return response;
        }

        if (
            response &&
            Array.isArray(response.data)
        ) {

            return response.data;

        }

        if (
            response &&
            response.data &&
            typeof response.data === 'object'
        ) {

            for (const key of possibleKeys) {

                if (
                    Array.isArray(
                        response.data[key]
                    )
                ) {

                    return response.data[key];

                }

            }

            if (
                Array.isArray(
                    response.data.data
                )
            ) {

                return response.data.data;

            }

        }

        if (response) {

            for (const key of possibleKeys) {

                if (
                    Array.isArray(
                        response[key]
                    )
                ) {

                    return response[key];

                }

            }

        }

        if (
            response &&
            Array.isArray(response.results)
        ) {

            return response.results;

        }

        if (
            response &&
            Array.isArray(response.items)
        ) {

            return response.items;

        }

        return [];
    }


    /* =========================================================
       GET ID
    ========================================================== */

    function getId(item, type) {

        if (!item) {
            return '';
        }

        let keys = [];

        if (type === 'state') {

            keys = [
                'id',
                'state_id',
                'state_code',
                'code'
            ];

        } else if (type === 'district') {

            keys = [
                'id',
                'district_id',
                'district_code',
                'code'
            ];

        } else if (type === 'taluka') {

            keys = [
                'id',
                'tehsil_id',
                'taluka_id',
                'tehsil_code',
                'taluka_code',
                'code'
            ];

        } else if (type === 'location') {

            keys = [
                'id',
                'location_id',
                'village_id',
                'location_code',
                'village_code',
                'code'
            ];

        }

        for (const key of keys) {

            if (
                item[key] !== undefined &&
                item[key] !== null &&
                item[key] !== ''
            ) {

                return item[key];

            }

        }

        return '';
    }


    /* =========================================================
       GET NAME
    ========================================================== */

    function getName(item, type) {

        if (!item) {
            return '';
        }

        let keys = [];

        if (type === 'state') {

            keys = [
                'name',
                'state_name',
                'state',
                'title'
            ];

        } else if (type === 'district') {

            keys = [
                'name',
                'district_name',
                'district',
                'title'
            ];

        } else if (type === 'taluka') {

            keys = [
                'name',
                'tehsil_name',
                'taluka_name',
                'taluka',
                'tehsil',
                'title'
            ];

        } else if (type === 'location') {

            keys = [
                'name',
                'location_name',
                'village_name',
                'village',
                'location',
                'title'
            ];

        }

        for (const key of keys) {

            if (
                item[key] !== undefined &&
                item[key] !== null &&
                String(item[key]).trim() !== ''
            ) {

                return String(
                    item[key]
                ).trim();

            }

        }

        return '';
    }


    /* =========================================================
       NORMALIZE
    ========================================================== */

    function normalize(value) {

        return String(value ?? '')
            .trim()
            .toLowerCase();

    }


    /* =========================================================
       CLEAR SELECT
    ========================================================== */

    function clearSelect(
        select,
        placeholder
    ) {

        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';

        option.textContent =
            placeholder;

        select.appendChild(option);

    }


    /* =========================================================
       ADD OPTION
    ========================================================== */

    function addOption(
        select,
        value,
        text,
        selected = false
    ) {

        if (!select) {
            return;
        }

        const option =
            document.createElement('option');

        option.value =
            value;

        option.textContent =
            text;

        option.selected =
            selected;

        select.appendChild(option);

    }


    /* =========================================================
       SAVE STATE NAME
    ========================================================== */

    function saveStateName() {

        if (
            !stateField ||
            !stateHidden
        ) {
            return;
        }

        const option =
            stateField.options[
                stateField.selectedIndex
            ];

        if (
            option &&
            option.value &&
            option.textContent.trim()
        ) {

            stateHidden.value =
                option.textContent.trim();

        } else {

            stateHidden.value =
                currentState || 'Maharashtra';

        }

        console.log(
            'STATE NAME TO SAVE:',
            stateHidden.value
        );

    }


    /* =========================================================
       SAVE DISTRICT NAME
    ========================================================== */

    function saveDistrictName() {

        if (
            !districtField ||
            !districtHidden
        ) {
            return;
        }

        const option =
            districtField.options[
                districtField.selectedIndex
            ];

        if (
            option &&
            option.value &&
            option.textContent.trim()
        ) {

            districtHidden.value =
                option.textContent.trim();

        } else {

            districtHidden.value =
                currentDistrict || '';

        }

        console.log(
            'DISTRICT NAME TO SAVE:',
            districtHidden.value
        );

    }


    /* =========================================================
       SAVE TALUKA NAME
    ========================================================== */

    function saveTalukaName() {

        if (
            !talukaField ||
            !talukaHidden
        ) {
            return;
        }

        const option =
            talukaField.options[
                talukaField.selectedIndex
            ];

        if (
            option &&
            option.value &&
            option.textContent.trim()
        ) {

            talukaHidden.value =
                option.textContent.trim();

        } else {

            talukaHidden.value =
                currentTaluka || '';

        }

        console.log(
            'TALUKA NAME TO SAVE:',
            talukaHidden.value
        );

    }


    /* =========================================================
       LOAD DISTRICTS
    ========================================================== */

    async function loadDistricts() {

        try {

            districtField.disabled = true;
            talukaField.disabled = true;
            villageField.disabled = true;

            clearSelect(
                districtField,
                'Loading districts...'
            );

            clearSelect(
                talukaField,
                'Select district first'
            );

            clearSelect(
                villageField,
                'Select Taluka first'
            );

            if (districtLoading) {

                districtLoading.classList.remove(
                    'd-none'
                );

            }

            if (districtError) {

                districtError.classList.add(
                    'd-none'
                );

                districtError.textContent = '';

            }


            let stateId =
                stateField
                    ? stateField.value
                    : '1';

            if (!stateId) {
                stateId = '1';
            }


            const districtUrl =
                new URL(
                    LOCATION_URLS.districts,
                    window.location.origin
                );

            districtUrl.searchParams.set(
                'state_id',
                stateId
            );


            console.log(
                'DISTRICT URL:',
                districtUrl.toString()
            );


            const districtResponse =
                await fetchJson(
                    districtUrl.toString(),
                    'District'
                );


            const districts =
                extractArray(
                    districtResponse,
                    [
                        'districts',
                        'data',
                        'results',
                        'items'
                    ]
                );


            console.log(
                'DISTRICTS:',
                districts
            );


            clearSelect(
                districtField,
                'Select District'
            );


            if (!districts.length) {

                throw new Error(
                    'No districts found.'
                );

            }


            let selectedDistrictItem =
                null;


            districts.forEach(
                function (district) {

                    const id =
                        getId(
                            district,
                            'district'
                        );

                    const name =
                        getName(
                            district,
                            'district'
                        );


                    if (
                        !id ||
                        !name
                    ) {
                        return;
                    }


                    const selected =
                        normalize(name) ===
                        normalize(
                            currentDistrict
                        );


                    addOption(
                        districtField,
                        id,
                        name,
                        selected
                    );


                    if (selected) {

                        selectedDistrictItem =
                            district;

                    }

                }
            );


            if (
                currentDistrict &&
                !selectedDistrictItem
            ) {

                addOption(
                    districtField,
                    currentDistrict,
                    currentDistrict,
                    true
                );

            }


            districtField.disabled = false;


            saveDistrictName();


            if (selectedDistrictItem) {

                await loadTalukas(
                    getId(
                        selectedDistrictItem,
                        'district'
                    ),
                    currentTaluka
                );

            } else if (currentDistrict) {

                await loadTalukas(
                    currentDistrict,
                    currentTaluka
                );

            }


        } catch (error) {

            console.error(
                'District API error:',
                error
            );


            clearSelect(
                districtField,
                'Unable to load districts'
            );

            districtField.disabled = true;


            if (districtError) {

                districtError.textContent =
                    error.message ||
                    'Unable to load districts.';

                districtError.classList.remove(
                    'd-none'
                );

            }

        } finally {

            if (districtLoading) {

                districtLoading.classList.add(
                    'd-none'
                );

            }

        }

    }


    /* =========================================================
       LOAD TALUKAS
    ========================================================== */

    async function loadTalukas(
        districtId,
        selectedTaluka = ''
    ) {

        if (!districtId) {
            return;
        }


        try {

            talukaField.disabled = true;
            villageField.disabled = true;


            clearSelect(
                talukaField,
                'Loading talukas...'
            );

            clearSelect(
                villageField,
                'Select Taluka first'
            );


            if (talukaLoading) {

                talukaLoading.classList.remove(
                    'd-none'
                );

            }


            if (talukaError) {

                talukaError.classList.add(
                    'd-none'
                );

                talukaError.textContent = '';

            }


            const tehsilUrl =
                new URL(
                    LOCATION_URLS.tehsils,
                    window.location.origin
                );

            tehsilUrl.searchParams.set(
                'district_id',
                districtId
            );


            console.log(
                'TALUKA URL:',
                tehsilUrl.toString()
            );


            const response =
                await fetchJson(
                    tehsilUrl.toString(),
                    'Taluka'
                );


            const talukas =
                extractArray(
                    response,
                    [
                        'tehsils',
                        'talukas',
                        'tehsil',
                        'taluka',
                        'results',
                        'items',
                        'data'
                    ]
                );


            console.log(
                'TALUKAS:',
                talukas
            );


            clearSelect(
                talukaField,
                'Select Taluka'
            );


            if (!talukas.length) {

                throw new Error(
                    'No talukas found.'
                );

            }


            let selectedTalukaItem =
                null;


            talukas.forEach(
                function (taluka) {

                    const id =
                        getId(
                            taluka,
                            'taluka'
                        );

                    const name =
                        getName(
                            taluka,
                            'taluka'
                        );


                    if (
                        !id ||
                        !name
                    ) {
                        return;
                    }


                    const selected =
                        normalize(name) ===
                        normalize(
                            selectedTaluka
                        );


                    addOption(
                        talukaField,
                        id,
                        name,
                        selected
                    );


                    if (selected) {

                        selectedTalukaItem =
                            taluka;

                    }

                }
            );


            if (
                selectedTaluka &&
                !selectedTalukaItem
            ) {

                addOption(
                    talukaField,
                    selectedTaluka,
                    selectedTaluka,
                    true
                );

            }


            talukaField.disabled = false;


            saveTalukaName();


            if (selectedTalukaItem) {

                await loadLocations(
                    getId(
                        selectedTalukaItem,
                        'taluka'
                    ),
                    currentVillage
                );

            } else if (selectedTaluka) {

                await loadLocations(
                    selectedTaluka,
                    currentVillage
                );

            }


        } catch (error) {

            console.error(
                'Taluka API error:',
                error
            );


            clearSelect(
                talukaField,
                'Unable to load talukas'
            );

            talukaField.disabled = true;


            if (talukaError) {

                talukaError.textContent =
                    error.message ||
                    'Unable to load talukas.';

                talukaError.classList.remove(
                    'd-none'
                );

            }

        } finally {

            if (talukaLoading) {

                talukaLoading.classList.add(
                    'd-none'
                );

            }

        }

    }


    /* =========================================================
       LOAD CITY / VILLAGE
    ========================================================== */

    async function loadLocations(
        talukaId,
        selectedVillage = ''
    ) {

        if (!talukaId) {
            return;
        }


        try {

            villageField.disabled = true;


            clearSelect(
                villageField,
                'Loading villages...'
            );


            if (locationLoading) {

                locationLoading.classList.remove(
                    'd-none'
                );

            }


            if (locationError) {

                locationError.classList.add(
                    'd-none'
                );

                locationError.textContent = '';

            }


            const locationUrl =
                new URL(
                    LOCATION_URLS.locations,
                    window.location.origin
                );

            locationUrl.searchParams.set(
                'tehsil_id',
                talukaId
            );


            console.log(
                'LOCATION URL:',
                locationUrl.toString()
            );


            const response =
                await fetchJson(
                    locationUrl.toString(),
                    'Location'
                );


            const locations =
                extractArray(
                    response,
                    [
                        'locations',
                        'villages',
                        'results',
                        'items',
                        'data'
                    ]
                );


            console.log(
                'LOCATIONS:',
                locations
            );


            clearSelect(
                villageField,
                'Select Village'
            );


            if (!locations.length) {

                throw new Error(
                    'No villages/locations found.'
                );

            }


            let selectedFound =
                false;


            locations.forEach(
                function (location) {

                    const id =
                        getId(
                            location,
                            'location'
                        );

                    const name =
                        getName(
                            location,
                            'location'
                        );


                    if (!name) {
                        return;
                    }


                    const option =
                        document.createElement('option');


                    option.value =
                        name;

                    option.textContent =
                        name;


                    option.dataset.locationId =
                        id || '';


                    const pin =
                        location.pin_code ??
                        location.pincode ??
                        location.pinCode ??
                        location.pin ??
                        location.postal_code ??
                        '';


                    option.dataset.pincode =
                        pin;


                    if (
                        normalize(name) ===
                        normalize(selectedVillage)
                    ) {

                        option.selected = true;

                        selectedFound = true;


                        if (pin) {

                            pincodeField.value =
                                pin;

                        }

                    }


                    villageField.appendChild(
                        option
                    );

                }
            );


            if (
                selectedVillage &&
                !selectedFound
            ) {

                addOption(
                    villageField,
                    selectedVillage,
                    selectedVillage,
                    true
                );


                pincodeField.value =
                    currentPincode || '';

            }


            if (
                selectedVillage &&
                !pincodeField.value &&
                currentPincode
            ) {

                pincodeField.value =
                    currentPincode;

            }


            villageField.disabled = false;


        } catch (error) {

            console.error(
                'Location API error:',
                error
            );


            clearSelect(
                villageField,
                'Unable to load locations'
            );

            villageField.disabled = true;


            if (locationError) {

                locationError.textContent =
                    error.message ||
                    'Unable to load city / village locations.';

                locationError.classList.remove(
                    'd-none'
                );

            }

        } finally {

            if (locationLoading) {

                locationLoading.classList.add(
                    'd-none'
                );

            }

        }

    }


    /* =========================================================
       DISTRICT CHANGE
    ========================================================== */

    if (districtField) {

        districtField.addEventListener(
            'change',
            function () {

                const districtId =
                    this.value;


                saveDistrictName();


                clearSelect(
                    talukaField,
                    'Select district first'
                );

                clearSelect(
                    villageField,
                    'Select Taluka first'
                );


                talukaField.disabled = true;
                villageField.disabled = true;


                if (!districtId) {

                    if (districtHidden) {
                        districtHidden.value = '';
                    }

                    if (talukaHidden) {
                        talukaHidden.value = '';
                    }

                    if (pincodeField) {
                        pincodeField.value = '';
                    }

                    return;

                }


                loadTalukas(
                    districtId,
                    ''
                );

            }
        );

    }


    /* =========================================================
       TALUKA CHANGE
    ========================================================== */

    if (talukaField) {

        talukaField.addEventListener(
            'change',
            function () {

                const talukaId =
                    this.value;


                saveTalukaName();


                clearSelect(
                    villageField,
                    'Select Taluka first'
                );


                villageField.disabled = true;


                if (!talukaId) {

                    if (talukaHidden) {
                        talukaHidden.value = '';
                    }

                    if (pincodeField) {
                        pincodeField.value = '';
                    }

                    return;

                }


                loadLocations(
                    talukaId,
                    ''
                );

            }
        );

    }


    /* =========================================================
       CITY / VILLAGE CHANGE
       → PINCODE
    ========================================================== */

    if (villageField) {

        villageField.addEventListener(
            'change',
            function () {

                const selectedOption =
                    this.options[
                        this.selectedIndex
                    ];


                if (!selectedOption) {

                    pincodeField.value = '';

                    return;

                }


                pincodeField.value =
                    selectedOption
                        .dataset
                        .pincode || '';

            }
        );

    }


    /* =========================================================
       STATE CHANGE
    ========================================================== */

    if (stateField) {

        stateField.addEventListener(
            'change',
            function () {

                saveStateName();

                const stateId =
                    this.value;


                if (!stateId) {
                    return;
                }


                loadDistricts();

            }
        );

    }


    /* =========================================================
       INITIAL LOCATION LOAD
    ========================================================== */

    console.log(
        '========================================'
    );

    console.log(
        'EDIT STUDENT LOCATION LOAD'
    );

    console.log(
        'LOCATION URLS:',
        LOCATION_URLS
    );

    console.log(
        'CURRENT STATE:',
        currentState
    );

    console.log(
        'CURRENT DISTRICT:',
        currentDistrict
    );

    console.log(
        'CURRENT TALUKA:',
        currentTaluka
    );

    console.log(
        'CURRENT VILLAGE:',
        currentVillage
    );

    console.log(
        'CURRENT PINCODE:',
        currentPincode
    );

    console.log(
        '========================================'
    );


    if (
        stateField &&
        districtField &&
        talukaField &&
        villageField
    ) {

        saveStateName();

        loadDistricts();

    }


    /* =========================================================
       FORM VALIDATION
    ========================================================== */

    const studentForm =
        document.getElementById('studentForm');


    if (studentForm) {

        studentForm.addEventListener(
            'submit',
            function (event) {

                let valid = true;


                /* ---------------------------------------------
                   AADHAAR
                ---------------------------------------------- */

                if (
                    aadhar &&
                    aadhar.value.trim() !== '' &&
                    !/^\d{12}$/.test(
                        aadhar.value.trim()
                    )
                ) {

                    if (aadharError) {

                        aadharError.textContent =
                            'Aadhaar number must contain exactly 12 digits.';

                    }

                    valid = false;

                }


                /* ---------------------------------------------
                   PHONE
                ---------------------------------------------- */

                if (
                    phone &&
                    phone.value.trim() !== '' &&
                    !/^[6-9]\d{9}$/.test(
                        phone.value.trim()
                    )
                ) {

                    if (phoneError) {

                        phoneError.textContent =
                            'Enter a valid 10 digit Indian mobile number.';

                    }

                    valid = false;

                }


                /* ---------------------------------------------
                   PARENT PHONES
                ---------------------------------------------- */

                document
                    .querySelectorAll('.parent-phone')
                    .forEach(function (input) {

                        if (
                            input.value.trim() !== '' &&
                            !/^[6-9]\d{9}$/.test(
                                input.value.trim()
                            )
                        ) {

                            valid = false;

                        }

                    });


                /* ---------------------------------------------
                   STATE NAME
                ---------------------------------------------- */

                saveStateName();


                /* ---------------------------------------------
                   DISTRICT NAME
                ---------------------------------------------- */

                saveDistrictName();


                /* ---------------------------------------------
                   TALUKA NAME
                ---------------------------------------------- */

                saveTalukaName();


                /* ---------------------------------------------
                   DEBUG VALUES
                ---------------------------------------------- */

                console.log(
                    '========================================'
                );

                console.log(
                    'FORM SUBMIT'
                );

                console.log(
                    'State ID:',
                    stateField
                        ? stateField.value
                        : ''
                );

                console.log(
                    'State Name:',
                    stateHidden
                        ? stateHidden.value
                        : ''
                );

                console.log(
                    'District ID:',
                    districtField
                        ? districtField.value
                        : ''
                );

                console.log(
                    'District Name:',
                    districtHidden
                        ? districtHidden.value
                        : ''
                );

                console.log(
                    'Taluka ID:',
                    talukaField
                        ? talukaField.value
                        : ''
                );

                console.log(
                    'Taluka Name:',
                    talukaHidden
                        ? talukaHidden.value
                        : ''
                );

                console.log(
                    'Village:',
                    villageField
                        ? villageField.value
                        : ''
                );

                console.log(
                    'Pincode:',
                    pincodeField
                        ? pincodeField.value
                        : ''
                );

                console.log(
                    'Marathi Name:',
                    document.getElementById('marathi_name')
                        ? document.getElementById('marathi_name').value
                        : ''
                );

                console.log(
                    '========================================'
                );


                if (!valid) {

                    event.preventDefault();


                    const basicButton =
                        document.querySelector(
                            '[data-tab="basic"]'
                        );


                    if (basicButton) {

                        basicButton.click();

                    }

                }

            }
        );

    }

});

</script>

@endsection