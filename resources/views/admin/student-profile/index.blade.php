@extends('layouts.app')

@section('content')

<style>

/* =========================================================
   PAGE
========================================================= */

.student-profile-page {
    min-height: calc(100vh - 70px);
    padding: 24px;
    background:
        linear-gradient(180deg, #f5f8ff 0%, #f8fafc 45%, #f4f7fb 100%);
}


/* =========================================================
   WELCOME
========================================================= */

.welcome-card {
    position: relative;
    overflow: hidden;
    min-height: 135px;
    margin-bottom: 24px;
    padding: 28px 30px;
    border-radius: 22px;

    background:
        linear-gradient(
            135deg,
            #174ea6 0%,
            #2563eb 42%,
            #0891b2 100%
        );

    box-shadow:
        0 15px 35px rgba(37, 99, 235, .20);
}

.welcome-content {
    position: relative;
    z-index: 3;

    display: flex;
    align-items: center;
    gap: 18px;
}

.welcome-icon {
    width: 60px;
    height: 60px;
    min-width: 60px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 17px;

    background: rgba(255,255,255,.16);
    border: 1px solid rgba(255,255,255,.25);

    color: #fff;
    font-size: 25px;

    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.15);
}

.welcome-card h2 {
    margin: 0 0 6px;
    color: #fff;
    font-size: 24px;
    font-weight: 800;
}

.welcome-card p {
    margin: 0;
    color: rgba(255,255,255,.82);
    font-size: 12px;
}

.welcome-decoration {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.decoration-one {
    width: 260px;
    height: 260px;
    right: -70px;
    top: -150px;
}

.decoration-two {
    width: 190px;
    height: 190px;
    right: 170px;
    bottom: -145px;
}

.decoration-three {
    width: 100px;
    height: 100px;
    right: 42%;
    top: -55px;
}


/* =========================================================
   STAT CARDS
========================================================= */

.student-stat-card {
    position: relative;
    overflow: hidden;

    min-height: 140px;
    padding: 22px;

    border: 0;
    border-radius: 18px;

    color: #fff;

    box-shadow:
        0 12px 28px rgba(15,23,42,.10);

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}

.student-stat-card:hover {
    transform: translateY(-5px);

    box-shadow:
        0 18px 36px rgba(15,23,42,.16);
}

.stat-total {
    background:
        linear-gradient(135deg, #2563eb, #1d4ed8);
}

.stat-boys {
    background:
        linear-gradient(135deg, #0891b2, #0369a1);
}

.stat-girls {
    background:
        linear-gradient(135deg, #ec4899, #db2777);
}

.stat-classes {
    background:
        linear-gradient(135deg, #7c3aed, #4f46e5);
}

.stat-icon {
    width: 46px;
    height: 46px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 15px;

    border-radius: 13px;

    background: rgba(255,255,255,.17);
    color: #fff;

    font-size: 19px;
}

.stat-background-icon {
    position: absolute;
    right: -12px;
    bottom: -25px;

    color: rgba(255,255,255,.10);

    font-size: 110px;

    pointer-events: none;
}

.stat-label {
    margin-bottom: 4px;

    color: rgba(255,255,255,.78);

    font-size: 10px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .7px;
}

.stat-value {
    color: #fff;

    font-size: 28px;
    line-height: 1;

    font-weight: 800;
}

.stat-description {
    margin-top: 9px;

    color: rgba(255,255,255,.76);

    font-size: 10px;
    font-weight: 600;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.profile-back-row {
    margin: 24px 0 18px;
}

.back-students-btn {
    min-height: 40px;

    padding: 8px 16px;

    border: 1px solid #dce3ed;
    border-radius: 10px;

    background: #fff;
    color: #475467;

    font-size: 11px;
    font-weight: 700;

    box-shadow:
        0 4px 12px rgba(15,23,42,.04);

    transition: all .2s ease;
}

.back-students-btn:hover {
    color: #2563eb;

    border-color: #bfdbfe;
    background: #f8fbff;

    transform: translateX(-3px);
}


/* =========================================================
   SEARCH
========================================================= */

.search-card {
    overflow: hidden;

    margin-bottom: 24px;

    border: 1px solid #e5eaf1;
    border-radius: 18px;

    background: #fff;

    box-shadow:
        0 8px 25px rgba(15,23,42,.055);
}

.search-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 18px 22px;

    border-bottom: 1px solid #edf0f4;

    background:
        linear-gradient(
            90deg,
            #ffffff,
            #f8fbff
        );
}

.search-title-wrapper {
    display: flex;
    align-items: center;
}

.search-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-right: 13px;

    border-radius: 12px;

    background: #eaf2ff;
    color: #2563eb;

    font-size: 18px;
}

.search-card-header h5 {
    margin: 0 0 3px;

    color: #172033;

    font-size: 15px;
    font-weight: 800;
}

.search-card-header p {
    margin: 0;

    color: #8994a6;

    font-size: 11px;
}

.search-badge {
    padding: 7px 12px;

    border-radius: 20px;

    background: #eef5ff;
    color: #2563eb;

    font-size: 10px;
    font-weight: 800;
}

.search-card-body {
    padding: 22px;
}

.search-card label {
    display: block;

    margin-bottom: 7px;

    color: #344054;

    font-size: 11px;
    font-weight: 800;
}

.form-control {
    min-height: 45px;

    border: 1px solid #dce2ea;
    border-radius: 10px;

    font-size: 12px;
}

.form-control:focus {
    border-color: #60a5fa;

    box-shadow:
        0 0 0 .2rem rgba(37,99,235,.08);
}

.search-main-btn {
    min-height: 45px;

    border: 0;
    border-radius: 10px;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );

    font-size: 11px;
    font-weight: 800;

    box-shadow:
        0 8px 18px rgba(37,99,235,.20);
}


/* =========================================================
   ALERT
========================================================= */

.alert-area {
    margin-bottom: 18px;
}

.alert-area .alert {
    margin-bottom: 0;

    border: 0;
    border-radius: 12px;

    font-size: 12px;

    box-shadow:
        0 5px 18px rgba(15,23,42,.05);
}


/* =========================================================
   LOADING
========================================================= */

.loading-box {
    display: none;

    margin-bottom: 24px;

    padding: 45px 20px;

    text-align: center;

    border: 1px solid #e5eaf1;
    border-radius: 18px;

    background: #fff;

    box-shadow:
        0 8px 25px rgba(15,23,42,.05);
}


/* =========================================================
   PROFILE CARD
========================================================= */

.profile-card {
    overflow: hidden;

    margin-bottom: 24px;

    border: 1px solid #e1e7f0;
    border-radius: 22px;

    background: #fff;

    box-shadow:
        0 12px 35px rgba(15,23,42,.075);
}

.profile-cover {
    position: relative;

    min-height: 155px;

    overflow: hidden;

    background:
        linear-gradient(
            120deg,
            #1e40af 0%,
            #2563eb 40%,
            #0891b2 100%
        );
}

.profile-cover::before {
    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    right: -80px;
    top: -175px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.profile-cover::after {
    content: "";

    position: absolute;

    width: 210px;
    height: 210px;

    right: 160px;
    bottom: -170px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);
}


/* =========================================================
   PROFILE MAIN
========================================================= */

.profile-main {
    padding: 0 28px 27px;
}

.profile-top {
    position: relative;
    z-index: 5;

    display: flex;
    align-items: flex-end;

    gap: 20px;

    margin-top: -62px;
}


/* =========================================================
   PROFILE IMAGE
========================================================= */

.profile-image-wrap {
    width: 128px;
    height: 128px;
    min-width: 128px;

    padding: 5px;

    border-radius: 21px;

    background: #fff;

    box-shadow:
        0 12px 30px rgba(15,23,42,.20);
}

.profile-image {
    width: 100%;
    height: 100%;

    object-fit: cover;

    border-radius: 16px;

    background: #eef2f7;
}


/* =========================================================
   PROFILE INFO
========================================================= */

.profile-name {
    margin-bottom: 3px;

    color: #172033;

    font-size: 26px;
    line-height: 1.2;

    font-weight: 800;
}

.profile-marathi {
    margin-bottom: 10px;

    color: #667085;

    font-size: 14px;
    font-weight: 600;
}

.profile-meta {
    display: flex;
    flex-wrap: wrap;

    gap: 7px;
}

.profile-meta > span {
    display: inline-flex;
    align-items: center;

    padding: 6px 10px;

    border: 1px solid #e1e7ef;
    border-radius: 8px;

    background: #f8fafc;

    color: #475467;

    font-size: 10px;
    font-weight: 700;
}


/* =========================================================
   STATUS
========================================================= */

.sp-status {
    display: inline-flex !important;
    align-items: center;

    gap: 5px;

    border-radius: 20px !important;

    font-size: 10px !important;
    font-weight: 800 !important;
}

.sp-status-active {
    color: #047857 !important;
    background: #ecfdf5 !important;
    border-color: #a7f3d0 !important;
}

.sp-status-inactive {
    color: #b91c1c !important;
    background: #fef2f2 !important;
    border-color: #fecaca !important;
}


/* =========================================================
   PROFILE ACTIONS
========================================================= */

.profile-actions {
    margin-left: auto;
    align-self: flex-start;

    padding-top: 15px;
}

.profile-actions .btn {
    min-height: 39px;

    padding: 8px 14px;

    border-radius: 9px;

    font-size: 11px;
    font-weight: 800;
}


/* =========================================================
   TABS
========================================================= */

.sp-tabs-wrapper {
    overflow: hidden;

    border: 1px solid #e2e7ef;
    border-radius: 20px;

    background: #fff;

    box-shadow:
        0 10px 30px rgba(15,23,42,.055);
}

.sp-tabs {
    display: flex;

    padding: 7px;

    margin: 0;

    background: #f7f9fc;

    border-bottom: 1px solid #e8edf3;
}

.sp-tabs .nav-link {
    position: relative;

    margin-right: 4px;

    padding: 12px 16px;

    border: 0;
    border-radius: 10px;

    background: transparent;

    color: #64748b;

    font-size: 11px;
    font-weight: 800;

    transition: all .2s ease;
}

.sp-tabs .nav-link:hover {
    color: #2563eb;

    background: #edf4ff;
}

.sp-tabs .nav-link.active {
    color: #fff;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );

    box-shadow:
        0 5px 12px rgba(37,99,235,.18);
}


/* =========================================================
   TAB CONTENT
========================================================= */

.sp-tab-content {
    padding: 25px;
}

.sp-tab-content .tab-pane {
    display: none !important;
}

.sp-tab-content .tab-pane.active {
    display: block !important;
}


/* =========================================================
   INFORMATION SECTION
========================================================= */

.info-section {
    margin-bottom: 22px;

    padding: 21px;

    border: 1px solid #e6ebf2;
    border-radius: 16px;

    background: #fff;

    box-shadow:
        0 5px 18px rgba(15,23,42,.035);
}

.info-section:last-child {
    margin-bottom: 0;
}


/* =========================================================
   SECTION TITLE
========================================================= */

.section-title {
    display: flex;
    align-items: center;
    gap: 11px;

    margin-bottom: 18px;
    padding-bottom: 13px;

    border-bottom: 1px solid #edf0f4;

    color: #1f2937;

    font-size: 14px;
    font-weight: 800;
}

.section-title i {
    width: 37px;
    height: 37px;
    min-width: 37px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #eaf2ff;
    color: #2563eb;

    font-size: 15px;
}


/* =========================================================
   INFORMATION GRID
========================================================= */

.info-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 13px;
}


/* =========================================================
   INFORMATION ITEM
========================================================= */

.info-item {
    position: relative;

    min-height: 70px;

    padding: 13px 15px 13px 18px;

    overflow: hidden;

    border: 1px solid #e5eaf1;
    border-radius: 11px;

    background: #f9fafc;

    transition:
        transform .2s ease,
        box-shadow .2s ease,
        border-color .2s ease;
}

.info-item::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 3px;

    background: #2563eb;
}

.info-item:hover {
    transform: translateY(-2px);

    border-color: #cbdcf7;

    background: #fff;

    box-shadow:
        0 7px 18px rgba(37,99,235,.07);
}

.info-label {
    margin-bottom: 5px;

    color: #8a94a6;

    font-size: 9px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .55px;
}

.info-value {
    min-height: 20px;

    color: #344054;

    font-size: 12px;
    line-height: 1.5;

    font-weight: 700;

    word-break: break-word;
}


/* =========================================================
   DIFFERENT SECTION COLORS
========================================================= */

#academicTab .info-item::before {
    background: #7c3aed;
}

#parentsTab .info-item::before {
    background: #db2777;
}

#previousSchoolTab .info-item::before {
    background: #0891b2;
}

#addressTab .info-item::before {
    background: #059669;
}

#academicTab .section-title i {
    color: #7c3aed;
    background: #f1eafe;
}

#parentsTab .section-title i {
    color: #db2777;
    background: #fce7f3;
}

#previousSchoolTab .section-title i {
    color: #0891b2;
    background: #e6f8fb;
}

#addressTab .section-title i {
    color: #059669;
    background: #e8f8f1;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 70px 20px;

    text-align: center;

    border: 1px solid #e4e9f1;
    border-radius: 20px;

    background: #fff;

    box-shadow:
        0 10px 28px rgba(15,23,42,.05);
}

.empty-state i {
    display: inline-flex;

    width: 75px;
    height: 75px;

    align-items: center;
    justify-content: center;

    margin-bottom: 18px;

    border-radius: 22px;

    background: #edf4ff;
    color: #5b8def;

    font-size: 35px;
}

.empty-state h5 {
    margin-bottom: 7px;

    color: #344054;

    font-size: 17px;
    font-weight: 800;
}

.empty-state p {
    color: #98a2b3;

    font-size: 11px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1199px) {

    .info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 991px) {

    .profile-top {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .profile-actions {
        width: 100%;
        margin-left: 0;
        padding-top: 0;
    }

}


@media (max-width: 767px) {

    .student-profile-page {
        padding: 15px;
    }

    .welcome-card {
        padding: 22px;
    }

    .welcome-card h2 {
        font-size: 19px;
    }

    .search-card-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .search-badge {
        display: none;
    }

    .profile-main {
        padding: 0 17px 20px;
    }

    .profile-top {
        flex-direction: column;

        margin-top: -55px;
    }

    .profile-name {
        font-size: 21px;
    }

    .profile-actions {
        width: 100%;
    }

    .profile-actions .btn {
        width: 100%;
    }

    .profile-image-wrap {
        width: 112px;
        height: 112px;
        min-width: 112px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .sp-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
    }

    .sp-tabs .nav-link {
        white-space: nowrap;
    }

    .sp-tab-content {
        padding: 15px;
    }

    .info-section {
        padding: 15px;
    }

}


@media (max-width: 575px) {

    .student-stat-card {
        min-height: 130px;
        padding: 18px;
    }

    .stat-value {
        font-size: 24px;
    }

}


/* =========================================================
   PRINT
========================================================= */

@media print {

    body {
        background: #fff !important;
    }

    .student-profile-page {
        padding: 0 !important;
        background: #fff !important;
    }

    .welcome-card,
    .student-stat-card,
    .profile-back-row,
    .search-card,
    .alert-area,
    .profile-actions,
    .sp-tabs {
        display: none !important;
    }

    .profile-card,
    .sp-tabs-wrapper {
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    .profile-cover {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    .sp-tab-content .tab-pane {
        display: block !important;
    }

    .info-section {
        break-inside: avoid;
    }

}

</style>


<div class="student-profile-page">


    {{-- =========================================================
         WELCOME
    ========================================================== --}}

    <div class="welcome-card">

        <div class="welcome-content">

            <div class="welcome-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>

                <h2>
                    Student Profile
                </h2>

                <p>
                    Search and view complete student information from one place.
                </p>

            </div>

        </div>

        <div class="welcome-decoration decoration-one"></div>
        <div class="welcome-decoration decoration-two"></div>
        <div class="welcome-decoration decoration-three"></div>

    </div>


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="student-stat-card stat-total">

                <div class="stat-background-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div class="stat-label">
                    Total Students
                </div>

                <div class="stat-value">
                    {{ number_format($totalStudents) }}
                </div>

                <div class="stat-description">
                    <i class="bi bi-person-check-fill me-1"></i>
                    All registered students
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="student-stat-card stat-boys">

                <div class="stat-background-icon">
                    <i class="bi bi-gender-male"></i>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-gender-male"></i>
                </div>

                <div class="stat-label">
                    Boys
                </div>

                <div class="stat-value">
                    {{ number_format($totalMaleStudents) }}
                </div>

                <div class="stat-description">
                    <i class="bi bi-person-fill me-1"></i>
                    Male students
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="student-stat-card stat-girls">

                <div class="stat-background-icon">
                    <i class="bi bi-gender-female"></i>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-gender-female"></i>
                </div>

                <div class="stat-label">
                    Girls
                </div>

                <div class="stat-value">
                    {{ number_format($totalFemaleStudents) }}
                </div>

                <div class="stat-description">
                    <i class="bi bi-person-fill me-1"></i>
                    Female students
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="student-stat-card stat-classes">

                <div class="stat-background-icon">
                    <i class="bi bi-building"></i>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-building"></i>
                </div>

                <div class="stat-label">
                    Classes
                </div>

                <div class="stat-value">
                    {{ count($classes) }}
                </div>

                <div class="stat-description">
                    <i class="bi bi-grid-fill me-1"></i>
                    Available classes
                </div>

            </div>

        </div>

    </div>


    {{-- BACK --}}

    <div class="profile-back-row">

        <a href="{{ route('admin.students.index') }}"
           class="btn back-students-btn">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Students

        </a>

    </div>


    {{-- ALERT --}}

    <div class="alert-area"
         id="alertArea">
    </div>


    {{-- =========================================================
         SEARCH
    ========================================================== --}}

    <div class="search-card">

        <div class="search-card-header">

            <div class="search-title-wrapper">

                <div class="search-icon">
                    <i class="bi bi-search"></i>
                </div>

                <div>

                    <h5>
                        Find Student Profile
                    </h5>

                    <p>
                        Enter a unique Student ID to view complete details.
                    </p>

                </div>

            </div>

            <div class="search-badge">
                <i class="bi bi-funnel-fill me-1"></i>
                Student Search
            </div>

        </div>


        <div class="search-card-body">

            <form id="studentSearchForm">

                <div class="row align-items-end g-3">

                    <div class="col-lg-8">

                        <label for="studentId">
                            Student ID
                        </label>

                        <input
                            type="text"
                            id="studentId"
                            name="student_id"
                            class="form-control"
                            placeholder="Enter Student ID"
                            autocomplete="off"
                        >

                        <div class="form-text">
                            Example: Enter the Student ID generated during registration.
                        </div>

                    </div>

                    <div class="col-lg-4">

                        <button
                            type="submit"
                            class="btn btn-primary w-100 search-main-btn"
                            id="searchStudentBtn"
                        >

                            <i class="bi bi-search me-1"></i>

                            Search Student

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- LOADING --}}

    <div class="loading-box"
         id="loadingBox">

        <div class="spinner-border text-primary mb-3"
             role="status">

            <span class="visually-hidden">
                Loading...
            </span>

        </div>

        <div class="fw-semibold">
            Loading student profile...
        </div>

    </div>


    {{-- =========================================================
         PROFILE
    ========================================================== --}}

    <div id="studentProfileContent"
         style="display:none;">


        {{-- PROFILE HEADER --}}

        <div class="profile-card">

            <div class="profile-cover"></div>

            <div class="profile-main">

                <div class="profile-top">

                    {{-- IMAGE --}}

                    <div class="profile-image-wrap">

                        <img
                            src=""
                            id="studentProfileImage"
                            class="profile-image"
                            alt="Student Photo"
                        >

                    </div>


                    {{-- INFORMATION --}}

                    <div class="flex-grow-1">

                        <div class="profile-name"
                             id="studentFullName">
                            -
                        </div>

                        <div class="profile-marathi"
                             id="studentMarathiName">
                            -
                        </div>

                        <div class="profile-meta">

                            <span>
                                <i class="bi bi-person-badge-fill me-1"></i>
                                <span id="profileStudentId">-</span>
                            </span>

                            <span>
                                <i class="bi bi-building-fill me-1"></i>
                                Class:
                                <span id="profileClass">-</span>
                            </span>

                            <span>
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i>
                                Section:
                                <span id="profileSection">-</span>
                            </span>

                            <span>
                                <i class="bi bi-list-ol me-1"></i>
                                Roll:
                                <span id="profileRollNumber">-</span>
                            </span>

                            <span
                                class="sp-status sp-status-active"
                                id="profileStatus"
                            >
                                <i class="bi bi-circle-fill"></i>
                                Active
                            </span>

                        </div>

                    </div>


                    {{-- ACTION --}}

                    <div class="profile-actions">

                        <button
                            type="button"
                            class="btn btn-outline-primary btn-sm"
                            onclick="window.print()"
                        >

                            <i class="bi bi-printer-fill me-1"></i>

                            Print Profile

                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TABS
        ====================================================== --}}

        <div class="sp-tabs-wrapper">

            <ul class="nav nav-tabs sp-tabs"
                id="studentTabs"
                role="tablist">

                <li class="nav-item">

                    <button
                        class="nav-link active"
                        type="button"
                        data-bs-target="#basicTab"
                    >

                        <i class="bi bi-person-fill me-1"></i>

                        Basic Information

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        type="button"
                        data-bs-target="#academicTab"
                    >

                        <i class="bi bi-mortarboard-fill me-1"></i>

                        Academic

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        type="button"
                        data-bs-target="#parentsTab"
                    >

                        <i class="bi bi-people-fill me-1"></i>

                        Parents

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        type="button"
                        data-bs-target="#previousSchoolTab"
                    >

                        <i class="bi bi-bank2 me-1"></i>

                        Previous School

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        type="button"
                        data-bs-target="#addressTab"
                    >

                        <i class="bi bi-geo-alt-fill me-1"></i>

                        Address

                    </button>

                </li>

            </ul>


            <div class="sp-tab-content">


                {{-- =================================================
                     BASIC
                ================================================== --}}

                <div
                    class="tab-pane active"
                    id="basicTab"
                >

                    <div class="info-section">

                        <div class="section-title">

                            <i class="bi bi-person-circle"></i>

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


                {{-- =================================================
                     ACADEMIC
                ================================================== --}}

                <div
                    class="tab-pane"
                    id="academicTab"
                >

                    <div class="info-section">

                        <div class="section-title">

                            <i class="bi bi-mortarboard-fill"></i>

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


                {{-- =================================================
                     PARENTS
                ================================================== --}}

                <div
                    class="tab-pane"
                    id="parentsTab"
                >

                    <div class="info-section">

                        <div class="section-title">

                            <i class="bi bi-people-fill"></i>

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


                {{-- =================================================
                     PREVIOUS SCHOOL
                ================================================== --}}

                <div
                    class="tab-pane"
                    id="previousSchoolTab"
                >

                    <div class="info-section">

                        <div class="section-title">

                            <i class="bi bi-bank2"></i>

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

                            <div class="info-item"
                                 style="grid-column:1 / -1;">

                                <div class="info-label">
                                    School Address
                                </div>

                                <div class="info-value"
                                     id="previousSchoolAddress">
                                    -
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ADDRESS
                ================================================== --}}

                <div
                    class="tab-pane"
                    id="addressTab"
                >

                    <div class="info-section">

                        <div class="section-title">

                            <i class="bi bi-geo-alt-fill"></i>

                            Address Information

                        </div>

                        <div class="info-grid">

                            <div class="info-item"
                                 style="grid-column:1 / -1;">

                                <div class="info-label">
                                    Address
                                </div>

                                <div class="info-value"
                                     id="addressValue">
                                    -
                                </div>

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

        </div>

    </div>


    {{-- EMPTY STATE --}}

    <div
        class="empty-state"
        id="emptyStudentState"
    >

        <i class="bi bi-person-search"></i>

        <h5>
            Search for a Student
        </h5>

        <p class="mb-0">
            Enter a Student ID above to view the complete student profile.
        </p>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       TABS
    ========================================================= */

    const tabButtons =
        document.querySelectorAll(
            '#studentTabs [data-bs-target]'
        );

    const tabPanes =
        document.querySelectorAll(
            '#studentProfileContent .tab-pane'
        );

    function activateTab(button) {

        if (!button) {
            return;
        }

        const targetSelector =
            button.getAttribute('data-bs-target');

        if (!targetSelector) {
            return;
        }

        tabButtons.forEach(function (tab) {

            tab.classList.remove('active');

        });

        tabPanes.forEach(function (pane) {

            pane.classList.remove('active');

        });

        button.classList.add('active');

        const targetPane =
            document.querySelector(targetSelector);

        if (targetPane) {

            targetPane.classList.add('active');

        }

    }

    tabButtons.forEach(function (button) {

        button.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                activateTab(this);

            }
        );

    });


    /* =========================================================
       ALERT
    ========================================================= */

    function showAlert(
        message,
        type = 'danger'
    ) {

        const alertArea =
            document.getElementById('alertArea');

        if (!alertArea) {
            return;
        }

        alertArea.innerHTML = `

            <div
                class="alert alert-${type} alert-dismissible fade show"
                role="alert"
            >

                ${message}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>

            </div>

        `;

    }


    /* =========================================================
       SAFE VALUE
    ========================================================= */

    function valueOrDash(value) {

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {

            return '-';

        }

        return value;

    }


    /* =========================================================
       SET TEXT
    ========================================================= */

    function setText(id, value) {

        const element =
            document.getElementById(id);

        if (element) {

            element.textContent =
                valueOrDash(value);

        }

    }


    /* =========================================================
       LOAD STUDENT
    ========================================================= */

    function loadStudent(student) {

        const fullName = [

            student.first_name,
            student.middle_name,
            student.last_name

        ]
        .filter(function (value) {

            return value !== null &&
                   value !== undefined &&
                   value !== '';

        })
        .join(' ');


        /* PROFILE HEADER */

        setText(
            'studentFullName',
            fullName
        );

        setText(
            'studentMarathiName',
            student.marathi_name
        );

        setText(
            'profileStudentId',
            student.student_id
        );

        setText(
            'profileClass',
            student.class
        );

        setText(
            'profileSection',
            student.section
        );

        setText(
            'profileRollNumber',
            student.roll_number
        );


        /* STATUS */

        const statusElement =
            document.getElementById(
                'profileStatus'
            );

        if (statusElement) {

            const status =
                String(
                    student.status || 'active'
                ).toLowerCase();

            statusElement.classList.remove(
                'sp-status-active',
                'sp-status-inactive'
            );

            if (status === 'active') {

                statusElement.classList.add(
                    'sp-status-active'
                );

                statusElement.innerHTML =
                    '<i class="bi bi-circle-fill"></i> Active';

            } else {

                statusElement.classList.add(
                    'sp-status-inactive'
                );

                statusElement.innerHTML =
                    '<i class="bi bi-circle-fill"></i> Inactive';

            }

        }


        /* PROFILE IMAGE */

        const profileImage =
            document.getElementById(
                'studentProfileImage'
            );

        if (profileImage) {

            const imageUrl =
                student.profile_image ||
                student.image ||
                (
                    'https://ui-avatars.com/api/?name=' +
                    encodeURIComponent(
                        fullName || 'Student'
                    ) +
                    '&background=2563eb&color=fff&size=300'
                );

            profileImage.src = imageUrl;

            profileImage.onerror = function () {

                this.onerror = null;

                this.src =
                    'https://ui-avatars.com/api/?name=' +
                    encodeURIComponent(
                        fullName || 'Student'
                    ) +
                    '&background=2563eb&color=fff&size=300';

            };

        }


        /* BASIC */

        setText('basicFirstName', student.first_name);
        setText('basicMiddleName', student.middle_name);
        setText('basicLastName', student.last_name);
        setText('basicMarathiName', student.marathi_name);
        setText('basicGender', student.gender);
        setText('basicDob', student.date_of_birth);
        setText('basicAadhar', student.aadhar_card_no);
        setText('basicPhone', student.phone);
        setText('basicEmail', student.email);


        /* ACADEMIC */

        setText(
            'academicYear',
            student.academic_year ||
            student.educational_year
        );

        setText('academicClass', student.class);
        setText('academicSection', student.section);
        setText('academicRollNumber', student.roll_number);
        setText('academicAdmissionClass', student.admission_class);
        setText('academicAdmissionDate', student.admission_date);
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


        /* PARENTS */

        setText('fatherName', student.father_name);
        setText('fatherPhone', student.father_phone);
        setText('fatherOccupation', student.father_occupation);

        setText('motherName', student.mother_name);
        setText('motherPhone', student.mother_phone);
        setText('motherOccupation', student.mother_occupation);

        setText('guardianName', student.guardian_name);
        setText('guardianRelation', student.guardian_relation);
        setText('guardianPhone', student.guardian_phone);


        /* PREVIOUS SCHOOL */

        setText(
            'previousSchoolName',
            student.previous_school_name
        );

        setText(
            'previousSchoolClass',
            student.previous_school_class
        );

        setText(
            'previousSchoolMedium',
            student.previous_school_medium
        );

        setText(
            'previousSchoolBoard',
            student.previous_school_board
        );

        setText(
            'previousSchoolResult',
            student.previous_school_result
        );

        setText(
            'previousSchoolRemarks',
            student.previous_school_remarks
        );

        setText(
            'previousSchoolAddress',
            student.previous_school_address
        );


        /* ADDRESS */

        setText(
            'addressValue',
            student.address
        );

        setText(
            'addressCountry',
            student.country
        );

        setText(
            'addressState',
            student.state
        );

        setText(
            'addressDistrict',
            student.district
        );

        setText(
            'addressTaluka',
            student.taluka
        );

        setText(
            'addressCityVillage',
            student.city_village
        );

        setText(
            'addressPincode',
            student.pincode
        );


        /* SHOW PROFILE */

        const profileContent =
            document.getElementById(
                'studentProfileContent'
            );

        const emptyState =
            document.getElementById(
                'emptyStudentState'
            );

        if (profileContent) {

            profileContent.style.display =
                'block';

        }

        if (emptyState) {

            emptyState.style.display =
                'none';

        }


        /* RESET BASIC TAB */

        const basicTab =
            document.querySelector(
                '#studentTabs [data-bs-target="#basicTab"]'
            );

        if (basicTab) {

            activateTab(basicTab);

        }

    }


    /* =========================================================
       SEARCH
    ========================================================= */

    const searchForm =
        document.getElementById(
            'studentSearchForm'
        );

    if (searchForm) {

        searchForm.addEventListener(
            'submit',
            function (event) {

                event.preventDefault();

                const studentInput =
                    document.getElementById(
                        'studentId'
                    );

                const studentId =
                    studentInput
                        ? studentInput.value.trim()
                        : '';

                if (!studentId) {

                    showAlert(
                        'Please enter a Student ID.',
                        'warning'
                    );

                    if (studentInput) {
                        studentInput.focus();
                    }

                    return;

                }


                const searchButton =
                    document.getElementById(
                        'searchStudentBtn'
                    );

                const loadingBox =
                    document.getElementById(
                        'loadingBox'
                    );

                const profileContent =
                    document.getElementById(
                        'studentProfileContent'
                    );

                const emptyState =
                    document.getElementById(
                        'emptyStudentState'
                    );


                if (searchButton) {

                    searchButton.disabled = true;

                    searchButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>' +
                        'Searching...';

                }


                if (loadingBox) {

                    loadingBox.style.display =
                        'block';

                }


                if (profileContent) {

                    profileContent.style.display =
                        'none';

                }


                const alertArea =
                    document.getElementById(
                        'alertArea'
                    );

                if (alertArea) {

                    alertArea.innerHTML = '';

                }


                fetch(
                    '{{ route("admin.student-profile.search") }}' +
                    '?student_id=' +
                    encodeURIComponent(studentId),
                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                )

                .then(function (response) {

                    return response.json()

                        .then(function (data) {

                            return {
                                ok: response.ok,
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

                    const student =
                        result.data.student ||
                        result.data.data ||
                        result.data;

                    if (!student) {

                        throw new Error(
                            'Student data was not returned.'
                        );

                    }

                    loadStudent(student);

                    showAlert(
                        'Student profile loaded successfully.',
                        'success'
                    );

                })

                .catch(function (error) {

                    if (profileContent) {

                        profileContent.style.display =
                            'none';

                    }

                    if (emptyState) {

                        emptyState.style.display =
                            'block';

                    }

                    showAlert(
                        error.message ||
                        'Unable to load student profile.',
                        'danger'
                    );

                })

                .finally(function () {

                    if (loadingBox) {

                        loadingBox.style.display =
                            'none';

                    }

                    if (searchButton) {

                        searchButton.disabled =
                            false;

                        searchButton.innerHTML =
                            '<i class="bi bi-search me-1"></i>' +
                            'Search Student';

                    }

                });

            }
        );

    }

});

</script>

@endsection