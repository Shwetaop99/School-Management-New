@extends('layouts.app')

@section('title', 'Add Class | Admin')

@section('page-title', 'Add Class')

@section('content')

<style>
    .class-form-page {
        width: 100%;
    }

    .class-page-header {
        margin-bottom: 20px;
    }

    .class-page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #26344a;
    }

    .class-page-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .class-form-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 8px;
        overflow: hidden;
        max-width: 900px;
    }

    .class-form-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e7edf5;
    }

    .class-form-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #26344a;
    }

    .class-form-card-body {
        padding: 22px 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 18px;
    }

    .form-group {
        width: 100%;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #26344a;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }

    .form-control-custom {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #dce3ec;
        border-radius: 6px;
        background: #fff;
        color: #26344a;
        font-size: 13px;
        outline: none;
        transition: .2s;
        box-sizing: border-box;
    }

    .form-control-custom:focus {
        border-color: #1677f0;
        box-shadow: 0 0 0 3px rgba(22, 119, 240, 0.10);
    }

    select.form-control-custom {
        cursor: pointer;
    }

    .form-error {
        margin-top: 6px;
        color: #dc3545;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 5px;
        border-top: 1px solid #edf1f6;
        margin-top: 5px;
    }

    .btn-primary-custom,
    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
        box-sizing: border-box;
    }

    .btn-primary-custom {
        background: #1677f0;
        color: #fff;
        border: 1px solid #1677f0;
    }

    .btn-primary-custom:hover {
        background: #0d5fd1;
        border-color: #0d5fd1;
        color: #fff;
    }

    .btn-secondary-custom {
        background: #fff;
        color: #26344a;
        border: 1px solid #dce3ec;
    }

    .btn-secondary-custom:hover {
        background: #f5f8fc;
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

    .alert-error-custom ul {
        margin: 0;
        padding-left: 18px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 18px;
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

<div class="class-form-page">

    {{-- Page Header --}}
    <div class="class-page-header">
        <h2>Add Class</h2>
        <p>Create a new school class and assign its section and academic year.</p>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert-error-custom">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="class-form-card">

        <div class="class-form-card-header">
            <h3>Class Information</h3>
        </div>

        <div class="class-form-card-body">

            <form action="{{ route('admin.classes.store') }}" method="POST">

                @csrf

                {{-- Row 1 --}}
                <div class="form-row">

                    <div class="form-group">
                        <label for="class_name" class="form-label">
                            Class Name <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="class_name"
                            name="class_name"
                            class="form-control-custom"
                            value="{{ old('class_name') }}"
                            placeholder="Example: Class 1"
                            required
                        >

                        @error('class_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="section" class="form-label">
                            Section <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="section"
                            name="section"
                            class="form-control-custom"
                            value="{{ old('section') }}"
                            placeholder="Example: A"
                            required
                        >

                        @error('section')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Row 2 --}}
                <div class="form-row">

                    <div class="form-group">
                        <label for="academic_year" class="form-label">
                            Academic Year <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="academic_year"
                            name="academic_year"
                            class="form-control-custom"
                            value="{{ old('academic_year') }}"
                            placeholder="Example: 2026-27"
                            required
                        >

                        @error('academic_year')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">
                            Status <span class="required">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-control-custom"
                            required
                        >
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>

                        @error('status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="form-actions">

                    <button type="submit" class="btn-primary-custom">
                        <i class="fas fa-save"></i>
                        Save Class
                    </button>

                    <a href="{{ route('admin.classes.index') }}"
                       class="btn-secondary-custom">
                        <i class="fas fa-arrow-left"></i>
                        Back to Classes
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection