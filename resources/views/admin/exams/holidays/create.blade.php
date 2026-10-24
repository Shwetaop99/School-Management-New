
@extends('layouts.app')

@section('title', 'Add Exam Holiday')

@section('content')

<div class="exam-holidays-page">

    {{-- PAGE HEADER --}}
    <div class="eh-page-header">

        <div class="eh-header-copy">
            <div class="eh-eyebrow">EXAMINATION MANAGEMENT</div>

            <div class="eh-title-row">
                <h1 class="eh-page-title">Add Exam Holiday</h1>
                <span class="eh-count-chip">New Holiday</span>
            </div>

            <p class="eh-page-description">
                Add a non-working day to the examination schedule.
            </p>

            <div class="eh-breadcrumb">
                <a href="{{ route('admin.exams.show', $exam) }}">Examination</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('admin.exam-holidays.index', $exam) }}">Exam Holidays</a>
                <i class="bi bi-chevron-right"></i>
                <span>Add Holiday</span>
            </div>
        </div>

        <div class="eh-header-actions">
            <a href="{{ route('admin.exam-holidays.index', $exam) }}"
               class="eh-btn eh-btn-secondary">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Holidays</span>
            </a>
        </div>

    </div>

    {{-- EXAM SUMMARY --}}
    <div class="eh-summary-grid">

        <div class="eh-summary-card">
            <div class="eh-summary-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div class="eh-summary-content">
                <div class="eh-summary-label">Examination</div>
                <div class="eh-summary-value">
                    {{ $exam->exam_name }}
                </div>
                <div class="eh-summary-note">Selected examination</div>
            </div>
        </div>

        <div class="eh-summary-card">
            <div class="eh-summary-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="eh-summary-content">
                <div class="eh-summary-label">Academic Year</div>
                <div class="eh-summary-value">
                    {{ $exam->academic_year }}
                </div>
                <div class="eh-summary-note">Current examination year</div>
            </div>
        </div>

        <div class="eh-summary-card">
            <div class="eh-summary-icon">
                <i class="bi bi-calendar-range"></i>
            </div>

            <div class="eh-summary-content">
                <div class="eh-summary-label">Exam Period</div>
                <div class="eh-summary-value">
                    @if($exam->start_date)
                        {{ \Illuminate\Support\Carbon::parse($exam->start_date)->format('d M Y') }}
                    @else
                        Not specified
                    @endif

                    @if($exam->end_date)
                        <span class="eh-date-separator">—</span>
                        {{ \Illuminate\Support\Carbon::parse($exam->end_date)->format('d M Y') }}
                    @endif
                </div>
                <div class="eh-summary-note">Scheduled examination dates</div>
            </div>
        </div>

    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="eh-alert eh-alert-error" role="alert">
            <div class="eh-alert-icon">
                <i class="bi bi-exclamation-circle"></i>
            </div>

            <div class="eh-alert-content">
                <strong>Please correct the following:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM PANEL --}}
    <section class="eh-panel">

        <div class="eh-panel-header">
            <div class="eh-panel-heading">
                <div class="eh-panel-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div>
                    <h2>Holiday Information</h2>
                    <p>Enter the date and details of the holiday.</p>
                </div>
            </div>

            <div class="eh-required-note">
                <span class="eh-required-star">*</span>
                Required field
            </div>
        </div>

        <div class="eh-form-body">

            <form method="POST"
                  action="{{ route('admin.exam-holidays.store', $exam) }}">
                @csrf

                <div class="eh-form-grid">

                    {{-- HOLIDAY DATE --}}
                    <div class="eh-form-field">
                        <label for="holiday_date" class="eh-form-label">
                            <i class="bi bi-calendar-date"></i>
                            Holiday Date
                            <span class="eh-required-star">*</span>
                        </label>

                        <div class="eh-input-wrap">
                            <span class="eh-input-icon">
                                <i class="bi bi-calendar3"></i>
                            </span>

                            <input
                                type="date"
                                id="holiday_date"
                                name="holiday_date"
                                class="eh-form-control @error('holiday_date') eh-input-invalid @enderror"
                                value="{{ old('holiday_date') }}"
                                min="{{ \Illuminate\Support\Carbon::parse($exam->start_date)->format('Y-m-d') }}"
                                max="{{ $exam->end_date
                                    ? \Illuminate\Support\Carbon::parse($exam->end_date)->format('Y-m-d')
                                    : \Illuminate\Support\Carbon::parse($exam->start_date)->format('Y-m-d') }}"
                                required
                            >
                        </div>

                        @error('holiday_date')
                            <div class="eh-field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="eh-field-help">
                            <i class="bi bi-info-circle"></i>
                            Choose a date within the examination period.
                        </div>

                        <div class="eh-period-box">
                            <div class="eh-period-icon">
                                <i class="bi bi-calendar-range"></i>
                            </div>

                            <div class="eh-period-content">
                                <span class="eh-period-label">Examination Period</span>

                                <strong>
                                    @if($exam->start_date)
                                        {{ \Illuminate\Support\Carbon::parse($exam->start_date)->format('d M Y') }}
                                    @else
                                        Not specified
                                    @endif

                                    @if($exam->end_date)
                                        <span class="eh-date-separator">—</span>
                                        {{ \Illuminate\Support\Carbon::parse($exam->end_date)->format('d M Y') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="eh-form-field">
                        <label for="status" class="eh-form-label">
                            <i class="bi bi-toggle-on"></i>
                            Holiday Status
                        </label>

                        <div class="eh-input-wrap">
                            <span class="eh-input-icon">
                                <i class="bi bi-check2-circle"></i>
                            </span>

                            <select
                                id="status"
                                name="status"
                                class="eh-form-control eh-form-select @error('status') eh-input-invalid @enderror"
                            >
                                <option value="1"
                                    {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                    Active — Non-working
                                </option>

                                <option value="0"
                                    {{ old('status') === '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        @error('status')
                            <div class="eh-field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="eh-field-help">
                            <i class="bi bi-info-circle"></i>
                            Active holidays are intended to mark non-working days.
                        </div>

                        <div class="eh-status-info" id="ehStatusInfo">
                            <div class="eh-status-info-icon" id="ehStatusIcon">
                                <i class="bi bi-check-circle"></i>
                            </div>

                            <div>
                                <strong id="ehStatusTitle">Active holiday</strong>
                                <p id="ehStatusDescription">
                                    The timetable generator should skip this date
                                    when its holiday-checking logic is enabled.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- REASON --}}
                    <div class="eh-form-field eh-form-field-full">
                        <label for="reason" class="eh-form-label">
                            <i class="bi bi-chat-left-text"></i>
                            Reason
                            <span class="eh-optional-label">(Optional)</span>
                        </label>

                        <input
                            type="text"
                            id="reason"
                            name="reason"
                            class="eh-form-control @error('reason') eh-input-invalid @enderror"
                            value="{{ old('reason') }}"
                            placeholder="Example: Sunday, Diwali Holiday, Public Holiday"
                            maxlength="255"
                        >

                        @error('reason')
                            <div class="eh-field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="eh-field-help">
                            <i class="bi bi-info-circle"></i>
                            Add a short description explaining why this date is a holiday.
                            <span class="eh-character-count">
                                <span id="ehReasonCount">{{ strlen(old('reason', '')) }}</span>/255
                            </span>
                        </div>
                    </div>

                </div>

                {{-- SCHEDULING NOTICE --}}
                <div class="eh-notice">
                    <div class="eh-notice-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>

                    <div class="eh-notice-content">
                        <h3>Scheduling Tip</h3>
                        <p>
                            Add holidays before generating the examination timetable.
                            The timetable generation logic must check active holiday
                            records to avoid scheduling examinations on those dates.
                        </p>
                    </div>
                </div>

                {{-- FORM FOOTER --}}
                <div class="eh-form-footer">
                    <div class="eh-footer-guidance">
                        <i class="bi bi-shield-check"></i>
                        Review the holiday details before saving.
                    </div>

                    <div class="eh-form-actions">
                        <a href="{{ route('admin.exam-holidays.index', $exam) }}"
                           class="eh-btn eh-btn-secondary">
                            <i class="bi bi-x-lg"></i>
                            <span>Cancel</span>
                        </a>

                        <button type="submit" class="eh-btn eh-btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            <span>Save Holiday</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </section>

    <div class="eh-page-footer">
        <span>Exam Holidays</span>
        <span>Manage examination non-working days</span>
    </div>

</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Scoped exclusively to Exam Holidays Create
========================================================= */

.exam-holidays-page {
    --eh-page: #f7f6f0;
    --eh-paper: #fffefa;
    --eh-chalk: #f5f3e9;
    --eh-ink: #263b35;
    --eh-dark: #1d302b;
    --eh-soft: #40574a;
    --eh-text: #39483e;
    --eh-muted: #7b857b;
    --eh-line: #e5e1d5;
    --eh-sage: #527f70;
    --eh-blue: #426b8a;
    --eh-amber: #a87543;
    --eh-green: #43835d;
    --eh-red: #b95b50;

    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
    color: var(--eh-text);
    font-family: "Public Sans", sans-serif;
}

.exam-holidays-page *,
.exam-holidays-page *::before,
.exam-holidays-page *::after {
    box-sizing: border-box;
}

.exam-holidays-page a {
    text-decoration: none;
}

/* HEADER */

.eh-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 25px;
}

.eh-header-copy {
    min-width: 0;
}

.eh-eyebrow {
    margin-bottom: 8px;
    color: var(--eh-sage);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15em;
}

.eh-title-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.eh-page-title {
    margin: 0;
    color: var(--eh-ink);
    font-family: Georgia, serif;
    font-size: clamp(26px, 3vw, 34px);
    font-weight: 700;
    letter-spacing: -.045em;
    line-height: 1.2;
}

.eh-count-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 11px;
    border: 1px solid var(--eh-line);
    border-radius: 30px;
    background: var(--eh-paper);
    color: var(--eh-soft);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.eh-page-description {
    margin: 9px 0 13px;
    color: var(--eh-muted);
    font-size: 13px;
    line-height: 1.7;
}

.eh-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    color: var(--eh-muted);
    font-size: 11px;
}

.eh-breadcrumb a {
    color: var(--eh-sage);
    font-weight: 700;
}

.eh-breadcrumb a:hover {
    color: var(--eh-dark);
}

.eh-breadcrumb i {
    font-size: 9px;
}

.eh-header-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 9px;
    flex-shrink: 0;
    padding-top: 17px;
}

/* BUTTONS */

.exam-holidays-page .eh-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 10px 16px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.3;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease,
                color .18s ease, transform .18s ease;
}

.exam-holidays-page .eh-btn:hover {
    transform: translateY(-1px);
}

.exam-holidays-page .eh-btn-primary {
    border-color: var(--eh-ink);
    background: var(--eh-ink);
    color: var(--eh-paper);
}

.exam-holidays-page .eh-btn-primary:hover {
    border-color: var(--eh-dark);
    background: var(--eh-dark);
    color: #fff;
}

.exam-holidays-page .eh-btn-secondary {
    border-color: var(--eh-line);
    background: var(--eh-paper);
    color: var(--eh-ink);
}

.exam-holidays-page .eh-btn-secondary:hover {
    border-color: #c9c7b9;
    background: var(--eh-chalk);
    color: var(--eh-dark);
}

/* SUMMARY CARDS */

.eh-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 22px;
}

.eh-summary-card {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-width: 0;
    padding: 20px;
    overflow: hidden;
    border: 1px solid var(--eh-line);
    border-radius: 12px;
    background: var(--eh-paper);
    box-shadow: 0 3px 10px rgb(38 59 53 / 3%);
    transition: border-color .2s ease, box-shadow .2s ease,
                transform .2s ease;
}

.eh-summary-card::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--eh-sage);
    content: "";
}

.eh-summary-card:nth-child(2)::before {
    background: var(--eh-blue);
}

.eh-summary-card:nth-child(3)::before {
    background: var(--eh-amber);
}

.eh-summary-card:hover {
    transform: translateY(-2px);
    border-color: #d6d6c9;
    box-shadow: 0 8px 20px rgb(38 59 53 / 6%);
}

.eh-summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    border: 1px solid #dce7df;
    border-radius: 10px;
    background: #edf3ed;
    color: var(--eh-sage);
    font-size: 18px;
}

.eh-summary-card:nth-child(2) .eh-summary-icon {
    border-color: #dce5ec;
    background: #edf2f5;
    color: var(--eh-blue);
}

.eh-summary-card:nth-child(3) .eh-summary-icon {
    border-color: #eee0cf;
    background: #f7efe4;
    color: var(--eh-amber);
}

.eh-summary-content {
    min-width: 0;
    flex: 1;
}

.eh-summary-label {
    margin: 1px 0 6px;
    color: var(--eh-muted);
    font-size: 11px;
    font-weight: 600;
}

.eh-summary-value {
    overflow-wrap: anywhere;
    color: var(--eh-ink);
    font-size: 15px;
    font-weight: 750;
    line-height: 1.55;
}

.eh-summary-note {
    margin-top: 5px;
    color: var(--eh-muted);
    font-size: 10px;
    line-height: 1.5;
}

.eh-date-separator {
    padding: 0 3px;
    color: var(--eh-muted);
}

/* VALIDATION */

.eh-alert {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 20px;
    padding: 16px;
    border: 1px solid var(--eh-line);
    border-radius: 10px;
    background: var(--eh-paper);
}

.eh-alert-error {
    border-color: #ecd5ce;
    background: #fcf5f1;
}

.eh-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    border: 1px solid #ecd5ce;
    border-radius: 9px;
    background: #f6e7e1;
    color: var(--eh-red);
    font-size: 17px;
}

.eh-alert-content {
    min-width: 0;
    flex: 1;
}

.eh-alert-content strong {
    display: block;
    margin: 2px 0 4px;
    color: var(--eh-ink);
    font-size: 12px;
}

.eh-alert-content ul {
    margin: 6px 0 0;
    padding-left: 18px;
    color: var(--eh-text);
    font-size: 12px;
    line-height: 1.8;
}

/* MAIN PANEL */

.eh-panel {
    overflow: hidden;
    border: 1px solid var(--eh-line);
    border-radius: 13px;
    background: var(--eh-paper);
    box-shadow: 0 4px 15px rgb(38 59 53 / 3%);
}

.eh-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 20px 22px;
    border-bottom: 1px solid var(--eh-line);
    background: var(--eh-paper);
}

.eh-panel-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.eh-panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    border: 1px solid #dce7df;
    border-radius: 10px;
    background: #edf3ed;
    color: var(--eh-sage);
    font-size: 18px;
}

.eh-panel-heading h2 {
    margin: 0 0 5px;
    color: var(--eh-ink);
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.025em;
}

.eh-panel-heading p {
    margin: 0;
    color: var(--eh-muted);
    font-size: 11px;
    line-height: 1.6;
}

.eh-required-note {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
    color: var(--eh-muted);
    font-size: 10px;
}

.eh-required-star {
    color: var(--eh-red);
    font-weight: 800;
}

.eh-form-body {
    padding: 27px;
}

/* FORM GRID AND LABELS */

.eh-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 27px 24px;
}

.eh-form-field {
    min-width: 0;
}

.eh-form-field-full {
    grid-column: 1 / -1;
}

.eh-form-label {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 10px;
    color: var(--eh-ink);
    font-size: 12px;
    font-weight: 750;
}

.eh-form-label > i {
    color: var(--eh-sage);
    font-size: 14px;
}

.eh-optional-label {
    color: var(--eh-muted);
    font-size: 10px;
    font-weight: 500;
}

/* INPUTS */

.eh-input-wrap {
    display: flex;
    align-items: stretch;
    min-width: 0;
    overflow: hidden;
    border: 1px solid #dcded3;
    border-radius: 8px;
    background: var(--eh-paper);
    transition: border-color .18s ease, box-shadow .18s ease;
}

.eh-input-wrap:focus-within {
    border-color: var(--eh-sage);
    box-shadow: 0 0 0 3px rgb(82 127 112 / 12%);
}

.eh-input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    flex: 0 0 45px;
    border-right: 1px solid var(--eh-line);
    background: #f5f4ec;
    color: var(--eh-sage);
    font-size: 15px;
}

.exam-holidays-page .eh-form-control {
    display: block;
    width: 100%;
    min-width: 0;
    min-height: 46px;
    padding: 11px 13px;
    border: 1px solid #dcded3;
    border-radius: 8px;
    outline: none;
    background: var(--eh-paper);
    color: var(--eh-ink);
    font-family: inherit;
    font-size: 12px;
    line-height: 1.5;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.exam-holidays-page .eh-input-wrap .eh-form-control {
    min-width: 0;
    flex: 1;
    border: 0;
    border-radius: 0;
    background: transparent;
}

.exam-holidays-page .eh-form-control:focus {
    border-color: var(--eh-sage);
    outline: none;
    box-shadow: 0 0 0 3px rgb(82 127 112 / 12%);
}

.exam-holidays-page .eh-input-wrap .eh-form-control:focus {
    box-shadow: none;
}

.exam-holidays-page .eh-form-control::placeholder {
    color: #9a9f94;
    opacity: 1;
}

.exam-holidays-page .eh-form-control.eh-input-invalid {
    border-color: var(--eh-red);
}

.eh-input-wrap:has(.eh-input-invalid) {
    border-color: var(--eh-red);
}

.eh-field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--eh-red);
    font-size: 11px;
    line-height: 1.6;
}

.eh-field-error i {
    margin-top: 2px;
    flex-shrink: 0;
}

.eh-field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 9px;
    color: var(--eh-muted);
    font-size: 10px;
    line-height: 1.7;
}

.eh-field-help > i {
    margin-top: 2px;
    flex-shrink: 0;
    color: var(--eh-sage);
}

.eh-character-count {
    margin-left: auto;
    padding-left: 8px;
    color: var(--eh-muted);
    white-space: nowrap;
}

/* EXAM PERIOD */

.eh-period-box {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-top: 15px;
    padding: 13px;
    border: 1px solid var(--eh-line);
    border-radius: 9px;
    background: var(--eh-chalk);
}

.eh-period-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    border: 1px solid #dce7df;
    border-radius: 8px;
    background: var(--eh-paper);
    color: var(--eh-sage);
    font-size: 15px;
}

.eh-period-content {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.eh-period-label {
    color: var(--eh-muted);
    font-size: 10px;
}

.eh-period-content strong {
    color: var(--eh-ink);
    font-size: 12px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

/* STATUS INFO */

.eh-status-info {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-top: 15px;
    padding: 14px;
    border: 1px solid #d4e4d7;
    border-radius: 9px;
    background: #f1f6ee;
    transition: background .18s ease, border-color .18s ease;
}

.eh-status-info.is-inactive {
    border-color: var(--eh-line);
    background: #f5f4ee;
}

.eh-status-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 33px;
    height: 33px;
    flex: 0 0 33px;
    border: 1px solid #d4e4d7;
    border-radius: 8px;
    background: var(--eh-paper);
    color: var(--eh-green);
    font-size: 15px;
}

.eh-status-info.is-inactive .eh-status-info-icon {
    border-color: var(--eh-line);
    color: var(--eh-muted);
}

.eh-status-info strong {
    display: block;
    margin: 1px 0 5px;
    color: var(--eh-ink);
    font-size: 11px;
}

.eh-status-info p {
    margin: 0;
    color: var(--eh-muted);
    font-size: 10px;
    line-height: 1.75;
}

/* NOTICE */

.eh-notice {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-top: 27px;
    padding: 16px 17px;
    border: 1px solid #e9ddca;
    border-radius: 10px;
    background: #f8f3e9;
}

.eh-notice-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border: 1px solid #e9ddca;
    border-radius: 9px;
    background: var(--eh-paper);
    color: var(--eh-amber);
    font-size: 16px;
}

.eh-notice-content {
    min-width: 0;
}

.eh-notice-content h3 {
    margin: 1px 0 5px;
    color: var(--eh-ink);
    font-size: 12px;
    font-weight: 750;
}

.eh-notice-content p {
    margin: 0;
    color: var(--eh-muted);
    font-size: 11px;
    line-height: 1.8;
}

/* FORM FOOTER */

.eh-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid var(--eh-line);
}

.eh-footer-guidance {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--eh-muted);
    font-size: 10px;
    line-height: 1.6;
}

.eh-footer-guidance i {
    color: var(--eh-sage);
    font-size: 14px;
}

.eh-form-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 9px;
}

/* PAGE FOOTER */

.eh-page-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 17px;
    padding: 0 3px;
    color: var(--eh-muted);
    font-size: 10px;
}

.eh-page-footer span:first-child {
    color: var(--eh-soft);
    font-weight: 750;
}

/* ACCESSIBILITY */

.exam-holidays-page a:focus-visible,
.exam-holidays-page button:focus-visible,
.exam-holidays-page input:focus-visible,
.exam-holidays-page select:focus-visible {
    outline: 3px solid rgb(82 127 112 / 30%);
    outline-offset: 3px;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .exam-holidays-page {
        padding: 20px;
    }

    .eh-page-header {
        flex-direction: column;
    }

    .eh-header-actions {
        justify-content: flex-start;
        padding-top: 0;
    }

    .eh-summary-card {
        padding: 17px;
    }
}

@media (max-width: 767.98px) {
    .exam-holidays-page {
        padding: 14px;
    }

    .eh-page-header {
        gap: 17px;
        margin-bottom: 20px;
    }

    .eh-title-row {
        align-items: flex-start;
        gap: 9px;
    }

    .eh-page-title {
        font-size: 27px;
    }

    .eh-page-description {
        font-size: 12px;
    }

    .eh-header-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .eh-summary-grid {
        grid-template-columns: 1fr;
        gap: 10px;
        margin-bottom: 17px;
    }

    .eh-summary-card {
        padding: 15px;
    }

    .eh-summary-value {
        font-size: 14px;
    }

    .eh-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 16px;
    }

    .eh-panel-heading h2 {
        font-size: 18px;
    }

    .eh-required-note {
        align-self: flex-start;
    }

    .eh-form-body {
        padding: 20px;
    }

    .eh-form-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .eh-form-field-full {
        grid-column: auto;
    }

    .eh-notice {
        margin-top: 23px;
        padding: 13px;
    }

    .eh-form-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .eh-form-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        width: 100%;
    }

    .exam-holidays-page .eh-form-actions .eh-btn {
        width: 100%;
    }

    .eh-page-footer {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 380px) {
    .exam-holidays-page {
        padding: 10px;
    }

    .eh-page-title {
        font-size: 24px;
    }

    .eh-count-chip {
        padding: 5px 8px;
        font-size: 10px;
    }

    .eh-form-body {
        padding: 15px;
    }

    .eh-form-actions {
        grid-template-columns: 1fr;
    }

    .eh-footer-guidance {
        align-items: flex-start;
    }
}

@media (prefers-reduced-motion: reduce) {
    .exam-holidays-page *,
    .exam-holidays-page *::before,
    .exam-holidays-page *::after {
        scroll-behavior: auto !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('status');
    const statusInfo = document.getElementById('ehStatusInfo');
    const statusIcon = document.getElementById('ehStatusIcon');
    const statusTitle = document.getElementById('ehStatusTitle');
    const statusDescription = document.getElementById('ehStatusDescription');

    function updateStatusInfo() {
        if (!statusSelect || !statusInfo) {
            return;
        }

        if (statusSelect.value === '1') {
            statusInfo.classList.remove('is-inactive');
            statusIcon.innerHTML = '<i class="bi bi-check-circle"></i>';
            statusTitle.textContent = 'Active holiday';
            statusDescription.textContent =
                'The timetable generator should skip this date when its holiday-checking logic is enabled.';
        } else {
            statusInfo.classList.add('is-inactive');
            statusIcon.innerHTML = '<i class="bi bi-pause-circle"></i>';
            statusTitle.textContent = 'Inactive holiday';
            statusDescription.textContent =
                'This record is marked inactive and should not be treated as a non-working day by the timetable generator.';
        }
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', updateStatusInfo);
        updateStatusInfo();
    }

    const reasonInput = document.getElementById('reason');
    const reasonCount = document.getElementById('ehReasonCount');

    function updateReasonCount() {
        if (reasonInput && reasonCount) {
            reasonCount.textContent = reasonInput.value.length;
        }
    }

    if (reasonInput) {
        reasonInput.addEventListener('input', updateReasonCount);
        updateReasonCount();
    }
});
</script>

@endsection
