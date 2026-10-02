@extends('layouts.app')

@section('title', 'Classes | Admin')

@section('page-title', 'Classes')

@section('content')

<style>
    /* =========================================
       CLASSES PAGE
    ========================================= */

    .class-page {
        width: 100%;
    }

    /* =========================================
       TRANSPORT-STYLE HERO HEADER
    ========================================= */

    .class-page-header {
        position: relative;
        overflow: hidden;
        min-height: 165px;
        padding: 28px 32px;
        margin-bottom: 22px;
        border-radius: 18px;
        background: linear-gradient(135deg, #1769d1, #159cc7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        box-shadow: 0 8px 22px rgba(23, 105, 209, .15);
    }

    .class-page-header::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        right: -70px;
        top: -110px;
        background: rgba(255, 255, 255, .08);
    }

    .class-page-header::after {
        content: "";
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        right: 150px;
        bottom: -100px;
        background: rgba(255, 255, 255, .06);
    }

    .class-heading {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .class-heading-icon {
        width: 105px;
        height: 105px;
        flex-shrink: 0;
        border-radius: 22px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .22);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 43px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, .08);
    }

    .class-heading-content {
        position: relative;
        z-index: 2;
    }

    .class-page-header h2 {
        margin: 0;
        font-size: 27px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -.3px;
    }

    .class-page-header p {
        margin: 7px 0 0;
        color: rgba(255, 255, 255, .88);
        font-size: 13px;
        line-height: 1.6;
    }

    .class-page-header .header-description {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 10px;
        font-size: 12px;
        color: rgba(255, 255, 255, .78);
    }

    /* =========================================
       ADD BUTTON
    ========================================= */

    .btn-primary-custom {
        position: relative;
        z-index: 3;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        background: #fff;
        color: #1769d1;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 6px 16px rgba(0, 0, 0, .12);
        transition: all .2s ease;
        white-space: nowrap;
    }

    .btn-primary-custom:hover {
        color: #1268ca;
        transform: translateY(-2px);
        box-shadow: 0 9px 20px rgba(0, 0, 0, .16);
    }

    .btn-plus {
        font-size: 13px;
        line-height: 1;
    }

    /* =========================================
       SUMMARY CARDS - TRANSPORT STYLE
    ========================================= */

    .class-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .summary-card {
        position: relative;
        overflow: hidden;
        min-height: 125px;
        padding: 19px 20px;
        border-radius: 15px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 7px 18px rgba(25, 55, 95, .08);
        transition: all .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 11px 24px rgba(25, 55, 95, .13);
    }

    .summary-card::before {
        content: "";
        position: absolute;
        width: 105px;
        height: 105px;
        border-radius: 50%;
        right: -32px;
        top: -38px;
        background: rgba(255, 255, 255, .10);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        right: 30px;
        bottom: -43px;
        background: rgba(255, 255, 255, .07);
    }

    /* Blue */
    .summary-blue {
        background: linear-gradient(135deg, #147cf5, #1268ca);
    }

    /* Orange */
    .summary-orange {
        background: linear-gradient(135deg, #ffb238, #ff9d1c);
    }

    /* Red */
    .summary-red {
        background: linear-gradient(135deg, #ff6d61, #f65343);
    }

    /* Cyan */
    .summary-cyan {
        background: linear-gradient(135deg, #2bcfe8, #18b5d5);
    }

    .summary-icon {
        position: relative;
        z-index: 2;
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        border-radius: 12px;
        background: rgba(255, 255, 255, .18);
        border: 1px solid rgba(255, 255, 255, .17);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .summary-content {
        position: relative;
        z-index: 2;
    }

    .summary-content span {
        display: block;
        font-size: 12px;
        font-weight: 500;
        opacity: .90;
        margin-bottom: 6px;
    }

    .summary-content strong {
        display: block;
        font-size: 26px;
        line-height: 1;
        font-weight: 700;
    }

    /* =========================================
       SUCCESS ALERT
    ========================================= */

    .alert-success-custom {
        margin-bottom: 18px;
        padding: 12px 15px;
        border-radius: 8px;
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
        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================
       TABLE CARD
    ========================================= */

    .class-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(25, 55, 95, .04);
    }

    .class-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e7edf5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .card-title-area {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #147cf5;
        box-shadow: 0 0 0 4px #eef5ff;
    }

    .class-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #26344a;
    }

    .class-count {
        background: #f3f7fc;
        color: #64748b;
        border: 1px solid #e7edf5;
        padding: 6px 11px;
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

    .class-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .class-table th {
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

    .class-table td {
        padding: 14px 16px;
        color: #26344a;
        font-size: 13px;
        border-bottom: 1px solid #edf1f6;
        vertical-align: middle;
    }

    .class-table tbody tr {
        transition: background .18s ease;
    }

    .class-table tbody tr:hover {
        background: #f8fbff;
    }

    .class-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================
       ROW NUMBER
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
       CLASS NAME
    ========================================= */

    .class-name-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .class-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #eef5ff;
        color: #147cf5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .class-name {
        font-weight: 700;
        color: #26344a;
        font-size: 13px;
    }

    /* =========================================
       SECTION
    ========================================= */

    .section-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 28px;
        padding: 0 9px;
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
        border-radius: 7px;
        text-decoration: none;
        border: 1px solid transparent;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s ease;
    }

    .action-view {
        color: #147cf5;
        background: #eef5ff;
        border-color: #dbeaff;
    }

    .action-view:hover {
        background: #147cf5;
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
        color: #147cf5;
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
        .class-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {

        .class-page-header {
            min-height: auto;
            padding: 24px;
            align-items: flex-start;
            flex-direction: column;
        }

        .class-heading {
            width: 100%;
            align-items: center;
        }

        .class-heading-icon {
            width: 78px;
            height: 78px;
            border-radius: 17px;
            font-size: 31px;
        }

        .class-page-header h2 {
            font-size: 22px;
        }

        .class-page-header .btn-primary-custom {
            width: 100%;
        }

        .class-summary {
            grid-template-columns: 1fr;
        }

        .class-card-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {

        .class-page-header {
            padding: 20px;
            border-radius: 14px;
        }

        .class-heading {
            gap: 14px;
        }

        .class-heading-icon {
            width: 65px;
            height: 65px;
            border-radius: 14px;
            font-size: 26px;
        }

        .class-page-header h2 {
            font-size: 20px;
        }

        .class-page-header p {
            font-size: 12px;
        }

        .summary-card {
            min-height: 115px;
        }
    }
</style>


<div class="class-page">

    {{-- =========================================
         TRANSPORT-STYLE PAGE HEADER
    ========================================== --}}

    <div class="class-page-header">

        <div class="class-heading">

            <div class="class-heading-icon">
                <i
                    class="fa-solid fa-building-columns"
                    aria-hidden="true"
                ></i>
            </div>

            <div class="class-heading-content">

                <h2>Classes</h2>

                <p>
                    Manage school classes, sections and academic years.
                </p>

                <div class="header-description">
                    <i
                        class="fa-solid fa-layer-group"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Organize classes and academic sections
                    </span>
                </div>

            </div>

        </div>


        <a
            href="{{ route('admin.classes.create') }}"
            class="btn-primary-custom"
        >
            <span class="btn-plus">
                <i
                    class="fa-solid fa-plus"
                    aria-hidden="true"
                ></i>
            </span>

            Add Class
        </a>

    </div>


    {{-- =========================================
         SUCCESS MESSAGE
    ========================================== --}}

    @if(session('success'))

        <div class="alert-success-custom">

            <span class="success-icon">
                <i
                    class="fa-solid fa-check"
                    aria-hidden="true"
                ></i>
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =========================================
         DASHBOARD SUMMARY CARDS
    ========================================== --}}

    <div class="class-summary">

        {{-- Total Classes --}}

        <div class="summary-card summary-blue">

            <div class="summary-icon">
                <i
                    class="fa-solid fa-building-columns"
                    aria-hidden="true"
                ></i>
            </div>

            <div class="summary-content">

                <span>Total Classes</span>

                <strong>
                    {{ $classes->count() }}
                </strong>

            </div>

        </div>


        {{-- Active Classes --}}

        <div class="summary-card summary-orange">

            <div class="summary-icon">
                <i
                    class="fa-solid fa-circle-check"
                    aria-hidden="true"
                ></i>
            </div>

            <div class="summary-content">

                <span>Active Classes</span>

                <strong>
                    {{ $classes->where('status', true)->count() }}
                </strong>

            </div>

        </div>


        {{-- Inactive Classes --}}

        <div class="summary-card summary-red">

            <div class="summary-icon">
                <i
                    class="fa-solid fa-circle-xmark"
                    aria-hidden="true"
                ></i>
            </div>

            <div class="summary-content">

                <span>Inactive Classes</span>

                <strong>
                    {{ $classes->where('status', false)->count() }}
                </strong>

            </div>

        </div>


        {{-- Total Sections --}}

        <div class="summary-card summary-cyan">

            <div class="summary-icon">
                <i
                    class="fa-solid fa-layer-group"
                    aria-hidden="true"
                ></i>
            </div>

            <div class="summary-content">

                <span>Total Sections</span>

                <strong>
                    {{ $classes->pluck('section')->unique()->count() }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================
         CLASSES TABLE
    ========================================== --}}

    <div class="class-card">

        <div class="class-card-header">

            <div class="card-title-area">

                <span class="card-title-dot"></span>

                <h3>All Classes</h3>

            </div>

            <span class="class-count">

                {{ $classes->count() }}

                {{ $classes->count() == 1 ? 'Class' : 'Classes' }}

            </span>

        </div>


        @if($classes->count())

            <div class="table-wrapper">

                <table class="class-table">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Academic Year</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($classes as $class)

                            <tr>

                                {{-- Number --}}

                                <td>

                                    <span class="row-number">
                                        {{ $loop->iteration }}
                                    </span>

                                </td>


                                {{-- Class --}}

                                <td>

                                    <div class="class-name-wrapper">

                                        <span class="class-icon">

                                            <i
                                                class="fa-solid fa-building-columns"
                                                aria-hidden="true"
                                            ></i>

                                        </span>

                                        <span class="class-name">
                                            {{ $class->class_name }}
                                        </span>

                                    </div>

                                </td>


                                {{-- Section --}}

                                <td>

                                    <span class="section-badge">
                                        {{ $class->section }}
                                    </span>

                                </td>


                                {{-- Academic Year --}}

                                <td>

                                    <span class="academic-year">
                                        {{ $class->academic_year }}
                                    </span>

                                </td>


                                {{-- Status --}}

                                <td>

                                    @if($class->status)

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


                                {{-- Actions --}}

                                <td>

                                    <div class="action-buttons">

                                        {{-- View --}}

                                        <a
                                            href="{{ route('admin.classes.show', $class->id) }}"
                                            class="action-btn action-view"
                                            title="View Class"
                                            aria-label="View Class"
                                        >

                                            <i
                                                class="fa-solid fa-eye"
                                                aria-hidden="true"
                                            ></i>

                                        </a>


                                        {{-- Edit --}}

                                        <a
                                            href="{{ route('admin.classes.edit', $class->id) }}"
                                            class="action-btn action-edit"
                                            title="Edit Class"
                                            aria-label="Edit Class"
                                        >

                                            <i
                                                class="fa-solid fa-pen-to-square"
                                                aria-hidden="true"
                                            ></i>

                                        </a>


                                        {{-- Delete --}}

                                        <form
                                            action="{{ route('admin.classes.destroy', $class->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this class?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn action-delete"
                                                title="Delete Class"
                                                aria-label="Delete Class"
                                            >

                                                <i
                                                    class="fa-solid fa-trash"
                                                    aria-hidden="true"
                                                ></i>

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

                    <i
                        class="fa-solid fa-building-columns"
                        aria-hidden="true"
                    ></i>

                </div>

                <h4>No Classes Found</h4>

                <p>
                    Start by adding your first school class.
                </p>

                <a
                    href="{{ route('admin.classes.create') }}"
                    class="btn-primary-custom"
                >

                    <span class="btn-plus">

                        <i
                            class="fa-solid fa-plus"
                            aria-hidden="true"
                        ></i>

                    </span>

                    Add First Class

                </a>

            </div>

        @endif

    </div>

</div>

@endsection