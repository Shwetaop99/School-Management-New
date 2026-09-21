@extends('layouts.app')

@section('title', 'Add Sports Equipment | Admin')

@section('content')

<style>
    /* =========================================================
       SPORTS EQUIPMENT - ADD PAGE
       Dashboard Matching Professional UI
    ========================================================= */

    .equipment-create-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f6f8fb;
        color: #172033;
        font-family: 'Inter', sans-serif;
    }

    .equipment-create-container {
        width: 100%;
        padding: 24px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .equipment-create-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .equipment-create-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .equipment-create-icon {
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        border-radius: 13px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 6px 16px rgba(20, 124, 245, .18);
    }

    .equipment-create-heading h2 {
        margin: 0;
        font-size: 22px;
        line-height: 1.2;
        font-weight: 700;
        color: #172033;
        letter-spacing: -.2px;
    }

    .equipment-create-heading p {
        margin: 5px 0 0;
        font-size: 13px;
        color: #718096;
    }

    .back-btn {
        height: 40px;
        padding: 0 16px;
        border-radius: 9px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        transition: all .2s ease;
        box-shadow: 0 2px 7px rgba(15, 23, 42, .03);
    }

    .back-btn:hover {
        background: #f8fbff;
        color: #147cf5;
        border-color: #cbdcf4;
        transform: translateY(-1px);
    }

    /* =========================================================
       ERROR ALERT
    ========================================================= */

    .form-error-alert {
        margin-bottom: 20px;
        padding: 14px 16px;
        border-radius: 11px;
        border: 1px solid #ffd9d4;
        background: #fff5f3;
        color: #c53030;
        font-size: 13px;
    }

    .form-error-alert strong {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-error-alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .form-error-alert li {
        margin-bottom: 3px;
    }

    /* =========================================================
       MAIN FORM CARD
    ========================================================= */

    .equipment-form-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #edf0f5;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    /* =========================================================
       CARD HEADER
    ========================================================= */

    .equipment-form-card-header {
        padding: 17px 22px;
        min-height: 58px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .equipment-form-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .equipment-form-card-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eaf3ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .equipment-form-card-header h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #172033;
    }

    .equipment-form-card-header small {
        color: #94a3b8;
        font-size: 11px;
    }

    /* =========================================================
       FORM BODY
    ========================================================= */

    .equipment-form-card-body {
        padding: 24px 22px 8px;
    }

    /* =========================================================
       FORM SECTION
    ========================================================= */

    .form-section {
        margin-bottom: 25px;
    }

    .form-section:last-child {
        margin-bottom: 15px;
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 17px;
        padding-bottom: 10px;
        border-bottom: 1px solid #edf0f5;
    }

    .form-section-title-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #f1f7ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .form-section-title span {
        font-size: 14px;
        font-weight: 700;
        color: #172033;
    }

    /* =========================================================
       LABELS
    ========================================================= */

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
    }

    .required-star {
        color: #f65343;
        margin-left: 2px;
    }

    /* =========================================================
       INPUTS
    ========================================================= */

    .form-control,
    .form-select {
        width: 100%;
        height: 42px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #ffffff;
        color: #172033;
        font-size: 13px;
        padding: 9px 12px;
        box-shadow: none;
        outline: none;
        transition: border-color .2s ease,
                    box-shadow .2s ease,
                    background .2s ease;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #cbd5e1;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #147cf5;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .10);
    }

    .form-control::placeholder {
        color: #a0aec0;
    }

    textarea.form-control {
        height: auto;
        min-height: 110px;
        line-height: 1.5;
        resize: vertical;
    }

    .invalid-feedback {
        display: block;
        margin-top: 5px;
        font-size: 11px;
        color: #dc3545;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, .08) !important;
    }

    /* =========================================================
       INPUT ICON
    ========================================================= */

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper .input-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
        z-index: 2;
    }

    .input-icon-wrapper .form-control {
        padding-left: 34px;
    }

    /* =========================================================
       STOCK INFO
    ========================================================= */

    .stock-info-box {
        padding: 13px 15px;
        margin-top: 3px;
        border-radius: 10px;
        background: #f8fbff;
        border: 1px solid #e3efff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stock-info-box i {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #eaf3ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex: 0 0 28px;
    }

    .stock-info-box span {
        font-size: 11px;
        color: #64748b;
        line-height: 1.4;
    }

    /* =========================================================
       FOOTER
    ========================================================= */

    .equipment-form-footer {
        padding: 17px 22px;
        border-top: 1px solid #edf0f5;
        background: #fafbfc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .form-footer-note {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #94a3b8;
        font-size: 11px;
    }

    .form-footer-note i {
        color: #147cf5;
        font-size: 11px;
    }

    .form-footer-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .btn-cancel {
        height: 40px;
        padding: 0 18px;
        border-radius: 9px;
        border: 1px solid #dfe5ec;
        background: #ffffff;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        transition: all .2s ease;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #172033;
        border-color: #cbd5e1;
    }

    .btn-save {
        height: 40px;
        padding: 0 20px;
        border: none;
        border-radius: 9px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
        transition: all .2s ease;
        box-shadow: 0 4px 10px rgba(20, 124, 245, .14);
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #1268ca, #105bab);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(20, 124, 245, .20);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .equipment-create-container {
            padding: 20px;
        }

        .equipment-form-card-body {
            padding: 21px 19px 5px;
        }

        .equipment-form-footer {
            padding: 16px 19px;
        }
    }

    @media (max-width: 767px) {

        .equipment-create-container {
            padding: 15px;
        }

        .equipment-create-header {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .equipment-create-heading h2 {
            font-size: 19px;
        }

        .equipment-create-heading p {
            font-size: 12px;
        }

        .back-btn {
            width: fit-content;
        }

        .equipment-form-card {
            border-radius: 13px;
        }

        .equipment-form-card-header {
            padding: 15px 16px;
        }

        .equipment-form-card-body {
            padding: 18px 16px 4px;
        }

        .form-section {
            margin-bottom: 21px;
        }

        .equipment-form-card-header small {
            display: none;
        }

        .equipment-form-footer {
            padding: 14px 16px;
            flex-direction: column;
            align-items: stretch;
        }

        .form-footer-note {
            justify-content: center;
        }

        .form-footer-actions {
            width: 100%;
        }

        .btn-cancel,
        .btn-save {
            flex: 1;
        }
    }

    @media (max-width: 480px) {

        .equipment-create-icon {
            width: 43px;
            height: 43px;
            flex-basis: 43px;
        }

        .equipment-create-heading {
            gap: 10px;
        }

        .form-footer-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
        }
    }
</style>


<div class="equipment-create-page">

    <div class="equipment-create-container">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="equipment-create-header">

            <div class="equipment-create-heading">

                <div class="equipment-create-icon">
                    <i class="fas fa-plus"></i>
                </div>

                <div>
                    <h2>Add Sports Equipment</h2>
                    <p>Add and manage sports equipment in the school inventory.</p>
                </div>

            </div>

            <a
                href="{{ route('admin.sports.equipment.index') }}"
                class="back-btn"
            >
                <i class="fas fa-arrow-left"></i>
                Back to Equipment
            </a>

        </div>


        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}

        @if ($errors->any())

            <div class="form-error-alert">

                <strong>
                    <i class="fas fa-exclamation-circle"></i>
                    Please correct the following errors:
                </strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        {{-- =====================================================
             MAIN FORM CARD
        ====================================================== --}}

        <div class="equipment-form-card">

            {{-- CARD HEADER --}}
            <div class="equipment-form-card-header">

                <div class="equipment-form-card-title">

                    <div class="equipment-form-card-title-icon">
                        <i class="fas fa-box-open"></i>
                    </div>

                    <div>
                        <h5>Equipment Information</h5>
                    </div>

                </div>

                <small>
                    Fields marked with
                    <span class="required-star">*</span>
                    are required
                </small>

            </div>


            {{-- FORM --}}
            <form
                action="{{ route('admin.sports.equipment.store') }}"
                method="POST"
            >

                @csrf


                <div class="equipment-form-card-body">


                    {{-- =================================================
                         BASIC INFORMATION
                    ================================================== --}}

                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="form-section-title-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>

                            <span>Basic Information</span>

                        </div>


                        <div class="row g-3">


                            {{-- EQUIPMENT NAME --}}
                            <div class="col-md-6">

                                <label
                                    for="equipment_name"
                                    class="form-label"
                                >
                                    Equipment Name
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-icon-wrapper">

                                    <i class="fas fa-dumbbell input-icon"></i>

                                    <input
                                        type="text"
                                        name="equipment_name"
                                        id="equipment_name"
                                        class="form-control @error('equipment_name') is-invalid @enderror"
                                        value="{{ old('equipment_name') }}"
                                        placeholder="Enter equipment name"
                                        required
                                    >

                                </div>

                                @error('equipment_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- CATEGORY --}}
                            <div class="col-md-6">

                                <label
                                    for="category"
                                    class="form-label"
                                >
                                    Category
                                    <span class="required-star">*</span>
                                </label>

                                <select
                                    name="category"
                                    id="category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    required
                                >

                                    <option value="">
                                        Select Category
                                    </option>

                                    {{-- STATIC CATEGORY VALUES --}}

                                    <option
                                        value="Balls"
                                        {{ old('category') == 'Balls' ? 'selected' : '' }}
                                    >
                                        Balls
                                    </option>

                                    <option
                                        value="Bats"
                                        {{ old('category') == 'Bats' ? 'selected' : '' }}
                                    >
                                        Bats
                                    </option>

                                    <option
                                        value="Rackets"
                                        {{ old('category') == 'Rackets' ? 'selected' : '' }}
                                    >
                                        Rackets
                                    </option>

                                    <option
                                        value="Nets"
                                        {{ old('category') == 'Nets' ? 'selected' : '' }}
                                    >
                                        Nets
                                    </option>

                                    <option
                                        value="Protective Gear"
                                        {{ old('category') == 'Protective Gear' ? 'selected' : '' }}
                                    >
                                        Protective Gear
                                    </option>

                                    <option
                                        value="Fitness Equipment"
                                        {{ old('category') == 'Fitness Equipment' ? 'selected' : '' }}
                                    >
                                        Fitness Equipment
                                    </option>

                                    <option
                                        value="Athletics Equipment"
                                        {{ old('category') == 'Athletics Equipment' ? 'selected' : '' }}
                                    >
                                        Athletics Equipment
                                    </option>

                                    <option
                                        value="Indoor Games"
                                        {{ old('category') == 'Indoor Games' ? 'selected' : '' }}
                                    >
                                        Indoor Games
                                    </option>

                                    <option
                                        value="Outdoor Games"
                                        {{ old('category') == 'Outdoor Games' ? 'selected' : '' }}
                                    >
                                        Outdoor Games
                                    </option>

                                    <option
                                        value="Sports Accessories"
                                        {{ old('category') == 'Sports Accessories' ? 'selected' : '' }}
                                    >
                                        Sports Accessories
                                    </option>

                                    <option
                                        value="Other"
                                        {{ old('category') == 'Other' ? 'selected' : '' }}
                                    >
                                        Other
                                    </option>

                                </select>

                                @error('category')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                    {{-- =================================================
                         STOCK INFORMATION
                    ================================================== --}}

                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="form-section-title-icon">
                                <i class="fas fa-boxes"></i>
                            </div>

                            <span>Stock Information</span>

                        </div>


                        <div class="row g-3">


                            {{-- TOTAL QUANTITY --}}
                            <div class="col-md-4">

                                <label
                                    for="quantity"
                                    class="form-label"
                                >
                                    Total Quantity
                                    <span class="required-star">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity', 0) }}"
                                    min="0"
                                    placeholder="Total quantity"
                                    required
                                >

                                @error('quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- AVAILABLE QUANTITY --}}
                            <div class="col-md-4">

                                <label
                                    for="available_quantity"
                                    class="form-label"
                                >
                                    Available Quantity
                                    <span class="required-star">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="available_quantity"
                                    id="available_quantity"
                                    class="form-control @error('available_quantity') is-invalid @enderror"
                                    value="{{ old('available_quantity', 0) }}"
                                    min="0"
                                    placeholder="Available quantity"
                                    required
                                >

                                @error('available_quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- UNIT --}}
                            <div class="col-md-4">

                                <label
                                    for="unit"
                                    class="form-label"
                                >
                                    Unit
                                    <span class="required-star">*</span>
                                </label>

                                <select
                                    name="unit"
                                    id="unit"
                                    class="form-select @error('unit') is-invalid @enderror"
                                    required
                                >

                                    <option value="">
                                        Select Unit
                                    </option>

                                    <option
                                        value="Piece"
                                        {{ old('unit') == 'Piece' ? 'selected' : '' }}
                                    >
                                        Piece
                                    </option>

                                    <option
                                        value="Set"
                                        {{ old('unit') == 'Set' ? 'selected' : '' }}
                                    >
                                        Set
                                    </option>

                                    <option
                                        value="Pair"
                                        {{ old('unit') == 'Pair' ? 'selected' : '' }}
                                    >
                                        Pair
                                    </option>

                                    <option
                                        value="Box"
                                        {{ old('unit') == 'Box' ? 'selected' : '' }}
                                    >
                                        Box
                                    </option>

                                    <option
                                        value="Kit"
                                        {{ old('unit') == 'Kit' ? 'selected' : '' }}
                                    >
                                        Kit
                                    </option>

                                </select>

                                @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- STOCK NOTE --}}
                            <div class="col-12">

                                <div class="stock-info-box">

                                    <i class="fas fa-lightbulb"></i>

                                    <span>
                                        Available quantity cannot be greater than total quantity.
                                        Enter the current number of usable units available in the inventory.
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         PURCHASE INFORMATION
                    ================================================== --}}

                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="form-section-title-icon">
                                <i class="fas fa-receipt"></i>
                            </div>

                            <span>Purchase Information</span>

                        </div>


                        <div class="row g-3">


                            {{-- PURCHASE PRICE --}}
                            <div class="col-md-6">

                                <label
                                    for="purchase_price"
                                    class="form-label"
                                >
                                    Purchase Price
                                </label>

                                <div class="input-icon-wrapper">

                                    <i class="fas fa-indian-rupee-sign input-icon"></i>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="purchase_price"
                                        id="purchase_price"
                                        class="form-control @error('purchase_price') is-invalid @enderror"
                                        value="{{ old('purchase_price') }}"
                                        placeholder="Enter purchase price"
                                    >

                                </div>

                                @error('purchase_price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- PURCHASE DATE --}}
                            <div class="col-md-6">

                                <label
                                    for="purchase_date"
                                    class="form-label"
                                >
                                    Purchase Date
                                </label>

                                <input
                                    type="date"
                                    name="purchase_date"
                                    id="purchase_date"
                                    class="form-control @error('purchase_date') is-invalid @enderror"
                                    value="{{ old('purchase_date') }}"
                                >

                                @error('purchase_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- SUPPLIER --}}
                            <div class="col-md-12">

                                <label
                                    for="supplier"
                                    class="form-label"
                                >
                                    Supplier
                                </label>

                                <input
                                    type="text"
                                    name="supplier"
                                    id="supplier"
                                    class="form-control @error('supplier') is-invalid @enderror"
                                    value="{{ old('supplier') }}"
                                    placeholder="Enter supplier or vendor name"
                                >

                                @error('supplier')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         CONDITION & LOCATION
                    ================================================== --}}

                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="form-section-title-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>

                            <span>Condition & Location</span>

                        </div>


                        <div class="row g-3">


                            {{-- CONDITION --}}
                            <div class="col-md-6">

                                <label
                                    for="condition"
                                    class="form-label"
                                >
                                    Condition
                                    <span class="required-star">*</span>
                                </label>

                                <select
                                    name="condition"
                                    id="condition"
                                    class="form-select @error('condition') is-invalid @enderror"
                                    required
                                >

                                    <option value="">
                                        Select Condition
                                    </option>

                                    <option
                                        value="new"
                                        {{ old('condition') == 'new' ? 'selected' : '' }}
                                    >
                                        New
                                    </option>

                                    <option
                                        value="good"
                                        {{ old('condition') == 'good' ? 'selected' : '' }}
                                    >
                                        Good
                                    </option>

                                    <option
                                        value="fair"
                                        {{ old('condition') == 'fair' ? 'selected' : '' }}
                                    >
                                        Fair
                                    </option>

                                    <option
                                        value="damaged"
                                        {{ old('condition') == 'damaged' ? 'selected' : '' }}
                                    >
                                        Damaged
                                    </option>

                                </select>

                                @error('condition')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- LOCATION --}}
                            <div class="col-md-6">

                                <label
                                    for="location"
                                    class="form-label"
                                >
                                    Location
                                </label>

                                <input
                                    type="text"
                                    name="location"
                                    id="location"
                                    class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}"
                                    placeholder="e.g. Sports Room, Ground, Store Room"
                                >

                                @error('location')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}

                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="form-section-title-icon">
                                <i class="fas fa-toggle-on"></i>
                            </div>

                            <span>Equipment Status</span>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="status"
                                    class="form-label"
                                >
                                    Status
                                    <span class="required-star">*</span>
                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required
                                >

                                    <option
                                        value="active"
                                        {{ old('status', 'active') == 'active' ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="inactive"
                                        {{ old('status') == 'inactive' ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         DESCRIPTION
                    ================================================== --}}

                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="form-section-title-icon">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <span>Description</span>

                        </div>


                        <div class="row">

                            <div class="col-12">

                                <label
                                    for="description"
                                    class="form-label"
                                >
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Enter any additional information about the equipment..."
                                >{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                </div>


                {{-- =====================================================
                     FORM FOOTER
                ====================================================== --}}

                <div class="equipment-form-footer">

                    <div class="form-footer-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Equipment information will be saved securely.</span>
                    </div>

                    <div class="form-footer-actions">

                        <a
                            href="{{ route('admin.sports.equipment.index') }}"
                            class="btn-cancel"
                        >
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn-save"
                        >
                            <i class="fas fa-save"></i>
                            Save Equipment
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection