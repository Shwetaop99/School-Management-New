
@extends('layouts.app')

@section('title', 'School Leaving Certificate | Admin')

@section('content')

@php
    $student = $certificate->student;

    // School profile
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

    // School logo
    $schoolLogo = trim((string) ($school->logo ?? ''));
    $schoolLogoUrl = null;

    if ($schoolLogo !== '') {
        if (preg_match('/^https?:\/\//i', $schoolLogo)) {
            $schoolLogoUrl = $schoolLogo;
        } elseif (str_starts_with($schoolLogo, '/')) {
            $schoolLogoUrl = $schoolLogo;
        } elseif (
            str_starts_with($schoolLogo, 'storage/') ||
            str_starts_with($schoolLogo, 'images/')
        ) {
            $schoolLogoUrl = asset($schoolLogo);
        } else {
            $schoolLogoUrl = asset('storage/' . ltrim($schoolLogo, '/'));
        }
    }

    // Date formatter
    $formatDate = function ($value) {
        if (empty($value)) {
            return '';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->format('d-m-Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    };

    // Student details
    $fullName = trim(implode(' ', array_filter([
        $student->first_name ?? '',
        $student->middle_name ?? '',
        $student->last_name ?? '',
    ], fn ($value) => trim((string) $value) !== '')));

    $dob = $formatDate($student->date_of_birth ?? null);

    $issueDate = $formatDate(
        $certificate->certificate_date
        ?? $certificate->issue_date
        ?? null
    );

    $leavingDate = $formatDate($certificate->leaving_date ?? null);
    $admissionDate = $formatDate($student->admission_date ?? null);

    $dobWords = $student->date_of_birth_words
        ?? $student->dob_in_words
        ?? '';

    $casteSubCaste = trim(
        ($student->caste ?? '') .
        (!empty($student->sub_caste)
            ? ' - ' . $student->sub_caste
            : '')
    );

    $previousSchoolDisplay = trim(
        ($student->previous_school_name ?? '') .
        (!empty($student->previous_school_class)
            ? ' - ' . $student->previous_school_class
            : '')
    );

    $registerNo = $student->register_no ?? '';
    $studentId = $student->student_id ?? '';
    $admissionClass = $student->admission_class ?? '';

    $academicYear = $certificate->academic_year
        ?? $student->academic_year
        ?? '';

    $aadhar = $student->aadhar_card_no ?? '';
    $apparId = $student->appar_id ?? '';
    $penNo = $student->pen_no ?? '';

    $motherTongue = $student->mother_tongue ?? '';
    $motherName = $student->mother_name ?? '';
    $religion = $student->religion ?? '';
    $nationality = $student->nationality ?? 'Indian';
    $birthPlace = $student->birth_place ?? '';
    $medium = $student->medium ?? '';

    $progress = $certificate->progress ?? '';
    $conduct = $certificate->conduct ?? '';
    $classSince = $student->class_studying_since ?? '';

    $reason = $certificate->reason_for_leaving
        ?? $certificate->leaving_reason
        ?? '';

    $remarks = $certificate->remarks ?? '';

    // School address
    $schoolAddressLine = implode(', ', array_filter([
        $schoolAddress,
        $schoolCity,
        $schoolDistrict,
        $schoolState,
    ], fn ($value) => trim((string) $value) !== ''));

    if ($schoolPincode !== '') {
        $schoolAddressLine .= ($schoolAddressLine !== '' ? ' - ' : '')
            . $schoolPincode;
    }

    // Certificate status
    $statusClass = match ($certificate->status) {
        'issued' => 'issued',
        'cancelled' => 'cancelled',
        default => 'draft',
    };
@endphp

<style>
    /* =====================================================
       SCHOOL LEAVING CERTIFICATE SHOW PAGE
       SAME DESIGN AS PRINT CERTIFICATE
    ===================================================== */

    .slc-show-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding: 20px 12px 40px;
        background: #f5f6f4;
        color: #111;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
    }

    .slc-show-page *,
    .slc-show-page *::before,
    .slc-show-page *::after {
        box-sizing: border-box;
    }

    /* TOP ACTION BAR */

    .slc-action-bar {
        max-width: 1100px;
        margin: 0 auto 18px;
        padding: 13px 16px;
        background: #fff;
        border: 1px solid #dfe7df;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .slc-action-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #245c35;
    }

    .slc-action-subtitle {
        margin-top: 4px;
        color: #777;
        font-size: 11px;
    }

    .slc-action-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .slc-action-btn {
        min-height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        border: 1px solid transparent;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .slc-back-btn {
        color: #444;
        background: #fff;
        border-color: #ddd;
    }

    .slc-back-btn:hover {
        background: #f2f2f2;
        color: #222;
    }

    .slc-edit-btn {
        color: #fff;
        background: #34775a;
        border-color: #34775a;
    }

    .slc-edit-btn:hover {
        background: #245c35;
        color: #fff;
    }

    .slc-print-btn {
        color: #245c35;
        background: #e8f5e9;
        border-color: #8ab795;
    }

    .slc-print-btn:hover {
        color: #174b29;
        background: #d8eddb;
    }

    /* STATUS */

    .slc-status-row {
        max-width: 1100px;
        margin: 0 auto 12px;
    }

    .slc-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 13px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .slc-status.issued {
        color: #245c35;
        background: #e8f5e9;
        border: 1px solid #8ab795;
    }

    .slc-status.draft {
        color: #87582d;
        background: #fff4df;
        border: 1px solid #e4c58d;
    }

    .slc-status.cancelled {
        color: #963c35;
        background: #fff0ee;
        border: 1px solid #e8b8b0;
    }

    .slc-success {
        max-width: 1100px;
        margin: 0 auto 15px;
        padding: 12px 15px;
        background: #e8f5e9;
        border: 1px solid #8ab795;
        border-radius: 6px;
        color: #245c35;
        font-size: 12px;
    }

    /* CERTIFICATE OUTER PAGE */

    .certificate-page {
        width: 194mm;
        min-height: 281mm;
        margin: 0 auto;
        padding: 4mm;
        border: 3px double #38734a;
        background: #f4fbf2;
        position: relative;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .10);
    }

    .inner-border {
        border: 1px solid #8ab795;
        min-height: 271mm;
        padding: 3mm;
        display: flex;
        flex-direction: column;
        background: #fafff8;
    }

    /* SCHOOL HEADER */

    .school-header {
        display: grid;
        grid-template-columns: 25mm minmax(0, 1fr) 25mm;
        align-items: center;
        gap: 3mm;
        padding: 2mm 1mm 3mm;
        text-align: center;
    }

    .logo-area {
        display: flex;
        justify-content: center;
        align-items: center;
        min-width: 0;
    }

    .school-logo {
        display: block;
        width: 23mm;
        height: 23mm;
        object-fit: contain;
    }

    .school-heading {
        min-width: 0;
    }

    .school-name {
        margin: 0 0 2mm;
        color: #245c35;
        font-size: 18px;
        line-height: 1.2;
        font-weight: 800;
        text-transform: uppercase;
        overflow-wrap: anywhere;
    }

    .school-line {
        margin: 1px 0;
        font-size: 9px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .school-contact {
        font-size: 8px;
    }

    .school-spacer {
        min-width: 0;
    }

    .header-divider {
        border: 0;
        border-top: 1.5px solid #38734a;
        margin: 0;
    }

    /* CERTIFICATE HEADING */

    .certificate-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 4mm;
        padding: 3mm 1mm 2mm;
        font-size: 9px;
    }

    .certificate-number {
        max-width: 35%;
        overflow-wrap: anywhere;
    }

    .certificate-title {
        margin: 0;
        text-align: center;
        color: #245c35;
        font-size: 17px;
        line-height: 1.25;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .certificate-subtitle {
        margin-top: 2px;
        font-size: 9px;
        font-weight: 600;
        text-align: center;
    }

    .book-info {
        text-align: right;
        max-width: 35%;
    }

    .notice {
        margin: 2mm 0;
        padding: 2mm;
        border-top: 1px solid #8ab795;
        border-bottom: 1px solid #8ab795;
        background: #e8f5e9;
        text-align: center;
        font-size: 9px;
        font-style: italic;
    }

    /* REFERENCE TABLE */

    .reference-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin: 1mm 0 3mm;
    }

    .reference-table td {
        width: 33.333%;
        padding: 2mm 1.5mm;
        border: 1px solid #8ab795;
        background: #f0f8ef;
        vertical-align: top;
        overflow-wrap: anywhere;
        font-size: 9px;
    }

    .field-label {
        font-weight: 700;
    }

    /* DETAILS TABLE */

    .details-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .details-table tr {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .details-table td {
        border: 1px solid #8ab795;
        padding: 2.1mm 2mm;
        vertical-align: middle;
        overflow-wrap: anywhere;
        word-break: normal;
        font-size: 10px;
    }

    .details-table .label {
        width: 43%;
        font-weight: 700;
        background: #e8f5e9;
    }

    .details-table .value {
        width: 57%;
        min-height: 8mm;
        background: #fafff8;
    }

    .details-table .dob-row td {
        padding-top: 3mm;
        padding-bottom: 3mm;
    }

    .muted {
        font-size: 9px;
        font-weight: 400;
        font-style: italic;
    }

    /* DECLARATION */

    .declaration {
        margin-top: 4mm;
        padding: 2mm 1mm;
        text-align: center;
        font-size: 9px;
        line-height: 1.6;
    }

    /* SIGNATURES */

    .signature-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: auto;
    }

    .signature-table td {
        width: 33.333%;
        padding: 0 2mm;
        text-align: center;
        vertical-align: bottom;
        font-size: 9px;
    }

    .signature-space {
        height: 22mm;
    }

    .signature-line {
        border-top: 1px solid #38734a;
        padding-top: 2mm;
        font-weight: 700;
    }

    .date-value {
        margin-bottom: 2mm;
        text-align: left;
        font-size: 9px;
    }

    .seal-space {
        height: 13mm;
        margin-bottom: 2mm;
        font-size: 8px;
        font-weight: 400;
    }

    .signature-note {
        margin-top: 1mm;
        font-size: 8px;
        font-weight: 400;
    }

    /* RESPONSIVE PREVIEW */

    @media screen and (max-width: 850px) {
        .certificate-page {
            transform-origin: top left;
            margin-left: 0;
            margin-right: 0;
        }

        .slc-certificate-scroll {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 15px;
        }
    }

    @media screen and (max-width: 600px) {
        .slc-show-page {
            padding: 12px 6px 25px;
        }

        .slc-action-bar {
            align-items: flex-start;
        }

        .slc-action-buttons {
            width: 100%;
        }

        .slc-action-btn {
            flex: 1;
            padding: 8px 9px;
        }
    }

    /* PRINT */

    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            width: auto;
            min-height: 0;
            margin: 0;
            padding: 0;
            background: #fff !important;
        }

        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .slc-action-bar,
        .slc-status-row,
        .slc-success {
            display: none !important;
        }

        .slc-show-page {
            width: 100%;
            min-height: 0;
            padding: 0;
            margin: 0;
            background: #fff !important;
        }

        .slc-certificate-scroll {
            overflow: visible !important;
            width: 100%;
            padding: 0;
        }

        .certificate-page {
            width: 100%;
            min-height: 0;
            margin: 0;
            padding: 0;
            border: 3px double #38734a;
            box-shadow: none;
            background: #f4fbf2 !important;
        }

        .inner-border {
            min-height: 278mm;
            background: #fafff8 !important;
        }

        .certificate-page,
        .inner-border,
        .school-header,
        .certificate-meta,
        .notice,
        .reference-table,
        .details-table tr,
        .declaration,
        .signature-table {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .certificate-page,
        .inner-border,
        .reference-table td,
        .details-table td,
        .notice {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div class="slc-show-page">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="slc-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- TOP ACTION BAR --}}
    <div class="slc-action-bar">

        <div>
            <h2 class="slc-action-title">
                School Leaving Certificate
            </h2>

            <div class="slc-action-subtitle">
                Certificate No:
                <strong>{{ $certificate->certificate_no ?: 'Not Generated' }}</strong>
            </div>
        </div>

        <div class="slc-action-buttons">

            <a href="{{ route('admin.school-leaving-certificate.index') }}"
               class="slc-action-btn slc-back-btn">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.school-leaving-certificate.edit', $certificate) }}"
               class="slc-action-btn slc-edit-btn">
                <i class="bi bi-pencil-square"></i>
                Edit
            </a>

            <a href="{{ route('admin.school-leaving-certificate.print', $certificate) }}"
               target="_blank"
               class="slc-action-btn slc-print-btn">
                <i class="bi bi-printer"></i>
                Print
            </a>

        </div>
    </div>

    {{-- STATUS --}}
    <div class="slc-status-row">
        <span class="slc-status {{ $statusClass }}">
            @if($certificate->status === 'issued')
                <i class="bi bi-check-circle"></i>
            @elseif($certificate->status === 'cancelled')
                <i class="bi bi-x-circle"></i>
            @else
                <i class="bi bi-pencil-square"></i>
            @endif

            {{ ucfirst($certificate->status ?? 'Draft') }}
        </span>
    </div>

    {{-- CERTIFICATE PREVIEW --}}
    <div class="slc-certificate-scroll">

        <main class="certificate-page">

            <div class="inner-border">

                {{-- SCHOOL HEADER --}}
                <header class="school-header">

                    <div class="logo-area">
                        @if($schoolLogoUrl)
                            <img
                                src="{{ $schoolLogoUrl }}"
                                class="school-logo"
                                alt="School Logo"
                                onerror="this.style.display='none';"
                            >
                        @endif
                    </div>

                    <div class="school-heading">

                        <h1 class="school-name">
                            {{ $schoolName ?: 'School Name' }}
                        </h1>

                        @if($schoolAddressLine !== '')
                            <p class="school-line">
                                {{ $schoolAddressLine }}
                            </p>
                        @endif

                        @if($udiseCode || $schoolCode)
                            <p class="school-line">

                                @if($udiseCode)
                                    UDISE Code: {{ $udiseCode }}
                                @endif

                                @if($udiseCode && $schoolCode)
                                    &nbsp; | &nbsp;
                                @endif

                                @if($schoolCode)
                                    School Code: {{ $schoolCode }}
                                @endif

                            </p>
                        @endif

                        @if($schoolPhone || $schoolEmail)
                            <p class="school-line school-contact">

                                @if($schoolPhone)
                                    Phone: {{ $schoolPhone }}
                                @endif

                                @if($schoolPhone && $schoolEmail)
                                    &nbsp; | &nbsp;
                                @endif

                                @if($schoolEmail)
                                    Email: {{ $schoolEmail }}
                                @endif

                            </p>
                        @endif

                    </div>

                    <div class="school-spacer"></div>

                </header>

                <hr class="header-divider">

                {{-- CERTIFICATE TITLE AND NUMBER --}}
                <section class="certificate-meta">

                    <div class="certificate-number">
                        <strong>Certificate No.:</strong>
                        {{ $certificate->certificate_no ?: 'Not Generated' }}
                    </div>

                    <div>
                        <h2 class="certificate-title">
                            School Leaving Certificate
                        </h2>

                        <div class="certificate-subtitle">
                            Transfer / Leaving Certificate
                        </div>
                    </div>

                    <div class="book-info">
                        <strong>Medium:</strong>
                        {{ $medium ?: '-' }}
                    </div>

                </section>

                {{-- NOTICE --}}
                <div class="notice">
                    No changes shall be made in any entry except by the
                    competent authority.
                </div>

                {{-- STUDENT REFERENCE DETAILS --}}
                <table class="reference-table">
                    <tbody>

                        <tr>
                            <td>
                                <span class="field-label">Register No.:</span>
                                {{ $registerNo ?: '-' }}
                            </td>

                            <td>
                                <span class="field-label">Admission Date:</span>
                                {{ $admissionDate ?: '-' }}
                            </td>

                            <td>
                                <span class="field-label">Academic Year:</span>
                                {{ $academicYear ?: '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="field-label">Student ID:</span>
                                {{ $studentId ?: '-' }}
                            </td>

                            <td>
                                <span class="field-label">Admission Class:</span>
                                {{ $admissionClass ?: '-' }}
                            </td>

                            <td>
                                <span class="field-label">Aadhaar No.:</span>
                                {{ $aadhar ?: '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="field-label">APAAR ID:</span>
                                {{ $apparId ?: '-' }}
                            </td>

                            <td>
                                <span class="field-label">Mother Tongue:</span>
                                {{ $motherTongue ?: '-' }}
                            </td>

                            <td>
                                <span class="field-label">PEN No.:</span>
                                {{ $penNo ?: '-' }}
                            </td>
                        </tr>

                    </tbody>
                </table>

                {{-- CERTIFICATE DETAILS --}}
                <table class="details-table">
                    <tbody>

                        <tr>
                            <td class="label">1. Full Name of Student</td>
                            <td class="value">{{ $fullName ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">2. Mother's Name</td>
                            <td class="value">{{ $motherName ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">3. Religion</td>
                            <td class="value">{{ $religion ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">4. Caste / Sub-caste</td>
                            <td class="value">{{ $casteSubCaste ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">5. Nationality</td>
                            <td class="value">{{ $nationality ?: '-' }}</td>
                        </tr>

                        <tr class="dob-row">
                            <td class="label">
                                6. Date of Birth
                                <br>
                                <span class="muted">(In figures and words)</span>
                            </td>

                            <td class="value">
                                {{ $dob ?: '-' }}

                                @if($dobWords)
                                    <br>
                                    <span class="muted">{{ $dobWords }}</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="label">7. Place of Birth</td>
                            <td class="value">{{ $birthPlace ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">8. Previous School and Class</td>
                            <td class="value">{{ $previousSchoolDisplay ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">9. Date of Leaving School</td>
                            <td class="value">{{ $leavingDate ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">10. Student's Progress</td>
                            <td class="value">{{ $progress ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">11. Student's Conduct</td>
                            <td class="value">{{ $conduct ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">12. Class Studied and Since When</td>
                            <td class="value">{{ $classSince ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">13. Reason for Leaving</td>
                            <td class="value">{{ $reason ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">14. Remarks</td>
                            <td class="value">{{ $remarks ?: '-' }}</td>
                        </tr>

                    </tbody>
                </table>

                {{-- DECLARATION --}}
                <div class="declaration">
                    Certified that the particulars recorded above are true
                    and correct according to the records of the institution.
                </div>

                {{-- SIGNATURES AND SEAL --}}
                <table class="signature-table">
                    <tbody>
                        <tr>

                            <td>
                                <div class="signature-space"></div>

                                <div class="date-value">
                                    <strong>Date:</strong>
                                    {{ $issueDate ?: $leavingDate ?: '-' }}
                                </div>

                                <div class="signature-line">
                                    Date of Issue
                                </div>
                            </td>

                            <td>
                                <div class="signature-space"></div>

                                <div class="signature-line">
                                    Class Teacher

                                    <div class="signature-note">
                                        Signature
                                    </div>
                                </div>
                            </td>

                            <td>
                                

                                <div class="signature-space"></div>

                                <div class="signature-line">
                                    Principal / Headmaster

                                    <div class="signature-note">
                                        Signature
                                    </div>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>
        </main>

    </div>

</div>

@endsection
