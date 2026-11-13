<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        Student Health Record -
        {{ $healthRecord->student->student_id ?? '' }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f2f4f7;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            font-size: 13px;
        }

        .print-page {
            width: 210mm;
            min-height: 297mm;
            margin: auto;
            padding: 15mm;
            background: #fff;
        }

        .school-header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .school-logo {
            max-height: 75px;
            max-width: 100px;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 22px;
            font-weight: 700;
            margin: 3px 0;
            text-transform: uppercase;
        }

        .school-address {
            font-size: 13px;
            line-height: 1.5;
        }

        .document-title {
            text-align: center;
            margin: 12px 0 18px;
        }

        .document-title h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .document-title p {
            margin: 5px 0 0;
            font-size: 13px;
        }

        .student-header {
            display: flex;
            gap: 15px;
            border: 1px solid #bbb;
            padding: 12px;
            margin-bottom: 15px;
        }

        .student-photo {
            width: 90px;
            height: 110px;
            border: 1px solid #aaa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-photo-placeholder {
            color: #777;
            font-size: 11px;
            text-align: center;
        }

        .student-info {
            flex: 1;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .student-info .label {
            width: 22%;
            font-weight: 700;
        }

        .student-info .value {
            width: 28%;
        }

        .section {
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #198754;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            padding: 7px 9px;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table.details th,
        table.details td {
            border: 1px solid #bbb;
            padding: 6px 7px;
            text-align: left;
            vertical-align: top;
        }

        table.details th {
            width: 25%;
            background: #f3f3f3;
            font-weight: 700;
        }

        .two-column {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .two-column .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .two-column .column:first-child {
            padding-right: 6px;
        }

        .two-column .column:last-child {
            padding-left: 6px;
        }

        .remarks {
            min-height: 45px;
        }

        .signature-area {
            display: table;
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 10px;
        }

        .signature-line {
            border-top: 1px solid #222;
            padding-top: 6px;
            margin-top: 40px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #aaa;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
        }

        .no-print {
            text-align: center;
            margin-bottom: 15px;
        }

        .print-button {
            border: 0;
            background: #198754;
            color: #fff;
            padding: 9px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }

        .back-button {
            display: inline-block;
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            padding: 9px 18px;
            border-radius: 5px;
            margin-right: 5px;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                padding: 0;
                background: #fff;
            }

            .print-page {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 12mm;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<div class="no-print">
    <a
        href="{{ route('admin.student-health.show', $healthRecord) }}"
        class="back-button"
    >
        ← Back
    </a>

    <button
        type="button"
        class="print-button"
        onclick="window.print()"
    >
        🖨 Print
    </button>
</div>

<div class="print-page">

    {{-- =========================================================
         SCHOOL HEADER - DYNAMIC SCHOOL PROFILE
    ========================================================== --}}
    <div class="school-header">

        {{-- SCHOOL LOGO --}}
        @if(!empty($schoolSetting?->logo))

            @php
                $schoolLogo = $schoolSetting->logo;

                if (
                    !str_starts_with($schoolLogo, 'http://') &&
                    !str_starts_with($schoolLogo, 'https://')
                ) {
                    $schoolLogo = asset($schoolLogo);
                }
            @endphp

            <img
                src="{{ $schoolLogo }}"
                class="school-logo"
                alt="{{ $schoolSetting->school_name ?? 'School Logo' }}"
            >

        @elseif(!empty($schoolSetting?->school_logo))

            @php
                $schoolLogo = $schoolSetting->school_logo;

                if (
                    !str_starts_with($schoolLogo, 'http://') &&
                    !str_starts_with($schoolLogo, 'https://')
                ) {
                    $schoolLogo = asset($schoolLogo);
                }
            @endphp

            <img
                src="{{ $schoolLogo }}"
                class="school-logo"
                alt="{{ $schoolSetting->school_name ?? 'School Logo' }}"
            >

        @endif


        {{-- SCHOOL NAME --}}
        @if(!empty($schoolSetting?->school_name))
            <div class="school-name">
                {{ $schoolSetting->school_name }}
            </div>
        @endif


        {{-- SCHOOL ADDRESS --}}
        <div class="school-address">

            @if(!empty($schoolSetting?->address))
                {{ $schoolSetting->address }}
            @endif

            @if(!empty($schoolSetting?->city))
                , {{ $schoolSetting->city }}
            @endif

            @if(!empty($schoolSetting?->district))
                , {{ $schoolSetting->district }}
            @endif

            @if(!empty($schoolSetting?->state))
                , {{ $schoolSetting->state }}
            @endif

            @if(!empty($schoolSetting?->pincode))
                - {{ $schoolSetting->pincode }}
            @endif

            @if(
                !empty($schoolSetting?->address) ||
                !empty($schoolSetting?->city) ||
                !empty($schoolSetting?->district) ||
                !empty($schoolSetting?->state) ||
                !empty($schoolSetting?->pincode)
            )
                <br>
            @endif


            {{-- PHONE --}}
            @if(!empty($schoolSetting?->phone))
                Phone: {{ $schoolSetting->phone }}
            @endif


            {{-- EMAIL --}}
            @if(!empty($schoolSetting?->email))
                @if(!empty($schoolSetting?->phone))
                    &nbsp; | &nbsp;
                @endif

                Email: {{ $schoolSetting->email }}
            @endif


            {{-- UDISE CODE --}}
            @if(!empty($schoolSetting?->udise_code))
                &nbsp; | &nbsp;
                UDISE Code: {{ $schoolSetting->udise_code }}
            @endif


            {{-- SCHOOL CODE --}}
            @if(!empty($schoolSetting?->school_code))
                &nbsp; | &nbsp;
                School Code: {{ $schoolSetting->school_code }}
            @endif

        </div>

    </div>


    {{-- TITLE --}}
    <div class="document-title">
        <h2>Student Annual Health Checkup Record</h2>

        <p>
            Academic Year:
            <strong>{{ $healthRecord->academic_year }}</strong>
        </p>
    </div>


    {{-- STUDENT INFORMATION --}}
    @php
        $student = $healthRecord->student;
    @endphp

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

                    <td class="label">
                        Student ID
                    </td>

                    <td class="value">
                        {{ $student->student_id ?? '-' }}
                    </td>

                    <td class="label">
                        Roll No.
                    </td>

                    <td class="value">
                        {{ $student->roll_number ?? '-' }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Student Name
                    </td>

                    <td class="value">

                        {{ trim(
                            ($student->first_name ?? '') . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->last_name ?? '')
                        ) ?: '-' }}

                    </td>

                    <td class="label">
                        Gender
                    </td>

                    <td class="value">
                        {{ $student->gender ?? '-' }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Class
                    </td>

                    <td class="value">
                        {{ $student->class ?? '-' }}
                    </td>

                    <td class="label">
                        Section
                    </td>

                    <td class="value">
                        {{ $student->section ?? '-' }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Date of Birth
                    </td>

                    <td class="value">
                        {{ $student->date_of_birth?->format('d-m-Y') ?? '-' }}
                    </td>

                    <td class="label">
                        Aadhaar No.
                    </td>

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

                <th>
                    Checkup Date
                </th>

                <td>
                    {{ $healthRecord->checkup_date?->format('d-m-Y') ?? '-' }}
                </td>

                <th>
                    Checkup Type
                </th>

                <td>
                    {{ $healthRecord->checkup_type ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Doctor / Medical Officer
                </th>

                <td>
                    {{ $healthRecord->doctor_name ?? '-' }}
                </td>

                <th>
                    Health Center
                </th>

                <td>
                    {{ $healthRecord->health_center ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Conducted By
                </th>

                <td colspan="3">
                    {{ $healthRecord->conducted_by ?? '-' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- PHYSICAL + VISION --}}
    <div class="two-column">

        <div class="column">

            <div class="section">

                <div class="section-title">
                    Physical Measurements
                </div>

                <table class="details">

                    <tr>

                        <th>
                            Height
                        </th>

                        <td>
                            {{ $healthRecord->height ?? '-' }} cm
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Weight
                        </th>

                        <td>
                            {{ $healthRecord->weight ?? '-' }} kg
                        </td>

                    </tr>


                    <tr>

                        <th>
                            BMI
                        </th>

                        <td>
                            {{ $healthRecord->bmi ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Pulse Rate
                        </th>

                        <td>
                            {{ $healthRecord->pulse_rate ?? '-' }} bpm
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Blood Pressure
                        </th>

                        <td>
                            {{ $healthRecord->blood_pressure ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Temperature
                        </th>

                        <td>
                            {{ $healthRecord->temperature ?? '-' }} °C
                        </td>

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

                        <th>
                            Right Eye
                        </th>

                        <td>
                            {{ $healthRecord->vision_right ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Left Eye
                        </th>

                        <td>
                            {{ $healthRecord->vision_left ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Near Vision Right
                        </th>

                        <td>
                            {{ $healthRecord->near_vision_right ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Near Vision Left
                        </th>

                        <td>
                            {{ $healthRecord->near_vision_left ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Spectacles
                        </th>

                        <td>
                            {{ $healthRecord->uses_spectacles ? 'Yes' : 'No' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Spectacle Power
                        </th>

                        <td>
                            {{ $healthRecord->spectacle_power ?? '-' }}
                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>


    {{-- DENTAL --}}
    <div class="section">

        <div class="section-title">
            Dental Examination
        </div>

        <table class="details">

            <tr>

                <th>
                    Dental Status
                </th>

                <td>
                    {{ $healthRecord->dental_status ?? '-' }}
                </td>

                <th>
                    Dental Caries
                </th>

                <td>
                    {{ $healthRecord->dental_caries ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Gum Problem
                </th>

                <td>
                    {{ $healthRecord->gum_problem ?? '-' }}
                </td>

                <th>
                    Oral Hygiene
                </th>

                <td>
                    {{ $healthRecord->oral_hygiene ?? '-' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- ENT --}}
    <div class="section">

        <div class="section-title">
            ENT Examination
        </div>

        <table class="details">

            <tr>

                <th>
                    Right Ear
                </th>

                <td>
                    {{ $healthRecord->right_ear ?? '-' }}
                </td>

                <th>
                    Left Ear
                </th>

                <td>
                    {{ $healthRecord->left_ear ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Hearing Problem
                </th>

                <td>
                    {{ $healthRecord->hearing_problem ?? '-' }}
                </td>

                <th>
                    Nose
                </th>

                <td>
                    {{ $healthRecord->nose_status ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Throat
                </th>

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

                <th>
                    General Health
                </th>

                <td>
                    {{ $healthRecord->general_health ?? '-' }}
                </td>

                <th>
                    Skin
                </th>

                <td>
                    {{ $healthRecord->skin_status ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Respiratory
                </th>

                <td>
                    {{ $healthRecord->respiratory_status ?? '-' }}
                </td>

                <th>
                    Heart
                </th>

                <td>
                    {{ $healthRecord->heart_status ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Abdomen
                </th>

                <td>
                    {{ $healthRecord->abdomen_status ?? '-' }}
                </td>

                <th>
                    Musculoskeletal
                </th>

                <td>
                    {{ $healthRecord->musculoskeletal_status ?? '-' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- NUTRITION --}}
    <div class="section">

        <div class="section-title">
            Nutrition & Anemia
        </div>

        <table class="details">

            <tr>

                <th>
                    Nutritional Status
                </th>

                <td>
                    {{ $healthRecord->nutritional_status ?? '-' }}
                </td>

                <th>
                    Anemia Screening
                </th>

                <td>
                    {{ $healthRecord->anemia_screening ?? '-' }}
                </td>

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

                <th>
                    Known Health Condition
                </th>

                <td>
                    {{ $healthRecord->known_health_condition ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Allergy
                </th>

                <td>
                    {{ $healthRecord->allergy ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Current Medication
                </th>

                <td>
                    {{ $healthRecord->current_medication ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Medical History
                </th>

                <td>
                    {{ $healthRecord->medical_history ?? '-' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- REFERRAL --}}
    <div class="section">

        <div class="section-title">
            Referral & Follow-up
        </div>

        <table class="details">

            <tr>

                <th>
                    Referral Required
                </th>

                <td>
                    {{ $healthRecord->referral_required ? 'Yes' : 'No' }}
                </td>

                <th>
                    Referred To
                </th>

                <td>
                    {{ $healthRecord->referral_to ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Referral Date
                </th>

                <td>
                    {{ $healthRecord->referral_date?->format('d-m-Y') ?? '-' }}
                </td>

                <th>
                    Follow-up Date
                </th>

                <td>
                    {{ $healthRecord->follow_up_date?->format('d-m-Y') ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Follow-up Status
                </th>

                <td>
                    {{ $healthRecord->follow_up_status ?? '-' }}
                </td>

                <th>
                    Follow-up Remarks
                </th>

                <td>
                    {{ $healthRecord->follow_up_remarks ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Treatment Advised
                </th>

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

                <th>
                    Overall Health Status
                </th>

                <td>
                    {{ $healthRecord->overall_health_status ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Doctor Remarks
                </th>

                <td class="remarks">
                    {{ $healthRecord->doctor_remarks ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Teacher Remarks
                </th>

                <td class="remarks">
                    {{ $healthRecord->teacher_remarks ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Parent Remarks
                </th>

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


    <div class="footer">

        <span>
            Student Health Record
        </span>

        <span>
            Printed on:
            {{ now()->format('d-m-Y h:i A') }}
        </span>

    </div>

</div>

</body>
</html>
