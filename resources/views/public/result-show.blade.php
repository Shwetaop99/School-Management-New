
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    @php

        /*
        |--------------------------------------------------------------------------
        | SCHOOL
        |--------------------------------------------------------------------------
        */

        $school = \App\Models\SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | STUDENT / EXAM
        |--------------------------------------------------------------------------
        */

        $student = $result->student;
        $exam = $result->exam;


        /*
        |--------------------------------------------------------------------------
        | STUDENT NAME
        |--------------------------------------------------------------------------
        */

        $studentName = trim(
            collect([
                $student?->first_name,
                $student?->middle_name,
                $student?->last_name,
            ])
            ->filter(function ($value) {
                return filled($value);
            })
            ->implode(' ')
        );

        $studentName = $studentName ?: 'Student';


        /*
        |--------------------------------------------------------------------------
        | RESULT DETAILS
        |--------------------------------------------------------------------------
        */

        $details = $result->details ?? collect();


        /*
        |--------------------------------------------------------------------------
        | SCHOOL LOGO URL
        |--------------------------------------------------------------------------
        |
        | Supports:
        | 1. Full URL
        | 2. Storage path
        | 3. Default school logo
        |
        */

        $logoUrl = null;

        if ($school?->logo) {

            if (
                str_starts_with($school->logo, 'http://') ||
                str_starts_with($school->logo, 'https://')
            ) {

                $logoUrl = $school->logo;

            } else {

                $logoUrl = asset(
                    'storage/' . ltrim($school->logo, '/')
                );

            }

        } else {

            $defaultLogo = public_path('images/gurukullogo.png');

            if (file_exists($defaultLogo)) {
                $logoUrl = asset('images/gurukullogo.png');
            }

        }

    @endphp


    <title>
        Result - {{ $studentName }}
    </title>


    {{-- Bootstrap --}}

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Bootstrap Icons --}}

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <style>

        /* =========================================================
           GENERAL
        ========================================================== */

        body {

            background: #f4f7fb;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #222;

            margin: 0;

            padding: 0;

        }


        .result-wrapper {

            max-width: 1100px;

            margin: 30px auto;

            padding: 0 15px;

        }


        .result-card {

            background: #fff;

            border: 1px solid #dee2e6;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 8px 30px rgba(0, 0, 0, 0.08);

            position: relative;

        }


        /* =========================================================
           BACKGROUND SCHOOL LOGO / WATERMARK
        ========================================================== */

        .background-logo {

            position: absolute;

            top: 50%;

            left: 50%;

            width: 430px;

            height: 430px;

            transform: translate(-50%, -50%);

            display: flex;

            align-items: center;

            justify-content: center;

            pointer-events: none;

            z-index: 0;

        }


        .background-logo img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            opacity: 0.045;

        }


        /*
        |------------------------------------------------------------------
        | Keep all actual result content above the watermark
        |------------------------------------------------------------------
        */

        .document-content {

            position: relative;

            z-index: 1;

        }


        /* =========================================================
           SCHOOL HEADER
        ========================================================== */

        .school-header {

            text-align: center;

            border-bottom: 2px solid #222;

            padding: 25px 30px 15px;

        }


        .school-logo {

            width: 65px;

            height: 65px;

            object-fit: contain;

            margin-bottom: 5px;

        }


        .school-name {

            font-size: 26px;

            font-weight: 700;

            margin: 4px 0;

        }


        .school-address {

            font-size: 13px;

            color: #555;

            margin: 3px 0;

        }


        .school-contact {

            font-size: 12px;

            color: #555;

            margin-top: 5px;

        }


        /* =========================================================
           RESULT TITLE
        ========================================================== */

        .document-title {

            text-align: center;

            margin: 18px 0 15px;

        }


        .document-title h2 {

            margin: 0;

            font-size: 22px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .5px;

        }


        .exam-name {

            margin-top: 5px;

            font-size: 15px;

            font-weight: 700;

        }


        /* =========================================================
           STUDENT INFORMATION
        ========================================================== */

        .section-box {

            padding: 0 30px 20px;

        }


        .section-title {

            font-size: 16px;

            font-weight: 700;

            padding-bottom: 8px;

            margin-bottom: 15px;

            border-bottom: 2px solid #e9ecef;

        }


        .student-info {

            width: 100%;

            border-collapse: collapse;

            margin-bottom: 10px;

        }


        .student-info td {

            border: 1px solid #777;

            padding: 8px;

            vertical-align: middle;

        }


        .student-info .label {

            width: 18%;

            font-weight: 700;

            background: #f2f2f2;

        }


        .student-info .value {

            width: 32%;

            font-weight: 500;

        }


        /* =========================================================
           MARKS TABLE
        ========================================================== */

        .marks-table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 5px;

        }


        .marks-table th,
        .marks-table td {

            border: 1px solid #555;

            padding: 8px;

            vertical-align: middle;

        }


        .marks-table th {

            background: #eeeeee;

            font-weight: 700;

            font-size: 13px;

            text-align: center;

        }


        .marks-table td {

            font-size: 13px;

        }


        .marks-table th:first-child,
        .marks-table td:first-child {

            text-align: left;

        }


        .subject-name {

            font-weight: 600;

        }


        /* =========================================================
           SUMMARY
        ========================================================== */

        .summary {

            width: 100%;

            border-collapse: collapse;

            margin-top: 12px;

        }


        .summary td {

            border: 1px solid #777;

            padding: 9px;

        }


        .summary-label {

            width: 25%;

            background: #f2f2f2;

            font-weight: 700;

        }


        .summary-value {

            width: 25%;

            font-weight: 600;

        }


        /* =========================================================
           STATUS
        ========================================================== */

        .status-badge {

            display: inline-block;

            padding: 6px 18px;

            border-radius: 50px;

            font-size: 13px;

            font-weight: 700;

        }


        .status-pass {

            background: #d1e7dd;

            color: #0f5132;

        }


        .status-fail {

            background: #f8d7da;

            color: #842029;

        }


        .status-absent {

            background: #fff3cd;

            color: #664d03;

        }


        /* =========================================================
           REMARKS
        ========================================================== */

        .remarks {

            border: 1px solid #777;

            padding: 10px;

            margin-top: 10px;

            margin-bottom: 20px;

        }


        .remarks-title {

            font-weight: 700;

            margin-bottom: 5px;

        }


        /* =========================================================
           FOOTER
        ========================================================== */

        .result-footer {

            border-top: 1px solid #777;

            padding: 20px 30px;

            font-size: 12px;

            color: #666;

        }


        /* =========================================================
           ACTION BUTTONS
        ========================================================== */

        .action-bar {

            margin-top: 20px;

            margin-bottom: 30px;

            display: flex;

            justify-content: center;

            gap: 10px;

            flex-wrap: wrap;

        }


        .action-bar .btn {

            min-width: 150px;

            border-radius: 7px;

        }


        /* =========================================================
           PUBLISHED INFORMATION
        ========================================================== */

        .published-info {

            text-align: center;

            font-size: 10px;

            color: #777;

            margin-top: 12px;

        }


        /* =========================================================
           MOBILE
        ========================================================== */

        @media (max-width: 768px) {

            .result-wrapper {

                margin: 15px auto;

                padding: 0 8px;

            }


            .school-header {

                padding: 20px 15px 12px;

            }


            .school-name {

                font-size: 21px;

            }


            .school-address {

                font-size: 11px;

            }


            .school-contact {

                font-size: 10px;

            }


            .document-title h2 {

                font-size: 18px;

            }


            .exam-name {

                font-size: 13px;

            }


            .section-box {

                padding-left: 15px;

                padding-right: 15px;

            }


            /*
             * Keep the same table structure.
             * On small screens the table can scroll horizontally.
             */

            .student-info {

                min-width: 650px;

            }


            .marks-table {

                min-width: 700px;

            }


            .result-footer {

                padding: 15px;

            }


            .action-bar {

                margin-left: 5px;

                margin-right: 5px;

            }


            .action-bar .btn {

                width: 100%;

                max-width: 320px;

            }


            /*
             * Slightly smaller watermark on mobile
             */

            .background-logo {

                width: 300px;

                height: 300px;

            }

        }


        /* =========================================================
           PRINT
        ========================================================== */

        @media print {

            body {

                background: #fff;

            }


            .result-wrapper {

                max-width: 100%;

                margin: 0;

                padding: 0;

            }


            .result-card {

                border: none;

                border-radius: 0;

                box-shadow: none;

                overflow: visible;

            }


            .action-bar {

                display: none !important;

            }


            .no-print {

                display: none !important;

            }


            .school-header {

                padding-left: 10px;

                padding-right: 10px;

            }


            .section-box {

                padding-left: 10px;

                padding-right: 10px;

            }


            .result-footer {

                padding-left: 10px;

                padding-right: 10px;

            }


            .student-info,
            .marks-table {

                min-width: 0 !important;

            }


            .marks-table {

                page-break-inside: avoid;

            }


            tr {

                page-break-inside: avoid;

            }


            /*
             * Keep watermark centered when printing.
             */

            .background-logo {

                position: fixed;

                top: 50%;

                left: 50%;

                width: 430px;

                height: 430px;

                transform: translate(-50%, -50%);

                z-index: 0;

            }


            .background-logo img {

                opacity: 0.045;

            }


            .document-content {

                position: relative;

                z-index: 1;

            }


            @page {

                size: A4;

                margin: 12mm;

            }

        }

    </style>

</head>


<body>


<div class="result-wrapper">


    {{-- =========================================================
         RESULT CARD
    ========================================================== --}}

    <div class="result-card">


        {{-- =====================================================
             BACKGROUND SCHOOL LOGO
        ====================================================== --}}

        @if($logoUrl)

            <div class="background-logo">

                <img
                    src="{{ $logoUrl }}"
                    alt=""
                >

            </div>

        @endif


        {{-- =====================================================
             ALL RESULT CONTENT
        ====================================================== --}}

        <div class="document-content">


            {{-- =================================================
                 SCHOOL HEADER
            ================================================== --}}

            <div class="school-header">


                {{-- School Logo --}}

                @if($logoUrl)

                    <img
                        src="{{ $logoUrl }}"
                        alt="School Logo"
                        class="school-logo"
                    >

                @endif


                {{-- School Name --}}

                <div class="school-name">

                    {{ $school?->school_name ?? 'School Management System' }}

                </div>


                {{-- School Address --}}

                @if(
                    $school?->address ||
                    $school?->city ||
                    $school?->district ||
                    $school?->state ||
                    $school?->pincode
                )

                    <div class="school-address">


                        @if($school?->address)

                            {{ $school->address }}

                        @endif


                        @if($school?->city)

                            , {{ $school->city }}

                        @endif


                        @if($school?->district)

                            , {{ $school->district }}

                        @endif


                        @if($school?->state)

                            , {{ $school->state }}

                        @endif


                        @if($school?->pincode)

                            - {{ $school->pincode }}

                        @endif


                    </div>

                @endif


                {{-- Contact Information --}}

                @if(
                    $school?->phone ||
                    $school?->email ||
                    $school?->udise_code
                )

                    <div class="school-contact">


                        @if($school?->phone)

                            Phone:
                            {{ $school->phone }}

                        @endif


                        @if(
                            $school?->phone &&
                            $school?->email
                        )

                            &nbsp; | &nbsp;

                        @endif


                        @if($school?->email)

                            Email:
                            {{ $school->email }}

                        @endif


                        @if(
                            ($school?->phone || $school?->email) &&
                            $school?->udise_code
                        )

                            &nbsp; | &nbsp;

                        @endif


                        @if($school?->udise_code)

                            UDISE:
                            {{ $school->udise_code }}

                        @endif


                    </div>

                @endif


            </div>


            {{-- =================================================
                 RESULT TITLE
            ================================================== --}}

            <div class="document-title">


                <h2>

                    Student Result

                </h2>


                <div class="exam-name">

                    {{ $exam?->exam_name ?? 'Examination' }}

                </div>


            </div>


            {{-- =================================================
                 STUDENT INFORMATION
            ================================================== --}}

            <div class="section-box">


                <div class="section-title">

                    Student Information

                </div>


                <table class="student-info">


                    <tr>


                        <td class="label">

                            Student ID

                        </td>


                        <td class="value">

                            {{ $student?->student_id ?? '-' }}

                        </td>


                        <td class="label">

                            Academic Year

                        </td>


                        <td class="value">

                            {{ $result->academic_year ?? '-' }}

                        </td>


                    </tr>


                    <tr>


                        <td class="label">

                            Student Name

                        </td>


                        <td class="value">

                            {{ $studentName }}

                        </td>


                        <td class="label">

                            Class

                        </td>


                        <td class="value">

                            {{ $result->class_name ?? '-' }}

                        </td>


                    </tr>


                    <tr>


                        <td class="label">

                            Section

                        </td>


                        <td class="value">

                            {{ $result->section ?? '-' }}

                        </td>


                        <td class="label">

                            Published Date

                        </td>


                        <td class="value">

                            {{ $result->published_at?->format('d-m-Y') ?? '-' }}

                        </td>


                    </tr>


                </table>

            </div>


            {{-- =================================================
                 SUBJECT-WISE MARKS
            ================================================== --}}

            <div class="section-box">


                <div class="section-title">

                    Subject-wise Marks

                </div>


                <div class="table-responsive">


                    <table class="marks-table">


                        <thead>


                            <tr>


                                <th style="width: 45%;">

                                    Subject

                                </th>


                                <th style="width: 18%;">

                                    Maximum Marks

                                </th>


                                <th style="width: 18%;">

                                    Obtained Marks

                                </th>


                                <th style="width: 19%;">

                                    Percentage

                                </th>


                            </tr>


                        </thead>


                        <tbody>


                        @forelse($details as $detail)


                            @php


                                $subjectName =

                                    $detail->subject?->subject_name

                                    ?? $detail->subject?->name

                                    ?? $detail->subject_name

                                    ?? '-';


                                $maximumMarks = (float) ($detail->max_marks ?? 0);

if ($maximumMarks <= 0) {
    $maximumMarks = (float) ($detail->total_marks ?? 0);
}

$obtainedMarks = (float) (
    $detail->obtained_marks
    ?? $detail->marks_obtained
    ?? $detail->marks
    ?? 0
);

$percentage = $maximumMarks > 0
    ? ($obtainedMarks / $maximumMarks) * 100
    : 0;


                            @endphp


                            <tr>


                                <td>

                                    <span class="subject-name">

                                        {{ $subjectName }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        (float) $maximumMarks,
                                        2
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        (float) $obtainedMarks,
                                        2
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $percentage,
                                        2
                                    ) }}%

                                </td>


                            </tr>


                        @empty


                            <tr>


                                <td
                                    colspan="4"
                                    class="text-center"
                                >

                                    No subject-wise marks available.

                                </td>


                            </tr>


                        @endforelse


                        </tbody>


                    </table>


                </div>

            </div>


            {{-- =================================================
                 RESULT SUMMARY
            ================================================== --}}

            <div class="section-box">


                <div class="section-title">

                    Result Summary

                </div>


                <table class="summary">


                    <tr>


                        <td class="summary-label">

                            Total Marks

                        </td>


                        <td class="summary-value">

                            {{ number_format(
                                (float) $result->total_marks,
                                2
                            ) }}

                        </td>


                        <td class="summary-label">

                            Obtained Marks

                        </td>


                        <td class="summary-value">

                            {{ number_format(
                                (float) $result->obtained_marks,
                                2
                            ) }}

                        </td>


                    </tr>


                    <tr>


                        <td class="summary-label">

                            Percentage

                        </td>


                        <td class="summary-value">

                            {{ number_format(
                                (float) $result->percentage,
                                2
                            ) }}%

                        </td>


                        <td class="summary-label">

                            Grade

                        </td>


                        <td class="summary-value">

                            {{ $result->grade ?? '-' }}

                        </td>


                    </tr>


                    <tr>


                        <td class="summary-label">

                            Result Status

                        </td>


                        <td
                            colspan="3"
                            class="summary-value"
                        >


                            @php

                                $status = strtoupper(
                                    trim(
                                        $result->result_status ?? ''
                                    )
                                );

                            @endphp


                            @if($status === 'PASS')


                                <span
                                    class="status-badge status-pass"
                                >

                                    PASS

                                </span>


                            @elseif($status === 'FAIL')


                                <span
                                    class="status-badge status-fail"
                                >

                                    FAIL

                                </span>


                            @elseif($status === 'ABSENT')


                                <span
                                    class="status-badge status-absent"
                                >

                                    ABSENT

                                </span>


                            @else


                                <span class="status-badge">

                                    {{ $status ?: 'N/A' }}

                                </span>


                            @endif


                        </td>


                    </tr>


                </table>

            </div>


            {{-- =================================================
                 REMARKS
            ================================================== --}}

            @if($result->remarks)


                <div class="section-box">


                    <div class="section-title">

                        Remarks

                    </div>


                    <div class="remarks">


                        <div class="remarks-title">

                            Remarks

                        </div>


                        <div>

                            {{ $result->remarks }}

                        </div>


                    </div>


                </div>

            @endif


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div class="result-footer">


                <div class="row align-items-center">


                    <div class="col-md-8">


                        <strong>
                            Published Result
                        </strong>


                        <br>


                        This is an electronically published result.
                        Please contact the school administration if
                        any information appears incorrect.


                    </div>


                    <div class="col-md-4 text-md-end mt-3 mt-md-0">


                        <strong>

                            Published:

                        </strong>


                        {{ $result->published_at?->format('d M Y') ?? '-' }}


                    </div>


                </div>


                @if($result->published_at)


                    <div class="published-info">


                        Published on

                        {{ $result->published_at->format(
                            'd-m-Y h:i A'
                        ) }}


                        @if($result->id)


                            &nbsp; | &nbsp;


                            Result ID:
                            {{ $result->id }}


                        @endif


                    </div>


                @endif


            </div>


        </div>


    </div>


    {{-- =========================================================
         ACTION BUTTONS
    ========================================================== --}}

    <div class="action-bar no-print">


        {{-- Print --}}

        <button
            type="button"
            class="btn btn-primary"
            onclick="window.print()"
        >

            <i class="bi bi-printer me-2"></i>

            Print Result

        </button>


        {{-- View PDF --}}

        @if(
            Route::has('result.pdf') &&
            $student?->student_id
        )


            <a
                href="{{ route(
                    'result.pdf',
                    [
                        'student' => $student->student_id
                    ]
                ) }}"
                target="_blank"
                class="btn btn-primary"
            >

                <i class="bi bi-file-earmark-pdf me-2"></i>

                View PDF

            </a>


        @endif


        {{-- Download PDF --}}

        @if(
            Route::has('result.pdf.download') &&
            $student?->student_id
        )


            <a
                href="{{ route(
                    'result.pdf.download',
                    [
                        'student' => $student->student_id
                    ]
                ) }}"
                class="btn btn-success"
            >

                <i class="bi bi-download me-2"></i>

                Download PDF

            </a>


        @endif


        {{-- Check Another Result --}}

        @if(Route::has('result.public'))


            <a
                href="{{ route('result.public') }}?reset=1"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-search me-2"></i>

                Check Another Result

            </a>


        @endif


    </div>


</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | Prevent stale browser page from showing old result information
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'pageshow',
        function (event) {

            if (event.persisted) {

                window.location.reload();

            }

        }
    );

</script>


</body>

</html>
