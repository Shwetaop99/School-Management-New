```
@extends('layouts.app')

@section('title', 'Student Transport Profile')

@section('content')
@php
    $schoolName = $school?->school_name ?: config('app.name');
    $schoolLogo = $school?->logo_url;

    $studentName = $student->full_name ?: trim(collect([
        $student->first_name ?? null,
        $student->middle_name ?? null,
        $student->last_name ?? null,
    ])->filter()->implode(' '));

    $transportRecords = $student->transportRecords ?? collect();

    $formatDate = static function ($value) {
        return $value ? \Illuminate\Support\Carbon::parse($value)->format('d M Y') : '—';
    };

    $formatTime = static function ($value) {
        return $value ? \Illuminate\Support\Carbon::parse($value)->format('h:i A') : '—';
    };

    $studentDob = $student->dob ?? $student->date_of_birth ?? null;

    // Rows of the vertical print table (label => value resolver)
    $verticalRows = [
        'status'        => ['Status',           fn ($r) => ucfirst($r->transport_status ?? '—')],
        'route'         => ['Route',            fn ($r) => $r->route ?? '—'],
        'vehicle'       => ['Vehicle',          fn ($r) => $r->vehicle ?? '—'],
        'type'          => ['Transport Type',   fn ($r) => ucfirst($r->transport_type ?? '—')],
        'pickup_point'  => ['Pickup Point',     fn ($r) => $r->pickup_point ?? '—'],
        'drop_point'    => ['Drop Point',       fn ($r) => $r->drop_point ?? '—'],
        'pickup_time'   => ['Pickup Time',      fn ($r) => $formatTime($r->pickup_time)],
        'drop_time'     => ['Drop Time',        fn ($r) => $formatTime($r->drop_time)],
        'start_date'    => ['Start Date',       fn ($r) => $formatDate($r->start_date)],
        'end_date'      => ['End Date',         fn ($r) => $formatDate($r->end_date)],
        'fee'           => ['Transport Fee',    fn ($r) => $r->transport_fee !== null ? '₹' . number_format((float) $r->transport_fee, 2) : '—'],
        'fee_frequency' => ['Fee Frequency',    fn ($r) => ucfirst($r->fee_frequency ?? '—')],
        'payment'       => ['Payment Status',   fn ($r) => ucfirst(str_replace('_', ' ', $r->payment_status ?? 'Pending'))],
        'remarks'       => ['Remarks',          fn ($r) => $r->remarks ?: '—'],
    ];

    $recordsPerTable = 4;
@endphp

<div class="student-transport-profile">
    <div class="container-fluid py-4">

        {{-- TOOLBAR: BACK (LEFT) + PRINT / PDF / EXCEL (RIGHT) --}}
        <div class="profile-toolbar screen-only">
            <a href="{{ route('admin.reports.transport.students.class', [
                'class' => $student->class,
                'section' => $student->section,
                'academic_year' => $student->academic_year,
            ]) }}" class="profile-btn back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Reports</span>
            </a>

            <div class="topbar-actions">
                <button type="button" onclick="window.print()" class="profile-btn action-btn">
                    <i class="fas fa-print"></i> Print
                </button>

                <a href="{{ route('admin.reports.transport.students.pdf', $student->id) }}"
                   class="profile-btn action-btn">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>

                <a href="{{ route('admin.reports.transport.students.excel', $student->id) }}"
                   class="profile-btn action-btn">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>

        {{-- PRINT HEADER --}}
        <header class="print-school-header print-only">
            <div class="print-brand">
                @if($schoolLogo)
                    <img src="{{ $schoolLogo }}" alt="School Logo" class="print-school-logo">
                @endif

                <div class="print-school-details">
                    <h1>{{ $schoolName }}</h1>

                    @if($school?->address)
                        <p>{{ $school->address }}</p>
                    @endif

                    <p class="print-contact">
                        @if($school?->phone)
                            <span>Phone: {{ $school->phone }}</span>
                        @endif

                        @if($school?->email)
                            <span>Email: {{ $school->email }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="print-report-heading">
                <h2>STUDENT TRANSPORT PROFILE</h2>
                <p>Individual Student Report</p>
            </div>

            <div class="print-generated">
                <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
                <span>Student ID: {{ $student->student_id ?? '—' }}</span>
            </div>
        </header>

        {{-- STUDENT HERO (PEARL) --}}
        <section class="student-hero screen-only">
            <div class="student-avatar">
                {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
            </div>

            <div class="student-hero-info">
                <span class="eyebrow">STUDENT TRANSPORT RECORD</span>
                <h1>{{ $studentName ?: 'Student' }}</h1>

                <div class="student-meta">
                    <span><i class="fas fa-id-card"></i> {{ $student->student_id ?? 'No Student ID' }}</span>
                    <span><i class="fas fa-graduation-cap"></i> Class {{ $student->class ?? '—' }}</span>
                    <span><i class="fas fa-users"></i> Section {{ $student->section ?? '—' }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ $student->academic_year ?? '—' }}</span>
                </div>
            </div>

            <div class="hero-record-count">
                <strong>{{ $transportRecords->count() }}</strong>
                <span>Transport Records</span>
            </div>
        </section>

        {{-- STUDENT INFORMATION --}}
        <section class="profile-card screen-only">
            <div class="card-heading">
                <div class="heading-icon"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <h2>Student Information</h2>
                    <p>Personal and academic details</p>
                </div>
            </div>

            <div class="details-grid">
                <div class="detail-item"><span>Student Name</span><strong>{{ $studentName ?: '—' }}</strong></div>
                <div class="detail-item"><span>Student ID</span><strong>{{ $student->student_id ?? '—' }}</strong></div>
                <div class="detail-item"><span>Roll Number</span><strong>{{ $student->roll_number ?? '—' }}</strong></div>
                <div class="detail-item"><span>Class</span><strong>{{ $student->class ?? '—' }}</strong></div>
                <div class="detail-item"><span>Section</span><strong>{{ $student->section ?? '—' }}</strong></div>
                <div class="detail-item"><span>Academic Year</span><strong>{{ $student->academic_year ?? '—' }}</strong></div>
                <div class="detail-item"><span>Gender</span><strong>{{ $student->gender ?? '—' }}</strong></div>
                <div class="detail-item"><span>Date of Birth</span><strong>{{ $formatDate($studentDob) }}</strong></div>
                <div class="detail-item"><span>Student Phone</span><strong>{{ $student->phone ?? '—' }}</strong></div>
                <div class="detail-item"><span>Email</span><strong>{{ $student->email ?? '—' }}</strong></div>
                <div class="detail-item"><span>Student Status</span><strong>{{ ucfirst($student->status ?? '—') }}</strong></div>
            </div>
        </section>

        {{-- PARENT INFORMATION (SCREEN ONLY) --}}
        <section class="profile-card screen-only">
            <div class="card-heading">
                <div class="heading-icon parent-icon"><i class="fas fa-users"></i></div>
                <div>
                    <h2>Parent / Guardian Information</h2>
                    <p>Registered parent and contact details</p>
                </div>
            </div>

            <div class="details-grid">
                <div class="detail-item"><span>Father's Name</span><strong>{{ $student->father_name ?? '—' }}</strong></div>
                <div class="detail-item"><span>Father's Phone</span><strong>{{ $student->father_phone ?? '—' }}</strong></div>
                <div class="detail-item"><span>Mother's Name</span><strong>{{ $student->mother_name ?? '—' }}</strong></div>
                <div class="detail-item"><span>Mother's Phone</span><strong>{{ $student->mother_phone ?? '—' }}</strong></div>
                <div class="detail-item full-width"><span>Address</span><strong>{{ $student->address ?? '—' }}</strong></div>
            </div>
        </section>

        {{-- TRANSPORT HISTORY (SCREEN) --}}
        <section class="transport-section screen-only">
            <div class="section-title">
                <div>
                    <span class="eyebrow dark-eyebrow">TRANSPORT MANAGEMENT</span>
                    <h2>Transport Details &amp; History</h2>
                    <p>All transport records registered for this student.</p>
                </div>
                <div class="transport-icon"><i class="fas fa-bus"></i></div>
            </div>

            @forelse($transportRecords as $index => $record)
                <article class="transport-record-card">
                    <div class="record-heading">
                        <div>
                            <span class="record-number">TRANSPORT RECORD {{ $index + 1 }}</span>
                            <h3>{{ $record->route ?: 'Route Not Assigned' }}</h3>
                        </div>

                        <span class="status-badge {{ strtolower($record->transport_status ?? '') === 'active' ? 'active' : 'inactive' }}">
                            {{ ucfirst($record->transport_status ?? 'Unknown') }}
                        </span>
                    </div>

                    <div class="transport-details-grid">
                        <div class="transport-detail"><span><i class="fas fa-route"></i> Route</span><strong>{{ $record->route ?? '—' }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-bus-alt"></i> Vehicle</span><strong>{{ $record->vehicle ?? '—' }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-map-marker-alt"></i> Pickup Point</span><strong>{{ $record->pickup_point ?? '—' }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-map-marker-alt"></i> Drop Point</span><strong>{{ $record->drop_point ?? '—' }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-clock"></i> Pickup Time</span><strong>{{ $formatTime($record->pickup_time) }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-clock"></i> Drop Time</span><strong>{{ $formatTime($record->drop_time) }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-car-side"></i> Transport Type</span><strong>{{ $record->transport_type ?? '—' }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-calendar-check"></i> Start Date</span><strong>{{ $formatDate($record->start_date) }}</strong></div>
                        <div class="transport-detail"><span><i class="fas fa-calendar-times"></i> End Date</span><strong>{{ $formatDate($record->end_date) }}</strong></div>
                    </div>

                    <div class="fee-panel">
                        <div class="fee-item">
                            <span>Transport Fee</span>
                            <strong>{{ $record->transport_fee !== null ? '₹' . number_format((float) $record->transport_fee, 2) : '—' }}</strong>
                        </div>
                        <div class="fee-item">
                            <span>Fee Frequency</span>
                            <strong>{{ ucfirst($record->fee_frequency ?? '—') }}</strong>
                        </div>
                        <div class="fee-item">
                            <span>Payment Status</span>
                            <strong class="payment-status {{ strtolower($record->payment_status ?? 'pending') }}">
                                {{ ucfirst($record->payment_status ?? 'Pending') }}
                            </strong>
                        </div>
                    </div>

                    @if($record->remarks)
                        <div class="remarks-box">
                            <strong><i class="fas fa-sticky-note"></i> Remarks</strong>
                            <p>{{ $record->remarks }}</p>
                        </div>
                    @endif
                </article>
            @empty
                <div class="empty-transport">
                    <i class="fas fa-bus"></i>
                    <h3>No Transport Records</h3>
                    <p>No transport records are currently registered for this student.</p>
                </div>
            @endforelse
        </section>

        {{-- PRINT REPORT --}}
        <main class="print-report print-only">

            <h3 class="print-section-title">1. Student Information</h3>
            <table class="print-table">
                <tbody>
                    <tr>
                        <th>Student Name</th><td>{{ $studentName ?: '—' }}</td>
                        <th>Student ID</th><td>{{ $student->student_id ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Roll Number</th><td>{{ $student->roll_number ?? '—' }}</td>
                        <th>Class</th><td>{{ $student->class ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Section</th><td>{{ $student->section ?? '—' }}</td>
                        <th>Academic Year</th><td>{{ $student->academic_year ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th><td>{{ $student->gender ?? '—' }}</td>
                        <th>Date of Birth</th><td>{{ $formatDate($studentDob) }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th><td>{{ $student->phone ?? '—' }}</td>
                        <th>Email</th><td>{{ $student->email ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Student Status</th><td colspan="3">{{ ucfirst($student->status ?? '—') }}</td>
                    </tr>
                </tbody>
            </table>

            <h3 class="print-section-title">2. Transport Details &amp; History</h3>

            @if($transportRecords->isNotEmpty())
                @foreach($transportRecords->values()->chunk($recordsPerTable) as $chunkIndex => $chunk)
                    <table class="vertical-table">
                        <thead>
                            <tr>
                                <th class="vt-corner">Details</th>
                                @foreach($chunk as $i => $record)
                                    <th class="vt-head">Record {{ ($chunkIndex * $recordsPerTable) + $loop->iteration }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($verticalRows as $key => [$label, $resolver])
                                <tr>
                                    <th class="vt-label">{{ $label }}</th>
                                    @foreach($chunk as $record)
                                        <td>
                                            @if($key === 'status')
                                                @php $isActive = strtolower($record->transport_status ?? '') === 'active'; @endphp
                                                <span class="vt-chip {{ $isActive ? 'vt-active' : 'vt-inactive' }}">{{ $resolver($record) }}</span>
                                            @elseif($key === 'fee')
                                                <strong>{{ $resolver($record) }}</strong>
                                            @else
                                                {{ $resolver($record) }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @else
                <p class="print-empty">No transport records found for this student.</p>
            @endif

            <div class="print-signatures">
                <div>Parent / Guardian Signature</div>
                <div>Transport In-charge Signature</div>
                <div>Principal Signature</div>
            </div>

            <footer class="print-footer">
                <span>{{ $schoolName }}</span>
                <span>Student Transport Report | {{ now()->format('d M Y') }}</span>
            </footer>
        </main>

        {{-- SCREEN FOOTER --}}
        <div class="profile-footer screen-only">
            <span>Student Transport Report</span>
            <span>{{ $transportRecords->count() }} transport record(s)</span>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* =========================================================
   PEARL THEME - STUDENT TRANSPORT PROFILE
========================================================= */

.student-transport-profile {
    --page: #fffdf7;
    --paper: #fffefa;
    --pearl: #f8f6ef;
    --ink: #263b35;
    --dark: #1d302b;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --sage: #527f70;
    --cream: #f1ead8;

    min-height: 100vh;
    background: var(--page);
    color: var(--text);
    font-family: 'Public Sans', 'Inter', sans-serif;
}

.student-transport-profile .container-fluid {
    max-width: 1650px;
    margin: auto;
}

/* TOOLBAR: BACK LEFT, ACTIONS RIGHT */

.student-transport-profile .profile-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 20px;
}

.student-transport-profile .profile-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 47px;
    padding: 12px 20px;
    border: 1px solid #d9d5c9;
    border-radius: 10px;
    background: #fffefa;
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: all .2s ease;
}

.student-transport-profile .profile-btn:hover {
    background: #f1eee5;
    border-color: #b9c4b9;
    color: var(--dark);
    transform: translateY(-1px);
}

.student-transport-profile .back-btn {
    min-height: 46px;
    padding: 11px 19px;
}

.student-transport-profile .topbar-actions {
    display: flex;
    gap: 11px;
    flex-wrap: wrap;
    margin-left: auto;
    justify-content: flex-end;
}

.student-transport-profile .action-btn {
    min-height: 46px;
    padding: 12px 21px;
}

/* STUDENT HERO - PEARL */

.student-transport-profile .student-hero {
    display: flex;
    align-items: center;
    gap: 23px;
    padding: 30px;
    margin-bottom: 22px;
    color: var(--ink);
    background: linear-gradient(120deg, #fffefa 0%, #f8f6ef 45%, #f1ead8 100%);
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: 0 6px 22px rgba(239, 228, 143, 0.06);
}

.student-transport-profile .student-avatar {
    width: 86px;
    height: 86px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    border: 3px solid #ffffff;
    border-radius: 20px;
    background: var(--sage);
    box-shadow: 0 4px 14px rgba(82, 127, 112, .3);
    font: 700 38px Georgia, serif;
    color: #fff;
}

.student-transport-profile .student-hero-info {
    flex: 1;
    min-width: 0;
}

.student-transport-profile .eyebrow {
    font-size: 10px;
    letter-spacing: 2px;
    font-weight: 800;
    color: var(--sage);
}

.student-transport-profile .student-hero h1 {
    margin: 8px 0 12px;
    color: var(--ink);
    font: 700 30px Georgia, serif;
}

.student-transport-profile .student-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px 20px;
}

.student-transport-profile .student-meta span {
    color: var(--text);
    font-size: 12px;
    font-weight: 600;
}

.student-transport-profile .student-meta i {
    margin-right: 5px;
    color: var(--sage);
}

.student-transport-profile .hero-record-count {
    min-width: 130px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: #fffefa;
}

.student-transport-profile .hero-record-count strong {
    color: var(--ink);
    font-size: 29px;
}

.student-transport-profile .hero-record-count span {
    color: var(--muted);
    font-size: 11px;
    text-align: center;
}

/* INFORMATION CARDS */

.student-transport-profile .profile-card {
    padding: 26px;
    margin-bottom: 22px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 15px;
}

.student-transport-profile .card-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    padding-bottom: 17px;
    margin-bottom: 21px;
    border-bottom: 1px solid var(--line);
}

.student-transport-profile .heading-icon {
    width: 45px;
    height: 45px;
    display: grid;
    place-items: center;
    border-radius: 11px;
    background: #f1eee5;
    color: var(--sage);
    font-size: 18px;
}

.student-transport-profile .parent-icon {
    background: #f1eee5;
    color: var(--ink);
}

.student-transport-profile .card-heading h2,
.student-transport-profile .section-title h2 {
    margin: 0 0 4px;
    color: var(--ink);
    font-size: 19px;
    font-weight: 800;
}

.student-transport-profile .card-heading p,
.student-transport-profile .section-title p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
}

.student-transport-profile .details-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 21px 27px;
}

.student-transport-profile .detail-item {
    min-width: 0;
}

.student-transport-profile .detail-item span {
    display: block;
    margin-bottom: 7px;
    color: var(--muted);
    font-size: 11px;
}

.student-transport-profile .detail-item strong {
    display: block;
    color: var(--ink);
    font-size: 13px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.student-transport-profile .full-width {
    grid-column: 1 / -1;
}

/* TRANSPORT SECTION */

.student-transport-profile .transport-section {
    margin-top: 28px;
}

.student-transport-profile .section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
}

.student-transport-profile .dark-eyebrow {
    display: block;
    margin-bottom: 7px;
    color: var(--sage);
    opacity: 1;
}

.student-transport-profile .section-title h2 {
    font: 700 25px Georgia, serif;
}

.student-transport-profile .transport-icon {
    width: 58px;
    height: 58px;
    display: grid;
    place-items: center;
    border-radius: 13px;
    background: #f1eee5;
    color: var(--sage);
    font-size: 24px;
}

.student-transport-profile .transport-record-card {
    padding: 25px;
    margin-bottom: 18px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 15px;
}

.student-transport-profile .record-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--line);
}

.student-transport-profile .record-number {
    color: var(--sage);
    font-size: 10px;
    letter-spacing: 1.5px;
    font-weight: 800;
}

.student-transport-profile .record-heading h3 {
    margin: 6px 0 0;
    color: var(--ink);
    font-size: 18px;
    font-weight: 800;
}

.student-transport-profile .status-badge {
    padding: 8px 14px;
    border: 1px solid var(--line);
    border-radius: 20px;
    background: #f5f3e9;
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
}

.student-transport-profile .status-badge.active {
    background: #e6f2ea;
    border-color: #bfdcca;
    color: #e7df91;
}

.student-transport-profile .status-badge.inactive {
    background: #f8e9e7;
    border-color: #e8c5c1;
    color: #b3443b;
}

.student-transport-profile .transport-details-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px 25px;
}

.student-transport-profile .transport-detail span {
    display: block;
    margin-bottom: 7px;
    color: var(--muted);
    font-size: 11px;
}

.student-transport-profile .transport-detail span i {
    width: 17px;
    color: var(--sage);
}

.student-transport-profile .transport-detail strong {
    color: var(--ink);
    font-size: 13px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

/* FEE PANEL */

.student-transport-profile .fee-panel {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
    padding: 18px;
    margin-top: 24px;
    border: 1px solid #e5e1d5;
    border-radius: 11px;
    background: #f7f5ed;
}

.student-transport-profile .fee-item span {
    display: block;
    margin-bottom: 7px;
    color: var(--muted);
    font-size: 11px;
}

.student-transport-profile .fee-item strong {
    color: var(--ink);
    font-size: 15px;
    font-weight: 800;
}

.student-transport-profile .payment-status {
    color: var(--ink);
}

/* REMARKS AND EMPTY STATE */

.student-transport-profile .remarks-box {
    padding: 15px;
    margin-top: 17px;
    border-left: 3px solid #b9ad91;
    border-radius: 6px;
    background: #f8f6ef;
}

.student-transport-profile .remarks-box strong {
    color: var(--ink);
    font-size: 12px;
}

.student-transport-profile .remarks-box p {
    margin: 8px 0 0;
    color: var(--text);
    font-size: 12px;
    white-space: pre-line;
}

.student-transport-profile .empty-transport {
    padding: 45px 20px;
    text-align: center;
    background: var(--paper);
    border: 1px dashed var(--line);
    border-radius: 14px;
}

.student-transport-profile .empty-transport i {
    color: var(--sage);
    font-size: 30px;
}

.student-transport-profile .empty-transport h3 {
    margin: 13px 0 5px;
    color: var(--ink);
    font-size: 17px;
}

.student-transport-profile .empty-transport p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
}

.student-transport-profile .profile-footer {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 5px 5px;
    color: var(--muted);
    font-size: 11px;
}

/* PRINT HIDDEN ON SCREEN */

.student-transport-profile .print-only {
    display: none;
}

/* RESPONSIVE */

@media(max-width: 950px) {
    .student-transport-profile .details-grid,
    .student-transport-profile .transport-details-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 650px) {
    .student-transport-profile .student-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .student-transport-profile .profile-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .student-transport-profile .topbar-actions {
        width: 100%;
        margin-left: 0;
    }

    .student-transport-profile .action-btn {
        flex: 1;
    }

    .student-transport-profile .student-hero h1 {
        font-size: 25px;
    }

    .student-transport-profile .hero-record-count {
        width: 100%;
        flex-direction: row;
        gap: 10px;
    }

    .student-transport-profile .details-grid,
    .student-transport-profile .transport-details-grid,
    .student-transport-profile .fee-panel {
        grid-template-columns: 1fr;
    }

    .student-transport-profile .profile-card,
    .student-transport-profile .transport-record-card {
        padding: 18px;
    }

    .student-transport-profile .profile-footer {
        flex-direction: column;
    }
}

/* =========================================================
   PRINT DESIGN
========================================================= */

@page {
    size: A4 landscape;
    margin: 22px 28px 28px 28px;
}

@media print {

    html,
    body {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden !important;
    }

    .student-transport-profile,
    .student-transport-profile .print-only,
    .student-transport-profile .print-only * {
        visibility: visible !important;
    }

    .student-transport-profile {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        color: #263b35 !important;
        font-family: 'DejaVu Sans', Arial, sans-serif !important;
    }

    .student-transport-profile .container-fluid {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .student-transport-profile .screen-only {
        display: none !important;
    }

    .student-transport-profile .print-only {
        display: block !important;
    }

    /* SCHOOL BRANDING - CENTRED */

    .student-transport-profile .print-school-header {
        display: block !important;
        width: 100%;
        margin: 0 0 13px;
        padding: 0 0 9px;
        border-bottom: 2px solid #263b35;
        text-align: center;
    }

    .student-transport-profile .print-brand {
        display: block;
        width: 100%;
        text-align: center;
    }

    .student-transport-profile .print-school-logo {
        display: block;
        width: 65px;
        height: 65px;
        margin: 0 auto 5px;
        object-fit: contain;
    }

    .student-transport-profile .print-school-details {
        text-align: center;
    }

    .student-transport-profile .print-school-details h1 {
        margin: 0;
        color: #263b35;
        font-family: Georgia, serif;
        font-size: 20px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .student-transport-profile .print-school-details p {
        margin: 3px 0;
        color: #444444;
        font-size: 8px;
        line-height: 1.4;
    }

    .student-transport-profile .print-contact {
        display: flex;
        justify-content: center;
        gap: 16px;
    }

    /* REPORT TITLE */

    .student-transport-profile .print-report-heading {
        margin-top: 9px;
        text-align: center;
    }

    .student-transport-profile .print-report-heading h2 {
        margin: 0;
        color: #263b35;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .student-transport-profile .print-report-heading p {
        margin: 3px 0 0;
        color: #666666;
        font-size: 8px;
    }

    .student-transport-profile .print-generated {
        display: flex;
        justify-content: space-between;
        margin-top: 9px;
        color: #555555;
        font-size: 8px;
    }

    /* SECTION HEADINGS */

    .student-transport-profile .print-section-title {
        margin: 12px 0 5px;
        padding: 6px 8px;
        border-left: 3px solid #527f70;
        background: #eae8df !important;
        color: #263b35;
        font-size: 10px;
        font-weight: 800;
        page-break-after: avoid;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    /* STUDENT INFO TABLE */

    .student-transport-profile .print-table {
        width: 100%;
        margin: 0 0 8px;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 8px;
        page-break-inside: auto;
    }

    .student-transport-profile .print-table tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    .student-transport-profile .print-table th,
    .student-transport-profile .print-table td {
        padding: 5px 7px;
        border: 1px solid #cfcfc7;
        text-align: left;
        vertical-align: top;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .student-transport-profile .print-table th {
        width: 16%;
        background: #f1eee5 !important;
        color: #263b35;
        font-weight: 700;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .student-transport-profile .print-table td {
        width: 34%;
        background: #ffffff !important;
        color: #292929;
    }

    /* VERTICAL TRANSPORT TABLE */

    .student-transport-profile .vertical-table {
        width: 100%;
        margin: 0 0 12px;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
        font-size: 8px;
        border: 1px solid #cfcfc7;
        border-radius: 6px;
        overflow: hidden;
        page-break-inside: avoid;
    }

    .student-transport-profile .vertical-table th,
    .student-transport-profile .vertical-table td {
        padding: 5px 9px;
        border-bottom: 1px solid #e3e0d4;
        border-right: 1px solid #e3e0d4;
        text-align: left;
        vertical-align: middle;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .student-transport-profile .vertical-table tr > *:last-child {
        border-right: 0;
    }

    .student-transport-profile .vertical-table tbody tr:last-child > * {
        border-bottom: 0;
    }

    .student-transport-profile .vertical-table .vt-corner {
        width: 17%;
        background: #263b35 !important;
        color: #ffffff;
        font-size: 8px;
        letter-spacing: 1px;
        text-transform: uppercase;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .student-transport-profile .vertical-table .vt-head {
        background: #527f70 !important;
        color: #ffffff;
        font-size: 9px;
        font-weight: 800;
        text-align: center;
        letter-spacing: .5px;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .student-transport-profile .vertical-table .vt-label {
        background: #f1ead8 !important;
        color: #263b35;
        font-weight: 800;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .student-transport-profile .vertical-table td {
        text-align: center;
        color: #292929;
        background: #ffffff !important;
    }

    .student-transport-profile .vertical-table tbody tr:nth-child(even) td {
        background: #faf8f1 !important;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .student-transport-profile .vt-chip {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 10px;
        font-size: 8px;
        font-weight: 800;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .student-transport-profile .vt-active {
        background: #e6f2ea !important;
        color: #1a6b3c;
        border: 1px solid #bfdcca;
    }

    .student-transport-profile .vt-inactive {
        background: #f8e9e7 !important;
        color: #b3443b;
        border: 1px solid #e8c5c1;
    }

    .student-transport-profile .print-empty {
        color: #555555;
        font-size: 9px;
    }

    /* SIGNATURES */

    .student-transport-profile .print-signatures {
        display: flex;
        justify-content: space-between;
        gap: 25px;
        margin-top: 32px;
        page-break-inside: avoid;
    }

    .student-transport-profile .print-signatures div {
        width: 30%;
        padding-top: 27px;
        border-top: 1px solid #555555;
        color: #333333;
        text-align: center;
        font-size: 8px;
    }

    /* FOOTER */

    .student-transport-profile .print-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 6px;
        border-top: 1px solid #cccccc;
        color: #666666;
        font-size: 8px;
    }

    a {
        text-decoration: none !important;
    }
}
</style>
@endpush 