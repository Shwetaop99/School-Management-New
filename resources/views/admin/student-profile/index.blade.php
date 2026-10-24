@extends('layouts.app')

@section('title', 'Student Profile | Admin')
@section('page-title', 'Student Profile')

@section('content')

<div class="student-profile-page">
    <div class="container-fluid py-4">

        {{-- ALERT AREA (JS) --}}
        <div id="alertArea"></div>

        {{-- HERO --}}
        <section class="student-hero">
            <div class="student-hero-content">
                <div class="student-hero-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>

                <div class="student-hero-text">
                    <div class="student-breadcrumb">
                        <span>Student Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Student Profile</span>
                    </div>

                    <h2>Student Profile</h2>

                    <p>
                        Search and view complete student information,
                        academic details, parents, previous school and
                        address from one place.
                    </p>

                    <div class="student-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Records &amp; Student Information Management</span>
                    </div>
                </div>
            </div>

            <div class="student-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-id-card"></i>
                </div>

                <div class="hero-handwritten">Every Student. One Profile.</div>

                <p>
                    Every student matters. Keep records complete and
                    find any profile in seconds.
                </p>

                <a href="{{ route('admin.students.index') }}"
                   class="diary-primary-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Students
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="student-kpis">

            <article class="student-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($totalStudents) }}</div>
                <div class="kpi-caption">All registered students</div>
            </article>

            <article class="student-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Boys</span>
                    <span class="kpi-icon">
                        <i class="fas fa-mars"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($totalMaleStudents) }}</div>
                <div class="kpi-caption">Male students</div>
            </article>

            <article class="student-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Girls</span>
                    <span class="kpi-icon">
                        <i class="fas fa-venus"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($totalFemaleStudents) }}</div>
                <div class="kpi-caption">Female students</div>
            </article>

            <article class="student-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Classes</span>
                    <span class="kpi-icon">
                        <i class="fas fa-building"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format(count($classes)) }}</div>
                <div class="kpi-caption">Available classes</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="student-paper-panel">

            <div class="student-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h3>Student Profile Lookup</h3>
                        <p>View complete details of a single student.</p>
                    </div>
                </div>

                <div class="student-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($totalStudents) }}</strong>
                    Students
                </div>
            </div>

            {{-- SEARCH --}}
            <form id="studentSearchForm" class="student-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div>
                        <h4>Find Student Profile</h4>
                        <p>Enter a unique Student ID to view complete details.</p>
                    </div>
                </div>

                <div class="student-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="studentId">Student ID</label>
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
                            <input id="studentId"
                                   type="text"
                                   name="student_id"
                                   placeholder="Enter Student ID..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit"
                                class="diary-apply-btn"
                                id="searchStudentBtn">
                            <i class="fas fa-search"></i> Search Student
                        </button>
                    </div>

                </div>
            </form>

            {{-- LOADING --}}
            <div class="student-loading" id="loadingBox">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="loading-text">Loading student profile...</div>
            </div>

            {{-- PROFILE --}}
            <div id="studentProfileContent" style="display:none;">

                {{-- PROFILE HEADER --}}
                <div class="sp-profile-header">

                    <div class="sp-profile-image-wrap">
                        <img src=""
                             id="studentProfileImage"
                             class="sp-profile-image"
                             alt="Student Photo">
                    </div>

                    <div class="sp-profile-info">
                        <div class="sp-profile-name" id="studentFullName">-</div>
                        <div class="sp-profile-marathi" id="studentMarathiName">-</div>

                        <div class="sp-profile-meta">
                            <span>
                                <i class="fas fa-id-badge"></i>
                                <span id="profileStudentId">-</span>
                            </span>

                            <span>
                                <i class="fas fa-building"></i>
                                Class: <span id="profileClass">-</span>
                            </span>

                            <span>
                                <i class="fas fa-th"></i>
                                Section: <span id="profileSection">-</span>
                            </span>

                            <span>
                                <i class="fas fa-list-ol"></i>
                                Roll: <span id="profileRollNumber">-</span>
                            </span>

                            <span class="student-status active" id="profileStatus">
                                <span class="status-dot"></span>
                                <span id="profileStatusText">Active</span>
                            </span>
                        </div>
                    </div>

                    <div class="sp-profile-actions">
                        <button type="button"
                                class="diary-reset-btn"
                                onclick="window.print()">
                            <i class="fas fa-print"></i> Print Profile
                        </button>
                    </div>

                </div>

                {{-- TABS --}}
                <ul class="sp-tabs" id="studentTabs" role="tablist">

                    <li>
                        <button class="sp-tab-link active"
                                type="button"
                                data-sp-target="#basicTab">
                            <i class="fas fa-user"></i> Basic Information
                        </button>
                    </li>

                    <li>
                        <button class="sp-tab-link"
                                type="button"
                                data-sp-target="#academicTab">
                            <i class="fas fa-user-graduate"></i> Academic
                        </button>
                    </li>

                    <li>
                        <button class="sp-tab-link"
                                type="button"
                                data-sp-target="#parentsTab">
                            <i class="fas fa-users"></i> Parents
                        </button>
                    </li>

                    <li>
                        <button class="sp-tab-link"
                                type="button"
                                data-sp-target="#previousSchoolTab">
                            <i class="fas fa-landmark"></i> Previous School
                        </button>
                    </li>

                    <li>
                        <button class="sp-tab-link"
                                type="button"
                                data-sp-target="#addressTab">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </button>
                    </li>

                </ul>

                <div class="sp-tab-content">

                    {{-- BASIC --}}
                    <div class="sp-tab-pane active" id="basicTab">
                        <div class="info-section">

                            <div class="section-title">
                                <i class="fas fa-user-circle"></i>
                                Personal Information
                            </div>

                            <div class="info-grid">

                                <div class="info-item">
                                    <div class="info-label">First Name</div>
                                    <div class="info-value" id="basicFirstName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Middle Name</div>
                                    <div class="info-value" id="basicMiddleName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Last Name</div>
                                    <div class="info-value" id="basicLastName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Marathi Name</div>
                                    <div class="info-value" id="basicMarathiName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Gender</div>
                                    <div class="info-value" id="basicGender">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Date of Birth</div>
                                    <div class="info-value" id="basicDob">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Aadhar Number</div>
                                    <div class="info-value" id="basicAadhar">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Phone</div>
                                    <div class="info-value" id="basicPhone">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value" id="basicEmail">-</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ACADEMIC --}}
                    <div class="sp-tab-pane" id="academicTab">
                        <div class="info-section">

                            <div class="section-title">
                                <i class="fas fa-user-graduate"></i>
                                Academic Information
                            </div>

                            <div class="info-grid">

                                <div class="info-item">
                                    <div class="info-label">Academic Year</div>
                                    <div class="info-value" id="academicYear">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Class</div>
                                    <div class="info-value" id="academicClass">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Section</div>
                                    <div class="info-value" id="academicSection">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Roll Number</div>
                                    <div class="info-value" id="academicRollNumber">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Admission Class</div>
                                    <div class="info-value" id="academicAdmissionClass">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Admission Date</div>
                                    <div class="info-value" id="academicAdmissionDate">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Register Number</div>
                                    <div class="info-value" id="academicRegisterNo">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Book Number</div>
                                    <div class="info-value" id="academicBookNo">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">APPAR ID</div>
                                    <div class="info-value" id="academicApparId">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">PEN Number</div>
                                    <div class="info-value" id="academicPenNo">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Medium</div>
                                    <div class="info-value" id="academicMedium">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Mother Tongue</div>
                                    <div class="info-value" id="academicMotherTongue">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Nationality</div>
                                    <div class="info-value" id="academicNationality">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Religion</div>
                                    <div class="info-value" id="academicReligion">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Caste</div>
                                    <div class="info-value" id="academicCaste">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Sub Caste</div>
                                    <div class="info-value" id="academicSubCaste">-</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- PARENTS --}}
                    <div class="sp-tab-pane" id="parentsTab">
                        <div class="info-section">

                            <div class="section-title">
                                <i class="fas fa-users"></i>
                                Parents / Guardian Information
                            </div>

                            <div class="info-grid">

                                <div class="info-item">
                                    <div class="info-label">Father Name</div>
                                    <div class="info-value" id="fatherName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Father Phone</div>
                                    <div class="info-value" id="fatherPhone">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Father Occupation</div>
                                    <div class="info-value" id="fatherOccupation">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Mother Name</div>
                                    <div class="info-value" id="motherName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Mother Phone</div>
                                    <div class="info-value" id="motherPhone">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Mother Occupation</div>
                                    <div class="info-value" id="motherOccupation">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Guardian Name</div>
                                    <div class="info-value" id="guardianName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Guardian Relation</div>
                                    <div class="info-value" id="guardianRelation">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Guardian Phone</div>
                                    <div class="info-value" id="guardianPhone">-</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- PREVIOUS SCHOOL --}}
                    <div class="sp-tab-pane" id="previousSchoolTab">
                        <div class="info-section">

                            <div class="section-title">
                                <i class="fas fa-landmark"></i>
                                Previous School Information
                            </div>

                            <div class="info-grid">

                                <div class="info-item">
                                    <div class="info-label">School Name</div>
                                    <div class="info-value" id="previousSchoolName">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Class</div>
                                    <div class="info-value" id="previousSchoolClass">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Medium</div>
                                    <div class="info-value" id="previousSchoolMedium">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Board</div>
                                    <div class="info-value" id="previousSchoolBoard">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Result</div>
                                    <div class="info-value" id="previousSchoolResult">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Remarks</div>
                                    <div class="info-value" id="previousSchoolRemarks">-</div>
                                </div>

                                <div class="info-item info-item-wide">
                                    <div class="info-label">School Address</div>
                                    <div class="info-value" id="previousSchoolAddress">-</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ADDRESS --}}
                    <div class="sp-tab-pane" id="addressTab">
                        <div class="info-section">

                            <div class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Address Information
                            </div>

                            <div class="info-grid">

                                <div class="info-item info-item-wide">
                                    <div class="info-label">Address</div>
                                    <div class="info-value" id="addressValue">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Country</div>
                                    <div class="info-value" id="addressCountry">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">State</div>
                                    <div class="info-value" id="addressState">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">District</div>
                                    <div class="info-value" id="addressDistrict">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Taluka</div>
                                    <div class="info-value" id="addressTaluka">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">City / Village</div>
                                    <div class="info-value" id="addressCityVillage">-</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Pincode</div>
                                    <div class="info-value" id="addressPincode">-</div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="student-card-footer">
                    <div>Student profile loaded</div>
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>

            </div>

            {{-- EMPTY STATE --}}
            <div class="student-empty-state" id="emptyStudentState">
                <div class="empty-state-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>

                <h4>Search for a Student</h4>
                <p>
                    Enter a Student ID above to view the complete
                    student profile.
                </p>
            </div>

        </section>
    </div>
</div>

<style>
/* ==========================================================
   STUDENT PROFILE — SCHOOL DIARY DESIGN SYSTEM
   Premium editorial dashboard | Responsive | Accessible
========================================================== */

.student-profile-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', sans-serif;
    font-size: 13px;
}

.student-profile-page *,
.student-profile-page *::before,
.student-profile-page *::after {
    box-sizing: border-box;
}

.student-profile-page a,
.student-profile-page button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.student-profile-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.student-hero,
.student-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.student-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.student-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.student-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.student-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.student-hero-text {
    min-width: 0;
}

.student-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
    color: #858d80;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.student-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.student-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.student-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.student-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15px;
}

.student-hero-note i {
    font-size: 13px;
}

.student-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 30px 25px;
    overflow: hidden;
    color: #e8eee4;
    background: linear-gradient(145deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.student-hero-actions::before,
.student-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.student-hero-actions::before {
    top: -115px;
    right: -60px;
}

.student-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.hero-decoration {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255, 255, 255, .07);
    font-size: 16px;
}

.hero-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: var(--hand, cursive);
    font-size: 23px;
    line-height: 1.4;
}

.student-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

.diary-primary-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid var(--ink);
    border-radius: 9px;
    color: #fff;
    background: var(--ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.student-hero-actions .diary-primary-btn {
    border-color: #e8e0c9;
    color: var(--ink);
    background: #f1ead8;
}

.diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.student-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* ALERTS */

#alertArea:empty {
    display: none;
}

#alertArea {
    margin-bottom: 20px;
}

#alertArea .alert {
    margin-bottom: 0;
    padding: 13px 16px;
    border: 1px solid var(--line);
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
}

#alertArea .alert-success {
    border-color: #d2e5d1;
    color: #347849;
    background: #f1f8ef;
}

#alertArea .alert-danger {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

#alertArea .alert-warning {
    border-color: #e7dfc9;
    color: #765e32;
    background: #faf5e8;
}

/* KPI CARDS */

.student-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.student-kpi {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.student-kpi.blue { --kpi-color: #426b8a; }
.student-kpi.green { --kpi-color: #43835d; }
.student-kpi.orange { --kpi-color: #a87543; }
.student-kpi.red { --kpi-color: #b95b50; }

.student-kpi::before {
    position: absolute;
    top: -24px;
    left: 17px;
    padding: 5px 10px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
}

.student-kpi.blue::before { content: "TOTAL"; }
.student-kpi.green::before { content: "BOYS"; }
.student-kpi.orange::before { content: "GIRLS"; }
.student-kpi.red::before { content: "CLASSES"; }

.student-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.student-kpi .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e8e5da;
    border-radius: 12px;
    color: var(--kpi-color);
    background: #f7f6ef;
    font-size: 17px;
}

.kpi-value {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* MAIN PANEL */

.student-paper-panel {
    overflow: hidden;
}

.student-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: #fffefa;
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.panel-title-icon,
.filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 16px;
}

.panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.student-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.student-record-count i,
.student-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.student-filter-panel {
    margin: 0;
    padding: 23px 24px;
    border-bottom: 1px solid var(--line);
    background: #faf9f3;
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.filter-heading-icon {
    color: #527f70;
    background: #edf2e9;
}

.filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 17px;
    font-weight: 700;
}

.filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.student-filter-grid {
    display: grid;
    grid-template-columns: minmax(220px, 2fr) auto;
    align-items: end;
    gap: 16px;
}

.diary-filter-group {
    min-width: 0;
}

.diary-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.diary-search-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.diary-search-input:hover {
    border-color: #bdcabb;
}

.diary-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.diary-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.diary-search-input > i {
    flex: 0 0 auto;
    color: #829083;
    font-size: 13px;
}

.diary-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    outline: 0;
    color: var(--text);
    background: transparent;
    font: inherit;
}

.diary-search-input input::placeholder {
    color: #9a9f93;
}

.diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.diary-apply-btn,
.diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.diary-apply-btn {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: #426652;
    color: #fff;
    background: #426652;
    box-shadow: 0 4px 10px rgba(38, 59, 53, .12);
}

.diary-apply-btn:disabled {
    opacity: .7;
    cursor: not-allowed;
    transform: none;
}

.diary-reset-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: #fffefa;
}

.diary-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* LOADING */

.student-loading {
    display: none;
    padding: 55px 20px;
    border-bottom: 1px solid #eeece3;
    background: var(--paper);
    text-align: center;
}

.student-loading .spinner-border {
    width: 2rem;
    height: 2rem;
    margin-bottom: 14px;
    color: var(--sage);
}

.loading-text {
    color: var(--ink-soft);
    font-size: 12px;
    font-weight: 700;
}

/* PROFILE HEADER */

.sp-profile-header {
    position: relative;
    display: flex;
    align-items: center;
    gap: 22px;
    padding: 28px 24px 28px 51px;
    overflow: hidden;
    border-bottom: 1px solid var(--line);
    background: var(--paper);
}

.sp-profile-header::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.sp-profile-image-wrap {
    flex: 0 0 104px;
    width: 104px;
    height: 104px;
    padding: 5px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    background: var(--chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .06);
}

.sp-profile-image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 11px;
    background: var(--chalk);
}

.sp-profile-info {
    flex: 1 1 auto;
    min-width: 0;
}

.sp-profile-name {
    margin-bottom: 4px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(22px, 2vw, 28px);
    font-weight: 700;
    line-height: 1.25;
    letter-spacing: -.5px;
    overflow-wrap: anywhere;
}

.sp-profile-marathi {
    margin-bottom: 13px;
    color: var(--muted);
    font-size: 13px;
    font-weight: 600;
}

.sp-profile-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.sp-profile-meta > span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #e4e0d4;
    border-radius: 7px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.sp-profile-meta > span > i {
    color: var(--amber);
}

.sp-profile-actions {
    flex: 0 0 auto;
    align-self: flex-start;
}

/* STATUS */

.student-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.sp-profile-meta > .student-status {
    padding: 6px 10px;
}

.student-status.active {
    border-color: #d4e5d0 !important;
    color: #347849 !important;
    background: #edf6e9 !important;
}

.student-status.inactive {
    border-color: #eed6cf !important;
    color: #b95b50 !important;
    background: #fff1ed !important;
}

.status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.student-status.active .status-dot {
    background: #43835d;
}

.student-status.inactive .status-dot {
    background: #b95b50;
}

/* TABS */

.sp-tabs {
    display: flex;
    gap: 6px;
    margin: 0;
    padding: 12px 24px;
    overflow-x: auto;
    border-bottom: 1px solid #e2e0d5;
    background: #f1f2e9;
    list-style: none;
    scrollbar-width: thin;
}

.sp-tabs li {
    flex-shrink: 0;
}

.sp-tab-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 40px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    color: #637166;
    background: transparent;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
    cursor: pointer;
}

.sp-tab-link i {
    color: var(--sage);
    font-size: 12px;
}

.sp-tab-link:hover {
    border-color: #dce4d8;
    color: var(--ink);
    background: #fffefa;
}

.sp-tab-link.active {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
    box-shadow: 0 3px 8px rgba(29, 48, 43, .12);
}

.sp-tab-link.active i {
    color: #ead7aa;
}

/* TAB CONTENT */

.sp-tab-content {
    padding: 24px;
    background: #fffefa;
}

.sp-tab-pane {
    display: none;
}

.sp-tab-pane.active {
    display: block;
}

/* INFORMATION SECTIONS */

.info-section {
    padding: 21px;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #ddd8ca;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 17px;
    font-weight: 700;
}

.section-title i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #dce4d8;
    border-radius: 12px;
    color: var(--sage);
    background: #edf2e9;
    font-size: 16px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 13px;
}

.info-item {
    position: relative;
    min-width: 0;
    min-height: 72px;
    padding: 13px 14px 13px 18px;
    overflow: hidden;
    border: 1px solid #efede5;
    border-radius: 10px;
    background: #faf9f3;
    transition: border-color .2s ease, background .2s ease,
        box-shadow .2s ease, transform .2s ease;
}

.info-item::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 3px;
    background: var(--sage);
    content: "";
}

.info-item:hover {
    transform: translateY(-1px);
    border-color: #cbd7c9;
    background: var(--paper);
    box-shadow: 0 4px 12px rgba(38, 59, 53, .045);
}

.info-item-wide {
    grid-column: 1 / -1;
}

.info-label {
    margin-bottom: 6px;
    color: #758176;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .65px;
    text-transform: uppercase;
}

.info-value {
    min-height: 20px;
    color: #293d32;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.55;
    overflow-wrap: anywhere;
    word-break: break-word;
}

/* SECTION ACCENTS */

#academicTab .info-item::before { background: var(--blue); }
#parentsTab .info-item::before { background: var(--amber); }
#previousSchoolTab .info-item::before { background: var(--sage); }
#addressTab .info-item::before { background: var(--green); }

#academicTab .section-title i {
    border-color: #d9e2e9;
    color: var(--blue);
    background: #edf4f8;
}

#parentsTab .section-title i {
    border-color: #ebdfcc;
    color: var(--amber);
    background: #faf2e7;
}

#previousSchoolTab .section-title i {
    border-color: #dce4d8;
    color: var(--sage);
    background: #edf2e9;
}

#addressTab .section-title i {
    border-color: #d9e4d2;
    color: var(--green);
    background: #edf6e9;
}

/* FOOTER */

.student-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 55px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 10px;
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.footer-status i {
    color: #43835d;
}

/* EMPTY STATE */

.student-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.student-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.student-empty-state p {
    max-width: 450px;
    margin: 0 auto;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .student-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sp-profile-header {
        flex-wrap: wrap;
    }

    .sp-profile-actions {
        margin-left: 126px;
    }
}

@media (max-width: 900px) {
    .student-hero {
        grid-template-columns: 1fr;
    }

    .student-hero-actions {
        min-height: 220px;
    }

    .student-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .student-profile-page .container-fluid {
        padding: 18px 14px !important;
    }

    .student-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .student-hero::before {
        left: 18px;
    }

    .student-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .student-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .student-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .student-hero-text h2 {
        font-size: 24px;
    }

    .student-hero-text p {
        font-size: 11px;
    }

    .student-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .student-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .student-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .student-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .student-kpi .kpi-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .kpi-value {
        font-size: 29px;
    }

    .kpi-caption {
        font-size: 9px;
    }

    .student-panel-header {
        align-items: flex-start;
        padding: 16px;
    }

    .panel-title-wrap {
        gap: 10px;
    }

    .panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .panel-title-wrap h3 {
        font-size: 16px;
    }

    .panel-title-wrap p {
        font-size: 10px;
    }

    .student-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .student-filter-panel {
        padding: 17px 15px;
    }

    .filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .filter-heading h4 {
        font-size: 16px;
    }

    .student-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .sp-profile-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        padding: 22px 15px 22px 32px;
    }

    .sp-profile-header::before {
        left: 18px;
    }

    .sp-profile-image-wrap {
        flex-basis: 88px;
        width: 88px;
        height: 88px;
    }

    .sp-profile-actions {
        width: 100%;
        margin-left: 0;
    }

    .sp-profile-actions .diary-reset-btn {
        width: 100%;
    }

    .sp-tabs {
        padding: 10px 15px;
    }

    .sp-tab-content {
        padding: 15px;
    }

    .info-section {
        padding: 15px;
    }

    .section-title {
        font-size: 16px;
    }

    .section-title i {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .student-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .student-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 575px) {
    .info-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .info-item-wide {
        grid-column: auto;
    }
}

@media (max-width: 400px) {
    .student-kpis {
        grid-template-columns: 1fr;
    }

    .student-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .student-record-count {
        margin-left: 46px;
    }

    .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .diary-apply-btn,
    .diary-reset-btn {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.student-profile-page a:focus-visible,
.student-profile-page button:focus-visible,
.student-profile-page input:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .student-profile-page *,
    .student-profile-page *::before,
    .student-profile-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}

/* PRINT */

@media print {
    body {
        background: #fff !important;
    }

    .student-profile-page {
        background: #fff !important;
        color: #222 !important;
    }

    .student-hero,
    .student-kpis,
    .student-panel-header,
    .student-filter-panel,
    .student-loading,
    .student-empty-state,
    .student-card-footer,
    #alertArea,
    .sp-profile-actions,
    .sp-tabs {
        display: none !important;
    }

    .student-profile-page .container-fluid {
        padding: 0 !important;
    }

    .student-paper-panel,
    .info-section {
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    .sp-profile-header {
        padding: 0 0 12px !important;
    }

    .sp-profile-header::before {
        display: none;
    }

    .sp-profile-image-wrap {
        width: 85px !important;
        height: 85px !important;
        flex-basis: 85px !important;
        box-shadow: none !important;
    }

    .sp-tab-content {
        padding: 0 !important;
    }

    .sp-tab-pane {
        display: block !important;
    }

    .info-section {
        margin-bottom: 14px;
        padding: 12px 0 !important;
        break-inside: avoid;
    }

    .info-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }

    .info-item {
        background: #fff !important;
        break-inside: avoid;
    }

    .info-item::before {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('studentSearchForm');
    const input = document.getElementById('studentId');
    const button = document.getElementById('searchStudentBtn');
    const loading = document.getElementById('loadingBox');
    const profile = document.getElementById('studentProfileContent');
    const empty = document.getElementById('emptyStudentState');
    const alertArea = document.getElementById('alertArea');

    const tabLinks = document.querySelectorAll('.sp-tab-link');
    const tabPanes = document.querySelectorAll('.sp-tab-pane');

    /* =====================================================
       TABS
    ===================================================== */

    function activateTab(targetSelector) {
        tabLinks.forEach(function (link) {
            link.classList.toggle(
                'active',
                link.getAttribute('data-sp-target') === targetSelector
            );
        });

        tabPanes.forEach(function (pane) {
            pane.classList.toggle('active', '#' + pane.id === targetSelector);
        });
    }

    tabLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            activateTab(link.getAttribute('data-sp-target'));
        });
    });

    /* =====================================================
       HELPERS
    ===================================================== */

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showAlert(message, type = 'danger') {
        if (!alertArea) return;

        alertArea.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="status">
                ${escapeHtml(message)}
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>
            </div>
        `;
    }

    function setText(id, value) {
        const el = document.getElementById(id);

        if (el) {
            el.textContent =
                value === null ||
                value === undefined ||
                value === ''
                    ? '-'
                    : value;
        }
    }

    function formatDate(value) {
        if (!value) return '-';

        const date = new Date(value);

        if (isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleDateString('en-IN', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    /* =====================================================
       LOAD STUDENT
    ===================================================== */

    function loadStudent(student) {

        const fullName = [
            student.first_name,
            student.middle_name,
            student.last_name
        ].filter(Boolean).join(' ');

        /* Header */
        setText('studentFullName', fullName);
        setText('studentMarathiName', student.marathi_name);
        setText('profileStudentId', student.student_id);
        setText('profileClass', student.class);
        setText('profileSection', student.section);
        setText('profileRollNumber', student.roll_number);

        /* Status */
        const statusValue = String(student.status || 'active').toLowerCase();
        const status = document.getElementById('profileStatus');

        if (status) {
            status.classList.remove('active', 'inactive');
            status.classList.add(statusValue === 'active' ? 'active' : 'inactive');
        }

        setText(
            'profileStatusText',
            statusValue.charAt(0).toUpperCase() + statusValue.slice(1)
        );

        /* Image */
        const image = document.getElementById('studentProfileImage');

        if (image) {
            image.src = student.profile_image ||
                'https://ui-avatars.com/api/?name=' +
                encodeURIComponent(fullName || 'Student') +
                '&background=263b35&color=f1ead8&size=300';
        }

        /* Basic */
        setText('basicFirstName', student.first_name);
        setText('basicMiddleName', student.middle_name);
        setText('basicLastName', student.last_name);
        setText('basicMarathiName', student.marathi_name);
        setText('basicGender', student.gender);
        setText('basicDob', formatDate(student.date_of_birth));
        setText('basicAadhar', student.aadhar_card_no);
        setText('basicPhone', student.phone);
        setText('basicEmail', student.email);

        /* Academic */
        setText('academicYear', student.academic_year);
        setText('academicClass', student.class);
        setText('academicSection', student.section);
        setText('academicRollNumber', student.roll_number);
        setText('academicAdmissionClass', student.admission_class);
        setText('academicAdmissionDate', formatDate(student.admission_date));
        setText('academicRegisterNo', student.register_no);
        setText('academicBookNo', student.book_no);
        setText('academicApparId', student.appar_id);
        setText('academicPenNo', student.pen_no);
        setText('academicMedium', student.medium);
        setText('academicMotherTongue', student.mother_tongue);
        setText('academicNationality', student.nationality);
        setText('academicReligion', student.religion);
        setText('academicCaste', student.caste);
        setText('academicSubCaste', student.sub_caste);

        /* Parents */
        setText('fatherName', student.father_name);
        setText('fatherPhone', student.father_phone);
        setText('fatherOccupation', student.father_occupation);

        setText('motherName', student.mother_name);
        setText('motherPhone', student.mother_phone);
        setText('motherOccupation', student.mother_occupation);

        setText('guardianName', student.guardian_name);
        setText('guardianRelation', student.guardian_relation);
        setText('guardianPhone', student.guardian_phone);

        /* Previous School */
        setText('previousSchoolName', student.previous_school_name);
        setText('previousSchoolClass', student.previous_school_class);
        setText('previousSchoolMedium', student.previous_school_medium);
        setText('previousSchoolBoard', student.previous_school_board);
        setText('previousSchoolResult', student.previous_school_result);
        setText('previousSchoolRemarks', student.previous_school_remarks);
        setText('previousSchoolAddress', student.previous_school_address);

        /* Address */
        setText('addressValue', student.address);
        setText('addressCountry', student.country);
        setText('addressState', student.state);
        setText('addressDistrict', student.district);
        setText('addressTaluka', student.taluka);
        setText('addressCityVillage', student.city_village);
        setText('addressPincode', student.pincode);

        /* Reset to first tab and show profile */
        activateTab('#basicTab');

        if (profile) {
            profile.style.display = 'block';
        }

        if (empty) {
            empty.style.display = 'none';
        }
    }

    /* =====================================================
       SEARCH
    ===================================================== */

    if (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            const studentId = input
                ? input.value.trim()
                : '';

            if (!studentId) {
                showAlert('Please enter a Student ID.', 'warning');
                return;
            }

            if (button) {
                button.disabled = true;
                button.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Searching...';
            }

            if (loading) {
                loading.style.display = 'block';
            }

            if (profile) {
                profile.style.display = 'none';
            }

            if (empty) {
                empty.style.display = 'none';
            }

            if (alertArea) {
                alertArea.innerHTML = '';
            }

            const url =
                '/admin/students/profile/search?student_id=' +
                encodeURIComponent(studentId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(function (response) {

                return response.text().then(function (text) {

                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch (error) {
                        throw new Error(
                            'Server returned an invalid response.'
                        );
                    }

                    return {
                        ok: response.ok,
                        status: response.status,
                        data: data
                    };
                });
            })
            .then(function (result) {

                if (!result.ok) {
                    throw new Error(
                        result.data.message ||
                        'Student not found.'
                    );
                }

                if (
                    !result.data.success ||
                    !result.data.student
                ) {
                    throw new Error(
                        result.data.message ||
                        'Student data was not returned.'
                    );
                }

                loadStudent(result.data.student);

                showAlert(
                    'Student profile loaded successfully.',
                    'success'
                );
            })
            .catch(function (error) {

                console.error('SEARCH ERROR:', error);

                if (profile) {
                    profile.style.display = 'none';
                }

                if (empty) {
                    empty.style.display = 'block';
                }

                showAlert(
                    error.message ||
                    'Unable to load student profile.',
                    'danger'
                );
            })
            .finally(function () {

                if (loading) {
                    loading.style.display = 'none';
                }

                if (button) {
                    button.disabled = false;
                    button.innerHTML =
                        '<i class="fas fa-search"></i> Search Student';
                }
            });
        });
    }

});
</script>

@endsection