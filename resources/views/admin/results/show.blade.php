
@extends('layouts.app')

@section('title', 'Student Result')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | SCHOOL SETTINGS
    |--------------------------------------------------------------------------
    */

    $school = $school ?? \App\Models\SchoolSetting::first();

    $schoolName = data_get($school, 'school_name')
        ?? data_get($school, 'name')
        ?? 'School Name';

    $schoolAddress = data_get($school, 'address')
        ?? data_get($school, 'school_address')
        ?? '';

    $schoolPhone = data_get($school, 'phone')
        ?? data_get($school, 'phone_number')
        ?? '';

    $schoolEmail = data_get($school, 'email')
        ?? data_get($school, 'school_email')
        ?? '';

    $schoolWebsite = data_get($school, 'website')
        ?? data_get($school, 'website_url')
        ?? '';

    $schoolCode = data_get($school, 'school_code') ?? '';
    $udiseCode = data_get($school, 'udise_code')
        ?? data_get($school, 'udise')
        ?? '';

    $established = data_get($school, 'established_year')
        ?? data_get($school, 'established')
        ?? '';

    /*
    |--------------------------------------------------------------------------
    | SCHOOL LOGO
    |--------------------------------------------------------------------------
    */

    $logoValue = data_get($school, 'logo')
        ?: data_get($school, 'school_logo');

    $logoSource = null;

    if ($logoValue) {
        if (\Illuminate\Support\Str::startsWith($logoValue, ['http://', 'https://'])) {
            $logoSource = $logoValue;
        } else {
            $relativeLogo = ltrim(str_replace('\\', '/', $logoValue), '/');

            $relativeLogo = preg_replace(
                '#^(public/|storage/)#',
                '',
                $relativeLogo
            );

            $possiblePaths = [
                storage_path('app/public/' . $relativeLogo),
                public_path('storage/' . $relativeLogo),
                public_path($relativeLogo),
            ];

            foreach ($possiblePaths as $possiblePath) {
                if (is_file($possiblePath)) {
                    $extension = strtolower(pathinfo($possiblePath, PATHINFO_EXTENSION));

                    $mime = match ($extension) {
                        'png' => 'image/png',
                        'jpg', 'jpeg' => 'image/jpeg',
                        'gif' => 'image/gif',
                        'webp' => 'image/webp',
                        default => null,
                    };

                    if ($mime) {
                        $logoSource = 'data:' . $mime . ';base64,' .
                            base64_encode(file_get_contents($possiblePath));
                    }

                    break;
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESULT INFORMATION
    |--------------------------------------------------------------------------
    */

    $publicationStatus = strtolower(
        trim((string) ($result->publication_status ?? 'generated'))
    );

    $resultStatus = strtolower(
        trim((string) ($result->result_status ?? ''))
    );

    $studentName = trim(
        ($result->student?->first_name ?? '') . ' ' .
        ($result->student?->middle_name ?? '') . ' ' .
        ($result->student?->last_name ?? '')
    );

    $studentName = $studentName ?: (
        $result->student?->full_name
        ?? $result->student?->name
        ?? 'N/A'
    );

    $percentage = (float) ($result->percentage ?? 0);
    $percentage = max(0, min(100, $percentage));

    $details = $result->details ?? collect();
    $subjectCount = $details->count();

    $statusConfig = match ($publicationStatus) {
        'generated' => [
            'label' => 'Generated',
            'icon' => 'bi-file-earmark-check',
            'class' => 'status-generated',
        ],
        'verified' => [
            'label' => 'Verified',
            'icon' => 'bi-check-circle',
            'class' => 'status-verified',
        ],
        'approved' => [
            'label' => 'Approved',
            'icon' => 'bi-shield-check',
            'class' => 'status-approved',
        ],
        'published' => [
            'label' => 'Published',
            'icon' => 'bi-globe2',
            'class' => 'status-published',
        ],
        default => [
            'label' => ucfirst($publicationStatus),
            'icon' => 'bi-info-circle',
            'class' => 'status-default',
        ],
    };

    $examName = $result->exam?->exam_name ?? 'N/A';
    $examType = $result->exam?->exam_type ?? 'N/A';

    $generatedDate = $result->generated_at
        ?? $result->created_at;

    $publishedDate = $result->published_at;

    $finalStatus = strtoupper($result->result_status ?? 'N/A');

    $isPassed = in_array($resultStatus, ['pass', 'passed']);
    $isFailed = in_array($resultStatus, ['fail', 'failed']);
    $isAbsent = $resultStatus === 'absent';
@endphp


<style>
/* =========================================================
   STUDENT RESULT - ADMIN PAGE
========================================================= */

.result-admin {
    --green: #286044;
    --green-dark: #17452f;
    --green-light: #e8f1eb;
    --green-soft: #f3f8f4;
    --border: #c8d9cd;
    --text: #263c30;
    --muted: #6d7c72;
    color: var(--text);
}

/* ADMIN TOP SECTION */

.admin-result-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.admin-result-heading h3 {
    font-size: 22px;
    font-weight: 800;
    color: var(--green-dark);
    margin: 0 0 5px;
}

.admin-result-heading p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
}

.admin-result-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.admin-result-buttons .btn {
    font-weight: 700;
    border-radius: 8px;
    padding: 9px 14px;
}

/* PUBLICATION CARD */

.publication-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 22px;
    box-shadow: 0 3px 12px rgba(30, 75, 48, .05);
}

.publication-card-header {
    padding: 15px 19px;
    background: var(--green-soft);
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.publication-card-header h5 {
    font-size: 15px;
    font-weight: 800;
    color: var(--green-dark);
    margin: 0;
}

.publication-card-header p {
    font-size: 11px;
    color: var(--muted);
    margin: 4px 0 0;
}

.publication-card-body {
    padding: 18px;
}

.publication-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 13px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.status-generated {
    color: #52605a;
    background: #edf1ee;
}

.status-verified {
    color: #176b58;
    background: #e5f4ed;
}

.status-approved {
    color: #87600b;
    background: #fff5d9;
}

.status-published {
    color: #146b36;
    background: #e4f5e9;
}

.status-default {
    color: #52605a;
    background: #edf1ee;
}

/* WORKFLOW */

.workflow {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.workflow-step {
    border: 1px solid #dce7df;
    border-radius: 10px;
    padding: 13px;
    background: #fafcfa;
    min-height: 105px;
}

.workflow-step.completed {
    background: #f1f8f3;
    border-color: #9fc7aa;
}

.workflow-number {
    width: 31px;
    height: 31px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #e4eee7;
    color: var(--green);
    margin-bottom: 8px;
}

.workflow-title {
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 3px;
}

.workflow-text {
    font-size: 10px;
    color: var(--muted);
}

.publication-actions {
    border-top: 1px solid #e5ece7;
    padding-top: 15px;
    margin-top: 16px;
}

.publication-actions .btn {
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 15px;
}

.published-alert {
    padding: 13px;
    border-radius: 9px;
    background: #eff9f1;
    border: 1px solid #b6dfc2;
    color: #155b2e;
    font-size: 12px;
}

.whatsapp-row {
    display: flex;
    justify-content: flex-end;
    margin-top: -8px;
    margin-bottom: 18px;
}

.whatsapp-btn {
    background: #25a95a;
    color: white;
    border: 0;
    border-radius: 8px;
    padding: 9px 15px;
    font-weight: 700;
}

/* =========================================================
   A4 RESULT SHEET
========================================================= */

.result-sheet {
    position: relative;
    width: 100%;
    max-width: 900px;
    min-height: 1120px;
    margin: 0 auto 25px;
    padding: 25px;
    background: #fff;
    border: 2px solid var(--green);
    outline: 1px solid #9bbba3;
    outline-offset: -7px;
    color: #263c30;
    box-shadow: 0 5px 20px rgba(25, 70, 43, .10);
    overflow: hidden;
    isolation: isolate;
}

.result-sheet-content {
    position: relative;
    z-index: 2;
}

/* WATERMARK */

.result-watermark {
    position: absolute;
    z-index: 0;
    width: 270px;
    height: 270px;
    object-fit: contain;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: .045;
    pointer-events: none;
}

/* SCHOOL HEADER */

.school-header {
    display: table;
    width: 100%;
    table-layout: fixed;
    padding-bottom: 14px;
    border-bottom: 2px solid var(--green);
    margin-bottom: 13px;
}

.school-logo-cell,
.school-name-cell,
.school-code-cell {
    display: table-cell;
    vertical-align: middle;
}

.school-logo-cell {
    width: 16%;
    text-align: left;
}

.school-logo {
    width: 85px;
    height: 85px;
    object-fit: contain;
}

.school-name-cell {
    width: 59%;
    text-align: center;
    padding: 0 8px;
}

.school-name {
    font-size: 21px;
    font-weight: 900;
    text-transform: uppercase;
    color: var(--green-dark);
    letter-spacing: .6px;
    margin: 0 0 7px;
}

.school-address {
    font-size: 9px;
    line-height: 1.5;
    color: #43594a;
    margin-bottom: 5px;
}

.school-contact {
    font-size: 9px;
    line-height: 1.6;
    color: #43594a;
}

.school-code-cell {
    width: 25%;
    border-left: 1px solid #a9c5b0;
    padding-left: 12px;
}

.school-code-item {
    font-size: 9px;
    line-height: 1.8;
    color: #314d3b;
}

.school-code-item strong {
    color: var(--green-dark);
}

/* TITLE */

.marksheet-title {
    text-align: center;
    margin: 13px 0 15px;
}

.marksheet-title h2 {
    display: inline-block;
    border: 1px solid var(--green);
    background: var(--green-light);
    color: var(--green-dark);
    padding: 8px 28px;
    font-size: 19px;
    font-weight: 900;
    letter-spacing: 1px;
    margin: 0;
}

.marksheet-subtitle {
    font-size: 11px;
    font-weight: 800;
    color: var(--green-dark);
    margin-top: 7px;
}

/* SHEET SECTION */

.sheet-section {
    margin-bottom: 13px;
}

.sheet-section-title {
    background: #dcecdf;
    color: var(--green-dark);
    border: 1px solid var(--green);
    padding: 7px 10px;
    font-size: 12px;
    font-weight: 900;
    margin: 0;
}

.sheet-info-table,
.sheet-marks-table,
.sheet-summary-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.sheet-info-table td {
    border: 1px solid #a9c3ae;
    padding: 8px 9px;
    font-size: 10px;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.sheet-info-table td strong {
    color: var(--green-dark);
}

/* MARKS TABLE */

.sheet-marks-table {
    font-size: 10px;
}

.sheet-marks-table th {
    background: #e8f1eb;
    color: var(--green-dark);
    border: 1px solid #8eae98;
    padding: 8px 5px;
    font-weight: 900;
    text-align: center;
    vertical-align: middle;
}

.sheet-marks-table td {
    border: 1px solid #a9c3ae;
    padding: 8px 6px;
    text-align: center;
    vertical-align: middle;
    color: #304738;
}

.sheet-marks-table tbody tr:nth-child(even) {
    background: #f7faf7;
}

.sheet-marks-table .subject-name {
    text-align: left;
    font-weight: 800;
}

.sheet-marks-table .total-row {
    background: #e8f1eb !important;
    font-weight: 900;
    color: var(--green-dark);
}

.sheet-marks-table .grade-cell {
    font-weight: 900;
}

/* SUMMARY */

.sheet-summary-table {
    margin-top: 7px;
}

.sheet-summary-table td {
    border: 1px solid var(--green);
    padding: 12px 5px;
    text-align: center;
    width: 25%;
}

.summary-heading {
    display: block;
    font-size: 9px;
    font-weight: 900;
    color: #53685a;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.summary-number {
    display: block;
    font-size: 17px;
    font-weight: 900;
    color: var(--green-dark);
}

/* FINAL RESULT */

.sheet-final-result {
    text-align: center;
    margin: 15px 0;
}

.sheet-final-label {
    font-size: 11px;
    font-weight: 900;
    color: var(--green-dark);
    margin-bottom: 6px;
}

.sheet-final-status {
    display: inline-block;
    min-width: 120px;
    padding: 8px 22px;
    border: 1px solid;
    font-size: 15px;
    font-weight: 900;
    letter-spacing: 1px;
}

.sheet-pass {
    color: #176b36;
    background: #e7f5ea;
    border-color: #9fcba9;
}

.sheet-fail {
    color: #b52c2c;
    background: #fff0ef;
    border-color: #e6b9b5;
}

.sheet-absent {
    color: #87600b;
    background: #fff5d9;
    border-color: #e5cf91;
}

.sheet-other {
    color: #52605a;
    background: #edf1ee;
    border-color: #cbd8ce;
}

/* PUBLICATION INFO */

.sheet-publication {
    text-align: center;
    font-size: 9px;
    color: #52685a;
    font-weight: 800;
    padding: 9px 5px;
    border-top: 1px solid #a9c3ae;
    border-bottom: 1px solid #a9c3ae;
    margin-top: 12px;
}

/* SIGNATURES */

.sheet-signatures {
    display: table;
    width: 100%;
    table-layout: fixed;
    margin-top: 35px;
}

.sheet-signature-cell {
    display: table-cell;
    width: 33.33%;
    text-align: center;
    vertical-align: bottom;
    height: 65px;
    font-size: 10px;
    font-weight: 800;
    color: #304738;
}

.signature-line {
    width: 80%;
    margin: 0 auto 7px;
    border-top: 1px solid #708b77;
    height: 1px;
}

.sheet-footer {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    border-top: 1px solid #c8d9cd;
    padding-top: 8px;
    margin-top: 15px;
    font-size: 8px;
    color: #66796c;
}

/* ADMIN BOTTOM */

.admin-bottom-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 25px;
}

.admin-bottom-actions .btn {
    border-radius: 8px;
    font-weight: 700;
}

/* ALERTS */

.result-admin .alert {
    border-radius: 9px;
}

/* RESPONSIVE */

@media (max-width: 767px) {
    .workflow {
        grid-template-columns: repeat(2, 1fr);
    }

    .result-sheet {
        padding: 14px;
        min-height: auto;
    }

    .school-logo {
        width: 55px;
        height: 55px;
    }

    .school-name {
        font-size: 14px;
    }

    .school-address,
    .school-contact,
    .school-code-item {
        font-size: 7px;
    }

    .sheet-info-table td {
        padding: 6px 5px;
        font-size: 8px;
    }

    .sheet-marks-table {
        font-size: 8px;
    }

    .sheet-marks-table th,
    .sheet-marks-table td {
        padding: 5px 3px;
    }

    .summary-number {
        font-size: 13px;
    }

    .sheet-summary-table td {
        padding: 8px 3px;
    }

    .summary-heading {
        font-size: 7px;
    }

    .sheet-signature-cell {
        font-size: 8px;
    }
}

/* =========================================================
   PRINT - PRINT ONLY THE A4 RESULT SHEET
========================================================= */

@page {
    size: A4 portrait;
    margin: 7mm;
}

@media print {

    html,
    body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden !important;
    }

    .result-sheet,
    .result-sheet * {
        visibility: visible !important;
    }

    .result-sheet {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-width: none !important;
        min-height: 280mm !important;
        margin: 0 !important;
        padding: 6mm !important;
        border: 1.5px solid #286044 !important;
        outline: 1px solid #9bbba3 !important;
        outline-offset: -5px !important;
        box-shadow: none !important;
        overflow: visible !important;
        background: #fff !important;
    }

    .result-sheet-content {
        position: relative !important;
        z-index: 2 !important;
    }

    .result-watermark {
        position: absolute !important;
        opacity: .045 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .sheet-section,
    .sheet-summary-table,
    .sheet-signatures,
    .school-header,
    .marksheet-title {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .sheet-marks-table tr {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .admin-result-heading,
    .publication-card,
    .whatsapp-row,
    .admin-bottom-actions,
    .alert {
        display: none !important;
    }

    .result-admin {
        margin: 0 !important;
        padding: 0 !important;
    }

    .sheet-footer {
        break-inside: avoid !important;
    }
}
/* =====================================================
   PUBLICATION FEATURE CARD — SCHOOL DIARY DESIGN
===================================================== */

.publication-feature-card {
    display: grid;
    grid-template-columns: 64% 36%;
    width: 100%;
    min-height: 315px;
    margin-bottom: 25px;
    overflow: hidden;
    background: #fffefa;
    border: 1px solid #e6dfce;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(37, 57, 43, .06);
}

/* LEFT SECTION */

.publication-main {
    position: relative;
    padding: 38px 35px 30px;
    background: #fffefa;
    border-left: 4px solid #d9b78d;
}

.publication-heading {
    display: flex;
    align-items: center;
    gap: 25px;
}

.publication-heading-icon {
    flex: 0 0 80px;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5decc;
    border-radius: 19px;
    background: #f5f2e8;
    color: #244c3b;
    font-size: 31px;
}

.publication-heading-content {
    min-width: 0;
}

.publication-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 13px;
    color: #8b8b7c;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.1px;
}

.publication-breadcrumb i {
    font-size: 10px;
    color: #b5a98e;
}

.publication-heading-content h3 {
    margin: 0 0 10px;
    color: #193c2e;
    font-family: Georgia, serif;
    font-size: clamp(25px, 2.5vw, 37px);
    font-weight: 700;
    letter-spacing: -.7px;
}

.publication-heading-content p {
    max-width: 690px;
    margin: 0;
    color: #737b74;
    font-size: 13px;
    line-height: 1.8;
}

.publication-school-label {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-top: 17px;
    color: #286348;
    font-size: 11px;
    font-weight: 800;
}

.publication-school-label i {
    font-size: 16px;
}

/* WORKFLOW STEPS */

.publication-workflow {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
    margin-top: 32px;
    padding-top: 22px;
    border-top: 1px solid #eee8db;
}

.publication-step {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    padding: 11px 8px;
    border: 1px solid #e9e5da;
    border-radius: 11px;
    background: #faf9f4;
    transition: .2s ease;
}

.publication-step.is-completed {
    border-color: #c8dfce;
    background: #f1f8f2;
}

.publication-step-icon {
    flex: 0 0 35px;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #eeeee7;
    color: #778176;
    font-size: 16px;
}

.publication-step.is-completed .publication-step-icon {
    background: #dceee0;
    color: #286348;
}

.publication-step-title {
    color: #294b39;
    font-size: 10px;
    font-weight: 900;
    white-space: nowrap;
}

.publication-step-text {
    margin-top: 4px;
    color: #8a918a;
    font-size: 9px;
    line-height: 1.3;
}

/* RIGHT GREEN PANEL */

.publication-side {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 30px 35px;
    text-align: center;
    color: #fff;
    background:
        radial-gradient(circle at 100% 0%, transparent 0 105px, rgba(255,255,255,.07) 106px 107px, transparent 108px),
        radial-gradient(circle at 0% 100%, transparent 0 90px, rgba(255,255,255,.07) 91px 92px, transparent 93px),
        linear-gradient(145deg, #294c3d, #19372c);
}

.publication-side-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 17px;
    border: 1px solid rgba(255,255,255,.22);
    border-radius: 15px;
    background: rgba(255,255,255,.10);
    color: #e9d39c;
    font-size: 22px;
}

.publication-side h4 {
    margin: 0 0 13px;
    color: #f2dfad;
    font-family: Georgia, serif;
    font-size: clamp(19px, 1.8vw, 25px);
    font-style: italic;
    font-weight: 700;
    line-height: 1.4;
}

.publication-side > p {
    max-width: 330px;
    margin: 0 auto 17px;
    color: rgba(255,255,255,.82);
    font-size: 12px;
    line-height: 1.8;
}

/* CURRENT STATUS */

.publication-current-status {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    flex-wrap: wrap;
    margin-bottom: 16px;
    color: rgba(255,255,255,.75);
    font-size: 11px;
}

.publication-current-status strong {
    display: inline-flex;
    align-items: center;
    padding: 6px 11px;
    border: 1px solid rgba(255,255,255,.22);
    border-radius: 30px;
    background: rgba(255,255,255,.12);
    color: #fff2c9;
    font-size: 11px;
}

/* ACTION BUTTON */

.publication-side-actions {
    width: 100%;
    max-width: 270px;
}

.publication-side-actions form {
    width: 100%;
}

.publication-action-btn {
    display: flex;
    align-items: center;
    width: 100%;
    min-height: 46px;
    padding: 11px 16px;
    border: 1px solid #f1e7d0;
    border-radius: 10px;
    background: #f3ead5;
    color: #193c2e;
    font-size: 12px;
    font-weight: 800;
    text-align: left;
    cursor: pointer;
    transition: .2s ease;
}

.publication-action-btn:hover {
    transform: translateY(-2px);
    background: #fff8e9;
    box-shadow: 0 6px 15px rgba(0,0,0,.12);
}

.publication-published-message {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 10px;
    background: rgba(255,255,255,.10);
    text-align: left;
}

.publication-published-message > i {
    color: #a8e1b4;
    font-size: 22px;
}

.publication-published-message strong,
.publication-published-message span {
    display: block;
}

.publication-published-message strong {
    color: #f2dfad;
    font-size: 12px;
}

.publication-published-message span {
    margin-top: 4px;
    color: rgba(255,255,255,.78);
    font-size: 10px;
    line-height: 1.5;
}

.publication-date {
    margin-top: 10px;
    color: rgba(255,255,255,.78);
    font-size: 10px;
}

.publication-pending-message {
    color: #f2dfad;
    font-size: 11px;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .publication-feature-card {
        grid-template-columns: 1fr;
    }

    .publication-side {
        min-height: 260px;
    }

    .publication-workflow {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 600px) {
    .publication-main {
        padding: 25px 18px;
    }

    .publication-heading {
        align-items: flex-start;
        flex-direction: column;
        gap: 16px;
    }

    .publication-heading-icon {
        width: 60px;
        height: 60px;
        flex-basis: 60px;
        font-size: 24px;
    }

    .publication-heading-content h3 {
        font-size: 27px;
    }

    .publication-workflow {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 23px;
    }

    .publication-step {
        align-items: flex-start;
        flex-direction: column;
        padding: 12px;
    }

    .publication-step-title {
        white-space: normal;
    }

    .publication-side {
        padding: 28px 20px;
    }
}

@media (max-width: 380px) {
    .publication-workflow {
        grid-template-columns: 1fr;
    }

    .publication-step {
        flex-direction: row;
        align-items: center;
    }
}
</style>


<div class="container-fluid py-3 result-admin">

    {{-- FLASH MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =====================================================
         ADMIN HEADING AND BUTTONS
    ====================================================== --}}

    <div class="admin-result-heading">

        <div>
            <h3>
                <i class="bi bi-file-earmark-text me-2"></i>
                Student Result
            </h3>

            <p>
                Manage publication and view the student's A4 result marksheet.
            </p>
        </div>

        <div class="admin-result-buttons">

            <a href="{{ route('admin.results.print', $result) }}"
               target="_blank"
               class="btn btn-success">
                <i class="bi bi-printer me-1"></i>
                Print Result
            </a>

            <a href="{{ route('admin.results.history', $result) }}"
               class="btn btn-outline-success">
                <i class="bi bi-clock-history me-1"></i>
                Result History
            </a>

        </div>

    </div>


  {{-- =====================================================
     PUBLICATION WORKFLOW — FEATURE CARD DESIGN
====================================================== --}}

<div class="publication-feature-card">

    {{-- LEFT CONTENT --}}
    <div class="publication-main">

        <div class="publication-heading">

            <div class="publication-heading-icon">
                <i class="bi bi-cloud-arrow-up"></i>
            </div>

            <div class="publication-heading-content">
                <div class="publication-breadcrumb">
                    RESULT MANAGEMENT
                    <i class="bi bi-chevron-right"></i>
                    PUBLICATION WORKFLOW
                </div>

                <h3>Result Publication</h3>

                <p>
                    Verify, approve and publish this student's examination
                    result through the school result management system.
                </p>

                <div class="publication-school-label">
                    <i class="bi bi-shield-check"></i>
                    School Records & Academic Result Management
                </div>
            </div>

        </div>

        {{-- WORKFLOW STEPS --}}
        <div class="publication-workflow">

            <div class="publication-step
                {{ in_array($publicationStatus, ['generated', 'verified', 'approved', 'published']) ? 'is-completed' : '' }}">

                <div class="publication-step-icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>

                <div>
                    <div class="publication-step-title">01. Generated</div>
                    <div class="publication-step-text">
                        Result generated
                    </div>
                </div>

            </div>

            <div class="publication-step
                {{ in_array($publicationStatus, ['verified', 'approved', 'published']) ? 'is-completed' : '' }}">

                <div class="publication-step-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <div class="publication-step-title">02. Verified</div>
                    <div class="publication-step-text">
                        Result verified
                    </div>
                </div>

            </div>

            <div class="publication-step
                {{ in_array($publicationStatus, ['approved', 'published']) ? 'is-completed' : '' }}">

                <div class="publication-step-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <div class="publication-step-title">03. Approved</div>
                    <div class="publication-step-text">
                        Approval completed
                    </div>
                </div>

            </div>

            <div class="publication-step
                {{ $publicationStatus === 'published' ? 'is-completed' : '' }}">

                <div class="publication-step-icon">
                    <i class="bi bi-globe2"></i>
                </div>

                <div>
                    <div class="publication-step-title">04. Published</div>
                    <div class="publication-step-text">
                        Online availability
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- RIGHT DARK GREEN PANEL --}}
    <div class="publication-side">

        <div class="publication-side-icon">
            <i class="bi bi-patch-check-fill"></i>
        </div>

        <h4>
            Every Result. <br>
            Properly Verified.
        </h4>

        <p>
            Complete the publication process to make this student's
            academic performance available online.
        </p>

        <div class="publication-current-status">
            <span>Current Status</span>

            <strong>
                <i class="bi {{ $statusConfig['icon'] }} me-1"></i>
                {{ $statusConfig['label'] }}
            </strong>
        </div>


        {{-- WORKFLOW ACTIONS --}}
        <div class="publication-side-actions">

            @if($publicationStatus === 'generated')

                <form method="POST"
                      action="{{ route('admin.results.verify', $result) }}">
                    @csrf

                    <button type="submit" class="publication-action-btn">
                        <i class="bi bi-check-circle me-2"></i>
                        Verify Result
                        <i class="bi bi-arrow-right ms-auto"></i>
                    </button>
                </form>

            @elseif($publicationStatus === 'verified')

                <form method="POST"
                      action="{{ route('admin.results.approve', $result) }}">
                    @csrf

                    <button type="submit" class="publication-action-btn">
                        <i class="bi bi-shield-check me-2"></i>
                        Approve Result
                        <i class="bi bi-arrow-right ms-auto"></i>
                    </button>
                </form>

            @elseif($publicationStatus === 'approved')

                <form method="POST"
                      action="{{ route('admin.results.publish', $result) }}">
                    @csrf

                    <button type="submit"
                            class="publication-action-btn"
                            onclick="return confirm('Are you sure you want to publish this result online?');">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        Publish Result
                        <i class="bi bi-arrow-right ms-auto"></i>
                    </button>
                </form>

            @elseif($publicationStatus === 'published')

                <div class="publication-published-message">
                    <i class="bi bi-check-circle-fill"></i>

                    <div>
                        <strong>Successfully Published</strong>
                        <span>
                            This result is available for online viewing.
                        </span>
                    </div>
                </div>

                @if($publishedDate)
                    <div class="publication-date">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ $publishedDate->format('d M Y, h:i A') }}
                    </div>
                @endif

            @else

                <div class="publication-pending-message">
                    <i class="bi bi-info-circle me-1"></i>
                    Publication status is not yet available.
                </div>

            @endif

        </div>

    </div>

</div>


    {{-- WHATSAPP NOTIFICATION --}}

    @if($publicationStatus === 'published')

        <div class="whatsapp-row">

            <a href="{{ route('admin.results.whatsapp', $result) }}"
               target="_blank"
               class="btn whatsapp-btn"
               onclick="return confirm('Open WhatsApp with the result message for this parent?');">

                <i class="bi bi-whatsapp me-1"></i>
                Send WhatsApp Notification

            </a>

        </div>

    @endif


    {{-- =====================================================
         A4 RESULT MARKSHEET
    ====================================================== --}}

    <div class="result-sheet">

        @if($logoSource)
            <img src="{{ $logoSource }}"
                 class="result-watermark"
                 alt="School watermark">
        @endif

        <div class="result-sheet-content">

            {{-- SCHOOL HEADER --}}

            <div class="school-header">

                <div class="school-logo-cell">

                    @if($logoSource)
                        <img src="{{ $logoSource }}"
                             class="school-logo"
                             alt="School Logo">
                    @endif

                </div>

                <div class="school-name-cell">

                    <h1 class="school-name">
                        {{ $schoolName }}
                    </h1>

                    @if($schoolAddress)
                        <div class="school-address">
                            {{ $schoolAddress }}
                        </div>
                    @endif

                    <div class="school-contact">

                        @if($schoolPhone)
                            Phone: {{ $schoolPhone }}
                        @endif

                        @if($schoolPhone && $schoolEmail)
                            |
                        @endif

                        @if($schoolEmail)
                            Email: {{ $schoolEmail }}
                        @endif

                        @if($schoolWebsite)
                            <br>
                            {{ $schoolWebsite }}
                        @endif

                    </div>

                </div>

                <div class="school-code-cell">

                    @if($schoolCode)
                        <div class="school-code-item">
                            <strong>School Code:</strong>
                            {{ $schoolCode }}
                        </div>
                    @endif

                    @if($udiseCode)
                        <div class="school-code-item">
                            <strong>UDISE:</strong>
                            {{ $udiseCode }}
                        </div>
                    @endif

                    @if($established)
                        <div class="school-code-item">
                            <strong>Established:</strong>
                            {{ $established }}
                        </div>
                    @endif

                </div>

            </div>


            {{-- MARKSHEET TITLE --}}

            <div class="marksheet-title">

                <h2>STATEMENT OF MARKS</h2>

                <div class="marksheet-subtitle">
                    {{ $examName }}
                    |
                    Academic Year: {{ $result->academic_year ?? 'N/A' }}
                </div>

            </div>


            {{-- STUDENT INFORMATION --}}

            <div class="sheet-section">

                <h5 class="sheet-section-title">
                    Student Information
                </h5>

                <table class="sheet-info-table">

                    <tr>
                        <td>
                            <strong>Student Name:</strong>
                            {{ $studentName }}
                        </td>

                        <td>
                            <strong>Student ID:</strong>
                            {{ $result->student?->student_id ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Roll Number:</strong>
                            {{ $result->student?->roll_number ?? 'N/A' }}
                        </td>

                        <td>
                            <strong>Class:</strong>
                            {{ $result->class_name ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Section:</strong>
                            {{ $result->section ?? 'N/A' }}
                        </td>

                        <td>
                            <strong>Academic Year:</strong>
                            {{ $result->academic_year ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Examination:</strong>
                            {{ $examName }}
                        </td>

                        <td>
                            <strong>Exam Type:</strong>
                            {{ $examType }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Start Date:</strong>

                            @if($result->exam?->start_date)
                                {{ \Carbon\Carbon::parse($result->exam->start_date)->format('d-m-Y') }}
                            @else
                                N/A
                            @endif
                        </td>

                        <td>
                            <strong>End Date:</strong>

                            @if($result->exam?->end_date)
                                {{ \Carbon\Carbon::parse($result->exam->end_date)->format('d-m-Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Generated On:</strong>

                            {{ $generatedDate
                                ? \Carbon\Carbon::parse($generatedDate)->format('d-m-Y')
                                : 'N/A' }}
                        </td>

                        <td>
                            <strong>Publication Status:</strong>
                            {{ ucfirst($publicationStatus) }}
                        </td>
                    </tr>

                </table>

            </div>


            {{-- ACADEMIC PERFORMANCE --}}

            <div class="sheet-section">

                <h5 class="sheet-section-title">
                    Academic Performance
                </h5>

                <table class="sheet-marks-table">

                    <thead>

                        <tr>
                            <th rowspan="2" style="width:7%;">
                                Sr. No.
                            </th>

                            <th rowspan="2" style="width:25%;">
                                Subject
                            </th>

                            <th colspan="2" style="width:22%;">
                                Marks Obtained
                            </th>

                            <th rowspan="2" style="width:15%;">
                                Obtained
                            </th>

                            <th rowspan="2" style="width:15%;">
                                Maximum
                            </th>

                            <th rowspan="2" style="width:10%;">
                                Grade
                            </th>
                        </tr>

                        <tr>
                            <th>Internal</th>
                            <th>Theory</th>
                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $calculatedObtained = 0;
                            $calculatedMaximum = 0;
                        @endphp

                        @forelse($details as $index => $detail)

                            @php
                                $maximum = (float) (
                                    $detail->max_marks
                                    ?? $detail->maximum_marks
                                    ?? $detail->total_marks
                                    ?? 0
                                );

                                $obtained = (float) (
                                    $detail->obtained_marks
                                    ?? $detail->marks_obtained
                                    ?? $detail->marks
                                    ?? 0
                                );

                                $internal = (float) (
                                    $detail->internal_marks ?? 0
                                );

                                $theory = (float) (
                                    $detail->theory_marks ?? 0
                                );

                                $hasSplitMarks =
                                    $detail->internal_marks !== null
                                    || $detail->theory_marks !== null;

                                $calculatedObtained += $obtained;
                                $calculatedMaximum += $maximum;

                                $subjectPercentage = $maximum > 0
                                    ? ($obtained / $maximum) * 100
                                    : 0;

                                $displayGrade = $detail->grade ?? null;

                                if (!$displayGrade) {
                                    $displayGrade = match (true) {
                                        $subjectPercentage >= 90 => 'A+',
                                        $subjectPercentage >= 80 => 'A',
                                        $subjectPercentage >= 70 => 'B+',
                                        $subjectPercentage >= 60 => 'B',
                                        $subjectPercentage >= 50 => 'C',
                                        $subjectPercentage >= 40 => 'D',
                                        default => 'F',
                                    };
                                }

                                $subjectName =
                                    $detail->subject_name
                                    ?? $detail->subject?->subject_name
                                    ?? $detail->subject?->name
                                    ?? 'N/A';
                            @endphp

                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td class="subject-name">
                                    {{ $subjectName }}
                                </td>

                                <td>
                                    {{ $hasSplitMarks
                                        ? number_format($internal, 2)
                                        : '—' }}
                                </td>

                                <td>
                                    {{ $hasSplitMarks
                                        ? number_format($theory, 2)
                                        : '—' }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($obtained, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ number_format($maximum, 2) }}
                                </td>

                                <td class="grade-cell">
                                    {{ $displayGrade }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7">
                                    No subject-wise marks available.
                                </td>
                            </tr>

                        @endforelse


                        @if($subjectCount > 0)

                            <tr class="total-row">

                                <td colspan="4" style="text-align:right;">
                                    TOTAL
                                </td>

                                <td>
                                    {{ number_format(
                                        (float) ($result->obtained_marks ?? $calculatedObtained),
                                        2
                                    ) }}
                                </td>

                                <td>
                                    {{ number_format(
                                        (float) ($result->total_marks ?? $calculatedMaximum),
                                        2
                                    ) }}
                                </td>

                                <td>
                                    {{ $result->grade ?? 'N/A' }}
                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>


            {{-- RESULT SUMMARY --}}

            <div class="sheet-section">

                <h5 class="sheet-section-title">
                    Result Summary
                </h5>

                <table class="sheet-summary-table">

                    <tr>

                        <td>
                            <span class="summary-heading">
                                Total Maximum
                            </span>

                            <span class="summary-number">
                                {{ number_format((float) $result->total_marks, 2) }}
                            </span>
                        </td>

                        <td>
                            <span class="summary-heading">
                                Total Obtained
                            </span>

                            <span class="summary-number">
                                {{ number_format((float) $result->obtained_marks, 2) }}
                            </span>
                        </td>

                        <td>
                            <span class="summary-heading">
                                Percentage
                            </span>

                            <span class="summary-number">
                                {{ number_format($percentage, 2) }}%
                            </span>
                        </td>

                        <td>
                            <span class="summary-heading">
                                Grade
                            </span>

                            <span class="summary-number">
                                {{ $result->grade ?? 'N/A' }}
                            </span>
                        </td>

                    </tr>

                </table>

            </div>


            {{-- FINAL RESULT --}}

            <div class="sheet-final-result">

                <div class="sheet-final-label">
                    FINAL RESULT
                </div>

                <span class="sheet-final-status
                    {{ $isPassed ? 'sheet-pass' : ($isFailed ? 'sheet-fail' : ($isAbsent ? 'sheet-absent' : 'sheet-other')) }}">

                    {{ $finalStatus }}

                </span>

            </div>


            {{-- PUBLICATION INFORMATION --}}

            <div class="sheet-publication">

                {{ $publicationStatus === 'published'
                    ? 'ONLINE RESULT PUBLISHED'
                    : 'RESULT ' . strtoupper($publicationStatus) }}

                @if($publishedDate)
                    |
                    Published on:
                    {{ \Carbon\Carbon::parse($publishedDate)->format('d-m-Y') }}
                @endif

                @if($result->publisher)
                    |
                    Published by:
                    {{ $result->publisher->name }}
                @endif

            </div>


            {{-- SIGNATURES --}}

            <div class="sheet-signatures">

                <div class="sheet-signature-cell">
                    <div class="signature-line"></div>
                    Class Teacher
                </div>

                <div class="sheet-signature-cell">
                    <div class="signature-line"></div>
                    School Seal
                </div>

                <div class="sheet-signature-cell">
                    <div class="signature-line"></div>
                    Principal
                </div>

            </div>


            {{-- FOOTER --}}

            <div class="sheet-footer">

                <span>
                    Generated from {{ $schoolName }}
                </span>

                <span>
                    Generated on:
                    {{ now()->format('d-m-Y') }}
                </span>

                <span>
                    Published on:
                    {{ $publishedDate
                        ? \Carbon\Carbon::parse($publishedDate)->format('d-m-Y')
                        : 'Not Published' }}
                </span>

            </div>

        </div>

    </div>


    {{-- ADMIN BOTTOM ACTIONS --}}

    <div class="admin-bottom-actions">

        <a href="{{ route('admin.results.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Results
        </a>

        <a href="{{ route('admin.results.history', $result) }}"
           class="btn btn-outline-success">
            <i class="bi bi-clock-history me-1"></i>
            View Result History
        </a>

        <a href="{{ route('admin.results.print', $result) }}"
           target="_blank"
           class="btn btn-success">
            <i class="bi bi-printer me-1"></i>
            Print A4 Result
        </a>

    </div>

</div>

@endsection
