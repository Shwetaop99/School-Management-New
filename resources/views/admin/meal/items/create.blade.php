@extends('layouts.app')

@section('title', 'Add Meal Item | Admin')
@section('page-title', 'Add Meal Item')

@section('content')

@php
    $categories = ['Grains', 'Pulses', 'Vegetables', 'Fruits', 'Dairy', 'Oil', 'Spices', 'Other'];

    $units = [
        'Kg'     => 'Kilogram (Kg)',
        'Litre'  => 'Litre',
        'Packet' => 'Packet',
        'Piece'  => 'Piece',
        'Dozen'  => 'Dozen',
    ];
@endphp

<div class="meal-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        INVENTORY <span>/</span> MEAL ITEMS <span>/</span> ADD ITEM
                    </div>

                    <h2>Add Meal Item</h2>

                    <p>Create a new item for your meal inventory.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.meal.items.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Items
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
                        <i class="fas fa-plus"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">INVENTORY REGISTER</div>
                        <h3>Create Meal Item</h3>
                        <p>Enter the item information required for inventory management.</p>
                    </div>
                </div>

                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form action="{{ route('admin.meal.items.store') }}"
                  method="POST"
                  id="mealItemForm">
                @csrf

                <div class="form-body">

                    {{-- BASIC INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-circle-info"></i>
                            </div>

                            <div>
                                <h4>Basic Information</h4>
                                <span>Provide the main details of the meal item.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- ITEM NAME --}}
                            <div class="diary-filter-group">
                                <label for="item_name">
                                    Item Name <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-bowl-food input-icon"></i>

                                    <input type="text"
                                           name="item_name"
                                           id="item_name"
                                           value="{{ old('item_name') }}"
                                           class="diary-control with-icon @error('item_name') is-invalid @enderror"
                                           placeholder="e.g. Rice"
                                           maxlength="255"
                                           autocomplete="off"
                                           required
                                           @error('item_name') aria-invalid="true" aria-describedby="item_name_error" @enderror>
                                </div>

                                @error('item_name')
                                    <div class="field-error" id="item_name_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- CATEGORY --}}
                            <div class="diary-filter-group">
                                <label for="category">Category</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-layer-group input-icon"></i>

                                    <select name="category"
                                            id="category"
                                            class="diary-control with-icon @error('category') is-invalid @enderror"
                                            @error('category') aria-invalid="true" aria-describedby="category_error" @enderror>
                                        <option value="">Select Category</option>

                                        @foreach($categories as $category)
                                            <option value="{{ $category }}"
                                                @selected(old('category') === $category)>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('category')
                                    <div class="field-error" id="category_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- UNIT --}}
                            <div class="diary-filter-group">
                                <label for="unit">
                                    Unit <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-scale-balanced input-icon"></i>

                                    <select name="unit"
                                            id="unit"
                                            class="diary-control with-icon @error('unit') is-invalid @enderror"
                                            required
                                            @error('unit') aria-invalid="true" aria-describedby="unit_error" @enderror>
                                        <option value="">Select Unit</option>

                                        @foreach($units as $value => $label)
                                            <option value="{{ $value }}"
                                                @selected(old('unit') === $value)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('unit')
                                    <div class="field-error" id="unit_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- MINIMUM STOCK --}}
                            <div class="diary-filter-group">
                                <label for="minimum_stock">
                                    Minimum Stock <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-boxes-stacked input-icon"></i>

                                    <input type="number"
                                           name="minimum_stock"
                                           id="minimum_stock"
                                           value="{{ old('minimum_stock', 0) }}"
                                           class="diary-control with-icon @error('minimum_stock') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="e.g. 10"
                                           required
                                           @error('minimum_stock') aria-invalid="true" aria-describedby="minimum_stock_error" @enderror>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Used to identify low-stock items.
                                </div>

                                @error('minimum_stock')
                                    <div class="field-error" id="minimum_stock_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- OPENING STOCK --}}
                            <div class="diary-filter-group">
                                <label for="opening_stock">
                                    Opening Stock <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-arrow-down input-icon stock-in-icon"></i>

                                    <input type="number"
                                           name="opening_stock"
                                           id="opening_stock"
                                           value="{{ old('opening_stock', 0) }}"
                                           class="diary-control with-icon @error('opening_stock') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="e.g. 50"
                                           required
                                           @error('opening_stock') aria-invalid="true" aria-describedby="opening_stock_error" @enderror>
                                </div>

                                <div class="stock-in-help">
                                    <span class="stock-in-badge">
                                        <i class="fas fa-arrow-down"></i>
                                        Stock In
                                    </span>

                                    <span>Initial quantity added to inventory.</span>
                                </div>

                                @error('opening_stock')
                                    <div class="field-error" id="opening_stock_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- OPENING STOCK NOTE --}}
                            <div class="opening-stock-info">
                                <div class="opening-stock-info-icon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>

                                <div>
                                    <strong>Opening Stock is Stock In</strong>

                                    <p>
                                        When you create a new meal item, the opening
                                        quantity is recorded as an initial stock-in
                                        entry.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ADDITIONAL INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <div>
                                <h4>Additional Information</h4>
                                <span>Add optional notes about this meal item.</span>
                            </div>
                        </div>

                        <div class="description-card">

                            <div class="description-header">
                                <div class="description-icon">
                                    <i class="fas fa-pen"></i>
                                </div>

                                <label for="description">Description</label>

                                <span class="optional-badge">Optional</span>
                            </div>

                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      maxlength="2000"
                                      class="diary-textarea @error('description') is-invalid @enderror"
                                      placeholder="Enter storage information, quality details, preparation notes or other useful information..."
                                      @error('description') aria-invalid="true" aria-describedby="description_error" @enderror>{{ old('description') }}</textarea>

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
                                <div class="field-error" id="description_error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.meal.items.index') }}"
                       class="diary-reset-btn">
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="diary-primary-btn"
                            id="saveItemBtn">
                        <i class="fas fa-check"></i>
                        Save Item
                    </button>
                </div>

            </form>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   MEAL ITEMS — ADD ITEM FORM PAGE
   Same tokens, cards and buttons as Sports Achievements.
   Every selector is scoped under .meal-form-page.
========================================================= */

.meal-form-page {
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

.meal-form-page *,
.meal-form-page *::before,
.meal-form-page *::after {
    box-sizing: border-box;
}

.meal-form-page a,
.meal-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.meal-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.meal-form-page .form-hero,
.meal-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */

.meal-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.meal-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.meal-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.meal-form-page .form-hero-icon {
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

.meal-form-page .form-hero-text {
    min-width: 0;
}

.meal-form-page .form-breadcrumb {
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

.meal-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.meal-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.meal-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.meal-form-page .form-hero-actions {
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */

.meal-form-page .diary-primary-btn,
.meal-form-page .diary-reset-btn {
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

.meal-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.meal-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.meal-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.meal-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.meal-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ERROR ALERT */

.meal-form-page .form-error-alert {
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

.meal-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.meal-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.meal-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.meal-form-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.meal-form-page .alert-close {
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

.meal-form-page .alert-close:hover {
    color: var(--ink);
    background: #f8e3dc;
}

/* FORM CARD */

.meal-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.meal-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.meal-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.meal-form-page .panel-title-icon {
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

.meal-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.meal-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.meal-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.meal-form-page .required-info {
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

.meal-form-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* FORM BODY AND SECTIONS */

.meal-form-page .form-body {
    padding: 24px 22px 8px;
}

.meal-form-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.meal-form-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.meal-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.meal-form-page .section-heading-icon {
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

.meal-form-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.meal-form-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM GRID AND FIELDS */

.meal-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
    gap: 20px 16px;
}

.meal-form-page .diary-filter-group {
    min-width: 0;
}

.meal-form-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.meal-form-page .required {
    color: var(--red);
}

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
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
    transition: color .18s ease;
}

.meal-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.meal-form-page .stock-in-icon {
    color: var(--cyan);
}

.meal-form-page .diary-control,
.meal-form-page .diary-textarea {
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

.meal-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.meal-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.meal-form-page select.diary-control {
    cursor: pointer;
}

.meal-form-page .diary-control::placeholder,
.meal-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.meal-form-page .diary-control:hover,
.meal-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.meal-form-page .diary-control:focus,
.meal-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.meal-form-page .diary-control.is-invalid,
.meal-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.meal-form-page .diary-control.is-invalid:focus,
.meal-form-page .diary-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
}

/* HELP AND ERROR TEXT */

.meal-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.meal-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.meal-form-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

.meal-form-page .field-error i {
    margin-top: 2px;
}

/* STOCK IN BADGE AND NOTE */

.meal-form-page .stock-in-help {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.5;
}

.meal-form-page .stock-in-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border: 1px solid #cfe5d3;
    border-radius: 20px;
    color: #347849;
    background: #edf7ed;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.meal-form-page .opening-stock-info {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    height: 100%;
    min-height: 73px;
    padding: 14px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.meal-form-page .opening-stock-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    border: 1px solid #e6dfc9;
    border-radius: 8px;
    color: var(--amber);
    background: #fffefa;
    font-size: 12px;
}

.meal-form-page .opening-stock-info strong {
    display: block;
    margin-bottom: 4px;
    color: #765e32;
    font-size: 12px;
    font-weight: 800;
}

.meal-form-page .opening-stock-info p {
    margin: 0;
    color: #8a7447;
    font-size: 11px;
    line-height: 1.65;
}

/* DESCRIPTION CARD */

.meal-form-page .description-card {
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.meal-form-page .description-card:focus-within {
    border-color: #b7c4b7;
    box-shadow: 0 3px 12px rgba(58, 128, 96, .06);
}

.meal-form-page .description-header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
}

.meal-form-page .description-icon {
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

.meal-form-page .description-header label {
    margin: 0;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.meal-form-page .optional-badge {
    padding: 3px 9px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #fffefa;
    font-size: 9px;
    font-weight: 700;
}

.meal-form-page .diary-textarea {
    min-height: 125px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

.meal-form-page .description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 9px;
}

.meal-form-page .description-footer .field-help {
    margin-top: 0;
}

.meal-form-page .character-count {
    flex-shrink: 0;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

/* ACTION BAR */

.meal-form-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 8px;
    padding: 15px 22px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 900px) {
    .meal-form-page .form-hero {
        grid-template-columns: 1fr;
    }

    .meal-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .meal-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .meal-form-page .form-hero::before {
        left: 22px;
    }

    .meal-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .meal-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .meal-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .meal-form-page .form-hero-text p {
        font-size: 12px;
    }

    .meal-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .meal-form-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .meal-form-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .meal-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .meal-form-page .required-info {
        display: none;
    }

    .meal-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .meal-form-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    /* 16px prevents iOS from zooming into focused fields */
    .meal-form-page .diary-control,
    .meal-form-page .diary-textarea {
        font-size: 16px;
    }

    .meal-form-page .description-card {
        padding: 12px;
    }

    .meal-form-page .description-footer {
        align-items: flex-start;
    }

    .meal-form-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px 15px;
    }

    .meal-form-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .meal-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .meal-form-page .section-heading {
        align-items: flex-start;
    }
}

/* ACCESSIBILITY */

.meal-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.meal-form-page .diary-control:focus-visible,
.meal-form-page .diary-textarea:focus-visible {
    outline: none;
}

@media (prefers-reduced-motion: reduce) {
    .meal-form-page *,
    .meal-form-page *::before,
    .meal-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var description = document.getElementById('description');
    var counter = document.getElementById('descriptionCount');
    var form = document.getElementById('mealItemForm');
    var saveBtn = document.getElementById('saveItemBtn');

    /* Description character counter */
    if (description && counter) {
        var updateCounter = function () {
            counter.textContent = description.value.length;
        };

        description.addEventListener('input', updateCounter);
        updateCounter();
    }

    /* Prevent double submit */
    if (form && saveBtn) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) {
                return;
            }

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        });
    }
});
</script>

@endsection