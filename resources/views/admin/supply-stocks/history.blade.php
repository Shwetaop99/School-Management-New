@extends('layouts.app')

@section('title', 'Stock History')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-clock-history me-2"></i>
                Stock History
            </h3>

            <p class="text-muted mb-0">
                {{ $item->item_name ?? $item->name ?? 'Supply Item' }}
                — {{ $academicYear }}
            </p>
        </div>

        <a href="{{ route(
            'admin.supply-stocks.index',
            ['academic_year' => $academicYear]
        ) }}"
        class="btn btn-outline-primary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Stock

        </a>

    </div>


    {{-- Transaction History --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">
                    Transaction History
                </h5>

                <span class="badge bg-primary">
                    {{ $transactions->count() }} Transactions
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Transaction</th>

                            <th class="text-center">
                                Quantity
                            </th>

                            <th class="text-center">
                                Balance
                            </th>

                            <th>Remarks</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($transactions as $transaction)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $transaction->created_at?->format('d M Y') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $transaction->created_at?->format('h:i A') }}
                                    </small>

                                </td>


                                <td>

                                    @switch($transaction->transaction_type)

                                        @case('receive')

                                            <span class="badge bg-success">
                                                <i class="bi bi-box-arrow-in-down me-1"></i>
                                                Received
                                            </span>

                                            @break

                                        @case('issue')

                                            <span class="badge bg-danger">
                                                <i class="bi bi-box-arrow-up me-1"></i>
                                                Issued
                                            </span>

                                            @break

                                        @case('return')

                                            <span class="badge bg-primary">
                                                <i class="bi bi-arrow-return-left me-1"></i>
                                                Returned
                                            </span>

                                            @break

                                        @case('damaged')

                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Damaged
                                            </span>

                                            @break

                                        @case('adjustment')

                                            <span class="badge bg-secondary">
                                                <i class="bi bi-sliders me-1"></i>
                                                Adjustment
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-dark">
                                                {{ ucfirst($transaction->transaction_type) }}
                                            </span>

                                    @endswitch

                                </td>


                                <td class="text-center fw-semibold">

                                    {{ $transaction->quantity }}

                                </td>


                                <td class="text-center">

                                    <span class="fw-bold">

                                        {{ $transaction->balance_quantity }}

                                    </span>

                                </td>


                                <td>

                                    {{ $transaction->remarks ?: '—' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5 text-muted">

                                    <i class="bi bi-clock-history fs-1 d-block mb-2"></i>

                                    No transactions found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection