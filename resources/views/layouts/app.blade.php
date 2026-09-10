<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>@yield('title', 'School Management')</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #1677f0;
            --primary-dark: #0d5fd1;
            --sidebar-width: 260px;
            --header-height: 64px;
            --text: #26344a;
            --muted: #718096;
            --border: #e7edf5;
            --bg: #f5f8fc;
            --white: #ffffff;
            --hover: #eef5ff;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input {
            font-family: inherit;
        }

        /* =========================================================
           APP WRAPPER
        ========================================================= */

        .app-wrapper {
            min-height: 100vh;
            display: flex;
        }


        /* =========================================================
           SIDEBAR
        ========================================================= */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }


        /* =========================================================
           SIDEBAR LOGO
        ========================================================= */

        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .brand-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #ffffff;
}

.brand-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

        .brand-text {
            line-height: 1.2;
        }

        .brand-title {
            font-size: 17px;
            font-weight: 700;
            color: #17233f;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #718096;
            margin-top: 3px;
        }


        /* =========================================================
           SIDEBAR SCROLL
        ========================================================= */

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 12px 10px 20px;
        }

        .sidebar-content::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }


        /* =========================================================
           SECTION TITLE
        ========================================================= */

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            color: #9aa7b8;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding: 16px 14px 8px;
        }


        /* =========================================================
           SIDEBAR ITEM
        ========================================================= */

        .sidebar-item {
            width: 100%;
            min-height: 44px;
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-radius: 9px;
            color: #52627a;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 3px;
            cursor: pointer;
            transition:
                background 0.2s ease,
                color 0.2s ease;
            border: none;
            background: transparent;
            text-align: left;
        }

        .sidebar-item:hover {
            background: var(--hover);
            color: var(--primary);
        }

        .sidebar-item.active {
            background: #e9f2ff;
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-icon {
            width: 25px;
            min-width: 25px;
            text-align: center;
            font-size: 17px;
            margin-right: 9px;
        }

        .sidebar-label {
            flex: 1;
        }

        .sidebar-arrow {
            font-size: 13px;
            color: #8391a5;
            transition: transform 0.25s ease;
        }

        .sidebar-item.open .sidebar-arrow {
            transform: rotate(90deg);
        }


        /* =========================================================
           SUBMENU
        ========================================================= */

        .submenu {
            display: none;
            padding: 2px 0 5px 34px;
        }

        .submenu.open {
            display: block;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            min-height: 36px;
            padding: 7px 10px;
            border-radius: 7px;
            color: #6a788d;
            font-size: 13px;
            transition: all 0.2s ease;
            position: relative;
        }

        .submenu-item::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #b7c2d1;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .submenu-item:hover {
            background: #f3f7fc;
            color: var(--primary);
        }

        .submenu-item.active {
            color: var(--primary);
            font-weight: 600;
            background: #f0f6ff;
        }

        .submenu-item.active::before {
            background: var(--primary);
        }


        /* =========================================================
           OTHER SECTION
        ========================================================= */

        .other-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #9aa7b8;
        }

        .other-title::before {
            content: "•••";
            letter-spacing: 2px;
        }


        /* =========================================================
           LOGOUT
        ========================================================= */

        .logout-area {
            padding: 10px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .logout-button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 13px;
            border-radius: 9px;
            color: #64748b;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-align: left;
        }

        .logout-button:hover {
            background: #fff1f2;
            color: #ef4444;
        }


        /* =========================================================
           MAIN AREA
        ========================================================= */

        .main-area {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .top-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 22px;
            gap: 18px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .menu-toggle {
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 22px;
            color: #596a80;
            cursor: pointer;
        }

        .menu-toggle:hover {
            background: #f1f5f9;
        }

        .page-title {
            font-size: 18px;
            font-weight: 700;
            color: #16233e;
            white-space: nowrap;
        }


        /* =========================================================
           SEARCH
        ========================================================= */

        .header-search {
            margin-left: auto;
            position: relative;
            width: 300px;
        }

        .search-box {
            height: 38px;
            width: 100%;
            border: 1px solid #e4eaf2;
            background: #f5f7fa;
            border-radius: 9px;
            display: flex;
            align-items: center;
            padding: 0 10px;
            transition: all 0.2s ease;
        }

        .search-box:focus-within {
            background: white;
            border-color: #8bbcf8;
            box-shadow: 0 0 0 3px rgba(22, 119, 240, 0.08);
        }

        .search-icon {
            font-size: 16px;
            color: #718096;
            margin-right: 8px;
        }

        .search-input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            color: #26344a;
            font-size: 13px;
        }

        .search-input::placeholder {
            color: #8a97a9;
        }

        .search-button {
            border: none;
            background: transparent;
            cursor: pointer;
            color: #718096;
            font-size: 15px;
            padding: 4px;
        }

        .search-button:hover {
            color: var(--primary);
        }


        /* =========================================================
           SEARCH RESULTS
        ========================================================= */

        .search-results {
            position: absolute;
            top: 46px;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e4eaf2;
            border-radius: 10px;
            box-shadow: 0 12px 35px rgba(30, 55, 90, 0.14);
            padding: 7px;
            display: none;
            max-height: 320px;
            overflow-y: auto;
            z-index: 2000;
        }

        .search-results.show {
            display: block;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 10px;
            border-radius: 7px;
            font-size: 13px;
            color: #52627a;
            cursor: pointer;
        }

        .search-result-item:hover {
            background: #eef5ff;
            color: var(--primary);
        }

        .search-result-icon {
            width: 23px;
            text-align: center;
        }

        .search-empty {
            padding: 14px 10px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }


        /* =========================================================
           HEADER RIGHT
        ========================================================= */

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon-button {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            border-radius: 8px;
            color: #64748b;
            font-size: 18px;
            cursor: pointer;
            position: relative;
        }

        .header-icon-button:hover {
            background: #f1f5f9;
        }

        

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 9px;
            padding-left: 7px;
            border-left: 1px solid #e7edf5;
        }

        .admin-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #172b4d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }

        .admin-info {
            line-height: 1.2;
        }

        .admin-name {
            font-size: 13px;
            font-weight: 700;
            color: #25334a;
        }

        .admin-role {
            font-size: 10px;
            color: #8491a4;
            margin-top: 3px;
        }

        .admin-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #16a34a;
    margin-top: 3px;
}

.online-dot {
    width: 7px;
    height: 7px;
    background: #22c55e;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.15);
}


        /* =========================================================
           PAGE CONTENT
        ========================================================= */

        .main-content {
            flex: 1;
            width: 100%;
            padding: 22px;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .app-footer {
            min-height: 48px;
            background: white;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px;
            color: #7b8798;
            font-size: 12px;
        }

        .footer-right {
            color: #8b98a9;
        }


        /* =========================================================
           MOBILE OVERLAY
        ========================================================= */

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            z-index: 999;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1000px) {

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-area {
                margin-left: 0;
                width: 100%;
            }

            .header-search {
                width: 250px;
            }
        }


        @media (max-width: 700px) {

            .top-header {
                padding: 0 12px;
                gap: 8px;
            }

            .page-title {
                display: none;
            }

            .header-search {
                width: auto;
                flex: 1;
                margin-left: 0;
            }

            .admin-info {
                display: none;
            }

            .header-actions {
                gap: 3px;
            }

            .main-content {
                padding: 14px;
            }

            .app-footer {
                padding: 12px 14px;
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="app-wrapper">

    <!-- =========================================================
         SIDEBAR
    ========================================================== -->

    <aside class="sidebar" id="sidebar">

        <!-- Brand -->
        <div class="sidebar-brand">

           <div class="brand-icon">
    <img src="{{ asset('images/gurukullogo.png') }}" alt="Gurukul Logo">
</div>

            <div class="brand-text">
                <div class="brand-title">
                    Gurukul Vidyalaya
                </div>

                <div class="brand-subtitle">
                    School Management
                </div>
            </div>

        </div>


        <!-- Sidebar Navigation -->
        <div class="sidebar-content">

            <!-- Dashboard -->

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               data-search="dashboard home">

                <span class="sidebar-icon">
    <i class="fas fa-tachometer-alt"></i>
</span>

<span class="sidebar-label">
    Dashboard
</span>

            </a>


            <!-- =================================================
                 STUDENT
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.students.*') ? 'active open' : '' }}"
                    data-submenu="student-menu"
                    data-search="student students">

                <span class="sidebar-icon">
    <i class="fa fa-user-graduate"></i>
</span>

<span class="sidebar-label">
    Student
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.students.*') ? 'open' : '' }}"
                 id="student-menu">

                <a href="{{ route('admin.students.index') }}"
                   class="submenu-item {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">
                    All Students
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="submenu-item">
                    Add Student
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="submenu-item">
                    Student Profile
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="submenu-item">
                    Student Documents
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="submenu-item">
                    Student ID
                </a>

                <a href="{{ route('admin.attendance.index') }}"
                   class="submenu-item">
                    Attendance
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="submenu-item {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">
                    School Supplies(Kit)
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="submenu-item {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">
                    Student Report
                </a>



            </div>


            <!-- =================================================
                 FACULTY
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.faculty.*') ? 'active open' : '' }}"
                    data-submenu="faculty-menu"
                    data-search="faculty teacher teachers">

                <span class="sidebar-icon">
    <i class="fas fa-chalkboard-teacher"></i>
</span>

<span class="sidebar-label">
    Faculty (Teacher)
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.faculty.*') ? 'open' : '' }}"
                 id="faculty-menu">

                <a href="{{ route('admin.faculty.index') }}"
                   class="submenu-item">
                    All Faculty
                </a>

                <a href="{{ route('admin.faculty.index') }}"
                   class="submenu-item">
                    Add Faculty
                </a>

                <a href="{{ route('admin.faculty.index') }}"
                   class="submenu-item">
                    Faculty Profile
                </a>

                <a href="{{ route('admin.attendance.index') }}"
                   class="submenu-item">
                    Attendance
                </a>

                <a href="{{ route('admin.faculty.index') }}"
                   class="submenu-item">
                    Class Teacher Assignment
                </a>

                <a href="{{ route('admin.timetable.index') }}"
                   class="submenu-item">
                    Time Table
                </a>

                <a href="{{ route('admin.faculty.index') }}"
                   class="submenu-item">
                    Salary
                </a>

                <a href="{{ route('admin.faculty.index') }}"
                   class="submenu-item">
                    Report
                </a>

            </div>


            <!-- =================================================
                 TIME TABLE
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.timetable.*') ? 'active open' : '' }}"
                    data-submenu="timetable-menu"
                    data-search="time table timetable schedule">

                <span class="sidebar-icon">
    <i class="fas fa-table"></i>
</span>

<span class="sidebar-label">
    Time Table
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.timetable.*') ? 'open' : '' }}"
                 id="timetable-menu">

                <a href="{{ route('admin.timetable.index') }}"
                   class="submenu-item">
                    Class Timetable
                </a>

                <a href="{{ route('admin.timetable.index') }}"
                   class="submenu-item">
                    Teacher Timetable
                </a>

                <a href="{{ route('admin.timetable.index') }}"
                   class="submenu-item">
                    Create Timetable
                </a>

            </div>


            <!-- =================================================
                 ATTENDANCE
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.attendance.*') ? 'active open' : '' }}"
                    data-submenu="attendance-menu"
                    data-search="attendance student faculty mark">

                <span class="sidebar-icon">
    <i class="fas fa-check"></i>
</span>

<span class="sidebar-label">
    Attendance
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.attendance.*') ? 'open' : '' }}"
                 id="attendance-menu">

                <a href="{{ route('admin.attendance.index') }}"
                   class="submenu-item">
                    Student Attendance
                </a>

                <a href="{{ route('admin.attendance.index') }}"
                   class="submenu-item">
                    Faculty Attendance
                </a>

                <a href="{{ route('admin.attendance.index') }}"
                   class="submenu-item">
                    Attendance Report
                </a>

            </div>


            <!-- =================================================
                 FEES
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.fees.*') ? 'active open' : '' }}"
                    data-submenu="fees-menu"
                    data-search="fees fee payment scholarship">

                <span class="sidebar-icon">₹</span>

                <span class="sidebar-label">
                    Fees
                </span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.fees.*') ? 'open' : '' }}"
                 id="fees-menu">

                <a href="{{ route('admin.fees.index') }}"
                   class="submenu-item">
                    Fee Structure
                </a>

                <a href="{{ route('admin.fees.index') }}"
                   class="submenu-item">
                    Student Fee
                </a>

                <a href="{{ route('admin.fees.index') }}"
                   class="submenu-item">
                    Payment History
                </a>

                <a href="{{ route('admin.scholarship.index') }}"
                   class="submenu-item">
                    Scholarship
                </a>

            </div>


            <!-- =================================================
                 EXAM
            ================================================== -->

            <a href="{{ route('admin.exam.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.exam.*') ? 'active' : '' }}"
               data-search="exam examination">

                <span class="sidebar-icon">
    <i class="fas fa-file-alt"></i>
</span>

<span class="sidebar-label">
    Exam
</span>

            </a>


            <!-- =================================================
                 RESULT
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.results.*') ? 'active open' : '' }}"
                    data-submenu="result-menu"
                    data-search="result results marks grade">

                <span class="sidebar-icon">
    <i class="fas fa-chart-bar"></i>
</span>

<span class="sidebar-label">
    Result
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.results.*') ? 'open' : '' }}"
                 id="result-menu">

                <a href="{{ route('admin.results.index') }}"
                   class="submenu-item">
                    Student Result
                </a>

                <a href="{{ route('admin.results.index') }}"
                   class="submenu-item">
                    Grade Management
                </a>

                <a href="{{ route('admin.results.index') }}"
                   class="submenu-item">
                    Publish Result
                </a>

                <a href="{{ route('admin.results.index') }}"
                   class="submenu-item">
                    Result History
                </a>

                <a href="{{ route('admin.results.index') }}"
                   class="submenu-item">
                    Result Report
                </a>

            </div>


            <!-- =================================================
                 NOTICE
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.notices.*') ? 'active open' : '' }}"
                    data-submenu="notice-menu"
                    data-search="notice notices announcement">

               <span class="sidebar-icon">
    <i class="fas fa-flag"></i>
</span>

<span class="sidebar-label">
    Notice
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.notices.*') ? 'open' : '' }}"
                 id="notice-menu">

                <a href="{{ route('admin.notices.index') }}"
                   class="submenu-item">
                    All Notices
                </a>

            </div>


            <!-- =================================================
                 LIBRARY
            ================================================== -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.library.*') ? 'active open' : '' }}"
                    data-submenu="library-menu"
                    data-search="library books issue return fine">

                <span class="sidebar-icon">
    <i class="fas fa-book"></i>
</span>

<span class="sidebar-label">
    Library
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.library.*') ? 'open' : '' }}"
                 id="library-menu">

                <a href="{{ route('admin.library.index') }}"
                   class="submenu-item">
                    Total Books
                </a>

                <a href="{{ route('admin.library.index') }}"
                   class="submenu-item">
                    Book Categories
                </a>

                <a href="{{ route('admin.library.index') }}"
                   class="submenu-item">
                    Add Books
                </a>

                <a href="{{ route('admin.library.index') }}"
                   class="submenu-item">
                    Issues / Returns / Fine
                </a>

                <a href="{{ route('admin.library.index') }}"
                   class="submenu-item">
                    Incharge Profile
                </a>

                <a href="{{ route('admin.library.index') }}"
                   class="submenu-item">
                    Reports
                </a>

            </div>


            <!-- =================================================
                 OTHER
            ================================================== -->

            <div class="sidebar-section-title other-title">
                OTHER
            </div>


            <!-- Transport -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.transport.*') ? 'active open' : '' }}"
                    data-submenu="transport-menu"
                    data-search="transport bus vehicle">

                <span class="sidebar-icon">
    <i class="fas fa-bus"></i>
</span>

<span class="sidebar-label">
    Transport
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.transport.*') ? 'open' : '' }}"
                 id="transport-menu">

                <a href="{{ route('admin.transport.index') }}"
                   class="submenu-item">
                    Transport Records
                </a>

                <a href="{{ route('admin.transport.index') }}"
                   class="submenu-item">
                    Routes
                </a>

                <a href="{{ route('admin.transport.index') }}"
                   class="submenu-item">
                    Vehicles
                </a>

            </div>


            <!-- Meal Management -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.meals.*') ? 'active open' : '' }}"
                    data-submenu="meal-menu"
                    data-search="meal meals food stock">

                <span class="sidebar-icon">
    <i class="fas fa-utensils"></i>
</span>

<span class="sidebar-label">
    Meal Management
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.meals.*') ? 'open' : '' }}"
                 id="meal-menu">

                <a href="{{ route('admin.meals.index') }}"
                   class="submenu-item">
                    Stock In / Stock Out
                </a>

                <a href="{{ route('admin.meals.index') }}"
                   class="submenu-item">
                    Logs
                </a>

            </div>


            <!-- Payroll -->

            <a href="{{ route('admin.payroll.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}"
               data-search="payroll salary">

                <span class="sidebar-icon">
    <i class="fas fa-money-check-alt"></i>
</span>

<span class="sidebar-label">
    Payroll
</span>

            </a>


            <!-- Sports -->

            <a href="{{ route('admin.sports.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.sports.*') ? 'active' : '' }}"
               data-search="sports">

                <span class="sidebar-icon">
    <i class="fas fa-futbol"></i>
</span>

<span class="sidebar-label">
    Sports
</span>

            </a>


            <!-- Scholarship -->

            <a href="{{ route('admin.scholarship.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.scholarship.*') ? 'active' : '' }}"
               data-search="scholarship">

                <span class="sidebar-icon">
    <i class="fas fa-graduation-cap"></i>
</span>

<span class="sidebar-label">
    Scholarship
</span>

            </a>


            <!-- Class -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.classes.*') ? 'active open' : '' }}"
                    data-submenu="class-menu"
                    data-search="class classes division subjects">

                <span class="sidebar-icon">
    <i class="fas fa-chalkboard"></i>
</span>

<span class="sidebar-label">
    Class
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.classes.*') ? 'open' : '' }}"
                 id="class-menu">

                <a href="{{ route('admin.classes.index') }}"
                   class="submenu-item">
                    Classes
                </a>

                <a href="{{ route('admin.classes.index') }}"
                   class="submenu-item">
                    Subjects
                </a>

            </div>


            <!-- Settings -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.settings.*') ? 'active open' : '' }}"
                    data-submenu="settings-menu"
                    data-search="settings role permission users backup">

                <span class="sidebar-icon">
    <i class="fas fa-cog"></i>
</span>

<span class="sidebar-label">
    Settings
</span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="submenu
                        {{ request()->routeIs('admin.settings.*') ? 'open' : '' }}"
                 id="settings-menu">

                <a href="{{ route('admin.settings.index') }}"
                   class="submenu-item">
                    School Profile
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="submenu-item">
                    User Roles & Permission
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="submenu-item">
                    Backup & Recovery
                </a>

            </div>

        </div>


        <!-- Logout -->

        <div class="logout-area">

            <form method="POST" action="{{ route('admin.logout') }}">

                @csrf

                <button type="submit" class="logout-button">

                    <span class="sidebar-icon">
    <i class="fas fa-sign-out-alt"></i>
</span>

<span class="sidebar-label">
    Logout
</span>

                </button>

            </form>

        </div>

    </aside>


    <!-- Mobile overlay -->

    <div class="sidebar-overlay" id="sidebarOverlay"></div>


    <!-- =========================================================
         MAIN AREA
    ========================================================== -->

    <div class="main-area">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <header class="top-header">

            <button
                type="button"
                class="menu-toggle"
                id="menuToggle"
                aria-label="Toggle sidebar">

                ☰

            </button>


            <div class="page-title">
                @yield('page-title', 'Dashboard')
            </div>


            <!-- SEARCH -->

            <div class="header-search">

                <div class="search-box">

                    <span class="search-icon">
                        ⌕
                    </span>

                    <input
                        type="text"
                        id="globalSearch"
                        class="search-input"
                        placeholder="Search..."
                        autocomplete="off"
                    >

                    <button
                        type="button"
                        class="search-button"
                        id="searchButton"
                        title="Search">

                        ↵

                    </button>

                </div>


                <div
                    class="search-results"
                    id="searchResults">
                </div>

            </div>


            <!-- Header actions -->

            <div class="header-actions">

                

                <div class="admin-profile">

    <div class="admin-avatar">
        A
    </div>

    <div class="admin-info">

        <div class="admin-name">
            Admin
        </div>

        <div class="admin-status">
            <span class="online-dot"></span>
            Online
        </div>

    </div>

</div>

        </header>


        <!-- =====================================================
             PAGE CONTENT
        ====================================================== -->

        <main class="main-content">

            @yield('content')

        </main>


        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <footer class="app-footer">

            <div>
                © {{ date('Y') }} Gurukul Vidyalaya. All rights reserved.
            </div>

            <div class="footer-right">
                School Management System • v1.0.0
            </div>

        </footer>

    </div>

</div>


<!-- =============================================================
     JAVASCRIPT
============================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SIDEBAR SUBMENUS
    |--------------------------------------------------------------------------
    */

    const submenuButtons =
        document.querySelectorAll('.has-submenu');

    submenuButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const submenuId =
                this.getAttribute('data-submenu');

            const submenu =
                document.getElementById(submenuId);

            if (!submenu) {
                return;
            }


            /*
             * Close all other submenus
             */

            submenuButtons.forEach(function (otherButton) {

                if (otherButton !== button) {

                    otherButton.classList.remove('open');

                    const otherId =
                        otherButton.getAttribute('data-submenu');

                    const otherMenu =
                        document.getElementById(otherId);

                    if (otherMenu) {
                        otherMenu.classList.remove('open');
                    }
                }
            });


            /*
             * Toggle selected submenu
             */

            button.classList.toggle('open');

            submenu.classList.toggle('open');

        });

    });


    /*
    |--------------------------------------------------------------------------
    | MOBILE SIDEBAR
    |--------------------------------------------------------------------------
    */

    const menuToggle =
        document.getElementById('menuToggle');

    const sidebar =
        document.getElementById('sidebar');

    const sidebarOverlay =
        document.getElementById('sidebarOverlay');


    menuToggle.addEventListener('click', function () {

        sidebar.classList.toggle('mobile-open');

        sidebarOverlay.classList.toggle('active');

    });


    sidebarOverlay.addEventListener('click', function () {

        sidebar.classList.remove('mobile-open');

        sidebarOverlay.classList.remove('active');

    });


    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */

    const searchInput =
        document.getElementById('globalSearch');

    const searchButton =
        document.getElementById('searchButton');

    const searchResults =
        document.getElementById('searchResults');


    /*
     * Collect sidebar links/items
     */

    const searchableItems = [];


    document
        .querySelectorAll('.sidebar-item[data-search]')
        .forEach(function (item) {

            searchableItems.push({

                text:
                    item.innerText
                    .trim(),

                keywords:
                    item.getAttribute('data-search'),

                element:
                    item

            });

        });


    document
        .querySelectorAll('.submenu-item')
        .forEach(function (item) {

            searchableItems.push({

                text:
                    item.innerText
                    .trim(),

                keywords:
                    item.innerText
                    .trim(),

                element:
                    item

            });

        });


    /*
     * Search function
     */

    function performSearch() {

        const query =
            searchInput.value
                .trim()
                .toLowerCase();


        searchResults.innerHTML = '';


        if (!query) {

            searchResults.classList.remove('show');

            return;
        }


        const matches =
            searchableItems.filter(function (item) {

                return (
                    item.text
                        .toLowerCase()
                        .includes(query)
                    ||
                    item.keywords
                        .toLowerCase()
                        .includes(query)
                );

            });


        if (matches.length === 0) {

            searchResults.innerHTML = `
                <div class="search-empty">
                    No results found for "<strong>${query}</strong>"
                </div>
            `;

            searchResults.classList.add('show');

            return;
        }


        matches
            .slice(0, 8)
            .forEach(function (item) {

                const result =
                    document.createElement('div');

                result.className =
                    'search-result-item';


                result.innerHTML = `
                    <span class="search-result-icon">
                        🔎
                    </span>

                    <span>
                        ${item.text}
                    </span>
                `;


                result.addEventListener('click', function () {

                    /*
                     * If the result is a normal link,
                     * follow it.
                     */

                    if (
                        item.element.tagName === 'A'
                        &&
                        item.element.href
                    ) {

                        window.location.href =
                            item.element.href;

                        return;
                    }


                    /*
                     * If it's a parent menu,
                     * open its submenu.
                     */

                    if (
                        item.element.classList
                            .contains('has-submenu')
                    ) {

                        item.element.click();

                    }


                    searchResults.classList.remove('show');

                    searchInput.value =
                        item.text;

                });


                searchResults.appendChild(result);

            });


        searchResults.classList.add('show');

    }


    /*
     * Search while typing
     */

    searchInput.addEventListener(
        'input',
        performSearch
    );


    /*
     * Search button
     */

    searchButton.addEventListener(
        'click',
        performSearch
    );


    /*
     * Press Enter to search
     */

    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                performSearch();

            }

        }
    );


    /*
     * Close search results when
     * clicking outside.
     */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.closest(
                    '.header-search'
                )
            ) {

                searchResults.classList.remove(
                    'show'
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CTRL + K SEARCH SHORTCUT
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                (event.ctrlKey || event.metaKey)
                &&
                event.key.toLowerCase() === 'k'
            ) {

                event.preventDefault();

                searchInput.focus();

                searchInput.select();

            }

        }
    );

});

</script>


@stack('scripts')

</body>
</html>