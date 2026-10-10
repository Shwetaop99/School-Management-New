
@extends('layouts.app')

@section('title', 'Student Travel Data')
@section('page-title', 'Student Travel Data')

@section('content')

<style>
    .report-container {
        padding: 25px;
    }

    /* =========================
       HEADER
    ========================= */

    .report-header {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        padding: 25px;
        border-radius: 14px;
        margin-bottom: 25px;

        display: flex;
        justify-content: space-between;
        align-items: center;

        box-shadow: 0 5px 18px rgba(37, 99, 235, 0.20);
    }

    .report-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .report-title-icon {
        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(255, 255, 255, 0.18);
        border-radius: 12px;

        font-size: 24px;
    }

    .report-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .report-header p {
        margin: 6px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    /* =========================
       BUTTONS
    ========================= */

    .header-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-report {
        border: none;
        padding: 10px 15px;

        border-radius: 8px;

        text-decoration: none;
        cursor: pointer;

        font-size: 13px;
        font-weight: 600;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        transition: all 0.2s ease;
    }

    .btn-report:hover {
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-back {
        background: white;
        color: #2563eb;
    }

    .btn-back:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .btn-print {
        background: #16a34a;
        color: white;
    }

    .btn-print:hover {
        background: #15803d;
        color: white;
    }

    .btn-pdf {
        background: #dc2626;
        color: white;
    }

    .btn-pdf:hover {
        background: #b91c1c;
        color: white;
    }

    .btn-excel {
        background: #059669;
        color: white;
    }

    .btn-excel:hover {
        background: #047857;
        color: white;
    }

    /* =========================
       TABLE CARD
    ========================= */

    .table-card {
        background: white;
        border-radius: 14px;

        padding: 20px;

        box-shadow: 0 3px 14px rgba(0, 0, 0, 0.08);

        overflow-x: auto;
    }

    .table-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        margin-bottom: 18px;
    }

    .table-card-header h3 {
        margin: 0;
        color: #1e293b;
        font-size: 18px;
        font-weight: 700;
    }

    .record-count {
        background: #eff6ff;
        color: #2563eb;

        padding: 6px 11px;

        border-radius: 20px;

        font-size: 12px;
        font-weight: 600;
    }

    /* =========================
       TABLE
    ========================= */

    .report-table {
        width: 100%;
        border-collapse: collapse;

        min-width: 1150px;
    }

    .report-table th {
        background: #f1f5f9;

        color: #334155;

        padding: 13px;

        text-align: left;

        font-size: 12px;
        font-weight: 700;

        border-bottom: 2px solid #e2e8f0;

        white-space: nowrap;
    }

    .report-table td {
        padding: 13px;

        border-bottom: 1px solid #e5e7eb;

        color: #475569;

        font-size: 13px;

        vertical-align: middle;
    }

    .report-table tbody tr {
        transition: background 0.15s ease;
    }

    .report-table tbody tr:hover {
        background: #f8fafc;
    }

    .student-name {
        color: #1e293b;
        font-weight: 600;
    }

    .student-id {
        display: block;

        margin-top: 3px;

        color: #64748b;

        font-size: 11px;
    }

    /* =========================
       STATUS
    ========================= */

    .status {
        display: inline-block;

        padding: 5px 10px;

        border-radius: 20px;

        font-size: 11px;
        font-weight: 700;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    /* =========================
       PAYMENT
    ========================= */

    .payment-paid {
        color: #15803d;
        font-weight: 700;
    }

    .payment-pending {
        color: #dc2626;
        font-weight: 700;
    }

    .payment-partial {
        color: #d97706;
        font-weight: 700;
    }

    /* =========================
       EMPTY STATE
    ========================= */

    .empty-state {
        text-align: center;

        padding: 60px 30px;

        color: #64748b;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;

        margin: 0 auto 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #eff6ff;

        color: #2563eb;

        border-radius: 50%;

        font-size: 28px;
    }

    .empty-state h3 {
        margin: 0 0 8px;

        color: #334155;

        font-size: 18px;
    }

    .empty-state p {
        margin: 0;

        font-size: 13px;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 900px) {

        .report-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .btn-report {
            flex: 1;
        }
    }

    @media (max-width: 600px) {

        .report-container {
            padding: 15px;
        }

        .report-header {
            padding: 20px;
        }

        .report-header h2 {
            font-size: 20px;
        }

        .header-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
        }

        .btn-report {
            width: 100%;
        }

        .table-card {
            padding: 15px;
        }
    }

    /* =========================
       PRINT
    ========================= */

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

            box-shadow: none;
        }

        .report-title-icon {
            background: #f1f5f9;
            color: black;
        }

        .report-header p {
            color: #555;
            opacity: 1;
        }

        .table-card {
            box-shadow: none;
            padding: 0;
        }

        .report-table {
            min-width: 0;
        }

        .report-table th {
            background: #f1f5f9 !important;
            color: #000 !important;
        }

        .report-table td {
            color: #000 !important;
        }
    }
</style>


<div class="report-container">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="report-header">

        <div class="report-title">

            <div class="report-title-icon">
                <i class="fas fa-route"></i>
            </div>

            <div>
                <h2>
                    Student Travel Data
                </h2>

                <p>
                    Student-wise transport and travel information
                </p>
            </div>

        </div>


        {{-- =====================================================
             ACTION BUTTONS
        ====================================================== --}}

        <div class="header-actions no-print">

            {{-- Back --}}
            <a href="{{ route('admin.reports.transport') }}"
               class="btn-report btn-back">

                <i class="fas fa-arrow-left"></i>

                Back

            </a>


            {{-- PDF --}}
            <a href="{{ route('admin.reports.transport.student-travel.pdf') }}"
   class="btn-report btn-pdf">
    <i class="fas fa-file-pdf"></i>
    PDF
</a>


            {{-- Excel --}}
           <a href="{{ route('admin.reports.transport.student-travel.excel') }}"
   class="btn-report btn-excel">
    <i class="fas fa-file-excel"></i>
    Excel
</a>


            {{-- Print --}}
            <button type="button"
                    onclick="window.print()"
                    class="btn-report btn-print">

                <i class="fas fa-print"></i>

                Print

            </button>

        </div>

    </div>


    {{-- =========================================================
         TABLE CARD
    ========================================================== --}}

    <div class="table-card">

        <div class="table-card-header">

            <h3>
                <i class="fas fa-users"
                   style="margin-right:7px; color:#2563eb;"></i>

                Student Travel Records
            </h3>

            <span class="record-count">

                {{ $records->count() }}

                {{ $records->count() == 1 ? 'Record' : 'Records' }}

            </span>

        </div>


        @if($records->count())


            <table class="report-table">

                <thead>

                    <tr>

                        <th>
                            Student
                        </th>

                        <th>
                            Route
                        </th>

                        <th>
                            Vehicle
                        </th>

                        <th>
                            Pickup Point
                        </th>

                        <th>
                            Drop Point
                        </th>

                        <th>
                            Transport Type
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Transport Fee
                        </th>

                        <th>
                            Payment
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($records as $record)

                        <tr>

                            {{-- STUDENT --}}

                            <td>

                                @if($record->student)

                                    <span class="student-name">

                                        {{ trim(
                                            collect([
                                                $record->student->first_name,
                                                $record->student->middle_name,
                                                $record->student->last_name
                                            ])
                                            ->filter()
                                            ->implode(' ')
                                        ) }}

                                    </span>

                                    @if($record->student->student_id)

                                        <span class="student-id">

                                            ID:
                                            {{ $record->student->student_id }}

                                        </span>

                                    @endif

                                @else

                                    <span>
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- ROUTE --}}

                            <td>

                                {{ $record->route ?: '—' }}

                            </td>


                            {{-- VEHICLE --}}

                            <td>

                                {{ $record->vehicle ?: 'Not Assigned' }}

                            </td>


                            {{-- PICKUP --}}

                            <td>

                                {{ $record->pickup_point ?: '—' }}

                            </td>


                            {{-- DROP --}}

                            <td>

                                {{ $record->drop_point ?: '—' }}

                            </td>


                            {{-- TRANSPORT TYPE --}}

                            <td>

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $record->transport_type ?? 'other'
                                    )
                                ) }}

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($record->transport_status === 'active')

                                    <span class="status status-active">

                                        Active

                                    </span>

                                @else

                                    <span class="status status-inactive">

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- TRANSPORT FEE --}}

                            <td>

                                ₹{{ number_format(
                                    (float) ($record->transport_fee ?? 0),
                                    2
                                ) }}

                            </td>


                            {{-- PAYMENT --}}

                            <td>

                                @if($record->payment_status === 'paid')

                                    <span class="payment-paid">
                                        Paid
                                    </span>

                                @elseif($record->payment_status === 'pending')

                                    <span class="payment-pending">
                                        Pending
                                    </span>

                                @elseif($record->payment_status === 'partially_paid')

                                    <span class="payment-partial">
                                        Partially Paid
                                    </span>

                                @else

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $record->payment_status ?? '—'
                                        )
                                    ) }}

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>


        @else


            {{-- EMPTY STATE --}}

            <div class="empty-state">

                <div class="empty-state-icon">

                    <i class="fas fa-route"></i>

                </div>

                <h3>
                    No Travel Records Found
                </h3>

                <p>
                    There are currently no student transport
                    records available.
                </p>

            </div>


        @endif

    </div>

</div>

@endsection

