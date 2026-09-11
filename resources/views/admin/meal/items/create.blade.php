@extends('layouts.app')

@section('title', 'Add Meal Item | Admin')

@section('content')

<div class="meal-form-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
             ========================================================= --}}
        <div class="meal-page-header">

            <div class="meal-heading-wrapper">

                <div class="meal-page-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div>

                    <div class="meal-breadcrumb">
                        <span>Meal Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Add Item</span>
                    </div>

                    <h4 class="meal-page-title">
                        Add Meal Item
                    </h4>

                    <p class="meal-page-subtitle">
                        Create a new item for your meal inventory.
                    </p>

                </div>

            </div>

            <a href="{{ route('admin.meal.items.index') }}"
               class="meal-back-btn">

                <i class="fas fa-arrow-left"></i>

                Back to Items

            </a>

        </div>


        {{-- =========================================================
             VALIDATION ERRORS
             ========================================================= --}}
        @if($errors->any())

            <div class="meal-alert">

                <div class="meal-alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="meal-alert-content">

                    <strong>
                        Please check the following:
                    </strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- =========================================================
             FORM CARD
             ========================================================= --}}
        <div class="meal-form-card">

            {{-- CARD HEADER --}}
            <div class="meal-form-card-header">

                <div class="meal-form-title">

                    <div class="meal-title-icon">
                        <i class="fas fa-plus"></i>
                    </div>

                    <div>

                        <h5>
                            Create Meal Item
                        </h5>

                        <p>
                            Enter the item information required for inventory management.
                        </p>

                    </div>

                </div>

                <div class="required-info">

                    <span>*</span>

                    Required fields

                </div>

            </div>


            {{-- FORM BODY --}}
            <div class="meal-form-card-body">

                <form action="{{ route('admin.meal.items.store') }}"
                      method="POST">

                    @csrf


                    {{-- =================================================
                         BASIC INFORMATION
                         ================================================= --}}
                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-icon section-blue">
                                <i class="fas fa-circle-info"></i>
                            </div>

                            <div>

                                <h6>
                                    Basic Information
                                </h6>

                                <span>
                                    Provide the main details of the meal item.
                                </span>

                            </div>

                        </div>


                        <div class="row g-4">

                            {{-- ITEM NAME --}}
                            <div class="col-md-6">

                                <label for="item_name"
                                       class="meal-form-label">

                                    Item Name

                                    <span>*</span>

                                </label>

                                <div class="input-wrapper">

                                    <i class="fas fa-bowl-food input-icon"></i>

                                    <input type="text"
                                           name="item_name"
                                           id="item_name"
                                           value="{{ old('item_name') }}"
                                           class="form-control meal-form-control input-with-icon @error('item_name') is-invalid @enderror"
                                           placeholder="e.g. Rice"
                                           maxlength="255"
                                           required>

                                </div>

                                @error('item_name')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- CATEGORY --}}
                            <div class="col-md-6">

                                <label for="category"
                                       class="meal-form-label">

                                    Category

                                </label>

                                <div class="input-wrapper">

                                    <i class="fas fa-layer-group input-icon"></i>

                                    <select name="category"
                                            id="category"
                                            class="form-select meal-form-control input-with-icon @error('category') is-invalid @enderror">

                                        <option value="">
                                            Select Category
                                        </option>

                                        <option value="Grains"
                                            {{ old('category') === 'Grains' ? 'selected' : '' }}>
                                            Grains
                                        </option>

                                        <option value="Pulses"
                                            {{ old('category') === 'Pulses' ? 'selected' : '' }}>
                                            Pulses
                                        </option>

                                        <option value="Vegetables"
                                            {{ old('category') === 'Vegetables' ? 'selected' : '' }}>
                                            Vegetables
                                        </option>

                                        <option value="Fruits"
                                            {{ old('category') === 'Fruits' ? 'selected' : '' }}>
                                            Fruits
                                        </option>

                                        <option value="Dairy"
                                            {{ old('category') === 'Dairy' ? 'selected' : '' }}>
                                            Dairy
                                        </option>

                                        <option value="Oil"
                                            {{ old('category') === 'Oil' ? 'selected' : '' }}>
                                            Oil
                                        </option>

                                        <option value="Spices"
                                            {{ old('category') === 'Spices' ? 'selected' : '' }}>
                                            Spices
                                        </option>

                                        <option value="Other"
                                            {{ old('category') === 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                </div>

                                @error('category')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- UNIT --}}
                            <div class="col-md-6">

                                <label for="unit"
                                       class="meal-form-label">

                                    Unit

                                    <span>*</span>

                                </label>

                                <div class="input-wrapper">

                                    <i class="fas fa-scale-balanced input-icon"></i>

                                    <select name="unit"
                                            id="unit"
                                            class="form-select meal-form-control input-with-icon @error('unit') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            Select Unit
                                        </option>

                                        <option value="Kg"
                                            {{ old('unit') === 'Kg' ? 'selected' : '' }}>
                                            Kilogram (Kg)
                                        </option>

                                        <option value="Litre"
                                            {{ old('unit') === 'Litre' ? 'selected' : '' }}>
                                            Litre
                                        </option>

                                        <option value="Packet"
                                            {{ old('unit') === 'Packet' ? 'selected' : '' }}>
                                            Packet
                                        </option>

                                        <option value="Piece"
                                            {{ old('unit') === 'Piece' ? 'selected' : '' }}>
                                            Piece
                                        </option>

                                        <option value="Dozen"
                                            {{ old('unit') === 'Dozen' ? 'selected' : '' }}>
                                            Dozen
                                        </option>

                                    </select>

                                </div>

                                @error('unit')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- MINIMUM STOCK --}}
                            <div class="col-md-6">

                                <label for="minimum_stock"
                                       class="meal-form-label">

                                    Minimum Stock

                                    <span>*</span>

                                </label>

                                <div class="input-wrapper">

                                    <i class="fas fa-boxes-stacked input-icon"></i>

                                    <input type="number"
                                           name="minimum_stock"
                                           id="minimum_stock"
                                           value="{{ old('minimum_stock', 0) }}"
                                           class="form-control meal-form-control input-with-icon @error('minimum_stock') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="e.g. 10"
                                           required>

                                </div>

                                <div class="field-help">

                                    <i class="fas fa-circle-info"></i>

                                    Used to identify low-stock items.

                                </div>

                                @error('minimum_stock')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                 OPENING STOCK / INITIAL STOCK IN
                                 ================================================= --}}
                            <div class="col-md-6">

                                <label for="opening_stock"
                                       class="meal-form-label">

                                    Opening Stock

                                    <span>*</span>

                                </label>

                                <div class="input-wrapper">

                                    <i class="fas fa-arrow-down input-icon stock-in-icon"></i>

                                    <input type="number"
                                           name="opening_stock"
                                           id="opening_stock"
                                           value="{{ old('opening_stock', 0) }}"
                                           class="form-control meal-form-control input-with-icon @error('opening_stock') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="e.g. 50"
                                           required>

                                </div>

                                <div class="stock-in-help">

                                    <span class="stock-in-badge">
                                        <i class="fas fa-arrow-down"></i>
                                        Stock In
                                    </span>

                                    <span>
                                        Initial quantity added to inventory.
                                    </span>

                                </div>

                                @error('opening_stock')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- OPENING STOCK NOTE --}}
                            <div class="col-md-6">

                                <div class="opening-stock-info">

                                    <div class="opening-stock-info-icon">
                                        <i class="fas fa-circle-info"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            Opening Stock is Stock In
                                        </strong>

                                        <p>
                                            When you create a new meal item,
                                            the opening quantity will be treated
                                            as an initial stock-in entry.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         ADDITIONAL INFORMATION
                         ================================================= --}}
                    <div class="form-section additional-section">

                        <div class="section-heading">

                            <div class="section-icon section-cyan">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <div>

                                <h6>
                                    Additional Information
                                </h6>

                                <span>
                                    Add optional notes about this meal item.
                                </span>

                            </div>

                        </div>


                        <div class="description-card">

                            <div class="description-header">

                                <div class="description-title">

                                    <div class="description-icon">
                                        <i class="fas fa-pen"></i>
                                    </div>

                                    <label for="description">
                                        Description
                                    </label>

                                    <span>
                                        Optional
                                    </span>

                                </div>

                            </div>


                            <textarea name="description"
                                      id="description"
                                      rows="6"
                                      maxlength="2000"
                                      class="form-control meal-textarea @error('description') is-invalid @enderror"
                                      placeholder="Enter storage information, quality details, preparation notes, or any other useful information...">{{ old('description') }}</textarea>


                            <div class="description-footer">

                                <div class="field-help">

                                    <i class="fas fa-lightbulb"></i>

                                    Add any useful information for inventory management.

                                </div>

                                <span class="character-count">

                                    <span id="descriptionCount">
                                        0
                                    </span>/2000

                                </span>

                            </div>


                            @error('description')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- =================================================
                         FORM ACTIONS
                         ================================================= --}}
                    <div class="meal-form-actions">

                        <a href="{{ route('admin.meal.items.index') }}"
                           class="meal-cancel-btn">

                            <i class="fas fa-xmark"></i>

                            Cancel

                        </a>


                        <button type="submit"
                                class="meal-save-btn">

                            <i class="fas fa-check"></i>

                            Save Item

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PAGE
   ========================================================= */

.meal-form-page {
    min-height: calc(100vh - 70px);
    background: #f6f8fb;
    color: #263142;
    font-family: 'Inter', sans-serif;
}


/* =========================================================
   PAGE HEADER
   ========================================================= */

.meal-page-header {
    min-height: 62px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.meal-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 14px;
}

.meal-page-icon {
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: linear-gradient(
        135deg,
        #147cf5,
        #1268ca
    );
    color: #fff;
    font-size: 18px;
    box-shadow:
        0 7px 18px rgba(20, 124, 245, .18);
}

.meal-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
    color: #98a1ae;
    font-size: 10.5px;
    font-weight: 500;
}

.meal-breadcrumb i {
    color: #bcc3cc;
    font-size: 7px;
}

.meal-page-title {
    margin: 0 0 3px;
    color: #202938;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: -.45px;
}

.meal-page-subtitle {
    margin: 0;
    color: #7c8796;
    font-size: 13px;
    line-height: 1.5;
}


/* =========================================================
   BACK BUTTON
   ========================================================= */

.meal-back-btn {
    min-height: 41px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border: 1px solid #dfe4ea;
    border-radius: 8px;
    background: #fff;
    color: #586474 !important;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    box-shadow:
        0 3px 9px rgba(31, 41, 55, .035);
    transition: all .2s ease;
}

.meal-back-btn i {
    margin-right: 7px;
    font-size: 11px;
}

.meal-back-btn:hover {
    border-color: #bcd7f8;
    background: #f8fbff;
    color: #147cf5 !important;
    transform: translateY(-1px);
    box-shadow:
        0 6px 14px rgba(20, 124, 245, .10);
}


/* =========================================================
   VALIDATION ALERT
   ========================================================= */

.meal-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    padding: 14px 16px;
    border: 1px solid #ffd7d3;
    border-left: 4px solid #f65343;
    border-radius: 9px;
    background: #fff5f4;
    color: #b52e23;
    box-shadow:
        0 3px 12px rgba(31, 41, 55, .035);
}

.meal-alert-icon {
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #ffe4e1;
    color: #f65343;
    font-size: 13px;
}

.meal-alert-content {
    padding-top: 1px;
}

.meal-alert-content strong {
    display: block;
    margin-bottom: 4px;
    color: #a92f24;
    font-size: 13px;
    font-weight: 700;
}

.meal-alert-content ul {
    margin: 0;
    padding-left: 17px;
    color: #bd392f;
    font-size: 12px;
}

.meal-alert-content li {
    margin-bottom: 2px;
}


/* =========================================================
   MAIN CARD
   ========================================================= */

.meal-form-card {
    position: relative;
    overflow: hidden;
    border: 1px solid #e8edf3;
    border-radius: 13px;
    background: #fff;
    box-shadow:
        0 6px 25px rgba(31, 41, 55, .055);
}

.meal-form-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(
        90deg,
        #147cf5,
        #1987e8,
        #21b8d6
    );
}


/* =========================================================
   CARD HEADER
   ========================================================= */

.meal-form-card-header {
    min-height: 79px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 17px 24px;
    border-bottom: 1px solid #edf0f5;
}

.meal-form-title {
    display: flex;
    align-items: center;
    gap: 13px;
}

.meal-title-icon {
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d7e8ff;
    border-radius: 10px;
    background: linear-gradient(
        135deg,
        #eaf3ff,
        #dcecff
    );
    color: #147cf5;
    font-size: 14px;
}

.meal-form-title h5 {
    margin: 0 0 3px;
    color: #273142;
    font-size: 15px;
    font-weight: 700;
}

.meal-form-title p {
    margin: 0;
    color: #8a94a3;
    font-size: 11.5px;
}

.required-info {
    padding: 6px 10px;
    border-radius: 6px;
    background: #fff7f5;
    color: #8f98a5;
    font-size: 10.5px;
    font-weight: 500;
}

.required-info span {
    margin-right: 3px;
    color: #f65343;
    font-size: 14px;
}


/* =========================================================
   CARD BODY
   ========================================================= */

.meal-form-card-body {
    padding: 29px;
}


/* =========================================================
   FORM SECTION
   ========================================================= */

.form-section {
    padding-bottom: 29px;
    border-bottom: 1px solid #edf0f5;
}

.additional-section {
    padding-top: 29px;
    padding-bottom: 0;
    border-bottom: 0;
}


/* =========================================================
   SECTION HEADING
   ========================================================= */

.section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 23px;
}

.section-icon {
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    font-size: 13px;
}

.section-blue {
    border: 1px solid #dcecff;
    background: #eaf3ff;
    color: #147cf5;
}

.section-cyan {
    border: 1px solid #d5f5fa;
    background: #e7fbfe;
    color: #18b5d5;
}

.section-heading h6 {
    margin: 0 0 3px;
    color: #303846;
    font-size: 14px;
    font-weight: 700;
}

.section-heading span {
    color: #8a94a3;
    font-size: 11.5px;
}


/* =========================================================
   FORM LABEL
   ========================================================= */

.meal-form-label {
    display: block;
    margin-bottom: 8px;
    color: #374151;
    font-size: 12.5px;
    font-weight: 650;
}

.meal-form-label > span {
    margin-left: 2px;
    color: #f65343;
}


/* =========================================================
   INPUT WRAPPER
   ========================================================= */

.input-wrapper {
    position: relative;
    width: 100%;
}

.input-icon {
    position: absolute;
    top: 50%;
    left: 14px;
    z-index: 2;
    width: 17px;
    transform: translateY(-50%);
    color: #98a2b0;
    font-size: 11.5px;
    pointer-events: none;
    transition: color .2s ease;
}

.input-wrapper:focus-within .input-icon {
    color: #147cf5;
}

.stock-in-icon {
    color: #18b5d5;
}


/* =========================================================
   FORM CONTROLS
   ========================================================= */

.meal-form-control {
    width: 100%;
    min-height: 45px;
    padding: 9px 13px;
    border: 1px solid #dfe4ea;
    border-radius: 8px;
    background: #fff;
    color: #374151;
    font-size: 12.5px;
    box-shadow: none;
    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        background-color .2s ease;
}

.input-with-icon {
    padding-left: 40px;
}

.meal-form-control::placeholder {
    color: #a7afba;
}

.meal-form-control:hover {
    border-color: #cbd3dd;
}

.meal-form-control:focus {
    border-color: #147cf5;
    background: #fff;
    color: #263142;
    outline: none;
    box-shadow:
        0 0 0 3px rgba(20, 124, 245, .09);
}

.meal-form-control.is-invalid {
    border-color: #dc3545;
    background-image: none;
    box-shadow:
        0 0 0 3px rgba(220, 53, 69, .06);
}

select.meal-form-control {
    cursor: pointer;
}


/* =========================================================
   STOCK IN HELP
   ========================================================= */

.stock-in-help {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 7px;
    color: #8a94a3;
    font-size: 10.5px;
    line-height: 1.5;
}

.stock-in-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 7px;
    border-radius: 5px;
    background: #e8fbf8;
    color: #149d88;
    font-size: 9.5px;
    font-weight: 700;
    white-space: nowrap;
}

.stock-in-badge i {
    font-size: 8px;
}


/* =========================================================
   OPENING STOCK INFO
   ========================================================= */

.opening-stock-info {
    min-height: 72px;
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 13px 14px;
    border: 1px solid #d7f1f5;
    border-radius: 9px;
    background: linear-gradient(
        135deg,
        #f1fcfe,
        #f8fdff
    );
}

.opening-stock-info-icon {
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #e0f8fb;
    color: #18b5d5;
    font-size: 11px;
}

.opening-stock-info strong {
    display: block;
    margin-bottom: 3px;
    color: #287483;
    font-size: 11.5px;
    font-weight: 700;
}

.opening-stock-info p {
    margin: 0;
    color: #7c9198;
    font-size: 10.5px;
    line-height: 1.5;
}


/* =========================================================
   FIELD HELP
   ========================================================= */

.field-help {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 7px;
    color: #8a94a3;
    font-size: 10.5px;
    line-height: 1.5;
}

.field-help i {
    color: #9ca6b3;
    font-size: 9px;
}


/* =========================================================
   FIELD ERROR
   ========================================================= */

.field-error {
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
    line-height: 1.4;
}


/* =========================================================
   DESCRIPTION CARD
   ========================================================= */

.description-card {
    padding: 19px;
    border: 1px solid #e8edf3;
    border-radius: 11px;
    background: linear-gradient(
        180deg,
        #fbfdff 0%,
        #fff 100%
    );
    box-shadow:
        0 3px 12px rgba(31, 41, 55, .035);
    transition:
        border-color .2s ease,
        box-shadow .2s ease;
}

.description-card:focus-within {
    border-color: #c7def9;
    box-shadow:
        0 5px 17px rgba(20, 124, 245, .07);
}

.description-header {
    margin-bottom: 11px;
}

.description-title {
    display: flex;
    align-items: center;
    gap: 9px;
}

.description-title label {
    margin: 0;
    color: #374151;
    font-size: 12.5px;
    font-weight: 650;
}

.description-title > span {
    padding: 3px 7px;
    border-radius: 10px;
    background: #f1f4f7;
    color: #8b95a3;
    font-size: 9px;
    font-weight: 600;
}

.description-icon {
    width: 29px;
    height: 29px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #edf7ff;
    color: #147cf5;
    font-size: 10px;
}


/* =========================================================
   TEXTAREA
   ========================================================= */

.meal-textarea {
    width: 100%;
    min-height: 170px;
    padding: 14px 15px !important;
    border-radius: 8px;
    resize: vertical;
    color: #374151;
    font-size: 12.5px;
    line-height: 1.65;
    background: #fff;
}

.meal-textarea::placeholder {
    color: #adb5c0;
    font-size: 12px;
}

.meal-textarea:focus {
    border-color: #147cf5;
    box-shadow:
        0 0 0 3px rgba(20, 124, 245, .09);
}


/* =========================================================
   DESCRIPTION FOOTER
   ========================================================= */

.description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-top: 8px;
}

.description-footer .field-help {
    margin-top: 0;
}

.character-count {
    flex-shrink: 0;
    color: #9aa3af;
    font-size: 10px;
}


/* =========================================================
   FORM ACTIONS
   ========================================================= */

.meal-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 29px;
    padding-top: 21px;
    border-top: 1px solid #edf0f5;
}


/* =========================================================
   CANCEL BUTTON
   ========================================================= */

.meal-cancel-btn {
    min-height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 18px;
    border: 1px solid #dfe4ea;
    border-radius: 8px;
    background: #fff;
    color: #586474 !important;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
}

.meal-cancel-btn i {
    margin-right: 6px;
    font-size: 11px;
}

.meal-cancel-btn:hover {
    border-color: #cbd2db;
    background: #f6f8fb;
    color: #374151 !important;
    transform: translateY(-1px);
    box-shadow:
        0 4px 10px rgba(31, 41, 55, .05);
}


/* =========================================================
   SAVE BUTTON
   ========================================================= */

.meal-save-btn {
    min-height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 21px;
    border: 1px solid #147cf5;
    border-radius: 8px;
    background: linear-gradient(
        135deg,
        #147cf5,
        #1268ca
    );
    color: #fff !important;
    font-size: 12.5px;
    font-weight: 650;
    box-shadow:
        0 5px 13px rgba(20, 124, 245, .18);
    transition: all .2s ease;
}

.meal-save-btn i {
    margin-right: 6px;
    font-size: 11px;
}

.meal-save-btn:hover {
    border-color: #1268ca;
    background: linear-gradient(
        135deg,
        #1268ca,
        #1058ad
    );
    color: #fff !important;
    transform: translateY(-1px);
    box-shadow:
        0 8px 18px rgba(20, 124, 245, .24);
}

.meal-save-btn:focus {
    border-color: #1268ca;
    background: #1268ca;
    color: #fff !important;
    box-shadow:
        0 0 0 3px rgba(20, 124, 245, .12);
}


/* =========================================================
   TABLET
   ========================================================= */

@media (max-width: 768px) {

    .meal-page-header {
        align-items: flex-start;
    }

    .meal-page-title {
        font-size: 21px;
    }

    .meal-form-card-header {
        padding: 16px 19px;
    }

    .meal-form-card-body {
        padding: 23px;
    }

    .required-info {
        display: none;
    }

}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 576px) {

    .meal-form-page .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .meal-page-header {
        flex-direction: column;
        align-items: stretch;
        gap: 14px;
    }

    .meal-heading-wrapper {
        align-items: flex-start;
    }

    .meal-page-icon {
        width: 42px;
        height: 42px;
        flex-basis: 42px;
        font-size: 16px;
    }

    .meal-page-title {
        font-size: 20px;
    }

    .meal-page-subtitle {
        font-size: 12px;
    }

    .meal-back-btn {
        width: 100%;
    }

    .meal-form-card {
        border-radius: 10px;
    }

    .meal-form-card-header {
        padding: 15px;
    }

    .meal-form-title p {
        display: none;
    }

    .meal-form-card-body {
        padding: 17px;
    }

    .section-heading {
        margin-bottom: 18px;
    }

    .section-heading > div:last-child span {
        display: none;
    }

    .description-card {
        padding: 14px;
    }

    .meal-textarea {
        min-height: 180px;
    }

    .description-footer {
        align-items: flex-start;
    }

    .stock-in-help {
        align-items: flex-start;
        flex-direction: column;
    }

    .meal-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .meal-cancel-btn,
    .meal-save-btn {
        width: 100%;
    }

}


/* =========================================================
   SMALL MOBILE
   ========================================================= */

@media (max-width: 380px) {

    .meal-page-icon {
        width: 39px;
        height: 39px;
        flex-basis: 39px;
        font-size: 14px;
    }

    .meal-page-title {
        font-size: 18px;
    }

    .meal-form-card-body {
        padding: 14px;
    }

    .meal-form-title h5 {
        font-size: 14px;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const description =
        document.getElementById('description');

    const counter =
        document.getElementById('descriptionCount');

    if (!description || !counter) {
        return;
    }

    const updateCounter = () => {

        counter.textContent =
            description.value.length;

    };

    description.addEventListener(
        'input',
        updateCounter
    );

    updateCounter();

});

</script>

@endpush