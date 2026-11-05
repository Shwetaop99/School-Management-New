@extends('layouts.app')

@section('title', 'Student Attendance Reports | Admin')
@section('page-title', 'Student Attendance Reports')

@section('content')

<div class="payroll-simple student-attendance-page">
    <div class="container-fluid py-4">

        <style>
            .student-attendance-page {
                background: #f5f4f0;
                color: #1e2a25;
                min-height: 100vh;
                padding: 10px;
            }

            .student-attendance-page .back-btn {
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
                transition: .2s ease;
            }

            .student-attendance-page .back-btn:hover {
                background: #f3ead8;
                color: #2c3e36;
            }

            .student-attendance-page .report-hero {
                display: grid;
                grid-template-columns: 1.4fr 1fr;
                background: #fdfbf7;
                border: 1px solid #d4cfc2;
                border-radius: 18px;
                overflow: hidden;
                margin-bottom: 22px;
            }

            .student-attendance-page .hero-main {
                padding: 28px;
            }

            .student-attendance-page .hero-side {
                background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
                padding: 28px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .student-attendance-page .eyebrow {
                color: #5c6b64;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-transform: uppercase;
            }

            .student-attendance-page .hero-title {
                font-size: 28px;
                font-weight: 800;
                color: #1e2a25;
                margin: 10px 0;
            }

            .student-attendance-page .hero-description {
                color: #5c6b64;
                font-size: 13px;
                line-height: 1.8;
                max-width: 650px;
            }

            .student-attendance-page .hero-number {
                font-size: 38px;
                font-weight: 800;
                color: #2c3e36;
                margin: 8px 0;
            }

            .student-attendance-page .section-heading {
                font-size: 17px;
                font-weight: 800;
                color: #1e2a25;
                margin-bottom: 17px;
            }

            .student-attendance-page .class-card {
                display: block;
                height: 100%;
                background: #fff;
                border: 1px solid #d4cfc2;
                border-radius: 15px;
                padding: 21px;
                text-decoration: none;
                color: #1e2a25;
                box-shadow: 0 3px 12px rgba(30, 42, 37, .035);
                transition: .22s ease;
            }

            .student-attendance-page .class-card:hover {
                transform: translateY(-4px);
                border-color: #8d9d8d;
                box-shadow: 0 9px 22px rgba(30, 42, 37, .09);
                color: #1e2a25;
            }

            .student-attendance-page .class-icon {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 13px;
                background: #f3ead8;
                color: #2c3e36;
                font-size: 20px;
                margin-bottom: 19px;
            }

            .student-attendance-page .class-name {
                font-size: 19px;
                font-weight: 800;
                color: #2c3e36;
                margin-bottom: 7px;
            }

            .student-attendance-page .class-count {
                color: #5c6b64;
                font-size: 12px;
            }

            .student-attendance-page .class-action {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-top: 1px solid #eeeae1;
                margin-top: 20px;
                padding-top: 14px;
                color: #2c3e36;
                font-size: 12px;
                font-weight: 800;
            }

            .student-attendance-page .class-arrow {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: #f3ead8;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .student-attendance-page .empty-state {
                background: #fff;
                border: 1px solid #d4cfc2;
                border-radius: 15px;
                padding: 45px 20px;
                text-align: center;
                color: #7b857b;
            }

            .student-attendance-page .empty-icon {
                font-size: 35px;
                color: #9b9f91;
                margin-bottom: 13px;
            }

            @media(max-width: 768px) {
                .student-attendance-page .report-hero {
                    grid-template-columns: 1fr;
                }

                .student-attendance-page .hero-title {
                    font-size: 23px;
                }
            }
        </style>

        {{-- BACK BUTTON --}}
        <a href="{{ route('admin.reports.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Reports
        </a>

        {{-- REPORT HERO --}}
        <div class="report-hero">

            <div class="hero-main">
                <div class="eyebrow">School Management / Reports</div>

                <div class="hero-title">
                    Student Attendance Report
                </div>

                <div class="hero-description">
                    Explore student attendance class-wise.
                    Select a class to view its divisions, academic years
                    and individual student attendance profiles.
                </div>

                <div class="mt-3">
                    <span class="eyebrow">Report Categories</span>

                    <div class="fw-bold mt-1">
                        Class-wise · Division-wise · Student-wise
                    </div>
                </div>
            </div>

            <div class="hero-side">
                <div class="eyebrow">Total Classes</div>

                <div class="hero-number">
                    {{ $classes->count() }}
                </div>

                <div style="font-size:13px;color:#5c6b64;">
                    Classes available for attendance reporting
                </div>
            </div>

        </div>

        {{-- CLASS DASHBOARD --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

            <div>
                <div class="section-heading mb-1">
                    Select a Class
                </div>

                <div style="font-size:12px;color:#7b857b;">
                    Choose a class to explore student attendance records.
                </div>
            </div>

            <div class="eyebrow">
                {{ $classes->count() }} Classes
            </div>

        </div>

        @if($classes->isNotEmpty())

            <div class="row g-3">

                @foreach($classes as $class)

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <a href="{{ route('admin.reports.student-attendance.class', [
                            'class' => $class['value']
                        ]) }}"
                           class="class-card">

                            <div class="class-icon">
                                <i class="fas fa-school"></i>
                            </div>

                            <div class="class-name">
                                {{ $class['label'] }}
                            </div>

                            <div class="class-count">
                                {{ $class['student_count'] }}

                                {{ $class['student_count'] === 1 ? 'Student' : 'Students' }}
                            </div>

                            <div class="class-action">

                                <span>View Class Attendance</span>

                                <span class="class-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </span>

                            </div>

                        </a>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    <i class="fas fa-school"></i>
                </div>

                <h5 style="font-weight:800;color:#2c3e36;">
                    No Classes Available
                </h5>

                <p class="mb-0" style="font-size:13px;">
                    No student classes were found in the system.
                </p>

            </div>

        @endif

    </div>
</div>

@endsection
