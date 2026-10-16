
@extends('layouts.app')

@section('title', 'Create Fee Structure')

@section('content')

<style>
    .fee-page {
        background: #f4f7fb;
        min-height: calc(100vh - 64px);
        padding: 25px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .page-title {
        margin: 0;
        font-size: 27px;
        font-weight: 700;
        color: #1f2937;
    }

    .page-subtitle {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 17px;
        border-radius: 9px;
        background: #fff;
        color: #374151;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        font-weight: 600;
        transition: .2s;
    }

    .back-btn:hover {
        color: #1769d1;
        border-color: #1769d1;
        background: #f8fbff;
    }

    .form-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7ebf2;
        box-shadow: 0 5px 20px rgba(31, 41, 55, .06);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .card-header-custom {
        padding: 18px 22px;
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
    }

    .card-header-custom h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
    }

    .card-header-custom p {
        margin: 4px 0 0;
        font-size: 13px;
        opacity: .9;
    }

    .card-body-custom {
        padding: 24px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
        font-size: 14px;
    }

    .required {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border: 1px solid #dce2ea;
        border-radius: 9px;
        font-size: 14px;
        box-shadow: none !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .10) !important;
    }

    .form-select:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }

    .field-help {
        font-size: 12px;
        color: #8a94a6;
        margin-top: 5px;
    }

    .fee-table-wrapper {
        overflow-x: auto;
    }

    .fee-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .fee-table th {
        background: #f6f8fc;
        color: #4b5563;
        font-size: 13px;
        font-weight: 700;
        padding: 13px 10px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .fee-table td {
        padding: 10px;
        border-bottom: 1px solid #edf0f5;
        vertical-align: top;
    }

    .fee-table .form-control,
    .fee-table .form-select {
        min-width: 130px;
    }

    /*
    |--------------------------------------------------------------------------
    | MANUAL FEE FIELD
    |--------------------------------------------------------------------------
    */

    .manual-fee-name {
        margin-top: 8px;
        display: none;
    }

    .manual-label {
        display: none;
        margin-top: 6px;
        font-size: 11px;
        color: #6c63ff;
        font-weight: 600;
    }

    .manual-fee-name.is-invalid {
        border-color: #dc3545 !important;
    }

    .add-row-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid #147cf5;
        color: #147cf5;
        background: #fff;
        border-radius: 8px;
        padding: 9px 15px;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
    }

    .add-row-btn:hover {
        background: #147cf5;
        color: #fff;
    }

    .remove-row-btn {
        width: 38px;
        height: 38px;
        border: 1px solid #f1c5ca;
        color: #dc3545;
        background: #fff;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
    }

    .remove-row-btn:hover {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }

    .total-box {
        background: #f5f8ff;
        border: 1px solid #dbe7ff;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 18px;
    }

    .total-label {
        font-size: 14px;
        font-weight: 700;
        color: #4b5563;
    }

    .total-amount {
        font-size: 22px;
        font-weight: 800;
        color: #1769d1;
    }

    .bottom-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 5px;
        padding-bottom: 25px;
    }

    .btn-cancel {
        background: #fff;
        border: 1px solid #d9dee7;
        color: #4b5563;
        padding: 11px 20px;
        border-radius: 9px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-save {
        border: 0;
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        padding: 11px 22px;
        border-radius: 9px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 5px 14px rgba(20, 124, 245, .20);
    }

    .btn-save:hover {
        opacity: .94;
    }

    .alert-danger-custom {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #9f1239;
        border-radius: 10px;
        padding: 14px 17px;
        margin-bottom: 20px;
    }

    .alert-danger-custom ul {
        margin: 6px 0 0 20px;
    }

    .manual-info {
        margin-top: 14px;
        padding: 12px 15px;
        background: #f6f4ff;
        border: 1px solid #e4defe;
        border-radius: 9px;
        color: #5b52a3;
        font-size: 12px;
    }

    @media (max-width: 768px) {

        .fee-page {
            padding: 15px;
        }

        .page-title {
            font-size: 22px;
        }

        .card-body-custom {
            padding: 17px;
        }

        .bottom-actions {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
            text-align: center;
        }
    }
</style>


<div class="fee-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>

            <h1 class="page-title">
                Create Fee Structure
            </h1>

            <p class="page-subtitle">
                Create a fee structure for an academic year, class and section.
            </p>

        </div>

        <a
            href="{{ route('admin.fees.fee-structures.index') }}"
            class="back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Back to Fee Structures
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())

        <div class="alert-danger-custom">

            <strong>
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </strong>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('admin.fees.fee-structures.store') }}"
        method="POST"
        id="feeStructureForm"
    >

        @csrf


        {{-- BASIC INFORMATION --}}
        <div class="form-card">

            <div class="card-header-custom">

                <h5>
                    <i class="bi bi-building me-2"></i>
                    Basic Information
                </h5>

                <p>
                    Select the academic year, class and section.
                </p>

            </div>


            <div class="card-body-custom">

                <div class="row g-4">


                    {{-- ACADEMIC YEAR --}}
                    <div class="col-md-6">

                        <label
                            for="academic_year"
                            class="form-label"
                        >
                            Academic Year
                            <span class="required">*</span>
                        </label>

                        <select
                            name="academic_year"
                            id="academic_year"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears ?? [] as $year)

                                <option
                                    value="{{ $year }}"
                                    {{ old('academic_year') == $year ? 'selected' : '' }}
                                >
                                    {{ $year }}
                                </option>

                            @endforeach

                        </select>

                        <div class="field-help">
                            Academic years are taken from existing student data.
                        </div>

                        @error('academic_year')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- CLASS --}}
                    <div class="col-md-6">

                        <label
                            for="class_id"
                            class="form-label"
                        >
                            Class
                            <span class="required">*</span>
                        </label>

                        <select
                            name="class_id"
                            id="class_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes ?? [] as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}
                                >
                                    {{ $class->class_name }}
                                </option>

                            @endforeach

                        </select>

                        <div class="field-help">
                            Select a class to load its sections.
                        </div>

                        @error('class_id')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SECTION --}}
                    <div class="col-md-6">

                        <label
                            for="section_id"
                            class="form-label"
                        >
                            Section
                        </label>

                        <select
                            name="section_id"
                            id="section_id"
                            class="form-select"
                            disabled
                        >

                            <option value="">
                                Select Section
                            </option>

                        </select>

                        <div
                            class="field-help"
                            id="sectionHelp"
                        >
                            Select a class first.
                        </div>

                        @error('section_id')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STRUCTURE NAME --}}
                    <div class="col-md-6">

                        <label
                            for="structure_name"
                            class="form-label"
                        >
                            Structure Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="structure_name"
                            id="structure_name"
                            class="form-control"
                            value="{{ old('structure_name') }}"
                            placeholder="Example: 2026-27 Class 5 Fee Structure"
                            required
                        >

                        @error('structure_name')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-6">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                            <span class="required">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Active"
                                {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status') == 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- FEE ITEMS --}}
        <div class="form-card">

            <div class="card-header-custom">

                <h5>
                    <i class="bi bi-cash-stack me-2"></i>
                    Fee Items
                </h5>

                <p>
                    Select an existing fee type or create a manual fee.
                </p>

            </div>


            <div class="card-body-custom">

                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                    <div>

                        <strong class="text-dark">
                            Fee Components
                        </strong>

                        <div class="field-help">
                            You can select an existing fee type or choose Manual / Custom Fee.
                        </div>

                    </div>

                    <button
                        type="button"
                        class="add-row-btn"
                        id="addFeeRow"
                    >
                        <i class="bi bi-plus-circle"></i>
                        Add Fee
                    </button>

                </div>


                <div class="fee-table-wrapper">

                    <table class="fee-table">

                        <thead>

                            <tr>

                                <th style="width:34%;">
                                    Fee Type
                                </th>

                                <th style="width:20%;">
                                    Amount
                                </th>

                                <th style="width:22%;">
                                    Due Date
                                </th>

                                <th style="width:12%; text-align:center;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody id="feeItemsBody">

                            @if(old('fee_type_id'))

                                @foreach(old('fee_type_id') as $index => $oldFeeType)

                                    <tr class="fee-row">

                                        <td>

                                            <select
                                                name="fee_type_id[]"
                                                class="form-select fee-type-select"
                                                required
                                            >

                                                <option value="">
                                                    Select Fee Type
                                                </option>

                                                @foreach($feeTypes ?? [] as $feeType)

                                                    <option
                                                        value="{{ $feeType->id }}"
                                                        data-amount="{{ $feeType->amount }}"
                                                        {{ (string) $oldFeeType === (string) $feeType->id ? 'selected' : '' }}
                                                    >
                                                        {{ $feeType->name }}
                                                    </option>

                                                @endforeach

                                                <option
                                                    value="manual"
                                                    {{ (string) $oldFeeType === 'manual' ? 'selected' : '' }}
                                                >
                                                    + Manual / Custom Fee
                                                </option>

                                            </select>


                                            <div class="manual-label">
                                                Custom Fee Name
                                            </div>

                                            <input
                                                type="text"
                                                name="manual_fee_name[]"
                                                class="form-control manual-fee-name"
                                                value="{{ old('manual_fee_name.' . $index) }}"
                                                placeholder="Enter custom fee name"
                                            >

                                        </td>


                                        <td>

                                            <input
                                                type="number"
                                                name="amount[]"
                                                class="form-control amount-input"
                                                value="{{ old('amount.' . $index) }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                required
                                            >

                                        </td>


                                        <td>

                                            <input
                                                type="date"
                                                name="due_date[]"
                                                class="form-control"
                                                value="{{ old('due_date.' . $index) }}"
                                            >

                                        </td>


                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="remove-row-btn remove-fee-row"
                                                title="Remove"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </td>

                                    </tr>

                                @endforeach

                            @else

                                <tr class="fee-row">

                                    <td>

                                        <select
                                            name="fee_type_id[]"
                                            class="form-select fee-type-select"
                                            required
                                        >

                                            <option value="">
                                                Select Fee Type
                                            </option>

                                            @foreach($feeTypes ?? [] as $feeType)

                                                <option
                                                    value="{{ $feeType->id }}"
                                                    data-amount="{{ $feeType->amount }}"
                                                >
                                                    {{ $feeType->name }}
                                                </option>

                                            @endforeach

                                            <option value="manual">
                                                + Manual / Custom Fee
                                            </option>

                                        </select>


                                        <div class="manual-label">
                                            Custom Fee Name
                                        </div>

                                        <input
                                            type="text"
                                            name="manual_fee_name[]"
                                            class="form-control manual-fee-name"
                                            placeholder="Enter custom fee name"
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="amount[]"
                                            class="form-control amount-input"
                                            min="0"
                                            step="0.01"
                                            placeholder="0.00"
                                            required
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="date"
                                            name="due_date[]"
                                            class="form-control"
                                        >

                                    </td>


                                    <td class="text-center">

                                        <button
                                            type="button"
                                            class="remove-row-btn remove-fee-row"
                                            title="Remove"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>


                <div class="manual-info">

                    <i class="bi bi-info-circle me-1"></i>

                    <strong>Manual Fee:</strong>

                    Choose
                    <strong>+ Manual / Custom Fee</strong>
                    when the fee is not available in the existing Fee Type list.

                    Enter its name and amount manually.

                </div>


                <div class="total-box">

                    <span class="total-label">
                        Total Fee Amount
                    </span>

                    <span class="total-amount">
                        &#8377;
                        <span id="totalAmount">
                            0.00
                        </span>
                    </span>

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="bottom-actions">

            <a
                href="{{ route('admin.fees.fee-structures.index') }}"
                class="btn-cancel"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn-save"
            >
                <i class="bi bi-check-circle me-1"></i>
                Save Fee Structure
            </button>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const sectionHelp = document.getElementById('sectionHelp');

    const feeItemsBody = document.getElementById('feeItemsBody');
    const addFeeRow = document.getElementById('addFeeRow');
    const totalAmount = document.getElementById('totalAmount');

    /*
    |--------------------------------------------------------------------------
    | SECTION LOADING
    |--------------------------------------------------------------------------
    */

    function loadSections(classId, selectedSection = '') {

        sectionSelect.disabled = true;

        sectionSelect.innerHTML =
            '<option value="">Loading sections...</option>';

        if (sectionHelp) {
            sectionHelp.textContent = 'Loading sections...';
        }

        if (!classId) {

            sectionSelect.innerHTML =
                '<option value="">Select Section</option>';

            sectionSelect.disabled = true;

            if (sectionHelp) {
                sectionHelp.textContent = 'Select a class first.';
            }

            return;
        }

        const url =
            "{{ route('admin.fees.fee-structures.sections', ['classId' => '__CLASS_ID__']) }}"
                .replace(
                    '__CLASS_ID__',
                    encodeURIComponent(classId)
                );

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            cache: 'no-store'
        })
        .then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'HTTP Error: ' + response.status
                );
            }

            return response.json();
        })
        .then(function (sections) {

            sectionSelect.innerHTML =
                '<option value="">All Sections</option>';

            if (!Array.isArray(sections) || sections.length === 0) {

                sectionSelect.innerHTML =
                    '<option value="">No sections available</option>';

                sectionSelect.disabled = false;

                if (sectionHelp) {
                    sectionHelp.textContent =
                        'No sections found for this class.';
                }

                return;
            }

            sections.forEach(function (section) {

                const option =
                    document.createElement('option');

                option.value = section.id;
                option.textContent = section.section_name;

                if (
                    selectedSection &&
                    String(section.id) ===
                    String(selectedSection)
                ) {
                    option.selected = true;
                }

                sectionSelect.appendChild(option);
            });

            sectionSelect.disabled = false;

            if (sectionHelp) {
                sectionHelp.textContent =
                    sections.length +
                    ' section(s) available.';
            }
        })
        .catch(function (error) {

            console.error(
                'Section loading error:',
                error
            );

            sectionSelect.innerHTML =
                '<option value="">Unable to load sections</option>';

            sectionSelect.disabled = false;

            if (sectionHelp) {
                sectionHelp.textContent =
                    'Unable to load sections.';
            }
        });
    }


    if (classSelect) {

        classSelect.addEventListener(
            'change',
            function () {

                loadSections(this.value);

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE OLD CLASS / SECTION
    |--------------------------------------------------------------------------
    */

    const oldClass = @json(old('class_id'));
    const oldSection = @json(old('section_id'));

    if (oldClass) {

        classSelect.value = oldClass;

        loadSections(
            oldClass,
            oldSection
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateTotal() {

        let total = 0;

        document
            .querySelectorAll('.amount-input')
            .forEach(function (input) {

                const amount =
                    parseFloat(input.value) || 0;

                total += amount;
            });

        if (totalAmount) {

            totalAmount.textContent =
                total.toFixed(2);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW / HIDE MANUAL FEE FIELD
    |--------------------------------------------------------------------------
    */

    function handleFeeTypeChange(select) {

        const row =
            select.closest('.fee-row');

        if (!row) {
            return;
        }

        const manualInput =
            row.querySelector('.manual-fee-name');

        const manualLabel =
            row.querySelector('.manual-label');

        const amountInput =
            row.querySelector('.amount-input');

        if (!manualInput || !amountInput) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | MANUAL / CUSTOM FEE
        |--------------------------------------------------------------------------
        */

        if (select.value === 'manual') {

            manualLabel.style.display = 'block';

            manualInput.style.display = 'block';

            manualInput.required = true;

            amountInput.value = '';

            manualInput.focus();

        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL FEE TYPE
        |--------------------------------------------------------------------------
        */

        else {

            manualLabel.style.display = 'none';

            manualInput.style.display = 'none';

            manualInput.required = false;

            manualInput.classList.remove('is-invalid');

            manualInput.value = '';


            const selectedOption =
                select.options[
                    select.selectedIndex
                ];


            if (
                selectedOption &&
                selectedOption.dataset.amount !== undefined &&
                selectedOption.dataset.amount !== ''
            ) {

                amountInput.value =
                    selectedOption.dataset.amount;

            } else {

                amountInput.value = '';
            }
        }


        calculateTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE ROW
    |--------------------------------------------------------------------------
    */

    function initializeRow(row) {

        const select =
            row.querySelector('.fee-type-select');

        const amountInput =
            row.querySelector('.amount-input');

        const manualInput =
            row.querySelector('.manual-fee-name');

        const manualLabel =
            row.querySelector('.manual-label');


        if (select) {

            /*
            | Use change event
            */

            select.addEventListener(
                'change',
                function () {

                    handleFeeTypeChange(this);

                }
            );


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Initialize the current state immediately.
            |--------------------------------------------------------------------------
            */

            if (select.value === 'manual') {

                if (manualLabel) {
                    manualLabel.style.display = 'block';
                }

                if (manualInput) {

                    manualInput.style.display = 'block';

                    manualInput.required = true;
                }

            } else {

                if (manualLabel) {
                    manualLabel.style.display = 'none';
                }

                if (manualInput) {

                    manualInput.style.display = 'none';

                    manualInput.required = false;
                }
            }
        }


        if (amountInput) {

            amountInput.addEventListener(
                'input',
                calculateTotal
            );
        }


        if (manualInput) {

            manualInput.addEventListener(
                'input',
                function () {

                    manualInput.classList.remove(
                        'is-invalid'
                    );

                }
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE EXISTING ROWS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.fee-row')
        .forEach(function (row) {

            initializeRow(row);

        });


    /*
    |--------------------------------------------------------------------------
    | ADD NEW FEE ROW
    |--------------------------------------------------------------------------
    */

    if (addFeeRow && feeItemsBody) {

        addFeeRow.addEventListener(
            'click',
            function () {

                const row =
                    document.createElement('tr');

                row.className =
                    'fee-row';


                row.innerHTML = `
                    <td>

                        <select
                            name="fee_type_id[]"
                            class="form-select fee-type-select"
                            required
                        >

                            <option value="">
                                Select Fee Type
                            </option>

                            @foreach($feeTypes ?? [] as $feeType)

                                <option
                                    value="{{ $feeType->id }}"
                                    data-amount="{{ $feeType->amount }}"
                                >
                                    {{ $feeType->name }}
                                </option>

                            @endforeach

                            <option value="manual">
                                + Manual / Custom Fee
                            </option>

                        </select>

                        <div class="manual-label">
                            Custom Fee Name
                        </div>

                        <input
                            type="text"
                            name="manual_fee_name[]"
                            class="form-control manual-fee-name"
                            placeholder="Enter custom fee name"
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            name="amount[]"
                            class="form-control amount-input"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                    </td>


                    <td>

                        <input
                            type="date"
                            name="due_date[]"
                            class="form-control"
                        >

                    </td>


                    <td class="text-center">

                        <button
                            type="button"
                            class="remove-row-btn remove-fee-row"
                            title="Remove"
                        >
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>
                `;


                feeItemsBody.appendChild(row);

                initializeRow(row);

                calculateTotal();

            }
        );


    /*
    |--------------------------------------------------------------------------
    | REMOVE FEE ROW
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            const removeButton =
                event.target.closest(
                    '.remove-fee-row'
                );

            if (!removeButton) {
                return;
            }

            const rows =
                document.querySelectorAll(
                    '.fee-row'
                );

            if (rows.length <= 1) {

                alert(
                    'At least one fee component is required.'
                );

                return;
            }

            const row =
                removeButton.closest('.fee-row');

            if (row) {
                row.remove();
            }

            calculateTotal();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL TOTAL
    |--------------------------------------------------------------------------
    */

    calculateTotal();


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById(
            'feeStructureForm'
        );


    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                let valid = true;


                document
                    .querySelectorAll('.fee-row')
                    .forEach(function (row) {

                        const select =
                            row.querySelector(
                                '.fee-type-select'
                            );

                        const manualInput =
                            row.querySelector(
                                '.manual-fee-name'
                            );

                        const amountInput =
                            row.querySelector(
                                '.amount-input'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | MANUAL NAME VALIDATION
                        |--------------------------------------------------------------------------
                        */

                        if (
                            select &&
                            select.value === 'manual'
                        ) {

                            if (
                                !manualInput ||
                                manualInput.value.trim() === ''
                            ) {

                                valid = false;

                                if (manualInput) {

                                    manualInput.classList.add(
                                        'is-invalid'
                                    );

                                    manualInput.focus();
                                }

                            } else {

                                manualInput.classList.remove(
                                    'is-invalid'
                                );
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | AMOUNT VALIDATION
                        |--------------------------------------------------------------------------
                        */

                        if (
                            amountInput &&
                            (
                                amountInput.value === '' ||
                                parseFloat(amountInput.value) < 0
                            )
                        ) {

                            valid = false;

                            amountInput.classList.add(
                                'is-invalid'
                            );

                        } else if (amountInput) {

                            amountInput.classList.remove(
                                'is-invalid'
                            );
                        }

                    });


                if (!valid) {

                    event.preventDefault();

                    alert(
                        'Please complete all fee details correctly.'
                    );
                }

            }
        );
    }

});
</script>

@endsection
