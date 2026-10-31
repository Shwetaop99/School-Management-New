```blade
{{-- resources/views/admin/results/marks.blade.php --}}

@extends('layouts.app')

@section('title', 'Enter Examination Marks')

@section('content')

@php
    $selectionMode = !(
        isset($selectedExam, $selectedClass, $selectedSection, $examSubject)
        && $selectedExam
        && $selectedClass
        && $selectedSection !== null
        && $examSubject
    );

    $internalMaximum = (float) ($examSubject->internal_max_marks ?? 0);
    $theoryMaximum = (float) ($examSubject->theory_max_marks ?? 0);
    $totalMaximum = (float) ($examSubject->maximum_marks ?? 0);
    $passingMarks = (float) ($examSubject->passing_marks ?? 0);

    $distributionValid = $totalMaximum > 0
        && ($internalMaximum + $theoryMaximum) == $totalMaximum;
@endphp

<div class="marks-page">

<style>
    .marks-page {
        --marks-gold: #c49a45;
        --marks-dark: #171717;
        --marks-muted: #727783;
        --marks-border: #e8eaf0;
        color: #242631;
        padding: 8px 0 30px;
    }

    .marks-page * {
        box-sizing: border-box;
    }

    .marks-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .marks-title {
        margin: 0;
        font-size: clamp(23px, 3vw, 30px);
        font-weight: 800;
        color: var(--marks-dark);
        letter-spacing: -.5px;
    }

    .marks-subtitle {
        color: var(--marks-muted);
        margin: 6px 0 0;
        font-size: 13px;
    }

    .marks-panel {
        background: #fff;
        border: 1px solid var(--marks-border);
        border-radius: 16px;
        box-shadow: 0 5px 22px rgba(24,31,45,.045);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .marks-panel-heading {
        padding: 18px 21px;
        border-bottom: 1px solid var(--marks-border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .marks-heading-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        color: #8a641c;
        background: #fbf3e3;
        font-size: 19px;
    }

    .marks-panel-heading h2 {
        font-size: 16px;
        font-weight: 750;
        margin: 0;
        color: var(--marks-dark);
    }

    .marks-panel-heading p {
        margin: 4px 0 0;
        font-size: 12px;
        color: var(--marks-muted);
    }

    .marks-panel-body {
        padding: 21px;
    }

    .marks-filters {
        display: grid;
        grid-template-columns: repeat(4,minmax(0,1fr));
        gap: 16px;
    }

    .marks-field label {
        display: block;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 750;
        color: #444957;
    }

    .marks-field label .required {
        color: #d64545;
    }

    .marks-control {
        display: block;
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #dfe2e9;
        border-radius: 10px;
        background: #fff;
        color: #242631;
        font-size: 13px;
        outline: none;
    }

    .marks-control:focus {
        border-color: var(--marks-gold);
        box-shadow: 0 0 0 3px rgba(196,154,69,.14);
    }

    .marks-control:disabled {
        background: #f5f6f8;
        color: #8b909a;
        cursor: not-allowed;
    }

    .marks-help {
        display: block;
        margin-top: 6px;
        color: #858a95;
        font-size: 11px;
    }

    .marks-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 17px;
    }

    .marks-toolbar h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: var(--marks-dark);
    }

    .marks-toolbar p {
        margin: 5px 0 0;
        color: var(--marks-muted);
        font-size: 12px;
    }

    .marks-button {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        border: 0;
        border-radius: 10px;
        padding: 11px 17px;
        min-height: 42px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        transition: .2s ease;
    }

    .marks-button-primary {
        background: var(--marks-dark);
        color: #fff;
    }

    .marks-button-primary:hover {
        background: #303030;
        color: #fff;
    }

    .marks-button-gold {
        background: var(--marks-gold);
        color: #171717;
    }

    .marks-button-gold:hover {
        background: #d4ad5f;
        color: #171717;
    }

    .marks-button:disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    .marks-stats {
        display: grid;
        grid-template-columns: repeat(4,minmax(0,1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .marks-stat {
        border: 1px solid var(--marks-border);
        background: #fff;
        padding: 15px 17px;
        border-radius: 12px;
    }

    .marks-stat-label {
        font-size: 11px;
        color: var(--marks-muted);
        margin-bottom: 6px;
    }

    .marks-stat-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--marks-dark);
    }

    .marks-distribution {
        display: grid;
        grid-template-columns: repeat(4,minmax(0,1fr));
        gap: 12px;
    }

    .marks-distribution-item {
        border: 1px solid var(--marks-border);
        border-radius: 12px;
        padding: 16px;
        background: #fff;
    }

    .marks-distribution-label {
        color: var(--marks-muted);
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .marks-distribution-value {
        font-size: 24px;
        font-weight: 850;
        color: var(--marks-dark);
    }

    .marks-distribution-item small {
        display: block;
        margin-top: 5px;
        color: var(--marks-muted);
        font-size: 11px;
    }

    .marks-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid var(--marks-border);
        border-radius: 12px;
    }

    .marks-table {
        width: 100%;
        min-width: 950px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .marks-table th {
        background: #f7f8fa;
        color: #555b68;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .35px;
        white-space: nowrap;
        padding: 13px 12px;
        border-bottom: 1px solid var(--marks-border);
        text-align: left;
    }

    .marks-table td {
        padding: 11px 12px;
        border-bottom: 1px solid #eff0f4;
        vertical-align: middle;
        font-size: 12px;
    }

    .marks-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .marks-table tbody tr:hover {
        background: #fdfbf7;
    }

    .marks-student-name {
        font-size: 13px;
        font-weight: 750;
        color: #292d37;
    }

    .marks-student-id {
        font-size: 11px;
        color: var(--marks-muted);
        margin-top: 4px;
    }

    .marks-number {
        width: 90px;
        min-width: 75px;
        padding: 9px 8px;
        border: 1px solid #dfe2e9;
        border-radius: 8px;
        outline: none;
        font-size: 12px;
    }

    .marks-number:focus {
        border-color: var(--marks-gold);
        box-shadow: 0 0 0 2px rgba(196,154,69,.13);
    }

    .marks-number:disabled {
        background: #f2f3f5;
        cursor: not-allowed;
    }

    .marks-status {
        min-width: 110px;
        padding: 9px 7px;
        border: 1px solid #dfe2e9;
        border-radius: 8px;
        background: #fff;
        font-size: 12px;
    }

    .marks-total {
        font-weight: 800;
        color: #282c35;
        white-space: nowrap;
    }

    .marks-alert {
        padding: 13px 15px;
        border-radius: 10px;
        margin-bottom: 17px;
        font-size: 13px;
    }

    .marks-empty {
        padding: 42px 20px;
        text-align: center;
        color: var(--marks-muted);
    }

    .marks-empty-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        margin: 0 auto 13px;
        border-radius: 15px;
        background: #fbf3e3;
        color: #8a641c;
        font-size: 23px;
    }

    .marks-empty h3 {
        color: var(--marks-dark);
        font-size: 16px;
        font-weight: 800;
        margin: 0 0 7px;
    }

    .marks-empty p {
        margin: 0;
        font-size: 12px;
    }

    .marks-loading {
        display: none;
        font-size: 12px;
        color: #8a641c;
        margin-top: 8px;
    }

    .marks-loading.show {
        display: block;
    }

    .marks-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .marks-info-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 7px;
        padding: 5px 8px;
        background: #f3f4f6;
        color: #555b68;
        font-size: 11px;
        font-weight: 700;
        margin-right: 4px;
    }

    .marks-total-warning {
        background: #fff0f0 !important;
    }

    @media(max-width:1000px) {
        .marks-filters,
        .marks-stats,
        .marks-distribution {
            grid-template-columns: repeat(2,minmax(0,1fr));
        }
    }

    @media(max-width:600px) {
        .marks-panel-heading,
        .marks-panel-body {
            padding: 15px;
        }

        .marks-filters,
        .marks-stats,
        .marks-distribution {
            grid-template-columns: 1fr;
        }

        .marks-header {
            align-items: flex-start;
        }

        .marks-footer .marks-button {
            width: 100%;
        }
    }
</style>

{{-- PAGE HEADER --}}
<div class="marks-header">
    <div>
        <h1 class="marks-title">Enter Examination Marks</h1>
        <p class="marks-subtitle">
            Select an examination, class, section and subject to record student marks.
        </p>
    </div>

    <a href="{{ route('admin.results.index') }}"
       class="marks-button marks-button-primary">
        <i class="bi bi-arrow-left"></i>
        Back to Results
    </a>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div class="alert alert-success marks-alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger marks-alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger marks-alert">
        <strong>Please correct the following:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- SELECTION PANEL --}}
<div class="marks-panel">
    <div class="marks-panel-heading">
        <div class="marks-heading-icon">
            <i class="bi bi-funnel"></i>
        </div>
        <div>
            <h2>Marks Selection</h2>
            <p>Choose the examination and class details before entering marks.</p>
        </div>
    </div>

    <div class="marks-panel-body">

        <form id="marksSelectionForm"
              method="GET"
              action="{{ route('admin.results.marks') }}">

            <div class="marks-filters">

                {{-- EXAM --}}
                <div class="marks-field">
                    <label for="exam_id">
                        Examination <span class="required">*</span>
                    </label>

                    <select id="exam_id"
                            name="exam_id"
                            class="marks-control"
                            required>

                        <option value="">Select examination</option>

                        @foreach($exams as $examOption)
                            <option value="{{ $examOption->id }}"
                                @selected((string)($selectedExam?->id ?? request('exam_id')) === (string)$examOption->id)>
                                {{ $examOption->exam_name }}
                                @if($examOption->academic_year)
                                    — {{ $examOption->academic_year }}
                                @endif
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- CLASS --}}
                <div class="marks-field">
                    <label for="class_id">
                        Class <span class="required">*</span>
                    </label>

                    <select id="class_id"
                            name="class_id"
                            class="marks-control"
                            data-initial="{{ request('class_id') }}"
                            required
                            {{ !$selectedExam ? 'disabled' : '' }}>

                        <option value="">Select class</option>

                        @foreach($classes as $classOption)
                            <option value="{{ $classOption->id }}"
                                @selected((string)($selectedClass?->id ?? request('class_id')) === (string)$classOption->id)>
                                {{ $classOption->class_name }}
                            </option>
                        @endforeach

                    </select>

                    <small class="marks-help">
                        Only classes assigned to this examination are listed.
                    </small>
                </div>

                {{-- SECTION --}}
                <div class="marks-field">
                    <label for="section">
                        Section <span class="required">*</span>
                    </label>

                    <select id="section"
                            name="section"
                            class="marks-control"
                            data-initial="{{ request('section') }}"
                            required
                            {{ !$selectedClass ? 'disabled' : '' }}>

                        <option value="">Select section</option>

                        @foreach($sections as $sectionOption)
                            <option value="{{ $sectionOption }}"
                                @selected((string)($selectedSection ?? request('section')) === (string)$sectionOption)>
                                {{ $sectionOption }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- SUBJECT --}}
                <div class="marks-field">
                    <label for="subject_id">
                        Subject <span class="required">*</span>
                    </label>

                    <select id="subject_id"
                            name="subject_id"
                            class="marks-control"
                            data-initial="{{ request('subject_id') }}"
                            required
                            {{ !$selectedClass ? 'disabled' : '' }}>

                        <option value="">Select subject</option>

                        @foreach($subjects as $subjectOption)
                            <option value="{{ $subjectOption->subject_id }}"
                                @selected((string)($selectedSubject?->id ?? request('subject_id')) === (string)$subjectOption->subject_id)>

                                {{ $subjectOption->subject->subject_name ?? 'Unnamed Subject' }}
                                — Max {{ $subjectOption->maximum_marks }}

                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div id="selectionLoading"
                 class="marks-loading"
                 role="status">

                <i class="bi bi-arrow-repeat"></i>
                Loading selection options…

            </div>

            <div class="marks-footer">

                <button type="button"
                        id="resetSelection"
                        class="marks-button marks-button-primary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </button>

                <button type="submit"
                        class="marks-button marks-button-gold">
                    <i class="bi bi-people"></i>
                    Load Students
                </button>

            </div>

        </form>

    </div>
</div>

{{-- MARKS ENTRY --}}
@if(!$selectionMode && $examSubject)

    {{-- DISTRIBUTION SUMMARY --}}
    <div class="marks-panel">

        <div class="marks-panel-heading">
            <div class="marks-heading-icon">
                <i class="bi bi-calculator"></i>
            </div>

            <div>
                <h2>Marks Distribution</h2>
                <p>Configured maximum and passing marks for this subject.</p>
            </div>
        </div>

        <div class="marks-panel-body">

            <div class="marks-distribution">

                <div class="marks-distribution-item">
                    <div class="marks-distribution-label">
                        Internal Maximum
                    </div>
                    <div class="marks-distribution-value">
                        {{ $internalMaximum }}
                    </div>
                    <small>Internal assessment</small>
                </div>

                <div class="marks-distribution-item">
                    <div class="marks-distribution-label">
                        Theory Maximum
                    </div>
                    <div class="marks-distribution-value">
                        {{ $theoryMaximum }}
                    </div>
                    <small>Theory examination</small>
                </div>

                <div class="marks-distribution-item">
                    <div class="marks-distribution-label">
                        Total Maximum
                    </div>
                    <div class="marks-distribution-value">
                        {{ $totalMaximum }}
                    </div>
                    <small>Internal + Theory</small>
                </div>

                <div class="marks-distribution-item">
                    <div class="marks-distribution-label">
                        Passing Marks
                    </div>
                    <div class="marks-distribution-value">
                        {{ $passingMarks }}
                    </div>
                    <small>Minimum required marks</small>
                </div>

            </div>

            @if(!$distributionValid)
                <div class="alert alert-danger marks-alert mt-3 mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Internal maximum ({{ $internalMaximum }}) plus Theory maximum
                    ({{ $theoryMaximum }}) must equal Total maximum
                    ({{ $totalMaximum }}). Configure the distribution before saving marks.
                </div>
            @endif

        </div>

    </div>

    {{-- STUDENT SUMMARY --}}
    <div class="marks-stats">

        <div class="marks-stat">
            <div class="marks-stat-label">Students</div>
            <div class="marks-stat-value">
                {{ $students->count() }}
            </div>
        </div>

        <div class="marks-stat">
            <div class="marks-stat-label">Internal Marks</div>
            <div class="marks-stat-value">
                {{ $internalMaximum }}
            </div>
        </div>

        <div class="marks-stat">
            <div class="marks-stat-label">Theory Marks</div>
            <div class="marks-stat-value">
                {{ $theoryMaximum }}
            </div>
        </div>

        <div class="marks-stat">
            <div class="marks-stat-label">Total Maximum</div>
            <div class="marks-stat-value">
                {{ $totalMaximum }}
            </div>
        </div>

    </div>

    {{-- STUDENT MARKS PANEL --}}
    <div class="marks-panel">

        <div class="marks-panel-heading">

            <div class="marks-heading-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h2>Student Marks Entry</h2>

                <p>
                    {{ $selectedExam->exam_name }}

                    <span class="marks-info-chip">
                        {{ $selectedClass->class_name }}
                    </span>

                    <span class="marks-info-chip">
                        Section {{ $selectedSection }}
                    </span>

                    <span class="marks-info-chip">
                        {{ $selectedSubject->subject_name ?? 'Subject' }}
                    </span>
                </p>
            </div>

        </div>

        <div class="marks-panel-body">

            @if($students->isEmpty())

                <div class="marks-empty">

                    <div class="marks-empty-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <h3>No students found</h3>

                    <p>
                        Check the selected class, section and academic year.
                    </p>

                </div>

            @elseif(!$distributionValid)

                <div class="marks-empty">

                    <div class="marks-empty-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>

                    <h3>Marks distribution is incomplete</h3>

                    <p>
                        Correct the subject distribution before entering student marks.
                    </p>

                </div>

            @else

                <form method="POST"
                      action="{{ route('admin.results.save-marks') }}"
                      id="saveMarksForm">

                    @csrf

                    <input type="hidden"
                           name="exam_id"
                           value="{{ $selectedExam->id }}">

                    <input type="hidden"
                           name="class_id"
                           value="{{ $selectedClass->id }}">

                    <input type="hidden"
                           name="section"
                           value="{{ $selectedSection }}">

                    <input type="hidden"
                           name="subject_id"
                           value="{{ $examSubject->subject_id }}">

                    <div class="marks-toolbar">

                        <div>
                            <h3>Marks Register</h3>
                            <p>
                                Enter Internal and Theory marks separately.
                                Total calculates automatically.
                            </p>
                        </div>

                        <span class="marks-info-chip">
                            Maximum: {{ $totalMaximum }}
                        </span>

                    </div>

                    <div class="marks-table-wrap">

                        <table class="marks-table">

                            <thead>
                                <tr>
                                    <th style="width:55px">#</th>
                                    <th>Student</th>
                                    <th>Roll No.</th>
                                    <th>Internal / {{ $internalMaximum }}</th>
                                    <th>Theory / {{ $theoryMaximum }}</th>
                                    <th>Total / {{ $totalMaximum }}</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($students as $student)

                                    @php
                                        $savedMark = $existingMarks->get($student->id);

                                        $studentFullName = $student->full_name
                                            ?? trim(collect([
                                                $student->first_name ?? '',
                                                $student->middle_name ?? '',
                                                $student->last_name ?? '',
                                            ])->filter()->implode(' '));

                                        $markStatus = old(
                                            "marks.{$student->id}.status",
                                            $savedMark->status ?? 'present'
                                        );

                                        $internal = old(
                                            "marks.{$student->id}.internal_marks",
                                            $savedMark->internal_marks ?? ''
                                        );

                                        $theory = old(
                                            "marks.{$student->id}.theory_marks",
                                            $savedMark->theory_marks ?? ''
                                        );

                                        $remarks = old(
                                            "marks.{$student->id}.remarks",
                                            $savedMark->remarks ?? ''
                                        );
                                    @endphp

                                    <tr class="student-mark-row"
                                        data-internal-max="{{ $internalMaximum }}"
                                        data-theory-max="{{ $theoryMaximum }}"
                                        data-max="{{ $totalMaximum }}">

                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <div class="marks-student-name">
                                                {{ $studentFullName ?: 'Unnamed Student' }}
                                            </div>

                                            <div class="marks-student-id">
                                                {{ $student->student_id }}
                                            </div>
                                        </td>

                                        <td>
                                            {{ $student->roll_number ?: '—' }}
                                        </td>

                                        {{-- INTERNAL --}}
                                        <td>
                                            <input type="number"
                                                   class="marks-number mark-input internal-input"
                                                   name="marks[{{ $student->id }}][internal_marks]"
                                                   value="{{ $internal }}"
                                                   min="0"
                                                   max="{{ $internalMaximum }}"
                                                   step="0.01"
                                                   required
                                                   aria-label="Internal marks for {{ $studentFullName }}">
                                        </td>

                                        {{-- THEORY --}}
                                        <td>
                                            <input type="number"
                                                   class="marks-number mark-input theory-input"
                                                   name="marks[{{ $student->id }}][theory_marks]"
                                                   value="{{ $theory }}"
                                                   min="0"
                                                   max="{{ $theoryMaximum }}"
                                                   step="0.01"
                                                   required
                                                   aria-label="Theory marks for {{ $studentFullName }}">
                                        </td>

                                        {{-- TOTAL --}}
                                        <td>
                                            <span class="marks-total js-row-total">
                                                {{ number_format((float)($savedMark->total_marks ?? 0), 2) }}
                                            </span>
                                        </td>

                                        {{-- STATUS --}}
                                        <td>
                                            <select class="marks-status mark-status"
                                                    name="marks[{{ $student->id }}][status]">

                                                <option value="present"
                                                    @selected($markStatus === 'present')>
                                                    Present
                                                </option>

                                                <option value="absent"
                                                    @selected($markStatus === 'absent')>
                                                    Absent
                                                </option>

                                                <option value="na"
                                                    @selected($markStatus === 'na')>
                                                    Not Applicable
                                                </option>

                                            </select>
                                        </td>

                                        {{-- REMARKS --}}
                                        <td>
                                            <input type="text"
                                                   class="marks-number"
                                                   style="width:125px"
                                                   name="marks[{{ $student->id }}][remarks]"
                                                   value="{{ $remarks }}"
                                                   maxlength="255"
                                                   placeholder="Optional">
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="marks-footer">

                        <button type="button"
                                id="clearMarks"
                                class="marks-button marks-button-primary">

                            <i class="bi bi-eraser"></i>
                            Clear Unsaved Inputs

                        </button>

                        <button type="submit"
                                class="marks-button marks-button-gold"
                                id="saveMarksButton">

                            <i class="bi bi-check2-circle"></i>
                            Save Marks

                        </button>

                    </div>

                </form>

            @endif

        </div>

    </div>

@else

    <div class="marks-panel">

        <div class="marks-empty">

            <div class="marks-empty-icon">
                <i class="bi bi-journal-check"></i>
            </div>

            <h3>Select the exam details first</h3>

            <p>
                Choose an examination, class, section and subject,
                then click Load Students.
            </p>

        </div>

    </div>

@endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    'use strict';

    const examSelect = document.getElementById('exam_id');
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section');
    const subjectSelect = document.getElementById('subject_id');
    const loading = document.getElementById('selectionLoading');

    const urls = {
        classes: @json(route('admin.results.load-classes')),
        sections: @json(route('admin.results.load-sections')),
        subjects: @json(route('admin.results.load-subjects'))
    };

    const requestVersion = {
        classes: 0,
        sections: 0,
        subjects: 0
    };

    function setLoading(show) {
        loading?.classList.toggle('show', show);
    }

    function resetSelect(select, placeholder, disabled = true) {

        if (!select) return;

        select.replaceChildren(new Option(placeholder, ''));
        select.disabled = disabled;
    }

    function addOption(select, value, label, selected = false) {

        const option = new Option(
            String(label ?? ''),
            String(value ?? '')
        );

        option.selected = selected;
        select.add(option);
    }

    function showError(error) {

        console.error('Marks selection error:', error);

        alert(
            error.message ||
            'Unable to load selection options. Please try again.'
        );
    }

    async function getJson(url, params) {

        const query = new URLSearchParams();

        Object.entries(params).forEach(([key, value]) => {

            if (
                value !== undefined &&
                value !== null &&
                value !== ''
            ) {
                query.set(key, value);
            }

        });

        const response = await fetch(
            `${url}?${query.toString()}`,
            {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            }
        );

        const rawText = await response.text();

        const cleanText = rawText.replace(/^[\s\uFEFF]+/, '');

        let data;

        try {

            data = JSON.parse(cleanText);

        } catch (error) {

            console.error('Invalid JSON response:', {
                url: response.url,
                status: response.status,
                contentType: response.headers.get('content-type'),
                response: rawText
            });

            throw new Error(
                'Invalid server response. Check the Network tab for the request.'
            );

        }

        if (!response.ok || data.success === false) {

            throw new Error(
                data.message || `Request failed (${response.status}).`
            );

        }

        return data;
    }

    function getItemId(item) {

        return item?.id
            ?? item?.class_id
            ?? item?.subject_id
            ?? '';
    }

    function getClassName(item) {

        return item?.name
            ?? item?.class_name
            ?? item?.school_class?.class_name
            ?? item?.schoolClass?.class_name
            ?? '';
    }

    function getSectionValue(item) {

        return typeof item === 'object'
            ? (item.section ?? item.name ?? item.id ?? '')
            : item;
    }

    function getSubjectName(item) {

        return item?.name
            ?? item?.subject_name
            ?? item?.subject?.subject_name
            ?? item?.subject?.name
            ?? 'Unnamed subject';
    }

    async function loadClasses(selectedClass = '') {

        const version = ++requestVersion.classes;

        requestVersion.sections++;
        requestVersion.subjects++;

        resetSelect(classSelect, 'Select class');
        resetSelect(sectionSelect, 'Select section');
        resetSelect(subjectSelect, 'Select subject');

        if (!examSelect?.value) return;

        setLoading(true);

        try {

            const data = await getJson(urls.classes, {
                exam_id: examSelect.value
            });

            if (version !== requestVersion.classes) return;

            if (!Array.isArray(data.classes)) {
                throw new Error(
                    data.message || 'Classes array is missing from the response.'
                );
            }

            const seen = new Set();

            data.classes.forEach(item => {

                const id = getItemId(item);
                const name = getClassName(item);

                if (id === '' || !name) return;

                const key = String(id);

                if (seen.has(key)) return;

                seen.add(key);

                const section =
                    item.section ??
                    item.school_class?.section ??
                    item.schoolClass?.section ??
                    '';

                const label = section
                    ? `${name} — Section ${section}`
                    : name;

                addOption(
                    classSelect,
                    id,
                    label,
                    String(id) === String(selectedClass)
                );

            });

            classSelect.disabled = false;

            if (selectedClass && classSelect.value) {

                await loadSections(
                    classSelect.value,
                    sectionSelect.dataset.initial || ''
                );

            }

        } catch (error) {

            if (version === requestVersion.classes) {
                showError(error);
            }

        } finally {

            if (version === requestVersion.classes) {
                setLoading(false);
            }

        }
    }

    async function loadSections(classId, selectedSection = '') {

        const version = ++requestVersion.sections;

        requestVersion.subjects++;

        resetSelect(sectionSelect, 'Select section');
        resetSelect(subjectSelect, 'Select subject');

        if (!examSelect?.value || !classId) return;

        setLoading(true);

        try {

            const data = await getJson(urls.sections, {
                exam_id: examSelect.value,
                class_id: classId
            });

            if (version !== requestVersion.sections) return;

            if (!Array.isArray(data.sections)) {
                throw new Error(
                    data.message || 'Sections array is missing from the response.'
                );
            }

            const seen = new Set();

            data.sections.forEach(item => {

                const value = getSectionValue(item);

                if (
                    value === '' ||
                    value === null ||
                    value === undefined
                ) {
                    return;
                }

                const key = String(value);

                if (seen.has(key)) return;

                seen.add(key);

                const label = typeof item === 'object'
                    ? (item.name ?? item.section ?? item.label ?? value)
                    : value;

                addOption(
                    sectionSelect,
                    value,
                    label,
                    String(value) === String(selectedSection)
                );

            });

            sectionSelect.disabled = false;

            if (selectedSection && sectionSelect.value) {

                await loadSubjects(
                    classId,
                    subjectSelect.dataset.initial || '',
                    sectionSelect.value
                );

            }

        } catch (error) {

            if (version === requestVersion.sections) {
                showError(error);
            }

        } finally {

            if (version === requestVersion.sections) {
                setLoading(false);
            }

        }
    }

    async function loadSubjects(
        classId,
        selectedSubject = '',
        section = ''
    ) {

        const version = ++requestVersion.subjects;

        resetSelect(subjectSelect, 'Select subject');

        if (!examSelect?.value || !classId || !section) return;

        setLoading(true);

        try {

            const data = await getJson(urls.subjects, {
                exam_id: examSelect.value,
                class_id: classId,
                section: section
            });

            if (version !== requestVersion.subjects) return;

            if (!Array.isArray(data.subjects)) {
                throw new Error(
                    data.message || 'Subjects array is missing from the response.'
                );
            }

            const seen = new Set();

            data.subjects.forEach(item => {

                const id = getItemId(item);

                if (id === '') return;

                const key = String(id);

                if (seen.has(key)) return;

                seen.add(key);

                let label = getSubjectName(item);

                const code =
                    item.code ??
                    item.subject_code ??
                    item.subject?.subject_code;

                const internal = item.internal_max_marks;
                const theory = item.theory_max_marks;
                const maximum = item.maximum_marks;

                if (code) {
                    label += ` (${code})`;
                }

                if (
                    internal !== undefined &&
                    theory !== undefined
                ) {
                    label += ` — Internal ${internal} + Theory ${theory}`;
                }

                if (maximum !== undefined) {
                    label += ` = ${maximum}`;
                }

                addOption(
                    subjectSelect,
                    id,
                    label,
                    String(id) === String(selectedSubject)
                );

            });

            subjectSelect.disabled = false;

        } catch (error) {

            if (version === requestVersion.subjects) {
                showError(error);
            }

        } finally {

            if (version === requestVersion.subjects) {
                setLoading(false);
            }

        }
    }

    // Examination changed.
    examSelect?.addEventListener('change', function () {

        classSelect.dataset.initial = '';
        sectionSelect.dataset.initial = '';
        subjectSelect.dataset.initial = '';

        loadClasses('');
    });

    // Class changed.
    classSelect?.addEventListener('change', function () {

        sectionSelect.dataset.initial = '';
        subjectSelect.dataset.initial = '';

        loadSections(this.value, '');
    });

    // Section changed.
    sectionSelect?.addEventListener('change', function () {

        subjectSelect.dataset.initial = '';

        if (classSelect.value && this.value) {

            loadSubjects(
                classSelect.value,
                '',
                this.value
            );

        } else {

            requestVersion.subjects++;

            resetSelect(subjectSelect, 'Select subject');

        }

    });

    // Reset selection.
    document.getElementById('resetSelection')
        ?.addEventListener('click', function () {

            window.location.href = @json(route('admin.results.marks'));

        });

    // Restore dropdowns after page reload.
    if (examSelect?.value && classSelect) {

        const initialClass =
            classSelect.dataset.initial || classSelect.value;

        const initialSection =
            sectionSelect.dataset.initial || '';

        const initialSubject =
            subjectSelect.dataset.initial || '';

        sectionSelect.dataset.initial = initialSection;
        subjectSelect.dataset.initial = initialSubject;

        loadClasses(initialClass);
    }

    // Calculate student totals.
    function updateRow(row) {

        const status = row.querySelector('.mark-status')?.value;

        const internalInput = row.querySelector('.internal-input');
        const theoryInput = row.querySelector('.theory-input');
        const totalElement = row.querySelector('.js-row-total');

        const internalMaximum = Number(
            row.dataset.internalMax || 0
        );

        const theoryMaximum = Number(
            row.dataset.theoryMax || 0
        );

        const totalMaximum = Number(
            row.dataset.max || 0
        );

        if (status !== 'present') {

            internalInput.value = '';
            theoryInput.value = '';

            internalInput.disabled = true;
            theoryInput.disabled = true;

            internalInput.setCustomValidity('');
            theoryInput.setCustomValidity('');

            if (totalElement) {
                totalElement.textContent = '0.00';
                totalElement.style.color = '';
            }

            return;
        }

        internalInput.disabled = false;
        theoryInput.disabled = false;

        const internal = Number(internalInput.value || 0);
        const theory = Number(theoryInput.value || 0);

        const total = internal + theory;

        if (totalElement) {

            totalElement.textContent = total.toFixed(2);

            totalElement.style.color =
                total > totalMaximum ? '#c62828' : '';

        }

        internalInput.setCustomValidity(
            internal > internalMaximum
                ? `Maximum internal marks are ${internalMaximum}.`
                : ''
        );

        theoryInput.setCustomValidity(
            theory > theoryMaximum
                ? `Maximum theory marks are ${theoryMaximum}.`
                : ''
        );

        row.classList.toggle(
            'marks-total-warning',
            total > totalMaximum
        );
    }

    // Initialize all student rows.
    document.querySelectorAll('.student-mark-row').forEach(row => {

        row.querySelectorAll('.mark-input').forEach(input => {

            input.addEventListener('input', function () {
                updateRow(row);
            });

        });

        row.querySelector('.mark-status')
            ?.addEventListener('change', function () {
                updateRow(row);
            });

        updateRow(row);

    });

    // Clear unsaved marks.
    document.getElementById('clearMarks')
        ?.addEventListener('click', function () {

            if (!confirm(
                'Clear entered marks and remarks from this form?'
            )) {
                return;
            }

            document.querySelectorAll('.student-mark-row')
                .forEach(row => {

                    row.querySelectorAll('.mark-input')
                        .forEach(input => {

                            input.value = '';
                            input.setCustomValidity('');

                        });

                    const remarks = row.querySelector(
                        'input[name$="[remarks]"]'
                    );

                    if (remarks) {
                        remarks.value = '';
                    }

                    const status = row.querySelector('.mark-status');

                    if (status) {
                        status.value = 'present';
                    }

                    updateRow(row);

                });

        });

    // Validate before saving.
    document.getElementById('saveMarksForm')
        ?.addEventListener('submit', function (event) {

            let invalid = false;
            let firstInvalid = null;

            document.querySelectorAll('.student-mark-row')
                .forEach(row => {

                    updateRow(row);

                    row.style.background = '';

                    const status = row.querySelector('.mark-status')?.value;

                    if (status !== 'present') {
                        return;
                    }

                    const internalInput = row.querySelector('.internal-input');
                    const theoryInput = row.querySelector('.theory-input');

                    const internalMaximum = Number(
                        row.dataset.internalMax || 0
                    );

                    const theoryMaximum = Number(
                        row.dataset.theoryMax || 0
                    );

                    const internal = Number(internalInput.value);
                    const theory = Number(theoryInput.value);

                    const internalInvalid =
                        internalInput.value.trim() === '' ||
                        !Number.isFinite(internal) ||
                        internal < 0 ||
                        internal > internalMaximum;

                    const theoryInvalid =
                        theoryInput.value.trim() === '' ||
                        !Number.isFinite(theory) ||
                        theory < 0 ||
                        theory > theoryMaximum;

                    if (internalInvalid || theoryInvalid) {

                        invalid = true;

                        firstInvalid ||= internalInvalid
                            ? internalInput
                            : theoryInput;

                        row.style.background = '#fff0f0';

                    }

                });

            if (invalid) {

                event.preventDefault();

                alert(
                    'Please check the marks. Internal and Theory marks are required and cannot exceed their individual maximum marks.'
                );

                firstInvalid?.focus();

            }

        });

});
</script>

@endsection
```