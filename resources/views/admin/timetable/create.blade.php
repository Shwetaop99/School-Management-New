@extends('layouts.app')

@section('title', 'Create Timetable')
@section('page-title', 'Create Timetable')

@section('content')

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
>

<style>

/* =========================================================
   PAGE
========================================================= */

.timetable-create-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
}

/* =========================================================
   HEADER
========================================================= */

.timetable-header {
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    padding: 28px 32px;
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 8px 25px rgba(20, 124, 245, 0.18);
}

.timetable-header h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.timetable-header p {
    margin: 8px 0 0;
    font-size: 14px;
    opacity: .9;
}

/* =========================================================
   FORM CARD
========================================================= */

.timetable-form-card {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, .06);
}

.form-section {
    margin-bottom: 30px;
}

.form-section:last-child {
    margin-bottom: 0;
}

/* =========================================================
   SECTION TITLE
========================================================= */

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
    color: #1f2937;
    font-size: 18px;
    font-weight: 700;
}

.section-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 17px;
}

/* =========================================================
   FORM GRID
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

/* =========================================================
   LABEL
========================================================= */

.form-label {
    margin-bottom: 8px;
    color: #374151;
    font-size: 14px;
    font-weight: 600;
}

.required-star {
    color: #ef4444;
}

/* =========================================================
   INPUTS
========================================================= */

.timetable-form-input,
.timetable-form-select,
.form-control-custom {
    width: 100%;
    height: 46px;
    padding: 0 14px;
    box-sizing: border-box;
    border: 1px solid #d9e2ec;
    border-radius: 9px;
    outline: none;
    background: #fff;
    color: #1f2937;
    font-size: 14px;
    font-family: inherit;
    transition: .2s ease;
}

.form-control-custom {
    height: 56px;
    padding: 0 18px;
    border-radius: 12px;
}

/* =========================================================
   SELECT
========================================================= */

select.form-control-custom,
select.timetable-form-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;

    background-image:
        linear-gradient(
            45deg,
            transparent 50%,
            #172033 50%
        ),
        linear-gradient(
            135deg,
            #172033 50%,
            transparent 50%
        );

    background-position:
        calc(100% - 20px) 24px,
        calc(100% - 14px) 24px;

    background-size:
        6px 6px,
        6px 6px;

    background-repeat: no-repeat;

    padding-right: 45px;
}

select.timetable-form-select {
    background-position:
        calc(100% - 16px) 19px,
        calc(100% - 11px) 19px;
}

/* =========================================================
   HOVER / FOCUS
========================================================= */

.form-control-custom:hover,
.timetable-form-input:hover,
.timetable-form-select:hover {
    border-color: #b8c6d9;
}

.form-control-custom:focus,
.timetable-form-input:focus,
.timetable-form-select:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, .10);
}

/* =========================================================
   DISABLED
========================================================= */

.form-control-custom:disabled,
.timetable-form-input:disabled,
.timetable-form-select:disabled {
    background: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
}

/* =========================================================
   HELP
========================================================= */

.form-help,
.time-help {
    margin-top: 6px;
    color: #6b7280;
    font-size: 12px;
}

/* =========================================================
   ERROR
========================================================= */

.error-message {
    margin-top: 6px;
    color: #dc2626;
    font-size: 12px;
}

.alert-error {
    margin-bottom: 24px;
    padding: 14px 16px;
    border: 1px solid #fecaca;
    border-radius: 10px;
    background: #fef2f2;
    color: #b91c1c;
}

.alert-error ul {
    margin: 0;
    padding-left: 20px;
}

/* =========================================================
   ACTIONS
========================================================= */

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 10px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}

.btn-cancel,
.btn-save {
    height: 44px;
    padding: 0 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.btn-cancel {
    background: #f3f4f6;
    color: #4b5563;
}

.btn-cancel:hover {
    background: #e5e7eb;
    color: #374151;
}

.btn-save {
    background: linear-gradient(
        135deg,
        #147cf5,
        #6c63ff
    );
    color: #fff;
    box-shadow: 0 5px 15px rgba(20, 124, 245, .20);
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 7px 18px rgba(20, 124, 245, .28);
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .timetable-create-page {
        padding: 18px;
    }

    .timetable-form-card {
        padding: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-cancel,
    .btn-save {
        width: 100%;
    }

    .timetable-header {
        padding: 22px;
    }

    .timetable-header h2 {
        font-size: 23px;
    }
}

</style>


<div class="timetable-create-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="timetable-header">

        <h2>
            <i class="bi bi-calendar-plus me-2"></i>
            Create Timetable
        </h2>

        <p>
            Add a new class timetable entry
        </p>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if ($errors->any())

        <div class="alert-error">

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="timetable-form-card">

        <form
            action="{{ route('admin.timetable.save') }}"
            method="POST"
            id="timetableForm"
        >

            @csrf


            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}

            <div class="form-section">

                <div class="section-title">

                    <div class="section-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>

                    Basic Information

                </div>


                <div class="form-grid">


                    {{-- TEACHER --}}

                    <div class="form-group">

                        <label
                            for="teacher_id"
                            class="form-label"
                        >
                            Teacher

                            <span
                                class="required-star"
                                id="teacherRequired"
                            >
                                *
                            </span>
                        </label>


                        <select
                            name="teacher_id"
                            id="teacher_id"
                            class="timetable-form-select"
                        >

                            <option value="">
                                Select Teacher
                            </option>

                            @foreach($teachers as $teacher)

                                <option
                                    value="{{ $teacher->id }}"
                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}
                                >

                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}

                                    @if(!empty($teacher->teacher_id))
                                        ({{ $teacher->teacher_id }})
                                    @endif

                                </option>

                            @endforeach

                        </select>


                        @error('teacher_id')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ACADEMIC YEAR --}}

                    <div class="form-group">

                        <label
                            for="academic_year"
                            class="form-label"
                        >
                            Academic Year

                            <span class="required-star">
                                *
                            </span>
                        </label>


                        <input
                            type="text"
                            name="academic_year"
                            id="academic_year"
                            class="timetable-form-input"
                            value="{{ old('academic_year', '2026-2027') }}"
                            placeholder="Example: 2026-2027"
                            required
                        >

                        @error('academic_year')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- CLASS --}}

                    <div class="form-group">

                        <label
                            for="class"
                            class="form-label"
                        >
                            Class / Standard

                            <span
                                class="required-star"
                                id="classRequired"
                            >
                                *
                            </span>
                        </label>


                        <select
                            name="class"
                            id="class"
                            class="form-control-custom"
                        >

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $classItem)

                                @php
                                    $classValue =
                                        $classItem->class_name
                                        ?? $classItem->name
                                        ?? '';
                                @endphp

                                <option
                                    value="{{ $classValue }}"
                                    {{ old('class') == $classValue ? 'selected' : '' }}
                                >

                                    {{ $classValue }}

                                </option>

                            @endforeach

                        </select>


                        @error('class')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SECTION --}}

                    <div class="form-group">

                        <label
                            for="section"
                            class="form-label"
                        >
                            Section

                            <span
                                class="required-star"
                                id="sectionRequired"
                            >
                                *
                            </span>
                        </label>


                        <select
                            name="section"
                            id="section"
                            class="timetable-form-select"
                            disabled
                        >

                            <option value="">
                                Select Section
                            </option>

                        </select>


                        <div
                            class="form-help"
                            id="sectionHelp"
                        >
                            Select a class to load available sections.
                        </div>


                        @error('section')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PERIOD INFORMATION
            ================================================== --}}

            <div class="form-section">

                <div class="section-title">

                    <div class="section-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    Period Information

                </div>


                <div class="form-grid">


                    {{-- DATE --}}

                    <div class="form-group">

                        <label
                            for="timetable_date"
                            class="form-label"
                        >
                            Timetable Date

                            <span class="required-star">
                                *
                            </span>
                        </label>


                        <input
                            type="date"
                            name="timetable_date"
                            id="timetable_date"
                            class="timetable-form-input"
                            value="{{ old('timetable_date', date('Y-m-d')) }}"
                            required
                        >


                        @error('timetable_date')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DAY --}}

                    <div class="form-group">

                        <label
                            for="day"
                            class="form-label"
                        >
                            Day
                        </label>


                        <input
                            type="text"
                            name="day"
                            id="day"
                            class="timetable-form-input"
                            value="{{ old('day') }}"
                            readonly
                        >

                    </div>


                    {{-- PERIOD NUMBER --}}

                    <div class="form-group">

                        <label
                            for="period_number"
                            class="form-label"
                        >
                            Period Number

                            <span class="required-star">
                                *
                            </span>
                        </label>


                        <input
                            type="number"
                            name="period_number"
                            id="period_number"
                            class="timetable-form-input"
                            value="{{ old('period_number') }}"
                            min="1"
                            max="15"
                            placeholder="Example: 1"
                            required
                        >

                        @error('period_number')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PERIOD TYPE --}}

                    <div class="form-group">

                        <label
                            for="period_type"
                            class="form-label"
                        >
                            Period Type

                            <span class="required-star">
                                *
                            </span>
                        </label>


                        <select
                            name="period_type"
                            id="period_type"
                            class="timetable-form-select"
                            required
                        >

                            <option value="">
                                Select Period Type
                            </option>

                            <option
                                value="Regular"
                                {{ old('period_type') == 'Regular' ? 'selected' : '' }}
                            >
                                Regular Class
                            </option>

                            <option
                                value="Break"
                                {{ old('period_type') == 'Break' ? 'selected' : '' }}
                            >
                                Break
                            </option>

                            <option
                                value="Lunch"
                                {{ old('period_type') == 'Lunch' ? 'selected' : '' }}
                            >
                                Lunch
                            </option>

                            <option
                                value="Activity"
                                {{ old('period_type') == 'Activity' ? 'selected' : '' }}
                            >
                                Activity
                            </option>

                        </select>

                        @error('period_type')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- LECTURE TYPE --}}

                    <div class="form-group">

                        <label
                            for="lecture_type"
                            class="form-label"
                        >
                            Lecture Type
                        </label>


                        <select
                            name="lecture_type"
                            id="lecture_type"
                            class="timetable-form-select"
                        >

                            <option
                                value="regular"
                                {{ old('lecture_type', 'regular') == 'regular' ? 'selected' : '' }}
                            >
                                Regular Lecture
                            </option>

                            <option
                                value="extra"
                                {{ old('lecture_type') == 'extra' ? 'selected' : '' }}
                            >
                                Extra Lecture
                            </option>

                            <option
                                value="practical"
                                {{ old('lecture_type') == 'practical' ? 'selected' : '' }}
                            >
                                Practical
                            </option>

                            <option
                                value="activity"
                                {{ old('lecture_type') == 'activity' ? 'selected' : '' }}
                            >
                                Activity
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SUBJECT INFORMATION
            ================================================== --}}

            <div class="form-section">

                <div class="section-title">

                    <div class="section-icon">
                        <i class="bi bi-book"></i>
                    </div>

                    Subject Information

                </div>


                <div class="form-grid">


                    {{-- SUBJECT / ACTIVITY --}}

                    <div class="form-group">

                        <label
                            for="subject"
                            class="form-label"
                        >
                            Subject / Activity

                            <span
                                class="required-star"
                                id="subjectRequired"
                            >
                                *
                            </span>
                        </label>


                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            class="timetable-form-input"
                            value="{{ old('subject') }}"
                            placeholder="Example: Mathematics"
                        >


                        <select
                            id="activitySubject"
                            class="timetable-form-select"
                            style="display:none;"
                        >

                            <option value="">
                                Select Activity
                            </option>

                            <option value="PT">
                                PT
                            </option>

                            <option value="Dance">
                                Dance
                            </option>

                            <option value="Singing">
                                Singing
                            </option>

                            <option value="Art & Craft">
                                Art & Craft
                            </option>

                        </select>


                        <div
                            class="form-help"
                            id="activityHelp"
                            style="display:none;"
                        >
                            Select the activity for this timetable period.
                        </div>


                        @error('subject')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SUBJECT TYPE --}}

                    <div class="form-group">

                        <label
                            for="subject_type"
                            class="form-label"
                        >
                            Subject Type

                            <span
                                class="required-star"
                                id="subjectTypeRequired"
                            >
                                *
                            </span>
                        </label>


                        <select
                            name="subject_type"
                            id="subject_type"
                            class="timetable-form-select"
                        >

                            <option value="">
                                Select Subject Type
                            </option>

                            <option
                                value="Theory"
                                {{ old('subject_type') == 'Theory' ? 'selected' : '' }}
                            >
                                Theory
                            </option>

                            <option
                                value="Practical"
                                {{ old('subject_type') == 'Practical' ? 'selected' : '' }}
                            >
                                Practical
                            </option>

                            <option
                                value="Activity"
                                {{ old('subject_type') == 'Activity' ? 'selected' : '' }}
                            >
                                Activity
                            </option>

                        </select>


                        @error('subject_type')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                 TIME & ROOM
            ================================================== --}}

            <div class="form-section">

                <div class="section-title">

                    <div class="section-icon">
                        <i class="bi bi-stopwatch"></i>
                    </div>

                    Time & Room

                </div>


                <div class="form-grid">


                    {{-- START TIME --}}

                    <div class="form-group">

                        <label
                            for="start_time"
                            class="form-label"
                        >
                            Start Time

                            <span class="required-star">
                                *
                            </span>
                        </label>


                        <input
                            type="time"
                            name="start_time"
                            id="start_time"
                            class="timetable-form-input"
                            value="{{ old('start_time') }}"
                            required
                        >


                        <div
                            class="form-help"
                            id="timeHelp"
                        >
                            Standard lecture duration is 45 minutes.
                        </div>


                        @error('start_time')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- END TIME --}}

                    <div class="form-group">

                        <label
                            for="end_time"
                            class="form-label"
                        >
                            End Time
                        </label>


                        <input
                            type="time"
                            name="end_time"
                            id="end_time"
                            class="timetable-form-input"
                            value="{{ old('end_time') }}"
                        >

                        @error('end_time')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ROOM --}}

                    <div class="form-group full-width">

                        <label
                            for="room"
                            class="form-label"
                        >
                            Room
                        </label>


                        <input
                            type="text"
                            name="room"
                            id="room"
                            class="timetable-form-input"
                            value="{{ old('room') }}"
                            placeholder="Example: Room 101"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('admin.timetable.index') }}"
                    class="btn-cancel"
                >
                    <i class="bi bi-arrow-left"></i>
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn-save"
                >
                    <i class="bi bi-check-circle"></i>
                    Save Timetable
                </button>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('timetableForm');

    const classSelect = document.getElementById('class');
    const sectionSelect = document.getElementById('section');
    const sectionHelp = document.getElementById('sectionHelp');

    const teacherSelect = document.getElementById('teacher_id');

    const periodTypeSelect = document.getElementById('period_type');
    const lectureTypeSelect = document.getElementById('lecture_type');

    const subjectInput = document.getElementById('subject');
    const activitySubject = document.getElementById('activitySubject');
    const activityHelp = document.getElementById('activityHelp');

    const subjectTypeSelect = document.getElementById('subject_type');

    const timetableDate = document.getElementById('timetable_date');
    const dayInput = document.getElementById('day');

    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    const timeHelp = document.getElementById('timeHelp');

    const teacherRequired = document.getElementById('teacherRequired');
    const classRequired = document.getElementById('classRequired');
    const sectionRequired = document.getElementById('sectionRequired');
    const subjectRequired = document.getElementById('subjectRequired');
    const subjectTypeRequired = document.getElementById('subjectTypeRequired');


    /* =========================================================
       DATA FROM CONTROLLER
    ========================================================= */

    const allClasses = @json($classes);
    const allSections = @json($sections);

    const oldSection = @json(old('section'));
    const oldSubject = @json(old('subject'));


    /* =========================================================
       LOAD SECTIONS
    ========================================================= */

    function loadSections(className, selectedSection = '') {

        sectionSelect.innerHTML =
            '<option value="">Select Section</option>';

        sectionSelect.disabled = true;

        sectionHelp.textContent =
            'Select a class to load available sections.';

        sectionHelp.style.color =
            '#6b7280';


        if (!className) {
            return;
        }


        const selectedClass = allClasses.find(function (item) {

            const classValue =
                item.class_name ??
                item.name ??
                '';

            return String(classValue).trim()
                === String(className).trim();

        });


        if (!selectedClass) {

            sectionHelp.textContent =
                'No matching class was found.';

            sectionHelp.style.color =
                '#dc2626';

            return;

        }


        const classSections = allSections.filter(function (section) {

            return String(section.class_id)
                === String(selectedClass.id);

        });


        if (classSections.length === 0) {

            sectionHelp.textContent =
                'No sections available for this class.';

            sectionHelp.style.color =
                '#dc2626';

            return;

        }


        sectionSelect.disabled = false;

        sectionHelp.textContent =
            'Select the section for this class.';


        classSections.forEach(function (section) {

            const option =
                document.createElement('option');

            option.value =
                section.section_name;

            option.textContent =
                section.section_name;


            if (
                String(section.section_name)
                === String(selectedSection)
            ) {

                option.selected = true;

            }


            sectionSelect.appendChild(option);

        });

    }


    /* =========================================================
       CLASS CHANGE
    ========================================================= */

    classSelect.addEventListener('change', function () {

        loadSections(this.value);

    });


    /* =========================================================
       ACTIVITY CHANGE
    ========================================================= */

    activitySubject.addEventListener('change', function () {

        subjectInput.value = this.value;

    });


    /* =========================================================
       PERIOD TYPE
    ========================================================= */

    function toggleFields() {

        const periodType =
            periodTypeSelect.value;


        /* =====================================================
           DEFAULT STATE
        ===================================================== */

        teacherSelect.disabled = false;
        classSelect.disabled = false;

        teacherSelect.setAttribute('required', 'required');
        classSelect.setAttribute('required', 'required');

        subjectInput.disabled = false;
        subjectInput.style.display = 'block';
        subjectInput.setAttribute('required', 'required');

        activitySubject.style.display = 'none';
        activitySubject.disabled = true;
        activitySubject.removeAttribute('required');

        activityHelp.style.display = 'none';

        lectureTypeSelect.disabled = false;

        /*
         * IMPORTANT:
         * Subject Type is NEVER disabled for Activity.
         * This allows Laravel to receive subject_type=Activity.
         */
        subjectTypeSelect.disabled = false;
        subjectTypeSelect.setAttribute('required', 'required');

        teacherRequired.style.display = 'inline';
        classRequired.style.display = 'inline';
        sectionRequired.style.display = 'inline';
        subjectRequired.style.display = 'inline';
        subjectTypeRequired.style.display = 'inline';


        if (classSelect.value) {
            sectionSelect.disabled = false;
        } else {
            sectionSelect.disabled = true;
        }


        /* =====================================================
           BREAK
        ===================================================== */

        if (periodType === 'Break') {

            teacherSelect.disabled = true;
            classSelect.disabled = true;
            sectionSelect.disabled = true;

            teacherSelect.removeAttribute('required');
            classSelect.removeAttribute('required');
            sectionSelect.removeAttribute('required');

            subjectInput.value = 'Break';
            subjectInput.disabled = true;
            subjectInput.removeAttribute('required');

            subjectTypeSelect.value = 'Activity';

            /*
             * Break does not need subject_type validation.
             * Disabled is okay here because Laravel does not
             * require it for Break.
             */
            subjectTypeSelect.disabled = true;
            subjectTypeSelect.removeAttribute('required');

            lectureTypeSelect.value = 'activity';
            lectureTypeSelect.disabled = true;
            lectureTypeSelect.removeAttribute('required');

            activitySubject.style.display = 'none';
            activitySubject.disabled = true;
            activitySubject.removeAttribute('required');

            teacherRequired.style.display = 'none';
            classRequired.style.display = 'none';
            sectionRequired.style.display = 'none';
            subjectRequired.style.display = 'none';
            subjectTypeRequired.style.display = 'none';

            timeHelp.textContent =
                'Break duration is automatically set to 15 minutes.';

            return;
        }


        /* =====================================================
           LUNCH
        ===================================================== */

        if (periodType === 'Lunch') {

            teacherSelect.disabled = true;
            classSelect.disabled = true;
            sectionSelect.disabled = true;

            teacherSelect.removeAttribute('required');
            classSelect.removeAttribute('required');
            sectionSelect.removeAttribute('required');

            subjectInput.value = 'Lunch';
            subjectInput.disabled = true;
            subjectInput.removeAttribute('required');

            subjectTypeSelect.value = 'Activity';

            /*
             * Lunch does not need subject_type validation.
             */
            subjectTypeSelect.disabled = true;
            subjectTypeSelect.removeAttribute('required');

            lectureTypeSelect.value = 'activity';
            lectureTypeSelect.disabled = true;
            lectureTypeSelect.removeAttribute('required');

            activitySubject.style.display = 'none';
            activitySubject.disabled = true;
            activitySubject.removeAttribute('required');

            teacherRequired.style.display = 'none';
            classRequired.style.display = 'none';
            sectionRequired.style.display = 'none';
            subjectRequired.style.display = 'none';
            subjectTypeRequired.style.display = 'none';

            timeHelp.textContent =
                'Lunch duration is automatically set to 60 minutes.';

            return;
        }


        /* =====================================================
           ACTIVITY
        ===================================================== */

        if (periodType === 'Activity') {

            /*
             * TEACHER
             * Must remain visible and selectable.
             */
            teacherSelect.disabled = false;
            teacherSelect.setAttribute('required', 'required');


            /*
             * CLASS
             * Must remain visible and selectable.
             */
            classSelect.disabled = false;
            classSelect.setAttribute('required', 'required');


            /*
             * SECTION
             * Must remain available when a class is selected.
             */
            if (classSelect.value) {

                sectionSelect.disabled = false;
                sectionSelect.setAttribute('required', 'required');

            } else {

                sectionSelect.disabled = true;
                sectionSelect.setAttribute('required', 'required');

            }


            /*
             * ACTIVITY DROPDOWN
             */
            subjectInput.style.display = 'none';
            subjectInput.disabled = false;
            subjectInput.removeAttribute('required');

            activitySubject.style.display = 'block';
            activitySubject.disabled = false;
            activitySubject.setAttribute('required', 'required');

            activityHelp.style.display = 'block';


            /*
             * SUBJECT TYPE
             *
             * IMPORTANT:
             * Do NOT disable this select.
             * Laravel must receive:
             *
             * subject_type = Activity
             */
            subjectTypeSelect.value = 'Activity';
            subjectTypeSelect.disabled = false;
            subjectTypeSelect.removeAttribute('required');


            /*
             * LECTURE TYPE
             */
            lectureTypeSelect.value = 'activity';
            lectureTypeSelect.disabled = true;
            lectureTypeSelect.removeAttribute('required');


            teacherRequired.style.display = 'inline';
            classRequired.style.display = 'inline';
            sectionRequired.style.display = 'inline';
            subjectRequired.style.display = 'inline';

            /*
             * Subject Type is automatically Activity,
             * therefore no red star is necessary.
             */
            subjectTypeRequired.style.display = 'none';


            timeHelp.textContent =
                'Activity period: enter the start and end time.';


            /*
             * Restore old activity after validation error.
             */
            if (oldSubject) {

                const activityOption =
                    Array.from(activitySubject.options)
                    .find(function (option) {

                        return option.value === oldSubject;

                    });


                if (activityOption) {

                    activitySubject.value =
                        oldSubject;

                }

            }


            return;
        }


        /* =====================================================
           REGULAR
        ===================================================== */

        teacherSelect.disabled = false;
        classSelect.disabled = false;

        teacherSelect.setAttribute('required', 'required');
        classSelect.setAttribute('required', 'required');


        subjectInput.style.display = 'block';
        subjectInput.disabled = false;
        subjectInput.setAttribute('required', 'required');


        activitySubject.style.display = 'none';
        activitySubject.disabled = true;
        activitySubject.removeAttribute('required');
        activitySubject.value = '';


        lectureTypeSelect.disabled = false;


        subjectTypeSelect.disabled = false;
        subjectTypeSelect.setAttribute('required', 'required');


        teacherRequired.style.display = 'inline';
        classRequired.style.display = 'inline';
        sectionRequired.style.display = 'inline';
        subjectRequired.style.display = 'inline';
        subjectTypeRequired.style.display = 'inline';


        if (classSelect.value) {

            sectionSelect.disabled = false;

        } else {

            sectionSelect.disabled = true;

        }


        if (
            subjectInput.value === 'Break' ||
            subjectInput.value === 'Lunch'
        ) {

            subjectInput.value = '';

        }

    }


    periodTypeSelect.addEventListener(
        'change',
        function () {

            toggleFields();

            calculateEndTime();

        }
    );


    /* =========================================================
       DATE → DAY
    ========================================================= */

    function updateDay() {

        if (!timetableDate.value) {

            dayInput.value = '';

            return;

        }


        const date =
            new Date(
                timetableDate.value + 'T00:00:00'
            );


        const days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];


        dayInput.value =
            days[date.getDay()];

    }


    timetableDate.addEventListener(
        'change',
        updateDay
    );

    updateDay();


    /* =========================================================
       AUTOMATIC END TIME
    ========================================================= */

    function calculateEndTime() {

        const startValue =
            startTimeInput.value;


        if (!startValue) {
            return;
        }


        const lectureType =
            lectureTypeSelect.value;

        const periodType =
            periodTypeSelect.value;


        const parts =
            startValue.split(':').map(Number);


        const hours = parts[0];
        const minutes = parts[1];


        let duration = null;
        let message = '';


        if (periodType === 'Break') {

            duration = 15;

            message =
                'Break: 15 minutes automatically applied.';

        }

        else if (periodType === 'Lunch') {

            duration = 60;

            message =
                'Lunch: 1 hour automatically applied.';

        }

        else if (periodType === 'Activity') {

            timeHelp.textContent =
                'Activity period: enter the start and end time.';

            timeHelp.style.color =
                '#6b7280';

            return;

        }

        else if (lectureType === 'regular') {

            duration = 45;

            message =
                'Regular lecture: 45 minutes automatically applied.';

        }

        else {

            return;

        }


        const totalMinutes =
            (
                hours * 60 +
                minutes +
                duration
            ) % 1440;


        const endHours =
            Math.floor(totalMinutes / 60);

        const endMinutes =
            totalMinutes % 60;


        endTimeInput.value =
            String(endHours).padStart(2, '0')
            + ':' +
            String(endMinutes).padStart(2, '0');


        timeHelp.textContent =
            message;

        timeHelp.style.color =
            '#6b7280';

    }


    startTimeInput.addEventListener(
        'change',
        function () {

            endTimeInput.value = '';

            calculateEndTime();

        }
    );


    /* =========================================================
       LECTURE TYPE
    ========================================================= */

    lectureTypeSelect.addEventListener(
        'change',
        function () {

            if (
                periodTypeSelect.value === 'Activity'
            ) {

                endTimeInput.value = '';

                timeHelp.textContent =
                    'Activity period: enter the start and end time.';

                return;

            }


            if (
                periodTypeSelect.value === 'Break' ||
                periodTypeSelect.value === 'Lunch'
            ) {

                calculateEndTime();

                return;

            }


            if (this.value === 'regular') {

                calculateEndTime();

            } else {

                endTimeInput.value = '';

                timeHelp.textContent =
                    'Enter a custom end time for this lecture.';

            }

        }
    );


    /* =========================================================
       END TIME VALIDATION
    ========================================================= */

    endTimeInput.addEventListener(
        'change',
        function () {

            if (
                !startTimeInput.value ||
                !endTimeInput.value
            ) {

                return;

            }


            const start =
                startTimeInput.value
                .split(':')
                .map(Number);


            const end =
                endTimeInput.value
                .split(':')
                .map(Number);


            const startMinutes =
                start[0] * 60 + start[1];


            const endMinutes =
                end[0] * 60 + end[1];


            if (endMinutes <= startMinutes) {

                timeHelp.textContent =
                    'End time must be after start time.';

                timeHelp.style.color =
                    '#dc2626';

                return;

            }


            const duration =
                endMinutes - startMinutes;


            timeHelp.textContent =
                `Custom duration: ${duration} minutes.`;

            timeHelp.style.color =
                '#16a34a';

        }
    );


    /* =========================================================
       FORM SUBMIT
    ========================================================= */

    form.addEventListener('submit', function (event) {

        const periodType =
            periodTypeSelect.value;


        /* =====================================================
           ACTIVITY
        ===================================================== */

        if (periodType === 'Activity') {

            const selectedActivity =
                activitySubject.value;


            if (!selectedActivity) {

                event.preventDefault();

                alert('Please select an activity.');

                activitySubject.focus();

                return;

            }


            /*
             * Put selected activity into the real
             * name="subject" input.
             */
            subjectInput.value =
                selectedActivity;


            /*
             * VERY IMPORTANT:
             * Ensure Laravel receives subject_type.
             */
            subjectTypeSelect.value =
                'Activity';

            subjectTypeSelect.disabled =
                false;

        }


        /* =====================================================
           BREAK
        ===================================================== */

        if (periodType === 'Break') {

            subjectInput.value =
                'Break';

        }


        /* =====================================================
           LUNCH
        ===================================================== */

        if (periodType === 'Lunch') {

            subjectInput.value =
                'Lunch';

        }


        /* =====================================================
           SUNDAY PROTECTION
        ===================================================== */

        if (dayInput.value === 'Sunday') {

            event.preventDefault();

            alert(
                'Sunday timetable is not allowed.'
            );

            return;

        }

    });


    /* =========================================================
       INITIAL SECTION LOAD
    ========================================================= */

    if (classSelect.value) {

        loadSections(
            classSelect.value,
            oldSection
        );

    }


    /* =========================================================
       INITIAL FIELD STATE
    ========================================================= */

    toggleFields();

});

</script>

@endsection