```blade
@extends('layouts.app')

@section('title', 'Generate Exam Timetable')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-calendar2-plus me-2 text-primary"></i>
                Generate Exam Timetable
            </h3>

            <div class="text-muted">
                {{ $exam->exam_name ?? 'Examination' }}
                @if($exam->academic_year)
                    • {{ $exam->academic_year }}
                @endif
            </div>
        </div>

        <a href="{{ route('admin.exam-schedules.index', $exam) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Timetable
        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Please fix the following:
            </div>

            <ul class="mb-0 ps-3">

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

            <h5 class="mb-0 fw-bold">
                <i class="bi bi-info-circle me-2 text-primary"></i>
                Examination Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="small text-muted">
                        Examination
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="small text-muted">
                        Academic Year
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->academic_year ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="small text-muted">
                        Exam Type
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_type ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="small text-muted">
                        Exam Period
                    </div>

                    <div class="fw-semibold">

                        {{ $exam->start_date
                            ? \Carbon\Carbon::parse($exam->start_date)->format('d M Y')
                            : '-'
                        }}

                        <span class="text-muted mx-1">to</span>

                        {{ $exam->end_date
                            ? \Carbon\Carbon::parse($exam->end_date)->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        AUTOMATIC GENERATION FORM
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-bold">
                <i class="bi bi-magic me-2 text-primary"></i>
                Automatic Timetable Generation
            </h5>

        </div>


        <div class="card-body">

            <div class="alert alert-info border-0 mb-4">

                <div class="fw-bold mb-2">
                    <i class="bi bi-lightbulb me-1"></i>
                    How automatic generation works
                </div>

                <ul class="mb-0 ps-3">

                    <li>
                        Exam dates are taken automatically from the
                        configured exam start and end dates.
                    </li>

                    <li>
                        Saturdays and Sundays are automatically skipped.
                    </li>

                    <li>
                        Configured examination holidays are automatically skipped.
                    </li>

                    <li>
                        Every class gets its own independent subject sequence.
                    </li>

                    <li>
                        Each class advances to its next subject after every paper slot.
                    </li>

                    <li>
                        When a class finishes all its subjects, it is automatically
                        skipped for the remaining slots.
                    </li>

                    <li>
                        Teachers are not selected during automatic generation.
                        They can be assigned later from the Edit timetable screen.
                    </li>

                    <li>
                        Manual examination sessions are not required.
                    </li>

                </ul>

            </div>


            <form method="POST"
                  action="{{ route('admin.exam-schedules.generate', $exam) }}"
                  id="generateTimetableForm">

                @csrf


                {{-- =====================================================
                    PAPERS PER DAY
                ====================================================== --}}
                <div class="row g-4">

                    <div class="col-lg-6">

                        <label for="papers_per_day"
                               class="form-label fw-semibold">

                            Papers Per Day
                            <span class="text-danger">*</span>

                        </label>

                        <select name="papers_per_day"
                                id="papers_per_day"
                                class="form-select form-select-lg"
                                required>

                            <option value="">
                                Select number of papers
                            </option>

                            <option value="1"
                                {{ old('papers_per_day') == '1' ? 'selected' : '' }}>
                                1 Paper Per Day
                            </option>

                            <option value="2"
                                {{ old('papers_per_day') == '2' ? 'selected' : '' }}>
                                2 Papers Per Day
                            </option>

                            <option value="3"
                                {{ old('papers_per_day') == '3' ? 'selected' : '' }}>
                                3 Papers Per Day
                            </option>

                        </select>

                        <div class="form-text">
                            Choose how many papers each class can have
                            on an examination day.
                        </div>

                    </div>


                    {{-- =================================================
                        AUTOMATIC TIME INFORMATION
                    ================================================== --}}
                    <div class="col-lg-6">

                        <label class="form-label fw-semibold">
                            Automatic Paper Times
                        </label>

                        <div class="border rounded p-3 bg-light">

                            <div class="row g-2">

                                <div class="col-md-4">

                                    <div class="border rounded bg-white p-3 text-center">

                                        <div class="small text-muted">
                                            Paper 1
                                        </div>

                                        <div class="fw-bold fs-5">
                                            10:00 AM
                                        </div>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="border rounded bg-white p-3 text-center">

                                        <div class="small text-muted">
                                            Paper 2
                                        </div>

                                        <div class="fw-bold fs-5">
                                            01:00 PM
                                        </div>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="border rounded bg-white p-3 text-center">

                                        <div class="small text-muted">
                                            Paper 3
                                        </div>

                                        <div class="fw-bold fs-5">
                                            03:00 PM
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="small text-muted mt-3">

                                <i class="bi bi-info-circle me-1"></i>

                                Only the selected number of daily paper slots
                                will be used.

                                The actual paper end time is calculated from
                                the subject's configured duration.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                    CLASSES
                ====================================================== --}}
                <div class="mt-4">

                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <label class="form-label fw-semibold mb-0">
                            Classes Included
                        </label>

                        <span class="badge bg-primary">
                            {{ $examClasses->count() }} Classes
                        </span>

                    </div>


                    @if($examClasses->isEmpty())

                        <div class="alert alert-warning">
                            No classes have been added to this examination.
                        </div>

                    @else

                        <div class="border rounded p-3">

                            <div class="row g-2">

                                @foreach($examClasses as $examClass)

                                    <div class="col-md-3 col-sm-6">

                                        <div class="border rounded bg-light p-2">

                                            <i class="bi bi-mortarboard me-1 text-primary"></i>

                                            {{ $examClass->schoolClass->class_name
                                                ?? $examClass->schoolClass->name
                                                ?? 'Unknown Class'
                                            }}

                                            @if($examClass->schoolClass->section ?? null)

                                                <span class="text-muted">
                                                    - {{ $examClass->schoolClass->section }}
                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </div>


                {{-- =====================================================
                    SUBJECT SUMMARY
                ====================================================== --}}
                <div class="mt-4">

                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <label class="form-label fw-semibold mb-0">
                            Configured Exam Subjects
                        </label>

                        <span class="badge bg-secondary">
                            {{ $examSubjects->count() }} Subjects
                        </span>

                    </div>


                    @if($examSubjects->isEmpty())

                        <div class="alert alert-warning">
                            No examination subjects have been configured.
                        </div>

                    @else

                        <div class="table-responsive border rounded">

                            <table class="table table-sm table-hover mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th style="width: 60px;">
                                            #
                                        </th>

                                        <th>
                                            Class
                                        </th>

                                        <th>
                                            Subject
                                        </th>

                                        <th class="text-center">
                                            Marks
                                        </th>

                                        <th class="text-center">
                                            Duration
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($examSubjects as $index => $examSubject)

                                        <tr>

                                            <td>
                                                {{ $index + 1 }}
                                            </td>

                                            <td>

                                                {{ $examSubject->schoolClass->class_name
                                                    ?? $examSubject->schoolClass->name
                                                    ?? '-'
                                                }}

                                                @if($examSubject->schoolClass->section ?? null)

                                                    <span class="text-muted">
                                                        - {{ $examSubject->schoolClass->section }}
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="fw-semibold">

                                                {{ $examSubject->subject->subject_name
                                                    ?? 'Unknown Subject'
                                                }}

                                            </td>

                                            <td class="text-center">

                                                {{ $examSubject->maximum_marks ?? 0 }}

                                            </td>

                                            <td class="text-center">

                                                {{ $examSubject->duration_minutes ?? 60 }}
                                                min

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>


                {{-- =====================================================
                    HOLIDAYS
                ====================================================== --}}
                <div class="mt-4">

                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <label class="form-label fw-semibold mb-0">
                            Configured Holidays
                        </label>

                        <span class="badge bg-warning text-dark">
                            {{ $holidays->count() }} Holidays
                        </span>

                    </div>


                    @if($holidays->isEmpty())

                        <div class="text-muted small">
                            No additional examination holidays are configured.
                        </div>

                    @else

                        <div class="d-flex flex-wrap gap-2">

                            @foreach($holidays as $holiday)

                                <span class="badge bg-light text-dark border p-2">

                                    <i class="bi bi-calendar-x me-1 text-danger"></i>

                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}

                                    @if($holiday->holiday_name ?? null)

                                        -
                                        {{ $holiday->holiday_name }}

                                    @endif

                                </span>

                            @endforeach

                        </div>

                    @endif

                </div>


                {{-- =====================================================
                    REPLACE EXISTING
                ====================================================== --}}
                <div class="mt-4">

                    <div class="border rounded p-3">

                        <div class="form-check">

                            <input type="checkbox"
                                   class="form-check-input"
                                   name="replace_existing"
                                   value="1"
                                   id="replace_existing">

                            <label class="form-check-label fw-semibold"
                                   for="replace_existing">

                                Replace existing timetable

                            </label>

                        </div>

                        <div class="small text-muted mt-2">

                            If enabled, the existing timetable for this examination
                            will be deleted and a completely new timetable will
                            be generated.

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                    GENERATION SUMMARY
                ====================================================== --}}
                <div class="alert alert-success mt-4 mb-0">

                    <div class="fw-bold mb-2">

                        <i class="bi bi-check-circle me-1"></i>

                        Automatic Scheduling Rules

                    </div>

                    <ul class="mb-0 ps-3">

                        <li>
                            Start from the exam start date.
                        </li>

                        <li>
                            End at the exam end date.
                        </li>

                        <li>
                            Skip Saturday and Sunday.
                        </li>

                        <li>
                            Skip configured examination holidays.
                        </li>

                        <li>
                            Schedule subjects independently for each class.
                        </li>

                        <li>
                            Use the next available automatic paper slot for
                            each class.
                        </li>

                        <li>
                            Classes that finish their subjects early are skipped.
                        </li>

                        <li>
                            Teachers remain unassigned until manually edited.
                        </li>

                    </ul>

                </div>


                {{-- =====================================================
                    ACTIONS
                ====================================================== --}}
                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                    <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary btn-lg"
                            id="generateButton">

                        <i class="bi bi-magic me-1"></i>

                        Generate Timetable

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
    CONFIRMATION SCRIPT
========================================================== --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('generateTimetableForm');

    const button = document.getElementById('generateButton');

    const papersSelect =
        document.getElementById('papers_per_day');

    const replaceCheckbox =
        document.getElementById('replace_existing');


    if (!form) {
        return;
    }


    form.addEventListener('submit', function (event) {

        if (!papersSelect.value) {

            event.preventDefault();

            papersSelect.focus();

            alert('Please select Papers Per Day.');

            return;
        }


        let message =
            'Generate the examination timetable automatically?\n\n'
            + 'Papers Per Day: '
            + papersSelect.options[
                papersSelect.selectedIndex
            ].text
            + '\n\n'
            + 'The system will automatically schedule subjects '
            + 'for each class based on the examination dates.';


        if (
            replaceCheckbox &&
            replaceCheckbox.checked
        ) {

            message +=
                '\n\nWARNING: Existing timetable entries for this examination '
                + 'will be deleted and replaced.';
        }


        if (!confirm(message)) {

            event.preventDefault();

            return;
        }


        if (button) {

            button.disabled = true;

            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>'
                + 'Generating Timetable...';

        }

    });

});

</script>

@endpush

@endsection
