@extends('layouts.app')

@section('title', 'Exam Timetable')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-calendar3 me-2"></i>
                Exam Timetable
            </h4>

            <div class="text-muted">
                {{ $exam->exam_name }}
                @if($exam->exam_type)
                    <span class="mx-1">•</span>
                    {{ $exam->exam_type }}
                @endif

                @if($exam->academic_year)
                    <span class="mx-1">•</span>
                    {{ $exam->academic_year }}
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

            <a href="{{ route('admin.exam-schedules.generate', $exam) }}"
               class="btn btn-primary">
                <i class="bi bi-magic me-1"></i>
                Generate Timetable
            </a>

            <a href="{{ route('admin.exam-schedules.print', array_merge(
                    ['exam' => $exam->id],
                    request()->only(['class_id', 'exam_date'])
                )) }}"
               target="_blank"
               class="btn btn-outline-dark">
                <i class="bi bi-printer me-1"></i>
                Print
            </a>

            <a href="{{ route('admin.exam-schedules.pdf', array_merge(
                    ['exam' => $exam->id],
                    request()->only(['class_id', 'exam_date'])
                )) }}"
               class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                PDF
            </a>

        </div>
    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
         ERRORS
    ========================================================== --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please fix the following:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
         FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.exam-schedules.index', $exam) }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <select name="class_id"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->class_name }}
                                    @if($class->section)
                                        - {{ $class->section }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            Date
                        </label>

                        <input type="date"
                               name="exam_date"
                               class="form-control"
                               value="{{ request('exam_date') }}">

                    </div>


                    <div class="col-md-2 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1">
                            <i class="bi bi-filter me-1"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- =========================================================
         TIMETABLE
    ========================================================== --}}
    @php

        /*
        |--------------------------------------------------------------------------
        | Group timetable by date
        |--------------------------------------------------------------------------
        */

        $groupedByDate = $schedules->groupBy(function ($schedule) {
            return \Illuminate\Support\Carbon::parse(
                $schedule->exam_date
            )->format('Y-m-d');
        });

    @endphp


    @forelse($groupedByDate as $date => $dateSchedules)

        <div class="card border-0 shadow-sm mb-4 timetable-card">

            {{-- DATE HEADER --}}
            <div class="card-header bg-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <div class="date-icon me-3">
                        <i class="bi bi-calendar-event"></i>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-0">
                            {{ \Illuminate\Support\Carbon::parse($date)->format('d F Y') }}
                        </h5>

                        <small class="text-muted">
                            {{ \Illuminate\Support\Carbon::parse($date)->format('l') }}
                        </small>
                    </div>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table timetable-table mb-0">

                    <thead>

                        <tr>
                            <th style="width: 150px;">
                                Time
                            </th>

                            <th style="width: 120px;">
                                Class
                            </th>

                            <th>
                                Subject
                            </th>

                            <th style="width: 90px;">
                                Marks
                            </th>

                            <th style="width: 220px;">
                                Teacher
                            </th>

                            <th style="width: 70px;"
                                class="text-center">
                                Actions
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Group by session/time
                        |--------------------------------------------------------------------------
                        */

                        $groupedByTime = $dateSchedules->groupBy(function ($schedule) {

                            return
                                \Illuminate\Support\Carbon::parse(
                                    $schedule->start_time
                                )->format('H:i')
                                . '|'
                                .
                                \Illuminate\Support\Carbon::parse(
                                    $schedule->end_time
                                )->format('H:i');

                        });

                    @endphp


                    @foreach($groupedByTime as $time => $timeSchedules)

                        @php
                            [$startTime, $endTime] = explode('|', $time);
                        @endphp


                        @foreach($timeSchedules as $index => $schedule)

                            <tr>

                                {{-- TIME --}}
                                <td class="time-cell">

                                    @if($index === 0)

                                        <span class="fw-semibold">
                                            {{ \Illuminate\Support\Carbon::createFromFormat('H:i', $startTime)->format('H:i') }}
                                            –
                                            {{ \Illuminate\Support\Carbon::createFromFormat('H:i', $endTime)->format('H:i') }}
                                        </span>

                                    @endif

                                </td>


                                {{-- CLASS --}}
                                <td>

                                    <span class="class-badge">

                                        {{ optional($schedule->schoolClass)->class_name }}

                                        @if(optional($schedule->schoolClass)->section)
                                            -{{ optional($schedule->schoolClass)->section }}
                                        @endif

                                    </span>

                                </td>


                                {{-- SUBJECT --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ optional(optional($schedule->examSubject)->subject)->subject_name
                                            ?? '—'
                                        }}

                                    </div>

                                </td>


                                {{-- MARKS --}}
                                <td>

                                    <span class="marks-badge">
                                        {{ $schedule->maximum_marks }}
                                    </span>

                                </td>


                                {{-- TEACHER --}}
                                <td>

                                    @if($schedule->teacher)

                                        <div class="d-flex align-items-center">

                                            <div class="teacher-icon me-2">
                                                <i class="bi bi-person"></i>
                                            </div>

                                            <span>
                                                {{ trim(
                                                    $schedule->teacher->first_name . ' ' .
                                                    $schedule->teacher->last_name
                                                ) }}
                                            </span>

                                        </div>

                                    @else

                                        <span class="text-danger">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Not Assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}
                                <td class="text-center">

                                    <div class="dropdown">

                                        <button class="btn btn-sm btn-light action-btn"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">

                                            <i class="bi bi-three-dots-vertical"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

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

                                                <form method="POST"
                                                      action="{{ route(
                                                            'admin.exam-schedules.destroy',
                                                            [
                                                                'exam' => $exam->id,
                                                                'schedule' => $schedule->id
                                                            ]
                                                      ) }}">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this timetable entry?')">

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


                        {{-- SESSION SEPARATOR --}}

                        @if(!$loop->last)

                            <tr class="session-separator">
                                <td colspan="6"></td>
                            </tr>

                        @endif

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @empty

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div class="empty-icon mb-3">
                    <i class="bi bi-calendar-x"></i>
                </div>

                <h5 class="fw-semibold">
                    No Timetable Found
                </h5>

                <p class="text-muted mb-4">
                    No examination timetable has been generated for the selected filters.
                </p>

                <a href="{{ route('admin.exam-schedules.generate', $exam) }}"
                   class="btn btn-primary">

                    <i class="bi bi-magic me-1"></i>
                    Generate Timetable

                </a>

            </div>

        </div>

    @endforelse

</div>


{{-- =============================================================
     STYLES
============================================================== --}}
<style>

    .timetable-card {
        border-radius: 12px;
        overflow: hidden;
    }

    .timetable-table {
        min-width: 850px;
    }

    .timetable-table thead th {
        background: #f8f9fa;
        border-top: 1px solid #edf0f2;
        border-bottom: 1px solid #e5e7eb;
        color: #495057;
        font-size: 13px;
        font-weight: 600;
        padding: 13px 16px;
        white-space: nowrap;
    }

    .timetable-table tbody td {
        padding: 13px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f1f3;
    }

    .timetable-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .time-cell {
        color: #495057;
        white-space: nowrap;
    }

    .class-badge {
        display: inline-block;
        background: #eef5ff;
        color: #1677f0;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 13px;
    }

    .marks-badge {
        display: inline-block;
        min-width: 42px;
        text-align: center;
        background: #f1f3f5;
        padding: 5px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .teacher-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #495057;
        flex-shrink: 0;
    }

    .date-icon {
        width: 42px;
        height: 42px;
        border-radius: 9px;
        background: #eef5ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 7px;
        border: 1px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background: #f1f3f5;
    }

    .session-separator td {
        padding: 0 !important;
        height: 6px;
        background: #f8f9fa;
        border: 0 !important;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        margin: auto;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        color: #6c757d;
    }

    @media (max-width: 768px) {

        .timetable-table {
            min-width: 780px;
        }

        .timetable-table tbody td,
        .timetable-table thead th {
            padding: 10px 12px;
        }

    }

</style>

@endsection