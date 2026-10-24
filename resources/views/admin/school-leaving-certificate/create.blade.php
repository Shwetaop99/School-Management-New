@extends('layouts.app')

@section('title', 'Create School Leaving Certificate | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — CREATE SCHOOL LEAVING CERTIFICATE
       LOCKED VERSION
    ========================================================= */

    .slc-create-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .slc-create-page *,
    .slc-create-page *::before,
    .slc-create-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .slc-header {
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

    .slc-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .slc-breadcrumb {
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

    .slc-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .slc-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .slc-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .slc-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .slc-title-icon {
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

    .slc-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .slc-subtitle {
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
        margin-bottom: 18px;
        overflow: hidden;
    }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
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
        height: 40px;
        padding: 0 12px;
    }

    .scheme-form-textarea {
        min-height: 100px;
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

    .scheme-form-control[readonly] {
        cursor: default;
        color: #69766d;
        background: #f0eee6;
    }

    .scheme-form-control.is-invalid,
    .scheme-form-select.is-invalid,
    .scheme-form-textarea.is-invalid {
        border-color: #bd5148;
        background: #fff8f5;
    }

    .field-error {
        margin-top: 5px;
        color: #bd5148;
        font-size: 10px;
        line-height: 1.4;
    }

    .field-hint {
        margin-top: 5px;
        color: #7a857d;
        font-size: 10px;
    }

    /* ---------- FORM ACTIONS ---------- */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 8px;
        padding: 16px 0 8px;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 767.98px) {
        .slc-header {
            padding: 18px 16px;
        }

        .slc-title {
            font-size: 19px;
        }

        .slc-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .slc-header-content {
            flex-direction: column;
        }

        .btn-scheme-back {
            width: 100%;
        }

        .form-card-body {
            padding: 16px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn-scheme-cancel,
        .btn-scheme-save {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .slc-title {
            font-size: 17px;
        }

        .slc-subtitle {
            font-size: 10px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .slc-create-page *,
        .slc-create-page *::before,
        .slc-create-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="slc-create-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="slc-header">
        <div class="slc-header-content">
            <div>
                <div class="slc-breadcrumb">
                    <a href="{{ route('admin.school-leaving-certificate.index') }}">School Leaving Certificates</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Create</span>
                </div>

                <div class="slc-title-row">
                    <div class="slc-title-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <h1 class="slc-title">Create School Leaving Certificate</h1>
                        <p class="slc-subtitle">Create a new school leaving certificate for a student.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.school-leaving-certificate.index') }}" class="btn-scheme-back">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="scheme-alert scheme-alert-danger">
            <strong>
                <i class="bi bi-exclamation-triangle-fill"></i>
                Please correct the following errors:
            </strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.school-leaving-certificate.store') }}">
        @csrf

        {{-- ===================== STUDENT SELECTION ===================== --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>
                <h5>Student Information</h5>
            </div>

            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">
                            Select Student <span class="required">*</span>
                        </label>
                        <select name="student_id"
                                id="student_id"
                                class="scheme-form-select @error('student_id') is-invalid @enderror"
                                required>
                            <option value="">-- Select Student --</option>
                            @foreach ($students as $student)
                                @php
                                    $studentName = trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    );
                                @endphp
                                <option value="{{ $student->id }}"
                                        data-student-id="{{ $student->student_id }}"
                                        data-name="{{ $studentName }}"
                                        data-class="{{ $student->class ?? '' }}"
                                        data-section="{{ $student->section ?? '' }}"
                                        data-roll="{{ $student->roll_number ?? '' }}"
                                        data-dob="{{ $student->date_of_birth?->format('d-m-Y') }}"
                                        data-gender="{{ $student->gender ?? '' }}"
                                        data-aadhar="{{ $student->aadhar_card_no ?? '' }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->student_id }} - {{ $studentName }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Student ID</label>
                        <input type="text"
                               id="display_student_id"
                               class="scheme-form-control"
                               readonly
                               placeholder="Auto-filled">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Student Name</label>
                        <input type="text"
                               id="display_student_name"
                               class="scheme-form-control"
                               readonly
                               placeholder="Auto-filled">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Class</label>
                        <input type="text"
                               id="display_class"
                               class="scheme-form-control"
                               readonly
                               placeholder="Auto-filled">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Division</label>
                        <input type="text"
                               id="display_section"
                               class="scheme-form-control"
                               readonly
                               placeholder="Auto-filled">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Roll Number</label>
                        <input type="text"
                               id="display_roll"
                               class="scheme-form-control"
                               readonly
                               placeholder="Auto-filled">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="text"
                               id="display_dob"
                               class="scheme-form-control"
                               readonly
                               placeholder="Auto-filled">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <input type="text"
                               id="display_gender"
                               class="scheme-form-control"
                               readonly
                               placeholder="Auto-filled">
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== CERTIFICATE INFORMATION ===================== --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="bi bi-file-earmark-medical"></i>
                </div>
                <h5>Certificate Information</h5>
            </div>

            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            Certificate Number <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="certificate_no"
                               value="{{ old('certificate_no') }}"
                               class="scheme-form-control @error('certificate_no') is-invalid @enderror"
                               placeholder="Enter certificate number"
                               required>
                        @error('certificate_no')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Issue Date</label>
                        <input type="date"
                               name="issue_date"
                               value="{{ old('issue_date', now()->format('Y-m-d')) }}"
                               class="scheme-form-control @error('issue_date') is-invalid @enderror">
                        @error('issue_date')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Leaving Date <span class="required">*</span>
                        </label>
                        <input type="date"
                               name="leaving_date"
                               value="{{ old('leaving_date') }}"
                               class="scheme-form-control @error('leaving_date') is-invalid @enderror"
                               required>
                        <div class="field-hint">Enter the actual leaving date manually.</div>
                        @error('leaving_date')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Leaving Reason</label>
                        <input type="text"
                               name="leaving_reason"
                               value="{{ old('leaving_reason') }}"
                               class="scheme-form-control @error('leaving_reason') is-invalid @enderror"
                               placeholder="e.g. Transfer to another school">
                        @error('leaving_reason')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text"
                               name="academic_year"
                               value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                               class="scheme-form-control @error('academic_year') is-invalid @enderror"
                               placeholder="2026-2027">
                        @error('academic_year')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Status <span class="required">*</span>
                        </label>
                        <select name="status"
                                class="scheme-form-select @error('status') is-invalid @enderror"
                                required>
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                            <option value="issued" {{ old('status') === 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                        @error('status')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== ACADEMIC / CONDUCT ===================== --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h5>Academic & Conduct Information</h5>
            </div>

            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Last Class Studied</label>
                        <input type="text"
                               name="last_class"
                               id="last_class"
                               value="{{ old('last_class') }}"
                               class="scheme-form-control @error('last_class') is-invalid @enderror"
                               placeholder="e.g. 10th">
                        @error('last_class')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Last Division</label>
                        <input type="text"
                               name="last_division"
                               id="last_division"
                               value="{{ old('last_division') }}"
                               class="scheme-form-control @error('last_division') is-invalid @enderror"
                               placeholder="e.g. A">
                        @error('last_division')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Conduct</label>
                        <input type="text"
                               name="conduct"
                               value="{{ old('conduct', 'Good') }}"
                               class="scheme-form-control @error('conduct') is-invalid @enderror"
                               placeholder="Good">
                        @error('conduct')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Progress</label>
                        <input type="text"
                               name="progress"
                               value="{{ old('progress', 'Satisfactory') }}"
                               class="scheme-form-control @error('progress') is-invalid @enderror"
                               placeholder="Satisfactory">
                        @error('progress')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks"
                                  rows="4"
                                  class="scheme-form-textarea @error('remarks') is-invalid @enderror"
                                  placeholder="Enter any additional remarks...">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== ACTIONS ===================== --}}
        <div class="form-actions">
            <a href="{{ route('admin.school-leaving-certificate.index') }}"
               class="btn-scheme-cancel">
                Cancel
            </a>
            <button type="submit" class="btn-scheme-save">
                <i class="bi bi-check-circle"></i>
                Create Certificate
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const studentSelect = document.getElementById('student_id');
    const studentIdField = document.getElementById('display_student_id');
    const studentNameField = document.getElementById('display_student_name');
    const classField = document.getElementById('display_class');
    const sectionField = document.getElementById('display_section');
    const rollField = document.getElementById('display_roll');
    const dobField = document.getElementById('display_dob');
    const genderField = document.getElementById('display_gender');

    function clearStudentDetails() {
        studentIdField.value = '';
        studentNameField.value = '';
        classField.value = '';
        sectionField.value = '';
        rollField.value = '';
        dobField.value = '';
        genderField.value = '';
    }

    function loadStudentDetails() {
        const option = studentSelect.options[studentSelect.selectedIndex];

        if (!option || !option.value) {
            clearStudentDetails();
            return;
        }

        studentIdField.value = option.dataset.studentId || '';
        studentNameField.value = option.dataset.name || '';
        classField.value = option.dataset.class || '';
        sectionField.value = option.dataset.section || '';
        rollField.value = option.dataset.roll || '';
        dobField.value = option.dataset.dob || '';
        genderField.value = option.dataset.gender || '';

        const lastClass = document.getElementById('last_class');
        const lastDivision = document.getElementById('last_division');

        if (lastClass && !lastClass.value) {
            lastClass.value = option.dataset.class || '';
        }

        if (lastDivision && !lastDivision.value) {
            lastDivision.value = option.dataset.section || '';
        }
    }

    studentSelect.addEventListener('change', loadStudentDetails);
    loadStudentDetails();
});
</script>
@endpush

@endsection