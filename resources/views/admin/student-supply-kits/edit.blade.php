```blade
@extends('layouts.app')

@section('title', 'Edit Student Supply Kit')

@section('content')

<div class="container-fluid py-4 supply-kit-page">

    {{-- PAGE HEADER --}}
    <div class="supply-page-header mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="supply-header-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="supply-eyebrow">SUPPLY MANAGEMENT</div>
                    <h3 class="supply-page-title mb-1">Edit Student Supply Kit</h3>
                    <p class="mb-0">
                        Update student details, distribution status and supplied items.
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 supply-header-actions">
                <a href="{{ route('admin.student-supply-kits.show', $studentSupplyKit) }}"
                   class="btn supply-btn-light">
                    <i class="bi bi-eye me-1"></i> View Kit
                </a>

                <a href="{{ route('admin.student-supply-kits.index') }}"
                   class="btn supply-btn-outline">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show supply-alert" role="alert">
            <div class="d-flex gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    <strong>Please check the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.student-supply-kits.update', $studentSupplyKit) }}"
          id="supplyKitForm">
        @csrf
        @method('PUT')

        {{-- STUDENT AND KIT INFORMATION --}}
        <div class="supply-panel mb-4">
            <div class="supply-panel-heading">
                <div class="d-flex align-items-center gap-3">
                    <div class="supply-section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Student & Kit Information</h5>
                        <small>Update the student, template and distribution details.</small>
                    </div>
                </div>
                <span class="supply-section-number">01</span>
            </div>

            <div class="supply-panel-body">
                <div class="row g-4">

                    {{-- STUDENT SEARCH --}}
                    <div class="col-12 col-lg-6">
                        <label for="studentSearch" class="supply-label">
                            Search Student <span class="text-danger">*</span>
                        </label>

                        <div class="supply-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text"
                                   id="studentSearch"
                                   class="form-control supply-input"
                                   value="{{ old('student_id') && old('student_id') != $studentSupplyKit->student_id ? '' : trim(($studentSupplyKit->student->first_name ?? '') . ' ' . ($studentSupplyKit->student->middle_name ?? '') . ' ' . ($studentSupplyKit->student->last_name ?? '')) }}"
                                   placeholder="Search by student name or ID..."
                                   autocomplete="off">
                        </div>

                        <input type="hidden"
                               name="student_id"
                               id="studentId"
                               value="{{ old('student_id', $studentSupplyKit->student_id) }}">

                        <div id="studentResults" class="supply-student-results" style="display:none;"></div>

                        <small class="supply-help">
                            <i class="bi bi-info-circle me-1"></i>
                            Type at least 2 characters to find a student.
                        </small>
                    </div>

                    {{-- SELECTED STUDENT --}}
                    <div class="col-12 col-lg-6">
                        <label class="supply-label">Selected Student</label>

                        <div id="selectedStudent" class="selected-student-card">
                            <div class="selected-student-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="selected-student-name">
                                    {{ trim(($studentSupplyKit->student->first_name ?? '') . ' ' . ($studentSupplyKit->student->middle_name ?? '') . ' ' . ($studentSupplyKit->student->last_name ?? '')) ?: 'Student not selected' }}
                                </div>
                                <div class="selected-student-meta">
                                    <span>
                                        <i class="bi bi-person-badge me-1"></i>
                                        ID: {{ $studentSupplyKit->student->student_id ?? '-' }}
                                    </span>
                                    <span>
                                        <i class="bi bi-mortarboard me-1"></i>
                                        Class: {{ $studentSupplyKit->student->admission_class ?? '-' }}@if($studentSupplyKit->student?->section) - {{ $studentSupplyKit->student->section }}@endif
                                    </span>
                                </div>
                            </div>
                            <span class="selected-student-check">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                        </div>
                    </div>

                    {{-- KIT TEMPLATE --}}
                    <div class="col-12 col-lg-6">
                        <label for="kitTemplate" class="supply-label">
                            Kit Template <span class="text-danger">*</span>
                        </label>

                        <select name="kit_template_id"
                                id="kitTemplate"
                                class="form-select supply-input"
                                required>
                            <option value="">Select Kit Template</option>
                            @foreach($kitTemplates as $template)
                                <option value="{{ $template->id }}"
                                        data-academic-year="{{ $template->academic_year }}"
                                        @selected(old('kit_template_id', $studentSupplyKit->kit_template_id) == $template->id)>
                                    {{ $template->kit_name }}
                                    @if($template->class)
                                        - Class {{ $template->class }}
                                    @endif
                                    - {{ $template->academic_year }}
                                </option>
                            @endforeach
                        </select>
                        <small class="supply-help">
                            <i class="bi bi-boxes me-1"></i>
                            Changing the template loads its standard items.
                        </small>
                    </div>

                    {{-- ACADEMIC YEAR --}}
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="academicYear" class="supply-label">
                            Academic Year <span class="text-danger">*</span>
                        </label>
                        <div class="supply-field-icon">
                            <i class="bi bi-calendar3"></i>
                            <input type="text"
                                   name="academic_year"
                                   id="academicYear"
                                   class="form-control supply-input"
                                   value="{{ old('academic_year', $studentSupplyKit->academic_year) }}"
                                   placeholder="e.g. 2026-2027"
                                   required>
                        </div>
                    </div>

                    {{-- DISTRIBUTION DATE --}}
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="distributionDate" class="supply-label">Distribution Date</label>
                        <div class="supply-field-icon">
                            <i class="bi bi-calendar-event"></i>
                            <input type="date"
                                   name="distribution_date"
                                   id="distributionDate"
                                   class="form-control supply-input"
                                   value="{{ old('distribution_date', optional($studentSupplyKit->distribution_date)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="kitStatus" class="supply-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="kitStatus" class="form-select supply-input" required>
                            <option value="pending" @selected(old('status', $studentSupplyKit->status) === 'pending')>
                                Pending
                            </option>
                            <option value="issued" @selected(old('status', $studentSupplyKit->status) === 'issued')>
                                Issued
                            </option>
                            <option value="cancelled" @selected(old('status', $studentSupplyKit->status) === 'cancelled')>
                                Cancelled
                            </option>
                        </select>
                    </div>

                    {{-- REMARKS --}}
                    <div class="col-12 col-lg-9">
                        <label for="kitRemarks" class="supply-label">Remarks</label>
                        <textarea name="remarks"
                                  id="kitRemarks"
                                  class="form-control supply-input supply-textarea"
                                  rows="2"
                                  placeholder="Add distribution notes or other important information...">{{ old('remarks', $studentSupplyKit->remarks) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUPPLY ITEMS --}}
        <div class="supply-panel mb-4">
            <div class="supply-panel-heading supply-items-heading">
                <div class="d-flex align-items-center gap-3">
                    <div class="supply-section-icon">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Supply Kit Items</h5>
                        <small>Manage quantities, issued units and item-level remarks.</small>
                    </div>
                </div>

                <button type="button" class="btn supply-btn-add" id="addItemBtn">
                    <i class="bi bi-plus-lg me-1"></i> Add Item
                </button>
            </div>

            <div class="supply-items-summary">
                <div class="supply-summary-item">
                    <span class="supply-summary-icon"><i class="bi bi-list-check"></i></span>
                    <span>
                        <small>Total item types</small>
                        <strong id="itemCount">{{ $studentSupplyKit->items->count() }}</strong>
                    </span>
                </div>
                <div class="supply-summary-item">
                    <span class="supply-summary-icon"><i class="bi bi-box-seam"></i></span>
                    <span>
                        <small>Total quantity</small>
                        <strong id="totalQuantity">{{ $studentSupplyKit->items->sum('quantity') }}</strong>
                    </span>
                </div>
                <div class="supply-summary-item">
                    <span class="supply-summary-icon"><i class="bi bi-check2-circle"></i></span>
                    <span>
                        <small>Total issued</small>
                        <strong id="totalIssued">{{ $studentSupplyKit->items->sum('issued_quantity') }}</strong>
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table supply-items-table align-middle mb-0" id="itemsTable">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width:35%">Supply Item</th>
                            <th style="width:14%">Quantity</th>
                            <th style="width:16%">Issued Quantity</th>
                            <th>Remarks</th>
                            <th class="text-center" style="width:75px">Remove</th>
                        </tr>
                    </thead>

                    <tbody id="itemsBody">
                        @forelse($studentSupplyKit->items as $index => $kitItem)
                            <tr data-item-row="true">
                                <td>
                                    <select name="items[{{ $index }}][supply_item_id]"
                                            class="form-select supply-input item-select"
                                            required>
                                        <option value="">Select Supply Item</option>
                                        @foreach($supplyItems as $supplyItem)
                                            <option value="{{ $supplyItem->id }}"
                                                @selected($kitItem->supply_item_id == $supplyItem->id)>
                                                {{ $supplyItem->item_name }}@if($supplyItem->item_code) ({{ $supplyItem->item_code }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number"
                                           name="items[{{ $index }}][quantity]"
                                           class="form-control supply-input quantity-input"
                                           min="1"
                                           value="{{ $kitItem->quantity }}"
                                           required>
                                </td>
                                <td>
                                    <input type="number"
                                           name="items[{{ $index }}][issued_quantity]"
                                           class="form-control supply-input issued-input"
                                           min="0"
                                           max="{{ $kitItem->quantity }}"
                                           value="{{ $kitItem->issued_quantity }}">
                                </td>
                                <td>
                                    <input type="text"
                                           name="items[{{ $index }}][remarks]"
                                           class="form-control supply-input"
                                           value="{{ $kitItem->remarks }}"
                                           placeholder="Optional remarks">
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn supply-remove-btn remove-item"
                                            aria-label="Remove supply item"
                                            title="Remove item">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyItemsRow">
                                <td colspan="5" class="text-center py-5">
                                    <div class="supply-empty-icon"><i class="bi bi-inbox"></i></div>
                                    <div class="fw-bold mt-2">No supply items added</div>
                                    <small class="text-muted">Use Add Item or select a kit template.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="supply-items-footer">
                <i class="bi bi-info-circle me-1"></i>
                Issued quantity should not exceed the quantity assigned to the item.
            </div>
        </div>

        {{-- BOTTOM ACTIONS --}}
        <div class="supply-form-footer mb-4">
            <div class="supply-footer-note">
                <i class="bi bi-shield-check"></i>
                <span>
                    <strong>Review before saving</strong>
                    <small>Confirm the student, quantities and distribution status.</small>
                </span>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.student-supply-kits.show', $studentSupplyKit) }}"
                   class="btn supply-btn-cancel">
                    Cancel
                </a>
                <button type="submit" class="btn supply-btn-save" id="saveKitBtn">
                    <i class="bi bi-check2-circle me-1"></i> Update Supply Kit
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.supply-kit-page {
    --supply-green: #176238;
    --supply-green-dark: #0b3b23;
    --supply-green-light: #e8f5eb;
    --supply-border: #dce8dd;
    padding: 28px !important;
    color: #234331;
    background: #faf8f1;
    background-image: repeating-linear-gradient(to bottom, transparent 0, transparent 59px, rgba(22,83,48,.035) 60px);
}

.supply-page-header {
    padding: 25px 26px;
    color: #fff;
    background: linear-gradient(110deg, #0b3b23 0%, #145a34 60%, #1d6840 100%);
    border: 1px solid #1c6038;
    border-radius: 17px;
    box-shadow: 0 8px 22px rgba(15,65,36,.13);
}

.supply-header-icon {
    width: 56px;
    height: 56px;
    flex: 0 0 56px;
    display: grid;
    place-items: center;
    color: #fff;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.24);
    border-radius: 15px;
    font-size: 25px;
}

.supply-eyebrow {
    margin-bottom: 5px;
    color: #9cf2c0;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.4px;
}

.supply-page-title {
    color: #fff;
    font-size: clamp(1.3rem,2vw,1.8rem);
    font-weight: 800;
    letter-spacing: -.4px;
}

.supply-page-header p {
    color: #d7f0df;
    font-size: 13px;
}

.supply-btn-light,
.supply-btn-outline,
.supply-btn-cancel,
.supply-btn-save,
.supply-btn-add {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 41px;
    padding: 9px 15px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    transition: .18s ease;
}

.supply-btn-light {
    color: #14532d;
    background: #fff;
    border: 1px solid #fff;
}

.supply-btn-light:hover { background: #e8f7ec; color: #0b3b23; }

.supply-btn-outline {
    color: #fff;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.4);
}

.supply-btn-outline:hover { color: #104426; background: #fff; }

.supply-panel {
    overflow: hidden;
    background: #fff;
    border: 1px solid #dce8dd;
    border-radius: 15px;
    box-shadow: 0 3px 13px rgba(24,62,37,.045);
}

.supply-panel-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 17px 22px;
    background: #fbfdfb;
    border-bottom: 1px solid #e2ece4;
}

.supply-panel-heading h5 {
    color: #193e28;
    font-size: 14px;
    font-weight: 800;
}

.supply-panel-heading small {
    color: #78877c;
    font-size: 12px;
}

.supply-section-icon {
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    display: grid;
    place-items: center;
    color: #217448;
    background: #e7f4e9;
    border: 1px solid #dcecdf;
    border-radius: 11px;
    font-size: 19px;
}

.supply-section-number {
    color: #b5c8b9;
    font-size: 22px;
    font-weight: 800;
}

.supply-panel-body { padding: 24px; }

.supply-label {
    display: block;
    margin-bottom: 8px;
    color: #244b32;
    font-size: 12px;
    font-weight: 750;
}

.supply-input {
    width: 100%;
    min-height: 43px;
    color: #274533;
    background-color: #fff;
    border: 1px solid #d5e4d8;
    border-radius: 9px;
    font-size: 13px;
}

.supply-input:focus {
    border-color: #318552;
    box-shadow: 0 0 0 3px rgba(35,126,68,.10);
}

.supply-textarea { min-height: 78px; }

.supply-help {
    display: block;
    margin-top: 7px;
    color: #7a8b7e;
    font-size: 11px;
}

.supply-search-wrap,
.supply-field-icon { position: relative; }

.supply-search-wrap > i,
.supply-field-icon > i {
    position: absolute;
    z-index: 2;
    top: 50%;
    left: 13px;
    color: #47865d;
    transform: translateY(-50%);
}

.supply-search-wrap .supply-input,
.supply-field-icon .supply-input { padding-left: 38px; }

.selected-student-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 13px;
    min-height: 78px;
    padding: 13px;
    background: #f5faf5;
    border: 1px solid #d9eadc;
    border-radius: 11px;
}

.selected-student-avatar {
    display: grid;
    place-items: center;
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    color: #176238;
    background: #e1f1e5;
    border-radius: 12px;
    font-size: 21px;
}

.selected-student-name {
    color: #1c5531;
    font-size: 13px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.selected-student-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 5px 13px;
    margin-top: 5px;
    color: #728276;
    font-size: 11px;
}

.selected-student-check {
    margin-left: auto;
    color: #2b8a50;
    font-size: 18px;
}

.supply-student-results {
    position: relative;
    z-index: 20;
    max-height: 260px;
    overflow-y: auto;
    margin-top: 5px;
    background: #fff;
    border: 1px solid #dce8dd;
    border-radius: 10px;
    box-shadow: 0 8px 22px rgba(20,60,35,.12);
}

.supply-student-result {
    display: block;
    width: 100%;
    padding: 12px 14px;
    text-align: left;
    color: #274533;
    background: #fff;
    border: 0;
    border-bottom: 1px solid #edf2ed;
}

.supply-student-result:hover,
.supply-student-result:focus { background: #f0f8f1; }

.supply-student-result strong {
    display: block;
    font-size: 13px;
}

.supply-student-result small {
    display: block;
    margin-top: 4px;
    color: #718176;
    font-size: 11px;
}

.supply-btn-add {
    color: #176238;
    background: #e8f5eb;
    border: 1px solid #cfe4d3;
}

.supply-btn-add:hover { color: #fff; background: #176238; }

.supply-items-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    padding: 17px 22px;
    background: #fff;
    border-bottom: 1px solid #edf2ed;
}

.supply-summary-item {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 150px;
    padding-right: 20px;
}

.supply-summary-icon {
    display: grid;
    place-items: center;
    width: 38px;
    height: 38px;
    color: #217448;
    background: #e8f5eb;
    border-radius: 10px;
    font-size: 17px;
}

.supply-summary-item small,
.supply-summary-item strong { display: block; }

.supply-summary-item small {
    color: #7a8b7e;
    font-size: 11px;
}

.supply-summary-item strong {
    margin-top: 2px;
    color: #164b2b;
    font-size: 17px;
}

.supply-items-table {
    --bs-table-bg: #fff;
    --bs-table-hover-bg: #f5faf5;
}

.supply-items-table thead th {
    padding: 14px 12px;
    color: #42634d;
    background: #f0f7f1;
    border-bottom: 1px solid #dce8dd;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .45px;
    white-space: nowrap;
}

.supply-items-table tbody td {
    padding: 13px 10px;
    border-bottom: 1px solid #edf2ed;
}

.supply-items-table tbody tr:hover > * { background-color: #f8fbf8; }

.supply-items-table .supply-input { min-width: 95px; }

.supply-remove-btn {
    width: 36px;
    height: 36px;
    color: #b42332;
    background: #fff;
    border: 1px solid #f0cdd1;
    border-radius: 9px;
}

.supply-remove-btn:hover {
    color: #fff;
    background: #b42332;
    border-color: #b42332;
}

.supply-items-footer {
    padding: 12px 22px;
    color: #6e8173;
    background: #fbfdfb;
    border-top: 1px solid #e7eee8;
    font-size: 11px;
}

.supply-empty-icon {
    display: inline-grid;
    place-items: center;
    width: 50px;
    height: 50px;
    color: #217448;
    background: #e8f5eb;
    border-radius: 15px;
    font-size: 22px;
}

.supply-form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding: 18px 22px;
    background: #fff;
    border: 1px solid #dce8dd;
    border-radius: 14px;
    box-shadow: 0 3px 13px rgba(24,62,37,.045);
}

.supply-footer-note {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #217448;
}

.supply-footer-note > i { font-size: 24px; }

.supply-footer-note strong,
.supply-footer-note small { display: block; }

.supply-footer-note strong {
    color: #234331;
    font-size: 12px;
}

.supply-footer-note small {
    margin-top: 3px;
    color: #7a8b7e;
    font-size: 11px;
}

.supply-btn-cancel {
    color: #46614e;
    background: #fff;
    border: 1px solid #d5e4d8;
}

.supply-btn-cancel:hover { background: #f1f7f2; }

.supply-btn-save {
    color: #fff;
    background: #176238;
    border: 1px solid #176238;
}

.supply-btn-save:hover {
    color: #fff;
    background: #0e4829;
}

.supply-alert { border-radius: 12px; }

@media (max-width: 767.98px) {
    .supply-kit-page { padding: 14px !important; }
    .supply-page-header { padding: 20px 16px; border-radius: 14px; }
    .supply-header-icon { width: 46px; height: 46px; flex-basis: 46px; font-size: 21px; }
    .supply-header-actions { width: 100%; }
    .supply-header-actions .btn { flex: 1 1 auto; }
    .supply-panel-heading { padding: 15px; }
    .supply-panel-body { padding: 17px; }
    .supply-items-heading { align-items: flex-start; }
    .supply-items-summary { padding: 14px; gap: 14px; }
    .supply-summary-item { min-width: 125px; padding-right: 0; }
    .supply-form-footer { padding: 16px; }
    .supply-form-footer > div:last-child { width: 100%; }
    .supply-form-footer .btn { flex: 1; }
}

@media (prefers-reduced-motion: reduce) {
    .supply-btn-light,
    .supply-btn-outline,
    .supply-btn-cancel,
    .supply-btn-save,
    .supply-btn-add { transition: none; }
}
</style>

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
    const form = document.getElementById('supplyKitForm');

    let searchTimer = null;
    let activeSearchController = null;
    let templateRequestNumber = 0;

    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[char]);

    /*
     * Student search
     */
    studentSearch.addEventListener('input', function () {
        clearTimeout(searchTimer);

        // Prevent accidentally submitting the previous student ID
        // after the user edits the search text without choosing a result.
        studentId.value = '';

        const search = this.value.trim();

        if (search.length < 2) {
            studentResults.style.display = 'none';
            studentResults.innerHTML = '';
            return;
        }

        searchTimer = setTimeout(async function () {
            if (activeSearchController) {
                activeSearchController.abort();
            }

            activeSearchController = new AbortController();

            studentResults.innerHTML =
                '<div class="p-3 text-muted small"><span class="spinner-border spinner-border-sm me-2"></span>Searching students...</div>';
            studentResults.style.display = 'block';

            try {
                const response = await fetch(
                    `{{ route('admin.student-supply-kits.students.search') }}?search=${encodeURIComponent(search)}`,
                    {
                        headers: { 'Accept': 'application/json' },
                        signal: activeSearchController.signal
                    }
                );

                if (!response.ok) {
                    throw new Error('Student search request failed.');
                }

                const students = await response.json();
                studentResults.innerHTML = '';

                if (!Array.isArray(students) || students.length === 0) {
                    studentResults.innerHTML =
                        '<div class="p-3 text-muted small">No students found.</div>';
                    studentResults.style.display = 'block';
                    return;
                }

                students.forEach(function (student) {
                    const fullName = [
                        student.first_name,
                        student.middle_name,
                        student.last_name
                    ].filter(Boolean).join(' ');

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'supply-student-result';

                    const name = document.createElement('strong');
                    name.textContent = fullName || 'Unnamed student';

                    const details = document.createElement('small');
                    details.textContent =
                        `ID: ${student.student_id ?? '-'} | Class: ${student.admission_class ?? '-'}${student.section ? ' - ' + student.section : ''}`;

                    button.append(name, details);

                    button.addEventListener('click', function () {
                        studentId.value = student.id;
                        studentSearch.value = fullName;

                        selectedStudent.innerHTML = `
                            <div class="selected-student-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="selected-student-name">${escapeHtml(fullName || 'Unnamed student')}</div>
                                <div class="selected-student-meta">
                                    <span><i class="bi bi-person-badge me-1"></i>ID: ${escapeHtml(student.student_id ?? '-')}</span>
                                    <span><i class="bi bi-mortarboard me-1"></i>Class: ${escapeHtml(student.admission_class ?? '-')}${student.section ? ' - ' + escapeHtml(student.section) : ''}</span>
                                </div>
                            </div>
                            <span class="selected-student-check"><i class="bi bi-check-circle-fill"></i></span>
                        `;

                        studentResults.style.display = 'none';
                        studentResults.innerHTML = '';
                    });

                    studentResults.appendChild(button);
                });

                studentResults.style.display = 'block';
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error(error);
                    studentResults.innerHTML =
                        '<div class="p-3 text-danger small">Unable to search students. Please try again.</div>';
                    studentResults.style.display = 'block';
                }
            }
        }, 300);
    });

    document.addEventListener('click', function (event) {
        if (!studentResults.contains(event.target) &&
            !studentSearch.contains(event.target)) {
            studentResults.style.display = 'none';
        }
    });

    /*
     * Item row helpers
     */
    function getItemRows() {
        return Array.from(itemsBody.querySelectorAll('tr[data-item-row="true"]'));
    }

    function updateItemSummary() {
        const rows = getItemRows();

        const totalQuantity = rows.reduce((sum, row) => {
            return sum + Math.max(0, Number(row.querySelector('.quantity-input')?.value || 0));
        }, 0);

        const totalIssued = rows.reduce((sum, row) => {
            return sum + Math.max(0, Number(row.querySelector('.issued-input')?.value || 0));
        }, 0);

        document.getElementById('itemCount').textContent = rows.length;
        document.getElementById('totalQuantity').textContent = totalQuantity;
        document.getElementById('totalIssued').textContent = totalIssued;
    }

    function showEmptyItemsRow(message = 'No supply items added yet.') {
        if (getItemRows().length) return;

        itemsBody.innerHTML = `
            <tr id="emptyItemsRow">
                <td colspan="5" class="text-center py-5">
                    <div class="supply-empty-icon"><i class="bi bi-inbox"></i></div>
                    <div class="fw-bold mt-2">${escapeHtml(message)}</div>
                    <small class="text-muted">Use Add Item or select a kit template.</small>
                </td>
            </tr>
        `;
    }

    function reindexItems() {
        getItemRows().forEach(function (row, index) {
            row.querySelectorAll('input, select').forEach(function (input) {
                input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
            });
        });

        updateItemSummary();
        showEmptyItemsRow();
    }

    function addItemRow(item = {}) {
        document.getElementById('emptyItemsRow')?.remove();

        const index = getItemRows().length;
        const row = document.createElement('tr');
        row.setAttribute('data-item-row', 'true');

        row.innerHTML = `
            <td>
                <select name="items[${index}][supply_item_id]"
                        class="form-select supply-input item-select" required>
                    <option value="">Select Supply Item</option>
                    @foreach($supplyItems as $supplyItem)
                        <option value="{{ $supplyItem->id }}">
                            {{ $supplyItem->item_name }}@if($supplyItem->item_code) ({{ $supplyItem->item_code }})@endif
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="items[${index}][quantity]"
                       class="form-control supply-input quantity-input"
                       min="1" value="${escapeHtml(item.quantity ?? 1)}" required>
            </td>
            <td>
                <input type="number" name="items[${index}][issued_quantity]"
                       class="form-control supply-input issued-input"
                       min="0" value="${escapeHtml(item.issued_quantity ?? 0)}">
            </td>
            <td>
                <input type="text" name="items[${index}][remarks]"
                       class="form-control supply-input"
                       value="${escapeHtml(item.remarks ?? '')}"
                       placeholder="Optional remarks">
            </td>
            <td class="text-center">
                <button type="button" class="btn supply-remove-btn remove-item"
                        aria-label="Remove supply item" title="Remove item">
                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        `;

        itemsBody.appendChild(row);

        const select = row.querySelector('.item-select');
        if (item.supply_item_id) {
            select.value = String(item.supply_item_id);
        }

        attachRowEvents(row);
        updateItemSummary();
    }

    function attachRowEvents(row) {
        row.querySelector('.remove-item')?.addEventListener('click', function () {
            row.remove();
            reindexItems();
        });

        row.querySelector('.quantity-input')?.addEventListener('input', function () {
            const issued = row.querySelector('.issued-input');
            issued.max = this.value || '';
            if (this.value !== '' && Number(issued.value) > Number(this.value)) {
                issued.value = this.value;
            }
            updateItemSummary();
        });

        row.querySelector('.issued-input')?.addEventListener('input', function () {
            updateItemSummary();
        });
    }

    getItemRows().forEach(attachRowEvents);
    updateItemSummary();

    addItemBtn.addEventListener('click', function () {
        addItemRow();
        const rows = getItemRows();
        rows[rows.length - 1]?.querySelector('.item-select')?.focus();
    });

    /*
     * Template change: load its items while preserving quantities
     * already issued for matching supply items.
     */
    kitTemplate.addEventListener('change', async function () {
        const templateId = this.value;
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption?.dataset.academicYear) {
            academicYear.value = selectedOption.dataset.academicYear;
        }

        if (!templateId) return;

        const requestNumber = ++templateRequestNumber;
        const previousItems = {};

        getItemRows().forEach(function (row) {
            const supplyItemId = row.querySelector('.item-select')?.value;
            if (!supplyItemId) return;

            previousItems[supplyItemId] = {
                issued_quantity: row.querySelector('.issued-input')?.value || 0,
                remarks: row.querySelector('input[name*="[remarks]"]')?.value || ''
            };
        });

        itemsBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Loading template items...
                </td>
            </tr>
        `;

        try {
            const response = await fetch(
                `{{ route('admin.student-supply-kits.template.items') }}?kit_template_id=${encodeURIComponent(templateId)}`,
                { headers: { 'Accept': 'application/json' } }
            );

            if (!response.ok) throw new Error('Template items request failed.');

            const items = await response.json();

            // Ignore stale results if another template was selected meanwhile.
            if (requestNumber !== templateRequestNumber) return;

            itemsBody.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                showEmptyItemsRow('No items found in this kit template.');
                updateItemSummary();
                return;
            }

            items.forEach(function (item) {
                const previous = previousItems[String(item.supply_item_id)];

                addItemRow({
                    ...item,
                    issued_quantity: previous ? previous.issued_quantity : (item.issued_quantity ?? 0),
                    remarks: previous ? previous.remarks : (item.remarks ?? '')
                });
            });

            reindexItems();
        } catch (error) {
            if (requestNumber !== templateRequestNumber) return;

            console.error(error);
            itemsBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger py-4">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Unable to load template items. Please try again.
                    </td>
                </tr>
            `;
        }
    });

    /*
     * Validate before submitting.
     */
    form.addEventListener('submit', function (event) {
        if (!studentId.value) {
            event.preventDefault();
            alert('Please select a student from the search results.');
            studentSearch.focus();
            return;
        }

        const rows = getItemRows();

        if (!rows.length) {
            event.preventDefault();
            alert('Please add at least one supply item.');
            addItemBtn.focus();
            return;
        }

        const selectedItems = new Set();

        for (const row of rows) {
            const itemSelect = row.querySelector('.item-select');
            const quantityInput = row.querySelector('.quantity-input');
            const issuedInput = row.querySelector('.issued-input');

            const itemId = itemSelect.value;
            const quantity = Number(quantityInput.value);
            const issued = Number(issuedInput.value || 0);

            if (!itemId) {
                event.preventDefault();
                alert('Please select a supply item for every row.');
                itemSelect.focus();
                return;
            }

            if (selectedItems.has(itemId)) {
                event.preventDefault();
                alert('The same supply item is selected more than once. Please keep each item in one row.');
                itemSelect.focus();
                return;
            }
            selectedItems.add(itemId);

            if (!Number.isFinite(quantity) || quantity < 1) {
                event.preventDefault();
                alert('Each item quantity must be at least 1.');
                quantityInput.focus();
                return;
            }

            if (!Number.isFinite(issued) || issued < 0 || issued > quantity) {
                event.preventDefault();
                alert('Issued quantity must be between 0 and the assigned quantity.');
                issuedInput.focus();
                return;
            }
        }

        const saveButton = document.getElementById('saveKitBtn');
        saveButton.disabled = true;
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating Kit...';
    });
});
</script>
@endpush
```