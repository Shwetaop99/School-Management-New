@extends('layouts.app')

@section('title', 'Result Dashboard')

@section('content')
<div class="result-page">
    <div class="container-fluid py-4 result-dashboard">

        {{-- PAGE HEADER --}}
        <header class="rd-header">
            <div class="rd-header-content">
                <div class="rd-header-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>

                <div class="rd-header-text">
                    <span class="rd-eyebrow">ACADEMIC MANAGEMENT</span>
                    <h1>Result Dashboard</h1>
                    <p>Manage examination results, track progress, and publish student results.</p>
                </div>
            </div>

            <div class="rd-header-actions">
                <a href="{{ route('admin.results.generate') }}" class="rd-btn rd-btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    <span>Enter Marks</span>
                </a>

                <button type="button" class="rd-btn rd-btn-light" id="refreshDashboard">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </header>

        {{-- NOTIFICATIONS --}}
        @if(session('success'))
            <div class="rd-alert rd-alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="rd-alert-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="rd-alert rd-alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="rd-alert-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        {{-- EXAM FILTER --}}
        <section class="rd-filter-card">
            <div class="rd-filter-heading">
                <div class="rd-filter-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>

                <div>
                    <h2>Examination Overview</h2>
                    <p>Select an examination to view its result status.</p>
                </div>
            </div>

            <div class="rd-filter-controls">
                <div class="rd-field">
                    <label for="examSelect">Select Examination</label>

                    <select id="examSelect" class="form-select">
                        <option value="">Choose an examination</option>

                        @foreach($exams as $exam)
                            <option
                                value="{{ $exam->id }}"
                                {{ isset($selectedExam) && $selectedExam && $selectedExam->id == $exam->id ? 'selected' : '' }}
                            >
                                {{ $exam->exam_name }}
                                @if(!empty($exam->academic_year))
                                    — {{ $exam->academic_year }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" id="loadResultsButton" class="rd-btn rd-btn-dark">
                    <i class="bi bi-search"></i>
                    View Results
                </button>
            </div>

            <div class="rd-filter-note">
                <i class="bi bi-info-circle"></i>
                Select an examination and load its results to view class-wise statistics.
            </div>
        </section>

        {{-- LOADING --}}
        <div id="dashboardLoading" class="rd-loading" hidden>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span>Loading examination results...</span>
        </div>

        {{-- ERROR --}}
        <div id="dashboardError" class="rd-alert rd-alert-danger" role="alert" hidden>
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div id="dashboardErrorMessage">Unable to load results. Please try again.</div>
            <button type="button" class="rd-btn rd-btn-light" id="retryDashboard">
                Retry
            </button>
        </div>

        {{-- DASHBOARD CONTENT --}}
        <div id="dashboardContent" hidden>

            {{-- EXAM INFORMATION --}}
            <section class="rd-exam-banner">
                <div class="rd-exam-banner-main">
                    <span class="rd-exam-icon">
                        <i class="bi bi-journal-check"></i>
                    </span>

                    <div>
                        <span class="rd-exam-label">CURRENT EXAMINATION</span>
                        <h2 id="selectedExamName">—</h2>
                        <p id="selectedExamMeta">Examination summary</p>
                    </div>
                </div>

                <div class="rd-live-status">
                    <span class="rd-live-dot"></span>
                    <span id="dashboardStatusText">Dashboard updated</span>
                </div>
            </section>

            {{-- SUMMARY CARDS --}}
            <section class="rd-summary-grid">
                <article class="rd-summary-card rd-card-students">
                    <div class="rd-summary-top">
                        <span>Total Students</span>
                        <span class="rd-summary-icon">
                            <i class="bi bi-people-fill"></i>
                        </span>
                    </div>

                    <div class="rd-summary-number" id="totalStudents">0</div>
                    <div class="rd-summary-bottom">
                        <i class="bi bi-person-check"></i>
                        Students across selected classes
                    </div>
                </article>

                <article class="rd-summary-card rd-card-generated">
                    <div class="rd-summary-top">
                        <span>Generated Results</span>
                        <span class="rd-summary-icon">
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </span>
                    </div>

                    <div class="rd-summary-number" id="generatedResults">0</div>
                    <div class="rd-summary-bottom">
                        <i class="bi bi-clipboard-check"></i>
                        Results generated for students
                    </div>
                </article>

                <article class="rd-summary-card rd-card-approved">
                    <div class="rd-summary-top">
                        <span>Approved Results</span>
                        <span class="rd-summary-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </span>
                    </div>

                    <div class="rd-summary-number" id="approvedResults">0</div>
                    <div class="rd-summary-bottom">
                        <i class="bi bi-shield-check"></i>
                        Results approved for publication
                    </div>
                </article>

                <article class="rd-summary-card rd-card-published">
                    <div class="rd-summary-top">
                        <span>Published Results</span>
                        <span class="rd-summary-icon">
                            <i class="bi bi-megaphone-fill"></i>
                        </span>
                    </div>

                    <div class="rd-summary-number" id="publishedResults">0</div>
                    <div class="rd-summary-bottom">
                        <i class="bi bi-globe"></i>
                        Results available to students
                    </div>
                </article>
            </section>

            {{-- CLASS RESULTS --}}
            <section class="rd-section-card">
                <div class="rd-section-header">
                    <div class="rd-section-title">
                        <span class="rd-section-title-icon">
                            <i class="bi bi-grid-1x2-fill"></i>
                        </span>

                        <div>
                            <h2>Class-wise Results</h2>
                            <p>Review result progress for each class separately.</p>
                        </div>
                    </div>

                    <span class="rd-class-count" id="classCount">0 classes</span>
                </div>

                <div class="rd-table-toolbar">
                    <div class="rd-search-box">
                        <i class="bi bi-search"></i>
                        <input
                            type="search"
                            id="classSearch"
                            placeholder="Search class or section..."
                            aria-label="Search classes"
                        >
                    </div>

                    <div class="rd-table-toolbar-right">
                        <button type="button" class="rd-btn rd-btn-light rd-btn-small" id="clearSearch">
                            <i class="bi bi-x-circle"></i>
                            Clear Search
                        </button>
                    </div>
                </div>

                <div class="rd-table-wrap">
                    <table class="rd-table">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Total Students</th>
                                <th>Generated</th>
                                <th>Approved</th>
                                <th>Published</th>
                                <th>Progress</th>
                                <th class="rd-action-column">Actions</th>
                            </tr>
                        </thead>

                        <tbody id="classResultsBody">
                            <tr>
                                <td colspan="7" class="rd-table-message">
                                    Select an examination to view class results.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="noClassResults" class="rd-empty-state" hidden>
                    <span class="rd-empty-icon">
                        <i class="bi bi-folder2-open"></i>
                    </span>
                    <h3>No classes found</h3>
                    <p>There are no class results matching your current selection.</p>
                </div>
            </section>

            {{-- QUICK ACTIONS --}}
            <section class="rd-section-card rd-quick-section">
                <div class="rd-section-header">
                    <div class="rd-section-title">
                        <span class="rd-section-title-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </span>

                        <div>
                            <h2>Quick Actions</h2>
                            <p>Continue managing the selected examination.</p>
                        </div>
                    </div>
                </div>

                <div class="rd-quick-grid">
                    <a href="{{ route('admin.results.generate') }}" class="rd-quick-action">
                        <span class="rd-quick-icon rd-quick-blue">
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <span class="rd-quick-copy">
                            <strong>Enter Marks</strong>
                            <small>Record student subject marks.</small>
                        </span>

                        <i class="bi bi-arrow-up-right rd-quick-arrow"></i>
                    </a>

                    <a href="{{ route('admin.results.class-results') }}" class="rd-quick-action">
                        <span class="rd-quick-icon rd-quick-green">
                            <i class="bi bi-clipboard-data-fill"></i>
                        </span>

                        <span class="rd-quick-copy">
                            <strong>Publish Results</strong>
                            <small>Review and manage class results.</small>
                        </span>

                        <i class="bi bi-arrow-up-right rd-quick-arrow"></i>
                    </a>

                    <a href="{{ route('admin.results.class-results') }}" class="rd-quick-action">
    <span class="rd-quick-icon rd-quick-purple">
        <i class="bi bi-clock-history"></i>
    </span>

    <span class="rd-quick-copy">
        <strong>Review Results</strong>
        <small>Review and manage class-wise results.</small>
    </span>

    <i class="bi bi-arrow-up-right rd-quick-arrow"></i>
</a>

                    <a href="{{ route('admin.reports.results.index') }}" class="rd-quick-action">
                        <span class="rd-quick-icon rd-quick-amber">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        </span>

                        <span class="rd-quick-copy">
                            <strong>Result Reports</strong>
                            <small>View examination reports.</small>
                        </span>

                        <i class="bi bi-arrow-up-right rd-quick-arrow"></i>
                    </a>
                </div>
            </section>
        </div>

        {{-- INITIAL EMPTY STATE --}}
        <section id="dashboardInitialEmpty" class="rd-empty-state rd-initial-empty" hidden>
            <span class="rd-empty-icon">
                <i class="bi bi-journal-bookmark"></i>
            </span>

            <h3>Select an Examination</h3>
            <p>Choose an examination above and click View Results to load its dashboard.</p>
        </section>

        <footer class="rd-footer">
            <i class="bi bi-mortarboard"></i>
            <span>School Management</span>
            <span class="rd-footer-separator">•</span>
            <span>Examination &amp; Result Management</span>
        </footer>

    </div>
</div>

<style>

/* SCHOOL DIARY — RESULT DASHBOARD
   Matches Exam Management: warm paper, forest green, cream and earthy accents
*/

.result-page {
    --rd-ink: #263b35;
    --rd-forest: #344f43;
    --rd-muted: #78827a;
    --rd-line: #e5e1d5;
    --rd-paper: #fffefa;
    --rd-bg: #f7f6f0;
    --rd-cream: #f1ead8;
    --rd-wood: #a87543;
    --rd-blue: #2b5d8a;
    --rd-green: #3a8961;
    --rd-teal: #527f79;
    --rd-red: #bd5148;

    min-height: calc(100vh - 70px);
    color: #34423d;
    background: var(--rd-bg);
    font-family: "Public Sans", "Segoe UI", Arial, sans-serif;
}

.result-page *,
.result-page *::before,
.result-page *::after {
    box-sizing: border-box;
}

.result-page a,
.result-page button {
    transition: all .2s ease;
}

.result-page .result-dashboard {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px !important;
}

/* HEADER */

.rd-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
    flex-wrap: wrap;
    overflow: hidden;
    padding: 30px;
    margin-bottom: 24px;
    border: 1px solid #30483b;
    border-radius: 10px;
    color: #fff;
    background: linear-gradient(125deg, #263b30, #344f43 65%, #526c54);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .08);
}

.rd-header::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 30px;
    width: 2px;
    background: rgba(241, 234, 216, .45);
    content: "";
}

.rd-header::after {
    position: absolute;
    top: -120px;
    right: -70px;
    width: 320px;
    height: 320px;
    border: 1px solid rgba(255, 255, 255, .12);
    border-radius: 50%;
    box-shadow: 0 0 0 35px rgba(255,255,255,.035),
                0 0 0 70px rgba(255,255,255,.025);
    content: "";
    pointer-events: none;
}

.rd-header-content,
.rd-header-actions {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 15px;
}

.rd-header-content {
    min-width: 0;
}

.rd-header-icon {
    display: flex;
    flex: 0 0 56px;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 12px;
    color: var(--rd-cream);
    background: rgba(255,255,255,.08);
    font-size: 24px;
}

.rd-eyebrow {
    display: block;
    margin-bottom: 8px;
    color: #d7c49b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.rd-header h1 {
    margin: 0;
    color: #fffefa;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 3vw, 32px);
    font-weight: 700;
    line-height: 1.2;
}

.rd-header p {
    max-width: 550px;
    margin: 9px 0 0;
    color: #e0e8df;
    font-size: 12px;
    line-height: 1.8;
}

.rd-header-actions {
    flex-wrap: wrap;
}

/* BUTTONS */

.rd-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 15px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 750;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.rd-btn:hover {
    transform: translateY(-2px);
}

.rd-btn:disabled {
    opacity: .65;
    cursor: wait;
    transform: none;
}

.rd-btn-primary {
    border-color: var(--rd-cream);
    color: var(--rd-ink);
    background: var(--rd-cream);
}

.rd-btn-primary:hover {
    border-color: #fffefa;
    color: var(--rd-ink);
    background: #fffefa;
}

.rd-btn-light {
    border-color: #dedfd5;
    color: var(--rd-ink);
    background: var(--rd-paper);
}

.rd-btn-light:hover {
    border-color: #c9d5c7;
    color: var(--rd-ink);
    background: #f0f2e9;
}

.rd-btn-dark {
    border-color: var(--rd-ink);
    color: #fffefa;
    background: var(--rd-ink);
}

.rd-btn-dark:hover {
    border-color: #456854;
    color: #fffefa;
    background: #456854;
}

.rd-btn-small {
    min-height: 34px;
    padding: 7px 11px;
    font-size: 10px;
}

/* ALERTS */

.rd-alert {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 14px 16px;
    margin-bottom: 20px;
    border: 1px solid;
    border-radius: 9px;
    font-size: 12px;
    line-height: 1.7;
}

.rd-alert > i {
    margin-top: 1px;
    font-size: 17px;
}

.rd-alert-success {
    border-color: #cfe4d2;
    color: #347849;
    background: #f2f8ef;
}

.rd-alert-danger {
    border-color: #ebd2ca;
    color: #a34038;
    background: #fff4f0;
}

.rd-alert-close {
    margin-left: auto;
    border: 0;
    color: inherit;
    background: transparent;
    cursor: pointer;
}

/* FILTER PANEL */

.rd-filter-card,
.rd-section-card {
    overflow: hidden;
    margin-bottom: 24px;
    border: 1px solid var(--rd-line);
    border-radius: 10px;
    background: var(--rd-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

.rd-filter-card {
    padding: 22px;
}

.rd-filter-heading,
.rd-section-title {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.rd-filter-heading {
    margin-bottom: 21px;
}

.rd-filter-icon,
.rd-section-title-icon {
    display: flex;
    flex: 0 0 43px;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    border: 1px solid #d9e3dd;
    border-radius: 10px;
    color: var(--rd-forest);
    background: #f0f2e9;
    font-size: 16px;
}

.rd-filter-heading h2,
.rd-section-title h2 {
    margin: 0;
    color: var(--rd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 19px;
    font-weight: 700;
}

.rd-filter-heading p,
.rd-section-title p {
    margin: 5px 0 0;
    color: var(--rd-muted);
    font-size: 11px;
    line-height: 1.7;
}

.rd-filter-controls {
    display: flex;
    align-items: flex-end;
    gap: 13px;
    flex-wrap: wrap;
}

.rd-field {
    flex: 1;
    min-width: 230px;
}

.rd-field label {
    display: block;
    margin-bottom: 8px;
    color: #526356;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.rd-field select {
    width: 100%;
    min-height: 43px;
    border: 1px solid #dedfd5;
    border-radius: 7px;
    color: var(--rd-ink);
    background-color: #fffefa;
    font-size: 12px;
}

.rd-field select:focus,
.rd-search-box:focus-within {
    border-color: #829a80;
    box-shadow: 0 0 0 3px rgba(82, 127, 121, .12);
}

.rd-filter-note {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-top: 14px;
    color: var(--rd-muted);
    font-size: 10px;
    line-height: 1.7;
}

.rd-filter-note i {
    color: var(--rd-teal);
}

/* LOADING */

.rd-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 11px;
    padding: 23px;
    margin-bottom: 20px;
    border: 1px solid var(--rd-line);
    border-radius: 9px;
    color: var(--rd-forest);
    background: var(--rd-paper);
    font-size: 12px;
    font-weight: 700;
}

.rd-loading[hidden],
.rd-alert[hidden],
#dashboardContent[hidden],
.rd-empty-state[hidden] {
    display: none !important;
}

/* SELECTED EXAM BANNER */

.rd-exam-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    padding: 21px 23px;
    margin-bottom: 25px;
    border: 1px solid #dce4d7;
    border-radius: 10px;
    background: linear-gradient(115deg, #f0f2e9, #fffefa);
}

.rd-exam-banner-main {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.rd-exam-icon {
    display: flex;
    flex: 0 0 48px;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border: 1px solid #d9e3dd;
    border-radius: 10px;
    color: var(--rd-forest);
    background: #e5ebdf;
    font-size: 21px;
}

.rd-exam-label {
    display: block;
    margin-bottom: 5px;
    color: var(--rd-wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.1px;
}

.rd-exam-banner h2 {
    margin: 0;
    color: var(--rd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 22px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.rd-exam-banner p {
    margin: 5px 0 0;
    color: var(--rd-muted);
    font-size: 11px;
}

.rd-live-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 11px;
    border: 1px solid #d8e4d4;
    border-radius: 20px;
    color: #426a49;
    background: #f4f8ef;
    font-size: 10px;
    font-weight: 750;
}

.rd-live-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #5a9a61;
    box-shadow: 0 0 0 3px #5a9a611c;
}

/* SUMMARY CARDS */

.rd-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    margin: 37px 0 27px;
}

.rd-summary-card {
    --card-color: #2b5d8a;
    --card-tint: #edf3f8;
    --card-border: #d7e2e9;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-width: 0;
    min-height: 180px;
    padding: 23px 20px 17px;
    border: 1px solid var(--rd-line);
    border-top: 3px solid var(--card-color);
    border-radius: 9px;
    background: var(--rd-paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.rd-summary-card:hover {
    transform: translateY(-3px);
    border-color: var(--card-border);
    box-shadow: 0 10px 23px rgba(38, 59, 53, .075);
}

.rd-card-students {
    --card-color: #2b5d8a;
    --card-tint: #edf3f8;
    --card-border: #d7e2e9;
}

.rd-card-generated {
    --card-color: #a87543;
    --card-tint: #faf3e6;
    --card-border: #e8dcc5;
}

.rd-card-approved {
    --card-color: #3a8961;
    --card-tint: #edf6ed;
    --card-border: #d0e5d2;
}

.rd-card-published {
    --card-color: #527f79;
    --card-tint: #edf5f2;
    --card-border: #d0e3dc;
}

.rd-summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    color: #68756b;
    font-size: 11px;
    font-weight: 750;
}

.rd-summary-icon {
    display: flex;
    flex: 0 0 43px;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    border: 1px solid var(--card-border);
    border-radius: 10px;
    color: var(--card-color);
    background: var(--card-tint);
    font-size: 17px;
}

.rd-summary-number {
    color: var(--rd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 38px;
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: -.5px;
    overflow-wrap: anywhere;
}

.rd-summary-bottom {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    min-height: 30px;
    margin-top: auto;
    padding-top: 11px;
    border-top: 1px dashed #ded8c9;
    color: var(--rd-muted);
    font-size: 10px;
    line-height: 1.6;
}

.rd-summary-bottom i {
    flex-shrink: 0;
    padding-top: 1px;
    color: var(--card-color);
}

/* SECTION HEADERS */

.rd-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
    padding: 19px 22px;
    border-bottom: 1px dashed #dcd6c7;
    background: var(--rd-paper);
}

.rd-class-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 32px;
    padding: 7px 12px;
    border: 1px solid #e5e1d5;
    border-radius: 20px;
    color: var(--rd-ink);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

/* SEARCH TOOLBAR */

.rd-table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 15px 20px;
    border-bottom: 1px solid #eeece3;
    background: #faf9f4;
}

.rd-search-box {
    display: flex;
    align-items: center;
    gap: 9px;
    width: min(100%, 350px);
    min-height: 38px;
    padding: 0 11px;
    border: 1px solid #dedfd5;
    border-radius: 7px;
    color: #899287;
    background: #fffefa;
}

.rd-search-box input {
    width: 100%;
    min-width: 0;
    padding: 9px 0;
    border: 0;
    outline: none;
    color: var(--rd-ink);
    background: transparent;
    font-family: inherit;
    font-size: 11px;
}

.rd-table-toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* RESULTS TABLE */

.rd-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.rd-table {
    width: 100%;
    min-width: 900px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.rd-table th {
    padding: 14px 15px;
    border-bottom: 1px solid #e5e1d5;
    color: #707a70;
    background: #f5f3e9;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .5px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.rd-table td {
    padding: 14px 15px;
    border-bottom: 1px solid #eeece3;
    color: #526054;
    background: var(--rd-paper);
    font-size: 11px;
    vertical-align: middle;
}

.rd-table tbody tr {
    transition: background .18s ease;
}

.rd-table tbody tr:hover td {
    background: #faf9f2;
}

.rd-table tbody tr:last-child td {
    border-bottom: 0;
}

.rd-table .rd-action-column {
    text-align: right;
}

.rd-class-name {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 135px;
}

.rd-class-icon-small {
    display: flex;
    flex: 0 0 37px;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    border: 1px solid #d9e3dd;
    border-radius: 9px;
    color: var(--rd-forest);
    background: #f0f2e9;
    font-size: 14px;
}

.rd-class-name strong {
    display: block;
    color: var(--rd-ink);
    font-size: 11px;
    font-weight: 800;
}

.rd-class-name small {
    display: block;
    margin-top: 4px;
    color: var(--rd-muted);
    font-size: 9px;
}

.rd-count-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 35px;
    padding: 6px 9px;
    border: 1px solid #e5e1d5;
    border-radius: 7px;
    color: var(--rd-ink);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 800;
}

.rd-count-chip.is-complete {
    border-color: #cfe3d2;
    color: #347849;
    background: #eff7ed;
}

.rd-progress-cell {
    min-width: 130px;
}

.rd-progress-label {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--rd-muted);
    font-size: 9px;
}

.rd-progress-label strong {
    color: var(--rd-ink);
}

.rd-progress-track {
    width: 100%;
    height: 6px;
    overflow: hidden;
    border-radius: 20px;
    background: #e9ece4;
}

.rd-progress-fill {
    height: 100%;
    border-radius: inherit;
    background: #6b9470;
    transition: width .25s ease;
}

.rd-row-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    white-space: nowrap;
}

.rd-action-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 33px;
    padding: 7px 9px;
    border: 1px solid #dedfd5;
    border-radius: 7px;
    color: var(--rd-ink);
    background: #fffefa;
    font-size: 10px;
    font-weight: 750;
    text-decoration: none;
}

.rd-action-link:hover {
    transform: translateY(-1px);
    border-color: #cbd8c6;
    color: var(--rd-forest);
    background: #f0f2e9;
}

/* EMPTY STATES */

.rd-table-message {
    padding: 32px 15px !important;
    color: var(--rd-muted) !important;
    text-align: center;
}

.rd-empty-state {
    display: flex;
    align-items: center;
    flex-direction: column;
    padding: 38px 20px;
    text-align: center;
}

.rd-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 62px;
    height: 62px;
    border: 1px solid #e5dfcc;
    border-radius: 13px;
    color: var(--rd-forest);
    background: #f4f0df;
    font-size: 25px;
}

.rd-empty-state h3 {
    margin: 15px 0 7px;
    color: var(--rd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 21px;
    font-weight: 700;
}

.rd-empty-state p {
    max-width: 440px;
    margin: 0;
    color: var(--rd-muted);
    font-size: 11px;
    line-height: 1.8;
}

/* QUICK ACTIONS */

.rd-quick-section {
    padding-bottom: 20px;
}

.rd-quick-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 13px;
    padding: 20px 22px 0;
}

.rd-quick-action {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    padding: 14px 12px;
    border: 1px solid var(--rd-line);
    border-radius: 9px;
    background: #fffefa;
    text-decoration: none;
}

.rd-quick-action:hover {
    transform: translateY(-2px);
    border-color: #cbd8c6;
    box-shadow: 0 7px 17px rgba(38, 59, 53, .06);
}

.rd-quick-icon {
    display: flex;
    flex: 0 0 39px;
    align-items: center;
    justify-content: center;
    width: 39px;
    height: 39px;
    border-radius: 9px;
    font-size: 16px;
}

.rd-quick-blue {
    color: #426b8a;
    background: #edf3f8;
}

.rd-quick-green {
    color: #416f49;
    background: #edf6ed;
}

.rd-quick-purple {
    color: #73528e;
    background: #f0e9f6;
}

.rd-quick-amber {
    color: #956d31;
    background: #faf3e6;
}

.rd-quick-copy {
    display: flex;
    flex: 1;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.rd-quick-copy strong {
    color: var(--rd-ink);
    font-size: 11px;
    font-weight: 800;
}

.rd-quick-copy small {
    color: var(--rd-muted);
    font-size: 9px;
    line-height: 1.5;
}

.rd-quick-arrow {
    flex-shrink: 0;
    color: #8a9689;
    font-size: 12px;
}

/* FOOTER */

.rd-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    padding: 19px 8px 5px;
    color: #8a9185;
    font-size: 9px;
    font-weight: 600;
}

.rd-footer i {
    color: var(--rd-wood);
}

.rd-footer-separator {
    color: #b4ad9b;
}

/* RESPONSIVE */

@media (max-width: 1200px) {
    .rd-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 27px 16px;
    }

    .rd-quick-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .rd-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .rd-header-actions {
        width: 100%;
    }
}

@media (max-width: 650px) {
    .result-page .result-dashboard {
        padding: 16px 13px !important;
    }

    .rd-header {
        padding: 22px 17px 22px 29px;
        border-radius: 9px;
    }

    .rd-header::before {
        left: 17px;
    }

    .rd-header-content {
        align-items: flex-start;
        gap: 12px;
    }

    .rd-header-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 10px;
        font-size: 19px;
    }

    .rd-header h1 {
        font-size: 25px;
    }

    .rd-header p {
        font-size: 11px;
    }

    .rd-header-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .rd-header-actions .rd-btn {
        padding: 10px 8px;
        white-space: normal;
    }

    .rd-filter-card {
        padding: 16px;
    }

    .rd-filter-controls {
        align-items: stretch;
        flex-direction: column;
    }

    .rd-field {
        width: 100%;
        min-width: 0;
    }

    .rd-filter-controls > .rd-btn {
        width: 100%;
    }

    .rd-summary-grid {
        gap: 27px 12px;
    }

    .rd-summary-card {
        min-height: 165px;
        padding: 18px 14px 14px;
    }

    .rd-summary-number {
        font-size: 32px;
    }

    .rd-summary-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
        font-size: 15px;
    }

    .rd-summary-bottom {
        font-size: 9px;
    }

    .rd-exam-banner {
        align-items: flex-start;
        flex-direction: column;
        padding: 17px;
    }

    .rd-exam-banner h2 {
        font-size: 19px;
    }

    .rd-section-header {
        align-items: flex-start;
        padding: 15px;
    }

    .rd-section-title {
        gap: 9px;
    }

    .rd-section-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .rd-section-title h2 {
        font-size: 17px;
    }

    .rd-section-title p {
        font-size: 10px;
    }

    .rd-table-toolbar {
        align-items: stretch;
        flex-direction: column;
        padding: 13px 15px;
    }

    .rd-search-box {
        width: 100%;
    }

    .rd-table-toolbar-right .rd-btn {
        width: 100%;
    }

    .rd-quick-grid {
        grid-template-columns: 1fr;
        padding: 15px;
    }

    .rd-quick-section {
        padding-bottom: 5px;
    }

    .rd-footer {
        line-height: 1.8;
        text-align: center;
    }
}

@media (max-width: 380px) {
    .rd-summary-grid {
        grid-template-columns: 1fr;
    }

    .rd-header-actions {
        grid-template-columns: 1fr;
    }

    .rd-header h1 {
        font-size: 22px;
    }

    .rd-section-header {
        flex-direction: column;
    }

    .rd-class-count {
        align-self: flex-start;
    }
}

/* ACCESSIBILITY */

.result-page .rd-btn:focus-visible,
.result-page .rd-action-link:focus-visible,
.result-page .rd-quick-action:focus-visible {
    outline: 3px solid rgba(82, 127, 121, .35);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .result-page *,
    .result-page *::before,
    .result-page *::after {
        animation: none !important;
        transition: none !important;
        scroll-behavior: auto !important;
    }
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const examSelect = document.getElementById('examSelect');
    const loadButton = document.getElementById('loadResultsButton');
    const refreshButton = document.getElementById('refreshDashboard');
    const retryButton = document.getElementById('retryDashboard');
    const searchInput = document.getElementById('classSearch');
    const clearSearchButton = document.getElementById('clearSearch');

    const loading = document.getElementById('dashboardLoading');
    const errorBox = document.getElementById('dashboardError');
    const errorMessage = document.getElementById('dashboardErrorMessage');
    const dashboardContent = document.getElementById('dashboardContent');
    const initialEmpty = document.getElementById('dashboardInitialEmpty');
    const tbody = document.getElementById('classResultsBody');
    const noClassResults = document.getElementById('noClassResults');

    let requestController = null;
    let requestSequence = 0;
    let currentClasses = [];
    let refreshTimer = null;

    const routeTemplate = @json(route('admin.results.exam-classes', ['exam' => '__EXAM_ID__']));
    const classResultsUrl = @json(route('admin.results.class-results'));
    const bulkWhatsAppUrl = @json(route('admin.results.bulk-whatsapp'));

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function numberValue(value) {
        const parsed = Number(value);
        return Number.isFinite(parsed) && parsed >= 0 ? parsed : 0;
    }

    function formatNumber(value) {
        return numberValue(value).toLocaleString();
    }

    function getFirst(object, keys, fallback = 0) {
        for (const key of keys) {
            if (object && object[key] !== undefined && object[key] !== null) {
                return object[key];
            }
        }

        return fallback;
    }

    function getClassName(item) {
        const classObject = item.school_class || item.schoolClass || item.class || {};

        const name = getFirst(item, [
            'class_name',
            'name',
            'className'
        ], getFirst(classObject, [
            'class_name',
            'name'
        ], 'Class'));

        const section = getFirst(item, [
            'section',
            'class_section'
        ], getFirst(classObject, [
            'section'
        ], ''));

        return {
            name: String(name || 'Class'),
            section: String(section || '')
        };
    }

    function getCount(item, keys) {
        return numberValue(getFirst(item, keys, 0));
    }

    function normalizeClass(item) {
        const classInfo = getClassName(item);

        const students = getCount(item, [
            'total_students',
            'students_count',
            'student_count',
            'totalStudents'
        ]);

        const generated = getCount(item, [
            'generated_results',
            'results_generated',
            'generated_count',
            'generated',
            'results_count'
        ]);

        const approved = getCount(item, [
            'approved_results',
            'results_approved',
            'approved_count',
            'approved'
        ]);

        const published = getCount(item, [
            'published_results',
            'results_published',
            'published_count',
            'published'
        ]);

        return {
            id: getFirst(item, ['exam_class_id', 'class_id', 'id'], ''),
            name: classInfo.name,
            section: classInfo.section,
            students: students,
            generated: generated,
            approved: approved,
            published: published
        };
    }

    function setLoading(isLoading) {
        loading.hidden = !isLoading;
        loadButton.disabled = isLoading;
        refreshButton.disabled = isLoading;

        loadButton.innerHTML = isLoading
            ? '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Loading...'
            : '<i class="bi bi-search"></i> View Results';

        refreshButton.innerHTML = isLoading
            ? '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Refreshing'
            : '<i class="bi bi-arrow-clockwise"></i><span>Refresh</span>';
    }

    function showError(message) {
        errorMessage.textContent = message || 'Unable to load results. Please try again.';
        errorBox.hidden = false;
    }

    function hideError() {
        errorBox.hidden = true;
        errorMessage.textContent = '';
    }

    function getSelectedExamName() {
        return examSelect.options[examSelect.selectedIndex]?.textContent.trim() || '';
    }

    function getExamMeta(data) {
        const exam = data.exam || data.selected_exam || {};
        const parts = [];

        const year = getFirst(exam, ['academic_year', 'year'], '');
        const type = getFirst(exam, ['exam_type', 'type'], '');

        if (year) parts.push(String(year));
        if (type) parts.push(String(type));

        return parts.join(' • ') || 'Examination summary';
    }

    function updateSummary(data, classes) {
        const summary = data.summary || data.statistics || data.stats || {};

        const totalStudents = getFirst(summary, [
            'total_students',
            'students',
            'totalStudents'
        ], classes.reduce((sum, item) => sum + item.students, 0));

        const generated = getFirst(summary, [
            'generated_results',
            'total_generated',
            'generated',
            'generatedResults'
        ], classes.reduce((sum, item) => sum + item.generated, 0));

        const approved = getFirst(summary, [
            'approved_results',
            'total_approved',
            'approved',
            'approvedResults'
        ], classes.reduce((sum, item) => sum + item.approved, 0));

        const published = getFirst(summary, [
            'published_results',
            'total_published',
            'published',
            'publishedResults'
        ], classes.reduce((sum, item) => sum + item.published, 0));

        document.getElementById('totalStudents').textContent = formatNumber(totalStudents);
        document.getElementById('generatedResults').textContent = formatNumber(generated);
        document.getElementById('approvedResults').textContent = formatNumber(approved);
        document.getElementById('publishedResults').textContent = formatNumber(published);
    }

    function createProgress(item) {
        const percentage = item.students > 0
            ? Math.min(100, Math.round((item.generated / item.students) * 100))
            : 0;

        return `
            <div class="rd-progress-cell">
                <div class="rd-progress-label">
                    <span>Generated</span>
                    <strong>${percentage}%</strong>
                </div>
                <div class="rd-progress-track"
                     role="progressbar"
                     aria-valuenow="${percentage}"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     aria-label="Result generation progress">
                    <div class="rd-progress-fill" style="width:${percentage}%"></div>
                </div>
            </div>
        `;
    }

    function buildClassUrl(baseUrl, item) {
        const url = new URL(baseUrl, window.location.origin);

        if (examSelect.value) {
            url.searchParams.set('exam_id', examSelect.value);
        }

        if (item.id !== '') {
            url.searchParams.set('class_id', item.id);
        }

        if (item.name) {
            url.searchParams.set('class_name', item.name);
        }

        if (item.section) {
            url.searchParams.set('section', item.section);
        }

        return url.pathname + url.search;
    }

    function renderClassRows(classes) {
        const query = (searchInput.value || '').trim().toLowerCase();

        const filtered = classes.filter(item => {
            const searchable = (item.name + ' ' + item.section).toLowerCase();
            return searchable.includes(query);
        });

        document.getElementById('classCount').textContent =
            classes.length + (classes.length === 1 ? ' class' : ' classes');

        tbody.innerHTML = '';

        if (!filtered.length) {
            noClassResults.hidden = false;

            const message = classes.length
                ? 'No classes match your search.'
                : 'No classes have been configured for this examination.';

            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="rd-table-message">${escapeHtml(message)}</td>
                </tr>
            `;

            return;
        }

        noClassResults.hidden = true;

        filtered.forEach(item => {
            const generated = item.generated;
            const approved = item.approved;
            const published = item.published;

            const row = document.createElement('tr');

            row.innerHTML = `
                <td>
                    <div class="rd-class-name">
                        <span class="rd-class-icon-small">
                            <i class="bi bi-mortarboard-fill"></i>
                        </span>
                        <div>
                            <strong>${escapeHtml(item.name)}</strong>
                            <small>${item.section ? 'Section ' + escapeHtml(item.section) : 'All sections'}</small>
                        </div>
                    </div>
                </td>

                <td>
                    <span class="rd-count-chip">${formatNumber(item.students)}</span>
                </td>

                <td>
                    <span class="rd-count-chip ${generated > 0 ? 'is-complete' : ''}">
                        ${formatNumber(generated)}
                    </span>
                </td>

                <td>
                    <span class="rd-count-chip ${approved > 0 ? 'is-complete' : ''}">
                        ${formatNumber(approved)}
                    </span>
                </td>

                <td>
                    <span class="rd-count-chip ${published > 0 ? 'is-complete' : ''}">
                        ${formatNumber(published)}
                    </span>
                </td>

                <td>${createProgress(item)}</td>

                <td>
                    <div class="rd-row-actions">
                        <a class="rd-action-link"
                           href="${escapeHtml(buildClassUrl(classResultsUrl, item))}">
                            <i class="bi bi-eye"></i> View
                        </a>

                        <a class="rd-action-link"
                           href="${escapeHtml(buildClassUrl(bulkWhatsAppUrl, item))}">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </td>
            `;

            tbody.appendChild(row);
        });
    }

    function normalizePayload(payload) {
        let data = payload;

        if (data && data.data && !Array.isArray(data.data)) {
            data = { ...data, ...data.data };
        }

        let rawClasses = [];

        if (Array.isArray(data.classes)) {
            rawClasses = data.classes;
        } else if (Array.isArray(data.exam_classes)) {
            rawClasses = data.exam_classes;
        } else if (Array.isArray(data.class_results)) {
            rawClasses = data.class_results;
        } else if (Array.isArray(data.results)) {
            rawClasses = data.results;
        } else if (Array.isArray(data.data)) {
            rawClasses = data.data;
        }

        return {
            ...data,
            classes: rawClasses.map(normalizeClass)
        };
    }

    async function loadDashboard(options = {}) {
        const examId = examSelect.value;

        if (!examId) {
            dashboardContent.hidden = true;
            initialEmpty.hidden = false;
            hideError();
            return;
        }

        if (requestController) {
            requestController.abort();
        }

        if (refreshTimer) {
            clearTimeout(refreshTimer);
            refreshTimer = null;
        }

        requestController = new AbortController();

        const controller = requestController;
        const sequence = ++requestSequence;

        hideError();
        initialEmpty.hidden = true;
        setLoading(true);

        const endpoint = new URL(
    @json(route('admin.results.exam-classes')),
    window.location.origin
);

endpoint.searchParams.set('exam_id', examId);

        try {
            const response = await fetch(endpoint.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                signal: controller.signal
            });

            const contentType = response.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                throw new Error(
                    'The results endpoint did not return JSON. Check the controller response and route.'
                );
            }

const responseText = await response.text();
const cleanText = responseText.replace(/^[\s\uFEFF]+/, '');

let payload;

try {
    payload = JSON.parse(cleanText);
} catch (error) {
    console.error('Invalid Result Dashboard response:', {
        url: response.url,
        status: response.status,
        response: responseText
    });

    throw new Error(
        'The server returned an invalid response. Check the browser console and Laravel logs.'
    );
}
            if (!response.ok) {
                throw new Error(
                    payload.message || 'Unable to load results. Server returned ' + response.status + '.'
                );
            }

            if (sequence !== requestSequence) return;

            const data = normalizePayload(payload);

            currentClasses = data.classes;

            document.getElementById('selectedExamName').textContent =
                data.exam?.exam_name ||
                data.selected_exam?.exam_name ||
                getSelectedExamName();

            document.getElementById('selectedExamMeta').textContent = getExamMeta(data);

            updateSummary(data, currentClasses);
            renderClassRows(currentClasses);

            document.getElementById('dashboardStatusText').textContent =
                'Updated ' + new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

            dashboardContent.hidden = false;
            initialEmpty.hidden = true;

            if (options.autoRefresh) {
                refreshTimer = setTimeout(function () {
                    if (examSelect.value === examId) {
                        loadDashboard({ silent: true, autoRefresh: true });
                    }
                }, 10000);
            }
        } catch (error) {
            if (error.name === 'AbortError') return;
            if (sequence !== requestSequence) return;

            if (options.silent && dashboardContent.hidden === false) {
                document.getElementById('dashboardStatusText').textContent =
                    'Refresh failed — showing last loaded data';
            } else {
                showError(error.message || 'Unable to load results. Please try again.');
            }
        } finally {
            if (sequence === requestSequence) {
                setLoading(false);
            }
        }
    }

    loadButton.addEventListener('click', function () {
        loadDashboard({ autoRefresh: true });
    });

    refreshButton.addEventListener('click', function () {
        loadDashboard({ autoRefresh: true });
    });

    retryButton.addEventListener('click', function () {
        loadDashboard({ autoRefresh: true });
    });

    examSelect.addEventListener('change', function () {
        if (refreshTimer) {
            clearTimeout(refreshTimer);
            refreshTimer = null;
        }

        dashboardContent.hidden = true;
        initialEmpty.hidden = !examSelect.value;
        hideError();

        if (examSelect.value) {
            loadDashboard({ autoRefresh: true });
        }
    });

    searchInput.addEventListener('input', function () {
        renderClassRows(currentClasses);
    });

    clearSearchButton.addEventListener('click', function () {
        searchInput.value = '';
        renderClassRows(currentClasses);
        searchInput.focus();
    });

    // Load a previously selected examination when the page opens.
    if (examSelect.value) {
        loadDashboard({ autoRefresh: true });
    } else {
        dashboardContent.hidden = true;
        initialEmpty.hidden = false;
    }

    // Stop scheduled refreshes when navigating away.
    window.addEventListener('pagehide', function () {
        if (refreshTimer) clearTimeout(refreshTimer);
        if (requestController) requestController.abort();
    });
});
</script>
@endsection