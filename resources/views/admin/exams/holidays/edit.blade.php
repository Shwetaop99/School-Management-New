
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

                    <strong class="summary-value">
                        {{ $exam->exam_name }}
                    </strong>

                    <span class="summary-caption">
                        Examination details
                    </span>
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

                    <strong class="summary-value">
                        {{ $exam->academic_year }}
                    </strong>

                    <span class="summary-caption">
                        Current academic session
                    </span>
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

                    <span class="summary-caption">
                        Existing holiday date
                    </span>
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

                <p>
                    Review and update the details of this holiday record.
                </p>
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

                {{-- SECTION: HOLIDAY DETAILS --}}
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

                        {{-- EXAM PERIOD --}}
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

                    {{-- STATUS --}}
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

                {{-- SECTION: REASON --}}
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

                        <span>
                            Review your changes before saving.
                        </span>
                    </div>

                    <div class="holiday-form-actions">

                        <a href="{{ route('admin.exam-holidays.index', $exam) }}"
                           class="holiday-btn holiday-btn-cancel">
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </a>

                        <button type="submit"
                                class="holiday-btn holiday-btn-save">
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
   SCHOOL DIARY DESIGN SYSTEM
   Edit Exam Holiday
   Scoped styles only
========================================================= */

.exam-holiday-page {
    --holiday-page: #f7f6f0;
    --holiday-paper: #fffefa;
    --holiday-cream: #f5f3e9;
    --holiday-ink: #263b35;
    --holiday-dark: #1d302b;
    --holiday-soft: #40574a;
    --holiday-text: #39483e;
    --holiday-muted: #7b857b;
    --holiday-line: #e5e1d5;
    --holiday-sage: #527f70;
    --holiday-blue: #426b8a;
    --holiday-amber: #a87543;
    --holiday-green: #43835d;
    --holiday-red: #b95b50;

    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
    color: var(--holiday-text);
    font-family: "Public Sans", "Segoe UI", sans-serif;
    font-size: 14px;
}

.exam-holiday-page *,
.exam-holiday-page *::before,
.exam-holiday-page *::after {
    box-sizing: border-box;
}

.exam-holiday-page a {
    text-decoration: none;
}

.exam-holiday-page button,
.exam-holiday-page input,
.exam-holiday-page select {
    font: inherit;
}

/* PAGE HEADER */

.holiday-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    margin-bottom: 20px;
}

.holiday-heading {
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
    border: 1px solid #d9dfd2;
    border-radius: 15px;
    background: #e9eee4;
    color: var(--holiday-ink);
    font-size: 23px;
}

.holiday-heading-content {
    min-width: 0;
}

.holiday-eyebrow {
    margin-bottom: 5px;
    color: var(--holiday-sage);
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
    color: var(--holiday-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 2.3vw, 32px);
    font-weight: 700;
    letter-spacing: -0.8px;
    line-height: 1.25;
}

.holiday-edit-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #eadbc4;
    border-radius: 30px;
    background: #f6ecda;
    color: #8b6338;
    font-size: 11px;
    font-weight: 700;
}

.holiday-page-subtitle {
    margin: 7px 0 0;
    color: var(--holiday-muted);
    font-size: 13px;
    line-height: 1.7;
}

.holiday-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 43px;
    flex-shrink: 0;
    padding: 0 16px;
    border: 1px solid #d8dcd2;
    border-radius: 10px;
    background: var(--holiday-paper);
    color: var(--holiday-ink);
    font-size: 12px;
    font-weight: 700;
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}

.holiday-back-btn:hover {
    border-color: var(--holiday-sage);
    background: #f0f2e9;
    color: var(--holiday-dark);
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
    margin-bottom: 23px;
}

.holiday-summary-card {
    position: relative;
    overflow: hidden;
    min-width: 0;
    border: 1px solid var(--holiday-line);
    border-radius: 15px;
    background: var(--holiday-paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.holiday-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(38, 59, 53, .07);
}

.summary-top-line {
    height: 3px;
    width: 100%;
}

.summary-line-green {
    background: #527f70;
}

.summary-line-blue {
    background: #6687a0;
}

.summary-line-amber {
    background: #b78b57;
}

.summary-card-content {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px;
}

.summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    flex-shrink: 0;
    border-radius: 12px;
    font-size: 19px;
}

.summary-icon-green {
    background: #e8eee5;
    color: var(--holiday-ink);
}

.summary-icon-blue {
    background: #e9eff2;
    color: var(--holiday-blue);
}

.summary-icon-amber {
    background: #f5ecdf;
    color: var(--holiday-amber);
}

.summary-details {
    display: flex;
    flex-direction: column;
    min-width: 0;
    gap: 4px;
}

.summary-label {
    color: var(--holiday-muted);
    font-size: 11px;
    font-weight: 600;
}

.summary-value {
    overflow-wrap: anywhere;
    color: var(--holiday-ink);
    font-size: 15px;
    font-weight: 750;
    line-height: 1.45;
}

.summary-caption {
    color: #969b90;
    font-size: 10px;
}

/* VALIDATION */

.holiday-error-panel {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 22px;
    padding: 17px 19px;
    border: 1px solid #e9cbc4;
    border-radius: 12px;
    background: #fff8f5;
    color: #843f36;
}

.holiday-error-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 10px;
    background: #f7e5df;
    color: var(--holiday-red);
    font-size: 17px;
}

.holiday-error-content {
    min-width: 0;
    padding-top: 2px;
}

.holiday-error-content > strong {
    font-size: 13px;
}

.holiday-error-content ul {
    margin: 7px 0 0;
    padding-left: 18px;
    font-size: 12px;
    line-height: 1.8;
}

/* MAIN FORM CARD */

.holiday-form-card {
    overflow: hidden;
    border: 1px solid var(--holiday-line);
    border-radius: 16px;
    background: var(--holiday-paper);
    box-shadow: 0 5px 22px rgba(38, 59, 53, .045);
}

.holiday-form-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 22px 27px;
    border-bottom: 1px solid var(--holiday-line);
    background: #fbfaf5;
}

.form-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border: 1px solid #dce2d7;
    border-radius: 12px;
    background: #e9eee4;
    color: var(--holiday-ink);
    font-size: 18px;
}

.form-header-copy {
    min-width: 0;
}

.form-header-copy h2 {
    margin: 0 0 5px;
    color: var(--holiday-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 19px;
    font-weight: 700;
}

.form-header-copy p {
    margin: 0;
    color: var(--holiday-muted);
    font-size: 12px;
    line-height: 1.6;
}

.form-header-side {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-left: auto;
    padding: 8px 11px;
    border: 1px solid #e4e2d8;
    border-radius: 30px;
    background: var(--holiday-paper);
    color: var(--holiday-soft);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.form-header-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--holiday-sage);
}

.holiday-form-body {
    padding: 29px;
}

/* SECTION HEADINGS */

.holiday-section-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 24px;
}

.section-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border: 1px solid #dce2d7;
    border-radius: 11px;
    background: #edf0e7;
    color: var(--holiday-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 15px;
    font-weight: 700;
}

.holiday-section-heading h3 {
    margin: 0 0 4px;
    color: var(--holiday-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 17px;
    font-weight: 700;
}

.holiday-section-heading p {
    margin: 0;
    color: var(--holiday-muted);
    font-size: 11px;
    line-height: 1.6;
}

.holiday-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 27px;
    margin-bottom: 30px;
}

.holiday-field {
    min-width: 0;
}

.holiday-label {
    display: block;
    margin-bottom: 9px;
    color: var(--holiday-ink);
    font-size: 12px;
    font-weight: 750;
}

.required-star {
    margin-left: 3px;
    color: var(--holiday-red);
}

.optional-label {
    margin-left: 4px;
    color: var(--holiday-muted);
    font-size: 11px;
    font-weight: 400;
}

/* INPUTS */

.holiday-input-wrap {
    display: flex;
    align-items: stretch;
    min-width: 0;
    min-height: 48px;
    overflow: hidden;
    border: 1px solid #dedfd5;
    border-radius: 10px;
    background: #fffefa;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.holiday-input-wrap:focus-within {
    border-color: var(--holiday-sage);
    box-shadow: 0 0 0 3px rgba(82, 127, 112, .11);
}

.holiday-input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    flex-shrink: 0;
    border-right: 1px solid #e8e6dc;
    background: #f5f4ec;
    color: var(--holiday-soft);
    font-size: 15px;
}

.holiday-input {
    display: block;
    width: 100%;
    min-width: 0;
    min-height: 46px;
    padding: 11px 13px;
    border: 0;
    outline: 0;
    border-radius: 0;
    background: transparent;
    color: var(--holiday-ink);
    font-size: 13px;
    box-shadow: none;
}

.holiday-input::placeholder {
    color: #a0a397;
}

.holiday-input:focus {
    border: 0;
    outline: 0;
    box-shadow: none;
}

.holiday-input.is-invalid {
    border-color: transparent !important;
    background-image: none;
}

.holiday-input-wrap:has(.holiday-input.is-invalid) {
    border-color: var(--holiday-red);
}

.holiday-select {
    cursor: pointer;
    appearance: auto;
}

.holiday-field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--holiday-red);
    font-size: 11px;
    line-height: 1.6;
}

.holiday-help-text {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    margin-top: 10px;
    color: var(--holiday-muted);
    font-size: 11px;
    line-height: 1.7;
}

.holiday-help-text i {
    margin-top: 2px;
    color: var(--holiday-sage);
}

/* EXAM PERIOD */

.exam-period-panel {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 17px;
    padding: 14px;
    border: 1px solid #e2e3d7;
    border-radius: 11px;
    background: #f7f6ef;
}

.period-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex-shrink: 0;
    border: 1px solid #dce2d7;
    border-radius: 10px;
    background: #e9eee4;
    color: var(--holiday-ink);
    font-size: 15px;
}

.period-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.period-label {
    color: var(--holiday-muted);
    font-size: 10px;
    font-weight: 600;
}

.period-details strong {
    color: var(--holiday-ink);
    font-size: 12px;
    line-height: 1.6;
}

.period-separator {
    padding: 0 4px;
    color: var(--holiday-sage);
}

/* STATUS PANEL */

.holiday-status-panel {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 17px;
    padding: 15px;
    border: 1px solid;
    border-radius: 11px;
    transition: background .2s ease, border-color .2s ease;
}

.status-active {
    border-color: #d9e6d8;
    background: #f1f5ec;
}

.status-inactive {
    border-color: #e4e1d8;
    background: #f6f4ee;
}

.status-panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    border: 1px solid #d9e2d5;
    border-radius: 10px;
    background: #e4ecdf;
    color: var(--holiday-green);
    font-size: 16px;
}

.status-inactive .status-panel-icon {
    border-color: #e1ded5;
    background: #ebe9e1;
    color: #797f74;
}

.holiday-status-panel > div:last-child {
    min-width: 0;
}

.holiday-status-panel strong {
    display: block;
    margin-bottom: 5px;
    color: var(--holiday-ink);
    font-size: 12px;
}

.holiday-status-panel p {
    margin: 0;
    color: var(--holiday-muted);
    font-size: 11px;
    line-height: 1.7;
}

/* REASON SECTION */

.reason-section-heading {
    position: relative;
    margin-top: 7px;
    padding-top: 26px;
    border-top: 1px solid var(--holiday-line);
}

.optional-section-badge {
    margin-left: auto;
    padding: 6px 10px;
    border: 1px solid #e4e1d6;
    border-radius: 30px;
    background: #f7f5ed;
    color: var(--holiday-muted);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* RECORD NOTE */

.holiday-record-note {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-top: 27px;
    padding: 17px;
    border: 1px solid #dfe3d8;
    border-radius: 12px;
    background: #f5f5ed;
}

.record-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border: 1px solid #dce1d5;
    border-radius: 10px;
    background: #e8ede3;
    color: var(--holiday-ink);
    font-size: 16px;
}

.holiday-record-note > div:last-child {
    min-width: 0;
}

.holiday-record-note strong {
    display: block;
    margin-bottom: 5px;
    color: var(--holiday-ink);
    font-size: 12px;
}

.holiday-record-note p {
    margin: 0;
    color: var(--holiday-muted);
    font-size: 11px;
    line-height: 1.8;
}

/* FOOTER AND BUTTONS */

.holiday-form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    margin-top: 27px;
    padding-top: 23px;
    border-top: 1px solid var(--holiday-line);
}

.holiday-security-note {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--holiday-muted);
    font-size: 11px;
    line-height: 1.6;
}

.holiday-security-note i {
    color: var(--holiday-green);
    font-size: 16px;
}

.holiday-form-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}

.holiday-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 44px;
    padding: 0 17px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 750;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, transform .2s ease, box-shadow .2s ease;
}

.holiday-btn-cancel {
    border-color: #dedfd5;
    background: #fffefa;
    color: var(--holiday-soft);
}

.holiday-btn-cancel:hover {
    border-color: #c5cbbd;
    background: #f3f2e9;
    color: var(--holiday-ink);
}

.holiday-btn-save {
    border-color: var(--holiday-dark);
    background: linear-gradient(135deg, #344f43 0%, #20352c 100%);
    color: #fffefa;
    box-shadow: 0 4px 10px rgba(29, 48, 43, .12);
}

.holiday-btn-save:hover {
    border-color: #1d302b;
    background: #1d302b;
    color: #fffefa;
    box-shadow: 0 7px 16px rgba(29, 48, 43, .18);
    transform: translateY(-1px);
}

.holiday-btn:focus-visible,
.holiday-back-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 112, .35);
    outline-offset: 3px;
}

.save-arrow {
    margin-left: 3px;
    color: #e6e0cf;
    transition: transform .2s ease;
}

.holiday-btn-save:hover .save-arrow {
    transform: translateX(3px);
}

/* BOTTOM NOTE */

.holiday-bottom-note {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 18px;
    padding: 13px 3px 0;
    color: var(--holiday-muted);
    font-size: 11px;
    line-height: 1.7;
}

.holiday-bottom-note > i {
    color: var(--holiday-sage);
    font-size: 15px;
}

.holiday-bottom-note strong {
    color: var(--holiday-soft);
}

.bottom-note-divider {
    margin: 0 7px;
    color: #c1c1b5;
}

/* RESPONSIVE */

@media (max-width: 1050px) {
    .exam-holiday-page {
        padding: 20px;
    }

    .holiday-form-header,
    .holiday-form-body {
        padding: 23px;
    }

    .summary-card-content {
        gap: 11px;
        padding: 16px;
    }

    .summary-icon {
        width: 41px;
        height: 41px;
    }
}

@media (max-width: 767.98px) {
    .exam-holiday-page {
        padding: 14px;
    }

    .holiday-page-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 16px;
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

    .holiday-breadcrumb {
        margin-bottom: 17px;
    }

    .holiday-summary-grid {
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 18px;
    }

    .summary-card-content {
        padding: 15px 17px;
    }

    .holiday-form-header {
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 11px;
        padding: 18px;
    }

    .form-header-icon {
        width: 38px;
        height: 38px;
    }

    .form-header-copy {
        flex: 1;
    }

    .form-header-copy h2 {
        font-size: 17px;
    }

    .form-header-side {
        margin-left: 49px;
    }

    .holiday-form-body {
        padding: 19px;
    }

    .holiday-form-grid {
        grid-template-columns: 1fr;
        gap: 24px;
        margin-bottom: 26px;
    }

    .holiday-section-heading {
        gap: 11px;
        margin-bottom: 20px;
    }

    .holiday-section-heading h3 {
        font-size: 16px;
    }

    .holiday-section-heading p {
        font-size: 10px;
    }

    .section-number {
        width: 34px;
        height: 34px;
    }

    .holiday-form-footer {
        align-items: stretch;
        flex-direction: column;
        gap: 16px;
    }

    .holiday-form-actions {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        width: 100%;
    }

    .holiday-btn {
        padding: 0 10px;
        font-size: 11px;
    }

    .holiday-bottom-note {
        align-items: flex-start;
    }
}

@media (max-width: 420px) {
    .exam-holiday-page {
        padding: 11px;
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
        padding: 16px;
    }

    .holiday-form-actions {
        grid-template-columns: 1fr;
    }

    .holiday-btn {
        width: 100%;
        min-height: 45px;
    }

    .optional-section-badge {
        padding: 5px 7px;
        font-size: 9px;
    }

    .holiday-record-note {
        padding: 13px;
    }

    .bottom-note-divider {
        margin: 0 3px;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .exam-holiday-page *,
    .exam-holiday-page *::before,
    .exam-holiday-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
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
