@extends('layouts.app')

@section('title', 'Add Achievement | Admin')

@section('content')

<div class="sports-achievement-create-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="achievement-create-header">

            <div class="create-heading-area">

                <a href="{{ route('admin.sports.achievements.index') }}"
                   class="back-btn"
                   aria-label="Back to achievements">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="create-heading-icon">
                    <i class="fas fa-trophy"></i>
                </div>

                <div class="create-heading-content">
                    <div class="create-breadcrumb">
                        <span>Sports</span>
                        <span class="breadcrumb-separator">/</span>
                        <span>Achievements</span>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-current">Add</span>
                    </div>

                    <h1>Add Achievement</h1>
                    <p>Record and preserve a student's sporting milestones.</p>
                </div>

            </div>

            <div class="header-badge">
                <i class="fas fa-plus-circle"></i>
                New Record
            </div>

        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-card" role="alert">

                <div class="form-error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="form-error-content">
                    <strong>Please correct the following errors:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endif

        {{-- ACHIEVEMENT FORM --}}
        <form method="POST"
              action="{{ route('admin.sports.achievements.store') }}"
              class="achievement-form">

            @csrf

            {{-- SECTION 01: ACHIEVEMENT INFORMATION --}}
            <div class="form-card">

                <div class="form-card-header">
                    <div class="form-section-title">

                        <div class="section-icon blue-icon">
                            <i class="fas fa-trophy"></i>
                        </div>

                        <div>
                            <h3>Achievement Information</h3>
                            <p>Enter the basic details of the achievement.</p>
                        </div>

                    </div>

                    <span class="section-step">01</span>
                </div>

                <div class="form-card-body">
                    <div class="form-grid">

                        {{-- TITLE --}}
                        <div class="form-group full-width">
                            <label for="title">
                                Achievement Title <span class="required">*</span>
                            </label>

                            <div class="input-with-icon">
                                <i class="fas fa-heading"></i>

                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}"
                                    placeholder="Enter achievement title"
                                    required
                                >
                            </div>

                            @error('title')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STUDENT --}}
                        <div class="form-group">
                            <label for="student_name">Student Name</label>

                            <div class="input-with-icon">
                                <i class="fas fa-user-graduate"></i>

                                <input
                                    type="text"
                                    name="student_name"
                                    id="student_name"
                                    class="form-control @error('student_name') is-invalid @enderror"
                                    value="{{ old('student_name') }}"
                                    placeholder="Enter student name"
                                >
                            </div>

                            @error('student_name')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SPORT --}}
                        <div class="form-group">
                            <label for="sport_name">
                                Sport <span class="required">*</span>
                            </label>

                            <div class="input-with-icon">
                                <i class="fas fa-running"></i>

                                <input
                                    type="text"
                                    name="sport_name"
                                    id="sport_name"
                                    class="form-control @error('sport_name') is-invalid @enderror"
                                    value="{{ old('sport_name') }}"
                                    placeholder="e.g. Cricket, Football"
                                    required
                                >
                            </div>

                            @error('sport_name')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ACHIEVEMENT TYPE --}}
                        <div class="form-group">
                            <label for="achievement_type">Achievement Type</label>

                            <div class="input-with-icon">
                                <i class="fas fa-medal"></i>

                                <input
                                    type="text"
                                    name="achievement_type"
                                    id="achievement_type"
                                    class="form-control @error('achievement_type') is-invalid @enderror"
                                    value="{{ old('achievement_type') }}"
                                    placeholder="e.g. Championship, Tournament"
                                >
                            </div>

                            @error('achievement_type')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- POSITION --}}
                        <div class="form-group">
                            <label for="position">Position / Rank</label>

                            <div class="input-with-icon">
                                <i class="fas fa-award"></i>

                                <input
                                    type="text"
                                    name="position"
                                    id="position"
                                    class="form-control @error('position') is-invalid @enderror"
                                    value="{{ old('position') }}"
                                    placeholder="e.g. 1st, 2nd, Winner"
                                >
                            </div>

                            @error('position')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- SECTION 02: STUDENT AND ACADEMIC DETAILS --}}
            <div class="form-card">

                <div class="form-card-header">
                    <div class="form-section-title">

                        <div class="section-icon cyan-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div>
                            <h3>Student &amp; Academic Details</h3>
                            <p>Associate the achievement with the student's class.</p>
                        </div>

                    </div>

                    <span class="section-step">02</span>
                </div>

                <div class="form-card-body">
                    <div class="form-grid">

                        {{-- ACADEMIC YEAR --}}
                        <div class="form-group">
                            <label for="academic_year">Academic Year</label>

                            <div class="input-with-icon">
                                <i class="fas fa-calendar-alt"></i>

                                <input
                                    type="text"
                                    name="academic_year"
                                    id="academic_year"
                                    class="form-control @error('academic_year') is-invalid @enderror"
                                    value="{{ old('academic_year') }}"
                                    placeholder="e.g. 2026-27"
                                >
                            </div>

                            @error('academic_year')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CLASS --}}
                        <div class="form-group">
                            <label for="class">Class</label>

                            <div class="input-with-icon">
                                <i class="fas fa-school"></i>

                                <select
                                    name="class"
                                    id="class"
                                    class="form-control @error('class') is-invalid @enderror"
                                >
                                    <option value="">Select Class</option>

                                    @foreach($classes->unique('class_name') as $schoolClass)
                                        <option
                                            value="{{ $schoolClass->class_name }}"
                                            {{ old('class') == $schoolClass->class_name ? 'selected' : '' }}
                                        >
                                            {{ $schoolClass->class_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            @error('class')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SECTION --}}
                        <div class="form-group">
                            <label for="section">Section</label>

                            <div class="input-with-icon">
                                <i class="fas fa-layer-group"></i>

                                <input
                                    type="text"
                                    name="section"
                                    id="section"
                                    class="form-control @error('section') is-invalid @enderror"
                                    value="{{ old('section') }}"
                                    placeholder="Enter section, e.g. A, B, C"
                                >
                            </div>

                            @error('section')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ACHIEVEMENT DATE --}}
                        <div class="form-group">
                            <label for="achievement_date">Achievement Date</label>

                            <div class="input-with-icon">
                                <i class="fas fa-calendar-day"></i>

                                <input
                                    type="date"
                                    name="achievement_date"
                                    id="achievement_date"
                                    class="form-control @error('achievement_date') is-invalid @enderror"
                                    value="{{ old('achievement_date') }}"
                                >
                            </div>

                            @error('achievement_date')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- SECTION 03: COMPETITION DETAILS --}}
            <div class="form-card">

                <div class="form-card-header">
                    <div class="form-section-title">

                        <div class="section-icon orange-icon">
                            <i class="fas fa-flag-checkered"></i>
                        </div>

                        <div>
                            <h3>Competition Details</h3>
                            <p>Record the competition, venue, and additional details.</p>
                        </div>

                    </div>

                    <span class="section-step">03</span>
                </div>

                <div class="form-card-body">
                    <div class="form-grid">

                        {{-- COMPETITION NAME --}}
                        <div class="form-group">
                            <label for="competition_name">Competition Name</label>

                            <div class="input-with-icon">
                                <i class="fas fa-flag"></i>

                                <input
                                    type="text"
                                    name="competition_name"
                                    id="competition_name"
                                    class="form-control @error('competition_name') is-invalid @enderror"
                                    value="{{ old('competition_name') }}"
                                    placeholder="Enter competition name"
                                >
                            </div>

                            @error('competition_name')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- VENUE --}}
                        <div class="form-group">
                            <label for="venue">Venue</label>

                            <div class="input-with-icon">
                                <i class="fas fa-map-marker-alt"></i>

                                <input
                                    type="text"
                                    name="venue"
                                    id="venue"
                                    class="form-control @error('venue') is-invalid @enderror"
                                    value="{{ old('venue') }}"
                                    placeholder="Enter venue"
                                >
                            </div>

                            @error('venue')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="form-group full-width">
                            <label for="description">Description</label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="form-control textarea-control @error('description') is-invalid @enderror"
                                placeholder="Enter additional achievement details..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- SECTION 04: STATUS --}}
            <div class="form-card">

                <div class="form-card-header">
                    <div class="form-section-title">

                        <div class="section-icon green-icon">
                            <i class="fas fa-toggle-on"></i>
                        </div>

                        <div>
                            <h3>Status</h3>
                            <p>Choose whether this achievement is active.</p>
                        </div>

                    </div>

                    <span class="section-step">04</span>
                </div>

                <div class="form-card-body">

                    <div class="status-options">

                        <label class="status-option active-option">

                            <input
                                type="radio"
                                name="status"
                                value="active"
                                {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                            >

                            <span class="custom-radio"></span>

                            <span class="status-option-content">
                                <strong>Active</strong>
                                <small>Achievement is currently active.</small>
                            </span>

                            <span class="status-indicator">
                                <i class="fas fa-check"></i>
                            </span>

                        </label>

                        <label class="status-option inactive-option">

                            <input
                                type="radio"
                                name="status"
                                value="inactive"
                                {{ old('status') === 'inactive' ? 'checked' : '' }}
                            >

                            <span class="custom-radio"></span>

                            <span class="status-option-content">
                                <strong>Inactive</strong>
                                <small>Achievement will be marked inactive.</small>
                            </span>

                            <span class="status-indicator">
                                <i class="fas fa-check"></i>
                            </span>

                        </label>

                    </div>

                    @error('status')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                </div>
            </div>

            {{-- FORM ACTIONS --}}
            <div class="form-actions">

                <a href="{{ route('admin.sports.achievements.index') }}"
                   class="cancel-btn">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>

                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i>
                    Save Achievement
                    <i class="fas fa-arrow-right save-arrow"></i>
                </button>

            </div>

        </form>

    </div>
</div>

<style>
/* =========================================================
   SPORTS ACHIEVEMENT CREATE
   SCHOOL DIARY DESIGN SYSTEM
   Warm ivory paper, forest green, earthy accents
   All styles scoped to this page
========================================================= */

.sports-achievement-create-page {
    --ink: #263b35;
    --ink-2: #1d302b;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e0d3;
    --paper: #fffefa;
    --chalk: #f4f0df;
    --wood: #a87543;
    --red: #c94f3d;
    --blue: #2b5d8a;
    --amber: #d58a0b;
    --green: #3a9d6b;
    --cyan: #268da0;

    min-height: calc(100vh - 60px);
    color: var(--text);
    background:
        radial-gradient(ellipse at top left, rgba(214, 202, 169, .16), transparent 35%),
        radial-gradient(ellipse at bottom right, rgba(43, 93, 138, .05), transparent 35%),
        linear-gradient(135deg, #f7f6f0 0%, #f4f6f2 100%);
    font-family: Inter, "Segoe UI", sans-serif;
}

.sports-achievement-create-page *,
.sports-achievement-create-page *::before,
.sports-achievement-create-page *::after {
    box-sizing: border-box;
}

.sports-achievement-create-page .container-fluid {
    width: 100%;
    max-width: 1600px;
    padding-right: 26px;
    padding-left: 26px;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.sports-achievement-create-page .achievement-create-header {
    display: flex;
    align-items: stretch;
    justify-content: space-between;
    gap: 22px;
    margin-bottom: 23px;
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 13px;
    background: var(--paper);
    box-shadow: 0 7px 24px rgba(38, 59, 53, .045);
}

.sports-achievement-create-page .create-heading-area {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
    gap: 14px;
    min-width: 0;
    padding: 24px 26px;
}

.sports-achievement-create-page .create-heading-area::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 4px;
    background: var(--red);
    content: "";
}

.sports-achievement-create-page .back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    min-width: 36px;
    border: 1px solid var(--line);
    border-radius: 8px;
    background: #f8f6ee;
    color: var(--ink) !important;
    text-decoration: none !important;
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}

.sports-achievement-create-page .back-btn:hover {
    border-color: #c6d4c8;
    background: #eaf0e7;
    transform: translateX(-2px);
}

.sports-achievement-create-page .create-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 49px;
    height: 49px;
    min-width: 49px;
    border: 1px solid #e7dfc9;
    border-radius: 10px;
    background: var(--chalk);
    color: var(--wood);
    font-size: 20px;
}

.sports-achievement-create-page .create-heading-content {
    min-width: 0;
}

.sports-achievement-create-page .create-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 6px;
    color: var(--wood);
    font-size: 10px;
    font-weight: 750;
    letter-spacing: 1.25px;
    text-transform: uppercase;
}

.sports-achievement-create-page .breadcrumb-separator {
    color: #c2b8a4;
}

.sports-achievement-create-page .breadcrumb-current {
    color: var(--green);
}

.sports-achievement-create-page .create-heading-area h1 {
    margin: 0;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 29px;
    font-weight: 600;
    line-height: 1.2;
    letter-spacing: -.5px;
}

.sports-achievement-create-page .create-heading-area p {
    margin: 7px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.6;
}

.sports-achievement-create-page .header-badge {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 8px;
    min-width: 155px;
    padding: 19px 24px;
    border-left: 1px solid #38544a;
    background: var(--ink);
    color: #f8f2e4;
    font-size: 12px;
    font-weight: 650;
}

.sports-achievement-create-page .header-badge i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid rgba(244, 240, 223, .25);
    border-radius: 8px;
    background: rgba(244, 240, 223, .1);
    color: #e4c98e;
    font-size: 14px;
}

/* =========================================================
   VALIDATION ERRORS
========================================================= */

.sports-achievement-create-page .form-error-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    padding: 15px 17px;
    border: 1px solid #ead0c8;
    border-left: 4px solid var(--red);
    border-radius: 10px;
    background: #fffaf6;
    box-shadow: 0 4px 15px rgba(38, 59, 53, .035);
}

.sports-achievement-create-page .form-error-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 8px;
    background: #f8e9e4;
    color: var(--red);
    font-size: 14px;
}

.sports-achievement-create-page .form-error-content {
    min-width: 0;
}

.sports-achievement-create-page .form-error-content strong {
    display: block;
    margin: 2px 0 7px;
    color: #9f3d30;
    font-size: 12px;
    font-weight: 750;
}

.sports-achievement-create-page .form-error-content ul {
    margin: 0;
    padding-left: 17px;
    color: #a74a3d;
    font-size: 11px;
    line-height: 1.8;
}

/* =========================================================
   FORM CARDS
========================================================= */

.sports-achievement-create-page .form-card {
    margin-bottom: 19px;
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
    transition: border-color .2s ease, box-shadow .2s ease;
}

.sports-achievement-create-page .form-card:hover {
    border-color: #d8d1bf;
    box-shadow: 0 7px 22px rgba(38, 59, 53, .06);
}

.sports-achievement-create-page .form-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed var(--line);
    background: #fffdf7;
}

.sports-achievement-create-page .form-section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.sports-achievement-create-page .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    min-width: 40px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-size: 15px;
}

.sports-achievement-create-page .blue-icon {
    border-color: #d8e1e9;
    background: #eaf0f5;
    color: var(--blue);
}

.sports-achievement-create-page .cyan-icon {
    border-color: #d1e7e4;
    background: #e9f4f0;
    color: var(--cyan);
}

.sports-achievement-create-page .orange-icon {
    border-color: #eddfc4;
    background: #f7efdc;
    color: var(--wood);
}

.sports-achievement-create-page .green-icon {
    border-color: #d1e5d5;
    background: #e9f3e8;
    color: var(--green);
}

.sports-achievement-create-page .form-section-title h3 {
    margin: 0;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 17px;
    font-weight: 600;
    line-height: 1.4;
}

.sports-achievement-create-page .form-section-title p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

.sports-achievement-create-page .section-step {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    min-width: 34px;
    border: 1px solid #e7dfcc;
    border-radius: 8px;
    background: var(--chalk);
    color: var(--wood);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 12px;
    font-weight: 700;
}

.sports-achievement-create-page .form-card-body {
    padding: 23px;
}

/* =========================================================
   FORM GRID AND FIELDS
========================================================= */

.sports-achievement-create-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 21px 24px;
}

.sports-achievement-create-page .form-group {
    min-width: 0;
    margin: 0;
}

.sports-achievement-create-page .form-group.full-width {
    grid-column: 1 / -1;
}

.sports-achievement-create-page .form-group > label {
    display: block;
    margin: 0 0 8px;
    color: var(--ink-2);
    font-size: 11px;
    font-weight: 750;
    line-height: 1.5;
}

.sports-achievement-create-page .required {
    margin-left: 2px;
    color: var(--red);
}

.sports-achievement-create-page .input-with-icon {
    position: relative;
}

.sports-achievement-create-page .input-with-icon > i {
    position: absolute;
    top: 50%;
    left: 13px;
    z-index: 2;
    transform: translateY(-50%);
    color: #948d7c;
    font-size: 11px;
    pointer-events: none;
    transition: color .18s ease;
}

.sports-achievement-create-page .input-with-icon:focus-within > i {
    color: var(--green);
}

.sports-achievement-create-page .input-with-icon .form-control {
    padding-left: 36px;
}

.sports-achievement-create-page .form-control {
    display: block;
    width: 100%;
    min-width: 0;
    height: 43px;
    padding: 10px 12px;
    border: 1px solid #dedbce;
    border-radius: 8px;
    outline: none;
    background: #fffefa;
    color: var(--text);
    font-family: inherit;
    font-size: 12px;
    line-height: 1.5;
    box-shadow: none !important;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
}

.sports-achievement-create-page .form-control::placeholder {
    color: #a49f91;
    opacity: 1;
}

.sports-achievement-create-page .form-control:hover {
    border-color: #c5c0af;
}

.sports-achievement-create-page .form-control:focus {
    border-color: var(--green);
    background: #fffefa;
    box-shadow: 0 0 0 3px rgba(58, 157, 107, .1) !important;
}

.sports-achievement-create-page .form-control.is-invalid {
    border-color: var(--red);
    background-image: none;
}

.sports-achievement-create-page .form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(201, 79, 61, .1) !important;
}

.sports-achievement-create-page select.form-control {
    padding-right: 30px;
    cursor: pointer;
}

.sports-achievement-create-page .textarea-control {
    min-height: 115px;
    height: auto;
    padding: 12px 13px;
    resize: vertical;
    line-height: 1.7;
}

.sports-achievement-create-page .field-error {
    margin-top: 6px;
    color: #b84739;
    font-size: 10.5px;
    font-weight: 650;
    line-height: 1.5;
}

/* =========================================================
   STATUS OPTIONS
========================================================= */

.sports-achievement-create-page .status-options {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}

.sports-achievement-create-page .status-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: #fffefa;
    cursor: pointer;
    transition: border-color .18s ease, background .18s ease, box-shadow .18s ease;
}

.sports-achievement-create-page .status-option:hover {
    border-color: #c6d4c5;
    background: #fbfcf6;
}

.sports-achievement-create-page .status-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.sports-achievement-create-page .custom-radio {
    position: relative;
    width: 19px;
    height: 19px;
    min-width: 19px;
    border: 2px solid #c6c2b4;
    border-radius: 50%;
    background: #fffefa;
    transition: border-color .18s ease;
}

.sports-achievement-create-page .status-option input:checked + .custom-radio {
    border-color: var(--green);
}

.sports-achievement-create-page .status-option input:checked + .custom-radio::after {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--green);
    content: "";
}

.sports-achievement-create-page .status-option:has(input:checked) {
    border-color: #b9d3b8;
    background: #f5f8ef;
    box-shadow: 0 0 0 2px rgba(58, 157, 107, .04);
}

.sports-achievement-create-page .status-option-content {
    display: flex;
    flex: 1;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.sports-achievement-create-page .status-option-content strong {
    color: var(--ink);
    font-size: 12px;
    font-weight: 750;
}

.sports-achievement-create-page .status-option-content small {
    color: var(--muted);
    font-size: 10px;
    line-height: 1.5;
}

.sports-achievement-create-page .status-indicator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 23px;
    height: 23px;
    min-width: 23px;
    border-radius: 50%;
    background: #eaf2e5;
    color: var(--green);
    font-size: 9px;
    opacity: 0;
    transform: scale(.8);
    transition: all .18s ease;
}

.sports-achievement-create-page .status-option input:checked ~ .status-indicator {
    opacity: 1;
    transform: scale(1);
}

.sports-achievement-create-page .active-option input:checked ~ .status-indicator {
    background: #e1f0df;
    color: #2c7c4f;
}

.sports-achievement-create-page .inactive-option input:checked ~ .status-indicator {
    background: #f8e9e4;
    color: var(--red);
}

.sports-achievement-create-page .status-option input:focus-visible + .custom-radio {
    outline: 3px solid rgba(58, 157, 107, .18);
    outline-offset: 3px;
}

/* =========================================================
   FORM ACTIONS
========================================================= */

.sports-achievement-create-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 11px;
    margin-top: 4px;
    padding: 2px 0 14px;
}

.sports-achievement-create-page .cancel-btn,
.sports-achievement-create-page .save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 43px;
    padding: 0 19px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 750;
    text-decoration: none !important;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.sports-achievement-create-page .cancel-btn {
    border: 1px solid var(--line);
    background: var(--paper);
    color: var(--ink) !important;
}

.sports-achievement-create-page .cancel-btn:hover {
    border-color: #c9c2af;
    background: var(--chalk);
    color: var(--ink-2) !important;
}

.sports-achievement-create-page .save-btn {
    border: 1px solid var(--ink);
    background: var(--ink);
    color: #fff9e9;
    box-shadow: 0 5px 13px rgba(38, 59, 53, .13);
}

.sports-achievement-create-page .save-btn:hover {
    border-color: var(--ink-2);
    background: var(--ink-2);
    color: #fff9e9;
    transform: translateY(-1px);
    box-shadow: 0 8px 17px rgba(38, 59, 53, .18);
}

.sports-achievement-create-page .save-btn:active {
    transform: translateY(0);
}

.sports-achievement-create-page .save-arrow {
    margin-left: 3px;
    font-size: 10px;
    transition: transform .18s ease;
}

.sports-achievement-create-page .save-btn:hover .save-arrow {
    transform: translateX(3px);
}

/* =========================================================
   RESPONSIVE DESIGN
========================================================= */

@media (max-width: 1100px) {
    .sports-achievement-create-page .container-fluid {
        padding-right: 20px;
        padding-left: 20px;
    }

    .sports-achievement-create-page .form-grid {
        gap: 18px 20px;
    }

    .sports-achievement-create-page .header-badge {
        min-width: 135px;
        padding-right: 18px;
        padding-left: 18px;
    }
}

@media (max-width: 900px) {
    .sports-achievement-create-page .achievement-create-header {
        gap: 0;
    }

    .sports-achievement-create-page .create-heading-area {
        padding: 20px;
    }

    .sports-achievement-create-page .header-badge {
        min-width: 115px;
        padding: 15px;
    }

    .sports-achievement-create-page .create-heading-area h1 {
        font-size: 25px;
    }
}

@media (max-width: 768px) {
    .sports-achievement-create-page .container-fluid {
        padding: 18px 16px 24px;
    }

    .sports-achievement-create-page .achievement-create-header {
        flex-direction: column;
    }

    .sports-achievement-create-page .create-heading-area {
        align-items: flex-start;
        gap: 10px;
        padding: 20px 16px;
    }

    .sports-achievement-create-page .header-badge {
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        gap: 9px;
        min-width: 0;
        padding: 11px 17px;
        border-top: 1px solid #38544a;
        border-left: 0;
    }

    .sports-achievement-create-page .header-badge i {
        width: 27px;
        height: 27px;
    }

    .sports-achievement-create-page .create-heading-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        font-size: 17px;
    }

    .sports-achievement-create-page .create-heading-area h1 {
        font-size: 23px;
    }

    .sports-achievement-create-page .form-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 17px;
    }

    .sports-achievement-create-page .form-group.full-width {
        grid-column: auto;
    }

    .sports-achievement-create-page .status-options {
        grid-template-columns: minmax(0, 1fr);
    }

    .sports-achievement-create-page .form-card-body {
        padding: 18px;
    }

    .sports-achievement-create-page .form-card-header {
        padding: 16px 18px;
    }
}

@media (max-width: 480px) {
    .sports-achievement-create-page .container-fluid {
        padding-right: 12px;
        padding-left: 12px;
    }

    .sports-achievement-create-page .achievement-create-header {
        margin-bottom: 17px;
    }

    .sports-achievement-create-page .create-heading-area {
        gap: 8px;
        padding: 17px 12px;
    }

    .sports-achievement-create-page .back-btn {
        width: 31px;
        height: 31px;
        min-width: 31px;
        border-radius: 7px;
    }

    .sports-achievement-create-page .create-heading-icon {
        width: 35px;
        height: 35px;
        min-width: 35px;
        border-radius: 8px;
        font-size: 14px;
    }

    .sports-achievement-create-page .create-breadcrumb {
        gap: 5px;
        font-size: 8px;
        letter-spacing: .8px;
    }

    .sports-achievement-create-page .create-heading-area h1 {
        font-size: 20px;
    }

    .sports-achievement-create-page .create-heading-area p {
        font-size: 10px;
    }

    .sports-achievement-create-page .form-card {
        margin-bottom: 15px;
        border-radius: 10px;
    }

    .sports-achievement-create-page .form-card-header {
        padding: 13px;
    }

    .sports-achievement-create-page .form-card-body {
        padding: 14px;
    }

    .sports-achievement-create-page .section-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 8px;
        font-size: 13px;
    }

    .sports-achievement-create-page .form-section-title {
        gap: 9px;
    }

    .sports-achievement-create-page .form-section-title h3 {
        font-size: 15px;
    }

    .sports-achievement-create-page .form-section-title p {
        font-size: 9px;
    }

    .sports-achievement-create-page .section-step {
        width: 27px;
        height: 27px;
        min-width: 27px;
        font-size: 10px;
    }

    .sports-achievement-create-page .form-actions {
        align-items: stretch;
        gap: 8px;
    }

    .sports-achievement-create-page .cancel-btn,
    .sports-achievement-create-page .save-btn {
        flex: 1;
        min-width: 0;
        min-height: 41px;
        padding: 0 9px;
        gap: 6px;
        font-size: 10px;
    }

    .sports-achievement-create-page .save-arrow {
        display: none;
    }
}

/* ACCESSIBILITY */

@media (prefers-reduced-motion: reduce) {
    .sports-achievement-create-page *,
    .sports-achievement-create-page *::before,
    .sports-achievement-create-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

@endsection
