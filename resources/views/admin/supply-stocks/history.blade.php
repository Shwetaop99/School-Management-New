@extends('layouts.app')

@section('title', 'Supply Stock History')

@section('content')

<style>
    .history-page {
        padding: 20px;
        color: #263b35;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 22px;
    }

    .history-title {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        color: #263b35;
    }

    .history-subtitle {
        color: #7b8981;
        font-size: 13px;
        margin-top: 5px;
    }

    .history-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        background: #285c4d;
        color: #fff;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 750;
    }

    .history-back:hover {
        background: #1e493d;
        color: #fff;
    }

    .history-info-card {
        background: #fff;
        border: 1px solid #e7ebe7;
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 3px 12px rgba(30, 50, 40, .035);
    }

    .history-info-label {
        color: #829087;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: .5px;
        margin-bottom: 5px;
    }

    .history-info-value {
        color: #30483d;
        font-size: 16px;
        font-weight: 800;
    }

    .history-info-small {
        color: #7e8a82;
        font-size: 12px;
        margin-top: 4px;
    }

    .history-count {
        background: #e8f2ec;
        color: #285c4d;
        border-radius: 20px;
        padding: 8px 13px;
        font-size: 12px;
        font-weight: 800;
    }

    .history-card {
        background: #fff;
        border: 1px solid #e7ebe7;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(30, 50, 40, .035);
    }

    .history-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0ed;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .history-card-header h5 {
        color: #30483d;
        font-size: 16px;
        font-weight: 800;
        margin: 0;
    }

    .history-table-wrap {
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }

    .history-table th {
        background: #f7f9f7;
        color: #758078;
        padding: 13px 15px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .45px;
        white-space: nowrap;
        border-bottom: 1px solid #edf0ed;
    }

    .history-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #f0f2f0;
        font-size: 13px;
        vertical-align: middle;
    }

    .history-table tbody tr:hover {
        background: #fbfcfb;
    }

    .transaction-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .transaction-receive {
        background: #e5f4eb;
        color: #28734f;
    }

    .transaction-issue {
        background: #fdebea;
        color: #b64242;
    }

    .transaction-return {
        background: #e8f0fb;
        color: #3567a3;
    }

    .transaction-damaged {
        background: #fff1dc;
        color: #a96a18;
    }

    .transaction-adjustment {
        background: #f0eafa;
        color: #7952a5;
    }

    .history-quantity {
        font-weight: 850;
        font-size: 14px;
    }

    .quantity-positive { color: #28734f; }
    .quantity-negative { color: #b64242; }
    .quantity-zero { color: #7c8981; }

    .history-balance {
        font-weight: 800;
        color: #30483d;
    }

    .history-remarks {
        color: #66756b;
        max-width: 250px;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .history-empty {
        padding: 45px 20px;
        text-align: center;
        color: #849087;
    }

    .history-empty i {
        display: block;
        font-size: 36px;
        color: #aab5ad;
        margin-bottom: 10px;
    }

    .history-empty strong {
        color: #42574b;
        display: block;
        margin-bottom: 5px;
    }

    @media (max-width: 600px) {
        .history-page {
            padding: 12px;
        }

        .history-title {
            font-size: 21px;
        }

        .history-header {
            align-items: stretch;
        }

        .history-back {
            justify-content: center;
        }

        .history-info-card {
            padding: 15px;
        }

        .history-card-header {
            padding: 14px;
        }
    }
</style>

<div class="history-page">

    {{-- PAGE HEADER --}}
    <div class="history-header">

        <div>
            <h2 class="history-title">
                <i class="bi bi-clock-history me-2"></i>Stock History
            </h2>

            <div class="history-subtitle">
                View all stock received, issued, returned and adjusted for this item.
            </div>
        </div>

        <a href="{{ route('admin.supply-stocks.index', [
            'academic_year' => $academicYear
        ]) }}"
           class="history-back">
            <i class="bi bi-arrow-left"></i>
            Back to Supply Stock
        </a>

    </div>

    {{-- ITEM INFORMATION --}}
    <div class="history-info-card">

        <div>
            <div class="history-info-label">Supply Item</div>

            <div class="history-info-value">
                {{ $item->item_name ?? $item->name ?? 'Supply Item' }}
            </div>

            <div class="history-info-small">
                Item ID: {{ $item->id }}
            </div>
        </div>

        <div>
            <div class="history-info-label">Academic Year</div>
            <div class="history-info-value">{{ $academicYear }}</div>
        </div>

        <div>
            <div class="history-info-label">Total Transactions</div>
            <span class="history-count">
                {{ $transactions->count() }} Records
            </span>
        </div>

    </div>

    {{-- TRANSACTION HISTORY --}}
    <div class="history-card">

        <div class="history-card-header">
            <h5>
                <i class="bi bi-list-ul me-2"></i>Transaction Details
            </h5>

            <span class="text-muted small">
                Latest transactions appear first
            </span>
        </div>

        <div class="history-table-wrap">

            <table class="history-table">

                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Date & Time</th>
                        <th>Transaction Type</th>
                        <th class="text-center">Quantity Change</th>
                        <th class="text-center">Balance After Transaction</th>
                        <th>Remarks</th>
                        <th>Recorded By</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transactions as $index => $transaction)

                        @php
                            $typeLabels = [
                                'receive' => 'Received',
                                'issue' => 'Issued',
                                'return' => 'Returned',
                                'damaged' => 'Damaged',
                                'adjustment' => 'Adjustment',
                            ];

                            $typeClasses = [
                                'receive' => 'transaction-receive',
                                'issue' => 'transaction-issue',
                                'return' => 'transaction-return',
                                'damaged' => 'transaction-damaged',
                                'adjustment' => 'transaction-adjustment',
                            ];

                            $type = $transaction->transaction_type;
                            $quantity = (int) $transaction->quantity;
                        @endphp

                        <tr>

                            <td class="text-muted">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                <div class="fw-bold text-dark">
                                    {{ $transaction->created_at?->format('d M Y') ?? '—' }}
                                </div>

                                <div class="small text-muted">
                                    {{ $transaction->created_at?->format('h:i A') ?? '' }}
                                </div>
                            </td>

                            <td>
                                <span class="transaction-badge {{ $typeClasses[$type] ?? 'transaction-adjustment' }}">
                                    {{ $typeLabels[$type] ?? ucfirst($type) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="history-quantity
                                    {{ $quantity > 0 ? 'quantity-positive' : ($quantity < 0 ? 'quantity-negative' : 'quantity-zero') }}">

                                    @if($quantity > 0)
                                        +{{ number_format($quantity) }}
                                    @else
                                        {{ number_format($quantity) }}
                                    @endif

                                </span>
                            </td>

                            <td class="text-center">
                                <span class="history-balance">
                                    {{ number_format($transaction->balance_quantity) }}
                                </span>
                            </td>

                            <td>
                                <div class="history-remarks">
                                    {{ $transaction->remarks ?: '—' }}
                                </div>
                            </td>

                            <td>
                                {{ $transaction->creator->name ?? 'System' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="history-empty">
                                    <i class="bi bi-clock-history"></i>
                                    <strong>No transaction history found</strong>
                                    <span>
                                        No stock transactions have been recorded for
                                        this item in {{ $academicYear }}.
                                    </span>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection