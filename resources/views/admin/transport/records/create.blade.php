@extends('layouts.app')

@section('title', 'Add Transport Record | Admin')

@section('content')

<style>
    /* =========================================================
       ADD TRANSPORT RECORD PAGE
    ========================================================= */

    .transport-create-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f4f7fb;
        color: #172033;
        font-family: "Inter", sans-serif;
        padding-bottom: 40px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .transport-create-header {
        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #237de0 55%,
            #16a7c9 100%
        );
        border-radius: 18px;
        padding: 28px 32px;
        margin-bottom: 24px;
        color: #fff;
        box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
        position: relative;
        overflow: hidden;
    }

    .transport-create-header::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        right: -50px;
        top: -70px;
    }

    .transport-create-header::before {
        content: "";
        position: absolute;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .06);
        right: 100px;
        bottom: -70px;
    }

    .create-header-content {
        position: relative;
        z-index: 2;
    }

    .create-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        margin-bottom: 10px;
        color: rgba(255,255,255,.78);
    }

    .create-breadcrumb a {
        color: rgba(255,255,255,.82);
        text-decoration: none;
        transition: .2s ease;
    }

    .create-breadcrumb a:hover {
        color: #fff;
    }

    .create-breadcrumb i {
        font-size: 9px;
    }

    .create-title-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .create-title-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.20);
        font-size: 23px;
        backdrop-filter: blur(5px);
    }

    .create-title {
        margin: 0;
        font-size: 23px;
        font-weight: 750;
        letter-spacing: -.3px;
    }

    .create-subtitle {
        margin: 4px 0 0;
        font-size: 12px;
        color: rgba(255,255,255,.80);
    }

    /* =========================================================
       FORM CARD
    ========================================================= */

    .transport-form-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .form-card-header {
        padding: 21px 25px;
        border-bottom: 1px solid #e9eef5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .form-card-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-heading-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dbeafe;
        color: #1769d1;
        font-size: 17px;
    }

    .form-card-heading h5 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 700;
    }

    .form-card-heading p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 11px;
    }

    .required-note {
        font-size: 11px;
        color: #64748b;
    }

    .required-note span {
        color: #e94d47;
    }

    /* =========================================================
       FORM BODY
    ========================================================= */

    .transport-form-body {
        padding: 28px 25px 24px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 18px;
    }

    .section-heading-icon {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: #f2f7ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .section-heading h6 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #172033;
    }

    .section-heading span {
        color: #94a3b8;
        font-size: 10px;
        margin-left: 2px;
    }

    /* =========================================================
       FORM GROUP
    ========================================================= */

    .transport-form-group {
        margin-bottom: 19px;
    }

    .transport-form-group label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 11.5px;
        font-weight: 650;
    }

    .transport-form-group label .required {
        color: #e94d47;
        margin-left: 2px;
    }

    .transport-input-wrapper {
        position: relative;
    }

    .transport-input-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 2;
    }

    .transport-form-control,
    .transport-form-select,
    .transport-form-textarea {
        width: 100%;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        background: #f8fafc;
        color: #172033;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        transition: all .2s ease;
        box-sizing: border-box;
    }

    .transport-form-control,
    .transport-form-select {
        height: 43px;
        padding: 0 13px;
    }

    .transport-form-control.with-icon,
    .transport-form-select.with-icon {
        padding-left: 39px;
    }

    .transport-form-textarea {
        min-height: 105px;
        padding: 12px 13px;
        resize: vertical;
        line-height: 1.5;
    }

    .transport-form-control::placeholder,
    .transport-form-textarea::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .transport-form-control:hover,
    .transport-form-select:hover,
    .transport-form-textarea:hover {
        background: #fff;
        border-color: #cbd5e1;
    }

    .transport-form-control:focus,
    .transport-form-select:focus,
    .transport-form-textarea:focus {
        background: #fff;
        border-color: #8bb8ef;
        box-shadow: 0 0 0 3px rgba(23,105,209,.08);
    }

    .transport-form-control[readonly] {
        cursor: default;
        background: #f8fafc;
        color: #475569;
    }

    /* =========================================================
       CLASS SELECT
    ========================================================= */

    .class-select-wrapper {
        position: relative;
    }

    .class-select-wrapper .transport-input-icon {
        z-index: 3;
    }

    .class-select {
        cursor: pointer;
    }

    .class-loading {
        margin-top: 6px;
        color: #1769d1;
        font-size: 10px;
        display: none;
        align-items: center;
        gap: 5px;
    }

    .class-loading.show {
        display: flex;
    }

    .class-loading i {
        animation: transportSpin .8s linear infinite;
    }

    @keyframes transportSpin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* =========================================================
       SEARCHABLE STUDENT SELECT
    ========================================================= */

    .student-search-wrapper {
        position: relative;
    }

    .student-search-input {
        width: 100%;
        height: 43px;
        padding: 0 42px 0 39px;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        background: #f8fafc;
        color: #172033;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        box-sizing: border-box;
        transition: all .2s ease;
    }

    .student-search-input:hover {
        background: #fff;
        border-color: #cbd5e1;
    }

    .student-search-input:focus {
        background: #fff;
        border-color: #8bb8ef;
        box-shadow: 0 0 0 3px rgba(23,105,209,.08);
    }

    .student-search-input:disabled {
        cursor: not-allowed;
        background: #f1f5f9;
        color: #94a3b8;
    }

    .student-search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 2;
    }

    .student-search-arrow {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
    }

    .student-dropdown {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 6px);
        background: #fff;
        border: 1px solid #e1e8f0;
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(15,23,42,.12);
        max-height: 260px;
        overflow-y: auto;
        z-index: 100;
        display: none;
    }

    .student-dropdown.show {
        display: block;
    }

    .student-option {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f3f7;
        transition: background .15s ease;
    }

    .student-option:last-child {
        border-bottom: none;
    }

    .student-option:hover {
        background: #f2f7ff;
    }

    .student-option-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #dbeafe;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 13px;
        font-weight: 700;
    }

    .student-option-info {
        min-width: 0;
        flex: 1;
    }

    .student-option-name {
        font-size: 11.5px;
        font-weight: 650;
        color: #172033;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-option-meta {
        margin-top: 2px;
        color: #94a3b8;
        font-size: 9.5px;
    }

    .student-no-result {
        padding: 18px;
        text-align: center;
        color: #94a3b8;
        font-size: 11px;
    }

    .student-loading {
        padding: 18px;
        text-align: center;
        color: #1769d1;
        font-size: 11px;
    }

    .student-loading i {
        display: inline-block;
        animation: transportSpin .8s linear infinite;
    }

    /* =========================================================
       SELECTED STUDENT INFO
    ========================================================= */

    .selected-student-card {
        display: none;
        margin-top: 18px;
        border: 1px solid #dbeafe;
        background: #f8fbff;
        border-radius: 13px;
        padding: 18px;
    }

    .selected-student-card.show {
        display: block;
    }

    .selected-student-header {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 15px;
        padding-bottom: 13px;
        border-bottom: 1px solid #e5eef9;
    }

    .selected-student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #dbeafe;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .selected-student-header h6 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: #172033;
    }

    .selected-student-header p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 10px;
    }

    .student-detail-box {
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 10px;
        padding: 10px 11px;
        min-height: 61px;
    }

    .student-detail-label {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #94a3b8;
        font-size: 9.5px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .student-detail-label i {
        color: #1769d1;
        font-size: 10px;
    }

    .student-detail-value {
        color: #172033;
        font-size: 11px;
        font-weight: 650;
        word-break: break-word;
    }

    /* =========================================================
       SELECT
    ========================================================= */

    .transport-form-select {
        cursor: pointer;
    }

    /* =========================================================
       STATUS OPTIONS
    ========================================================= */

    .status-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .status-option {
        position: relative;
        flex: 1;
        min-width: 140px;
    }

    .status-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .status-option label {
        width: 100%;
        min-height: 43px;
        padding: 0 14px;
        margin: 0;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        background: #f8fafc;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all .2s ease;
        box-sizing: border-box;
        font-size: 11.5px;
        font-weight: 600;
    }

    .status-option label i {
        font-size: 14px;
    }

    .status-option.active-option label {
        color: #15803d;
    }

    .status-option.active-option input:checked + label {
        background: #f0fdf4;
        border-color: #86efac;
        color: #15803d;
        box-shadow: 0 0 0 3px rgba(22,163,74,.06);
    }

    .status-option.inactive-option label {
        color: #dc2626;
    }

    .status-option.inactive-option input:checked + label {
        background: #fff7f7;
        border-color: #fca5a5;
        color: #dc2626;
        box-shadow: 0 0 0 3px rgba(233,77,71,.06);
    }

    /* =========================================================
       PAYMENT OPTIONS
    ========================================================= */

    .payment-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .payment-option {
        position: relative;
        flex: 1;
        min-width: 120px;
    }

    .payment-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-option label {
        width: 100%;
        min-height: 43px;
        padding: 0 13px;
        margin: 0;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        background: #f8fafc;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        transition: all .2s ease;
        box-sizing: border-box;
        font-size: 11px;
        font-weight: 600;
    }

    .payment-option label i {
        font-size: 13px;
    }

    .payment-option.paid-option label {
        color: #15803d;
    }

    .payment-option.paid-option input:checked + label {
        background: #f0fdf4;
        border-color: #86efac;
        color: #15803d;
        box-shadow: 0 0 0 3px rgba(22,163,74,.06);
    }

    .payment-option.pending-option label {
        color: #c2410c;
    }

    .payment-option.pending-option input:checked + label {
        background: #fff7ed;
        border-color: #fdba74;
        color: #c2410c;
        box-shadow: 0 0 0 3px rgba(234,88,12,.06);
    }

    .payment-option.partial-option label {
        color: #1769d1;
    }

    .payment-option.partial-option input:checked + label {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23,105,209,.06);
    }

    /* =========================================================
       FIELD HINT
    ========================================================= */

    .field-hint {
        margin-top: 5px;
        color: #94a3b8;
        font-size: 10px;
        line-height: 1.4;
    }

    /* =========================================================
       VALIDATION
    ========================================================= */

    .transport-form-control.is-invalid,
    .transport-form-select.is-invalid,
    .student-search-input.is-invalid {
        border-color: #f1a4a0;
        background: #fffafa;
    }

    .transport-form-control.is-invalid:focus,
    .transport-form-select.is-invalid:focus,
    .student-search-input.is-invalid:focus {
        border-color: #e94d47;
        box-shadow: 0 0 0 3px rgba(233,77,71,.08);
    }

    .field-error {
        margin-top: 5px;
        color: #dc2626;
        font-size: 10.5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* =========================================================
       DIVIDER
    ========================================================= */

    .form-divider {
        height: 1px;
        background: #edf1f6;
        margin: 26px 0;
    }

    /* =========================================================
       FOOTER ACTIONS
    ========================================================= */

    .form-actions {
        padding: 18px 25px;
        border-top: 1px solid #e9eef5;
        background: #fbfcfe;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-transport-cancel,
    .btn-transport-save {
        height: 40px;
        border-radius: 9px;
        padding: 0 17px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 11.5px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
        border: none;
    }

    .btn-transport-cancel {
        color: #475569;
        background: #fff;
        border: 1px solid #dfe6ef;
    }

    .btn-transport-cancel:hover {
        background: #f8fafc;
        color: #172033;
        border-color: #cbd5e1;
    }

    .btn-transport-save {
        color: #fff;
        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #237de0 100%
        );
        box-shadow: 0 5px 12px rgba(23,105,209,.20);
    }

    .btn-transport-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 7px 16px rgba(23,105,209,.25);
    }

    .btn-transport-save:active {
        transform: translateY(0);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .transport-create-header {
            padding: 23px 20px;
        }

        .transport-form-body {
            padding: 22px 18px;
        }

        .form-card-header {
            padding: 18px;
        }

        .form-actions {
            padding: 16px 18px;
        }

        .status-option,
        .payment-option {
            min-width: 100%;
        }
    }

    @media (max-width: 576px) {

        .create-title {
            font-size: 19px;
        }

        .create-title-icon {
            width: 45px;
            height: 45px;
            font-size: 19px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-transport-cancel,
        .btn-transport-save {
            width: 100%;
        }

        .selected-student-card {
            padding: 14px;
        }
    }
</style>

<div class="transport-create-page">

```
<div class="container-fluid py-4">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="transport-create-header">

        <div class="create-header-content">

            <div class="create-breadcrumb">

                <a href="{{ route('admin.transport.records.index') }}">
                    Transport Records
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>Add Record</span>

            </div>

            <div class="create-title-row">

                <div class="create-title-icon">
                    <i class="bi bi-plus-lg"></i>
                </div>

                <div>

                    <h1 class="create-title">
                        Add Transport Record
                    </h1>

                    <p class="create-subtitle">
                        Assign transport service and fee details to a student.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FORM CARD
    ====================================================== --}}

    <div class="transport-form-card">

        {{-- CARD HEADER --}}

        <div class="form-card-header">

            <div class="form-card-heading">

                <div class="form-heading-icon">
                    <i class="bi bi-bus-front"></i>
                </div>

                <div>

                    <h5>
                        Add Transport Record
                    </h5>

                    <p>
                        Enter the student's transport assignment details.
                    </p>

                </div>

            </div>

            <div class="required-note">
                <span>*</span> Required fields
            </div>

        </div>


        {{-- =================================================
             FORM
        ================================================== --}}

        <form
            action="{{ route('admin.transport.records.store') }}"
            method="POST"
            id="transportRecordForm"
        >

            @csrf


            <div class="transport-form-body">


                {{-- =================================================
                     1. STUDENT INFORMATION
                ================================================== --}}

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-heading-icon">
                            <i class="bi bi-person-vcard"></i>
                        </div>

                        <div>

                            <h6>
                                Student Information
                            </h6>

                            <span>
                                Select a class first, then choose a student.
                            </span>

                        </div>

                    </div>


                    <div class="row">


                        {{-- =================================================
                             CLASS
                        ================================================== --}}

                        <div class="col-lg-5">

                            <div class="transport-form-group">

                                <label for="class_id">
                                    Select Class
                                    <span class="required">*</span>
                                </label>

                                <div class="transport-input-wrapper class-select-wrapper">

                                    <i class="bi bi-mortarboard transport-input-icon"></i>

                                    <select
                                        name="class_filter"
                                        id="class_id"
                                        class="transport-form-select with-icon @error('class_filter') is-invalid @enderror"
                                    >

                                        <option value="">
                                            Select class
                                        </option>

                                        @forelse(($classes ?? collect()) as $schoolClass)

                                            <option
                                                value="{{ $schoolClass->id }}"
                                                data-class-name="{{ $schoolClass->class_name }}"
                                                data-section="{{ $schoolClass->section }}"
                                                data-academic-year="{{ $schoolClass->academic_year }}"
                                                {{ (string) old('class_filter') === (string) $schoolClass->id ? 'selected' : '' }}
                                            >
                                                {{ $schoolClass->class_name }}

                                                @if($schoolClass->section)
                                                    - {{ $schoolClass->section }}
                                                @endif

                                                @if($schoolClass->academic_year)
                                                    ({{ $schoolClass->academic_year }})
                                                @endif
                                            </option>

                                        @empty

                                            <option value="" disabled>
                                                No active classes found
                                            </option>

                                        @endforelse

                                    </select>

                                </div>

                                @error('class_filter')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div
                                    class="class-loading"
                                    id="classLoading"
                                >
                                    <i class="bi bi-arrow-repeat"></i>
                                    Loading students...
                                </div>

                                <div class="field-hint">
                                    Select the class and section to load its students.
                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             STUDENT SEARCH
                        ================================================== --}}

                        <div class="col-lg-7">

                            <div class="transport-form-group">

                                <label for="student_search">
                                    Select Student
                                    <span class="required">*</span>
                                </label>

                                <div class="student-search-wrapper">

                                    <i class="bi bi-search student-search-icon"></i>

                                    <input
                                        type="text"
                                        id="student_search"
                                        class="student-search-input @error('student_id') is-invalid @enderror"
                                        placeholder="Select a class first..."
                                        autocomplete="off"
                                        value=""
                                        disabled
                                    >

                                    <i class="bi bi-chevron-down student-search-arrow"></i>

                                    <input
                                        type="hidden"
                                        name="student_id"
                                        id="student_id"
                                        value="{{ old('student_id') }}"
                                    >

                                    <div
                                        class="student-dropdown"
                                        id="studentDropdown"
                                    >

                                        <div
                                            class="student-no-result"
                                            id="studentInitialMessage"
                                        >
                                            <i class="bi bi-mortarboard me-1"></i>
                                            Select a class to load students.
                                        </div>

                                    </div>

                                </div>

                                @error('student_id')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="field-hint">
                                    Search by student name, student ID, or roll number.
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SELECTED STUDENT DETAILS --}}

                    <div
                        class="selected-student-card"
                        id="selectedStudentCard"
                    >

                        <div class="selected-student-header">

                            <div
                                class="selected-student-avatar"
                                id="selectedStudentAvatar"
                            >
                                S
                            </div>

                            <div>

                                <h6 id="selectedStudentTitle">
                                    Student Details
                                </h6>

                                <p>
                                    Existing student information
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            {{-- STUDENT NAME --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="student-detail-box">

                                    <div class="student-detail-label">
                                        <i class="bi bi-person"></i>
                                        Student Name
                                    </div>

                                    <div
                                        class="student-detail-value"
                                        id="studentName"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- ROLL / STUDENT ID --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="student-detail-box">

                                    <div class="student-detail-label">
                                        <i class="bi bi-card-text"></i>
                                        Roll No / Student ID
                                    </div>

                                    <div
                                        class="student-detail-value"
                                        id="studentRollId"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- CLASS --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="student-detail-box">

                                    <div class="student-detail-label">
                                        <i class="bi bi-mortarboard"></i>
                                        Class
                                    </div>

                                    <div
                                        class="student-detail-value"
                                        id="studentClass"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- DIVISION --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="student-detail-box">

                                    <div class="student-detail-label">
                                        <i class="bi bi-grid"></i>
                                        Division
                                    </div>

                                    <div
                                        class="student-detail-value"
                                        id="studentDivision"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- PARENT NAME --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="student-detail-box">

                                    <div class="student-detail-label">
                                        <i class="bi bi-person-heart"></i>
                                        Parent Name
                                    </div>

                                    <div
                                        class="student-detail-value"
                                        id="studentParentName"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- PARENT PHONE --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="student-detail-box">

                                    <div class="student-detail-label">
                                        <i class="bi bi-telephone"></i>
                                        Parent Phone No.
                                    </div>

                                    <div
                                        class="student-detail-value"
                                        id="studentParentPhone"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- ADDRESS --}}

                            <div class="col-12">

                                <div class="student-detail-box">

                                    <div class="student-detail-label">
                                        <i class="bi bi-geo-alt"></i>
                                        Address
                                    </div>

                                    <div
                                        class="student-detail-value"
                                        id="studentAddress"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="form-divider"></div>


                {{-- =================================================
                     2. TRANSPORT INFORMATION
                ================================================== --}}

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-heading-icon">
                            <i class="bi bi-bus-front"></i>
                        </div>

                        <div>

                            <h6>
                                Transport Information
                            </h6>

                            <span>
                                Enter the student's assigned transport details.
                            </span>

                        </div>

                    </div>


                    <div class="row">

                        {{-- ROUTE --}}

                        <div class="col-lg-6">

                            <div class="transport-form-group">

                                <label for="route">
                                    Route
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-signpost-2 transport-input-icon"></i>

                                    <input
                                        type="text"
                                        name="route"
                                        id="route"
                                        value="{{ old('route') }}"
                                        class="transport-form-control with-icon @error('route') is-invalid @enderror"
                                        placeholder="e.g. Route 01 - Chandgad"
                                    >

                                </div>

                                @error('route')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- VEHICLE --}}

                        <div class="col-lg-6">

                            <div class="transport-form-group">

                                <label for="vehicle">
                                    Vehicle
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-bus-front transport-input-icon"></i>

                                    <input
                                        type="text"
                                        name="vehicle"
                                        id="vehicle"
                                        value="{{ old('vehicle') }}"
                                        class="transport-form-control with-icon @error('vehicle') is-invalid @enderror"
                                        placeholder="e.g. MH-09-AB-1234"
                                    >

                                </div>

                                @error('vehicle')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- PICKUP POINT --}}

                        <div class="col-lg-6">

                            <div class="transport-form-group">

                                <label for="pickup_point">
                                    Pickup Point
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-geo-alt transport-input-icon"></i>

                                    <input
                                        type="text"
                                        name="pickup_point"
                                        id="pickup_point"
                                        value="{{ old('pickup_point') }}"
                                        class="transport-form-control with-icon @error('pickup_point') is-invalid @enderror"
                                        placeholder="e.g. Main Bus Stop"
                                    >

                                </div>

                                @error('pickup_point')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- DROP POINT --}}

                        <div class="col-lg-6">

                            <div class="transport-form-group">

                                <label for="drop_point">
                                    Drop Point
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-geo-alt-fill transport-input-icon"></i>

                                    <input
                                        type="text"
                                        name="drop_point"
                                        id="drop_point"
                                        value="{{ old('drop_point') }}"
                                        class="transport-form-control with-icon @error('drop_point') is-invalid @enderror"
                                        placeholder="e.g. School Main Gate"
                                    >

                                </div>

                                @error('drop_point')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- TRANSPORT STATUS --}}

                        <div class="col-lg-6">

                            <div class="transport-form-group">

                                <label>
                                    Transport Status
                                    <span class="required">*</span>
                                </label>

                                <div class="status-options">

                                    <div class="status-option active-option">

                                        <input
                                            type="radio"
                                            name="transport_status"
                                            id="status_active"
                                            value="active"
                                            {{ old('transport_status', 'active') === 'active' ? 'checked' : '' }}
                                        >

                                        <label for="status_active">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Active

                                        </label>

                                    </div>


                                    <div class="status-option inactive-option">

                                        <input
                                            type="radio"
                                            name="transport_status"
                                            id="status_inactive"
                                            value="inactive"
                                            {{ old('transport_status') === 'inactive' ? 'checked' : '' }}
                                        >

                                        <label for="status_inactive">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Inactive

                                        </label>

                                    </div>

                                </div>

                                @error('transport_status')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- START DATE --}}

                        <div class="col-lg-3 col-md-6">

                            <div class="transport-form-group">

                                <label for="start_date">
                                    Start Date
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-calendar-event transport-input-icon"></i>

                                    <input
                                        type="date"
                                        name="start_date"
                                        id="start_date"
                                        value="{{ old('start_date') }}"
                                        class="transport-form-control with-icon @error('start_date') is-invalid @enderror"
                                    >

                                </div>

                                @error('start_date')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- END DATE --}}

                        <div class="col-lg-3 col-md-6">

                            <div class="transport-form-group">

                                <label for="end_date">
                                    End Date
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-calendar-check transport-input-icon"></i>

                                    <input
                                        type="date"
                                        name="end_date"
                                        id="end_date"
                                        value="{{ old('end_date') }}"
                                        class="transport-form-control with-icon @error('end_date') is-invalid @enderror"
                                    >

                                </div>

                                @error('end_date')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="field-hint">
                                    Must be equal to or later than start date.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="form-divider"></div>


                {{-- =================================================
                     3. TRAVEL DETAILS
                ================================================== --}}

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-heading-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div>

                            <h6>
                                Travel Details
                            </h6>

                            <span>
                                Set the transport type and daily travel timings.
                            </span>

                        </div>

                    </div>


                    <div class="row">

                        {{-- TRANSPORT TYPE --}}

                        <div class="col-lg-4">

                            <div class="transport-form-group">

                                <label for="transport_type">
                                    Transport Type
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-bus-front transport-input-icon"></i>

                                    <select
                                        name="transport_type"
                                        id="transport_type"
                                        class="transport-form-select with-icon @error('transport_type') is-invalid @enderror"
                                    >

                                        <option value="">
                                            Select transport type
                                        </option>

                                        <option
                                            value="school_bus"
                                            {{ old('transport_type') === 'school_bus' ? 'selected' : '' }}
                                        >
                                            School Bus
                                        </option>

                                        <option
                                            value="van"
                                            {{ old('transport_type') === 'van' ? 'selected' : '' }}
                                        >
                                            Van
                                        </option>

                                        <option
                                            value="private"
                                            {{ old('transport_type') === 'private' ? 'selected' : '' }}
                                        >
                                            Private
                                        </option>

                                        <option
                                            value="other"
                                            {{ old('transport_type') === 'other' ? 'selected' : '' }}
                                        >
                                            Other
                                        </option>

                                    </select>

                                </div>

                                @error('transport_type')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- PICKUP TIME --}}

                        <div class="col-lg-4">

                            <div class="transport-form-group">

                                <label for="pickup_time">
                                    Pickup Time
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-alarm transport-input-icon"></i>

                                    <input
                                        type="time"
                                        name="pickup_time"
                                        id="pickup_time"
                                        value="{{ old('pickup_time') }}"
                                        class="transport-form-control with-icon @error('pickup_time') is-invalid @enderror"
                                    >

                                </div>

                                @error('pickup_time')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- DROP TIME --}}

                        <div class="col-lg-4">

                            <div class="transport-form-group">

                                <label for="drop_time">
                                    Drop Time
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-clock transport-input-icon"></i>

                                    <input
                                        type="time"
                                        name="drop_time"
                                        id="drop_time"
                                        value="{{ old('drop_time') }}"
                                        class="transport-form-control with-icon @error('drop_time') is-invalid @enderror"
                                    >

                                </div>

                                @error('drop_time')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                <div class="form-divider"></div>


                {{-- =================================================
                     4. FEE INFORMATION
                ================================================== --}}

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-heading-icon">
                            <i class="bi bi-currency-rupee"></i>
                        </div>

                        <div>

                            <h6>
                                Fee Information
                            </h6>

                            <span>
                                Enter transport fee and payment information.
                            </span>

                        </div>

                    </div>


                    <div class="row">

                        {{-- TRANSPORT FEE --}}

                        <div class="col-lg-4">

                            <div class="transport-form-group">

                                <label for="transport_fee">
                                    Transport Fee
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-currency-rupee transport-input-icon"></i>

                                    <input
                                        type="number"
                                        name="transport_fee"
                                        id="transport_fee"
                                        value="{{ old('transport_fee') }}"
                                        class="transport-form-control with-icon @error('transport_fee') is-invalid @enderror"
                                        placeholder="e.g. 1500"
                                        min="0"
                                        step="0.01"
                                    >

                                </div>

                                @error('transport_fee')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- FEE FREQUENCY --}}

                        <div class="col-lg-4">

                            <div class="transport-form-group">

                                <label for="fee_frequency">
                                    Fee Frequency
                                </label>

                                <div class="transport-input-wrapper">

                                    <i class="bi bi-calendar3 transport-input-icon"></i>

                                    <select
                                        name="fee_frequency"
                                        id="fee_frequency"
                                        class="transport-form-select with-icon @error('fee_frequency') is-invalid @enderror"
                                    >

                                        <option value="">
                                            Select frequency
                                        </option>

                                        <option
                                            value="monthly"
                                            {{ old('fee_frequency') === 'monthly' ? 'selected' : '' }}
                                        >
                                            Monthly
                                        </option>

                                        <option
                                            value="quarterly"
                                            {{ old('fee_frequency') === 'quarterly' ? 'selected' : '' }}
                                        >
                                            Quarterly
                                        </option>

                                        <option
                                            value="yearly"
                                            {{ old('fee_frequency') === 'yearly' ? 'selected' : '' }}
                                        >
                                            Yearly
                                        </option>

                                    </select>

                                </div>

                                @error('fee_frequency')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- PAYMENT STATUS --}}

                        <div class="col-lg-4">

                            <div class="transport-form-group">

                                <label>
                                    Payment Status
                                    <span class="required">*</span>
                                </label>

                                <div class="payment-options">

                                    <div class="payment-option paid-option">

                                        <input
                                            type="radio"
                                            name="payment_status"
                                            id="payment_paid"
                                            value="paid"
                                            {{ old('payment_status') === 'paid' ? 'checked' : '' }}
                                        >

                                        <label for="payment_paid">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Paid

                                        </label>

                                    </div>


                                    <div class="payment-option pending-option">

                                        <input
                                            type="radio"
                                            name="payment_status"
                                            id="payment_pending"
                                            value="pending"
                                            {{ old('payment_status', 'pending') === 'pending' ? 'checked' : '' }}
                                        >

                                        <label for="payment_pending">

                                            <i class="bi bi-clock-fill"></i>

                                            Pending

                                        </label>

                                    </div>


                                    <div class="payment-option partial-option">

                                        <input
                                            type="radio"
                                            name="payment_status"
                                            id="payment_partial"
                                            value="partially_paid"
                                            {{ old('payment_status') === 'partially_paid' ? 'checked' : '' }}
                                        >

                                        <label for="payment_partial">

                                            <i class="bi bi-circle-half"></i>

                                            Partial

                                        </label>

                                    </div>

                                </div>

                                @error('payment_status')

                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FORM ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('admin.transport.records.index') }}"
                    class="btn-transport-cancel"
                >
                    <i class="bi bi-arrow-left"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-transport-save"
                >
                    <i class="bi bi-check2-circle"></i>
                    Save Transport Record
                </button>

            </div>

        </form>

    </div>

</div>
```

</div>

{{-- =============================================================
CLASS + STUDENT SEARCH + AUTO DETAILS SCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================= */

    const classSelect =
        document.getElementById('class_id');

    const classLoading =
        document.getElementById('classLoading');

    const searchInput =
        document.getElementById('student_search');

    const hiddenStudentId =
        document.getElementById('student_id');

    const dropdown =
        document.getElementById('studentDropdown');

    const selectedCard =
        document.getElementById('selectedStudentCard');

    const selectedAvatar =
        document.getElementById('selectedStudentAvatar');

    const selectedTitle =
        document.getElementById('selectedStudentTitle');

    const studentName =
        document.getElementById('studentName');

    const studentRollId =
        document.getElementById('studentRollId');

    const studentClass =
        document.getElementById('studentClass');

    const studentDivision =
        document.getElementById('studentDivision');

    const studentParentName =
        document.getElementById('studentParentName');

    const studentParentPhone =
        document.getElementById('studentParentPhone');

    const studentAddress =
        document.getElementById('studentAddress');


    /* =========================================================
       CORRECT AJAX ENDPOINT
    ========================================================= */

    const studentsByClassUrl =
    @json(route('admin.transport.records.students-by-class'));


    let students = [];


    /* =========================================================
       HELPER
    ========================================================= */

    function displayValue(value) {

        if (
            value === null ||
            value === undefined ||
            String(value).trim() === ''
        ) {
            return 'Not available';
        }

        return value;

    }


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* =========================================================
       CLEAR SELECTED STUDENT
    ========================================================= */

    function clearSelectedStudent() {

        hiddenStudentId.value = '';

        searchInput.value = '';

        selectedCard.classList.remove('show');

        studentName.textContent = '—';
        studentRollId.textContent = '—';
        studentClass.textContent = '—';
        studentDivision.textContent = '—';
        studentParentName.textContent = '—';
        studentParentPhone.textContent = '—';
        studentAddress.textContent = '—';

        selectedAvatar.textContent = 'S';
        selectedTitle.textContent = 'Student Details';

    }


    /* =========================================================
       SHOW SELECTED STUDENT
    ========================================================= */

    function selectStudent(student) {

        const id =
            student.id || '';

        const name =
            student.full_name || '';

        const studentId =
            student.student_id || '';

        const rollNumber =
            student.roll_number || '';

        const studentClassValue =
            student.class || '';

        const section =
            student.section || '';

        const parentName =
            student.father_name || '';

        const parentPhone =
            student.father_phone || '';

        const address =
            student.address || '';


        /* SAVE STUDENT ID */

        hiddenStudentId.value = id;


        /* SEARCH FIELD */

        searchInput.value = name;


        /* STUDENT DETAILS */

        studentName.textContent =
            displayValue(name);

        studentRollId.textContent =
            studentId && rollNumber
                ? rollNumber + ' / ' + studentId
                : (rollNumber || studentId || 'Not available');

        studentClass.textContent =
            displayValue(studentClassValue);

        studentDivision.textContent =
            displayValue(section);

        studentParentName.textContent =
            displayValue(parentName);

        studentParentPhone.textContent =
            displayValue(parentPhone);

        studentAddress.textContent =
            displayValue(address);


        /* AVATAR */

        selectedAvatar.textContent =
            name
                ? name.charAt(0).toUpperCase()
                : 'S';


        selectedTitle.textContent =
            name || 'Student Details';


        /* SHOW CARD */

        selectedCard.classList.add('show');


        /* CLOSE DROPDOWN */

        dropdown.classList.remove('show');


        /* CLEAR INVALID STATE */

        searchInput.classList.remove('is-invalid');

    }


    /* =========================================================
       RENDER STUDENTS
    ========================================================= */

    function renderStudents(list) {

        dropdown.innerHTML = '';

        if (!list.length) {

            const noResult =
                document.createElement('div');

            noResult.className =
                'student-no-result';

            noResult.innerHTML =
                '<i class="bi bi-person-x me-1"></i> No students found in this class.';

            dropdown.appendChild(noResult);

            return;

        }


        list.forEach(function (student) {

            const option =
                document.createElement('div');

            option.className =
                'student-option';


            const firstLetter =
                student.full_name
                    ? student.full_name.charAt(0).toUpperCase()
                    : 'S';


            option.innerHTML = `

                <div class="student-option-avatar">
                    ${escapeHtml(firstLetter)}
                </div>

                <div class="student-option-info">

                    <div class="student-option-name">
                        ${escapeHtml(student.full_name || 'Student')}
                    </div>

                    <div class="student-option-meta">

                        ${student.student_id
                            ? 'ID: ' + escapeHtml(student.student_id)
                            : ''
                        }

                        ${student.roll_number
                            ? ' • Roll No: ' + escapeHtml(student.roll_number)
                            : ''
                        }

                        ${student.class
                            ? ' • Class: ' + escapeHtml(student.class)
                            : ''
                        }

                        ${student.section
                            ? ' - ' + escapeHtml(student.section)
                            : ''
                        }

                    </div>

                </div>

            `;


            option.addEventListener(
                'click',
                function () {

                    selectStudent(student);

                }
            );


            dropdown.appendChild(option);

        });

    }


    /* =========================================================
       FILTER STUDENTS
    ========================================================= */

    function filterStudents() {

        const searchTerm =
            searchInput.value
                .trim()
                .toLowerCase();


        const filtered =
            students.filter(function (student) {

                const name =
                    (student.full_name || '')
                        .toLowerCase();

                const studentId =
                    (student.student_id || '')
                        .toLowerCase();

                const rollNumber =
                    (student.roll_number || '')
                        .toLowerCase();


                return (
                    name.includes(searchTerm) ||
                    studentId.includes(searchTerm) ||
                    rollNumber.includes(searchTerm)
                );

            });


        renderStudents(filtered);

    }


    /* =========================================================
       LOAD STUDENTS FOR SELECTED CLASS
    ========================================================= */

    async function loadStudentsForClass(
        classId,
        studentIdToRestore = null
    ) {

        students = [];

        clearSelectedStudent();

        dropdown.innerHTML = `
            <div class="student-no-result">
                <i class="bi bi-mortarboard me-1"></i>
                Select a class to load students.
            </div>
        `;

        dropdown.classList.remove('show');


        if (!classId) {

            searchInput.disabled = true;

            searchInput.placeholder =
                'Select a class first...';

            classLoading.classList.remove('show');

            return;

        }


        searchInput.disabled = true;

        searchInput.placeholder =
            'Loading students...';

        classLoading.classList.add('show');


        dropdown.innerHTML = `
            <div class="student-loading">
                <i class="bi bi-arrow-repeat me-1"></i>
                Loading students...
            </div>
        `;


        try {

            const response =
                await fetch(
                    studentsByClassUrl +
                    '?class_id=' +
                    encodeURIComponent(classId),
                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load students.'
                );

            }


            const data =
                await response.json();


            students =
                Array.isArray(data.students)
                    ? data.students
                    : [];


            searchInput.disabled = false;

            searchInput.placeholder =
                students.length
                    ? 'Search student by name or student ID...'
                    : 'No students found in this class';


            if (students.length) {

                renderStudents(students);

            } else {

                dropdown.innerHTML = `
                    <div class="student-no-result">
                        <i class="bi bi-person-x me-1"></i>
                        No students found in this class.
                    </div>
                `;

            }


            /* =================================================
               RESTORE OLD STUDENT AFTER VALIDATION ERROR
            ================================================== */

            if (studentIdToRestore) {

                const oldStudent =
                    students.find(function (student) {

                        return String(student.id) ===
                            String(studentIdToRestore);

                    });


                if (oldStudent) {

                    selectStudent(oldStudent);

                }

            }


        } catch (error) {

            console.error(
                'Transport student loading error:',
                error
            );


            searchInput.disabled = true;

            searchInput.placeholder =
                'Unable to load students';


            dropdown.innerHTML = `
                <div class="student-no-result">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    Unable to load students. Please try again.
                </div>
            `;

        } finally {

            classLoading.classList.remove('show');

        }

    }


    /* =========================================================
       CLASS CHANGE
    ========================================================= */

    classSelect.addEventListener(
        'change',
        function () {

            const classId =
                this.value;


            /*
             * Every time class changes,
             * previously selected student is cleared.
             */

            hiddenStudentId.value = '';

            loadStudentsForClass(
                classId
            );

        }
    );


    /* =========================================================
       OPEN STUDENT DROPDOWN
    ========================================================= */

    searchInput.addEventListener(
        'focus',
        function () {

            if (
                searchInput.disabled ||
                !students.length
            ) {
                return;
            }


            filterStudents();

            dropdown.classList.add('show');

        }
    );


    searchInput.addEventListener(
        'click',
        function () {

            if (
                searchInput.disabled ||
                !students.length
            ) {
                return;
            }


            filterStudents();

            dropdown.classList.add('show');

        }
    );


    /* =========================================================
       STUDENT SEARCH
    ========================================================= */

    searchInput.addEventListener(
        'input',
        function () {

            /*
             * If user starts typing after selecting a student,
             * clear the selected student so they must select
             * another valid student.
             */

            if (
                hiddenStudentId.value &&
                searchInput.value !==
                    (
                        students.find(function (student) {
                            return String(student.id) ===
                                String(hiddenStudentId.value);
                        })?.full_name || ''
                    )
            ) {

                hiddenStudentId.value = '';

                selectedCard.classList.remove('show');

            }


            if (
                searchInput.disabled ||
                !students.length
            ) {
                return;
            }


            filterStudents();

            dropdown.classList.add('show');

        }
    );


    /* =========================================================
       CLOSE DROPDOWN OUTSIDE
    ========================================================= */

    document.addEventListener(
        'click',
        function (event) {

            const wrapper =
                document.querySelector(
                    '.student-search-wrapper'
                );


            if (
                wrapper &&
                !wrapper.contains(event.target)
            ) {

                dropdown.classList.remove('show');

            }

        }
    );


    /* =========================================================
       FORM VALIDATION
    ========================================================= */

    document.getElementById('transportRecordForm')
        .addEventListener(
            'submit',
            function (event) {

                /*
                 * Class is required only for selecting
                 * the correct student. It is not submitted
                 * as a database field.
                 */

                if (!classSelect.value) {

                    event.preventDefault();

                    classSelect.classList.add(
                        'is-invalid'
                    );

                    classSelect.focus();

                    return;

                }


                /*
                 * Student must be selected.
                 */

                if (!hiddenStudentId.value) {

                    event.preventDefault();

                    searchInput.classList.add(
                        'is-invalid'
                    );

                    searchInput.focus();

                    dropdown.classList.add(
                        'show'
                    );

                    return;

                }

            }
        );


    /* =========================================================
       REMOVE CLASS INVALID STATE
    ========================================================= */

    classSelect.addEventListener(
        'change',
        function () {

            if (this.value) {

                this.classList.remove(
                    'is-invalid'
                );

            }

        }
    );


    /* =========================================================
       RESTORE OLD VALUES AFTER VALIDATION ERROR
    ========================================================= */

    const oldClassId =
        @json(old('class_filter'));

    const oldStudentId =
        @json(old('student_id'));


    if (oldClassId) {

        classSelect.value =
            oldClassId;


        loadStudentsForClass(
            oldClassId,
            oldStudentId
        );

    }


});

</script>

@endsection
