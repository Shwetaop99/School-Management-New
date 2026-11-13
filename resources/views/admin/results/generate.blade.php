
@extends('layouts.app')

@section('title', 'Generate Result')

@section('content')

<style>
    /* =========================================================
       Generate Result Page
    ========================================================= */

    .result-page {
        --primary: #1677f0;
        --primary-dark: #0d5dcc;
        --soft-blue: #eef6ff;
        --border: #e8edf3;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --success: #198754;
        --warning: #f59e0b;
        --danger: #dc3545;
    }

    .result-page .page-hero {
        background: linear-gradient(135deg, #1677f0 0%, #0d5dcc 100%);
        border-radius: 18px;
        padding: 24px 26px;
        color: #fff;
        box-shadow: 0 10px 30px rgba(22, 119, 240, 0.15);
    }

    .result-page .page-hero h4 {
        font-size: 1.35rem;
    }

    .result-page .page-hero .hero-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.16);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    .result-page .hero-subtitle {
        color: rgba(255, 255, 255, 0.82);
        font-size: 0.9rem;
    }

    .result-page .hero-back {
        color: #fff;
        border-color: rgba(255, 255, 255, 0.45);
        background: rgba(255, 255, 255, 0.08);
    }

    .result-page .hero-back:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.16);
        border-color: rgba(255, 255, 255, 0.65);
    }

    .result-page .main-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
    }

    .result-page .card-header-custom {
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: 18px 20px;
    }

    .result-page .card-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--soft-blue);
        color: var(--primary);
        font-size: 1.05rem;
    }

    .result-page .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--soft-blue);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        margin-right: 9px;
        flex-shrink: 0;
    }

    .result-page .form-label {
        color: var(--text-dark);
        font-size: 0.88rem;
        margin-bottom: 8px;
    }

    .result-page .form-select {
        min-height: 48px;
        border-radius: 10px;
        border-color: #dce3eb;
        font-size: 0.92rem;
        padding-left: 14px;
        transition: all 0.2s ease;
    }

    .result-page .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(22, 119, 240, 0.12);
    }

    .result-page .form-select:disabled {
        background-color: #f7f9fc;
        cursor: not-allowed;
    }

    .result-page .selection-summary {
        background: linear-gradient(
            135deg,
            #f0f7ff,
            #f8fbff
        );
        border: 1px solid #d9eaff;
        border-radius: 12px;
        padding: 13px 16px;
    }

    .result-page .selection-summary .summary-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #fff;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 10px rgba(22, 119, 240, 0.08);
    }

    .result-page .action-area {
        border-top: 1px solid var(--border);
        padding-top: 18px;
    }

    .result-page .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 10px 17px;
    }

    .result-page .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
    }

    .result-page .btn-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .result-page .students-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
    }

    .result-page .student-count {
        background: var(--soft-blue);
        color: var(--primary);
        border: 1px solid #d8eaff;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .result-page .table {
        font-size: 0.9rem;
    }

    .result-page .table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
        padding: 14px 15px;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .result-page .table tbody td {
        padding: 15px;
        border-color: #edf1f5;
    }

    .result-page .table tbody tr {
        transition: background 0.15s ease;
    }

    .result-page .table tbody tr:hover {
        background: #f9fbff;
    }

    .result-page .student-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--soft-blue);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 9px;
        flex-shrink: 0;
    }

    .result-page .student-id {
        color: var(--primary);
        font-weight: 700;
        font-size: 0.84rem;
    }

    .result-page .student-name {
        color: var(--text-dark);
        font-weight: 600;
    }

    .result-page .roll-badge {
        background: #f3f4f6;
        color: #4b5563;
        border-radius: 7px;
        padding: 5px 9px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .result-page .marks-badge {
        background: #ecfdf3;
        color: #15803d;
        border: 1px solid #ccefd9;
        border-radius: 8px;
        padding: 6px 9px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .result-page .generate-student-btn {
        white-space: nowrap;
        padding: 7px 12px;
        font-size: 0.8rem;
    }

    .result-page .empty-state {
        padding: 65px 20px;
    }

    .result-page .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
    }

    .result-page .table-footer {
        background: #fff;
        border-top: 1px solid var(--border);
        padding: 17px 20px;
    }

    .result-page .info-alert,
    .result-page .loading-alert,
    .result-page .error-alert {
        border-radius: 11px;
        border: 1px solid transparent;
    }

    .result-page .loading-alert {
        background: #f0f7ff;
        border-color: #d8eaff;
        color: #1456a8;
    }

    .result-page .workflow-line {
        height: 1px;
        background: #dce5ef;
        flex: 1;
        min-width: 20px;
        margin: 0 8px;
    }

    .result-page .workflow-item {
        display: flex;
        align-items: center;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .result-page .workflow-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #cbd5e1;
        margin-right: 6px;
    }

    .result-page .workflow-item.active {
        color: var(--primary);
    }

    .result-page .workflow-item.active .workflow-dot {
        background: var(--primary);
    }

    @media (max-width: 767.98px) {

        .result-page .page-hero {
            padding: 20px;
            border-radius: 14px;
        }

        .result-page .page-hero .hero-actions {
            width: 100%;
            margin-top: 15px;
        }

        .result-page .page-hero .hero-actions .btn {
            width: 100%;
        }

        .result-page .card-header-custom,
        .result-page .students-header {
            padding: 16px;
        }

        .result-page .table-footer {
            padding: 16px;
        }

        .result-page .table-footer .footer-content {
            flex-direction: column;
            align-items: stretch !important;
            gap: 12px;
        }

        .result-page .table-footer .footer-content .btn {
            width: 100%;
        }

        .result-page .workflow-line {
            display: none;
        }

        .result-page .workflow {
            flex-wrap: wrap;
            gap: 10px;
        }
    }
</style>


<div class="container-fluid py-4 result-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="page-hero mb-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">

                <div class="hero-icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>

                <div>

                    <h4 class="fw-bold mb-1">
                        Generate Result
                    </h4>

                    <div class="hero-subtitle">
                        Select examination, class and section
                        to generate student results.
                    </div>

                </div>

            </div>


            <div class="hero-actions">

                <a href="{{ route('admin.results.index') }}"
                   class="btn btn-outline-light hero-back">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back to Results

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show
                    shadow-sm rounded-3 mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show
                    shadow-sm rounded-3 mb-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         SELECTION CARD
    ========================================================== --}}
    <div class="main-card mb-4">

        <div class="card-header-custom">

            <div class="d-flex align-items-center gap-3">

                <div class="card-header-icon">
                    <i class="bi bi-funnel"></i>
                </div>

                <div>

                    <h5 class="fw-bold mb-1">
                        Result Selection
                    </h5>

                    <small class="text-muted">
                        Choose the examination and class
                        before loading students.
                    </small>

                </div>

            </div>

        </div>


        <div class="p-4">

            {{-- Workflow --}}
            <div class="workflow d-flex align-items-center mb-4">

                <div class="workflow-item active">
                    <span class="workflow-dot"></span>
                    Examination
                </div>

                <div class="workflow-line"></div>

                <div class="workflow-item active">
                    <span class="workflow-dot"></span>
                    Class
                </div>

                <div class="workflow-line"></div>

                <div class="workflow-item active">
                    <span class="workflow-dot"></span>
                    Section
                </div>

                <div class="workflow-line"></div>

                <div class="workflow-item">
                    <span class="workflow-dot"></span>
                    Students
                </div>

            </div>


            <div class="row g-4">

                {{-- Examination --}}
                <div class="col-lg-4">

                    <label for="exam_id"
                           class="form-label fw-bold">

                        <span class="step-number">
                            1
                        </span>

                        Examination

                    </label>

                    <select id="exam_id"
                            class="form-select">

                        <option value="">
                            Select Examination
                        </option>

                        @foreach($exams ?? [] as $exam)

                            <option value="{{ $exam->id }}">

                                {{ $exam->exam_name }}

                                @if($exam->academic_year)

                                    - {{ $exam->academic_year }}

                                @endif

                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted d-block mt-2">
                        Select the examination for which
                        results need to be generated.
                    </small>

                </div>


                {{-- Class --}}
                <div class="col-lg-4">

                    <label for="class_id"
                           class="form-label fw-bold">

                        <span class="step-number">
                            2
                        </span>

                        Class

                    </label>

                    <select id="class_id"
                            class="form-select"
                            disabled>

                        <option value="">
                            Select Class
                        </option>

                    </select>

                    <small class="text-muted d-block mt-2">
                        Classes are loaded from the selected examination.
                    </small>

                </div>


                {{-- Section --}}
                <div class="col-lg-4">

                    <label for="section"
                           class="form-label fw-bold">

                        <span class="step-number">
                            3
                        </span>

                        Section

                    </label>

                    <select id="section"
                            class="form-select"
                            disabled>

                        <option value="">
                            Select Section
                        </option>

                    </select>

                    <small class="text-muted d-block mt-2">
                        Select the section to load its students.
                    </small>

                </div>

            </div>


            {{-- Selection Information --}}
            <div id="selectionInfo"
                 class="selection-summary mt-4 d-none">

                <div class="d-flex align-items-center gap-3">

                    <div class="summary-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>

                        <div class="small text-muted mb-1">
                            Current Selection
                        </div>

                        <div id="selectionInfoText"
                             class="fw-semibold text-dark">
                        </div>

                    </div>

                </div>

            </div>


            {{-- Loading --}}
            <div id="loadingMessage"
                 class="alert loading-alert mt-4 mb-0 d-none">

                <span class="spinner-border spinner-border-sm me-2"></span>

                <span id="loadingText">
                    Loading...
                </span>

            </div>


            {{-- Error --}}
            <div id="errorMessage"
                 class="alert alert-danger error-alert mt-4 mb-0 d-none">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                <span id="errorText"></span>

            </div>


            {{-- Load Students --}}
            <div class="action-area mt-4">

                <button type="button"
                        id="loadStudentsBtn"
                        class="btn btn-primary"
                        disabled>

                    <i class="bi bi-people-fill me-1"></i>

                    Load Students

                </button>

            </div>

        </div>

    </div>


    {{-- =========================================================
         STUDENTS CARD
    ========================================================== --}}
    <div class="main-card">

        <div class="students-header">

            <div class="d-flex flex-wrap
                        justify-content-between
                        align-items-center gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="card-header-icon">

                        <i class="bi bi-people-fill"></i>

                    </div>

                    <div>

                        <h5 class="mb-1 fw-bold">
                            Students
                        </h5>

                        <small class="text-muted">
                            Generate the result individually
                            or generate results for all students.
                        </small>

                    </div>

                </div>


                <span id="studentCount"
                      class="student-count">

                    <i class="bi bi-people me-1"></i>

                    0 Students

                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th style="width:65px;">
                            #
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Student ID
                        </th>

                        <th>
                            Roll No.
                        </th>

                        <th>
                            Class
                        </th>

                        <th>
                            Section
                        </th>

                        <th>
                            Marks Status
                        </th>

                        <th class="text-end"
                            style="width:150px;">

                            Action

                        </th>

                    </tr>

                </thead>


                <tbody id="studentsTableBody">

                    <tr>

                        <td colspan="8">

                            <div class="empty-state text-center">

                                <div class="empty-state-icon mb-3">

                                    <i class="bi bi-people"></i>

                                </div>

                                <h6 class="fw-bold text-dark">
                                    No Students Loaded
                                </h6>

                                <p class="text-muted mb-0">
                                    Select examination, class and section,
                                    then click <strong>Load Students</strong>.
                                </p>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- Footer --}}
        <div class="table-footer">

            <div class="footer-content
                        d-flex
                        justify-content-between
                        align-items-center
                        gap-3">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-info-circle text-primary"></i>

                    <small class="text-muted">

                        Use <strong>Generate</strong> to create
                        an individual student's result.

                    </small>

                </div>


                <button type="button"
                        id="generateResultBtn"
                        class="btn btn-success"
                        disabled>

                    <i class="bi bi-file-earmark-check me-1"></i>

                    Generate All Results

                </button>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const examSelect =
        document.getElementById('exam_id');

    const classSelect =
        document.getElementById('class_id');

    const sectionSelect =
        document.getElementById('section');

    const loadStudentsBtn =
        document.getElementById('loadStudentsBtn');

    const generateResultBtn =
        document.getElementById('generateResultBtn');

    const studentsTableBody =
        document.getElementById('studentsTableBody');

    const studentCount =
        document.getElementById('studentCount');

    const selectionInfo =
        document.getElementById('selectionInfo');

    const selectionInfoText =
        document.getElementById('selectionInfoText');

    const loadingMessage =
        document.getElementById('loadingMessage');

    const loadingText =
        document.getElementById('loadingText');

    const errorMessage =
        document.getElementById('errorMessage');

    const errorText =
        document.getElementById('errorText');


    let examClasses = [];

    let selectedExamClassId = null;


    /* =========================================================
       Utility
    ========================================================== */

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

    }


    function updateSelectionInfo() {

        const examText =
            examSelect.options[
                examSelect.selectedIndex
            ]?.text || '';

        const classText =
            classSelect.options[
                classSelect.selectedIndex
            ]?.text || '';

        const sectionText =
            sectionSelect.options[
                sectionSelect.selectedIndex
            ]?.text || '';


        if (
            examSelect.value &&
            classSelect.value &&
            sectionSelect.value
        ) {

            selectionInfoText.textContent =
                `${examText} | ${classText} | Section ${sectionText}`;

            selectionInfo.classList.remove('d-none');

        } else {

            selectionInfo.classList.add('d-none');

        }

    }


    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    function addHiddenInput(
        form,
        name,
        value
    ) {

        const input =
            document.createElement('input');

        input.type = 'hidden';

        input.name = name;

        input.value = value ?? '';

        form.appendChild(input);

    }


    /* =========================================================
       Load Examination Classes
    ========================================================== */

    async function loadClasses() {

        const examId =
            examSelect.value;

        classSelect.innerHTML =
            '<option value="">Select Class</option>';

        sectionSelect.innerHTML =
            '<option value="">Select Section</option>';

        classSelect.disabled = true;

        sectionSelect.disabled = true;

        loadStudentsBtn.disabled = true;

        generateResultBtn.disabled = true;

        selectedExamClassId = null;

        examClasses = [];

        hideError();

        if (!examId) {

            return;

        }


        showLoading('Loading classes...');


        try {

            const url =
                `{{ route('admin.results.exam-classes') }}` +
                `?exam_id=${encodeURIComponent(examId)}`;


            const response =
                await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });


            const data =
                await response.json();


            if (!response.ok || !data.success) {

                throw new Error(
                    data.message ||
                    'Unable to load classes.'
                );

            }


            examClasses =
                data.classes || [];


            const uniqueClasses =
                new Map();


            examClasses.forEach(function (item) {

                if (
                    !uniqueClasses.has(
                        String(item.class_id)
                    )
                ) {

                    uniqueClasses.set(
                        String(item.class_id),
                        item
                    );

                }

            });


            uniqueClasses.forEach(function (item) {

                const option =
                    document.createElement('option');

                option.value =
                    item.class_id;

                option.textContent =
                    item.class_name;

                classSelect.appendChild(option);

            });


            classSelect.disabled =
                examClasses.length === 0;


            if (examClasses.length === 0) {

                showError(
                    'No classes are assigned to this examination.'
                );

            }

        } catch (error) {

            showError(
                error.message ||
                'Unable to load classes.'
            );

        } finally {

            hideLoading();

        }

    }


    /* =========================================================
       Load Sections
    ========================================================== */

    function loadSections() {

        const classId =
            classSelect.value;


        sectionSelect.innerHTML =
            '<option value="">Select Section</option>';

        sectionSelect.disabled = true;

        loadStudentsBtn.disabled = true;

        generateResultBtn.disabled = true;

        selectedExamClassId = null;


        if (!classId) {

            updateSelectionInfo();

            return;

        }


        const matchingClasses =
            examClasses.filter(function (item) {

                return String(item.class_id) ===
                    String(classId);

            });


        matchingClasses.forEach(function (item) {

            const option =
                document.createElement('option');

            option.value =
                item.section;

            option.textContent =
                item.section;

            option.dataset.examClassId =
                item.exam_class_id;

            sectionSelect.appendChild(option);

        });


        sectionSelect.disabled =
            matchingClasses.length === 0;


        if (matchingClasses.length === 0) {

            showError(
                'No section is assigned to this class for the selected examination.'
            );

        }


        updateSelectionInfo();

    }


    /* =========================================================
       Section Change
    ========================================================== */

    function handleSectionChange() {

        const selectedOption =
            sectionSelect.options[
                sectionSelect.selectedIndex
            ];


        selectedExamClassId =
            selectedOption?.dataset.examClassId ||
            null;


        loadStudentsBtn.disabled =
            !(
                examSelect.value &&
                classSelect.value &&
                sectionSelect.value &&
                selectedExamClassId
            );


        generateResultBtn.disabled =
            true;


        updateSelectionInfo();

    }


    /* =========================================================
       Load Students
    ========================================================== */

    async function loadStudents() {

        const examId =
            examSelect.value;

        const classId =
            classSelect.value;

        const section =
            sectionSelect.value;

        const examClassId =
            selectedExamClassId;


        if (
            !examId ||
            !classId ||
            !section ||
            !examClassId
        ) {

            showError(
                'Please select examination, class and section.'
            );

            return;

        }


        hideError();

        showLoading('Loading students...');


        studentsTableBody.innerHTML = `
            <tr>
                <td colspan="8"
                    class="text-center py-5">

                    <span class="spinner-border text-primary"></span>

                    <div class="mt-3 text-muted">
                        Loading students...
                    </div>

                </td>
            </tr>
        `;


        try {

            const url =
                `{{ route('admin.results.load-students') }}` +
                `?exam_id=${encodeURIComponent(examId)}` +
                `&exam_class_id=${encodeURIComponent(examClassId)}` +
                `&class_id=${encodeURIComponent(classId)}` +
                `&section=${encodeURIComponent(section)}`;


            const response =
                await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });


            const data =
                await response.json();


            if (!response.ok || !data.success) {

                throw new Error(
                    data.message ||
                    'Unable to load students.'
                );

            }


            const students =
                data.students || [];


            studentCount.innerHTML =
                `<i class="bi bi-people me-1"></i>
                 ${students.length}
                 Student${students.length === 1 ? '' : 's'}`;


            if (students.length === 0) {

                studentsTableBody.innerHTML = `
                    <tr>
                        <td colspan="8">

                            <div class="empty-state text-center">

                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-person-x"></i>
                                </div>

                                <h6 class="fw-bold text-dark">
                                    No Active Students Found
                                </h6>

                                <p class="text-muted mb-0">
                                    No active students were found
                                    for this class and section.
                                </p>

                            </div>

                        </td>
                    </tr>
                `;

                generateResultBtn.disabled = true;

                return;

            }


            studentsTableBody.innerHTML = '';


            students.forEach(function (student, index) {

                const fullName =
                    [
                        student.first_name,
                        student.middle_name,
                        student.last_name
                    ]
                    .filter(Boolean)
                    .join(' ');


                const initials =
                    fullName
                        .split(' ')
                        .filter(Boolean)
                        .slice(0, 2)
                        .map(
                            name => name.charAt(0)
                        )
                        .join('')
                        .toUpperCase() || 'S';


                const row =
                    document.createElement('tr');


                row.innerHTML = `

                    <td class="text-muted fw-semibold">
                        ${index + 1}
                    </td>


                    <td>

                        <div class="d-flex align-items-center">

                            <div class="student-avatar">
                                ${escapeHtml(initials)}
                            </div>

                            <div>

                                <div class="student-name">
                                    ${escapeHtml(
                                        fullName || '-'
                                    )}
                                </div>

                                <div class="small text-muted">
                                    Student
                                </div>

                            </div>

                        </div>

                    </td>


                    <td>

                        <span class="student-id">

                            ${escapeHtml(
                                student.student_id || '-'
                            )}

                        </span>

                    </td>


                    <td>

                        <span class="roll-badge">

                            ${escapeHtml(
                                student.roll_number || '-'
                            )}

                        </span>

                    </td>


                    <td>

                        ${escapeHtml(
                            student.class || '-'
                        )}

                    </td>


                    <td>

                        <span class="badge bg-light text-dark border">

                            ${escapeHtml(
                                student.section || '-'
                            )}

                        </span>

                    </td>


                    <td>

                        <span class="marks-badge">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Marks Available

                        </span>

                    </td>


                    <td class="text-end">

                        <button
                            type="button"
                            class="btn btn-sm btn-primary
                                   generate-student-btn"
                            data-student-id="${student.id}"
                            data-student-name="${escapeHtml(fullName)}"
                        >

                            <i class="bi bi-file-earmark-check me-1"></i>

                            Generate

                        </button>

                    </td>

                `;


                studentsTableBody.appendChild(row);

            });


            generateResultBtn.disabled = false;


        } catch (error) {

            studentsTableBody.innerHTML = `
                <tr>
                    <td colspan="8">

                        <div class="empty-state text-center">

                            <div class="empty-state-icon mb-3
                                        text-danger bg-danger-subtle">

                                <i class="bi bi-exclamation-triangle"></i>

                            </div>

                            <h6 class="fw-bold text-danger">
                                Unable to Load Students
                            </h6>

                            <p class="text-muted mb-0">
                                ${escapeHtml(
                                    error.message ||
                                    'Unable to load students.'
                                )}
                            </p>

                        </div>

                    </td>
                </tr>
            `;


            studentCount.innerHTML =
                '<i class="bi bi-people me-1"></i> 0 Students';


            generateResultBtn.disabled =
                true;


            showError(
                error.message ||
                'Unable to load students.'
            );

        } finally {

            hideLoading();

        }

    }


    /* =========================================================
       Generate Individual Student Result
    ========================================================== */

    function generateStudentResult(
        studentId,
        studentName,
        button
    ) {

        const examId =
            examSelect.value;

        const classId =
            classSelect.value;

        const section =
            sectionSelect.value;

        const examClassId =
            selectedExamClassId;


        if (
            !examId ||
            !classId ||
            !section ||
            !examClassId ||
            !studentId
        ) {

            showError(
                'Required examination, class, section or student information is missing.'
            );

            return;

        }


        if (
            !confirm(
                'Generate result for ' +
                studentName +
                '?'
            )
        ) {

            return;

        }


        button.disabled = true;

        button.innerHTML = `
            <span class="spinner-border
                         spinner-border-sm me-1"></span>
            Generating...
        `;


        showLoading(
            'Generating result for ' +
            studentName +
            '...'
        );


        const form =
            document.createElement('form');

        form.method =
            'POST';

        form.action =
            `{{ route('admin.results.generate-result') }}`;


        addHiddenInput(
            form,
            '_token',
            '{{ csrf_token() }}'
        );


        addHiddenInput(
            form,
            'exam_id',
            examId
        );


        addHiddenInput(
            form,
            'exam_class_id',
            examClassId
        );


        addHiddenInput(
            form,
            'class_id',
            classId
        );


        addHiddenInput(
            form,
            'section',
            section
        );


        addHiddenInput(
            form,
            'student_id',
            studentId
        );


        document.body.appendChild(form);

        form.submit();

    }


    /* =========================================================
       Generate All Results
    ========================================================== */

    function generateResult() {

        const examId =
            examSelect.value;

        const classId =
            classSelect.value;

        const section =
            sectionSelect.value;

        const examClassId =
            selectedExamClassId;


        if (
            !examId ||
            !classId ||
            !section ||
            !examClassId
        ) {

            showError(
                'Please select examination, class and section.'
            );

            return;

        }


        if (
            !confirm(
                'Generate results for all students in this class and section?'
            )
        ) {

            return;

        }


        generateResultBtn.disabled =
            true;


        generateResultBtn.innerHTML = `
            <span class="spinner-border
                         spinner-border-sm me-1"></span>
            Generating...
        `;


        const form =
            document.createElement('form');

        form.method =
            'POST';

        form.action =
            `{{ route('admin.results.generate-result') }}`;


        addHiddenInput(
            form,
            '_token',
            '{{ csrf_token() }}'
        );


        addHiddenInput(
            form,
            'exam_id',
            examId
        );


        addHiddenInput(
            form,
            'exam_class_id',
            examClassId
        );


        addHiddenInput(
            form,
            'class_id',
            classId
        );


        addHiddenInput(
            form,
            'section',
            section
        );


        document.body.appendChild(form);

        form.submit();

    }


    /* =========================================================
       Events
    ========================================================== */

    examSelect.addEventListener(
        'change',
        loadClasses
    );


    classSelect.addEventListener(
        'change',
        loadSections
    );


    sectionSelect.addEventListener(
        'change',
        handleSectionChange
    );


    loadStudentsBtn.addEventListener(
        'click',
        loadStudents
    );


    generateResultBtn.addEventListener(
        'click',
        generateResult
    );


    /* =========================================================
       Dynamic Student Generate Buttons
    ========================================================== */

    studentsTableBody.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '.generate-student-btn'
                );


            if (!button) {

                return;

            }


            const studentId =
                button.dataset.studentId;


            const studentName =
                button.dataset.studentName ||
                'this student';


            generateStudentResult(
                studentId,
                studentName,
                button
            );

        }
    );


    /* =========================================================
       Initial Load
    ========================================================== */

    if (examSelect.value) {

        loadClasses();

    }

});

</script>

@endsection
