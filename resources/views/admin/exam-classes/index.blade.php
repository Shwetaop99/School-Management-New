
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                Exam Classes
            </h3>

            <p class="text-muted mb-0">
                Manage classes for
                <strong>{{ $exam->exam_name }}</strong>
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.exams.show', $exam->id) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Exam
            </a>

            <a href="{{ route('admin.exam-classes.create', $exam->id) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Class
            </a>

        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- EXAM INFORMATION --}}
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


    {{-- CLASSES TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-collection-fill text-primary me-2"></i>
                    Assigned Classes
                </h5>

                <span class="badge bg-primary">
                    {{ $examClasses->count() }} Classes
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($examClasses->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th class="ps-4" style="width:80px;">
                                    #
                                </th>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Order
                                </th>

                                <th class="text-end pe-4">
                                    Actions
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($examClasses as $index => $examClass)

                                <tr>

                                    <td class="ps-4">
                                        {{ $index + 1 }}
                                    </td>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="bg-primary bg-opacity-10
                                                        rounded-circle
                                                        d-flex
                                                        align-items-center
                                                        justify-content-center
                                                        me-3"
                                                 style="width:40px;height:40px;">

                                                <i class="bi bi-mortarboard-fill text-primary"></i>

                                            </div>

                                            <div>
                                                <div class="fw-semibold">
                                                    Class {{ $examClass->class_name }}
                                                </div>

                                                <small class="text-muted">
                                                    Exam Class
                                                </small>
                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        @if($examClass->status === 'active')

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
                                        {{ $examClass->sort_order }}
                                    </td>


                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-light border"
                                                type="button"
                                                data-bs-toggle="dropdown">

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route(
                                                           'admin.exam-classes.edit',
                                                           [
                                                               'exam' => $exam->id,
                                                               'examClass' => $examClass->id
                                                           ]
                                                       ) }}">

                                                        <i class="bi bi-pencil-square me-2"></i>
                                                        Edit
                                                    </a>
                                                </li>


                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                <li>

                                                    <form
                                                        action="{{ route(
                                                            'admin.exam-classes.destroy',
                                                            [
                                                                'exam' => $exam->id,
                                                                'examClass' => $examClass->id
                                                            ]
                                                        ) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to remove this class from the exam?');">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger">

                                                            <i class="bi bi-trash me-2"></i>
                                                            Remove

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

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-mortarboard text-muted"
                           style="font-size:55px;"></i>

                    </div>

                    <h5 class="fw-semibold">
                        No Classes Assigned
                    </h5>

                    <p class="text-muted">
                        Add classes to this examination before creating the exam schedule.
                    </p>

                    <a href="{{ route('admin.exam-classes.create', $exam->id) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Class

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
