@extends('layouts.app')

@section('title', 'Student Registration | Admin')

@section('content')

<div class="student-create-page">
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

                            
<div class="col-md-4">

    <label class="form-label">
        Birth Place
    </label>

    <input type="text"
           name="birth_place"
           id="birth_place"
           class="form-control"
           value="{{ old('birth_place') }}"
           placeholder="Enter Birth Place">

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
        Section
        <span class="text-danger">*</span>
    </label>

    <select name="section"
            id="section"
            class="form-select"
            required>

        <option value="">Select Section</option>

    </select>

</div>

<div class="col-md-4">

    <label class="form-label">
        Roll Number
    </label>

    <input type="text"
           name="roll_number"
           id="roll_number"
           class="form-control"
           value="{{ old('roll_number') }}"
           placeholder="Enter Roll Number">

</div>


<div class="col-md-4">

    <label class="form-label">
        Admission Class
    </label>

    <select name="admission_class"
            id="admission_class"
            class="form-select">

        <option value="">Select Class</option>

        @foreach($classes as $class)
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
           value="{{ old('register_no') }}"
           placeholder="Enter Register Number">

</div>


                    <div class="col-md-4">

    <label class="form-label">
        Book Number
    </label>

    <input type="text"
           name="book_no"
           id="book_no"
           class="form-control"
           value="{{ old('book_no') }}"
           placeholder="Enter Book Number">

</div>


                <div class="col-md-4">

    <label class="form-label">
        APAAR ID
    </label>

    <input type="text"
           name="appar_id"
           id="appar_id"
           class="form-control"
           value="{{ old('appar_id') }}"
           placeholder="Enter APAAR ID">

</div>


                    <div class="col-md-4">

    <label class="form-label">
        PEN Number
    </label>

    <input type="text"
           name="pen_no"
           id="pen_no"
           class="form-control"
           value="{{ old('pen_no') }}"
           placeholder="Enter PEN Number">

</div>

<div class="col-md-4">

    <label class="form-label">
        Saral ID
    </label>

    <input type="text"
           name="saral_id"
           id="saral_id"
           class="form-control"
           value="{{ old('saral_id') }}"
           placeholder="Enter Saral ID">

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
</div>

{{-- =============================================================
STYLES — SCHOOL DIARY DESIGN SYSTEM (same as Transport Records)
============================================================= --}}

<style>

/* ==========================================================
   STUDENT REGISTRATION — SCHOOL DIARY DESIGN SYSTEM
   Scoped to .student-create-page so nothing leaks to other pages
========================================================== */

.student-create-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #6b756a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --green-dark: #347849;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', 'Inter', sans-serif;
    font-size: 13px;
}

.student-create-page *,
.student-create-page *::before,
.student-create-page *::after {
    box-sizing: border-box;
}

.student-create-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

/* ---------- PAGE HEADER ---------- */

.student-create-page h3 {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(22px, 2vw, 28px);
    font-weight: 700;
    letter-spacing: -.5px;
}

.student-create-page h3 .text-primary,
.student-create-page .text-primary {
    color: var(--green) !important;
}

.student-create-page .text-muted {
    color: var(--muted) !important;
}

.student-create-page .text-danger {
    color: var(--red) !important;
}

.student-create-page small,
.student-create-page .small {
    font-size: 11px;
}

/* ---------- BUTTONS ---------- */

.student-create-page .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 42px;
    padding: 0 17px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.student-create-page .btn i {
    margin: 0 !important;
}

.student-create-page .btn-primary {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.student-create-page .btn-primary:hover,
.student-create-page .btn-primary:focus {
    transform: translateY(-1px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.student-create-page .btn-success {
    border-color: var(--green);
    color: #fff;
    background: var(--green);
    box-shadow: 0 3px 8px rgba(67, 131, 93, .15);
}

.student-create-page .btn-success:hover,
.student-create-page .btn-success:focus {
    transform: translateY(-1px);
    border-color: var(--green-dark);
    color: #fff;
    background: var(--green-dark);
    box-shadow: 0 6px 15px rgba(52, 120, 73, .2);
}

.student-create-page .btn-outline-secondary,
.student-create-page .btn-light {
    border-color: #deded2;
    color: #5d6b60;
    background: var(--paper);
}

.student-create-page .btn-outline-secondary:hover,
.student-create-page .btn-outline-secondary:focus,
.student-create-page .btn-light:hover,
.student-create-page .btn-light:focus {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* ---------- ALERTS ---------- */

.student-create-page .alert {
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
}

.student-create-page .alert-success {
    border: 1px solid #d2e5d1;
    color: var(--green-dark);
    background: #f1f8ef;
}

.student-create-page .alert-danger {
    border: 1px solid #eed6cf;
    color: var(--red);
    background: #fff1ed;
}

.student-create-page .alert-danger li {
    font-weight: 600;
}

/* ---------- MAIN CARD ---------- */

.student-registration-card {
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* ---------- TABS ---------- */

.registration-tabs {
    display: flex;
    overflow-x: auto;
    border-bottom: 1px solid var(--line);
    background: #f1f2e9;
    -webkit-overflow-scrolling: touch;
}

.registration-tab {
    display: flex;
    align-items: center;
    flex: 1;
    gap: 12px;
    min-width: 190px;
    padding: 18px 16px;
    border: 0;
    border-bottom: 3px solid transparent;
    color: #637166;
    background: transparent;
    font-family: inherit;
    text-align: left;
    transition: color .2s ease, background .2s ease, border-color .2s ease;
}

.registration-tab:hover {
    background: #f6f6ee;
}

.registration-tab.active {
    border-bottom-color: var(--green);
    color: var(--ink);
    background: var(--paper);
}

.registration-tab .tab-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    border: 1px solid #e0e3d7;
    border-radius: 10px;
    color: var(--ink);
    background: #edf1e7;
    font-size: 13px;
    font-weight: 800;
}

.registration-tab.active .tab-number {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.registration-tab strong {
    display: block;
    font-size: 12px;
    font-weight: 800;
}

.registration-tab small {
    display: block;
    margin-top: 2px;
    color: #758176;
    font-size: 10px;
}

/* ---------- BODY ---------- */

.registration-body {
    padding: 30px;
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
    margin-bottom: 28px;
    padding-bottom: 18px;
    border-bottom: 1px dashed #ddd8ca;
}

.section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 18px;
}

.section-heading h5 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.section-heading p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

/* ---------- FORM FIELDS ---------- */

.student-create-page .form-label {
    margin-bottom: 7px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.student-create-page .form-control,
.student-create-page .form-select {
    min-height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background-color: var(--paper);
    font-family: inherit;
    font-size: 13px;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.student-create-page textarea.form-control {
    min-height: 100px;
    line-height: 1.6;
}

.student-create-page .form-control::placeholder {
    color: #8a9086;
}

.student-create-page .form-control:hover,
.student-create-page .form-select:hover {
    border-color: #bdcabb;
}

.student-create-page .form-control:focus,
.student-create-page .form-select:focus {
    border-color: #739780;
    background-color: var(--paper);
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.student-create-page .form-control[readonly],
.student-create-page .form-control:disabled,
.student-create-page .form-select:disabled {
    color: #69766d;
    background-color: var(--chalk);
    cursor: not-allowed;
}

.student-create-page .form-control[readonly] {
    cursor: default;
}

.student-create-page .form-control.is-invalid,
.student-create-page .form-select.is-invalid {
    border-color: var(--red);
    background-color: #fff8f5;
}

.student-create-page .form-control.is-invalid:focus,
.student-create-page .form-select.is-invalid:focus {
    border-color: var(--red);
    box-shadow: 0 0 0 3px rgba(185, 91, 80, .1);
}

.student-create-page input[type="file"].form-control {
    padding-top: 10px;
    font-size: 12px;
}

/* ---------- PHOTO UPLOAD ---------- */

.photo-upload-box {
    padding: 15px;
    border: 1px dashed #ddd8ca;
    border-radius: 12px;
    background: #faf9f3;
}

.image-preview-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 230px;
    overflow: hidden;
    border: 1px solid #e3e1d6;
    border-radius: 10px;
    background: var(--chalk);
}

.image-preview-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #829083;
    font-size: 12px;
    font-weight: 700;
}

.image-placeholder i {
    font-size: 48px;
}

/* ---------- PARENT CARDS ---------- */

.parent-card {
    padding: 20px;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: #faf9f3;
}

.parent-card h6 {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px dashed #ddd8ca;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 15px;
    font-weight: 700;
}

.parent-card h6 i {
    color: var(--green);
}

/* ---------- FOOTER ---------- */

.registration-footer {
    display: flex;
    align-items: center;
    padding: 18px 30px;
    border-top: 1px dashed #ddd7c9;
    background: #f8f7f0;
}

.registration-footer .btn {
    min-width: 110px;
}

/* ---------- LOADING / LOCATION ---------- */

#districtLoading,
#talukaLoading,
#locationLoading {
    color: var(--green) !important;
    font-size: 11px;
}

.location-loaded {
    background: #f1f8ef;
}

/* ---------- RESPONSIVE ---------- */

@media (max-width: 1200px) {
    .registration-body {
        padding: 24px;
    }
}

@media (max-width: 768px) {
    .student-create-page .container-fluid {
        padding: 18px 14px !important;
    }

    .registration-body {
        padding: 20px 16px;
    }

    .registration-tabs {
        flex-direction: column;
    }

    .registration-tab {
        width: 100%;
        min-width: 0;
        border-bottom: 1px solid var(--line);
        border-left: 3px solid transparent;
    }

    .registration-tab.active {
        border-bottom-color: var(--line);
        border-left-color: var(--green);
    }

    .registration-footer {
        padding: 15px;
    }

    /* Prevent iOS zoom on input focus */
    .student-create-page .form-control,
    .student-create-page .form-select {
        font-size: 16px;
    }

    .section-heading h5 {
        font-size: 17px;
    }
}

@media (max-width: 480px) {
    .registration-footer {
        flex-direction: column-reverse;
        align-items: stretch;
        gap: 10px;
    }

    .registration-footer > .ms-auto {
        flex-direction: column-reverse;
        margin-left: 0 !important;
    }

    .registration-footer .btn {
        width: 100%;
    }
}

/* ---------- ACCESSIBILITY ---------- */

.student-create-page a:focus-visible,
.student-create-page button:focus-visible,
.student-create-page input:focus-visible,
.student-create-page select:focus-visible,
.student-create-page textarea:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .student-create-page *,
    .student-create-page *::before,
    .student-create-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
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

    const tabs = Array.from(document.querySelectorAll('.registration-tab'));
    const sections = Array.from(document.querySelectorAll('.tab-content-section'));

    const previousBtn = document.getElementById('previousBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    let currentTab = 0;

    function showTab(index) {

        if (index < 0) index = 0;
        if (index >= tabs.length) index = tabs.length - 1;

        currentTab = index;

        tabs.forEach((tab, i) => {
            tab.classList.toggle('active', i === currentTab);
        });

        sections.forEach((section, i) => {
            section.classList.toggle('active', i === currentTab);
        });

        if (previousBtn) {
            previousBtn.style.display =
                currentTab === 0 ? 'none' : 'inline-flex';
        }

        if (nextBtn) {
            nextBtn.style.display =
                currentTab === tabs.length - 1
                    ? 'none'
                    : 'inline-flex';
        }

        if (submitBtn) {
            submitBtn.style.display =
                currentTab === tabs.length - 1
                    ? 'inline-flex'
                    : 'none';
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }


    function validateCurrentTab() {

        const section = sections[currentTab];

        if (!section) {
            return true;
        }

        const requiredFields = section.querySelectorAll(
            'input[required], select[required], textarea[required]'
        );

        let valid = true;

        requiredFields.forEach(field => {

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

        return true;
    }


    if (nextBtn) {
        nextBtn.addEventListener('click', function () {

            if (validateCurrentTab()) {
                showTab(currentTab + 1);
            }

        });
    }


    if (previousBtn) {
        previousBtn.addEventListener('click', function () {

            /*
             * IMPORTANT:
             * Do NOT reset the form here.
             * All entered values remain in the inputs.
             */

            showTab(currentTab - 1);

        });
    }


    tabs.forEach((tab, index) => {

        tab.addEventListener('click', function () {
            showTab(index);
        });

    });


    if (cancelBtn) {

        cancelBtn.addEventListener('click', function () {

            window.location.href =
                "{{ route('admin.students.index') }}";

        });

    }


    /* =========================================================
       ADMISSION DATE
       TODAY ONLY FOR NEW/EMPTY FORM
    ========================================================== */

    const admissionDate =
        document.getElementById('admission_date');

    if (admissionDate && !admissionDate.value) {

        const today = new Date();

        const year = today.getFullYear();

        const month = String(
            today.getMonth() + 1
        ).padStart(2, '0');

        const day = String(
            today.getDate()
        ).padStart(2, '0');

        admissionDate.value =
            `${year}-${month}-${day}`;
    }


    /* =========================================================
       NUMERIC INPUT
    ========================================================== */

    function numericOnly(element, maxLength = null) {

        if (!element) return;

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
        .forEach(input => {
            numericOnly(input, 10);
        });


    /* =========================================================
       AADHAAR
    ========================================================== */

    const aadhaar =
        document.getElementById('aadhar_card_no');

    const aadhaarError =
        document.getElementById('aadharError');

    if (aadhaar) {

        aadhaar.addEventListener('input', function () {

            this.classList.remove('is-invalid');

            if (aadhaarError) {
                aadhaarError.classList.add('d-none');
            }

        });
    }


    /* =========================================================
       PHONE
    ========================================================== */

    const phone =
        document.getElementById('phone');

    const phoneError =
        document.getElementById('phoneError');

    if (phone) {

        phone.addEventListener('input', function () {

            this.classList.remove('is-invalid');

            if (phoneError) {
                phoneError.classList.add('d-none');
            }

        });
    }


    /* =========================================================
       PHOTO PREVIEW
    ========================================================== */

    const profileImage =
        document.getElementById('profile_image');

    const imagePreview =
        document.getElementById('imagePreview');

    const imagePlaceholder =
        document.getElementById('imagePlaceholder');

    if (profileImage) {

        profileImage.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {

                if (imagePreview) {
                    imagePreview.style.display = 'none';
                    imagePreview.src = '';
                }

                if (imagePlaceholder) {
                    imagePlaceholder.style.display = 'flex';
                }

                return;
            }

            if (!file.type.startsWith('image/')) {

                this.value = '';

                alert('Please select a valid image file.');

                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                if (imagePreview) {

                    imagePreview.src =
                        event.target.result;

                    imagePreview.style.display =
                        'block';
                }

                if (imagePlaceholder) {

                    imagePlaceholder.style.display =
                        'none';
                }
            };

            reader.readAsDataURL(file);
        });
    }


    /* =========================================================
       MARATHI NAME
    ========================================================== */

    const firstName =
        document.getElementById('first_name');

    const middleName =
        document.getElementById('middle_name');

    const lastName =
        document.getElementById('last_name');

    const marathiName =
        document.getElementById('marathi_name');

    if (
        firstName &&
        middleName &&
        lastName &&
        marathiName
    ) {

        let transliterationTimer = null;
        let requestCounter = 0;

        async function transliterateToMarathi(text) {

            const value = text.trim();

            if (!value) {
                return '';
            }

            const url =
                'https://inputtools.google.com/request' +
                '?text=' + encodeURIComponent(value) +
                '&ime=transliteration_en_mr' +
                '&num=1' +
                '&ie=utf-8' +
                '&oe=utf-8' +
                '&app=jsapi';

            try {

                const response =
                    await fetch(url);

                if (!response.ok) {
                    return '';
                }

                const data =
                    await response.json();

                if (
                    !Array.isArray(data) ||
                    data[0] !== 'SUCCESS' ||
                    !Array.isArray(data[1])
                ) {
                    return '';
                }

                return data[1]
                    .map(item => {

                        if (
                            Array.isArray(item) &&
                            Array.isArray(item[1]) &&
                            item[1].length
                        ) {
                            return item[1][0];
                        }

                        return '';
                    })
                    .join(' ')
                    .replace(/\s+/g, ' ')
                    .trim();

            } catch (error) {

                console.error(
                    'Marathi transliteration error:',
                    error
                );

                return '';
            }
        }


        async function generateMarathiName() {

            if (marathiName.dataset.manual === '1') {
                return;
            }

            const request =
                ++requestCounter;

            const parts = [
                firstName.value.trim(),
                middleName.value.trim(),
                lastName.value.trim()
            ].filter(Boolean);

            if (!parts.length) {

                marathiName.value = '';

                return;
            }

            const translatedParts = [];

            for (const part of parts) {

                const translated =
                    await transliterateToMarathi(part);

                if (request !== requestCounter) {
                    return;
                }

                translatedParts.push(
                    translated || part
                );
            }

            if (marathiName.dataset.manual === '1') {
                return;
            }

            marathiName.value =
                translatedParts.join(' ')
                    .replace(/\s+/g, ' ')
                    .trim();
        }


        function scheduleMarathiGeneration() {

            marathiName.dataset.manual = '0';

            clearTimeout(transliterationTimer);

            transliterationTimer =
                setTimeout(
                    generateMarathiName,
                    350
                );
        }


        [
            firstName,
            middleName,
            lastName
        ].forEach(input => {

            input.addEventListener(
                'input',
                scheduleMarathiGeneration
            );

        });


        marathiName.addEventListener(
            'input',
            function () {

                this.dataset.manual = '1';

            }
        );
    }


    /* =========================================================
       ADMISSION CLASS / SECTION
       SOURCE = school_classes
    ========================================================== */

    const classSelect =
        document.getElementById('admission_class');

    const sectionSelect =
        document.getElementById('section');

    const sectionsByClass =
        @json($sectionsByClass ?? []);

    console.log(
        'Admission Classes:',
        sectionsByClass
    );


    function loadSections(selectedSection = '') {

        if (!classSelect || !sectionSelect) {
            return;
        }

        const selectedClass =
            classSelect.value;

        sectionSelect.innerHTML =
            '<option value="">Select Section</option>';

        if (
            !selectedClass ||
            !sectionsByClass[selectedClass]
        ) {

            sectionSelect.disabled = true;

            return;
        }

        const sections =
            sectionsByClass[selectedClass];

        sections.forEach(section => {

            const option =
                document.createElement('option');

            option.value = section;

            option.textContent = section;

            if (
                String(section) ===
                String(selectedSection)
            ) {

                option.selected = true;
            }

            sectionSelect.appendChild(option);
        });

        sectionSelect.disabled = false;
    }


    if (classSelect) {

        classSelect.addEventListener(
            'change',
            function () {

                loadSections('');

            }
        );
    }


    loadSections(
        @json(old('section'))
    );


    /* =========================================================
       MANUAL ROLL NUMBER
    ========================================================== */

    const rollNumber =
        document.getElementById('roll_number');

    if (rollNumber) {

        rollNumber.disabled = false;

    }


    /* =========================================================
   LOCATION API
========================================================== */

const stateSelect = document.getElementById('state_id');

console.log('STATE SELECT:', stateSelect);
console.log('STATE ID:', stateSelect ? stateSelect.value : 'NOT FOUND');
console.log(
    'DISTRICT URL:',
    "{{ route('admin.locations.districts') }}?state_id=" +
    (stateSelect ? stateSelect.value : '')
);

const districtSelect = document.getElementById('district_id');
const talukaSelect = document.getElementById('taluka_id');
const locationSelect = document.getElementById('city_village');

const pincode = document.getElementById('pincode');

const stateName = document.getElementById('state');
const districtName = document.getElementById('district');
const talukaName = document.getElementById('taluka');

const oldDistrictId = @json(old('district_id'));
const oldTalukaId = @json(old('taluka_id'));
const oldLocation = @json(old('city_village'));
const oldPincode = @json(old('pincode'));


/* =========================================================
   SYNC LOCATION NAMES
========================================================== */

function syncLocationNames() {

    if (
        stateSelect &&
        stateName &&
        stateSelect.value
    ) {

        const option =
            stateSelect.options[stateSelect.selectedIndex];

        stateName.value =
            option ? option.text.trim() : '';
    }


    if (
        districtSelect &&
        districtName &&
        districtSelect.value
    ) {

        const option =
            districtSelect.options[
                districtSelect.selectedIndex
            ];

        districtName.value =
            option ? option.text.trim() : '';
    }


    if (
        talukaSelect &&
        talukaName &&
        talukaSelect.value
    ) {

        const option =
            talukaSelect.options[
                talukaSelect.selectedIndex
            ];

        talukaName.value =
            option ? option.text.trim() : '';
    }
}


/* =========================================================
   GET LOCATION API JSON
========================================================== */

async function getLocationApi(url) {

    console.log('LOCATION API URL:', url);

    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    const text = await response.text();

    console.log(
        'LOCATION API STATUS:',
        response.status
    );

    console.log(
        'LOCATION API RESPONSE:',
        text
    );

    if (!response.ok) {

        throw new Error(
            'HTTP ' + response.status
        );
    }

    if (!text.trim()) {

        throw new Error(
            'Empty API response'
        );
    }

    let result;

try {
    // Remove UTF-8 BOM before parsing JSON
    const cleanText = text.replace(/^\uFEFF/, '').trim();

    result = JSON.parse(cleanText);
} catch (error) {
    console.error('JSON PARSE ERROR:', error);
    throw new Error('Invalid JSON response');
}


    /*
     * Your API format:
     *
     * {
     *     success: true,
     *     data: [...]
     * }
     */

    if (
        !result ||
        result.success !== true ||
        !Array.isArray(result.data)
    ) {

        console.error(
            'INVALID API DATA:',
            result
        );

        throw new Error(
            'Invalid location API data'
        );
    }


    return result.data;
}


/* =========================================================
   LOAD DISTRICTS
========================================================== */

async function loadDistricts(
    stateId,
    selectedDistrict = '',
    selectedTaluka = '',
    selectedLocation = ''
) {

    if (!districtSelect) {
        return;
    }


    districtSelect.innerHTML =
        '<option value="">Loading districts...</option>';

    districtSelect.disabled = true;


    if (talukaSelect) {

        talukaSelect.innerHTML =
            '<option value="">Select District First</option>';

        talukaSelect.disabled = true;
    }


    if (locationSelect) {

        locationSelect.innerHTML =
            '<option value="">Select Taluka First</option>';

        locationSelect.disabled = true;
    }


    if (pincode) {
        pincode.value = '';
    }


    if (!stateId) {

        districtSelect.innerHTML =
            '<option value="">Select District</option>';

        districtSelect.disabled = true;

        return;
    }


    try {

        const url =
            "{{ route('admin.locations.districts') }}" +
            '?state_id=' +
            encodeURIComponent(stateId);


        const districts =
            await getLocationApi(url);


        console.log(
            'DISTRICTS:',
            districts
        );


        districtSelect.innerHTML =
            '<option value="">Select District</option>';


        districts.forEach(function (district) {

            const option =
                document.createElement('option');

            option.value =
                district.id;

            option.textContent =
                district.district_name;


            if (
                String(district.id) ===
                String(selectedDistrict)
            ) {

                option.selected = true;
            }


            districtSelect.appendChild(option);
        });


        districtSelect.disabled = false;

        syncLocationNames();


        /*
         * Only load old values during
         * initial page loading.
         */

        if (selectedDistrict) {

            await loadTalukas(
                selectedDistrict,
                selectedTaluka,
                selectedLocation
            );
        }

    } catch (error) {

        console.error(
            'District API error:',
            error
        );


       districtSelect.innerHTML =
            '<option value="">Unable to load districts</option>';

        districtSelect.disabled = false;
    }
}


/* =========================================================
   LOAD TALUKAS / TEHSILS
========================================================== */

async function loadTalukas(
    districtId,
    selectedTaluka = '',
    selectedLocation = ''
) {

    if (!talukaSelect) {
        return;
    }


    if (!districtId) {

        talukaSelect.innerHTML =
            '<option value="">Select District First</option>';

        talukaSelect.disabled = true;

        return;
    }


    talukaSelect.innerHTML =
        '<option value="">Loading talukas...</option>';

    talukaSelect.disabled = true;


    if (locationSelect) {

        locationSelect.innerHTML =
            '<option value="">Select Taluka First</option>';

        locationSelect.disabled = true;
    }


    if (pincode) {
        pincode.value = '';
    }


    try {

        const url =
            "{{ route('admin.locations.tehsils') }}" +
            '?district_id=' +
            encodeURIComponent(districtId);


        const talukas =
            await getLocationApi(url);


        console.log(
            'TALUKAS:',
            talukas
        );


        talukaSelect.innerHTML =
            '<option value="">Select Taluka / Tehsil</option>';


        talukas.forEach(function (taluka) {

            const option =
                document.createElement('option');

            option.value =
                taluka.id;

            option.textContent =
                taluka.tehsil_name;


            if (
                String(taluka.id) ===
                String(selectedTaluka)
            ) {

                option.selected = true;
            }


            talukaSelect.appendChild(option);
        });


        talukaSelect.disabled = false;

        syncLocationNames();


        if (selectedTaluka) {

            await loadLocations(
                selectedTaluka,
                selectedLocation
            );
        }

    } catch (error) {

        console.error(
            'Taluka API error:',
            error
        );


        talukaSelect.innerHTML =
            '<option value="">Unable to load talukas</option>';

        talukaSelect.disabled = false;
    }
}


/* =========================================================
   LOAD CITY / VILLAGE
========================================================== */

async function loadLocations(
    talukaId,
    selectedLocation = ''
) {

    if (!locationSelect) {
        return;
    }


    if (!talukaId) {

        locationSelect.innerHTML =
            '<option value="">Select Taluka First</option>';

        locationSelect.disabled = true;

        return;
    }


    locationSelect.innerHTML =
        '<option value="">Loading locations...</option>';

    locationSelect.disabled = true;


    try {

        const url =
            "{{ route('admin.locations.locations') }}" +
            '?tehsil_id=' +
            encodeURIComponent(talukaId);


        const locations =
            await getLocationApi(url);


        console.log(
            'LOCATIONS:',
            locations
        );


        locationSelect.innerHTML =
            '<option value="">Select City / Village</option>';


        locations.forEach(function (location) {

            const option =
                document.createElement('option');


            /*
             * Database stores city_village
             * as the location name.
             */

            option.value =
                location.location_name;


            option.textContent =
                location.pin_code
                    ? location.location_name +
                      ' - ' +
                      location.pin_code
                    : location.location_name;


            option.dataset.locationId =
                location.id;


            option.dataset.pincode =
                location.pin_code || '';


            if (
                String(location.location_name) ===
                String(selectedLocation)
            ) {

                option.selected = true;

                if (
                    pincode &&
                    location.pin_code
                ) {

                    pincode.value =
                        location.pin_code;
                }
            }


            locationSelect.appendChild(option);
        });


        /*
         * Keep old location after
         * validation failure.
         */

        if (
            selectedLocation &&
            !Array.from(locationSelect.options)
                .some(function (option) {

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

            locationSelect.appendChild(option);


            if (
                pincode &&
                oldPincode
            ) {

                pincode.value =
                    oldPincode;
            }
        }


        locationSelect.disabled = false;

    } catch (error) {

        console.error(
            'Location API error:',
            error
        );


        locationSelect.innerHTML =
            '<option value="">Unable to load locations</option>';

        locationSelect.disabled = false;
    }
}


/* =========================================================
   STATE CHANGE
========================================================== */

if (stateSelect) {

    stateSelect.addEventListener(
        'change',
        function () {

            syncLocationNames();

            /*
             * New state means old
             * district/taluka/location
             * must be cleared.
             */

            loadDistricts(
                this.value,
                '',
                '',
                ''
            );
        }
    );
}


/* =========================================================
   DISTRICT CHANGE
========================================================== */

if (districtSelect) {

    districtSelect.addEventListener(
        'change',
        function () {

            syncLocationNames();

            /*
             * New district means
             * old taluka/location must
             * be cleared.
             */

            loadTalukas(
                this.value,
                '',
                ''
            );
        }
    );
}


/* =========================================================
   TALUKA CHANGE
========================================================== */

if (talukaSelect) {

    talukaSelect.addEventListener(
        'change',
        function () {

            syncLocationNames();

            /*
             * New taluka means
             * old location must be cleared.
             */

            loadLocations(
                this.value,
                ''
            );
        }
    );
}


/* =========================================================
   LOCATION CHANGE
========================================================== */

if (locationSelect) {

    locationSelect.addEventListener(
        'change',
        function () {

            const option =
                this.options[
                    this.selectedIndex
                ];


            if (
                option &&
                pincode
            ) {

                pincode.value =
                    option.dataset.pincode || '';
            }
        }
    );
}


/* =========================================================
   INITIAL LOCATION LOAD
========================================================== */

if (
    stateSelect &&
    stateSelect.value
) {

    loadDistricts(
        stateSelect.value,
        oldDistrictId,
        oldTalukaId,
        oldLocation
    );
}

    /* =========================================================
       FORM SUBMIT
    ========================================================== */

    const studentForm =
        document.getElementById('studentForm');

    if (studentForm) {

        studentForm.addEventListener(
            'submit',
            function (event) {

                syncLocationNames();

                let valid = true;


                if (
                    aadhaar &&
                    aadhaar.value.trim() &&
                    !/^\d{12}$/.test(
                        aadhaar.value.trim()
                    )
                ) {

                    aadhaar.classList.add(
                        'is-invalid'
                    );

                    valid = false;
                }


                if (
                    phone &&
                    phone.value.trim() &&
                    !/^[6-9]\d{9}$/.test(
                        phone.value.trim()
                    )
                ) {

                    phone.classList.add(
                        'is-invalid'
                    );

                    valid = false;
                }


                document
                    .querySelectorAll('.parent-phone')
                    .forEach(input => {

                        if (
                            input.value.trim() &&
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
                        'Please correct the highlighted fields.'
                    );

                    return;
                }


                /*
                 * Manual Roll Number
                 */

                if (rollNumber) {
                    rollNumber.disabled = false;
                }

            }
        );
    }


    /* =========================================================
       REMOVE INVALID STATE
    ========================================================== */

    document
        .querySelectorAll(
            '.form-control, .form-select'
        )
        .forEach(field => {

            field.addEventListener(
                'input',
                function () {
                    this.classList.remove('is-invalid');
                }
            );

            field.addEventListener(
                'change',
                function () {
                    this.classList.remove('is-invalid');
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