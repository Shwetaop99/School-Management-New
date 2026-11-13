@extends('layouts.app')

@section('title', 'Edit Fee Structure')

@section('content')

<style>
    .fee-edit-page {
        min-height: calc(100vh - 64px);
        background: #f4f7fb;
        padding: 24px;
    }

    /* HEADER */
    .fee-page-header {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #147cf5 0%, #6c63ff 100%);
        border-radius: 20px;
        padding: 26px 28px;
        color: #fff;
        margin-bottom: 22px;
        box-shadow: 0 10px 30px rgba(46, 91, 255, .16);
    }

    .fee-page-header::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        right: -70px;
        top: -110px;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .fee-page-header h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 750;
    }

    .fee-page-header p {
        margin: 6px 0 0;
        font-size: 14px;
        opacity: .9;
    }

    .header-actions {
        position: relative;
        z-index: 3;
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .header-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 15px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: .2s ease;
    }

    .header-btn-light {
        background: #fff;
        color: #1769d1;
    }

    .header-btn-light:hover {
        color: #0d5dbd;
        transform: translateY(-1px);
    }

    .header-btn-outline {
        color: #fff;
        border: 1px solid rgba(255,255,255,.55);
        background: rgba(255,255,255,.08);
    }

    .header-btn-outline:hover {
        background: rgba(255,255,255,.16);
        color: #fff;
    }

    /* FORM CARD */
    .form-card {
        background: #fff;
        border: 1px solid #e7ecf3;
        border-radius: 18px;
        box-shadow: 0 5px 22px rgba(31,45,61,.055);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .form-card-header {
        padding: 18px 21px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #26364a;
        font-size: 16px;
        font-weight: 750;
    }

    .card-title i {
        color: #147cf5;
        font-size: 18px;
    }

    .form-card-body {
        padding: 22px;
    }

    /* FORM */
    .form-label {
        color: #46556a;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .required-star {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border: 1px solid #dce3ed;
        border-radius: 10px;
        color: #26364a;
        font-size: 14px;
        box-shadow: none !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20,124,245,.09) !important;
    }

    .form-control::placeholder {
        color: #a0aaba;
    }

    .form-text {
        color: #8a96a7;
        font-size: 11px;
        margin-top: 5px;
    }

    /* SECTION */
    .section-loading {
        display: none;
        font-size: 12px;
        color: #147cf5;
        margin-top: 6px;
    }

    .section-loading i {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* FEE ROW */
    .fee-items-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .fee-row {
        background: #f9fbfe;
        border: 1px solid #e5ebf3;
        border-radius: 14px;
        padding: 15px;
        transition: .2s ease;
    }

    .fee-row:hover {
        border-color: #cbdcf4;
        box-shadow: 0 4px 15px rgba(31,45,61,.04);
    }

    .fee-row-number {
        width: 31px;
        height: 31px;
        border-radius: 9px;
        background: #eef5ff;
        color: #147cf5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 750;
    }

    .remove-row {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        border: 1px solid #f1c7cd;
        background: #fff;
        color: #dc3545;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .2s ease;
    }

    .remove-row:hover {
        background: #fff1f2;
        border-color: #dc3545;
    }

    .add-row-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #eef5ff;
        color: #147cf5;
        border: 1px solid #cfe0fa;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s ease;
    }

    .add-row-btn:hover {
        background: #e4efff;
        color: #0d68d8;
    }

    /* TOTAL */
    .total-box {
        margin-top: 18px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f1f6ff, #f5f2ff);
        border: 1px solid #dfe8f7;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .total-label {
        color: #536174;
        font-size: 13px;
        font-weight: 700;
    }

    .total-label small {
        display: block;
        color: #8995a6;
        font-weight: 500;
        margin-top: 2px;
    }

    .total-amount {
        color: #5c52db;
        font-size: 23px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* BOTTOM ACTIONS */
    .bottom-actions {
        background: #fff;
        border: 1px solid #e7ecf3;
        border-radius: 18px;
        padding: 17px 20px;
        box-shadow: 0 5px 22px rgba(31,45,61,.055);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fff;
        color: #59677a;
        border: 1px solid #dce3ed;
        border-radius: 10px;
        padding: 10px 17px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #f7f9fc;
        color: #344054;
    }

    .btn-update {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 750;
        transition: .2s ease;
    }

    .btn-update:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(20,124,245,.2);
    }

    .btn-update:disabled {
        opacity: .7;
        cursor: not-allowed;
        transform: none;
    }

    /* ALERT */
    .alert-custom {
        border: 0;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    /* MOBILE */
    @media (max-width: 768px) {

        .fee-edit-page {
            padding: 14px;
        }

        .fee-page-header {
            padding: 20px;
        }

        .fee-page-header h2 {
            font-size: 21px;
        }

        .header-actions {
            width: 100%;
        }

        .header-btn {
            flex: 1;
        }

        .form-card-body {
            padding: 15px;
        }

        .bottom-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-cancel,
        .btn-update {
            justify-content: center;
            width: 100%;
        }

        .total-box {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

@php
    $existingItems = $feeStructure->items ?? collect();
@endphp

<div class="fee-edit-page">

    {{-- HEADER --}}
    <div class="fee-page-header">

        <div class="header-content">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>
                    <h2>
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Fee Structure
                    </h2>

                    <p>
                        Update the academic year, class, section and fee items.
                    </p>
                </div>

                <div class="header-actions">

                    <a href="{{ route('admin.fees.fee-structures.index') }}"
                       class="header-btn header-btn-outline">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.fees.fee-structures.show', $feeStructure->id) }}"
                       class="header-btn header-btn-light">
                        <i class="bi bi-eye"></i>
                        View
                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger alert-custom">

            <div class="fw-bold mb-1">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following:
            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success alert-custom">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>

    @endif

    <form action="{{ route('admin.fees.fee-structures.update', $feeStructure->id) }}"
          method="POST"
          id="feeStructureForm">

        @csrf
        @method('PUT')

        {{-- BASIC INFORMATION --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="card-title">
                    <i class="bi bi-info-circle"></i>
                    Basic Information
                </div>

                <span class="badge rounded-pill text-bg-primary">
                    ID #{{ $feeStructure->id }}
                </span>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    {{-- ACADEMIC YEAR --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label">
                            Academic Year
                            <span class="required-star">*</span>
                        </label>

                        <select name="academic_year"
                                class="form-select"
                                required>

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year }}"
                                    {{ old('academic_year', $feeStructure->academic_year) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- CLASS --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label">
                            Class
                            <span class="required-star">*</span>
                        </label>

                        <select name="class_id"
                                id="class_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $feeStructure->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- SECTION --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label">
                            Section
                        </label>

                        <select name="section_id"
                                id="section_id"
                                class="form-select">

                            <option value="">
                                All Sections
                            </option>

                        </select>

                        <div class="section-loading" id="sectionLoading">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Loading sections...
                        </div>

                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label">
                            Status
                            <span class="required-star">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="Active"
                                {{ old('status', $feeStructure->status) === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ old('status', $feeStructure->status) === 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    {{-- STRUCTURE NAME --}}
                    <div class="col-12">

                        <label class="form-label">
                            Structure Name
                            <span class="required-star">*</span>
                        </label>

                        <input type="text"
                               name="structure_name"
                               class="form-control"
                               value="{{ old('structure_name', $feeStructure->structure_name) }}"
                               placeholder="Example: 10th Standard Annual Fee Structure"
                               maxlength="255"
                               required>

                    </div>

                </div>

            </div>

        </div>

        {{-- FEE ITEMS --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="card-title">
                    <i class="bi bi-wallet2"></i>
                    Fee Items
                </div>

                <button type="button"
                        class="add-row-btn"
                        id="addFeeRow">

                    <i class="bi bi-plus-lg"></i>
                    Add Fee

                </button>

            </div>

            <div class="form-card-body">

                <div class="fee-items-wrapper"
                     id="feeItemsContainer">

                    @if(old('fee_type_id'))

                        @foreach(old('fee_type_id') as $index => $oldFeeType)

                            <div class="fee-row">

                                <div class="row g-3 align-items-end">

                                    <div class="col-auto">

                                        <span class="fee-row-number">
                                            {{ $index + 1 }}
                                        </span>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Fee Type
                                            <span class="required-star">*</span>
                                        </label>

                                        <select name="fee_type_id[]"
                                                class="form-select fee-type-select"
                                                required>

                                            <option value="">
                                                Select Fee Type
                                            </option>

                                            @foreach($feeTypes as $feeType)

                                                <option value="{{ $feeType->id }}"
                                                    {{ (string) $oldFeeType === (string) $feeType->id ? 'selected' : '' }}>
                                                    {{ $feeType->name }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Amount
                                            <span class="required-star">*</span>
                                        </label>

                                        <input type="number"
                                               name="amount[]"
                                               class="form-control amount-input"
                                               value="{{ old('amount.' . $index) }}"
                                               min="0"
                                               max="99999999.99"
                                               step="0.01"
                                               placeholder="0.00"
                                               required>

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Due Date
                                        </label>

                                        <input type="date"
                                               name="due_date[]"
                                               class="form-control"
                                               value="{{ old('due_date.' . $index) }}">

                                    </div>

                                    <div class="col-auto ms-auto">

                                        <button type="button"
                                                class="remove-row"
                                                title="Remove Fee">

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    @else

                        @forelse($existingItems as $index => $item)

                            <div class="fee-row">

                                <div class="row g-3 align-items-end">

                                    <div class="col-auto">

                                        <span class="fee-row-number">
                                            {{ $index + 1 }}
                                        </span>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Fee Type
                                            <span class="required-star">*</span>
                                        </label>

                                        <select name="fee_type_id[]"
                                                class="form-select fee-type-select"
                                                required>

                                            <option value="">
                                                Select Fee Type
                                            </option>

                                            @foreach($feeTypes as $feeType)

                                                <option value="{{ $feeType->id }}"
                                                    {{ (string) $item->fee_type_id === (string) $feeType->id ? 'selected' : '' }}>
                                                    {{ $feeType->name }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Amount
                                            <span class="required-star">*</span>
                                        </label>

                                        <input type="number"
                                               name="amount[]"
                                               class="form-control amount-input"
                                               value="{{ $item->amount }}"
                                               min="0"
                                               max="99999999.99"
                                               step="0.01"
                                               placeholder="0.00"
                                               required>

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Due Date
                                        </label>

                                        <input type="date"
                                               name="due_date[]"
                                               class="form-control"
                                               value="{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') : '' }}">

                                    </div>

                                    <div class="col-auto ms-auto">

                                        <button type="button"
                                                class="remove-row"
                                                title="Remove Fee">

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="fee-row">

                                <div class="row g-3 align-items-end">

                                    <div class="col-auto">
                                        <span class="fee-row-number">1</span>
                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Fee Type
                                            <span class="required-star">*</span>
                                        </label>

                                        <select name="fee_type_id[]"
                                                class="form-select fee-type-select"
                                                required>

                                            <option value="">
                                                Select Fee Type
                                            </option>

                                            @foreach($feeTypes as $feeType)

                                                <option value="{{ $feeType->id }}">
                                                    {{ $feeType->name }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Amount
                                            <span class="required-star">*</span>
                                        </label>

                                        <input type="number"
                                               name="amount[]"
                                               class="form-control amount-input"
                                               min="0"
                                               max="99999999.99"
                                               step="0.01"
                                               placeholder="0.00"
                                               required>

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Due Date
                                        </label>

                                        <input type="date"
                                               name="due_date[]"
                                               class="form-control">

                                    </div>

                                    <div class="col-auto ms-auto">

                                        <button type="button"
                                                class="remove-row"
                                                title="Remove Fee">

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        @endforelse

                    @endif

                </div>

                {{-- TOTAL --}}
                <div class="total-box">

                    <div class="total-label">

                        <i class="bi bi-calculator me-1"></i>
                        Total Fee Amount

                        <small>
                            Automatically calculated from all fee items
                        </small>

                    </div>

                    <div class="total-amount">
                        ₹ <span id="totalAmount">0.00</span>
                    </div>

                </div>

            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="bottom-actions">

            <a href="{{ route('admin.fees.fee-structures.index') }}"
               class="btn-cancel">

                <i class="bi bi-x-lg"></i>
                Cancel

            </a>

            <button type="submit"
                    class="btn-update"
                    id="updateButton">

                <i class="bi bi-check2-circle"></i>
                Update Fee Structure

            </button>

        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const sectionLoading = document.getElementById('sectionLoading');

    const container = document.getElementById('feeItemsContainer');
    const addButton = document.getElementById('addFeeRow');
    const totalAmount = document.getElementById('totalAmount');
    const form = document.getElementById('feeStructureForm');
    const updateButton = document.getElementById('updateButton');

    const existingSectionId = @json(
        old('section_id', $feeStructure->section_id)
    );

    const sectionsUrlTemplate = @json(
        route(
            'admin.fees.fee-structures.sections',
            ['classId' => '__CLASS_ID__']
        )
    );

    const feeTypes = @json(
        $feeTypes->map(function ($feeType) {
            return [
                'id' => $feeType->id,
                'name' => $feeType->name,
            ];
        })->values()
    );

    /* -----------------------------------------
       LOAD SECTIONS
    ----------------------------------------- */

    function loadSections(selectedSection = null) {

        const classId = classSelect.value;

        sectionSelect.innerHTML =
            '<option value="">All Sections</option>';

        if (!classId) {
            sectionSelect.disabled = true;
            return;
        }

        sectionSelect.disabled = true;
        sectionLoading.style.display = 'block';

        const url = sectionsUrlTemplate.replace(
            '__CLASS_ID__',
            encodeURIComponent(classId)
        );

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            cache: 'no-store'
        })
        .then(response => response.text())
        .then(text => {

            text = text
                .replace(/^\uFEFF/, '')
                .trim();

            if (!text) {
                throw new Error('Empty response received.');
            }

            let sections;

            try {
                sections = JSON.parse(text);
            } catch (error) {
                console.error('Section response:', text);
                throw new Error('Invalid section response.');
            }

            sectionSelect.innerHTML =
                '<option value="">All Sections</option>';

            sections.forEach(section => {

                const option = document.createElement('option');

                option.value = section.id;
                option.textContent = section.section_name;

                if (
                    selectedSection !== null &&
                    selectedSection !== '' &&
                    String(selectedSection) === String(section.id)
                ) {
                    option.selected = true;
                }

                sectionSelect.appendChild(option);
            });

            sectionSelect.disabled = false;
        })
        .catch(error => {

            console.error('Section loading error:', error);

            sectionSelect.innerHTML =
                '<option value="">Unable to load sections</option>';

            sectionSelect.disabled = true;
        })
        .finally(() => {
            sectionLoading.style.display = 'none';
        });
    }

    classSelect.addEventListener('change', function () {
        loadSections('');
    });

    if (classSelect.value) {
        loadSections(existingSectionId);
    }

    /* -----------------------------------------
       CREATE FEE TYPE OPTIONS
    ----------------------------------------- */

    function feeTypeOptions(selectedValue = '') {

        let html =
            '<option value="">Select Fee Type</option>';

        feeTypes.forEach(feeType => {

            const selected =
                String(selectedValue) === String(feeType.id)
                    ? 'selected'
                    : '';

            html += `
                <option value="${feeType.id}" ${selected}>
                    ${escapeHtml(feeType.name)}
                </option>
            `;
        });

        return html;
    }

    /* -----------------------------------------
       ESCAPE HTML
    ----------------------------------------- */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* -----------------------------------------
       ADD FEE ROW
    ----------------------------------------- */

    addButton.addEventListener('click', function () {

        const row = document.createElement('div');

        row.className = 'fee-row';

        row.innerHTML = `
            <div class="row g-3 align-items-end">

                <div class="col-auto">

                    <span class="fee-row-number">
                        1
                    </span>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        Fee Type
                        <span class="required-star">*</span>
                    </label>

                    <select name="fee_type_id[]"
                            class="form-select fee-type-select"
                            required>

                        ${feeTypeOptions()}

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Amount
                        <span class="required-star">*</span>
                    </label>

                    <input type="number"
                           name="amount[]"
                           class="form-control amount-input"
                           min="0"
                           max="99999999.99"
                           step="0.01"
                           placeholder="0.00"
                           required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Due Date
                    </label>

                    <input type="date"
                           name="due_date[]"
                           class="form-control">

                </div>

                <div class="col-auto ms-auto">

                    <button type="button"
                            class="remove-row"
                            title="Remove Fee">

                        <i class="bi bi-trash3"></i>

                    </button>

                </div>

            </div>
        `;

        container.appendChild(row);

        updateRowNumbers();
        updateTotal();

    });

    /* -----------------------------------------
       REMOVE FEE ROW
    ----------------------------------------- */

    container.addEventListener('click', function (event) {

        const removeButton =
            event.target.closest('.remove-row');

        if (!removeButton) {
            return;
        }

        const rows =
            container.querySelectorAll('.fee-row');

        if (rows.length <= 1) {

            const row = removeButton.closest('.fee-row');

            row.querySelector('.fee-type-select').value = '';
            row.querySelector('.amount-input').value = '';

            const dateInput =
                row.querySelector('input[name="due_date[]"]');

            if (dateInput) {
                dateInput.value = '';
            }

            updateTotal();

            return;
        }

        removeButton
            .closest('.fee-row')
            .remove();

        updateRowNumbers();
        updateTotal();
        checkDuplicateFeeTypes();

    });

    /* -----------------------------------------
       UPDATE ROW NUMBERS
    ----------------------------------------- */

    function updateRowNumbers() {

        const rows =
            container.querySelectorAll('.fee-row');

        rows.forEach((row, index) => {

            const number =
                row.querySelector('.fee-row-number');

            if (number) {
                number.textContent = index + 1;
            }

        });
    }

    /* -----------------------------------------
       CALCULATE TOTAL
    ----------------------------------------- */

    function updateTotal() {

        let total = 0;

        const amounts =
            container.querySelectorAll('.amount-input');

        amounts.forEach(input => {

            const value =
                parseFloat(input.value) || 0;

            total += value;

        });

        totalAmount.textContent =
            total.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
    }

    container.addEventListener('input', function (event) {

        if (
            event.target.classList.contains('amount-input')
        ) {
            updateTotal();
        }

    });

    /* -----------------------------------------
       DUPLICATE FEE TYPE CHECK
    ----------------------------------------- */

    function checkDuplicateFeeTypes() {

        const selects =
            container.querySelectorAll('.fee-type-select');

        const selectedValues = [];

        selects.forEach(select => {

            select.classList.remove('is-invalid');

            if (select.value) {
                selectedValues.push(select.value);
            }

        });

        const duplicates =
            selectedValues.filter(
                (value, index, array) =>
                    array.indexOf(value) !== index
            );

        selects.forEach(select => {

            if (
                select.value &&
                duplicates.includes(select.value)
            ) {
                select.classList.add('is-invalid');
            }

        });

        return duplicates.length === 0;
    }

    container.addEventListener('change', function (event) {

        if (
            event.target.classList.contains('fee-type-select')
        ) {
            checkDuplicateFeeTypes();
        }

    });

    /* -----------------------------------------
       FORM SUBMIT
    ----------------------------------------- */

    form.addEventListener('submit', function (event) {

        const rows =
            container.querySelectorAll('.fee-row');

        if (rows.length === 0) {

            event.preventDefault();

            alert('Please add at least one fee item.');

            return;
        }

        if (!checkDuplicateFeeTypes()) {

            event.preventDefault();

            alert('Please select each fee type only once.');

            return;
        }

        let validAmount = true;

        container
            .querySelectorAll('.amount-input')
            .forEach(input => {

                if (
                    input.value === '' ||
                    parseFloat(input.value) < 0
                ) {
                    validAmount = false;
                }

            });

        if (!validAmount) {

            event.preventDefault();

            alert('Please enter a valid amount for every fee item.');

            return;
        }

        updateButton.disabled = true;

        updateButton.innerHTML = `
            <span class="spinner-border spinner-border-sm"
                  aria-hidden="true"></span>
            Updating...
        `;

    });

    /* -----------------------------------------
       INITIALIZE
    ----------------------------------------- */

    updateRowNumbers();
    updateTotal();
    checkDuplicateFeeTypes();

});
</script>

@endsection