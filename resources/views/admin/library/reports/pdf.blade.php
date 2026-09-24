<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Library Report</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #172033;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #172033;
        }

        .header p {
            margin: 5px 0 0;
            color: #667085;
            font-size: 11px;
        }

        .summary {
            width: 100%;
            border: 1px solid #d9dee7;
            background: #f8faff;
            padding: 12px;
            margin-bottom: 18px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            width: 25%;
            padding: 8px;
            text-align: center;
            border-right: 1px solid #e1e5ec;
        }

        .summary td:last-child {
            border-right: none;
        }

        .summary-title {
            font-size: 9px;
            color: #667085;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #172033;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            margin: 18px 0 8px;
            color: #172033;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        table.report-table th {
            background: #eef2f7;
            color: #172033;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #d5dae2;
            text-align: left;
        }

        table.report-table td {
            padding: 7px;
            border: 1px solid #d5dae2;
            vertical-align: middle;
        }

        table.stock-table th,
        table.stock-table td {
            font-size: 10px;
        }

        .stock-total td {
            background: #f8faff;
            font-weight: bold;
            border-top: 2px solid #bfc7d4;
        }

        .number {
            text-align: center;
        }

        .status-returned {
            color: #138a4b;
            font-weight: bold;
        }

        .status-issued {
            color: #2563eb;
            font-weight: bold;
        }

        .status-overdue {
            color: #dc2626;
            font-weight: bold;
        }

        .fine {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
            color: #667085;
            font-size: 10px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Gurukul Vidyalaya</h1>
        <p>Library Management System — Library Report</p>
        <p>
            Generated on:
            {{ now()->format('d M Y, h:i A') }}
        </p>
    </div>


    {{-- SUMMARY --}}
    <div class="summary">

        <table>
            <tr>

                <td>
                    <div class="summary-title">Total Books</div>
                    <div class="summary-value">
                        {{ $bookStock->count() }}
                    </div>
                </td>

                <td>
                    <div class="summary-title">Total Book Stock</div>
                    <div class="summary-value">
                        {{ $bookStock->sum('quantity') }}
                    </div>
                </td>

                <td>
                    <div class="summary-title">Available Copies</div>
                    <div class="summary-value">
                        {{ $bookStock->sum('available_quantity') }}
                    </div>
                </td>

                <td>
                    <div class="summary-title">Currently Issued</div>
                    <div class="summary-value">
                        {{ $bookStock->sum('quantity') - $bookStock->sum('available_quantity') }}
                    </div>
                </td>

            </tr>

            <tr>

                <td>
                    <div class="summary-title">Returned Books</div>
                    <div class="summary-value">
                        {{ $transactions->whereNotNull('return_date')->count() }}
                    </div>
                </td>

                <td>
                    <div class="summary-title">Overdue Books</div>
                    <div class="summary-value">
                        {{ $transactions->filter(function ($issue) {
                            return !$issue->return_date &&
                                   $issue->due_date &&
                                   $issue->due_date->isPast();
                        })->count() }}
                    </div>
                </td>

                <td>
                    <div class="summary-title">Total Fines</div>
                    <div class="summary-value">
                        ₹{{ number_format($transactions->sum('fine'), 2) }}
                    </div>
                </td>

                <td>
                    <div class="summary-title">Pending Fines</div>
                    <div class="summary-value">
                        ₹{{ number_format(
                            $transactions
                                ->filter(function ($issue) {
                                    return ($issue->fine ?? 0) > 0 &&
                                           (($issue->fine_status ?? 'Pending') === 'Pending');
                                })
                                ->sum('fine'),
                            2
                        ) }}
                    </div>
                </td>

            </tr>
        </table>

    </div>


    {{-- BOOK STOCK --}}
    <div class="section-title">
        Book Stock
    </div>

    <table class="report-table stock-table">

        <thead>
            <tr>
                <th>Book</th>
                <th>Author</th>
                <th class="number">Total Copies</th>
                <th class="number">Available</th>
                <th class="number">Issued</th>
            </tr>
        </thead>

        <tbody>

            @forelse($bookStock as $book)

                @php
                    $issued = $book->quantity - $book->available_quantity;
                @endphp

                <tr>

                    <td>
                        <strong>{{ $book->title }}</strong>
                    </td>

                    <td>
                        {{ $book->author }}
                    </td>

                    <td class="number">
                        {{ $book->quantity }}
                    </td>

                    <td class="number">
                        {{ $book->available_quantity }}
                    </td>

                    <td class="number">
                        {{ $issued }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" style="text-align:center;">
                        No books found.
                    </td>
                </tr>

            @endforelse

        </tbody>

        @if($bookStock->count())

            <tfoot>

                <tr class="stock-total">

                    <td colspan="2">
                        Total Book Stock
                    </td>

                    <td class="number">
                        {{ $bookStock->sum('quantity') }}
                    </td>

                    <td class="number">
                        {{ $bookStock->sum('available_quantity') }}
                    </td>

                    <td class="number">
                        {{
                            $bookStock->sum('quantity')
                            -
                            $bookStock->sum('available_quantity')
                        }}
                    </td>

                </tr>

            </tfoot>

        @endif

    </table>


    {{-- TRANSACTIONS --}}
    <div class="section-title">
        Library Transactions
    </div>

    <table class="report-table">

        <thead>
            <tr>
                <th>Book</th>
                <th>Student ID</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Fine</th>
            </tr>
        </thead>

        <tbody>

            @forelse($transactions as $issue)

                @php
                    $status = $issue->status;

                    if (
                        !$issue->return_date &&
                        $issue->due_date &&
                        $issue->due_date->isPast()
                    ) {
                        $status = 'Overdue';
                    }
                @endphp

                <tr>

                    <td>
                        <strong>
                            {{ $issue->book->title ?? 'Deleted Book' }}
                        </strong>

                        @if($issue->book)
                            <br>
                            <span style="font-size:9px;color:#667085;">
                                {{ $issue->book->author }}
                            </span>
                        @endif
                    </td>

                    <td>
                        {{ $issue->student_id }}
                    </td>

                    <td>
                        {{ $issue->issue_date
                            ? $issue->issue_date->format('d M Y')
                            : '—'
                        }}
                    </td>

                    <td>
                        {{ $issue->due_date
                            ? $issue->due_date->format('d M Y')
                            : '—'
                        }}
                    </td>

                    <td>
                        {{ $issue->return_date
                            ? $issue->return_date->format('d M Y')
                            : '—'
                        }}
                    </td>

                    <td>

                        @if($status === 'Returned')

                            <span class="status-returned">
                                Returned
                            </span>

                        @elseif($status === 'Overdue')

                            <span class="status-overdue">
                                Overdue
                            </span>

                        @else

                            <span class="status-issued">
                                Issued
                            </span>

                        @endif

                    </td>

                    <td>

                        @if(($issue->fine ?? 0) > 0)

                            <span class="fine">
                                ₹{{ number_format($issue->fine, 2) }}
                            </span>

                        @else

                            ₹0.00

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" style="text-align:center;">
                        No transactions found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- FOOTER --}}
    <div class="footer">
        Gurukul Vidyalaya — Library Management System
    </div>

</body>
</html>