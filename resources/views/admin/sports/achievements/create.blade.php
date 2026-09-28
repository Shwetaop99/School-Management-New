@extends('layouts.app')

@section('title', 'Add Achievement | Admin')

@section('content')

<div class="sports-achievement-create-page">

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="achievement-create-header">

        <div class="create-heading-area">

            <a href="{{ route('admin.sports.achievements.index') }}"
               class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="create-heading-icon">
                <i class="fas fa-trophy"></i>
            </div>

            <div>
                <div class="create-breadcrumb">
                    Sports
                    <span>/</span>
                    Achievements
                    <span>/</span>
                    Add
                </div>

                <h1>Add Achievement</h1>

                <p>
                    Record a new student sports achievement.
                </p>
            </div>

        </div>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="form-error-card">

            <div class="form-error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <div class="form-error-content">

                <strong>
                    Please correct the following errors:
                </strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <form method="POST"
          action="{{ route('admin.sports.achievements.store') }}">

        @csrf


        {{-- =====================================================
             ACHIEVEMENT INFORMATION
        ====================================================== --}}

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

            </div>


            <div class="form-card-body">

                <div class="form-grid">

                    {{-- Title --}}

                    <div class="form-group full-width">

                        <label for="title">
                            Achievement Title
                            <span class="required">*</span>
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


                    {{-- Student --}}

                    <div class="form-group">

                        <label for="student_name">
                            Student Name
                        </label>

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


                    {{-- Sport --}}

                    <div class="form-group">

                        <label for="sport_name">
                            Sport
                            <span class="required">*</span>
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


                    {{-- Achievement Type --}}

                    <div class="form-group">

                        <label for="achievement_type">
                            Achievement Type
                        </label>

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


                    {{-- Position --}}

                    <div class="form-group">

                        <label for="position">
                            Position / Rank
                        </label>

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


        {{-- =====================================================
             STUDENT / ACADEMIC DETAILS
        ====================================================== --}}

        <div class="form-card">

            <div class="form-card-header">

                <div class="form-section-title">

                    <div class="section-icon cyan-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <div>
                        <h3>Student & Academic Details</h3>
                        <p>Associate the achievement with the student's class.</p>
                    </div>

                </div>

            </div>


            <div class="form-card-body">

                <div class="form-grid">


                    {{-- Academic Year --}}

                    <div class="form-group">

                        <label for="academic_year">
                            Academic Year
                        </label>

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


                    {{-- Class --}}

                    <div class="form-group">

                        <label for="class">
                            Class
                        </label>

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


                    {{-- Section --}}

                    <div class="form-group">

                        <label for="section">
                            Section
                        </label>

                        <div class="input-with-icon">

                            <i class="fas fa-layer-group"></i>

                            <input
                                type="text"
                                name="section"
                                id="section"
                                class="form-control @error('section') is-invalid @enderror"
                                value="{{ old('section') }}"
                                placeholder="Enter section e.g. A, B, C"
                            >

                        </div>

                        @error('section')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Achievement Date --}}

                    <div class="form-group">

                        <label for="achievement_date">
                            Achievement Date
                        </label>

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


        {{-- =====================================================
             COMPETITION DETAILS
        ====================================================== --}}

        <div class="form-card">

            <div class="form-card-header">

                <div class="form-section-title">

                    <div class="section-icon orange-icon">
                        <i class="fas fa-flag-checkered"></i>
                    </div>

                    <div>
                        <h3>Competition Details</h3>
                        <p>Enter competition and event information.</p>
                    </div>

                </div>

            </div>


            <div class="form-card-body">

                <div class="form-grid">


                    {{-- Competition --}}

                    <div class="form-group">

                        <label for="competition_name">
                            Competition Name
                        </label>

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


                    {{-- Venue --}}

                    <div class="form-group">

                        <label for="venue">
                            Venue
                        </label>

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


                    {{-- Description --}}

                    <div class="form-group full-width">

                        <label for="description">
                            Description
                        </label>

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


        {{-- =====================================================
             STATUS
        ====================================================== --}}

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

                    </label>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FORM ACTIONS
        ====================================================== --}}

        <div class="form-actions">

            <a href="{{ route('admin.sports.achievements.index') }}"
               class="cancel-btn">
                <i class="fas fa-times"></i>
                Cancel
            </a>

            <button type="submit"
                    class="save-btn">
                <i class="fas fa-save"></i>
                Save Achievement
            </button>

        </div>

    </form>

</div>

</div>

<style>

/* =========================================================
   PAGE
========================================================= */

.sports-achievement-create-page {
    min-height: calc(100vh - 60px);
    background: #f6f8fb;
    color: #172033;
}


/* =========================================================
   HEADER
========================================================= */

.achievement-create-header {
    margin-bottom: 22px;
}

.create-heading-area {
    display: flex;
    align-items: center;
    gap: 13px;
}

.back-btn {
    width: 38px;
    height: 38px;
    min-width: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #fff;
    border: 1px solid #edf0f5;
    color: #64748b !important;
    text-decoration: none !important;
    box-shadow: 0 4px 13px rgba(15,23,42,.04);
    transition: all .18s ease;
}

.back-btn:hover {
    color: #147cf5 !important;
    border-color: #cfe5ff;
    background: #f8fbff;
    transform: translateX(-2px);
}

.create-heading-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    font-size: 19px;
    box-shadow: 0 7px 18px rgba(20,124,245,.18);
}

.create-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 3px;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
}

.create-breadcrumb span {
    color: #cbd5e1;
}

.create-heading-area h1 {
    margin: 0;
    color: #172033;
    font-size: 23px;
    font-weight: 700;
    letter-spacing: -.2px;
}

.create-heading-area p {
    margin: 4px 0 0;
    color: #718096;
    font-size: 12px;
}


/* =========================================================
   ERROR CARD
========================================================= */

.form-error-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 18px;
    padding: 14px 16px;
    border: 1px solid #ffd7d3;
    border-left: 4px solid #f65343;
    border-radius: 11px;
    background: #fff;
    box-shadow: 0 5px 18px rgba(15,23,42,.04);
}

.form-error-icon {
    width: 32px;
    height: 32px;
    min-width: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #fff0ee;
    color: #f65343;
    font-size: 13px;
}

.form-error-content strong {
    display: block;
    margin-bottom: 5px;
    color: #b94136;
    font-size: 12px;
}

.form-error-content ul {
    margin: 0;
    padding-left: 17px;
    color: #df5b50;
    font-size: 11px;
}


/* =========================================================
   FORM CARDS
========================================================= */

.form-card {
    margin-bottom: 18px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(15,23,42,.05);
}

.form-card-header {
    padding: 16px 19px;
    border-bottom: 1px solid #edf0f5;
    background: #fff;
}

.form-section-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.section-icon {
    width: 37px;
    height: 37px;
    min-width: 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    font-size: 14px;
}

.blue-icon {
    background: #edf6ff;
    color: #147cf5;
}

.cyan-icon {
    background: #eafcff;
    color: #18b5d5;
}

.orange-icon {
    background: #fff7e8;
    color: #f5a623;
}

.green-icon {
    background: #eafaf2;
    color: #20a968;
}

.form-section-title h3 {
    margin: 0;
    color: #172033;
    font-size: 14px;
    font-weight: 700;
}

.form-section-title p {
    margin: 2px 0 0;
    color: #94a3b8;
    font-size: 10px;
}

.form-card-body {
    padding: 20px;
}


/* =========================================================
   FORM GRID
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 17px 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
}

.required {
    color: #f65343;
    margin-left: 2px;
}

.input-with-icon {
    position: relative;
}

.input-with-icon > i {
    position: absolute;
    top: 50%;
    left: 13px;
    z-index: 2;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 11px;
    pointer-events: none;
}

.input-with-icon .form-control {
    padding-left: 35px;
}

.form-control {
    width: 100%;
    height: 41px;
    padding: 8px 12px;
    border: 1px solid #e5eaf0;
    border-radius: 8px;
    background: #fff;
    color: #334155;
    font-size: 12px;
    box-shadow: none !important;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.form-control::placeholder {
    color: #b0b8c4;
}

.form-control:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20,124,245,.08) !important;
}

.form-control.is-invalid {
    border-color: #f65343;
}

.textarea-control {
    min-height: 100px;
    height: auto;
    padding: 11px 12px;
    resize: vertical;
}

.field-error {
    margin-top: 5px;
    color: #e05247;
    font-size: 10px;
    font-weight: 600;
}


/* =========================================================
   STATUS
========================================================= */

.status-options {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}

.status-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 13px 14px;
    border: 1px solid #e8edf3;
    border-radius: 10px;
    cursor: pointer;
    background: #fff;
    transition: all .18s ease;
}

.status-option:hover {
    border-color: #cfe5ff;
    background: #fbfdff;
}

.status-option input {
    position: absolute;
    opacity: 0;
}

.custom-radio {
    width: 18px;
    height: 18px;
    min-width: 18px;
    border: 2px solid #cbd5e1;
    border-radius: 50%;
    position: relative;
}

.status-option input:checked + .custom-radio {
    border-color: #147cf5;
}

.status-option input:checked + .custom-radio::after {
    content: "";
    position: absolute;
    width: 8px;
    height: 8px;
    top: 3px;
    left: 3px;
    border-radius: 50%;
    background: #147cf5;
}

.status-option-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.status-option-content strong {
    color: #334155;
    font-size: 11px;
}

.status-option-content small {
    color: #94a3b8;
    font-size: 9px;
}


/* =========================================================
   FORM ACTIONS
========================================================= */

.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 3px;
    padding-bottom: 10px;
}

.cancel-btn,
.save-btn {
    height: 41px;
    padding: 0 17px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    transition: all .18s ease;
}

.cancel-btn {
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b !important;
    text-decoration: none !important;
}

.cancel-btn:hover {
    background: #f8fafc;
    color: #334155 !important;
}

.save-btn {
    border: 0;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(20,124,245,.17);
}

.save-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 9px 20px rgba(20,124,245,.23);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .sports-achievement-create-page .container-fluid {
        padding-top: 18px !important;
    }

    .create-heading-area {
        align-items: flex-start;
    }

    .create-heading-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
    }

    .create-heading-area h1 {
        font-size: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .status-options {
        grid-template-columns: 1fr;
    }

    .form-actions {
        justify-content: stretch;
    }

    .cancel-btn,
    .save-btn {
        flex: 1;
    }

}


@media (max-width: 480px) {

    .create-heading-area {
        gap: 9px;
    }

    .back-btn {
        width: 34px;
        height: 34px;
        min-width: 34px;
    }

    .create-heading-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        font-size: 16px;
    }

    .create-breadcrumb {
        font-size: 9px;
    }

    .create-heading-area h1 {
        font-size: 18px;
    }

    .create-heading-area p {
        font-size: 10px;
    }

    .form-card-body {
        padding: 15px;
    }

    .form-card-header {
        padding: 14px 15px;
    }

}

</style>

@endsection
