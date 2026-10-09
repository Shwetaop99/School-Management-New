<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Bonafide Certificate -
        {{ $bonafideCertificate->certificate_number ?? 'Certificate' }}
    </title>

    <style>

        /*
        |--------------------------------------------------------------------------
        | PAGE SETUP
        |--------------------------------------------------------------------------
        */

        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f3f3;
            color: #111;
        }


        /*
        |--------------------------------------------------------------------------
        | PRINT PAGE
        |--------------------------------------------------------------------------
        */

        .print-page {
            width: 297mm;
            height: 210mm;
            min-height: 210mm;

            margin: 20px auto;
            padding: 15mm;

            background: #fff;

            position: relative;

            border: 2px solid #222;

            overflow: hidden;
        }


        /*
        |--------------------------------------------------------------------------
        | SCHOOL HEADER
        |--------------------------------------------------------------------------
        */

        .school-header {
            text-align: center;

            padding-bottom: 10px;

            border-bottom: 2px solid #222;
        }

        .school-logo {
            width: 80px;
            height: 80px;

            object-fit: contain;

            margin-bottom: 5px;
        }

        .school-name {
            font-size: 25px;
            font-weight: 700;

            text-transform: uppercase;

            margin: 0 0 5px 0;
        }

        .school-address {
            font-size: 13px;
            line-height: 1.5;

            margin: 0;
        }

        .school-contact {
            font-size: 12px;
            line-height: 1.5;

            margin-top: 3px;
        }


        /*
        |--------------------------------------------------------------------------
        | CERTIFICATE HEADER
        |--------------------------------------------------------------------------
        */

        .certificate-heading {
            text-align: center;

            margin-top: 18px;
            margin-bottom: 15px;
        }

        .certificate-heading h1 {
            font-size: 24px;

            margin: 0;

            font-weight: 700;

            text-transform: uppercase;

            text-decoration: underline;
        }

        .certificate-subtitle {
            font-size: 13px;

            margin-top: 5px;
        }


        /*
        |--------------------------------------------------------------------------
        | CERTIFICATE META
        |--------------------------------------------------------------------------
        */

        .certificate-meta {
            width: 100%;

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 18px;

            font-size: 13px;
        }

        .certificate-number {
            font-weight: 600;
        }

        .certificate-date {
            font-weight: 600;
        }


        /*
        |--------------------------------------------------------------------------
        | CERTIFICATE CONTENT
        |--------------------------------------------------------------------------
        */

        .certificate-content {
            margin-top: 10px;

            font-size: 17px;

            line-height: 2;

            text-align: justify;

            padding: 0 10px;
        }

        .certificate-content p {
            margin: 0 0 12px 0;
        }

        .student-name {
            font-weight: 700;

            text-transform: uppercase;
        }

        .school-name-inline {
            font-weight: 700;
        }

        .class-value {
            font-weight: 700;
        }

        .academic-year {
            font-weight: 700;
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENT DETAILS
        |--------------------------------------------------------------------------
        */

        .student-details {
            width: 100%;

            border-collapse: collapse;

            margin-top: 15px;

            font-size: 14px;
        }

        .student-details th,
        .student-details td {
            border: 1px solid #777;

            padding: 9px 10px;

            text-align: center;
        }

        .student-details th {
            background: #f1f1f1;

            font-weight: 700;
        }


        /*
        |--------------------------------------------------------------------------
        | PURPOSE
        |--------------------------------------------------------------------------
        */

        .purpose {
            margin-top: 18px;

            font-size: 15px;

            line-height: 1.7;

            padding: 0 10px;
        }

        .purpose-label {
            font-weight: 700;
        }


        /*
        |--------------------------------------------------------------------------
        | SIGNATURE SECTION
        |--------------------------------------------------------------------------
        */

        .signature-section {
            position: absolute;

            left: 15mm;
            right: 15mm;
            bottom: 18mm;

            display: flex;

            justify-content: space-between;

            align-items: flex-end;

            font-size: 14px;
        }

        .signature-box {
            width: 220px;

            text-align: center;
        }

        .signature-space {
            height: 45px;
        }

        .signature-line {
            border-top: 1px solid #222;

            margin-bottom: 6px;
        }

        .signature-title {
            font-weight: 700;
        }


        /*
        |--------------------------------------------------------------------------
        | PRINT BUTTON
        |--------------------------------------------------------------------------
        */

        .print-button-wrapper {
            position: fixed;

            top: 20px;
            right: 20px;

            z-index: 9999;
        }

        .print-button {
            border: none;

            background: #1677f0;

            color: #fff;

            padding: 10px 18px;

            border-radius: 5px;

            font-size: 14px;

            cursor: pointer;

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .print-button:hover {
            background: #0d5fc7;
        }


        /*
        |--------------------------------------------------------------------------
        | SCREEN
        |--------------------------------------------------------------------------
        */

        @media screen {

            body {
                background: #f3f3f3;
            }

            .print-page {
                margin: 20px auto;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PRINT
        |--------------------------------------------------------------------------
        */

        @media print {

            @page {
                size: A4 landscape;
                margin: 0;
            }

            html,
            body {
                width: 297mm;
                height: 210mm;

                margin: 0 !important;
                padding: 0 !important;

                background: #fff !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-page {
                width: 297mm !important;
                height: 210mm !important;
                min-height: 210mm !important;

                margin: 0 !important;

                padding: 15mm !important;

                border: 2px solid #222;

                page-break-after: always;
                break-after: page;

                overflow: hidden;
            }

            .no-print {
                display: none !important;
            }
        }

    </style>

</head>


<body>


    {{-- 
    |--------------------------------------------------------------------------
    | SCHOOL PROFILE
    |--------------------------------------------------------------------------
    --}}

    @php

        $school = $schoolSetting
            ?? \App\Models\SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        $student = $bonafideCertificate->student;


        /*
        |--------------------------------------------------------------------------
        | SCHOOL LOGO
        |--------------------------------------------------------------------------
        */

        $logo = $school->logo ?? null;

        if ($logo) {

            if (
                str_starts_with($logo, 'http://')
                ||
                str_starts_with($logo, 'https://')
            ) {

                $logoUrl = $logo;

            } else {

                $logoUrl = asset(
                    'storage/' . ltrim($logo, '/')
                );
            }

        } else {

            $logoUrl = asset(
                'images/gurukullogo.png'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENT NAME
        |--------------------------------------------------------------------------
        */

        $studentName = trim(
            implode(
                ' ',
                array_filter([
                    $student->first_name ?? null,
                    $student->middle_name ?? null,
                    $student->last_name ?? null,
                ])
            )
        );

        if (!$studentName) {

            $studentName =
                $student->name
                ?? '-';
        }


        /*
        |--------------------------------------------------------------------------
        | CLASS
        |--------------------------------------------------------------------------
        */

        $className =
            $student->admission_class
            ?? $student->class
            ?? '-';


        /*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

        $section =
            $student->section
            ?? '-';


        /*
        |--------------------------------------------------------------------------
        | ACADEMIC YEAR
        |--------------------------------------------------------------------------
        */

        $academicYear =
            $student->academic_year
            ?? $bonafideCertificate->academic_year
            ?? '2026-2027';


        /*
        |--------------------------------------------------------------------------
        | ISSUE DATE
        |--------------------------------------------------------------------------
        */

        $issueDate =
            $bonafideCertificate->issue_date
            ?? $bonafideCertificate->created_at
            ?? null;

        if ($issueDate) {

            try {

                $formattedIssueDate =
                    \Carbon\Carbon::parse($issueDate)
                        ->format('d/m/Y');

            } catch (\Throwable $e) {

                $formattedIssueDate =
                    $issueDate;
            }

        } else {

            $formattedIssueDate =
                now()->format('d/m/Y');
        }


        /*
        |--------------------------------------------------------------------------
        | CERTIFICATE NUMBER
        |--------------------------------------------------------------------------
        */

        $certificateNumber =
            $bonafideCertificate->certificate_number
            ?? '-';


        /*
        |--------------------------------------------------------------------------
        | PURPOSE / REASON
        |--------------------------------------------------------------------------
        */

        $purpose =
            $bonafideCertificate->purpose
            ?? $bonafideCertificate->reason
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | SCHOOL ADDRESS
        |--------------------------------------------------------------------------
        */

        $schoolAddress =
            $school->address
            ?? null;

        $schoolCity =
            $school->city
            ?? null;

        $schoolDistrict =
            $school->district
            ?? null;

        $schoolState =
            $school->state
            ?? null;

        $schoolPincode =
            $school->pincode
            ?? null;

        $schoolPhone =
            $school->phone
            ?? null;

        $schoolEmail =
            $school->email
            ?? null;

    @endphp


    {{-- 
    |--------------------------------------------------------------------------
    | PRINT BUTTON
    |--------------------------------------------------------------------------
    --}}

    <div class="print-button-wrapper no-print">

        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            Print Certificate
        </button>

    </div>


    {{-- 
    |--------------------------------------------------------------------------
    | CERTIFICATE
    |--------------------------------------------------------------------------
    --}}

    <div class="print-page">


        {{-- SCHOOL HEADER --}}

        <div class="school-header">

            <img
                src="{{ $logoUrl }}"
                alt="School Logo"
                class="school-logo"
            >

            <h2 class="school-name">
                {{ $school->school_name ?? 'School Name' }}
            </h2>


            <p class="school-address">

                @if($schoolAddress)
                    {{ $schoolAddress }}
                @endif

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

            </p>


            @if($schoolPhone || $schoolEmail)

                <div class="school-contact">

                    @if($schoolPhone)

                        <span>
                            Phone:
                            {{ $schoolPhone }}
                        </span>

                    @endif


                    @if($schoolPhone && $schoolEmail)

                        <span>
                            &nbsp; | &nbsp;
                        </span>

                    @endif


                    @if($schoolEmail)

                        <span>
                            Email:
                            {{ $schoolEmail }}
                        </span>

                    @endif

                </div>

            @endif

        </div>


        {{-- CERTIFICATE HEADING --}}

        <div class="certificate-heading">

            <h1>
                Bonafide Certificate
            </h1>

            <div class="certificate-subtitle">
                This certificate is issued by the school
            </div>

        </div>


        {{-- CERTIFICATE META --}}

        <div class="certificate-meta">

            <div class="certificate-number">

                Certificate No:
                {{ $certificateNumber }}

            </div>


            <div class="certificate-date">

                Date:
                {{ $formattedIssueDate }}

            </div>

        </div>


        {{-- MAIN CONTENT --}}

        <div class="certificate-content">

            <p>

                This is to certify that

                <span class="student-name">
                    {{ $studentName }}
                </span>

                is a bonafide student of

                <span class="school-name-inline">
                    {{ $school->school_name ?? 'the school' }}
                </span>.

            </p>


            <p>

                He/She is studying in

                <span class="class-value">
                    Class {{ $className }}
                </span>

                @if($section && $section !== '-')

                    -
                    <span class="class-value">
                        Section {{ $section }}
                    </span>

                @endif

                during the academic year

                <span class="academic-year">
                    {{ $academicYear }}
                </span>.

            </p>


            <p>

                As per the school records, the above information
                is true and correct to the best of our knowledge.

            </p>

        </div>


        {{-- STUDENT DETAILS --}}

        <table class="student-details">

            <thead>

                <tr>

                    <th>
                        Class
                    </th>

                    <th>
                        Section
                    </th>

                    <th>
                        Academic Year
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        {{ $className }}
                    </td>

                    <td>
                        {{ $section }}
                    </td>

                    <td>
                        {{ $academicYear }}
                    </td>

                </tr>

            </tbody>

        </table>


        {{-- PURPOSE --}}

        @if($purpose)

            <div class="purpose">

                <span class="purpose-label">
                    Purpose:
                </span>

                {{ $purpose }}

            </div>

        @endif


        {{-- SIGNATURES --}}

        <div class="signature-section">


            <div class="signature-box">

                <div class="signature-space"></div>

                <div class="signature-line"></div>

                <div class="signature-title">
                    Class Teacher
                </div>

            </div>


            <div class="signature-box">

                <div class="signature-space"></div>

                <div class="signature-line"></div>

                <div class="signature-title">
                    Principal
                </div>

            </div>


        </div>


    </div>


</body>

</html>