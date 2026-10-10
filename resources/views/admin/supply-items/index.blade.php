@extends('layouts.app')

@section('title', 'Supply Items')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-box-seam text-primary me-2"></i>
                Supply Items
            </h3>

            <p class="text-muted mb-0">
                Manage school supply items and stock.
            </p>
        </div>

        <a href="{{ route('admin.supply-items.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Add Supply Item
        </a>

    </div>


    {{-- Alerts --}}
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
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted">
                                Total Items
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $totalItems }}
                            </h3>
                        </div>

                        <div class="text-primary fs-2">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted">
                                Active Items
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $activeItems }}
                            </h3>
                        </div>

                        <div class="text-success fs-2">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted">
                                Low Stock
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $lowStockItems }}
                            </h3>
                        </div>

                        <div class="text-warning fs-2">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted">
                                Total Stock
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalStock) }}
                            </h3>
                        </div>

                        <div class="text-info fs-2">
                            <i class="bi bi-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.supply-items.index') }}">

                <div class="row g-3">

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Item code, item name or category">
                    </div>


                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Category
                        </label>

                        <select name="category"
                                class="form-select">

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category }}"
                                    @selected(request('category') == $category)>
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="active"
                                @selected(request('status') == 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(request('status') == 'inactive')>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="px-3">#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Stock</th>
                            <th>Min. Stock</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-end px-3">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($items as $item)

                        <tr>

                            <td class="px-3">
                                {{ $items->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    {{ $item->item_code }}
                                </span>
                            </td>

                            <td>
                                {{ $item->item_name }}
                            </td>

                            <td>
                                {{ $item->category ?? '—' }}
                            </td>

                            <td>
                                {{ $item->unit }}
                            </td>

                            <td>

                                @if($item->quantity_in_stock <= $item->minimum_stock)

                                    <span class="badge bg-warning text-dark">
                                        {{ $item->quantity_in_stock }}
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        {{ $item->quantity_in_stock }}
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $item->minimum_stock }}
                            </td>

                            <td>
                                ₹{{ number_format($item->unit_price, 2) }}
                            </td>

                            <td>

                                @if($item->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="text-end px-3">

                                <div class="dropdown">

                                    <button class="btn btn-sm btn-light"
                                            type="button"
                                            data-bs-toggle="dropdown">

                                        <i class="bi bi-three-dots-vertical"></i>

                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('admin.supply-items.show', $item) }}">

                                                <i class="bi bi-eye me-2"></i>
                                                View

                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('admin.supply-items.edit', $item) }}">

                                                <i class="bi bi-pencil me-2"></i>
                                                Edit

                                            </a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>

                                            <form method="POST"
                                                  action="{{ route('admin.supply-items.destroy', $item) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this supply item?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="dropdown-item text-danger">

                                                    <i class="bi bi-trash me-2"></i>
                                                    Delete

                                                </button>

                                            </form>

                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>

                                No supply items found.

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($items->hasPages())

            <div class="card-footer bg-white border-0">

                {{ $items->links() }}

            </div>

        @endif

    </div>

</div>

@endsection