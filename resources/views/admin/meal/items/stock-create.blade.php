@extends('layouts.app')

@section('title', 'Stock In / Out | Admin')
@section('page-title', 'Stock In / Out')

@section('content')

<div class="meal-stock-form-page">
    <div class="container-fluid py-4">

    
{{-- BACK BUTTON --}}
<div class="stock-back-row">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.meal.items.index') }}"
       class="stock-back-btn">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
    </a>
</div>


        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-right-left"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        INVENTORY <span>/</span> STOCK MOVEMENTS <span>/</span> NEW TRANSACTION
                    </div>

                    <h2>Stock In / Out</h2>

                    <p>Record stock purchases, usage and inventory movements.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.meal.items.stock.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-clock-rotate-left"></i>
                    Stock History
                </a>

                <a href="{{ route('admin.meal.items.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-boxes-stacked"></i>
                    Meal Items
                </a>
            </div>
        </section>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="form-success-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>

                <div class="alert-copy">
                    <strong>Transaction completed</strong>
                    <span>{{ session('success') }}</span>
                </div>

                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="alert-copy">
                    <strong>Please check the form</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- MAIN FORM CARD --}}
        <section class="form-card">

            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-right-left"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">INVENTORY MANAGEMENT</div>
                        <h3>Stock Transaction</h3>
                        <p>Add or remove inventory for an existing meal item.</p>
                    </div>
                </div>

                <div class="record-pill">
                    <i class="fas fa-shield-halved"></i>
                    Inventory Record
                </div>
            </div>

            <form action="{{ route('admin.meal.items.stock.store') }}"
                  method="POST"
                  id="stockTransactionForm">
                @csrf

                <div class="form-body">

                    {{-- ITEM AND TRANSACTION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-box"></i>
                            </div>

                            <div>
                                <h4>Item &amp; Transaction</h4>
                                <span>Select the meal item and choose the inventory movement.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- MEAL ITEM --}}
                            <div class="diary-filter-group">
                                <label for="meal_item_id">
                                    Meal Item <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-box input-icon"></i>

                                    <select name="meal_item_id"
                                            id="meal_item_id"
                                            class="diary-control with-icon @error('meal_item_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select a meal item</option>

                                        @foreach($mealItems as $item)
                                            <option value="{{ $item->id }}"
                                                    data-stock="{{ $item->current_stock }}"
                                                    data-unit="{{ $item->unit }}"
                                                    @selected(old('meal_item_id') == $item->id)>
                                                {{ $item->item_name }}@if($item->category) — {{ $item->category }}@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Only active meal items are shown.
                                </div>

                                @error('meal_item_id')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- TRANSACTION TYPE --}}
                            <div class="diary-filter-group">
                                <span class="group-label">
                                    Transaction Type <span class="required">*</span>
                                </span>

                                <div class="transaction-type-wrapper"
                                     role="radiogroup"
                                     aria-label="Transaction type">

                                    <label class="transaction-option stock-in-option">
                                        <input type="radio"
                                               name="transaction_type"
                                               value="stock_in"
                                               {{ old('transaction_type', 'stock_in') === 'stock_in' ? 'checked' : '' }}>

                                        <span class="transaction-option-content">
                                            <span class="transaction-option-icon">
                                                <i class="fas fa-arrow-down"></i>
                                            </span>

                                            <span class="transaction-option-text">
                                                <strong>Stock In</strong>
                                                <small>Add quantity to inventory</small>
                                            </span>

                                            <span class="transaction-check">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </span>
                                    </label>

                                    <label class="transaction-option stock-out-option">
                                        <input type="radio"
                                               name="transaction_type"
                                               value="stock_out"
                                               {{ old('transaction_type') === 'stock_out' ? 'checked' : '' }}>

                                        <span class="transaction-option-content">
                                            <span class="transaction-option-icon">
                                                <i class="fas fa-arrow-up"></i>
                                            </span>

                                            <span class="transaction-option-text">
                                                <strong>Stock Out</strong>
                                                <small>Reduce quantity from inventory</small>
                                            </span>

                                            <span class="transaction-check">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </span>
                                    </label>

                                </div>

                                @error('transaction_type')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- QUANTITY AND STOCK --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-scale-balanced"></i>
                            </div>

                            <div>
                                <h4>Quantity &amp; Stock</h4>
                                <span>Enter the quantity and verify the resulting inventory.</span>
                            </div>
                        </div>

                        <div class="form-grid cols-3">

                            {{-- CURRENT STOCK --}}
                            <div class="diary-filter-group">
                                <span class="group-label">Current Stock</span>

                                <div class="current-stock-box">
                                    <div class="current-stock-icon">
                                        <i class="fas fa-cubes"></i>
                                    </div>

                                    <div class="current-stock-info">
                                        <div class="current-stock-value" id="currentStockValue">0</div>
                                        <div class="current-stock-label" id="currentStockUnit">Unit</div>
                                    </div>

                                    <span class="live-badge">Live</span>
                                </div>
                            </div>

                            {{-- QUANTITY --}}
                            <div class="diary-filter-group">
                                <label for="quantity">
                                    Quantity <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-scale-balanced input-icon"></i>

                                    <input type="number"
                                           name="quantity"
                                           id="quantity"
                                           value="{{ old('quantity') }}"
                                           class="diary-control with-icon @error('quantity') is-invalid @enderror"
                                           min="0.01"
                                           step="0.01"
                                           placeholder="Enter quantity"
                                           required>
                                </div>

                                <div class="field-help" id="quantityHelp">
                                    <i class="fas fa-circle-info"></i>
                                    Select a meal item first.
                                </div>

                                <div class="field-error" id="quantityError" role="alert" hidden></div>

                                @error('quantity')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DATE --}}
                            <div class="diary-filter-group">
                                <label for="transaction_date">
                                    Transaction Date <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-days input-icon"></i>

                                    <input type="date"
                                           name="transaction_date"
                                           id="transaction_date"
                                           value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                           class="diary-control with-icon @error('transaction_date') is-invalid @enderror"
                                           required>
                                </div>

                                @error('transaction_date')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        {{-- INVENTORY PREVIEW --}}
                        <div class="stock-preview-card">
                            <div class="stock-preview-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>

                            <div class="stock-preview-content">
                                <div class="stock-preview-title">Inventory Preview</div>

                                <div class="stock-preview-text"
                                     id="stockPreviewText"
                                     aria-live="polite">
                                    Select an item and enter quantity to preview the updated stock.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TRANSACTION DETAILS --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <div>
                                <h4>Transaction Details</h4>
                                <span>Add supplier or usage information.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- SUPPLIER --}}
                            <div class="diary-filter-group">
                                <label for="supplier">
                                    Supplier <span class="optional-badge">Optional</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-truck input-icon"></i>

                                    <input type="text"
                                           name="supplier"
                                           id="supplier"
                                           value="{{ old('supplier') }}"
                                           class="diary-control with-icon @error('supplier') is-invalid @enderror"
                                           maxlength="255"
                                           placeholder="Enter supplier name">
                                </div>

                                @error('supplier')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- REASON --}}
                            <div class="diary-filter-group">
                                <label for="reason">
                                    Reason <span class="optional-badge">Optional</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-comment-dots input-icon"></i>

                                    <input type="text"
                                           name="reason"
                                           id="reason"
                                           value="{{ old('reason') }}"
                                           class="diary-control with-icon @error('reason') is-invalid @enderror"
                                           maxlength="255"
                                           placeholder="Purchase / Daily Use">
                                </div>

                                @error('reason')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- REMARKS --}}
                            <div class="diary-filter-group span-full">
                                <div class="description-card">

                                    <div class="description-header">
                                        <div class="description-icon">
                                            <i class="fas fa-pen"></i>
                                        </div>

                                        <label for="remarks">Remarks</label>

                                        <span class="optional-badge">Optional</span>
                                    </div>

                                    <textarea name="remarks"
                                              id="remarks"
                                              rows="4"
                                              maxlength="1000"
                                              class="diary-textarea @error('remarks') is-invalid @enderror"
                                              placeholder="Add any additional information about this stock transaction...">{{ old('remarks') }}</textarea>

                                    <div class="description-footer">
                                        <div class="field-help">
                                            <i class="fas fa-lightbulb"></i>
                                            Additional notes for inventory records.
                                        </div>

                                        <span class="character-count" id="remarksCounter">0 / 1000</span>
                                    </div>

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

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <div class="footer-note">
                        <i class="fas fa-shield-halved"></i>
                        <span>Transaction will be added to the inventory log.</span>
                    </div>

                    <div class="footer-actions">
                        <a href="{{ route('admin.meal.items.stock.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-xmark"></i>
                            Cancel
                        </a>

                        <button type="submit"
                                class="diary-primary-btn"
                                id="submitBtn">
                            <i class="fas fa-check"></i>
                            Record Transaction
                        </button>
                    </div>
                </div>

            </form>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   MEAL STOCK IN / OUT — TRANSACTION FORM PAGE
   Same tokens, cards and buttons as Sports Achievements.
   Every selector is scoped under .meal-stock-form-page.
========================================================= */


/* BACK BUTTON — SCOPED TO STOCK FORM */
.meal-stock-form-page .stock-back-row {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 14px;
}

.meal-stock-form-page .stock-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 38px;
    padding: 9px 15px;
    border: 1px solid #deded2;
    border-radius: 7px;
    color: #59675d !important;
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none !important;
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease;
}

.meal-stock-form-page .stock-back-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
    transform: translateX(-2px);
}

.meal-stock-form-page .stock-back-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (max-width: 650px) {
    .meal-stock-form-page .stock-back-row {
        margin-bottom: 12px;
    }
}


.meal-stock-form-page {
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
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.meal-stock-form-page *,
.meal-stock-form-page *::before,
.meal-stock-form-page *::after {
    box-sizing: border-box;
}

.meal-stock-form-page a,
.meal-stock-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.meal-stock-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.meal-stock-form-page .form-hero,
.meal-stock-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */

.meal-stock-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.meal-stock-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.meal-stock-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.meal-stock-form-page .form-hero-icon {
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

.meal-stock-form-page .form-hero-text {
    min-width: 0;
}

.meal-stock-form-page .form-breadcrumb {
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

.meal-stock-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.meal-stock-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.meal-stock-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.meal-stock-form-page .form-hero-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */

.meal-stock-form-page .diary-primary-btn,
.meal-stock-form-page .diary-reset-btn {
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
}

.meal-stock-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.meal-stock-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.meal-stock-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.meal-stock-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.meal-stock-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ALERTS */

.meal-stock-form-page .form-success-alert,
.meal-stock-form-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
}

.meal-stock-form-page .form-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.meal-stock-form-page .form-error-alert {
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.meal-stock-form-page .success-icon,
.meal-stock-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.meal-stock-form-page .success-icon { background: #dcefd9; }
.meal-stock-form-page .error-icon { background: #fbe0d8; }

.meal-stock-form-page .alert-copy {
    flex: 1;
    min-width: 0;
}

.meal-stock-form-page .alert-copy strong {
    display: block;
    margin-bottom: 3px;
    font-weight: 800;
}

.meal-stock-form-page .alert-copy span {
    font-weight: 600;
}

.meal-stock-form-page .alert-copy ul {
    margin: 4px 0 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.meal-stock-form-page .alert-close {
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

.meal-stock-form-page .alert-close:hover {
    color: var(--ink);
    background: rgba(38, 59, 53, .07);
}

/* FORM CARD */

.meal-stock-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.meal-stock-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.meal-stock-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.meal-stock-form-page .panel-title-icon {
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

.meal-stock-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.meal-stock-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.meal-stock-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.meal-stock-form-page .record-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    flex-shrink: 0;
    min-height: 31px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.meal-stock-form-page .record-pill i {
    color: var(--green);
}

/* FORM BODY AND SECTIONS */

.meal-stock-form-page .form-body {
    padding: 24px 22px 8px;
}

.meal-stock-form-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.meal-stock-form-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.meal-stock-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.meal-stock-form-page .section-heading-icon {
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

.meal-stock-form-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.meal-stock-form-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM GRID AND FIELDS */

.meal-stock-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
    gap: 20px 16px;
}

.meal-stock-form-page .form-grid.cols-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.meal-stock-form-page .form-grid .span-full {
    grid-column: 1 / -1;
}

.meal-stock-form-page .diary-filter-group {
    min-width: 0;
}

.meal-stock-form-page .diary-filter-group > label,
.meal-stock-form-page .group-label {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.meal-stock-form-page .required {
    color: var(--red);
}

.meal-stock-form-page .optional-badge {
    padding: 2px 8px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #fffefa;
    font-size: 9px;
    font-weight: 700;
}

.meal-stock-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.meal-stock-form-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
    transition: color .18s ease;
}

.meal-stock-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.meal-stock-form-page .diary-control,
.meal-stock-form-page .diary-textarea {
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

.meal-stock-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.meal-stock-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.meal-stock-form-page select.diary-control {
    cursor: pointer;
}

.meal-stock-form-page .diary-control::placeholder,
.meal-stock-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.meal-stock-form-page .diary-control:hover,
.meal-stock-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.meal-stock-form-page .diary-control:focus,
.meal-stock-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.meal-stock-form-page .diary-control.is-invalid,
.meal-stock-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.meal-stock-form-page .diary-control.is-invalid:focus,
.meal-stock-form-page .diary-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
}

/* HELP AND ERROR TEXT */

.meal-stock-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.meal-stock-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.meal-stock-form-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

.meal-stock-form-page .field-error[hidden] {
    display: none;
}

.meal-stock-form-page .field-error i {
    margin-top: 2px;
}

/* TRANSACTION TYPE */

.meal-stock-form-page .transaction-type-wrapper {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.meal-stock-form-page .transaction-option {
    display: block;
    position: relative;
    margin: 0 !important;
    cursor: pointer;
}

.meal-stock-form-page .transaction-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.meal-stock-form-page .transaction-option-content {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 63px;
    padding: 10px 11px;
    border: 1px solid #deded2;
    border-radius: 9px;
    background: #fffefa;
    transition: border-color .18s ease, background .18s ease, box-shadow .18s ease;
}

.meal-stock-form-page .transaction-option:hover .transaction-option-content {
    border-color: #b7c4b7;
}

.meal-stock-form-page .transaction-option input:focus-visible + .transaction-option-content {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 2px;
}

.meal-stock-form-page .transaction-option-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-size: 13px;
}

.meal-stock-form-page .stock-in-option .transaction-option-icon {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.meal-stock-form-page .stock-out-option .transaction-option-icon {
    border-color: #f0d5ce;
    color: var(--red);
    background: #fff1ed;
}

.meal-stock-form-page .transaction-option-text {
    flex: 1;
    min-width: 0;
}

.meal-stock-form-page .transaction-option-text strong {
    display: block;
    margin-bottom: 3px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.meal-stock-form-page .transaction-option-text small {
    display: block;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.4;
}

.meal-stock-form-page .transaction-check {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 19px;
    width: 19px;
    height: 19px;
    border: 1px solid #d8d5c7;
    border-radius: 50%;
    color: transparent;
    font-size: 8px;
}

.meal-stock-form-page .stock-in-option input:checked + .transaction-option-content {
    border-color: var(--green);
    background: #f1f8ef;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .09);
}

.meal-stock-form-page .stock-in-option input:checked + .transaction-option-content .transaction-check {
    border-color: var(--green);
    color: #fff;
    background: var(--green);
}

.meal-stock-form-page .stock-out-option input:checked + .transaction-option-content {
    border-color: var(--red);
    background: #fff4ef;
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .09);
}

.meal-stock-form-page .stock-out-option input:checked + .transaction-option-content .transaction-check {
    border-color: var(--red);
    color: #fff;
    background: var(--red);
}

/* CURRENT STOCK */

.meal-stock-form-page .current-stock-box {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 41px;
    padding: 0 10px;
    border: 1px solid var(--line);
    border-radius: 7px;
    background: #faf9f4;
}

.meal-stock-form-page .current-stock-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 11px;
}

.meal-stock-form-page .current-stock-info {
    display: flex;
    align-items: baseline;
    gap: 6px;
    min-width: 0;
}

.meal-stock-form-page .current-stock-value {
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
}

.meal-stock-form-page .current-stock-label {
    color: var(--muted);
    font-size: 10px;
}

.meal-stock-form-page .live-badge {
    margin-left: auto;
    padding: 3px 8px;
    border: 1px solid #cfe5d3;
    border-radius: 20px;
    color: #347849;
    background: #edf7ed;
    font-size: 9px;
    font-weight: 800;
}

/* INVENTORY PREVIEW */

.meal-stock-form-page .stock-preview-card {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 22px;
    padding: 14px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.meal-stock-form-page .stock-preview-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    color: var(--amber);
    background: #fffefa;
    font-size: 13px;
}

.meal-stock-form-page .stock-preview-content {
    flex: 1;
    min-width: 0;
}

.meal-stock-form-page .stock-preview-title {
    margin-bottom: 4px;
    color: #765e32;
    font-size: 12px;
    font-weight: 800;
}

.meal-stock-form-page .stock-preview-text {
    color: #8a7447;
    font-size: 11px;
    line-height: 1.65;
    overflow-wrap: anywhere;
}

.meal-stock-form-page .stock-preview-text strong {
    color: var(--ink);
    font-weight: 800;
}

.meal-stock-form-page .stock-preview-error {
    color: var(--red);
    font-weight: 800;
}

/* REMARKS CARD */

.meal-stock-form-page .description-card {
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.meal-stock-form-page .description-card:focus-within {
    border-color: #b7c4b7;
    box-shadow: 0 3px 12px rgba(58, 128, 96, .06);
}

.meal-stock-form-page .description-header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
}

.meal-stock-form-page .description-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 11px;
}

.meal-stock-form-page .description-header label {
    margin: 0;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.meal-stock-form-page .diary-textarea {
    min-height: 110px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

.meal-stock-form-page .description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 9px;
}

.meal-stock-form-page .description-footer .field-help {
    margin-top: 0;
}

.meal-stock-form-page .character-count {
    flex-shrink: 0;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

/* ACTION BAR */

.meal-stock-form-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-top: 8px;
    padding: 15px 22px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

.meal-stock-form-page .footer-note {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

.meal-stock-form-page .footer-note i {
    color: var(--green);
}

.meal-stock-form-page .footer-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

/* TABLET */

@media (max-width: 1100px) {
    .meal-stock-form-page .form-grid.cols-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .meal-stock-form-page .form-hero {
        grid-template-columns: 1fr;
    }

    .meal-stock-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }

    .meal-stock-form-page .form-grid,
    .meal-stock-form-page .form-grid.cols-3 {
        grid-template-columns: 1fr;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .meal-stock-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .meal-stock-form-page .form-hero::before {
        left: 22px;
    }

    .meal-stock-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .meal-stock-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .meal-stock-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .meal-stock-form-page .form-hero-text p {
        font-size: 12px;
    }

    .meal-stock-form-page .form-hero-actions {
        flex-direction: column;
        align-items: stretch;
        padding: 0 16px 20px 42px;
    }

    .meal-stock-form-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .meal-stock-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .meal-stock-form-page .record-pill {
        display: none;
    }

    .meal-stock-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .meal-stock-form-page .transaction-type-wrapper {
        grid-template-columns: 1fr;
    }

    /* 16px prevents iOS from zooming into focused fields */
    .meal-stock-form-page .diary-control,
    .meal-stock-form-page .diary-textarea {
        font-size: 16px;
    }

    .meal-stock-form-page .description-card {
        padding: 12px;
    }

    .meal-stock-form-page .description-footer {
        align-items: flex-start;
    }

    .meal-stock-form-page .form-actions {
        flex-direction: column;
        align-items: stretch;
        padding: 14px 15px;
    }

    .meal-stock-form-page .footer-actions {
        flex-direction: column-reverse;
        width: 100%;
    }

    .meal-stock-form-page .footer-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .meal-stock-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .meal-stock-form-page .section-heading {
        align-items: flex-start;
    }

    .meal-stock-form-page .stock-preview-card {
        align-items: flex-start;
    }
}

/* ACCESSIBILITY */

.meal-stock-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.meal-stock-form-page .diary-control:focus-visible,
.meal-stock-form-page .diary-textarea:focus-visible {
    outline: none;
}

@media (prefers-reduced-motion: reduce) {
    .meal-stock-form-page *,
    .meal-stock-form-page *::before,
    .meal-stock-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var page = document.querySelector('.meal-stock-form-page');

    if (!page) return;

    var itemSelect = page.querySelector('#meal_item_id');
    var quantityInput = page.querySelector('#quantity');
    var currentStockValue = page.querySelector('#currentStockValue');
    var currentStockUnit = page.querySelector('#currentStockUnit');
    var quantityHelp = page.querySelector('#quantityHelp');
    var quantityError = page.querySelector('#quantityError');
    var stockPreviewText = page.querySelector('#stockPreviewText');
    var transactionTypeInputs = page.querySelectorAll('input[name="transaction_type"]');
    var submitBtn = page.querySelector('#submitBtn');
    var form = page.querySelector('#stockTransactionForm');
    var remarks = page.querySelector('#remarks');
    var remarksCounter = page.querySelector('#remarksCounter');

    var currentStock = 0;
    var currentUnit = 'Unit';

    function getTransactionType() {
        var selected = page.querySelector('input[name="transaction_type"]:checked');
        return selected ? selected.value : 'stock_in';
    }

    function formatNumber(value) {
        return Number(value).toLocaleString('en-IN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }

    function showQuantityError(message) {
        quantityError.innerHTML = '<i class="fas fa-circle-exclamation"></i> ' + message;
        quantityError.hidden = false;
        quantityInput.classList.add('is-invalid');
        quantityInput.focus();
    }

    function clearQuantityError() {
        quantityError.hidden = true;
        quantityError.innerHTML = '';
        quantityInput.classList.remove('is-invalid');
    }

    function updateItemDetails() {
        var selectedOption = itemSelect.options[itemSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            currentStock = 0;
            currentUnit = 'Unit';

            currentStockValue.textContent = '0';
            currentStockUnit.textContent = 'Unit';
            quantityInput.removeAttribute('max');

            quantityHelp.innerHTML =
                '<i class="fas fa-circle-info"></i> Select a meal item first.';
            stockPreviewText.textContent =
                'Select an item and enter quantity to preview the updated stock.';

            return;
        }

        currentStock = parseFloat(selectedOption.dataset.stock || 0);
        currentUnit = selectedOption.dataset.unit || 'Unit';

        currentStockValue.textContent = formatNumber(currentStock);
        currentStockUnit.textContent = currentUnit;

        updateQuantityLimit();
        updatePreview();
    }

    function updateQuantityLimit() {
        if (!itemSelect.value) return;

        if (getTransactionType() === 'stock_out') {
            quantityInput.max = currentStock;

            quantityHelp.innerHTML =
                '<i class="fas fa-circle-info"></i> Maximum Stock Out: ' +
                formatNumber(currentStock) + ' ' + currentUnit;
        } else {
            quantityInput.removeAttribute('max');

            quantityHelp.innerHTML =
                '<i class="fas fa-circle-info"></i> Stock In will increase the current stock.';
        }
    }

    function updatePreview() {
        var quantity = parseFloat(quantityInput.value || 0);
        var transactionType = getTransactionType();

        clearQuantityError();

        if (!itemSelect.value) {
            stockPreviewText.textContent =
                'Select an item and enter quantity to preview the updated stock.';
            return;
        }

        if (!quantity || quantity <= 0) {
            stockPreviewText.textContent =
                'Enter a quantity to preview the updated stock.';
            return;
        }

        if (transactionType === 'stock_in') {
            var increased = currentStock + quantity;

            stockPreviewText.innerHTML =
                'Current stock <strong>' + formatNumber(currentStock) + ' ' + currentUnit +
                '</strong> + Stock In <strong>' + formatNumber(quantity) + ' ' + currentUnit +
                '</strong> = Updated stock <strong>' + formatNumber(increased) + ' ' +
                currentUnit + '</strong>.';

            return;
        }

        if (quantity > currentStock) {
            stockPreviewText.innerHTML =
                '<span class="stock-preview-error">' +
                '<i class="fas fa-triangle-exclamation"></i> ' +
                'Stock Out quantity cannot be greater than available stock.' +
                '</span>';
            return;
        }

        var reduced = currentStock - quantity;

        stockPreviewText.innerHTML =
            'Current stock <strong>' + formatNumber(currentStock) + ' ' + currentUnit +
            '</strong> − Stock Out <strong>' + formatNumber(quantity) + ' ' + currentUnit +
            '</strong> = Updated stock <strong>' + formatNumber(reduced) + ' ' +
            currentUnit + '</strong>.';
    }

    function updateRemarksCounter() {
        if (!remarks || !remarksCounter) return;
        remarksCounter.textContent = remarks.value.length + ' / 1000';
    }

    itemSelect.addEventListener('change', updateItemDetails);
    quantityInput.addEventListener('input', updatePreview);

    transactionTypeInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            updateQuantityLimit();
            updatePreview();
        });
    });

    if (remarks) {
        remarks.addEventListener('input', updateRemarksCounter);
    }

    form.addEventListener('submit', function (event) {
        var transactionType = getTransactionType();
        var quantity = parseFloat(quantityInput.value || 0);

        if (!itemSelect.value || !quantity || quantity <= 0) {
            event.preventDefault();
            showQuantityError('Please select a meal item and enter a valid quantity.');
            return;
        }

        if (transactionType === 'stock_out' && quantity > currentStock) {
            event.preventDefault();
            showQuantityError('Stock Out quantity cannot be greater than available stock.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });

    updateItemDetails();
    updateRemarksCounter();
});
</script>

@endsection