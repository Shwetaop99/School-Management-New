@extends('layouts.app')

@section('title', 'Add Fee Type')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>
    .fee-form-page {
        background: #f4f7fb;
        min-height: calc(100vh - 64px);
        padding: 28px;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        flex-wrap: wrap;
    }

    .form-header h2 {
        color: #172b4d;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .form-header p {
        color: #7b8794;
    }

    .back-btn {
        border: 1px solid #dfe5ec;
        background: #fff;
        color: #344054;
        border-radius: 10px;
        padding: 10px 17px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .back-btn:hover {
        background: #f7faff;
        color: #1677f0;
        border-color: #cfe0ff;
    }

    .form-card {
        background: #fff;
        border: none;
        border-radius: 18px;
        box-shadow: 0 6px 25px rgba(31, 45, 61, .07);
        overflow: hidden;
    }

    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #edf5ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .form-body {
        padding: 28px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #344054;
        margin-bottom: 8px;
    }

    .required {
        color: #e5484d;
    }

    .form-control,
    .form-select {
        min-height: 46px;
        border-radius: 10px;
        border: 1px solid #dfe5ec;
        box-shadow: none;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1677f0;
        box-shadow: 0 0 0 3px rgba(22, 119, 240, .08);
    }

    .input-group .input-group-text {
        min-height: 46px;
        border: 1px solid #dfe5ec;
        border-right: none;
        background: #f8fafc;
        color: #1677f0;
        font-weight: 700;
        border-radius: 10px 0 0 10px;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    textarea.form-control {
        min-height: 115px;
        resize: vertical;
    }

    .info-box {
        background: #f7faff;
        border: 1px solid #e5efff;
        border-radius: 12px;
        padding: 15px;
        color: #667085;
        font-size: 13px;
    }

    .info-box i {
        color: #1677f0;
    }

    .btn-save {
        background: linear-gradient(135deg, #1677f0, #6c63ff);
        border: none;
        color: #fff;
        min-height: 46px;
        padding: 0 22px;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s ease;
    }

    .btn-save:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(22, 119, 240, .18);
    }

    .btn-cancel {
        min-height: 46px;
        border-radius: 10px;
        padding: 0 22px;
        font-weight: 600;
    }

    .field-help {
        font-size: 12px;
        color: #98a2b3;
        margin-top: 6px;
    }

    .alert {
        border-radius: 12px;
    }

    @media (max-width: 768px) {

        .fee-form-page {
            padding: 18px;
        }

        .form-body {
            padding: 20px;
        }

        .form-header {
            display: block;
        }

        .back-btn {
            display: inline-block;
            margin-top: 12px;
        }
    }
</style>


<div class="fee-form-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="form-header mb-4">

        <div>

            <h2>
                <i class="bi bi-cash-stack me-2"></i>
                Add Fee Type
            </h2>

            <p class="mb-0">
                Create a fee category that can be used in fee structures.
            </p>

        </div>

        <a
            href="{{ route('admin.fees.fee-types.index') }}"
            class="back-btn"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Fee Types
        </a>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm mb-4">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please fix the following:

            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         MAIN FORM CARD
    ====================================================== --}}

    <div class="form-card">


        {{-- CARD HEADER --}}

        <div class="form-card-header">

            <div class="header-icon">

                <i class="bi bi-cash-coin"></i>

            </div>

            <div>

                <h5 class="mb-1 fw-bold">
                    Fee Type Information
                </h5>

                <small class="text-muted">
                    Enter the basic details of the fee.
                </small>

            </div>

        </div>


        {{-- FORM BODY --}}

        <div class="form-body">

            <form
                action="{{ route('admin.fees.fee-types.store') }}"
                method="POST"
            >

                @csrf


                <div class="row g-4">


                    {{-- =================================================
                         FEE TYPE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Fee Type

                            <span class="required">*</span>

                        </label>

                        <select
                            name="name"
                            class="form-select @error('name') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Fee Type
                            </option>

                            @foreach($feeTypeOptions as $option)

                                <option
                                    value="{{ $option }}"
                                    {{ old('name') === $option ? 'selected' : '' }}
                                >
                                    {{ $option }}
                                </option>

                            @endforeach

                        </select>

                        <div class="field-help">
                            Select the type of fee you want to create.
                        </div>

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CATEGORY
                    ================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Category

                            <span class="required">*</span>

                        </label>

                        <select
                            name="category"
                            class="form-select @error('category') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category }}"
                                    {{ old('category') === $category ? 'selected' : '' }}
                                >
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                        <div class="field-help">
                            Example: Academic, Facility, Transport.
                        </div>

                        @error('category')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         FREQUENCY
                    ================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Frequency

                            <span class="required">*</span>

                        </label>

                        <select
                            name="frequency"
                            class="form-select @error('frequency') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Frequency
                            </option>

                            @foreach($frequencies as $frequency)

                                <option
                                    value="{{ $frequency }}"
                                    {{ old('frequency') === $frequency ? 'selected' : '' }}
                                >
                                    {{ $frequency }}
                                </option>

                            @endforeach

                        </select>

                        <div class="field-help">
                            How often this fee is normally charged.
                        </div>

                        @error('frequency')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         FEE AMOUNT
                    ================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Fee Amount

                            <span class="required">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input
                                type="number"
                                name="amount"
                                step="0.01"
                                min="0"
                                max="99999999.99"
                                class="form-control @error('amount') is-invalid @enderror"
                                value="{{ old('amount') }}"
                                placeholder="Enter fee amount"
                                required
                            >

                        </div>

                        <div class="field-help">
                            Enter the default amount for this fee type.
                        </div>

                        @error('amount')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Status

                            <span class="required">*</span>

                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="Active"
                                {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status') === 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        <div class="field-help">
                            Inactive fee types won't normally be available for new structures.
                        </div>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         DESCRIPTION
                    ================================================== --}}

                    <div class="col-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter a short description..."
                        >{{ old('description') }}</textarea>

                        <div class="field-help">
                            Optional. Add any additional information about this fee.
                        </div>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         INFORMATION BOX
                    ================================================== --}}

                    <div class="col-12">

                        <div class="info-box">

                            <i class="bi bi-info-circle me-2"></i>

                            Fee types created here will be available when creating a
                            <strong>Fee Structure</strong>.

                            <div class="mt-1">

                                The amount entered above can be used as the
                                default amount and can be adjusted later in the
                                <strong>Fee Structure</strong>.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     BUTTONS
                ================================================== --}}

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('admin.fees.fee-types.index') }}"
                        class="btn btn-light border btn-cancel"
                    >

                        <i class="bi bi-x-lg me-1"></i>

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn-save"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        Save Fee Type

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection