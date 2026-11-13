
@php
    $school = $school ?? \App\Models\SchoolSetting::first();

    $student = $result->student;
    $exam = $result->exam;
    $details = $result->details ?? collect();

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

    /*
    |--------------------------------------------------------------------------
    | Logo handling
    |--------------------------------------------------------------------------
    | Cloudinary/HTTP URLs are used directly.
    | Local storage logos are converted to Base64 for DomPDF.
    */
    $logoUrl = asset('images/gurukullogo.png');

    if (!empty($school?->logo)) {
        if (filter_var($school->logo, FILTER_VALIDATE_URL)) {
            $logoUrl = $school->logo;
        } else {
            $logoPath = storage_path(
                'app/public/' . ltrim($school->logo, '/')
            );

            if (file_exists($logoPath)) {
                $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));

                $mime = match ($extension) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'webp' => 'image/webp',
                    default => 'image/png',
                };

                $logoUrl = 'data:' . $mime . ';base64,' .
                    base64_encode(file_get_contents($logoPath));
            }
        }
    }

    $publishedDate = $result->published_at
        ? \Carbon\Carbon::parse($result->published_at)->format('d-m-Y')
        : '-';

    $totalMarks = $result->total_marks ?? $details->sum('max_marks');

    $obtainedMarks = $result->obtained_marks
        ?? $details->sum('obtained_marks');

    $percentage = $result->percentage
        ?? ($totalMarks > 0
            ? round(($obtainedMarks / $totalMarks) * 100, 2)
            : 0);

    $resultStatus = $result->result_status ?? '—';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Student Result - {{ $studentName }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #202b38;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .marksheet {
            position: relative;
            border: 2px solid #263d5b;
            padding: 20px 22px;
        }

        .inner-border {
            position: absolute;
            top: 5px;
            right: 5px;
            bottom: 5px;
            left: 5px;
            border: 1px solid #263d5b;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 2;
        }

        .school-header {
            width: 100%;
            border-bottom: 2px solid #263d5b;
            padding-bottom: 13px;
            border-collapse: collapse;
        }

        .school-header td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 95px;
            text-align: left;
        }

        .school-logo {
            width: 82px;
            height: 82px;
            object-fit: contain;
        }

        .school-info {
            text-align: center;
            padding: 0 8px;
        }

        .school-name {
            color: #17385f;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 6px;
        }

        .school-address {
            font-size: 9px;
            line-height: 1.6;
            margin: 0;
        }

        .school-contact {
            font-size: 8px;
            line-height: 1.6;
            margin-top: 4px;
        }

        .school-codes {
            width: 115px;
            text-align: right;
            font-size: 8px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .school-codes strong {
            color: #17385f;
        }

        .marksheet-heading {
            text-align: center;
            margin: 18px 0;
        }

        .marksheet-heading h2 {
            display: inline-block;
            color: #17385f;
            border: 1px solid #17385f;
            padding: 7px 20px;
            font-size: 15px;
            letter-spacing: 1px;
            margin: 0 0 7px;
        }

        .marksheet-heading p {
            font-size: 10px;
            font-weight: bold;
            margin: 0;
        }

        .section-heading {
            background: #eaf0f7;
            color: #17385f;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 7px 9px;
            border: 1px solid #b9c7d8;
            margin-top: 15px;
        }

        table.details-table,
        table.marks-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            width: 50%;
            border: 1px solid #cbd3dc;
            padding: 8px;
            font-size: 9px;
        }

        .details-table strong {
            color: #263d5b;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #aeb9c7;
            padding: 8px 6px;
            font-size: 9px;
            text-align: center;
        }

        .marks-table th {
            background: #eaf0f7;
            color: #17385f;
            font-weight: bold;
        }

        .marks-table td.subject-name {
            text-align: left;
        }

        .marks-table .total-row td {
            background: #f1f4f8;
            font-weight: bold;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin: 8px -5px 0;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #b9c7d8;
            padding: 10px 5px;
            text-align: center;
        }

        .summary-label {
            display: block;
            color: #617084;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-value {
            color: #17385f;
            font-size: 13px;
            font-weight: bold;
        }

        .final-result {
            border: 1px solid #b9c7d8;
            padding: 9px;
            text-align: center;
            margin-top: 5px;
        }

        .remarks {
            border: 1px solid #cbd3dc;
            min-height: 45px;
            padding: 9px;
            font-size: 9px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 65px;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 8px;
        }

        .signature-line {
            border-top: 1px solid #263d5b;
            padding-top: 7px;
            font-size: 9px;
            font-weight: bold;
        }

        .principal-name {
            font-size: 8px;
            font-weight: normal;
            margin-top: 3px;
        }

        .footer {
            border-top: 1px solid #cbd3dc;
            margin-top: 18px;
            padding-top: 8px;
            font-size: 8px;
            color: #617084;
        }

        .footer table {
            width: 100%;
        }

        .footer td:last-child {
            text-align: right;
        }

        .watermark {
            position: absolute;
            width: 260px;
            height: 260px;
            top: 50%;
            left: 50%;
            margin-left: -130px;
            margin-top: -130px;
            opacity: 0.045;
            z-index: 1;
        }
    </style>
</head>

<body>

<div class="marksheet">

    <div class="inner-border"></div>

    @if($logoUrl)
        <img src="{{ $logoUrl }}" class="watermark" alt="">
    @endif

    <div class="content">

        {{-- SCHOOL HEADER --}}
        <table class="school-header">
            <tr>
                <td class="logo-cell">
                    <img src="{{ $logoUrl }}" class="school-logo" alt="School Logo">
                </td>

                <td class="school-info">
                    <h1 class="school-name">{{ $schoolName }}</h1>

                    @if($schoolAddress)
                        <p class="school-address">{{ $schoolAddress }}</p>
                    @endif

                    <div class="school-contact">
                        @if($school->phone ?? false)
                            Phone: {{ $school->phone }}
                        @endif

                        @if($school->email ?? false)
                            | Email: {{ $school->email }}
                        @endif

                        @if($school->website ?? false)
                            <br>{{ $school->website }}
                        @endif
                    </div>
                </td>

                <td class="school-codes">
                    @if($school->school_code ?? false)
                        <div><strong>School Code:</strong> {{ $school->school_code }}</div>
                    @endif

                    @if($school->udise_code ?? false)
                        <div><strong>UDISE:</strong> {{ $school->udise_code }}</div>
                    @endif

                    @if($school->established_year ?? false)
                        <div><strong>Established:</strong> {{ $school->established_year }}</div>
                    @endif
                </td>
            </tr>
        </table>

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

        <table class="details-table">
            <tr>
                <td><strong>Student Name:</strong> {{ $studentName ?: '-' }}</td>
                <td><strong>Student ID:</strong> {{ $student->student_id ?? '-' }}</td>
            </tr>

            <tr>
                <td>
                    <strong>Class:</strong>
                    {{ $result->class_name ?? $student->class ?? '-' }}
                </td>

                <td>
                    <strong>Section:</strong>
                    {{ $result->section ?? $student->section ?? '-' }}
                </td>
            </tr>

            <tr>
                <td><strong>Academic Year:</strong> {{ $result->academic_year ?? '-' }}</td>
                <td><strong>Result Published:</strong> {{ $publishedDate }}</td>
            </tr>
        </table>

        {{-- SUBJECT MARKS --}}
        <div class="section-heading">Academic Performance</div>

        <table class="marks-table">
            <thead>
                <tr>
                    <th width="8%">Sr. No.</th>
                    <th>Subject</th>
                    <th width="20%">Maximum Marks</th>
                    <th width="20%">Obtained Marks</th>
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
                            {{ $detail->max_marks ?? $detail->total_marks ?? '-' }}
                        </td>

                        <td>
                            {{ $detail->obtained_marks
                                ?? $detail->marks_obtained
                                ?? $detail->marks
                                ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No subject marks available.</td>
                    </tr>
                @endforelse

                <tr class="total-row">
                    <td colspan="2" style="text-align:right">TOTAL</td>
                    <td>{{ $totalMarks }}</td>
                    <td>{{ $obtainedMarks }}</td>
                </tr>
            </tbody>
        </table>

        {{-- RESULT SUMMARY --}}
        <div class="section-heading">Result Summary</div>

        <table class="summary-table">
            <tr>
                <td>
                    <span class="summary-label">Total Marks</span>
                    <span class="summary-value">{{ $totalMarks }}</span>
                </td>

                <td>
                    <span class="summary-label">Marks Obtained</span>
                    <span class="summary-value">{{ $obtainedMarks }}</span>
                </td>

                <td>
                    <span class="summary-label">Percentage</span>
                    <span class="summary-value">{{ number_format((float) $percentage, 2) }}%</span>
                </td>

                <td>
                    <span class="summary-label">Grade</span>
                    <span class="summary-value">{{ $result->grade ?? '-' }}</span>
                </td>
            </tr>
        </table>

        <div class="final-result">
            <span class="summary-label">Final Result</span>
            <span class="summary-value">{{ $resultStatus }}</span>
        </div>

        {{-- REMARKS --}}
        @if(!empty($result->remarks))
            <div class="section-heading">Remarks</div>
            <div class="remarks">{{ $result->remarks }}</div>
        @endif

        {{-- SIGNATURES --}}
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line">Class Teacher</div>
                </td>

                <td>
                    <div class="signature-line">School Seal</div>
                </td>

                <td>
                    <div class="signature-line">
                        Principal

                        @if($school->principal_name ?? false)
                            <div class="principal-name">
                                {{ $school->principal_name }}
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        {{-- FOOTER --}}
        <div class="footer">
            <table>
                <tr>
                    <td>Generated from {{ $schoolName }}</td>
                    <td>Published on: {{ $publishedDate }}</td>
                </tr>
            </table>
        </div>

    </div>
</div>

</body>
</html>
