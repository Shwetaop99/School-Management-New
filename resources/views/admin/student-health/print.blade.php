<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Student Health Record -
        {{ $healthRecord->student->student_id ?? '' }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            width: 100%;
        }

        body {
            margin: 0;
            padding: 15px;
            background: #f3f4f6;
            color: #222;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 20px;
        }

        /* =====================================================
           MAIN PAGE
        ===================================================== */

        .print-page {
            position: relative;
            isolation: isolate;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 12mm;
            background: #fff;
            border: 4px double #222;
        }

        .print-page::after {
            content: "";
            position: fixed;
            inset: 1mm;
            pointer-events: none;
            z-index: 99999;
        }

        /* =====================================================
           WATERMARK
        ===================================================== */

        .print-watermark {
            position: absolute;
            z-index: 0;
            top: 50%;
            left: 50%;
            width: 420px;
            height: 420px;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            overflow: hidden;
        }

        .print-watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            opacity: 0.07;
        }

        .print-page > :not(.print-watermark) {
            position: relative;
            z-index: 1;
        }

        /* =====================================================
           SCHOOL HEADER
        ===================================================== */

        .school-header {
            display: grid;
            grid-template-columns: 70px minmax(0, 1fr) 70px;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 5px 4px 8px;
            margin-bottom: 8px;
            border-bottom: 2px solid #111;
            text-align: center;
        }

        .school-header-logo {
            grid-column: 1;
            grid-row: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-header-logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .school-header-details {
            grid-column: 2;
            grid-row: 1;
            min-width: 0;
        }

        .school-name {
            margin: 0 0 3px;
            color: #111;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .school-address {
            color: #222;
            font-size: 8px;
            line-height: 1.35;
            font-weight: 500;
            overflow-wrap: anywhere;
        }

        .school-contact,
        .school-codes {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 2px 8px;
            font-size: 8px;
        }

        .school-contact span,
        .school-codes span {
            display: inline-block;
        }

        /* =====================================================
           DOCUMENT TITLE
        ===================================================== */

        .document-title {
            text-align: center;
            margin: 8px 0 10px;
        }

        .document-title h2 {
            margin: 0;
            font-size: 15px;
            text-transform: uppercase;
            color: #111;
        }

        .document-title p {
            margin: 3px 0 0;
            font-size: 9px;
            color: #333;
        }

        /* =====================================================
           STUDENT INFORMATION
        ===================================================== */

        .student-header {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            border: 1px solid #555;
            padding: 8px;
            margin-bottom: 9px;
            background: rgba(255,255,255,0.35);
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .student-photo {
            width: 70px;
            height: 85px;
            border: 1px solid #555;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            background: transparent;
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-photo-placeholder {
            color: #555;
            font-size: 8px;
            text-align: center;
        }

        .student-info {
            flex: 1;
            min-width: 0;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .student-info td {
            padding: 3px 4px;
            vertical-align: top;
            overflow-wrap: anywhere;
            font-size: 9px;
            line-height: 1.25;
        }

        .student-info .label {
            width: 20%;
            font-weight: 700;
            color: #111;
        }

        .student-info .value {
            width: 30%;
            color: #333;
        }

        /* =====================================================
           SECTIONS
        ===================================================== */

        .section {
            width: 100%;
            margin-top: 9px;
        }

        .section-title {
            background: #222;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 5px 7px;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* =====================================================
           DETAIL TABLES
        ===================================================== */

        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            table-layout: fixed;
            background: transparent;
        }

        table.details th,
        table.details td {
            border: 1px solid #777;
            padding: 4px 5px;
            text-align: left;
            vertical-align: top;
            overflow-wrap: anywhere;
            background: transparent;
            font-size: 12px;
            line-height: 1.25;
        }

        table.details th {
            width: 25%;
            background: #ededed;
            color: #111;
            font-weight: 700;
        }

        table.details td {
            color: #222;
        }

        /* =====================================================
           TWO COLUMN LAYOUT
        ===================================================== */

        .two-column {
            display: table;
            width: 100%;
            table-layout: fixed;
            border-spacing: 0;
        }

        .two-column .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .two-column .column:first-child {
            padding-right: 5px;
        }

        .two-column .column:last-child {
            padding-left: 5px;
        }

        .remarks {
            min-height: 35px;
        }

        /* =====================================================
           SIGNATURES
        ===================================================== */

        .signature-area {
            display: table;
            width: 100%;
            margin-top: 35px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 8px;
            color: #222;
            font-size: 9px;
        }

        .signature-line {
            border-top: 1px solid #222;
            padding-top: 5px;
            margin-top: 28px;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            margin-top: 15px;
            padding-top: 6px;
            border-top: 1px solid #777;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            font-size: 8px;
            color: #444;
        }

        /* =====================================================
           PRINT BUTTON
        ===================================================== */

        .no-print {
            text-align: center;
            margin-bottom: 12px;
        }

        .print-button {
            border: 1px solid #222;
            background: #222;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
        }

        .print-button:hover {
            background: #444;
        }

        /* =====================================================
           A4 PRINT SETTINGS
        ===================================================== */

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        @media print {

            html,
            body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                color: #000 !important;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 7px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-page {
                position: relative !important;
                isolation: isolate !important;
                width: 100% !important;
                min-height: 0 !important;
                margin: 0 !important;
                padding: 4mm 5mm !important;
                border: none !important;
                background: #fff !important;
                box-shadow: none !important;
                overflow: visible !important;
            }

            .print-page::after {
                content: "";
                position: fixed !important;
                inset: 1mm !important;
                border: 3px double #222 !important;
                pointer-events: none !important;
                z-index: 99999 !important;
            }

            /* WATERMARK */

            .print-watermark {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                width: 115mm !important;
                height: 115mm !important;
                transform: translate(-50%, -50%) !important;
                display: flex !important;
                z-index: 0 !important;
                pointer-events: none !important;
            }

            .print-watermark img {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
                opacity: 0.07 !important;
            }

            .print-page > :not(.print-watermark) {
                position: relative !important;
                z-index: 1 !important;
            }

            /* SCHOOL HEADER */

            .school-header {
                grid-template-columns: 55px minmax(0, 1fr) 55px !important;
                gap: 6px !important;
                padding: 3px 2px 5px !important;
                margin-bottom: 6px !important;
                border-bottom: 2px solid #000 !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .school-header-logo img {
                width: 45px !important;
                height: 45px !important;
            }

            .school-name {
                font-size: 12px !important;
                margin-bottom: 2px !important;
            }

            .school-address {
                font-size: 6px !important;
                line-height: 1.25 !important;
            }

            .school-contact,
            .school-codes {
                font-size: 6px !important;
                gap: 1px 6px !important;
            }

            /* DOCUMENT TITLE */

            .document-title {
                margin: 5px 0 6px !important;
                text-align: center !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .document-title h2 {
                font-size: 10px !important;
            }

            .document-title p {
                font-size: 7px !important;
                margin-top: 2px !important;
            }

            /* STUDENT INFORMATION */

            .student-header {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 6px !important;
                padding: 5px !important;
                margin-bottom: 5px !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .student-photo {
                width: 48px !important;
                height: 58px !important;
                flex: 0 0 48px !important;
            }

            .student-photo-placeholder {
                font-size: 6px !important;
            }

            .student-info {
                flex: 1 !important;
                min-width: 0 !important;
            }

            .student-info table {
                width: 100% !important;
                table-layout: fixed !important;
            }

            .student-info td {
                padding: 2px 3px !important;
                font-size: 6.5px !important;
                line-height: 1.2 !important;
            }

            .student-info .label {
                width: 20% !important;
            }

            .student-info .value {
                width: 30% !important;
            }

            /* SECTIONS */

            .section {
                width: 100% !important;
                margin-top: 5px !important;
            }

            .section-title {
                padding: 3px 5px !important;
                font-size: 7px !important;
                background: #222 !important;
                color: #fff !important;
            }

            /* TABLES */

            table.details {
                width: 100% !important;
                table-layout: fixed !important;
                margin-bottom: 3px !important;
                border-collapse: collapse !important;
                break-inside: auto !important;
                page-break-inside: auto !important;
            }

            table.details tr {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            table.details th,
            table.details td {
                padding: 2.5px 3.5px !important;
                font-size: 6.5px !important;
                line-height: 1.2 !important;
                border: 1px solid #777 !important;
                overflow-wrap: anywhere !important;
            }

            table.details th {
                background: #ededed !important;
                color: #111 !important;
            }

            /* TWO COLUMNS */

            .two-column {
                display: table !important;
                width: 100% !important;
                table-layout: fixed !important;
                border-spacing: 0 !important;
            }

            .two-column .column {
                display: table-cell !important;
                width: 50% !important;
                vertical-align: top !important;
            }

            .two-column .column:first-child {
                padding-right: 3px !important;
            }

            .two-column .column:last-child {
                padding-left: 3px !important;
            }

            /* SIGNATURES */

            .signature-area {
                display: table !important;
                width: 100% !important;
                margin-top: 15px !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .signature {
                display: table-cell !important;
                width: 33.33% !important;
                padding: 0 6px !important;
                text-align: center !important;
                vertical-align: bottom !important;
                font-size: 6.5px !important;
            }

            .signature-line {
                margin-top: 17px !important;
                padding-top: 3px !important;
                border-top: 1px solid #222 !important;
            }

            /* FOOTER */

            .footer {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                width: 100% !important;
                margin-top: 7px !important;
                padding-top: 4px !important;
                border-top: 1px solid #777 !important;
                font-size: 6px !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
        }

        /* =====================================================
           SCREEN RESPONSIVENESS
        ===================================================== */

        @media screen and (max-width: 800px) {

            body {
                padding: 8px;
            }

            .print-page {
                width: 100%;
                min-height: auto;
                padding: 12px;
            }

            .school-header {
                grid-template-columns: 55px minmax(0, 1fr);
                gap: 6px;
            }

            .school-header-logo img {
                width: 48px;
                height: 48px;
            }

            .school-name {
                font-size: 14px;
            }

            .school-address {
                font-size: 7px;
            }

            .school-contact,
            .school-codes {
                font-size: 7px;
            }

            .student-header {
                flex-direction: column;
                align-items: center;
            }

            .student-info {
                width: 100%;
            }

            .student-info table {
                table-layout: fixed;
            }

            .two-column {
                display: block;
            }

            .two-column .column {
                display: block;
                width: 100%;
                padding: 0 !important;
            }

            .print-watermark {
                width: 250px;
                height: 250px;
            }
        }

        @media screen and (max-width: 500px) {

            .school-header {
                grid-template-columns: 45px minmax(0, 1fr);
                gap: 5px;
            }

            .school-header-logo img {
                width: 40px;
                height: 40px;
            }

            .school-name {
                font-size: 12px;
            }

            .school-address {
                font-size: 7px;
            }

            .student-info td {
                padding: 3px 2px;
                font-size: 8px;
            }

            .student-info .label {
                width: 25%;
            }

            .student-info .value {
                width: 25%;
            }

            .document-title h2 {
                font-size: 12px;
            }

            table.details th,
            table.details td {
                padding: 3px 2px;
                font-size: 8px;
            }

            .section-title {
                font-size: 8px;
            }

            .signature-area {
                margin-top: 25px;
            }

            .signature {
                padding: 0 3px;
                font-size: 8px;
            }

            .footer {
                font-size: 7px;
            }
        }
    </style>
</head>

<body>

@php
    $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

    $schoolLogo = null;

    if (!empty($school?->logo)) {
        $schoolLogo = preg_match('~^https?://~i', $school->logo)
            ? $school->logo
            : asset('storage/' . ltrim($school->logo, '/'));
    } elseif (!empty($school?->school_logo)) {
        $schoolLogo = preg_match('~^https?://~i', $school->school_logo)
            ? $school->school_logo
            : asset('storage/' . ltrim($school->school_logo, '/'));
    }

    $watermarkLogo = $schoolLogo ?: asset('images/gurukullogo.png');

    $student = $healthRecord->student;
@endphp

{{-- PRINT BUTTON --}}

<div class="no-print">
    <button
        type="button"
        class="print-button"
        onclick="window.print()"
    >
        Print Health Record
    </button>
</div>

<div class="print-page">

    {{-- WATERMARK --}}

    <div class="print-watermark" aria-hidden="true">
        <img src="{{ $watermarkLogo }}" alt="">
    </div>

    {{-- SCHOOL HEADER --}}

    <div class="school-header">

        <div class="school-header-logo">
            @if(!empty($schoolLogo))
                <img src="{{ $schoolLogo }}" alt="School Logo">
            @endif
        </div>

        <div class="school-header-details">

            @if(!empty($school?->school_name))
                <h1 class="school-name">
                    {{ $school->school_name }}
                </h1>
            @endif

            <div class="school-address">

                @php
                    $addressParts = array_filter([
                        $school?->address,
                        $school?->city,
                        $school?->district,
                        $school?->state,
                    ]);

                    $fullAddress = implode(', ', $addressParts);

                    if (!empty($school?->pincode)) {
                        $fullAddress .= ($fullAddress ? ' - ' : '') . $school->pincode;
                    }
                @endphp

                @if($fullAddress)
                    <div>{{ $fullAddress }}</div>
                @endif

                <div class="school-contact">

                    @if(!empty($school?->phone))
                        <span>Phone: {{ $school->phone }}</span>
                    @endif

                    @if(!empty($school?->email))
                        <span>Email: {{ $school->email }}</span>
                    @endif

                </div>

                <div class="school-codes">

                    @if(!empty($school?->udise_code))
                        <span>UDISE Code: {{ $school->udise_code }}</span>
                    @endif

                    @if(!empty($school?->school_code))
                        <span>School Code: {{ $school->school_code }}</span>
                    @endif

                </div>

            </div>
        </div>

    </div>

    {{-- DOCUMENT TITLE --}}

    <div class="document-title">

        <h2>Student Annual Health Checkup Record</h2>

        <p>
            Academic Year:
            <strong>{{ $healthRecord->academic_year ?? '-' }}</strong>
        </p>

    </div>

    {{-- STUDENT INFORMATION --}}

    <div class="student-header">

        <div class="student-photo">

            @if(!empty($student?->profile_image))

                <img
                    src="{{ $student->profile_image }}"
                    alt="Student Photo"
                >

            @else

                <div class="student-photo-placeholder">
                    Student Photo
                </div>

            @endif

        </div>

        <div class="student-info">

            <table>

                <tr>
                    <td class="label">Student ID</td>
                    <td class="value">
                        {{ $student->student_id ?? '-' }}
                    </td>

                    <td class="label">Roll No.</td>
                    <td class="value">
                        {{ $student->roll_number ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Student Name</td>
                    <td class="value">
                        {{ trim(
                            ($student->first_name ?? '') . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->last_name ?? '')
                        ) ?: '-' }}
                    </td>

                    <td class="label">Gender</td>
                    <td class="value">
                        {{ $student->gender ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Class</td>
                    <td class="value">
                        {{ $student->class ?? '-' }}
                    </td>

                    <td class="label">Section</td>
                    <td class="value">
                        {{ $student->section ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Date of Birth</td>
                    <td class="value">
                        {{ $student->date_of_birth?->format('d-m-Y') ?? '-' }}
                    </td>

                    <td class="label">Aadhaar No.</td>
                    <td class="value">
                        {{ $student->aadhar_card_no ?? '-' }}
                    </td>
                </tr>

            </table>

        </div>

    </div>

    {{-- CHECKUP INFORMATION --}}

    <div class="section">

        <div class="section-title">
            Checkup Information
        </div>

        <table class="details">

            <tr>
                <th>Checkup Date</th>
                <td>
                    {{ $healthRecord->checkup_date?->format('d-m-Y') ?? '-' }}
                </td>

                <th>Checkup Type</th>
                <td>
                    {{ $healthRecord->checkup_type ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Doctor / Medical Officer</th>
                <td>
                    {{ $healthRecord->doctor_name ?? '-' }}
                </td>

                <th>Health Center</th>
                <td>
                    {{ $healthRecord->health_center ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Conducted By</th>
                <td colspan="3">
                    {{ $healthRecord->conducted_by ?? '-' }}
                </td>
            </tr>

        </table>

    </div>

    {{-- PHYSICAL MEASUREMENTS AND VISION --}}

    <div class="two-column">

        <div class="column">

            <div class="section">

                <div class="section-title">
                    Physical Measurements
                </div>

                <table class="details">

                    <tr>
                        <th>Height</th>
                        <td>{{ $healthRecord->height ?? '-' }} cm</td>
                    </tr>

                    <tr>
                        <th>Weight</th>
                        <td>{{ $healthRecord->weight ?? '-' }} kg</td>
                    </tr>

                    <tr>
                        <th>BMI</th>
                        <td>{{ $healthRecord->bmi ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Pulse Rate</th>
                        <td>{{ $healthRecord->pulse_rate ?? '-' }} bpm</td>
                    </tr>

                    <tr>
                        <th>Blood Pressure</th>
                        <td>{{ $healthRecord->blood_pressure ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Temperature</th>
                        <td>{{ $healthRecord->temperature ?? '-' }} °C</td>
                    </tr>

                </table>

            </div>

        </div>

        <div class="column">

            <div class="section">

                <div class="section-title">
                    Vision
                </div>

                <table class="details">

                    <tr>
                        <th>Right Eye</th>
                        <td>{{ $healthRecord->vision_right ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Left Eye</th>
                        <td>{{ $healthRecord->vision_left ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Near Vision Right</th>
                        <td>{{ $healthRecord->near_vision_right ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Near Vision Left</th>
                        <td>{{ $healthRecord->near_vision_left ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Spectacles</th>
                        <td>
                            {{ $healthRecord->uses_spectacles ? 'Yes' : 'No' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Spectacle Power</th>
                        <td>{{ $healthRecord->spectacle_power ?? '-' }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

    {{-- DENTAL EXAMINATION --}}

    <div class="section">

        <div class="section-title">
            Dental Examination
        </div>

        <table class="details">

            <tr>
                <th>Dental Status</th>
                <td>{{ $healthRecord->dental_status ?? '-' }}</td>

                <th>Dental Caries</th>
                <td>{{ $healthRecord->dental_caries ?? '-' }}</td>
            </tr>

            <tr>
                <th>Gum Problem</th>
                <td>{{ $healthRecord->gum_problem ?? '-' }}</td>

                <th>Oral Hygiene</th>
                <td>{{ $healthRecord->oral_hygiene ?? '-' }}</td>
            </tr>

        </table>

    </div>

    {{-- ENT EXAMINATION --}}

    <div class="section">

        <div class="section-title">
            ENT Examination
        </div>

        <table class="details">

            <tr>
                <th>Right Ear</th>
                <td>{{ $healthRecord->right_ear ?? '-' }}</td>

                <th>Left Ear</th>
                <td>{{ $healthRecord->left_ear ?? '-' }}</td>
            </tr>

            <tr>
                <th>Hearing Problem</th>
                <td>{{ $healthRecord->hearing_problem ?? '-' }}</td>

                <th>Nose</th>
                <td>{{ $healthRecord->nose_status ?? '-' }}</td>
            </tr>

            <tr>
                <th>Throat</th>
                <td colspan="3">
                    {{ $healthRecord->throat_status ?? '-' }}
                </td>
            </tr>

        </table>

    </div>

    {{-- GENERAL HEALTH --}}

    <div class="section">

        <div class="section-title">
            General Health Examination
        </div>

        <table class="details">

            <tr>
                <th>General Health</th>
                <td>{{ $healthRecord->general_health ?? '-' }}</td>

                <th>Skin</th>
                <td>{{ $healthRecord->skin_status ?? '-' }}</td>
            </tr>

            <tr>
                <th>Respiratory</th>
                <td>{{ $healthRecord->respiratory_status ?? '-' }}</td>

                <th>Heart</th>
                <td>{{ $healthRecord->heart_status ?? '-' }}</td>
            </tr>

            <tr>
                <th>Abdomen</th>
                <td>{{ $healthRecord->abdomen_status ?? '-' }}</td>

                <th>Musculoskeletal</th>
                <td>{{ $healthRecord->musculoskeletal_status ?? '-' }}</td>
            </tr>

        </table>

    </div>

    {{-- NUTRITION AND ANEMIA --}}

    <div class="section">

        <div class="section-title">
            Nutrition & Anemia
        </div>

        <table class="details">

            <tr>
                <th>Nutritional Status</th>
                <td>{{ $healthRecord->nutritional_status ?? '-' }}</td>

                <th>Anemia Screening</th>
                <td>{{ $healthRecord->anemia_screening ?? '-' }}</td>
            </tr>

        </table>

    </div>

    {{-- MEDICAL HISTORY --}}

    <div class="section">

        <div class="section-title">
            Medical History
        </div>

        <table class="details">

            <tr>
                <th>Known Health Condition</th>
                <td>{{ $healthRecord->known_health_condition ?? '-' }}</td>
            </tr>

            <tr>
                <th>Allergy</th>
                <td>{{ $healthRecord->allergy ?? '-' }}</td>
            </tr>

            <tr>
                <th>Current Medication</th>
                <td>{{ $healthRecord->current_medication ?? '-' }}</td>
            </tr>

            <tr>
                <th>Medical History</th>
                <td>{{ $healthRecord->medical_history ?? '-' }}</td>
            </tr>

        </table>

    </div>

    {{-- REFERRAL AND FOLLOW-UP --}}

    <div class="section">

        <div class="section-title">
            Referral & Follow-up
        </div>

        <table class="details">

            <tr>
                <th>Referral Required</th>
                <td>
                    {{ $healthRecord->referral_required ? 'Yes' : 'No' }}
                </td>

                <th>Referred To</th>
                <td>{{ $healthRecord->referral_to ?? '-' }}</td>
            </tr>

            <tr>
                <th>Referral Date</th>
                <td>
                    {{ $healthRecord->referral_date?->format('d-m-Y') ?? '-' }}
                </td>

                <th>Follow-up Date</th>
                <td>
                    {{ $healthRecord->follow_up_date?->format('d-m-Y') ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Follow-up Status</th>
                <td>{{ $healthRecord->follow_up_status ?? '-' }}</td>

                <th>Follow-up Remarks</th>
                <td>{{ $healthRecord->follow_up_remarks ?? '-' }}</td>
            </tr>

            <tr>
                <th>Treatment Advised</th>
                <td colspan="3">
                    {{ $healthRecord->treatment_advised ?? '-' }}
                </td>
            </tr>

        </table>

    </div>

    {{-- FINAL ASSESSMENT --}}

    <div class="section">

        <div class="section-title">
            Final Assessment
        </div>

        <table class="details">

            <tr>
                <th>Overall Health Status</th>
                <td>
                    {{ $healthRecord->overall_health_status ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Doctor Remarks</th>
                <td class="remarks">
                    {{ $healthRecord->doctor_remarks ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Teacher Remarks</th>
                <td class="remarks">
                    {{ $healthRecord->teacher_remarks ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Parent Remarks</th>
                <td class="remarks">
                    {{ $healthRecord->parent_remarks ?? '-' }}
                </td>
            </tr>

        </table>

    </div>

    {{-- SIGNATURES --}}

    <div class="signature-area">

        <div class="signature">
            <div class="signature-line">
                Parent / Guardian
            </div>
        </div>

        <div class="signature">
            <div class="signature-line">
                Teacher
            </div>
        </div>

        <div class="signature">
            <div class="signature-line">
                Doctor / Medical Officer
            </div>
        </div>

    </div>

    {{-- FOOTER --}}

    <div class="footer">

        <span>
            Student Health Record
        </span>

        <span>
            Printed on: {{ now()->format('d-m-Y h:i A') }}
        </span>

    </div>

</div>

</body>
</html>
