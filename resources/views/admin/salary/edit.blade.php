@extends('layouts.app')

@section('title', 'Edit Salary')

@section('content')

<style>
    .salary-edit-page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
        min-height: calc(100vh - 70px);
    }

    /* HEADER */

    .salary-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .salary-header-left {
        min-width: 0;
    }

    .salary-edit-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #172033;
    }

    .salary-edit-subtitle {
        margin: 6px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .salary-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        padding: 11px 17px;

        background: #ffffff;
        color: #475467;

        border: 1px solid #d9e0e8;
        border-radius: 9px;

        font-size: 13px;
        font-weight: 700;

        text-decoration: none;
        white-space: nowrap;

        box-shadow: 0 3px 10px rgba(15, 23, 42, .05);

        transition: .2s ease;
    }

    .salary-back-btn i {
        color: #147cf5;
    }

    .salary-back-btn:hover {
        background: #f7faff;
        color: #147cf5;
        border-color: #bcd7f7;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(20, 124, 245, .10);
    }

    /* CARD */

    .salary-form-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .salary-section {
        padding: 24px;
        border-bottom: 1px solid #edf1f5;
    }

    .salary-section:last-child {
        border-bottom: 0;
    }

    .salary-section-title {
        margin: 0 0 18px;
        font-size: 17px;
        font-weight: 800;
        color: #172033;
    }

    .salary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .salary-field {
        display: flex;
        flex-direction: column;
    }

    .salary-field.full {
        grid-column: 1 / -1;
    }

    .salary-label {
        margin-bottom: 7px;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
    }

    .salary-label span {
        color: #e94d47;
    }

    .salary-input,
    .salary-select,
    .salary-textarea {
        width: 100%;
        box-sizing: border-box;

        border: 1px solid #d9e0e8;
        border-radius: 9px;

        padding: 11px 13px;

        background: #fff;
        color: #172033;

        font-size: 13px;
        outline: none;

        transition: .2s ease;
    }

    .salary-input:focus,
    .salary-select:focus,
    .salary-textarea:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .10);
    }

    .salary-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .salary-error {
        margin-top: 5px;
        color: #e94d47;
        font-size: 12px;
    }

    .salary-help {
        margin-top: 5px;
        color: #98a2b3;
        font-size: 11px;
    }

    /* CALCULATION */

    .salary-calculation {
        margin-top: 20px;
        padding: 18px;

        border-radius: 12px;

        background: #f7faff;
        border: 1px solid #e4efff;
    }

    .salary-calculation-title {
        margin: 0 0 14px;

        color: #147cf5;
        font-size: 14px;
        font-weight: 800;
    }

    .salary-calculation-row {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 8px 0;

        color: #475467;
        font-size: 13px;
    }

    .salary-calculation-row.total {
        margin-top: 8px;
        padding-top: 14px;

        border-top: 1px solid #dfe9f5;

        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .salary-net-preview {
        color: #147cf5;
        font-size: 20px;
        font-weight: 800;
    }

    /* ACTIONS */

    .salary-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;

        padding: 20px 24px;

        background: #fbfcfe;
        border-top: 1px solid #edf1f5;
    }

    .salary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        min-width: 110px;

        padding: 11px 18px;

        border-radius: 9px;

        font-size: 13px;
        font-weight: 750;

        text-decoration: none;

        border: 0;
        cursor: pointer;

        transition: .2s ease;
    }

    .salary-cancel {
        background: #eef2f6;
        color: #475467;
    }

    .salary-cancel:hover {
        background: #e3e8ee;
        color: #344054;
    }

    .salary-update {
        background: #147cf5;
        color: #fff;

        box-shadow: 0 5px 14px rgba(20, 124, 245, .20);
    }

    .salary-update:hover {
        background: #1268ca;
    }

    .salary-update:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    /* RESPONSIVE */

    @media (max-width: 700px) {

        .salary-edit-page {
            padding: 18px;
        }

        .salary-edit-header {
            align-items: stretch;
            flex-direction: column;
        }

        .salary-back-btn {
            width: 100%;
        }

        .salary-grid {
            grid-template-columns: 1fr;
        }

        .salary-field.full {
            grid-column: auto;
        }

        .salary-actions {
            flex-direction: column;
        }

        .salary-btn {
            width: 100%;
        }
    }
</style>


<div class="salary-edit-page">

    {{-- Header --}}

    <div class="salary-edit-header">

        <div class="salary-header-left">

            <h1 class="salary-edit-title">
                Edit Salary
            </h1>

            <p class="salary-edit-subtitle">
                Update teacher salary and payment information
            </p>

        </div>


        {{-- Back to Salary List --}}

        <a
            href="{{ route('admin.teachers.salary.index') }}"
            class="salary-back-btn"
        >
            <i class="fas fa-arrow-left"></i>
            Back to Salary List
        </a>

    </div>


    <form
        action="{{ route('admin.teachers.salary.update', $teacherSalary->id) }}"
        method="POST"
        id="salaryEditForm"
    >

        @csrf
        @method('PUT')


        <div class="salary-form-card">

            {{-- Salary Information --}}

            <div class="salary-section">

                <h2 class="salary-section-title">
                    Salary Information
                </h2>


                <div class="salary-grid">

                    {{-- Teacher --}}

                    <div class="salary-field">

                        <label class="salary-label">
                            Teacher <span>*</span>
                        </label>

                        <select
                            name="teacher_id"
                            class="salary-select"
                            required
                        >

                            <option value="">
                                Select Teacher
                            </option>

                            @foreach($teachers as $teacher)

                                <option
                                    value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $teacherSalary->teacher_id) == $teacher->id ? 'selected' : '' }}
                                >

                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}

                                    @if($teacher->teacher_id)
                                        — {{ $teacher->teacher_id }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('teacher_id')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Salary Month --}}

                    <div class="salary-field">

                        <label class="salary-label">
                            Salary Month <span>*</span>
                        </label>

                        <input
                            type="month"
                            name="salary_month"
                            class="salary-input"
                            value="{{ old('salary_month', $teacherSalary->salary_month) }}"
                            required
                        >

                        @error('salary_month')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- Salary Details --}}

            <div class="salary-section">

                <h2 class="salary-section-title">
                    Salary Details
                </h2>


                <div class="salary-grid">

                    {{-- Basic Salary --}}

                    <div class="salary-field">

                        <label class="salary-label">
                            Basic Salary <span>*</span>
                        </label>

                        <input
                            type="number"
                            name="basic_salary"
                            id="basic_salary"
                            class="salary-input"
                            value="{{ old('basic_salary', $teacherSalary->basic_salary) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                        @error('basic_salary')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Allowances --}}

                    <div class="salary-field">

                        <label class="salary-label">
                            Allowances
                        </label>

                        <input
                            type="number"
                            name="allowances"
                            id="allowances"
                            class="salary-input"
                            value="{{ old('allowances', $teacherSalary->allowances) }}"
                            min="0"
                            step="0.01"
                        >

                        <div class="salary-help">
                            Example: HRA, travel allowance, etc.
                        </div>

                        @error('allowances')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Deductions --}}

                    <div class="salary-field">

                        <label class="salary-label">
                            Deductions
                        </label>

                        <input
                            type="number"
                            name="deductions"
                            id="deductions"
                            class="salary-input"
                            value="{{ old('deductions', $teacherSalary->deductions) }}"
                            min="0"
                            step="0.01"
                        >

                        <div class="salary-help">
                            Example: leave deduction, other deductions, etc.
                        </div>

                        @error('deductions')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Payment Status --}}

                    <div class="salary-field">

                        <label class="salary-label">
                            Payment Status <span>*</span>
                        </label>

                        <select
                            name="payment_status"
                            id="payment_status"
                            class="salary-select"
                            required
                        >

                            <option
                                value="Pending"
                                {{ old('payment_status', $teacherSalary->payment_status) === 'Pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="Paid"
                                {{ old('payment_status', $teacherSalary->payment_status) === 'Paid' ? 'selected' : '' }}
                            >
                                Paid
                            </option>

                        </select>

                        @error('payment_status')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Payment Date --}}

                    <div class="salary-field">

                        <label class="salary-label">
                            Payment Date
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            id="payment_date"
                            class="salary-input"
                            value="{{ old(
                                'payment_date',
                                $teacherSalary->payment_date
                                    ? $teacherSalary->payment_date->format('Y-m-d')
                                    : ''
                            ) }}"
                        >

                        @error('payment_date')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Calculation --}}

                <div class="salary-calculation">

                    <h3 class="salary-calculation-title">
                        Salary Calculation
                    </h3>


                    <div class="salary-calculation-row">

                        <span>
                            Basic Salary
                        </span>

                        <strong id="previewBasic">
                            ₹0.00
                        </strong>

                    </div>


                    <div class="salary-calculation-row">

                        <span>
                            + Allowances
                        </span>

                        <strong id="previewAllowances">
                            ₹0.00
                        </strong>

                    </div>


                    <div class="salary-calculation-row">

                        <span>
                            - Deductions
                        </span>

                        <strong id="previewDeductions">
                            ₹0.00
                        </strong>

                    </div>


                    <div class="salary-calculation-row total">

                        <span>
                            Net Salary
                        </span>

                        <strong
                            class="salary-net-preview"
                            id="previewNet"
                        >
                            ₹0.00
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Remarks --}}

            <div class="salary-section">

                <h2 class="salary-section-title">
                    Remarks
                </h2>

                <div class="salary-field full">

                    <label class="salary-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        class="salary-textarea"
                        placeholder="Enter any additional remarks..."
                    >{{ old('remarks', $teacherSalary->remarks) }}</textarea>

                    @error('remarks')

                        <div class="salary-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- Buttons --}}

            <div class="salary-actions">

                <a
                    href="{{ route('admin.teachers.salary.index') }}"
                    class="salary-btn salary-cancel"
                >
                    <i class="fas fa-times"></i>
                    Cancel
                </a>


                <button
                    type="submit"
                    class="salary-btn salary-update"
                    id="updateSalaryBtn"
                >
                    <i class="fas fa-save"></i>
                    Update Salary
                </button>

            </div>

        </div>

    </form>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const basicInput =
            document.getElementById('basic_salary');

        const allowancesInput =
            document.getElementById('allowances');

        const deductionsInput =
            document.getElementById('deductions');

        const previewBasic =
            document.getElementById('previewBasic');

        const previewAllowances =
            document.getElementById('previewAllowances');

        const previewDeductions =
            document.getElementById('previewDeductions');

        const previewNet =
            document.getElementById('previewNet');


        function numberValue(input) {

            const value = parseFloat(input.value);

            return isNaN(value) ? 0 : value;

        }


        function money(value) {

            return '₹' + value.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        }


        function calculateSalary() {

            const basic =
                numberValue(basicInput);

            const allowances =
                numberValue(allowancesInput);

            const deductions =
                numberValue(deductionsInput);

            const net =
                basic + allowances - deductions;


            previewBasic.textContent =
                money(basic);

            previewAllowances.textContent =
                money(allowances);

            previewDeductions.textContent =
                money(deductions);

            previewNet.textContent =
                money(net);

        }


        basicInput.addEventListener(
            'input',
            calculateSalary
        );

        allowancesInput.addEventListener(
            'input',
            calculateSalary
        );

        deductionsInput.addEventListener(
            'input',
            calculateSalary
        );


        calculateSalary();


        /* PAYMENT DATE */

        const paymentStatus =
            document.getElementById('payment_status');

        const paymentDate =
            document.getElementById('payment_date');


        function updatePaymentDate() {

            if (paymentStatus.value === 'Paid') {

                paymentDate.required = true;

            } else {

                paymentDate.required = false;

            }

        }


        paymentStatus.addEventListener(
            'change',
            updatePaymentDate
        );

        updatePaymentDate();


        /* UPDATE BUTTON */

        const salaryForm =
            document.getElementById('salaryEditForm');

        const updateButton =
            document.getElementById('updateSalaryBtn');


        salaryForm.addEventListener(
            'submit',
            function () {

                updateButton.disabled = true;

                updateButton.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Updating...';

            }
        );

    });
</script>

@endsection