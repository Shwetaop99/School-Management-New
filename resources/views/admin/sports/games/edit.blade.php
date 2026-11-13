@extends('layouts.app')

@section('title', 'Edit Game / Event | Admin')
@section('page-title', 'Edit Game / Event')

@section('content')

<div class="sports-edit-page">

    <div class="sn-container">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="sn-page-header">

            <div class="sn-header-left">
                <div class="sn-header-icon">
                    <i class="fas fa-pen"></i>
                </div>

                <div>
                    <p class="sn-eyebrow">School Diary · Sports Department</p>

                    <h1 class="sn-page-title">
                        Edit Game / Event
                    </h1>

                    <p class="sn-page-subtitle">
                        Update event details, participants and schedule information.
                    </p>
                </div>
            </div>

            <div class="sn-header-actions">
                <a href="{{ route('admin.sports.games.index') }}"
                   class="sn-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Games
                </a>
            </div>

        </div>


        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}

        @if($errors->any())
            <div class="sn-alert sn-alert-error">
                <div class="sn-alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>

                <div>
                    <strong>Please correct the following errors:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        {{-- =====================================================
             FORM CARD
        ====================================================== --}}

        <div class="sn-form-card">

            <div class="sn-card-header">
                <div class="sn-card-heading-wrapper">
                    <div class="sn-card-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>

                    <div>
                        <h3 class="sn-card-title">
                            Event Information
                        </h3>

                        <p class="sn-card-subtitle">
                            Update the game or sports event information below.
                        </p>
                    </div>
                </div>

                <div class="sn-event-badge">
                    <i class="fas fa-trophy"></i>
                    Sports Event
                </div>
            </div>


            <form action="{{ route('admin.sports.games.update', $game->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                {{-- =====================================================
                     BASIC DETAILS
                ====================================================== --}}

                <div class="sn-form-section">

                    <div class="sn-section-heading">
                        <div class="sn-section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>
                            <h4>Basic Details</h4>
                            <p>Enter the event name and general information.</p>
                        </div>
                    </div>

                    <div class="sn-form-grid">

                        <div class="sn-form-group sn-full-width">
                            <label for="title">
                                Event Title <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title', $game->title) }}"
                                class="sn-form-control"
                                placeholder="Enter game or event title"
                                required
                            >
                        </div>


                        <div class="sn-form-group">
                            <label for="sport_name">
                                Sport <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="sport_name"
                                name="sport_name"
                                value="{{ old('sport_name', $game->sport_name) }}"
                                class="sn-form-control"
                                placeholder="e.g. Cricket, Football"
                                required
                            >
                        </div>


                        <div class="sn-form-group">
                            <label for="event_type">
                                Event Type
                            </label>

                            <select
                                id="event_type"
                                name="event_type"
                                class="sn-form-control"
                            >
                                <option value="">Select Event Type</option>

                                @foreach([
                                    'Tournament',
                                    'Match',
                                    'Competition',
                                    'Practice',
                                    'Sports Day',
                                    'Inter-School',
                                    'Other'
                                ] as $type)

                                    <option
                                        value="{{ $type }}"
                                        {{ old('event_type', $game->event_type) == $type ? 'selected' : '' }}
                                    >
                                        {{ $type }}
                                    </option>

                                @endforeach
                            </select>
                        </div>


                        <div class="sn-form-group">
                            <label for="academic_year">
                                Academic Year
                            </label>

                            <input
                                type="text"
                                id="academic_year"
                                name="academic_year"
                                value="{{ old('academic_year', $game->academic_year) }}"
                                class="sn-form-control"
                                placeholder="e.g. 2026-27"
                            >
                        </div>


                        <div class="sn-form-group">
                            <label for="status">
                                Status <span>*</span>
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="sn-form-control"
                                required
                            >
                                <option value="upcoming"
                                    {{ old('status', $game->status) == 'upcoming' ? 'selected' : '' }}>
                                    Upcoming
                                </option>

                                <option value="ongoing"
                                    {{ old('status', $game->status) == 'ongoing' ? 'selected' : '' }}>
                                    Ongoing
                                </option>

                                <option value="completed"
                                    {{ old('status', $game->status) == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option value="cancelled"
                                    {{ old('status', $game->status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     PARTICIPANTS
                ====================================================== --}}

                <div class="sn-form-section">

                    <div class="sn-section-heading">
                        <div class="sn-section-icon sn-icon-orange">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>
                            <h4>Participants</h4>
                            <p>Specify the class and section participating in the event.</p>
                        </div>
                    </div>

                    <div class="sn-form-grid">

                        <div class="sn-form-group">
                            <label for="class">
                                Class
                            </label>

                            <input
                                type="text"
                                id="class"
                                name="class"
                                value="{{ old('class', $game->class) }}"
                                class="sn-form-control"
                                placeholder="e.g. 10"
                            >
                        </div>


                        <div class="sn-form-group">
                            <label for="section">
                                Section
                            </label>

                            <input
                                type="text"
                                id="section"
                                name="section"
                                value="{{ old('section', $game->section) }}"
                                class="sn-form-control"
                                placeholder="e.g. A"
                            >
                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     SCHEDULE & LOCATION
                ====================================================== --}}

                <div class="sn-form-section">

                    <div class="sn-section-heading">
                        <div class="sn-section-icon sn-icon-blue">
                            <i class="fas fa-calendar-alt"></i>
                        </div>

                        <div>
                            <h4>Schedule & Location</h4>
                            <p>Set the event date, venue and timing.</p>
                        </div>
                    </div>

                    <div class="sn-form-grid">

                        <div class="sn-form-group">
                            <label for="event_date">
                                Event Date
                            </label>

                            <input
                                type="date"
                                id="event_date"
                                name="event_date"
                                value="{{ old('event_date', $game->event_date ? $game->event_date->format('Y-m-d') : '') }}"
                                class="sn-form-control"
                            >
                        </div>


                        <div class="sn-form-group">
                            <label for="venue">
                                Venue
                            </label>

                            <input
                                type="text"
                                id="venue"
                                name="venue"
                                value="{{ old('venue', $game->venue) }}"
                                class="sn-form-control"
                                placeholder="e.g. School Ground"
                            >
                        </div>


                        <div class="sn-form-group">
                            <label for="start_time">
                                Start Time
                            </label>

                            <input
                                type="time"
                                id="start_time"
                                name="start_time"
                                value="{{ old('start_time', $game->start_time ? \Illuminate\Support\Carbon::parse($game->start_time)->format('H:i') : '') }}"
                                class="sn-form-control"
                            >
                        </div>


                        <div class="sn-form-group">
                            <label for="end_time">
                                End Time
                            </label>

                            <input
                                type="time"
                                id="end_time"
                                name="end_time"
                                value="{{ old('end_time', $game->end_time ? \Illuminate\Support\Carbon::parse($game->end_time)->format('H:i') : '') }}"
                                class="sn-form-control"
                            >
                        </div>


                        <div class="sn-form-group sn-full-width">
                            <label for="organizer">
                                Organizer
                            </label>

                            <input
                                type="text"
                                id="organizer"
                                name="organizer"
                                value="{{ old('organizer', $game->organizer) }}"
                                class="sn-form-control"
                                placeholder="Enter organizer name"
                            >
                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     EVENT DESCRIPTION
                ====================================================== --}}

                <div class="sn-form-section">

                    <div class="sn-section-heading">
                        <div class="sn-section-icon sn-icon-violet">
                            <i class="fas fa-align-left"></i>
                        </div>

                        <div>
                            <h4>Event Description</h4>
                            <p>Add details or instructions about the sports event.</p>
                        </div>
                    </div>

                    <div class="sn-form-group">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="sn-form-control sn-description-box"
                            rows="5"
                            placeholder="Enter short information about this event"
                        >{{ old('description', $game->description) }}</textarea>

                    </div>

                </div>


                {{-- =====================================================
                     FORM ACTIONS
                ====================================================== --}}

                <div class="sn-form-actions">

                    <a href="{{ route('admin.sports.games.index') }}"
                       class="sn-btn">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>

                    <button type="submit" class="sn-btn sn-btn-primary">
                        <i class="fas fa-save"></i>
                        Update Game / Event
                    </button>

                </div>

            </form>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="sn-screen-footer">
            <div>
                <div class="sn-footer-title">
                    Sports Department
                </div>

                <div class="sn-footer-caption">
                    Promoting discipline, teamwork and fair play
                </div>
            </div>

            <div class="sn-footer-note">
                <i class="fas fa-edit"></i>
                Event Management
            </div>
        </div>

    </div>

</div>


<style>
/* =========================================================
   SPORTS EDIT PAGE
   SCHOOL DIARY DESIGN SYSTEM
   Matching Sports Event Notice
========================================================= */

.sports-edit-page {
    --sn-ink: #263b35;
    --sn-ink-2: #1d302b;
    --sn-muted: #778078;
    --sn-text: #37443d;
    --sn-line: #e5dfd0;
    --sn-paper: #ffffff;
    --sn-cream: #f8f6ef;
    --sn-green: #3a9d6b;
    --sn-green-dark: #28764e;
    --sn-green-soft: #eaf5ed;
    --sn-amber: #d58a0b;
    --sn-amber-soft: #fff5df;
    --sn-orange: #e4572e;
    --sn-orange-soft: #fff0e9;
    --sn-blue: #2b5d8a;
    --sn-blue-soft: #edf4fa;
    --sn-violet: #7a4fb5;
    --sn-violet-soft: #f3edfb;

    position: relative;
    min-height: calc(100vh - 70px);
    padding: 26px;
    color: var(--sn-text);
    background:
        radial-gradient(
            circle at 4% 0%,
            rgba(58, 157, 107, 0.08),
            transparent 28%
        ),
        radial-gradient(
            circle at 100% 12%,
            rgba(213, 138, 11, 0.07),
            transparent 25%
        ),
        var(--sn-cream);
    border-radius: 12px;
    font-family: "Public Sans", "Segoe UI", Arial, sans-serif;
    font-size: 14px;
    line-height: 1.65;
}

.sports-edit-page *,
.sports-edit-page *::before,
.sports-edit-page *::after {
    box-sizing: border-box;
}

.sports-edit-page .sn-container {
    width: 100%;
    max-width: 1250px;
    margin: 0 auto;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.sports-edit-page .sn-page-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 24px 26px;
    margin-bottom: 22px;
    background: var(--sn-paper);
    border: 1px solid var(--sn-line);
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, 0.035);
    overflow: hidden;
}

.sports-edit-page .sn-page-header::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: var(--sn-orange);
}

.sports-edit-page .sn-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.sports-edit-page .sn-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d8ebdd;
    border-radius: 10px;
    font-size: 21px;
}

.sports-edit-page .sn-eyebrow {
    margin: 0 0 3px;
    color: var(--sn-green-dark);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
    text-transform: uppercase;
}

.sports-edit-page .sn-page-title {
    margin: 0;
    color: var(--sn-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(23px, 3vw, 30px);
    font-weight: 700;
    line-height: 1.25;
    letter-spacing: -0.45px;
}

.sports-edit-page .sn-page-subtitle {
    margin: 6px 0 0;
    color: var(--sn-muted);
    font-size: 12px;
}

.sports-edit-page .sn-header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
}

/* =========================================================
   BUTTONS
========================================================= */

.sports-edit-page .sn-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 9px 15px;
    color: var(--sn-ink);
    background: #fff;
    border: 1px solid var(--sn-line);
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.4;
    text-decoration: none;
    white-space: normal;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}

.sports-edit-page .sn-btn:hover {
    color: var(--sn-green-dark);
    background: var(--sn-cream);
    transform: translateY(-1px);
}

.sports-edit-page .sn-btn-primary {
    color: #fff;
    background: var(--sn-green-dark);
    border-color: var(--sn-green-dark);
}

.sports-edit-page .sn-btn-primary:hover {
    color: #fff;
    background: var(--sn-ink-2);
    border-color: var(--sn-ink-2);
}

.sports-edit-page .sn-btn:focus-visible {
    outline: 3px solid rgba(58, 157, 107, 0.25);
    outline-offset: 2px;
}

/* =========================================================
   VALIDATION ALERT
========================================================= */

.sports-edit-page .sn-alert {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 17px 19px;
    margin-bottom: 22px;
    border-radius: 9px;
    font-size: 12px;
}

.sports-edit-page .sn-alert-error {
    color: #9c3828;
    background: var(--sn-orange-soft);
    border: 1px solid #f3d4c8;
    border-left: 5px solid var(--sn-orange);
}

.sports-edit-page .sn-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    color: #bd4929;
    background: #fff;
    border: 1px solid #f1d8cf;
    border-radius: 7px;
    font-size: 16px;
}

.sports-edit-page .sn-alert strong {
    display: block;
    margin: 2px 0 5px;
    font-size: 12px;
    font-weight: 800;
}

.sports-edit-page .sn-alert ul {
    padding-left: 18px;
    margin: 0;
}

.sports-edit-page .sn-alert li {
    margin: 3px 0;
    overflow-wrap: anywhere;
}

/* =========================================================
   FORM CARD
========================================================= */

.sports-edit-page .sn-form-card {
    margin-bottom: 22px;
    background: var(--sn-paper);
    border: 1px solid var(--sn-line);
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, 0.035);
    overflow: hidden;
}

.sports-edit-page .sn-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 24px;
    background:
        linear-gradient(
            120deg,
            rgba(58, 157, 107, 0.045),
            transparent 55%
        ),
        #fff;
    border-bottom: 1px dashed var(--sn-line);
}

.sports-edit-page .sn-card-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.sports-edit-page .sn-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d8ebdd;
    border-radius: 8px;
    font-size: 16px;
}

.sports-edit-page .sn-card-title {
    margin: 0;
    color: var(--sn-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 21px;
    font-weight: 700;
    line-height: 1.3;
}

.sports-edit-page .sn-card-subtitle {
    margin: 4px 0 0;
    color: var(--sn-muted);
    font-size: 11px;
}

.sports-edit-page .sn-event-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 7px 11px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d6e9dc;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

/* =========================================================
   FORM SECTIONS
========================================================= */

.sports-edit-page .sn-form-section {
    padding: 24px;
    border-bottom: 1px dashed var(--sn-line);
}

.sports-edit-page .sn-form-section:last-of-type {
    border-bottom: none;
}

.sports-edit-page .sn-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.sports-edit-page .sn-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 39px;
    width: 39px;
    height: 39px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d8ebdd;
    border-radius: 8px;
    font-size: 15px;
}

.sports-edit-page .sn-icon-orange {
    color: #bd4929;
    background: var(--sn-orange-soft);
    border-color: #f6d7ca;
}

.sports-edit-page .sn-icon-blue {
    color: var(--sn-blue);
    background: var(--sn-blue-soft);
    border-color: #dce8f3;
}

.sports-edit-page .sn-icon-violet {
    color: var(--sn-violet);
    background: var(--sn-violet-soft);
    border-color: #e5daf6;
}

.sports-edit-page .sn-section-heading h4 {
    margin: 0;
    color: var(--sn-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 19px;
    font-weight: 700;
    line-height: 1.3;
}

.sports-edit-page .sn-section-heading p {
    margin: 4px 0 0;
    color: var(--sn-muted);
    font-size: 11px;
}

/* =========================================================
   FORM GRID & CONTROLS
========================================================= */

.sports-edit-page .sn-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.sports-edit-page .sn-form-group {
    min-width: 0;
}

.sports-edit-page .sn-full-width {
    grid-column: 1 / -1;
}

.sports-edit-page .sn-form-group label {
    display: block;
    margin-bottom: 7px;
    color: var(--sn-ink);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.2px;
}

.sports-edit-page .sn-form-group label span {
    color: var(--sn-orange);
}

.sports-edit-page .sn-form-control {
    display: block;
    width: 100%;
    min-height: 43px;
    padding: 10px 12px;
    color: var(--sn-text);
    background: #fcfbf7;
    border: 1px solid #e4dfd2;
    border-radius: 7px;
    outline: none;
    font-family: inherit;
    font-size: 12px;
    line-height: 1.5;
    transition:
        border-color 0.2s ease,
        background 0.2s ease,
        box-shadow 0.2s ease;
}

.sports-edit-page .sn-form-control::placeholder {
    color: #9b9d94;
    opacity: 1;
}

.sports-edit-page .sn-form-control:hover {
    border-color: #c9d8ca;
}

.sports-edit-page .sn-form-control:focus {
    background: #fff;
    border-color: var(--sn-green);
    box-shadow: 0 0 0 3px rgba(58, 157, 107, 0.12);
}

.sports-edit-page select.sn-form-control {
    cursor: pointer;
}

.sports-edit-page textarea.sn-form-control {
    resize: vertical;
}

.sports-edit-page .sn-description-box {
    min-height: 135px;
    line-height: 1.8;
}

/* =========================================================
   FORM ACTIONS
========================================================= */

.sports-edit-page .sn-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 10px;
    padding: 19px 24px;
    background: #fcfbf7;
    border-top: 1px dashed var(--sn-line);
}

/* =========================================================
   FOOTER
========================================================= */

.sports-edit-page .sn-screen-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 17px 20px;
    margin-top: 2px;
    color: #fff;
    background: var(--sn-ink-2);
    border-radius: 9px;
}

.sports-edit-page .sn-footer-title {
    color: #fff;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 16px;
    font-weight: 700;
}

.sports-edit-page .sn-footer-caption {
    margin-top: 3px;
    color: #c6d3c9;
    font-size: 10px;
}

.sports-edit-page .sn-footer-note {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #d9e5d9;
    font-size: 11px;
    font-weight: 700;
}

/* =========================================================
   RESPONSIVE DESIGN
========================================================= */

@media (max-width: 992px) {
    .sports-edit-page {
        padding: 18px;
    }

    .sports-edit-page .sn-page-header {
        align-items: flex-start;
    }
}

@media (max-width: 640px) {
    .sports-edit-page {
        padding: 10px;
    }

    .sports-edit-page .sn-page-header {
        flex-direction: column;
        padding: 19px;
    }

    .sports-edit-page .sn-header-left {
        align-items: flex-start;
    }

    .sports-edit-page .sn-header-actions {
        width: 100%;
    }

    .sports-edit-page .sn-header-actions .sn-btn {
        width: 100%;
    }

    .sports-edit-page .sn-card-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 17px;
    }

    .sports-edit-page .sn-form-section {
        padding: 20px 17px;
    }

    .sports-edit-page .sn-form-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .sports-edit-page .sn-full-width {
        grid-column: auto;
    }

    .sports-edit-page .sn-form-actions {
        padding: 16px 17px;
    }

    .sports-edit-page .sn-form-actions .sn-btn {
        flex: 1 1 140px;
    }

    .sports-edit-page .sn-screen-footer {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>

@endsection
