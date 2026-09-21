
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route(
                    'admin.exams.show',
                    $exam->id
                ) }}"
                   class="text-decoration-none text-muted">

                    <i class="bi bi-arrow-left"></i>

                    Exam

                </a>

                <span class="text-muted">/</span>

                <span class="text-muted">
                    Schedule
                </span>

            </div>


            <h3 class="fw-bold mb-1">

                <i class="bi bi-calendar3 text-primary me-2"></i>

                Exam Schedule

            </h3>


            <p class="text-muted mb-0">

                {{ $exam->exam_name }}

                &nbsp;•&nbsp;

                Academic Year {{ $exam->academic_year }}

            </p>

        </div>


        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route(
                'admin.exam-schedules.create',
                $exam->id
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Schedule

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


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                Please fix the following:

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
        EXAM INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Examination
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_name }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Academic Year
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->academic_year }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Exam Type
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_type }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Start Date
                    </div>

                    <div class="fw-semibold">

                        {{ $exam->start_date
                            ? $exam->start_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        End Date
                    </div>

                    <div class="fw-semibold">

                        {{ $exam->end_date
                            ? $exam->end_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-1">

                    <div class="text-muted small">
                        Status
                    </div>

                    @if($exam->status === 'active')

                        <span class="badge bg-success">
                            Active
                        </span>

                    @elseif($exam->status === 'completed')

                        <span class="badge bg-primary">
                            Completed
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">

                <i class="bi bi-funnel text-primary me-2"></i>

                Filter Schedule

            </h5>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route(
                      'admin.exam-schedules.index',
                      $exam->id
                  ) }}">

                <div class="row g-3">

                    {{-- Class --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <select name="exam_class_id"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($examClasses as $examClass)

                                <option value="{{ $examClass->id }}"
                                    {{ request('exam_class_id') == $examClass->id
                                        ? 'selected'
                                        : '' }}>

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

                    </div>


                    {{-- Section --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Section
                        </label>

                        <select name="exam_class_section_id"
                                class="form-select">

                            <option value="">
                                All Sections
                            </option>

                            @foreach($examClasses as $examClass)

                                @foreach($examClass->sections as $section)

                                    <option value="{{ $section->id }}"
                                        {{ request('exam_class_section_id') == $section->id
                                            ? 'selected'
                                            : '' }}>

                                        @if(in_array(
                                            $examClass->class_name,
                                            ['Nursery', 'LKG', 'UKG']
                                        ))
                                            {{ $examClass->class_name }}
                                        @else
                                            Class {{ $examClass->class_name }}
                                        @endif

                                        - Section {{ $section->section_name }}

                                    </option>

                                @endforeach

                            @endforeach

                        </select>

                    </div>


                    {{-- Date --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Exam Date
                        </label>

                        <input type="date"
                               name="exam_date"
                               value="{{ request('exam_date') }}"
                               class="form-control">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="scheduled"
                                {{ request('status') === 'scheduled'
                                    ? 'selected'
                                    : '' }}>
                                Scheduled
                            </option>

                            <option value="completed"
                                {{ request('status') === 'completed'
                                    ? 'selected'
                                    : '' }}>
                                Completed
                            </option>

                            <option value="cancelled"
                                {{ request('status') === 'cancelled'
                                    ? 'selected'
                                    : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search me-1"></i>

                            Filter

                        </button>

                        <a href="{{ route(
                            'admin.exam-schedules.index',
                            $exam->id
                        ) }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-counterclockwise"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        SCHEDULE TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-calendar-week text-primary me-2"></i>

                        Examination Timetable

                    </h5>

                    <small class="text-muted">

                        {{ $schedules->count() }}

                        {{ $schedules->count() == 1
                            ? 'Schedule'
                            : 'Schedules' }}

                    </small>

                </div>


                <a href="{{ route(
                    'admin.exam-schedules.create',
                    $exam->id
                ) }}"
                   class="btn btn-sm btn-primary">

                    <i class="bi bi-plus-lg me-1"></i>

                    Add Schedule

                </a>

            </div>

        </div>


        <div class="card-body p-0">

            @if($schedules->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    #
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Class
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

                                <th class="text-end px-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($schedules as $index => $schedule)

                                <tr>

                                    <td class="px-4">
                                        {{ $index + 1 }}
                                    </td>


                                    {{-- DATE --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $schedule->exam_date
                                                ->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $schedule->exam_date
                                                ->format('l') }}

                                        </small>

                                    </td>


                                    {{-- CLASS --}}
                                    <td>

                                        @if($schedule->examClass)

                                            @if(in_array(
                                                $schedule->examClass->class_name,
                                                ['Nursery', 'LKG', 'UKG']
                                            ))

                                                {{ $schedule->examClass->class_name }}

                                            @else

                                                Class
                                                {{ $schedule->examClass->class_name }}

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- SECTION --}}
                                    <td>

                                        @if($schedule->examClassSection)

                                            <span class="badge bg-primary">

                                                Section
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

                                            <span class="text-muted">
                                                Subject #{{ $schedule->subject_id }}
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">

                                                <i class="bi bi-hourglass-split me-1"></i>

                                                Not Configured

                                            </span>

                                        @endif

                                    </td>


                                    {{-- TIME --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ \Carbon\Carbon::parse(
                                                $schedule->start_time
                                            )->format('h:i A') }}

                                        </div>

                                        <small class="text-muted">

                                            to

                                            {{ \Carbon\Carbon::parse(
                                                $schedule->end_time
                                            )->format('h:i A') }}

                                        </small>

                                    </td>


                                    {{-- MARKS --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $schedule->max_marks }}

                                        </div>

                                        <small class="text-muted">

                                            Pass:
                                            {{ $schedule->pass_marks }}

                                        </small>

                                    </td>


                                    {{-- ROOM --}}
                                    <td>

                                        {{ $schedule->room_no ?: '-' }}

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @if($schedule->status === 'scheduled')

                                            <span class="badge bg-primary">

                                                <i class="bi bi-calendar-check me-1"></i>

                                                Scheduled

                                            </span>

                                        @elseif($schedule->status === 'completed')

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Completed

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                <i class="bi bi-x-circle me-1"></i>

                                                Cancelled

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="text-end px-4">

                                        <div class="dropdown">

                                            <button class="btn btn-sm btn-light border"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                                {{-- Edit --}}
                                                <li>


<a href="{{ route(
    'admin.exam-schedules.edit',
    [
        'exam' => $exam->id,
        'schedule' => $schedule->id,
    ]
) }}"
   class="dropdown-item">

    <i class="bi bi-pencil-square text-primary me-2"></i>

    Edit

</a>
                       

                                                </li>


                                                <li>

                                                    <hr class="dropdown-divider">

                                                </li>


                                                {{-- Delete --}}
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

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-calendar-x text-muted"
                           style="font-size: 4rem;">
                        </i>

                    </div>


                    <h5 class="fw-bold">
                        No Exam Schedule Found
                    </h5>


                    <p class="text-muted mb-4">

                        No examination schedules have been added
                        for this examination yet.

                    </p>


                    <a href="{{ route(
                        'admin.exam-schedules.create',
                        $exam->id
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Create First Schedule

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
