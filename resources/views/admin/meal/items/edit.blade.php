
@extends('layouts.app')

@section('title', 'Edit Meal Item | Admin')
@section('page-title', 'Edit Meal Item')

@section('content')

<div class="meal-form-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="meal-page-header">
            <div class="meal-heading-content">
                <div class="meal-heading-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div class="meal-heading-text">
                    <div class="meal-breadcrumb">
                        <span>MEAL MANAGEMENT</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>INVENTORY</span>
                        <i class="fas fa-chevron-right"></i>
                        <span class="breadcrumb-current">EDIT ITEM</span>
                    </div>

                    <h4 class="meal-page-title">Edit Meal Item</h4>

                    <p class="meal-page-subtitle">
                        Update item information and inventory settings.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.admin.meal.items.index') }}"
               class="meal-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Items</span>
            </a>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="meal-alert" role="alert">
                <div class="meal-alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="meal-alert-content">
                    <strong>Please check the following details</strong>

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
                        <i class="fas fa-pen-to-square"></i>
                    </div>

                    <div>
                        <div class="card-eyebrow">INVENTORY RECORD</div>
                        <h5>Item Information</h5>
                        <p>Update the details of this meal inventory item.</p>
                    </div>
                </div>

                <div class="meal-edit-badge">
                    <i class="fas fa-pen"></i>
                    Editing
                </div>
            </div>

            {{-- CARD BODY --}}
            <div class="meal-form-card-body">

                <form action="{{ route('admin.admin.meal.items.update', $mealItem) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    {{-- SECTION 01: BASIC INFORMATION --}}
                    <div class="form-section">

                        <div class="form-section-heading">
                            <div class="section-number">01</div>

                            <div class="section-heading-icon section-green">
                                <i class="fas fa-circle-info"></i>
                            </div>

                            <div class="section-heading-copy">
                                <h6>Basic Information</h6>
                                <span>
                                    Update the name, category, unit, and status.
                                </span>
                            </div>
                        </div>

                        <div class="row g-3">

                            {{-- ITEM NAME --}}
                            <div class="col-md-6">
                                <label for="item_name" class="meal-form-label">
                                    Item Name <span class="required-mark">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-utensils input-icon"></i>

                                    <input type="text"
                                           name="item_name"
                                           id="item_name"
                                           value="{{ old('item_name', $mealItem->item_name) }}"
                                           class="form-control meal-form-control input-with-icon @error('item_name') is-invalid @enderror"
                                           placeholder="e.g. Rice"
                                           maxlength="255"
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

                                        @foreach([
                                            'Grains',
                                            'Pulses',
                                            'Vegetables',
                                            'Fruits',
                                            'Dairy',
                                            'Oil',
                                            'Spices',
                                            'Other'
                                        ] as $category)

                                            <option value="{{ $category }}"
                                                {{ old('category', $mealItem->category) === $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div>

                                @error('category')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- UNIT --}}
                            <div class="col-md-6">
                                <label for="unit" class="meal-form-label">
                                    Unit <span class="required-mark">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-scale-balanced input-icon"></i>

                                    <select name="unit"
                                            id="unit"
                                            class="form-select meal-form-control input-with-icon @error('unit') is-invalid @enderror"
                                            required>

                                        <option value="">Select Unit</option>

                                        @foreach([
                                            'Kg',
                                            'Litre',
                                            'Packet',
                                            'Piece',
                                            'Dozen'
                                        ] as $unit)

                                            <option value="{{ $unit }}"
                                                {{ old('unit', $mealItem->unit) === $unit ? 'selected' : '' }}>
                                                {{ $unit }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div>

                                @error('unit')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- STATUS --}}
                            <div class="col-md-6">
                                <label for="status" class="meal-form-label">
                                    Status <span class="required-mark">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-toggle-on input-icon"></i>

                                    <select name="status"
                                            id="status"
                                            class="form-select meal-form-control input-with-icon @error('status') is-invalid @enderror"
                                            required>

                                        <option value="active"
                                            {{ old('status', $mealItem->status) === 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="inactive"
                                            {{ old('status', $mealItem->status) === 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                @error('status')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 02: STOCK INFORMATION --}}
                    <div class="form-section">

                        <div class="form-section-heading">
                            <div class="section-number">02</div>

                            <div class="section-heading-icon section-wood">
                                <i class="fas fa-boxes-stacked"></i>
                            </div>

                            <div class="section-heading-copy">
                                <h6>Stock Information</h6>
                                <span>
                                    Configure inventory quantity and low-stock threshold.
                                </span>
                            </div>
                        </div>

                        <div class="row g-3">

                            {{-- MINIMUM STOCK --}}
                            <div class="col-md-6">
                                <label for="minimum_stock" class="meal-form-label">
                                    Minimum Stock <span class="required-mark">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-chart-line input-icon"></i>

                                    <input type="number"
                                           name="minimum_stock"
                                           id="minimum_stock"
                                           value="{{ old('minimum_stock', $mealItem->minimum_stock) }}"
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

                            {{-- CURRENT STOCK --}}
                            <div class="col-md-6">
                                <label for="current_stock" class="meal-form-label">
                                    Current Stock <span class="required-mark">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-box input-icon"></i>

                                    <input type="number"
                                           name="current_stock"
                                           id="current_stock"
                                           value="{{ old('current_stock', $mealItem->current_stock) }}"
                                           class="form-control meal-form-control input-with-icon @error('current_stock') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="e.g. 50"
                                           required>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-pen-to-square"></i>
                                    Current inventory quantity can be edited directly.
                                </div>

                                @error('current_stock')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="stock-note">
                            <div class="stock-note-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>

                            <div>
                                <strong>Inventory tip</strong>
                                <p>
                                    Set a minimum stock level that helps staff identify
                                    items that may need replenishment.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 03: ADDITIONAL DETAILS --}}
                    <div class="form-section description-section">

                        <div class="form-section-heading">
                            <div class="section-number">03</div>

                            <div class="section-heading-icon section-cyan">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <div class="section-heading-copy">
                                <h6>Additional Details</h6>
                                <span>
                                    Add optional storage or inventory notes.
                                </span>
                            </div>
                        </div>

                        <div class="description-card">
                            <div class="description-card-top">
                                <label for="description" class="meal-form-label">
                                    Description
                                </label>

                                <span class="optional-badge">OPTIONAL</span>
                            </div>

                            <textarea name="description"
                                      id="description"
                                      rows="6"
                                      maxlength="2000"
                                      class="form-control meal-form-control meal-textarea @error('description') is-invalid @enderror"
                                      placeholder="Enter storage information, quality details, preparation notes, or other useful information...">{{ old('description', $mealItem->description) }}</textarea>

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
                        <div class="form-action-note">
                            <i class="fas fa-shield-halved"></i>
                            Review your changes before saving.
                        </div>

                        <div class="form-action-buttons">
                            <a href="{{ route('admin.admin.meal.items.index') }}"
                               class="meal-cancel-btn">
                                <i class="fas fa-xmark"></i>
                                Cancel
                            </a>

                            <button type="submit" class="meal-save-btn">
                                <i class="fas fa-check"></i>
                                Update Item
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="meal-page-footer">
            <i class="fas fa-book-open"></i>
            Meal Inventory <span>·</span> Item Management
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM — EDIT MEAL ITEM
========================================================= */

.meal-form-page {
    --meal-paper: #fffefa;
    --meal-background: #f5f3e9;
    --meal-ink: #263b35;
    --meal-green: #34775a;
    --meal-green-dark: #245a43;
    --meal-green-light: #eaf3e9;
    --meal-wood: #a87543;
    --meal-cyan: #527f79;
    --meal-red: #bd5148;
    --meal-border: #e5e1d3;
    --meal-muted: #858b7f;

    min-height: calc(100vh - 70px);
    background: var(--meal-background);
    color: var(--meal-ink);
    font-family: 'Inter', sans-serif;
    font-size: 12px;
}

.meal-form-page .container-fluid {
    max-width: 1600px;
}

/* Page header */
.meal-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 20px;
    padding: 4px 0 4px 14px;
    border-left: 4px solid var(--meal-green);
}

.meal-heading-content {
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.meal-heading-icon {
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e2d2;
    border-radius: 9px;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 16px;
}

.meal-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 4px;
    color: #8b8c7e;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .65px;
}

.meal-breadcrumb i {
    color: #b0ad9e;
    font-size: 7px;
}

.meal-breadcrumb .breadcrumb-current {
    color: var(--meal-green);
}

.meal-page-title {
    margin: 0 0 3px;
    color: var(--meal-ink);
    font-size: 22px;
    font-weight: 750;
    letter-spacing: -.45px;
}

.meal-page-subtitle {
    margin: 0;
    color: #777e72;
    font-size: 11.5px;
    line-height: 1.5;
}

.meal-back-btn {
    min-height: 36px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 8px 13px;
    border: 1px solid var(--meal-border);
    border-radius: 7px;
    background: var(--meal-paper);
    color: var(--meal-ink) !important;
    font-size: 11px;
    font-weight: 650;
    text-decoration: none;
    transition: background .18s ease, border-color .18s ease, color .18s ease;
}

.meal-back-btn:hover {
    border-color: #b7cfb6;
    background: var(--meal-green-light);
    color: var(--meal-green-dark) !important;
}

/* Validation alert */
.meal-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 16px;
    padding: 12px 14px;
    border: 1px solid #ead0c9;
    border-left: 3px solid var(--meal-red);
    border-radius: 8px;
    background: #fff8f4;
    color: #823d35;
}

.meal-alert-icon {
    width: 29px;
    height: 29px;
    flex: 0 0 29px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #f7e5de;
    color: var(--meal-red);
    font-size: 12px;
}

.meal-alert-content strong {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 750;
}

.meal-alert-content ul {
    margin: 0;
    padding-left: 16px;
    font-size: 11px;
    line-height: 1.7;
}

/* Main card */
.meal-form-card {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--meal-border);
    border-radius: 10px;
    background: var(--meal-paper);
    box-shadow: 0 4px 15px rgba(56, 60, 42, .045);
}

.meal-form-card::before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    height: 3px;
    background: var(--meal-green);
    content: "";
}

.meal-form-card-header {
    min-height: 75px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 22px;
    border-bottom: 1px solid var(--meal-border);
    background: #fffdf6;
}

.meal-form-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.meal-title-icon {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e2d2;
    border-radius: 8px;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 13px;
}

.card-eyebrow {
    margin-bottom: 3px;
    color: var(--meal-green);
    font-size: 8px;
    font-weight: 750;
    letter-spacing: .8px;
}

.meal-form-title h5 {
    margin: 0 0 3px;
    color: var(--meal-ink);
    font-size: 14px;
    font-weight: 750;
}

.meal-form-title p {
    margin: 0;
    color: #85897e;
    font-size: 10.5px;
}

.meal-edit-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid #d6e5d3;
    border-radius: 5px;
    background: var(--meal-green-light);
    color: var(--meal-green-dark);
    font-size: 9px;
    font-weight: 750;
}

.meal-edit-badge i {
    font-size: 9px;
}

.meal-form-card-body {
    padding: 24px;
}

/* Sections */
.form-section {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--meal-border);
}

.description-section {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: 0;
}

.form-section-heading {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 18px;
}

.section-number {
    width: 25px;
    height: 25px;
    flex: 0 0 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--meal-border);
    border-radius: 6px;
    background: #f5f1e5;
    color: #777765;
    font-size: 9px;
    font-weight: 800;
}

.section-heading-icon {
    width: 33px;
    height: 33px;
    flex: 0 0 33px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    font-size: 12px;
}

.section-green {
    border: 1px solid #d4e2d2;
    background: var(--meal-green-light);
    color: var(--meal-green);
}

.section-wood {
    border: 1px solid #e8d9c5;
    background: #f7eee2;
    color: var(--meal-wood);
}

.section-cyan {
    border: 1px solid #d5e5df;
    background: #eaf2ed;
    color: var(--meal-cyan);
}

.section-heading-copy h6 {
    margin: 0 0 3px;
    color: var(--meal-ink);
    font-size: 12px;
    font-weight: 750;
}

.section-heading-copy span {
    color: #85897e;
    font-size: 10px;
    line-height: 1.4;
}

/* Labels and required indicators */
.meal-form-label {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 7px;
    color: #3d4a40;
    font-size: 10.5px;
    font-weight: 700;
}

.required-mark {
    color: var(--meal-red);
}

.input-wrapper {
    position: relative;
    width: 100%;
}

.input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 14px;
    transform: translateY(-50%);
    color: #8b9588;
    font-size: 10px;
    pointer-events: none;
    transition: color .18s ease;
}

.input-wrapper:focus-within .input-icon {
    color: var(--meal-green);
}

/* Inputs */
.meal-form-control {
    width: 100%;
    min-height: 39px;
    padding: 8px 11px;
    border: 1px solid #dedecf;
    border-radius: 6px;
    background: #fffefa;
    color: var(--meal-ink);
    font-family: inherit;
    font-size: 11px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
}

.input-with-icon {
    padding-left: 35px;
}

.meal-form-control::placeholder {
    color: #a5a697;
    opacity: 1;
}

.meal-form-control:hover {
    border-color: #c6cdbd;
}

.meal-form-control:focus {
    border-color: var(--meal-green);
    background: #fffefa;
    color: var(--meal-ink);
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .09);
}

select.meal-form-control {
    cursor: pointer;
}

.meal-form-control.is-invalid {
    border-color: var(--meal-red);
    background-image: none;
    box-shadow: 0 0 0 2px rgba(189, 81, 72, .07);
}

.meal-form-control.is-invalid:focus {
    border-color: var(--meal-red);
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .09);
}

/* Helper and validation text */
.field-help {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    margin-top: 6px;
    color: #838879;
    font-size: 9.5px;
    line-height: 1.5;
}

.field-help i {
    margin-top: 2px;
    color: var(--meal-wood);
    font-size: 9px;
}

.field-error {
    margin-top: 5px;
    color: var(--meal-red);
    font-size: 10px;
    line-height: 1.4;
}

/* Stock information note */
.stock-note {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 17px;
    padding: 11px 12px;
    border: 1px solid #e6dfcd;
    border-radius: 7px;
    background: #f9f5e9;
}

.stock-note-icon {
    width: 27px;
    height: 27px;
    flex: 0 0 27px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: #efe4ce;
    color: var(--meal-wood);
    font-size: 11px;
}

.stock-note strong {
    display: block;
    margin-bottom: 3px;
    color: #665238;
    font-size: 10px;
    font-weight: 750;
}

.stock-note p {
    margin: 0;
    color: #81745e;
    font-size: 10px;
    line-height: 1.55;
}

/* Description */
.description-card {
    padding: 15px;
    border: 1px solid var(--meal-border);
    border-radius: 8px;
    background: #fffdf7;
}

.description-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.optional-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 7px;
    border: 1px solid #e4dfd1;
    border-radius: 4px;
    background: #f5f1e5;
    color: #858170;
    font-size: 8px;
    font-weight: 750;
    letter-spacing: .4px;
}

.meal-textarea {
    min-height: 135px;
    padding: 11px 12px;
    resize: vertical;
    line-height: 1.65;
}

.description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 8px;
}

.description-footer .field-help {
    margin-top: 0;
}

.character-count {
    flex-shrink: 0;
    color: #85897e;
    font-size: 9px;
    font-variant-numeric: tabular-nums;
}

/* Action bar */
.meal-form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-top: 24px;
    padding: 16px;
    border: 1px solid var(--meal-border);
    border-radius: 8px;
    background: #f8f5eb;
}

.form-action-note {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #7c806f;
    font-size: 10px;
    line-height: 1.5;
}

.form-action-note i {
    color: var(--meal-green);
    font-size: 11px;
}

.form-action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
}

.meal-cancel-btn,
.meal-save-btn {
    min-height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 6px;
    font-family: inherit;
    font-size: 10.5px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease, color .18s ease;
}

.meal-cancel-btn {
    border: 1px solid #dedecf;
    background: var(--meal-paper);
    color: #5f685d !important;
}

.meal-cancel-btn:hover {
    border-color: #c7c8b8;
    background: #f0eddf;
    color: var(--meal-ink) !important;
}

.meal-save-btn {
    border: 1px solid var(--meal-green);
    background: var(--meal-green);
    color: #fff !important;
    box-shadow: 0 3px 8px rgba(52, 119, 90, .12);
}

.meal-save-btn:hover {
    border-color: var(--meal-green-dark);
    background: var(--meal-green-dark);
    color: #fff !important;
}

.meal-cancel-btn:focus-visible,
.meal-save-btn:focus-visible,
.meal-back-btn:focus-visible {
    outline: 3px solid rgba(52, 119, 90, .22);
    outline-offset: 2px;
}

.meal-page-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 15px 0 3px;
    color: #929181;
    font-size: 9px;
}

.meal-page-footer i {
    color: var(--meal-green);
}

.meal-page-footer span {
    color: #b5b09f;
}

/* Tablet */
@media (max-width: 991.98px) {
    .meal-form-card-body {
        padding: 20px;
    }

    .meal-page-header {
        gap: 12px;
    }

    .form-action-note {
        max-width: 230px;
    }
}

/* Mobile */
@media (max-width: 767.98px) {
    .meal-page-header {
        align-items: flex-start;
    }

    .meal-page-title {
        font-size: 20px;
    }

    .meal-form-card-header {
        padding: 14px 16px;
    }

    .meal-form-card-body {
        padding: 17px;
    }

    .form-section {
        margin-bottom: 20px;
        padding-bottom: 20px;
    }

    .meal-form-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .form-action-note {
        max-width: none;
    }

    .form-action-buttons {
        justify-content: flex-end;
    }
}

/* Small mobile */
@media (max-width: 480px) {
    .meal-form-page .container-fluid {
        padding-right: 10px !important;
        padding-left: 10px !important;
    }

    .meal-page-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        padding-left: 10px;
    }

    .meal-heading-content {
        align-items: flex-start;
        gap: 9px;
    }

    .meal-heading-icon {
        width: 37px;
        height: 37px;
        flex-basis: 37px;
        font-size: 14px;
    }

    .meal-breadcrumb {
        gap: 5px;
        font-size: 8px;
    }

    .meal-page-title {
        font-size: 19px;
    }

    .meal-page-subtitle {
        font-size: 10.5px;
    }

    .meal-back-btn {
        width: 100%;
    }

    .meal-form-card {
        border-radius: 8px;
    }

    .meal-form-card-header {
        padding: 13px;
    }

    .meal-title-icon {
        width: 33px;
        height: 33px;
        flex-basis: 33px;
    }

    .meal-form-title p {
        font-size: 9.5px;
    }

    .meal-edit-badge {
        padding: 5px 6px;
        font-size: 8px;
    }

    .meal-form-card-body {
        padding: 14px;
    }

    .form-section-heading {
        gap: 7px;
        margin-bottom: 15px;
    }

    .section-number {
        width: 22px;
        height: 22px;
        flex-basis: 22px;
        font-size: 8px;
    }

    .section-heading-icon {
        width: 29px;
        height: 29px;
        flex-basis: 29px;
        font-size: 11px;
    }

    .section-heading-copy span {
        font-size: 9px;
    }

    .description-card {
        padding: 11px;
    }

    .description-footer {
        align-items: flex-start;
        flex-direction: column;
        gap: 5px;
    }

    .meal-form-actions {
        padding: 12px;
    }

    .form-action-buttons {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .meal-cancel-btn,
    .meal-save-btn {
        width: 100%;
    }

    .meal-page-footer {
        flex-wrap: wrap;
        text-align: center;
    }
}

/* Reduced motion */
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
