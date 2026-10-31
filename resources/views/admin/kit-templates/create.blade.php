```blade
@extends('layouts.app')

@section('title', 'Create Government Kit')

@section('content')
<div class="container-fluid py-4 government-kit-page">

    {{-- PAGE HEADER --}}
    <div class="gk-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="gk-hero-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="gk-eyebrow">SUPPLY MANAGEMENT</div>
                    <h3 class="gk-page-title">Create Government Kit</h3>
                    <p class="gk-page-subtitle mb-0">
                        Configure student supplies, quantities and distribution eligibility.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.kit-templates.index') }}"
               class="btn btn-outline-secondary gk-back-btn">
                <i class="bi bi-arrow-left me-2"></i>Back to Government Kits
            </a>
        </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger gk-error-alert mb-4">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-circle me-2"></i>
                Please correct the following errors.
            </div>
            <ul class="mb-0 ps-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.kit-templates.store') }}"
          id="kitTemplateForm">
        @csrf

        {{-- KIT INFORMATION --}}
        <div class="gk-card mb-4">
            <div class="gk-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="gk-section-icon">
                        <i class="bi bi-card-list"></i>
                    </div>
                    <div>
                        <h5>Kit Information</h5>
                        <p>Enter the basic details of the government supply kit.</p>
                    </div>
                    <span class="gk-section-number ms-auto">01</span>
                </div>
            </div>

            <div class="gk-card-body">
                <div class="row g-4">

                    <div class="col-lg-6">
                        <label for="kit_name" class="form-label">
                            Government Kit Name <span class="text-danger">*</span>
                        </label>
                        <input id="kit_name"
                               type="text"
                               name="kit_name"
                               class="form-control @error('kit_name') is-invalid @enderror"
                               value="{{ old('kit_name') }}"
                               maxlength="255"
                               placeholder="e.g. Government Student Supply Kit 2026-27"
                               required>
                        @error('kit_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6">
                        <label for="class_id" class="form-label">
                            Applicable Class <span class="text-danger">*</span>
                        </label>
                        <select name="class_id"
                                id="class_id"
                                class="form-select @error('class_id') is-invalid @enderror"
                                required>
                            <option value="">Select class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                    @selected(old('class_id') == $class->id)>
                                    {{ $class->class_name }}
                                    @if(!empty($class->section))
                                        - Section {{ $class->section }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <label for="academic_year" class="form-label">
                            Academic Year <span class="text-danger">*</span>
                        </label>
                        <input id="academic_year"
                               type="text"
                               name="academic_year"
                               class="form-control @error('academic_year') is-invalid @enderror"
                               value="{{ old('academic_year', date('Y').'-'.(date('Y') + 1)) }}"
                               maxlength="20"
                               placeholder="2026-2027"
                               required>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="gk-help">Example: 2026-2027</div>
                    </div>

                    <div class="col-lg-4">
                        <label for="status" class="form-label">
                            Kit Status <span class="text-danger">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>
                            <option value="active"
                                @selected(old('status', 'active') === 'active')>
                                Active
                            </option>
                            <option value="inactive"
                                @selected(old('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                        <div class="gk-help">
                            Inactive kits cannot be used for new distributions.
                        </div>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <div class="gk-info-panel h-100">
                            <i class="bi bi-info-circle"></i>
                            <div>
                                <strong>Kit eligibility</strong>
                                <p class="mb-0">
                                    Assign the correct class and academic year so the kit can be matched to eligible students.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe the scheme, purpose or eligibility conditions for this kit...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- KIT ITEMS --}}
        <div class="gk-card mb-4">
            <div class="gk-card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="gk-section-icon gk-section-icon-purple">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <h5>Government Supplied Items</h5>
                            <p>Add each item and its quantity per student.</p>
                        </div>
                        <span class="gk-section-number">02</span>
                    </div>

                    <button type="button"
                            class="btn btn-primary gk-add-btn"
                            id="addItemBtn">
                        <i class="bi bi-plus-lg me-1"></i>Add Item
                    </button>
                </div>
            </div>

            <div class="gk-card-body">

                {{-- SUMMARY --}}
                <div class="gk-items-summary mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="gk-summary-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <div>
                            <div class="gk-summary-title">Kit contents</div>
                            <div class="gk-summary-subtitle">
                                Define the supplies included in one student's kit.
                            </div>
                        </div>
                    </div>
                    <div class="gk-item-counter">
                        <span id="itemCount">0</span>
                        <small>items</small>
                    </div>
                </div>

                {{-- EXISTING / OLD ITEMS --}}
                <div id="itemsContainer">
                    @foreach(old('items', []) as $index => $oldItem)
                        <div class="gk-item-row">
                            <div class="gk-item-heading">
                                <span class="gk-item-index">
                                    <i class="bi bi-box"></i>
                                    Supply item
                                </span>
                                <button type="button"
                                        class="btn gk-remove-btn remove-item"
                                        aria-label="Remove item"
                                        title="Remove item">
                                    <i class="bi bi-trash3"></i>
                                    <span>Remove</span>
                                </button>
                            </div>

                            <div class="row g-3 align-items-end">
                                <div class="col-lg-5 col-md-6">
                                    <label class="form-label">
                                        Supply Item <span class="text-danger">*</span>
                                    </label>
                                    <select name="items[{{ $index }}][supply_item_id]"
                                            class="form-select"
                                            required>
                                        <option value="">Select supply item</option>
                                        @foreach($supplyItems as $supplyItem)
                                            <option value="{{ $supplyItem->id }}"
                                                @selected(($oldItem['supply_item_id'] ?? '') == $supplyItem->id)>
                                                {{ $supplyItem->item_name }}
                                                ({{ $supplyItem->item_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label">
                                        Quantity <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           name="items[{{ $index }}][quantity]"
                                           class="form-control"
                                           min="1"
                                           step="1"
                                           value="{{ $oldItem['quantity'] ?? 1 }}"
                                           required>
                                </div>

                                <div class="col-lg-5 col-md-9">
                                    <label class="form-label">Remarks</label>
                                    <input type="text"
                                           name="items[{{ $index }}][remarks]"
                                           class="form-control"
                                           maxlength="500"
                                           value="{{ $oldItem['remarks'] ?? '' }}"
                                           placeholder="Optional item instructions">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- EMPTY STATE --}}
                <div id="emptyItemsMessage"
                     class="gk-empty-state text-center"
                     @if(count(old('items', [])) > 0) hidden @endif>
                    <div class="gk-empty-icon">
                        <i class="bi bi-inboxes"></i>
                    </div>
                    <h6>No supply items added</h6>
                    <p>
                        Start building your kit by adding notebooks, stationery or other supplies.
                    </p>
                    <button type="button"
                            class="btn btn-outline-primary gk-empty-add-btn"
                            id="emptyAddItemBtn">
                        <i class="bi bi-plus-lg me-1"></i>Add Your First Item
                    </button>
                </div>

                <div class="gk-items-tip mt-4">
                    <i class="bi bi-lightbulb"></i>
                    <span>
                        <strong>Tip:</strong> Set the quantity required for one student. The same kit configuration will be used for each eligible student.
                    </span>
                </div>
            </div>
        </div>

        {{-- ACTION BAR --}}
        <div class="gk-action-bar">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="gk-action-note">
                    <div class="gk-action-shield">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <strong>Ready to save your kit?</strong>
                        <p class="mb-0">Review the details and included quantities first.</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 gk-action-buttons">
                    <a href="{{ route('admin.kit-templates.index') }}"
                       class="btn btn-light gk-cancel-btn">
                        Cancel
                    </a>
                    <button type="submit"
                            class="btn btn-primary gk-save-btn"
                            id="saveKitBtn">
                        <i class="bi bi-check2-circle me-2"></i>Create Government Kit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
```css
/* =========================================================
   GOVERNMENT KIT — COMPLETE DARK GREEN THEME
   ========================================================= */

.government-kit-page {
    --gk-primary: #14532d;
    --gk-primary-dark: #0b301b;
    --gk-primary-hover: #1c6639;
    --gk-primary-soft: #e8f3eb;
    --gk-text: #1b3024;
    --gk-muted: #718176;
    --gk-border: #dce7df;
    --gk-bg: #f4f8f4;

    color: var(--gk-text);
    padding-bottom: 30px;
}

.government-kit-page * {
    box-sizing: border-box;
}

/* ================= HERO SECTION ================= */

.gk-hero {
    padding: 25px;
    border: 1px solid #174b2c;
    border-radius: 17px;
    background: linear-gradient(
        135deg,
        #092c19 0%,
        #14532d 58%,
        #1c6639 100%
    );
    box-shadow: 0 8px 24px rgba(11, 48, 27, .18);
    color: #fff;
}

.gk-hero-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 57px;
    height: 57px;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 15px;
    background: rgba(255, 255, 255, .12);
    color: #fff;
    font-size: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, .12);
}

.gk-eyebrow {
    margin-bottom: 5px;
    color: #b7e4c4;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.gk-page-title {
    margin: 0;
    color: #fff;
    font-size: clamp(21px, 2.5vw, 27px);
    font-weight: 800;
    letter-spacing: -.6px;
}

.gk-page-subtitle {
    margin-top: 6px;
    color: #d0e5d5;
    font-size: 13px;
    line-height: 1.7;
}

.gk-back-btn {
    min-height: 42px;
    padding: 10px 15px;
    border: 1px solid rgba(255, 255, 255, .45);
    border-radius: 9px;
    background: rgba(255, 255, 255, .08);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
}

.gk-back-btn:hover {
    border-color: #fff;
    background: #fff;
    color: #14532d;
}

/* ================= VALIDATION ERRORS ================= */

.gk-error-alert {
    padding: 17px 20px;
    border: 1px solid #f1c8c8;
    border-radius: 12px;
}

/* ================= MAIN CARDS ================= */

.gk-card {
    overflow: hidden;
    border: 1px solid var(--gk-border);
    border-radius: 15px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(20, 83, 45, .04);
}

.gk-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e7eee8;
    background: linear-gradient(180deg, #fff, #fbfdfb);
}

.gk-card-header h5 {
    margin: 0;
    color: var(--gk-text);
    font-size: 15px;
    font-weight: 800;
}

.gk-card-header p {
    margin: 5px 0 0;
    color: var(--gk-muted);
    font-size: 12px;
    line-height: 1.6;
}

.gk-section-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #e8f3eb;
    color: #14532d;
    font-size: 18px;
}

.gk-section-icon-purple {
    background: #e3f0e6;
    color: #14532d;
}

.gk-section-number {
    color: #9aaa9e;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 1px;
}

.gk-card-body {
    padding: 25px;
}

/* ================= FORM FIELDS ================= */

.government-kit-page .form-label {
    display: block;
    margin-bottom: 8px;
    color: #354b3c;
    font-size: 12px;
    font-weight: 750;
}

.government-kit-page .form-control,
.government-kit-page .form-select {
    width: 100%;
    min-height: 45px;
    padding: 10px 12px;
    border: 1px solid #d5e1d8;
    border-radius: 9px;
    background-color: #fff;
    color: var(--gk-text);
    font-size: 13px;
    transition: border-color .2s, box-shadow .2s;
}

.government-kit-page .form-control::placeholder {
    color: #9aa99d;
}

.government-kit-page textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

.government-kit-page .form-control:focus,
.government-kit-page .form-select:focus {
    border-color: #43865b;
    box-shadow: 0 0 0 3px rgba(20, 83, 45, .12);
}

.government-kit-page .form-control.is-invalid,
.government-kit-page .form-select.is-invalid {
    border-color: #dc3545;
}

.government-kit-page .invalid-feedback {
    font-size: 12px;
}

.gk-help {
    margin-top: 6px;
    color: var(--gk-muted);
    font-size: 11px;
    line-height: 1.5;
}

/* ================= ELIGIBILITY PANEL ================= */

.gk-info-panel {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 14px;
    border: 1px solid #d2e6d7;
    border-radius: 11px;
    background: #f0f7f1;
    color: #14532d;
}

.gk-info-panel > i {
    margin-top: 2px;
    font-size: 17px;
}

.gk-info-panel strong {
    color: #174c2b;
    font-size: 12px;
}

.gk-info-panel p {
    margin-top: 5px;
    color: #617c68;
    font-size: 11px;
    line-height: 1.7;
}

/* ================= BUTTONS ================= */

.government-kit-page .btn {
    min-height: 41px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    transition: background-color .2s, border-color .2s,
        color .2s, transform .2s;
}

.government-kit-page .btn-primary {
    border-color: var(--gk-primary);
    background: var(--gk-primary);
    color: #fff;
    box-shadow: 0 4px 10px rgba(20, 83, 45, .13);
}

.government-kit-page .btn-primary:hover {
    border-color: var(--gk-primary-dark);
    background: var(--gk-primary-dark);
    color: #fff;
    transform: translateY(-1px);
}

.gk-add-btn {
    padding: 10px 16px;
}

.gk-empty-add-btn {
    border-color: #a8c9b0;
    background: #fff;
    color: #14532d;
}

.gk-empty-add-btn:hover {
    border-color: #14532d;
    background: #e8f3eb;
    color: #0b301b;
}

/* ================= KIT CONTENT SUMMARY ================= */

.gk-items-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 15px 17px;
    border: 1px solid #dce9df;
    border-radius: 12px;
    background: #f4f9f5;
}

.gk-summary-icon {
    display: grid;
    place-items: center;
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border-radius: 12px;
    background: #e0efe4;
    color: #14532d;
    font-size: 20px;
}

.gk-summary-title {
    color: var(--gk-text);
    font-size: 13px;
    font-weight: 800;
}

.gk-summary-subtitle {
    margin-top: 4px;
    color: var(--gk-muted);
    font-size: 11px;
    line-height: 1.6;
}

.gk-item-counter {
    display: flex;
    align-items: baseline;
    gap: 5px;
    padding: 7px 12px;
    border: 1px solid #cfe1d3;
    border-radius: 9px;
    background: #fff;
    color: #14532d;
}

.gk-item-counter span {
    font-size: 20px;
    font-weight: 800;
}

.gk-item-counter small {
    color: var(--gk-muted);
    font-size: 10px;
    font-weight: 700;
}

/* ================= SUPPLY ITEM ROWS ================= */

.gk-item-row {
    margin-bottom: 14px;
    padding: 17px;
    border: 1px solid #dce6de;
    border-radius: 12px;
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
}

.gk-item-row:hover {
    border-color: #a9cbb2;
    box-shadow: 0 4px 13px rgba(20, 83, 45, .06);
}

.gk-item-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 17px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8efe9;
}

.gk-item-index {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #405647;
    font-size: 12px;
    font-weight: 800;
}

.gk-item-index i {
    display: grid;
    place-items: center;
    width: 29px;
    height: 29px;
    border-radius: 8px;
    background: #e8f3eb;
    color: #14532d;
    font-size: 14px;
}

/* ================= REMOVE ITEM ================= */

.gk-remove-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 32px !important;
    padding: 6px 10px;
    border: 1px solid #efd6d6;
    background: #fff;
    color: #b43d3d;
    font-size: 11px !important;
}

.gk-remove-btn:hover {
    border-color: #d94c4c;
    background: #fff2f2;
    color: #a92d2d;
}

/* ================= EMPTY STATE ================= */

.gk-empty-state {
    padding: 40px 20px;
    border: 1px dashed #bdd3c2;
    border-radius: 13px;
    background: #f8fbf8;
}

.gk-empty-icon {
    display: grid;
    place-items: center;
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    border-radius: 17px;
    background: #e5f1e8;
    color: #14532d;
    font-size: 26px;
}

.gk-empty-state h6 {
    margin-bottom: 7px;
    color: var(--gk-text);
    font-size: 14px;
    font-weight: 800;
}

.gk-empty-state p {
    max-width: 440px;
    margin: 0 auto 17px;
    color: var(--gk-muted);
    font-size: 12px;
    line-height: 1.8;
}

/* ================= ITEM TIP ================= */

.gk-items-tip {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px 15px;
    border: 1px solid #e7e1c5;
    border-radius: 10px;
    background: #fffdf4;
    color: #756b45;
    font-size: 11px;
    line-height: 1.8;
}

.gk-items-tip i {
    margin-top: 2px;
    color: #b99a36;
    font-size: 16px;
}

/* ================= BOTTOM ACTION BAR ================= */

.gk-action-bar {
    position: sticky;
    bottom: 12px;
    z-index: 20;
    padding: 16px 20px;
    border: 1px solid #d7e4da;
    border-radius: 13px;
    background: rgba(255, 255, 255, .97);
    box-shadow: 0 8px 28px rgba(20, 83, 45, .11);
    backdrop-filter: blur(10px);
}

.gk-action-note {
    display: flex;
    align-items: center;
    gap: 12px;
}

.gk-action-shield {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 11px;
    background: #e4f3e8;
    color: #217346;
    font-size: 19px;
}

.gk-action-note strong {
    color: var(--gk-text);
    font-size: 12px;
}

.gk-action-note p {
    margin-top: 4px;
    color: var(--gk-muted);
    font-size: 11px;
    line-height: 1.6;
}

.gk-cancel-btn {
    padding: 10px 17px;
    border: 1px solid #d5e1d8;
    background: #fff;
    color: #4f6355;
}

.gk-cancel-btn:hover {
    border-color: #9ebda5;
    background: #f0f7f1;
    color: #14532d;
}

.gk-save-btn {
    padding: 10px 19px;
}

/* ================= ACCESSIBILITY ================= */

.government-kit-page button:focus-visible,
.government-kit-page a:focus-visible {
    outline: 3px solid rgba(20, 83, 45, .28);
    outline-offset: 3px;
}

/* ================= RESPONSIVE DESIGN ================= */

@media (max-width: 767.98px) {
    .government-kit-page {
        padding-top: 15px !important;
    }

    .gk-hero {
        padding: 18px;
        border-radius: 13px;
    }

    .gk-hero-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        font-size: 21px;
    }

    .gk-page-title {
        font-size: 21px;
    }

    .gk-page-subtitle {
        font-size: 12px;
    }

    .gk-back-btn {
        width: 100%;
    }

    .gk-card-header {
        padding: 16px;
    }

    .gk-card-body {
        padding: 17px;
    }

    .gk-section-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }

    .gk-card-header h5 {
        font-size: 14px;
    }

    .gk-section-number {
        display: none;
    }

    .gk-item-row {
        padding: 13px;
    }

    .gk-item-heading {
        margin-bottom: 13px;
    }

    .gk-action-bar {
        bottom: 7px;
        padding: 13px;
    }

    .gk-action-note {
        align-items: flex-start;
    }

    .gk-action-buttons {
        width: 100%;
    }

    .gk-action-buttons > * {
        flex: 1;
    }

    .gk-save-btn {
        padding: 10px 12px;
    }
}

@media (max-width: 400px) {
    .gk-items-summary {
        align-items: flex-start;
    }

    .gk-summary-subtitle {
        max-width: 180px;
    }

    .gk-item-counter {
        padding: 6px 9px;
    }

    .gk-item-heading {
        align-items: flex-start;
    }

    .gk-remove-btn span {
        display: none;
    }
}
```
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const container = document.getElementById('itemsContainer');
    const addButton = document.getElementById('addItemBtn');
    const emptyAddButton = document.getElementById('emptyAddItemBtn');
    const emptyMessage = document.getElementById('emptyItemsMessage');
    const itemCount = document.getElementById('itemCount');
    const form = document.getElementById('kitTemplateForm');
    const saveButton = document.getElementById('saveKitBtn');

    const supplyItems = {{ Illuminate\Support\Js::from(
        $supplyItems->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->item_name,
            'code' => $item->item_code,
        ])->values()->all()
    ) }};

    let itemIndex = Math.max(
        0,
        ...Array.from(container.querySelectorAll('[name]'))
            .map(element => {
                const match = element.name.match(/^items\[(\d+)\]/);
                return match ? Number(match[1]) + 1 : 0;
            })
    );

    function updateItemSummary() {
        const rows = container.querySelectorAll('.gk-item-row');

        itemCount.textContent = rows.length;
        emptyMessage.hidden = rows.length > 0;

        rows.forEach(function (row, index) {
            const label = row.querySelector('.gk-item-index');

            if (label) {
                label.innerHTML =
                    '<i class="bi bi-box"></i> Supply item ' + (index + 1);
            }
        });
    }

    function createItemRow() {
        const row = document.createElement('div');
        row.className = 'gk-item-row';

        const rowIndex = itemIndex++;

        const heading = document.createElement('div');
        heading.className = 'gk-item-heading';

        const headingLabel = document.createElement('span');
        headingLabel.className = 'gk-item-index';
        headingLabel.innerHTML = '<i class="bi bi-box"></i> Supply item';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn gk-remove-btn remove-item';
        removeButton.title = 'Remove item';
        removeButton.setAttribute('aria-label', 'Remove item');
        removeButton.innerHTML =
            '<i class="bi bi-trash3"></i><span>Remove</span>';

        heading.append(headingLabel, removeButton);

        const grid = document.createElement('div');
        grid.className = 'row g-3 align-items-end';

        function createColumn(className, labelText, control) {
            const column = document.createElement('div');
            column.className = className;

            const label = document.createElement('label');
            label.className = 'form-label';
            label.textContent = labelText;

            column.append(label, control);
            return column;
        }

        const itemSelect = document.createElement('select');
        itemSelect.className = 'form-select';
        itemSelect.name = `items[${rowIndex}][supply_item_id]`;
        itemSelect.required = true;

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select supply item';
        itemSelect.appendChild(placeholder);

        supplyItems.forEach(function (item) {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.name} (${item.code ?? ''})`;
            itemSelect.appendChild(option);
        });

        const quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.name = `items[${rowIndex}][quantity]`;
        quantityInput.className = 'form-control';
        quantityInput.min = '1';
        quantityInput.step = '1';
        quantityInput.value = '1';
        quantityInput.required = true;

        const remarksInput = document.createElement('input');
        remarksInput.type = 'text';
        remarksInput.name = `items[${rowIndex}][remarks]`;
        remarksInput.className = 'form-control';
        remarksInput.maxLength = 500;
        remarksInput.placeholder = 'Optional item instructions';

        grid.append(
            createColumn('col-lg-5 col-md-6', 'Supply Item *', itemSelect),
            createColumn('col-lg-2 col-md-3', 'Quantity *', quantityInput),
            createColumn('col-lg-5 col-md-9', 'Remarks', remarksInput)
        );

        row.append(heading, grid);
        container.appendChild(row);

        updateItemSummary();
        itemSelect.focus();
    }

    addButton.addEventListener('click', createItemRow);
    emptyAddButton.addEventListener('click', createItemRow);

    container.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.remove-item');

        if (!removeButton) return;

        const row = removeButton.closest('.gk-item-row');

        if (row) row.remove();

        updateItemSummary();
    });

    form.addEventListener('submit', function (event) {
        if (container.querySelectorAll('.gk-item-row').length === 0) {
            event.preventDefault();
            emptyMessage.hidden = false;
            emptyMessage.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return;
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        saveButton.disabled = true;
        saveButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Creating kit...';
    });

    updateItemSummary();
});
</script>
@endsection
```