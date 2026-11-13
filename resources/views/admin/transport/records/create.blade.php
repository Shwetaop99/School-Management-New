@extends('layouts.app')

@section('title', 'Add Transport Record | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — ADD TRANSPORT RECORD
    ========================================================= */

    .transport-create-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 35px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .transport-create-page *,
    .transport-create-page *::before,
    .transport-create-page *::after {
        box-sizing: border-box;
    }

    /* PAGE HEADER */

    .transport-create-header {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 20px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
    }

    .create-header-content {
        position: relative;
        z-index: 1;
    }

    .create-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 13px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .create-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .create-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .create-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .create-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .create-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        color: #34775a;
        background: #eaf3e9;
        border: 1px solid #d8e8d8;
        border-radius: 10px;
        font-size: 21px;
    }

    .create-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.3;
        letter-spacing: -.35px;
    }

    .create-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    /* FORM CARD */

    .transport-form-card {
        overflow: visible;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        box-shadow: 0 3px 14px rgba(38, 59, 53, .045);
    }

    .form-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 17px 21px;
        background: #fffefa;
        border-bottom: 1px solid #e5e1d3;
        border-radius: 10px 10px 0 0;
    }

    .form-card-heading {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .form-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        color: #34775a;
        background: #eaf3e9;
        border: 1px solid #dce9d9;
        border-radius: 9px;
        font-size: 16px;
    }

    .form-card-heading h5 {
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
    }

    .form-card-heading p {
        margin: 4px 0 0;
        color: #7a857d;
        font-size: 10px;
        line-height: 1.5;
    }

    .required-note {
        flex-shrink: 0;
        color: #7a857d;
        font-size: 10px;
    }

    .required-note span,
    .transport-form-group label .required {
        color: #bd5148;
    }

    /* FORM BODY */

    .transport-form-body {
        padding: 23px 21px;
    }

    .form-section {
        margin-bottom: 23px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .section-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 31px;
        width: 31px;
        height: 31px;
        color: #34775a;
        background: #eaf3e9;
        border: 1px solid #dce9d9;
        border-radius: 8px;
        font-size: 13px;
    }

    .section-heading h6 {
        margin: 0;
        color: #263b35;
        font-size: 12px;
        font-weight: 750;
    }

    .section-heading span {
        display: block;
        margin-top: 3px;
        color: #879087;
        font-size: 10px;
        line-height: 1.5;
    }

    /* FORM FIELDS */

    .transport-form-group {
        margin-bottom: 17px;
    }

    .transport-form-group label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .transport-input-wrapper {
        position: relative;
        width: 100%;
    }

    .transport-input-icon {
        position: absolute;
        top: 50%;
        left: 12px;
        z-index: 2;
        color: #7c8b7e;
        font-size: 13px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .transport-form-control,
    .transport-form-select,
    .transport-form-textarea {
        display: block;
        width: 100%;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 7px;
        outline: none;
        font-family: inherit;
        font-size: 11px;
        font-weight: 500;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .transport-form-control,
    .transport-form-select {
        height: 39px;
        padding: 0 11px;
    }

    .transport-form-control.with-icon,
    .transport-form-select.with-icon {
        padding-left: 35px;
    }

    .transport-form-textarea {
        min-height: 95px;
        padding: 10px 11px;
        resize: vertical;
        line-height: 1.5;
    }

    .transport-form-control::placeholder,
    .transport-form-textarea::placeholder,
    .student-search-input::placeholder {
        color: #a0a397;
        font-weight: 400;
    }

    .transport-form-control:hover,
    .transport-form-select:hover,
    .transport-form-textarea:hover,
    .student-search-input:hover {
        border-color: #c5d1c2;
    }

    .transport-form-control:focus,
    .transport-form-select:focus,
    .transport-form-textarea:focus,
    .student-search-input:focus {
        background: #fffefa;
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .transport-form-control[readonly] {
        cursor: default;
        color: #69766d;
        background: #f5f3e9;
    }

    .transport-form-select {
        cursor: pointer;
    }

    /* CLASS SELECT */

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
        display: none;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
        color: #34775a;
        font-size: 10px;
    }

    .class-loading.show {
        display: flex;
    }

    .class-loading i,
    .student-loading i {
        display: inline-block;
        animation: transportSpin .8s linear infinite;
    }

    @keyframes transportSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* SEARCHABLE STUDENT SELECT */

    .student-search-wrapper {
        position: relative;
    }

    .student-search-input {
        display: block;
        width: 100%;
        height: 39px;
        padding: 0 37px 0 35px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 7px;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
        font-size: 11px;
        font-weight: 500;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .student-search-input:disabled {
        cursor: not-allowed;
        color: #91968c;
        background: #f5f3e9;
    }

    .student-search-icon {
        position: absolute;
        top: 50%;
        left: 12px;
        z-index: 2;
        color: #7c8b7e;
        font-size: 13px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .student-search-arrow {
        position: absolute;
        top: 50%;
        right: 12px;
        color: #7c8b7e;
        font-size: 11px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .student-dropdown {
        position: absolute;
        top: calc(100% + 5px);
        right: 0;
        left: 0;
        z-index: 100;
        display: none;
        max-height: 255px;
        overflow-y: auto;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 8px;
        box-shadow: 0 9px 24px rgba(38, 59, 53, .12);
    }

    .student-dropdown.show {
        display: block;
    }

    .student-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 11px;
        cursor: pointer;
        border-bottom: 1px solid #efecdf;
        transition: background .15s ease;
    }

    .student-option:last-child {
        border-bottom: none;
    }

    .student-option:hover {
        background: #eaf3e9;
    }

    .student-option-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 32px;
        width: 32px;
        height: 32px;
        color: #34775a;
        background: #eaf3e9;
        border: 1px solid #dce9d9;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 750;
    }

    .student-option-info {
        flex: 1;
        min-width: 0;
    }

    .student-option-name {
        overflow: hidden;
        color: #263b35;
        font-size: 11px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .student-option-meta {
        margin-top: 3px;
        color: #81887e;
        font-size: 9px;
        line-height: 1.5;
    }

    .student-no-result,
    .student-loading {
        padding: 16px 12px;
        color: #81887e;
        font-size: 10px;
        text-align: center;
    }

    .student-loading {
        color: #34775a;
    }

    /* SELECTED STUDENT DETAILS */

    .selected-student-card {
        display: none;
        margin-top: 13px;
        padding: 15px;
        background: #eaf3e9;
        border: 1px solid #d4e4d1;
        border-radius: 9px;
    }

    .selected-student-card.show {
        display: block;
    }

    .selected-student-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
        padding-bottom: 11px;
        border-bottom: 1px solid #d5e4d1;
    }

    .selected-student-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 37px;
        width: 37px;
        height: 37px;
        color: #34775a;
        background: #fffefa;
        border: 1px solid #d5e4d1;
        border-radius: 9px;
        font-size: 15px;
        font-weight: 750;
    }

    .selected-student-header h6 {
        margin: 0;
        color: #263b35;
        font-size: 11px;
        font-weight: 750;
    }

    .selected-student-header p {
        margin: 3px 0 0;
        color: #718075;
        font-size: 9px;
    }

    .student-detail-box {
        min-height: 58px;
        padding: 9px 10px;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 7px;
    }

    .student-detail-label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 5px;
        color: #82897e;
        font-size: 9px;
        font-weight: 600;
    }

    .student-detail-label i {
        color: #34775a;
        font-size: 10px;
    }

    .student-detail-value {
        color: #263b35;
        font-size: 10px;
        font-weight: 650;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    /* STATUS OPTIONS */

    .status-options,
    .payment-options {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .status-option,
    .payment-option {
        position: relative;
        flex: 1;
        min-width: 105px;
    }

    .status-option input,
    .payment-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .status-option label,
    .payment-option label {
        display: flex;
        align-items: center;
        gap: 7px;
        width: 100%;
        min-height: 39px;
        padding: 8px 10px;
        margin: 0;
        color: #58675d;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 7px;
        cursor: pointer;
        font-size: 10px;
        font-weight: 650;
        transition: border-color .18s ease, background .18s ease, box-shadow .18s ease;
    }

    .status-option label i,
    .payment-option label i {
        font-size: 12px;
    }

    .status-option.active-option label,
    .payment-option.paid-option label {
        color: #34775a;
    }

    .status-option.active-option input:checked + label,
    .payment-option.paid-option input:checked + label {
        color: #245a43;
        background: #eaf3e9;
        border-color: #83aa8a;
        box-shadow: 0 0 0 2px rgba(52, 119, 90, .07);
    }

    .status-option.inactive-option label {
        color: #bd5148;
    }

    .status-option.inactive-option input:checked + label {
        color: #a23d36;
        background: #fff1ed;
        border-color: #dba39a;
        box-shadow: 0 0 0 2px rgba(189, 81, 72, .06);
    }

    .payment-option.pending-option label {
        color: #a87543;
    }

    .payment-option.pending-option input:checked + label {
        color: #87582d;
        background: #f8eee0;
        border-color: #d9b88f;
        box-shadow: 0 0 0 2px rgba(168, 117, 67, .06);
    }

    .payment-option.partial-option label {
        color: #527f79;
    }

    .payment-option.partial-option input:checked + label {
        color: #386862;
        background: #e9f2ef;
        border-color: #a5c5bd;
        box-shadow: 0 0 0 2px rgba(82, 127, 121, .06);
    }

    .status-option input:focus-visible + label,
    .payment-option input:focus-visible + label {
        outline: 2px solid #34775a;
        outline-offset: 2px;
    }

    /* HINTS AND VALIDATION */

    .field-hint {
        margin-top: 5px;
        color: #858a7f;
        font-size: 9px;
        line-height: 1.5;
    }

    .transport-form-control.is-invalid,
    .transport-form-select.is-invalid,
    .student-search-input.is-invalid {
        background: #fff8f5;
        border-color: #bd5148;
    }

    .transport-form-control.is-invalid:focus,
    .transport-form-select.is-invalid:focus,
    .student-search-input.is-invalid:focus {
        border-color: #bd5148;
        box-shadow: 0 0 0 3px rgba(189, 81, 72, .09);
    }

    .field-error {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 5px;
        color: #bd5148;
        font-size: 10px;
        line-height: 1.5;
    }

    .field-error i {
        margin-top: 1px;
    }

    /* SECTION DIVIDER */

    .form-divider {
        height: 1px;
        margin: 21px 0;
        background: #e9e5d9;
    }

    /* FOOTER ACTIONS */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        padding: 15px 21px;
        background: #faf8f0;
        border-top: 1px solid #e5e1d3;
        border-radius: 0 0 10px 10px;
    }

    .btn-transport-cancel,
    .btn-transport-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 37px;
        padding: 0 15px;
        border-radius: 7px;
        cursor: pointer;
        font-family: inherit;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: background .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .btn-transport-cancel {
        color: #526057;
        background: #fffefa;
        border: 1px solid #dcd8ca;
    }

    .btn-transport-cancel:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    .btn-transport-save {
        color: #fffefa;
        background: #34775a;
        border: 1px solid #34775a;
        box-shadow: 0 3px 7px rgba(52, 119, 90, .13);
    }

    .btn-transport-save:hover {
        color: #fffefa;
        background: #245a43;
        border-color: #245a43;
        box-shadow: 0 4px 10px rgba(36, 90, 67, .17);
        transform: translateY(-1px);
    }

    .btn-transport-save:active {
        transform: translateY(0);
    }

    /* RESPONSIVE */

    @media (max-width: 991.98px) {
        .transport-create-header {
            padding: 20px;
        }

        .transport-form-body {
            padding: 20px 18px;
        }

        .form-card-header {
            padding: 15px 18px;
        }

        .form-actions {
            padding: 14px 18px;
        }
    }

    @media (max-width: 767.98px) {
        .transport-create-page .container-fluid {
            padding-top: 14px !important;
        }

        .transport-create-header {
            padding: 18px 16px;
            margin-bottom: 15px;
        }

        .create-title {
            font-size: 19px;
        }

        .create-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .transport-form-body {
            padding: 18px 15px;
        }

        .form-card-header {
            align-items: flex-start;
            padding: 14px 15px;
        }

        .required-note {
            padding-top: 4px;
        }

        .status-option,
        .payment-option {
            min-width: calc(50% - 5px);
        }

        .form-actions {
            padding: 13px 15px;
        }
    }

    @media (max-width: 480px) {
        .create-title-row {
            align-items: flex-start;
            gap: 10px;
        }

        .create-title {
            font-size: 17px;
        }

        .create-subtitle {
            font-size: 10px;
        }

        .create-breadcrumb {
            font-size: 9px;
        }

        .form-card-header {
            flex-wrap: wrap;
            gap: 9px;
        }

        .required-note {
            padding-left: 47px;
        }

        .section-heading {
            align-items: flex-start;
        }

        .section-heading-icon {
            flex-basis: 29px;
            width: 29px;
            height: 29px;
        }

        .status-option,
        .payment-option {
            min-width: 100%;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn-transport-cancel,
        .btn-transport-save {
            width: 100%;
        }

        .selected-student-card {
            padding: 11px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .transport-create-page *,
        .transport-create-page *::before,
        .transport-create-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="transport-create-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}

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
                        <h1 class="create-title">Add Transport Record</h1>
                        <p class="create-subtitle">
                            Assign transport service and fee details to a student.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- FORM CARD --}}

        <div class="transport-form-card">

            <div class="form-card-header">
                <div class="form-card-heading">

                    <div class="form-heading-icon">
                        <i class="bi bi-bus-front"></i>
                    </div>

                    <div>
                        <h5>Add Transport Record</h5>
                        <p>Enter the student's transport assignment details.</p>
                    </div>

                </div>

                <div class="required-note">
                    <span>*</span> Required fields
                </div>
            </div>

            <form
                action="{{ route('admin.transport.records.store') }}"
                method="POST"
                id="transportRecordForm"
            >
                @csrf

                <div class="transport-form-body">

                    {{-- 1. STUDENT INFORMATION --}}

                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-person-vcard"></i>
                            </div>

                            <div>
                                <h6>Student Information</h6>
                                <span>Select a class first, then choose a student.</span>
                            </div>
                        </div>

                        <div class="row">

                            {{-- CLASS --}}

                            <div class="col-lg-5">
                                <div class="transport-form-group">

                                    <label for="class_id">
                                        Select Class <span class="required">*</span>
                                    </label>

                                    <div class="transport-input-wrapper class-select-wrapper">
                                        <i class="bi bi-mortarboard transport-input-icon"></i>

                                        <select
                                            name="class_filter"
                                            id="class_id"
                                            class="transport-form-select with-icon @error('class_filter') is-invalid @enderror"
                                        >
                                            <option value="">Select class</option>

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
                                                <option value="" disabled>No active classes found</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    @error('class_filter')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="class-loading" id="classLoading">
                                        <i class="bi bi-arrow-repeat"></i>
                                        Loading students...
                                    </div>

                                    <div class="field-hint">
                                        Select the class and section to load its students.
                                    </div>

                                </div>
                            </div>

                            {{-- STUDENT SEARCH --}}

                            <div class="col-lg-7">
                                <div class="transport-form-group">

                                    <label for="student_search">
                                        Select Student <span class="required">*</span>
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

                                        <div class="student-dropdown" id="studentDropdown">
                                            <div class="student-no-result" id="studentInitialMessage">
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

                        <div class="selected-student-card" id="selectedStudentCard">

                            <div class="selected-student-header">

                                <div class="selected-student-avatar" id="selectedStudentAvatar">
                                    S
                                </div>

                                <div>
                                    <h6 id="selectedStudentTitle">Student Details</h6>
                                    <p>Existing student information</p>
                                </div>

                            </div>

                            <div class="row g-3">

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-person"></i> Student Name
                                        </div>
                                        <div class="student-detail-value" id="studentName">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-card-text"></i> Roll No / Student ID
                                        </div>
                                        <div class="student-detail-value" id="studentRollId">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-mortarboard"></i> Class
                                        </div>
                                        <div class="student-detail-value" id="studentClass">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-grid"></i> Division
                                        </div>
                                        <div class="student-detail-value" id="studentDivision">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-person-heart"></i> Parent Name
                                        </div>
                                        <div class="student-detail-value" id="studentParentName">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-telephone"></i> Parent Phone No.
                                        </div>
                                        <div class="student-detail-value" id="studentParentPhone">—</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-geo-alt"></i> Address
                                        </div>
                                        <div class="student-detail-value" id="studentAddress">—</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="form-divider"></div>

                    {{-- 2. TRANSPORT INFORMATION --}}

                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-bus-front"></i>
                            </div>

                            <div>
                                <h6>Transport Information</h6>
                                <span>Enter the student's assigned transport details.</span>
                            </div>
                        </div>

                        <div class="row">

                            {{-- ROUTE --}}

                            <div class="col-lg-6">
                                <div class="transport-form-group">
                                    <label for="route">Route</label>

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
                                    <label for="vehicle">Vehicle</label>

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
                                    <label for="pickup_point">Pickup Point</label>

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
                                    <label for="drop_point">Drop Point</label>

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
                                        Transport Status <span class="required">*</span>
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
                                    <label for="start_date">Start Date</label>

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
                                    <label for="end_date">End Date</label>

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

                    {{-- 3. TRAVEL DETAILS --}}

                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>

                            <div>
                                <h6>Travel Details</h6>
                                <span>Set the transport type and daily travel timings.</span>
                            </div>
                        </div>

                        <div class="row">

                            {{-- TRANSPORT TYPE --}}

                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label for="transport_type">Transport Type</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-bus-front transport-input-icon"></i>

                                        <select
                                            name="transport_type"
                                            id="transport_type"
                                            class="transport-form-select with-icon @error('transport_type') is-invalid @enderror"
                                        >
                                            <option value="">Select transport type</option>

                                            <option value="school_bus" {{ old('transport_type') === 'school_bus' ? 'selected' : '' }}>
                                                School Bus
                                            </option>

                                            <option value="van" {{ old('transport_type') === 'van' ? 'selected' : '' }}>
                                                Van
                                            </option>

                                            <option value="private" {{ old('transport_type') === 'private' ? 'selected' : '' }}>
                                                Private
                                            </option>

                                            <option value="other" {{ old('transport_type') === 'other' ? 'selected' : '' }}>
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
                                    <label for="pickup_time">Pickup Time</label>

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
                                    <label for="drop_time">Drop Time</label>

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

                    {{-- 4. FEE INFORMATION --}}

                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-currency-rupee"></i>
                            </div>

                            <div>
                                <h6>Fee Information</h6>
                                <span>Enter transport fee and payment information.</span>
                            </div>
                        </div>

                        <div class="row">

                            {{-- TRANSPORT FEE --}}

                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label for="transport_fee">Transport Fee</label>

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
                                    <label for="fee_frequency">Fee Frequency</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-calendar3 transport-input-icon"></i>

                                        <select
                                            name="fee_frequency"
                                            id="fee_frequency"
                                            class="transport-form-select with-icon @error('fee_frequency') is-invalid @enderror"
                                        >
                                            <option value="">Select frequency</option>

                                            <option value="monthly" {{ old('fee_frequency') === 'monthly' ? 'selected' : '' }}>
                                                Monthly
                                            </option>

                                            <option value="quarterly" {{ old('fee_frequency') === 'quarterly' ? 'selected' : '' }}>
                                                Quarterly
                                            </option>

                                            <option value="yearly" {{ old('fee_frequency') === 'yearly' ? 'selected' : '' }}>
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
                                        Payment Status <span class="required">*</span>
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

                {{-- FORM ACTIONS --}}

                <div class="form-actions">

                    <a
                        href="{{ route('admin.transport.records.index') }}"
                        class="btn-transport-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Cancel
                    </a>

                    <button type="submit" class="btn-transport-save">
                        <i class="bi bi-check2-circle"></i>
                        Save Transport Record
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

{{-- =============================================================
CLASS + STUDENT SEARCH + AUTO DETAILS SCRIPT
============================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ELEMENTS */

    const classSelect = document.getElementById('class_id');
    const classLoading = document.getElementById('classLoading');
    const searchInput = document.getElementById('student_search');
    const hiddenStudentId = document.getElementById('student_id');
    const dropdown = document.getElementById('studentDropdown');
    const selectedCard = document.getElementById('selectedStudentCard');
    const selectedAvatar = document.getElementById('selectedStudentAvatar');
    const selectedTitle = document.getElementById('selectedStudentTitle');
    const studentName = document.getElementById('studentName');
    const studentRollId = document.getElementById('studentRollId');
    const studentClass = document.getElementById('studentClass');
    const studentDivision = document.getElementById('studentDivision');
    const studentParentName = document.getElementById('studentParentName');
    const studentParentPhone = document.getElementById('studentParentPhone');
    const studentAddress = document.getElementById('studentAddress');

    /* AJAX ENDPOINT */

    const studentsByClassUrl =
        @json(route('admin.transport.records.students-by-class'));

    let students = [];

    /* HELPER */

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

    /* ESCAPE HTML */

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* CLEAR SELECTED STUDENT */

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

    /* SHOW SELECTED STUDENT */

    function selectStudent(student) {
        const id = student.id || '';
        const name = student.full_name || '';
        const studentId = student.student_id || '';
        const rollNumber = student.roll_number || '';
        const studentClassValue = student.class || '';
        const section = student.section || '';
        const parentName = student.father_name || '';
        const parentPhone = student.father_phone || '';
        const address = student.address || '';

        hiddenStudentId.value = id;
        searchInput.value = name;

        studentName.textContent = displayValue(name);

        studentRollId.textContent =
            studentId && rollNumber
                ? rollNumber + ' / ' + studentId
                : (rollNumber || studentId || 'Not available');

        studentClass.textContent = displayValue(studentClassValue);
        studentDivision.textContent = displayValue(section);
        studentParentName.textContent = displayValue(parentName);
        studentParentPhone.textContent = displayValue(parentPhone);
        studentAddress.textContent = displayValue(address);

        selectedAvatar.textContent =
            name ? name.charAt(0).toUpperCase() : 'S';

        selectedTitle.textContent = name || 'Student Details';

        selectedCard.classList.add('show');
        dropdown.classList.remove('show');
        searchInput.classList.remove('is-invalid');
    }

    /* RENDER STUDENTS */

    function renderStudents(list) {
        dropdown.innerHTML = '';

        if (!list.length) {
            const noResult = document.createElement('div');
            noResult.className = 'student-no-result';
            noResult.innerHTML =
                '<i class="bi bi-person-x me-1"></i> No students found in this class.';

            dropdown.appendChild(noResult);
            return;
        }

        list.forEach(function (student) {
            const option = document.createElement('div');
            option.className = 'student-option';

            const firstLetter = student.full_name
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

            option.addEventListener('click', function () {
                selectStudent(student);
            });

            dropdown.appendChild(option);
        });
    }

    /* FILTER STUDENTS */

    function filterStudents() {
        const searchTerm = searchInput.value.trim().toLowerCase();

        const filtered = students.filter(function (student) {
            const name = (student.full_name || '').toLowerCase();
            const studentId = (student.student_id || '').toLowerCase();
            const rollNumber = (student.roll_number || '').toLowerCase();

            return (
                name.includes(searchTerm) ||
                studentId.includes(searchTerm) ||
                rollNumber.includes(searchTerm)
            );
        });

        renderStudents(filtered);
    }

    /* LOAD STUDENTS FOR SELECTED CLASS */

    async function loadStudentsForClass(classId, studentIdToRestore = null) {
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
            searchInput.placeholder = 'Select a class first...';
            classLoading.classList.remove('show');
            return;
        }

        searchInput.disabled = true;
        searchInput.placeholder = 'Loading students...';
        classLoading.classList.add('show');

        dropdown.innerHTML = `
            <div class="student-loading">
                <i class="bi bi-arrow-repeat me-1"></i>
                Loading students...
            </div>
        `;

        try {
            const response = await fetch(
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
                throw new Error('Unable to load students.');
            }

           const responseText = await response.text();
const data = JSON.parse(responseText.replace(/^\uFEFF/, '').trim());


            students = Array.isArray(data.students)
                ? data.students
                : [];

            searchInput.disabled = false;

            searchInput.placeholder = students.length
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

            /* RESTORE OLD STUDENT AFTER VALIDATION ERROR */

            if (studentIdToRestore) {
                const oldStudent = students.find(function (student) {
                    return String(student.id) === String(studentIdToRestore);
                });

                if (oldStudent) {
                    selectStudent(oldStudent);
                }
            }

        } catch (error) {
            console.error('Transport student loading error:', error);

            searchInput.disabled = true;
            searchInput.placeholder = 'Unable to load students';

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

    /* CLASS CHANGE */

    classSelect.addEventListener('change', function () {
        const classId = this.value;

        hiddenStudentId.value = '';

        loadStudentsForClass(classId);
    });

    /* OPEN STUDENT DROPDOWN */

    searchInput.addEventListener('focus', function () {
        if (searchInput.disabled || !students.length) {
            return;
        }

        filterStudents();
        dropdown.classList.add('show');
    });

    searchInput.addEventListener('click', function () {
        if (searchInput.disabled || !students.length) {
            return;
        }

        filterStudents();
        dropdown.classList.add('show');
    });

    /* STUDENT SEARCH */

    searchInput.addEventListener('input', function () {
        if (
            hiddenStudentId.value &&
            searchInput.value !== (
                students.find(function (student) {
                    return String(student.id) === String(hiddenStudentId.value);
                })?.full_name || ''
            )
        ) {
            hiddenStudentId.value = '';
            selectedCard.classList.remove('show');
        }

        if (searchInput.disabled || !students.length) {
            return;
        }

        filterStudents();
        dropdown.classList.add('show');
    });

    /* CLOSE DROPDOWN WHEN CLICKING OUTSIDE */

    document.addEventListener('click', function (event) {
        const wrapper = document.querySelector('.student-search-wrapper');

        if (wrapper && !wrapper.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });

    /* FORM VALIDATION */

    document.getElementById('transportRecordForm').addEventListener(
        'submit',
        function (event) {
            /*
             * Class is required only to select the correct student.
             * It is not submitted as a database field.
             */

            if (!classSelect.value) {
                event.preventDefault();

                classSelect.classList.add('is-invalid');
                classSelect.focus();

                return;
            }

            /* A student must be selected. */

            if (!hiddenStudentId.value) {
                event.preventDefault();

                searchInput.classList.add('is-invalid');
                searchInput.focus();
                dropdown.classList.add('show');

                return;
            }
        }
    );

    /* REMOVE CLASS INVALID STATE */

    classSelect.addEventListener('change', function () {
        if (this.value) {
            this.classList.remove('is-invalid');
        }
    });

    /* RESTORE OLD VALUES AFTER VALIDATION ERROR */

    const oldClassId = @json(old('class_filter'));
    const oldStudentId = @json(old('student_id'));

    if (oldClassId) {
        classSelect.value = oldClassId;
        loadStudentsForClass(oldClassId, oldStudentId);
    }

});
</script>

@endsection