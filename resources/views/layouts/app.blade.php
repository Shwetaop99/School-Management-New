```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

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
        ========================================================== */

        .app-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* =========================================================
           SIDEBAR
        ========================================================== */

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
           SIDEBAR BRAND
        ========================================================== */

        .sidebar-brand {
            min-height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 6px 16px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            gap: 10px;
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
            min-width: 0;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 700;
            color: #17233f;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-subtitle {
            font-size: 10px;
            color: #718096;
            margin-top: 3px;
        }

        /* =========================================================
           SIDEBAR SCROLL
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

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
            transition:
                background 0.2s ease,
                color 0.2s ease;
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
           NESTED SCHOOL SUPPLY MENU
        ========================================================== */

        .nested-menu-wrapper {
            margin: 2px 0 4px;
        }

        .nested-menu-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            min-height: 36px;
            padding: 7px 10px;
            border: none;
            border-radius: 7px;
            background: transparent;
            color: #6a788d;
            font-size: 13px;
            text-align: left;
            cursor: pointer;
            transition:
                background 0.2s ease,
                color 0.2s ease;
        }

        .nested-menu-toggle:hover {
            background: #f3f7fc;
            color: var(--primary);
        }

        .nested-menu-toggle.active {
            color: var(--primary);
            font-weight: 600;
            background: #f0f6ff;
        }

        .nested-menu-toggle::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #b7c2d1;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .nested-menu-toggle.active::before {
            background: var(--primary);
        }

        .nested-menu-arrow {
            margin-left: auto;
            font-size: 11px;
            transition: transform 0.2s ease;
        }

        .nested-menu-toggle.open .nested-menu-arrow {
            transform: rotate(90deg);
        }

        .nested-menu {
            display: none;
            padding-left: 18px;
            margin-top: 2px;
        }

        .nested-menu.open {
            display: block;
        }

        .nested-menu .submenu-item {
            font-size: 12.5px;
            min-height: 34px;
        }

        /* =========================================================
           OTHER SECTION
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

        .main-area {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* =========================================================
           HEADER
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
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
        ========================================================== */

        .main-content {
            flex: 1;
            width: 100%;
            padding: 22px;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

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
        ========================================================== */

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            z-index: 999;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================== */

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

@php
    /*
    |--------------------------------------------------------------------------
    | DYNAMIC SCHOOL PROFILE
    |--------------------------------------------------------------------------
    | $school is expected to be shared from AppServiceProvider/View Composer.
    |
    | Logo priority:
    | 1. Database / Cloudinary logo
    | 2. public/images/gurukullogo.png
    */

    $schoolName = $school?->school_name ?? 'Gurukul Vidyalaya';

    $schoolLogo = $school?->logo_url
        ?: asset('images/gurukullogo.png');
@endphp

<div class="app-wrapper">

    <!-- =========================================================
         SIDEBAR
    ========================================================== -->

    <aside class="sidebar" id="sidebar">

        <!-- Brand -->
        <div class="sidebar-brand">

            <div class="brand-icon">
                <img
                    src="{{ $schoolLogo }}"
                    alt="{{ $schoolName }} Logo"
                    onerror="this.onerror=null;this.src='{{ asset('images/gurukullogo.png') }}';"
                >
            </div>

            <div class="brand-text">

                <div class="brand-title">
                    {{ $schoolName }}
                </div>

                <div class="brand-subtitle">
                    School Management
                </div>

            </div>

        </div>


        <!-- Sidebar Navigation -->
        <div class="sidebar-content">

            <!-- =================================================
                 DASHBOARD
            ================================================== -->

            <a
                href="{{ route('admin.dashboard') }}"
                class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                data-search="dashboard home"
            >

                <span class="sidebar-icon">⌂</span>

                <span class="sidebar-label">
                    Dashboard
                </span>

            </a>


            <!-- =================================================
                 STUDENT
            ================================================== -->

            @php
                $studentMenuActive = request()->routeIs(
                    'admin.students.*',
                    'admin.student-profile.*',
                    'admin.student-documents.*',
                    'admin.id-card.*',
                    'admin.attendance.*',
                    'admin.student-supply-kits.*',
                    'admin.student-general-register.*',
                    'admin.student-health.*'
                );
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $studentMenuActive ? 'active open' : '' }}"
                data-submenu="student-menu"
                data-search="student students"
            >

                <span class="sidebar-icon">♟</span>

                <span class="sidebar-label">
                    Student
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $studentMenuActive ? 'open' : '' }}"
                id="student-menu"
            >

                <a
                    href="{{ route('admin.students.class-wise') }}"
                    class="submenu-item {{ request()->routeIs('admin.students.class-wise') ? 'active' : '' }}"
                >
                    All Students
                </a>

                <a
                    href="{{ route('admin.students.create') }}"
                    class="submenu-item {{ request()->routeIs('admin.students.create') ? 'active' : '' }}"
                >
                    Add Student
                </a>

                <a
                    href="{{ route('admin.student-profile.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.student-profile.*') ? 'active' : '' }}"
                >
                    Student Profile
                </a>

                <a
                    href="{{ route('admin.student-documents.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.student-documents.*') ? 'active' : '' }}"
                >
                    Student Documents
                </a>

                <a
                    href="{{ route('admin.id-card.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.id-card.*') ? 'active' : '' }}"
                >
                    Student ID
                </a>

                <a
                    href="{{ route('admin.attendance.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}"
                >
                    Attendance
                </a>


                <!-- =================================================
                     SCHOOL SUPPLIES / GOVERNMENT KIT
                ================================================== -->

                @php
                    $supplyKitActive = request()->routeIs(
                        'admin.supply-items.*',
                        'admin.kit-templates.*',
                        'admin.student-supply-kits.*'
                    );
                @endphp

                <div class="nested-menu-wrapper">

                    <button
                        type="button"
                        class="nested-menu-toggle {{ $supplyKitActive ? 'active open' : '' }}"
                        data-nested-menu="school-supplies-menu"
                    >

                        <span>
                            School Supplies (Kit)
                        </span>

                        <span class="nested-menu-arrow">
                            ›
                        </span>

                    </button>


                    <div
                        id="school-supplies-menu"
                        class="nested-menu {{ $supplyKitActive ? 'open' : '' }}"
                    >

                        <a
                            href="{{ route('admin.supply-items.index') }}"
                            class="submenu-item {{ request()->routeIs('admin.supply-items.*') ? 'active' : '' }}"
                        >
                            Supply Items
                        </a>

                        <a
                            href="{{ route('admin.kit-templates.index') }}"
                            class="submenu-item {{ request()->routeIs('admin.kit-templates.*') ? 'active' : '' }}"
                        >
                            Government Kit Templates
                        </a>

                        <a
                            href="{{ route('admin.student-supply-kits.index') }}"
                            class="submenu-item {{ request()->routeIs('admin.student-supply-kits.*') ? 'active' : '' }}"
                        >
                            Student Kit Distribution
                        </a>

                    </div>

                </div>


                <a
                    href="{{ route('admin.student-general-register.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.student-general-register.*') ? 'active' : '' }}"
                >
                    Student Register
                </a>


                <a
                    href="{{ route('admin.student-health.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.student-health.*') ? 'active' : '' }}"
                >
                    Student Health Report
                </a>


                <a
                    href="{{ route('admin.students.index') }}"
                    class="submenu-item"
                >
                    Student Report
                </a>

            </div>


            <!-- =================================================
                 FACULTY
            ================================================== -->

            @php
                $facultyActive = request()->routeIs('admin.faculty.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $facultyActive ? 'active open' : '' }}"
                data-submenu="faculty-menu"
                data-search="faculty teacher teachers"
            >

                <span class="sidebar-icon">♟</span>

                <span class="sidebar-label">
                    Faculty (Teacher)
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $facultyActive ? 'open' : '' }}"
                id="faculty-menu"
            >

                <a
                    href="{{ route('admin.faculty.index') }}"
                    class="submenu-item"
                >
                    All Faculty
                </a>

                <a
                    href="{{ route('admin.faculty.index') }}"
                    class="submenu-item"
                >
                    Add Faculty
                </a>

                <a
                    href="{{ route('admin.faculty.index') }}"
                    class="submenu-item"
                >
                    Faculty Profile
                </a>

                <a
                    href="{{ route('admin.attendance.index') }}"
                    class="submenu-item"
                >
                    Attendance
                </a>

                <a
                    href="{{ route('admin.faculty.index') }}"
                    class="submenu-item"
                >
                    Class Teacher Assignment
                </a>

                <a
                    href="{{ route('admin.timetable.index') }}"
                    class="submenu-item"
                >
                    Time Table
                </a>

                <a
                    href="{{ route('admin.faculty.index') }}"
                    class="submenu-item"
                >
                    Salary
                </a>

                <a
                    href="{{ route('admin.faculty.index') }}"
                    class="submenu-item"
                >
                    Report
                </a>

            </div>


            <!-- =================================================
                 TIME TABLE
            ================================================== -->

            @php
                $timetableActive = request()->routeIs('admin.timetable.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $timetableActive ? 'active open' : '' }}"
                data-submenu="timetable-menu"
                data-search="time table timetable schedule"
            >

                <span class="sidebar-icon">▦</span>

                <span class="sidebar-label">
                    Time Table
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $timetableActive ? 'open' : '' }}"
                id="timetable-menu"
            >

                <a
                    href="{{ route('admin.timetable.index') }}"
                    class="submenu-item"
                >
                    Class Timetable
                </a>

                <a
                    href="{{ route('admin.timetable.index') }}"
                    class="submenu-item"
                >
                    Teacher Timetable
                </a>

                <a
                    href="{{ route('admin.timetable.index') }}"
                    class="submenu-item"
                >
                    Create Timetable
                </a>

            </div>


            <!-- =================================================
                 ATTENDANCE
            ================================================== -->

            @php
                $attendanceActive = request()->routeIs('admin.attendance.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $attendanceActive ? 'active open' : '' }}"
                data-submenu="attendance-menu"
                data-search="attendance student faculty mark"
            >

                <span class="sidebar-icon">✓</span>

                <span class="sidebar-label">
                    Attendance
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $attendanceActive ? 'open' : '' }}"
                id="attendance-menu"
            >

                <a
                    href="{{ route('admin.attendance.index') }}"
                    class="submenu-item"
                >
                    Student Attendance
                </a>

                <a
                    href="{{ route('admin.attendance.index') }}"
                    class="submenu-item"
                >
                    Faculty Attendance
                </a>

                <a
                    href="{{ route('admin.attendance.index') }}"
                    class="submenu-item"
                >
                    Attendance Report
                </a>

            </div>


            <!-- =================================================
                 FEES
            ================================================== -->

            @php
                $feesActive = request()->routeIs(
                    'admin.fees.*',
                    'admin.scholarship.*'
                );
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $feesActive ? 'active open' : '' }}"
                data-submenu="fees-menu"
                data-search="fees fee payment scholarship"
            >

                <span class="sidebar-icon">₹</span>

                <span class="sidebar-label">
                    Fees
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $feesActive ? 'open' : '' }}"
                id="fees-menu"
            >

                <a
                    href="{{ route('admin.fees.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.fees.*') ? 'active' : '' }}"
                >
                    Fee Structure
                </a>

                <a
                    href="{{ route('admin.fees.index') }}"
                    class="submenu-item"
                >
                    Student Fee
                </a>

                <a
                    href="{{ route('admin.fees.index') }}"
                    class="submenu-item"
                >
                    Payment History
                </a>

                <a
                    href="{{ route('admin.scholarship.index') }}"
                    class="submenu-item {{ request()->routeIs('admin.scholarship.*') ? 'active' : '' }}"
                >
                    Scholarship
                </a>

            </div>


            <!-- =================================================
                 EXAM
            ================================================== -->

            <a
                href="{{ route('admin.exams.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}"
                data-search="exam examination"
            >

                <span class="sidebar-icon">▣</span>

                <span class="sidebar-label">
                    Exam
                </span>

            </a>


            <!-- =================================================
                 RESULT
            ================================================== -->

            @php
                $resultActive = request()->routeIs('admin.results.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $resultActive ? 'active open' : '' }}"
                data-submenu="result-menu"
                data-search="result results marks grade"
            >

                <span class="sidebar-icon">▥</span>

                <span class="sidebar-label">
                    Result
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $resultActive ? 'open' : '' }}"
                id="result-menu"
            >

                <a
                    href="{{ route('admin.results.index') }}"
                    class="submenu-item"
                >
                    Student Result
                </a>

                <a
                    href="{{ route('admin.results.index') }}"
                    class="submenu-item"
                >
                    Grade Management
                </a>

                <a
                    href="{{ route('admin.results.index') }}"
                    class="submenu-item"
                >
                    Publish Result
                </a>

                <a
                    href="{{ route('admin.results.index') }}"
                    class="submenu-item"
                >
                    Result History
                </a>

                <a
                    href="{{ route('admin.results.index') }}"
                    class="submenu-item"
                >
                    Result Report
                </a>

            </div>


            <!-- =================================================
                 NOTICE
            ================================================== -->

            @php
                $noticeActive = request()->routeIs('admin.notices.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $noticeActive ? 'active open' : '' }}"
                data-submenu="notice-menu"
                data-search="notice notices announcement"
            >

                <span class="sidebar-icon">⚑</span>

                <span class="sidebar-label">
                    Notice
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $noticeActive ? 'open' : '' }}"
                id="notice-menu"
            >

                <a
                    href="{{ route('admin.notices.index') }}"
                    class="submenu-item"
                >
                    All Notices
                </a>

                <a
                    href="{{ route('admin.notices.index') }}"
                    class="submenu-item"
                >
                    Create Notice
                </a>

                <a
                    href="{{ route('admin.notices.index') }}"
                    class="submenu-item"
                >
                    Publish
                </a>

            </div>


            <!-- =================================================
                 LIBRARY
            ================================================== -->

            @php
                $libraryActive = request()->routeIs('admin.library.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $libraryActive ? 'active open' : '' }}"
                data-submenu="library-menu"
                data-search="library books issue return fine"
            >

                <span class="sidebar-icon">▤</span>

                <span class="sidebar-label">
                    Library
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $libraryActive ? 'open' : '' }}"
                id="library-menu"
            >

                <a
                    href="{{ route('admin.library.index') }}"
                    class="submenu-item"
                >
                    Total Books
                </a>

                <a
                    href="{{ route('admin.library.index') }}"
                    class="submenu-item"
                >
                    Book Categories
                </a>

                <a
                    href="{{ route('admin.library.index') }}"
                    class="submenu-item"
                >
                    Add Books
                </a>

                <a
                    href="{{ route('admin.library.index') }}"
                    class="submenu-item"
                >
                    Issues / Returns / Fine
                </a>

                <a
                    href="{{ route('admin.library.index') }}"
                    class="submenu-item"
                >
                    Incharge Profile
                </a>

                <a
                    href="{{ route('admin.library.index') }}"
                    class="submenu-item"
                >
                    Reports
                </a>

            </div>


            <!-- =================================================
                 OTHER
            ================================================== -->

            <div class="sidebar-section-title other-title">
                OTHER
            </div>


            <!-- =================================================
                 TRANSPORT
            ================================================== -->

            @php
                $transportActive = request()->routeIs('admin.transport.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $transportActive ? 'active open' : '' }}"
                data-submenu="transport-menu"
                data-search="transport bus vehicle"
            >

                <span class="sidebar-icon">▣</span>

                <span class="sidebar-label">
                    Transport
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $transportActive ? 'open' : '' }}"
                id="transport-menu"
            >

                <a
                    href="{{ route('admin.transport.index') }}"
                    class="submenu-item"
                >
                    Transport Records
                </a>

                <a
                    href="{{ route('admin.transport.index') }}"
                    class="submenu-item"
                >
                    Routes
                </a>

                <a
                    href="{{ route('admin.transport.index') }}"
                    class="submenu-item"
                >
                    Vehicles
                </a>

            </div>


            <!-- =================================================
                 MEAL MANAGEMENT
            ================================================== -->

            @php
                $mealActive = request()->routeIs('admin.meals.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $mealActive ? 'active open' : '' }}"
                data-submenu="meal-menu"
                data-search="meal meals food stock"
            >

                <span class="sidebar-icon">♨</span>

                <span class="sidebar-label">
                    Meal Management
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $mealActive ? 'open' : '' }}"
                id="meal-menu"
            >

                <a
                    href="{{ route('admin.meals.index') }}"
                    class="submenu-item"
                >
                    Stock In / Stock Out
                </a>

                <a
                    href="{{ route('admin.meals.index') }}"
                    class="submenu-item"
                >
                    Logs
                </a>

            </div>


            <!-- =================================================
                 PAYROLL
            ================================================== -->

            <a
                href="{{ route('admin.payroll.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}"
                data-search="payroll salary"
            >

                <span class="sidebar-icon">▤</span>

                <span class="sidebar-label">
                    Payroll
                </span>

            </a>


            <!-- =================================================
                 SPORTS
            ================================================== -->

            <a
                href="{{ route('admin.sports.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.sports.*') ? 'active' : '' }}"
                data-search="sports"
            >

                <span class="sidebar-icon">♜</span>

                <span class="sidebar-label">
                    Sports
                </span>

            </a>


            <!-- =================================================
                 SCHOLARSHIP
            ================================================== -->

            <a
                href="{{ route('admin.scholarship.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.scholarship.*') ? 'active' : '' }}"
                data-search="scholarship"
            >

                <span class="sidebar-icon">♢</span>

                <span class="sidebar-label">
                    Scholarship
                </span>

            </a>


            <!-- =================================================
                 CLASS
            ================================================== -->

            @php
                $classActive = request()->routeIs('admin.classes.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $classActive ? 'active open' : '' }}"
                data-submenu="class-menu"
                data-search="class classes division subjects"
            >

                <span class="sidebar-icon">▦</span>

                <span class="sidebar-label">
                    Class
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $classActive ? 'open' : '' }}"
                id="class-menu"
            >

                <a
                    href="{{ route('admin.classes.index') }}"
                    class="submenu-item"
                >
                    Classes
                </a>

                <a
                    href="{{ route('admin.classes.index') }}"
                    class="submenu-item"
                >
                    Subjects
                </a>

            </div>


            <!-- =================================================
                 SETTINGS
            ================================================== -->

            @php
                $settingsActive = request()->routeIs('admin.settings.*');
            @endphp

            <button
                type="button"
                class="sidebar-item has-submenu {{ $settingsActive ? 'active open' : '' }}"
                data-submenu="settings-menu"
                data-search="settings role permission users backup"
            >

                <span class="sidebar-icon">⚙</span>

                <span class="sidebar-label">
                    Settings
                </span>

                <span class="sidebar-arrow">›</span>

            </button>


            <div
                class="submenu {{ $settingsActive ? 'open' : '' }}"
                id="settings-menu"
            >

                <a
                    href="{{ route('admin.settings.index') }}"
                    class="submenu-item {{ $settingsActive ? 'active' : '' }}"
                >
                    School Profile
                </a>

                <a
                    href="javascript:void(0)"
                    class="submenu-item"
                    onclick="alert('User Roles & Permissions module is coming soon.')"
                >
                    User Roles & Permission
                </a>

                <a
                    href="javascript:void(0)"
                    class="submenu-item"
                    onclick="alert('Backup & Recovery module is coming soon.')"
                >
                    Backup & Recovery
                </a>

            </div>

        </div>


        <!-- =========================================================
             LOGOUT
        ========================================================== -->

        <div class="logout-area">

            <form method="POST" action="{{ route('admin.logout') }}">

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >

                    <span>⇥</span>

                    <span>
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </aside>


    <!-- =========================================================
         MOBILE OVERLAY
    ========================================================== -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


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
                aria-label="Toggle sidebar"
            >
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
                        title="Search"
                    >
                        ↵
                    </button>

                </div>


                <div
                    class="search-results"
                    id="searchResults"
                ></div>

            </div>


            <!-- HEADER ACTIONS -->

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
                © {{ date('Y') }} {{ $schoolName }}.
                All rights reserved.
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

    /* ==========================================================
       SIDEBAR MAIN SUBMENUS
    ========================================================== */

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
             * Close all other main submenus
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


    /* ==========================================================
       SCHOOL SUPPLIES NESTED MENU
    ========================================================== */

    const nestedMenuButtons =
        document.querySelectorAll('.nested-menu-toggle');

    nestedMenuButtons.forEach(function (button) {

        button.addEventListener('click', function (event) {

            event.preventDefault();
            event.stopPropagation();

            const menuId =
                this.getAttribute('data-nested-menu');

            const menu =
                document.getElementById(menuId);

            if (!menu) {
                return;
            }

            this.classList.toggle('open');

            menu.classList.toggle('open');

        });

    });


    /* ==========================================================
       MOBILE SIDEBAR
    ========================================================== */

    const menuToggle =
        document.getElementById('menuToggle');

    const sidebar =
        document.getElementById('sidebar');

    const sidebarOverlay =
        document.getElementById('sidebarOverlay');


    if (menuToggle) {

        menuToggle.addEventListener('click', function () {

            sidebar.classList.toggle('mobile-open');

            sidebarOverlay.classList.toggle('active');

        });

    }


    if (sidebarOverlay) {

        sidebarOverlay.addEventListener('click', function () {

            sidebar.classList.remove('mobile-open');

            sidebarOverlay.classList.remove('active');

        });

    }


    /* ==========================================================
       GLOBAL SEARCH
    ========================================================== */

    const searchInput =
        document.getElementById('globalSearch');

    const searchButton =
        document.getElementById('searchButton');

    const searchResults =
        document.getElementById('searchResults');


    const searchableItems = [];


    /*
     * Main sidebar items
     */

    document
        .querySelectorAll('.sidebar-item[data-search]')
        .forEach(function (item) {

            searchableItems.push({

                text:
                    item.innerText.trim(),

                keywords:
                    item.getAttribute('data-search'),

                element:
                    item

            });

        });


    /*
     * Normal submenu links
     */

    document
        .querySelectorAll('.submenu-item')
        .forEach(function (item) {

            searchableItems.push({

                text:
                    item.innerText.trim(),

                keywords:
                    item.innerText.trim(),

                element:
                    item

            });

        });


    /*
     * Nested School Supply menu
     */

    document
        .querySelectorAll('.nested-menu-toggle')
        .forEach(function (item) {

            searchableItems.push({

                text:
                    item.innerText.trim(),

                keywords:
                    'school supplies kit supply items government kit templates student kit distribution',

                element:
                    item

            });

        });


    /* ==========================================================
       SEARCH FUNCTION
    ========================================================== */

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

                    No results found for

                    "<strong>${query}</strong>"

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
                     * Normal link
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
                     * Main parent menu
                     */

                    if (

                        item.element.classList
                            .contains('has-submenu')

                    ) {

                        item.element.click();

                    }


                    /*
                     * Nested menu
                     */

                    if (

                        item.element.classList
                            .contains('nested-menu-toggle')

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


    /* ==========================================================
       SEARCH WHILE TYPING
    ========================================================== */

    searchInput.addEventListener(
        'input',
        performSearch
    );


    /* ==========================================================
       SEARCH BUTTON
    ========================================================== */

    searchButton.addEventListener(
        'click',
        performSearch
    );


    /* ==========================================================
       ENTER TO SEARCH
    ========================================================== */

    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                performSearch();

            }

        }
    );


    /* ==========================================================
       CLOSE SEARCH RESULTS
    ========================================================== */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.closest('.header-search')
            ) {

                searchResults.classList.remove('show');

            }

        }
    );


    /* ==========================================================
       CTRL + K SEARCH SHORTCUT
    ========================================================== */

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


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
></script>

@stack('scripts')

</body>

</html>
