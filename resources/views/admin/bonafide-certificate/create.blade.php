@extends('layouts.app')

@section('title', 'Generate Bonafide Certificate')

@section('content')

<style>
    .bonafide-page {
        padding: 20px;
    }

    .page-header {
        background: #fff;
        border: 1px solid #e8edf3;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .page-title {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        color: #1f2937;
    }

    .page-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5eaf0;
        border-radius: 10px;
        padding: 20px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #edf0f4;
    }

    .student-box {
        background: #f8fbff;
        border: 1px solid #dcecff;
        border-radius: 9px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .student-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .student-field {
        min-width: 0;
    }

    .field-label {
        display: block;
        color: #6b7280;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .field-value {
        color: #1f2937;
        font-size: 14px;
        font-weight: 500;
    }

    .student-name {
        font-size: 17px;
        font-weight: 600;
        color: #1677f0;
        margin-bottom: 14px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-color: #dfe5ec;
        border-radius: 7px;
        font-size: 14px;
        min-height: 40px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1677f0;
        box-shadow: 0 0 0 0.15rem rgba(22, 119, 240, 0.12);
    }

    textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }

    .required {
        color: #dc3545;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #edf0f4;
    }

    .btn-primary {
        background: #1677f0;
        border-color: #1677f0;
    }

    .btn-primary:hover {
        background: #0d5fc7;
        border-color: #0d5fc7;
    }

    @media (max-width: 768px) {
        .bonafide-page {
            padding: 12px;
        }

        .student-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .student-row {
            grid-template-columns: 1fr;
        }

        .actions {
            flex-direction: column;
        }

        .actions .btn {
            width: 100%;
        }
    }
</style>

<div class="bonafide-page">

    {{-- Page Header --}}
    <div class="page-header">

        <h1 class="page-title">
            <i class="bi bi-file-earmark-text me-2"></i>
            Generate Bonafide Certificate
        </h1>

        <p class="page-subtitle">
            Review student details and enter certificate information.
        </p>

    </div>


    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="form-card">

        {{-- Student Information --}}
        <div class="section-title">
            <i class="bi bi-person-fill me-2"></i>
            Student Information
        </div>

        <div class="student-box">

            <div class="student-name">
                {{ trim(
                    ($student->first_name ?? '') . ' ' .
                    ($student->middle_name ?? '') . ' ' .
                    ($student->last_name ?? '')
                ) ?: 'Student' }}
            </div>

            <div class="student-row">

                {{-- Student ID --}}
                <div class="student-field">

                    <span class="field-label">
                        Student ID
                    </span>

                    <div class="field-value">
                        {{ $student->student_id ?? '-' }}
                    </div>

                </div>


                {{-- Class --}}
                <div class="student-field">

                    <span class="field-label">
                        Class
                    </span>

                    <div class="field-value">
                        {{ $student->class ?? '-' }}
                    </div>

                </div>


                {{-- Section --}}
                <div class="student-field">

                    <span class="field-label">
                        Section
                    </span>

                    <div class="field-value">
                        {{ $student->section ?? '-' }}
                    </div>

                </div>


                {{-- Date of Birth --}}
                <div class="student-field">

                    <span class="field-label">
                        Date of Birth
                    </span>

                    <div class="field-value">

                        @if(!empty($student->date_of_birth))
                            {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') }}
                        @else
                            -
                        @endif

                    </div>

                </div>


                {{-- Parent / Guardian --}}
                <div class="student-field">

                    <span class="field-label">
                        Parent / Guardian
                    </span>

                    <div class="field-value">
                        {{ $student->father_name
                            ?? $student->guardian_name
                            ?? '-' }}
                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="student-field">

                    <span class="field-label">
                        Academic Year
                    </span>

                    <div class="field-value">
                        {{ $student->academic_year ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Certificate Details --}}
        <div class="section-title">
            <i class="bi bi-file-earmark-check me-2"></i>
            Certificate Details
        </div>


        <form
            action="{{ route('admin.bonafide.store') }}"
            method="POST"
        >

            @csrf

            {{-- Hidden selected student --}}
            <input
                type="hidden"
                name="student_id"
                value="{{ $student->id }}"
            >


            <div class="row g-3">

                {{-- Reason --}}
                <div class="col-md-12">

                    <label
                        for="reason"
                        class="form-label"
                    >
                        Reason
                        <span class="required">*</span>
                    </label>

                    <textarea
                        name="reason"
                        id="reason"
                        class="form-control @error('reason') is-invalid @enderror"
                        placeholder="Enter reason for issuing the bonafide certificate"
                        maxlength="500"
                        required
                    >{{ old('reason', 'To avail of travel benefits') }}</textarea>

                    @error('reason')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Issue Date --}}
                <div class="col-md-6">

                    <label
                        for="issue_date"
                        class="form-label"
                    >
                        Issue Date
                        <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="issue_date"
                        id="issue_date"
                        class="form-control @error('issue_date') is-invalid @enderror"
                        value="{{ old('issue_date', date('Y-m-d')) }}"
                        required
                    >

                    @error('issue_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Status --}}
                <div class="col-md-6">

                    <label
                        for="status"
                        class="form-label"
                    >
                        Status
                        <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >

                        <option
                            value="active"
                            {{ old('status', 'active') === 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ old('status') === 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- Buttons --}}
            <div class="actions">

                <a
                    href="{{ route('admin.bonafide.students', ['class' => $student->class]) }}"
                    class="btn btn-light border"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Students
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-file-earmark-check me-1"></i>
                    Generate Certificate
                </button>

            </div>

        </form>

    </div>

</div>

@endsection