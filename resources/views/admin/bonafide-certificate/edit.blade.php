@extends('layouts.app')

@section('title', 'Edit Bonafide Certificate | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — EDIT BONAFIDE CERTIFICATE
    ========================================================= */

    .bonafide-edit-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .bonafide-edit-page *,
    .bonafide-edit-page *::before,
    .bonafide-edit-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .bonafide-header {
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

    .bonafide-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .bonafide-breadcrumb {
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

    .bonafide-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .bonafide-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .bonafide-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .bonafide-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .bonafide-title-icon {
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

    .bonafide-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .bonafide-subtitle {
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
        min-height: 40px;
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

    /* ---------- MAIN CARD ---------- */
    .certificate-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        overflow: hidden;
    }

    .certificate-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 18px 22px;
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
        border-bottom: 1px solid #e9e5d9;
    }

    .certificate-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .certificate-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 11px;
        font-size: 18px;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .07);
    }

    .certificate-header h5 {
        margin: 0;
        color: #263b35;
        font-size: 14px;
        font-weight: 750;
    }

    .certificate-header p {
        margin: 3px 0 0;
        color: #7a857d;
        font-size: 11px;
    }

    .certificate-number {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-radius: 20px;
        color: #245a43;
        font-size: 11px;
        font-weight: 700;
    }

    .certificate-number .number-label {
        color: #5c6b62;
        font-weight: 600;
    }

    /* ---------- BODY ---------- */
    .certificate-body {
        padding: 24px 22px;
    }

    .form-section {
        margin-bottom: 26px;
        padding-bottom: 26px;
        border-bottom: 1px solid #e9e5d9;
    }

    .form-section:last-of-type {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .section-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 9px;
        font-size: 11px;
        font-weight: 800;
        box-shadow: 0 2px 4px rgba(52, 119, 90, .06);
    }

    .section-title h6 {
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
    }

    .section-title small {
        display: block;
        margin-top: 2px;
        color: #7a857d;
        font-size: 10px;
    }

    /* ---------- FIELD CARDS ---------- */
    .field-card,
    .student-select-wrapper,
    .reason-card,
    .status-card {
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        padding: 16px 18px;
    }

    .field-card {
        height: 100%;
    }

    /* ---------- FORM FIELDS ---------- */
    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .required {
        color: #bd5148;
    }

    .scheme-form-control,
    .scheme-form-select,
    .scheme-form-textarea {
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

    .scheme-form-control,
    .scheme-form-select {
        height: 42px;
        padding: 0 12px 0 38px;
    }

    .scheme-form-textarea {
        min-height: 110px;
        padding: 10px 12px;
        resize: vertical;
        line-height: 1.5;
    }

    .scheme-form-control::placeholder,
    .scheme-form-textarea::placeholder {
        color: #a0a397;
        font-weight: 400;
    }

    .scheme-form-control:hover,
    .scheme-form-select:hover,
    .scheme-form-textarea:hover {
        border-color: #c5d1c2;
    }

    .scheme-form-control:focus,
    .scheme-form-select:focus,
    .scheme-form-textarea:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .scheme-form-control.is-invalid,
    .scheme-form-select.is-invalid,
    .scheme-form-textarea.is-invalid {
        border-color: #bd5148;
        background: #fff8f5;
    }

    .scheme-form-control[readonly] {
        cursor: default;
        color: #69766d;
        background: #f0eee6;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #7c8b7e;
        font-size: 14px;
        z-index: 2;
        pointer-events: none;
    }

    .helper-text {
        margin-top: 7px;
        color: #7a857d;
        font-size: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .helper-text i {
        color: #34775a;
        font-size: 11px;
    }

    .field-error {
        margin-top: 5px;
        color: #bd5148;
        font-size: 10px;
        line-height: 1.4;
    }

    .character-counter {
        color: #7a857d;
        font-size: 10px;
        font-weight: 650;
    }

    /* ---------- STATUS CARD ---------- */
    .status-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .status-content {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .status-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        color: #34775a;
        background: #eaf3e9;
        border: 1px solid #d4e4d1;
        border-radius: 10px;
        font-size: 18px;
    }

    .status-select {
        width: 200px;
        flex-shrink: 0;
    }

    .status-select .scheme-form-select {
        padding-left: 12px;
    }

    /* ---------- FOOTER ---------- */
    .certificate-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        padding: 16px 22px;
        background: #faf8f0;
        border-top: 1px solid #e5e1d3;
    }

    .footer-info {
        color: #7a857d;
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .footer-info i {
        color: #34775a;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 767.98px) {
        .bonafide-header {
            padding: 18px 16px;
        }

        .bonafide-title {
            font-size: 19px;
        }

        .bonafide-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .bonafide-header-content {
            flex-direction: column;
        }

        .btn-scheme-back {
            width: 100%;
        }

        .certificate-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 16px;
        }

        .certificate-body {
            padding: 18px 15px;
        }

        .status-card {
            flex-direction: column;
            align-items: stretch;
        }

        .status-select {
            width: 100%;
        }

        .certificate-footer {
            flex-direction: column-reverse;
            align-items: stretch;
            padding: 14px 15px;
        }

        .certificate-footer .d-flex {
            width: 100%;
        }

        .btn-scheme-cancel,
        .btn-scheme-save {
            flex: 1;
        }
    }

    @media (max-width: 480px) {
        .bonafide-title {
            font-size: 17px;
        }

        .bonafide-subtitle {
            font-size: 10px;
        }

        .certificate-footer .d-flex {
            flex-direction: column;
        }

        .btn-scheme-cancel,
        .btn-scheme-save {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bonafide-edit-page *,
        .bonafide-edit-page *::before,
        .bonafide-edit-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="bonafide-edit-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="bonafide-header">
        <div class="bonafide-header-content">
            <div>
                <div class="bonafide-breadcrumb">
                    <a href="{{ route('admin.bonafide.index') }}">Bonafide Certificates</a>
                    <i class="bi bi-chevron-right"></i>
                    <a href="{{ route('admin.bonafide.show', $certificate->id) }}">{{ $certificate->certificate_no }}</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Edit</span>
                </div>

                <div class="bonafide-title-row">
                    <div class="bonafide-title-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h1 class="bonafide-title">Edit Bonafide Certificate</h1>
                        <p class="bonafide-subtitle">Update the information of this bonafide certificate.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.bonafide.show', $certificate->id) }}" class="btn-scheme-back">
                <i class="bi bi-arrow-left"></i>
                Back to Certificate
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
                Please correct the following errors:
            </strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===================== FORM CARD ===================== --}}
    <form method="POST" action="{{ route('admin.bonafide.update', $certificate->id) }}">
        @csrf
        @method('PUT')

        <div class="certificate-card">

            {{-- CARD HEADER --}}
            <div class="certificate-header">
                <div class="certificate-header-left">
                    <div class="certificate-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <h5>Certificate Details</h5>
                        <p>Modify the certificate information below.</p>
                    </div>
                </div>

                <div class="certificate-number">
                    <span class="number-label">Certificate No.</span>
                    {{ $certificate->certificate_no }}
                </div>
            </div>

            {{-- BODY --}}
            <div class="certificate-body">

                {{-- SECTION 01 — Certificate Information --}}
                <div class="form-section">
                    <div class="section-title">
                        <div class="section-number">01</div>
                        <div>
                            <h6>Certificate Information</h6>
                            <small>View the certificate number and update the issue date.</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="field-card">
                                <label class="form-label">Certificate Number</label>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-hash input-icon"></i>
                                    <input type="text"
                                           class="scheme-form-control"
                                           value="{{ $certificate->certificate_no }}"
                                           readonly>
                                </div>
                                <div class="helper-text">
                                    <i class="bi bi-lock-fill"></i>
                                    Certificate number cannot be changed.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-card">
                                <label for="issue_date" class="form-label">
                                    Issue Date <span class="required">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-calendar3 input-icon"></i>
                                    <input type="date"
                                           name="issue_date"
                                           id="issue_date"
                                           value="{{ old('issue_date', optional($certificate->issue_date)->format('Y-m-d')) }}"
                                           class="scheme-form-control @error('issue_date') is-invalid @enderror"
                                           required>
                                </div>
                                @error('issue_date')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 02 — Student Information --}}
                <div class="form-section">
                    <div class="section-title">
                        <div class="section-number">02</div>
                        <div>
                            <h6>Student Information</h6>
                            <small>Select the student associated with this certificate.</small>
                        </div>
                    </div>

                    <div class="student-select-wrapper">
                        <label for="student_id" class="form-label">
                            Student <span class="required">*</span>
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person-fill input-icon"></i>
                            <select name="student_id"
                                    id="student_id"
                                    class="scheme-form-select @error('student_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Select Active Student --</option>
                                @foreach($students as $student)
                                    @php
                                        $studentName = trim(
                                            ($student->first_name ?? '') . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            ($student->last_name ?? '')
                                        );
                                    @endphp
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id', $certificate->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $studentName }}
                                        — ID: {{ $student->student_id }}
                                        @if($student->class)
                                            — Class: {{ $student->class }}
                                        @endif
                                        @if($student->section)
                                            / {{ $student->section }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('student_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                        <div class="helper-text">
                            <i class="bi bi-info-circle"></i>
                            Only active students are displayed.
                        </div>
                    </div>
                </div>

                {{-- SECTION 03 — Certificate Purpose --}}
                <div class="form-section">
                    <div class="section-title">
                        <div class="section-number">03</div>
                        <div>
                            <h6>Certificate Purpose</h6>
                            <small>Update the reason for issuing this certificate.</small>
                        </div>
                    </div>

                    <div class="reason-card">
                        <label for="reason" class="form-label">
                            Reason for Certificate <span class="required">*</span>
                        </label>
                        <textarea name="reason"
                                  id="reason"
                                  rows="4"
                                  maxlength="500"
                                  class="scheme-form-textarea @error('reason') is-invalid @enderror"
                                  placeholder="Enter the reason for issuing the bonafide certificate..."
                                  required>{{ old('reason', $certificate->reason) }}</textarea>
                        @error('reason')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="helper-text mb-0">
                                <i class="bi bi-pencil-square"></i>
                                Maximum 500 characters
                            </div>
                            <span class="character-counter">
                                <span id="reasonCount">0</span>/500
                            </span>
                        </div>
                    </div>
                </div>

                {{-- SECTION 04 — Certificate Status --}}
                <div class="form-section">
                    <div class="section-title">
                        <div class="section-number">04</div>
                        <div>
                            <h6>Certificate Status</h6>
                            <small>Control whether this certificate is currently active.</small>
                        </div>
                    </div>

                    <div class="status-card">
                        <div class="status-content">
                            <div class="status-icon">
                                <i class="bi bi-toggle-on"></i>
                            </div>
                            <div>
                                <label for="status" class="form-label mb-1">
                                    Status <span class="required">*</span>
                                </label>
                                <div class="helper-text mb-0">
                                    Active certificates can be used and printed.
                                </div>
                            </div>
                        </div>

                        <div class="status-select">
                            <select name="status"
                                    id="status"
                                    class="scheme-form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="active"
                                    {{ old('status', $certificate->status) === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $certificate->status) === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="certificate-footer">
                <div class="footer-info">
                    <i class="bi bi-shield-check"></i>
                    Changes will be saved to the certificate record.
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.bonafide.show', $certificate->id) }}"
                       class="btn-scheme-cancel">
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-scheme-save">
                        <i class="bi bi-check-circle"></i>
                        Update Certificate
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const reason = document.getElementById('reason');
    const counter = document.getElementById('reasonCount');

    if (reason && counter) {
        function updateCounter() {
            counter.textContent = reason.value.length;
        }
        updateCounter();
        reason.addEventListener('input', updateCounter);
    }
});
</script>

@endsection