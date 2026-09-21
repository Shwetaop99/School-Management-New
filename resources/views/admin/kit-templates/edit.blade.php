```blade
@extends('layouts.app')

@section('title', 'Edit Government Kit')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Edit Government Student Supply Kit
            </h3>

            <p class="text-muted mb-0">
                Update the government-provided kit and its standard distribution items.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.kit-templates.show', $kitTemplate) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-eye me-1"></i>
                View

            </a>

            <a href="{{ route('admin.kit-templates.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        SUCCESS / ERROR MESSAGES
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.kit-templates.update', $kitTemplate) }}"
          id="kitTemplateForm">

        @csrf
        @method('PUT')


        {{-- =====================================================
            GOVERNMENT KIT INFORMATION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Government Kit Information
                </h5>

                <small class="text-muted">
                    Update the details of this government-provided student kit.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Kit Name --}}
                    <div class="col-lg-6">

                        <label class="form-label fw-semibold">
                            Government Kit Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="kit_name"
                               class="form-control @error('kit_name') is-invalid @enderror"
                               value="{{ old('kit_name', $kitTemplate->kit_name) }}"
                               placeholder="Example: Government Student Supply Kit 2026-27"
                               required>

                        @error('kit_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Class --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Applicable Class
                            <span class="text-danger">*</span>
                        </label>

                        <select name="class"
                                class="form-select @error('class') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class }}"
                                    {{ old('class', $kitTemplate->class) == $class ? 'selected' : '' }}>

                                    Class {{ $class }}

                                </option>

                            @endforeach

                        </select>

                        @error('class')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <input type="text"
                               name="academic_year"
                               class="form-control @error('academic_year') is-invalid @enderror"
                               value="{{ old('academic_year', $kitTemplate->academic_year) }}"
                               placeholder="2026-27">

                        @error('academic_year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe the government scheme or purpose of this kit...">{{ old('description', $kitTemplate->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="active"
                                {{ old('status', $kitTemplate->status) === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status', $kitTemplate->status) === 'inactive' ? 'selected' : '' }}>
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

        </div>


        {{-- =====================================================
            GOVERNMENT SUPPLIED ITEMS
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div>
                        <h5 class="fw-bold mb-1">

                            <i class="bi bi-boxes text-primary me-2"></i>

                            Government Supplied Items

                        </h5>

                        <small class="text-muted">
                            Define the quantity of each item provided to one student.
                        </small>
                    </div>

                    <button type="button"
                            class="btn btn-sm btn-primary"
                            id="addItemBtn">

                        <i class="bi bi-plus-circle me-1"></i>
                        Add Item

                    </button>

                </div>

            </div>


            <div class="card-body">

                <div id="itemsContainer">

                    @php
                        $oldItems = old('items');
                    @endphp


                    {{-- =================================================
                        OLD INPUT AFTER VALIDATION ERROR
                    ================================================== --}}
                    @if(is_array($oldItems))

                        @foreach($oldItems as $index => $oldItem)

                            <div class="kit-item-row border rounded-3 p-3 mb-3">

                                <div class="row g-3 align-items-end">

                                    <div class="col-lg-5">

                                        <label class="form-label fw-semibold">
                                            Supply Item
                                        </label>

                                        <select name="items[{{ $index }}][supply_item_id]"
                                                class="form-select"
                                                required>

                                            <option value="">
                                                Select Supply Item
                                            </option>

                                            @foreach($supplyItems as $supplyItem)

                                                <option value="{{ $supplyItem->id }}"
                                                    {{ ($oldItem['supply_item_id'] ?? '') == $supplyItem->id ? 'selected' : '' }}>

                                                    {{ $supplyItem->item_name }}
                                                    ({{ $supplyItem->item_code }})

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    <div class="col-lg-2">

                                        <label class="form-label fw-semibold">
                                            Quantity
                                        </label>

                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="form-control"
                                               min="1"
                                               value="{{ $oldItem['quantity'] ?? 1 }}"
                                               required>

                                    </div>


                                    <div class="col-lg-4">

                                        <label class="form-label fw-semibold">
                                            Remarks
                                        </label>

                                        <input type="text"
                                               name="items[{{ $index }}][remarks]"
                                               class="form-control"
                                               maxlength="500"
                                               value="{{ $oldItem['remarks'] ?? '' }}"
                                               placeholder="Optional">

                                    </div>


                                    <div class="col-lg-1">

                                        <button type="button"
                                                class="btn btn-outline-danger remove-item w-100">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        @endforeach


                    {{-- =================================================
                        EXISTING DATABASE ITEMS
                    ================================================== --}}
                    @else

                        @forelse($kitTemplate->items as $index => $kitItem)

                            <div class="kit-item-row border rounded-3 p-3 mb-3">

                                <div class="row g-3 align-items-end">

                                    <div class="col-lg-5">

                                        <label class="form-label fw-semibold">
                                            Supply Item
                                        </label>

                                        <select name="items[{{ $index }}][supply_item_id]"
                                                class="form-select"
                                                required>

                                            <option value="">
                                                Select Supply Item
                                            </option>

                                            @foreach($supplyItems as $supplyItem)

                                                <option value="{{ $supplyItem->id }}"
                                                    {{ $kitItem->supply_item_id == $supplyItem->id ? 'selected' : '' }}>

                                                    {{ $supplyItem->item_name }}
                                                    ({{ $supplyItem->item_code }})

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    <div class="col-lg-2">

                                        <label class="form-label fw-semibold">
                                            Quantity
                                        </label>

                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="form-control"
                                               min="1"
                                               value="{{ $kitItem->quantity }}"
                                               required>

                                    </div>


                                    <div class="col-lg-4">

                                        <label class="form-label fw-semibold">
                                            Remarks
                                        </label>

                                        <input type="text"
                                               name="items[{{ $index }}][remarks]"
                                               class="form-control"
                                               maxlength="500"
                                               value="{{ $kitItem->remarks }}"
                                               placeholder="Optional">

                                    </div>


                                    <div class="col-lg-1">

                                        <button type="button"
                                                class="btn btn-outline-danger remove-item w-100">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        @empty

                            {{-- No existing items --}}

                        @endforelse

                    @endif

                </div>


                {{-- Empty state --}}
                <div id="emptyItemsMessage"
                     class="text-center py-5 text-muted">

                    <i class="bi bi-boxes fs-1 d-block mb-2"></i>

                    <div class="fw-semibold">
                        No items added
                    </div>

                    <small>
                        Add the items provided by the government in this kit.
                    </small>

                </div>

            </div>

        </div>


        {{-- =====================================================
            FORM ACTIONS
        ====================================================== --}}
        <div class="d-flex justify-content-between align-items-center">

            <a href="{{ route('admin.kit-templates.index') }}"
               class="btn btn-light border">

                <i class="bi bi-arrow-left me-1"></i>
                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary"
                    id="updateKitBtn">

                <i class="bi bi-check-circle me-1"></i>
                Update Government Kit

            </button>

        </div>

    </form>

</div>


{{-- =============================================================
    STYLES
============================================================= --}}
<style>

    .card {
        border-radius: 14px;
    }

    .kit-item-row {
        background: #f8f9fb;
        transition: 0.2s ease;
    }

    .kit-item-row:hover {
        background: #f1f4f8;
    }

</style>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('itemsContainer');
    const addButton = document.getElementById('addItemBtn');
    const emptyMessage = document.getElementById('emptyItemsMessage');
    const form = document.getElementById('kitTemplateForm');
    const updateButton = document.getElementById('updateKitBtn');

    let itemIndex = container.querySelectorAll('.kit-item-row').length;


    function updateEmptyMessage() {

        const rows = container.querySelectorAll('.kit-item-row');

        emptyMessage.style.display =
            rows.length === 0 ? 'block' : 'none';

    }


    function createItemRow() {

        const row = document.createElement('div');

        row.className = 'kit-item-row border rounded-3 p-3 mb-3';

        row.innerHTML = `

            <div class="row g-3 align-items-end">

                <div class="col-lg-5">

                    <label class="form-label fw-semibold">
                        Supply Item
                    </label>

                    <select name="items[${itemIndex}][supply_item_id]"
                            class="form-select"
                            required>

                        <option value="">
                            Select Supply Item
                        </option>

                        @foreach($supplyItems as $supplyItem)

                            <option value="{{ $supplyItem->id }}">
                                {{ addslashes($supplyItem->item_name) }}
                                ({{ addslashes($supplyItem->item_code) }})
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-lg-2">

                    <label class="form-label fw-semibold">
                        Quantity
                    </label>

                    <input type="number"
                           name="items[${itemIndex}][quantity]"
                           class="form-control"
                           min="1"
                           value="1"
                           required>

                </div>


                <div class="col-lg-4">

                    <label class="form-label fw-semibold">
                        Remarks
                    </label>

                    <input type="text"
                           name="items[${itemIndex}][remarks]"
                           class="form-control"
                           maxlength="500"
                           placeholder="Optional">

                </div>


                <div class="col-lg-1">

                    <button type="button"
                            class="btn btn-outline-danger remove-item w-100">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            </div>

        `;

        container.appendChild(row);

        itemIndex++;

        updateEmptyMessage();

    }


    addButton.addEventListener('click', function () {

        createItemRow();

    });


    container.addEventListener('click', function (event) {

        const removeButton =
            event.target.closest('.remove-item');

        if (!removeButton) {
            return;
        }

        const row =
            removeButton.closest('.kit-item-row');

        if (row) {
            row.remove();
        }

        updateEmptyMessage();

    });


    form.addEventListener('submit', function () {

        updateButton.disabled = true;

        updateButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Updating Government Kit...
        `;

    });


    updateEmptyMessage();

});

</script>

@endsection
