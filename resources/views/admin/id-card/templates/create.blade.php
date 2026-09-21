```blade
@extends('layouts.app')

@section('title', 'Add ID Card Template')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-person-vcard me-2"></i>
                Add ID Card Template
            </h4>
            <p class="text-muted mb-0">
                Upload and create a new ID card design template.
            </p>
        </div>

        <a href="{{ route('admin.id-card.templates.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Templates
        </a>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-circle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row g-4">

        {{-- LEFT SIDE: FORM --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>
                        Template Information
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('admin.id-card.templates.store') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf


                        {{-- Template Name --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Template Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Example: Standard Student ID Card"
                                   maxlength="150"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Give a unique and meaningful name to the ID card design.
                            </div>
                        </div>


                        {{-- Academic Year --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Academic Year
                                <span class="text-danger">*</span>
                            </label>

                            <select name="academic_year"
                                    class="form-select form-select-lg @error('academic_year') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Academic Year
                                </option>

                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}"
                                        {{ old('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach

                            </select>

                            @error('academic_year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Status --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="status-option">
                                        <input type="radio"
                                               name="status"
                                               value="active"
                                               {{ old('status', 'active') === 'active' ? 'checked' : '' }}>

                                        <div class="status-box">
                                            <div class="status-icon active-icon">
                                                <i class="bi bi-check-circle"></i>
                                            </div>

                                            <div>
                                                <div class="fw-semibold">
                                                    Active
                                                </div>

                                                <small class="text-muted">
                                                    Template can be used for ID cards
                                                </small>
                                            </div>
                                        </div>
                                    </label>
                                </div>


                                <div class="col-md-6">
                                    <label class="status-option">
                                        <input type="radio"
                                               name="status"
                                               value="inactive"
                                               {{ old('status') === 'inactive' ? 'checked' : '' }}>

                                        <div class="status-box">
                                            <div class="status-icon inactive-icon">
                                                <i class="bi bi-pause-circle"></i>
                                            </div>

                                            <div>
                                                <div class="fw-semibold">
                                                    Inactive
                                                </div>

                                                <small class="text-muted">
                                                    Template will not be available
                                                </small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                            </div>

                            @error('status')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Template Image --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                ID Card Design
                                <span class="text-danger">*</span>
                            </label>

                            <div class="upload-area"
                                 id="uploadArea">

                                <input type="file"
                                       name="template_image"
                                       id="templateImage"
                                       accept=".jpg,.jpeg,.png,.webp"
                                       class="d-none"
                                       required>

                                <label for="templateImage"
                                       class="upload-content">

                                    <div class="upload-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>

                                    <h6 class="fw-bold mt-3 mb-1">
                                        Upload ID Card Design
                                    </h6>

                                    <p class="text-muted mb-2">
                                        Click to select your ID card design
                                    </p>

                                    <span class="badge bg-light text-dark">
                                        JPG / JPEG / PNG / WEBP
                                    </span>

                                    <div class="small text-muted mt-2">
                                        Maximum file size: 5 MB
                                    </div>

                                </label>
                            </div>

                            @error('template_image')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Image Preview --}}
                        <div id="imagePreviewContainer"
                             class="d-none mb-4">

                            <label class="form-label fw-semibold">
                                Design Preview
                            </label>

                            <div class="preview-wrapper">

                                <img id="imagePreview"
                                     src=""
                                     alt="ID Card Template Preview">

                            </div>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger mt-2"
                                    id="removeImage">

                                <i class="bi bi-trash me-1"></i>
                                Remove Image

                            </button>

                        </div>


                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">

                            <a href="{{ route('admin.id-card.templates.index') }}"
                               class="btn btn-light px-4">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary px-4"
                                    id="saveTemplateBtn">

                                <i class="bi bi-check-circle me-1"></i>
                                Save Template

                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>


        {{-- RIGHT SIDE: INFORMATION --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>
                        How It Works
                    </h5>
                </div>

                <div class="card-body">

                    <div class="step-item">

                        <div class="step-number">
                            1
                        </div>

                        <div>
                            <h6 class="fw-bold mb-1">
                                Create Template
                            </h6>

                            <p class="text-muted small mb-0">
                                Enter the template name, academic year
                                and status.
                            </p>
                        </div>

                    </div>


                    <div class="step-item">

                        <div class="step-number">
                            2
                        </div>

                        <div>
                            <h6 class="fw-bold mb-1">
                                Upload Design
                            </h6>

                            <p class="text-muted small mb-0">
                                Upload the blank ID card design that
                                will be used as the background.
                            </p>
                        </div>

                    </div>


                    <div class="step-item">

                        <div class="step-number">
                            3
                        </div>

                        <div>
                            <h6 class="fw-bold mb-1">
                                Configure Fields
                            </h6>

                            <p class="text-muted small mb-0">
                                After saving, you can place student
                                fields such as name, photo, class,
                                roll number and other information.
                            </p>
                        </div>

                    </div>


                    <div class="step-item">

                        <div class="step-number">
                            4
                        </div>

                        <div>
                            <h6 class="fw-bold mb-1">
                                Generate ID Cards
                            </h6>

                            <p class="text-muted small mb-0">
                                Select the template and student to
                                generate the final ID card.
                            </p>
                        </div>

                    </div>

                </div>
            </div>


            {{-- Supported Fields --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-list-check me-2"></i>
                        Available Student Fields
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-2">

                        @php
                            $fields = [
                                'Student Photo',
                                'Student Name',
                                'Student ID',
                                'Roll Number',
                                'Register Number',
                                'Class',
                                'Section',
                                'Date of Birth',
                                'Gender',
                                'Aadhaar Number',
                                'APAAR ID',
                                'Phone Number',
                                'Address',
                                'District',
                                'Taluka',
                                'Blood Group',
                                'Academic Year',
                            ];
                        @endphp

                        @foreach($fields as $field)

                            <div class="col-6">

                                <div class="field-badge">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ $field }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>


<style>

    .card {
        border-radius: 12px;
    }

    .status-option {
        display: block;
        cursor: pointer;
    }

    .status-option input {
        display: none;
    }

    .status-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        transition: all .2s ease;
        background: #fff;
    }

    .status-option input:checked + .status-box {
        border-color: #1677f0;
        background: #f5f9ff;
        box-shadow: 0 0 0 2px rgba(22, 119, 240, .08);
    }

    .status-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .active-icon {
        background: #e8f7ee;
        color: #198754;
    }

    .inactive-icon {
        background: #f1f3f5;
        color: #6c757d;
    }

    .upload-area {
        border: 2px dashed #ced4da;
        border-radius: 12px;
        background: #fafbfc;
        transition: all .2s ease;
    }

    .upload-area:hover {
        border-color: #1677f0;
        background: #f7faff;
    }

    .upload-content {
        width: 100%;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        display: block;
        margin: 0;
    }

    .upload-icon {
        width: 65px;
        height: 65px;
        margin: auto;
        border-radius: 50%;
        background: #eaf3ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .preview-wrapper {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .preview-wrapper img {
        max-width: 100%;
        max-height: 450px;
        object-fit: contain;
        border-radius: 6px;
    }

    .step-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .step-item:last-child {
        border-bottom: none;
    }

    .step-number {
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #1677f0;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .field-badge {
        padding: 8px 10px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 7px;
        font-size: 12px;
        color: #495057;
    }

    .field-badge i {
        color: #198754;
        margin-right: 4px;
    }

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const imageInput = document.getElementById('templateImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const uploadArea = document.getElementById('uploadArea');
    const removeImage = document.getElementById('removeImage');


    /*
    |--------------------------------------------------------------------------
    | Image Preview
    |--------------------------------------------------------------------------
    */

    imageInput.addEventListener('change', function () {

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

            alert('Please select a JPG, JPEG, PNG or WEBP image.');

            this.value = '';

            return;
        }


        if (file.size > 5 * 1024 * 1024) {

            alert('Maximum allowed file size is 5 MB.');

            this.value = '';

            return;
        }


        const reader = new FileReader();

        reader.onload = function (event) {

            imagePreview.src = event.target.result;

            previewContainer.classList.remove('d-none');

            uploadArea.classList.add('d-none');

        };

        reader.readAsDataURL(file);

    });


    /*
    |--------------------------------------------------------------------------
    | Remove Image
    |--------------------------------------------------------------------------
    */

    removeImage.addEventListener('click', function () {

        imageInput.value = '';

        imagePreview.src = '';

        previewContainer.classList.add('d-none');

        uploadArea.classList.remove('d-none');

    });


    /*
    |--------------------------------------------------------------------------
    | Submit Loading
    |--------------------------------------------------------------------------
    */

    const form = imageInput.closest('form');
    const saveButton = document.getElementById('saveTemplateBtn');

    form.addEventListener('submit', function () {

        saveButton.disabled = true;

        saveButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Saving...
        `;

    });

});

</script>

@endsection
