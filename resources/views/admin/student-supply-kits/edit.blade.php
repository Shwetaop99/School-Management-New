```blade
@extends('layouts.app')

@section('title', 'Edit Student Kit Distribution')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-warning"
                    style="width:52px;height:52px;"
                >
                    <i class="bi bi-pencil-square fs-4"></i>
                </div>

                <div>

                    <h3 class="fw-bold mb-1">
                        Edit Student Kit Distribution
                    </h3>

                    <p class="text-muted mb-0">
                        Update government supplied kit distribution details
                    </p>

                </div>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.student-supply-kits.show',
                    $studentSupplyKit
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-eye me-1"></i>
                View
            </a>

            <a
                href="{{ route('admin.student-supply-kits.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm mb-4">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle-fill me-1"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        IMPORTANT STOCK NOTICE
    ========================================================== --}}
    @if($studentSupplyKit->status === 'issued')

        <div class="alert alert-info border-0 shadow-sm mb-4">

            <div class="d-flex gap-3">

                <div class="flex-shrink-0">

                    <i class="bi bi-info-circle-fill fs-4"></i>

                </div>

                <div>

                    <div class="fw-bold">
                        Stock Adjustment
                    </div>

                    <div class="small">
                        This distribution has already been issued.
                        When you save changes, the previously deducted stock
                        will be restored and the updated quantities will be
                        deducted again automatically.
                    </div>

                </div>

            </div>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.student-supply-kits.update',
            $studentSupplyKit
        ) }}"
        id="distributionForm"
    >

        @csrf

        @method('PUT')


        {{-- =====================================================
            STUDENT INFORMATION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-person-vcard-fill text-primary fs-5"></i>

                    <div>

                        <h5 class="fw-bold mb-0">
                            Student Information
                        </h5>

                        <small class="text-muted">
                            Student receiving the government kit
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                @php

                    $student = $studentSupplyKit->student;

                    $studentName = $student
                        ? collect([
                            $student->first_name,
                            $student->middle_name,
                            $student->last_name
                        ])->filter()->implode(' ')
                        : '';

                @endphp


                <div class="row g-4">

                    {{-- Student Search --}}
                    <div class="col-lg-7">

                        <label class="form-label fw-semibold">

                            Student

                            <span class="text-danger">*</span>

                        </label>


                        <div class="position-relative">

                            <input
                                type="text"
                                id="student_search"
                                class="form-control form-control-lg"
                                value="{{ old('student_name', $studentName) }}"
                                placeholder="Search Student ID or Name..."
                                autocomplete="off"
                            >


                            <input
                                type="hidden"
                                name="student_id"
                                id="student_id"
                                value="{{ old(
                                    'student_id',
                                    $studentSupplyKit->student_id
                                ) }}"
                            >


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


                        <div
                            id="selectedStudent"
                            class="mt-3"
                        >

                            <div class="alert alert-primary border-0 mb-0">

                                <div class="d-flex justify-content-between align-items-start gap-3">

                                    <div>

                                        <div
                                            class="fw-bold"
                                            id="selectedStudentName"
                                        >
                                            {{ $studentName ?: 'Student Not Available' }}
                                        </div>

                                        <div class="small mt-1">

                                            Student ID:

                                            <strong id="selectedStudentId">
                                                {{ $student?->student_id ?? '-' }}
                                            </strong>

                                        </div>

                                        <div class="small">

                                            Class:

                                            <strong id="selectedStudentClass">
                                                {{ $student?->class ?? '-' }}
                                            </strong>

                                            &nbsp; | &nbsp;

                                            Section:

                                            <strong id="selectedStudentSection">
                                                {{ $student?->section ?? '-' }}
                                            </strong>

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

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">

                            Academic Year

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            id="academic_year"
                            class="form-control"
                            value="{{ old(
                                'academic_year',
                                $studentSupplyKit->academic_year
                            ) }}"
                            placeholder="Example: 2026-27"
                            required
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DISTRIBUTION INFORMATION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-clipboard-check-fill text-primary fs-5"></i>

                    <div>

                        <h5 class="fw-bold mb-0">
                            Distribution Information
                        </h5>

                        <small class="text-muted">
                            Government kit and issue details
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <div class="row g-4">

                    {{-- Government Kit --}}
                    <div class="col-lg-7">

                        <label class="form-label fw-semibold">

                            Government Kit

                            <span class="text-danger">*</span>

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


                            @foreach($kitTemplates as $kitTemplate)

                                <option
                                    value="{{ $kitTemplate->id }}"
                                    data-class="{{ $kitTemplate->class }}"
                                    data-academic-year="{{ $kitTemplate->academic_year }}"
                                    {{ old(
                                        'kit_template_id',
                                        $studentSupplyKit->kit_template_id
                                    ) == $kitTemplate->id ? 'selected' : '' }}
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
                            Select the government kit that was distributed.
                        </div>

                    </div>


                    {{-- Date --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">

                            Distribution Date

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            class="form-control"
                            value="{{ old(
                                'issue_date',
                                $studentSupplyKit->issue_date
                                    ? \Carbon\Carbon::parse(
                                        $studentSupplyKit->issue_date
                                    )->format('Y-m-d')
                                    : ''
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">

                            Distribution Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="issued"
                                {{ old(
                                    'status',
                                    $studentSupplyKit->status
                                ) === 'issued' ? 'selected' : '' }}
                            >
                                Issued
                            </option>

                            <option
                                value="pending"
                                {{ old(
                                    'status',
                                    $studentSupplyKit->status
                                ) === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="cancelled"
                                {{ old(
                                    'status',
                                    $studentSupplyKit->status
                                ) === 'cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Kit information --}}
                    <div class="col-lg-7">

                        <div
                            id="kitInformation"
                            class="alert alert-light border mb-0"
                        >

                            <div class="fw-bold mb-1">

                                <i class="bi bi-info-circle-fill text-primary me-1"></i>

                                Government Kit

                            </div>

                            <div class="small text-muted">

                                <div>

                                    Kit:

                                    <strong id="kitInfoName">

                                        {{ $studentSupplyKit->kitTemplate?->kit_name ?? '-' }}

                                    </strong>

                                </div>

                                <div>

                                    Applicable Class:

                                    <strong id="kitInfoClass">

                                        {{ $studentSupplyKit->kitTemplate?->class ?? '-' }}

                                    </strong>

                                </div>

                                <div>

                                    Academic Year:

                                    <strong id="kitInfoYear">

                                        {{ $studentSupplyKit->kitTemplate?->academic_year ?? '-' }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Remarks --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="3"
                            placeholder="Enter distribution remarks..."
                        >{{ old(
                            'remarks',
                            $studentSupplyKit->remarks
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DISTRIBUTION ITEMS
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-boxes text-primary fs-5"></i>

                            <h5 class="fw-bold mb-0">
                                Distribution Items
                            </h5>

                        </div>

                        <small class="text-muted">
                            Items and quantities distributed to the student
                        </small>

                    </div>


                    <span
                        id="stockStatusBadge"
                        class="badge bg-secondary"
                    >
                        Checking Stock
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

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


                <div
                    id="itemsEmptyState"
                    class="text-center py-5"
                    style="display:none;"
                >

                    <i class="bi bi-box2 text-muted fs-1"></i>

                    <h6 class="fw-bold mt-3">
                        No Kit Items
                    </h6>

                    <p class="text-muted mb-0">
                        No items are available for the selected government kit.
                    </p>

                </div>


                <div
                    id="itemsTableWrapper"
                    class="table-responsive"
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
                                    Supply Item
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th class="text-center">
                                    Kit Qty / Student
                                </th>

                                <th class="text-center">
                                    Available Stock
                                </th>

                                <th
                                    class="text-center"
                                    style="width:150px;"
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


        {{-- =====================================================
            SUMMARY
        ====================================================== --}}
        <div
            id="distributionSummary"
            class="card border-0 shadow-sm mb-4"
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
                                id="summaryStock"
                                class="fs-5 fw-bold"
                            >
                                Checking...
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

            <a
                href="{{ route(
                    'admin.student-supply-kits.show',
                    $studentSupplyKit
                ) }}"
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

                Update Distribution

            </button>

        </div>

    </form>

</div>


{{-- =============================================================
    STYLES
============================================================= --}}
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


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const studentSearch =
        document.getElementById('student_search');

    const studentResults =
        document.getElementById('studentResults');

    const studentId =
        document.getElementById('student_id');

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

    const kitInfoName =
        document.getElementById('kitInfoName');

    const kitInfoClass =
        document.getElementById('kitInfoClass');

    const kitInfoYear =
        document.getElementById('kitInfoYear');


    const itemsLoadingState =
        document.getElementById('itemsLoadingState');

    const itemsEmptyState =
        document.getElementById('itemsEmptyState');

    const itemsTableWrapper =
        document.getElementById('itemsTableWrapper');

    const kitItemsBody =
        document.getElementById('kitItemsBody');


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


    /*
     * Existing distribution items.
     */
    const existingItems = @json(
        $studentSupplyKit->items->map(function ($item) {

            return [
                'supply_item_id' => $item->supply_item_id,
                'quantity' => $item->quantity,
                'condition' => $item->condition ?? 'New',
                'remarks' => $item->remarks,
            ];

        })->values()
    );


    /* ============================================================
       STUDENT SEARCH
    ============================================================ */

    studentSearch.addEventListener(
        'input',
        function () {

            const search =
                this.value.trim();

            clearTimeout(studentSearchTimer);


            if (search.length < 2) {

                studentResults.style.display =
                    'none';

                studentResults.innerHTML =
                    '';

                return;

            }


            studentSearchTimer =
                setTimeout(function () {

                    fetch(
                        "{{ route(
                            'admin.student-supply-kits.students.search'
                        ) }}"
                        + "?search="
                        + encodeURIComponent(search)
                    )
                        .then(response => {

                            if (!response.ok) {

                                throw new Error(
                                    'Unable to search students.'
                                );

                            }

                            return response.json();

                        })
                        .then(students => {

                            studentResults.innerHTML =
                                '';


                            if (!students.length) {

                                studentResults.innerHTML = `

                                    <div class="p-3 text-muted text-center">

                                        <i class="bi bi-person-x me-1"></i>

                                        No students found.

                                    </div>

                                `;

                                studentResults.style.display =
                                    'block';

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


                                const result =
                                    document.createElement('div');


                                result.className =
                                    'student-result';


                                result.innerHTML = `

                                    <div class="fw-semibold">

                                        ${escapeHtml(fullName)}

                                    </div>

                                    <div class="small text-muted">

                                        Student ID:

                                        <strong>
                                            ${escapeHtml(
                                                student.student_id ?? ''
                                            )}
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

                                        selectStudent(
                                            student,
                                            fullName
                                        );

                                    }
                                );


                                studentResults.appendChild(
                                    result
                                );

                            });


                            studentResults.style.display =
                                'block';

                        })
                        .catch(error => {

                            console.error(error);

                            studentResults.innerHTML = `

                                <div class="p-3 text-danger text-center">

                                    Unable to search students.

                                </div>

                            `;

                            studentResults.style.display =
                                'block';

                        });

                }, 300);

        }
    );


    /* ============================================================
       SELECT STUDENT
    ============================================================ */

    function selectStudent(
        student,
        fullName
    ) {

        studentId.value =
            student.id;

        studentSearch.value =
            fullName;

        selectedStudentName.textContent =
            fullName;

        selectedStudentId.textContent =
            student.student_id ?? '-';

        selectedStudentClass.textContent =
            student.class ?? '-';

        selectedStudentSection.textContent =
            student.section ?? '-';

        studentResults.style.display =
            'none';

        studentResults.innerHTML =
            '';

        studentSearch.readOnly =
            true;

    }


    /* ============================================================
       CHANGE STUDENT
    ============================================================ */

    changeStudentBtn.addEventListener(
        'click',
        function () {

            studentId.value =
                '';

            studentSearch.value =
                '';

            studentSearch.readOnly =
                false;

            selectedStudentName.textContent =
                'Select Student';

            selectedStudentId.textContent =
                '-';

            selectedStudentClass.textContent =
                '-';

            selectedStudentSection.textContent =
                '-';

            studentSearch.focus();

        }
    );


    /* ============================================================
       KIT CHANGE
    ============================================================ */

    kitTemplate.addEventListener(
        'change',
        function () {

            const kitId =
                this.value;


            if (!kitId) {

                clearItems();

                return;

            }


            const selectedOption =
                this.options[
                    this.selectedIndex
                ];


            kitInfoName.textContent =
                selectedOption.textContent.trim();

            kitInfoClass.textContent =
                selectedOption.dataset.class || '-';

            kitInfoYear.textContent =
                selectedOption.dataset.academicYear || '-';


            if (
                selectedOption.dataset.academicYear
            ) {

                academicYear.value =
                    selectedOption.dataset.academicYear;

            }


            loadKitItems(
                kitId
            );

        }
    );


    /* ============================================================
       LOAD KIT ITEMS
    ============================================================ */

    function loadKitItems(
        kitId
    ) {

        itemsTableWrapper.style.display =
            'none';

        itemsEmptyState.style.display =
            'none';

        itemsLoadingState.style.display =
            'block';


        fetch(
            "{{ route(
                'admin.student-supply-kits.template.items'
            ) }}"
            + "?kit_template_id="
            + encodeURIComponent(kitId)
        )
            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Unable to load kit items.'
                    );

                }

                return response.json();

            })
            .then(data => {

                renderItems(
                    data.items || []
                );

            })
            .catch(error => {

                console.error(error);

                itemsLoadingState.style.display =
                    'none';

                itemsEmptyState.innerHTML = `

                    <div class="text-danger">

                        <i class="bi bi-exclamation-triangle fs-2"></i>

                        <h6 class="fw-bold mt-2">
                            Unable to Load Items
                        </h6>

                        <p class="text-muted mb-0">
                            Please try selecting the kit again.
                        </p>

                    </div>

                `;

                itemsEmptyState.style.display =
                    'block';

            });

    }


    /* ============================================================
       RENDER ITEMS
    ============================================================ */

    function renderItems(
        items
    ) {

        itemsLoadingState.style.display =
            'none';


        kitItemsBody.innerHTML =
            '';


        if (!items.length) {

            itemsEmptyState.innerHTML = `

                <div class="text-warning">

                    <i class="bi bi-box2 fs-2"></i>

                    <h6 class="fw-bold mt-2">
                        No Items Defined
                    </h6>

                    <p class="text-muted mb-0">
                        This government kit has no defined items.
                    </p>

                </div>

            `;

            itemsEmptyState.style.display =
                'block';

            return;

        }


        items.forEach(
            function (item, index) {

                const supplyItem =
                    item.supply_item || {};


                const kitQuantity =
                    parseInt(
                        item.quantity || 0
                    );


                const stock =
                    parseInt(
                        supplyItem.quantity_in_stock || 0
                    );


                /*
                 * Find the old distribution quantity.
                 */
                const existing =
                    existingItems.find(
                        oldItem =>
                            parseInt(
                                oldItem.supply_item_id
                            )
                            ===
                            parseInt(
                                supplyItem.id
                            )
                    );


                const issueQuantity =
                    existing
                        ? parseInt(
                            existing.quantity
                        )
                        : kitQuantity;


                const condition =
                    existing?.condition
                    || 'New';


                const remarks =
                    existing?.remarks
                    || '';


                const row =
                    document.createElement('tr');


                row.className =
                    'distribution-item-row';


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
                                supplyItem.item_name
                                || 'Unknown Item'
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

                        <span class="fw-semibold">

                            ${stock}

                        </span>

                    </td>


                    <td>

                        <input
                            type="number"
                            name="items[${index}][quantity]"
                            value="${issueQuantity}"
                            min="1"
                            max="${kitQuantity}"
                            class="form-control form-control-sm quantity-input issue-quantity text-center"
                            required
                        >


                        <select
                            name="items[${index}][condition]"
                            class="form-select form-select-sm mt-2 condition-input"
                        >

                            <option
                                value="New"
                                ${condition === 'New' ? 'selected' : ''}
                            >
                                New
                            </option>

                            <option
                                value="Good"
                                ${condition === 'Good' ? 'selected' : ''}
                            >
                                Good
                            </option>

                            <option
                                value="Damaged"
                                ${condition === 'Damaged' ? 'selected' : ''}
                            >
                                Damaged
                            </option>

                        </select>

                    </td>


                    <td class="pe-4">

                        <input
                            type="text"
                            name="items[${index}][remarks]"
                            value="${escapeHtml(remarks)}"
                            class="form-control form-control-sm"
                            placeholder="Remarks"
                            maxlength="500"
                        >


                        <div class="text-center mt-2">

                            <span class="badge stock-badge">

                                Checking

                            </span>

                        </div>

                    </td>

                `;


                kitItemsBody.appendChild(
                    row
                );


                const quantityInput =
                    row.querySelector(
                        '.issue-quantity'
                    );


                quantityInput.addEventListener(
                    'input',
                    function () {

                        validateRow(
                            row
                        );

                        updateSummary();

                    }
                );


                validateRow(
                    row
                );

            }
        );


        itemsTableWrapper.style.display =
            'block';

        updateSummary();

    }


    /* ============================================================
       VALIDATE STOCK
    ============================================================ */

    
function validateRow(row)
{
    const stock =
        parseInt(
            row.dataset.stock || 0
        );

    const maxQuantity =
        parseInt(
            row.dataset.maxQuantity || 0
        );

    const input =
        row.querySelector(
            '.issue-quantity'
        );

    const badge =
        row.querySelector(
            '.stock-badge'
        );

    let quantity =
        parseInt(
            input.value || 0
        );


    if (quantity < 1) {

        quantity = 1;

        input.value = 1;

    }


    if (quantity > maxQuantity) {

        quantity =
            maxQuantity;

        input.value =
            maxQuantity;

    }


    /*
     * Stock is required only when
     * distribution status is ISSUED.
     */
    const status =
        document.getElementById('status').value;


    if (status !== 'issued') {

        row.classList.remove(
            'stock-danger'
        );

        row.classList.add(
            'stock-ok'
        );

        badge.className =
            'badge stock-badge bg-secondary';

        badge.textContent =
            'Not Deducted';

        return;

    }


    /*
     * ISSUED distribution:
     * check actual available stock.
     */
    if (quantity > stock) {

        row.classList.add(
            'stock-danger'
        );

        row.classList.remove(
            'stock-ok'
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
       SUMMARY
    ============================================================ */

    function updateSummary()
    {

        const rows =
            kitItemsBody.querySelectorAll(
                '.distribution-item-row'
            );


        let totalQuantity =
            0;

        let insufficient =
            false;


        rows.forEach(
            function (row) {

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


                totalQuantity +=
                    quantity;


                if (quantity > stock) {

                    insufficient =
                        true;

                }

            }
        );


        summaryItemTypes.textContent =
            rows.length;


        summaryQuantity.textContent =
            totalQuantity;


        if (insufficient) {

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
       CLEAR ITEMS
    ============================================================ */

    function clearItems()
    {

        kitItemsBody.innerHTML =
            '';

        itemsTableWrapper.style.display =
            'none';

        itemsEmptyState.style.display =
            'block';

        stockStatusBadge.className =
            'badge bg-secondary';

        stockStatusBadge.textContent =
            'Select a Government Kit';

        summaryItemTypes.textContent =
            '0';

        summaryQuantity.textContent =
            '0';

        summaryStock.textContent =
            '—';

        summaryStock.className =
            'fs-5 fw-bold text-muted';

    }


    /* ============================================================
       FORM SUBMIT
    ============================================================ */

    distributionForm.addEventListener(
        'submit',
        function (event) {

            if (!studentId.value) {

                event.preventDefault();

                alert(
                    'Please select a student.'
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
                    'The selected government kit has no items.'
                );

                return;

            }


            let insufficient =
                false;


            rows.forEach(
                function (row) {

                    const quantity =
                        parseInt(
                            row.querySelector(
                                '.issue-quantity'
                            )?.value || 0
                        );


                    const stock =
                        parseInt(
                            row.dataset.stock || 0
                        );


                    if (quantity > stock) {

                        insufficient =
                            true;

                    }

                }
            );


            if (
                insufficient
                &&
                document.getElementById('status').value
                    === 'issued'
            ) {

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

                Updating...

            `;

        }
    );


    /* ============================================================
       CLOSE SEARCH RESULTS
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

    function escapeHtml(value)
    {

        return String(value ?? '')
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );

    }


    /* ============================================================
       LOAD CURRENT KIT
    ============================================================ */

    if (kitTemplate.value) {

        setTimeout(
            function () {

                kitTemplate.dispatchEvent(
                    new Event('change')
                );

            },
            100
        );

    }

});

</script>

@endsection
