
@extends('layouts.app')

@section('title', 'Edit Subject | Admin')
@section('page-title', 'Edit Subject')

@section('content')

<div class="subject-form-page">
    <div class="subject-page-container">

        {{-- PAGE HEADER --}}
        <div class="subject-page-header">
            <div class="subject-heading-area">
                <div class="subject-heading-icon">
                    <i class="fas fa-book-open"></i>
                </div>

                <div class="subject-heading-text">
                    <div class="subject-breadcrumb">
                        ACADEMIC MANAGEMENT
                        <i class="fas fa-chevron-right"></i>
                        SUBJECTS
                        <i class="fas fa-chevron-right"></i>
                        EDIT SUBJECT
                    </div>

                    <h2>Edit Subject</h2>
                    <p>Update subject information and class assignment.</p>
                </div>
            </div>

            <a href="{{ route('admin.subjects.index') }}"
               class="subject-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Subjects</span>
            </a>
        </div>

        {{-- VALIDATION SUMMARY --}}
        @if($errors->any())
            <div class="subject-alert-error" role="alert">
                <div class="subject-alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>

                <div>
                    <strong>Please check the form details.</strong>
                    <p>Please correct the errors below and try again.</p>
                </div>
            </div>
        @endif

        {{-- SUBJECT FORM CARD --}}
        <div class="subject-form-card">

            <div class="subject-card-header">
                <div class="subject-card-heading">
                    <div class="subject-section-icon">
                        <i class="fas fa-edit"></i>
                    </div>

                    <div>
                        <h3>Subject Information</h3>
                        <p>Modify the details below and save your changes.</p>
                    </div>
                </div>

                <span class="subject-required-note">
                    <span class="subject-required-star">*</span>
                    Required fields
                </span>
            </div>

            <form
                action="{{ route('admin.subjects.update', $subject->id) }}"
                method="POST"
                class="subject-form"
            >
                @csrf
                @method('PUT')

                {{-- SECTION 01 --}}
                <div class="subject-form-section">

                    <div class="subject-section-title">
                        <span class="subject-section-number">01</span>

                        <div>
                            <h4>Basic Details</h4>
                            <p>Enter the subject name and identification code.</p>
                        </div>
                    </div>

                    <div class="subject-form-grid">

                        {{-- SUBJECT NAME --}}
                        <div class="subject-form-group">
                            <label for="subject_name" class="subject-form-label">
                                Subject Name
                                <span class="subject-required-star">*</span>
                            </label>

                            <div class="subject-input-wrap">
                                <i class="fas fa-book subject-input-icon"></i>

                                <input
                                    type="text"
                                    id="subject_name"
                                    name="subject_name"
                                    class="subject-form-control"
                                    value="{{ old('subject_name', $subject->subject_name) }}"
                                    placeholder="Enter subject name"
                                    required
                                >
                            </div>

                            @error('subject_name')
                                <div class="subject-field-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- SUBJECT CODE --}}
                        <div class="subject-form-group">
                            <label for="subject_code" class="subject-form-label">
                                Subject Code
                                <span class="subject-optional-label">OPTIONAL</span>
                            </label>

                            <div class="subject-input-wrap">
                                <i class="fas fa-hashtag subject-input-icon"></i>

                                <input
                                    type="text"
                                    id="subject_code"
                                    name="subject_code"
                                    class="subject-form-control"
                                    value="{{ old('subject_code', $subject->subject_code) }}"
                                    placeholder="Enter subject code"
                                >
                            </div>

                            <div class="subject-form-help">
                                <i class="fas fa-info-circle"></i>
                                Example: MATH101
                            </div>

                            @error('subject_code')
                                <div class="subject-field-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="subject-form-divider"></div>

                {{-- SECTION 02 --}}
                <div class="subject-form-section">

                    <div class="subject-section-title">
                        <span class="subject-section-number">02</span>

                        <div>
                            <h4>Class Assignment</h4>
                            <p>Select the class and section for this subject.</p>
                        </div>
                    </div>

                    <div class="subject-form-grid">

                        {{-- CLASS --}}
                        <div class="subject-form-group">
                            <label for="class_id" class="subject-form-label">
                                Class
                                <span class="subject-required-star">*</span>
                            </label>

                            <div class="subject-input-wrap">
                                <i class="fas fa-school subject-input-icon"></i>

                                <select
                                    id="class_id"
                                    name="class_id"
                                    class="subject-form-control subject-form-select"
                                    required
                                >
                                    <option value="">Select Class</option>

                                    @foreach($classes as $class)
                                        <option
                                            value="{{ $class->id }}"
                                            {{ old('class_id', $subject->class_id) == $class->id ? 'selected' : '' }}
                                        >
                                            {{ $class->class_name }}
                                            - Section {{ $class->section }}
                                            ({{ $class->academic_year }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if($classes->isEmpty())
                                <div class="subject-form-help">
                                    <i class="fas fa-info-circle"></i>
                                    No active classes are available.
                                </div>
                            @endif

                            @error('class_id')
                                <div class="subject-field-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="subject-form-group">
                            <label class="subject-form-label">
                                Subject Status
                                <span class="subject-required-star">*</span>
                            </label>

                            <div class="subject-status-panel">

                                <label class="subject-status-option subject-status-active">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="1"
                                        {{ old('status', $subject->status) == '1' ? 'checked' : '' }}
                                    >

                                    <span class="subject-status-radio"></span>

                                    <span class="subject-status-content">
                                        <span class="subject-status-title">Active</span>
                                        <span class="subject-status-description">
                                            Subject is enabled
                                        </span>
                                    </span>

                                    <i class="fas fa-check-circle subject-status-check"></i>
                                </label>

                                <label class="subject-status-option subject-status-inactive">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="0"
                                        {{ old('status', $subject->status) == '0' ? 'checked' : '' }}
                                    >

                                    <span class="subject-status-radio"></span>

                                    <span class="subject-status-content">
                                        <span class="subject-status-title">Inactive</span>
                                        <span class="subject-status-description">
                                            Subject is disabled
                                        </span>
                                    </span>

                                    <i class="fas fa-check-circle subject-status-check"></i>
                                </label>

                            </div>

                            @error('status')
                                <div class="subject-field-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- FORM ACTIONS --}}
                <div class="subject-form-actions">
                    <div class="subject-action-note">
                        <i class="fas fa-shield-alt"></i>
                        Review your changes before updating.
                    </div>

                    <div class="subject-action-buttons">
                        <a
                            href="{{ route('admin.subjects.index') }}"
                            class="subject-btn subject-btn-secondary"
                        >
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>

                        <button type="submit" class="subject-btn subject-btn-primary">
                            <i class="fas fa-check"></i>
                            Update Subject
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- FOOTER NOTE --}}
        <div class="subject-page-footer">
            <i class="fas fa-book-reader"></i>
            <span>School Management System</span>
            <span class="subject-footer-dot">•</span>
            <span>Subject Management</span>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    /* =========================================
       SCHOOL DIARY DESIGN SYSTEM
       EDIT SUBJECT
    ========================================= */

    .subject-form-page {
        --sd-bg: #f5f3e9;
        --sd-paper: #fffefa;
        --sd-ink: #263b35;
        --sd-green: #34775a;
        --sd-green-dark: #245a43;
        --sd-green-light: #eaf3e9;
        --sd-wood: #a87543;
        --sd-cyan: #527f79;
        --sd-red: #bd5148;
        --sd-border: #e5e1d3;

        width: 100%;
        min-height: 100%;
        padding: 5px 0 20px;
        color: var(--sd-ink);
        font-size: 12px;
        box-sizing: border-box;
    }

    .subject-form-page *,
    .subject-form-page *::before,
    .subject-form-page *::after {
        box-sizing: border-box;
    }

    .subject-page-container {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
    }

    /* PAGE HEADER */

    .subject-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        padding: 15px 17px;
        background: var(--sd-paper);
        border: 1px solid var(--sd-border);
        border-left: 4px solid var(--sd-green);
        border-radius: 9px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, 0.035);
    }

    .subject-heading-area {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .subject-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border: 1px solid #d6e6d8;
        border-radius: 9px;
        background: var(--sd-green-light);
        color: var(--sd-green);
        font-size: 19px;
    }

    .subject-heading-text {
        min-width: 0;
    }

    .subject-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 5px;
        color: #8b927f;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.75px;
    }

    .subject-breadcrumb i {
        color: #b8b7a7;
        font-size: 8px;
    }

    .subject-heading-text h2 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 22px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -0.45px;
    }

    .subject-heading-text p {
        margin: 4px 0 0;
        color: #7e887d;
        font-size: 11px;
        line-height: 1.5;
    }

    .subject-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        flex-shrink: 0;
        padding: 9px 12px;
        border: 1px solid var(--sd-border);
        border-radius: 7px;
        background: var(--sd-paper);
        color: var(--sd-ink);
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s ease, border-color .2s ease, color .2s ease;
    }

    .subject-back-btn:hover {
        background: var(--sd-green-light);
        border-color: #c8ddcb;
        color: var(--sd-green-dark);
        text-decoration: none;
    }

    /* VALIDATION ALERT */

    .subject-alert-error {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 16px;
        padding: 12px 14px;
        border: 1px solid #edc9c4;
        border-radius: 8px;
        background: #fff6f3;
        color: var(--sd-red);
    }

    .subject-alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        border-radius: 7px;
        background: #fbe5e1;
        font-size: 14px;
    }

    .subject-alert-error strong {
        display: block;
        margin: 1px 0 3px;
        font-size: 12px;
        font-weight: 800;
    }

    .subject-alert-error p {
        margin: 0;
        color: #96665f;
        font-size: 11px;
        line-height: 1.5;
    }

    /* MAIN CARD */

    .subject-form-card {
        overflow: hidden;
        border: 1px solid var(--sd-border);
        border-radius: 10px;
        background: var(--sd-paper);
        box-shadow: 0 4px 18px rgba(38, 59, 53, 0.045);
    }

    .subject-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--sd-border);
        background: #fffdf7;
    }

    .subject-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .subject-section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 35px;
        width: 35px;
        height: 35px;
        border: 1px solid #d7e5d7;
        border-radius: 8px;
        background: var(--sd-green-light);
        color: var(--sd-green);
        font-size: 14px;
    }

    .subject-card-heading h3 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 14px;
        font-weight: 800;
    }

    .subject-card-heading p {
        margin: 3px 0 0;
        color: #858b7d;
        font-size: 10px;
        line-height: 1.5;
    }

    .subject-required-note {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
        color: #898c7d;
        font-size: 10px;
    }

    .subject-required-star {
        color: var(--sd-red);
        font-weight: 800;
    }

    /* FORM */

    .subject-form {
        padding: 22px;
    }

    .subject-form-section {
        width: 100%;
    }

    .subject-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 19px;
    }

    .subject-section-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border: 1px solid #d7e5d7;
        border-radius: 8px;
        background: var(--sd-green-light);
        color: var(--sd-green-dark);
        font-size: 11px;
        font-weight: 800;
    }

    .subject-section-title h4 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 13px;
        font-weight: 800;
    }

    .subject-section-title p {
        margin: 3px 0 0;
        color: #898e80;
        font-size: 10px;
        line-height: 1.5;
    }

    .subject-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 21px 24px;
    }

    .subject-form-group {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .subject-form-label {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        margin: 0 0 8px;
        color: var(--sd-ink);
        font-size: 11px;
        font-weight: 800;
    }

    .subject-optional-label {
        margin-left: 4px;
        padding: 3px 5px;
        border-radius: 4px;
        background: #f1eee3;
        color: #8a795d;
        font-size: 8px;
        font-weight: 800;
        letter-spacing: .35px;
    }

    .subject-input-wrap {
        position: relative;
        width: 100%;
    }

    .subject-input-icon {
        position: absolute;
        top: 50%;
        left: 12px;
        z-index: 1;
        color: #8c9a87;
        font-size: 12px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .subject-form-control {
        display: block;
        width: 100%;
        min-width: 0;
        height: 41px;
        margin: 0;
        padding: 10px 12px 10px 35px;
        border: 1px solid #dedecf;
        border-radius: 7px;
        outline: none;
        background: #fffefa;
        color: var(--sd-ink);
        font-family: inherit;
        font-size: 11px;
        line-height: 1.4;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .subject-form-control::placeholder {
        color: #aaa998;
        opacity: 1;
    }

    .subject-form-control:hover {
        border-color: #c9d5c6;
    }

    .subject-form-control:focus {
        border-color: var(--sd-green);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .subject-form-select {
        padding-right: 32px;
        cursor: pointer;
        appearance: auto;
    }

    .subject-form-help {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 7px;
        color: #8b8e7f;
        font-size: 10px;
        line-height: 1.5;
    }

    .subject-form-help i {
        margin-top: 2px;
        color: var(--sd-cyan);
        font-size: 10px;
    }

    .subject-field-error {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 7px;
        color: var(--sd-red);
        font-size: 10px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .subject-field-error i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    .subject-form-divider {
        height: 1px;
        margin: 25px 0;
        background: var(--sd-border);
    }

    /* STATUS OPTIONS */

    .subject-status-panel {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .subject-status-option {
        position: relative;
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
        min-height: 63px;
        padding: 10px 11px;
        overflow: hidden;
        border: 1px solid var(--sd-border);
        border-radius: 8px;
        background: #fffefa;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .subject-status-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .subject-status-radio {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        border: 1.5px solid #c7c9b9;
        border-radius: 50%;
        background: #fffefa;
    }

    .subject-status-content {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .subject-status-title {
        color: var(--sd-ink);
        font-size: 11px;
        font-weight: 800;
    }

    .subject-status-description {
        color: #8b8d80;
        font-size: 9px;
        line-height: 1.4;
    }

    .subject-status-check {
        display: none;
        flex-shrink: 0;
        color: var(--sd-green);
        font-size: 13px;
    }

    .subject-status-active:has(input:checked) {
        border-color: #a9cdb0;
        background: #f0f7ed;
        box-shadow: 0 0 0 2px rgba(52, 119, 90, .05);
    }

    .subject-status-active input:checked + .subject-status-radio {
        border-color: var(--sd-green);
        background: var(--sd-green);
        box-shadow: inset 0 0 0 4px #fffefa;
    }

    .subject-status-active:has(input:checked) .subject-status-check {
        display: block;
    }

    .subject-status-inactive:has(input:checked) {
        border-color: #e3c5bd;
        background: #fff5f1;
        box-shadow: 0 0 0 2px rgba(189, 81, 72, .04);
    }

    .subject-status-inactive input:checked + .subject-status-radio {
        border-color: var(--sd-red);
        background: var(--sd-red);
        box-shadow: inset 0 0 0 4px #fffefa;
    }

    .subject-status-inactive:has(input:checked) .subject-status-check {
        display: block;
        color: var(--sd-red);
    }

    .subject-status-option:focus-within {
        outline: 2px solid rgba(52, 119, 90, .25);
        outline-offset: 2px;
    }

    /* ACTION BAR */

    .subject-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 28px;
        padding-top: 18px;
        border-top: 1px solid var(--sd-border);
    }

    .subject-action-note {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #898d7e;
        font-size: 10px;
        line-height: 1.5;
    }

    .subject-action-note i {
        color: var(--sd-wood);
        font-size: 12px;
    }

    .subject-action-buttons {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
    }

    .subject-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 9px 15px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.3;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
    }

    .subject-btn-primary {
        border-color: var(--sd-green);
        background: var(--sd-green);
        color: #fffefa;
    }

    .subject-btn-primary:hover {
        border-color: var(--sd-green-dark);
        background: var(--sd-green-dark);
        color: #fff;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .subject-btn-secondary {
        border-color: var(--sd-border);
        background: #fffefa;
        color: #687367;
    }

    .subject-btn-secondary:hover {
        border-color: #d2d6c5;
        background: #f1eee3;
        color: var(--sd-ink);
        text-decoration: none;
    }

    .subject-btn:focus-visible,
    .subject-back-btn:focus-visible {
        outline: 2px solid var(--sd-green);
        outline-offset: 3px;
    }

    /* FOOTER */

    .subject-page-footer {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
        padding: 15px 8px 0;
        color: #969787;
        font-size: 10px;
    }

    .subject-page-footer i {
        color: var(--sd-green);
    }

    .subject-footer-dot {
        color: var(--sd-wood);
    }

    /* RESPONSIVE */

    @media (max-width: 991.98px) {
        .subject-form {
            padding: 19px;
        }

        .subject-form-grid {
            gap: 19px;
        }

        .subject-action-note {
            max-width: 220px;
        }
    }

    @media (max-width: 767.98px) {
        .subject-page-header {
            align-items: flex-start;
            padding: 13px;
        }

        .subject-heading-icon {
            flex-basis: 40px;
            width: 40px;
            height: 40px;
            font-size: 17px;
        }

        .subject-heading-text h2 {
            font-size: 19px;
        }

        .subject-back-btn {
            padding: 9px 10px;
        }

        .subject-back-btn span {
            display: none;
        }

        .subject-form-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 17px;
        }

        .subject-card-header {
            padding: 13px 15px;
        }

        .subject-form {
            padding: 17px;
        }

        .subject-form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .subject-action-note {
            max-width: none;
        }

        .subject-action-buttons {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            width: 100%;
        }

        .subject-btn {
            width: 100%;
            padding-right: 9px;
            padding-left: 9px;
        }
    }

    @media (max-width: 480px) {
        .subject-form-page {
            padding-top: 2px;
        }

        .subject-page-header {
            gap: 9px;
            margin-bottom: 14px;
            padding: 11px;
        }

        .subject-heading-area {
            gap: 9px;
        }

        .subject-heading-icon {
            flex-basis: 35px;
            width: 35px;
            height: 35px;
            font-size: 15px;
        }

        .subject-breadcrumb {
            gap: 4px;
            font-size: 8px;
            letter-spacing: .35px;
        }

        .subject-heading-text h2 {
            font-size: 17px;
        }

        .subject-heading-text p {
            font-size: 10px;
        }

        .subject-back-btn {
            padding: 8px;
        }

        .subject-card-header {
            align-items: flex-start;
            gap: 7px;
            padding: 12px;
        }

        .subject-card-heading {
            gap: 8px;
        }

        .subject-section-icon {
            flex-basis: 31px;
            width: 31px;
            height: 31px;
            font-size: 12px;
        }

        .subject-card-heading h3 {
            font-size: 12px;
        }

        .subject-card-heading p {
            font-size: 9px;
        }

        .subject-required-note {
            font-size: 9px;
        }

        .subject-form {
            padding: 14px 12px;
        }

        .subject-section-title {
            gap: 8px;
            margin-bottom: 15px;
        }

        .subject-section-number {
            flex-basis: 30px;
            width: 30px;
            height: 30px;
        }

        .subject-section-title h4 {
            font-size: 12px;
        }

        .subject-section-title p {
            font-size: 9px;
        }

        .subject-form-divider {
            margin: 21px 0;
        }

        .subject-status-panel {
            grid-template-columns: minmax(0, 1fr);
        }

        .subject-form-actions {
            margin-top: 22px;
            padding-top: 15px;
        }

        .subject-action-buttons {
            gap: 7px;
        }

        .subject-btn {
            gap: 5px;
            font-size: 10px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .subject-form-page *,
        .subject-form-page *::before,
        .subject-form-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>
@endpush
