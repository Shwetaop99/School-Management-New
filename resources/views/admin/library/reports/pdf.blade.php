<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">



    <title>Library Report</title>



    <style>
        @page { margin: 25px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #172033;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #147cf5;
        }

        .header h1 {
            margin: 0;
            font-size: 21px;
            color: #172b4d;
        }

        .header p {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 10px;
        }

        .report-panel {
            width: 100%;
            border: 1px solid #d8e0eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 0;
            padding: 11px 10px;
            color: #172b4d;
            background: #f1f5fb;
            border-bottom: 1px solid #d8e0eb;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        table.report-table th {
            background: #eaf0f8;
            color: #26364d;
            font-weight: bold;
            padding: 7px 6px;
            border: 1px solid #d8e0eb;
            text-align: left;
            font-size: 9px;
        }

        table.report-table td {
            padding: 7px 6px;
            border: 1px solid #d8e0eb;
            vertical-align: middle;
            font-size: 9px;
        }

        table.stock-table th,
        table.stock-table td { font-size: 9px; }

        .stock-total td {
            background: #f3f7fc;
            font-weight: bold;
            border-top: 2px solid #c4cfdd;
        }

        .number { text-align: center; }
        .status-returned { color: #138a4b; font-weight: bold; }
        .status-issued { color: #2563eb; font-weight: bold; }
        .status-overdue { color: #dc2626; font-weight: bold; }
        .fine { color: #dc2626; font-weight: bold; }

        .report-divider {
            height: 1px;
            background: #cbd5e1;
            margin: 0;
        }

        .footer {
            margin-top: 18px;
            text-align: right;
            color: #64748b;
            font-size: 9px;
        }

        .page-break { page-break-before: always; }
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





        {{-- UNIFIED LIBRARY REPORT --}}
    <div class="report-panel">

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





        <div class="report-divider"></div>

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





        </div>

{{-- FOOTER --}}

    <div class="footer">

        Gurukul Vidyalaya — Library Management System

    </div>



</body>

</html>