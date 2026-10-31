
@extends('layouts.app')

@section('title', 'Supply Stock Management')

@section('content')

<style>
    .stock-page {
        --stock-green: #285c4d;
        --stock-dark: #203b31;
        --stock-muted: #78877e;
        --stock-border: #e7ece8;
        padding: 22px;
        color: #293b32;
        background: #f5f7f5;
        min-height: calc(100vh - 60px);
    }

    .stock-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 22px;
    }

    .stock-heading {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .stock-heading-icon {
        width: 49px;
        height: 49px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e4f0e9;
        color: var(--stock-green);
        font-size: 22px;
        flex-shrink: 0;
    }

    .stock-title {
        font-size: 25px;
        font-weight: 800;
        color: var(--stock-dark);
        margin: 0;
        letter-spacing: -.5px;
    }

    .stock-subtitle {
        font-size: 13px;
        color: var(--stock-muted);
        margin-top: 5px;
    }

    .stock-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
    }

    .stock-btn {
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 10px 15px;
        min-height: 40px;
        font-size: 12px;
        font-weight: 750;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .stock-btn-back {
        background: #fff;
        border-color: #dfe7e1;
        color: #45584c;
    }

    .stock-btn-back:hover {
        background: #edf3ef;
        color: var(--stock-green);
        border-color: #b9d0c1;
    }

    .stock-btn-primary {
        background: var(--stock-green);
        color: #fff;
    }

    .stock-btn-primary:hover {
        background: #1e493d;
        color: #fff;
        transform: translateY(-1px);
    }

    .stock-btn-add {
        background: #eaf1fc;
        border: 1px solid #dce7f8;
        color: #365f9c;
    }

    .stock-btn-add:hover {
        background: #dce8fa;
        border-color: #c4d7f3;
        color: #254d85;
        transform: translateY(-1px);
    }

    .stock-btn-print {
        background: #f0eafb;
        border: 1px solid #e3d8f5;
        color: #7952a5;
    }

    .stock-btn-print:hover {
        background: #e5daf5;
        border-color: #d4c4eb;
        color: #64418d;
        transform: translateY(-1px);
    }

    .stock-year-filter {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid var(--stock-border);
        border-radius: 10px;
        padding: 0 11px;
        min-height: 40px;
    }

    .stock-year-filter i {
        color: #718278;
    }

    .stock-year-filter select {
        border: 0;
        outline: 0;
        background: transparent;
        color: #34483f;
        font-size: 12px;
        font-weight: 700;
        min-width: 95px;
        padding: 9px 0;
    }

    .stock-alert {
        border: 0;
        border-radius: 11px;
        font-size: 13px;
        box-shadow: 0 3px 12px rgba(30, 50, 40, .035);
    }

    .stock-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 22px;
    }

    .stock-stat-card {
        background: #fff;
        border: 1px solid var(--stock-border);
        border-radius: 15px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 13px;
        box-shadow: 0 3px 12px rgba(30, 50, 40, .025);
        transition: .2s ease;
    }

    .stock-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(30, 50, 40, .06);
    }

    .stock-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-green { background: #e5f3eb; color: #28734f; }
    .stat-blue { background: #e9f0fc; color: #396ca8; }
    .stat-orange { background: #fff1df; color: #b16b13; }
    .stat-purple { background: #f0eafb; color: #7952a5; }

    .stock-stat-label {
        color: #7c8981;
        font-size: 11px;
        font-weight: 650;
        margin-bottom: 5px;
    }

    .stock-stat-value {
        color: var(--stock-dark);
        font-size: 23px;
        font-weight: 850;
        line-height: 1.2;
    }

    .stock-card {
        background: #fff;
        border: 1px solid var(--stock-border);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(30, 50, 40, .025);
    }

    .stock-card-header {
        padding: 18px 21px;
        border-bottom: 1px solid #edf0ed;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .stock-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stock-card-heading-icon {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: #edf4ef;
        color: var(--stock-green);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stock-card-header h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #30483d;
    }

    .stock-card-description {
        font-size: 11px;
        color: #8a968e;
        margin-top: 3px;
    }

    .stock-record-count {
        color: #77847c;
        background: #f5f7f5;
        border: 1px solid #e9eeea;
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 11px;
        font-weight: 700;
    }

    .stock-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .stock-table {
        width: 100%;
        min-width: 790px;
        border-collapse: collapse;
    }

    .stock-table th {
        background: #f7f9f7;
        color: #77847b;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .55px;
        font-weight: 800;
        padding: 14px 17px;
        white-space: nowrap;
        border-bottom: 1px solid #e9eeea;
    }

    .stock-table td {
        padding: 15px 17px;
        border-bottom: 1px solid #f0f3f0;
        font-size: 12px;
        vertical-align: middle;
    }

    .stock-table tbody tr {
        transition: background .15s ease;
    }

    .stock-table tbody tr:hover {
        background: #fafcfb;
    }

    .stock-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .stock-item-name {
        font-weight: 800;
        color: #30483d;
        font-size: 13px;
    }

    .stock-item-id {
        color: #98a39b;
        font-size: 10px;
        margin-top: 4px;
    }

    .stock-quantity {
        font-size: 17px;
        font-weight: 850;
    }

    .quantity-low { color: #c05a36; }
    .quantity-normal { color: #28734f; }

    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 30px;
        padding: 6px 10px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .badge-active,
    .badge-available {
        background: #e7f4eb;
        color: #28734f;
    }

    .badge-inactive {
        background: #f0f2f1;
        color: #727b75;
    }

    .badge-low {
        background: #fff0e8;
        color: #b54d2d;
    }

    .stock-history-link {
        color: #315f4e;
        background: #edf5ef;
        border: 1px solid #e1eee4;
        border-radius: 8px;
        padding: 7px 10px;
        text-decoration: none;
        font-weight: 750;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .2s ease;
    }

    .stock-history-link:hover {
        color: #fff;
        background: var(--stock-green);
        border-color: var(--stock-green);
    }

    .stock-empty {
        text-align: center;
        padding: 55px 20px;
        color: #849087;
    }

    .stock-empty-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 13px;
        border-radius: 20px;
        background: #f0f4f1;
        color: #91a196;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .stock-empty strong {
        color: #42574b;
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .stock-empty span {
        font-size: 12px;
    }

    .stock-modal .modal-content {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(20, 40, 30, .15);
    }

    .stock-modal .modal-header {
        background: var(--stock-green);
        color: #fff;
        padding: 18px 21px;
        border: 0;
    }

    .stock-modal .modal-title {
        font-size: 15px;
        font-weight: 800;
    }

    .stock-modal .btn-close {
        filter: brightness(0) invert(1);
    }

    .stock-modal .modal-body {
        padding: 23px;
    }

    .stock-modal .form-label {
        font-size: 11px;
        font-weight: 800;
        color: #45564c;
        margin-bottom: 7px;
    }

    .stock-modal .form-control,
    .stock-modal .form-select {
        border-radius: 9px;
        padding: 10px 12px;
        font-size: 12px;
        border-color: #dfe6e0;
    }

    .stock-modal .form-control:focus,
    .stock-modal .form-select:focus {
        border-color: #6a9a83;
        box-shadow: 0 0 0 3px rgba(54, 115, 82, .1);
    }

    .stock-modal .modal-footer {
        border-top: 1px solid #edf0ed;
        padding: 14px 22px;
    }

    .stock-help {
        color: #87928a;
        font-size: 10px;
        margin-top: 6px;
        line-height: 1.5;
    }

    /* PRINT REPORT */
    .stock-print-heading {
        display: none;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        body {
            background: #fff !important;
        }

        body * {
            visibility: hidden !important;
        }

        #stockPrintArea,
        #stockPrintArea * {
            visibility: visible !important;
        }

        #stockPrintArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
            background: #fff !important;
        }

        .stock-print-heading {
            display: block !important;
            text-align: center;
            padding: 10px 0 18px;
            color: #222 !important;
        }

        .stock-print-heading h2 {
            margin: 0 0 7px;
            font-size: 22px;
            font-weight: 800;
            color: #203b31 !important;
        }

        .stock-print-heading p {
            margin: 3px 0;
            font-size: 11px;
            color: #555 !important;
        }

        .stock-card-header {
            padding: 10px 0 !important;
            border-bottom: 1px solid #bbb !important;
        }

        .stock-card-heading-icon,
        .stock-card-description,
        .stock-history-link,
        .no-print {
            display: none !important;
        }

        .stock-card-header h5 {
            font-size: 15px !important;
            color: #222 !important;
        }

        .stock-record-count {
            background: #fff !important;
            border: 0 !important;
            color: #333 !important;
        }

        .stock-table-wrap {
            overflow: visible !important;
        }

        .stock-table {
            width: 100% !important;
            min-width: 100% !important;
            border-collapse: collapse !important;
        }

        .stock-table th,
        .stock-table td {
            border: 1px solid #aaa !important;
            padding: 9px 8px !important;
            font-size: 10px !important;
            color: #222 !important;
        }

        .stock-table th {
            background: #e9eee9 !important;
            font-weight: 800 !important;
        }

        .stock-item-name,
        .stock-item-id,
        .stock-quantity,
        .stock-table strong,
        .stock-table small {
            color: #222 !important;
        }

        .stock-badge {
            background: #fff !important;
            color: #222 !important;
            border: 1px solid #999 !important;
        }

        .stock-empty {
            padding: 25px !important;
            color: #333 !important;
        }

        .stock-empty-icon,
        .stock-empty a {
            display: none !important;
        }

        tr {
            page-break-inside: avoid !important;
        }

        .stock-table thead {
            display: table-header-group;
        }

        .stock-table tfoot {
            display: table-footer-group;
        }
    }

    @media (max-width: 1050px) {
        .stock-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .stock-page {
            padding: 13px;
        }

        .stock-header {
            align-items: stretch;
        }

        .stock-heading {
            align-items: center;
        }

        .stock-title {
            font-size: 21px;
        }

        .stock-subtitle {
            font-size: 11px;
        }

        .stock-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .stock-actions > * {
            width: 100%;
        }

        .stock-year-filter {
            grid-column: span 2;
        }

        .stock-year-filter select {
            width: 100%;
        }

        .stock-btn {
            padding: 10px 8px;
            font-size: 11px;
        }

        .stock-stats {
            gap: 9px;
        }

        .stock-stat-card {
            padding: 12px;
            gap: 9px;
        }

        .stock-stat-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
            border-radius: 10px;
        }

        .stock-stat-value {
            font-size: 19px;
        }

        .stock-stat-label {
            font-size: 10px;
        }

        .stock-card-header {
            padding: 14px;
        }

        .stock-modal .modal-body {
            padding: 17px;
        }
    }

    @media (max-width: 380px) {
        .stock-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stock-page">

    {{-- PAGE HEADER --}}
    <div class="stock-header">

        <div class="stock-heading">
            <div class="stock-heading-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h2 class="stock-title">Supply Stock Management</h2>
                <div class="stock-subtitle">
                    Monitor available supplies, stock levels and inventory transactions.
                </div>
            </div>
        </div>

        <div class="stock-actions">

            <a href="{{ route('admin.student-supply-kits.index', [
                    'academic_year' => $academicYear
                ]) }}"
               class="stock-btn stock-btn-back">
                <i class="bi bi-arrow-left"></i>
                Back to Supply Kits
            </a>

            {{-- ACADEMIC YEAR FILTER --}}
            <form method="GET"
                  action="{{ route('admin.supply-stocks.index') }}"
                  class="stock-year-filter">

                <i class="bi bi-calendar3"></i>

                <select name="academic_year"
                        onchange="this.form.submit()"
                        aria-label="Select academic year">

                    @foreach(['2025-26', '2026-27', '2027-28', '2028-29'] as $year)
                        <option value="{{ $year }}"
                            {{ $academicYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach

                </select>
            </form>

            <a href="{{ route('admin.supply-items.create') }}"
               class="stock-btn stock-btn-add">
                <i class="bi bi-plus-circle"></i>
                Add Supply Item
            </a>

            {{-- PRINT BUTTON --}}
            <a href="{{ route('admin.supply-stocks.print', ['academic_year' => $academicYear]) }}"
   target="_blank"
   class="btn btn-success">
    <i class="fas fa-print"></i> Print Stock
</a>

            {{-- RECEIVE STOCK --}}
            <button type="button"
                    class="stock-btn stock-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#receiveStockModal">
                <i class="bi bi-box-arrow-in-down"></i>
                Receive Stock
            </button>

        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success stock-alert alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger stock-alert">
            <strong>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please check the following:
            </strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="stock-stats">

        <div class="stock-stat-card">
            <div class="stock-stat-icon stat-green">
                <i class="bi bi-boxes"></i>
            </div>
            <div>
                <div class="stock-stat-label">Stock Items</div>
                <div class="stock-stat-value">{{ number_format($totalItems) }}</div>
            </div>
        </div>

        <div class="stock-stat-card">
            <div class="stock-stat-icon stat-blue">
                <i class="bi bi-stack"></i>
            </div>
            <div>
                <div class="stock-stat-label">Total Available Quantity</div>
                <div class="stock-stat-value">{{ number_format($totalQuantity) }}</div>
            </div>
        </div>

        <div class="stock-stat-card">
            <div class="stock-stat-icon stat-orange">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div>
                <div class="stock-stat-label">Low Stock Items</div>
                <div class="stock-stat-value">{{ number_format($lowStockCount) }}</div>
            </div>
        </div>

        <div class="stock-stat-card">
            <div class="stock-stat-icon stat-purple">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div>
                <div class="stock-stat-label">Active Stock Records</div>
                <div class="stock-stat-value">{{ number_format($activeStockCount) }}</div>
            </div>
        </div>

    </div>

    {{-- STOCK TABLE / PRINT AREA --}}
    <div class="stock-card" id="stockPrintArea">

        {{-- PRINT REPORT HEADING --}}
        <div class="stock-print-heading">
            <h2>Supply Stock Report</h2>
            <p><strong>Academic Year:</strong> {{ $academicYear }}</p>
            <p><strong>Generated On:</strong> {{ now()->format('d-m-Y h:i A') }}</p>
            <p><strong>Total Stock Items:</strong> {{ number_format($totalItems) }}</p>
        </div>

        <div class="stock-card-header">

            <div class="stock-card-heading">
                <div class="stock-card-heading-icon">
                    <i class="bi bi-list-ul"></i>
                </div>

                <div>
                    <h5>Available Stock</h5>
                    <div class="stock-card-description">
                        Current inventory balance for the selected academic year.
                    </div>
                </div>
            </div>

            <span class="stock-record-count">
                <i class="bi bi-calendar3 me-1"></i>
                {{ $academicYear }}
                <span class="mx-1">|</span>
                {{ $stocks->count() }} records
            </span>

        </div>

        <div class="stock-table-wrap">

            <table class="stock-table">

                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Supply Item</th>
                        <th class="text-center">Available Quantity</th>
                        <th class="text-center">Minimum Quantity</th>
                        <th class="text-center">Stock Level</th>
                        <th class="text-center">Status</th>
                        <th class="text-center no-print">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($stocks as $index => $stock)

                        @php
                            $item = $stock->supplyItem;
                            $itemName = $item->item_name ?? 'Unknown Item';
                            $unit = $item->unit ?? 'Units';

                            $quantity = (float) $stock->quantity;
                            $minimum = (float) $stock->minimum_quantity;

                            $isReceived = $quantity > 0;
                            $isLowStock = $stock->status && $isReceived && $quantity <= $minimum;
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <div class="stock-item-name">{{ $itemName }}</div>

                                <div class="stock-item-id">
                                    Item ID: {{ $stock->supply_item_id }}

                                    @if($item && $item->item_code)
                                        <span class="mx-1">|</span>
                                        Code: {{ $item->item_code }}
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="stock-quantity {{ $isLowStock ? 'quantity-low' : 'quantity-normal' }}">
                                    {{ number_format($quantity, 2) }}
                                </span>
                                <small class="text-muted">{{ $unit }}</small>
                            </td>

                            <td class="text-center">
                                <strong>{{ number_format($minimum, 2) }}</strong>
                                <small class="text-muted">{{ $unit }}</small>
                            </td>

                            <td class="text-center">
                                @if(!$stock->status)
                                    <span class="stock-badge badge-inactive">
                                        Disabled
                                    </span>
                                @elseif(!$isReceived)
                                    <span class="stock-badge badge-inactive">
                                        Not Received
                                    </span>
                                @elseif($isLowStock)
                                    <span class="stock-badge badge-low">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="stock-badge badge-available">
                                        Available
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($stock->status)
                                    <span class="stock-badge badge-active">Active</span>
                                @else
                                    <span class="stock-badge badge-inactive">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center no-print">
                                <a href="{{ route('admin.supply-stocks.history', [
                                    'supplyItemId' => $stock->supply_item_id,
                                    'academic_year' => $academicYear
                                ]) }}"
                                   class="stock-history-link">
                                    <i class="bi bi-clock-history"></i>
                                    View History
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="stock-empty">
                                    <div class="stock-empty-icon">
                                        <i class="bi bi-inboxes"></i>
                                    </div>

                                    <strong>No stock records found</strong>

                                    <span>
                                        No active supply items are available for {{ $academicYear }}.
                                        Add a supply item to get started.
                                    </span>

                                    <div class="mt-3 no-print">
                                        <a href="{{ route('admin.supply-items.create') }}"
                                           class="stock-btn stock-btn-primary">
                                            <i class="bi bi-plus-circle"></i>
                                            Add Supply Item
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>

{{-- RECEIVE STOCK MODAL --}}
<div class="modal fade stock-modal"
     id="receiveStockModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.supply-stocks.receive') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-box-arrow-in-down me-2"></i>
                        Receive New Stock
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
                        <label class="form-label">Select Supply Item *</label>

                        <select name="supply_item_id"
                                class="form-select"
                                required>

                            <option value="">Choose supply item</option>

                            @foreach($supplyItems as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('supply_item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name ?? 'Supply Item' }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Received Quantity *</label>

                        <input type="number"
                               name="quantity"
                               class="form-control"
                               min="1"
                               step="1"
                               value="{{ old('quantity') }}"
                               placeholder="Enter received quantity"
                               required>

                        <div class="stock-help">
                            Enter the total number of units received.
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Remarks</label>

                        <textarea name="remarks"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Supplier, bill number or delivery details...">{{ old('remarks') }}</textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light btn-sm"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="stock-btn stock-btn-primary">
                        <i class="bi bi-check-circle"></i>
                        Save Received Stock
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- REOPEN RECEIVE MODAL AFTER VALIDATION FAILURE --}}
@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('receiveStockModal');

            if (window.bootstrap && modalElement) {
                bootstrap.Modal
                    .getOrCreateInstance(modalElement)
                    .show();
            }
        });
    </script>
@endif

<script>
    function printStockDetails() {
        window.print();
    }
</script>

@endsection
