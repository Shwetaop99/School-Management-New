@extends('layouts.app')

@section('title', 'Edit Achievement | Admin')

@section('content')

<div class="sports-achievement-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}
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

                    <p>
                        Update and manage the selected sports achievement information.
                    </p>

                </div>

            </div>

            <a href="{{ route('admin.sports.achievements.show', $achievement) }}"
               class="achievement-back-btn">

                <i class="fas fa-arrow-left"></i>

                <span>Back to Details</span>

            </a>

        </div>


        {{-- =========================================================
             VALIDATION ERRORS
        ========================================================== --}}
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


        {{-- =========================================================
             MAIN FORM CARD
        ========================================================== --}}
        <div class="achievement-form-card">

            <form action="{{ route('admin.sports.achievements.update', $achievement) }}"
                  method="POST">

                @csrf
                @method('PUT')


                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}
                <div class="form-section">

                    <div class="form-section-header">

                        <div class="form-section-icon blue">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div class="form-section-heading">

                            <div class="section-title-row">

                                <h4>Basic Information</h4>

                                <span class="section-badge blue-badge">
                                    Required Details
                                </span>

                            </div>

                            <p>
                                Update the primary information related to this achievement.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- ACHIEVEMENT TITLE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Achievement Title
                                <span class="required">*</span>
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-heading input-icon"></i>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control with-icon @error('title') is-invalid @enderror"
                                    value="{{ old('title', $achievement->title) }}"
                                    placeholder="Enter achievement title"
                                    required
                                >

                            </div>

                            @error('title')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- STUDENT --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Student Name
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-user input-icon"></i>

                                <input
                                    type="text"
                                    name="student_name"
                                    class="form-control with-icon @error('student_name') is-invalid @enderror"
                                    value="{{ old('student_name', $achievement->student_name) }}"
                                    placeholder="Enter student name"
                                >

                            </div>

                            @error('student_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- SPORT --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Sport
                                <span class="required">*</span>
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-futbol input-icon"></i>

                                <input
                                    type="text"
                                    name="sport_name"
                                    class="form-control with-icon @error('sport_name') is-invalid @enderror"
                                    value="{{ old('sport_name', $achievement->sport_name) }}"
                                    placeholder="e.g. Cricket, Football, Athletics"
                                    required
                                >

                            </div>

                            @error('sport_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ACHIEVEMENT TYPE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Achievement Type
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-medal input-icon"></i>

                                <input
                                    type="text"
                                    name="achievement_type"
                                    class="form-control with-icon @error('achievement_type') is-invalid @enderror"
                                    value="{{ old('achievement_type', $achievement->achievement_type) }}"
                                    placeholder="e.g. Tournament, Championship"
                                >

                            </div>

                            @error('achievement_type')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- POSITION --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Position / Award
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-award input-icon"></i>

                                <input
                                    type="text"
                                    name="position"
                                    class="form-control with-icon @error('position') is-invalid @enderror"
                                    value="{{ old('position', $achievement->position) }}"
                                    placeholder="e.g. 1st Place, Gold Medal"
                                >

                            </div>

                            @error('position')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ACADEMIC YEAR --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Academic Year
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-calendar-alt input-icon"></i>

                                <input
                                    type="text"
                                    name="academic_year"
                                    class="form-control with-icon @error('academic_year') is-invalid @enderror"
                                    value="{{ old('academic_year', $achievement->academic_year) }}"
                                    placeholder="e.g. 2026-27"
                                >

                            </div>

                            @error('academic_year')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STUDENT / CLASS INFORMATION
                ================================================== --}}
                <div class="form-section">

                    <div class="form-section-header">

                        <div class="form-section-icon cyan">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div class="form-section-heading">

                            <div class="section-title-row">

                                <h4>Student & Class Information</h4>

                                <span class="section-badge cyan-badge">
                                    Academic Details
                                </span>

                            </div>

                            <p>
                                Update the student's class and section information.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- CLASS --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Class
                            </label>

                            <div class="input-wrapper select-wrapper">

                                <i class="fas fa-school input-icon"></i>

                                <select
                                    name="class"
                                    id="class"
                                    class="form-select with-icon @error('class') is-invalid @enderror"
                                >

                                    <option value="">
                                        Select Class
                                    </option>

                                    @foreach($classes->unique('class_name') as $class)

                                        <option
                                            value="{{ $class->class_name }}"
                                            @selected(old('class', $achievement->class) == $class->class_name)
                                        >
                                            {{ $class->class_name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            @error('class')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- SECTION --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Section
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-layer-group input-icon"></i>

                                <input
                                    type="text"
                                    name="section"
                                    class="form-control with-icon @error('section') is-invalid @enderror"
                                    value="{{ old('section', $achievement->section) }}"
                                    placeholder="Enter section e.g. A, B, C"
                                >

                            </div>

                            @error('section')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     COMPETITION INFORMATION
                ================================================== --}}
                <div class="form-section">

                    <div class="form-section-header">

                        <div class="form-section-icon orange">
                            <i class="fas fa-trophy"></i>
                        </div>

                        <div class="form-section-heading">

                            <div class="section-title-row">

                                <h4>Competition Information</h4>

                                <span class="section-badge orange-badge">
                                    Event Details
                                </span>

                            </div>

                            <p>
                                Update competition, venue, date and achievement status.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- COMPETITION --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Competition Name
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-flag-checkered input-icon"></i>

                                <input
                                    type="text"
                                    name="competition_name"
                                    class="form-control with-icon @error('competition_name') is-invalid @enderror"
                                    value="{{ old('competition_name', $achievement->competition_name) }}"
                                    placeholder="Enter competition name"
                                >

                            </div>

                            @error('competition_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- DATE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Achievement Date
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-calendar-day input-icon"></i>

                                <input
                                    type="date"
                                    name="achievement_date"
                                    class="form-control with-icon @error('achievement_date') is-invalid @enderror"
                                    value="{{ old(
                                        'achievement_date',
                                        $achievement->achievement_date
                                            ? $achievement->achievement_date->format('Y-m-d')
                                            : ''
                                    ) }}"
                                >

                            </div>

                            @error('achievement_date')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- VENUE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Venue
                            </label>

                            <div class="input-wrapper">

                                <i class="fas fa-map-marker-alt input-icon"></i>

                                <input
                                    type="text"
                                    name="venue"
                                    class="form-control with-icon @error('venue') is-invalid @enderror"
                                    value="{{ old('venue', $achievement->venue) }}"
                                    placeholder="Enter venue"
                                >

                            </div>

                            @error('venue')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- STATUS --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Achievement Status
                                <span class="required">*</span>
                            </label>

                            <div class="status-options">

                                {{-- ACTIVE --}}
                                <label class="status-option active-option">

                                    <input
                                        type="radio"
                                        name="status"
                                        value="active"
                                        @checked(old('status', $achievement->status) === 'active')
                                    >

                                    <span class="status-radio"></span>

                                    <span class="status-option-content">

                                        <strong>
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </strong>

                                        <small>
                                            Achievement is currently active
                                        </small>

                                    </span>

                                </label>


                                {{-- INACTIVE --}}
                                <label class="status-option inactive-option">

                                    <input
                                        type="radio"
                                        name="status"
                                        value="inactive"
                                        @checked(old('status', $achievement->status) === 'inactive')
                                    >

                                    <span class="status-radio"></span>

                                    <span class="status-option-content">

                                        <strong>
                                            <i class="fas fa-pause-circle"></i>
                                            Inactive
                                        </strong>

                                        <small>
                                            Achievement is currently inactive
                                        </small>

                                    </span>

                                </label>

                            </div>

                            @error('status')

                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DESCRIPTION
                ================================================== --}}
                <div class="form-section">

                    <div class="form-section-header">

                        <div class="form-section-icon red">
                            <i class="fas fa-align-left"></i>
                        </div>

                        <div class="form-section-heading">

                            <div class="section-title-row">

                                <h4>Description</h4>

                                <span class="section-badge red-badge">
                                    Additional Information
                                </span>

                            </div>

                            <p>
                                Add additional information, notes or details about the achievement.
                            </p>

                        </div>

                    </div>


                    <div class="description-wrapper">

                        <textarea
                            name="description"
                            rows="5"
                            class="form-control description-control @error('description') is-invalid @enderror"
                            placeholder="Enter achievement description..."
                        >{{ old('description', $achievement->description) }}</textarea>

                        <div class="description-helper">
                            <i class="fas fa-info-circle"></i>
                            Provide a clear description of the student's achievement or accomplishment.
                        </div>

                    </div>

                    @error('description')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =================================================
                     FORM FOOTER
                ================================================== --}}
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

                        <button type="submit"
                                class="update-btn">

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
       MAIN PAGE
    ========================================================== */

    .sports-achievement-page {
        min-height: calc(100vh - 60px);
        background: #f6f8fb;
        color: #172033;
        font-family: 'Inter', sans-serif;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .achievement-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 24px;
    }

    .achievement-heading-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }

    .achievement-page-icon {
        width: 52px;
        height: 52px;
        flex: 0 0 52px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        box-shadow: 0 7px 18px rgba(20, 124, 245, .20);
    }

    .achievement-heading-content {
        min-width: 0;
    }

    .achievement-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 5px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .achievement-breadcrumb i {
        font-size: 8px;
        color: #cbd5e1;
    }

    .achievement-breadcrumb strong {
        color: #147cf5;
        font-weight: 700;
    }

    .achievement-heading-wrapper h2 {
        margin: 0;
        color: #172033;
        font-size: 24px;
        font-weight: 750;
        letter-spacing: -.3px;
    }

    .achievement-heading-wrapper p {
        margin: 5px 0 0;
        color: #7b8798;
        font-size: 13px;
        line-height: 1.5;
    }


    /* =========================================================
       BACK BUTTON
    ========================================================== */

    .achievement-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-shrink: 0;
        min-height: 40px;
        padding: 9px 15px;
        border: 1px solid #e3e8ef;
        border-radius: 9px;
        background: #fff;
        color: #596579;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: all .2s ease;
        box-shadow: 0 2px 6px rgba(15, 23, 42, .03);
    }

    .achievement-back-btn i {
        font-size: 11px;
        transition: transform .2s ease;
    }

    .achievement-back-btn:hover {
        background: #f8fbff;
        border-color: #cfe2fb;
        color: #147cf5;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(15, 23, 42, .06);
    }

    .achievement-back-btn:hover i {
        transform: translateX(-2px);
    }


    /* =========================================================
       ERROR ALERT
    ========================================================== */

    .achievement-error-alert {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-bottom: 20px;
        padding: 15px 17px;
        border: 1px solid #ffd8d4;
        border-radius: 12px;
        background: #fff8f7;
        color: #9f241b;
        box-shadow: 0 3px 12px rgba(246, 83, 67, .04);
    }

    .error-alert-icon {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #ffe8e5;
        color: #f65343;
        font-size: 14px;
    }

    .error-alert-content {
        min-width: 0;
    }

    .error-title {
        color: #9f241b;
        font-size: 13px;
        font-weight: 700;
    }

    .achievement-error-alert ul {
        margin: 6px 0 0;
        padding-left: 17px;
        color: #b42318;
        font-size: 12px;
        line-height: 1.7;
    }


    /* =========================================================
       FORM CARD
    ========================================================== */

    .achievement-form-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e9edf3;
        border-radius: 17px;
        box-shadow:
            0 4px 10px rgba(15, 23, 42, .025),
            0 10px 30px rgba(15, 23, 42, .045);
    }


    /* =========================================================
       FORM SECTIONS
    ========================================================== */

    .form-section {
        padding: 27px 28px 30px;
        border-bottom: 1px solid #edf0f5;
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .form-section-header {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 24px;
    }

    .form-section-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        font-size: 15px;
    }

    .form-section-icon.blue {
        background: #edf5ff;
        color: #147cf5;
    }

    .form-section-icon.cyan {
        background: #e9fbfe;
        color: #18b5d5;
    }

    .form-section-icon.orange {
        background: #fff6e8;
        color: #f59e0b;
    }

    .form-section-icon.red {
        background: #fff0ee;
        color: #f65343;
    }

    .form-section-heading {
        min-width: 0;
    }

    .section-title-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
    }

    .form-section-header h4 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 750;
        letter-spacing: -.1px;
    }

    .form-section-header p {
        margin: 4px 0 0;
        color: #94a3b8;
        font-size: 11.5px;
        line-height: 1.5;
    }


    /* =========================================================
       SECTION BADGES
    ========================================================== */

    .section-badge {
        display: inline-flex;
        align-items: center;
        min-height: 21px;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .blue-badge {
        background: #edf5ff;
        color: #147cf5;
    }

    .cyan-badge {
        background: #e9fbfe;
        color: #159db9;
    }

    .orange-badge {
        background: #fff6e8;
        color: #d88700;
    }

    .red-badge {
        background: #fff0ee;
        color: #df4c3d;
    }


    /* =========================================================
       LABELS
    ========================================================== */

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #344054;
        font-size: 11.5px;
        font-weight: 700;
    }

    .required {
        margin-left: 2px;
        color: #f65343;
    }


    /* =========================================================
       INPUTS
    ========================================================== */

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 13px;
        width: 16px;
        color: #9aa6b6;
        font-size: 12px;
        text-align: center;
        pointer-events: none;
        transform: translateY(-50%);
        transition: color .2s ease;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        outline: none;
        background-color: #fff;
        color: #172033;
        font-size: 12.5px;
        box-shadow: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background-color .2s ease;
    }

    .form-control::placeholder {
        color: #aab4c2;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #cbd5e1;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #147cf5;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .09);
    }

    .input-wrapper:focus-within .input-icon {
        color: #147cf5;
    }

    .form-control.with-icon,
    .form-select.with-icon {
        padding-left: 39px;
    }

    .form-select.with-icon {
        cursor: pointer;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #f65343;
    }

    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus {
        border-color: #f65343;
        box-shadow: 0 0 0 3px rgba(246, 83, 67, .08);
    }

    .invalid-feedback {
        margin-top: 5px;
        color: #dc4435;
        font-size: 10.5px;
        font-weight: 600;
    }


    /* =========================================================
       STATUS OPTIONS
    ========================================================== */

    .status-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .status-option {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 66px;
        padding: 10px 12px;
        overflow: hidden;
        border: 1px solid #e4e9ef;
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
        transition:
            border-color .2s ease,
            background .2s ease,
            box-shadow .2s ease,
            transform .2s ease;
    }

    .status-option:hover {
        border-color: #cbd5e1;
        background: #fafcfe;
        transform: translateY(-1px);
    }

    .status-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .status-radio {
        position: relative;
        width: 17px;
        height: 17px;
        flex: 0 0 17px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        transition: all .2s ease;
    }

    .status-option input:checked + .status-radio {
        border-color: #147cf5;
    }

    .status-option input:checked + .status-radio::after {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #147cf5;
        content: '';
    }

    .status-option:has(input:checked) {
        border-color: #b9d8fb;
        background: #f8fbff;
        box-shadow: 0 4px 12px rgba(20, 124, 245, .06);
    }

    .inactive-option:has(input:checked) {
        border-color: #d9dee6;
        background: #fafbfc;
        box-shadow: none;
    }

    .status-option-content {
        min-width: 0;
    }

    .status-option strong {
        display: block;
        color: #172033;
        font-size: 11.5px;
        font-weight: 750;
    }

    .status-option strong i {
        margin-right: 4px;
        color: #147cf5;
        font-size: 10px;
    }

    .inactive-option strong i {
        color: #94a3b8;
    }

    .status-option small {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 9.5px;
        line-height: 1.35;
    }


    /* =========================================================
       DESCRIPTION
    ========================================================== */

    .description-wrapper {
        position: relative;
    }

    .description-control {
        min-height: 135px !important;
        padding: 13px 14px !important;
        line-height: 1.6;
        resize: vertical;
    }

    .description-helper {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        color: #94a3b8;
        font-size: 10.5px;
    }

    .description-helper i {
        color: #147cf5;
        font-size: 10px;
    }


    /* =========================================================
       FORM FOOTER
    ========================================================== */

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 28px;
        border-top: 1px solid #edf0f5;
        background: #fafbfd;
    }

    .form-footer-info {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
    }

    .save-status-icon {
        width: 31px;
        height: 31px;
        flex: 0 0 31px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #edf5ff;
        color: #147cf5;
        font-size: 11px;
    }

    .form-footer-info strong {
        display: block;
        color: #344054;
        font-size: 11px;
        font-weight: 700;
    }

    .form-footer-info span {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 9.5px;
    }

    .form-footer-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
    }


    /* =========================================================
       CANCEL BUTTON
    ========================================================== */

    .cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 16px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #fff;
        color: #64748b;
        text-decoration: none;
        font-size: 11.5px;
        font-weight: 700;
        transition: all .2s ease;
    }

    .cancel-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #172033;
        transform: translateY(-1px);
    }


    /* =========================================================
       UPDATE BUTTON
    ========================================================== */

    .update-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 9px 18px;
        border: none;
        border-radius: 9px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #fff;
        font-size: 11.5px;
        font-weight: 700;
        box-shadow: 0 5px 14px rgba(20, 124, 245, .18);
        transition: all .2s ease;
    }

    .update-btn:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 7px 17px rgba(20, 124, 245, .25);
    }

    .update-btn:active {
        transform: translateY(0);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 991px) {

        .achievement-page-header {
            align-items: flex-start;
        }

        .status-options {
            grid-template-columns: 1fr;
        }

        .form-footer-info {
            display: none;
        }

        .form-footer {
            justify-content: flex-end;
        }

    }


    @media (max-width: 768px) {

        .sports-achievement-page .container-fluid {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        .achievement-page-header {
            flex-direction: column;
            align-items: stretch;
            margin-bottom: 20px;
        }

        .achievement-heading-wrapper {
            align-items: flex-start;
        }

        .achievement-page-icon {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            border-radius: 13px;
            font-size: 18px;
        }

        .achievement-heading-wrapper h2 {
            font-size: 21px;
        }

        .achievement-heading-wrapper p {
            font-size: 12px;
        }

        .achievement-back-btn {
            width: 100%;
        }

        .form-section {
            padding: 22px 18px 24px;
        }

        .form-section-header {
            margin-bottom: 20px;
        }

        .form-footer {
            padding: 16px 18px;
        }

    }


    @media (max-width: 575px) {

        .achievement-breadcrumb {
            font-size: 9px;
        }

        .achievement-heading-wrapper h2 {
            font-size: 19px;
        }

        .achievement-heading-wrapper p {
            font-size: 11px;
        }

        .section-title-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 6px;
        }

        .form-section-icon {
            width: 38px;
            height: 38px;
            flex-basis: 38px;
            border-radius: 9px;
        }

        .form-section-header {
            align-items: flex-start;
        }

        .status-options {
            grid-template-columns: 1fr;
        }

        .form-footer {
            padding: 15px;
        }

        .form-footer-actions {
            width: 100%;
            flex-direction: column-reverse;
        }

        .form-footer-actions .btn,
        .form-footer-actions a,
        .form-footer-actions button {
            width: 100%;
        }

    }


    @media (max-width: 400px) {

        .achievement-heading-wrapper {
            gap: 10px;
        }

        .achievement-page-icon {
            width: 42px;
            height: 42px;
            flex-basis: 42px;
            font-size: 16px;
        }

        .form-section {
            padding: 20px 14px;
        }

        .achievement-form-card {
            border-radius: 13px;
        }

    }

</style>

@endsection