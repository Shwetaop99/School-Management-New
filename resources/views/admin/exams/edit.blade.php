
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Exam
            </h4>

            <div class="text-muted">
                Update examination information
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.exams.show', $exam) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Exam
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Edit Form --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-event me-2"></i>
                Exam Information
            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.exams.update', $exam) }}"
            >

                @csrf
                @method('PUT')


                <div class="row g-4">

                    {{-- Academic Year --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Academic Year
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            class="form-control @error('academic_year') is-invalid @enderror"
                            value="{{ old('academic_year', $exam->academic_year) }}"
                            placeholder="2026-2027"
                            required
                        >

                        @error('academic_year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Exam Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Exam Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="exam_name"
                            class="form-control @error('exam_name') is-invalid @enderror"
                            value="{{ old('exam_name', $exam->exam_name) }}"
                            placeholder="Mid Term Examination"
                            required
                        >

                        @error('exam_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Exam Type --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Exam Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="exam_type"
                            class="form-select @error('exam_type') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Exam Type
                            </option>

                            @php
                                $examTypes = [
                                    'Unit Test',
                                    'First Term',
                                    'Mid Term',
                                    'Second Term',
                                    'Annual Examination',
                                    'Preliminary',
                                    'Pre-Board',
                                    'Final Examination',
                                    'Other',
                                ];
                            @endphp

                            @foreach($examTypes as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(old('exam_type', $exam->exam_type) === $type)
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                            {{-- Keep custom existing value if it is not in the list --}}
                            @if(
                                $exam->exam_type &&
                                !in_array($exam->exam_type, $examTypes)
                            )

                                <option
                                    value="{{ $exam->exam_type }}"
                                    selected
                                >
                                    {{ $exam->exam_type }}
                                </option>

                            @endif

                        </select>

                        @error('exam_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="draft"
                                @selected(old('status', $exam->status) === 'draft')
                            >
                                Draft
                            </option>

                            <option
                                value="scheduled"
                                @selected(old('status', $exam->status) === 'scheduled')
                            >
                                Scheduled
                            </option>

                            <option
                                value="completed"
                                @selected(old('status', $exam->status) === 'completed')
                            >
                                Completed
                            </option>

                            <option
                                value="cancelled"
                                @selected(old('status', $exam->status) === 'cancelled')
                            >
                                Cancelled
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Start Date --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Start Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old(
                                'start_date',
                                $exam->start_date?->format('Y-m-d')
                            ) }}"
                            required
                        >

                        @error('start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- End Date --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control @error('end_date') is-invalid @enderror"
                            value="{{ old(
                                'end_date',
                                $exam->end_date?->format('Y-m-d')
                            ) }}"
                        >

                        <div class="form-text">
                            The timetable generator can update the end date
                            according to the generated timetable.
                        </div>

                        @error('end_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Divider --}}
                <hr class="my-4">


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.exams.show', $exam) }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Update Exam
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Exam Setup Information --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <h6 class="fw-semibold mb-3">
                <i class="bi bi-info-circle me-2"></i>
                Exam Setup
            </h6>

            <div class="row g-3">

                <div class="col-md-3">
                    <div class="small text-muted">
                        Classes
                    </div>

                    <div class="fw-semibold">
                        Configure from Exam Details
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted">
                        Subjects
                    </div>

                    <div class="fw-semibold">
                        Marks & Duration
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted">
                        Sessions
                    </div>

                    <div class="fw-semibold">
                        Exam Timing
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted">
                        Holidays
                    </div>

                    <div class="fw-semibold">
                        Non-working Days
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection

