
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route(
                    'admin.exam-class-sections.index',
                    [
                        'exam' => $exam->id,
                        'examClass' => $examClass->id,
                    ]
                ) }}"
                   class="text-decoration-none text-muted">

                    <i class="bi bi-arrow-left"></i>
                    Sections

                </a>

                <span class="text-muted">/</span>

                <span class="text-muted">
                    Add Section
                </span>

            </div>

            <h3 class="fw-bold mb-1">

                <i class="bi bi-plus-circle-fill text-primary me-2"></i>
                Add Section

            </h3>

            <p class="text-muted mb-0">

                {{ $exam->exam_name }}

                &nbsp;•&nbsp;

                Class {{ $examClass->class_name }}

            </p>

        </div>


        <a href="{{ route(
            'admin.exam-class-sections.index',
            [
                'exam' => $exam->id,
                'examClass' => $examClass->id,
            ]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Sections

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
                Please fix the following:

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
        CLASS INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Examination
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_name }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Academic Year
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->academic_year }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Class
                    </div>

                    <div class="fw-semibold">
                        {{ $examClass->class_name }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    @if($examClass->status === 'active')

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

    </div>


    {{-- =========================================================
        ADD SECTION FORM
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-diagram-3-fill text-primary me-2"></i>
                Section Details

            </h5>

            <small class="text-muted">

                Add a section for Class {{ $examClass->class_name }}

            </small>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.exam-class-sections.store',
                      [
                          'exam' => $exam->id,
                          'examClass' => $examClass->id,
                      ]
                  ) }}">

                @csrf


                <div class="row g-4">

                    {{-- =================================================
                        SECTION NAME
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="section_name"
                               class="form-label fw-semibold">

                            Section Name

                            <span class="text-danger">*</span>

                        </label>


                        <select name="section_name"
                                id="section_name"
                                class="form-select @error('section_name') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Section
                            </option>


                            @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $section)

                                <option value="{{ $section }}"
                                    {{ old('section_name') === $section ? 'selected' : '' }}>

                                    Section {{ $section }}

                                </option>

                            @endforeach

                        </select>


                        @error('section_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="form-text">

                            Select the section assigned to this class.

                        </div>

                    </div>


                    {{-- =================================================
                        INFORMATION
                    ================================================== --}}
                    <div class="col-md-6">

                        <div class="alert alert-info mb-0">

                            <div class="d-flex">

                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>

                                <div>

                                    <strong>
                                        Section Assignment
                                    </strong>

                                    <p class="mb-0 mt-1 small">

                                        Each section can be added only once
                                        to this exam class.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    BUTTONS
                ================================================== --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route(
                        'admin.exam-class-sections.index',
                        [
                            'exam' => $exam->id,
                            'examClass' => $examClass->id,
                        ]
                    ) }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-lg me-1"></i>
                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Add Section

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection