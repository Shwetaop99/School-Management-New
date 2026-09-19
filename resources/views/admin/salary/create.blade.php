

@extends('layouts.app')

@section('title', 'Generate Salary')
@section('page-title', 'Generate Salary')

@section('content')

<style>
    /* ================================
       PAGE
    ================================= */
    .salary-create-page {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 60px);
    }

    /* ================================
       HEADER
    ================================= */
    .salary-create-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .salary-header-left {
        min-width: 0;
    }

    .salary-create-title {
        margin: 0;
        color: #172033;
        font-size: 28px;
        font-weight: 800;
    }

    .salary-create-subtitle {
        margin: 7px 0 0;
        color: #667085;
        font-size: 14px;
    }

    .salary-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 17px;
        background: #fff;
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
    }

    /* ================================
       MAIN CARD
    ================================= */
    .salary-form-card {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(15, 23, 42, .06);
    }

    /* ================================
       CARD HEADER
    ================================= */
    .salary-card-top {
        padding: 22px 25px;
        background: linear-gradient(135deg, #1769d1, #237de0);
        color: #fff;
    }

    .salary-card-top-inner {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .salary-card-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .18);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 12px;
        font-size: 20px;
    }

    .salary-card-heading {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
    }

    .salary-card-description {
        margin: 4px 0 0;
        color: rgba(255, 255, 255, .85);
        font-size: 13px;
    }

    /* ================================
       FORM BODY
    ================================= */
    .salary-form-body {
        padding: 28px;
    }

    .salary-section {
        margin-bottom: 30px;
        padding-bottom: 26px;
        border-bottom: 1px solid #edf0f4;
    }

    .salary-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .salary-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0 0 18px;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .salary-section-title i {
        color: #147cf5;
        font-size: 15px;
    }

    /* ================================
       GRID
    ================================= */
    .salary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .salary-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .salary-field {
        display: flex;
        flex-direction: column;
    }

    .salary-field-full {
        grid-column: 1 / -1;
    }

    .salary-label {
        margin-bottom: 8px;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
    }

    .salary-label span {
        color: #ef4444;
    }

    /* ================================
       INPUTS
    ================================= */
    .salary-input-wrapper {
        position: relative;
    }

    .salary-input-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #7b8798;
        font-size: 14px;
        pointer-events: none;
    }

    .salary-input,
    .salary-select,
    .salary-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d9e0e8;
        border-radius: 9px;
        background: #fff;
        color: #172033;
        font-size: 14px;
        outline: none;
        transition: .2s ease;
    }

    .salary-input,
    .salary-select {
        height: 45px;
        padding: 0 13px;
    }

    .salary-input.has-icon,
    .salary-select.has-icon {
        padding-left: 40px;
    }

    .salary-textarea {
        min-height: 105px;
        padding: 12px 13px;
        resize: vertical;
    }

    .salary-input:focus,
    .salary-select:focus,
    .salary-textarea:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .10);
    }

    .salary-input::placeholder,
    .salary-textarea::placeholder {
        color: #98a2b3;
    }

    /* ================================
       CALCULATION
    ================================= */
    .salary-calculation {
        margin-top: 22px;
        padding: 20px;
        background: linear-gradient(135deg, #f5f9ff, #f6f5ff);
        border: 1px solid #dce7fb;
        border-radius: 13px;
    }

    .salary-calculation-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        color: #344054;
        font-size: 14px;
        font-weight: 800;
    }

    .salary-calculation-title i {
        color: #6c63ff;
    }

    .salary-calculation-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .salary-calculation-item {
        padding: 14px;
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 10px;
    }

    .salary-calculation-label {
        color: #667085;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .salary-calculation-value {
        margin-top: 6px;
        color: #172033;
        font-size: 18px;
        font-weight: 800;
    }

    .salary-net {
        background: #f2fbf6;
        border-color: #b9e4cd;
    }

    .salary-net .salary-calculation-value {
        color: #16a34a;
    }

    /* ================================
       PAYMENT STATUS
    ================================= */
    .payment-status-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .payment-option {
        position: relative;
    }

    .payment-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-option label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 15px;
        background: #fff;
        border: 1px solid #d9e0e8;
        border-radius: 8px;
        color: #475467;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .payment-option label:hover {
        border-color: #147cf5;
    }

    .payment-option input:checked + label {
        border-color: #147cf5;
        background: #eef6ff;
        color: #147cf5;
    }

    /* ================================
       ERRORS
    ================================= */
    .salary-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
    }

    /* ================================
       ACTION BUTTONS
    ================================= */
    .salary-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid #edf0f4;
    }

    .salary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 43px;
        padding: 0 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .salary-btn-cancel {
        background: #fff;
        color: #667085;
        border: 1px solid #d9e0e8;
    }

    .salary-btn-cancel:hover {
        background: #f8fafc;
        color: #344054;
    }

    .salary-btn-save {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        border: none;
        box-shadow: 0 6px 15px rgba(20, 124, 245, .18);
    }

    .salary-btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(20, 124, 245, .25);
    }

    /* ================================
       RESPONSIVE
    ================================= */
    @media (max-width: 900px) {
        .salary-grid-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .salary-calculation-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .salary-create-page {
            padding: 16px;
        }

        .salary-create-header {
            flex-direction: column;
            align-items: stretch;
        }

        .salary-back-btn {
            width: 100%;
        }

        .salary-grid,
        .salary-grid-3 {
            grid-template-columns: 1fr;
        }

        .salary-form-body {
            padding: 20px;
        }

        .salary-actions {
            flex-direction: column-reverse;
        }

        .salary-btn {
            width: 100%;
        }
    }

    @media (max-width: 450px) {
        .salary-calculation-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="salary-create-page">

    {{-- PAGE HEADER --}}
    <div class="salary-create-header">

        <div class="salary-header-left">
            <h1 class="salary-create-title">
                Generate Salary
            </h1>

            <p class="salary-create-subtitle">
                Create a salary record for a teacher
            </p>
        </div>

        <a
            href="{{ route('admin.teachers.salary.index') }}"
            class="salary-back-btn"
        >
            <i class="fas fa-arrow-left"></i>
            Back to Salary List
        </a>

    </div>

    {{-- MAIN CARD --}}
    <div class="salary-form-card">

        {{-- CARD HEADER --}}
        <div class="salary-card-top">

            <div class="salary-card-top-inner">

                <div class="salary-card-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>

                <div>
                    <h2 class="salary-card-heading">
                        Salary Information
                    </h2>

                    <p class="salary-card-description">
                        Enter salary and payment details below.
                    </p>
                </div>

            </div>

        </div>

        {{-- FORM BODY --}}
        <div class="salary-form-body">

            <form
                action="{{ route('admin.teachers.salary.store') }}"
                method="POST"
            >

                @csrf

                {{-- ============================
                     TEACHER INFORMATION
                ============================= --}}
                <div class="salary-section">

                    <h3 class="salary-section-title">
                        <i class="fas fa-user-tie"></i>
                        Teacher Information
                    </h3>

                    <div class="salary-grid">

                        {{-- TEACHER --}}
                        <div class="salary-field">

                            <label class="salary-label">
                                Teacher <span>*</span>
                            </label>

                            <div class="salary-input-wrapper">

                                <i class="fas fa-user salary-input-icon"></i>

                                <select
                                    name="teacher_id"
                                    id="teacher_id"
                                    class="salary-select has-icon"
                                    required
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

                                            @if($teacher->teacher_id)
                                                - {{ $teacher->teacher_id }}
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            @error('teacher_id')
                                <div class="salary-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- SALARY MONTH --}}
                        <div class="salary-field">

                            <label class="salary-label">
                                Salary Month <span>*</span>
                            </label>

                            <div class="salary-input-wrapper">

                                <i class="fas fa-calendar-alt salary-input-icon"></i>

                                <input
                                    type="month"
                                    name="salary_month"
                                    id="salary_month"
                                    value="{{ old('salary_month', now()->format('Y-m')) }}"
                                    class="salary-input has-icon"
                                    required
                                >

                            </div>

                            @error('salary_month')
                                <div class="salary-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

                {{-- ============================
                     SALARY DETAILS
                ============================= --}}
                <div class="salary-section">

                    <h3 class="salary-section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Salary Details
                    </h3>

                    <div class="salary-grid-3">

                        {{-- BASIC SALARY --}}
                        <div class="salary-field">

                            <label class="salary-label">
                                Basic Salary <span>*</span>
                            </label>

                            <div class="salary-input-wrapper">

                                <i class="fas fa-rupee-sign salary-input-icon"></i>

                                <input
                                    type="number"
                                    name="basic_salary"
                                    id="basic_salary"
                                    value="{{ old('basic_salary') }}"
                                    class="salary-input has-icon"
                                    placeholder="0.00"
                                    min="0"
                                    step="0.01"
                                    required
                                >

                            </div>

                            @error('basic_salary')
                                <div class="salary-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ALLOWANCES --}}
                        <div class="salary-field">

                            <label class="salary-label">
                                Allowances
                            </label>

                            <div class="salary-input-wrapper">

                                <i class="fas fa-plus-circle salary-input-icon"></i>

                                <input
                                    type="number"
                                    name="allowances"
                                    id="allowances"
                                    value="{{ old('allowances', 0) }}"
                                    class="salary-input has-icon"
                                    placeholder="0.00"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                            @error('allowances')
                                <div class="salary-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- DEDUCTION RATE --}}
                        <div class="salary-field">

                            <label class="salary-label">
                                Deduction Rate (%)
                            </label>

                            <div class="salary-input-wrapper">

                                <i class="fas fa-percent salary-input-icon"></i>

                                <input
                                    type="number"
                                    name="deduction_rate"
                                    id="deduction_rate"
                                    value="{{ old('deduction_rate', 0) }}"
                                    class="salary-input has-icon"
                                    placeholder="0.00"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                >

                            </div>

                            @error('deduction_rate')
                                <div class="salary-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    {{-- SALARY CALCULATION --}}
                    <div class="salary-calculation">

                        <div class="salary-calculation-title">
                            <i class="fas fa-calculator"></i>
                            Salary Calculation
                        </div>

                        <div class="salary-calculation-grid">

                            <div class="salary-calculation-item">

                                <div class="salary-calculation-label">
                                    Basic
                                </div>

                                <div
                                    class="salary-calculation-value"
                                    id="display-basic"
                                >
                                    ₹0.00
                                </div>

                            </div>

                            <div class="salary-calculation-item">

                                <div class="salary-calculation-label">
                                    Allowances
                                </div>

                                <div
                                    class="salary-calculation-value"
                                    id="display-allowances"
                                >
                                    ₹0.00
                                </div>

                            </div>

                            <div class="salary-calculation-item">

                                <div class="salary-calculation-label">
                                    Deductions
                                </div>

                                <div
                                    class="salary-calculation-value"
                                    id="display-deductions"
                                >
                                    ₹0.00
                                </div>

                            </div>

                            <div class="salary-calculation-item salary-net">

                                <div class="salary-calculation-label">
                                    Net Salary
                                </div>

                                <div
                                    class="salary-calculation-value"
                                    id="display-net"
                                >
                                    ₹0.00
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ============================
                     PAYMENT INFORMATION
                ============================= --}}
                <div class="salary-section">

                    <h3 class="salary-section-title">
                        <i class="fas fa-credit-card"></i>
                        Payment Information
                    </h3>

                    <div class="salary-grid">

                        {{-- PAYMENT STATUS --}}
                        <div class="salary-field">

                            <label class="salary-label">
                                Payment Status <span>*</span>
                            </label>

                            <div class="payment-status-options">

                                <div class="payment-option">

                                    <input
                                        type="radio"
                                        name="payment_status"
                                        id="status_pending"
                                        value="Pending"
                                        {{ old('payment_status', 'Pending') == 'Pending' ? 'checked' : '' }}
                                    >

                                    <label for="status_pending">
                                        <i class="fas fa-clock"></i>
                                        Pending
                                    </label>

                                </div>

                                <div class="payment-option">

                                    <input
                                        type="radio"
                                        name="payment_status"
                                        id="status_paid"
                                        value="Paid"
                                        {{ old('payment_status') == 'Paid' ? 'checked' : '' }}
                                    >

                                    <label for="status_paid">
                                        <i class="fas fa-check-circle"></i>
                                        Paid
                                    </label>

                                </div>

                            </div>

                            @error('payment_status')
                                <div class="salary-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- PAYMENT DATE --}}
                        <div class="salary-field">

                            <label class="salary-label">
                                Payment Date
                            </label>

                            <div class="salary-input-wrapper">

                                <i class="fas fa-calendar-check salary-input-icon"></i>

                                <input
                                    type="date"
                                    name="payment_date"
                                    id="payment_date"
                                    value="{{ old('payment_date') }}"
                                    class="salary-input has-icon"
                                >

                            </div>

                            @error('payment_date')
                                <div class="salary-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

                {{-- ============================
                     ADDITIONAL INFORMATION
                ============================= --}}
                <div class="salary-section">

                    <h3 class="salary-section-title">
                        <i class="fas fa-comment-alt"></i>
                        Additional Information
                    </h3>

                    <div class="salary-field salary-field-full">

                        <label class="salary-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="salary-textarea"
                            placeholder="Enter any additional salary notes..."
                        >{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="salary-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                {{-- ============================
                     ACTIONS
                ============================= --}}
                <div class="salary-actions">

                    <a
                        href="{{ route('admin.teachers.salary.index') }}"
                        class="salary-btn salary-btn-cancel"
                    >
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="salary-btn salary-btn-save"
                    >
                        <i class="fas fa-save"></i>
                        Generate Salary
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================
     SALARY CALCULATION SCRIPT
================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const basicInput = document.getElementById('basic_salary');
    const allowancesInput = document.getElementById('allowances');
    const deductionRateInput = document.getElementById('deduction_rate');

    const displayBasic = document.getElementById('display-basic');
    const displayAllowances = document.getElementById('display-allowances');
    const displayDeductions = document.getElementById('display-deductions');
    const displayNet = document.getElementById('display-net');

    function getValue(input) {
        return parseFloat(input.value) || 0;
    }

    function formatMoney(value) {
        return '₹' + value.toFixed(2);
    }

    function calculateSalary() {

        const basic = getValue(basicInput);
        const allowances = getValue(allowancesInput);
        const deductionRate = getValue(deductionRateInput);

        const deductionAmount =
            basic * deductionRate / 100;

        const netSalary =
            basic +
            allowances -
            deductionAmount;

        displayBasic.textContent =
            formatMoney(basic);

        displayAllowances.textContent =
            formatMoney(allowances);

        displayDeductions.textContent =
            formatMoney(deductionAmount);

        displayNet.textContent =
            formatMoney(netSalary);
    }

    basicInput.addEventListener(
        'input',
        calculateSalary
    );

    allowancesInput.addEventListener(
        'input',
        calculateSalary
    );

    deductionRateInput.addEventListener(
        'input',
        calculateSalary
    );

    calculateSalary();
});
</script>

@endsection