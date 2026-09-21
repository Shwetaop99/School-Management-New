@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Edit School Leaving Certificate
            </h3>

            <p class="text-muted mb-0">
                Update certificate information and student details.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.school-leaving-certificate.show', $certificate) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-eye me-1"></i>
                View Certificate
            </a>

            <a href="{{ route('admin.school-leaving-certificate.index') }}"
               class="btn btn-outline-dark">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>
        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())

        <div class="alert alert-danger">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    @endif


    {{-- =========================================================
        FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route('admin.school-leaving-certificate.update', $certificate) }}">

        @csrf
        @method('PUT')


        {{-- =====================================================
            STUDENT INFORMATION
        ====================================================== --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-vcard text-primary me-2"></i>
                    Student Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Student --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Select Student <span class="text-danger">*</span>
                        </label>

                        <select name="student_id"
                                id="student_id"
                                class="form-select @error('student_id') is-invalid @enderror"
                                required>

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

                                <option value="{{ $student->id }}"
                                        data-student-id="{{ $student->student_id ?? '' }}"
                                        data-name="{{ $studentName }}"
                                        data-class="{{ $student->class ?? '' }}"
                                        data-section="{{ $student->section ?? '' }}"
                                        data-roll="{{ $student->roll_number ?? '' }}"
                                        data-dob="{{ $student->date_of_birth ? \Illuminate\Support\Carbon::parse($student->date_of_birth)->format('d-m-Y') : '' }}"
                                        data-gender="{{ $student->gender ?? '' }}"
                                        {{ old('student_id', $certificate->student_id) == $student->id ? 'selected' : '' }}>

                                    {{ $student->student_id ?? 'N/A' }}
                                    -
                                    {{ $studentName ?: 'Unnamed Student' }}

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
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Student ID
                        </label>

                        <input type="text"
                               id="student_code"
                               class="form-control bg-light"
                               value="{{ $certificate->student->student_id ?? '' }}"
                               readonly>

                    </div>


                    {{-- Student Name --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Student Name
                        </label>

                        <input type="text"
                               id="student_name"
                               class="form-control bg-light"
                               value="{{ trim(
                                   ($certificate->student->first_name ?? '') . ' ' .
                                   ($certificate->student->middle_name ?? '') . ' ' .
                                   ($certificate->student->last_name ?? '')
                               ) }}"
                               readonly>

                    </div>


                    {{-- Class --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <input type="text"
                               id="student_class"
                               class="form-control bg-light"
                               value="{{ $certificate->student->class ?? '' }}"
                               readonly>

                    </div>


                    {{-- Division --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Division
                        </label>

                        <input type="text"
                               id="student_section"
                               class="form-control bg-light"
                               value="{{ $certificate->student->section ?? '' }}"
                               readonly>

                    </div>


                    {{-- Roll Number --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Roll Number
                        </label>

                        <input type="text"
                               id="student_roll"
                               class="form-control bg-light"
                               value="{{ $certificate->student->roll_number ?? '' }}"
                               readonly>

                    </div>


                    {{-- DOB --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Date of Birth
                        </label>

                        <input type="text"
                               id="student_dob"
                               class="form-control bg-light"
                               value="{{ $certificate->student->date_of_birth
                                   ? \Illuminate\Support\Carbon::parse($certificate->student->date_of_birth)->format('d-m-Y')
                                   : '' }}"
                               readonly>

                    </div>


                    {{-- Gender --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Gender
                        </label>

                        <input type="text"
                               id="student_gender"
                               class="form-control bg-light"
                               value="{{ $certificate->student->gender ?? '' }}"
                               readonly>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
            CERTIFICATE INFORMATION
        ====================================================== --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
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

                        <input type="text"
                               name="certificate_no"
                               value="{{ old('certificate_no', $certificate->certificate_no) }}"
                               class="form-control @error('certificate_no') is-invalid @enderror"
                               required>

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

                        <input type="date"
                               name="issue_date"
                               value="{{ old(
                                   'issue_date',
                                   $certificate->issue_date
                                       ? $certificate->issue_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               class="form-control @error('issue_date') is-invalid @enderror">

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

                        <input type="date"
                               name="leaving_date"
                               value="{{ old(
                                   'leaving_date',
                                   $certificate->leaving_date
                                       ? $certificate->leaving_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               class="form-control @error('leaving_date') is-invalid @enderror"
                               required>

                        <small class="text-muted">
                            This date is entered manually for the certificate.
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

                        <input type="text"
                               name="leaving_reason"
                               value="{{ old('leaving_reason', $certificate->leaving_reason) }}"
                               class="form-control @error('leaving_reason') is-invalid @enderror"
                               placeholder="Enter reason for leaving">

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

                        <input type="text"
                               name="academic_year"
                               value="{{ old('academic_year', $certificate->academic_year) }}"
                               class="form-control @error('academic_year') is-invalid @enderror"
                               placeholder="2026-2027">

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

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="draft"
                                {{ old('status', $certificate->status) === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="issued"
                                {{ old('status', $certificate->status) === 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>

                            <option value="cancelled"
                                {{ old('status', $certificate->status) === 'cancelled' ? 'selected' : '' }}>
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
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                    Academic & Conduct Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Last Class --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Last Class
                        </label>

                        <input type="text"
                               name="last_class"
                               id="last_class"
                               value="{{ old('last_class', $certificate->last_class) }}"
                               class="form-control @error('last_class') is-invalid @enderror">

                        @error('last_class')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Last Division --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Last Division
                        </label>

                        <input type="text"
                               name="last_division"
                               id="last_division"
                               value="{{ old('last_division', $certificate->last_division) }}"
                               class="form-control @error('last_division') is-invalid @enderror">

                        @error('last_division')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Conduct --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Conduct
                        </label>

                        <input type="text"
                               name="conduct"
                               value="{{ old('conduct', $certificate->conduct) }}"
                               class="form-control @error('conduct') is-invalid @enderror"
                               placeholder="Good">

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

                        <input type="text"
                               name="progress"
                               value="{{ old('progress', $certificate->progress) }}"
                               class="form-control @error('progress') is-invalid @enderror"
                               placeholder="Satisfactory">

                        @error('progress')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="3"
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  placeholder="Enter any additional remarks">{{ old('remarks', $certificate->remarks) }}</textarea>

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
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.school-leaving-certificate.show', $certificate) }}"
               class="btn btn-outline-secondary px-4">
                <i class="bi bi-x-circle me-1"></i>
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary px-4">
                <i class="bi bi-check-circle me-1"></i>
                Update Certificate
            </button>

        </div>

    </form>

</div>



{{-- =============================================================
    STUDENT AUTO-FILL SCRIPT
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const studentSelect = document.getElementById('student_id');

    const studentCode = document.getElementById('student_code');
    const studentName = document.getElementById('student_name');
    const studentClass = document.getElementById('student_class');
    const studentSection = document.getElementById('student_section');
    const studentRoll = document.getElementById('student_roll');
    const studentDob = document.getElementById('student_dob');
    const studentGender = document.getElementById('student_gender');

    const lastClass = document.getElementById('last_class');
    const lastDivision = document.getElementById('last_division');


    function updateStudentDetails() {

        const selectedOption =
            studentSelect.options[studentSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {

            studentCode.value = '';
            studentName.value = '';
            studentClass.value = '';
            studentSection.value = '';
            studentRoll.value = '';
            studentDob.value = '';
            studentGender.value = '';

            return;
        }


        studentCode.value =
            selectedOption.dataset.studentId || '';

        studentName.value =
            selectedOption.dataset.name || '';

        studentClass.value =
            selectedOption.dataset.class || '';

        studentSection.value =
            selectedOption.dataset.section || '';

        studentRoll.value =
            selectedOption.dataset.roll || '';

        studentDob.value =
            selectedOption.dataset.dob || '';

        studentGender.value =
            selectedOption.dataset.gender || '';


        /*
         * Automatically update academic information
         * when another student is selected.
         */
        lastClass.value =
            selectedOption.dataset.class || '';

        lastDivision.value =
            selectedOption.dataset.section || '';
    }


    studentSelect.addEventListener(
        'change',
        updateStudentDetails
    );

});

</script>

@endsection