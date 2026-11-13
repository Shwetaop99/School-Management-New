@extends('layouts.app')

@section('title', 'Scholarship Rules | Admin')
@section('page-title', 'Scholarship Rules')

@section('content')

<style>
.scholarship-rule-page {
    --ink: #263b35;
    --ink-2: #1d302b;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e0d3;
    --paper: #fffefa;
    --chalk: #f4f0df;
    --wood: #a87543;
    --red: #c94f3d;
    --blue: #2b5d8a;
    --green: #3a9d6b;

    max-width: 1500px;
    margin: 0 auto;
    padding: 0 0 30px;
    color: var(--text);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    font-size: 13px;
}

.scholarship-rule-page .rule-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
    padding: 22px 25px;
    border: 1px solid var(--line);
    border-left: 4px solid var(--wood);
    border-radius: 8px;
    background: var(--paper);
}

.scholarship-rule-page .rule-header-content {
    min-width: 0;
}

.scholarship-rule-page .rule-header h2 {
    margin: 0;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 27px;
    font-weight: 700;
    line-height: 1.3;
}

.scholarship-rule-page .rule-header p {
    margin: 7px 0 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.7;
}

.scholarship-rule-page .back-btn {
    display: inline-flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 39px;
    padding: 0 14px;
    border: 1px solid var(--line);
    border-radius: 7px;
    background: var(--paper);
    color: var(--ink);
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition: background .2s ease, border-color .2s ease;
}

.scholarship-rule-page .back-btn:hover {
    border-color: var(--wood);
    background: var(--chalk);
    color: var(--ink);
}

.scholarship-rule-page .alert {
    margin-bottom: 22px;
    padding: 15px 18px;
    border: 1px solid #e8c5bd;
    border-left: 4px solid var(--red);
    border-radius: 8px;
    background: #fff5f2;
    color: #873c32;
    font-size: 12px;
}

.scholarship-rule-page .alert ul {
    padding-left: 20px;
}

.scholarship-rule-page .alert li {
    margin-top: 5px;
}

.scholarship-rule-page .rule-card {
    position: relative;
    margin-bottom: 27px;
    padding: 27px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.scholarship-rule-page .rule-card::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    border-radius: 9px 9px 0 0;
    background: var(--wood);
    content: "";
}

.scholarship-rule-page .rule-card:nth-of-type(2)::before {
    background: var(--blue);
}

.scholarship-rule-page .section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 17px;
    border-bottom: 1px dashed var(--line);
}

.scholarship-rule-page .section-icon {
    display: flex;
    flex: 0 0 40px;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid #e5ddca;
    border-radius: 8px;
    background: var(--chalk);
    color: var(--wood);
    font-size: 16px;
}

.scholarship-rule-page .section-title h5 {
    margin: 0;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
}

.scholarship-rule-page .section-title span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 400;
    line-height: 1.6;
}

.scholarship-rule-page .form-label {
    display: block;
    margin-bottom: 8px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.scholarship-rule-page .required {
    color: var(--red);
}

.scholarship-rule-page .form-control,
.scholarship-rule-page .form-select {
    width: 100%;
    min-height: 44px;
    border: 1px solid #dedbcc;
    border-radius: 7px;
    background-color: var(--paper);
    color: var(--text);
    font-family: inherit;
    font-size: 13px;
    box-shadow: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.scholarship-rule-page .form-control {
    padding: 10px 13px;
}

.scholarship-rule-page .form-select {
    padding: 10px 35px 10px 13px;
}

.scholarship-rule-page .form-control::placeholder {
    color: #a1a59c;
    opacity: 1;
}

.scholarship-rule-page .form-control:focus,
.scholarship-rule-page .form-select:focus {
    border-color: var(--wood);
    background-color: var(--paper);
    box-shadow: 0 0 0 3px rgba(168, 117, 67, .10);
    outline: none;
}

.scholarship-rule-page textarea.form-control {
    min-height: 110px;
    resize: vertical;
    line-height: 1.7;
}

.scholarship-rule-page .input-group {
    display: flex;
    flex-wrap: nowrap;
}

.scholarship-rule-page .input-group .form-control {
    min-width: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.scholarship-rule-page .input-group-text {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
    padding: 0 13px;
    border: 1px solid #dedbcc;
    border-left: 0;
    border-radius: 0 7px 7px 0;
    background: var(--chalk);
    color: var(--wood);
    font-size: 13px;
    font-weight: 800;
}

.scholarship-rule-page .help-text {
    margin-top: 7px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.65;
}

.scholarship-rule-page .rule-preview {
    padding: 21px;
    border: 1px dashed #d9d1bd;
    border-radius: 8px;
    background: #fcfaf3;
}

.scholarship-rule-page .rule-preview h6 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 17px;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 16px;
    font-weight: 700;
    line-height: 1.5;
}

.scholarship-rule-page .rule-preview h6::before {
    flex: 0 0 4px;
    width: 4px;
    height: 20px;
    border-radius: 2px;
    background: var(--wood);
    content: "";
}

.scholarship-rule-page .slab {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 47px;
    margin-bottom: 8px;
    padding: 11px 14px;
    border: 1px solid var(--line);
    border-radius: 7px;
    background: var(--paper);
    color: var(--text);
    font-size: 12px;
    line-height: 1.5;
}

.scholarship-rule-page .slab:last-child {
    margin-bottom: 0;
}

.scholarship-rule-page .slab-percent,
.scholarship-rule-page .slab-scholarship {
    flex-shrink: 0;
    font-size: 12px;
    font-weight: 800;
    text-align: right;
}

.scholarship-rule-page .slab-scholarship {
    color: var(--green);
}

.scholarship-rule-page .slab-percent {
    color: var(--red);
}

.scholarship-rule-page .preview-note {
    margin-top: 13px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.scholarship-rule-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 11px;
    margin-top: 8px;
    padding-top: 22px;
    border-top: 1px dashed var(--line);
}

.scholarship-rule-page .btn-cancel,
.scholarship-rule-page .btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 18px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease;
}

.scholarship-rule-page .btn-cancel {
    border: 1px solid var(--line);
    background: var(--paper);
    color: var(--text);
}

.scholarship-rule-page .btn-cancel:hover {
    border-color: var(--wood);
    background: var(--chalk);
    color: var(--ink);
}

.scholarship-rule-page .btn-save {
    border: 1px solid var(--ink);
    background: var(--ink);
    color: var(--paper);
}

.scholarship-rule-page .btn-save:hover {
    border-color: var(--ink-2);
    background: var(--ink-2);
    color: var(--paper);
}

.scholarship-rule-page .back-btn:focus-visible,
.scholarship-rule-page .btn-cancel:focus-visible,
.scholarship-rule-page .btn-save:focus-visible {
    outline: 3px solid rgba(168, 117, 67, .3);
    outline-offset: 3px;
}

@media (max-width: 768px) {
    .scholarship-rule-page {
        padding-bottom: 20px;
    }

    .scholarship-rule-page .rule-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 15px;
        padding: 20px;
    }

    .scholarship-rule-page .rule-header h2 {
        font-size: 24px;
    }

    .scholarship-rule-page .rule-card {
        padding: 20px 16px;
        margin-bottom: 22px;
    }

    .scholarship-rule-page .section-title {
        align-items: flex-start;
    }

    .scholarship-rule-page .section-title h5 {
        font-size: 17px;
    }

    .scholarship-rule-page .rule-preview {
        padding: 14px;
    }

    .scholarship-rule-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .scholarship-rule-page .btn-cancel,
    .scholarship-rule-page .btn-save {
        width: 100%;
    }
}

@media (max-width: 420px) {
    .scholarship-rule-page .slab {
        align-items: flex-start;
        flex-direction: column;
        gap: 5px;
    }

    .scholarship-rule-page .slab-percent,
    .scholarship-rule-page .slab-scholarship {
        text-align: left;
    }
}
</style>

<div class="scholarship-rule-page">

    <div class="rule-header">
        <div class="rule-header-content">
            <h2>
                <i class="fas fa-award me-2"></i>
                Scholarship Rules
            </h2>
            <p>
                Define scholarship concessions based on students' Annual Exam percentages.
            </p>
        </div>

        <a href="{{ route('admin.scholarship.merit.concession.index') }}"
           class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Scholarship
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>
                <i class="fas fa-exclamation-circle me-1"></i>
                Please check the following:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.scholarship.merit.concession.store') }}"
          method="POST">
        @csrf

        <div class="rule-card">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <h5>Scholarship Rule Details</h5>
                    <span>
                        Set the minimum Annual Exam percentage and scholarship concession.
                    </span>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label for="academic_year" class="form-label">
                        Academic Year <span class="required">*</span>
                    </label>

                    <select id="academic_year" name="academic_year"
                            class="form-select" required>
                        <option value="">Select Academic Year</option>

                        @forelse ($academicYears as $academicYear)
                            <option value="{{ $academicYear }}"
                                {{ old('academic_year') == $academicYear ? 'selected' : '' }}>
                                {{ $academicYear }}
                            </option>
                        @empty
                            <option value="" disabled>
                                No Annual Exam Academic Year Available
                            </option>
                        @endforelse
                    </select>

                    <div class="help-text">
                        Academic years are fetched from existing Annual Exam results.
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">
                        Status <span class="required">*</span>
                    </label>

                    <select id="status" name="status" class="form-select" required>
                        <option value="active"
                            {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    <div class="help-text">
                        Select whether this scholarship rule should be active or inactive.
                    </div>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              class="form-control"
                              placeholder="Enter any additional details about this scholarship rule...">{{ old('description') }}</textarea>

                    <div class="help-text">
                        Optional additional details about the scholarship rule.
                    </div>
                </div>

            </div>
        </div>
        {{-- Configurable Scholarship Concession Slabs --}}
        <div class="rule-card">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h5>Scholarship Concession Slabs</h5>
                    <span>
                        Configure percentage ranges and their scholarship concessions.
                    </span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <div class="help-text">
                    Enter ranges from lowest to highest. Use 0–100 for percentage values.
                </div>
                <button type="button" class="btn-save" id="add-slab">
                    <i class="fas fa-plus me-1"></i> Add Slab
                </button>
            </div>

            <div id="slab-rows">
                @php
                    $initialSlabs = old('slabs', [
                        ['minimum_percentage' => '0', 'maximum_percentage' => '59.99', 'scholarship_percentage' => '0'],
                        ['minimum_percentage' => '60', 'maximum_percentage' => '69.99', 'scholarship_percentage' => '10'],
                        ['minimum_percentage' => '70', 'maximum_percentage' => '79.99', 'scholarship_percentage' => '25'],
                        ['minimum_percentage' => '80', 'maximum_percentage' => '100', 'scholarship_percentage' => '35'],
                    ]);
                @endphp

                @foreach ($initialSlabs as $index => $slab)
                    <div class="slab-editor-row border rounded-3 p-3 mb-3" data-slab-row>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong class="slab-row-title">Slab {{ $loop->iteration }}</strong>
                            <button type="button" class="btn-cancel remove-slab">
                                <i class="fas fa-trash me-1"></i> Remove
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Minimum Exam Percentage (%) <span class="required">*</span></label>
                                <input type="number"
                                       class="form-control slab-minimum"
                                       name="slabs[{{ $index }}][minimum_percentage]"
                                       min="0" max="100" step="0.01"
                                       value="{{ $slab['minimum_percentage'] ?? '' }}"
                                       required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Maximum Exam Percentage (%)</label>
                                <input type="number"
                                       class="form-control slab-maximum"
                                       name="slabs[{{ $index }}][maximum_percentage]"
                                       min="0" max="100" step="0.01"
                                       value="{{ $slab['maximum_percentage'] ?? '' }}"
                                       placeholder="Example: 79.99">
                                <div class="help-text">Leave blank for an open-ended range.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Scholarship Concession (%) <span class="required">*</span></label>
                                <input type="number"
                                       class="form-control slab-scholarship"
                                       name="slabs[{{ $index }}][scholarship_percentage]"
                                       min="0" max="100" step="0.01"
                                       value="{{ $slab['scholarship_percentage'] ?? '' }}"
                                       required>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rule-preview mt-4">
                <h6>
                    <i class="fas fa-eye me-2"></i>
                    Live Scholarship Preview
                </h6>
                <div id="slab-preview-list">
                    <div class="slab">
                        <span>Configure your slabs above</span>
                        <span class="slab-percent">Preview</span>
                    </div>
                </div>
            </div>

            <div class="preview-note mt-3">
                <i class="fas fa-info-circle me-1"></i>
                Every slab is saved separately. Percentage ranges must not overlap, and an
                open-ended range must be the final slab. Review the live preview before saving.
            </div>
        </div>


        <div class="form-actions">
            <a href="{{ route('admin.scholarship.merit.concession.index') }}"
               class="btn-cancel">
                <i class="fas fa-times"></i>
                Cancel
            </a>

            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i>
                Save Scholarship Rule
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rowsContainer = document.getElementById('slab-rows');
    const previewContainer = document.getElementById('slab-preview-list');
    const addButton = document.getElementById('add-slab');

    if (!rowsContainer || !previewContainer || !addButton) {
        return;
    }

    function formatPercentage(value) {
        return Number(value).toLocaleString('en-US', {
            maximumFractionDigits: 2,
            useGrouping: false
        });
    }

    function renumberRows() {
        rowsContainer.querySelectorAll('[data-slab-row]').forEach(function (row, index) {
            row.querySelector('.slab-row-title').textContent = 'Slab ' + (index + 1);

            row.querySelector('.slab-minimum').name =
                'slabs[' + index + '][minimum_percentage]';
            row.querySelector('.slab-maximum').name =
                'slabs[' + index + '][maximum_percentage]';
            row.querySelector('.slab-scholarship').name =
                'slabs[' + index + '][scholarship_percentage]';

            const removeButton = row.querySelector('.remove-slab');
            removeButton.disabled = rowsContainer.querySelectorAll('[data-slab-row]').length <= 1;
        });
    }

    function updatePreview() {
        const rows = Array.from(rowsContainer.querySelectorAll('[data-slab-row]'));
        previewContainer.replaceChildren();

        let validRows = 0;

        rows.forEach(function (row) {
            const minimumInput = row.querySelector('.slab-minimum');
            const maximumInput = row.querySelector('.slab-maximum');
            const scholarshipInput = row.querySelector('.slab-scholarship');

            const minimum = minimumInput.value.trim();
            const maximum = maximumInput.value.trim();
            const scholarship = scholarshipInput.value.trim();

            if (minimum === '' || scholarship === '') {
                return;
            }

            if (!Number.isFinite(Number(minimum)) ||
                !Number.isFinite(Number(scholarship)) ||
                Number(minimum) < 0 || Number(minimum) > 100 ||
                Number(scholarship) < 0 || Number(scholarship) > 100) {
                return;
            }

            if (maximum !== '' &&
                (!Number.isFinite(Number(maximum)) ||
                 Number(maximum) < Number(minimum) ||
                 Number(maximum) > 100)) {
                return;
            }

            const rangeLabel = maximum === ''
                ? formatPercentage(minimum) + '% and above'
                : formatPercentage(minimum) + '% – ' + formatPercentage(maximum) + '%';

            const item = document.createElement('div');
            item.className = 'slab';

            const range = document.createElement('span');
            range.textContent = rangeLabel;

            const concession = document.createElement('span');
            concession.className = 'slab-scholarship';
            concession.textContent = formatPercentage(scholarship) + '% Scholarship';

            item.append(range, concession);
            previewContainer.appendChild(item);
            validRows++;
        });

        if (validRows === 0) {
            const empty = document.createElement('div');
            empty.className = 'slab';
            empty.textContent = 'Enter valid slab percentages to see the preview.';
            previewContainer.appendChild(empty);
        }
    }

    function createSlabRow(index) {
        const row = document.createElement('div');
        row.className = 'slab-editor-row border rounded-3 p-3 mb-3';
        row.setAttribute('data-slab-row', '');

        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong class="slab-row-title">Slab ${index + 1}</strong>
                <button type="button" class="btn-cancel remove-slab">
                    <i class="fas fa-trash me-1"></i> Remove
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Minimum Exam Percentage (%) <span class="required">*</span></label>
                    <input type="number" class="form-control slab-minimum"
                           min="0" max="100" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Maximum Exam Percentage (%)</label>
                    <input type="number" class="form-control slab-maximum"
                           min="0" max="100" step="0.01" placeholder="Example: 79.99">
                    <div class="help-text">Leave blank for an open-ended range.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Scholarship Concession (%) <span class="required">*</span></label>
                    <input type="number" class="form-control slab-scholarship"
                           min="0" max="100" step="0.01" required>
                </div>
            </div>
        `;

        return row;
    }

    addButton.addEventListener('click', function () {
        const count = rowsContainer.querySelectorAll('[data-slab-row]').length;
        rowsContainer.appendChild(createSlabRow(count));
        renumberRows();
        updatePreview();
    });

    rowsContainer.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-slab');

        if (!button) {
            return;
        }

        const rows = rowsContainer.querySelectorAll('[data-slab-row]');

        if (rows.length <= 1) {
            return;
        }

        button.closest('[data-slab-row]').remove();
        renumberRows();
        updatePreview();
    });

    rowsContainer.addEventListener('input', function () {
        updatePreview();
    });

    renumberRows();
    updatePreview();
});
</script>

@endsection
