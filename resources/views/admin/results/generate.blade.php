
@extends('layouts.app')

@section('title', 'Generate Result')

@section('content')
<style>
    
.result-page {
    --rp-page: #f7f6f0;
    --rp-paper: #fffefa;
    --rp-ink: #263b35;
    --rp-forest: #344f43;
    --rp-forest-dark: #263d32;
    --rp-wood: #a87543;
    --rp-sage: #e8eee6;
    --rp-border: #e5e0d4;
    --rp-muted: #77796f;
    color: var(--rp-ink);
    padding: 24px;
    background: var(--rp-page);
    border-radius: 16px;
    font-family: Inter, "Segoe UI", sans-serif;
}

.result-page .page-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 28px;
}

.result-page .heading-title,
.result-page .panel-heading-main {
    display: flex;
    align-items: center;
    gap: 15px;
}

.result-page .heading-icon,
.result-page .section-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 13px;
    background: var(--rp-sage);
    color: var(--rp-forest);
    font-size: 21px;
    border: 1px solid #dce5da;
}

.result-page .page-title {
    margin: 0 0 7px;
    color: var(--rp-forest-dark);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 1.85rem;
    font-weight: 700;
    letter-spacing: -.4px;
}

.result-page .page-subtitle {
    margin: 0;
    color: var(--rp-muted);
    font-size: .9rem;
    line-height: 1.6;
}

.result-page .panel {
    background: var(--rp-paper);
    border: 1px solid var(--rp-border);
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(48, 56, 43, .045);
    overflow: hidden;
}

.result-page .panel-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    padding: 20px 24px;
    background: #fbfaf5;
    border-bottom: 1px solid var(--rp-border);
}

.result-page .panel-heading .section-icon {
    width: 42px;
    height: 42px;
    font-size: 18px;
    border-radius: 11px;
}

.result-page .panel-title {
    margin: 0 0 5px;
    color: var(--rp-forest-dark);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 1.18rem;
    font-weight: 700;
}

.result-page .panel-description {
    color: var(--rp-muted);
    font-size: .83rem;
    margin: 0;
}

.result-page .panel-body {
    padding: 25px 24px;
}

.result-page .form-label {
    display: flex;
    align-items: center;
    color: var(--rp-ink);
    font-size: .88rem;
    font-weight: 650;
    margin-bottom: 10px;
}

.result-page .step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    margin-right: 9px;
    border-radius: 9px;
    background: var(--rp-sage);
    color: var(--rp-forest);
    border: 1px solid #dce5da;
    font-size: .78rem;
    font-weight: 700;
}

.result-page .form-select {
    min-height: 46px;
    border: 1px solid #dcd9ce;
    border-radius: 10px;
    background-color: #fffefa;
    color: var(--rp-ink);
    font-size: .9rem;
    box-shadow: none;
    transition: border-color .2s, box-shadow .2s;
}

.result-page .form-select:focus {
    border-color: var(--rp-wood);
    box-shadow: 0 0 0 .2rem rgba(168, 117, 67, .13);
}

.result-page .form-select:disabled {
    background-color: #f0efe8;
    cursor: not-allowed;
}

.result-page .field-help {
    display: block;
    margin-top: 8px;
    color: var(--rp-muted);
    font-size: .78rem;
    line-height: 1.6;
}

.result-page .workflow {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 27px;
    padding: 15px 17px;
    border: 1px solid var(--rp-border);
    border-radius: 12px;
    background: #fbfaf5;
}

.result-page .workflow-step {
    display: flex;
    align-items: center;
    white-space: nowrap;
    color: #8a897e;
    font-size: .79rem;
    font-weight: 650;
}

.result-page .workflow-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
    background: #d4d1c7;
}

.result-page .workflow-step.active {
    color: var(--rp-forest);
}

.result-page .workflow-step.active .workflow-dot {
    background: var(--rp-forest);
    box-shadow: 0 0 0 4px #e8eee6;
}

.result-page .workflow-line {
    height: 1px;
    min-width: 15px;
    flex: 1;
    margin: 0 13px;
    background: #dcd9ce;
}

.result-page .selection-summary {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 15px 17px;
    background: #f0f4ec;
    border: 1px solid #dce5d7;
    border-radius: 12px;
}

.result-page .summary-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 39px;
    height: 39px;
    flex-shrink: 0;
    background: #dfe9dc;
    color: var(--rp-forest);
    border-radius: 10px;
}

.result-page .selection-label {
    font-size: .75rem;
    color: var(--rp-muted);
    margin-bottom: 4px;
}

.result-page .selection-value {
    color: var(--rp-forest-dark);
    font-size: .91rem;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.result-page .action-area {
    border-top: 1px solid #ebe7dc;
    margin-top: 25px;
    padding-top: 20px;
}

.result-page .btn {
    border-radius: 9px;
    font-size: .86rem;
    font-weight: 650;
    padding: 10px 16px;
    transition: background .2s, border-color .2s, transform .2s;
}

.result-page .btn-primary {
    color: #fff;
    background: var(--rp-forest);
    border-color: var(--rp-forest);
}

.result-page .btn-primary:hover {
    color: #fff;
    background: var(--rp-forest-dark);
    border-color: var(--rp-forest-dark);
}

.result-page .btn-success {
    color: #fff;
    background: var(--rp-wood);
    border-color: var(--rp-wood);
}

.result-page .btn-success:hover {
    color: #fff;
    background: #8f6036;
    border-color: #8f6036;
}

.result-page .btn:disabled {
    cursor: not-allowed;
    opacity: .55;
}

.result-page .status-alert {
    border-radius: 11px;
    font-size: .88rem;
}

.result-page .loading-alert {
    color: var(--rp-forest);
    background: #edf3e9;
    border: 1px solid #d9e5d4;
}

.result-page .student-count {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 13px;
    border: 1px solid #dce5d7;
    border-radius: 10px;
    background: #f0f4ec;
    color: var(--rp-forest);
    font-size: .81rem;
    font-weight: 700;
    white-space: nowrap;
}

.result-page .table-responsive {
    width: 100%;
}

.result-page .table {
    margin-bottom: 0;
    font-size: .86rem;
    vertical-align: middle;
    color: var(--rp-ink);
}

.result-page .table thead th {
    padding: 15px 16px;
    background: #f0efe7;
    color: #596258;
    border-bottom: 1px solid #dfdbcf;
    font-size: .73rem;
    font-weight: 750;
    letter-spacing: .045em;
    text-transform: uppercase;
    white-space: nowrap;
}

.result-page .table tbody td {
    padding: 14px 16px;
    border-color: #eeeadf;
}

.result-page .table tbody tr {
    transition: background .15s;
}

.result-page .table tbody tr:hover {
    background: #f8f7f0;
}

.result-page .student-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    margin-right: 11px;
    border-radius: 11px;
    background: #e8eee6;
    color: var(--rp-forest);
    border: 1px solid #dce5da;
    font-size: .82rem;
    font-weight: 750;
}

.result-page .student-name {
    color: var(--rp-forest-dark);
    font-weight: 700;
}

.result-page .student-id {
    color: var(--rp-wood);
    font-weight: 700;
    font-size: .83rem;
}

.result-page .roll-badge {
    display: inline-block;
    padding: 6px 9px;
    background: #f0efe8;
    color: #555b50;
    border: 1px solid #e4e0d5;
    border-radius: 7px;
    font-size: .78rem;
    font-weight: 650;
}

.result-page .marks-badge {
    display: inline-block;
    padding: 6px 9px;
    background: #e9f3e6;
    color: #376342;
    border: 1px solid #d3e5ce;
    border-radius: 8px;
    font-size: .75rem;
    font-weight: 700;
    white-space: nowrap;
}

.result-page .generate-student-btn {
    padding: 7px 11px;
    font-size: .79rem;
    white-space: nowrap;
}

.result-page .empty-state {
    padding: 48px 20px;
    text-align: center;
}

.result-page .empty-state-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    margin-bottom: 16px;
    border-radius: 17px;
    background: #f0efe8;
    color: var(--rp-forest);
    font-size: 1.7rem;
}

.result-page .empty-state h6 {
    color: var(--rp-forest-dark);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 1.05rem;
}

.result-page .table-footer {
    padding: 17px 24px;
    background: #fbfaf5;
    border-top: 1px solid var(--rp-border);
}

.result-page .table-footer small {
    line-height: 1.7;
}

.result-page .badge.bg-light {
    background: #f3f1e9 !important;
    color: #53594f !important;
    border-color: #e3dfd3 !important;
}

.result-page .badge.bg-secondary-subtle {
    background: #f0efe8 !important;
    color: #68695f !important;
    border-color: #e1ddd1 !important;
}

@media (max-width: 991.98px) {
    .result-page {
        padding: 19px;
    }

    .result-page .workflow {
        flex-wrap: wrap;
        gap: 12px;
    }

    .result-page .workflow-line {
        display: none;
    }
}

@media (max-width: 767.98px) {
    .result-page {
        padding: 14px;
        border-radius: 12px;
    }

    .result-page .page-heading {
        align-items: flex-start;
    }

    .result-page .page-title {
        font-size: 1.5rem;
    }

    .result-page .heading-actions {
        width: 100%;
    }

    .result-page .heading-actions .btn {
        width: 100%;
    }

    .result-page .panel-heading,
    .result-page .panel-body,
    .result-page .table-footer {
        padding: 16px;
    }

    .result-page .panel-heading-main {
        gap: 11px;
    }

    .result-page .workflow {
        padding: 13px;
    }

    .result-page .workflow-step {
        font-size: .76rem;
    }

    .result-page .footer-content {
        flex-direction: column;
        align-items: stretch !important;
    }

    .result-page .footer-content .btn {
        width: 100%;
    }

    .result-page .table {
        min-width: 900px;
    }
}

</style>

<div class="container-fluid py-4 result-page">

    {{-- PAGE HEADING --}}
    <div class="page-heading">
        <div class="heading-title">
            <div class="heading-icon">
                <i class="bi bi-file-earmark-check"></i>
            </div>

            <div>
                <h4 class="page-title">Generate Result</h4>
                <p class="page-subtitle">
                    Select an examination, class and section to generate student results.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('admin.results.index') }}"
               class="btn btn-primary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Results
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show status-alert shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show status-alert shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- SELECTION PANEL --}}
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-heading-main">
                <div class="section-icon">
                    <i class="bi bi-funnel"></i>
                </div>
                <div>
                    <h5 class="panel-title">Result Selection</h5>
                    <p class="panel-description">
                        Choose the examination, class and section.
                    </p>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="workflow">
                <div class="workflow-step active">
                    <span class="workflow-dot"></span> Examination
                </div>
                <div class="workflow-line"></div>
                <div class="workflow-step active">
                    <span class="workflow-dot"></span> Class
                </div>
                <div class="workflow-line"></div>
                <div class="workflow-step active">
                    <span class="workflow-dot"></span> Section
                </div>
                <div class="workflow-line"></div>
                <div class="workflow-step">
                    <span class="workflow-dot"></span> Students
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <label for="exam_id" class="form-label">
                        <span class="step-number">1</span> Examination
                    </label>

                    <select id="exam_id" class="form-select">
                        <option value="">Select Examination</option>
                        @foreach($exams ?? [] as $exam)
                            <option value="{{ $exam->id }}">
                                {{ $exam->exam_name }}
                                @if($exam->academic_year)
                                    - {{ $exam->academic_year }}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <small class="field-help">
                        Choose the examination for which results are required.
                    </small>
                </div>

                <div class="col-lg-4">
                    <label for="class_id" class="form-label">
                        <span class="step-number">2</span> Class
                    </label>

                    <select id="class_id" class="form-select" disabled>
                        <option value="">Select Class</option>
                    </select>

                    <small class="field-help">
                        Only classes assigned to the selected examination will appear.
                    </small>
                </div>

                <div class="col-lg-4">
                    <label for="section" class="form-label">
                        <span class="step-number">3</span> Section
                    </label>

                    <select id="section" class="form-select" disabled>
                        <option value="">Select Section</option>
                    </select>

                    <small class="field-help">
                        Select a section to load its students.
                    </small>
                </div>
            </div>

            <div id="selectionInfo" class="selection-summary mt-4 d-none">
                <div class="summary-icon">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div>
                    <div class="selection-label">Current Selection</div>
                    <div id="selectionInfoText" class="selection-value"></div>
                </div>
            </div>

            <div id="loadingMessage"
                 class="alert loading-alert status-alert mt-4 mb-0 d-none"
                 role="status">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                <span id="loadingText">Loading...</span>
            </div>

            <div id="errorMessage"
                 class="alert alert-danger status-alert mt-4 mb-0 d-none"
                 role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span id="errorText"></span>
            </div>

            <div class="action-area">
                <button type="button" id="loadStudentsBtn"
                        class="btn btn-primary" disabled>
                    <i class="bi bi-people-fill me-1"></i>
                    Load Students
                </button>
            </div>
        </div>
    </div>

    {{-- STUDENTS PANEL --}}
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-heading-main">
                <div class="section-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h5 class="panel-title">Students</h5>
                    <p class="panel-description">
                        Generate results individually or for the selected class and section.
                    </p>
                </div>
            </div>

            <span id="studentCount" class="student-count">
                <i class="bi bi-people"></i>
                0 Students
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Roll No.</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Marks Status</th>
                        <th class="text-end" style="width:140px;">Action</th>
                    </tr>
                </thead>

                <tbody id="studentsTableBody">
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h6 class="fw-bold">No Students Loaded</h6>
                                <p class="text-muted mb-0">
                                    Select an examination, class and section, then click
                                    <strong>Load Students</strong>.
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="footer-content d-flex justify-content-between align-items-center gap-3">
                <small class="text-muted">
                    <i class="bi bi-info-circle text-primary me-1"></i>
                    Generate results only after confirming the selected examination and class.
                </small>

                <button type="button" id="generateResultBtn"
                        class="btn btn-success" disabled>
                    <i class="bi bi-file-earmark-check me-1"></i>
                    Generate All Results
                </button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const examSelect = document.getElementById('exam_id');
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section');
    const loadStudentsBtn = document.getElementById('loadStudentsBtn');
    const generateResultBtn = document.getElementById('generateResultBtn');
    const studentsTableBody = document.getElementById('studentsTableBody');
    const studentCount = document.getElementById('studentCount');
    const selectionInfo = document.getElementById('selectionInfo');
    const selectionInfoText = document.getElementById('selectionInfoText');
    const loadingMessage = document.getElementById('loadingMessage');
    const loadingText = document.getElementById('loadingText');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');

    let examClasses = [];
    let selectedExamClassId = null;
    let requestVersion = 0;
    let classRequestVersion = 0;
    let loadedStudents = [];

    const urls = {
        examClasses: @json(route('admin.results.exam-classes')),
        loadStudents: @json(route('admin.results.load-students')),
        generateResult: @json(route('admin.results.generate-result'))
    };

    function showLoading(message = 'Loading...') {
        loadingText.textContent = message;
        loadingMessage.classList.remove('d-none');
    }

    function hideLoading() {
        loadingMessage.classList.add('d-none');
    }

    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('d-none');
    }

    function hideError() {
        errorMessage.classList.add('d-none');
        errorText.textContent = '';
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function addHiddenInput(form, name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value ?? '';
        form.appendChild(input);
    }

    function resetStudents(message = 'Select an examination, class and section, then click Load Students.') {
        loadedStudents = [];
        studentCount.innerHTML = '<i class="bi bi-people"></i> 0 Students';
        generateResultBtn.disabled = true;

        studentsTableBody.innerHTML = `
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h6 class="fw-bold">No Students Loaded</h6>
                        <p class="text-muted mb-0">${escapeHtml(message)}</p>
                    </div>
                </td>
            </tr>`;
    }

    function updateSelectionInfo() {
        if (examSelect.value && classSelect.value && sectionSelect.value) {
            const examText =
                examSelect.options[examSelect.selectedIndex]?.text.trim() || '';

            const classText =
                classSelect.options[classSelect.selectedIndex]?.text.trim() || '';

            const sectionText =
                sectionSelect.options[sectionSelect.selectedIndex]?.text.trim() || '';

            selectionInfoText.textContent =
                `${examText} | ${classText} | Section ${sectionText}`;

            selectionInfo.classList.remove('d-none');
        } else {
            selectionInfo.classList.add('d-none');
            selectionInfoText.textContent = '';
        }
    }

    async function fetchJson(url, options = {}) {
        const response = await fetch(url, {
            credentials: 'same-origin',
            ...options,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(options.headers || {})
            }
        });

        let raw = await response.text();

        // Remove a UTF-8 BOM and leading whitespace.
        raw = raw.replace(/^\uFEFF/, '').trim();

        let data;

        try {
            data = JSON.parse(raw);
        } catch (error) {
            console.error('Invalid JSON response:', {
                url,
                status: response.status,
                responsePreview: raw.slice(0, 500)
            });

            if (response.status === 401 || response.status === 419) {
                throw new Error(
                    'Your session may have expired. Refresh the page and try again.'
                );
            }

            throw new Error(
                'The server returned an invalid response. Check the Laravel controller and Network response.'
            );
        }

        if (!response.ok) {
            throw new Error(
                data.message || `Request failed (${response.status}).`
            );
        }

        if (data.success === false) {
            throw new Error(data.message || 'The request could not be completed.');
        }

        return data;
    }

    function setPlaceholder(select, label) {
        select.replaceChildren(new Option(label, ''));
    }

    async function loadClasses() {
        const version = ++classRequestVersion;
        ++requestVersion;

        const examId = examSelect.value;

        setPlaceholder(classSelect, 'Select Class');
        setPlaceholder(sectionSelect, 'Select Section');

        classSelect.disabled = true;
        sectionSelect.disabled = true;
        loadStudentsBtn.disabled = true;

        selectedExamClassId = null;
        examClasses = [];

        hideError();
        resetStudents();
        updateSelectionInfo();

        if (!examId) {
            hideLoading();
            return;
        }

        showLoading('Loading classes...');

        try {
            const url = new URL(urls.examClasses, window.location.origin);
            url.searchParams.set('exam_id', examId);

            console.log('Loading classes for examination:', {
                exam_id: examId
            });

            // Important: do not reference examClassId, classId, or section here.
            const data = await fetchJson(url.toString());

            if (version !== classRequestVersion) return;

            console.log('Exam classes API response:', data);

            examClasses = Array.isArray(data.classes)
                ? data.classes
                : [];

            const uniqueClasses = new Map();

            examClasses.forEach(item => {
                if (
                    item.class_id != null &&
                    !uniqueClasses.has(String(item.class_id))
                ) {
                    uniqueClasses.set(String(item.class_id), item);
                }
            });

            uniqueClasses.forEach(item => {
                classSelect.add(
                    new Option(
                        item.class_name ?? 'Unnamed Class',
                        String(item.class_id)
                    )
                );
            });

            classSelect.disabled = uniqueClasses.size === 0;

            if (uniqueClasses.size === 0) {
                showError('No classes are assigned to this examination.');
            }
        } catch (error) {
            if (version === classRequestVersion) {
                console.error('Load classes error:', error);
                showError(error.message || 'Unable to load classes.');
            }
        } finally {
            if (version === classRequestVersion) {
                hideLoading();
            }
        }
    }

    function loadSections() {
        ++requestVersion;

        setPlaceholder(sectionSelect, 'Select Section');
        sectionSelect.disabled = true;
        loadStudentsBtn.disabled = true;
        selectedExamClassId = null;

        hideError();
        resetStudents();

        const classId = classSelect.value;

        if (!classId) {
            updateSelectionInfo();
            return;
        }

        const matchingClasses = examClasses.filter(
            item => String(item.class_id) === String(classId)
        );

        const seenSections = new Set();

        matchingClasses.forEach(item => {
            const section = String(item.section ?? '').trim();

            if (!section || seenSections.has(section)) return;

            seenSections.add(section);

            const option = new Option(section, section);

            // This value must be returned by the exam-classes endpoint.
            option.dataset.examClassId = item.exam_class_id ?? '';

            sectionSelect.add(option);
        });

        sectionSelect.disabled = seenSections.size === 0;

        if (seenSections.size === 0) {
            showError(
                'No sections are assigned to this class for the selected examination.'
            );
        }

        updateSelectionInfo();
    }

    function handleSectionChange() {
        ++requestVersion;

        const option = sectionSelect.options[sectionSelect.selectedIndex];

        selectedExamClassId = option?.dataset.examClassId || null;

        loadStudentsBtn.disabled = !(
            examSelect.value &&
            classSelect.value &&
            sectionSelect.value &&
            selectedExamClassId
        );

        hideError();
        resetStudents();
        updateSelectionInfo();

        if (
            sectionSelect.value &&
            !selectedExamClassId
        ) {
            showError(
                'The selected section has no exam-class ID. Check the exam-classes API response for exam_class_id.'
            );
        }
    }

    async function loadStudents() {
        const examId = examSelect.value;
        const classId = classSelect.value;
        const section = sectionSelect.value;
        const examClassId = selectedExamClassId;
        const version = ++requestVersion;

        if (!examId || !classId || !section || !examClassId) {
            showError('Please select an examination, class and section.');
            return;
        }

        hideError();
        resetStudents();
        showLoading('Loading students...');
        loadStudentsBtn.disabled = true;

        studentsTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <span class="spinner-border spinner-border-sm text-primary"></span>
                    <div class="mt-2 text-muted">Loading students...</div>
                </td>
            </tr>`;

        const params = {
            exam_id: examId,
            exam_class_id: examClassId,
            class_id: classId,
            section: section
        };

        console.log('Loading students with:', params);

        try {
            const url = new URL(urls.loadStudents, window.location.origin);

            Object.entries(params).forEach(([key, value]) => {
                url.searchParams.set(key, value);
            });

            const data = await fetchJson(url.toString());

            if (version !== requestVersion) return;

            console.log('Students API response:', data);

            if (!Array.isArray(data.students)) {
                console.error('Expected data.students to be an array:', data);

                throw new Error(
                    'The server response does not contain a valid students array.'
                );
            }

            loadedStudents = data.students;

            studentCount.innerHTML =
                `<i class="bi bi-people"></i> ${loadedStudents.length} Student${loadedStudents.length === 1 ? '' : 's'}`;

            if (loadedStudents.length === 0) {
                studentsTableBody.innerHTML = `
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-person-x"></i>
                                </div>
                                <h6 class="fw-bold">No Students Found</h6>
                                <p class="text-muted mb-0">
                                    No students were returned for this class and section.
                                </p>
                            </div>
                        </td>
                    </tr>`;

                generateResultBtn.disabled = true;
                return;
            }

            studentsTableBody.replaceChildren();

            loadedStudents.forEach((student, index) => {
                const fullName = [
                    student.first_name,
                    student.middle_name,
                    student.last_name
                ]
                    .filter(value =>
                        value !== null &&
                        value !== undefined &&
                        String(value).trim() !== ''
                    )
                    .join(' ')
                    .trim() || 'Unnamed Student';

                const initials = fullName
                    .split(/\s+/)
                    .filter(Boolean)
                    .slice(0, 2)
                    .map(part => part.charAt(0))
                    .join('')
                    .toUpperCase() || 'S';

                const row = document.createElement('tr');

                row.innerHTML = `
                    <td class="text-muted">${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="student-avatar">${escapeHtml(initials)}</div>
                            <div>
                                <div class="student-name">${escapeHtml(fullName)}</div>
                                <small class="text-muted">Student</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="student-id">
                            ${escapeHtml(student.student_id ?? '-')}
                        </span>
                    </td>
                    <td>
                        <span class="roll-badge">
                            ${escapeHtml(student.roll_number ?? '-')}
                        </span>
                    </td>
                    <td>${escapeHtml(student.class ?? '-')}</td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            ${escapeHtml(student.section ?? '-')}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary border">
                            Check marks
                        </span>
                    </td>
                    <td class="text-end">
                        <button type="button"
                                class="btn btn-sm btn-primary generate-student-btn"
                                data-student-id="${escapeHtml(student.id ?? '')}"
                                data-student-name="${escapeHtml(fullName)}">
                            <i class="bi bi-file-earmark-check me-1"></i>
                            Generate
                        </button>
                    </td>`;

                studentsTableBody.appendChild(row);
            });

            generateResultBtn.disabled = false;

        } catch (error) {
            if (version !== requestVersion) return;

            console.error('Load students error:', error);

            loadedStudents = [];

            studentsTableBody.innerHTML = `
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon text-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h6 class="fw-bold text-danger">Unable to Load Students</h6>
                            <p class="text-muted mb-0">
                                ${escapeHtml(error.message || 'Unable to load students.')}
                            </p>
                        </div>
                    </td>
                </tr>`;

            studentCount.innerHTML =
                '<i class="bi bi-people"></i> 0 Students';

            generateResultBtn.disabled = true;
            showError(error.message || 'Unable to load students.');

        } finally {
            if (version === requestVersion) {
                loadStudentsBtn.disabled = !(
                    examSelect.value &&
                    classSelect.value &&
                    sectionSelect.value &&
                    selectedExamClassId
                );

                hideLoading();
            }
        }
    }

    function submitGeneration(studentId = null, studentName = null, button = null) {
        const examId = examSelect.value;
        const classId = classSelect.value;
        const section = sectionSelect.value;
        const examClassId = selectedExamClassId;

        if (!examId || !classId || !section || !examClassId) {
            showError('Please select an examination, class and section.');
            return;
        }

        if (
            studentId &&
            !loadedStudents.some(
                student => String(student.id) === String(studentId)
            )
        ) {
            showError(
                'The selected student is no longer in the loaded list. Load students again.'
            );
            return;
        }

        if (!studentId && loadedStudents.length === 0) {
            showError('Load students before generating results.');
            return;
        }

        const confirmation = studentId
            ? `Generate result for ${studentName || 'this student'}?`
            : `Generate results for all ${loadedStudents.length} loaded students in this class and section?`;

        if (!confirm(confirmation)) return;

        if (button) {
            button.disabled = true;
            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';
        } else {
            generateResultBtn.disabled = true;
            generateResultBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';
        }

        showLoading(
            studentId
                ? `Generating result for ${studentName || 'student'}...`
                : 'Submitting result generation request...'
        );

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = urls.generateResult;

        addHiddenInput(form, '_token', @json(csrf_token()));
        addHiddenInput(form, 'exam_id', examId);
        addHiddenInput(form, 'exam_class_id', examClassId);
        addHiddenInput(form, 'class_id', classId);
        addHiddenInput(form, 'section', section);

        if (studentId) {
            addHiddenInput(form, 'student_id', studentId);
        }

        document.body.appendChild(form);
        form.submit();
    }

    examSelect.addEventListener('change', loadClasses);
    classSelect.addEventListener('change', loadSections);
    sectionSelect.addEventListener('change', handleSectionChange);
    loadStudentsBtn.addEventListener('click', loadStudents);

    generateResultBtn.addEventListener('click', function () {
        submitGeneration();
    });

    studentsTableBody.addEventListener('click', function (event) {
        const button = event.target.closest('.generate-student-btn');

        if (!button) return;

        submitGeneration(
            button.dataset.studentId,
            button.dataset.studentName || 'this student',
            button
        );
    });

    updateSelectionInfo();

    // Load classes automatically if an examination is already selected.
    if (examSelect.value) {
        loadClasses();
    }
});
</script>

@endsection
