@extends('layouts.app')

@section('title', 'Class & Subject Reports | Admin')
@section('page-title', 'Class & Subject Reports')

@section('content')
<div class="class-subject-simple">
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
                    <div class="print-brand-name">Class &amp; Subject Reports</div>
                    <div class="print-brand-sub">Official Report Directory</div>
                </div>
            </div>
            <div class="print-meta">
                <div class="print-meta-row">
                    <span class="print-label">Report:</span>
                    <span class="print-value">Class &amp; Subject Reports</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="csr-print-date">—</span>
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
                    <i class="fas fa-school"></i>
                </div>
                <div class="hero-text">
                    <div class="hero-breadcrumb"></div>
                    <h2>Class &amp; Subject Reports</h2>
                    <p>Explore your school's class structure and subject allocation through clear, organized academic reports.</p>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-handwritten">Organize. Teach. Grow.</div>
                <p>Review classes, sections, subjects and their assignments in one place.</p>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printClassSubjectReports()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </section>

        {{-- REPORT LINKS --}}
        <section class="report-section">
            <div class="section-heading">
                <div>
                    <h3>Choose a Report</h3>
                    <p>Select a report to view the existing academic records.</p>
                </div>
                <span class="report-count">2 Reports</span>
            </div>

            <div class="report-grid">

                <a href="{{ route('admin.class-reports.classes') }}"
                   class="report-card accent-green"
                   aria-label="Open Class Report">
                    <div class="report-card-top">
                        <span class="report-icon icon-green">
                            <i class="fas fa-chalkboard"></i>
                        </span>
                        <span class="report-tag tag-green">Classes</span>
                    </div>

                    <div class="report-card-body">
                        <strong>Class Report</strong>
                        <span>
                            View existing classes, sections, academic years, status,
                            and the number of subjects associated with each class.
                        </span>
                    </div>

                    <div class="report-chips">
                        <span>Sections</span>
                        <span>Academic years</span>
                        <span>Status</span>
                    </div>

                    <div class="report-card-footer">
                        <span class="report-open">View Class Report</span>
                        <span class="report-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.class-reports.subjects') }}"
                   class="report-card accent-blue"
                   aria-label="Open Subject Report">
                    <div class="report-card-top">
                        <span class="report-icon icon-blue">
                            <i class="fas fa-book-open"></i>
                        </span>
                        <span class="report-tag tag-blue">Subjects</span>
                    </div>

                    <div class="report-card-body">
                        <strong>Subject Report</strong>
                        <span>
                            View existing subjects with their subject codes, assigned
                            classes, and status, with options to search and filter.
                        </span>
                    </div>

                    <div class="report-chips">
                        <span>Subject codes</span>
                        <span>Assigned classes</span>
                        <span>Search &amp; filter</span>
                    </div>

                    <div class="report-card-footer">
                        <span class="report-open">View Subject Report</span>
                        <span class="report-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </a>

            </div>
        </section>

        {{-- NOTE --}}
        <div class="report-note">
            <span class="report-note-mark"><i class="fas fa-info"></i></span>
            <div>
                <strong>About these reports</strong><br>
                These reports use your existing class and subject records.
                They do not create or modify academic data.
            </div>
        </div>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            This is a system-generated class and subject report directory. For official use only.
        </div>

    </div>
</div>

<style>
.class-subject-simple {
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

.class-subject-simple *,
.class-subject-simple *::before,
.class-subject-simple *::after {
    box-sizing: border-box;
}

.class-subject-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.class-subject-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.class-subject-simple .btn-back {
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

.class-subject-simple .btn-back:hover {
    background: #f0eee8;
    color: var(--text);
}

.class-subject-simple .btn-primary,
.class-subject-simple .btn-secondary {
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

.class-subject-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.class-subject-simple .btn-primary:hover {
    background: var(--primary-hover);
}

.class-subject-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.class-subject-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HERO SECTION (Cream / Pearl) ========== */
.class-subject-simple .hero-section {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
}

.class-subject-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 28px 28px 32px;
    background: #fdfbf7;
}

.class-subject-simple .hero-icon {
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

.class-subject-simple .hero-text .hero-breadcrumb {
    font-size: 11px;
    font-weight: 700;
    color: #9c7b4a;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.class-subject-simple .hero-text h2 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.class-subject-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 480px;
}

/* Cream / Pearl Right Side */
.class-subject-simple .hero-right {
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

.class-subject-simple .hero-handwritten {
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 22px;
    color: #5c4a32;
    line-height: 1.3;
}

.class-subject-simple .hero-right p {
    margin: 0;
    font-size: 13px;
    color: #6b5c45;
    max-width: 260px;
    line-height: 1.6;
}

.class-subject-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 6px;
}

.class-subject-simple .hero-right .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.class-subject-simple .hero-right .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

.class-subject-simple .hero-right .btn-secondary {
    background: rgba(255, 252, 245, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}

.class-subject-simple .hero-right .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}

/* Report section */
.class-subject-simple .report-section {
    margin-bottom: 20px;
}

.class-subject-simple .section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 14px;
}

.class-subject-simple .section-heading h3 {
    margin: 0 0 4px;
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
}

.class-subject-simple .section-heading p {
    margin: 0;
    font-size: 12.5px;
    color: var(--muted);
    line-height: 1.5;
}

.class-subject-simple .report-count {
    padding: 4px 10px;
    border: 1px solid #e5d9c0;
    border-radius: 20px;
    background: #f3ead8;
    color: #5c4a32;
    font-size: 12px;
    font-weight: 600;
}

/* Report cards */
.class-subject-simple .report-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.class-subject-simple .report-card {
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

.class-subject-simple .report-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent);
}

.class-subject-simple .report-card.accent-green {
    --accent: var(--net);
}

.class-subject-simple .report-card.accent-blue {
    --accent: var(--blue);
}

.class-subject-simple .report-card:hover {
    border-color: var(--accent);
    box-shadow: 0 10px 24px rgba(44, 62, 54, 0.10);
    transform: translateY(-2px);
    color: var(--text);
}

.class-subject-simple .report-card:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}

.class-subject-simple .report-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.class-subject-simple .report-icon {
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

.class-subject-simple .report-icon.icon-green {
    background: #e4f3ea;
    border-color: #bfe0cb;
    color: var(--net);
}

.class-subject-simple .report-icon.icon-blue {
    background: #e6eef4;
    border-color: #c5d6e3;
    color: var(--blue);
}

.class-subject-simple .report-tag {
    padding: 4px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.class-subject-simple .report-tag.tag-green {
    background: #e4f3ea;
    color: #1a6b3c;
}

.class-subject-simple .report-tag.tag-blue {
    background: #e6eef4;
    color: #34556f;
}

.class-subject-simple .report-card-body {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.class-subject-simple .report-card-body strong {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
    color: var(--text);
}

.class-subject-simple .report-card-body > span {
    font-size: 13px;
    line-height: 1.65;
    color: var(--muted);
}

.class-subject-simple .report-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.class-subject-simple .report-chips span {
    padding: 4px 10px;
    border: 1px solid #e5d9c0;
    border-radius: 20px;
    background: #fdfbf7;
    color: #5c4a32;
    font-size: 11.5px;
    font-weight: 600;
}

.class-subject-simple .report-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px solid #eee9dd;
}

.class-subject-simple .report-open {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent);
}

.class-subject-simple .report-arrow {
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

.class-subject-simple .report-card:hover .report-arrow {
    background: var(--accent);
    color: #fff;
    transform: translateX(3px);
}

/* Note */
.class-subject-simple .report-note {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    padding: 14px 16px;
    background: #fdfbf7;
    border: 1px solid #e5d9c0;
    border-radius: 8px;
    color: var(--muted);
    font-size: 12.5px;
    line-height: 1.6;
}

.class-subject-simple .report-note strong {
    color: var(--text);
}

.class-subject-simple .report-note-mark {
    width: 24px;
    height: 24px;
    min-width: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3ead8;
    color: #5c4a32;
    font-size: 11px;
}

@media (prefers-reduced-motion: reduce) {
    .class-subject-simple .report-card,
    .class-subject-simple .report-arrow {
        transition: none;
    }
    .class-subject-simple .report-card:hover,
    .class-subject-simple .report-card:hover .report-arrow {
        transform: none;
    }
}

/* Print only (hidden on screen) */
.class-subject-simple .print-only,
.class-subject-simple .print-footer {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .class-subject-simple .hero-section {
        grid-template-columns: 1fr;
    }
    .class-subject-simple .hero-right {
        border-left: none;
        border-top: 1px solid #e5d9c4;
    }
}

@media (max-width: 768px) {
    .class-subject-simple .hero-left {
        padding: 22px 20px;
    }
    .class-subject-simple .hero-text h2 {
        font-size: 20px;
    }
    .class-subject-simple .report-grid {
        grid-template-columns: 1fr;
    }
    .class-subject-simple .report-card {
        padding: 20px 18px 16px;
    }
    .class-subject-simple .report-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        font-size: 18px;
    }
    .class-subject-simple .report-card-body strong {
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

    .class-subject-simple,
    .class-subject-simple * {
        visibility: visible;
    }

    .class-subject-simple {
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
    .class-subject-simple .top-bar,
    .class-subject-simple .hero-section,
    .class-subject-simple .section-heading,
    .class-subject-simple .report-note {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .class-subject-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .class-subject-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .class-subject-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .class-subject-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .class-subject-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .class-subject-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .class-subject-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
        overflow-wrap: anywhere;
    }

    .class-subject-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .class-subject-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .class-subject-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .class-subject-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .class-subject-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .class-subject-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .class-subject-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .class-subject-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Report cards for print ---- */
    .class-subject-simple .report-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .class-subject-simple .report-card {
        padding: 16px 16px 14px;
        border: 1px solid #999;
        border-radius: 0;
        box-shadow: none !important;
        transform: none !important;
        break-inside: avoid;
    }

    .class-subject-simple .report-card::before {
        height: 3px;
        background: var(--accent) !important;
    }

    .class-subject-simple .report-card-body strong {
        color: #1a1a1a !important;
        font-size: 13px;
    }

    .class-subject-simple .report-card-body > span {
        color: #555 !important;
        font-size: 10px;
    }

    .class-subject-simple .report-chips span {
        font-size: 9px;
    }

    .class-subject-simple .report-card-footer {
        display: none !important;
    }

    /* Subtle footer note */
    .class-subject-simple .print-footer {
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
    function setClassSubjectPrintDate() {
        const el = document.getElementById('csr-print-date');

        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printClassSubjectReports = function () {
        setClassSubjectPrintDate();

        const logo = document.querySelector('.class-subject-simple .print-school-logo');

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