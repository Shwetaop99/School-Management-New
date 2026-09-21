@extends('layouts.app')

@section('title', 'Edit Timetable')

@section('content')

<style>

    .timetable-edit-page {
        padding: 28px;
        background: #f4f7fb;
        min-height: 100%;
    }

    .timetable-edit-header {
        background: linear-gradient(135deg, #1769d1, #159cc7);
        color: #fff;
        border-radius: 18px;
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        box-shadow: 0 10px 28px rgba(23,105,209,.18);
    }

    .timetable-edit-header h2 {
        margin: 0 0 6px;
        font-size: 28px;
        font-weight: 800;
    }

    .timetable-edit-header p {
        margin: 0;
        font-size: 14px;
        opacity: .9;
    }

    .back-timetable-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 9px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .back-timetable-btn:hover {
        background: #fff;
        color: #1769d1;
    }

    .timetable-form-card {
        max-width: 1100px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(15,23,42,.06);
    }

    .timetable-form-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf1f6;
    }

    .timetable-form-header h3 {
        margin: 0;
        color: #172033;
        font-size: 18px;
        font-weight: 800;
    }

    .timetable-form-header p {
        margin: 5px 0 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .timetable-form-body {
        padding: 26px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        width: 100%;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .required {
        color: #ef4444;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #dfe6ef;
        border-radius: 9px;
        background: #fff;
        color: #334155;
        font-size: 13px;
        outline: none;
        transition: .2s ease;
        box-sizing: border-box;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23,105,209,.10);
    }

    .form-control:disabled,
    .form-select:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #475569;
        cursor: not-allowed;
    }

    .form-control::placeholder {
        color: #a0aec0;
    }

    .field-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 11px;
        font-weight: 600;
    }

    .form-help {
        margin-top: 5px;
        color: #94a3b8;
        font-size: 10px;
    }

    .form-section-title {
        margin: 5px 0 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #edf1f6;
        color: #1769d1;
        font-size: 14px;
        font-weight: 800;
    }

    .timetable-form-footer {
        padding: 18px 26px;
        border-top: 1px solid #edf1f6;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .cancel-btn,
    .update-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 17px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .cancel-btn {
        border: 1px solid #dfe6ef;
        background: #fff;
        color: #64748b;
    }

    .cancel-btn:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .update-btn {
        border: 1px solid #1769d1;
        background: #1769d1;
        color: #fff;
    }

    .update-btn:hover {
        background: #1258b5;
        border-color: #1258b5;
    }

    .alert-error {
        max-width: 1100px;
        margin: 0 auto 20px;
        padding: 14px 17px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 12px;
    }

    .alert-error ul {
        margin: 7px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 700px) {

        .timetable-edit-page {
            padding: 16px;
        }

        .timetable-edit-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .timetable-edit-header h2 {
            font-size: 23px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .timetable-form-body {
            padding: 20px;
        }

        .timetable-form-footer {
            padding: 16px 20px;
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-btn,
        .update-btn {
            width: 100%;
        }
    }

</style>


<div class="timetable-edit-page">

    {{-- HEADER --}}
    <div class="timetable-edit-header">

        <div>

            <h2>
                Edit Timetable 📅
            </h2>

            <p>
                Update the teacher's class, subject and teaching schedule.
            </p>

        </div>

        <a
            href="{{ route('admin.timetable.index') }}"
            class="back-timetable-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Back to Timetable
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="alert-error">

            <strong>
                Please correct the following errors:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORM CARD --}}
    <div class="timetable-form-card">

        <div class="timetable-form-header">

            <h3>
                Update Timetable Information
            </h3>

            <p>
                Modify the required information and save your changes.
            </p>

        </div>


        <form
            action="{{ route('admin.timetable.update', $timetable->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="timetable-form-body">


                {{-- TEACHER & CLASS --}}
                <div class="form-section-title">

                    <i class="bi bi-person-workspace"></i>
                    Teacher & Class Information

                </div>


                {{-- TEACHER + DATE --}}
                <div class="form-row">

                    {{-- TEACHER --}}
                    <div class="form-group">

                        <label
                            for="teacher_id"
                            class="form-label"
                        >
                            Teacher
                            <span class="required">*</span>
                        </label>

                        <select
                            name="teacher_id"
                            id="teacher_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Teacher
                            </option>

                            @foreach($teachers as $teacher)

                                <option
                                    value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $timetable->teacher_id) == $teacher->id ? 'selected' : '' }}
                                >

                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}
                                    —
                                    {{ $teacher->teacher_id }}

                                </option>

                            @endforeach

                        </select>

                        @error('teacher_id')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- TIMETABLE DATE --}}
                    <div class="form-group">

                        <label
                            for="timetable_date"
                            class="form-label"
                        >
                            Timetable Date
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="timetable_date"
                            id="timetable_date"
                            class="form-control"
                            value="{{ old('timetable_date', $timetable->timetable_date ? \Carbon\Carbon::parse($timetable->timetable_date)->format('Y-m-d') : '') }}"
                            required
                        >

                        <div class="form-help">
                            Select the date for this timetable entry.
                        </div>

                        @error('timetable_date')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- CLASS + DAY --}}
                <div class="form-row">

                    {{-- CLASS --}}
                    <div class="form-group">

                        <label
                            for="class"
                            class="form-label"
                        >
                            Class
                            <span class="required">*</span>
                        </label>

                        <select
                            name="class"
                            id="class"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->class_name }}"
                                    {{ old('class', $timetable->class) == $class->class_name ? 'selected' : '' }}
                                >
                                    {{ $class->class_name }}
                                </option>

                            @endforeach

                        </select>

                        @error('class')

                            <div class="field-error">
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
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="day"
                            id="day"
                            class="form-control"
                            value="{{ old('day', $timetable->day) }}"
                            readonly
                        >

                        <div class="form-help">
                            Day is automatically calculated from the selected date.
                        </div>

                        @error('day')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- SECTION --}}
                <div class="form-row">

                    <div class="form-group">

                        <label
                            for="section"
                            class="form-label"
                        >
                            Section
                            <span class="required">*</span>
                        </label>

                        <select
                            name="section"
                            id="section"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Section
                            </option>

                        </select>

                        <div class="form-help">
                            Sections are loaded according to the selected class.
                        </div>

                        @error('section')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- TIMETABLE DETAILS --}}
                <div class="form-section-title">

                    <i class="bi bi-calendar3"></i>
                    Timetable Details

                </div>


                <div class="form-row">

                    {{-- ACADEMIC YEAR --}}
                    <div class="form-group">

                        <label
                            for="academic_year"
                            class="form-label"
                        >
                            Academic Year
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            id="academic_year"
                            class="form-control"
                            value="{{ old('academic_year', $timetable->academic_year) }}"
                            placeholder="Example: 2026-27"
                            required
                        >

                        @error('academic_year')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PERIOD NUMBER --}}
                    <div class="form-group">

                        <label
                            for="period_number"
                            class="form-label"
                        >
                            Period Number
                            <span class="required">*</span>
                        </label>

                        <input
                            type="number"
                            name="period_number"
                            id="period_number"
                            class="form-control"
                            value="{{ old('period_number', $timetable->period_number) }}"
                            min="1"
                            placeholder="Example: 1"
                            required
                        >

                        @error('period_number')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="form-row">

                    {{-- PERIOD TYPE --}}
                    <div class="form-group">

                        <label
                            for="period_type"
                            class="form-label"
                        >
                            Period Type
                            <span class="required">*</span>
                        </label>

                        <select
                            name="period_type"
                            id="period_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Period Type
                            </option>

                            @foreach([
                                'Regular',
                                'Break',
                                'Lunch'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    {{ old('period_type', $timetable->period_type) == $type ? 'selected' : '' }}
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                        @error('period_type')

                            <div class="field-error">
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
                            <span class="required">*</span>
                        </label>

                        <select
                            name="subject_type"
                            id="subject_type"
                            class="form-select"
                        >

                            <option value="">
                                Select Subject Type
                            </option>

                            @foreach([
                                'Theory',
                                'Practical',
                                'Activity'
                            ] as $subjectType)

                                <option
                                    value="{{ $subjectType }}"
                                    {{ old('subject_type', $timetable->subject_type) == $subjectType ? 'selected' : '' }}
                                >
                                    {{ $subjectType }}
                                </option>

                            @endforeach

                        </select>

                        <div class="form-help">
                            Not required for Break or Lunch.
                        </div>

                        @error('subject_type')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- SUBJECT INFORMATION --}}
                <div class="form-section-title">

                    <i class="bi bi-book"></i>
                    Subject Information

                </div>


                <div class="form-row">

                    {{-- SUBJECT --}}
                    <div class="form-group">

                        <label
                            for="subject"
                            class="form-label"
                        >
                            Subject
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            class="form-control"
                            value="{{ old('subject', $timetable->subject) }}"
                            placeholder="Example: Mathematics"
                            required
                        >

                        @error('subject')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ROOM --}}
                    <div class="form-group">

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
                            class="form-control"
                            value="{{ old('room', $timetable->room) }}"
                            placeholder="Example: Room 101"
                        >

                        <div class="form-help">
                            Optional
                        </div>

                        @error('room')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- CLASS TIMING --}}
                <div class="form-section-title">

                    <i class="bi bi-clock"></i>
                    Class Timing

                </div>


                <div class="form-row">

                    {{-- START TIME --}}
                    <div class="form-group">

                        <label
                            for="start_time"
                            class="form-label"
                        >
                            Start Time
                            <span class="required">*</span>
                        </label>

                        <input
                            type="time"
                            name="start_time"
                            id="start_time"
                            class="form-control"
                            value="{{ old('start_time', \Carbon\Carbon::parse($timetable->start_time)->format('H:i')) }}"
                            required
                        >

                        @error('start_time')

                            <div class="field-error">
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
                            <span class="required">*</span>
                        </label>

                        <input
                            type="time"
                            name="end_time"
                            id="end_time"
                            class="form-control"
                            value="{{ old('end_time', \Carbon\Carbon::parse($timetable->end_time)->format('H:i')) }}"
                            required
                        >

                        @error('end_time')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="timetable-form-footer">

                <a
                    href="{{ route('admin.timetable.index') }}"
                    class="cancel-btn"
                >
                    <i class="bi bi-x-circle"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="update-btn"
                >
                    <i class="bi bi-check-circle-fill"></i>
                    Update Timetable
                </button>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | DATE → DAY
    |--------------------------------------------------------------------------
    */

    const timetableDate =
        document.getElementById('timetable_date');

    const dayInput =
        document.getElementById('day');


    function updateDayFromDate() {

        if (!timetableDate.value) {

            dayInput.value = '';

            return;
        }


        const date = new Date(
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


    updateDayFromDate();


    timetableDate.addEventListener(
        'change',
        updateDayFromDate
    );


    /*
    |--------------------------------------------------------------------------
    | CLASS → SECTION
    |--------------------------------------------------------------------------
    */

    const classSelect =
        document.getElementById('class');

    const sectionSelect =
        document.getElementById('section');

    const allSections =
        @json($sections);

    const allClasses =
        @json($classes);

    const currentSection =
        @json(old('section', $timetable->section));


    function loadSections(
        className,
        selectedSection = ''
    ) {

        sectionSelect.innerHTML =
            '<option value="">Select Section</option>';


        if (!className) {

            sectionSelect.disabled = true;

            return;
        }


        const selectedClass =
            allClasses.find(
                item =>
                    item.class_name === className
            );


        if (!selectedClass) {

            sectionSelect.disabled = true;

            return;
        }


        const classSections =
            allSections.filter(
                section =>
                    section.class_id ==
                    selectedClass.id
            );


        if (classSections.length === 0) {

            sectionSelect.disabled = true;

            return;
        }


        sectionSelect.disabled = false;


        classSections.forEach(function (section) {

            const option =
                document.createElement('option');

            option.value =
                section.section_name;

            option.textContent =
                section.section_name;


            if (
                section.section_name ===
                selectedSection
            ) {

                option.selected = true;

            }


            sectionSelect.appendChild(option);

        });

    }


    classSelect.addEventListener(
        'change',
        function () {

            loadSections(this.value);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LOAD EXISTING SECTION
    |--------------------------------------------------------------------------
    */

    if (classSelect.value) {

        loadSections(
            classSelect.value,
            currentSection
        );

    } else {

        sectionSelect.disabled = true;

    }


    /*
    |--------------------------------------------------------------------------
    | PERIOD TYPE → SUBJECT TYPE
    |--------------------------------------------------------------------------
    */

    const periodType =
        document.getElementById('period_type');

    const subject =
        document.getElementById('subject');

    const subjectType =
        document.getElementById('subject_type');


    function toggleFields() {

        const type =
            periodType.value;


        if (
            type === 'Break' ||
            type === 'Lunch'
        ) {

            subject.disabled = true;

            subjectType.disabled = true;

            subject.removeAttribute(
                'required'
            );

            subjectType.removeAttribute(
                'required'
            );


            subject.value = type;


        } else {

            subject.disabled = false;

            subjectType.disabled = false;

            subject.setAttribute(
                'required',
                'required'
            );

            subjectType.setAttribute(
                'required',
                'required'
            );


            if (
                subject.value === 'Break' ||
                subject.value === 'Lunch'
            ) {

                subject.value = '';

            }

        }

    }


    periodType.addEventListener(
        'change',
        toggleFields
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    toggleFields();

});

</script>

@endsection