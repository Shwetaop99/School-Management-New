
@extends('layouts.app')

@section('title', 'Assign Student Fee | Admin')
@section('page-title', 'Assign Student Fee')

@section('content')

<div class="student-fee-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-receipt"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        FEES <span>/</span> STUDENT FEES <span>/</span> ASSIGN
                    </div>

                    <h2>Assign Student Fee</h2>

                    <p>
                        Assign fee structures, manage discounts and fines,
                        and review the student's final fee balance.
                    </p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.fees.student-fees.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Student Fees
                </a>
            </div>
        </section>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="error-copy">
                    <strong>Please check the following:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.fees.student-fees.store') }}"
              id="studentFeeForm">

            @csrf

            {{-- MAIN FORM CARD --}}
            <section class="form-card">

                <div class="form-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>

                        <div>
                            <div class="section-eyebrow">SCHOOL FEE REGISTER</div>
                            <p>Enter student details and assign the applicable fee structure.</p>
                        </div>
                    </div>

                    <div class="required-info">
                        <span>*</span> Required fields
                    </div>
                </div>

                <div class="form-body">

                    {{-- =====================================================
                         SECTION 1: STUDENT INFORMATION
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>

                            <div>
                                <h4>Student Information</h4>
                                <span>Select the class and identify the student.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- CLASS --}}
                            <div class="diary-filter-group">
                                <label for="class_id">
                                    Select Class <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-school input-icon"></i>

                                    <select id="class_id"
                                            class="diary-control with-icon"
                                            required>
                                        <option value="">Select Class First</option>

                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}"
                                                data-class-name="{{ $class->class_name }}"
                                                @selected(old('class_id') == $class->id)>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Select a class to load its students.
                                </div>

                                <div class="class-selection-note" id="classNote">
                                    <i class="fas fa-info-circle"></i>
                                    Students will appear after selecting a class.
                                </div>
                            </div>

                            {{-- ACADEMIC YEAR --}}
                            <div class="diary-filter-group">
                                <label for="academic_year">
                                    Academic Year <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-alt input-icon"></i>

                                    <select name="academic_year"
                                            id="academic_year"
                                            class="diary-control with-icon @error('academic_year') is-invalid @enderror"
                                            required>

                                        <option value="">Select Academic Year</option>

                                        @foreach($academicYears as $year)
                                            <option value="{{ $year }}"
                                                @selected(old('academic_year') == $year)>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('academic_year')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- STUDENT --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="student_id">
                                    Select Student <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>

                                    <select name="student_id"
                                            id="student_id"
                                            class="diary-control with-icon @error('student_id') is-invalid @enderror"
                                            required
                                            disabled>

                                        <option value="">Select Class First</option>

                                        @foreach($students as $student)
                                            @php
                                                $fullName = trim(
                                                    ($student->first_name ?? '') . ' ' .
                                                    ($student->middle_name ?? '') . ' ' .
                                                    ($student->last_name ?? '')
                                                );
                                            @endphp

                                            <option value="{{ $student->id }}"
                                                data-student-id="{{ $student->student_id }}"
                                                data-name="{{ $fullName }}"
                                                data-year="{{ $student->academic_year ?? '' }}"
                                                data-class="{{ $student->class ?? '' }}"
                                                data-section="{{ $student->section ?? '' }}"
                                                data-image="{{ !empty($student->profile_image) ? asset('storage/' . $student->profile_image) : '' }}"
                                                @selected(old('student_id') == $student->id)>

                                                {{ $student->student_id }} - {{ $fullName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Only students belonging to the selected class will be displayed.
                                </div>

                                @error('student_id')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- STUDENT PREVIEW --}}
                            <div class="form-grid-full">
                                <div class="student-preview" id="studentPreview">

                                    <div class="student-avatar" id="studentAvatar">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div class="student-preview-content">
                                        <div class="student-preview-name" id="studentName">
                                            Student Name
                                        </div>

                                        <div class="student-preview-details" id="studentDetails">
                                            Student information will appear here.
                                        </div>
                                    </div>

                                    <div class="student-preview-badge">
                                        <i class="fas fa-check-circle"></i>
                                        Selected
                                    </div>

                                </div>
                            </div>

                            {{-- STUDENT ID --}}
                            <div class="diary-filter-group">
                                <label for="display_student_id">Student ID</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-id-card input-icon"></i>

                                    <input type="text"
                                           id="display_student_id"
                                           class="diary-control with-icon is-readonly"
                                           readonly
                                           placeholder="Select student">
                                </div>
                            </div>

                            {{-- CLASS DISPLAY --}}
                            <div class="diary-filter-group">
                                <label for="display_class">Student Class</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-school input-icon"></i>

                                    <input type="text"
                                           id="display_class"
                                           class="diary-control with-icon is-readonly"
                                           readonly
                                           placeholder="Select student">
                                </div>
                            </div>

                            {{-- SECTION DISPLAY --}}
                            <div class="diary-filter-group">
                                <label for="display_section">Student Section</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-layer-group input-icon"></i>

                                    <input type="text"
                                           id="display_section"
                                           class="diary-control with-icon is-readonly"
                                           readonly
                                           placeholder="Select student">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- =====================================================
                         SECTION 2: FEE STRUCTURE
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon purple">
                                <i class="fas fa-file-alt"></i>
                            </div>

                            <div>
                                <h4>Fee Structure</h4>
                                <span>Choose the applicable fee structure for this student.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="diary-filter-group form-grid-full">
                                <label for="fee_structure_id">
                                    Fee Structure <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-file-invoice input-icon"></i>

                                    <select name="fee_structure_id"
                                            id="fee_structure_id"
                                            class="diary-control with-icon @error('fee_structure_id') is-invalid @enderror"
                                            required>

                                        <option value="">Select Fee Structure</option>

                                        @foreach($feeStructures as $structure)
                                            @php
                                                $structureTotal = $structure->items->sum('amount');
                                            @endphp

                                            <option value="{{ $structure->id }}"
                                                data-total="{{ $structureTotal }}"
                                                data-year="{{ $structure->academic_year }}"
                                                data-class-id="{{ $structure->class_id }}"
                                                @selected(old('fee_structure_id') == $structure->id)>

                                                {{ $structure->structure_name }}
                                                - {{ $structure->schoolClass->class_name ?? 'Class' }}

                                                @if($structure->section)
                                                    / {{ $structure->section->section_name }}
                                                @else
                                                    / All Sections
                                                @endif

                                                - ₹{{ number_format((float)$structureTotal, 2) }}
                                                - {{ $structure->academic_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('fee_structure_id')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="structure-details" id="structureDetails">

                                    <div class="structure-details-heading">
                                        <i class="fas fa-list-check"></i>
                                        Fee Structure Summary
                                    </div>

                                    <div class="fee-items" id="feeItems"></div>

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- =====================================================
                         SECTION 3: FEE AMOUNT
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon green">
                                <i class="fas fa-calculator"></i>
                            </div>

                            <div>
                                <h4>Fee Amount & Calculation</h4>
                                <span>Enter discount, applicable fine and payment due date.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- DISCOUNT --}}
                            <div class="diary-filter-group">
                                <label for="discount_amount">Discount Amount</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-percent input-icon"></i>

                                    <input type="number"
                                           name="discount_amount"
                                           id="discount_amount"
                                           class="diary-control with-icon @error('discount_amount') is-invalid @enderror"
                                           value="{{ old('discount_amount', 0) }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="Enter discount amount">
                                </div>

                                @error('discount_amount')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Enter the discount amount in rupees.
                                </div>
                            </div>

                            {{-- FINE --}}
                            <div class="diary-filter-group">
                                <label for="fine_amount">Fine Amount</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-exclamation-circle input-icon"></i>

                                    <input type="number"
                                           name="fine_amount"
                                           id="fine_amount"
                                           class="diary-control with-icon @error('fine_amount') is-invalid @enderror"
                                           value="{{ old('fine_amount', 0) }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="Enter fine amount">
                                </div>

                                @error('fine_amount')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Enter any applicable additional fine.
                                </div>
                            </div>

                            {{-- DUE DATE --}}
                            <div class="diary-filter-group">
                                <label for="due_date">Due Date</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-day input-icon"></i>

                                    <input type="date"
                                           name="due_date"
                                           id="due_date"
                                           class="diary-control with-icon @error('due_date') is-invalid @enderror"
                                           value="{{ old('due_date') }}">
                                </div>

                                @error('due_date')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- CALCULATION SUMMARY --}}
                            <div class="calculation-box">

                                <div class="calculation-title">
                                    <i class="fas fa-receipt"></i>
                                    Fee Calculation Summary
                                </div>

                                <div class="calculation-row">
                                    <span>Structure Total</span>
                                    <strong id="totalDisplay">₹0.00</strong>
                                </div>

                                <div class="calculation-row">
                                    <span>Discount</span>
                                    <strong id="discountDisplay" class="discount-value">
                                        - ₹0.00
                                    </strong>
                                </div>

                                <div class="calculation-row">
                                    <span>Fine</span>
                                    <strong id="fineDisplay" class="fine-value">
                                        + ₹0.00
                                    </strong>
                                </div>

                                <div class="calculation-total">
                                    <span>Final Balance</span>
                                    <strong id="balanceDisplay">₹0.00</strong>
                                </div>

                            </div>

                            {{-- REMARKS --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="remarks">Remarks</label>

                                <textarea name="remarks"
                                          id="remarks"
                                          class="diary-textarea @error('remarks') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Enter any additional remarks...">{{ old('remarks') }}</textarea>

                                @error('remarks')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">

                    <div class="action-note">
                        <i class="fas fa-shield-alt"></i>
                        Please verify student and fee details before saving.
                    </div>

                    <div class="action-buttons">

                        <a href="{{ route('admin.fees.student-fees.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-xmark"></i>
                            Cancel
                        </a>

                        <button type="submit"
                                id="submitButton"
                                class="diary-primary-btn">
                            <i class="fas fa-check"></i>
                            Assign Fee
                        </button>

                    </div>
                </div>

            </section>

        </form>

    </div>
</div>

<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM
   STUDENT FEES - ASSIGN STUDENT FEE
========================================================= */

.student-fee-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --purple: #7063b9;
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.student-fee-page *,
.student-fee-page *::before,
.student-fee-page *::after {
    box-sizing: border-box;
}

.student-fee-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

/* HERO */

.student-fee-page .form-hero,
.student-fee-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

.student-fee-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38,59,53,.045);
}

.student-fee-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189,81,72,.35);
}

.student-fee-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244,240,223,.65), transparent 65%),
        var(--paper);
}

.student-fee-page .form-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.student-fee-page .form-hero-text {
    min-width: 0;
}

.student-fee-page .form-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.student-fee-page .form-breadcrumb span {
    color: #b8b09e;
}

.student-fee-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.student-fee-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.student-fee-page .form-hero-actions {
    padding: 0 28px;
}

/* BUTTONS */

.student-fee-page .diary-primary-btn,
.student-fee-page .diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 16px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
    transition: all .18s ease;
}

.student-fee-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.student-fee-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29,48,43,.14);
}

.student-fee-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
}

.student-fee-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.student-fee-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ERRORS */

.student-fee-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #f0d5ce;
    border-radius: 9px;
    color: #a84840;
    background: #fff4ef;
    font-size: 12px;
}

.student-fee-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.student-fee-page .error-copy {
    flex: 1;
    min-width: 0;
}

.student-fee-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.student-fee-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.student-fee-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

/* MAIN CARD */

.student-fee-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38,59,53,.04);
}

.student-fee-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.student-fee-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.student-fee-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.student-fee-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.student-fee-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.student-fee-page .required-info {
    flex-shrink: 0;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.student-fee-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* FORM BODY */

.student-fee-page .form-body {
    padding: 24px 22px 8px;
}

.student-fee-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.student-fee-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.student-fee-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.student-fee-page .section-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 13px;
}

.student-fee-page .section-heading-icon.purple {
    color: var(--purple);
    background: #f1efff;
    border-color: #e6e1ff;
}

.student-fee-page .section-heading-icon.green {
    color: var(--green);
    background: #eaf5eb;
    border-color: #d8eadb;
}

.student-fee-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.student-fee-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* GRID */

.student-fee-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    align-items: start;
    gap: 20px 16px;
}

.student-fee-page .form-grid-full {
    grid-column: 1 / -1;
}

.student-fee-page .diary-filter-group {
    min-width: 0;
}

.student-fee-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.student-fee-page .required {
    color: var(--red);
}

/* INPUTS */

.student-fee-page .input-wrapper {
    position: relative;
    width: 100%;
}

.student-fee-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
}

.student-fee-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.student-fee-page .diary-control,
.student-fee-page .diary-textarea {
    display: block;
    width: 100%;
    min-width: 0;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.student-fee-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.student-fee-page .diary-control.with-icon {
    padding-left: 35px;
}

.student-fee-page select.diary-control {
    cursor: pointer;
}

.student-fee-page .diary-control::placeholder,
.student-fee-page .diary-textarea::placeholder {
    color: #929b8f;
}

.student-fee-page .diary-control:focus,
.student-fee-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58,128,96,.11);
}

.student-fee-page .diary-control.is-invalid,
.student-fee-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.student-fee-page .diary-control.is-readonly {
    background: #f5f4eb;
    color: #68766d;
    cursor: not-allowed;
    border-color: #e3e2d5;
}

.student-fee-page .diary-textarea {
    min-height: 90px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

/* HELP AND ERRORS */

.student-fee-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.student-fee-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.student-fee-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

/* CLASS NOTE */

.student-fee-page .class-selection-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    padding: 10px 12px;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: #78827a;
    background: #faf9f4;
    font-size: 10px;
    line-height: 1.5;
}

.student-fee-page .class-selection-note i {
    color: var(--amber);
}

/* STUDENT PREVIEW */

.student-fee-page .student-preview {
    display: none;
    align-items: center;
    gap: 15px;
    padding: 16px;
    border: 1px solid #dce5d8;
    border-radius: 9px;
    background: #f5f8ef;
}

.student-fee-page .student-preview.show {
    display: flex;
}

.student-fee-page .student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    overflow: hidden;
    border: 2px solid #e1e6d9;
    border-radius: 50%;
    color: var(--ink);
    background: var(--chalk);
    font-size: 20px;
}

.student-fee-page .student-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.student-fee-page .student-preview-content {
    flex: 1;
    min-width: 0;
}

.student-fee-page .student-preview-name {
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
}

.student-fee-page .student-preview-details {
    margin-top: 5px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

.student-fee-page .student-preview-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border: 1px solid #d6e8d5;
    border-radius: 20px;
    color: var(--green);
    background: #fffefa;
    font-size: 10px;
    font-weight: 700;
}

/* FEE STRUCTURE */

.student-fee-page .structure-details {
    display: none;
    margin-top: 14px;
    padding: 15px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf9f4;
}

.student-fee-page .structure-details.show {
    display: block;
}

.student-fee-page .structure-details-heading {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    color: #765e32;
    font-family: var(--serif);
    font-size: 13px;
    font-weight: 700;
}

.student-fee-page .fee-items {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.student-fee-page .fee-item {
    padding: 8px 12px;
    border: 1px solid #e3e2d5;
    border-radius: 7px;
    color: #59675d;
    background: #fffefa;
    font-size: 11px;
    font-weight: 600;
}

/* CALCULATION */

.student-fee-page .calculation-box {
    min-width: 0;
    padding: 17px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    background: #faf9f4;
}

.student-fee-page .calculation-title {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #d8d1bf;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.student-fee-page .calculation-title i {
    color: var(--green);
    font-size: 15px;
}

.student-fee-page .calculation-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    padding: 11px 0;
    border-bottom: 1px dashed #deded2;
    color: var(--muted);
    font-size: 11px;
}

.student-fee-page .calculation-row strong {
    color: var(--ink);
    font-weight: 700;
}

.student-fee-page .discount-value {
    color: var(--green) !important;
}

.student-fee-page .fine-value {
    color: var(--amber) !important;
}

.student-fee-page .calculation-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-top: 13px;
    padding: 13px;
    border: 1px solid #d6e8d5;
    border-radius: 8px;
    background: #edf4e9;
}

.student-fee-page .calculation-total span {
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
}

.student-fee-page .calculation-total strong {
    color: var(--green);
    font-size: 19px;
    font-weight: 800;
}

/* ACTION BAR */

.student-fee-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 22px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

.student-fee-page .action-note {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--muted);
    font-size: 10px;
}

.student-fee-page .action-note i {
    color: var(--green);
}

.student-fee-page .action-buttons {
    display: flex;
    align-items: center;
    gap: 9px;
}

/* TABLET */

@media(max-width: 900px) {
    .student-fee-page .form-hero {
        grid-template-columns: 1fr;
    }

    .student-fee-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

/* MOBILE */

@media(max-width: 650px) {

    .student-fee-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .student-fee-page .form-hero::before {
        left: 22px;
    }

    .student-fee-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .student-fee-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        font-size: 18px;
    }

    .student-fee-page .form-hero-text h2 {
        font-size: 23px;
    }

    .student-fee-page .form-hero-text p {
        font-size: 12px;
    }

    .student-fee-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .student-fee-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .student-fee-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .student-fee-page .required-info {
        display: none;
    }

    .student-fee-page .form-body {
        padding: 18px 15px 6px;
    }

    .student-fee-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .student-fee-page .form-grid-full {
        grid-column: auto;
    }

    .student-fee-page .diary-control,
    .student-fee-page .diary-textarea {
        font-size: 16px;
    }

    .student-fee-page .student-preview {
        flex-wrap: wrap;
    }

    .student-fee-page .student-preview-badge {
        margin-left: 68px;
    }

    .student-fee-page .form-actions {
        flex-direction: column;
        align-items: stretch;
        padding: 14px 15px;
    }

    .student-fee-page .action-buttons {
        flex-direction: column-reverse;
    }

    .student-fee-page .action-buttons > * {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.student-fee-page :focus-visible {
    outline: 3px solid rgba(82,127,121,.45);
    outline-offset: 3px;
}

@media(prefers-reduced-motion: reduce) {
    .student-fee-page *,
    .student-fee-page *::before,
    .student-fee-page *::after {
        transition: none !important;
        animation: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('student_id');
    const academicYear = document.getElementById('academic_year');

    const classNote = document.getElementById('classNote');

    const studentPreview = document.getElementById('studentPreview');
    const studentAvatar = document.getElementById('studentAvatar');
    const studentName = document.getElementById('studentName');
    const studentDetails = document.getElementById('studentDetails');

    const studentIdDisplay = document.getElementById('display_student_id');
    const classDisplay = document.getElementById('display_class');
    const sectionDisplay = document.getElementById('display_section');

    const feeStructure = document.getElementById('fee_structure_id');
    const structureDetails = document.getElementById('structureDetails');
    const feeItems = document.getElementById('feeItems');

    const discount = document.getElementById('discount_amount');
    const fine = document.getElementById('fine_amount');

    const totalDisplay = document.getElementById('totalDisplay');
    const discountDisplay = document.getElementById('discountDisplay');
    const fineDisplay = document.getElementById('fineDisplay');
    const balanceDisplay = document.getElementById('balanceDisplay');

    const form = document.getElementById('studentFeeForm');
    const submitButton = document.getElementById('submitButton');

    const allStudentOptions = Array.from(
        studentSelect.querySelectorAll('option[data-student-id]')
    ).map(option => option.cloneNode(true));

    const oldStudentId = @json(old('student_id'));
    const oldClassId = @json(old('class_id'));

    const oldDiscount = @json(old('discount_amount', 0));
    const oldFine = @json(old('fine_amount', 0));

    let isSubmitting = false;

    /* MONEY FORMAT */

    function formatMoney(value) {
        return '₹' + Number(value || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    /* RESET STUDENT */

    function resetStudent() {

        studentSelect.innerHTML =
            '<option value="">-- Select Class First --</option>';

        studentSelect.disabled = true;

        studentPreview.classList.remove('show');

        studentIdDisplay.value = '';
        classDisplay.value = '';
        sectionDisplay.value = '';

        studentAvatar.innerHTML =
            '<i class="fas fa-user"></i>';

        classNote.innerHTML =
            '<i class="fas fa-info-circle"></i>' +
            'Students will appear after selecting a class.';
    }

    /* LOAD STUDENTS */

    function loadStudentsForClass() {

        const selectedClassId = classSelect.value;

        const selectedClassName =
            classSelect.options[classSelect.selectedIndex]
                ?.dataset.className || '';

        resetStudent();

        if (!selectedClassId) {
            return;
        }

        const matchingStudents = allStudentOptions.filter(option => {

            const studentClass =
                (option.dataset.class || '').trim().toLowerCase();

            const className =
                selectedClassName.trim().toLowerCase();

            return studentClass === className ||
                   studentClass === String(selectedClassId);
        });

        studentSelect.innerHTML = '';

        const defaultOption = document.createElement('option');

        defaultOption.value = '';

        defaultOption.textContent = matchingStudents.length
            ? '-- Select Student --'
            : '-- No Students Found In This Class --';

        studentSelect.appendChild(defaultOption);

        matchingStudents.forEach(option => {
            studentSelect.appendChild(option.cloneNode(true));
        });

        studentSelect.disabled = matchingStudents.length === 0;

        classNote.innerHTML = matchingStudents.length
            ? '<i class="fas fa-check-circle"></i>' +
              matchingStudents.length + ' student(s) found in this class.'
            : '<i class="fas fa-exclamation-circle"></i>' +
              'No students found in the selected class.';

        if (oldStudentId && matchingStudents.some(option =>
            option.value == oldStudentId
        )) {
            studentSelect.value = oldStudentId;
            updateStudent();
        }
    }

    /* UPDATE STUDENT PREVIEW */

    function updateStudent() {

        const option = studentSelect.options[
            studentSelect.selectedIndex
        ];

        if (!option || !option.value) {

            studentPreview.classList.remove('show');

            studentIdDisplay.value = '';
            classDisplay.value = '';
            sectionDisplay.value = '';

            return;
        }

        const id = option.dataset.studentId || '';
        const name = option.dataset.name || 'Student';
        const year = option.dataset.year || '';
        const className = option.dataset.class || '';
        const section = option.dataset.section || '';
        const image = option.dataset.image || '';

        studentIdDisplay.value = id;
        classDisplay.value = className || 'Not available';
        sectionDisplay.value = section || 'Not available';

        studentName.textContent = name;

        let details = 'ID: ' + id;

        if (year) details += ' • Academic Year: ' + year;
        if (className) details += ' • Class: ' + className;
        if (section) details += ' • Section: ' + section;

        studentDetails.textContent = details;

        studentAvatar.innerHTML = '';

        if (image) {

            const img = document.createElement('img');

            img.src = image;
            img.alt = name;

            img.onerror = function () {
                studentAvatar.textContent =
                    name.charAt(0).toUpperCase();
            };

            studentAvatar.appendChild(img);

        } else {

            studentAvatar.textContent =
                name.charAt(0).toUpperCase();
        }

        studentPreview.classList.add('show');

        if (year) {

            const yearOption = Array.from(
                academicYear.options
            ).find(item => item.value === year);

            if (yearOption) {
                academicYear.value = year;
            }
        }
    }

    /* FEE STRUCTURE */

    function updateFeeStructure() {

        const option = feeStructure.options[
            feeStructure.selectedIndex
        ];

        if (!option || !option.value) {

            structureDetails.classList.remove('show');
            feeItems.innerHTML = '';

            updateCalculation(0);

            return;
        }

        const total = Number(option.dataset.total || 0);

        feeItems.innerHTML = '';

        const item = document.createElement('div');

        item.className = 'fee-item';
        item.textContent = 'Total Fee: ' + formatMoney(total);

        feeItems.appendChild(item);

        structureDetails.classList.add('show');

        updateCalculation(total);
    }

    /* CALCULATION */

    function updateCalculation(suppliedTotal = null) {

        let total = 0;

        if (suppliedTotal !== null) {

            total = Number(suppliedTotal || 0);

        } else {

            const option = feeStructure.options[
                feeStructure.selectedIndex
            ];

            if (option && option.value) {
                total = Number(option.dataset.total || 0);
            }
        }

        const discountAmount = Math.max(
            0,
            Number(discount.value || 0)
        );

        const fineAmount = Math.max(
            0,
            Number(fine.value || 0)
        );

        const balance = Math.max(
            0,
            total - discountAmount + fineAmount
        );

        totalDisplay.textContent = formatMoney(total);

        discountDisplay.textContent =
            '- ' + formatMoney(discountAmount);

        fineDisplay.textContent =
            '+ ' + formatMoney(fineAmount);

        balanceDisplay.textContent =
            formatMoney(balance);
    }

    /* EVENTS */

    classSelect.addEventListener('change', function () {

        feeStructure.value = '';

        structureDetails.classList.remove('show');
        feeItems.innerHTML = '';

        updateCalculation(0);

        loadStudentsForClass();
    });

    studentSelect.addEventListener('change', updateStudent);

    feeStructure.addEventListener('change', updateFeeStructure);

    discount.addEventListener('input', function () {
        updateCalculation();
    });

    fine.addEventListener('input', function () {
        updateCalculation();
    });

    /* FORM SUBMISSION */

    form.addEventListener('submit', function (event) {

        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        if (!form.checkValidity()) {
            return;
        }

        isSubmitting = true;

        submitButton.disabled = true;

        submitButton.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });

    /* RESTORE OLD VALUES */

    discount.value = oldDiscount ?? 0;
    fine.value = oldFine ?? 0;

    if (oldClassId) {

        classSelect.value = oldClassId;

        loadStudentsForClass();

    } else {

        resetStudent();
    }

    updateFeeStructure();
    updateCalculation();

});
</script>

@endsection
