
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>
                Create Exam
            </h4>

            <p class="text-muted mb-0">
                Create the basic exam information first.
            </p>
        </div>

        <a href="{{ route('admin.exams.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Exams

        </a>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Create Exam Card --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Exam Information
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.exams.store') }}">

                @csrf


                <div class="row g-4">


                    {{-- Academic Year --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Academic Year
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="academic_year"
                               class="form-control"
                               value="{{ old('academic_year', '2026-2027') }}"
                               placeholder="2026-2027"
                               required>

                        <small class="text-muted">
                            Example: 2026-2027
                        </small>

                    </div>


                    {{-- Exam Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Exam Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="exam_name"
                               class="form-control"
                               value="{{ old('exam_name') }}"
                               placeholder="Mid Term Examination"
                               required>

                    </div>


                    {{-- Exam Type --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Exam Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="exam_type"
                                class="form-select"
                                required>

                            <option value="">
                                Select Exam Type
                            </option>

                            <option value="Unit Test"
                                {{ old('exam_type') == 'Unit Test' ? 'selected' : '' }}>
                                Unit Test
                            </option>

                            <option value="Terminal"
                                {{ old('exam_type') == 'Terminal' ? 'selected' : '' }}>
                                Terminal
                            </option>

                            <option value="Mid Term"
                                {{ old('exam_type') == 'Mid Term' ? 'selected' : '' }}>
                                Mid Term
                            </option>

                            <option value="Preliminary"
                                {{ old('exam_type') == 'Preliminary' ? 'selected' : '' }}>
                                Preliminary
                            </option>

                            <option value="Annual"
                                {{ old('exam_type') == 'Annual' ? 'selected' : '' }}>
                                Annual
                            </option>

                            <option value="Final"
                                {{ old('exam_type') == 'Final' ? 'selected' : '' }}>
                                Final
                            </option>

                            <option value="Other"
                                {{ old('exam_type') == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="draft"
                                {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="scheduled"
                                {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                                Scheduled
                            </option>

                            <option value="completed"
                                {{ old('status') == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="cancelled"
                                {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Start Date --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Start Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="start_date"
                               id="start_date"
                               class="form-control"
                               value="{{ old('start_date') }}"
                               required>

                        <small class="text-muted">
                            Initial exam start date.
                        </small>

                    </div>


                    {{-- End Date --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            End Date
                        </label>

                        <input type="date"
                               name="end_date"
                               id="end_date"
                               class="form-control"
                               value="{{ old('end_date') }}">

                        <small class="text-muted">
                            The timetable generator can automatically extend
                            the period when required.
                        </small>

                    </div>


                </div>


                {{-- Information --}}
                <div class="alert alert-info mt-4 mb-0">

                    <div class="d-flex">

                        <i class="bi bi-info-circle fs-5 me-2"></i>

                        <div>

                            <strong>Next steps after creating the exam:</strong>

                            <ol class="mb-0 mt-2">

                                <li>Select classes from the existing Classes module.</li>

                                <li>Configure subjects, maximum marks,
                                    passing marks and duration.</li>

                                <li>Create exam sessions.</li>

                                <li>Add holidays if required.</li>

                                <li>Generate the timetable automatically.</li>

                            </ol>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('admin.exams.index') }}"
                       class="btn btn-light border">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>

                        Create Exam

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection

