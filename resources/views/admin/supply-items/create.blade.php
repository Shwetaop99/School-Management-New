@extends('layouts.app')

@section('title', 'Add Supply Item')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-plus-circle text-primary me-2"></i>
                Add Supply Item
            </h3>

            <p class="text-muted mb-0">
                Add a new school supply item.
            </p>
        </div>

        <a href="{{ route('admin.supply-items.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    {{-- Form Card --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('admin.supply-items.store') }}">

                @csrf

                <div class="row g-4">

                    {{-- Item Code --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Item Code
                        </label>

                        <input type="text"
                               name="item_code"
                               class="form-control @error('item_code') is-invalid @enderror"
                               value="{{ old('item_code') }}"
                               placeholder="Example: NOTE-200">

                        @error('item_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Item Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Item Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="item_name"
                               class="form-control @error('item_name') is-invalid @enderror"
                               value="{{ old('item_name') }}"
                               placeholder="Example: Notebook 200 Pages"
                               required>

                        @error('item_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Unit --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Unit
                            <span class="text-danger">*</span>
                        </label>

                        <select name="unit"
                                class="form-select @error('unit') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Unit
                            </option>

                            <option value="Piece"
                                @selected(old('unit') === 'Piece')>
                                Piece
                            </option>

                            <option value="Set"
                                @selected(old('unit') === 'Set')>
                                Set
                            </option>

                            <option value="Box"
                                @selected(old('unit') === 'Box')>
                                Box
                            </option>

                            <option value="Pack"
                                @selected(old('unit') === 'Pack')>
                                Pack
                            </option>

                            <option value="Kg"
                                @selected(old('unit') === 'Kg')>
                                Kg
                            </option>

                            <option value="Litre"
                                @selected(old('unit') === 'Litre')>
                                Litre
                            </option>

                            <option value="Other"
                                @selected(old('unit') === 'Other')>
                                Other
                            </option>

                        </select>

                        @error('unit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
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
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Optional description">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <hr class="my-4">


                {{-- Form Buttons --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.supply-items.index') }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Save Supply Item

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection