@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div class="d-flex align-items-center">

        <div class="page-icon me-3">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0">
                    Exam Classes
                </h4>

                <span class="badge rounded-pill bg-primary-subtle text-primary">
                    {{ $classes->count() }} Classes
                </span>
            </div>

            <div class="text-muted small">
                Configure which classes will participate in this examination.
            </div>
        </div>

    </div>

    <a href="{{ route('admin.exams.show', $exam) }}"
       class="btn btn-outline-secondary px-3">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Exam
    </a>

</div>


{{-- =========================================================
    EXAM INFORMATION
========================================================== --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="info-card h-100">

            <div class="info-icon bg-primary-subtle text-primary">
                <i class="bi bi-journal-text"></i>
            </div>

            <div>
                <div class="small text-muted mb-1">
                    Examination
                </div>

                <div class="fw-semibold text-dark">
                    {{ $exam->exam_name }}
                </div>
            </div>

        </div>
    </div>


    <div class="col-md-4">
        <div class="info-card h-100">

            <div class="info-icon bg-success-subtle text-success">
                <i class="bi bi-calendar3"></i>
            </div>

            <div>
                <div class="small text-muted mb-1">
                    Academic Year
                </div>

                <div class="fw-semibold text-dark">
                    {{ $exam->academic_year }}
                </div>
            </div>

        </div>
    </div>


    <div class="col-md-4">
        <div class="info-card h-100">

            <div class="info-icon bg-warning-subtle text-warning">
                <i class="bi bi-check2-square"></i>
            </div>

            <div>
                <div class="small text-muted mb-1">
                    Selected Classes
                </div>

                <div class="fw-semibold text-dark">
                    <span id="selectedCount">
                        {{ count($selectedClassIds) }}
                    </span>
                    of {{ $classes->count() }}
                </div>
            </div>

        </div>
    </div>

</div>


{{-- =========================================================
    FLASH SUCCESS
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4"
         role="alert">

        <div class="alert-icon me-3">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <div>
            <div class="fw-semibold">
                Successfully saved
            </div>

            <div class="small">
                {{ session('success') }}
            </div>
        </div>

    </div>

@endif


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm mb-4"
         role="alert">

        <div class="d-flex align-items-start">

            <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>

            <div>
                <div class="fw-semibold mb-1">
                    Please fix the following:
                </div>

                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>

@endif


{{-- =========================================================
    MAIN CARD
========================================================== --}}
<div class="card border-0 shadow-sm exam-class-card">

    {{-- Card Header --}}
    <div class="card-header bg-white border-bottom px-4 py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
                    Available Classes
                </h5>

                <div class="text-muted small">
                    Select the classes that should be included in this examination.
                </div>
            </div>


            @if($classes->count())

                <div class="selection-summary">

                    <span class="text-muted small me-2">
                        Selected
                    </span>

                    <span class="badge bg-primary rounded-pill px-3"
                          id="selectedBadge">
                        {{ count($selectedClassIds) }}
                    </span>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
        FORM
    ====================================================== --}}
    <form method="POST"
          action="{{ route('admin.exam-classes.store', $exam) }}">

        @csrf

        <div class="card-body p-0">

            @if($classes->count())

                {{-- =================================================
                    SELECTION TOOLBAR
                ================================================== --}}
                <div class="selection-toolbar px-4 py-3">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <div class="form-check custom-select-all mb-0">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="selectAll"
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="selectAll"
                            >
                                Select All Classes
                            </label>

                        </div>


                        <div class="small text-muted">

                            <i class="bi bi-info-circle me-1"></i>

                            Select one or more classes to continue.

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TABLE
                ================================================== --}}
                <div class="table-responsive">

                    <table class="table exam-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="select-column">
                                    <span class="visually-hidden">
                                        Select
                                    </span>
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


                        <tbody>

                            @foreach($classes as $class)

                                <tr class="class-row">

                                    {{-- Select --}}
                                    <td>

                                        <div class="form-check class-check-wrapper">

                                            <input
                                                type="checkbox"
                                                name="class_ids[]"
                                                value="{{ $class->id }}"
                                                class="form-check-input class-checkbox"
                                                {{ in_array($class->id, $selectedClassIds) ? 'checked' : '' }}
                                            >

                                        </div>

                                    </td>


                                    {{-- Class --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="class-avatar">
                                                <i class="bi bi-mortarboard"></i>
                                            </div>

                                            <div>

                                                <div class="fw-semibold text-dark">
                                                    {{ $class->class_name }}
                                                </div>

                                                <div class="small text-muted">
                                                    Class ID: {{ $class->id }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Section --}}
                                    <td>

                                        <span class="section-badge">

                                            <i class="bi bi-people me-1"></i>

                                            {{ $class->section }}

                                        </span>

                                    </td>


                                    {{-- Academic Year --}}
                                    <td>

                                        <div class="d-flex align-items-center text-muted">

                                            <i class="bi bi-calendar3 me-2"></i>

                                            {{ $class->academic_year }}

                                        </div>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($class->status)

                                            <span class="status-badge active">

                                                <span class="status-dot"></span>

                                                Active

                                            </span>

                                        @else

                                            <span class="status-badge inactive">

                                                <span class="status-dot"></span>

                                                Inactive

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}
                <div class="empty-state py-5 px-4 text-center">

                    <div class="empty-icon mx-auto mb-3">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">
                        No Classes Available
                    </h5>

                    <p class="text-muted mb-0">
                        No active classes were found for
                        <strong>{{ $exam->academic_year }}</strong>.
                    </p>

                </div>

            @endif

        </div>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}
        @if($classes->count())

            <div class="card-footer bg-white border-top px-4 py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="small text-muted">

                        <i class="bi bi-shield-check text-success me-1"></i>

                        Your class selection will be saved for this examination.

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary save-btn px-4"
                        id="saveButton"
                    >

                        <i class="bi bi-check2-circle me-2"></i>

                        Save & Continue

                    </button>

                </div>

            </div>

        @endif

    </form>

</div>

</div>

{{-- =============================================================
STYLES
============================================================= --}}

<style>

    /* ---------------------------------------------------------
       PAGE ICON
    --------------------------------------------------------- */

    .page-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }


    /* ---------------------------------------------------------
       INFO CARDS
    --------------------------------------------------------- */

    .info-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
    }

    .info-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }


    /* ---------------------------------------------------------
       MAIN CARD
    --------------------------------------------------------- */

    .exam-class-card {
        border-radius: 14px;
        overflow: hidden;
    }


    /* ---------------------------------------------------------
       SELECTION TOOLBAR
    --------------------------------------------------------- */

    .selection-toolbar {
        background: #f8fafc;
        border-bottom: 1px solid #edf0f3;
    }

    .custom-select-all .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0;
        cursor: pointer;
    }

    .custom-select-all .form-check-label {
        margin-left: 5px;
        cursor: pointer;
        color: #212529;
    }


    /* ---------------------------------------------------------
       TABLE
    --------------------------------------------------------- */

    .exam-table thead th {
        background: #f8f9fa;
        border-bottom: 1px solid #e5e7eb;
        color: #6c757d;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 15px 20px;
        white-space: nowrap;
    }

    .exam-table tbody td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f1f3;
    }

    .exam-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .class-row {
        transition: background 0.15s ease;
    }

    .class-row:hover {
        background: #f8fbff;
    }

    .class-row.selected {
        background: #f0f7ff;
    }


    /* ---------------------------------------------------------
       CHECKBOX
    --------------------------------------------------------- */

    .select-column {
        width: 65px;
    }

    .class-check-wrapper {
        margin: 0;
    }

    .class-checkbox,
    #selectAll {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }


    /* ---------------------------------------------------------
       CLASS AVATAR
    --------------------------------------------------------- */

    .class-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #eef5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 17px;
        flex-shrink: 0;
    }


    /* ---------------------------------------------------------
       SECTION BADGE
    --------------------------------------------------------- */

    .section-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 7px;
        background: #f8f9fa;
        border: 1px solid #e3e6e9;
        color: #495057;
        font-size: 13px;
        font-weight: 600;
    }


    /* ---------------------------------------------------------
       STATUS
    --------------------------------------------------------- */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.active {
        background: #eaf8f0;
        color: #198754;
    }

    .status-badge.inactive {
        background: #f1f3f5;
        color: #6c757d;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }


    /* ---------------------------------------------------------
       SELECTION SUMMARY
    --------------------------------------------------------- */

    .selection-summary {
        display: flex;
        align-items: center;
    }


    /* ---------------------------------------------------------
       SAVE BUTTON
    --------------------------------------------------------- */

    .save-btn {
        min-height: 42px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .save-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(13, 110, 253, 0.20);
    }


    /* ---------------------------------------------------------
       EMPTY STATE
    --------------------------------------------------------- */

    .empty-state {
        min-height: 300px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }


    /* ---------------------------------------------------------
       ALERT ICON
    --------------------------------------------------------- */

    .alert-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: rgba(25, 135, 84, 0.10);
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }


    /* ---------------------------------------------------------
       MOBILE
    --------------------------------------------------------- */

    @media (max-width: 767.98px) {

        .container-fluid {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        .page-icon {
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .info-card {
            padding: 15px;
        }

        .exam-table {
            min-width: 760px;
        }

        .card-footer .save-btn {
            width: 100%;
        }

    }

</style>

{{-- =============================================================
JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const selectAll = document.getElementById('selectAll');

    const checkboxes = document.querySelectorAll(
        '.class-checkbox'
    );

    const selectedCount = document.getElementById(
        'selectedCount'
    );

    const selectedBadge = document.getElementById(
        'selectedBadge'
    );

    const rows = document.querySelectorAll(
        '.class-row'
    );


    if (!selectAll || !checkboxes.length) {
        return;
    }


    /* ---------------------------------------------------------
       UPDATE COUNTER
    --------------------------------------------------------- */

    function updateSelection() {

        const checkedCount =
            document.querySelectorAll(
                '.class-checkbox:checked'
            ).length;


        /* Counter */

        if (selectedCount) {
            selectedCount.textContent = checkedCount;
        }


        /* Badge */

        if (selectedBadge) {
            selectedBadge.textContent = checkedCount;
        }


        /* Select all */

        selectAll.checked =
            checkedCount === checkboxes.length;


        selectAll.indeterminate =
            checkedCount > 0 &&
            checkedCount < checkboxes.length;


        /* Highlight selected rows */

        checkboxes.forEach(function (checkbox, index) {

            if (rows[index]) {

                rows[index].classList.toggle(
                    'selected',
                    checkbox.checked
                );

            }

        });

    }


    /* ---------------------------------------------------------
       SELECT ALL
    --------------------------------------------------------- */

    selectAll.addEventListener('change', function () {

        checkboxes.forEach(function (checkbox) {

            checkbox.checked =
                selectAll.checked;

        });

        updateSelection();

    });


    /* ---------------------------------------------------------
       INDIVIDUAL CHECKBOX
    --------------------------------------------------------- */

    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            updateSelection
        );

    });


    /* ---------------------------------------------------------
       CLICK ROW TO SELECT
       But don't interfere with links/buttons.
    --------------------------------------------------------- */

    rows.forEach(function (row, index) {

        row.addEventListener('click', function (event) {

            if (
                event.target.closest('input') ||
                event.target.closest('button') ||
                event.target.closest('a')
            ) {
                return;
            }


            if (checkboxes[index]) {

                checkboxes[index].checked =
                    !checkboxes[index].checked;

                updateSelection();

            }

        });

    });


    /* ---------------------------------------------------------
       INITIAL STATE
    --------------------------------------------------------- */

    updateSelection();

});

</script>

@endsection
