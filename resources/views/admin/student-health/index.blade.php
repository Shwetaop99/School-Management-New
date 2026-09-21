@extends('layouts.app')

@section('content')

<style>
    .health-page {
        padding: 24px 0;
    }

    .health-header {
        background: linear-gradient(135deg, #198754, #157347);
        color: #fff;
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .health-header h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
    }

    .health-header p {
        margin: 6px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .filter-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        border: 1px solid #eef0f2;
    }

    .filter-card label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 7px;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        height: 42px;
        border-radius: 9px;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.15rem rgba(25, 135, 84, 0.12);
    }

    .btn-filter {
        height: 42px;
        border-radius: 9px;
        font-weight: 600;
        padding: 0 20px;
    }

    .btn-reset {
        height: 42px;
        border-radius: 9px;
        padding: 0 18px;
        font-weight: 600;
    }

    .records-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #eef0f2;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    .records-header {
        padding: 18px 22px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .records-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #212529;
    }

    .record-count {
        background: #e9f7ef;
        color: #198754;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .health-table {
        width: 100%;
        margin: 0;
        min-width: 1100px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .health-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 14px 13px;
        border-bottom: 1px solid #dee2e6;
        white-space: nowrap;
    }

    .health-table tbody td {
        padding: 14px 13px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f1f2;
        font-size: 13px;
        color: #343a40;
    }

    .health-table tbody tr:hover {
        background: #fafdfb;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 220px;
    }

    .student-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
        flex-shrink: 0;
    }

    .student-avatar-placeholder {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #e9f7ef;
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .student-name {
        font-weight: 700;
        color: #212529;
        margin-bottom: 2px;
    }

    .student-id {
        font-size: 11px;
        color: #6c757d;
    }

    .class-badge {
        display: inline-block;
        padding: 5px 9px;
        background: #f1f3f5;
        color: #495057;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
    }

    .section-badge {
        display: inline-block;
        padding: 5px 9px;
        background: #e7f1ff;
        color: #0d6efd;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
    }

    .health-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .health-status.good {
        background: #e8f7ee;
        color: #198754;
    }

    .health-status.attention {
        background: #fff3cd;
        color: #997404;
    }

    .health-status.referred {
        background: #f8d7da;
        color: #b02a37;
    }

    .health-status.unknown {
        background: #e9ecef;
        color: #6c757d;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid #dee2e6;
        background: #fff;
        transition: 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .action-add {
        color: #198754;
        border-color: #b7dfc8;
        background: #f3fbf6;
    }

    .action-add:hover {
        background: #e8f7ee;
    }

    .action-view {
        color: #0d6efd;
    }

    .action-view:hover {
        background: #e7f1ff;
    }

    .action-edit {
        color: #198754;
    }

    .action-edit:hover {
        background: #e8f7ee;
    }

    .action-print {
        color: #6f42c1;
    }

    .action-print:hover {
        background: #f1ebfa;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: #e9f7ef;
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .empty-state h5 {
        font-weight: 700;
        margin-bottom: 7px;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 14px;
        margin: 0 0 18px;
    }

    .pagination-wrapper {
        padding: 18px 22px;
        border-top: 1px solid #edf0f2;
    }

    .pagination-wrapper .pagination {
        margin: 0;
    }

    .health-year {
        font-size: 12px;
        font-weight: 600;
        color: #495057;
    }

    @media (max-width: 767px) {
        .health-page {
            padding: 15px 0;
        }

        .health-header {
            padding: 20px;
        }

        .health-header h2 {
            font-size: 21px;
        }

        .records-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="container-fluid health-page">


{{-- =========================================================
     HEADER
========================================================== --}}
<div class="health-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h2>
                <i class="fas fa-heartbeat me-2"></i>
                Student Health Records
            </h2>

            <p>
                Manage annual student health checkup records
            </p>
        </div>

        <a
            href="{{ route('admin.students.index') }}"
            class="btn btn-light"
        >
            <i class="fas fa-user-plus me-1"></i>
            Select Student
        </a>

    </div>

</div>


{{-- =========================================================
     SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show rounded-3"
        role="alert"
    >
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>
    </div>

@endif


{{-- =========================================================
     FILTERS
========================================================== --}}
<div class="filter-card">

    <form
        method="GET"
        action="{{ route('admin.student-health.index') }}"
    >

        <div class="row g-3 align-items-end">

            {{-- Academic Year --}}
            <div class="col-xl-2 col-lg-3 col-md-6">

                <label for="academic_year">
                    Academic Year
                </label>

                <select
                    name="academic_year"
                    id="academic_year"
                    class="form-select"
                >

                    <option value="">
                        All Years
                    </option>

                    @foreach($academicYears as $year)

                        <option
                            value="{{ $year }}"
                            {{ request('academic_year') == $year ? 'selected' : '' }}
                        >
                            {{ $year }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Class --}}
            <div class="col-xl-2 col-lg-3 col-md-6">

                <label for="class">
                    Class
                </label>

                <select
                    name="class"
                    id="class"
                    class="form-select"
                >

                    <option value="">
                        All Classes
                    </option>

                    @foreach($classes as $class)

                        <option
                            value="{{ $class }}"
                            {{ request('class') == $class ? 'selected' : '' }}
                        >
                            Class {{ $class }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Section --}}
            <div class="col-xl-2 col-lg-3 col-md-6">

                <label for="section">
                    Section
                </label>

                <select
                    name="section"
                    id="section"
                    class="form-select"
                >

                    <option value="">
                        All Sections
                    </option>

                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $section)

                        <option
                            value="{{ $section }}"
                            {{ request('section') == $section ? 'selected' : '' }}
                        >
                            Section {{ $section }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Search --}}
            <div class="col-xl-4 col-lg-3 col-md-6">

                <label for="search">
                    Search Student
                </label>

                <input
                    type="text"
                    name="search"
                    id="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Student ID or student name..."
                >

            </div>


            {{-- Buttons --}}
            <div class="col-xl-2 col-lg-12 col-md-12">

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-success btn-filter"
                    >
                        <i class="fas fa-filter me-1"></i>
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.student-health.index') }}"
                        class="btn btn-outline-secondary btn-reset"
                        title="Reset"
                    >
                        <i class="fas fa-rotate-left"></i>
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =========================================================
     STUDENTS / HEALTH RECORDS
========================================================== --}}
<div class="records-card">

    <div class="records-header">

        <h5>
            <i class="fas fa-notes-medical text-success me-2"></i>
            Students Health Checkup
        </h5>

        <span class="record-count">
            {{ $students->total() }} Students
        </span>

    </div>


    @if($students->count())

        <div class="table-responsive">

            <table class="health-table">

                <thead>

                    <tr>

                        <th>
                            Student
                        </th>

                        <th>
                            Class
                        </th>

                        <th>
                            Section
                        </th>

                        <th>
                            Current Academic Year
                        </th>

                        <th>
                            Latest Checkup
                        </th>

                        <th>
                            Height
                        </th>

                        <th>
                            Weight
                        </th>

                        <th>
                            BMI
                        </th>

                        <th>
                            Health Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($students as $student)

                        @php

                            $studentName = trim(
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

                            $latestRecord = $student->healthRecords
                                ->sortByDesc('checkup_date')
                                ->first();

                            $status = strtolower(
                                $latestRecord->overall_health_status ?? ''
                            );

                        @endphp


                        <tr>

                            {{-- Student --}}
                            <td>

                                <div class="student-info">

                                    @if(!empty($student->profile_image))

                                        <img
                                            src="{{ $student->profile_image }}"
                                            alt="{{ $studentName }}"
                                            class="student-avatar"
                                        >

                                    @else

                                        <div class="student-avatar-placeholder">
                                            {{ $initial }}
                                        </div>

                                    @endif


                                    <div>

                                        <div class="student-name">
                                            {{ $studentName ?: 'N/A' }}
                                        </div>

                                        <div class="student-id">
                                            {{ $student->student_id ?? 'N/A' }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Class --}}
                            <td>

                                <span class="class-badge">
                                    {{ $student->class ?? '-' }}
                                </span>

                            </td>


                            {{-- Section --}}
                            <td>

                                <span class="section-badge">
                                    {{ $student->section ?? '-' }}
                                </span>

                            </td>


                            {{-- Current Academic Year --}}
                            <td>

                                <span class="health-year">
                                    {{ $student->academic_year ?? '-' }}
                                </span>

                            </td>


                            {{-- Latest Checkup --}}
                            <td>

                                @if($latestRecord)

                                    {{ $latestRecord->checkup_date
                                        ? $latestRecord->checkup_date->format('d M Y')
                                        : '-'
                                    }}

                                    <div class="text-muted small">
                                        {{ $latestRecord->academic_year }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        Not Checked
                                    </span>

                                @endif

                            </td>


                            {{-- Height --}}
                            <td>

                                @if($latestRecord && $latestRecord->height !== null)

                                    {{ $latestRecord->height }} cm

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Weight --}}
                            <td>

                                @if($latestRecord && $latestRecord->weight !== null)

                                    {{ $latestRecord->weight }} kg

                                @else

                                    -

                                @endif

                            </td>


                            {{-- BMI --}}
                            <td>

                                @if($latestRecord && $latestRecord->bmi !== null)

                                    {{ $latestRecord->bmi }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Health Status --}}
                            <td>

                                @if($latestRecord && $latestRecord->overall_health_status)

                                    @if(
                                        str_contains($status, 'good') ||
                                        str_contains($status, 'normal') ||
                                        str_contains($status, 'healthy')
                                    )

                                        <span class="health-status good">

                                            <i class="fas fa-circle-check"></i>

                                            {{ $latestRecord->overall_health_status }}

                                        </span>

                                    @elseif(
                                        str_contains($status, 'refer') ||
                                        str_contains($status, 'poor')
                                    )

                                        <span class="health-status referred">

                                            <i class="fas fa-circle-exclamation"></i>

                                            {{ $latestRecord->overall_health_status }}

                                        </span>

                                    @else

                                        <span class="health-status attention">

                                            <i class="fas fa-triangle-exclamation"></i>

                                            {{ $latestRecord->overall_health_status }}

                                        </span>

                                    @endif

                                @else

                                    <span class="health-status unknown">

                                        <i class="fas fa-circle-minus"></i>

                                        Not Recorded

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- ADD HEALTH RECORD --}}
                                    <a
                                        href="{{ route(
                                            'admin.student-health.create',
                                            $student
                                        ) }}"
                                        class="action-btn action-add"
                                        title="Add Health Record"
                                    >
                                        <i class="fas fa-plus"></i>
                                    </a>


                                    @if($latestRecord)

                                        {{-- VIEW --}}
                                        <a
                                            href="{{ route(
                                                'admin.student-health.show',
                                                $latestRecord
                                            ) }}"
                                            class="action-btn action-view"
                                            title="View Latest Record"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route(
                                                'admin.student-health.edit',
                                                $latestRecord
                                            ) }}"
                                            class="action-btn action-edit"
                                            title="Edit Latest Record"
                                        >
                                            <i class="fas fa-pen"></i>
                                        </a>


                                        {{-- PRINT --}}
                                        <a
                                            href="{{ route(
                                                'admin.student-health.print',
                                                $latestRecord
                                            ) }}"
                                            target="_blank"
                                            class="action-btn action-print"
                                            title="Print Latest Record"
                                        >
                                            <i class="fas fa-print"></i>
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="pagination-wrapper">

            {{ $students->withQueryString()->links() }}

        </div>


    @else

        <div class="empty-state">

            <div class="empty-icon">

                <i class="fas fa-user-graduate"></i>

            </div>

            <h5>
                No Students Found
            </h5>

            <p>
                No students match your selected filters.
            </p>

            <a
                href="{{ route('admin.students.index') }}"
                class="btn btn-success"
            >
                <i class="fas fa-users me-1"></i>
                Go to Students
            </a>

        </div>

    @endif

</div>


</div>

@endsection
