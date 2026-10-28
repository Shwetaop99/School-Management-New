@extends('layouts.app')

@section('title', 'Edit Salary')

@section('content')

@php
    $staffTypes = [
        'Teacher'      => 'Teacher',
        'Driver'       => 'Driver',
        'Peon'         => 'Peon',
        'Librarian'    => 'Librarian',
        'Accountant'   => 'Accountant',
        'Receptionist' => 'Receptionist',
        'OtherStaff'   => 'Other Staff',
    ];

    // Saved staff type of this salary (old rows without staff_type but with teacher_id = Teacher)
    $savedType = $teacherSalary->staff_type
        ?: ($teacherSalary->teacher_id ? 'Teacher' : 'OtherStaff');

    $currentType = old('staff_type', $savedType);

    if (!array_key_exists($currentType, $staffTypes)) {
        $currentType = 'Teacher';
    }

    $savedIsTeacher = $savedType === 'Teacher';

    // Deduction rate (%). Older rows may only have a deduction amount, so work the rate out.
    $basicSaved = (float) $teacherSalary->basic_salary;
    $rateSaved  = (float) ($teacherSalary->deduction_rate ?? 0);

    if ($rateSaved <= 0 && $basicSaved > 0 && (float) $teacherSalary->deductions > 0) {
        $rateSaved = round(((float) $teacherSalary->deductions / $basicSaved) * 100, 2);
    }
@endphp

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

    .salary-edit-subtitle strong {
        color: #172033;
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

    /* ALERT */

    .salary-alert {
        margin-bottom: 18px;
        padding: 13px 16px;
        border: 1px solid #f3d0cb;
        border-radius: 10px;
        background: #fff1ee;
        color: #b4382f;
        font-size: 13px;
        font-weight: 700;
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

    .salary-select:disabled {
        background: #f4f6f9;
        cursor: not-allowed;
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
                Update salary and payment information for
                <strong id="typeLabel">{{ $staffTypes[$currentType] }}</strong>
            </p>

        </div>


        {{-- Back to Salary List --}}

        <a
            href="{{ route('admin.salary.index') }}"
            class="salary-back-btn"
        >
            <i class="fas fa-arrow-left"></i>
            Back to Salary List
        </a>

    </div>


    @if($errors->any())
        <div class="salary-alert" role="alert">
            Please fix the highlighted fields and try again.
        </div>
    @endif


    <form
        action="{{ route('admin.salary.update', $teacherSalary->id) }}"
        method="POST"
        id="salaryEditForm"
    >

        @csrf
        @method('PUT')


        <div class="salary-form-card">

            {{-- Staff Information --}}

            <div class="salary-section">

                <h2 class="salary-section-title">
                    Staff Information
                </h2>


                <div class="salary-grid">

                    {{-- Staff Type --}}

                    <div class="salary-field">

                        <label class="salary-label" for="staff_type">
                            Staff Type <span>*</span>
                        </label>

                        <select
                            name="staff_type"
                            id="staff_type"
                            class="salary-select"
                            required
                        >

                            <option value="">
                                Select Staff Type
                            </option>

                            @foreach($staffTypes as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    {{ $currentType === $value ? 'selected' : '' }}
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                        @error('staff_type')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Staff Member --}}

                    <div class="salary-field">

                        <label class="salary-label" for="staff_member_id">
                            <span id="memberLabel" style="color:inherit;">{{ $staffTypes[$currentType] }}</span>
                            Name <span>*</span>
                        </label>

                        <select
                            id="staff_member_id"
                            class="salary-select"
                            required
                        >

                            <option value="">
                                Select Staff Member
                            </option>

                            @foreach($teachers as $teacher)

                                <option
                                    value="{{ $teacher->id }}"
                                    data-category="Teacher"
                                >

                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}

                                    @if($teacher->teacher_id)
                                        — {{ $teacher->teacher_id }}
                                    @endif

                                </option>

                            @endforeach

                            @foreach(($otherStaff ?? collect()) as $staff)

                                @php
                                    $designation = strtolower(trim($staff->designation ?? ''));

                                    if (str_contains($designation, 'driver')) {
                                        $category = 'Driver';
                                    } elseif (str_contains($designation, 'peon')) {
                                        $category = 'Peon';
                                    } elseif (
                                        str_contains($designation, 'librarian') ||
                                        str_contains($designation, 'library')
                                    ) {
                                        $category = 'Librarian';
                                    } elseif (
                                        str_contains($designation, 'accountant') ||
                                        str_contains($designation, 'account')
                                    ) {
                                        $category = 'Accountant';
                                    } elseif (
                                        str_contains($designation, 'receptionist') ||
                                        str_contains($designation, 'reception')
                                    ) {
                                        $category = 'Receptionist';
                                    } else {
                                        $category = 'OtherStaff';
                                    }

                                    // Keep the saved staff member under the saved staff type,
                                    // even if the designation text does not match it.
                                    if (!$savedIsTeacher && $staff->id == $teacherSalary->other_staff_id) {
                                        $category = $savedType;
                                    }
                                @endphp

                                <option
                                    value="{{ $staff->id }}"
                                    data-category="{{ $category }}"
                                >

                                    {{ $staff->name }}

                                    @if($staff->staff_id)
                                        — {{ $staff->staff_id }}
                                    @endif

                                    @if($staff->designation)
                                        ({{ $staff->designation }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        {{-- Only one of these two is filled, depending on staff type --}}

                        <input
                            type="hidden"
                            name="teacher_id"
                            id="teacher_id"
                            value="{{ old('teacher_id', $savedIsTeacher ? $teacherSalary->teacher_id : '') }}"
                        >

                        <input
                            type="hidden"
                            name="other_staff_id"
                            id="other_staff_id"
                            value="{{ old('other_staff_id', !$savedIsTeacher ? $teacherSalary->other_staff_id : '') }}"
                        >

                        <div class="salary-help" id="staffHelp">
                            Choose a staff type to see matching staff members.
                        </div>

                        @error('teacher_id')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                        @error('other_staff_id')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Salary Month --}}

                    <div class="salary-field">

                        <label class="salary-label" for="salary_month">
                            Salary Month <span>*</span>
                        </label>

                        <input
                            type="month"
                            name="salary_month"
                            id="salary_month"
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

                        <label class="salary-label" for="basic_salary">
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

                        <label class="salary-label" for="allowances">
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


                    {{-- Deduction Rate --}}

                    <div class="salary-field">

                        <label class="salary-label" for="deduction_rate">
                            Deduction Rate (%)
                        </label>

                        <input
                            type="number"
                            name="deduction_rate"
                            id="deduction_rate"
                            class="salary-input"
                            value="{{ old('deduction_rate', $rateSaved) }}"
                            min="0"
                            max="100"
                            step="0.01"
                        >

                        <div class="salary-help">
                            Percentage of basic salary. Example: 5 means 5% of basic salary.
                        </div>

                        @error('deduction_rate')

                            <div class="salary-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Payment Status --}}

                    <div class="salary-field">

                        <label class="salary-label" for="payment_status">
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

                        <label class="salary-label" for="payment_date">
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
                            - Deductions (<span id="previewRate">0</span>%)
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

                    <label class="salary-label" for="remarks">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        id="remarks"
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
                    href="{{ route('admin.salary.index') }}"
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

        /* =====================================================
           STAFF TYPE + STAFF MEMBER
        ===================================================== */

        const staffType = document.getElementById('staff_type');
        const staffSelect = document.getElementById('staff_member_id');
        const teacherIdInput = document.getElementById('teacher_id');
        const otherStaffIdInput = document.getElementById('other_staff_id');
        const staffHelp = document.getElementById('staffHelp');
        const typeLabel = document.getElementById('typeLabel');
        const memberLabel = document.getElementById('memberLabel');

        const typeNames = {
            Teacher: 'Teacher',
            Driver: 'Driver',
            Peon: 'Peon',
            Librarian: 'Librarian',
            Accountant: 'Accountant',
            Receptionist: 'Receptionist',
            OtherStaff: 'Other Staff'
        };

        const pluralNames = {
            Teacher: 'teachers',
            Driver: 'drivers',
            Peon: 'peons',
            Librarian: 'librarians',
            Accountant: 'accountants',
            Receptionist: 'receptionists',
            OtherStaff: 'other staff members'
        };

        // Keep every staff option, because the select is rebuilt on each change.
        const allStaffOptions = Array.from(staffSelect.options)
            .filter(option => option.value !== '');

        function isTeacher(category) {
            return category === 'Teacher';
        }

        function updateStaffOptions(restoreSaved) {

            const category = staffType.value;

            // Selection saved in the hidden inputs (existing record or old input).
            const previousValue = restoreSaved
                ? (isTeacher(category) ? teacherIdInput.value : otherStaffIdInput.value)
                : '';

            teacherIdInput.value = '';
            otherStaffIdInput.value = '';

            staffSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = category
                ? 'Select ' + (typeNames[category] || 'Staff Member')
                : 'Select Staff Type First';
            staffSelect.appendChild(placeholder);

            const matchingOptions = allStaffOptions.filter(option => {
                return option.dataset.category === category;
            });

            matchingOptions.forEach(option => staffSelect.appendChild(option));

            const previousExists = matchingOptions.some(option => {
                return option.value === String(previousValue);
            });

            staffSelect.value = previousExists ? String(previousValue) : '';

            if (staffSelect.value) {
                if (isTeacher(category)) {
                    teacherIdInput.value = staffSelect.value;
                } else {
                    otherStaffIdInput.value = staffSelect.value;
                }
            }

            staffSelect.disabled = !category || matchingOptions.length === 0;

            const name = typeNames[category] || 'Staff';
            typeLabel.textContent = name;
            memberLabel.textContent = name;

            if (!category) {
                staffHelp.textContent = 'Choose a staff type to see matching staff members.';
            } else if (matchingOptions.length === 0) {
                staffHelp.textContent = 'No staff found for ' + pluralNames[category] +
                    '. Check the staff designations.';
            } else {
                staffHelp.textContent = 'Showing ' + matchingOptions.length + ' ' +
                    pluralNames[category] + '.';
            }

        }

        staffType.addEventListener('change', function () {
            updateStaffOptions(false);
        });

        staffSelect.addEventListener('change', function () {

            teacherIdInput.value = '';
            otherStaffIdInput.value = '';

            if (!staffSelect.value) {
                return;
            }

            if (isTeacher(staffType.value)) {
                teacherIdInput.value = staffSelect.value;
            } else {
                otherStaffIdInput.value = staffSelect.value;
            }

        });

        // Initial load: select the staff member saved on this record.
        updateStaffOptions(true);


        /* =====================================================
           SALARY CALCULATION (deduction is a % of basic salary)
        ===================================================== */

        const basicInput = document.getElementById('basic_salary');
        const allowancesInput = document.getElementById('allowances');
        const rateInput = document.getElementById('deduction_rate');

        const previewBasic = document.getElementById('previewBasic');
        const previewAllowances = document.getElementById('previewAllowances');
        const previewDeductions = document.getElementById('previewDeductions');
        const previewRate = document.getElementById('previewRate');
        const previewNet = document.getElementById('previewNet');

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

            const basic = numberValue(basicInput);
            const allowances = numberValue(allowancesInput);
            const rate = numberValue(rateInput);

            const deductions = basic * rate / 100;
            const net = basic + allowances - deductions;

            previewBasic.textContent = money(basic);
            previewAllowances.textContent = money(allowances);
            previewDeductions.textContent = money(deductions);
            previewRate.textContent = rate;
            previewNet.textContent = money(net);

        }

        basicInput.addEventListener('input', calculateSalary);
        allowancesInput.addEventListener('input', calculateSalary);
        rateInput.addEventListener('input', calculateSalary);

        calculateSalary();


        /* =====================================================
           PAYMENT DATE
        ===================================================== */

        const paymentStatus = document.getElementById('payment_status');
        const paymentDate = document.getElementById('payment_date');

        function updatePaymentDate() {
            paymentDate.required = paymentStatus.value === 'Paid';
        }

        paymentStatus.addEventListener('change', updatePaymentDate);

        updatePaymentDate();


        /* =====================================================
           UPDATE BUTTON
        ===================================================== */

        const salaryForm = document.getElementById('salaryEditForm');
        const updateButton = document.getElementById('updateSalaryBtn');

        salaryForm.addEventListener('submit', function () {

            updateButton.disabled = true;

            updateButton.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Updating...';

        });

    });
</script>

@endsection