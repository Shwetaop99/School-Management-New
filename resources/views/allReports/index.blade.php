@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')

<style>
    .reports-page {
        padding: 5px;
    }

    /* =========================
       HEADER
    ========================= */

    .reports-header {
        margin-bottom: 24px;
    }

    .reports-header h2 {
        margin: 0;
        color: #16233e;
        font-size: 24px;
        font-weight: 700;
    }

    .reports-header p {
        margin: 7px 0 0;
        color: #718096;
        font-size: 14px;
    }

    /* =========================
       REPORT GRID
    ========================= */

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    /* =========================
       REPORT CARD
    ========================= */

    .report-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 16px;

        min-height: 145px;
        padding: 22px;

        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 14px;

        text-decoration: none;
        color: inherit;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .report-card:hover {
        transform: translateY(-3px);
        border-color: #c7dcf7;
        box-shadow: 0 10px 28px rgba(30, 55, 90, 0.09);
    }

    /* =========================
       ICON
    ========================= */

    .report-icon {
    width: 56px;
    height: 56px;
    min-width: 56px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    font-size: 22px;

    transition: all 0.2s ease;
}


/* Student */
.report-card:nth-child(1) .report-icon {
    background: #e8f1ff;
    color: #2563eb;
}

/* Faculty */
.report-card:nth-child(2) .report-icon {
    background: #f1e9ff;
    color: #7c3aed;
}

/* Staff */
.report-card:nth-child(3) .report-icon {
    background: #e8f8ef;
    color: #16a34a;
}

/* Timetable */
.report-card:nth-child(4) .report-icon {
    background: #e5f8ff;
    color: #0891b2;
}

/* Attendance */
.report-card:nth-child(5) .report-icon {
    background: #e5f8f4;
    color: #0f9f87;
}

/* Fees */
.report-card:nth-child(6) .report-icon {
    background: #fff3df;
    color: #ea8a00;
}

/* Exam */
.report-card:nth-child(7) .report-icon {
    background: #ffe8e8;
    color: #dc2626;
}

/* Result */
.report-card:nth-child(8) .report-icon {
    background: #f0eaff;
    color: #8b5cf6;
}

/* Library */
.report-card:nth-child(9) .report-icon {
    background: #fff4df;
    color: #b7791f;
}

/* Transport */
.report-card:nth-child(10) .report-icon {
    background: #e5f3ff;
    color: #0284c7;
}

/* Meal */
.report-card:nth-child(11) .report-icon {
    background: #fff9dc;
    color: #ca8a04;
}

/* Payroll */
.report-card:nth-child(12) .report-icon {
    background: #e8f8ee;
    color: #15803d;
}

/* Sports */
.report-card:nth-child(13) .report-icon {
    background: #ffe9e9;
    color: #e11d48;
}

/* Scholarship */
.report-card:nth-child(14) .report-icon {
    background: #f3eaff;
    color: #9333ea;
}

/* Class */
.report-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;

    min-height: 145px;
    padding: 22px;

    background: #ffffff;
    border: 1px solid #e7edf5;
    border-radius: 16px;

    text-decoration: none;
    color: inherit;

    transition: all 0.25s ease;
}

.report-card:hover {
    transform: translateY(-4px);
    border-color: #d5e2f5;
    box-shadow: 0 12px 30px rgba(30, 55, 90, 0.12);
}

.report-card:hover .report-icon {
    transform: scale(1.08);
}

    /* =========================
       CARD CONTENT
    ========================= */

    .report-info {
        flex: 1;
        min-width: 0;
    }

    .report-info h3 {
        margin: 0;

        color: #26344a;
        font-size: 16px;
        font-weight: 700;
    }

    .report-info p {
        margin: 6px 0 0;

        color: #718096;
        font-size: 13px;
        line-height: 1.5;
    }

    /* =========================
       ARROW
    ========================= */

    .report-arrow {
        color: #94a3b8;
        font-size: 14px;

        transition:
            transform 0.2s ease,
            color 0.2s ease;
    }

    .report-card:hover .report-arrow {
        color: #1677f0;
        transform: translateX(3px);
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1100px) {

        .reports-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 650px) {

        .reports-page {
            padding: 0;
        }

        .reports-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .report-card {
            min-height: 125px;
            padding: 18px;
        }

        .reports-header h2 {
            font-size: 21px;
        }

    }
</style>


<div class="reports-page">

    {{-- PAGE HEADER --}}
    <div class="reports-header">

        <h2>
            Reports
        </h2>

        <p>
            Generate and view reports for different areas of the school.
        </p>

    </div>


    {{-- REPORT CARDS --}}
    <div class="reports-grid">


        {{-- 1. STUDENT --}}
        <a href="{{ route('admin.reports.students') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-user-graduate"></i>
            </div>

            <div class="report-info">

                <h3>
                    Student Reports
                </h3>

                <p>
                    Student information, profiles and academic records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 2. FACULTY --}}
        <a href="{{ route('admin.teachers.reports.index') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>

            <div class="report-info">

                <h3>
                    Faculty Reports
                </h3>

                <p>
                    Faculty details, allocation and teaching records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 3. STAFF --}}
        <a href="{{ route('admin.reports.staff') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-users"></i>
            </div>

            <div class="report-info">

                <h3>
                    Staff Reports
                </h3>

                <p>
                    Non-teaching staff and employee information.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 5. ATTENDANCE --}}
        <a href="{{ route('admin.reports.attendance') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-calendar-check"></i>
            </div>

            <div class="report-info">

                <h3>
                    Attendance Reports
                </h3>

                <p>
                    Student and faculty attendance summaries.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 6. FEES --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-indian-rupee-sign"></i>
            </div>

            <div class="report-info">

                <h3>
                    Fee Reports
                </h3>

                <p>
                    Fee collection, payments and pending fees.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 7. EXAM --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-file-alt"></i>
            </div>

            <div class="report-info">

                <h3>
                    Exam Reports
                </h3>

                <p>
                    Examination schedules and examination records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 8. RESULT --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-chart-column"></i>
            </div>

            <div class="report-info">

                <h3>
                    Result Reports
                </h3>

                <p>
                    Marks, grades and student result summaries.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 9. LIBRARY --}}
        <a href="{{ route('admin.library.reports.index') }}" class="report-card">

            <div class="report-icon">
                <i class="fas fa-book"></i>
            </div>

            <div class="report-info">

                <h3>
                    Library Reports
                </h3>

                <p>
                    Books, issues, returns, overdue books and fines.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 10. TRANSPORT --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-bus"></i>
            </div>

            <div class="report-info">

                <h3>
                    Transport Reports
                </h3>

                <p>
                    Routes, vehicles, drivers and student transport.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 11. MEAL --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-utensils"></i>
            </div>

            <div class="report-info">

                <h3>
                    Meal Reports
                </h3>

                <p>
                    Meal stock, usage and inventory records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 12. PAYROLL --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-money-check-dollar"></i>
            </div>

            <div class="report-info">

                <h3>
                    Payroll Reports
                </h3>

                <p>
                    Salary, payroll and employee payment records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 13. SPORTS --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-futbol"></i>
            </div>

            <div class="report-info">

                <h3>
                    Sports Reports
                </h3>

                <p>
                    Sports activities and student participation.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 14. SCHOLARSHIP --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>

            <div class="report-info">

                <h3>
                    Scholarship Reports
                </h3>

                <p>
                    Scholarship applications and student records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        {{-- 15. CLASS --}}
        <a href="#" class="report-card">

            <div class="report-icon">
                <i class="fas fa-chalkboard"></i>
            </div>

            <div class="report-info">

                <h3>
                    Class Reports
                </h3>

                <p>
                    Classes, divisions, subjects and class records.
                </p>

            </div>

            <div class="report-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


    </div>

</div>

@endsection