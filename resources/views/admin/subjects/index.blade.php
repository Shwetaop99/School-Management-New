@extends('layouts.app')

@section('title', 'Subjects | Admin')

@section('page-title', 'Subjects')

@section('content')

<style>

    /* =========================================
       SUBJECT PAGE
    ========================================= */

    .subject-page {
        width: 100%;
    }

    /* =========================================
       PAGE HEADER
    ========================================= */

    .subject-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 22px;
    }

    .subject-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .subject-heading-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        box-shadow: 0 6px 16px rgba(20, 124, 245, .18);
    }

    .subject-page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #26344a;
        letter-spacing: -.2px;
    }

    .subject-page-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    /* =========================================
       ADD BUTTON
    ========================================= */

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #fff;
        border: none;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 5px 12px rgba(20, 124, 245, .16);
        transition: all .2s ease;
    }

    .btn-primary-custom:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(20, 124, 245, .22);
    }

    .btn-plus {
        font-size: 17px;
        line-height: 1;
    }

    /* =========================================
       CLASS FILTER
    ========================================= */

    .subject-filter {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 22px;
        box-shadow: 0 2px 8px rgba(25, 55, 95, .025);
    }

    .subject-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 280px;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #26344a;
    }

    .class-select {
        height: 40px;
        padding: 0 12px;
        border: 1px solid #dfe7f1;
        border-radius: 7px;
        background: #fff;
        color: #26344a;
        font-size: 13px;
        outline: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .class-select:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .10);
    }

    .filter-btn {
        height: 40px;
        padding: 0 16px;
        border: none;
        border-radius: 7px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s ease;
    }

    .filter-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(20, 124, 245, .18);
    }

    .clear-filter-btn {
        height: 40px;
        padding: 0 16px;
        border: 1px solid #dfe7f1;
        border-radius: 7px;
        background: #f8fafc;
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .clear-filter-btn:hover {
        background: #eef5ff;
        border-color: #dbeaff;
        color: #1769d1;
    }

    /* =========================================
       DASHBOARD SUMMARY CARDS
    ========================================= */

    .subject-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    .summary-card {
        position: relative;
        overflow: hidden;
        min-height: 112px;
        padding: 18px;
        border-radius: 10px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 6px 16px rgba(25, 55, 95, .08);
        transition: all .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(25, 55, 95, .14);
    }

    .summary-card::before {
        content: "";
        position: absolute;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        right: -28px;
        top: -38px;
        background: rgba(255, 255, 255, .10);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        right: 30px;
        bottom: -42px;
        background: rgba(255, 255, 255, .07);
    }

    .summary-blue {
        background: linear-gradient(135deg, #147cf5, #1268ca);
    }

    .summary-orange {
        background: linear-gradient(135deg, #ffb238, #ff9d1c);
    }

    .summary-red {
        background: linear-gradient(135deg, #ff6d61, #f65343);
    }

    .summary-cyan {
        background: linear-gradient(135deg, #2bcfe8, #18b5d5);
    }

    .summary-icon {
        position: relative;
        z-index: 2;
        width: 46px;
        height: 46px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .18);
        border: 1px solid rgba(255, 255, 255, .16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .summary-content {
        position: relative;
        z-index: 2;
    }

    .summary-content span {
        display: block;
        font-size: 12px;
        font-weight: 500;
        opacity: .92;
        margin-bottom: 5px;
    }

    .summary-content strong {
        display: block;
        font-size: 25px;
        line-height: 1;
        font-weight: 700;
    }

    /* =========================================
       SUCCESS ALERT
    ========================================= */

    .alert-success-custom {
        margin-bottom: 18px;
        padding: 12px 15px;
        border-radius: 7px;
        background: #eaf8ef;
        border: 1px solid #ccebd7;
        color: #198754;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .success-icon {
        width: 23px;
        height: 23px;
        border-radius: 50%;
        background: #198754;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }

    /* =========================================
       MAIN TABLE CARD
    ========================================= */

    .subject-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(25, 55, 95, .025);
    }

    .subject-card-header {
        padding: 17px 19px;
        border-bottom: 1px solid #e7edf5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .card-title-area {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .card-title-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #147cf5;
        box-shadow: 0 0 0 4px #eef5ff;
    }

    .subject-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #26344a;
    }

    .subject-count {
        background: #f3f7fc;
        color: #64748b;
        border: 1px solid #e7edf5;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    /* =========================================
       TABLE
    ========================================= */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .subject-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .subject-table th {
        background: #f8fafc;
        color: #718096;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
        text-align: left;
        padding: 13px 16px;
        border-bottom: 1px solid #e7edf5;
        white-space: nowrap;
    }

    .subject-table td {
        padding: 14px 16px;
        color: #26344a;
        font-size: 13px;
        border-bottom: 1px solid #edf1f6;
        vertical-align: middle;
    }

    .subject-table tbody tr {
        transition: background .18s ease;
    }

    .subject-table tbody tr:hover {
        background: #f8fbff;
    }

    .subject-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================
       NUMBER
    ========================================= */

    .row-number {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        background: #f4f7fb;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================
       SUBJECT
    ========================================= */

    .subject-name-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .subject-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: #eef5ff;
        color: #147cf5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .subject-name {
        font-weight: 700;
        color: #26344a;
        font-size: 13px;
    }

    /* =========================================
       SUBJECT CODE
    ========================================= */

    .subject-code {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 5px;
        background: #f5f7fa;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #edf1f5;
    }

    /* =========================================
       CLASS
    ========================================= */

    .class-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        background: #eef5ff;
        color: #147cf5;
        font-size: 11px;
        font-weight: 600;
    }

    /* =========================================
       SECTION
    ========================================= */

    .section-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 29px;
        height: 27px;
        padding: 0 8px;
        border-radius: 6px;
        background: #f4efff;
        color: #6f42c1;
        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================
       ACADEMIC YEAR
    ========================================= */

    .academic-year {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    /* =========================================
       STATUS
    ========================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-active {
        background: #e8f7ee;
        color: #198754;
    }

    .status-active .status-dot {
        background: #198754;
    }

    .status-inactive {
        background: #fcebec;
        color: #dc3545;
    }

    .status-inactive .status-dot {
        background: #dc3545;
    }

    /* =========================================
       ACTION BUTTONS
    ========================================= */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-buttons form {
        margin: 0;
        padding: 0;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid transparent;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s ease;
    }

    .action-view {
        color: #1769d1;
        background: #eef5ff;
        border-color: #dbeaff;
    }

    .action-view:hover {
        background: #1769d1;
        color: #fff;
        transform: translateY(-1px);
    }

    .action-edit {
        color: #6f42c1;
        background: #f4efff;
        border-color: #e8dcff;
    }

    .action-edit:hover {
        background: #6f42c1;
        color: #fff;
        transform: translateY(-1px);
    }

    .action-delete {
        color: #dc3545;
        background: #fff0f1;
        border-color: #ffdadd;
    }

    .action-delete:hover {
        background: #dc3545;
        color: #fff;
        transform: translateY(-1px);
    }

    /* =========================================
       EMPTY STATE
    ========================================= */

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #718096;
    }

    .empty-state-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 15px;
        border-radius: 16px;
        background: #eef5ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .empty-state h4 {
        margin: 0 0 6px;
        color: #26344a;
        font-size: 16px;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0 0 20px;
        font-size: 13px;
        color: #718096;
    }

    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 1100px) {

        .subject-summary {
            grid-template-columns: repeat(2, 1fr);
        }

    }

    @media (max-width: 768px) {

        .subject-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .subject-page-header .btn-primary-custom {
            width: 100%;
        }

        .subject-summary {
            grid-template-columns: 1fr;
        }

        .subject-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .subject-filter-form {
            align-items: stretch;
            flex-direction: column;
        }

        .filter-group {
            min-width: 100%;
        }

        .filter-btn,
        .clear-filter-btn {
            width: 100%;
        }

    }

</style>


<div class="subject-page">

    {{-- =========================================
         PAGE HEADER
    ========================================== --}}

    <div class="subject-page-header">

        <div class="subject-heading">

            <div class="subject-heading-icon">
                📚
            </div>

            <div>

                <h2>Subjects</h2>

                <p>
                    Manage subjects and assign them to school classes.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.subjects.create') }}"
            class="btn-primary-custom"
        >

            <span class="btn-plus">＋</span>

            Add Subject

        </a>

    </div>


    {{-- =========================================
         SUCCESS MESSAGE
    ========================================== --}}

    @if(session('success'))

        <div class="alert-success-custom">

            <span class="success-icon">✓</span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =========================================
         CLASS FILTER
    ========================================== --}}

    <div class="subject-filter">

        <form
            action="{{ route('admin.subjects.index') }}"
            method="GET"
            class="subject-filter-form"
        >

            <div class="filter-group">

                <label
                    for="class_id"
                    class="filter-label"
                >
                    Select Class
                </label>

                <select
                    name="class_id"
                    id="class_id"
                    class="class-select"
                >

                    <option value="">
                        All Classes
                    </option>

                    @foreach($classes as $class)

                        <option
                            value="{{ $class->id }}"
                            {{ request('class_id') == $class->id ? 'selected' : '' }}
                        >
                            Class {{ $class->class_name }}
                            - Section {{ $class->section }}
                            ({{ $class->academic_year }})
                        </option>

                    @endforeach

                </select>

            </div>


            <button
                type="submit"
                class="filter-btn"
            >
                Show Subjects
            </button>


            @if(request()->filled('class_id'))

                <a
                    href="{{ route('admin.subjects.index') }}"
                    class="clear-filter-btn"
                >
                    Clear Filter
                </a>

            @endif

        </form>

    </div>


    {{-- =========================================
         DASHBOARD SUMMARY CARDS
    ========================================== --}}

    <div class="subject-summary">

        {{-- BLUE --}}

        <div class="summary-card summary-blue">

            <div class="summary-icon">
                📚
            </div>

            <div class="summary-content">

                <span>Total Subjects</span>

                <strong>
                    {{ $subjects->count() }}
                </strong>

            </div>

        </div>


        {{-- ORANGE --}}

        <div class="summary-card summary-orange">

            <div class="summary-icon">
                ✓
            </div>

            <div class="summary-content">

                <span>Active Subjects</span>

                <strong>
                    {{ $subjects->where('status', true)->count() }}
                </strong>

            </div>

        </div>


        {{-- RED --}}

        <div class="summary-card summary-red">

            <div class="summary-icon">
                !
            </div>

            <div class="summary-content">

                <span>Inactive Subjects</span>

                <strong>
                    {{ $subjects->where('status', false)->count() }}
                </strong>

            </div>

        </div>


        {{-- CYAN --}}

        <div class="summary-card summary-cyan">

            <div class="summary-icon">
                🏫
            </div>

            <div class="summary-content">

                <span>Assigned Classes</span>

                <strong>
                    {{ $subjects->pluck('class_id')->unique()->count() }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================
         SUBJECT TABLE
    ========================================== --}}

    <div class="subject-card">

        <div class="subject-card-header">

            <div class="card-title-area">

                <span class="card-title-dot"></span>

                <h3>

                    @if(request()->filled('class_id'))

                        @php
                            $selectedClass = $classes->firstWhere('id', request('class_id'));
                        @endphp

                        {{ $selectedClass
                            ? 'Subjects - Class ' . $selectedClass->class_name . ' - Section ' . $selectedClass->section
                            : 'Subjects'
                        }}

                    @else

                        All Subjects

                    @endif

                </h3>

            </div>


            <span class="subject-count">

                {{ $subjects->count() }}

                {{ $subjects->count() == 1 ? 'Subject' : 'Subjects' }}

            </span>

        </div>


        @if($subjects->count())

            <div class="table-wrapper">

                <table class="subject-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Subject</th>

                            <th>Subject Code</th>

                            <th>Class</th>

                            <th>Section</th>

                            <th>Academic Year</th>

                            <th>Status</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($subjects as $subject)

                            <tr>

                                <td>

                                    <span class="row-number">
                                        {{ $loop->iteration }}
                                    </span>

                                </td>


                                <td>

                                    <div class="subject-name-wrapper">

                                        <span class="subject-icon">
                                            📖
                                        </span>

                                        <span class="subject-name">
                                            {{ $subject->subject_name }}
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    @if($subject->subject_code)

                                        <span class="subject-code">
                                            {{ $subject->subject_code }}
                                        </span>

                                    @else

                                        <span class="subject-code">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($subject->schoolClass)

                                        <span class="class-badge">
                                            Class {{ $subject->schoolClass->class_name }}
                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($subject->schoolClass)

                                        <span class="section-badge">
                                            {{ $subject->schoolClass->section }}
                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($subject->schoolClass)

                                        <span class="academic-year">
                                            {{ $subject->schoolClass->academic_year }}
                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($subject->status)

                                        <span class="status-badge status-active">

                                            <span class="status-dot"></span>

                                            Active

                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">

                                            <span class="status-dot"></span>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.subjects.show', $subject->id) }}"
                                            class="action-btn action-view"
                                            title="View Subject"
                                            aria-label="View Subject"
                                        >
                                            👁
                                        </a>


                                        <a
                                            href="{{ route('admin.subjects.edit', $subject->id) }}"
                                            class="action-btn action-edit"
                                            title="Edit Subject"
                                            aria-label="Edit Subject"
                                        >
                                            ✎
                                        </a>


                                        <form
                                            action="{{ route('admin.subjects.destroy', $subject->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this subject?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn action-delete"
                                                title="Delete Subject"
                                                aria-label="Delete Subject"
                                            >
                                                🗑
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-state-icon">
                    📚
                </div>

                <h4>No Subjects Found</h4>

                @if(request()->filled('class_id'))

                    <p>
                        No subjects have been assigned to the selected class yet.
                    </p>

                @else

                    <p>
                        Start by adding your first subject to a school class.
                    </p>

                @endif


                <a
                    href="{{ route('admin.subjects.create') }}"
                    class="btn-primary-custom"
                >

                    <span class="btn-plus">＋</span>

                    Add Subject

                </a>

            </div>

        @endif

    </div>

</div>

@endsection
