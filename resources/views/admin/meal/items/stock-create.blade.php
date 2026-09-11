@extends('layouts.app')

@section('title', 'Stock In / Out | Admin')

@section('content')

<div class="meal-stock-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}
        <div class="meal-page-header mb-4">

            <div class="meal-heading-wrapper">

                <a href="{{ route('admin.meal.items.stock.index') }}"
                   class="back-btn"
                   title="Back to Stock Transactions">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div>

                    <div class="page-eyebrow">
                        <i class="fas fa-warehouse me-1"></i>
                        MEAL INVENTORY
                    </div>

                    <h1 class="meal-page-title">
                        Stock In / Out
                    </h1>

                    <p class="meal-page-subtitle">
                        Record stock purchases, usage and inventory movements.
                    </p>

                </div>

            </div>

            <div class="header-actions">

                <a href="{{ route('admin.meal.items.stock.index') }}"
                   class="btn meal-secondary-btn">
                    <i class="fas fa-clock-rotate-left me-1"></i>
                    Stock History
                </a>

                <a href="{{ route('admin.meal.items.index') }}"
                   class="btn meal-secondary-btn">
                    <i class="fas fa-boxes-stacked me-1"></i>
                    Meal Items
                </a>

            </div>

        </div>


        {{-- =========================================================
             SUCCESS MESSAGE
        ========================================================== --}}
        @if(session('success'))

            <div class="alert custom-alert success-alert">

                <div class="alert-icon">
                    <i class="fas fa-check"></i>
                </div>

                <div class="alert-content">

                    <strong>Transaction completed</strong>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

                <button type="button"
                        class="alert-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">

                    <i class="fas fa-xmark"></i>

                </button>

            </div>

        @endif


        {{-- =========================================================
             VALIDATION ERRORS
        ========================================================== --}}
        @if($errors->any())

            <div class="alert custom-alert error-alert">

                <div class="alert-icon">
                    <i class="fas fa-exclamation"></i>
                </div>

                <div class="alert-content">

                    <strong>Please check the form</strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

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


        {{-- =========================================================
             MAIN FORM CARD
        ========================================================== --}}
        <div class="meal-form-card">

            {{-- CARD HEADER --}}
            <div class="meal-form-card-header">

                <div class="form-header-left">

                    <div class="form-header-icon">
                        <i class="fas fa-right-left"></i>
                    </div>

                    <div>

                        <h5>
                            Stock Transaction
                        </h5>

                        <p>
                            Add or remove inventory for an existing meal item.
                        </p>

                    </div>

                </div>

                <div class="secure-badge">

                    <i class="fas fa-shield-halved"></i>

                    Inventory Record

                </div>

            </div>


            {{-- FORM --}}
            <form action="{{ route('admin.meal.items.stock.store') }}"
                  method="POST"
                  id="stockTransactionForm">

                @csrf

                <div class="meal-form-body">


                    {{-- =================================================
                         STEP 01
                    ================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-number">
                                01
                            </div>

                            <div>

                                <h6>
                                    Item & Transaction
                                </h6>

                                <p>
                                    Select the meal item and choose the inventory movement.
                                </p>

                            </div>

                        </div>


                        <div class="row g-4">


                            {{-- MEAL ITEM --}}
                            <div class="col-lg-6">

                                <label for="meal_item_id"
                                       class="meal-form-label">

                                    Meal Item

                                    <span class="required-star">*</span>

                                </label>

                                <div class="input-icon-wrapper">

                                    <i class="fas fa-box input-icon"></i>

                                    <select name="meal_item_id"
                                            id="meal_item_id"
                                            class="form-select meal-form-control input-with-icon @error('meal_item_id') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            Select a meal item
                                        </option>

                                        @foreach($mealItems as $item)

                                            <option value="{{ $item->id }}"
                                                    data-stock="{{ $item->current_stock }}"
                                                    data-unit="{{ $item->unit }}"
                                                    {{ old('meal_item_id') == $item->id ? 'selected' : '' }}>

                                                {{ $item->item_name }}

                                                @if($item->category)
                                                    — {{ $item->category }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="field-hint">

                                    <i class="fas fa-circle-info"></i>

                                    Only active meal items are shown.

                                </div>

                                @error('meal_item_id')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- TRANSACTION TYPE --}}
                            <div class="col-lg-6">

                                <label class="meal-form-label">

                                    Transaction Type

                                    <span class="required-star">*</span>

                                </label>

                                <div class="transaction-type-wrapper">


                                    {{-- STOCK IN --}}
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

                                                <strong>
                                                    Stock In
                                                </strong>

                                                <small>
                                                    Add quantity to inventory
                                                </small>

                                            </span>

                                            <span class="transaction-check">

                                                <i class="fas fa-check"></i>

                                            </span>

                                        </span>

                                    </label>


                                    {{-- STOCK OUT --}}
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

                                                <strong>
                                                    Stock Out
                                                </strong>

                                                <small>
                                                    Reduce quantity from inventory
                                                </small>

                                            </span>

                                            <span class="transaction-check">

                                                <i class="fas fa-check"></i>

                                            </span>

                                        </span>

                                    </label>

                                </div>

                                @error('transaction_type')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>


                    <div class="section-divider"></div>


                    {{-- =================================================
                         STEP 02
                    ================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-number">
                                02
                            </div>

                            <div>

                                <h6>
                                    Quantity & Stock
                                </h6>

                                <p>
                                    Enter the quantity and verify the resulting inventory.
                                </p>

                            </div>

                        </div>


                        <div class="row g-4">


                            {{-- CURRENT STOCK --}}
                            <div class="col-lg-4 col-md-6">

                                <label class="meal-form-label">
                                    Current Stock
                                </label>

                                <div class="current-stock-box">

                                    <div class="current-stock-icon">

                                        <i class="fas fa-cubes"></i>

                                    </div>

                                    <div class="current-stock-info">

                                        <div class="current-stock-value"
                                             id="currentStockValue">
                                            0
                                        </div>

                                        <div class="current-stock-label"
                                             id="currentStockUnit">
                                            Unit
                                        </div>

                                    </div>

                                    <span class="live-badge">
                                        LIVE
                                    </span>

                                </div>

                            </div>


                            {{-- QUANTITY --}}
                            <div class="col-lg-4 col-md-6">

                                <label for="quantity"
                                       class="meal-form-label">

                                    Quantity

                                    <span class="required-star">*</span>

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

                                <div class="form-help-text"
                                     id="quantityHelp">

                                    Select a meal item first.

                                </div>

                                @error('quantity')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- DATE --}}
                            <div class="col-lg-4 col-md-6">

                                <label for="transaction_date"
                                       class="meal-form-label">

                                    Transaction Date

                                    <span class="required-star">*</span>

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

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- STOCK PREVIEW --}}
                        <div class="stock-preview-card mt-4">

                            <div class="stock-preview-icon">

                                <i class="fas fa-chart-line"></i>

                            </div>

                            <div class="stock-preview-content">

                                <div class="stock-preview-title">
                                    Inventory Preview
                                </div>

                                <div class="stock-preview-text"
                                     id="stockPreviewText">

                                    Select an item and enter quantity
                                    to preview the updated stock.

                                </div>

                            </div>

                            <div class="preview-arrow">

                                <i class="fas fa-arrow-right"></i>

                            </div>

                        </div>

                    </div>


                    <div class="section-divider"></div>


                    {{-- =================================================
                         STEP 03
                    ================================================== --}}
                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-number">
                                03
                            </div>

                            <div>

                                <h6>
                                    Transaction Details
                                </h6>

                                <p>
                                    Add supplier or usage information.
                                </p>

                            </div>

                        </div>


                        <div class="row g-4">


                            {{-- SUPPLIER --}}
                            <div class="col-lg-6 col-md-6">

                                <label for="supplier"
                                       class="meal-form-label">

                                    Supplier

                                    <span class="optional-text">
                                        Optional
                                    </span>

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

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- REASON --}}
                            <div class="col-lg-6 col-md-6">

                                <label for="reason"
                                       class="meal-form-label">

                                    Reason

                                    <span class="optional-text">
                                        Optional
                                    </span>

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

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- REMARKS --}}
                            <div class="col-12">

                                <label for="remarks"
                                       class="meal-form-label">

                                    Remarks

                                    <span class="optional-text">
                                        Optional
                                    </span>

                                </label>

                                <textarea name="remarks"
                                          id="remarks"
                                          rows="4"
                                          maxlength="1000"
                                          class="form-control meal-form-control @error('remarks') is-invalid @enderror"
                                          placeholder="Add any additional information about this stock transaction...">{{ old('remarks') }}</textarea>

                                <div class="textarea-footer">

                                    <span>
                                        Additional notes for inventory records.
                                    </span>

                                    <span id="remarksCounter">
                                        0 / 1000
                                    </span>

                                </div>

                                @error('remarks')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     FORM FOOTER
                ================================================== --}}
                <div class="meal-form-footer">

                    <div class="footer-note">

                        <i class="fas fa-shield-halved"></i>

                        <span>
                            Transaction will be added to the inventory log.
                        </span>

                    </div>

                    <div class="footer-actions">

                        <a href="{{ route('admin.meal.items.stock.index') }}"
                           class="btn meal-cancel-btn">

                            <i class="fas fa-xmark me-1"></i>

                            Cancel

                        </a>

                        <button type="submit"
                                class="btn meal-save-btn"
                                id="submitBtn">

                            <i class="fas fa-check me-1"></i>

                            Record Transaction

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
     PAGE CSS
================================================================ --}}
<style>

    /* ================================================================
       BASE
    ================================================================ */

    .meal-stock-page {
        min-height: calc(100vh - 70px);
        background: #f6f8fb;
        font-family: 'Inter', sans-serif;
    }

    .meal-stock-page .container-fluid {
        max-width: 1500px;
    }


    /* ================================================================
       HEADER
    ================================================================ */

    .meal-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .meal-heading-wrapper {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .back-btn {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border-radius: 12px;
        background: #ffffff;
        color: #147cf5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid #e8edf3;
        box-shadow: 0 4px 14px rgba(30, 50, 80, 0.06);
        transition: all .2s ease;
    }

    .back-btn:hover {
        background: #147cf5;
        color: #ffffff;
        border-color: #147cf5;
        transform: translateX(-2px);
        box-shadow: 0 6px 18px rgba(20, 124, 245, .18);
    }

    .page-eyebrow {
        color: #147cf5;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.2px;
        margin-bottom: 4px;
    }

    .meal-page-title {
        margin: 0;
        color: #17212b;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 750;
        letter-spacing: -.5px;
    }

    .meal-page-subtitle {
        margin: 5px 0 0;
        color: #7d8996;
        font-size: 13px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    .meal-secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 9px 14px;
        background: #ffffff;
        color: #52606d;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 650;
        transition: all .2s ease;
    }

    .meal-secondary-btn:hover {
        color: #147cf5;
        background: #f8fbff;
        border-color: #bcd8fa;
        transform: translateY(-1px);
    }


    /* ================================================================
       ALERTS
    ================================================================ */

    .custom-alert {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        margin-bottom: 18px;
        border-radius: 12px;
        border: 1px solid transparent;
        box-shadow: 0 4px 16px rgba(30, 50, 80, .04);
    }

    .success-alert {
        background: #f0fbf5;
        border-color: #ccefdc;
        color: #237849;
    }

    .error-alert {
        background: #fff5f4;
        border-color: #f5d1cd;
        color: #a33c31;
    }

    .alert-icon {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .success-alert .alert-icon {
        background: #d9f4e5;
        color: #20a15a;
    }

    .error-alert .alert-icon {
        background: #fde1de;
        color: #e24d40;
    }

    .alert-content {
        flex: 1;
        min-width: 0;
    }

    .alert-content strong {
        display: block;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .alert-content span {
        display: block;
        font-size: 12px;
    }

    .alert-content ul {
        margin: 5px 0 0;
        padding-left: 17px;
        font-size: 12px;
    }

    .alert-content li {
        margin-bottom: 2px;
    }

    .alert-close {
        border: 0;
        background: transparent;
        color: currentColor;
        opacity: .55;
        padding: 3px;
        cursor: pointer;
    }

    .alert-close:hover {
        opacity: 1;
    }


    /* ================================================================
       MAIN CARD
    ================================================================ */

    .meal-form-card {
        background: #ffffff;
        border: 1px solid #e9edf2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(30, 50, 80, .055);
    }

    .meal-form-card-header {
        min-height: 82px;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-bottom: 1px solid #edf0f5;
        background: #ffffff;
    }

    .form-header-left {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .form-header-icon {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 6px 14px rgba(20, 124, 245, .16);
    }

    .meal-form-card-header h5 {
        margin: 0 0 3px;
        color: #1d2733;
        font-size: 16px;
        font-weight: 750;
    }

    .meal-form-card-header p {
        margin: 0;
        color: #8a96a3;
        font-size: 12px;
    }

    .secure-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 8px;
        background: #f4f8fd;
        color: #6f8194;
        border: 1px solid #e5edf6;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .secure-badge i {
        color: #147cf5;
    }


    /* ================================================================
       FORM BODY
    ================================================================ */

    .meal-form-body {
        padding: 28px 26px 30px;
    }

    .form-section {
        width: 100%;
    }

    .section-heading {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 20px;
    }

    .section-number {
        width: 29px;
        height: 29px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #edf5ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
    }

    .section-heading h6 {
        margin: 1px 0 3px;
        color: #273444;
        font-size: 14px;
        font-weight: 750;
    }

    .section-heading p {
        margin: 0;
        color: #8a96a3;
        font-size: 11px;
    }

    .section-divider {
        height: 1px;
        margin: 27px 0;
        background: #edf0f5;
    }


    /* ================================================================
       LABELS
    ================================================================ */

    .meal-form-label {
        display: flex;
        align-items: center;
        gap: 3px;
        min-height: 20px;
        margin-bottom: 8px;
        color: #34404d;
        font-size: 12px;
        font-weight: 750;
    }

    .required-star {
        color: #f65343;
        font-weight: 800;
    }

    .optional-text {
        margin-left: 4px;
        padding: 2px 6px;
        border-radius: 5px;
        background: #f3f5f7;
        color: #98a2ad;
        font-size: 9px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: .3px;
    }


    /* ================================================================
       INPUTS
    ================================================================ */

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        z-index: 2;
        transform: translateY(-50%);
        color: #8d99a6;
        font-size: 12px;
        pointer-events: none;
        transition: color .2s ease;
    }

    .input-icon-wrapper:focus-within .input-icon {
        color: #147cf5;
    }

    .meal-form-control {
        width: 100%;
        min-height: 44px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #ffffff;
        color: #273444;
        font-size: 12px;
        box-shadow: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .meal-form-control::placeholder {
        color: #a3adb8;
    }

    .meal-form-control:hover {
        border-color: #cdd6e0;
    }

    .meal-form-control:focus {
        border-color: #147cf5;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .09);
        outline: none;
    }

    .input-with-icon {
        padding-left: 40px;
    }

    select.meal-form-control {
        cursor: pointer;
        padding-right: 38px;
    }

    textarea.meal-form-control {
        min-height: 112px;
        padding: 12px 14px;
        line-height: 1.55;
        resize: vertical;
    }

    .field-hint,
    .form-help-text {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
        color: #8c98a5;
        font-size: 10px;
    }

    .field-hint i {
        color: #9aa7b4;
        font-size: 9px;
    }

    .invalid-feedback {
        font-size: 11px;
        font-weight: 600;
    }


    /* ================================================================
       TRANSACTION TYPE
    ================================================================ */

    .transaction-type-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 11px;
    }

    .transaction-option {
        position: relative;
        display: block;
        margin: 0;
        cursor: pointer;
    }

    .transaction-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .transaction-option-content {
        position: relative;
        min-height: 68px;
        padding: 11px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #dfe5ec;
        border-radius: 11px;
        background: #ffffff;
        transition: all .2s ease;
    }

    .transaction-option:hover .transaction-option-content {
        border-color: #cbd5df;
        transform: translateY(-1px);
    }

    .transaction-option-icon {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .stock-in-option .transaction-option-icon {
        background: #eaf8ef;
        color: #20a15a;
    }

    .stock-out-option .transaction-option-icon {
        background: #fff0ee;
        color: #f65343;
    }

    .transaction-option-text {
        min-width: 0;
        flex: 1;
    }

    .transaction-option-text strong {
        display: block;
        margin-bottom: 3px;
        color: #34404d;
        font-size: 12px;
        font-weight: 750;
    }

    .transaction-option-text small {
        display: block;
        color: #929eaa;
        font-size: 9px;
        line-height: 1.3;
    }

    .transaction-check {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8dfe6;
        color: transparent;
        font-size: 8px;
        transition: all .2s ease;
    }

    .stock-in-option input:checked + .transaction-option-content {
        border-color: #20a15a;
        background: #f5fcf8;
        box-shadow: 0 0 0 3px rgba(32, 161, 90, .08);
    }

    .stock-in-option input:checked + .transaction-option-content .transaction-check {
        background: #20a15a;
        border-color: #20a15a;
        color: #ffffff;
    }

    .stock-out-option input:checked + .transaction-option-content {
        border-color: #f65343;
        background: #fff8f7;
        box-shadow: 0 0 0 3px rgba(246, 83, 67, .08);
    }

    .stock-out-option input:checked + .transaction-option-content .transaction-check {
        background: #f65343;
        border-color: #f65343;
        color: #ffffff;
    }


    /* ================================================================
       CURRENT STOCK
    ================================================================ */

    .current-stock-box {
        position: relative;
        min-height: 44px;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #f8fafc;
    }

    .current-stock-icon {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #eaf3ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .current-stock-info {
        min-width: 0;
    }

    .current-stock-value {
        color: #1d2733;
        font-size: 14px;
        line-height: 1.1;
        font-weight: 800;
    }

    .current-stock-label {
        margin-top: 2px;
        color: #8b97a4;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .live-badge {
        margin-left: auto;
        padding: 3px 6px;
        border-radius: 5px;
        background: #eaf8ef;
        color: #20a15a;
        font-size: 8px;
        font-weight: 800;
        letter-spacing: .5px;
    }


    /* ================================================================
       STOCK PREVIEW
    ================================================================ */

    .stock-preview-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 15px 16px;
        border: 1px solid #dbeaff;
        border-radius: 11px;
        background: linear-gradient(135deg, #f7faff, #fbfdff);
    }

    .stock-preview-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 10px;
        background: #e8f2ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .stock-preview-content {
        min-width: 0;
        flex: 1;
    }

    .stock-preview-title {
        margin-bottom: 3px;
        color: #34404d;
        font-size: 11px;
        font-weight: 800;
    }

    .stock-preview-text {
        color: #7b8794;
        font-size: 11px;
        line-height: 1.5;
    }

    .stock-preview-text strong {
        color: #273444;
        font-weight: 750;
    }

    .preview-arrow {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e2eaf2;
        color: #8a98a7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }


    /* ================================================================
       TEXTAREA
    ================================================================ */

    .textarea-footer {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 6px;
        color: #98a3ae;
        font-size: 9px;
    }

    #remarksCounter {
        font-weight: 650;
        white-space: nowrap;
    }


    /* ================================================================
       FOOTER
    ================================================================ */

    .meal-form-footer {
        min-height: 70px;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        background: #fafbfc;
        border-top: 1px solid #edf0f5;
    }

    .footer-note {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #8995a1;
        font-size: 10px;
    }

    .footer-note i {
        color: #147cf5;
        font-size: 11px;
    }

    .footer-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .meal-cancel-btn,
    .meal-save-btn {
        min-height: 40px;
        padding: 9px 16px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        transition: all .2s ease;
    }

    .meal-cancel-btn {
        background: #ffffff;
        color: #52606d;
        border: 1px solid #dfe5ec;
    }

    .meal-cancel-btn:hover {
        background: #f4f6f8;
        color: #34404d;
        border-color: #cfd7df;
    }

    .meal-save-btn {
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        border: 0;
        box-shadow: 0 5px 13px rgba(20, 124, 245, .18);
    }

    .meal-save-btn:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 17px rgba(20, 124, 245, .25);
    }

    .meal-save-btn:disabled {
        opacity: .7;
        cursor: not-allowed;
        transform: none;
    }


    /* ================================================================
       RESPONSIVE
    ================================================================ */

    @media (max-width: 991px) {

        .meal-form-card-header {
            align-items: flex-start;
        }

        .secure-badge {
            display: none;
        }

    }


    @media (max-width: 767px) {

        .meal-stock-page .container-fluid {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        .meal-page-title {
            font-size: 23px;
        }

        .meal-page-subtitle {
            font-size: 12px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .meal-form-card-header {
            padding: 17px 16px;
        }

        .meal-form-body {
            padding: 22px 16px 24px;
        }

        .transaction-type-wrapper {
            grid-template-columns: 1fr;
        }

        .meal-form-footer {
            flex-direction: column;
            align-items: stretch;
            padding: 15px 16px;
        }

        .footer-note {
            justify-content: center;
        }

        .footer-actions {
            width: 100%;
        }

        .footer-actions .btn {
            flex: 1;
        }

        .preview-arrow {
            display: none;
        }

    }


    @media (max-width: 480px) {

        .meal-heading-wrapper {
            align-items: flex-start;
        }

        .back-btn {
            width: 40px;
            height: 40px;
        }

        .meal-page-header {
            gap: 15px;
        }

        .header-actions {
            flex-direction: column;
        }

        .header-actions .btn {
            width: 100%;
        }

        .form-header-icon {
            width: 40px;
            height: 40px;
        }

        .meal-form-card-header h5 {
            font-size: 14px;
        }

        .meal-form-card-header p {
            font-size: 10px;
        }

        .stock-preview-card {
            align-items: flex-start;
        }

    }

</style>


{{-- ================================================================
     PAGE JAVASCRIPT
================================================================ --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const itemSelect =
        document.getElementById('meal_item_id');

    const quantityInput =
        document.getElementById('quantity');

    const currentStockValue =
        document.getElementById('currentStockValue');

    const currentStockUnit =
        document.getElementById('currentStockUnit');

    const quantityHelp =
        document.getElementById('quantityHelp');

    const stockPreviewText =
        document.getElementById('stockPreviewText');

    const transactionTypeInputs =
        document.querySelectorAll(
            'input[name="transaction_type"]'
        );

    const submitBtn =
        document.getElementById('submitBtn');

    const form =
        document.getElementById('stockTransactionForm');

    const remarks =
        document.getElementById('remarks');

    const remarksCounter =
        document.getElementById('remarksCounter');


    let currentStock = 0;

    let currentUnit = 'Unit';


    /* ================================================================
       GET TRANSACTION TYPE
    ================================================================ */

    function getTransactionType() {

        const selected =
            document.querySelector(
                'input[name="transaction_type"]:checked'
            );

        return selected
            ? selected.value
            : 'stock_in';

    }


    /* ================================================================
       FORMAT NUMBER
    ================================================================ */

    function formatNumber(value) {

        return Number(value).toLocaleString(
            'en-IN',
            {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }
        );

    }


    /* ================================================================
       UPDATE ITEM DETAILS
    ================================================================ */

    function updateItemDetails() {

        const selectedOption =
            itemSelect.options[itemSelect.selectedIndex];


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            currentStock = 0;

            currentUnit = 'Unit';

            currentStockValue.textContent = '0';

            currentStockUnit.textContent = 'Unit';

            quantityInput.removeAttribute('max');

            quantityHelp.textContent =
                'Select a meal item first.';

            stockPreviewText.textContent =
                'Select an item and enter quantity to preview the updated stock.';

            return;

        }


        currentStock =
            parseFloat(
                selectedOption.dataset.stock || 0
            );

        currentUnit =
            selectedOption.dataset.unit || 'Unit';


        currentStockValue.textContent =
            formatNumber(currentStock);

        currentStockUnit.textContent =
            currentUnit;


        updateQuantityLimit();

        updatePreview();

    }


    /* ================================================================
       UPDATE QUANTITY LIMIT
    ================================================================ */

    function updateQuantityLimit() {

        const transactionType =
            getTransactionType();


        if (transactionType === 'stock_out') {

            quantityInput.max =
                currentStock;

            quantityHelp.innerHTML =
                '<i class="fas fa-circle-info"></i>' +
                ' Maximum Stock Out: ' +
                formatNumber(currentStock) +
                ' ' +
                currentUnit;

        } else {

            quantityInput.removeAttribute('max');

            quantityHelp.innerHTML =
                '<i class="fas fa-circle-info"></i>' +
                ' Stock In will increase the current stock.';

        }

    }


    /* ================================================================
       UPDATE STOCK PREVIEW
    ================================================================ */

    function updatePreview() {

        const quantity =
            parseFloat(
                quantityInput.value || 0
            );

        const transactionType =
            getTransactionType();


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

            const updatedStock =
                currentStock + quantity;


            stockPreviewText.innerHTML =
                'Current stock <strong>' +
                formatNumber(currentStock) +
                ' ' +
                currentUnit +
                '</strong> + Stock In <strong>' +
                formatNumber(quantity) +
                ' ' +
                currentUnit +
                '</strong> = Updated stock <strong>' +
                formatNumber(updatedStock) +
                ' ' +
                currentUnit +
                '</strong>.';

        } else {


            if (quantity > currentStock) {

                stockPreviewText.innerHTML =
                    '<span style="color:#f65343;font-weight:700;">' +
                    '<i class="fas fa-triangle-exclamation me-1"></i>' +
                    'Stock Out quantity cannot be greater than available stock.' +
                    '</span>';

                return;

            }


            const updatedStock =
                currentStock - quantity;


            stockPreviewText.innerHTML =
                'Current stock <strong>' +
                formatNumber(currentStock) +
                ' ' +
                currentUnit +
                '</strong> − Stock Out <strong>' +
                formatNumber(quantity) +
                ' ' +
                currentUnit +
                '</strong> = Updated stock <strong>' +
                formatNumber(updatedStock) +
                ' ' +
                currentUnit +
                '</strong>.';

        }

    }


    /* ================================================================
       REMARKS COUNTER
    ================================================================ */

    function updateRemarksCounter() {

        if (!remarks || !remarksCounter) {
            return;
        }

        remarksCounter.textContent =
            remarks.value.length + ' / 1000';

    }


    /* ================================================================
       EVENTS
    ================================================================ */

    itemSelect.addEventListener(
        'change',
        updateItemDetails
    );


    quantityInput.addEventListener(
        'input',
        updatePreview
    );


    transactionTypeInputs.forEach(
        function (input) {

            input.addEventListener(
                'change',
                function () {

                    updateQuantityLimit();

                    updatePreview();

                }
            );

        }
    );


    if (remarks) {

        remarks.addEventListener(
            'input',
            updateRemarksCounter
        );

    }


    /* ================================================================
       FORM SUBMIT
    ================================================================ */

    form.addEventListener(
        'submit',
        function (event) {

            const transactionType =
                getTransactionType();

            const quantity =
                parseFloat(
                    quantityInput.value || 0
                );


            if (
                transactionType === 'stock_out' &&
                quantity > currentStock
            ) {

                event.preventDefault();

                alert(
                    'Stock Out quantity cannot be greater than available stock.'
                );

                quantityInput.focus();

                return;

            }


            submitBtn.disabled = true;

            submitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin me-1"></i>' +
                ' Saving...';

        }
    );


    /* ================================================================
       INITIALIZE
    ================================================================ */

    updateItemDetails();

    updateRemarksCounter();

});

</script>

@endsection