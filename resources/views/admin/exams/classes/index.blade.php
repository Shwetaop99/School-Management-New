@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-mortarboard me-2"></i>
                Exam Classes
            </h4>

            <div class="text-muted">
                {{ $exam->exam_name }}
                —
                {{ $exam->academic_year }}
            </div>
        </div>

        <a href="{{ route('admin.exams.show', $exam) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Exam
        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1">
                        Select Classes
                    </h5>

                    <small class="text-muted">
                        Classes are automatically loaded from the Classes module.
                    </small>
                </div>

                <div>
                    <span class="badge bg-primary">
                        {{ $classes->count() }} Available
                    </span>
                </div>

            </div>

        </div>


        <form method="POST"
              action="{{ route('admin.exam-classes.store', $exam) }}">

            @csrf

            <div class="card-body">

                @if($classes->count())

                    {{-- Select All --}}
                    <div class="mb-3">

                        <div class="form-check">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="selectAll"
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="selectAll"
                            >
                                Select All Classes
                            </label>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th style="width: 60px;">
                                        Select
                                    </th>

                                    <th>
                                        Class
                                    </th>

                                    <th>
                                        Section
                                    </th>

                                    <th>
                                        Academic Year
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($classes as $class)

                                    <tr>

                                        <td>

                                            <div class="form-check">

                                                <input
                                                    type="checkbox"
                                                    name="class_ids[]"
                                                    value="{{ $class->id }}"
                                                    class="form-check-input class-checkbox"
                                                    {{ in_array($class->id, $selectedClassIds) ? 'checked' : '' }}
                                                >

                                            </div>

                                        </td>

                                        <td>
                                            <strong>
                                                {{ $class->class_name }}
                                            </strong>
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $class->section }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $class->academic_year }}
                                        </td>

                                        <td>

                                            @if($class->status)

                                                <span class="badge bg-success">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="bi bi-mortarboard fs-1 text-muted"></i>

                        <h5 class="mt-3">
                            No Classes Available
                        </h5>

                        <p class="text-muted mb-0">
                            No active classes were found for
                            {{ $exam->academic_year }}.
                        </p>

                    </div>

                @endif

            </div>


            @if($classes->count())

                <div class="card-footer bg-white d-flex justify-content-end">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Save & Continue
                    </button>

                </div>

            @endif

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const selectAll = document.getElementById('selectAll');

    const checkboxes = document.querySelectorAll(
        '.class-checkbox'
    );

    if (!selectAll) {
        return;
    }

    function updateSelectAllState() {

        const checkedCount =
            document.querySelectorAll(
                '.class-checkbox:checked'
            ).length;

        selectAll.checked =
            checkboxes.length > 0 &&
            checkedCount === checkboxes.length;
    }

    selectAll.addEventListener('change', function () {

        checkboxes.forEach(function (checkbox) {

            checkbox.checked = selectAll.checked;

        });

    });

    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            updateSelectAllState
        );

    });

    updateSelectAllState();

});

</script>

@endsection