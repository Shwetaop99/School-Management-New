```blade
@extends('layouts.app')

@section('title', 'Student Kit Distribution')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span
                    class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                    style="width:42px;height:42px;"
                >
                    <i class="bi bi-box-seam-fill fs-5"></i>
                </span>

                <div>
                    <h3 class="fw-bold mb-0">
                        Student Kit Distribution
                    </h3>

                    <div class="text-muted small">
                        Distribute government supplied kits to students
                    </div>
                </div>
            </div>
        </div>

        <a
            href="{{ route('admin.student-supply-kits.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Distribution
        </a>

    </div>


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if ($errors->any())

        <div class="alert alert-danger border-0 shadow-sm mb-4">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-4">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.student-supply-kits.store') }}"
        id="distributionForm"
    >

        @csrf


        {{-- =====================================================
            STUDENT & DISTRIBUTION INFORMATION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center gap-2">

                    <span class="text-primary">
                        <i class="bi bi-person-vcard-fill fs-5"></i>
                    </span>

                    <div>
                        <h5 class="fw-bold mb-0">
                            Student & Distribution Information
                        </h5>

                        <small class="text-muted">
                            Select the student who will receive the government kit
                        </small>
                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <div class="row g-4">

                    {{-- =================================================
                        STUDENT SEARCH
                    ================================================== --}}
                    <div class="col-lg-7">

                        <label class="form-label fw-semibold">
                            Student <span class="text-danger">*</span>
                        </label>

                        <div class="position-relative">

                            <input
                                type="text"
                                class="form-control form-control-lg"
                                id="student_search"
                                placeholder="Search by Student ID or Student Name..."
                                autocomplete="off"
                            >

                            <input
                                type="hidden"
                                name="student_id"
                                id="student_id"
                                value="{{ old('student_id') }}"
                            >


                            {{-- Search results --}}
                            <div
                                id="studentResults"
                                class="position-absolute bg-white border rounded shadow-sm w-100 mt-1"
                                style="
                                    display:none;
                                    z-index:1050;
                                    max-height:300px;
                                    overflow-y:auto;
                                "
                            ></div>

                        </div>


                        {{-- Selected student --}}
                        <div
                            id="selectedStudent"
                            class="mt-3"
                            style="display:none;"
                        >

                            <div class="alert alert-primary border-0 mb-0">

                                <div class="d-flex justify-content-between align-items-start gap-3">

                                    <div>

                                        <div class="fw-bold" id="selectedStudentName"></div>

                                        <div class="small mt-1">
                                            Student ID:
                                            <strong id="selectedStudentId"></strong>
                                        </div>

                                        <div class="small">
                                            Class:
                                            <strong id="selectedStudentClass"></strong>
                                            <span id="selectedStudentSectionWrapper">
                                                &nbsp; | &nbsp;
                                                Section:
                                                <strong id="selectedStudentSection"></strong>
                                            </span>
                                        </div>

                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        id="changeStudentBtn"
                                    >
                                        <i class="bi bi-pencil me-1"></i>
                                        Change
                                    </button>

                                </div>

                            </div>

                        </div>

                        <div class="form-text">
                            Search using at least 2 characters.
                        </div>

                    </div>


                    {{-- =================================================
                        ACADEMIC YEAR
                    ================================================== --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Academic Year <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            id="academic_year"
                            class="form-control"
                            value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                            placeholder="Example: 2026-27"
                            required
                        >

                    </div>


                    {{-- =================================================
                        GOVERNMENT KIT
                    ================================================== --}}
                    <div class="col-lg-7">

                        <label class="form-label fw-semibold">
                            Government Kit <span class="text-danger">*</span>
                        </label>

                        <select
                            name="kit_template_id"
                            id="kit_template_id"
                            class="form-select form-select-lg"
                            required
                        >

                            <option value="">
                                Select Government Kit
                            </option>

                            @foreach ($kitTemplates as $kitTemplate)

                                <option
                                    value="{{ $kitTemplate->id }}"
                                    data-class="{{ $kitTemplate->class }}"
                                    data-academic-year="{{ $kitTemplate->academic_year }}"
                                    {{ old('kit_template_id') == $kitTemplate->id ? 'selected' : '' }}
                                >
                                    {{ $kitTemplate->kit_name }}
                                    @if($kitTemplate->class)
                                        — Class {{ $kitTemplate->class }}
                                    @endif
                                    @if($kitTemplate->academic_year)
                                        — {{ $kitTemplate->academic_year }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Select the government-defined kit to be distributed.
                        </div>

                    </div>


                    {{-- =================================================
                        ISSUE DATE
                    ================================================== --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Distribution Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            class="form-control"
                            value="{{ old('issue_date', date('Y-m-d')) }}"
                            required
                        >

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Distribution Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="issued"
                                {{ old('status', 'issued') === 'issued' ? 'selected' : '' }}
                            >
                                Issued
                            </option>

                            <option
                                value="pending"
                                {{ old('status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="cancelled"
                                {{ old('status') === 'cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        SELECTED KIT INFORMATION
                    ================================================== --}}
                    <div class="col-lg-7">

                        <div
                            id="kitInformation"
                            class="alert alert-light border mb-0"
                            style="display:none;"
                        >

                            <div class="fw-bold mb-1">
                                <i class="bi bi-info-circle-fill text-primary me-1"></i>
                                Government Kit Information
                            </div>

                            <div class="small text-muted">

                                <div>
                                    Kit:
                                    <strong id="kitInfoName"></strong>
                                </div>

                                <div>
                                    Applicable Class:
                                    <strong id="kitInfoClass"></strong>
                                </div>

                                <div>
                                    Academic Year:
                                    <strong id="kitInfoYear"></strong>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        REMARKS
                    ================================================== --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="3"
                            placeholder="Enter any remarks about this distribution..."
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            GOVERNMENT KIT ITEMS
        ========================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-boxes text-primary me-2"></i>
                            Distribution Items
                        </h5>

                        <small class="text-muted">
                            Items and quantities provided to this student
                        </small>

                    </div>

                    <span
                        id="stockStatusBadge"
                        class="badge bg-secondary"
                    >
                        Select a Government Kit
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                {{-- Empty state --}}
                <div
                    id="itemsEmptyState"
                    class="text-center py-5 px-3"
                >

                    <div
                        class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-secondary mb-3"
                        style="width:70px;height:70px;"
                    >
                        <i class="bi bi-box-seam fs-2"></i>
                    </div>

                    <h6 class="fw-bold">
                        No Government Kit Selected
                    </h6>

                    <p class="text-muted mb-0">
                        Select a government kit above to load its distribution items.
                    </p>

                </div>


                {{-- Loading state --}}
                <div
                    id="itemsLoadingState"
                    class="text-center py-5"
                    style="display:none;"
                >

                    <div
                        class="spinner-border text-primary mb-3"
                        role="status"
                    ></div>

                    <div class="text-muted">
                        Loading government kit items...
                    </div>

                </div>


                {{-- Items table --}}
                <div
                    id="itemsTableWrapper"
                    class="table-responsive"
                    style="display:none;"
                >

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th
                                    class="ps-4"
                                    style="width:60px;"
                                >
                                    #
                                </th>

                                <th>
                                    Item
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th
                                    class="text-center"
                                    style="width:180px;"
                                >
                                    Kit Qty / Student
                                </th>

                                <th
                                    class="text-center"
                                    style="width:170px;"
                                >
                                    Available Stock
                                </th>

                                <th
                                    class="text-center"
                                    style="width:170px;"
                                >
                                    Issue Qty
                                </th>

                                <th
                                    class="text-center pe-4"
                                    style="width:130px;"
                                >
                                    Stock Status
                                </th>

                            </tr>

                        </thead>

                        <tbody id="kitItemsBody"></tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =========================================================
            DISTRIBUTION SUMMARY
        ========================================================== --}}
        <div
            id="distributionSummary"
            class="card border-0 shadow-sm mb-4"
            style="display:none;"
        >

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="small text-muted">
                                Total Item Types
                            </div>

                            <div
                                class="fs-4 fw-bold"
                                id="summaryItemTypes"
                            >
                                0
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="small text-muted">
                                Total Quantity
                            </div>

                            <div
                                class="fs-4 fw-bold"
                                id="summaryQuantity"
                            >
                                0
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="small text-muted">
                                Stock Check
                            </div>

                            <div
                                class="fs-5 fw-bold text-success"
                                id="summaryStock"
                            >
                                Ready
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            FORM ACTIONS
        ========================================================== --}}
        <div class="d-flex justify-content-between align-items-center gap-2">

            <a
                href="{{ route('admin.student-supply-kits.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-x-lg me-1"></i>
                Cancel
            </a>


            <button
                type="submit"
                id="submitBtn"
                class="btn btn-primary px-4"
            >

                <i class="bi bi-check-circle-fill me-1"></i>

                Issue Government Kit

            </button>

        </div>

    </form>

</div>


{{-- ================================================================
    STYLES
================================================================ --}}
<style>

    #studentResults .student-result {
        padding: 12px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }

    #studentResults .student-result:last-child {
        border-bottom: 0;
    }

    #studentResults .student-result:hover {
        background: #f5f8ff;
    }

    .distribution-item-row.stock-danger {
        background: rgba(220, 53, 69, 0.04);
    }

    .distribution-item-row.stock-ok {
        background: rgba(25, 135, 84, 0.03);
    }

    .stock-badge {
        min-width: 95px;
    }

    .quantity-input {
        max-width: 110px;
        margin: auto;
    }

</style>


{{-- ================================================================
    JAVASCRIPT
================================================================ --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const studentSearch =
        document.getElementById('student_search');

    const studentResults =
        document.getElementById('studentResults');

    const studentId =
        document.getElementById('student_id');

    const selectedStudent =
        document.getElementById('selectedStudent');

    const selectedStudentName =
        document.getElementById('selectedStudentName');

    const selectedStudentId =
        document.getElementById('selectedStudentId');

    const selectedStudentClass =
        document.getElementById('selectedStudentClass');

    const selectedStudentSection =
        document.getElementById('selectedStudentSection');

    const changeStudentBtn =
        document.getElementById('changeStudentBtn');


    const kitTemplate =
        document.getElementById('kit_template_id');

    const academicYear =
        document.getElementById('academic_year');

    const kitInformation =
        document.getElementById('kitInformation');

    const kitInfoName =
        document.getElementById('kitInfoName');

    const kitInfoClass =
        document.getElementById('kitInfoClass');

    const kitInfoYear =
        document.getElementById('kitInfoYear');


    const itemsEmptyState =
        document.getElementById('itemsEmptyState');

    const itemsLoadingState =
        document.getElementById('itemsLoadingState');

    const itemsTableWrapper =
        document.getElementById('itemsTableWrapper');

    const kitItemsBody =
        document.getElementById('kitItemsBody');

    const distributionSummary =
        document.getElementById('distributionSummary');

    const summaryItemTypes =
        document.getElementById('summaryItemTypes');

    const summaryQuantity =
        document.getElementById('summaryQuantity');

    const summaryStock =
        document.getElementById('summaryStock');

    const stockStatusBadge =
        document.getElementById('stockStatusBadge');

    const distributionForm =
        document.getElementById('distributionForm');

    const submitBtn =
        document.getElementById('submitBtn');


    let studentSearchTimer = null;


    /* ============================================================
       STUDENT SEARCH
    ============================================================ */

    studentSearch.addEventListener('input', function () {

        const search = this.value.trim();

        clearTimeout(studentSearchTimer);

        if (search.length < 2) {

            studentResults.style.display = 'none';
            studentResults.innerHTML = '';

            return;
        }


        studentSearchTimer = setTimeout(function () {

            fetch(
                "{{ route('admin.student-supply-kits.students.search') }}"
                + "?search="
                + encodeURIComponent(search)
            )
                .then(response => {

                    if (!response.ok) {
                        throw new Error('Unable to search students.');
                    }

                    return response.json();

                })
                .then(students => {

                    studentResults.innerHTML = '';


                    if (!students.length) {

                        studentResults.innerHTML = `
                            <div class="p-3 text-muted text-center">
                                <i class="bi bi-person-x me-1"></i>
                                No students found.
                            </div>
                        `;

                        studentResults.style.display = 'block';

                        return;
                    }


                    students.forEach(student => {

                        const fullName = [
                            student.first_name,
                            student.middle_name,
                            student.last_name
                        ]
                            .filter(Boolean)
                            .join(' ');


                        const result = document.createElement('div');

                        result.className =
                            'student-result';


                        result.innerHTML = `

                            <div class="fw-semibold">
                                ${escapeHtml(fullName)}
                            </div>

                            <div class="small text-muted">

                                Student ID:
                                <strong>
                                    ${escapeHtml(student.student_id ?? '')}
                                </strong>

                                ${student.class ? `
                                    &nbsp; | &nbsp;
                                    Class:
                                    ${escapeHtml(student.class)}
                                ` : ''}

                                ${student.section ? `
                                    &nbsp; | &nbsp;
                                    Section:
                                    ${escapeHtml(student.section)}
                                ` : ''}

                            </div>
                        `;


                        result.addEventListener(
                            'click',
                            function () {

                                selectStudent(student, fullName);

                            }
                        );


                        studentResults.appendChild(result);

                    });


                    studentResults.style.display = 'block';

                })
                .catch(error => {

                    console.error(error);

                    studentResults.innerHTML = `
                        <div class="p-3 text-danger text-center">
                            Unable to search students.
                        </div>
                    `;

                    studentResults.style.display = 'block';

                });

        }, 300);

    });


    /* ============================================================
       SELECT STUDENT
    ============================================================ */

    function selectStudent(student, fullName) {

        studentId.value = student.id;

        studentSearch.value = fullName;

        selectedStudentName.textContent =
            fullName;

        selectedStudentId.textContent =
            student.student_id ?? '-';

        selectedStudentClass.textContent =
            student.class ?? '-';

        selectedStudentSection.textContent =
            student.section ?? '-';


        selectedStudent.style.display =
            'block';

        studentResults.style.display =
            'none';

        studentResults.innerHTML = '';

        studentSearch.readOnly = true;

    }


    /* ============================================================
       CHANGE STUDENT
    ============================================================ */

    changeStudentBtn.addEventListener(
        'click',
        function () {

            studentId.value = '';

            studentSearch.value = '';

            studentSearch.readOnly = false;

            selectedStudent.style.display =
                'none';

            studentSearch.focus();

        }
    );


    /* ============================================================
       GOVERNMENT KIT CHANGE
    ============================================================ */

    kitTemplate.addEventListener(
        'change',
        function () {

            const kitId = this.value;

            resetItems();


            if (!kitId) {

                return;

            }


            const selectedOption =
                this.options[this.selectedIndex];


            kitInfoName.textContent =
                selectedOption.textContent.trim();

            kitInfoClass.textContent =
                selectedOption.dataset.class || '-';

            kitInfoYear.textContent =
                selectedOption.dataset.academicYear || '-';

            kitInformation.style.display =
                'block';


            if (
                selectedOption.dataset.academicYear
                && !academicYear.value
            ) {

                academicYear.value =
                    selectedOption.dataset.academicYear;

            }


            itemsEmptyState.style.display =
                'none';

            itemsLoadingState.style.display =
                'block';


            fetch(
                "{{ route('admin.student-supply-kits.template.items') }}"
                + "?kit_template_id="
                + encodeURIComponent(kitId)
            )
                .then(response => {

                    if (!response.ok) {
                        throw new Error(
                            'Unable to load government kit items.'
                        );
                    }

                    return response.json();

                })
                .then(data => {

                    renderKitItems(
                        data.items || []
                    );

                })
                .catch(error => {

                    console.error(error);

                    itemsLoadingState.style.display =
                        'none';

                    itemsEmptyState.innerHTML = `

                        <div class="text-danger">

                            <i class="bi bi-exclamation-triangle-fill fs-3"></i>

                            <h6 class="fw-bold mt-2">
                                Unable to Load Kit Items
                            </h6>

                            <p class="text-muted mb-0">
                                Please try selecting the government kit again.
                            </p>

                        </div>
                    `;

                    itemsEmptyState.style.display =
                        'block';

                });

        }
    );


    /* ============================================================
       RENDER KIT ITEMS
    ============================================================ */

    function renderKitItems(items) {

        itemsLoadingState.style.display =
            'none';


        if (!items.length) {

            itemsEmptyState.innerHTML = `

                <div class="text-warning">

                    <i class="bi bi-box2 fs-3"></i>

                    <h6 class="fw-bold mt-2">
                        No Items Defined
                    </h6>

                    <p class="text-muted mb-0">
                        This government kit does not contain any items.
                    </p>

                </div>
            `;

            itemsEmptyState.style.display =
                'block';

            return;
        }


        kitItemsBody.innerHTML = '';


        items.forEach(function (item, index) {

            const supplyItem =
                item.supply_item || {};

            const kitQuantity =
                parseInt(item.quantity || 0);

            const stock =
                parseInt(
                    supplyItem.quantity_in_stock || 0
                );


            const row =
                document.createElement('tr');

            row.className =
                'distribution-item-row stock-ok';

            row.dataset.stock =
                stock;

            row.dataset.maxQuantity =
                kitQuantity;


            row.innerHTML = `

                <td class="ps-4 fw-semibold">
                    ${index + 1}
                </td>

                <td>

                    <div class="fw-semibold">
                        ${escapeHtml(
                            supplyItem.item_name || 'Unknown Item'
                        )}
                    </div>

                    ${
                        item.remarks
                        ? `
                            <div class="small text-muted">
                                ${escapeHtml(item.remarks)}
                            </div>
                        `
                        : ''
                    }

                    <input
                        type="hidden"
                        name="items[${index}][supply_item_id]"
                        value="${supplyItem.id || ''}"
                    >

                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        ${escapeHtml(
                            supplyItem.item_code || '-'
                        )}
                    </span>
                </td>

                <td>
                    ${escapeHtml(
                        supplyItem.unit || '-'
                    )}
                </td>

                <td class="text-center">

                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        ${kitQuantity}
                    </span>

                </td>

                <td class="text-center">

                    <span
                        class="fw-semibold stock-value"
                    >
                        ${stock}
                    </span>

                </td>

                <td class="text-center">

                    <input
                        type="number"
                        name="items[${index}][quantity]"
                        value="${kitQuantity}"
                        min="1"
                        max="${kitQuantity}"
                        class="form-control form-control-sm quantity-input issue-quantity text-center"
                        required
                    >

                </td>

                <td class="text-center pe-4">

                    <span class="badge stock-badge bg-success">
                        Available
                    </span>

                </td>

            `;


            kitItemsBody.appendChild(row);


            const quantityInput =
                row.querySelector('.issue-quantity');


            quantityInput.addEventListener(
                'input',
                function () {

                    validateRow(row);

                    updateSummary();

                }
            );


            validateRow(row);

        });


        itemsTableWrapper.style.display =
            'block';

        distributionSummary.style.display =
            'block';

        updateSummary();

    }


    /* ============================================================
       VALIDATE ITEM STOCK
    ============================================================ */

    function validateRow(row) {

        const stock =
            parseInt(
                row.dataset.stock || 0
            );

        const maxQuantity =
            parseInt(
                row.dataset.maxQuantity || 0
            );

        const input =
            row.querySelector('.issue-quantity');

        const badge =
            row.querySelector('.stock-badge');


        let quantity =
            parseInt(input.value || 0);


        if (quantity < 1) {
            quantity = 1;
            input.value = 1;
        }


        if (quantity > maxQuantity) {

            input.value =
                maxQuantity;

            quantity =
                maxQuantity;

        }


        if (quantity > stock) {

            row.classList.remove(
                'stock-ok'
            );

            row.classList.add(
                'stock-danger'
            );

            badge.className =
                'badge stock-badge bg-danger';

            badge.textContent =
                'Insufficient';

        } else {

            row.classList.remove(
                'stock-danger'
            );

            row.classList.add(
                'stock-ok'
            );

            badge.className =
                'badge stock-badge bg-success';

            badge.textContent =
                'Available';

        }

    }


    /* ============================================================
       UPDATE SUMMARY
    ============================================================ */

    function updateSummary() {

        const rows =
            kitItemsBody.querySelectorAll(
                '.distribution-item-row'
            );


        let totalQuantity = 0;

        let hasInsufficientStock =
            false;


        rows.forEach(function (row) {

            const input =
                row.querySelector(
                    '.issue-quantity'
                );

            const quantity =
                parseInt(
                    input?.value || 0
                );

            const stock =
                parseInt(
                    row.dataset.stock || 0
                );


            totalQuantity += quantity;


            if (quantity > stock) {

                hasInsufficientStock =
                    true;

            }

        });


        summaryItemTypes.textContent =
            rows.length;

        summaryQuantity.textContent =
            totalQuantity;


        if (hasInsufficientStock) {

            summaryStock.textContent =
                'Insufficient Stock';

            summaryStock.className =
                'fs-5 fw-bold text-danger';

            stockStatusBadge.className =
                'badge bg-danger';

            stockStatusBadge.textContent =
                'Insufficient Stock';

            submitBtn.disabled =
                true;

        } else {

            summaryStock.textContent =
                'Stock Available';

            summaryStock.className =
                'fs-5 fw-bold text-success';

            stockStatusBadge.className =
                'badge bg-success';

            stockStatusBadge.textContent =
                'Stock Available';

            submitBtn.disabled =
                false;

        }

    }


    /* ============================================================
       RESET ITEMS
    ============================================================ */

    function resetItems() {

        kitItemsBody.innerHTML = '';

        itemsTableWrapper.style.display =
            'none';

        itemsLoadingState.style.display =
            'none';

        itemsEmptyState.style.display =
            'block';

        distributionSummary.style.display =
            'none';

        kitInformation.style.display =
            'none';

        stockStatusBadge.className =
            'badge bg-secondary';

        stockStatusBadge.textContent =
            'Select a Government Kit';

        submitBtn.disabled =
            false;

    }


    /* ============================================================
       FORM SUBMIT VALIDATION
    ============================================================ */

    distributionForm.addEventListener(
        'submit',
        function (event) {

            if (!studentId.value) {

                event.preventDefault();

                alert(
                    'Please select a student before issuing the government kit.'
                );

                studentSearch.focus();

                return;

            }


            if (!kitTemplate.value) {

                event.preventDefault();

                alert(
                    'Please select a government kit.'
                );

                kitTemplate.focus();

                return;

            }


            const rows =
                kitItemsBody.querySelectorAll(
                    '.distribution-item-row'
                );


            if (!rows.length) {

                event.preventDefault();

                alert(
                    'Please select a government kit with defined items.'
                );

                return;

            }


            let invalidStock =
                false;


            rows.forEach(function (row) {

                const input =
                    row.querySelector(
                        '.issue-quantity'
                    );

                const quantity =
                    parseInt(
                        input?.value || 0
                    );

                const stock =
                    parseInt(
                        row.dataset.stock || 0
                    );


                if (quantity > stock) {

                    invalidStock =
                        true;

                }

            });


            if (invalidStock) {

                event.preventDefault();

                alert(
                    'One or more items have insufficient stock.'
                );

                return;

            }


            submitBtn.disabled =
                true;

            submitBtn.innerHTML = `

                <span
                    class="spinner-border spinner-border-sm me-1"
                ></span>

                Processing...

            `;

        }
    );


    /* ============================================================
       CLOSE RESULTS WHEN CLICKING OUTSIDE
    ============================================================ */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !studentSearch.contains(event.target)
                &&
                !studentResults.contains(event.target)
            ) {

                studentResults.style.display =
                    'none';

            }

        }
    );


    /* ============================================================
       HTML ESCAPE
    ============================================================ */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* ============================================================
       INITIALIZE OLD KIT AFTER VALIDATION ERROR
    ============================================================ */

    @if(old('kit_template_id'))

        setTimeout(function () {

            kitTemplate.dispatchEvent(
                new Event('change')
            );

        }, 100);

    @endif

});

</script>

@endsection
