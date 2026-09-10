@extends('layouts.app')

@section('title', 'Add Subject | Admin')

@section('page-title', 'Add Subject')

@section('content')

<style>
    .subject-form-page {
        width: 100%;
    }

    .subject-form-header {
        margin-bottom: 20px;
    }

    .subject-form-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #26344a;
    }

    .subject-form-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .subject-form-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 8px;
        overflow: hidden;
    }

    .subject-form-card-header {
        padding: 16px 18px;
        border-bottom: 1px solid #e7edf5;
    }

    .subject-form-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #26344a;
    }

    .subject-form {
        padding: 22px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        margin-bottom: 7px;
        color: #26344a;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }

    .form-control-custom,
    .form-select-custom {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #dce3ec;
        border-radius: 6px;
        background: #fff;
        color: #26344a;
        font-size: 13px;
        outline: none;
        transition: .2s ease;
        box-sizing: border-box;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: #1677f0;
        box-shadow: 0 0 0 3px rgba(22, 119, 240, .08);
    }

    .form-help {
        margin-top: 5px;
        color: #8a94a6;
        font-size: 11px;
    }

    .error-message {
        margin-top: 5px;
        color: #dc3545;
        font-size: 11px;
    }

    .status-options {
        display: flex;
        align-items: center;
        gap: 18px;
        min-height: 40px;
    }

    .status-option {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #26344a;
        font-size: 13px;
        cursor: pointer;
    }

    .status-option input {
        accent-color: #1677f0;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 5px;
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 17px;
        background: #1677f0;
        color: #fff;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .btn-primary-custom:hover {
        background: #0d5fd1;
        color: #fff;
    }

    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 17px;
        background: #f4f6f9;
        color: #526071;
        border: 1px solid #e1e6ee;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-secondary-custom:hover {
        background: #e9edf3;
        color: #26344a;
    }

    .alert-error-custom {
        margin-bottom: 18px;
        padding: 12px 15px;
        border-radius: 6px;
        background: #fff0f1;
        border: 1px solid #ffdadd;
        color: #dc3545;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .subject-form {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
        }
    }
</style>

<div class="subject-form-page">

    <div class="subject-form-header">
        <h2>Add Subject</h2>
        <p>Create a new subject and assign it to a class.</p>
    </div>

    @if($errors->any())
        <div class="alert-error-custom">
            Please correct the errors below and try again.
        </div>
    @endif

    <div class="subject-form-card">

        <div class="subject-form-card-header">
            <h3>Subject Information</h3>
        </div>

        <form
            action="{{ route('admin.subjects.store') }}"
            method="POST"
            class="subject-form"
        >

            @csrf

            <div class="form-row">

                <div class="form-group">

                    <label for="subject_name" class="form-label">
                        Subject Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="subject_name"
                        name="subject_name"
                        class="form-control-custom"
                        value="{{ old('subject_name') }}"
                        placeholder="Enter subject name"
                        required
                    >

                    @error('subject_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="subject_code" class="form-label">
                        Subject Code
                    </label>

                    <input
                        type="text"
                        id="subject_code"
                        name="subject_code"
                        class="form-control-custom"
                        value="{{ old('subject_code') }}"
                        placeholder="Enter subject code"
                    >

                    <div class="form-help">
                        Optional. Example: MATH101
                    </div>

                    @error('subject_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label for="class_id" class="form-label">
                        Class <span class="required">*</span>
                    </label>

                    <select
                        id="class_id"
                        name="class_id"
                        class="form-select-custom"
                        required
                    >

                        <option value="">Select Class</option>

                        @foreach($classes as $class)

                            <option
                                value="{{ $class->id }}"
                                {{ old('class_id') == $class->id ? 'selected' : '' }}
                            >
                                {{ $class->class_name }}
                                - Section {{ $class->section }}
                                ({{ $class->academic_year }})
                            </option>

                        @endforeach

                    </select>

                    @if($classes->isEmpty())
                        <div class="form-help">
                            No active classes are available. Please create a class first.
                        </div>
                    @endif

                    @error('class_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Status <span class="required">*</span>
                    </label>

                    <div class="status-options">

                        <label class="status-option">
                            <input
                                type="radio"
                                name="status"
                                value="1"
                                {{ old('status', '1') == '1' ? 'checked' : '' }}
                            >
                            Active
                        </label>

                        <label class="status-option">
                            <input
                                type="radio"
                                name="status"
                                value="0"
                                {{ old('status') === '0' ? 'checked' : '' }}
                            >
                            Inactive
                        </label>

                    </div>

                    @error('status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            <div class="form-actions">

                <button type="submit" class="btn-primary-custom">
                    <span>＋</span>
                    Add Subject
                </button>

                <a
                    href="{{ route('admin.subjects.index') }}"
                    class="btn-secondary-custom"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection