@extends('layouts.app')

@section('title', 'Add Exam Session')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
     PAGE HEADER
========================================================== --}}

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>

        <div class="d-flex align-items-center gap-2 mb-2">

            <div class="page-icon">
                <i class="bi bi-clock"></i>
            </div>

            <div>

                <h4 class="fw-bold mb-0">
                    Add Exam Session
                </h4>

                <div class="text-muted small mt-1">
                    Create a time slot for this examination
                </div>

            </div>

        </div>

    </div>


    <a href="{{ route('admin.exam-sessions.index', $exam) }}"
       class="btn btn-outline-secondary px-3">

        <i class="bi bi-arrow-left me-1"></i>

        Back to Sessions

    </a>

</div>


{{-- =========================================================
     EXAM INFORMATION
========================================================== --}}

<div class="exam-summary mb-4">

    <div class="row align-items-center">

        <div class="col-lg-8">

            <div class="d-flex align-items-center">

                <div class="exam-icon me-3">
                    <i class="bi bi-journal-text"></i>
                </div>

                <div>

                    <div class="small text-uppercase text-muted fw-semibold">
                        Examination
                    </div>

                    <div class="fw-bold exam-name">
                        {{ $exam->exam_name }}
                    </div>

                    <div class="small text-muted mt-1">

                        <span>
                            <i class="bi bi-calendar3 me-1"></i>
                            Academic Year:
                            <strong>{{ $exam->academic_year }}</strong>
                        </span>

                        @if($exam->exam_type)

                            <span class="mx-2">•</span>

                            <span>
                                <i class="bi bi-tag me-1"></i>
                                {{ $exam->exam_type }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-4 mt-3 mt-lg-0">

            <div class="info-box">

                <div class="info-box-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <div class="small text-muted">
                        Session
                    </div>

                    <div class="fw-semibold">
                        Time Slot Configuration
                    </div>

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

            <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>

            <div class="flex-grow-1">

                <div class="fw-bold mb-1">
                    Please correct the following errors:
                </div>

                <ul class="mb-0 ps-3">

                    @foreach($errors->all() as $error)

                        <li class="mb-1">
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

<div class="card border-0 shadow-sm form-card">

    {{-- Card Header --}}

    <div class="card-header bg-white border-bottom px-4 py-3">

        <div class="d-flex align-items-center">

            <div class="section-icon me-3">
                <i class="bi bi-sliders"></i>
            </div>

            <div>

                <h6 class="fw-bold mb-1">
                    Session Information
                </h6>

                <div class="text-muted small">
                    Define the session name, timing and status.
                </div>

            </div>

        </div>

    </div>


    {{-- Card Body --}}

    <div class="card-body p-4">

        <form method="POST"
              action="{{ route(
                  'admin.exam-sessions.store',
                  $exam
              ) }}">

            @csrf


            <div class="row g-4">


                {{-- =================================================
                     SESSION NAME
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Session Name

                        <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-clock"></i>
                        </span>

                        <input
                            type="text"
                            name="session_name"
                            class="form-control @error('session_name') is-invalid @enderror"
                            value="{{ old('session_name') }}"
                            placeholder="Example: Morning Session"
                            required
                        >

                    </div>


                    @error('session_name')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @else

                        <div class="form-text">
                            Example: Morning Session, Afternoon Session.
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     STATUS
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Status

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                        >

                            <option value="1"
                                {{ old('status', '1') == '1' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="0"
                                {{ old('status') === '0' ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>


                    @error('status')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @else

                        <div class="form-text">
                            Only active sessions should normally be used for scheduling.
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     START TIME
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Start Time

                        <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-play-circle"></i>
                        </span>

                        <input
                            type="time"
                            name="start_time"
                            class="form-control @error('start_time') is-invalid @enderror"
                            value="{{ old('start_time', '10:00') }}"
                            required
                        >

                    </div>


                    @error('start_time')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @else

                        <div class="form-text">
                            Select when the examination session begins.
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     END TIME
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        End Time

                        <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-stop-circle"></i>
                        </span>

                        <input
                            type="time"
                            name="end_time"
                            class="form-control @error('end_time') is-invalid @enderror"
                            value="{{ old('end_time', '13:00') }}"
                            required
                        >

                    </div>


                    @error('end_time')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @else

                        <div class="form-text">
                            Select when the examination session ends.
                        </div>

                    @enderror

                </div>

            </div>


            {{-- =================================================
                 TIME PREVIEW
            ================================================== --}}

            <div class="session-preview mt-4">

                <div class="d-flex align-items-center">

                    <div class="preview-icon me-3">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>

                        <div class="fw-semibold">
                            Session Time
                        </div>

                        <div class="small text-muted">
                            This time slot will be available when generating the examination timetable.
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FORM ACTIONS
            ================================================== --}}

            <div class="form-actions mt-4 pt-4">

                <div class="d-flex flex-wrap justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.exam-sessions.index',
                        $exam
                    ) }}"
                       class="btn btn-light border px-4">

                        <i class="bi bi-x-lg me-1"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        Save Session

                    </button>

                </div>

            </div>


        </form>

    </div>

</div>

</div>

{{-- =========================================================
PAGE STYLES
========================================================== --}}

<style>

    /* =========================================================
       PAGE ICON
    ========================================================= */

    .page-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #eef6ff;
        color: #1677f0;

        font-size: 21px;
    }


    /* =========================================================
       EXAM SUMMARY
    ========================================================= */

    .exam-summary {
        padding: 18px 20px;

        background: linear-gradient(
            135deg,
            #f8fbff 0%,
            #ffffff 100%
        );

        border: 1px solid #dce8f5;

        border-radius: 12px;
    }


    .exam-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #1677f0;
        color: #ffffff;

        font-size: 20px;
    }


    .exam-name {
        color: #172033;
        font-size: 17px;
    }


    .info-box {
        display: flex;
        align-items: center;

        padding: 11px 14px;

        background: #ffffff;

        border: 1px solid #e1e7ef;

        border-radius: 9px;
    }


    .info-box-icon {
        width: 36px;
        height: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 10px;

        border-radius: 8px;

        background: #eef6ff;
        color: #1677f0;
    }


    /* =========================================================
       FORM CARD
    ========================================================= */

    .form-card {
        border-radius: 12px;

        overflow: hidden;
    }


    .card-header {
        border-color: #e8edf3 !important;
    }


    .section-icon {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #eef6ff;
        color: #1677f0;

        font-size: 17px;
    }


    /* =========================================================
       FORM ELEMENTS
    ========================================================= */

    .form-label {
        color: #344054;

        font-size: 14px;

        margin-bottom: 7px;
    }


    .input-group-text {
        min-width: 43px;

        justify-content: center;

        background: #f8fafc;

        border-color: #d9e0e8;

        color: #667085;
    }


    .form-control,
    .form-select {
        min-height: 43px;

        border-color: #d9e0e8;

        color: #344054;

        box-shadow: none;
    }


    .form-control:focus,
    .form-select:focus {
        border-color: #1677f0;

        box-shadow: 0 0 0 0.2rem rgba(22, 119, 240, 0.10);
    }


    .form-text {
        color: #7a8797;

        font-size: 12px;

        margin-top: 6px;
    }


    /* =========================================================
       SESSION PREVIEW
    ========================================================= */

    .session-preview {
        padding: 13px 15px;

        border: 1px solid #dce8f5;

        border-left: 4px solid #1677f0;

        border-radius: 8px;

        background: #f7fbff;
    }


    .preview-icon {
        width: 35px;
        height: 35px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #ffffff;

        color: #1677f0;

        border: 1px solid #dce8f5;
    }


    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .form-actions {
        border-top: 1px solid #e8edf3;
    }


    .btn {
        border-radius: 7px;

        font-weight: 500;
    }


    .btn-primary {
        background: #1677f0;

        border-color: #1677f0;
    }


    .btn-primary:hover {
        background: #0d66d5;

        border-color: #0d66d5;
    }


    /* =========================================================
       ALERT
    ========================================================= */

    .alert {
        border-radius: 9px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767.98px) {

        .container-fluid {
            padding-top: 20px !important;
        }

        .exam-summary {
            padding: 15px;
        }

        .exam-name {
            font-size: 15px;
        }

        .form-card .card-body {
            padding: 20px !important;
        }

        .form-actions .btn {
            width: 100%;
        }

    }

</style>

@endsection
