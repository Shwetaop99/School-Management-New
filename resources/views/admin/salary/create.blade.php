@extends('layouts.app')

@section('title', 'Generate Salary')
@section('page-title', 'Generate Salary')

@section('content')

<div class="teacher-salary-create-page">
    <div class="tsc-container">

        {{-- SUCCESS / ERROR --}}
        @if(session('success'))
            <div class="tsc-alert tsc-success" role="status">
                <span class="tsc-alert-icon">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="tsc-alert tsc-error" role="status">
                <span class="tsc-alert-icon">
                    <i class="bi bi-exclamation-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- FORM PANEL --}}
        <section class="tsc-paper-panel">

            <div class="tsc-panel-header">
                <div class="tsc-title-wrap">
                    <div class="tsc-title-icon">
                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h3>Salary Information</h3>
                        <p>Enter teacher salary and payment details below.</p>
                    </div>
                </div>

                <a href="{{ route('admin.teachers.salary.index') }}"
                   class="tsc-back-btn">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Back to Salary List
                </a>
            </div>

            <form action="{{ route('admin.teachers.salary.store') }}"
                  method="POST"
                  class="tsc-form">

                @csrf

                {{-- STAFF INFORMATION --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-person-badge" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Staff Information</h4>
                            <p>Select the staff category, staff member and salary month.</p>
                        </div>
                    </div>

                    <div class="tsc-grid tsc-grid-2">

                        {{-- STAFF TYPE --}}
                        <div class="tsc-field">
                            <label for="staff_type">
                                Staff Type <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-people" aria-hidden="true"></i>

                                <select name="staff_type"
                                        id="staff_type"
                                        class="tsc-control"
                                        required>
                                    <option value="">Select Staff Type</option>

                                    @foreach([
                                        'Teacher' => 'Teacher',
                                        'Driver' => 'Driver',
                                        'Peon' => 'Peon',
                                        'Librarian' => 'Librarian',
                                        'Accountant' => 'Accountant',
                                        'Receptionist' => 'Receptionist',
                                        'OtherStaff' => 'Other Staff',
                                    ] as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('staff_type', 'Teacher') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('staff_type')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STAFF MEMBER --}}
                        <div class="tsc-field">
                            <label for="staff_member_id">
                                Staff Member <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-person" aria-hidden="true"></i>

                                <select id="staff_member_id"
                                        class="tsc-control"
                                        required>
                                    <option value="">Select Staff Member</option>

                                    @foreach($teachers as $teacher)
                                        <option
                                            value="{{ $teacher->id }}"
                                            data-category="Teacher"
                                            data-designation="Teacher"
                                            data-other-staff="0"
                                            {{ old('staff_type', 'Teacher') === 'Teacher'
                                                && old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                                            @if($teacher->teacher_id)
                                                - {{ $teacher->teacher_id }}
                                            @endif
                                        </option>
                                    @endforeach

                                    @foreach($otherStaff as $staff)
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
                                        @endphp

                                        <option
                                            value="{{ $staff->id }}"
                                            data-category="{{ $category }}"
                                            data-designation="{{ $staff->designation }}"
                                            data-other-staff="1"
                                            {{ old('staff_type') === $category
                                                && old('other_staff_id') == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                            @if($staff->staff_id)
                                                - {{ $staff->staff_id }}
                                            @endif
                                            ({{ $staff->designation }})
                                        </option>
                                    @endforeach
                                </select>

                                <input type="hidden"
                                       name="teacher_id"
                                       id="teacher_id"
                                       value="{{ old('teacher_id') }}">

                                <input type="hidden"
                                       name="other_staff_id"
                                       id="other_staff_id"
                                       value="{{ old('other_staff_id') }}">
                            </div>

                            <small id="staff-help"
                                   style="display:block;margin-top:6px;color:#7b857b;">
                                Choose a staff type to see matching staff members.
                            </small>

                            @error('teacher_id')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror

                            @error('other_staff_id')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SALARY MONTH --}}
                        <div class="tsc-field">
                            <label for="salary_month">
                                Salary Month <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>

                                <input type="month"
                                       name="salary_month"
                                       id="salary_month"
                                       value="{{ old('salary_month', now()->format('Y-m')) }}"
                                       class="tsc-control"
                                       required>
                            </div>

                            @error('salary_month')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- SALARY DETAILS --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-cash-coin" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Salary Details</h4>
                            <p>Enter basic salary, allowances and deduction rate.</p>
                        </div>
                    </div>

                    <div class="tsc-grid tsc-grid-3">
                        <div class="tsc-field">
                            <label for="basic_salary">
                                Basic Salary <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-currency-rupee" aria-hidden="true"></i>

                                <input type="number"
                                       name="basic_salary"
                                       id="basic_salary"
                                       value="{{ old('basic_salary') }}"
                                       class="tsc-control"
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01"
                                       required>
                            </div>

                            @error('basic_salary')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="tsc-field">
                            <label for="allowances">Allowances</label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-plus-circle" aria-hidden="true"></i>

                                <input type="number"
                                       name="allowances"
                                       id="allowances"
                                       value="{{ old('allowances', 0) }}"
                                       class="tsc-control"
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01">
                            </div>

                            @error('allowances')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="tsc-field">
                            <label for="deduction_rate">Deduction Rate (%)</label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-percent" aria-hidden="true"></i>

                                <input type="number"
                                       name="deduction_rate"
                                       id="deduction_rate"
                                       value="{{ old('deduction_rate', 0) }}"
                                       class="tsc-control"
                                       placeholder="0.00"
                                       min="0"
                                       max="100"
                                       step="0.01">
                            </div>

                            @error('deduction_rate')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- CALCULATION --}}
                    <div class="tsc-calculation">
                        <div class="tsc-calculation-heading">
                            <i class="bi bi-calculator" aria-hidden="true"></i>
                            Salary Calculation
                        </div>

                        <div class="tsc-calculation-grid">
                            <div class="tsc-calculation-item">
                                <span class="tsc-calculation-label">Basic</span>
                                <span class="tsc-calculation-value" id="display-basic">₹0.00</span>
                            </div>

                            <div class="tsc-calculation-item">
                                <span class="tsc-calculation-label">Allowances</span>
                                <span class="tsc-calculation-value" id="display-allowances">₹0.00</span>
                            </div>

                            <div class="tsc-calculation-item">
                                <span class="tsc-calculation-label">Deductions</span>
                                <span class="tsc-calculation-value tsc-deduction" id="display-deductions">₹0.00</span>
                            </div>

                            <div class="tsc-calculation-item tsc-net">
                                <span class="tsc-calculation-label">Net Salary</span>
                                <span class="tsc-calculation-value" id="display-net">₹0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT INFORMATION --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-credit-card" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Payment Information</h4>
                            <p>Choose the payment status and payment date.</p>
                        </div>
                    </div>

                    <div class="tsc-grid tsc-grid-2">
                        <div class="tsc-field">
                            <label>
                                Payment Status <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-status-options">
                                <div class="tsc-status-option">
                                    <input type="radio"
                                           name="payment_status"
                                           id="status_pending"
                                           value="Pending"
                                           {{ old('payment_status', 'Pending') == 'Pending' ? 'checked' : '' }}>

                                    <label for="status_pending">
                                        <i class="bi bi-clock" aria-hidden="true"></i>
                                        Pending
                                    </label>
                                </div>

                                <div class="tsc-status-option">
                                    <input type="radio"
                                           name="payment_status"
                                           id="status_paid"
                                           value="Paid"
                                           {{ old('payment_status') == 'Paid' ? 'checked' : '' }}>

                                    <label for="status_paid">
                                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                                        Paid
                                    </label>
                                </div>
                            </div>

                            @error('payment_status')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="tsc-field">
                            <label for="payment_date">Payment Date</label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-calendar-event" aria-hidden="true"></i>

                                <input type="date"
                                       name="payment_date"
                                       id="payment_date"
                                       value="{{ old('payment_date') }}"
                                       class="tsc-control">
                            </div>

                            @error('payment_date')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ADDITIONAL INFORMATION --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-chat-left-text" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Additional Information</h4>
                            <p>Add any salary-related remarks if needed.</p>
                        </div>
                    </div>

                    <div class="tsc-field">
                        <label for="remarks">Remarks</label>

                        <textarea name="remarks"
                                  id="remarks"
                                  class="tsc-textarea"
                                  placeholder="Enter any additional salary notes...">{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="tsc-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="tsc-form-actions">
                    <a href="{{ route('admin.teachers.salary.index') }}"
                       class="tsc-cancel-btn">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        Cancel
                    </a>

                    <button type="submit" class="tsc-save-btn">
                        <i class="bi bi-check-lg" aria-hidden="true"></i>
                        Generate Salary
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

<style>
/* TEACHER SALARY CREATE — TRANSPORT PAGE DESIGN SYSTEM
   No page header or hero. Scoped to this page. */

.teacher-salary-create-page {
    --tsc-ink: #263b35;
    --tsc-text: #39483e;
    --tsc-muted: #7b857b;
    --tsc-line: #e5e1d5;
    --tsc-paper: #fffefa;
    --tsc-chalk: #f5f3e9;
    --tsc-blue: #426b8a;
    --tsc-green: #43835d;
    --tsc-amber: #a87543;
    --tsc-red: #b95b50;
    --tsc-serif: var(--serif, Georgia, "Times New Roman", serif);

    min-height: calc(100vh - 70px);
    color: var(--tsc-text);
    background: #f7f6f0;
    font-family: "Public Sans", sans-serif;
    font-size: 13px;
}

.teacher-salary-create-page *,
.teacher-salary-create-page *::before,
.teacher-salary-create-page *::after {
    box-sizing: border-box;
}

.teacher-salary-create-page a,
.teacher-salary-create-page button,
.teacher-salary-create-page input,
.teacher-salary-create-page select,
.teacher-salary-create-page textarea {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.teacher-salary-create-page .tsc-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.teacher-salary-create-page .tsc-paper-panel {
    overflow: hidden;
    border: 1px solid var(--tsc-line);
    border-radius: 15px;
    background: var(--tsc-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* ALERTS */

.teacher-salary-create-page .tsc-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
}

.teacher-salary-create-page .tsc-success {
    border: 1px solid #d2e5d1;
    color: #347849;
    background: #f1f8ef;
}

.teacher-salary-create-page .tsc-error {
    border: 1px solid #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.teacher-salary-create-page .tsc-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    background: rgba(255, 255, 255, .7);
}

/* PANEL HEADER */

.teacher-salary-create-page .tsc-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: var(--tsc-paper);
}

.teacher-salary-create-page .tsc-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.teacher-salary-create-page .tsc-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--tsc-ink);
    background: #f1f2e8;
    font-size: 16px;
}

.teacher-salary-create-page .tsc-title-wrap h3 {
    margin: 0;
    color: var(--tsc-ink);
    font-family: var(--tsc-serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.teacher-salary-create-page .tsc-title-wrap p {
    margin: 5px 0 0;
    color: var(--tsc-muted);
    font-size: 11px;
    line-height: 1.7;
}

.teacher-salary-create-page .tsc-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 9px 14px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: var(--tsc-paper);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.teacher-salary-create-page .tsc-back-btn:hover {
    border-color: #b5c4b0;
    color: var(--tsc-ink);
    background: #f0f3e9;
}

/* FORM */

.teacher-salary-create-page .tsc-form {
    padding: 24px;
}

.teacher-salary-create-page .tsc-form-section {
    margin-bottom: 28px;
    padding-bottom: 26px;
    border-bottom: 1px dashed #ddd8ca;
}

.teacher-salary-create-page .tsc-form-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.teacher-salary-create-page .tsc-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #eeece3;
}

.teacher-salary-create-page .tsc-section-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border: 1px solid #dce6da;
    border-radius: 11px;
    color: #527f70;
    background: #edf3e9;
    font-size: 15px;
}

.teacher-salary-create-page .tsc-section-heading h4 {
    margin: 0;
    color: var(--tsc-ink);
    font-family: var(--tsc-serif);
    font-size: 17px;
    font-weight: 700;
}

.teacher-salary-create-page .tsc-section-heading p {
    margin: 4px 0 0;
    color: var(--tsc-muted);
    font-size: 10px;
    line-height: 1.7;
}

.teacher-salary-create-page .tsc-grid {
    display: grid;
    gap: 16px;
}

.teacher-salary-create-page .tsc-grid-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.teacher-salary-create-page .tsc-grid-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.teacher-salary-create-page .tsc-field {
    min-width: 0;
}

.teacher-salary-create-page .tsc-field label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.teacher-salary-create-page .tsc-required {
    color: var(--tsc-red);
}

.teacher-salary-create-page .tsc-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.teacher-salary-create-page .tsc-input-wrap > i {
    position: absolute;
    left: 13px;
    z-index: 1;
    color: #829083;
    font-size: 13px;
    pointer-events: none;
}

.teacher-salary-create-page .tsc-control,
.teacher-salary-create-page .tsc-textarea {
    width: 100%;
    min-width: 0;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    color: var(--tsc-text);
    background: var(--tsc-paper);
    font-family: inherit;
    font-size: 12px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.teacher-salary-create-page .tsc-control {
    height: 44px;
    padding: 0 12px 0 38px;
}

.teacher-salary-create-page .tsc-textarea {
    min-height: 105px;
    padding: 12px 13px;
    resize: vertical;
}

.teacher-salary-create-page .tsc-control:hover,
.teacher-salary-create-page .tsc-textarea:hover {
    border-color: #bdcabb;
}

.teacher-salary-create-page .tsc-control:focus,
.teacher-salary-create-page .tsc-textarea:focus {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.teacher-salary-create-page .tsc-control::placeholder,
.teacher-salary-create-page .tsc-textarea::placeholder {
    color: #9a9f93;
}

/* CALCULATION */

.teacher-salary-create-page .tsc-calculation {
    margin-top: 20px;
    padding: 18px;
    border: 1px solid #e7dfc9;
    border-radius: 12px;
    background: #faf5e8;
}

.teacher-salary-create-page .tsc-calculation-heading {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
    color: #765e32;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.teacher-salary-create-page .tsc-calculation-heading i {
    color: var(--tsc-amber);
}

.teacher-salary-create-page .tsc-calculation-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 13px;
}

.teacher-salary-create-page .tsc-calculation-item {
    min-width: 0;
    padding: 14px;
    border: 1px solid #e5e1d5;
    border-radius: 10px;
    background: var(--tsc-paper);
}

.teacher-salary-create-page .tsc-calculation-label {
    display: block;
    color: #758176;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.teacher-salary-create-page .tsc-calculation-value {
    display: block;
    margin-top: 6px;
    overflow-wrap: anywhere;
    color: var(--tsc-ink);
    font-family: var(--tsc-serif);
    font-size: 18px;
    font-weight: 700;
}

.teacher-salary-create-page .tsc-deduction {
    color: var(--tsc-red);
}

.teacher-salary-create-page .tsc-net {
    border-color: #d4e5d0;
    background: #edf6e9;
}

.teacher-salary-create-page .tsc-net .tsc-calculation-value {
    color: #347849;
}

/* STATUS OPTIONS */

.teacher-salary-create-page .tsc-status-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.teacher-salary-create-page .tsc-status-option {
    position: relative;
}

.teacher-salary-create-page .tsc-status-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.teacher-salary-create-page .tsc-status-option label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 15px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: var(--tsc-paper);
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
}

.teacher-salary-create-page .tsc-status-option label:hover {
    border-color: #b5c4b0;
    color: var(--tsc-ink);
    background: #f0f3e9;
}

.teacher-salary-create-page .tsc-status-option input:checked + label {
    border-color: var(--tsc-ink);
    color: var(--tsc-ink);
    background: #f1f2e8;
}

/* ERRORS */

.teacher-salary-create-page .tsc-error {
    margin-top: 6px;
    color: #b95b50;
    font-size: 10px;
    font-weight: 700;
}

/* ACTIONS */

.teacher-salary-create-page .tsc-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 24px;
    padding-top: 22px;
    border-top: 1px dashed #ddd8ca;
}

.teacher-salary-create-page .tsc-cancel-btn,
.teacher-salary-create-page .tsc-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 16px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.teacher-salary-create-page .tsc-cancel-btn {
    border: 1px solid #deded2;
    color: #5d6b60;
    background: var(--tsc-paper);
}

.teacher-salary-create-page .tsc-cancel-btn:hover {
    border-color: #b5c4b0;
    color: var(--tsc-ink);
    background: #f0f3e9;
}

.teacher-salary-create-page .tsc-save-btn {
    border: 1px solid var(--tsc-ink);
    color: #fff;
    background: var(--tsc-ink);
}

.teacher-salary-create-page .tsc-save-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

/* TABLET */

@media (max-width: 1000px) {
    .teacher-salary-create-page .tsc-grid-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .teacher-salary-create-page .tsc-calculation-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* MOBILE */

@media (max-width: 700px) {
    .teacher-salary-create-page .tsc-container {
        padding: 18px 14px;
    }

    .teacher-salary-create-page .tsc-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 16px;
    }

    .teacher-salary-create-page .tsc-title-wrap {
        gap: 10px;
    }

    .teacher-salary-create-page .tsc-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .teacher-salary-create-page .tsc-title-wrap h3 {
        font-size: 16px;
    }

    .teacher-salary-create-page .tsc-title-wrap p {
        font-size: 10px;
    }

    .teacher-salary-create-page .tsc-back-btn {
        width: 100%;
    }

    .teacher-salary-create-page .tsc-form {
        padding: 17px 15px;
    }

    .teacher-salary-create-page .tsc-section-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .teacher-salary-create-page .tsc-section-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .teacher-salary-create-page .tsc-grid-2,
    .teacher-salary-create-page .tsc-grid-3,
    .teacher-salary-create-page .tsc-calculation-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .teacher-salary-create-page .tsc-form-actions {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .teacher-salary-create-page .tsc-cancel-btn,
    .teacher-salary-create-page .tsc-save-btn {
        width: 100%;
    }
}

/* ACCESSIBILITY AND REDUCED MOTION */

.teacher-salary-create-page a:focus-visible,
.teacher-salary-create-page button:focus-visible,
.teacher-salary-create-page input:focus-visible,
.teacher-salary-create-page select:focus-visible,
.teacher-salary-create-page textarea:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .teacher-salary-create-page *,
    .teacher-salary-create-page *::before,
    .teacher-salary-create-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Salary calculation fields
    const basicInput = document.getElementById('basic_salary');
    const allowancesInput = document.getElementById('allowances');
    const deductionRateInput = document.getElementById('deduction_rate');

    const displayBasic = document.getElementById('display-basic');
    const displayAllowances = document.getElementById('display-allowances');
    const displayDeductions = document.getElementById('display-deductions');
    const displayNet = document.getElementById('display-net');

    // Staff fields
    const staffType = document.getElementById('staff_type');
    const staffSelect = document.getElementById('staff_member_id');
    const teacherIdInput = document.getElementById('teacher_id');
    const otherStaffIdInput = document.getElementById('other_staff_id');
    const staffHelp = document.getElementById('staff-help');

    const allStaffOptions = Array.from(staffSelect.options)
        .filter(option => option.value !== '');

    function updateStaffOptions() {
        const category = staffType.value;
        const previousTeacherId = teacherIdInput.value;
        const previousOtherStaffId = otherStaffIdInput.value;

        // Clear IDs so the form cannot submit a stale staff selection.
        teacherIdInput.value = '';
        otherStaffIdInput.value = '';

        staffSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = category
            ? 'Select Staff Member'
            : 'Select Staff Type First';

        staffSelect.appendChild(placeholder);

        const matchingOptions = allStaffOptions.filter(option => {
            return option.dataset.category === category;
        });

        matchingOptions.forEach(option => {
            staffSelect.appendChild(option);
        });

        const previousValue = category === 'Teacher'
            ? previousTeacherId
            : previousOtherStaffId;

        const previousOptionExists = matchingOptions.some(option => {
            return option.value === previousValue;
        });

        staffSelect.value = previousOptionExists ? previousValue : '';

        // Populate the correct hidden field.
        if (staffSelect.value) {
            if (category === 'Teacher') {
                teacherIdInput.value = staffSelect.value;
            } else {
                otherStaffIdInput.value = staffSelect.value;
            }
        }

        staffSelect.disabled = !category || matchingOptions.length === 0;

        const labels = {
            Teacher: 'teachers',
            Driver: 'drivers',
            Peon: 'peons',
            Librarian: 'librarians',
            Accountant: 'accountants',
            Receptionist: 'receptionists',
            OtherStaff: 'other staff members'
        };

        if (!category) {
            staffHelp.textContent =
                'Choose a staff type to see matching staff members.';
        } else if (matchingOptions.length === 0) {
            staffHelp.textContent =
                'No matching staff found. Check the staff member designations.';
        } else {
            staffHelp.textContent =
                'Showing ' + matchingOptions.length + ' ' +
                labels[category] + '.';
        }
    }

    staffType.addEventListener('change', updateStaffOptions);

    staffSelect.addEventListener('change', function () {
        teacherIdInput.value = '';
        otherStaffIdInput.value = '';

        if (!staffSelect.value) {
            return;
        }

        if (staffType.value === 'Teacher') {
            teacherIdInput.value = staffSelect.value;
        } else {
            otherStaffIdInput.value = staffSelect.value;
        }
    });

    // Initialize dropdown and restore any old selection.
    updateStaffOptions();

    // Salary calculation
    function getValue(input) {
        return parseFloat(input.value) || 0;
    }

    function formatMoney(value) {
        return '₹' + value.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calculateSalary() {
        const basic = getValue(basicInput);
        const allowances = getValue(allowancesInput);
        const deductionRate = getValue(deductionRateInput);

        const deductionAmount = basic * deductionRate / 100;
        const netSalary = basic + allowances - deductionAmount;

        displayBasic.textContent = formatMoney(basic);
        displayAllowances.textContent = formatMoney(allowances);
        displayDeductions.textContent = formatMoney(deductionAmount);
        displayNet.textContent = formatMoney(netSalary);
    }

    basicInput.addEventListener('input', calculateSalary);
    allowancesInput.addEventListener('input', calculateSalary);
    deductionRateInput.addEventListener('input', calculateSalary);

    calculateSalary();
});
</script>

@endsection