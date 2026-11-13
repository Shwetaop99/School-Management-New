@extends('layouts.app')

@section('title', 'Vehicle Report')
@section('page-title', 'Vehicle Report')

@section('content')

<style>
    .report-container {
        padding: 25px;
    }

    .report-header {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .report-header h2 {
        margin: 0;
        font-size: 24px;
    }

    .report-header p {
        margin: 6px 0 0;
        opacity: 0.9;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-report {
        border: none;
        padding: 10px 16px;
        border-radius: 7px;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        transition: 0.2s ease;
    }

    .btn-report:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-back {
        background: white;
        color: #2563eb;
    }

    .btn-print {
        background: #16a34a;
        color: white;
    }

    .btn-pdf {
        background: #dc2626;
        color: white;
    }

    .btn-excel {
        background: #15803d;
        color: white;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .report-table th {
        background: #f1f5f9;
        color: #334155;
        padding: 13px;
        text-align: left;
        font-size: 13px;
        border-bottom: 2px solid #e2e8f0;
    }

    .report-table td {
        padding: 13px;
        border-bottom: 1px solid #e5e7eb;
        color: #475569;
        font-size: 13px;
    }

    .report-table tr:hover {
        background: #f8fafc;
    }

    .badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-active {
        background: #dcfce7;
        color: #166534;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #64748b;
    }

    @media print {

        .no-print {
            display: none !important;
        }

        .report-container {
            padding: 0;
        }

        .report-header {
            background: white !important;
            color: black !important;
            border: 1px solid #ddd;
        }

        .table-card {
            box-shadow: none;
            padding: 0;
        }

        .report-table {
            min-width: 0;
        }
    }

    @media (max-width: 768px) {

        .report-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .header-actions {
            width: 100%;
        }

        .btn-report {
            flex: 1;
        }
    }
</style>

<div class="report-container">

<!-- =========================
     REPORT HEADER
========================== -->

<div class="report-header">

    <div>
        <h2>
            <i class="fas fa-bus"></i>
            Vehicle Report
        </h2>

        <p>
            Vehicle-wise transport assignment and fee information
        </p>
    </div>


    <!-- =========================
         ACTION BUTTONS
    ========================== -->

    <div class="header-actions no-print">

        <!-- Back -->

        <a href="{{ route('admin.reports.transport') }}"
           class="btn-report btn-back">

            <i class="fas fa-arrow-left"></i>

            Back

        </a>


        <!-- Print -->

        <button type="button"
                onclick="window.print()"
                class="btn-report btn-print">

            <i class="fas fa-print"></i>

            Print

        </button>


        <!-- PDF -->

       <a href="{{ route('admin.reports.transport.vehicle.pdf') }}"
   class="btn-report btn-pdf">
    <i class="fas fa-file-pdf"></i>
    PDF
</a>


        <!-- Excel -->

        <a href="{{ route('admin.reports.transport.vehicle.excel') }}"
   class="btn-report btn-excel">
    <i class="fas fa-file-excel"></i>
    Excel
</a>

    </div>

</div>


<!-- =========================
     VEHICLE REPORT TABLE
========================== -->

<div class="table-card">

    @if($vehicles->count())

        <table class="report-table">

            <thead>

                <tr>

                    <th>
                        Vehicle
                    </th>

                    <th>
                        Transport Type
                    </th>

                    <th>
                        Assigned Students
                    </th>

                    <th>
                        Active Students
                    </th>

                    <th>
                        Inactive Students
                    </th>

                    <th>
                        Total Fees
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($vehicles as $vehicle)

                    <tr>

                        <!-- Vehicle -->

                        <td>

                            <strong>
                                {{ $vehicle->vehicle ?: 'Not Assigned' }}
                            </strong>

                        </td>


                        <!-- Transport Type -->

                        <td>

                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $vehicle->transport_type ?? '-'
                                )
                            ) }}

                        </td>


                        <!-- Assigned Students -->

                        <td>

                            {{ $vehicle->assigned_students }}

                        </td>


                        <!-- Active Students -->

                        <td>

                            <span class="badge badge-active">

                                {{ $vehicle->active_students }}

                            </span>

                        </td>


                        <!-- Inactive Students -->

                        <td>

                            <span class="badge badge-inactive">

                                {{ $vehicle->inactive_students }}

                            </span>

                        </td>


                        <!-- Total Fees -->

                        <td>

                            ₹{{ number_format(
                                (float) ($vehicle->total_fees ?? 0),
                                2
                            ) }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <!-- =========================
             EMPTY STATE
        ========================== -->

        <div class="empty-state">

            <i class="fas fa-bus"
               style="font-size:40px; margin-bottom:15px;">
            </i>

            <h3>
                No Vehicle Records Found
            </h3>

            <p>
                There are currently no vehicle transport records available.
            </p>

        </div>

    @endif

</div>


</div>

@endsection
