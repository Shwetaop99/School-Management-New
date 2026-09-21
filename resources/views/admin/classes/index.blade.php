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
       PAGE HEADER
    ========================================= */

    .class-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 22px;
    }

    .class-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .class-heading-icon {
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

    .class-page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #26344a;
    }

    .class-page-header p {
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
       DASHBOARD STYLE SUMMARY CARDS
    ========================================= */

    .class-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    .summary-card {
        position: relative;
        overflow: hidden;
        min-height: 112px;
        border-radius: 10px;
        padding: 18px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 6px 16px rgba(25, 55, 95, .08);
        transition: all .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(25, 55, 95, .13);
    }

    .summary-card::before {
        content: "";
        position: absolute;
        width: 95px;
        height: 95px;
        border-radius: 50%;
        right: -28px;
        top: -35px;
        background: rgba(255,255,255,.10);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        right: 28px;
        bottom: -38px;
        background: rgba(255,255,255,.07);
    }

    /* Dashboard Blue */
    .summary-blue {
        background: linear-gradient(135deg, #147cf5, #1268ca);
    }

    /* Dashboard Orange */
    .summary-orange {
        background: linear-gradient(135deg, #ffb238, #ff9d1c);
    }

    /* Dashboard Red */
    .summary-red {
        background: linear-gradient(135deg, #ff6d61, #f65343);
    }

    /* Dashboard Cyan */
    .summary-cyan {
        background: linear-gradient(135deg, #2bcfe8, #18b5d5);
    }

    .summary-icon {
        position: relative;
        z-index: 2;
        width: 46px;
        height: 46px;
        border-radius: 10px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.16);
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
        opacity: .90;
        margin-bottom: 5px;
    }

    .summary-content strong {
        display: block;
        font-size: 24px;
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
       TABLE CARD
    ========================================= */

    .class-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(25, 55, 95, .025);
    }

    .class-card-header {
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

    .class-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
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
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: #eef5ff;
        color: #147cf5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
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
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid transparent;
        font-size: 14px;
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
            align-items: flex-start;
            flex-direction: column;
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
</style>


<div class="class-page">

    {{-- =========================================
         PAGE HEADER
    ========================================== --}}

    <div class="class-page-header">

        <div class="class-heading">

            <div class="class-heading-icon">
                🏫
            </div>

            <div>

                <h2>Classes</h2>

                <p>
                    Manage school classes, sections and academic years.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.classes.create') }}"
            class="btn-primary-custom"
        >
            <span class="btn-plus">＋</span>
            Add Class
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
         DASHBOARD SUMMARY CARDS
    ========================================== --}}

    <div class="class-summary">

        {{-- Total Classes --}}
        <div class="summary-card summary-blue">

            <div class="summary-icon">
                🏫
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
                ✓
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
                !
            </div>

            <div class="summary-content">

                <span>Inactive Classes</span>

                <strong>
                    {{ $classes->where('status', false)->count() }}
                </strong>

            </div>

        </div>


        {{-- Sections --}}
        <div class="summary-card summary-cyan">

            <div class="summary-icon">
                ▦
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
                                            🏫
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

                                        <a
                                            href="{{ route('admin.classes.show', $class->id) }}"
                                            class="action-btn action-view"
                                            title="View Class"
                                            aria-label="View Class"
                                        >
                                            👁
                                        </a>


                                        <a
                                            href="{{ route('admin.classes.edit', $class->id) }}"
                                            class="action-btn action-edit"
                                            title="Edit Class"
                                            aria-label="Edit Class"
                                        >
                                            ✎
                                        </a>


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
                    🏫
                </div>

                <h4>No Classes Found</h4>

                <p>
                    Start by adding your first school class.
                </p>

                <a
                    href="{{ route('admin.classes.create') }}"
                    class="btn-primary-custom"
                >
                    <span class="btn-plus">＋</span>
                    Add First Class
                </a>

            </div>

        @endif

    </div>

</div>

@endsection

