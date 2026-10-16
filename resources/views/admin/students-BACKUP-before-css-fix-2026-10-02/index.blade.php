@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
       PAGE
    ========================================================== */

    .student-page {
        background: #f4f7fb;
        min-height: calc(100vh - 70px);
    }

    /* =========================================================
       WELCOME CARD
    ========================================================== */

    .welcome-card {
        position: relative;
        overflow: hidden;
        border: 0;
        border-radius: 20px;
        background: linear-gradient(135deg, #1769e0 0%, #0d47a1 100%);
        color: #fff;
        box-shadow: 0 12px 30px rgba(23, 105, 224, 0.20);
    }

    .welcome-card::before {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -80px;
        top: -120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.10);
    }

    .welcome-card::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: 130px;
        bottom: -110px;
        border-radius: 50%;
        background: rgba(255,255,255,0.07);
    }

    .welcome-content {
        position: relative;
        z-index: 2;
    }

    .welcome-title {
        font-size: 25px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .welcome-text {
        font-size: 14px;
        opacity: 0.90;
        margin-bottom: 0;
    }

    .welcome-icon {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        background: rgba(255,255,255,0.16);
        border: 1px solid rgba(255,255,255,0.20);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        backdrop-filter: blur(5px);
    }

    /* =========================================================
       STAT CARDS
    ========================================================== */

    .stat-card {
        border: 0 !important;
        border-radius: 18px;
        overflow: hidden;
        position: relative;
        transition: all 0.25s ease;
        box-shadow: 0 8px 24px rgba(30,41,59,0.08) !important;
        color: #fff;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(30,41,59,0.15) !important;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        right: -35px;
        bottom: -50px;
        background: rgba(255,255,255,0.13);
    }

    .stat-blue {
        background: linear-gradient(135deg, #1976f3, #0d47a1);
    }

    .stat-cyan {
        background: linear-gradient(135deg, #00a6d6, #006f91);
    }

    .stat-pink {
        background: linear-gradient(135deg, #ef476f, #c9184a);
    }

    .stat-green {
        background: linear-gradient(135deg, #20b26b, #087f5b);
    }

    .stat-content {
        position: relative;
        z-index: 2;
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 15px;
        background: rgba(255,255,255,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
        border: 1px solid rgba(255,255,255,0.12);
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        opacity: 0.88;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .stat-value {
        font-size: 28px;
        line-height: 1;
        font-weight: 800;
        margin-top: 5px;
    }

    /* =========================================================
       COMMON CARD
    ========================================================== */

    .student-page .card {
        border: 0 !important;
        border-radius: 16px;
        box-shadow: 0 6px 22px rgba(30,41,59,0.06) !important;
    }

    /* =========================================================
       FILTER
    ========================================================== */

    .filter-heading {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .filter-subtitle {
        font-size: 12px;
        color: #8993a4;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #5d6878;
        margin-bottom: 6px;
    }

    .student-page .form-control,
    .student-page .form-select {
        min-height: 43px;
        border-radius: 9px;
        border-color: #dfe5ed;
        font-size: 13px;
        box-shadow: none;
    }

    .student-page .form-control:focus,
    .student-page .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13,110,253,.08);
    }

    .student-page .input-group-text {
        border-color: #dfe5ed;
        background: #fff;
    }

    .filter-btn {
        min-height: 42px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        padding: 0 17px;
    }

    /* =========================================================
       TABLE
    ========================================================== */

    .student-list-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .student-list-subtitle {
        font-size: 12px;
        color: #8a94a5;
    }

    .student-page .table {
        font-size: 13px;
    }

    .student-page .table > :not(caption) > * > * {
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom-color: #edf0f4;
    }

    .student-page .table thead th {
        background: #f8fafc;
        color: #687386;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-weight: 700;
        white-space: nowrap;
    }

    .student-page .table tbody tr {
        transition: all .18s ease;
    }

    .student-page .table tbody tr:hover {
        background: #f7fbff;
    }

    /* =========================================================
       STUDENT
    ========================================================== */

    .student-avatar {
        width: 44px;
        height: 44px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .student-name {
        color: #202938;
        font-weight: 600;
    }

    .student-marathi-name {
        color: #8a94a5;
        font-size: 11px;
        margin-top: 2px;
    }

    .student-id-badge {
        background: #edf5ff;
        color: #1769d2;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .class-badge {
        border-radius: 7px;
        padding: 6px 9px;
        font-size: 11px;
    }

    .section-badge {
        border-radius: 7px;
        padding: 6px 9px;
        font-size: 11px;
    }

    .status-badge {
        border-radius: 20px;
        padding: 6px 11px;
        font-size: 10px;
        font-weight: 700;
    }

    /* =========================================================
       ACTIONS
    ========================================================== */

    .action-group {
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 8px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: auto;
        border-radius: 50%;
        background: #f1f4f8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        font-size: 35px;
        color: #9aa4b2;
    }

    /* =========================================================
       PAGINATION
    ========================================================== */

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: 1px solid #e1e6ed;
        color: #586274;
        font-size: 12px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 768px) {

        .student-page {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .welcome-title {
            font-size: 21px;
        }

        .welcome-icon {
            width: 58px;
            height: 58px;
            font-size: 25px;
        }

        .student-page .table {
            min-width: 1100px;
        }

        .stat-value {
            font-size: 24px;
        }
    }
</style>


<div class="container-fluid py-4 student-page">

    {{-- =========================================================
         WELCOME CARD
    ========================================================== --}}

    <div class="welcome-card mb-4">

        <div class="card-body p-4 p-lg-4">

            <div class="d-flex justify-content-between align-items-center">

                <div class="welcome-content">

                    <div class="welcome-title">
                        Welcome to Student Management
                    </div>

                    <p class="welcome-text">
                        Manage student registrations, academic information,
                        profiles and records from one place.
                    </p>

                </div>

                <div class="welcome-icon d-none d-sm-flex">

                    <i class="bi bi-mortarboard-fill"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Students
            </h3>

            <div class="text-muted small">
                Manage and view all registered students
            </div>

        </div>

        <a href="{{ route('admin.students.create') }}"
           class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm">

            <i class="bi bi-person-plus-fill me-2"></i>
            Add Student

        </a>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3"
             role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3"
             role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         COLOURED STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- TOTAL --}}
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card stat-blue h-100">

                <div class="card-body p-4">

                    <div class="stat-content d-flex align-items-center">

                        <div class="stat-icon me-3">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <div>

                            <div class="stat-label">
                                Total Students
                            </div>

                            <div class="stat-value">
                                {{ number_format($totalStudents) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- BOYS --}}
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card stat-cyan h-100">

                <div class="card-body p-4">

                    <div class="stat-content d-flex align-items-center">

                        <div class="stat-icon me-3">
                            <i class="bi bi-gender-male"></i>
                        </div>

                        <div>

                            <div class="stat-label">
                                Boys
                            </div>

                            <div class="stat-value">
                                {{ number_format($totalMaleStudents) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- GIRLS --}}
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card stat-pink h-100">

                <div class="card-body p-4">

                    <div class="stat-content d-flex align-items-center">

                        <div class="stat-icon me-3">
                            <i class="bi bi-gender-female"></i>
                        </div>

                        <div>

                            <div class="stat-label">
                                Girls
                            </div>

                            <div class="stat-value">
                                {{ number_format($totalFemaleStudents) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- CLASSES --}}
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card stat-green h-100">

                <div class="card-body p-4">

                    <div class="stat-content d-flex align-items-center">

                        <div class="stat-icon me-3">
                            <i class="bi bi-building"></i>
                        </div>

                        <div>

                            <div class="stat-label">
                                Classes
                            </div>

                            <div class="stat-value">
                                {{ count($classes) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTER CARD
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-3 p-lg-4">

            <div class="d-flex align-items-center mb-4">

                <div class="bg-primary bg-opacity-10 text-primary rounded-3
                            d-flex align-items-center justify-content-center me-3"
                     style="width:42px;height:42px;">

                    <i class="bi bi-funnel-fill"></i>

                </div>

                <div>

                    <div class="filter-heading">
                        Search & Filter Students
                    </div>

                    <div class="filter-subtitle">
                        Find students quickly using the available filters
                    </div>

                </div>

            </div>


            <form id="studentFilterForm"
                  method="GET"
                  action="{{ route('admin.students.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-xl-4 col-lg-6">

                        <label class="form-label filter-label">
                            Search Student
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-search text-primary"></i>

                            </span>

                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Student ID, name, phone or email">

                        </div>

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label filter-label">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                class="form-select">

                            <option value="">
                                All Years
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}>

                                    {{ $year }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Class --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label filter-label">
                            Class
                        </label>

                        <select name="class"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class }}"
                                    {{ request('class') == $class ? 'selected' : '' }}>

                                    {{ $class }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Section --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label filter-label">
                            Section
                        </label>

                        <select name="section"
                                class="form-select">

                            <option value="">
                                All Sections
                            </option>

                            @foreach($sections as $section)

                                <option value="{{ $section }}"
                                    {{ request('section') == $section ? 'selected' : '' }}>

                                    {{ $section }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label filter-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-12">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.students.index') }}"
                               class="btn btn-outline-secondary filter-btn">

                                <i class="bi bi-arrow-counterclockwise me-1"></i>

                                Reset

                            </a>

                            <button type="submit"
                                    class="btn btn-primary filter-btn">

                                <i class="bi bi-funnel-fill me-1"></i>

                                Apply Filters

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         STUDENT TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-body border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>

                    <div class="student-list-title">

                        <i class="bi bi-list-ul text-primary me-2"></i>

                        Student List

                    </div>

                    <div class="student-list-subtitle">

                        @if($students->total() > 0)

                            Showing
                            {{ $students->firstItem() }}
                            -
                            {{ $students->lastItem() }}
                            of
                            {{ $students->total() }}

                        @else

                            No students found

                        @endif

                    </div>

                </div>


                @if(request()->hasAny([
                    'search',
                    'academic_year',
                    'class',
                    'section',
                    'status'
                ]))

                    <span class="badge bg-primary rounded-pill px-3 py-2">

                        <i class="bi bi-funnel-fill me-1"></i>

                        Filters Applied

                    </span>

                @endif

            </div>

        </div>


        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th class="ps-4">#</th>

                        <th>Student</th>

                        <th>Student ID</th>

                        <th>Academic Year</th>

                        <th>Class</th>

                        <th>Section</th>

                        <th>Roll No.</th>

                        <th>Gender</th>

                        <th>Phone</th>

                        <th>Status</th>

                        <th class="text-center pe-4">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($students as $student)

                        @php

                            $fullName = trim(
                                ($student->first_name ?? '') . ' ' .
                                ($student->middle_name ?? '') . ' ' .
                                ($student->last_name ?? '')
                            );

                            $initial = strtoupper(
                                substr(
                                    $student->first_name ?? 'S',
                                    0,
                                    1
                                )
                            );

                        @endphp


                        <tr>

                            {{-- NUMBER --}}
                            <td class="ps-4">

                                <span class="text-muted fw-semibold">

                                    {{ $students->firstItem() + $loop->index }}

                                </span>

                            </td>


                            {{-- STUDENT --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    @if($student->profile_image)

                                        <img src="{{ $student->profile_image }}"
                                             alt="{{ $fullName }}"
                                             class="rounded-circle border student-avatar me-2"
                                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($fullName ?: 'Student') }}&background=2563eb&color=fff&size=100';">

                                    @else

                                        <div class="student-avatar rounded-circle bg-primary text-white
                                                    d-flex align-items-center justify-content-center
                                                    fw-bold me-2">

                                            {{ $initial }}

                                        </div>

                                    @endif


                                    <div>

                                        <div class="student-name">
                                            {{ $fullName ?: 'Unnamed Student' }}
                                        </div>

                                        @if($student->marathi_name)

                                            <div class="student-marathi-name">
                                                {{ $student->marathi_name }}
                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- STUDENT ID --}}
                            <td>

                                <span class="student-id-badge">
                                    {{ $student->student_id ?? '-' }}
                                </span>

                            </td>


                            {{-- ACADEMIC YEAR --}}
                            <td>

                                @if($student->academic_year)

                                    <span class="text-muted">

                                        <i class="bi bi-calendar3 me-1"></i>

                                        {{ $student->academic_year }}

                                    </span>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>


                            {{-- CLASS --}}
                            <td>

                                @if($student->class)

                                    <span class="badge bg-success class-badge">
                                        {{ $student->class }}
                                    </span>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>


                            {{-- SECTION --}}
                            <td>

                                @if($student->section)

                                    <span class="badge bg-primary section-badge">
                                        {{ $student->section }}
                                    </span>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>


                            {{-- ROLL --}}
                            <td>

                                {{ $student->roll_number ?? '-' }}

                            </td>


                            {{-- GENDER --}}
                            <td>

                                @if(strtolower($student->gender ?? '') === 'male')

                                    <span class="text-info fw-semibold">

                                        <i class="bi bi-gender-male me-1"></i>
                                        Male

                                    </span>

                                @elseif(strtolower($student->gender ?? '') === 'female')

                                    <span class="text-danger fw-semibold">

                                        <i class="bi bi-gender-female me-1"></i>
                                        Female

                                    </span>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>


                            {{-- PHONE --}}
                            <td>

                                @if($student->phone)

                                    <a href="tel:{{ $student->phone }}"
                                       class="text-decoration-none text-dark">

                                        <i class="bi bi-telephone text-primary me-1"></i>

                                        {{ $student->phone }}

                                    </a>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if(strtolower($student->status ?? '') === 'active')

                                    <span class="badge bg-success status-badge">

                                        <i class="bi bi-check-circle-fill me-1"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger status-badge">

                                        {{ ucfirst($student->status ?? 'Inactive') }}

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td class="text-center pe-4">

                                <div class="action-group">

                                    {{-- VIEW --}}
                                    <a href="{{ route('admin.students.show', $student) }}"
                                       class="btn btn-sm btn-outline-primary action-btn"
                                       title="View Student">

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="btn btn-sm btn-outline-warning action-btn"
                                       title="Edit Student">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>


                                    {{-- DELETE --}}
                                    <form method="POST"
                                          action="{{ route('admin.students.destroy', $student) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this student?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger action-btn"
                                                title="Delete Student">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="11"
                                class="text-center py-5">

                                <div class="py-4">

                                    <div class="empty-icon">
                                        <i class="bi bi-person-x"></i>
                                    </div>

                                    <h5 class="fw-bold mt-3 mb-2">
                                        No Students Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        No student records match your current filters.
                                    </p>

                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('admin.students.index') }}"
                                           class="btn btn-outline-secondary filter-btn">

                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Reset Filters

                                        </a>

                                        <a href="{{ route('admin.students.create') }}"
                                           class="btn btn-primary filter-btn">

                                            <i class="bi bi-person-plus-fill me-1"></i>
                                            Add Student

                                        </a>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($students->hasPages())

            <div class="card-body border-top">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <small class="text-muted">

                        Showing
                        <strong>{{ $students->firstItem() }}</strong>
                        to
                        <strong>{{ $students->lastItem() }}</strong>
                        of
                        <strong>{{ $students->total() }}</strong>
                        students

                    </small>

                    <div>

                        {{ $students->appends(request()->query())->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection