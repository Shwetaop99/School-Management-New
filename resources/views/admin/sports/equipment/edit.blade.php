@extends('layouts.app')

@section('title', 'Edit Equipment | Admin')
@section('page-title', 'Edit Equipment')

@section('content')

<style>
/* =========================================================
   SPORTS EQUIPMENT EDIT
   SCHOOL DIARY DESIGN SYSTEM
========================================================= */

.equipment-edit-page {
    --eq-ink: #263b35;
    --eq-ink-dark: #1d302b;
    --eq-muted: #849087;
    --eq-green: #34775a;
    --eq-green-dark: #245a43;
    --eq-green-soft: #eaf3e9;
    --eq-paper: #fffefa;
    --eq-page: #f5f3e9;
    --eq-border: #e5e1d3;
    --eq-brown: #a87543;
    --eq-brown-soft: #f7eee3;
    --eq-red: #bd5148;
    --eq-red-soft: #fff0ec;
    --eq-cyan: #527f79;

    width: 100%;
    min-height: calc(100vh - 60px);
    padding: 26px;
    background: var(--eq-page);
    color: var(--eq-ink);
    font-family: 'Inter', sans-serif;
}

/* PAGE HEADER */

.equipment-edit-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.equipment-edit-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.equipment-edit-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ead9c3;
    border-radius: 14px;
    background: var(--eq-brown-soft);
    color: var(--eq-brown);
    font-size: 19px;
}

.equipment-edit-breadcrumb {
    margin: 0 0 5px;
    color: var(--eq-muted);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .04em;
}

.equipment-edit-title {
    margin: 0;
    color: var(--eq-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 28px;
    line-height: 1.2;
    font-weight: 700;
}

.equipment-edit-subtitle {
    margin: 6px 0 0;
    color: var(--eq-muted);
    font-size: 12px;
    line-height: 1.6;
}

/* BACK BUTTON */

.equipment-edit-page .equipment-edit-back-btn {
    min-height: 40px;
    padding: 0 15px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    background: var(--eq-paper);
    color: var(--eq-ink);
    border: 1px solid var(--eq-border);
    transition: all .2s ease;
    white-space: nowrap;
}

.equipment-edit-page .equipment-edit-back-btn:hover {
    background: #f0eee3;
    border-color: #c9c5b5;
    color: var(--eq-ink-dark);
}

/* FORM CARD */

.equipment-edit-card {
    background: var(--eq-paper);
    border: 1px solid var(--eq-border);
    border-radius: 13px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
    overflow: hidden;
}

.equipment-edit-card-header {
    position: relative;
    padding: 19px 24px;
    border-bottom: 1px solid var(--eq-border);
    background: #fcfbf5;
    display: flex;
    align-items: center;
    gap: 12px;
}

.equipment-edit-card-header::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 4px;
    background: var(--eq-green);
}

.equipment-edit-card-icon {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border: 1px solid #d9e5d5;
    border-radius: 9px;
    background: var(--eq-green-soft);
    color: var(--eq-green);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.equipment-edit-card-title {
    margin: 0;
    color: var(--eq-ink-dark);
    font-size: 14px;
    font-weight: 800;
}

.equipment-edit-card-subtitle {
    margin: 4px 0 0;
    color: var(--eq-muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM BODY */

.equipment-edit-body {
    padding: 25px;
}

.equipment-form-section {
    margin-bottom: 29px;
}

.equipment-form-section:last-child {
    margin-bottom: 0;
}

.equipment-form-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 17px;
    padding-bottom: 11px;
    border-bottom: 1px dashed var(--eq-border);
    color: var(--eq-ink-dark);
    font-size: 13px;
    font-weight: 800;
}

.equipment-form-section-title > i {
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border: 1px solid #d9e5d5;
    border-radius: 8px;
    background: var(--eq-green-soft);
    color: var(--eq-green);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.equipment-form-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px 15px;
}

.equipment-form-group {
    min-width: 0;
}

.equipment-form-group-full {
    grid-column: 1 / -1;
}

/* LABELS */

.equipment-edit-page .equipment-form-label {
    display: block;
    margin-bottom: 7px;
    color: #526258;
    font-size: 11px;
    font-weight: 700;
}

.equipment-required {
    color: var(--eq-red);
    margin-left: 2px;
}

/* INPUTS */

.equipment-edit-page .equipment-form-control {
    display: block;
    width: 100%;
    height: 43px;
    padding: 0 12px;
    border: 1px solid var(--eq-border);
    border-radius: 8px;
    background: #fffefa;
    color: var(--eq-ink);
    font-family: inherit;
    font-size: 12px;
    font-weight: 500;
    outline: none;
    box-shadow: none;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.equipment-edit-page .equipment-form-control::placeholder {
    color: #a0a99f;
    font-weight: 400;
}

.equipment-edit-page .equipment-form-control:hover {
    border-color: #c7d5c2;
}

.equipment-edit-page .equipment-form-control:focus {
    border-color: var(--eq-green);
    background: #fffefa;
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
}

.equipment-edit-page select.equipment-form-control {
    cursor: pointer;
    appearance: auto;
}

.equipment-edit-page textarea.equipment-form-control {
    height: 115px;
    padding: 12px;
    resize: vertical;
    line-height: 1.7;
}

.equipment-edit-page .equipment-form-control.is-invalid {
    border-color: var(--eq-red);
    background: #fffaf8;
}

.equipment-edit-page .equipment-form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
}

.equipment-invalid-feedback {
    margin-top: 6px;
    color: var(--eq-red);
    font-size: 10px;
    font-weight: 600;
    line-height: 1.5;
}

/* FORM FOOTER */

.equipment-edit-footer {
    padding: 17px 24px;
    border-top: 1px solid var(--eq-border);
    background: #f9f7ef;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
}

.equipment-edit-page .equipment-cancel-btn,
.equipment-edit-page .equipment-update-btn {
    min-height: 42px;
    padding: 0 17px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    transition: all .2s ease;
    cursor: pointer;
}

.equipment-edit-page .equipment-cancel-btn {
    background: var(--eq-paper);
    color: var(--eq-ink);
    border: 1px solid var(--eq-border);
}

.equipment-edit-page .equipment-cancel-btn:hover {
    background: #f0eee3;
    border-color: #c9c5b5;
    color: var(--eq-ink-dark);
}

.equipment-edit-page .equipment-update-btn {
    background: var(--eq-green);
    color: #fff;
    border: 1px solid var(--eq-green);
    box-shadow: 0 3px 8px rgba(52, 119, 90, .12);
}

.equipment-edit-page .equipment-update-btn:hover {
    background: var(--eq-green-dark);
    border-color: var(--eq-green-dark);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 5px 12px rgba(52, 119, 90, .18);
}

/* RESPONSIVE */

@media (max-width: 992px) {
    .equipment-edit-page {
        padding: 21px;
    }

    .equipment-form-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .equipment-edit-body {
        padding: 21px;
    }
}

@media (max-width: 768px) {
    .equipment-edit-page {
        padding: 16px;
    }

    .equipment-edit-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .equipment-edit-header > .equipment-edit-back-btn {
        width: 100%;
    }

    .equipment-form-grid {
        grid-template-columns: 1fr;
    }

    .equipment-form-group-full {
        grid-column: auto;
    }

    .equipment-edit-body {
        padding: 18px;
    }

    .equipment-edit-card-header {
        padding: 18px;
    }

    .equipment-edit-footer {
        padding: 15px 18px;
    }

    .equipment-edit-page .equipment-cancel-btn,
    .equipment-edit-page .equipment-update-btn {
        flex: 1;
    }
}

@media (max-width: 576px) {
    .equipment-edit-title {
        font-size: 23px;
    }

    .equipment-edit-heading {
        align-items: flex-start;
    }

    .equipment-edit-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
    }

    .equipment-edit-subtitle {
        font-size: 11px;
    }

    .equipment-edit-footer {
        flex-direction: column;
        align-items: stretch;
    }

    .equipment-edit-page .equipment-cancel-btn,
    .equipment-edit-page .equipment-update-btn {
        width: 100%;
        flex: auto;
    }
}
</style>

<div class="equipment-edit-page">

    {{-- PAGE HEADER --}}
    <div class="equipment-edit-header">

        <div class="equipment-edit-heading">

            <div class="equipment-edit-icon">
                <i class="fas fa-edit"></i>
            </div>

            <div>
                <div class="equipment-edit-breadcrumb">
                    Sports Management / Equipment / Edit
                </div>

                <h1 class="equipment-edit-title">
                    Edit Equipment
                </h1>

                <p class="equipment-edit-subtitle">
                    Update sports equipment details, stock and availability.
                </p>
            </div>

        </div>

        <a
            href="{{ route('admin.sports.equipment.show', $equipment->id) }}"
            class="equipment-edit-back-btn"
        >
            <i class="fas fa-arrow-left"></i>
            Back to Details
        </a>

    </div>

    {{-- FORM CARD --}}
    <div class="equipment-edit-card">

        <div class="equipment-edit-card-header">

            <div class="equipment-edit-card-icon">
                <i class="fas fa-dumbbell"></i>
            </div>

            <div>
                <h2 class="equipment-edit-card-title">
                    Update Equipment Information
                </h2>

                <p class="equipment-edit-card-subtitle">
                    Modify the information below and save your changes.
                </p>
            </div>

        </div>

        <form
            method="POST"
            action="{{ route('admin.sports.equipment.update', $equipment->id) }}"
        >
            @csrf
            @method('PUT')

            <div class="equipment-edit-body">

                {{-- BASIC INFORMATION --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-info-circle"></i>
                        Basic Information
                    </h3>

                    <div class="equipment-form-grid">

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">
                                Equipment Name
                                <span class="equipment-required">*</span>
                            </label>

                            <input
                                type="text"
                                name="equipment_name"
                                value="{{ old('equipment_name', $equipment->equipment_name) }}"
                                class="equipment-form-control @error('equipment_name') is-invalid @enderror"
                                placeholder="Enter equipment name"
                                required
                            >

                            @error('equipment_name')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">Category</label>

                            <select
                                name="category"
                                class="equipment-form-control @error('category') is-invalid @enderror"
                            >
                                <option value="">Select Category</option>
                                <option value="Balls" {{ old('category', $equipment->category) === 'Balls' ? 'selected' : '' }}>Balls</option>
                                <option value="Bats" {{ old('category', $equipment->category) === 'Bats' ? 'selected' : '' }}>Bats</option>
                                <option value="Rackets" {{ old('category', $equipment->category) === 'Rackets' ? 'selected' : '' }}>Rackets</option>
                                <option value="Nets" {{ old('category', $equipment->category) === 'Nets' ? 'selected' : '' }}>Nets</option>
                                <option value="Protective Gear" {{ old('category', $equipment->category) === 'Protective Gear' ? 'selected' : '' }}>Protective Gear</option>
                                <option value="Fitness Equipment" {{ old('category', $equipment->category) === 'Fitness Equipment' ? 'selected' : '' }}>Fitness Equipment</option>
                                <option value="Athletics Equipment" {{ old('category', $equipment->category) === 'Athletics Equipment' ? 'selected' : '' }}>Athletics Equipment</option>
                                <option value="Indoor Games" {{ old('category', $equipment->category) === 'Indoor Games' ? 'selected' : '' }}>Indoor Games</option>
                                <option value="Outdoor Games" {{ old('category', $equipment->category) === 'Outdoor Games' ? 'selected' : '' }}>Outdoor Games</option>
                                <option value="Sports Accessories" {{ old('category', $equipment->category) === 'Sports Accessories' ? 'selected' : '' }}>Sports Accessories</option>
                                <option value="Other" {{ old('category', $equipment->category) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>

                            @error('category')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">Unit</label>

                            <select
                                name="unit"
                                class="equipment-form-control @error('unit') is-invalid @enderror"
                            >
                                <option value="">Select Unit</option>
                                <option value="Piece" {{ old('unit', $equipment->unit) === 'Piece' ? 'selected' : '' }}>Piece</option>
                                <option value="Set" {{ old('unit', $equipment->unit) === 'Set' ? 'selected' : '' }}>Set</option>
                                <option value="Pair" {{ old('unit', $equipment->unit) === 'Pair' ? 'selected' : '' }}>Pair</option>
                                <option value="Box" {{ old('unit', $equipment->unit) === 'Box' ? 'selected' : '' }}>Box</option>
                                <option value="Kit" {{ old('unit', $equipment->unit) === 'Kit' ? 'selected' : '' }}>Kit</option>
                            </select>

                            @error('unit')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- STOCK INFORMATION --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-boxes"></i>
                        Stock Information
                    </h3>

                    <div class="equipment-form-grid">

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">
                                Total Quantity
                                <span class="equipment-required">*</span>
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                min="0"
                                value="{{ old('quantity', $equipment->quantity) }}"
                                class="equipment-form-control @error('quantity') is-invalid @enderror"
                                placeholder="Enter total quantity"
                                required
                            >

                            @error('quantity')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">
                                Available Quantity
                                <span class="equipment-required">*</span>
                            </label>

                            <input
                                type="number"
                                name="available_quantity"
                                min="0"
                                value="{{ old('available_quantity', $equipment->available_quantity) }}"
                                class="equipment-form-control @error('available_quantity') is-invalid @enderror"
                                placeholder="Enter available quantity"
                                required
                            >

                            @error('available_quantity')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- PURCHASE INFORMATION --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-shopping-cart"></i>
                        Purchase Information
                    </h3>

                    <div class="equipment-form-grid">

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">Purchase Price</label>

                            <input
                                type="number"
                                name="purchase_price"
                                min="0"
                                step="0.01"
                                value="{{ old('purchase_price', $equipment->purchase_price) }}"
                                class="equipment-form-control @error('purchase_price') is-invalid @enderror"
                                placeholder="Enter purchase price"
                            >

                            @error('purchase_price')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">Purchase Date</label>

                            <input
                                type="date"
                                name="purchase_date"
                                value="{{ old('purchase_date', $equipment->purchase_date ? \Carbon\Carbon::parse($equipment->purchase_date)->format('Y-m-d') : '') }}"
                                class="equipment-form-control @error('purchase_date') is-invalid @enderror"
                            >

                            @error('purchase_date')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">Supplier</label>

                            <input
                                type="text"
                                name="supplier"
                                value="{{ old('supplier', $equipment->supplier) }}"
                                class="equipment-form-control @error('supplier') is-invalid @enderror"
                                placeholder="Enter supplier name"
                            >

                            @error('supplier')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- CONDITION AND LOCATION --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Condition &amp; Location
                    </h3>

                    <div class="equipment-form-grid">

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">
                                Condition
                                <span class="equipment-required">*</span>
                            </label>

                            <select
                                name="condition"
                                class="equipment-form-control @error('condition') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Condition</option>
                                <option value="new" {{ old('condition', $equipment->condition) === 'new' ? 'selected' : '' }}>New</option>
                                <option value="good" {{ old('condition', $equipment->condition) === 'good' ? 'selected' : '' }}>Good</option>
                                <option value="fair" {{ old('condition', $equipment->condition) === 'fair' ? 'selected' : '' }}>Fair</option>
                                <option value="damaged" {{ old('condition', $equipment->condition) === 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>

                            @error('condition')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">Location</label>

                            <input
                                type="text"
                                name="location"
                                value="{{ old('location', $equipment->location) }}"
                                class="equipment-form-control @error('location') is-invalid @enderror"
                                placeholder="e.g. Sports Room"
                            >

                            @error('location')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="equipment-form-group">
                            <label class="equipment-form-label">
                                Status
                                <span class="equipment-required">*</span>
                            </label>

                            <select
                                name="status"
                                class="equipment-form-control @error('status') is-invalid @enderror"
                                required
                            >
                                <option value="active" {{ old('status', $equipment->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $equipment->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-align-left"></i>
                        Description
                    </h3>

                    <div class="equipment-form-grid">

                        <div class="equipment-form-group equipment-form-group-full">
                            <label class="equipment-form-label">Description</label>

                            <textarea
                                name="description"
                                class="equipment-form-control @error('description') is-invalid @enderror"
                                placeholder="Enter equipment description..."
                            >{{ old('description', $equipment->description) }}</textarea>

                            @error('description')
                                <div class="equipment-invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- FORM FOOTER --}}
            <div class="equipment-edit-footer">

                <a
                    href="{{ route('admin.sports.equipment.show', $equipment->id) }}"
                    class="equipment-cancel-btn"
                >
                    <i class="fas fa-times"></i>
                    Cancel
                </a>

                <button type="submit" class="equipment-update-btn">
                    <i class="fas fa-save"></i>
                    Update Equipment
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
