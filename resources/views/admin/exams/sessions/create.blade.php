
@extends('layouts.app')

@section('title', 'Add Exam Session')

@section('content')

<div class="exam-session-page">

    {{-- PAGE HEADER --}}
    <div class="exam-session-header">
        <div class="header-main">
            <div class="breadcrumb-line">
                <a href="{{ route('admin.exam-sessions.index', $exam) }}">
                    Examinations
                </a>
                <i class="bi bi-chevron-right"></i>
                <span>Add Exam Session</span>
            </div>

            <div class="header-title-row">
                <div class="page-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>
                    <h1>Add Exam Session</h1>
                    <p>Create and configure a time slot for this examination.</p>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.exam-sessions.index', $exam) }}"
           class="diary-btn diary-btn-secondary">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Sessions</span>
        </a>
    </div>


    {{-- EXAMINATION SUMMARY --}}
    <div class="exam-summary">
        <div class="exam-summary-main">
            <div class="exam-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div class="exam-summary-details">
                <span class="eyebrow">EXAMINATION DETAILS</span>

                <h2>{{ $exam->exam_name }}</h2>

                <div class="exam-meta">
                    <span>
                        <i class="bi bi-calendar3"></i>
                        Academic Year:
                        <strong>{{ $exam->academic_year }}</strong>
                    </span>

                    @if($exam->exam_type)
                        <span class="meta-separator">•</span>
                        <span>
                            <i class="bi bi-tag"></i>
                            {{ $exam->exam_type }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="exam-summary-side">
            <div class="summary-side-icon">
                <i class="bi bi-clock"></i>
            </div>

            <div>
                <span class="summary-side-label">SESSION TYPE</span>
                <strong>Time Slot Configuration</strong>
                <small>Examination scheduling</small>
            </div>
        </div>
    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="validation-panel">
            <div class="validation-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div>
                <h3>Please check the following details</h3>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- MAIN FORM CARD --}}
    <div class="form-card">

        <div class="form-card-header">
            <div class="section-icon">
                <i class="bi bi-sliders"></i>
            </div>

            <div>
                <h2>Session Information</h2>
                <p>Define the session name, examination timings and status.</p>
            </div>

            <span class="required-note">
                <span>*</span> Required fields
            </span>
        </div>


        <div class="form-card-body">

            <form method="POST"
                  action="{{ route('admin.exam-sessions.store', $exam) }}">

                @csrf

                {{-- SESSION DETAILS --}}
                <div class="form-section">
                    <div class="form-section-heading">
                        <span class="section-number">01</span>
                        <div>
                            <h3>Session Details</h3>
                            <p>Enter the session name and availability.</p>
                        </div>
                    </div>

                    <div class="form-grid">

                        {{-- SESSION NAME --}}
                        <div class="form-field">
                            <label for="session_name" class="form-label">
                                Session Name <span class="required">*</span>
                            </label>

                            <div class="input-shell">
                                <span class="input-icon">
                                    <i class="bi bi-clock"></i>
                                </span>

                                <input
                                    type="text"
                                    id="session_name"
                                    name="session_name"
                                    class="form-control @error('session_name') is-invalid @enderror"
                                    value="{{ old('session_name') }}"
                                    placeholder="e.g. Morning Session"
                                    required
                                >
                            </div>

                            @error('session_name')
                                <div class="field-error">{{ $message }}</div>
                            @else
                                <div class="field-hint">
                                    Example: Morning Session or Afternoon Session.
                                </div>
                            @enderror
                        </div>


                        {{-- STATUS --}}
                        <div class="form-field">
                            <label for="status" class="form-label">
                                Session Status
                            </label>

                            <div class="input-shell">
                                <span class="input-icon">
                                    <i class="bi bi-toggle-on"></i>
                                </span>

                                <select
                                    id="status"
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
                                <div class="field-error">{{ $message }}</div>
                            @else
                                <div class="field-hint">
                                    Active sessions can be used for examination scheduling.
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- TIME CONFIGURATION --}}
                <div class="form-section time-section">
                    <div class="form-section-heading">
                        <span class="section-number">02</span>
                        <div>
                            <h3>Session Timing</h3>
                            <p>Set the start and end time for the examination.</p>
                        </div>
                    </div>

                    <div class="form-grid">

                        {{-- START TIME --}}
                        <div class="form-field">
                            <label for="start_time" class="form-label">
                                Start Time <span class="required">*</span>
                            </label>

                            <div class="input-shell">
                                <span class="input-icon">
                                    <i class="bi bi-play-circle"></i>
                                </span>

                                <input
                                    type="time"
                                    id="start_time"
                                    name="start_time"
                                    class="form-control @error('start_time') is-invalid @enderror"
                                    value="{{ old('start_time', '10:00') }}"
                                    required
                                >
                            </div>

                            @error('start_time')
                                <div class="field-error">{{ $message }}</div>
                            @else
                                <div class="field-hint">
                                    When should this session begin?
                                </div>
                            @enderror
                        </div>


                        {{-- END TIME --}}
                        <div class="form-field">
                            <label for="end_time" class="form-label">
                                End Time <span class="required">*</span>
                            </label>

                            <div class="input-shell">
                                <span class="input-icon">
                                    <i class="bi bi-stop-circle"></i>
                                </span>

                                <input
                                    type="time"
                                    id="end_time"
                                    name="end_time"
                                    class="form-control @error('end_time') is-invalid @enderror"
                                    value="{{ old('end_time', '13:00') }}"
                                    required
                                >
                            </div>

                            @error('end_time')
                                <div class="field-error">{{ $message }}</div>
                            @else
                                <div class="field-hint">
                                    When should this session finish?
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- TIME PREVIEW --}}
                <div class="session-preview">
                    <div class="preview-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div class="preview-content">
                        <strong>Session scheduling information</strong>
                        <p>
                            This time slot will be available when generating
                            the examination timetable.
                        </p>
                    </div>
                </div>


                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.exam-sessions.index', $exam) }}"
                       class="diary-btn diary-btn-secondary">
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button type="submit" class="diary-btn diary-btn-primary">
                        <i class="bi bi-check2-circle"></i>
                        Save Session
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="page-footer-note">
        <i class="bi bi-shield-check"></i>
        <span>School Diary <span class="footer-dot">•</span> Examination Management</span>
    </div>

</div>


{{-- PAGE STYLES --}}
<style>
    .exam-session-page {
        --page: #f7f6f0;
        --paper: #fffefa;
        --chalk: #f5f3e9;
        --ink: #263b35;
        --ink-dark: #1d302b;
        --soft-ink: #40574a;
        --text: #39483e;
        --muted: #7b857b;
        --line: #e5e1d5;
        --sage: #527f70;
        --sage-light: #edf2ec;
        --danger: #b95b50;

        padding: 24px;
        color: var(--text);
        background: var(--page);
        border-radius: 16px;
        font-family: "Public Sans", sans-serif;
    }

    .exam-session-page,
    .exam-session-page * {
        box-sizing: border-box;
    }

    /* HEADER */

    .exam-session-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 25px;
    }

    .header-main {
        min-width: 0;
    }

    .breadcrumb-line {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-bottom: 14px;
        color: var(--muted);
        font-size: 12px;
    }

    .breadcrumb-line a {
        color: var(--soft-ink);
        text-decoration: none;
    }

    .breadcrumb-line a:hover {
        color: var(--ink-dark);
        text-decoration: underline;
    }

    .breadcrumb-line i {
        font-size: 9px;
        color: #a5a99e;
    }

    .header-title-row {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .page-icon {
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
        color: var(--ink);
        font-size: 20px;
    }

    .header-title-row h1 {
        margin: 0;
        color: var(--ink-dark);
        font-family: Georgia, serif;
        font-size: clamp(23px, 2.2vw, 30px);
        font-weight: 700;
        letter-spacing: -0.6px;
    }

    .header-title-row p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    /* BUTTONS */

    .diary-btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.3;
        text-decoration: none;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease,
                    color .18s ease, transform .18s ease;
    }

    .diary-btn:hover {
        transform: translateY(-1px);
    }

    .diary-btn-secondary {
        background: var(--paper);
        border-color: var(--line);
        color: var(--ink);
    }

    .diary-btn-secondary:hover {
        background: var(--chalk);
        border-color: #cbcfc1;
        color: var(--ink-dark);
    }

    .diary-btn-primary {
        background: var(--ink);
        border-color: var(--ink);
        color: #fffefa;
    }

    .diary-btn-primary:hover {
        background: var(--ink-dark);
        border-color: var(--ink-dark);
        color: #fffefa;
    }

    /* EXAM SUMMARY */

    .exam-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 22px;
        margin-bottom: 22px;
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 14px;
    }

    .exam-summary-main {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .exam-icon {
        width: 52px;
        height: 52px;
        flex: 0 0 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d9e0d5;
        border-radius: 13px;
        background: var(--sage-light);
        color: var(--ink);
        font-size: 22px;
    }

    .exam-summary-details {
        min-width: 0;
    }

    .eyebrow,
    .summary-side-label {
        display: block;
        margin-bottom: 6px;
        color: var(--muted);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.1px;
    }

    .exam-summary-details h2 {
        margin: 0;
        color: var(--ink-dark);
        font-family: Georgia, serif;
        font-size: 21px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .exam-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-top: 9px;
        color: var(--muted);
        font-size: 12px;
    }

    .exam-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .exam-meta strong {
        color: var(--text);
        font-weight: 700;
    }

    .meta-separator {
        color: #c5c6ba;
    }

    .exam-summary-side {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 240px;
        padding: 14px 16px;
        background: var(--chalk);
        border: 1px solid var(--line);
        border-radius: 11px;
    }

    .summary-side-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: var(--paper);
        color: var(--ink);
        font-size: 16px;
    }

    .exam-summary-side strong {
        display: block;
        color: var(--ink);
        font-size: 12px;
        font-weight: 700;
    }

    .exam-summary-side small {
        display: block;
        margin-top: 4px;
        color: var(--muted);
        font-size: 11px;
    }

    /* VALIDATION */

    .validation-panel {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 17px 19px;
        margin-bottom: 22px;
        background: #fff9f6;
        border: 1px solid #ead4cd;
        border-radius: 11px;
        color: #7e4038;
    }

    .validation-icon {
        width: 35px;
        height: 35px;
        flex: 0 0 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8e9e3;
        border-radius: 9px;
        font-size: 17px;
    }

    .validation-panel h3 {
        margin: 2px 0 8px;
        font-size: 14px;
        font-weight: 700;
    }

    .validation-panel ul {
        padding-left: 18px;
        margin: 0;
        font-size: 12px;
        line-height: 1.8;
    }

    /* MAIN FORM CARD */

    .form-card {
        overflow: hidden;
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 15px;
        box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
    }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 21px 25px;
        border-bottom: 1px solid var(--line);
        background: #fffefa;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dce3d9;
        border-radius: 11px;
        background: var(--sage-light);
        color: var(--ink);
        font-size: 18px;
    }

    .form-card-header h2 {
        margin: 0;
        color: var(--ink);
        font-family: Georgia, serif;
        font-size: 18px;
        font-weight: 700;
    }

    .form-card-header p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.5;
    }

    .required-note {
        margin-left: auto;
        color: var(--muted);
        white-space: nowrap;
        font-size: 11px;
    }

    .required,
    .required-note span {
        color: var(--danger);
    }

    .form-card-body {
        padding: 26px;
    }

    /* FORM SECTIONS */

    .form-section {
        padding-bottom: 26px;
        margin-bottom: 25px;
        border-bottom: 1px solid var(--line);
    }

    .form-section-heading {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 22px;
    }

    .section-number {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: var(--chalk);
        color: var(--ink);
        font-size: 12px;
        font-weight: 700;
    }

    .form-section-heading h3 {
        margin: 1px 0 5px;
        color: var(--ink);
        font-size: 14px;
        font-weight: 700;
    }

    .form-section-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 23px 25px;
    }

    .form-field {
        min-width: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .input-shell {
        display: flex;
        align-items: stretch;
        min-width: 0;
    }

    .input-icon {
        width: 44px;
        min-width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--chalk);
        border: 1px solid var(--line);
        border-right: 0;
        border-radius: 9px 0 0 9px;
        color: var(--soft-ink);
        font-size: 15px;
    }

    .exam-session-page .form-control,
    .exam-session-page .form-select {
        width: 100%;
        min-width: 0;
        min-height: 45px;
        padding: 10px 12px;
        background-color: var(--paper);
        border: 1px solid var(--line);
        border-radius: 0 9px 9px 0;
        color: var(--text);
        font-family: inherit;
        font-size: 13px;
        box-shadow: none;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .exam-session-page .form-control:focus,
    .exam-session-page .form-select:focus {
        border-color: var(--sage);
        box-shadow: 0 0 0 3px rgba(82, 127, 112, .11);
        z-index: 1;
    }

    .exam-session-page .form-control.is-invalid,
    .exam-session-page .form-select.is-invalid {
        border-color: var(--danger);
    }

    .field-hint {
        margin-top: 7px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.6;
    }

    .field-error {
        margin-top: 7px;
        color: var(--danger);
        font-size: 12px;
        line-height: 1.5;
    }

    /* SESSION PREVIEW */

    .session-preview {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 17px;
        margin-top: 3px;
        background: var(--chalk);
        border: 1px solid var(--line);
        border-radius: 11px;
    }

    .preview-icon {
        width: 37px;
        height: 37px;
        flex: 0 0 37px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: var(--paper);
        color: var(--ink);
        font-size: 16px;
    }

    .preview-content strong {
        display: block;
        margin: 1px 0 5px;
        color: var(--ink);
        font-size: 12px;
        font-weight: 700;
    }

    .preview-content p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    /* FORM ACTIONS */

    .form-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 23px;
        margin-top: 24px;
        border-top: 1px solid var(--line);
    }

    /* FOOTER */

    .page-footer-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        color: var(--muted);
        font-size: 11px;
    }

    .page-footer-note > i {
        color: var(--soft-ink);
        font-size: 14px;
    }

    .footer-dot {
        padding: 0 3px;
        color: #b3b5a8;
    }

    /* RESPONSIVE */

    @media (max-width: 900px) {
        .exam-summary {
            align-items: stretch;
            flex-direction: column;
        }

        .exam-summary-side {
            min-width: 0;
        }
    }

    @media (max-width: 767.98px) {
        .exam-session-page {
            padding: 16px;
            border-radius: 12px;
        }

        .exam-session-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 15px;
        }

        .exam-session-header > .diary-btn {
            width: 100%;
        }

        .header-title-row {
            align-items: flex-start;
            gap: 11px;
        }

        .page-icon {
            width: 41px;
            height: 41px;
            flex-basis: 41px;
            font-size: 18px;
        }

        .header-title-row h1 {
            font-size: 24px;
        }

        .header-title-row p {
            font-size: 12px;
        }

        .exam-summary {
            padding: 17px;
            gap: 17px;
        }

        .exam-summary-main {
            align-items: flex-start;
            gap: 12px;
        }

        .exam-icon {
            width: 43px;
            height: 43px;
            flex-basis: 43px;
            font-size: 19px;
        }

        .exam-summary-details h2 {
            font-size: 18px;
        }

        .exam-meta {
            align-items: flex-start;
            flex-direction: column;
            gap: 7px;
        }

        .meta-separator {
            display: none !important;
        }

        .form-card-header {
            padding: 17px;
            flex-wrap: wrap;
        }

        .form-card-header h2 {
            font-size: 16px;
        }

        .required-note {
            width: 100%;
            margin-left: 55px;
            margin-top: -7px;
        }

        .form-card-body {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 19px;
        }

        .form-section {
            padding-bottom: 22px;
            margin-bottom: 22px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .diary-btn {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .exam-session-page *,
        .exam-session-page *::before,
        .exam-session-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>

@endsection
