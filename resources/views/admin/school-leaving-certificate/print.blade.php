
@php
    $student = $certificate->student;

    /*
    |--------------------------------------------------------------------------
    | SCHOOL SETTINGS
    |--------------------------------------------------------------------------
    | Fetch the logo from School Settings, not from $school->logo.
    */
    $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::first();

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

    // Get logo from School Settings.
    $schoolLogo = trim((string) (
        data_get($schoolSetting, 'logo')
        ?: data_get($schoolSetting, 'school_logo')
        ?: data_get($schoolSetting, 'logo_path')
        ?: ''
    ));

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

    // Date formatter.
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

    // Student details.
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

    // Complete school address.
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
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>School Leaving Certificate - {{ $fullName }}</title>

    
<style>
    @page {
        size: A4 portrait;
        margin: 8mm;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        color: #17251b;
        background: #edf2ed;
        font-family: Arial, Helvetica, sans-serif;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body {
        font-size: 10px;
        line-height: 1.4;
    }

    /* PRINT BUTTON */

    .print-toolbar {
        width: 194mm;
        max-width: calc(100% - 24px);
        margin: 15px auto;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .print-toolbar button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        background: #245c35;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .print-toolbar button:hover {
        background: #174628;
    }

    /* CERTIFICATE PAGE */

    .certificate-page {
        position: relative;
        isolation: isolate;
        width: 194mm;
        min-height: 281mm;
        margin: 0 auto 20px;
        padding: 4mm;
        overflow: hidden;
        border: 3px double #38734a;
        background: #f4fbf2;
    }

    /* INNER BORDER */

    .inner-border {
        position: relative;
        z-index: 1;
        min-height: 271mm;
        padding: 3mm;
        border: 1px solid #8ab795;
        background: transparent !important;
        display: flex;
        flex-direction: column;
    }

    /*
    |--------------------------------------------------------------------------
    | WATERMARK
    |--------------------------------------------------------------------------
    | The watermark is positioned between the page background and content.
    | Table cells must remain transparent for it to be visible.
    */

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 125mm;
        height: 125mm;
        transform: translate(-50%, -50%);
        z-index: 1;
        opacity: 0.12;
        pointer-events: none;
        user-select: none;
        display: block;
    }

    .watermark img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* CERTIFICATE CONTENT */

    .certificate-content {
        position: relative;
        z-index: 2;
        width: 100%;
        display: flex;
        flex-direction: column;
        flex: 1;
        background: transparent !important;
    }

    /* SCHOOL HEADER */

    .school-header {
        display: grid;
        grid-template-columns: 25mm minmax(0, 1fr) 25mm;
        align-items: center;
        gap: 3mm;
        padding: 2mm 1mm 3mm;
        text-align: center;
        background: transparent !important;
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

    /* CERTIFICATE TITLE */

    .certificate-meta {
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        align-items: start;
        gap: 4mm;
        padding: 3mm 1mm 2mm;
        font-size: 9px;
        background: transparent !important;
    }

    .certificate-number {
        overflow-wrap: anywhere;
    }

    .certificate-title {
        margin: 0;
        color: #245c35;
        text-align: center;
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
    }

    .notice {
        margin: 2mm 0;
        padding: 2mm;
        border-top: 1px solid #8ab795;
        border-bottom: 1px solid #8ab795;
        background: transparent !important;
        text-align: center;
        font-size: 9px;
        font-style: italic;
    }

    /* REFERENCE TABLE */

    .reference-table {
        position: relative;
        z-index: 2;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin: 1mm 0 3mm;
        background: transparent !important;
    }

    .reference-table td {
        width: 33.333%;
        padding: 2mm 1.5mm;
        border: 1px solid #8ab795;
        background: transparent !important;
        vertical-align: top;
        overflow-wrap: anywhere;
        font-size: 9px;
    }

    .field-label {
        font-weight: 700;
    }

    /* DETAILS TABLE */

    .details-table {
        position: relative;
        z-index: 2;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        background: transparent !important;
    }

    .details-table tr {
        page-break-inside: avoid;
        break-inside: avoid;
        background: transparent !important;
    }

    .details-table td {
        border: 1px solid #8ab795;
        padding: 2.1mm 2mm;
        background: transparent !important;
        vertical-align: middle;
        overflow-wrap: anywhere;
        word-break: normal;
        font-size: 10px;
    }

    .details-table .label {
        width: 43%;
        background: transparent !important;
        font-weight: 700;
    }

    .details-table .value {
        width: 57%;
        background: transparent !important;
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
        position: relative;
        z-index: 2;
        margin-top: 4mm;
        padding: 2mm 1mm;
        text-align: center;
        font-size: 9px;
        line-height: 1.6;
        background: transparent !important;
    }

    /* SIGNATURES */

    .signature-table {
        position: relative;
        z-index: 2;
        width: 100%;
        border-collapse: collapse;
        margin-top: auto;
        background: transparent !important;
    }

    .signature-table td {
        width: 33.333%;
        padding: 0 2mm;
        background: transparent !important;
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

    /* SCREEN RESPONSIVENESS */

    @media screen and (max-width: 700px) {
        .certificate-page {
            width: calc(100% - 16px);
            min-height: auto;
            margin: 0 auto 15px;
            padding: 8px;
        }

        .inner-border {
            min-height: 0;
            padding: 8px;
        }

        .school-header {
            grid-template-columns: 50px minmax(0, 1fr);
        }

        .school-logo {
            width: 45px;
            height: 45px;
        }

        .school-spacer {
            display: none;
        }

        .school-name {
            font-size: 14px;
        }

        .school-line {
            font-size: 8px;
        }

        .certificate-meta {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 8px;
        }

        .book-info {
            text-align: center;
        }

        .certificate-title {
            font-size: 15px;
        }

        .reference-table td {
            padding: 6px 4px;
            font-size: 8px;
        }

        .details-table td {
            padding: 6px 5px;
            font-size: 9px;
        }

        .watermark {
            width: 75%;
            height: auto;
        }

        .signature-space {
            height: 15mm;
        }
    }

    /* PRINT SETTINGS */

    @media print {
        html,
        body {
            width: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        body {
            font-size: 10px;
        }

        .print-toolbar {
            display: none !important;
        }

        .certificate-page {
            position: relative !important;
            isolation: isolate !important;
            width: 194mm;
            min-height: 281mm;
            height: auto;
            margin: 0;
            padding: 4mm;
            overflow: hidden;
            border: 3px double #38734a;
            background: #f4fbf2 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .watermark {
            display: block !important;
            position: absolute !important;
            z-index: 1 !important;
            visibility: visible !important;
            opacity: 0.12 !important;
        }

        .inner-border {
            position: relative !important;
            z-index: 2 !important;
            min-height: 271mm;
            padding: 3mm;
            background: transparent !important;
        }

        .certificate-content {
            position: relative !important;
            z-index: 2 !important;
            background: transparent !important;
        }

        .reference-table,
        .reference-table tbody,
        .reference-table tr,
        .reference-table td,
        .details-table,
        .details-table tbody,
        .details-table tr,
        .details-table td,
        .details-table .label,
        .details-table .value,
        .notice,
        .declaration,
        .signature-table,
        .signature-table td {
            background: transparent !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .school-logo {
            display: block !important;
            visibility: visible !important;
        }

        .school-header,
        .certificate-meta,
        .notice,
        .reference-table,
        .declaration,
        .signature-table {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .details-table tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .certificate-page,
        .inner-border {
            break-before: auto;
            page-break-before: auto;
            break-after: auto;
            page-break-after: auto;
        }
    }
</style>

</head>

<body>

    {{-- PRINT BUTTON --}}
    <div class="print-toolbar">
        <button type="button" id="printCertificateBtn">
            <span>🖨</span>
            Print Certificate
        </button>
    </div>

    {{-- CERTIFICATE --}}
    <main class="certificate-page">

        {{-- TRANSPARENT WATERMARK FROM SCHOOL SETTINGS --}}
        @if($schoolLogoUrl)
            <img
                src="{{ $schoolLogoUrl }}"
                class="watermark"
                alt=""
                aria-hidden="true"
            >
        @endif

        <div class="inner-border">

            <div class="certificate-content">

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

                {{-- CERTIFICATE TITLE --}}
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
                                <span class="muted">
                                    (In figures and words)
                                </span>
                            </td>

                            <td class="value">
                                {{ $dob ?: '-' }}

                                @if($dobWords)
                                    <br>
                                    <span class="muted">
                                        {{ $dobWords }}
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="label">7. Place of Birth</td>
                            <td class="value">{{ $birthPlace ?: '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">8. Previous School and Class</td>
                            <td class="value">
                                {{ $previousSchoolDisplay ?: '-' }}
                            </td>
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
        </div>
    </main>

    {{-- PRINT SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const printButton = document.getElementById('printCertificateBtn');

            if (!printButton) {
                return;
            }

            printButton.addEventListener('click', async function () {

                const images = Array.from(
                    document.querySelectorAll('.watermark, .school-logo')
                );

                await Promise.all(images.map(function (img) {

                    if (img.complete && img.naturalWidth > 0) {
                        return Promise.resolve();
                    }

                    return new Promise(function (resolve) {

                        img.addEventListener('load', resolve, { once: true });
                        img.addEventListener('error', resolve, { once: true });

                        // Avoid waiting forever if an image cannot load.
                        setTimeout(resolve, 5000);
                    });

                }));

                window.focus();

                setTimeout(function () {
                    window.print();
                }, 300);

            });

        });
    </script>

</body>
</html>
