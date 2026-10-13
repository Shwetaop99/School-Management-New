@extends('layouts.app')

@section('title', 'Supply Stock')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-box-seam me-2"></i>
                Supply Stock
            </h3>

            <p class="text-muted mb-0">
                Manage available government supply stock.
            </p>
        </div>

        <div class="d-flex gap-2">

            <form method="GET"
                  action="{{ route('admin.supply-stocks.index') }}"
                  class="d-flex gap-2">

                <select name="academic_year"
                        class="form-select">

                    @foreach([
                        '2025-26',
                        '2026-27',
                        '2027-28',
                        '2028-29'
                    ] as $year)

                        <option value="{{ $year }}"
                            {{ $academicYear === $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>

                    @endforeach

                </select>

                <button class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>

            </form>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Stock Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">
                    Available Stock
                </h5>

                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#receiveStockModal">

                    <i class="bi bi-plus-circle me-1"></i>
                    Receive Stock

                </button>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Supply Item</th>

                            <th>Academic Year</th>

                            <th class="text-center">
                                Available Quantity
                            </th>

                            <th class="text-center">
                                Minimum Quantity
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($stocks as $stock)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $stock->supplyItem->item_name
                                            ?? $stock->supplyItem->name
                                            ?? 'Unknown Item' }}

                                    </div>

                                </td>

                                <td>
                                    {{ $stock->academic_year }}
                                </td>

                                <td class="text-center">

                                    <span class="fw-bold
                                        {{ $stock->quantity <= $stock->minimum_quantity
                                            ? 'text-danger'
                                            : 'text-success' }}">

                                        {{ $stock->quantity }}

                                    </span>

                                </td>

                                <td class="text-center">
                                    {{ $stock->minimum_quantity }}
                                </td>

                                <td class="text-center">

                                    @if($stock->status)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td class="text-end">

                                    <a href="{{ route(
                                        'admin.supply-stocks.history',
                                        [
                                            'supplyItemId' => $stock->supply_item_id,
                                            'academic_year' => $stock->academic_year
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-clock-history"></i>
                                        History

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5 text-muted">

                                    <i class="bi bi-box-seam fs-1 d-block mb-2"></i>

                                    No stock available for
                                    {{ $academicYear }}.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- RECEIVE STOCK MODAL --}}
{{-- ========================================================= --}}

<div class="modal fade"
     id="receiveStockModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <form method="POST"
              action="{{ route('admin.supply-stocks.receive') }}"
              class="modal-content">

            @csrf

            <div class="modal-header">

                <h5 class="modal-title fw-bold">

                    <i class="bi bi-box-arrow-in-down me-2"></i>

                    Receive Stock

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>


            <div class="modal-body">

                <input type="hidden"
                       name="academic_year"
                       value="{{ $academicYear }}">


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Supply Item
                    </label>

                    <select name="supply_item_id"
                            class="form-select"
                            required>

                        <option value="">
                            Select Supply Item
                        </option>

                        @foreach(\App\Models\SupplyItem::where('status', true)->orderBy('id')->get() as $item)

                            <option value="{{ $item->id }}">

                                {{ $item->item_name
                                    ?? $item->name
                                    ?? 'Item #'.$item->id }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Quantity
                    </label>

                    <input type="number"
                           name="quantity"
                           class="form-control"
                           min="1"
                           required>

                </div>


                <div class="mb-0">

                    <label class="form-label fw-semibold">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              class="form-control"
                              rows="3"
                              placeholder="Optional"></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Cancel

                </button>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle me-1"></i>

                    Receive Stock

                </button>

            </div>

        </form>

    </div>

</div>

@endsection