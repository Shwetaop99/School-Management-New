@extends('layouts.app')

@section('title', 'Meal Reports | Admin')
@section('page-title', 'Meal Reports')

@section('content')

<div class="meal-reports-page">
    <div class="container-fluid py-4">

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Back to Reports
            </a>
        </div>

        {{-- HERO SECTION — MATCHING PAYROLL DESIGN --}}
        <section class="hero-section">

            <div class="hero-left">
                <div class="hero-icon">
                    <i class="bi bi-egg-fried"></i>
                </div>

                <div class="hero-text">
                    <div class="hero-breadcrumb">REPORTS / MEAL MANAGEMENT</div>

                    <h2>Meal Reports</h2>

                    <p>
                        A unified view of incoming stock, outgoing inventory
                        and meal stock-history records.
                    </p>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-handwritten">Track. Review. Report.</div>

                <p>
                    Choose a report to review records, apply filters,
                    print statements or download PDFs.
                </p>

                <div class="hero-buttons">
                    <a
                        href="{{ route('admin.reports.meal', ['type' => 'stock_in']) }}"
                        class="btn-primary"
                    >
                        <i class="bi bi-box-arrow-in-down"></i>
                        Stock In
                    </a>

                    <a
                        href="{{ route('admin.reports.meal', ['type' => 'stock_out']) }}"
                        class="btn-secondary"
                    >
                        <i class="bi bi-box-arrow-up"></i>
                        Stock Out
                    </a>
                </div>
            </div>

        </section>

        {{-- REPORT OPTIONS --}}
        <div class="reports-panel">

            <div class="section-header">
                <div>
                    <h3 class="section-heading">Choose a Report</h3>

                    <p class="section-description">
                        Select a report to view its records, apply filters,
                        print the report or download it as a PDF.
                    </p>
                </div>

                <span class="section-tag">
                    <i class="bi bi-grid-1x2"></i>
                    3 Reports
                </span>
            </div>

            <div class="report-options">

                {{-- STOCK IN --}}
                <a
                    href="{{ route('admin.reports.meal', ['type' => 'stock_in']) }}"
                    class="report-option"
                >
                    <div class="option-top">
                        <div class="option-icon stock-in-icon">
                            <i class="bi bi-box-arrow-in-down"></i>
                        </div>

                        <span class="option-number">01</span>
                    </div>

                    <div class="option-content">
                        <h3>Meal Stock In</h3>

                        <p>
                            View incoming meal inventory, quantities received,
                            suppliers, rates and transaction amounts.
                        </p>
                    </div>

                    <div class="option-footer">
                        <span class="option-link">
                            View Stock In Report
                        </span>

                        <span class="option-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </a>

                {{-- STOCK OUT --}}
                <a
                    href="{{ route('admin.reports.meal', ['type' => 'stock_out']) }}"
                    class="report-option"
                >
                    <div class="option-top">
                        <div class="option-icon stock-out-icon">
                            <i class="bi bi-box-arrow-up"></i>
                        </div>

                        <span class="option-number">02</span>
                    </div>

                    <div class="option-content">
                        <h3>Meal Stock Out</h3>

                        <p>
                            View outgoing meal inventory, quantities issued,
                            transaction dates and recorded reasons.
                        </p>
                    </div>

                    <div class="option-footer">
                        <span class="option-link">
                            View Stock Out Report
                        </span>

                        <span class="option-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </a>

                {{-- MEAL LOGS --}}
                <a
                    href="{{ route('admin.reports.meal', ['type' => 'logs']) }}"
                    class="report-option"
                >
                    <div class="option-top">
                        <div class="option-icon logs-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <span class="option-number">03</span>
                    </div>

                    <div class="option-content">
                        <h3>Meal Logs</h3>

                        <p>
                            Review stock-history entries, previous and updated
                            stock, recorded actions and remarks.
                        </p>
                    </div>

                    <div class="option-footer">
                        <span class="option-link">
                            View Meal Logs
                        </span>

                        <span class="option-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </a>

            </div>

            {{-- INFORMATION NOTE --}}
            <div class="report-note">
                <div class="note-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div class="note-content">
                    <strong>Important Note</strong>

                    <p>
                        Meal Logs displays recorded stock-history entries.
                        It does not represent meals served or consumed unless
                        those records are maintained separately in your system.
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
/* =========================================================
   MEAL REPORTS
   Scoped design matching the locked Payroll Report UI
========================================================= */

.meal-reports-page {
    --bg: #f5f4f0;
    --text: #1e2a25;
    --muted: #5c6b64;
    --border: #d4cfc2;
    --primary: #2c3e36;
    --primary-hover: #3a4f45;
    --cream: #f3ead8;
    --cream-border: #e5d9c0;
    --net: #1a6b3c;

    width: 100%;
    min-height: calc(100vh - 70px);
    background: var(--bg);
    color: var(--text);
    font-family: Inter, "Public Sans", Arial, sans-serif;
}

.meal-reports-page *,
.meal-reports-page *::before,
.meal-reports-page *::after {
    box-sizing: border-box;
}

.meal-reports-page .container-fluid {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding-left: 24px;
    padding-right: 24px;
}

/* Top bar */

.meal-reports-page .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.meal-reports-page .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #fff;
    color: var(--text);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background .2s ease, border-color .2s ease;
}

.meal-reports-page .btn-back:hover {
    background: #f0eee8;
    border-color: #bdb6a7;
    color: var(--text);
}

/* Shared buttons */

.meal-reports-page .btn-primary,
.meal-reports-page .btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 9px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid transparent;
    transition: background .2s ease, border-color .2s ease;
}

.meal-reports-page .btn-primary {
    background: #3a3428;
    color: #fff;
    border-color: #3a3428;
}

.meal-reports-page .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
    color: #fff;
}

.meal-reports-page .btn-secondary {
    background: rgba(255, 252, 245, .7);
    border-color: #c4b49a;
    color: #5c4a32;
}

.meal-reports-page .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}

/* Hero section */

.meal-reports-page .hero-section {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
}

.meal-reports-page .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 28px 28px 32px;
    background: #fdfbf7;
}

.meal-reports-page .hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    border-radius: 12px;
    background: var(--cream);
    border: 1px solid var(--cream-border);
    color: #5c4a32;
    font-size: 22px;
}

.meal-reports-page .hero-text {
    min-width: 0;
}

.meal-reports-page .hero-breadcrumb {
    margin-bottom: 6px;
    color: #9c7b4a;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
}

.meal-reports-page .hero-text h2 {
    margin: 0 0 8px;
    color: var(--text);
    font-size: 24px;
    font-weight: 700;
    line-height: 1.3;
}

.meal-reports-page .hero-text p {
    max-width: 480px;
    margin: 0;
    color: var(--muted);
    font-size: 13.5px;
    line-height: 1.6;
}

/* Cream / pearl right side */

.meal-reports-page .hero-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 28px 24px;
    text-align: center;
    background: linear-gradient(
        145deg,
        #f8f1e3 0%,
        #f0e6d2 50%,
        #e8dcc6 100%
    );
    border-left: 1px solid #e5d9c4;
}

.meal-reports-page .hero-handwritten {
    color: #5c4a32;
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 22px;
    line-height: 1.3;
}

.meal-reports-page .hero-right p {
    max-width: 280px;
    margin: 0;
    color: #6b5c45;
    font-size: 13px;
    line-height: 1.6;
}

.meal-reports-page .hero-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 6px;
}

/* Reports panel */

.meal-reports-page .reports-panel {
    padding: 25px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fff;
}

.meal-reports-page .section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 22px;
}

.meal-reports-page .section-heading {
    margin: 0 0 7px;
    color: var(--text);
    font-size: 20px;
    font-weight: 700;
    line-height: 1.4;
}

.meal-reports-page .section-description {
    max-width: 700px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.7;
}

.meal-reports-page .section-tag {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    flex-shrink: 0;
    padding: 7px 11px;
    border: 1px solid var(--cream-border);
    border-radius: 6px;
    background: #fdfbf7;
    color: #6b5c45;
    font-size: 12px;
    font-weight: 600;
}

/* Report cards */

.meal-reports-page .report-options {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.meal-reports-page .report-option {
    display: flex;
    flex-direction: column;
    min-width: 0;
    min-height: 255px;
    padding: 22px;
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

.meal-reports-page .report-option:hover {
    transform: translateY(-3px);
    border-color: #b8aa8e;
    background: #fffefa;
    box-shadow: 0 9px 22px rgba(44, 62, 54, .08);
    color: var(--text);
}

.meal-reports-page .report-option:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 3px;
}

.meal-reports-page .option-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
}

.meal-reports-page .option-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    border: 1px solid var(--cream-border);
    border-radius: 10px;
    background: var(--cream);
    color: #5c4a32;
    font-size: 23px;
}

.meal-reports-page .stock-in-icon {
    background: #e8f0e8;
    border-color: #d2e0d1;
    color: #37664a;
}

.meal-reports-page .stock-out-icon {
    background: #f5e9e3;
    border-color: #e8d4c9;
    color: #9a5140;
}

.meal-reports-page .logs-icon {
    background: #e9ece6;
    border-color: #d8ded2;
    color: #536b55;
}

.meal-reports-page .option-number {
    color: #a49a87;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
}

.meal-reports-page .option-content {
    flex: 1;
}

.meal-reports-page .report-option h3 {
    margin: 0 0 9px;
    color: var(--text);
    font-size: 17px;
    font-weight: 700;
    line-height: 1.4;
}

.meal-reports-page .report-option p {
    margin: 0 0 20px;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.meal-reports-page .option-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-top: 15px;
    border-top: 1px solid #eee9dd;
}

.meal-reports-page .option-link {
    color: var(--primary);
    font-size: 12px;
    font-weight: 700;
    line-height: 1.5;
}

.meal-reports-page .option-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #fdfbf7;
    color: var(--primary);
    transition:
        background .2s ease,
        color .2s ease,
        border-color .2s ease;
}

.meal-reports-page .report-option:hover .option-arrow {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}

/* Information note */

.meal-reports-page .report-note {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-top: 22px;
    padding: 16px 18px;
    border: 1px solid #e5d9c4;
    border-radius: 8px;
    background: #fdfbf7;
    color: var(--muted);
}

.meal-reports-page .note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border: 1px solid var(--cream-border);
    border-radius: 7px;
    background: var(--cream);
    color: #806744;
    font-size: 15px;
}

.meal-reports-page .note-content {
    min-width: 0;
}

.meal-reports-page .note-content strong {
    display: block;
    margin-bottom: 4px;
    color: var(--text);
    font-size: 12px;
    font-weight: 700;
}

.meal-reports-page .note-content p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.7;
}

/* Responsive layout */

@media (max-width: 1000px) {
    .meal-reports-page .report-options {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .meal-reports-page .hero-section {
        grid-template-columns: 1fr;
    }

    .meal-reports-page .hero-right {
        border-left: none;
        border-top: 1px solid #e5d9c4;
    }
}

@media (max-width: 768px) {
    .meal-reports-page .container-fluid {
        padding-left: 16px;
        padding-right: 16px;
    }

    .meal-reports-page .hero-left {
        padding: 22px 20px;
    }

    .meal-reports-page .hero-text h2 {
        font-size: 20px;
    }

    .meal-reports-page .hero-right {
        padding: 24px 18px;
    }

    .meal-reports-page .reports-panel {
        padding: 18px;
    }

    .meal-reports-page .section-heading {
        font-size: 18px;
    }

    .meal-reports-page .section-header {
        flex-wrap: wrap;
    }

    .meal-reports-page .report-options {
        gap: 14px;
    }

    .meal-reports-page .report-option {
        padding: 18px;
        min-height: 245px;
    }
}

@media (max-width: 560px) {
    .meal-reports-page .container-fluid {
        padding-left: 12px;
        padding-right: 12px;
    }

    .meal-reports-page .hero-left {
        align-items: flex-start;
        gap: 13px;
        padding: 20px 16px;
    }

    .meal-reports-page .hero-icon {
        width: 45px;
        height: 45px;
        border-radius: 9px;
        font-size: 19px;
    }

    .meal-reports-page .hero-breadcrumb {
        font-size: 9px;
        letter-spacing: .6px;
    }

    .meal-reports-page .hero-text h2 {
        font-size: 19px;
    }

    .meal-reports-page .hero-text p {
        font-size: 12.5px;
    }

    .meal-reports-page .hero-handwritten {
        font-size: 20px;
    }

    .meal-reports-page .hero-buttons {
        width: 100%;
    }

    .meal-reports-page .hero-buttons a {
        flex: 1;
    }

    .meal-reports-page .reports-panel {
        padding: 16px;
    }

    .meal-reports-page .report-options {
        grid-template-columns: 1fr;
    }

    .meal-reports-page .report-option {
        min-height: 0;
    }

    .meal-reports-page .option-content {
        margin-bottom: 4px;
    }

    .meal-reports-page .section-tag {
        font-size: 11px;
    }

    .meal-reports-page .report-note {
        padding: 13px;
        gap: 10px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .meal-reports-page *,
    .meal-reports-page *::before,
    .meal-reports-page *::after {
        transition: none !important;
    }
}
</style>

@endsection
