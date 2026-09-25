
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route('admin.exams.show', $exam) }}"
                   class="text-muted text-decoration-none">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h4 class="mb-0 fw-bold">
                    Subjects & Marks
                </h4>

            </div>

            <p class="text-muted mb-0">

                {{ $exam->exam_name }}
                ·
                {{ $exam->academic_year }}

            </p>

        </div>


        <a href="{{ route('admin.exams.show', $exam) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Exam

        </a>

    </div>


    {{-- =========================================================
         SUCCESS
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
         ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please correct the following:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         INFORMATION
    ========================================================== --}}
    <div class="alert alert-info border-0 shadow-sm">

        <div class="d-flex">

            <i class="bi bi-info-circle fs-5 me-3"></i>

            <div>

                <div class="fw-semibold mb-1">

                    Subjects are fetched automatically from the Classes module.

                </div>

                <div class="small">

                    You do not need to create subjects again here.
                    Configure only the maximum marks, passing marks
                    and duration for this particular exam.

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         NO CLASSES
    ========================================================== --}}
    @if($examClasses->isEmpty())

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="bi bi-mortarboard fs-1 text-muted"></i>

                <h5 class="fw-semibold mt-3">

                    No Classes Selected

                </h5>

                <p class="text-muted">

                    Select classes before configuring subjects.

                </p>

                <a href="{{ route('admin.exam-classes.index', $exam) }}"
                   class="btn btn-primary">

                    <i class="bi bi-mortarboard me-1"></i>

                    Select Classes

                </a>

            </div>

        </div>

    @else


        {{-- =====================================================
             FORM
        ====================================================== --}}
        <form method="POST"
              action="{{ route('admin.exam-subjects.store', $exam) }}">

            @csrf


            {{-- =================================================
                 EACH CLASS
            ================================================== --}}
            @foreach($examClasses as $examClass)

                @php

                    $class = $examClass->schoolClass;

                    $classSubjects = $class?->subjects ?? collect();

                @endphp


                @if($class)


                    <div class="card border-0 shadow-sm mb-4">


                        {{-- =====================================
                             CLASS HEADER
                        ====================================== --}}
                        <div class="card-header bg-white py-3">

                            <div class="d-flex justify-content-between
                                        align-items-center">

                                <div>

                                    <h5 class="mb-1 fw-semibold">

                                        <i class="bi bi-mortarboard me-2
                                                  text-primary"></i>

                                        {{ $class->class_name }}

                                        @if($class->section)

                                            <span class="text-muted">
                                                - {{ $class->section }}
                                            </span>

                                        @endif

                                    </h5>

                                    <small class="text-muted">

                                        {{ $classSubjects->count() }}
                                        {{ Str::plural('subject', $classSubjects->count()) }}
                                        available

                                    </small>

                                </div>


                                @if($classSubjects->isNotEmpty())

                                    <div>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary
                                                       select-all-class"
                                                data-class-id="{{ $class->id }}">

                                            <i class="bi bi-check2-all me-1"></i>

                                            Select All

                                        </button>

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- =====================================
                             SUBJECTS
                        ====================================== --}}
                        <div class="card-body p-0">

                            @if($classSubjects->isEmpty())

                                <div class="p-4 text-center text-muted">

                                    <i class="bi bi-book fs-3"></i>

                                    <div class="mt-2">

                                        No active subjects found
                                        for this class.

                                    </div>

                                </div>

                            @else

                                <div class="table-responsive">

                                    <table class="table table-hover
                                                  align-middle mb-0">

                                        <thead class="table-light">

                                            <tr>

                                                <th class="ps-4"
                                                    style="width:70px;">

                                                    Include

                                                </th>

                                                <th>
                                                    Subject
                                                </th>

                                                <th>
                                                    Subject Code
                                                </th>

                                                <th style="width:170px;">
                                                    Maximum Marks
                                                </th>

                                                <th style="width:170px;">
                                                    Passing Marks
                                                </th>

                                                <th style="width:170px;">
                                                    Duration
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>


                                            @foreach($classSubjects as $subject)

                                                @php

                                                    $key =
                                                        $class->id .
                                                        '_' .
                                                        $subject->id;

                                                    $existing =
                                                        $examSubjects[$key]
                                                        ?? null;

                                                @endphp


                                                <tr>


                                                    {{-- =================================
                                                         INCLUDE
                                                    ================================== --}}
                                                    <td class="ps-4">

                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][class_id]"
                                                            value="{{ $class->id }}">


                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][subject_id]"
                                                            value="{{ $subject->id }}">


                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][status]"
                                                            value="0">


                                                        <div class="form-check">

                                                            <input
                                                                class="form-check-input subject-checkbox"
                                                                type="checkbox"
                                                                name="subjects[{{ $class->id }}_{{ $subject->id }}][status]"
                                                                value="1"
                                                                data-class-id="{{ $class->id }}"
                                                                {{ $existing
                                                                    ? ($existing->status ? 'checked' : '')
                                                                    : 'checked'
                                                                }}>

                                                        </div>

                                                    </td>


                                                    {{-- =================================
                                                         SUBJECT
                                                    ================================== --}}
                                                    <td>

                                                        <div class="fw-semibold">

                                                            {{ $subject->subject_name }}

                                                        </div>

                                                    </td>


                                                    {{-- =================================
                                                         CODE
                                                    ================================== --}}
                                                    <td>

                                                        @if($subject->subject_code)

                                                            <span class="badge
                                                                         bg-light
                                                                         text-dark
                                                                         border">

                                                                {{ $subject->subject_code }}

                                                            </span>

                                                        @else

                                                            <span class="text-muted">
                                                                -
                                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- =================================
                                                         MAXIMUM MARKS
                                                    ================================== --}}
                                                    <td>

                                                        <input
                                                            type="number"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][maximum_marks]"
                                                            class="form-control"
                                                            min="1"
                                                            max="1000"
                                                            value="{{ old(
                                                                'subjects.' . $class->id . '_' . $subject->id . '.maximum_marks',
                                                                $existing?->maximum_marks ?? 50
                                                            ) }}"
                                                            required>

                                                    </td>


                                                    {{-- =================================
                                                         PASSING MARKS
                                                    ================================== --}}
                                                    <td>

                                                        <input
                                                            type="number"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][passing_marks]"
                                                            class="form-control passing-marks"
                                                            min="0"
                                                            max="1000"
                                                            value="{{ old(
                                                                'subjects.' . $class->id . '_' . $subject->id . '.passing_marks',
                                                                $existing?->passing_marks ?? 17
                                                            ) }}"
                                                            required>

                                                    </td>


                                                    {{-- =================================
                                                         DURATION
                                                    ================================== --}}
                                                    <td>

                                                        <div class="input-group">

                                                            <input
                                                                type="number"
                                                                name="subjects[{{ $class->id }}_{{ $subject->id }}][duration_minutes]"
                                                                class="form-control"
                                                                min="1"
                                                                max="600"
                                                                value="{{ old(
                                                                    'subjects.' . $class->id . '_' . $subject->id . '.duration_minutes',
                                                                    $existing?->duration_minutes ?? 60
                                                                ) }}"
                                                                required>

                                                            <span class="input-group-text">
                                                                min
                                                            </span>

                                                        </div>

                                                    </td>


                                                </tr>

                                            @endforeach


                                        </tbody>

                                    </table>

                                </div>

                            @endif

                        </div>

                    </div>


                @endif

            @endforeach


            {{-- =================================================
                 SAVE AREA
            ================================================== --}}
            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>

                            <div class="fw-semibold">

                                <i class="bi bi-info-circle me-1"></i>

                                Exam-specific configuration

                            </div>

                            <small class="text-muted">

                                These marks and durations apply only
                                to this exam.

                            </small>

                        </div>


                        <div class="d-flex gap-2">

                            <a href="{{ route('admin.exams.show', $exam) }}"
                               class="btn btn-light border">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-circle me-1"></i>

                                Save Subjects & Marks

                            </button>

                        </div>

                    </div>

                </div>

            </div>


        </form>


    @endif

</div>


{{-- =============================================================
     JAVASCRIPT
============================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Select All Subjects for Class
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.select-all-class')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const classId =
                    this.dataset.classId;

                const checkboxes =
                    document.querySelectorAll(
                        '.subject-checkbox[data-class-id="' +
                        classId +
                        '"]'
                    );

                let allChecked = true;

                checkboxes.forEach(function (checkbox) {

                    if (!checkbox.checked) {
                        allChecked = false;
                    }

                });

                checkboxes.forEach(function (checkbox) {

                    checkbox.checked = !allChecked;

                });

                this.innerHTML =
                    allChecked
                        ? '<i class="bi bi-check2-all me-1"></i> Select All'
                        : '<i class="bi bi-x-circle me-1"></i> Unselect All';

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Maximum Marks / Passing Marks Validation
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('input[name$="[maximum_marks]"]')
        .forEach(function (maximumInput) {

            maximumInput.addEventListener('input', function () {

                const row =
                    this.closest('tr');

                const passingInput =
                    row.querySelector(
                        '.passing-marks'
                    );

                if (!passingInput) {
                    return;
                }

                const maximum =
                    parseInt(this.value || 0);

                passingInput.max = maximum;

                if (
                    parseInt(passingInput.value || 0)
                    > maximum
                ) {

                    passingInput.value = maximum;

                }

            });

        });

});

</script>

@endsection

