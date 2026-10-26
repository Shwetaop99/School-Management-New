@extends('layouts.app')

@section('title', 'Add Scholarship Exam | Admin')
@section('page-title', 'Add Scholarship Exam')

@section('content')
<div class="scholarship-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        SCHOLARSHIP <span>/</span> EXAMS <span>/</span> ADD RECORD
                    </div>
                    <h2>Add Scholarship Exam Record</h2>
                    <p>Record scholarship examination details and maintain yearly history.</p>
                </div>
            </div>
            <div class="form-hero-actions">
                <a href="{{ route('admin.scholarship.exams.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Exam Records
                </a>
            </div>
        </section>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
                <div class="error-copy">
                    <strong>Please correct the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.scholarship.exams.store') }}"
              id="scholarshipExamForm">
            @csrf

            <div class="scholarship-layout">

                {{-- ===================== LEFT COLUMN ===================== --}}
                <div class="scholarship-main">

                    {{-- EXAMINATION INFORMATION --}}
                    <section class="form-card">
                        <div class="form-panel-header">
                            <div class="panel-title-wrap">
                                <div class="panel-title-icon">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <div>
                                    <div class="section-eyebrow">EXAM DETAILS</div>
                                    <h3>Examination Information</h3>
                                    <p>Basic details of the scholarship examination.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="form-grid">
                                <div class="diary-filter-group full">
                                    <label for="exam_name">
                                        Exam Name <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-award input-icon"></i>
                                        <input type="text"
                                               name="exam_name"
                                               id="exam_name"
                                               value="{{ old('exam_name') }}"
                                               class="diary-control with-icon @error('exam_name') is-invalid @enderror"
                                               placeholder="e.g. Maharashtra Scholarship Examination"
                                               required>
                                    </div>
                                    @error('exam_name')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="diary-filter-group">
                                    <label for="academic_year">
                                        Academic Year <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-calendar input-icon"></i>
                                        <input type="text"
                                               name="academic_year"
                                               id="academic_year"
                                               value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                                               class="diary-control with-icon @error('academic_year') is-invalid @enderror"
                                               placeholder="e.g. 2026-2027"
                                               required>
                                    </div>
                                    <div class="field-help">
                                        <i class="fas fa-circle-info"></i>
                                        Use the same academic year format as the Student Module.
                                    </div>
                                    @error('academic_year')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="diary-filter-group">
                                    <label for="exam_date">
                                        Exam Date <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-calendar-days input-icon"></i>
                                        <input type="date"
                                               name="exam_date"
                                               id="exam_date"
                                               value="{{ old('exam_date') }}"
                                               class="diary-control with-icon @error('exam_date') is-invalid @enderror"
                                               required>
                                    </div>
                                    @error('exam_date')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="diary-filter-group">
                                    <label for="exam_type">
                                        Exam Type <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-layer-group input-icon"></i>
                                        <select name="exam_type"
                                                id="exam_type"
                                                class="diary-control with-icon @error('exam_type') is-invalid @enderror"
                                                required>
                                            <option value="">Select Exam Type</option>
                                            <option value="Government Scholarship" {{ old('exam_type') == 'Government Scholarship' ? 'selected' : '' }}>Government Scholarship</option>
                                            <option value="School Scholarship" {{ old('exam_type') == 'School Scholarship' ? 'selected' : '' }}>School Scholarship</option>
                                            <option value="State Scholarship" {{ old('exam_type') == 'State Scholarship' ? 'selected' : '' }}>State Scholarship</option>
                                            <option value="National Scholarship" {{ old('exam_type') == 'National Scholarship' ? 'selected' : '' }}>National Scholarship</option>
                                            <option value="Other" {{ old('exam_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    @error('exam_type')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="diary-filter-group">
                                    <label for="conducted_by">Conducted By</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-building input-icon"></i>
                                        <input type="text"
                                               name="conducted_by"
                                               id="conducted_by"
                                               value="{{ old('conducted_by') }}"
                                               class="diary-control with-icon"
                                               placeholder="e.g. School / Education Department">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- PARTICIPATING CLASSES --}}
                    <section class="form-card">
                        <div class="form-panel-header">
                            <div class="panel-title-wrap">
                                <div class="panel-title-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div>
                                    <div class="section-eyebrow">CLASSES</div>
                                    <h3>Participating Classes</h3>
                                    <p>Select classes from the existing Class Module.</p>
                                </div>
                            </div>
                            <button type="button" class="diary-reset-btn" id="selectAllClasses">
                                Select All
                            </button>
                        </div>

                        <div class="form-body">
                            @if($classes->count())
                                <div class="class-selection">
                                    @foreach($classes as $class)
                                        <div class="class-option">
                                            <input type="checkbox"
                                                   name="class_ids[]"
                                                   value="{{ $class->id }}"
                                                   id="class_{{ $class->id }}"
                                                   {{ in_array($class->id, old('class_ids', [])) ? 'checked' : '' }}>
                                            <label for="class_{{ $class->id }}">
                                                <span class="class-check">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <span class="class-info">
                                                    <span class="class-name">{{ $class->class_name }}</span>
                                                    <span class="class-section">Section {{ $class->section ?: '—' }}</span>
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="class-students-container" id="classStudentsContainer" aria-live="polite">
                                    <div class="student-list-message">
                                        <i class="fas fa-users"></i>
                                        Select a class above to load its students and mark their scholarship status.
                                    </div>
                                </div>
                            @else
                                <div class="empty-classes">
                                    <div class="empty-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <strong>No Active Classes Found</strong>
                                    <span>Please add active classes in the Class Module first.</span>
                                </div>
                            @endif

                            @error('class_ids')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </section>

                    {{-- EXAMINATION STATISTICS --}}
                    <section class="form-card">
                        <div class="form-panel-header">
                            <div class="panel-title-wrap">
                                <div class="panel-title-icon">
                                    <i class="fas fa-chart-column"></i>
                                </div>
                                <div>
                                    <div class="section-eyebrow">STATISTICS</div>
                                    <h3>Examination Statistics</h3>
                                    <p>Enter the recorded participation numbers.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="form-grid three">
                                <div class="diary-filter-group counter-card">
                                    <label for="total_eligible">
                                        Eligible Students <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-users input-icon"></i>
                                        <input type="number"
                                               name="total_eligible"
                                               id="total_eligible"
                                               value="{{ old('total_eligible', 0) }}"
                                               min="0"
                                               class="diary-control with-icon @error('total_eligible') is-invalid @enderror"
                                               required>
                                    </div>
                                    @error('total_eligible')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="diary-filter-group counter-card">
                                    <label for="total_applied">
                                        Applied Students <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user-plus input-icon"></i>
                                        <input type="number"
                                               name="total_applied"
                                               id="total_applied"
                                               value="{{ old('total_applied', 0) }}"
                                               min="0"
                                               class="diary-control with-icon @error('total_applied') is-invalid @enderror"
                                               required>
                                    </div>
                                    @error('total_applied')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="diary-filter-group counter-card">
                                    <label for="total_appeared">
                                        Appeared Students <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user-check input-icon"></i>
                                        <input type="number"
                                               name="total_appeared"
                                               id="total_appeared"
                                               value="{{ old('total_appeared', 0) }}"
                                               min="0"
                                               class="diary-control with-icon @error('total_appeared') is-invalid @enderror"
                                               required>
                                    </div>
                                    @error('total_appeared')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="diary-filter-group counter-card">
                                    <label for="total_passed">
                                        Passed Students <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-medal input-icon"></i>
                                        <input type="number"
                                               name="total_passed"
                                               id="total_passed"
                                               value="{{ old('total_passed', 0) }}"
                                               min="0"
                                               class="diary-control with-icon @error('total_passed') is-invalid @enderror"
                                               required>
                                    </div>
                                    @error('total_passed')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- REMARKS --}}
                    <section class="form-card">
                        <div class="form-panel-header">
                            <div class="panel-title-wrap">
                                <div class="panel-title-icon">
                                    <i class="fas fa-note-sticky"></i>
                                </div>
                                <div>
                                    <div class="section-eyebrow">NOTES</div>
                                    <h3>Additional Information</h3>
                                    <p>Optional notes for historical reference.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="description-card">
                                <div class="description-header">
                                    <div class="description-icon">
                                        <i class="fas fa-pen"></i>
                                    </div>
                                    <label for="remarks">Remarks</label>
                                    <span class="optional-badge">Optional</span>
                                </div>
                                <textarea name="remarks"
                                          id="remarks"
                                          rows="4"
                                          class="diary-textarea"
                                          placeholder="Enter any additional information about this scholarship examination...">{{ old('remarks') }}</textarea>
                            </div>
                        </div>
                    </section>

                    {{-- ACTIONS --}}
                    <div class="form-actions">
                        <a href="{{ route('admin.scholarship.exams.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-xmark"></i>
                            Cancel
                        </a>
                        <button type="submit" class="diary-primary-btn" id="saveExamBtn">
                            <i class="fas fa-check"></i>
                            Save Exam Record
                        </button>
                    </div>
                </div>

                {{-- ===================== RIGHT SIDEBAR ===================== --}}
                <aside class="scholarship-sidebar">

                    {{-- RECORD INFORMATION --}}
                    <section class="form-card">
                        <div class="form-panel-header">
                            <div class="panel-title-wrap">
                                <div class="panel-title-icon">
                                    <i class="fas fa-circle-info"></i>
                                </div>
                                <div>
                                    <div class="section-eyebrow">GUIDE</div>
                                    <h3>Record Information</h3>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="info-list">
                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div>
                                        <strong>Year-wise History</strong>
                                        <span>Maintain scholarship examination records for every academic year.</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div>
                                        <strong>Existing Classes</strong>
                                        <span>Classes are loaded directly from the Class Management module.</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <strong>Existing Students</strong>
                                        <span>Student records will be linked from the Student Management module.</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <div>
                                        <strong>Passed Students</strong>
                                        <span>Only students who passed will be recorded in the scholarship result history.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- STATUS --}}
                    <section class="form-card">
                        <div class="form-panel-header">
                            <div class="panel-title-wrap">
                                <div class="panel-title-icon">
                                    <i class="fas fa-toggle-on"></i>
                                </div>
                                <div>
                                    <div class="section-eyebrow">VISIBILITY</div>
                                    <h3>Record Status</h3>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="status-box">
                                <div class="status-row">
                                    <div class="status-text">
                                        <strong>Active Record</strong>
                                        <span>Keep this exam available in historical records.</span>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox"
                                               name="status"
                                               value="1"
                                               {{ old('status', true) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- DATA RULES --}}
                    <section class="form-card">
                        <div class="form-panel-header">
                            <div class="panel-title-wrap">
                                <div class="panel-title-icon">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                                <div>
                                    <div class="section-eyebrow">VALIDATION</div>
                                    <h3>Data Rules</h3>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="rules-box">
                                <div class="rules-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <p>
                                    Applied students cannot exceed eligible students,
                                    appeared students cannot exceed applied students,
                                    and passed students cannot exceed appeared students.
                                </p>
                            </div>
                        </div>
                    </section>

                </aside>
            </div>
        </form>
    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   SCHOLARSHIP — CREATE EXAM RECORD
   Same tokens, cards and buttons as previous pages.
   Every selector is scoped under .scholarship-form-page.
========================================================= */
.scholarship-form-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;
    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.scholarship-form-page *,
.scholarship-form-page *::before,
.scholarship-form-page *::after {
    box-sizing: border-box;
}

.scholarship-form-page a,
.scholarship-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.scholarship-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.scholarship-form-page .form-hero,
.scholarship-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */
.scholarship-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.scholarship-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.scholarship-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.scholarship-form-page .form-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.scholarship-form-page .form-hero-text {
    min-width: 0;
}

.scholarship-form-page .form-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.scholarship-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.scholarship-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.scholarship-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.scholarship-form-page .form-hero-actions {
    padding: 0 28px;
}

/* BUTTONS */
.scholarship-form-page .diary-primary-btn,
.scholarship-form-page .diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 16px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.scholarship-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.scholarship-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.scholarship-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.scholarship-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.scholarship-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ERROR ALERT */
.scholarship-form-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #f0d5ce;
    border-radius: 9px;
    color: #a84840;
    background: #fff4ef;
    font-size: 12px;
}

.scholarship-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.scholarship-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.scholarship-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.scholarship-form-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.scholarship-form-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

.scholarship-form-page .alert-close:hover {
    color: var(--ink);
    background: #f8e3dc;
}

/* LAYOUT */
.scholarship-form-page .scholarship-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
    gap: 20px;
    align-items: start;
}

.scholarship-form-page .scholarship-main {
    min-width: 0;
}

.scholarship-form-page .scholarship-sidebar {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* FORM CARD */
.scholarship-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
    margin-bottom: 20px;
}

.scholarship-form-page .scholarship-sidebar .form-card {
    margin-bottom: 0;
}

.scholarship-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 18px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.scholarship-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.scholarship-form-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.scholarship-form-page .section-eyebrow {
    margin-bottom: 3px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.scholarship-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.scholarship-form-page .panel-title-wrap p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM BODY */
.scholarship-form-page .form-body {
    padding: 20px 18px;
}

.scholarship-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px 14px;
}

.scholarship-form-page .form-grid.three {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.scholarship-form-page .diary-filter-group {
    min-width: 0;
}

.scholarship-form-page .diary-filter-group.full {
    grid-column: 1 / -1;
}

.scholarship-form-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.scholarship-form-page .required {
    color: var(--red);
}

.scholarship-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.scholarship-form-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
    transition: color .18s ease;
}

.scholarship-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.scholarship-form-page .diary-control,
.scholarship-form-page .diary-textarea {
    display: block;
    width: 100%;
    min-width: 0;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.scholarship-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.scholarship-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.scholarship-form-page select.diary-control {
    cursor: pointer;
}

.scholarship-form-page .diary-control::placeholder,
.scholarship-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.scholarship-form-page .diary-control:hover,
.scholarship-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.scholarship-form-page .diary-control:focus,
.scholarship-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.scholarship-form-page .diary-control.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.scholarship-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.scholarship-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.scholarship-form-page .field-error {
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
}

/* CLASS SELECTION */
.scholarship-form-page .class-selection {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.scholarship-form-page .class-option {
    position: relative;
}

.scholarship-form-page .class-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.scholarship-form-page .class-option label {
    min-height: 52px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #deded2;
    border-radius: 9px;
    background: #fffefa;
    cursor: pointer;
    transition: .18s ease;
}

.scholarship-form-page .class-option label:hover {
    border-color: #b7c4b7;
    background: #faf9f4;
}

.scholarship-form-page .class-option input:checked + label {
    border-color: var(--green);
    background: #edf7ed;
    box-shadow: 0 0 0 2px rgba(58, 128, 96, .08);
}

.scholarship-form-page .class-check {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #deded2;
    color: transparent;
    background: #fff;
    font-size: 10px;
}

.scholarship-form-page .class-option input:checked + label .class-check {
    border-color: var(--green);
    background: var(--green);
    color: #fff;
}

.scholarship-form-page .class-name {
    display: block;
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
}

.scholarship-form-page .class-section {
    display: block;
    margin-top: 2px;
    color: var(--muted);
    font-size: 10px;
}

/* STUDENT STATUS WORKFLOW */
.scholarship-form-page .class-students-container {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.scholarship-form-page .class-students-panel {
    border: 1px solid var(--line);
    border-radius: 9px;
    overflow: hidden;
    background: #fffefa;
}

.scholarship-form-page .class-students-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 14px;
    background: #faf9f4;
    border-bottom: 1px solid var(--line);
}

.scholarship-form-page .class-students-heading strong {
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.scholarship-form-page .class-students-heading span {
    color: var(--muted);
    font-size: 10px;
}

.scholarship-form-page .student-status-search {
    width: calc(100% - 28px);
    max-width: 320px;
    margin: 12px 14px 0;
    padding: 9px 12px;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    background: #fffefa;
    font-size: 12px;
}

.scholarship-form-page .student-status-search:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.scholarship-form-page .student-status-table-wrap {
    overflow-x: auto;
    padding: 12px 14px 14px;
}

.scholarship-form-page .student-status-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 680px;
}

.scholarship-form-page .student-status-table th {
    padding: 10px 9px;
    text-align: left;
    color: var(--muted);
    background: #faf9f4;
    border-bottom: 1px solid var(--line);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.scholarship-form-page .student-status-table td {
    padding: 11px 9px;
    color: var(--text);
    border-bottom: 1px solid #f0ebe0;
    font-size: 11px;
    vertical-align: middle;
}

.scholarship-form-page .student-status-table tbody tr:last-child td {
    border-bottom: 0;
}

.scholarship-form-page .student-status-table tbody tr:hover {
    background: #faf9f4;
}

.scholarship-form-page .student-name-main {
    display: block;
    color: var(--ink);
    font-weight: 700;
}

.scholarship-form-page .student-meta {
    display: block;
    margin-top: 3px;
    color: var(--muted);
    font-size: 10px;
}

.scholarship-form-page .student-status-choice {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    cursor: pointer;
}

.scholarship-form-page .student-status-choice input {
    accent-color: var(--green);
    width: 14px;
    height: 14px;
    margin: 0;
    cursor: pointer;
}

.scholarship-form-page .student-status-choice span {
    font-size: 10px;
    font-weight: 700;
}

.scholarship-form-page .student-status-choice.eligible span { color: var(--muted); }
.scholarship-form-page .student-status-choice.applied span { color: var(--blue); }
.scholarship-form-page .student-status-choice.appeared span { color: var(--amber); }
.scholarship-form-page .student-status-choice.passed span { color: var(--green); }

.scholarship-form-page .student-list-message {
    padding: 22px 14px;
    color: var(--muted);
    text-align: center;
    font-size: 12px;
}

.scholarship-form-page .student-list-message i {
    margin-right: 6px;
}

.scholarship-form-page .student-row-hidden {
    display: none;
}

.scholarship-form-page .student-status-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 12px;
}

.scholarship-form-page .student-status-summary span {
    padding: 5px 9px;
    border-radius: 6px;
    background: #faf9f4;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
}

.scholarship-form-page .student-status-summary .summary-applied {
    background: #eef4f8;
    color: var(--blue);
}

.scholarship-form-page .student-status-summary .summary-appeared {
    background: #faf5e7;
    color: #765e32;
}

.scholarship-form-page .student-status-summary .summary-passed {
    background: #edf7ed;
    color: #347849;
}

/* EMPTY CLASSES */
.scholarship-form-page .empty-classes {
    padding: 28px 16px;
    text-align: center;
    border: 1px dashed var(--line);
    border-radius: 9px;
    background: #faf9f4;
}

.scholarship-form-page .empty-classes .empty-icon {
    color: var(--ink);
    font-size: 24px;
    margin-bottom: 8px;
}

.scholarship-form-page .empty-classes strong {
    display: block;
    color: var(--ink);
    font-size: 13px;
}

.scholarship-form-page .empty-classes span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
}

/* DESCRIPTION CARD */
.scholarship-form-page .description-card {
    padding: 14px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
}

.scholarship-form-page .description-header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 10px;
}

.scholarship-form-page .description-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 11px;
}

.scholarship-form-page .description-header label {
    margin: 0;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.scholarship-form-page .optional-badge {
    padding: 3px 8px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #fffefa;
    font-size: 9px;
    font-weight: 700;
}

.scholarship-form-page .diary-textarea {
    min-height: 100px;
    padding: 11px 12px;
    line-height: 1.65;
    resize: vertical;
}

/* SIDEBAR INFO */
.scholarship-form-page .info-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.scholarship-form-page .info-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;
}

.scholarship-form-page .info-item-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    border: 1px solid #e3e2d5;
    border-radius: 8px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 12px;
}

.scholarship-form-page .info-item strong {
    display: block;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.scholarship-form-page .info-item span {
    display: block;
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* STATUS SWITCH */
.scholarship-form-page .status-box {
    padding: 14px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
}

.scholarship-form-page .status-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
}

.scholarship-form-page .status-text strong {
    display: block;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.scholarship-form-page .status-text span {
    display: block;
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
}

.scholarship-form-page .switch {
    position: relative;
    width: 42px;
    height: 22px;
    flex-shrink: 0;
}

.scholarship-form-page .switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.scholarship-form-page .slider {
    position: absolute;
    inset: 0;
    cursor: pointer;
    border-radius: 30px;
    background: #c5c9be;
    transition: .2s ease;
}

.scholarship-form-page .slider::before {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    left: 3px;
    top: 3px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,.12);
    transition: .2s ease;
}

.scholarship-form-page .switch input:checked + .slider {
    background: var(--green);
}

.scholarship-form-page .switch input:checked + .slider::before {
    transform: translateX(20px);
}

/* RULES BOX */
.scholarship-form-page .rules-box {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 14px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.scholarship-form-page .rules-icon {
    color: var(--amber);
    font-size: 14px;
    margin-top: 1px;
}

.scholarship-form-page .rules-box p {
    margin: 0;
    color: #8a7447;
    font-size: 11px;
    line-height: 1.6;
}

/* FORM ACTIONS */
.scholarship-form-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 4px;
    padding: 16px 0 0;
}

/* RESPONSIVE */
@media (max-width: 1100px) {
    .scholarship-form-page .scholarship-layout {
        grid-template-columns: 1fr;
    }

    .scholarship-form-page .class-selection {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .scholarship-form-page .form-hero {
        grid-template-columns: 1fr;
    }
    .scholarship-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

@media (max-width: 650px) {
    .scholarship-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .scholarship-form-page .form-hero::before {
        left: 22px;
    }

    .scholarship-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .scholarship-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .scholarship-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .scholarship-form-page .form-hero-text p {
        font-size: 12px;
    }

    .scholarship-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .scholarship-form-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .scholarship-form-page .form-panel-header {
        align-items: flex-start;
        padding: 14px 15px;
        flex-wrap: wrap;
    }

    .scholarship-form-page .form-body {
        padding: 16px 15px;
    }

    .scholarship-form-page .form-grid,
    .scholarship-form-page .form-grid.three {
        grid-template-columns: 1fr;
    }

    .scholarship-form-page .class-selection {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .scholarship-form-page .diary-control,
    .scholarship-form-page .diary-textarea {
        font-size: 16px;
    }

    .scholarship-form-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .scholarship-form-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .scholarship-form-page .class-selection {
        grid-template-columns: 1fr;
    }

    .scholarship-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
}

/* ACCESSIBILITY */
.scholarship-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.scholarship-form-page .diary-control:focus-visible,
.scholarship-form-page .diary-textarea:focus-visible {
    outline: none;
}

@media (prefers-reduced-motion: reduce) {
    .scholarship-form-page *,
    .scholarship-form-page *::before,
    .scholarship-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classCheckboxes = Array.from(document.querySelectorAll('input[name="class_ids[]"]'));
    const selectAllButton = document.getElementById('selectAllClasses');
    const studentsContainer = document.getElementById('classStudentsContainer');
    const studentsUrl = @json(route('admin.scholarship.exams.students'));
    const academicYearInput = document.querySelector('input[name="academic_year"]');
    const oldStudentRecords = @json(old('student_records', []));

    const totalEligible = document.querySelector('input[name="total_eligible"]');
    const totalApplied = document.querySelector('input[name="total_applied"]');
    const totalAppeared = document.querySelector('input[name="total_appeared"]');
    const totalPassed = document.querySelector('input[name="total_passed"]');
    const form = document.getElementById('scholarshipExamForm');
    const saveBtn = document.getElementById('saveExamBtn');

    const classData = new Map();
    const statusLabels = {
        eligible: 'Eligible',
        applied: 'Applied',
        appeared: 'Appeared',
        passed: 'Passed'
    };

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (character) {
            return {
                '&': '&amp;', '<': '&lt;', '>': '&gt;',
                '"': '&quot;', "'": '&#039;'
            }[character];
        });
    }

    function studentName(student) {
        const direct = student.full_name || student.student_name || student.name;
        if (direct) return direct;
        return [student.first_name, student.middle_name, student.last_name]
            .filter(Boolean).join(' ') || 'Student';
    }

    function studentMeta(student) {
        const parts = [];
        const identifier = student.student_id || student.admission_number || student.admission_no;
        const roll = student.roll_number || student.roll_no;
        if (identifier) parts.push('ID: ' + identifier);
        if (roll !== undefined && roll !== null && roll !== '') parts.push('Roll: ' + roll);
        return parts.join(' · ');
    }

    function panelId(classId) {
        return 'students-panel-' + String(classId).replace(/[^a-zA-Z0-9_-]/g, '');
    }

    function selectedStatus(studentId) {
        const oldStatus = oldStudentRecords[String(studentId)];
        if (['eligible', 'applied', 'appeared', 'passed'].includes(oldStatus)) return oldStatus;
        return 'eligible';
    }

    function makeStatusRadio(student, status, checkedStatus) {
        const id = 'student-' + student.id + '-' + status;
        return '<label class="student-status-choice ' + status + '" for="' + id + '">' +
            '<input type="radio" id="' + id + '" name="student_records[' + escapeHtml(student.id) + ']" value="' + status + '" ' +
            (checkedStatus === status ? 'checked' : '') + '>' +
            '<span>' + statusLabels[status] + '</span></label>';
    }

    function renderClassPanel(classId, className, section, students, loadingError) {
        const id = panelId(classId);
        let panel = document.getElementById(id);
        if (!panel) {
            panel = document.createElement('section');
            panel.className = 'class-students-panel';
            panel.id = id;
            panel.dataset.classId = classId;
            studentsContainer.appendChild(panel);
        }

        const title = className + (section ? ' · Section ' + section : '');
        if (loadingError) {
            panel.innerHTML = '<div class="class-students-heading"><strong>' + escapeHtml(title) + '</strong></div>' +
                '<div class="student-list-message"><i class="fas fa-exclamation-circle"></i>' + escapeHtml(loadingError) + '</div>';
            return;
        }

        if (!students.length) {
            panel.innerHTML = '<div class="class-students-heading"><strong>' + escapeHtml(title) + '</strong><span>0 students</span></div>' +
                '<div class="student-list-message"><i class="fas fa-user-slash"></i>No students were found for this class.</div>';
            return;
        }

        const rows = students.map(function (student) {
            const status = selectedStatus(student.id);
            const meta = studentMeta(student);
            return '<tr data-student-row data-search="' + escapeHtml((studentName(student) + ' ' + meta).toLowerCase()) + '">' +
                '<td class="student-name-cell"><span class="student-name-main">' + escapeHtml(studentName(student)) + '</span>' +
                (meta ? '<span class="student-meta">' + escapeHtml(meta) + '</span>' : '') + '</td>' +
                '<td>' + makeStatusRadio(student, 'eligible', status) + '</td>' +
                '<td>' + makeStatusRadio(student, 'applied', status) + '</td>' +
                '<td>' + makeStatusRadio(student, 'appeared', status) + '</td>' +
                '<td>' + makeStatusRadio(student, 'passed', status) + '</td>' +
                '</tr>';
        }).join('');

        panel.innerHTML = '<div class="class-students-heading"><strong><i class="fas fa-users me-1"></i> ' + escapeHtml(title) +
            '</strong><span>' + students.length + ' student' + (students.length === 1 ? '' : 's') + '</span></div>' +
            '<input type="search" class="student-status-search" placeholder="Search students in this class..." aria-label="Search students">' +
            '<div class="student-status-table-wrap"><table class="student-status-table"><thead><tr>' +
            '<th>Student</th><th>Eligible</th><th>Applied</th><th>Appeared</th><th>Passed</th>' +
            '</tr></thead><tbody>' + rows + '</tbody></table>' +
            '<div class="student-status-summary"><span class="summary-eligible">Eligible: 0</span>' +
            '<span class="summary-applied">Applied: 0</span><span class="summary-appeared">Appeared: 0</span>' +
            '<span class="summary-passed">Passed: 0</span></div></div>';

        const search = panel.querySelector('.student-status-search');
        search.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();
            panel.querySelectorAll('[data-student-row]').forEach(function (row) {
                row.classList.toggle('student-row-hidden', !row.dataset.search.includes(term));
            });
        });
    }

    function updateStatistics() {
        let eligible = 0, applied = 0, appeared = 0, passed = 0;
        document.querySelectorAll('input[name^="student_records["]:checked').forEach(function (input) {
            eligible++;
            if (['applied', 'appeared', 'passed'].includes(input.value)) applied++;
            if (['appeared', 'passed'].includes(input.value)) appeared++;
            if (input.value === 'passed') passed++;
        });

        if (totalEligible) totalEligible.value = eligible;
        if (totalApplied) totalApplied.value = applied;
        if (totalAppeared) totalAppeared.value = appeared;
        if (totalPassed) totalPassed.value = passed;

        document.querySelectorAll('.class-students-panel').forEach(function (panel) {
            const counts = {eligible: 0, applied: 0, appeared: 0, passed: 0};
            panel.querySelectorAll('input[name^="student_records["]:checked').forEach(function (input) {
                counts.eligible++;
                if (['applied', 'appeared', 'passed'].includes(input.value)) counts.applied++;
                if (['appeared', 'passed'].includes(input.value)) counts.appeared++;
                if (input.value === 'passed') counts.passed++;
            });
            ['eligible', 'applied', 'appeared', 'passed'].forEach(function (status) {
                const summary = panel.querySelector('.summary-' + status);
                if (summary) summary.textContent = statusLabels[status] + ': ' + counts[status];
            });
        });
    }

    async function loadStudentsForClass(checkbox) {
        const classId = checkbox.value;
        const classOption = checkbox.closest('.class-option');
        const className = classOption?.querySelector('.class-name')?.textContent.trim() || 'Class';
        const sectionText = classOption?.querySelector('.class-section')?.textContent || '';
        const section = sectionText.replace(/^\s*Section\s*/i, '').trim();
        const id = panelId(classId);

        if (!checkbox.checked) {
            document.getElementById(id)?.remove();
            classData.delete(String(classId));
            updateStatistics();
            return;
        }

        renderClassPanel(classId, className, section, [], null);
        const panel = document.getElementById(id);
        if (panel) panel.innerHTML = '<div class="class-students-heading"><strong>' + escapeHtml(className) +
            (section && section !== '—' ? ' · Section ' + escapeHtml(section) : '') +
            '</strong></div><div class="student-list-message"><i class="fas fa-spinner fa-spin"></i>Loading students...</div>';

        try {
            const academicYear = academicYearInput ? academicYearInput.value.trim() : '';
            if (!academicYear) {
                throw new Error('Please enter the academic year before selecting a class.');
            }

            const query = new URLSearchParams({
                class_id: classId,
                academic_year: academicYear
            });
            const response = await fetch(studentsUrl + '?' + query.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const responseText = await response.text();
            let data;
            try {
                data = JSON.parse(responseText.replace(/^\uFEFF/, '').trim());
            } catch (parseError) {
                console.error('Scholarship students endpoint returned non-JSON:', responseText);
                throw new Error('The students endpoint did not return valid JSON. Check the Laravel route/controller response.');
            }

            if (!response.ok) {
                const validationMessage = data.message ||
                    (data.errors ? Object.values(data.errors).flat().join(' ') : 'HTTP ' + response.status);
                throw new Error('Could not load students: ' + validationMessage);
            }
            if (data.success === false) {
                throw new Error(data.message || 'The students endpoint could not load students.');
            }

            const students = Array.isArray(data)
                ? data
                : (Array.isArray(data.students) ? data.students : []);
            classData.set(String(classId), students);
            if (!checkbox.checked) return;
            renderClassPanel(classId, className, section === '—' ? '' : section, students, null);
            updateStatistics();
        } catch (error) {
            console.error('Scholarship students loading error:', error);
            renderClassPanel(classId, className, section, [], error.message || 'Unable to load students.');
        }
    }

    classCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            loadStudentsForClass(checkbox);
            if (selectAllButton) {
                selectAllButton.textContent = classCheckboxes.every(function (item) { return item.checked; }) ? 'Clear All' : 'Select All';
            }
        });
    });

    if (selectAllButton) {
        selectAllButton.addEventListener('click', function () {
            const shouldCheck = !classCheckboxes.every(function (checkbox) { return checkbox.checked; });
            classCheckboxes.forEach(function (checkbox) {
                if (checkbox.checked !== shouldCheck) {
                    checkbox.checked = shouldCheck;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
            this.textContent = shouldCheck ? 'Clear All' : 'Select All';
        });
    }

    document.addEventListener('change', function (event) {
        if (event.target.matches('input[name^="student_records["]')) updateStatistics();
    });

    if (form) {
        form.addEventListener('submit', function (event) {
            const checkedClasses = classCheckboxes.filter(function (checkbox) { return checkbox.checked; });
            if (!checkedClasses.length) {
                event.preventDefault();
                alert('Please select at least one class.');
                classCheckboxes[0]?.focus();
                return;
            }

            const loading = checkedClasses.some(function (checkbox) {
                const panel = document.getElementById(panelId(checkbox.value));
                return !panel || panel.querySelector('.fa-spinner');
            });
            if (loading) {
                event.preventDefault();
                alert('Please wait until the selected class student lists finish loading.');
                return;
            }

            const eligibleValue = parseInt(totalEligible?.value || 0, 10);
            const appliedValue = parseInt(totalApplied?.value || 0, 10);
            const appearedValue = parseInt(totalAppeared?.value || 0, 10);
            const passedValue = parseInt(totalPassed?.value || 0, 10);
            if (appliedValue > eligibleValue || appearedValue > appliedValue || passedValue > appearedValue) {
                event.preventDefault();
                alert('Please check the student statuses. Applied cannot exceed eligible, appeared cannot exceed applied, and passed cannot exceed appeared.');
                return;
            }

            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            }
        });
    }

    if (academicYearInput) {
        academicYearInput.addEventListener('change', function () {
            classCheckboxes.filter(function (checkbox) { return checkbox.checked; }).forEach(loadStudentsForClass);
        });
    }

    classCheckboxes.filter(function (checkbox) { return checkbox.checked; }).forEach(loadStudentsForClass);
    updateStatistics();
});
</script>
@endsection