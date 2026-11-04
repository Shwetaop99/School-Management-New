@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')

<div class="school-reports-page">
    <div class="container-fluid py-4">

        {{-- PAGE HERO --}}
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
                    <p>
                        Choose a module to access its reports and records.
                    </p>
                </div>
            </div>

            <div class="reports-count">
                <span class="count-number">15</span>
                <span class="count-label">Available Modules</span>
            </div>
        </div>

        {{-- REPORT CARDS --}}
        <div class="reports-grid">

            {{-- 1. STUDENT --}}
            <a href="{{ route('admin.reports.students') }}" class="report-card">

                <div class="report-icon student-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>

                <div class="report-info">
                    <h3>Student Reports</h3>
                    <p>
                        Student information, profiles and academic records.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 2. FACULTY --}}
            <a href="{{ route('admin.teachers.reports.index') }}" class="report-card">

                <div class="report-icon faculty-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>

                <div class="report-info">
                    <h3>Faculty Reports</h3>
                    <p>
                        Faculty details, allocation and teaching records.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 3. STAFF --}}
            <a href="{{ route('admin.reports.staff') }}" class="report-card">

                <div class="report-icon staff-icon">
                    <i class="fas fa-users"></i>
                </div>

                <div class="report-info">
                    <h3>Staff Reports</h3>
                    <p>
                        Non-teaching staff and employee information.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 4. STUDENT ATTENDANCE --}}
            <a href="{{ route('admin.reports.student-attendance') }}" class="report-card">

                <div class="report-icon attendance-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <div class="report-info">
                    <h3>Student Attendance Reports</h3>
                    <p>
                        Student attendance records, daily summaries and reports.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 5. FACULTY ATTENDANCE --}}
            <a href="{{ route('admin.reports.teacher-attendance') }}" class="report-card">

                <div class="report-icon faculty-attendance-icon">
                    <i class="fas fa-user-check"></i>
                </div>

                <div class="report-info">
                    <h3>Faculty Attendance Reports</h3>
                    <p>
                        Faculty attendance, leave records, overtime and individual profiles.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 6. FEES --}}
            <a href="{{ route('admin.reports.fees') }}" class="report-card">

                <div class="report-icon fees-icon">
                    <i class="fas fa-indian-rupee-sign"></i>
                </div>

                <div class="report-info">
                    <h3>Fee Reports</h3>
                    <p>
                        Fee collection, payments and pending fees.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 7. EXAM --}}
            <a href="{{ route('admin.reports.exams') }}" class="report-card">

                <div class="report-icon exam-icon">
                    <i class="fas fa-file-alt"></i>
                </div>

                <div class="report-info">
                    <h3>Exam Reports</h3>
                    <p>
                        Examination schedules and examination records.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 8. RESULT --}}
            <a href="{{ route('admin.reports.results.index') }}" class="report-card">

                <div class="report-icon result-icon">
                    <i class="fas fa-chart-column"></i>
                </div>

                <div class="report-info">
                    <h3>Result Reports</h3>
                    <p>
                        Marks, grades and student result summaries.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 9. LIBRARY --}}
            <a href="{{ route('admin.library.reports.index') }}" class="report-card">

                <div class="report-icon library-icon">
                    <i class="fas fa-book"></i>
                </div>

                <div class="report-info">
                    <h3>Library Reports</h3>
                    <p>
                        Books, issues, returns, overdue books and fines.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 10. TRANSPORT --}}
            <a href="{{ route('admin.reports.transport') }}" class="report-card">

                <div class="report-icon transport-icon">
                    <i class="fas fa-bus"></i>
                </div>

                <div class="report-info">
                    <h3>Transport Reports</h3>
                    <p>
                        Routes, vehicles, drivers and student transport.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 11. MEAL --}}
            <a href="{{ route('admin.reports.meal') }}" class="report-card">

                <div class="report-icon meal-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div class="report-info">
                    <h3>Meal Reports</h3>
                    <p>
                        Meal stock, usage and inventory records.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 12. PAYROLL --}}
            <a href="{{ route('admin.reports.payroll') }}" class="report-card">

                <div class="report-icon payroll-icon">
                    <i class="fas fa-money-check-dollar"></i>
                </div>

                <div class="report-info">
                    <h3>Payroll Reports</h3>
                    <p>
                        Salary, payroll and employee payment records.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 13. SPORTS --}}
            <a href="{{ route('admin.reports.sports') }}" class="report-card">

                <div class="report-icon sports-icon">
                    <i class="fas fa-futbol"></i>
                </div>

                <div class="report-info">
                    <h3>Sports Reports</h3>
                    <p>
                        Sports activities and student participation.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 14. SCHOLARSHIP --}}
            <a href="{{ route('admin.reports.scholarship') }}" class="report-card">

                <div class="report-icon scholarship-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>

                <div class="report-info">
                    <h3>Scholarship Reports</h3>
                    <p>
                        Scholarship applications and student records.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

            {{-- 15. CLASS --}}
            <a href="{{ route('admin.class-reports.index') }}" class="report-card">

                <div class="report-icon class-icon">
                    <i class="fas fa-chalkboard"></i>
                </div>

                <div class="report-info">
                    <h3>Class Reports</h3>
                    <p>
                        Classes, divisions, subjects and class records.
                    </p>
                </div>

                <div class="report-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>

            </a>

        </div>

        {{-- BOTTOM INFORMATION STRIP --}}
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
/* =========================================================
   SCHOOL REPORTS — LOCKED PAYROLL DESIGN LANGUAGE
   All styling is scoped to .school-reports-page
========================================================= */

.school-reports-page {
    --bg: #f5f4f0;
    --text: #1e2a25;
    --muted: #5c6b64;
    --border: #d4cfc2;
    --primary: #2c3e36;
    --primary-hover: #3a4f45;
    --cream: #f3ead8;
    --cream-border: #e5d9c0;

    min-height: calc(100vh - 70px);
    background: var(--bg);
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
    max-width: 1400px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

/* HERO */

.school-reports-page .reports-hero {
    display: grid;
    grid-template-columns: 1.55fr 1fr;
    overflow: hidden;
    margin-bottom: 32px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: #fff;
}

.school-reports-page .reports-hero-left {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 34px 30px;
    background: #fdfbf7;
}

.school-reports-page .reports-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    border: 1px solid var(--cream-border);
    border-radius: 12px;
    background: var(--cream);
    color: #5c4a32;
    font-size: 23px;
}

.school-reports-page .reports-hero-content {
    min-width: 0;
}

.school-reports-page .reports-eyebrow {
    margin-bottom: 9px;
    color: #9c7b4a;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.4px;
}

.school-reports-page .reports-hero-content h2 {
    margin: 0 0 10px;
    color: var(--text);
    font-size: 28px;
    font-weight: 750;
    line-height: 1.25;
    letter-spacing: -.5px;
}

.school-reports-page .reports-hero-content > p {
    max-width: 510px;
    margin: 0;
    color: var(--muted);
    font-size: 13.5px;
    line-height: 1.8;
}

.school-reports-page .reports-hero-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 22px;
    color: #526158;
    font-size: 11px;
    font-weight: 600;
}

.school-reports-page .reports-hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.school-reports-page .reports-hero-meta i {
    color: #8c7958;
}

.school-reports-page .reports-hero-meta .meta-divider {
    width: 1px;
    height: 14px;
    background: var(--border);
}

/* HERO RIGHT — CREAM DASHBOARD ILLUSTRATION */

.school-reports-page .reports-hero-right {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    min-width: 0;
    overflow: hidden;
    padding: 28px 25px;
    border-left: 1px solid #e5d9c4;
    background: linear-gradient(
        145deg,
        #f8f1e3 0%,
        #f0e6d2 50%,
        #e8dcc6 100%
    );
}

.school-reports-page .hero-decoration {
    position: absolute;
    border: 1px solid rgba(140, 112, 72, .13);
    border-radius: 50%;
    pointer-events: none;
}

.school-reports-page .hero-decoration-one {
    top: -95px;
    right: -65px;
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
    max-width: 310px;
    padding: 17px 19px 15px;
    border: 1px solid rgba(151, 130, 95, .28);
    border-radius: 10px;
    background: rgba(255, 254, 250, .82);
    box-shadow: 0 10px 28px rgba(74, 59, 34, .07);
}

.school-reports-page .illustration-top {
    display: flex;
    align-items: center;
    gap: 5px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee6d8;
}

.school-reports-page .illustration-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #c4b49a;
}

.school-reports-page .illustration-label {
    margin-left: auto;
    color: #87765c;
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
    border-bottom: 1px solid #e9e0d1;
    background: repeating-linear-gradient(
        to bottom,
        transparent 0,
        transparent 22px,
        rgba(180, 164, 135, .16) 23px,
        transparent 24px
    );
}

.school-reports-page .chart-bar {
    width: 27px;
    max-width: 18%;
    border-radius: 4px 4px 0 0;
    background: #718778;
    animation: reportsBarRise .65s ease-out both;
    transform-origin: bottom;
}

.school-reports-page .chart-bar-one {
    height: 38%;
    background: #c7b18a;
    animation-delay: .04s;
}

.school-reports-page .chart-bar-two {
    height: 62%;
    background: #899b83;
    animation-delay: .10s;
}

.school-reports-page .chart-bar-three {
    height: 48%;
    background: #bba47a;
    animation-delay: .16s;
}

.school-reports-page .chart-bar-four {
    height: 85%;
    background: #527564;
    animation-delay: .22s;
}

.school-reports-page .chart-bar-five {
    height: 70%;
    background: #839783;
    animation-delay: .28s;
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
    padding-top: 13px;
}

.school-reports-page .illustration-check {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    flex-shrink: 0;
    border-radius: 7px;
    background: #e6eee4;
    color: #42674b;
    font-size: 11px;
}

.school-reports-page .illustration-footer strong,
.school-reports-page .illustration-footer span {
    display: block;
}

.school-reports-page .illustration-footer strong {
    color: #33443a;
    font-size: 10px;
    font-weight: 750;
}

.school-reports-page .illustration-footer span {
    margin-top: 3px;
    color: #7b8072;
    font-size: 9px;
}

.school-reports-page .hero-caption {
    position: relative;
    z-index: 1;
    color: #796448;
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 16px;
    text-align: center;
}

/* SECTION HEADER */

.school-reports-page .reports-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 19px;
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
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fff;
    color: var(--primary);
    font-size: 17px;
}

.school-reports-page .reports-section-title h3 {
    margin: 0 0 4px;
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
    padding: 9px 13px;
    border: 1px solid var(--cream-border);
    border-radius: 7px;
    background: #fdfbf7;
}

.school-reports-page .count-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: var(--primary);
    color: #fff;
    font-size: 13px;
    font-weight: 750;
}

.school-reports-page .count-label {
    color: #665d4e;
    font-size: 11px;
    font-weight: 650;
}

/* REPORT GRID */

.school-reports-page .reports-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

/* REPORT CARDS */

.school-reports-page .report-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    min-height: 144px;
    padding: 21px 18px;
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fff;
    color: var(--text);
    text-decoration: none;
    transition:
        transform .2s ease,
        box-shadow .2s ease,
        border-color .2s ease,
        background .2s ease;
}

.school-reports-page .report-card::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 3px;
    background: #a7b5a7;
    content: "";
    opacity: 0;
    transition: opacity .2s ease;
}

.school-reports-page .report-card:hover {
    transform: translateY(-3px);
    border-color: #c6c0b1;
    background: #fffefa;
    box-shadow: 0 9px 22px rgba(44, 62, 54, .075);
    color: var(--text);
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
    width: 51px;
    height: 51px;
    flex: 0 0 51px;
    border: 1px solid #e5e0d4;
    border-radius: 9px;
    background: #f5f2e9;
    color: #526b59;
    font-size: 20px;
    transition:
        transform .2s ease,
        background .2s ease;
}

.school-reports-page .report-card:hover .report-icon {
    transform: scale(1.045);
}

.school-reports-page .report-info {
    flex: 1;
    min-width: 0;
}

.school-reports-page .report-info h3 {
    margin: 0;
    color: #26352d;
    font-size: 14px;
    font-weight: 750;
    line-height: 1.5;
}

.school-reports-page .report-info p {
    margin: 6px 0 0;
    color: #6b766e;
    font-size: 11.5px;
    line-height: 1.7;
}

.school-reports-page .report-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    flex: 0 0 29px;
    border: 1px solid #e4dfd4;
    border-radius: 6px;
    background: #fdfbf7;
    color: #777f73;
    font-size: 11px;
    transition:
        transform .2s ease,
        background .2s ease,
        color .2s ease,
        border-color .2s ease;
}

.school-reports-page .report-card:hover .report-arrow {
    transform: translateX(3px);
    border-color: var(--primary);
    background: var(--primary);
    color: #fff;
}

/* Individual icon accents — muted to complement the locked palette */

.school-reports-page .student-icon {
    background: #e9efe8;
    border-color: #d9e3d6;
    color: #456b50;
}

.school-reports-page .faculty-icon {
    background: #eee9df;
    border-color: #e1d8c6;
    color: #786447;
}

.school-reports-page .staff-icon {
    background: #e8eee8;
    border-color: #d8e1d6;
    color: #4b7058;
}

.school-reports-page .attendance-icon {
    background: #e6eeeb;
    border-color: #d5e2dc;
    color: #456e60;
}

.school-reports-page .faculty-attendance-icon {
    background: #e6eeeb;
    border-color: #d5e2dc;
    color: #456e60;
}

.school-reports-page .fees-icon {
    background: #f4ecdc;
    border-color: #e8dcc2;
    color: #977039;
}

.school-reports-page .exam-icon {
    background: #f2e9e3;
    border-color: #e8d7cc;
    color: #9a5a43;
}

.school-reports-page .result-icon {
    background: #e9ece6;
    border-color: #d8dfd2;
    color: #5b7155;
}

.school-reports-page .library-icon {
    background: #f3ecdf;
    border-color: #e7dcc6;
    color: #8c7042;
}

.school-reports-page .transport-icon {
    background: #e7ece9;
    border-color: #d8e0da;
    color: #4b6a5a;
}

.school-reports-page .meal-icon {
    background: #f5efdc;
    border-color: #e9dfbf;
    color: #92753d;
}

.school-reports-page .payroll-icon {
    background: #e5efe7;
    border-color: #d0e1d3;
    color: #39734b;
}

.school-reports-page .sports-icon {
    background: #f1e9e3;
    border-color: #e5d7cb;
    color: #986247;
}

.school-reports-page .scholarship-icon {
    background: #ecebe1;
    border-color: #dfdfce;
    color: #6b7650;
}

.school-reports-page .class-icon {
    background: #e9eee7;
    border-color: #d9e2d5;
    color: #4e6e50;
}

/* BOTTOM NOTE */

.school-reports-page .reports-footer-note {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-top: 24px;
    padding: 17px 19px;
    border: 1px solid #e5d9c4;
    border-radius: 8px;
    background: #fdfbf7;
}

.school-reports-page .footer-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border: 1px solid var(--cream-border);
    border-radius: 7px;
    background: var(--cream);
    color: #806744;
    font-size: 14px;
}

.school-reports-page .reports-footer-note strong {
    display: block;
    margin-bottom: 4px;
    color: var(--text);
    font-size: 12px;
    font-weight: 750;
}

.school-reports-page .reports-footer-note p {
    margin: 0;
    color: var(--muted);
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
        padding: 28px 23px;
    }

    .school-reports-page .reports-hero-content h2 {
        font-size: 24px;
    }

    .school-reports-page .reports-hero-meta {
        gap: 8px;
    }
}

@media (max-width: 760px) {
    .school-reports-page .container-fluid {
        padding-right: 16px;
        padding-left: 16px;
    }

    .school-reports-page .reports-hero {
        grid-template-columns: 1fr;
    }

    .school-reports-page .reports-hero-left {
        padding: 24px 20px;
    }

    .school-reports-page .reports-hero-right {
        min-height: 250px;
        border-top: 1px solid #e5d9c4;
        border-left: none;
    }

    .school-reports-page .reports-hero-content h2 {
        font-size: 22px;
    }

    .school-reports-page .reports-section-title h3 {
        font-size: 17px;
    }

    .school-reports-page .report-card {
        gap: 12px;
        padding: 17px 14px;
    }

    .school-reports-page .report-icon {
        width: 44px;
        height: 44px;
        flex-basis: 44px;
        font-size: 17px;
    }

    .school-reports-page .report-info h3 {
        font-size: 13px;
    }

    .school-reports-page .report-info p {
        font-size: 11px;
    }
}

@media (max-width: 540px) {
    .school-reports-page .container-fluid {
        padding-right: 12px;
        padding-left: 12px;
    }

    .school-reports-page .reports-hero-left {
        align-items: flex-start;
        gap: 13px;
        padding: 22px 15px;
    }

    .school-reports-page .reports-hero-icon {
        width: 45px;
        height: 45px;
        flex-basis: 45px;
        font-size: 18px;
    }

    .school-reports-page .reports-eyebrow {
        font-size: 9px;
        letter-spacing: .8px;
    }

    .school-reports-page .reports-hero-content h2 {
        font-size: 20px;
    }

    .school-reports-page .reports-hero-content > p {
        font-size: 12px;
    }

    .school-reports-page .reports-hero-meta {
        align-items: flex-start;
        flex-direction: column;
        font-size: 10px;
    }

    .school-reports-page .reports-hero-meta .meta-divider {
        display: none;
    }

    .school-reports-page .reports-hero-right {
        padding: 23px 15px;
    }

    .school-reports-page .reports-section-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 13px;
    }

    .school-reports-page .reports-count {
        align-self: flex-start;
    }

    .school-reports-page .reports-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .school-reports-page .report-card {
        min-height: 112px;
        padding: 17px;
    }

    .school-reports-page .report-info h3 {
        font-size: 14px;
    }

    .school-reports-page .report-info p {
        font-size: 12px;
    }

    .school-reports-page .reports-footer-note {
        padding: 14px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .school-reports-page *,
    .school-reports-page *::before,
    .school-reports-page *::after {
        animation: none !important;
        transition: none !important;
    }
}
</style>


<div class="reports-page">

    {{-- PAGE HEADER --}}
    <div class="reports-header">

        <h2>
            Reports
        </h2>

        <p>
            Generate and view reports for different areas of the school.
        </p>

    </div>


    {{-- REPORT CARDS --}}
    <div class="reports-grid">


        {{-- 1. STUDENT --}}
        <a href="{{ route('admin.reports.students') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-user-graduate"></i>
            </div>

            <div class="report-info">

                <h3>
                    Student Reports
                </h3>

                <p>
                    Student information, profiles and academic records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 2. FACULTY --}}
        <a href="{{ route('admin.teachers.reports.index') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>

            <div class="report-info">

                <h3>
                    Faculty Reports
                </h3>

                <p>
                    Faculty details, allocation and teaching records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 3. STAFF --}}
        <a href="{{ route('admin.reports.staff') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-users"></i>
            </div>

            <div class="report-info">

                <h3>
                    Staff Reports
                </h3>

                <p>
                    Non-teaching staff and employee information.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 5. ATTENDANCE --}}
        <a href="{{ route('admin.attendance.report') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-calendar-check"></i>
            </div>

            <div class="report-info">

                <h3>
                    Attendance Reports
                </h3>

                <p>
                    Student and faculty attendance summaries.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 6. FEES --}}
       <a href="{{ route('admin.fees.student-fees.index') }}" class="report-card">
            <div class="report-icon">
                <i class="fas fa-indian-rupee-sign"></i>
            </div>

            <div class="report-info">

                <h3>
                    Fee Reports
                </h3>

                <p>
                    Fee collection, payments and pending fees.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 7. EXAM --}}

<a href="{{ route('admin.reports.exams') }}" class="report-card">


            <div class="report-icon">
                <i class="fas fa-file-alt"></i>
            </div>

            <div class="report-info">

                <h3>
                    Exam Reports
                </h3>

                <p>
                    Examination schedules and examination records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 8. RESULT --}}
        <a href="{{ route('admin.reports.results.index') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-chart-column"></i>
            </div>

            <div class="report-info">

                <h3>
                    Result Reports
                </h3>

                <p>
                    Marks, grades and student result summaries.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 9. LIBRARY --}}
        <a href="{{ route('admin.library.reports.index') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-book"></i>
            </div>

            <div class="report-info">

                <h3>
                    Library Reports
                </h3>

                <p>
                    Books, issues, returns, overdue books and fines.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 10. TRANSPORT --}}
        <a href="{{ route('admin.transport.reports.student-search') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-bus"></i>
            </div>

            <div class="report-info">

                <h3>
                    Transport Reports
                </h3>

                <p>
                    Routes, vehicles, drivers and student transport.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 11. MEAL --}}
        <a href="{{ route('admin.meal.logs.index') }}" class="report-card">

    <div class="report-icon">
        <i class="fas fa-utensils"></i>
    </div>

    <div class="report-info">

        <h3>
            Meal Reports
        </h3>

        <p>
            Meal stock, usage and inventory records.
        </p>

    </div>

    <div class="report-arrow">
        <i class="fas fa-chevron-right"></i>
    </div>

</a>


        {{-- 12. PAYROLL --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-money-check-dollar"></i>
            </div>

            <div class="report-info">

                <h3>
                    Payroll Reports
                </h3>

                <p>
                    Salary, payroll and employee payment records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 13. SPORTS --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-futbol"></i>
            </div>

            <div class="report-info">

                <h3>
                    Sports Reports
                </h3>

                <p>
                    Sports activities and student participation.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 14. SCHOLARSHIP --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>

            <div class="report-info">

                <h3>
                    Scholarship Reports
                </h3>

                <p>
                    Scholarship applications and student records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 15. CLASS --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-chalkboard"></i>
            </div>

            <div class="report-info">

                <h3>
                    Class Reports
                </h3>

                <p>
                    Classes, divisions, subjects and class records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


    </div>

</div>

@endsection

@endsection
