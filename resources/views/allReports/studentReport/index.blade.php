@extends('layouts.app')

@section('title', 'Student Reports')
@section('page-title', 'Student Reports')

@section('content')

<style>

    .report-section {
    background: #ffffff;
    border-radius: 14px;
    padding: 22px;
    margin-top: 20px;
    border: 1px solid #e8edf3;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 18px;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    color: #2563eb;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th {
    background: #f4f7fb;
    padding: 12px;
    text-align: left;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
}

.report-table td {
    padding: 12px;
    border-top: 1px solid #edf0f4;
    font-size: 14px;
    color: #374151;
}

.status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    background: #eef2ff;
    font-size: 12px;
    font-weight: 600;
}

.empty-report {
    padding: 18px;
    text-align: center;
    color: #6b7280;
    background: #f8fafc;
    border-radius: 10px;
}

    .report-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.report-action-btn {
    height: 40px;
    padding: 0 15px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

.excel-btn {
    background: #ecfdf5;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.excel-btn:hover {
    background: #dcfce7;
    color: #166534;
}

    /* PDF Button */
    .pdf-btn {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .pdf-btn:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    /* Print Button */
    .print-btn {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
        cursor: pointer;
    }

    .print-btn:hover {
        background: #bbf7d0;
        color: #166534;
    }

    .student-report-page {
        padding: 25px;
        background: #f4f7fb;
        min-height: calc(100vh - 70px);
    }

    /* Header */
    .report-header {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: white;
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
    }

    .report-header-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .report-header-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
    }

    .report-header h2 {
        margin: 0 0 5px;
        font-size: 25px;
        font-weight: 700;
    }

    .report-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #e3eaf3;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
    }

    .filter-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
    }

    .filter-title-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eaf2ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .filter-title h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #172033;
    }

    .filter-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 7px;
    }

    .filter-control {
        width: 100%;
        height: 43px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        padding: 0 13px;
        font-size: 14px;
        color: #334155;
        background: white;
        outline: none;
        transition: 0.2s;
    }

    .filter-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .search-wrapper input {
        padding-left: 38px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        align-items: end;
    }

    .btn-filter {
        height: 43px;
        border: none;
        border-radius: 10px;
        padding: 0 20px;
        background: #2563eb;
        color: white;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-filter:hover {
        background: #1d4ed8;
        color: white;
    }

    .btn-reset {
        height: 43px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        padding: 0 18px;
        background: white;
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-reset:hover {
        background: #f8fafc;
        color: #1e293b;
    }

    /* Report Table Card */
    .table-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #e3eaf3;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid #edf1f6;
    }

    .report-actions {
        margin-left: auto;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .report-actions {
            margin-left: 0;
            width: 100%;
            flex-wrap: wrap;
        }
    }

    .table-header h5 {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .table-header p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .student-report-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }

    .student-report-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 14px 16px;
        border-bottom: 1px solid #e8edf4;
        white-space: nowrap;
    }

    .student-report-table tbody td {
        padding: 15px 16px;
        border-bottom: 1px solid #edf1f6;
        color: #334155;
        font-size: 13px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .student-report-table tbody tr:hover {
        background: #f8fbff;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .student-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eaf2ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .student-name {
        font-weight: 600;
        color: #172033;
    }

    .student-id {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }

    .gender-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        border: 1px solid #dbe3ef;
        background: white;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: 0.2s;
    }

    .action-btn:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 45px;
        color: #cbd5e1;
        margin-bottom: 12px;
    }

    .empty-state h6 {
        font-size: 16px;
        color: #475569;
        margin-bottom: 5px;
    }

    .empty-state p {
        margin: 0;
        font-size: 13px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .report-header {
            align-items: flex-start;
        }

        .filter-buttons {
            margin-top: 10px;
        }
    }

    @media (max-width: 768px) {
        .student-report-page {
            padding: 15px;
        }

        .report-header {
            padding: 20px;
        }

        .report-header h2 {
            font-size: 21px;
        }

        .filter-card {
            padding: 18px;
        }
    }
</style>


<div class="student-report-page">

    {{-- HEADER --}}
    <div class="report-header">

        <div class="report-header-left">

            <div class="report-header-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>
                <h2>Student Reports</h2>
                <p>Generate and view detailed student information and academic records.</p>
            </div>

        </div>

    </div>


    {{-- FILTERS --}}
    <div class="filter-card">

        <div class="filter-title">

            <div class="filter-title-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>

            <h5>Filter Student Reports</h5>

        </div>


        <form method="GET" action="{{ route('admin.reports.students') }}">

            <div class="row g-3">

                {{-- Student Search --}}
                <div class="col-lg-4 col-md-6">

                    <label class="filter-label">
                        Student Search
                    </label>

                    <div class="search-wrapper">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            class="filter-control"
                            placeholder="Name, ID, phone or email..."
                            value="{{ request('search') }}"
                        >

                    </div>

                </div>


                {{-- From Date --}}
                <div class="col-lg-2 col-md-3">

                    <label class="filter-label">
                        From Date
                    </label>

                    <input
                        type="date"
                        name="from_date"
                        class="filter-control"
                        value="{{ request('from_date') }}"
                    >

                </div>


                {{-- To Date --}}
                <div class="col-lg-2 col-md-3">

                    <label class="filter-label">
                        To Date
                    </label>

                    <input
                        type="date"
                        name="to_date"
                        class="filter-control"
                        value="{{ request('to_date') }}"
                    >

                </div>


                {{-- Academic Year --}}
                <div class="col-lg-2 col-md-4">

                    <label class="filter-label">
                        Academic Year
                    </label>

                    <select name="academic_year" class="filter-control">

    <option value="">All Years</option>

    @foreach($academicYears as $year)

        <option
            value="{{ $year }}"
            {{ request('academic_year') == $year ? 'selected' : '' }}
        >
            {{ $year }}
        </option>

    @endforeach

</select>

                </div>


                {{-- Class --}}
                <div class="col-lg-2 col-md-4">

                    <label class="filter-label">
                        Class
                    </label>

                    <select name="class" class="filter-control">

    <option value="">All Classes</option>

    @foreach($classes as $class)

        <option
            value="{{ $class }}"
            {{ request('class') == $class ? 'selected' : '' }}
        >
            {{ $class }}
        </option>

    @endforeach

</select>

                </div>


                {{-- Section --}}
                <div class="col-lg-2 col-md-4">

                    <label class="filter-label">
                        Section
                    </label>

                    <select name="section" class="filter-control">

    <option value="">All Sections</option>

    @foreach($sections as $section)

        <option
            value="{{ $section }}"
            {{ request('section') == $section ? 'selected' : '' }}
        >
            {{ $section }}
        </option>

    @endforeach

</select>

                </div>


                {{-- Gender --}}
                <div class="col-lg-2 col-md-4">

                    <label class="filter-label">
                        Gender
                    </label>

                    <select name="gender" class="filter-control">

    <option value="">All Genders</option>

    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>
        Male
    </option>

    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>
        Female
    </option>

</select>

                </div>


                {{-- Status --}}
                <div class="col-lg-2 col-md-4">

                    <label class="filter-label">
                        Status
                    </label>

                    <select name="status" class="filter-control">

    <option value="">All Status</option>

    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
        Active
    </option>

    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
        Inactive
    </option>

</select>

                </div>


                {{-- Buttons --}}
                <div class="col-lg-4 col-md-8">

                    <label class="filter-label">
                        &nbsp;
                    </label>

                    <div class="filter-buttons">

                        <button type="submit" class="btn-filter">
                            <i class="bi bi-funnel me-1"></i>
                            Apply Filters
                        </button>

                        <a
                            href="{{ route('admin.reports.students') }}"
                            class="btn-reset"
                        >
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- REPORT TABLE --}}
    <div class="table-card">

        <div class="table-header">

    <div>
        <h5>Student Report</h5>

        <p>
            Student information based on the selected filters.
        </p>
    </div>


    <div class="report-actions">

    {{-- Download Excel --}}
    <a
        href="{{ route('admin.reports.students.excel', request()->query()) }}"
        class="report-action-btn excel-btn"
    >
        <i class="bi bi-file-earmark-excel"></i>
        Download Excel
    </a>

    {{-- Download PDF --}}
    <a
        href="{{ route('admin.reports.students.pdf', request()->query()) }}"
        class="report-action-btn pdf-btn"
    >
        <i class="bi bi-file-earmark-pdf"></i>
        Download PDF
    </a>

    {{-- Print --}}
    <button
        type="button"
        onclick="window.print()"
        class="report-action-btn print-btn"
    >
        <i class="bi bi-printer"></i>
        🖨️ Print
    </button>

        </div>

        </div>

        <div class="table-responsive">

            <table class="student-report-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Roll No.</th>
                        <th>Admission Date</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="print-hide">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($students as $student)

                    <tr>
                        <td>
                            {{ $students->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="student-info">

                                <div class="student-avatar">
                                    {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                                </div>

                                <div>
                                    <div class="student-name">
                                        {{ $student->full_name }}
                                    </div>

                                    <div class="student-id">
                                        {{ $student->student_id }}
                                    </div>
                                </div>

                            </div>
                        </td>

                        <td>
                            {{ $student->student_id ?? '-' }}
                        </td>

                        <td>
                            {{ $student->academic_year ?? '-' }}
                        </td>

                        <td>
                            {{ $student->class ?? '-' }}
                        </td>

                        <td>
                            {{ $student->section ?? '-' }}
                        </td>

                        <td>
                            {{ $student->roll_number ?? '-' }}
                        </td>

                        <td>
                            @if($student->admission_date)
                                {{ $student->admission_date->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            @if($student->gender)
                                <span class="gender-badge">
                                    {{ $student->gender }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $student->phone ?? '-' }}
                        </td>

                        <td>
                            @if(strtolower($student->status ?? '') === 'active')
                                <span class="status-badge status-active">
                                    Active
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    {{ ucfirst($student->status ?? 'Inactive') }}
                                </span>
                            @endif
                        </td>

                        <td class="print-hide">
                            <a
                                href="{{ route('admin.students.show', $student->id) }}"
                                class="action-btn"
                                title="View Student"
                            >
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="12">

                            <div class="empty-state">
                                <i class="bi bi-people"></i>

                                <h6>No students found</h6>

                                <p>
                                    No student records match the selected filters.
                                </p>
                            </div>

                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if($students->hasPages())
            <div style="padding: 20px; border-top: 1px solid #edf1f6;" class="print-hide">
                {{ $students->links() }}
            </div>
        @endif

    </div>


    {{-- ================= STUDENT DETAILS ================= --}}
    @foreach($students as $student)

        <div class="student-detail-report">

            {{-- ================= ATTENDANCE ================= --}}
            <div class="report-section">

                <div class="section-title">
                    <i class="bi bi-calendar-check"></i>
                    Attendance — {{ $student->full_name }}
                </div>

                @if($student->attendanceRecords->count())

                    <div class="attendance-table-wrapper">

                        <table class="report-table">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Attendance Date</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($student->attendanceRecords as $index => $attendance)

                                    <tr>
                                        <td>
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            {{ $attendance->attendance_date
                                                ? $attendance->attendance_date->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td>
                                            <span class="status-badge">
                                                {{ $attendance->status ?? '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $attendance->remarks ?? '-' }}
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="empty-report">
                        No attendance records available.
                    </div>

                @endif

            </div>


            {{-- ================= LIBRARY ================= --}}
            <div class="report-section">

                <div class="section-title">
                    <i class="bi bi-book"></i>
                    Library — {{ $student->full_name }}
                </div>

                @if($student->libraryRecords->count())

                    <div class="attendance-table-wrapper">

                        <table class="report-table">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Issue Date</th>
                                    <th>Due Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                    <th>Fine</th>
                                    <th>Fine Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($student->libraryRecords as $index => $library)

                                    <tr>
                                        <td>
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            {{ $library->issue_date
                                                ? $library->issue_date->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td>
                                            {{ $library->due_date
                                                ? $library->due_date->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td>
                                            {{ $library->return_date
                                                ? $library->return_date->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td>
                                            <span class="status-badge">
                                                {{ $library->status ?? '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $library->fine !== null
                                                ? '₹' . number_format($library->fine, 2)
                                                : '-' }}
                                        </td>

                                        <td>
                                            {{ $library->fine_status ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $library->remarks ?? '-' }}
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="empty-report">
                        No library records available.
                    </div>

                @endif

            </div>

        </div>

    @endforeach
</div>

<style>
@media print {

    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    html,
    body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        overflow: visible !important;
    }

    /* Hide dashboard navigation and controls */
    .main-sidebar,
    .main-header,
    .navbar,
    .sidebar,
    .filter-card,
    .report-actions,
    .print-hide,
    button,
    .btn,
    a.btn {
        display: none !important;
    }

    /* Use the complete printable width */
    .content-wrapper,
    .main-content,
    .container,
    .container-fluid,
    .student-report-page {
        width: 100% !important;
        max-width: 100% !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        background: #ffffff !important;
    }

    /* Clean report heading */
    .report-header {
        background: #ffffff !important;
        color: #111827 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 0 0 12px !important;
        margin: 0 0 12px !important;
        border-bottom: 1px solid #d1d5db !important;
    }

    .report-header-icon {
        background: #eef2ff !important;
        color: #2563eb !important;
    }

    .report-header p {
        color: #64748b !important;
        opacity: 1 !important;
    }

    .table-card {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .table-header {
        padding: 0 0 10px !important;
        border-bottom: 1px solid #d1d5db !important;
    }

    .table-responsive,
    .attendance-table-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        overflow: visible !important;
    }

    /* Main student table */
    .student-report-table {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
    }

    .student-report-table thead th {
        padding: 7px 5px !important;
        background: #f1f5f9 !important;
        color: #334155 !important;
        font-size: 8px !important;
        white-space: normal !important;
        word-break: break-word !important;
        border: 1px solid #d1d5db !important;
    }

    .student-report-table tbody td {
        padding: 7px 5px !important;
        color: #111827 !important;
        font-size: 8px !important;
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
        border: 1px solid #d1d5db !important;
    }

    .student-info {
        gap: 5px !important;
    }

    .student-avatar {
        width: 24px !important;
        height: 24px !important;
        min-width: 24px !important;
        border-radius: 5px !important;
        font-size: 10px !important;
    }

    .student-name {
        font-size: 8px !important;
    }

    .student-id {
        font-size: 7px !important;
    }

    .gender-badge,
    .status-badge {
        padding: 2px 5px !important;
        font-size: 7px !important;
        white-space: normal !important;
    }

    /* Attendance and library */
    .student-detail-report {
        width: 100% !important;
        margin-top: 15px !important;
        page-break-inside: auto !important;
    }

    .report-section {
        width: 100% !important;
        box-sizing: border-box !important;
        padding: 10px !important;
        margin-top: 12px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
    }

    .section-title {
        margin-bottom: 8px !important;
        font-size: 11px !important;
        color: #111827 !important;
    }

    .report-table {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
    }

    .report-table th,
    .report-table td {
        padding: 5px !important;
        font-size: 8px !important;
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
        border: 1px solid #d1d5db !important;
    }

    .empty-report {
        padding: 8px !important;
        font-size: 8px !important;
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
    }

    .empty-state {
        padding: 20px !important;
    }

    tr {
        page-break-inside: avoid !important;
    }
}
</style>

@endsection