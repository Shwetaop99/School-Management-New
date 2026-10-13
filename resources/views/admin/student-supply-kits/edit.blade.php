@extends('layouts.app')

@section('title', 'Edit Student Supply Kit')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Student Supply Kit</h4>
            <p class="text-muted mb-0">
                Update student supply kit details and items.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.student-supply-kits.show', $studentSupplyKit) }}"
               class="btn btn-light border">

                <i class="fas fa-eye me-1"></i>
                View

            </a>

            <a href="{{ route('admin.student-supply-kits.index') }}"
               class="btn btn-light border">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

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
          action="{{ route('admin.student-supply-kits.update', $studentSupplyKit) }}"
          id="supplyKitForm">

        @csrf
        @method('PUT')


        {{-- Student & Kit Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0">

                    <i class="fas fa-user-graduate text-primary me-2"></i>

                    Student & Kit Information

                </h6>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Student Search --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Search Student
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               id="studentSearch"
                               class="form-control"
                               value="{{ trim(
                                   ($studentSupplyKit->student->first_name ?? '') . ' ' .
                                   ($studentSupplyKit->student->middle_name ?? '') . ' ' .
                                   ($studentSupplyKit->student->last_name ?? '')
                               ) }}"
                               placeholder="Search by Student ID or name"
                               autocomplete="off">

                        <input type="hidden"
                               name="student_id"
                               id="studentId"
                               value="{{ old(
                                   'student_id',
                                   $studentSupplyKit->student_id
                               ) }}">

                        <div id="studentResults"
                             class="list-group mt-1"
                             style="display:none;">
                        </div>

                        <small class="text-muted">

                            Start typing to change the student.

                        </small>

                    </div>


                    {{-- Selected Student --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Selected Student
                        </label>

                        <div id="selectedStudent"
                             class="border rounded p-3 bg-light">

                            <div class="fw-semibold">

                                {{ trim(
                                    ($studentSupplyKit->student->first_name ?? '') . ' ' .
                                    ($studentSupplyKit->student->middle_name ?? '') . ' ' .
                                    ($studentSupplyKit->student->last_name ?? '')
                                ) }}

                            </div>

                            <small class="text-muted">

                                Student ID:
                                {{ $studentSupplyKit->student->student_id ?? '-' }}

                                <br>

                                Class:
                                {{ $studentSupplyKit->student->admission_class ?? '-' }}

                                @if($studentSupplyKit->student?->section)
                                    - {{ $studentSupplyKit->student->section }}
                                @endif

                            </small>

                        </div>

                    </div>


                    {{-- Kit Template --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Kit Template
                            <span class="text-danger">*</span>

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
                                        {{ old(
                                            'kit_template_id',
                                            $studentSupplyKit->kit_template_id
                                        ) == $template->id ? 'selected' : '' }}>

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

                            Academic Year
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="academic_year"
                               id="academicYear"
                               class="form-control"
                               value="{{ old(
                                   'academic_year',
                                   $studentSupplyKit->academic_year
                               ) }}"
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
                               value="{{ old(
                                   'distribution_date',
                                   optional(
                                       $studentSupplyKit->distribution_date
                                   )->format('Y-m-d')
                               ) }}">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="pending"
                                {{ old(
                                    'status',
                                    $studentSupplyKit->status
                                ) === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="issued"
                                {{ old(
                                    'status',
                                    $studentSupplyKit->status
                                ) === 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>

                            <option value="cancelled"
                                {{ old(
                                    'status',
                                    $studentSupplyKit->status
                                ) === 'cancelled' ? 'selected' : '' }}>
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
                                  placeholder="Optional remarks">{{ old(
                                      'remarks',
                                      $studentSupplyKit->remarks
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Supply Items --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h6 class="mb-0">

                        <i class="fas fa-boxes-stacked text-primary me-2"></i>

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

                            @forelse($studentSupplyKit->items as $index => $kitItem)

                                <tr data-item-row="true">

                                    <td>

                                        <select name="items[{{ $index }}][supply_item_id]"
                                                class="form-select form-select-sm"
                                                required>

                                            <option value="">
                                                Select Item
                                            </option>

                                            @foreach($supplyItems as $supplyItem)

                                                <option value="{{ $supplyItem->id }}"
                                                    {{ $kitItem->supply_item_id == $supplyItem->id ? 'selected' : '' }}>

                                                    {{ $supplyItem->item_name }}

                                                    @if($supplyItem->item_code)
                                                        ({{ $supplyItem->item_code }})
                                                    @endif

                                                </option>

                                            @endforeach

                                        </select>

                                    </td>


                                    <td>

                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="form-control form-control-sm"
                                               min="1"
                                               value="{{ $kitItem->quantity }}"
                                               required>

                                    </td>


                                    <td>

                                        <input type="number"
                                               name="items[{{ $index }}][issued_quantity]"
                                               class="form-control form-control-sm"
                                               min="0"
                                               value="{{ $kitItem->issued_quantity }}">

                                    </td>


                                    <td>

                                        <input type="text"
                                               name="items[{{ $index }}][remarks]"
                                               class="form-control form-control-sm"
                                               value="{{ $kitItem->remarks }}"
                                               placeholder="Optional">

                                    </td>


                                    <td class="text-center">

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger remove-item">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr id="emptyItemsRow">

                                    <td colspan="5"
                                        class="text-center text-muted py-4">

                                        No items added yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route(
                'admin.student-supply-kits.show',
                $studentSupplyKit
            ) }}"
               class="btn btn-light border">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fas fa-save me-1"></i>

                Update Supply Kit

            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')

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

    const kitTemplate =
        document.getElementById('kitTemplate');

    const academicYear =
        document.getElementById('academicYear');

    const itemsBody =
        document.getElementById('itemsBody');

    const addItemBtn =
        document.getElementById('addItemBtn');


    /*
    |--------------------------------------------------------------------------
    | Student Search
    |--------------------------------------------------------------------------
    */

    let searchTimer = null;

    studentSearch.addEventListener('input', function () {

        clearTimeout(searchTimer);

        const search = this.value.trim();

        if (search.length < 2) {

            studentResults.style.display = 'none';

            return;

        }

        searchTimer = setTimeout(function () {

            fetch(
                `{{ route('admin.student-supply-kits.students.search') }}?search=${encodeURIComponent(search)}`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            )
            .then(response => response.json())
            .then(students => {

                studentResults.innerHTML = '';

                if (!students.length) {

                    studentResults.innerHTML = `
                        <div class="list-group-item text-muted">
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


                    const item =
                        document.createElement('button');

                    item.type = 'button';

                    item.className =
                        'list-group-item list-group-item-action';


                    item.innerHTML = `

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
                                : ''}

                        </small>

                    `;


                    item.addEventListener(
                        'click',
                        function () {

                            studentId.value =
                                student.id;

                            studentSearch.value =
                                fullName;


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
                                        : ''}

                                </small>

                            `;


                            studentResults.style.display =
                                'none';

                        }
                    );


                    studentResults.appendChild(item);

                });


                studentResults.style.display =
                    'block';

            })
            .catch(error => {

                console.error(error);

            });

        }, 300);

    });


    /*
    |--------------------------------------------------------------------------
    | Remove Item
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.remove-item')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    this.closest('tr').remove();

                    reindexItems();

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Kit Template Change
    |--------------------------------------------------------------------------
    */

    kitTemplate.addEventListener('change', function () {
    const templateId = this.value;
    const selectedOption = this.options[this.selectedIndex];

    if (selectedOption.dataset.academicYear) {
        academicYear.value = selectedOption.dataset.academicYear;
    }

    if (!templateId) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Remember existing issued quantities before replacing rows
    |--------------------------------------------------------------------------
    */

    const existingIssuedQuantities = {};

    itemsBody
        .querySelectorAll('tr[data-item-row]')
        .forEach(function (row) {
            const supplyItemSelect =
                row.querySelector('select[name*="[supply_item_id]"]');

            const issuedInput =
                row.querySelector('input[name*="[issued_quantity]"]');

            if (
                supplyItemSelect &&
                issuedInput &&
                supplyItemSelect.value
            ) {
                existingIssuedQuantities[
                    supplyItemSelect.value
                ] = parseInt(
                    issuedInput.value || 0,
                    10
                );
            }
        });

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
    .then(response => response.json())
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

            /*
            |--------------------------------------------------------------------------
            | Preserve previously issued quantity
            |--------------------------------------------------------------------------
            */

            if (
                existingIssuedQuantities[item.supply_item_id] !== undefined
            ) {
                item.issued_quantity =
                    existingIssuedQuantities[item.supply_item_id];
            } else {
                item.issued_quantity = 0;
            }

            addItemRow(item);
        });
    })
    .catch(error => {
        console.error(error);

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

                <select name="items[${index}][supply_item_id]"
                        class="form-select form-select-sm"
                        required>

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

                <input type="number"
                       name="items[${index}][quantity]"
                       class="form-control form-control-sm"
                       min="1"
                       value="${item.quantity ?? 1}"
                       required>

            </td>


            <td>

                <input type="number"
                       name="items[${index}][issued_quantity]"
                       class="form-control form-control-sm"
                       min="0"
                       value="${item.issued_quantity ?? 0}">

            </td>


            <td>

                <input type="text"
                       name="items[${index}][remarks]"
                       class="form-control form-control-sm"
                       value="${item.remarks ?? ''}"
                       placeholder="Optional">

            </td>


            <td class="text-center">

                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-item">

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


        row.querySelector('.remove-item')
            .addEventListener(
                'click',
                function () {

                    row.remove();

                    reindexItems();

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Add Manual Item
    |--------------------------------------------------------------------------
    */

    addItemBtn.addEventListener(
        'click',
        function () {

            const emptyRow =
                document.getElementById(
                    'emptyItemsRow'
                );


            if (emptyRow) {

                emptyRow.remove();

            }


            addItemRow();

        }
    );


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

            row.querySelectorAll(
                'input, select'
            )
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

    document
        .getElementById('supplyKitForm')
        .addEventListener(
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

});

</script>

@endpush