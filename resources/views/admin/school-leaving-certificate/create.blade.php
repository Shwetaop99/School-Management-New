@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>
                Create School Leaving Certificate
            </h3>

            <p class="text-muted mb-0">
                Create a new school leaving certificate for a student.
            </p>
        </div>

        <a href="{{ route('admin.school-leaving-certificate.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())

        <div class="alert alert-danger shadow-sm">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.school-leaving-certificate.store') }}">

        @csrf


        {{-- =====================================================
             STUDENT SELECTION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-person-vcard text-primary me-2"></i>

                    Student Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Student --}}
                    <div class="col-md-8">

                        <label class="form-label fw-semibold">
                            Select Student
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            class="form-select @error('student_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Select Student --
                            </option>

                            @foreach ($students as $student)

                                @php

                                    $studentName = trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    );

                                @endphp

                                <option
                                    value="{{ $student->id }}"
                                    data-student-id="{{ $student->student_id }}"
                                    data-name="{{ $studentName }}"
                                    data-class="{{ $student->class ?? '' }}"
                                    data-section="{{ $student->section ?? '' }}"
                                    data-roll="{{ $student->roll_number ?? '' }}"
                                    data-dob="{{ $student->date_of_birth?->format('d-m-Y') }}"
                                    data-gender="{{ $student->gender ?? '' }}"
                                    data-aadhar="{{ $student->aadhar_card_no ?? '' }}"
                                    {{ old('student_id') == $student->id ? 'selected' : '' }}
                                >
                                    {{ $student->student_id }}
                                    -
                                    {{ $studentName }}
                                </option>

                            @endforeach

                        </select>

                        @error('student_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Student ID --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Student ID
                        </label>

                        <input
                            type="text"
                            id="display_student_id"
                            class="form-control"
                            readonly
                            placeholder="Auto-filled"
                        >

                    </div>


                    {{-- Student Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Student Name
                        </label>

                        <input
                            type="text"
                            id="display_student_name"
                            class="form-control"
                            readonly
                            placeholder="Auto-filled"
                        >

                    </div>


                    {{-- Class --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <input
                            type="text"
                            id="display_class"
                            class="form-control"
                            readonly
                            placeholder="Auto-filled"
                        >

                    </div>


                    {{-- Division --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Division
                        </label>

                        <input
                            type="text"
                            id="display_section"
                            class="form-control"
                            readonly
                            placeholder="Auto-filled"
                        >

                    </div>


                    {{-- Roll Number --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Roll Number
                        </label>

                        <input
                            type="text"
                            id="display_roll"
                            class="form-control"
                            readonly
                            placeholder="Auto-filled"
                        >

                    </div>


                    {{-- DOB --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Date of Birth
                        </label>

                        <input
                            type="text"
                            id="display_dob"
                            class="form-control"
                            readonly
                            placeholder="Auto-filled"
                        >

                    </div>


                    {{-- Gender --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Gender
                        </label>

                        <input
                            type="text"
                            id="display_gender"
                            class="form-control"
                            readonly
                            placeholder="Auto-filled"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CERTIFICATE INFORMATION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-file-earmark-medical text-success me-2"></i>

                    Certificate Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Certificate Number --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Certificate Number
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="certificate_no"
                            value="{{ old('certificate_no') }}"
                            class="form-control @error('certificate_no') is-invalid @enderror"
                            placeholder="Enter certificate number"
                            required
                        >

                        @error('certificate_no')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Issue Date --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Issue Date
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            value="{{ old('issue_date', now()->format('Y-m-d')) }}"
                            class="form-control @error('issue_date') is-invalid @enderror"
                        >

                        @error('issue_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Leaving Date --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Leaving Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="leaving_date"
                            value="{{ old('leaving_date') }}"
                            class="form-control @error('leaving_date') is-invalid @enderror"
                            required
                        >

                        <small class="text-muted">
                            Enter the actual leaving date manually.
                        </small>

                        @error('leaving_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Leaving Reason --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Leaving Reason
                        </label>

                        <input
                            type="text"
                            name="leaving_reason"
                            value="{{ old('leaving_reason') }}"
                            class="form-control @error('leaving_reason') is-invalid @enderror"
                            placeholder="e.g. Transfer to another school"
                        >

                        @error('leaving_reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                            class="form-control @error('academic_year') is-invalid @enderror"
                            placeholder="2026-2027"
                        >

                        @error('academic_year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="draft"
                                {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="issued"
                                {{ old('status') === 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>

                            <option value="cancelled"
                                {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACADEMIC / CONDUCT INFORMATION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-mortarboard-fill text-warning me-2"></i>

                    Academic & Conduct Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Last Class --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Last Class Studied
                        </label>

                        <input
                            type="text"
                            name="last_class"
                            id="last_class"
                            value="{{ old('last_class') }}"
                            class="form-control @error('last_class') is-invalid @enderror"
                            placeholder="e.g. 10th"
                        >

                        @error('last_class')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Last Division --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Last Division
                        </label>

                        <input
                            type="text"
                            name="last_division"
                            id="last_division"
                            value="{{ old('last_division') }}"
                            class="form-control @error('last_division') is-invalid @enderror"
                            placeholder="e.g. A"
                        >

                        @error('last_division')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Conduct --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Conduct
                        </label>

                        <input
                            type="text"
                            name="conduct"
                            value="{{ old('conduct', 'Good') }}"
                            class="form-control @error('conduct') is-invalid @enderror"
                            placeholder="Good"
                        >

                        @error('conduct')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Progress --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Progress
                        </label>

                        <input
                            type="text"
                            name="progress"
                            value="{{ old('progress', 'Satisfactory') }}"
                            class="form-control @error('progress') is-invalid @enderror"
                            placeholder="Satisfactory"
                        >

                        @error('progress')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Enter any additional remarks..."
                        >{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACTION BUTTONS
        ====================================================== --}}
        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('admin.school-leaving-certificate.index') }}"
                class="btn btn-light border"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary px-4"
            >
                <i class="bi bi-check-circle me-1"></i>
                Create Certificate
            </button>

        </div>

    </form>

</div>


{{-- =============================================================
     STUDENT AUTO-FILL SCRIPT
============================================================= --}}
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

        const option =
            studentSelect.options[studentSelect.selectedIndex];

        if (!option || !option.value) {

            clearStudentDetails();

            return;
        }

        studentIdField.value =
            option.dataset.studentId || '';

        studentNameField.value =
            option.dataset.name || '';

        classField.value =
            option.dataset.class || '';

        sectionField.value =
            option.dataset.section || '';

        rollField.value =
            option.dataset.roll || '';

        dobField.value =
            option.dataset.dob || '';

        genderField.value =
            option.dataset.gender || '';

        /*
        |--------------------------------------------------------------------------
        | Automatically fill last class/division
        |--------------------------------------------------------------------------
        */

        const lastClass =
            document.getElementById('last_class');

        const lastDivision =
            document.getElementById('last_division');

        if (
            lastClass &&
            !lastClass.value
        ) {
            lastClass.value =
                option.dataset.class || '';
        }

        if (
            lastDivision &&
            !lastDivision.value
        ) {
            lastDivision.value =
                option.dataset.section || '';
        }
    }


    studentSelect.addEventListener(
        'change',
        loadStudentDetails
    );


    /*
    |--------------------------------------------------------------------------
    | Load selected student after validation error
    |--------------------------------------------------------------------------
    */

    loadStudentDetails();

});

</script>

@endpush


@endsection