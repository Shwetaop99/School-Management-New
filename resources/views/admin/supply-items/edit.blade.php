@extends('layouts.app')

@section('title', 'Edit Supply Item')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Edit Supply Item
            </h3>

            <p class="text-muted mb-0">
                Update supply item details and stock information.
            </p>
        </div>

        <a href="{{ route('admin.supply-items.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Supply Items

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('admin.supply-items.update', $supplyItem) }}">

                @csrf
                @method('PUT')


                <div class="row g-4">

                    {{-- Item Code --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Item Code <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="item_code"
                               value="{{ old('item_code', $supplyItem->item_code) }}"
                               class="form-control @error('item_code') is-invalid @enderror">

                        @error('item_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Item Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Item Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="item_name"
                               value="{{ old('item_name', $supplyItem->item_name) }}"
                               class="form-control @error('item_name') is-invalid @enderror">

                        @error('item_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Category --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Category
                        </label>

                        <select name="category"
                                class="form-select @error('category') is-invalid @enderror">

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category }}"
                                    @selected(old('category', $supplyItem->category) == $category)>
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                        @error('category')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Unit --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Unit <span class="text-danger">*</span>
                        </label>

                        <select name="unit"
                                class="form-select @error('unit') is-invalid @enderror">

                            @foreach($units as $unit)

                                <option value="{{ $unit }}"
                                    @selected(old('unit', $supplyItem->unit) == $unit)>
                                    {{ $unit }}
                                </option>

                            @endforeach

                        </select>

                        @error('unit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Quantity --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Quantity in Stock
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="quantity_in_stock"
                               min="0"
                               value="{{ old('quantity_in_stock', $supplyItem->quantity_in_stock) }}"
                               class="form-control @error('quantity_in_stock') is-invalid @enderror">

                        @error('quantity_in_stock')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Minimum Stock --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Minimum Stock
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="minimum_stock"
                               min="0"
                               value="{{ old('minimum_stock', $supplyItem->minimum_stock) }}"
                               class="form-control @error('minimum_stock') is-invalid @enderror">

                        @error('minimum_stock')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Price --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Unit Price
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input type="number"
                                   name="unit_price"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('unit_price', $supplyItem->unit_price) }}"
                                   class="form-control @error('unit_price') is-invalid @enderror">

                        </div>

                        @error('unit_price')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="active"
                                @selected(old('status', $supplyItem->status) === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(old('status', $supplyItem->status) === 'inactive')>
                                Inactive
                            </option>

                        </select>

                        @error('status')
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
                                  rows="4"
                                  class="form-control"
                                  placeholder="Optional description">{{ old('description', $supplyItem->description) }}</textarea>

                    </div>

                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.supply-items.index') }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Update Supply Item

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection