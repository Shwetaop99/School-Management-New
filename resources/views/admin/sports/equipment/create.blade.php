@extends('layouts.app')

@section('title', 'Add Sports Equipment | Admin')
@section('page-title', 'Add Sports Equipment')

@section('content')

<style>
/* =========================================================
   SPORTS EQUIPMENT CREATE — SCHOOL DIARY DESIGN SYSTEM
========================================================= */

.equipment-create-page {
    --paper: #fffefa;
    --ink: #263b35;
    --muted: #849087;
    --wood: #a87543;
    --green: #34775a;
    --green-dark: #245a43;
    --green-light: #eaf3e9;
    --red: #bd5148;
    --red-light: #fff0ec;
    --cyan: #527f79;
    --border: #e5e1d3;

    min-height: calc(100vh - 70px);
    padding: 24px;
    background: #f5f3e9;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
}

.equipment-create-page *,
.equipment-create-page *::before,
.equipment-create-page *::after {
    box-sizing: border-box;
}

.equipment-create-page .equipment-create-container {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
}

/* PAGE HEADER */

.equipment-create-page .equipment-create-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
    padding: 22px 24px;
    border: 1px solid var(--border);
    border-left: 4px solid var(--green);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .04);
}

.equipment-create-page .equipment-create-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.equipment-create-page .equipment-create-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    border: 1px solid #dce8d9;
    border-radius: 12px;
    background: var(--green-light);
    color: var(--green);
    font-size: 19px;
}

.equipment-create-page .equipment-create-heading > div:last-child {
    min-width: 0;
}

.equipment-create-page .breadcrumb-text {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 6px;
    color: #879084;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
}

.equipment-create-page .breadcrumb-text i {
    color: #b1b8a9;
    font-size: 8px;
}

.equipment-create-page .equipment-create-heading h2 {
    margin: 0;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 23px;
    line-height: 1.4;
    font-weight: 700;
}

.equipment-create-page .equipment-create-heading p {
    margin: 5px 0 0;
    color: #7b857a;
    font-size: 11px;
    line-height: 1.7;
}

/* BACK BUTTON */

.equipment-create-page .back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-shrink: 0;
    padding: 9px 13px 9px 9px;
    border: 1px solid #dcd9ca;
    border-radius: 8px;
    background: var(--paper);
    color: #52665c;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
    transition: .2s ease;
}

.equipment-create-page .back-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: var(--green-light);
    color: var(--green);
}

.equipment-create-page .back-btn:hover {
    border-color: #a8c0a5;
    background: #eef5e9;
    color: var(--green-dark);
}

.equipment-create-page .back-btn:hover .back-icon {
    background: var(--green);
    color: #fff;
}

/* VALIDATION ALERT */

.equipment-create-page .form-error-alert {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 19px;
    padding: 15px 16px;
    border: 1px solid #efd0c8;
    border-radius: 11px;
    background: var(--red-light);
    color: var(--red);
    font-size: 11px;
    line-height: 1.7;
}

.equipment-create-page .alert-symbol {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border-radius: 9px;
    background: #ffe4de;
    color: var(--red);
    font-size: 14px;
}

.equipment-create-page .alert-content {
    min-width: 0;
    flex: 1;
}

.equipment-create-page .alert-content strong {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 800;
}

.equipment-create-page .alert-content ul {
    margin: 5px 0 0;
    padding-left: 17px;
}

.equipment-create-page .alert-content li {
    margin-bottom: 2px;
}

/* MAIN FORM CARD */

.equipment-create-page .equipment-form-card {
    width: 100%;
    overflow: hidden;
    margin-bottom: 19px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(54, 64, 43, .035);
    transition: border-color .2s ease, box-shadow .2s ease;
}

.equipment-create-page .equipment-form-card:hover {
    border-color: #d7d3c2;
    box-shadow: 0 5px 17px rgba(54, 64, 43, .05);
}

/* CARD HEADER */

.equipment-create-page .equipment-form-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    min-height: 66px;
    padding: 15px 18px;
    border-bottom: 1px solid #ece8dc;
    background: #faf8f0;
}

.equipment-create-page .equipment-form-card-title {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.equipment-create-page .equipment-form-card-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 1px solid #dce8d9;
    border-radius: 10px;
    background: var(--green-light);
    color: var(--green);
    font-size: 14px;
}

.equipment-create-page .equipment-form-card-header h5 {
    margin: 0;
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

.equipment-create-page .equipment-form-card-header small {
    color: #879084;
    font-size: 10px;
    line-height: 1.6;
}

/* FORM BODY */

.equipment-create-page .equipment-form-card-body {
    padding: 22px 20px 8px;
}

/* FORM SECTIONS */

.equipment-create-page .form-section {
    margin-bottom: 25px;
}

.equipment-create-page .form-section:last-child {
    margin-bottom: 15px;
}

.equipment-create-page .form-section-title {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 18px;
    padding: 0 0 12px;
    border-bottom: 1px dashed #e5e1d3;
}

.equipment-create-page .form-section-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border: 1px solid #dce8d9;
    border-radius: 9px;
    background: var(--green-light);
    color: var(--green);
    font-size: 13px;
}

.equipment-create-page .form-section-title-icon.cyan {
    border-color: #d7e6e4;
    background: #edf5f1;
    color: var(--cyan);
}

.equipment-create-page .form-section-title-icon.orange {
    border-color: #edddc5;
    background: #fbf0df;
    color: var(--wood);
}

.equipment-create-page .form-section-title-icon.red {
    border-color: #efd0c8;
    background: var(--red-light);
    color: var(--red);
}

.equipment-create-page .form-section-title span {
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

/* LABELS */

.equipment-create-page .form-label {
    display: block;
    margin-bottom: 7px;
    color: #56665a;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.equipment-create-page .required-star {
    margin-left: 2px;
    color: var(--red);
}

/* INPUTS AND SELECTS */

.equipment-create-page .form-control,
.equipment-create-page .form-select {
    display: block;
    width: 100%;
    min-width: 0;
    height: 43px;
    padding: 10px 12px;
    border: 1px solid #dedbcc;
    border-radius: 8px;
    outline: none;
    background: #fffefb;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease,
                background .18s ease;
}

.equipment-create-page .form-control::placeholder {
    color: #a5a79b;
    font-weight: 400;
}

.equipment-create-page .form-control:hover,
.equipment-create-page .form-select:hover {
    border-color: #c9c5b3;
}

.equipment-create-page .form-control:focus,
.equipment-create-page .form-select:focus {
    border-color: var(--green);
    background: #fff;
    color: var(--ink);
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
}

.equipment-create-page .form-control:disabled,
.equipment-create-page .form-select:disabled {
    cursor: not-allowed;
    opacity: .65;
    background: #f0eee6;
}

.equipment-create-page select.form-select {
    cursor: pointer;
}

.equipment-create-page textarea.form-control {
    height: auto;
    min-height: 120px;
    padding: 12px;
    line-height: 1.7;
    resize: vertical;
}

/* INPUT ICONS */

.equipment-create-page .input-icon-wrapper {
    position: relative;
    min-width: 0;
}

.equipment-create-page .input-icon-wrapper .input-icon {
    position: absolute;
    top: 50%;
    left: 14px;
    z-index: 2;
    transform: translateY(-50%);
    color: #8e9a8b;
    font-size: 11px;
    pointer-events: none;
    transition: color .18s ease;
}

.equipment-create-page .input-icon-wrapper .form-control {
    padding-left: 40px;
}

.equipment-create-page .input-icon-wrapper:focus-within .input-icon {
    color: var(--green);
}

/* VALIDATION FEEDBACK */

.equipment-create-page .invalid-feedback {
    display: block;
    margin-top: 6px;
    color: var(--red);
    font-size: 10px;
    line-height: 1.6;
}

.equipment-create-page .form-control.is-invalid,
.equipment-create-page .form-select.is-invalid {
    border-color: var(--red) !important;
    background-color: #fff8f5;
}

.equipment-create-page .form-control.is-invalid:focus,
.equipment-create-page .form-select.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .09) !important;
}

/* STOCK INFORMATION NOTE */

.equipment-create-page .stock-info-box {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-top: 2px;
    padding: 13px 15px;
    border: 1px solid #e6e2d5;
    border-left: 3px solid var(--wood);
    border-radius: 9px;
    background: #faf8f0;
}

.equipment-create-page .stock-info-box > i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    flex: 0 0 32px;
    border: 1px solid #edddc5;
    border-radius: 8px;
    background: #fbf0df;
    color: var(--wood);
    font-size: 12px;
}

.equipment-create-page .stock-info-box span {
    color: #778078;
    font-size: 10px;
    line-height: 1.7;
}

/* FORM FOOTER */

.equipment-create-page .equipment-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 15px 18px;
    border-top: 1px solid var(--border);
    background: #faf8f0;
}

.equipment-create-page .form-footer-note {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.equipment-create-page .form-footer-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border: 1px solid #dce8d9;
    border-radius: 9px;
    background: var(--green-light);
    color: var(--green);
    font-size: 12px;
}

.equipment-create-page .form-footer-note span {
    color: #879084;
    font-size: 10px;
    line-height: 1.6;
}

/* ACTION BUTTONS */

.equipment-create-page .form-footer-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
}

.equipment-create-page .btn-cancel,
.equipment-create-page .btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 15px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: .2s ease;
}

.equipment-create-page .btn-cancel {
    border: 1px solid #dcd9ca;
    background: #fffefa;
    color: #687466;
}

.equipment-create-page .btn-cancel:hover {
    border-color: #c6c4b4;
    background: #f3f1e8;
    color: var(--ink);
}

.equipment-create-page .btn-save {
    border: 1px solid #315f45;
    background: linear-gradient(135deg, #315f45, #4b8059);
    color: #fff;
    box-shadow: 0 4px 10px rgba(49, 95, 69, .12);
}

.equipment-create-page .btn-save:hover {
    border-color: #244a35;
    background: linear-gradient(135deg, #244a35, #386b49);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 5px 13px rgba(49, 95, 69, .16);
}

.equipment-create-page .btn-save:active {
    transform: translateY(0);
}

.equipment-create-page .btn-save:focus-visible,
.equipment-create-page .btn-cancel:focus-visible,
.equipment-create-page .back-btn:focus-visible {
    outline: 3px solid rgba(52, 119, 90, .22);
    outline-offset: 2px;
}

/* RESPONSIVE */

@media (max-width: 991.98px) {
    .equipment-create-page {
        padding: 18px;
    }

    .equipment-create-page .equipment-form-card-body {
        padding: 20px 18px 7px;
    }
}

@media (max-width: 767.98px) {
    .equipment-create-page {
        padding: 14px;
    }

    .equipment-create-page .equipment-create-header {
        align-items: stretch;
        flex-direction: column;
        gap: 14px;
        padding: 17px;
    }

    .equipment-create-page .equipment-create-heading {
        align-items: flex-start;
        gap: 11px;
    }

    .equipment-create-page .equipment-create-icon {
        width: 43px;
        height: 43px;
        flex-basis: 43px;
        border-radius: 10px;
        font-size: 16px;
    }

    .equipment-create-page .equipment-create-heading h2 {
        font-size: 20px;
    }

    .equipment-create-page .equipment-create-heading p {
        font-size: 10px;
    }

    .equipment-create-page .back-btn {
        width: 100%;
    }

    .equipment-create-page .equipment-form-card {
        border-radius: 11px;
    }

    .equipment-create-page .equipment-form-card-header {
        padding: 14px 15px;
    }

    .equipment-create-page .equipment-form-card-header small {
        display: none;
    }

    .equipment-create-page .equipment-form-card-body {
        padding: 16px 15px 5px;
    }

    .equipment-create-page .form-section {
        margin-bottom: 22px;
    }

    .equipment-create-page .form-section-title {
        margin-bottom: 15px;
    }

    .equipment-create-page .equipment-form-footer {
        align-items: stretch;
        flex-direction: column;
        padding: 14px;
    }

    .equipment-create-page .form-footer-note {
        align-items: center;
    }

    .equipment-create-page .form-footer-actions {
        width: 100%;
    }

    .equipment-create-page .btn-cancel,
    .equipment-create-page .btn-save {
        flex: 1;
    }
}

@media (max-width: 480px) {
    .equipment-create-page {
        padding: 10px;
    }

    .equipment-create-page .equipment-create-header {
        padding: 15px;
    }

    .equipment-create-page .equipment-create-heading h2 {
        font-size: 18px;
    }

    .equipment-create-page .breadcrumb-text {
        gap: 6px;
        font-size: 8px;
    }

    .equipment-create-page .equipment-form-card-body {
        padding: 14px 13px 4px;
    }

    .equipment-create-page .form-footer-actions {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .equipment-create-page .btn-cancel,
    .equipment-create-page .btn-save {
        width: 100%;
        flex: auto;
    }

    .equipment-create-page .stock-info-box {
        align-items: flex-start;
        padding: 11px;
    }

    .equipment-create-page .form-error-alert {
        padding: 12px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .equipment-create-page *,
    .equipment-create-page *::before,
    .equipment-create-page *::after {
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

<div class="equipment-create-page">
    <div class="equipment-create-container">

        {{-- PAGE HEADER --}}
        <div class="equipment-create-header">
            <div class="equipment-create-heading">
                <div class="equipment-create-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>

                <div>
                    <div class="breadcrumb-text">
                        <span>Sports Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Equipment</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Add Equipment</span>
                    </div>

                    <h2>Add Sports Equipment</h2>
                    <p>Add and manage sports equipment in the school inventory.</p>
                </div>
            </div>

            <a href="{{ route('admin.sports.equipment.index') }}"
               class="back-btn">
                <span class="back-icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>Back to Equipment</span>
            </a>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="form-error-alert" role="alert">
                <div class="alert-symbol">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="alert-content">
                    <strong>Please correct the following errors:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN FORM CARD --}}
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
            <form action="{{ route('admin.sports.equipment.store') }}"
                  method="POST">
                @csrf

                <div class="equipment-form-card-body">

                    {{-- BASIC INFORMATION --}}
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
                                <label for="equipment_name" class="form-label">
                                    Equipment Name
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-dumbbell input-icon"></i>
                                    <input type="text"
                                           name="equipment_name"
                                           id="equipment_name"
                                           class="form-control @error('equipment_name') is-invalid @enderror"
                                           value="{{ old('equipment_name') }}"
                                           placeholder="Enter equipment name"
                                           required>
                                </div>

                                @error('equipment_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- CATEGORY --}}
                            <div class="col-md-6">
                                <label for="category" class="form-label">
                                    Category
                                    <span class="required-star">*</span>
                                </label>

                                <select name="category"
                                        id="category"
                                        class="form-select @error('category') is-invalid @enderror"
                                        required>
                                    <option value="">Select Category</option>

                                    <option value="Balls" {{ old('category') == 'Balls' ? 'selected' : '' }}>Balls</option>
                                    <option value="Bats" {{ old('category') == 'Bats' ? 'selected' : '' }}>Bats</option>
                                    <option value="Rackets" {{ old('category') == 'Rackets' ? 'selected' : '' }}>Rackets</option>
                                    <option value="Nets" {{ old('category') == 'Nets' ? 'selected' : '' }}>Nets</option>
                                    <option value="Protective Gear" {{ old('category') == 'Protective Gear' ? 'selected' : '' }}>Protective Gear</option>
                                    <option value="Fitness Equipment" {{ old('category') == 'Fitness Equipment' ? 'selected' : '' }}>Fitness Equipment</option>
                                    <option value="Athletics Equipment" {{ old('category') == 'Athletics Equipment' ? 'selected' : '' }}>Athletics Equipment</option>
                                    <option value="Indoor Games" {{ old('category') == 'Indoor Games' ? 'selected' : '' }}>Indoor Games</option>
                                    <option value="Outdoor Games" {{ old('category') == 'Outdoor Games' ? 'selected' : '' }}>Outdoor Games</option>
                                    <option value="Sports Accessories" {{ old('category') == 'Sports Accessories' ? 'selected' : '' }}>Sports Accessories</option>
                                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>

                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- STOCK INFORMATION --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-title-icon cyan">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <span>Stock Information</span>
                        </div>

                        <div class="row g-3">

                            {{-- TOTAL QUANTITY --}}
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">
                                    Total Quantity
                                    <span class="required-star">*</span>
                                </label>

                                <input type="number"
                                       name="quantity"
                                       id="quantity"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity', 0) }}"
                                       min="0"
                                       placeholder="Total quantity"
                                       required>

                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- AVAILABLE QUANTITY --}}
                            <div class="col-md-4">
                                <label for="available_quantity" class="form-label">
                                    Available Quantity
                                    <span class="required-star">*</span>
                                </label>

                                <input type="number"
                                       name="available_quantity"
                                       id="available_quantity"
                                       class="form-control @error('available_quantity') is-invalid @enderror"
                                       value="{{ old('available_quantity', 0) }}"
                                       min="0"
                                       placeholder="Available quantity"
                                       required>

                                @error('available_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- UNIT --}}
                            <div class="col-md-4">
                                <label for="unit" class="form-label">
                                    Unit
                                    <span class="required-star">*</span>
                                </label>

                                <select name="unit"
                                        id="unit"
                                        class="form-select @error('unit') is-invalid @enderror"
                                        required>
                                    <option value="">Select Unit</option>
                                    <option value="Piece" {{ old('unit') == 'Piece' ? 'selected' : '' }}>Piece</option>
                                    <option value="Set" {{ old('unit') == 'Set' ? 'selected' : '' }}>Set</option>
                                    <option value="Pair" {{ old('unit') == 'Pair' ? 'selected' : '' }}>Pair</option>
                                    <option value="Box" {{ old('unit') == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Kit" {{ old('unit') == 'Kit' ? 'selected' : '' }}>Kit</option>
                                </select>

                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
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

                    {{-- PURCHASE INFORMATION --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-title-icon orange">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <span>Purchase Information</span>
                        </div>

                        <div class="row g-3">

                            {{-- PURCHASE PRICE --}}
                            <div class="col-md-6">
                                <label for="purchase_price" class="form-label">
                                    Purchase Price
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-indian-rupee-sign input-icon"></i>
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="purchase_price"
                                           id="purchase_price"
                                           class="form-control @error('purchase_price') is-invalid @enderror"
                                           value="{{ old('purchase_price') }}"
                                           placeholder="Enter purchase price">
                                </div>

                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- PURCHASE DATE --}}
                            <div class="col-md-6">
                                <label for="purchase_date" class="form-label">
                                    Purchase Date
                                </label>

                                <input type="date"
                                       name="purchase_date"
                                       id="purchase_date"
                                       class="form-control @error('purchase_date') is-invalid @enderror"
                                       value="{{ old('purchase_date') }}">

                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SUPPLIER --}}
                            <div class="col-md-12">
                                <label for="supplier" class="form-label">
                                    Supplier
                                </label>

                                <input type="text"
                                       name="supplier"
                                       id="supplier"
                                       class="form-control @error('supplier') is-invalid @enderror"
                                       value="{{ old('supplier') }}"
                                       placeholder="Enter supplier or vendor name">

                                @error('supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- CONDITION & LOCATION --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-title-icon red">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <span>Condition &amp; Location</span>
                        </div>

                        <div class="row g-3">

                            {{-- CONDITION --}}
                            <div class="col-md-6">
                                <label for="condition" class="form-label">
                                    Condition
                                    <span class="required-star">*</span>
                                </label>

                                <select name="condition"
                                        id="condition"
                                        class="form-select @error('condition') is-invalid @enderror"
                                        required>
                                    <option value="">Select Condition</option>
                                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                </select>

                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- LOCATION --}}
                            <div class="col-md-6">
                                <label for="location" class="form-label">
                                    Location
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text"
                                           name="location"
                                           id="location"
                                           class="form-control @error('location') is-invalid @enderror"
                                           value="{{ old('location') }}"
                                           placeholder="e.g. Sports Room, Ground, Store Room">
                                </div>

                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- EQUIPMENT STATUS --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-title-icon">
                                <i class="fas fa-toggle-on"></i>
                            </div>
                            <span>Equipment Status</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    Status
                                    <span class="required-star">*</span>
                                </label>

                                <select name="status"
                                        id="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-title-icon cyan">
                                <i class="fas fa-align-left"></i>
                            </div>
                            <span>Description</span>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="description" class="form-label">
                                    Description
                                </label>

                                <textarea name="description"
                                          id="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Enter any additional information about the equipment...">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                {{-- FORM FOOTER --}}
                <div class="equipment-form-footer">
                    <div class="form-footer-note">
                        <div class="form-footer-note-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Equipment information will be saved securely.</span>
                    </div>

                    <div class="form-footer-actions">
                        <a href="{{ route('admin.sports.equipment.index') }}"
                           class="btn-cancel">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>

                        <button type="submit" class="btn-save">
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