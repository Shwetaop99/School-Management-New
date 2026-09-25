
@extends('layouts.app')

@section('title', 'Create Timetable')
@section('page-title', 'Create Timetable')

@section('content')

<style>
    .timetable-page {
        background: #f4f7fb;
        min-height: calc(100vh - 80px);
        padding: 28px;
    }

    .timetable-wrapper {
        max-width: 1450px;
        margin: 0 auto;
    }

    .page-header-card {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: white;
        border-radius: 18px;
        padding: 25px 30px;
        margin-bottom: 22px;
        box-shadow: 0 8px 25px rgba(20, 124, 245, 0.15);
    }

    .page-header-card h2 {
        margin: 0 0 6px;
        font-size: 25px;
        font-weight: 700;
    }

    .page-header-card p {
        margin: 0;
        opacity: .9;
        font-size: 14px;
    }

    .form-card {
        background: white;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 5px 20px rgba(31, 45, 61, 0.07);
        margin-bottom: 22px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: 700;
        color: #26364a;
        margin-bottom: 22px;
        padding-bottom: 13px;
        border-bottom: 1px solid #edf1f7;
    }

    .section-title i {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #eef5ff;
        color: #147cf5;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #344054;
        margin-bottom: 8px;
    }

    .required-star {
        color: #e63946;
    }

    .timetable-form-control,
    .timetable-form-select {
        width: 100%;
        height: 46px;
        border: 1px solid #d9e1ec;
        border-radius: 10px;
        padding: 0 13px;
        font-size: 14px;
        color: #26364a;
        background: #fff;
        outline: none;
        transition: .2s ease;
        box-sizing: border-box;
    }

    .timetable-form-control:focus,
    .timetable-form-select:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .10);
    }

    .timetable-form-control:disabled,
    .timetable-form-select:disabled {
        background: #f2f4f7;
        color: #98a2b3;
        cursor: not-allowed;
    }

    .error-message {
        color: #e63946;
        font-size: 12px;
        margin-top: 6px;
    }

    .field-help {
        color: #8a96a8;
        font-size: 11px;
        margin-top: 5px;
    }

    .activity-group {
        display: none;
    }

    .activity-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: #6c63ff;
        margin-top: 5px;
    }

    .button-area {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 10px;
    }

    .btn-cancel,
    .btn-save {
        height: 46px;
        padding: 0 23px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-cancel {
        background: #eef1f5;
        color: #475467;
    }

    .btn-cancel:hover {
        background: #e2e6eb;
        color: #344054;
    }

    .btn-save {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: white;
        box-shadow: 0 5px 15px rgba(20, 124, 245, .18);
    }

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(20, 124, 245, .25);
    }

    .alert-error {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #b42318;
        border-radius: 10px;
        padding: 13px 16px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    @media (max-width: 1100px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {
        .timetable-page {
            padding: 15px;
        }

        .form-card {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .button-area {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
        }
    }
</style>

<div class="timetable-page">

    <div class="timetable-wrapper">

        {{-- PAGE HEADER --}}
        <div class="page-header-card">
            <h2>
                <i class="fas fa-calendar-plus"></i>
                Create Timetable
            </h2>

            <p>
                Create a timetable entry for teachers, classes, subjects and activities.
            </p>
        </div>


        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="alert-error">
                <strong>Please fix the following:</strong>

                <ul style="margin:8px 0 0 18px; padding:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form
            action="{{ route('admin.timetable.save') }}"
            method="POST"
            id="timetableForm"
        >

            @csrf


            {{-- ========================================================= --}}
            {{-- BASIC INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="form-card">

                <div class="section-title">
                    <i class="fas fa-users"></i>
                    Basic Information
                </div>

                <div class="form-grid">

                    {{-- TEACHER --}}
                    <div class="form-group">

                        <label class="form-label" for="teacher_id">
                            Teacher
                            <span class="required-star" id="teacherRequired">*</span>
                        </label>

                        <select
                            name="teacher_id"
                            id="teacher_id"
                            class="timetable-form-select"
                        >
                            <option value="">Select Teacher</option>

                            @foreach ($teachers as $teacher)
                                <option
                                    value="{{ $teacher->id }}"
                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}
                                >
                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}
                                    @if($teacher->teacher_id)
                                        ({{ $teacher->teacher_id }})
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('teacher_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- ACADEMIC YEAR --}}
                    <div class="form-group">

                        <label class="form-label" for="academic_year">
                            Academic Year
                            <span class="required-star">*</span>
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            id="academic_year"
                            class="timetable-form-control"
                            value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                            placeholder="Example: 2026-2027"
                            required
                        >

                        @error('academic_year')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- CLASS --}}
                    <div class="form-group">

                        <label class="form-label" for="class">
                            Class
                            <span class="required-star" id="classRequired">*</span>
                        </label>

                        <select
                            name="class"
                            id="class"
                            class="timetable-form-select"
                        >
                            <option value="">Select Class</option>

                            @foreach ($classes as $class)
                                <option
                                    value="{{ $class->class_name }}"
                                    data-class-id="{{ $class->id }}"
                                    {{ old('class') == $class->class_name ? 'selected' : '' }}
                                >
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('class')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- SECTION --}}
                    <div class="form-group">

                        <label class="form-label" for="section">
                            Section
                        </label>

                        <select
                            name="section"
                            id="section"
                            class="timetable-form-select"
                        >
                            <option value="">Select Section</option>
                        </select>

                        @error('section')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- PERIOD INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="form-card">

                <div class="section-title">
                    <i class="fas fa-clock"></i>
                    Period Information
                </div>

                <div class="form-grid">

                    {{-- DATE --}}
                    <div class="form-group">

                        <label class="form-label" for="timetable_date">
                            Timetable Date
                            <span class="required-star">*</span>
                        </label>

                        <input
                            type="date"
                            name="timetable_date"
                            id="timetable_date"
                            class="timetable-form-control"
                            value="{{ old('timetable_date') }}"
                            required
                        >

                        @error('timetable_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- DAY --}}
                    <div class="form-group">

                        <label class="form-label" for="day">
                            Day
                            <span class="required-star">*</span>
                        </label>

                        <select
                            name="day"
                            id="day"
                            class="timetable-form-select"
                            required
                        >
                            <option value="">Select Day</option>

                            @foreach([
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday'
                            ] as $day)
                                <option
                                    value="{{ $day }}"
                                    {{ old('day') == $day ? 'selected' : '' }}
                                >
                                    {{ $day }}
                                </option>
                            @endforeach

                        </select>

                        @error('day')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- PERIOD NUMBER --}}
                    <div class="form-group">

                        <label class="form-label" for="period_number">
                            Period Number
                            <span class="required-star">*</span>
                        </label>

                        <select
                            name="period_number"
                            id="period_number"
                            class="timetable-form-select"
                            required
                        >
                            <option value="">Select Period</option>

                            @for($i = 1; $i <= 15; $i++)
                                <option
                                    value="{{ $i }}"
                                    {{ old('period_number') == $i ? 'selected' : '' }}
                                >
                                    Period {{ $i }}
                                </option>
                            @endfor

                        </select>

                        @error('period_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- PERIOD TYPE --}}
                    <div class="form-group">

                        <label class="form-label" for="period_type">
                            Period Type
                            <span class="required-star">*</span>
                        </label>

                        <select
                            name="period_type"
                            id="period_type"
                            class="timetable-form-select"
                            required
                        >
                            <option value="">Select Period Type</option>

                            <option
                                value="Regular"
                                {{ old('period_type') == 'Regular' ? 'selected' : '' }}
                            >
                                Regular
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
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- LECTURE TYPE --}}
                    <div class="form-group">

                        <label class="form-label" for="lecture_type">
                            Lecture Type
                        </label>

                        <select
                            name="lecture_type"
                            id="lecture_type"
                            class="timetable-form-select"
                        >
                            <option value="">Select Lecture Type</option>

                            <option
                                value="regular"
                                {{ old('lecture_type') == 'regular' ? 'selected' : '' }}
                            >
                                Regular
                            </option>

                            <option
                                value="extra"
                                {{ old('lecture_type') == 'extra' ? 'selected' : '' }}
                            >
                                Extra
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

                        @error('lecture_type')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SUBJECT / ACTIVITY INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="form-card">

                <div class="section-title">
                    <i class="fas fa-book"></i>
                    Subject Information
                </div>

                <div class="form-grid">

                    {{-- SUBJECT --}}
                    <div class="form-group" id="subjectGroup">

                        <label class="form-label" for="subject">
                            Subject
                            <span class="required-star" id="subjectRequired">*</span>
                        </label>

                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            class="timetable-form-control"
                            value="{{ old('subject') }}"
                            placeholder="Enter subject name"
                        >

                        @error('subject')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- ACTIVITY --}}
                    <div class="form-group activity-group" id="activityGroup">

                        <label class="form-label" for="activity_name">
                            Activity
                            <span class="required-star">*</span>
                        </label>

                        <select
                            name="activity_name"
                            id="activity_name"
                            class="timetable-form-select"
                        >
                            <option value="">Select Activity</option>

                            <option
                                value="PT"
                                {{ old('activity_name') == 'PT' ? 'selected' : '' }}
                            >
                                PT
                            </option>

                            <option
                                value="Dance"
                                {{ old('activity_name') == 'Dance' ? 'selected' : '' }}
                            >
                                Dance
                            </option>

                            <option
                                value="Singing"
                                {{ old('activity_name') == 'Singing' ? 'selected' : '' }}
                            >
                                Singing
                            </option>

                            <option
                                value="Drawing"
                                {{ old('activity_name') == 'Drawing' ? 'selected' : '' }}
                            >
                                Drawing
                            </option>

                        </select>

                        <div class="activity-badge">
                            <i class="fas fa-star"></i>
                            Select the activity for this period
                        </div>

                        @error('activity_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- SUBJECT TYPE --}}
                    <div class="form-group">

                        <label class="form-label" for="subject_type">
                            Subject Type
                            <span class="required-star" id="subjectTypeRequired">*</span>
                        </label>

                        <select
                            name="subject_type"
                            id="subject_type"
                            class="timetable-form-select"
                        >
                            <option value="">Select Subject Type</option>

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
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- TIME & ROOM --}}
            {{-- ========================================================= --}}

            <div class="form-card">

                <div class="section-title">
                    <i class="fas fa-stopwatch"></i>
                    Time & Room
                </div>

                <div class="form-grid">

                    {{-- START TIME --}}
                    <div class="form-group">

                        <label class="form-label" for="start_time">
                            Start Time
                            <span class="required-star">*</span>
                        </label>

                        <input
                            type="time"
                            name="start_time"
                            id="start_time"
                            class="timetable-form-control"
                            value="{{ old('start_time') }}"
                            required
                        >

                        @error('start_time')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- END TIME --}}
                    <div class="form-group">

                        <label class="form-label" for="end_time">
                            End Time
                        </label>

                        <input
                            type="time"
                            name="end_time"
                            id="end_time"
                            class="timetable-form-control"
                            value="{{ old('end_time') }}"
                        >

                        <div class="field-help" id="timeHelp">
                            Regular lectures automatically use 45 minutes.
                        </div>

                        @error('end_time')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- ROOM --}}
                    <div class="form-group">

                        <label class="form-label" for="room">
                            Room
                        </label>

                        <input
                            type="text"
                            name="room"
                            id="room"
                            class="timetable-form-control"
                            value="{{ old('room') }}"
                            placeholder="Example: Room 101"
                        >

                        @error('room')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- BUTTONS --}}
            {{-- ========================================================= --}}

            <div class="button-area">

                <a
                    href="{{ route('admin.timetable.index') }}"
                    class="btn-cancel"
                >
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-save"
                >
                    <i class="fas fa-save"></i>
                    Save Timetable
                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const allSections = @json($sections);

    const classSelect = document.getElementById('class');
    const sectionSelect = document.getElementById('section');

    const periodType = document.getElementById('period_type');

    const teacherSelect = document.getElementById('teacher_id');

    const subjectInput = document.getElementById('subject');
    const subjectTypeSelect = document.getElementById('subject_type');

    const activityGroup = document.getElementById('activityGroup');
    const activitySelect = document.getElementById('activity_name');

    const lectureTypeSelect = document.getElementById('lecture_type');

    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');

    const dateInput = document.getElementById('timetable_date');
    const daySelect = document.getElementById('day');

    const timeHelp = document.getElementById('timeHelp');

    const teacherRequired = document.getElementById('teacherRequired');
    const classRequired = document.getElementById('classRequired');

    const subjectRequired = document.getElementById('subjectRequired');
    const subjectTypeRequired = document.getElementById('subjectTypeRequired');


    /*
    |--------------------------------------------------------------------------
    | LOAD SECTIONS
    |--------------------------------------------------------------------------
    */

    function loadSections(selectedSection = '') {

        const selectedOption =
            classSelect.options[classSelect.selectedIndex];

        const classId =
            selectedOption
                ? selectedOption.dataset.classId
                : null;

        sectionSelect.innerHTML =
            '<option value="">Select Section</option>';

        if (!classId) {
            return;
        }

        allSections.forEach(function (section) {

            if (String(section.class_id) === String(classId)) {

                const option =
                    document.createElement('option');

                option.value =
                    section.section_name;

                option.textContent =
                    section.section_name;

                if (
                    selectedSection &&
                    selectedSection === section.section_name
                ) {
                    option.selected = true;
                }

                sectionSelect.appendChild(option);
            }
        });
    }


    classSelect.addEventListener('change', function () {
        loadSections();
    });


    /*
    |--------------------------------------------------------------------------
    | DATE → DAY
    |--------------------------------------------------------------------------
    */

    function updateDayFromDate() {

        if (!dateInput.value) {

            daySelect.value = '';

            return;
        }

        const date =
            new Date(
                dateInput.value + 'T00:00:00'
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

        const day =
            days[date.getDay()];

        if (day === 'Sunday') {

            daySelect.value = '';

            alert(
                'Sunday timetable is not allowed.'
            );

            return;
        }

        daySelect.value = day;
    }


    dateInput.addEventListener(
        'change',
        updateDayFromDate
    );


    /*
    |--------------------------------------------------------------------------
    | TOGGLE PERIOD FIELDS
    |--------------------------------------------------------------------------
    */

    function toggleFields() {

        const type =
            periodType.value;

        const isBreak =
            type === 'Break';

        const isLunch =
            type === 'Lunch';

        const isActivity =
            type === 'Activity';

        /*
        |--------------------------------------------------------------------------
        | BREAK / LUNCH
        |--------------------------------------------------------------------------
        */

        if (isBreak || isLunch) {

            teacherSelect.value = '';
            teacherSelect.disabled = true;

            classSelect.value = '';
            classSelect.disabled = true;

            sectionSelect.innerHTML =
                '<option value="">Select Section</option>';

            sectionSelect.value = '';
            sectionSelect.disabled = true;

            subjectInput.value =
                type;

            subjectInput.disabled = true;
            subjectInput.required = false;

            activityGroup.style.display =
                'none';

            activitySelect.value = '';
            activitySelect.disabled = true;
            activitySelect.required = false;

            subjectTypeSelect.value =
                'Activity';

            subjectTypeSelect.disabled = true;
            subjectTypeSelect.required = false;

            lectureTypeSelect.value =
                'activity';

            lectureTypeSelect.disabled = true;

            subjectRequired.style.display =
                'none';

            subjectTypeRequired.style.display =
                'none';

            teacherRequired.style.display =
                'none';

            classRequired.style.display =
                'none';

            timeHelp.textContent =
                isBreak
                    ? 'Break duration is automatically set to 15 minutes.'
                    : 'Lunch duration is automatically set to 60 minutes.';

            calculateEndTime();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL / ACTIVITY
        |--------------------------------------------------------------------------
        */

        teacherSelect.disabled = false;

        classSelect.disabled = false;

        sectionSelect.disabled = false;

        teacherRequired.style.display =
            'inline';

        classRequired.style.display =
            'inline';


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY
        |--------------------------------------------------------------------------
        */

        if (isActivity) {

            activityGroup.style.display =
                'flex';

            activitySelect.disabled =
                false;

            activitySelect.required =
                true;

            subjectInput.value = '';

            subjectInput.disabled =
                true;

            subjectInput.required =
                false;

            subjectTypeSelect.value =
                'Activity';

            subjectTypeSelect.disabled =
                true;

            subjectTypeSelect.required =
                false;

            lectureTypeSelect.value =
                'activity';

            lectureTypeSelect.disabled =
                true;

            subjectRequired.style.display =
                'none';

            subjectTypeRequired.style.display =
                'none';

            timeHelp.textContent =
                'Enter the activity time manually.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REGULAR
        |--------------------------------------------------------------------------
        */

        activityGroup.style.display =
            'none';

        activitySelect.value = '';

        activitySelect.disabled =
            true;

        activitySelect.required =
            false;

        subjectInput.disabled =
            false;

        subjectInput.required =
            true;

        subjectTypeSelect.disabled =
            false;

        subjectTypeSelect.required =
            true;

        lectureTypeSelect.disabled =
            false;

        subjectRequired.style.display =
            'inline';

        subjectTypeRequired.style.display =
            'inline';

        timeHelp.textContent =
            'Regular lectures automatically use 45 minutes.';

        calculateEndTime();
    }


    periodType.addEventListener(
        'change',
        toggleFields
    );


    /*
    |--------------------------------------------------------------------------
    | ACTIVITY CHANGE
    |--------------------------------------------------------------------------
    */

    activitySelect.addEventListener(
        'change',
        function () {

            if (
                periodType.value === 'Activity'
            ) {

                subjectInput.value =
                    this.value;

                subjectTypeSelect.value =
                    'Activity';
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | LECTURE TYPE
    |--------------------------------------------------------------------------
    */

    lectureTypeSelect.addEventListener(
        'change',
        function () {

            if (
                periodType.value === 'Break' ||
                periodType.value === 'Lunch' ||
                periodType.value === 'Activity'
            ) {
                return;
            }

            calculateEndTime();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | TIME CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateEndTime() {

        if (!startTime.value) {
            return;
        }

        const type =
            periodType.value;

        const lectureType =
            lectureTypeSelect.value;


        /*
        |--------------------------------------------------------------------------
        | BREAK
        |--------------------------------------------------------------------------
        */

        if (type === 'Break') {

            setEndTime(
                15
            );

            timeHelp.textContent =
                'Break duration is automatically set to 15 minutes.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LUNCH
        |--------------------------------------------------------------------------
        */

        if (type === 'Lunch') {

            setEndTime(
                60
            );

            timeHelp.textContent =
                'Lunch duration is automatically set to 60 minutes.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REGULAR
        |--------------------------------------------------------------------------
        */

        if (
            lectureType === 'regular' ||
            lectureType === ''
        ) {

            setEndTime(
                45
            );

            timeHelp.textContent =
                'Regular lecture duration is automatically set to 45 minutes.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY / EXTRA / PRACTICAL
        |--------------------------------------------------------------------------
        */

        if (
            type === 'Activity' ||
            lectureType === 'extra' ||
            lectureType === 'practical'
        ) {

            timeHelp.textContent =
                'Enter the end time manually.';

            return;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SET END TIME
    |--------------------------------------------------------------------------
    */

    function setEndTime(minutes) {

        const parts =
            startTime.value.split(':');

        if (parts.length !== 2) {
            return;
        }

        let hours =
            parseInt(parts[0]);

        let mins =
            parseInt(parts[1]);

        let total =
            hours * 60 +
            mins +
            minutes;

        total =
            total % (24 * 60);

        const newHours =
            Math.floor(total / 60);

        const newMinutes =
            total % 60;

        endTime.value =
            String(newHours).padStart(2, '0') +
            ':' +
            String(newMinutes).padStart(2, '0');
    }


    startTime.addEventListener(
        'change',
        calculateEndTime
    );


    /*
    |--------------------------------------------------------------------------
    | END TIME VALIDATION
    |--------------------------------------------------------------------------
    */

    endTime.addEventListener(
        'change',
        function () {

            if (
                !startTime.value ||
                !endTime.value
            ) {
                return;
            }

            if (
                endTime.value <= startTime.value
            ) {

                alert(
                    'End time must be after start time.'
                );

                endTime.value = '';
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    const oldSection =
        @json(old('section'));

    loadSections(
        oldSection
    );

    updateDayFromDate();

    toggleFields();

});
</script>

@endsection
