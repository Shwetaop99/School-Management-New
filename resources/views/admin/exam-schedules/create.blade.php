
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route('admin.exam-schedules.index', $exam->id) }}"
                   class="text-decoration-none text-muted">

                    <i class="bi bi-arrow-left"></i>
                    Schedule

                </a>

                <span class="text-muted">/</span>

                <span class="text-muted">
                    Add
                </span>

            </div>

            <h3 class="fw-bold mb-1">

                <i class="bi bi-calendar-plus text-primary me-2"></i>

                Add Exam Schedule

            </h3>

            <p class="text-muted mb-0">

                {{ $exam->exam_name }}

                &nbsp;•&nbsp;

                Academic Year {{ $exam->academic_year }}

            </p>

        </div>


        <a href="{{ route('admin.exam-schedules.index', $exam->id) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Schedule

        </a>

    </div>


    {{-- =========================================================
        ERRORS
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
        FORM
    ========================================================== --}}

    <form method="POST"
          action="{{ route('admin.exam-schedules.store', $exam->id) }}">

        @csrf


        {{-- =====================================================
            EXAM INFORMATION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-info-circle text-primary me-2"></i>

                    Examination Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label text-muted small">
                            Examination
                        </label>

                        <div class="fw-semibold">
                            {{ $exam->exam_name }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label text-muted small">
                            Academic Year
                        </label>

                        <div class="fw-semibold">
                            {{ $exam->academic_year }}
                        </div>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label text-muted small">
                            Start Date
                        </label>

                        <div class="fw-semibold">

                            {{ $exam->start_date
                                ? $exam->start_date->format('d M Y')
                                : '-' }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label text-muted small">
                            End Date
                        </label>

                        <div class="fw-semibold">

                            {{ $exam->end_date
                                ? $exam->end_date->format('d M Y')
                                : '-' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            CLASS & SECTION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-diagram-3 text-primary me-2"></i>

                    Class & Section

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- CLASS --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Class

                            <span class="text-danger">
                                *
                            </span>

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
                                    {{ old('exam_class_id') == $examClass->id ? 'selected' : '' }}>

                                    @if(in_array(
                                        $examClass->class_name,
                                        ['Nursery', 'LKG', 'UKG']
                                    ))

                                        {{ $examClass->class_name }}

                                    @else

                                        Class {{ $examClass->class_name }}

                                    @endif

                                </option>

                            @endforeach

                        </select>


                        @error('exam_class_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SECTION --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Section

                        </label>


                        <select name="exam_class_section_id"
                                id="exam_class_section_id"
                                class="form-select @error('exam_class_section_id') is-invalid @enderror">

                            <option value="">
                                All Sections
                            </option>


                            {{-- ALL SECTIONS --}}

                            @foreach($examClasses as $examClass)

                                @foreach($examClass->sections as $section)

                                    <option value="{{ $section->id }}"
                                            data-class-id="{{ $examClass->id }}"
                                            class="section-option">

                                        Section {{ $section->section_name }}

                                    </option>

                                @endforeach

                            @endforeach

                        </select>


                        <div class="form-text">

                            Leave as
                            <strong>All Sections</strong>
                            if the same exam schedule applies to every section.

                        </div>


                        @error('exam_class_section_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SUBJECT
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-book text-primary me-2"></i>

                    Subject

                </h5>

            </div>


            <div class="card-body">

                <div class="alert alert-warning mb-0">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>

                        <div>

                            <strong>
                                Subject Module Not Configured
                            </strong>

                            <div class="small mt-1">

                                Subjects will be connected to this schedule
                                after the Class & Subject Module is created.

                                For now, the schedule can be saved without
                                selecting a subject.

                            </div>

                        </div>

                    </div>

                </div>


                <input type="hidden"
                       name="subject_id"
                       value="{{ old('subject_id') }}">

            </div>

        </div>


        {{-- =====================================================
            DATE & TIME
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-clock text-primary me-2"></i>

                    Date & Time

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- DATE --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Exam Date

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="date"
                               name="exam_date"
                               value="{{ old(
                                   'exam_date',
                                   $exam->start_date
                                       ? $exam->start_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               min="{{ $exam->start_date
                                   ? $exam->start_date->format('Y-m-d')
                                   : '' }}"
                               max="{{ $exam->end_date
                                   ? $exam->end_date->format('Y-m-d')
                                   : '' }}"
                               class="form-control @error('exam_date') is-invalid @enderror"
                               required>


                        @error('exam_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- START TIME --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Start Time

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="time"
                               name="start_time"
                               value="{{ old('start_time') }}"
                               class="form-control @error('start_time') is-invalid @enderror"
                               required>


                        @error('start_time')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- END TIME --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            End Time

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="time"
                               name="end_time"
                               value="{{ old('end_time') }}"
                               class="form-control @error('end_time') is-invalid @enderror"
                               required>


                        @error('end_time')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            MARKS
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-bar-chart text-primary me-2"></i>

                    Marks

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- MAX MARKS --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Maximum Marks

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="number"
                               name="max_marks"
                               value="{{ old('max_marks', 100) }}"
                               min="1"
                               class="form-control @error('max_marks') is-invalid @enderror"
                               required>


                        @error('max_marks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PASS MARKS --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Passing Marks

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="number"
                               name="pass_marks"
                               value="{{ old('pass_marks', 35) }}"
                               min="0"
                               class="form-control @error('pass_marks') is-invalid @enderror"
                               required>


                        @error('pass_marks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ROOM & STATUS
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-building text-primary me-2"></i>

                    Room & Status

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- ROOM --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Room / Hall

                        </label>


                        <input type="text"
                               name="room_no"
                               value="{{ old('room_no') }}"
                               class="form-control @error('room_no') is-invalid @enderror"
                               placeholder="Example: Room 5">


                        @error('room_no')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Status

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="scheduled"
                                {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>

                                Scheduled

                            </option>


                            <option value="completed"
                                {{ old('status') === 'completed' ? 'selected' : '' }}>

                                Completed

                            </option>


                            <option value="cancelled"
                                {{ old('status') === 'cancelled' ? 'selected' : '' }}>

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
            INSTRUCTIONS
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-card-text text-primary me-2"></i>

                    Instructions / Notes

                </h5>

            </div>


            <div class="card-body">

                <textarea name="instructions"
                          rows="4"
                          class="form-control @error('instructions') is-invalid @enderror"
                          placeholder="Enter any examination instructions or notes...">{{ old('instructions') }}</textarea>


                @error('instructions')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- =====================================================
            FORM BUTTONS
        ====================================================== --}}

        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.exam-schedules.index', $exam->id) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-x-lg me-1"></i>

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Save Schedule

            </button>

        </div>

    </form>

</div>


{{-- =============================================================
    SECTION DYNAMIC LOADING
============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect =
        document.getElementById('exam_class_id');

    const sectionSelect =
        document.getElementById('exam_class_section_id');

    const sectionOptions =
        Array.from(
            sectionSelect.querySelectorAll('.section-option')
        );


    /*
    |--------------------------------------------------------------------------
    | Load Sections Based On Selected Class
    |--------------------------------------------------------------------------
    */

    function loadSections(classId) {

        /*
        |--------------------------------------------------------------------------
        | Clear Current Sections
        |--------------------------------------------------------------------------
        */

        sectionSelect.innerHTML =
            '<option value="">All Sections</option>';


        /*
        |--------------------------------------------------------------------------
        | No Class Selected
        |--------------------------------------------------------------------------
        */

        if (!classId) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Add Matching Sections
        |--------------------------------------------------------------------------
        */

        sectionOptions.forEach(function (option) {

            if (
                String(option.dataset.classId) ===
                String(classId)
            ) {

                sectionSelect.appendChild(
                    option.cloneNode(true)
                );

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Restore Old Section After Validation Error
        |--------------------------------------------------------------------------
        */

        const oldSection =
            "{{ old('exam_class_section_id') }}";


        if (oldSection) {

            sectionSelect.value =
                oldSection;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Class Changed
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        function () {

            loadSections(
                this.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Load Sections On Page Load
    |--------------------------------------------------------------------------
    */

    if (classSelect.value) {

        loadSections(
            classSelect.value
        );

    }

});

</script>

@endsection
