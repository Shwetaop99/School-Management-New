<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Profile Report</title>
    <style>
        @page { margin: 28px 30px; }
        body { font-family: DejaVu Sans, sans-serif; color: #26352e; font-size: 9px; }
        .header { text-align: center; border-bottom: 2px solid #2c3e36; padding-bottom: 12px; margin-bottom: 18px; }
        .header img { max-height: 65px; max-width: 75px; margin-bottom: 5px; }
        .header h1 { font-size: 19px; margin: 3px 0; color: #20352c; }
        .header p { margin: 3px 0; color: #5c6b64; }
        .header h2 { font-size: 13px; margin: 10px 0 0; }
        .student-hero { background: #f3f0e8; border: 1px solid #d4cfc2; padding: 12px; margin-bottom: 15px; }
        .student-hero h2 { margin: 0 0 5px; font-size: 16px; color: #20352c; }
        .student-hero p { margin: 3px 0; }
        .section { margin: 13px 0; page-break-inside: auto; }
        .section h3 { background: #2c3e36; color: #fff; padding: 7px 9px; font-size: 11px; margin: 0 0 7px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #d8d8d0; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { background: #f1eee5; color: #263b35; font-weight: bold; }
        .details td:first-child { width: 23%; font-weight: bold; background: #faf9f5; }
        .muted { color: #68756e; }
        .empty { color: #68756e; font-style: italic; padding: 7px 0; }
        .subheading { font-size: 10px; font-weight: bold; margin: 9px 0 5px; color: #344f43; }
        .note { font-size: 8px; color: #68756e; margin-top: 5px; }
        .footer { text-align: center; color: #7b857b; font-size: 8px; border-top: 1px solid #ddd; padding-top: 8px; margin-top: 20px; }
        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body>

<div class="header">
    @if($schoolSetting && $schoolSetting->logo_url)
        <img src="{{ $schoolSetting->logo_url }}" alt="School Logo">
    @endif
    <h1>{{ $schoolSetting->school_name ?? config('app.name') }}</h1>
    <p>{{ collect([$schoolSetting->address ?? null, $schoolSetting->city ?? null, $schoolSetting->district ?? null, $schoolSetting->state ?? null, $schoolSetting->pincode ?? null])->filter()->implode(', ') }}</p>
    <p>
        @if($schoolSetting?->phone) Phone: {{ $schoolSetting->phone }} @endif
        @if($schoolSetting?->email) | Email: {{ $schoolSetting->email }} @endif
        @if($schoolSetting?->website) | {{ $schoolSetting->website }} @endif
    </p>
    <h2>INDIVIDUAL STUDENT PROFILE REPORT</h2>
</div>

<div class="student-hero">
    <h2>{{ $student->full_name ?: 'Student Name Not Available' }}</h2>
    <p>
        Class: {{ $student->class ?: '—' }} |
        Section: {{ $student->section ?: '—' }} |
        Roll No: {{ $student->roll_number ?: '—' }}
    </p>
    <p>
        Student ID: {{ $student->student_id ?: '—' }} |
        Academic Year: {{ $student->academic_year ?: '—' }} |
        Status: {{ ucfirst($student->status ?? '—') }}
    </p>
</div>

<div class="section">
    <h3>1. Personal Details</h3>
    <table class="details">
        <tr><td>Full Name</td><td>{{ $student->full_name ?: '—' }}</td><td>Gender</td><td>{{ ucfirst($student->gender ?? '—') }}</td></tr>
        <tr><td>Date of Birth</td><td>{{ $student->date_of_birth?->format('d M Y') ?? '—' }}</td><td>Birth Place</td><td>{{ $student->birth_place ?: '—' }}</td></tr>
        <tr><td>Phone</td><td>{{ $student->phone ?: '—' }}</td><td>Email</td><td>{{ $student->email ?: '—' }}</td></tr>
        <tr><td>Aadhaar Number</td><td>{{ $student->aadhar_card_no ?: '—' }}</td><td>Saral ID</td><td>{{ $student->saral_id ?: '—' }}</td></tr>
        <tr><td>Admission Class</td><td>{{ $student->admission_class ?: '—' }}</td><td>Admission Date</td><td>{{ $student->admission_date?->format('d M Y') ?? '—' }}</td></tr>
        <tr><td>Register Number</td><td>{{ $student->register_no ?: '—' }}</td><td>Medium</td><td>{{ $student->medium ?: '—' }}</td></tr>
        <tr><td>Mother Tongue</td><td>{{ $student->mother_tongue ?: '—' }}</td><td>Nationality</td><td>{{ $student->nationality ?: '—' }}</td></tr>
        <tr><td>Religion</td><td>{{ $student->religion ?: '—' }}</td><td>Caste / Sub Caste</td><td>{{ collect([$student->caste, $student->sub_caste])->filter()->implode(' / ') ?: '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <h3>2. Parent & Guardian Details</h3>
    <table class="details">
        <tr><td>Father Name</td><td>{{ $student->father_name ?: '—' }}</td><td>Father Phone</td><td>{{ $student->father_phone ?: '—' }}</td></tr>
        <tr><td>Father Occupation</td><td>{{ $student->father_occupation ?: '—' }}</td><td>Mother Name</td><td>{{ $student->mother_name ?: '—' }}</td></tr>
        <tr><td>Mother Phone</td><td>{{ $student->mother_phone ?: '—' }}</td><td>Mother Occupation</td><td>{{ $student->mother_occupation ?: '—' }}</td></tr>
        <tr><td>Guardian Name</td><td>{{ $student->guardian_name ?: '—' }}</td><td>Guardian Relation</td><td>{{ $student->guardian_relation ?: '—' }}</td></tr>
        <tr><td>Guardian Phone</td><td colspan="3">{{ $student->guardian_phone ?: '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <h3>3. Address Details</h3>
    <table class="details">
        <tr><td>Address</td><td colspan="3">{{ $student->address ?: '—' }}</td></tr>
        <tr><td>City / Village</td><td>{{ $student->city_village ?: '—' }}</td><td>Taluka</td><td>{{ $student->taluka ?: '—' }}</td></tr>
        <tr><td>District</td><td>{{ $student->district ?: '—' }}</td><td>State</td><td>{{ $student->state ?: '—' }}</td></tr>
        <tr><td>Country</td><td>{{ $student->country ?: '—' }}</td><td>Pincode</td><td>{{ $student->pincode ?: '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <h3>4. Attendance Summary</h3>
    <table>
        <tr>
            <th>Attendance %</th>
            <th>Attended Equivalent</th>
            <th>Present</th>
            <th>Absent</th>
        </tr>
        <tr>
            <td>{{ number_format($attendancePercentage, 2) }}%</td>
            <td>{{ number_format($attendedDays, 1) }}</td>
            <td>{{ $presentDays }}</td>
            <td>{{ $absentDays }}</td>
        </tr>
    </table>

    <div class="subheading">Attendance History</div>
    @if($attendanceRecords->isNotEmpty())
        <table>
            <tr><th>Date</th><th>Class</th><th>Section</th><th>Status</th><th>Remarks</th></tr>
            @foreach($attendanceRecords as $attendance)
                <tr>
                    <td>{{ $attendance->attendance_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $attendance->class ?: '—' }}</td>
                    <td>{{ $attendance->section ?: '—' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $attendance->status ?? '—')) }}</td>
                    <td>{{ $attendance->remarks ?: '—' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="empty">No attendance history found.</p>
    @endif
</div>

<div class="section">
    <h3>5. Transport Details</h3>
    @if($transportRecords->isNotEmpty())
        <table>
            <tr><th>Route</th><th>Vehicle</th><th>Pickup</th><th>Drop</th><th>Type</th><th>Status</th><th>Fee</th><th>Payment</th></tr>
            @foreach($transportRecords as $transport)
                <tr>
                    <td>{{ $transport->route ?: '—' }}</td>
                    <td>{{ $transport->vehicle ?: '—' }}</td>
                    <td>{{ $transport->pickup_point ?: '—' }}</td>
                    <td>{{ $transport->drop_point ?: '—' }}</td>
                    <td>{{ $transport->transport_type ?: '—' }}</td>
                    <td>{{ $transport->transport_status ?: '—' }}</td>
                    <td>{{ $transport->transport_fee !== null ? number_format((float)$transport->transport_fee, 2) : '—' }}</td>
                    <td>{{ $transport->payment_status ?: '—' }}</td>
                </tr>
                <tr><td colspan="8">Pickup Time: {{ $transport->pickup_time ?: '—' }} | Drop Time: {{ $transport->drop_time ?: '—' }} | Start: {{ $transport->start_date ? \Carbon\Carbon::parse($transport->start_date)->format('d M Y') : '—' }} | End: {{ $transport->end_date ? \Carbon\Carbon::parse($transport->end_date)->format('d M Y') : '—' }}</td></tr>
            @endforeach
        </table>
    @else
        <p class="empty">No transport records found for this student.</p>
    @endif
</div>

<div class="section">
    <h3>6. Scholarship Exam Applications</h3>
    @if($scholarshipApplications->isNotEmpty())
        <table>
            <tr><th>Exam Name</th><th>Exam Date</th><th>Academic Year</th><th>Application Status</th></tr>
            @foreach($scholarshipApplications as $application)
                <tr>
                    <td>{{ $application->exam?->exam_name ?? '—' }}</td>
                    <td>{{ $application->exam?->exam_date ? \Carbon\Carbon::parse($application->exam->exam_date)->format('d M Y') : '—' }}</td>
                    <td>{{ $application->exam?->academic_year ?? '—' }}</td>
                    <td>{{ ucfirst($application->status ?? '—') }}</td>
                </tr>
            @endforeach
        </table>
        <p class="note">These are scholarship exam applications, not confirmed scholarship awards.</p>
    @else
        <p class="empty">No scholarship exam applications found.</p>
    @endif
</div>

<div class="section">
    <h3>7. Fee Records & Payment History</h3>
    @if($feeRecords->isNotEmpty())
        @foreach($feeRecords as $fee)
            <div class="subheading">{{ $fee->feeStructure?->structure_name ?? 'Fee Record' }} — {{ $fee->academic_year ?: '—' }}</div>
            <table>
                <tr><th>Total</th><th>Discount</th><th>Fine</th><th>Paid</th><th>Balance</th><th>Status</th><th>Due Date</th></tr>
                <tr>
                    <td>{{ number_format((float)$fee->total_amount, 2) }}</td>
                    <td>{{ number_format((float)$fee->discount_amount, 2) }}</td>
                    <td>{{ number_format((float)$fee->fine_amount, 2) }}</td>
                    <td>{{ number_format((float)$fee->paid_amount, 2) }}</td>
                    <td>{{ number_format((float)$fee->balance_amount, 2) }}</td>
                    <td>{{ ucfirst($fee->status ?? '—') }}</td>
                    <td>{{ $fee->due_date?->format('d M Y') ?? '—' }}</td>
                </tr>
            </table>
            @if($fee->payments->isNotEmpty())
                <table>
                    <tr><th>Receipt</th><th>Amount</th><th>Payment Date</th><th>Method</th><th>Reference</th></tr>
                    @foreach($fee->payments as $payment)
                        <tr>
                            <td>{{ $payment->receipt_number ?: '—' }}</td>
                            <td>{{ number_format((float)$payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $payment->payment_method ?: '—' }}</td>
                            <td>{{ $payment->transaction_reference ?: '—' }}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <p class="empty">No payment history available for this fee record.</p>
            @endif
        @endforeach
    @else
        <p class="empty">No fee records found for this student.</p>
    @endif
</div>

<div class="section">
    <h3>8. Sports Achievements</h3>
    @if($sportsAchievements->isNotEmpty())
        <table>
            <tr><th>Sport</th><th>Achievement</th><th>Competition</th><th>Position</th><th>Date</th><th>Venue</th><th>Academic Year</th></tr>
            @foreach($sportsAchievements as $achievement)
                <tr>
                    <td>{{ $achievement->sport_name ?: '—' }}</td>
                    <td>{{ $achievement->title ?: '—' }}</td>
                    <td>{{ $achievement->competition_name ?: '—' }}</td>
                    <td>{{ $achievement->position ?: '—' }}</td>
                    <td>{{ $achievement->achievement_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $achievement->venue ?: '—' }}</td>
                    <td>{{ $achievement->academic_year ?: '—' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="empty">No sports achievements found.</p>
    @endif
</div>

<div class="section">
    <h3>9. Library Issue & Return History</h3>
    @if($libraryRecords->isNotEmpty())
        <table>
            <tr><th>Book</th><th>Issue Date</th><th>Due Date</th><th>Return Date</th><th>Status</th><th>Fine</th></tr>
            @foreach($libraryRecords as $record)
                <tr>
                    <td>{{ $record->book?->title ?? $record->book?->book_name ?? '—' }}</td>
                    <td>{{ $record->issue_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $record->due_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $record->return_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ ucfirst($record->status ?? '—') }}</td>
                    <td>{{ $record->fine !== null ? number_format((float)$record->fine, 2) : '—' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="empty">No library issue records found.</p>
    @endif
</div>

<div class="footer">
    Generated on {{ now()->format('d M Y, h:i A') }} | {{ $schoolSetting->school_name ?? config('app.name') }}
</div>

</body>
</html>