@extends('layouts.app')

@section('title', 'Edit Game / Event | Admin')

@section('content')

<div class="sports-edit-page">

    <div class="container-fluid py-4">

        {{-- ========================= PAGE HEADER ========================= --}}
        <div class="edit-page-header mb-4">

            <div class="edit-heading-wrapper">
                <div class="edit-page-icon">
                    <i class="fas fa-pen"></i>
                </div>

                <div>
                    <h1>Edit Game / Event</h1>
                    <p>Update the details of this sports event.</p>
                </div>
            </div>

            <a href="{{ route('admin.sports.games.index') }}"
               class="dashboard-secondary-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Games
            </a>

        </div>


        {{-- ========================= VALIDATION ERRORS ========================= --}}
        @if($errors->any())

            <div class="sports-alert error-alert">
                <div class="alert-icon">
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


        {{-- ========================= FORM CARD ========================= --}}
        <div class="edit-form-card">

            <div class="form-card-header">
                <div>
                    <h3>Event Information</h3>
                    <p>Update the game or sports event information below.</p>
                </div>

                <div class="form-header-badge">
                    <i class="fas fa-trophy"></i>
                    Sports Event
                </div>
            </div>


            <form action="{{ route('admin.sports.games.update', $game->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                {{-- ========================= BASIC DETAILS ========================= --}}
                <div class="form-section">

                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i>
                        Basic Details
                    </div>

                    <div class="form-grid">

                        <div class="form-group full-width">
                            <label for="title">
                                Event Title
                                <span>*</span>
                            </label>

                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $game->title) }}"
                                   class="form-control"
                                   placeholder="Enter game or event title"
                                   required>
                        </div>


                        <div class="form-group">
                            <label for="sport_name">
                                Sport
                                <span>*</span>
                            </label>

                            <input type="text"
                                   id="sport_name"
                                   name="sport_name"
                                   value="{{ old('sport_name', $game->sport_name) }}"
                                   class="form-control"
                                   placeholder="e.g. Cricket, Football"
                                   required>
                        </div>


                        <div class="form-group">
                            <label for="event_type">
                                Event Type
                            </label>

                            <select id="event_type"
                                    name="event_type"
                                    class="form-control">

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

                                    <option value="{{ $type }}"
                                        {{ old('event_type', $game->event_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        <div class="form-group">
                            <label for="academic_year">
                                Academic Year
                            </label>

                            <input type="text"
                                   id="academic_year"
                                   name="academic_year"
                                   value="{{ old('academic_year', $game->academic_year) }}"
                                   class="form-control"
                                   placeholder="e.g. 2026-27">
                        </div>


                        <div class="form-group">
                            <label for="status">
                                Status
                                <span>*</span>
                            </label>

                            <select id="status"
                                    name="status"
                                    class="form-control"
                                    required>

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


                {{-- ========================= PARTICIPANTS ========================= --}}
                <div class="form-section">

                    <div class="form-section-title">
                        <i class="fas fa-users"></i>
                        Participants
                    </div>

                    <div class="form-grid">

                        <div class="form-group">
                            <label for="class">
                                Class
                            </label>

                            <input type="text"
                                   id="class"
                                   name="class"
                                   value="{{ old('class', $game->class) }}"
                                   class="form-control"
                                   placeholder="e.g. 10">
                        </div>


                        <div class="form-group">
                            <label for="section">
                                Section
                            </label>

                            <input type="text"
                                   id="section"
                                   name="section"
                                   value="{{ old('section', $game->section) }}"
                                   class="form-control"
                                   placeholder="e.g. A">
                        </div>

                    </div>

                </div>


                {{-- ========================= SCHEDULE ========================= --}}
                <div class="form-section">

                    <div class="form-section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Schedule & Location
                    </div>

                    <div class="form-grid">

                        <div class="form-group">
                            <label for="event_date">
                                Event Date
                            </label>

                            <input type="date"
                                   id="event_date"
                                   name="event_date"
                                   value="{{ old('event_date', $game->event_date ? $game->event_date->format('Y-m-d') : '') }}"
                                   class="form-control">
                        </div>


                        <div class="form-group">
                            <label for="venue">
                                Venue
                            </label>

                            <input type="text"
                                   id="venue"
                                   name="venue"
                                   value="{{ old('venue', $game->venue) }}"
                                   class="form-control"
                                   placeholder="e.g. School Ground">
                        </div>


                        <div class="form-group">
                            <label for="start_time">
                                Start Time
                            </label>

                            <input type="time"
                                   id="start_time"
                                   name="start_time"
                                   value="{{ old('start_time', $game->start_time) }}"
                                   class="form-control">
                        </div>


                        <div class="form-group">
                            <label for="end_time">
                                End Time
                            </label>

                            <input type="time"
                                   id="end_time"
                                   name="end_time"
                                   value="{{ old('end_time', $game->end_time) }}"
                                   class="form-control">
                        </div>


                        <div class="form-group full-width">
                            <label for="organizer">
                                Organizer
                            </label>

                            <input type="text"
                                   id="organizer"
                                   name="organizer"
                                   value="{{ old('organizer', $game->organizer) }}"
                                   class="form-control"
                                   placeholder="Enter organizer name">
                        </div>

                    </div>

                </div>


                {{-- ========================= DESCRIPTION ========================= --}}
                <div class="form-section">

                    <div class="form-section-title">
                        <i class="fas fa-align-left"></i>
                        Event Description
                    </div>

                    <div class="form-group">

                        <label for="description">
                            Description
                        </label>

                        <textarea id="description"
                                  name="description"
                                  class="form-control description-box"
                                  rows="5"
                                  placeholder="Enter short information about this event">{{ old('description', $game->description) }}</textarea>

                    </div>

                </div>


                {{-- ========================= ACTIONS ========================= --}}
                <div class="form-actions">

                    <a href="{{ route('admin.sports.games.index') }}"
                       class="dashboard-secondary-btn">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="dashboard-primary-btn">
                        <i class="fas fa-save"></i>
                        Update Game / Event
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<style>

/* =========================================================
   SPORTS EDIT PAGE
   ========================================================= */

.sports-edit-page {
    background: #f6f8fb;
    min-height: calc(100vh - 70px);
    font-family: 'Inter', sans-serif;
    color: #172033;
}


/* ========================= HEADER ========================= */

.edit-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.edit-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 14px;
}

.edit-page-icon {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 5px 14px rgba(20,124,245,.18);
}

.edit-heading-wrapper h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.edit-heading-wrapper p {
    margin: 4px 0 0;
    color: #718096;
    font-size: 13px;
}


/* ========================= BUTTONS ========================= */

.dashboard-primary-btn,
.dashboard-secondary-btn {
    min-height: 40px;
    padding: 0 16px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .18s ease;
}

.dashboard-primary-btn {
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    box-shadow: 0 4px 12px rgba(20,124,245,.16);
}

.dashboard-primary-btn:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 7px 18px rgba(20,124,245,.22);
}

.dashboard-secondary-btn {
    background: #fff;
    color: #475569;
    border: 1px solid #e5e9f0;
}

.dashboard-secondary-btn:hover {
    background: #f8fafc;
    color: #147cf5;
}


/* ========================= ALERT ========================= */

.sports-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 11px;
    margin-bottom: 18px;
    font-size: 13px;
}

.error-alert {
    background: #fff3f1;
    border: 1px solid #ffd8d3;
    color: #c24135;
}

.alert-icon {
    font-size: 17px;
    margin-top: 1px;
}

.sports-alert strong {
    font-size: 13px;
}

.sports-alert ul {
    margin: 5px 0 0;
    padding-left: 17px;
}

.sports-alert li {
    margin-bottom: 2px;
}


/* ========================= FORM CARD ========================= */

.edit-form-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15,23,42,.06);
    overflow: hidden;
}

.form-card-header {
    padding: 21px 24px;
    border-bottom: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.form-card-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
}

.form-card-header p {
    margin: 4px 0 0;
    color: #718096;
    font-size: 12px;
}

.form-header-badge {
    padding: 7px 11px;
    border-radius: 8px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 11px;
    font-weight: 700;
}

.form-header-badge i {
    margin-right: 5px;
}


/* ========================= FORM SECTION ========================= */

.form-section {
    padding: 22px 24px;
    border-bottom: 1px solid #edf0f5;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 17px;
    color: #172033;
    font-size: 13px;
    font-weight: 700;
}

.form-section-title i {
    color: #147cf5;
    font-size: 13px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 17px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    color: #475569;
    font-size: 12px;
    font-weight: 650;
}

.form-group label span {
    color: #ef5b4b;
}

.form-control {
    width: 100%;
    min-height: 42px;
    padding: 9px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #fff;
    color: #172033;
    font-family: inherit;
    font-size: 13px;
    outline: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.form-control::placeholder {
    color: #a0aec0;
}

.form-control:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20,124,245,.08);
}

textarea.form-control {
    resize: vertical;
}

.description-box {
    min-height: 120px;
    line-height: 1.6;
}


/* ========================= ACTIONS ========================= */

.form-actions {
    padding: 18px 24px;
    background: #fafbfd;
    border-top: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
}


/* ========================= RESPONSIVE ========================= */

@media (max-width: 768px) {

    .edit-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .edit-page-header > a {
        width: 100%;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .form-card-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .form-section {
        padding: 20px 17px;
    }

    .form-actions {
        padding: 16px 17px;
    }

    .form-actions .dashboard-primary-btn,
    .form-actions .dashboard-secondary-btn {
        flex: 1;
    }

}

</style>

@endsection