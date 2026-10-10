@extends('layouts.app')

@section('content')

<style>
    .supply-kit-page {
        padding: 24px 0;
    }

    .supply-kit-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .07);
        overflow: hidden;
    }

    .supply-kit-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eee;
        background: #fff;
    }

    .supply-kit-header h4 {
        margin: 0;
        font-weight: 700;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 18px;
        color: #333;
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
    }

    .student-search-wrapper {
        position: relative;
    }

    .student-results {
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        z-index: 1050;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 0 0 10px 10px;
        max-height: 300px;
        overflow-y: auto;
        display: none;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .10);
    }

    .student-result {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .student-result:last-child {
        border-bottom: 0;
    }

    .student-result:hover {
        background: #f5f7fa;
    }

    .student-result-id {
        font-size: 12px;
        color: #6c757d;
    }

    .student-result-name {
        font-weight: 600;
        color: #212529;
    }

    .selected-student {
        display: none;
        margin-top: 15px;
        padding: 15px;
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .student-avatar {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .10);
    }

    .student-avatar-placeholder {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #6c757d;
    }

    .student-info-label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }

    .student-info-value {
        font-size: 14px;
        font-weight: 600;
    }

    .items-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
    }

    .items-card-header {
        background: #f8f9fa;
        padding: 14px 16px;
        font-weight: 700;
    }

    .items-table {
        margin-bottom: 0;
    }

    .items-table th {
        background: #f8f9fa;
        font-size: 13px;
        white-space: nowrap;
    }

    .items-table td {
        vertical-align: middle;
    }

    .stock-badge {
        font-size: 11px;
        padding: 5px 8px;
        border-radius: 20px;
    }

    .stock-ok {
        background: #d1e7dd;
        color: #0f5132;
    }

    .stock-low {
        background: #fff3cd;
        color: #664d03;
    }

    .stock-out {
        background: #f8d7da;
        color: #842029;
    }

    .remove-item {
        border: 0;
        background: transparent;
        color: #dc3545;
        font-size: 18px;
    }

    .empty-items {
        padding: 40px 20px;
        text-align: center;
        color: #6c757d;
    }

    .total-box {
        padding: 15px 18px;
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .total-number {
        font-size: 22px;
        font-weight: 700;
    }

    .manual-item-box {
        background: #fafafa;
        border: 1px dashed #ced4da;
        border-radius: 12px;
        padding: 15px;
    }

    .required-star {
        color: #dc3545;
    }
</style>

@php
    $isEdit = isset($studentSupplyKit);

    $oldItems = $isEdit
        ? $studentSupplyKit->items
        : collect();

    $selectedStudent = $isEdit
        ? $studentSupplyKit->student
        : null;

    $selectedTemplateId = $isEdit
        ? $studentSupplyKit->kit_template_id
        : old('kit_template_id');

    $selectedIssueDate = $isEdit
        ? optional($studentSupplyKit->issue_date)->format('Y-m-d')
        : old('issue_date', now()->format('Y-m-d'));

    $selectedAcademicYear = $isEdit
        ? $studentSupplyKit->academic_year
        : old('academic_year', $academicYear ?? '');

    $selectedRemarks = $isEdit
        ? $studentSupplyKit->remarks
        : old('remarks');
@endphp

<div class="container-fluid supply-kit-page">

    <div class="supply-kit-card">

        {{-- HEADER --}}
        <div class="supply-kit-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4>
                        <i class="bi bi-box-seam me-2"></i>

                        {{ $isEdit
                            ? 'Edit Student Supply Kit'
                            : 'Issue Student Supply Kit'
                        }}
                    </h4>

                    <div class="text-muted small mt-1">
                        {{ $isEdit
                            ? 'Update the issued student kit and stock details.'
                            : 'Assign a supply kit to a student.'
                        }}
                    </div>
                </div>

                <a
                    href="{{ route('admin.student-supply-kits.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>

            </div>

        </div>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ $isEdit
                ? route('admin.student-supply-kits.update', $studentSupplyKit)
                : route('admin.student-supply-kits.store')
            }}"
            id="studentSupplyKitForm"
        >

            @csrf

            @if($isEdit)
                @method('PUT')
            @endif


            <div class="p-4">

                {{-- VALIDATION ERRORS --}}
                @if($errors->any())

                    <div class="alert alert-danger">

                        <strong>
                            Please fix the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- =========================================================
                     STUDENT
                ========================================================== --}}

                <div class="section-title">
                    <i class="bi bi-person me-2"></i>
                    Student Information
                </div>

                <div class="row g-3">

                    <div class="col-lg-8">

                        <label class="form-label">
                            Search Student
                            <span class="required-star">*</span>
                        </label>

                        <div class="student-search-wrapper">

                            <input
                                type="text"
                                id="studentSearch"
                                class="form-control"
                                placeholder="Search by Student ID or student name..."
                                autocomplete="off"
                                value="{{ $selectedStudent
                                    ? $selectedStudent->student_id . ' - ' . $selectedStudent->full_name
                                    : ''
                                }}"
                            >

                            <div
                                id="studentResults"
                                class="student-results"
                            ></div>

                        </div>

                        <input
                            type="hidden"
                            name="student_id"
                            id="studentId"
                            value="{{ old(
                                'student_id',
                                $selectedStudent->id ?? ''
                            ) }}"
                        >

                        <div class="form-text">
                            Search using Student ID, first name, middle name or last name.
                        </div>

                    </div>

                </div>


                {{-- SELECTED STUDENT --}}
                <div
                    id="selectedStudent"
                    class="selected-student"
                    @if(!$selectedStudent)
                        style="display:none;"
                    @endif
                >

                    <div class="row align-items-center g-3">

                        <div class="col-auto">

                            @if($selectedStudent && $selectedStudent->profile_image)

                                <img
                                    id="studentImage"
                                    src="{{ $selectedStudent->profile_image }}"
                                    class="student-avatar"
                                    alt="Student"
                                >

                                <div
                                    id="studentPlaceholder"
                                    class="student-avatar-placeholder"
                                    style="display:none;"
                                >
                                    <i class="bi bi-person"></i>
                                </div>

                            @else

                                <img
                                    id="studentImage"
                                    src=""
                                    class="student-avatar"
                                    style="display:none;"
                                    alt="Student"
                                >

                                <div
                                    id="studentPlaceholder"
                                    class="student-avatar-placeholder"
                                >
                                    <i class="bi bi-person"></i>
                                </div>

                            @endif

                        </div>


                        <div class="col">

                            <div class="row g-3">

                                <div class="col-md-3">

                                    <div class="student-info-label">
                                        Student ID
                                    </div>

                                    <div
                                        id="studentDisplayId"
                                        class="student-info-value"
                                    >
                                        {{ $selectedStudent->student_id ?? '-' }}
                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="student-info-label">
                                        Student Name
                                    </div>

                                    <div
                                        id="studentDisplayName"
                                        class="student-info-value"
                                    >
                                        {{ $selectedStudent->full_name ?? '-' }}
                                    </div>

                                </div>

                                <div class="col-md-2">

                                    <div class="student-info-label">
                                        Class
                                    </div>

                                    <div
                                        id="studentDisplayClass"
                                        class="student-info-value"
                                    >
                                        {{ $selectedStudent->class ?? '-' }}
                                    </div>

                                </div>

                                <div class="col-md-2">

                                    <div class="student-info-label">
                                        Section
                                    </div>

                                    <div
                                        id="studentDisplaySection"
                                        class="student-info-value"
                                    >
                                        {{ $selectedStudent->section ?? '-' }}
                                    </div>

                                </div>

                                <div class="col-md-2">

                                    <div class="student-info-label">
                                        Roll No.
                                    </div>

                                    <div
                                        id="studentDisplayRoll"
                                        class="student-info-value"
                                    >
                                        {{ $selectedStudent->roll_number ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =========================================================
                     KIT DETAILS
                ========================================================== --}}

                <div class="section-title">
                    <i class="bi bi-box-seam me-2"></i>
                    Kit Details
                </div>

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Kit Template
                            <span class="required-star">*</span>
                        </label>

                        <select
                            name="kit_template_id"
                            id="kitTemplateId"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Kit Template
                            </option>

                            @foreach($kitTemplates as $template)

                                <option
                                    value="{{ $template->id }}"
                                    {{ (string) $selectedTemplateId === (string) $template->id
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $template->kit_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Issue Date
                            <span class="required-star">*</span>
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            id="issueDate"
                            class="form-control"
                            value="{{ $selectedIssueDate }}"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Academic Year
                            <span class="required-star">*</span>
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            id="academicYear"
                            class="form-control"
                            value="{{ $selectedAcademicYear }}"
                            maxlength="20"
                            required
                        >

                    </div>

                </div>


                <hr class="my-4">


                {{-- =========================================================
                     KIT ITEMS
                ========================================================== --}}

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="section-title mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        Kit Items
                    </div>

                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        id="addManualItemBtn"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Item
                    </button>

                </div>


                <div class="items-card">

                    <div class="items-card-header">

                        <div class="d-flex justify-content-between">

                            <span>
                                Selected Items
                            </span>

                            <span>
                                <span id="itemCount">0</span>
                                Items
                            </span>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table items-table">

                            <thead>

                                <tr>

                                    <th width="35%">
                                        Supply Item
                                    </th>

                                    <th width="15%">
                                        Available
                                    </th>

                                    <th width="15%">
                                        Quantity
                                    </th>

                                    <th width="25%">
                                        Remarks
                                    </th>

                                    <th width="10%">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody id="itemsTableBody">

                                <tr id="emptyItemsRow">

                                    <td
                                        colspan="5"
                                        class="empty-items"
                                    >

                                        <i class="bi bi-box-seam fs-2 d-block mb-2"></i>

                                        Select a kit template to load items.

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- TOTAL --}}
                <div class="row justify-content-end mt-3">

                    <div class="col-md-4">

                        <div class="total-box">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Total Items
                                </span>

                                <span
                                    id="totalItems"
                                    class="total-number"
                                >
                                    0
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =========================================================
                     REMARKS
                ========================================================== --}}

                <div class="section-title">
                    <i class="bi bi-chat-left-text me-2"></i>
                    Remarks
                </div>

                <div class="mb-4">

                    <textarea
                        name="remarks"
                        class="form-control"
                        rows="3"
                        maxlength="2000"
                        placeholder="Enter any additional remarks..."
                    >{{ $selectedRemarks }}</textarea>

                </div>


                {{-- =========================================================
                     BUTTONS
                ========================================================== --}}

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.student-supply-kits.index') }}"
                        class="btn btn-light border"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="submitBtn"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        {{ $isEdit
                            ? 'Update Supply Kit'
                            : 'Issue Supply Kit'
                        }}

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
     JAVASCRIPT
============================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const studentSearch =
        document.getElementById('studentSearch');

    const studentResults =
        document.getElementById('studentResults');

    const studentId =
        document.getElementById('studentId');

    const selectedStudent =
        document.getElementById('selectedStudent');

    const kitTemplateId =
        document.getElementById('kitTemplateId');

    const itemsTableBody =
        document.getElementById('itemsTableBody');

    const emptyItemsRow =
        document.getElementById('emptyItemsRow');

    const itemCount =
        document.getElementById('itemCount');

    const totalItems =
        document.getElementById('totalItems');

    const addManualItemBtn =
        document.getElementById('addManualItemBtn');


    let searchTimer = null;


    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */

    const studentSearchUrl =
        @json(route('admin.student-supply-kits.students.search'));

    const templateItemsUrl =
        @json(route('admin.student-supply-kits.template.items'));

    const supplyItemsUrl =
        @json(route('admin.student-supply-kits.supply-items'));


    /*
    |--------------------------------------------------------------------------
    | Existing Edit Items
    |--------------------------------------------------------------------------
    */

    const existingItems = @json(
        $oldItems->map(function ($item) {

            return [
                'supply_item_id' =>
                    $item->supply_item_id,

                'item_name' =>
                    optional($item->supplyItem)->item_name,

                'quantity' =>
                    $item->quantity,

                'remarks' =>
                    $item->remarks,

                'available_quantity' =>
                    optional($item->supplyItem)->available_quantity,
            ];

        })->values()
    );


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
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
    | Student Search
    |--------------------------------------------------------------------------
    */

    studentSearch.addEventListener(
        'input',
        function () {

            clearTimeout(searchTimer);

            const search =
                this.value.trim();

            if (search.length < 2) {

                studentResults.style.display =
                    'none';

                return;
            }

            searchTimer = setTimeout(
                function () {

                    fetch(
                        studentSearchUrl +
                        '?search=' +
                        encodeURIComponent(search),
                        {
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    )
                    .then(response => response.json())
                    .then(data => {

                        studentResults.innerHTML =
                            '';

                        if (
                            !data.success ||
                            !data.students ||
                            data.students.length === 0
                        ) {

                            studentResults.innerHTML =
                                '<div class="p-3 text-muted">' +
                                'No active students found.' +
                                '</div>';

                            studentResults.style.display =
                                'block';

                            return;
                        }


                        data.students.forEach(
                            function (student) {

                                const row =
                                    document.createElement('div');

                                row.className =
                                    'student-result';

                                row.innerHTML = `

                                    <div class="student-result-name">
                                        ${escapeHtml(student.name)}
                                    </div>

                                    <div class="student-result-id">
                                        ID: ${escapeHtml(student.student_id)}
                                        &nbsp; | &nbsp;
                                        Class: ${escapeHtml(student.class ?? '-')}
                                        &nbsp; | &nbsp;
                                        Section: ${escapeHtml(student.section ?? '-')}
                                        &nbsp; | &nbsp;
                                        Roll: ${escapeHtml(student.roll_number ?? '-')}
                                    </div>

                                `;

                                row.addEventListener(
                                    'click',
                                    function () {

                                        selectStudent(student);

                                    }
                                );

                                studentResults.appendChild(row);

                            }
                        );

                        studentResults.style.display =
                            'block';

                    })
                    .catch(error => {

                        console.error(error);

                        studentResults.innerHTML =
                            '<div class="p-3 text-danger">' +
                            'Unable to search students.' +
                            '</div>';

                        studentResults.style.display =
                            'block';
                    });

                },
                300
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Select Student
    |--------------------------------------------------------------------------
    */

    function selectStudent(student) {

        studentId.value =
            student.id;

        studentSearch.value =
            student.student_id +
            ' - ' +
            student.name;

        document.getElementById(
            'studentDisplayId'
        ).textContent =
            student.student_id || '-';

        document.getElementById(
            'studentDisplayName'
        ).textContent =
            student.name || '-';

        document.getElementById(
            'studentDisplayClass'
        ).textContent =
            student.class || '-';

        document.getElementById(
            'studentDisplaySection'
        ).textContent =
            student.section || '-';

        document.getElementById(
            'studentDisplayRoll'
        ).textContent =
            student.roll_number || '-';


        const image =
            document.getElementById(
                'studentImage'
            );

        const placeholder =
            document.getElementById(
                'studentPlaceholder'
            );


        if (student.profile_image) {

            image.src =
                student.profile_image;

            image.style.display =
                'block';

            placeholder.style.display =
                'none';

        } else {

            image.style.display =
                'none';

            placeholder.style.display =
                'flex';
        }


        selectedStudent.style.display =
            'block';

        studentResults.style.display =
            'none';
    }


    /*
    |--------------------------------------------------------------------------
    | Close Student Results
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.closest(
                    '.student-search-wrapper'
                )
            ) {

                studentResults.style.display =
                    'none';
            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Kit Template Change
    |--------------------------------------------------------------------------
    */

    kitTemplateId.addEventListener(
        'change',
        function () {

            const templateId =
                this.value;

            if (!templateId) {

                clearItems();

                return;
            }

            loadTemplateItems(templateId);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Load Template Items
    |--------------------------------------------------------------------------
    */

    function loadTemplateItems(templateId) {

        itemsTableBody.innerHTML = `

            <tr>

                <td colspan="5"
                    class="empty-items">

                    <div class="spinner-border spinner-border-sm me-2"></div>

                    Loading kit items...

                </td>

            </tr>

        `;


        fetch(
            templateItemsUrl +
            '?kit_template_id=' +
            encodeURIComponent(templateId),
            {
                headers: {
                    'Accept':
                        'application/json'
                }
            }
        )
        .then(response => response.json())
        .then(data => {

            if (
                !data.success ||
                !data.items
            ) {

                clearItems();

                showError(
                    'No items found for this kit template.'
                );

                return;
            }

            renderItems(
                data.items
            );

        })
        .catch(error => {

            console.error(error);

            clearItems();

            showError(
                'Unable to load kit template items.'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Render Items
    |--------------------------------------------------------------------------
    */

    function renderItems(items) {

        itemsTableBody.innerHTML =
            '';

        if (!items.length) {

            showEmptyItems(
                'No supply items are configured for this template.'
            );

            updateTotals();

            return;
        }


        items.forEach(
            function (item, index) {

                addItemRow(
                    item,
                    index
                );

            }
        );

        updateTotals();

    }


    /*
    |--------------------------------------------------------------------------
    | Add Item Row
    |--------------------------------------------------------------------------
    */

    function addItemRow(
        item,
        index = Date.now()
    ) {

        if (emptyItemsRow) {

            emptyItemsRow.remove();

        }


        const supplyItemId =
            item.supply_item_id ||
            item.id;

        const quantity =
            parseInt(
                item.quantity || 1
            );

        const available =
            parseInt(
                item.available_quantity || 0
            );


        const row =
            document.createElement('tr');

        row.dataset.itemId =
            supplyItemId;


        row.innerHTML = `

            <td>

                <input
                    type="hidden"
                    name="items[${index}][supply_item_id]"
                    value="${escapeHtml(supplyItemId)}"
                >

                <strong>
                    ${escapeHtml(item.item_name || '-')}
                </strong>

            </td>


            <td>

                <span class="stock-badge ${getStockClass(available, quantity)}">

                    ${escapeHtml(available)}

                    available

                </span>

            </td>


            <td>

                <input
                    type="number"
                    name="items[${index}][quantity]"
                    class="form-control form-control-sm item-quantity"
                    value="${quantity}"
                    min="1"
                    max="${Math.max(available, quantity)}"
                    data-available="${available}"
                    required
                >

            </td>


            <td>

                <input
                    type="text"
                    name="items[${index}][remarks]"
                    class="form-control form-control-sm"
                    value="${escapeHtml(item.remarks || '')}"
                    maxlength="500"
                    placeholder="Optional"
                >

            </td>


            <td class="text-center">

                <button
                    type="button"
                    class="remove-item"
                    title="Remove item"
                >

                    <i class="bi bi-trash"></i>

                </button>

            </td>

        `;


        itemsTableBody.appendChild(row);


        const quantityInput =
            row.querySelector(
                '.item-quantity'
            );


        quantityInput.addEventListener(
            'input',
            function () {

                updateStockBadge(
                    row,
                    this
                );

                updateTotals();

            }
        );


        row.querySelector(
            '.remove-item'
        ).addEventListener(
            'click',
            function () {

                row.remove();

                updateTotals();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Add Manual Item
    |--------------------------------------------------------------------------
    */

    addManualItemBtn.addEventListener(
        'click',
        function () {

            openSupplyItemSelector();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Supply Item Selector
    |--------------------------------------------------------------------------
    */

    function openSupplyItemSelector() {

        const existingModal =
            document.getElementById(
                'supplyItemModal'
            );

        if (existingModal) {

            existingModal.remove();

        }


        const modalHtml = `

            <div
                class="modal fade"
                id="supplyItemModal"
                tabindex="-1"
            >

                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Select Supply Item
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                            ></button>

                        </div>


                        <div class="modal-body">

                            <input
                                type="text"
                                id="supplyItemSearch"
                                class="form-control mb-3"
                                placeholder="Search supply item..."
                                autocomplete="off"
                            >


                            <div
                                id="supplyItemResults"
                                class="list-group"
                            >

                                <div class="text-muted text-center p-3">
                                    Loading...
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        `;


        document.body.insertAdjacentHTML(
            'beforeend',
            modalHtml
        );


        const modalElement =
            document.getElementById(
                'supplyItemModal'
            );

        const modal =
            new bootstrap.Modal(
                modalElement
            );

        modal.show();


        loadSupplyItems('');


        const searchInput =
            document.getElementById(
                'supplyItemSearch'
            );

        let timer = null;


        searchInput.addEventListener(
            'input',
            function () {

                clearTimeout(timer);

                const search =
                    this.value.trim();

                timer = setTimeout(
                    function () {

                        loadSupplyItems(
                            search
                        );

                    },
                    300
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Load Supply Items
    |--------------------------------------------------------------------------
    */

    function loadSupplyItems(search) {

        const results =
            document.getElementById(
                'supplyItemResults'
            );

        if (!results) {
            return;
        }


        results.innerHTML = `

            <div class="text-center p-3">

                <div class="spinner-border spinner-border-sm"></div>

                Loading...

            </div>

        `;


        fetch(
            supplyItemsUrl +
            '?search=' +
            encodeURIComponent(search),
            {
                headers: {
                    'Accept':
                        'application/json'
                }
            }
        )
        .then(response => response.json())
        .then(data => {

            results.innerHTML = '';


            if (
                !data.success ||
                !data.items ||
                !data.items.length
            ) {

                results.innerHTML = `

                    <div class="text-center text-muted p-3">
                        No supply items found.
                    </div>

                `;

                return;
            }


            data.items.forEach(
                function (item) {

                    const button =
                        document.createElement(
                            'button'
                        );

                    button.type =
                        'button';

                    button.className =
                        'list-group-item list-group-item-action';


                    button.innerHTML = `

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="fw-semibold">
                                    ${escapeHtml(item.item_name)}
                                </div>

                                <small class="text-muted">
                                    Available:
                                    ${escapeHtml(item.available_quantity)}
                                </small>

                            </div>

                            <span class="badge bg-secondary">
                                Select
                            </span>

                        </div>

                    `;


                    button.addEventListener(
                        'click',
                        function () {

                            addManualSupplyItem(
                                item
                            );

                            const modalElement =
                                document.getElementById(
                                    'supplyItemModal'
                                );

                            const modal =
                                bootstrap.Modal.getInstance(
                                    modalElement
                                );

                            if (modal) {
                                modal.hide();
                            }

                        }
                    );


                    results.appendChild(
                        button
                    );

                }
            );

        })
        .catch(error => {

            console.error(error);

            results.innerHTML = `

                <div class="text-danger text-center p-3">
                    Unable to load supply items.
                </div>

            `;

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Add Manual Supply Item
    |--------------------------------------------------------------------------
    */

    function addManualSupplyItem(item) {

        const alreadyExists =
            [...itemsTableBody.querySelectorAll(
                'input[name$="[supply_item_id]"]'
            )]
            .some(
                input =>
                    String(input.value) ===
                    String(item.id)
            );


        if (alreadyExists) {

            alert(
                'This supply item is already added.'
            );

            return;
        }


        addItemRow({
            supply_item_id:
                item.id,

            item_name:
                item.item_name,

            quantity:
                1,

            available_quantity:
                item.available_quantity,

            remarks:
                ''
        });


        updateTotals();

    }


    /*
    |--------------------------------------------------------------------------
    | Stock Badge
    |--------------------------------------------------------------------------
    */

    function getStockClass(
        available,
        quantity
    ) {

        if (available <= 0) {
            return 'stock-out';
        }

        if (quantity > available) {
            return 'stock-out';
        }

        if (available <= 5) {
            return 'stock-low';
        }

        return 'stock-ok';
    }


    function updateStockBadge(
        row,
        quantityInput
    ) {

        const available =
            parseInt(
                quantityInput.dataset.available || 0
            );

        const quantity =
            parseInt(
                quantityInput.value || 0
            );


        const badge =
            row.querySelector(
                '.stock-badge'
            );


        if (!badge) {
            return;
        }


        badge.className =
            'stock-badge ' +
            getStockClass(
                available,
                quantity
            );


        badge.textContent =
            available +
            ' available';

    }


    /*
    |--------------------------------------------------------------------------
    | Totals
    |--------------------------------------------------------------------------
    */

    function updateTotals() {

        const rows =
            itemsTableBody.querySelectorAll(
                'tr[data-item-id]'
            );


        let count = 0;

        let total = 0;


        rows.forEach(
            function (row) {

                count++;

                const quantity =
                    row.querySelector(
                        '.item-quantity'
                    );

                if (quantity) {

                    total +=
                        parseInt(
                            quantity.value || 0
                        );
                }

            }
        );


        itemCount.textContent =
            count;

        totalItems.textContent =
            total;


        if (count === 0) {

            showEmptyItems(
                'Select a kit template to load items.'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Empty Items
    |--------------------------------------------------------------------------
    */

    function showEmptyItems(message) {

        itemsTableBody.innerHTML = `

            <tr id="emptyItemsRow">

                <td
                    colspan="5"
                    class="empty-items"
                >

                    <i class="bi bi-box-seam fs-2 d-block mb-2"></i>

                    ${escapeHtml(message)}

                </td>

            </tr>

        `;

    }


    function clearItems() {

        showEmptyItems(
            'Select a kit template to load items.'
        );

        updateTotals();

    }


    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    */

    function showError(message) {

        itemsTableBody.innerHTML = `

            <tr>

                <td
                    colspan="5"
                    class="text-center text-danger p-4"
                >

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    ${escapeHtml(message)}

                </td>

            </tr>

        `;

    }


    /*
    |--------------------------------------------------------------------------
    | Form Validation
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'studentSupplyKitForm'
    ).addEventListener(
        'submit',
        function (event) {

            /*
            | Student required
            */

            if (!studentId.value) {

                event.preventDefault();

                alert(
                    'Please select a student.'
                );

                studentSearch.focus();

                return;
            }


            /*
            | Template required
            */

            if (!kitTemplateId.value) {

                event.preventDefault();

                alert(
                    'Please select a kit template.'
                );

                kitTemplateId.focus();

                return;
            }


            /*
            | At least one item
            */

            const rows =
                itemsTableBody.querySelectorAll(
                    'tr[data-item-id]'
                );


            if (!rows.length) {

                event.preventDefault();

                alert(
                    'Please add at least one supply item.'
                );

                return;
            }


            /*
            | Validate quantities
            */

            let invalidStock =
                false;


            rows.forEach(
                function (row) {

                    const quantityInput =
                        row.querySelector(
                            '.item-quantity'
                        );

                    if (!quantityInput) {
                        return;
                    }


                    const available =
                        parseInt(
                            quantityInput.dataset.available || 0
                        );

                    const quantity =
                        parseInt(
                            quantityInput.value || 0
                        );


                    if (
                        quantity < 1 ||
                        quantity > available
                    ) {

                        invalidStock =
                            true;

                        quantityInput.classList.add(
                            'is-invalid'
                        );

                    } else {

                        quantityInput.classList.remove(
                            'is-invalid'
                        );

                    }

                }
            );


            if (invalidStock) {

                event.preventDefault();

                alert(
                    'Please check the item quantities. Quantity cannot be greater than available stock.'
                );

                return;
            }


            /*
            | Prevent double submit
            */

            const submitBtn =
                document.getElementById(
                    'submitBtn'
                );

            submitBtn.disabled =
                true;

            submitBtn.innerHTML = `

                <span
                    class="spinner-border spinner-border-sm me-1"
                ></span>

                Saving...

            `;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL EDIT DATA
    |--------------------------------------------------------------------------
    */

    @if($isEdit)

        if (
            existingItems &&
            existingItems.length
        ) {

            renderItems(
                existingItems
            );

        } else if (
            kitTemplateId.value
        ) {

            loadTemplateItems(
                kitTemplateId.value
            );

        }

    @endif

});
</script>

@endsection