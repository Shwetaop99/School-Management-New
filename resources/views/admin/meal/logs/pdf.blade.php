<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Stock Movement History</title>

    <style>
        @page {
            margin: 20px 15px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0 0 4px;
            font-size: 16px;
            color: #147cf5;
        }

        .header p {
            margin: 2px 0;
            color: #6b7280;
            font-size: 8px;
        }

        table.logs-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .logs-table th {
            background: #147cf5;
            color: #ffffff;
            border: 1px solid #147cf5;
            padding: 6px 3px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
        }

        .logs-table td {
            border: 1px solid #dfe3e8;
            padding: 5px 3px;
            font-size: 7px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .logs-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .stock-in {
            color: #159a65;
            font-weight: bold;
        }

        .stock-out {
            color: #f65343;
            font-weight: bold;
        }

        .transaction-id {
            font-weight: bold;
            color: #374151;
        }

        .item-name {
            font-weight: bold;
            color: #111827;
        }

        .footer {
            margin-top: 12px;
            padding-top: 7px;
            border-top: 1px solid #e5e7eb;
            text-align: right;
            color: #6b7280;
            font-size: 7px;
        }
    </style>
</head>

<body>

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="header">

        <h1>
            Stock Movement History
        </h1>

        <p>
            School Management System
        </p>

        <p>
            Generated on:
            {{ now()->format('d M Y, h:i A') }}
        </p>

    </div>


    {{-- =========================================================
         STOCK MOVEMENT HISTORY
         ========================================================= --}}

    <table class="logs-table">

        <thead>

            <tr>

                <th style="width: 3%;">
                    #
                </th>

                <th style="width: 9%;">
                    Date
                </th>

                <th style="width: 9%;">
                    Transaction ID
                </th>

                <th style="width: 11%;">
                    Item
                </th>

                <th style="width: 8%;">
                    Category
                </th>

                <th style="width: 7%;">
                    Type
                </th>

                <th style="width: 7%;">
                    Quantity
                </th>

                <th style="width: 7%;">
                    Unit
                </th>

                <th style="width: 9%;">
                    Stock Before
                </th>

                <th style="width: 9%;">
                    Stock After
                </th>

                <th style="width: 9%;">
                    Reason
                </th>

                <th style="width: 7%;">
                    Performed By
                </th>

                <th style="width: 5%;">
                    Remarks
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($logs as $index => $log)

                <tr>

                    {{-- # --}}
                    <td class="center">
                        {{ $index + 1 }}
                    </td>


                    {{-- DATE --}}
                    <td class="center">

                        @if($log->stockTransaction?->transaction_date)

                            {{ $log->stockTransaction->transaction_date->format('d-m-Y') }}

                        @else

                            {{ optional($log->created_at)->format('d-m-Y') }}

                        @endif

                    </td>


                    {{-- TRANSACTION ID --}}
                    <td class="center transaction-id">

                        @if($log->stock_transaction_id)

                            TRX-{{ str_pad(
                                $log->stock_transaction_id,
                                6,
                                '0',
                                STR_PAD_LEFT
                            ) }}

                        @else

                            -

                        @endif

                    </td>


                    {{-- ITEM --}}
                    <td class="item-name">

                        {{ $log->mealItem?->item_name ?? '-' }}

                    </td>


                    {{-- CATEGORY --}}
                    <td>

                        {{ $log->mealItem?->category ?? '-' }}

                    </td>


                    {{-- TYPE --}}
                    <td class="center">

                        @if($log->action === 'stock_in')

                            <span class="stock-in">
                                Stock In
                            </span>

                        @else

                            <span class="stock-out">
                                Stock Out
                            </span>

                        @endif

                    </td>


                    {{-- QUANTITY --}}
                    <td class="right">

                        {{ number_format((float) $log->quantity, 2) }}

                    </td>


                    {{-- UNIT --}}
                    <td class="center">

                        {{ $log->unit ?? '-' }}

                    </td>


                    {{-- STOCK BEFORE --}}
                    <td class="right">

                        {{ number_format(
                            (float) $log->previous_stock,
                            2
                        ) }}

                    </td>


                    {{-- STOCK AFTER --}}
                    <td class="right">

                        {{ number_format(
                            (float) $log->updated_stock,
                            2
                        ) }}

                    </td>


                    {{-- REASON --}}
                    <td>

                        {{ $log->reason ?? '-' }}

                    </td>


                    {{-- PERFORMED BY --}}
                    <td>

                        {{ $log->performed_by ?? '-' }}

                    </td>


                    {{-- REMARKS --}}
                    <td>

                        {{ $log->remarks ?? '-' }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="13"
                        class="center"
                        style="padding: 20px;"
                    >
                        No stock movement records found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
         FOOTER
         ========================================================= --}}

    <div class="footer">

        Stock Movement History

        &nbsp; | &nbsp;

        Generated
        {{ now()->format('d-m-Y h:i A') }}

    </div>

</body>
</html>