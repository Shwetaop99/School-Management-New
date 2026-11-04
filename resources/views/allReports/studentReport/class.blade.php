 @extends('layouts.app')

@section('title', 'Class Student Report | Admin')
@section('page-title', 'Class Student Report')

@section('content')

<div class="payroll-simple student-class-report-page">
    <div class="container-fluid py-4">

        <div class="student-report-print-header">
            @if($schoolSetting && $schoolSetting->logo_url)
                <img src="{{ $schoolSetting->logo_url }}" alt="School Logo" class="student-report-print-logo">
            @endif

            <div class="student-report-print-school-details">
                <h1>{{ $schoolSetting->school_name ?? config('app.name') }}</h1>
                <p>
                    {{ collect([$schoolSetting->address ?? null, $schoolSetting->city ?? null, $schoolSetting->district ?? null, $schoolSetting->state ?? null, $schoolSetting->pincode ?? null])->filter()->implode(', ') }}
                </p>
                <p>
                    @if($schoolSetting?->phone) Phone: {{ $schoolSetting->phone }} @endif
                    @if($schoolSetting?->email) | Email: {{ $schoolSetting->email }} @endif
                    @if($schoolSetting?->website) | {{ $schoolSetting->website }} @endif
                </p>
                <h2>Student Class Report</h2>
                <p>Class: {{ $selectedClass }} | Section: {{ $selectedSection }} | Academic Year: {{ $selectedAcademicYear }}</p>
            </div>
        </div>

        <div class="student-report-hero mb-4">
            <div class="student-report-hero-content">
                <span class="student-report-eyebrow">STUDENT REPORTS</span>
                <h2>{{ $selectedClass }}</h2>
                <p>Choose a section and academic year to view the student records.</p>
            </div>
            <div class="student-report-hero-icon">▤</div>
        </div>

        <div class="student-report-toolbar mb-4">
            <a href="{{ route('admin.reports.students') }}" class="student-report-back">
                Back to Classes
            </a>

            @if($selectedSection && $selectedAcademicYear)
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.reports.students.class.pdf', ['class' => $selectedClass, 'section' => $selectedSection, 'academic_year' => $selectedAcademicYear]) }}"
                       class="student-report-print text-decoration-none">
                        Download PDF
                    </a>

                    <a href="{{ route('admin.reports.students.class.excel', ['class' => $selectedClass, 'section' => $selectedSection, 'academic_year' => $selectedAcademicYear]) }}"
                       class="student-report-print text-decoration-none">
                        Export Excel
                    </a>

                    <button type="button" class="student-report-print" onclick="window.print()">
                        Print Report
                    </button>
                </div>
            @endif
        </div>

        <div class="student-report-filter-card mb-4">
            <form method="GET" action="{{ route('admin.reports.students.class') }}">
                <input type="hidden" name="class" value="{{ $selectedClass }}">

                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="section" class="form-label">Section</label>
                        <select name="section" id="section" class="form-select">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ $selectedSection == $section ? 'selected' : '' }}>
                                    Section {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-select">
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ $selectedAcademicYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="student-report-filter-btn w-100">
                            Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="student-report-list-card">
            <div class="student-report-list-heading">
                <div>
                    <h4>Student Records</h4>
                    <p>
                        @if($selectedSection && $selectedAcademicYear)
                            Class {{ $selectedClass }} · Section {{ $selectedSection }} · {{ $selectedAcademicYear }}
                        @else
                            Select a section and academic year to view students.
                        @endif
                    </p>
                </div>

                @if($selectedSection && $selectedAcademicYear)
                    <span class="student-report-count">
                        {{ $students->count() }} Students
                    </span>
                @endif
            </div>

            @if(!$selectedSection || !$selectedAcademicYear)
                <div class="student-report-empty">
                    <div class="student-report-empty-icon">⌕</div>
                    <h5>Select Report Filters</h5>
                    <p>Please select both section and academic year, then click Apply.</p>
                </div>
            @elseif($students->isEmpty())
                <div class="student-report-empty">
                    <div class="student-report-empty-icon">○</div>
                    <h5>No Students Found</h5>
                    <p>No student records are available for the selected filters.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table student-report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Roll Number</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Gender</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->roll_number ?: '—' }}</td>
                                    <td>{{ $student->student_id ?: '—' }}</td>
                                    <td class="student-report-name">
    <a href="{{ route('admin.reports.students.profile', $student->id) }}" class="student-profile-link">
        {{ $student->full_name ?: trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}
    </a>
</td>
                                    <td>{{ ucfirst($student->gender ?? '—') }}</td>
                                    <td>{{ $student->phone ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="student-report-footer">
            <span>Student Records · {{ $schoolSetting->school_name ?? config('app.name') }}</span>
            <span>Generated on {{ now()->format('d M Y') }}</span>
        </div>

    </div>
</div>

<style>
.student-class-report-page {
    background: #f5f4f0;
    color: #1e2a25;
    min-height: calc(100vh - 100px);
    padding: 1px 0 24px;
    font-family: 'Public Sans', 'Inter', sans-serif;
}
.student-class-report-page .student-report-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 30px 34px;
    border: 1px solid #d4cfc2;
    border-radius: 18px;
    background: linear-gradient(110deg, #fdfbf7 0%, #f8f1e3 58%, #e8dcc6 100%);
}
.student-class-report-page .student-report-eyebrow {
    color: #5c6b64;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
}
.student-class-report-page .student-report-hero h2 {
    margin: 9px 0 5px;
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: clamp(27px, 3vw, 36px);
    font-weight: 700;
}
.student-class-report-page .student-report-hero p {
    margin: 0;
    color: #5c6b64;
    font-size: 14px;
}
.student-class-report-page .student-report-hero-icon {
    display: grid;
    place-items: center;
    width: 65px;
    height: 65px;
    flex: 0 0 65px;
    border-radius: 18px;
    background: #f3ead8;
    color: #2c3e36;
    font-size: 30px;
}
.student-class-report-page .student-report-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}
.student-class-report-page .student-report-back {
    color: #2c3e36;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}
.student-class-report-page .student-report-back:hover {
    color: #3a4f45;
}
.student-class-report-page .student-report-print,
.student-class-report-page .student-report-filter-btn {
    border: 0;
    border-radius: 9px;
    padding: 11px 19px;
    background: #2c3e36;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    transition: background .2s ease;
}
.student-class-report-page .student-report-print:hover,
.student-class-report-page .student-report-filter-btn:hover {
    background: #3a4f45;
}
.student-class-report-page .student-report-filter-card,
.student-class-report-page .student-report-list-card {
    padding: 23px;
    border: 1px solid #d4cfc2;
    border-radius: 15px;
    background: #fff;
}
.student-class-report-page .form-label {
    margin-bottom: 8px;
    color: #39483e;
    font-size: 13px;
    font-weight: 700;
}
.student-class-report-page .form-select {
    min-height: 44px;
    border: 1px solid #d4cfc2;
    border-radius: 8px;
    color: #1e2a25;
    background-color: #fff;
    box-shadow: none;
}
.student-class-report-page .form-select:focus {
    border-color: #527f70;
    box-shadow: 0 0 0 3px rgba(82,127,112,.12);
}
.student-class-report-page .student-report-list-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}
.student-class-report-page .student-report-list-heading h4 {
    margin: 0 0 5px;
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 21px;
    font-weight: 700;
}
.student-class-report-page .student-report-list-heading p {
    margin: 0;
    color: #5c6b64;
    font-size: 13px;
}
.student-class-report-page .student-report-count {
    padding: 8px 13px;
    border-radius: 20px;
    background: #f3ead8;
    color: #2c3e36;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}
.student-class-report-page .student-report-table {
    margin: 0;
    vertical-align: middle;
}
.student-class-report-page .student-report-table thead th {
    padding: 14px 12px;
    border-bottom: 1px solid #d4cfc2;
    background: #f5f4f0;
    color: #39483e;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
    white-space: nowrap;
}
.student-class-report-page .student-report-table tbody td {
    padding: 15px 12px;
    border-bottom: 1px solid #eeeae1;
    color: #39483e;
    font-size: 13px;
}
.student-class-report-page .student-report-table tbody tr:last-child td {
    border-bottom: 0;
}
.student-class-report-page .student-report-name {
    color: #1e2a25 !important;
    font-weight: 700;
}
.student-class-report-page .student-report-empty {
    padding: 45px 15px;
    text-align: center;
}
.student-class-report-page .student-report-empty-icon {
    display: grid;
    place-items: center;
    width: 54px;
    height: 54px;
    margin: 0 auto 14px;
    border-radius: 16px;
    background: #f3ead8;
    color: #2c3e36;
    font-size: 25px;
}
.student-class-report-page .student-report-empty h5 {
    margin-bottom: 7px;
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 700;
}
.student-class-report-page .student-report-empty p {
    margin: 0;
    color: #5c6b64;
    font-size: 13px;
}
.student-class-report-page .student-report-footer {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 3px 0;
    color: #7b857b;
    font-size: 11px;
}
@media(max-width: 767px) {
    .student-class-report-page .student-report-hero {
        padding: 23px 20px;
    }
    .student-class-report-page .student-report-filter-card,
    .student-class-report-page .student-report-list-card {
        padding: 17px;
    }
    .student-class-report-page .student-report-list-heading {
        align-items: flex-start;
        flex-direction: column;
    }
    .student-class-report-page .student-report-footer {
        flex-direction: column;
    }
}
.student-class-report-page .student-report-print-header {
    display: none;
}

@media print {
    .student-class-report-page .student-report-print-header {
        display: flex !important;
        align-items: center;
        gap: 22px;
        width: 100%;
        padding: 0 0 16px;
        margin-bottom: 20px;
        border-bottom: 2px solid #2c3e36;
        color: #1e2a25 !important;
        break-inside: avoid;
    }

    .student-class-report-page .student-report-print-logo {
        width: 90px;
        height: 90px;
        object-fit: contain;
    }

    .student-class-report-page .student-report-print-school-details {
        flex: 1;
        text-align: center;
    }

    .student-class-report-page .student-report-print-school-details h1 {
        margin: 0 0 5px;
        font-size: 23px;
        font-weight: 700;
        color: #1e2a25 !important;
    }

    .student-class-report-page .student-report-print-school-details h2 {
        margin: 8px 0 4px;
        font-size: 17px;
        font-weight: 700;
    }

    .student-class-report-page .student-report-print-school-details p {
        margin: 3px 0;
        font-size: 11px;
    }

    body * {
        visibility: hidden !important;
    }
    .student-class-report-page,
    .student-class-report-page * {
        visibility: visible !important;
    }
    .student-class-report-page {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: #fff !important;
        padding: 0 !important;
    }
    .student-class-report-page .student-report-toolbar,
    .student-class-report-page .student-report-filter-card {
        display: none !important;
    }
    .student-class-report-page .student-report-hero {
        display: none !important;
    }

    .student-class-report-page .student-report-hero,
    .student-class-report-page .student-report-list-card {
        box-shadow: none !important;
        break-inside: avoid;
    }
    .student-class-report-page .student-report-table {
        width: 100%;
        border-collapse: collapse;
    }
    .student-class-report-page .student-report-table th,
    .student-class-report-page .student-report-table td {
        border: 1px solid #d4cfc2 !important;
    }
    @page {
        size: A4 landscape;
        margin: 12mm;
    }
}
</style>

@endsection