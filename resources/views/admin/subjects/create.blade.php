
@extends('layouts.app')

@section('title', 'Add Subjects | Admin')
@section('page-title', 'Add Subjects')

@section('content')

<div class="add-subjects-page">
    <div class="add-subjects-container">

        {{-- PAGE HEADER --}}
        <div class="as-page-header">
            <div class="as-heading-area">
                <div class="as-heading-icon">
                    <i class="fas fa-book-open"></i>
                </div>

                <div class="as-heading-text">
                    <div class="as-breadcrumb">
                        ACADEMIC MANAGEMENT
                        <i class="fas fa-chevron-right"></i>
                        SUBJECTS
                        <i class="fas fa-chevron-right"></i>
                        ADD SUBJECTS
                    </div>

                    <h2>Add Subjects</h2>
                    <p>Create multiple subjects for a selected class and division.</p>
                </div>
            </div>

            <a href="{{ route('admin.subjects.index') }}" class="as-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Subjects</span>
            </a>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="as-alert-error" role="alert">
                <div class="as-alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>

                <div class="as-alert-content">
                    <strong>Please check the form details.</strong>
                    <p>Correct the following errors and try again.</p>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN FORM CARD --}}
        <div class="as-form-card">

            <div class="as-card-header">
                <div class="as-card-heading">
                    <div class="as-section-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>

                    <div>
                        <h3>Subject Information</h3>
                        <p>Configure the class, division, subjects and status.</p>
                    </div>
                </div>

                <span class="as-required-note">
                    <span class="as-required">*</span>
                    Required fields
                </span>
            </div>

            <form
                action="{{ route('admin.subjects.store') }}"
                method="POST"
                class="as-form"
                id="subjectForm"
            >
                @csrf

                {{-- SECTION 01: CLASS ASSIGNMENT --}}
                <div class="as-form-section">

                    <div class="as-section-title">
                        <span class="as-section-number">01</span>

                        <div>
                            <h4>Class Assignment</h4>
                            <p>Select the class and division for these subjects.</p>
                        </div>
                    </div>

                    <div class="as-form-grid">

                        {{-- CLASS --}}
                        <div class="as-form-group">
                            <label for="class_id" class="as-form-label">
                                Class
                                <span class="as-required">*</span>
                            </label>

                            <div class="as-input-wrap">
                                <i class="fas fa-school as-input-icon"></i>

                                <select
                                    id="class_id"
                                    name="class_id"
                                    class="as-form-control as-form-select"
                                    required
                                >
                                    <option value="">Select Class</option>

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
                            </div>

                            @if($classes->isEmpty())
                                <div class="as-form-help">
                                    <i class="fas fa-info-circle"></i>
                                    No active classes are available. Please create a class first.
                                </div>
                            @endif

                            @error('class_id')
                                <div class="as-field-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="as-class-info" id="classInfoBox">
                                <div class="as-class-info-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>

                                <div>
                                    <span>Selected Class</span>
                                    <strong id="selectedClassText"></strong>
                                </div>
                            </div>
                        </div>

                        {{-- DIVISION --}}
                        <div class="as-form-group">
                            <label for="division" class="as-form-label">
                                Division
                                <span class="as-required">*</span>
                            </label>

                            <div class="as-input-wrap">
                                <i class="fas fa-layer-group as-input-icon"></i>

                                <input
                                    type="text"
                                    id="division"
                                    name="division"
                                    class="as-form-control"
                                    value="{{ old('division') }}"
                                    placeholder="Enter division, e.g. A"
                                    maxlength="20"
                                    required
                                >
                            </div>

                            <div class="as-form-help">
                                <i class="fas fa-info-circle"></i>
                                Enter the division for which these subjects should be added.
                            </div>

                            @error('division')
                                <div class="as-field-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="as-form-divider"></div>

                {{-- SECTION 02: SUBJECT LIST --}}
                <div class="as-form-section">

                    <div class="as-section-title">
                        <span class="as-section-number">02</span>

                        <div>
                            <h4>Subject List</h4>
                            <p>Add each subject and its optional subject code.</p>
                        </div>
                    </div>

                    <div class="as-subjects-panel">

                        <div class="as-subjects-header">
                            <div class="as-subjects-heading">
                                <div class="as-subjects-icon">
                                    <i class="fas fa-book"></i>
                                </div>

                                <div>
                                    <h5>Subjects</h5>
                                    <p>
                                        <span id="subjectCount">0</span>
                                        subject(s) added
                                    </p>
                                </div>
                            </div>

                            <button
                                type="button"
                                class="as-add-subject-btn"
                                id="addSubjectBtn"
                            >
                                <i class="fas fa-plus"></i>
                                Add Subject
                            </button>
                        </div>

                        <div class="as-subjects-list" id="subjectsList">

                            {{-- RESTORE OLD INPUTS AFTER VALIDATION ERRORS --}}
                            @if(old('subjects'))

                                @foreach(old('subjects') as $index => $subject)

                                    <div class="as-subject-row">

                                        <div class="as-subject-number">
                                            {{ $index + 1 }}
                                        </div>

                                        <div class="as-subject-field">
                                            <label>
                                                Subject Name
                                                <span class="as-required">*</span>
                                            </label>

                                            <div class="as-input-wrap">
                                                <i class="fas fa-book as-input-icon"></i>

                                                <input
                                                    type="text"
                                                    name="subjects[{{ $index }}][subject_name]"
                                                    class="as-form-control"
                                                    value="{{ $subject['subject_name'] ?? '' }}"
                                                    placeholder="Enter subject name"
                                                    required
                                                >
                                            </div>
                                        </div>

                                        <div class="as-subject-field as-subject-code-field">
                                            <label>
                                                Subject Code
                                                <span class="as-optional-label">OPTIONAL</span>
                                            </label>

                                            <div class="as-input-wrap">
                                                <i class="fas fa-hashtag as-input-icon"></i>

                                                <input
                                                    type="text"
                                                    name="subjects[{{ $index }}][subject_code]"
                                                    class="as-form-control"
                                                    value="{{ $subject['subject_code'] ?? '' }}"
                                                    placeholder="Example: MATH101"
                                                >
                                            </div>
                                        </div>

                                        <button
                                            type="button"
                                            class="as-remove-subject-btn"
                                            title="Remove Subject"
                                            aria-label="Remove subject {{ $index + 1 }}"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                    </div>

                                @endforeach

                            @else

                                {{-- DEFAULT FIRST SUBJECT --}}
                                <div class="as-subject-row">

                                    <div class="as-subject-number">1</div>

                                    <div class="as-subject-field">
                                        <label>
                                            Subject Name
                                            <span class="as-required">*</span>
                                        </label>

                                        <div class="as-input-wrap">
                                            <i class="fas fa-book as-input-icon"></i>

                                            <input
                                                type="text"
                                                name="subjects[0][subject_name]"
                                                class="as-form-control"
                                                placeholder="Enter subject name"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="as-subject-field as-subject-code-field">
                                        <label>
                                            Subject Code
                                            <span class="as-optional-label">OPTIONAL</span>
                                        </label>

                                        <div class="as-input-wrap">
                                            <i class="fas fa-hashtag as-input-icon"></i>

                                            <input
                                                type="text"
                                                name="subjects[0][subject_code]"
                                                class="as-form-control"
                                                placeholder="Example: MATH101"
                                            >
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="as-remove-subject-btn"
                                        title="Remove Subject"
                                        aria-label="Remove subject 1"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                </div>

                            @endif

                        </div>

                        <div class="as-subjects-footer">
                            <i class="fas fa-lightbulb"></i>
                            <span>
                                Subject names are required. Subject codes are optional.
                            </span>
                        </div>

                    </div>
                </div>

                <div class="as-form-divider"></div>

                {{-- SECTION 03: STATUS --}}
                <div class="as-form-section">

                    <div class="as-section-title">
                        <span class="as-section-number">03</span>

                        <div>
                            <h4>Subject Status</h4>
                            <p>Choose whether the subjects should be active or inactive.</p>
                        </div>
                    </div>

                    <div class="as-status-panel">

                        <label class="as-status-option as-status-active">
                            <input
                                type="radio"
                                name="status"
                                value="1"
                                {{ old('status', '1') == '1' ? 'checked' : '' }}
                            >

                            <span class="as-status-radio"></span>

                            <span class="as-status-content">
                                <span class="as-status-title">Active</span>
                                <span class="as-status-description">
                                    Subjects are enabled.
                                </span>
                            </span>

                            <i class="fas fa-check-circle as-status-check"></i>
                        </label>

                        <label class="as-status-option as-status-inactive">
                            <input
                                type="radio"
                                name="status"
                                value="0"
                                {{ old('status') === '0' ? 'checked' : '' }}
                            >

                            <span class="as-status-radio"></span>

                            <span class="as-status-content">
                                <span class="as-status-title">Inactive</span>
                                <span class="as-status-description">
                                    Subjects are disabled.
                                </span>
                            </span>

                            <i class="fas fa-check-circle as-status-check"></i>
                        </label>

                    </div>

                    @error('status')
                        <div class="as-field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- FORM ACTIONS --}}
                <div class="as-form-actions">

                    <div class="as-action-note">
                        <i class="fas fa-shield-alt"></i>
                        Review the details before saving your subjects.
                    </div>

                    <div class="as-action-buttons">
                        <a
                            href="{{ route('admin.subjects.index') }}"
                            class="as-btn as-btn-secondary"
                        >
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>

                        <button type="submit" class="as-btn as-btn-primary">
                            <i class="fas fa-save"></i>
                            Add All Subjects
                        </button>
                    </div>

                </div>

            </form>
        </div>

        {{-- PAGE FOOTER --}}
        <div class="as-page-footer">
            <i class="fas fa-book-reader"></i>
            <span>School Management System</span>
            <span class="as-footer-dot">•</span>
            <span>Subject Management</span>
        </div>

    </div>
</div>

{{-- JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('subjectForm');
    const subjectsList = document.getElementById('subjectsList');
    const addSubjectBtn = document.getElementById('addSubjectBtn');
    const classSelect = document.getElementById('class_id');
    const classInfoBox = document.getElementById('classInfoBox');
    const selectedClassText = document.getElementById('selectedClassText');
    const subjectCount = document.getElementById('subjectCount');

    if (!form || !subjectsList || !addSubjectBtn) {
        return;
    }

    // Keep the next index unique, including after a validation error.
    let subjectIndex = 0;

    document.querySelectorAll('.as-subject-row input[name]').forEach(function (input) {
        const match = input.name.match(/^subjects\[(\d+)\]/);

        if (match) {
            subjectIndex = Math.max(subjectIndex, Number(match[1]) + 1);
        }
    });

    function updateSubjectRows() {
        const rows = subjectsList.querySelectorAll('.as-subject-row');

        rows.forEach(function (row, index) {
            const number = row.querySelector('.as-subject-number');
            const removeButton = row.querySelector('.as-remove-subject-btn');

            if (number) {
                number.textContent = index + 1;
            }

            if (removeButton) {
                removeButton.setAttribute(
                    'aria-label',
                    'Remove subject ' + (index + 1)
                );
            }
        });

        subjectCount.textContent = rows.length;
    }

    function createSubjectRow(index) {
        const row = document.createElement('div');
        row.className = 'as-subject-row';

        const number = document.createElement('div');
        number.className = 'as-subject-number';
        number.textContent = index + 1;

        const nameField = document.createElement('div');
        nameField.className = 'as-subject-field';

        const nameLabel = document.createElement('label');
        nameLabel.textContent = 'Subject Name ';

        const required = document.createElement('span');
        required.className = 'as-required';
        required.textContent = '*';
        nameLabel.appendChild(required);

        const nameWrap = document.createElement('div');
        nameWrap.className = 'as-input-wrap';

        const nameIcon = document.createElement('i');
        nameIcon.className = 'fas fa-book as-input-icon';

        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.name = `subjects[${index}][subject_name]`;
        nameInput.className = 'as-form-control';
        nameInput.placeholder = 'Enter subject name';
        nameInput.required = true;

        nameWrap.append(nameIcon, nameInput);
        nameField.append(nameLabel, nameWrap);

        const codeField = document.createElement('div');
        codeField.className = 'as-subject-field as-subject-code-field';

        const codeLabel = document.createElement('label');
        codeLabel.textContent = 'Subject Code ';

        const optional = document.createElement('span');
        optional.className = 'as-optional-label';
        optional.textContent = 'OPTIONAL';
        codeLabel.appendChild(optional);

        const codeWrap = document.createElement('div');
        codeWrap.className = 'as-input-wrap';

        const codeIcon = document.createElement('i');
        codeIcon.className = 'fas fa-hashtag as-input-icon';

        const codeInput = document.createElement('input');
        codeInput.type = 'text';
        codeInput.name = `subjects[${index}][subject_code]`;
        codeInput.className = 'as-form-control';
        codeInput.placeholder = 'Example: MATH101';

        codeWrap.append(codeIcon, codeInput);
        codeField.append(codeLabel, codeWrap);

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'as-remove-subject-btn';
        removeButton.title = 'Remove Subject';
        removeButton.setAttribute('aria-label', 'Remove subject ' + (index + 1));

        const removeIcon = document.createElement('i');
        removeIcon.className = 'fas fa-trash-alt';
        removeButton.appendChild(removeIcon);

        row.append(number, nameField, codeField, removeButton);

        return row;
    }

    addSubjectBtn.addEventListener('click', function () {
        const row = createSubjectRow(subjectIndex);

        subjectIndex++;
        subjectsList.appendChild(row);
        updateSubjectRows();

        const nameInput = row.querySelector('input[name*="[subject_name]"]');

        if (nameInput) {
            nameInput.focus();
        }
    });

    subjectsList.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.as-remove-subject-btn');

        if (!removeButton || !subjectsList.contains(removeButton)) {
            return;
        }

        const rows = subjectsList.querySelectorAll('.as-subject-row');

        if (rows.length <= 1) {
            alert('At least one subject is required.');
            return;
        }

        removeButton.closest('.as-subject-row').remove();
        updateSubjectRows();
    });

    function updateClassInformation() {
        if (!classSelect || !classInfoBox || !selectedClassText) {
            return;
        }

        const selectedOption = classSelect.options[classSelect.selectedIndex];

        if (!classSelect.value || !selectedOption) {
            classInfoBox.style.display = 'none';
            selectedClassText.textContent = '';
            return;
        }

        selectedClassText.textContent = selectedOption.textContent.trim();
        classInfoBox.style.display = 'flex';
    }

    if (classSelect) {
        classSelect.addEventListener('change', updateClassInformation);
        updateClassInformation();
    }

    // Prevent accidental submission with an empty subject name.
    form.addEventListener('submit', function (event) {
        const rows = subjectsList.querySelectorAll('.as-subject-row');

        if (rows.length === 0) {
            event.preventDefault();
            alert('Please add at least one subject.');
            return;
        }

        for (const row of rows) {
            const nameInput = row.querySelector('input[name*="[subject_name]"]');

            if (!nameInput || !nameInput.value.trim()) {
                event.preventDefault();

                if (nameInput) {
                    nameInput.focus();
                    nameInput.reportValidity();
                }

                return;
            }
        }
    });

    updateSubjectRows();
});
</script>

@endsection

@push('styles')
<style>
    /* =========================================
       SCHOOL DIARY DESIGN SYSTEM
       ADD SUBJECTS
    ========================================= */

    .add-subjects-page {
        --sd-bg: #f5f3e9;
        --sd-paper: #fffefa;
        --sd-ink: #263b35;
        --sd-green: #34775a;
        --sd-green-dark: #245a43;
        --sd-green-light: #eaf3e9;
        --sd-wood: #a87543;
        --sd-cyan: #527f79;
        --sd-red: #bd5148;
        --sd-border: #e5e1d3;

        width: 100%;
        min-height: calc(100vh - 60px);
        padding: 5px 0 20px;
        background: var(--sd-bg);
        color: var(--sd-ink);
        font-size: 12px;
        box-sizing: border-box;
    }

    .add-subjects-page *,
    .add-subjects-page *::before,
    .add-subjects-page *::after {
        box-sizing: border-box;
    }

    .add-subjects-container {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
    }

    /* PAGE HEADER */

    .add-subjects-page .as-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        padding: 15px 17px;
        border: 1px solid var(--sd-border);
        border-left: 4px solid var(--sd-green);
        border-radius: 9px;
        background: var(--sd-paper);
        box-shadow: 0 3px 12px rgba(38, 59, 53, .035);
    }

    .add-subjects-page .as-heading-area {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .add-subjects-page .as-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border: 1px solid #d6e6d8;
        border-radius: 9px;
        background: var(--sd-green-light);
        color: var(--sd-green);
        font-size: 19px;
    }

    .add-subjects-page .as-heading-text {
        min-width: 0;
    }

    .add-subjects-page .as-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 5px;
        color: #8b927f;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .75px;
    }

    .add-subjects-page .as-breadcrumb i {
        color: #b8b7a7;
        font-size: 8px;
    }

    .add-subjects-page .as-heading-text h2 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 22px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -.45px;
    }

    .add-subjects-page .as-heading-text p {
        margin: 4px 0 0;
        color: #7e887d;
        font-size: 11px;
        line-height: 1.5;
    }

    .add-subjects-page .as-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        flex-shrink: 0;
        padding: 9px 12px;
        border: 1px solid var(--sd-border);
        border-radius: 7px;
        background: var(--sd-paper);
        color: var(--sd-ink);
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s ease, border-color .2s ease, color .2s ease;
    }

    .add-subjects-page .as-back-btn:hover {
        border-color: #c8ddcb;
        background: var(--sd-green-light);
        color: var(--sd-green-dark);
        text-decoration: none;
    }

    /* VALIDATION ALERT */

    .add-subjects-page .as-alert-error {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 16px;
        padding: 12px 14px;
        border: 1px solid #edc9c4;
        border-radius: 8px;
        background: #fff6f3;
        color: var(--sd-red);
    }

    .add-subjects-page .as-alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        border-radius: 7px;
        background: #fbe5e1;
        font-size: 14px;
    }

    .add-subjects-page .as-alert-content {
        min-width: 0;
    }

    .add-subjects-page .as-alert-content strong {
        display: block;
        margin: 1px 0 3px;
        font-size: 12px;
        font-weight: 800;
    }

    .add-subjects-page .as-alert-content p {
        margin: 0;
        color: #96665f;
        font-size: 11px;
    }

    .add-subjects-page .as-alert-content ul {
        margin: 7px 0 0 16px;
        padding: 0;
        color: #96665f;
        font-size: 11px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    /* FORM CARD */

    .add-subjects-page .as-form-card {
        overflow: hidden;
        border: 1px solid var(--sd-border);
        border-radius: 10px;
        background: var(--sd-paper);
        box-shadow: 0 4px 18px rgba(38, 59, 53, .045);
    }

    .add-subjects-page .as-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--sd-border);
        background: #fffdf7;
    }

    .add-subjects-page .as-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .add-subjects-page .as-section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 35px;
        width: 35px;
        height: 35px;
        border: 1px solid #d7e5d7;
        border-radius: 8px;
        background: var(--sd-green-light);
        color: var(--sd-green);
        font-size: 14px;
    }

    .add-subjects-page .as-card-heading h3 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 14px;
        font-weight: 800;
    }

    .add-subjects-page .as-card-heading p {
        margin: 3px 0 0;
        color: #858b7d;
        font-size: 10px;
        line-height: 1.5;
    }

    .add-subjects-page .as-required-note {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
        color: #898c7d;
        font-size: 10px;
    }

    .add-subjects-page .as-required {
        color: var(--sd-red);
        font-weight: 800;
    }

    /* FORM AND SECTION HEADERS */

    .add-subjects-page .as-form {
        padding: 22px;
    }

    .add-subjects-page .as-form-section {
        width: 100%;
    }

    .add-subjects-page .as-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 19px;
    }

    .add-subjects-page .as-section-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border: 1px solid #d7e5d7;
        border-radius: 8px;
        background: var(--sd-green-light);
        color: var(--sd-green-dark);
        font-size: 11px;
        font-weight: 800;
    }

    .add-subjects-page .as-section-title h4 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 13px;
        font-weight: 800;
    }

    .add-subjects-page .as-section-title p {
        margin: 3px 0 0;
        color: #898e80;
        font-size: 10px;
        line-height: 1.5;
    }

    .add-subjects-page .as-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 21px 24px;
    }

    .add-subjects-page .as-form-group,
    .add-subjects-page .as-subject-field {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .add-subjects-page .as-form-label,
    .add-subjects-page .as-subject-field > label {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        margin: 0 0 8px;
        color: var(--sd-ink);
        font-size: 11px;
        font-weight: 800;
    }

    .add-subjects-page .as-input-wrap {
        position: relative;
        width: 100%;
    }

    .add-subjects-page .as-input-icon {
        position: absolute;
        top: 50%;
        left: 12px;
        z-index: 1;
        color: #8c9a87;
        font-size: 12px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .add-subjects-page .as-form-control {
        display: block;
        width: 100%;
        min-width: 0;
        height: 41px;
        margin: 0;
        padding: 10px 12px 10px 35px;
        border: 1px solid #dedecf;
        border-radius: 7px;
        outline: none;
        background: #fffefa;
        color: var(--sd-ink);
        font-family: inherit;
        font-size: 11px;
        line-height: 1.4;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .add-subjects-page .as-form-control::placeholder {
        color: #aaa998;
        opacity: 1;
    }

    .add-subjects-page .as-form-control:hover {
        border-color: #c9d5c6;
    }

    .add-subjects-page .as-form-control:focus {
        border-color: var(--sd-green);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .add-subjects-page .as-form-select {
        padding-right: 32px;
        cursor: pointer;
    }

    .add-subjects-page .as-form-help {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 7px;
        color: #8b8e7f;
        font-size: 10px;
        line-height: 1.5;
    }

    .add-subjects-page .as-form-help i {
        margin-top: 2px;
        color: var(--sd-cyan);
        font-size: 10px;
    }

    .add-subjects-page .as-field-error {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 7px;
        color: var(--sd-red);
        font-size: 10px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .add-subjects-page .as-field-error i {
        flex-shrink: 0;
        margin-top: 2px;
    }

    .add-subjects-page .as-optional-label {
        margin-left: 4px;
        padding: 3px 5px;
        border-radius: 4px;
        background: #f1eee3;
        color: #8a795d;
        font-size: 8px;
        font-weight: 800;
        letter-spacing: .35px;
    }

    .add-subjects-page .as-form-divider {
        height: 1px;
        margin: 25px 0;
        background: var(--sd-border);
    }

    /* SELECTED CLASS INFO */

    .add-subjects-page .as-class-info {
        display: none;
        align-items: center;
        gap: 9px;
        margin-top: 9px;
        padding: 10px 11px;
        border: 1px solid #d5e5d3;
        border-radius: 7px;
        background: #f0f6e9;
    }

    .add-subjects-page .as-class-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 27px;
        width: 27px;
        height: 27px;
        border-radius: 6px;
        background: #e0edd9;
        color: var(--sd-green);
        font-size: 12px;
    }

    .add-subjects-page .as-class-info > div:last-child {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .add-subjects-page .as-class-info span {
        color: #7b8a71;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .add-subjects-page .as-class-info strong {
        color: var(--sd-green-dark);
        font-size: 11px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    /* SUBJECTS PANEL */

    .add-subjects-page .as-subjects-panel {
        overflow: hidden;
        border: 1px solid var(--sd-border);
        border-radius: 9px;
        background: var(--sd-paper);
    }

    .add-subjects-page .as-subjects-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 13px 15px;
        border-bottom: 1px solid var(--sd-border);
        background: #fbfaf3;
    }

    .add-subjects-page .as-subjects-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .add-subjects-page .as-subjects-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border: 1px solid #d7e5d7;
        border-radius: 8px;
        background: var(--sd-green-light);
        color: var(--sd-green);
        font-size: 13px;
    }

    .add-subjects-page .as-subjects-heading h5 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 12px;
        font-weight: 800;
    }

    .add-subjects-page .as-subjects-heading p {
        margin: 3px 0 0;
        color: #898e80;
        font-size: 10px;
    }

    .add-subjects-page .as-subjects-heading p span {
        color: var(--sd-green);
        font-weight: 800;
    }

    .add-subjects-page .as-add-subject-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        flex-shrink: 0;
        min-height: 36px;
        padding: 8px 12px;
        border: 1px solid var(--sd-green);
        border-radius: 7px;
        background: var(--sd-green);
        color: #fffefa;
        font-family: inherit;
        font-size: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, transform .2s ease;
    }

    .add-subjects-page .as-add-subject-btn:hover {
        border-color: var(--sd-green-dark);
        background: var(--sd-green-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .add-subjects-page .as-subjects-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 15px;
    }

    /* INDIVIDUAL SUBJECT ROW */

    .add-subjects-page .as-subject-row {
        display: grid;
        grid-template-columns: 38px minmax(0, 1.3fr) minmax(0, 1fr) 36px;
        align-items: end;
        gap: 12px;
        min-width: 0;
        padding: 13px;
        border: 1px solid #e6e3d7;
        border-radius: 8px;
        background: #fffefa;
        transition: border-color .2s ease, background .2s ease;
    }

    .add-subjects-page .as-subject-row:hover {
        border-color: #d3ddcc;
        background: #fffef9;
    }

    .add-subjects-page .as-subject-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        margin-bottom: 2px;
        border: 1px solid #d7e5d7;
        border-radius: 7px;
        background: var(--sd-green-light);
        color: var(--sd-green-dark);
        font-size: 11px;
        font-weight: 800;
    }

    .add-subjects-page .as-subject-field > label {
        margin-bottom: 7px;
        font-size: 10px;
    }

    .add-subjects-page .as-subject-field .as-form-control {
        height: 39px;
        font-size: 10px;
    }

    .add-subjects-page .as-remove-subject-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 39px;
        margin-bottom: 0;
        border: 1px solid #ebd3c9;
        border-radius: 7px;
        background: #fff5ef;
        color: var(--sd-red);
        font-size: 12px;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .add-subjects-page .as-remove-subject-btn:hover {
        border-color: var(--sd-red);
        background: var(--sd-red);
        color: #fff;
    }

    .add-subjects-page .as-subjects-footer {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        padding: 11px 15px;
        border-top: 1px solid #ece9dd;
        background: #fbfaf3;
        color: #898d7e;
        font-size: 10px;
        line-height: 1.5;
    }

    .add-subjects-page .as-subjects-footer i {
        margin-top: 2px;
        color: var(--sd-wood);
    }

    /* STATUS */

    .add-subjects-page .as-status-panel {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .add-subjects-page .as-status-option {
        position: relative;
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
        min-height: 63px;
        padding: 10px 11px;
        overflow: hidden;
        border: 1px solid var(--sd-border);
        border-radius: 8px;
        background: #fffefa;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .add-subjects-page .as-status-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .add-subjects-page .as-status-radio {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        border: 1.5px solid #c7c9b9;
        border-radius: 50%;
        background: #fffefa;
    }

    .add-subjects-page .as-status-content {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .add-subjects-page .as-status-title {
        color: var(--sd-ink);
        font-size: 11px;
        font-weight: 800;
    }

    .add-subjects-page .as-status-description {
        color: #8b8d80;
        font-size: 9px;
        line-height: 1.4;
    }

    .add-subjects-page .as-status-check {
        display: none;
        flex-shrink: 0;
        color: var(--sd-green);
        font-size: 13px;
    }

    .add-subjects-page .as-status-active:has(input:checked) {
        border-color: #a9cdb0;
        background: #f0f7ed;
        box-shadow: 0 0 0 2px rgba(52, 119, 90, .05);
    }

    .add-subjects-page .as-status-active input:checked + .as-status-radio {
        border-color: var(--sd-green);
        background: var(--sd-green);
        box-shadow: inset 0 0 0 4px #fffefa;
    }

    .add-subjects-page .as-status-active:has(input:checked) .as-status-check {
        display: block;
    }

    .add-subjects-page .as-status-inactive:has(input:checked) {
        border-color: #e3c5bd;
        background: #fff5f1;
        box-shadow: 0 0 0 2px rgba(189, 81, 72, .04);
    }

    .add-subjects-page .as-status-inactive input:checked + .as-status-radio {
        border-color: var(--sd-red);
        background: var(--sd-red);
        box-shadow: inset 0 0 0 4px #fffefa;
    }

    .add-subjects-page .as-status-inactive:has(input:checked) .as-status-check {
        display: block;
        color: var(--sd-red);
    }

    .add-subjects-page .as-status-option:focus-within {
        outline: 2px solid rgba(52, 119, 90, .25);
        outline-offset: 2px;
    }

    /* FORM ACTIONS */

    .add-subjects-page .as-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 28px;
        padding-top: 18px;
        border-top: 1px solid var(--sd-border);
    }

    .add-subjects-page .as-action-note {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #898d7e;
        font-size: 10px;
        line-height: 1.5;
    }

    .add-subjects-page .as-action-note i {
        color: var(--sd-wood);
        font-size: 12px;
    }

    .add-subjects-page .as-action-buttons {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
    }

    .add-subjects-page .as-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 9px 15px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.3;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
    }

    .add-subjects-page .as-btn-primary {
        border-color: var(--sd-green);
        background: var(--sd-green);
        color: #fffefa;
    }

    .add-subjects-page .as-btn-primary:hover {
        border-color: var(--sd-green-dark);
        background: var(--sd-green-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .add-subjects-page .as-btn-secondary {
        border-color: var(--sd-border);
        background: #fffefa;
        color: #687367;
    }

    .add-subjects-page .as-btn-secondary:hover {
        border-color: #d2d6c5;
        background: #f1eee3;
        color: var(--sd-ink);
    }

    .add-subjects-page .as-btn:focus-visible,
    .add-subjects-page .as-add-subject-btn:focus-visible,
    .add-subjects-page .as-remove-subject-btn:focus-visible,
    .add-subjects-page .as-back-btn:focus-visible {
        outline: 2px solid var(--sd-green);
        outline-offset: 3px;
    }

    /* PAGE FOOTER */

    .add-subjects-page .as-page-footer {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
        padding: 15px 8px 0;
        color: #969787;
        font-size: 10px;
    }

    .add-subjects-page .as-page-footer i {
        color: var(--sd-green);
    }

    .add-subjects-page .as-footer-dot {
        color: var(--sd-wood);
    }

    /* TABLET */

    @media (max-width: 991.98px) {
        .add-subjects-page .as-form {
            padding: 19px;
        }

        .add-subjects-page .as-subject-row {
            grid-template-columns: 35px minmax(0, 1fr) minmax(0, 1fr) 34px;
            gap: 9px;
            padding: 10px;
        }

        .add-subjects-page .as-subject-number,
        .add-subjects-page .as-remove-subject-btn {
            width: 34px;
        }
    }

    /* MOBILE */

    @media (max-width: 767.98px) {
        .add-subjects-page .as-page-header {
            align-items: flex-start;
            padding: 13px;
        }

        .add-subjects-page .as-heading-icon {
            flex-basis: 40px;
            width: 40px;
            height: 40px;
            font-size: 17px;
        }

        .add-subjects-page .as-heading-text h2 {
            font-size: 19px;
        }

        .add-subjects-page .as-back-btn {
            padding: 9px 10px;
        }

        .add-subjects-page .as-back-btn span {
            display: none;
        }

        .add-subjects-page .as-form-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 17px;
        }

        .add-subjects-page .as-card-header {
            padding: 13px 15px;
        }

        .add-subjects-page .as-form {
            padding: 17px;
        }

        .add-subjects-page .as-subjects-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .add-subjects-page .as-add-subject-btn {
            width: 100%;
        }

        .add-subjects-page .as-subject-row {
            grid-template-columns: 35px minmax(0, 1fr) 35px;
            gap: 10px;
            align-items: start;
            padding: 12px;
        }

        .add-subjects-page .as-subject-number {
            grid-column: 1;
            grid-row: 1;
            width: 35px;
            height: 35px;
        }

        .add-subjects-page .as-subject-field:not(.as-subject-code-field) {
            grid-column: 2 / 3;
            grid-row: 1;
        }

        .add-subjects-page .as-subject-code-field {
            grid-column: 2 / 3;
            grid-row: 2;
        }

        .add-subjects-page .as-remove-subject-btn {
            grid-column: 3;
            grid-row: 1;
            width: 35px;
            height: 35px;
        }

        .add-subjects-page .as-status-panel {
            grid-template-columns: minmax(0, 1fr);
        }

        .add-subjects-page .as-form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .add-subjects-page .as-action-note {
            max-width: none;
        }

        .add-subjects-page .as-action-buttons {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            width: 100%;
        }

        .add-subjects-page .as-btn {
            width: 100%;
            padding-right: 9px;
            padding-left: 9px;
        }
    }

    /* SMALL MOBILE */

    @media (max-width: 480px) {
        .add-subjects-page {
            padding-top: 2px;
        }

        .add-subjects-page .as-page-header {
            gap: 9px;
            margin-bottom: 14px;
            padding: 11px;
        }

        .add-subjects-page .as-heading-area {
            gap: 9px;
        }

        .add-subjects-page .as-heading-icon {
            flex-basis: 35px;
            width: 35px;
            height: 35px;
            font-size: 15px;
        }

        .add-subjects-page .as-breadcrumb {
            gap: 4px;
            font-size: 8px;
            letter-spacing: .35px;
        }

        .add-subjects-page .as-heading-text h2 {
            font-size: 17px;
        }

        .add-subjects-page .as-heading-text p {
            font-size: 10px;
        }

        .add-subjects-page .as-card-header {
            align-items: flex-start;
            gap: 7px;
            padding: 12px;
        }

        .add-subjects-page .as-card-heading {
            gap: 8px;
        }

        .add-subjects-page .as-section-icon {
            flex-basis: 31px;
            width: 31px;
            height: 31px;
            font-size: 12px;
        }

        .add-subjects-page .as-card-heading h3 {
            font-size: 12px;
        }

        .add-subjects-page .as-card-heading p {
            font-size: 9px;
        }

        .add-subjects-page .as-required-note {
            font-size: 9px;
        }

        .add-subjects-page .as-form {
            padding: 14px 12px;
        }

        .add-subjects-page .as-section-title {
            gap: 8px;
            margin-bottom: 15px;
        }

        .add-subjects-page .as-section-number {
            flex-basis: 30px;
            width: 30px;
            height: 30px;
        }

        .add-subjects-page .as-section-title h4 {
            font-size: 12px;
        }

        .add-subjects-page .as-section-title p {
            font-size: 9px;
        }

        .add-subjects-page .as-form-divider {
            margin: 21px 0;
        }

        .add-subjects-page .as-subjects-header {
            padding: 12px;
        }

        .add-subjects-page .as-subjects-list {
            gap: 9px;
            padding: 10px;
        }

        .add-subjects-page .as-subject-row {
            grid-template-columns: 31px minmax(0, 1fr) 31px;
            gap: 8px;
            padding: 9px;
        }

        .add-subjects-page .as-subject-number,
        .add-subjects-page .as-remove-subject-btn {
            width: 31px;
            height: 34px;
        }

        .add-subjects-page .as-subject-field > label {
            font-size: 9px;
        }

        .add-subjects-page .as-subject-field .as-form-control {
            height: 37px;
            padding-left: 31px;
            font-size: 10px;
        }

        .add-subjects-page .as-subject-field .as-input-icon {
            left: 10px;
            font-size: 10px;
        }

        .add-subjects-page .as-subjects-footer {
            padding: 10px 12px;
            font-size: 9px;
        }

        .add-subjects-page .as-form-actions {
            margin-top: 22px;
            padding-top: 15px;
        }

        .add-subjects-page .as-action-buttons {
            gap: 7px;
        }

        .add-subjects-page .as-btn {
            gap: 5px;
            font-size: 10px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .add-subjects-page *,
        .add-subjects-page *::before,
        .add-subjects-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>
@endpush
