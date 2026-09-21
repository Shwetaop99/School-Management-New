@extends('layouts.app')

@section('title', 'Edit Meal Item | Admin')

@section('content')

<div class="meal-form-page">

    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="meal-page-header">

            <div class="meal-heading-content">

                <div class="meal-heading-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div>

                    <div class="meal-breadcrumb">
                        <span>Meal Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Edit Item</span>
                    </div>

                    <h4 class="meal-page-title">
                        Edit Meal Item
                    </h4>

                    <p class="meal-page-subtitle">
                        Update item information and inventory settings.
                    </p>

                </div>

            </div>

            <a href="{{ route('admin.meal.items.index') }}"
               class="meal-back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Items
            </a>

        </div>


        {{-- VALIDATION ERRORS --}}
        @if($errors->any())

            <div class="meal-alert">

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
                        <i class="fas fa-pen-to-square"></i>
                    </div>

                    <div>

                        <h5>Item Information</h5>

                        <p>
                            Update the details of this meal inventory item.
                        </p>

                    </div>

                </div>

                <div class="meal-edit-badge">
                    <i class="fas fa-pen"></i>
                    Editing
                </div>

            </div>


            {{-- CARD BODY --}}
            <div class="meal-form-card-body">

                <form action="{{ route('admin.meal.items.update', $mealItem) }}"
                      method="POST">

                    @csrf
                    @method('PUT')


                    {{-- BASIC INFORMATION --}}
                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-heading-icon section-blue">
                                <i class="fas fa-circle-info"></i>
                            </div>

                            <div>

                                <h6>Basic Information</h6>

                                <span>
                                    Update the basic details of the meal item.
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
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- STATUS --}}
                            <div class="col-md-6">

                                <label for="status"
                                       class="meal-form-label">

                                    Status
                                    <span>*</span>

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
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- STOCK INFORMATION --}}
                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-heading-icon section-orange">
                                <i class="fas fa-boxes-stacked"></i>
                            </div>

                            <div>

                                <h6>Stock Information</h6>

                                <span>
                                    Configure inventory quantity and low-stock threshold.
                                </span>

                            </div>

                        </div>


                        <div class="row g-4">

                            {{-- MINIMUM STOCK --}}
                            <div class="col-md-6">

                                <label for="minimum_stock"
                                       class="meal-form-label">

                                    Minimum Stock
                                    <span>*</span>

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
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- CURRENT STOCK --}}
                            <div class="col-md-6">

                                <label for="current_stock"
                                       class="meal-form-label">

                                    Current Stock
                                    <span>*</span>

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
                                    <i class="fas fa-edit"></i>
                                    Current inventory quantity can be edited directly.
                                </div>

                                @error('current_stock')
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- ADDITIONAL DETAILS --}}
                    <div class="form-section description-section">

                        <div class="form-section-heading">

                            <div class="section-heading-icon section-cyan">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <div>

                                <h6>Additional Details</h6>

                                <span>
                                    Add optional information about this meal item.
                                </span>

                            </div>

                        </div>


                        <div class="description-card">

                            <label for="description"
                                   class="meal-form-label">

                                Description

                                <span class="optional-badge">
                                    Optional
                                </span>

                            </label>


                            <textarea name="description"
                                      id="description"
                                      rows="6"
                                      maxlength="2000"
                                      class="form-control meal-form-control meal-textarea @error('description') is-invalid @enderror"
                                      placeholder="Enter storage information, quality details, preparation notes, or any other useful information...">{{ old('description', $mealItem->description) }}</textarea>


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

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- FORM ACTIONS --}}
                    <div class="meal-form-actions">

                        <a href="{{ route('admin.meal.items.index') }}"
                           class="meal-cancel-btn">

                            <i class="fas fa-xmark"></i>
                            Cancel

                        </a>


                        <button type="submit"
                                class="meal-save-btn">

                            <i class="fas fa-check"></i>
                            Update Item

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

.meal-heading-content {
    display: flex;
    align-items: center;
    gap: 14px;
}

.meal-heading-icon {
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
    font-size: 17px;
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
    white-space: nowrap;
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

.meal-edit-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #dcecff;
    border-radius: 20px;
    background: #eef6ff;
    color: #147cf5;
    font-size: 10.5px;
    font-weight: 600;
}

.meal-edit-badge i {
    font-size: 9px;
}


/* =========================================================
   CARD BODY
   ========================================================= */

.meal-form-card-body {
    padding: 29px;
}


/* =========================================================
   FORM SECTIONS
   ========================================================= */

.form-section {
    padding-bottom: 29px;
    margin-bottom: 29px;
    border-bottom: 1px solid #edf0f5;
}

.description-section {
    padding-bottom: 0;
    margin-bottom: 0;
    border-bottom: 0;
}


/* =========================================================
   SECTION HEADING
   ========================================================= */

.form-section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 23px;
}

.section-heading-icon {
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

.section-orange {
    border: 1px solid #ffe4bd;
    background: #fff4e5;
    color: #f29b18;
}

.section-cyan {
    border: 1px solid #d5f5fa;
    background: #e7fbfe;
    color: #18b5d5;
}

.form-section-heading h6 {
    margin: 0 0 3px;
    color: #303846;
    font-size: 14px;
    font-weight: 700;
}

.form-section-heading span {
    color: #8a94a3;
    font-size: 11.5px;
}


/* =========================================================
   LABEL
   ========================================================= */

.meal-form-label {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-bottom: 8px;
    color: #374151;
    font-size: 12.5px;
    font-weight: 650;
}

.meal-form-label > span:first-child:not(.optional-badge) {
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


/* =========================================================
   FORM CONTROL
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

select.meal-form-control {
    cursor: pointer;
}


/* =========================================================
   HELP TEXT
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
   ERROR
   ========================================================= */

.field-error {
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
    line-height: 1.4;
}

.meal-form-control.is-invalid {
    border-color: #dc3545;
    background-image: none;
    box-shadow:
        0 0 0 3px rgba(220, 53, 69, .06);
}

.meal-form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow:
        0 0 0 3px rgba(220, 53, 69, .09);
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

.optional-badge {
    padding: 3px 7px;
    border-radius: 10px;
    background: #f1f4f7;
    color: #8b95a3 !important;
    font-size: 9px !important;
    font-weight: 600;
}

.meal-textarea {
    min-height: 165px;
    padding: 14px 15px !important;
    resize: vertical;
    line-height: 1.65;
}

.meal-textarea::placeholder {
    color: #adb5c0;
    font-size: 12px;
}

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
   CANCEL
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
   UPDATE
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

    .meal-heading-content {
        align-items: flex-start;
    }

    .meal-heading-icon {
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

    .meal-edit-badge {
        display: none;
    }

    .meal-form-card-body {
        padding: 17px;
    }

    .form-section {
        padding-bottom: 22px;
        margin-bottom: 22px;
    }

    .form-section-heading {
        margin-bottom: 18px;
    }

    .form-section-heading > div:last-child span {
        display: none;
    }

    .description-card {
        padding: 14px;
    }

    .meal-textarea {
        min-height: 180px;
    }

    .meal-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        gap: 9px;
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

    .meal-heading-icon {
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