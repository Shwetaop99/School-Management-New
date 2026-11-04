@extends('layouts.app')

@section('title', 'Faculty Reports | Admin')
@section('page-title', 'Faculty Reports')

@section('content')

@php
    $selectedSearch  = request('search');
    $selectedFrom    = request('from_date');
    $selectedTo      = request('to_date');
    $selectedGender  = request('gender');
    $selectedSubject = request('subject');
    $selectedStatus  = request('status');

    $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::query()->first();

    $schoolName = trim((string) ($schoolSetting->school_name ?? ''));
    $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';

    $schoolLogo = $schoolSetting->logo_url ?? null;

    $schoolAddress = collect([
        $schoolSetting->address ?? null,
        $schoolSetting->city ?? null,
        $schoolSetting->district ?? null,
        $schoolSetting->state ?? null,
        $schoolSetting->pincode ?? null,
    ])->filter(fn ($value) => trim((string) $value) !== '')->implode(', ');

    $schoolPhone = trim((string) ($schoolSetting->phone ?? ''));
    $schoolEmail = trim((string) ($schoolSetting->email ?? ''));
@endphp

<div class="payroll-simple faculty-reports-page">
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="faculty-report-topbar">
            <a href="{{ route('admin.reports.index') }}" class="faculty-back-link">
                <span>&larr;</span> Back to Reports
            </a>

            <button type="button" class="faculty-print-btn" onclick="printFacultyReport()">
                <span>⎙</span> Print
            </button>
        </div>

        {{-- Hero Section --}}
        <section class="faculty-report-hero">
            <div class="faculty-report-hero-content">
                <span class="faculty-report-eyebrow">FACULTY RECORDS</span>

                <h2>Faculty Reports</h2>

                <p>
                    Explore faculty profiles, attendance records, salary details
                    and complete individual teacher reports from one place.
                </p>
            </div>

            <div class="faculty-report-hero-art">
                <div class="faculty-hero-icon">▤</div>
                <span>FACULTY DIRECTORY</span>
                <strong>Teacher Overview</strong>
            </div>
        </section>

        {{-- Faculty Directory --}}
        <section class="faculty-directory-section">

            <div class="faculty-section-heading">
                <div>
                    <span class="faculty-report-eyebrow">EXPLORE RECORDS</span>
                    <h3>Select a Faculty Member</h3>
                    <p>Choose a teacher to view their complete individual report.</p>
                </div>

                <div class="faculty-record-count">
                    {{ $teachers->count() }} Faculty Members
                </div>
            </div>

            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.teachers.reports.index') }}"
                  class="faculty-filters">

                <div class="faculty-filter-item faculty-search-item">
                    <label for="faculty-search">Search Faculty</label>
                    <input
                        type="text"
                        id="faculty-search"
                        name="search"
                        value="{{ $selectedSearch }}"
                        placeholder="Name, ID, email, phone..."
                    >
                </div>

                <div class="faculty-filter-item">
                    <label for="faculty-from-date">Attendance From</label>
                    <input
                        type="date"
                        id="faculty-from-date"
                        name="from_date"
                        value="{{ $selectedFrom }}"
                    >
                </div>

                <div class="faculty-filter-item">
                    <label for="faculty-to-date">Attendance To</label>
                    <input
                        type="date"
                        id="faculty-to-date"
                        name="to_date"
                        value="{{ $selectedTo }}"
                    >
                </div>

                <div class="faculty-filter-item">
                    <label for="faculty-gender">Gender</label>
                    <select id="faculty-gender" name="gender">
                        <option value="">All Genders</option>
                        @foreach($genders ?? [] as $gender)
                            <option value="{{ $gender }}" @selected($selectedGender == $gender)>
                                {{ $gender }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="faculty-filter-item">
                    <label for="faculty-subject">Subject</label>
                    <select id="faculty-subject" name="subject">
                        <option value="">All Subjects</option>
                        @foreach($subjects ?? [] as $subject)
                            <option value="{{ $subject }}" @selected($selectedSubject == $subject)>
                                {{ $subject }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="faculty-filter-item">
                    <label for="faculty-status">Status</label>
                    <select id="faculty-status" name="status">
                        <option value="">All Status</option>
                        <option value="Active" @selected($selectedStatus === 'Active')>Active</option>
                        <option value="Inactive" @selected($selectedStatus === 'Inactive')>Inactive</option>
                    </select>
                </div>

                <div class="faculty-filter-buttons">
                    <button type="submit" class="faculty-apply-btn">Apply Filters</button>
                    <a href="{{ route('admin.teachers.reports.index') }}" class="faculty-reset-btn">
                        Reset
                    </a>
                </div>
            </form>

            {{-- Faculty Cards --}}
            @if($teachers->isNotEmpty())

                <div class="faculty-card-grid">

                    @foreach($teachers as $teacher)

                        @php
                            $teacherName = trim(
                                ($teacher->first_name ?? '') . ' ' .
                                ($teacher->last_name ?? '')
                            );

                            $teacherName = $teacherName !== '' ? $teacherName : 'Unnamed Faculty';

                            $teacherStatus = strtolower(trim((string) ($teacher->status ?? '')));
                            $statusClass = $teacherStatus === 'active' ? 'faculty-active' : 'faculty-inactive';
                        @endphp

                        <a
                            href="{{ route('admin.teachers.reports.show', $teacher->id) }}"
                            class="faculty-member-card"
                        >
                            <div class="faculty-card-icon">
                                <span>♙</span>
                            </div>

                            <div class="faculty-card-content">
                                <span class="faculty-card-label">FACULTY MEMBER</span>

                                <h4>{{ $teacherName }}</h4>

                                <div class="faculty-card-id">
                                    ID: {{ $teacher->teacher_id ?? 'Not assigned' }}
                                </div>

                                <div class="faculty-card-details">
                                    <span>
                                        <strong>Subject:</strong>
                                        {{ $teacher->subject ?: 'Not specified' }}
                                    </span>

                                    <span>
                                        <strong>Gender:</strong>
                                        {{ $teacher->gender ?: 'Not specified' }}
                                    </span>
                                </div>

                                <span class="faculty-status {{ $statusClass }}">
                                    {{ $teacher->status ? ucfirst($teacher->status) : 'Not set' }}
                                </span>

                                <div class="faculty-card-action">
                                    View Individual Report <span>&rarr;</span>
                                </div>
                            </div>
                        </a>

                    @endforeach

                </div>

            @else

                <div class="faculty-empty-state">
                    <div class="faculty-empty-icon">▤</div>
                    <h4>No Faculty Records Found</h4>
                    <p>
                        No faculty records match your selected filters.
                        Try changing your search or filter criteria.
                    </p>

                    @if(request()->query())
                        <a href="{{ route('admin.teachers.reports.index') }}" class="faculty-reset-btn">
                            Clear Filters
                        </a>
                    @endif
                </div>

            @endif

        </section>

        {{-- Print School Header --}}
        <div class="faculty-print-school print-only">
            @if(!empty($schoolLogo))
                <img src="{{ $schoolLogo }}" alt="School Logo">
            @endif

            <div class="faculty-print-school-info">
                <h2>{{ $schoolName }}</h2>

                @if($schoolAddress !== '')
                    <p>{{ $schoolAddress }}</p>
                @endif

                @if($schoolPhone !== '' || $schoolEmail !== '')
                    <p>
                        @if($schoolPhone !== '')
                            Phone: {{ $schoolPhone }}
                        @endif

                        @if($schoolPhone !== '' && $schoolEmail !== '')
                            |
                        @endif

                        @if($schoolEmail !== '')
                            Email: {{ $schoolEmail }}
                        @endif
                    </p>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
/* =========================================================
   FACULTY REPORTS - MATCHING STUDENT REPORTS DESIGN
========================================================= */

.payroll-simple.faculty-reports-page {
    background: #f5f4f0;
    color: #1e2a25;
    min-height: 100vh;
    padding-bottom: 35px;
    font-family: 'Inter', 'Public Sans', Arial, sans-serif;
}

.faculty-reports-page * {
    box-sizing: border-box;
}

.faculty-reports-page .container-fluid {
    max-width: 1650px;
}

/* Topbar */

.faculty-report-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 22px;
}

.faculty-back-link {
    color: #526158;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
}

.faculty-back-link:hover {
    color: #1a6b3c;
}

.faculty-back-link span {
    margin-right: 6px;
}

.faculty-print-btn {
    border: 1px solid #d4cfc2;
    background: #fff;
    color: #2c3e36;
    border-radius: 9px;
    padding: 10px 17px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}

.faculty-print-btn:hover {
    background: #2c3e36;
    color: #fff;
}

/* Hero */

.faculty-report-hero {
    display: grid;
    grid-template-columns: 1.35fr .65fr;
    min-height: 225px;
    overflow: hidden;
    border: 1px solid #d4cfc2;
    border-radius: 17px;
    background: #fdfbf7;
    margin-bottom: 32px;
}

.faculty-report-hero-content {
    padding: 38px 40px;
}

.faculty-report-eyebrow {
    display: inline-block;
    color: #8b795b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 12px;
}

.faculty-report-hero h2 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 34px;
    font-weight: 700;
    margin: 0 0 12px;
}

.faculty-report-hero-content p {
    color: #5c6b64;
    font-size: 14px;
    line-height: 1.7;
    max-width: 560px;
    margin: 0;
}

.faculty-report-hero-art {
    background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 25px;
    color: #2c3e36;
}

.faculty-hero-icon {
    width: 65px;
    height: 65px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    background: #fffefa;
    color: #527f70;
    font-size: 34px;
    margin-bottom: 13px;
    box-shadow: 0 5px 18px rgba(44, 62, 54, .08);
}

.faculty-report-hero-art > span {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2px;
    color: #8b795b;
}

.faculty-report-hero-art strong {
    font-family: Georgia, serif;
    font-size: 21px;
    margin-top: 5px;
}

/* Directory section */

.faculty-directory-section {
    background: #fffefa;
    border: 1px solid #d4cfc2;
    border-radius: 16px;
    padding: 28px;
}

.faculty-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 24px;
}

.faculty-section-heading h3 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 25px;
    margin: 0 0 7px;
}

.faculty-section-heading p {
    color: #5c6b64;
    font-size: 13px;
    margin: 0;
}

.faculty-record-count {
    padding: 9px 14px;
    background: #f3ead8;
    color: #526158;
    border: 1px solid #e5dccb;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

/* Filters */

.faculty-filters {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    padding: 20px;
    margin-bottom: 24px;
    background: #fdfbf7;
    border: 1px solid #e5e0d5;
    border-radius: 13px;
}

.faculty-filter-item {
    display: flex;
    flex-direction: column;
    gap: 7px;
    min-width: 0;
}

.faculty-filter-item label {
    color: #526158;
    font-size: 11px;
    font-weight: 800;
}

.faculty-filter-item input,
.faculty-filter-item select {
    width: 100%;
    height: 41px;
    padding: 0 12px;
    border: 1px solid #d4cfc2;
    border-radius: 8px;
    background: #fffefa;
    color: #1e2a25;
    font-size: 12px;
    outline: none;
}

.faculty-filter-item input:focus,
.faculty-filter-item select:focus {
    border-color: #527f70;
    box-shadow: 0 0 0 3px rgba(82, 127, 112, .10);
}

.faculty-filter-buttons {
    display: flex;
    align-items: flex-end;
    gap: 9px;
}

.faculty-apply-btn,
.faculty-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 41px;
    padding: 0 17px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: all .2s ease;
}

.faculty-apply-btn {
    background: #2c3e36;
    color: #fff;
    border: 1px solid #2c3e36;
}

.faculty-apply-btn:hover {
    background: #3a4f45;
}

.faculty-reset-btn {
    background: #fff;
    color: #526158;
    border: 1px solid #d4cfc2;
}

.faculty-reset-btn:hover {
    background: #f3ead8;
    color: #2c3e36;
}

/* Faculty cards */

.faculty-card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px;
}

.faculty-member-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    min-height: 190px;
    padding: 21px;
    border: 1px solid #e5e0d5;
    border-radius: 13px;
    background: #fff;
    text-decoration: none;
    transition: all .2s ease;
}

.faculty-member-card:hover {
    border-color: #8ca899;
    background: #fcfdf9;
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(44, 62, 54, .07);
}

.faculty-card-icon {
    width: 53px;
    height: 53px;
    min-width: 53px;
    border-radius: 13px;
    background: #f3ead8;
    display: grid;
    place-items: center;
    color: #527f70;
    font-size: 27px;
}

.faculty-card-content {
    flex: 1;
    min-width: 0;
}

.faculty-card-label {
    display: block;
    color: #8b795b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    margin-bottom: 5px;
}

.faculty-card-content h4 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 21px;
    font-weight: 700;
    margin: 0 0 5px;
    overflow-wrap: anywhere;
}

.faculty-card-id {
    color: #7b857b;
    font-size: 11px;
    margin-bottom: 12px;
}

.faculty-card-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    color: #5c6b64;
    font-size: 12px;
    margin-bottom: 12px;
    overflow-wrap: anywhere;
}

.faculty-card-details strong {
    color: #39483e;
}

.faculty-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
}

.faculty-active {
    background: #e4f3ea;
    color: #1a6b3c;
}

.faculty-inactive {
    background: #fcebe8;
    color: #a33a2e;
}

.faculty-card-action {
    color: #527f70;
    font-size: 12px;
    font-weight: 800;
    margin-top: 15px;
}

.faculty-card-action span {
    margin-left: 4px;
    transition: margin .2s ease;
}

.faculty-member-card:hover .faculty-card-action span {
    margin-left: 9px;
}

/* Empty state */

.faculty-empty-state {
    text-align: center;
    padding: 55px 20px;
    border: 1px dashed #d4cfc2;
    border-radius: 13px;
    background: #fdfbf7;
}

.faculty-empty-icon {
    font-size: 38px;
    color: #527f70;
    margin-bottom: 12px;
}

.faculty-empty-state h4 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 21px;
    margin: 0 0 8px;
}

.faculty-empty-state p {
    color: #5c6b64;
    font-size: 13px;
    margin: 0 0 17px;
}

/* Print school header */

.faculty-reports-page .print-only {
    display: none;
}

/* Responsive */

@media (max-width: 1100px) {
    .faculty-card-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .faculty-filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .faculty-report-hero {
        grid-template-columns: 1fr;
    }

    .faculty-report-hero-content {
        padding: 28px 22px;
    }

    .faculty-report-hero h2 {
        font-size: 29px;
    }

    .faculty-report-hero-art {
        min-height: 170px;
    }

    .faculty-card-grid,
    .faculty-filters {
        grid-template-columns: 1fr;
    }

    .faculty-directory-section {
        padding: 20px;
    }

    .faculty-member-card {
        padding: 17px;
    }

    .faculty-filter-buttons {
        align-items: stretch;
    }

    .faculty-filter-buttons > * {
        flex: 1;
    }
}

/* Print */

@media print {
    @page {
        size: A4 landscape;
        margin: 12mm;
    }

    body {
        background: #fff !important;
    }

    .sidebar,
    .top-header,
    .app-footer,
    .faculty-report-topbar,
    .faculty-report-hero-art,
    .faculty-filters {
        display: none !important;
    }

    .main-area {
        margin: 0 !important;
        width: 100% !important;
    }

    .main-content {
        padding: 0 !important;
        background: #fff !important;
    }

    .payroll-simple.faculty-reports-page {
        background: #fff !important;
        padding: 0 !important;
        min-height: auto !important;
    }

    .faculty-reports-page .container-fluid {
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .faculty-reports-page .print-only {
        display: flex !important;
    }

    .faculty-print-school {
        align-items: center;
        justify-content: center;
        gap: 18px;
        text-align: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #2c3e36;
        padding-bottom: 15px;
    }

    .faculty-print-school img {
        width: 65px;
        height: 65px;
        object-fit: contain;
    }

    .faculty-print-school h2 {
        font-family: Georgia, serif;
        color: #1e2a25;
        margin: 0 0 5px;
        font-size: 20px;
    }

    .faculty-print-school p {
        color: #5c6b64;
        font-size: 11px;
        margin: 3px 0;
    }

    .faculty-report-hero {
        display: block;
        min-height: auto;
        border: none;
        margin-bottom: 20px;
    }

    .faculty-report-hero-content {
        padding: 10px 0;
    }

    .faculty-directory-section {
        border: none;
        padding: 0;
    }

    .faculty-card-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .faculty-member-card {
        min-height: auto;
        break-inside: avoid;
        box-shadow: none !important;
        transform: none !important;
        border: 1px solid #d4cfc2;
        color: #1e2a25 !important;
    }

    .faculty-card-content h4 {
        color: #1e2a25 !important;
    }

    .faculty-card-action {
        color: #527f70 !important;
    }
}
</style>

<script>
function printFacultyReport() {
    const logo = document.querySelector('.faculty-print-school img');

    if (logo && typeof logo.decode === 'function') {
        logo.decode().catch(function () {}).finally(function () {
            window.print();
        });
        return;
    }

    window.print();
}
</script>

@endsection