```blade
@extends('layouts.app')

@section('title', 'Add Supply Item')

@section('content')

<style>
    .supply-create-page {
        --green-dark: #174c3a;
        --green-main: #21664c;
        --green-light: #eaf3ed;
        --green-border: #d9e7dc;
        --text-dark: #243b31;

        padding: 25px 0 45px;
        color: var(--text-dark);
    }

    .supply-create-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 25px;
    }

    .supply-create-title {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .supply-create-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: var(--green-dark);
        color: #fff;
        font-size: 23px;
        box-shadow: 0 5px 14px rgba(23, 76, 58, .18);
    }

    .supply-create-header h3 {
        color: var(--green-dark);
        font-weight: 800;
        margin: 0 0 4px;
        font-size: 25px;
    }

    .supply-create-header p {
        color: #77877d;
        margin: 0;
        font-size: 13px;
    }

    .supply-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid var(--green-border);
        border-radius: 9px;
        color: var(--green-dark);
        background: #fff;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .supply-back-btn:hover {
        background: var(--green-dark);
        border-color: var(--green-dark);
        color: #fff;
    }

    .supply-form-card {
        background: #fff;
        border: 1px solid #e5ece6;
        border-radius: 17px;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(25, 60, 42, .07);
    }

    .supply-form-card-header {
        padding: 20px 25px;
        background: linear-gradient(120deg, #174c3a, #286c50);
        color: #fff;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .supply-form-card-header .header-icon {
        width: 39px;
        height: 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.14);
        border-radius: 11px;
        font-size: 19px;
    }

    .supply-form-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .supply-form-card-header small {
        display: block;
        color: rgba(255,255,255,.75);
        font-size: 12px;
        margin-top: 3px;
    }

    .supply-form-body {
        padding: 30px;
    }

    .supply-section-title {
        color: var(--green-dark);
        font-weight: 750;
        font-size: 14px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .supply-section-title i {
        color: var(--green-main);
        font-size: 17px;
    }

    .supply-field {
        margin-bottom: 22px;
    }

    .supply-field label {
        display: block;
        font-weight: 650;
        color: #344d40;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .supply-field label .required {
        color: #dc5555;
    }

    .supply-field .form-control,
    .supply-field .form-select {
        min-height: 45px;
        border: 1px solid #dce6de;
        border-radius: 9px;
        color: #30483a;
        font-size: 13px;
        padding: 10px 13px;
        background-color: #fff;
        box-shadow: none;
        transition: .2s ease;
    }

    .supply-field textarea.form-control {
        min-height: 115px;
        resize: vertical;
    }

    .supply-field .form-control:focus,
    .supply-field .form-select:focus {
        border-color: #438766;
        box-shadow: 0 0 0 3px rgba(52, 126, 85, .11);
    }

    .supply-field .form-control::placeholder {
        color: #a2afa6;
    }

    .supply-field .invalid-feedback {
        font-size: 12px;
    }

    .supply-field .form-text {
        color: #87958c;
        font-size: 11px;
        margin-top: 6px;
    }

    .supply-form-divider {
        border-color: #e9eee9;
        margin: 5px 0 24px;
    }

    .supply-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .supply-btn-cancel,
    .supply-btn-save {
        min-height: 43px;
        padding: 10px 20px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: .2s ease;
    }

    .supply-btn-cancel {
        background: #f1f4f1;
        border: 1px solid #e1e8e1;
        color: #53645a;
    }

    .supply-btn-cancel:hover {
        background: #e5ebe5;
        color: #263f31;
    }

    .supply-btn-save {
        border: 1px solid var(--green-dark);
        background: var(--green-dark);
        color: #fff;
    }

    .supply-btn-save:hover {
        background: #23674c;
        border-color: #23674c;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(23, 76, 58, .18);
    }

    .supply-info-note {
        background: #eff6f0;
        border: 1px solid #dceadd;
        border-radius: 10px;
        padding: 13px 15px;
        display: flex;
        gap: 11px;
        align-items: flex-start;
        color: #45634e;
        font-size: 12px;
        margin-top: 3px;
    }

    .supply-info-note i {
        font-size: 17px;
        color: var(--green-main);
    }

    @media(max-width: 767px) {
        .supply-create-page {
            padding-top: 15px;
        }

        .supply-create-header h3 {
            font-size: 21px;
        }

        .supply-form-body {
            padding: 20px 16px;
        }

        .supply-form-card-header {
            padding: 17px;
        }

        .supply-create-header {
            align-items: flex-start;
        }

        .supply-back-btn {
            width: 100%;
            justify-content: center;
        }

        .supply-form-actions {
            flex-direction: column-reverse;
        }

        .supply-btn-cancel,
        .supply-btn-save {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid supply-create-page">

    {{-- Page Header --}}
    <div class="supply-create-header">

        <div class="supply-create-title">
            <div class="supply-create-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h3>Add Supply Item</h3>
                <p>Add a new item to your school's supply inventory.</p>
            </div>
        </div>

        <a href="{{ route('admin.supply-items.index') }}"
           class="supply-back-btn">
            <i class="bi bi-arrow-left"></i>
            Back to Supply Items
        </a>

    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <strong>
                <i class="bi bi-exclamation-circle me-1"></i>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="supply-form-card">

        <div class="supply-form-card-header">
            <div class="header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h5>Supply Item Information</h5>
                <small>Enter the basic details of the new supply item.</small>
            </div>
        </div>

        <div class="supply-form-body">

            <form method="POST"
                  action="{{ route('admin.supply-items.store') }}">

                @csrf

                <div class="supply-section-title">
                    <i class="bi bi-info-circle"></i>
                    Basic Information
                </div>

                <div class="row">

                    {{-- Item Code --}}
                    <div class="col-12 col-md-6">
                        <div class="supply-field">

                            <label>
                                Item Code
                                <span class="text-muted fw-normal">(Optional)</span>
                            </label>

                            <input
                                type="text"
                                name="item_code"
                                class="form-control @error('item_code') is-invalid @enderror"
                                value="{{ old('item_code') }}"
                                placeholder="Example: NOTE-200"
                            >

                            <div class="form-text">
                                Enter a unique code for easy identification.
                            </div>

                            @error('item_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                    {{-- Item Name --}}
                    <div class="col-12 col-md-6">
                        <div class="supply-field">

                            <label>
                                Item Name <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="item_name"
                                class="form-control @error('item_name') is-invalid @enderror"
                                value="{{ old('item_name') }}"
                                placeholder="Example: Notebook 200 Pages"
                                required
                            >

                            @error('item_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                    {{-- Unit --}}
                    <div class="col-12 col-md-6">
                        <div class="supply-field">

                            <label>
                                Unit <span class="required">*</span>
                            </label>

                            <select
                                name="unit"
                                class="form-select @error('unit') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Unit</option>

                                @foreach([
                                    'Piece',
                                    'Set',
                                    'Box',
                                    'Pack',
                                    'Kg',
                                    'Litre',
                                    'Other'
                                ] as $unit)

                                    <option value="{{ $unit }}"
                                        @selected(old('unit') === $unit)>
                                        {{ $unit }}
                                    </option>

                                @endforeach

                            </select>

                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-12 col-md-6">
                        <div class="supply-field">

                            <label>
                                Status <span class="required">*</span>
                            </label>

                            <select
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <div class="supply-field mb-3">

                            <label>Description</label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter optional details about this supply item..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                </div>

                <div class="supply-info-note">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>
                        <strong>Stock management is separate.</strong>
                        <div class="mt-1">
                            After creating this item, use Supply Stock Management
                            to receive stock and set its minimum stock level.
                        </div>
                    </div>
                </div>

                <hr class="supply-form-divider">

                {{-- Form Buttons --}}
                <div class="supply-form-actions">

                    <a href="{{ route('admin.supply-items.index') }}"
                       class="supply-btn-cancel">
                        Cancel
                    </a>

                    <button type="submit" class="supply-btn-save">
                        <i class="bi bi-check-lg me-1"></i>
                        Save Supply Item
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
```