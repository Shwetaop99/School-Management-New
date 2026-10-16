
@extends('layouts.app')

@section('title', 'Stock In / Out | Admin')
@section('page-title', 'Stock In / Out')

@section('content')

<div class="meal-stock-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="meal-page-header mb-4">
            <div class="meal-heading-wrapper">
                <a href="{{ route('admin.admin.meal.items.stock.index') }}"
                   class="back-btn"
                   title="Back to Stock Transactions"
                   aria-label="Back to Stock Transactions">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="meal-heading-content">
                    <div class="page-eyebrow">
                        <i class="fas fa-warehouse"></i>
                        MEAL MANAGEMENT / INVENTORY
                    </div>

                    <h1 class="meal-page-title">Stock In / Out</h1>

                    <p class="meal-page-subtitle">
                        Record stock purchases, usage and inventory movements.
                    </p>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ route('admin.admin.meal.items.stock.index') }}"
                   class="meal-secondary-btn">
                    <i class="fas fa-clock-rotate-left"></i>
                    Stock History
                </a>

                <a href="{{ route('admin.admin.meal.items.index') }}"
                   class="meal-secondary-btn">
                    <i class="fas fa-boxes-stacked"></i>
                    Meal Items
                </a>
            </div>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="custom-alert success-alert" role="status">
                <div class="alert-icon">
                    <i class="fas fa-check"></i>
                </div>

                <div class="alert-content">
                    <strong>Transaction completed</strong>
                    <span>{{ session('success') }}</span>
                </div>

                <button type="button"
                        class="alert-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="custom-alert error-alert" role="alert">
                <div class="alert-icon">
                    <i class="fas fa-exclamation"></i>
                </div>

                <div class="alert-content">
                    <strong>Please check the form</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button"
                        class="alert-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- MAIN FORM CARD --}}
        <div class="meal-form-card">

            <div class="meal-form-card-header">
                <div class="form-header-left">
                    <div class="form-header-icon">
                        <i class="fas fa-right-left"></i>
                    </div>

                    <div>
                        <div class="card-eyebrow">INVENTORY MANAGEMENT</div>
                        <h5>Stock Transaction</h5>
                        <p>Add or remove inventory for an existing meal item.</p>
                    </div>
                </div>

                <div class="secure-badge">
                    <i class="fas fa-shield-halved"></i>
                    Inventory Record
                </div>
            </div>

            <form action="{{ route('admin.admin.meal.items.stock.store') }}"
                  method="POST"
                  id="stockTransactionForm">

                @csrf

                <div class="meal-form-body">

                    {{-- SECTION 01 --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-number">01</div>
                            <div>
                                <h6>Item & Transaction</h6>
                                <p>Select the meal item and choose the inventory movement.</p>
                            </div>
                        </div>

                        <div class="row g-3">

                            {{-- MEAL ITEM --}}
                            <div class="col-lg-6">
                                <label for="meal_item_id" class="meal-form-label">
                                    Meal Item <span class="required-star">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-box input-icon"></i>

                                    <select name="meal_item_id"
                                            id="meal_item_id"
                                            class="form-select meal-form-control input-with-icon @error('meal_item_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select a meal item</option>

                                        @foreach($mealItems as $item)
                                            <option value="{{ $item->id }}"
                                                    data-stock="{{ $item->current_stock }}"
                                                    data-unit="{{ $item->unit }}"
                                                    {{ old('meal_item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->item_name }}@if($item->category) — {{ $item->category }}@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-hint">
                                    <i class="fas fa-circle-info"></i>
                                    Only active meal items are shown.
                                </div>

                                @error('meal_item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- TRANSACTION TYPE --}}
                            <div class="col-lg-6">
                                <label class="meal-form-label">
                                    Transaction Type <span class="required-star">*</span>
                                </label>

                                <div class="transaction-type-wrapper">

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
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    {{-- SECTION 02 --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-number">02</div>
                            <div>
                                <h6>Quantity & Stock</h6>
                                <p>Enter the quantity and verify the resulting inventory.</p>
                            </div>
                        </div>

                        <div class="row g-3">

                            {{-- CURRENT STOCK --}}
                            <div class="col-lg-4 col-md-6">
                                <label class="meal-form-label">Current Stock</label>

                                <div class="current-stock-box">
                                    <div class="current-stock-icon">
                                        <i class="fas fa-cubes"></i>
                                    </div>

                                    <div class="current-stock-info">
                                        <div class="current-stock-value" id="currentStockValue">0</div>
                                        <div class="current-stock-label" id="currentStockUnit">Unit</div>
                                    </div>

                                    <span class="live-badge">LIVE</span>
                                </div>
                            </div>

                            {{-- QUANTITY --}}
                            <div class="col-lg-4 col-md-6">
                                <label for="quantity" class="meal-form-label">
                                    Quantity <span class="required-star">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-scale-balanced input-icon"></i>

                                    <input type="number"
                                           name="quantity"
                                           id="quantity"
                                           value="{{ old('quantity') }}"
                                           class="form-control meal-form-control input-with-icon @error('quantity') is-invalid @enderror"
                                           min="0.01"
                                           step="0.01"
                                           placeholder="Enter quantity"
                                           required>
                                </div>

                                <div class="form-help-text" id="quantityHelp">
                                    Select a meal item first.
                                </div>

                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- DATE --}}
                            <div class="col-lg-4 col-md-6">
                                <label for="transaction_date" class="meal-form-label">
                                    Transaction Date <span class="required-star">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-calendar-days input-icon"></i>

                                    <input type="date"
                                           name="transaction_date"
                                           id="transaction_date"
                                           value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                           class="form-control meal-form-control input-with-icon @error('transaction_date') is-invalid @enderror"
                                           required>
                                </div>

                                @error('transaction_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- STOCK PREVIEW --}}
                        <div class="stock-preview-card mt-4">
                            <div class="stock-preview-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>

                            <div class="stock-preview-content">
                                <div class="stock-preview-title">Inventory Preview</div>
                                <div class="stock-preview-text" id="stockPreviewText" aria-live="polite">
                                    Select an item and enter quantity to preview the updated stock.
                                </div>
                            </div>

                            <div class="preview-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    {{-- SECTION 03 --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-number">03</div>
                            <div>
                                <h6>Transaction Details</h6>
                                <p>Add supplier or usage information.</p>
                            </div>
                        </div>

                        <div class="row g-3">

                            {{-- SUPPLIER --}}
                            <div class="col-lg-6 col-md-6">
                                <label for="supplier" class="meal-form-label">
                                    Supplier <span class="optional-text">Optional</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-truck input-icon"></i>

                                    <input type="text"
                                           name="supplier"
                                           id="supplier"
                                           value="{{ old('supplier') }}"
                                           class="form-control meal-form-control input-with-icon @error('supplier') is-invalid @enderror"
                                           maxlength="255"
                                           placeholder="Enter supplier name">
                                </div>

                                @error('supplier')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- REASON --}}
                            <div class="col-lg-6 col-md-6">
                                <label for="reason" class="meal-form-label">
                                    Reason <span class="optional-text">Optional</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-comment-dots input-icon"></i>

                                    <input type="text"
                                           name="reason"
                                           id="reason"
                                           value="{{ old('reason') }}"
                                           class="form-control meal-form-control input-with-icon @error('reason') is-invalid @enderror"
                                           maxlength="255"
                                           placeholder="Purchase / Daily Use">
                                </div>

                                @error('reason')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- REMARKS --}}
                            <div class="col-12">
                                <label for="remarks" class="meal-form-label">
                                    Remarks <span class="optional-text">Optional</span>
                                </label>

                                <textarea name="remarks"
                                          id="remarks"
                                          rows="4"
                                          maxlength="1000"
                                          class="form-control meal-form-control @error('remarks') is-invalid @enderror"
                                          placeholder="Add any additional information about this stock transaction...">{{ old('remarks') }}</textarea>

                                <div class="textarea-footer">
                                    <span>Additional notes for inventory records.</span>
                                    <span id="remarksCounter">0 / 1000</span>
                                </div>

                                @error('remarks')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM FOOTER --}}
                <div class="meal-form-footer">
                    <div class="footer-note">
                        <i class="fas fa-shield-halved"></i>
                        <span>Transaction will be added to the inventory log.</span>
                    </div>

                    <div class="footer-actions">
                        <a href="{{ route('admin.admin.meal.items.stock.index') }}"
                           class="meal-cancel-btn">
                            <i class="fas fa-xmark"></i>
                            Cancel
                        </a>

                        <button type="submit" class="meal-save-btn" id="submitBtn">
                            <i class="fas fa-check"></i>
                            Record Transaction
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM — STOCK IN / OUT
========================================================= */
.meal-stock-page {
    --paper: #fffefa;
    --ink: #263b35;
    --muted: #849087;
    --wood: #a87543;
    --green: #34775a;
    --green-dark: #245a43;
    --green-light: #eaf3e9;
    --cyan: #527f79;
    --red: #bd5148;
    --border: #e5e1d3;
    --page: #f5f3e9;
    --white: #ffffff;

    min-height: calc(100vh - 70px);
    background: var(--page);
    color: var(--ink);
    font-family: 'Inter', sans-serif;
    font-size: 12px;
}

.meal-stock-page *,
.meal-stock-page *::before,
.meal-stock-page *::after {
    box-sizing: border-box;
}

.meal-stock-page .container-fluid {
    max-width: 1500px;
    padding-left: 24px;
    padding-right: 24px;
}

/* HEADER */
.meal-stock-page .meal-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    flex-wrap: wrap;
}

.meal-stock-page .meal-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.meal-stock-page .back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 39px;
    height: 39px;
    flex-shrink: 0;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: var(--paper);
    color: var(--green);
    text-decoration: none;
    transition: background .2s ease, color .2s ease, transform .2s ease;
}

.meal-stock-page .back-btn:hover {
    background: var(--green);
    border-color: var(--green);
    color: var(--white);
    transform: translateX(-2px);
}

.meal-stock-page .page-eyebrow {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 4px;
    color: var(--wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.meal-stock-page .meal-page-title {
    margin: 0;
    color: var(--ink);
    font-size: 22px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.45px;
}

.meal-stock-page .meal-page-subtitle {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

.meal-stock-page .header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.meal-stock-page .meal-secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 36px;
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--paper);
    color: var(--ink);
    font-size: 10px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s ease;
}

.meal-stock-page .meal-secondary-btn:hover {
    background: var(--green-light);
    border-color: #bfd3c1;
    color: var(--green-dark);
}

/* ALERTS */
.meal-stock-page .custom-alert {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 15px;
    padding: 12px 14px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: var(--paper);
}

.meal-stock-page .success-alert {
    background: #f0f6ed;
    border-color: #cfdfc9;
    color: var(--green-dark);
}

.meal-stock-page .error-alert {
    background: #fff3ef;
    border-color: #e9c9bf;
    color: var(--red);
}

.meal-stock-page .alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    flex-shrink: 0;
    border-radius: 7px;
    background: rgba(52, 119, 90, .1);
}

.meal-stock-page .error-alert .alert-icon {
    background: rgba(189, 81, 72, .1);
}

.meal-stock-page .alert-content {
    flex: 1;
    min-width: 0;
}

.meal-stock-page .alert-content strong {
    display: block;
    margin-bottom: 3px;
    font-size: 12px;
    font-weight: 800;
}

.meal-stock-page .alert-content span,
.meal-stock-page .alert-content li {
    font-size: 11px;
    line-height: 1.6;
}

.meal-stock-page .alert-content ul {
    margin: 4px 0 0;
    padding-left: 17px;
}

.meal-stock-page .alert-close {
    border: 0;
    background: transparent;
    color: currentColor;
    opacity: .65;
    cursor: pointer;
}

/* MAIN CARD */
.meal-stock-page .meal-form-card {
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 11px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(57, 65, 45, .045);
}

.meal-stock-page .meal-form-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 20px;
    border-bottom: 1px solid var(--border);
    background: #fbfaf4;
}

.meal-stock-page .form-header-left {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.meal-stock-page .form-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    border: 1px solid #d4e2d2;
    border-radius: 9px;
    background: var(--green-light);
    color: var(--green);
    font-size: 14px;
}

.meal-stock-page .card-eyebrow {
    margin-bottom: 3px;
    color: var(--wood);
    font-size: 8px;
    font-weight: 800;
    letter-spacing: .9px;
}

.meal-stock-page .meal-form-card-header h5 {
    margin: 0 0 3px;
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
}

.meal-stock-page .meal-form-card-header p {
    margin: 0;
    color: var(--muted);
    font-size: 10px;
}

.meal-stock-page .secure-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 9px;
    border: 1px solid #d9e4d5;
    border-radius: 7px;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 9px;
    font-weight: 750;
    white-space: nowrap;
}

/* FORM BODY */
.meal-stock-page .meal-form-body {
    padding: 23px 22px 25px;
}

.meal-stock-page .section-heading {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 17px;
}

.meal-stock-page .section-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    flex-shrink: 0;
    border: 1px solid #d4e2d2;
    border-radius: 7px;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 9px;
    font-weight: 800;
}

.meal-stock-page .section-heading h6 {
    margin: 1px 0 3px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.meal-stock-page .section-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.5;
}

.meal-stock-page .section-divider {
    height: 1px;
    margin: 23px 0;
    background: var(--border);
}

/* LABELS */
.meal-stock-page .meal-form-label {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
    min-height: 18px;
    margin-bottom: 7px;
    color: var(--ink);
    font-size: 10px;
    font-weight: 800;
}

.meal-stock-page .required-star {
    color: var(--red);
}

.meal-stock-page .optional-text {
    margin-left: 3px;
    padding: 2px 5px;
    border: 1px solid #e8e0cf;
    border-radius: 4px;
    background: #f6f0e3;
    color: #8b785d;
    font-size: 8px;
    font-weight: 750;
    text-transform: uppercase;
}

/* INPUTS */
.meal-stock-page .input-icon-wrapper {
    position: relative;
}

.meal-stock-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    transform: translateY(-50%);
    color: #8b9487;
    font-size: 11px;
    pointer-events: none;
}

.meal-stock-page .input-icon-wrapper:focus-within .input-icon {
    color: var(--green);
}

.meal-stock-page .meal-form-control {
    width: 100%;
    min-height: 39px;
    border: 1px solid #dedccc;
    border-radius: 7px;
    background: #fffefa;
    color: var(--ink);
    font-family: inherit;
    font-size: 11px;
    box-shadow: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.meal-stock-page .meal-form-control::placeholder {
    color: #a4a596;
}

.meal-stock-page .meal-form-control:hover {
    border-color: #c8c6b5;
}

.meal-stock-page .meal-form-control:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .09);
    outline: none;
}

.meal-stock-page .input-with-icon {
    padding-left: 35px;
}

.meal-stock-page select.meal-form-control {
    padding-right: 30px;
    cursor: pointer;
}

.meal-stock-page textarea.meal-form-control {
    min-height: 95px;
    padding: 11px 12px;
    line-height: 1.6;
    resize: vertical;
}

.meal-stock-page .field-hint,
.meal-stock-page .form-help-text {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 6px;
    color: var(--muted);
    font-size: 9px;
    line-height: 1.5;
}

.meal-stock-page .field-hint i {
    color: var(--wood);
}

.meal-stock-page .invalid-feedback {
    font-size: 10px;
    font-weight: 650;
}

/* TRANSACTION TYPE */
.meal-stock-page .transaction-type-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 9px;
}

.meal-stock-page .transaction-option {
    display: block;
    position: relative;
    margin: 0;
    cursor: pointer;
}

.meal-stock-page .transaction-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.meal-stock-page .transaction-option-content {
    display: flex;
    align-items: center;
    gap: 9px;
    min-height: 61px;
    padding: 9px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--paper);
    transition: border-color .2s ease, background .2s ease;
}

.meal-stock-page .transaction-option:hover .transaction-option-content {
    border-color: #b8cbb6;
}

.meal-stock-page .transaction-option input:focus-visible + .transaction-option-content {
    outline: 2px solid var(--green);
    outline-offset: 2px;
}

.meal-stock-page .transaction-option-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 7px;
    font-size: 12px;
}

.meal-stock-page .stock-in-option .transaction-option-icon {
    background: var(--green-light);
    color: var(--green);
}

.meal-stock-page .stock-out-option .transaction-option-icon {
    background: #f9e9e3;
    color: var(--red);
}

.meal-stock-page .transaction-option-text {
    flex: 1;
    min-width: 0;
}

.meal-stock-page .transaction-option-text strong {
    display: block;
    margin-bottom: 3px;
    color: var(--ink);
    font-size: 10px;
    font-weight: 800;
}

.meal-stock-page .transaction-option-text small {
    display: block;
    color: var(--muted);
    font-size: 8px;
    line-height: 1.4;
}

.meal-stock-page .transaction-check {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    border: 1px solid #d8d5c7;
    border-radius: 50%;
    color: transparent;
    font-size: 7px;
}

.meal-stock-page .stock-in-option input:checked + .transaction-option-content {
    border-color: var(--green);
    background: #f0f6ed;
}

.meal-stock-page .stock-in-option input:checked + .transaction-option-content .transaction-check {
    border-color: var(--green);
    background: var(--green);
    color: white;
}

.meal-stock-page .stock-out-option input:checked + .transaction-option-content {
    border-color: var(--red);
    background: #fff5f0;
}

.meal-stock-page .stock-out-option input:checked + .transaction-option-content .transaction-check {
    border-color: var(--red);
    background: var(--red);
    color: white;
}

/* CURRENT STOCK */
.meal-stock-page .current-stock-box {
    display: flex;
    align-items: center;
    gap: 9px;
    min-height: 39px;
    padding: 5px 9px;
    border: 1px solid var(--border);
    border-radius: 7px;
    background: #f8f7ef;
}

.meal-stock-page .current-stock-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 6px;
    background: var(--green-light);
    color: var(--green);
    font-size: 11px;
}

.meal-stock-page .current-stock-info {
    min-width: 0;
}

.meal-stock-page .current-stock-value {
    color: var(--ink);
    font-size: 13px;
    line-height: 1.2;
    font-weight: 800;
}

.meal-stock-page .current-stock-label {
    margin-top: 2px;
    color: var(--muted);
    font-size: 8px;
    text-transform: uppercase;
}

.meal-stock-page .live-badge {
    margin-left: auto;
    padding: 3px 5px;
    border-radius: 4px;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 7px;
    font-weight: 800;
    letter-spacing: .5px;
}

/* INVENTORY PREVIEW */
.meal-stock-page .stock-preview-card {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 13px;
    border: 1px solid #d7e2d0;
    border-radius: 8px;
    background: #f2f6ed;
}

.meal-stock-page .stock-preview-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border: 1px solid #d4e2d2;
    border-radius: 7px;
    background: #e4efdf;
    color: var(--green);
    font-size: 12px;
}

.meal-stock-page .stock-preview-content {
    flex: 1;
    min-width: 0;
}

.meal-stock-page .stock-preview-title {
    margin-bottom: 4px;
    color: var(--ink);
    font-size: 10px;
    font-weight: 800;
}

.meal-stock-page .stock-preview-text {
    color: #6e7c6f;
    font-size: 10px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.meal-stock-page .stock-preview-text strong {
    color: var(--green-dark);
    font-weight: 800;
}

.meal-stock-page .preview-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    flex-shrink: 0;
    border: 1px solid #d8e2d2;
    border-radius: 50%;
    background: var(--paper);
    color: var(--green);
    font-size: 9px;
}

/* REMARKS */
.meal-stock-page .textarea-footer {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 6px;
    color: var(--muted);
    font-size: 9px;
}

.meal-stock-page #remarksCounter {
    color: var(--green-dark);
    font-weight: 750;
    white-space: nowrap;
}

/* FOOTER */
.meal-stock-page .meal-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    background: #f9f7ef;
}

.meal-stock-page .footer-note {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--muted);
    font-size: 9px;
    line-height: 1.5;
}

.meal-stock-page .footer-note i {
    color: var(--green);
}

.meal-stock-page .footer-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.meal-stock-page .meal-cancel-btn,
.meal-stock-page .meal-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 36px;
    padding: 8px 13px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: inherit;
    font-size: 10px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}

.meal-stock-page .meal-cancel-btn {
    border-color: var(--border);
    background: var(--paper);
    color: var(--ink);
}

.meal-stock-page .meal-cancel-btn:hover {
    border-color: #c9c5b3;
    background: #f1efe4;
    color: var(--ink);
}

.meal-stock-page .meal-save-btn {
    border-color: var(--green);
    background: var(--green);
    color: white;
}

.meal-stock-page .meal-save-btn:hover {
    border-color: var(--green-dark);
    background: var(--green-dark);
    color: white;
    transform: translateY(-1px);
}

.meal-stock-page .meal-save-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
}

/* RESPONSIVE */
@media (max-width: 991.98px) {
    .meal-stock-page .secure-badge {
        display: none;
    }

    .meal-stock-page .meal-form-card-header {
        padding: 15px 17px;
    }
}

@media (max-width: 767.98px) {
    .meal-stock-page .container-fluid {
        padding: 19px 15px !important;
    }

    .meal-stock-page .meal-page-header {
        align-items: flex-start;
    }

    .meal-stock-page .meal-page-title {
        font-size: 20px;
    }

    .meal-stock-page .header-actions {
        width: 100%;
    }

    .meal-stock-page .header-actions .meal-secondary-btn {
        flex: 1;
    }

    .meal-stock-page .meal-form-body {
        padding: 19px 15px 21px;
    }

    .meal-stock-page .transaction-type-wrapper {
        grid-template-columns: 1fr;
    }

    .meal-stock-page .meal-form-footer {
        align-items: stretch;
        flex-direction: column;
        padding: 14px 15px;
    }

    .meal-stock-page .footer-note {
        align-items: flex-start;
    }

    .meal-stock-page .footer-actions {
        width: 100%;
    }

    .meal-stock-page .footer-actions > * {
        flex: 1;
    }

    .meal-stock-page .preview-arrow {
        display: none;
    }
}

@media (max-width: 480px) {
    .meal-stock-page .meal-heading-wrapper {
        align-items: flex-start;
        gap: 9px;
    }

    .meal-stock-page .back-btn {
        width: 34px;
        height: 34px;
    }

    .meal-stock-page .meal-page-title {
        font-size: 18px;
    }

    .meal-stock-page .meal-page-subtitle {
        font-size: 10px;
    }

    .meal-stock-page .header-actions {
        flex-direction: column;
    }

    .meal-stock-page .header-actions .meal-secondary-btn {
        width: 100%;
    }

    .meal-stock-page .meal-form-card-header {
        padding: 14px 12px;
    }

    .meal-stock-page .meal-form-body {
        padding: 17px 12px;
    }

    .meal-stock-page .form-header-icon {
        width: 35px;
        height: 35px;
    }

    .meal-stock-page .meal-form-card-header p {
        max-width: 220px;
        line-height: 1.5;
    }

    .meal-stock-page .stock-preview-card {
        align-items: flex-start;
    }

    .meal-stock-page .textarea-footer {
        align-items: flex-start;
    }

    .meal-stock-page .footer-actions {
        flex-direction: column-reverse;
    }

    .meal-stock-page .footer-actions > * {
        width: 100%;
    }
}

@media (prefers-reduced-motion: reduce) {
    .meal-stock-page *,
    .meal-stock-page *::before,
    .meal-stock-page *::after {
        transition: none !important;
        scroll-behavior: auto !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.meal-stock-page');

    if (!page) return;

    const itemSelect = page.querySelector('#meal_item_id');
    const quantityInput = page.querySelector('#quantity');
    const currentStockValue = page.querySelector('#currentStockValue');
    const currentStockUnit = page.querySelector('#currentStockUnit');
    const quantityHelp = page.querySelector('#quantityHelp');
    const stockPreviewText = page.querySelector('#stockPreviewText');
    const transactionTypeInputs = page.querySelectorAll('input[name="transaction_type"]');
    const submitBtn = page.querySelector('#submitBtn');
    const form = page.querySelector('#stockTransactionForm');
    const remarks = page.querySelector('#remarks');
    const remarksCounter = page.querySelector('#remarksCounter');

    let currentStock = 0;
    let currentUnit = 'Unit';

    function getTransactionType() {
        const selected = page.querySelector('input[name="transaction_type"]:checked');
        return selected ? selected.value : 'stock_in';
    }

    function formatNumber(value) {
        return Number(value).toLocaleString('en-IN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }

    function updateItemDetails() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            currentStock = 0;
            currentUnit = 'Unit';

            currentStockValue.textContent = '0';
            currentStockUnit.textContent = 'Unit';
            quantityInput.removeAttribute('max');

            quantityHelp.textContent = 'Select a meal item first.';
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
        const quantity = parseFloat(quantityInput.value || 0);
        const transactionType = getTransactionType();

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
            const updatedStock = currentStock + quantity;

            stockPreviewText.innerHTML =
                'Current stock <strong>' + formatNumber(currentStock) + ' ' + currentUnit +
                '</strong> + Stock In <strong>' + formatNumber(quantity) + ' ' + currentUnit +
                '</strong> = Updated stock <strong>' + formatNumber(updatedStock) + ' ' +
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

        const updatedStock = currentStock - quantity;

        stockPreviewText.innerHTML =
            'Current stock <strong>' + formatNumber(currentStock) + ' ' + currentUnit +
            '</strong> − Stock Out <strong>' + formatNumber(quantity) + ' ' + currentUnit +
            '</strong> = Updated stock <strong>' + formatNumber(updatedStock) + ' ' +
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
        const transactionType = getTransactionType();
        const quantity = parseFloat(quantityInput.value || 0);

        if (transactionType === 'stock_out' && quantity > currentStock) {
            event.preventDefault();

            alert('Stock Out quantity cannot be greater than available stock.');
            quantityInput.focus();
            return;
        }

        if (!itemSelect.value || !quantity || quantity <= 0) {
            event.preventDefault();

            alert('Please select a meal item and enter a valid quantity.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });

    updateItemDetails();
    updateRemarksCounter();
});
</script>

<style>
.meal-stock-page .stock-preview-error {
    color: #bd5148;
    font-weight: 800;
}
</style>

@endsection
