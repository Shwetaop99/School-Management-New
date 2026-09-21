@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-person-plus-fill text-primary me-2"></i>
            Student Registration
        </h3>

        <p class="text-muted mb-0">
            Register a new student with complete academic and personal information.
        </p>
    </div>

    <a href="{{ route('admin.students.index') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Students
    </a>
</div>


{{-- SUCCESS --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif


{{-- ERROR --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif


{{-- VALIDATION ERRORS --}}
@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Please correct the following errors:
        </div>

        <ul class="mb-0 ps-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
    MAIN FORM
========================================================== --}}
<form method="POST"
      action="{{ route('admin.students.store') }}"
      enctype="multipart/form-data"
      id="studentForm">

    @csrf

    <div class="student-registration-card">

        {{-- =================================================
            TABS
        ================================================== --}}
        <div class="registration-tabs">

            <button type="button"
                    class="registration-tab active"
                    data-tab="basic">

                <span class="tab-number">1</span>

                <span>
                    <strong>Basic Information</strong>
                    <small>Personal details</small>
                </span>

            </button>


            <button type="button"
                    class="registration-tab"
                    data-tab="academic">

                <span class="tab-number">2</span>

                <span>
                    <strong>Academic Information</strong>
                    <small>Admission details</small>
                </span>

            </button>


            <button type="button"
                    class="registration-tab"
                    data-tab="parents">

                <span class="tab-number">3</span>

                <span>
                    <strong>Parents Data</strong>
                    <small>Parent information</small>
                </span>

            </button>


            <button type="button"
                    class="registration-tab"
                    data-tab="previous">

                <span class="tab-number">4</span>

                <span>
                    <strong>Previous School</strong>
                    <small>Previous education</small>
                </span>

            </button>


            <button type="button"
                    class="registration-tab"
                    data-tab="address">

                <span class="tab-number">5</span>

                <span>
                    <strong>Address</strong>
                    <small>Location details</small>
                </span>

            </button>

        </div>


        {{-- =================================================
            TAB CONTENT
        ================================================== --}}
        <div class="registration-body">


            {{-- =================================================
                TAB 1 : BASIC INFORMATION
            ================================================== --}}
            <div class="tab-content-section active"
                 id="tab-basic">

                <div class="section-heading">

                    <div class="section-icon">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>

                    <div>
                        <h5>Basic Information</h5>
                        <p>Enter the student's personal information.</p>
                    </div>

                </div>


                <div class="row g-4">

                    {{-- PHOTO --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Student Photo
                        </label>

                        <div class="photo-upload-box">

                            <div id="imagePreviewContainer"
                                 class="image-preview-container">

                                <img id="imagePreview"
                                     src=""
                                     alt="Student Photo"
                                     style="display:none;">

                                <div id="imagePlaceholder"
                                     class="image-placeholder">

                                    <i class="bi bi-person-bounding-box"></i>
                                    <span>Upload Photo</span>

                                </div>

                            </div>


                            <input type="file"
                                   name="profile_image"
                                   id="profile_image"
                                   class="form-control mt-3"
                                   accept="image/jpeg,image/png,image/webp">


                            <small class="text-muted d-block mt-2">
                                JPG, PNG or WEBP. Maximum 5 MB.
                            </small>


                            @error('profile_image')
                                <small class="text-danger d-block">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>


                    {{-- NAME --}}
                    <div class="col-md-9">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    First Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="first_name"
                                       id="first_name"
                                       class="form-control"
                                       value="{{ old('first_name') }}"
                                       required>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label">
                                    Middle Name
                                </label>

                                <input type="text"
                                       name="middle_name"
                                       id="middle_name"
                                       class="form-control"
                                       value="{{ old('middle_name') }}">

                            </div>


                            <div class="col-md-4">

                                <label class="form-label">
                                    Last Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="last_name"
                                       id="last_name"
                                       class="form-control"
                                       value="{{ old('last_name') }}"
                                       required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Marathi Name
                                </label>

                                <input type="text"
                                       name="marathi_name"
                                       id="marathi_name"
                                       class="form-control"
                                       value="{{ old('marathi_name') }}">

                                <small class="text-muted">
                                    Automatically generated from English name.
                                    You can edit it.
                                </small>

                            </div>


                            <div class="col-md-3">

                                <label class="form-label">
                                    Gender
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="gender"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="Male"
                                        {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="Female"
                                        {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                    <option value="Other"
                                        {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-3">

                                <label class="form-label">
                                    Date of Birth
                                </label>

                                <input type="date"
                                       name="date_of_birth"
                                       id="date_of_birth"
                                       class="form-control"
                                       value="{{ old('date_of_birth') }}">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Aadhaar Number
                                </label>

                                <input type="text"
                                       name="aadhar_card_no"
                                       id="aadhar_card_no"
                                       class="form-control"
                                       value="{{ old('aadhar_card_no') }}"
                                       maxlength="12"
                                       inputmode="numeric"
                                       placeholder="12 digit Aadhaar number">

                                <small id="aadharError"
                                       class="text-danger d-none">
                                    Aadhaar number must contain exactly 12 digits.
                                </small>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       class="form-control"
                                       value="{{ old('phone') }}"
                                       maxlength="10"
                                       inputmode="numeric"
                                       placeholder="10 digit mobile number">

                                <small id="phoneError"
                                       class="text-danger d-none">
                                    Enter a valid 10 digit mobile number
                                    starting with 6, 7, 8 or 9.
                                </small>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email') }}"
                                       placeholder="student@example.com">

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                TAB 2 : ACADEMIC
            ================================================== --}}
            <div class="tab-content-section"
                 id="tab-academic">

                <div class="section-heading">

                    <div class="section-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <div>
                        <h5>Academic Information</h5>
                        <p>Enter admission and academic details.</p>
                    </div>

                </div>


                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Academic Year
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="academic_year"
                               class="form-control"
                               value="{{ old('academic_year', date('Y').'-'.(date('Y') + 1)) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Current Class
                            <span class="text-danger">*</span>
                        </label>

                        <select name="class"
                                id="class"
                                class="form-select"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach([
                                'Nursery','LKG','UKG',
                                '1','2','3','4','5','6','7','8',
                                '9','10','11','12'
                            ] as $class)

                                <option value="{{ $class }}"
                                    {{ old('class') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Section
                            <span class="text-danger">*</span>
                        </label>

                        <select name="section"
                                id="section"
                                class="form-select"
                                required>

                            <option value="">
                                Select Section
                            </option>

                            @foreach(['A','B','C','D','E','F'] as $section)

                                <option value="{{ $section }}"
                                    {{ old('section') == $section ? 'selected' : '' }}>
                                    {{ $section }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Roll Number
                        </label>

                        <input type="text"
                               name="roll_number"
                               id="roll_number"
                               class="form-control bg-light"
                               value="{{ old('roll_number') }}"
                               readonly>

                        <small class="text-muted">
                            Automatically generated class-wise.
                        </small>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Admission Class
                        </label>

                        <select name="admission_class"
                                class="form-select">

                            <option value="">
                                Select Class
                            </option>

                            @foreach([
                                'Nursery','LKG','UKG',
                                '1','2','3','4','5','6','7','8',
                                '9','10','11','12'
                            ] as $class)

                                <option value="{{ $class }}"
                                    {{ old('admission_class') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Admission Date
                        </label>

                        <input type="date"
                               name="admission_date"
                               class="form-control"
                               value="{{ old('admission_date') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Register Number
                        </label>

                        <input type="text"
                               name="register_no"
                               id="register_no"
                               class="form-control"
                               value="{{ old('register_no', 'Auto Generated') }}"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Book Number
                        </label>

                        <input type="text"
                               name="book_no"
                               id="book_no"
                               class="form-control"
                               value="{{ old('book_no', 'Auto Generated') }}"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            APAAR ID
                        </label>

                        <input type="text"
                               name="appar_id"
                               id="appar_id"
                               class="form-control"
                               value="{{ old('appar_id', 'Auto Generated') }}"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            PEN Number
                        </label>

                        <input type="text"
                               name="pen_no"
                               id="pen_no"
                               class="form-control"
                               value="{{ old('pen_no', 'Auto Generated') }}"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Medium
                        </label>

                        <select name="medium"
                                class="form-select">

                            <option value="">
                                Select Medium
                            </option>

                            <option value="Marathi"
                                {{ old('medium') == 'Marathi' ? 'selected' : '' }}>
                                Marathi
                            </option>

                            <option value="English"
                                {{ old('medium') == 'English' ? 'selected' : '' }}>
                                English
                            </option>

                            <option value="Semi-English"
                                {{ old('medium') == 'Semi-English' ? 'selected' : '' }}>
                                Semi-English
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Mother Tongue
                        </label>

                        <input type="text"
                               name="mother_tongue"
                               class="form-control"
                               value="{{ old('mother_tongue') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Nationality
                        </label>

                        <input type="text"
                               name="nationality"
                               class="form-control"
                               value="{{ old('nationality', 'Indian') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Religion
                        </label>

                        <input type="text"
                               name="religion"
                               class="form-control"
                               value="{{ old('religion') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Caste
                        </label>

                        <input type="text"
                               name="caste"
                               class="form-control"
                               value="{{ old('caste') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Sub Caste
                        </label>

                        <input type="text"
                               name="sub_caste"
                               class="form-control"
                               value="{{ old('sub_caste') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="active"
                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status') == 'inactive' ? 'selected' : '' }}>
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
                        <p>Enter father, mother and guardian information.</p>
                    </div>

                </div>


                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="parent-card">

                            <h6>
                                <i class="bi bi-person-fill me-2"></i>
                                Father's Information
                            </h6>

                            <div class="row g-3">

                                <div class="col-12">

                                    <label class="form-label">
                                        Father Name
                                    </label>

                                    <input type="text"
                                           name="father_name"
                                           class="form-control"
                                           value="{{ old('father_name') }}">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Phone
                                    </label>

                                    <input type="text"
                                           name="father_phone"
                                           class="form-control parent-phone"
                                           maxlength="10"
                                           inputmode="numeric"
                                           value="{{ old('father_phone') }}">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Occupation
                                    </label>

                                    <input type="text"
                                           name="father_occupation"
                                           class="form-control"
                                           value="{{ old('father_occupation') }}">

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="parent-card">

                            <h6>
                                <i class="bi bi-person-fill me-2"></i>
                                Mother's Information
                            </h6>

                            <div class="row g-3">

                                <div class="col-12">

                                    <label class="form-label">
                                        Mother Name
                                    </label>

                                    <input type="text"
                                           name="mother_name"
                                           class="form-control"
                                           value="{{ old('mother_name') }}">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Phone
                                    </label>

                                    <input type="text"
                                           name="mother_phone"
                                           class="form-control parent-phone"
                                           maxlength="10"
                                           inputmode="numeric"
                                           value="{{ old('mother_phone') }}">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Occupation
                                    </label>

                                    <input type="text"
                                           name="mother_occupation"
                                           class="form-control"
                                           value="{{ old('mother_occupation') }}">

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-12">

                        <div class="parent-card">

                            <h6>
                                <i class="bi bi-shield-person-fill me-2"></i>
                                Guardian Information
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Guardian Name
                                    </label>

                                    <input type="text"
                                           name="guardian_name"
                                           class="form-control"
                                           value="{{ old('guardian_name') }}">

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label">
                                        Guardian Relation
                                    </label>

                                    <input type="text"
                                           name="guardian_relation"
                                           class="form-control"
                                           value="{{ old('guardian_relation') }}">

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label">
                                        Guardian Phone
                                    </label>

                                    <input type="text"
                                           name="guardian_phone"
                                           class="form-control parent-phone"
                                           maxlength="10"
                                           inputmode="numeric"
                                           value="{{ old('guardian_phone') }}">

                                </div>

                            </div>

                        </div>

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
                        <h5>Previous School Information</h5>
                        <p>Enter details of the student's previous school.</p>
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
                               value="{{ old('previous_school_name') }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Previous School Address
                        </label>

                        <input type="text"
                               name="previous_school_address"
                               class="form-control"
                               value="{{ old('previous_school_address') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Previous Class
                        </label>

                        <input type="text"
                               name="previous_school_class"
                               class="form-control"
                               value="{{ old('previous_school_class') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Previous Board
                        </label>

                        <input type="text"
                               name="previous_school_board"
                               class="form-control"
                               value="{{ old('previous_school_board') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Previous School Medium
                        </label>

                        <input type="text"
                               name="previous_school_medium"
                               class="form-control"
                               value="{{ old('previous_school_medium') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Previous School Result
                        </label>

                        <input type="text"
                               name="previous_school_result"
                               class="form-control"
                               value="{{ old('previous_school_result') }}">

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Previous School Remarks
                        </label>

                        <textarea name="previous_school_remarks"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Enter previous school remarks">{{ old('previous_school_remarks') }}</textarea>

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
                            District and taluka are loaded dynamically.
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
                                {{ old('country', 'India') == 'India' ? 'selected' : '' }}>
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

                        {{-- DATABASE FIELD --}}
                        <input type="hidden"
                               name="state"
                               id="state"
                               value="{{ old('state', 'Maharashtra') }}">

                    </div>


                    {{-- DISTRICT --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            District
                        </label>

                        <select name="district_id"
                                id="district_id"
                                class="form-select">

                            <option value="">
                                Select District
                            </option>

                        </select>

                        {{-- DATABASE FIELD --}}
                        <input type="hidden"
                               name="district"
                               id="district"
                               value="{{ old('district') }}">

                        <small id="districtLoading"
                               class="text-muted d-none">

                            <i class="bi bi-arrow-repeat"></i>
                            Loading districts...

                        </small>

                        <small id="districtError"
                               class="text-danger d-none">
                        </small>

                    </div>


                    {{-- TALUKA --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Taluka / Tehsil
                        </label>

                        <select name="taluka_id"
                                id="taluka_id"
                                class="form-select"
                                disabled>

                            <option value="">
                                Select district first
                            </option>

                        </select>

                        {{-- DATABASE FIELD --}}
                        <input type="hidden"
                               name="taluka"
                               id="taluka"
                               value="{{ old('taluka') }}">

                        <small id="talukaLoading"
                               class="text-muted d-none">

                            <i class="bi bi-arrow-repeat"></i>
                            Loading talukas...

                        </small>

                        <small id="talukaError"
                               class="text-danger d-none">
                        </small>

                    </div>


                    {{-- CITY / VILLAGE --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            City / Village
                        </label>

                        <select name="city_village"
                                id="city_village"
                                class="form-select"
                                disabled>

                            <option value="">
                                Select Taluka first
                            </option>

                        </select>

                        <small id="locationLoading"
                               class="text-muted d-none">

                            <i class="bi bi-arrow-repeat"></i>
                            Loading locations...

                        </small>

                        <small id="locationError"
                               class="text-danger d-none">
                        </small>

                    </div>


                    {{-- PINCODE --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Pincode
                        </label>

                        <input type="text"
                               name="pincode"
                               id="pincode"
                               class="form-control"
                               maxlength="6"
                               inputmode="numeric"
                               value="{{ old('pincode') }}"
                               readonly>

                    </div>


                    {{-- ADDRESS --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Full Address
                        </label>

                        <textarea name="address"
                                  id="address"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Enter complete residential address">{{ old('address') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            FOOTER
        ================================================== --}}
        <div class="registration-footer">

            <button type="button"
                    class="btn btn-outline-secondary"
                    id="previousBtn"
                    style="display:none;">

                <i class="bi bi-arrow-left me-1"></i>
                Previous

            </button>


            <div class="ms-auto d-flex gap-2">

                <button type="button"
                        class="btn btn-light"
                        id="cancelBtn">

                    Cancel

                </button>


                <button type="button"
                        class="btn btn-primary"
                        id="nextBtn">

                    Next
                    <i class="bi bi-arrow-right ms-1"></i>

                </button>


                <button type="submit"
                        class="btn btn-success"
                        id="submitBtn"
                        style="display:none;">

                    <i class="bi bi-check-circle-fill me-1"></i>
                    Register Student

                </button>

            </div>

        </div>

    </div>

</form>


</div>

{{-- =============================================================
STYLES
============================================================= --}}

<style>

.student-registration-card {
    background:#fff;
    border:1px solid #e7eaf0;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 30px rgba(0,0,0,.05);
}

.registration-tabs {
    display:flex;
    background:#f8f9fc;
    border-bottom:1px solid #e5e7eb;
    overflow-x:auto;
}

.registration-tab {
    flex:1;
    min-width:190px;
    border:0;
    background:transparent;
    padding:18px 16px;
    display:flex;
    align-items:center;
    gap:12px;
    text-align:left;
    transition:.2s ease;
    color:#64748b;
    border-bottom:3px solid transparent;
}

.registration-tab:hover {
    background:#f1f5f9;
}

.registration-tab.active {
    color:#0d6efd;
    background:#fff;
    border-bottom-color:#0d6efd;
}

.registration-tab .tab-number {
    width:34px;
    height:34px;
    border-radius:50%;
    background:#e9eef7;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    flex-shrink:0;
}

.registration-tab.active .tab-number {
    background:#0d6efd;
    color:#fff;
}

.registration-tab strong {
    display:block;
    font-size:14px;
}

.registration-tab small {
    display:block;
    margin-top:2px;
    font-size:11px;
    color:#94a3b8;
}

.registration-body {
    padding:30px;
}

.tab-content-section {
    display:none;
}

.tab-content-section.active {
    display:block;
}

.section-heading {
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:28px;
    padding-bottom:18px;
    border-bottom:1px solid #eef0f4;
}

.section-icon {
    width:44px;
    height:44px;
    border-radius:12px;
    background:#eef5ff;
    color:#0d6efd;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}

.section-heading h5 {
    margin:0;
    font-weight:700;
    color:#1e293b;
}

.section-heading p {
    margin:3px 0 0;
    color:#94a3b8;
    font-size:13px;
}

.form-label {
    color:#334155;
    font-size:13px;
    font-weight:600;
    margin-bottom:7px;
}

.form-control,
.form-select {
    min-height:43px;
    border-color:#dfe3e8;
    border-radius:8px;
    font-size:14px;
}

.form-control:focus,
.form-select:focus {
    border-color:#86b7fe;
    box-shadow:0 0 0 .2rem rgba(13,110,253,.10);
}

textarea.form-control {
    min-height:100px;
}

.photo-upload-box {
    padding:15px;
    border:1px dashed #cbd5e1;
    border-radius:12px;
    background:#f8fafc;
}

.image-preview-container {
    height:230px;
    border-radius:10px;
    background:#eef2f7;
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
}

.image-preview-container img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.image-placeholder {
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    color:#94a3b8;
    gap:8px;
}

.image-placeholder i {
    font-size:48px;
}

.parent-card {
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:20px;
    background:#fafbfc;
}

.parent-card h6 {
    font-weight:700;
    color:#334155;
    margin-bottom:18px;
}

.registration-footer {
    display:flex;
    align-items:center;
    padding:18px 30px;
    border-top:1px solid #e5e7eb;
    background:#fafbfc;
}

.registration-footer .btn {
    min-width:110px;
    border-radius:8px;
}

#districtLoading,
#talukaLoading,
#locationLoading {
    font-size:12px;
}

.location-loaded {
    background:#f8fff9;
}

@media(max-width:768px) {

    .registration-body {
        padding:20px;
    }

    .registration-tabs {
        flex-direction:column;
    }

    .registration-tab {
        width:100%;
        min-width:0;
    }

    .registration-footer {
        padding:15px;
    }

}

</style>

{{-- =============================================================
JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       TAB NAVIGATION
    ========================================================== */

    const tabs = Array.from(
        document.querySelectorAll('.registration-tab')
    );

    const sections = Array.from(
        document.querySelectorAll('.tab-content-section')
    );

    const previousBtn =
        document.getElementById('previousBtn');

    const nextBtn =
        document.getElementById('nextBtn');

    const submitBtn =
        document.getElementById('submitBtn');

    const cancelBtn =
        document.getElementById('cancelBtn');

    let currentTab = 0;


    function showTab(index) {

        if (index < 0) {
            index = 0;
        }

        if (index >= tabs.length) {
            index = tabs.length - 1;
        }

        currentTab = index;

        tabs.forEach(function (tab, i) {

            tab.classList.toggle(
                'active',
                i === currentTab
            );

        });

        sections.forEach(function (section, i) {

            section.classList.toggle(
                'active',
                i === currentTab
            );

        });

        previousBtn.style.display =
            currentTab === 0
                ? 'none'
                : 'inline-block';

        nextBtn.style.display =
            currentTab === tabs.length - 1
                ? 'none'
                : 'inline-block';

        submitBtn.style.display =
            currentTab === tabs.length - 1
                ? 'inline-block'
                : 'none';

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    }


    tabs.forEach(function (tab, index) {

        tab.addEventListener('click', function () {
            showTab(index);
        });

    });


    nextBtn.addEventListener('click', function () {

        if (validateCurrentTab()) {
            showTab(currentTab + 1);
        }

    });


    previousBtn.addEventListener('click', function () {

        showTab(currentTab - 1);

    });


    cancelBtn.addEventListener('click', function () {

        window.location.href =
            "{{ route('admin.students.index') }}";

    });


    function validateCurrentTab() {

        const section = sections[currentTab];

        const requiredFields =
            section.querySelectorAll(
                'input[required], select[required], textarea[required]'
            );

        let valid = true;

        requiredFields.forEach(function (field) {

            if (!field.checkValidity()) {

                field.classList.add('is-invalid');

                valid = false;

            } else {

                field.classList.remove('is-invalid');

            }

        });


        if (!valid) {

            const firstInvalid =
                section.querySelector('.is-invalid');

            if (firstInvalid) {
                firstInvalid.focus();
            }

            return false;

        }


        const aadhaar =
            document.getElementById('aadhar_card_no');

        if (
            currentTab === 0 &&
            aadhaar.value.trim() !== ''
        ) {

            if (!/^\d{12}$/.test(aadhaar.value.trim())) {

                aadhaar.classList.add('is-invalid');

                document
                    .getElementById('aadharError')
                    .classList.remove('d-none');

                aadhaar.focus();

                return false;

            }

        }


        const phone =
            document.getElementById('phone');

        if (
            currentTab === 0 &&
            phone.value.trim() !== ''
        ) {

            if (!/^[6-9]\d{9}$/.test(phone.value.trim())) {

                phone.classList.add('is-invalid');

                document
                    .getElementById('phoneError')
                    .classList.remove('d-none');

                phone.focus();

                return false;

            }

        }

        return true;

    }


    /* =========================================================
       NUMERIC INPUT
    ========================================================== */

    function numericOnly(element, maxLength = null) {

        if (!element) {
            return;
        }

        element.addEventListener('input', function () {

            this.value =
                this.value.replace(/\D/g, '');

            if (maxLength) {

                this.value =
                    this.value.substring(0, maxLength);

            }

        });

    }


    numericOnly(
        document.getElementById('aadhar_card_no'),
        12
    );


    numericOnly(
        document.getElementById('phone'),
        10
    );


    document
        .querySelectorAll('.parent-phone')
        .forEach(function (input) {

            numericOnly(input, 10);

        });


    /* =========================================================
       AADHAAR
    ========================================================== */

    const aadhaar =
        document.getElementById('aadhar_card_no');

    const aadhaarError =
        document.getElementById('aadharError');


    aadhaar.addEventListener('input', function () {

        this.classList.remove('is-invalid');

        aadhaarError.classList.add('d-none');

        if (
            this.value.length === 12 &&
            !/^\d{12}$/.test(this.value)
        ) {

            this.classList.add('is-invalid');

            aadhaarError.classList.remove('d-none');

        }

    });


    /* =========================================================
       PHONE
    ========================================================== */

    const phone =
        document.getElementById('phone');

    const phoneError =
        document.getElementById('phoneError');


    phone.addEventListener('input', function () {

        this.classList.remove('is-invalid');

        phoneError.classList.add('d-none');

        if (
            this.value.length === 10 &&
            !/^[6-9]\d{9}$/.test(this.value)
        ) {

            this.classList.add('is-invalid');

            phoneError.classList.remove('d-none');

        }

    });


    /* =========================================================
       PHOTO PREVIEW
    ========================================================== */

    const profileImage =
        document.getElementById('profile_image');

    const imagePreview =
        document.getElementById('imagePreview');

    const imagePlaceholder =
        document.getElementById('imagePlaceholder');


    profileImage.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {

            imagePreview.style.display = 'none';
            imagePreview.src = '';
            imagePlaceholder.style.display = 'flex';

            return;

        }


        if (!file.type.startsWith('image/')) {

            this.value = '';

            alert('Please select a valid image file.');

            return;

        }


        const reader = new FileReader();


        reader.onload = function (event) {

            imagePreview.src =
                event.target.result;

            imagePreview.style.display =
                'block';

            imagePlaceholder.style.display =
                'none';

        };


        reader.readAsDataURL(file);

    });


    /* =========================================================
   MARATHI NAME - AUTOMATIC OFFLINE TRANSLITERATION
   ========================================================== */

const firstName =
    document.getElementById('first_name');

const middleName =
    document.getElementById('middle_name');

const lastName =
    document.getElementById('last_name');

const marathiName =
    document.getElementById('marathi_name');


/*
|--------------------------------------------------------------------------
| Make sure all fields exist
|--------------------------------------------------------------------------
*/

if (
    firstName &&
    middleName &&
    lastName &&
    marathiName
) {


    /*
    |--------------------------------------------------------------------------
    | Common Marathi Names / Surnames
    |--------------------------------------------------------------------------
    | These give better results for frequently used names.
    |--------------------------------------------------------------------------
    */

    const marathiDictionary = {

        /* ---------- Female Names ---------- */

        'sanika': 'सानिका',
        'saniya': 'सानिया',
        'sneha': 'स्नेहा',
        'shreya': 'श्रेया',
        'priya': 'प्रिया',
        'pooja': 'पूजा',
        'puja': 'पूजा',
        'neha': 'नेहा',
        'rutuja': 'ऋतुजा',
        'vaishnavi': 'वैष्णवी',
        'sakshi': 'साक्षी',
        'shruti': 'श्रुती',
        'swara': 'स्वरा',
        'ananya': 'अनन्या',
        'aditi': 'अदिती',
        'kavya': 'काव्या',
        'komal': 'कोमल',
        'nikita': 'निकिता',
        'poornima': 'पूर्णिमा',
        'pallavi': 'पल्लवी',
        'madhuri': 'माधुरी',
        'manisha': 'मनिषा',
        'archana': 'अर्चना',
        'seema': 'सीमा',
        'meena': 'मीना',
        'rani': 'राणी',
        'radha': 'राधा',
        'gauri': 'गौरी',
        'sakshi': 'साक्षी',
        'sayali': 'सायली',
        'mrunal': 'मृणाल',
        'mrunmayi': 'मृण्मयी',
        'shubhangi': 'शुभांगी',
        'tanvi': 'तन्वी',
        'tejaswini': 'तेजस्विनी',
        'vaidehi': 'वैदेही',
        'prachi': 'प्राची',
        'pranali': 'प्रणाली',
        'karishma': 'करिश्मा',
        'kajal': 'काजल',
        'deepali': 'दीपाली',
        'dipali': 'दीपाली',
        'jyoti': 'ज्योती',
        'sonali': 'सोनाली',
        'monali': 'मोनाली',


        /* ---------- Male Names ---------- */

        'rahul': 'राहुल',
        'rohit': 'रोहित',
        'rohan': 'रोहन',
        'sachin': 'सचिन',
        'sanjay': 'संजय',
        'sunil': 'सुनील',
        'suresh': 'सुरेश',
        'mahesh': 'महेश',
        'ramesh': 'रमेश',
        'rajendra': 'राजेंद्र',
        'ajay': 'अजय',
        'vijay': 'विजय',
        'amit': 'अमित',
        'akash': 'आकाश',
        'vaibhav': 'वैभव',
        'pratik': 'प्रतीक',
        'pranav': 'प्रणव',
        'om': 'ओम',
        'atharva': 'अथर्व',
        'ganesh': 'गणेश',
        'shubham': 'शुभम',
        'abhishek': 'अभिषेक',
        'mahendra': 'महेंद्र',
        'dinesh': 'दिनेश',
        'nilesh': 'निलेश',
        'milind': 'मिलिंद',
        'anil': 'अनिल',
        'ajit': 'अजित',
        'amit': 'अमित',
        'amol': 'अमोल',
        'ashok': 'अशोक',
        'santosh': 'संतोष',
        'sandeep': 'संदीप',
        'sandip': 'संदीप',
        'deepak': 'दीपक',
        'dipak': 'दीपक',
        'prakash': 'प्रकाश',
        'pravin': 'प्रवीण',
        'sunil': 'सुनील',
        'yogesh': 'योगेश',
        'mahesh': 'महेश',
        'mukesh': 'मुकेश',
        'rakesh': 'राकेश',
        'akash': 'आकाश',
        'akash': 'आकाश',
        'shankar': 'शंकर',
        'shivaji': 'शिवाजी',
        'swaraj': 'स्वराज',
        'siddharth': 'सिद्धार्थ',
        'sameer': 'समीर',
        'samir': 'समीर',
        'tushar': 'तुषार',
        'vishal': 'विशाल',
        'vikas': 'विकास',
        'vivek': 'विवेक',
        'vinod': 'विनोद',
        'manoj': 'मनोज',
        'mahadev': 'महादेव',
        'mangesh': 'मंगेश',
        'nagesh': 'नागेश',


        /* ---------- Common Surnames ---------- */

        'jadhav': 'जाधव',
        'patil': 'पाटील',
        'shinde': 'शिंदे',
        'pawar': 'पवार',
        'chavan': 'चव्हाण',
        'deshmukh': 'देशमुख',
        'gaikwad': 'गायकवाड',
        'kadam': 'कदम',
        'mane': 'माने',
        'more': 'मोरे',
        'jagtap': 'जगताप',
        'bhosale': 'भोसले',
        'bhosle': 'भोसले',
        'salunkhe': 'साळुंखे',
        'yadav': 'यादव',
        'shinde': 'शिंदे',
        'lokhande': 'लोखंडे',
        'sawant': 'सावंत',
        'desai': 'देसाई',
        'kulkarni': 'कुलकर्णी',
        'joshi': 'जोशी',
        'kanase': 'कणसे',
        'kore': 'कोरे',
        'more': 'मोरे',
        'nikam': 'निकम',
        'surve': 'सुर्वे',
        'thorat': 'थोरात',
        'bargaje': 'बारगळे',
        'shirole': 'शिरोळे',
        'ingale': 'इंगळे',
        'kumbhar': 'कुंभार',
        'tamboli': 'तांबोळी',
        'mulik': 'मुळीक',
        'kadak': 'कडक',
        'jagtap': 'जगताप',
        'sutar': 'सुतार',
        'gurav': 'गुरव',
        'mane': 'माने',
        'bhise': 'भिसे',
        'dhere': 'ढेरे',
        'pote': 'पोते',
        'dixit': 'दीक्षित',
        'joshi': 'जोशी',
        'bapat': 'बापट',
        'apte': 'आपटे',
        'sane': 'साने'
    };


    /*
    |--------------------------------------------------------------------------
    | Transliteration Rules
    |--------------------------------------------------------------------------
    */

    const specialRules = [

        ['ksh', 'क्ष'],
        ['dny', 'ज्ञ'],
        ['jn', 'ज्ञ'],
        ['gny', 'ज्ञ'],

        ['shri', 'श्री'],
        ['shr', 'श्र'],

        ['tra', 'त्र'],
        ['tr', 'त्र'],

        ['chh', 'छ'],
        ['ch', 'च'],

        ['kh', 'ख'],
        ['gh', 'घ'],

        ['th', 'थ'],
        ['dh', 'ध'],

        ['ph', 'फ'],
        ['bh', 'भ'],

        ['zh', 'झ'],
        ['jh', 'झ'],

        ['ng', 'ङ'],
        ['ny', 'ञ']
    ];


    /*
    |--------------------------------------------------------------------------
    | Transliterate One Word
    |--------------------------------------------------------------------------
    */

    function transliterateWord(word) {

        const originalWord = word.trim();

        if (!originalWord) {
            return '';
        }

        const lowerWord =
            originalWord.toLowerCase();


        /*
        |--------------------------------------------------------------------------
        | First check dictionary
        |--------------------------------------------------------------------------
        */

        if (
            marathiDictionary[lowerWord]
        ) {
            return marathiDictionary[lowerWord];
        }


        /*
        |--------------------------------------------------------------------------
        | Special Marathi combinations
        |--------------------------------------------------------------------------
        */

        let text = lowerWord;


        /*
        |--------------------------------------------------------------------------
        | Protect common combinations
        |--------------------------------------------------------------------------
        */

        specialRules.forEach(function (rule) {

            const english = rule[0];
            const marathi = rule[1];

            text = text.replace(
                new RegExp(english, 'g'),
                marathi
            );

        });


        /*
        |--------------------------------------------------------------------------
        | If Marathi characters were already generated,
        | don't convert them again.
        |--------------------------------------------------------------------------
        */

        let result = '';

        let i = 0;


        /*
        |--------------------------------------------------------------------------
        | Basic phonetic processing
        |--------------------------------------------------------------------------
        */

        while (i < text.length) {

            const remaining =
                text.substring(i);


            /*
            |--------------------------------------------------------------------------
            | Vowel combinations
            |--------------------------------------------------------------------------
            */

            if (remaining.startsWith('aa')) {

                result += 'आ';
                i += 2;
                continue;

            }

            if (remaining.startsWith('ee')) {

                result += 'ई';
                i += 2;
                continue;

            }

            if (remaining.startsWith('ii')) {

                result += 'ई';
                i += 2;
                continue;

            }

            if (remaining.startsWith('oo')) {

                result += 'ऊ';
                i += 2;
                continue;

            }

            if (remaining.startsWith('uu')) {

                result += 'ऊ';
                i += 2;
                continue;

            }

            if (remaining.startsWith('ai')) {

                result += 'ऐ';
                i += 2;
                continue;

            }

            if (remaining.startsWith('au')) {

                result += 'औ';
                i += 2;
                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | Single vowels
            |--------------------------------------------------------------------------
            */

            const vowelMap = {

                'a': 'अ',
                'i': 'इ',
                'u': 'उ',
                'e': 'ए',
                'o': 'ओ'

            };


            if (vowelMap[text[i]]) {

                result +=
                    vowelMap[text[i]];

                i++;
                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | Consonants
            |--------------------------------------------------------------------------
            */

            const consonantMap = {

                'b': 'ब',
                'c': 'क',
                'd': 'द',
                'f': 'फ',
                'g': 'ग',
                'h': 'ह',
                'j': 'ज',
                'k': 'क',
                'l': 'ल',
                'm': 'म',
                'n': 'न',
                'p': 'प',
                'q': 'क',
                'r': 'र',
                's': 'स',
                't': 'त',
                'v': 'व',
                'w': 'व',
                'x': 'क्स',
                'y': 'य',
                'z': 'ज'

            };


            if (consonantMap[text[i]]) {

                result +=
                    consonantMap[text[i]];

                i++;
                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | Keep spaces / punctuation
            |--------------------------------------------------------------------------
            */

            result += text[i];

            i++;
        }


        return result;
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Complete Marathi Name
    |--------------------------------------------------------------------------
    */

    function generateMarathiName() {

        /*
        |--------------------------------------------------------------------------
        | Do not overwrite manually corrected Marathi name
        |--------------------------------------------------------------------------
        */

        if (
            marathiName.dataset.manual === '1'
        ) {

            return;

        }


        const parts = [

            firstName.value.trim(),
            middleName.value.trim(),
            lastName.value.trim()

        ].filter(Boolean);


        if (!parts.length) {

            marathiName.value = '';

            return;
        }


        const translatedParts =
            parts.map(function (part) {

                return transliterateWord(part);

            });


        marathiName.value =
            translatedParts
                .join(' ')
                .replace(/\s+/g, ' ')
                .trim();
    }


    /*
    |--------------------------------------------------------------------------
    | English Name Fields
    |--------------------------------------------------------------------------
    */

    [
        firstName,
        middleName,
        lastName

    ].forEach(function (input) {

        input.addEventListener(
            'input',
            function () {

                /*
                |--------------------------------------------------------------------------
                | If user starts changing English name,
                | allow automatic generation again.
                |--------------------------------------------------------------------------
                */

                marathiName.dataset.manual = '0';

                generateMarathiName();

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Detect Manual Marathi Editing
    |--------------------------------------------------------------------------
    */

    marathiName.addEventListener(
        'input',
        function () {

            this.dataset.manual = '1';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Generation
    |--------------------------------------------------------------------------
    */

    if (
        marathiName.value.trim() === ''
    ) {

        marathiName.dataset.manual = '0';

        generateMarathiName();

    }

}


    /* =========================================================
       ROLL NUMBER
    ========================================================== */

    const classSelect =
        document.getElementById('class');

    const sectionSelect =
        document.getElementById('section');

    const rollNumber =
        document.getElementById('roll_number');


    async function loadNextRollNumber() {

        const selectedClass =
            classSelect.value;

        const selectedSection =
            sectionSelect.value;


        if (
            !selectedClass ||
            !selectedSection
        ) {

            rollNumber.value = '';

            return;

        }


        rollNumber.value = 'Loading...';

        rollNumber.disabled = true;


        try {

            const url =
                "{{ route('admin.students.next-roll-number') }}"
                + '?class='
                + encodeURIComponent(selectedClass)
                + '&section='
                + encodeURIComponent(selectedSection);


            const response =
                await fetch(url, {

                    headers: {

                        'Accept':'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'

                    }

                });


            if (!response.ok) {

                throw new Error(
                    'Unable to generate roll number.'
                );

            }


            const data =
                await response.json();


            rollNumber.value =
                data.roll_number ??
                data.roll ??
                data.next_roll_number ??
                '';


        } catch (error) {

            console.error(error);

            rollNumber.value = '';

        } finally {

            rollNumber.disabled = false;

        }

    }


    classSelect.addEventListener(
        'change',
        loadNextRollNumber
    );


    sectionSelect.addEventListener(
        'change',
        loadNextRollNumber
    );


    /* =========================================================
       LOCATION API
    ========================================================== */

    const stateSelect =
        document.getElementById('state_id');

    const districtSelect =
        document.getElementById('district_id');

    const talukaSelect =
        document.getElementById('taluka_id');

    const locationSelect =
        document.getElementById('city_village');

    const pincode =
        document.getElementById('pincode');


    /* DATABASE NAME FIELDS */

    const stateName =
        document.getElementById('state');

    const districtName =
        document.getElementById('district');

    const talukaName =
        document.getElementById('taluka');


    const districtLoading =
        document.getElementById('districtLoading');

    const talukaLoading =
        document.getElementById('talukaLoading');

    const locationLoading =
        document.getElementById('locationLoading');


    const districtError =
        document.getElementById('districtError');

    const talukaError =
        document.getElementById('talukaError');

    const locationError =
        document.getElementById('locationError');


    const oldDistrictId =
        @json(old('district_id'));

    const oldTalukaId =
        @json(old('taluka_id'));

    const oldLocation =
        @json(old('city_village'));

    const oldPincode =
        @json(old('pincode'));


    /* =========================================================
       SYNC API SELECT NAMES TO DATABASE FIELDS
    ========================================================== */

    function syncLocationNames() {

        if (
            stateSelect &&
            stateSelect.selectedIndex >= 0 &&
            stateSelect.value
        ) {

            stateName.value =
                stateSelect.options[
                    stateSelect.selectedIndex
                ].text.trim();

        }


        if (
            districtSelect &&
            districtSelect.selectedIndex >= 0 &&
            districtSelect.value
        ) {

            districtName.value =
                districtSelect.options[
                    districtSelect.selectedIndex
                ].text.trim();

        } else {

            districtName.value = '';

        }


        if (
            talukaSelect &&
            talukaSelect.selectedIndex >= 0 &&
            talukaSelect.value
        ) {

            talukaName.value =
                talukaSelect.options[
                    talukaSelect.selectedIndex
                ].text.trim();

        } else {

            talukaName.value = '';

        }

    }


    /* =========================================================
       MESSAGE HELPERS
    ========================================================== */

    function hideElement(element) {

        if (element) {
            element.classList.add('d-none');
        }

    }


    function showElement(element) {

        if (element) {
            element.classList.remove('d-none');
        }

    }


    function clearLocationMessages() {

        hideElement(districtLoading);
        hideElement(talukaLoading);
        hideElement(locationLoading);

        hideElement(districtError);
        hideElement(talukaError);
        hideElement(locationError);


        if (districtError) {
            districtError.textContent = '';
        }

        if (talukaError) {
            talukaError.textContent = '';
        }

        if (locationError) {
            locationError.textContent = '';
        }

    }


    /* =========================================================
       API ARRAY EXTRACTOR
    ========================================================== */

    function extractArray(result, possibleKeys = []) {

        if (Array.isArray(result)) {
            return result;
        }


        if (!result || typeof result !== 'object') {
            return [];
        }


        for (const key of possibleKeys) {

            if (Array.isArray(result[key])) {
                return result[key];
            }

        }


        if (Array.isArray(result.data)) {
            return result.data;
        }


        if (
            result.data &&
            typeof result.data === 'object'
        ) {

            for (const key of possibleKeys) {

                if (Array.isArray(result.data[key])) {
                    return result.data[key];
                }

            }


            if (Array.isArray(result.data.data)) {
                return result.data.data;
            }

        }


        if (Array.isArray(result.results)) {
            return result.results;
        }


        if (Array.isArray(result.items)) {
            return result.items;
        }


        if (Array.isArray(result.result)) {
            return result.result;
        }


        return [];

    }


    /* =========================================================
       GET JSON RESPONSE
    ========================================================== */

    async function getJson(url, type) {

        console.log(type + ' API:', url);


        const response =
            await fetch(url, {

                method: 'GET',

                headers: {

                    'Accept':
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'

                }

            });


        const text =
            await response.text();


        console.log(
            type + ' HTTP:',
            response.status
        );


        console.log(
            type + ' response:',
            text
        );


        if (!response.ok) {

            throw new Error(
                type +
                ' API HTTP ' +
                response.status
            );

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
                'Invalid JSON from ' +
                type +
                ' API.'
            );

        }

    }


    /* =========================================================
       LOAD DISTRICTS
    ========================================================== */

    async function loadDistricts(
        stateId,
        selectedDistrict = null,
        selectedTaluka = null,
        selectedLocation = null
    ) {

        clearLocationMessages();


        districtSelect.innerHTML =
            '<option value="">Loading districts...</option>';

        districtSelect.disabled = true;


        talukaSelect.innerHTML =
            '<option value="">Select district first</option>';

        talukaSelect.disabled = true;


        locationSelect.innerHTML =
            '<option value="">Select taluka first</option>';

        locationSelect.disabled = true;


        pincode.value = '';

        districtName.value = '';
        talukaName.value = '';


        if (!stateId) {

            districtSelect.innerHTML =
                '<option value="">Select District</option>';

            districtSelect.disabled = false;

            syncLocationNames();

            return;

        }


        showElement(districtLoading);


        try {

            const url =
                "{{ route('admin.locations.districts') }}"
                + '?state_id='
                + encodeURIComponent(stateId);


            const result =
                await getJson(
                    url,
                    'District'
                );


            console.log(
                'DISTRICT FULL OBJECT:',
                result
            );


            const districts =
                extractArray(
                    result,
                    [
                        'districts',
                        'data',
                        'results',
                        'items'
                    ]
                );


            console.log(
                'TOTAL DISTRICTS:',
                districts.length
            );


            console.table(districts);


            districtSelect.innerHTML =
                '<option value="">Select District</option>';


            districts.forEach(function (district) {

                const id =
                    district.id ??
                    district.district_id ??
                    district.code ??
                    district.district_code ??
                    district.value;


                const name =
                    district.district_name ??
                    district.name ??
                    district.district ??
                    district.label ??
                    district.title ??
                    district.text;


                if (
                    id !== undefined &&
                    id !== null &&
                    name
                ) {

                    const option =
                        document.createElement('option');


                    option.value = id;

                    option.textContent = name;


                    if (
                        selectedDistrict !== null &&
                        String(selectedDistrict) ===
                        String(id)
                    ) {

                        option.selected = true;

                    }


                    districtSelect.appendChild(
                        option
                    );

                }

            });


            if (
                districtSelect.options.length === 1
            ) {

                districtSelect.innerHTML =
                    '<option value="">No districts found</option>';

            }


            districtSelect.disabled = false;


            syncLocationNames();


            /* LOAD OLD TALUKA */

            if (selectedDistrict) {

                await loadTalukas(
                    selectedDistrict,
                    selectedTaluka,
                    selectedLocation
                );

            }


        } catch (error) {

            console.error(
                'District loading error:',
                error
            );


            districtSelect.innerHTML =
                '<option value="">Unable to load districts</option>';

            districtSelect.disabled = false;


            districtError.textContent =
                error.message ||
                'Unable to load districts.';

            showElement(districtError);

        } finally {

            hideElement(districtLoading);

        }

    }


    /* =========================================================
       LOAD TALUKAS / TEHSILS
    ========================================================== */

    async function loadTalukas(
        districtId,
        selectedTaluka = null,
        selectedLocation = null
    ) {

        console.log(
            '----------------------------------------'
        );

        console.log(
            'LOADING TALUKAS FOR DISTRICT:',
            districtId
        );


        talukaSelect.innerHTML =
            '<option value="">Loading all talukas...</option>';

        talukaSelect.disabled = true;


        locationSelect.innerHTML =
            '<option value="">Select taluka first</option>';

        locationSelect.disabled = true;


        pincode.value = '';

        talukaName.value = '';


        hideElement(talukaError);


        if (!districtId) {

            talukaSelect.innerHTML =
                '<option value="">Select District First</option>';

            talukaSelect.disabled = true;

            districtName.value = '';

            return;

        }


        showElement(talukaLoading);


        try {

            const url =
                "{{ route('admin.locations.tehsils') }}"
                + '?district_id='
                + encodeURIComponent(districtId);


            const result =
                await getJson(
                    url,
                    'Taluka'
                );


            console.log(
                'TALUKA FULL API OBJECT:',
                result
            );


            console.log(
                'TALUKA DATA TYPE:',
                typeof result
            );


            console.log(
                'TALUKA DATA:',
                result?.data
            );


            const talukas =
                extractArray(
                    result,
                    [
                        'tehsils',
                        'talukas',
                        'tehsil',
                        'taluka',
                        'district_tehsils',
                        'district_talukas',
                        'results',
                        'items'
                    ]
                );


            console.log(
                'TOTAL TALUKAS RECEIVED:',
                talukas.length
            );


            console.table(talukas);


            talukaSelect.innerHTML =
                '<option value="">Select Taluka / Tehsil</option>';


            talukas.forEach(function (taluka, index) {

                console.log(
                    'PROCESSING TALUKA:',
                    index,
                    taluka
                );


                const id =
                    taluka.id ??
                    taluka.tehsil_id ??
                    taluka.taluka_id ??
                    taluka.tehsilId ??
                    taluka.talukaId ??
                    taluka.code ??
                    taluka.tehsil_code ??
                    taluka.taluka_code ??
                    taluka.value;


                const name =
                    taluka.tehsil_name ??
                    taluka.taluka_name ??
                    taluka.tehsilName ??
                    taluka.talukaName ??
                    taluka.name ??
                    taluka.tehsil ??
                    taluka.taluka ??
                    taluka.label ??
                    taluka.title ??
                    taluka.text;


                console.log(
                    'TALUKA ID:',
                    id,
                    'NAME:',
                    name
                );


                if (
                    id !== undefined &&
                    id !== null &&
                    name !== undefined &&
                    name !== null &&
                    String(name).trim() !== ''
                ) {

                    const option =
                        document.createElement('option');


                    option.value = id;

                    option.textContent =
                        String(name).trim();


                    if (
                        selectedTaluka !== null &&
                        String(selectedTaluka) ===
                        String(id)
                    ) {

                        option.selected = true;

                    }


                    talukaSelect.appendChild(
                        option
                    );

                }

            });


            console.log(
                'TALUKA DROPDOWN OPTIONS:',
                talukaSelect.options.length
            );


            if (
                talukaSelect.options.length === 1
            ) {

                talukaSelect.innerHTML =
                    '<option value="">No talukas found</option>';


                talukaSelect.disabled = false;


                talukaError.textContent =
                    'No talukas / tehsils returned for district ID ' +
                    districtId +
                    '. Check the Taluka API response in browser Console.';

                showElement(talukaError);

                return;

            }


            talukaSelect.disabled = false;


            syncLocationNames();


            /* LOAD OLD LOCATION */

            if (
                selectedTaluka !== null &&
                selectedTaluka !== ''
            ) {

                await loadLocations(
                    selectedTaluka,
                    selectedLocation
                );

            }


        } catch (error) {

            console.error(
                'Taluka loading error:',
                error
            );


            talukaSelect.innerHTML =
                '<option value="">Unable to load talukas</option>';

            talukaSelect.disabled = false;


            talukaError.textContent =
                error.message ||
                'Unable to load talukas.';

            showElement(talukaError);

        } finally {

            hideElement(talukaLoading);

        }

    }


    /* =========================================================
       LOAD CITY / VILLAGE LOCATIONS
    ========================================================== */

    async function loadLocations(
        talukaId,
        selectedLocation = null
    ) {

        locationSelect.innerHTML =
            '<option value="">Loading locations...</option>';

        locationSelect.disabled = true;


        pincode.value = '';

        hideElement(locationError);

        showElement(locationLoading);


        if (!talukaId) {

            hideElement(locationLoading);


            locationSelect.innerHTML =
                '<option value="">Select Taluka first</option>';

            locationSelect.disabled = true;

            talukaName.value = '';

            return;

        }


        try {

            const url =
                "{{ route('admin.locations.locations') }}"
                + '?tehsil_id='
                + encodeURIComponent(talukaId);


            const result =
                await getJson(
                    url,
                    'Location'
                );


            console.log(
                'LOCATION FULL API OBJECT:',
                result
            );


            const locations =
                extractArray(
                    result,
                    [
                        'locations',
                        'results',
                        'items'
                    ]
                );


            console.log(
                'TOTAL LOCATIONS:',
                locations.length
            );


            console.table(locations);


            locationSelect.innerHTML =
                '<option value="">Select City / Village</option>';


            locations.forEach(function (location) {

                const id =
                    location.id ??
                    location.location_id ??
                    location.locationId ??
                    location.code ??
                    location.location_code;


                const name =
                    location.location_name ??
                    location.locationName ??
                    location.name ??
                    location.location ??
                    location.label ??
                    location.title ??
                    location.text;


                const pin =
                    location.pin_code ??
                    location.pincode ??
                    location.pinCode ??
                    location.pin ??
                    '';


                if (
                    id !== undefined &&
                    id !== null &&
                    name !== undefined &&
                    name !== null &&
                    String(name).trim() !== ''
                ) {

                    const option =
                        document.createElement('option');


                    option.value =
                        String(name).trim();


                    option.textContent =
                        pin
                            ? String(name).trim() +
                              ' - ' +
                              pin
                            : String(name).trim();


                    option.dataset.locationId =
                        id;


                    option.dataset.pincode =
                        pin;


                    if (
                        selectedLocation !== null &&
                        String(selectedLocation) ===
                        String(name)
                    ) {

                        option.selected = true;


                        if (pin) {

                            pincode.value = pin;

                        }

                    }


                    locationSelect.appendChild(
                        option
                    );

                }

            });


            /* PRESERVE OLD CITY / VILLAGE */

            if (
                selectedLocation &&
                !Array.from(
                    locationSelect.options
                ).some(function (option) {

                    return String(option.value) ===
                        String(selectedLocation);

                })
            ) {

                const option =
                    document.createElement('option');


                option.value =
                    selectedLocation;


                option.textContent =
                    selectedLocation;


                option.selected = true;


                locationSelect.appendChild(
                    option
                );


                if (oldPincode) {

                    pincode.value =
                        oldPincode;

                }

            }


            if (
                locationSelect.options.length === 1
            ) {

                locationSelect.innerHTML =
                    '<option value="">No locations found</option>';

            }


            locationSelect.disabled = false;


        } catch (error) {

            console.error(
                'Location loading error:',
                error
            );


            locationSelect.innerHTML =
                '<option value="">Unable to load locations</option>';

            locationSelect.disabled = false;


            locationError.textContent =
                error.message ||
                'Unable to load city / village locations.';

            showElement(locationError);

        } finally {

            hideElement(locationLoading);

        }

    }


    /* =========================================================
       STATE CHANGE
    ========================================================== */

    stateSelect.addEventListener(
        'change',
        function () {

            syncLocationNames();

            loadDistricts(
                this.value
            );

        }
    );


    /* =========================================================
       DISTRICT CHANGE
    ========================================================== */

    districtSelect.addEventListener(
        'change',
        function () {

            const districtId =
                this.value;


            syncLocationNames();


            console.log(
                'DISTRICT SELECTED:',
                districtId
            );


            loadTalukas(
                districtId
            );

        }
    );


    /* =========================================================
       TALUKA CHANGE
    ========================================================== */

    talukaSelect.addEventListener(
        'change',
        function () {

            const talukaId =
                this.value;


            syncLocationNames();


            console.log(
                'TALUKA SELECTED:',
                talukaId
            );


            loadLocations(
                talukaId
            );

        }
    );


    /* =========================================================
       CITY / VILLAGE CHANGE → PINCODE
    ========================================================== */

    locationSelect.addEventListener(
        'change',
        function () {

            const selectedOption =
                this.options[
                    this.selectedIndex
                ];


            if (!selectedOption) {

                pincode.value = '';

                return;

            }


            pincode.value =
                selectedOption.dataset.pincode || '';

        }
    );


    /* =========================================================
       INITIAL LOCATION LOAD
    ========================================================== */

    const initialStateId =
        stateSelect.value || 1;


    console.log(
        '========================================'
    );

    console.log(
        'INITIAL STATE ID:',
        initialStateId
    );

    console.log(
        'OLD DISTRICT ID:',
        oldDistrictId
    );

    console.log(
        'OLD TALUKA ID:',
        oldTalukaId
    );

    console.log(
        'OLD LOCATION:',
        oldLocation
    );

    console.log(
        '========================================'
    );


    loadDistricts(
        initialStateId,
        oldDistrictId,
        oldTalukaId,
        oldLocation
    );


    /* =========================================================
       FORM SUBMIT VALIDATION
    ========================================================== */

    const studentForm =
        document.getElementById('studentForm');


    studentForm.addEventListener(
        'submit',
        function (event) {

            /* IMPORTANT:
               Convert API selected IDs into database names
            */

            syncLocationNames();


            let valid = true;


            if (
                aadhaar.value.trim() !== '' &&
                !/^\d{12}$/.test(
                    aadhaar.value.trim()
                )
            ) {

                aadhaar.classList.add(
                    'is-invalid'
                );

                aadhaarError.classList.remove(
                    'd-none'
                );

                valid = false;

            }


            if (
                phone.value.trim() !== '' &&
                !/^[6-9]\d{9}$/.test(
                    phone.value.trim()
                )
            ) {

                phone.classList.add(
                    'is-invalid'
                );

                phoneError.classList.remove(
                    'd-none'
                );

                valid = false;

            }


            document
                .querySelectorAll('.parent-phone')
                .forEach(function (input) {

                    if (
                        input.value.trim() !== '' &&
                        !/^[6-9]\d{9}$/.test(
                            input.value.trim()
                        )
                    ) {

                        input.classList.add(
                            'is-invalid'
                        );

                        valid = false;

                    }

                });


            if (!valid) {

                event.preventDefault();

                alert(
                    'Please correct the highlighted fields before submitting the form.'
                );

                return false;

            }


            rollNumber.disabled = false;

        }
    );


    /* =========================================================
       REMOVE INVALID STATE
    ========================================================== */

    document
        .querySelectorAll(
            '.form-control, .form-select'
        )
        .forEach(function (field) {

            field.addEventListener(
                'input',
                function () {

                    this.classList.remove(
                        'is-invalid'
                    );

                }
            );


            field.addEventListener(
                'change',
                function () {

                    this.classList.remove(
                        'is-invalid'
                    );

                }
            );

        });


    /* =========================================================
       INITIAL TAB
    ========================================================== */

    showTab(0);

});

</script>

@endsection