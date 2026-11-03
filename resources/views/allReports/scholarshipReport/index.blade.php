@extends('layouts.app')

@section('title', 'Scholarship Reports | Admin')
@section('page-title', 'Scholarship Reports')

@section('content')
<div class="scholarship-simple">
    <div class="container-fluid py-4">

        @php
            // Fetch school identity for the browser print view.
            $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::query()->first();

            $schoolName = trim((string) ($schoolSetting->school_name ?? ''));
            $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';

            $schoolLogo = $schoolSetting->logo_url
                ?? $schoolSetting->logo
                ?? $schoolSetting->school_logo
                ?? null;

            if ($schoolLogo && !filter_var($schoolLogo, FILTER_VALIDATE_URL)
                && !str_starts_with($schoolLogo, 'data:')
                && !str_starts_with($schoolLogo, '/')) {
                $schoolLogo = \Illuminate\Support\Facades\Storage::url($schoolLogo);
            }

            $schoolAddress = collect([
                $schoolSetting->address ?? null,
                $schoolSetting->city ?? null,
                $schoolSetting->district ?? null,
                $schoolSetting->state ?? null,
                $schoolSetting->pincode ?? null,
            ])->filter(fn ($value) => trim((string) $value) !== '')->implode(', ');

            $schoolPhone = trim((string) ($schoolSetting->phone ?? ''));
            $schoolEmail = trim((string) ($schoolSetting->email ?? ''));
        @endphp

        {{-- PRINT ONLY SCHOOL BRANDING AND REPORT HEADER --}}
        <div class="print-only">
            <div class="print-header">
                <div class="print-brand">
                    @if (!empty($schoolLogo))
                        <img class="print-school-logo" src="{{ $schoolLogo }}" alt="School Logo">
                    @endif
                    <div class="print-school-identity">
                        <div class="print-school-name">{{ $schoolName }}</div>
                        @if ($schoolAddress !== '')
                            <div class="print-school-contact">{{ $schoolAddress }}</div>
                        @endif
                        @if ($schoolPhone !== '' || $schoolEmail !== '')
                            <div class="print-school-contact">
                                @if ($schoolPhone !== '') Phone: {{ $schoolPhone }} @endif
                                @if ($schoolPhone !== '' && $schoolEmail !== '') &nbsp; | &nbsp; @endif
                                @if ($schoolEmail !== '') Email: {{ $schoolEmail }} @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="print-report-title">
                    <div class="print-brand-name">Scholarship Reports</div>
                    <div class="print-brand-sub">Official Report Directory</div>
                </div>
            </div>
            <div class="print-meta">
                <div class="print-meta-row">
                    <span class="print-label">Report:</span>
                    <span class="print-value">Scholarship Reports</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="sr-print-date">—</span>
                </div>
            </div>
            <div class="print-divider"></div>
        </div>

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <div>
            </div>
        </div>

        {{-- HERO SECTION (Cream / Pearl) --}}
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="hero-text">
                    <div class="hero-breadcrumb"></div>
                    <h2>Scholarship Reports</h2>
                    <p>Access merit concession and scholarship examination reports from one central reporting page.</p>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-handwritten">Recognize. Reward. Achieve.</div>
                <p>Review academic performance, eligibility, examinations, and scholarship awards.</p>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printScholarshipReports()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </section>

        {{-- REPORT LINKS --}}
        <section class="report-section">
            <div class="section-heading">
                <div>
                    <h3>Available Reports</h3>
                    <p>Select a report to open its detailed records.</p>
                </div>
                <span class="report-count">2 Reports</span>
            </div>

            <div class="report-grid">

                <a href="{{ route('admin.reports.scholarship.merit-concession') }}" class="report-card accent-green">
                    <div class="report-card-top">
                        <span class="report-icon icon-green">
                            <i class="fas fa-award"></i>
                        </span>
                        <span class="report-tag tag-green">Academic</span>
                    </div>

                    <div class="report-card-body">
                        <strong>Merit Concession Report</strong>
                        <span>
                            Review student academic performance, examination
                            percentages, eligibility, and merit concessions.
                        </span>
                    </div>

                    <div class="report-chips">
                        <span>Performance</span>
                        <span>Eligibility</span>
                        <span>Concessions</span>
                    </div>

                    <div class="report-card-footer">
                        <span class="report-open">Open report</span>
                        <span class="report-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.reports.scholarship.exams') }}" class="report-card accent-blue">
                    <div class="report-card-top">
                        <span class="report-icon icon-blue">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <span class="report-tag tag-blue">Examination</span>
                    </div>

                    <div class="report-card-body">
                        <strong>Scholarship Exam Report</strong>
                        <span>
                            Review examination participation, results,
                            pass percentages, awards, and scholarship amounts.
                        </span>
                    </div>

                    <div class="report-chips">
                        <span>Participation</span>
                        <span>Results</span>
                        <span>Awards</span>
                    </div>

                    <div class="report-card-footer">
                        <span class="report-open">Open report</span>
                        <span class="report-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </a>

            </div>
        </section>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            This is a system-generated scholarship report directory. For official use only.
        </div>

    </div>
</div>

<style>
.scholarship-simple {
    --bg: #f5f4f0;
    --text: #1e2a25;
    --muted: #5c6b64;
    --border: #d4cfc2;
    --primary: #2c3e36;
    --primary-hover: #3a4f45;
    --net: #d4cfc2;
    --blue: #d4cfc2;
    background: var(--bg);
    color: var(--text);
    min-height: calc(100vh - 70px);
    font-family: Inter, "Public Sans", Arial, sans-serif;
}

.scholarship-simple *,
.scholarship-simple *::before,
.scholarship-simple *::after {
    box-sizing: border-box;
}

.scholarship-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.scholarship-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.scholarship-simple .btn-back {
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
}

.scholarship-simple .btn-back:hover {
    background: #f0eee8;
    color: var(--text);
}

.scholarship-simple .btn-primary,
.scholarship-simple .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid transparent;
}

.scholarship-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.scholarship-simple .btn-primary:hover {
    background: var(--primary-hover);
}

.scholarship-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.scholarship-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HERO SECTION (Cream / Pearl) ========== */
.scholarship-simple .hero-section {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
}

.scholarship-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 28px 28px 32px;
    background: #fdfbf7;
}

.scholarship-simple .hero-icon {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    background: #f3ead8;
    border: 1px solid #e5d9c0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5c4a32;
    font-size: 22px;
    flex-shrink: 0;
}

.scholarship-simple .hero-text .hero-breadcrumb {
    font-size: 11px;
    font-weight: 700;
    color: #9c7b4a;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.scholarship-simple .hero-text h2 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.scholarship-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 480px;
}

/* Cream / Pearl Right Side */
.scholarship-simple .hero-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 28px 24px;
    text-align: center;
    background: linear-gradient(145deg, #f8f1e3 0%, #f0e6d2 50%, #e8dcc6 100%);
    border-left: 1px solid #e5d9c4;
}

.scholarship-simple .hero-handwritten {
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 22px;
    color: #5c4a32;
    line-height: 1.3;
}

.scholarship-simple .hero-right p {
    margin: 0;
    font-size: 13px;
    color: #6b5c45;
    max-width: 260px;
    line-height: 1.6;
}

.scholarship-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 6px;
}

.scholarship-simple .hero-right .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.scholarship-simple .hero-right .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

.scholarship-simple .hero-right .btn-secondary {
    background: rgba(255, 252, 245, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}

.scholarship-simple .hero-right .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}

/* Report section */
.scholarship-simple .report-section {
    margin-bottom: 20px;
}

.scholarship-simple .section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 14px;
}

.scholarship-simple .section-heading h3 {
    margin: 0 0 4px;
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
}

.scholarship-simple .section-heading p {
    margin: 0;
    font-size: 12.5px;
    color: var(--muted);
    line-height: 1.5;
}

.scholarship-simple .report-count {
    padding: 4px 10px;
    border: 1px solid #e5d9c0;
    border-radius: 20px;
    background: #f3ead8;
    color: #5c4a32;
    font-size: 12px;
    font-weight: 600;
}

/* Report cards */
.scholarship-simple .report-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.scholarship-simple .report-card {
    --accent: var(--primary);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-width: 0;
    padding: 24px 24px 20px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    color: var(--text);
    text-decoration: none;
    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.scholarship-simple .report-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent);
}

.scholarship-simple .report-card.accent-green {
    --accent: var(--net);
}

.scholarship-simple .report-card.accent-blue {
    --accent: var(--blue);
}

.scholarship-simple .report-card:hover {
    border-color: var(--accent);
    box-shadow: 0 10px 24px rgba(44, 62, 54, 0.10);
    transform: translateY(-2px);
    color: var(--text);
}

.scholarship-simple .report-card:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}

.scholarship-simple .report-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.scholarship-simple .report-icon {
    width: 54px;
    height: 54px;
    min-width: 54px;
    border-radius: 12px;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.scholarship-simple .report-icon.icon-green {
    background: #e4f3ea;
    border-color: #bfe0cb;
    color: var(--net);
}

.scholarship-simple .report-icon.icon-blue {
    background: #e6eef4;
    border-color: #c5d6e3;
    color: var(--blue);
}

.scholarship-simple .report-tag {
    padding: 4px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.scholarship-simple .report-tag.tag-green {
    background: #e4f3ea;
    color: #1a6b3c;
}

.scholarship-simple .report-tag.tag-blue {
    background: #e6eef4;
    color: #34556f;
}

.scholarship-simple .report-card-body {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.scholarship-simple .report-card-body strong {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
    color: var(--text);
}

.scholarship-simple .report-card-body > span {
    font-size: 13px;
    line-height: 1.65;
    color: var(--muted);
}

.scholarship-simple .report-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.scholarship-simple .report-chips span {
    padding: 4px 10px;
    border: 1px solid #e5d9c0;
    border-radius: 20px;
    background: #fdfbf7;
    color: #5c4a32;
    font-size: 11.5px;
    font-weight: 600;
}

.scholarship-simple .report-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px solid #eee9dd;
}

.scholarship-simple .report-open {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent);
}

.scholarship-simple .report-arrow {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0eee8;
    color: var(--accent);
    font-size: 13px;
    transition: background .2s ease, color .2s ease, transform .2s ease;
}

.scholarship-simple .report-card:hover .report-arrow {
    background: var(--accent);
    color: #fff;
    transform: translateX(3px);
}

@media (prefers-reduced-motion: reduce) {
    .scholarship-simple .report-card,
    .scholarship-simple .report-arrow {
        transition: none;
    }
    .scholarship-simple .report-card:hover,
    .scholarship-simple .report-card:hover .report-arrow {
        transform: none;
    }
}

/* Print only (hidden on screen) */
.scholarship-simple .print-only,
.scholarship-simple .print-footer {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .scholarship-simple .hero-section {
        grid-template-columns: 1fr;
    }
    .scholarship-simple .hero-right {
        border-left: none;
        border-top: 1px solid #e5d9c4;
    }
}

@media (max-width: 768px) {
    .scholarship-simple .hero-left {
        padding: 22px 20px;
    }
    .scholarship-simple .hero-text h2 {
        font-size: 20px;
    }
    .scholarship-simple .report-grid {
        grid-template-columns: 1fr;
    }
    .scholarship-simple .report-card {
        padding: 20px 18px 16px;
    }
    .scholarship-simple .report-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        font-size: 18px;
    }
    .scholarship-simple .report-card-body strong {
        font-size: 16px;
    }
}

/* ================= PRINT — Professional Layout ================= */
@media print {
    @page {
        size: A4 landscape;
        margin: 10mm 12mm 12mm 12mm;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    body * {
        visibility: hidden;
    }

    .scholarship-simple,
    .scholarship-simple * {
        visibility: visible;
    }

    .scholarship-simple {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: #fff !important;
        color: #1a1a1a;
        font-family: "Inter", "Segoe UI", Arial, Helvetica, sans-serif;
        font-size: 10.5px;
        line-height: 1.35;
    }

    /* Hide non-print elements */
    .scholarship-simple .top-bar,
    .scholarship-simple .hero-section,
    .scholarship-simple .section-heading {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .scholarship-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .scholarship-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .scholarship-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .scholarship-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .scholarship-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .scholarship-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .scholarship-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
        overflow-wrap: anywhere;
    }

    .scholarship-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .scholarship-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .scholarship-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .scholarship-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .scholarship-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .scholarship-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .scholarship-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .scholarship-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Report cards for print ---- */
    .scholarship-simple .report-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .scholarship-simple .report-card {
        padding: 16px 16px 14px;
        border: 1px solid #999;
        border-radius: 0;
        box-shadow: none !important;
        transform: none !important;
        break-inside: avoid;
    }

    .scholarship-simple .report-card::before {
        height: 3px;
        background: var(--accent) !important;
    }

    .scholarship-simple .report-icon,
    .scholarship-simple .report-tag {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .scholarship-simple .report-card-body strong {
        color: #1a1a1a !important;
        font-size: 13px;
    }

    .scholarship-simple .report-card-body > span {
        color: #555 !important;
        font-size: 10px;
    }

    .scholarship-simple .report-chips span {
        font-size: 9px;
    }

    .scholarship-simple .report-card-footer {
        display: none !important;
    }

    /* Subtle footer note */
    .scholarship-simple .print-footer {
        display: block !important;
        margin-top: 10px;
        font-size: 8.5px;
        color: #777;
        text-align: center;
        border-top: 1px solid #ddd;
        padding-top: 6px;
    }
}
</style>

<script>
(function () {
    function setScholarshipPrintDate() {
        const el = document.getElementById('sr-print-date');

        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printScholarshipReports = function () {
        setScholarshipPrintDate();

        const logo = document.querySelector('.scholarship-simple .print-school-logo');

        if (logo && !logo.complete) {
            logo.addEventListener('load', function () {
                window.print();
            }, { once: true });

            logo.addEventListener('error', function () {
                window.print();
            }, { once: true });

            // Avoid waiting indefinitely for a logo that fails to load.
            window.setTimeout(function () {
                if (!logo.complete) {
                    window.print();
                }
            }, 1500);

            return;
        }

        window.print();
    };
})();
</script>
@endsection