@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-calendar2-week me-2"></i>
                Generate Exam Timetable
            </h4>

            <div class="text-muted">
                {{ $exam->exam_name }}
            </div>
        </div>

        <a href="{{ route('admin.exam-schedules.index', $exam) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Timetable
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please fix the following:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- EXAM INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">
                <i class="bi bi-info-circle me-2"></i>
                Exam Information
            </h6>

        </div>

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


                <div class="col-md-2">

                    <div class="text-muted small">
                        Exam Type
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_type }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Exam Period
                    </div>

                    <div class="fw-semibold">

                        {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}

                        -

                        {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- EXISTING CLASSES --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="fw-bold mb-0">

                    <i class="bi bi-people me-2"></i>

                    Classes Included in This Exam

                </h6>

                <span class="badge bg-primary">
                    {{ $examClasses->count() }} Classes
                </span>

            </div>

        </div>


        <div class="card-body">

            @if($examClasses->count())

                <div class="row g-3">

                    @foreach($examClasses as $examClass)

                        <div class="col-md-4 col-lg-3">

                            <div class="border rounded p-3 h-100">

                                <div class="fw-semibold">

                                    {{ $examClass->schoolClass?->class_name ?? 'Class' }}

                                </div>

                                @if($examClass->schoolClass?->section)

                                    <div class="small text-muted">

                                        Section:
                                        {{ $examClass->schoolClass->section }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    No classes have been configured for this exam.

                </div>

            @endif

        </div>

    </div>


    {{-- SUBJECT SUMMARY --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">

                <i class="bi bi-book me-2"></i>

                Exam Subjects

            </h6>

        </div>


        <div class="card-body">

            @if($examSubjects->count())

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th class="text-center">
                                    Maximum Marks
                                </th>

                                <th class="text-center">
                                    Duration
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($examSubjects as $examSubject)

                                <tr>

                                    <td>

                                        {{ $examSubject->schoolClass?->class_name ?? '-' }}

                                        @if($examSubject->schoolClass?->section)
                                            -
                                            {{ $examSubject->schoolClass->section }}
                                        @endif

                                    </td>


                                    <td>

                                        {{ $examSubject->subject?->subject_name ?? '-' }}

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-primary">

                                            {{ $examSubject->maximum_marks }}

                                            Marks

                                        </span>

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-secondary">

                                            {{ $examSubject->duration_minutes }}

                                            Minutes

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    No subjects are configured for this exam.

                </div>

            @endif

        </div>

    </div>


    {{-- SESSIONS --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">

                <i class="bi bi-clock me-2"></i>

                Exam Sessions

            </h6>

        </div>


        <div class="card-body">

            @if($sessions->count())

                <div class="row g-3">

                    @foreach($sessions as $session)

                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="fw-semibold">

                                    {{ $session->session_name }}

                                </div>

                                <div class="small text-muted">

                                    {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }}

                                    -

                                    {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    No active exam sessions have been configured.

                </div>

            @endif

        </div>

    </div>


    {{-- GENERATION FORM --}}
    <form method="POST"
          action="{{ route('admin.exam-schedules.generate', $exam) }}"
          id="generateTimetableForm">

        @csrf


        {{-- SETTINGS --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="fw-bold mb-0">

                    <i class="bi bi-gear me-2"></i>

                    Timetable Generation Settings

                </h6>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Papers Per Day

                        </label>

                        <select name="papers_per_day"
                                class="form-select"
                                required>

                            <option value="1"
                                {{ old('papers_per_day', 1) == 1 ? 'selected' : '' }}>

                                1 Paper Per Day

                            </option>

                            <option value="2"
                                {{ old('papers_per_day') == 2 ? 'selected' : '' }}>

                                2 Papers Per Day

                            </option>

                            <option value="3"
                                {{ old('papers_per_day') == 3 ? 'selected' : '' }}>

                                3 Papers Per Day

                            </option>

                        </select>

                        <div class="form-text">

                            The system will automatically use the configured
                            subject duration and available exam sessions.

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="form-check mt-4">

                            <input type="checkbox"
                                   class="form-check-input"
                                   name="replace_existing"
                                   value="1"
                                   id="replaceExisting">

                            <label class="form-check-label fw-semibold"
                                   for="replaceExisting">

                                Replace Existing Timetable

                            </label>

                        </div>

                        <div class="form-text">

                            Enable this if a timetable has already been generated
                            and you want to generate it again.

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- TEACHERS --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="fw-bold mb-1">

                            <i class="bi bi-person-check me-2"></i>

                            Teachers Available for This Examination

                        </h6>

                        <div class="text-muted small">

                            Select teachers who can supervise exam sessions.

                        </div>

                    </div>


                    <span class="badge bg-primary"
                          id="selectedTeacherCount">

                        0 Selected

                    </span>

                </div>

            </div>


            <div class="card-body">


                @if($activeTeachers->count())

                    {{-- SEARCH --}}
                    <div class="row mb-3">

                        <div class="col-md-6">

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-search"></i>

                                </span>

                                <input type="text"
                                       id="teacherSearch"
                                       class="form-control"
                                       placeholder="Search teacher name or employee ID...">

                            </div>

                        </div>


                        <div class="col-md-6 text-md-end mt-2 mt-md-0">

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    id="selectAllTeachers">

                                <i class="bi bi-check-all me-1"></i>

                                Select All

                            </button>


                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    id="clearAllTeachers">

                                <i class="bi bi-x-circle me-1"></i>

                                Clear All

                            </button>

                        </div>

                    </div>


                    {{-- TEACHERS --}}
                    <div class="row g-3"
                         id="teacherList">

                        @foreach($activeTeachers as $teacher)

                            @php

                                $teacherName = trim(
                                    ($teacher->first_name ?? '') .
                                    ' ' .
                                    ($teacher->middle_name ?? '') .
                                    ' ' .
                                    ($teacher->last_name ?? '')
                                );

                                if ($teacherName === '') {
                                    $teacherName =
                                        $teacher->name ??
                                        'Teacher';
                                }

                                $employeeId =
                                    $teacher->employee_id ??
                                    $teacher->teacher_id ??
                                    '';

                            @endphp


                            <div class="col-md-6 col-lg-4 teacher-card"
                                 data-search="{{ strtolower($teacherName . ' ' . $employeeId) }}">

                                <label class="border rounded p-3 w-100 h-100 teacher-item"
                                       style="cursor:pointer;">

                                    <div class="form-check">

                                        <input class="form-check-input teacher-checkbox"
                                               type="checkbox"
                                               name="teacher_ids[]"
                                               value="{{ $teacher->id }}"
                                               id="teacher_{{ $teacher->id }}"
                                               {{ in_array($teacher->id, old('teacher_ids', [])) ? 'checked' : '' }}>


                                        <span class="form-check-label">

                                            <span class="fw-semibold d-block">

                                                {{ $teacherName }}

                                            </span>


                                            @if($employeeId)

                                                <span class="text-muted small">

                                                    Employee ID:
                                                    {{ $employeeId }}

                                                </span>

                                            @endif

                                        </span>

                                    </div>

                                </label>

                            </div>

                        @endforeach

                    </div>


                    <div class="alert alert-info mt-4 mb-0">

                        <i class="bi bi-info-circle me-2"></i>

                        Only selected teachers will be assigned as exam
                        supervisors. The system will automatically rotate
                        selected teachers between available sessions.

                    </div>

                @else

                    <div class="alert alert-warning mb-0">

                        No active teachers are available.

                        Please add active teachers from the Teacher module.

                    </div>

                @endif

            </div>

        </div>


        {{-- GENERATE --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="fw-semibold">

                            Ready to Generate?

                        </div>

                        <div class="text-muted small">

                            The timetable will use each subject's configured
                            marks and duration.

                        </div>

                    </div>


                    <button type="submit"
                            class="btn btn-primary btn-lg"
                            id="generateButton"
                            @disabled(
                                $examClasses->isEmpty() ||
                                $examSubjects->isEmpty() ||
                                $sessions->isEmpty() ||
                                $activeTeachers->isEmpty()
                            )>

                        <i class="bi bi-magic me-2"></i>

                        Generate Timetable

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- JAVASCRIPT --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const teacherCheckboxes =
        document.querySelectorAll('.teacher-checkbox');

    const selectedCount =
        document.getElementById('selectedTeacherCount');

    const selectAll =
        document.getElementById('selectAllTeachers');

    const clearAll =
        document.getElementById('clearAllTeachers');

    const searchInput =
        document.getElementById('teacherSearch');

    const form =
        document.getElementById('generateTimetableForm');

    function updateSelectedCount() {

        const count =
            document.querySelectorAll(
                '.teacher-checkbox:checked'
            ).length;

        selectedCount.textContent =
            count + ' Selected';

    }


    teacherCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            updateSelectedCount
        );

    });


    if (selectAll) {

        selectAll.addEventListener(
            'click',
            function () {

                teacherCheckboxes.forEach(
                    function (checkbox) {

                        const card =
                            checkbox.closest(
                                '.teacher-card'
                            );

                        if (
                            !card ||
                            card.style.display !== 'none'
                        ) {

                            checkbox.checked =
                                true;
                        }

                    }
                );

                updateSelectedCount();

            }
        );

    }


    if (clearAll) {

        clearAll.addEventListener(
            'click',
            function () {

                teacherCheckboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            false;

                    }
                );

                updateSelectedCount();

            }
        );

    }


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                const search =
                    this.value
                        .toLowerCase()
                        .trim();

                document
                    .querySelectorAll(
                        '.teacher-card'
                    )
                    .forEach(
                        function (card) {

                            const text =
                                card.dataset
                                    .search
                                    .toLowerCase();

                            card.style.display =
                                text.includes(
                                    search
                                )
                                    ? ''
                                    : 'none';

                        }
                    );

            }
        );

    }


    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                const count =
                    document.querySelectorAll(
                        '.teacher-checkbox:checked'
                    ).length;

                if (count === 0) {

                    event.preventDefault();

                    alert(
                        'Please select at least one teacher for exam supervision.'
                    );

                    return;

                }


                const replace =
                    document.getElementById(
                        'replaceExisting'
                    );


                let message =
                    'Generate the examination timetable using ' +
                    count +
                    ' selected teacher(s)?';


                if (
                    replace &&
                    replace.checked
                ) {

                    message +=
                        '\n\nThe existing timetable will be deleted and replaced.';

                }


                if (!confirm(message)) {

                    event.preventDefault();

                }

            }
        );

    }


    updateSelectedCount();

});

</script>

@endsection