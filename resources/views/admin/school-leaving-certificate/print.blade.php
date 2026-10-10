@php

    $student = $certificate->student;

    /*
    |--------------------------------------------------------------------------
    | SCHOOL PROFILE
    |--------------------------------------------------------------------------
    */

    $schoolName = $school->school_name ?? '';
    $schoolAddress = $school->address ?? '';
    $schoolCity = $school->city ?? '';
    $schoolDistrict = $school->district ?? '';
    $schoolState = $school->state ?? '';
    $schoolPincode = $school->pincode ?? '';
    $schoolPhone = $school->phone ?? '';
    $schoolEmail = $school->email ?? '';
    $udiseCode = $school->udise_code ?? '';
    $schoolCode = $school->school_code ?? '';

    /*
    |--------------------------------------------------------------------------
    | SCHOOL LOGO
    |--------------------------------------------------------------------------
    */

    $schoolLogo = $school->logo ?? null;
    $schoolLogoUrl = null;

    if (!empty($schoolLogo)) {

        $schoolLogo = trim($schoolLogo);

        /*
        | Cloudinary / external URL
        */
        if (
            str_starts_with($schoolLogo, 'http://') ||
            str_starts_with($schoolLogo, 'https://')
        ) {

            $schoolLogoUrl = $schoolLogo;

        /*
        | Already starts with /
        */
        } elseif (str_starts_with($schoolLogo, '/')) {

            $schoolLogoUrl = $schoolLogo;

        /*
        | Laravel storage path
        */
        } elseif (str_starts_with($schoolLogo, 'storage/')) {

            $schoolLogoUrl = asset($schoolLogo);

        /*
        | Public images path
        */
        } elseif (str_starts_with($schoolLogo, 'images/')) {

            $schoolLogoUrl = asset($schoolLogo);

        /*
        | Normal uploaded file
        */
        } else {

            $schoolLogoUrl = asset(
                'storage/' . ltrim($schoolLogo, '/')
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT NAME
    |--------------------------------------------------------------------------
    */

    $fullName = trim(
        ($student->first_name ?? '') . ' ' .
        ($student->middle_name ?? '') . ' ' .
        ($student->last_name ?? '')
    );


    /*
    |--------------------------------------------------------------------------
    | DATE OF BIRTH
    |--------------------------------------------------------------------------
    */

    $dob = '';

    if (!empty($student->date_of_birth)) {

        try {

            $dob = \Illuminate\Support\Carbon::parse(
                $student->date_of_birth
            )->format('d-m-Y');

        } catch (\Throwable $e) {

            $dob = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CERTIFICATE DATE
    |--------------------------------------------------------------------------
    |
    | Supports certificate_date first because this is the field in the
    | current school leaving certificate table.
    |
    */

    $issueDate = '';

    $certificateDate =
        $certificate->certificate_date
        ?? $certificate->issue_date
        ?? null;

    if ($certificateDate) {

        try {

            $issueDate = \Illuminate\Support\Carbon::parse(
                $certificateDate
            )->format('d-m-Y');

        } catch (\Throwable $e) {

            $issueDate = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LEAVING DATE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | This comes ONLY from the certificate record.
    |
    | It does NOT use:
    | students.school_leaving_date
    |
    */

    $leavingDate = '';

    if (!empty($certificate->leaving_date)) {

        try {

            $leavingDate = \Illuminate\Support\Carbon::parse(
                $certificate->leaving_date
            )->format('d-m-Y');

        } catch (\Throwable $e) {

            $leavingDate = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ADMISSION DATE
    |--------------------------------------------------------------------------
    */

    $admissionDate = $student->admission_date ?? '';

    if ($admissionDate) {

        try {

            $admissionDate = \Illuminate\Support\Carbon::parse(
                $admissionDate
            )->format('d-m-Y');

        } catch (\Throwable $e) {

            // Keep original value.
        }

    }


    /*
    |--------------------------------------------------------------------------
    | DOB WORDS
    |--------------------------------------------------------------------------
    */

    $dobWords =
        $student->date_of_birth_words
        ?? $student->dob_in_words
        ?? '';


    /*
    |--------------------------------------------------------------------------
    | CASTE / SUB CASTE
    |--------------------------------------------------------------------------
    */

    $caste = $student->caste ?? '';
    $subCaste = $student->sub_caste ?? '';

    $casteSubCaste = trim(
        $caste .
        ($subCaste ? ' - ' . $subCaste : '')
    );


    /*
    |--------------------------------------------------------------------------
    | PREVIOUS SCHOOL
    |--------------------------------------------------------------------------
    */

    $previousSchool =
        $student->previous_school_name ?? '';

    $previousSchoolClass =
        $student->previous_school_class ?? '';

    $previousSchoolDisplay = trim(
        $previousSchool .
        ($previousSchoolClass
            ? ' - ' . $previousSchoolClass
            : '')
    );


    /*
    |--------------------------------------------------------------------------
    | STUDENT VALUES
    |--------------------------------------------------------------------------
    */

    $registerNo =
        $student->register_no ?? '';

    $studentId =
        $student->student_id ?? '';

    $admissionClass =
        $student->admission_class ?? '';

    $academicYear =
        $certificate->academic_year
        ?? $student->academic_year
        ?? '';

    $aadhar =
        $student->aadhar_card_no ?? '';

    $apparId =
        $student->appar_id ?? '';

    $penNo =
        $student->pen_no ?? '';

    $motherTongue =
        $student->mother_tongue ?? '';

    $motherName =
        $student->mother_name ?? '';

    $religion =
        $student->religion ?? '';

    $nationality =
        $student->nationality ?? 'Indian';

    $birthPlace =
        $student->birth_place ?? '';

    $medium =
        $student->medium ?? '';

    $bookNo =
        $student->book_no ?? '';

    $progress =
        $certificate->progress ?? '';

    $conduct =
        $certificate->conduct ?? '';

    $classSince =
        $student->class_studying_since ?? '';

    /*
    |--------------------------------------------------------------------------
    | REASON FOR LEAVING
    |--------------------------------------------------------------------------
    |
    | Current DB field:
    | reason_for_leaving
    |
    | leaving_reason is kept as fallback.
    |
    */

    $reason =
        $certificate->reason_for_leaving
        ?? $certificate->leaving_reason
        ?? '';

    /*
    |--------------------------------------------------------------------------
    | REMARKS
    |--------------------------------------------------------------------------
    */

    $remarks =
        $certificate->remarks ?? '';

@endphp


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        School Leaving Certificate
    </title>


    <style>

        /*
        ============================================================
        A4 PAGE
        ============================================================
        */

        @page {
            size: A4 portrait;
            margin: 0;
        }


        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            color: #000000;
        }


        body {
            font-size: 9px;
        }


        /*
        ============================================================
        PRINT BUTTON
        ============================================================
        */

        .print-toolbar {
            width: 210mm;
            margin: 10px auto;
            text-align: right;
        }


        .print-toolbar button {
            border: none;
            background: #0d6efd;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }


        /*
        ============================================================
        CERTIFICATE PAGE
        ============================================================
        */

        .certificate-page {
            width: 210mm;
            height: 297mm;
            padding: 2mm;
            margin: 0 auto;
            background: #ffffff;
        }


        /*
        ============================================================
        MAIN BORDER
        ============================================================
        */

        .certificate {
            width: 100%;
            height: 293mm;
            border: 1px solid #000;
            overflow: hidden;
        }


        /*
        ============================================================
        HEADER
        ============================================================
        */

        .school-header {
            text-align: center;
            padding-top: 6px;
            padding-bottom: 3px;
        }


        .school-logo {
            width: 55px;
            height: 55px;
            max-width: 55px;
            max-height: 55px;
            object-fit: contain;
            display: block;
            margin: 0 auto 2px auto;
        }


        .school-name {
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 7px 0;
        }


        .school-line {
            font-size: 8px;
            line-height: 1.4;
            margin: 0;
        }


        /*
        ============================================================
        HEADER TITLE
        ============================================================
        */

        .title-row {
            height: 22px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            position: relative;
            text-align: center;
        }


        .title {
            font-size: 10px;
            font-weight: bold;
            padding-top: 4px;
        }


        .not-generated {
            position: absolute;
            left: 7px;
            top: 5px;
            font-size: 8px;
        }


        .book-medium {
            position: absolute;
            right: 7px;
            top: 3px;
            font-size: 8px;
            line-height: 1.3;
            text-align: left;
        }


        /*
        ============================================================
        NOTICE
        ============================================================
        */

        .notice {
            border-bottom: 1px solid #000;
            text-align: center;
            padding: 4px 10px;
            font-size: 7px;
            line-height: 1.4;
        }


        /*
        ============================================================
        TOP STUDENT INFORMATION
        ============================================================
        */

        .top-info {
            width: 100%;
            border-collapse: collapse;
        }


        .top-info td {
            padding: 3px 7px;
            font-size: 8px;
            vertical-align: middle;
        }


        .top-info .col-1 {
            width: 33.33%;
        }


        .top-info .col-2 {
            width: 33.33%;
        }


        .top-info .col-3 {
            width: 33.33%;
        }


        /*
        ============================================================
        MAIN DETAILS
        ============================================================
        */

        .details {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }


        .details td {
            border: 1px solid #000;
            padding: 3px 7px;
            font-size: 9px;
            height: 20px;
            vertical-align: middle;
        }


        .details .label {
            width: 47%;
            font-weight: bold;
        }


        .details .value {
            width: 53%;
            font-weight: normal;
        }


        /*
        ============================================================
        DOB
        ============================================================
        */

        .dob-row td {
            height: 38px;
        }


        /*
        ============================================================
        DECLARATION
        ============================================================
        */

        .declaration {
            border-bottom: 1px solid #000;
            padding: 6px 10px;
            text-align: center;
            font-size: 8px;
            height: 30px;
            line-height: 1.5;
        }


        /*
        ============================================================
        BOTTOM SIGNATURES
        ============================================================
        */

        .bottom {
            width: 100%;
            border-collapse: collapse;
        }


        .bottom td {
            font-size: 8px;
            padding: 0;
            vertical-align: bottom;
        }


        .date-cell {
            width: 33.33%;
            padding-left: 18px !important;
        }


        .teacher-cell {
            width: 33.33%;
            text-align: center;
        }


        .principal-cell {
            width: 33.33%;
            text-align: center;
        }


        .signature-space {
            height: 38px;
        }


        /*
        ============================================================
        PRINT
        ============================================================
        */

        @media print {

            .print-toolbar {
                display: none !important;
            }


            html,
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }


            .certificate-page {
                width: 210mm;
                height: 297mm;
                padding: 2mm;
                margin: 0;
            }


            .certificate {
                width: 100%;
                height: 293mm;
            }

        }

    </style>

</head>


<body>


    {{-- =========================================================
         PRINT BUTTON
    ========================================================== --}}

    <div class="print-toolbar">

        <button onclick="window.print()">
            Print Certificate
        </button>

    </div>



    {{-- =========================================================
         CERTIFICATE
    ========================================================== --}}

    <div class="certificate-page">

        <div class="certificate">


            {{-- =================================================
                 SCHOOL PROFILE HEADER
            ================================================== --}}

            <div class="school-header">


                {{-- SCHOOL LOGO --}}

                @if($schoolLogoUrl)

                    <img
                        src="{{ $schoolLogoUrl }}"
                        class="school-logo"
                        alt="School Logo"
                        onerror="this.style.display='none';"
                    >

                @endif


                {{-- SCHOOL NAME --}}

                <div class="school-name">

                    {{ $schoolName }}

                </div>


                {{-- SCHOOL ADDRESS --}}

                <div class="school-line">

                    @if($schoolAddress)

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

                    @else

                        @if($schoolCity)
                            {{ $schoolCity }}
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

                    @endif

                </div>


                {{-- UDISE / SCHOOL CODE --}}

                <div class="school-line">

                    @if($udiseCode)

                        UDISE No : {{ $udiseCode }}

                    @endif


                    @if($schoolCode)

                        &nbsp; | &nbsp;

                        School Code : {{ $schoolCode }}

                    @endif

                </div>


                {{-- PHONE / EMAIL --}}

                <div class="school-line">

                    @if($schoolPhone)

                        Phone No : {{ $schoolPhone }}

                    @endif


                    @if($schoolEmail)

                        &nbsp; | &nbsp;

                        Email : {{ $schoolEmail }}

                    @endif

                </div>

            </div>



            {{-- =================================================
                 CERTIFICATE TITLE
            ================================================== --}}

            <div class="title-row">

                <div class="not-generated">

                    {{ $certificate->certificate_no ?: 'Not Generated' }}

                </div>


                <div class="title">

                    School Leaving Certificate

                </div>


                <div class="book-medium">

                    Book No :-

                    {{ $bookNo }}

                    <br>

                    Medium :-

                    {{ $medium }}

                </div>

            </div>



            {{-- =================================================
                 NOTICE
            ================================================== --}}

            <div class="notice">

                No changes are to be made in any entries,
                unless in writing by the authorized officer.

                <br>

                Teaching certificate with education will be taken.

            </div>



            {{-- =================================================
                 TOP INFORMATION
            ================================================== --}}

            <table class="top-info">

                <tr>

                    <td class="col-1">

                        Register No :
                        {{ $registerNo }}

                    </td>


                    <td class="col-2">

                        Admission date :
                        {{ $admissionDate }}

                    </td>


                    <td class="col-3">

                        Educational Year :
                        {{ $academicYear }}

                    </td>

                </tr>


                <tr>

                    <td class="col-1">

                        Student ID :
                        {{ $studentId }}

                    </td>


                    <td class="col-2">

                        Admission Class :
                        {{ $admissionClass }}

                    </td>


                    <td class="col-3">

                        Aadhar Card No :
                        {{ $aadhar }}

                    </td>

                </tr>


                <tr>

                    <td class="col-1">

                        Appar ID :
                        {{ $apparId }}

                    </td>


                    <td class="col-2">

                        Mother Tongue :
                        {{ $motherTongue }}

                    </td>


                    <td class="col-3">

                        PEN No :
                        {{ $penNo }}

                    </td>

                </tr>

            </table>



            {{-- =================================================
                 MAIN CERTIFICATE DATA
            ================================================== --}}

            <table class="details">

                <tr>

                    <td class="label">
                        Full Name of Student
                    </td>

                    <td class="value">
                        {{ $fullName }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Mother's Name
                    </td>

                    <td class="value">
                        {{ $motherName }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Religion
                    </td>

                    <td class="value">
                        {{ $religion }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Caste- Sub Caste
                    </td>

                    <td class="value">
                        {{ $casteSubCaste ?: '-' }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Nationality
                    </td>

                    <td class="value">
                        {{ $nationality }}
                    </td>

                </tr>


                <tr class="dob-row">

                    <td class="label">

                        Date of Birth

                        <br>

                        (Numerical and in words)

                    </td>

                    <td class="value">

                        {{ $dob }}

                        @if($dobWords)

                            <br>

                            {{ $dobWords }}

                        @endif

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Place of Birth
                    </td>

                    <td class="value">
                        {{ $birthPlace }}
                    </td>

                </tr>


                <tr>

                    <td class="label">

                        Previous School Name
                        <br>
                        and Class

                    </td>

                    <td class="value">

                        {{ $previousSchoolDisplay }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        School leaving date
                    </td>

                    <td class="value">

                        {{ $leavingDate }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Student's Progress
                    </td>

                    <td class="value">

                        {{ $progress }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Student's Conduct
                    </td>

                    <td class="value">

                        {{ $conduct }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Class in which studying, since when
                    </td>

                    <td class="value">

                        {{ $classSince }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Reason for Leaving
                    </td>

                    <td class="value">

                        {{ $reason }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Remarks
                    </td>

                    <td class="value">

                        {{ $remarks }}

                    </td>

                </tr>

            </table>



            {{-- =================================================
                 DECLARATION
            ================================================== --}}

            <div class="declaration">

                This is to certify that the above information
                is as per the records of the institution.

            </div>



            {{-- =================================================
                 SIGNATURES
            ================================================== --}}

            <table class="bottom">

                <tr>

                    <td class="date-cell">

                        <div class="signature-space"></div>

                        Date :
                        {{ $leavingDate ?: $issueDate }}

                    </td>


                    <td class="teacher-cell">

                        <div class="signature-space"></div>

                        Class Teacher Signature

                    </td>


                    <td class="principal-cell">

                        <div class="signature-space"></div>

                        Principal's Signature

                    </td>

                </tr>

            </table>


        </div>

    </div>


</body>

</html>
