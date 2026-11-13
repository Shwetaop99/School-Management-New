@extends('layouts.app')

@section('title', 'Edit Scholarship Examination | Admin')
@section('page-title', 'Edit Scholarship Examination')

@section('content')

<style>
.scholarship-edit-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --muted: #778078;
    --text: #37443d;
    --line: #e5dfd0;
    --paper: #ffffff;
    --cream: #f8f6ef;
    --green: #3a9d6b;
    --amber: #d58a0b;
    --red: #c94a36;
    --blue: #2b5d8a;
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    padding: 26px;
    color: var(--text);
    background:
        radial-gradient(circle at 8% 4%, rgba(58,157,107,.045), transparent 25%),
        radial-gradient(circle at 95% 16%, rgba(212,138,11,.045), transparent 25%),
        var(--cream);
    font-family: "Public Sans", "Segoe UI", sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

.scholarship-edit-page *,
.scholarship-edit-page *::before,
.scholarship-edit-page *::after {
    box-sizing: border-box;
}

.scholarship-edit-page a {
    text-decoration: none;
}

.scholarship-edit-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 25px 28px 25px 42px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 10px;
    background:
        radial-gradient(circle at 95% 15%, rgba(58,157,107,.07), transparent 35%),
        #fff;
    box-shadow: 0 4px 16px rgba(38,59,53,.035);
}

.scholarship-edit-header::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 22px;
    width: 2px;
    background: rgba(228,87,46,.4);
}

.scholarship-edit-heading {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.scholarship-edit-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    flex: 0 0 56px;
    border: 1px solid #dce7d5;
    border-radius: 12px;
    color: var(--green);
    background: #edf5e9;
    font-size: 22px;
}

.scholarship-edit-title {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 27px;
    font-weight: 700;
    letter-spacing: -.4px;
    line-height: 1.3;
}

.scholarship-edit-subtitle {
    margin: 7px 0 0;
    color: var(--muted);
    font-size: 13px;
}

.scholarship-edit-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.scholarship-edit-page .btn-edit-header {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    transition: transform .2s ease, background .2s ease;
}

.scholarship-edit-page .btn-edit-header:hover {
    transform: translateY(-2px);
}

.scholarship-edit-page .btn-edit-back {
    color: var(--ink);
    background: #fff;
    border-color: #d8d2c4;
}

.scholarship-edit-page .btn-edit-back:hover {
    background: #f8f6ef;
}

.scholarship-edit-page .btn-edit-save {
    color: #fff;
    background: var(--ink);
    border-color: var(--ink);
    cursor: pointer;
}

.scholarship-edit-page .btn-edit-save:hover {
    background: var(--ink-dark);
}

.scholarship-edit-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 20px;
    align-items: start;
}

.scholarship-edit-main,
.scholarship-edit-sidebar {
    min-width: 0;
}

.scholarship-edit-card {
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38,59,53,.035);
}

.scholarship-edit-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: #fff;
}

.scholarship-edit-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    border: 1px solid #dce7d5;
    border-radius: 10px;
    color: var(--green);
    background: #edf5e9;
}

.scholarship-edit-card-icon.orange {
    color: var(--amber);
    background: #fff5df;
    border-color: #f0e1c2;
}

.scholarship-edit-card-icon.blue {
    color: var(--blue);
    background: #eff4fa;
    border-color: #dbe3ed;
}

.scholarship-edit-card-title {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.scholarship-edit-card-subtitle {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 11.5px;
}

.scholarship-edit-card-body {
    padding: 22px;
}

.edit-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 19px;
}

.edit-field {
    min-width: 0;
}

.edit-field.full-width {
    grid-column: 1 / -1;
}

.edit-label {
    display: block;
    margin-bottom: 7px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 750;
}

.required-mark {
    color: var(--red);
}

.edit-control {
    display: block;
    width: 100%;
    min-height: 44px;
    padding: 10px 12px;
    border: 1px solid #deded3;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fff;
    font: inherit;
    font-size: 13px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.edit-control:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 3px rgba(58,157,107,.10);
}

textarea.edit-control {
    min-height: 115px;
    resize: vertical;
}

.edit-help {
    display: block;
    margin-top: 6px;
    color: var(--muted);
    font-size: 11px;
}

.edit-error {
    display: block;
    margin-top: 5px;
    color: #bd302b;
    font-size: 11px;
    font-weight: 600;
}

.edit-control.is-invalid {
    border-color: #c94a36;
}

.edit-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 16px;
    margin-bottom: 20px;
    border: 1px solid #f1c9c5;
    border-radius: 8px;
    color: #9f3028;
    background: #fff4f2;
    font-size: 12px;
}

.edit-alert ul {
    margin: 5px 0 0;
    padding-left: 18px;
}

.edit-section-note {
    padding: 13px 15px;
    margin-bottom: 18px;
    border: 1px solid #dce7d5;
    border-radius: 7px;
    color: #45634a;
    background: #f5f9f1;
    font-size: 12px;
}

.edit-class-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.edit-class-option {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px;
    border: 1px solid #e5dfd0;
    border-radius: 8px;
    background: #fcfbf7;
    cursor: pointer;
    transition: border-color .2s ease, background .2s ease;
}

.edit-class-option:hover {
    border-color: #b9d6b5;
    background: #f5f9f1;
}

.edit-class-option input {
    margin-top: 4px;
    accent-color: var(--green);
}

.edit-class-name {
    display: block;
    color: var(--ink);
    font-size: 12px;
    font-weight: 750;
}

.edit-class-meta {
    display: block;
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
}

.edit-status-panel {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 15px;
    border: 1px solid #dce7d5;
    border-radius: 8px;
    background: #f7fbf6;
}

.edit-status-title {
    color: var(--ink);
    font-size: 13px;
    font-weight: 750;
}

.edit-status-description {
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
}

.edit-status-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    color: var(--green);
    font-size: 12px;
    font-weight: 750;
}

.edit-status-toggle input {
    width: 17px;
    height: 17px;
    accent-color: var(--green);
}

.edit-sidebar-card {
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 3px 12px rgba(38,59,53,.035);
}

.edit-sidebar-top {
    padding: 22px;
    color: #fff;
    background:
        radial-gradient(circle at 95% 5%, rgba(255,255,255,.10), transparent 40%),
        var(--ink);
}

.edit-sidebar-top small {
    display: block;
    margin-bottom: 7px;
    color: #c5d0bd;
    font-size: 11px;
    font-weight: 650;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.edit-sidebar-top strong {
    display: block;
    font-family: var(--serif);
    font-size: 21px;
    overflow-wrap: anywhere;
}

.edit-sidebar-body {
    padding: 18px;
}

.edit-sidebar-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px dashed #e5dfd0;
    font-size: 11px;
}

.edit-sidebar-row:first-child {
    padding-top: 0;
}

.edit-sidebar-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
}

.edit-sidebar-row span {
    color: var(--muted);
}

.edit-sidebar-row strong {
    color: var(--ink);
    text-align: right;
    overflow-wrap: anywhere;
}

.edit-sidebar-note {
    padding: 16px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.edit-bottom-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 18px 22px;
    border-top: 1px dashed #d8d1bf;
    background: #fcfbf7;
}

.scholarship-edit-page a:focus-visible,
.scholarship-edit-page button:focus-visible,
.scholarship-edit-page input:focus-visible,
.scholarship-edit-page select:focus-visible,
.scholarship-edit-page textarea:focus-visible {
    outline: 3px solid rgba(58,157,107,.35);
    outline-offset: 2px;
}

@media (max-width: 1050px) {
    .scholarship-edit-layout {
        grid-template-columns: minmax(0, 1fr);
    }
}

@media (max-width: 700px) {
    .scholarship-edit-page {
        padding: 15px;
    }

    .scholarship-edit-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 21px 20px 21px 34px;
    }

    .scholarship-edit-header::before {
        left: 18px;
    }

    .scholarship-edit-actions {
        width: 100%;
    }

    .scholarship-edit-page .btn-edit-header {
        flex: 1;
    }

    .edit-form-grid,
    .edit-class-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .scholarship-edit-card-body {
        padding: 16px;
    }

    .scholarship-edit-title {
        font-size: 23px;
    }

    .edit-bottom-actions {
        flex-direction: column-reverse;
        padding: 16px;
    }

    .edit-bottom-actions .btn-edit-header {
        width: 100%;
    }
}

@media (prefers-reduced-motion: reduce) {
    .scholarship-edit-page *,
    .scholarship-edit-page *::before,
    .scholarship-edit-page *::after {
        transition: none !important;
        animation: none !important;
    }
}
</style>

@php
    $selectedClasses = old(
        'classes',
        isset($exam) ? $exam->classes->pluck('id')->toArray() : []
    );

    $selectedClasses = array_map('strval', (array) $selectedClasses);
@endphp

<div class="scholarship-edit-page">

    <div class="scholarship-edit-header">
        <div class="scholarship-edit-heading">
            <div class="scholarship-edit-icon">
                <i class="fas fa-edit"></i>
            </div>

            <div>
                <h1 class="scholarship-edit-title">Edit Scholarship Examination</h1>
                <p class="scholarship-edit-subtitle">
                    Update examination information, participation totals and status
                </p>
            </div>
        </div>

        <div class="scholarship-edit-actions">
            <a href="{{ route('admin.scholarship.exams.index') }}"
               class="btn-edit-header btn-edit-back">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.scholarship.exams.show', $exam) }}"
               class="btn-edit-header btn-edit-back">
                <i class="fas fa-eye"></i>
                View Exam
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="edit-alert" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.scholarship.exams.update', $exam) }}"
          method="POST">
        @csrf
        @method('PUT')

        <div class="scholarship-edit-layout">

            <div class="scholarship-edit-main">

                {{-- EXAMINATION DETAILS --}}
                <div class="scholarship-edit-card">
                    <div class="scholarship-edit-card-header">
                        <div class="scholarship-edit-card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h2 class="scholarship-edit-card-title">Examination Details</h2>
                            <p class="scholarship-edit-card-subtitle">
                                Basic information about the scholarship examination
                            </p>
                        </div>
                    </div>

                    <div class="scholarship-edit-card-body">
                        <div class="edit-form-grid">

                            <div class="edit-field full-width">
                                <label for="exam_name" class="edit-label">
                                    Examination Name <span class="required-mark">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="exam_name"
                                    name="exam_name"
                                    class="edit-control @error('exam_name') is-invalid @enderror"
                                    value="{{ old('exam_name', $exam->exam_name) }}"
                                    placeholder="Enter scholarship examination name"
                                    required
                                    maxlength="255">
                                @error('exam_name')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field">
                                <label for="exam_type" class="edit-label">Examination Type</label>
                                <input
                                    type="text"
                                    id="exam_type"
                                    name="exam_type"
                                    class="edit-control @error('exam_type') is-invalid @enderror"
                                    value="{{ old('exam_type', $exam->exam_type) }}"
                                    placeholder="e.g. State, National, School">
                                @error('exam_type')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field">
                                <label for="academic_year" class="edit-label">
                                    Academic Year <span class="required-mark">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="academic_year"
                                    name="academic_year"
                                    class="edit-control @error('academic_year') is-invalid @enderror"
                                    value="{{ old('academic_year', $exam->academic_year) }}"
                                    placeholder="e.g. 2026-2027"
                                    required>
                                @error('academic_year')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field">
                                <label for="exam_date" class="edit-label">Examination Date</label>
                                <input
                                    type="date"
                                    id="exam_date"
                                    name="exam_date"
                                    class="edit-control @error('exam_date') is-invalid @enderror"
                                    value="{{ old('exam_date', $exam->exam_date ? \Illuminate\Support\Carbon::parse($exam->exam_date)->format('Y-m-d') : '') }}">
                                @error('exam_date')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field">
                                <label for="conducted_by" class="edit-label">Conducted By</label>
                                <input
                                    type="text"
                                    id="conducted_by"
                                    name="conducted_by"
                                    class="edit-control @error('conducted_by') is-invalid @enderror"
                                    value="{{ old('conducted_by', $exam->conducted_by) }}"
                                    placeholder="Enter organization or authority">
                                @error('conducted_by')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- PARTICIPATION TOTALS --}}
                <div class="scholarship-edit-card">
                    <div class="scholarship-edit-card-header">
                        <div class="scholarship-edit-card-icon blue">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h2 class="scholarship-edit-card-title">Participation Statistics</h2>
                            <p class="scholarship-edit-card-subtitle">
                                Update the recorded student totals for this examination
                            </p>
                        </div>
                    </div>

                    <div class="scholarship-edit-card-body">
                        <div class="edit-section-note">
                            These totals are used as fallback values when detailed student
                            application records are not available.
                        </div>

                        <div class="edit-form-grid">
                            <div class="edit-field">
                                <label for="total_eligible" class="edit-label">Eligible Students</label>
                                <input
                                    type="number"
                                    id="total_eligible"
                                    name="total_eligible"
                                    min="0"
                                    step="1"
                                    class="edit-control @error('total_eligible') is-invalid @enderror"
                                    value="{{ old('total_eligible', $exam->total_eligible ?? 0) }}">
                                @error('total_eligible')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field">
                                <label for="total_applied" class="edit-label">Applied Students</label>
                                <input
                                    type="number"
                                    id="total_applied"
                                    name="total_applied"
                                    min="0"
                                    step="1"
                                    class="edit-control @error('total_applied') is-invalid @enderror"
                                    value="{{ old('total_applied', $exam->total_applied ?? 0) }}">
                                @error('total_applied')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field">
                                <label for="total_appeared" class="edit-label">Appeared Students</label>
                                <input
                                    type="number"
                                    id="total_appeared"
                                    name="total_appeared"
                                    min="0"
                                    step="1"
                                    class="edit-control @error('total_appeared') is-invalid @enderror"
                                    value="{{ old('total_appeared', $exam->total_appeared ?? 0) }}">
                                @error('total_appeared')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field">
                                <label for="total_passed" class="edit-label">Passed Students</label>
                                <input
                                    type="number"
                                    id="total_passed"
                                    name="total_passed"
                                    min="0"
                                    step="1"
                                    class="edit-control @error('total_passed') is-invalid @enderror"
                                    value="{{ old('total_passed', $exam->total_passed ?? 0) }}">
                                @error('total_passed')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PARTICIPATING CLASSES --}}
                <div class="scholarship-edit-card">
                    <div class="scholarship-edit-card-header">
                        <div class="scholarship-edit-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h2 class="scholarship-edit-card-title">Participating Classes</h2>
                            <p class="scholarship-edit-card-subtitle">
                                Select the classes included in this examination
                            </p>
                        </div>
                    </div>

                    <div class="scholarship-edit-card-body">
                        <div class="edit-section-note">
                            Previously linked classes are selected automatically.
                            Update this selection if the participating classes have changed.
                        </div>

                        @if(isset($classes) && $classes->count())
                            <div class="edit-class-grid">
                                @foreach($classes as $class)
                                    <label class="edit-class-option">
                                        <input
                                            type="checkbox"
                                            name="classes[]"
                                            value="{{ $class->id }}"
                                            @checked(in_array((string) $class->id, $selectedClasses, true))>

                                        <span>
                                            <span class="edit-class-name">
                                                {{ $class->class_name ?? $class->name ?? 'Class' }}
                                                @if(!empty($class->section))
                                                    - {{ $class->section }}
                                                @endif
                                            </span>
                                            <span class="edit-class-meta">
                                                {{ $class->academic_year ?? 'Academic year not specified' }}
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="edit-section-note">
                                No class list was provided by the controller. The examination
                                details can still be edited, but class selection is unavailable.
                            </div>
                        @endif

                        @error('classes')
                            <span class="edit-error">{{ $message }}</span>
                        @enderror
                        @error('classes.*')
                            <span class="edit-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- STATUS AND REMARKS --}}
                <div class="scholarship-edit-card">
                    <div class="scholarship-edit-card-header">
                        <div class="scholarship-edit-card-icon orange">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <h2 class="scholarship-edit-card-title">Status &amp; Remarks</h2>
                            <p class="scholarship-edit-card-subtitle">
                                Control availability and add additional notes
                            </p>
                        </div>
                    </div>

                    <div class="scholarship-edit-card-body">
                        <div class="edit-form-grid">

                            <div class="edit-field full-width">
                                <div class="edit-status-panel">
                                    <div>
                                        <div class="edit-status-title">Examination Status</div>
                                        <div class="edit-status-description">
                                            Enable or disable this examination record.
                                        </div>
                                    </div>

                                    <label class="edit-status-toggle">
                                        <input
                                            type="checkbox"
                                            name="status"
                                            value="1"
                                            @checked((bool) old('status', $exam->status))>
                                        Active
                                    </label>
                                </div>
                                @error('status')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="edit-field full-width">
                                <label for="remarks" class="edit-label">Examination Remarks</label>
                                <textarea
                                    id="remarks"
                                    name="remarks"
                                    class="edit-control @error('remarks') is-invalid @enderror"
                                    placeholder="Enter additional examination information...">{{ old('remarks', $exam->remarks) }}</textarea>
                                @error('remarks')
                                    <span class="edit-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="edit-bottom-actions">
                        <a href="{{ route('admin.scholarship.exams.show', $exam) }}"
                           class="btn-edit-header btn-edit-back">
                            Cancel
                        </a>

                        <button type="submit" class="btn-edit-header btn-edit-save">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <aside class="scholarship-edit-sidebar">

                <div class="edit-sidebar-card">
                    <div class="edit-sidebar-top">
                        <small>Editing Examination</small>
                        <strong>{{ $exam->exam_name }}</strong>
                    </div>

                    <div class="edit-sidebar-body">
                        <div class="edit-sidebar-row">
                            <span>Academic Year</span>
                            <strong>{{ $exam->academic_year ?: '—' }}</strong>
                        </div>

                        <div class="edit-sidebar-row">
                            <span>Exam Date</span>
                            <strong>
                                {{ $exam->exam_date
                                    ? \Illuminate\Support\Carbon::parse($exam->exam_date)->format('d M Y')
                                    : '—' }}
                            </strong>
                        </div>

                        <div class="edit-sidebar-row">
                            <span>Linked Classes</span>
                            <strong>{{ isset($exam->classes) ? $exam->classes->count() : 0 }}</strong>
                        </div>

                        <div class="edit-sidebar-row">
                            <span>Applications</span>
                            <strong>{{ isset($exam->applications) ? $exam->applications->count() : 0 }}</strong>
                        </div>

                        <div class="edit-sidebar-row">
                            <span>Status</span>
                            <strong>{{ $exam->status ? 'Active' : 'Inactive' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="edit-sidebar-card">
                    <div class="scholarship-edit-card-header">
                        <div class="scholarship-edit-card-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h2 class="scholarship-edit-card-title">Before Saving</h2>
                        </div>
                    </div>

                    <div class="edit-sidebar-note">
                        <p>Review the examination name and academic year.</p>
                        <p>Verify participation totals before updating them.</p>
                        <p>Changes are saved only after selecting <strong>Save Changes</strong>.</p>
                    </div>
                </div>

            </aside>

        </div>
    </form>

</div>

@endsection
