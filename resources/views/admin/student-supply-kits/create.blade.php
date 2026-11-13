
@extends('layouts.app')

@section('title', 'Issue Student Supply Kit')

@section('content')

<style>
    :root {
        --kit-green: #198754;
        --kit-green-dark: #116640;
        --kit-green-soft: #eaf6ef;
        --kit-border: #e2e9e4;
        --kit-text: #25352c;
        --kit-muted: #738078;
        --kit-bg: #f5f8f5;
    }

    .supply-page {
        padding: 8px 2px 28px;
        color: var(--kit-text);
    }

    .supply-page .form-control,
    .supply-page .form-select {
        min-height: 44px;
        border-color: #dce5de;
        border-radius: 9px;
        font-size: 14px;
        box-shadow: none;
    }

    .supply-page textarea.form-control {
        min-height: 80px;
    }

    .supply-page .form-control:focus,
    .supply-page .form-select:focus {
        border-color: #66b88b;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, .10);
    }

    .supply-page .form-label {
        color: #34463a;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .supply-hero {
        position: relative;
        overflow: hidden;
        padding: 27px 30px;
        margin-bottom: 24px;
        border-radius: 17px;
        color: #fff;
        background: linear-gradient(120deg, #145c3b 0%, #198754 60%, #269e65 100%);
        box-shadow: 0 8px 22px rgba(20, 92, 59, .14);
    }

    .supply-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border: 35px solid rgba(255, 255, 255, .07);
        border-radius: 50%;
        right: -65px;
        top: -100px;
        pointer-events: none;
    }

    .supply-hero-content {
        position: relative;
        z-index: 1;
    }

    .supply-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 11px;
        margin-bottom: 12px;
        border: 1px solid rgba(255,255,255,.24);
        border-radius: 30px;
        background: rgba(255,255,255,.10);
        color: #f1fff6;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .7px;
        text-transform: uppercase;
    }

    .supply-hero h4 {
        margin: 0 0 8px;
        font-size: clamp(21px, 3vw, 28px);
        font-weight: 750;
        letter-spacing: -.5px;
    }

    .supply-hero p {
        margin: 0;
        color: rgba(255,255,255,.83);
        font-size: 14px;
    }

    .supply-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 16px;
        border: 1px solid rgba(255,255,255,.35);
        border-radius: 9px;
        background: rgba(255,255,255,.12);
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: .2s ease;
    }

    .supply-back-btn:hover {
        color: #fff;
        background: rgba(255,255,255,.22);
    }

    .supply-card {
        overflow: visible;
        margin-bottom: 23px;
        border: 1px solid var(--kit-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(31, 62, 42, .035);
    }

    .supply-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 19px 22px;
        border-bottom: 1px solid #edf1ed;
        border-radius: 15px 15px 0 0;
        background: #fff;
    }

    .supply-card-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .supply-section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 43px;
        height: 43px;
        border-radius: 12px;
        background: var(--kit-green-soft);
        color: var(--kit-green-dark);
        font-size: 17px;
    }

    .supply-card-header h6 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 750;
        color: #24372a;
    }

    .supply-card-header p {
        margin: 0;
        color: var(--kit-muted);
        font-size: 12px;
    }

    .supply-card-body {
        padding: 23px;
    }

    .supply-field-help {
        display: block;
        margin-top: 7px;
        color: var(--kit-muted);
        font-size: 11px;
        line-height: 1.5;
    }

    .supply-required {
        color: #dc3545;
    }

    .student-search-wrap {
        position: relative;
    }

    .student-search-wrap .search-icon {
        position: absolute;
        left: 14px;
        top: 14px;
        z-index: 2;
        color: #829087;
        pointer-events: none;
    }

    .student-search-wrap input {
        padding-left: 39px;
    }

    #studentResults {
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        z-index: 1050;
        display: none;
        max-height: 280px;
        overflow-y: auto;
        border: 1px solid var(--kit-border);
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(24, 50, 33, .14);
    }

    #studentResults .list-group-item {
        padding: 13px 15px;
        border-color: #edf1ed;
        background: #fff;
        color: var(--kit-text);
        font-size: 13px;
        cursor: pointer;
    }

    #studentResults .list-group-item:hover {
        background: #f0f8f2;
    }

    .selected-student-box {
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 75px;
        padding: 14px;
        border: 1px dashed #c9ded0;
        border-radius: 11px;
        background: #f6fbf7;
    }

    .selected-student-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #dff1e5;
        color: var(--kit-green-dark);
        font-size: 16px;
    }

    .selected-student-content {
        min-width: 0;
        font-size: 13px;
    }

    .selected-student-content .student-name {
        display: block;
        margin-bottom: 4px;
        color: #21442f;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .selected-student-content small {
        color: var(--kit-muted);
        line-height: 1.6;
    }

    .supply-add-btn,
    .supply-save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s ease;
    }

    .supply-add-btn {
        border: 1px solid #198754;
        background: #fff;
        color: #198754;
    }

    .supply-add-btn:hover {
        background: #eaf6ef;
        color: #116640;
    }

    .supply-table-wrap {
        overflow-x: auto;
        border-radius: 0 0 15px 15px;
    }

    #itemsTable {
        min-width: 760px;
        margin: 0;
    }

    #itemsTable thead th {
        padding: 15px 16px;
        border-bottom: 1px solid #e3ebe4;
        background: #f3f8f4;
        color: #42574a;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    #itemsTable tbody td {
        padding: 13px 15px;
        border-color: #edf1ed;
        vertical-align: middle;
    }

    #itemsTable tbody tr[data-item-row]:hover {
        background: #fbfdfb;
    }

    #itemsTable .form-control,
    #itemsTable .form-select {
        min-height: 39px;
        font-size: 12px;
        border-radius: 7px;
    }

    .supply-empty-state {
        padding: 40px 20px !important;
        text-align: center;
        color: var(--kit-muted);
    }

    .supply-empty-state .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 55px;
        height: 55px;
        margin: 0 auto 12px;
        border-radius: 15px;
        background: #f0f6f1;
        color: #6c9279;
        font-size: 21px;
    }

    .supply-empty-state strong {
        display: block;
        margin-bottom: 5px;
        color: #3c5143;
        font-size: 13px;
    }

    .supply-empty-state span {
        font-size: 12px;
    }

    .remove-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border: 1px solid #f0cccc;
        border-radius: 8px;
        background: #fff7f7;
        color: #c73e47;
    }

    .remove-item:hover {
        background: #c73e47;
        color: #fff;
    }

    .supply-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 17px 20px;
        border: 1px solid var(--kit-border);
        border-radius: 13px;
        background: #fff;
    }

    .supply-actions-note {
        color: var(--kit-muted);
        font-size: 12px;
    }

    .supply-cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 43px;
        padding: 10px 16px;
        border: 1px solid #dce5de;
        border-radius: 9px;
        background: #fff;
        color: #526257;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
    }

    .supply-cancel-btn:hover {
        background: #f4f7f4;
        color: #25352c;
    }

    .supply-save-btn {
        min-height: 43px;
        border: 1px solid var(--kit-green);
        background: var(--kit-green);
        color: #fff;
    }

    .supply-save-btn:hover {
        border-color: var(--kit-green-dark);
        background: var(--kit-green-dark);
        color: #fff;
    }

    .supply-alert {
        padding: 17px 19px;
        border-radius: 12px;
        font-size: 13px;
    }

    @media (max-width: 767.98px) {
        .supply-page {
            padding-top: 0;
        }

        .supply-hero {
            padding: 22px 19px;
            border-radius: 13px;
        }

        .supply-hero .d-flex {
            align-items: flex-start !important;
        }

        .supply-back-btn {
            padding: 9px 11px;
            font-size: 12px;
        }

        .supply-card-header {
            padding: 16px;
        }

        .supply-card-body {
            padding: 17px;
        }

        .supply-form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .supply-form-actions .supply-actions-buttons {
            display: flex;
            width: 100%;
        }

        .supply-actions-buttons > * {
            flex: 1;
        }
    }
</style>

<div class="container-fluid supply-page">

    {{-- Page Header --}}
    <div class="supply-hero">
        <div class="supply-hero-content d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <div class="supply-eyebrow">
                    <i class="fas fa-box-open"></i>
                    Government Supply Distribution
                </div>

                <h4>Issue Student Supply Kit</h4>

                <p>Assign a government supply kit, select its items and record distribution details.</p>
            </div>

            <a href="{{ route('admin.student-supply-kits.index') }}"
               class="supply-back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Supply Kits
            </a>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger supply-alert mb-4">
            <div class="d-flex gap-2 align-items-start">
                <i class="fas fa-exclamation-circle mt-1"></i>
                <div>
                    <strong>Please correct the following errors.</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.student-supply-kits.store') }}"
          id="supplyKitForm">
        @csrf

        {{-- Student & Kit Information --}}
        <section class="supply-card">
            <div class="supply-card-header">
                <div class="supply-card-heading">
                    <div class="supply-section-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h6>Student & Kit Information</h6>
                        <p>Select the student and configure the distribution.</p>
                    </div>
                </div>
            </div>

            <div class="supply-card-body">
                <div class="row g-4">

                    {{-- Student Search --}}
                    <div class="col-lg-6">
                        <label for="studentSearch" class="form-label">
                            Search Student <span class="supply-required">*</span>
                        </label>

                        <div class="student-search-wrap">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   id="studentSearch"
                                   class="form-control"
                                   placeholder="Enter student ID or student name"
                                   autocomplete="off"
                                   aria-describedby="studentSearchHelp">
                            <div id="studentResults" class="list-group"></div>
                        </div>

                        <input type="hidden"
                               name="student_id"
                               id="studentId"
                               value="{{ old('student_id') }}">

                        <small class="supply-field-help" id="studentSearchHelp">
                            <i class="fas fa-info-circle me-1"></i>
                            Type at least 2 characters, then select a student from the results.
                        </small>
                    </div>

                    {{-- Selected Student --}}
                    <div class="col-lg-6">
                        <label class="form-label">Selected Student</label>

                        <div id="selectedStudent" class="selected-student-box">
                            <div class="selected-student-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="selected-student-content">
                                <span class="student-name">No student selected</span>
                                <small>Student information will appear here.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Kit Template --}}
                    <div class="col-lg-6">
                        <label for="kitTemplate" class="form-label">
                            Kit Template <span class="supply-required">*</span>
                        </label>

                        <select name="kit_template_id"
                                id="kitTemplate"
                                class="form-select"
                                required>
                            <option value="">Select a kit template</option>

                            @foreach($kitTemplates as $template)
                                <option value="{{ $template->id }}"
                                        data-academic-year="{{ $template->academic_year }}"
                                        {{ old('kit_template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->kit_name }}
                                    @if($template->class)
                                        - Class {{ $template->class }}
                                    @endif
                                    - {{ $template->academic_year }}
                                </option>
                            @endforeach
                        </select>

                        <small class="supply-field-help">Choosing a template loads its configured supply items.</small>
                    </div>

                    {{-- Academic Year --}}
                    <div class="col-sm-6 col-lg-3">
                        <label for="academicYear" class="form-label">
                            Academic Year <span class="supply-required">*</span>
                        </label>

                        <input type="text"
                               name="academic_year"
                               id="academicYear"
                               class="form-control"
                               value="{{ old('academic_year', '2026-27') }}"
                               placeholder="e.g. 2026-27"
                               required>
                    </div>

                    {{-- Distribution Date --}}
                    <div class="col-sm-6 col-lg-3">
                        <label for="distributionDate" class="form-label">Distribution Date</label>

                        <input type="date"
                               name="distribution_date"
                               id="distributionDate"
                               class="form-control"
                               value="{{ old('distribution_date', date('Y-m-d')) }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-sm-6 col-lg-3">
                        <label for="supplyStatus" class="form-label">
                            Status <span class="supply-required">*</span>
                        </label>

                        <select name="status" id="supplyStatus" class="form-select" required>
                            <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="issued" {{ old('status') === 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>

                    {{-- Remarks --}}
                    <div class="col-lg-9">
                        <label for="supplyRemarks" class="form-label">Remarks</label>

                        <textarea name="remarks"
                                  id="supplyRemarks"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Add any additional distribution notes (optional)">{{ old('remarks') }}</textarea>
                    </div>

                </div>
            </div>
        </section>

        {{-- Kit Items --}}
        <section class="supply-card">
            <div class="supply-card-header">
                <div class="supply-card-heading">
                    <div class="supply-section-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h6>Supply Kit Items</h6>
                        <p>Review item quantities and enter the issued quantity.</p>
                    </div>
                </div>

                <button type="button" class="supply-add-btn" id="addItemBtn">
                    <i class="fas fa-plus"></i>
                    <span>Add Item</span>
                </button>
            </div>

            <div class="supply-table-wrap">
                <table class="table align-middle" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 31%;">Supply Item</th>
                            <th style="width: 14%;">Quantity</th>
                            <th style="width: 17%;">Issued Quantity</th>
                            <th>Remarks</th>
                            <th class="text-center" style="width: 75px;">Action</th>
                        </tr>
                    </thead>

                    <tbody id="itemsBody">
                        <tr id="emptyItemsRow">
                            <td colspan="5" class="supply-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <strong>No supply items loaded</strong>
                                <span>Select a kit template above to load its items.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Form Actions --}}
        <div class="supply-form-actions">
            <div class="supply-actions-note">
                <i class="fas fa-shield-alt me-1"></i>
                Review the student and item details before saving.
            </div>

            <div class="supply-actions-buttons d-flex gap-2">
                <a href="{{ route('admin.student-supply-kits.index') }}"
                   class="supply-cancel-btn">
                    Cancel
                </a>

                <button type="submit" class="supply-save-btn">
                    <i class="fas fa-save"></i>
                    Save Supply Kit
                </button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const studentSearch = document.getElementById('studentSearch');
    const studentResults = document.getElementById('studentResults');
    const studentId = document.getElementById('studentId');
    const selectedStudent = document.getElementById('selectedStudent');

    const kitTemplate = document.getElementById('kitTemplate');
    const academicYear = document.getElementById('academicYear');
    const itemsBody = document.getElementById('itemsBody');
    const addItemBtn = document.getElementById('addItemBtn');
    const supplyKitForm = document.getElementById('supplyKitForm');

    let searchTimer = null;
    let searchRequest = null;
    let templateRequest = null;

    /*
    |--------------------------------------------------------------------------
    | Student Search
    |--------------------------------------------------------------------------
    */

    if (studentSearch) {
        studentSearch.addEventListener('input', function () {
            clearTimeout(searchTimer);

            if (searchRequest) {
                searchRequest.abort();
            }

            const search = this.value.trim();

            studentId.value = '';
            selectedStudent.innerHTML = `
                <div class="selected-student-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="selected-student-content">
                    <span class="student-name">No student selected</span>
                    <small>Select a student from the search results.</small>
                </div>
            `;

            if (search.length < 2) {
                studentResults.innerHTML = '';
                studentResults.style.display = 'none';
                return;
            }

            searchTimer = setTimeout(async function () {
                searchRequest = new AbortController();

                studentResults.innerHTML = `
                    <div class="list-group-item text-muted">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Searching students...
                    </div>
                `;
                studentResults.style.display = 'block';

                try {
                    const url = `{{ route('admin.student-supply-kits.students.search') }}?search=${encodeURIComponent(search)}`;

                    const response = await fetch(url, {
                        headers: { 'Accept': 'application/json' },
                        signal: searchRequest.signal
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error: ${response.status}`);
                    }

                    const responseText = await response.text();
                    const students = JSON.parse(responseText.replace(/^\uFEFF/, ''));

                    studentResults.innerHTML = '';

                    if (!Array.isArray(students) || students.length === 0) {
                        studentResults.innerHTML = `
                            <div class="list-group-item text-muted">
                                <i class="fas fa-search me-2"></i>
                                No students found.
                            </div>
                        `;
                        studentResults.style.display = 'block';
                        return;
                    }

                    students.forEach(function (student) {
                        const fullName = [
                            student.first_name,
                            student.middle_name,
                            student.last_name
                        ].filter(Boolean).join(' ');

                        const result = document.createElement('button');
                        result.type = 'button';
                        result.className = 'list-group-item list-group-item-action text-start';

                        const name = document.createElement('div');
                        name.className = 'fw-semibold';
                        name.textContent = fullName || 'Unnamed student';

                        const details = document.createElement('small');
                        details.className = 'text-muted';

                        const studentNumber = student.student_id ?? '-';
                        const className = student.admission_class ?? '-';
                        const section = student.section ? ` - ${student.section}` : '';

                        details.textContent = `Student ID: ${studentNumber} | Class: ${className}${section}`;

                        result.appendChild(name);
                        result.appendChild(details);

                        result.addEventListener('click', function () {
                            studentId.value = student.id;
                            studentSearch.value = fullName;

                            selectedStudent.innerHTML = '';

                            const icon = document.createElement('div');
                            icon.className = 'selected-student-icon';
                            icon.innerHTML = '<i class="fas fa-user-check"></i>';

                            const content = document.createElement('div');
                            content.className = 'selected-student-content';

                            const studentName = document.createElement('span');
                            studentName.className = 'student-name';
                            studentName.textContent = fullName || 'Unnamed student';

                            const studentDetails = document.createElement('small');
                            studentDetails.textContent = `Student ID: ${studentNumber} | Class: ${className}${section}`;

                            content.appendChild(studentName);
                            content.appendChild(studentDetails);

                            selectedStudent.appendChild(icon);
                            selectedStudent.appendChild(content);

                            studentResults.innerHTML = '';
                            studentResults.style.display = 'none';
                        });

                        studentResults.appendChild(result);
                    });

                    studentResults.style.display = 'block';

                } catch (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }

                    console.error('Student search error:', error);

                    studentResults.innerHTML = `
                        <div class="list-group-item text-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Unable to search students. Please try again.
                        </div>
                    `;
                    studentResults.style.display = 'block';
                }
            }, 300);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Kit Template Change
    |--------------------------------------------------------------------------
    */

    if (kitTemplate) {
        kitTemplate.addEventListener('change', function () {
            const templateId = this.value;
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption && selectedOption.dataset.academicYear) {
                academicYear.value = selectedOption.dataset.academicYear;
            }

            if (templateRequest) {
                templateRequest.abort();
            }

            if (!templateId) {
                itemsBody.innerHTML = `
                    <tr id="emptyItemsRow">
                        <td colspan="5" class="supply-empty-state">
                            <div class="empty-icon"><i class="fas fa-box-open"></i></div>
                            <strong>No supply items loaded</strong>
                            <span>Select a kit template above to load its items.</span>
                        </td>
                    </tr>
                `;
                return;
            }

            itemsBody.innerHTML = `
                <tr>
                    <td colspan="5" class="supply-empty-state">
                        <div class="spinner-border spinner-border-sm text-success mb-2"></div>
                        <br>
                        <strong>Loading supply items</strong>
                        <span>Please wait while the template items are retrieved.</span>
                    </td>
                </tr>
            `;

            templateRequest = new AbortController();

            fetch(
                `{{ route('admin.student-supply-kits.template.items') }}?kit_template_id=${encodeURIComponent(templateId)}`,
                {
                    headers: { 'Accept': 'application/json' },
                    signal: templateRequest.signal
                }
            )
            .then(async response => {
                if (!response.ok) {
                    throw new Error(`HTTP error: ${response.status}`);
                }

                const responseText = await response.text();
                return JSON.parse(responseText.replace(/^\uFEFF/, ''));
            })
            .then(items => {
                itemsBody.innerHTML = '';

                if (!Array.isArray(items) || items.length === 0) {
                    itemsBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="supply-empty-state">
                                <div class="empty-icon"><i class="fas fa-box-open"></i></div>
                                <strong>No items found in this template</strong>
                                <span>You can add an item manually using the Add Item button.</span>
                            </td>
                        </tr>
                    `;
                    return;
                }

                items.forEach(function (item) {
                    addItemRow(item);
                });
            })
            .catch(error => {
                if (error.name === 'AbortError') {
                    return;
                }

                console.error('Kit template error:', error);

                itemsBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="supply-empty-state">
                            <div class="empty-icon text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <strong>Unable to load kit items</strong>
                            <span>Please select the template again or try later.</span>
                        </td>
                    </tr>
                `;
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Add Item Row
    |--------------------------------------------------------------------------
    */

    function addItemRow(item = {}) {
        const index = itemsBody.querySelectorAll('tr[data-item-row]').length;
        const row = document.createElement('tr');

        row.setAttribute('data-item-row', 'true');

        row.innerHTML = `
            <td>
                <select name="items[${index}][supply_item_id]"
                        class="form-select form-select-sm"
                        aria-label="Supply item"
                        required>
                    <option value="">Select Item</option>
                    @foreach($supplyItems as $supplyItem)
                        <option value="{{ $supplyItem->id }}">
                            {{ $supplyItem->item_name }}@if($supplyItem->item_code) ({{ $supplyItem->item_code }})@endif
                        </option>
                    @endforeach
                </select>
            </td>

            <td>
                <input type="number"
                       name="items[${index}][quantity]"
                       class="form-control form-control-sm"
                       min="1"
                       step="1"
                       value="${item.quantity ?? 1}"
                       aria-label="Quantity"
                       required>
            </td>

            <td>
                <input type="number"
                       name="items[${index}][issued_quantity]"
                       class="form-control form-control-sm"
                       min="0"
                       step="1"
                       value="${item.issued_quantity ?? 0}"
                       aria-label="Issued quantity">
            </td>

            <td>
                <input type="text"
                       name="items[${index}][remarks]"
                       class="form-control form-control-sm"
                       value="${item.remarks ?? ''}"
                       placeholder="Optional remarks"
                       aria-label="Item remarks">
            </td>

            <td class="text-center">
                <button type="button"
                        class="remove-item"
                        aria-label="Remove supply item"
                        title="Remove item">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        itemsBody.appendChild(row);

        const select = row.querySelector('select');

        if (item.supply_item_id) {
            select.value = item.supply_item_id;
        }

        row.querySelector('.remove-item').addEventListener('click', function () {
            row.remove();
            reindexItems();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Add Manual Item
    |--------------------------------------------------------------------------
    */

    if (addItemBtn) {
        addItemBtn.addEventListener('click', function () {
            const emptyRow = document.getElementById('emptyItemsRow');

            if (emptyRow) {
                emptyRow.remove();
            }

            addItemRow();
            reindexItems();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Reindex Items
    |--------------------------------------------------------------------------
    */

    function reindexItems() {
        const rows = itemsBody.querySelectorAll('tr[data-item-row]');

        rows.forEach(function (row, index) {
            row.querySelectorAll('input, select').forEach(function (input) {
                input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Form Validation
    |--------------------------------------------------------------------------
    */

    if (supplyKitForm) {
        supplyKitForm.addEventListener('submit', function (event) {
            if (!studentId.value) {
                event.preventDefault();
                alert('Please search for and select a student before saving.');
                studentSearch.focus();
                return;
            }

            const rows = itemsBody.querySelectorAll('tr[data-item-row]');

            if (!rows.length) {
                event.preventDefault();
                alert('Please add at least one supply item.');
                addItemBtn.focus();
                return;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Close Student Search When Clicking Outside
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {
        if (
            studentResults &&
            !studentResults.contains(event.target) &&
            !studentSearch.contains(event.target)
        ) {
            studentResults.style.display = 'none';
        }
    });
});
</script>
@endpush
