
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route('admin.exams.index') }}"
                   class="text-decoration-none text-muted">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h4 class="mb-0 fw-bold">
                    {{ $exam->exam_name }}
                </h4>

            </div>

            <p class="text-muted mb-0">
                Exam setup and timetable management
            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.exams.edit', $exam) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-pencil me-1"></i>
                Edit Exam

            </a>

            <a href="{{ route('admin.exams.index') }}"
               class="btn btn-light border">

                <i class="bi bi-list me-1"></i>
                All Exams

            </a>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please check the following:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         EXAM INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-info-circle me-2"></i>

                Exam Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Exam Name --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Exam Name
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_name }}
                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Academic Year
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->academic_year }}
                    </div>

                </div>


                {{-- Exam Type --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Exam Type
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_type }}
                    </div>

                </div>


                {{-- Start Date --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Start Date
                    </div>

                    <div class="fw-semibold">

                        {{ $exam->start_date?->format('d M Y') ?? '-' }}

                    </div>

                </div>


                {{-- End Date --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        End Date
                    </div>

                    <div class="fw-semibold">

                        {{ $exam->end_date?->format('d M Y') ?? 'Not calculated' }}

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    @php

                        $statusClass = match($exam->status) {

                            'draft' => 'bg-secondary',

                            'scheduled' => 'bg-primary',

                            'completed' => 'bg-success',

                            'cancelled' => 'bg-danger',

                            default => 'bg-secondary',

                        };

                    @endphp

                    <span class="badge {{ $statusClass }}">

                        {{ ucfirst($exam->status) }}

                    </span>

                </div>


            </div>

        </div>

    </div>


    {{-- =========================================================
         SETUP STEPS
    ========================================================== --}}
    <div class="row g-4 mb-4">


        {{-- =====================================================
             STEP 1 - CLASSES
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <div class="rounded-circle bg-primary bg-opacity-10
                                    text-primary p-3">

                            <i class="bi bi-mortarboard fs-4"></i>

                        </div>


                        <span class="badge bg-light text-dark border">
                            Step 1
                        </span>

                    </div>


                    <h5 class="fw-semibold">
                        Select Classes
                    </h5>

                    <p class="text-muted small">

                        Select which classes from the existing
                        Classes module will participate in this exam.

                    </p>


                    <div class="mb-3">

                        <span class="fs-4 fw-bold">
                            {{ $exam->exam_classes_count }}
                        </span>

                        <span class="text-muted">
                            selected
                        </span>

                    </div>


                    <a href="{{ route('admin.exam-classes.index', $exam) }}"
                       class="btn btn-outline-primary w-100">

                        <i class="bi bi-arrow-right me-1"></i>

                        Manage Classes

                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
             STEP 2 - SUBJECTS
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <div class="rounded-circle bg-success bg-opacity-10
                                    text-success p-3">

                            <i class="bi bi-book fs-4"></i>

                        </div>


                        <span class="badge bg-light text-dark border">
                            Step 2
                        </span>

                    </div>


                    <h5 class="fw-semibold">
                        Subjects & Marks
                    </h5>

                    <p class="text-muted small">

                        Configure subjects, maximum marks,
                        passing marks and exam duration.

                    </p>


                    <div class="mb-3">

                        <span class="fs-4 fw-bold">
                            {{ $exam->exam_subjects_count }}
                        </span>

                        <span class="text-muted">
                            configured
                        </span>

                    </div>


                    <a href="{{ route('admin.exam-subjects.index', $exam) }}"
                       class="btn btn-outline-success w-100">

                        <i class="bi bi-arrow-right me-1"></i>

                        Configure Subjects

                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
             STEP 3 - SESSIONS
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <div class="rounded-circle bg-warning bg-opacity-10
                                    text-warning p-3">

                            <i class="bi bi-clock fs-4"></i>

                        </div>


                        <span class="badge bg-light text-dark border">
                            Step 3
                        </span>

                    </div>


                    <h5 class="fw-semibold">
                        Exam Sessions
                    </h5>

                    <p class="text-muted small">

                        Define Morning, Afternoon or custom
                        exam session timings.

                    </p>


                    <div class="mb-3">

                        <span class="fs-4 fw-bold">
                            {{ $exam->exam_sessions_count }}
                        </span>

                        <span class="text-muted">
                            active/configured
                        </span>

                    </div>


                    <a href="{{ route('admin.exam-sessions.index', $exam) }}"
                       class="btn btn-outline-warning w-100">

                        <i class="bi bi-arrow-right me-1"></i>

                        Manage Sessions

                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
             STEP 4 - HOLIDAYS
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <div class="rounded-circle bg-danger bg-opacity-10
                                    text-danger p-3">

                            <i class="bi bi-calendar-x fs-4"></i>

                        </div>


                        <span class="badge bg-light text-dark border">
                            Step 4
                        </span>

                    </div>


                    <h5 class="fw-semibold">
                        Holidays
                    </h5>

                    <p class="text-muted small">

                        Add holidays or non-working days
                        during the examination period.

                    </p>


                    <div class="mb-3">

                        <span class="fs-4 fw-bold">
                            {{ $exam->exam_holidays_count }}
                        </span>

                        <span class="text-muted">
                            configured
                        </span>

                    </div>


                    <a href="{{ route('admin.exam-holidays.index', $exam) }}"
                       class="btn btn-outline-danger w-100">

                        <i class="bi bi-arrow-right me-1"></i>

                        Manage Holidays

                    </a>

                </div>

            </div>

        </div>


    </div>


    {{-- =========================================================
         TIMETABLE SECTION
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1 fw-semibold">

                        <i class="bi bi-calendar3 me-2"></i>

                        Exam Timetable

                    </h5>

                    <p class="text-muted small mb-0">

                        Generate and manage the examination timetable
                        using the configured classes, subjects,
                        durations, sessions and holidays.

                    </p>

                </div>


                @if($exam->exam_timetables_count > 0)

                    <span class="badge bg-success">

                        {{ $exam->exam_timetables_count }}
                        timetable entries

                    </span>

                @endif

            </div>

        </div>


        <div class="card-body">

            @if(
                $exam->exam_classes_count > 0 &&
                $exam->exam_subjects_count > 0 &&
                $exam->exam_sessions_count > 0
            )

                <div class="row g-3">


                    {{-- Generate --}}
                    <div class="col-md-6">

                        <div class="border rounded p-4 h-100">

                            <div class="d-flex align-items-start">

                                <div class="rounded-circle bg-primary
                                            bg-opacity-10 text-primary
                                            p-3 me-3">

                                    <i class="bi bi-magic fs-4"></i>

                                </div>


                                <div>

                                    <h5 class="fw-semibold mb-2">

                                        Generate Timetable

                                    </h5>

                                    <p class="text-muted small mb-3">

                                        Automatically create the timetable
                                        using subject durations, session
                                        timings and holidays.

                                    </p>


                                    <a href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
                                       class="btn btn-primary">

                                        <i class="bi bi-magic me-1"></i>

                                        Generate Timetable

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- View --}}
                    <div class="col-md-6">

                        <div class="border rounded p-4 h-100">

                            <div class="d-flex align-items-start">

                                <div class="rounded-circle bg-success
                                            bg-opacity-10 text-success
                                            p-3 me-3">

                                    <i class="bi bi-calendar-week fs-4"></i>

                                </div>


                                <div>

                                    <h5 class="fw-semibold mb-2">

                                        View Timetable

                                    </h5>

                                    <p class="text-muted small mb-3">

                                        View, edit and print the generated
                                        examination timetable.

                                    </p>


                                    @if($exam->exam_timetables_count > 0)

                                        <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                                           class="btn btn-outline-success">

                                            <i class="bi bi-eye me-1"></i>

                                            View Timetable

                                        </a>

                                    @else

                                        <button type="button"
                                                class="btn btn-outline-secondary"
                                                disabled>

                                            <i class="bi bi-eye me-1"></i>

                                            No Timetable Yet

                                        </button>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                </div>


            @else

                {{-- Setup incomplete --}}

                <div class="text-center py-4">

                    <div class="mb-3">

                        <i class="bi bi-exclamation-circle
                                  text-warning fs-1"></i>

                    </div>


                    <h5 class="fw-semibold">

                        Complete Exam Setup First

                    </h5>


                    <p class="text-muted mb-4">

                        Configure classes, subjects and sessions
                        before generating the timetable.

                    </p>


                    <div class="d-flex justify-content-center
                                flex-wrap gap-2">


                        @if($exam->exam_classes_count == 0)

                            <a href="{{ route('admin.exam-classes.index', $exam) }}"
                               class="btn btn-outline-primary">

                                <i class="bi bi-mortarboard me-1"></i>

                                Select Classes

                            </a>

                        @endif


                        @if($exam->exam_subjects_count == 0)

                            <a href="{{ route('admin.exam-subjects.index', $exam) }}"
                               class="btn btn-outline-success">

                                <i class="bi bi-book me-1"></i>

                                Configure Subjects

                            </a>

                        @endif


                        @if($exam->exam_sessions_count == 0)

                            <a href="{{ route('admin.exam-sessions.index', $exam) }}"
                               class="btn btn-outline-warning">

                                <i class="bi bi-clock me-1"></i>

                                Create Sessions

                            </a>

                        @endif


                    </div>

                </div>

            @endif

        </div>

    </div>


</div>

@endsection

