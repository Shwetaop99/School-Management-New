@extends('layouts.app')

@section('title', 'Generate Exam Timetable')

@section('content')

<div class="container-fluid py-4">

```
{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-calendar2-week me-2"></i>
            Generate Exam Timetable
        </h3>

        <div class="text-muted">
            {{ $exam->exam_name ?? 'Examination' }}
            @if(!empty($exam->academic_year))
                <span class="mx-1">•</span>
                {{ $exam->academic_year }}
            @endif
        </div>
    </div>

    <div>
        <a
            href="{{ route('admin.exam-schedules.index', $exam) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Timetable
        </a>
    </div>

</div>


{{-- =========================================================
    VALIDATION / ERROR MESSAGES
========================================================== --}}
@if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm mb-4">

        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Timetable could not be generated
        </div>

        <ul class="mb-0 ps-4">

            @foreach($errors->all() as $error)

                <li class="mb-1">
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =========================================================
    EXAM INFORMATION
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="mb-0 fw-bold">
            <i class="bi bi-info-circle me-2 text-primary"></i>
            Examination Information
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Examination --}}
            <div class="col-md-6 col-lg-3">

                <div class="small text-muted mb-1">
                    Examination
                </div>

                <div class="fw-semibold">
                    {{ $exam->exam_name ?? '-' }}
                </div>

            </div>


            {{-- Academic Year --}}
            <div class="col-md-6 col-lg-3">

                <div class="small text-muted mb-1">
                    Academic Year
                </div>

                <div class="fw-semibold">
                    {{ $exam->academic_year ?? '-' }}
                </div>

            </div>


            {{-- Start Date --}}
            <div class="col-md-6 col-lg-3">

                <div class="small text-muted mb-1">
                    Exam Start Date
                </div>

                <div class="fw-semibold">

                    @if($exam->start_date)

                        {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}

                    @else

                        <span class="text-danger">
                            Not configured
                        </span>

                    @endif

                </div>

            </div>


            {{-- End Date --}}
            <div class="col-md-6 col-lg-3">

                <div class="small text-muted mb-1">
                    Exam End Date
                </div>

                <div class="fw-semibold">

                    @if($exam->end_date)

                        {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}

                    @else

                        <span class="text-danger">
                            Not configured
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    AUTOMATIC SCHEDULING INFORMATION
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex align-items-center gap-2">

            <div
                class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px;"
            >
                <i class="bi bi-clock-history"></i>
            </div>

            <div>

                <h5 class="mb-0 fw-bold">
                    Automatic Exam Time Management
                </h5>

                <small class="text-muted">
                    No manual sessions are required.
                </small>

            </div>

        </div>

    </div>


    <div class="card-body">

        <div class="alert alert-primary border-0 mb-4">

            <div class="d-flex gap-3">

                <i class="bi bi-magic fs-4"></i>

                <div>

                    <div class="fw-bold mb-1">
                        Time slots will be managed automatically
                    </div>

                    <div class="small">
                        After you select the number of papers per day,
                        the system automatically assigns the examination
                        time slots. You do not need to create or select
                        Session 1, Session 2 or Session 3.
                    </div>

                </div>

            </div>

        </div>


        <div class="row g-3">

            {{-- 1 PAPER --}}
            <div class="col-md-4">

                <div class="border rounded-3 p-3 h-100">

                    <div class="fw-bold mb-2">
                        <i class="bi bi-1-circle me-1 text-primary"></i>
                        1 Paper Per Day
                    </div>

                    <div class="small text-muted">
                        Paper 1
                    </div>

                    <div class="fw-semibold">
                        10:00 AM – 11:00 AM*
                    </div>

                </div>

            </div>


            {{-- 2 PAPERS --}}
            <div class="col-md-4">

                <div class="border rounded-3 p-3 h-100">

                    <div class="fw-bold mb-2">
                        <i class="bi bi-2-circle me-1 text-primary"></i>
                        2 Papers Per Day
                    </div>

                    <div class="small text-muted">
                        Paper 1
                    </div>

                    <div class="fw-semibold mb-2">
                        10:00 AM – 11:00 AM*
                    </div>

                    <div class="small text-muted">
                        Paper 2
                    </div>

                    <div class="fw-semibold">
                        01:00 PM – 02:00 PM*
                    </div>

                </div>

            </div>


            {{-- 3 PAPERS --}}
            <div class="col-md-4">

                <div class="border rounded-3 p-3 h-100">

                    <div class="fw-bold mb-2">
                        <i class="bi bi-3-circle me-1 text-primary"></i>
                        3 Papers Per Day
                    </div>

                    <div class="small text-muted">
                        Paper 1
                    </div>

                    <div class="fw-semibold mb-2">
                        10:00 AM – 11:00 AM*
                    </div>

                    <div class="small text-muted">
                        Paper 2
                    </div>

                    <div class="fw-semibold mb-2">
                        01:00 PM – 02:00 PM*
                    </div>

                    <div class="small text-muted">
                        Paper 3
                    </div>

                    <div class="fw-semibold">
                        03:00 PM – 04:00 PM*
                    </div>

                </div>

            </div>

        </div>


        <div class="small text-muted mt-3">

            <i class="bi bi-info-circle me-1"></i>

            The actual end time is calculated from the subject's configured
            duration. The system also checks that papers do not overlap.

        </div>

    </div>

</div>


{{-- =========================================================
    CLASSES INCLUDED
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0 fw-bold">

                <i class="bi bi-people me-2 text-primary"></i>

                Classes Included

            </h5>

            <span class="badge bg-primary">
                {{ $examClasses->count() }}
                {{ $examClasses->count() === 1 ? 'Class' : 'Classes' }}
            </span>

        </div>

    </div>


    <div class="card-body">

        @if($examClasses->count())

            <div class="row g-3">

                @foreach($examClasses as $examClass)

                    @php
                        $schoolClass = $examClass->schoolClass;
                    @endphp

                    <div class="col-md-6 col-lg-4 col-xl-3">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="d-flex align-items-center gap-2">

                                <div
                                    class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                    style="width: 38px; height: 38px;"
                                >
                                    <i class="bi bi-mortarboard"></i>
                                </div>

                                <div>

                                    <div class="fw-semibold">

                                        {{ $schoolClass->class_name ?? $schoolClass->name ?? 'Class' }}

                                    </div>

                                    @if(!empty($schoolClass->section))

                                        <div class="small text-muted">

                                            Section {{ $schoolClass->section }}

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="text-muted">
                No classes have been added to this examination.
            </div>

        @endif

    </div>

</div>


{{-- =========================================================
    SUBJECTS CONFIGURED
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0 fw-bold">

                <i class="bi bi-journal-text me-2 text-primary"></i>

                Exam Subjects

            </h5>

            <span class="badge bg-secondary">

                {{ $examSubjects->count() }}

                {{ $examSubjects->count() === 1 ? 'Subject' : 'Subjects' }}

            </span>

        </div>

    </div>


    <div class="card-body p-0">

        @if($examSubjects->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                #
                            </th>

                            <th>
                                Class
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Maximum Marks
                            </th>

                            <th>
                                Passing Marks
                            </th>

                            <th>
                                Duration
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($examSubjects as $index => $examSubject)

                            <tr>

                                <td class="ps-4">
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    @php
                                        $schoolClass = $examSubject->schoolClass;
                                    @endphp

                                    <span class="fw-semibold">

                                        {{ $schoolClass->class_name ?? $schoolClass->name ?? '-' }}

                                    </span>

                                    @if(!empty($schoolClass->section))

                                        <small class="text-muted">

                                            - {{ $schoolClass->section }}

                                        </small>

                                    @endif

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ $examSubject->subject->subject_name ?? 'Subject' }}

                                    </div>

                                    @if(!empty($examSubject->subject->subject_code))

                                        <small class="text-muted">

                                            {{ $examSubject->subject->subject_code }}

                                        </small>

                                    @endif

                                </td>


                                <td>

                                    {{ $examSubject->maximum_marks ?? '-' }}

                                </td>


                                <td>

                                    {{ $examSubject->passing_marks ?? '-' }}

                                </td>


                                <td>

                                    {{ $examSubject->duration_minutes ?? 60 }}
                                    min

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-4 text-muted">

                No exam subjects have been configured.

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
    HOLIDAYS
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0 fw-bold">

                <i class="bi bi-calendar-x me-2 text-danger"></i>

                Holidays Excluded From Scheduling

            </h5>

            <span class="badge bg-danger">

                {{ $holidays->count() }}

            </span>

        </div>

    </div>


    <div class="card-body">

        <div class="mb-3 small text-muted">

            Saturday and Sunday are automatically excluded.
            The following configured examination holidays are also skipped.

        </div>


        @if($holidays->count())

            <div class="d-flex flex-wrap gap-2">

                @foreach($holidays as $holiday)

                    <span class="badge bg-light text-dark border px-3 py-2">

                        <i class="bi bi-calendar-x me-1 text-danger"></i>

                        {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}

                        @if(!empty($holiday->holiday_name))

                            <span class="text-muted">
                                — {{ $holiday->holiday_name }}
                            </span>

                        @endif

                    </span>

                @endforeach

            </div>

        @else

            <span class="text-muted">
                No additional examination holidays configured.
            </span>

        @endif

    </div>

</div>


{{-- =========================================================
    GENERATION SETTINGS
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="mb-0 fw-bold">

            <i class="bi bi-gear me-2 text-primary"></i>

            Generation Settings

        </h5>

    </div>


    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.exam-schedules.generate', $exam) }}"
            id="generateTimetableForm"
        >

            @csrf


            <div class="row g-4">

                {{-- Papers Per Day --}}
                <div class="col-md-6">

                    <label
                        for="papers_per_day"
                        class="form-label fw-semibold"
                    >
                        Papers Per Day
                        <span class="text-danger">*</span>
                    </label>


                    <select
                        name="papers_per_day"
                        id="papers_per_day"
                        class="form-select @error('papers_per_day') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            Select papers per day
                        </option>

                        <option
                            value="1"
                            {{ old('papers_per_day') == '1' ? 'selected' : '' }}
                        >
                            1 Paper Per Day
                        </option>

                        <option
                            value="2"
                            {{ old('papers_per_day') == '2' ? 'selected' : '' }}
                        >
                            2 Papers Per Day
                        </option>

                        <option
                            value="3"
                            {{ old('papers_per_day') == '3' ? 'selected' : '' }}
                        >
                            3 Papers Per Day
                        </option>

                    </select>


                    @error('papers_per_day')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror


                    <div class="form-text">

                        The selected number applies independently to
                        every class. A class stops receiving papers after
                        all of its subjects are completed.

                    </div>

                </div>


                {{-- Date Information --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Automatic Exam Period
                    </label>

                    <div class="border rounded-3 bg-light p-3">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Start Date
                            </span>

                            <strong>

                                @if($exam->start_date)

                                    {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}

                                @else

                                    Not configured

                                @endif

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                End Date
                            </span>

                            <strong>

                                @if($exam->end_date)

                                    {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}

                                @else

                                    Not configured

                                @endif

                            </strong>

                        </div>

                    </div>

                    <div class="form-text">

                        The system automatically uses available weekdays
                        between these dates.

                    </div>

                </div>


                {{-- Replace Existing --}}
                <div class="col-12">

                    <div class="border rounded-3 p-3">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="replace_existing"
                                value="1"
                                id="replace_existing"
                                {{ old('replace_existing') ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="replace_existing"
                            >
                                Replace Existing Timetable
                            </label>

                        </div>


                        <div class="small text-muted mt-2 ms-4">

                            If enabled, the existing timetable for this
                            examination will be deleted and regenerated
                            from the beginning.

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                IMPORTANT AUTOMATIC GENERATION NOTICE
            ====================================================== --}}
            <div class="alert alert-warning border-0 mt-4 mb-0">

                <div class="d-flex gap-3">

                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>

                    <div>

                        <div class="fw-bold mb-1">
                            Before generating
                        </div>

                        <ul class="mb-0 ps-3 small">

                            <li>
                                Subjects are scheduled separately for
                                each class.
                            </li>

                            <li>
                                Saturday, Sunday and configured holidays
                                are skipped.
                            </li>

                            <li>
                                No teacher is assigned automatically.
                                Teachers can be assigned later from the
                                timetable Edit page.
                            </li>

                            <li>
                                The system will stop without saving if
                                every subject cannot fit within the
                                selected examination period.
                            </li>

                            <li>
                                Existing timetable data is changed only
                                when <strong>Replace Existing Timetable</strong>
                                is selected.
                            </li>

                        </ul>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                ACTION BUTTONS
            ====================================================== --}}
            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                <a
                    href="{{ route('admin.exam-schedules.index', $exam) }}"
                    class="btn btn-light border"
                >
                    <i class="bi bi-x-lg me-1"></i>
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary px-4"
                    id="generateButton"
                >

                    <i class="bi bi-magic me-1"></i>

                    Generate Timetable

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    HOW AUTOMATIC GENERATION WORKS
========================================================== --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="mb-0 fw-bold">

            <i class="bi bi-diagram-3 me-2 text-primary"></i>

            How Automatic Scheduling Works

        </h5>

    </div>


    <div class="card-body">

        <div class="row g-4">

            {{-- Step 1 --}}
            <div class="col-md-6 col-lg-3">

                <div class="text-center">

                    <div
                        class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 52px; height: 52px;"
                    >

                        <strong>1</strong>

                    </div>

                    <h6 class="fw-bold">
                        Exam Dates
                    </h6>

                    <p class="small text-muted mb-0">
                        The system uses the configured start and end
                        dates automatically.
                    </p>

                </div>

            </div>


            {{-- Step 2 --}}
            <div class="col-md-6 col-lg-3">

                <div class="text-center">

                    <div
                        class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 52px; height: 52px;"
                    >

                        <strong>2</strong>

                    </div>

                    <h6 class="fw-bold">
                        Automatic Time Slots
                    </h6>

                    <p class="small text-muted mb-0">
                        1, 2 or 3 paper slots are created automatically
                        according to your selection.
                    </p>

                </div>

            </div>


            {{-- Step 3 --}}
            <div class="col-md-6 col-lg-3">

                <div class="text-center">

                    <div
                        class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 52px; height: 52px;"
                    >

                        <strong>3</strong>

                    </div>

                    <h6 class="fw-bold">
                        Class-Wise Subjects
                    </h6>

                    <p class="small text-muted mb-0">
                        Every class moves independently through its own
                        subject list.
                    </p>

                </div>

            </div>


            {{-- Step 4 --}}
            <div class="col-md-6 col-lg-3">

                <div class="text-center">

                    <div
                        class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 52px; height: 52px;"
                    >

                        <strong>4</strong>

                    </div>

                    <h6 class="fw-bold">
                        Complete Timetable
                    </h6>

                    <p class="small text-muted mb-0">
                        The timetable is saved only after all subjects
                        are successfully scheduled.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>
```

</div>

{{-- =========================================================
JAVASCRIPT
========================================================== --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('generateTimetableForm');

    const button = document.getElementById('generateButton');

    const papersPerDay = document.getElementById('papers_per_day');

    const replaceExisting = document.getElementById('replace_existing');


    /*
    |--------------------------------------------------------------------------
    | Submit Protection
    |--------------------------------------------------------------------------
    */

    if (form && button) {

        form.addEventListener('submit', function (event) {

            /*
            |--------------------------------------------------------------------------
            | Validate Papers Per Day
            |--------------------------------------------------------------------------
            */

            if (
                !papersPerDay ||
                !papersPerDay.value
            ) {

                event.preventDefault();

                papersPerDay.focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Confirmation When Replacing Existing Timetable
            |--------------------------------------------------------------------------
            */

            if (
                replaceExisting &&
                replaceExisting.checked
            ) {

                const confirmed = confirm(
                    'Are you sure you want to replace the existing timetable? All existing timetable entries for this examination will be deleted and regenerated.'
                );

                if (!confirmed) {

                    event.preventDefault();

                    return;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submit
            |--------------------------------------------------------------------------
            */

            button.disabled = true;

            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" role="status"></span>' +
                'Generating Timetable...';

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Papers Per Day Visual Information
    |--------------------------------------------------------------------------
    */

    if (papersPerDay) {

        papersPerDay.addEventListener('change', function () {

            const value = this.value;

            if (!value) {
                return;
            }

            /*
            | No AJAX is required.
            | Time slots are managed by the controller.
            */

        });

    }

});

</script>

@endpush

@endsection
