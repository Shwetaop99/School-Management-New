
@php
    $school = $school ?? \App\Models\SchoolSetting::first();

    $student = $result->student;
    $exam = $result->exam;
    $details = $details ?? ($result->details ?? collect());

    $studentName = trim(collect([
        $student->first_name ?? '',
        $student->middle_name ?? '',
        $student->last_name ?? '',
    ])->filter()->implode(' '));

    $schoolName = $school->school_name ?? 'School Name';

    $addressParts = collect([
        $school->address ?? '',
        $school->city ?? '',
        $school->district ?? '',
        $school->state ?? '',
        $school->pincode ?? '',
    ])->filter();

    $schoolAddress = $addressParts->implode(', ');

    $logoUrl = $school?->logo_url ?? asset('images/gurukullogo.png');

    $publishedDate = $result->published_at
        ? \Carbon\Carbon::parse($result->published_at)->format('d-m-Y')
        : '-';

    $generatedDate = $result->generated_at
        ? \Carbon\Carbon::parse($result->generated_at)->format('d-m-Y')
        : '-';

    $totalMarks = $result->total_marks ?? $details->sum('max_marks');
    $obtainedMarks = $result->obtained_marks ?? $details->sum('obtained_marks');

    $percentage = $result->percentage
        ?? ($totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0);

    $resultStatus = $result->result_status ?? '—';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Result - {{ $studentName ?: ($student->student_id ?? 'Student') }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px 12px;
            background: #edf2ed;
            color: #26352b;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        /* ACTION BUTTONS */

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            max-width: 900px;
            margin: 0 auto 18px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px 15px;
            border: 0;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-back {
            background: #667085;
            color: #fff;
        }

        .btn-back:hover {
            background: #475467;
            color: #fff;
        }

        .btn-print {
            background: #286044;
            color: #fff;
        }

        .btn-print:hover {
            background: #20583b;
            color: #fff;
        }

        /* MAIN MARKSHEET */

        .result-wrapper {
            width: 100%;
            max-width: 900px;
            margin: auto;
        }

        .marksheet {
            position: relative;
            overflow: hidden;
            background: #fff;
            border: 2px solid #286044;
            outline: 1px solid #286044;
            outline-offset: -8px;
            padding: 25px 28px;
            box-shadow: 0 5px 25px rgba(0,0,0,.08);
        }

        .marksheet-content {
            position: relative;
            z-index: 1;
        }

        /* WATERMARK */

        .watermark {
            position: absolute;
            width: 270px;
            height: 270px;
            object-fit: contain;
            opacity: .045;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        /* SCHOOL HEADER */

        .school-header {
            display: grid;
            grid-template-columns: 85px 1fr 135px;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 2px solid #286044;
            text-align: center;
        }

        .school-logo {
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .school-name {
            color: #20583b;
            font-size: 21px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0 0 5px;
        }

        .school-address {
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
        }

        .school-contact {
            font-size: 9px;
            line-height: 1.5;
            margin-top: 4px;
        }

        .school-codes {
            text-align: right;
            font-size: 9px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .school-codes strong {
            color: #20583b;
        }

        /* MARKSHEET TITLE */

        .marksheet-heading {
            text-align: center;
            margin: 16px 0;
        }

        .marksheet-heading h2 {
            display: inline-block;
            color: #20583b;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            border: 1px solid #286044;
            padding: 7px 20px;
            margin: 0 0 6px;
        }

        .marksheet-heading p {
            margin: 0;
            font-size: 10px;
            font-weight: 700;
        }

        /* SECTION HEADINGS */

        .section-heading {
            background: #e8f1eb;
            color: #20583b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 7px 9px;
            border: 1px solid #b7cdbd;
            margin: 15px 0 0;
        }

        /* STUDENT DETAILS */

        .student-table,
        .marks-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-table td {
            width: 50%;
            border: 1px solid #c5d5c9;
            padding: 7px 9px;
            font-size: 10px;
        }

        .student-table strong {
            color: #286044;
            margin-right: 4px;
        }

        /* MARKS TABLE */

        .marks-table th,
        .marks-table td {
            border: 1px solid #b7cdbd;
            padding: 8px 7px;
            font-size: 10px;
            text-align: center;
        }

        .marks-table th {
            background: #e8f1eb;
            color: #20583b;
            font-weight: 800;
        }

        .marks-table td.subject-name {
            text-align: left;
            font-weight: 600;
        }

        .marks-table .total-row td {
            background: #edf4ef;
            font-weight: 800;
            color: #20583b;
        }

        /* SUMMARY */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 12px;
        }

        .summary-item {
            border: 1px solid #b7cdbd;
            padding: 9px 7px;
            text-align: center;
        }

        .summary-item span {
            display: block;
            color: #607467;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .summary-item strong {
            color: #20583b;
            font-size: 13px;
        }

        .final-result {
            margin-top: 8px;
            background: #edf4ef;
        }

        /* REMARKS */

        .remarks {
            border: 1px solid #c5d5c9;
            min-height: 45px;
            padding: 8px;
            font-size: 10px;
            line-height: 1.6;
        }

        /* PUBLICATION */

        .publication-info {
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #b7cdbd;
            background: #f3f8f4;
            color: #286044;
            font-size: 9px;
            text-align: center;
            font-weight: 700;
        }

        /* SIGNATURES */

        .signature-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            margin-top: 55px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #286044;
            padding-top: 7px;
            font-size: 9px;
            font-weight: 700;
        }

        .principal-name {
            font-size: 8px;
            font-weight: 400;
            margin-top: 3px;
        }

        /* FOOTER */

        .result-footer {
            border-top: 1px solid #c5d5c9;
            margin-top: 18px;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-size: 8px;
            color: #607467;
        }

        /* MOBILE */

        @media (max-width: 650px) {
            body {
                padding: 10px 5px;
            }

            .top-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                width: 100%;
            }

            .marksheet {
                padding: 20px 13px;
                outline-offset: -6px;
            }

            .school-header {
                grid-template-columns: 60px 1fr;
                gap: 8px;
            }

            .school-logo {
                width: 55px;
                height: 55px;
            }

            .school-name {
                font-size: 15px;
            }

            .school-address,
            .school-contact {
                font-size: 8px;
            }

            .school-codes {
                grid-column: 1 / -1;
                text-align: center;
                font-size: 8px;
            }

            .student-table td {
                display: block;
                width: 100%;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .marks-table th,
            .marks-table td {
                padding: 6px 3px;
                font-size: 9px;
            }

            .signature-row {
                gap: 9px;
                margin-top: 40px;
            }

            .result-footer {
                flex-direction: column;
                text-align: center;
            }
        }

        /* PRINT */

        @media print {
            @page {
                size: A4 portrait;
                margin: 8mm;
            }

            body {
                padding: 0;
                background: #fff;
            }

            .result-wrapper {
                max-width: none;
            }

            .top-actions,
            .no-print {
                display: none !important;
            }

            .marksheet {
                box-shadow: none;
                padding: 20px 22px;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .watermark {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .marks-table tr,
            .student-table tr,
            .summary-item,
            .signature-row,
            .publication-info,
            .remarks {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .section-heading {
                break-after: avoid;
            }

            .marks-table thead {
                display: table-header-group;
            }

            .marks-table tfoot {
                display: table-footer-group;
            }
        }

        .final-result strong {
    display: inline-block;
    font-size: 13px;
    font-weight: 800;
}

.final-result strong.status-pass {
    color: #198754;
}

.final-result strong.status-fail {
    color: #dc3545;
}
    </style>
</head>

<body>

<div class="result-wrapper">

    {{-- ADMIN ACTION BUTTONS --}}
    <div class="top-actions no-print">

        <a href="{{ route('admin.results.show', $result->id) }}"
           class="btn btn-back">
            ← Back to Result
        </a>

        <button type="button"
                class="btn btn-print"
                onclick="window.print()">
            Print Result
        </button>

    </div>

    <div class="marksheet">

        {{-- WATERMARK --}}
        @if($logoUrl)
            <img src="{{ $logoUrl }}"
                 class="watermark"
                 alt=""
                 onerror="this.style.display='none'">
        @endif

        <div class="marksheet-content">

            {{-- SCHOOL HEADER --}}
            <div class="school-header">

                <div>
                    <img src="{{ $logoUrl }}"
                         class="school-logo"
                         alt="School Logo"
                         onerror="this.style.display='none'">
                </div>

                <div>
                    <h1 class="school-name">{{ $schoolName }}</h1>

                    @if($schoolAddress)
                        <p class="school-address">{{ $schoolAddress }}</p>
                    @endif

                    <div class="school-contact">
                        @if($school?->phone)
                            Phone: {{ $school->phone }}
                        @endif

                        @if($school?->email)
                            | Email: {{ $school->email }}
                        @endif

                        @if($school?->website)
                            <br>{{ $school->website }}
                        @endif
                    </div>
                </div>

                <div class="school-codes">

                    @if($school?->school_code)
                        <div>
                            <strong>School Code:</strong>
                            {{ $school->school_code }}
                        </div>
                    @endif

                    @if($school?->udise_code)
                        <div>
                            <strong>UDISE:</strong>
                            {{ $school->udise_code }}
                        </div>
                    @endif

                    @if($school?->established_year)
                        <div>
                            <strong>Established:</strong>
                            {{ $school->established_year }}
                        </div>
                    @endif

                </div>

            </div>

            {{-- RESULT TITLE --}}
            <div class="marksheet-heading">
                <h2>STATEMENT OF MARKS</h2>

                <p>
                    {{ $exam->name ?? $exam->exam_name ?? 'Examination' }}

                    @if($result->academic_year)
                        | Academic Year: {{ $result->academic_year }}
                    @endif
                </p>
            </div>

            {{-- STUDENT INFORMATION --}}
            <div class="section-heading">Student Information</div>

            <table class="student-table">
                <tr>
                    <td>
                        <strong>Student Name:</strong>
                        {{ $studentName ?: '-' }}
                    </td>

                    <td>
                        <strong>Student ID:</strong>
                        {{ $student->student_id ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Roll Number:</strong>
                        {{ $student->roll_number ?? '-' }}
                    </td>

                    <td>
                        <strong>Class:</strong>
                        {{ $result->class_name ?? $student->class ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Section:</strong>
                        {{ $result->section ?? $student->section ?? '-' }}
                    </td>

                    <td>
                        <strong>Academic Year:</strong>
                        {{ $result->academic_year ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Examination:</strong>
                        {{ $exam->exam_name ?? $exam->name ?? '-' }}
                    </td>

                    <td>
                        <strong>Generated On:</strong>
                        {{ $generatedDate }}
                    </td>
                </tr>
            </table>

            {{-- SUBJECT MARKS --}}
            {{-- SUBJECT MARKS --}}
<div class="section-heading">Academic Performance</div>

<table class="marks-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 7%">Sr. No.</th>
            <th rowspan="2">Subject</th>
            <th colspan="2">Marks Obtained</th>
            <th rowspan="2">Obtained</th>
            <th rowspan="2">Maximum</th>
            <th rowspan="2">Grade</th>
        </tr>

        <tr>
            <th>Internal</th>
            <th>Theory</th>
        </tr>
    </thead>

    <tbody>
        @forelse($details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td class="subject-name">
                    {{ $detail->subject->subject_name
                        ?? $detail->subject->name
                        ?? $detail->subject_name
                        ?? '-' }}
                </td>

                <td>
                    {{ number_format((float) ($detail->internal_marks ?? 0), 2) }}
                </td>

                <td>
                    {{ number_format((float) ($detail->theory_marks ?? 0), 2) }}
                </td>

                <td>
                    <strong>
                        {{ number_format((float) ($detail->obtained_marks ?? 0), 2) }}
                    </strong>
                </td>

                <td>
                    {{ number_format((float) ($detail->max_marks ?? 0), 2) }}
                </td>

                <td>
                    {{ $detail->grade ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No subject marks available.</td>
            </tr>
        @endforelse

        <tr class="total-row">
            <td colspan="4" style="text-align: right;">TOTAL</td>
            <td>{{ number_format((float) $obtainedMarks, 2) }}</td>
            <td>{{ number_format((float) $totalMarks, 2) }}</td>
            <td>{{ $result->grade ?? '-' }}</td>
        </tr>
    </tbody>
</table>
            {{-- RESULT SUMMARY --}}
            <div class="section-heading">Result Summary</div>

            <div class="summary-grid">

                <div class="summary-item">
                    <span>Total Maximum</span>
                    <strong>{{ number_format((float) $totalMarks, 2) }}</strong>
                </div>

                <div class="summary-item">
                    <span>Total Obtained</span>
                    <strong>{{ number_format((float) $obtainedMarks, 2) }}</strong>
                </div>

                <div class="summary-item">
                    <span>Percentage</span>
                    <strong>{{ number_format((float) $percentage, 2) }}%</strong>
                </div>

                <div class="summary-item">
                    <span>Grade</span>
                    <strong>{{ $result->grade ?? '-' }}</strong>
                </div>

            </div>

            <div class="summary-item final-result">
    <span>Final Result</span>

    <strong class="{{ strtolower($resultStatus) === 'fail' ? 'status-fail' : 'status-pass' }}">
        {{ strtoupper($resultStatus) }}
    </strong>
</div>

            {{-- PUBLICATION INFORMATION --}}
            @if($result->publication_status === 'published')
                <div class="publication-info">
                    ONLINE RESULT PUBLISHED
                    @if($result->published_at)
                        | Published on:
                        {{ \Carbon\Carbon::parse($result->published_at)->format('d-m-Y h:i A') }}
                    @endif
                </div>
            @endif

            {{-- REMARKS --}}
            @if(!empty($result->remarks))
                <div class="section-heading">Remarks</div>
                <div class="remarks">{{ $result->remarks }}</div>
            @endif

            {{-- SIGNATURES --}}
            <div class="signature-row">

                <div class="signature-line">
                    Class Teacher
                </div>

                <div class="signature-line">
                    School Seal
                </div>

                <div class="signature-line">
                    Principal

                    @if($school?->principal_name)
                        <div class="principal-name">
                            {{ $school->principal_name }}
                        </div>
                    @endif
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="result-footer">
                <span>Generated from {{ $schoolName }}</span>

                <span>
                    Published on: {{ $publishedDate }}
                </span>
            </div>

        </div>
    </div>
</div>

</body>
</html>
