
@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')

@php
    $reportModules = [
        [
            'title' => 'Student Reports',
            'description' => 'Student information, profiles and academic records.',
            'route' => 'admin.reports.students',
            'icon' => 'fa-user-graduate',
            'color' => 'student',
        ],
        [
            'title' => 'Faculty Reports',
            'description' => 'Faculty details, allocation and teaching records.',
            'route' => 'admin.teachers.reports.index',
            'icon' => 'fa-chalkboard-teacher',
            'color' => 'faculty',
        ],
        [
            'title' => 'Staff Reports',
            'description' => 'Non-teaching staff and employee information.',
            'route' => 'admin.reports.staff',
            'icon' => 'fa-users',
            'color' => 'staff',
        ],
        [
            'title' => 'Student Attendance Reports',
            'description' => 'Student attendance records and daily summaries.',
            'route' => 'admin.reports.student-attendance',
            'icon' => 'fa-calendar-check',
            'color' => 'attendance',
        ],
        [
            'title' => 'Faculty Attendance Reports',
            'description' => 'Faculty attendance, leave records and overtime.',
            'route' => 'admin.reports.teacher-attendance',
            'icon' => 'fa-user-check',
            'color' => 'faculty-attendance',
        ],
        [
            'title' => 'Fee Reports',
            'description' => 'Fee collection, payments and pending fees.',
            'route' => 'admin.reports.fees',
            'icon' => 'fa-indian-rupee-sign',
            'color' => 'fees',
        ],
        [
            'title' => 'Exam Reports',
            'description' => 'Examination schedules and examination records.',
            'route' => 'admin.reports.exams',
            'icon' => 'fa-file-alt',
            'color' => 'exam',
        ],
        [
            'title' => 'Result Reports',
            'description' => 'Marks, grades and student result summaries.',
            'route' => 'admin.reports.results.index',
            'icon' => 'fa-chart-column',
            'color' => 'result',
        ],
        [
            'title' => 'Library Reports',
            'description' => 'Books, issues, returns, overdue books and fines.',
            'route' => 'admin.library.reports.index',
            'icon' => 'fa-book',
            'color' => 'library',
        ],
        [
            'title' => 'Transport Reports',
            'description' => 'Routes, vehicles, drivers and student transport.',
            'route' => 'admin.reports.transport',
            'icon' => 'fa-bus',
            'color' => 'transport',
        ],
        [
            'title' => 'Meal Reports',
            'description' => 'Meal stock, usage and inventory records.',
            'route' => 'admin.reports.meal',
            'icon' => 'fa-utensils',
            'color' => 'meal',
        ],
        [
            'title' => 'Payroll Reports',
            'description' => 'Salary, payroll and employee payment records.',
            'route' => 'admin.reports.payroll',
            'icon' => 'fa-money-check-dollar',
            'color' => 'payroll',
        ],
        [
            'title' => 'Sports Reports',
            'description' => 'Sports activities and student participation.',
            'route' => 'admin.reports.sports',
            'icon' => 'fa-futbol',
            'color' => 'sports',
        ],
        [
            'title' => 'Scholarship Reports',
            'description' => 'Scholarship applications and student records.',
            'route' => 'admin.reports.scholarship',
            'icon' => 'fa-graduation-cap',
            'color' => 'scholarship',
        ],
        [
            'title' => 'Class Reports',
            'description' => 'Classes, divisions, subjects and class records.',
            'route' => 'admin.class-reports.index',
            'icon' => 'fa-chalkboard',
            'color' => 'class',
        ],
    ];
@endphp

<div class="school-reports-page">
    <div class="container-fluid">

        {{-- HERO SECTION --}}
        <section class="reports-hero">

            <div class="reports-hero-left">
                <div class="reports-hero-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>

                <div class="reports-hero-content">
                    <div class="reports-eyebrow">
                        SCHOOL MANAGEMENT / REPORTS
                    </div>

                    <h2>Reports & Analytics</h2>

                    <p>
                        Generate and view reports for different areas of the
                        school, all organized in one convenient place.
                    </p>

                    <div class="reports-hero-meta">
                        <span>
                            <i class="fas fa-layer-group"></i>
                            Centralized reporting
                        </span>

                        <span class="meta-divider"></span>

                        <span>
                            <i class="fas fa-chart-line"></i>
                            Academic & administrative insights
                        </span>
                    </div>
                </div>
            </div>

            <div class="reports-hero-right">
                <div class="hero-decoration hero-decoration-one"></div>
                <div class="hero-decoration hero-decoration-two"></div>

                <div class="hero-illustration">
                    <div class="illustration-top">
                        <span class="illustration-dot"></span>
                        <span class="illustration-dot"></span>
                        <span class="illustration-dot"></span>
                        <span class="illustration-label">REPORT OVERVIEW</span>
                    </div>

                    <div class="illustration-chart">
                        <div class="chart-bar chart-bar-one"></div>
                        <div class="chart-bar chart-bar-two"></div>
                        <div class="chart-bar chart-bar-three"></div>
                        <div class="chart-bar chart-bar-four"></div>
                        <div class="chart-bar chart-bar-five"></div>
                    </div>

                    <div class="illustration-footer">
                        <div class="illustration-check">
                            <i class="fas fa-check"></i>
                        </div>

                        <div>
                            <strong>Everything in one place</strong>
                            <span>Organized school records</span>
                        </div>
                    </div>
                </div>

                <div class="hero-caption">
                    Clear records. Better decisions.
                </div>
            </div>

        </section>

        {{-- SECTION HEADING --}}
        <div class="reports-section-header">
            <div class="reports-section-title">
                <span class="section-icon">
                    <i class="fas fa-folder-open"></i>
                </span>

                <div>
                    <h3>Explore Reports</h3>
                    <p>Choose a module to access its reports and records.</p>
                </div>
            </div>

            <div class="reports-count">
                <span class="count-number">{{ count($reportModules) }}</span>
                <span class="count-label">Available Modules</span>
            </div>
        </div>

        {{-- REPORT CARDS --}}
        <div class="reports-grid">

            @foreach($reportModules as $module)
                <a href="{{ route($module['route']) }}"
                   class="report-card">

                    <div class="report-icon {{ $module['color'] }}-icon">
                        <i class="fas {{ $module['icon'] }}"></i>
                    </div>

                    <div class="report-info">
                        <h3>{{ $module['title'] }}</h3>
                        <p>{{ $module['description'] }}</p>
                    </div>

                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>

                </a>
            @endforeach

        </div>

        {{-- BOTTOM INFORMATION --}}
        <div class="reports-footer-note">
            <div class="footer-note-icon">
                <i class="fas fa-lightbulb"></i>
            </div>

            <div>
                <strong>Your school information, organized.</strong>
                <p>
                    Select any report module above to access its available
                    records and reporting tools.
                </p>
            </div>
        </div>

    </div>
</div>

<style>
/* =====================================================
   SCHOOL REPORTS - MODERN DASHBOARD DESIGN
===================================================== */

.school-reports-page {
    --primary: #365b49;
    --primary-dark: #294637;
    --primary-light: #eaf2eb;
    --text: #26352d;
    --muted: #748078;
    --border: #e5eae5;
    --surface: #ffffff;
    --background: #f6f7f5;

    min-height: calc(100vh - 70px);
    padding-bottom: 30px;
    background: var(--background);
    color: var(--text);
    font-family: Inter, "Public Sans", Arial, sans-serif;
}

.school-reports-page *,
.school-reports-page *::before,
.school-reports-page *::after {
    box-sizing: border-box;
}

.school-reports-page .container-fluid {
    width: 100%;
    max-width: 1450px;
    margin: 0 auto;
    padding: 28px 26px;
}

/* HERO */

.school-reports-page .reports-hero {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    overflow: hidden;
    margin-bottom: 35px;
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--surface);
    box-shadow: 0 5px 20px rgba(35, 60, 45, .04);
}

.school-reports-page .reports-hero-left {
    display: flex;
    align-items: flex-start;
    gap: 22px;
    padding: 40px 35px;
    background: linear-gradient(135deg, #fff, #f5f9f5);
}

.school-reports-page .reports-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    flex: 0 0 60px;
    border-radius: 14px;
    background: #dce9df;
    color: var(--primary);
    font-size: 24px;
}

.school-reports-page .reports-hero-content {
    min-width: 0;
}

.school-reports-page .reports-eyebrow {
    margin-bottom: 10px;
    color: #718b79;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.school-reports-page .reports-hero-content h2 {
    margin: 0 0 12px;
    color: var(--text);
    font-size: 30px;
    font-weight: 750;
    line-height: 1.25;
    letter-spacing: -.7px;
}

.school-reports-page .reports-hero-content > p {
    max-width: 520px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.school-reports-page .reports-hero-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 23px;
    color: #536b5c;
    font-size: 11px;
    font-weight: 600;
}

.school-reports-page .reports-hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.school-reports-page .reports-hero-meta i {
    color: var(--primary);
}

.school-reports-page .reports-hero-meta .meta-divider {
    width: 1px;
    height: 14px;
    background: var(--border);
}

/* HERO ILLUSTRATION */

.school-reports-page .reports-hero-right {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    min-width: 0;
    overflow: hidden;
    padding: 30px;
    border-left: 1px solid #e6eee7;
    background: linear-gradient(145deg, #eaf2eb, #dce9df);
}

.school-reports-page .hero-decoration {
    position: absolute;
    border: 1px solid rgba(54, 91, 73, .12);
    border-radius: 50%;
    pointer-events: none;
}

.school-reports-page .hero-decoration-one {
    top: -95px;
    right: -55px;
    width: 240px;
    height: 240px;
}

.school-reports-page .hero-decoration-two {
    right: -20px;
    bottom: -130px;
    width: 230px;
    height: 230px;
}

.school-reports-page .hero-illustration {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 320px;
    padding: 18px 20px;
    border: 1px solid rgba(54, 91, 73, .12);
    border-radius: 13px;
    background: rgba(255, 255, 255, .9);
    box-shadow: 0 12px 30px rgba(35, 60, 45, .08);
}

.school-reports-page .illustration-top {
    display: flex;
    align-items: center;
    gap: 6px;
    padding-bottom: 13px;
    border-bottom: 1px solid #edf1ed;
}

.school-reports-page .illustration-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #a8b9ac;
}

.school-reports-page .illustration-label {
    margin-left: auto;
    color: #718477;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1px;
}

.school-reports-page .illustration-chart {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 13px;
    height: 95px;
    padding: 14px 12px 0;
    border-bottom: 1px solid #e8eee8;
    background: repeating-linear-gradient(
        to bottom,
        transparent 0,
        transparent 22px,
        rgba(54, 91, 73, .06) 23px,
        transparent 24px
    );
}

.school-reports-page .chart-bar {
    width: 27px;
    max-width: 18%;
    border-radius: 5px 5px 0 0;
    background: #718e79;
    transform-origin: bottom;
    animation: reportsBarRise .65s ease-out both;
}

.school-reports-page .chart-bar-one {
    height: 38%;
    background: #b6cbb9;
}

.school-reports-page .chart-bar-two {
    height: 62%;
    background: #8cae96;
}

.school-reports-page .chart-bar-three {
    height: 48%;
    background: #a6bea9;
}

.school-reports-page .chart-bar-four {
    height: 85%;
    background: #416c53;
}

.school-reports-page .chart-bar-five {
    height: 70%;
    background: #789982;
}

@keyframes reportsBarRise {
    from {
        transform: scaleY(.15);
        opacity: .4;
    }

    to {
        transform: scaleY(1);
        opacity: 1;
    }
}

.school-reports-page .illustration-footer {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-top: 14px;
}

.school-reports-page .illustration-check {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #e5f1e7;
    color: #3f7650;
    font-size: 12px;
}

.school-reports-page .illustration-footer strong,
.school-reports-page .illustration-footer span {
    display: block;
}

.school-reports-page .illustration-footer strong {
    color: #344b3b;
    font-size: 10px;
    font-weight: 750;
}

.school-reports-page .illustration-footer span {
    margin-top: 3px;
    color: #829087;
    font-size: 9px;
}

.school-reports-page .hero-caption {
    position: relative;
    z-index: 1;
    color: #54735e;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
}

/* SECTION HEADING */

.school-reports-page .reports-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.school-reports-page .reports-section-title {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.school-reports-page .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    border: 1px solid var(--border);
    border-radius: 11px;
    background: #fff;
    color: var(--primary);
    font-size: 18px;
}

.school-reports-page .reports-section-title h3 {
    margin: 0 0 5px;
    color: var(--text);
    font-size: 19px;
    font-weight: 750;
}

.school-reports-page .reports-section-title p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.6;
}

.school-reports-page .reports-count {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    padding: 9px 14px;
    border: 1px solid #dce8de;
    border-radius: 9px;
    background: #f0f6f1;
}

.school-reports-page .count-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: var(--primary);
    color: #fff;
    font-size: 13px;
    font-weight: 750;
}

.school-reports-page .count-label {
    color: #536c5b;
    font-size: 11px;
    font-weight: 650;
}

/* REPORT GRID */

.school-reports-page .reports-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

/* REPORT CARDS */

.school-reports-page .report-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    min-height: 132px;
    padding: 22px 19px;
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 13px;
    background: var(--surface);
    color: var(--text);
    text-decoration: none;
    box-shadow: 0 3px 12px rgba(35, 60, 45, .025);
    transition: transform .22s ease,
                box-shadow .22s ease,
                border-color .22s ease;
}

.school-reports-page .report-card::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 4px;
    background: var(--primary);
    content: "";
    opacity: 0;
    transition: opacity .2s ease;
}

.school-reports-page .report-card:hover {
    transform: translateY(-4px);
    border-color: #b9d0be;
    background: #fff;
    box-shadow: 0 10px 25px rgba(35, 60, 45, .09);
    color: var(--text);
    text-decoration: none;
}

.school-reports-page .report-card:hover::before {
    opacity: 1;
}

.school-reports-page .report-card:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 3px;
}

.school-reports-page .report-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    flex: 0 0 52px;
    border: 1px solid #e1e9e2;
    border-radius: 12px;
    background: #edf4ee;
    color: var(--primary);
    font-size: 20px;
    transition: transform .22s ease;
}

.school-reports-page .report-card:hover .report-icon {
    transform: scale(1.07);
}

.school-reports-page .report-info {
    flex: 1;
    min-width: 0;
}

.school-reports-page .report-info h3 {
    margin: 0;
    color: #2c3c32;
    font-size: 14px;
    font-weight: 750;
    line-height: 1.5;
}

.school-reports-page .report-info p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 11.5px;
    line-height: 1.7;
}

.school-reports-page .report-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    border: 1px solid #e5ebe5;
    border-radius: 8px;
    background: #f7f9f7;
    color: #748379;
    font-size: 11px;
    transition: transform .2s ease,
                background .2s ease,
                color .2s ease;
}

.school-reports-page .report-card:hover .report-arrow {
    transform: translateX(3px);
    border-color: var(--primary);
    background: var(--primary);
    color: #fff;
}

/* INDIVIDUAL ICON COLORS */

.school-reports-page .student-icon {
    background: #e8f2eb;
    border-color: #d9e8dc;
    color: #3f7650;
}

.school-reports-page .faculty-icon {
    background: #edf0f8;
    border-color: #dfe4f0;
    color: #586b9a;
}

.school-reports-page .staff-icon {
    background: #f0ebf7;
    border-color: #e4dcef;
    color: #8063a1;
}

.school-reports-page .attendance-icon,
.school-reports-page .faculty-attendance-icon {
    background: #e8f3f2;
    border-color: #d6e9e6;
    color: #397d75;
}

.school-reports-page .fees-icon {
    background: #f5f0df;
    border-color: #ece2c3;
    color: #94752f;
}

.school-reports-page .exam-icon {
    background: #f8ece8;
    border-color: #f0dcd5;
    color: #a65d48;
}

.school-reports-page .result-icon {
    background: #eaf0e6;
    border-color: #dce7d5;
    color: #607d4d;
}

.school-reports-page .library-icon {
    background: #f4eee3;
    border-color: #eae0cc;
    color: #927344;
}

.school-reports-page .transport-icon {
    background: #e9eff5;
    border-color: #dce5ef;
    color: #52769a;
}

.school-reports-page .meal-icon {
    background: #f8f0e4;
    border-color: #eee1ca;
    color: #a47b3c;
}

.school-reports-page .payroll-icon {
    background: #e6f2eb;
    border-color: #d4e8db;
    color: #3c8055;
}

.school-reports-page .sports-icon {
    background: #f8eae9;
    border-color: #efd9d7;
    color: #a95750;
}

.school-reports-page .scholarship-icon {
    background: #f0edf8;
    border-color: #e2dcf0;
    color: #7866a4;
}

.school-reports-page .class-icon {
    background: #e9f0e9;
    border-color: #dbe7dc;
    color: #527457;
}

/* BOTTOM INFORMATION */

.school-reports-page .reports-footer-note {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-top: 26px;
    padding: 19px 21px;
    border: 1px solid #dce8de;
    border-radius: 12px;
    background: #f0f6f1;
}

.school-reports-page .footer-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    border-radius: 9px;
    background: #deebdf;
    color: var(--primary);
    font-size: 15px;
}

.school-reports-page .reports-footer-note strong {
    display: block;
    margin-bottom: 5px;
    color: #344d3b;
    font-size: 12px;
    font-weight: 750;
}

.school-reports-page .reports-footer-note p {
    margin: 0;
    color: #708076;
    font-size: 12px;
    line-height: 1.7;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .school-reports-page .reports-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .school-reports-page .reports-hero {
        grid-template-columns: 1.3fr 1fr;
    }

    .school-reports-page .reports-hero-left {
        padding: 30px 24px;
    }

    .school-reports-page .reports-hero-content h2 {
        font-size: 26px;
    }
}

@media (max-width: 760px) {
    .school-reports-page .container-fluid {
        padding: 20px 16px;
    }

    .school-reports-page .reports-hero {
        grid-template-columns: 1fr;
    }

    .school-reports-page .reports-hero-left {
        padding: 26px 22px;
    }

    .school-reports-page .reports-hero-right {
        min-height: 250px;
        border-top: 1px solid #dce8de;
        border-left: none;
    }

    .school-reports-page .reports-hero-content h2 {
        font-size: 24px;
    }

    .school-reports-page .report-card {
        min-height: 120px;
        padding: 17px 15px;
    }
}

@media (max-width: 540px) {
    .school-reports-page .container-fluid {
        padding: 16px 12px;
    }

    .school-reports-page .reports-hero-left {
        gap: 14px;
        padding: 23px 16px;
    }

    .school-reports-page .reports-hero-icon {
        width: 46px;
        height: 46px;
        flex-basis: 46px;
        font-size: 19px;
    }

    .school-reports-page .reports-eyebrow {
        font-size: 9px;
        letter-spacing: 1px;
    }

    .school-reports-page .reports-hero-content h2 {
        font-size: 21px;
    }

    .school-reports-page .reports-hero-content > p {
        font-size: 12px;
    }

    .school-reports-page .reports-hero-meta {
        align-items: flex-start;
        flex-direction: column;
        gap: 9px;
        font-size: 10px;
    }

    .school-reports-page .reports-hero-meta .meta-divider {
        display: none;
    }

    .school-reports-page .reports-hero-right {
        padding: 24px 15px;
    }

    .school-reports-page .reports-section-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 13px;
    }

    .school-reports-page .reports-section-title h3 {
        font-size: 17px;
    }

    .school-reports-page .reports-count {
        align-self: flex-start;
    }

    .school-reports-page .reports-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .school-reports-page .report-card {
        min-height: 110px;
        padding: 16px;
    }

    .school-reports-page .report-icon {
        width: 45px;
        height: 45px;
        flex-basis: 45px;
        font-size: 18px;
    }

    .school-reports-page .report-info h3 {
        font-size: 13px;
    }

    .school-reports-page .report-info p {
        font-size: 11px;
    }

    .school-reports-page .reports-footer-note {
        padding: 15px;
    }
}

/* ACCESSIBILITY */

@media (prefers-reduced-motion: reduce) {
    .school-reports-page *,
    .school-reports-page *::before,
    .school-reports-page *::after {
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection
