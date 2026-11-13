@extends('layouts.app')

@section('title', 'View Student Supply Kit')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Student Supply Kit</h4>
            <p class="text-muted mb-0">
                View supply kit details and issued items.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.student-supply-kits.index') }}"
               class="btn btn-light border">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

            <a href="{{ route('admin.student-supply-kits.edit', $studentSupplyKit) }}"
               class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>
                Edit
            </a>

            <a href="{{ route('admin.student-supply-kits.print', $studentSupplyKit) }}"
               target="_blank"
               class="btn btn-outline-secondary">
                <i class="fas fa-print me-1"></i>
                Print
            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    {{-- Student Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0">

                <i class="fas fa-user-graduate text-primary me-2"></i>

                Student Information

            </h6>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Student Name
                    </small>

                    <div class="fw-semibold">

                        {{ trim(
                            ($studentSupplyKit->student->first_name ?? '') . ' ' .
                            ($studentSupplyKit->student->middle_name ?? '') . ' ' .
                            ($studentSupplyKit->student->last_name ?? '')
                        ) ?: '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Student ID
                    </small>

                    <div class="fw-semibold">

                        {{ $studentSupplyKit->student->student_id ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Class / Section
                    </small>

                    <div class="fw-semibold">

                        {{ $studentSupplyKit->student->admission_class ?? '-' }}

                        @if($studentSupplyKit->student?->section)
                            - {{ $studentSupplyKit->student->section }}
                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Father's Name
                    </small>

                    <div>

                        {{ $studentSupplyKit->student->father_name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Mother's Name
                    </small>

                    <div>

                        {{ $studentSupplyKit->student->mother_name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Academic Year
                    </small>

                    <div>

                        {{ $studentSupplyKit->academic_year }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Kit Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0">

                <i class="fas fa-box-open text-primary me-2"></i>

                Kit Information

            </h6>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Kit Name
                    </small>

                    <div class="fw-semibold">

                        {{ $studentSupplyKit->kitTemplate->kit_name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Government Scheme
                    </small>

                    <div>

                        {{ $studentSupplyKit->kitTemplate->scheme->scheme_name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Kit Academic Year
                    </small>

                    <div>

                        {{ $studentSupplyKit->kitTemplate->academic_year ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Distribution Date
                    </small>

                    <div>

                        {{ $studentSupplyKit->distribution_date
                            ? $studentSupplyKit->distribution_date->format('d-m-Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <div>

                        @if($studentSupplyKit->status === 'issued')

                            <span class="badge bg-success">
                                Issued
                            </span>

                        @elseif($studentSupplyKit->status === 'pending')

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Cancelled
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Total Items
                    </small>

                    <div class="fw-semibold">

                        {{ $studentSupplyKit->items->count() }}

                    </div>

                </div>


                @if($studentSupplyKit->remarks)

                    <div class="col-12">

                        <small class="text-muted d-block">
                            Remarks
                        </small>

                        <div>

                            {{ $studentSupplyKit->remarks }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Supply Items --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0">

                <i class="fas fa-boxes-stacked text-primary me-2"></i>

                Supply Items

            </h6>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width:70px;">
                                #
                            </th>

                            <th>
                                Item
                            </th>

                            <th>
                                Item Code
                            </th>

                            <th>
                                Unit
                            </th>

                            <th class="text-center">
                                Quantity
                            </th>

                            <th class="text-center">
                                Issued
                            </th>

                            <th>
                                Remarks
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($studentSupplyKit->items as $item)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $item->supplyItem->item_name ?? '-' }}

                                    </div>

                                </td>

                                <td>

                                    {{ $item->supplyItem->item_code ?? '-' }}

                                </td>

                                <td>

                                    {{ $item->supplyItem->unit ?? '-' }}

                                </td>

                                <td class="text-center">

                                    {{ $item->quantity }}

                                </td>

                                <td class="text-center">

                                    @if($item->issued_quantity > 0)

                                        <span class="badge bg-success">

                                            {{ $item->issued_quantity }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            0
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $item->remarks ?? '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-4 text-muted">

                                    No supply items found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Bottom Actions --}}
    <div class="d-flex justify-content-between mb-4">

        <a href="{{ route('admin.student-supply-kits.index') }}"
           class="btn btn-light border">

            <i class="fas fa-arrow-left me-1"></i>

            Back to List

        </a>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.student-supply-kits.edit', $studentSupplyKit) }}"
               class="btn btn-primary">

                <i class="fas fa-edit me-1"></i>

                Edit

            </a>

            <a href="{{ route('admin.student-supply-kits.print', $studentSupplyKit) }}"
               target="_blank"
               class="btn btn-outline-secondary">

                <i class="fas fa-print me-1"></i>

                Print

            </a>

        </div>

    </div>

</div>

@endsection