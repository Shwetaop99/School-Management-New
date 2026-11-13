
@extends('layouts.app')

@section('title', 'Subjects & Marks')

@section('content')

<div class="subjects-page">

    {{-- PAGE HEADER --}}
    <div class="subjects-header">
        <div class="subjects-header-main">
            <a href="{{ route('admin.exams.show', $exam) }}"
               class="subjects-back-icon"
               title="Back to Exam"
               aria-label="Back to Exam">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div class="subjects-page-icon">
                <i class="bi bi-bookmarks"></i>
            </div>

            <div class="subjects-heading-content">
                <div class="subjects-title-line">
                    <h1>Subjects &amp; Marks</h1>
                    <span class="subjects-title-badge">
                        Exam Setup
                    </span>
                </div>

                
            </div>
        </div>

        <a href="{{ route('admin.exams.show', $exam) }}"
           class="subjects-secondary-btn">
            <i class="bi bi-arrow-left"></i>
            Back to Exam
        </a>
    </div>

    {{-- BREADCRUMB --}}
    <div class="subjects-breadcrumb">
        <i class="bi bi-journal-text"></i>
        <span>{{ $exam->exam_name }}</span>

        <span class="subjects-breadcrumb-dot">/</span>

        <span>{{ $exam->academic_year }}</span>

        @if(!empty($exam->exam_type))
            <span class="subjects-breadcrumb-dot">/</span>
            <span>{{ $exam->exam_type }}</span>
        @endif
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="subjects-alert subjects-alert-success" role="alert">
            <div class="subjects-alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="subjects-alert-content">
                <strong>Configuration Saved</strong>
                <p>{{ session('success') }}</p>
            </div>

            <button type="button"
                    class="subjects-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="subjects-alert subjects-alert-danger" role="alert">
            <div class="subjects-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="subjects-alert-content">
                <strong>Please correct the following errors</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- INFORMATION CARD --}}
    <div class="subjects-information-card">
        <div class="subjects-information-icon">
            <i class="bi bi-info-circle-fill"></i>
        </div>

        <div>
            <h3>Subjects are managed from the Classes module</h3>

            <p>
                Subjects shown below are fetched automatically from Class
                Management. Configure which subjects are included in this
                examination and set their maximum marks, passing marks and duration.
            </p>
        </div>
    </div>

    {{-- NO CLASSES SELECTED --}}
    @if($examClasses->isEmpty())

        <section class="subjects-empty-card">
            <div class="subjects-empty-icon">
                <i class="bi bi-mortarboard"></i>
            </div>

            <h2>No Classes Selected</h2>

            <p>
                Select at least one class before configuring subjects and marks
                for this examination.
            </p>

            <a href="{{ route('admin.exam-classes.index', $exam) }}"
               class="subjects-primary-btn">
                <i class="bi bi-mortarboard"></i>
                Select Classes
            </a>
        </section>

    @else

        {{-- SUMMARY CALCULATION --}}
        @php
            $totalClasses = $examClasses->count();
            $totalAvailableSubjects = 0;

            foreach ($examClasses as $summaryExamClass) {
                if ($summaryExamClass->schoolClass) {
                    $totalAvailableSubjects +=
                        ($summaryExamClass->schoolClass->subjects ?? collect())->count();
                }
            }
        @endphp

        {{-- SUMMARY CARDS --}}
        <div class="subjects-summary-grid">

            <div class="subjects-summary-card">
                <div class="subjects-summary-top forest"></div>

                <div class="subjects-summary-icon forest">
                    <i class="bi bi-mortarboard"></i>
                </div>

                <div class="subjects-summary-content">
                    <span class="subjects-summary-label">Selected Classes</span>
                    <strong>{{ $totalClasses }}</strong>
                    <span class="subjects-summary-note">
                        Classes included in this exam
                    </span>
                </div>
            </div>

            <div class="subjects-summary-card">
                <div class="subjects-summary-top blue"></div>

                <div class="subjects-summary-icon blue">
                    <i class="bi bi-book"></i>
                </div>

                <div class="subjects-summary-content">
                    <span class="subjects-summary-label">Available Subjects</span>
                    <strong>{{ $totalAvailableSubjects }}</strong>
                    <span class="subjects-summary-note">
                        Subjects fetched from Classes
                    </span>
                </div>
            </div>

            <div class="subjects-summary-card">
                <div class="subjects-summary-top amber"></div>

                <div class="subjects-summary-icon amber">
                    <i class="bi bi-sliders"></i>
                </div>

                <div class="subjects-summary-content">
                    <span class="subjects-summary-label">Configuration</span>
                    <strong class="subjects-summary-word">Exam</strong>
                    <span class="subjects-summary-note">
                        Marks and duration are exam-specific
                    </span>
                </div>
            </div>

        </div>

        {{-- MAIN FORM --}}
        <form method="POST"
              action="{{ route('admin.exam-subjects.store', $exam) }}"
              id="examSubjectsForm">

            @csrf

            {{-- CLASS-WISE SUBJECTS --}}
            @foreach($examClasses as $examClass)

                @php
                    $class = $examClass->schoolClass;
                    $classSubjects = $class?->subjects ?? collect();
                    $activeSubjectCount = $classSubjects->count();
                @endphp

                @if($class)

                    <section class="subjects-class-card">

                        {{-- CLASS HEADER --}}
                        <div class="subjects-class-header">

                            <div class="subjects-class-heading">
                                <div class="subjects-class-icon">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>

                                <div>
                                    <div class="subjects-class-title-line">
                                        <h2>{{ $class->class_name }}</h2>

                                        @if($class->section)
                                            <span class="subjects-section-badge">
                                                Section {{ $class->section }}
                                            </span>
                                        @endif
                                    </div>

                                    <p>
                                        <i class="bi bi-book"></i>

                                        {{ $activeSubjectCount }}
                                        {{ Str::plural('subject', $activeSubjectCount) }}
                                        available
                                    </p>
                                </div>
                            </div>

                            @if($classSubjects->isNotEmpty())
                                <button type="button"
                                        class="subjects-select-all-btn"
                                        data-class-id="{{ $class->id }}"
                                        aria-pressed="false">
                                    <i class="bi bi-check2-all"></i>
                                    <span>Select All</span>
                                </button>
                            @endif

                        </div>

                        {{-- SUBJECT TABLE OR EMPTY STATE --}}
                        <div class="subjects-class-body">

                            @if($classSubjects->isEmpty())

                                <div class="subjects-class-empty">
                                    <div class="subjects-class-empty-icon">
                                        <i class="bi bi-book"></i>
                                    </div>

                                    <div>
                                        <strong>No Active Subjects</strong>
                                        <p>
                                            No active subjects are currently
                                            available for this class.
                                        </p>
                                    </div>
                                </div>

                            @else

                                <div class="subjects-table-wrap">
                                    <table class="subjects-table">
                                        <thead>
                                            <tr>
                                                <th class="subjects-include-column">
                                                    Include
                                                </th>
                                                <th>Subject</th>
                                                <th>Code</th>
                                                <th>Maximum Marks</th>
                                                <th>Passing Marks</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($classSubjects as $subject)

                                                @php
                                                    $key = $class->id . '_' . $subject->id;
                                                    $existing = $examSubjects[$key] ?? null;

                                                    $statusValue = old(
                                                        'subjects.' . $key . '.status',
                                                        $existing ? (int) $existing->status : 1
                                                    );
                                                @endphp

                                                <tr class="subject-row">

                                                    {{-- INCLUDE SUBJECT --}}
                                                    <td>
                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $key }}][class_id]"
                                                            value="{{ $class->id }}">

                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $key }}][subject_id]"
                                                            value="{{ $subject->id }}">

                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $key }}][status]"
                                                            value="0">

                                                        <label class="subjects-checkbox-wrap">
                                                            <input
                                                                class="subject-checkbox"
                                                                type="checkbox"
                                                                name="subjects[{{ $key }}][status]"
                                                                value="1"
                                                                data-class-id="{{ $class->id }}"
                                                                {{ (string) $statusValue === '1' ? 'checked' : '' }}
                                                                aria-label="Include {{ $subject->subject_name }}">

                                                            <span class="subjects-custom-checkbox"></span>
                                                        </label>
                                                    </td>

                                                    {{-- SUBJECT NAME --}}
                                                    <td>
                                                        <div class="subjects-name-cell">
                                                            <div class="subjects-book-icon">
                                                                <i class="bi bi-book"></i>
                                                            </div>

                                                            <div>
                                                                <strong>
                                                                    {{ $subject->subject_name }}
                                                                </strong>

                                                                <span>Exam subject</span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    {{-- SUBJECT CODE --}}
                                                    <td>
                                                        @if($subject->subject_code)
                                                            <span class="subjects-code">
                                                                {{ $subject->subject_code }}
                                                            </span>
                                                        @else
                                                            <span class="subjects-dash">—</span>
                                                        @endif
                                                    </td>

                                                    {{-- MAXIMUM MARKS --}}
                                                    <td>
                                                        <div class="subjects-input-group">
                                                            <span class="subjects-input-icon">
                                                                <i class="bi bi-award"></i>
                                                            </span>

                                                            <input
                                                                type="number"
                                                                name="subjects[{{ $key }}][maximum_marks]"
                                                                class="subjects-number-input maximum-marks"
                                                                min="1"
                                                                max="1000"
                                                                value="{{ old(
                                                                    'subjects.' . $key . '.maximum_marks',
                                                                    $existing?->maximum_marks ?? 50
                                                                ) }}"
                                                                required
                                                                aria-label="Maximum marks for {{ $subject->subject_name }}">
                                                        </div>
                                                    </td>

                                                    {{-- PASSING MARKS --}}
                                                    <td>
                                                        <div class="subjects-input-group">
                                                            <span class="subjects-input-icon">
                                                                <i class="bi bi-check-circle"></i>
                                                            </span>

                                                            <input
                                                                type="number"
                                                                name="subjects[{{ $key }}][passing_marks]"
                                                                class="subjects-number-input passing-marks"
                                                                min="0"
                                                                max="1000"
                                                                value="{{ old(
                                                                    'subjects.' . $key . '.passing_marks',
                                                                    $existing?->passing_marks ?? 17
                                                                ) }}"
                                                                required
                                                                aria-label="Passing marks for {{ $subject->subject_name }}">
                                                        </div>
                                                    </td>

                                                    {{-- DURATION --}}
                                                    <td>
                                                        <div class="subjects-duration-group">
                                                            <input
                                                                type="number"
                                                                name="subjects[{{ $key }}][duration_minutes]"
                                                                class="subjects-number-input duration-input"
                                                                min="1"
                                                                max="600"
                                                                value="{{ old(
                                                                    'subjects.' . $key . '.duration_minutes',
                                                                    $existing?->duration_minutes ?? 60
                                                                ) }}"
                                                                required
                                                                aria-label="Duration in minutes for {{ $subject->subject_name }}">

                                                            <span>min</span>
                                                        </div>
                                                    </td>

                                                </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @endif

                        </div>
                    </section>

                @endif

            @endforeach

            {{-- SAVE PANEL --}}
            <div class="subjects-save-panel">

                <div class="subjects-save-info">
                    <div class="subjects-save-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>
                        <strong>Exam-specific configuration</strong>
                        <p>
                            These settings apply only to
                            <span>{{ $exam->exam_name }}</span>.
                        </p>
                    </div>
                </div>

                <div class="subjects-save-actions">
                    <a href="{{ route('admin.exams.show', $exam) }}"
                       class="subjects-cancel-btn">
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="subjects-save-btn"
                            id="saveSubjectsButton">
                        <i class="bi bi-check-circle"></i>
                        <span>Save Subjects &amp; Marks</span>
                    </button>
                </div>

            </div>

        </form>

    @endif

    {{-- PAGE FOOTNOTE --}}
    <div class="subjects-footnote">
        <i class="bi bi-journal-check"></i>
        <span>
            School Diary
            <span class="subjects-footnote-dot">·</span>
            Examination Management
        </span>
    </div>

</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Subjects & Marks
========================================================= */

.subjects-page {
    --sd-page: #f7f6f0;
    --sd-paper: #fffefa;
    --sd-chalk: #f5f3e9;
    --sd-ink: #263b35;
    --sd-forest: #344f43;
    --sd-forest-dark: #20352c;
    --sd-text: #39483e;
    --sd-muted: #7b857b;
    --sd-line: #e5e1d5;
    --sd-sage: #527f70;
    --sd-blue: #426b8a;
    --sd-amber: #a87543;
    --sd-green: #43835d;
    --sd-red: #b95b50;

    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
    color: var(--sd-text);
    font-family: "Public Sans", Arial, sans-serif;
}

.subjects-page *,
.subjects-page *::before,
.subjects-page *::after {
    box-sizing: border-box;
}

.subjects-page h1,
.subjects-page h2,
.subjects-page h3,
.subjects-page p {
    margin-top: 0;
}

.subjects-page a {
    text-decoration: none;
}

.subjects-page button,
.subjects-page input {
    font: inherit;
}

.subjects-page button:focus-visible,
.subjects-page a:focus-visible,
.subjects-page input:focus-visible {
    outline: 3px solid rgba(82, 127, 112, 0.35);
    outline-offset: 3px;
}

/* HEADER */

.subjects-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 15px;
}

.subjects-header-main {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.subjects-back-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 35px;
    width: 35px;
    height: 35px;
    border: 1px solid var(--sd-line);
    border-radius: 10px;
    background: var(--sd-paper);
    color: var(--sd-ink);
    transition: all 0.2s ease;
}

.subjects-back-icon:hover {
    border-color: #c4d3c3;
    background: #edf1e8;
    color: var(--sd-forest);
}

.subjects-page-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 51px;
    width: 51px;
    height: 51px;
    border: 1px solid #d8e1d7;
    border-radius: 15px;
    background: #e9eee5;
    color: var(--sd-forest);
    font-size: 23px;
}

.subjects-page-icon::after {
    content: "";
    position: absolute;
    top: 6px;
    right: 6px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #b4c8a9;
}

.subjects-heading-content {
    min-width: 0;
}

.subjects-title-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 5px;
}

.subjects-title-line h1 {
    margin: 0;
    color: var(--sd-ink);
    font-family: Georgia, serif;
    font-size: clamp(24px, 3vw, 31px);
    font-weight: 700;
    letter-spacing: -0.6px;
}

.subjects-title-badge {
    padding: 5px 10px;
    border: 1px solid #d9e3d7;
    border-radius: 30px;
    background: #edf2e9;
    color: var(--sd-forest);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.subjects-heading-content > p {
    margin: 0;
    color: var(--sd-muted);
    font-size: 12px;
    line-height: 1.7;
}

.subjects-secondary-btn,
.subjects-cancel-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 15px;
    border: 1px solid #d8d8ca;
    border-radius: 10px;
    background: var(--sd-paper);
    color: var(--sd-ink);
    font-size: 12px;
    font-weight: 700;
    transition: all 0.2s ease;
}

.subjects-secondary-btn:hover,
.subjects-cancel-btn:hover {
    border-color: #b6c5b6;
    background: #f0f1e8;
    color: var(--sd-forest-dark);
}

/* BREADCRUMB */

.subjects-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin: 0 0 23px;
    padding: 11px 14px;
    border: 1px solid var(--sd-line);
    border-radius: 10px;
    background: rgba(255, 254, 250, 0.72);
    color: var(--sd-muted);
    font-size: 11px;
    line-height: 1.7;
}

.subjects-breadcrumb > i {
    color: var(--sd-sage);
    font-size: 14px;
}

.subjects-breadcrumb-dot {
    color: #b0b4a7;
}

/* ALERTS */

.subjects-alert {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 20px;
    padding: 16px 48px 16px 17px;
    border: 1px solid;
    border-radius: 13px;
}

.subjects-alert-success {
    border-color: #d4e5d4;
    background: #f0f6ed;
    color: var(--sd-forest);
}

.subjects-alert-danger {
    border-color: #eed7d0;
    background: #fbefeb;
    color: #93473e;
}

.subjects-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(82, 127, 112, 0.11);
    font-size: 17px;
}

.subjects-alert-danger .subjects-alert-icon {
    background: rgba(185, 91, 80, 0.1);
}

.subjects-alert-content {
    min-width: 0;
}

.subjects-alert-content strong {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
}

.subjects-alert-content p {
    margin: 0;
    font-size: 12px;
    line-height: 1.7;
}

.subjects-alert-content ul {
    margin: 7px 0 0;
    padding-left: 18px;
    font-size: 12px;
    line-height: 1.8;
}

.subjects-alert-close {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    border: 0;
    border-radius: 7px;
    background: transparent;
    color: inherit;
    cursor: pointer;
    opacity: 0.7;
}

.subjects-alert-close:hover {
    background: rgba(38, 59, 53, 0.07);
    opacity: 1;
}

/* INFORMATION CARD */

.subjects-information-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 23px;
    padding: 18px 20px;
    border: 1px solid #dce5d8;
    border-radius: 14px;
    background: #f0f3e9;
}

.subjects-information-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 41px;
    width: 41px;
    height: 41px;
    border: 1px solid #d7e0d0;
    border-radius: 12px;
    background: #e3eadd;
    color: var(--sd-forest);
    font-size: 18px;
}

.subjects-information-card h3 {
    margin: 1px 0 6px;
    color: var(--sd-ink);
    font-size: 12px;
    font-weight: 800;
}

.subjects-information-card p {
    margin: 0;
    color: #657367;
    font-size: 11px;
    line-height: 1.8;
}

/* SUMMARY GRID */

.subjects-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.subjects-summary-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    min-height: 119px;
    padding: 23px 20px 19px;
    overflow: hidden;
    border: 1px solid var(--sd-line);
    border-radius: 15px;
    background: var(--sd-paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, 0.025);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.subjects-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(38, 59, 53, 0.07);
}

.subjects-summary-top {
    position: absolute;
    top: 0;
    left: 20px;
    width: 44px;
    height: 3px;
    border-radius: 0 0 5px 5px;
}

.subjects-summary-top.forest {
    background: var(--sd-sage);
}

.subjects-summary-top.blue {
    background: var(--sd-blue);
}

.subjects-summary-top.amber {
    background: var(--sd-amber);
}

.subjects-summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 46px;
    width: 46px;
    height: 46px;
    border-radius: 13px;
    font-size: 20px;
}

.subjects-summary-icon.forest {
    background: #e9eee5;
    color: var(--sd-forest);
}

.subjects-summary-icon.blue {
    background: #e9eff3;
    color: var(--sd-blue);
}

.subjects-summary-icon.amber {
    background: #f5ede0;
    color: var(--sd-amber);
}

.subjects-summary-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.subjects-summary-label {
    color: var(--sd-muted);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.45px;
    text-transform: uppercase;
}

.subjects-summary-content > strong {
    color: var(--sd-ink);
    font-size: 24px;
    font-weight: 800;
    line-height: 1.2;
}

.subjects-summary-content > strong.subjects-summary-word {
    font-family: Georgia, serif;
    font-size: 22px;
}

.subjects-summary-note {
    color: var(--sd-muted);
    font-size: 10px;
    line-height: 1.6;
}

/* CLASS CARD */

.subjects-class-card {
    margin-bottom: 22px;
    overflow: hidden;
    border: 1px solid var(--sd-line);
    border-radius: 16px;
    background: var(--sd-paper);
    box-shadow: 0 4px 16px rgba(38, 59, 53, 0.03);
}

.subjects-class-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 19px 22px;
    border-bottom: 1px solid var(--sd-line);
    background: linear-gradient(135deg, #fffefa 0%, #f7f7ef 100%);
}

.subjects-class-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.subjects-class-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 45px;
    width: 45px;
    height: 45px;
    border: 1px solid #dce5d8;
    border-radius: 13px;
    background: #e9eee5;
    color: var(--sd-forest);
    font-size: 19px;
}

.subjects-class-title-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
}

.subjects-class-title-line h2 {
    margin: 0;
    color: var(--sd-ink);
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 700;
}

.subjects-section-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border: 1px solid #e2e1d6;
    border-radius: 7px;
    background: #f5f3e9;
    color: #6d786b;
    font-size: 10px;
    font-weight: 700;
}

.subjects-class-heading p {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 6px 0 0;
    color: var(--sd-muted);
    font-size: 11px;
}

.subjects-class-heading p i {
    color: var(--sd-sage);
}

/* SELECT ALL BUTTON */

.subjects-select-all-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 37px;
    padding: 8px 12px;
    border: 1px solid #d3dfd0;
    border-radius: 9px;
    background: #edf2e9;
    color: var(--sd-forest);
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}

.subjects-select-all-btn:hover {
    border-color: #aebfae;
    background: #e2ebdd;
    color: var(--sd-forest-dark);
}

.subjects-select-all-btn i {
    font-size: 14px;
}

/* TABLE */

.subjects-class-body {
    background: var(--sd-paper);
}

.subjects-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.subjects-table {
    width: 100%;
    min-width: 920px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--sd-text);
}

.subjects-table thead th {
    padding: 15px 16px;
    border-bottom: 1px solid var(--sd-line);
    background: #f4f3eb;
    color: #727e72;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.65px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.subjects-table thead th:first-child {
    padding-left: 23px;
}

.subjects-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #eeece3;
    background: var(--sd-paper);
    vertical-align: middle;
    transition: background 0.15s ease;
}

.subjects-table tbody tr:last-child td {
    border-bottom: 0;
}

.subjects-table tbody tr:hover td {
    background: #f8f8f1;
}

.subjects-table tbody tr.subject-selected td {
    background: #f0f3e9;
}

.subjects-table tbody tr.subject-selected:hover td {
    background: #e9eee2;
}

.subjects-table tbody td:first-child {
    padding-left: 23px;
}

.subjects-include-column {
    width: 85px;
}

/* SUBJECT CHECKBOX */

.subjects-checkbox-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    min-height: 25px;
    cursor: pointer;
}

.subjects-checkbox-wrap input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.subjects-custom-checkbox {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 19px;
    height: 19px;
    border: 1.5px solid #c7cbbf;
    border-radius: 5px;
    background: var(--sd-paper);
    transition: all 0.15s ease;
}

.subjects-checkbox-wrap input:checked + .subjects-custom-checkbox {
    border-color: var(--sd-forest);
    background: var(--sd-forest);
}

.subjects-checkbox-wrap input:checked + .subjects-custom-checkbox::after {
    content: "";
    width: 8px;
    height: 5px;
    margin-top: -2px;
    border-bottom: 2px solid #fffefa;
    border-left: 2px solid #fffefa;
    transform: rotate(-45deg);
}

.subjects-checkbox-wrap input:focus-visible + .subjects-custom-checkbox {
    outline: 3px solid rgba(82, 127, 112, 0.3);
    outline-offset: 3px;
}

/* SUBJECT NAME */

.subjects-name-cell {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 180px;
}

.subjects-book-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 39px;
    width: 39px;
    height: 39px;
    border: 1px solid #e0e4d8;
    border-radius: 11px;
    background: #f0f1e8;
    color: var(--sd-forest);
    font-size: 17px;
}

.subjects-name-cell > div:last-child {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.subjects-name-cell strong {
    color: var(--sd-ink);
    font-size: 12px;
    font-weight: 700;
}

.subjects-name-cell span {
    color: var(--sd-muted);
    font-size: 10px;
}

/* SUBJECT CODE */

.subjects-code {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border: 1px solid #e4e2d6;
    border-radius: 7px;
    background: #f7f6ef;
    color: #637063;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.subjects-dash {
    color: #a5a79b;
}

/* MARKS INPUTS */

.subjects-input-group {
    display: flex;
    align-items: stretch;
    width: 145px;
    max-width: 100%;
}

.subjects-input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 35px;
    width: 35px;
    border: 1px solid #dedfd3;
    border-right: 0;
    border-radius: 8px 0 0 8px;
    background: #f5f4ec;
    color: var(--sd-sage);
    font-size: 13px;
}

.subjects-number-input {
    display: block;
    width: 100%;
    min-width: 0;
    min-height: 39px;
    padding: 8px 9px;
    border: 1px solid #dedfd3;
    background: #fffefa;
    color: var(--sd-ink);
    font-size: 12px;
    font-weight: 700;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.subjects-input-group .subjects-number-input {
    border-radius: 0 8px 8px 0;
}

.subjects-number-input:focus {
    position: relative;
    z-index: 1;
    border-color: var(--sd-sage);
    box-shadow: 0 0 0 3px rgba(82, 127, 112, 0.11);
}

.subjects-input-group:focus-within .subjects-input-icon {
    border-color: var(--sd-sage);
    background: #edf2e9;
}

/* DURATION */

.subjects-duration-group {
    display: flex;
    align-items: stretch;
    width: 125px;
    max-width: 100%;
}

.subjects-duration-group .subjects-number-input {
    border-radius: 8px 0 0 8px;
}

.subjects-duration-group > span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 39px;
    padding: 0 7px;
    border: 1px solid #dedfd3;
    border-left: 0;
    border-radius: 0 8px 8px 0;
    background: #f5f4ec;
    color: var(--sd-muted);
    font-size: 10px;
    font-weight: 700;
}

/* CLASS EMPTY STATE */

.subjects-class-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 36px 20px;
}

.subjects-class-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 45px;
    width: 45px;
    height: 45px;
    border: 1px solid #e1e4d8;
    border-radius: 13px;
    background: #f1f1e8;
    color: #81927e;
    font-size: 19px;
}

.subjects-class-empty strong {
    color: var(--sd-ink);
    font-size: 12px;
}

.subjects-class-empty p {
    margin: 5px 0 0;
    color: var(--sd-muted);
    font-size: 11px;
    line-height: 1.7;
}

/* NO CLASSES EMPTY CARD */

.subjects-empty-card {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    min-height: 340px;
    padding: 40px 22px;
    border: 1px solid var(--sd-line);
    border-radius: 16px;
    background: var(--sd-paper);
    text-align: center;
    box-shadow: 0 4px 18px rgba(38, 59, 53, 0.03);
}

.subjects-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 78px;
    height: 78px;
    margin-bottom: 19px;
    border: 1px solid #e1e4d8;
    border-radius: 23px;
    background: #f1f1e8;
    color: #81927e;
    font-size: 31px;
}

.subjects-empty-card h2 {
    margin: 0 0 9px;
    color: var(--sd-ink);
    font-family: Georgia, serif;
    font-size: 22px;
    font-weight: 700;
}

.subjects-empty-card p {
    max-width: 480px;
    margin: 0 0 21px;
    color: var(--sd-muted);
    font-size: 12px;
    line-height: 1.8;
}

/* PRIMARY BUTTON */

.subjects-primary-btn,
.subjects-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 43px;
    padding: 11px 18px;
    border: 1px solid var(--sd-forest-dark);
    border-radius: 10px;
    background: linear-gradient(135deg, #344f43 0%, #20352c 100%);
    color: #fffefa;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(32, 53, 44, 0.11);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.subjects-primary-btn:hover,
.subjects-save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 16px rgba(32, 53, 44, 0.18);
    color: #fffefa;
}

/* SAVE PANEL */

.subjects-save-panel {
    position: sticky;
    bottom: 14px;
    z-index: 20;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 25px;
    padding: 17px 20px;
    border: 1px solid #d8ded2;
    border-radius: 15px;
    background: rgba(255, 254, 250, 0.97);
    box-shadow: 0 8px 26px rgba(38, 59, 53, 0.11);
    backdrop-filter: blur(8px);
}

.subjects-save-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.subjects-save-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    border: 1px solid #dce5d8;
    border-radius: 12px;
    background: #e9eee5;
    color: var(--sd-forest);
    font-size: 18px;
}

.subjects-save-info strong {
    display: block;
    margin-bottom: 4px;
    color: var(--sd-ink);
    font-size: 12px;
}

.subjects-save-info p {
    margin: 0;
    color: var(--sd-muted);
    font-size: 11px;
    line-height: 1.6;
}

.subjects-save-info p span {
    color: var(--sd-ink);
    font-weight: 700;
}

.subjects-save-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
}

.subjects-cancel-btn {
    min-height: 43px;
    background: var(--sd-paper);
}

.subjects-save-btn:disabled {
    opacity: 0.7;
    cursor: wait;
    transform: none;
}

/* FOOTNOTE */

.subjects-footnote {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 20px 10px 4px;
    color: #93988c;
    font-size: 10px;
    letter-spacing: 0.2px;
}

.subjects-footnote > i {
    color: var(--sd-sage);
    font-size: 13px;
}

.subjects-footnote-dot {
    padding: 0 3px;
    color: #b2b5a8;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .subjects-summary-grid {
        gap: 12px;
    }

    .subjects-summary-card {
        padding: 21px 15px 17px;
        gap: 11px;
    }

    .subjects-summary-icon {
        flex-basis: 40px;
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 767.98px) {
    .subjects-page {
        padding: 15px;
    }

    .subjects-header {
        align-items: flex-start;
    }

    .subjects-header-main {
        align-items: flex-start;
        gap: 9px;
    }

    .subjects-back-icon {
        flex-basis: 31px;
        width: 31px;
        height: 31px;
    }

    .subjects-page-icon {
        flex-basis: 42px;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        font-size: 19px;
    }

    .subjects-title-line h1 {
        font-size: 23px;
    }

    .subjects-heading-content > p {
        font-size: 11px;
    }

    .subjects-header > .subjects-secondary-btn {
        margin-left: 40px;
    }

    .subjects-breadcrumb {
        margin-bottom: 18px;
        font-size: 10px;
    }

    .subjects-information-card {
        padding: 15px;
    }

    .subjects-summary-grid {
        grid-template-columns: 1fr;
        gap: 11px;
        margin-bottom: 19px;
    }

    .subjects-summary-card {
        min-height: 96px;
        padding: 18px;
    }

    .subjects-class-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 17px;
    }

    .subjects-class-heading {
        align-items: flex-start;
    }

    .subjects-class-title-line h2 {
        font-size: 17px;
    }

    .subjects-select-all-btn {
        width: 100%;
    }

    .subjects-table {
        min-width: 920px;
    }

    .subjects-save-panel {
        position: static;
        align-items: stretch;
        flex-direction: column;
        padding: 16px;
    }

    .subjects-save-info {
        align-items: flex-start;
    }

    .subjects-save-actions {
        display: grid;
        grid-template-columns: 1fr;
        width: 100%;
    }

    .subjects-save-actions > * {
        width: 100%;
    }
}

@media (max-width: 420px) {
    .subjects-page {
        padding: 12px;
    }

    .subjects-title-line h1 {
        font-size: 21px;
    }

    .subjects-title-badge {
        font-size: 9px;
    }

    .subjects-header > .subjects-secondary-btn {
        margin-left: 0;
    }

    .subjects-alert {
        padding: 14px 40px 14px 12px;
        gap: 10px;
    }

    .subjects-information-card {
        gap: 10px;
        padding: 13px;
    }

    .subjects-information-icon {
        flex-basis: 35px;
        width: 35px;
        height: 35px;
        font-size: 15px;
    }

    .subjects-information-card h3 {
        line-height: 1.6;
    }

    .subjects-class-empty {
        align-items: flex-start;
        padding: 25px 15px;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .subjects-page *,
    .subjects-page *::before,
    .subjects-page *::after {
        scroll-behavior: auto !important;
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.subjects-page');

    if (!page) {
        return;
    }

    /* SELECT / UNSELECT ALL SUBJECTS FOR A CLASS */
    page.querySelectorAll('.subjects-select-all-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const classId = this.dataset.classId;

            const checkboxes = Array.from(
                page.querySelectorAll(
                    '.subject-checkbox[data-class-id="' + classId + '"]'
                )
            );

            if (!checkboxes.length) {
                return;
            }

            const shouldSelectAll = checkboxes.some(
                checkbox => !checkbox.checked
            );

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = shouldSelectAll;
                updateSubjectRow(checkbox);
            });

            this.setAttribute('aria-pressed', String(shouldSelectAll));

            this.innerHTML = shouldSelectAll
                ? '<i class="bi bi-x-circle"></i><span>Unselect All</span>'
                : '<i class="bi bi-check2-all"></i><span>Select All</span>';
        });
    });

    /* SUBJECT ROW VISUAL STATE */
    function updateSubjectRow(checkbox) {
        const row = checkbox.closest('.subject-row');

        if (row) {
            row.classList.toggle('subject-selected', checkbox.checked);
        }

        const classId = checkbox.dataset.classId;
        const classButton = page.querySelector(
            '.subjects-select-all-btn[data-class-id="' + classId + '"]'
        );

        if (!classButton) {
            return;
        }

        const classCheckboxes = Array.from(
            page.querySelectorAll(
                '.subject-checkbox[data-class-id="' + classId + '"]'
            )
        );

        const allChecked = classCheckboxes.length > 0 &&
            classCheckboxes.every(item => item.checked);

        classButton.setAttribute('aria-pressed', String(allChecked));

        classButton.innerHTML = allChecked
            ? '<i class="bi bi-x-circle"></i><span>Unselect All</span>'
            : '<i class="bi bi-check2-all"></i><span>Select All</span>';
    }

    page.querySelectorAll('.subject-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateSubjectRow(this);
        });

        updateSubjectRow(checkbox);
    });

    /* MAXIMUM MARKS / PASSING MARKS VALIDATION */
    page.querySelectorAll('.maximum-marks').forEach(function (maximumInput) {
        maximumInput.addEventListener('input', function () {
            const row = this.closest('.subject-row');

            if (!row) {
                return;
            }

            const passingInput = row.querySelector('.passing-marks');
            const maximum = Number(this.value);

            if (!passingInput) {
                return;
            }

            if (this.value !== '' && maximum > 0) {
                passingInput.max = String(maximum);

                if (
                    passingInput.value !== '' &&
                    Number(passingInput.value) > maximum
                ) {
                    passingInput.value = String(maximum);
                }
            } else {
                passingInput.removeAttribute('max');
            }
        });
    });

    /* INITIAL PASSING MARK LIMIT */
    page.querySelectorAll('.subject-row').forEach(function (row) {
        const maximumInput = row.querySelector('.maximum-marks');
        const passingInput = row.querySelector('.passing-marks');

        if (!maximumInput || !passingInput) {
            return;
        }

        const maximum = Number(maximumInput.value);

        if (maximum > 0) {
            passingInput.max = String(maximum);
        }
    });

    /* PREVENT PASSING MARKS FROM EXCEEDING MAXIMUM */
    page.querySelectorAll('.passing-marks').forEach(function (passingInput) {
        passingInput.addEventListener('input', function () {
            const row = this.closest('.subject-row');
            const maximumInput = row
                ? row.querySelector('.maximum-marks')
                : null;

            if (!maximumInput || this.value === '') {
                return;
            }

            const maximum = Number(maximumInput.value);

            if (maximum > 0 && Number(this.value) > maximum) {
                this.value = String(maximum);
            }
        });
    });

    /* SAVE BUTTON */
    const form = page.querySelector('#examSubjectsForm');
    const saveButton = page.querySelector('#saveSubjectsButton');

    if (form && saveButton) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                return;
            }

            saveButton.disabled = true;
            saveButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" ' +
                'aria-hidden="true"></span>' +
                '<span>Saving...</span>';
        });
    }
});
</script>

@endsection
