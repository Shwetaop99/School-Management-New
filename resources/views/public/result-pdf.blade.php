
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

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
        | SCHOOL LOGO
        |--------------------------------------------------------------------------
        |
        | DomPDF may not load browser/public URLs correctly.
        | Therefore we load the actual file and convert it to Base64.
        |
        */

        $logoData = null;

        if ($school?->logo) {

            $logoPath = storage_path(
                'app/public/' . ltrim($school->logo, '/')
            );

            if (file_exists($logoPath)) {

                try {

                    $imageContents = file_get_contents($logoPath);

                    if ($imageContents !== false) {

                        $extension = strtolower(
                            pathinfo(
                                $logoPath,
                                PATHINFO_EXTENSION
                            )
                        );

                        $mimeType = 'image/jpeg';

                        if ($extension === 'png') {

                            $mimeType = 'image/png';

                        } elseif (
                            $extension === 'jpg' ||
                            $extension === 'jpeg'
                        ) {

                            $mimeType = 'image/jpeg';

                        } elseif ($extension === 'webp') {

                            $mimeType = 'image/webp';

                        }

                        $logoData =
                            'data:' .
                            $mimeType .
                            ';base64,' .
                            base64_encode($imageContents);
                    }

                } catch (\Throwable $e) {

                    $logoData = null;
                }
            }
        }

    @endphp


    <title>
        Result - {{ $studentName }}
    </title>


    <style>

        @page {
            size: A4;
            margin: 12mm;
        }


        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #222;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }


        /*
        |--------------------------------------------------------------------------
        | MAIN PAGE
        |--------------------------------------------------------------------------
        */

        .page {
            width: 100%;
            margin: 0 auto;
            position: relative;
        }


        /*
        |--------------------------------------------------------------------------
        | FAINT BACKGROUND LOGO / WATERMARK
        |--------------------------------------------------------------------------
        |
        | The logo is placed behind the result content.
        | Very low opacity keeps the document readable.
        |
        */

        .watermark {
            position: fixed;

            top: 50%;
            left: 50%;

            width: 330px;
            height: 330px;

            margin-left: -165px;
            margin-top: -165px;

            text-align: center;

            z-index: -1;

            opacity: 0.055;
        }


        .watermark img {
            width: 330px;
            height: 330px;

            object-fit: contain;
        }


        /*
        |--------------------------------------------------------------------------
        | CONTENT ABOVE WATERMARK
        |--------------------------------------------------------------------------
        */

        .content {
            position: relative;
            z-index: 1;
        }


        /* ================================
           SCHOOL HEADER
        ================================= */

        .school-header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }


        .school-logo {
            width: 65px;
            height: 65px;
            margin-bottom: 4px;
            object-fit: contain;
        }


        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin: 3px 0;
        }


        .school-address {
            font-size: 10px;
            margin: 3px 0;
        }


        .school-contact {
            font-size: 9px;
            margin-top: 4px;
        }


        /* ================================
           RESULT TITLE
        ================================= */

        .document-title {
            text-align: center;
            margin: 10px 0 12px;
        }


        .document-title h2 {
            margin: 0;
            font-size: 17px;
            text-transform: uppercase;
        }


        .exam-name {
            margin-top: 4px;
            font-size: 12px;
            font-weight: bold;
        }


        /* ================================
           STUDENT INFORMATION
        ================================= */

        .student-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }


        .student-info td {
            border: 1px solid #777;
            padding: 6px;
            vertical-align: middle;
        }


        .label {
            width: 18%;
            font-weight: bold;
            background: #f2f2f2;
        }


        .value {
            width: 32%;
        }


        /* ================================
           MARKS TABLE
        ================================= */

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }


        .marks-table th,
        .marks-table td {
            border: 1px solid #555;
            padding: 6px;
            text-align: center;
        }


        .marks-table th {
            background: #eeeeee;
            font-weight: bold;
        }


        .marks-table th:first-child,
        .marks-table td:first-child {
            text-align: left;
        }


        .marks-table tr {
            page-break-inside: avoid;
        }


        /* ================================
           RESULT SUMMARY
        ================================= */

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }


        .summary td {
            border: 1px solid #777;
            padding: 7px;
        }


        .summary-label {
            width: 25%;
            background: #f2f2f2;
            font-weight: bold;
        }


        .summary-value {
            width: 25%;
        }


        .status {
            font-weight: bold;
            text-transform: uppercase;
        }


        /* ================================
           REMARKS
        ================================= */

        .remarks {
            border: 1px solid #777;
            margin-top: 12px;
            padding: 8px;
        }


        .remarks-title {
            font-weight: bold;
            margin-bottom: 4px;
        }


        /* ================================
           FOOTER
        ================================= */

        .footer {
            margin-top: 28px;
            font-size: 9px;
        }


        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }


        .footer-table td {
            width: 50%;
            padding-top: 15px;
            vertical-align: bottom;
        }


        .right {
            text-align: right;
        }


        .signature-line {
            border-top: 1px solid #555;
            width: 140px;
            margin-left: auto;
            padding-top: 4px;
            text-align: center;
        }


        .published-info {
            text-align: center;
            margin-top: 15px;
            font-size: 8px;
            color: #666;
        }


        /* ================================
           PRINT / PDF SAFETY
        ================================= */

        table {
            page-break-inside: avoid;
        }


        tr {
            page-break-inside: avoid;
        }

    </style>

</head>


<body>


<div class="page">


    {{-- =========================================================
         FAINT BACKGROUND SCHOOL LOGO
    ========================================================== --}}

    @if($logoData)

        <div class="watermark">

            <img
                src="{{ $logoData }}"
                alt=""
            >

        </div>

    @endif


    <div class="content">


        {{-- =========================================================
             SCHOOL HEADER
        ========================================================== --}}

        <div class="school-header">


            @if($logoData)

                <img
                    src="{{ $logoData }}"
                    class="school-logo"
                    alt="School Logo"
                >

            @endif


            <div class="school-name">

                {{ $school?->school_name ?? 'School Management System' }}

            </div>


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


            <div class="school-contact">


                @if($school?->phone)

                    Phone:
                    {{ $school->phone }}

                @endif


                @if($school?->email)

                    &nbsp; | &nbsp;

                    Email:
                    {{ $school->email }}

                @endif


                @if($school?->udise_code)

                    &nbsp; | &nbsp;

                    UDISE:
                    {{ $school->udise_code }}

                @endif

            </div>

        </div>


        {{-- =========================================================
             RESULT TITLE
        ========================================================== --}}

        <div class="document-title">


            <h2>
                Student Result
            </h2>


            <div class="exam-name">

                {{ $exam?->exam_name ?? 'Examination' }}

            </div>

        </div>


        {{-- =========================================================
             STUDENT INFORMATION
        ========================================================== --}}

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


        {{-- =========================================================
             SUBJECT-WISE MARKS
        ========================================================== --}}

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


                    $maximumMarks =
                        $detail->max_marks
                        ?? $detail->maximum_marks
                        ?? $detail->total_marks
                        ?? 0;


                    $obtainedMarks =
                        $detail->obtained_marks
                        ?? $detail->marks_obtained
                        ?? $detail->marks
                        ?? 0;


                    $percentage =
                        $maximumMarks > 0
                        ?
                        (
                            (
                                (float) $obtainedMarks /
                                (float) $maximumMarks
                            ) * 100
                        )
                        : 0;

                @endphp


                <tr>


                    <td>

                        {{ $subjectName }}

                    </td>


                    <td>

                        {{ number_format((float) $maximumMarks, 2) }}

                    </td>


                    <td>

                        {{ number_format((float) $obtainedMarks, 2) }}

                    </td>


                    <td>

                        {{ number_format($percentage, 2) }}%

                    </td>


                </tr>


            @empty


                <tr>

                    <td colspan="4">

                        No subject-wise marks available.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>


        {{-- =========================================================
             RESULT SUMMARY
        ========================================================== --}}

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


                <td colspan="3" class="status">

                    {{ $result->result_status ?? '-' }}

                </td>


            </tr>


        </table>


        {{-- =========================================================
             REMARKS
        ========================================================== --}}

        @if($result->remarks)


            <div class="remarks">


                <div class="remarks-title">

                    Remarks

                </div>


                <div>

                    {{ $result->remarks }}

                </div>


            </div>


        @endif


        {{-- =========================================================
             FOOTER
        ========================================================== --}}

        <div class="footer">


            <table class="footer-table">


                <tr>


                    <td>


                        <strong>
                            Published Result
                        </strong>


                        <br>


                        This result was published online
                        by the school.


                    </td>


                    <td class="right">


                        <div class="signature-line">

                            School Authority

                        </div>


                    </td>


                </tr>


            </table>


        </div>


        {{-- =========================================================
             PUBLISHED INFORMATION
        ========================================================== --}}

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


</body>

</html>
