@extends('layouts.app')

@section('title', 'Document Center | Admin')
@section('page-title', 'Document Center')

@section('content')

<div class="documents-page">
    <div class="container-fluid py-4">

        {{-- HERO --}}
        <section class="documents-hero">
            <div class="documents-hero-content">
                <div class="documents-hero-icon">
                    <i class="fas fa-folder-open"></i>
                </div>

                <div class="documents-hero-text">
                    <div class="documents-breadcrumb">
                        <span>Student Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Document Center</span>
                    </div>

                    <h2>Student Document Center</h2>

                    <p>
                        Manage certificates, reports and official student
                        documents from one place.
                    </p>

                    <div class="documents-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Official School Documents &amp; Records</span>
                    </div>
                </div>
            </div>

            <div class="documents-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-file-circle-check"></i>
                </div>

                <div class="hero-handwritten">Ready to generate.</div>

                <p>
                    Every document matters. Search a student, generate the
                    certificate and prepare it for printing.
                </p>

                <a href="#documentServices" class="diary-primary-btn">
                    <i class="fas fa-th-large"></i>
                    View Services
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="documents-kpis">

            <article class="documents-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Document Modules</span>
                    <span class="kpi-icon">
                        <i class="fas fa-copy"></i>
                    </span>
                </div>
                <div class="kpi-value">4</div>
                <div class="kpi-caption">Available document services</div>
            </article>

            <article class="documents-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Certificate Types</span>
                    <span class="kpi-icon">
                        <i class="fas fa-certificate"></i>
                    </span>
                </div>
                <div class="kpi-value">2</div>
                <div class="kpi-caption">Bonafide and leaving certificates</div>
            </article>

            <article class="documents-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Report Types</span>
                    <span class="kpi-icon">
                        <i class="fas fa-chart-bar"></i>
                    </span>
                </div>
                <div class="kpi-value">2</div>
                <div class="kpi-caption">Caste and age reports</div>
            </article>

            <article class="documents-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Print Format</span>
                    <span class="kpi-icon">
                        <i class="fas fa-print"></i>
                    </span>
                </div>
                <div class="kpi-value">A4</div>
                <div class="kpi-caption">Documents are print ready</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="documents-paper-panel" id="documentServices">

            <div class="documents-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div>
                        <h3>Document Services</h3>
                        <p>Select a service to manage student documents.</p>
                    </div>
                </div>

                <div class="documents-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>4</strong>
                    Services
                </div>
            </div>

            {{-- SERVICE CARDS --}}
            <div class="documents-grid">

                {{-- BONAFIDE CERTIFICATE --}}
                <article class="document-card green">
                    <div class="document-card-top">
                        <span class="document-icon">
                            <i class="fas fa-id-card"></i>
                        </span>

                        <span class="document-label">
                            <i class="fas fa-certificate"></i>
                            Certificate
                        </span>
                    </div>

                    <h5>Bonafide Certificate</h5>

                    <p>
                        Search a student and generate a professional
                        bonafide certificate for official school use.
                    </p>

                    <a href="{{ route('admin.bonafide.classes') }}"
                       class="document-button">
                        <span>Open Bonafide</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

                {{-- LEAVING CERTIFICATE --}}
                <article class="document-card orange">
                    <div class="document-card-top">
                        <span class="document-icon">
                            <i class="fas fa-file-alt"></i>
                        </span>

                        <span class="document-label">
                            <i class="fas fa-file-signature"></i>
                            Certificate
                        </span>
                    </div>

                    <h5>Leaving Certificate</h5>

                    <p>
                        Search a student and create an official
                        school leaving certificate.
                    </p>

                    <a href="{{ route('admin.school-leaving-certificate.index') }}"
                       class="document-button">
                        <span>Create Certificate</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

                {{-- CASTE REPORT --}}
                <article class="document-card blue">
                    <div class="document-card-top">
                        <span class="document-icon">
                            <i class="fas fa-chart-pie"></i>
                        </span>

                        <span class="document-label">
                            <i class="fas fa-chart-bar"></i>
                            Report
                        </span>
                    </div>

                    <h5>Caste Report</h5>

                    <p>
                        View class-wise caste, boys and girls
                        summary for active students.
                    </p>

                    <a href="{{ route('admin.caste-report.index') }}"
                       class="document-button">
                        <span>View Report</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

                {{-- AGE REPORT --}}
                <article class="document-card sage">
                    <div class="document-card-top">
                        <span class="document-icon">
                            <i class="far fa-calendar-alt"></i>
                        </span>

                        <span class="document-label">
                            <i class="fas fa-users"></i>
                            Report
                        </span>
                    </div>

                    <h5>Age Report</h5>

                    <p>
                        Generate class-wise student age report
                        using date of birth.
                    </p>

                    <a href="{{ route('admin.age-report.index') }}"
                       class="document-button">
                        <span>View Report</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

            </div>

            {{-- INFORMATION BAR --}}
            <div class="documents-info-bar">
                <span class="documents-info-icon">
                    <i class="fas fa-info-circle"></i>
                </span>

                <div>
                    <h6>Document Management</h6>
                    <p>
                        Use the available services above to search students,
                        generate official documents and prepare them for printing.
                    </p>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="documents-card-footer">
                <div>
                    Showing <strong>4</strong> document service(s)
                </div>
                <div class="footer-status">
                    <i class="fas fa-circle-check"></i>
                    All services available
                </div>
            </div>

        </section>
    </div>
</div>

<style>
/* ==========================================================
   DOCUMENT CENTER — SCHOOL DIARY DESIGN SYSTEM
   Premium editorial dashboard | Responsive | Accessible
========================================================== */

.documents-page {
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

.documents-page *,
.documents-page *::before,
.documents-page *::after {
    box-sizing: border-box;
}

.documents-page a,
.documents-page button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.documents-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.documents-hero,
.documents-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.documents-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.documents-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.documents-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.documents-hero-icon {
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

.documents-hero-text {
    min-width: 0;
}

.documents-breadcrumb {
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

.documents-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.documents-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.documents-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.documents-hero-note {
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

.documents-hero-note i {
    font-size: 13px;
}

.documents-hero-actions {
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

.documents-hero-actions::before,
.documents-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.documents-hero-actions::before {
    top: -115px;
    right: -60px;
}

.documents-hero-actions::after {
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

.documents-hero-actions p {
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

.documents-hero-actions .diary-primary-btn {
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

.documents-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* KPI CARDS */

.documents-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.documents-kpi,
.document-card {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    background: var(--paper);
}

.documents-kpi.blue,
.document-card.blue { --kpi-color: #426b8a; }
.documents-kpi.green,
.document-card.green { --kpi-color: #43835d; }
.documents-kpi.orange,
.document-card.orange { --kpi-color: #a87543; }
.documents-kpi.red,
.document-card.red { --kpi-color: #b95b50; }
.document-card.sage { --kpi-color: #527f70; }

.documents-kpi {
    gap: 15px;
    padding: 22px 21px 18px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.documents-kpi::before {
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

.documents-kpi.blue::before { content: "MODULES"; }
.documents-kpi.green::before { content: "CERTIFICATES"; }
.documents-kpi.orange::before { content: "REPORTS"; }
.documents-kpi.red::before { content: "PRINT"; }

.documents-kpi:hover {
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

.documents-kpi .kpi-icon {
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

.documents-paper-panel {
    overflow: hidden;
}

.documents-panel-header {
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

.panel-title-icon {
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

.documents-record-count {
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

.documents-record-count i,
.documents-record-count strong {
    color: var(--ink);
}

/* SERVICE CARDS */

.documents-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    padding: 36px 24px 28px;
    background: #faf9f3;
}

.document-card {
    gap: 0;
    padding: 22px 21px 21px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.document-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.document-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 17px;
}

.document-icon {
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

.document-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #e7dfc9;
    border-radius: 7px;
    color: #765e32;
    background: #faf5e8;
    font-size: 10px;
    font-weight: 700;
}

.document-label i {
    color: var(--amber);
}

.document-card h5 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.document-card p {
    min-height: 66px;
    margin: 0 0 19px;
    padding-bottom: 13px;
    border-bottom: 1px dashed #e1dccf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

.document-button {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    gap: 9px;
    width: 100%;
    min-height: 44px;
    margin-top: auto;
    padding: 0 15px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: #fffefa;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.document-button i {
    font-size: 12px;
}

.document-button:hover {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
    box-shadow: 0 4px 10px rgba(38, 59, 53, .12);
}

.document-button:hover i {
    transform: translateX(3px);
}

/* INFORMATION BAR */

.documents-info-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px 24px;
    border-top: 1px solid var(--line);
    background: #fffefa;
}

.documents-info-icon {
    display: flex;
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

.documents-info-bar h6 {
    margin: 0 0 4px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 15px;
    font-weight: 700;
}

.documents-info-bar p {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

/* FOOTER */

.documents-card-footer {
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

.documents-card-footer strong {
    color: var(--ink);
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

/* TABLET */

@media (max-width: 1200px) {
    .documents-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .documents-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .documents-hero {
        grid-template-columns: 1fr;
    }

    .documents-hero-actions {
        min-height: 220px;
    }

    .documents-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .documents-page .container-fluid {
        padding: 18px 14px !important;
    }

    .documents-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .documents-hero::before {
        left: 18px;
    }

    .documents-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .documents-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .documents-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .documents-hero-text h2 {
        font-size: 24px;
    }

    .documents-hero-text p {
        font-size: 11px;
    }

    .documents-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .documents-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .documents-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .documents-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .documents-kpi .kpi-icon {
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

    .documents-panel-header {
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

    .documents-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .documents-grid {
        grid-template-columns: 1fr;
        gap: 34px;
        padding: 34px 15px 22px;
    }

    .document-card p {
        min-height: 0;
    }

    .documents-info-bar {
        align-items: flex-start;
        padding: 16px 15px;
    }

    .documents-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }
}

@media (max-width: 400px) {
    .documents-kpis {
        grid-template-columns: 1fr;
    }

    .documents-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .documents-record-count {
        margin-left: 46px;
    }
}

/* ACCESSIBILITY */

.documents-page a:focus-visible,
.documents-page button:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .documents-page *,
    .documents-page *::before,
    .documents-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection