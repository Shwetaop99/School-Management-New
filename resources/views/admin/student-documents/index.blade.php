@extends('layouts.app')

@section('content')

<style>

/* =========================================================
   DOCUMENT CENTER PAGE
========================================================= */

.documents-page {
    background:
        radial-gradient(circle at 5% 10%, rgba(59,115,209,.035), transparent 25%),
        radial-gradient(circle at 95% 20%, rgba(19,161,200,.035), transparent 25%),
        #f6f8fc;

    min-height: calc(100vh - 70px);
    padding: 28px;
}


/* =========================================================
   WELCOME HERO
========================================================= */

.documents-welcome {
    position: relative;
    overflow: hidden;

    min-height: 175px;
    padding: 30px 34px;
    margin-bottom: 24px;

    border-radius: 22px;

    background:
        linear-gradient(
            135deg,
            #1557c0 0%,
            #087fca 48%,
            #13a1c8 100%
        );

    color: #fff;

    box-shadow:
        0 14px 35px rgba(21,87,192,.17);
}

.documents-welcome::after {
    content: "";

    position: absolute;

    width: 330px;
    height: 330px;

    right: -110px;
    top: -190px;

    border-radius: 50%;

    border: 1px solid rgba(255,255,255,.08);
}

.documents-welcome-content {
    position: relative;
    z-index: 5;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 25px;
}

.documents-welcome-left {
    display: flex;
    align-items: center;

    gap: 20px;
}

.documents-welcome-icon {
    width: 66px;
    height: 66px;
    min-width: 66px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 18px;

    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.22);

    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.18),
        0 8px 20px rgba(0,0,0,.08);

    font-size: 28px;

    backdrop-filter: blur(8px);

    transition:
        transform .25s ease,
        background .25s ease;
}

.documents-welcome:hover .documents-welcome-icon {
    transform: translateY(-3px) rotate(-2deg);
    background: rgba(255,255,255,.19);
}

.documents-welcome h2 {
    margin: 0 0 7px;

    font-size: 29px;
    font-weight: 800;

    letter-spacing: -.5px;
}

.documents-welcome p {
    margin: 0;

    color: rgba(255,255,255,.90);

    font-size: 14px;
}

.welcome-badge {
    display: inline-flex;
    align-items: center;

    gap: 7px;

    margin-top: 14px;
    padding: 7px 13px;

    border-radius: 20px;

    background: rgba(255,255,255,.13);
    border: 1px solid rgba(255,255,255,.16);

    color: rgba(255,255,255,.95);

    font-size: 11px;
    font-weight: 700;

    backdrop-filter: blur(5px);
}

.welcome-badge i {
    font-size: 13px;
}


/* =========================================================
   WELCOME DECORATIONS
========================================================= */

.documents-welcome-decoration {
    position: absolute;

    border-radius: 50%;

    background: rgba(255,255,255,.07);

    pointer-events: none;
}

.welcome-circle-one {
    width: 250px;
    height: 250px;

    right: -75px;
    top: -150px;
}

.welcome-circle-two {
    width: 180px;
    height: 180px;

    right: 150px;
    bottom: -135px;
}

.welcome-circle-three {
    width: 80px;
    height: 80px;

    right: 60px;
    bottom: 20px;

    background: rgba(255,255,255,.05);
}


/* =========================================================
   WELCOME SIDE CARD
========================================================= */

.welcome-side-card {
    position: relative;
    z-index: 5;

    min-width: 205px;

    padding: 15px 18px;

    border-radius: 15px;

    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.16);

    backdrop-filter: blur(8px);

    transition:
        transform .25s ease,
        background .25s ease;
}

.welcome-side-card:hover {
    transform: translateY(-3px);
    background: rgba(255,255,255,.16);
}

.welcome-side-label {
    display: block;

    color: rgba(255,255,255,.70);

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .8px;

    margin-bottom: 4px;
}

.welcome-side-title {
    display: flex;
    align-items: center;

    gap: 8px;

    font-size: 14px;
    font-weight: 700;
}

.welcome-side-title i {
    font-size: 17px;
}


/* =========================================================
   QUICK STAT CARDS
========================================================= */

.document-stat-card {
    position: relative;
    overflow: hidden;

    min-height: 112px;

    padding: 20px;

    display: flex;
    align-items: center;

    gap: 15px;

    background: rgba(255,255,255,.96);

    border: 1px solid #e8edf4;
    border-radius: 17px;

    box-shadow:
        0 5px 18px rgba(31,41,55,.045);

    transition:
        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease;
}

.document-stat-card:hover {
    transform: translateY(-5px);

    box-shadow:
        0 14px 30px rgba(31,41,55,.09);

    border-color: #dce5f0;
}

.document-stat-card::after {
    content: "";

    position: absolute;

    width: 85px;
    height: 85px;

    right: -30px;
    bottom: -35px;

    border-radius: 50%;

    background: rgba(59,115,209,.045);

    transition:
        transform .3s ease;
}

.document-stat-card:hover::after {
    transform: scale(1.2);
}

.document-stat-icon {
    position: relative;
    z-index: 2;

    width: 52px;
    height: 52px;
    min-width: 52px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    font-size: 22px;

    transition:
        transform .25s ease;
}

.document-stat-card:hover .document-stat-icon {
    transform: scale(1.08);
}

.stat-blue .document-stat-icon {
    background: #eaf1ff;
    color: #3972ce;
}

.stat-green .document-stat-icon {
    background: #eaf7f1;
    color: #3c9674;
}

.stat-orange .document-stat-icon {
    background: #fff4e7;
    color: #c9863d;
}

.stat-purple .document-stat-icon {
    background: #f2efff;
    color: #7565bd;
}

.document-stat-content {
    position: relative;
    z-index: 2;
}

.document-stat-number {
    color: #26364a;

    font-size: 25px;
    line-height: 1.1;

    font-weight: 800;
}

.document-stat-label {
    margin-top: 5px;

    color: #7b8794;

    font-size: 12px;
    font-weight: 600;
}


/* =========================================================
   SECTION HEADER
========================================================= */

.documents-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    margin-top: 28px;
    margin-bottom: 16px;
}

.documents-section-title {
    display: flex;
    align-items: center;

    gap: 11px;
}

.documents-section-title-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #eaf1ff;
    color: #3b73d1;

    font-size: 17px;
}

.documents-section-title h4 {
    margin: 0;

    color: #26364a;

    font-size: 18px;
    font-weight: 750;
}

.documents-section-title p {
    margin: 2px 0 0;

    color: #8a95a3;

    font-size: 11px;
}

.documents-count {
    padding: 6px 11px;

    border-radius: 20px;

    background: #fff;

    border: 1px solid #e3e9f1;

    color: #6e7c8f;

    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   DOCUMENT CARDS
========================================================= */

.document-card {
    position: relative;
    overflow: hidden;

    background: #fff;

    border: 1px solid #e6ebf2;
    border-radius: 19px;

    padding: 24px;

    min-height: 305px;
    height: 100%;

    box-shadow:
        0 5px 18px rgba(31,41,55,.045);

    transition:
        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease;
}

.document-card:hover {
    transform: translateY(-7px);

    border-color: #d7e1ee;

    box-shadow:
        0 16px 34px rgba(31,41,55,.10);
}

.document-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;

    width: 100%;
    height: 4px;

    background: #dbe8ff;

    transition: height .25s ease;
}

.document-card:hover::before {
    height: 5px;
}

.document-card::after {
    content: "";

    position: absolute;

    width: 120px;
    height: 120px;

    right: -55px;
    bottom: -65px;

    border-radius: 50%;

    background: rgba(59,115,209,.035);

    transition:
        transform .3s ease;
}

.document-card:hover::after {
    transform: scale(1.15);
}

.document-card-inner {
    position: relative;
    z-index: 2;

    height: 100%;

    display: flex;
    flex-direction: column;
}


/* =========================================================
   DOCUMENT ICON
========================================================= */

.document-icon {
    width: 58px;
    height: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;

    margin-bottom: 17px;

    font-size: 25px;

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}

.document-card:hover .document-icon {
    transform: scale(1.07);

    box-shadow:
        0 8px 18px rgba(0,0,0,.06);
}


/* =========================================================
   LABEL
========================================================= */

.document-label {
    display: inline-flex;
    align-items: center;

    width: fit-content;

    gap: 5px;

    padding: 5px 10px;

    margin-bottom: 11px;

    border-radius: 20px;

    background: #f3f6fa;

    color: #738094;

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .3px;
}

.document-label i {
    font-size: 11px;
}


/* =========================================================
   DOCUMENT TEXT
========================================================= */

.document-card h5 {
    margin-bottom: 8px;

    color: #2d3b4f;

    font-size: 17px;
    font-weight: 750;
}

.document-card p {
    min-height: 45px;

    margin-bottom: 20px;

    color: #7b8794;

    font-size: 12.5px;
    line-height: 1.65;
}


/* =========================================================
   DOCUMENT BUTTON
========================================================= */

.document-button {
    position: relative;
    z-index: 3;

    display: flex;
    align-items: center;
    justify-content: space-between;

    width: 100%;
    height: 43px;

    padding: 0 15px;

    margin-top: auto;

    border-radius: 10px;

    background: #f3f6fa;

    border: 1px solid #e1e8f0;

    color: #45627f;

    text-decoration: none;

    font-size: 12.5px;
    font-weight: 700;

    transition:
        all .2s ease;
}

.document-button:hover {
    background: #e8f0ff;

    border-color: #d2e0f7;

    color: #3569b7;
}

.document-button i {
    font-size: 15px;

    transition:
        transform .2s ease;
}

.document-button:hover i {
    transform: translateX(4px);
}


/* =========================================================
   BONAFIDE
========================================================= */

.bonafide .document-icon {
    background: #edf7f3;
    color: #3d9273;
}

.bonafide::before {
    background: #d8eee5;
}

.bonafide:hover {
    border-color: #d5e9e0;
}

.bonafide .document-button:hover {
    background: #edf8f3;
    border-color: #d5eade;
    color: #398468;
}


/* =========================================================
   LEAVING
========================================================= */

.leaving .document-icon {
    background: #fff5e9;
    color: #c8873e;
}

.leaving::before {
    background: #f7e4c8;
}

.leaving:hover {
    border-color: #f0dfc8;
}

.leaving .document-button:hover {
    background: #fff8ef;
    border-color: #f1dfc7;
    color: #b87834;
}


/* =========================================================
   CASTE REPORT
========================================================= */

.caste-report .document-icon {
    background: #f2efff;
    color: #7565bd;
}

.caste-report::before {
    background: #e1dcf7;
}

.caste-report:hover {
    border-color: #dfdaf2;
}

.caste-report .document-button:hover {
    background: #f5f2ff;
    border-color: #dfd9f5;
    color: #6959ae;
}


/* =========================================================
   AGE
========================================================= */

.age .document-icon {
    background: #edf5ff;
    color: #4d83bd;
}

.age::before {
    background: #d8e8f8;
}

.age:hover {
    border-color: #d7e5f2;
}

.age .document-button:hover {
    background: #edf5ff;
    border-color: #d7e6f6;
    color: #4275aa;
}


/* =========================================================
   DISABLED
========================================================= */

.document-button.disabled {
    cursor: not-allowed;
    opacity: .60;
}

.document-button.disabled:hover {
    background: #f3f6fa;
    border-color: #e1e8f0;
    color: #45627f;
}

.document-button.disabled:hover i {
    transform: none;
}


/* =========================================================
   INFORMATION CARD
========================================================= */

.document-info-card {
    margin-top: 25px;

    padding: 20px 22px;

    display: flex;
    align-items: center;

    gap: 15px;

    background: #fff;

    border: 1px solid #e7ecf3;
    border-radius: 15px;

    box-shadow:
        0 4px 15px rgba(31,41,55,.035);

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}

.document-info-card:hover {
    transform: translateY(-2px);

    box-shadow:
        0 9px 22px rgba(31,41,55,.065);
}

.document-info-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #edf4ff;
    color: #4b7fd0;

    font-size: 18px;
}

.document-info-card h6 {
    margin: 0 0 3px;

    color: #334155;

    font-size: 13px;
    font-weight: 700;
}

.document-info-card p {
    margin: 0;

    color: #8792a1;

    font-size: 11px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 991px) {

    .documents-page {
        padding: 22px;
    }

    .documents-welcome {
        padding: 27px;
    }

    .welcome-side-card {
        display: none;
    }
}


@media(max-width: 767px) {

    .documents-page {
        padding: 17px;
    }

    .documents-welcome {
        min-height: auto;
        padding: 25px;
    }

    .documents-welcome-left {
        align-items: flex-start;
    }

    .documents-welcome-icon {
        width: 55px;
        height: 55px;
        min-width: 55px;

        font-size: 23px;
    }

    .documents-welcome h2 {
        font-size: 23px;
    }

    .documents-welcome p {
        font-size: 12px;
    }

    .document-card {
        min-height: auto;
    }
}


@media(max-width: 575px) {

    .documents-page {
        padding: 12px;
    }

    .documents-welcome {
        padding: 21px;
        border-radius: 17px;
    }

    .documents-welcome-left {
        gap: 13px;
    }

    .documents-welcome-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;

        border-radius: 13px;

        font-size: 20px;
    }

    .documents-welcome h2 {
        font-size: 20px;
    }

    .documents-welcome p {
        font-size: 11px;
    }

    .welcome-badge {
        font-size: 9px;
    }

    .document-stat-card {
        min-height: 95px;
        padding: 16px;
    }

    .document-stat-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;

        border-radius: 12px;

        font-size: 19px;
    }

    .document-stat-number {
        font-size: 22px;
    }

    .documents-section-header {
        align-items: flex-start;
    }

    .documents-count {
        display: none;
    }

    .document-card {
        padding: 21px;
    }

    .document-info-card {
        align-items: flex-start;
    }
}

</style>


<div class="documents-page">


    {{-- =====================================================
         WELCOME HERO
    ====================================================== --}}

    <div class="documents-welcome">

        <div class="documents-welcome-content">

            <div class="documents-welcome-left">

                <div class="documents-welcome-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>

                <div>

                    <h2>
                        Student Document Center
                    </h2>

                    <p>
                        Manage certificates, reports and official student documents
                        from one place.
                    </p>

                    <span class="welcome-badge">
                        <i class="bi bi-shield-check"></i>
                        Official School Documents
                    </span>

                </div>

            </div>


            <div class="welcome-side-card">

                <span class="welcome-side-label">
                    Document Management
                </span>

                <div class="welcome-side-title">

                    <i class="bi bi-file-earmark-check"></i>

                    Ready to generate

                </div>

            </div>

        </div>


        <div class="documents-welcome-decoration welcome-circle-one"></div>
        <div class="documents-welcome-decoration welcome-circle-two"></div>
        <div class="documents-welcome-decoration welcome-circle-three"></div>

    </div>


    {{-- =====================================================
         QUICK STATISTICS
    ====================================================== --}}

    <div class="row g-3">

        {{-- Available --}}
        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="document-stat-card stat-blue">

                <div class="document-stat-icon">
                    <i class="bi bi-files"></i>
                </div>

                <div class="document-stat-content">

                    <div class="document-stat-number">
                        4
                    </div>

                    <div class="document-stat-label">
                        Document Modules
                    </div>

                </div>

            </div>

        </div>


        {{-- Certificate --}}
        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="document-stat-card stat-green">

                <div class="document-stat-icon">
                    <i class="bi bi-patch-check"></i>
                </div>

                <div class="document-stat-content">

                    <div class="document-stat-number">
                        2
                    </div>

                    <div class="document-stat-label">
                        Certificate Types
                    </div>

                </div>

            </div>

        </div>


        {{-- Reports --}}
        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="document-stat-card stat-orange">

                <div class="document-stat-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <div class="document-stat-content">

                    <div class="document-stat-number">
                        2
                    </div>

                    <div class="document-stat-label">
                        Report Types
                    </div>

                </div>

            </div>

        </div>


        {{-- Print --}}
        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="document-stat-card stat-purple">

                <div class="document-stat-icon">
                    <i class="bi bi-printer"></i>
                </div>

                <div class="document-stat-content">

                    <div class="document-stat-number">
                        A4
                    </div>

                    <div class="document-stat-label">
                        Print Ready
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         DOCUMENT SECTION HEADER
    ====================================================== --}}

    <div class="documents-section-header">

        <div class="documents-section-title">

            <div class="documents-section-title-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>

            <div>

                <h4>
                    Document Services
                </h4>

                <p>
                    Select a service to manage student documents
                </p>

            </div>

        </div>


        <span class="documents-count">
            4 Services
        </span>

    </div>


    {{-- =====================================================
         DOCUMENT CARDS
    ====================================================== --}}

    <div class="row g-4">


        {{-- =================================================
             BONAFIDE CERTIFICATE
        ================================================== --}}

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="document-card bonafide">

                <div class="document-card-inner">

                    <div class="document-icon">
                        <i class="bi bi-file-earmark-person"></i>
                    </div>

                    <span class="document-label">
                        <i class="bi bi-patch-check"></i>
                        Certificate
                    </span>

                    <h5>
                        Bonafide Certificate
                    </h5>

                    <p>
                        Search a student and generate a professional
                        bonafide certificate for official school use.
                    </p>

                    <a href="{{ route('admin.bonafide.index') }}"
                       class="document-button">

                        <span>
                            Open Bonafide
                        </span>

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>


        {{-- =================================================
             LEAVING CERTIFICATE
        ================================================== --}}

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="document-card leaving">

                <div class="document-card-inner">

                    <div class="document-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>

                    <span class="document-label">
                        <i class="bi bi-file-text"></i>
                        Certificate
                    </span>

                    <h5>
                        Leaving Certificate
                    </h5>

                    <p>
                        Search a student and create an official
                        school leaving certificate.
                    </p>

                    <a href="{{ route('admin.school-leaving-certificate.index') }}"
                       class="document-button">

                        <span>
                            Create Certificate
                        </span>

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>


{{-- =================================================
     CASTE / CATEGORY REPORT
================================================== --}}

<div class="col-xl-3 col-lg-6 col-md-6">

    <div class="document-card caste-report">

        <div class="document-card-inner">

            <div class="document-icon">
                <i class="bi bi-bar-chart-fill"></i>
            </div>

            <span class="document-label">
                <i class="bi bi-pie-chart"></i>
                Report
            </span>

            <h5>
                Caste Report
            </h5>

            <p>
                View class-wise caste, boys and girls
                summary for active students.
            </p>

            <a href="{{ route('admin.caste-report.index') }}"
               class="document-button">

                <span>
                    View Report
                </span>

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</div>


{{-- =================================================
     AGE REPORT
================================================== --}}

<div class="col-xl-3 col-lg-6 col-md-6">

    <div class="document-card age">

        <div class="document-card-inner">

            <div class="document-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <span class="document-label">
                <i class="bi bi-people"></i>
                Report
            </span>

            <h5>
                Age Report
            </h5>

            <p>
                Generate class-wise student age report
                using date of birth.
            </p>

            <a href="{{ route('admin.age-report.index') }}"
               class="document-button">

                <span>
                    View Report
                </span>

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</div>


    </div>


    {{-- =====================================================
         INFORMATION BAR
    ====================================================== --}}

    <div class="document-info-card">

        <div class="document-info-icon">
            <i class="bi bi-info-circle"></i>
        </div>

        <div>

            <h6>
                Document Management
            </h6>

            <p>
                Use the available services above to search students,
                generate official documents and prepare them for printing.
            </p>

        </div>

    </div>


</div>

@endsection