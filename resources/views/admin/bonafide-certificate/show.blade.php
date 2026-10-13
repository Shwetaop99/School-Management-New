@extends('layouts.app')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | CERTIFICATE
    |--------------------------------------------------------------------------
    */

    $bonafideCertificate = $certificate ?? null;


    /*
    |--------------------------------------------------------------------------
    | SCHOOL
    |--------------------------------------------------------------------------
    */

    $school = $schoolSetting
        ?? \App\Models\SchoolSetting::first();


    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */

    $student = $bonafideCertificate
        ? $bonafideCertificate->student
        : null;


    /*
    |--------------------------------------------------------------------------
    | STUDENT NAME
    |--------------------------------------------------------------------------
    */

    $fullName = trim(
        ($student ? $student->first_name : '') . ' ' .
        ($student ? $student->middle_name : '') . ' ' .
        ($student ? $student->last_name : '')
    );

    if (!$fullName && $student) {
        $fullName = $student->name ?? '';
    }


    /*
    |--------------------------------------------------------------------------
    | DATE OF BIRTH
    |--------------------------------------------------------------------------
    */

    $dob = $student
        ? ($student->date_of_birth ?? $student->dob ?? null)
        : null;

    $formattedDob = '';

    if ($dob) {

        try {

            $formattedDob =
                \Carbon\Carbon::parse($dob)
                    ->format('d/m/Y');

        } catch (\Throwable $e) {

            $formattedDob = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRATION NUMBER
    |--------------------------------------------------------------------------
    */

    $registrationNo = $student
        ? ($student->register_no
            ?? $student->registration_no
            ?? '')
        : '';


    /*
    |--------------------------------------------------------------------------
    | AADHAR NUMBER
    |--------------------------------------------------------------------------
    */

    $aadharNo = $student
        ? ($student->aadhar_card_no
            ?? $student->aadhar_no
            ?? '')
        : '';


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    $academicYear = $student
        ? ($student->educational_year
            ?? $student->academic_year
            ?? '')
        : '';

    if (!$academicYear && $bonafideCertificate) {

        $academicYear =
            $bonafideCertificate->academic_year
            ?? '';

    }


    /*
    |--------------------------------------------------------------------------
    | CLASS
    |--------------------------------------------------------------------------
    */

    $className = '';

    if ($student) {

        $className =
            $student->class
            ?? $student->class_name
            ?? $student->admission_class
            ?? '';

    }


    /*
    |--------------------------------------------------------------------------
    | SECTION / DIVISION
    |--------------------------------------------------------------------------
    */

    $section = $student
        ? ($student->section
            ?? $student->division
            ?? '')
        : '';

    $division = $section;


    /*
    |--------------------------------------------------------------------------
    | MOTHER NAME
    |--------------------------------------------------------------------------
    */

    $motherName = $student
        ? ($student->mother_name ?? '')
        : '';


    /*
    |--------------------------------------------------------------------------
    | RELIGION
    |--------------------------------------------------------------------------
    */

    $religion = $student
        ? ($student->religion ?? '')
        : '';


    /*
    |--------------------------------------------------------------------------
    | CASTE
    |--------------------------------------------------------------------------
    */

    $caste = $student
        ? ($student->caste ?? '')
        : '';


    /*
    |--------------------------------------------------------------------------
    | CASTE + RELIGION
    |--------------------------------------------------------------------------
    */

    $casteReligion = trim(
        $caste .
        ($caste && $religion ? ' / ' : '') .
        $religion
    );


    /*
    |--------------------------------------------------------------------------
    | REASON
    |--------------------------------------------------------------------------
    */

    $reason = $bonafideCertificate
        ? (
            $bonafideCertificate->reason
            ?: 'To avail of travel benefits'
        )
        : 'To avail of travel benefits';


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

@endphp


<div class="bonafide-wrapper">


    {{-- =========================================================
         ACTION BAR
    ========================================================== --}}

    <div class="certificate-actions no-print">

        <a
            href="{{ route('admin.bonafide.index') }}"
            class="btn btn-outline-secondary btn-sm"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>


        @if($bonafideCertificate)

            <div class="d-flex gap-2">

                <a
                    href="{{ route(
                        'admin.bonafide.edit',
                        $bonafideCertificate->id
                    ) }}"
                    class="btn btn-primary btn-sm"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                </a>


                <a
                    href="{{ route(
                        'admin.bonafide.print',
                        $bonafideCertificate->id
                    ) }}"
                    target="_blank"
                    class="btn btn-primary btn-sm"
                >
                    <i class="bi bi-printer me-1"></i>
                    Print Certificate
                </a>

            </div>

        @endif

    </div>


    {{-- =========================================================
         A4 LANDSCAPE CERTIFICATE
    ========================================================== --}}

    <div class="certificate-page">


        {{-- =====================================================
             SCHOOL HEADER
        ====================================================== --}}

        <div class="school-header">


            {{-- SCHOOL LOGO --}}

            <div class="school-logo">

                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $school->school_name ?? 'School Logo' }}"
                >

            </div>


            {{-- SCHOOL INFORMATION --}}

            <div class="school-information">


                {{-- SCHOOL NAME --}}

                <div class="school-name">

                    {{ $school->school_name ?? 'School Name' }}

                </div>


                {{-- ADDRESS --}}

                @if(
                    $school &&
                    (
                        $school->address ||
                        $school->city ||
                        $school->district ||
                        $school->state ||
                        $school->pincode
                    )
                )

                    <div class="school-line">

                        @if($school->address)

                            {{ $school->address }}

                        @endif


                        @if($school->city)

                            @if($school->address)
                                ,
                            @endif

                            {{ $school->city }}

                        @endif


                        @if($school->district)

                            @if(
                                $school->city ||
                                $school->address
                            )
                                ,
                            @endif

                            {{ $school->district }}

                        @endif


                        @if($school->state)

                            @if(
                                $school->district ||
                                $school->city ||
                                $school->address
                            )
                                ,
                            @endif

                            {{ $school->state }}

                        @endif


                        @if($school->pincode)

                            @if(
                                $school->state ||
                                $school->district ||
                                $school->city ||
                                $school->address
                            )
                                -
                            @endif

                            {{ $school->pincode }}

                        @endif

                    </div>

                @endif


                {{-- SCHOOL CODE / UDISE --}}

                @if(
                    $school &&
                    (
                        $school->school_code ||
                        $school->udise_code
                    )
                )

                    <div class="school-line school-code-line">

                        @if($school->school_code)

                            <span>
                                School Code :
                                {{ $school->school_code }}
                            </span>

                        @endif


                        @if($school->udise_code)

                            <span>
                                UDISE No :
                                {{ $school->udise_code }}
                            </span>

                        @endif

                    </div>

                @endif


                {{-- CONTACT DETAILS --}}

                @if(
                    $school &&
                    (
                        $school->phone ||
                        $school->email ||
                        $school->website
                    )
                )

                    <div class="school-line school-contact-line">

                        @if($school->phone)

                            <span>
                                Phone :
                                {{ $school->phone }}
                            </span>

                        @endif


                        @if($school->email)

                            <span>
                                Email :
                                {{ $school->email }}
                            </span>

                        @endif


                        @if($school->website)

                            <span>
                                Website :
                                {{ $school->website }}
                            </span>

                        @endif

                    </div>

                @endif


                {{-- PRINCIPAL --}}

                @if(
                    $school &&
                    $school->principal_name
                )

                    <div class="school-line">

                        Principal :
                        {{ $school->principal_name }}

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
             CERTIFICATE TITLE
        ====================================================== --}}

        <div class="certificate-title">

            Bonafide Certificate

        </div>


        {{-- =====================================================
             TOP INFORMATION
        ====================================================== --}}

        <div class="top-info">

            <div class="top-left">

                <strong>
                    Registration Number :
                </strong>

                <span>
                    {{ $registrationNo ?: '-' }}
                </span>

            </div>


            <div class="top-right">

                <strong>
                    Aadhar No :
                </strong>

                <span>
                    {{ $aadharNo ?: '-' }}
                </span>

            </div>

        </div>


        {{-- =====================================================
             CERTIFIED TEXT
        ====================================================== --}}

        <div class="certified-text">

            It is certified that

        </div>


        {{-- =====================================================
             STUDENT DETAILS
        ====================================================== --}}

        <div class="student-details">


            {{-- LEFT COLUMN --}}

            <div class="detail-left">


                <div class="detail-row">

                    <span class="label">
                        Full name of student :
                    </span>

                    <span class="value">
                        {{ $fullName ?: '-' }}
                    </span>

                </div>


                <div class="detail-row">

                    <span class="label">
                        Mother's name :
                    </span>

                    <span class="value">
                        {{ $motherName ?: '-' }}
                    </span>

                </div>


                <div class="detail-row">

                    <span class="label">
                        Academic Year :
                    </span>

                    <span class="value">
                        {{ $academicYear ?: '-' }}
                    </span>

                </div>


                <div class="detail-row">

                    <span class="label">
                        Caste and religion :
                    </span>

                    <span class="value">
                        {{ $casteReligion ?: '-' }}
                    </span>

                </div>


                <div class="detail-row">

                    <span class="label">
                        Reason :
                    </span>

                    <span class="value">
                        {{ $reason }}
                    </span>

                </div>

            </div>


            {{-- RIGHT COLUMN --}}

            <div class="detail-right">


                <div class="detail-row">

                    <span class="label">
                        Date of Birth :
                    </span>

                    <span class="value">
                        {{ $formattedDob ?: '-' }}
                    </span>

                </div>


                <div class="detail-row">

                    <span class="label">
                        Class :
                    </span>

                    <span class="value">
                        {{ $className ?: '-' }}
                    </span>

                </div>


                <div class="detail-row">

                    <span class="label">
                        Division :
                    </span>

                    <span class="value">
                        {{ $division ?: '-' }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CERTIFICATE PARAGRAPH
        ====================================================== --}}

        <div class="certificate-paragraph">

            This student belongs to our school and all the information
            given above is correct. According to our information, her
            behavior is satisfactory.

        </div>


        {{-- =====================================================
             SIGNATURE AREA
        ====================================================== --}}

        <div class="signature-area">


            {{-- PRINCIPAL --}}

            <div class="principal-signature">

                <div class="signature-space"></div>

                <div class="signature-line"></div>

                <div class="signature-label">

                    {{ $school->principal_name ?? 'Principal / Dean' }}

                </div>

                <div class="signature-role">

                    Principal / Head

                </div>

            </div>


            {{-- SCHOOL SEAL --}}

            <div class="school-signature">

                <div class="seal-space">

                    <span class="seal-circle">
                        SEAL
                    </span>

                </div>

                <div class="signature-label">

                    {{ $school->school_name ?? 'School' }}

                </div>

                <div class="official-seal">

                    (Official Seal)

                </div>

            </div>

        </div>


    </div>

</div>


<style>

/*
|--------------------------------------------------------------------------
| PAGE WRAPPER
|--------------------------------------------------------------------------
*/

.bonafide-wrapper {

    width: 100%;

    min-height: calc(100vh - 70px);

    padding: 25px;

    background: #f4f6f9;

    box-sizing: border-box;

}


/*
|--------------------------------------------------------------------------
| ACTION BAR
|--------------------------------------------------------------------------
*/

.certificate-actions {

    width: 100%;

    max-width: 1120px;

    margin: 0 auto 15px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 10px;

}


/*
|--------------------------------------------------------------------------
| A4 LANDSCAPE CERTIFICATE
|--------------------------------------------------------------------------
*/

.certificate-page {

    position: relative;

    width: 297mm;

    height: 210mm;

    min-height: 210mm;

    margin: 0 auto;

    background: #ffffff;

    border: 1px solid #111;

    padding: 13mm 12mm 10mm;

    box-sizing: border-box;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    color: #111;

    font-size: 12px;

    overflow: hidden;

}


/*
|--------------------------------------------------------------------------
| SCHOOL HEADER
|--------------------------------------------------------------------------
*/

.school-header {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 16px;

    text-align: center;

    padding-top: 2px;

    padding-bottom: 12px;

    border-bottom: 2px solid #111;

}


.school-logo {

    width: 70px;

    min-width: 70px;

    height: 70px;

    display: flex;

    align-items: center;

    justify-content: center;

}


.school-logo img {

    width: 65px;

    height: 65px;

    object-fit: contain;

}


.school-information {

    flex: 1;

    min-width: 0;

}


.school-name {

    font-size: 18px;

    font-weight: 800;

    margin-bottom: 4px;

    text-transform: uppercase;

    letter-spacing: .2px;

}


.school-line {

    font-size: 9px;

    font-weight: 600;

    line-height: 14px;

}


.school-code-line {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 15px;

}


.school-contact-line {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 15px;

}


/*
|--------------------------------------------------------------------------
| CERTIFICATE TITLE
|--------------------------------------------------------------------------
*/

.certificate-title {

    position: relative;

    width: max-content;

    margin: -12px auto 12px;

    padding: 5px 20px;

    background: #000;

    color: #fff;

    border-radius: 12px;

    font-size: 10px;

    font-weight: 700;

    line-height: 1;

    z-index: 2;

}


/*
|--------------------------------------------------------------------------
| TOP INFORMATION
|--------------------------------------------------------------------------
*/

.top-info {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 30px;

    margin-top: 3px;

    margin-bottom: 8px;

    font-size: 11px;

}


.top-left,
.top-right {

    display: flex;

    gap: 4px;

    align-items: baseline;

}


.top-right {

    justify-content: flex-start;

}


/*
|--------------------------------------------------------------------------
| CERTIFIED TEXT
|--------------------------------------------------------------------------
*/

.certified-text {

    text-align: center;

    font-size: 10px;

    margin: 0 0 8px;

}


/*
|--------------------------------------------------------------------------
| STUDENT DETAILS
|--------------------------------------------------------------------------
*/

.student-details {

    display: grid;

    grid-template-columns: 1.55fr 1fr;

    column-gap: 25px;

    font-size: 11px;

    line-height: 19px;

}


.detail-row {

    display: flex;

    align-items: baseline;

    min-height: 19px;

}


.detail-left .detail-row .label {

    min-width: 125px;

}


.detail-right .detail-row .label {

    min-width: 85px;

}


.label {

    white-space: nowrap;

    font-weight: 500;

}


.value {

    flex: 1;

    font-weight: 400;

    min-width: 0;

    word-break: break-word;

}


/*
|--------------------------------------------------------------------------
| CERTIFICATE PARAGRAPH
|--------------------------------------------------------------------------
*/

.certificate-paragraph {

    margin-top: 8px;

    font-size: 10.5px;

    line-height: 17px;

    text-align: left;

}


/*
|--------------------------------------------------------------------------
| SIGNATURE AREA
|--------------------------------------------------------------------------
*/

.signature-area {

    position: absolute;

    left: 12mm;

    right: 12mm;

    bottom: 9mm;

    display: flex;

    justify-content: space-between;

    align-items: flex-end;

    font-size: 10px;

}


.principal-signature {

    width: 35%;

    text-align: center;

}


.school-signature {

    width: 35%;

    text-align: center;

}


.signature-space {

    height: 24px;

}


.signature-line {

    width: 70%;

    margin: 0 auto 5px;

    border-top: 1px solid #111;

}


.signature-label {

    font-weight: 600;

}


.signature-role {

    font-size: 9px;

    margin-top: 2px;

}


.seal-space {

    height: 38px;

    display: flex;

    justify-content: center;

    align-items: center;

}


.seal-circle {

    width: 32px;

    height: 32px;

    border: 1px solid #111;

    border-radius: 50%;

    display: flex;

    justify-content: center;

    align-items: center;

    font-size: 7px;

    font-weight: 700;

}


.official-seal {

    font-size: 9px;

    margin-top: 2px;

}


/*
|--------------------------------------------------------------------------
| SCREEN
|--------------------------------------------------------------------------
*/

@media screen and (max-width: 1200px) {

    .bonafide-wrapper {

        overflow-x: auto;

    }

    .certificate-page {

        margin-left: 0;

        margin-right: 0;

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


    html {

        width: 297mm !important;

        height: 210mm !important;

        margin: 0 !important;

        padding: 0 !important;

    }


    body {

        width: 297mm !important;

        height: 210mm !important;

        margin: 0 !important;

        padding: 0 !important;

        background: #ffffff !important;

        overflow: hidden !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;

    }


    body * {

        visibility: hidden;

    }


    .certificate-page,
    .certificate-page * {

        visibility: visible;

    }


    .certificate-page {

        position: absolute !important;

        left: 0 !important;

        top: 0 !important;

        width: 297mm !important;

        height: 210mm !important;

        min-height: 210mm !important;

        margin: 0 !important;

        padding: 13mm 12mm 10mm !important;

        background: #ffffff !important;

        border: 1px solid #111 !important;

        box-sizing: border-box !important;

        box-shadow: none !important;

        overflow: hidden !important;

        page-break-after: always !important;

        break-after: page !important;

    }


    .bonafide-wrapper {

        width: 297mm !important;

        height: 210mm !important;

        min-height: 210mm !important;

        padding: 0 !important;

        margin: 0 !important;

        background: #ffffff !important;

        overflow: hidden !important;

    }


    .no-print {

        display: none !important;

        visibility: hidden !important;

    }


    .school-header {

        display: flex !important;

        align-items: center !important;

        justify-content: center !important;

    }


    .school-logo img {

        width: 65px !important;

        height: 65px !important;

    }


    .school-name {

        font-size: 18px !important;

    }

}

</style>

@endsection