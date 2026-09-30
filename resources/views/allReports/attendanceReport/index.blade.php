@extends('layouts.app')

@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')

@section('content')

<style>
    .attendance-report-page {
        padding: 28px;
        background: #f5f7fb;
        min-height: 100vh;
    }

    /* Header */
    .report-header {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        border-radius: 20px;
        padding: 30px 34px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
    }

    .report-header-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .report-header-icon {
        width: 58px;
        height: 58px;
        border-radius: 15px;
        background: rgba(255,255,255,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .report-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }

    .report-header p {
        margin: 6px 0 0;
        font-size: 15px;
        opacity: .9;
    }

    /* Header buttons */
    .report-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .report-action-btn {
        border: none;
        border-radius: 10px;
        padding: 12px 18px;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .report-action-btn:hover {
        color: #fff;
        opacity: .92;
    }

    .pdf-btn {
        background: #dc2626;
    }

    .excel-btn {
        background: #16a34a;
    }

    .print-btn {
        background: #0f766e;
    }

    /* Filter card */
    .filter-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 28px;
        margin-bottom: 28px;
    }

    .filter-title {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 25px;
    }

    .filter-title-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .filter-title h5 {
        margin: 0;
        font-size: 20px;
        font-weight: 500;
    }

    .filter-label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    .filter-control {
        width: 100%;
        height: 52px;
        border: 1px solid #d9dee7;
        border-radius: 10px;
        padding: 0 14px;
        font-size: 15px;
        color: #334155;
        background: #fff;
        outline: none;
    }

    .filter-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-top: 22px;
    }

    .btn-filter {
        border: none;
        background: #2563eb;
        color: #fff;
        padding: 13px 21px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-reset {
        background: #fff;
        color: #475569;
        border: 1px solid #d9dee7;
        padding: 13px 21px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-reset:hover {
        color: #475569;
        background: #f8fafc;
    }

    /* Report table */
    .table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        overflow: hidden;
    }

    .table-header {
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-header h5 {
        margin: 0;
        font-size: 20px;
        font-weight: 500;
    }

    .table-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .record-count {
        background: #eff6ff;
        color: #2563eb;
        border-radius: 20px;
        padding: 9px 15px;
        font-size: 13px;
        font-weight: 600;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attendance-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        padding: 16px 18px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
        text-align: left;
    }

    .attendance-table tbody td {
        padding: 17px 18px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .attendance-table tbody tr:hover {
        background: #fafbff;
    }

    .student-name {
        font-weight: 600;
        color: #172033;
    }

    .student-id {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 3px;
    }

    .status-badge {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-present {
        background: #dcfce7;
        color: #15803d;
    }

    .status-absent {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-late {
        background: #fef3c7;
        color: #a16207;
    }

    .status-other {
        background: #f1f5f9;
        color: #475569;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 42px;
        display: block;
        margin-bottom: 12px;
        color: #94a3b8;
    }

    .pagination-wrapper {
        padding: 18px 24px;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 992px) {
        .report-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .report-actions {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .attendance-report-page {
            padding: 15px;
        }

        .report-header {
            padding: 22px;
        }

        .report-header h2 {
            font-size: 22px;
        }

        .filter-card {
            padding: 20px;
        }

        .table-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media print {
        .report-actions,
        .filter-card,
        .pagination-wrapper {
            display: none !important;
        }

        .attendance-report-page {
            padding: 0;
            background: #fff;
        }

        .report-header {
            color: #000;
            background: none;
            padding: 0 0 20px;
        }

        .report-header-icon {
            display: none;
        }

        .table-card {
            border: 1px solid #ddd;
        }
    }
</style>


<div class="attendance-report-page">

    {{-- HEADER --}}
    <div class="report-header">

        <div class="report-header-left">

            <div class="report-header-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div>
                <h2>Attendance Reports</h2>

                <p>
                    Generate and view detailed student attendance information.
                </p>
            </div>

        </div>


        <div class="report-actions">

            {{-- PDF --}}
            <a
                href="{{ route('admin.reports.attendance.pdf', request()->query()) }}"
                class="report-action-btn pdf-btn"
            >
                <i class="bi bi-file-earmark-pdf"></i>
                PDF
            </a>


            {{-- Excel --}}
            <a
                href="{{ route('admin.reports.attendance.excel', request()->query()) }}"
                class="report-action-btn excel-btn"
            >
                <i class="bi bi-file-earmark-excel"></i>
                Excel
            </a>


            {{-- Print --}}
            <button
                type="button"
                onclick="window.print()"
                class="report-action-btn print-btn"
            >
                <i class="bi bi-printer"></i>
                Print
            </button>

        </div>

    </div>


    {{-- FILTERS --}}
    <div class="filter-card">

        <div class="filter-title">

            <div class="filter-title-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>

            <h5>Filter Attendance Reports</h5>

        </div>


        <form method="GET" action="{{ route('admin.reports.attendance') }}">

            <div class="row g-3">

                {{-- Student --}}
                <div class="col-lg-4 col-md-6">

                    <label class="filter-label">
                        Search Student
                    </label>

                    <select
                        name="student_id"
                        id="studentSelect"
                        class="filter-control"
                    >

                        <option value="">
                            All Students
                        </option>

                        @foreach($students as $student)

                            <option
                                value="{{ $student->student_id }}"
                                data-academic-year="{{ $student->academic_year }}"
                                data-class="{{ $student->class }}"
                                data-section="{{ $student->section }}"
                                {{ request('student_id') == $student->student_id ? 'selected' : '' }}
                            >
                                {{ $student->student_id }} -
                                {{ $student->first_name }}
                                {{ $student->last_name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Academic Year --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Academic Year
                    </label>

                    <select
                        name="academic_year"
                        class="filter-control"
                    >

                        <option value="">
                            All Academic Years
                        </option>

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
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Class
                    </label>

                    <select
                        name="class"
                        class="filter-control"
                    >

                        <option value="">
                            All Classes
                        </option>

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
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Section
                    </label>

                    <select
                        name="section"
                        class="filter-control"
                    >

                        <option value="">
                            All Sections
                        </option>

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


                {{-- Status --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="filter-control"
                    >

                        <option value="">
                            All Status
                        </option>

                        @foreach($statuses as $status)

                            <option
                                value="{{ $status }}"
                                {{ request('status') == $status ? 'selected' : '' }}
                            >
                                {{ ucfirst($status) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- From Date --}}
                <div class="col-lg-2 col-md-6">

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
                <div class="col-lg-2 col-md-6">

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


                {{-- Buttons --}}
                <div class="col-lg-4 col-md-8">

                    <label class="filter-label">
                        &nbsp;
                    </label>

                    <div class="filter-buttons">

                        <button
                            type="submit"
                            class="btn-filter"
                        >
                            <i class="bi bi-funnel me-1"></i>
                            Apply Filters
                        </button>


                        <a
                            href="{{ route('admin.reports.attendance') }}"
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

                <h5>Attendance Report</h5>

                <p>
                    Attendance information based on the selected filters.
                </p>

            </div>


            <div class="record-count">
                {{ $attendance->total() }} Records
            </div>

        </div>


        <div class="table-responsive">

            <table class="attendance-table">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($attendance as $record)

                        <tr>

                            <td>
                                {{ $attendance->firstItem() + $loop->index }}
                            </td>


                            <td>

                                @if($record->student)

                                    <div class="student-name">
                                        {{ $record->student->first_name }}
                                        {{ $record->student->last_name }}
                                    </div>

                                    <div class="student-id">
                                        {{ $record->student->student_id }}
                                    </div>

                                @else

                                    <span>
                                        Student not found
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $record->student->student_id ?? '-' }}
                            </td>


                            <td>
                                {{ $record->academic_year ?? '-' }}
                            </td>


                            <td>
                                {{ $record->class ?? '-' }}
                            </td>


                            <td>
                                {{ $record->section ?? '-' }}
                            </td>


                            <td>

                                @if($record->attendance_date)

                                    {{ $record->attendance_date->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @php
                                    $status = strtolower($record->status ?? '');
                                @endphp


                                @if($status === 'present')

                                    <span class="status-badge status-present">
                                        Present
                                    </span>

                                @elseif($status === 'absent')

                                    <span class="status-badge status-absent">
                                        Absent
                                    </span>

                                @elseif($status === 'late')

                                    <span class="status-badge status-late">
                                        Late
                                    </span>

                                @else

                                    <span class="status-badge status-other">
                                        {{ ucfirst($record->status ?? '-') }}
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $record->remarks ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9">

                                <div class="empty-state">

                                    <i class="bi bi-calendar-x"></i>

                                    <div>
                                        No attendance records found.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($attendance->hasPages())

            <div class="pagination-wrapper">
                {{ $attendance->links() }}
            </div>

        @endif

    </div>

</div>


{{-- Student → Academic Year / Class / Section --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const studentSelect =
            document.getElementById('studentSelect');

        const academicYearSelect =
            document.querySelector('select[name="academic_year"]');

        const classSelect =
            document.querySelector('select[name="class"]');

        const sectionSelect =
            document.querySelector('select[name="section"]');


        function setSelectValue(select, value) {

            if (!value) {
                select.value = '';
                return;
            }

            let optionExists = false;

            for (let option of select.options) {

                if (option.value == value) {
                    optionExists = true;
                    break;
                }

            }


            if (!optionExists) {

                const option =
                    document.createElement('option');

                option.value = value;
                option.textContent = value;

                select.appendChild(option);

            }


            select.value = value;

        }


        function updateStudentDetails() {

            const selectedOption =
                studentSelect.options[
                    studentSelect.selectedIndex
                ];


            if (!studentSelect.value) {

                academicYearSelect.value = '';
                classSelect.value = '';
                sectionSelect.value = '';

                return;

            }


            setSelectValue(
                academicYearSelect,
                selectedOption.dataset.academicYear
            );

            setSelectValue(
                classSelect,
                selectedOption.dataset.class
            );

            setSelectValue(
                sectionSelect,
                selectedOption.dataset.section
            );

        }


        studentSelect.addEventListener(
            'change',
            updateStudentDetails
        );


        updateStudentDetails();

    });
</script>

@endsection