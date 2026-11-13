@extends('layouts.app')

@section('title', 'Generate Bonafide Certificate | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — GENERATE BONAFIDE CERTIFICATE
    ========================================================= */

    .bonafide-generate-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .bonafide-generate-page *,
    .bonafide-generate-page *::before,
    .bonafide-generate-page *::after {
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

    /* ---------- FORM CARD ---------- */
    .form-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        overflow: hidden;
    }

    .form-card-body {
        padding: 24px 22px;
    }

    /* ---------- SECTION HEADING ---------- */
    .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e9e5d9;
    }

    .section-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 10px;
        font-size: 17px;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .07);
    }

    .section-heading h5 {
        margin: 0;
        color: #263b35;
        font-size: 14px;
        font-weight: 750;
    }

    .section-heading p {
        margin: 3px 0 0;
        color: #7a857d;
        font-size: 11px;
    }

    /* ---------- STUDENT INFO BOX ---------- */
    .student-box {
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        border-radius: 11px;
        padding: 18px 20px;
        margin-bottom: 26px;
    }

    .student-name {
        margin: 0 0 16px;
        color: #245a43;
        font-size: 16px;
        font-weight: 750;
        letter-spacing: -.2px;
    }

    .student-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px 20px;
    }

    .meta-item label {
        display: block;
        margin-bottom: 4px;
        color: #82897e;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .meta-item span {
        color: #263b35;
        font-size: 12px;
        font-weight: 650;
        word-break: break-word;
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
        min-height: 95px;
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

    .field-error {
        margin-top: 5px;
        color: #bd5148;
        font-size: 10px;
        line-height: 1.4;
    }

    /* ---------- ACTIONS ---------- */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding: 16px 22px;
        background: #faf8f0;
        border-top: 1px solid #e5e1d3;
    }

    .btn-scheme-cancel,
    .btn-scheme-save {
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

    .btn-scheme-cancel {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

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

        .form-card-body {
            padding: 18px 15px;
        }

        .student-meta {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-actions {
            padding: 14px 15px;
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn-scheme-cancel,
        .btn-scheme-save {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .bonafide-title {
            font-size: 17px;
        }

        .bonafide-subtitle {
            font-size: 10px;
        }

        .student-meta {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bonafide-generate-page *,
        .bonafide-generate-page *::before,
        .bonafide-generate-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="bonafide-generate-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="bonafide-header">
        <div class="bonafide-header-content">
            <div class="bonafide-breadcrumb">
                <a href="{{ route('admin.students.index') }}">Students</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('admin.bonafide.index') }}">Bonafide Certificate</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('admin.bonafide.students', ['class' => $student->class]) }}">Class {{ $student->class }}</a>
                <i class="bi bi-chevron-right"></i>
                <span>Generate</span>
            </div>

            <div class="bonafide-title-row">
                <div class="bonafide-title-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <h1 class="bonafide-title">Generate Bonafide Certificate</h1>
                    <p class="bonafide-subtitle">
                        Review student details and enter certificate information.
                    </p>
                </div>
            </div>
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

    {{-- ===================== FORM CARD ===================== --}}
    <div class="form-card">
        <div class="form-card-body">

            {{-- Student Information --}}
            <div class="section-heading">
                <div class="section-heading-icon">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <h5>Student Information</h5>
                    <p>Selected student details for the certificate</p>
                </div>
            </div>

            <div class="student-box">
                <div class="student-name">
                    {{ trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        ($student->last_name ?? '')
                    ) ?: 'Student' }}
                </div>

                <div class="student-meta">
                    <div class="meta-item">
                        <label>Student ID</label>
                        <span>{{ $student->student_id ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <label>Class</label>
                        <span>{{ $student->class ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <label>Section</label>
                        <span>{{ $student->section ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <label>Date of Birth</label>
                        <span>
                            @if(!empty($student->date_of_birth))
                                {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="meta-item">
                        <label>Parent / Guardian</label>
                        <span>{{ $student->father_name ?? $student->guardian_name ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <label>Academic Year</label>
                        <span>{{ $student->academic_year ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Certificate Details --}}
            <div class="section-heading">
                <div class="section-heading-icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <div>
                    <h5>Certificate Details</h5>
                    <p>Enter the reason and issue information</p>
                </div>
            </div>

            <form action="{{ route('admin.bonafide.store') }}" method="POST">
                @csrf

                <input type="hidden" name="student_id" value="{{ $student->id }}">

                <div class="row g-3">
                    <div class="col-12">
                        <label for="reason" class="form-label">
                            Reason <span class="required">*</span>
                        </label>
                        <textarea name="reason"
                                  id="reason"
                                  class="scheme-form-textarea @error('reason') is-invalid @enderror"
                                  placeholder="Enter reason for issuing the bonafide certificate"
                                  maxlength="500"
                                  required>{{ old('reason', 'To avail of travel benefits') }}</textarea>
                        @error('reason')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="issue_date" class="form-label">
                            Issue Date <span class="required">*</span>
                        </label>
                        <input type="date"
                               name="issue_date"
                               id="issue_date"
                               class="scheme-form-control @error('issue_date') is-invalid @enderror"
                               value="{{ old('issue_date', date('Y-m-d')) }}"
                               required>
                        @error('issue_date')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Status <span class="required">*</span>
                        </label>
                        <select name="status"
                                id="status"
                                class="scheme-form-select @error('status') is-invalid @enderror"
                                required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.bonafide.students', ['class' => $student->class]) }}"
                       class="btn-scheme-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Back to Students
                    </a>
                    <button type="submit" class="btn-scheme-save">
                        <i class="bi bi-file-earmark-check"></i>
                        Generate Certificate
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection