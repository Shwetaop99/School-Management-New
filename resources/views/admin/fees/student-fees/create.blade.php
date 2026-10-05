@extends('layouts.app')

@section('title', 'Assign Student Fee')

@section('content')

<style>
    .student-fee-page {
        background: #f4f7fb;
        min-height: calc(100vh - 64px);
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .page-title h2 {
        margin: 0;
        color: #172033;
        font-size: 25px;
        font-weight: 700;
    }

    .page-title p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid #dbe2ec;
        border-radius: 9px;
        background: #fff;
        color: #526071;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-back:hover {
        background: #f8fafc;
        color: #344054;
    }

    .card-box {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(30, 55, 90, .04);
        overflow: hidden;
    }

    .card-header-custom {
        padding: 18px 22px;
        border-bottom: 1px solid #edf1f6;
    }

    .card-header-custom h5 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .card-header-custom p {
        margin: 4px 0 0;
        color: #8792a2;
        font-size: 12px;
    }

    .card-body-custom {
        padding: 22px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #e04b4b;
    }

    .form-control,
    .form-select {
        width: 100%;
        height: 44px;
        border: 1px solid #dbe2ec;
        border-radius: 9px;
        padding: 0 13px;
        background: #fff;
        color: #344054;
        font-size: 13px;
        outline: none;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .08);
    }

    .form-control[readonly] {
        background: #f8fafc;
    }

    .form-select:disabled {
        background: #f5f7fa;
        color: #98a2b3;
        cursor: not-allowed;
    }

    textarea.form-control {
        height: auto;
        min-height: 100px;
        padding: 12px 13px;
        resize: vertical;
    }

    .hint {
        display: block;
        margin-top: 5px;
        color: #8a95a5;
        font-size: 11px;
    }

    .error-text {
        display: block;
        margin-top: 5px;
        color: #d13c3c;
        font-size: 11px;
    }

    /* CLASS SELECTION INFO */
    .class-selection-note {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-top: 10px;
        padding: 10px 12px;
        border-radius: 9px;
        background: #eef6ff;
        border: 1px solid #d9eaff;
        color: #4d6b91;
        font-size: 11px;
    }

    .class-selection-note i {
        color: #147cf5;
        font-size: 15px;
    }

    /* STUDENT PREVIEW */
    .student-preview {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 13px;
        border-radius: 10px;
        background: #f4f8ff;
        border: 1px solid #dceaff;
    }

    .student-preview.show {
        display: flex;
    }

    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #e4efff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        overflow: hidden;
        flex-shrink: 0;
    }

    .student-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .student-preview-name {
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .student-preview-details {
        margin-top: 4px;
        color: #718096;
        font-size: 11px;
    }

    /* STRUCTURE */
    .structure-details {
        display: none;
        margin-top: 12px;
        padding: 14px;
        border-radius: 10px;
        background: #f8f7ff;
        border: 1px solid #e7e3ff;
    }

    .structure-details.show {
        display: block;
    }

    .structure-details-title {
        color: #4d46a8;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .fee-items {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .fee-item {
        padding: 7px 10px;
        border-radius: 7px;
        background: #fff;
        border: 1px solid #e5e1ff;
        color: #5d5a85;
        font-size: 11px;
    }

    /* CALCULATION */
    .calculation-box {
        background: #f8faff;
        border: 1px solid #e1e9f5;
        border-radius: 12px;
        padding: 17px;
    }

    .calculation-row {
        display: flex;
        justify-content: space-between;
        padding: 9px 0;
        border-bottom: 1px dashed #dfe5ee;
        color: #526071;
        font-size: 13px;
    }

    .calculation-row strong {
        color: #172033;
    }

    .discount-value {
        color: #16a36a !important;
    }

    .fine-value {
        color: #e8590c !important;
    }

    .calculation-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 13px;
        margin-top: 8px;
        border-top: 2px solid #dce4ef;
    }

    .calculation-total span {
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .calculation-total strong {
        color: #147cf5;
        font-size: 21px;
        font-weight: 800;
    }

    /* ACTIONS */
    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 22px;
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 15px;
    }

    .btn-cancel,
    .btn-submit {
        height: 43px;
        padding: 0 19px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-cancel {
        background: #fff;
        color: #526071;
        border: 1px solid #dbe2ec;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #344054;
    }

    .btn-submit {
        background: #147cf5;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: #0d68d7;
    }

    .btn-submit:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    .alert-errors {
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 10px;
        background: #fff0f0;
        color: #c03939;
        border: 1px solid #ffd5d5;
        font-size: 13px;
    }

    .alert-errors ul {
        margin: 7px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 768px) {

        .student-fee-page {
            padding: 15px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
        }
    }
</style>


<div class="student-fee-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div class="page-title">
            <h2>Assign Student Fee</h2>
            <p>Assign an existing fee structure to a student.</p>
        </div>

        <a href="{{ route('admin.fees.student-fees.index') }}"
           class="btn-back">
            <i class="bi bi-arrow-left"></i>
            Back to Student Fees
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="alert-errors">

            <strong>
                Please correct the following errors:
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.fees.student-fees.store') }}"
        id="studentFeeForm"
    >

        @csrf


        {{-- =====================================================
             STUDENT INFORMATION
             ===================================================== --}}

        <div class="card-box">

            <div class="card-header-custom">

                <h5>
                    <i class="bi bi-person-vcard me-1"></i>
                    Student Information
                </h5>

                <p>
                    First select the class, then select a student from that class.
                </p>

            </div>

            <div class="card-body-custom">

                <div class="form-grid">


                    {{-- CLASS FIRST --}}
                    <div class="form-group">

                        <label
                            for="class_id"
                            class="form-label"
                        >
                            Select Class <span class="required">*</span>
                        </label>

                        <select
                            id="class_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Select Class First --
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    data-class-name="{{ $class->class_name }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}
                                >
                                    {{ $class->class_name }}
                                </option>

                            @endforeach

                        </select>

                        <span class="hint">
                            Select the class to load its students.
                        </span>

                        <div class="class-selection-note">
                            <i class="bi bi-info-circle"></i>
                            <span>
                                Students will appear only after selecting a class.
                            </span>
                        </div>

                    </div>


                    {{-- ACADEMIC YEAR --}}
                    <div class="form-group">

                        <label
                            for="academic_year"
                            class="form-label"
                        >
                            Academic Year <span class="required">*</span>
                        </label>

                        <select
                            name="academic_year"
                            id="academic_year"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Select Academic Year --
                            </option>

                            @foreach($academicYears as $year)

                                <option
                                    value="{{ $year }}"
                                    {{ old('academic_year') == $year ? 'selected' : '' }}
                                >
                                    {{ $year }}
                                </option>

                            @endforeach

                        </select>

                        @error('academic_year')
                            <span class="error-text">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- STUDENT --}}
                    <div class="form-group full">

                        <label
                            for="student_id"
                            class="form-label"
                        >
                            Select Student <span class="required">*</span>
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            class="form-select"
                            required
                            disabled
                        >

                            <option value="">
                                -- Select Class First --
                            </option>

                            @foreach($students as $student)

                                @php
                                    $fullName = trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    );
                                @endphp

                                <option
                                    value="{{ $student->id }}"
                                    data-student-id="{{ $student->student_id }}"
                                    data-name="{{ $fullName }}"
                                    data-year="{{ $student->academic_year ?? '' }}"
                                    data-class="{{ $student->class ?? '' }}"
                                    data-section="{{ $student->section ?? '' }}"
                                    data-image="{{ !empty($student->profile_image) ? asset('storage/' . $student->profile_image) : '' }}"
                                    {{ old('student_id') == $student->id ? 'selected' : '' }}
                                >
                                    {{ $student->student_id }} -
                                    {{ $fullName }}
                                </option>

                            @endforeach

                        </select>

                        <span class="hint">
                            Only students belonging to the selected class will be shown.
                        </span>

                        @error('student_id')
                            <span class="error-text">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- STUDENT PREVIEW --}}
                    <div
                        class="form-group full student-preview"
                        id="studentPreview"
                    >

                        <div
                            class="student-avatar"
                            id="studentAvatar"
                        >
                            <span id="studentInitial">S</span>
                        </div>

                        <div>

                            <div
                                class="student-preview-name"
                                id="studentName"
                            >
                                Student
                            </div>

                            <div
                                class="student-preview-details"
                                id="studentDetails"
                            >
                                Student information
                            </div>

                        </div>

                    </div>


                    {{-- STUDENT ID --}}
                    <div class="form-group">

                        <label class="form-label">
                            Student ID
                        </label>

                        <input
                            type="text"
                            id="display_student_id"
                            class="form-control"
                            readonly
                            placeholder="Select student"
                        >

                    </div>


                    {{-- SECTION --}}
                    <div class="form-group">

                        <label class="form-label">
                            Student Section
                        </label>

                        <input
                            type="text"
                            id="display_section"
                            class="form-control"
                            readonly
                            placeholder="Select student"
                        >

                    </div>


                    {{-- CLASS DISPLAY --}}
                    <div class="form-group">

                        <label class="form-label">
                            Student Class
                        </label>

                        <input
                            type="text"
                            id="display_class"
                            class="form-control"
                            readonly
                            placeholder="Select student"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FEE STRUCTURE
             ===================================================== --}}

        <div class="card-box">

            <div class="card-header-custom">

                <h5>
                    <i class="bi bi-file-earmark-text me-1"></i>
                    Fee Structure
                </h5>

                <p>
                    Select the active fee structure to assign.
                </p>

            </div>

            <div class="card-body-custom">

                <div class="form-grid">

                    <div class="form-group full">

                        <label
                            for="fee_structure_id"
                            class="form-label"
                        >
                            Fee Structure <span class="required">*</span>
                        </label>

                        <select
                            name="fee_structure_id"
                            id="fee_structure_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Select Fee Structure --
                            </option>

                            @foreach($feeStructures as $structure)

                                @php
                                    $structureTotal = $structure->items->sum('amount');
                                @endphp

                                <option
                                    value="{{ $structure->id }}"
                                    data-total="{{ $structureTotal }}"
                                    data-year="{{ $structure->academic_year }}"
                                    data-class-id="{{ $structure->class_id }}"
                                >

                                    {{ $structure->structure_name }}

                                    -
                                    {{ $structure->schoolClass->class_name ?? 'Class' }}

                                    @if($structure->section)
                                        / {{ $structure->section->section_name }}
                                    @else
                                        / All Sections
                                    @endif

                                    -
                                    &#8377;{{ number_format((float) $structureTotal, 2) }}

                                    -
                                    {{ $structure->academic_year }}

                                </option>

                            @endforeach

                        </select>

                        @error('fee_structure_id')
                            <span class="error-text">
                                {{ $message }}
                            </span>
                        @enderror


                        {{-- FEE ITEMS --}}
                        <div
                            class="structure-details"
                            id="structureDetails"
                        >

                            <div class="structure-details-title">
                                Fee Structure Items
                            </div>

                            <div
                                class="fee-items"
                                id="feeItems"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             AMOUNT
             ===================================================== --}}

        <div class="card-box">

            <div class="card-header-custom">

                <h5>
                    <i class="bi bi-calculator me-1"></i>
                    Fee Amount
                </h5>

                <p>
                    Review the fee amount before assigning.
                </p>

            </div>

            <div class="card-body-custom">

                <div class="form-grid">


                    {{-- DISCOUNT --}}
                    <div class="form-group">

                        <label
                            for="discount_amount"
                            class="form-label"
                        >
                            Discount Amount
                        </label>

                        <input
                            type="number"
                            name="discount_amount"
                            id="discount_amount"
                            class="form-control"
                            value="{{ old('discount_amount', 0) }}"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        >

                        @error('discount_amount')
                            <span class="error-text">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- FINE --}}
                    <div class="form-group">

                        <label
                            for="fine_amount"
                            class="form-label"
                        >
                            Fine Amount
                        </label>

                        <input
                            type="number"
                            name="fine_amount"
                            id="fine_amount"
                            class="form-control"
                            value="{{ old('fine_amount', 0) }}"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        >

                        @error('fine_amount')
                            <span class="error-text">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- DUE DATE --}}
                    <div class="form-group">

                        <label
                            for="due_date"
                            class="form-label"
                        >
                            Due Date
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            class="form-control"
                            value="{{ old('due_date') }}"
                        >

                        @error('due_date')
                            <span class="error-text">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- CALCULATION --}}
                    <div class="form-group">

                        <div class="calculation-box">

                            <div class="calculation-row">
                                <span>Structure Total</span>
                                <strong id="totalDisplay">
                                    &#8377;0.00
                                </strong>
                            </div>

                            <div class="calculation-row">
                                <span>Discount</span>

                                <strong
                                    id="discountDisplay"
                                    class="discount-value"
                                >
                                    - &#8377;0.00
                                </strong>
                            </div>

                            <div class="calculation-row">
                                <span>Fine</span>

                                <strong
                                    id="fineDisplay"
                                    class="fine-value"
                                >
                                    + &#8377;0.00
                                </strong>
                            </div>

                            <div class="calculation-total">

                                <span>
                                    Final Balance
                                </span>

                                <strong id="balanceDisplay">
                                    &#8377;0.00
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- REMARKS --}}
                    <div class="form-group full">

                        <label
                            for="remarks"
                            class="form-label"
                        >
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            class="form-control"
                            placeholder="Optional remarks..."
                        >{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <span class="error-text">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="actions">

            <a
                href="{{ route('admin.fees.student-fees.index') }}"
                class="btn-cancel"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn-submit"
                id="submitButton"
            >
                <i class="bi bi-check-lg"></i>
                Assign Fee
            </button>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const classSelect =
        document.getElementById('class_id');

    const studentSelect =
        document.getElementById('student_id');

    const academicYear =
        document.getElementById('academic_year');

    const studentPreview =
        document.getElementById('studentPreview');

    const studentAvatar =
        document.getElementById('studentAvatar');

    const studentName =
        document.getElementById('studentName');

    const studentDetails =
        document.getElementById('studentDetails');

    const studentIdDisplay =
        document.getElementById('display_student_id');

    const classDisplay =
        document.getElementById('display_class');

    const sectionDisplay =
        document.getElementById('display_section');

    const feeStructure =
        document.getElementById('fee_structure_id');

    const structureDetails =
        document.getElementById('structureDetails');

    const feeItems =
        document.getElementById('feeItems');

    const discount =
        document.getElementById('discount_amount');

    const fine =
        document.getElementById('fine_amount');

    const totalDisplay =
        document.getElementById('totalDisplay');

    const discountDisplay =
        document.getElementById('discountDisplay');

    const fineDisplay =
        document.getElementById('fineDisplay');

    const balanceDisplay =
        document.getElementById('balanceDisplay');


    /*
    |--------------------------------------------------------------------------
    | STORE ALL STUDENTS
    |--------------------------------------------------------------------------
    */

    const allStudentOptions =
        Array.from(
            studentSelect.querySelectorAll(
                'option[data-student-id]'
            )
        ).map(function (option) {

            return option.cloneNode(true);

        });


    /*
    |--------------------------------------------------------------------------
    | FORMAT MONEY
    |--------------------------------------------------------------------------
    */

    function formatMoney(value) {

        const number =
            Number(value || 0);

        return '₹' +
            number.toLocaleString(
                'en-IN',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RESET STUDENT
    |--------------------------------------------------------------------------
    */

    function resetStudent() {

        studentSelect.innerHTML =
            '<option value="">-- Select Class First --</option>';

        studentSelect.disabled = true;

        studentPreview.classList.remove('show');

        studentIdDisplay.value = '';
        classDisplay.value = '';
        sectionDisplay.value = '';

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS FOR CLASS
    |--------------------------------------------------------------------------
    */

    function loadStudentsForClass() {

        const selectedClassId =
            classSelect.value;

        const selectedClassName =
            classSelect.options[
                classSelect.selectedIndex
            ]?.dataset.className || '';


        resetStudent();


        if (!selectedClassId) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | FILTER BY STUDENT.class
        |
        | Your Student model stores the class directly
        | in students.class.
        |--------------------------------------------------------------------------
        */

        const matchingStudents =
            allStudentOptions.filter(function (option) {

                const studentClass =
                    (option.dataset.class || '')
                        .trim()
                        .toLowerCase();

                const className =
                    selectedClassName
                        .trim()
                        .toLowerCase();

                /*
                 * Match either:
                 * 1. class name
                 * 2. class id
                 *
                 * This makes the page safer with your
                 * existing student data.
                 */

                return (
                    studentClass === className ||
                    studentClass === String(selectedClassId)
                );

            });


        studentSelect.innerHTML =
            '';


        const defaultOption =
            document.createElement('option');

        defaultOption.value = '';

        defaultOption.textContent =
            matchingStudents.length
                ? '-- Select Student --'
                : '-- No Students Found In This Class --';

        studentSelect.appendChild(
            defaultOption
        );


        matchingStudents.forEach(function (option) {

            studentSelect.appendChild(
                option
            );

        });


        studentSelect.disabled =
            matchingStudents.length === 0;


        /*
        |--------------------------------------------------------------------------
        | RESTORE OLD STUDENT AFTER VALIDATION ERROR
        |--------------------------------------------------------------------------
        */

        const oldStudentId =
            @json(old('student_id'));

        if (
            oldStudentId &&
            matchingStudents.some(
                function (option) {
                    return option.value ==
                        oldStudentId;
                }
            )
        ) {

            studentSelect.value =
                oldStudentId;

            updateStudent();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SELECTED STUDENT
    |--------------------------------------------------------------------------
    */

    function updateStudent() {

        const option =
            studentSelect.options[
                studentSelect.selectedIndex
            ];

        if (!option || !option.value) {

            studentPreview.classList.remove('show');

            studentIdDisplay.value = '';
            classDisplay.value = '';
            sectionDisplay.value = '';

            return;
        }


        const id =
            option.dataset.studentId || '';

        const name =
            option.dataset.name || 'Student';

        const year =
            option.dataset.year || '';

        const className =
            option.dataset.class || '';

        const section =
            option.dataset.section || '';

        const image =
            option.dataset.image || '';


        studentIdDisplay.value =
            id;

        classDisplay.value =
            className || 'Not available';

        sectionDisplay.value =
            section || 'Not available';

        studentName.textContent =
            name;


        let details =
            'ID: ' + id;

        if (year) {
            details +=
                ' • Academic Year: ' + year;
        }

        if (className) {
            details +=
                ' • Class: ' + className;
        }

        if (section) {
            details +=
                ' • Section: ' + section;
        }

        studentDetails.textContent =
            details;


        studentAvatar.innerHTML = '';


        if (image) {

            const img =
                document.createElement('img');

            img.src = image;
            img.alt = name;

            img.onerror = function () {

                studentAvatar.innerHTML =
                    '<span>' +
                    name.charAt(0).toUpperCase() +
                    '</span>';

            };

            studentAvatar.appendChild(img);

        } else {

            studentAvatar.innerHTML =
                '<span>' +
                name.charAt(0).toUpperCase() +
                '</span>';

        }


        studentPreview.classList.add('show');


        /*
        |--------------------------------------------------------------------------
        | AUTO SELECT ACADEMIC YEAR
        |--------------------------------------------------------------------------
        */

        if (year) {

            const yearOption =
                Array.from(
                    academicYear.options
                ).find(function (item) {

                    return item.value === year;

                });

            if (yearOption) {

                academicYear.value =
                    year;

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | FEE STRUCTURE
    |--------------------------------------------------------------------------
    */

    function updateFeeStructure() {

        const option =
            feeStructure.options[
                feeStructure.selectedIndex
            ];

        if (!option || !option.value) {

            structureDetails.classList.remove('show');

            feeItems.innerHTML = '';

            updateCalculation(0);

            return;
        }


        const total =
            Number(
                option.dataset.total || 0
            );


        feeItems.innerHTML = '';


        const item =
            document.createElement('div');

        item.className =
            'fee-item';

        item.textContent =
            'Total Fee: ' +
            formatMoney(total);

        feeItems.appendChild(item);

        structureDetails.classList.add('show');

        updateCalculation(total);

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATION
    |--------------------------------------------------------------------------
    */

    function updateCalculation(
        suppliedTotal = null
    ) {

        let total = 0;


        if (suppliedTotal !== null) {

            total =
                Number(
                    suppliedTotal || 0
                );

        } else {

            const option =
                feeStructure.options[
                    feeStructure.selectedIndex
                ];

            if (option && option.value) {

                total =
                    Number(
                        option.dataset.total || 0
                    );

            }

        }


        let discountAmount =
            Number(
                discount.value || 0
            );

        let fineAmount =
            Number(
                fine.value || 0
            );


        discountAmount =
            Math.max(0, discountAmount);

        fineAmount =
            Math.max(0, fineAmount);


        const balance =
            Math.max(
                0,
                total -
                discountAmount +
                fineAmount
            );


        totalDisplay.textContent =
            formatMoney(total);

        discountDisplay.textContent =
            '- ' +
            formatMoney(discountAmount);

        fineDisplay.textContent =
            '+ ' +
            formatMoney(fineAmount);

        balanceDisplay.textContent =
            formatMoney(balance);

    }


    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        function () {

            /*
             * Clear previously selected fee structure
             * when class changes.
             */
            feeStructure.value = '';

            structureDetails.classList.remove('show');
            feeItems.innerHTML = '';

            updateCalculation(0);

            loadStudentsForClass();

        }
    );


    studentSelect.addEventListener(
        'change',
        updateStudent
    );


    feeStructure.addEventListener(
        'change',
        updateFeeStructure
    );


    discount.addEventListener(
        'input',
        function () {
            updateCalculation();
        }
    );


    fine.addEventListener(
        'input',
        function () {
            updateCalculation();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('studentFeeForm')
        .addEventListener(
            'submit',
            function () {

                const button =
                    document.getElementById(
                        'submitButton'
                    );

                button.disabled = true;

                button.innerHTML =
                    '<i class="bi bi-hourglass-split"></i> Saving...';

            }
        );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    const initialClass =
        @json(old('class_id'));

    if (initialClass) {

        classSelect.value =
            initialClass;

        loadStudentsForClass();

    } else {

        resetStudent();

    }


    updateCalculation();

});
</script>

@endsection