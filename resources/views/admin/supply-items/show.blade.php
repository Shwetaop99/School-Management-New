@extends('layouts.app')

@section('title', 'Supply Item Details')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-box-seam text-primary me-2"></i>
                Supply Item Details
            </h3>

            <p class="text-muted mb-0">
                View supply item and stock information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.supply-items.edit', $supplyItem) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil me-1"></i>
                Edit

            </a>

            <a href="{{ route('admin.supply-items.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Main Information --}}
    <div class="row g-4">

        {{-- Item Details --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">
                        Item Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Item Code
                            </small>

                            <div class="fw-semibold fs-5">
                                {{ $supplyItem->item_code }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Item Name
                            </small>

                            <div class="fw-semibold fs-5">
                                {{ $supplyItem->item_name }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Category
                            </small>

                            <div>
                                {{ $supplyItem->category ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Unit
                            </small>

                            <div>
                                {{ $supplyItem->unit }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Unit Price
                            </small>

                            <div class="fw-semibold">
                                ₹{{ number_format($supplyItem->unit_price, 2) }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            <div>

                                @if($supplyItem->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="col-12">

                            <small class="text-muted d-block">
                                Description
                            </small>

                            <div>
                                {{ $supplyItem->description ?: 'No description available.' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Stock --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">
                        Stock Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="text-center py-3">

                        <small class="text-muted">
                            Current Stock
                        </small>

                        <h1 class="fw-bold mb-1
                            {{ $supplyItem->quantity_in_stock <= $supplyItem->minimum_stock
                                ? 'text-warning'
                                : 'text-success' }}">

                            {{ number_format($supplyItem->quantity_in_stock) }}

                        </h1>

                        <div class="text-muted">
                            {{ $supplyItem->unit }}
                        </div>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Minimum Stock
                        </span>

                        <strong>
                            {{ number_format($supplyItem->minimum_stock) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Stock Status
                        </span>

                        @if($supplyItem->quantity_in_stock <= $supplyItem->minimum_stock)

                            <span class="badge bg-warning text-dark">
                                Low Stock
                            </span>

                        @else

                            <span class="badge bg-success">
                                Available
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="fw-bold mb-1">
                        Delete Supply Item
                    </h6>

                    <p class="text-muted mb-0">
                        You can delete this item only if it has not been
                        used in any kit.
                    </p>

                </div>


                <form method="POST"
                      action="{{ route('admin.supply-items.destroy', $supplyItem) }}"
                      onsubmit="return confirm('Are you sure you want to delete this supply item?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-outline-danger">

                        <i class="bi bi-trash me-1"></i>
                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection