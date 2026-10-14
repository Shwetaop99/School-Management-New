@extends('layouts.app')

@section('title', 'Edit Achievement | Admin')
@section('page-title', 'Edit Achievement')

@section('content')

<div class="sports-achievement-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="achievement-page-header">
            <div class="achievement-heading-wrapper">
                <div class="achievement-page-icon">
                    <i class="fas fa-trophy"></i>
                </div>

                <div class="achievement-heading-content">
                    <div class="achievement-breadcrumb">
                        <span>Sports Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Achievements</span>
                        <i class="fas fa-chevron-right"></i>
                        <strong>Edit</strong>
                    </div>

                    <h2>Edit Achievement</h2>
                    <p>Update and manage the selected sports achievement information.</p>
                </div>
            </div>

            <a href="{{ route('admin.sports.achievements.show', $achievement) }}"
               class="achievement-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Details</span>
            </a>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="achievement-error-alert">
                <div class="error-alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="error-alert-content">
                    <div class="error-title">
                        Please review the highlighted information
                    </div>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN FORM CARD --}}
        <div class="achievement-form-card">
            <form action="{{ route('admin.sports.achievements.update', $achievement) }}"
                  method="POST">
                @csrf
                @method('PUT')

                {{-- BASIC INFORMATION --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-icon green">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div class="form-section-heading">
                            <div class="section-title-row">
                                <h4>Basic Information</h4>
                                <span class="section-badge green-badge">Required Details</span>
                            </div>
                            <p>Update the primary information related to this achievement.</p>
                        </div>
                    </div>

                    <div class="row g-4">

                        {{-- TITLE --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Achievement Title <span class="required">*</span>
                            </label>

                            <div class="input-wrapper">
                                <i class="fas fa-heading input-icon"></i>
                                <input type="text"
                                       name="title"
                                       class="form-control with-icon @error('title') is-invalid @enderror"
                                       value="{{ old('title', $achievement->title) }}"
                                       placeholder="Enter achievement title"
                                       required>
                            </div>

                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STUDENT --}}
                        <div class="col-md-6">
                            <label class="form-label">Student Name</label>

                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text"
                                       name="student_name"
                                       class="form-control with-icon @error('student_name') is-invalid @enderror"
                                       value="{{ old('student_name', $achievement->student_name) }}"
                                       placeholder="Enter student name">
                            </div>

                            @error('student_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SPORT --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Sport <span class="required">*</span>
                            </label>

                            <div class="input-wrapper">
                                <i class="fas fa-futbol input-icon"></i>
                                <input type="text"
                                       name="sport_name"
                                       class="form-control with-icon @error('sport_name') is-invalid @enderror"
                                       value="{{ old('sport_name', $achievement->sport_name) }}"
                                       placeholder="e.g. Cricket, Football, Athletics"
                                       required>
                            </div>

                            @error('sport_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ACHIEVEMENT TYPE --}}
                        <div class="col-md-6">
                            <label class="form-label">Achievement Type</label>

                            <div class="input-wrapper">
                                <i class="fas fa-medal input-icon"></i>
                                <input type="text"
                                       name="achievement_type"
                                       class="form-control with-icon @error('achievement_type') is-invalid @enderror"
                                       value="{{ old('achievement_type', $achievement->achievement_type) }}"
                                       placeholder="e.g. Tournament, Championship">
                            </div>

                            @error('achievement_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- POSITION --}}
                        <div class="col-md-6">
                            <label class="form-label">Position / Award</label>

                            <div class="input-wrapper">
                                <i class="fas fa-award input-icon"></i>
                                <input type="text"
                                       name="position"
                                       class="form-control with-icon @error('position') is-invalid @enderror"
                                       value="{{ old('position', $achievement->position) }}"
                                       placeholder="e.g. 1st Place, Gold Medal">
                            </div>

                            @error('position')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ACADEMIC YEAR --}}
                        <div class="col-md-6">
                            <label class="form-label">Academic Year</label>

                            <div class="input-wrapper">
                                <i class="fas fa-calendar-alt input-icon"></i>
                                <input type="text"
                                       name="academic_year"
                                       class="form-control with-icon @error('academic_year') is-invalid @enderror"
                                       value="{{ old('academic_year', $achievement->academic_year) }}"
                                       placeholder="e.g. 2026-27">
                            </div>

                            @error('academic_year')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- STUDENT AND CLASS INFORMATION --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-icon cyan">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div class="form-section-heading">
                            <div class="section-title-row">
                                <h4>Student &amp; Class Information</h4>
                                <span class="section-badge cyan-badge">Academic Details</span>
                            </div>
                            <p>Update the student's class and section information.</p>
                        </div>
                    </div>

                    <div class="row g-4">

                        {{-- CLASS --}}
                        <div class="col-md-6">
                            <label class="form-label">Class</label>

                            <div class="input-wrapper">
                                <i class="fas fa-school input-icon"></i>
                                <select name="class"
                                        id="class"
                                        class="form-select with-icon @error('class') is-invalid @enderror">
                                    <option value="">Select Class</option>

                                    @foreach($classes->unique('class_name') as $class)
                                        <option value="{{ $class->class_name }}"
                                            @selected(old('class', $achievement->class) == $class->class_name)>
                                            {{ $class->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('class')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SECTION --}}
                        <div class="col-md-6">
                            <label class="form-label">Section</label>

                            <div class="input-wrapper">
                                <i class="fas fa-layer-group input-icon"></i>
                                <input type="text"
                                       name="section"
                                       class="form-control with-icon @error('section') is-invalid @enderror"
                                       value="{{ old('section', $achievement->section) }}"
                                       placeholder="Enter section e.g. A, B, C">
                            </div>

                            @error('section')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- COMPETITION INFORMATION --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-icon brown">
                            <i class="fas fa-trophy"></i>
                        </div>

                        <div class="form-section-heading">
                            <div class="section-title-row">
                                <h4>Competition Information</h4>
                                <span class="section-badge brown-badge">Event Details</span>
                            </div>
                            <p>Update competition, venue, date and achievement status.</p>
                        </div>
                    </div>

                    <div class="row g-4">

                        {{-- COMPETITION --}}
                        <div class="col-md-6">
                            <label class="form-label">Competition Name</label>

                            <div class="input-wrapper">
                                <i class="fas fa-flag-checkered input-icon"></i>
                                <input type="text"
                                       name="competition_name"
                                       class="form-control with-icon @error('competition_name') is-invalid @enderror"
                                       value="{{ old('competition_name', $achievement->competition_name) }}"
                                       placeholder="Enter competition name">
                            </div>

                            @error('competition_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DATE --}}
                        <div class="col-md-6">
                            <label class="form-label">Achievement Date</label>

                            <div class="input-wrapper">
                                <i class="fas fa-calendar-day input-icon"></i>
                                <input type="date"
                                       name="achievement_date"
                                       class="form-control with-icon @error('achievement_date') is-invalid @enderror"
                                       value="{{ old(
                                           'achievement_date',
                                           $achievement->achievement_date
                                               ? $achievement->achievement_date->format('Y-m-d')
                                               : ''
                                       ) }}">
                            </div>

                            @error('achievement_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- VENUE --}}
                        <div class="col-md-6">
                            <label class="form-label">Venue</label>

                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text"
                                       name="venue"
                                       class="form-control with-icon @error('venue') is-invalid @enderror"
                                       value="{{ old('venue', $achievement->venue) }}"
                                       placeholder="Enter venue">
                            </div>

                            @error('venue')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Achievement Status <span class="required">*</span>
                            </label>

                            <div class="status-options">

                                <label class="status-option active-option">
                                    <input type="radio"
                                           name="status"
                                           value="active"
                                           @checked(old('status', $achievement->status) === 'active')>

                                    <span class="status-radio"></span>

                                    <span class="status-option-content">
                                        <strong>
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </strong>
                                        <small>Achievement is currently active</small>
                                    </span>
                                </label>

                                <label class="status-option inactive-option">
                                    <input type="radio"
                                           name="status"
                                           value="inactive"
                                           @checked(old('status', $achievement->status) === 'inactive')>

                                    <span class="status-radio"></span>

                                    <span class="status-option-content">
                                        <strong>
                                            <i class="fas fa-pause-circle"></i>
                                            Inactive
                                        </strong>
                                        <small>Achievement is currently inactive</small>
                                    </span>
                                </label>
                            </div>

                            @error('status')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-icon red">
                            <i class="fas fa-align-left"></i>
                        </div>

                        <div class="form-section-heading">
                            <div class="section-title-row">
                                <h4>Description</h4>
                                <span class="section-badge red-badge">Additional Information</span>
                            </div>
                            <p>Add additional information, notes or details about the achievement.</p>
                        </div>
                    </div>

                    <div class="description-wrapper">
                        <textarea name="description"
                                  rows="5"
                                  class="form-control description-control @error('description') is-invalid @enderror"
                                  placeholder="Enter achievement description...">{{ old('description', $achievement->description) }}</textarea>

                        <div class="description-helper">
                            <i class="fas fa-info-circle"></i>
                            Provide a clear description of the student's achievement or accomplishment.
                        </div>
                    </div>

                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FORM FOOTER --}}
                <div class="form-footer">
                    <div class="form-footer-info">
                        <div class="save-status-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>

                        <div>
                            <strong>Ready to update</strong>
                            <span>Review the information before saving changes.</span>
                        </div>
                    </div>

                    <div class="form-footer-actions">
                        <a href="{{ route('admin.sports.achievements.show', $achievement) }}"
                           class="cancel-btn">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>

                        <button type="submit" class="update-btn">
                            <i class="fas fa-save"></i>
                            Update Achievement
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
/* =========================================================
   SCHOOL DIARY THEME — EDIT ACHIEVEMENT
   Scoped to this page to protect other modules.
========================================================= */

.sports-achievement-page {
    --achievement-ink: #263b35;
    --achievement-ink-dark: #1d302b;
    --achievement-green: #34775a;
    --achievement-green-dark: #245a43;
    --achievement-green-soft: #eaf3e9;
    --achievement-paper: #fffefa;
    --achievement-cream: #f5f3e9;
    --achievement-muted: #849087;
    --achievement-border: #e5e1d3;
    --achievement-brown: #a87543;
    --achievement-brown-soft: #f7eee3;
    --achievement-red: #bd5148;
    --achievement-red-soft: #fff0ec;
    --achievement-cyan: #527f79;

    min-height: calc(100vh - 60px);
    background: var(--achievement-cream);
    color: var(--achievement-ink);
    font-family: 'Inter', sans-serif;
}

.sports-achievement-page .container-fluid {
    max-width: 1600px;
}

/* PAGE HEADER */
.sports-achievement-page .achievement-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
    margin-bottom: 23px;
}

.sports-achievement-page .achievement-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.sports-achievement-page .achievement-page-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 51px;
    height: 51px;
    flex: 0 0 51px;
    border: 1px solid #cbdcc9;
    border-radius: 13px;
    background: var(--achievement-green-soft);
    color: var(--achievement-green);
    font-size: 20px;
}

.sports-achievement-page .achievement-heading-content {
    min-width: 0;
}

.sports-achievement-page .achievement-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 4px;
    color: var(--achievement-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.sports-achievement-page .achievement-breadcrumb i {
    color: #b8b9a9;
    font-size: 8px;
}

.sports-achievement-page .achievement-breadcrumb strong {
    color: var(--achievement-green);
}

.sports-achievement-page .achievement-heading-wrapper h2 {
    margin: 0;
    color: var(--achievement-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 27px;
    font-weight: 700;
    line-height: 1.25;
}

.sports-achievement-page .achievement-heading-wrapper p {
    margin: 6px 0 0;
    color: #778078;
    font-size: 12px;
    line-height: 1.6;
}

/* HEADER AND FOOTER BUTTONS */
.sports-achievement-page .achievement-back-btn,
.sports-achievement-page .cancel-btn,
.sports-achievement-page .update-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 9px 15px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 750;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
}

.sports-achievement-page .achievement-back-btn,
.sports-achievement-page .cancel-btn {
    border-color: var(--achievement-border);
    background: var(--achievement-paper);
    color: var(--achievement-ink);
}

.sports-achievement-page .achievement-back-btn:hover,
.sports-achievement-page .cancel-btn:hover {
    border-color: #cfcbbb;
    background: #f0eee3;
    color: var(--achievement-ink-dark);
}

.sports-achievement-page .update-btn {
    border-color: var(--achievement-green);
    background: var(--achievement-green);
    color: #fff;
    box-shadow: 0 4px 11px rgba(52, 119, 90, .15);
}

.sports-achievement-page .update-btn:hover {
    border-color: var(--achievement-green-dark);
    background: var(--achievement-green-dark);
    color: #fff;
    transform: translateY(-1px);
}

/* VALIDATION ALERT */
.sports-achievement-page .achievement-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 20px;
    padding: 15px 17px;
    border: 1px solid #e8c8bf;
    border-radius: 9px;
    background: var(--achievement-red-soft);
    color: #963d35;
}

.sports-achievement-page .error-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border-radius: 8px;
    background: #f9dfd8;
    color: var(--achievement-red);
}

.sports-achievement-page .error-title {
    font-size: 12px;
    font-weight: 800;
}

.sports-achievement-page .achievement-error-alert ul {
    margin: 6px 0 0;
    padding-left: 17px;
    color: #963d35;
    font-size: 11px;
    line-height: 1.8;
}

/* FORM CARD */
.sports-achievement-page .achievement-form-card {
    overflow: hidden;
    border: 1px solid var(--achievement-border);
    border-radius: 12px;
    background: var(--achievement-paper);
    box-shadow: 0 4px 16px rgba(38, 59, 53, .045);
}

/* SECTIONS */
.sports-achievement-page .form-section {
    padding: 26px 28px 29px;
    border-bottom: 1px solid var(--achievement-border);
}

.sports-achievement-page .form-section:last-of-type {
    border-bottom: none;
}

.sports-achievement-page .form-section-header {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 23px;
}

.sports-achievement-page .form-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 41px;
    height: 41px;
    flex: 0 0 41px;
    border: 1px solid var(--achievement-border);
    border-radius: 9px;
    font-size: 14px;
}

.sports-achievement-page .form-section-icon.green {
    background: var(--achievement-green-soft);
    color: var(--achievement-green);
}

.sports-achievement-page .form-section-icon.cyan {
    background: #eaf1ed;
    color: var(--achievement-cyan);
}

.sports-achievement-page .form-section-icon.brown {
    background: var(--achievement-brown-soft);
    color: var(--achievement-brown);
}

.sports-achievement-page .form-section-icon.red {
    background: var(--achievement-red-soft);
    color: var(--achievement-red);
}

.sports-achievement-page .form-section-heading {
    min-width: 0;
}

.sports-achievement-page .section-title-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
}

.sports-achievement-page .form-section-header h4 {
    margin: 0;
    color: var(--achievement-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 17px;
    font-weight: 700;
}

.sports-achievement-page .form-section-header p {
    margin: 4px 0 0;
    color: var(--achievement-muted);
    font-size: 10.5px;
    line-height: 1.6;
}

/* SECTION BADGES */
.sports-achievement-page .section-badge {
    display: inline-flex;
    align-items: center;
    min-height: 21px;
    padding: 3px 8px;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: .55px;
    text-transform: uppercase;
}

.sports-achievement-page .green-badge {
    border-color: #d0e1cd;
    background: var(--achievement-green-soft);
    color: var(--achievement-green);
}

.sports-achievement-page .cyan-badge {
    border-color: #d4e5df;
    background: #eaf1ed;
    color: var(--achievement-cyan);
}

.sports-achievement-page .brown-badge {
    border-color: #ead8c2;
    background: var(--achievement-brown-soft);
    color: var(--achievement-brown);
}

.sports-achievement-page .red-badge {
    border-color: #e8c8bf;
    background: var(--achievement-red-soft);
    color: var(--achievement-red);
}

/* LABELS */
.sports-achievement-page .form-label {
    display: block;
    margin-bottom: 8px;
    color: var(--achievement-ink);
    font-size: 11px;
    font-weight: 750;
}

.sports-achievement-page .required {
    color: var(--achievement-red);
}

/* INPUTS */
.sports-achievement-page .input-wrapper {
    position: relative;
}

.sports-achievement-page .input-icon {
    position: absolute;
    z-index: 2;
    top: 50%;
    left: 13px;
    width: 15px;
    color: #929b90;
    font-size: 11px;
    text-align: center;
    pointer-events: none;
    transform: translateY(-50%);
    transition: color .2s ease;
}

.sports-achievement-page .form-control,
.sports-achievement-page .form-select {
    width: 100%;
    min-height: 43px;
    padding: 10px 12px;
    border: 1px solid #dcded2;
    border-radius: 7px;
    outline: none;
    background-color: #fffefa;
    color: var(--achievement-ink);
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.sports-achievement-page .form-control::placeholder {
    color: #a0a79b;
}

.sports-achievement-page .form-control:hover,
.sports-achievement-page .form-select:hover {
    border-color: #c4cfc0;
}

.sports-achievement-page .form-control:focus,
.sports-achievement-page .form-select:focus {
    border-color: var(--achievement-green);
    background: #fffefa;
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
}

.sports-achievement-page .input-wrapper:focus-within .input-icon {
    color: var(--achievement-green);
}

.sports-achievement-page .form-control.with-icon,
.sports-achievement-page .form-select.with-icon {
    padding-left: 38px;
}

.sports-achievement-page .form-select.with-icon {
    cursor: pointer;
}

.sports-achievement-page .form-control.is-invalid,
.sports-achievement-page .form-select.is-invalid {
    border-color: var(--achievement-red);
}

.sports-achievement-page .form-control.is-invalid:focus,
.sports-achievement-page .form-select.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .09);
}

.sports-achievement-page .invalid-feedback {
    margin-top: 5px;
    color: var(--achievement-red);
    font-size: 10px;
    font-weight: 650;
}

/* STATUS OPTIONS */
.sports-achievement-page .status-options {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.sports-achievement-page .status-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    min-height: 67px;
    padding: 11px;
    border: 1px solid var(--achievement-border);
    border-radius: 8px;
    background: var(--achievement-paper);
    cursor: pointer;
    transition: border-color .2s ease, background .2s ease;
}

.sports-achievement-page .status-option:hover {
    border-color: #c7d5c2;
    background: #fbfcf6;
}

.sports-achievement-page .status-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.sports-achievement-page .status-radio {
    position: relative;
    width: 17px;
    height: 17px;
    flex: 0 0 17px;
    border: 2px solid #c6cbbf;
    border-radius: 50%;
}

.sports-achievement-page .status-option input:checked + .status-radio {
    border-color: var(--achievement-green);
}

.sports-achievement-page .status-option input:checked + .status-radio::after {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--achievement-green);
    content: '';
}

.sports-achievement-page .status-option:has(input:checked) {
    border-color: #bfd5bb;
    background: #f5f9f1;
}

.sports-achievement-page .inactive-option:has(input:checked) {
    border-color: #dfc9bf;
    background: #fff7f3;
}

.sports-achievement-page .status-option-content {
    min-width: 0;
}

.sports-achievement-page .status-option strong {
    display: block;
    color: var(--achievement-ink);
    font-size: 11px;
    font-weight: 800;
}

.sports-achievement-page .status-option strong i {
    margin-right: 4px;
    color: var(--achievement-green);
    font-size: 10px;
}

.sports-achievement-page .inactive-option strong i {
    color: var(--achievement-red);
}

.sports-achievement-page .status-option small {
    display: block;
    margin-top: 4px;
    color: var(--achievement-muted);
    font-size: 9px;
    line-height: 1.5;
}

/* DESCRIPTION */
.sports-achievement-page .description-wrapper {
    position: relative;
}

.sports-achievement-page .description-control {
    min-height: 135px !important;
    padding: 13px 14px !important;
    line-height: 1.7;
    resize: vertical;
}

.sports-achievement-page .description-helper {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    margin-top: 8px;
    color: var(--achievement-muted);
    font-size: 10px;
    line-height: 1.6;
}

.sports-achievement-page .description-helper i {
    margin-top: 3px;
    color: var(--achievement-green);
}

/* FOOTER */
.sports-achievement-page .form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 17px 28px;
    border-top: 1px solid var(--achievement-border);
    background: #f8f7ef;
}

.sports-achievement-page .form-footer-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.sports-achievement-page .save-status-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border: 1px solid #cbdcc9;
    border-radius: 8px;
    background: var(--achievement-green-soft);
    color: var(--achievement-green);
    font-size: 12px;
}

.sports-achievement-page .form-footer-info strong {
    display: block;
    color: var(--achievement-ink);
    font-size: 11px;
    font-weight: 800;
}

.sports-achievement-page .form-footer-info span {
    display: block;
    margin-top: 3px;
    color: var(--achievement-muted);
    font-size: 9px;
    line-height: 1.5;
}

.sports-achievement-page .form-footer-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-shrink: 0;
}

/* RESPONSIVE */
@media (max-width: 991px) {
    .sports-achievement-page .achievement-page-header {
        align-items: flex-start;
    }

    .sports-achievement-page .status-options {
        grid-template-columns: 1fr;
    }

    .sports-achievement-page .form-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .sports-achievement-page .form-footer-actions {
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width: 768px) {
    .sports-achievement-page .achievement-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .sports-achievement-page .achievement-back-btn {
        width: 100%;
    }

    .sports-achievement-page .achievement-heading-wrapper h2 {
        font-size: 23px;
    }

    .sports-achievement-page .form-section {
        padding: 22px 18px 24px;
    }

    .sports-achievement-page .form-footer {
        padding: 16px 18px;
    }
}

@media (max-width: 575px) {
    .sports-achievement-page .achievement-heading-wrapper {
        align-items: flex-start;
        gap: 11px;
    }

    .sports-achievement-page .achievement-page-icon {
        width: 44px;
        height: 44px;
        flex-basis: 44px;
        border-radius: 10px;
        font-size: 17px;
    }

    .sports-achievement-page .achievement-heading-wrapper h2 {
        font-size: 21px;
    }

    .sports-achievement-page .achievement-heading-wrapper p {
        font-size: 11px;
    }

    .sports-achievement-page .section-title-row {
        align-items: flex-start;
        flex-direction: column;
        gap: 7px;
    }

    .sports-achievement-page .form-section-header {
        align-items: flex-start;
    }

    .sports-achievement-page .form-section-icon {
        width: 37px;
        height: 37px;
        flex-basis: 37px;
    }

    .sports-achievement-page .status-options {
        grid-template-columns: 1fr;
    }

    .sports-achievement-page .form-footer-actions {
        flex-direction: column-reverse;
        width: 100%;
    }

    .sports-achievement-page .form-footer-actions .cancel-btn,
    .sports-achievement-page .form-footer-actions .update-btn {
        width: 100%;
    }

    .sports-achievement-page .form-footer-info {
        align-items: flex-start;
    }
}

@media (max-width: 380px) {
    .sports-achievement-page .achievement-heading-wrapper h2 {
        font-size: 19px;
    }

    .sports-achievement-page .form-section {
        padding: 19px 13px;
    }

    .sports-achievement-page .achievement-form-card {
        border-radius: 10px;
    }

    .sports-achievement-page .form-footer {
        padding: 14px;
    }
}
</style>

@endsection
