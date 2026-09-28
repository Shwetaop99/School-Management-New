<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Meal Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
            color: #17365d;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            width: 16.66%;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .summary-title {
            display: block;
            color: #666;
            font-size: 9px;
            margin-bottom: 5px;
        }

        .summary-value {
            display: block;
            font-size: 15px;
            font-weight: bold;
            color: #17365d;
        }

        h2 {
            color: #17365d;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .report-table th {
            background: #f1f3f5;
            color: #17365d;
            font-weight: bold;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #ccc;
            padding: 7px;
            text-align: left;
        }

        .text-center {
            text-align: center !important;
        }

        .stock-in {
            color: #198754;
            font-weight: bold;
        }

        .stock-out {
            color: #dc3545;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>

<body>

    {{-- =========================
         SCHOOL HEADER
    ========================== --}}

    <table style="
        width: 100%;
        border-collapse: collapse;
        border-bottom: 2px solid #17365d;
        margin-bottom: 15px;
    ">
        <tr>

            {{-- SCHOOL LOGO --}}
            <td style="
                width: 75px;
                vertical-align: middle;
                padding-bottom: 10px;
            ">

                @if(file_exists(public_path('images/gurukullogo.png')))

                    <img
                        src="{{ public_path('images/gurukullogo.png') }}"
                        style="
                            width: 55px;
                            height: 55px;
                            object-fit: contain;
                        "
                    >

                @endif

            </td>


            {{-- SCHOOL NAME --}}
            <td style="
                text-align: center;
                vertical-align: middle;
                padding-bottom: 10px;
            ">

                <div style="
                    font-size: 20px;
                    font-weight: bold;
                    color: #17365d;
                    margin-bottom: 3px;
                ">
                    Gurukul Vidyalaya
                </div>

                <div style="
                    font-size: 9px;
                    color: #666;
                ">
                    School Management System
                </div>

            </td>


            {{-- BALANCE SPACE --}}
            <td style="
                width: 75px;
                padding-bottom: 10px;
            "></td>

        </tr>
    </table>


    {{-- REPORT TITLE --}}

    <h1>Meal Management Report</h1>

    <div class="subtitle">
        Meal stock, usage and inventory records
    </div>


    {{-- SUMMARY --}}

    <table class="summary">

        <tr>

            <td>
                <span class="summary-title">
                    Total Items
                </span>

                <span class="summary-value">
                    {{ $totalItems }}
                </span>
            </td>


            <td>
                <span class="summary-title">
                    Low Stock Items
                </span>

                <span class="summary-value">
                    {{ $lowStockItems }}
                </span>
            </td>


            <td>
                <span class="summary-title">
                    Current Stock
                </span>

                <span class="summary-value">
                    {{ number_format($totalCurrentStock, 2) }}
                </span>
            </td>


            <td>
                <span class="summary-title">
                    Total Stock In
                </span>

                <span class="summary-value">
                    {{ number_format($totalStockIn, 2) }}
                </span>
            </td>


            <td>
                <span class="summary-title">
                    Total Stock Out
                </span>

                <span class="summary-value">
                    {{ number_format($totalStockOut, 2) }}
                </span>
            </td>


            <td>
                <span class="summary-title">
                    Stock In Amount
                </span>

                <span class="summary-value">
                    ₹{{ number_format($totalStockInAmount, 2) }}
                </span>
            </td>

        </tr>

    </table>


    {{-- CURRENT STOCK --}}

    <h2>
        Current Meal Stock
    </h2>


    <table class="report-table">

        <thead>

            <tr>

                <th>Item</th>
                <th>Category</th>
                <th>Current Stock</th>
                <th>Unit</th>
                <th>Minimum Stock</th>
                <th>Status</th>

            </tr>

        </thead>


        <tbody>

            @forelse($items as $item)

                <tr>

                    <td>
                        {{ $item->item_name }}
                    </td>

                    <td>
                        {{ $item->category ?? '-' }}
                    </td>

                    <td>
                        {{ number_format($item->current_stock, 2) }}
                    </td>

                    <td>
                        {{ $item->unit }}
                    </td>

                    <td>
                        {{ number_format($item->minimum_stock, 2) }}
                    </td>

                    <td>
                        {{ $item->status ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">
                        No meal items found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- TRANSACTIONS --}}

    <h2>
        Stock Transactions
    </h2>


    <table class="report-table">

        <thead>

            <tr>

                <th>Date</th>
                <th>Item</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Rate</th>
                <th>Total Amount</th>
                <th>Supplier</th>

            </tr>

        </thead>


        <tbody>

            @forelse($transactions as $transaction)

                <tr>

                    <td>
                        {{ optional($transaction->transaction_date)->format('d-m-Y') }}
                    </td>

                    <td>
                        {{ $transaction->mealItem?->item_name ?? '-' }}
                    </td>

                    <td>

                        @if($transaction->transaction_type === 'stock_in')

                            <span class="stock-in">
                                Stock In
                            </span>

                        @else

                            <span class="stock-out">
                                Stock Out
                            </span>

                        @endif

                    </td>

                    <td>
                        {{ number_format($transaction->quantity, 2) }}
                    </td>

                    <td>
                        {{ $transaction->unit ?? '-' }}
                    </td>

                    <td>
                        ₹{{ number_format($transaction->rate ?? 0, 2) }}
                    </td>

                    <td>
                        ₹{{ number_format($transaction->total_amount ?? 0, 2) }}
                    </td>

                    <td>
                        {{ $transaction->supplier ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="text-center">
                        No transactions found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- FOOTER --}}

    <div class="footer">
        Generated from School Management System
    </div>

</body>
</html>