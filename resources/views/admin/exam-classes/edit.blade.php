@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-pencil-square text-primary me-2"></i>
            Edit Exam Class
        </h3>

        <p class="text-muted mb-0">
            Update class details for
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
        EDIT FORM
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
                      action="{{ route('admin.exam-classes.update', [$exam->id, $examClass->id]) }}">

                    @csrf
                    @method('PUT')

                    {{-- Exam --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Exam
                        </label>

                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $exam->exam_name }} ({{ $exam->academic_year }})"
                               readonly>

                    </div>


                    {{-- Class --}}
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
                                {{ old('class_name', $examClass->class_name) == 'Nursery' ? 'selected' : '' }}>
                                Nursery
                            </option>

                            <option value="LKG"
                                {{ old('class_name', $examClass->class_name) == 'LKG' ? 'selected' : '' }}>
                                LKG
                            </option>

                            <option value="UKG"
                                {{ old('class_name', $examClass->class_name) == 'UKG' ? 'selected' : '' }}>
                                UKG
                            </option>

                            @for ($i = 1; $i <= 12; $i++)

                                <option value="{{ $i }}"
                                    {{ old('class_name', $examClass->class_name) == $i ? 'selected' : '' }}>
                                    Class {{ $i }}
                                </option>

                            @endfor

                        </select>

                        @error('class_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="mb-4">

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
                                {{ old('status', $examClass->status) === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status', $examClass->status) === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Update Class
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
        EXAM DETAILS
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
                        Class
                    </small>

                    <strong>
                        {{ $examClass->class_name }}
                    </strong>
                </div>

                <div>
                    <small class="text-muted d-block">
                        Current Status
                    </small>

                    @if ($examClass->status === 'active')
                        <span class="badge bg-success">
                            Active
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            Inactive
                        </span>
                    @endif
                </div>

            </div>

        </div>


        {{-- =================================================
            NEXT STEP
        ================================================== --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    <i class="bi bi-diagram-3 text-primary me-2"></i>
                    Class Setup
                </h6>

                <p class="text-muted small mb-3">
                    After updating this class, you can manage
                    its exam sections.
                </p>

                
<a href="{{ route(
    'admin.exam-class-sections.index',
    [
        'exam' => $exam->id,
        'examClass' => $examClass->id,
    ]
) }}"
   class="btn btn-primary">

    <i class="bi bi-diagram-3-fill me-1"></i>

    Manage Sections

</a>


            </div>

        </div>

    </div>

</div>


</div>

@endsection
