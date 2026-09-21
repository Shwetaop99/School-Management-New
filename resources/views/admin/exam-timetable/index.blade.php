@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            Exam Timetable
        </h3>

        <div class="text-muted">
            {{ $exam->exam_name }}
            <span class="mx-1">•</span>
            {{ $exam->academic_year }}
        </div>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route(
            'admin.exam-schedules.create',
            ['exam' => $exam->id]
        ) }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>
            Create Schedule
        </a>

        <a href="{{ route(
            'admin.exam-timetable.print',
            ['exam' => $exam->id]
        ) }}"
           target="_blank"
           class="btn btn-success">

            <i class="bi bi-printer me-1"></i>
            Print All Timetables
        </a>

    </div>

</div>


{{-- =========================================================
    EXAM INFORMATION
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-3">
                <div class="text-muted small">
                    Academic Year
                </div>

                <div class="fw-semibold">
                    {{ $exam->academic_year }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">
                    Exam Name
                </div>

                <div class="fw-semibold">
                    {{ $exam->exam_name }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">
                    Exam Type
                </div>

                <div class="fw-semibold">
                    {{ $exam->exam_type }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">
                    Exam Period
                </div>

                <div class="fw-semibold">

                    @if($exam->start_date)
                        {{ $exam->start_date->format('d M Y') }}
                    @else
                        -
                    @endif

                    <span class="mx-1">to</span>

                    @if($exam->end_date)
                        {{ $exam->end_date->format('d M Y') }}
                    @else
                        -
                    @endif

                </div>
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    FILTERS
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-0 py-3">

        <h5 class="mb-0 fw-bold">
            <i class="bi bi-funnel me-2 text-primary"></i>
            Filter Timetable
        </h5>

    </div>

    <div class="card-body">

        <form method="GET"
              action="{{ route(
                  'admin.exam-timetable.index',
                  ['exam' => $exam->id]
              ) }}">

            <div class="row g-3 align-items-end">

                {{-- CLASS --}}
                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Class
                    </label>

                    <select name="exam_class_id"
                            id="exam_class_id"
                            class="form-select">

                        <option value="">
                            All Classes
                        </option>

                        @foreach($examClasses as $examClass)

                            <option value="{{ $examClass->id }}"
                                {{ $selectedClass &&
                                   $selectedClass->id == $examClass->id
                                   ? 'selected'
                                   : '' }}>

                                {{ $examClass->class_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- SECTION --}}
                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Section
                    </label>

                    <select name="exam_class_section_id"
                            id="exam_class_section_id"
                            class="form-select"
                            data-selected-section="{{ $selectedSection?->id ?? '' }}"
                            {{ $selectedClass ? '' : 'disabled' }}>

                        <option value="">
                            All Sections
                        </option>

                        @foreach($examClasses as $examClass)

                            @foreach($examClass->sections as $section)

                                <option value="{{ $section->id }}"
                                        data-class-id="{{ $examClass->id }}"
                                    {{ $selectedSection &&
                                       $selectedSection->id == $section->id
                                       ? 'selected'
                                       : '' }}>

                                    {{ $section->section_name }}

                                </option>

                            @endforeach

                        @endforeach

                    </select>

                </div>


                {{-- BUTTONS --}}
                <div class="col-md-2">

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="bi bi-search me-1"></i>
                            Generate
                        </button>

                        <a href="{{ route(
                            'admin.exam-timetable.index',
                            ['exam' => $exam->id]
                        ) }}"
                           class="btn btn-light border"
                           title="Reset">

                            <i class="bi bi-arrow-counterclockwise"></i>

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    FILTER STATUS
========================================================== --}}
@if($selectedClass)

    <div class="alert alert-info border-0 shadow-sm mb-4">

        <div class="d-flex align-items-center">

            <i class="bi bi-info-circle-fill me-2"></i>

            <div>

                Showing timetable for
                <strong>
                    Class {{ $selectedClass->class_name }}
                </strong>

                @if($selectedSection)
                    -
                    <strong>
                        Section {{ $selectedSection->section_name }}
                    </strong>
                @endif

            </div>

        </div>

    </div>

@else

    <div class="alert alert-primary border-0 shadow-sm mb-4">

        <div class="d-flex align-items-center">

            <i class="bi bi-calendar-week me-2"></i>

            <div>
                Showing timetable for
                <strong>all active classes</strong>.
            </div>

        </div>

    </div>

@endif


{{-- =========================================================
    TIMETABLES
========================================================== --}}
@if($examClasses->count())

    @foreach($examClasses as $examClass)

        @php
            $classSchedules = $schedules->where(
                'exam_class_id',
                $examClass->id
            );
        @endphp

        {{-- When a class filter is selected, only show that class --}}
        @if(!$selectedClass || $selectedClass->id == $examClass->id)

            <div class="card border-0 shadow-sm mb-4">

                {{-- CLASS HEADER --}}
                <div class="card-header bg-white py-3">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                        <div>

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-mortarboard-fill text-primary me-2"></i>

                                Class {{ $examClass->class_name }}

                            </h5>

                            @if($selectedSection)

                                <div class="text-muted small">
                                    Section {{ $selectedSection->section_name }}
                                </div>

                            @else

                                <div class="text-muted small">
                                    All Sections
                                </div>

                            @endif

                        </div>

                        <div>

                            <span class="badge bg-primary rounded-pill">

                                {{ $classSchedules->count() }}
                                {{ $classSchedules->count() == 1 ? 'Schedule' : 'Schedules' }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- CLASS TIMETABLE --}}
                <div class="card-body p-0">

                    @if($classSchedules->count())

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-4">
                                            #
                                        </th>

                                        <th>
                                            Date
                                        </th>

                                        <th>
                                            Day
                                        </th>

                                        <th>
                                            Section
                                        </th>

                                        <th>
                                            Subject
                                        </th>

                                        <th>
                                            Time
                                        </th>

                                        <th>
                                            Marks
                                        </th>

                                        <th>
                                            Room
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th class="text-center">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($classSchedules as $schedule)

                                        <tr>

                                            {{-- NUMBER --}}
                                            <td class="ps-4 fw-semibold">
                                                {{ $loop->iteration }}
                                            </td>


                                            {{-- DATE --}}
                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $schedule->exam_date
                                                        ? $schedule->exam_date->format('d M Y')
                                                        : '-'
                                                    }}

                                                </div>

                                            </td>


                                            {{-- DAY --}}
                                            <td>

                                                @if($schedule->exam_date)

                                                    <span class="badge bg-light text-dark border">

                                                        {{ $schedule->exam_date->format('l') }}

                                                    </span>

                                                @else

                                                    -

                                                @endif

                                            </td>


                                            {{-- SECTION --}}
                                            <td>

                                                @if($schedule->examClassSection)

                                                    <span class="badge bg-primary-subtle text-primary">

                                                        {{ $schedule->examClassSection->section_name }}

                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        All Sections
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- SUBJECT --}}
                                            <td>

                                                @if($schedule->subject_id)

                                                    <span class="fw-semibold">
                                                        Subject #{{ $schedule->subject_id }}
                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        Not Assigned
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- TIME --}}
                                            <td>

                                                <div class="fw-semibold">

                                                    {{ \Carbon\Carbon::parse(
                                                        $schedule->start_time
                                                    )->format('h:i A') }}

                                                    <span class="text-muted mx-1">
                                                        -
                                                    </span>

                                                    {{ \Carbon\Carbon::parse(
                                                        $schedule->end_time
                                                    )->format('h:i A') }}

                                                </div>

                                            </td>


                                            {{-- MARKS --}}
                                            <td>

                                                <div>
                                                    Max:
                                                    <strong>
                                                        {{ $schedule->max_marks }}
                                                    </strong>
                                                </div>

                                                <div class="text-muted small">
                                                    Pass:
                                                    {{ $schedule->pass_marks }}
                                                </div>

                                            </td>


                                            {{-- ROOM --}}
                                            <td>

                                                @if($schedule->room_no)

                                                    <span>
                                                        <i class="bi bi-door-open me-1"></i>
                                                        {{ $schedule->room_no }}
                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- STATUS --}}
                                            <td>

                                                @if($schedule->status === 'scheduled')

                                                    <span class="badge bg-success">
                                                        Scheduled
                                                    </span>

                                                @elseif($schedule->status === 'completed')

                                                    <span class="badge bg-primary">
                                                        Completed
                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">
                                                        Cancelled
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- ACTION --}}
                                            <td class="text-center">

                                                <div class="dropdown">

                                                    <button class="btn btn-sm btn-light border"
                                                            type="button"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">

                                                        <i class="bi bi-three-dots-vertical"></i>

                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end">

                                                        {{-- EDIT --}}
                                                        <li>

                                                            <a class="dropdown-item"
                                                               href="{{ route(
                                                                   'admin.exam-schedules.edit',
                                                                   [
                                                                       'exam' => $exam->id,
                                                                       'schedule' => $schedule->id,
                                                                   ]
                                                               ) }}">

                                                                <i class="bi bi-pencil-square text-primary me-2"></i>

                                                                Edit

                                                            </a>

                                                        </li>


                                                        {{-- PRINT --}}
                                                        <li>

                                                            <a class="dropdown-item"
                                                               href="{{ route(
                                                                   'admin.exam-schedules.print',
                                                                   [
                                                                       'exam' => $exam->id,
                                                                       'schedule' => $schedule->id,
                                                                   ]
                                                               ) }}"
                                                               target="_blank">

                                                                <i class="bi bi-printer text-success me-2"></i>

                                                                Print

                                                            </a>

                                                        </li>


                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>


                                                        {{-- DELETE --}}
                                                        <li>

                                                            <form method="POST"
                                                                  action="{{ route(
                                                                      'admin.exam-schedules.destroy',
                                                                      [
                                                                          'exam' => $exam->id,
                                                                          'schedule' => $schedule->id,
                                                                      ]
                                                                  ) }}"
                                                                  onsubmit="return confirm('Are you sure you want to delete this schedule?');">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                        class="dropdown-item text-danger">

                                                                    <i class="bi bi-trash me-2"></i>

                                                                    Delete

                                                                </button>

                                                            </form>

                                                        </li>

                                                    </ul>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        {{-- NO SCHEDULE FOR CLASS --}}
                        <div class="text-center py-5">

                            <div class="mb-3">

                                <i class="bi bi-calendar-x text-muted"
                                   style="font-size: 3rem;"></i>

                            </div>

                            <h6 class="fw-bold">
                                No Schedule Created
                            </h6>

                            <p class="text-muted mb-3">

                                No exam schedule has been created
                                for Class {{ $examClass->class_name }}.

                            </p>

                            <a href="{{ route(
                                'admin.exam-schedules.create',
                                ['exam' => $exam->id]
                            ) }}"
                               class="btn btn-sm btn-primary">

                                <i class="bi bi-plus-circle me-1"></i>

                                Create Schedule

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        @endif

    @endforeach

@else

    {{-- =====================================================
        NO EXAM CLASSES
    ====================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <i class="bi bi-mortarboard text-muted"
               style="font-size: 4rem;"></i>

            <h5 class="fw-bold mt-3">
                No Exam Classes Found
            </h5>

            <p class="text-muted">
                Please add classes to this examination first.
            </p>

            <a href="{{ route(
                'admin.exam-classes.index',
                ['exam' => $exam->id]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>

                Manage Exam Classes

            </a>

        </div>

    </div>

@endif


</div>

{{-- =============================================================
SECTION FILTER JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('exam_class_id');

    const sectionSelect = document.getElementById(
        'exam_class_section_id'
    );

    if (!classSelect || !sectionSelect) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Store all section options
    |--------------------------------------------------------------------------
    */

    const allSections = [];

    Array.from(sectionSelect.options).forEach(function (option) {

        if (option.value !== '') {

            allSections.push({
                value: option.value,
                text: option.text,
                classId: option.getAttribute(
                    'data-class-id'
                )
            });

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Load Sections
    |--------------------------------------------------------------------------
    */

    function loadSections(classId, selectedSectionId) {

        sectionSelect.innerHTML = '';

        const allOption = document.createElement('option');

        allOption.value = '';
        allOption.textContent = 'All Sections';

        sectionSelect.appendChild(allOption);


        if (!classId) {

            sectionSelect.disabled = true;

            sectionSelect.value = '';

            return;
        }


        sectionSelect.disabled = false;


        allSections.forEach(function (section) {

            if (section.classId === classId) {

                const option = document.createElement('option');

                option.value = section.value;
                option.textContent = section.text;

                if (
                    selectedSectionId &&
                    section.value === selectedSectionId
                ) {
                    option.selected = true;
                }

                sectionSelect.appendChild(option);

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    const initialClassId = classSelect.value;

    const initialSectionId =
        sectionSelect.getAttribute(
            'data-selected-section'
        );


    loadSections(
        initialClassId,
        initialSectionId
    );


    /*
    |--------------------------------------------------------------------------
    | Class Change
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener('change', function () {

        loadSections(
            this.value,
            ''
        );

    });

});

</script>

@endsection
