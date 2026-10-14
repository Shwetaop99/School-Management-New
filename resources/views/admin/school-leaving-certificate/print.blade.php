
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
            background: #fff;
            color: #111;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-size: 10px;
            line-height: 1.4;
        }

        .print-toolbar {
            width: 194mm;
            margin: 12px auto;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .print-toolbar button {
            padding: 9px 18px;
            border: 0;
            border-radius: 4px;
            background: #1677f0;
            color: #fff;
            font-size: 13px;
            cursor: pointer;
        }

        .certificate-page {
            width: 194mm;
            min-height: 281mm;
            margin: 0 auto;
            padding: 4mm;
            border: 3px double #222;
            background: #fff;
            position: relative;
        }

        .inner-border {
            border: 1px solid #777;
            min-height: 271mm;
            padding: 3mm;
            display: flex;
            flex-direction: column;
        }

        /* School header */

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
            border-top: 1.5px solid #222;
            margin: 0;
        }

        /* Certificate heading */

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
            border-top: 1px solid #777;
            border-bottom: 1px solid #777;
            text-align: center;
            font-size: 9px;
            font-style: italic;
        }

        /* Student reference information */

        .reference-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 1mm 0 3mm;
        }

        .reference-table td {
            width: 33.333%;
            padding: 2mm 1.5mm;
            border: 1px solid #777;
            vertical-align: top;
            overflow-wrap: anywhere;
            font-size: 9px;
        }

        .field-label {
            font-weight: 700;
        }

        /* Main certificate details */

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
            border: 1px solid #555;
            padding: 2.1mm 2mm;
            vertical-align: middle;
            overflow-wrap: anywhere;
            word-break: normal;
            font-size: 10px;
        }

        .details-table .label {
            width: 43%;
            font-weight: 700;
        }

        .details-table .value {
            width: 57%;
            min-height: 8mm;
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

        /* Declaration and signatures */

        .declaration {
            margin-top: 4mm;
            padding: 2mm 1mm;
            text-align: center;
            font-size: 9px;
            line-height: 1.6;
        }

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
            border-top: 1px solid #222;
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

        @media print {
            html,
            body {
                width: auto;
                min-height: 0;
                margin: 0;
                padding: 0;
                background: #fff;
            }

            .print-toolbar {
                display: none !important;
            }

            .certificate-page {
                width: 100%;
                min-height: 0;
                margin: 0;
                padding: 0;
                border: 3px double #222;
            }

            .inner-border {
                min-height: 278mm;
            }

            .certificate-page,
            .inner-border {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .details-table tr,
            .school-header,
            .certificate-meta,
            .notice,
            .reference-table,
            .declaration,
            .signature-table {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }

        @media screen and (max-width: 700px) {
            .print-toolbar,
            .certificate-page {
                width: 100%;
            }

            .certificate-page {
                padding: 8px;
            }

            .inner-border {
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

            .details-table td {
                padding: 6px 5px;
                font-size: 9px;
            }
        }

        /* Light green certificate styling */

.certificate-page {
    background: #f4fbf2;
    border-color: #38734a;
}

.inner-border {
    background: #fafff8;
    border-color: #8ab795;
}

.school-name {
    color: #245c35;
}

.certificate-title {
    color: #245c35;
}

.header-divider {
    border-top-color: #38734a;
}

.notice {
    background: #e8f5e9;
    border-color: #8ab795;
}

.reference-table td {
    background: #f0f8ef;
}

.details-table td {
    border-color: #8ab795;
}

.details-table .label {
    background: #e8f5e9;
}

.signature-line {
    border-top-color: #38734a;
}

@media print {
    .certificate-page,
    .inner-border,
    .reference-table td,
    .details-table td,
    .notice {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
    </style>
</head>

<body>
    <div class="print-toolbar">
        <button type="button" onclick="window.print()">
            Print Certificate
        </button>
    </div>

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
        <div>
            <strong>Medium:</strong>
            {{ $medium ?: '-' }}
        </div>
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
                            <div class="signature-line">Date of Issue</div>
                        </td>

                        <td>
                            <div class="signature-space"></div>
                            <div class="signature-line">
                                Class Teacher
                                <div class="signature-note">Signature</div>
                            </div>
                        </td>

                        <td>
                            <div class="seal-space">School Seal</div>
                            <div class="signature-space"></div>
                            <div class="signature-line">
                                Principal / Headmaster
                                <div class="signature-note">Signature</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </main>
</body>
</html>
