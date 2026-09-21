
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route('admin.exams.index') }}"
                   class="text-decoration-none text-muted">

                    <i class="bi bi-arrow-left"></i>
                    Examinations

                </a>

                <span class="text-muted">/</span>

                <span class="text-muted">
                    Create Exam
                </span>

            </div>

            <h3 class="fw-bold mb-1">

                <i class="bi bi-plus-circle-fill text-primary me-2"></i>

                Create Examination

            </h3>

            <p class="text-muted mb-0">
                Create a new examination for the academic year.
            </p>

        </div>


        <a href="{{ route('admin.exams.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Exams

        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                Please fix the following errors:

            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        CREATE EXAM CARD
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-journal-plus text-primary me-2"></i>

                Examination Details

            </h5>

            <small class="text-muted">
                Enter the basic information for the examination.
            </small>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.exams.store') }}">

                @csrf


                <div class="row g-4">

                    {{-- =================================================
                        ACADEMIC YEAR
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="academic_year"
                               class="form-label fw-semibold">

                            Academic Year
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="academic_year"
                               id="academic_year"
                               class="form-control @error('academic_year') is-invalid @enderror"
                               value="{{ old('academic_year', '2026-27') }}"
                               placeholder="Example: 2026-27"
                               maxlength="20"
                               required>

                        @error('academic_year')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text">
                            Enter the academic year in which this examination is conducted.
                        </div>

                    </div>


                    {{-- =================================================
                        EXAM TYPE
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="exam_type"
                               class="form-label fw-semibold">

                            Examination Type
                            <span class="text-danger">*</span>

                        </label>

                        <select name="exam_type"
                                id="exam_type"
                                class="form-select @error('exam_type') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Examination Type
                            </option>

                            @foreach([
                                'Unit Test',
                                'Periodic Test',
                                'Mid Term',
                                'Terminal Examination',
                                'Half Yearly',
                                'Preliminary Examination',
                                'Annual Examination',
                                'Final Examination',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old('exam_type') === $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                        @error('exam_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        EXAM NAME
                    ================================================== --}}
                    <div class="col-md-12">

                        <label for="exam_name"
                               class="form-label fw-semibold">

                            Examination Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="exam_name"
                               id="exam_name"
                               class="form-control @error('exam_name') is-invalid @enderror"
                               value="{{ old('exam_name') }}"
                               placeholder="Example: Mid Term Examination"
                               maxlength="255"
                               required>

                        @error('exam_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text">
                            Enter the display name of the examination.
                        </div>

                    </div>


                    {{-- =================================================
                        START DATE
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="start_date"
                               class="form-label fw-semibold">

                            Start Date

                        </label>

                        <input type="date"
                               name="start_date"
                               id="start_date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date') }}">

                        @error('start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        END DATE
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="end_date"
                               class="form-label fw-semibold">

                            End Date

                        </label>

                        <input type="date"
                               name="end_date"
                               id="end_date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date') }}">

                        @error('end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label fw-semibold">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}>

                                Inactive

                            </option>

                            <option value="completed"
                                {{ old('status') === 'completed' ? 'selected' : '' }}>

                                Completed

                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text">
                            Active examinations can be used for exam setup and marks entry.
                        </div>

                    </div>


                    {{-- =================================================
                        DESCRIPTION
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="description"
                               class="form-label fw-semibold">

                            Description

                        </label>

                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Enter examination description or instructions...">{{ old('description') }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                    FORM BUTTONS
                ================================================== --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route('admin.exams.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-lg me-1"></i>

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>

                        Create Examination

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        NEXT STEP INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex align-items-start">

                <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>

                <div>

                    <h6 class="fw-bold mb-1">
                        What happens next?
                    </h6>

                    <p class="text-muted mb-0">

                        After creating the examination, you can assign
                        classes and sections to it. Later, subjects,
                        schedules, students and teacher-wise marks can
                        be configured from the examination.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
