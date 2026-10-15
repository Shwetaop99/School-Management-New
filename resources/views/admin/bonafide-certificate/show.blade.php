@extends('layouts.app')

@section('title', 'Bonafide Certificate')

@section('content')

@php
    $bonafideCertificate = $certificate ?? null;
    $school = $schoolSetting ?? \App\Models\SchoolSetting::first();
    $student = $bonafideCertificate?->student;

    $fullName = trim(implode(' ', array_filter([
        $student?->first_name,
        $student?->middle_name,
        $student?->last_name,
    ])));

    if (!$fullName && $student) {
        $fullName = $student->name ?? '';
    }

    $dob = $student?->date_of_birth ?? $student?->dob ?? null;

    try {
        $formattedDob = $dob
            ? \Carbon\Carbon::parse($dob)->format('d/m/Y')
            : '';
    } catch (\Throwable $e) {
        $formattedDob = '';
    }

    $registrationNo = $student?->register_no
        ?? $student?->registration_no
        ?? '';

    $aadharNo = $student?->aadhar_card_no
        ?? $student?->aadhar_no
        ?? '';

    $academicYear = $student?->educational_year
        ?? $student?->academic_year
        ?? $bonafideCertificate?->academic_year
        ?? '';

    $className = $student?->class
        ?? $student?->class_name
        ?? $student?->admission_class
        ?? '';

    $division = $student?->section
        ?? $student?->division
        ?? '';

    $motherName = $student?->mother_name ?? '';
    $religion = $student?->religion ?? '';
    $caste = $student?->caste ?? '';

    $casteReligion = trim(
        $caste . ($caste && $religion ? ' / ' : '') . $religion
    );

    $reason = $bonafideCertificate?->reason
        ?: 'To avail of travel benefits';

    $issueDate = $bonafideCertificate?->issue_date;

    try {
        $formattedIssueDate = $issueDate
            ? \Carbon\Carbon::parse($issueDate)->format('d/m/Y')
            : '';
    } catch (\Throwable $e) {
        $formattedIssueDate = '';
    }

    $logo = $school?->logo;

    $logoUrl = $logo
        ? (
            \Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])
                ? $logo
                : asset('storage/' . ltrim($logo, '/'))
        )
        : asset('images/gurukullogo.png');
@endphp

<div class="bonafide-container">

    {{-- Page heading and actions --}}
    <div class="page-heading no-print">
        <div>
            <div class="page-eyebrow">CERTIFICATE MANAGEMENT</div>
            <h2>Bonafide Certificate</h2>
            <p>View and print the student's official bonafide certificate.</p>
        </div>

        <div class="heading-actions">
            <a href="{{ route('admin.bonafide.index') }}"
               class="action-btn btn-back">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            @if($bonafideCertificate)
                <a href="{{ route('admin.bonafide.edit', $bonafideCertificate->id) }}"
                   class="action-btn btn-edit">
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </a>

                <a href="{{ route('admin.bonafide.print', $bonafideCertificate->id) }}"
                   target="_blank"
                   rel="noopener"
                   class="action-btn btn-print">
                    <i class="bi bi-printer"></i>
                    Print Certificate
                </a>
            @endif
        </div>
    </div>

    {{-- Certificate preview --}}
    <div class="certificate-card">
        <div class="certificate-top-accent"></div>

        <div class="certificate-page">

            {{-- School header --}}
            <header class="school-header">
                <div class="school-logo">
                    <img src="{{ $logoUrl }}"
                         alt="School Logo"
                         onerror="this.style.display='none'">
                </div>

                <div class="school-information">
                    <h1>{{ $school?->school_name ?? 'School Name' }}</h1>

                    <p class="school-address">
                        {{ implode(', ', array_filter([
                            $school?->address,
                            $school?->city,
                            $school?->district,
                            $school?->state,
                        ])) }}

                        @if($school?->pincode)
                            - {{ $school->pincode }}
                        @endif
                    </p>

                    <div class="school-meta">
                        @if($school?->school_code)
                            <span>
                                <strong>School Code:</strong>
                                {{ $school->school_code }}
                            </span>
                        @endif

                        @if($school?->udise_code)
                            <span>
                                <strong>UDISE:</strong>
                                {{ $school->udise_code }}
                            </span>
                        @endif
                    </div>

                    <div class="school-meta">
                        @if($school?->phone)
                            <span>
                                <strong>Phone:</strong> {{ $school->phone }}
                            </span>
                        @endif

                        @if($school?->email)
                            <span>
                                <strong>Email:</strong> {{ $school->email }}
                            </span>
                        @endif
                    </div>
                </div>
            </header>

            {{-- Certificate title --}}
            <div class="certificate-title-area">
                <span class="certificate-kicker">OFFICIAL SCHOOL DOCUMENT</span>
                <h2>BONAFIDE CERTIFICATE</h2>
                <div class="title-underline"></div>
            </div>

            {{-- Certificate number and issue date --}}
            <div class="certificate-meta">
                <div>
                    <span class="meta-label">Certificate No.</span>
                    <strong>{{ $bonafideCertificate?->certificate_no ?? '-' }}</strong>
                </div>

                <div>
                    <span class="meta-label">Date of Issue</span>
                    <strong>{{ $formattedIssueDate ?: '-' }}</strong>
                </div>
            </div>

            {{-- Student particulars --}}
            <section class="student-section">
                <div class="section-heading">
                    <span class="section-number">01</span>
                    <h3>Student Particulars</h3>
                </div>

                <div class="details-grid">

                    <div class="detail-item">
                        <span class="detail-label">Full Name of Student</span>
                        <span class="detail-value">{{ $fullName ?: '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Date of Birth</span>
                        <span class="detail-value">{{ $formattedDob ?: '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Registration Number</span>
                        <span class="detail-value">{{ $registrationNo ?: '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Aadhaar Number</span>
                        <span class="detail-value">{{ $aadharNo ?: '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Mother's Name</span>
                        <span class="detail-value">{{ $motherName ?: '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Class / Division</span>
                        <span class="detail-value">
                            {{ $className ?: '-' }}
                            @if($division)
                                / {{ $division }}
                            @endif
                        </span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Academic Year</span>
                        <span class="detail-value">{{ $academicYear ?: '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Caste / Religion</span>
                        <span class="detail-value">{{ $casteReligion ?: '-' }}</span>
                    </div>

                </div>
            </section>

            {{-- Certification statement --}}
            <section class="certification-section">
                <div class="section-heading">
                    <span class="section-number">02</span>
                    <h3>Certification</h3>
                </div>

                <p class="certification-text">
                    This is to certify that
                    <strong>{{ $fullName ?: 'the above-named student' }}</strong>
                    is a bonafide student of
                    <strong>{{ $school?->school_name ?? 'our school' }}</strong>.
                    The particulars stated above are as per our official records.
                    This certificate is issued for the following purpose:
                </p>

                <div class="reason-box">
                    <span class="reason-label">Purpose of Certificate</span>
                    <p>{{ $reason }}</p>
                </div>
            </section>

            {{-- Signatures --}}
            <footer class="signature-area">
                <div class="signature-block">
                    <div class="signature-space"></div>
                    <div class="signature-rule"></div>
                    <strong>{{ $school?->principal_name ?? 'Principal' }}</strong>
                    <span>Principal / Head of School</span>
                </div>

                <div class="official-seal-block">
                    <div class="seal-circle">
                        <i class="bi bi-patch-check"></i>
                        <span>OFFICIAL<br>SEAL</span>
                    </div>
                </div>

                <div class="signature-block">
                    <div class="signature-space"></div>
                    <div class="signature-rule"></div>
                    <strong>Authorized Signatory</strong>
                    <span>{{ $school?->school_name ?? 'School' }}</span>
                </div>
            </footer>

            <div class="certificate-bottom-line">
                <span>Issued by the school administration</span>
                <span>Certificate No.: {{ $bonafideCertificate?->certificate_no ?? '-' }}</span>
            </div>

        </div>
    </div>

</div>

<style>
.bonafide-container {
    --green: #286749;
    --green-dark: #194d35;
    --green-soft: #edf6ef;
    --green-border: #cfe2d2;
    --text: #26372d;
    --muted: #69776e;

    padding: 26px;
    background: #f3f6f3;
    min-height: calc(100vh - 70px);
    color: var(--text);
}

.page-heading {
    max-width: 1120px;
    margin: 0 auto 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
}

.page-eyebrow {
    color: var(--green);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.8px;
    margin-bottom: 5px;
}

.page-heading h2 {
    margin: 0;
    font-size: 25px;
    font-weight: 750;
    color: #243b2d;
}

.page-heading p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 13px;
}

.heading-actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 9px 13px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 650;
    text-decoration: none;
    transition: background .2s ease;
}

.btn-back {
    color: #45534a;
    background: #fff;
    border: 1px solid #d6dfd8;
}

.btn-edit {
    color: #245a3c;
    background: #e6f1e8;
    border: 1px solid #c9dfce;
}

.btn-print {
    color: #fff;
    background: var(--green);
    border: 1px solid var(--green);
}

.btn-print:hover {
    background: var(--green-dark);
    color: #fff;
}

.btn-back:hover,
.btn-edit:hover {
    background: #e8efe9;
    color: var(--green-dark);
}

.certificate-card {
    max-width: 1120px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #dce7de;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(35, 70, 45, .07);
}

.certificate-top-accent {
    height: 5px;
    background: linear-gradient(90deg, #194d35, #75aa7e, #d4e9d5);
}

.certificate-page {
    position: relative;
    box-sizing: border-box;
    padding: 35px 48px 25px;
    background: #fff;
    font-family: Arial, Helvetica, sans-serif;
    color: #26352b;
}

.school-header {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 22px;
    padding-bottom: 22px;
    border-bottom: 2px solid var(--green);
    text-align: center;
}

.school-logo {
    width: 82px;
    min-width: 82px;
    height: 82px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.school-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.school-information {
    flex: 1;
    min-width: 0;
}

.school-information h1 {
    margin: 0 0 8px;
    font-size: 25px;
    font-weight: 800;
    color: var(--green-dark);
    line-height: 1.25;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.school-address {
    margin: 0 auto 8px;
    font-size: 11px;
    line-height: 1.6;
    color: #526458;
}

.school-meta {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 5px;
    font-size: 10px;
    line-height: 1.5;
    color: #45564a;
}

.certificate-title-area {
    text-align: center;
    margin: 25px 0 20px;
}

.certificate-kicker {
    font-size: 9px;
    font-weight: 800;
    color: #728878;
    letter-spacing: 2px;
}

.certificate-title-area h2 {
    margin: 7px 0 9px;
    color: var(--green-dark);
    font-size: 23px;
    font-weight: 800;
    letter-spacing: 2px;
}

.title-underline {
    width: 90px;
    height: 3px;
    margin: auto;
    background: #6da77a;
    border-radius: 5px;
}

.certificate-meta {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 12px 16px;
    background: var(--green-soft);
    border: 1px solid var(--green-border);
    border-radius: 5px;
    font-size: 11px;
}

.certificate-meta > div {
    display: flex;
    gap: 9px;
    align-items: baseline;
    flex-wrap: wrap;
}

.meta-label {
    color: #607466;
    font-weight: 600;
}

.certificate-meta strong {
    color: var(--green-dark);
}

.student-section,
.certification-section {
    margin-top: 23px;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 9px;
    border-bottom: 1px solid #dce8de;
}

.section-number {
    width: 25px;
    height: 25px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border-radius: 5px;
    background: #e5f1e7;
    color: var(--green);
    font-size: 10px;
    font-weight: 800;
}

.section-heading h3 {
    margin: 0;
    font-size: 13px;
    color: var(--green-dark);
    font-weight: 750;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 35px;
    row-gap: 0;
}

.detail-item {
    display: grid;
    grid-template-columns: minmax(125px, 42%) 1fr;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #edf1ed;
    font-size: 11px;
    line-height: 1.5;
    min-width: 0;
}

.detail-label {
    color: #637368;
    font-weight: 600;
}

.detail-value {
    color: #25382b;
    font-weight: 650;
    overflow-wrap: anywhere;
}

.certification-text {
    margin: 0;
    font-size: 12px;
    line-height: 1.9;
    color: #35453a;
    text-align: justify;
}

.reason-box {
    margin-top: 13px;
    padding: 12px 15px;
    background: #f5faf5;
    border-left: 3px solid #6da77a;
    border-radius: 0 5px 5px 0;
}

.reason-label {
    display: block;
    margin-bottom: 5px;
    color: #637b68;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .8px;
}

.reason-box p {
    margin: 0;
    font-size: 11px;
    line-height: 1.6;
    color: #263d2e;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.signature-area {
    display: grid;
    grid-template-columns: 1fr 110px 1fr;
    align-items: end;
    gap: 25px;
    margin-top: 35px;
}

.signature-block {
    text-align: center;
    font-size: 10px;
}

.signature-space {
    height: 35px;
}

.signature-rule {
    width: 85%;
    margin: 0 auto 9px;
    border-top: 1px solid #718276;
}

.signature-block strong,
.signature-block span {
    display: block;
}

.signature-block strong {
    color: #304b37;
    font-size: 10px;
}

.signature-block span {
    margin-top: 4px;
    color: #758278;
    font-size: 9px;
}

.official-seal-block {
    display: flex;
    justify-content: center;
    align-items: center;
}

.seal-circle {
    width: 70px;
    height: 70px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 4px;
    border: 1.5px dashed #7aa485;
    border-radius: 50%;
    color: #527d5d;
    text-align: center;
    transform: rotate(-8deg);
}

.seal-circle i {
    font-size: 18px;
}

.seal-circle span {
    font-size: 7px;
    font-weight: 800;
    letter-spacing: .5px;
    line-height: 1.4;
}

.certificate-bottom-line {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 25px;
    padding-top: 10px;
    border-top: 1px solid #dce8de;
    color: #819086;
    font-size: 8px;
}

@media (max-width: 768px) {
    .bonafide-container {
        padding: 14px;
    }

    .page-heading h2 {
        font-size: 21px;
    }

    .heading-actions {
        width: 100%;
    }

    .certificate-page {
        padding: 25px 20px;
    }

    .school-header {
        gap: 12px;
    }

    .school-logo {
        width: 60px;
        min-width: 60px;
        height: 60px;
    }

    .school-information h1 {
        font-size: 18px;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .certificate-meta {
        flex-direction: column;
        gap: 9px;
    }

    .signature-area {
        grid-template-columns: 1fr 75px 1fr;
        gap: 10px;
    }

    .seal-circle {
        width: 60px;
        height: 60px;
    }
}

@media print {
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    body {
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .no-print,
    .page-heading,
    .sidebar,
    .navbar,
    .app-header,
    .main-header,
    footer:not(.signature-area) {
        display: none !important;
    }

    .bonafide-container {
        min-height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #fff !important;
    }

    .certificate-card {
        max-width: none !important;
        margin: 0 !important;
        border: 1px solid #cbd8cc !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .certificate-top-accent {
        height: 3px !important;
    }

    .certificate-page {
        padding: 8mm 10mm 5mm !important;
        overflow: visible !important;
    }

    .school-header {
        padding-bottom: 12px !important;
    }

    .school-logo {
        width: 65px !important;
        min-width: 65px !important;
        height: 65px !important;
    }

    .school-information h1 {
        font-size: 19px !important;
    }

    .certificate-title-area {
        margin: 14px 0 !important;
    }

    .certificate-title-area h2 {
        font-size: 19px !important;
    }

    .student-section,
    .certification-section {
        margin-top: 15px !important;
    }

    .detail-item {
        padding: 7px 0 !important;
    }

    .signature-area {
        margin-top: 20px !important;
        break-inside: avoid;
    }

    .certificate-bottom-line {
        margin-top: 14px !important;
    }
}
</style>

@endsection
