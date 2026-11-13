
@php
    use Illuminate\Support\Facades\Storage;

    // Get school settings
    $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::first();

    // School details
    $schoolName = $schoolSetting->school_name ?? 'GURUKUL VIDYALAY PALUS';

    $schoolAddress = collect([
        $schoolSetting->address ?? null,
        $schoolSetting->city ?? null,
        $schoolSetting->district ?? null,
        $schoolSetting->state ?? null,
        $schoolSetting->pincode ?? null,
    ])->filter()->implode(', ');

    $schoolPhone = $schoolSetting->phone ?? null;
    $schoolEmail = $schoolSetting->email ?? null;
    $schoolCode = $schoolSetting->school_code ?? null;
    $udiseCode = $schoolSetting->udise_code ?? null;

    // Fetch logo from School Settings
    $logoPath = $schoolSetting->logo ?? null;

    $logo = null;

    if (!empty($logoPath)) {
        if (\Illuminate\Support\Str::startsWith($logoPath, ['http://', 'https://'])) {
            $logo = $logoPath;
        } else {
            $logo = asset('storage/' . ltrim($logoPath, '/'));
        }
    }

    // Stock calculations
    $stocks = $stocks ?? collect();

    $totalItems = $stocks->count();

    $totalQuantity = $stocks->sum(function ($stock) {
        return (float) ($stock->quantity ?? 0);
    });

    $lowStockCount = $stocks->filter(function ($stock) {
        return (bool) $stock->status &&
            (float) ($stock->quantity ?? 0) <=
            (float) ($stock->minimum_quantity ?? 0);
    })->count();

    $availableStockCount = $stocks->filter(function ($stock) {
        return (bool) $stock->status &&
            (float) ($stock->quantity ?? 0) >
            (float) ($stock->minimum_quantity ?? 0);
    })->count();

    $academicYear = $academicYear ?? request('academic_year', date('Y') . '-' . (date('y') + 1));

    $printedAt = now()->format('d M Y, h:i A');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Supply Stock Report - {{ $schoolName }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #eeeeee;
            color: #222222;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .print-toolbar {
            max-width: 194mm;
            margin: 0 auto 15px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .toolbar-btn {
            border: 0;
            border-radius: 6px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .print-btn {
            background: #111111;
            color: #fff;
        }

        .back-btn {
            background: #fff;
            color: #222222;
            border: 1px solid #bbbbbb;
        }

        .print-page {
            position: relative;
            width: 194mm;
            min-height: 281mm;
            margin: auto;
            padding: 9mm;
            background: #fff;
            border: 2px solid #111111;
            outline: 1px solid #555555;
            outline-offset: -7px;
            overflow: hidden;
            box-shadow: 0 3px 18px rgba(0, 0, 0, .12);
        }

        /*
        |--------------------------------------------------------------------------
        | BLURRED SCHOOL LOGO WATERMARK
        |--------------------------------------------------------------------------
        */

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 125mm;
            height: 125mm;
            transform: translate(-50%, -50%);
            background-image: url('{{ $logo ?? '' }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: .10;
            filter: blur(2px);
            pointer-events: none;
            z-index: 0;
        }

        .print-content {
            position: relative;
            z-index: 1;
        }

        /*
        |--------------------------------------------------------------------------
        | SCHOOL HEADER
        |--------------------------------------------------------------------------
        */

        .school-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding: 8px 5px 14px;
            border-bottom: 2px solid #111111;
            text-align: center;
        }

        .school-logo {
            width: 85px;
            height: 85px;
            object-fit: contain;
            padding: 5px;
            border: 2px solid #111111;
            border-radius: 10px;
            background: #fff;
            flex-shrink: 0;
        }

        .school-details {
            flex: 1 1 auto;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .school-name {
            margin: 0 0 7px;
            color: #111111;
            font-size: 19px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .3px;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .school-address {
            margin: 3px 0;
            color: #444;
            font-size: 11px;
            line-height: 1.5;
        }

        .school-contact {
            margin-top: 4px;
            color: #444;
            font-size: 10px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | REPORT HEADING
        |--------------------------------------------------------------------------
        */

        .report-heading {
            margin: 18px 0 12px;
            text-align: center;
        }

        .report-heading h2 {
            margin: 0;
            color: #111111;
            font-size: 19px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .report-heading p {
            margin: 6px 0 0;
            color: #555;
            font-size: 11px;
        }

        .report-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            align-items: center;
            gap: 8px;
            margin: 14px 0;
            padding: 10px 12px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            background: rgba(245, 245, 245, .94);
        }

        .meta-item {
            min-width: 0;
            font-size: 10px;
            color: #333;
            overflow-wrap: anywhere;
        }

        .meta-item:nth-child(2) {
            text-align: center;
        }

        .meta-item:nth-child(3) {
            text-align: right;
        }

        .meta-item strong {
            color: #111111;
        }

        /*
        |--------------------------------------------------------------------------
        | SUMMARY CARDS
        |--------------------------------------------------------------------------
        */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 9px;
            margin: 18px 0 24px;
        }

        .summary-card {
            padding: 10px 5px;
            text-align: center;
            border: 1px solid #cccccc;
            border-radius: 6px;
            background: rgba(255, 255, 255, .94);
        }

        .summary-card .number {
            display: block;
            margin-bottom: 4px;
            color: #111111;
            font-size: 20px;
            font-weight: 800;
        }

        .summary-card .label {
            color: #555;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .summary-card.low .number {
            color: #111111;
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK TABLE
        |--------------------------------------------------------------------------
        */

        .section-title {
            margin: 18px 0 9px;
            color: #111111;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .stock-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            background: rgba(255, 255, 255, .94);
        }

        .stock-table th {
            padding: 9px 6px;
            color: #fff;
            background: #111111;
            border: 1px solid #111111;
            font-size: 10px;
            text-align: center;
            text-transform: uppercase;
        }

        .stock-table td {
            padding: 10px 5px;
            color: #333;
            border: 1px solid #bdbdbd;
            font-size: 10px;
            text-align: center;
        }

        .stock-table tbody tr:nth-child(even) {
            background: rgba(242, 242, 242, .92);
        }

        .stock-table td.item-name {
            text-align: left;
            font-weight: 700;
        }

        .status {
            display: inline-block;
            padding: 4px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-available {
            color: #222222;
            background: #eeeeee;
        }

        .status-low {
            color: #111111;
            background: #e5e5e5;
        }

        .status-inactive {
            color: #555;
            background: #eeeeee;
        }

        .empty-message {
            padding: 25px 10px !important;
            color: #777 !important;
            text-align: center !important;
        }

        /*
        |--------------------------------------------------------------------------
        | FOOTER AND SIGNATURES
        |--------------------------------------------------------------------------
        */

        .report-note {
            margin-top: 18px;
            padding: 10px 12px;
            border-left: 3px solid #111111;
            background: rgba(245, 245, 245, .94);
            color: #555;
            font-size: 10px;
            line-height: 1.5;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-top: 42px;
            padding: 0 18px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 40%;
            padding-top: 7px;
            border-top: 1px solid #444;
            color: #444;
            font-size: 10px;
            font-weight: 700;
            text-align: center;
        }

        .report-footer {
            margin-top: 36px;
            padding-top: 10px;
            border-top: 1px solid #bdbdbd;
            color: #666;
            font-size: 9px;
            text-align: center;
        }

        @media print {
            html,
            body {
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                color: #111 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-toolbar {
                display: none !important;
            }

            .print-page {
                width: 100% !important;
                max-width: 100% !important;
                min-height: 281mm !important;
                margin: 0 auto !important;
                padding: 8mm !important;
                border: 1.0px solid #111 !important;
                outline: 1px solid #555 !important;
                outline-offset: -5px !important;
                box-shadow: none !important;
                overflow: visible !important;
                background: #fff !important;
            }

            .print-content {
                position: relative !important;
                z-index: 1 !important;
            }

            .watermark {
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                opacity: .07 !important;
                filter: grayscale(1) blur(1px) !important;
                print-color-adjust: exact !important;
            }

            .school-header,
            .report-heading,
            .report-meta,
            .summary-grid,
            .section-title,
            .report-note,
            .signature-section,
            .report-footer {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .school-header {
                gap: 12px !important;
                width: 100% !important;
            }

            .school-logo {
                width: 72px !important;
                height: 72px !important;
            }

            .school-name {
                font-size: 18px !important;
                white-space: normal !important;
            }

            .stock-table {
                width: 100% !important;
                max-width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;
            }

            .stock-table thead {
                display: table-header-group;
            }

            .stock-table tfoot {
                display: table-footer-group;
            }

            .stock-table tr {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .stock-table th,
            .stock-table td {
                overflow-wrap: anywhere;
                word-break: normal;
            }

            .summary-card,
            .report-meta,
            .stock-table th,
            .stock-table tbody tr:nth-child(even) {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .signature-section {
                margin-top: 22px !important;
            }

            a {
                color: #111 !important;
                text-decoration: none !important;
            }
        }

        @media screen and (max-width: 700px) {
            body {
                padding: 8px;
            }

            .print-page {
                width: 100%;
                padding: 12px;
            }

            .school-header {
                flex-direction: column;
            }

            .school-name {
                font-size: 19px;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .report-meta {
                grid-template-columns: 1fr;
            }

            .meta-item,
            .meta-item:nth-child(2),
            .meta-item:nth-child(3) {
                text-align: left;
            }

            .stock-table th,
            .stock-table td {
                padding: 6px 3px;
                font-size: 9px;
            }
        }
    </style>
</head>

<body>

    {{-- PRINT TOOLBAR --}}
    <div class="print-toolbar">

        <button type="button"
                class="toolbar-btn print-btn"
                onclick="window.print()">
            Print Report
        </button>
    </div>

    <div class="print-page">

        {{-- BLURRED SCHOOL LOGO WATERMARK --}}
        @if($logo)
            <div class="watermark"></div>
        @endif

        <div class="print-content">

            {{-- SCHOOL HEADER --}}
            <div class="school-header">

                @if($logo)
                    <img src="{{ $logo }}"
                         alt="School Logo"
                         class="school-logo">
                @endif

                <div class="school-details">

                    <h1 class="school-name">
                        {{ $schoolName }}
                    </h1>

                    @if($schoolAddress)
                        <p class="school-address">
                            {{ $schoolAddress }}
                        </p>
                    @endif

                    <div class="school-contact">

                        @if($schoolPhone)
                            <span>Phone: {{ $schoolPhone }}</span>
                        @endif

                        @if($schoolPhone && $schoolEmail)
                            <span> | </span>
                        @endif

                        @if($schoolEmail)
                            <span>Email: {{ $schoolEmail }}</span>
                        @endif

                    </div>

                    <div class="school-contact">

                        @if($schoolCode)
                            <span>School Code: {{ $schoolCode }}</span>
                        @endif

                        @if($schoolCode && $udiseCode)
                            <span> | </span>
                        @endif

                        @if($udiseCode)
                            <span>UDISE Code: {{ $udiseCode }}</span>
                        @endif

                    </div>

                </div>

            </div>

            {{-- REPORT TITLE --}}
            <div class="report-heading">

                <h2>Supply Stock Report</h2>

                <p>
                    Complete overview of school supply items and available stock
                </p>

            </div>

            {{-- REPORT INFORMATION --}}
            <div class="report-meta">

                <div class="meta-item">
                    <strong>Academic Year:</strong>
                    {{ $academicYear }}
                </div>

                <div class="meta-item">
                    <strong>Report Date:</strong>
                    {{ now()->format('d M Y') }}
                </div>

                <div class="meta-item">
                    <strong>Printed At:</strong>
                    {{ now()->format('h:i A') }}
                </div>

            </div>

            {{-- STOCK SUMMARY --}}
            <div class="summary-grid">

                <div class="summary-card">
                    <span class="number">
                        {{ $totalItems }}
                    </span>
                    <span class="label">Total Items</span>
                </div>

                <div class="summary-card">
                    <span class="number">
                        {{ number_format($totalQuantity, 2) }}
                    </span>
                    <span class="label">Total Quantity</span>
                </div>

                <div class="summary-card">
                    <span class="number">
                        {{ $availableStockCount }}
                    </span>
                    <span class="label">Available Stock</span>
                </div>

                <div class="summary-card low">
                    <span class="number">
                        {{ $lowStockCount }}
                    </span>
                    <span class="label">Low Stock Items</span>
                </div>

            </div>

            {{-- STOCK TABLE --}}
            <h3 class="section-title">
                Supply Items Stock Details
            </h3>

            <table class="stock-table">

                <thead>
                    <tr>
                        <th style="width: 6%;">Sr. No.</th>
                        <th style="width: 25%;">Item Name</th>
                        <th style="width: 14%;">Item Code</th>
                        <th style="width: 10%;">Unit</th>
                        <th style="width: 13%;">Quantity</th>
                        <th style="width: 13%;">Minimum</th>
                        <th style="width: 19%;">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($stocks as $index => $stock)

                        @php
                            $item = $stock->supplyItem;

                            $isActive = (bool) $stock->status;

                            $quantity = (float) ($stock->quantity ?? 0);

                            $minimum = (float) ($stock->minimum_quantity ?? 0);

                            $isLowStock = $isActive && $quantity <= $minimum;
                        @endphp

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td class="item-name">
                                {{ $item->item_name ?? 'Deleted Item' }}
                            </td>

                            <td>
                                {{ $item->item_code ?? '-' }}
                            </td>

                            <td>
                                {{ $item->unit ?? '-' }}
                            </td>

                            <td>
                                {{ number_format($quantity, 2) }}
                            </td>

                            <td>
                                {{ number_format($minimum, 2) }}
                            </td>

                            <td>

                                @if(!$isActive)

                                    <span class="status status-inactive">
                                        Inactive
                                    </span>

                                @elseif($isLowStock)

                                    <span class="status status-low">
                                        Low Stock
                                    </span>

                                @else

                                    <span class="status status-available">
                                        Available
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="empty-message">
                                No supply stock records found for this academic year.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{-- REPORT NOTE --}}
            <div class="report-note">
                <strong>Note:</strong>
                This report displays the supply stock quantities recorded
                for the selected academic year. Items marked as Low Stock
                have quantities equal to or below their minimum stock level.
            </div>

            {{-- SIGNATURES --}}
            <div class="signature-section">

                <div class="signature-box">
                    Prepared By
                </div>

                <div class="signature-box">
                    Principal / Authorized Signature
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="report-footer">

                This is a computer-generated Supply Stock Report.

                <br>

                {{ $schoolName }}

                @if($schoolPhone)
                    | {{ $schoolPhone }}
                @endif

            </div>

        </div>

    </div>

</body>
</html>
