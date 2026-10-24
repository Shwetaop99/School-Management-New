@extends('layouts.app')

@section('title', 'School Leaving Certificate | Admin')

@section('content')

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

        if (str_starts_with($schoolLogo, 'http://') || str_starts_with($schoolLogo, 'https://')) {
            $schoolLogoUrl = $schoolLogo;
        } elseif (str_starts_with($schoolLogo, '/')) {
            $schoolLogoUrl = $schoolLogo;
        } elseif (str_starts_with($schoolLogo, 'storage/')) {
            $schoolLogoUrl = asset($schoolLogo);
        } elseif (str_starts_with($schoolLogo, 'images/')) {
            $schoolLogoUrl = asset($schoolLogo);
        } else {
            $schoolLogoUrl = asset('storage/' . ltrim($schoolLogo, '/'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SCHOOL LOCATION
    |--------------------------------------------------------------------------
    */
    $schoolLocationParts = [];
    if ($schoolCity) $schoolLocationParts[] = $schoolCity;
    if ($schoolDistrict) $schoolLocationParts[] = $schoolDistrict;
    if ($schoolState) $schoolLocationParts[] = $schoolState;

    $schoolLocation = implode(', ', $schoolLocationParts);
    if ($schoolPincode) {
        $schoolLocation .= ($schoolLocation ? ' - ' : '') . $schoolPincode;
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

    $marathiName = $student->marathi_name ?? '';

    /*
    |--------------------------------------------------------------------------
    | DATE OF BIRTH
    |--------------------------------------------------------------------------
    */
    $dob = '';
    if (!empty($student->date_of_birth)) {
        try {
            $dob = \Illuminate\Support\Carbon::parse($student->date_of_birth)->format('d-m-Y');
        } catch (\Throwable $e) {
            $dob = '';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CERTIFICATE / ISSUE DATE
    |--------------------------------------------------------------------------
    */
    $certificateDate = $certificate->certificate_date ?? $certificate->issue_date ?? null;
    $issueDate = '';
    if ($certificateDate) {
        try {
            $issueDate = \Illuminate\Support\Carbon::parse($certificateDate)->format('d-m-Y');
        } catch (\Throwable $e) {
            $issueDate = '';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LEAVING DATE
    |--------------------------------------------------------------------------
    */
    $leavingDate = '';
    if (!empty($certificate->leaving_date)) {
        try {
            $leavingDate = \Illuminate\Support\Carbon::parse($certificate->leaving_date)->format('d-m-Y');
        } catch (\Throwable $e) {
            $leavingDate = '';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REASON FOR LEAVING
    |--------------------------------------------------------------------------
    */
    $reason = $certificate->reason_for_leaving ?? $certificate->leaving_reason ?? '';

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */
    $statusClass = match ($certificate->status) {
        'issued' => 'issued',
        'cancelled' => 'cancelled',
        default => 'draft',
    };
@endphp

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — SCHOOL LEAVING CERTIFICATE SHOW
       LOCKED VERSION
    ========================================================= */

    .slc-show-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .slc-show-page *,
    .slc-show-page *::before,
    .slc-show-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .slc-header {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 20px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .slc-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .slc-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .slc-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .slc-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .slc-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .slc-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .slc-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 12px;
        font-size: 22px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .08);
    }

    .slc-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .slc-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    .slc-header-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
        flex-wrap: wrap;
    }

    /* ---------- BUTTONS ---------- */
    .btn-scheme-back,
    .btn-scheme-edit,
    .btn-scheme-print {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all .18s ease;
    }

    .btn-scheme-back {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .btn-scheme-back:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    .btn-scheme-edit {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .btn-scheme-edit:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    .btn-scheme-print {
        color: #245a43;
        background: #eaf3e9;
        border-color: #c5dcc8;
    }

    .btn-scheme-print:hover {
        background: #dce9d9;
        border-color: #83aa8a;
    }

    /* ---------- ALERTS ---------- */
    .scheme-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.5;
    }

    .scheme-alert-success {
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-left: 4px solid #34775a;
        color: #245a43;
    }

    /* ---------- STATUS BADGE ---------- */
    .status-wrap {
        margin-bottom: 18px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-badge.issued {
        background: #eaf3e9;
        color: #245a43;
        border: 1px solid #c5dcc8;
    }

    .status-badge.cancelled {
        background: #fff1ed;
        color: #8a3a34;
        border: 1px solid #e8b8b0;
    }

    .status-badge.draft {
        background: #f8eee0;
        color: #87582d;
        border: 1px solid #d9b88f;
    }

    /* ---------- CERTIFICATE CARD ---------- */
    .certificate-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        overflow: hidden;
    }

    .certificate-preview {
        background: #fffefa;
        padding: 36px 40px;
    }

    /* ---------- SCHOOL HEADER ---------- */
    .school-header {
        text-align: center;
        margin-bottom: 8px;
    }

    .school-logo {
        max-height: 90px;
        max-width: 150px;
        margin-bottom: 12px;
        object-fit: contain;
    }

    .school-name {
        margin: 0 0 6px;
        color: #263b35;
        font-size: 20px;
        font-weight: 750;
        letter-spacing: .3px;
    }

    .school-meta {
        margin: 0 0 4px;
        color: #5c6b62;
        font-size: 11px;
        line-height: 1.5;
    }

    .cert-type-label {
        margin: 10px 0 0;
        color: #34775a;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .cert-meta-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin: 16px 0 24px;
        padding: 12px 16px;
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 650;
        color: #263b35;
    }

    .cert-meta-bar strong {
        color: #5c6b62;
        font-weight: 600;
        margin-right: 4px;
    }

    /* ---------- SECTION TITLE ---------- */
    .section-title {
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border-left: 4px solid #34775a;
        padding: 10px 14px;
        font-weight: 750;
        font-size: 13px;
        color: #263b35;
        margin-bottom: 0;
        border-radius: 0 8px 0 0;
    }

    /* ---------- CERTIFICATE TABLE ---------- */
    .certificate-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 12px;
    }

    .certificate-table th,
    .certificate-table td {
        padding: 10px 12px;
        vertical-align: middle;
        border: 1px solid #e5e1d3;
    }

    .certificate-table th {
        background: #f8f6ed;
        font-weight: 700;
        color: #405149;
        width: 22%;
        text-align: left;
    }

    .certificate-table td {
        background: #fffefa;
        color: #263b35;
        font-weight: 500;
        width: 28%;
    }

    /* ---------- REMARKS ---------- */
    .remarks-box {
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        border-radius: 9px;
        padding: 14px 16px;
        margin-bottom: 20px;
        color: #405149;
        font-size: 12px;
        line-height: 1.7;
        white-space: pre-line;
    }

    /* ---------- DECLARATION ---------- */
    .certificate-declaration {
        font-size: 12px;
        line-height: 1.8;
        text-align: justify;
        color: #405149;
        padding: 14px 0;
        border-top: 1px solid #e9e5d9;
        margin-top: 8px;
    }

    /* ---------- SIGNATURES ---------- */
    .signatures-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-top: 40px;
        padding-top: 20px;
        flex-wrap: wrap;
    }

    .signature-block {
        flex: 1;
        min-width: 140px;
        text-align: center;
    }

    .signature-line {
        border-top: 1px solid #5c6b62;
        width: 75%;
        margin: 0 auto 8px;
        height: 28px;
    }

    .signature-block strong {
        color: #263b35;
        font-size: 11px;
        font-weight: 700;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 767.98px) {
        .slc-header {
            padding: 18px 16px;
        }

        .slc-title {
            font-size: 19px;
        }

        .slc-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .slc-header-content {
            flex-direction: column;
        }

        .slc-header-actions {
            width: 100%;
        }

        .btn-scheme-back,
        .btn-scheme-edit,
        .btn-scheme-print {
            flex: 1;
        }

        .certificate-preview {
            padding: 20px 16px;
        }

        .school-name {
            font-size: 17px;
        }

        .cert-meta-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .certificate-table th,
        .certificate-table td {
            display: block;
            width: 100% !important;
        }

        .certificate-table tr {
            display: block;
            margin-bottom: 8px;
            border: 1px solid #e5e1d3;
            border-radius: 8px;
            overflow: hidden;
        }

        .signatures-row {
            flex-direction: column;
            gap: 28px;
        }
    }

    @media (max-width: 480px) {
        .slc-title {
            font-size: 17px;
        }

        .slc-subtitle {
            font-size: 10px;
        }

        .slc-header-actions {
            flex-direction: column;
        }

        .btn-scheme-back,
        .btn-scheme-edit,
        .btn-scheme-print {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .slc-show-page *,
        .slc-show-page *::before,
        .slc-show-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="slc-show-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="slc-header">
        <div class="slc-header-content">
            <div>
                <div class="slc-breadcrumb">
                    <a href="{{ route('admin.school-leaving-certificate.index') }}">School Leaving Certificates</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>{{ $certificate->certificate_no }}</span>
                </div>

                <div class="slc-title-row">
                    <div class="slc-title-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <h1 class="slc-title">School Leaving Certificate</h1>
                        <p class="slc-subtitle">
                            Certificate No: <strong>{{ $certificate->certificate_no }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="slc-header-actions">
                <a href="{{ route('admin.school-leaving-certificate.index') }}" class="btn-scheme-back">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
                <a href="{{ route('admin.school-leaving-certificate.edit', $certificate) }}" class="btn-scheme-edit">
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </a>
                <a href="{{ route('admin.school-leaving-certificate.print', $certificate) }}"
                   target="_blank"
                   class="btn-scheme-print">
                    <i class="bi bi-printer"></i>
                    Print
                </a>
            </div>
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="scheme-alert scheme-alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- STATUS BADGE --}}
    <div class="status-wrap">
        <span class="status-badge {{ $statusClass }}">
            @if($certificate->status === 'issued')
                <i class="bi bi-check-circle"></i>
            @elseif($certificate->status === 'cancelled')
                <i class="bi bi-x-circle"></i>
            @else
                <i class="bi bi-pencil-square"></i>
            @endif
            {{ ucfirst($certificate->status) }}
        </span>
    </div>

    {{-- ===================== CERTIFICATE CARD ===================== --}}
    <div class="certificate-card">
        <div class="certificate-preview">

            {{-- SCHOOL HEADER --}}
            <div class="school-header">
                @if($schoolLogoUrl)
                    <img src="{{ $schoolLogoUrl }}"
                         alt="School Logo"
                         class="school-logo"
                         onerror="this.style.display='none';">
                @endif

                <h2 class="school-name">{{ $schoolName ?: 'School Name' }}</h2>

                @if($schoolAddress || $schoolLocation)
                    <p class="school-meta">
                        @if($schoolAddress){{ $schoolAddress }}@endif
                        @if($schoolAddress && $schoolLocation), @endif
                        {{ $schoolLocation }}
                    </p>
                @endif

                @if($udiseCode || $schoolCode)
                    <p class="school-meta">
                        @if($udiseCode)UDISE No : {{ $udiseCode }}@endif
                        @if($udiseCode && $schoolCode)&nbsp; | &nbsp;@endif
                        @if($schoolCode)School Code : {{ $schoolCode }}@endif
                    </p>
                @endif

                @if($schoolPhone || $schoolEmail)
                    <p class="school-meta">
                        @if($schoolPhone)Phone No : {{ $schoolPhone }}@endif
                        @if($schoolPhone && $schoolEmail)&nbsp; | &nbsp;@endif
                        @if($schoolEmail)Email : {{ $schoolEmail }}@endif
                    </p>
                @endif

                <p class="cert-type-label">School Leaving Certificate</p>

                <div class="cert-meta-bar">
                    <div>
                        <strong>Certificate No:</strong>
                        {{ $certificate->certificate_no }}
                    </div>
                    <div>
                        <strong>Academic Year:</strong>
                        {{ $certificate->academic_year ?: '—' }}
                    </div>
                    <div>
                        <strong>Issue Date:</strong>
                        {{ $issueDate ?: '—' }}
                    </div>
                </div>
            </div>

            {{-- STUDENT INFORMATION --}}
            <div class="section-title">Student Information</div>
            <div class="table-responsive">
                <table class="certificate-table">
                    <tbody>
                        <tr>
                            <th>Student ID</th>
                            <td>{{ $student->student_id ?? '—' }}</td>
                            <th>GR / Register No.</th>
                            <td>{{ $student->register_no ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Student Name</th>
                            <td>{{ $fullName ?: '—' }}</td>
                            <th>Marathi Name</th>
                            <td>{{ $marathiName ?: '—' }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $dob ?: '—' }}</td>
                            <th>Gender</th>
                            <td>{{ $student->gender ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Aadhaar Number</th>
                            <td>{{ $student->aadhar_card_no ?? '—' }}</td>
                            <th>PEN Number</th>
                            <td>{{ $student->pen_no ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>APAAR ID</th>
                            <td>{{ $student->appar_id ?? '—' }}</td>
                            <th>Saral ID</th>
                            <td>{{ $student->saral_id ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Nationality</th>
                            <td>{{ $student->nationality ?? 'Indian' }}</td>
                            <th>Mother Tongue</th>
                            <td>{{ $student->mother_tongue ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Religion</th>
                            <td>{{ $student->religion ?? '—' }}</td>
                            <th>Category</th>
                            <td>{{ $student->category ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- ACADEMIC INFORMATION --}}
            <div class="section-title">Academic Information</div>
            <div class="table-responsive">
                <table class="certificate-table">
                    <tbody>
                        <tr>
                            <th>Admission Class</th>
                            <td>{{ $student->admission_class ?? '—' }}</td>
                            <th>Current / Last Class</th>
                            <td>{{ $certificate->last_class ?: ($student->class ?? '—') }}</td>
                        </tr>
                        <tr>
                            <th>Division</th>
                            <td>{{ $certificate->last_division ?: ($student->section ?? '—') }}</td>
                            <th>Roll Number</th>
                            <td>{{ $student->roll_number ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Medium</th>
                            <td>{{ $student->medium ?? '—' }}</td>
                            <th>Stream</th>
                            <td>{{ $student->stream ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Admission Type</th>
                            <td>{{ $student->admission_type ?? '—' }}</td>
                            <th>Academic Year</th>
                            <td>{{ $certificate->academic_year ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- LEAVING INFORMATION --}}
            <div class="section-title">Leaving Information</div>
            <div class="table-responsive">
                <table class="certificate-table">
                    <tbody>
                        <tr>
                            <th>Date of Leaving</th>
                            <td>{{ $leavingDate ?: '—' }}</td>
                            <th>Reason for Leaving</th>
                            <td>{{ $reason ?: '—' }}</td>
                        </tr>
                        <tr>
                            <th>Conduct</th>
                            <td>{{ $certificate->conduct ?: '—' }}</td>
                            <th>Progress</th>
                            <td>{{ $certificate->progress ?: '—' }}</td>
                        </tr>
                        <tr>
                            <th>Certificate Status</th>
                            <td>{{ ucfirst($certificate->status) }}</td>
                            <th>Certificate Issue Date</th>
                            <td>{{ $issueDate ?: '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- REMARKS --}}
            @if($certificate->remarks)
                <div class="section-title">Remarks</div>
                <div class="remarks-box">
                    {!! nl2br(e($certificate->remarks)) !!}
                </div>
            @endif

            {{-- DECLARATION --}}
            <div class="certificate-declaration">
                <p class="mb-0">
                    This is to certify that the above information is recorded in the school records
                    and this School Leaving Certificate has been issued accordingly.
                </p>
            </div>

            {{-- SIGNATURES --}}
            <div class="signatures-row">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <strong>Class Teacher</strong>
                </div>
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <strong>Clerk</strong>
                </div>
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <strong>Principal</strong>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection