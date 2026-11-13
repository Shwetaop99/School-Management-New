@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | School Profile
    |--------------------------------------------------------------------------
    */
    $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

    $schoolName = $school->school_name ?? 'Gurukul Vidyalay';
    $schoolAddress = $school->address ?? '';
    $schoolCity = $school->city ?? '';
    $schoolDistrict = $school->district ?? '';
    $schoolState = $school->state ?? '';
    $schoolPincode = $school->pincode ?? '';
    $schoolPhone = $school->phone ?? '';
    $schoolEmail = $school->email ?? '';
    $schoolUdise = $school->udise_code ?? '';
    $schoolCode = $school->school_code ?? '';
    $schoolLogo = $school->logo ?? null;

    /*
    |--------------------------------------------------------------------------
    | Build School Address
    |--------------------------------------------------------------------------
    */
    $schoolLocation = collect([
        $schoolCity,
        $schoolDistrict,
        $schoolState,
        $schoolPincode
    ])->filter()->implode(', ');

    /*
    |--------------------------------------------------------------------------
    | Academic Year
    |--------------------------------------------------------------------------
    */
    $academicYear = request('academic_year');

    if (!$academicYear) {
        $academicYear = $students->first()->academic_year ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Register Heading
    |--------------------------------------------------------------------------
    */
    $headingParts = [];

    if (request('class')) {
        $headingParts[] = 'CLASS ' . request('class');
    }

    if (request('section')) {
        $headingParts[] = 'SECTION ' . request('section');
    }

    if ($academicYear) {
        $headingParts[] = $academicYear;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper for DOB in Words
    |--------------------------------------------------------------------------
    */
    function generalRegisterDateInWords($date)
    {
        if (!$date) {
            return '—';
        }

        try {
            $date = \Carbon\Carbon::parse($date);

            $day = $date->format('j');
            $month = $date->format('F');
            $year = $date->format('Y');

            $dayWords = [
                1 => 'First',
                2 => 'Second',
                3 => 'Third',
                4 => 'Fourth',
                5 => 'Fifth',
                6 => 'Sixth',
                7 => 'Seventh',
                8 => 'Eighth',
                9 => 'Ninth',
                10 => 'Tenth',
                11 => 'Eleventh',
                12 => 'Twelfth',
                13 => 'Thirteenth',
                14 => 'Fourteenth',
                15 => 'Fifteenth',
                16 => 'Sixteenth',
                17 => 'Seventeenth',
                18 => 'Eighteenth',
                19 => 'Nineteenth',
                20 => 'Twentieth',
                21 => 'Twenty First',
                22 => 'Twenty Second',
                23 => 'Twenty Third',
                24 => 'Twenty Fourth',
                25 => 'Twenty Fifth',
                26 => 'Twenty Sixth',
                27 => 'Twenty Seventh',
                28 => 'Twenty Eighth',
                29 => 'Twenty Ninth',
                30 => 'Thirtieth',
                31 => 'Thirty First',
            ];

            return ($dayWords[(int) $day] ?? $day)
                . ' '
                . $month
                . ' '
                . $year;

        } catch (\Throwable $e) {
            return '—';
        }
    }
@endphp


{{-- ================================================================
     PRINT CONTROLS
================================================================ --}}
<div class="container-fluid py-3 no-print">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h4 class="fw-bold mb-1">
                Student General Register
            </h4>

            <p class="text-muted mb-0">
                Print Preview
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.student-general-register.index', request()->query()) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

            <button type="button"
                    onclick="window.print()"
                    class="btn btn-primary">

                <i class="bi bi-printer me-1"></i>
                Print

            </button>

        </div>

    </div>

</div>


{{-- ================================================================
     PRINT AREA
================================================================ --}}
<div class="gr-print-page">


    {{-- ============================================================
         SCHOOL HEADER
    ============================================================= --}}
    <div class="school-header">

        <div class="school-logo-wrapper">

            @if($schoolLogo)

                @php
                    $logoUrl = str_starts_with($schoolLogo, 'http')
                        ? $schoolLogo
                        : asset($schoolLogo);
                @endphp

                <img src="{{ $logoUrl }}"
                     class="school-logo"
                     alt="School Logo">

            @else

                <div class="school-logo-placeholder">
                    <i class="bi bi-mortarboard"></i>
                </div>

            @endif

        </div>


        <div class="school-information">

            <div class="school-name">
                {{ $schoolName }}
            </div>


            @if($schoolAddress)

                <div class="school-address">
                    {{ $schoolAddress }}
                </div>

            @endif


            @if($schoolLocation)

                <div class="school-address">
                    {{ $schoolLocation }}
                </div>

            @endif


            <div class="school-contact">

                @if($schoolPhone)

                    <span>
                        Phone: {{ $schoolPhone }}
                    </span>

                @endif


                @if($schoolEmail)

                    <span>
                        Email: {{ $schoolEmail }}
                    </span>

                @endif

            </div>


            <div class="school-codes">

                @if($schoolUdise)

                    <span>
                        UDISE Code:
                        <strong>{{ $schoolUdise }}</strong>
                    </span>

                @endif


                @if($schoolCode)

                    <span>
                        School Code:
                        <strong>{{ $schoolCode }}</strong>
                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- ============================================================
         DYNAMIC TITLE
    ============================================================= --}}
    <div class="register-heading">

        <h1>
            STUDENT GENERAL REGISTER
        </h1>


        @if(count($headingParts))

            <div class="register-filter-heading">

                {{ implode(' — ', $headingParts) }}

            </div>

        @endif

    </div>


    {{-- ============================================================
         FILTER INFORMATION
    ============================================================= --}}
    @if(request('class') || request('section') || request('search'))

        <div class="filter-summary">

            @if(request('class'))

                <span>
                    Class:
                    <strong>{{ request('class') }}</strong>
                </span>

            @endif


            @if(request('section'))

                <span>
                    Section:
                    <strong>{{ request('section') }}</strong>
                </span>

            @endif


            @if(request('search'))

                <span>
                    Search:
                    <strong>{{ request('search') }}</strong>
                </span>

            @endif

        </div>

    @endif


    {{-- ============================================================
         REGISTER TABLE
    ============================================================= --}}
    <div class="table-container">

        <table class="general-register-table">

            <thead>

                <tr>

                    <th rowspan="2" class="sr-col">
                        Sr.<br>No.
                    </th>

                    <th rowspan="2">
                        Register<br>Number
                    </th>

                    <th rowspan="2">
                        Book<br>Number
                    </th>

                    <th rowspan="2" class="name-col">
                        Student's Name
                    </th>

                    <th rowspan="2">
                        Student<br>ID
                    </th>

                    <th rowspan="2">
                        Aadhar<br>Number
                    </th>

                    <th rowspan="2" class="mother-col">
                        Mother's<br>Name
                    </th>

                    <th rowspan="2">
                        Nationality
                    </th>

                    <th rowspan="2">
                        Mother<br>Tongue
                    </th>

                    <th rowspan="2">
                        Religion
                    </th>

                    <th rowspan="2">
                        Caste
                    </th>

                    <th rowspan="2">
                        Sub<br>Caste
                    </th>

                    <th rowspan="2">
                        Admission<br>Date
                    </th>

                    <th rowspan="2">
                        Gender
                    </th>

                    <th rowspan="2">
                        Birth<br>Place
                    </th>

                    <th colspan="2">
                        Date of Birth
                    </th>

                    <th rowspan="2" class="previous-school-col">
                        Previous<br>School
                    </th>

                    <th rowspan="2">
                        Admission<br>Class
                    </th>

                    <th rowspan="2">
                        Current<br>Class
                    </th>

                    <th rowspan="2">
                        Section
                    </th>

                    <th rowspan="2">
                        State
                    </th>

                    <th rowspan="2">
                        Country
                    </th>

                </tr>


                <tr>

                    <th>
                        In Numbers
                    </th>

                    <th class="dob-words-col">
                        In Words
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($students as $student)

                    @php

                        $serialNumber = $loop->iteration;

                        $studentName =
                            $student->full_name
                            ?: trim(
                                ($student->first_name ?? '')
                                . ' '
                                . ($student->middle_name ?? '')
                                . ' '
                                . ($student->last_name ?? '')
                            );

                    @endphp


                    <tr>

                        {{-- Sr No --}}
                        <td class="text-center">
                            {{ $serialNumber }}
                        </td>


                        {{-- Register Number --}}
                        <td>
                            {{ $student->register_no ?: '—' }}
                        </td>


                        {{-- Book Number --}}
                        <td>
                            {{ $student->book_no ?: '—' }}
                        </td>


                        {{-- Student Name --}}
                        <td class="name-cell">
                            {{ $studentName ?: '—' }}
                        </td>


                        {{-- Student ID --}}
                        <td>
                            {{ $student->student_id ?: '—' }}
                        </td>


                        {{-- Aadhar --}}
                        <td>
                            {{ $student->aadhar_card_no ?: '—' }}
                        </td>


                        {{-- Mother Name --}}
                        <td class="name-cell">
                            {{ $student->mother_name ?: '—' }}
                        </td>


                        {{-- Nationality --}}
                        <td>
                            {{ $student->nationality ?: '—' }}
                        </td>


                        {{-- Mother Tongue --}}
                        <td>
                            {{ $student->mother_tongue ?: '—' }}
                        </td>


                        {{-- Religion --}}
                        <td>
                            {{ $student->religion ?: '—' }}
                        </td>


                        {{-- Caste --}}
                        <td>
                            {{ $student->caste ?: '—' }}
                        </td>


                        {{-- Sub Caste --}}
                        <td>
                            {{ $student->sub_caste ?: '—' }}
                        </td>


                        {{-- Admission Date --}}
                        <td class="nowrap">

                            @if($student->admission_date)

                                {{ $student->admission_date->format('d-m-Y') }}

                            @else

                                —

                            @endif

                        </td>


                        {{-- Gender --}}
                        <td>
                            {{ $student->gender ?: '—' }}
                        </td>


                        {{-- Birth Place --}}
                        <td>
                            {{ $student->birth_place ?: '—' }}
                        </td>


                        {{-- DOB --}}
                        <td class="nowrap">

                            @if($student->date_of_birth)

                                {{ $student->date_of_birth->format('d-m-Y') }}

                            @else

                                —

                            @endif

                        </td>


                        {{-- DOB Words --}}
                        <td class="dob-words">

                            {{ generalRegisterDateInWords($student->date_of_birth) }}

                        </td>


                        {{-- Previous School --}}
                        <td class="previous-school">

                            {{ $student->previous_school_name ?: '—' }}

                        </td>


                        {{-- Admission Class --}}
                        <td class="text-center">

                            {{ $student->admission_class ?: '—' }}

                        </td>


                        {{-- Current Class --}}
                        <td class="text-center">

                            {{ $student->class ?: '—' }}

                        </td>


                        {{-- Section --}}
                        <td class="text-center">

                            {{ $student->section ?: '—' }}

                        </td>


                        {{-- State --}}
                        <td>

                            {{ $student->state ?: '—' }}

                        </td>


                        {{-- Country --}}
                        <td>

                            {{ $student->country ?: '—' }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="23"
                            class="no-records">

                            No student records found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ============================================================
         FOOTER
    ============================================================= --}}
    <div class="register-footer">

        <div>
            Total Students:
            <strong>{{ $students->count() }}</strong>
        </div>

        <div>
            Printed On:
            <strong>{{ now()->format('d-m-Y h:i A') }}</strong>
        </div>

    </div>


    {{-- ============================================================
         SIGNATURE AREA
    ============================================================= --}}
    <div class="signature-section">

        <div class="signature-box">

            <div class="signature-line"></div>

            <strong>Class Teacher</strong>

        </div>


        <div class="signature-box">

            <div class="signature-line"></div>

            <strong>Head Clerk</strong>

        </div>


        <div class="signature-box">

            <div class="signature-line"></div>

            <strong>Head Master / Principal</strong>

        </div>

    </div>

</div>


{{-- ================================================================
     STYLES
================================================================ --}}
<style>

    * {
        box-sizing: border-box;
    }


    body {
        background: #f5f6f8;
    }


    .gr-print-page {
        background: #ffffff;
        width: 100%;
        margin: 0 auto;
        padding: 15px;
    }


    /* ============================================================
       SCHOOL HEADER
    ============================================================ */

    .school-header {
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
        margin-bottom: 7px;
    }


    .school-logo-wrapper {
        width: 85px;
        min-width: 85px;
        text-align: center;
        margin-right: 15px;
    }


    .school-logo {
        width: 75px;
        height: 75px;
        object-fit: contain;
    }


    .school-logo-placeholder {
        width: 65px;
        height: 65px;
        border: 1px solid #777;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin: auto;
    }


    .school-information {
        text-align: center;
        flex: 1;
    }


    .school-name {
        font-size: 22px;
        font-weight: 800;
        text-transform: uppercase;
        line-height: 1.2;
    }


    .school-address {
        font-size: 11px;
        margin-top: 2px;
    }


    .school-contact {
        display: flex;
        justify-content: center;
        gap: 20px;
        font-size: 10px;
        margin-top: 3px;
    }


    .school-codes {
        display: flex;
        justify-content: center;
        gap: 25px;
        font-size: 10px;
        margin-top: 3px;
    }


    /* ============================================================
       TITLE
    ============================================================ */

    .register-heading {
        text-align: center;
        margin: 7px 0;
    }


    .register-heading h1 {
        font-size: 17px;
        font-weight: 800;
        margin: 0;
        text-decoration: underline;
    }


    /*
    |--------------------------------------------------------------------------
    | NEW: DYNAMIC CLASS / SECTION / ACADEMIC YEAR HEADING
    |--------------------------------------------------------------------------
    */

    .register-filter-heading {
        font-size: 12px;
        font-weight: 700;
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }


    .filter-summary {
        display: flex;
        justify-content: center;
        gap: 25px;
        font-size: 10px;
        margin-bottom: 7px;
    }


    /* ============================================================
       TABLE
    ============================================================ */

    .table-container {
        width: 100%;
        overflow-x: visible;
    }


    .general-register-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 7.5px;
        color: #000;
    }


    .general-register-table th,
    .general-register-table td {
        border: 1px solid #000;
        padding: 3px 3px;
        vertical-align: middle;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }


    .general-register-table thead th {
        background: #eeeeee;
        font-weight: 700;
        text-align: center;
        line-height: 1.15;
    }


    .general-register-table tbody td {
        line-height: 1.2;
    }


    .general-register-table tbody tr {
        page-break-inside: avoid;
    }


    .general-register-table tbody tr:nth-child(even) {
        background: #fafafa;
    }


    .general-register-table .text-center {
        text-align: center;
    }


    .general-register-table .nowrap {
        white-space: nowrap;
    }


    /* ============================================================
       COLUMN WIDTHS
    ============================================================ */

    .sr-col {
        width: 28px;
    }


    .name-col {
        width: 105px;
    }


    .mother-col {
        width: 90px;
    }


    .previous-school-col {
        width: 105px;
    }


    .dob-words-col {
        width: 95px;
    }


    .general-register-table th:nth-child(2) {
        width: 55px;
    }


    .general-register-table th:nth-child(3) {
        width: 48px;
    }


    .general-register-table th:nth-child(5) {
        width: 58px;
    }


    .general-register-table th:nth-child(6) {
        width: 72px;
    }


    .general-register-table th:nth-child(8) {
        width: 55px;
    }


    .general-register-table th:nth-child(9) {
        width: 55px;
    }


    .general-register-table th:nth-child(10) {
        width: 55px;
    }


    .general-register-table th:nth-child(11) {
        width: 55px;
    }


    .general-register-table th:nth-child(12) {
        width: 55px;
    }


    .general-register-table th:nth-child(13) {
        width: 62px;
    }


    .general-register-table th:nth-child(14) {
        width: 45px;
    }


    .general-register-table th:nth-child(15) {
        width: 65px;
    }


    .general-register-table th:nth-child(16) {
        width: 62px;
    }


    .general-register-table th:nth-child(18) {
        width: 58px;
    }


    .general-register-table th:nth-child(19) {
        width: 58px;
    }


    .general-register-table th:nth-child(20) {
        width: 55px;
    }


    .general-register-table th:nth-child(21) {
        width: 45px;
    }


    .general-register-table th:nth-child(22) {
        width: 55px;
    }


    .general-register-table th:nth-child(23) {
        width: 55px;
    }


    .name-cell {
        font-weight: 600;
    }


    .previous-school {
        font-size: 7px;
    }


    .dob-words {
        font-size: 7px;
    }


    .no-records {
        text-align: center;
        padding: 20px !important;
        font-weight: 600;
    }


    /* ============================================================
       FOOTER
    ============================================================ */

    .register-footer {
        display: flex;
        justify-content: space-between;
        border-top: 1px solid #000;
        margin-top: 7px;
        padding-top: 5px;
        font-size: 9px;
    }


    /* ============================================================
       SIGNATURES
    ============================================================ */

    .signature-section {
        display: flex;
        justify-content: space-between;
        margin-top: 35px;
        padding: 0 30px;
    }


    .signature-box {
        width: 180px;
        text-align: center;
        font-size: 9px;
    }


    .signature-line {
        border-top: 1px solid #000;
        margin-bottom: 5px;
    }


    /* ============================================================
       SCREEN
    ============================================================ */

    @media screen {

        .gr-print-page {
            max-width: 1500px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

    }


    /* ============================================================
       PRINT
    ============================================================ */

    @media print {

        @page {
            size: A4 landscape;
            margin: 7mm;
        }


        html,
        body {
            width: 100%;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }


        .no-print,
        nav,
        header,
        footer,
        .sidebar,
        .navbar {
            display: none !important;
        }


        .gr-print-page {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }


        .school-header {
            page-break-inside: avoid;
        }


        .register-heading {
            page-break-inside: avoid;
        }


        .general-register-table {
            width: 100%;
        }


        .general-register-table thead {
            display: table-header-group;
        }


        .general-register-table tfoot {
            display: table-footer-group;
        }


        .general-register-table tr {
            page-break-inside: avoid;
        }


        .general-register-table th,
        .general-register-table td {
            border: 1px solid #000 !important;
        }


        .general-register-table thead th {
            background: #eeeeee !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }


        .general-register-table tbody tr:nth-child(even) {
            background: transparent !important;
        }


        .signature-section {
            page-break-inside: avoid;
        }

    }

</style>

@endsection