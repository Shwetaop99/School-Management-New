
@extends('layouts.app')

@section('content')

@php

    $student = $certificate->student;

    /*
    |--------------------------------------------------------------------------
    | SCHOOL PROFILE
    |--------------------------------------------------------------------------
    */

    $schoolName =
        $school->school_name
        ?? '';

    $schoolAddress =
        $school->address
        ?? '';

    $schoolCity =
        $school->city
        ?? '';

    $schoolDistrict =
        $school->district
        ?? '';

    $schoolState =
        $school->state
        ?? '';

    $schoolPincode =
        $school->pincode
        ?? '';

    $schoolPhone =
        $school->phone
        ?? '';

    $schoolEmail =
        $school->email
        ?? '';

    $udiseCode =
        $school->udise_code
        ?? '';

    $schoolCode =
        $school->school_code
        ?? '';

    /*
    |--------------------------------------------------------------------------
    | SCHOOL LOGO
    |--------------------------------------------------------------------------
    */

    $schoolLogo =
        $school->logo
        ?? null;

    $schoolLogoUrl = null;

    if (!empty($schoolLogo)) {

        $schoolLogo = trim($schoolLogo);

        if (
            str_starts_with($schoolLogo, 'http://') ||
            str_starts_with($schoolLogo, 'https://')
        ) {

            // Cloudinary / external URL
            $schoolLogoUrl = $schoolLogo;

        } elseif (str_starts_with($schoolLogo, '/')) {

            // Already public URL
            $schoolLogoUrl = $schoolLogo;

        } elseif (str_starts_with($schoolLogo, 'storage/')) {

            // Laravel storage path
            $schoolLogoUrl = asset($schoolLogo);

        } elseif (str_starts_with($schoolLogo, 'images/')) {

            // Public images path
            $schoolLogoUrl = asset($schoolLogo);

        } else {

            // Normal Laravel storage filename/path
            $schoolLogoUrl = asset(
                'storage/' . ltrim($schoolLogo, '/')
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | SCHOOL LOCATION
    |--------------------------------------------------------------------------
    */

    $schoolLocationParts = [];

    if ($schoolCity) {
        $schoolLocationParts[] = $schoolCity;
    }

    if ($schoolDistrict) {
        $schoolLocationParts[] = $schoolDistrict;
    }

    if ($schoolState) {
        $schoolLocationParts[] = $schoolState;
    }

    $schoolLocation =
        implode(', ', $schoolLocationParts);

    if ($schoolPincode) {

        $schoolLocation .=
            ($schoolLocation ? ' - ' : '') .
            $schoolPincode;

    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT FULL NAME
    |--------------------------------------------------------------------------
    */

    $fullName = trim(
        ($student->first_name ?? '') . ' ' .
        ($student->middle_name ?? '') . ' ' .
        ($student->last_name ?? '')
    );


    /*
    |--------------------------------------------------------------------------
    | MARATHI NAME
    |--------------------------------------------------------------------------
    */

    $marathiName =
        $student->marathi_name
        ?? '';


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
    | CERTIFICATE / ISSUE DATE
    |--------------------------------------------------------------------------
    |
    | Supports:
    | certificate_date
    | issue_date
    |
    */

    $certificateDate =
        $certificate->certificate_date
        ?? $certificate->issue_date
        ?? null;

    $issueDate = '';

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
    | Only certificate.leaving_date is used.
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
    | REASON FOR LEAVING
    |--------------------------------------------------------------------------
    */

    $reason =
        $certificate->reason_for_leaving
        ?? $certificate->leaving_reason
        ?? '';


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    $statusClass = match ($certificate->status) {

        'issued' => 'success',

        'cancelled' => 'danger',

        default => 'warning',

    };

@endphp


<div class="container-fluid py-4">


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>

                School Leaving Certificate

            </h3>


            <p class="text-muted mb-0">

                Certificate No:

                <strong>
                    {{ $certificate->certificate_no }}
                </strong>

            </p>

        </div>


        <div class="d-flex gap-2">


            <a href="{{ route('admin.school-leaving-certificate.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back

            </a>


            <a href="{{ route('admin.school-leaving-certificate.edit', $certificate) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil-square me-1"></i>

                Edit

            </a>


            <a href="{{ route('admin.school-leaving-certificate.print', $certificate) }}"
               target="_blank"
               class="btn btn-dark">

                <i class="bi bi-printer me-1"></i>

                Print

            </a>

        </div>

    </div>



    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif



    {{-- =========================================================
         CERTIFICATE STATUS
    ========================================================== --}}

    <div class="mb-4">

        <span class="badge bg-{{ $statusClass }} px-3 py-2 fs-6">

            @if($certificate->status === 'issued')

                <i class="bi bi-check-circle me-1"></i>

            @elseif($certificate->status === 'cancelled')

                <i class="bi bi-x-circle me-1"></i>

            @else

                <i class="bi bi-pencil-square me-1"></i>

            @endif

            {{ ucfirst($certificate->status) }}

        </span>

    </div>



    {{-- =========================================================
         CERTIFICATE CARD
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">


            {{-- =================================================
                 SCHOOL HEADER
            ================================================== --}}

            <div class="certificate-preview">


                <div class="school-header text-center">


                    {{-- SCHOOL LOGO --}}

                    @if($schoolLogoUrl)

                        <div class="mb-3">

                            <img
                                src="{{ $schoolLogoUrl }}"
                                alt="School Logo"
                                style="max-height:90px; max-width:150px;"
                                onerror="this.style.display='none';"
                            >

                        </div>

                    @endif



                    {{-- SCHOOL NAME --}}

                    <h2 class="fw-bold mb-1">

                        {{ $schoolName ?: 'School Name' }}

                    </h2>



                    {{-- SCHOOL ADDRESS --}}

                    @if($schoolAddress || $schoolLocation)

                        <p class="mb-1 text-muted">

                            @if($schoolAddress)

                                {{ $schoolAddress }}

                            @endif

                            @if($schoolAddress && $schoolLocation)

                                ,

                            @endif

                            {{ $schoolLocation }}

                        </p>

                    @endif



                    {{-- UDISE / SCHOOL CODE --}}

                    @if($udiseCode || $schoolCode)

                        <p class="mb-1 text-muted">

                            @if($udiseCode)

                                UDISE No : {{ $udiseCode }}

                            @endif

                            @if($udiseCode && $schoolCode)

                                &nbsp; | &nbsp;

                            @endif

                            @if($schoolCode)

                                School Code : {{ $schoolCode }}

                            @endif

                        </p>

                    @endif



                    {{-- PHONE / EMAIL --}}

                    @if($schoolPhone || $schoolEmail)

                        <p class="mb-1 text-muted">

                            @if($schoolPhone)

                                Phone No : {{ $schoolPhone }}

                            @endif

                            @if($schoolPhone && $schoolEmail)

                                &nbsp; | &nbsp;

                            @endif

                            @if($schoolEmail)

                                Email : {{ $schoolEmail }}

                            @endif

                        </p>

                    @endif



                    <p class="mb-3 text-muted">

                        School Leaving Certificate

                    </p>



                    <div class="border-top border-bottom py-2 mb-4">


                        <div class="row">


                            <div class="col-md-4 text-start">

                                <strong>
                                    Certificate No:
                                </strong>

                                {{ $certificate->certificate_no }}

                            </div>


                            <div class="col-md-4 text-center">

                                <strong>
                                    Academic Year:
                                </strong>

                                {{ $certificate->academic_year ?: '—' }}

                            </div>


                            <div class="col-md-4 text-end">

                                <strong>
                                    Issue Date:
                                </strong>

                                {{ $issueDate ?: '—' }}

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     STUDENT INFORMATION
                ================================================== --}}

                <div class="section-title">

                    Student Information

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered certificate-table mb-4">

                        <tbody>


                            <tr>

                                <th width="22%">
                                    Student ID
                                </th>

                                <td width="28%">
                                    {{ $student->student_id ?? '—' }}
                                </td>


                                <th width="22%">
                                    GR / Register No.
                                </th>

                                <td width="28%">
                                    {{ $student->register_no ?? '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Student Name
                                </th>

                                <td>
                                    {{ $fullName ?: '—' }}
                                </td>


                                <th>
                                    Marathi Name
                                </th>

                                <td>
                                    {{ $marathiName ?: '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Date of Birth
                                </th>

                                <td>
                                    {{ $dob ?: '—' }}
                                </td>


                                <th>
                                    Gender
                                </th>

                                <td>
                                    {{ $student->gender ?? '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Aadhaar Number
                                </th>

                                <td>
                                    {{ $student->aadhar_card_no ?? '—' }}
                                </td>


                                <th>
                                    PEN Number
                                </th>

                                <td>
                                    {{ $student->pen_no ?? '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    APAAR ID
                                </th>

                                <td>
                                    {{ $student->appar_id ?? '—' }}
                                </td>


                                <th>
                                    Saral ID
                                </th>

                                <td>
                                    {{ $student->saral_id ?? '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Nationality
                                </th>

                                <td>
                                    {{ $student->nationality ?? 'Indian' }}
                                </td>


                                <th>
                                    Mother Tongue
                                </th>

                                <td>
                                    {{ $student->mother_tongue ?? '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Religion
                                </th>

                                <td>
                                    {{ $student->religion ?? '—' }}
                                </td>


                                <th>
                                    Category
                                </th>

                                <td>
                                    {{ $student->category ?? '—' }}
                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                     ACADEMIC INFORMATION
                ================================================== --}}

                <div class="section-title">

                    Academic Information

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered certificate-table mb-4">

                        <tbody>


                            <tr>

                                <th width="22%">
                                    Admission Class
                                </th>

                                <td width="28%">
                                    {{ $student->admission_class ?? '—' }}
                                </td>


                                <th width="22%">
                                    Current / Last Class
                                </th>

                                <td width="28%">

                                    {{ $certificate->last_class
                                        ?: ($student->class ?? '—') }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Division
                                </th>

                                <td>

                                    {{ $certificate->last_division
                                        ?: ($student->section ?? '—') }}

                                </td>


                                <th>
                                    Roll Number
                                </th>

                                <td>
                                    {{ $student->roll_number ?? '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Medium
                                </th>

                                <td>
                                    {{ $student->medium ?? '—' }}
                                </td>


                                <th>
                                    Stream
                                </th>

                                <td>
                                    {{ $student->stream ?? '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Admission Type
                                </th>

                                <td>
                                    {{ $student->admission_type ?? '—' }}
                                </td>


                                <th>
                                    Academic Year
                                </th>

                                <td>
                                    {{ $certificate->academic_year ?? '—' }}
                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                     LEAVING INFORMATION
                ================================================== --}}

                <div class="section-title">

                    Leaving Information

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered certificate-table mb-4">

                        <tbody>


                            <tr>

                                <th width="25%">
                                    Date of Leaving
                                </th>

                                <td width="25%">
                                    {{ $leavingDate ?: '—' }}
                                </td>


                                <th width="25%">
                                    Reason for Leaving
                                </th>

                                <td width="25%">
                                    {{ $reason ?: '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Conduct
                                </th>

                                <td>
                                    {{ $certificate->conduct ?: '—' }}
                                </td>


                                <th>
                                    Progress
                                </th>

                                <td>
                                    {{ $certificate->progress ?: '—' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Certificate Status
                                </th>

                                <td>
                                    {{ ucfirst($certificate->status) }}
                                </td>


                                <th>
                                    Certificate Issue Date
                                </th>

                                <td>
                                    {{ $issueDate ?: '—' }}
                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                     REMARKS
                ================================================== --}}

                @if($certificate->remarks)

                    <div class="section-title">

                        Remarks

                    </div>


                    <div class="border rounded p-3 mb-4">

                        {!! nl2br(e($certificate->remarks)) !!}

                    </div>

                @endif



                {{-- =================================================
                     DECLARATION
                ================================================== --}}

                <div class="certificate-declaration mt-4">

                    <p class="mb-0">

                        This is to certify that the above information
                        is recorded in the school records and this
                        School Leaving Certificate has been issued
                        accordingly.

                    </p>

                </div>



                {{-- =================================================
                     SIGNATURES
                ================================================== --}}

                <div class="row mt-5 pt-4">


                    <div class="col-md-4 text-center">

                        <div class="signature-line"></div>

                        <strong>
                            Class Teacher
                        </strong>

                    </div>


                    <div class="col-md-4 text-center">

                        <div class="signature-line"></div>

                        <strong>
                            Clerk
                        </strong>

                    </div>


                    <div class="col-md-4 text-center">

                        <div class="signature-line"></div>

                        <strong>
                            Principal
                        </strong>

                    </div>


                </div>


            </div>

        </div>

    </div>

</div>



{{-- =============================================================
     PAGE STYLES
============================================================= --}}

<style>

.certificate-preview {
    background: #fff;
    border: 1px solid #dee2e6;
    padding: 40px;
    border-radius: 8px;
}

.school-header h2 {
    letter-spacing: 1px;
}

.section-title {
    background: #f1f3f5;
    border-left: 4px solid #0d6efd;
    padding: 10px 14px;
    font-weight: 700;
    font-size: 17px;
    margin-bottom: 0;
}

.certificate-table {
    margin-bottom: 0;
}

.certificate-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.certificate-table td,
.certificate-table th {
    padding: 10px 12px;
    vertical-align: middle;
}

.certificate-declaration {
    font-size: 15px;
    line-height: 1.8;
    text-align: justify;
}

.signature-line {
    border-top: 1px solid #333;
    width: 75%;
    margin: 0 auto 8px;
    height: 25px;
}

@media (max-width: 768px) {

    .certificate-preview {
        padding: 20px;
    }

}

</style>

@endsection
