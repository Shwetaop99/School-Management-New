@extends('layouts.app')

@section('title', 'Distribute Supply Kits')

@push('styles')
<style>
    .distribution-page {
        --primary: #1677f0;
        --primary-dark: #0d5fc7;
        --soft-blue: #eef6ff;
        --border: #e8edf3;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .page-subtitle {
        color: var(--text-muted);
        margin: 0;
    }

    .card {
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
    }

    .card-header {
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-color: #dfe5ec;
        border-radius: 8px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 .2rem rgba(22, 119, 240, .10);
    }

    .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .filter-info {
        background: var(--soft-blue);
        border: 1px solid #d7eaff;
        border-radius: 10px;
        padding: 12px 15px;
        color: #1457a6;
        font-size: 14px;
    }

    .student-count {
        font-size: 14px;
        color: var(--text-muted);
    }

    .student-count strong {
        color: var(--text-dark);
    }

    .table thead th {
        background: #f8fafc;
        color: #4b5563;
        font-size: 13px;
        font-weight: 700;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .table tbody td {
        vertical-align: middle;
        font-size: 14px;
    }

    .student-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .student-id {
        font-size: 12px;
        color: var(--text-muted);
    }

    .already-issued {
        background: #fff7ed;
    }

    .badge-issued {
        background: #fff3cd;
        color: #856404;
        font-size: 11px;
        padding: 5px 8px;
        border-radius: 20px;
    }

    .kit-details {
        background: #f8fbff;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        padding: 16px;
    }

    .kit-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .kit-meta {
        font-size: 13px;
        color: var(--text-muted);
    }

    .summary-box {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 15px;
    }

    .summary-number {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary);
    }

    .summary-label {
        font-size: 12px;
        color: var(--text-muted);
    }

    .empty-state {
        text-align: center;
        padding: 45px 20px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        color: #b7c2d0;
    }

    .loading-box {
        text-align: center;
        padding: 30px;
        color: var(--text-muted);
    }

    .required::after {
        content: " *";
        color: #dc3545;
    }

    .action-bar {
        position: sticky;
        bottom: 15px;
        z-index: 20;
        background: rgba(255, 255, 255, .96);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        padding: 14px 16px;
    }

    @media (max-width: 767px) {
        .page-title {
            font-size: 21px;
        }

        .table {
            min-width: 750px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 distribution-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="page-title">
                <i class="bi bi-box-seam me-2"></i>
                Kit Distribution
            </h3>

            <p class="page-subtitle">
                Select students first, then select the supply kit to distribute.
            </p>
        </div>

        <a href="{{ route('admin.student-supply-kits.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-clock-history me-1"></i>
            Distribution History
        </a>
    </div>


    {{-- =========================================================
        STEP 1 - FILTER STUDENTS
    ========================================================== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-funnel me-2 text-primary"></i>
                1. Select Students
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Academic Year --}}
                <div class="col-md-4">
                    <label for="academicYear" class="form-label required">
                        Academic Year
                    </label>

                    <select id="academicYear"
                            class="form-select">
                        <option value="">Select Academic Year</option>

                        @foreach($academicYears as $year)
                            <option value="{{ $year }}">
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Class --}}
                <div class="col-md-4">
                    <label for="classSelect" class="form-label required">
                        Class
                    </label>

                    <select id="classSelect"
                            class="form-select">
                        <option value="">Select Class</option>

                        @foreach($classes as $class)
                            <option value="{{ $class }}">
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Section --}}
                <div class="col-md-4">
                    <label for="sectionSelect" class="form-label">
                        Section
                    </label>

                    <select id="sectionSelect"
                            class="form-select">
                        <option value="">All Sections</option>

                        @foreach($sections as $section)
                            <option value="{{ $section }}">
                                {{ $section }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>


            <div class="filter-info mt-3">
                <i class="bi bi-info-circle me-1"></i>

                Select an academic year and class to load the students.
                Section is optional.
            </div>


            <div class="mt-3">
                <button type="button"
                        id="loadStudentsBtn"
                        class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>
                    Load Students
                </button>

                <button type="button"
                        id="clearFiltersBtn"
                        class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-x-circle me-1"></i>
                    Clear
                </button>
            </div>

        </div>
    </div>


    {{-- =========================================================
        STEP 2 - STUDENT LIST
    ========================================================== --}}
    <div class="card mb-4 d-none" id="studentsCard">

        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>
                    <h5 class="card-title">
                        <i class="bi bi-people me-2 text-primary"></i>
                        2. Select Students
                    </h5>

                    <div class="student-count mt-1">
                        Students found:
                        <strong id="studentCount">0</strong>
                    </div>
                </div>

                <div class="d-flex gap-2">

                    <button type="button"
                            id="selectAllBtn"
                            class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-check2-square me-1"></i>
                        Select All
                    </button>

                    <button type="button"
                            id="unselectAllBtn"
                            class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-square me-1"></i>
                        Unselect All
                    </button>

                </div>

            </div>
        </div>


        <div class="card-body">

            {{-- Student Search --}}
            <div class="row mb-3">

                <div class="col-md-6">
                    <label for="studentSearch" class="form-label">
                        Search Student
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               id="studentSearch"
                               class="form-control"
                               placeholder="Search by student name or ID...">
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-end justify-content-md-end mt-3 mt-md-0">

                    <div class="summary-box text-center px-4">
                        <div class="summary-number" id="selectedStudentCount">
                            0
                        </div>

                        <div class="summary-label">
                            Selected Students
                        </div>
                    </div>

                </div>

            </div>


            {{-- Loading --}}
            <div id="studentsLoading"
                 class="loading-box d-none">

                <div class="spinner-border text-primary mb-2"
                     role="status">
                </div>

                <div>
                    Loading students...
                </div>
            </div>


            {{-- Empty --}}
            <div id="studentsEmpty"
                 class="empty-state d-none">

                <i class="bi bi-people"></i>

                <h6>No students found</h6>

                <p class="mb-0">
                    No active students match the selected filters.
                </p>

            </div>


            {{-- Table --}}
            <div class="table-responsive d-none"
                 id="studentsTableWrapper">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="masterCheckbox">
                        </th>

                        <th width="60">
                            #
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Student ID
                        </th>

                        <th>
                            Class
                        </th>

                        <th>
                            Section
                        </th>

                        <th>
                            Academic Year
                        </th>

                        <th>
                            Status
                        </th>
                    </tr>
                    </thead>

                    <tbody id="studentsTableBody">
                    </tbody>

                </table>

            </div>

        </div>
    </div>


    {{-- =========================================================
        STEP 3 - SELECT KIT + DISTRIBUTION DETAILS
    ========================================================== --}}
    <div class="card mb-4 d-none" id="distributionCard">

        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-box-seam me-2 text-primary"></i>
                3. Select Kit & Distribution Details
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Kit Selection --}}
                <div class="col-lg-6">

                    <label for="kitTemplateId"
                           class="form-label required">
                        Kit Template
                    </label>

                    <select id="kitTemplateId"
                            class="form-select">

                        <option value="">
                            Select Kit
                        </option>

                        @foreach($kitTemplates as $kit)
                            <option value="{{ $kit->id }}"
                                    data-year="{{ $kit->academic_year }}"
                                    data-class="{{ $kit->class }}"
                                    data-scheme="{{ $kit->scheme?->scheme_name ?? '' }}"
                                    data-description="{{ $kit->description ?? '' }}">

                                {{ $kit->kit_name }}

                                @if($kit->class)
                                    - {{ $kit->class }}
                                @endif

                                @if($kit->academic_year)
                                    - {{ $kit->academic_year }}
                                @endif

                            </option>
                        @endforeach

                    </select>

                    <small class="text-muted">
                        Select the kit after selecting the students.
                    </small>

                </div>


                {{-- Distribution Date --}}
                <div class="col-lg-3">

                    <label for="distributionDate"
                           class="form-label required">
                        Distribution Date
                    </label>

                    <input type="date"
                           id="distributionDate"
                           class="form-control"
                           value="{{ now()->format('Y-m-d') }}">

                </div>


                {{-- Remarks --}}
                <div class="col-lg-3">

                    <label for="remarks"
                           class="form-label">
                        Remarks
                    </label>

                    <input type="text"
                           id="remarks"
                           class="form-control"
                           placeholder="Optional remarks">

                </div>

            </div>


            {{-- Selected Kit Details --}}
            <div class="kit-details mt-4 d-none"
                 id="kitDetailsCard">

                <div class="d-flex flex-wrap justify-content-between gap-3">

                    <div>

                        <div class="kit-name"
                             id="selectedKitName">
                            -
                        </div>

                        <div class="kit-meta mt-1">
                            Scheme:
                            <strong id="selectedKitScheme">-</strong>
                        </div>

                        <div class="kit-meta">
                            Academic Year:
                            <strong id="selectedKitYear">-</strong>
                        </div>

                        <div class="kit-meta">
                            Class:
                            <strong id="selectedKitClass">-</strong>
                        </div>

                    </div>

                    <div class="text-md-end">

                        <div class="kit-meta">
                            Kit Items:
                        </div>

                        <strong id="kitItemCount">
                            0
                        </strong>

                    </div>

                </div>


                <div class="mt-3"
                     id="selectedKitDescriptionWrapper">

                    <div class="kit-meta mb-1">
                        Description
                    </div>

                    <div id="selectedKitDescription">
                        -
                    </div>

                </div>


                <div class="table-responsive mt-3">

                    <table class="table table-sm table-bordered mb-0">

                        <thead>
                        <tr>
                            <th>
                                Item
                            </th>

                            <th width="130">
                                Code
                            </th>

                            <th width="100">
                                Unit
                            </th>

                            <th width="100">
                                Quantity
                            </th>

                            <th>
                                Remarks
                            </th>
                        </tr>
                        </thead>

                        <tbody id="kitItemsBody">
                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
        STEP 4 - SAVE / DISTRIBUTE
    ========================================================== --}}
    <div class="card mb-4 d-none"
         id="actionCard">

        <div class="card-body">

            <div class="action-bar">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <div class="fw-bold">
                            Ready to distribute
                        </div>

                        <div class="text-muted small">
                            Selected students:
                            <strong id="finalSelectedCount">0</strong>
                        </div>

                    </div>

                    <div>

                        <button type="button"
                                id="distributeBtn"
                                class="btn btn-primary px-4">

                            <i class="bi bi-box-seam me-1"></i>
                            Distribute Selected Students

                        </button>

                    </div>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
        HIDDEN DISTRIBUTION FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route('admin.student-supply-kits.distribution.store') }}"
          id="distributionForm"
          class="d-none">

        @csrf

        <input type="hidden"
               name="academic_year"
               id="formAcademicYear">

        <input type="hidden"
               name="class"
               id="formClass">

        <input type="hidden"
               name="section"
               id="formSection">

        <input type="hidden"
               name="kit_template_id"
               id="formKitTemplateId">

        <input type="hidden"
               name="distribution_date"
               id="formDistributionDate">

        <input type="hidden"
               name="remarks"
               id="formRemarks">

        <div id="selectedStudentInputs"></div>

    </form>

</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const academicYear = document.getElementById('academicYear');
    const classSelect = document.getElementById('classSelect');
    const sectionSelect = document.getElementById('sectionSelect');

    const loadStudentsBtn = document.getElementById('loadStudentsBtn');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    const studentsCard = document.getElementById('studentsCard');
    const distributionCard = document.getElementById('distributionCard');
    const actionCard = document.getElementById('actionCard');

    const studentsLoading = document.getElementById('studentsLoading');
    const studentsEmpty = document.getElementById('studentsEmpty');
    const studentsTableWrapper = document.getElementById('studentsTableWrapper');
    const studentsTableBody = document.getElementById('studentsTableBody');

    const studentSearch = document.getElementById('studentSearch');

    const studentCount = document.getElementById('studentCount');
    const selectedStudentCount = document.getElementById('selectedStudentCount');
    const finalSelectedCount = document.getElementById('finalSelectedCount');

    const selectAllBtn = document.getElementById('selectAllBtn');
    const unselectAllBtn = document.getElementById('unselectAllBtn');
    const masterCheckbox = document.getElementById('masterCheckbox');

    const kitTemplateId = document.getElementById('kitTemplateId');

    const distributionDate = document.getElementById('distributionDate');
    const remarks = document.getElementById('remarks');

    const kitDetailsCard = document.getElementById('kitDetailsCard');
    const selectedKitName = document.getElementById('selectedKitName');
    const selectedKitScheme = document.getElementById('selectedKitScheme');
    const selectedKitYear = document.getElementById('selectedKitYear');
    const selectedKitClass = document.getElementById('selectedKitClass');
    const selectedKitDescription = document.getElementById('selectedKitDescription');
    const kitItemCount = document.getElementById('kitItemCount');
    const kitItemsBody = document.getElementById('kitItemsBody');

    const distributeBtn = document.getElementById('distributeBtn');
    const distributionForm = document.getElementById('distributionForm');
    const selectedStudentInputs = document.getElementById('selectedStudentInputs');

    const formAcademicYear = document.getElementById('formAcademicYear');
    const formClass = document.getElementById('formClass');
    const formSection = document.getElementById('formSection');
    const formKitTemplateId = document.getElementById('formKitTemplateId');
    const formDistributionDate = document.getElementById('formDistributionDate');
    const formRemarks = document.getElementById('formRemarks');

    let studentsData = [];
    let currentKitData = null;


    /*
    |--------------------------------------------------------------------------
    | ROUTE
    |--------------------------------------------------------------------------
    */

    const studentsUrl =
        "{{ route('admin.student-supply-kits.distribution.students') }}";


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SELECTED COUNT
    |--------------------------------------------------------------------------
    */

    function updateSelectedCount() {

        const checked = document.querySelectorAll(
            '.student-checkbox:checked:not(:disabled)'
        ).length;

        selectedStudentCount.textContent = checked;
        finalSelectedCount.textContent = checked;

        updateMasterCheckbox();
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE MASTER CHECKBOX
    |--------------------------------------------------------------------------
    */

    function updateMasterCheckbox() {

        const available = Array.from(
            document.querySelectorAll('.student-checkbox:not(:disabled)')
        );

        const checked = available.filter(
            checkbox => checkbox.checked
        );

        if (!available.length) {

            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;

            return;
        }

        masterCheckbox.checked =
            checked.length === available.length;

        masterCheckbox.indeterminate =
            checked.length > 0 &&
            checked.length < available.length;
    }


    /*
    |--------------------------------------------------------------------------
    | RESET STUDENT DISPLAY
    |--------------------------------------------------------------------------
    */

    function resetStudentDisplay() {

        studentsCard.classList.add('d-none');

        distributionCard.classList.add('d-none');

        actionCard.classList.add('d-none');

        studentsLoading.classList.add('d-none');

        studentsEmpty.classList.add('d-none');

        studentsTableWrapper.classList.add('d-none');

        studentsTableBody.innerHTML = '';

        studentCount.textContent = '0';

        studentsData = [];

        currentKitData = null;

        updateSelectedCount();
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER STUDENTS
    |--------------------------------------------------------------------------
    */

    function renderStudents(students) {

        studentsTableBody.innerHTML = '';

        studentCount.textContent = students.length;

        if (!students.length) {

            studentsTableWrapper.classList.add('d-none');

            studentsEmpty.classList.remove('d-none');

            return;
        }

        studentsEmpty.classList.add('d-none');

        studentsTableWrapper.classList.remove('d-none');

        students.forEach(function (student, index) {

            const alreadyIssued =
                student.already_issued === true;

            const row = document.createElement('tr');

            if (alreadyIssued) {
                row.classList.add('already-issued');
            }

            row.innerHTML = `
                <td>
                    <input
                        type="checkbox"
                        class="form-check-input student-checkbox"
                        value="${escapeHtml(student.id)}"
                        data-student-id="${escapeHtml(student.id)}"
                        ${alreadyIssued ? 'disabled' : ''}
                    >
                </td>

                <td>
                    ${index + 1}
                </td>

                <td>
                    <div class="student-name">
                        ${escapeHtml(student.name || '-')}
                    </div>

                    <div class="student-id">
                        ${escapeHtml(student.student_id || '-')}
                    </div>
                </td>

                <td>
                    ${escapeHtml(student.student_id || '-')}
                </td>

                <td>
                    ${escapeHtml(student.class || '-')}
                </td>

                <td>
                    ${escapeHtml(student.section || '-')}
                </td>

                <td>
                    ${escapeHtml(student.academic_year || '-')}
                </td>

                <td>
                    ${
                        alreadyIssued
                        ? `
                            <span class="badge-issued">
                                <i class="bi bi-check-circle me-1"></i>
                                Already Issued
                            </span>
                        `
                        : `
                            <span class="badge bg-success-subtle text-success">
                                Available
                            </span>
                        `
                    }
                </td>
            `;

            studentsTableBody.appendChild(row);
        });


        /*
        |--------------------------------------------------------------------------
        | STUDENT CHECKBOX CHANGE
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.student-checkbox')
            .forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {

                    updateSelectedCount();

                });

            });


        updateSelectedCount();
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS
    |--------------------------------------------------------------------------
    */

    async function loadStudents(includeKit = false) {

        const year = academicYear.value;

        const selectedClass = classSelect.value;

        const section = sectionSelect.value;

        const selectedKit = kitTemplateId.value;


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if (!year) {

            alert('Please select Academic Year.');

            academicYear.focus();

            return;
        }


        if (!selectedClass) {

            alert('Please select Class.');

            classSelect.focus();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | SHOW LOADING
        |--------------------------------------------------------------------------
        */

        studentsCard.classList.remove('d-none');

        studentsLoading.classList.remove('d-none');

        studentsEmpty.classList.add('d-none');

        studentsTableWrapper.classList.add('d-none');

        distributionCard.classList.add('d-none');

        actionCard.classList.add('d-none');

        studentsTableBody.innerHTML = '';

        currentKitData = null;


        try {

            const params = new URLSearchParams();

            params.append(
                'academic_year',
                year
            );

            params.append(
                'class',
                selectedClass
            );


            if (section) {

                params.append(
                    'section',
                    section
                );

            }


            if (includeKit && selectedKit) {

                params.append(
                    'kit_template_id',
                    selectedKit
                );

            }


            const response = await fetch(
                studentsUrl + '?' + params.toString(),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );


            const data = await response.json();


            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Unable to load students.'
                );

            }


            studentsData =
                data.students || [];


            renderStudents(
                studentsData
            );


            /*
            |--------------------------------------------------------------------------
            | KIT INFORMATION
            |--------------------------------------------------------------------------
            */

            if (
                includeKit &&
                selectedKit
            ) {

                currentKitData =
                    data.kit_template || null;

                renderKitDetails(
                    currentKitData
                );

                distributionCard.classList.remove('d-none');

                actionCard.classList.remove('d-none');

            } else {

                distributionCard.classList.remove('d-none');

                actionCard.classList.remove('d-none');

            }


        } catch (error) {

            console.error(error);

            studentsEmpty.classList.remove('d-none');

            studentsEmpty.innerHTML = `
                <i class="bi bi-exclamation-circle text-danger"></i>

                <h6>Unable to load students</h6>

                <p class="mb-0">
                    ${escapeHtml(error.message)}
                </p>
            `;


        } finally {

            studentsLoading.classList.add('d-none');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER KIT DETAILS
    |--------------------------------------------------------------------------
    */

    function renderKitDetails(kit) {

        kitItemsBody.innerHTML = '';


        if (!kit) {

            kitDetailsCard.classList.add('d-none');

            selectedKitName.textContent = '-';

            selectedKitScheme.textContent = '-';

            selectedKitYear.textContent = '-';

            selectedKitClass.textContent = '-';

            selectedKitDescription.textContent = '-';

            kitItemCount.textContent = '0';

            return;
        }


        kitDetailsCard.classList.remove('d-none');


        selectedKitName.textContent =
            kit.kit_name || '-';


        selectedKitScheme.textContent =
            kit.scheme_name || '-';


        selectedKitYear.textContent =
            kit.academic_year || '-';


        selectedKitClass.textContent =
            classSelect.value || '-';


        selectedKitDescription.textContent =
            kit.description || '-';


        const items =
            kit.items || [];


        kitItemCount.textContent =
            items.length;


        if (!items.length) {

            kitItemsBody.innerHTML = `
                <tr>
                    <td
                        colspan="5"
                        class="text-center text-muted py-3"
                    >
                        No items found in this kit.
                    </td>
                </tr>
            `;

            return;
        }


        items.forEach(function (item) {

            const row =
                document.createElement('tr');


            row.innerHTML = `
                <td>
                    <strong>
                        ${escapeHtml(item.item_name || '-')}
                    </strong>
                </td>

                <td>
                    ${escapeHtml(item.item_code || '-')}
                </td>

                <td>
                    ${escapeHtml(item.unit || '-')}
                </td>

                <td>
                    ${escapeHtml(item.quantity ?? 0)}
                </td>

                <td>
                    ${escapeHtml(item.remarks || '-')}
                </td>
            `;


            kitItemsBody.appendChild(row);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER KIT OPTIONS
    |--------------------------------------------------------------------------
    */

    function filterKitOptions() {

        const year =
            academicYear.value;

        const selectedClass =
            classSelect.value;


        Array.from(kitTemplateId.options)
            .forEach(function (option, index) {

                if (index === 0) {
                    return;
                }


                const optionYear =
                    option.dataset.year || '';


                const optionClass =
                    option.dataset.class || '';


                const yearMatch =
                    !year ||
                    optionYear === year;


                const classMatch =
                    !selectedClass ||
                    optionClass === selectedClass;


                option.hidden =
                    !(yearMatch && classMatch);

            });


        /*
        |--------------------------------------------------------------------------
        | RESET INVALID KIT
        |--------------------------------------------------------------------------
        */

        const selectedOption =
            kitTemplateId.options[
                kitTemplateId.selectedIndex
            ];


        if (
            selectedOption &&
            selectedOption.hidden
        ) {

            kitTemplateId.value = '';

            renderKitDetails(null);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | KIT CHANGE
    |--------------------------------------------------------------------------
    */

    kitTemplateId.addEventListener(
        'change',
        async function () {

            const selectedKit =
                this.value;


            renderKitDetails(null);


            if (!selectedKit) {

                actionCard.classList.remove('d-none');

                return;
            }


            if (
                !academicYear.value ||
                !classSelect.value
            ) {

                alert(
                    'Please select Academic Year and Class first.'
                );

                this.value = '';

                return;
            }


            try {

                const params =
                    new URLSearchParams();


                params.append(
                    'academic_year',
                    academicYear.value
                );


                params.append(
                    'class',
                    classSelect.value
                );


                if (sectionSelect.value) {

                    params.append(
                        'section',
                        sectionSelect.value
                    );

                }


                params.append(
                    'kit_template_id',
                    selectedKit
                );


                const response =
                    await fetch(
                        studentsUrl +
                        '?' +
                        params.toString(),
                        {
                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Unable to load kit details.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | Do NOT call renderStudents() here.
                |
                | This prevents the student list from being
                | reloaded when the kit is selected.
                |
                */

                currentKitData =
                    data.kit_template || null;


                renderKitDetails(
                    currentKitData
                );


                distributionCard.classList.remove(
                    'd-none'
                );


                actionCard.classList.remove(
                    'd-none'
                );


                updateSelectedCount();


            } catch (error) {

                console.error(error);


                alert(
                    error.message ||
                    'Unable to load kit details.'
                );


                this.value = '';


                renderKitDetails(null);

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS BUTTON
    |--------------------------------------------------------------------------
    |
    | STUDENTS LOAD ONLY WHEN THIS BUTTON IS CLICKED.
    |--------------------------------------------------------------------------
    */

    loadStudentsBtn.addEventListener(
        'click',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Reset selected kit
            |--------------------------------------------------------------------------
            */

            kitTemplateId.value = '';

            renderKitDetails(null);


            /*
            |--------------------------------------------------------------------------
            | Load students
            |--------------------------------------------------------------------------
            */

            loadStudents(false);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLASS CHANGE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Do NOT automatically load students here.
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        function () {

            kitTemplateId.value = '';

            renderKitDetails(null);

            resetStudentDisplay();

            filterKitOptions();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR CHANGE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Do NOT automatically load students here.
    |--------------------------------------------------------------------------
    */

    academicYear.addEventListener(
        'change',
        function () {

            filterKitOptions();

            kitTemplateId.value = '';

            renderKitDetails(null);

            resetStudentDisplay();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SECTION CHANGE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Do NOT automatically load students here.
    |--------------------------------------------------------------------------
    */

    sectionSelect.addEventListener(
        'change',
        function () {

            kitTemplateId.value = '';

            renderKitDetails(null);

            resetStudentDisplay();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SEARCH STUDENTS
    |--------------------------------------------------------------------------
    */

    studentSearch.addEventListener(
        'input',
        function () {

            const search =
                this.value
                    .toLowerCase()
                    .trim();


            document.querySelectorAll(
                '#studentsTableBody tr'
            ).forEach(function (row) {

                const text =
                    row.textContent
                        .toLowerCase();


                row.style.display =
                    !search ||
                    text.includes(search)
                        ? ''
                        : 'none';

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SELECT ALL
    |--------------------------------------------------------------------------
    */

    selectAllBtn.addEventListener(
        'click',
        function () {

            document.querySelectorAll(
                '.student-checkbox:not(:disabled)'
            ).forEach(function (checkbox) {

                if (
                    checkbox.closest('tr').style.display !==
                    'none'
                ) {

                    checkbox.checked = true;

                }

            });


            updateSelectedCount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | UNSELECT ALL
    |--------------------------------------------------------------------------
    */

    unselectAllBtn.addEventListener(
        'click',
        function () {

            document.querySelectorAll(
                '.student-checkbox:not(:disabled)'
            ).forEach(function (checkbox) {

                checkbox.checked = false;

            });


            updateSelectedCount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | MASTER CHECKBOX
    |--------------------------------------------------------------------------
    */

    masterCheckbox.addEventListener(
        'change',
        function () {

            const checked =
                this.checked;


            document.querySelectorAll(
                '.student-checkbox:not(:disabled)'
            ).forEach(function (checkbox) {

                if (
                    checkbox.closest('tr').style.display !==
                    'none'
                ) {

                    checkbox.checked =
                        checked;

                }

            });


            updateSelectedCount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLEAR FILTERS
    |--------------------------------------------------------------------------
    */

    clearFiltersBtn.addEventListener(
        'click',
        function () {

            academicYear.value = '';

            classSelect.value = '';

            sectionSelect.value = '';

            kitTemplateId.value = '';

            studentSearch.value = '';


            resetStudentDisplay();


            renderKitDetails(null);


            updateSelectedCount();


            filterKitOptions();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DISTRIBUTE BUTTON
    |--------------------------------------------------------------------------
    */

    distributeBtn.addEventListener(
        'click',
        function () {

            const selectedStudents =
                Array.from(
                    document.querySelectorAll(
                        '.student-checkbox:checked:not(:disabled)'
                    )
                ).map(function (checkbox) {

                    return checkbox.value;

                });


            /*
            |--------------------------------------------------------------------------
            | Academic Year
            |--------------------------------------------------------------------------
            */

            if (!academicYear.value) {

                alert(
                    'Please select Academic Year.'
                );

                academicYear.focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Class
            |--------------------------------------------------------------------------
            */

            if (!classSelect.value) {

                alert(
                    'Please select Class.'
                );

                classSelect.focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Students
            |--------------------------------------------------------------------------
            */

            if (!selectedStudents.length) {

                alert(
                    'Please select at least one student.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Kit
            |--------------------------------------------------------------------------
            */

            if (!kitTemplateId.value) {

                alert(
                    'Please select a Kit Template.'
                );

                kitTemplateId.focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Date
            |--------------------------------------------------------------------------
            */

            if (!distributionDate.value) {

                alert(
                    'Please select Distribution Date.'
                );

                distributionDate.focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Confirmation
            |--------------------------------------------------------------------------
            */

            const confirmed =
                confirm(
                    'Are you sure you want to distribute the selected kit to ' +
                    selectedStudents.length +
                    ' student(s)?'
                );


            if (!confirmed) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Set hidden form values
            |--------------------------------------------------------------------------
            */

            formAcademicYear.value =
                academicYear.value;


            formClass.value =
                classSelect.value;


            formSection.value =
                sectionSelect.value;


            formKitTemplateId.value =
                kitTemplateId.value;


            formDistributionDate.value =
                distributionDate.value;


            formRemarks.value =
                remarks.value;


            /*
            |--------------------------------------------------------------------------
            | Clear old student inputs
            |--------------------------------------------------------------------------
            */

            selectedStudentInputs.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | Add selected student IDs
            |--------------------------------------------------------------------------
            */

            selectedStudents.forEach(
                function (studentId) {

                    const input =
                        document.createElement('input');


                    input.type =
                        'hidden';


                    input.name =
                        'student_ids[]';


                    input.value =
                        studentId;


                    selectedStudentInputs.appendChild(
                        input
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Submit
            |--------------------------------------------------------------------------
            */

            distributionForm.submit();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    filterKitOptions();


    /*
    |--------------------------------------------------------------------------
    | DEFAULT DISTRIBUTION DATE
    |--------------------------------------------------------------------------
    */

    if (!distributionDate.value) {

        const today =
            new Date()
                .toISOString()
                .split('T')[0];


        distributionDate.value =
            today;
    }

});
</script>
@endpush