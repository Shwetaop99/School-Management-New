
@extends('layouts.app')

@section('title', 'Student Reports | Admin')
@section('page-title', 'Student Reports')

@section('content')

<div class="payroll-simple student-reports-page">

    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="student-report-topbar">
            <a href="{{ route('admin.reports.index') }}" class="student-back-link">
                <span>&larr;</span> Back to Reports
            </a>

            <button type="button" class="student-print-btn" onclick="window.print()">
                <span>⎙</span> Print
            </button>
        </div>

        {{-- Hero Section --}}
        <section class="student-report-hero">
            <div class="student-report-hero-content">
                <span class="student-report-eyebrow">ACADEMIC RECORDS</span>

                <h2>Student Reports</h2>

                <p>
                    Select a class to explore sections, academic years
                    and individual student reports.
                </p>
            </div>

            <div class="student-report-hero-art">
                <div class="student-hero-icon">▤</div>
                <span>STUDENT DIRECTORY</span>
                <strong>Class Overview</strong>
            </div>
        </section>

        {{-- Class Overview --}}
        <section class="student-class-section">

            <div class="student-section-heading">
                <div>
                    <span class="student-report-eyebrow">EXPLORE RECORDS</span>
                    <h3>Select a Class</h3>
                    <p>Choose a class to view its student records.</p>
                </div>
            </div>

            @if($classes->isNotEmpty())

                <div class="student-class-grid">

                    @foreach($classes as $class)

                        <a
                            href="{{ route('admin.reports.students.class', ['class' => $class]) }}"
                            class="student-class-card"
                        >
                            <div class="student-class-card-icon">
                                <span>▤</span>
                            </div>

                            <div class="student-class-card-content">
                                <span class="student-class-label">CLASS</span>
                                <h4>{{ $class }}</h4>
                                <span class="student-class-action">
                                    View Students <span>&rarr;</span>
                                </span>
                            </div>
                        </a>

                    @endforeach

                </div>

            @else

                <div class="student-empty-state">
                    <div class="student-empty-icon">▤</div>
                    <h4>No Classes Found</h4>
                    <p>
                        No student class records are currently available.
                        Please check the student records.
                    </p>
                </div>

            @endif

        </section>

        {{-- Print School Header --}}
        <div class="student-print-school print-only">
            @if(!empty($schoolSetting?->logo_url))
                <img src="{{ $schoolSetting->logo_url }}" alt="School Logo">
            @endif

            <div>
                <h2>{{ $schoolSetting->school_name ?? 'School Name' }}</h2>

                <p>
                    {{ $schoolSetting->address ?? '' }}
                    {{ $schoolSetting->city ?? '' }}
                    {{ $schoolSetting->district ?? '' }}
                    {{ $schoolSetting->state ?? '' }}
                    {{ $schoolSetting->pincode ?? '' }}
                </p>

                <p>
                    {{ $schoolSetting->phone ?? '' }}
                    @if(!empty($schoolSetting?->email))
                        | {{ $schoolSetting->email }}
                    @endif
                </p>
            </div>
        </div>

    </div>
</div>

<style>
/* =========================================================
   STUDENT REPORTS - PAYROLL DESIGN SYSTEM
========================================================= */

.payroll-simple.student-reports-page {
    background: #f5f4f0;
    color: #1e2a25;
    min-height: 100vh;
    padding-bottom: 35px;
    font-family: 'Inter', 'Public Sans', Arial, sans-serif;
}

.student-reports-page * {
    box-sizing: border-box;
}

.student-report-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 22px;
}

.student-back-link {
    color: #526158;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
}

.student-back-link:hover {
    color: #1a6b3c;
}

.student-back-link span {
    margin-right: 6px;
}

.student-print-btn {
    border: 1px solid #d4cfc2;
    background: #fff;
    color: #2c3e36;
    border-radius: 9px;
    padding: 10px 17px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}

.student-print-btn:hover {
    background: #2c3e36;
    color: #fff;
}

/* Hero */

.student-report-hero {
    display: grid;
    grid-template-columns: 1.35fr 0.65fr;
    min-height: 225px;
    overflow: hidden;
    border: 1px solid #d4cfc2;
    border-radius: 17px;
    background: #fdfbf7;
    margin-bottom: 32px;
}

.student-report-hero-content {
    padding: 38px 40px;
}

.student-report-eyebrow {
    display: inline-block;
    color: #8b795b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 12px;
}

.student-report-hero h2 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 34px;
    font-weight: 700;
    margin: 0 0 12px;
}

.student-report-hero-content p {
    color: #5c6b64;
    font-size: 14px;
    line-height: 1.7;
    max-width: 560px;
    margin: 0;
}

.student-report-hero-art {
    background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 25px;
    color: #2c3e36;
}

.student-hero-icon {
    width: 65px;
    height: 65px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    background: #fffefa;
    color: #527f70;
    font-size: 34px;
    margin-bottom: 13px;
    box-shadow: 0 5px 18px rgba(44, 62, 54, 0.08);
}

.student-report-hero-art span {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2px;
    color: #8b795b;
}

.student-report-hero-art strong {
    font-family: Georgia, serif;
    font-size: 21px;
    margin-top: 5px;
}

/* Class section */

.student-class-section {
    background: #fffefa;
    border: 1px solid #d4cfc2;
    border-radius: 16px;
    padding: 28px;
}

.student-section-heading {
    margin-bottom: 24px;
}

.student-section-heading h3 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 25px;
    margin: 0 0 7px;
}

.student-section-heading p {
    color: #5c6b64;
    font-size: 13px;
    margin: 0;
}

/* Class cards */

.student-class-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px;
}

.student-class-card {
    display: flex;
    align-items: center;
    gap: 16px;
    min-height: 125px;
    padding: 20px;
    border: 1px solid #e5e0d5;
    border-radius: 13px;
    background: #fff;
    text-decoration: none;
    transition: all 0.2s ease;
}

.student-class-card:hover {
    border-color: #8ca899;
    background: #fcfdf9;
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(44, 62, 54, 0.07);
}

.student-class-card-icon {
    width: 53px;
    height: 53px;
    min-width: 53px;
    border-radius: 13px;
    background: #f3ead8;
    display: grid;
    place-items: center;
    color: #527f70;
    font-size: 25px;
}

.student-class-card-content {
    min-width: 0;
}

.student
.student-class-card-content {
    flex: 1;
}

.student-class-label {
    display: block;
    color: #8b795b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    margin-bottom: 5px;
}

.student-class-card-content h4 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 23px;
    font-weight: 700;
    margin: 0 0 10px;
}

.student-class-action {
    color: #527f70;
    font-size: 12px;
    font-weight: 700;
}

.student-class-action span {
    margin-left: 4px;
}

.student-empty-state {
    text-align: center;
    padding: 55px 20px;
    border: 1px dashed #d4cfc2;
    border-radius: 13px;
    background: #fdfbf7;
}

.student-empty-icon {
    font-size: 38px;
    color: #527f70;
    margin-bottom: 12px;
}

.student-empty-state h4 {
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 21px;
    margin-bottom: 8px;
}

.student-empty-state p {
    color: #5c6b64;
    font-size: 13px;
    margin: 0;
}

.print-only {
    display: none;
}

/* Responsive */

@media (max-width: 1100px) {
    .student-class-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .student-report-hero {
        grid-template-columns: 1fr;
    }

    .student-report-hero-content {
        padding: 28px 22px;
    }

    .student-report-hero-art {
        min-height: 170px;
    }

    .student-class-grid {
        grid-template-columns: 1fr;
    }

    .student-class-section {
        padding: 20px;
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
    .student-report-topbar,
    .student-report-hero-art {
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

    .payroll-simple.student-reports-page {
        background: #fff !important;
        padding: 0 !important;
    }

    .student-reports-page .container-fluid {
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .print-only {
        display: flex !important;
    }

    .student-print-school {
        align-items: center;
        justify-content: center;
        gap: 18px;
        text-align: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #2c3e36;
        padding-bottom: 15px;
    }

    .student-print-school img {
        width: 65px;
        height: 65px;
        object-fit: contain;
    }

    .student-print-school h2 {
        font-family: Georgia, serif;
        color: #1e2a25;
        margin: 0 0 5px;
    }

    .student-print-school p {
        color: #5c6b64;
        font-size: 11px;
        margin: 3px 0;
    }

    .student-report-hero {
        display: block;
        min-height: auto;
        border: none;
        margin-bottom: 20px;
    }

    .student-report-hero-content {
        padding: 10px 0;
    }

    .student-class-section {
        border: none;
        padding: 0;
    }

    .student-class-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .student-class-card {
        break-inside: avoid;
        box-shadow: none !important;
        transform: none !important;
    }
}
</style>

</div>
</div>

@endsection
