@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div class="d-flex align-items-center">

        <div class="page-icon me-3">
            <i class="bi bi-calendar-x-fill"></i>
        </div>

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="fw-bold mb-0">
                    Exam Holidays
                </h4>

                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">
                    {{ $holidays->count() }} Holidays
                </span>

            </div>

            <div class="text-muted small">
                Manage non-working days for the examination timetable.
            </div>

        </div>

    </div>


    <div class="d-flex flex-wrap gap-2">

        <a href="{{ route('admin.exams.show', $exam) }}"
           class="btn btn-outline-secondary px-3">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Exam

        </a>


        <a href="{{ route('admin.exam-holidays.create', $exam) }}"
           class="btn btn-primary px-3">

            <i class="bi bi-plus-lg me-1"></i>

            Add Holiday

        </a>

    </div>

</div>


{{-- =========================================================
    EXAM SUMMARY
========================================================== --}}
<div class="row g-3 mb-4">

    {{-- Examination --}}
    <div class="col-md-4">

        <div class="summary-card h-100">

            <div class="summary-icon exam-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div>

                <div class="summary-label">
                    Examination
                </div>

                <div class="summary-value">
                    {{ $exam->exam_name }}
                </div>

            </div>

        </div>

    </div>


    {{-- Academic Year --}}
    <div class="col-md-4">

        <div class="summary-card h-100">

            <div class="summary-icon year-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div>

                <div class="summary-label">
                    Academic Year
                </div>

                <div class="summary-value">
                    {{ $exam->academic_year }}
                </div>

            </div>

        </div>

    </div>


    {{-- Exam Period --}}
    <div class="col-md-4">

        <div class="summary-card h-100">

            <div class="summary-icon period-icon">
                <i class="bi bi-calendar-range"></i>
            </div>

            <div>

                <div class="summary-label">
                    Exam Period
                </div>

                <div class="summary-value">

                    {{ $exam->start_date->format('d M Y') }}

                    @if($exam->end_date)

                        <span class="text-muted mx-1">
                            →
                        </span>

                        {{ $exam->end_date->format('d M Y') }}

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4"
         role="alert">

        <div class="d-flex align-items-center">

            <div class="alert-icon success-icon me-3">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>

                <div class="fw-semibold">
                    Success
                </div>

                <div class="small">
                    {{ session('success') }}
                </div>

            </div>

        </div>


        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm mb-4">

        <div class="d-flex align-items-start">

            <div class="alert-icon error-icon me-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>

                <div class="fw-semibold mb-1">
                    Please correct the following:
                </div>

                <ul class="mb-0 ps-3">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    </div>

@endif


{{-- =========================================================
    INFORMATION NOTICE
========================================================== --}}
<div class="schedule-notice mb-4">

    <div class="notice-icon">
        <i class="bi bi-info-circle-fill"></i>
    </div>

    <div>

        <div class="fw-semibold mb-1">
            Timetable Scheduling
        </div>

        <div class="small text-muted">
            Active holidays added here will automatically be skipped
            when generating the examination timetable.
        </div>

    </div>

</div>


{{-- =========================================================
    HOLIDAYS CARD
========================================================== --}}
<div class="card border-0 shadow-sm holiday-card">

    {{-- Card Header --}}
    <div class="card-header bg-white border-bottom px-4 py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div class="d-flex align-items-center">

                <div class="header-icon me-3">
                    <i class="bi bi-calendar-week"></i>
                </div>

                <div>

                    <h5 class="fw-bold mb-1">
                        Non-working Days
                    </h5>

                    <div class="text-muted small">
                        Holidays configured for this examination.
                    </div>

                </div>

            </div>


            @if($holidays->count())

                <div class="holiday-count">

                    <span class="text-muted small me-2">
                        Total
                    </span>

                    <span class="badge bg-primary rounded-pill px-3">
                        {{ $holidays->count() }}
                    </span>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
        TABLE / EMPTY STATE
    ====================================================== --}}
    <div class="card-body p-0">

        @if($holidays->count())

            <div class="table-responsive">

                <table class="table holiday-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th class="number-column">
                                #
                            </th>

                            <th>
                                Holiday Date
                            </th>

                            <th>
                                Day
                            </th>

                            <th>
                                Reason
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="actions-column">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($holidays as $holiday)

                            <tr class="holiday-row">

                                {{-- Number --}}
                                <td>

                                    <span class="row-number">
                                        {{ $loop->iteration }}
                                    </span>

                                </td>


                                {{-- Date --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="date-icon me-3">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">

                                                {{ $holiday->holiday_date->format('d M Y') }}

                                            </div>

                                            <div class="small text-muted">

                                                Holiday Date

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Day --}}
                                <td>

                                    <span class="day-badge">

                                        <i class="bi bi-calendar3 me-1"></i>

                                        {{ $holiday->holiday_date->format('l') }}

                                    </span>

                                </td>


                                {{-- Reason --}}
                                <td>

                                    @if($holiday->reason)

                                        <div class="reason-text">

                                            <i class="bi bi-chat-left-text me-2"></i>

                                            {{ $holiday->reason }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($holiday->status)

                                        <span class="status-badge active">

                                            <span class="status-dot"></span>

                                            Non-working

                                        </span>

                                    @else

                                        <span class="status-badge inactive">

                                            <span class="status-dot"></span>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        {{-- Edit --}}
                                        <a
                                            href="{{ route(
                                                'admin.exam-holidays.edit',
                                                [$exam, $holiday]
                                            ) }}"
                                            class="action-btn edit-btn"
                                            title="Edit Holiday"
                                        >

                                            <i class="bi bi-pencil"></i>

                                            <span class="d-none d-xl-inline">
                                                Edit
                                            </span>

                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.exam-holidays.destroy',
                                                [$exam, $holiday]
                                            ) }}"
                                            class="delete-form"
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="action-btn delete-btn"
                                                title="Delete Holiday"
                                            >

                                                <i class="bi bi-trash3"></i>

                                                <span class="d-none d-xl-inline">
                                                    Delete
                                                </span>

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

            {{-- =================================================
                EMPTY STATE
            ================================================== --}}
            <div class="empty-state">

                <div class="empty-icon">

                    <i class="bi bi-calendar-check"></i>

                </div>


                <h5 class="fw-bold text-dark mt-4 mb-2">
                    No Holidays Added
                </h5>


                <p class="text-muted mb-4">

                    No non-working days have been configured for this
                    examination yet.

                    <br>

                    You can add Sundays, public holidays, school holidays,
                    or other non-working days.

                </p>


                <a
                    href="{{ route(
                        'admin.exam-holidays.create',
                        $exam
                    ) }}"
                    class="btn btn-primary px-4"
                >

                    <i class="bi bi-plus-lg me-1"></i>

                    Add First Holiday

                </a>

            </div>

        @endif

    </div>

</div>


</div>

{{-- =============================================================
STYLES
============================================================= --}}

<style>

    /* ---------------------------------------------------------
       PAGE HEADER
    --------------------------------------------------------- */

    .page-icon {

        width: 48px;
        height: 48px;

        border-radius: 12px;

        background: rgba(13, 110, 253, 0.10);

        color: #0d6efd;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 21px;

    }


    /* ---------------------------------------------------------
       SUMMARY CARDS
    --------------------------------------------------------- */

    .summary-card {

        display: flex;
        align-items: center;

        gap: 14px;

        padding: 18px 20px;

        background: #ffffff;

        border: 1px solid #e9ecef;

        border-radius: 12px;

        box-shadow:
            0 2px 8px rgba(0, 0, 0, 0.03);

        transition: all 0.2s ease;

    }


    .summary-card:hover {

        transform: translateY(-2px);

        box-shadow:
            0 7px 18px rgba(0, 0, 0, 0.07);

    }


    .summary-icon {

        width: 44px;
        height: 44px;

        border-radius: 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 19px;

        flex-shrink: 0;

    }


    .exam-icon {

        background: #eef5ff;
        color: #0d6efd;

    }


    .year-icon {

        background: #eaf8f0;
        color: #198754;

    }


    .period-icon {

        background: #fff4df;
        color: #d98b00;

    }


    .summary-label {

        color: #6c757d;

        font-size: 12px;

        margin-bottom: 3px;

    }


    .summary-value {

        color: #212529;

        font-size: 14px;

        font-weight: 600;

    }


    /* ---------------------------------------------------------
       NOTIFICATION
    --------------------------------------------------------- */

    .schedule-notice {

        display: flex;

        align-items: flex-start;

        gap: 13px;

        padding: 15px 17px;

        border-radius: 10px;

        background: #f8fbff;

        border: 1px solid #e1edff;

    }


    .notice-icon {

        width: 35px;
        height: 35px;

        border-radius: 8px;

        background: #e8f1ff;

        color: #0d6efd;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    /* ---------------------------------------------------------
       MAIN CARD
    --------------------------------------------------------- */

    .holiday-card {

        border-radius: 14px;

        overflow: hidden;

    }


    .header-icon {

        width: 40px;
        height: 40px;

        border-radius: 10px;

        background: #eef5ff;

        color: #0d6efd;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 17px;

    }


    /* ---------------------------------------------------------
       TABLE
    --------------------------------------------------------- */

    .holiday-table {

        min-width: 900px;

    }


    .holiday-table thead th {

        background: #f8f9fa;

        border-bottom: 1px solid #e5e7eb;

        color: #6c757d;

        font-size: 12px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.04em;

        padding: 15px 20px;

        white-space: nowrap;

    }


    .holiday-table tbody td {

        padding: 17px 20px;

        border-bottom: 1px solid #f0f1f3;

    }


    .holiday-table tbody tr:last-child td {

        border-bottom: 0;

    }


    .holiday-row {

        transition: background 0.15s ease;

    }


    .holiday-row:hover {

        background: #f8fbff;

    }


    .number-column {

        width: 65px;

    }


    .actions-column {

        width: 180px;

    }


    /* ---------------------------------------------------------
       NUMBER
    --------------------------------------------------------- */

    .row-number {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        width: 30px;
        height: 30px;

        border-radius: 8px;

        background: #f1f3f5;

        color: #6c757d;

        font-size: 12px;

        font-weight: 600;

    }


    /* ---------------------------------------------------------
       DATE
    --------------------------------------------------------- */

    .date-icon {

        width: 40px;
        height: 40px;

        border-radius: 9px;

        background: #fff4df;

        color: #d98b00;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    /* ---------------------------------------------------------
       DAY
    --------------------------------------------------------- */

    .day-badge {

        display: inline-flex;

        align-items: center;

        padding: 6px 10px;

        border-radius: 7px;

        background: #f8f9fa;

        border: 1px solid #e3e6e9;

        color: #495057;

        font-size: 12px;

        font-weight: 600;

        white-space: nowrap;

    }


    /* ---------------------------------------------------------
       REASON
    --------------------------------------------------------- */

    .reason-text {

        display: flex;

        align-items: center;

        color: #495057;

        font-size: 13px;

    }


    .reason-text i {

        color: #94a3b8;

    }


    /* ---------------------------------------------------------
       STATUS
    --------------------------------------------------------- */

    .status-badge {

        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 6px 11px;

        border-radius: 20px;

        font-size: 12px;

        font-weight: 600;

        white-space: nowrap;

    }


    .status-badge.active {

        background: #fff0f0;

        color: #dc3545;

    }


    .status-badge.inactive {

        background: #f1f3f5;

        color: #6c757d;

    }


    .status-dot {

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: currentColor;

    }


    /* ---------------------------------------------------------
       ACTION BUTTONS
    --------------------------------------------------------- */

    .action-btn {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        min-height: 35px;

        padding: 5px 10px;

        border-radius: 7px;

        border: 1px solid;

        background: #ffffff;

        text-decoration: none;

        font-size: 12px;

        font-weight: 600;

        cursor: pointer;

        transition: all 0.15s ease;

    }


    .edit-btn {

        color: #0d6efd;

        border-color: #b9d3ff;

    }


    .edit-btn:hover {

        background: #eef5ff;

        border-color: #86b7fe;

    }


    .delete-btn {

        color: #dc3545;

        border-color: #f1b7bd;

    }


    .delete-btn:hover {

        background: #fff1f2;

        border-color: #dc3545;

    }


    .delete-form {

        margin: 0;

    }


    /* ---------------------------------------------------------
       ALERT ICONS
    --------------------------------------------------------- */

    .alert-icon {

        width: 38px;
        height: 38px;

        border-radius: 9px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    .success-icon {

        background: rgba(25, 135, 84, 0.10);

        color: #198754;

    }


    .error-icon {

        background: rgba(220, 53, 69, 0.10);

        color: #dc3545;

    }


    /* ---------------------------------------------------------
       EMPTY STATE
    --------------------------------------------------------- */

    .empty-state {

        min-height: 340px;

        padding: 50px 20px;

        display: flex;

        flex-direction: column;

        justify-content: center;

        align-items: center;

        text-align: center;

    }


    .empty-icon {

        width: 82px;
        height: 82px;

        border-radius: 50%;

        background: #f1f5f9;

        color: #94a3b8;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 34px;

    }


    /* ---------------------------------------------------------
       MOBILE
    --------------------------------------------------------- */

    @media (max-width: 767.98px) {

        .container-fluid {

            padding-left: 14px !important;

            padding-right: 14px !important;

        }


        .page-icon {

            width: 42px;
            height: 42px;

            font-size: 18px;

        }


        .summary-card {

            padding: 15px;

        }


        .holiday-table {

            min-width: 900px;

        }


        .empty-state {

            padding: 40px 18px;

        }

    }

</style>

{{-- =============================================================
DELETE CONFIRMATION
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteForms =
        document.querySelectorAll('.delete-form');


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const confirmed = confirm(
                'Are you sure you want to delete this holiday?'
            );


            if (!confirmed) {

                event.preventDefault();

            }

        });

    });

});

</script>

@endsection
