
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $reportTitle ?? 'Meal Report' }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #263238;
        }

        .school-header {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #17365d;
            margin-bottom: 16px;
        }

        .school-header td {
            padding: 8px;
            vertical-align: middle;
        }

        .logo-cell {
            width: 65px;
            text-align: center;
        }

        .logo {
            width: 52px;
            height: 52px;
        }

        .school-name {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #17365d;
        }

        .school-subtitle {
            margin-top: 4px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }

        h1 {
            text-align: center;
            margin: 14px 0 5px;
            color: #17365d;
            font-size: 17px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 15px;
            color: #64748b;
            font-size: 9px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary td {
            width: 33.33%;
            border: 1px solid #dbe2ea;
            padding: 10px;
            text-align: center;
        }

        .summary-label {
            display: block;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 9px;
        }

        .summary-value {
            display: block;
            color: #17365d;
            font-size: 15px;
            font-weight: bold;
        }

        h2 {
            color: #17365d;
            font-size: 12px;
            margin: 15px 0 8px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .report-table th {
            padding: 7px 6px;
            background: #eaf0f7;
            color: #17365d;
            border: 1px solid #cbd5e1;
            font-weight: bold;
            text-align: left;
        }

        .report-table td {
            padding: 6px;
            border: 1px solid #d7dee7;
            vertical-align: top;
        }

        .stock-in {
            color: #15803d;
            font-weight: bold;
        }

        .stock-out {
            color: #b91c1c;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 18px;
            color: #64748b;
        }

        .meta {
            margin: 8px 0 12px;
            color: #64748b;
            font-size: 9px;
        }

        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #d7dee7;
            color: #64748b;
            font-size: 8px;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- SCHOOL HEADER --}}
    <table class="school-header">
        <tr>
            <td class="logo-cell">
                @if(file_exists(public_path('images/gurukullogo.png')))
                    <img
                        class="logo"
                        src="{{ public_path('images/gurukullogo.png') }}"
                    >
                @endif
            </td>

            <td>
                <div class="school-name">Gurukul Vidyalaya</div>
                <div class="school-subtitle">School Management System</div>
            </td>

            <td style="width:65px;"></td>
        </tr>
    </table>

    <h1>{{ $reportTitle ?? 'Meal Report' }}</h1>

    <div class="subtitle">
        Meal inventory and stock history report
    </div>

    <div class="meta">
        Generated: {{ now()->format('d M Y, h:i A') }}
        &nbsp; | &nbsp;
        @if(request('from_date'))
            From: {{ request('from_date') }}
        @endif
        @if(request('to_date'))
            &nbsp; To: {{ request('to_date') }}
        @endif
    </div>

    {{-- SUMMARY --}}
    <table class="summary">
        <tr>
            <td>
                <span class="summary-label">Total Records</span>
                <span class="summary-value">
                    {{ number_format($totalRecords ?? 0) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Total Quantity</span>
                <span class="summary-value">
                    {{ number_format((float) ($totalQuantity ?? 0), 2) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Transaction Amount</span>
                <span class="summary-value">
                    &#8377;{{ number_format((float) ($totalAmount ?? 0), 2) }}
                </span>
            </td>
        </tr>
    </table>

    {{-- MEAL LOGS --}}
    @if(($type ?? '') === 'logs')

        <h2>Meal Stock History</h2>

        <table class="report-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date &amp; Time</th>
                    <th>Meal Item</th>
                    <th>Action</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Previous Stock</th>
                    <th>Updated Stock</th>
                    <th>Performed By</th>
                    <th>Reason / Remarks</th>
                </tr>
            </thead>

            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $log->created_at ? $log->created_at->format('d M Y, h:i A') : '-' }}
                        </td>

                        <td>{{ $log->mealItem?->item_name ?? '-' }}</td>

                        <td class="{{ $log->action === 'stock_in' ? 'stock-in' : 'stock-out' }}">
                            {{ \Illuminate\Support\Str::headline($log->action ?? 'unknown') }}
                        </td>

                        <td>{{ number_format((float) $log->quantity, 2) }}</td>
                        <td>{{ $log->unit ?? '-' }}</td>

                        <td>
                            {{ number_format((float) $log->previous_stock, 2) }}
                        </td>

                        <td>
                            {{ number_format((float) $log->updated_stock, 2) }}
                        </td>

                        <td>{{ $log->performed_by ?? '-' }}</td>

                        <td>
                            {{ $log->reason ?? '-' }}
                            @if($log->remarks)
                                <br>{{ $log->remarks }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="empty">
                            No meal logs found for the selected filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    {{-- STOCK IN / STOCK OUT --}}
    @else

        <h2>
            {{ ($type ?? '') === 'stock_in' ? 'Incoming Stock Transactions' : 'Outgoing Stock Transactions' }}
        </h2>

        <table class="report-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Transaction Date</th>
                    <th>Meal Item</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Total Amount</th>
                    <th>Supplier</th>
                    <th>Reason</th>
                    <th>Remarks</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $transaction->transaction_date?->format('d M Y') ?? '-' }}
                        </td>

                        <td>{{ $transaction->mealItem?->item_name ?? '-' }}</td>

                        <td>
                            {{ number_format((float) $transaction->quantity, 2) }}
                        </td>

                        <td>{{ $transaction->unit ?? '-' }}</td>

                        <td>
                            &#8377;{{ number_format((float) ($transaction->rate ?? 0), 2) }}
                        </td>

                        <td>
                            &#8377;{{ number_format((float) ($transaction->total_amount ?? 0), 2) }}
                        </td>

                        <td>{{ $transaction->supplier ?? '-' }}</td>
                        <td>{{ $transaction->reason ?? '-' }}</td>
                        <td>{{ $transaction->remarks ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="empty">
                            No transactions found for the selected filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @endif

    <div class="footer">
        Generated from School Management System
    </div>

</body>
</html>
