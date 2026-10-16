
@extends('layouts.app')

@section('title', 'Add Meal Item | Admin')
@section('page-title', 'Add Meal Item')

@section('content')

<div class="meal-form-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="meal-page-header">
            <div class="meal-heading-wrapper">
                <div class="meal-page-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div class="meal-heading-content">
                    <div class="meal-breadcrumb">
                        <span>Meal Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Add Item</span>
                    </div>

                    <h4 class="meal-page-title">Add Meal Item</h4>
                    <p class="meal-page-subtitle">
                        Create a new item for your meal inventory.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.admin.meal.items.index') }}"
               class="meal-back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Items
            </a>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="meal-alert" role="alert">
                <div class="meal-alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="meal-alert-content">
                    <strong>Please check the following:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN FORM CARD --}}
        <div class="meal-form-card">

            {{-- CARD HEADER --}}
            <div class="meal-form-card-header">
                <div class="meal-form-title">
                    <div class="meal-title-icon">
                        <i class="fas fa-plus"></i>
                    </div>

                    <div>
                        <h5>Create Meal Item</h5>
                        <p>
                            Enter the item information required for inventory management.
                        </p>
                    </div>
                </div>

                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            {{-- FORM BODY --}}
            <div class="meal-form-card-body">

                <form action="{{ route('admin.admin.meal.items.store') }}"
                      method="POST">
                    @csrf

                    {{-- SECTION 01: BASIC INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-number">01</div>

                            <div class="section-icon section-green">
                                <i class="fas fa-circle-info"></i>
                            </div>

                            <div class="section-heading-content">
                                <h6>Basic Information</h6>
                                <span>
                                    Provide the main details of the meal item.
                                </span>
                            </div>
                        </div>

                        <div class="row g-4">

                            {{-- ITEM NAME --}}
                            <div class="col-md-6">
                                <label for="item_name" class="meal-form-label">
                                    Item Name <span>*</span>
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
                                           autocomplete="off"
                                           required>
                                </div>

                                @error('item_name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- CATEGORY --}}
                            <div class="col-md-6">
                                <label for="category" class="meal-form-label">
                                    Category
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-layer-group input-icon"></i>

                                    <select name="category"
                                            id="category"
                                            class="form-select meal-form-control input-with-icon @error('category') is-invalid @enderror">
                                        <option value="">Select Category</option>

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
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- UNIT --}}
                            <div class="col-md-6">
                                <label for="unit" class="meal-form-label">
                                    Unit <span>*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-scale-balanced input-icon"></i>

                                    <select name="unit"
                                            id="unit"
                                            class="form-select meal-form-control input-with-icon @error('unit') is-invalid @enderror"
                                            required>
                                        <option value="">Select Unit</option>

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
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- MINIMUM STOCK --}}
                            <div class="col-md-6">
                                <label for="minimum_stock" class="meal-form-label">
                                    Minimum Stock <span>*</span>
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
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- OPENING STOCK --}}
                            <div class="col-md-6">
                                <label for="opening_stock" class="meal-form-label">
                                    Opening Stock <span>*</span>
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

                                    <span>Initial quantity added to inventory.</span>
                                </div>

                                @error('opening_stock')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- OPENING STOCK INFORMATION --}}
                            <div class="col-md-6">
                                <div class="opening-stock-info">
                                    <div class="opening-stock-info-icon">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>

                                    <div>
                                        <strong>Opening Stock is Stock In</strong>
                                        <p>
                                            When you create a new meal item, the opening
                                            quantity will be treated as an initial
                                            stock-in entry.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 02: ADDITIONAL INFORMATION --}}
                    <div class="form-section additional-section">

                        <div class="section-heading">
                            <div class="section-number">02</div>

                            <div class="section-icon section-wood">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <div class="section-heading-content">
                                <h6>Additional Information</h6>
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

                                    <label for="description">Description</label>

                                    <span class="optional-badge">Optional</span>
                                </div>
                            </div>

                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      maxlength="2000"
                                      class="form-control meal-textarea @error('description') is-invalid @enderror"
                                      placeholder="Enter storage information, quality details, preparation notes, or other useful information...">{{ old('description') }}</textarea>

                            <div class="description-footer">
                                <div class="field-help">
                                    <i class="fas fa-lightbulb"></i>
                                    Add useful information for inventory management.
                                </div>

                                <span class="character-count">
                                    <span id="descriptionCount">0</span>/2000
                                </span>
                            </div>

                            @error('description')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- FORM ACTIONS --}}
                    <div class="meal-form-actions">
                        <a href="{{ route('admin.admin.meal.items.index') }}"
                           class="meal-cancel-btn">
                            <i class="fas fa-xmark"></i>
                            Cancel
                        </a>

                        <button type="submit" class="meal-save-btn">
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
   SCHOOL DIARY DESIGN SYSTEM — ADD MEAL ITEM
   Scoped to this page to avoid affecting other modules.
========================================================= */

.meal-form-page {
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
    --soft-paper: #f8f7f0;

    min-height: calc(100vh - 70px);
    background: #f5f3e9;
    color: var(--ink);
    font-family: 'Inter', sans-serif;
    font-size: 12px;
}

.meal-form-page .container-fluid {
    max-width: 1600px;
}

/* PAGE HEADER */

.meal-form-page .meal-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 21px;
    padding: 4px 0 4px 14px;
    border-left: 4px solid var(--green);
}

.meal-form-page .meal-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.meal-form-page .meal-page-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    border: 1px solid #cbdcc9;
    border-radius: 10px;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 16px;
}

.meal-form-page .meal-heading-content {
    min-width: 0;
}

.meal-form-page .meal-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 4px;
    color: #8a9185;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .65px;
    text-transform: uppercase;
}

.meal-form-page .meal-breadcrumb i {
    color: #b1ad9f;
    font-size: 7px;
}

.meal-form-page .meal-page-title {
    margin: 0 0 3px;
    color: var(--ink);
    font-size: 22px;
    font-weight: 750;
    letter-spacing: -.45px;
    line-height: 1.25;
}

.meal-form-page .meal-page-subtitle {
    margin: 0;
    color: #7d877b;
    font-size: 11px;
    line-height: 1.5;
}

/* BACK BUTTON */

.meal-form-page .meal-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    flex-shrink: 0;
    border: 1px solid var(--border);
    border-radius: 7px;
    background: var(--paper);
    color: var(--ink) !important;
    font-size: 10.5px;
    font-weight: 700;
    text-decoration: none;
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}

.meal-form-page .meal-back-btn:hover {
    border-color: #b7cbb4;
    background: var(--green-light);
    color: var(--green-dark) !important;
    transform: translateY(-1px);
}

/* VALIDATION ALERT */

.meal-form-page .meal-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 17px;
    padding: 12px 14px;
    border: 1px solid #e9c9c2;
    border-left: 4px solid var(--red);
    border-radius: 8px;
    background: #fff8f5;
    color: #8e3f36;
}

.meal-form-page .meal-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    border-radius: 7px;
    background: #f8e8e2;
    color: var(--red);
    font-size: 12px;
}

.meal-form-page .meal-alert-content strong {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 750;
}

.meal-form-page .meal-alert-content ul {
    margin: 0;
    padding-left: 16px;
    font-size: 10.5px;
    line-height: 1.65;
}

.meal-form-page .meal-alert-content li {
    margin-bottom: 2px;
}

/* MAIN CARD */

.meal-form-page .meal-form-card {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 11px;
    background: var(--paper);
    box-shadow: 0 4px 16px rgba(57, 65, 46, .045);
}

.meal-form-page .meal-form-card::before {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--green);
    content: "";
}

/* CARD HEADER */

.meal-form-page .meal-form-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 21px;
    border-bottom: 1px solid var(--border);
    background: #fffef9;
}

.meal-form-page .meal-form-title {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.meal-form-page .meal-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    border: 1px solid #d2e1ce;
    border-radius: 8px;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 12px;
}

.meal-form-page .meal-form-title h5 {
    margin: 0 0 3px;
    color: var(--ink);
    font-size: 13px;
    font-weight: 750;
}

.meal-form-page .meal-form-title p {
    margin: 0;
    color: #858d80;
    font-size: 10px;
    line-height: 1.5;
}

.meal-form-page .required-info {
    flex-shrink: 0;
    padding: 6px 9px;
    border: 1px solid #e9e1d3;
    border-radius: 6px;
    background: #f8f5eb;
    color: #817c6d;
    font-size: 9.5px;
    font-weight: 650;
}

.meal-form-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* CARD BODY */

.meal-form-page .meal-form-card-body {
    padding: 23px;
}

/* FORM SECTIONS */

.meal-form-page .form-section {
    padding-bottom: 25px;
    border-bottom: 1px solid var(--border);
}

.meal-form-page .additional-section {
    padding-top: 24px;
    padding-bottom: 0;
    border-bottom: 0;
}

/* NUMBERED SECTION HEADINGS */

.meal-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.meal-form-page .section-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    flex: 0 0 25px;
    border: 1px solid #d6dfcf;
    border-radius: 6px;
    background: #f0f4e9;
    color: var(--green-dark);
    font-size: 9px;
    font-weight: 800;
}

.meal-form-page .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 33px;
    height: 33px;
    flex: 0 0 33px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 12px;
}

.meal-form-page .section-green {
    border-color: #d2e2ce;
    background: var(--green-light);
    color: var(--green-dark);
}

.meal-form-page .section-wood {
    border-color: #e8d8c5;
    background: #f8efe3;
    color: var(--wood);
}

.meal-form-page .section-heading-content {
    min-width: 0;
}

.meal-form-page .section-heading h6 {
    margin: 0 0 3px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.meal-form-page .section-heading-content > span {
    display: block;
    color: #858d80;
    font-size: 10px;
    line-height: 1.45;
}

/* LABELS */

.meal-form-page .meal-form-label {
    display: block;
    margin-bottom: 7px;
    color: #39463b;
    font-size: 10.5px;
    font-weight: 750;
}

.meal-form-page .meal-form-label > span {
    color: var(--red);
}

/* INPUT ICONS */

.meal-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.meal-form-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #939b8d;
    font-size: 10.5px;
    pointer-events: none;
    transition: color .2s ease;
}

.meal-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.meal-form-page .stock-in-icon {
    color: var(--cyan);
}

/* FORM CONTROLS */

.meal-form-page .meal-form-control {
    width: 100%;
    min-height: 39px;
    padding: 8px 11px;
    border: 1px solid #dddccd;
    border-radius: 7px;
    background-color: #fffefa;
    color: var(--ink);
    font-family: inherit;
    font-size: 11px;
    box-shadow: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.meal-form-page .input-with-icon {
    padding-left: 35px;
}

.meal-form-page .meal-form-control::placeholder {
    color: #a5a697;
}

.meal-form-page .meal-form-control:hover {
    border-color: #c9cfbd;
}

.meal-form-page .meal-form-control:focus {
    border-color: var(--green);
    background-color: #fffefa;
    color: var(--ink);
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .09);
}

.meal-form-page .meal-form-control.is-invalid,
.meal-form-page .meal-textarea.is-invalid {
    border-color: var(--red);
    background-image: none;
    box-shadow: 0 0 0 2px rgba(189, 81, 72, .06);
}

.meal-form-page select.meal-form-control {
    cursor: pointer;
}

/* STOCK IN BADGE */

.meal-form-page .stock-in-help {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 7px;
    color: #858d80;
    font-size: 9.5px;
    line-height: 1.5;
}

.meal-form-page .stock-in-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 7px;
    border: 1px solid #cfe1d5;
    border-radius: 5px;
    background: #edf5eb;
    color: var(--green-dark);
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
}

/* OPENING STOCK NOTE */

.meal-form-page .opening-stock-info {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    min-height: 66px;
    height: 100%;
    padding: 12px;
    border: 1px solid #d8e3d3;
    border-radius: 8px;
    background: #f4f7ed;
}

.meal-form-page .opening-stock-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    flex: 0 0 28px;
    border: 1px solid #d8e3d3;
    border-radius: 7px;
    background: #e8efdf;
    color: var(--green);
    font-size: 10px;
}

.meal-form-page .opening-stock-info strong {
    display: block;
    margin-bottom: 4px;
    color: var(--green-dark);
    font-size: 10.5px;
    font-weight: 800;
}

.meal-form-page .opening-stock-info p {
    margin: 0;
    color: #788273;
    font-size: 9.5px;
    line-height: 1.6;
}

/* HELP TEXT AND ERRORS */

.meal-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    margin-top: 7px;
    color: #858d80;
    font-size: 9.5px;
    line-height: 1.55;
}

.meal-form-page .field-help i {
    margin-top: 2px;
    color: var(--wood);
    font-size: 9px;
}

.meal-form-page .field-error {
    margin-top: 5px;
    color: var(--red);
    font-size: 10px;
    line-height: 1.45;
}

/* DESCRIPTION CARD */

.meal-form-page .description-card {
    padding: 16px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: #fbfaf4;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.meal-form-page .description-card:focus-within {
    border-color: #b7cbb4;
    box-shadow: 0 3px 12px rgba(52, 119, 90, .06);
}

.meal-form-page .description-header {
    margin-bottom: 10px;
}

.meal-form-page .description-title {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.meal-form-page .description-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    border: 1px solid #e8d8c5;
    border-radius: 7px;
    background: #f8efe3;
    color: var(--wood);
    font-size: 10px;
}

.meal-form-page .description-title label {
    margin: 0;
    color: #39463b;
    font-size: 10.5px;
    font-weight: 750;
}

.meal-form-page .optional-badge {
    padding: 3px 7px;
    border: 1px solid #e4e0d4;
    border-radius: 5px;
    background: #f4f1e8;
    color: #858273;
    font-size: 9px;
    font-weight: 650;
}

/* TEXTAREA */

.meal-form-page .meal-textarea {
    display: block;
    width: 100%;
    min-height: 125px;
    padding: 11px 12px;
    border: 1px solid #dddccd;
    border-radius: 7px;
    background: #fffefa;
    color: var(--ink);
    font-family: inherit;
    font-size: 11px;
    line-height: 1.7;
    resize: vertical;
    box-shadow: none;
}

.meal-form-page .meal-textarea::placeholder {
    color: #a5a697;
    font-size: 10.5px;
}

.meal-form-page .meal-textarea:focus {
    border-color: var(--green);
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .09);
}

/* DESCRIPTION FOOTER */

.meal-form-page .description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 8px;
}

.meal-form-page .description-footer .field-help {
    margin-top: 0;
}

.meal-form-page .character-count {
    flex-shrink: 0;
    color: #858d80;
    font-size: 9.5px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

/* ACTION BAR */

.meal-form-page .meal-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 23px;
    padding-top: 17px;
    border-top: 1px solid var(--border);
}

.meal-form-page .meal-cancel-btn,
.meal-form-page .meal-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 37px;
    padding: 8px 15px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 10.5px;
    font-weight: 750;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}

.meal-form-page .meal-cancel-btn {
    border: 1px solid #d9d6c9;
    background: var(--paper);
    color: #586257 !important;
}

.meal-form-page .meal-cancel-btn:hover {
    border-color: #c7c6b6;
    background: #f1efe5;
    color: var(--ink) !important;
}

.meal-form-page .meal-save-btn {
    border: 1px solid var(--green-dark);
    background: var(--green);
    color: #fff !important;
}

.meal-form-page .meal-save-btn:hover {
    border-color: var(--green-dark);
    background: var(--green-dark);
    color: #fff !important;
    transform: translateY(-1px);
}

.meal-form-page .meal-save-btn:focus-visible,
.meal-form-page .meal-cancel-btn:focus-visible,
.meal-form-page .meal-back-btn:focus-visible {
    outline: 3px solid rgba(52, 119, 90, .25);
    outline-offset: 2px;
}

/* TABLET */

@media (max-width: 991.98px) {
    .meal-form-page .meal-form-card-body {
        padding: 20px;
    }

    .meal-form-page .meal-form-card-header {
        padding: 15px 18px;
    }
}

/* MOBILE */

@media (max-width: 767.98px) {
    .meal-form-page .meal-page-header {
        align-items: flex-start;
    }

    .meal-form-page .meal-page-title {
        font-size: 20px;
    }

    .meal-form-page .required-info {
        display: none;
    }

    .meal-form-page .meal-form-card-body {
        padding: 17px;
    }
}

@media (max-width: 480px) {
    .meal-form-page .container-fluid {
        padding-right: 10px !important;
        padding-left: 10px !important;
    }

    .meal-form-page .meal-page-header {
        flex-direction: column;
        align-items: stretch;
        gap: 13px;
        padding-left: 10px;
    }

    .meal-form-page .meal-heading-wrapper {
        align-items: flex-start;
        gap: 9px;
    }

    .meal-form-page .meal-page-icon {
        width: 38px;
        height: 38px;
        flex-basis: 38px;
        font-size: 14px;
    }

    .meal-form-page .meal-page-title {
        font-size: 19px;
    }

    .meal-form-page .meal-page-subtitle {
        font-size: 10.5px;
    }

    .meal-form-page .meal-back-btn {
        width: 100%;
    }

    .meal-form-page .meal-form-card {
        border-radius: 9px;
    }

    .meal-form-page .meal-form-card-header {
        padding: 13px;
    }

    .meal-form-page .meal-form-title p {
        display: none;
    }

    .meal-form-page .meal-form-card-body {
        padding: 14px;
    }

    .meal-form-page .section-heading {
        gap: 7px;
        margin-bottom: 16px;
    }

    .meal-form-page .section-number {
        width: 23px;
        height: 23px;
        flex-basis: 23px;
        font-size: 8px;
    }

    .meal-form-page .section-icon {
        width: 30px;
        height: 30px;
        flex-basis: 30px;
    }

    .meal-form-page .section-heading-content > span {
        font-size: 9px;
    }

    .meal-form-page .form-section {
        padding-bottom: 19px;
    }

    .meal-form-page .additional-section {
        padding-top: 19px;
        padding-bottom: 0;
    }

    .meal-form-page .description-card {
        padding: 12px;
    }

    .meal-form-page .description-footer {
        align-items: flex-start;
    }

    .meal-form-page .stock-in-help {
        align-items: flex-start;
    }

    .meal-form-page .meal-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .meal-form-page .meal-cancel-btn,
    .meal-form-page .meal-save-btn {
        width: 100%;
    }
}

/* ACCESSIBILITY */

@media (prefers-reduced-motion: reduce) {
    .meal-form-page *,
    .meal-form-page *::before,
    .meal-form-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const description = document.getElementById('description');
    const counter = document.getElementById('descriptionCount');

    if (!description || !counter) {
        return;
    }

    const updateCounter = function () {
        counter.textContent = description.value.length;
    };

    description.addEventListener('input', updateCounter);
    updateCounter();
});
</script>
@endpush
