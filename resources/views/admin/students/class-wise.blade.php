@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
       PAGE
    ========================================================= */

    .class-wise-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }


    /* =========================================================
       WELCOME HEADER
    ========================================================= */

    .welcome-card {
        position: relative;
        overflow: hidden;
        min-height: 155px;
        padding: 30px 34px;
        margin-bottom: 14px;
        border-radius: 18px;
        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #159cc7 100%
        );
        color: #fff;
        box-shadow: 0 10px 28px rgba(23, 105, 209, 0.18);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .welcome-card::before {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        right: -45px;
        top: -105px;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
    }

    .welcome-card::after {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        right: 100px;
        bottom: -82px;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
    }

    .welcome-card h2 {
        position: relative;
        z-index: 2;
        margin: 0 0 7px;
        font-size: 30px;
        font-weight: 800;
    }

    .welcome-card p {
        position: relative;
        z-index: 2;
        margin: 0;
        font-size: 15px;
        color: rgba(255,255,255,.94);
    }

    .back-wrapper {
        margin-bottom: 20px;
    }

    .cw-back-btn {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e1e6ed;
        background: #fff;
        color: #495057;
        border-radius: 9px;
        padding: 9px 15px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .cw-back-btn:hover {
        background: #f8f9fa;
        color: #212529;
        border-color: #cfd6df;
    }


    /* =========================================================
       FILTER CARD
    ========================================================= */

    .filter-card {
        background: #fff;
        border: 1px solid #e5e9ef;
        border-radius: 15px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .045);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .filter-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .filter-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eef4ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .filter-title {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 700;
    }

    .filter-description {
        color: #8a93a2;
        font-size: 11px;
        margin-top: 2px;
    }

    .filter-body {
        padding: 20px;
    }

    .filter-label {
        display: block;
        margin-bottom: 7px;
        color: #596273;
        font-size: 11px;
        font-weight: 700;
    }

    .filter-control {
        min-height: 42px;
        border: 1px solid #dfe4ea;
        border-radius: 9px;
        font-size: 13px;
        color: #374151;
        box-shadow: none !important;
    }

    .filter-control:focus {
        border-color: #86b7fe;
    }

    .filter-actions {
        display: flex;
        align-items: end;
        gap: 8px;
        height: 100%;
    }

    .btn-filter {
        min-height: 42px;
        border-radius: 9px;
        padding: 0 18px;
        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       DASHBOARD STYLE SUMMARY CARDS
    ========================================================= */

    .dashboard-summary-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border: 0;
        border-radius: 16px;
        min-height: 130px;
        padding: 20px 22px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        transition: all .25s ease;
    }

    .dashboard-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(15, 23, 42, .10);
    }

    .dashboard-summary-card::after {
        content: "";
        position: absolute;
        width: 105px;
        height: 105px;
        right: -35px;
        top: -38px;
        border-radius: 50%;
        opacity: .08;
    }

    .dashboard-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .dashboard-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    /* Total */

    .dashboard-card-total .dashboard-card-icon {
        background: #eaf2ff;
        color: #1769d1;
    }

    .dashboard-card-total::after {
        background: #1769d1;
    }

    /* Boys */

    .dashboard-card-boys .dashboard-card-icon {
        background: #fff4df;
        color: #ed9208;
    }

    .dashboard-card-boys::after {
        background: #ed9208;
    }

    /* Girls */

    .dashboard-card-girls .dashboard-card-icon {
        background: #e8f9fc;
        color: #079dbd;
    }

    .dashboard-card-girls::after {
        background: #079dbd;
    }

    /* Active */

    .dashboard-card-active .dashboard-card-icon {
        background: #eaf9f0;
        color: #198754;
    }

    .dashboard-card-active::after {
        background: #198754;
    }

    .dashboard-card-label {
        margin-top: 14px;
        color: #7b8494;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .dashboard-card-value {
        color: #172033;
        font-size: 27px;
        line-height: 1;
        font-weight: 800;
        margin-top: 5px;
    }

    .dashboard-card-description {
        margin-top: 7px;
        color: #9aa2af;
        font-size: 10px;
    }


    /* =========================================================
       STUDENT LIST CARD
    ========================================================= */

    .student-list-card {
        background: #fff;
        border: 1px solid #e5e9ef;
        border-radius: 15px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .045);
        overflow: hidden;
        margin-top: 20px;
    }

    .student-list-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .class-heading {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .class-heading-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .class-title {
        color: #172033;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .class-meta {
        color: #8a93a2;
        font-size: 11px;
        margin-top: 3px;
    }

    .print-btn {
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        padding: 9px 15px;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .student-table-wrapper {
        overflow-x: auto;
    }

    .student-table {
        margin: 0;
        min-width: 850px;
    }

    .student-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e6eaf0;
        color: #727b8a;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .45px;
        padding: 13px 15px;
        white-space: nowrap;
    }

    .student-table tbody td {
        padding: 13px 15px;
        border-bottom: 1px solid #f0f2f5;
        color: #374151;
        font-size: 12px;
        vertical-align: middle;
    }

    .student-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .student-table tbody tr:hover {
        background: #fafbfc;
    }

    .table-number {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
    }

    .student-name {
        color: #172033;
        font-size: 12px;
        font-weight: 700;
    }

    .student-id {
        color: #7b8494;
        font-size: 10px;
        margin-top: 2px;
    }

    .roll-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 35px;
        height: 28px;
        padding: 0 8px;
        border-radius: 7px;
        background: #f8f9fa;
        border: 1px solid #e8ebef;
        color: #495057;
        font-size: 11px;
        font-weight: 700;
    }

    .gender-badge {
        font-size: 11px;
        font-weight: 600;
    }

    .gender-male {
        color: #0b78c5;
    }

    .gender-female {
        color: #d63384;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 30px;
        padding: 5px 10px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-active {
        background: #ecfdf3;
        color: #198754;
    }

    .status-inactive {
        background: #fff1f2;
        color: #dc3545;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 15px;
        border-radius: 15px;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .empty-title {
        color: #374151;
        font-size: 15px;
        font-weight: 700;
    }

    .empty-text {
        color: #8a93a2;
        font-size: 12px;
        margin-top: 5px;
    }


    /* =========================================================
       FOOTER / PAGINATION
    ========================================================= */

    .student-list-footer {
        border-top: 1px solid #edf0f4;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .list-summary {
        color: #7b8494;
        font-size: 11px;
    }

    .list-summary strong {
        color: #374151;
    }

    .student-list-footer .pagination {
        margin: 0;
    }

    .student-list-footer .page-link {
        border-radius: 7px;
        margin: 0 2px;
        border: 1px solid #e5e9ef;
        color: #495057;
        font-size: 11px;
        min-width: 32px;
        text-align: center;
    }

    .student-list-footer .page-item.active .page-link {
        background: #1769d1;
        border-color: #1769d1;
        color: #fff;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 991px) {

        .filter-actions {
            margin-top: 10px;
        }

        .filter-actions .btn {
            flex: 1;
        }
    }

    @media(max-width: 767px) {

        .class-wise-page {
            padding: 14px;
        }

        .welcome-card {
            min-height: 135px;
            padding: 24px;
        }

        .welcome-card h2 {
            font-size: 23px;
        }

        .welcome-card p {
            font-size: 13px;
        }

        .student-list-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .print-btn {
            width: 100%;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn {
            width: 50%;
        }

        .student-list-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-summary-card {
            min-height: 120px;
            padding: 18px;
        }

        .dashboard-card-icon {
            width: 43px;
            height: 43px;
            font-size: 18px;
        }

        .dashboard-card-value {
            font-size: 24px;
        }
    }
</style>


<div class="class-wise-page">

    {{-- =====================================================
         WELCOME HEADER
    ====================================================== --}}

    <div class="welcome-card">

        <h2>
            Welcome back, Admin! 👋
        </h2>

        <p>
            Here's what's happening across your school today.
        </p>

    </div>


    {{-- BACK TO STUDENTS --}}

    <div class="back-wrapper">

        <a href="{{ route('admin.students.index') }}"
           class="cw-back-btn">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Students

        </a>

    </div>



    {{-- =====================================================
         DASHBOARD STYLE SUMMARY CARDS
    ====================================================== --}}

    <div class="row g-3 mb-4">

        {{-- TOTAL STUDENTS --}}

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-summary-card dashboard-card-total">

                <div class="dashboard-card-top">

                    <div class="dashboard-card-icon">

                        <i class="bi bi-people-fill"></i>

                    </div>

                </div>

                <div class="dashboard-card-label">
                    Total Students
                </div>

                <div class="dashboard-card-value">
                    {{ $totalStudents }}
                </div>

                <div class="dashboard-card-description">
                    Students matching filters
                </div>

            </div>

        </div>


        {{-- BOYS --}}

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-summary-card dashboard-card-boys">

                <div class="dashboard-card-top">

                    <div class="dashboard-card-icon">

                        <i class="bi bi-gender-male"></i>

                    </div>

                </div>

                <div class="dashboard-card-label">
                    Boys
                </div>

                <div class="dashboard-card-value">
                    {{ $totalBoys }}
                </div>

                <div class="dashboard-card-description">
                    Male students
                </div>

            </div>

        </div>


        {{-- GIRLS --}}

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-summary-card dashboard-card-girls">

                <div class="dashboard-card-top">

                    <div class="dashboard-card-icon">

                        <i class="bi bi-gender-female"></i>

                    </div>

                </div>

                <div class="dashboard-card-label">
                    Girls
                </div>

                <div class="dashboard-card-value">
                    {{ $totalGirls }}
                </div>

                <div class="dashboard-card-description">
                    Female students
                </div>

            </div>

        </div>


        {{-- ACTIVE STUDENTS --}}

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-summary-card dashboard-card-active">

                <div class="dashboard-card-top">

                    <div class="dashboard-card-icon">

                        <i class="bi bi-person-check-fill"></i>

                    </div>

                </div>

                <div class="dashboard-card-label">
                    Active Students
                </div>

                <div class="dashboard-card-value">
                    {{ $totalActive }}
                </div>

                <div class="dashboard-card-description">
                    Currently active
                </div>

            </div>

        </div>

    </div>

      {{-- =====================================================
         FILTER CARD
    ====================================================== --}}

    <div class="filter-card">

        <div class="filter-header">

            <div class="filter-icon">
                <i class="bi bi-funnel"></i>
            </div>

            <div>

                <h5 class="filter-title">
                    Student List Filters
                </h5>

                <div class="filter-description">
                    Select the required criteria to generate the student list
                </div>

            </div>

        </div>


        <div class="filter-body">

            <form method="GET"
                  action="{{ route('admin.students.class-wise') }}">

                <div class="row g-3">

                    {{-- ACADEMIC YEAR --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="filter-label">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                class="form-select filter-control">

                            <option value="">
                                All Academic Years
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}>

                                    {{ $year }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CLASS --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="filter-label">
                            Class
                        </label>

                        <select name="class"
                                class="form-select filter-control">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class }}"
                                    {{ request('class') == $class ? 'selected' : '' }}>

                                    Class {{ $class }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SECTION --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="filter-label">
                            Section
                        </label>

                        <select name="section"
                                class="form-select filter-control">

                            <option value="">
                                All Sections
                            </option>

                            @foreach($sections as $section)

                                <option value="{{ $section }}"
                                    {{ request('section') == $section ? 'selected' : '' }}>

                                    Section {{ $section }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- GENDER --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="filter-label">
                            Gender
                        </label>

                        <select name="gender"
                                class="form-select filter-control">

                            <option value="">
                                All
                            </option>

                            <option value="Male"
                                {{ request('gender') == 'Male' ? 'selected' : '' }}>

                                Male

                            </option>

                            <option value="Female"
                                {{ request('gender') == 'Female' ? 'selected' : '' }}>

                                Female

                            </option>

                        </select>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="filter-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select filter-control">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="inactive"
                                {{ request('status') == 'inactive' ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- ACTIONS --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="filter-label">
                            &nbsp;
                        </label>

                        <div class="filter-actions">

                            <button type="submit"
                                    class="btn btn-primary btn-filter">

                                <i class="bi bi-search me-1"></i>

                                Apply

                            </button>


                            <a href="{{ route('admin.students.class-wise') }}"
                               class="btn btn-light border btn-filter"
                               title="Reset Filters">

                                <i class="bi bi-arrow-counterclockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- =====================================================
         STUDENT LIST
    ====================================================== --}}

    <div class="student-list-card">


        {{-- =================================================
             LIST HEADER
        ================================================== --}}

        <div class="student-list-header">

            <div class="class-heading">

                <div class="class-heading-icon">

                    <i class="bi bi-mortarboard-fill"></i>

                </div>

                <div>

                    <h5 class="class-title">

                        @if(request('class'))

                            Class {{ request('class') }}

                            @if(request('section'))

                                - Section {{ request('section') }}

                            @endif

                        @else

                            All Classes

                        @endif

                    </h5>


                    <div class="class-meta">

                        @if(request('academic_year'))

                            Academic Year:

                            <strong>
                                {{ request('academic_year') }}
                            </strong>

                        @else

                            All Academic Years

                        @endif

                        @if(request('gender'))

                            <span class="ms-2">
                                • {{ request('gender') }}
                            </span>

                        @endif

                        @if(request('status'))

                            <span class="ms-2">
                                • {{ ucfirst(request('status')) }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- PRINT BUTTON --}}

            <a href="{{ route('admin.students.class-wise.print', [
        'academic_year' => request('academic_year'),
        'class'        => request('class'),
        'section'      => request('section'),
        'gender'       => request('gender'),
        'status'       => request('status'),
    ]) }}"
   target="_blank"
   class="btn btn-dark print-btn">

    <i class="bi bi-printer me-1"></i>
    Print List
</a>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}

        @if($students->count())

            <div class="student-table-wrapper">

                <table class="table student-table">

                    <thead>

                        <tr>

                            <th class="ps-4">
                                No.
                            </th>

                            <th>
                                Roll No.
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Student ID
                            </th>

                            <th>
                                Class
                            </th>

                            <th>
                                Section
                            </th>

                            <th>
                                Gender
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($students as $student)

                            <tr>


                                {{-- NUMBER --}}

                                <td class="ps-4">

                                    <span class="table-number">

                                        {{ $students->firstItem() + $loop->index }}

                                    </span>

                                </td>


                                {{-- ROLL NUMBER --}}

                                <td>

                                    <span class="roll-badge">

                                        {{ $student->roll_number ?? '-' }}

                                    </span>

                                </td>


                                {{-- STUDENT --}}

                                <td>

                                    <div class="student-name">

                                        {{ trim(
                                            ($student->first_name ?? '') . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            ($student->last_name ?? '')
                                        ) }}

                                    </div>

                                    @if(!empty($student->marathi_name))

                                        <div class="student-id">

                                            {{ $student->marathi_name }}

                                        </div>

                                    @endif

                                </td>


                                {{-- STUDENT ID --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $student->student_id ?? '-' }}

                                    </span>

                                </td>


                                {{-- CLASS --}}

                                <td>

                                    {{ $student->class ?? '-' }}

                                </td>


                                {{-- SECTION --}}

                                <td>

                                    {{ $student->section ?? '-' }}

                                </td>


                                {{-- GENDER --}}

                                <td>

                                    @if($student->gender === 'Male')

                                        <span class="gender-badge gender-male">

                                            <i class="bi bi-gender-male me-1"></i>

                                            Male

                                        </span>

                                    @elseif($student->gender === 'Female')

                                        <span class="gender-badge gender-female">

                                            <i class="bi bi-gender-female me-1"></i>

                                            Female

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($student->status === 'active')

                                        <span class="status-badge status-active">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">

                                            <i class="bi bi-x-circle-fill"></i>

                                            {{ ucfirst($student->status ?? 'Inactive') }}

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div class="student-list-footer">

                <div class="list-summary">

                    Showing

                    <strong>
                        {{ $students->firstItem() }}
                    </strong>

                    -

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


        @else


            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="empty-state">

                <div class="empty-icon">

                    <i class="bi bi-people"></i>

                </div>


                <div class="empty-title">

                    No Students Found

                </div>


                <div class="empty-text">

                    No students match the selected filters.

                </div>

            </div>

        @endif


    </div>

</div>

@endsection
