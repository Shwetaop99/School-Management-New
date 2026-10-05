
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-journal-text me-2"></i>
                Exam Management
            </h4>

            <p class="text-muted mb-0">
                Manage examinations, classes, subjects, sessions and timetables.
            </p>
        </div>

        <a href="{{ route('admin.exams.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>
            Create Exam

        </a>

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

            <div class="fw-semibold mb-2">
                Please correct the following:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Exam List --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">
                    Exams
                </h5>

                <span class="badge bg-light text-dark border">
                    {{ $exams->count() }}
                    {{ Str::plural('Exam', $exams->count()) }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($exams->isEmpty())

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-journal-x fs-1 text-muted"></i>
                    </div>

                    <h5 class="fw-semibold">
                        No Exams Found
                    </h5>

                    <p class="text-muted mb-3">
                        Create your first exam to start the exam setup.
                    </p>

                    <a href="{{ route('admin.exams.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>
                        Create Exam

                    </a>

                </div>

            @else

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    #
                                </th>

                                <th>
                                    Exam
                                </th>

                                <th>
                                    Academic Year
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Exam Period
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($exams as $exam)

                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-4">

                                        {{ $loop->iteration }}

                                    </td>


                                    {{-- Exam --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $exam->exam_name }}
                                        </div>

                                        <small class="text-muted">
                                            ID: {{ $exam->id }}
                                        </small>

                                    </td>


                                    {{-- Academic Year --}}
                                    <td>

                                        {{ $exam->academic_year }}

                                    </td>


                                    {{-- Exam Type --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $exam->exam_type }}

                                        </span>

                                    </td>


                                    {{-- Exam Period --}}
                                    <td>

                                        <div>
                                            {{ $exam->start_date?->format('d M Y') ?? '-' }}
                                        </div>

                                        <small class="text-muted">

                                            to

                                            {{ $exam->end_date?->format('d M Y') ?? 'Not set' }}

                                        </small>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @php

                                            $statusClass = match($exam->status) {

                                                'draft' =>
                                                    'bg-secondary',

                                                'scheduled' =>
                                                    'bg-primary',

                                                'completed' =>
                                                    'bg-success',

                                                'cancelled' =>
                                                    'bg-danger',

                                                default =>
                                                    'bg-secondary',

                                            };

                                        @endphp


                                        <span class="badge {{ $statusClass }}">

                                            {{ ucfirst($exam->status) }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-light border"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">


                                                {{-- View --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.exams.show', $exam) }}">

                                                        <i class="bi bi-eye me-2"></i>

                                                        View Exam

                                                    </a>

                                                </li>


                                                {{-- Edit --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.exams.edit', $exam) }}">

                                                        <i class="bi bi-pencil me-2"></i>

                                                        Edit Exam

                                                    </a>

                                                </li>


                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                {{-- Delete --}}
                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.exams.destroy', $exam) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this exam? All related exam setup and timetable data will also be removed.');">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger">

                                                            <i class="bi bi-trash me-2"></i>

                                                            Delete Exam

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

            @endif

        </div>

    </div>

</div>

@endsection

