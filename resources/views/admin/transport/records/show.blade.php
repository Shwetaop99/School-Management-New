@extends('layouts.app')

@section('title', '')

@section('content')

<style>

/* =========================================================
   TRANSPORT RECORD PAGE
========================================================= */

.transport-show-page {
    width: 100%;
    min-height: calc(100vh - 60px);
    background: #f4f7fb;
    padding: 28px 0 40px;
    color: #172033;
}

.transport-container {
    width: 100%;
    padding: 0 28px;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.page-header {
    background: linear-gradient(
        135deg,
        #1769d1 0%,
        #159cc7 100%
    );

    border-radius: 18px;
    padding: 25px 30px;
    margin-bottom: 22px;

    color: #fff;

    box-shadow:
        0 10px 28px rgba(23, 105, 209, .18);

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 16px;

    min-width: 0;
}

.page-header-icon {
    width: 54px;
    height: 54px;
    min-width: 54px;

    border-radius: 15px;

    background: rgba(255, 255, 255, .18);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 25px;

    backdrop-filter: blur(5px);
}

.page-header h1 {
    margin: 0;

    font-size: 23px;
    font-weight: 700;

    letter-spacing: -.3px;
}

.page-header p {
    margin: 5px 0 0;

    font-size: 13px;

    color: rgba(255, 255, 255, .84);
}


/* =========================================================
   HEADER ACTIONS
========================================================= */

.header-actions {
    display: flex;
    align-items: center;

    gap: 10px;

    flex-wrap: wrap;

    flex-shrink: 0;
}

.header-btn {
    height: 40px;

    padding: 0 16px;

    border-radius: 10px;

    border: 1px solid rgba(255, 255, 255, .25);

    background: rgba(255, 255, 255, .14);

    color: #fff;

    text-decoration: none;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 8px;

    font-size: 12.5px;
    font-weight: 600;

    transition: all .2s ease;

    cursor: pointer;

    font-family: inherit;
}

.header-btn:hover {
    background: #fff;
    color: #1769d1;

    transform: translateY(-1px);
}


/* =========================================================
   PRINT ONLY HEADER
========================================================= */

.print-only {
    display: none;
}


/* =========================================================
   MAIN DETAILS CARD
========================================================= */

.details-card {
    background: #fff;

    border: 1px solid #e5ebf3;

    border-radius: 16px;

    box-shadow:
        0 5px 20px rgba(15, 23, 42, .06);

    overflow: hidden;
}


/* =========================================================
   RECORD TOP
========================================================= */

.record-top {
    padding: 24px 26px;

    border-bottom: 1px solid #e8edf4;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;
}

.student-heading {
    display: flex;
    align-items: center;

    gap: 15px;
}

.student-avatar {
    width: 58px;
    height: 58px;
    min-width: 58px;

    border-radius: 16px;

    background: linear-gradient(
        135deg,
        #dbeafe,
        #e0f2fe
    );

    color: #1769d1;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 22px;
    font-weight: 700;
}

.student-heading h2 {
    margin: 0;

    font-size: 18px;
    font-weight: 700;

    color: #172033;
}

.student-heading p {
    margin: 5px 0 0;

    font-size: 12px;

    color: #64748b;
}


/* =========================================================
   STATUS BADGES
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;

    gap: 6px;

    padding: 7px 12px;

    border-radius: 999px;

    font-size: 11.5px;
    font-weight: 700;

    text-transform: capitalize;
}

.status-active {
    background: #dcfce7;
    color: #15803d;
}

.status-inactive {
    background: #fee2e2;
    color: #dc2626;
}

.status-partial {
    background: #ffedd5;
    color: #c2410c;
}

.status-pending {
    background: #fee2e2;
    color: #dc2626;
}


/* =========================================================
   DETAILS SECTION
========================================================= */

.details-section {
    padding: 25px 26px;

    border-bottom: 1px solid #edf1f5;
}

.details-section:last-child {
    border-bottom: none;
}


/* =========================================================
   SECTION HEADING
========================================================= */

.section-heading {
    display: flex;
    align-items: center;

    gap: 10px;

    margin-bottom: 20px;
}

.section-icon {
    width: 36px;
    height: 36px;

    border-radius: 10px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 16px;
}

.section-icon.blue {
    background: #dbeafe;
    color: #1769d1;
}

.section-icon.orange {
    background: #ffedd5;
    color: #ea580c;
}

.section-icon.cyan {
    background: #cffafe;
    color: #0891b2;
}

.section-icon.green {
    background: #dcfce7;
    color: #16a34a;
}

.section-heading h3 {
    margin: 0;

    font-size: 15px;
    font-weight: 700;

    color: #172033;
}

.section-heading span {
    display: block;

    margin-top: 2px;

    font-size: 11px;

    color: #94a3b8;
}


/* =========================================================
   INFO GRID
========================================================= */

.info-grid {
    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 14px;
}

.info-item {
    background: #f8fafc;

    border: 1px solid #e6edf5;

    border-radius: 13px;

    padding: 15px 16px;

    min-height: 72px;

    transition: all .2s ease;
}

.info-item:hover {
    background: #fff;

    border-color: #d5e1ee;

    box-shadow:
        0 4px 12px rgba(15, 23, 42, .04);
}

.info-label {
    display: flex;
    align-items: center;

    gap: 6px;

    margin-bottom: 7px;

    font-size: 10.5px;

    color: #94a3b8;

    font-weight: 600;

    text-transform: uppercase;

    letter-spacing: .35px;
}

.info-label i {
    font-size: 11px;

    color: #1769d1;
}

.info-value {
    font-size: 13px;

    color: #172033;

    font-weight: 600;

    word-break: break-word;
}


/* =========================================================
   FOOTER ACTIONS
========================================================= */

.record-actions {
    padding: 20px 26px;

    background: #fbfcfe;

    border-top: 1px solid #edf1f5;

    display: flex;
    justify-content: flex-end;

    gap: 10px;
}

.action-btn {
    height: 40px;

    padding: 0 17px;

    border-radius: 10px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 8px;

    font-size: 12.5px;

    font-weight: 600;

    text-decoration: none;

    transition: all .2s ease;

    border: 1px solid transparent;

    cursor: pointer;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.btn-back {
    background: #fff;

    color: #475569;

    border-color: #dce4ed;
}

.btn-back:hover {
    background: #f8fafc;

    color: #172033;

    transform: translateY(-1px);
}


/* =========================================================
   EDIT BUTTON
========================================================= */

.btn-edit {
    background: linear-gradient(
        135deg,
        #1769d1,
        #237de0
    );

    color: #fff;

    box-shadow:
        0 5px 12px rgba(23, 105, 209, .18);
}

.btn-edit:hover {
    color: #fff;

    transform: translateY(-1px);

    box-shadow:
        0 7px 16px rgba(23, 105, 209, .25);
}


/* =========================================================
   PRINT CSS
========================================================= */

@media print {

    @page {
        size: A4;
        margin: 12mm;
    }

    html,
    body {
        width: 100% !important;

        margin: 0 !important;
        padding: 0 !important;

        background: #fff !important;
    }

    body * {
        visibility: hidden !important;
    }

    .transport-print-area,
    .transport-print-area * {
        visibility: visible !important;
    }

    .transport-print-area {
        position: absolute !important;

        left: 0 !important;
        top: 0 !important;

        width: 100% !important;

        margin: 0 !important;
        padding: 0 !important;

        background: #fff !important;
    }

    .no-print {
        display: none !important;
        visibility: hidden !important;
    }

    .print-only {
        display: block !important;
        visibility: visible !important;
    }


    /* =====================================================
       SCHOOL PRINT HEADER
    ===================================================== */

    .print-title {
        width: 100% !important;

        text-align: center;

        margin: 0 0 18px !important;

        padding: 0 0 12px !important;

        border-bottom: 2px solid #1769d1 !important;

        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .print-school-header {
        width: 100% !important;

        display: flex !important;

        align-items: center !important;

        justify-content: center !important;

        gap: 14px !important;

        margin-bottom: 8px !important;
    }

    .print-school-logo {
        width: 62px !important;

        height: 62px !important;

        object-fit: contain !important;

        display: block !important;

        flex-shrink: 0 !important;
    }

    .print-school-details {
        text-align: left !important;

        max-width: 560px !important;
    }

    .print-school-name {
        margin: 0 !important;

        font-size: 19px !important;

        line-height: 1.2 !important;

        font-weight: 800 !important;

        color: #172033 !important;

        letter-spacing: .1px !important;
    }

    .print-school-address {
        margin: 4px 0 0 !important;

        font-size: 9.5px !important;

        line-height: 1.35 !important;

        color: #64748b !important;
    }

    .print-school-contact {
        margin: 3px 0 0 !important;

        font-size: 9px !important;

        line-height: 1.3 !important;

        color: #64748b !important;
    }

    .print-document-title {
        margin: 8px 0 0 !important;

        font-size: 18px !important;

        line-height: 1.2 !important;

        font-weight: 800 !important;

        color: #1769d1 !important;

        text-transform: uppercase !important;

        letter-spacing: .8px !important;
    }

    .print-document-subtitle {
        margin: 3px 0 0 !important;

        font-size: 9.5px !important;

        color: #64748b !important;
    }


    /* =====================================================
       MAIN PRINT CONTAINER
    ===================================================== */

    .transport-print-area .transport-container {
        width: 100% !important;

        max-width: none !important;

        padding: 0 !important;

        margin: 0 !important;
    }


    /* =====================================================
       DETAILS CARD
    ===================================================== */

    .transport-print-area .details-card {
        width: 100% !important;

        margin: 0 !important;

        padding: 0 !important;

        background: #fff !important;

        border: 1px solid #d9e2ec !important;

        border-radius: 8px !important;

        box-shadow: none !important;

        overflow: visible !important;
    }


    /* =====================================================
       RECORD HEADER
    ===================================================== */

    .transport-print-area .record-top {
        padding: 18px 20px !important;

        border-bottom: 1px solid #dce4ed !important;

        break-inside: avoid !important;

        page-break-inside: avoid !important;
    }


    /* =====================================================
       DETAILS SECTIONS
    ===================================================== */

    .transport-print-area .details-section {
        padding: 18px 20px !important;

        border-bottom: 1px solid #e5ebf3 !important;

        break-inside: avoid !important;

        page-break-inside: avoid !important;
    }


    /* =====================================================
       INFO GRID
    ===================================================== */

    .transport-print-area .info-grid {
        display: grid !important;

        grid-template-columns: repeat(3, 1fr) !important;

        gap: 10px !important;
    }

    .transport-print-area .info-item {
        min-height: auto !important;

        padding: 11px 12px !important;

        background: #f8fafc !important;

        border: 1px solid #e2e8f0 !important;

        border-radius: 8px !important;

        box-shadow: none !important;

        break-inside: avoid !important;

        page-break-inside: avoid !important;
    }


    /* =====================================================
       SECTION HEADINGS
    ===================================================== */

    .transport-print-area .section-heading {
        margin-bottom: 13px !important;

        break-inside: avoid !important;

        page-break-inside: avoid !important;
    }

    .transport-print-area .section-icon {
        width: 30px !important;

        height: 30px !important;

        border-radius: 8px !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;
    }

    .transport-print-area .section-heading h3 {
        font-size: 13px !important;
    }

    .transport-print-area .section-heading span {
        font-size: 9px !important;
    }


    /* =====================================================
       INFO TEXT
    ===================================================== */

    .transport-print-area .info-label {
        font-size: 8.5px !important;

        margin-bottom: 5px !important;
    }

    .transport-print-area .info-value {
        font-size: 10.5px !important;
    }


    /* =====================================================
       STUDENT AVATAR
    ===================================================== */

    .transport-print-area .student-avatar {
        width: 46px !important;

        height: 46px !important;

        min-width: 46px !important;

        border-radius: 12px !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;
    }

    .transport-print-area .student-heading h2 {
        font-size: 15px !important;
    }

    .transport-print-area .student-heading p {
        font-size: 9.5px !important;
    }


    /* =====================================================
       STATUS BADGES
    ===================================================== */

    .transport-print-area .status-badge {
        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;

        font-size: 9px !important;

        padding: 5px 9px !important;
    }

    .transport-print-area .status-active {
        background: #dcfce7 !important;

        color: #15803d !important;
    }

    .transport-print-area .status-inactive {
        background: #fee2e2 !important;

        color: #dc2626 !important;
    }

    .transport-print-area .status-partial {
        background: #ffedd5 !important;

        color: #c2410c !important;
    }

    .transport-print-area .status-pending {
        background: #fee2e2 !important;

        color: #dc2626 !important;
    }


    /* =====================================================
       PRINT CLEANUP
    ===================================================== */

    .transport-print-area,
    .transport-print-area * {
        box-shadow: none !important;

        text-shadow: none !important;
    }

    .transport-print-area .section-icon,
    .transport-print-area .student-avatar,
    .transport-print-area .status-badge,
    .transport-print-area .info-item {
        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;
    }

    .transport-print-area a {
        text-decoration: none !important;

        color: inherit !important;
    }

    .transport-print-area a::after {
        content: none !important;
    }
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 992px) {

    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}


@media (max-width: 768px) {

    .transport-container {
        padding: 0 16px;
    }

    .page-header {
        padding: 20px;

        align-items: flex-start;

        flex-direction: column;
    }

    .header-actions {
        width: 100%;
    }

    .header-btn {
        flex: 1;
    }

    .record-top {
        align-items: flex-start;

        flex-direction: column;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .details-section {
        padding: 20px;
    }

    .record-actions {
        padding: 18px 20px;

        flex-direction: column;
    }

    .action-btn {
        width: 100%;
    }
}

</style>


{{-- =========================================================
     TRANSPORT PRINT AREA
========================================================= --}}

<div class="transport-show-page transport-print-area">

    <div class="transport-container">


        {{-- =====================================================
             SCREEN HEADER
        ====================================================== --}}

        <div class="page-header no-print">

            <div class="page-header-left">

                <div class="page-header-icon">
                    <i class="bi bi-bus-front-fill"></i>
                </div>

                <div>

                    <h1>
                        Transport Record
                    </h1>

                    <p>
                        View complete transport assignment details
                    </p>

                </div>

            </div>


            {{-- HEADER ACTIONS --}}

            <div class="header-actions">

                {{-- BACK --}}

                <a
                    href="{{ route('admin.transport.records.index') }}"
                    class="header-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Records
                </a>


                {{-- PRINT --}}

                <button
                    type="button"
                    class="header-btn"
                    onclick="printTransportRecord()"
                >
                    <i class="bi bi-printer-fill"></i>
                    Print
                </button>


                {{-- REAL PDF DOWNLOAD --}}

                <a
                    href="{{ route('admin.transport.records.download-pdf', ['transportRecord' => $transportRecord->id]) }}"
                    class="header-btn"
                    title="Download Transport Record PDF"
                >
                    <i class="bi bi-download"></i>
                    Download PDF
                </a>


                {{-- EDIT --}}

                <a
                    href="{{ route('admin.transport.records.edit', ['transportRecord' => $transportRecord->id]) }}"
                    class="header-btn"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </a>

            </div>

        </div>


        {{-- =====================================================
             PRINT HEADER
        ====================================================== --}}

        <div class="print-only print-title">

            <div class="print-school-header">

                {{-- SCHOOL LOGO --}}

                <img
                    src="{{ $school?->logo_url ?? asset('images/gurukullogo.png') }}"
                    alt="{{ $school?->school_name ?? 'School Logo' }}"
                    class="print-school-logo"
                >


                {{-- SCHOOL DETAILS --}}

                <div class="print-school-details">

                    <div class="print-school-name">
                        {{ $school?->school_name ?? 'School Name' }}
                    </div>


                    @php
                        $schoolAddress = collect([
                            $school?->address,
                            $school?->city,
                            $school?->district,
                            $school?->state,
                            $school?->pincode,
                        ])->filter()->implode(', ');
                    @endphp


                    @if($schoolAddress)

                        <div class="print-school-address">
                            {{ $schoolAddress }}
                        </div>

                    @endif


                    @if($school?->phone || $school?->email)

                        <div class="print-school-contact">

                            @if($school?->phone)
                                Phone: {{ $school->phone }}
                            @endif

                            @if($school?->phone && $school?->email)
                                &nbsp; | &nbsp;
                            @endif

                            @if($school?->email)
                                Email: {{ $school->email }}
                            @endif

                        </div>

                    @endif

                </div>

            </div>


            {{-- DOCUMENT TITLE --}}

            <div class="print-document-title">
                Transport Record
            </div>



        </div>


        {{-- =====================================================
             DETAILS CARD
        ====================================================== --}}

        <div class="details-card">


            {{-- =================================================
                 RECORD HEADER
            ================================================== --}}

            <div class="record-top">

                <div class="student-heading">

                    <div class="student-avatar">

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <div>

                        <h2>
                            {{ $transportRecord->student?->full_name ?? 'Student Not Available' }}
                        </h2>

                        <p>

                            Student ID:
                            {{ $transportRecord->student?->student_id ?? '—' }}

                            @if($transportRecord->student?->roll_number)

                                &nbsp; • &nbsp;

                                Roll No:
                                {{ $transportRecord->student->roll_number }}

                            @endif

                        </p>

                    </div>

                </div>


                {{-- TRANSPORT STATUS --}}

                @if($transportRecord->transport_status === 'active')

                    <span class="status-badge status-active">

                        <i class="bi bi-check-circle-fill"></i>

                        Active

                    </span>

                @else

                    <span class="status-badge status-inactive">

                        <i class="bi bi-x-circle-fill"></i>

                        Inactive

                    </span>

                @endif

            </div>


            {{-- =================================================
                 STUDENT INFORMATION
            ================================================== --}}

            <div class="details-section">

                <div class="section-heading">

                    <div class="section-icon blue">

                        <i class="bi bi-person-vcard-fill"></i>

                    </div>

                    <div>

                        <h3>
                            Student Information
                        </h3>

                        <span>
                            Student and parent details
                        </span>

                    </div>

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-person"></i>

                            Student Name

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->full_name ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-card-text"></i>

                            Student ID

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->student_id ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-123"></i>

                            Roll No.

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->roll_number ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-mortarboard"></i>

                            Class

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->class ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-grid-3x3-gap"></i>

                            Division

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->section ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-people"></i>

                            Parent Name

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->father_name ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-telephone"></i>

                            Parent Phone

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->father_phone ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-geo-alt"></i>

                            Address

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->student?->address ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 TRANSPORT INFORMATION
            ================================================== --}}

            <div class="details-section">

                <div class="section-heading">

                    <div class="section-icon orange">

                        <i class="bi bi-bus-front-fill"></i>

                    </div>

                    <div>

                        <h3>
                            Transport Information
                        </h3>

                        <span>
                            Transport assignment details
                        </span>

                    </div>

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-signpost-2"></i>

                            Route

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->route ?: '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-bus-front"></i>

                            Vehicle

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->vehicle ?: '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-geo-alt"></i>

                            Pickup Point

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->pickup_point ?: '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-geo-alt-fill"></i>

                            Drop Point

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->drop_point ?: '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-toggle-on"></i>

                            Transport Status

                        </div>

                        <div class="info-value">

                            @if($transportRecord->transport_status === 'active')

                                <span class="status-badge status-active">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Active

                                </span>

                            @else

                                <span class="status-badge status-inactive">

                                    <i class="bi bi-x-circle-fill"></i>

                                    Inactive

                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-calendar-event"></i>

                            Start Date

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->start_date?->format('d M Y') ?? '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-calendar-x"></i>

                            End Date

                        </div>

                        <div class="info-value">

                            {{ $transportRecord->end_date?->format('d M Y') ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 TRAVEL DETAILS
            ================================================== --}}

            <div class="details-section">

                <div class="section-heading">

                    <div class="section-icon cyan">

                        <i class="bi bi-clock-history"></i>

                    </div>

                    <div>

                        <h3>
                            Travel Details
                        </h3>

                        <span>
                            Daily transportation timing
                        </span>

                    </div>

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-truck"></i>

                            Transport Type

                        </div>

                        <div class="info-value">

                            {{
                                $transportRecord->transport_type
                                ? ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $transportRecord->transport_type
                                    )
                                )
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-clock"></i>

                            Pickup Time

                        </div>

                        <div class="info-value">

                            {{
                                $transportRecord->pickup_time
                                ? \Carbon\Carbon::parse(
                                    $transportRecord->pickup_time
                                )->format('h:i A')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-clock-fill"></i>

                            Drop Time

                        </div>

                        <div class="info-value">

                            {{
                                $transportRecord->drop_time
                                ? \Carbon\Carbon::parse(
                                    $transportRecord->drop_time
                                )->format('h:i A')
                                : '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FEE INFORMATION
            ================================================== --}}

            <div class="details-section">

                <div class="section-heading">

                    <div class="section-icon green">

                        <i class="bi bi-cash-stack"></i>

                    </div>

                    <div>

                        <h3>
                            Fee Information
                        </h3>

                        <span>
                            Transport fee and payment details
                        </span>

                    </div>

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-currency-rupee"></i>

                            Transport Fee

                        </div>

                        <div class="info-value">

                            @if($transportRecord->transport_fee !== null)

                                ₹ {{ number_format(
                                    (float) $transportRecord->transport_fee,
                                    2
                                ) }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-arrow-repeat"></i>

                            Fee Frequency

                        </div>

                        <div class="info-value">

                            {{
                                $transportRecord->fee_frequency
                                ? ucfirst($transportRecord->fee_frequency)
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">

                            <i class="bi bi-credit-card"></i>

                            Payment Status

                        </div>

                        <div class="info-value">

                            @if($transportRecord->payment_status === 'paid')

                                <span class="status-badge status-active">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Paid

                                </span>

                            @elseif($transportRecord->payment_status === 'partially_paid')

                                <span class="status-badge status-partial">

                                    <i class="bi bi-clock-fill"></i>

                                    Partially Paid

                                </span>

                            @else

                                <span class="status-badge status-pending">

                                    <i class="bi bi-exclamation-circle-fill"></i>

                                    Pending

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FOOTER ACTIONS
                 ONLY BACK + EDIT
            ================================================== --}}

            <div class="record-actions no-print">

                <a
                    href="{{ route('admin.transport.records.index') }}"
                    class="action-btn btn-back"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>


                <a
                    href="{{ route('admin.transport.records.edit', ['transportRecord' => $transportRecord->id]) }}"
                    class="action-btn btn-edit"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit Record
                </a>

            </div>

        </div>

    </div>

</div>


<script>

/* =========================================================
   PRINT TRANSPORT RECORD
========================================================= */

function printTransportRecord() {

    const originalTitle = document.title;

    document.title = 'Transport Record';

    window.print();

    setTimeout(function () {

        document.title = originalTitle;

    }, 1000);
}

</script>

@endsection
