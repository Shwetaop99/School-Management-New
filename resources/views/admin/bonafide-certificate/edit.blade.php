@extends('layouts.app')

@section('content')

<div class="bonafide-page py-4">


<div class="container-fluid">

    {{-- ================= PAGE HEADER ================= --}}
    <div class="page-header mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="header-icon">
                    <i class="bi bi-pencil-square"></i>
                </span>

                <span class="text-primary fw-semibold small text-uppercase">
                    Certificate Management
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Edit Bonafide Certificate
            </h2>

            <p class="text-muted mb-0">
                Update the information of this bonafide certificate.
            </p>

        </div>


        <a href="{{ route('admin.bonafide.show', $certificate->id) }}"
           class="btn btn-outline-secondary back-btn">

            <i class="bi bi-arrow-left me-2"></i>
            Back to Certificate

        </a>

    </div>


    {{-- ================= SUCCESS ================= --}}
    @if(session('success'))

        <div class="alert alert-success custom-alert alert-dismissible fade show">

            <div class="d-flex align-items-center">

                <div class="alert-icon success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div>
                    <strong>Success</strong>

                    <div>
                        {{ session('success') }}
                    </div>
                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================= ERROR ================= --}}
    @if(session('error'))

        <div class="alert alert-danger custom-alert alert-dismissible fade show">

            <div class="d-flex align-items-center">

                <div class="alert-icon danger-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </div>

                <div>

                    <strong>Error</strong>

                    <div>
                        {{ session('error') }}
                    </div>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================= VALIDATION ERRORS ================= --}}
    @if($errors->any())

        <div class="alert alert-danger custom-alert">

            <div class="d-flex align-items-start">

                <div class="alert-icon danger-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div>

                    <strong>
                        Please correct the following errors:
                    </strong>

                    <ul class="mb-0 mt-2 ps-3">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ================= MAIN ================= --}}
    <div class="row justify-content-center">

        <div class="col-12 col-xl-10">

            <form method="POST"
                  action="{{ route('admin.bonafide.update', $certificate->id) }}">

                @csrf
                @method('PUT')


                <div class="certificate-card">


                    {{-- ================= CARD HEADER ================= --}}
                    <div class="certificate-header">

                        <div class="d-flex align-items-center">

                            <div class="certificate-icon">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Certificate Details
                                </h5>

                                <p class="text-muted mb-0 small">
                                    Modify the certificate information below.
                                </p>

                            </div>

                        </div>


                        <div class="certificate-number">

                            <span class="number-label">
                                Certificate No.
                            </span>

                            <strong>
                                {{ $certificate->certificate_no }}
                            </strong>

                        </div>

                    </div>


                    {{-- ================= BODY ================= --}}
                    <div class="certificate-body">


                        {{-- ================= SECTION 01 ================= --}}
                        <div class="form-section">

                            <div class="section-title">

                                <div class="section-number">
                                    01
                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Certificate Information
                                    </h6>

                                    <small class="text-muted">
                                        View the certificate number and update the issue date.
                                    </small>

                                </div>

                            </div>


                            <div class="row g-4">


                                {{-- Certificate Number --}}
                                <div class="col-md-6">

                                    <div class="field-card">

                                        <label class="form-label fw-semibold">
                                            Certificate Number
                                        </label>

                                        <div class="input-icon-wrapper">

                                            <i class="bi bi-hash input-icon"></i>

                                            <input type="text"
                                                   class="form-control form-control-lg ps-5 readonly-field"
                                                   value="{{ $certificate->certificate_no }}"
                                                   readonly>

                                        </div>

                                        <div class="helper-text mt-2">

                                            <i class="bi bi-lock-fill me-1"></i>

                                            Certificate number cannot be changed.

                                        </div>

                                    </div>

                                </div>


                                {{-- Issue Date --}}
                                <div class="col-md-6">

                                    <div class="field-card">

                                        <label for="issue_date"
                                               class="form-label fw-semibold">

                                            Issue Date

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>


                                        <div class="input-icon-wrapper">

                                            <i class="bi bi-calendar3 input-icon"></i>

                                            <input type="date"
                                                   name="issue_date"
                                                   id="issue_date"
                                                   value="{{ old('issue_date', optional($certificate->issue_date)->format('Y-m-d')) }}"
                                                   class="form-control form-control-lg ps-5 @error('issue_date') is-invalid @enderror"
                                                   required>

                                        </div>


                                        @error('issue_date')

                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================= SECTION 02 ================= --}}
                        <div class="form-section">

                            <div class="section-title">

                                <div class="section-number">
                                    02
                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Student Information
                                    </h6>

                                    <small class="text-muted">
                                        Select the student associated with this certificate.
                                    </small>

                                </div>

                            </div>


                            <div class="student-select-wrapper">

                                <label for="student_id"
                                       class="form-label fw-semibold">

                                    Student

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <div class="input-icon-wrapper">

                                    <i class="bi bi-person-fill input-icon"></i>

                                    <select name="student_id"
                                            id="student_id"
                                            class="form-select form-control-lg ps-5 @error('student_id') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            -- Select Active Student --
                                        </option>


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

                                                —
                                                ID: {{ $student->student_id }}

                                                @if($student->class)

                                                    —
                                                    Class: {{ $student->class }}

                                                @endif

                                                @if($student->section)

                                                    / {{ $student->section }}

                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                @error('student_id')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div class="helper-text mt-2">

                                    <i class="bi bi-info-circle me-1"></i>

                                    Only active students are displayed.

                                </div>

                            </div>

                        </div>


                        {{-- ================= SECTION 03 ================= --}}
                        <div class="form-section">

                            <div class="section-title">

                                <div class="section-number">
                                    03
                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Certificate Purpose
                                    </h6>

                                    <small class="text-muted">
                                        Update the reason for issuing this certificate.
                                    </small>

                                </div>

                            </div>


                            <div class="reason-card">

                                <label for="reason"
                                       class="form-label fw-semibold">

                                    Reason for Certificate

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <textarea name="reason"
                                          id="reason"
                                          rows="4"
                                          maxlength="500"
                                          class="form-control @error('reason') is-invalid @enderror"
                                          placeholder="Enter the reason for issuing the bonafide certificate..."
                                          required>{{ old('reason', $certificate->reason) }}</textarea>


                                @error('reason')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div class="d-flex justify-content-between align-items-center mt-2">

                                    <small class="text-muted">

                                        <i class="bi bi-pencil-square me-1"></i>

                                        Maximum 500 characters

                                    </small>


                                    <small class="character-counter">

                                        <span id="reasonCount">
                                            0
                                        </span>/500

                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- ================= SECTION 04 ================= --}}
                        <div class="form-section mb-0">

                            <div class="section-title">

                                <div class="section-number">
                                    04
                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Certificate Status
                                    </h6>

                                    <small class="text-muted">
                                        Control whether this certificate is currently active.
                                    </small>

                                </div>

                            </div>


                            <div class="status-card">

                                <div class="status-content">

                                    <div class="status-icon">

                                        <i class="bi bi-toggle-on"></i>

                                    </div>


                                    <div>

                                        <label for="status"
                                               class="form-label fw-semibold mb-1">

                                            Status

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>

                                        <div class="small text-muted">
                                            Active certificates can be used and printed.
                                        </div>

                                    </div>

                                </div>


                                <div class="status-select">

                                    <select name="status"
                                            id="status"
                                            class="form-select @error('status') is-invalid @enderror"
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

                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>


                    </div>


                    {{-- ================= FOOTER ================= --}}
                    <div class="certificate-footer">

                        <div class="footer-info">

                            <i class="bi bi-shield-check me-2"></i>

                            Changes will be saved to the certificate record.

                        </div>


                        <div class="d-flex gap-2">

                            <a href="{{ route('admin.bonafide.show', $certificate->id) }}"
                               class="btn btn-light border cancel-btn">

                                <i class="bi bi-x-lg me-1"></i>

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary update-btn">

                                <i class="bi bi-check-circle-fill me-1"></i>

                                Update Certificate

                            </button>

                        </div>

                    </div>


                </div>

            </form>

        </div>

    </div>

</div>


</div>

{{-- ========================================================= --}}
{{-- PAGE CSS --}}
{{-- ========================================================= --}}

<style>

    .bonafide-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
    }


    /* Header */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .header-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
        font-size: 18px;
    }

    .page-header h2 {
        letter-spacing: -.4px;
    }

    .back-btn {
        border-radius: 9px;
        padding: 10px 18px;
        font-weight: 600;
    }


    /* Alerts */

    .custom-alert {
        border: 0;
        border-radius: 12px;
        padding: 15px 18px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .04);
    }

    .alert-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .success-icon {
        background: rgba(25, 135, 84, .12);
        color: #198754;
    }

    .danger-icon {
        background: rgba(220, 53, 69, .12);
        color: #dc3545;
    }


    /* Main Card */

    .certificate-card {
        background: #fff;
        border: 1px solid #e8ebf0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(31, 41, 55, .07);
    }


    /* Card Header */

    .certificate-header {
        padding: 22px 26px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .certificate-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
        font-size: 23px;
        margin-right: 14px;
    }


    /* Certificate Number */

    .certificate-number {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        border-radius: 10px;
        background: #f8f9fb;
        border: 1px solid #e8ebf0;
    }

    .number-label {
        color: #667085;
        font-size: 12px;
    }

    .certificate-number strong {
        color: #344054;
        font-size: 13px;
    }


    /* Body */

    .certificate-body {
        padding: 28px;
    }


    /* Sections */

    .form-section {
        padding-bottom: 30px;
        margin-bottom: 30px;
        border-bottom: 1px solid #edf0f4;
    }

    .section-title {
        display: flex;
        align-items: center;
        margin-bottom: 24px;
    }

    .section-number {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #f4f7fb;
        color: #0d6efd;
        border: 1px solid #e4eaf2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        margin-right: 13px;
    }


    /* Fields */

    .field-card {
        background: #fafbfc;
        border: 1px solid #edf0f4;
        border-radius: 12px;
        padding: 18px;
        height: 100%;
    }

    .student-select-wrapper {
        background: #fafbfc;
        border: 1px solid #edf0f4;
        border-radius: 12px;
        padding: 20px;
    }

    .reason-card {
        background: #fafbfc;
        border: 1px solid #edf0f4;
        border-radius: 12px;
        padding: 20px;
    }


    /* Status */

    .status-card {
        background: #fafbfc;
        border: 1px solid #edf0f4;
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .status-content {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .status-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(25, 135, 84, .10);
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .status-select {
        width: 200px;
    }


    /* Forms */

    .form-label {
        color: #344054;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control,
    .form-select {
        min-height: 46px;
        border-radius: 9px;
        border-color: #dfe4ea;
        background-color: #fff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .08);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .readonly-field {
        background: #f1f3f5 !important;
        color: #667085;
        cursor: not-allowed;
    }


    /* Input Icons */

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 17px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        z-index: 5;
        pointer-events: none;
    }


    /* Helper */

    .helper-text {
        color: #667085;
        font-size: 12px;
    }


    /* Counter */

    .character-counter {
        color: #667085;
        font-weight: 600;
    }


    /* Footer */

    .certificate-footer {
        background: #fafbfc;
        border-top: 1px solid #edf0f4;
        padding: 18px 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .footer-info {
        color: #667085;
        font-size: 12px;
    }

    .footer-info i {
        color: #198754;
    }

    .cancel-btn,
    .update-btn {
        border-radius: 9px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .update-btn {
        min-width: 185px;
    }


    /* Responsive */

    @media (max-width: 767.98px) {

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .back-btn {
            width: 100%;
        }

        .certificate-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }

        .certificate-number {
            width: 100%;
            justify-content: space-between;
        }

        .certificate-body {
            padding: 20px;
        }

        .status-card {
            align-items: stretch;
            flex-direction: column;
        }

        .status-select {
            width: 100%;
        }

        .certificate-footer {
            align-items: stretch;
            flex-direction: column;
            padding: 20px;
        }

        .certificate-footer .d-flex {
            width: 100%;
        }

        .cancel-btn,
        .update-btn {
            flex: 1;
        }

    }

</style>

{{-- ========================================================= --}}
{{-- CHARACTER COUNTER --}}
{{-- ========================================================= --}}

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
