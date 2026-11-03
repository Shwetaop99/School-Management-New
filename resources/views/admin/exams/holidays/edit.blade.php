
@extends('layouts.app')

@section('title', 'Edit Exam Holiday')

@section('content')

<div class="exam-holiday-page">

    {{-- PAGE HEADER --}}
    <div class="holiday-page-header">

        <div class="holiday-heading">
            <div class="holiday-page-icon">
                <i class="bi bi-calendar2-check"></i>
            </div>

            <div class="holiday-heading-content">
                <div class="holiday-eyebrow">
                    EXAMINATION MANAGEMENT
                </div>

                <div class="holiday-title-row">
                    <h1 class="holiday-page-title">Edit Exam Holiday</h1>

                    <span class="holiday-edit-badge">
                        <i class="bi bi-pencil-square"></i>
                        Edit Record
                    </span>
                </div>

                <p class="holiday-page-subtitle">
                    Update the holiday date, status, or reason for this examination.
                </p>
            </div>
        </div>

        <a href="{{ route('admin.exam-holidays.index', $exam) }}"
           class="holiday-back-btn">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Holidays</span>
        </a>

    </div>

    {{-- BREADCRUMB --}}
    <div class="holiday-breadcrumb">
        <a href="{{ route('admin.exam-holidays.index', $exam) }}">
            Examinations
        </a>

        <i class="bi bi-chevron-right"></i>
        <span>Exam Holidays</span>
        <i class="bi bi-chevron-right"></i>
        <span class="breadcrumb-current">Edit Holiday</span>
    </div>

    {{-- EXAM SUMMARY --}}
    <div class="holiday-summary-grid">

        <div class="holiday-summary-card">
            <div class="summary-top-line summary-line-green"></div>

            <div class="summary-card-content">
                <div class="summary-icon summary-icon-green">
                    <i class="bi bi-journal-bookmark"></i>
                </div>

                <div class="summary-details">
                    <span class="summary-label">Examination</span>
                    <strong class="summary-value">{{ $exam->exam_name }}</strong>
                    <span class="summary-caption">Examination details</span>
                </div>
            </div>
        </div>

        <div class="holiday-summary-card">
            <div class="summary-top-line summary-line-blue"></div>

            <div class="summary-card-content">
                <div class="summary-icon summary-icon-blue">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="summary-details">
                    <span class="summary-label">Academic Year</span>
                    <strong class="summary-value">{{ $exam->academic_year }}</strong>
                    <span class="summary-caption">Current academic session</span>
                </div>
            </div>
        </div>

        <div class="holiday-summary-card">
            <div class="summary-top-line summary-line-amber"></div>

            <div class="summary-card-content">
                <div class="summary-icon summary-icon-amber">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div class="summary-details">
                    <span class="summary-label">Current Holiday</span>
                    <strong class="summary-value">
                        {{ $holiday->holiday_date->format('d M Y') }}
                    </strong>
                    <span class="summary-caption">Existing holiday date</span>
                </div>
            </div>
        </div>

    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="holiday-error-panel">
            <div class="holiday-error-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="holiday-error-content">
                <strong>Please correct the following errors.</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- MAIN FORM CARD --}}
    <div class="holiday-form-card">

        <div class="holiday-form-header">
            <div class="form-header-icon">
                <i class="bi bi-calendar2-week"></i>
            </div>

            <div class="form-header-copy">
                <h2>Holiday Information</h2>
                <p>Review and update the details of this holiday record.</p>
            </div>

            <div class="form-header-side">
                <span class="form-header-dot"></span>
                Existing Record
            </div>
        </div>

        <div class="holiday-form-body">

            <form method="POST"
                  action="{{ route('admin.exam-holidays.update', [$exam, $holiday]) }}">

                @csrf
                @method('PUT')

                {{-- HOLIDAY DETAILS --}}
                <div class="holiday-section-heading">
                    <div class="section-number">01</div>

                    <div>
                        <h3>Holiday Details</h3>
                        <p>Choose the holiday date and configure its status.</p>
                    </div>
                </div>

                <div class="holiday-form-grid">

                    {{-- HOLIDAY DATE --}}
                    <div class="holiday-field">

                        <label for="holiday_date" class="holiday-label">
                            Holiday Date
                            <span class="required-star">*</span>
                        </label>

                        <div class="holiday-input-wrap">
                            <span class="holiday-input-icon">
                                <i class="bi bi-calendar-date"></i>
                            </span>

                            <input
                                id="holiday_date"
                                type="date"
                                name="holiday_date"
                                class="holiday-input @error('holiday_date') is-invalid @enderror"
                                value="{{ old('holiday_date', $holiday->holiday_date->format('Y-m-d')) }}"
                                min="{{ $exam->start_date->format('Y-m-d') }}"
                                max="{{ ($exam->end_date ?? $exam->start_date)->format('Y-m-d') }}"
                                required
                            >
                        </div>

                        @error('holiday_date')
                            <div class="holiday-field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="holiday-help-text">
                            <i class="bi bi-info-circle"></i>
                            The holiday date must fall within the examination period.
                        </div>

                        <div class="exam-period-panel">
                            <div class="period-icon">
                                <i class="bi bi-calendar-range"></i>
                            </div>

                            <div class="period-details">
                                <span class="period-label">Examination Period</span>

                                <strong>
                                    {{ $exam->start_date->format('d M Y') }}

                                    @if($exam->end_date)
                                        <span class="period-separator">—</span>
                                        {{ $exam->end_date->format('d M Y') }}
                                    @endif
                                </strong>
                            </div>
                        </div>

                    </div>

                    {{-- HOLIDAY STATUS --}}
                    <div class="holiday-field">

                        <label for="status" class="holiday-label">
                            Holiday Status
                            <span class="required-star">*</span>
                        </label>

                        @php
                            $currentStatus = (string) old(
                                'status',
                                $holiday->status ? '1' : '0'
                            );
                        @endphp

                        <div class="holiday-input-wrap">
                            <span class="holiday-input-icon">
                                <i class="bi bi-toggle-on"></i>
                            </span>

                            <select
                                id="status"
                                name="status"
                                class="holiday-input holiday-select @error('status') is-invalid @enderror"
                                required
                            >
                                <option value="1"
                                    {{ $currentStatus === '1' ? 'selected' : '' }}>
                                    Active — Non-working Day
                                </option>

                                <option value="0"
                                    {{ $currentStatus === '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        @error('status')
                            <div class="holiday-field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div id="holiday-status-info"
                             class="holiday-status-panel {{ $currentStatus === '1' ? 'status-active' : 'status-inactive' }}">

                            <div class="status-panel-icon">
                                <i id="holiday-status-icon"
                                   class="bi {{ $currentStatus === '1' ? 'bi-check-circle-fill' : 'bi-pause-circle-fill' }}"></i>
                            </div>

                            <div>
                                <strong id="holiday-status-title">
                                    {{ $currentStatus === '1' ? 'Active holiday' : 'Inactive holiday' }}
                                </strong>

                                <p id="holiday-status-description">
                                    @if($currentStatus === '1')
                                        This date will be treated as a non-working day when generating the examination timetable.
                                    @else
                                        This date will not be treated as an active non-working day.
                                    @endif
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- HOLIDAY REASON --}}
                <div class="holiday-section-heading reason-section-heading">
                    <div class="section-number">02</div>

                    <div>
                        <h3>Holiday Reason</h3>
                        <p>Add a short explanation for the non-working day.</p>
                    </div>

                    <span class="optional-section-badge">Optional</span>
                </div>

                <div class="holiday-field">
                    <label for="reason" class="holiday-label">
                        Reason
                        <span class="optional-label">(Optional)</span>
                    </label>

                    <div class="holiday-input-wrap">
                        <span class="holiday-input-icon">
                            <i class="bi bi-chat-left-text"></i>
                        </span>

                        <input
                            id="reason"
                            type="text"
                            name="reason"
                            class="holiday-input @error('reason') is-invalid @enderror"
                            value="{{ old('reason', $holiday->reason) }}"
                            placeholder="e.g. Sunday, Diwali Holiday, Public Holiday"
                            maxlength="255"
                        >
                    </div>

                    @error('reason')
                        <div class="holiday-field-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="holiday-help-text">
                        <i class="bi bi-info-circle"></i>
                        Update the reason if the purpose of the holiday has changed.
                    </div>
                </div>

                {{-- RECORD INFORMATION --}}
                <div class="holiday-record-note">
                    <div class="record-note-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>
                        <strong>Updating an Existing Record</strong>
                        <p>
                            Your changes will replace the current holiday information
                            after you select <strong>Update Holiday</strong>.
                            Please review the date and status before saving.
                        </p>
                    </div>
                </div>

                {{-- FORM FOOTER --}}
                <div class="holiday-form-footer">

                    <div class="holiday-security-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Review your changes before saving.</span>
                    </div>

                    <div class="holiday-form-actions">

                        <a href="{{ route('admin.exam-holidays.index', $exam) }}"
                           class="holiday-btn holiday-btn-cancel">
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </a>

                        <button type="submit" class="holiday-btn holiday-btn-save">
                            <i class="bi bi-check2-circle"></i>
                            Update Holiday
                            <i class="bi bi-arrow-right save-arrow"></i>
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- BOTTOM NOTE --}}
    <div class="holiday-bottom-note">
        <i class="bi bi-journal-check"></i>

        <span>
            <strong>Examination Records</strong>
            <span class="bottom-note-divider">/</span>
            Keep holiday information accurate for reliable timetable planning.
        </span>
    </div>

</div>

<style>
/* =========================================================
   EDIT EXAM HOLIDAY — SCHOOL DIARY DESIGN
========================================================= */

.exam-holiday-page {
    --holiday-dark: #17382b;
    --holiday-green: #285640;
    --holiday-sage: #527d61;
    --holiday-gold: #c49a45;
    --holiday-ink: #26352c;
    --holiday-muted: #7c877e;
    --holiday-border: #e8ece7;
    --holiday-bg: #f7f9f6;
    width: 100%;
    color: var(--holiday-ink);
}

/* PAGE HEADER */

.holiday-page-header {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    min-height: 178px;
    margin-bottom: 20px;
    padding: 25px 28px;
    border: 1px solid rgba(196, 154, 69, .38);
    border-left: 4px solid var(--holiday-gold);
    border-radius: 16px;
    background: linear-gradient(135deg, #142f25 0%, #1e4535 55%, #285640 100%);
    box-shadow: 0 10px 25px rgba(20, 47, 37, .14);
}

.holiday-page-header::after {
    content: "";
    position: absolute;
    z-index: 0;
    top: -145px;
    right: -70px;
    width: 340px;
    height: 340px;
    border: 1px solid rgba(226, 196, 127, .22);
    border-radius: 50%;
    box-shadow:
        0 0 0 28px rgba(255, 255, 255, .025),
        0 0 0 58px rgba(255, 255, 255, .02),
        0 0 0 90px rgba(255, 255, 255, .015);
    pointer-events: none;
}

.holiday-heading {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.holiday-page-icon {
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(226, 196, 127, .35);
    border-radius: 15px;
    background: rgba(226, 196, 127, .13);
    color: #f0dba9;
    font-size: 23px;
}

.holiday-heading-content {
    min-width: 0;
}

.holiday-eyebrow {
    margin-bottom: 7px;
    color: #e2c47f;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.holiday-title-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}

.holiday-page-title {
    margin: 0;
    color: #fffefa;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 2.3vw, 32px);
    font-weight: 700;
    letter-spacing: -.8px;
    line-height: 1.25;
}

.holiday-edit-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid rgba(226, 196, 127, .4);
    border-radius: 30px;
    background: rgba(226, 196, 127, .14);
    color: #f0dba9;
    font-size: 11px;
    font-weight: 700;
}

.holiday-page-subtitle {
    margin: 7px 0 0;
    color: rgba(255, 254, 250, .78);
    font-size: 13px;
    line-height: 1.7;
}

.holiday-back-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 43px;
    flex-shrink: 0;
    padding: 0 16px;
    border: 1px solid rgba(255, 255, 255, .30);
    border-radius: 10px;
    background: rgba(255, 255, 255, .09);
    color: #fffefa;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s ease;
}

.holiday-back-btn:hover {
    border-color: #fffefa;
    background: #fffefa;
    color: #20392d;
    transform: translateY(-1px);
}

/* BREADCRUMB */

.holiday-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
    color: #969b90;
    font-size: 11px;
}

.holiday-breadcrumb a {
    color: var(--holiday-sage);
    font-weight: 700;
    text-decoration: none;
}

.holiday-breadcrumb a:hover {
    color: var(--holiday-dark);
}

.holiday-breadcrumb i {
    font-size: 9px;
}

.holiday-breadcrumb .breadcrumb-current {
    color: var(--holiday-ink);
    font-weight: 700;
}

/* SUMMARY CARDS */

.holiday-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.holiday-summary-card {
    position: relative;
    overflow: hidden;
    min-width: 0;
    border: 1px solid var(--holiday-border);
    border-radius: 13px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(30, 55, 40, .035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.holiday-summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(30, 55, 40, .08);
}

.summary-top-line {
    height: 3px;
}

.summary-line-green { background: #4d8660; }
.summary-line-blue { background: #5685b6; }
.summary-line-amber { background: #c49a45; }

.summary-card-content {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 18px;
}

.summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    border-radius: 12px;
    font-size: 19px;
}

.summary-icon-green {
    color: #36714a;
    background: #eaf4ec;
}

.summary-icon-blue {
    color: #4777a8;
    background: #edf3fa;
}

.summary-icon-amber {
    color: #a77b2e;
    background: #fbf3e3;
}

.summary-details {
    display: flex;
    flex-direction: column;
    min-width: 0;
    gap: 4px;
}

.summary-label {
    color: #89928a;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .65px;
}

.summary-value {
    overflow-wrap: anywhere;
    color: #293b30;
    font-size: 15px;
    font-weight: 800;
}

.summary-caption {
    color: #a0a79f;
    font-size: 10px;
}

/* VALIDATION */

.holiday-error-panel {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 20px;
    padding: 16px 18px;
    border: 1px solid #f2d0ce;
    border-left: 4px solid #c6534d;
    border-radius: 11px;
    background: #fff7f6;
    color: #873c38;
}

.holiday-error-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    border-radius: 9px;
    background: #fce5e3;
    color: #b84741;
    font-size: 17px;
}

.holiday-error-content strong {
    font-size: 13px;
}

.holiday-error-content ul {
    margin: 7px 0 0;
    padding-left: 18px;
    font-size: 12px;
    line-height: 1.8;
}

/* FORM CARD */

.holiday-form-card {
    overflow: hidden;
    border: 1px solid var(--holiday-border);
    border-radius: 15px;
    background: #fff;
    box-shadow: 0 5px 20px rgba(30, 55, 40, .045);
}

.holiday-form-header {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 19px 23px;
    border-bottom: 1px solid #edf0eb;
    background: linear-gradient(110deg, #fbfcfa, #f6f9f5);
}

.form-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    border: 1px solid #dce9dc;
    border-radius: 11px;
    background: #eaf3e9;
    color: #396d49;
    font-size: 18px;
}

.form-header-copy {
    min-width: 0;
}

.form-header-copy h2 {
    margin: 0;
    color: #293b30;
    font-size: 16px;
    font-weight: 800;
}

.form-header-copy p {
    margin: 4px 0 0;
    color: #89928a;
    font-size: 11px;
}

.form-header-side {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-left: auto;
    color: #718176;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.form-header-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #c49a45;
    box-shadow: 0 0 0 3px rgba(196, 154, 69, .13);
}

.holiday-form-body {
    padding: 25px;
}

/* SECTION HEADINGS */

.holiday-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
}

.section-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex-shrink: 0;
    border: 1px solid #e6eadf;
    border-radius: 10px;
    background: #f4f7f1;
    color: #64805d;
    font-size: 11px;
    font-weight: 800;
}

.holiday-section-heading h3 {
    margin: 0;
    color: #2e4034;
    font-size: 14px;
    font-weight: 800;
}

.holiday-section-heading p {
    margin: 4px 0 0;
    color: #929a92;
    font-size: 11px;
}

.holiday-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 25px;
    padding-bottom: 27px;
    border-bottom: 1px solid #edf0eb;
}

/* FIELDS */

.holiday-field {
    min-width: 0;
}

.holiday-label {
    display: block;
    margin-bottom: 9px;
    color: #38473d;
    font-size: 12px;
    font-weight: 800;
}

.required-star {
    color: #d45c52;
    margin-left: 2px;
}

.optional-label {
    color: #a0a79f;
    font-size: 10px;
    font-weight: 500;
}

.holiday-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.holiday-input-icon {
    position: absolute;
    z-index: 1;
    left: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #829385;
    font-size: 15px;
    pointer-events: none;
}

.holiday-input {
    display: block;
    width: 100%;
    min-height: 47px;
    padding: 10px 13px 10px 42px;
    border: 1px solid #dfe6dd;
    border-radius: 9px;
    outline: none;
    background: #fff;
    color: #35443a;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.holiday-input:focus {
    border-color: #729879;
    box-shadow: 0 0 0 3px rgba(85, 133, 94, .11);
}

.holiday-input::placeholder {
    color: #adb5ae;
}

.holiday-select {
    appearance: none;
    cursor: pointer;
    padding-right: 35px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%237e8d80' d='M3.2 5.8 8 10.3l4.8-4.5.9 1-5.7 5.3L2.3 6.8z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

.holiday-input.is-invalid {
    border-color: #d66a64;
}

.holiday-field-error {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 7px;
    color: #bd4d47;
    font-size: 11px;
}

.holiday-help-text {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    margin-top: 9px;
    color: #929b92;
    font-size: 10px;
    line-height: 1.6;
}

.holiday-help-text i {
    margin-top: 1px;
    color: #819a84;
}

/* EXAM PERIOD */

.exam-period-panel {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-top: 17px;
    padding: 13px;
    border: 1px solid #e4ebdf;
    border-radius: 10px;
    background: #f8faf6;
}

.period-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    border-radius: 9px;
    background: #e9f1e6;
    color: #608264;
    font-size: 15px;
}

.period-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.period-label {
    color: #929b90;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.period-details strong {
    color: #405444;
    font-size: 11px;
}

.period-separator {
    padding: 0 5px;
    color: #a8b2a6;
}

/* STATUS PANEL */

.holiday-status-panel {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-top: 17px;
    padding: 14px;
    border: 1px solid;
    border-radius: 10px;
    transition: all .2s ease;
}

.status-active {
    border-color: #d7e9d9;
    background: #f2f9f2;
    color: #3c7549;
}

.status-inactive {
    border-color: #e5e8e5;
    background: #f7f8f7;
    color: #778078;
}

.status-panel-icon {
    font-size: 17px;
    padding-top: 1px;
}

.holiday-status-panel strong {
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
}

.holiday-status-panel p {
    margin: 0;
    font-size: 10px;
    line-height: 1.6;
    color: #78857a;
}

/* REASON SECTION */

.reason-section-heading {
    margin-top: 27px;
    margin-bottom: 19px;
}

.optional-section-badge {
    margin-left: auto;
    padding: 5px 10px;
    border: 1px solid #e9e8d8;
    border-radius: 20px;
    background: #faf9f1;
    color: #96834d;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* RECORD NOTE */

.holiday-record-note {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 25px;
    padding: 15px 17px;
    border: 1px solid #e6e9df;
    border-radius: 10px;
    background: #f8f9f5;
}

.record-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    border-radius: 9px;
    background: #edf1e5;
    color: #758650;
    font-size: 16px;
}

.holiday-record-note strong {
    display: block;
    margin-bottom: 5px;
    color: #465441;
    font-size: 11px;
    font-weight: 800;
}

.holiday-record-note p {
    margin: 0;
    color: #899187;
    font-size: 10px;
    line-height: 1.7;
}

/* FORM FOOTER */

.holiday-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #edf0eb;
}

.holiday-security-note {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #8a958a;
    font-size: 10px;
}

.holiday-security-note i {
    color: #6e9272;
    font-size: 15px;
}

.holiday-form-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.holiday-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 43px;
    padding: 0 17px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: all .2s ease;
}

.holiday-btn-cancel {
    border-color: #e1e6df;
    background: #fff;
    color: #69766b;
}

.holiday-btn-cancel:hover {
    border-color: #cbd4c9;
    background: #f7f9f6;
    color: #354c3a;
}

.holiday-btn-save {
    border-color: #234c35;
    background: linear-gradient(135deg, #234c35, #326746);
    color: #fff;
    box-shadow: 0 4px 10px rgba(35, 76, 53, .15);
}

.holiday-btn-save:hover {
    border-color: #173c29;
    background: linear-gradient(135deg, #173c29, #28583a);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(35, 76, 53, .2);
}

.save-arrow {
    margin-left: 3px;
    font-size: 10px;
    transition: transform .2s ease;
}

.holiday-btn-save:hover .save-arrow {
    transform: translateX(3px);
}

/* BOTTOM NOTE */

.holiday-bottom-note {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-top: 18px;
    padding: 13px 15px;
    border: 1px solid #e9ece5;
    border-radius: 10px;
    background: #fbfcfa;
    color: #8b958a;
    font-size: 10px;
    line-height: 1.6;
}

.holiday-bottom-note > i {
    color: #9b8959;
    font-size: 16px;
}

.holiday-bottom-note strong {
    color: #5a6a5a;
    font-weight: 800;
}

.bottom-note-divider {
    margin: 0 5px;
    color: #c2c9bd;
}

/* RESPONSIVE */

@media (max-width: 991.98px) {
    .holiday-summary-grid {
        gap: 11px;
    }

    .summary-card-content {
        padding: 14px;
    }

    .summary-icon {
        width: 39px;
        height: 39px;
    }

    .summary-value {
        font-size: 13px;
    }
}

@media (max-width: 767.98px) {
    .holiday-page-header {
        align-items: stretch;
        flex-direction: column;
        min-height: 0;
        gap: 17px;
        padding: 20px 18px;
    }

    .holiday-heading {
        align-items: flex-start;
        gap: 12px;
    }

    .holiday-page-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        font-size: 19px;
    }

    .holiday-page-title {
        font-size: 25px;
    }

    .holiday-page-subtitle {
        font-size: 12px;
    }

    .holiday-back-btn {
        width: 100%;
    }

    .holiday-summary-grid {
        grid-template-columns: 1fr;
    }

    .holiday-form-grid {
        grid-template-columns: 1fr;
        gap: 23px;
    }

    .holiday-form-body {
        padding: 19px;
    }

    .holiday-form-header {
        padding: 16px 19px;
    }

    .form-header-side {
        font-size: 9px;
    }

    .holiday-form-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .holiday-form-actions {
        width: 100%;
    }

    .holiday-form-actions .holiday-btn {
        flex: 1;
    }
}

@media (max-width: 420px) {
    .holiday-page-header {
        padding: 18px 15px;
    }

    .holiday-page-title {
        font-size: 22px;
    }

    .holiday-edit-badge {
        padding: 5px 8px;
        font-size: 10px;
    }

    .holiday-form-body {
        padding: 15px;
    }

    .holiday-form-header {
        flex-wrap: wrap;
        padding: 14px;
    }

    .form-header-side {
        width: 100%;
        margin-left: 55px;
    }

    .holiday-section-heading {
        gap: 9px;
    }

    .holiday-section-heading h3 {
        font-size: 13px;
    }

    .holiday-section-heading p {
        font-size: 10px;
    }

    .optional-section-badge {
        padding: 4px 7px;
        font-size: 8px;
    }

    .holiday-bottom-note {
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('status');
    const statusPanel = document.getElementById('holiday-status-info');
    const statusIcon = document.getElementById('holiday-status-icon');
    const statusTitle = document.getElementById('holiday-status-title');
    const statusDescription = document.getElementById('holiday-status-description');

    if (!statusSelect || !statusPanel || !statusIcon || !statusTitle || !statusDescription) {
        return;
    }

    function updateHolidayStatus() {
        const isActive = statusSelect.value === '1';

        statusPanel.classList.toggle('status-active', isActive);
        statusPanel.classList.toggle('status-inactive', !isActive);

        statusIcon.className = isActive
            ? 'bi bi-check-circle-fill'
            : 'bi bi-pause-circle-fill';

        statusTitle.textContent = isActive
            ? 'Active holiday'
            : 'Inactive holiday';

        statusDescription.textContent = isActive
            ? 'This date will be treated as a non-working day when generating the examination timetable.'
            : 'This date will not be treated as an active non-working day.';
    }

    statusSelect.addEventListener('change', updateHolidayStatus);
    updateHolidayStatus();
});
</script>

@endsection
