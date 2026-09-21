
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
                    View Exam
                </span>

            </div>

            <h3 class="fw-bold mb-1">

                <i class="bi bi-journal-check text-primary me-2"></i>

                {{ $exam->exam_name }}

            </h3>

            <p class="text-muted mb-0">
                Examination Details
            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.exams.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

            <a href="{{ route('admin.exams.edit', $exam->id) }}"
               class="btn btn-warning">

                <i class="bi bi-pencil-square me-1"></i>
                Edit

            </a>

            <a href="{{ route('admin.exam-classes.index', $exam->id) }}"
               class="btn btn-primary">

                <i class="bi bi-people me-1"></i>
                Manage Classes

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row g-4">

        {{-- =====================================================
             EXAM INFORMATION
        ====================================================== --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-info-circle-fill text-primary me-2"></i>

                        Examination Information

                    </h5>

                    <small class="text-muted">
                        Basic details of this examination.
                    </small>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Exam Name --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Examination Name
                            </div>

                            <div class="fw-bold fs-5">
                                {{ $exam->exam_name }}
                            </div>

                        </div>


                        {{-- Exam Type --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Examination Type
                            </div>

                            <span class="badge bg-light text-dark border fs-6">
                                {{ $exam->exam_type }}
                            </span>

                        </div>


                        {{-- Academic Year --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Academic Year
                            </div>

                            <div class="fw-semibold">
                                {{ $exam->academic_year }}
                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

                            @if($exam->status === 'active')

                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Active
                                </span>

                            @elseif($exam->status === 'completed')

                                <span class="badge bg-primary">
                                    <i class="bi bi-check2-all me-1"></i>
                                    Completed
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    <i class="bi bi-dash-circle me-1"></i>
                                    Inactive
                                </span>

                            @endif

                        </div>


                        {{-- Start Date --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Start Date
                            </div>

                            <div class="fw-semibold">

                                @if($exam->start_date)

                                    {{ $exam->start_date->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        Not specified
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- End Date --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                End Date
                            </div>

                            <div class="fw-semibold">

                                @if($exam->end_date)

                                    {{ $exam->end_date->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        Not specified
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            @if($exam->description)

                                <div class="p-3 bg-light rounded">
                                    {{ $exam->description }}
                                </div>

                            @else

                                <span class="text-muted">
                                    No description provided.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             QUICK ACTIONS
        ====================================================== --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
                        Quick Actions
                    </h5>

                </div>


                <div class="card-body">

                    <a href="{{ route(
                        'admin.exam-classes.index',
                        $exam->id
                    ) }}"
                       class="btn btn-primary w-100 mb-3">

                        <i class="bi bi-people-fill me-2"></i>

                        Manage Classes

                    </a>


                    <a href="{{ route(
                        'admin.exams.edit',
                        $exam->id
                    ) }}"
                       class="btn btn-warning w-100 mb-3">

                        <i class="bi bi-pencil-square me-2"></i>

                        Edit Examination

                    </a>


                    <a href="{{ route(
                        'admin.exams.index'
                    ) }}"
                       class="btn btn-outline-secondary w-100">

                        <i class="bi bi-list-ul me-2"></i>

                        All Examinations

                    </a>

                </div>

            </div>


            {{-- Created Information --}}
            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <h6 class="fw-bold mb-3">
                        Record Information
                    </h6>

                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div class="fw-semibold">

                            @if($exam->creator)
                                {{ $exam->creator->name }}
                            @else
                                System
                            @endif

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Created On
                        </div>

                        <div class="fw-semibold">

                            {{ $exam->created_at->format('d M Y, h:i A') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        NEXT MODULE
    ========================================================== --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex align-items-start">

                <i class="bi bi-diagram-3-fill text-primary fs-4 me-3"></i>

                <div>

                    <h6 class="fw-bold mb-1">
                        Exam Setup
                    </h6>

                    <p class="text-muted mb-2">

                        Assign classes and sections to this examination
                        before configuring subjects, schedules and marks.

                    </p>

                    <a href="{{ route(
                        'admin.exam-classes.index',
                        $exam->id
                    ) }}"
                       class="btn btn-sm btn-primary">

                        Manage Exam Classes

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
