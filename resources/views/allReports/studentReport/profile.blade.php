@extends('layouts.app')

@section('title', 'Student Profile Report | Admin')
@section('page-title', 'Student Profile Report')

@section('content')
<div class="payroll-simple student-profile-report-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL PRINT HEADER --}}
        <div class="profile-print-header">
            @if($schoolSetting && $schoolSetting->logo_url)
                <div class="profile-print-logo-wrap">
                    <img src="{{ $schoolSetting->logo_url }}" alt="School Logo">
                </div>
            @endif
            <div class="profile-print-school">
                <h1>{{ $schoolSetting->school_name ?? config('app.name') }}</h1>
                <p>{{ collect([$schoolSetting->address ?? null, $schoolSetting->city ?? null, $schoolSetting->district ?? null, $schoolSetting->state ?? null, $schoolSetting->pincode ?? null])->filter()->implode(', ') }}</p>
                <p>
                    @if($schoolSetting?->phone) Phone: {{ $schoolSetting->phone }} @endif
                    @if($schoolSetting?->email) | Email: {{ $schoolSetting->email }} @endif
                    @if($schoolSetting?->website) | {{ $schoolSetting->website }} @endif
                </p>
                <h2>Individual Student Profile Report</h2>
            </div>
        </div>

        {{-- TOOLBAR --}}
        <div class="profile-toolbar mb-4">
            <a href="{{ url()->previous() }}" class="profile-back">← Back to Student List</a>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.students.profile.pdf', $student->id) }}"
                   class="profile-action text-decoration-none">Download PDF</a>

                <a href="{{ route('admin.reports.students.profile.excel', $student->id) }}"
                   class="profile-action text-decoration-none">Export Excel</a>

                <button type="button" class="profile-action" onclick="window.print()">Print Profile</button>
            </div>
        </div>

        {{-- STUDENT HERO --}}
        <div class="profile-hero mb-4">
            <div class="profile-hero-main">
                <div class="profile-avatar">
                    @if($student->profile_image)
                        <img src="{{ filter_var($student->profile_image, FILTER_VALIDATE_URL) ? $student->profile_image : asset('storage/' . $student->profile_image) }}"
                             alt="{{ $student->full_name }}">
                    @else
                        <span>{{ strtoupper(substr($student->full_name ?: 'S', 0, 1)) }}</span>
                    @endif
                </div>

                <div>
                    <span class="profile-eyebrow">STUDENT PROFILE</span>
                    <h2>{{ $student->full_name ?: 'Student Name Not Available' }}</h2>
                    <p>
                        {{ $student->class ? 'Class ' . $student->class : 'Class —' }}
                        · Section {{ $student->section ?: '—' }}
                        · Roll No. {{ $student->roll_number ?: '—' }}
                    </p>
                    <span class="profile-status">{{ ucfirst($student->status ?? 'Status unavailable') }}</span>
                </div>
            </div>

            <div class="profile-hero-meta">
                <div><span>Student ID</span><strong>{{ $student->student_id ?: '—' }}</strong></div>
                <div><span>Academic Year</span><strong>{{ $student->academic_year ?: '—' }}</strong></div>
                <div><span>Admission Date</span><strong>{{ $student->admission_date?->format('d M Y') ?? '—' }}</strong></div>
            </div>
        </div>

        {{-- ATTENDANCE SUMMARY --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">ACADEMIC PERFORMANCE</span>
                    <h3>Attendance Summary</h3>
                </div>
                <span class="profile-section-note">Based on recorded attendance</span>
            </div>

            <div class="profile-stats-grid">
                <div class="profile-stat">
                    <span>Attendance</span>
                    <strong>{{ number_format($attendancePercentage, 2) }}%</strong>
                </div>
                <div class="profile-stat">
                    <span>Attended Days</span>
                    <strong>{{ number_format($attendedDays, 1) }}</strong>
                </div>
                <div class="profile-stat">
                    <span>Present</span>
                    <strong>{{ $presentDays }}</strong>
                </div>
                <div class="profile-stat">
                    <span>Absent</span>
                    <strong>{{ $absentDays }}</strong>
                </div>
            </div>
        </div>

        {{-- PERSONAL INFORMATION --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">STUDENT INFORMATION</span>
                    <h3>Personal Details</h3>
                </div>
            </div>

            <div class="profile-details-grid">
                <div><span>Full Name</span><strong>{{ $student->full_name ?: '—' }}</strong></div>
                <div><span>Gender</span><strong>{{ $student->gender ? ucfirst($student->gender) : '—' }}</strong></div>
                <div><span>Date of Birth</span><strong>{{ $student->date_of_birth?->format('d M Y') ?? '—' }}</strong></div>
                <div><span>Birth Place</span><strong>{{ $student->birth_place ?: '—' }}</strong></div>
                <div><span>Phone</span><strong>{{ $student->phone ?: '—' }}</strong></div>
                <div><span>Email</span><strong>{{ $student->email ?: '—' }}</strong></div>
                <div><span>Aadhaar Number</span><strong>{{ $student->aadhar_card_no ?: '—' }}</strong></div>
                <div><span>Saral ID</span><strong>{{ $student->saral_id ?: '—' }}</strong></div>
                <div><span>Nationality</span><strong>{{ $student->nationality ?: '—' }}</strong></div>
                <div><span>Religion</span><strong>{{ $student->religion ?: '—' }}</strong></div>
                <div><span>Caste</span><strong>{{ $student->caste ?: '—' }}</strong></div>
                <div><span>Sub Caste</span><strong>{{ $student->sub_caste ?: '—' }}</strong></div>
                <div><span>Mother Tongue</span><strong>{{ $student->mother_tongue ?: '—' }}</strong></div>
                <div><span>Medium</span><strong>{{ $student->medium ?: '—' }}</strong></div>
            </div>
        </div>

        {{-- PARENT DETAILS --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">FAMILY INFORMATION</span>
                    <h3>Parent & Guardian Details</h3>
                </div>
            </div>

            <div class="profile-details-grid">
                <div><span>Father's Name</span><strong>{{ $student->father_name ?: '—' }}</strong></div>
                <div><span>Father's Phone</span><strong>{{ $student->father_phone ?: '—' }}</strong></div>
                <div><span>Father's Occupation</span><strong>{{ $student->father_occupation ?: '—' }}</strong></div>
                <div><span>Mother's Name</span><strong>{{ $student->mother_name ?: '—' }}</strong></div>
                <div><span>Mother's Phone</span><strong>{{ $student->mother_phone ?: '—' }}</strong></div>
                <div><span>Mother's Occupation</span><strong>{{ $student->mother_occupation ?: '—' }}</strong></div>
                <div><span>Guardian's Name</span><strong>{{ $student->guardian_name ?: '—' }}</strong></div>
                <div><span>Guardian Relation</span><strong>{{ $student->guardian_relation ?: '—' }}</strong></div>
                <div><span>Guardian Phone</span><strong>{{ $student->guardian_phone ?: '—' }}</strong></div>
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">RESIDENTIAL INFORMATION</span>
                    <h3>Address Details</h3>
                </div>
            </div>
            <div class="profile-details-grid">
                <div class="profile-detail-wide"><span>Address</span><strong>{{ $student->address ?: '—' }}</strong></div>
                <div><span>City / Village</span><strong>{{ $student->city_village ?: '—' }}</strong></div>
                <div><span>Taluka</span><strong>{{ $student->taluka ?: '—' }}</strong></div>
                <div><span>District</span><strong>{{ $student->district ?: '—' }}</strong></div>
                <div><span>State</span><strong>{{ $student->state ?: '—' }}</strong></div>
                <div><span>Country</span><strong>{{ $student->country ?: '—' }}</strong></div>
                <div><span>Pincode</span><strong>{{ $student->pincode ?: '—' }}</strong></div>
            </div>
        </div>

        {{-- TRANSPORT --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">STUDENT FACILITIES</span>
                    <h3>Transport Details</h3>
                </div>
                <span class="profile-section-note">{{ $transportRecords->count() }} Records</span>
            </div>

            @forelse($transportRecords as $transport)
                <div class="profile-record-card">
                    <div class="profile-record-grid">
                        <div><span>Transport Status</span><strong>{{ ucfirst($transport->transport_status ?? '—') }}</strong></div>
                        <div><span>Transport Type</span><strong>{{ $transport->transport_type ?: '—' }}</strong></div>
                        <div><span>Route</span><strong>{{ $transport->route ?: '—' }}</strong></div>
                        <div><span>Vehicle</span><strong>{{ $transport->vehicle ?: '—' }}</strong></div>
                        <div><span>Pickup Point</span><strong>{{ $transport->pickup_point ?: '—' }}</strong></div>
                        <div><span>Drop Point</span><strong>{{ $transport->drop_point ?: '—' }}</strong></div>
                        <div><span>Pickup Time</span><strong>{{ $transport->pickup_time ?: '—' }}</strong></div>
                        <div><span>Drop Time</span><strong>{{ $transport->drop_time ?: '—' }}</strong></div>
                        <div><span>Transport Fee</span><strong>{{ $transport->transport_fee !== null ? number_format((float) $transport->transport_fee, 2) : '—' }}</strong></div>
                        <div><span>Payment Status</span><strong>{{ ucfirst($transport->payment_status ?? '—') }}</strong></div>
                        <div><span>Start Date</span><strong>{{ $transport->start_date ? \Carbon\Carbon::parse($transport->start_date)->format('d M Y') : '—' }}</strong></div>
                        <div><span>End Date</span><strong>{{ $transport->end_date ? \Carbon\Carbon::parse($transport->end_date)->format('d M Y') : '—' }}</strong></div>
                    </div>
                </div>
            @empty
                <div class="profile-empty">No transport records found for this student.</div>
            @endforelse
        </div>

        {{-- SCHOLARSHIP --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">STUDENT SUPPORT</span>
                    <h3>Scholarship Applications</h3>
                </div>
                <span class="profile-section-note">{{ $scholarshipApplications->count() }} Records</span>
            </div>

            @forelse($scholarshipApplications as $application)
                <div class="profile-record-card">
                    <div class="profile-record-grid">
                        <div><span>Scholarship Exam</span><strong>{{ $application->exam?->exam_name ?? '—' }}</strong></div>
                        <div><span>Academic Year</span><strong>{{ $application->exam?->academic_year ?? '—' }}</strong></div>
                        <div><span>Exam Date</span><strong>{{ $application->exam?->exam_date ? \Carbon\Carbon::parse($application->exam->exam_date)->format('d M Y') : '—' }}</strong></div>
                        <div><span>Application Status</span><strong>{{ ucfirst($application->status ?? '—') }}</strong></div>
                        <div class="profile-detail-wide"><span>Remarks</span><strong>{{ $application->remarks ?: '—' }}</strong></div>
                    </div>
                </div>
            @empty
                <div class="profile-empty">No scholarship exam applications found.</div>
            @endforelse
            <p class="profile-disclaimer">This section displays scholarship exam applications, not confirmed scholarship awards.</p>
        </div>

        {{-- FEES --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">FINANCIAL INFORMATION</span>
                    <h3>Fee Records & Payment History</h3>
                </div>
                <span class="profile-section-note">{{ $feeRecords->count() }} Fee Records</span>
            </div>

            @forelse($feeRecords as $fee)
                <div class="profile-record-card">
                    <h4>{{ $fee->feeStructure?->structure_name ?? 'Fee Record' }}</h4>
                    <div class="profile-record-grid">
                        <div><span>Academic Year</span><strong>{{ $fee->academic_year ?: '—' }}</strong></div>
                        <div><span>Total Amount</span><strong>{{ number_format((float) $fee->total_amount, 2) }}</strong></div>
                        <div><span>Discount</span><strong>{{ number_format((float) $fee->discount_amount, 2) }}</strong></div>
                        <div><span>Fine</span><strong>{{ number_format((float) $fee->fine_amount, 2) }}</strong></div>
                        <div><span>Paid Amount</span><strong>{{ number_format((float) $fee->paid_amount, 2) }}</strong></div>
                        <div><span>Balance Amount</span><strong>{{ number_format((float) $fee->balance_amount, 2) }}</strong></div>
                        <div><span>Fee Status</span><strong>{{ ucfirst($fee->status ?? '—') }}</strong></div>
                        <div><span>Due Date</span><strong>{{ $fee->due_date?->format('d M Y') ?? '—' }}</strong></div>
                    </div>

                    @if($fee->payments->isNotEmpty())
                        <h5 class="profile-subheading">Payment History</h5>
                        <div class="table-responsive">
                            <table class="table profile-table">
                                <thead>
                                    <tr>
                                        <th>Receipt No.</th>
                                        <th>Payment Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Transaction Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fee->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->receipt_number ?: '—' }}</td>
                                            <td>{{ $payment->payment_date?->format('d M Y') ?? '—' }}</td>
                                            <td>{{ number_format((float) $payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_method ?: '—' }}</td>
                                            <td>{{ $payment->transaction_reference ?: '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="profile-muted">No payment history available for this fee record.</p>
                    @endif
                </div>
            @empty
                <div class="profile-empty">No fee records found for this student.</div>
            @endforelse
        </div>

        {{-- SPORTS --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">EXTRACURRICULAR ACTIVITIES</span>
                    <h3>Sports Achievements</h3>
                </div>
                <span class="profile-section-note">{{ $sportsAchievements->count() }} Records</span>
            </div>

            @forelse($sportsAchievements as $achievement)
                <div class="profile-record-card">
                    <div class="profile-record-grid">
                        <div><span>Achievement</span><strong>{{ $achievement->title ?: '—' }}</strong></div>
                        <div><span>Sport</span><strong>{{ $achievement->sport_name ?: '—' }}</strong></div>
                        <div><span>Achievement Type</span><strong>{{ $achievement->achievement_type ?: '—' }}</strong></div>
                        <div><span>Position</span><strong>{{ $achievement->position ?: '—' }}</strong></div>
                        <div><span>Competition</span><strong>{{ $achievement->competition_name ?: '—' }}</strong></div>
                        <div><span>Academic Year</span><strong>{{ $achievement->academic_year ?: '—' }}</strong></div>
                        <div><span>Date</span><strong>{{ $achievement->achievement_date?->format('d M Y') ?? '—' }}</strong></div>
                        <div><span>Venue</span><strong>{{ $achievement->venue ?: '—' }}</strong></div>
                        <div class="profile-detail-wide"><span>Description</span><strong>{{ $achievement->description ?: '—' }}</strong></div>
                    </div>
                </div>
            @empty
                <div class="profile-empty">No sports achievements found.</div>
            @endforelse
        </div>

        {{-- LIBRARY --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">LIBRARY ACTIVITY</span>
                    <h3>Library Issue & Return History</h3>
                </div>
                <span class="profile-section-note">{{ $libraryRecords->count() }} Records</span>
            </div>

            @if($libraryRecords->isNotEmpty())
                <div class="table-responsive">
                    <table class="table profile-table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Fine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($libraryRecords as $record)
                                <tr>
                                    <td>{{ $record->book?->title ?? $record->book?->book_name ?? '—' }}</td>
                                    <td>{{ $record->issue_date?->format('d M Y') ?? '—' }}</td>
                                    <td>{{ $record->due_date?->format('d M Y') ?? '—' }}</td>
                                    <td>{{ $record->return_date?->format('d M Y') ?? 'Not returned' }}</td>
                                    <td>{{ ucfirst($record->status ?? '—') }}</td>
                                    <td>{{ number_format((float) $record->fine, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="profile-empty">No library issue records found.</div>
            @endif
        </div>

        {{-- ATTENDANCE HISTORY --}}
        <div class="profile-section">
            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">DAILY RECORDS</span>
                    <h3>Attendance History</h3>
                </div>
                <span class="profile-section-note">{{ $attendanceRecords->count() }} Records</span>
            </div>

            @if($attendanceRecords->isNotEmpty())
                <div class="table-responsive">
                    <table class="table profile-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceRecords as $attendance)
                                <tr>
                                    <td>{{ $attendance->attendance_date?->format('d M Y') ?? '—' }}</td>
                                    <td>{{ $attendance->class ?: '—' }}</td>
                                    <td>{{ $attendance->section ?: '—' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $attendance->status ?? '—')) }}</td>
                                    <td>{{ $attendance->remarks ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="profile-empty">No attendance history found.</div>
            @endif
        </div>

        <div class="profile-footer">
            <span>{{ $schoolSetting->school_name ?? config('app.name') }} · Student Profile Report</span>
            <span>Generated on {{ now()->format('d M Y') }}</span>
        </div>

    </div>
</div>

<style>
.student-profile-report-page {
    background: #f5f4f0;
    color: #1e2a25;
    min-height: calc(100vh - 100px);
    padding: 1px 0 24px;
    font-family: 'Public Sans', 'Inter', sans-serif;
}
.student-profile-report-page .profile-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}
.student-profile-report-page .profile-back {
    color: #2c3e36;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}
.student-profile-report-page .profile-action {
    display: inline-block;
    border: 0;
    border-radius: 9px;
    padding: 11px 17px;
    background: #2c3e36;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
}
.student-profile-report-page .profile-action:hover {
    background: #3a4f45;
    color: #fff;
}
.student-profile-report-page .profile-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 25px;
    padding: 28px;
    border: 1px solid #d4cfc2;
    border-radius: 18px;
    background: linear-gradient(110deg, #fdfbf7 0%, #f8f1e3 58%, #e8dcc6 100%);
}
.student-profile-report-page .profile-hero-main {
    display: flex;
    align-items: center;
    gap: 20px;
    min-width: 0;
}
.student-profile-report-page .profile-avatar {
    width: 92px;
    height: 92px;
    flex: 0 0 92px;
    display: grid;
    place-items: center;
    overflow: hidden;
    border: 3px solid #fff;
    border-radius: 22px;
    background: #f3ead8;
    color: #2c3e36;
    font-family: Georgia, serif;
    font-size: 38px;
    font-weight: 700;
}
.student-profile-report-page .profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.student-profile-report-page .profile-eyebrow {
    color: #5c6b64;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
}
.student-profile-report-page .profile-hero h2 {
    margin: 7px 0;
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: clamp(23px, 3vw, 32px);
    font-weight: 700;
}
.student-profile-report-page .profile-hero p {
    margin: 0 0 9px;
    color: #5c6b64;
    font-size: 13px;
}
.student-profile-report-page .profile-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    background: #f3ead8;
    color: #2c3e36;
    font-size: 11px;
    font-weight: 800;
}
.student-profile-report-page .profile-hero-meta {
    display: grid;
    gap: 13px;
    min-width: 175px;
}
.student-profile-report-page .profile-hero-meta div,
.student-profile-report-page .profile-details-grid div,
.student-profile-report-page .profile-record-grid div {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}
.student-profile-report-page .profile-hero-meta span,
.student-profile-report-page .profile-details-grid span,
.student-profile-report-page .profile-record-grid span {
    color: #7b857b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.student-profile-report-page .profile-hero-meta strong,
.student-profile-report-page .profile-details-grid strong,
.student-profile-report-page .profile-record-grid strong {
    color: #1e2a25;
    font-size: 13px;
    font-weight: 700;
    overflow-wrap: anywhere;
}
.student-profile-report-page .profile-section {
    margin-bottom: 20px;
    padding: 23px;
    border: 1px solid #d4cfc2;
    border-radius: 15px;
    background: #fff;
}
.student-profile-report-page .profile-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}
.student-profile-report-page .profile-section-heading h3 {
    margin: 5px 0 0;
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 21px;
    font-weight: 700;
}
.student-profile-report-page .profile-section-note {
    color: #7b857b;
    font-size: 12px;
    font-weight: 700;
}
.student-profile-report-page .profile-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
}
.student-profile-report-page .profile-stat {
    padding: 15px;
    border: 1px solid #eeeae1;
    border-radius: 11px;
    background: #fdfbf7;
}
.student-profile-report-page .profile-stat span {
    display: block;
    margin-bottom: 8px;
    color: #7b857b;
    font-size: 11px;
    font-weight: 700;
}
.student-profile-report-page .profile-stat strong {
    color: #1a6b3c;
    font-size: 22px;
    font-weight: 800;
}
.student-profile-report-page .profile-details-grid,
.student-profile-report-page .profile-record-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 19px 22px;
}
.student-profile-report-page .profile-detail-wide {
    grid-column: 1 / -1;
}
.student-profile-report-page .profile-record-card {
    padding: 18px;
    margin-bottom: 13px;
    border: 1px solid #eeeae1;
    border-radius: 11px;
    background: #fdfbf7;
}
.student-profile-report-page .profile-record-card:last-child {
    margin-bottom: 0;
}
.student-profile-report-page .profile-record-card h4 {
    margin: 0 0 17px;
    color: #2c3e36;
    font-family: Georgia, serif;
    font-size: 17px;
    font-weight: 700;
}
.student-profile-report-page .profile-subheading {
    margin: 20px 0 10px;
    color: #2c3e36;
    font-size: 13px;
    font-weight: 800;
}
.student-profile-report-page .profile-table {
    margin: 12px 0 0;
    vertical-align: middle;
}
.student-profile-report-page .profile-table thead th {
    padding: 12px;
    border-bottom: 1px solid #d4cfc2;
    background: #f5f4f0;
    color: #39483e;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
}
.student-profile-report-page .profile-table tbody td {
    padding: 12px;
    border-bottom: 1px solid #eeeae1;
    color: #39483e;
    font-size: 12px;
}
.student-profile-report-page .profile-empty {
    padding: 20px;
    border-radius: 9px;
    background: #f5f4f0;
    color: #7b857b;
    font-size: 13px;
    text-align: center;
}
.student-profile-report-page .profile-muted,
.student-profile-report-page .profile-disclaimer {
    margin: 12px 0 0;
    color: #7b857b;
    font-size: 11px;
}
.student-profile-report-page .profile-footer {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 5px 3px 0;
    color: #7b857b;
    font-size: 11px;
}
.student-profile-report-page .profile-print-header {
    display: none;
}
@media(max-width: 1100px) {
    .student-profile-report-page .profile-stats-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}
@media(max-width: 767px) {
    .student-profile-report-page .profile-toolbar,
    .student-profile-report-page .profile-hero {
        align-items: flex-start;
        flex-direction: column;
    }
    .student-profile-report-page .profile-hero-main {
        align-items: flex-start;
    }
    .student-profile-report-page .profile-hero-meta {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        width: 100%;
    }
    .student-profile-report-page .profile-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .student-profile-report-page .profile-details-grid,
    .student-profile-report-page .profile-record-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .student-profile-report-page .profile-section {
        padding: 17px;
    }
    .student-profile-report-page .profile-footer {
        flex-direction: column;
    }
}
@media print {
    @page {
        size: A4 portrait;
        margin: 12mm 12mm 14mm;
    }

    html,
    body {
        background: #fff !important;
        color: #1f2d26 !important;
        font-family: "DejaVu Sans", Arial, Helvetica, sans-serif !important;
        font-size: 9px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden !important;
    }

    .student-profile-report-page,
    .student-profile-report-page * {
        visibility: visible !important;
    }

    .student-profile-report-page {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #fff !important;
        color: #1f2d26 !important;
        font-family: "DejaVu Sans", Arial, Helvetica, sans-serif !important;
        font-size: 9px !important;
    }

    .student-profile-report-page .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .student-profile-report-page .profile-toolbar,
    .student-profile-report-page .profile-footer {
        display: none !important;
    }

    /* ========== PROFESSIONAL CENTERED LETTERHEAD ========== */
    .student-profile-report-page .profile-print-header {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        gap: 0 !important;
        min-height: auto !important;
        padding: 0 0 14px !important;
        margin: 0 0 16px !important;
        border-bottom: 2.5px solid #2c3e36 !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .student-profile-report-page .profile-print-logo-wrap {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        margin: 0 0 8px !important;
    }

    .student-profile-report-page .profile-print-header img {
        display: block !important;
        width: 72px !important;
        height: 72px !important;
        object-fit: contain !important;
        margin: 0 auto !important;
    }

    .student-profile-report-page .profile-print-school {
        flex: none !important;
        width: 100% !important;
        text-align: center !important;
    }

    .student-profile-report-page .profile-print-school h1 {
        margin: 0 0 4px !important;
        color: #1a2e25 !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 20px !important;
        font-weight: 700 !important;
        letter-spacing: 0.4px !important;
        line-height: 1.25 !important;
    }

    .student-profile-report-page .profile-print-school h2 {
        margin: 8px 0 0 !important;
        padding-top: 7px !important;
        border-top: 1px solid #c5cdc6 !important;
        color: #2c3e36 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 1.2px !important;
        text-transform: uppercase !important;
    }

    .student-profile-report-page .profile-print-school p {
        margin: 2px 0 !important;
        color: #5a6b62 !important;
        font-size: 8px !important;
        line-height: 1.45 !important;
    }

    /* ========== STUDENT HERO (print) — same as PDF ========== */
    .student-profile-report-page .profile-hero {
        display: block !important;
        padding: 12px !important;
        margin: 0 0 15px !important;
        border: 1px solid #d4cfc2 !important;
        border-radius: 0 !important;
        background: #f3f0e8 !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .student-profile-report-page .profile-hero-main {
        display: block !important;
        gap: 0 !important;
    }

    .student-profile-report-page .profile-avatar {
        display: none !important;
    }

    .student-profile-report-page .profile-eyebrow {
        display: none !important;
    }

    .student-profile-report-page .profile-hero h2 {
        margin: 0 0 5px !important;
        font-size: 16px !important;
        font-family: "DejaVu Sans", Arial, Helvetica, sans-serif !important;
        font-weight: 700 !important;
        color: #20352c !important;
        line-height: 1.3 !important;
    }

    .student-profile-report-page .profile-hero p {
        margin: 3px 0 !important;
        font-size: 9px !important;
        color: #26352e !important;
        line-height: 1.45 !important;
    }

    .student-profile-report-page .profile-status {
        display: none !important;
    }

    .student-profile-report-page .profile-hero-meta {
        display: block !important;
        margin-top: 3px !important;
        min-width: 0 !important;
        gap: 0 !important;
    }

    .student-profile-report-page .profile-hero-meta div {
        display: inline !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
    }

    .student-profile-report-page .profile-hero-meta span {
        display: none !important;
    }

    .student-profile-report-page .profile-hero-meta strong {
        display: inline !important;
        font-size: 9px !important;
        font-weight: 400 !important;
        color: #26352e !important;
    }

    .student-profile-report-page .profile-hero-meta div:not(:last-child) strong::after {
        content: " | ";
        color: #68756e !important;
        font-weight: 400 !important;
    }

    /* ========== SECTIONS ========== */
    .student-profile-report-page .profile-section {
        display: block !important;
        width: 100% !important;
        margin: 0 0 12px !important;
        padding: 0 !important;
        border: 1px solid #cfd6d0 !important;
        border-radius: 4px !important;
        background: #fff !important;
        box-shadow: none !important;
        break-inside: auto !important;
        page-break-inside: auto !important;
        overflow: hidden !important;
    }

    .student-profile-report-page .profile-section-heading {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        gap: 10px !important;
        margin: 0 !important;
        padding: 8px 12px !important;
        background: #2c3e36 !important;
        border-bottom: 2px solid #b89b5e !important;
        color: #fff !important;
        break-after: avoid !important;
        page-break-after: avoid !important;
    }

    .student-profile-report-page .profile-section-heading h3 {
        margin: 0 !important;
        color: #fff !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px !important;
    }

    .student-profile-report-page .profile-section-heading span,
    .student-profile-report-page .profile-section-heading .profile-eyebrow,
    .student-profile-report-page .profile-section-note {
        margin: 0 !important;
        color: #f0ebe0 !important;
        font-family: "DejaVu Sans", Arial, sans-serif !important;
        font-size: 7.5px !important;
        font-weight: 600 !important;
        letter-spacing: 0.4px !important;
    }

    /* ========== STATS / DETAILS GRIDS ========== */
    .student-profile-report-page .profile-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        gap: 0 !important;
        padding: 0 !important;
    }

    .student-profile-report-page .profile-details-grid,
    .student-profile-report-page .profile-record-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 0 !important;
        padding: 0 !important;
    }

    .student-profile-report-page .profile-stats-grid > div,
    .student-profile-report-page .profile-details-grid > div,
    .student-profile-report-page .profile-record-grid > div {
        display: block !important;
        min-width: 0 !important;
        padding: 7px 10px !important;
        border: 0 !important;
        border-right: 1px solid #e0e6e1 !important;
        border-bottom: 1px solid #e0e6e1 !important;
        border-radius: 0 !important;
        background: #fff !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .student-profile-report-page .profile-stats-grid > div:nth-child(even),
    .student-profile-report-page .profile-details-grid > div:nth-child(even),
    .student-profile-report-page .profile-record-grid > div:nth-child(even) {
        background: #f6f8f5 !important;
    }

    .student-profile-report-page .profile-details-grid span,
    .student-profile-report-page .profile-record-grid span,
    .student-profile-report-page .profile-stats-grid span {
        display: block !important;
        margin: 0 0 3px !important;
        color: #5f6f66 !important;
        font-size: 7px !important;
        font-weight: 700 !important;
        letter-spacing: 0.35px !important;
        text-transform: uppercase !important;
    }

    .student-profile-report-page .profile-details-grid strong,
    .student-profile-report-page .profile-record-grid strong,
    .student-profile-report-page .profile-stats-grid strong {
        display: block !important;
        color: #1f2d26 !important;
        font-size: 9px !important;
        font-weight: 600 !important;
        line-height: 1.4 !important;
        overflow-wrap: anywhere !important;
    }

    .student-profile-report-page .profile-stats-grid strong {
        color: #1a6b3c !important;
        font-size: 13px !important;
        font-weight: 700 !important;
    }

    .student-profile-report-page .profile-detail-wide {
        grid-column: 1 / -1 !important;
    }

    /* ========== RECORD CARDS ========== */
    .student-profile-report-page .profile-record-card {
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        border-bottom: 1px solid #d8ded8 !important;
        border-radius: 0 !important;
        background: #fff !important;
        box-shadow: none !important;
        break-inside: auto !important;
        page-break-inside: auto !important;
    }

    .student-profile-report-page .profile-record-card:last-child {
        border-bottom: 0 !important;
    }

    .student-profile-report-page .profile-record-card h4,
    .student-profile-report-page .profile-subheading {
        margin: 0 !important;
        padding: 7px 10px !important;
        background: #e9efea !important;
        border-bottom: 1px solid #d0d8d1 !important;
        color: #2c3e36 !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 9.5px !important;
        font-weight: 700 !important;
        break-after: avoid !important;
        page-break-after: avoid !important;
    }

    /* ========== TABLES ========== */
    .student-profile-report-page .table-responsive {
        overflow: visible !important;
    }

    .student-profile-report-page .profile-table {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        font-size: 8px !important;
    }

    .student-profile-report-page .profile-table thead {
        display: table-header-group !important;
    }

    .student-profile-report-page .profile-table tfoot {
        display: table-footer-group !important;
    }

    .student-profile-report-page .profile-table tr {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .student-profile-report-page .profile-table th,
    .student-profile-report-page .profile-table td {
        padding: 6px 8px !important;
        border: 1px solid #d0d8d1 !important;
        color: #1f2d26 !important;
        font-size: 8px !important;
        line-height: 1.4 !important;
        text-align: left !important;
        vertical-align: top !important;
    }

    .student-profile-report-page .profile-table thead th {
        background: #e4ebe5 !important;
        color: #1f2d26 !important;
        font-size: 7.5px !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px !important;
        text-transform: uppercase !important;
    }

    .student-profile-report-page .profile-table tbody tr:nth-child(even) td {
        background: #f6f8f5 !important;
    }

    /* ========== NOTES / EMPTY ========== */
    .student-profile-report-page .profile-disclaimer,
    .student-profile-report-page .profile-empty,
    .student-profile-report-page .profile-muted {
        padding: 7px 10px !important;
        margin: 0 !important;
        color: #5f6f66 !important;
        font-size: 8px !important;
        line-height: 1.45 !important;
    }

    /* ========== PRINT COLOR ACCURACY ========== */
    .student-profile-report-page .profile-section,
    .student-profile-report-page .profile-table th,
    .student-profile-report-page .profile-table td,
    .student-profile-report-page .profile-record-card,
    .student-profile-report-page .profile-section-heading,
    .student-profile-report-page .profile-hero {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .student-profile-report-page a {
        color: #1f2d26 !important;
        text-decoration: none !important;
    }
}
</style>
@endsection