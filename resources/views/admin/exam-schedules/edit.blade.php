@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-pencil-square text-primary me-2"></i>
            Edit Exam Schedule
        </h3>

        <div class="text-muted">
            {{ $exam->exam_name }}
            @if($exam->academic_year)
                <span class="mx-1">•</span>
                {{ $exam->academic_year }}
            @endif
        </div>
    </div>

    <a href="{{ route('admin.exam-schedules.index', $exam->id) }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Schedule
    </a>

</div>


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Please correct the following errors:
        </div>

        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-1"></i>
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif


{{-- =========================================================
    EXAM INFORMATION
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

            <div class="col-md-4">
                <div class="text-muted small">
                    Academic Year
                </div>

                <div class="fw-semibold">
                    {{ $exam->academic_year }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="text-muted small">
                    Exam Type
                </div>

                <div class="fw-semibold">
                    {{ $exam->exam_type }}
                </div>
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    EDIT FORM
========================================================== --}}
<form method="POST"
      action="{{ route(
          'admin.exam-schedules.update',
          [
              'exam' => $exam->id,
              'schedule' => $schedule->id
          ]
      ) }}">

    @csrf
    @method('PUT')


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-calendar-event text-primary me-2"></i>
                Schedule Details
            </h5>
        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- =================================================
                    CLASS
                ================================================== --}}
                <div class="col-md-6">

                    <label for="exam_class_id"
                           class="form-label fw-semibold">
                        Class <span class="text-danger">*</span>
                    </label>

                    <select name="exam_class_id"
                            id="exam_class_id"
                            class="form-select @error('exam_class_id') is-invalid @enderror"
                            required>

                        <option value="">
                            Select Class
                        </option>

                        @foreach($examClasses as $examClass)

                            <option value="{{ $examClass->id }}"
                                {{ old(
                                    'exam_class_id',
                                    $schedule->exam_class_id
                                ) == $examClass->id ? 'selected' : '' }}>

                                {{ $examClass->class_name }}

                            </option>

                        @endforeach

                    </select>

                    @error('exam_class_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    SECTION
                ================================================== --}}
                <div class="col-md-6">

                    <label for="exam_class_section_id"
                           class="form-label fw-semibold">

                        Section

                    </label>

                    <select name="exam_class_section_id"
                            id="exam_class_section_id"
                            class="form-select @error('exam_class_section_id') is-invalid @enderror">

                        <option value="">
                            All Sections
                        </option>

                        @foreach($sections as $section)

                            <option
                                value="{{ $section->id }}"
                                data-class-id="{{ $section->exam_class_id }}"
                                {{ old(
                                    'exam_class_section_id',
                                    $schedule->exam_class_section_id
                                ) == $section->id ? 'selected' : '' }}>

                                Section {{ $section->section_name }}

                            </option>

                        @endforeach

                    </select>

                    <div class="form-text">
                        Leave as "All Sections" if the examination
                        applies to the complete class.
                    </div>

                    @error('exam_class_section_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    EXAM DATE
                ================================================== --}}
                <div class="col-md-4">

                    <label for="exam_date"
                           class="form-label fw-semibold">

                        Exam Date <span class="text-danger">*</span>

                    </label>

                    <input type="date"
                           name="exam_date"
                           id="exam_date"
                           class="form-control @error('exam_date') is-invalid @enderror"
                           value="{{ old(
                               'exam_date',
                               $schedule->exam_date?->format('Y-m-d')
                           ) }}"
                           required>

                    @error('exam_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    START TIME
                ================================================== --}}
                <div class="col-md-4">

                    <label for="start_time"
                           class="form-label fw-semibold">

                        Start Time <span class="text-danger">*</span>

                    </label>

                    <input type="time"
                           name="start_time"
                           id="start_time"
                           class="form-control @error('start_time') is-invalid @enderror"
                           value="{{ old(
                               'start_time',
                               $schedule->start_time
                           ) }}"
                           required>

                    @error('start_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    END TIME
                ================================================== --}}
                <div class="col-md-4">

                    <label for="end_time"
                           class="form-label fw-semibold">

                        End Time <span class="text-danger">*</span>

                    </label>

                    <input type="time"
                           name="end_time"
                           id="end_time"
                           class="form-control @error('end_time') is-invalid @enderror"
                           value="{{ old(
                               'end_time',
                               $schedule->end_time
                           ) }}"
                           required>

                    @error('end_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    MAX MARKS
                ================================================== --}}
                <div class="col-md-4">

                    <label for="max_marks"
                           class="form-label fw-semibold">

                        Maximum Marks <span class="text-danger">*</span>

                    </label>

                    <input type="number"
                           name="max_marks"
                           id="max_marks"
                           class="form-control @error('max_marks') is-invalid @enderror"
                           value="{{ old(
                               'max_marks',
                               $schedule->max_marks
                           ) }}"
                           min="1"
                           required>

                    @error('max_marks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    PASS MARKS
                ================================================== --}}
                <div class="col-md-4">

                    <label for="pass_marks"
                           class="form-label fw-semibold">

                        Pass Marks <span class="text-danger">*</span>

                    </label>

                    <input type="number"
                           name="pass_marks"
                           id="pass_marks"
                           class="form-control @error('pass_marks') is-invalid @enderror"
                           value="{{ old(
                               'pass_marks',
                               $schedule->pass_marks
                           ) }}"
                           min="0"
                           required>

                    @error('pass_marks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    ROOM
                ================================================== --}}
                <div class="col-md-4">

                    <label for="room_no"
                           class="form-label fw-semibold">

                        Room / Hall No.

                    </label>

                    <input type="text"
                           name="room_no"
                           id="room_no"
                           class="form-control @error('room_no') is-invalid @enderror"
                           value="{{ old(
                               'room_no',
                               $schedule->room_no
                           ) }}"
                           maxlength="50"
                           placeholder="Example: Room 101">

                    @error('room_no')
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

                        Status <span class="text-danger">*</span>

                    </label>

                    <select name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                        <option value="scheduled"
                            {{ old(
                                'status',
                                $schedule->status
                            ) === 'scheduled' ? 'selected' : '' }}>
                            Scheduled
                        </option>

                        <option value="completed"
                            {{ old(
                                'status',
                                $schedule->status
                            ) === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>

                        <option value="cancelled"
                            {{ old(
                                'status',
                                $schedule->status
                            ) === 'cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                    SUBJECT NOTE
                ================================================== --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Subject
                    </label>

                    <div class="form-control bg-light text-muted">
                        Subject selection will be connected later
                        from Class Management.
                    </div>

                </div>


                {{-- =================================================
                    INSTRUCTIONS
                ================================================== --}}
                <div class="col-12">

                    <label for="instructions"
                           class="form-label fw-semibold">

                        Instructions

                    </label>

                    <textarea name="instructions"
                              id="instructions"
                              rows="4"
                              class="form-control @error('instructions') is-invalid @enderror"
                              placeholder="Enter any special instructions for this examination...">{{ old(
                                  'instructions',
                                  $schedule->instructions
                              ) }}</textarea>

                    @error('instructions')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- =========================================================
            FORM FOOTER
        ========================================================== --}}
        <div class="card-footer bg-white d-flex justify-content-end gap-2 py-3">

            <a href="{{ route(
                'admin.exam-schedules.index',
                $exam->id
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-x-circle me-1"></i>
                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-circle me-1"></i>
                Update Schedule

            </button>

        </div>

    </div>

</form>


</div>

{{-- =========================================================
SECTION FILTER SCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('exam_class_id');
    const sectionSelect = document.getElementById('exam_class_section_id');

    if (!classSelect || !sectionSelect) {
        return;
    }

    const allOptions = Array.from(
        sectionSelect.querySelectorAll('option[data-class-id]')
    );

    const selectedSectionId = "{{ old(
        'exam_class_section_id',
        $schedule->exam_class_section_id
    ) }}";

    function filterSections() {

        const classId = classSelect.value;

        sectionSelect.value = '';

        allOptions.forEach(function (option) {

            const matches =
                option.dataset.classId === classId;

            option.hidden = !matches;

            option.disabled = !matches;

        });

        if (selectedSectionId) {

            const selectedOption = allOptions.find(function (option) {

                return (
                    option.value === selectedSectionId &&
                    option.dataset.classId === classId
                );

            });

            if (selectedOption) {
                selectedOption.hidden = false;
                selectedOption.disabled = false;
                sectionSelect.value = selectedSectionId;
            }

        }

    }

    classSelect.addEventListener(
        'change',
        filterSections
    );

    filterSections();

});

</script>

@endsection
