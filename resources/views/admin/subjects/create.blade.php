@extends('layouts.app')

@section('title', 'Add Subjects | Admin')

@section('page-title', 'Add Subjects')

@section('content')

<style>
    /* =========================================================
       ADD SUBJECTS PAGE
    ========================================================= */

    .subject-form-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f6f8fb;
        padding-bottom: 30px;
    }

    .subject-form-header {
        margin-bottom: 20px;
    }

    .subject-form-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #26344a;
    }

    .subject-form-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    /* =========================================================
       CARD
    ========================================================= */

    .subject-form-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(30, 55, 90, 0.04);
    }

    .subject-form-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #e7edf5;
        background: #ffffff;
    }

    .subject-form-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #26344a;
    }

    .subject-form-card-header p {
        margin: 4px 0 0;
        color: #8a94a6;
        font-size: 12px;
    }

    .subject-form {
        padding: 22px;
    }

    /* =========================================================
       FORM ROW
    ========================================================= */

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        margin-bottom: 7px;
        color: #26344a;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }

    .form-control-custom,
    .form-select-custom {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #dce3ec;
        border-radius: 6px;
        background: #ffffff;
        color: #26344a;
        font-size: 13px;
        outline: none;
        transition: 0.2s ease;
        box-sizing: border-box;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: #1677f0;
        box-shadow: 0 0 0 3px rgba(22, 119, 240, 0.08);
    }

    .form-help {
        margin-top: 5px;
        color: #8a94a6;
        font-size: 11px;
    }

    .error-message {
        margin-top: 5px;
        color: #dc3545;
        font-size: 11px;
    }

    /* =========================================================
       SELECTED CLASS INFORMATION
    ========================================================= */

    .class-info-box {
        display: none;
        margin-top: 8px;
        padding: 11px 13px;
        background: #f0f7ff;
        border: 1px solid #d7eaff;
        border-radius: 7px;
        color: #1268ca;
        font-size: 12px;
    }

    .class-info-box strong {
        font-weight: 700;
    }

    /* =========================================================
       SUBJECT SECTION
    ========================================================= */

    .subjects-section {
        margin-top: 5px;
        border: 1px solid #e4eaf2;
        border-radius: 9px;
        overflow: hidden;
        background: #ffffff;
    }

    .subjects-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e4eaf2;
    }

    .subjects-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .subjects-section-icon {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf4ff;
        color: #1677f0;
        font-size: 14px;
    }

    .subjects-section-title h4 {
        margin: 0;
        color: #26344a;
        font-size: 14px;
        font-weight: 700;
    }

    .subjects-section-title p {
        margin: 2px 0 0;
        color: #8a94a6;
        font-size: 11px;
    }

    /* =========================================================
       ADD SUBJECT BUTTON
    ========================================================= */

    .btn-add-subject {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 13px;
        background: #1677f0;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .btn-add-subject:hover {
        background: #0d5fd1;
        color: #ffffff;
    }

    /* =========================================================
       SUBJECT ROWS
    ========================================================= */

    .subjects-list {
        padding: 16px;
    }

    .subject-row {
        display: grid;
        grid-template-columns: 55px minmax(0, 1fr) minmax(0, 220px) 42px;
        align-items: end;
        gap: 12px;
        padding: 13px;
        margin-bottom: 10px;
        background: #fbfcfe;
        border: 1px solid #e5ebf2;
        border-radius: 7px;
    }

    .subject-row:last-child {
        margin-bottom: 0;
    }

    .subject-number {
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf4ff;
        color: #1677f0;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
    }

    .subject-field {
        display: flex;
        flex-direction: column;
    }

    .subject-field label {
        margin-bottom: 6px;
        color: #526071;
        font-size: 11px;
        font-weight: 600;
    }

    .remove-subject-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff1f1;
        color: #dc3545;
        border: 1px solid #ffd8d8;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .remove-subject-btn:hover {
        background: #dc3545;
        color: #ffffff;
        border-color: #dc3545;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .subjects-empty {
        padding: 28px 15px;
        text-align: center;
        color: #8a94a6;
        font-size: 12px;
    }

    .subjects-empty i {
        display: block;
        margin-bottom: 8px;
        font-size: 25px;
        color: #b7c1ce;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .status-options {
        display: flex;
        align-items: center;
        gap: 18px;
        min-height: 40px;
    }

    .status-option {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #26344a;
        font-size: 13px;
        cursor: pointer;
    }

    .status-option input {
        accent-color: #1677f0;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 20px;
        margin-top: 20px;
        border-top: 1px solid #e7edf5;
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 18px;
        background: #1677f0;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .btn-primary-custom:hover {
        background: #0d5fd1;
        color: #ffffff;
    }

    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 17px;
        background: #f4f6f9;
        color: #526071;
        border: 1px solid #e1e6ee;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .btn-secondary-custom:hover {
        background: #e9edf3;
        color: #26344a;
    }

    /* =========================================================
       ALERT
    ========================================================= */

    .alert-error-custom {
        margin-bottom: 18px;
        padding: 12px 15px;
        border-radius: 6px;
        background: #fff0f1;
        border: 1px solid #ffdadd;
        color: #dc3545;
        font-size: 13px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {
        .subject-row {
            grid-template-columns: 45px minmax(0, 1fr) 40px;
        }

        .subject-row .subject-code-field {
            grid-column: 2 / 3;
        }

        .subject-row .remove-subject-btn {
            grid-column: 3;
            grid-row: 1 / span 2;
        }
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .subject-form {
            padding: 18px;
        }

        .subjects-section-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn-add-subject {
            width: 100%;
        }

        .subject-row {
            grid-template-columns: 40px minmax(0, 1fr) 40px;
        }

        .subject-row .subject-code-field {
            grid-column: 2 / 3;
        }

        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
        }
    }
</style>


<div class="subject-form-page">

    <div class="container-fluid py-4">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="subject-form-header">

            <h2>Add Subjects</h2>

            <p>
                Select a class and division, then add all subjects together.
            </p>

        </div>


        {{-- =====================================================
             VALIDATION ERROR
        ====================================================== --}}

        @if($errors->any())

            <div class="alert-error-custom">

                <strong>Please correct the following errors:</strong>

                <ul style="margin: 7px 0 0 18px; padding: 0;">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             FORM CARD
        ====================================================== --}}

        <div class="subject-form-card">

            <div class="subject-form-card-header">

                <h3>Subject Information</h3>

                <p>
                    Add multiple subjects for the selected class and division.
                </p>

            </div>


            <form
                action="{{ route('admin.subjects.store') }}"
                method="POST"
                class="subject-form"
                id="subjectForm"
            >

                @csrf


                {{-- =================================================
                     CLASS + DIVISION
                ================================================== --}}

                <div class="form-row">

                    <div class="form-group">

                        <label for="class_id" class="form-label">
                            Class <span class="required">*</span>
                        </label>

                        <select
                            id="class_id"
                            name="class_id"
                            class="form-select-custom"
                            required
                        >

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    data-section="{{ $class->section }}"
                                    data-year="{{ $class->academic_year }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}
                                >
                                    {{ $class->class_name }}
                                    - Section {{ $class->section }}
                                    ({{ $class->academic_year }})
                                </option>

                            @endforeach

                        </select>


                        @if($classes->isEmpty())

                            <div class="form-help">
                                No active classes are available.
                                Please create a class first.
                            </div>

                        @endif


                        @error('class_id')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror


                        <div
                            class="class-info-box"
                            id="classInfoBox"
                        >

                            <strong>Selected Class:</strong>

                            <span id="selectedClassText"></span>

                        </div>

                    </div>


                    {{-- =================================================
                         DIVISION
                    ================================================== --}}

                    <div class="form-group">

                        <label for="division" class="form-label">
                            Division <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="division"
                            name="division"
                            class="form-control-custom"
                            value="{{ old('division') }}"
                            placeholder="Enter division e.g. A"
                            maxlength="20"
                            required
                        >

                        <div class="form-help">
                            Enter the division for which these subjects
                            should be added.
                        </div>

                        @error('division')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                     SUBJECTS
                ================================================== --}}

                <div class="subjects-section">

                    <div class="subjects-section-header">

                        <div class="subjects-section-title">

                            <div class="subjects-section-icon">
                                <i class="fas fa-book"></i>
                            </div>

                            <div>

                                <h4>Subjects</h4>

                                <p>
                                    Add all subjects for this class and division.
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="btn-add-subject"
                            id="addSubjectBtn"
                        >

                            <i class="fas fa-plus"></i>

                            Add Subject

                        </button>

                    </div>


                    <div
                        class="subjects-list"
                        id="subjectsList"
                    >

                        {{-- =================================================
                             OLD INPUTS AFTER VALIDATION ERROR
                        ================================================== --}}

                        @if(old('subjects'))

                            @foreach(old('subjects') as $index => $subject)

                                <div class="subject-row">

                                    <div class="subject-number">
                                        {{ $index + 1 }}
                                    </div>


                                    <div class="subject-field">

                                        <label>
                                            Subject Name
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="subjects[{{ $index }}][subject_name]"
                                            class="form-control-custom"
                                            value="{{ $subject['subject_name'] ?? '' }}"
                                            placeholder="Enter subject name"
                                            required
                                        >

                                    </div>


                                    <div class="subject-field subject-code-field">

                                        <label>
                                            Subject Code
                                        </label>

                                        <input
                                            type="text"
                                            name="subjects[{{ $index }}][subject_code]"
                                            class="form-control-custom"
                                            value="{{ $subject['subject_code'] ?? '' }}"
                                            placeholder="Example: MATH101"
                                        >

                                    </div>


                                    <button
                                        type="button"
                                        class="remove-subject-btn"
                                        title="Remove Subject"
                                    >

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </div>

                            @endforeach

                        @else

                            {{-- DEFAULT FIRST SUBJECT --}}

                            <div class="subject-row">

                                <div class="subject-number">
                                    1
                                </div>


                                <div class="subject-field">

                                    <label>
                                        Subject Name
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="subjects[0][subject_name]"
                                        class="form-control-custom"
                                        placeholder="Enter subject name"
                                        required
                                    >

                                </div>


                                <div class="subject-field subject-code-field">

                                    <label>
                                        Subject Code
                                    </label>

                                    <input
                                        type="text"
                                        name="subjects[0][subject_code]"
                                        class="form-control-custom"
                                        placeholder="Example: MATH101"
                                    >

                                </div>


                                <button
                                    type="button"
                                    class="remove-subject-btn"
                                    title="Remove Subject"
                                >

                                    <i class="fas fa-trash"></i>

                                </button>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     STATUS
                ================================================== --}}

                <div class="form-row" style="margin-top: 20px; margin-bottom: 0;">

                    <div class="form-group">

                        <label class="form-label">
                            Status <span class="required">*</span>
                        </label>

                        <div class="status-options">

                            <label class="status-option">

                                <input
                                    type="radio"
                                    name="status"
                                    value="1"
                                    {{ old('status', '1') == '1' ? 'checked' : '' }}
                                >

                                Active

                            </label>


                            <label class="status-option">

                                <input
                                    type="radio"
                                    name="status"
                                    value="0"
                                    {{ old('status') === '0' ? 'checked' : '' }}
                                >

                                Inactive

                            </label>

                        </div>

                        @error('status')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                ================================================== --}}

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-primary-custom"
                    >

                        <i class="fas fa-save"></i>

                        Add All Subjects

                    </button>


                    <a
                        href="{{ route('admin.subjects.index') }}"
                        class="btn-secondary-custom"
                    >

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const subjectsList = document.getElementById('subjectsList');

    const addSubjectBtn = document.getElementById('addSubjectBtn');

    const classSelect = document.getElementById('class_id');

    const classInfoBox = document.getElementById('classInfoBox');

    const selectedClassText = document.getElementById('selectedClassText');


    let subjectIndex = document.querySelectorAll('.subject-row').length;


    /* =========================================================
       UPDATE SUBJECT NUMBERS
    ========================================================= */

    function updateSubjectNumbers() {

        const rows = document.querySelectorAll('.subject-row');

        rows.forEach(function (row, index) {

            const number = row.querySelector('.subject-number');

            if (number) {
                number.textContent = index + 1;
            }

        });

    }


    /* =========================================================
       ADD SUBJECT
    ========================================================= */

    addSubjectBtn.addEventListener('click', function () {

        const row = document.createElement('div');

        row.className = 'subject-row';

        row.innerHTML = `

            <div class="subject-number">
                ${subjectIndex + 1}
            </div>

            <div class="subject-field">

                <label>
                    Subject Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="subjects[${subjectIndex}][subject_name]"
                    class="form-control-custom"
                    placeholder="Enter subject name"
                    required
                >

            </div>

            <div class="subject-field subject-code-field">

                <label>
                    Subject Code
                </label>

                <input
                    type="text"
                    name="subjects[${subjectIndex}][subject_code]"
                    class="form-control-custom"
                    placeholder="Example: MATH101"
                >

            </div>

            <button
                type="button"
                class="remove-subject-btn"
                title="Remove Subject"
            >

                <i class="fas fa-trash"></i>

            </button>

        `;


        subjectsList.appendChild(row);

        subjectIndex++;

        updateSubjectNumbers();

        const newInput = row.querySelector('input[name*="[subject_name]"]');

        if (newInput) {
            newInput.focus();
        }

    });


    /* =========================================================
       REMOVE SUBJECT
    ========================================================= */

    subjectsList.addEventListener('click', function (event) {

        const removeButton = event.target.closest('.remove-subject-btn');

        if (!removeButton) {
            return;
        }

        const rows = document.querySelectorAll('.subject-row');

        if (rows.length <= 1) {

            alert('At least one subject is required.');

            return;
        }

        removeButton.closest('.subject-row').remove();

        updateSubjectNumbers();

    });


    /* =========================================================
       CLASS INFORMATION
    ========================================================= */

    function updateClassInformation() {

        const selectedOption =
            classSelect.options[classSelect.selectedIndex];

        if (!classSelect.value || !selectedOption) {

            classInfoBox.style.display = 'none';

            selectedClassText.textContent = '';

            return;
        }


        const className = selectedOption.textContent.trim();

        selectedClassText.textContent = className;

        classInfoBox.style.display = 'block';

    }


    classSelect.addEventListener('change', updateClassInformation);


    updateClassInformation();

});

</script>

@endsection
