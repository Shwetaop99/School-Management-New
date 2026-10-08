@extends('layouts.app')

@section('title', 'Issue Student Supply Kit')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Issue Student Supply Kit</h4>
            <p class="text-muted mb-0">
                Assign a government supply kit to a student.
            </p>
        </div>

        <a href="{{ route('admin.student-supply-kits.index') }}"
           class="btn btn-light border">
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>

    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <form method="POST"
          action="{{ route('admin.student-supply-kits.store') }}"
          id="supplyKitForm">

        @csrf

        {{-- Student & Kit Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0">
                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                    Student & Kit Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Student Search --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Search Student <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="studentSearch"
                               class="form-control"
                               placeholder="Search by Student ID or name"
                               autocomplete="off">

                        <input type="hidden"
                               name="student_id"
                               id="studentId"
                               value="{{ old('student_id') }}">

                        <div id="studentResults"
                             class="list-group mt-1"
                             style="display:none;">
                        </div>

                        <small class="text-muted">
                            Start typing Student ID or student name.
                        </small>

                    </div>

                    {{-- Selected Student --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Selected Student
                        </label>

                        <div id="selectedStudent"
                             class="border rounded p-3 bg-light">

                            <span class="text-muted">
                                No student selected
                            </span>

                        </div>

                    </div>

                    {{-- Kit Template --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Kit Template <span class="text-danger">*</span>
                        </label>

                        <select name="kit_template_id"
                                id="kitTemplate"
                                class="form-select"
                                required>

                            <option value="">
                                Select Kit Template
                            </option>

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

                    </div>

                    {{-- Academic Year --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Academic Year <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="academic_year"
                               id="academicYear"
                               class="form-control"
                               value="{{ old('academic_year', '2026-27') }}"
                               required>

                    </div>

                    {{-- Distribution Date --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Distribution Date
                        </label>

                        <input type="date"
                               name="distribution_date"
                               class="form-control"
                               value="{{ old('distribution_date', date('Y-m-d')) }}">

                    </div>

                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="pending"
                                {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="issued"
                                {{ old('status') === 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>

                            <option value="cancelled"
                                {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>

                    {{-- Remarks --}}
                    <div class="col-md-9">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Optional remarks">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        {{-- Kit Items --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h6 class="mb-0">
                        <i class="fas fa-boxes-stacked me-2 text-primary"></i>
                        Supply Kit Items
                    </h6>

                    <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            id="addItemBtn">

                        <i class="fas fa-plus me-1"></i>
                        Add Item

                    </button>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0"
                           id="itemsTable">

                        <thead class="table-light">

                            <tr>
                                <th style="width:35%;">
                                    Supply Item
                                </th>

                                <th style="width:15%;">
                                    Quantity
                                </th>

                                <th style="width:15%;">
                                    Issued Quantity
                                </th>

                                <th>
                                    Remarks
                                </th>

                                <th style="width:60px;">
                                    #
                                </th>
                            </tr>

                        </thead>

                        <tbody id="itemsBody">

                            <tr id="emptyItemsRow">

                                <td colspan="5"
                                    class="text-center text-muted py-4">

                                    Select a kit template to load its items.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.student-supply-kits.index') }}"
               class="btn btn-light border">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fas fa-save me-1"></i>
                Save Supply Kit

            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const studentSearch = document.getElementById('studentSearch');
    const studentResults = document.getElementById('studentResults');
    const studentId = document.getElementById('studentId');
    const selectedStudent = document.getElementById('selectedStudent');

    const kitTemplate = document.getElementById('kitTemplate');
    const academicYear = document.getElementById('academicYear');
    const itemsBody = document.getElementById('itemsBody');
    const addItemBtn = document.getElementById('addItemBtn');
    const supplyKitForm = document.getElementById('supplyKitForm');


    /*
    |--------------------------------------------------------------------------
    | Student Search
    |--------------------------------------------------------------------------
    */

    let searchTimer = null;

    if (studentSearch) {

        studentSearch.addEventListener('input', function () {

            clearTimeout(searchTimer);

            const search = this.value.trim();

            studentId.value = '';

            if (selectedStudent) {
                selectedStudent.innerHTML = '';
            }

            if (search.length < 2) {

                studentResults.innerHTML = '';
                studentResults.style.display = 'none';

                return;
            }

            searchTimer = setTimeout(async function () {

                try {

                    const url =
                        `{{ route('admin.student-supply-kits.students.search') }}?search=${encodeURIComponent(search)}`;

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(
                            `HTTP error: ${response.status}`
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Read as text first because the response may contain UTF-8 BOM
                    |--------------------------------------------------------------------------
                    */

                    const text = await response.text();

                    const cleanText = text.replace(/^\uFEFF/, '');

                    const students = JSON.parse(cleanText);


                    studentResults.innerHTML = '';


                    /*
                    |--------------------------------------------------------------------------
                    | No Students
                    |--------------------------------------------------------------------------
                    */

                    if (!students.length) {

                        studentResults.innerHTML = `
                            <div class="list-group-item text-muted">
                                No students found.
                            </div>
                        `;

                        studentResults.style.display = 'block';

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Show Students
                    |--------------------------------------------------------------------------
                    */

                    students.forEach(function (student) {

                        const fullName = [
                            student.first_name,
                            student.middle_name,
                            student.last_name
                        ]
                        .filter(Boolean)
                        .join(' ');


                        const result = document.createElement('button');

                        result.type = 'button';

                        result.className =
                            'list-group-item list-group-item-action text-start';


                        result.innerHTML = `
                            <div class="fw-semibold">
                                ${fullName}
                            </div>

                            <small class="text-muted">
                                Student ID:
                                ${student.student_id ?? '-'}

                                |

                                Class:
                                ${student.admission_class ?? '-'}

                                ${student.section
                                    ? ' - ' + student.section
                                    : ''
                                }
                            </small>
                        `;


                        /*
                        |--------------------------------------------------------------------------
                        | Select Student
                        |--------------------------------------------------------------------------
                        */

                        result.addEventListener('click', function () {

                            studentId.value = student.id;

                            studentSearch.value = fullName;


                            if (selectedStudent) {

                                selectedStudent.innerHTML = `
                                    <div class="fw-semibold">
                                        ${fullName}
                                    </div>

                                    <small class="text-muted">
                                        Student ID:
                                        ${student.student_id ?? '-'}

                                        <br>

                                        Class:
                                        ${student.admission_class ?? '-'}

                                        ${student.section
                                            ? ' - ' + student.section
                                            : ''
                                        }
                                    </small>
                                `;

                            }


                            studentResults.innerHTML = '';

                            studentResults.style.display = 'none';

                        });


                        studentResults.appendChild(result);

                    });


                    studentResults.style.display = 'block';


                } catch (error) {

                    console.error(
                        'Student search error:',
                        error
                    );


                    studentResults.innerHTML = `
                        <div class="list-group-item text-danger">
                            Unable to search students.
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

            const selectedOption =
                this.options[this.selectedIndex];


            if (
                selectedOption &&
                selectedOption.dataset.academicYear
            ) {

                academicYear.value =
                    selectedOption.dataset.academicYear;

            }


            if (!templateId) {

                itemsBody.innerHTML = `
                    <tr id="emptyItemsRow">
                        <td colspan="5"
                            class="text-center text-muted py-4">

                            Select a kit template to load its items.

                        </td>
                    </tr>
                `;

                return;
            }


            itemsBody.innerHTML = `
                <tr>
                    <td colspan="5"
                        class="text-center py-4">

                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>

                        Loading kit items...

                    </td>
                </tr>
            `;


            fetch(
                `{{ route('admin.student-supply-kits.template.items') }}?kit_template_id=${templateId}`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            )
            .then(async response => {

                if (!response.ok) {
                    throw new Error(
                        `HTTP error: ${response.status}`
                    );
                }

                const text = await response.text();

                return JSON.parse(
                    text.replace(/^\uFEFF/, '')
                );

            })
            .then(items => {

                itemsBody.innerHTML = '';


                if (!items.length) {

                    itemsBody.innerHTML = `
                        <tr>
                            <td colspan="5"
                                class="text-center text-muted py-4">

                                No items found in this kit template.

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

                console.error(
                    'Kit template error:',
                    error
                );


                itemsBody.innerHTML = `
                    <tr>
                        <td colspan="5"
                            class="text-center text-danger py-4">

                            Unable to load kit items.

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

        const index =
            itemsBody.querySelectorAll(
                'tr[data-item-row]'
            ).length;


        const row =
            document.createElement('tr');


        row.setAttribute(
            'data-item-row',
            'true'
        );


        row.innerHTML = `
            <td>

                <select
                    name="items[${index}][supply_item_id]"
                    class="form-select form-select-sm"
                    required
                >

                    <option value="">
                        Select Item
                    </option>

                    @foreach($supplyItems as $supplyItem)

                        <option value="{{ $supplyItem->id }}">

                            {{ $supplyItem->item_name }}

                            @if($supplyItem->item_code)
                                ({{ $supplyItem->item_code }})
                            @endif

                        </option>

                    @endforeach

                </select>

            </td>


            <td>

                <input
                    type="number"
                    name="items[${index}][quantity]"
                    class="form-control form-control-sm"
                    min="1"
                    value="${item.quantity ?? 1}"
                    required
                >

            </td>


            <td>

                <input
                    type="number"
                    name="items[${index}][issued_quantity]"
                    class="form-control form-control-sm"
                    min="0"
                    value="${item.issued_quantity ?? 0}"
                >

            </td>


            <td>

                <input
                    type="text"
                    name="items[${index}][remarks]"
                    class="form-control form-control-sm"
                    value="${item.remarks ?? ''}"
                    placeholder="Optional"
                >

            </td>


            <td class="text-center">

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger remove-item"
                >

                    <i class="fas fa-trash"></i>

                </button>

            </td>
        `;


        itemsBody.appendChild(row);


        const select =
            row.querySelector('select');


        if (item.supply_item_id) {

            select.value =
                item.supply_item_id;

        }


        row
            .querySelector('.remove-item')
            .addEventListener('click', function () {

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

            const emptyRow =
                document.getElementById('emptyItemsRow');


            if (emptyRow) {
                emptyRow.remove();
            }


            addItemRow();

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Reindex Items
    |--------------------------------------------------------------------------
    */

    function reindexItems() {

        const rows =
            itemsBody.querySelectorAll(
                'tr[data-item-row]'
            );


        rows.forEach(function (row, index) {

            row
                .querySelectorAll('input, select')
                .forEach(function (input) {

                    input.name =
                        input.name.replace(
                            /items\[\d+\]/,
                            `items[${index}]`
                        );

                });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Form Validation
    |--------------------------------------------------------------------------
    */

    if (supplyKitForm) {

        supplyKitForm.addEventListener(
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


                const rows =
                    itemsBody.querySelectorAll(
                        'tr[data-item-row]'
                    );


                if (!rows.length) {

                    event.preventDefault();

                    alert(
                        'Please add at least one supply item.'
                    );

                    return;
                }

            }
        );

    }

});
</script>
@endpush