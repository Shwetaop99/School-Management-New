@extends('layouts.app')

@section('title', 'Edit Equipment | Admin')

@section('content')

<style>
    /* =========================================================
       SPORTS EQUIPMENT EDIT PAGE
    ========================================================= */

    .equipment-edit-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f6f8fb;
        color: #172033;
        font-family: 'Inter', sans-serif;
        padding: 24px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .equipment-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .equipment-edit-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .equipment-edit-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ffb238, #ff9d1c);
        color: #ffffff;
        font-size: 19px;
        box-shadow: 0 6px 16px rgba(255, 178, 56, 0.20);
    }

    .equipment-edit-breadcrumb {
        margin: 0 0 3px;
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
    }

    .equipment-edit-title {
        margin: 0;
        font-size: 23px;
        line-height: 1.2;
        font-weight: 700;
        color: #172033;
    }

    .equipment-edit-subtitle {
        margin: 5px 0 0;
        font-size: 13px;
        color: #718096;
    }

    /* =========================================================
       HEADER BACK BUTTON
    ========================================================= */

    .equipment-edit-back-btn {
        height: 40px;
        padding: 0 14px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .equipment-edit-back-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #172033;
    }

    /* =========================================================
       FORM CARD
    ========================================================= */

    .equipment-edit-card {
        background: #ffffff;
        border: 1px solid #edf0f5;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .equipment-edit-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .equipment-edit-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #fff5e3;
        color: #d98b0b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .equipment-edit-card-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #172033;
    }

    .equipment-edit-card-subtitle {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }

    /* =========================================================
       FORM BODY
    ========================================================= */

    .equipment-edit-body {
        padding: 22px;
    }

    .equipment-form-section {
        margin-bottom: 26px;
    }

    .equipment-form-section:last-child {
        margin-bottom: 0;
    }

    .equipment-form-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 15px;
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .equipment-form-section-title i {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eaf3ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }

    .equipment-form-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
    }

    .equipment-form-group {
        min-width: 0;
    }

    .equipment-form-group-full {
        grid-column: 1 / -1;
    }

    .equipment-form-label {
        display: block;
        margin-bottom: 7px;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    .equipment-required {
        color: #f65343;
        margin-left: 2px;
    }

    .equipment-form-control {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #ffffff;
        color: #172033;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
    }

    .equipment-form-control::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    .equipment-form-control:hover {
        border-color: #cbd5e1;
    }

    .equipment-form-control:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.10);
    }

    select.equipment-form-control {
        cursor: pointer;
        appearance: auto;
    }

    textarea.equipment-form-control {
        height: 110px;
        padding: 11px 12px;
        resize: vertical;
        line-height: 1.6;
    }

    .equipment-form-control.is-invalid {
        border-color: #f65343;
    }

    .equipment-invalid-feedback {
        margin-top: 5px;
        color: #e65343;
        font-size: 10px;
        font-weight: 500;
    }

    /* =========================================================
       STOCK INPUT GROUP
    ========================================================= */

    .equipment-stock-input-wrapper {
        position: relative;
    }

    .equipment-stock-input-wrapper .equipment-form-control {
        padding-right: 65px;
    }

    .equipment-stock-unit-label {
        position: absolute;
        right: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
        pointer-events: none;
    }

    /* =========================================================
       FORM FOOTER
    ========================================================= */

    .equipment-edit-footer {
        padding: 16px 22px;
        border-top: 1px solid #edf0f5;
        background: #fafbfc;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
    }

    .equipment-cancel-btn,
    .equipment-update-btn {
        height: 41px;
        padding: 0 16px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .equipment-cancel-btn {
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .equipment-cancel-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #172033;
    }

    .equipment-update-btn {
        border: 1px solid #147cf5;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        box-shadow: 0 5px 14px rgba(20, 124, 245, 0.16);
    }

    .equipment-update-btn:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(20, 124, 245, 0.22);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 992px) {

        .equipment-form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
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

        .equipment-edit-back-btn {
            width: 100%;
        }

        .equipment-form-grid {
            grid-template-columns: 1fr;
        }

        .equipment-edit-body {
            padding: 17px;
        }

        .equipment-edit-footer {
            padding: 14px 17px;
        }

        .equipment-cancel-btn,
        .equipment-update-btn {
            flex: 1;
        }
    }

    @media (max-width: 576px) {

        .equipment-edit-title {
            font-size: 20px;
        }

        .equipment-edit-heading {
            align-items: flex-start;
        }

        .equipment-edit-footer {
            flex-direction: column;
        }

        .equipment-cancel-btn,
        .equipment-update-btn {
            width: 100%;
        }
    }
</style>


<div class="equipment-edit-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
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


    {{-- =====================================================
         FORM CARD
    ====================================================== --}}
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

                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-info-circle"></i>
                        Basic Information
                    </h3>


                    <div class="equipment-form-grid">

                        {{-- Equipment Name --}}
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
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Category --}}
                        <div class="equipment-form-group">

                            <label class="equipment-form-label">
                                Category
                            </label>

                            <select
                                name="category"
                                class="equipment-form-control @error('category') is-invalid @enderror"
                            >
                                <option value="">
                                    Select Category
                                </option>

                                <option value="Balls"
                                    {{ old('category', $equipment->category) === 'Balls' ? 'selected' : '' }}>
                                    Balls
                                </option>

                                <option value="Bats"
                                    {{ old('category', $equipment->category) === 'Bats' ? 'selected' : '' }}>
                                    Bats
                                </option>

                                <option value="Rackets"
                                    {{ old('category', $equipment->category) === 'Rackets' ? 'selected' : '' }}>
                                    Rackets
                                </option>

                                <option value="Nets"
                                    {{ old('category', $equipment->category) === 'Nets' ? 'selected' : '' }}>
                                    Nets
                                </option>

                                <option value="Protective Gear"
                                    {{ old('category', $equipment->category) === 'Protective Gear' ? 'selected' : '' }}>
                                    Protective Gear
                                </option>

                                <option value="Fitness Equipment"
                                    {{ old('category', $equipment->category) === 'Fitness Equipment' ? 'selected' : '' }}>
                                    Fitness Equipment
                                </option>

                                <option value="Athletics Equipment"
                                    {{ old('category', $equipment->category) === 'Athletics Equipment' ? 'selected' : '' }}>
                                    Athletics Equipment
                                </option>

                                <option value="Indoor Games"
                                    {{ old('category', $equipment->category) === 'Indoor Games' ? 'selected' : '' }}>
                                    Indoor Games
                                </option>

                                <option value="Outdoor Games"
                                    {{ old('category', $equipment->category) === 'Outdoor Games' ? 'selected' : '' }}>
                                    Outdoor Games
                                </option>

                                <option value="Sports Accessories"
                                    {{ old('category', $equipment->category) === 'Sports Accessories' ? 'selected' : '' }}>
                                    Sports Accessories
                                </option>

                                <option value="Other"
                                    {{ old('category', $equipment->category) === 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>

                            @error('category')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        
                        {{-- Unit --}}
                        <div class="equipment-form-group">

                            <label class="equipment-form-label">
                                Unit
                            </label>

                            <select
                                name="unit"
                                class="equipment-form-control @error('unit') is-invalid @enderror"
                            >
                                <option value="">
                                    Select Unit
                                </option>

                                <option value="Piece"
                                    {{ old('unit', $equipment->unit) === 'Piece' ? 'selected' : '' }}>
                                    Piece
                                </option>

                                <option value="Set"
                                    {{ old('unit', $equipment->unit) === 'Set' ? 'selected' : '' }}>
                                    Set
                                </option>

                                <option value="Pair"
                                    {{ old('unit', $equipment->unit) === 'Pair' ? 'selected' : '' }}>
                                    Pair
                                </option>

                                <option value="Box"
                                    {{ old('unit', $equipment->unit) === 'Box' ? 'selected' : '' }}>
                                    Box
                                </option>

                                <option value="Kit"
                                    {{ old('unit', $equipment->unit) === 'Kit' ? 'selected' : '' }}>
                                    Kit
                                </option>
                            </select>

                            @error('unit')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STOCK INFORMATION
                ================================================== --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-boxes"></i>
                        Stock Information
                    </h3>


                    <div class="equipment-form-grid">

                        {{-- Quantity --}}
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
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Available Quantity --}}
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
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PURCHASE INFORMATION
                ================================================== --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-shopping-cart"></i>
                        Purchase Information
                    </h3>


                    <div class="equipment-form-grid">

                        {{-- Purchase Price --}}
                        <div class="equipment-form-group">

                            <label class="equipment-form-label">
                                Purchase Price
                            </label>

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
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Purchase Date --}}
                        <div class="equipment-form-group">

                            <label class="equipment-form-label">
                                Purchase Date
                            </label>

                            <input
                                type="date"
                                name="purchase_date"
                                value="{{ old('purchase_date', $equipment->purchase_date ? \Carbon\Carbon::parse($equipment->purchase_date)->format('Y-m-d') : '') }}"
                                class="equipment-form-control @error('purchase_date') is-invalid @enderror"
                            >

                            @error('purchase_date')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Supplier --}}
                        <div class="equipment-form-group">

                            <label class="equipment-form-label">
                                Supplier
                            </label>

                            <input
                                type="text"
                                name="supplier"
                                value="{{ old('supplier', $equipment->supplier) }}"
                                class="equipment-form-control @error('supplier') is-invalid @enderror"
                                placeholder="Enter supplier name"
                            >

                            @error('supplier')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CONDITION & LOCATION
                ================================================== --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Condition & Location
                    </h3>


                    <div class="equipment-form-grid">

                        {{-- Condition --}}
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
                                <option value="">
                                    Select Condition
                                </option>

                                <option value="new"
                                    {{ old('condition', $equipment->condition) === 'new' ? 'selected' : '' }}>
                                    New
                                </option>

                                <option value="good"
                                    {{ old('condition', $equipment->condition) === 'good' ? 'selected' : '' }}>
                                    Good
                                </option>

                                <option value="fair"
                                    {{ old('condition', $equipment->condition) === 'fair' ? 'selected' : '' }}>
                                    Fair
                                </option>

                                <option value="damaged"
                                    {{ old('condition', $equipment->condition) === 'damaged' ? 'selected' : '' }}>
                                    Damaged
                                </option>
                            </select>

                            @error('condition')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Location --}}
                        <div class="equipment-form-group">

                            <label class="equipment-form-label">
                                Location
                            </label>

                            <input
                                type="text"
                                name="location"
                                value="{{ old('location', $equipment->location) }}"
                                class="equipment-form-control @error('location') is-invalid @enderror"
                                placeholder="e.g. Sports Room"
                            >

                            @error('location')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Status --}}
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
                                <option value="active"
                                    {{ old('status', $equipment->status) === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="inactive"
                                    {{ old('status', $equipment->status) === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>

                            @error('status')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DESCRIPTION
                ================================================== --}}
                <div class="equipment-form-section">

                    <h3 class="equipment-form-section-title">
                        <i class="fas fa-align-left"></i>
                        Description
                    </h3>


                    <div class="equipment-form-grid">

                        <div class="equipment-form-group equipment-form-group-full">

                            <label class="equipment-form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="equipment-form-control @error('description') is-invalid @enderror"
                                placeholder="Enter equipment description..."
                            >{{ old('description', $equipment->description) }}</textarea>

                            @error('description')
                                <div class="equipment-invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FORM FOOTER
            ================================================== --}}
            <div class="equipment-edit-footer">

                <a
                    href="{{ route('admin.sports.equipment.show', $equipment->id) }}"
                    class="equipment-cancel-btn"
                >
                    <i class="fas fa-times"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="equipment-update-btn"
                >
                    <i class="fas fa-save"></i>
                    Update Equipment
                </button>

            </div>

        </form>

    </div>

</div>

@endsection