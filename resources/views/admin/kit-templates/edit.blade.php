```blade
@extends('layouts.app')

@section('title', 'Edit Government Kit')

@section('content')
<div class="container-fluid py-4 government-kit-page">

    {{-- DARK GREEN HERO --}}
    <div class="gk-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="gk-hero-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <div class="gk-eyebrow">GOVERNMENT SUPPLY MANAGEMENT</div>
                    <h3 class="gk-page-title">Edit Government Kit</h3>
                    <p class="gk-page-subtitle mb-0">
                        Update scheme details, applicable class and supplies per student.
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 gk-header-actions">
                <a href="{{ route('admin.kit-templates.show', $kitTemplate) }}"
                   class="btn gk-light-btn">
                    <i class="bi bi-eye me-1"></i> View Kit
                </a>

                <a href="{{ route('admin.kit-templates.index') }}"
                   class="btn gk-light-btn">
                    <i class="bi bi-arrow-left me-1"></i> Back to Kits
                </a>
            </div>
        </div>

        <div class="gk-hero-meta mt-4">
            <span><i class="bi bi-hash me-1"></i> Kit ID: {{ $kitTemplate->id }}</span>
            <span>
                <i class="bi bi-circle-fill me-1"></i>
                {{ ucfirst($kitTemplate->status ?? 'inactive') }}
            </span>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show gk-alert" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show gk-alert" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- VALIDATION SUMMARY --}}
    @if($errors->any())
        <div class="alert alert-danger gk-alert mb-4">
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
          action="{{ route('admin.kit-templates.update', $kitTemplate) }}"
          id="kitTemplateForm">
        @csrf
        @method('PUT')

        {{-- KIT DETAILS --}}
        <div class="gk-card mb-4">
            <div class="gk-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="gk-section-icon">
                        <i class="bi bi-card-list"></i>
                    </div>
                    <div>
                        <h5>Government Kit Information</h5>
                        <p>Manage the scheme, class and basic kit details.</p>
                    </div>
                    <span class="gk-section-number ms-auto">01</span>
                </div>
            </div>

            <div class="gk-card-body">
                <div class="row g-4">

                    {{-- KIT NAME --}}
                    <div class="col-lg-6">
                        <label for="kit_name" class="form-label">
                            Government Kit Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="kit_name"
                               name="kit_name"
                               maxlength="255"
                               class="form-control @error('kit_name') is-invalid @enderror"
                               value="{{ old('kit_name', $kitTemplate->kit_name) }}"
                               placeholder="e.g. Class 1 Government Supply Kit"
                               required>

                        @error('kit_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SCHEME --}}
                    <div class="col-lg-3 col-md-6">
                        <label for="scheme_id" class="form-label">
                            Government Scheme <span class="text-danger">*</span>
                        </label>

                        <select id="scheme_id"
                                name="scheme_id"
                                class="form-select @error('scheme_id') is-invalid @enderror"
                                required>
                            <option value="">Select scheme</option>

                            @foreach($schemes as $scheme)
                                <option value="{{ $scheme->id }}"
                                    @selected((string) old('scheme_id', $kitTemplate->scheme_id) === (string) $scheme->id)>
                                    {{ $scheme->scheme_name }}
                                    @if($scheme->scheme_code)
                                        ({{ $scheme->scheme_code }})
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('scheme_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if($schemes->isEmpty())
                            <div class="gk-help text-danger">
                                No active schemes found. Activate a scheme before updating this kit.
                            </div>
                        @endif
                    </div>

                    {{-- CLASS: SUBMITS SCHOOL CLASS ID --}}
                    <div class="col-lg-3 col-md-6">
                        <label for="class_id" class="form-label">
                            Applicable Class <span class="text-danger">*</span>
                        </label>

                        <select id="class_id"
                                name="class_id"
                                class="form-select @error('class_id') is-invalid @enderror"
                                required>
                            <option value="">Select class</option>

                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                    @selected((string) old('class_id', $kitTemplate->class_id) === (string) $class->id)>
                                    {{ $class->class_name }}
                                    @if(!empty($class->section))
                                        — Section {{ $class->section }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if($classes->isEmpty())
                            <div class="gk-help text-danger">
                                No class records found. Add a class before updating this kit.
                            </div>
                        @endif
                    </div>

                    {{-- ACADEMIC YEAR --}}
                    <div class="col-lg-3 col-md-6">
                        <label for="academic_year" class="form-label">
                            Academic Year <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="academic_year"
                               name="academic_year"
                               maxlength="20"
                               class="form-control @error('academic_year') is-invalid @enderror"
                               value="{{ old('academic_year', $kitTemplate->academic_year) }}"
                               placeholder="2026-27"
                               required>

                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="gk-help">Example: 2026-27 or 2026-2027</div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-lg-9">
                        <label for="description" class="form-label">Description</label>

                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe the purpose of this government kit...">{{ old('description', $kitTemplate->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="col-lg-3">
                        <label for="status" class="form-label">
                            Kit Status <span class="text-danger">*</span>
                        </label>

                        <select id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>
                            <option value="active"
                                @selected(old('status', $kitTemplate->status) === 'active')>
                                Active
                            </option>
                            <option value="inactive"
                                @selected(old('status', $kitTemplate->status) === 'inactive')>
                                Inactive
                            </option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="gk-help">
                            Inactive kits cannot be used for new distributions.
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- SUPPLY ITEMS --}}
        <div class="gk-card mb-4">
            <div class="gk-card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="gk-section-icon">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <h5>Government Supplied Items</h5>
                            <p>Set the quantity of each supply provided to one student.</p>
                        </div>
                        <span class="gk-section-number">02</span>
                    </div>

                    <button type="button" class="btn gk-add-btn" id="addItemBtn">
                        <i class="bi bi-plus-lg me-1"></i> Add Item
                    </button>
                </div>
            </div>

            <div class="gk-card-body">

                <div class="gk-items-summary mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="gk-summary-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <div>
                            <div class="gk-summary-title">Kit contents</div>
                            <div class="gk-summary-subtitle">
                                Review the supplies and quantities included in this kit.
                            </div>
                        </div>
                    </div>

                    <div class="gk-item-counter">
                        <span id="itemCount">0</span>
                        <small>items</small>
                    </div>
                </div>

                <div id="itemsContainer">
                    @php
                        $oldItems = old('items');
                    @endphp

                    {{-- PRESERVE ITEMS AFTER VALIDATION FAILURE --}}
                    @if(is_array($oldItems))
                        @foreach($oldItems as $index => $oldItem)
                            <div class="gk-item-row">
                                <div class="gk-item-heading">
                                    <span class="gk-item-index">
                                        <i class="bi bi-box"></i> Supply item
                                    </span>

                                    <button type="button"
                                            class="btn gk-remove-btn remove-item"
                                            aria-label="Remove item">
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
                                                class="form-select @error('items.'.$index.'.supply_item_id') is-invalid @enderror"
                                                required>
                                            <option value="">Select supply item</option>

                                            @foreach($supplyItems as $supplyItem)
                                                <option value="{{ $supplyItem->id }}"
                                                    @selected((string) ($oldItem['supply_item_id'] ?? '') === (string) $supplyItem->id)>
                                                    {{ $supplyItem->item_name }}
                                                    @if($supplyItem->item_code)
                                                        ({{ $supplyItem->item_code }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('items.'.$index.'.supply_item_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
    <label class="form-label fw-semibold">
        Quantity per Student
    </label>

    <input type="number"
           name="items[{{ $index }}][quantity]"
           class="form-control"
           value="{{ old("items.$index.quantity", $item->quantity ?? '') }}"
           min="0.01"
           step="0.01"
           required>
</div>

                                    <div class="col-lg-5 col-md-9">
                                        <label class="form-label">Remarks</label>
                                        <input type="text"
                                               name="items[{{ $index }}][remarks]"
                                               class="form-control"
                                               maxlength="1000"
                                               value="{{ $oldItem['remarks'] ?? '' }}"
                                               placeholder="Optional item instructions">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- EXISTING SAVED ITEMS --}}
                        @foreach($kitTemplate->items as $index => $kitItem)
                            <div class="gk-item-row">
                                <div class="gk-item-heading">
                                    <span class="gk-item-index">
                                        <i class="bi bi-box"></i> Supply item
                                    </span>

                                    <button type="button"
                                            class="btn gk-remove-btn remove-item"
                                            aria-label="Remove item">
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
                                                    @selected((string) $kitItem->supply_item_id === (string) $supplyItem->id)>
                                                    {{ $supplyItem->item_name }}
                                                    @if($supplyItem->item_code)
                                                        ({{ $supplyItem->item_code }})
                                                    @endif
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
                                               value="{{ $kitItem->quantity }}"
                                               required>
                                    </div>

                                    <div class="col-lg-5 col-md-9">
                                        <label class="form-label">Remarks</label>

                                        <input type="text"
                                               name="items[{{ $index }}][remarks]"
                                               class="form-control"
                                               maxlength="1000"
                                               value="{{ $kitItem->remarks }}"
                                               placeholder="Optional item instructions">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- EMPTY STATE --}}
                <div id="emptyItemsMessage" class="gk-empty-state text-center" hidden>
                    <div class="gk-empty-icon">
                        <i class="bi bi-inboxes"></i>
                    </div>
                    <h6>No supply items added</h6>
                    <p>Add notebooks, stationery or other government-provided supplies.</p>
                    <button type="button" class="btn gk-add-btn" id="emptyAddItemBtn">
                        <i class="bi bi-plus-lg me-1"></i> Add First Item
                    </button>
                </div>

                <div class="gk-items-tip mt-4">
                    <i class="bi bi-lightbulb"></i>
                    <span>
                        <strong>Tip:</strong>
                        Quantity means the number of units supplied to one student.
                        Verify quantities before saving.
                    </span>
                </div>
            </div>
        </div>

        {{-- SAVE BAR --}}
        <div class="gk-action-bar">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="gk-action-note">
                    <div class="gk-action-shield">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <strong>Ready to update this kit?</strong>
                        <p class="mb-0">
                            Confirm the scheme, class and supply quantities before saving.
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 gk-action-buttons">
                    <a href="{{ route('admin.kit-templates.index') }}"
                       class="btn gk-cancel-btn">
                        Cancel
                    </a>

                    <button type="submit" class="btn gk-save-btn" id="updateKitBtn">
                        <i class="bi bi-check2-circle me-2"></i>
                        Update Government Kit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.government-kit-page {
    --gk-primary: #14532d;
    --gk-dark: #092c19;
    --gk-hover: #0b301b;
    --gk-soft: #e8f3eb;
    --gk-text: #1b3024;
    --gk-muted: #718176;
    --gk-border: #dce7df;
    color: var(--gk-text);
    padding-bottom: 35px;
}

.government-kit-page * {
    box-sizing: border-box;
}

/* Hero */
.gk-hero {
    padding: 25px;
    color: #fff;
    border: 1px solid #174b2c;
    border-radius: 17px;
    background: linear-gradient(135deg, #092c19, #14532d 60%, #1c6639);
    box-shadow: 0 8px 24px rgba(11, 48, 27, .18);
}

.gk-hero-icon,
.gk-section-icon,
.gk-summary-icon,
.gk-action-shield,
.gk-empty-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.gk-hero-icon {
    width: 57px;
    height: 57px;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 15px;
    background: rgba(255,255,255,.12);
    font-size: 25px;
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
}

.gk-page-subtitle {
    margin-top: 6px;
    color: #d0e5d5;
    font-size: 13px;
    line-height: 1.7;
}

.gk-header-actions .btn {
    min-height: 41px;
    padding: 10px 15px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.gk-light-btn {
    color: #fff;
    border: 1px solid rgba(255,255,255,.4);
    background: rgba(255,255,255,.08);
}

.gk-light-btn:hover {
    color: var(--gk-primary);
    background: #fff;
}

.gk-hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.gk-hero-meta span {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 20px;
    background: rgba(255,255,255,.08);
    color: #e2f0e5;
    font-size: 11px;
}

/* Cards */
.gk-card {
    overflow: hidden;
    border: 1px solid var(--gk-border);
    border-radius: 15px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(20,83,45,.04);
}

.gk-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e7eee8;
    background: linear-gradient(180deg,#fff,#fbfdfb);
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
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: var(--gk-soft);
    color: var(--gk-primary);
    font-size: 18px;
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

/* Form */
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
    box-shadow: 0 0 0 3px rgba(20,83,45,.12);
}

.gk-help {
    margin-top: 6px;
    color: var(--gk-muted);
    font-size: 11px;
    line-height: 1.6;
}

.gk-alert {
    border-radius: 12px;
}

/* Buttons */
.gk-add-btn,
.gk-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 41px;
    padding: 10px 16px;
    border: 1px solid var(--gk-primary);
    border-radius: 9px;
    background: var(--gk-primary);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(20,83,45,.12);
}

.gk-add-btn:hover,
.gk-save-btn:hover {
    border-color: var(--gk-dark);
    background: var(--gk-dark);
    color: #fff;
    transform: translateY(-1px);
}

.gk-save-btn:disabled {
    opacity: .7;
    cursor: wait;
    transform: none;
}

/* Summary */
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
    width: 43px;
    height: 43px;
    border-radius: 12px;
    background: #e0efe4;
    color: var(--gk-primary);
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
    color: var(--gk-primary);
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

/* Supply rows */
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
    box-shadow: 0 4px 13px rgba(20,83,45,.06);
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
    background: var(--gk-soft);
    color: var(--gk-primary);
    font-size: 14px;
}

.gk-remove-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 32px;
    padding: 6px 10px;
    border: 1px solid #efd6d6;
    border-radius: 8px;
    background: #fff;
    color: #b43d3d;
    font-size: 11px;
}

.gk-remove-btn:hover {
    border-color: #d94c4c;
    background: #fff2f2;
    color: #a92d2d;
}

/* Empty state */
.gk-empty-state {
    padding: 40px 20px;
    border: 1px dashed #bdd3c2;
    border-radius: 13px;
    background: #f8fbf8;
}

.gk-empty-state[hidden] {
    display: none !important;
}

.gk-empty-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    border-radius: 17px;
    background: #e5f1e8;
    color: var(--gk-primary);
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

/* Tip */
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

/* Bottom actions */
.gk-action-bar {
    position: sticky;
    bottom: 12px;
    z-index: 20;
    padding: 16px 20px;
    border: 1px solid #d7e4da;
    border-radius: 13px;
    background: rgba(255,255,255,.97);
    box-shadow: 0 8px 28px rgba(20,83,45,.11);
    backdrop-filter: blur(10px);
}

.gk-action-note {
    display: flex;
    align-items: center;
    gap: 12px;
}

.gk-action-shield {
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
    min-height: 41px;
    padding: 10px 17px;
    border: 1px solid #d5e1d8;
    border-radius: 9px;
    background: #fff;
    color: #4f6355;
    font-size: 12px;
    font-weight: 700;
}

.gk-cancel-btn:hover {
    border-color: #9ebda5;
    background: #f0f7f1;
    color: var(--gk-primary);
}

.government-kit-page button:focus-visible,
.government-kit-page a:focus-visible {
    outline: 3px solid rgba(20,83,45,.28);
    outline-offset: 3px;
}

/* Responsive */
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

    .gk-header-actions {
        width: 100%;
    }

    .gk-header-actions > * {
        flex: 1;
        text-align: center;
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
        text-align: center;
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
    const updateButton = document.getElementById('updateKitBtn');

    if (!container || !addButton || !emptyAddButton ||
        !emptyMessage || !itemCount || !form || !updateButton) {
        return;
    }

    const supplyItems = {{ Illuminate\Support\Js::from(
        $supplyItems->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->item_name,
            'code' => $item->item_code,
        ])->values()->all()
    ) }};

    // Find the next unused index, including after rows have been removed.
    let itemIndex = 0;

    container.querySelectorAll('[name]').forEach(function (element) {
        const match = element.name.match(/^items\[(\d+)\]/);

        if (match) {
            itemIndex = Math.max(itemIndex, Number(match[1]) + 1);
        }
    });

    function updateItemSummary() {
        const rows = container.querySelectorAll('.gk-item-row');

        itemCount.textContent = rows.length;
        emptyMessage.hidden = rows.length > 0;

        rows.forEach(function (row, index) {
            const label = row.querySelector('.gk-item-index');

            if (!label) return;

            label.replaceChildren();

            const icon = document.createElement('i');
            icon.className = 'bi bi-box';

            label.append(
                icon,
                document.createTextNode(' Supply item ' + (index + 1))
            );
        });
    }

    function createColumn(className, labelText, control) {
        const column = document.createElement('div');
        column.className = className;

        const label = document.createElement('label');
        label.className = 'form-label';
        label.textContent = labelText;

        column.append(label, control);
        return column;
    }

    function createItemRow() {
        const index = itemIndex++;
        const row = document.createElement('div');
        row.className = 'gk-item-row';

        const heading = document.createElement('div');
        heading.className = 'gk-item-heading';

        const headingLabel = document.createElement('span');
        headingLabel.className = 'gk-item-index';

        const headingIcon = document.createElement('i');
        headingIcon.className = 'bi bi-box';

        headingLabel.append(
            headingIcon,
            document.createTextNode(' Supply item')
        );

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn gk-remove-btn remove-item';
        removeButton.title = 'Remove item';
        removeButton.setAttribute('aria-label', 'Remove item');

        const removeIcon = document.createElement('i');
        removeIcon.className = 'bi bi-trash3';

        const removeText = document.createElement('span');
        removeText.textContent = 'Remove';

        removeButton.append(removeIcon, removeText);
        heading.append(headingLabel, removeButton);

        const grid = document.createElement('div');
        grid.className = 'row g-3 align-items-end';

        const itemSelect = document.createElement('select');
        itemSelect.name = `items[${index}][supply_item_id]`;
        itemSelect.className = 'form-select';
        itemSelect.required = true;

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select supply item';
        itemSelect.appendChild(placeholder);

        supplyItems.forEach(function (item) {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.code
                ? `${item.name} (${item.code})`
                : item.name;

            itemSelect.appendChild(option);
        });

        const quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.name = `items[${index}][quantity]`;
        quantityInput.className = 'form-control';
        quantityInput.min = '1';
        quantityInput.step = '1';
        quantityInput.value = '1';
        quantityInput.required = true;

        const remarksInput = document.createElement('input');
        remarksInput.type = 'text';
        remarksInput.name = `items[${index}][remarks]`;
        remarksInput.className = 'form-control';
        remarksInput.maxLength = 1000;
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

        if (!removeButton || !container.contains(removeButton)) return;

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

        updateButton.disabled = true;
        updateButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Updating kit...';
    });

    updateItemSummary();
});
</script>
@endsection
```