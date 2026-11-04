
@extends('layouts.app')

@section('title', 'Create Fee Structure | Admin')
@section('page-title', 'Create Fee Structure')

@section('content')

<div class="fee-create-page">
    <div class="container-fluid py-4">

        {{-- PAGE HERO --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        FEES <span>/</span> FEE STRUCTURES <span>/</span> CREATE
                    </div>

                    <h2>Create Fee Structure</h2>
                    <p>Configure academic fees, class, section and payment components.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.fees.fee-structures.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Fee Structures
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
                    <strong>Please correct the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button"
                        class="alert-close"
                        onclick="this.parentElement.remove();"
                        aria-label="Close alert">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.fees.fee-structures.store') }}"
              method="POST"
              id="feeStructureForm">
            @csrf

            {{-- BASIC INFORMATION --}}
            <section class="form-card">

                <div class="form-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-school"></i>
                        </div>

                        <div>
                            <div class="section-eyebrow">ACADEMIC DETAILS</div>
                            <h3>Basic Information</h3>
                            <p>Choose the academic year, class and section.</p>
                        </div>
                    </div>

                    <div class="required-info">
                        <span>*</span> Required fields
                    </div>
                </div>

                <div class="form-body">

                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-building"></i>
                            </div>

                            <div>
                                <h4>Class & Academic Details</h4>
                                <span>Define which students this fee structure applies to.</span>
                            </div>
                        </div>

                        <div class="form-grid">

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

                                        @foreach($academicYears ?? [] as $year)
                                            <option value="{{ $year }}"
                                                @selected((string)old('academic_year') === (string)$year)>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Academic years are taken from existing student data.
                                </div>

                                @error('academic_year')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- CLASS --}}
                            <div class="diary-filter-group">
                                <label for="class_id">
                                    Class <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-chalkboard input-icon"></i>

                                    <select name="class_id"
                                            id="class_id"
                                            class="diary-control with-icon @error('class_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Class</option>

                                        @foreach($classes ?? [] as $class)
                                            <option value="{{ $class->id }}"
                                                @selected((string)old('class_id') === (string)$class->id)>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Select a class to load its available sections.
                                </div>

                                @error('class_id')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SECTION --}}
                            <div class="diary-filter-group">
                                <label for="section_id">Section</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-users input-icon"></i>

                                    <select name="section_id"
                                            id="section_id"
                                            class="diary-control with-icon"
                                            disabled>
                                        <option value="">All Sections</option>
                                    </select>
                                </div>

                                <div class="field-help" id="sectionHelp">
                                    <i class="fas fa-circle-info"></i>
                                    Select a class first.
                                </div>

                                @error('section_id')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- STRUCTURE NAME --}}
                            <div class="diary-filter-group">
                                <label for="structure_name">
                                    Structure Name <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-file-signature input-icon"></i>

                                    <input type="text"
                                           name="structure_name"
                                           id="structure_name"
                                           class="diary-control with-icon @error('structure_name') is-invalid @enderror"
                                           placeholder="Example: 2026-27 Class 5 Fee Structure"
                                           value="{{ old('structure_name') }}"
                                           maxlength="255"
                                           required>
                                </div>

                                @error('structure_name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- STATUS --}}
                            <div class="diary-filter-group">
                                <label for="status">
                                    Status <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-toggle-on input-icon"></i>

                                    <select name="status"
                                            id="status"
                                            class="diary-control with-icon"
                                            required>
                                        <option value="Active"
                                            @selected(old('status', 'Active') === 'Active')>
                                            Active
                                        </option>

                                        <option value="Inactive"
                                            @selected(old('status') === 'Inactive')>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </section>

            {{-- FEE COMPONENTS --}}
            <section class="form-card fee-items-card">

                <div class="form-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>

                        <div>
                            <div class="section-eyebrow">FEE REGISTER</div>
                            <h3>Fee Components</h3>
                            <p>Add existing fee types or create custom fee components.</p>
                        </div>
                    </div>

                    <div class="fee-count-badge">
                        <i class="fas fa-list-ul"></i>
                        Fee Items
                    </div>
                </div>

                <div class="form-body">

                    <div class="fee-toolbar">
                        <div>
                            <h4>Fee Breakdown</h4>
                            <p>Enter the amount and optional due date for each fee.</p>
                        </div>

                        <button type="button"
                                class="diary-add-btn"
                                id="addFeeRow">
                            <i class="fas fa-plus"></i>
                            Add Fee
                        </button>
                    </div>

                    {{-- FEE TABLE --}}
                    <div class="fee-table-wrapper">
                        <table class="fee-table">
                            <thead>
                                <tr>
                                    <th style="width:36%">Fee Type</th>
                                    <th style="width:22%">Amount (₹)</th>
                                    <th style="width:25%">Due Date</th>
                                    <th style="width:12%;text-align:center">Action</th>
                                </tr>
                            </thead>

                            <tbody id="feeItemsBody">

                                @php
                                    $oldFeeTypes = old('fee_type_id', ['']);
                                    $oldAmounts = old('amount', []);
                                    $oldDueDates = old('due_date', []);
                                    $oldManualNames = old('manual_fee_name', []);
                                @endphp

                                @foreach($oldFeeTypes as $index => $oldFeeType)

                                    <tr class="fee-row">

                                        <td>
                                            <select name="fee_type_id[]"
                                                    class="diary-control fee-type-select"
                                                    required>
                                                <option value="">Select Fee Type</option>

                                                @foreach($feeTypes ?? [] as $feeType)
                                                    <option value="{{ $feeType->id }}"
                                                            data-amount="{{ $feeType->amount }}"
                                                            @selected((string)$oldFeeType === (string)$feeType->id)>
                                                        {{ $feeType->name }}
                                                    </option>
                                                @endforeach

                                                <option value="manual"
                                                    @selected((string)$oldFeeType === 'manual')>
                                                    + Manual / Custom Fee
                                                </option>
                                            </select>

                                            <div class="manual-label">Custom Fee Name</div>

                                            <input type="text"
                                                   name="manual_fee_name[]"
                                                   class="diary-control manual-fee-name"
                                                   value="{{ $oldManualNames[$index] ?? '' }}"
                                                   placeholder="Enter custom fee name"
                                                   maxlength="255">
                                        </td>

                                        <td>
                                            <div class="input-wrapper">
                                                <i class="fas fa-rupee-sign input-icon"></i>

                                                <input type="number"
                                                       name="amount[]"
                                                       class="diary-control with-icon amount-input"
                                                       value="{{ $oldAmounts[$index] ?? '' }}"
                                                       min="0"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       required>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-wrapper">
                                                <i class="fas fa-calendar input-icon"></i>

                                                <input type="date"
                                                       name="due_date[]"
                                                       class="diary-control with-icon"
                                                       value="{{ $oldDueDates[$index] ?? '' }}">
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                    class="remove-row-btn remove-fee-row"
                                                    title="Remove fee">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    {{-- MANUAL FEE INFORMATION --}}
                    <div class="manual-info">
                        <div class="manual-info-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>

                        <div>
                            <strong>Custom Fee Information</strong>
                            <p>
                                Choose <strong>Manual / Custom Fee</strong> when the required
                                fee type is not available in the existing list. Enter the
                                custom fee name and amount manually.
                            </p>
                        </div>
                    </div>

                    {{-- TOTAL --}}
                    <div class="total-box">
                        <div class="total-information">
                            <div class="total-icon">
                                <i class="fas fa-calculator"></i>
                            </div>

                            <div>
                                <strong>Total Fee Amount</strong>
                                <span>Calculated automatically from all fee items.</span>
                            </div>
                        </div>

                        <div class="total-amount">
                            <span class="currency-symbol">₹</span>
                            <span id="totalAmount">0.00</span>
                        </div>
                    </div>

                </div>
            </section>

            {{-- ACTIONS --}}
            <div class="form-actions">
                <a href="{{ route('admin.fees.fee-structures.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>

                <button type="submit"
                        id="saveButton"
                        class="diary-primary-btn">
                    <i class="fas fa-check"></i>
                    Save Fee Structure
                </button>
            </div>

        </form>
    </div>
</div>

<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM
   FEE STRUCTURE — CREATE
   Scoped exclusively to .fee-create-page
========================================================= */

.fee-create-page {
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
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.fee-create-page *,
.fee-create-page *::before,
.fee-create-page *::after {
    box-sizing: border-box;
}

.fee-create-page a,
.fee-create-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.fee-create-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.fee-create-page .form-hero,
.fee-create-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.fee-create-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38,59,53,.045);
}

.fee-create-page .form-hero::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189,81,72,.35);
}

.fee-create-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background: radial-gradient(ellipse at top left,rgba(244,240,223,.65),transparent 65%),var(--paper);
}

.fee-create-page .form-hero-icon {
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

.fee-create-page .form-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.fee-create-page .form-breadcrumb span {
    color: #b8b09e;
}

.fee-create-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
}

.fee-create-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.fee-create-page .form-hero-actions {
    padding: 0 28px;
}

/* BUTTONS */

.fee-create-page .diary-primary-btn,
.fee-create-page .diary-reset-btn,
.fee-create-page .diary-add-btn {
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
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.fee-create-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: white;
    background: var(--ink);
}

.fee-create-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: white;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29,48,43,.14);
}

.fee-create-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
}

.fee-create-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d;
    background: #fffefa;
}

.fee-create-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink);
    background: #f5f4eb;
}

.fee-create-page .diary-add-btn {
    border: 1px solid var(--ink);
    color: white;
    background: var(--ink);
}

.fee-create-page .diary-add-btn:hover {
    transform: translateY(-2px);
    background: var(--ink-hover);
}

/* ALERT */

.fee-create-page .form-error-alert {
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

.fee-create-page .error-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.fee-create-page .error-copy {
    flex: 1;
    min-width: 0;
}

.fee-create-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
}

.fee-create-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    line-height: 1.7;
}

.fee-create-page .alert-close {
    border: 0;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

/* FORM CARD */

.fee-create-page .form-card {
    overflow: hidden;
    margin-bottom: 23px;
    box-shadow: 0 5px 17px rgba(38,59,53,.04);
}

.fee-create-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
}

.fee-create-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.fee-create-page .panel-title-icon {
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
}

.fee-create-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.fee-create-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.fee-create-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
}

.fee-create-page .required-info,
.fee-create-page .fee-count-badge {
    flex-shrink: 0;
    padding: 7px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
}

.fee-create-page .required-info span {
    color: var(--red);
}

.fee-create-page .form-body {
    padding: 24px 22px;
}

.fee-create-page .form-section {
    padding-bottom: 12px;
}

.fee-create-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.fee-create-page .section-heading-icon {
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
}

.fee-create-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
}

.fee-create-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
}

/* GRID */

.fee-create-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    align-items: start;
    gap: 20px 16px;
}

.fee-create-page .diary-filter-group {
    min-width: 0;
}

.fee-create-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.fee-create-page .required {
    color: var(--red);
}

.fee-create-page .input-wrapper {
    position: relative;
    width: 100%;
}

.fee-create-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
}

.fee-create-page .diary-control {
    display: block;
    width: 100%;
    min-width: 0;
    height: 41px;
    padding: 0 11px;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
}

.fee-create-page .diary-control.with-icon {
    padding-left: 35px;
}

.fee-create-page select.diary-control {
    cursor: pointer;
}

.fee-create-page .diary-control:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58,128,96,.11);
}

.fee-create-page .diary-control:disabled {
    background: #f5f4eb;
    cursor: not-allowed;
    opacity: .75;
}

.fee-create-page .diary-control.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.fee-create-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.fee-create-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
}

.fee-create-page .field-error {
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
}

/* FEE TOOLBAR */

.fee-create-page .fee-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 17px;
}

.fee-create-page .fee-toolbar h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
}

.fee-create-page .fee-toolbar p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
}

/* TABLE */

.fee-create-page .fee-table-wrapper {
    width: 100%;
    overflow-x: auto;
    border: 1px solid var(--line);
    border-radius: 8px;
}

.fee-create-page .fee-table {
    width: 100%;
    min-width: 760px;
    border-collapse: collapse;
    background: #fffefa;
}

.fee-create-page .fee-table thead th {
    padding: 13px 12px;
    border-bottom: 1px solid var(--line);
    color: #59675d;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    white-space: nowrap;
}

.fee-create-page .fee-table td {
    padding: 12px;
    border-bottom: 1px solid #eeeade;
    vertical-align: top;
}

.fee-create-page .fee-table tbody tr:last-child td {
    border-bottom: 0;
}

.fee-create-page .fee-table .diary-control {
    min-width: 130px;
}

.fee-create-page .fee-table select.diary-control {
    min-width: 200px;
}

.fee-create-page .fee-table .input-wrapper .diary-control {
    min-width: 130px;
}

/* MANUAL FEE */

.fee-create-page .manual-fee-name {
    display: none;
    margin-top: 9px;
}

.fee-create-page .manual-label {
    display: none;
    margin-top: 9px;
    margin-bottom: 6px;
    color: var(--amber);
    font-size: 10px;
    font-weight: 800;
}

.fee-create-page .manual-info {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 17px;
    padding: 13px 15px;
    border: 1px solid #e6dfc9;
    border-radius: 8px;
    color: #765e32;
    background: #faf5e7;
}

.fee-create-page .manual-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    border: 1px solid #e6dfc9;
    border-radius: 8px;
    background: white;
}

.fee-create-page .manual-info strong {
    font-size: 11px;
}

.fee-create-page .manual-info p {
    margin: 4px 0 0;
    color: #8a7447;
    font-size: 10px;
    line-height: 1.7;
}

/* REMOVE BUTTON */

.fee-create-page .remove-row-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    border: 1px solid #efd2cd;
    border-radius: 7px;
    color: var(--red);
    background: #fffefa;
    cursor: pointer;
}

.fee-create-page .remove-row-btn:hover {
    border-color: var(--red);
    color: white;
    background: var(--red);
}

/* TOTAL */

.fee-create-page .total-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-top: 19px;
    padding: 17px 20px;
    border: 1px solid #d9dfcf;
    border-radius: 9px;
    background: #f5f6ed;
}

.fee-create-page .total-information {
    display: flex;
    align-items: center;
    gap: 12px;
}

.fee-create-page .total-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 39px;
    width: 39px;
    height: 39px;
    border: 1px solid #d9dfcf;
    border-radius: 9px;
    color: var(--green);
    background: white;
}

.fee-create-page .total-information strong {
    display: block;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.fee-create-page .total-information span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 10px;
}

.fee-create-page .total-amount {
    color: var(--ink);
    font-size: 24px;
    font-weight: 800;
    white-space: nowrap;
}

.fee-create-page .currency-symbol {
    color: var(--green);
}

/* ACTIONS */

.fee-create-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 0 25px;
}

/* RESPONSIVE */

@media(max-width:900px) {
    .fee-create-page .form-hero {
        grid-template-columns: 1fr;
    }

    .fee-create-page .form-hero-actions {
        padding: 0 28px 22px 56px;
    }
}

@media(max-width:650px) {
    .fee-create-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .fee-create-page .form-hero::before {
        left: 22px;
    }

    .fee-create-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .fee-create-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        font-size: 18px;
    }

    .fee-create-page .form-hero-text h2 {
        font-size: 23px;
    }

    .fee-create-page .form-hero-text p {
        font-size: 12px;
    }

    .fee-create-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .fee-create-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .fee-create-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .fee-create-page .required-info,
    .fee-create-page .fee-count-badge {
        display: none;
    }

    .fee-create-page .form-body {
        padding: 18px 15px;
    }

    .fee-create-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .fee-create-page .fee-toolbar {
        align-items: flex-start;
        flex-direction: column;
    }

    .fee-create-page .diary-add-btn {
        width: 100%;
    }

    .fee-create-page .total-box {
        align-items: flex-start;
        flex-direction: column;
    }

    .fee-create-page .total-amount {
        font-size: 22px;
    }

    .fee-create-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .fee-create-page .form-actions > * {
        width: 100%;
    }

    .fee-create-page .diary-control {
        font-size: 16px;
    }
}

@media(prefers-reduced-motion:reduce) {
    .fee-create-page *,
    .fee-create-page *::before,
    .fee-create-page *::after {
        transition: none !important;
        animation: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       CLASS AND SECTION AJAX
    ===================================================== */

    const classEl = document.getElementById('class_id');
    const sectionEl = document.getElementById('section_id');
    const sectionHelp = document.getElementById('sectionHelp');

    const selectedSection = @json(old('section_id', ''));

    const sectionsUrl = "{{ route('admin.fees.fee-structures.sections', ['class' => '__ID__']) }}";

    async function loadSections() {

        sectionEl.innerHTML = '<option value="">All Sections</option>';
        sectionEl.disabled = true;

        if (!classEl.value) {
            sectionHelp.innerHTML =
                '<i class="fas fa-circle-info"></i> Select a class first.';
            return;
        }

        sectionHelp.textContent = 'Loading sections...';

        try {
            const response = await fetch(
                sectionsUrl.replace('__ID__', encodeURIComponent(classEl.value)),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('HTTP error: ' + response.status);
            }

            let result = await response.text();

            result = result.replace(/^\uFEFF/, '').trim();

            const sections = JSON.parse(result);

            if (!Array.isArray(sections)) {
                throw new Error('Invalid sections response');
            }

            sections.forEach(section => {
                const option = new Option(
                    section.section_name,
                    section.id
                );

                if (String(section.id) === String(selectedSection)) {
                    option.selected = true;
                }

                sectionEl.add(option);
            });

            sectionEl.disabled = false;

            sectionHelp.innerHTML = sections.length
                ? '<i class="fas fa-circle-info"></i> Select a section or choose All Sections.'
                : '<i class="fas fa-circle-info"></i> No sections found for this class.';

        } catch (error) {
            console.error('Could not load sections:', error);

            sectionHelp.textContent =
                'Unable to load sections. Please try again.';
        }
    }

    classEl.addEventListener('change', function () {
        loadSections();
    });

    if (classEl.value) {
        loadSections();
    }

    /* =====================================================
       FEE ROWS
    ===================================================== */

    const feeBody = document.getElementById('feeItemsBody');
    const addFeeButton = document.getElementById('addFeeRow');
    const totalAmount = document.getElementById('totalAmount');

    function updateManualField(row) {

        const feeSelect = row.querySelector('.fee-type-select');
        const manualInput = row.querySelector('.manual-fee-name');
        const manualLabel = row.querySelector('.manual-label');
        const isManual = feeSelect.value === 'manual';

        manualInput.style.display = isManual ? 'block' : 'none';
        manualLabel.style.display = isManual ? 'block' : 'none';

        manualInput.required = isManual;

        if (!isManual) {
            manualInput.classList.remove('is-invalid');
        }
    }

    function updateTotal() {

        let total = 0;

        feeBody.querySelectorAll('.amount-input').forEach(input => {
            const amount = parseFloat(input.value);

            if (!isNaN(amount) && amount > 0) {
                total += amount;
            }
        });

        totalAmount.textContent = total.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function createFeeRow() {

        const row = document.createElement('tr');
        row.className = 'fee-row';

        row.innerHTML = `
            <td>
                <select name="fee_type_id[]"
                        class="diary-control fee-type-select"
                        required>
                    <option value="">Select Fee Type</option>

                    @foreach($feeTypes ?? [] as $feeType)
                        <option value="{{ $feeType->id }}"
                                data-amount="{{ $feeType->amount }}">
                            {{ $feeType->name }}
                        </option>
                    @endforeach

                    <option value="manual">+ Manual / Custom Fee</option>
                </select>

                <div class="manual-label">Custom Fee Name</div>

                <input type="text"
                       name="manual_fee_name[]"
                       class="diary-control manual-fee-name"
                       placeholder="Enter custom fee name"
                       maxlength="255">
            </td>

            <td>
                <div class="input-wrapper">
                    <i class="fas fa-rupee-sign input-icon"></i>
                    <input type="number"
                           name="amount[]"
                           class="diary-control with-icon amount-input"
                           min="0"
                           step="0.01"
                           placeholder="0.00"
                           required>
                </div>
            </td>

            <td>
                <div class="input-wrapper">
                    <i class="fas fa-calendar input-icon"></i>
                    <input type="date"
                           name="due_date[]"
                           class="diary-control with-icon">
                </div>
            </td>

            <td class="text-center">
                <button type="button"
                        class="remove-row-btn remove-fee-row"
                        title="Remove fee">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        feeBody.appendChild(row);

        updateManualField(row);
        updateTotal();

        row.querySelector('.fee-type-select').focus();
    }

    addFeeButton.addEventListener('click', createFeeRow);

    feeBody.addEventListener('change', function (event) {

        if (event.target.classList.contains('fee-type-select')) {

            const row = event.target.closest('.fee-row');
            const selectedOption = event.target.selectedOptions[0];

            updateManualField(row);

            // Automatically fill the default amount when available.
            if (selectedOption && selectedOption.dataset.amount !== undefined
                && selectedOption.dataset.amount !== '') {

                const amountInput = row.querySelector('.amount-input');

                if (!amountInput.value) {
                    amountInput.value = selectedOption.dataset.amount;
                }
            }

            updateTotal();
        }
    });

    feeBody.addEventListener('input', function (event) {

        if (event.target.classList.contains('amount-input')) {
            updateTotal();
        }
    });

    feeBody.addEventListener('click', function (event) {

        const removeButton = event.target.closest('.remove-fee-row');

        if (!removeButton) return;

        const rows = feeBody.querySelectorAll('.fee-row');

        if (rows.length <= 1) {
            alert('At least one fee item is required.');
            return;
        }

        removeButton.closest('.fee-row').remove();
        updateTotal();
    });

    // Restore manual fields and calculate total on page load.
    feeBody.querySelectorAll('.fee-row').forEach(row => {
        updateManualField(row);
    });

    updateTotal();

    /* =====================================================
       FORM SUBMISSION
    ===================================================== */

    const form = document.getElementById('feeStructureForm');
    const saveButton = document.getElementById('saveButton');

    form.addEventListener('submit', function (event) {

        // Validate manual fee names.
        let valid = true;

        feeBody.querySelectorAll('.fee-row').forEach(row => {

            const feeSelect = row.querySelector('.fee-type-select');
            const manualInput = row.querySelector('.manual-fee-name');

            if (feeSelect.value === 'manual' && !manualInput.value.trim()) {
                manualInput.classList.add('is-invalid');
                valid = false;
            } else {
                manualInput.classList.remove('is-invalid');
            }
        });

        if (!valid) {
            event.preventDefault();
            alert('Please enter a name for every manual fee.');
            return;
        }

        saveButton.disabled = true;
        saveButton.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });

});
</script>

@endsection
