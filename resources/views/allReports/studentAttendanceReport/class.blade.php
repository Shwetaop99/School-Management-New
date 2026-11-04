@extends('layouts.app')

@section('title', 'Class Student Attendance | Admin')
@section('page-title', 'Class Student Attendance')

@section('content')

<div class="payroll-simple student-attendance-class-page">
    <div class="container-fluid py-4">

        <style>
            .student-attendance-class-page {
                background: #f5f4f0;
                color: #1e2a25;
                min-height: 100vh;
                padding: 10px;
            }

            .student-attendance-class-page .back-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #fff;
                color: #2c3e36;
                border: 1px solid #d4cfc2;
                border-radius: 9px;
                padding: 10px 17px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
                margin-bottom: 18px;
            }

            .student-attendance-class-page .back-btn:hover {
                background: #f3ead8;
                color: #2c3e36;
            }

            .student-attendance-class-page .report-hero {
                display: grid;
                grid-template-columns: 1.4fr 1fr;
                background: #fdfbf7;
                border: 1px solid #d4cfc2;
                border-radius: 18px;
                overflow: hidden;
                margin-bottom: 22px;
            }

            .student-attendance-class-page .hero-main,
            .student-attendance-class-page .hero-side {
                padding: 27px;
            }

            .student-attendance-class-page .hero-side {
                background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .student-attendance-class-page .eyebrow {
                color: #5c6b64;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 1.4px;
                text-transform: uppercase;
            }

            .student-attendance-class-page .hero-title {
                font-size: 27px;
                font-weight: 800;
                margin: 10px 0;
                color: #1e2a25;
            }

            .student-attendance-class-page .hero-description {
                color: #5c6b64;
                font-size: 13px;
                line-height: 1.8;
            }

            .student-attendance-class-page .hero-number {
                font-size: 36px;
                font-weight: 800;
                color: #2c3e36;
                margin: 7px 0;
            }

            .student-attendance-class-page .filter-panel,
            .student-attendance-class-page .report-card {
                background: #fff;
                border: 1px solid #d4cfc2;
                border-radius: 15px;
                padding: 22px;
                box-shadow: 0 3px 12px rgba(30, 42, 37, .035);
                margin-bottom: 20px;
            }

            .student-attendance-class-page .section-heading {
                font-size: 17px;
                font-weight: 800;
                color: #1e2a25;
                margin-bottom: 16px;
            }

            .student-attendance-class-page .form-label {
                color: #39483e;
                font-size: 12px;
                font-weight: 800;
                margin-bottom: 7px;
            }

            .student-attendance-class-page .form-control,
            .student-attendance-class-page .form-select {
                min-height: 43px;
                border: 1px solid #d4cfc2;
                border-radius: 9px;
                color: #1e2a25;
                font-size: 13px;
                background-color: #fff;
                box-shadow: none;
            }

            .student-attendance-class-page .form-control:focus,
            .student-attendance-class-page .form-select:focus {
                border-color: #527f70;
                box-shadow: 0 0 0 3px rgba(82, 127, 112, .10);
            }

            .student-attendance-class-page .primary-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 43px;
                padding: 10px 20px;
                background: #2c3e36;
                color: #fff;
                border: 1px solid #2c3e36;
                border-radius: 9px;
                font-size: 13px;
                font-weight: 800;
                text-decoration: none;
                cursor: pointer;
            }

            .student-attendance-class-page .primary-btn:hover {
                background: #3a4f45;
                color: #fff;
            }

            .student-attendance-class-page .light-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                min-height: 43px;
                padding: 10px 17px;
                background: #f3ead8;
                color: #2c3e36;
                border: 1px solid #e4d8c2;
                border-radius: 9px;
                font-size: 13px;
                font-weight: 800;
                text-decoration: none;
            }

            .student-attendance-class-page .light-btn:hover {
                background: #e8dcc6;
                color: #2c3e36;
            }

            .student-attendance-class-page .student-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                min-width: 850px;
            }

            .student-attendance-class-page .student-table thead th {
                background: #f5f4f0;
                color: #526057;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: .5px;
                padding: 14px 13px;
                border-bottom: 1px solid #e5e1d5;
                white-space: nowrap;
            }

            .student-attendance-class-page .student-table tbody td {
                padding: 14px 13px;
                font-size: 12px;
                color: #39483e;
                border-bottom: 1px solid #eeeae1;
                vertical-align: middle;
            }

            .student-attendance-class-page .student-table tbody tr:hover {
                background: #fdfbf7;
            }

            .student-attendance-class-page .student-name {
                font-weight: 800;
                color: #2c3e36;
                text-decoration: none;
            }

            .student-attendance-class-page .student-name:hover {
                color: #527f70;
                text-decoration: underline;
            }

            .student-attendance-class-page .percentage {
                font-weight: 800;
                color: #1a6b3c;
            }

            .student-attendance-class-page .view-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #f3ead8;
                color: #2c3e36;
                padding: 7px 11px;
                border-radius: 7px;
                font-size: 11px;
                font-weight: 800;
                text-decoration: none;
                white-space: nowrap;
            }

            .student-attendance-class-page .view-btn:hover {
                background: #e8dcc6;
                color: #2c3e36;
            }

            .student-attendance-class-page .empty-state {
                text-align: center;
                padding: 42px 15px;
                color: #7b857b;
            }

            .student-attendance-class-page .empty-icon {
                font-size: 34px;
                color: #9b9f91;
                margin-bottom: 12px;
            }

            .student-attendance-class-page .student-count {
                font-size: 12px;
                font-weight: 800;
                color: #5c6b64;
            }

            @media(max-width: 768px) {
                .student-attendance-class-page .report-hero {
                    grid-template-columns: 1fr;
                }

                .student-attendance-class-page .hero-title {
                    font-size: 23px;
                }

                .student-attendance-class-page .filter-panel,
                .student-attendance-class-page .report-card {
                    padding: 16px;
                }
            }
        </style>

        {{-- BACK BUTTON --}}
        <a href="{{ route('admin.reports.student-attendance') }}"
           class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Classes
        </a>

        {{-- HERO --}}
        <div class="report-hero">

            <div class="hero-main">
                <div class="eyebrow">Student Attendance / Class Report</div>

                <div class="hero-title">
                    Class {{ $selectedClass }}
                </div>

                <div class="hero-description">
                    Select a division and academic year to view the
                    students enrolled in this class and their attendance
                    summaries.
                </div>
            </div>

            <div class="hero-side">
                <div class="eyebrow">Students Displayed</div>

                <div class="hero-number">
                    {{ $students->count() }}
                </div>

                <div style="font-size:13px;color:#5c6b64;">
                    @if($selectedSection && $selectedAcademicYear)
                        Students matching your selected filters
                    @else
                        Apply the filters to view students
                    @endif
                </div>
            </div>

        </div>

        {{-- FILTERS --}}
        <div class="filter-panel">

            <div class="section-heading">
                <i class="fas fa-filter me-2"></i>
                Filter Student Records
            </div>

            <form method="GET"
                  action="{{ route('admin.reports.student-attendance.class') }}">

                <input type="hidden"
                       name="class"
                       value="{{ $selectedClass }}">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Division</label>

                        <select name="section"
                                class="form-select"
                                required>
                            <option value="">Select Division</option>

                            @foreach($sections as $section)
                                <option value="{{ $section }}"
                                    {{ (string) $selectedSection === (string) $section ? 'selected' : '' }}>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Academic Year</label>

                        <select name="academic_year"
                                class="form-select"
                                required>
                            <option value="">Select Academic Year</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    {{ (string) $selectedAcademicYear === (string) $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <button type="submit" class="primary-btn w-100">
                            <i class="fas fa-search"></i>
                            Apply Filters
                        </button>
                    </div>

                </div>

            </form>
        </div>

        {{-- STUDENT LIST --}}
        <div class="report-card">

            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-2 mb-3">

                <div>
                    <div class="section-heading mb-1">
                        Student Attendance List
                    </div>

                    <div class="student-count">
                        @if($selectedSection && $selectedAcademicYear)
                            Division {{ $selectedSection }}
                            · Academic Year {{ $selectedAcademicYear }}
                        @else
                            Select Division and Academic Year to continue
                        @endif
                    </div>
                </div>

                @if($selectedSection && $selectedAcademicYear)
                    <form method="GET"
                          action="{{ route('admin.reports.student-attendance.class') }}"
                          class="d-flex gap-2">

                        <input type="hidden"
                               name="class"
                               value="{{ $selectedClass }}">

                        <input type="hidden"
                               name="section"
                               value="{{ $selectedSection }}">

                        <input type="hidden"
                               name="academic_year"
                               value="{{ $selectedAcademicYear }}">

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search student..."
                               value="{{ $search }}"
                               style="min-width:210px;">

                        <button type="submit" class="primary-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                @endif

            </div>

            @if(!$selectedSection || !$selectedAcademicYear)

                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-filter"></i>
                    </div>

                    <h5 style="font-weight:800;color:#2c3e36;">
                        Select Your Filters
                    </h5>

                    <p class="mb-0" style="font-size:13px;">
                        Choose a division and academic year, then click
                        Apply Filters to view students.
                    </p>
                </div>

            @elseif($students->isNotEmpty())

                <div class="table-responsive">

                    <table class="student-table">

                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Roll No.</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Present Days</th>
                                <th>Absent Days</th>
                                <th>Attendance %</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($students as $index => $student)

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $student->roll_number ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $student->student_id ?? '—' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.reports.student-attendance.profile', [
                                            'student' => $student->id,
                                            'academic_year' => $selectedAcademicYear
                                        ]) }}"
                                           class="student-name">
                                            {{ $student->full_name }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $student->attendance_summary['present'] }}
                                    </td>

                                    <td>
                                        {{ $student->attendance_summary['absent'] }}
                                    </td>

                                    <td>
                                        <span class="percentage">
                                            {{ $student->attendance_summary['percentage'] }}%
                                        </span>
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.reports.student-attendance.profile', [
                                            'student' => $student->id,
                                            'academic_year' => $selectedAcademicYear
                                        ]) }}"
                                           class="view-btn">
                                            <i class="fas fa-eye"></i>
                                            View Profile
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <h5 style="font-weight:800;color:#2c3e36;">
                        No Students Found
                    </h5>

                    <p class="mb-0" style="font-size:13px;">
                        No students match the selected class, division,
                        academic year or search term.
                    </p>
                </div>

            @endif

        </div>

    </div>
</div>

@endsection
