@extends('layouts.app')

@section('title', 'Edit Exam | Admin')
@section('page-title', 'Edit Exam')

@section('content')
<div class="edit-exam-page">
    <div class="edit-exam-container">

        {{-- SIMPLE HEADER — NO COLOURED BACKGROUND --}}
        <header class="exam-hero">
            <div class="exam-hero-content">
                <div class="exam-breadcrumb">
                    <span>ACADEMIC MANAGEMENT</span>
                    <i class="bi bi-chevron-right"></i>
                    <span>EXAMINATIONS</span>
                    <i class="bi bi-chevron-right"></i>
                    <span class="exam-breadcrumb-current">EDIT EXAM</span>
                </div>

                <span class="exam-eyebrow"></span>

                <h1>
                    <i class="bi bi-pencil-square"></i>
                    Edit Examination
                </h1>

                <p>
                    Update examination details, dates, and academic status.
                </p>
            </div>

            <a href="{{ route('admin.exams.show', $exam) }}"
               class="exam-hero-back">
                <i class="bi bi-arrow-left"></i>
                Back to Exam
            </a>
        </header>

        {{-- EXAM SUMMARY --}}
        <section class="exam-summary-grid" aria-label="Current exam summary">

            <article class="exam-summary-card summary-green">
                <div class="exam-summary-top">
                    <span class="exam-summary-icon">
                        <i class="bi bi-calendar3"></i>
                    </span>
                    <span class="exam-summary-caption">ACADEMIC YEAR</span>
                </div>

                <div class="exam-summary-value">
                    {{ $exam->academic_year ?: 'Not specified' }}
                </div>

                <div class="exam-summary-foot">Academic session</div>
            </article>

            <article class="exam-summary-card summary-amber">
                <div class="exam-summary-top">
                    <span class="exam-summary-icon">
                        <i class="bi bi-bookmark-star"></i>
                    </span>
                    <span class="exam-summary-caption">EXAM TYPE</span>
                </div>

                <div class="exam-summary-value">
                    {{ $exam->exam_type ?: 'Not specified' }}
                </div>

                <div class="exam-summary-foot">Examination category</div>
            </article>

            <article class="exam-summary-card summary-blue">
                <div class="exam-summary-top">
                    <span class="exam-summary-icon">
                        <i class="bi bi-calendar-event"></i>
                    </span>
                    <span class="exam-summary-caption">START DATE</span>
                </div>

                <div class="exam-summary-value">
                    {{ $exam->start_date?->format('d M Y') ?? 'Not set' }}
                </div>

                <div class="exam-summary-foot">Planned commencement</div>
            </article>

            <article class="exam-summary-card summary-sage">
                <div class="exam-summary-top">
                    <span class="exam-summary-icon">
                        <i class="bi bi-flag"></i>
                    </span>
                    <span class="exam-summary-caption">CURRENT STATUS</span>
                </div>

                <div class="exam-summary-value">
                    <span class="exam-status-pill status-{{ strtolower($exam->status ?? 'draft') }}">
                        <span class="exam-status-dot"></span>
                        {{ ucfirst($exam->status ?? 'draft') }}
                    </span>
                </div>

                <div class="exam-summary-foot">Current exam state</div>
            </article>

        </section>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="exam-alert-error" role="alert">
                <div class="exam-alert-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div>
                    <strong>Please review the highlighted information</strong>

                    <p>
                        Some details need your attention before this exam can be updated.
                    </p>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN FORM --}}
        <section class="exam-form-card">

            <div class="exam-card-heading">
                <div class="exam-heading-main">
                    <div class="exam-heading-symbol">
                        <i class="bi bi-sliders2"></i>
                    </div>

                    <div>
                        <span class="exam-card-eyebrow">EXAM CONFIGURATION</span>
                        <h2>Examination Details</h2>
                        <p>Keep the information accurate and up to date.</p>
                    </div>
                </div>

                <div class="exam-required-note">
                    <span class="exam-required">*</span>
                    Required fields
                </div>
            </div>

            <div class="exam-form-body">

                <form method="POST"
                      action="{{ route('admin.exams.update', $exam) }}"
                      id="editExamForm">

                    @csrf
                    @method('PUT')

                    {{-- BASIC INFORMATION --}}
                    <div class="exam-form-section">

                        <div class="exam-section-heading">
                            <div class="exam-section-number">01</div>

                            <div>
                                <h3>Basic Information</h3>
                                <p>Identify the examination and its current status.</p>
                            </div>
                        </div>

                        <div class="exam-form-grid">

                            {{-- ACADEMIC YEAR --}}
                            <div class="exam-form-group">
                                <label for="academic_year" class="exam-form-label">
                                    Academic Year <span class="exam-required">*</span>
                                </label>

                                <div class="exam-input-wrap">
                                    <i class="bi bi-calendar3 exam-input-icon"></i>

                                    <input type="text"
                                           id="academic_year"
                                           name="academic_year"
                                           class="exam-form-control @error('academic_year') is-invalid @enderror"
                                           value="{{ old('academic_year', $exam->academic_year) }}"
                                           placeholder="e.g. 2026-2027"
                                           required
                                           autocomplete="off"
                                           aria-describedby="academic-year-error">
                                </div>

                                @error('academic_year')
                                    <div class="exam-form-error" id="academic-year-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="exam-field-hint">
                                        <i class="bi bi-info-circle"></i>
                                        Enter the academic session for this exam.
                                    </div>
                                @enderror
                            </div>

                            {{-- EXAM NAME --}}
                            <div class="exam-form-group">
                                <label for="exam_name" class="exam-form-label">
                                    Exam Name <span class="exam-required">*</span>
                                </label>

                                <div class="exam-input-wrap">
                                    <i class="bi bi-pencil-square exam-input-icon"></i>

                                    <input type="text"
                                           id="exam_name"
                                           name="exam_name"
                                           class="exam-form-control @error('exam_name') is-invalid @enderror"
                                           value="{{ old('exam_name', $exam->exam_name) }}"
                                           placeholder="e.g. Mid Term Examination"
                                           required
                                           autocomplete="off"
                                           aria-describedby="exam-name-error">
                                </div>

                                @error('exam_name')
                                    <div class="exam-form-error" id="exam-name-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="exam-field-hint">
                                        <i class="bi bi-info-circle"></i>
                                        Use the official name of the examination.
                                    </div>
                                @enderror
                            </div>

                            {{-- EXAM TYPE --}}
                            <div class="exam-form-group">
                                <label for="exam_type" class="exam-form-label">
                                    Exam Type <span class="exam-required">*</span>
                                </label>

                                <div class="exam-input-wrap">
                                    <i class="bi bi-bookmark-star exam-input-icon"></i>

                                    <select id="exam_type"
                                            name="exam_type"
                                            class="exam-form-control exam-form-select @error('exam_type') is-invalid @enderror"
                                            required
                                            aria-describedby="exam-type-error">

                                        <option value="">Select exam type</option>

                                        @php
                                            $examTypes = [
                                                'Unit Test',
                                                'First Term',
                                                'Mid Term',
                                                'Second Term',
                                                'Annual Examination',
                                                'Preliminary',
                                                'Pre-Board',
                                                'Final Examination',
                                                'Other',
                                            ];

                                            $selectedExamType = old(
                                                'exam_type',
                                                $exam->exam_type
                                            );
                                        @endphp

                                        @foreach($examTypes as $type)
                                            <option value="{{ $type }}"
                                                @selected($selectedExamType === $type)>
                                                {{ $type }}
                                            </option>
                                        @endforeach

                                        @if($exam->exam_type && !in_array($exam->exam_type, $examTypes))
                                            <option value="{{ $exam->exam_type }}"
                                                @selected($selectedExamType === $exam->exam_type)>
                                                {{ $exam->exam_type }}
                                            </option>
                                        @endif

                                    </select>
                                </div>

                                @error('exam_type')
                                    <div class="exam-form-error" id="exam-type-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="exam-field-hint">
                                        <i class="bi bi-info-circle"></i>
                                        Choose the appropriate exam category.
                                    </div>
                                @enderror
                            </div>

                            {{-- STATUS --}}
                            <div class="exam-form-group">
                                <label for="status" class="exam-form-label">
                                    Status <span class="exam-required">*</span>
                                </label>

                                <div class="exam-input-wrap">
                                    <i class="bi bi-flag exam-input-icon"></i>

                                    <select id="status"
                                            name="status"
                                            class="exam-form-control exam-form-select @error('status') is-invalid @enderror"
                                            required
                                            aria-describedby="exam-status-error">

                                        <option value="draft"
                                            @selected(old('status', $exam->status ?? 'draft') === 'draft')>
                                            Draft
                                        </option>

                                        <option value="scheduled"
                                            @selected(old('status', $exam->status) === 'scheduled')>
                                            Scheduled
                                        </option>

                                        <option value="completed"
                                            @selected(old('status', $exam->status) === 'completed')>
                                            Completed
                                        </option>

                                        <option value="cancelled"
                                            @selected(old('status', $exam->status) === 'cancelled')>
                                            Cancelled
                                        </option>
                                    </select>
                                </div>

                                @error('status')
                                    <div class="exam-form-error" id="exam-status-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="exam-field-hint">
                                        <i class="bi bi-info-circle"></i>
                                        Update the current examination state.
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- EXAMINATION PERIOD --}}
                    <div class="exam-form-section exam-form-section-last">

                        <div class="exam-section-heading">
                            <div class="exam-section-number">02</div>

                            <div>
                                <h3>Examination Period</h3>
                                <p>Set the start and end dates for the examination.</p>
                            </div>
                        </div>

                        <div class="exam-form-grid">

                            {{-- START DATE --}}
                            <div class="exam-form-group">
                                <label for="start_date" class="exam-form-label">
                                    Start Date <span class="exam-required">*</span>
                                </label>

                                <div class="exam-input-wrap">
                                    <i class="bi bi-calendar-event exam-input-icon"></i>

                                    <input type="date"
                                           id="start_date"
                                           name="start_date"
                                           class="exam-form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date', $exam->start_date?->format('Y-m-d')) }}"
                                           required
                                           aria-describedby="start-date-error">
                                </div>

                                @error('start_date')
                                    <div class="exam-form-error" id="start-date-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="exam-field-hint">
                                        <i class="bi bi-info-circle"></i>
                                        The date on which the examination begins.
                                    </div>
                                @enderror
                            </div>

                            {{-- END DATE --}}
                            <div class="exam-form-group">
                                <label for="end_date" class="exam-form-label">
                                    End Date
                                </label>

                                <div class="exam-input-wrap">
                                    <i class="bi bi-calendar-check exam-input-icon"></i>

                                    <input type="date"
                                           id="end_date"
                                           name="end_date"
                                           class="exam-form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date', $exam->end_date?->format('Y-m-d')) }}"
                                           aria-describedby="end-date-error">
                                </div>

                                @error('end_date')
                                    <div class="exam-form-error" id="end-date-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="exam-field-hint">
                                        <i class="bi bi-info-circle"></i>
                                        Optional — set the final examination date.
                                    </div>
                                @enderror
                            </div>

                        </div>

                        {{-- LIVE DATE PREVIEW --}}
                        <div class="exam-date-preview"
                             id="datePreview"
                             aria-live="polite">

                            <div class="exam-date-preview-icon">
                                <i class="bi bi-calendar2-week"></i>
                            </div>

                            <div class="exam-date-preview-copy">
                                <span class="exam-date-preview-label">
                                    YOUR EXAMINATION WINDOW
                                </span>

                                <div class="exam-date-preview-value"
                                     id="datePreviewText"></div>
                            </div>

                            <div class="exam-preview-decoration">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                        </div>
                    </div>

                    {{-- FORM ACTIONS --}}
                    <div class="exam-form-actions">

                        <div class="exam-action-note">
                            <span class="exam-action-note-icon">
                                <i class="bi bi-shield-check"></i>
                            </span>

                            <span>
                                Check the details before saving your changes.
                            </span>
                        </div>

                        <div class="exam-action-buttons">
                            <a href="{{ route('admin.exams.show', $exam) }}"
                               class="exam-btn exam-btn-light">
                                <i class="bi bi-x-lg"></i>
                                Cancel
                            </a>

                            <button type="submit"
                                    class="exam-btn exam-btn-primary"
                                    id="updateExamBtn">
                                <i class="bi bi-check2-circle"></i>
                                Update Examination
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </section>

        {{-- EXAM SETUP --}}
        <section class="exam-setup-card">

            <div class="exam-setup-heading">
                <div class="exam-setup-heading-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>

                <div>
                    <span class="exam-card-eyebrow">NEXT STEPS</span>
                    <h2>Complete Your Exam Setup</h2>
                    <p>
                        Manage the remaining examination components from Exam Details.
                    </p>
                </div>
            </div>

            <div class="exam-setup-grid">

                <article class="exam-setup-item">
                    <div class="exam-setup-icon setup-green">
                        <i class="bi bi-people"></i>
                    </div>

                    <div class="exam-setup-content">
                        <span class="exam-setup-label">STEP 01</span>
                        <h3>Exam Classes</h3>
                        <p>Select which classes will participate in this examination.</p>
                    </div>

                    <i class="bi bi-arrow-up-right exam-setup-arrow"></i>
                </article>

                <article class="exam-setup-item">
                    <div class="exam-setup-icon setup-amber">
                        <i class="bi bi-journal-text"></i>
                    </div>

                    <div class="exam-setup-content">
                        <span class="exam-setup-label">STEP 02</span>
                        <h3>Subjects &amp; Marks</h3>
                        <p>Configure maximum marks, passing marks, and duration.</p>
                    </div>

                    <i class="bi bi-arrow-up-right exam-setup-arrow"></i>
                </article>

                <article class="exam-setup-item">
                    <div class="exam-setup-icon setup-blue">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div class="exam-setup-content">
                        <span class="exam-setup-label">STEP 03</span>
                        <h3>Exam Sessions</h3>
                        <p>Organise morning, afternoon, and other exam timings.</p>
                    </div>

                    <i class="bi bi-arrow-up-right exam-setup-arrow"></i>
                </article>

                <article class="exam-setup-item">
                    <div class="exam-setup-icon setup-sage">
                        <i class="bi bi-calendar-x"></i>
                    </div>

                    <div class="exam-setup-content">
                        <span class="exam-setup-label">STEP 04</span>
                        <h3>Exam Holidays</h3>
                        <p>Add non-working days to exclude from the timetable.</p>
                    </div>

                    <i class="bi bi-arrow-up-right exam-setup-arrow"></i>
                </article>

            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="exam-page-footer">
            <i class="bi bi-info-circle"></i>
            <span>
                Accurate examination details help keep academic planning clear and consistent.
            </span>
        </footer>

    </div>
</div>
@endsection

@push('styles')
<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM — EDIT EXAM
   Simple neutral header · Warm cream · Forest green
========================================================= */

.edit-exam-page {
    --exam-page: #f7f6f0;
    --exam-paper: #fffefa;
    --exam-chalk: #f5f3e9;
    --exam-ink: #263b35;
    --exam-dark: #20352c;
    --exam-soft: #40574a;
    --exam-muted: #7b857b;
    --exam-line: #e5e1d5;
    --exam-green: #43835d;
    --exam-sage: #527f70;
    --exam-blue: #426b8a;
    --exam-amber: #a87543;
    --exam-red: #b95b50;
    --exam-serif: Georgia, "Times New Roman", serif;

    width: 100%;
    min-width: 0;
    padding: 8px 0 24px;
    color: var(--exam-ink);
    font-family: "Public Sans", Inter, -apple-system, BlinkMacSystemFont,
        "Segoe UI", sans-serif;
    font-size: 12px;
    line-height: 1.55;
}

.edit-exam-page *,
.edit-exam-page *::before,
.edit-exam-page *::after {
    box-sizing: border-box;
}

.edit-exam-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 0 24px;
}

/* SIMPLE HEADER — WHITE/CREAM, NO DARK BACKGROUND */

.edit-exam-page .exam-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    min-height: 0;
    margin-bottom: 22px;
    padding: 20px 0 22px;
    border: 0;
    border-bottom: 1px solid var(--exam-line);
    border-radius: 0;
    background: transparent;
    color: var(--exam-ink);
    box-shadow: none;
}

.edit-exam-page .exam-hero-content {
    min-width: 0;
}

.edit-exam-page .exam-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
    color: var(--exam-muted);
    font-size: 9px;
    font-weight: 750;
    letter-spacing: .8px;
}

.edit-exam-page .exam-breadcrumb i {
    color: #b3a58d;
    font-size: 8px;
}

.edit-exam-page .exam-breadcrumb-current {
    color: var(--exam-sage);
}

.edit-exam-page .exam-eyebrow {
    display: block;
    margin-bottom: 4px;
    color: var(--exam-amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.edit-exam-page .exam-hero h1 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: var(--exam-ink);
    font-family: var(--exam-serif);
    font-size: clamp(25px, 2.5vw, 32px);
    font-weight: 700;
    line-height: 1.3;
}

.edit-exam-page .exam-hero h1 i {
    color: var(--exam-sage);
    font-size: 22px;
}

.edit-exam-page .exam-hero-content p {
    margin: 7px 0 0;
    color: var(--exam-muted);
    font-size: 11px;
    line-height: 1.7;
}

.edit-exam-page .exam-hero-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex: 0 0 auto;
    min-height: 40px;
    padding: 10px 15px;
    border: 1px solid var(--exam-line);
    border-radius: 8px;
    background: var(--exam-paper);
    color: var(--exam-ink);
    font-size: 11px;
    font-weight: 750;
    text-decoration: none;
    transition: background .2s ease, border-color .2s ease;
}

.edit-exam-page .exam-hero-back:hover {
    border-color: #c7d5c5;
    background: var(--exam-chalk);
    color: var(--exam-dark);
    text-decoration: none;
}

.edit-exam-page .exam-hero-back:focus-visible {
    outline: 2px solid var(--exam-sage);
    outline-offset: 3px;
}

/* SUMMARY CARDS */

.edit-exam-page .exam-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

.edit-exam-page .exam-summary-card {
    position: relative;
    min-width: 0;
    overflow: hidden;
    padding: 17px;
    border: 1px solid var(--exam-line);
    border-radius: 13px;
    background: var(--exam-paper);
    box-shadow: 0 4px 14px rgba(38,59,53,.035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.edit-exam-page .exam-summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 20px rgba(38,59,53,.075);
}

.edit-exam-page .exam-summary-card::before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    height: 4px;
    background: var(--summary-accent, var(--exam-green));
    content: "";
}

.edit-exam-page .summary-green {
    --summary-accent: #43835d;
    --summary-tint: #edf4e9;
}

.edit-exam-page .summary-amber {
    --summary-accent: #a87543;
    --summary-tint: #f7efe2;
}

.edit-exam-page .summary-blue {
    --summary-accent: #426b8a;
    --summary-tint: #edf3f7;
}

.edit-exam-page .summary-sage {
    --summary-accent: #527f70;
    --summary-tint: #edf4f0;
}

.edit-exam-page .exam-summary-top {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 13px;
}

.edit-exam-page .exam-summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    border: 1px solid #e0e5d9;
    border-radius: 10px;
    background: var(--summary-tint);
    color: var(--summary-accent);
    font-size: 16px;
}

.edit-exam-page .exam-summary-caption {
    color: var(--exam-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .8px;
}

.edit-exam-page .exam-summary-value {
    min-height: 23px;
    overflow-wrap: anywhere;
    color: var(--exam-ink);
    font-family: var(--exam-serif);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
}

.edit-exam-page .exam-summary-foot {
    margin-top: 7px;
    color: var(--exam-muted);
    font-size: 10px;
}

.edit-exam-page .exam-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 9px;
    border: 1px solid #d8e5d7;
    border-radius: 30px;
    background: #eff5eb;
    color: #39714b;
    font-family: inherit;
    font-size: 11px;
    font-weight: 750;
}

.edit-exam-page .exam-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.edit-exam-page .status-cancelled {
    border-color: #efd5d1;
    background: #fff0ed;
    color: #a64840;
}

.edit-exam-page .status-completed {
    border-color: #cfe4d4;
    background: #eaf5ed;
    color: #33784c;
}

.edit-exam-page .status-scheduled {
    border-color: #d4e1ef;
    background: #edf4fb;
    color: #365f89;
}

.edit-exam-page .status-draft {
    border-color: #e8dccb;
    background: #f8f1e6;
    color: #966638;
}

/* VALIDATION */

.edit-exam-page .exam-alert-error {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 20px;
    padding: 16px 18px;
    border: 1px solid #ebc9c3;
    border-radius: 12px;
    background: #fff5f2;
    color: #8d3932;
}

.edit-exam-page .exam-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border-radius: 10px;
    background: #fbe4df;
    color: var(--exam-red);
    font-size: 17px;
}

.edit-exam-page .exam-alert-error strong {
    display: block;
    margin-bottom: 3px;
    color: #8d3932;
    font-size: 12px;
    font-weight: 800;
}

.edit-exam-page .exam-alert-error p {
    margin: 0 0 7px;
    color: #9a5b52;
    font-size: 11px;
}

.edit-exam-page .exam-alert-error ul {
    margin: 0;
    padding-left: 18px;
}

.edit-exam-page .exam-alert-error li {
    margin: 3px 0;
    font-size: 11px;
}

/* FORM CARD */

.edit-exam-page .exam-form-card,
.edit-exam-page .exam-setup-card {
    overflow: hidden;
    border: 1px solid var(--exam-line);
    border-radius: 15px;
    background: var(--exam-paper);
    box-shadow: 0 5px 20px rgba(38,59,53,.045);
}

.edit-exam-page .exam-card-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 21px 24px;
    border-bottom: 1px solid var(--exam-line);
    background: linear-gradient(110deg, #fffefa 0%, #f8f7ef 100%);
}

.edit-exam-page .exam-heading-main {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.edit-exam-page .exam-heading-symbol {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    flex: 0 0 45px;
    border: 1px solid #dce6d8;
    border-radius: 12px;
    background: #edf4e9;
    color: var(--exam-green);
    font-size: 19px;
}

.edit-exam-page .exam-card-eyebrow {
    display: block;
    margin-bottom: 3px;
    color: var(--exam-amber);
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.edit-exam-page .exam-card-heading h2,
.edit-exam-page .exam-setup-heading h2 {
    margin: 0;
    color: var(--exam-ink);
    font-family: var(--exam-serif);
    font-size: 21px;
    font-weight: 700;
    line-height: 1.35;
}

.edit-exam-page .exam-card-heading p,
.edit-exam-page .exam-setup-heading p {
    margin: 4px 0 0;
    color: var(--exam-muted);
    font-size: 11px;
    line-height: 1.6;
}

.edit-exam-page .exam-required-note {
    flex: 0 0 auto;
    color: var(--exam-muted);
    font-size: 10px;
}

.edit-exam-page .exam-required {
    color: var(--exam-red);
    font-weight: 800;
}

.edit-exam-page .exam-form-body {
    padding: 25px 24px 21px;
}

.edit-exam-page .exam-form-section {
    margin-bottom: 26px;
    padding-bottom: 26px;
    border-bottom: 1px dashed #e4e0d4;
}

.edit-exam-page .exam-form-section-last {
    margin-bottom: 0;
    padding-bottom: 25px;
    border-bottom: 0;
}

.edit-exam-page .exam-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 21px;
}

.edit-exam-page .exam-section-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    border: 1px solid #dbe5d8;
    border-radius: 11px;
    background: #edf4e9;
    color: #43835d;
    font-family: var(--exam-serif);
    font-size: 14px;
    font-weight: 700;
}

.edit-exam-page .exam-section-heading h3 {
    margin: 0;
    color: var(--exam-ink);
    font-size: 13px;
    font-weight: 800;
}

.edit-exam-page .exam-section-heading p {
    margin: 4px 0 0;
    color: var(--exam-muted);
    font-size: 10px;
    line-height: 1.6;
}

.edit-exam-page .exam-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px 25px;
}

.edit-exam-page .exam-form-group {
    min-width: 0;
}

.edit-exam-page .exam-form-label {
    display: block;
    margin-bottom: 8px;
    color: #354a3e;
    font-size: 11px;
    font-weight: 750;
}

.edit-exam-page .exam-input-wrap {
    position: relative;
}

.edit-exam-page .exam-input-icon {
    position: absolute;
    top: 50%;
    left: 14px;
    z-index: 2;
    color: #809084;
    font-size: 14px;
    pointer-events: none;
    transform: translateY(-50%);
}

.edit-exam-page .exam-form-control {
    display: block;
    width: 100%;
    height: 46px;
    min-height: 46px;
    margin: 0;
    padding: 0 13px 0 42px;
    border: 1px solid #deded1;
    border-radius: 9px;
    outline: none;
    background: #fffefa;
    color: var(--exam-ink);
    font-family: inherit;
    font-size: 12px;
    font-weight: 500;
    line-height: normal;
    box-shadow: 0 1px 2px rgba(38,59,53,.025);
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.edit-exam-page .exam-form-control::placeholder {
    color: #a1a69a;
    opacity: 1;
}

.edit-exam-page .exam-form-control:hover {
    border-color: #b9cabb;
}

.edit-exam-page .exam-form-control:focus {
    border-color: var(--exam-sage);
    outline: none;
    background: #fffefa;
    box-shadow: 0 0 0 4px rgba(82,127,112,.12);
}

.edit-exam-page .exam-form-control.is-invalid {
    border-color: var(--exam-red);
    background-image: none;
}

.edit-exam-page .exam-form-control.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(185,91,80,.1);
}

.edit-exam-page .exam-form-select {
    padding-right: 32px;
    cursor: pointer;
}

.edit-exam-page .exam-form-control option {
    background: #fffefa;
    color: var(--exam-ink);
}

.edit-exam-page .exam-field-hint,
.edit-exam-page .exam-form-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 8px;
    font-size: 10px;
    line-height: 1.6;
}

.edit-exam-page .exam-field-hint {
    color: #838b7f;
}

.edit-exam-page .exam-field-hint i {
    margin-top: 2px;
    color: var(--exam-sage);
}

.edit-exam-page .exam-form-error {
    color: var(--exam-red);
}

.edit-exam-page .exam-form-error i {
    margin-top: 2px;
}

/* DATE PREVIEW */

.edit-exam-page .exam-date-preview {
    position: relative;
    display: none;
    align-items: center;
    gap: 13px;
    margin-top: 22px;
    overflow: hidden;
    padding: 15px 18px;
    border: 1px solid #d9e5d7;
    border-radius: 12px;
    background: linear-gradient(110deg, #f0f5eb, #faf9f1);
}

.edit-exam-page .exam-date-preview.show {
    display: flex;
    animation: examPreviewIn .25s ease both;
}

.edit-exam-page .exam-date-preview-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    border: 1px solid #dce7d9;
    border-radius: 11px;
    background: #fffefa;
    color: var(--exam-green);
    font-size: 18px;
}

.edit-exam-page .exam-date-preview-copy {
    position: relative;
    z-index: 1;
    min-width: 0;
}

.edit-exam-page .exam-date-preview-label {
    display: block;
    margin-bottom: 4px;
    color: #71806f;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1.1px;
}

.edit-exam-page .exam-date-preview-value {
    color: var(--exam-ink);
    font-family: var(--exam-serif);
    font-size: 16px;
    font-weight: 700;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.edit-exam-page .exam-preview-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    margin-left: auto;
    flex: 0 0 46px;
    border-radius: 50%;
    background: rgba(67,131,93,.08);
    color: #43835d;
    font-size: 29px;
}

/* FORM ACTIONS */

.edit-exam-page .exam-form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding-top: 21px;
    border-top: 1px solid var(--exam-line);
}

.edit-exam-page .exam-action-note {
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 360px;
    color: var(--exam-muted);
    font-size: 10px;
    line-height: 1.7;
}

.edit-exam-page .exam-action-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 33px;
    height: 33px;
    flex: 0 0 33px;
    border: 1px solid #dce6d8;
    border-radius: 9px;
    background: #edf4e9;
    color: var(--exam-green);
    font-size: 14px;
}

.edit-exam-page .exam-action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 10px;
}

.edit-exam-page .exam-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 17px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    line-height: 1.35;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
}

.edit-exam-page .exam-btn:focus-visible {
    outline: 2px solid var(--exam-sage);
    outline-offset: 3px;
}

.edit-exam-page .exam-btn-light {
    border-color: var(--exam-line);
    background: #fffefa;
    color: #59675d;
}

.edit-exam-page .exam-btn-light:hover {
    border-color: #cfcbbb;
    background: var(--exam-chalk);
    color: var(--exam-ink);
}

.edit-exam-page .exam-btn-primary {
    border-color: #344f43;
    background: linear-gradient(115deg, #344f43, #20352c);
    color: #fffefa;
    box-shadow: 0 4px 10px rgba(38,59,53,.15);
}

.edit-exam-page .exam-btn-primary:hover {
    transform: translateY(-2px);
    background: linear-gradient(115deg, #405f4f, #263e32);
    color: #fffefa;
    box-shadow: 0 7px 15px rgba(38,59,53,.2);
}

.edit-exam-page .exam-btn-primary:disabled {
    opacity: .75;
    cursor: wait;
    transform: none;
    box-shadow: none;
}

/* SETUP CARD */

.edit-exam-page .exam-setup-card {
    margin-top: 22px;
    padding: 23px 24px 24px;
}

.edit-exam-page .exam-setup-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 20px;
}

.edit-exam-page .exam-setup-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    flex: 0 0 44px;
    border: 1px solid #eadcc9;
    border-radius: 12px;
    background: #f7efe1;
    color: var(--exam-amber);
    font-size: 18px;
}

.edit-exam-page .exam-setup-heading h2 {
    font-size: 20px;
}

.edit-exam-page .exam-setup-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 13px;
}

.edit-exam-page .exam-setup-item {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 11px;
    min-width: 0;
    min-height: 140px;
    padding: 15px 13px;
    border: 1px solid #e6e2d7;
    border-radius: 12px;
    background: #fffefa;
    transition: transform .2s ease, border-color .2s ease, background .2s ease;
}

.edit-exam-page .exam-setup-item:hover {
    transform: translateY(-3px);
    border-color: #c8d9c7;
    background: #fbfcf6;
}

.edit-exam-page .exam-setup-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-size: 15px;
}

.edit-exam-page .setup-green {
    border-color: #dce8d9;
    background: #edf4e9;
    color: #43835d;
}

.edit-exam-page .setup-amber {
    border-color: #eadcc9;
    background: #f7efe1;
    color: #a87543;
}

.edit-exam-page .setup-blue {
    border-color: #d9e5ee;
    background: #edf3f8;
    color: #426b8a;
}

.edit-exam-page .setup-sage {
    border-color: #d6e5df;
    background: #edf4f0;
    color: #527f70;
}

.edit-exam-page .exam-setup-content {
    min-width: 0;
    padding-right: 7px;
}

.edit-exam-page .exam-setup-label {
    display: block;
    margin-bottom: 5px;
    color: var(--exam-muted);
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1px;
}

.edit-exam-page .exam-setup-content h3 {
    margin: 0 0 7px;
    color: var(--exam-ink);
    font-family: var(--exam-serif);
    font-size: 15px;
    font-weight: 700;
    line-height: 1.35;
}

.edit-exam-page .exam-setup-content p {
    margin: 0;
    color: #7b857b;
    font-size: 10px;
    line-height: 1.7;
}

.edit-exam-page .exam-setup-arrow {
    position: absolute;
    top: 13px;
    right: 12px;
    color: #a6ada1;
    font-size: 12px;
}

/* FOOTER */

.edit-exam-page .exam-page-footer {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 17px;
    padding: 0 3px;
    color: #7b857b;
    font-size: 10px;
    line-height: 1.7;
}

.edit-exam-page .exam-page-footer i {
    margin-top: 2px;
    color: var(--exam-amber);
}

/* ANIMATION */

@keyframes examPreviewIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* RESPONSIVE */

@media (max-width: 1199px) {
    .edit-exam-page .exam-setup-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 991px) {
    .edit-exam-container {
        padding-right: 18px;
        padding-left: 18px;
    }

    .edit-exam-page .exam-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .edit-exam-page .exam-form-actions {
        align-items: flex-start;
        flex-direction: column;
    }

    .edit-exam-page .exam-action-buttons {
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width: 767px) {
    .edit-exam-container {
        padding-right: 12px;
        padding-left: 12px;
    }

    .edit-exam-page .exam-hero {
        align-items: flex-start;
        flex-direction: column;
        gap: 16px;
        padding: 17px 0 20px;
    }

    .edit-exam-page .exam-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .6px;
    }

    .edit-exam-page .exam-hero h1 {
        font-size: 27px;
    }

    .edit-exam-page .exam-hero-content p {
        font-size: 11px;
    }

    .edit-exam-page .exam-hero-back {
        width: 100%;
    }

    .edit-exam-page .exam-card-heading {
        align-items: flex-start;
        flex-direction: column;
        padding: 18px;
    }

    .edit-exam-page .exam-card-heading h2 {
        font-size: 19px;
    }

    .edit-exam-page .exam-form-body {
        padding: 20px 18px;
    }

    .edit-exam-page .exam-form-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
    }

    .edit-exam-page .exam-form-section {
        margin-bottom: 22px;
        padding-bottom: 22px;
    }

    .edit-exam-page .exam-form-section-last {
        margin-bottom: 0;
    }

    .edit-exam-page .exam-setup-card {
        padding: 19px 18px;
    }

    .edit-exam-page .exam-setup-heading h2 {
        font-size: 18px;
    }

    .edit-exam-page .exam-setup-grid {
        gap: 10px;
    }

    .edit-exam-page .exam-setup-item {
        min-height: 130px;
    }
}

@media (max-width: 480px) {
    .edit-exam-page .exam-summary-grid,
    .edit-exam-page .exam-setup-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .edit-exam-page .exam-summary-card {
        padding: 15px;
    }

    .edit-exam-page .exam-summary-top {
        margin-bottom: 9px;
    }

    .edit-exam-page .exam-summary-value {
        font-size: 17px;
    }

    .edit-exam-page .exam-hero {
        padding: 15px 0 18px;
    }

    .edit-exam-page .exam-hero h1 {
        font-size: 24px;
    }

    .edit-exam-page .exam-heading-main {
        align-items: flex-start;
        gap: 10px;
    }

    .edit-exam-page .exam-heading-symbol {
        width: 38px;
        height: 38px;
        flex-basis: 38px;
        font-size: 16px;
    }

    .edit-exam-page .exam-card-heading h2 {
        font-size: 18px;
    }

    .edit-exam-page .exam-form-body {
        padding: 17px 13px;
    }

    .edit-exam-page .exam-section-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .edit-exam-page .exam-section-number {
        width: 32px;
        height: 32px;
        flex-basis: 32px;
    }

    .edit-exam-page .exam-form-control {
        height: 45px;
        min-height: 45px;
    }

    .edit-exam-page .exam-date-preview {
        gap: 9px;
        padding: 12px;
    }

    .edit-exam-page .exam-date-preview-icon {
        width: 35px;
        height: 35px;
        flex-basis: 35px;
    }

    .edit-exam-page .exam-date-preview-value {
        font-size: 13px;
    }

    .edit-exam-page .exam-preview-decoration {
        display: none;
    }

    .edit-exam-page .exam-action-buttons {
        display: grid;
        grid-template-columns: 1fr;
    }

    .edit-exam-page .exam-btn {
        width: 100%;
        min-height: 43px;
    }

    .edit-exam-page .exam-setup-item {
        min-height: 0;
        padding: 15px;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .edit-exam-page *,
    .edit-exam-page *::before,
    .edit-exam-page *::after {
        scroll-behavior: auto !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.edit-exam-page');

    if (!page) {
        return;
    }

    const startDate = page.querySelector('#start_date');
    const endDate = page.querySelector('#end_date');
    const datePreview = page.querySelector('#datePreview');
    const datePreviewText = page.querySelector('#datePreviewText');
    const form = page.querySelector('#editExamForm');
    const updateButton = page.querySelector('#updateExamBtn');

    if (
        !startDate ||
        !endDate ||
        !datePreview ||
        !datePreviewText ||
        !form ||
        !updateButton
    ) {
        return;
    }

    function formatDate(date) {
        return date.toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function updateDatePreview() {
        if (!startDate.value) {
            datePreview.classList.remove('show');
            datePreviewText.textContent = '';
            return;
        }

        const start = new Date(startDate.value + 'T00:00:00');

        if (Number.isNaN(start.getTime())) {
            datePreview.classList.remove('show');
            datePreviewText.textContent = '';
            return;
        }

        let previewText = 'Starts on ' + formatDate(start);

        if (endDate.value) {
            const end = new Date(endDate.value + 'T00:00:00');

            if (!Number.isNaN(end.getTime()) && end >= start) {
                const days = Math.floor(
                    (end.getTime() - start.getTime()) / 86400000
                ) + 1;

                previewText =
                    formatDate(start) + ' → ' +
                    formatDate(end) + ' · ' +
                    days + (days === 1 ? ' day' : ' days');
            } else if (!Number.isNaN(end.getTime())) {
                previewText =
                    formatDate(start) +
                    ' · Please choose an end date on or after the start date.';
            }
        }

        datePreviewText.textContent = previewText;
        datePreview.classList.add('show');
    }

    function syncDateConstraints() {
        if (startDate.value) {
            endDate.min = startDate.value;

            if (endDate.value && endDate.value < startDate.value) {
                endDate.value = '';
                endDate.setCustomValidity('');
            }
        } else {
            endDate.removeAttribute('min');
        }

        updateDatePreview();
    }

    startDate.addEventListener('change', syncDateConstraints);

    endDate.addEventListener('change', function () {
        if (
            startDate.value &&
            endDate.value &&
            endDate.value < startDate.value
        ) {
            endDate.setCustomValidity(
                'End date must be on or after the start date.'
            );
        } else {
            endDate.setCustomValidity('');
        }

        updateDatePreview();
    });

    if (startDate.value) {
        endDate.min = startDate.value;
    }

    if (
        startDate.value &&
        endDate.value &&
        endDate.value < startDate.value
    ) {
        endDate.setCustomValidity(
            'End date must be on or after the start date.'
        );
    }

    updateDatePreview();

    let isSubmitting = false;

    form.addEventListener('submit', function (event) {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        if (!form.checkValidity()) {
            return;
        }

        isSubmitting = true;
        updateButton.disabled = true;
        updateButton.innerHTML =
            '<span class="spinner-border spinner-border-sm" ' +
            'role="status" aria-hidden="true"></span> Updating Exam...';
    });
});
</script>
@endpush