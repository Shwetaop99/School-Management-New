<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bonafide Certificate - {{ $certificate->certificate_no ?? 'Certificate' }}</title>

    @php
        $school = $schoolSetting ?? \App\Models\SchoolSetting::first();
        $student = $certificate->student;

        $fullName = trim(implode(' ', array_filter([
            $student?->first_name,
            $student?->middle_name,
            $student?->last_name,
        ])));

        $fullName = $fullName ?: ($student?->name ?? '');

        $dob = $student?->date_of_birth ?? $student?->dob;

        try {
            $formattedDob = $dob
                ? \Carbon\Carbon::parse($dob)->format('d/m/Y')
                : '-';
        } catch (\Throwable $e) {
            $formattedDob = '-';
        }

        $issueDate = $certificate->issue_date;

        try {
            $formattedIssueDate = $issueDate
                ? \Carbon\Carbon::parse($issueDate)->format('d/m/Y')
                : '-';
        } catch (\Throwable $e) {
            $formattedIssueDate = '-';
        }

        $registrationNo = $student?->register_no
            ?? $student?->registration_no
            ?? '';

        $aadharNo = $student?->aadhar_card_no
            ?? $student?->aadhar_no
            ?? '';

        $academicYear = $student?->educational_year
            ?? $student?->academic_year
            ?? $certificate->academic_year
            ?? '';

        $className = $student?->class
            ?? $student?->class_name
            ?? $student?->admission_class
            ?? '';

        $division = $student?->section
            ?? $student?->division
            ?? '';

        $motherName = $student?->mother_name ?? '';
        $caste = $student?->caste ?? '';
        $religion = $student?->religion ?? '';

        $casteReligion = trim(
            $caste . ($caste && $religion ? ' / ' : '') . $religion
        );

        $reason = $certificate->reason ?: 'To avail of travel benefits';

        $logo = $school?->logo;

        $logoUrl = $logo
            ? (
                \Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])
                    ? $logo
                    : asset('storage/' . ltrim($logo, '/'))
            )
            : asset('images/gurukullogo.png');

        $schoolAddress = implode(', ', array_filter([
            $school?->address,
            $school?->city,
            $school?->district,
            $school?->state,
        ]));

        if ($school?->pincode) {
            $schoolAddress .= ($schoolAddress ? ' - ' : '') . $school->pincode;
        }
    @endphp

    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", Georgia, serif;
            color: #26372d;
        }

        body {
            background: #edf1ed;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .toolbar {
            display: flex;
            justify-content: center;
            gap: 12px;
            padding: 18px;
            font-family: Arial, sans-serif;
        }

        .toolbar a,
        .toolbar button {
            padding: 10px 18px;
            border-radius: 5px;
            border: 1px solid #286749;
            background: #286749;
            color: white;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .toolbar a {
            background: white;
            color: #286749;
        }

        .paper {
            position: relative;
            width: 281mm;
            min-height: 194mm;
            margin: 0 auto 25px;
            padding: 8mm 11mm 7mm;
            background: #fff;
            border: 2px solid #286749;
            outline: 1px solid #b7cfbb;
            outline-offset: -6px;
            overflow: hidden;
        }

        .watermark {
            position: absolute;
            top: 42%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            color: rgba(40, 103, 73, .045);
            font-size: 48px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
        }

        .paper-content {
            position: relative;
            z-index: 1;
        }

        .school-header {
            display: grid;
            grid-template-columns: 78px 1fr 78px;
            align-items: center;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #286749;
            text-align: center;
        }

        .logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            justify-self: center;
        }

        .school-name {
            margin: 0 0 5px;
            color: #194d35;
            font-family: Arial, sans-serif;
            font-size: 22px;
            line-height: 1.2;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .school-address,
        .school-contact,
        .school-codes {
            margin: 3px 0 0;
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.5;
            color: #45584b;
        }

        .school-codes span,
        .school-contact span {
            margin: 0 6px;
        }

        .heading {
            text-align: center;
            margin: 13px 0 10px;
        }

        .heading .kicker {
            color: #69806e;
            font-family: Arial, sans-serif;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .heading h1 {
            margin: 4px 0;
            color: #194d35;
            font-size: 23px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .heading .subtitle {
            color: #617366;
            font-family: Arial, sans-serif;
            font-size: 9px;
            font-style: italic;
        }

        .heading-rule {
            width: 75px;
            margin: 7px auto 0;
            border-top: 3px solid #77a783;
        }

        .certificate-meta {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 8px 12px;
            border: 1px solid #cbdccb;
            background: #f1f7f1;
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .meta-label {
            color: #5c7162;
            font-weight: bold;
        }

        .meta-value {
            margin-left: 5px;
            color: #263e2e;
            font-weight: bold;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 13px 0 7px;
            padding-bottom: 5px;
            border-bottom: 1px solid #cfdfd0;
            color: #194d35;
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .section-number {
            display: inline-flex;
            width: 20px;
            height: 20px;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            background: #e6f1e7;
            color: #286749;
            font-size: 9px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 24px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 135px 1fr;
            gap: 7px;
            align-items: baseline;
            padding: 6px 0;
            border-bottom: 1px dotted #d9e2da;
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.35;
        }

        .detail-label {
            color: #65766a;
        }

        .detail-value {
            color: #26372d;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .certification {
            font-size: 12px;
            line-height: 1.7;
            text-align: justify;
            margin: 8px 0;
        }

        .purpose-box {
            padding: 8px 11px;
            background: #f4f8f4;
            border-left: 3px solid #6c9f76;
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.5;
        }

        .purpose-label {
            display: block;
            margin-bottom: 3px;
            color: #52715a;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .signature-area {
            display: grid;
            grid-template-columns: 1fr 100px 1fr;
            gap: 18px;
            align-items: end;
            margin-top: 20px;
        }

        .signature {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 9px;
        }

        .signature-space {
            height: 28px;
        }

        .signature-line {
            width: 75%;
            margin: 0 auto 5px;
            border-top: 1px solid #66776a;
        }

        .signature strong,
        .signature span {
            display: block;
        }

        .signature strong {
            color: #263e2e;
            font-size: 9px;
        }

        .signature span {
            margin-top: 3px;
            color: #6a7a6d;
            font-size: 8px;
        }

        .seal {
            width: 66px;
            height: 66px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            border: 1.5px dashed #7da486;
            border-radius: 50%;
            color: #477454;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 8px;
            font-weight: bold;
            line-height: 1.5;
        }

        .footer-line {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid #d8e4d9;
            color: #7b897e;
            font-family: Arial, sans-serif;
            font-size: 7px;
        }

        @media print {
            html,
            body {
                width: 100%;
                height: auto;
                background: white !important;
            }

            .toolbar {
                display: none !important;
            }

            .paper {
                width: 100%;
                min-height: 0;
                height: 194mm;
                margin: 0;
                padding: 8mm 11mm 7mm;
                border: 2px solid #286749;
                outline: 1px solid #b7cfbb;
                outline-offset: -6px;
                box-shadow: none;
                break-after: avoid;
                page-break-after: avoid;
            }

            .watermark {
                color: rgba(40, 103, 73, .045) !important;
            }

            .certificate-meta,
            .purpose-box,
            .section-number {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }

        @media screen and (max-width: 1000px) {
            .paper {
                transform-origin: top left;
                margin-left: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <a href="{{ route('admin.bonafide.show', $certificate->id) }}">
            Back to Certificate
        </a>

        <button type="button" onclick="window.print()">
            Print Certificate
        </button>
    </div>

    <main class="paper">
        <div class="watermark">
            {{ $school?->school_name ?? 'School' }}
        </div>

        <div class="paper-content">

            {{-- School details --}}
            <header class="school-header">
                <img
                    src="{{ $logoUrl }}"
                    alt="School Logo"
                    class="logo"
                    onerror="this.style.visibility='hidden'"
                >

                <div>
                    <h2 class="school-name">
                        {{ $school?->school_name ?? 'School Name' }}
                    </h2>

                    @if($schoolAddress)
                        <p class="school-address">{{ $schoolAddress }}</p>
                    @endif

                    <div class="school-codes">
                        @if($school?->school_code)
                            <span><strong>School Code:</strong> {{ $school->school_code }}</span>
                        @endif

                        @if($school?->udise_code)
                            <span><strong>UDISE:</strong> {{ $school->udise_code }}</span>
                        @endif
                    </div>

                    <div class="school-contact">
                        @if($school?->phone)
                            <span><strong>Phone:</strong> {{ $school->phone }}</span>
                        @endif

                        @if($school?->email)
                            <span><strong>Email:</strong> {{ $school->email }}</span>
                        @endif
                    </div>
                </div>

                <div></div>
            </header>

            {{-- Certificate heading --}}
            <section class="heading">
                <div class="kicker">OFFICIAL SCHOOL DOCUMENT</div>
                <h1>Bonafide Certificate</h1>
                <div class="subtitle">Certificate of student status</div>
                <div class="heading-rule"></div>
            </section>

            {{-- Certificate number and issue date --}}
            <div class="certificate-meta">
                <div>
                    <span class="meta-label">Certificate No.:</span>
                    <span class="meta-value">{{ $certificate->certificate_no ?: '-' }}</span>
                </div>

                <div>
                    <span class="meta-label">Date of Issue:</span>
                    <span class="meta-value">{{ $formattedIssueDate }}</span>
                </div>
            </div>

            {{-- Student particulars --}}
            <section>
                <div class="section-heading">
                    <span class="section-number">01</span>
                    Student Particulars
                </div>

                <div class="details-grid">
                    <div>
                        <div class="detail-row">
                            <span class="detail-label">Full Name of Student</span>
                            <span class="detail-value">{{ $fullName ?: '-' }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Mother's Name</span>
                            <span class="detail-value">{{ $motherName ?: '-' }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Registration Number</span>
                            <span class="detail-value">{{ $registrationNo ?: '-' }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Academic Year</span>
                            <span class="detail-value">{{ $academicYear ?: '-' }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="detail-row">
                            <span class="detail-label">Date of Birth</span>
                            <span class="detail-value">{{ $formattedDob }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Class / Division</span>
                            <span class="detail-value">
                                {{ $className ?: '-' }}
                                @if($division)
                                    / {{ $division }}
                                @endif
                            </span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Aadhaar Number</span>
                            <span class="detail-value">{{ $aadharNo ?: '-' }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Caste / Religion</span>
                            <span class="detail-value">{{ $casteReligion ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Certification --}}
            <section>
                <div class="section-heading">
                    <span class="section-number">02</span>
                    Certification
                </div>

                <p class="certification">
                    This is to certify that
                    <strong>{{ $fullName ?: 'the above-named student' }}</strong>
                    is a bonafide student of
                    <strong>{{ $school?->school_name ?? 'our school' }}</strong>.
                    The student's particulars stated above are recorded in the
                    official school records and are true to the best of our knowledge.
                </p>

                <div class="purpose-box">
                    <span class="purpose-label">Purpose of Certificate</span>
                    {{ $reason }}
                </div>
            </section>

            {{-- Signatures and seal --}}
            <footer class="signature-area">
                <div class="signature">
                    <div class="signature-space"></div>
                    <div class="signature-line"></div>
                    <strong>{{ $school?->principal_name ?? 'Principal' }}</strong>
                    <span>Principal / Head of School</span>
                </div>

                <div class="seal">
                    OFFICIAL<br> SCHOOL SEAL
                </div>

                <div class="signature">
                    <div class="signature-space"></div>
                    <div class="signature-line"></div>
                    <strong>Class Teacher / Authorized Signatory</strong>
                    <span>{{ $school?->school_name ?? 'School' }}</span>
                </div>
            </footer>

            <div class="footer-line">
                <span>Issued by the school administration</span>
                <span>Certificate No.: {{ $certificate->certificate_no ?: '-' }}</span>
            </div>

        </div>
    </main>

</body>
</html>
