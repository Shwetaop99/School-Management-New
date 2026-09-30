
@extends('layouts.app')

@section('title', 'Edit Exam Session')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="page-icon">
                    <i class="bi bi-pencil-square"></i>
                </span>

                <h4 class="fw-bold mb-0">
                    Edit Exam Session
                </h4>
            </div>

            <div class="text-muted small">
                Update the examination time slot and session settings.
            </div>
        </div>

        <a href="{{ route('admin.exam-sessions.index', $exam) }}"
           class="btn btn-outline-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Sessions
        </a>

    </div>


    {{-- =========================================================
        EXAM SUMMARY
    ========================================================== --}}
    <div class="card border-0 shadow-sm exam-summary-card mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center g-4">

                <div class="col-lg-8">

                    <div class="d-flex align-items-start gap-3">

                        <div class="exam-icon">
                            <i class="bi bi-journal-text"></i>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                Examination
                            </div>

                            <h5 class="fw-bold mb-2">
                                {{ $exam->exam_name }}
                            </h5>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="info-badge">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $exam->academic_year }}
                                </span>

                                @if(!empty($exam->exam_type))
                                    <span class="info-badge">
                                        <i class="bi bi-bookmark me-1"></i>
                                        {{ $exam->exam_type }}
                                    </span>
                                @endif

                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="configuration-box">
                        <div class="configuration-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div>
                            <div class="fw-semibold">
                                Session Time Slot
                            </div>

                            <div class="small text-muted">
                                Update the timing used for timetable generation.
                            </div>
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

        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-start gap-3 mb-4"
             role="alert">

            <div class="alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="flex-grow-1">

                <div class="fw-semibold mb-1">
                    Please correct the following errors:
                </div>

                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
        SESSION FORM
    ========================================================== --}}
    <div class="card border-0 shadow-sm session-card">

        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">

            <div class="d-flex align-items-center gap-3">

                <div class="section-icon">
                    <i class="bi bi-clock"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Session Information
                    </h5>

                    <p class="text-muted small mb-0">
                        Update the details of this examination session.
                    </p>
                </div>

            </div>

        </div>


        <div class="card-body px-4 pb-4">

            <form method="POST"
                  action="{{ route(
                      'admin.exam-sessions.update',
                      [$exam, $session]
                  ) }}">

                @csrf
                @method('PUT')


                <div class="row g-4">

                    {{-- =================================================
                        SESSION NAME
                    ================================================== --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Session Name
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group custom-input-group">

                            <span class="input-group-text">
                                <i class="bi bi-tag"></i>
                            </span>

                            <input type="text"
                                   name="session_name"
                                   class="form-control @error('session_name') is-invalid @enderror"
                                   value="{{ old('session_name', $session->session_name) }}"
                                   placeholder="Example: Morning Session"
                                   required>

                        </div>

                        @error('session_name')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Give this session a clear and recognizable name.
                        </div>

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        @php
                            $currentStatus = old(
                                'status',
                                $session->status ? '1' : '0'
                            );
                        @endphp

                        <div class="input-group custom-input-group">

                            <span class="input-group-text">
                                <i class="bi bi-toggle-on"></i>
                            </span>

                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">

                                <option value="1"
                                    {{ $currentStatus == '1' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ $currentStatus == '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                        @error('status')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Only active sessions should normally be used for timetable generation.
                        </div>

                    </div>


                    {{-- =================================================
                        START TIME
                    ================================================== --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Start Time
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group custom-input-group">

                            <span class="input-group-text">
                                <i class="bi bi-clock"></i>
                            </span>

                            <input type="time"
                                   id="start_time"
                                   name="start_time"
                                   class="form-control @error('start_time') is-invalid @enderror"
                                   value="{{ old(
                                       'start_time',
                                       \Carbon\Carbon::parse($session->start_time)->format('H:i')
                                   ) }}"
                                   required>

                        </div>

                        @error('start_time')
                            <div class="text-danger small mt-1">
                                {{ $message }}
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

                        <div class="input-group custom-input-group">

                            <span class="input-group-text">
                                <i class="bi bi-clock-fill"></i>
                            </span>

                            <input type="time"
                                   id="end_time"
                                   name="end_time"
                                   class="form-control @error('end_time') is-invalid @enderror"
                                   value="{{ old(
                                       'end_time',
                                       \Carbon\Carbon::parse($session->end_time)->format('H:i')
                                   ) }}"
                                   required>

                        </div>

                        @error('end_time')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =================================================
                    TIME PREVIEW
                ================================================== --}}
                <div class="time-preview mt-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="preview-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="fw-semibold">
                                Session Preview
                            </div>

                            <div class="small text-muted"
                                 id="sessionPreview">
                                Select the start and end time to preview the session duration.
                            </div>

                        </div>

                        <div class="duration-badge"
                             id="durationBadge">
                            <i class="bi bi-clock me-1"></i>
                            <span id="durationText">--</span>
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    INFORMATION NOTICE
                ================================================== --}}
                <div class="info-notice mt-3">

                    <i class="bi bi-info-circle-fill"></i>

                    <div>
                        <strong>Timetable note:</strong>
                        This session time will be used when generating the examination timetable.
                        Make sure the start time is earlier than the end time.
                    </div>

                </div>


                <hr class="my-4">


                {{-- =================================================
                    ACTION BUTTONS
                ================================================== --}}
                <div class="d-flex flex-wrap justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.exam-sessions.index',
                        $exam
                    ) }}"
                       class="btn btn-light border px-4">

                        <i class="bi bi-x-lg me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4">

                        <i class="bi bi-check-lg me-1"></i>
                        Update Session

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
    PAGE STYLES
============================================================= --}}
<style>

    :root {
        --primary-blue: #1677f0;
        --primary-dark: #0f5fc4;
        --soft-blue: #eef6ff;
        --border-color: #e7ebf0;
        --text-dark: #263238;
        --muted-text: #6c757d;
    }

    .page-icon {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 20px;
    }

    .exam-summary-card,
    .session-card {
        border-radius: 16px;
        overflow: hidden;
    }

    .exam-summary-card {
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #f8fbff 100%
        );
    }

    .exam-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-blue);
        color: #fff;
        font-size: 22px;
        box-shadow: 0 7px 18px rgba(22, 119, 240, 0.20);
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 8px;
        background: #f1f4f8;
        color: #5f6b76;
        font-size: 13px;
        font-weight: 500;
    }

    .configuration-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border-radius: 12px;
        background: #f8fbff;
        border: 1px solid #dcecff;
    }

    .configuration-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #e5f1ff;
        color: var(--primary-blue);
        font-size: 18px;
    }

    .section-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 19px;
    }

    .session-card .card-header {
        border-bottom: 1px solid var(--border-color) !important;
    }

    .form-label {
        color: var(--text-dark);
        font-size: 14px;
        margin-bottom: 8px;
    }

    .custom-input-group {
        border-radius: 9px;
        overflow: hidden;
    }

    .custom-input-group .input-group-text {
        background: #f8fafc;
        border-color: #dfe4ea;
        color: #6c757d;
        min-width: 46px;
        justify-content: center;
    }

    .custom-input-group .form-control,
    .custom-input-group .form-select {
        border-color: #dfe4ea;
        min-height: 46px;
        box-shadow: none;
    }

    .custom-input-group:focus-within .input-group-text {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        background: var(--soft-blue);
    }

    .custom-input-group:focus-within .form-control,
    .custom-input-group:focus-within .form-select {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.18rem rgba(22, 119, 240, 0.10);
    }

    .form-text {
        font-size: 12px;
        color: #8a939d;
        margin-top: 6px;
    }

    .time-preview {
        padding: 16px 18px;
        border-radius: 12px;
        background: #f8fbff;
        border: 1px solid #dcecff;
    }

    .preview-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #e5f1ff;
        color: var(--primary-blue);
        font-size: 18px;
    }

    .duration-badge {
        padding: 8px 13px;
        border-radius: 8px;
        background: #e8f5ee;
        color: #198754;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .info-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 15px;
        border-radius: 10px;
        background: #fffaf0;
        border: 1px solid #ffe6ad;
        color: #765b18;
        font-size: 13px;
        line-height: 1.6;
    }

    .info-notice > i {
        color: #e0a800;
        margin-top: 2px;
    }

    .btn-primary {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .alert {
        border-radius: 12px;
    }

    .alert-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: rgba(220, 53, 69, 0.10);
        color: #dc3545;
    }

    @media (max-width: 767.98px) {

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .exam-summary-card .card-body,
        .session-card .card-body {
            padding-left: 18px !important;
            padding-right: 18px !important;
        }

        .duration-badge {
            display: none;
        }

        .time-preview {
            padding: 14px;
        }

    }

</style>


{{-- =============================================================
    SESSION DURATION PREVIEW
============================================================= --}}
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const startInput = document.getElementById('start_time');
        const endInput = document.getElementById('end_time');
        const durationText = document.getElementById('durationText');
        const sessionPreview = document.getElementById('sessionPreview');

        function updateDuration() {

            if (!startInput.value || !endInput.value) {
                durationText.textContent = '--';

                sessionPreview.textContent =
                    'Select the start and end time to preview the session duration.';

                return;
            }

            const [startHour, startMinute] =
                startInput.value.split(':').map(Number);

            const [endHour, endMinute] =
                endInput.value.split(':').map(Number);

            const startTotal =
                (startHour * 60) + startMinute;

            const endTotal =
                (endHour * 60) + endMinute;

            const difference =
                endTotal - startTotal;

            if (difference <= 0) {

                durationText.textContent = 'Invalid';

                sessionPreview.textContent =
                    'End time must be later than the start time.';

                return;
            }

            const hours = Math.floor(difference / 60);
            const minutes = difference % 60;

            let duration = '';

            if (hours > 0) {
                duration += hours + (hours === 1 ? ' hour' : ' hours');
            }

            if (minutes > 0) {

                if (duration !== '') {
                    duration += ' ';
                }

                duration += minutes + ' min';
            }

            durationText.textContent = duration;

            sessionPreview.textContent =
                'This session runs from ' +
                formatTime(startInput.value) +
                ' to ' +
                formatTime(endInput.value) +
                '.';

        }


        function formatTime(time) {

            const [hour, minute] = time.split(':').map(Number);

            const date = new Date();

            date.setHours(hour);
            date.setMinutes(minute);

            return date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

        }


        startInput.addEventListener('change', updateDuration);
        endInput.addEventListener('change', updateDuration);

        updateDuration();

    });

</script>

@endsection
