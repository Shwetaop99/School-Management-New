@extends('layouts.app')

@section('title', 'Student Attendance')
@section('page-title', 'Student Attendance')

@section('content')

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
>

@php
    /* =========================================================
       MONTH
    ========================================================= */

    $selectedMonth = request(
        'month',
        $month ?? now()->format('Y-m')
    );

    try {
        $currentMonth = \Carbon\Carbon::createFromFormat(
            'Y-m',
            $selectedMonth
        )->startOfMonth();
    } catch (\Throwable $e) {
        $currentMonth = now()->startOfMonth();
        $selectedMonth = $currentMonth->format('Y-m');
    }

    $previousMonth = $currentMonth
        ->copy()
        ->subMonth()
        ->format('Y-m');

    $nextMonth = $currentMonth
        ->copy()
        ->addMonth()
        ->format('Y-m');

    $daysInMonth = $currentMonth->daysInMonth;

    $monthDates = [];

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $monthDates[] = $currentMonth->copy()->day($day);
    }


    /* =========================================================
       FILTERS
    ========================================================= */

    $selectedClass = request(
        'class',
        $selectedClass ?? null
    );

    $selectedSection = request(
        'section',
        $selectedSection ?? null
    );

    $selectedAcademicYear = request(
        'academic_year',
        $selectedAcademicYear ?? null
    );


    /* =========================================================
       STUDENTS
    ========================================================= */

    $studentCollection = collect(
        $students ?? []
    );

    $classOptions = $studentCollection
        ->pluck('class')
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $sectionOptions = $studentCollection
        ->pluck('section')
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $academicYearOptions = $studentCollection
        ->pluck('academic_year')
        ->filter()
        ->unique()
        ->sort()
        ->values();


    /* =========================================================
       ATTENDANCE
    ========================================================= */

    $attendanceMap = $attendanceMap ?? [];


    /* =========================================================
       HOLIDAYS
    ========================================================= */

    $nationalHolidays = [
        '01-26' => 'Republic Day',
        '08-15' => 'Independence Day',
        '10-02' => 'Gandhi Jayanti',
    ];

    $getHolidayName = function ($date) use ($nationalHolidays) {

        if ($date->isSunday()) {
            return 'Sunday Holiday';
        }

        $key = $date->format('m-d');

        return $nationalHolidays[$key] ?? null;
    };


    /* =========================================================
       DAILY ATTENDANCE
    ========================================================= */

    $dailyAttendance = [];

    foreach ($monthDates as $date) {

        $dateKey = $date->format('Y-m-d');

        $holidayName = $getHolidayName($date);

        $present = 0;
        $absent = 0;

        if (!$holidayName) {

            foreach ($studentCollection as $student) {

                $status = strtolower(
                    trim(
                        (string) (
                            $attendanceMap[$student->id][$dateKey]
                            ?? ''
                        )
                    )
                );

                if ($status === 'present') {
                    $present++;
                }

                if ($status === 'absent') {
                    $absent++;
                }
            }
        }

        $dailyAttendance[$dateKey] = [
            'present' => $present,
            'absent' => $absent,
            'total' => $studentCollection->count(),
            'holiday' => $holidayName,
        ];
    }


    /* =========================================================
       WORKING / HOLIDAY DAYS
    ========================================================= */

    $workingDays = collect($monthDates)
        ->reject(function ($date) use ($getHolidayName) {
            return $getHolidayName($date) !== null;
        })
        ->count();

    $holidayDays = collect($monthDates)
        ->filter(function ($date) use ($getHolidayName) {
            return $getHolidayName($date) !== null;
        })
        ->count();


    /* =========================================================
       ROUTES
    ========================================================= */

    $attendanceIndexUrl = \Illuminate\Support\Facades\Route::has(
        'admin.attendance.index'
    )
        ? route('admin.attendance.index')
        : url('/admin/attendance');


    $attendanceStoreUrl = \Illuminate\Support\Facades\Route::has(
        'admin.attendance.store'
    )
        ? route('admin.attendance.store')
        : $attendanceIndexUrl;


    $hasPdfRoute = \Illuminate\Support\Facades\Route::has(
        'admin.attendance.pdf'
    );


    $hasExcelRoute = \Illuminate\Support\Facades\Route::has(
        'admin.attendance.excel'
    );


    $attendancePdfUrl = $hasPdfRoute
        ? route('admin.attendance.pdf')
        : null;


    $attendanceExcelUrl = $hasExcelRoute
        ? route('admin.attendance.excel')
        : null;
@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.attendance-page {
    min-height: calc(100vh - 64px);
    padding: 25px;
    background: #f4f7fb;
}

.attendance-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
}


/* =========================================================
   ALERTS
========================================================= */

.attendance-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px 17px;
    margin-bottom: 18px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
}

.attendance-alert-success {
    color: #16834b;
    background: #eafaf1;
    border: 1px solid #bce8d0;
}

.attendance-alert-danger {
    color: #c92d3d;
    background: #fff0f0;
    border: 1px solid #f1c2c7;
}


/* =========================================================
   HEADER
========================================================= */

.attendance-header {
    margin-bottom: 20px;
    padding: 24px 27px;
    color: #ffffff;
    border-radius: 18px;
    background: linear-gradient(
        135deg,
        #1769d1,
        #6c63ff
    );
    box-shadow: 0 12px 30px rgba(40, 80, 180, 0.16);
}

.attendance-header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.attendance-title-area {
    display: flex;
    align-items: center;
    gap: 15px;
}

.attendance-title-icon {
    width: 57px;
    height: 57px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 15px;
    background: rgba(255,255,255,.18);
    font-size: 27px;
}

.attendance-header h2 {
    margin: 0;
    font-size: 25px;
    font-weight: 750;
}

.attendance-header p {
    margin: 5px 0 0;
    font-size: 13px;
    opacity: .9;
}


/* =========================================================
   HEADER ACTIONS
========================================================= */

.header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
}

.header-btn,
.back-attendance-btn {
    min-height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 9px 15px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: .2s ease;
}

.header-btn:hover,
.back-attendance-btn:hover {
    transform: translateY(-1px);
}

.header-btn {
    border: 0;
}

.header-btn-light,
.back-attendance-btn {
    color: #ffffff;
    border: 1px solid rgba(255,255,255,.28);
    background: rgba(255,255,255,.15);
}

.header-btn-light:hover,
.back-attendance-btn:hover {
    color: #1769d1;
    background: #ffffff;
}

.header-btn-white {
    color: #1769d1;
    background: #ffffff;
    border: 1px solid #ffffff;
}

.header-btn-white:hover {
    color: #125bb8;
    background: #f7f9ff;
}


/* =========================================================
   EXPORT BUTTON
========================================================= */

.header-btn.export-loading {
    opacity: .75;
    pointer-events: none;
}

.header-btn.export-loading i {
    animation: exportSpin .8s linear infinite;
}

@keyframes exportSpin {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}


/* =========================================================
   FILTER CARD
========================================================= */

.filter-card {
    margin-bottom: 18px;
    padding: 20px;
    background: #ffffff;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(30,60,100,.05);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(150px, 1fr));
    gap: 15px;
    align-items: end;
}

.filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #536174;
    font-size: 12px;
    font-weight: 700;
}

.filter-control {
    width: 100%;
    height: 42px;
    padding: 0 12px;
    color: #25344a;
    background: #ffffff;
    border: 1px solid #dce3ed;
    border-radius: 9px;
    outline: none;
    font-size: 13px;
    box-sizing: border-box;
}

.filter-control:focus {
    border-color: #6c63ff;
    box-shadow: 0 0 0 3px rgba(108,99,255,.10);
}

.filter-buttons {
    display: flex;
    gap: 8px;
}

.filter-buttons .filter-control {
    flex: 1;
    min-width: 0;
}

.filter-btn {
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 16px;
    border: 0;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}

.filter-primary {
    color: #ffffff;
    background: #1769d1;
}

.filter-primary:hover {
    background: #125bb8;
}


/* =========================================================
   MONTH NAVIGATION
========================================================= */

.month-card {
    margin-bottom: 18px;
    padding: 14px 18px;
    background: #ffffff;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(30,60,100,.05);
}

.month-navigation {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.month-nav-btn {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1769d1;
    background: #ffffff;
    border: 1px solid #dfe6ef;
    border-radius: 10px;
    text-decoration: none;
    transition: .2s ease;
}

.month-nav-btn:hover {
    background: #f0f5ff;
}

.month-name {
    text-align: center;
}

.month-name strong {
    display: block;
    color: #25344a;
    font-size: 20px;
    font-weight: 800;
}

.month-name span {
    display: block;
    margin-top: 3px;
    color: #7b8798;
    font-size: 12px;
}


/* =========================================================
   INFORMATION CARDS
========================================================= */

.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 18px;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 18px;
    background: #ffffff;
    border: 1px solid #e7edf5;
    border-radius: 15px;
}

.info-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 12px;
    font-size: 20px;
}

.info-blue {
    color: #1769d1;
    background: #eaf3ff;
}

.info-green {
    color: #20a464;
    background: #eafaf1;
}

.info-purple {
    color: #6c63ff;
    background: #f1edff;
}

.info-red {
    color: #c92d3d;
    background: #fff0f0;
}

.info-text small {
    display: block;
    margin-bottom: 3px;
    color: #7b8798;
    font-size: 11px;
}

.info-text strong {
    color: #25344a;
    font-size: 18px;
}


/* =========================================================
   ATTENDANCE CARD
========================================================= */

.attendance-card {
    overflow: hidden;
    background: #ffffff;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    box-shadow: 0 7px 25px rgba(30,60,100,.06);
}

.attendance-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px;
    border-bottom: 1px solid #edf1f6;
}

.attendance-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.attendance-card-title > i {
    color: #1769d1;
    font-size: 19px;
}

.attendance-card-title strong {
    color: #25344a;
    font-size: 16px;
}

.attendance-card-title span {
    margin-left: 7px;
    color: #8994a4;
    font-size: 12px;
}


/* =========================================================
   TABLE
========================================================= */

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.attendance-table {
    width: 100%;
    min-width: 1400px;
    border-collapse: separate;
    border-spacing: 0;
}

.attendance-table th,
.attendance-table td {
    border-right: 1px solid #edf1f6;
    border-bottom: 1px solid #edf1f6;
    text-align: center;
    vertical-align: middle;
}

.attendance-table thead th {
    height: 80px;
    padding: 7px 5px;
    color: #526074;
    background: #f7f9fc;
    font-size: 11px;
    font-weight: 800;
}

.attendance-table tbody td {
    height: 59px;
    padding: 5px 4px;
    font-size: 12px;
}


/* =========================================================
   STICKY COLUMNS
========================================================= */

.student-column {
    min-width: 220px;
    position: sticky;
    left: 0;
    z-index: 5;
    text-align: left !important;
    background: #ffffff;
}

.attendance-table thead .student-column {
    z-index: 9;
    background: #f7f9fc;
}

.roll-column {
    min-width: 70px;
    position: sticky;
    left: 220px;
    z-index: 5;
    background: #ffffff;
}

.attendance-table thead .roll-column {
    z-index: 9;
    background: #f7f9fc;
}

.summary-column {
    min-width: 105px;
    position: sticky;
    right: 0;
    z-index: 5;
    background: #ffffff;
}

.attendance-table thead .summary-column {
    z-index: 9;
    background: #f7f9fc;
}


/* =========================================================
   STUDENT
========================================================= */

.student-info {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-left: 10px;
}

.student-avatar {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
    border-radius: 50%;
    color: #1769d1;
    background: #eaf3ff;
    font-size: 13px;
    font-weight: 800;
}

.student-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.student-name {
    color: #25344a;
    font-size: 12px;
    font-weight: 750;
}

.student-id {
    margin-top: 2px;
    color: #8a95a5;
    font-size: 10px;
}


/* =========================================================
   DATE HEADER
========================================================= */

.date-number {
    display: block;
    color: #25344a;
    font-size: 14px;
    font-weight: 800;
}

.date-day {
    display: block;
    margin-top: 2px;
    color: #8a95a5;
    font-size: 9px;
    text-transform: uppercase;
}

.date-present-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    margin-top: 5px;
    padding: 3px 6px;
    color: #16834b;
    background: #eafaf1;
    border-radius: 6px;
    font-size: 8px;
    font-weight: 800;
}

.date-present-count i {
    font-size: 8px;
}

.date-absent-count {
    display: block;
    margin-top: 2px;
    color: #dc3545;
    font-size: 8px;
    font-weight: 700;
}


/* =========================================================
   HOLIDAY
========================================================= */

.holiday-header {
    color: #c92d3d !important;
    background: #fff0f0 !important;
    border-right-color: #f3c1c6 !important;
    border-bottom-color: #f3c1c6 !important;
}

.holiday-header .date-number,
.holiday-header .date-day {
    color: #c92d3d !important;
}

.holiday-cell {
    background: #fff8f8 !important;
    border-right-color: #f3d0d0 !important;
}

.holiday-label {
    display: block;
    margin-top: 4px;
    color: #c92d3d;
    font-size: 8px;
    font-weight: 800;
    text-transform: uppercase;
}


/* =========================================================
   ATTENDANCE BUTTON
========================================================= */

.attendance-cell {
    position: relative;
}

.attendance-btn {
    width: 34px;
    height: 34px;
    border: 1px solid #dce3ed;
    border-radius: 9px;
    color: #a3adba;
    background: #ffffff;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
    transition: .15s ease;
}

.attendance-btn:hover {
    border-color: #6c63ff;
    transform: scale(1.04);
}

.attendance-btn.present {
    color: #1d9b5b;
    background: #eafaf1;
    border-color: #bde8d0;
}

.attendance-btn.absent {
    color: #dc3545;
    background: #fff0f0;
    border-color: #f3c1c6;
}

.attendance-btn.holiday-disabled {
    opacity: .75;
    cursor: not-allowed;
    pointer-events: none;
}


/* =========================================================
   SUMMARY
========================================================= */

.summary-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
}

.summary-percentage {
    color: #1769d1;
    font-size: 15px;
    font-weight: 800;
}

.summary-counts {
    display: flex;
    gap: 8px;
    font-size: 9px;
    font-weight: 800;
}

.summary-present {
    color: #1d9b5b;
}

.summary-absent {
    color: #dc3545;
}


/* =========================================================
   SAVE BAR
========================================================= */

.save-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 20px;
    background: #fbfcfe;
    border-top: 1px solid #edf1f6;
    flex-wrap: wrap;
}

.legend {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #697587;
    font-size: 11px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 4px;
}

.legend-present {
    background: #eafaf1;
    border: 1px solid #bde8d0;
}

.legend-absent {
    background: #fff0f0;
    border: 1px solid #f3c1c6;
}

.legend-empty {
    background: #ffffff;
    border: 1px solid #dce3ed;
}

.legend-holiday {
    background: #fff0f0;
    border: 1px solid #f3c1c6;
}

.save-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 20px;
    color: #ffffff;
    background: linear-gradient(
        135deg,
        #1769d1,
        #6c63ff
    );
    border: 0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(23,105,209,.20);
}

.save-btn:disabled {
    opacity: .7;
    cursor: not-allowed;
}


/* =========================================================
   EMPTY
========================================================= */

.empty-state {
    padding: 70px 20px;
    text-align: center;
}

.empty-icon {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: #7c8ba1;
    background: #eef3fb;
    border-radius: 18px;
    font-size: 27px;
}

.empty-state h4 {
    margin: 0 0 6px;
    color: #334155;
    font-size: 17px;
}

.empty-state p {
    margin: 0;
    color: #8a95a5;
    font-size: 13px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 1100px) {

    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 700px) {

    .attendance-page {
        padding: 12px;
    }

    .attendance-header {
        padding: 20px;
    }

    .attendance-header h2 {
        font-size: 20px;
    }

    .attendance-title-area {
        width: 100%;
    }

    .header-actions {
        width: 100%;
        justify-content: stretch;
    }

    .header-btn,
    .back-attendance-btn {
        flex: 1;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

}


/* =========================================================
   PRINT SHEET
========================================================= */

.print-sheet {
    display: none;
}


/* =========================================================
   PROFESSIONAL PRINT
========================================================= */

@media print {

    @page {
        size: A4 landscape;
        margin: 7mm;
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | Hide the ENTIRE application layout while printing.
    |
    | This removes:
    | ☰
    | Student Attendance A
    | Admin
    | Online
    | Sidebar
    | Navbar
    | Footer
    | Dashboard layout
    |
    | Only .print-sheet becomes visible.
    |--------------------------------------------------------------------------
    */

    html,
    body {
        width: 100% !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }


    body * {
        visibility: hidden !important;
    }


    .print-sheet,
    .print-sheet * {
        visibility: visible !important;
    }


    .print-sheet {
        display: block !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        color: #111827 !important;
        background: #ffffff !important;
        font-family: Arial, Helvetica, sans-serif !important;
    }


    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        box-sizing: border-box !important;
    }


    /* ---------------------------------------------------------
       SCHOOL HEADER
    --------------------------------------------------------- */

    .print-school-header {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 13px !important;
        padding-bottom: 7px !important;
        margin-bottom: 7px !important;
        border-bottom: 2px solid #1769d1 !important;
    }

    .print-school-logo {
        width: 52px !important;
        height: 52px !important;
        object-fit: contain !important;
    }

    .print-school-info {
        text-align: center !important;
    }

    .print-school-info h1 {
        margin: 0 !important;
        color: #1769d1 !important;
        font-size: 20px !important;
        font-weight: 800 !important;
    }

    .print-school-info p {
        margin: 2px 0 0 !important;
        color: #667085 !important;
        font-size: 8px !important;
    }


    /* ---------------------------------------------------------
       REPORT TITLE
    --------------------------------------------------------- */

    .print-report-title {
        margin-bottom: 7px !important;
        text-align: center !important;
    }

    .print-report-title h2 {
        margin: 0 !important;
        color: #1f2937 !important;
        font-size: 14px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
    }

    .print-report-title p {
        margin: 2px 0 0 !important;
        color: #667085 !important;
        font-size: 8px !important;
        font-weight: 600 !important;
    }


    /* ---------------------------------------------------------
       REPORT INFORMATION
    --------------------------------------------------------- */

    .print-info-box {
        display: grid !important;
        grid-template-columns: repeat(6, 1fr) !important;
        gap: 5px !important;
        margin-bottom: 7px !important;
    }

    .print-info-item {
        padding: 5px 6px !important;
        background: #f8fafc !important;
        border: 1px solid #d9e2ef !important;
        border-radius: 3px !important;
    }

    .print-info-label {
        display: block !important;
        margin-bottom: 2px !important;
        color: #667085 !important;
        font-size: 6px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
    }

    .print-info-value {
        display: block !important;
        color: #111827 !important;
        font-size: 8px !important;
        font-weight: 700 !important;
    }


    /* ---------------------------------------------------------
       PRINT TABLE
    --------------------------------------------------------- */

    .print-table {
        width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        margin: 0 !important;
        font-size: 6px !important;
    }

    .print-table th,
    .print-table td {
        padding: 2px 1px !important;
        border: 1px solid #aeb8c5 !important;
        text-align: center !important;
        vertical-align: middle !important;
        line-height: 1.1 !important;
    }

    .print-table thead th {
        height: 25px !important;
        color: #1f3b64 !important;
        background: #eaf2ff !important;
        font-weight: 800 !important;
    }

    .print-table tbody td {
        height: 17px !important;
    }


    /* ---------------------------------------------------------
       STUDENT COLUMN
    --------------------------------------------------------- */

    .print-table th:first-child,
    .print-table td:first-child {
        width: 19% !important;
        padding-left: 4px !important;
        text-align: left !important;
    }


    /* ---------------------------------------------------------
       ROLL COLUMN
    --------------------------------------------------------- */

    .print-table th:nth-child(2),
    .print-table td:nth-child(2) {
        width: 4.5% !important;
    }


    /* ---------------------------------------------------------
       SUMMARY COLUMNS
    --------------------------------------------------------- */

    .print-table th:nth-last-child(3),
    .print-table td:nth-last-child(3),
    .print-table th:nth-last-child(2),
    .print-table td:nth-last-child(2),
    .print-table th:last-child,
    .print-table td:last-child {
        width: 4.5% !important;
    }


    /* ---------------------------------------------------------
       ATTENDANCE COLORS
    --------------------------------------------------------- */

    .print-present {
        color: #16834b !important;
        font-weight: 800 !important;
    }

    .print-absent {
        color: #c92d3d !important;
        font-weight: 800 !important;
    }

    .print-empty {
        color: #98a2b3 !important;
        font-weight: 700 !important;
    }

    .print-holiday {
        color: #c92d3d !important;
        background: #fff0f0 !important;
        font-weight: 800 !important;
    }

    .print-holiday-cell {
        color: #c92d3d !important;
        background: #fff8f8 !important;
    }


    /* ---------------------------------------------------------
       DAILY COUNT
    --------------------------------------------------------- */

    .print-daily-count {
        display: block !important;
        margin-top: 1px !important;
        color: #16834b !important;
        font-size: 5px !important;
        font-weight: 800 !important;
    }

    .print-holiday .print-daily-count {
        color: #c92d3d !important;
    }


    /* ---------------------------------------------------------
       LEGEND
    --------------------------------------------------------- */

    .print-legend {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        margin-top: 6px !important;
        color: #475467 !important;
        font-size: 6.5px !important;
    }


    /* ---------------------------------------------------------
       FOOTER
    --------------------------------------------------------- */

    .print-footer {
        display: flex !important;
        align-items: flex-end !important;
        justify-content: space-between !important;
        margin-top: 8px !important;
        padding-top: 6px !important;
        border-top: 1px solid #d9e2ef !important;
        color: #667085 !important;
        font-size: 6.5px !important;
    }

    .print-signature {
        min-width: 145px !important;
        text-align: center !important;
    }

    .print-signature-line {
        margin-top: 17px !important;
        padding-top: 3px !important;
        color: #344054 !important;
        border-top: 1px solid #344054 !important;
        font-weight: 700 !important;
    }


    /* ---------------------------------------------------------
       PAGE BREAK
    --------------------------------------------------------- */

    .print-table thead {
        display: table-header-group !important;
    }

    .print-table tr {
        page-break-inside: avoid !important;
    }

    .print-table td,
    .print-table th {
        page-break-inside: avoid !important;
    }

}

</style>


<div class="attendance-page">

<div class="attendance-container">


{{-- =========================================================
     SUCCESS
========================================================= --}}

@if(session('success'))

    <div class="attendance-alert attendance-alert-success">

        <i class="bi bi-check-circle-fill"></i>

        <span>
            {{ session('success') }}
        </span>

    </div>

@endif


{{-- =========================================================
     ERROR
========================================================= --}}

@if(session('error'))

    <div class="attendance-alert attendance-alert-danger">

        <i class="bi bi-exclamation-circle-fill"></i>

        <span>
            {{ session('error') }}
        </span>

    </div>

@endif


{{-- =========================================================
     VALIDATION
========================================================= --}}

@if($errors->any())

    <div class="attendance-alert attendance-alert-danger">

        <i class="bi bi-exclamation-triangle-fill"></i>

        <div>

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    </div>

@endif


{{-- =========================================================
     HEADER
========================================================= --}}

<div class="attendance-header">

    <div class="attendance-header-content">

        <div class="attendance-title-area">

            <div class="attendance-title-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div>

                <h2>
                    Student Attendance
                </h2>

                <p>
                    Mark and manage daily student attendance
                </p>

            </div>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('admin.attendance.student') }}"
                class="back-attendance-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>


            <button
                type="button"
                class="header-btn header-btn-light"
                onclick="printAttendance()"
                title="Print attendance report"
            >
                <i class="bi bi-printer"></i>
                Print
            </button>


            <button
                type="button"
                class="header-btn header-btn-light"
                id="pdfExportBtn"
                onclick="exportAttendance('pdf', this)"
                title="Export attendance as PDF"
            >
                <i class="bi bi-file-earmark-pdf"></i>
                PDF
            </button>


            <button
                type="button"
                class="header-btn header-btn-white"
                id="excelExportBtn"
                onclick="exportAttendance('excel', this)"
                title="Export attendance as Excel"
            >
                <i class="bi bi-file-earmark-excel"></i>
                Excel
            </button>

        </div>

    </div>

</div>


{{-- =========================================================
     FILTERS
========================================================= --}}

<form
    method="GET"
    action="{{ $attendanceIndexUrl }}"
    class="filter-card"
>

    <div class="filter-grid">


        <div class="filter-group">

            <label for="academic_year">
                Academic Year
            </label>

            <select
                name="academic_year"
                id="academic_year"
                class="filter-control"
            >

                <option value="">
                    All Academic Years
                </option>

                @foreach($academicYearOptions as $year)

                    <option
                        value="{{ $year }}"
                        @selected(
                            (string) $selectedAcademicYear ===
                            (string) $year
                        )
                    >
                        {{ $year }}
                    </option>

                @endforeach

            </select>

        </div>


        <div class="filter-group">

            <label for="class">
                Class
            </label>

            <select
                name="class"
                id="class"
                class="filter-control"
            >

                <option value="">
                    All Classes
                </option>

                @foreach($classOptions as $class)

                    <option
                        value="{{ $class }}"
                        @selected(
                            (string) $selectedClass ===
                            (string) $class
                        )
                    >
                        {{ $class }}
                    </option>

                @endforeach

            </select>

        </div>


        <div class="filter-group">

            <label for="section">
                Section
            </label>

            <select
                name="section"
                id="section"
                class="filter-control"
            >

                <option value="">
                    All Sections
                </option>

                @foreach($sectionOptions as $section)

                    <option
                        value="{{ $section }}"
                        @selected(
                            (string) $selectedSection ===
                            (string) $section
                        )
                    >
                        {{ $section }}
                    </option>

                @endforeach

            </select>

        </div>


        <div class="filter-group">

            <label for="month">
                Attendance Month
            </label>

            <div class="filter-buttons">

                <input
                    type="month"
                    name="month"
                    id="month"
                    value="{{ $selectedMonth }}"
                    class="filter-control"
                >

                <button
                    type="submit"
                    class="filter-btn filter-primary"
                >
                    <i class="bi bi-funnel"></i>
                    Apply
                </button>

            </div>

        </div>

    </div>

</form>


{{-- =========================================================
     MONTH NAVIGATION
========================================================= --}}

<div class="month-card">

    <div class="month-navigation">

        <a
            href="{{ $attendanceIndexUrl . '?' . http_build_query(
                array_merge(
                    request()->except('month'),
                    ['month' => $previousMonth]
                )
            ) }}"
            class="month-nav-btn"
            title="Previous Month"
        >
            <i class="bi bi-chevron-left"></i>
        </a>


        <div class="month-name">

            <strong>
                {{ $currentMonth->format('F Y') }}
            </strong>

            <span>
                {{ $daysInMonth }} days
                •
                {{ $workingDays }} working days
                •
                {{ $holidayDays }} holidays
            </span>

        </div>


        <a
            href="{{ $attendanceIndexUrl . '?' . http_build_query(
                array_merge(
                    request()->except('month'),
                    ['month' => $nextMonth]
                )
            ) }}"
            class="month-nav-btn"
            title="Next Month"
        >
            <i class="bi bi-chevron-right"></i>
        </a>

    </div>

</div>


{{-- =========================================================
     INFORMATION CARDS
========================================================= --}}

<div class="info-grid">

    <div class="info-card">

        <div class="info-icon info-blue">
            <i class="bi bi-people"></i>
        </div>

        <div class="info-text">

            <small>
                Total Students
            </small>

            <strong>
                {{ $studentCollection->count() }}
            </strong>

        </div>

    </div>


    <div class="info-card">

        <div class="info-icon info-green">
            <i class="bi bi-calendar3"></i>
        </div>

        <div class="info-text">

            <small>
                Attendance Month
            </small>

            <strong>
                {{ $currentMonth->format('M Y') }}
            </strong>

        </div>

    </div>


    <div class="info-card">

        <div class="info-icon info-purple">
            <i class="bi bi-calendar-range"></i>
        </div>

        <div class="info-text">

            <small>
                Working Days
            </small>

            <strong>
                {{ $workingDays }}
            </strong>

        </div>

    </div>


    <div class="info-card">

        <div class="info-icon info-red">
            <i class="bi bi-calendar-x"></i>
        </div>

        <div class="info-text">

            <small>
                Holidays
            </small>

            <strong>
                {{ $holidayDays }}
            </strong>

        </div>

    </div>

</div>


{{-- =========================================================
     ATTENDANCE FORM
========================================================= --}}

<form
    method="POST"
    action="{{ $attendanceStoreUrl }}"
    id="attendanceForm"
>

@csrf

<input
    type="hidden"
    name="attendance_month"
    value="{{ $selectedMonth }}"
>

<input
    type="hidden"
    name="month"
    value="{{ $selectedMonth }}"
>

<input
    type="hidden"
    name="attendance_date"
    value="{{ $selectedDate ?? request('date', now()->format('Y-m-d')) }}"
>

<input
    type="hidden"
    name="academic_year"
    value="{{ $selectedAcademicYear ?? '' }}"
>

<input
    type="hidden"
    name="class"
    value="{{ $selectedClass ?? '' }}"
>

<input
    type="hidden"
    name="section"
    value="{{ $selectedSection ?? '' }}"
>


<div class="attendance-card">


    <div class="attendance-card-header">

        <div class="attendance-card-title">

            <i class="bi bi-table"></i>

            <div>

                <strong>
                    Daily Attendance
                </strong>

                <span>
                    Click a cell to mark Present / Absent
                </span>

            </div>

        </div>

    </div>


    @if($studentCollection->count() > 0)


    <div class="table-wrapper">

        <table
            class="attendance-table"
            id="attendanceTable"
        >

            <thead>

                <tr>

                    <th class="student-column">
                        Student
                    </th>

                    <th class="roll-column">
                        Roll No.
                    </th>


                    @foreach($monthDates as $date)

                        @php

                            $dateKey =
                                $date->format('Y-m-d');

                            $holidayName =
                                $getHolidayName($date);

                            $dailyPresent =
                                $dailyAttendance[$dateKey]['present']
                                ?? 0;

                            $dailyAbsent =
                                $dailyAttendance[$dateKey]['absent']
                                ?? 0;

                            $dailyTotal =
                                $dailyAttendance[$dateKey]['total']
                                ?? 0;

                        @endphp


                        <th
                            data-date="{{ $dateKey }}"
                            class="{{ $holidayName ? 'holiday-header' : '' }}"
                            title="{{ $holidayName
                                ? $holidayName
                                : $dailyPresent . ' students present out of ' . $dailyTotal }}"
                        >

                            <span class="date-number">
                                {{ $date->format('d') }}
                            </span>

                            <span class="date-day">
                                {{ $date->format('D') }}
                            </span>


                            @if($holidayName)

                                <span class="date-present-count">

                                    <i class="bi bi-calendar-x"></i>

                                    Holiday

                                </span>

                                <span class="date-absent-count">
                                    {{ $holidayName }}
                                </span>

                            @else

                                <span class="date-present-count">

                                    <i class="bi bi-person-check-fill"></i>

                                    <span class="js-daily-present">
                                        {{ $dailyPresent }}/{{ $dailyTotal }}
                                    </span>

                                </span>

                                <span class="date-absent-count js-daily-absent">
                                    {{ $dailyAbsent }} Absent
                                </span>

                            @endif

                        </th>

                    @endforeach


                    <th class="summary-column">
                        Summary
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($studentCollection as $student)

                    @php

                        $presentCount = 0;
                        $absentCount = 0;

                    @endphp


                    <tr data-student-row>


                        <td class="student-column">

                            <div class="student-info">

                                <div class="student-avatar">

                                    @if(!empty($student->profile_image))

                                        <img
                                            src="{{ $student->profile_image }}"
                                            alt="Student"
                                        >

                                    @else

                                        {{ strtoupper(
                                            substr(
                                                $student->first_name ?? 'S',
                                                0,
                                                1
                                            )
                                        ) }}

                                    @endif

                                </div>


                                <div>

                                    <div class="student-name">

                                        {{ trim(
                                            ($student->first_name ?? '') . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            ($student->last_name ?? '')
                                        ) }}

                                    </div>

                                    <div class="student-id">

                                        ID:
                                        {{ $student->student_id ?? $student->id }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        <td class="roll-column">
                            {{ $student->roll_number ?? '-' }}
                        </td>


                        @foreach($monthDates as $date)

                            @php

                                $dateKey =
                                    $date->format('Y-m-d');

                                $holidayName =
                                    $getHolidayName($date);

                                $status = strtolower(
                                    trim(
                                        (string) (
                                            $attendanceMap[$student->id][$dateKey]
                                            ?? ''
                                        )
                                    )
                                );

                                if (
                                    !$holidayName &&
                                    $status === 'present'
                                ) {
                                    $presentCount++;
                                }

                                if (
                                    !$holidayName &&
                                    $status === 'absent'
                                ) {
                                    $absentCount++;
                                }

                            @endphp


                            <td
                                class="attendance-cell {{ $holidayName ? 'holiday-cell' : '' }}"
                            >

                                @if($holidayName)

                                    <button
                                        type="button"
                                        class="attendance-btn holiday-disabled"
                                        disabled
                                        title="{{ $holidayName }}"
                                    >
                                        <i class="bi bi-calendar-x"></i>
                                    </button>

                                    <span class="holiday-label">
                                        {{ $holidayName }}
                                    </span>

                                @else

                                    <button
                                        type="button"
                                        class="attendance-btn
                                            {{ $status === 'present' ? 'present' : '' }}
                                            {{ $status === 'absent' ? 'absent' : '' }}"
                                        onclick="toggleAttendance(this)"
                                        title="{{ $status === 'present'
                                            ? 'Present'
                                            : ($status === 'absent'
                                                ? 'Absent'
                                                : 'Not Marked') }}"
                                    >

                                        @if($status === 'present')
                                            P
                                        @elseif($status === 'absent')
                                            A
                                        @else
                                            —
                                        @endif

                                    </button>


                                    <input
                                        type="hidden"
                                        class="attendance-input"
                                        name="attendance[{{ $student->id }}][{{ $dateKey }}]"
                                        value="{{ $status }}"
                                        data-date="{{ $dateKey }}"
                                    >

                                @endif

                            </td>

                        @endforeach


                        @php

                            $markedCount =
                                $presentCount +
                                $absentCount;

                            $percentage =
                                $markedCount > 0
                                    ? round(
                                        ($presentCount / $markedCount) * 100
                                    )
                                    : 0;

                        @endphp


                        <td class="summary-column">

                            <div class="summary-box">

                                <div class="summary-percentage">

                                    <span class="attendance-percentage">
                                        {{ $percentage }}
                                    </span>%

                                </div>

                                <div class="summary-counts">

                                    <span class="summary-present">
                                        P:
                                        <span class="present-count">
                                            {{ $presentCount }}
                                        </span>
                                    </span>

                                    <span class="summary-absent">
                                        A:
                                        <span class="absent-count">
                                            {{ $absentCount }}
                                        </span>
                                    </span>

                                </div>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>


    {{-- SAVE BAR --}}

    <div class="save-bar">

        <div class="legend">

            <div class="legend-item">
                <span class="legend-dot legend-present"></span>
                Present
            </div>

            <div class="legend-item">
                <span class="legend-dot legend-absent"></span>
                Absent
            </div>

            <div class="legend-item">
                <span class="legend-dot legend-empty"></span>
                Not Marked
            </div>

            <div class="legend-item">
                <span class="legend-dot legend-holiday"></span>
                Holiday
            </div>

        </div>


        <button
            type="submit"
            class="save-btn"
            id="saveAttendanceBtn"
        >

            <i class="bi bi-check2-circle"></i>

            Save Attendance

        </button>

    </div>


    @else


    <div class="empty-state">

        <div class="empty-icon">
            <i class="bi bi-people"></i>
        </div>

        <h4>
            No Students Found
        </h4>

        <p>
            No students match the selected filters.
        </p>

    </div>

    @endif

</div>

</form>


{{-- =========================================================
     PROFESSIONAL PRINT REPORT
========================================================= --}}

<div class="print-sheet">


    {{-- SCHOOL HEADER --}}

    <div class="print-school-header">

        <img
            src="{{ asset('images/gurukullogo.png') }}"
            alt="Gurukul Vidyalaya"
            class="print-school-logo"
        >

        <div class="print-school-info">

            <h1>
                Gurukul Vidyalaya
            </h1>

            <p>
                Student Attendance Management System
            </p>

        </div>

    </div>


    {{-- REPORT TITLE --}}

    <div class="print-report-title">

        <h2>
            Student Attendance Register
        </h2>

        <p>
            Monthly Attendance Report —
            {{ $currentMonth->format('F Y') }}
        </p>

    </div>


    {{-- REPORT INFORMATION --}}

    <div class="print-info-box">

        <div class="print-info-item">

            <span class="print-info-label">
                Academic Year
            </span>

            <span class="print-info-value">
                {{ $selectedAcademicYear ?: 'All' }}
            </span>

        </div>


        <div class="print-info-item">

            <span class="print-info-label">
                Class
            </span>

            <span class="print-info-value">
                {{ $selectedClass ?: 'All Classes' }}
            </span>

        </div>


        <div class="print-info-item">

            <span class="print-info-label">
                Section
            </span>

            <span class="print-info-value">
                {{ $selectedSection ?: 'All Sections' }}
            </span>

        </div>


        <div class="print-info-item">

            <span class="print-info-label">
                Total Students
            </span>

            <span class="print-info-value">
                {{ $studentCollection->count() }}
            </span>

        </div>


        <div class="print-info-item">

            <span class="print-info-label">
                Working Days
            </span>

            <span class="print-info-value">
                {{ $workingDays }}
            </span>

        </div>


        <div class="print-info-item">

            <span class="print-info-label">
                Holidays
            </span>

            <span class="print-info-value">
                {{ $holidayDays }}
            </span>

        </div>

    </div>


    {{-- PRINT TABLE --}}

    <table class="print-table">

        <thead>

            <tr>

                <th>
                    Student Name
                </th>

                <th>
                    Roll
                </th>


                @foreach($monthDates as $date)

                    @php

                        $dateKey =
                            $date->format('Y-m-d');

                        $holidayName =
                            $getHolidayName($date);

                        $dailyPresent =
                            $dailyAttendance[$dateKey]['present']
                            ?? 0;

                        $dailyTotal =
                            $dailyAttendance[$dateKey]['total']
                            ?? 0;

                    @endphp


                    <th
                        class="{{ $holidayName ? 'print-holiday' : '' }}"
                    >

                        {{ $date->format('d') }}

                        <span class="print-daily-count">

                            @if($holidayName)

                                {{ $date->isSunday()
                                    ? 'SUN'
                                    : 'HOL' }}

                            @else

                                {{ $dailyPresent }}/{{ $dailyTotal }}

                            @endif

                        </span>

                    </th>

                @endforeach


                <th>
                    P
                </th>

                <th>
                    A
                </th>

                <th>
                    %
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach($studentCollection as $student)

                @php

                    $printPresent = 0;
                    $printAbsent = 0;

                @endphp


                <tr>

                    <td>

                        {{ trim(
                            ($student->first_name ?? '') . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->last_name ?? '')
                        ) }}

                    </td>


                    <td>
                        {{ $student->roll_number ?? '-' }}
                    </td>


                    @foreach($monthDates as $date)

                        @php

                            $dateKey =
                                $date->format('Y-m-d');

                            $holidayName =
                                $getHolidayName($date);

                            $printStatus = strtolower(
                                trim(
                                    (string) (
                                        $attendanceMap[$student->id][$dateKey]
                                        ?? ''
                                    )
                                )
                            );

                            if (
                                !$holidayName &&
                                $printStatus === 'present'
                            ) {
                                $printPresent++;
                            }

                            if (
                                !$holidayName &&
                                $printStatus === 'absent'
                            ) {
                                $printAbsent++;
                            }

                        @endphp


                        <td
                            class="{{ $holidayName ? 'print-holiday-cell' : '' }}"
                        >

                            @if($holidayName)

                                <span class="print-absent">

                                    {{ $date->isSunday()
                                        ? 'SUN'
                                        : 'HOL' }}

                                </span>

                            @elseif($printStatus === 'present')

                                <span class="print-present">
                                    P
                                </span>

                            @elseif($printStatus === 'absent')

                                <span class="print-absent">
                                    A
                                </span>

                            @else

                                <span class="print-empty">
                                    —
                                </span>

                            @endif

                        </td>

                    @endforeach


                    @php

                        $printMarked =
                            $printPresent +
                            $printAbsent;

                        $printPercentage =
                            $printMarked > 0
                                ? round(
                                    ($printPresent / $printMarked) * 100
                                )
                                : 0;

                    @endphp


                    <td class="print-present">
                        {{ $printPresent }}
                    </td>

                    <td class="print-absent">
                        {{ $printAbsent }}
                    </td>

                    <td>
                        <strong>
                            {{ $printPercentage }}%
                        </strong>
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    {{-- LEGEND --}}

    <div class="print-legend">

        <strong>
            Attendance Key:
        </strong>

        <span>
            <strong class="print-present">P</strong>
            = Present
        </span>

        <span>
            <strong class="print-absent">A</strong>
            = Absent
        </span>

        <span>
            <strong class="print-empty">—</strong>
            = Not Marked
        </span>

        <span>
            <strong class="print-absent">SUN</strong>
            = Sunday Holiday
        </span>

        <span>
            <strong class="print-absent">HOL</strong>
            = National Holiday
        </span>

    </div>


    {{-- FOOTER --}}

    <div class="print-footer">

        <div>

            <div>
                Student Attendance Management System
            </div>

            <div>
                Report Month:
                {{ $currentMonth->format('F Y') }}
            </div>

            <div>
                Generated:
                {{ now()->format('d M Y, h:i A') }}
            </div>

        </div>


        <div class="print-signature">

            <div class="print-signature-line">
                Class Teacher / Authorized Signatory
            </div>

        </div>

    </div>

</div>


</div>

</div>


<script>

/* =========================================================
   ATTENDANCE TOGGLE
========================================================= */

function toggleAttendance(button) {

    if (
        button.disabled ||
        button.classList.contains('holiday-disabled')
    ) {
        return;
    }

    const cell = button.closest('td');

    if (!cell) {
        return;
    }

    const input = cell.querySelector(
        '.attendance-input'
    );

    if (!input) {
        return;
    }

    const current =
        (input.value || '')
            .toLowerCase()
            .trim();

    let next = '';

    if (current === '') {

        next = 'present';

    } else if (current === 'present') {

        next = 'absent';

    } else {

        next = '';

    }

    input.value = next;

    button.classList.remove(
        'present',
        'absent'
    );


    if (next === 'present') {

        button.classList.add('present');

        button.textContent = 'P';

        button.title = 'Present';

    }

    else if (next === 'absent') {

        button.classList.add('absent');

        button.textContent = 'A';

        button.title = 'Absent';

    }

    else {

        button.textContent = '—';

        button.title = 'Not Marked';

    }


    updateStudentSummary(
        button.closest('tr')
    );

    updateDailyDateCount(
        input.dataset.date
    );
}


/* =========================================================
   STUDENT SUMMARY
========================================================= */

function updateStudentSummary(row) {

    if (!row) {
        return;
    }

    const inputs =
        row.querySelectorAll(
            '.attendance-input'
        );

    let present = 0;
    let absent = 0;

    inputs.forEach(function(input) {

        const value =
            (input.value || '')
                .toLowerCase()
                .trim();

        if (value === 'present') {
            present++;
        }

        if (value === 'absent') {
            absent++;
        }

    });


    const marked =
        present + absent;

    const percentage =
        marked > 0
            ? Math.round(
                (present / marked) * 100
            )
            : 0;


    const presentElement =
        row.querySelector(
            '.present-count'
        );

    const absentElement =
        row.querySelector(
            '.absent-count'
        );

    const percentageElement =
        row.querySelector(
            '.attendance-percentage'
        );


    if (presentElement) {
        presentElement.textContent =
            present;
    }

    if (absentElement) {
        absentElement.textContent =
            absent;
    }

    if (percentageElement) {
        percentageElement.textContent =
            percentage;
    }
}


/* =========================================================
   DAILY COUNT
========================================================= */

function updateDailyDateCount(dateKey) {

    if (!dateKey) {
        return;
    }

    let present = 0;
    let absent = 0;
    let total = 0;


    const inputs =
        document.querySelectorAll(
            '.attendance-input[data-date="' +
            dateKey +
            '"]'
        );


    inputs.forEach(function(input) {

        total++;

        const value =
            (input.value || '')
                .toLowerCase()
                .trim();

        if (value === 'present') {
            present++;
        }

        if (value === 'absent') {
            absent++;
        }

    });


    const header =
        document.querySelector(
            'th[data-date="' +
            dateKey +
            '"]'
        );


    if (!header) {
        return;
    }


    const presentElement =
        header.querySelector(
            '.js-daily-present'
        );

    const absentElement =
        header.querySelector(
            '.js-daily-absent'
        );


    if (presentElement) {

        presentElement.textContent =
            present + '/' + total;

    }


    if (absentElement) {

        absentElement.textContent =
            absent + ' Absent';

    }
}


/* =========================================================
   PROFESSIONAL PRINT
========================================================= */

function printAttendance() {

    /*
    |--------------------------------------------------------------------------
    | Browser print dialog
    |--------------------------------------------------------------------------
    | The @media print CSS above hides the complete Laravel layout
    | and displays only .print-sheet.
    |--------------------------------------------------------------------------
    */

    window.print();

}


/* =========================================================
   EXPORT PARAMETERS
========================================================= */

function getAttendanceExportParams() {

    const params =
        new URLSearchParams();


    params.set(
        'month',
        @json($selectedMonth)
    );


    const academicYear =
        @json($selectedAcademicYear);

    const selectedClass =
        @json($selectedClass);

    const selectedSection =
        @json($selectedSection);


    if (academicYear) {

        params.set(
            'academic_year',
            academicYear
        );

    }


    if (selectedClass) {

        params.set(
            'class',
            selectedClass
        );

    }


    if (selectedSection) {

        params.set(
            'section',
            selectedSection
        );

    }


    return params;
}


/* =========================================================
   PDF / EXCEL
========================================================= */

function exportAttendance(type, button) {

    let baseUrl = null;


    if (type === 'pdf') {

        baseUrl =
            @json($attendancePdfUrl);

    }

    else if (type === 'excel') {

        baseUrl =
            @json($attendanceExcelUrl);

    }


    if (!baseUrl) {

        const format =
            type === 'pdf'
                ? 'PDF'
                : 'Excel';

        alert(
            format +
            ' export route is not configured yet.'
        );

        return;
    }


    if (button) {

        button.classList.add(
            'export-loading'
        );

        button.disabled = true;


        const originalHtml =
            button.innerHTML;


        button.dataset.originalHtml =
            originalHtml;


        button.innerHTML =
            '<i class="bi bi-arrow-repeat"></i> Preparing...';

    }


    const params =
        getAttendanceExportParams();


    const separator =
        baseUrl.includes('?')
            ? '&'
            : '?';


    const exportUrl =
        baseUrl +
        separator +
        params.toString();


    window.open(
        exportUrl,
        '_blank'
    );


    setTimeout(
        function() {

            if (!button) {
                return;
            }

            button.disabled = false;

            button.classList.remove(
                'export-loading'
            );

            button.innerHTML =
                button.dataset.originalHtml ||
                (
                    type === 'pdf'
                        ? '<i class="bi bi-file-earmark-pdf"></i> PDF'
                        : '<i class="bi bi-file-earmark-excel"></i> Excel'
                );

        },
        1200
    );
}


/* =========================================================
   PAGE LOAD
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function() {


        /* -----------------------------------------------------
           INITIAL STUDENT SUMMARY
        ----------------------------------------------------- */

        const rows =
            document.querySelectorAll(
                '#attendanceTable tbody tr[data-student-row]'
            );


        rows.forEach(function(row) {

            updateStudentSummary(row);

        });


        /* -----------------------------------------------------
           INITIAL DAILY COUNTS
        ----------------------------------------------------- */

        const dateSet =
            new Set();


        document
            .querySelectorAll(
                '.attendance-input[data-date]'
            )
            .forEach(function(input) {

                dateSet.add(
                    input.dataset.date
                );

            });


        dateSet.forEach(function(dateKey) {

            updateDailyDateCount(
                dateKey
            );

        });


        /* -----------------------------------------------------
           SAVE BUTTON
        ----------------------------------------------------- */

        const form =
            document.getElementById(
                'attendanceForm'
            );


        if (form) {

            form.addEventListener(
                'submit',
                function() {

                    const button =
                        document.getElementById(
                            'saveAttendanceBtn'
                        );


                    if (button) {

                        button.disabled = true;

                        button.innerHTML =
                            '<i class="bi bi-hourglass-split"></i> Saving...';

                    }

                }
            );

        }


        /* -----------------------------------------------------
           SUCCESS MESSAGE
        ----------------------------------------------------- */

        const successAlert =
            document.querySelector(
                '.attendance-alert-success'
            );


        if (successAlert) {

            setTimeout(
                function() {

                    successAlert.style.transition =
                        'opacity .4s ease';

                    successAlert.style.opacity =
                        '0';


                    setTimeout(
                        function() {

                            successAlert.remove();

                        },
                        400
                    );

                },
                3500
            );

        }

    }
);

</script>

@endsection