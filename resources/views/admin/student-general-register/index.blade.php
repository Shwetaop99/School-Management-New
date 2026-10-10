@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Student General Register
            </h2>

            <p class="text-muted mb-0">
                Complete general register of all students.
            </p>
        </div>

        <div class="d-flex gap-2">

            {{-- =================================================
                 PRINT FILTERED REGISTER
            ================================================== --}}
            <a href="{{ route('admin.student-general-register.print') }}"
               id="printRegisterBtn"
               target="_blank"
               class="btn btn-primary">

                <i class="bi bi-printer me-1"></i>
                Print Register

            </a>

        </div>

    </div>


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="gr-stat-card">

                <div>

                    <div class="gr-stat-label">
                        Total Students
                    </div>

                    <div class="gr-stat-value">
                        {{ $students->total() }}
                    </div>

                </div>

                <div class="gr-stat-icon">
                    <i class="bi bi-people"></i>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="gr-stat-card">

                <div>

                    <div class="gr-stat-label">
                        Academic Years
                    </div>

                    <div class="gr-stat-value">
                        {{ count($academicYears ?? []) }}
                    </div>

                </div>

                <div class="gr-stat-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="gr-stat-card">

                <div>

                    <div class="gr-stat-label">
                        Classes
                    </div>

                    <div class="gr-stat-value">
                        {{ count($classes ?? []) }}
                    </div>

                </div>

                <div class="gr-stat-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="gr-stat-card">

                <div>

                    <div class="gr-stat-label">
                        Showing Records
                    </div>

                    <div class="gr-stat-value">
                        {{ $students->count() }}
                    </div>

                </div>

                <div class="gr-stat-icon">
                    <i class="bi bi-list-columns"></i>
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

                <i class="bi bi-funnel me-2"></i>

                Filter General Register

            </h5>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.student-general-register.index') }}"
                  id="generalRegisterFilterForm">

                <div class="row g-3 align-items-end">


                    {{-- =================================================
                         ACADEMIC YEAR
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                id="academic_year"
                                class="form-select">

                            <option value="">
                                All Years
                            </option>

                            @foreach(($academicYears ?? []) as $year)

                                <option value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}>

                                    {{ $year }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         CLASS
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Current Class
                        </label>

                        <select name="class"
                                id="class"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach(($classes ?? []) as $class)

                                <option value="{{ $class }}"
                                    {{ request('class') == $class ? 'selected' : '' }}>

                                    {{ $class }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         SECTION
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Section
                        </label>

                        <select name="section"
                                id="section"
                                class="form-select">

                            <option value="">
                                All Sections
                            </option>

                            @foreach(($sections ?? []) as $section)

                                <option value="{{ $section }}"
                                    {{ request('section') == $section ? 'selected' : '' }}>

                                    {{ $section }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         SEARCH
                    ================================================== --}}

                    <div class="col-xl-4 col-lg-6 col-md-6">

                        <label class="form-label fw-semibold">
                            Search Student
                        </label>

                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Name, Student ID, Register No. or Aadhaar">

                    </div>


                    {{-- =================================================
                         BUTTONS
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-search me-1"></i>

                                Search

                            </button>


                            <a href="{{ route('admin.student-general-register.index') }}"
                               class="btn btn-outline-secondary"
                               title="Clear Filters">

                                <i class="bi bi-arrow-counterclockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         ACTIVE FILTER INFORMATION
    ========================================================== --}}

    @if(request('academic_year') || request('class') || request('section') || request('search'))

        <div class="alert alert-primary border-0 shadow-sm mb-4">

            <div class="d-flex flex-wrap align-items-center gap-3">

                <strong>
                    <i class="bi bi-funnel me-1"></i>
                    Active Filters:
                </strong>


                @if(request('academic_year'))

                    <span class="badge bg-primary">
                        Academic Year: {{ request('academic_year') }}
                    </span>

                @endif


                @if(request('class'))

                    <span class="badge bg-primary">
                        Class: {{ request('class') }}
                    </span>

                @endif


                @if(request('section'))

                    <span class="badge bg-primary">
                        Section: {{ request('section') }}
                    </span>

                @endif


                @if(request('search'))

                    <span class="badge bg-primary">
                        Search: {{ request('search') }}
                    </span>

                @endif

            </div>

        </div>

    @endif


    {{-- =========================================================
         GENERAL REGISTER
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        General Register
                    </h5>

                    <small class="text-muted">
                        Student master register
                    </small>

                </div>

                <span class="badge bg-primary">

                    {{ $students->total() }} Students

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="gr-table-wrapper">

                <table class="table table-bordered table-hover align-middle mb-0 gr-table">

                    <thead>

                        <tr>

                            <th rowspan="2">
                                Sr.<br>No.
                            </th>

                            <th rowspan="2">
                                Register<br>Number
                            </th>

                            <th rowspan="2">
                                Book<br>Number
                            </th>

                            <th rowspan="2">
                                Student's Name
                            </th>

                            <th rowspan="2">
                                Student ID
                            </th>

                            <th rowspan="2">
                                Aadhar Number
                            </th>

                            <th rowspan="2">
                                Mother's Name
                            </th>

                            <th rowspan="2">
                                Nationality
                            </th>

                            <th rowspan="2">
                                Mother Tongue
                            </th>

                            <th rowspan="2">
                                Religion
                            </th>

                            <th rowspan="2">
                                Caste
                            </th>

                            <th rowspan="2">
                                Sub Caste
                            </th>

                            <th rowspan="2">
                                Admission Date
                            </th>

                            <th rowspan="2">
                                Gender
                            </th>

                            <th rowspan="2">
                                Birth Place
                            </th>

                            <th colspan="2">
                                Birth Information
                            </th>

                            <th rowspan="2">
                                Previous School
                            </th>

                            <th rowspan="2">
                                Admission Class
                            </th>

                            <th rowspan="2">
                                Current Class
                            </th>

                            <th rowspan="2">
                                Section
                            </th>

                            <th rowspan="2">
                                State
                            </th>

                            <th rowspan="2">
                                Country
                            </th>

                            <th rowspan="2">
                                Action
                            </th>

                        </tr>


                        <tr>

                            <th>
                                Date of Birth
                            </th>

                            <th>
                                In Words
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($students as $student)

                            @php

                                $serialNumber =
                                    $students->firstItem()
                                    + $loop->index;

                                $studentName =
                                    $student->full_name
                                    ?: trim(
                                        ($student->first_name ?? '')
                                        . ' '
                                        . ($student->middle_name ?? '')
                                        . ' '
                                        . ($student->last_name ?? '')
                                    );

                            @endphp


                            <tr>

                                <td class="text-center fw-semibold">
                                    {{ $serialNumber }}
                                </td>


                                <td>
                                    {{ $student->register_no ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->book_no ?: '—' }}
                                </td>


                                <td class="student-name-cell">

                                    <div class="fw-semibold">
                                        {{ $studentName ?: '—' }}
                                    </div>

                                </td>


                                <td>
                                    {{ $student->student_id ?: '—' }}
                                </td>


                                <td class="nowrap">
                                    {{ $student->aadhar_card_no ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->mother_name ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->nationality ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->mother_tongue ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->religion ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->caste ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->sub_caste ?: '—' }}
                                </td>


                                <td class="nowrap">

                                    @if($student->admission_date)

                                        {{ $student->admission_date->format('d-m-Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>
                                    {{ $student->gender ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->birth_place ?: '—' }}
                                </td>


                                <td class="nowrap">

                                    @if($student->date_of_birth)

                                        {{ $student->date_of_birth->format('d-m-Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td class="dob-words">

                                    @if($student->date_of_birth)

                                        {{ $student->date_of_birth->format('d F Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>
                                    {{ $student->previous_school_name ?: '—' }}
                                </td>


                                <td class="text-center">
                                    {{ $student->admission_class ?: '—' }}
                                </td>


                                <td class="text-center">
                                    {{ $student->class ?: '—' }}
                                </td>


                                <td class="text-center">
                                    {{ $student->section ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->state ?: '—' }}
                                </td>


                                <td>
                                    {{ $student->country ?: '—' }}
                                </td>


                                <td class="text-center">

                                    <a href="{{ route('admin.student-general-register.show', $student->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Student">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="24"
                                    class="text-center py-5">

                                    <div class="py-4">

                                        <div class="gr-empty-icon mb-3">

                                            <i class="bi bi-journal-text"></i>

                                        </div>

                                        <h5 class="fw-bold">
                                            No Students Found
                                        </h5>

                                        <p class="text-muted mb-0">
                                            No students match the selected filters.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($students->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted small">

                        Showing

                        <strong>
                            {{ $students->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $students->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $students->total() }}
                        </strong>

                        students

                    </div>


                    <div>

                        {{ $students->withQueryString()->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
     GENERAL REGISTER STYLES
============================================================= --}}

<style>

    .gr-stat-card {

        background: #fff;

        border-radius: 12px;

        padding: 22px;

        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);

        display: flex;

        justify-content: space-between;

        align-items: center;

        height: 100%;

    }


    .gr-stat-label {

        color: #6c757d;

        font-size: 14px;

        margin-bottom: 5px;

    }


    .gr-stat-value {

        font-size: 28px;

        font-weight: 700;

        line-height: 1.2;

    }


    .gr-stat-icon {

        width: 50px;

        height: 50px;

        border-radius: 12px;

        background: rgba(13, 110, 253, 0.10);

        color: #0d6efd;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 22px;

    }


    .gr-table-wrapper {

        width: 100%;

        overflow-x: auto;

        overflow-y: hidden;

    }


    .gr-table {

        min-width: 2600px;

        font-size: 13px;

    }


    .gr-table thead th {

        background: #f8f9fa;

        font-weight: 700;

        text-align: center;

        vertical-align: middle;

        white-space: nowrap;

        padding: 10px 12px;

    }


    .gr-table tbody td {

        padding: 10px 12px;

        vertical-align: middle;

        white-space: nowrap;

    }


    .student-name-cell {

        min-width: 190px;

        white-space: normal !important;

    }


    .dob-words {

        min-width: 150px;

    }


    .nowrap {

        white-space: nowrap !important;

    }


    .gr-table tbody tr:hover {

        background: rgba(13, 110, 253, 0.03);

    }


    .gr-empty-icon {

        width: 75px;

        height: 75px;

        margin: 0 auto;

        border-radius: 50%;

        background: #f1f3f5;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 32px;

        color: #6c757d;

    }


    .form-control,
    .form-select {

        border-radius: 7px;

    }


    .form-control:focus,
    .form-select:focus {

        box-shadow: none;

    }


    .btn {

        border-radius: 7px;

    }


    @media (max-width: 768px) {

        .gr-table {

            min-width: 2400px;

        }

    }

</style>


{{-- =============================================================
     PRINT BUTTON FILTER HANDLING
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const printButton = document.getElementById('printRegisterBtn');

    if (!printButton) {
        return;
    }


    function updatePrintUrl() {

        const params = new URLSearchParams();


        const academicYear =
            document.getElementById('academic_year')?.value || '';

        const selectedClass =
            document.getElementById('class')?.value || '';

        const section =
            document.getElementById('section')?.value || '';

        const search =
            document.getElementById('search')?.value.trim() || '';


        /*
        |--------------------------------------------------------------------------
        | Add only selected filters
        |--------------------------------------------------------------------------
        */

        if (academicYear) {
            params.set('academic_year', academicYear);
        }

        if (selectedClass) {
            params.set('class', selectedClass);
        }

        if (section) {
            params.set('section', section);
        }

        if (search) {
            params.set('search', search);
        }


        /*
        |--------------------------------------------------------------------------
        | Build Print URL
        |--------------------------------------------------------------------------
        */

        const baseUrl =
            "{{ route('admin.student-general-register.print') }}";

        const queryString =
            params.toString();

        printButton.href =
            queryString
                ? baseUrl + '?' + queryString
                : baseUrl;

    }


    /*
    |--------------------------------------------------------------------------
    | Update when filters change
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('academic_year')
        ?.addEventListener('change', updatePrintUrl);


    document
        .getElementById('class')
        ?.addEventListener('change', updatePrintUrl);


    document
        .getElementById('section')
        ?.addEventListener('change', updatePrintUrl);


    document
        .getElementById('search')
        ?.addEventListener('input', updatePrintUrl);


    /*
    |--------------------------------------------------------------------------
    | Initial URL
    |--------------------------------------------------------------------------
    */

    updatePrintUrl();

});

</script>

@endsection