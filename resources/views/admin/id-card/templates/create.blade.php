@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-card-image text-primary me-2"></i>
                Create ID Card Template
            </h3>
            <p class="text-muted mb-0">
                Upload an ID-card design.
            </p>
        </div>

        <a href="{{ route('admin.id-card.templates.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Templates
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.id-card.templates.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="templateForm">
        @csrf

        <div class="row g-4">

            <div class="col-xl-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Template Information
                        </h5>
                        <small class="text-muted">
                            Enter the basic information for this design.
                        </small>
                    </div>

                    <div class="card-body p-4">

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Template Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Standard Student ID Card"
                                   maxlength="150"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="academic_year" class="form-label fw-semibold">
                                Academic Year <span class="text-danger">*</span>
                            </label>
                            <select name="academic_year"
                                    id="academic_year"
                                    class="form-select form-select-lg @error('academic_year') is-invalid @enderror"
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status"
                                    id="status"
                                    class="form-select form-select-lg @error('status') is-invalid @enderror"
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="info-box">
                            <div class="d-flex gap-3">
                                <div class="info-icon">
                                    <i class="bi bi-stars"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold mb-1">Automatic analysis</div>
                                    <div class="small text-muted">
                                        After upload, the server will analyze the design
                                        and detect ID-card fields automatically.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('admin.id-card.templates.index') }}"
                               class="btn btn-light border btn-lg flex-grow-1">
                                Cancel
                            </a>

                            <button type="submit"
                                    id="saveTemplateBtn"
                                    class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-cloud-arrow-up me-1"></i>
                                Upload & Analyze
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-image text-primary me-2"></i>
                            ID Card Design <span class="text-danger">*</span>
                        </h5>
                        <small class="text-muted">
                            Upload JPG, PNG or WebP.
                        </small>
                    </div>

                    <div class="card-body p-4">

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
                                <h5 class="fw-bold mb-2">Upload ID Card Design</h5>
                                <p class="text-muted mb-2">
                                    Click here to choose an image
                                </p>
                                <span class="small text-muted">
                                    JPG, JPEG, PNG or WebP • Maximum 5 MB
                                </span>
                            </div>

                            <div id="previewContainer" class="preview-container d-none">
                                <img id="imagePreview" src="" alt="ID Card Template Preview">
                                <div class="preview-overlay">
                                    <span class="badge bg-dark">
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
                                    <small id="fileSize" class="text-muted"></small>
                                </div>

                                <button type="button"
                                        id="removeImage"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="tips mt-4">
                            <div class="fw-semibold mb-2">
                                <i class="bi bi-lightbulb text-warning me-1"></i>
                                Template tips
                            </div>
                            <ul class="text-muted small mb-0">
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

<style>
.info-box {
    padding: 16px;
    border-radius: 14px;
    background: #f5f9ff;
    border: 1px solid #dbeafe;
}
.info-icon {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #e8f1ff;
    color: #0d6efd;
    font-size: 20px;
}
.upload-area {
    min-height: 520px;
    width: 100%;
    border: 2px dashed #cbd5e1;
    border-radius: 18px;
    background: #f8fafc;
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
    border-color: #0d6efd;
    background: #f5f9ff;
}
.upload-icon {
    width: 76px;
    height: 76px;
    margin: 0 auto 18px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e8f1ff;
    color: #0d6efd;
    font-size: 34px;
}
.preview-container {
    width: 100%;
    height: 100%;
    min-height: 520px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    background: #eef2f7;
}
.preview-container img {
    max-width: 100%;
    max-height: 520px;
    width: auto;
    height: auto;
    object-fit: contain;
    display: block;
}
.preview-overlay {
    position: absolute;
    right: 18px;
    top: 18px;
}
.selected-file-info {
    padding: 13px 15px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    background: #fff;
}
.file-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef5ff;
    color: #0d6efd;
    font-size: 20px;
}
.tips {
    padding: 15px 17px;
    border-radius: 13px;
    background: #fafafa;
    border: 1px solid #e9ecef;
}
.tips ul {
    padding-left: 20px;
}
.tips li {
    margin-bottom: 6px;
}
@media (max-width: 1199px) {
    .upload-area,
    .preview-container {
        min-height: 420px;
    }
}
</style>

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

        const allowed = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

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
            <span class="spinner-border spinner-border-sm me-2"></span>
            Uploading & analyzing...
        `;
    });
});
</script>

@endsection
