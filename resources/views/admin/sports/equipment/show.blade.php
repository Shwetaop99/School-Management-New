@extends('layouts.app')

@section('title', 'View Equipment | Admin')

@section('content')

<style>
    /* =========================================================
       SPORTS EQUIPMENT VIEW PAGE
    ========================================================= */

    .equipment-view-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f6f8fb;
        color: #172033;
        font-family: 'Inter', sans-serif;
        padding: 24px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .equipment-view-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .equipment-view-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .equipment-view-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        font-size: 19px;
        box-shadow: 0 6px 16px rgba(20, 124, 245, 0.20);
    }

    .equipment-view-breadcrumb {
        margin: 0 0 3px;
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
    }

    .equipment-view-title {
        margin: 0;
        font-size: 23px;
        line-height: 1.2;
        font-weight: 700;
        color: #172033;
    }

    .equipment-view-subtitle {
        margin: 5px 0 0;
        font-size: 13px;
        color: #718096;
    }

    /* =========================================================
       HEADER ACTIONS
    ========================================================= */

    .equipment-view-header-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .equipment-view-back-btn,
    .equipment-view-edit-btn {
        height: 40px;
        padding: 0 14px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .equipment-view-back-btn {
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .equipment-view-back-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #172033;
    }

    .equipment-view-edit-btn {
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        border: 1px solid #147cf5;
        box-shadow: 0 5px 14px rgba(20, 124, 245, 0.16);
    }

    .equipment-view-edit-btn:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(20, 124, 245, 0.22);
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .equipment-details-card {
        background: #ffffff;
        border: 1px solid #edf0f5;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    /* =========================================================
       CARD HEADER
    ========================================================= */

    .equipment-details-header {
        padding: 20px 22px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
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
        border-radius: 13px;
        background: #eaf3ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .equipment-main-name {
        margin: 0 0 4px;
        color: #172033;
        font-size: 18px;
        font-weight: 700;
    }

    .equipment-main-category {
        margin: 0;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 500;
    }

    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .equipment-view-status-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .equipment-view-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .equipment-view-badge-active {
        background: #eaf3ff;
        color: #147cf5;
    }

    .equipment-view-badge-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .equipment-view-badge-new {
        background: #eaf8ef;
        color: #239653;
    }

    .equipment-view-badge-good {
        background: #eaf8ef;
        color: #239653;
    }

    .equipment-view-badge-fair {
        background: #fff7e8;
        color: #d98b0b;
    }

    .equipment-view-badge-damaged {
        background: #fff0ee;
        color: #e65343;
    }

    /* =========================================================
       DETAILS BODY
    ========================================================= */

    .equipment-details-body {
        padding: 22px;
    }

    .equipment-section {
        margin-bottom: 25px;
    }

    .equipment-section:last-child {
        margin-bottom: 0;
    }

    .equipment-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 14px;
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .equipment-section-title i {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eaf3ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }

    /* =========================================================
       DETAIL GRID
    ========================================================= */

    .equipment-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .equipment-detail-item {
        padding: 13px 14px;
        border: 1px solid #edf0f5;
        background: #fafbfc;
        border-radius: 10px;
        min-width: 0;
    }

    .equipment-detail-label {
        margin-bottom: 6px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .equipment-detail-value {
        color: #172033;
        font-size: 12px;
        font-weight: 600;
        word-break: break-word;
    }

    .equipment-detail-value-muted {
        color: #94a3b8;
        font-weight: 500;
    }

    /* =========================================================
       STOCK SUMMARY
    ========================================================= */

    .equipment-stock-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .equipment-stock-card {
        padding: 15px;
        border-radius: 11px;
        border: 1px solid #edf0f5;
        background: #ffffff;
    }

    .equipment-stock-card-blue {
        background: #eaf3ff;
        border-color: #d8eaff;
    }

    .equipment-stock-card-cyan {
        background: #e8fbff;
        border-color: #d0f5fb;
    }

    .equipment-stock-card-orange {
        background: #fff5e3;
        border-color: #ffebc6;
    }

    .equipment-stock-label {
        margin-bottom: 7px;
        color: #64748b;
        font-size: 10px;
        font-weight: 600;
    }

    .equipment-stock-value {
        color: #172033;
        font-size: 21px;
        line-height: 1;
        font-weight: 700;
    }

    .equipment-stock-unit {
        margin-left: 4px;
        color: #718096;
        font-size: 10px;
        font-weight: 500;
    }

    /* =========================================================
       DESCRIPTION
    ========================================================= */

    .equipment-description {
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #edf0f5;
        background: #fafbfc;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
        white-space: pre-line;
    }

    /* =========================================================
       FOOTER ACTIONS
    ========================================================= */

    .equipment-details-footer {
        padding: 16px 22px;
        border-top: 1px solid #edf0f5;
        background: #fafbfc;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 992px) {

        .equipment-detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .equipment-stock-summary {
            grid-template-columns: repeat(3, minmax(0, 1fr));
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

        .equipment-view-back-btn,
        .equipment-view-edit-btn {
            flex: 1;
        }

        .equipment-details-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .equipment-view-status-group {
            justify-content: flex-start;
        }

        .equipment-detail-grid {
            grid-template-columns: 1fr;
        }

        .equipment-stock-summary {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {

        .equipment-view-title {
            font-size: 20px;
        }

        .equipment-view-heading {
            align-items: flex-start;
        }

        .equipment-details-body {
            padding: 16px;
        }

        .equipment-details-header {
            padding: 17px;
        }

        .equipment-details-footer {
            padding: 14px 16px;
        }

        .equipment-details-footer .equipment-view-back-btn,
        .equipment-details-footer .equipment-view-edit-btn {
            width: 100%;
        }

        .equipment-details-footer {
            flex-direction: column;
        }
    }
</style>


<div class="equipment-view-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="equipment-view-header">

        <div class="equipment-view-heading">

            <div class="equipment-view-icon">
                <i class="fas fa-dumbbell"></i>
            </div>

            <div>
                <div class="equipment-view-breadcrumb">
                    Sports Management / Equipment / View
                </div>

                <h1 class="equipment-view-title">
                    Equipment Details
                </h1>

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


    {{-- =====================================================
         MAIN DETAILS CARD
    ====================================================== --}}
    <div class="equipment-details-card">

        {{-- =================================================
             CARD HEADER
        ================================================== --}}
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


            {{-- STATUS --}}
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


        {{-- =================================================
             DETAILS BODY
        ================================================== --}}
        <div class="equipment-details-body">


            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-info-circle"></i>
                    Basic Information
                </h3>


                <div class="equipment-detail-grid">

                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Equipment Name
                        </div>

                        <div class="equipment-detail-value">
                            {{ $equipment->equipment_name }}
                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Category
                        </div>

                        <div class="equipment-detail-value">
                            {{ $equipment->category ?: '—' }}
                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Brand
                        </div>

                        <div class="equipment-detail-value">
                            {{ $equipment->brand ?: '—' }}
                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Model
                        </div>

                        <div class="equipment-detail-value">
                            {{ $equipment->model ?: '—' }}
                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Unit
                        </div>

                        <div class="equipment-detail-value">
                            {{ $equipment->unit ?: '—' }}
                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Equipment ID
                        </div>

                        <div class="equipment-detail-value">
                            #{{ $equipment->id }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 STOCK INFORMATION
            ================================================== --}}
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

                        <div class="equipment-stock-label">
                            Total Quantity
                        </div>

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

                        <div class="equipment-stock-label">
                            Available Quantity
                        </div>

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

                        <div class="equipment-stock-label">
                            Issued / Used
                        </div>

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


            {{-- =================================================
                 PURCHASE INFORMATION
            ================================================== --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-shopping-cart"></i>
                    Purchase Information
                </h3>


                <div class="equipment-detail-grid">

                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Purchase Price
                        </div>

                        <div class="equipment-detail-value">

                            @if($equipment->purchase_price !== null && $equipment->purchase_price !== '')
                                ₹{{ number_format((float) $equipment->purchase_price, 2) }}
                            @else
                                —
                            @endif

                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Purchase Date
                        </div>

                        <div class="equipment-detail-value">

                            @if($equipment->purchase_date)
                                {{ \Carbon\Carbon::parse($equipment->purchase_date)->format('d M Y') }}
                            @else
                                —
                            @endif

                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Supplier
                        </div>

                        <div class="equipment-detail-value">
                            {{ $equipment->supplier ?: '—' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 LOCATION & CONDITION
            ================================================== --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Location & Condition
                </h3>


                <div class="equipment-detail-grid">

                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Location
                        </div>

                        <div class="equipment-detail-value">
                            {{ $equipment->location ?: '—' }}
                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Condition
                        </div>

                        <div class="equipment-detail-value">
                            {{ ucfirst($equipment->condition ?: '—') }}
                        </div>

                    </div>


                    <div class="equipment-detail-item">

                        <div class="equipment-detail-label">
                            Status
                        </div>

                        <div class="equipment-detail-value">
                            {{ ucfirst($equipment->status ?: '—') }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 DESCRIPTION
            ================================================== --}}
            <div class="equipment-section">

                <h3 class="equipment-section-title">
                    <i class="fas fa-align-left"></i>
                    Description
                </h3>


                <div class="equipment-description">

                    @if($equipment->description)
                        {{ $equipment->description }}
                    @else
                        <span style="color: #94a3b8;">
                            No description has been added for this equipment.
                        </span>
                    @endif

                </div>

            </div>

        </div>


        {{-- =================================================
             FOOTER ACTIONS
        ================================================== --}}
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