```blade
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Student Kit Distribution -
        {{ $studentSupplyKit->student?->student_id ?? '' }}
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <style>

        @page {

            size: A4 portrait;

            margin: 12mm;

        }


        * {

            box-sizing: border-box;

        }


        body {

            margin: 0;

            padding: 0;

            background: #e9ecef;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #212529;

            font-size: 13px;

        }


        .print-wrapper {

            width: 210mm;

            min-height: 297mm;

            margin: 20px auto;

            padding: 12mm;

            background: #fff;

            box-shadow:
                0 0 12px rgba(0, 0, 0, 0.12);

        }


        /* =====================================================
           SCHOOL HEADER
        ====================================================== */

        .school-header {

            border-bottom: 2px solid #212529;

            padding-bottom: 12px;

            margin-bottom: 16px;

        }


        .school-logo {

            width: 82px;

            height: 82px;

            object-fit: contain;

        }


        .school-name {

            font-size: 24px;

            font-weight: 700;

            margin-bottom: 3px;

            text-transform: uppercase;

        }


        .school-address {

            font-size: 12px;

            line-height: 1.5;

        }


        .school-contact {

            font-size: 11px;

            margin-top: 3px;

        }


        /* =====================================================
           TITLE
        ====================================================== */

        .document-title {

            text-align: center;

            margin: 15px 0 18px;

        }


        .document-title h2 {

            font-size: 19px;

            font-weight: 700;

            margin: 0;

            text-transform: uppercase;

            letter-spacing: .4px;

        }


        .document-title .subtitle {

            font-size: 11px;

            color: #555;

            margin-top: 4px;

        }


        /* =====================================================
           STATUS
        ====================================================== */

        .status-box {

            display: inline-block;

            padding: 4px 12px;

            border: 1px solid #999;

            border-radius: 4px;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

        }


        /* =====================================================
           INFORMATION TABLE
        ====================================================== */

        .info-table {

            width: 100%;

            border-collapse: collapse;

            margin-bottom: 18px;

        }


        .info-table td {

            border: 1px solid #bfc3c7;

            padding: 7px 9px;

            vertical-align: middle;

        }


        .info-label {

            width: 18%;

            font-weight: 700;

            background: #f5f5f5;

            white-space: nowrap;

        }


        .info-value {

            width: 32%;

        }


        /* =====================================================
           ITEMS TABLE
        ====================================================== */

        .items-title {

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 8px;

        }


        .items-table {

            width: 100%;

            border-collapse: collapse;

            margin-bottom: 15px;

        }


        .items-table th,
        .items-table td {

            border: 1px solid #555;

            padding: 7px 8px;

        }


        .items-table th {

            background: #f1f1f1;

            font-weight: 700;

            text-align: center;

        }


        .items-table td {

            vertical-align: middle;

        }


        .text-center {

            text-align: center;

        }


        .text-right {

            text-align: right;

        }


        .total-row td {

            font-weight: 700;

            background: #f7f7f7;

        }


        /* =====================================================
           DECLARATION
        ====================================================== */

        .declaration {

            border: 1px solid #aaa;

            padding: 10px 12px;

            margin-top: 15px;

            margin-bottom: 40px;

            line-height: 1.6;

        }


        .declaration-title {

            font-weight: 700;

            margin-bottom: 4px;

        }


        /* =====================================================
           SIGNATURES
        ====================================================== */

        .signature-section {

            margin-top: 55px;

        }


        .signature-line {

            border-top: 1px solid #333;

            width: 145px;

            margin: 0 auto 6px;

        }


        .signature-label {

            text-align: center;

            font-size: 11px;

            font-weight: 600;

        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .document-footer {

            margin-top: 35px;

            padding-top: 8px;

            border-top: 1px solid #aaa;

            display: flex;

            justify-content: space-between;

            font-size: 9px;

            color: #666;

        }


        /* =====================================================
           PRINT BUTTON
        ====================================================== */

        .print-actions {

            width: 210mm;

            margin: 15px auto;

            display: flex;

            justify-content: center;

            gap: 10px;

        }


        @media print {

            body {

                background: #fff;

            }


            .print-wrapper {

                width: auto;

                min-height: auto;

                margin: 0;

                padding: 0;

                box-shadow: none;

            }


            .print-actions {

                display: none !important;

            }


            .no-print {

                display: none !important;

            }

        }

    </style>

</head>


<body>


@php

    $schoolName =
        $school?->school_name
        ?? 'Gurukul Vidyalay';

    $schoolAddress =
        $school?->address
        ?? '';

    $schoolCity =
        $school?->city
        ?? '';

    $schoolDistrict =
        $school?->district
        ?? '';

    $schoolState =
        $school?->state
        ?? '';

    $schoolPincode =
        $school?->pincode
        ?? '';

    $schoolPhone =
        $school?->phone
        ?? '';

    $schoolEmail =
        $school?->email
        ?? '';

    $schoolUdise =
        $school?->udise_code
        ?? '';

    $schoolCode =
        $school?->school_code
        ?? '';

    $schoolLogo =
        $school?->logo_url
        ?? asset('images/gurukullogo.png');


    $student =
        $studentSupplyKit->student;

    $kitTemplate =
        $studentSupplyKit->kitTemplate;


    $studentName = $student
        ? collect([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])->filter()->implode(' ')
        : '-';


    $totalQuantity =
        $studentSupplyKit->items->sum('quantity');


    $statusText =
        ucfirst(
            $studentSupplyKit->status
        );

@endphp


{{-- =========================================================
     PRINT ACTIONS
========================================================== --}}

<div class="print-actions">

    <button
        type="button"
        onclick="window.print()"
        class="btn btn-primary"
    >

        <i class="bi bi-printer me-1"></i>

        Print

    </button>


    <button
        type="button"
        onclick="window.close()"
        class="btn btn-outline-secondary"
    >

        <i class="bi bi-x-lg me-1"></i>

        Close

    </button>

</div>


{{-- =========================================================
     PRINT DOCUMENT
========================================================== --}}

<div class="print-wrapper">


    {{-- =====================================================
         SCHOOL HEADER
    ====================================================== --}}

    <div class="school-header">

        <div class="row align-items-center">

            <div class="col-2 text-center">

                <img
                    src="{{ $schoolLogo }}"
                    alt="School Logo"
                    class="school-logo"
                >

            </div>


            <div class="col-10 text-center">

                <div class="school-name">

                    {{ $schoolName }}

                </div>


                @if($schoolAddress || $schoolCity || $schoolDistrict)

                    <div class="school-address">

                        {{ $schoolAddress }}

                        @if($schoolCity)
                            , {{ $schoolCity }}
                        @endif

                        @if($schoolDistrict)
                            , {{ $schoolDistrict }}
                        @endif

                        @if($schoolState)
                            , {{ $schoolState }}
                        @endif

                        @if($schoolPincode)
                            - {{ $schoolPincode }}
                        @endif

                    </div>

                @endif


                @if($schoolPhone || $schoolEmail)

                    <div class="school-contact">

                        @if($schoolPhone)

                            Phone:
                            {{ $schoolPhone }}

                        @endif


                        @if($schoolPhone && $schoolEmail)

                            &nbsp; | &nbsp;

                        @endif


                        @if($schoolEmail)

                            Email:
                            {{ $schoolEmail }}

                        @endif

                    </div>

                @endif


                @if($schoolUdise || $schoolCode)

                    <div class="school-contact">

                        @if($schoolUdise)

                            UDISE Code:
                            <strong>
                                {{ $schoolUdise }}
                            </strong>

                        @endif


                        @if($schoolUdise && $schoolCode)

                            &nbsp; | &nbsp;

                        @endif


                        @if($schoolCode)

                            School Code:
                            <strong>
                                {{ $schoolCode }}
                            </strong>

                        @endif

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
         DOCUMENT TITLE
    ====================================================== --}}

    <div class="document-title">

        <h2>
            Government Student Supply Kit Distribution
        </h2>

        <div class="subtitle">
            Student Kit Distribution Record
        </div>

    </div>


    {{-- =====================================================
         STUDENT + DISTRIBUTION INFORMATION
    ====================================================== --}}

    <table class="info-table">

        <tr>

            <td class="info-label">
                Student Name
            </td>

            <td class="info-value">
                {{ $studentName }}
            </td>


            <td class="info-label">
                Student ID
            </td>

            <td class="info-value">
                {{ $student?->student_id ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Class
            </td>

            <td class="info-value">

                {{ $student?->class ?? '-' }}

                @if($student?->section)
                    / {{ $student->section }}
                @endif

            </td>


            <td class="info-label">
                Academic Year
            </td>

            <td class="info-value">
                {{ $studentSupplyKit->academic_year ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Government Kit
            </td>

            <td class="info-value">
                {{ $kitTemplate?->kit_name ?? '-' }}
            </td>


            <td class="info-label">
                Kit Class
            </td>

            <td class="info-value">
                {{ $kitTemplate?->class ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Distribution Date
            </td>

            <td class="info-value">

                @if($studentSupplyKit->issue_date)

                    {{ \Carbon\Carbon::parse(
                        $studentSupplyKit->issue_date
                    )->format('d-m-Y') }}

                @else

                    -

                @endif

            </td>


            <td class="info-label">
                Status
            </td>

            <td class="info-value">

                <span class="status-box">

                    {{ $statusText }}

                </span>

            </td>

        </tr>


        @if($studentSupplyKit->remarks)

            <tr>

                <td class="info-label">
                    Remarks
                </td>

                <td
                    class="info-value"
                    colspan="3"
                >
                    {{ $studentSupplyKit->remarks }}
                </td>

            </tr>

        @endif

    </table>


    {{-- =====================================================
         ITEMS
    ====================================================== --}}

    <div class="items-title">

        Government Supplied Items

    </div>


    <table class="items-table">

        <thead>

            <tr>

                <th style="width:45px;">
                    Sr. No.
                </th>

                <th>
                    Supply Item
                </th>

                <th style="width:100px;">
                    Item Code
                </th>

                <th style="width:75px;">
                    Unit
                </th>

                <th style="width:85px;">
                    Quantity
                </th>

                <th style="width:90px;">
                    Condition
                </th>

                <th>
                    Remarks
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($studentSupplyKit->items as $index => $item)

                <tr>

                    <td class="text-center">

                        {{ $index + 1 }}

                    </td>


                    <td>

                        {{ $item->supplyItem?->item_name ?? '-' }}

                    </td>


                    <td class="text-center">

                        {{ $item->supplyItem?->item_code ?? '-' }}

                    </td>


                    <td class="text-center">

                        {{ $item->supplyItem?->unit ?? '-' }}

                    </td>


                    <td class="text-center fw-bold">

                        {{ $item->quantity }}

                    </td>


                    <td class="text-center">

                        {{ $item->condition ?? 'New' }}

                    </td>


                    <td>

                        {{ $item->remarks ?? '-' }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center"
                    >

                        No distribution items found.

                    </td>

                </tr>

            @endforelse


            @if($studentSupplyKit->items->count())

                <tr class="total-row">

                    <td
                        colspan="4"
                        class="text-right"
                    >

                        Total Quantity

                    </td>


                    <td class="text-center">

                        {{ $totalQuantity }}

                    </td>


                    <td colspan="2"></td>

                </tr>

            @endif

        </tbody>

    </table>


    {{-- =====================================================
         DECLARATION
    ====================================================== --}}

    <div class="declaration">

        <div class="declaration-title">
            Distribution Declaration
        </div>

        This record confirms that the above government-supplied
        student kit items have been recorded against the student
        mentioned in this document.

        The quantities shown above represent the items recorded
        for this distribution.

    </div>


    {{-- =====================================================
         SIGNATURES
    ====================================================== --}}

    <div class="row signature-section">

        <div class="col-4">

            <div class="signature-line"></div>

            <div class="signature-label">
                Student / Parent Signature
            </div>

        </div>


        <div class="col-4">

            <div class="signature-line"></div>

            <div class="signature-label">
                Distributed By
            </div>

        </div>


        <div class="col-4">

            <div class="signature-line"></div>

            <div class="signature-label">
                Headmaster / Principal
            </div>

        </div>

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="document-footer">

        <div>

            Distribution ID:

            <strong>
                #{{ $studentSupplyKit->id }}
            </strong>

        </div>


        <div>

            Generated:

            {{ now()->format('d-m-Y H:i') }}

        </div>

    </div>

</div>


</body>

</html>
