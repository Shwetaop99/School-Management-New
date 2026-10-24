@extends('layouts.app')

@section('title', 'Create ID Card Template | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — CREATE ID CARD TEMPLATE
       LOCKED VERSION
    ========================================================= */

    .idcard-create-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .idcard-create-page *,
    .idcard-create-page *::before,
    .idcard-create-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .idcard-header {
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

    .idcard-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .idcard-breadcrumb {
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

    .idcard-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .idcard-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .idcard-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .idcard-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .idcard-title-icon {
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

    .idcard-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .idcard-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    /* ---------- BUTTONS ---------- */
    .btn-scheme-back,
    .btn-scheme-save,
    .btn-scheme-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
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

    .btn-scheme-back,
    .btn-scheme-cancel {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .btn-scheme-back:hover,
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

    .btn-scheme-save:disabled {
        opacity: .7;
        cursor: not-allowed;
        transform: none;
    }

    /* ---------- ALERTS ---------- */
    .scheme-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.5;
    }

    .scheme-alert-success {
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-left: 4px solid #34775a;
        color: #245a43;
    }

    .scheme-alert-danger {
        background: #fff1ed;
        border: 1px solid #e8b8b0;
        border-left: 4px solid #bd5148;
        color: #8a3a34;
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

    /* ---------- FORM CARDS ---------- */
    .form-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        overflow: hidden;
        height: 100%;
    }

    .form-card-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 18px 20px;
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
        border-bottom: 1px solid #e9e5d9;
    }

    .form-card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 10px;
        font-size: 15px;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .07);
    }

    .form-card-header h5 {
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
    }

    .form-card-header small {
        display: block;
        margin-top: 3px;
        color: #7a857d;
        font-size: 10px;
    }

    .form-card-body {
        padding: 20px;
    }

    /* ---------- FORM FIELDS ---------- */
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
    .scheme-form-select {
        display: block;
        width: 100%;
        height: 42px;
        padding: 0 12px;
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

    .scheme-form-control::placeholder {
        color: #a0a397;
        font-weight: 400;
    }

    .scheme-form-control:hover,
    .scheme-form-select:hover {
        border-color: #c5d1c2;
    }

    .scheme-form-control:focus,
    .scheme-form-select:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .scheme-form-control.is-invalid,
    .scheme-form-select.is-invalid {
        border-color: #bd5148;
        background: #fff8f5;
    }

    .field-error {
        margin-top: 5px;
        color: #bd5148;
        font-size: 10px;
        line-height: 1.4;
    }

    /* ---------- INFO BOX ---------- */
    .info-box {
        padding: 14px 16px;
        border-radius: 10px;
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        margin-bottom: 18px;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        color: #34775a;
        font-size: 18px;
    }

    .info-box .fw-semibold {
        color: #263b35;
        font-size: 12px;
        font-weight: 700;
    }

    .info-box .small {
        color: #7a857d;
        font-size: 10px;
        line-height: 1.5;
    }

    /* ---------- UPLOAD AREA ---------- */
    .upload-area {
        min-height: 420px;
        width: 100%;
        border: 2px dashed #c5c0b0;
        border-radius: 12px;
        background: #faf8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: .2s ease;
        overflow: hidden;
        position: relative;
    }

    .upload-area:hover,
    .upload-area.dragover {
        border-color: #34775a;
        background: #f5f3e9;
    }

    .upload-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        color: #34775a;
        font-size: 30px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .08);
    }

    .upload-area h5 {
        margin: 0 0 6px;
        color: #263b35;
        font-size: 14px;
        font-weight: 750;
    }

    .upload-area p {
        margin: 0 0 6px;
        color: #7a857d;
        font-size: 12px;
    }

    .upload-area .small {
        color: #a0a397;
        font-size: 10px;
    }

    .preview-container {
        width: 100%;
        height: 100%;
        min-height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: #f0eee6;
    }

    .preview-container img {
        max-width: 100%;
        max-height: 420px;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
    }

    .preview-overlay {
        position: absolute;
        right: 16px;
        top: 16px;
    }

    .preview-overlay .badge {
        background: #263b35 !important;
        color: #fffefa;
        font-size: 10px;
        font-weight: 650;
        padding: 6px 12px;
        border-radius: 8px;
    }

    .selected-file-info {
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid #e5e1d3;
        background: #fffefa;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        color: #34775a;
        font-size: 18px;
    }

    .selected-file-info .fw-semibold {
        color: #263b35;
        font-size: 12px;
        font-weight: 650;
    }

    .selected-file-info small {
        color: #7a857d;
        font-size: 10px;
    }

    .btn-remove-image {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 7px;
        color: #8a3a34;
        background: #fff1ed;
        border: 1px solid #e8b8b0;
        cursor: pointer;
        transition: all .18s ease;
        font-size: 13px;
    }

    .btn-remove-image:hover {
        background: #fde4de;
        border-color: #dba39a;
    }

    /* ---------- TIPS ---------- */
    .tips {
        padding: 14px 16px;
        border-radius: 10px;
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        margin-top: 16px;
    }

    .tips .fw-semibold {
        color: #263b35;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .tips .fw-semibold i {
        color: #a87543;
    }

    .tips ul {
        padding-left: 18px;
        margin: 0;
        color: #5c6b62;
        font-size: 11px;
        line-height: 1.6;
    }

    .tips li {
        margin-bottom: 4px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .form-actions .btn-scheme-cancel,
    .form-actions .btn-scheme-save {
        flex: 1;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 1199px) {
        .upload-area,
        .preview-container {
            min-height: 360px;
        }

        .preview-container img {
            max-height: 360px;
        }
    }

    @media (max-width: 767.98px) {
        .idcard-header {
            padding: 18px 16px;
        }

        .idcard-title {
            font-size: 19px;
        }

        .idcard-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .idcard-header-content {
            flex-direction: column;
        }

        .btn-scheme-back {
            width: 100%;
        }

        .form-card-body {
            padding: 16px;
        }

        .upload-area,
        .preview-container {
            min-height: 280px;
        }

        .preview-container img {
            max-height: 280px;
        }
    }

    @media (max-width: 480px) {
        .idcard-title {
            font-size: 17px;
        }

        .idcard-subtitle {
            font-size: 10px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .idcard-create-page *,
        .idcard-create-page *::before,
        .idcard-create-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="idcard-create-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="idcard-header">
        <div class="idcard-header-content">
            <div>
                <div class="idcard-breadcrumb">
                    <a href="{{ route('admin.id-card.templates.index') }}">ID Card Templates</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Create</span>
                </div>

                <div class="idcard-title-row">
                    <div class="idcard-title-icon">
                        <i class="bi bi-card-image"></i>
                    </div>
                    <div>
                        <h1 class="idcard-title">Create ID Card Template</h1>
                        <p class="idcard-subtitle">Upload an ID-card design.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.id-card.templates.index') }}" class="btn-scheme-back">
                <i class="bi bi-arrow-left"></i>
                Back to Templates
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="scheme-alert scheme-alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="scheme-alert scheme-alert-danger">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="scheme-alert scheme-alert-danger">
            <strong>
                <i class="bi bi-exclamation-triangle"></i>
                Please correct the following:
            </strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===================== FORM ===================== --}}
    <form action="{{ route('admin.id-card.templates.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="templateForm">
        @csrf

        <div class="row g-4">

            {{-- LEFT: Template Information --}}
            <div class="col-xl-5">
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div>
                            <h5>Template Information</h5>
                            <small>Enter the basic information for this design.</small>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Template Name <span class="required">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="scheme-form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Standard Student ID Card"
                                   maxlength="150"
                                   required>
                            @error('name')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="academic_year" class="form-label">
                                Academic Year <span class="required">*</span>
                            </label>
                            <select name="academic_year"
                                    id="academic_year"
                                    class="scheme-form-select @error('academic_year') is-invalid @enderror"
                                    required>
                                <option value="">Select academic year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}"
                                        {{ old('academic_year') === $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">
                                Status <span class="required">*</span>
                            </label>
                            <select name="status"
                                    id="status"
                                    class="scheme-form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="active"
                                    {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="info-box">
                            <div class="d-flex gap-3">
                                <div class="info-icon">
                                    <i class="bi bi-stars"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold mb-1">Automatic analysis</div>
                                    <div class="small">
                                        After upload, the server will analyze the design
                                        and detect ID-card fields automatically.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('admin.id-card.templates.index') }}"
                               class="btn-scheme-cancel">
                                Cancel
                            </a>
                            <button type="submit"
                                    id="saveTemplateBtn"
                                    class="btn-scheme-save">
                                <i class="bi bi-cloud-arrow-up"></i>
                                Upload & Analyze
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: ID Card Design Upload --}}
            <div class="col-xl-7">
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon">
                            <i class="bi bi-image"></i>
                        </div>
                        <div>
                            <h5>ID Card Design <span class="required">*</span></h5>
                            <small>Upload JPG, PNG or WebP.</small>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <input type="file"
                               name="template_image"
                               id="template_image"
                               class="d-none"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                               required>

                        <label for="template_image" id="uploadArea" class="upload-area">
                            <div id="uploadPlaceholder">
                                <div class="upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <h5>Upload ID Card Design</h5>
                                <p>Click here to choose an image</p>
                                <span class="small">JPG, JPEG, PNG or WebP • Maximum 5 MB</span>
                            </div>

                            <div id="previewContainer" class="preview-container d-none">
                                <img id="imagePreview" src="" alt="ID Card Template Preview">
                                <div class="preview-overlay">
                                    <span class="badge">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Change image
                                    </span>
                                </div>
                            </div>
                        </label>

                        <div id="selectedFileInfo" class="selected-file-info d-none mt-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="file-icon">
                                    <i class="bi bi-file-earmark-image"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div id="fileName" class="fw-semibold text-truncate"></div>
                                    <small id="fileSize"></small>
                                </div>
                                <button type="button"
                                        id="removeImage"
                                        class="btn-remove-image"
                                        title="Remove image">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="tips">
                            <div class="fw-semibold">
                                <i class="bi bi-lightbulb"></i>
                                Template tips
                            </div>
                            <ul>
                                <li>Use a clear, high-resolution ID-card image.</li>
                                <li>Keep field labels readable for OCR detection.</li>
                                <li>Sample values such as a demo name or ID are supported.</li>
                                <li>The design is analyzed automatically after submission.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('template_image');
    const uploadArea = document.getElementById('uploadArea');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const selectedFileInfo = document.getElementById('selectedFileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeImage = document.getElementById('removeImage');
    const form = document.getElementById('templateForm');
    const saveButton = document.getElementById('saveTemplateBtn');

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function showPreview(file) {
        if (!file) return;

        const allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!allowed.includes(file.type)) {
            alert('Please select a JPG, JPEG, PNG or WebP image.');
            fileInput.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('The image must be smaller than 5 MB.');
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            imagePreview.src = event.target.result;
            uploadPlaceholder.classList.add('d-none');
            previewContainer.classList.remove('d-none');
            selectedFileInfo.classList.remove('d-none');
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
        };

        reader.readAsDataURL(file);
    }

    fileInput.addEventListener('change', function () {
        showPreview(this.files[0]);
    });

    removeImage.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        fileInput.value = '';
        imagePreview.src = '';
        uploadPlaceholder.classList.remove('d-none');
        previewContainer.classList.add('d-none');
        selectedFileInfo.classList.add('d-none');
    });

    uploadArea.addEventListener('dragover', function (event) {
        event.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function () {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function (event) {
        event.preventDefault();
        uploadArea.classList.remove('dragover');

        const files = event.dataTransfer.files;
        if (!files.length) return;

        try {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(files[0]);
            fileInput.files = dataTransfer.files;
        } catch (error) {
            console.warn('Could not assign dropped file.', error);
        }

        showPreview(files[0]);
    });

    form.addEventListener('submit', function () {
        saveButton.disabled = true;
        saveButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Uploading & analyzing...
        `;
    });
});
</script>

@endsection