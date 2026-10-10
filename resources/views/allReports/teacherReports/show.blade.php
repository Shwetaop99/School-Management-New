@extends('layouts.app')

@section('title', 'Teacher Report')

@section('content')

<style>
    .report-container {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
    }

    .report-header {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        border-radius: 18px;
        padding: 28px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        box-shadow: 0 8px 25px rgba(20, 124, 245, 0.18);
    }

    .report-header h2 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 700;
    }

    .report-header p {
        margin: 0;
        opacity: .9;
    }

    .report-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .report-btn {
        border: none;
        text-decoration: none;
        padding: 11px 17px;
        border-radius: 9px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: .2s ease;
    }

    .report-btn:hover {
        transform: translateY(-1px);
        opacity: .92;
    }

    .btn-back {
        background: #fff;
        color: #147cf5;
    }

    .btn-pdf {
        background: #dc2626;
        color: #fff;
    }

    .btn-excel {
        background: #16a34a;
        color: #fff;
    }

    .btn-print {
        background: rgba(255,255,255,.18);
        color: #fff;
        border: 1px solid rgba(255,255,255,.35);
    }

    .report-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 22px;
        box-shadow: 0 5px 20px rgba(0,0,0,.06);
    }

    .teacher-profile {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .teacher-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e8f1ff;
    }

    .teacher-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #e8f1ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
    }

    .teacher-info h3 {
        margin: 0 0 7px;
        font-size: 24px;
        color: #1f2937;
    }

    .teacher-info p {
        margin: 4px 0;
        color: #64748b;
    }

    .status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        margin-top: 7px;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .section-title {
        font-size: 19px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .info-item {
        background: #f8fafc;
        border-radius: 10px;
        padding: 15px;
    }

    .info-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .info-value {
        color: #1f2937;
        font-size: 15px;
        font-weight: 600;
        word-break: break-word;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .summary-card {
        padding: 22px;
        border-radius: 14px;
        background: #f8fafc;
        text-align: center;
    }

    .summary-number {
        font-size: 30px;
        font-weight: 800;
        color: #147cf5;
        margin-bottom: 5px;
    }

    .summary-label {
        color: #64748b;
        font-weight: 600;
    }

    .coming-soon {
        text-align: center;
        padding: 25px;
        color: #64748b;
        background: #f8fafc;
        border-radius: 12px;
    }

    @media (max-width: 768px) {

        .report-container {
            padding: 15px;
        }

        .report-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-grid,
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .teacher-profile {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media print {

        .report-actions {
            display: none !important;
        }

        .report-container {
            background: #fff;
            padding: 0;
        }

        .report-card {
            box-shadow: none;
        }
    }
</style>


<div class="report-container">

    {{-- HEADER --}}
    <div class="report-header">

        <div>
            <h2>Teacher Report</h2>

            <p>
                Detailed information and report for the selected teacher.
            </p>
        </div>


        <div class="report-actions">

            {{-- BACK --}}
            <a href="{{ route('admin.teachers.reports.index') }}"
               class="report-btn btn-back">
                ← Back
            </a>


            {{-- PDF --}}
            <a href="{{ route('admin.teachers.reports.pdf', $teacher->id) }}"
               class="report-btn btn-pdf">
                📄 PDF
            </a>


            {{-- EXCEL --}}
            <a href="{{ route('admin.teachers.reports.excel', $teacher->id) }}"
               class="report-btn btn-excel">
                📊 Excel
            </a>


            {{-- PRINT --}}
            <button type="button"
                    class="report-btn btn-print"
                    onclick="window.print()">
                🖨 Print
            </button>

        </div>

    </div>


    {{-- TEACHER PROFILE --}}
    <div class="report-card">

        <div class="teacher-profile">

            @if($teacher->profile_image)

                <img
                    src="{{ $teacher->profile_image }}"
                    alt="Teacher"
                    class="teacher-image"
                >

            @else

                <div class="teacher-placeholder">
                    {{ strtoupper(substr($teacher->first_name, 0, 1)) }}
                </div>

            @endif


            <div class="teacher-info">

                <h3>
                    {{ $teacher->first_name }}
                    {{ $teacher->last_name }}
                </h3>

                <p>
                    Teacher ID:
                    <strong>{{ $teacher->teacher_id }}</strong>
                </p>


                @if($teacher->status === 'Active')

                    <span class="status status-active">
                        Active
                    </span>

                @else

                    <span class="status status-inactive">
                        Inactive
                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- PERSONAL INFORMATION --}}
    <div class="report-card">

        <h3 class="section-title">
            Personal Information
        </h3>


        <div class="info-grid">

            <div class="info-item">

                <span class="info-label">
                    First Name
                </span>

                <span class="info-value">
                    {{ $teacher->first_name }}
                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Last Name
                </span>

                <span class="info-value">
                    {{ $teacher->last_name }}
                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Gender
                </span>

                <span class="info-value">
                    {{ $teacher->gender ?? 'Not Provided' }}
                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Date of Birth
                </span>

                <span class="info-value">

                    @if($teacher->date_of_birth)

                        {{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') }}

                    @else

                        Not Provided

                    @endif

                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Qualification
                </span>

                <span class="info-value">
                    {{ $teacher->qualification ?? 'Not Provided' }}
                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Joining Date
                </span>

                <span class="info-value">

                    @if($teacher->joining_date)

                        {{ \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') }}

                    @else

                        Not Provided

                    @endif

                </span>

            </div>

        </div>

    </div>


    {{-- CONTACT INFORMATION --}}
    <div class="report-card">

        <h3 class="section-title">
            Contact Information
        </h3>


        <div class="info-grid">

            <div class="info-item">

                <span class="info-label">
                    Email
                </span>

                <span class="info-value">
                    {{ $teacher->email }}
                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Phone
                </span>

                <span class="info-value">
                    {{ $teacher->phone ?? 'Not Provided' }}
                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Address
                </span>

                <span class="info-value">
                    {{ $teacher->address ?? 'Not Provided' }}
                </span>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Teacher ID
                </span>

                <span class="info-value">
                    {{ $teacher->teacher_id }}
                </span>

            </div>

        </div>

    </div>


    {{-- REPORT SUMMARY --}}
    <div class="report-card">

        <h3 class="section-title">
            Report Summary
        </h3>


        <div class="summary-grid">

            <div class="summary-card">

                <div class="summary-number">
                    0
                </div>

                <div class="summary-label">
                    Attendance Records
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-number">
                    0
                </div>

                <div class="summary-label">
                    Salary Records
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-number">
                    0
                </div>

                <div class="summary-label">
                    Timetable Entries
                </div>

            </div>

        </div>

    </div>


    {{-- ADDITIONAL REPORTS --}}
    <div class="report-card">

        <h3 class="section-title">
            Additional Reports
        </h3>


        <div class="coming-soon">

            Additional teacher reports such as attendance,
            salary and timetable summaries will be available here.

        </div>

    </div>

</div>

@endsection