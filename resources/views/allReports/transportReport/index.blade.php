@extends('layouts.app')

@section('title', 'Transport Reports')
@section('page-title', 'Transport Reports')

@section('content')

<div style="
    padding: 25px;
    background: #f5f8fc;
    min-height: calc(100vh - 70px);
">

    {{-- HEADER --}}
    <div style="
        background: linear-gradient(135deg, #1976d2, #42a5f5);
        border-radius: 18px;
        padding: 28px 32px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(25, 118, 210, 0.18);
    ">

        <h1 style="
            margin: 0 0 8px 0;
            font-size: 30px;
            font-weight: 700;
        ">
            <i class="fas fa-bus" style="margin-right: 10px;"></i>
            Transport Reports
        </h1>

        <p style="
            margin: 0;
            font-size: 15px;
            opacity: 0.9;
        ">
            Routes, vehicles, students and transport fee records.
        </p>

    </div>


    {{-- SUMMARY CARDS --}}
    <div style="
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    ">

        {{-- TOTAL RECORDS --}}
        <div style="
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        ">
            <div style="
                color: #6c757d;
                font-size: 14px;
                margin-bottom: 8px;
            ">
                Total Records
            </div>

            <div style="
                font-size: 27px;
                font-weight: 700;
                color: #17365d;
            ">
                {{ $totalRecords }}
            </div>
        </div>


        {{-- ACTIVE --}}
        <div style="
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        ">
            <div style="
                color: #6c757d;
                font-size: 14px;
                margin-bottom: 8px;
            ">
                Active Transport
            </div>

            <div style="
                font-size: 27px;
                font-weight: 700;
                color: #198754;
            ">
                {{ $activeRecords }}
            </div>
        </div>


        {{-- INACTIVE --}}
        <div style="
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        ">
            <div style="
                color: #6c757d;
                font-size: 14px;
                margin-bottom: 8px;
            ">
                Inactive Transport
            </div>

            <div style="
                font-size: 27px;
                font-weight: 700;
                color: #dc3545;
            ">
                {{ $inactiveRecords }}
            </div>
        </div>


        {{-- STUDENTS --}}
        <div style="
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        ">
            <div style="
                color: #6c757d;
                font-size: 14px;
                margin-bottom: 8px;
            ">
                Assigned Students
            </div>

            <div style="
                font-size: 27px;
                font-weight: 700;
                color: #1976d2;
            ">
                {{ $assignedStudents }}
            </div>
        </div>


        {{-- FEES --}}
        <div style="
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        ">
            <div style="
                color: #6c757d;
                font-size: 14px;
                margin-bottom: 8px;
            ">
                Transport Fees
            </div>

            <div style="
                font-size: 24px;
                font-weight: 700;
                color: #17365d;
            ">
                ₹{{ number_format($totalTransportFees, 2) }}
            </div>
        </div>

    </div>


    {{-- FILTER SECTION --}}
    <div style="
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    ">

        <h2 style="
            margin: 0 0 20px 0;
            color: #17365d;
            font-size: 25px;
        ">
            Filter Report
        </h2>


        <form method="GET"
              action="{{ route('admin.reports.transport') }}">

            <div style="
                display: grid;
                grid-template-columns: repeat(6, minmax(0, 1fr));
                gap: 15px;
                align-items: end;
            ">

                {{-- STUDENT --}}
                <div>

                    <label style="
                        display: block;
                        margin-bottom: 7px;
                        color: #555;
                        font-size: 14px;
                    ">
                        Student
                    </label>

                    <select name="student_id"
                            style="
                                width: 100%;
                                padding: 10px 12px;
                                border: 1px solid #d7dce2;
                                border-radius: 8px;
                                background: white;
                            ">

                        <option value="">
                            All Students
                        </option>

                        @foreach($students as $student)

                            <option
                                value="{{ $student->id }}"
                                {{ request('student_id') == $student->id ? 'selected' : '' }}
                            >
                                {{ $student->student_id ?? $student->id }}
                                -
                                {{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- STATUS --}}
                <div>

                    <label style="
                        display: block;
                        margin-bottom: 7px;
                        color: #555;
                        font-size: 14px;
                    ">
                        Transport Status
                    </label>

                    <select name="transport_status"
                            style="
                                width: 100%;
                                padding: 10px 12px;
                                border: 1px solid #d7dce2;
                                border-radius: 8px;
                                background: white;
                            ">

                        <option value="">
                            All Status
                        </option>

                        <option value="active"
                            {{ request('transport_status') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ request('transport_status') == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- TRANSPORT TYPE --}}
                <div>

                    <label style="
                        display: block;
                        margin-bottom: 7px;
                        color: #555;
                        font-size: 14px;
                    ">
                        Transport Type
                    </label>

                    <select name="transport_type"
                            style="
                                width: 100%;
                                padding: 10px 12px;
                                border: 1px solid #d7dce2;
                                border-radius: 8px;
                                background: white;
                            ">

                        <option value="">
                            All Types
                        </option>

                        <option value="bus"
                            {{ request('transport_type') == 'bus' ? 'selected' : '' }}>
                            Bus
                        </option>

                        <option value="van"
                            {{ request('transport_type') == 'van' ? 'selected' : '' }}>
                            Van
                        </option>

                        <option value="other"
                            {{ request('transport_type') == 'other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                </div>


                {{-- PAYMENT STATUS --}}
                <div>

                    <label style="
                        display: block;
                        margin-bottom: 7px;
                        color: #555;
                        font-size: 14px;
                    ">
                        Payment Status
                    </label>

                    <select name="payment_status"
                            style="
                                width: 100%;
                                padding: 10px 12px;
                                border: 1px solid #d7dce2;
                                border-radius: 8px;
                                background: white;
                            ">

                        <option value="">
                            All Payments
                        </option>

                        <option value="paid"
                            {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                            Paid
                        </option>

                        <option value="pending"
                            {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="partially_paid"
                            {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>
                            Partially Paid
                        </option>

                    </select>

                </div>


                {{-- FROM DATE --}}
                <div>

                    <label style="
                        display: block;
                        margin-bottom: 7px;
                        color: #555;
                        font-size: 14px;
                    ">
                        From Date
                    </label>

                    <input
                        type="date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                        style="
                            width: 100%;
                            padding: 9px 12px;
                            border: 1px solid #d7dce2;
                            border-radius: 8px;
                        "
                    >

                </div>


                {{-- TO DATE --}}
                <div>

                    <label style="
                        display: block;
                        margin-bottom: 7px;
                        color: #555;
                        font-size: 14px;
                    ">
                        To Date
                    </label>

                    <input
                        type="date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                        style="
                            width: 100%;
                            padding: 9px 12px;
                            border: 1px solid #d7dce2;
                            border-radius: 8px;
                        "
                    >

                </div>

            </div>


            {{-- FILTER BUTTONS --}}
            <div style="
                margin-top: 18px;
                display: flex;
                gap: 10px;
            ">

                <button
                    type="submit"
                    style="
                        border: none;
                        background: #5b72e8;
                        color: white;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-size: 14px;
                    "
                >
                    <i class="fas fa-filter"></i>
                    Apply Filters
                </button>


                <a
                    href="{{ route('admin.reports.transport') }}"
                    style="
                        background: #eeeeee;
                        color: #333;
                        padding: 10px 20px;
                        border-radius: 8px;
                        text-decoration: none;
                        font-size: 14px;
                    "
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- ACTION BUTTONS --}}
    <div style="
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 15px;
    ">

        <button
            type="button"
            onclick="window.print()"
            style="
                border: none;
                background: #198754;
                color: white;
                padding: 10px 18px;
                border-radius: 8px;
                cursor: pointer;
            "
        >
            <i class="fas fa-print"></i>
            Print
        </button>


        <a
            href="{{ route('admin.reports.transport.pdf', request()->query()) }}"
            style="
                background: #dc3545;
                color: white;
                padding: 10px 18px;
                border-radius: 8px;
                text-decoration: none;
            "
        >
            <i class="fas fa-file-pdf"></i>
            PDF
        </a>


        <a
            href="{{ route('admin.reports.transport.excel', request()->query()) }}"
            style="
                background: #198754;
                color: white;
                padding: 10px 18px;
                border-radius: 8px;
                text-decoration: none;
            "
        >
            <i class="fas fa-file-excel"></i>
            Excel
        </a>

    </div>


    {{-- TRANSPORT RECORDS --}}
    <div style="
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        overflow-x: auto;
    ">

        <h2 style="
            margin: 0 0 20px 0;
            color: #17365d;
            font-size: 25px;
        ">
            Student Transport Records
        </h2>


        <table style="
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        ">

            <thead>

                <tr style="background: #f1f3f5;">

                    <th style="padding: 12px; text-align: left;">
                        Student
                    </th>

                    <th style="padding: 12px; text-align: left;">
                        Route
                    </th>

                    <th style="padding: 12px; text-align: left;">
                        Vehicle
                    </th>

                    <th style="padding: 12px; text-align: left;">
                        Pickup Point
                    </th>

                    <th style="padding: 12px; text-align: left;">
                        Drop Point
                    </th>

                    <th style="padding: 12px; text-align: center;">
                        Type
                    </th>

                    <th style="padding: 12px; text-align: center;">
                        Status
                    </th>

                    <th style="padding: 12px; text-align: right;">
                        Fee
                    </th>

                    <th style="padding: 12px; text-align: center;">
                        Payment
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($records as $record)

                    <tr style="border-bottom: 1px solid #eeeeee;">

                        <td style="padding: 12px;">

                            <strong>
                                {{ trim(
                                    ($record->student->first_name ?? '') .
                                    ' ' .
                                    ($record->student->last_name ?? '')
                                ) ?: '-' }}
                            </strong>

                            @if($record->student?->student_id)

                                <div style="
                                    font-size: 12px;
                                    color: #777;
                                    margin-top: 3px;
                                ">
                                    {{ $record->student->student_id }}
                                </div>

                            @endif

                        </td>


                        <td style="padding: 12px;">
                            {{ $record->route ?? '-' }}
                        </td>


                        <td style="padding: 12px;">
                            {{ $record->vehicle ?? '-' }}
                        </td>


                        <td style="padding: 12px;">
                            {{ $record->pickup_point ?? '-' }}
                        </td>


                        <td style="padding: 12px;">
                            {{ $record->drop_point ?? '-' }}
                        </td>


                        <td style="
                            padding: 12px;
                            text-align: center;
                        ">
                            {{ ucfirst($record->transport_type ?? '-') }}
                        </td>


                        <td style="
                            padding: 12px;
                            text-align: center;
                        ">

                            @if($record->transport_status === 'active')

                                <span style="
                                    background: #d1e7dd;
                                    color: #0f5132;
                                    padding: 5px 10px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: 600;
                                ">
                                    Active
                                </span>

                            @else

                                <span style="
                                    background: #f8d7da;
                                    color: #842029;
                                    padding: 5px 10px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: 600;
                                ">
                                    {{ ucfirst($record->transport_status ?? 'Inactive') }}
                                </span>

                            @endif

                        </td>


                        <td style="
                            padding: 12px;
                            text-align: right;
                            font-weight: 600;
                        ">
                            ₹{{ number_format($record->transport_fee ?? 0, 2) }}
                        </td>


                        <td style="
                            padding: 12px;
                            text-align: center;
                        ">

                            @if($record->payment_status === 'paid')

                                <span style="
                                    color: #198754;
                                    font-weight: 600;
                                ">
                                    Paid
                                </span>

                            @elseif($record->payment_status === 'pending')

                                <span style="
                                    color: #dc3545;
                                    font-weight: 600;
                                ">
                                    Pending
                                </span>

                            @else

                                <span style="
                                    color: #856404;
                                    font-weight: 600;
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $record->payment_status ?? '-')) }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            style="
                                padding: 30px;
                                text-align: center;
                                color: #777;
                            "
                        >
                            No transport records found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- PRINT CSS --}}

<style>

@media print {

    body {
        background: white !important;
    }

    .sidebar,
    .main-sidebar,
    nav,
    header,
    .navbar {
        display: none !important;
    }

    button,
    a {
        display: none !important;
    }

    @page {
        size: landscape;
        margin: 12mm;
    }

}

</style>

@endsection