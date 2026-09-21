```blade
@extends('layouts.app')

@section('title', 'Government Kit Details')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-box-seam text-primary me-2"></i>
                Government Kit Details
            </h3>

            <p class="text-muted mb-0">
                View the standard items provided to each student.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.kit-templates.edit', $kitTemplate) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil me-1"></i>
                Edit Government Kit

            </a>

            <a href="{{ route('admin.kit-templates.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        KIT SUMMARY
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4 align-items-center">

                {{-- Icon --}}
                <div class="col-auto">

                    <div class="rounded-4 bg-primary bg-opacity-10
                                text-primary d-flex align-items-center
                                justify-content-center"
                         style="width:80px;height:80px;">

                        <i class="bi bi-box-seam fs-1"></i>

                    </div>

                </div>


                {{-- Name --}}
                <div class="col">

                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">

                        <h4 class="fw-bold mb-0">
                            {{ $kitTemplate->kit_name }}
                        </h4>

                        @if($kitTemplate->status === 'active')

                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                <i class="bi bi-pause-circle me-1"></i>
                                Inactive
                            </span>

                        @endif

                    </div>

                    <div class="text-muted">

                        Government Student Supply Kit

                    </div>

                </div>


                {{-- Class --}}
                <div class="col-md-auto">

                    <div class="border rounded-3 px-4 py-3 text-center">

                        <div class="small text-muted">
                            Applicable Class
                        </div>

                        <div class="fw-bold fs-5">
                            Class {{ $kitTemplate->class }}
                        </div>

                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-md-auto">

                    <div class="border rounded-3 px-4 py-3 text-center">

                        <div class="small text-muted">
                            Academic Year
                        </div>

                        <div class="fw-bold fs-5">
                            {{ $kitTemplate->academic_year ?: '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- =====================================================
            KIT INFORMATION
        ====================================================== --}}
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-info-circle text-primary me-2"></i>

                        Kit Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-4">

                        <div class="small text-muted mb-1">
                            Government Kit Name
                        </div>

                        <div class="fw-semibold">
                            {{ $kitTemplate->kit_name }}
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="small text-muted mb-1">
                            Applicable Class
                        </div>

                        <div class="fw-semibold">
                            Class {{ $kitTemplate->class }}
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="small text-muted mb-1">
                            Academic Year
                        </div>

                        <div class="fw-semibold">
                            {{ $kitTemplate->academic_year ?: 'Not specified' }}
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="small text-muted mb-1">
                            Status
                        </div>

                        <div>

                            @if($kitTemplate->status === 'active')

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


                    <div>

                        <div class="small text-muted mb-1">
                            Description
                        </div>

                        <div class="text-muted">

                            @if($kitTemplate->description)

                                {{ $kitTemplate->description }}

                            @else

                                No description provided.

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            GOVERNMENT SUPPLIED ITEMS
        ====================================================== --}}
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-boxes text-primary me-2"></i>

                                Government Supplied Items

                            </h5>

                            <small class="text-muted">

                                Standard quantity provided to one student.

                            </small>

                        </div>

                        <span class="badge bg-primary">

                            {{ $kitTemplate->items->count() }} Items

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    @if($kitTemplate->items->count())

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-4">
                                            #
                                        </th>

                                        <th>
                                            Supply Item
                                        </th>

                                        <th>
                                            Code
                                        </th>

                                        <th>
                                            Unit
                                        </th>

                                        <th class="text-center">
                                            Quantity / Student
                                        </th>

                                        <th>
                                            Stock Available
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($kitTemplate->items as $kitItem)

                                        <tr>

                                            <td class="ps-4 text-muted">
                                                {{ $loop->iteration }}
                                            </td>


                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $kitItem->supplyItem?->item_name ?? 'Unknown Item' }}

                                                </div>

                                                @if($kitItem->remarks)

                                                    <small class="text-muted">

                                                        {{ $kitItem->remarks }}

                                                    </small>

                                                @endif

                                            </td>


                                            <td>

                                                <span class="badge bg-light text-dark border">

                                                    {{ $kitItem->supplyItem?->item_code ?? '—' }}

                                                </span>

                                            </td>


                                            <td>

                                                {{ $kitItem->supplyItem?->unit ?? '—' }}

                                            </td>


                                            <td class="text-center">

                                                <span class="badge bg-primary">

                                                    {{ $kitItem->quantity }}

                                                </span>

                                            </td>


                                            <td>

                                                @php
                                                    $stock = $kitItem->supplyItem?->quantity_in_stock ?? 0;
                                                    $required = $kitItem->quantity;
                                                @endphp

                                                @if($stock >= $required)

                                                    <span class="text-success fw-semibold">

                                                        <i class="bi bi-check-circle me-1"></i>

                                                        {{ $stock }}

                                                    </span>

                                                @else

                                                    <span class="text-danger fw-semibold">

                                                        <i class="bi bi-exclamation-triangle me-1"></i>

                                                        {{ $stock }}

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5 px-3">

                            <div class="rounded-circle bg-light d-inline-flex
                                        align-items-center justify-content-center mb-3"
                                 style="width:70px;height:70px;">

                                <i class="bi bi-boxes text-muted fs-2"></i>

                            </div>

                            <h6 class="fw-bold">
                                No Items Defined
                            </h6>

                            <p class="text-muted mb-3">
                                No government-supplied items have been added to this kit.
                            </p>

                            <a href="{{ route('admin.kit-templates.edit', $kitTemplate) }}"
                               class="btn btn-primary btn-sm">

                                <i class="bi bi-plus-circle me-1"></i>
                                Add Items

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DISTRIBUTION INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-primary bg-opacity-10
                                    text-primary p-3 me-3">

                            <i class="bi bi-boxes fs-4"></i>

                        </div>

                        <div>

                            <div class="small text-muted">
                                Items in Kit
                            </div>

                            <div class="fw-bold fs-5">
                                {{ $kitTemplate->items->count() }}
                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-success bg-opacity-10
                                    text-success p-3 me-3">

                            <i class="bi bi-person-check fs-4"></i>

                        </div>

                        <div>

                            <div class="small text-muted">
                                Distribution
                            </div>

                            <div class="fw-bold">
                                Per Student
                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-info bg-opacity-10
                                    text-info p-3 me-3">

                            <i class="bi bi-calendar3 fs-4"></i>

                        </div>

                        <div>

                            <div class="small text-muted">
                                Academic Year
                            </div>

                            <div class="fw-bold">
                                {{ $kitTemplate->academic_year ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACTIONS
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mt-4">

        <a href="{{ route('admin.kit-templates.index') }}"
           class="btn btn-light border">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Government Kits

        </a>


        <a href="{{ route('admin.kit-templates.edit', $kitTemplate) }}"
           class="btn btn-primary">

            <i class="bi bi-pencil me-1"></i>
            Edit Government Kit

        </a>

    </div>

</div>


{{-- =============================================================
    STYLES
============================================================= --}}
<style>

    .card {
        border-radius: 14px;
    }

    .table th {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .table td {
        font-size: 14px;
    }

</style>

@endsection
