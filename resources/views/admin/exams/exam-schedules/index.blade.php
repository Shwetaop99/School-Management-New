@extends('layouts.app')

@section('title', 'Exam Timetable')

@section('content')

<div class="container-fluid py-4">

```
{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-calendar3 me-2"></i>
            Exam Timetable
        </h3>

        <p class="text-muted mb-0">
            {{ $exam->exam_name }}
            @if($exam->academic_year)
                • {{ $exam->academic_year }}
            @endif
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
           class="btn btn-primary">
            <i class="bi bi-magic me-1"></i>
            Generate Timetable
        </a>

        @if($timetables->count())
            <a href="{{ route('admin.exam-schedules.print', $exam) }}"
               target="_blank"
               class="btn btn-outline-secondary">
                <i class="bi bi-printer me-1"></i>
                Print
            </a>

            <a href="{{ route('admin.exam-schedules.pdf', $exam) }}"
               class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                PDF
            </a>
        @endif

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
                    Examination
                </div>

                <div class="fw-semibold">
                    {{ $exam->exam_name }}
                </div>
            </div>


            <div class="col-md-3">
                <div class="text-muted small">
                    Academic Year
                </div>

                <div class="fw-semibold">
                    {{ $exam->academic_year ?? '-' }}
                </div>
            </div>


            <div class="col-md-3">
                <div class="text-muted small">
                    Exam Type
                </div>

                <div class="fw-semibold">
                    {{ $exam->exam_type ?? '-' }}
                </div>
            </div>


            <div class="col-md-3">
                <div class="text-muted small">
                    Timetable Entries
                </div>

                <div class="fw-semibold">
                    {{ $timetables->count() }}
                </div>
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    EMPTY STATE
========================================================== --}}
@if($timetables->isEmpty())

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <div class="mb-3">
                <i class="bi bi-calendar-x"
                   style="font-size: 50px; color:#adb5bd;"></i>
            </div>

            <h5 class="fw-bold">
                No Exam Timetable Generated
            </h5>

            <p class="text-muted mb-4">
                The timetable for this examination has not been generated yet.
            </p>

            <a href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
               class="btn btn-primary">
                <i class="bi bi-magic me-1"></i>
                Generate Timetable
            </a>

        </div>

    </div>

@else

    {{-- =====================================================
        TIMETABLE
    ====================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-0">
                        Generated Examination Timetable
                    </h5>

                    <small class="text-muted">
                        Ordered by date, time and class
                    </small>
                </div>

                <span class="badge bg-primary">
                    {{ $timetables->count() }} Papers
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Time
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
                                Duration
                            </th>

                            <th>
                                Teacher
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($timetables as $index => $schedule)

                            <tr>

                                {{-- Number --}}
                                <td class="px-3">
                                    {{ $index + 1 }}
                                </td>


                                {{-- Date --}}
                                <td>

                                    @if($schedule->exam_date)

                                        <div class="fw-semibold">
                                            {{ $schedule->exam_date->format('d M Y') }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $schedule->exam_date->format('l') }}
                                        </small>

                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- Time --}}
                                <td>

                                    @if($schedule->start_time && $schedule->end_time)

                                        <div class="fw-semibold">

                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}

                                            -

                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}

                                        </div>

                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- Class --}}
                                <td>

                                    <span class="badge bg-primary">

                                        {{ $schedule->schoolClass->class_name ?? '-' }}

                                        @if(!empty($schedule->schoolClass->section))
                                            - {{ $schedule->schoolClass->section }}
                                        @endif

                                    </span>

                                </td>


                                {{-- Subject --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $schedule->examSubject->subject->subject_name ?? '-' }}

                                    </div>

                                    @if(!empty($schedule->examSubject->subject->subject_code))

                                        <small class="text-muted">

                                            {{ $schedule->examSubject->subject->subject_code }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Maximum Marks --}}
                                <td>

                                    {{ $schedule->maximum_marks ?? '-' }}

                                </td>


                                {{-- Duration --}}
                                <td>

                                    @if($schedule->duration_minutes)

                                        {{ $schedule->duration_minutes }} min

                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- Teacher --}}
                                <td>

                                    @if($schedule->teacher)

                                        {{ trim(
                                            ($schedule->teacher->first_name ?? '') .
                                            ' ' .
                                            ($schedule->teacher->last_name ?? '')
                                        ) }}

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($schedule->status)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="text-end px-3">

                                    <div class="dropdown">

                                        <button
                                            class="btn btn-sm btn-light border"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                            <i class="bi bi-three-dots-vertical"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route(
                                                       'admin.exam-schedules.edit',
                                                       [
                                                           'exam' => $exam->id,
                                                           'schedule' => $schedule->id
                                                       ]
                                                   ) }}">

                                                    <i class="bi bi-pencil me-2"></i>
                                                    Edit

                                                </a>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            <li>

                                                <form
                                                    action="{{ route(
                                                        'admin.exam-schedules.destroy',
                                                        [
                                                            'exam' => $exam->id,
                                                            'schedule' => $schedule->id
                                                        ]
                                                    ) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this timetable entry?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
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

        </div>

    </div>

@endif
```

</div>

@endsection
