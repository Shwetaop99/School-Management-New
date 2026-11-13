@extends('layouts.app')

@section('title', 'View Equipment | Admin')
@section('page-title', 'Equipment Details')

@section('content')

<style>
/* =========================================================
   SPORTS EQUIPMENT VIEW
   SCHOOL DIARY DESIGN SYSTEM
========================================================= */

.equipment-view-page {
    --eq-ink: #263b35;
    --eq-ink-dark: #1d302b;
    --eq-muted: #849087;
    --eq-green: #34775a;
    --eq-green-dark: #245a43;
    --eq-green-soft: #eaf3e9;
    --eq-paper: #fffefa;
    --eq-page: #f5f3e9;
    --eq-border: #e5e1d3;
    --eq-brown: #a87543;
    --eq-brown-soft: #f7eee3;
    --eq-red: #bd5148;
    --eq-red-soft: #fff0ec;
    --eq-cyan: #527f79;

    width: 100%;
    min-height: calc(100vh - 60px);
    padding: 26px;
    background: var(--eq-page);
    color: var(--eq-ink);
    font-family: 'Inter', sans-serif;
}

/* PAGE HEADER */

.equipment-view-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.equipment-view-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.equipment-view-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e2d2;
    border-radius: 14px;
    background: var(--eq-green-soft);
    color: var(--eq-green);
    font-size: 20px;
}

.equipment-view-breadcrumb {
    margin: 0 0 5px;
    color: var(--eq-muted);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .04em;
}

.equipment-view-title {
    margin: 0;
    color: var(--eq-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 28px;
    line-height: 1.2;
    font-weight: 700;
}

.equipment-view-subtitle {
    margin: 6px 0 0;
    color: var(--eq-muted);
    font-size: 12px;
    line-height: 1.6;
}

/* HEADER ACTIONS */

.equipment-view-header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-shrink: 0;
}

.equipment-view-page .equipment-view-back-btn,
.equipment-view-page .equipment-view-edit-btn {
    min-height: 40px;
    padding: 0 15px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition: all .2s ease;
    white-space: nowrap;
}

.equipment-view-page .equipment-view-back-btn {
    background: var(--eq-paper);
    color: var(--eq-ink);
    border: 1px solid var(--eq-border);
}

.equipment-view-page .equipment-view-back-btn:hover {
    background: #f0eee3;
    border-color: #c9c5b5;
    color: var(--eq-ink-dark);
}

.equipment-view-page .equipment-view-edit-btn {
    background: var(--eq-green);
    color: #fff;
    border: 1px solid var(--eq-green);
    box-shadow: 0 3px 8px rgba(52, 119, 90, .12);
}

.equipment-view-page .equipment-view-edit-btn:hover {
    background: var(--eq-green-dark);
    border-color: var(--eq-green-dark);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 5px 12px rgba(52, 119, 90, .18);
}

/* MAIN DETAILS CARD */

.equipment-details-card {
    background: var(--eq-paper);
    border: 1px solid var(--eq-border);
    border-radius: 13px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
    overflow: hidden;
}

/* CARD HEADER */

.equipment-details-header {
    position: relative;
    padding: 22px 24px;
    border-bottom: 1px solid var(--eq-border);
    background: #fcfbf5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.equipment-details-header::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 4px;
    background: var(--eq-green);
}

.equipment-main-info {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.equipment-main-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border: 1px solid #d8e5d5;
    border-radius: 12px;
    background: var(--eq-green-soft);
    color: var(--eq-green);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}

.equipment-main-name {
    margin: 0 0 5px;
    color: var(--eq-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 20px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.equipment-main-category {
    margin: 0;
    color: var(--eq-muted);
    font-size: 11px;
    font-weight: 500;
    line-height: 1.6;
}

/* STATUS BADGES */

.equipment-view-status-group {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px;
}

.equipment-view-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid transparent;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.equipment-view-badge-active,
.equipment-view-badge-new,
.equipment-view-badge-good {
    background: var(--eq-green-soft);
    border-color: #d6e6d2;
    color: var(--eq-green-dark);
}

.equipment-view-badge-inactive {
    background: #f0eee7;
    border-color: #e2ded2;
    color: #77796f;
}

.equipment-view-badge-fair {
    background: var(--eq-brown-soft);
    border-color: #ead9c3;
    color: var(--eq-brown);
}

.equipment-view-badge-damaged {
    background: var(--eq-red-soft);
    border-color: #f1d4cd;
    color: var(--eq-red);
}

/* DETAILS BODY */

.equipment-details-body {
    padding: 25px;
}

.equipment-section {
    margin-bottom: 27px;
}

.equipment-section:last-child {
    margin-bottom: 0;
}

.equipment-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 15px;
    padding-bottom: 11px;
    border-bottom: 1px dashed var(--eq-border);
    color: var(--eq-ink-dark);
    font-size: 13px;
    font-weight: 800;
}

.equipment-section-title > i {
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border: 1px solid #d9e5d5;
    border-radius: 8px;
    background: var(--eq-green-soft);
    color: var(--eq-green);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

/* DETAIL GRID */

.equipment-detail-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}

.equipment-detail-item {
    min-width: 0;
    padding: 14px 15px;
    border: 1px solid var(--eq-border);
    border-radius: 9px;
    background: #fcfbf6;
    transition: border-color .2s ease, background .2s ease;
}

.equipment-detail-item:hover {
    border-color: #cbdac7;
    background: #f9faf2;
}

.equipment-detail-label {
    margin-bottom: 7px;
    color: var(--eq-muted);
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
}

.equipment-detail-value {
    color: var(--eq-ink);
    font-size: 12px;
    font-weight: 700;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.equipment-detail-value-muted {
    color: var(--eq-muted);
    font-weight: 500;
}

/* STOCK SUMMARY */

.equipment-stock-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 13px;
}

.equipment-stock-card {
    position: relative;
    min-width: 0;
    padding: 18px;
    border: 1px solid var(--eq-border);
    border-radius: 10px;
    background: var(--eq-paper);
    overflow: hidden;
}

.equipment-stock-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--eq-green);
}

.equipment-stock-card-blue {
    background: #f0f5ec;
    border-color: #dce7d6;
}

.equipment-stock-card-blue::before {
    background: var(--eq-green);
}

.equipment-stock-card-cyan {
    background: #eff5ef;
    border-color: #d9e7dc;
}

.equipment-stock-card-cyan::before {
    background: var(--eq-cyan);
}

.equipment-stock-card-orange {
    background: #f9f1e7;
    border-color: #ecdfcc;
}

.equipment-stock-card-orange::before {
    background: var(--eq-brown);
}

.equipment-stock-label {
    margin-bottom: 9px;
    color: #727f74;
    font-size: 10px;
    font-weight: 700;
}

.equipment-stock-value {
    color: var(--eq-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 27px;
    line-height: 1.2;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.equipment-stock-unit {
    margin-left: 4px;
    color: var(--eq-muted);
    font-family: 'Inter', sans-serif;
    font-size: 10px;
    font-weight: 600;
}

/* DESCRIPTION */

.equipment-description {
    padding: 16px 17px;
    border: 1px solid var(--eq-border);
    border-left: 3px solid #b8cdb0;
    border-radius: 8px;
    background: #fcfbf6;
    color: #5f7065;
    font-size: 12px;
    line-height: 1.8;
    white-space: pre-line;
    overflow-wrap: anywhere;
}

/* FOOTER ACTIONS */

.equipment-details-footer {
    padding: 17px 24px;
    border-top: 1px solid var(--eq-border);
    background: #f9f7ef;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
}

/* RESPONSIVE */

@media (max-width: 992px) {
    .equipment-view-page {
        padding: 21px;
    }

    .equipment-detail-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .equipment-details-body {
        padding: 21px;
    }
}

@media (max-width: 768px) {
    .equipment-view-page {
        padding: 16px;
    }

    .equipment-view-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .equipment-view-header-actions {
        width: 100%;
    }

    .equipment-view-header-actions .equipment-view-back-btn,
    .equipment-view-header-actions .equipment-view-edit-btn {
        flex: 1;
    }

    .equipment-details-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 20px;
    }

    .equipment-view-status-group {
        justify-content: flex-start;
    }

    .equipment-stock-summary {
        grid-template-columns: 1fr;
    }

    .equipment-stock-card {
        padding: 16px;
    }

    .equipment-details-body {
        padding: 18px;
    }
}

@media (max-width: 576px) {
    .equipment-view-title {
        font-size: 23px;
    }

    .equipment-view-heading {
        align-items: flex-start;
    }

    .equipment-view-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
    }

    .equipment-view-subtitle {
        font-size: 11px;
    }

    .equipment-detail-grid {
        grid-template-columns: 1fr;
    }

    .equipment-main-name {
        font-size: 18px;
    }

    .equipment-details-header {
        padding: 17px;
    }

    .equipment-details-body {
        padding: 16px;
    }

    .equipment-details-footer {
        padding: 15px 16px;
        flex-direction: column;
        align-items: stretch;
    }

    .equipment-details-footer .equipment-view-back-btn,
    .equipment-details-footer .equipment-view-edit-btn {
        width: 100%;
    }
}
</style>

<div class="equipment-view-page">

    {{-- PAGE HEADER --}}
    <div class="equipment-view-header">

        <div class="equipment-view-heading">
            <div class="equipment-view-icon">
                <i class="fas fa-dumbbell"></i>
            </div>

            <div>
                <div class="equipment-view-breadcrumb">
                    Sports Management / Equipment / View
                </div>

                <h1 class="equipment-view-title">Equipment Details</h1>

                <p class="equipment-view-subtitle">
                    View complete information about this sports equipment.
                </p>
            </div>
        </div>

        <div class="equipment-view-header-actions">
            <a
                href="{{ route('admin.sports.equipment.index') }}"
                class="equipment-view-back-btn"
            >
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            <a
                href="{{ route('admin.sports.equipment.edit', $equipment->id) }}"
                class="equipment-view-edit-btn"
            >
                <i class="fas fa-edit"></i>
                Edit Equipment
            </a>
        </div>

    </div>

    {{-- MAIN DETAILS CARD --}}
    <div class="equipment-details-card">

        {{-- CARD HEADER --}}
        <div class="equipment-details-header">

            <div class="equipment-main-info">
                <div class="equipment-main-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>

                <div>
                    <h2 class="equipment-main-name">
                        {{ $equipment->equipment_name }}
                    </h2>

                    <p class="equipment-main-category">
                        {{ $equipment->category ?: 'Sports Equipment' }}

                        @if($equipment->brand)
                            · {{ $equipment->brand }}
                        @endif

                        @if($equipment->model)
                            · {{ $equipment->model }}
                        @endif
                    </p>
                </div>
            </div>

            {{-- CONDITION AND STATUS BADGES --}}
            <div class="equipment-view-status-group">

                @if($equipment->condition === 'new')
                    <span class="equipment-view-badge equipment-view-badge-new">
                        <i class="fas fa-star"></i>
                        New
                    </span>
                @elseif($equipment->condition === 'good')
                    <span class="equipment-view-badge equipment-view-badge-good">
                        <i class="fas fa-check-circle"></i>
                        Good
                    </span>
                @elseif($equipment->condition === 'fair')
                    <span class="equipment-view-badge equipment-view-badge-fair">
                        <i class="fas fa-minus-circle"></i>
                        Fair
                    </span>
                @elseif($equipment->condition === 'damaged')
                    <span class="equipment-view-badge equipment-view-badge-damaged">
                        <i class="fas fa-exclamation-circle"></i>
                        Damaged
                    </span>
                @endif

                @if($equipment->status === 'active')
                    <span class="equipment-view-badge equipment-view-badge-active">
                        <i class="fas fa-circle" style="font-size: 5px;"></i>
                        Active
                    </span>
                @else
                    <span class="equipment-view-badge equipment-view-badge-inactive">
                        <i class="fas fa-circle" style="font-size: 5px;"></i>
                        Inactive
                    </span>
                @endif

            </div>

        </div>

        {{-- DETAILS BODY --}}
        <div class="equipment-details-body">

            {{-- BASIC INFORMATION --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-info-circle"></i>
                    Basic Information
                </h3>

                <div class="equipment-detail-grid">

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Equipment Name</div>
                        <div class="equipment-detail-value">
                            {{ $equipment->equipment_name }}
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Category</div>
                        <div class="equipment-detail-value">
                            {{ $equipment->category ?: '—' }}
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Brand</div>
                        <div class="equipment-detail-value">
                            {{ $equipment->brand ?: '—' }}
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Model</div>
                        <div class="equipment-detail-value">
                            {{ $equipment->model ?: '—' }}
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Unit</div>
                        <div class="equipment-detail-value">
                            {{ $equipment->unit ?: '—' }}
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Equipment ID</div>
                        <div class="equipment-detail-value">
                            #{{ $equipment->id }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- STOCK INFORMATION --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-boxes"></i>
                    Stock Information
                </h3>

                @php
                    $totalQuantity = (int) ($equipment->quantity ?? 0);
                    $availableQuantity = (int) ($equipment->available_quantity ?? 0);
                    $issuedQuantity = max(0, $totalQuantity - $availableQuantity);
                @endphp

                <div class="equipment-stock-summary">

                    <div class="equipment-stock-card equipment-stock-card-blue">
                        <div class="equipment-stock-label">Total Quantity</div>
                        <div class="equipment-stock-value">
                            {{ $totalQuantity }}

                            @if($equipment->unit)
                                <span class="equipment-stock-unit">
                                    {{ $equipment->unit }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="equipment-stock-card equipment-stock-card-cyan">
                        <div class="equipment-stock-label">Available Quantity</div>
                        <div class="equipment-stock-value">
                            {{ $availableQuantity }}

                            @if($equipment->unit)
                                <span class="equipment-stock-unit">
                                    {{ $equipment->unit }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="equipment-stock-card equipment-stock-card-orange">
                        <div class="equipment-stock-label">Issued / Used</div>
                        <div class="equipment-stock-value">
                            {{ $issuedQuantity }}

                            @if($equipment->unit)
                                <span class="equipment-stock-unit">
                                    {{ $equipment->unit }}
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- PURCHASE INFORMATION --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-shopping-cart"></i>
                    Purchase Information
                </h3>

                <div class="equipment-detail-grid">

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Purchase Price</div>
                        <div class="equipment-detail-value">
                            @if($equipment->purchase_price !== null && $equipment->purchase_price !== '')
                                ₹{{ number_format((float) $equipment->purchase_price, 2) }}
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Purchase Date</div>
                        <div class="equipment-detail-value">
                            @if($equipment->purchase_date)
                                {{ \Carbon\Carbon::parse($equipment->purchase_date)->format('d M Y') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Supplier</div>
                        <div class="equipment-detail-value">
                            {{ $equipment->supplier ?: '—' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- LOCATION AND CONDITION --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Location &amp; Condition
                </h3>

                <div class="equipment-detail-grid">

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Location</div>
                        <div class="equipment-detail-value">
                            {{ $equipment->location ?: '—' }}
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Condition</div>
                        <div class="equipment-detail-value">
                            {{ ucfirst($equipment->condition ?: '—') }}
                        </div>
                    </div>

                    <div class="equipment-detail-item">
                        <div class="equipment-detail-label">Status</div>
                        <div class="equipment-detail-value">
                            {{ ucfirst($equipment->status ?: '—') }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-align-left"></i>
                    Description
                </h3>

                <div class="equipment-description">
                    @if($equipment->description)
                        {{ $equipment->description }}
                    @else
                        <span style="color: #849087;">
                            No description has been added for this equipment.
                        </span>
                    @endif
                </div>

            </div>

        </div>

        {{-- FOOTER ACTIONS --}}
        <div class="equipment-details-footer">

            <a
                href="{{ route('admin.sports.equipment.index') }}"
                class="equipment-view-back-btn"
            >
                <i class="fas fa-arrow-left"></i>
                Back to Equipment
            </a>

            <a
                href="{{ route('admin.sports.equipment.edit', $equipment->id) }}"
                class="equipment-view-edit-btn"
            >
                <i class="fas fa-edit"></i>
                Edit Equipment
            </a>

        </div>

    </div>

</div>

@endsection
