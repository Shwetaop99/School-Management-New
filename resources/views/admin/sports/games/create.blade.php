
@extends('layouts.app')

@section('title', 'Add Game / Event | Admin')
@section('page-title', 'Add Game / Event')

@section('content')

<style>
/* =========================================================
   SPORTS GAME CREATE — SCHOOL DIARY DESIGN SYSTEM
========================================================= */
.sports-game-create-page {
    --paper: #fffefa;
    --ink: #263b35;
    --muted: #849087;
    --wood: #a87543;
    --green: #34775a;
    --green-dark: #245a43;
    --green-light: #eaf3e9;
    --red: #bd5148;
    --red-light: #fff0ec;
    --cyan: #527f79;
    --border: #e5e1d3;

    min-height: calc(100vh - 70px);
    padding: 24px;
    background: #f5f3e9;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
}

.sports-game-create-page *,
.sports-game-create-page *::before,
.sports-game-create-page *::after {
    box-sizing: border-box;
}

.sports-game-create-page .container-fluid {
    max-width: 1500px;
}

/* HEADER */
.sports-create-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
    padding: 22px 24px;
    border: 1px solid var(--border);
    border-left: 4px solid var(--green);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38,59,53,.04);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    border: 1px solid #dce8d9;
    border-radius: 12px;
    background: var(--green-light);
    color: var(--green);
    font-size: 19px;
}

.header-content {
    min-width: 0;
}

.breadcrumb-text {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 6px;
    color: #879084;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
}

.breadcrumb-text i {
    color: #b1b8a9;
    font-size: 8px;
}

.sports-create-header h2 {
    margin: 0;
    color: var(--ink);
    font-size: 22px;
    line-height: 1.4;
    font-weight: 800;
}

.sports-create-header p {
    margin: 5px 0 0;
    color: #7b857a;
    font-size: 11px;
    line-height: 1.7;
}

/* BACK BUTTON */
.back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-shrink: 0;
    padding: 9px 13px 9px 9px;
    border: 1px solid #dcd9ca;
    border-radius: 8px;
    background: var(--paper);
    color: #52665c;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
    transition: .2s ease;
}

.back-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: var(--green-light);
    color: var(--green);
}

.back-btn:hover {
    border-color: #a8c0a5;
    background: #eef5e9;
    color: var(--green-dark);
}

.back-btn:hover .back-icon {
    background: var(--green);
    color: white;
}

/* VALIDATION ALERT */
.sports-alert {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 19px;
    padding: 15px 44px 15px 16px;
    border: 1px solid #efd0c8;
    border-radius: 11px;
    background: var(--red-light);
    color: var(--red);
    font-size: 11px;
    line-height: 1.7;
}

.alert-symbol {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border-radius: 9px;
    background: #ffe4de;
    color: var(--red);
    font-size: 14px;
}

.alert-content strong {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 800;
}

.alert-content ul {
    margin: 5px 0 0;
    padding-left: 17px;
}

.alert-close {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    border: 0;
    border-radius: 7px;
    background: transparent;
    color: var(--red);
    cursor: pointer;
}

.alert-close:hover {
    background: #ffe0da;
}

/* FORM CARDS */
.sports-form-card {
    overflow: hidden;
    margin-bottom: 19px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(54,64,43,.035);
    transition: border-color .2s ease, box-shadow .2s ease;
}

.sports-form-card:hover {
    border-color: #d7d3c2;
    box-shadow: 0 5px 17px rgba(54,64,43,.05);
}

/* SECTION HEADER */
.card-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 15px 18px;
    border-bottom: 1px solid #ece8dc;
    background: #faf8f0;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.section-heading h5 {
    margin: 0;
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

.section-heading p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

.section-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 25px;
    padding: 0 7px;
    border: 1px solid #e6e2d5;
    border-radius: 7px;
    background: #fffefa;
    color: #92998b;
    font-size: 10px;
    font-weight: 800;
}

/* SECTION ICONS */
.section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 1px solid #dce8d9;
    border-radius: 10px;
    background: var(--green-light);
    color: var(--green);
    font-size: 14px;
}

.section-icon.blue {
    border-color: #dce8d9;
    background: var(--green-light);
    color: var(--green);
}

.section-icon.cyan {
    border-color: #d7e6e4;
    background: #edf5f1;
    color: var(--cyan);
}

.section-icon.orange {
    border-color: #edddc5;
    background: #fbf0df;
    color: var(--wood);
}

.section-icon.red {
    border-color: #efd0c8;
    background: var(--red-light);
    color: var(--red);
}

/* CARD BODY */
.card-section-body {
    padding: 20px;
}

/* FIELD LABELS */
.field-label {
    display: block;
    margin-bottom: 7px;
    color: #56665a;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .45px;
    text-transform: uppercase;
}

.field-label > span {
    margin-left: 2px;
    color: var(--red);
}

/* INPUT WRAPPER */
.modern-input {
    position: relative;
    display: flex;
    align-items: center;
    min-width: 0;
}

.modern-input .form-control {
    display: block;
    width: 100%;
    min-width: 0;
    height: 43px;
    padding: 10px 12px 10px 40px;
    border: 1px solid #dedbcc;
    border-radius: 8px;
    outline: none;
    background: #fffefb;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.modern-input .form-control::placeholder {
    color: #a5a79b;
    font-weight: 400;
}

.modern-input .form-control:hover {
    border-color: #c9c5b3;
}

.modern-input .form-control:focus {
    border-color: var(--green);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(52,119,90,.10);
}

.modern-input .form-control:disabled {
    cursor: not-allowed;
    opacity: .65;
    background: #f0eee6;
}

/* INPUT ICON */
.input-prefix {
    position: absolute;
    top: 50%;
    left: 14px;
    z-index: 2;
    transform: translateY(-50%);
    color: #8e9a8b;
    font-size: 11px;
    pointer-events: none;
    transition: color .18s ease;
}

.modern-input:focus-within .input-prefix {
    color: var(--green);
}

.status-prefix {
    color: var(--wood);
}

/* SELECT */
.modern-input select.form-control {
    padding-right: 35px;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
}

.select-arrow {
    position: absolute;
    top: 50%;
    right: 13px;
    z-index: 2;
    transform: translateY(-50%);
    color: #92998b;
    font-size: 9px;
    pointer-events: none;
}

.modern-input:focus-within .select-arrow {
    color: var(--green);
}

/* FIELD HELP */
.field-helper {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 6px;
    color: #92988b;
    font-size: 9px;
    line-height: 1.6;
}

.field-helper i {
    color: var(--green);
    font-size: 9px;
}

/* DESCRIPTION */
.description-field {
    display: block;
    width: 100%;
    min-height: 120px;
    padding: 12px;
    border: 1px solid #dedbcc;
    border-radius: 8px;
    outline: none;
    background: #fffefb;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
    line-height: 1.7;
    resize: vertical;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.description-field::placeholder {
    color: #a5a79b;
}

.description-field:hover {
    border-color: #c9c5b3;
}

.description-field:focus {
    border-color: var(--green);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(52,119,90,.10);
}

.description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 8px;
    color: #92988b;
    font-size: 9px;
    line-height: 1.6;
}

.description-footer span:first-child {
    display: flex;
    align-items: center;
    gap: 5px;
}

.description-footer i {
    color: var(--green);
}

/* ACTION BAR */
.form-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 15px;
    padding: 15px 17px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(54,64,43,.035);
}

.action-note {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.action-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border: 1px solid #dce8d9;
    border-radius: 9px;
    background: var(--green-light);
    color: var(--green);
    font-size: 12px;
}

.action-note strong {
    display: block;
    color: #35483b;
    font-size: 11px;
    font-weight: 800;
}

.action-note span {
    display: block;
    margin-top: 4px;
    color: #879084;
    font-size: 9px;
    line-height: 1.6;
}

/* ACTION BUTTONS */
.action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
}

.cancel-btn,
.save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 15px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: .2s ease;
}

.cancel-btn {
    border: 1px solid #dcd9ca;
    background: #fffefa;
    color: #687466;
}

.cancel-btn:hover {
    border-color: #c6c4b4;
    background: #f3f1e8;
    color: var(--ink);
}

.save-btn {
    border: 1px solid #315f45;
    background: linear-gradient(135deg, #315f45, #4b8059);
    color: #fff;
    box-shadow: 0 4px 10px rgba(49,95,69,.12);
}

.save-btn:hover {
    border-color: #244a35;
    background: linear-gradient(135deg, #244a35, #386b49);
    color: #fff;
    transform: translateY(-1px);
}

.save-btn:active {
    transform: translateY(0);
}

/* VALIDATION HIGHLIGHTS */
.sports-game-create-page .form-control.is-invalid,
.sports-game-create-page .description-field.is-invalid {
    border-color: var(--red);
    background-color: #fff8f5;
}

.sports-game-create-page .form-control.is-invalid:focus,
.sports-game-create-page .description-field.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189,81,72,.09);
}

/* RESPONSIVE */
@media (max-width: 991.98px) {
    .sports-game-create-page {
        padding: 18px;
    }

    .sports-create-header {
        align-items: flex-start;
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

@media (max-width: 767.98px) {
    .sports-game-create-page {
        padding: 14px;
    }

    .sports-create-header {
        align-items: stretch;
        flex-direction: column;
        gap: 14px;
        padding: 17px;
    }

    .header-left {
        align-items: flex-start;
        gap: 11px;
    }

    .header-icon {
        width: 43px;
        height: 43px;
        flex-basis: 43px;
        border-radius: 10px;
        font-size: 16px;
    }

    .sports-create-header h2 {
        font-size: 19px;
    }

    .sports-create-header p {
        font-size: 10px;
    }

    .back-btn {
        width: 100%;
    }

    .card-section-header {
        padding: 14px 15px;
    }

    .section-icon {
        width: 35px;
        height: 35px;
        flex-basis: 35px;
        border-radius: 9px;
        font-size: 12px;
    }

    .section-heading h5 {
        font-size: 12px;
    }

    .section-heading p {
        font-size: 9px;
    }

    .section-number {
        display: none;
    }

    .card-section-body {
        padding: 16px;
    }

    .form-action-bar {
        padding: 14px;
    }

    .action-buttons {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .cancel-btn,
    .save-btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .sports-game-create-page {
        padding: 10px;
    }

    .sports-create-header {
        padding: 15px;
    }

    .sports-create-header h2 {
        font-size: 17px;
    }

    .breadcrumb-text {
        gap: 6px;
        font-size: 8px;
    }

    .card-section-body {
        padding: 14px;
    }

    .description-footer {
        align-items: flex-start;
        flex-direction: column;
        gap: 4px;
    }

    .sports-form-card {
        border-radius: 11px;
    }

    .sports-alert {
        padding: 12px 38px 12px 12px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .sports-game-create-page *,
    .sports-game-create-page *::before,
    .sports-game-create-page *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<div class="sports-game-create-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
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

        {{-- VALIDATION ERRORS --}}
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

                <button type="button"
                        class="alert-close"
                        aria-label="Dismiss validation errors"
                        onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- MAIN FORM --}}
        <form action="{{ route('admin.sports.games.store') }}"
              method="POST"
              id="sportsGameCreateForm">
            @csrf

            {{-- BASIC INFORMATION --}}
            <div class="sports-form-card mb-4">
                <div class="card-section-header">
                    <div class="section-heading">
                        <div class="section-icon blue">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h5>Basic Information</h5>
                            <p>Provide the basic details of the sports activity.</p>
                        </div>
                    </div>
                    <span class="section-number">01</span>
                </div>

                <div class="card-section-body">
                    <div class="row g-4">

                        <div class="col-lg-6">
                            <label class="field-label" for="title">
                                Event Title <span>*</span>
                            </label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-heading"></i>
                                </div>
                                <input type="text"
                                       id="title"
                                       name="title"
                                       class="form-control"
                                       value="{{ old('title') }}"
                                       placeholder="e.g. Inter School Football Match"
                                       required>
                            </div>
                            @error('title')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="field-label" for="sport_name">
                                Sport Name <span>*</span>
                            </label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-running"></i>
                                </div>
                                <input type="text"
                                       id="sport_name"
                                       name="sport_name"
                                       class="form-control"
                                       value="{{ old('sport_name') }}"
                                       placeholder="e.g. Football, Cricket, Basketball"
                                       required>
                            </div>
                            @error('sport_name')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="field-label" for="event_type">Event Type</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <select name="event_type" id="event_type" class="form-control">
                                    <option value="">Select Event Type</option>
                                    @foreach([
                                        'Match',
                                        'Tournament',
                                        'Competition',
                                        'Practice',
                                        'Sports Day',
                                        'Other'
                                    ] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('event_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="select-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            @error('event_type')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="field-label" for="academic_year">
                                Academic Year
                            </label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <input type="text"
                                       name="academic_year"
                                       id="academic_year"
                                       class="form-control"
                                       value="{{ old('academic_year') }}"
                                       placeholder="e.g. 2026-27"
                                       autocomplete="off">
                            </div>
                            <div class="field-helper">
                                <i class="fas fa-info-circle"></i>
                                Enter academic year manually.
                            </div>
                            @error('academic_year')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="field-label" for="status">
                                Status <span>*</span>
                            </label>
                            <div class="modern-input">
                                <div class="input-prefix status-prefix">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <select name="status" id="status" class="form-control" required>
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
                            @error('status')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- ACADEMIC INFORMATION --}}
            <div class="sports-form-card mb-4">
                <div class="card-section-header">
                    <div class="section-heading">
                        <div class="section-icon cyan">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <h5>Academic Information</h5>
                            <p>Select the class and participating section.</p>
                        </div>
                    </div>
                    <span class="section-number">02</span>
                </div>

                <div class="card-section-body">
                    <div class="row g-4">

                        <div class="col-lg-6">
                            <label class="field-label" for="class">Class</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <select name="class" id="class" class="form-control">
                                    <option value="">Select Class</option>

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

                                            <option value="{{ $classRecord->class_name }}"
                                                {{ old('class') == $classRecord->class_name ? 'selected' : '' }}>
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
                                Classes are loaded from Class Management.
                            </div>
                            @error('class')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="field-label" for="section">Section</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-users"></i>
                                </div>
                                <input type="text"
                                       name="section"
                                       id="section"
                                       class="form-control"
                                       value="{{ old('section') }}"
                                       placeholder="e.g. A, B, C">
                            </div>
                            <div class="field-helper">
                                <i class="fas fa-info-circle"></i>
                                Enter the participating section.
                            </div>
                            @error('section')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- SCHEDULE & VENUE --}}
            <div class="sports-form-card mb-4">
                <div class="card-section-header">
                    <div class="section-heading">
                        <div class="section-icon orange">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h5>Schedule &amp; Venue</h5>
                            <p>Set the date, timing and location of the event.</p>
                        </div>
                    </div>
                    <span class="section-number">03</span>
                </div>

                <div class="card-section-body">
                    <div class="row g-4">

                        <div class="col-lg-4">
                            <label class="field-label" for="event_date">Event Date</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <input type="date"
                                       name="event_date"
                                       id="event_date"
                                       class="form-control"
                                       value="{{ old('event_date') }}">
                            </div>
                            @error('event_date')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="field-label" for="start_time">Start Time</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <input type="time"
                                       name="start_time"
                                       id="start_time"
                                       class="form-control"
                                       value="{{ old('start_time') }}">
                            </div>
                            @error('start_time')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="field-label" for="end_time">End Time</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-hourglass-end"></i>
                                </div>
                                <input type="time"
                                       name="end_time"
                                       id="end_time"
                                       class="form-control"
                                       value="{{ old('end_time') }}">
                            </div>
                            @error('end_time')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="field-label" for="venue">Venue</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <input type="text"
                                       name="venue"
                                       id="venue"
                                       class="form-control"
                                       value="{{ old('venue') }}"
                                       placeholder="e.g. School Ground, Indoor Hall">
                            </div>
                            @error('venue')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="field-label" for="organizer">Organizer</label>
                            <div class="modern-input">
                                <div class="input-prefix">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <input type="text"
                                       name="organizer"
                                       id="organizer"
                                       class="form-control"
                                       value="{{ old('organizer') }}"
                                       placeholder="Enter organizer name">
                            </div>
                            @error('organizer')
                                <div class="field-helper" style="color:var(--red)">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div class="sports-form-card mb-4">
                <div class="card-section-header">
                    <div class="section-heading">
                        <div class="section-icon red">
                            <i class="fas fa-align-left"></i>
                        </div>
                        <div>
                            <h5>Description</h5>
                            <p>Add instructions or additional event information.</p>
                        </div>
                    </div>
                    <span class="section-number">04</span>
                </div>

                <div class="card-section-body">
                    <label class="field-label" for="description">
                        Event Description
                    </label>

                    <textarea name="description"
                              id="description"
                              class="description-field"
                              rows="5"
                              placeholder="Enter event description, rules, instructions or additional information...">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="field-helper" style="color:var(--red)">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="description-footer">
                        <span>
                            <i class="fas fa-info-circle"></i>
                            Keep the description clear and informative.
                        </span>
                        <span id="characterCount">0 characters</span>
                    </div>
                </div>
            </div>

            {{-- FORM ACTIONS --}}
            <div class="form-action-bar">
                <div class="action-note">
                    <div class="action-note-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <strong>Ready to create this event?</strong>
                        <span>Review the information before saving.</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('admin.sports.games.index') }}"
                       class="cancel-btn">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>

                    <button type="submit" class="save-btn" id="saveGameButton">
                        <i class="fas fa-check-circle"></i>
                        Save Game / Event
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const description = document.getElementById('description');
    const characterCount = document.getElementById('characterCount');
    const form = document.getElementById('sportsGameCreateForm');
    const saveButton = document.getElementById('saveGameButton');

    if (description && characterCount) {
        function updateCharacterCount() {
            const count = description.value.length;

            characterCount.textContent =
                count + (count === 1 ? ' character' : ' characters');
        }

        description.addEventListener('input', updateCharacterCount);
        updateCharacterCount();
    }

    if (form && saveButton) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) {
                return;
            }

            saveButton.disabled = true;
            saveButton.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Saving Game / Event...';
        });
    }
});
</script>

@endsection
