@extends('layouts.app')

@section('title', 'Examinations')

@section('content')

<style>
    .exam-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        padding: 28px;
    }

    /* Header */
    .exam-header {
        margin-bottom: 25px;
    }

    .exam-title-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1677f0, #4f9cff);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 8px 20px rgba(22, 119, 240, .20);
    }

    .exam-title {
        font-size: 25px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .exam-subtitle {
        color: #7b8494;
        font-size: 14px;
        margin: 3px 0 0;
    }

    .btn-create-exam {
        border: 0;
        border-radius: 10px;
        padding: 11px 18px;
        font-weight: 600;
        box-shadow: 0 6px 15px rgba(22, 119, 240, .18);
    }

    /* Summary Cards */
    .summary-card {
        background: #fff;
        border: 0;
        border-radius: 16px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 5px 18px rgba(30, 50, 80, .06);
        transition: all .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(30, 50, 80, .10);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .summary-number {
        font-size: 25px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }

    .summary-label {
        color: #8992a3;
        font-size: 13px;
        margin-top: 5px;
    }

    /* Main Card */
    .exam-card {
        background: #fff;
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(30, 50, 80, .07);
    }

    .exam-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f5;
    }

    .section-title {
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .section-description {
        font-size: 13px;
        color: #8992a3;
    }

    /* Table */
    .exam-table {
        margin-bottom: 0;
    }

    .exam-table thead th {
        background: #f8faff;
        border-bottom: 1px solid #e9edf4;
        color: #687386;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
        padding: 15px 14px;
        white-space: nowrap;
    }

    .exam-table tbody td {
        padding: 17px 14px;
        border-bottom: 1px solid #f0f2f6;
        color: #374151;
        font-size: 14px;
    }

    .exam-table tbody tr {
        transition: background .15s ease;
    }

    .exam-table tbody tr:hover {
        background: #f9fbff;
    }

    .exam-number {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f0f6ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
    }

    .exam-icon {
        width: 43px;
        height: 43px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eaf3ff, #f5f9ff);
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .exam-name {
        font-weight: 700;
        color: #252b36;
        margin-bottom: 3px;
    }

    .exam-created {
        color: #9299a8;
        font-size: 12px;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 8px;
        background: #f5f6f8;
        border: 1px solid #e8ebef;
        color: #596273;
        font-size: 12px;
        font-weight: 600;
    }

    .date-text {
        font-size: 13px;
        color: #596273;
    }

    /* Action Buttons */
    .quick-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        white-space: nowrap;
    }

    .action-btn {
        height: 34px;
        padding: 0 10px;
        border-radius: 8px;
        border: 1px solid #e4e8ef;
        background: #fff;
        color: #596273;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all .15s ease;
    }

    .action-btn:hover {
        background: #f5f8ff;
        border-color: #cbdcff;
        color: #1677f0;
    }

    .action-btn.view:hover {
        color: #1677f0;
    }

    .action-btn.classes:hover {
        color: #198754;
    }

    .action-btn.timetable:hover {
        color: #6f42c1;
    }

    .action-btn.edit:hover {
        color: #d39e00;
    }

    .more-btn {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        border: 1px solid #e4e8ef;
        background: #fff;
        color: #596273;
    }

    .more-btn:hover {
        background: #f5f7fb;
    }

    .dropdown-menu {
        border: 0;
        border-radius: 12px;
        padding: 7px;
        box-shadow: 0 12px 35px rgba(0,0,0,.12);
        min-width: 190px;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 9px 11px;
        font-size: 13px;
        font-weight: 500;
    }

    .dropdown-item:hover {
        background: #f4f7fb;
    }

    /* Status */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #e9f8ef;
        color: #198754;
    }

    .status-completed {
        background: #eaf2ff;
        color: #1677f0;
    }

    .status-inactive {
        background: #f1f2f4;
        color: #6c757d;
    }

    /* Empty */
    .empty-state {
        padding: 70px 20px;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        background: #f1f5f9;
        color: #9aa3b2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 35px;
        margin: auto;
    }

    @media (max-width: 1200px) {
        .quick-actions .action-btn span {
            display: none;
        }

        .action-btn {
            width: 35px;
            justify-content: center;
            padding: 0;
        }
    }

    @media (max-width: 768px) {
        .exam-page {
            padding: 16px;
        }

        .exam-header {
            align-items: flex-start !important;
        }

        .quick-actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="exam-page">

```
{{-- =========================================================
     HEADER
========================================================== --}}
<div class="exam-header d-flex flex-wrap justify-content-between align-items-center gap-3">

    <div class="d-flex align-items-center gap-3">

        <div class="exam-title-icon">
            <i class="bi bi-journal-text"></i>
        </div>

        <div>
            <h3 class="exam-title">
                Examinations
            </h3>

            <p class="exam-subtitle">
                Manage examinations, classes, timetable and results.
            </p>
        </div>

    </div>

    <a href="{{ route('admin.exams.create') }}"
       class="btn btn-primary btn-create-exam">

        <i class="bi bi-plus-lg me-1"></i>
        Create Exam

    </a>

</div>


{{-- =========================================================
     ALERTS
========================================================== --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
         role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm"
         role="alert">

        <strong>
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please fix the following:
        </strong>

        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
     SUMMARY CARDS
========================================================== --}}

@php
    $totalExams = $exams->total();

    /*
     * These values are calculated from the current page.
     * If you later want exact database-wide counts,
     * pass them from the controller.
     */
    $activeExams = $exams->where('status', 'active')->count();
    $completedExams = $exams->where('status', 'completed')->count();
    $inactiveExams = $exams->where('status', 'inactive')->count();
@endphp

<div class="row g-3 mb-4">

    {{-- Total --}}
    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <div class="summary-number">
                        {{ $totalExams }}
                    </div>

                    <div class="summary-label">
                        Total Examinations
                    </div>
                </div>

                <div class="summary-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-journal-text"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Active --}}
    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <div class="summary-number">
                        {{ $activeExams }}
                    </div>

                    <div class="summary-label">
                        Active Exams
                    </div>
                </div>

                <div class="summary-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-play-circle"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Completed --}}
    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <div class="summary-number">
                        {{ $completedExams }}
                    </div>

                    <div class="summary-label">
                        Completed
                    </div>
                </div>

                <div class="summary-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-check2-circle"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Inactive --}}
    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <div class="summary-number">
                        {{ $inactiveExams }}
                    </div>

                    <div class="summary-label">
                        Inactive Exams
                    </div>
                </div>

                <div class="summary-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="bi bi-pause-circle"></i>
                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     EXAMINATION LIST
========================================================== --}}

<div class="exam-card">

    <div class="exam-card-header">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

            <div>

                <div class="section-title">
                    <i class="bi bi-list-check text-primary me-2"></i>
                    Examination List
                </div>

                <div class="section-description">
                    Manage your school examinations and related activities.
                </div>

            </div>

            <span class="badge rounded-pill bg-primary px-3 py-2">
                {{ $exams->total() }}
                {{ $exams->total() == 1 ? 'Exam' : 'Exams' }}
            </span>

        </div>

    </div>


    <div class="card-body p-0">

        @if($exams->count() > 0)

            <div class="table-responsive">

                <table class="table exam-table align-middle">

                    <thead>

                        <tr>

                            <th class="ps-4" style="width: 60px;">
                                #
                            </th>

                            <th>
                                Examination
                            </th>

                            <th>
                                Academic Year
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Exam Dates
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-4">
                                Quick Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($exams as $index => $exam)

                        <tr>

                            {{-- Serial --}}
                            <td class="ps-4">

                                <div class="exam-number">

                                    {{ ($exams->currentPage() - 1) * $exams->perPage() + $index + 1 }}

                                </div>

                            </td>


                            {{-- Examination --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="exam-icon me-3">
                                        <i class="bi bi-journal-check"></i>
                                    </div>

                                    <div>

                                        <div class="exam-name">
                                            {{ $exam->exam_name }}
                                        </div>

                                        <div class="exam-created">

                                            @if($exam->creator)

                                                <i class="bi bi-person me-1"></i>
                                                Created by {{ $exam->creator->name }}

                                            @else

                                                <i class="bi bi-gear me-1"></i>
                                                Created by System

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Academic Year --}}
                            <td>

                                <span class="fw-semibold">
                                    {{ $exam->academic_year }}
                                </span>

                            </td>


                            {{-- Type --}}
                            <td>

                                <span class="type-badge">

                                    <i class="bi bi-tag me-1"></i>

                                    {{ $exam->exam_type }}

                                </span>

                            </td>


                            {{-- Dates --}}
                            <td>

                                @if($exam->start_date || $exam->end_date)

                                    <div class="date-text">

                                        <i class="bi bi-calendar3 text-primary me-1"></i>

                                        @if($exam->start_date)
                                            {{ $exam->start_date->format('d M Y') }}
                                        @endif

                                        @if($exam->start_date && $exam->end_date)
                                            <span class="text-muted mx-1">→</span>
                                        @endif

                                        @if($exam->end_date)
                                            {{ $exam->end_date->format('d M Y') }}
                                        @endif

                                    </div>

                                @else

                                    <span class="text-muted small">
                                        Not specified
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($exam->status === 'active')

                                    <span class="status-badge status-active">
                                        <i class="bi bi-circle-fill"></i>
                                        Active
                                    </span>

                                @elseif($exam->status === 'completed')

                                    <span class="status-badge status-completed">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Completed
                                    </span>

                                @else

                                    <span class="status-badge status-inactive">
                                        <i class="bi bi-dash-circle-fill"></i>
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 QUICK ACTIONS
                            ================================================== --}}
                            <td class="text-end pe-4">

                                <div class="quick-actions">

                                    {{-- VIEW --}}
                                    <a href="{{ route('admin.exams.show', $exam->id) }}"
                                       class="action-btn view"
                                       title="View Examination">

                                        <i class="bi bi-eye"></i>
                                        <span>View</span>

                                    </a>


                                    {{-- MANAGE CLASSES --}}
                                    <a href="{{ route('admin.exam-classes.index', $exam->id) }}"
                                       class="action-btn classes"
                                       title="Manage Classes">

                                        <i class="bi bi-people"></i>
                                        <span>Classes</span>

                                    </a>


                                    {{-- EXAM TIMETABLE --}}
                                    <a href="{{ route('admin.exam-timetable.index', $exam->id) }}"
                                       class="action-btn timetable"
                                       title="Exam Timetable">

                                        <i class="bi bi-calendar3"></i>
                                        <span>Timetable</span>

                                    </a>


                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.exams.edit', $exam->id) }}"
                                       class="action-btn edit"
                                       title="Edit Examination">

                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>

                                    </a>


                                    {{-- MORE --}}
                                    <div class="dropdown">

                                        <button class="more-btn"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                title="More Actions">

                                            <i class="bi bi-three-dots-vertical"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.exams.show', $exam->id) }}">

                                                    <i class="bi bi-eye text-primary me-2"></i>
                                                    View Examination

                                                </a>

                                            </li>


                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.exam-classes.index', $exam->id) }}">

                                                    <i class="bi bi-people text-success me-2"></i>
                                                    Manage Classes

                                                </a>

                                            </li>


                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.exam-timetable.index', $exam->id) }}">

                                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                                    Exam Timetable

                                                </a>

                                            </li>


                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.exams.edit', $exam->id) }}">

                                                    <i class="bi bi-pencil-square text-warning me-2"></i>
                                                    Edit Examination

                                                </a>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            <li>

                                                <form method="POST"
                                                      action="{{ route('admin.exams.destroy', $exam->id) }}"
                                                      onsubmit="return confirm('Are you sure you want to delete this examination?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="dropdown-item text-danger">

                                                        <i class="bi bi-trash me-2"></i>
                                                        Delete Examination

                                                    </button>

                                                </form>

                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($exams->hasPages())

                <div class="px-4 py-3 border-top">

                    {{ $exams->links() }}

                </div>

            @endif


        @else

            {{-- Empty State --}}

            <div class="empty-state text-center">

                <div class="empty-icon mb-3">

                    <i class="bi bi-journal-x"></i>

                </div>

                <h5 class="fw-bold mb-2">
                    No Examinations Found
                </h5>

                <p class="text-muted mb-4">
                    You have not created any examinations yet.
                </p>

                <a href="{{ route('admin.exams.create') }}"
                   class="btn btn-primary btn-create-exam">

                    <i class="bi bi-plus-lg me-1"></i>
                    Create First Exam

                </a>

            </div>

        @endif

    </div>

</div>
```

</div>

@endsection
