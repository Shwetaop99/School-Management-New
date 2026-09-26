@extends('layouts.app')

@section('title', 'Add Game / Event | Admin')

@section('content')

<div class="sports-game-create-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}

        <div class="sports-create-header mb-4">

            <div class="header-left">

                <div class="header-icon">
                    <i class="fas fa-futbol"></i>
                </div>

                <div class="header-content">

                    <div class="breadcrumb-text">
                        <span>Sports Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Add Game / Event</span>
                    </div>

                    <h2>Add Game / Event</h2>

                    <p>
                        Create and schedule a new sports game, competition or school event.
                    </p>

                </div>

            </div>

            <a href="{{ route('admin.sports.games.index') }}" class="back-btn">

                <span class="back-icon">
                    <i class="fas fa-arrow-left"></i>
                </span>

                <span>Back to Games</span>

            </a>

        </div>


        {{-- =========================================================
             VALIDATION ERRORS
        ========================================================== --}}

        @if ($errors->any())

            <div class="sports-alert mb-4">

                <div class="alert-symbol">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="alert-content">

                    <strong>Please review the following errors</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

                <button
                    type="button"
                    class="alert-close"
                    onclick="this.parentElement.remove()"
                >
                    <i class="fas fa-times"></i>
                </button>

            </div>

        @endif


        {{-- =========================================================
             MAIN FORM
        ========================================================== --}}

        <form action="{{ route('admin.sports.games.store') }}" method="POST">

            @csrf


            {{-- =====================================================
                 BASIC INFORMATION
            ====================================================== --}}

            <div class="sports-form-card mb-4">

                <div class="card-section-header">

                    <div class="section-heading">

                        <div class="section-icon blue">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>

                            <h5>Basic Information</h5>

                            <p>
                                Provide the basic details of the sports activity.
                            </p>

                        </div>

                    </div>

                    <span class="section-number">01</span>

                </div>


                <div class="card-section-body">

                    <div class="row g-4">


                        {{-- Event Title --}}

                        <div class="col-lg-6">

                            <label class="field-label">
                                Event Title
                                <span>*</span>
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-heading"></i>
                                </div>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    value="{{ old('title') }}"
                                    placeholder="e.g. Inter School Football Match"
                                    required
                                >

                            </div>

                        </div>


                        {{-- Sport Name --}}

                        <div class="col-lg-6">

                            <label class="field-label">
                                Sport Name
                                <span>*</span>
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-running"></i>
                                </div>

                                <input
                                    type="text"
                                    name="sport_name"
                                    class="form-control"
                                    value="{{ old('sport_name') }}"
                                    placeholder="e.g. Football, Cricket, Basketball"
                                    required
                                >

                            </div>

                        </div>


                        {{-- Event Type --}}

                        <div class="col-lg-4">

                            <label class="field-label">
                                Event Type
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-layer-group"></i>
                                </div>

                                <select name="event_type" class="form-control">

                                    <option value="">
                                        Select Event Type
                                    </option>

                                    <option value="Match"
                                        {{ old('event_type') == 'Match' ? 'selected' : '' }}>
                                        Match
                                    </option>

                                    <option value="Tournament"
                                        {{ old('event_type') == 'Tournament' ? 'selected' : '' }}>
                                        Tournament
                                    </option>

                                    <option value="Competition"
                                        {{ old('event_type') == 'Competition' ? 'selected' : '' }}>
                                        Competition
                                    </option>

                                    <option value="Practice"
                                        {{ old('event_type') == 'Practice' ? 'selected' : '' }}>
                                        Practice
                                    </option>

                                    <option value="Sports Day"
                                        {{ old('event_type') == 'Sports Day' ? 'selected' : '' }}>
                                        Sports Day
                                    </option>

                                    <option value="Other"
                                        {{ old('event_type') == 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                </select>

                                <div class="select-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             ACADEMIC YEAR - MANUAL INPUT
                        ================================================== --}}

                        <div class="col-lg-4">

                            <label class="field-label">
                                Academic Year
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>

                                <input
                                    type="text"
                                    name="academic_year"
                                    id="academic_year"
                                    class="form-control"
                                    value="{{ old('academic_year') }}"
                                    placeholder="e.g. 2026-27"
                                    autocomplete="off"
                                >

                            </div>

                            <div class="field-helper">
                                <i class="fas fa-info-circle"></i>
                                Enter academic year manually
                            </div>

                        </div>


                        {{-- Status --}}

                        <div class="col-lg-4">

                            <label class="field-label">
                                Status
                                <span>*</span>
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix status-prefix">
                                    <i class="fas fa-circle"></i>
                                </div>

                                <select name="status" class="form-control" required>

                                    <option value="upcoming"
                                        {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>
                                        Upcoming
                                    </option>

                                    <option value="ongoing"
                                        {{ old('status') == 'ongoing' ? 'selected' : '' }}>
                                        Ongoing
                                    </option>

                                    <option value="completed"
                                        {{ old('status') == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled"
                                        {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>

                                <div class="select-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ACADEMIC INFORMATION
            ====================================================== --}}

            <div class="sports-form-card mb-4">

                <div class="card-section-header">

                    <div class="section-heading">

                        <div class="section-icon cyan">
                            <i class="fas fa-school"></i>
                        </div>

                        <div>

                            <h5>Academic Information</h5>

                            <p>
                                Select the class and participating section.
                            </p>

                        </div>

                    </div>

                    <span class="section-number">02</span>

                </div>


                <div class="card-section-body">

                    <div class="row g-4">


                        {{-- Class --}}

                        <div class="col-lg-6">

                            <label class="field-label">
                                Class
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>

                                <select
                                    name="class"
                                    id="class"
                                    class="form-control"
                                >

                                    <option value="">
                                        Select Class
                                    </option>

                                    @php
                                        $classNames = [];
                                    @endphp

                                    @foreach ($classes as $classRecord)

                                        @if (
                                            $classRecord->class_name &&
                                            !in_array($classRecord->class_name, $classNames)
                                        )

                                            @php
                                                $classNames[] = $classRecord->class_name;
                                            @endphp

                                            <option
                                                value="{{ $classRecord->class_name }}"
                                                {{ old('class') == $classRecord->class_name ? 'selected' : '' }}
                                            >
                                                {{ $classRecord->class_name }}
                                            </option>

                                        @endif

                                    @endforeach

                                </select>

                                <div class="select-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </div>

                            </div>

                            <div class="field-helper">
                                <i class="fas fa-link"></i>
                                Classes are loaded from Class module
                            </div>

                        </div>


                        {{-- Section --}}

                        <div class="col-lg-6">

                            <label class="field-label">
                                Section
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-users"></i>
                                </div>

                                <input
                                    type="text"
                                    name="section"
                                    id="section"
                                    class="form-control"
                                    value="{{ old('section') }}"
                                    placeholder="e.g. A, B, C"
                                >

                            </div>

                            <div class="field-helper">
                                <i class="fas fa-info-circle"></i>
                                Enter the participating section
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 SCHEDULE & VENUE
            ====================================================== --}}

            <div class="sports-form-card mb-4">

                <div class="card-section-header">

                    <div class="section-heading">

                        <div class="section-icon orange">
                            <i class="fas fa-calendar-check"></i>
                        </div>

                        <div>

                            <h5>Schedule & Venue</h5>

                            <p>
                                Set the date, timing and location of the event.
                            </p>

                        </div>

                    </div>

                    <span class="section-number">03</span>

                </div>


                <div class="card-section-body">

                    <div class="row g-4">


                        {{-- Event Date --}}

                        <div class="col-lg-4">

                            <label class="field-label">
                                Event Date
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-calendar-day"></i>
                                </div>

                                <input
                                    type="date"
                                    name="event_date"
                                    class="form-control"
                                    value="{{ old('event_date') }}"
                                >

                            </div>

                        </div>


                        {{-- Start Time --}}

                        <div class="col-lg-4">

                            <label class="field-label">
                                Start Time
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-clock"></i>
                                </div>

                                <input
                                    type="time"
                                    name="start_time"
                                    class="form-control"
                                    value="{{ old('start_time') }}"
                                >

                            </div>

                        </div>


                        {{-- End Time --}}

                        <div class="col-lg-4">

                            <label class="field-label">
                                End Time
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-hourglass-end"></i>
                                </div>

                                <input
                                    type="time"
                                    name="end_time"
                                    class="form-control"
                                    value="{{ old('end_time') }}"
                                >

                            </div>

                        </div>


                        {{-- Venue --}}

                        <div class="col-lg-6">

                            <label class="field-label">
                                Venue
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>

                                <input
                                    type="text"
                                    name="venue"
                                    class="form-control"
                                    value="{{ old('venue') }}"
                                    placeholder="e.g. School Ground, Indoor Hall"
                                >

                            </div>

                        </div>


                        {{-- Organizer --}}

                        <div class="col-lg-6">

                            <label class="field-label">
                                Organizer
                            </label>

                            <div class="modern-input">

                                <div class="input-prefix">
                                    <i class="fas fa-user-tie"></i>
                                </div>

                                <input
                                    type="text"
                                    name="organizer"
                                    class="form-control"
                                    value="{{ old('organizer') }}"
                                    placeholder="Enter organizer name"
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 DESCRIPTION
            ====================================================== --}}

            <div class="sports-form-card mb-4">

                <div class="card-section-header">

                    <div class="section-heading">

                        <div class="section-icon red">
                            <i class="fas fa-align-left"></i>
                        </div>

                        <div>

                            <h5>Description</h5>

                            <p>
                                Add instructions or additional event information.
                            </p>

                        </div>

                    </div>

                    <span class="section-number">04</span>

                </div>


                <div class="card-section-body">

                    <label class="field-label">
                        Event Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control description-field"
                        rows="5"
                        placeholder="Enter event description, rules, instructions or additional information..."
                    >{{ old('description') }}</textarea>

                    <div class="description-footer">

                        <span>
                            <i class="fas fa-info-circle"></i>
                            Keep the description clear and informative.
                        </span>

                        <span id="characterCount">
                            0 characters
                        </span>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 FORM ACTIONS
            ====================================================== --}}

            <div class="form-action-bar">

                <div class="action-note">

                    <div class="action-note-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>

                    <div>

                        <strong>Ready to create this event?</strong>

                        <span>
                            Review the information before saving.
                        </span>

                    </div>

                </div>


                <div class="action-buttons">

                    <a
                        href="{{ route('admin.sports.games.index') }}"
                        class="cancel-btn"
                    >
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        <i class="fas fa-check-circle"></i>
                        Save Game / Event
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
     PAGE CSS
============================================================= --}}

<style>

    /* =========================================================
       BASE
    ========================================================= */

    .sports-game-create-page {
        min-height: calc(100vh - 70px);
        background: #f6f8fb;
        font-family: "Inter", sans-serif;
        color: #172033;
    }

    .sports-game-create-page .container-fluid {
        max-width: 1500px;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .sports-create-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }

    .header-icon {
        width: 52px;
        height: 52px;
        flex: 0 0 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 21px;
        background: linear-gradient(
            135deg,
            #0d6efd,
            #147cf5,
            #1268ca
        );
        box-shadow: 0 8px 20px rgba(20, 124, 245, .20);
    }

    .header-content {
        min-width: 0;
    }

    .breadcrumb-text {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
        color: #94a3b8;
        font-size: 10.5px;
        font-weight: 600;
    }

    .breadcrumb-text i {
        font-size: 8px;
        color: #cbd5e1;
    }

    .sports-create-header h2 {
        margin: 0;
        font-size: 24px;
        line-height: 1.25;
        font-weight: 750;
        letter-spacing: -.4px;
        color: #172033;
    }

    .sports-create-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 12.5px;
        line-height: 1.5;
    }


    /* =========================================================
       BACK BUTTON
    ========================================================= */

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-height: 42px;
        padding: 7px 13px 7px 8px;
        border: 1px solid #e8edf4;
        border-radius: 10px;
        background: #fff;
        color: #475569;
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 650;
        white-space: nowrap;
        transition: all .2s ease;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .back-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f6ff;
        color: #147cf5;
        transition: all .2s ease;
    }

    .back-btn:hover {
        color: #147cf5;
        border-color: #cfe1ff;
        background: #fbfdff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(20, 124, 245, .08);
    }

    .back-btn:hover .back-icon {
        background: #147cf5;
        color: #fff;
    }


    /* =========================================================
       ALERT
    ========================================================= */

    .sports-alert {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 45px 15px 16px;
        border: 1px solid #ffd6d2;
        border-radius: 12px;
        background: #fff7f6;
        color: #7f1d1d;
    }

    .alert-symbol {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0ee;
        color: #f65343;
        font-size: 14px;
    }

    .alert-content {
        font-size: 12px;
        line-height: 1.6;
    }

    .alert-content strong {
        display: block;
        margin-bottom: 3px;
        color: #991b1b;
        font-size: 12.5px;
    }

    .alert-content ul {
        margin: 0;
        padding-left: 18px;
    }

    .alert-close {
        position: absolute;
        top: 13px;
        right: 13px;
        width: 28px;
        height: 28px;
        border: 0;
        border-radius: 7px;
        background: transparent;
        color: #b4534b;
        cursor: pointer;
        transition: .2s ease;
    }

    .alert-close:hover {
        background: #ffe4e1;
        color: #dc2626;
    }


    /* =========================================================
       FORM CARD
    ========================================================= */

    .sports-form-card {
        overflow: hidden;
        border: 1px solid #edf0f5;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .045);
        transition: box-shadow .2s ease;
    }

    .sports-form-card:hover {
        box-shadow: 0 8px 25px rgba(15, 23, 42, .055);
    }


    /* =========================================================
       CARD HEADER
    ========================================================= */

    .card-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 21px;
        border-bottom: 1px solid #edf0f5;
        background: #fff;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-heading h5 {
        margin: 0;
        color: #172033;
        font-size: 14px;
        font-weight: 750;
        letter-spacing: -.1px;
    }

    .section-heading p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11.5px;
    }

    .section-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 25px;
        padding: 0 8px;
        border-radius: 7px;
        background: #f7f9fc;
        color: #a0aec0;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .4px;
    }


    /* =========================================================
       SECTION ICONS
    ========================================================= */

    .section-icon {
        width: 39px;
        height: 39px;
        flex: 0 0 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        font-size: 14px;
    }

    .section-icon.blue {
        color: #147cf5;
        background: #eaf3ff;
    }

    .section-icon.cyan {
        color: #18b5d5;
        background: #e9fbfd;
    }

    .section-icon.orange {
        color: #ff9d1c;
        background: #fff6e5;
    }

    .section-icon.red {
        color: #f65343;
        background: #fff0ee;
    }


    /* =========================================================
       CARD BODY
    ========================================================= */

    .card-section-body {
        padding: 23px;
    }


    /* =========================================================
       LABELS
    ========================================================= */

    .field-label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.4;
    }

    .field-label span {
        color: #f65343;
        margin-left: 2px;
    }


    /* =========================================================
       INPUT WRAPPER
    ========================================================= */

    .modern-input {
        position: relative;
        display: flex;
        align-items: center;
    }

    .modern-input .form-control {
        width: 100%;
        height: 44px;
        padding: 9px 13px 9px 42px;
        border: 1px solid #e1e7ef;
        border-radius: 9px;
        background: #fff;
        color: #172033;
        font-size: 12.5px;
        font-weight: 500;
        box-shadow: none;
        outline: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .modern-input .form-control:hover {
        border-color: #cbd5e1;
    }

    .modern-input .form-control:focus {
        border-color: #147cf5;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .08);
    }

    .modern-input .form-control::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }


    /* =========================================================
       INPUT ICON
    ========================================================= */

    .input-prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        z-index: 3;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
        transition: color .2s ease;
    }

    .modern-input:focus-within .input-prefix {
        color: #147cf5;
    }

    .status-prefix {
        color: #ff9d1c;
    }


    /* =========================================================
       SELECT
    ========================================================= */

    .modern-input select.form-control {
        appearance: none;
        -webkit-appearance: none;
        padding-right: 38px;
        cursor: pointer;
    }

    .select-arrow {
        position: absolute;
        right: 13px;
        top: 50%;
        z-index: 3;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 9px;
        pointer-events: none;
    }

    .modern-input:focus-within .select-arrow {
        color: #147cf5;
    }


    /* =========================================================
       FIELD HELPER
    ========================================================= */

    .field-helper {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 7px;
        color: #94a3b8;
        font-size: 10.5px;
        line-height: 1.4;
    }

    .field-helper i {
        font-size: 9px;
        color: #a8b4c3;
    }


    /* =========================================================
       DESCRIPTION
    ========================================================= */

    .description-field {
        display: block;
        width: 100%;
        min-height: 135px;
        padding: 13px 14px;
        border: 1px solid #e1e7ef;
        border-radius: 10px;
        background: #fff;
        color: #172033;
        font-family: "Inter", sans-serif;
        font-size: 12.5px;
        line-height: 1.65;
        resize: vertical;
        outline: none;
        box-shadow: none;
        transition: .2s ease;
    }

    .description-field:hover {
        border-color: #cbd5e1;
    }

    .description-field:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .08);
    }

    .description-field::placeholder {
        color: #a0aec0;
    }

    .description-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 8px;
        color: #94a3b8;
        font-size: 10.5px;
    }

    .description-footer span:first-child {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .description-footer i {
        color: #147cf5;
        font-size: 9px;
    }


    /* =========================================================
       FORM ACTION BAR
    ========================================================= */

    .form-action-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 15px;
        padding: 14px 16px;
        border: 1px solid #edf0f5;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(15, 23, 42, .035);
    }

    .action-note {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-note-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #eaf3ff;
        color: #147cf5;
        font-size: 12px;
    }

    .action-note strong {
        display: block;
        color: #334155;
        font-size: 11.5px;
        font-weight: 700;
    }

    .action-note span {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 10.5px;
    }


    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .cancel-btn,
    .save-btn {
        min-height: 41px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .cancel-btn {
        color: #475569;
        background: #fff;
        border: 1px solid #e1e7ef;
    }

    .cancel-btn:hover {
        color: #172033;
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .save-btn {
        color: #fff;
        border: 1px solid transparent;
        background: linear-gradient(
            135deg,
            #147cf5,
            #1268ca
        );
        box-shadow: 0 6px 16px rgba(20, 124, 245, .18);
    }

    .save-btn:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(20, 124, 245, .25);
    }

    .save-btn:active {
        transform: translateY(0);
    }

    .save-btn i {
        font-size: 11px;
    }


    /* =========================================================
       RESPONSIVE - TABLET
    ========================================================= */

    @media (max-width: 991.98px) {

        .sports-create-header {
            align-items: flex-start;
        }

        .card-section-body {
            padding: 20px;
        }

        .form-action-bar {
            align-items: flex-start;
            flex-direction: column;
        }

        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }

    }


    /* =========================================================
       RESPONSIVE - MOBILE
    ========================================================= */

    @media (max-width: 767.98px) {

        .sports-game-create-page .container-fluid {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        .sports-create-header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .header-left {
            align-items: flex-start;
        }

        .header-icon {
            width: 45px;
            height: 45px;
            flex-basis: 45px;
            border-radius: 12px;
            font-size: 18px;
        }

        .sports-create-header h2 {
            font-size: 20px;
        }

        .sports-create-header p {
            font-size: 11.5px;
        }

        .breadcrumb-text {
            font-size: 9.5px;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }

        .sports-form-card {
            border-radius: 12px;
        }

        .card-section-header {
            padding: 15px;
        }

        .section-heading {
            gap: 10px;
        }

        .section-icon {
            width: 35px;
            height: 35px;
            flex-basis: 35px;
            border-radius: 9px;
            font-size: 12px;
        }

        .section-heading h5 {
            font-size: 13px;
        }

        .section-heading p {
            font-size: 10.5px;
        }

        .section-number {
            display: none;
        }

        .card-section-body {
            padding: 17px 15px;
        }

        .form-action-bar {
            padding: 13px;
        }

        .action-note {
            width: 100%;
        }

        .action-buttons {
            width: 100%;
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }

    }


    /* =========================================================
       SMALL MOBILE
    ========================================================= */

    @media (max-width: 450px) {

        .header-left {
            gap: 11px;
        }

        .header-icon {
            width: 42px;
            height: 42px;
            flex-basis: 42px;
            font-size: 16px;
        }

        .sports-create-header h2 {
            font-size: 18px;
        }

        .sports-create-header p {
            font-size: 10.5px;
        }

        .description-footer {
            align-items: flex-start;
            flex-direction: column;
            gap: 4px;
        }

    }

</style>


{{-- =============================================================
     PAGE JAVASCRIPT
============================================================= --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const description = document.querySelector('.description-field');
        const characterCount = document.getElementById('characterCount');

        if (description && characterCount) {

            function updateCharacterCount() {

                const count = description.value.length;

                characterCount.textContent =
                    count + (count === 1 ? ' character' : ' characters');

            }

            description.addEventListener('input', updateCharacterCount);

            updateCharacterCount();

        }

    });

</script>

@endsection
