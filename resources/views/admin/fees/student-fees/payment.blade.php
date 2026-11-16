@extends('layouts.app')

@section('title', 'Collect Student Fee Payment | Admin')
@section('page-title', 'Collect Fee Payment')

@section('content')

@php
    $student = $studentFee->student;

    $studentName = trim(
        ($student->first_name ?? '') . ' ' .
        ($student->middle_name ?? '') . ' ' .
        ($student->last_name ?? '')
    );

    $structure = $studentFee->feeStructure;

    $className = optional(optional($structure)->schoolClass)->class_name ?? '-';

    $sectionName = optional(optional($structure)->section)->section_name
        ?? 'All Sections';

    $balance = (float) $studentFee->balance_amount;

    $methods = [
        'Cash'          => 'fa-money-bill-wave',
        'UPI'           => 'fa-mobile-screen',
        'Card'          => 'fa-credit-card',
        'Bank Transfer' => 'fa-building-columns',
        'Cheque'        => 'fa-money-check',
    ];
@endphp

<div class="student-fee-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        FEES <span>/</span> STUDENT FEES <span>/</span> COLLECT PAYMENT
                    </div>

                    <h2>Collect Fee Payment</h2>

                    <p>
                        Record a payment for the selected student fee and
                        review the remaining balance.
                    </p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.fees.student-fees.show', $studentFee) }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Fee Details
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
              action="{{ route('admin.fees.student-fees.payments.store', $studentFee) }}"
              id="paymentForm">

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
                            <p>Enter the amount received and how it was paid.</p>
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
                                <span>The student this payment is being recorded for.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- STUDENT PREVIEW --}}
                            <div class="form-grid-full">
                                <div class="student-preview show">

                                    <div class="student-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div class="student-preview-content">
                                        <div class="student-preview-name">
                                            {{ $studentName ?: '-' }}
                                        </div>

                                        <div class="student-preview-details">
                                            ID: {{ $student->student_id ?? '-' }}
                                            &bull; Academic Year: {{ $studentFee->academic_year }}
                                            &bull; Class: {{ $className }}
                                            &bull; Section: {{ $sectionName }}
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
                                           value="{{ $student->student_id ?? '-' }}"
                                           readonly>
                                </div>
                            </div>

                            {{-- ACADEMIC YEAR --}}
                            <div class="diary-filter-group">
                                <label for="display_year">Academic Year</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-alt input-icon"></i>

                                    <input type="text"
                                           id="display_year"
                                           class="diary-control with-icon is-readonly"
                                           value="{{ $studentFee->academic_year }}"
                                           readonly>
                                </div>
                            </div>

                            {{-- CLASS --}}
                            <div class="diary-filter-group">
                                <label for="display_class">Student Class</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-school input-icon"></i>

                                    <input type="text"
                                           id="display_class"
                                           class="diary-control with-icon is-readonly"
                                           value="{{ $className }}"
                                           readonly>
                                </div>
                            </div>

                            {{-- SECTION --}}
                            <div class="diary-filter-group">
                                <label for="display_section">Student Section</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-layer-group input-icon"></i>

                                    <input type="text"
                                           id="display_section"
                                           class="diary-control with-icon is-readonly"
                                           value="{{ $sectionName }}"
                                           readonly>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- =====================================================
                         SECTION 2: PAYMENT DETAILS
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon purple">
                                <i class="fas fa-wallet"></i>
                            </div>

                            <div>
                                <h4>Payment Details</h4>
                                <span>Enter the amount, date and payment method.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- AMOUNT --}}
                            <div class="diary-filter-group">
                                <label for="paymentAmount">
                                    Payment Amount <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-indian-rupee-sign input-icon"></i>

                                    <input type="number"
                                           name="amount"
                                           id="paymentAmount"
                                           class="diary-control with-icon @error('amount') is-invalid @enderror"
                                           value="{{ old('amount') }}"
                                           min="0.01"
                                           max="{{ $balance }}"
                                           step="0.01"
                                           placeholder="Enter payment amount"
                                           required>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Maximum payable amount: &#8377;{{ number_format($balance, 2) }}
                                </div>

                                @error('amount')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DATE --}}
                            <div class="diary-filter-group">
                                <label for="payment_date">
                                    Payment Date <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-day input-icon"></i>

                                    <input type="date"
                                           name="payment_date"
                                           id="payment_date"
                                           class="diary-control with-icon @error('payment_date') is-invalid @enderror"
                                           value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                           required>
                                </div>

                                @error('payment_date')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PAYMENT METHOD --}}
                            <div class="diary-filter-group form-grid-full">
                                <label>
                                    Payment Method <span class="required">*</span>
                                </label>

                                <div class="payment-methods">

                                    @foreach($methods as $method => $icon)

                                        <div class="method-option">

                                            <input type="radio"
                                                   name="payment_method"
                                                   id="method_{{ Str::slug($method) }}"
                                                   value="{{ $method }}"
                                                   {{ old('payment_method', 'Cash') === $method ? 'checked' : '' }}>

                                            <label for="method_{{ Str::slug($method) }}">
                                                <i class="fas {{ $icon }}"></i>
                                                {{ $method }}
                                            </label>

                                        </div>

                                    @endforeach

                                </div>

                                @error('payment_method')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- TRANSACTION REFERENCE --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="transaction_reference">Transaction Reference</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-hashtag input-icon"></i>

                                    <input type="text"
                                           name="transaction_reference"
                                           id="transaction_reference"
                                           class="diary-control with-icon @error('transaction_reference') is-invalid @enderror"
                                           value="{{ old('transaction_reference') }}"
                                           maxlength="255"
                                           placeholder="UPI ID, cheque number, bank reference, etc.">
                                </div>

                                @error('transaction_reference')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Optional. Useful for UPI, card, bank transfer and cheque payments.
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- =====================================================
                         SECTION 3: FEE CALCULATION
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon green">
                                <i class="fas fa-calculator"></i>
                            </div>

                            <div>
                                <h4>Fee Calculation & Remarks</h4>
                                <span>Review the fee status and add any remarks.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- CALCULATION SUMMARY --}}
                            <div class="calculation-box form-grid-full">

                                <div class="calculation-title">
                                    <i class="fas fa-receipt"></i>
                                    Fee Calculation Summary
                                </div>

                                <div class="calculation-row">
                                    <span>Total Fee</span>
                                    <strong>&#8377;{{ number_format((float) $studentFee->total_amount, 2) }}</strong>
                                </div>

                                <div class="calculation-row">
                                    <span>Concession</span>
                                    <strong class="discount-value">
                                        - &#8377;{{ number_format((float) $studentFee->discount_amount, 2) }}
                                    </strong>
                                </div>

                                <div class="calculation-row">
                                    <span>Late Fine</span>
                                    <strong class="fine-value">
                                        + &#8377;{{ number_format((float) $studentFee->fine_amount, 2) }}
                                    </strong>
                                </div>

                                <div class="calculation-row">
                                    <span>Already Paid</span>
                                    <strong>&#8377;{{ number_format((float) $studentFee->paid_amount, 2) }}</strong>
                                </div>

                                <div class="calculation-row">
                                    <span>Current Balance</span>
                                    <strong>&#8377;{{ number_format($balance, 2) }}</strong>
                                </div>

                                <div class="calculation-total balance">
                                    <span>Balance After This Payment</span>
                                    <strong id="balanceDisplay">&#8377;{{ number_format($balance, 2) }}</strong>
                                </div>

                            </div>

                            {{-- REMARKS --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="remarks">Remarks</label>

                                <textarea name="remarks"
                                          id="remarks"
                                          class="diary-textarea @error('remarks') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Add any payment remarks...">{{ old('remarks') }}</textarea>

                                @error('remarks')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">

                    <div class="action-note">
                        <i class="fas fa-shield-alt"></i>
                        Paid amount, balance and fee status update automatically after saving.
                    </div>

                    <div class="action-buttons">

                        <a href="{{ route('admin.fees.student-fees.show', $studentFee) }}"
                           class="diary-reset-btn">
                            <i class="fas fa-xmark"></i>
                            Cancel
                        </a>

                        <button type="submit"
                                id="submitButton"
                                class="diary-primary-btn"
                                {{ $balance <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-check"></i>
                            Save Payment
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
   STUDENT FEES - COLLECT PAYMENT
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

/* PAYMENT METHODS */

.student-fee-page .payment-methods {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 9px;
}

.student-fee-page .method-option {
    position: relative;
}

.student-fee-page .method-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.student-fee-page .diary-filter-group .method-option label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 41px;
    margin: 0;
    padding: 8px;
    text-align: center;
    border: 1px solid #deded2;
    border-radius: 7px;
    color: #59675d;
    background: #fffefa;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: all .18s ease;
}

.student-fee-page .diary-filter-group .method-option label:hover {
    border-color: #b7c4b7;
    background: #f5f4eb;
}

.student-fee-page .method-option input:checked + label {
    border-color: var(--green);
    color: var(--green);
    background: #edf4e9;
    box-shadow: 0 0 0 3px rgba(58,128,96,.11);
}

.student-fee-page .method-option input:focus-visible + label {
    outline: 3px solid rgba(82,127,121,.45);
    outline-offset: 2px;
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

.student-fee-page .calculation-total.balance {
    border-color: #ecd9bd;
    background: #fbf3e6;
}

.student-fee-page .calculation-total.balance strong {
    color: var(--amber);
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

    .student-fee-page .payment-methods {
        grid-template-columns: repeat(3, minmax(0, 1fr));
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

    .student-fee-page .payment-methods {
        grid-template-columns: repeat(2, minmax(0, 1fr));
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

    const amountInput = document.getElementById('paymentAmount');
    const balanceDisplay = document.getElementById('balanceDisplay');

    const form = document.getElementById('paymentForm');
    const submitButton = document.getElementById('submitButton');

    const currentBalance = Number(@json($balance));

    let isSubmitting = false;

    /* MONEY FORMAT */

    function formatMoney(value) {
        return '₹' + Number(value || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    /* LIVE BALANCE */

    function updateBalance() {

        let payment = parseFloat(amountInput.value) || 0;

        if (payment < 0) {
            payment = 0;
        }

        if (payment > currentBalance) {
            payment = currentBalance;
            amountInput.value = currentBalance.toFixed(2);
        }

        balanceDisplay.textContent =
            formatMoney(Math.max(0, currentBalance - payment));
    }

    amountInput.addEventListener('input', updateBalance);

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

    updateBalance();

});
</script>

@endsection