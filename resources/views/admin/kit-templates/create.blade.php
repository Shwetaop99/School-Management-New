
@extends('layouts.app')

@section('title', 'Create Government Kit')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-box-seam text-primary me-2"></i>
                Create Government Student Supply Kit
            </h3>

            <p class="text-muted mb-0">
                Define the standard items provided by the government to students.
            </p>
        </div>

        <a href="{{ route('admin.kit-templates.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Government Kits

        </a>

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


    <form method="POST"
          action="{{ route('admin.kit-templates.store') }}"
          id="kitTemplateForm">

        @csrf


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
                    Enter the details of the government-provided kit.
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
                               value="{{ old('kit_name') }}"
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
                                    {{ old('class') == $class ? 'selected' : '' }}>

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
                               value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
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
                                  placeholder="Describe the government scheme or purpose of this kit...">{{ old('description') }}</textarea>

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
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}>
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

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="fw-bold mb-1">

                            <i class="bi bi-boxes text-primary me-2"></i>

                            Government Supplied Items

                        </h5>

                        <small class="text-muted">
                            Add the items and quantity provided to each student.
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

                    {{-- Existing old input rows --}}
                    @if(old('items'))

                        @foreach(old('items') as $index => $oldItem)

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

                    @endif

                </div>


                {{-- Empty message --}}
                <div id="emptyItemsMessage"
                     class="text-center py-5 text-muted">

                    <i class="bi bi-boxes fs-1 d-block mb-2"></i>

                    <div class="fw-semibold">
                        No items added yet
                    </div>

                    <small>
                        Add the items supplied by the government in this kit.
                    </small>

                </div>

            </div>

        </div>


        {{-- =====================================================
            FORM ACTIONS
        ====================================================== --}}
        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.kit-templates.index') }}"
               class="btn btn-light border">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary"
                    id="saveKitBtn">

                <i class="bi bi-check-circle me-1"></i>
                Create Government Kit

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
    const saveButton = document.getElementById('saveKitBtn');

    let itemIndex = {{ old('items') ? count(old('items')) : 0 }};


    function updateEmptyMessage() {

        const rows = container.querySelectorAll('.kit-item-row');

        if (rows.length === 0) {
            emptyMessage.style.display = 'block';
        } else {
            emptyMessage.style.display = 'none';
        }

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

        const removeButton = event.target.closest('.remove-item');

        if (!removeButton) {
            return;
        }

        const row = removeButton.closest('.kit-item-row');

        if (row) {
            row.remove();
        }

        updateEmptyMessage();

    });


    form.addEventListener('submit', function () {

        saveButton.disabled = true;

        saveButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Creating Government Kit...
        `;

    });


    updateEmptyMessage();

});

</script>

@endsection
