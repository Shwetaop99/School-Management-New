
@extends('layouts.app')

@section('title', 'Transport Record | Admin')
@section('page-title', 'Transport Record')

@section('content')

<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM — TRANSPORT RECORD
========================================================= */

.transport-show-page {
    --diary-bg: #f5f3e9;
    --diary-paper: #fffefa;
    --diary-ink: #263b35;
    --diary-green: #34775a;
    --diary-dark-green: #245a43;
    --diary-green-light: #eaf3e9;
    --diary-wood: #a87543;
    --diary-cyan: #527f79;
    --diary-red: #bd5148;
    --diary-border: #e5e1d3;
    --diary-muted: #7b847b;

    width: 100%;
    min-height: calc(100vh - 60px);
    padding: 22px 0 36px;
    background: var(--diary-bg);
    color: var(--diary-ink);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    font-size: 12px;
}

.transport-show-page *,
.transport-show-page *::before,
.transport-show-page *::after {
    box-sizing: border-box;
}

.transport-container {
    width: 100%;
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 24px;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 19px 22px;
    margin-bottom: 18px;
    background: var(--diary-paper);
    border: 1px solid var(--diary-border);
    border-left: 5px solid var(--diary-green);
    border-radius: 10px;
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045);
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.page-header-icon {
    width: 46px;
    height: 46px;
    min-width: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: var(--diary-green-light);
    color: var(--diary-green);
    font-size: 22px;
}

.page-breadcrumb {
    margin-bottom: 5px;
    color: var(--diary-muted);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.page-header h1 {
    margin: 0;
    color: var(--diary-ink);
    font-size: 22px;
    line-height: 1.3;
    font-weight: 750;
    letter-spacing: -.35px;
}

.page-header p {
    margin: 4px 0 0;
    color: var(--diary-muted);
    font-size: 11px;
    line-height: 1.5;
}

/* =========================================================
   HEADER ACTIONS
========================================================= */

.header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 7px;
    flex-shrink: 0;
}

.header-btn {
    min-height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 12px;
    border: 1px solid var(--diary-border);
    border-radius: 7px;
    background: var(--diary-paper);
    color: var(--diary-ink);
    font-family: inherit;
    font-size: 10.5px;
    font-weight: 650;
    text-decoration: none;
    cursor: pointer;
    transition: background .18s ease, color .18s ease,
                border-color .18s ease, transform .18s ease;
}

.header-btn:hover {
    background: var(--diary-green-light);
    border-color: #b9d1bd;
    color: var(--diary-dark-green);
    text-decoration: none;
    transform: translateY(-1px);
}

.header-btn.primary {
    background: var(--diary-green);
    border-color: var(--diary-green);
    color: #fffefa;
}

.header-btn.primary:hover {
    background: var(--diary-dark-green);
    border-color: var(--diary-dark-green);
    color: #fffefa;
}

.header-btn:focus-visible,
.action-btn:focus-visible {
    outline: 2px solid var(--diary-wood);
    outline-offset: 3px;
}

/* =========================================================
   PRINT-ONLY HEADER
========================================================= */

.print-only {
    display: none;
}

/* =========================================================
   MAIN DETAILS CARD
========================================================= */

.details-card {
    overflow: hidden;
    background: var(--diary-paper);
    border: 1px solid var(--diary-border);
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

/* =========================================================
   RECORD TOP
========================================================= */

.record-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 20px 23px;
    background: #fbfaf3;
    border-bottom: 1px solid var(--diary-border);
}

.student-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.student-avatar {
    width: 49px;
    height: 49px;
    min-width: 49px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e4d4;
    border-radius: 10px;
    background: var(--diary-green-light);
    color: var(--diary-green);
    font-size: 21px;
}

.student-heading h2 {
    margin: 0;
    color: var(--diary-ink);
    font-size: 16px;
    line-height: 1.4;
    font-weight: 750;
    overflow-wrap: anywhere;
}

.student-heading p {
    margin: 4px 0 0;
    color: var(--diary-muted);
    font-size: 10.5px;
    line-height: 1.6;
}

/* =========================================================
   STATUS BADGES
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 5px 9px;
    border: 1px solid transparent;
    border-radius: 6px;
    font-size: 10px;
    line-height: 1.4;
    font-weight: 750;
    text-transform: capitalize;
    white-space: nowrap;
}

.status-active {
    background: #eaf3e9;
    border-color: #cfe2ce;
    color: #286846;
}

.status-inactive {
    background: #f9e9e5;
    border-color: #edcfca;
    color: var(--diary-red);
}

.status-partial {
    background: #f8eee0;
    border-color: #ead5b7;
    color: #94602c;
}

.status-pending {
    background: #f9e9e5;
    border-color: #edcfca;
    color: var(--diary-red);
}

/* =========================================================
   DETAILS SECTIONS
========================================================= */

.details-section {
    padding: 22px 23px;
    border-bottom: 1px solid var(--diary-border);
}

.details-section:last-child {
    border-bottom: none;
}

/* =========================================================
   NUMBERED SECTION HEADINGS
========================================================= */

.section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.section-icon {
    position: relative;
    width: 35px;
    height: 35px;
    min-width: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 15px;
}

.section-icon.blue {
    background: var(--diary-green-light);
    color: var(--diary-green);
}

.section-icon.orange {
    background: #f7ecdf;
    color: var(--diary-wood);
}

.section-icon.cyan {
    background: #e6efec;
    color: var(--diary-cyan);
}

.section-icon.green {
    background: #eaf3e9;
    color: var(--diary-dark-green);
}

.section-heading h3 {
    margin: 0;
    color: var(--diary-ink);
    font-size: 13px;
    line-height: 1.4;
    font-weight: 750;
}

.section-heading span {
    display: block;
    margin-top: 3px;
    color: var(--diary-muted);
    font-size: 10px;
    line-height: 1.4;
}

/* =========================================================
   INFORMATION GRID
========================================================= */

.info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.info-item {
    min-width: 0;
    min-height: 69px;
    padding: 12px 13px;
    border: 1px solid var(--diary-border);
    border-radius: 8px;
    background: #fbfaf4;
    transition: background .18s ease, border-color .18s ease;
}

.info-item:hover {
    background: var(--diary-paper);
    border-color: #cbd8c9;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 7px;
    color: var(--diary-muted);
    font-size: 9px;
    font-weight: 750;
    letter-spacing: .55px;
    text-transform: uppercase;
    line-height: 1.4;
}

.info-label i {
    color: var(--diary-green);
    font-size: 11px;
}

.info-value {
    color: var(--diary-ink);
    font-size: 11.5px;
    font-weight: 650;
    line-height: 1.6;
    overflow-wrap: anywhere;
    word-break: break-word;
}

.info-value .status-badge {
    vertical-align: middle;
}

/* =========================================================
   FOOTER ACTIONS
========================================================= */

.record-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px;
    padding: 16px 23px;
    background: #fbfaf3;
    border-top: 1px solid var(--diary-border);
}

.action-btn {
    min-height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 14px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: inherit;
    font-size: 10.5px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: background .18s ease, color .18s ease,
                border-color .18s ease, transform .18s ease;
}

.action-btn:hover {
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-back {
    background: var(--diary-paper);
    color: var(--diary-ink);
    border-color: var(--diary-border);
}

.btn-back:hover {
    background: var(--diary-green-light);
    color: var(--diary-dark-green);
    border-color: #b9d1bd;
}

.btn-edit {
    background: var(--diary-green);
    color: #fffefa;
    border-color: var(--diary-green);
}

.btn-edit:hover {
    background: var(--diary-dark-green);
    color: #fffefa;
    border-color: var(--diary-dark-green);
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
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        color: #263b35 !important;
        font-family: Arial, sans-serif !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .no-print {
        display: none !important;
        visibility: hidden !important;
    }

    .print-only {
        display: block !important;
        visibility: visible !important;
    }

    .print-title {
        width: 100% !important;
        margin: 0 0 15px !important;
        padding: 0 0 11px !important;
        border-bottom: 2px solid #34775a !important;
        text-align: center !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .print-school-header {
        width: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 13px !important;
        margin-bottom: 8px !important;
    }

    .print-school-logo {
        display: block !important;
        width: 58px !important;
        height: 58px !important;
        flex-shrink: 0 !important;
        object-fit: contain !important;
    }

    .print-school-details {
        max-width: 560px !important;
        text-align: left !important;
    }

    .print-school-name {
        margin: 0 !important;
        color: #263b35 !important;
        font-size: 18px !important;
        line-height: 1.25 !important;
        font-weight: 800 !important;
    }

    .print-school-address {
        margin: 4px 0 0 !important;
        color: #626d65 !important;
        font-size: 9px !important;
        line-height: 1.4 !important;
    }

    .print-school-contact {
        margin: 3px 0 0 !important;
        color: #626d65 !important;
        font-size: 8.5px !important;
        line-height: 1.4 !important;
    }

    .print-document-title {
        margin: 8px 0 0 !important;
        color: #245a43 !important;
        font-size: 16px !important;
        line-height: 1.3 !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: .8px !important;
    }

    .transport-print-area .transport-container {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .transport-print-area .details-card {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        border: 1px solid #d8d8cb !important;
        border-radius: 6px !important;
        background: #fffefa !important;
        box-shadow: none !important;
    }

    .transport-print-area .record-top {
        padding: 14px 16px !important;
        border-bottom: 1px solid #e5e1d3 !important;
        background: #fbfaf3 !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .transport-print-area .details-section {
        padding: 14px 16px !important;
        border-bottom: 1px solid #e5e1d3 !important;
        break-inside: auto !important;
        page-break-inside: auto !important;
    }

    .transport-print-area .section-heading {
        margin-bottom: 10px !important;
        break-after: avoid !important;
        page-break-after: avoid !important;
    }

    .transport-print-area .section-icon {
        width: 27px !important;
        height: 27px !important;
        min-width: 27px !important;
        border-radius: 6px !important;
    }

    .transport-print-area .section-heading h3 {
        font-size: 11px !important;
    }

    .transport-print-area .section-heading span {
        font-size: 8px !important;
    }

    .transport-print-area .info-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 7px !important;
    }

    .transport-print-area .info-item {
        min-height: auto !important;
        padding: 9px 10px !important;
        background: #fbfaf4 !important;
        border: 1px solid #e5e1d3 !important;
        border-radius: 5px !important;
        box-shadow: none !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .transport-print-area .info-label {
        margin-bottom: 4px !important;
        color: #69756c !important;
        font-size: 7.5px !important;
        letter-spacing: .25px !important;
    }

    .transport-print-area .info-value {
        color: #263b35 !important;
        font-size: 9px !important;
        line-height: 1.45 !important;
    }

    .transport-print-area .student-avatar {
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        border-radius: 8px !important;
    }

    .transport-print-area .student-heading h2 {
        color: #263b35 !important;
        font-size: 13px !important;
    }

    .transport-print-area .student-heading p {
        color: #69756c !important;
        font-size: 8px !important;
    }

    .transport-print-area .status-badge {
        padding: 4px 7px !important;
        font-size: 8px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .transport-print-area .status-active {
        background: #eaf3e9 !important;
        color: #286846 !important;
    }

    .transport-print-area .status-inactive,
    .transport-print-area .status-pending {
        background: #f9e9e5 !important;
        color: #bd5148 !important;
    }

    .transport-print-area .status-partial {
        background: #f8eee0 !important;
        color: #94602c !important;
    }

    .transport-print-area .section-icon,
    .transport-print-area .student-avatar,
    .transport-print-area .info-item,
    .transport-print-area .status-badge {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .transport-print-area,
    .transport-print-area * {
        box-shadow: none !important;
        text-shadow: none !important;
    }

    .transport-print-area a {
        color: inherit !important;
        text-decoration: none !important;
    }

    .transport-print-area a::after {
        content: none !important;
    }
}

/* =========================================================
   RESPONSIVE — TABLET
========================================================= */

@media (max-width: 991.98px) {
    .transport-container {
        padding: 0 18px;
    }

    .page-header {
        align-items: flex-start;
    }

    .header-actions {
        max-width: 340px;
    }

    .info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* =========================================================
   RESPONSIVE — MOBILE
========================================================= */

@media (max-width: 767.98px) {
    .transport-show-page {
        padding: 15px 0 26px;
    }

    .transport-container {
        padding: 0 12px;
    }

    .page-header {
        flex-direction: column;
        padding: 16px;
        gap: 15px;
    }

    .page-header-left {
        align-items: flex-start;
    }

    .page-header h1 {
        font-size: 19px;
    }

    .header-actions {
        width: 100%;
        max-width: none;
        justify-content: flex-start;
    }

    .header-btn {
        flex: 1 1 auto;
        min-width: 0;
        padding: 0 10px;
    }

    .record-top {
        align-items: flex-start;
        flex-direction: column;
        padding: 17px;
    }

    .details-section {
        padding: 17px;
    }

    .record-actions {
        padding: 14px 17px;
    }

    .action-btn {
        flex: 1;
    }
}

/* =========================================================
   RESPONSIVE — SMALL MOBILE
========================================================= */

@media (max-width: 480px) {
    .transport-container {
        padding: 0 9px;
    }

    .page-header {
        padding: 13px;
    }

    .page-header-icon {
        width: 39px;
        height: 39px;
        min-width: 39px;
        font-size: 19px;
    }

    .page-header h1 {
        font-size: 17px;
    }

    .page-header p {
        font-size: 10px;
    }

    .header-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .header-btn {
        width: 100%;
        min-height: 35px;
        font-size: 9.5px;
    }

    .info-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .record-top,
    .details-section {
        padding: 14px;
    }

    .section-heading h3 {
        font-size: 12px;
    }

    .record-actions {
        flex-direction: column;
        padding: 13px 14px;
    }

    .action-btn {
        width: 100%;
        flex: auto;
    }
}

/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {
    .transport-show-page *,
    .transport-show-page *::before,
    .transport-show-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

{{-- =========================================================
     TRANSPORT PRINT AREA
========================================================= --}}

<div class="transport-show-page transport-print-area">
    <div class="transport-container">

        {{-- SCREEN HEADER --}}

        <div class="page-header no-print">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="bi bi-bus-front-fill"></i>
                </div>

                <div>
                    <div class="page-breadcrumb">
                        Transport Management / Records / View Record
                    </div>

                    <h1>Transport Record</h1>

                    <p>View complete transport assignment details</p>
                </div>
            </div>

            <div class="header-actions">
                <a
                    href="{{ route('admin.transport.records.index') }}"
                    class="header-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Records
                </a>

                <button
                    type="button"
                    class="header-btn"
                    onclick="printTransportRecord()"
                >
                    <i class="bi bi-printer-fill"></i>
                    Print
                </button>

                <a
                    href="{{ route('admin.transport.records.download-pdf', ['transportRecord' => $transportRecord->id]) }}"
                    class="header-btn"
                    title="Download Transport Record PDF"
                >
                    <i class="bi bi-download"></i>
                    Download PDF
                </a>

                <a
                    href="{{ route('admin.transport.records.edit', ['transportRecord' => $transportRecord->id]) }}"
                    class="header-btn primary"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </a>
            </div>
        </div>

        {{-- PRINT HEADER --}}

        <div class="print-only print-title">
            <div class="print-school-header">
                <img
                    src="{{ $school?->logo_url ?? asset('images/gurukullogo.png') }}"
                    alt="{{ $school?->school_name ?? 'School Logo' }}"
                    class="print-school-logo"
                >

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

            <div class="print-document-title">
                Transport Record
            </div>
        </div>

        {{-- DETAILS CARD --}}

        <div class="details-card">

            {{-- RECORD HEADER --}}

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

            {{-- 01: STUDENT INFORMATION --}}

            <div class="details-section">
                <div class="section-heading">
                    <div class="section-icon blue">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>

                    <div>
                        <h3>01. Student Information</h3>
                        <span>Student and parent details</span>
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

            {{-- 02: TRANSPORT INFORMATION --}}

            <div class="details-section">
                <div class="section-heading">
                    <div class="section-icon orange">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>

                    <div>
                        <h3>02. Transport Information</h3>
                        <span>Transport assignment details</span>
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

            {{-- 03: TRAVEL DETAILS --}}

            <div class="details-section">
                <div class="section-heading">
                    <div class="section-icon cyan">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>
                        <h3>03. Travel Details</h3>
                        <span>Daily transportation timing</span>
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

            {{-- 04: FEE INFORMATION --}}

            <div class="details-section">
                <div class="section-heading">
                    <div class="section-icon green">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div>
                        <h3>04. Fee Information</h3>
                        <span>Transport fee and payment details</span>
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

            {{-- FOOTER ACTIONS --}}

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
