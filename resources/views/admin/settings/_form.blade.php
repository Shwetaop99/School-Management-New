@php
    $school = $school ?? null;
@endphp


{{-- =========================================================
     ERROR SUMMARY
========================================================== --}}

@if($errors->any())

    <div class="alert alert-danger shadow-sm rounded-3 mb-4">

        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
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
     SCHOOL IDENTITY
========================================================== --}}

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 pt-4 px-4">

        <h5 class="fw-bold mb-1">
            <i class="bi bi-image text-primary me-2"></i>
            School Identity
        </h5>

        <p class="text-muted small mb-0">
            Upload your official school logo and identification details.
        </p>

    </div>


    <div class="card-body p-4">

        <div class="row g-4">

            <div class="col-lg-4">

                <label class="form-label fw-semibold">
                    School Logo
                </label>

                <div class="logo-upload-box">

                    <div class="logo-preview-wrapper">

                        <img
                            id="schoolLogoPreview"
                            src="{{ $school?->logo
                                ? asset('storage/' . $school->logo)
                                : asset('images/gurukullogo.png') }}"
                            alt="School Logo"
                            onerror="this.src='{{ asset('images/gurukullogo.png') }}'"
                        >

                    </div>

                    <label for="schoolLogo"
                           class="btn btn-outline-primary btn-sm mt-3">

                        <i class="bi bi-cloud-arrow-up me-1"></i>
                        Choose Logo

                    </label>

                    <input
                        type="file"
                        name="logo"
                        id="schoolLogo"
                        class="d-none"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <div class="small text-muted mt-2">
                        JPG, PNG or WEBP
                        <br>
                        Maximum 5 MB
                    </div>

                </div>

            </div>


            <div class="col-lg-8">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        School Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="school_name"
                        class="form-control form-control-lg"
                        value="{{ old('school_name', $school?->school_name) }}"
                        placeholder="Enter school name"
                        required
                    >

                    @error('school_name')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            School Code
                        </label>

                        <input
                            type="text"
                            name="school_code"
                            class="form-control"
                            value="{{ old('school_code', $school?->school_code) }}"
                            placeholder="School code"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            UDISE Code
                        </label>

                        <input
                            type="text"
                            name="udise_code"
                            class="form-control"
                            value="{{ old('udise_code', $school?->udise_code) }}"
                            placeholder="UDISE code"
                        >

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     ADDRESS
========================================================== --}}

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 pt-4 px-4">

        <h5 class="fw-bold mb-1">
            <i class="bi bi-geo-alt text-primary me-2"></i>
            School Address
        </h5>

    </div>


    <div class="card-body p-4">

        <div class="row g-3">

            <div class="col-12">

                <label class="form-label fw-semibold">
                    Address
                </label>

                <textarea
                    name="address"
                    class="form-control"
                    rows="3"
                    placeholder="Enter complete school address"
                >{{ old('address', $school?->address) }}</textarea>

            </div>


            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    City
                </label>

                <input
                    type="text"
                    name="city"
                    class="form-control"
                    value="{{ old('city', $school?->city) }}"
                    placeholder="City"
                >

            </div>


            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    District
                </label>

                <input
                    type="text"
                    name="district"
                    class="form-control"
                    value="{{ old('district', $school?->district) }}"
                    placeholder="District"
                >

            </div>


            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    State
                </label>

                <input
                    type="text"
                    name="state"
                    class="form-control"
                    value="{{ old('state', $school?->state ?? 'Maharashtra') }}"
                    placeholder="State"
                >

            </div>


            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    Pincode
                </label>

                <input
                    type="text"
                    name="pincode"
                    class="form-control"
                    value="{{ old('pincode', $school?->pincode) }}"
                    placeholder="6 digit pincode"
                    maxlength="6"
                    inputmode="numeric"
                >

                @error('pincode')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     CONTACT
========================================================== --}}

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 pt-4 px-4">

        <h5 class="fw-bold mb-1">
            <i class="bi bi-telephone text-primary me-2"></i>
            Contact Information
        </h5>

    </div>


    <div class="card-body p-4">

        <div class="row g-3">

            <div class="col-md-4">

                <label class="form-label fw-semibold">
                    Phone Number
                </label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone', $school?->phone) }}"
                    placeholder="10 digit phone number"
                    maxlength="10"
                    inputmode="numeric"
                >

                @error('phone')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="col-md-4">

                <label class="form-label fw-semibold">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $school?->email) }}"
                    placeholder="school@example.com"
                >

                @error('email')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="col-md-4">

                <label class="form-label fw-semibold">
                    Website
                </label>

                <input
                    type="url"
                    name="website"
                    class="form-control"
                    value="{{ old('website', $school?->website) }}"
                    placeholder="https://example.com"
                >

                @error('website')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     ADMINISTRATION
========================================================== --}}

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 pt-4 px-4">

        <h5 class="fw-bold mb-1">
            <i class="bi bi-person-badge text-primary me-2"></i>
            School Administration
        </h5>

    </div>


    <div class="card-body p-4">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Principal Name
                </label>

                <input
                    type="text"
                    name="principal_name"
                    class="form-control"
                    value="{{ old('principal_name', $school?->principal_name) }}"
                    placeholder="Principal name"
                >

            </div>


            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Established Year
                </label>

                <input
                    type="number"
                    name="established_year"
                    class="form-control"
                    value="{{ old('established_year', $school?->established_year) }}"
                    min="1800"
                    max="{{ now()->year }}"
                    placeholder="YYYY"
                >

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     BUTTONS
========================================================== --}}

<div class="d-flex justify-content-end gap-2 mb-5">

    <a href="{{ route('admin.settings.index') }}"
       class="btn btn-light border px-4">

        <i class="bi bi-x-circle me-1"></i>
        Cancel

    </a>

    <button
        type="submit"
        class="btn btn-primary px-4"
        id="saveSchoolBtn"
    >

        <i class="bi bi-check-circle me-1"></i>

        {{ $buttonText }}

    </button>

</div>


<style>

.logo-upload-box {
    min-height: 230px;
    border: 2px dashed #d9e2ec;
    border-radius: 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: #f8fafc;
    text-align: center;
}

.logo-upload-box:hover {
    border-color: #0d6efd;
    background: #f5f9ff;
}

.logo-preview-wrapper {
    width: 130px;
    height: 130px;
    border-radius: 16px;
    background: #fff;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.logo-preview-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 8px;
}

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const logoInput = document.getElementById('schoolLogo');
    const logoPreview = document.getElementById('schoolLogoPreview');
    const form = document.getElementById('schoolForm');
    const saveButton = document.getElementById('saveSchoolBtn');


    /*
    |--------------------------------------------------------------------------
    | Logo Preview
    |--------------------------------------------------------------------------
    */

    if (logoInput && logoPreview) {

        logoInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }


            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];


            if (!allowedTypes.includes(file.type)) {

                alert('Please select JPG, JPEG, PNG or WEBP.');

                this.value = '';

                return;
            }


            if (file.size > 5 * 1024 * 1024) {

                alert('Logo size must not exceed 5 MB.');

                this.value = '';

                return;
            }


            const reader = new FileReader();

            reader.onload = function (event) {

                logoPreview.src = event.target.result;

            };

            reader.readAsDataURL(file);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Multiple Submissions
    |--------------------------------------------------------------------------
    */

    if (form && saveButton) {

        form.addEventListener('submit', function () {

            saveButton.disabled = true;

            saveButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Saving...';

        });

    }

});

</script>