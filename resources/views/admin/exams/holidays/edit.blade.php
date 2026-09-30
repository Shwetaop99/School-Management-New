@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div class="d-flex align-items-center">

        <div class="page-icon me-3">
            <i class="bi bi-pencil-square"></i>
        </div>

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="fw-bold mb-0">
                    Edit Exam Holiday
                </h4>

                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">
                    Edit
                </span>

            </div>

            <div class="text-muted small">
                Update the holiday date, status, or reason for this examination.
            </div>

        </div>

    </div>


    <a href="{{ route('admin.exam-holidays.index', $exam) }}"
       class="btn btn-outline-secondary px-3">

        <i class="bi bi-arrow-left me-1"></i>

        Back to Holidays

    </a>

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


    {{-- Current Holiday --}}
    <div class="col-md-4">

        <div class="summary-card h-100">

            <div class="summary-icon holiday-icon">
                <i class="bi bi-calendar-event"></i>
            </div>

            <div>

                <div class="summary-label">
                    Current Holiday
                </div>

                <div class="summary-value">
                    {{ $holiday->holiday_date->format('d M Y') }}
                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm mb-4">

        <div class="d-flex align-items-start">

            <div class="error-icon me-3">
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
    MAIN FORM CARD
========================================================== --}}
<div class="card border-0 shadow-sm holiday-card">

    {{-- Card Header --}}
    <div class="card-header bg-white border-bottom px-4 py-3">

        <div class="d-flex align-items-center">

            <div class="header-icon me-3">
                <i class="bi bi-calendar-event"></i>
            </div>

            <div>

                <h5 class="fw-bold mb-1">
                    Holiday Information
                </h5>

                <div class="text-muted small">
                    Modify the information below and save your changes.
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        FORM
    ====================================================== --}}
    <div class="card-body p-4 p-lg-5">

        <form method="POST"
              action="{{ route(
                  'admin.exam-holidays.update',
                  [$exam, $holiday]
              ) }}">

            @csrf

            @method('PUT')


            <div class="row g-4">

                {{-- =================================================
                    HOLIDAY DATE
                ================================================== --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        <i class="bi bi-calendar-date text-primary me-1"></i>

                        Holiday Date

                        <span class="text-danger">*</span>

                    </label>


                    <div class="input-group custom-input-group">

                        <span class="input-group-text">
                            <i class="bi bi-calendar3"></i>
                        </span>

                        <input
                            type="date"
                            name="holiday_date"
                            class="form-control"
                            value="{{ old(
                                'holiday_date',
                                $holiday->holiday_date->format('Y-m-d')
                            ) }}"
                            min="{{ $exam->start_date->format('Y-m-d') }}"
                            max="{{ $exam->end_date
                                ? $exam->end_date->format('Y-m-d')
                                : $exam->start_date->format('Y-m-d') }}"
                            required
                        >

                    </div>


                    <div class="form-help mt-2">

                        <i class="bi bi-info-circle me-1"></i>

                        The holiday must be within the examination period.

                    </div>


                    {{-- Exam Period --}}
                    <div class="exam-period-box mt-3">

                        <div class="d-flex align-items-center">

                            <div class="period-icon-small me-2">
                                <i class="bi bi-calendar-range"></i>
                            </div>

                            <div>

                                <div class="small text-muted">
                                    Examination Period
                                </div>

                                <div class="fw-semibold">

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


                {{-- =================================================
                    STATUS
                ================================================== --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        <i class="bi bi-toggle-on text-success me-1"></i>

                        Status

                    </label>


                    @php
                        $currentStatus = old(
                            'status',
                            $holiday->status ? '1' : '0'
                        );
                    @endphp


                    <select
                        name="status"
                        class="form-select form-select-lg custom-select"
                    >

                        <option
                            value="1"
                            {{ $currentStatus == '1' ? 'selected' : '' }}
                        >
                            Active - Non-working
                        </option>

                        <option
                            value="0"
                            {{ $currentStatus == '0' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>


                    @if($currentStatus == '1')

                        <div class="status-info active-info mt-3">

                            <div class="d-flex align-items-start">

                                <div class="status-info-icon me-3">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        Active holiday
                                    </div>

                                    <div class="small text-muted">
                                        This date will be treated as a
                                        non-working day when generating
                                        the examination timetable.
                                    </div>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="status-info inactive-info mt-3">

                            <div class="d-flex align-items-start">

                                <div class="status-info-icon inactive-icon me-3">
                                    <i class="bi bi-pause-circle-fill"></i>
                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        Inactive holiday
                                    </div>

                                    <div class="small text-muted">
                                        This date will not be treated as
                                        an active non-working day.
                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    REASON
                ================================================== --}}
                <div class="col-12">

                    <label class="form-label fw-semibold">

                        <i class="bi bi-chat-left-text text-primary me-1"></i>

                        Reason

                        <span class="text-muted fw-normal">
                            (Optional)
                        </span>

                    </label>


                    <input
                        type="text"
                        name="reason"
                        class="form-control form-control-lg"
                        value="{{ old(
                            'reason',
                            $holiday->reason
                        ) }}"
                        placeholder="Example: Sunday, Diwali Holiday, Public Holiday"
                        maxlength="255"
                    >


                    <div class="form-help mt-2">

                        <i class="bi bi-info-circle me-1"></i>

                        Update the reason if the purpose of the holiday
                        has changed.

                    </div>

                </div>

            </div>


            {{-- =====================================================
                CURRENT RECORD INFORMATION
            ====================================================== --}}
            <div class="record-info mt-4">

                <div class="record-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <div class="fw-semibold mb-1">
                        Holiday Record
                    </div>

                    <div class="small text-muted">

                        You are editing the existing holiday record.
                        Your changes will replace the current information
                        after clicking <strong>Update Holiday</strong>.

                    </div>

                </div>

            </div>


            {{-- =====================================================
                DIVIDER
            ====================================================== --}}
            <hr class="my-4">


            {{-- =====================================================
                ACTIONS
            ====================================================== --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div class="small text-muted">

                    <i class="bi bi-shield-check text-success me-1"></i>

                    Changes are saved securely.

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'admin.exam-holidays.index',
                            $exam
                        ) }}"
                        class="btn btn-light border px-4"
                    >

                        <i class="bi bi-x-lg me-1"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary px-4 save-btn"
                    >

                        <i class="bi bi-check2-circle me-2"></i>

                        Update Holiday

                    </button>

                </div>

            </div>

        </form>

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

        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);

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


    .holiday-icon {

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
       FORM
    --------------------------------------------------------- */

    .form-label {

        color: #343a40;

        margin-bottom: 9px;

        font-size: 14px;

    }


    .form-control,
    .form-select {

        border-color: #dee2e6;

        border-radius: 8px;

    }


    .form-control:focus,
    .form-select:focus {

        border-color: #86b7fe;

        box-shadow:
            0 0 0 0.2rem rgba(13, 110, 253, 0.10);

    }


    .form-control-lg,
    .form-select-lg {

        min-height: 46px;

        font-size: 14px;

    }


    /* ---------------------------------------------------------
       DATE INPUT
    --------------------------------------------------------- */

    .custom-input-group {

        border-radius: 8px;

        overflow: hidden;

    }


    .custom-input-group .input-group-text {

        background: #f8f9fa;

        border-color: #dee2e6;

        color: #6c757d;

        min-width: 46px;

        justify-content: center;

    }


    .custom-input-group .form-control {

        border-left: 0;

    }


    .custom-input-group:focus-within {

        box-shadow:
            0 0 0 0.2rem rgba(13, 110, 253, 0.10);

    }


    .custom-input-group:focus-within .input-group-text,
    .custom-input-group:focus-within .form-control {

        border-color: #86b7fe;

    }


    /* ---------------------------------------------------------
       FORM HELP
    --------------------------------------------------------- */

    .form-help {

        font-size: 12px;

        color: #6c757d;

    }


    /* ---------------------------------------------------------
       EXAM PERIOD
    --------------------------------------------------------- */

    .exam-period-box {

        padding: 12px 14px;

        border-radius: 9px;

        background: #f8fbff;

        border: 1px solid #e3efff;

    }


    .period-icon-small {

        width: 32px;
        height: 32px;

        border-radius: 8px;

        background: #e8f1ff;

        color: #0d6efd;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    /* ---------------------------------------------------------
       STATUS INFORMATION
    --------------------------------------------------------- */

    .status-info {

        padding: 13px 15px;

        border-radius: 9px;

        border: 1px solid;

    }


    .active-info {

        background: #f7fcf9;

        border-color: #dff3e6;

    }


    .inactive-info {

        background: #f8f9fa;

        border-color: #e5e7eb;

    }


    .status-info-icon {

        width: 32px;
        height: 32px;

        border-radius: 8px;

        background: #e5f7eb;

        color: #198754;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    .inactive-icon {

        background: #e9ecef;

        color: #6c757d;

    }


    /* ---------------------------------------------------------
       RECORD INFORMATION
    --------------------------------------------------------- */

    .record-info {

        display: flex;

        align-items: flex-start;

        gap: 13px;

        padding: 15px 17px;

        border-radius: 10px;

        background: #f8fbff;

        border: 1px solid #e3efff;

    }


    .record-icon {

        width: 34px;
        height: 34px;

        border-radius: 8px;

        background: #e8f1ff;

        color: #0d6efd;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    /* ---------------------------------------------------------
       ERROR
    --------------------------------------------------------- */

    .error-icon {

        width: 38px;
        height: 38px;

        border-radius: 9px;

        background: rgba(220, 53, 69, 0.10);

        color: #dc3545;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    /* ---------------------------------------------------------
       BUTTON
    --------------------------------------------------------- */

    .save-btn {

        min-height: 43px;

        border-radius: 8px;

        font-weight: 600;

        transition: all 0.2s ease;

    }


    .save-btn:hover {

        transform: translateY(-1px);

        box-shadow:
            0 5px 14px rgba(13, 110, 253, 0.20);

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


        .card-body {

            padding: 20px !important;

        }


        .d-flex.justify-content-between {

            align-items: stretch;

        }


        .save-btn,
        .btn-light {

            min-width: 0;

        }

    }

</style>

@endsection
