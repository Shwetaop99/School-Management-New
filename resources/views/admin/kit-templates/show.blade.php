```blade
@extends('layouts.app')

@section('title', 'Government Kit Details')

@section('content')

@php
    $items = $kitTemplate->items;
    $totalItems = $items->count();

    $academicYear = $kitTemplate->academic_year;

    $getAvailableStock = function ($supply) use ($academicYear) {
        if (!$supply) {
            return 0;
        }

        $stockQuery = $supply->stock();

        if ($academicYear) {
            $stockQuery->where('academic_year', $academicYear);
        }

        return (float) ($stockQuery->value('quantity') ?? 0);
    };

    $totalRequired = $items->sum(
        fn ($item) => (float) $item->quantity
    );

    $lowStockCount = $items->filter(function ($item) use ($getAvailableStock) {
        $stock = $getAvailableStock($item->supplyItem);
        $required = (float) $item->quantity;

        return $stock < $required;
    })->count();

    $outOfStockCount = $items->filter(function ($item) use ($getAvailableStock) {
        return $getAvailableStock($item->supplyItem) <= 0;
    })->count();
@endphp

<div class="container-fluid kit-details-page py-4">

    {{-- PAGE HEADER --}}
    <div class="kit-page-header mb-4">
        <div>
            <div class="kit-eyebrow">
                <i class="bi bi-grid-1x2-fill me-2"></i>
                GOVERNMENT SUPPLY MANAGEMENT
            </div>

            <h2 class="kit-page-title">Government Kit Details</h2>

            <p class="kit-page-subtitle">
                View kit information, student requirements and current inventory.
            </p>
        </div>

        <div class="kit-header-actions">
            <a href="{{ route('admin.kit-templates.index') }}"
               class="btn btn-light border">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>

            <a href="{{ route('admin.kit-templates.edit', $kitTemplate) }}"
               class="btn btn-kit-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit Kit
            </a>
        </div>
    </div>

    {{-- KIT HERO --}}
    <div class="kit-hero mb-4">
        <div class="kit-hero-icon">
            <i class="bi bi-box-seam"></i>
        </div>

        <div class="kit-hero-content">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <h3>{{ $kitTemplate->kit_name }}</h3>

                @if($kitTemplate->status === 'active')
                    <span class="kit-status kit-status-active">
                        <span class="kit-status-dot"></span> Active
                    </span>
                @else
                    <span class="kit-status kit-status-inactive">
                        <span class="kit-status-dot"></span> Inactive
                    </span>
                @endif
            </div>

            <p>
                {{ $kitTemplate->description ?: 'Standard government-supplied items for eligible students.' }}
            </p>

            <div class="kit-meta-list">
                <span>
                    <i class="bi bi-mortarboard"></i>
                    Class {{ $kitTemplate->class }}
                </span>

                <span>
                    <i class="bi bi-calendar3"></i>
                    {{ $kitTemplate->academic_year ?: 'Academic year not specified' }}
                </span>

                <span>
                    <i class="bi bi-boxes"></i>
                    {{ $totalItems }} supply items
                </span>
            </div>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon stat-blue">
                    <i class="bi bi-boxes"></i>
                </div>
                <div>
                    <div class="kit-stat-label">Total Items</div>
                    <div class="kit-stat-value">{{ $totalItems }}</div>
                    <div class="kit-stat-help">Items configured in this kit</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon stat-purple">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div>
                    <div class="kit-stat-label">Required Quantity</div>
                    <div class="kit-stat-value">
                        {{ number_format($totalRequired, 2) }}
                    </div>
                    <div class="kit-stat-help">Sum of per-student quantities</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon stat-orange">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="kit-stat-label">Insufficient Stock</div>
                    <div class="kit-stat-value">{{ $lowStockCount }}</div>
                    <div class="kit-stat-help">Items below one student's requirement</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon stat-red">
                    <i class="bi bi-box2"></i>
                </div>
                <div>
                    <div class="kit-stat-label">Out of Stock</div>
                    <div class="kit-stat-value">{{ $outOfStockCount }}</div>
                    <div class="kit-stat-help">Items with zero available stock</div>
                </div>
            </div>
        </div>

    </div>

    {{-- INVENTORY TABLE --}}
    <div class="kit-panel">

        <div class="kit-panel-header">
            <div>
                <h5>
                    <i class="bi bi-clipboard-data me-2"></i>
                    Supply Inventory & Requirements
                </h5>
                <p>Available inventory compared with the standard quantity for one student.</p>
            </div>

            <span class="kit-count-badge">
                {{ $totalItems }} {{ $totalItems === 1 ? 'Item' : 'Items' }}
            </span>
        </div>

        @if($totalItems > 0)

            <div class="table-responsive">
                <table class="table kit-inventory-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Supply Item</th>
                            <th>Item Code</th>
                            <th>Unit</th>
                            <th class="text-center">Per Student</th>
                            <th>Available Stock</th>
                            <th>Stock Status</th>
                        </tr>
                    </thead>

                    <tbody>
    @foreach($items as $kitItem)
        @php
            $supply = $kitItem->supplyItem;

            $stock = $getAvailableStock($supply);
            $required = (float) $kitItem->quantity;

            $unit = $supply?->unit ?? '';
            $stockMissing = !$supply;
            $outOfStock = !$stockMissing && $stock <= 0;
            $insufficient = !$stockMissing
                && $stock > 0
                && $stock < $required;
            $sufficient = !$stockMissing && $stock >= $required;
        @endphp

        <tr>
            <td class="ps-4 text-muted">{{ $loop->iteration }}</td>

            <td>
                <div class="kit-item-name">
                    <span class="kit-item-icon">
                        <i class="bi bi-box"></i>
                    </span>

                    <div>
                        <div class="fw-semibold">
                            {{ $supply?->item_name ?? 'Unknown Item' }}
                        </div>

                        @if($kitItem->remarks)
                            <small class="text-muted">
                                {{ $kitItem->remarks }}
                            </small>
                        @endif
                    </div>
                </div>
            </td>

            <td>
                <span class="kit-code">
                    {{ $supply?->item_code ?? '—' }}
                </span>
            </td>

            <td>{{ $unit ?: '—' }}</td>

            <td class="text-center">
                <span class="kit-quantity">
                    {{ number_format($required, 2) }}
                    @if($unit)
                        <small>{{ $unit }}</small>
                    @endif
                </span>
            </td>

            <td>
                <div class="kit-stock-number
                    {{ $stockMissing || $outOfStock
                        ? 'stock-zero'
                        : ($insufficient ? 'stock-low' : 'stock-good') }}">

                    {{ number_format($stock, 2) }}
                    <small>{{ $unit }}</small>
                </div>

                @if($stockMissing)
                    <small class="text-muted">
                        Supply item not linked
                    </small>
                @elseif($academicYear)
                    <small class="text-muted">
                        Academic year: {{ $academicYear }}
                    </small>
                @endif

                @if(!$stockMissing && $required > 0)
                    <div class="kit-stock-track mt-2">
                        <div class="kit-stock-fill
                            {{ $sufficient ? 'fill-good' : 'fill-low' }}"
                            style="width: {{ min(100, ($stock / $required) * 100) }}%">
                        </div>
                    </div>
                @endif
            </td>

            <td>
                @if($stockMissing)
                    <span class="kit-stock-badge badge-neutral">
                        <i class="bi bi-question-circle"></i>
                        Unknown
                    </span>
                @elseif($outOfStock)
                    <span class="kit-stock-badge badge-danger">
                        <i class="bi bi-x-circle"></i>
                        Out of stock
                    </span>
                @elseif($insufficient)
                    <span class="kit-stock-badge badge-warning">
                        <i class="bi bi-exclamation-circle"></i>
                        Insufficient
                    </span>
                @else
                    <span class="kit-stock-badge badge-success">
                        <i class="bi bi-check-circle"></i>
                        Sufficient
                    </span>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>
                </table>
            </div>

            <div class="kit-table-footer">
                <div>
                    <i class="bi bi-info-circle me-1"></i>
                    Stock is compared with the quantity required for <strong>one student</strong>.
                </div>

                <div class="kit-legend">
                    <span><i class="legend-dot dot-green"></i> Sufficient</span>
                    <span><i class="legend-dot dot-orange"></i> Insufficient</span>
                    <span><i class="legend-dot dot-red"></i> Out of stock</span>
                </div>
            </div>

        @else
            <div class="kit-empty-state">
                <div class="kit-empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h5>No supply items added</h5>
                <p>Add the standard government-supplied items to this kit to view stock availability.</p>

                <a href="{{ route('admin.kit-templates.edit', $kitTemplate) }}"
                   class="btn btn-kit-primary">
                    <i class="bi bi-plus-lg me-1"></i> Add Supply Items
                </a>
            </div>
        @endif
    </div>

    {{-- FOOTER ACTIONS --}}
    <div class="kit-bottom-actions mt-4">
        <a href="{{ route('admin.kit-templates.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> All Government Kits
        </a>

        <a href="{{ route('admin.kit-templates.edit', $kitTemplate) }}"
           class="btn btn-kit-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit Government Kit
        </a>
    </div>

</div>

<style>
    .kit-details-page {
        color: #1e293b;
    }

    .kit-page-header,
    .kit-header-actions,
    .kit-meta-list,
    .kit-bottom-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .kit-eyebrow {
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.4px;
        margin-bottom: 8px;
    }

    .kit-page-title {
        font-size: clamp(24px, 3vw, 31px);
        font-weight: 800;
        letter-spacing: -.7px;
        margin: 0 0 6px;
    }

    .kit-page-subtitle {
        color: #64748b;
        margin: 0;
        font-size: 14px;
    }

    .btn-kit-primary {
        background: #176b50;
        border: 1px solid #176b50;
        color: #fff;
        font-weight: 600;
        border-radius: 9px;
        padding: 10px 16px;
    }

    .btn-kit-primary:hover,
    .btn-kit-primary:focus {
        background: #10543e;
        border-color: #10543e;
        color: #fff;
    }

    .kit-hero {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        padding: 26px;
        border-radius: 17px;
        background: linear-gradient(120deg, #103f32, #176b50 65%, #25815f);
        color: white;
        box-shadow: 0 8px 24px rgba(23, 107, 80, .12);
    }

    .kit-hero-icon {
        width: 66px;
        height: 66px;
        flex: 0 0 66px;
        display: grid;
        place-items: center;
        border-radius: 17px;
        background: rgba(255,255,255,.14);
        font-size: 30px;
    }

    .kit-hero-content {
        min-width: 0;
        flex: 1;
    }

    .kit-hero h3 {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        overflow-wrap: anywhere;
    }

    .kit-hero-content > p {
        color: rgba(255,255,255,.78);
        margin: 0 0 17px;
        font-size: 14px;
    }

    .kit-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .kit-status-active {
        color: #d1fae5;
        background: rgba(16,185,129,.2);
        border: 1px solid rgba(167,243,208,.3);
    }

    .kit-status-inactive {
        color: #f1f5f9;
        background: rgba(148,163,184,.2);
        border: 1px solid rgba(226,232,240,.3);
    }

    .kit-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .kit-meta-list {
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 10px 24px;
        color: rgba(255,255,255,.9);
        font-size: 13px;
    }

    .kit-meta-list span {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .kit-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        height: 100%;
        min-height: 120px;
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 14px;
        padding: 19px;
        box-shadow: 0 3px 12px rgba(15,23,42,.025);
    }

    .kit-stat-icon {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        border-radius: 13px;
        font-size: 21px;
    }

    .stat-blue { background: #eaf2ff; color: #2563eb; }
    .stat-purple { background: #f1eafe; color: #7c3aed; }
    .stat-orange { background: #fff2df; color: #c77716; }
    .stat-red { background: #feecec; color: #dc2626; }

    .kit-stat-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .kit-stat-value {
        color: #172033;
        font-size: 25px;
        line-height: 1.15;
        font-weight: 800;
    }

    .kit-stat-help {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 5px;
    }

    .kit-panel {
        background: white;
        border: 1px solid #e6ebf0;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(15,23,42,.025);
    }

    .kit-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        padding: 22px 24px;
        border-bottom: 1px solid #edf0f4;
    }

    .kit-panel-header h5 {
        font-size: 17px;
        font-weight: 800;
        margin: 0 0 5px;
    }

    .kit-panel-header h5 i {
        color: #176b50;
    }

    .kit-panel-header p {
        color: #64748b;
        font-size: 13px;
        margin: 0;
    }

    .kit-count-badge {
        white-space: nowrap;
        background: #e9f5ef;
        color: #176b50;
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 12px;
        font-weight: 700;
    }

    .kit-inventory-table thead th {
        background: #f7f9fb;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .45px;
        padding: 15px 12px;
        white-space: nowrap;
        border-bottom: 1px solid #e8edf2;
    }

    .kit-inventory-table tbody td {
        padding: 17px 12px;
        border-color: #edf0f4;
        font-size: 13px;
        color: #475569;
    }

    .kit-inventory-table tbody tr:hover {
        background: #fbfdfc;
    }

    .kit-item-name {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 180px;
    }

    .kit-item-icon {
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        flex: 0 0 36px;
        border-radius: 10px;
        background: #edf7f1;
        color: #176b50;
        font-size: 16px;
    }

    .kit-item-name .fw-semibold {
        color: #1e293b;
        font-size: 13px;
    }

    .kit-code {
        display: inline-block;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 4px 7px;
        font-size: 11px;
        white-space: nowrap;
    }

    .kit-quantity {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        font-weight: 800;
        color: #334155;
        background: #f1f5f9;
        border-radius: 7px;
        padding: 6px 10px;
        min-width: 65px;
    }

    .kit-quantity small,
    .kit-stock-number small {
        font-size: 10px;
        font-weight: 500;
        color: #64748b;
    }

    .kit-stock-number {
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
    }

    .stock-good { color: #15803d; }
    .stock-low { color: #c77716; }
    .stock-zero { color: #dc2626; }

    .kit-stock-track {
        width: 100px;
        height: 5px;
        overflow: hidden;
        border-radius: 10px;
        background: #edf0f4;
    }

    .kit-stock-fill {
        height: 100%;
        border-radius: inherit;
    }

    .fill-good { background: #22a06b; }
    .fill-low { background: #f59e0b; }

    .kit-stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 8px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-success { background: #e8f7ee; color: #167344; }
    .badge-warning { background: #fff4df; color: #a96009; }
    .badge-danger { background: #feecec; color: #c62828; }
    .badge-neutral { background: #f1f5f9; color: #64748b; }

    .kit-table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #fbfcfd;
        border-top: 1px solid #edf0f4;
        padding: 15px 22px;
        color: #64748b;
        font-size: 12px;
    }

    .kit-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 13px;
    }

    .kit-legend span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .dot-green { background: #22a06b; }
    .dot-orange { background: #f59e0b; }
    .dot-red { background: #dc2626; }

    .kit-empty-state {
        text-align: center;
        padding: 55px 20px;
    }

    .kit-empty-icon {
        width: 65px;
        height: 65px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: #edf7f1;
        color: #176b50;
        font-size: 28px;
        margin: 0 auto 16px;
    }

    .kit-empty-state h5 {
        font-weight: 800;
        color: #1e293b;
    }

    .kit-empty-state p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 20px;
    }

    @media (max-width: 767.98px) {
        .kit-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .kit-header-actions {
            width: 100%;
        }

        .kit-header-actions .btn {
            flex: 1;
        }

        .kit-hero {
            padding: 20px;
            gap: 13px;
        }

        .kit-hero-icon {
            width: 48px;
            height: 48px;
            flex-basis: 48px;
            border-radius: 12px;
            font-size: 23px;
        }

        .kit-hero h3 {
            font-size: 19px;
        }

        .kit-meta-list {
            flex-direction: column;
            align-items: flex-start;
            gap: 9px;
        }

        .kit-panel-header {
            padding: 18px;
        }

        .kit-table-footer {
            align-items: flex-start;
            flex-direction: column;
            padding: 15px;
        }

        .kit-bottom-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .kit-bottom-actions .btn {
            width: 100%;
        }
    }
</style>

@endsection
```