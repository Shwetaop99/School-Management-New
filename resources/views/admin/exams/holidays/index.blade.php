
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-calendar-x me-2"></i>
                Exam Holidays
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

            <a href="{{ route('admin.exam-holidays.create', $exam) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Holiday
            </a>

        </div>

    </div>


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


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="alert alert-info">

        <i class="bi bi-info-circle me-2"></i>

        Holidays added here will automatically be skipped by the
        exam timetable generator.

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <strong>
                Non-working Days
            </strong>

        </div>


        <div class="card-body p-0">

            @if($holidays->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="70">#</th>
                                <th>Holiday Date</th>
                                <th>Day</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th width="180">Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($holidays as $holiday)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $holiday->holiday_date->format('d M Y') }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $holiday->holiday_date->format('l') }}
                                    </td>

                                    <td>
                                        {{ $holiday->reason ?: '—' }}
                                    </td>

                                    <td>

                                        @if($holiday->status)

                                            <span class="badge bg-danger">
                                                Non-working
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
                                                'admin.exam-holidays.edit',
                                                [$exam, $holiday]
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary">

                                                <i class="bi bi-pencil"></i>
                                                Edit

                                            </a>


                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.exam-holidays.destroy',
                                                      [$exam, $holiday]
                                                  ) }}"
                                                  onsubmit="return confirm('Delete this holiday?');">

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

                    <i class="bi bi-calendar-check fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Holidays Added
                    </h5>

                    <p class="text-muted">
                        You can add Sundays, public holidays,
                        school holidays or other non-working days.
                    </p>

                    <a href="{{ route(
                        'admin.exam-holidays.create',
                        $exam
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add Holiday

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

