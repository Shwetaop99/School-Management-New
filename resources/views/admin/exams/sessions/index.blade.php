
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-clock-history me-2"></i>
                Exam Sessions
            </h4>

            <div class="text-muted">
                {{ $exam->exam_name }}
                • {{ $exam->academic_year }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.exams.show', $exam) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Exam
            </a>

            <a href="{{ route('admin.exam-sessions.create', $exam) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Session
            </a>

        </div>

    </div>


    {{-- Success Message --}}
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


    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Information --}}
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>

        Sessions define the time periods in which exams can be scheduled.
        You can create multiple sessions such as Morning and Afternoon.
    </div>


    {{-- Sessions --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <strong>
                Configured Sessions
            </strong>
        </div>

        <div class="card-body p-0">

            @if($sessions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="70">#</th>
                                <th>Session</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th width="180">Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($sessions as $session)

                                @php
                                    $start = \Carbon\Carbon::parse($session->start_time);
                                    $end = \Carbon\Carbon::parse($session->end_time);
                                    $duration = $start->diffInMinutes($end);
                                @endphp

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $session->session_name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $start->format('h:i A') }}
                                    </td>

                                    <td>
                                        {{ $end->format('h:i A') }}
                                    </td>

                                    <td>
                                        {{ $duration }} minutes
                                    </td>

                                    <td>

                                        @if($session->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route(
                                                'admin.exam-sessions.edit',
                                                [$exam, $session]
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary">

                                                <i class="bi bi-pencil"></i>
                                                Edit

                                            </a>


                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.exam-sessions.destroy',
                                                      [$exam, $session]
                                                  ) }}"
                                                  onsubmit="return confirm('Delete this session?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-clock-history fs-1 text-muted"></i>
                    </div>

                    <h5>
                        No Exam Sessions Configured
                    </h5>

                    <p class="text-muted mb-4">
                        Add at least one session before generating
                        the exam timetable.
                    </p>

                    <a href="{{ route(
                        'admin.exam-sessions.create',
                        $exam
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Session

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
