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
</head>

<body>
<div id="topbar"></div>

<div class="page-loader" id="pageLoader" role="status" aria-live="polite">
    <div class="loader-card">
        <div class="loader-dots"><i></i><i></i><i></i><i></i></div>
        <div class="loader-text" id="loaderText">Please wait...</div>
        <div class="loader-sub">This may take a few moments.</div>
    </div>
</div>
@php
    /* Safe route helper: returns '#' if a route doesn't exist. */
    $route = function ($name, $fallback = '#') {
        try {
            return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
        } catch (\Throwable $e) {
            return $fallback;
        }
    };

    /*
     * Sidebar definition.
     * l = label, i = icon, a = route patterns that mark the item active,
     * r = route (single link) or c = children [label, route name].
     */
    $menu = [
        'Main' => [
            ['l'=>'Dashboard','i'=>'fa-gauge-high','a'=>['admin.dashboard'],'r'=>'admin.dashboard'],
            ['l'=>'Student','i'=>'fa-user-graduate','a'=>['admin.students.*','admin.student-*','admin.id-card.*'],
                'c'=>[['All Students','admin.students.index'],['Student Profile','admin.students.index'],['Student Documents','admin.students.index'],['Student ID','admin.students.index'],['Attendance','admin.attendance.students'],['School Supplies (Kit)','admin.students.index'],['Student Report','admin.students.index'],['Add Student','admin.students.index']]],
            ['l'=>'Faculty (Teacher)','i'=>'fa-chalkboard-user','a'=>['admin.faculty.*'],
                'c'=>[['All Faculty','admin.faculty.index'],['Teacher Allocation','admin.faculty.index']]],
            ['l'=>'Other Staff','i'=>'fa-users','a'=>['admin.other-staff.*'],
                'c'=>[['All Staff','admin.other-staff.index'],['Add Staff','admin.other-staff.create']]],
            ['l'=>'Time Table','i'=>'fa-table-cells','a'=>['admin.timetable.*'],
                'c'=>[['Class Timetable','admin.timetable.index'],['Teacher Timetable','admin.timetable.index'],['Create Timetable','admin.timetable.index']]],
            ['l'=>'Attendance','i'=>'fa-check','a'=>['admin.attendance.*','admin.teachers.attendance.*'],
                'c'=>[['Student Attendance','admin.attendance.students'],['Faculty Attendance','admin.teachers.attendance.index']]],
            ['l'=>'Fees','i'=>'fa-indian-rupee-sign','a'=>['admin.fees.*'],
                'c'=>[['Fee Structure','admin.fees.index'],['Student Fee','admin.fees.index'],['Payment History','admin.fees.index'],['Scholarship','admin.scholarship.index']]],
            ['l'=>'Exam','i'=>'fa-file-lines','a'=>['admin.exam.*'],'r'=>'admin.exam.index'],
            ['l'=>'Result','i'=>'fa-chart-column','a'=>['admin.results.*'],
                'c'=>[['Student Result','admin.results.index'],['Grade Management','admin.results.index'],['Publish Result','admin.results.index'],['Result History','admin.results.index'],['Result Report','admin.results.index']]],
            ['l'=>'Notice','i'=>'fa-flag','a'=>['admin.notices.*'],
                'c'=>[['All Notices','admin.notices.index'],['Add Notice','admin.notices.create']]],
            ['l'=>'Library','i'=>'fa-book','a'=>['admin.library.*'],
                'c'=>[['Total Books','admin.library.books.index'],['Issues / Returns / Fine','admin.library.issues.index'],['Librarian','admin.library.librarian.index'],['Reports','admin.library.reports.index']]],
        ],
        'Other' => [
            ['l'=>'Transport','i'=>'fa-bus','a'=>['admin.transport.*'],
                'c'=>[['Transport Records','admin.transport.index'],['Routes','admin.transport.index'],['Vehicles','admin.transport.index']]],
            ['l'=>'Meal Management','i'=>'fa-utensils','a'=>['admin.meal.*','admin.meals.*'],
                'c'=>[['Stock In / Stock Out','admin.meal.items.index'],['Logs','admin.meal.logs.index']]],
            ['l'=>'Payroll','i'=>'fa-money-check-dollar','a'=>['admin.teachers.salary.*'],'r'=>'admin.teachers.salary.index'],
            ['l'=>'Sports','i'=>'fa-futbol','a'=>['admin.sports.*'],
                'c'=>[['Games / Events','admin.sports.games.index'],['Achievements','admin.sports.achievements.index'],['Sports Equipments','admin.sports.equipment.index']]],
            ['l'=>'Scholarship','i'=>'fa-graduation-cap','a'=>['admin.scholarship.*'],'r'=>'admin.scholarship.index'],
            ['l'=>'Class','i'=>'fa-chalkboard','a'=>['admin.classes.*','admin.subjects.*'],
                'c'=>[['Classes','admin.classes.index'],['Subjects','admin.subjects.index']]],
            ['l'=>'Reports','i'=>'fa-chart-pie','a'=>['admin.reports.*'],'r'=>'admin.reports.index'],
            ['l'=>'Settings','i'=>'fa-gear','a'=>['admin.settings.*','admin.backup.*'],
                'c'=>[['School Profile','admin.settings.index'],['User Roles & Permission','admin.settings.index'],['Backup & Recovery','admin.backup.index']]],
        ],
    ];
@endphp

<div class="app-wrapper">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">
                <img src="{{ $schoolLogo ?? asset('images/gurukullogo.png') }}"
                     alt="{{ $schoolName ?? 'School' }} logo"
                     onerror="this.onerror=null;this.src='{{ asset('images/gurukullogo.png') }}';">
            </div>
            <div>
                <div class="brand-title">{{ $schoolName ?? 'Gurukul Vidyalaya' }}</div>
                <div class="brand-subtitle">School management</div>
            </div>
        </div>

        <nav class="sidebar-content" aria-label="Main navigation">
            @foreach ($menu as $group => $items)
                <div class="nav-group">{{ $group }}</div>

                @foreach ($items as $n => $item)
                    @php
                        $isActive = request()->routeIs(...$item['a']);
                        $id = \Illuminate\Support\Str::slug($group . '-' . $item['l']);
                    @endphp

                    @if (isset($item['c']))
                        <button type="button" class="nav-item has-submenu {{ $isActive ? 'active open' : '' }}" data-submenu="{{ $id }}">
                            <span class="nav-icon"><i class="fas {{ $item['i'] }}"></i></span>
                            <span class="nav-label">{{ $item['l'] }}</span>
                            <i class="fas fa-chevron-right nav-arrow"></i>
                        </button>
                        <div class="submenu {{ $isActive ? 'open' : '' }}" id="{{ $id }}">
                            @php $matched = false; @endphp
                            @foreach ($item['c'] as $child)
                                @php
                                    // Several children can share one route; highlight only the first match.
                                    $childActive = !$matched && request()->routeIs($child[1]);
                                    if ($childActive) { $matched = true; }
                                @endphp
                                <a href="{{ $route($child[1]) }}"
                                   class="submenu-item {{ $childActive ? 'active' : '' }}">{{ $child[0] }}</a>
                            @endforeach
                        </div>
                    @elseif (\Illuminate\Support\Facades\Route::has($item['r']))
                        <a href="{{ route($item['r']) }}" class="nav-item {{ $isActive ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fas {{ $item['i'] }}"></i></span>
                            <span class="nav-label">{{ $item['l'] }}</span>
                        </a>
                    @endif
                @endforeach
            @endforeach
        </nav>

        <div class="logout-area">
            <form method="POST" action="{{ $route('admin.logout') }}">
                @csrf
                <button type="submit" class="logout-button">
                    <span class="nav-icon"><i class="fas fa-arrow-right-from-bracket"></i></span>
                    <span>Log out</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ================= MAIN ================= --}}
    <div class="main-area">

        <header class="top-header">
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <div class="page-title">@yield('page-title', 'Dashboard')</div>

            <div class="header-actions">
                <div class="header-date"><b id="liveClock">{{ now()->format('h:i A') }}</b>{{ now()->format('l, d F Y') }}</div>
                <div class="admin-profile">
                    <div class="avatar-wrap"><div class="admin-avatar">A</div><span class="status-dot" title="Online"></span></div>
                    <div class="admin-info">
                        <div class="admin-name">Admin <span class="online-pill"><i></i>Online</span></div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="app-footer">
            <div>© {{ date('Y') }} Gurukul Vidyalaya. All rights reserved.</div>
            <div>School Management System v1.0.0</div>
        </footer>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.has-submenu');

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            const menu = document.getElementById(this.dataset.submenu);
            if (!menu) return;
            const open = !menu.classList.contains('open');

            buttons.forEach(function (other) {
                if (other !== button) {
                    other.classList.remove('open');
                    const m = document.getElementById(other.dataset.submenu);
                    if (m) m.classList.remove('open');
                }
            });

            button.classList.toggle('open', open);
            menu.classList.toggle('open', open);
        });
    });

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    document.getElementById('menuToggle').addEventListener('click', function () {
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', function () {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    });
});
</script>

<script>
(function(){
    const el=document.getElementById('liveClock');
    if(!el) return;
    function tick(){el.textContent=new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});}
    tick(); setInterval(tick,15000);
})();
</script>

<script>
(function () {
    const root = document.documentElement;
    const bar = document.getElementById('topbar');
    const loader = document.getElementById('pageLoader');
    const loaderText = document.getElementById('loaderText');
    const started = performance.now();
    let creep = null;

    /* ----- top progress bar ----- */
    function barStart() {
        clearInterval(creep);
        bar.style.transition = 'none';
        bar.style.opacity = 1;
        bar.style.width = '0';
        bar.offsetWidth;
        bar.style.transition = 'width .4s ease, opacity .35s ease';
        let w = 12;
        bar.style.width = w + '%';
        creep = setInterval(function () {
            w += (90 - w) * 0.08;
            bar.style.width = w + '%';
        }, 250);
    }
    function barDone() {
        clearInterval(creep);
        bar.style.width = '100%';
        setTimeout(function () { bar.style.opacity = 0; }, 350);
    }

    /* ----- skeleton: remove once page is ready (min 600 ms so it never flickers) ----- */
    function ready() {
        const wait = Math.max(0, 600 - (performance.now() - started));
        setTimeout(function () { root.classList.remove('is-loading'); barDone(); }, wait);
    }
    barStart();
    if (document.readyState === 'complete') { ready(); } else { window.addEventListener('load', ready); }
    setTimeout(function () { root.classList.remove('is-loading'); }, 5000);

    /* ----- navigation: links ----- */
    document.addEventListener('click', function (e) {
        const a = e.target.closest('a[href]');
        if (!a || e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey) return;
        if (a.target === '_blank' || a.hasAttribute('download')) return;
        const href = a.getAttribute('href');
        if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) return;
        if (a.origin !== location.origin) return;
        barStart();
    });

    /* ----- forms: button spinner + optional full-screen loader ----- */
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (e.defaultPrevented) return;          // e.g. user pressed Cancel on a confirm()
        barStart();

        const btn = form.querySelector('button[type="submit"], button:not([type])');
        if (btn && !btn.classList.contains('is-busy')) {
            btn.classList.add('is-busy');
            btn.setAttribute('aria-busy', 'true');
            btn.insertAdjacentHTML('afterbegin', '<span class="btn-spin"></span>');
        }

        const msg = form.getAttribute('data-loader');
        if (msg) {
            loaderText.textContent = msg;
            loader.classList.add('show');
        }
    });

    /* ----- coming back with the browser Back button ----- */
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;
        loader.classList.remove('show');
        document.querySelectorAll('.is-busy').forEach(function (b) {
            b.classList.remove('is-busy');
            b.removeAttribute('aria-busy');
            const sp = b.querySelector('.btn-spin');
            if (sp) sp.remove();
        });
        root.classList.remove('is-loading');
        barDone();
    });
})();
</script>

@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>