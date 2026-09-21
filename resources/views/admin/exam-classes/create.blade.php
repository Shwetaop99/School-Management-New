@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-plus-circle text-primary me-2"></i>
            Add Class to Exam
        </h3>

        <p class="text-muted mb-0">
            Add a class for
            <strong>{{ $exam->exam_name }}</strong>
        </p>
    </div>

    <a href="{{ route('admin.exam-classes.index', $exam->id) }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Classes
    </a>

</div>


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Please fix the following errors:
        </div>

        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="row">

    {{-- =====================================================
        FORM
    ====================================================== --}}
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-mortarboard me-2 text-primary"></i>
                    Class Information
                </h5>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('admin.exam-classes.store', $exam->id) }}">

                    @csrf

                    {{-- Exam --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Exam
                        </label>

                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $exam->exam_name }} ({{ $exam->academic_year }})"
                               readonly>

                        <div class="form-text">
                            This class will be added to the selected exam.
                        </div>

                    </div>


                    {{-- Class Name --}}
                    <div class="mb-4">

                        <label for="class_name"
                               class="form-label fw-semibold">
                            Class Name
                            <span class="text-danger">*</span>
                        </label>

                        <select name="class_name"
                                id="class_name"
                                class="form-select @error('class_name') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            <option value="Nursery"
                                {{ old('class_name') == 'Nursery' ? 'selected' : '' }}>
                                Nursery
                            </option>

                            <option value="LKG"
                                {{ old('class_name') == 'LKG' ? 'selected' : '' }}>
                                LKG
                            </option>

                            <option value="UKG"
                                {{ old('class_name') == 'UKG' ? 'selected' : '' }}>
                                UKG
                            </option>

                            @for ($i = 1; $i <= 12; $i++)

                                <option value="{{ $i }}"
                                    {{ old('class_name') == $i ? 'selected' : '' }}>
                                    Class {{ $i }}
                                </option>

                            @endfor

                        </select>

                        @error('class_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Select the class that will participate in this exam.
                        </div>

                    </div>


                    {{-- Buttons --}}
                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Add Class
                        </button>

                        <a href="{{ route('admin.exam-classes.index', $exam->id) }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =====================================================
        EXAM INFORMATION
    ====================================================== --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Exam Details
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Exam Name
                    </small>

                    <strong>
                        {{ $exam->exam_name }}
                    </strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Academic Year
                    </small>

                    <strong>
                        {{ $exam->academic_year }}
                    </strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Exam Type
                    </small>

                    <strong>
                        {{ $exam->exam_type }}
                    </strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Start Date
                    </small>

                    <strong>
                        {{ $exam->start_date ? $exam->start_date->format('d M Y') : 'Not set' }}
                    </strong>
                </div>

                <div class="mb-0">
                    <small class="text-muted d-block">
                        End Date
                    </small>

                    <strong>
                        {{ $exam->end_date ? $exam->end_date->format('d M Y') : 'Not set' }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- =================================================
            FLOW INFO
        ================================================== --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    <i class="bi bi-diagram-3 text-primary me-2"></i>
                    Exam Setup Flow
                </h6>

                <div class="small text-muted">

                    <div class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Exam Created
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Add Classes
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-circle me-2"></i>
                        Add Sections
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-circle me-2"></i>
                        Add Subjects
                    </div>

                    <div>
                        <i class="bi bi-circle me-2"></i>
                        Create Exam Schedule
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
