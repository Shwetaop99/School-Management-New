@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

{{-- PAGE HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-building-fill text-primary me-2"></i>
            Add New School
        </h3>

        <p class="text-muted mb-0">
            Create a school profile for this school.
        </p>
    </div>

    <a href="{{ route('admin.settings.index') }}"
       class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-1"></i>
        Back to School Profiles

    </a>

</div>


{{-- VALIDATION ERRORS --}}
@if ($errors->any())

    <div class="alert alert-danger alert-dismissible fade show shadow-sm">

        <div class="fw-semibold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please correct the following errors:
        </div>

        <ul class="mb-0">

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- CREATE SCHOOL FORM --}}
<form action="{{ route('admin.settings.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf


    {{-- =====================================================
         SCHOOL INFORMATION
    ====================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="fw-bold mb-1">
                <i class="bi bi-building text-primary me-2"></i>
                School Information
            </h5>

            <small class="text-muted">
                Enter the basic details of the school.
            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-4">


                {{-- SCHOOL NAME --}}
                <div class="col-md-8">

                    <label class="form-label fw-semibold">
                        School Name
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="school_name"
                           class="form-control"
                           value="{{ old('school_name') }}"
                           placeholder="Enter school name"
                           required>

                </div>


                {{-- SCHOOL CODE --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        School Code
                    </label>

                    <input type="text"
                           name="school_code"
                           class="form-control"
                           value="{{ old('school_code') }}"
                           placeholder="Enter school code">

                </div>


                {{-- UDISE CODE --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        UDISE Code
                    </label>

                    <input type="text"
                           name="udise_code"
                           class="form-control"
                           value="{{ old('udise_code') }}"
                           placeholder="Enter UDISE code">

                </div>


                {{-- PRINCIPAL --}}
                <div class="col-md-8">

                    <label class="form-label fw-semibold">
                        Principal Name
                    </label>

                    <input type="text"
                           name="principal_name"
                           class="form-control"
                           value="{{ old('principal_name') }}"
                           placeholder="Enter principal name">

                </div>


                {{-- ESTABLISHED YEAR --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Established Year
                    </label>

                    <input type="number"
                           name="established_year"
                           class="form-control"
                           value="{{ old('established_year') }}"
                           min="1800"
                           max="{{ date('Y') }}"
                           placeholder="e.g. 1995">

                </div>


            </div>

        </div>

    </div>



    {{-- =====================================================
         ADDRESS
    ====================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="fw-bold mb-1">
                <i class="bi bi-geo-alt text-primary me-2"></i>
                School Address
            </h5>

            <small class="text-muted">
                Enter the complete school location.
            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-4">


                {{-- ADDRESS --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">
                        Address
                    </label>

                    <textarea name="address"
                              rows="3"
                              class="form-control"
                              placeholder="Enter complete school address">{{ old('address') }}</textarea>

                </div>


                {{-- CITY --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        City
                    </label>

                    <input type="text"
                           name="city"
                           class="form-control"
                           value="{{ old('city') }}"
                           placeholder="City">

                </div>


                {{-- DISTRICT --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        District
                    </label>

                    <input type="text"
                           name="district"
                           class="form-control"
                           value="{{ old('district') }}"
                           placeholder="District">

                </div>


                {{-- STATE --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        State
                    </label>

                    <input type="text"
                           name="state"
                           class="form-control"
                           value="{{ old('state') }}"
                           placeholder="State">

                </div>


                {{-- PINCODE --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Pincode
                    </label>

                    <input type="text"
                           name="pincode"
                           class="form-control"
                           value="{{ old('pincode') }}"
                           placeholder="Pincode">

                </div>


            </div>

        </div>

    </div>



    {{-- =====================================================
         CONTACT INFORMATION
    ====================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="fw-bold mb-1">
                <i class="bi bi-telephone text-primary me-2"></i>
                Contact Information
            </h5>

            <small class="text-muted">
                Add the school's contact details.
            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-4">


                {{-- PHONE --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Phone Number
                    </label>

                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ old('phone') }}"
                           placeholder="Enter phone number">

                </div>


                {{-- EMAIL --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Email Address
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="school@example.com">

                </div>


                {{-- WEBSITE --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Website
                    </label>

                    <input type="text"
                           name="website"
                           class="form-control"
                           value="{{ old('website') }}"
                           placeholder="https://example.com">

                </div>


            </div>

        </div>

    </div>



    {{-- =====================================================
         SCHOOL LOGO
    ====================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="fw-bold mb-1">
                <i class="bi bi-image text-primary me-2"></i>
                School Logo
            </h5>

            <small class="text-muted">
                Upload the official school logo.
            </small>

        </div>


        <div class="card-body p-4">

            <div class="row">

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        School Logo
                    </label>

                    <input type="file"
                           name="logo"
                           class="form-control"
                           accept="image/png,image/jpeg,image/webp">

                    <small class="text-muted d-block mt-2">
                        Supported formats: JPG, JPEG, PNG, WEBP.
                        Maximum size: 2 MB.
                    </small>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
         FORM ACTIONS
    ====================================================== --}}

    <div class="d-flex justify-content-end gap-2 mb-4">

        <a href="{{ route('admin.settings.index') }}"
           class="btn btn-light border px-4">

            <i class="bi bi-x-circle me-1"></i>
            Cancel

        </a>


        <button type="submit"
                class="btn btn-primary px-4">

            <i class="bi bi-check-circle me-1"></i>
            Save School

        </button>

    </div>


</form>

</div>

@endsection
