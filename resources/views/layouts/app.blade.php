<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'School Management')</title>
    <script>document.documentElement.classList.add('is-loading');</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        :root{
            --ink:#1d3a33; --ink-2:#2a4a41; --ink-line:rgba(238,242,230,.18);
            --side-text:#d9e1d0; --chalk:#f2b632; --wood:#8a5a2c;
            --accent:#2b5d8a; --accent-soft:#e6eef6;
            --text:#26302c; --muted:#6a756f; --line:#e6e0d2; --bg:#fbf8f1; --white:#fff;
            --red:#e4572e; --green:#3a9d6b;
            --sidebar-width:268px; --header-height:66px;
            --serif:'Fraunces',Georgia,serif; --hand:'Caveat','Comic Sans MS',cursive;
        }
        html{scroll-behavior:smooth}
        body{font-family:'Public Sans',Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;font-size:14px}
        a{text-decoration:none;color:inherit}
        button{font-family:inherit}
        :focus-visible{outline:2px solid var(--chalk);outline-offset:2px}

        .app-wrapper{min-height:100vh;display:flex}

        /* ---------- Chalkboard sidebar ---------- */
        .sidebar{position:fixed;inset:0 auto 0 0;width:var(--sidebar-width);z-index:1000;display:flex;flex-direction:column;transition:transform .3s ease;
            background:radial-gradient(ellipse at 18% 8%,rgba(255,255,255,.08),transparent 55%),radial-gradient(ellipse at 85% 88%,rgba(255,255,255,.06),transparent 50%),var(--ink);
            border-right:8px solid var(--wood);box-shadow:inset -3px 0 6px rgba(0,0,0,.28)}
        .sidebar-brand{height:var(--header-height);display:flex;align-items:center;gap:12px;padding:0 18px;border-bottom:2px dashed var(--ink-line);flex-shrink:0}
        .brand-icon{width:42px;height:42px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;box-shadow:0 0 0 3px var(--ink),0 0 0 4px var(--chalk)}
        .brand-icon img{width:100%;height:100%;object-fit:contain}
        .brand-title{font-family:var(--serif);font-size:16px;font-weight:700;color:#fff;line-height:1.2}
        .brand-subtitle{font-family:var(--hand);font-size:17px;color:var(--chalk);line-height:1}

        .sidebar-content{flex:1;overflow-y:auto;padding:8px 12px 18px 14px}
        .sidebar-content::-webkit-scrollbar{width:5px}
        .sidebar-content::-webkit-scrollbar-thumb{background:var(--ink-line);border-radius:10px}

        .nav-group{font-family:var(--hand);font-size:21px;font-weight:700;color:var(--chalk);padding:16px 10px 4px;letter-spacing:.3px}

        .nav-item{position:relative;width:100%;min-height:40px;display:flex;align-items:center;gap:12px;padding:9px 12px;margin-bottom:2px;border:none;border-radius:8px;background:transparent;color:var(--side-text);font-size:14px;font-weight:500;text-align:left;cursor:pointer;transition:background .15s,color .15s}
        .nav-item:hover{background:rgba(255,255,255,.08);color:#fff}
        .nav-item.active{background:rgba(242,182,50,.15);color:#fff}
        .nav-item.active::before{content:"";position:absolute;left:-14px;top:8px;bottom:8px;width:5px;border-radius:0 5px 5px 0;background:var(--chalk)}
        .nav-icon{width:20px;text-align:center;font-size:15px;flex-shrink:0;opacity:.9}
        .nav-item.active .nav-icon{color:var(--chalk);opacity:1}
        .nav-label{flex:1}
        .nav-arrow{font-size:10px;transition:transform .2s}
        .nav-item.open .nav-arrow{transform:rotate(90deg)}

        .submenu{display:none;margin:2px 0 6px 22px;padding-left:12px;border-left:2px dashed var(--ink-line)}
        .submenu.open{display:block}
        .submenu-item{display:block;padding:7px 12px;margin-bottom:1px;border-radius:7px;color:var(--side-text);font-size:13.5px;transition:background .15s,color .15s}
        .submenu-item:hover{color:#fff;background:rgba(255,255,255,.08)}
        .submenu-item.active{color:var(--ink);background:var(--chalk);font-weight:600}

        .logout-area{padding:12px 14px;border-top:2px dashed var(--ink-line);flex-shrink:0}
        .logout-button{width:100%;display:flex;align-items:center;gap:12px;padding:10px 12px;border:none;border-radius:8px;background:transparent;color:var(--side-text);font-size:14px;font-weight:500;cursor:pointer}
        .logout-button:hover{background:rgba(228,87,46,.18);color:#ffb3a1}

        /* ---------- Main ---------- */
        .main-area{margin-left:var(--sidebar-width);width:calc(100% - var(--sidebar-width));min-height:100vh;display:flex;flex-direction:column}
        .top-header{height:var(--header-height);background:var(--white);display:flex;align-items:center;gap:14px;padding:0 28px;position:sticky;top:0;z-index:900;border-bottom:1px solid var(--line)}
        .top-header::after{content:"";position:absolute;left:0;right:0;bottom:-1px;height:4px;background:linear-gradient(90deg,var(--red) 0 25%,var(--chalk) 25% 50%,var(--green) 50% 75%,var(--accent) 75% 100%)}
        .menu-toggle{display:none;width:38px;height:38px;border:none;background:transparent;border-radius:8px;font-size:20px;color:var(--muted);cursor:pointer}
        .page-title{font-family:var(--serif);font-size:20px;font-weight:700;color:var(--ink)}
        .header-actions{margin-left:auto;display:flex;align-items:center;gap:14px}
        .header-date{font-size:13px;color:var(--muted);text-align:right;line-height:1.3}
        .header-date b{display:block;font-family:var(--serif);font-size:15px;color:var(--ink)}
        .admin-profile{display:flex;align-items:center;gap:10px;padding-left:14px;border-left:1px solid var(--line)}
        .admin-avatar{width:38px;height:38px;border-radius:50%;background:var(--ink);color:var(--chalk);display:flex;align-items:center;justify-content:center;font-family:var(--hand);font-weight:700;font-size:22px;box-shadow:0 0 0 2px #fff,0 0 0 3px var(--wood)}

        .avatar-wrap{position:relative;flex-shrink:0}
        .status-dot{position:absolute;right:-1px;bottom:0;width:12px;height:12px;border-radius:50%;background:#22c55e;border:2px solid #fff}
        .status-dot::after{content:"";position:absolute;inset:-2px;border-radius:50%;border:2px solid #22c55e;opacity:0;animation:ping 2.2s ease-out infinite}
        @keyframes ping{0%{transform:scale(.8);opacity:.7}100%{transform:scale(2);opacity:0}}
        .online-pill{display:inline-flex;align-items:center;gap:5px;margin-left:6px;padding:1px 8px 1px 6px;border-radius:20px;background:#e3f6ea;color:#17843f;font-size:11px;font-weight:600;vertical-align:1px}
        .online-pill i{width:6px;height:6px;border-radius:50%;background:#22c55e}
        .admin-name{font-size:13px;font-weight:600;line-height:1.2}
        .admin-role{font-size:12px;color:var(--muted)}

        /* ruled notebook paper */
        .main-content{flex:1;width:100%;padding:28px;background-color:var(--bg);background-image:repeating-linear-gradient(transparent 0 31px,rgba(43,93,138,.08) 31px 32px)}
        .app-footer{min-height:46px;background:var(--white);border-top:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 28px;color:var(--muted);font-size:12px}

        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:999}

        @media (max-width:1000px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.mobile-open{transform:translateX(0)}
            .sidebar-overlay.active{display:block}
            .main-area{margin-left:0;width:100%}
            .menu-toggle{display:block}
            .top-header{padding:0 16px}
            .header-date{display:none}
        }
        @media (max-width:700px){
            .main-content{padding:16px}
            .admin-info{display:none}
            .app-footer{padding:12px 16px;flex-direction:column;gap:4px}
        }

        /* ---------- Loaders ---------- */
        #topbar{position:fixed;top:0;left:0;height:4px;width:0;z-index:3000;opacity:1;pointer-events:none;
            background:linear-gradient(90deg,var(--red) 0 25%,var(--chalk) 25% 50%,var(--green) 50% 75%,var(--accent) 75% 100%);
            border-radius:0 4px 4px 0;box-shadow:0 0 8px rgba(242,182,50,.6);transition:width .4s ease,opacity .35s ease}

        /* skeleton: cards turn into shimmering placeholders until the page is ready */
        .is-loading .paper,.is-loading .card{position:relative;overflow:hidden;border-color:transparent}
        .is-loading .paper>*,.is-loading .card>*{visibility:hidden}
        .is-loading .paper::after,.is-loading .card::after{content:"";position:absolute;inset:0;z-index:5;border-radius:inherit;
            background:linear-gradient(90deg,#eee8d8 25%,#f9f5ea 50%,#eee8d8 75%);background-size:220% 100%;animation:shimmer 1.3s linear infinite}
        .is-loading .kpi-tab{visibility:hidden}
        @keyframes shimmer{0%{background-position:120% 0}100%{background-position:-120% 0}}

        /* button spinner */
        .btn-spin{display:inline-block;width:14px;height:14px;margin-right:8px;border:2px solid currentColor;border-right-color:transparent;border-radius:50%;vertical-align:-2px;animation:spin .7s linear infinite}
        .is-busy{pointer-events:none;opacity:.8}
        @keyframes spin{to{transform:rotate(360deg)}}

        /* full-screen loader for long actions (add data-loader="Message" to a form) */
        .page-loader{position:fixed;inset:0;z-index:2500;display:none;align-items:center;justify-content:center;background:rgba(29,58,51,.65);backdrop-filter:blur(2px)}
        .page-loader.show{display:flex}
        .loader-card{min-width:260px;padding:26px 34px 22px;text-align:center;color:#fff;border-radius:14px;border:6px solid var(--wood);
            background:radial-gradient(ellipse at 20% 0,rgba(255,255,255,.1),transparent 60%),var(--ink);box-shadow:0 18px 40px rgba(0,0,0,.35)}
        .loader-dots{display:flex;justify-content:center;gap:9px;height:26px;margin-bottom:8px}
        .loader-dots i{width:14px;height:14px;border-radius:50%;animation:bounce .9s ease-in-out infinite}
        .loader-dots i:nth-child(1){background:var(--red)}
        .loader-dots i:nth-child(2){background:var(--chalk);animation-delay:.12s}
        .loader-dots i:nth-child(3){background:var(--green);animation-delay:.24s}
        .loader-dots i:nth-child(4){background:#5aa0d6;animation-delay:.36s}
        @keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
        .loader-text{font-family:var(--hand);font-size:26px;color:var(--chalk);line-height:1.1}
        .loader-sub{font-size:12.5px;color:#c5d0bd;margin-top:4px}

        @media (prefers-reduced-motion:reduce){.is-loading .paper::after,.is-loading .card::after{animation:none}}
        @media (prefers-reduced-motion:reduce){*{transition:none!important}}
    </style>

    @stack('styles')
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

           @php
    $studentMenuActive = request()->routeIs(
        'admin.students.*',
        'admin.student-profile.*',
        'admin.student-documents.*',
        'admin.id-card.*',
        'admin.attendance.*',
        'admin.government-schemes.*',
        'admin.student-general-register.*',
        'admin.student-health.*'
    );
@endphp

@php
    $user = auth()->user();

    $isSuperAdmin = $user?->role?->name === 'super_admin';

    $can = function (string $permission) use ($user, $isSuperAdmin): bool {
        if ($isSuperAdmin) {
            return true;
        }

        return $user?->hasPermission($permission) ?? false;
    };

    $canAny = function (array $permissions) use ($user, $isSuperAdmin): bool {
        if ($isSuperAdmin) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($user?->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    };
@endphp

            {{-- DASHBOARD --}}
            @if($can('dashboard.view'))
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-search="dashboard home">
                    <span class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="sidebar-label">Dashboard</span>
                </a>
            @endif

            {{-- STUDENT --}}
            @if($canAny(['students.view','students.create','students.edit','students.delete']))
                <button class="sidebar-item has-submenu {{ request()->routeIs('admin.students.*') ? 'active open' : '' }}" data-submenu="student-menu" data-search="student students">
                    <span class="sidebar-icon"><i class="fa fa-user-graduate"></i></span>
                    <span class="sidebar-label">Student</span><span class="sidebar-arrow">›</span>
                </button>
                <div class="submenu {{ request()->routeIs('admin.students.*') ? 'open' : '' }}" id="student-menu">
                    @if($can('students.view'))
                        <a href="{{ route('admin.students.index') }}" class="submenu-item {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">All Students</a>
                        <a href="{{ route('admin.student-profile.index') }}" class="submenu-item">Student Profile</a>
                        <a href="{{ route('admin.student-documents.index') }}" class="submenu-item">Student Documents</a>
                        <a href="{{ route('admin.id-card.index') }}" class="submenu-item">Student ID</a>
                        <a href="{{ route('admin.student-supply-kits.index') }}" class="submenu-item">School Supplies(Kit)</a>
                        <a href="{{ route('admin.student-general-register.index') }}" class="submenu-item">Student Register</a>
                    @endif
                    @if($can('students.create'))
                        <a href="{{ route('admin.students.bulk-create') }}" class="submenu-item">Add Student</a>
                    @endif
                    @if($can('attendance.view'))
                        <a href="{{ route('admin.attendance.index') }}" class="submenu-item">Attendance</a>
                    @endif
                </div>
            @endif

            {{-- FACULTY --}}
@if($canAny(['faculty.view','faculty.create','faculty.edit','faculty.delete']))
    <button class="sidebar-item has-submenu {{ request()->routeIs('admin.teachers.*') ? 'active open' : '' }}"
            data-submenu="faculty-menu"
            data-search="faculty teacher teachers">

        <span class="sidebar-icon">
            <i class="fas fa-chalkboard-teacher"></i>
        </span>

        <span class="sidebar-label">Faculty (Teacher)</span>
        <span class="sidebar-arrow">›</span>
    </button>

    <div class="submenu {{ request()->routeIs('admin.teachers.*') ? 'open' : '' }}"
         id="faculty-menu">

        @if($can('teachers.view'))
            <a href="{{ route('admin.teachers.index') }}" class="submenu-item">
                All Faculty
            </a>
        @endif


        {{-- Student Profile --}}
        @if($can('students.view'))
            <a
                href="{{ route('admin.student-profile.index') }}"
                class="submenu-item {{ request()->routeIs('admin.student-profile.*') ? 'active' : '' }}"
            >
                Student Profile
            </a>
        @endif


        {{-- Student Documents --}}
        @if($can('students.view'))
            <a
                href="{{ route('admin.student-documents.index') }}"
                class="submenu-item {{ request()->routeIs('admin.student-documents.*') ? 'active' : '' }}"
            >
                Student Documents
            </a>
        @endif


        {{-- Student ID --}}
        @if($can('students.view'))
            <a
                href="{{ route('admin.id-card.index') }}"
                class="submenu-item {{ request()->routeIs('admin.id-card.*') ? 'active' : '' }}"
            >
                Student ID
            </a>
        @endif


        {{-- School Supplies (Kit) --}}
        @if($can('students.view'))
            <a
                href="{{ route('admin.government-schemes.index') }}"
                class="submenu-item {{ request()->routeIs('admin.government-schemes.*') ? 'active' : '' }}"
            >
                School Supplies(Kit)
            </a>
        @endif


        {{-- Student Report --}}
        @if($can('students.view'))
            <a
                href="{{ route('admin.student-general-register.index') }}"
                class="submenu-item {{ request()->routeIs('admin.student-general-register.*') ? 'active' : '' }}"
            >
                Student Report
            </a>
        @endif


        {{-- Add Student --}}
        @if($can('students.create'))
            <a
                href="{{ route('admin.students.create') }}"
                class="submenu-item {{ request()->routeIs('admin.students.create') ? 'active' : '' }}"
            >
                Add Student
            </a>
        @endif


        {{-- Attendance --}}
        @if($can('attendance.view'))
            <a
                href="{{ route('admin.attendance.index') }}"
                class="submenu-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}"
            >
                Attendance
        @if($canAny(['teachers.create','teachers.edit']))
            <a href="{{ route('admin.teachers.index') }}" class="submenu-item">
                Teacher Allocation
            </a>
        @endif

    </div>
@endif

            {{-- OTHER STAFF --}}
            @if($canAny(['staff.view','staff.create','staff.edit','staff.delete']))
                <button class="sidebar-item has-submenu {{ request()->routeIs('admin.other-staff.*') ? 'active open' : '' }}" data-submenu="other-staff-menu" data-search="other staff employees librarian accountant receptionist peon driver">
                    <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                    <span class="sidebar-label">Other Staff</span><span class="sidebar-arrow">›</span>
                </button>
                <div class="submenu {{ request()->routeIs('admin.other-staff.*') ? 'open' : '' }}" id="other-staff-menu">
                    @if($can('staff.view'))
                        <a href="{{ route('admin.other-staff.index') }}" class="submenu-item {{ request()->routeIs('admin.other-staff.index') ? 'active' : '' }}">All Staff</a>
                    @endif
                    @if($can('staff.create'))
                        <a href="{{ route('admin.other-staff.create') }}" class="submenu-item {{ request()->routeIs('admin.other-staff.create') ? 'active' : '' }}">Add Staff</a>
                    @endif
                </div>
            @endif

            {{-- TIME TABLE: no dedicated permission exists yet --}}
            @if($isSuperAdmin)
                <button class="sidebar-item has-submenu {{ request()->routeIs('admin.timetable.*') ? 'active open' : '' }}" data-submenu="timetable-menu" data-search="time table timetable schedule">
                    <span class="sidebar-icon"><i class="fas fa-table"></i></span><span class="sidebar-label">Time Table</span><span class="sidebar-arrow">›</span>
                </button>
                <div class="submenu {{ request()->routeIs('admin.timetable.*') ? 'open' : '' }}" id="timetable-menu">
                    <a href="{{ route('admin.timetable.index') }}" class="submenu-item">Class Timetable</a>
                    <a href="{{ route('admin.timetable.index') }}" class="submenu-item">Teacher Timetable</a>
                    <a href="{{ route('admin.timetable.index') }}" class="submenu-item">Create Timetable</a>
                </div>
            @endif

            {{-- ATTENDANCE --}}
            @if($canAny(['attendance.view','attendance.manage']))
                <button class="sidebar-item has-submenu {{ request()->routeIs('admin.attendance.*') ? 'active open' : '' }}" data-submenu="attendance-menu" data-search="attendance student faculty mark">
                    <span class="sidebar-icon"><i class="fas fa-check"></i></span><span class="sidebar-label">Attendance</span><span class="sidebar-arrow">›</span>
                </button>
                <div class="submenu {{ request()->routeIs('admin.attendance.*') ? 'open' : '' }}" id="attendance-menu">
                    @if($can('attendance.view'))
                        <a href="{{ route('admin.attendance.index') }}" class="submenu-item">Student Attendance</a>
                        <a href="{{ route('admin.attendance.index') }}" class="submenu-item">Faculty Attendance</a>
                        <a href="{{ route('admin.attendance.index') }}" class="submenu-item">Attendance Report</a>
                    @endif
                    @if($can('attendance.manage'))
                        <a href="{{ route('admin.attendance.index') }}" class="submenu-item">Manage Attendance</a>
                    @endif
                </div>
            @endif

           {{-- FEES --}}
@if($canAny(['fees.view','fees.manage']))
    <button class="sidebar-item has-submenu {{ request()->routeIs('admin.fees.*') ? 'active open' : '' }}"
            data-submenu="fees-menu"
            data-search="fees fee payment">

        <span class="sidebar-icon">₹</span>
        <span class="sidebar-label">Fees</span>
        <span class="sidebar-arrow">›</span>
    </button>

    <div class="submenu {{ request()->routeIs('admin.fees.*') ? 'open' : '' }}"
         id="fees-menu">

        @if($can('fees.view'))
            <a href="{{ route('admin.fees.index') }}" class="submenu-item">
                Fee Structure
            </a>

            <a href="{{ route('admin.fees.index') }}" class="submenu-item">
                Student Fee
            </a>

            <a href="{{ route('admin.fees.index') }}" class="submenu-item">
                Payment History
            </a>
        @endif

    </div>
@endif
            {{-- EXAM --}}
            @if($canAny(['exams.view','exams.manage']))
    <a href="{{ route('admin.exams.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}"
       data-search="exam examination">

        <span class="sidebar-icon">
            <i class="fas fa-file-alt"></i>
        </span>

        <span class="sidebar-label">Exam</span>
    </a>
@endif

{{-- RESULT --}}
@if($isSuperAdmin)

    <button
        class="sidebar-item has-submenu {{ request()->routeIs('admin.results.*') ? 'active open' : '' }}"
        data-submenu="result-menu"
        data-search="result results marks grade topper toppers"
    >
        <span class="sidebar-icon">
            <i class="fas fa-chart-bar"></i>
        </span>

        <span class="sidebar-label">Result</span>

        <span class="sidebar-arrow">›</span>
    </button>

    <div
        class="submenu {{ request()->routeIs('admin.results.*') ? 'open' : '' }}"
        id="result-menu"
    >

        {{-- Result Dashboard --}}
        <a 
    href="{{ route('admin.results.index') }}" 
    class="submenu-item {{ request()->routeIs('admin.results.index') ? 'active' : '' }}"
>
    <i class="fas fa-home me-2"></i>
    Result Dashboard
</a>

        {{-- Enter Marks --}}
        <a
            href="{{ route('admin.results.marks') }}"
            class="submenu-item {{ request()->routeIs('admin.results.marks') ? 'active' : '' }}"
        >
            <i class="fas fa-edit me-2"></i>
            Enter Marks
        </a>

        {{-- Student Results --}}
        <a
            href="{{ route('admin.results.generate') }}"
            class="submenu-item {{ request()->routeIs('admin.results.generate') ? 'active' : '' }}"
        >
            <i class="fas fa-user-graduate me-2"></i>
            Student Results
        </a>

        {{-- Top 3 Toppers --}}
        <a
            href="{{ route('admin.results.class-wise-toppers') }}"
            class="submenu-item {{ request()->routeIs('admin.results.class-wise-toppers') ? 'active' : '' }}"
        >
            <i class="fas fa-trophy me-2"></i>
            Top 3 Toppers
        </a>

    </div>

@endif


            

            {{-- LIBRARY --}}
            @if($canAny(['library.view','library.books','library.issue','library.return','library.fines','library.reports']))
                <button class="sidebar-item has-submenu {{ request()->routeIs('admin.library.*') ? 'active open' : '' }}" data-submenu="library-menu" data-search="library books issue return fine">
                    <span class="sidebar-icon"><i class="fas fa-book"></i></span><span class="sidebar-label">Library</span><span class="sidebar-arrow">›</span>
                </button>
                <div class="submenu {{ request()->routeIs('admin.library.*') ? 'open' : '' }}" id="library-menu">
                    @if($canAny(['library.view','library.books']))
                        <a href="{{ route('admin.library.books.index') }}" class="submenu-item {{ request()->routeIs('admin.library.books.*') ? 'active' : '' }}">Total Books</a>
                    @endif
                    @if($canAny(['library.issue','library.return','library.fines']))
                        <a href="{{ route('admin.library.issues.index') }}" class="submenu-item {{ request()->routeIs('admin.library.issues.*') || request()->routeIs('admin.library.returns.*') || request()->routeIs('admin.library.fines.*') ? 'active' : '' }}">Issues / Returns / Fine</a>
                    @endif
                    @if($can('library.view'))
                        <a href="{{ route('admin.library.librarian.index') }}" class="submenu-item {{ request()->routeIs('admin.library.librarian.index') ? 'active' : '' }}">Librarian</a>
                    @endif
                    @if($can('library.reports'))
                        <a href="{{ route('admin.library.reports.index') }}" class="submenu-item {{ request()->routeIs('admin.library.reports.*') ? 'active' : '' }}">Reports</a>
                    @endif
                </div>
            @endif

            {{-- OTHER: these modules have no dedicated permissions yet --}}
            @if($isSuperAdmin)
                <div class="sidebar-section-title other-title">OTHER</div>

            <!-- Transport -->

                <button class="sidebar-item has-submenu {{ request()->routeIs('admin.meals.*') ? 'active open' : '' }}" data-submenu="meal-menu" data-search="meal meals food stock">
                    <span class="sidebar-icon"><i class="fas fa-utensils"></i></span><span class="sidebar-label">Meal Management</span><span class="sidebar-arrow">›</span>
                </button>
                <div class="submenu {{ request()->routeIs('admin.meals.*') ? 'open' : '' }}" id="meal-menu">
                   <a href="{{ route('admin.meal.items.stock.index') }}"
   class="submenu-item">
    
    Stock In / Stock Out
</a>
                    <a href="{{ route('admin.meal.logs.index') }}"
   class="submenu-item">
   
    Logs
</a>
                </div>
            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.transport.*') ? 'active open' : '' }}"
                    data-submenu="transport-menu"
                    data-search="transport bus vehicle">

                <span class="sidebar-icon">▣</span>

                <span class="sidebar-label">
                    Transport
                </span>

                <span class="sidebar-arrow">›</span>

            </button>

            <div class="sidebar-item"
     data-search="transport bus vehicle">
    <span class="sidebar-icon">
        <i class="fas fa-bus"></i>
    </span>
    <span class="sidebar-label">Transport</span>
</div>


            <!-- Meal Management -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.meals.*') ? 'active open' : '' }}"
                    data-submenu="meal-menu"
                    data-search="meal meals food stock">

                <span class="sidebar-icon">♨</span>

                <span class="sidebar-label">
                    Meal Management
                </span>

                <span class="sidebar-arrow">›</span>

            </button>

          <div class="submenu {{ request()->routeIs('admin.meal.items.*', 'admin.meal.stock.*', 'admin.meal.logs.*') ? 'open' : '' }}"
     id="meal-menu">

    <a href="{{ route('admin.meal.items.index') }}"
       class="submenu-item {{ request()->routeIs('admin.meal.stock.*') ? 'active' : '' }}">
        Stock In / Stock Out
    </a>

     <a href="{{ route('admin.meal.logs.index') }}"
   class="submenu-item {{ request()->routeIs('admin.meal.logs.*') ? 'active' : '' }}">
    Logs
</a>


</div>


                <a href="{{ route('admin.salary.index') }}"
                   class="submenu-item">
                    Salary
                </a>

['l'=>'Class','i'=>'fa-chalkboard','a'=>['admin.classes.*','admin.subjects.*'],
    'c'=>[
        ['Classes','admin.classes.index'],
        ['Subjects','admin.admin.subjects.index']
    ]
],

['l'=>'Reports','i'=>'fa-chart-pie','a'=>['admin.reports.*'],'r'=>'admin.reports.index'],

                <span class="sidebar-icon">▤</span>

        ],
    ];

@endphp

<div class="app-wrapper">

            <!-- Sports -->

            <a href="{{ route('admin.sports.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.sports.*') ? 'active' : '' }}"
               data-search="sports">

                <span class="sidebar-icon">♜</span>

                <span class="sidebar-label">
                    Sports
                </span>

            </a>


            <!-- Scholarship -->

            @if (Route::has('admin.scholarship.index'))
    <a href="{{ route('admin.scholarship.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.scholarship.*') ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i>
        <span class="sidebar-label">Scholarship</span>
    </a>
@endif
                <span class="sidebar-icon">♢</span>

                <span class="sidebar-label">
                    Scholarship
                </span>

            </a>


            <!-- Class -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.classes.*') ? 'active open' : '' }}"
                    data-submenu="class-menu"
                    data-search="class classes division subjects">

                <span class="sidebar-icon">▦</span>

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

                <button class="sidebar-item has-submenu {{ request()->routeIs('admin.classes.*') || request()->routeIs('admin.subjects.*') ? 'active open' : '' }}" data-submenu="class-menu" data-search="class classes division subjects">
                    <span class="sidebar-icon"><i class="fas fa-chalkboard"></i></span><span class="sidebar-label">Class</span><span class="sidebar-arrow">›</span>
                </button>
                <div class="submenu {{ request()->routeIs('admin.classes.*') || request()->routeIs('admin.subjects.*') ? 'open' : '' }}" id="class-menu">
                    <a href="{{ route('admin.classes.index') }}" class="submenu-item">Classes</a>
                    <a href="{{ route('admin.subjects.index') }}" class="submenu-item">Subjects</a>
                </div>
            @endif
               <a href="{{ route('admin.subjects.index') }}" class="submenu-item">
    Subjects
</a>

        <div class="sidebar-brand">
            <div class="brand-icon">
                <img src="{{ $schoolLogo ?? asset('images/gurukullogo.png') }}"
                     alt="{{ $schoolName ?? 'School' }} logo"
                     onerror="this.onerror=null;this.src='{{ asset('images/gurukullogo.png') }}';">
            </div>


            <!-- Settings -->

            <button class="sidebar-item has-submenu
                    {{ request()->routeIs('admin.settings.*') ? 'active open' : '' }}"
                    data-submenu="settings-menu"
                    data-search="settings role permission users backup">

                <span class="sidebar-icon">⚙</span>

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

                <a href="{{ route('admin.settings.roles.index') }}"
                   class="submenu-item">
                    User Roles & Permission
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="submenu-item">
                    Backup & Recovery
                </a>

            </div>
        </div>

        <!-- =========================================================
             LOGOUT
        ========================================================== -->

        <div class="logout-area">
            <form method="POST" action="{{ $route('admin.logout') }}">
                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >

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


    <!-- =========================================================
         MOBILE OVERLAY
    ========================================================== -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>

    {{-- ================= MAIN ================= --}}
    <div class="main-area">

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
                <div class="header-date"><b id="liveClock">{{ now()->format('h:i A') }}</b>{{ now()->format('l, d F Y') }}</div>
                <div class="admin-profile">
                    <div class="avatar-wrap"><div class="admin-avatar">A</div><span class="status-dot" title="Online"></span></div>
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

        <main class="main-content">
            @yield('content')
        </main>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.has-submenu');

    /* ==========================================================
       SIDEBAR MAIN SUBMENUS
    ========================================================== */

    const submenuButtons =
        document.querySelectorAll('.has-submenu');

    submenuButtons.forEach(function (button) {

        button.addEventListener('click', function () {
            const menu = document.getElementById(this.dataset.submenu);
            if (!menu) return;
            const open = !menu.classList.contains('open');

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

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

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


@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
