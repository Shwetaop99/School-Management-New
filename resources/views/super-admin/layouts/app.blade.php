@php

    use Illuminate\Support\Facades\Route;



    // ---- Who is signed in (uses the Super Admin guard, not the default one) -------------

    $me       = auth('super_admin')->user();

    $meName   = $me->name ?? 'Super Admin';

    $meInitial = strtoupper(substr($meName, 0, 1));



    // ---- Links -------------------------------------------------------------------------

    $dashboardUrl = Route::has('super-admin.dashboard') ? route('super-admin.dashboard') : url()->current();

    $securityUrl  = Route::has('super-admin.security') ? route('super-admin.security') : null;

    $logoutUrl    = Route::has('super-admin.logout') ? route('super-admin.logout') : null;



    // ---- Line icons (24x24, stroke) ------------------------------------------------------

    $icons = [

        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',

        'school'   => '<path d="M3 21h18M5 21V8l7-5 7 5v13"/><path d="M9 21v-6h6v6"/>',

        'card'     => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',

        'rupee'    => '<path d="M6 4h12M6 9h12M6 14h4a5 5 0 0 0 0-10M10 14l7 7"/>',

        'users'    => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.5 3-6 6.5-6s6.5 2.5 6.5 6"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18.5 14.5c2 .8 3 2.6 3 5.5"/>',

        'activity' => '<path d="M3 12h4l3-8 4 16 3-8h4"/>',

        'shield'   => '<path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/>',

        'logout'   => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>',

        'check'    => '<path d="M20 6 9 17l-5-5"/>',

        'pause'    => '<circle cx="12" cy="12" r="9"/><path d="M10 9v6M14 9v6"/>',

        'calendar' => '<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>',

        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',

        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',

    ];



    // Sidebar items: label, icon, href (null = planned, not clickable yet)



$schoolsUrl = \Illuminate\Support\Facades\Route::has('super-admin.schools.index')

    ? route('super-admin.schools.index')

    : url('/control-panel/schools');



$workspaceNav = [
    [
        'Dashboard',
        'grid',
        $dashboardUrl,
        request()->routeIs('super-admin.dashboard'),
        false,
    ],
    [
        'School management',
        'school',
        $schoolsUrl,
        request()->routeIs('super-admin.schools.*'),
        false,
    ],
    
[
    'Subscriptions',
    'card',
    Route::has('super-admin.subscriptions.index')
        ? route('super-admin.subscriptions.index')
        : url('/control-panel/subscriptions'),
    request()->routeIs('super-admin.subscriptions.*'),
    false,
],

[
    'Manage plans',
    'card',
    Route::has('super-admin.subscription-plans.index')
        ? route('super-admin.subscription-plans.index')
        : url('/control-panel/subscription-plans'),
    request()->routeIs('super-admin.subscription-plans.*'),
    false,
],


    ['Payments',          'rupee',    null, false, true],
    ['School accounts',   'users',    null, false, true],
    ['System activity',   'activity', null, false, true],
];


@endphp

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Super Admin') | School Central</title>



    <link rel="preconnect" href="https\://fonts.googleapis.com">

    <link rel="preconnect" href="https\://fonts.gstatic.com" crossorigin>

    <link href="https\://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">



    <style>

        :root {

            --navy: #172b27;

            --navy-2: #203b34;

            --green: #2f7658;

            --green-soft: #e9f5ee;

            --orange: #e76b45;

            --ink: #24332f;

            --muted: #75827d;

            --line: #e6ebe7;

            --surface: #ffffff;

            --canvas: #f5f7f5;

            --blue: #4778c7;

            --blue-soft: #edf3ff;

            --amber: #b87918;

            --amber-soft: #fff5df;

            --red: #bd5149;

            --red-soft: #fff0ed;

            --shadow: 0 8px 24px rgba(23, 43, 39, .045);

        }



        * { box-sizing: border-box; }



        body {

            margin: 0;

            background: var(--canvas);

            color: var(--ink);

            font-family: 'DM Sans', sans-serif;

            font-size: 15px;

        }



        a { color: inherit; text-decoration: none; }

        button, input { font: inherit; }



        svg.i {

            width: 19px;

            height: 19px;

            flex-shrink: 0;

            fill: none;

            stroke: currentColor;

            stroke-width: 1.8;

            stroke-linecap: round;

            stroke-linejoin: round;

        }



        :focus-visible { outline: 3px solid rgba(47, 118, 88, .45); outline-offset: 2px; }



        /* =============================== Layout =============================== */

        .layout { min-height: 100vh; display: flex; }



        /* ------------------------------ Sidebar ------------------------------ */

        .sidebar {

            position: sticky;

            top: 0;

            align-self: flex-start;

            display: flex;

            flex-direction: column;

            flex: 0 0 258px;

            width: 258px;

            height: 100vh;

            padding: 24px 16px 18px;

            overflow-y: auto;

            overscroll-behavior: contain;

            scrollbar-width: thin;

            scrollbar-color: #526b60 #172b27;

            background: var(--navy);

            background-image: radial-gradient(ellipse at 0 0, rgba(255, 255, 255, .06), transparent 55%);

            color: #dce8e1;

        }



        .brand {

            display: flex;

            align-items: center;

            gap: 11px;

            padding: 0 9px 24px;

            border-bottom: 1px solid rgba(255, 255, 255, .1);

        }



        .brand-mark {

            display: grid;

            place-items: center;

            flex: 0 0 42px;

            width: 42px;

            height: 42px;

            border-radius: 13px;

            background: linear-gradient(145deg, #e87852, #c94c2b);

            box-shadow: 0 6px 16px rgba(231, 107, 69, .22);

            color: #fff;

            font-family: Manrope, sans-serif;

            font-size: 20px;

            font-weight: 800;

        }



        .brand-name { margin: 0; color: #fff; font-family: Manrope, sans-serif; font-size: 15px; font-weight: 800; letter-spacing: -.3px; }

        .brand-caption { margin: 4px 0 0; color: #9db1a6; font-size: 12px; }



        .nav-label {

            margin: 26px 10px 10px;

            color: #83998e;

            font-size: 11px;

            font-weight: 700;

            letter-spacing: 1.3px;

            text-transform: uppercase;

        }



        .nav { display: grid; gap: 4px; }



        .nav-item {

            position: relative;

            display: flex;

            align-items: center;

            gap: 12px;

            min-height: 43px;

            padding: 0 12px;

            border-radius: 10px;

            color: #c5d4cb;

            font-size: 14px;

            font-weight: 500;

            transition: background .18s ease, color .18s ease;

        }



        a.nav-item:hover { background: rgba(255, 255, 255, .07); color: #fff; }



        .nav-item.active { background: rgba(255, 255, 255, .12); color: #fff; font-weight: 600; box-shadow: inset 3px 0 var(--orange); }



        .nav-item.planned { color: #8da197; cursor: default; }



        .nav-soon {

            margin-left: auto;

            padding: 3px 7px;

            border: 1px solid rgba(255, 255, 255, .14);

            border-radius: 5px;

            color: #9db1a6;

            font-size: 9.5px;

            font-weight: 700;

            letter-spacing: .5px;

            text-transform: uppercase;

        }



        .sidebar-bottom { margin-top: auto; padding-top: 18px; }



        .security-card {

            margin: 22px 4px 16px;

            padding: 14px;

            border: 1px solid rgba(255, 255, 255, .12);

            border-radius: 12px;

            background: rgba(255, 255, 255, .045);

        }



        .security-title { display: flex; align-items: center; gap: 8px; color: #fff; font-size: 13px; font-weight: 700; }

        .security-copy { margin-top: 7px; color: #9db1a6; font-size: 12px; line-height: 1.6; }



        .profile {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 15px 8px 3px;

            border-top: 1px solid rgba(255, 255, 255, .1);

        }



        .avatar {

            display: grid;

            place-items: center;

            flex: 0 0 36px;

            width: 36px;

            height: 36px;

            border-radius: 50%;

            background: #e9f5ee;

            color: var(--navy);

            font-weight: 800;

        }



        .profile-name { max-width: 150px; overflow: hidden; color: #fff; font-size: 13px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }

        .profile-role { margin-top: 3px; color: #9db1a6; font-size: 11px; }



        .logout-form { margin-top: 12px; }



        .logout-btn {

            display: flex;

            align-items: center;

            gap: 10px;

            width: 100%;

            padding: 10px 12px;

            border: 0;

            border-radius: 9px;

            background: transparent;

            color: #f1b5a4;

            font-size: 13.5px;

            text-align: left;

            cursor: pointer;

            transition: background .18s ease, color .18s ease;

        }



        .logout-btn:hover { background: rgba(231, 107, 69, .16); color: #fff; }



        /* ------------------------------ Main column ------------------------------ */

        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }



        .topbar {

            position: sticky;

            top: 0;

            z-index: 10;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 18px;

            min-height: 76px;

            padding: 15px 34px;

            border-bottom: 1px solid var(--line);

            background: rgba(255, 255, 255, .92);

            backdrop-filter: blur(8px);

        }



        .crumb { margin-bottom: 5px; color: var(--muted); font-size: 12px; }

        .topbar h1 { margin: 0; font-family: Manrope, sans-serif; font-size: 22px; font-weight: 800; letter-spacing: -.5px; }



        .topbar-right { display: flex; align-items: center; gap: 12px; }



        .date-chip {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 9px 12px;

            border: 1px solid var(--line);

            border-radius: 9px;

            background: #fff;

            color: #5e6d66;

            font-size: 12px;

        }



        .date-chip svg.i { width: 15px; height: 15px; }



        .role-chip {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 9px 12px;

            border-radius: 9px;

            background: var(--green-soft);

            color: var(--green);

            font-size: 11px;

            font-weight: 800;

            letter-spacing: .7px;

        }



        .role-chip::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: #39a56b; }



        .content { width: 100%; max-width: 1500px; margin: 0 auto; padding: 30px 34px 20px; }



        /* ------------------------------ Welcome ------------------------------ */

        .welcome {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 24px;

            margin-bottom: 24px;

        }



        .eyebrow { margin-bottom: 8px; color: var(--green); font-size: 11px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase; }



        .welcome h2 { margin: 0; font-family: Manrope, sans-serif; font-size: clamp(26px, 2.5vw, 34px); line-height: 1.25; letter-spacing: -1px; }



        .welcome p { max-width: 610px; margin: 9px 0 0; color: var(--muted); font-size: 14px; line-height: 1.7; }



        .welcome-action {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            flex-shrink: 0;

            padding: 11px 16px;

            border-radius: 10px;

            background: var(--navy);

            box-shadow: 0 5px 12px rgba(23, 43, 39, .14);

            color: #fff;

            font-size: 13px;

            font-weight: 700;

            transition: background .18s ease, transform .18s ease;

        }



        .welcome-action:hover { background: var(--navy-2); transform: translateY(-1px); }

        .welcome-action svg.i { width: 16px; height: 16px; }



        /* ------------------------------ Snapshot banner ------------------------------ */

        .status-banner {

            display: grid;

            grid-template-columns: auto minmax(0, 1fr) minmax(240px, .8fr);

            gap: 28px;

            align-items: center;

            margin-bottom: 8px;

            padding: 24px 28px;

            border: 1px solid #dbe8df;

            border-radius: 16px;

            background: linear-gradient(115deg, #fff 0%, #f3faf5 100%);

            box-shadow: var(--shadow);

        }



        .ring {

            --p: 0;

            position: relative;

            display: grid;

            place-items: center;

            width: 104px;

            height: 104px;

            border-radius: 50%;

            background: conic-gradient(var(--green) calc(var(--p) * 1%), #dfe9e2 0);

        }



        .ring::before { content: ''; position: absolute; inset: 11px; border-radius: 50%; background: #f9fcfa; }



        .ring-text { position: relative; text-align: center; line-height: 1.1; }

        .ring-text strong { display: block; font-family: Manrope, sans-serif; font-size: 24px; font-weight: 800; letter-spacing: -.8px; }

        .ring-text span { color: var(--muted); font-size: 10.5px; }



        .banner-kicker { color: var(--green); font-size: 11px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; }

        .banner-title { margin-top: 8px; font-family: Manrope, sans-serif; font-size: 20px; font-weight: 800; letter-spacing: -.5px; }

        .banner-copy { margin-top: 6px; color: var(--muted); font-size: 13px; line-height: 1.6; }



        .banner-stats { display: grid; gap: 12px; }



        .bar-row { display: grid; gap: 7px; }

        .bar-meta { display: flex; justify-content: space-between; gap: 12px; color: #52665b; font-size: 12px; font-weight: 700; }

        .bar-meta strong { color: var(--ink); }

        .bar-track { height: 9px; overflow: hidden; border-radius: 20px; background: #dfe9e2; }

        .bar-fill { height: 100%; border-radius: inherit; }

        .bar-fill.green { background: linear-gradient(90deg, #2f7658, #65ad82); }

        .bar-fill.amber { background: linear-gradient(90deg, #d9962b, #ecc268); }



        /* ------------------------------ Section headings ------------------------------ */

        .section-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 15px; margin: 30px 0 15px; }

        .section-heading h3 { margin: 0; font-family: Manrope, sans-serif; font-size: 15px; font-weight: 800; letter-spacing: -.3px; }

        .section-heading p { margin: 4px 0 0; color: var(--muted); font-size: 12px; }

        .section-note { color: var(--muted); font-size: 12px; }



        /* ------------------------------ KPI cards ------------------------------ */

        .kpis { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; }



        .kpi {

            --kpi-accent: var(--blue);

            --kpi-glow: rgba(71, 120, 199, .08);

            position: relative;

            min-width: 0;

            min-height: 178px;

            overflow: hidden;

            padding: 22px 22px 20px 26px;

            border: 1px solid var(--line);

            border-radius: 16px;

            background: var(--surface);

            box-shadow: 0 8px 22px rgba(23, 43, 39, .055);

            transition: transform .2s ease, box-shadow .2s ease;

        }



        .kpi:hover { transform: translateY(-4px); box-shadow: 0 16px 30px rgba(23, 43, 39, .11); }



        .kpi::before { content: ''; position: absolute; inset: 0 auto 0 0; width: 5px; background: var(--kpi-accent); }



        .kpi::after {

            content: '';

            position: absolute;

            right: -42px;

            bottom: -52px;

            width: 120px;

            height: 120px;

            border-radius: 50%;

            background: var(--kpi-glow);

            pointer-events: none;

        }



        .kpi.blue  { --kpi-accent: var(--blue);  --kpi-glow: rgba(71, 120, 199, .09); }

        .kpi.green { --kpi-accent: var(--green); --kpi-glow: rgba(47, 118, 88, .09); }

        .kpi.amber { --kpi-accent: var(--amber); --kpi-glow: rgba(184, 121, 24, .10); }

        .kpi.red   { --kpi-accent: var(--red);   --kpi-glow: rgba(189, 81, 73, .09); }



        .kpi-top { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 10px; }



        .kpi-label { color: var(--muted); font-size: 12px; font-weight: 700; }



        .kpi-icon { display: grid; place-items: center; width: 44px; height: 44px; border-radius: 12px; }



        .kpi.blue  .kpi-icon { color: var(--blue);  background: var(--blue-soft); }

        .kpi.green .kpi-icon { color: var(--green); background: var(--green-soft); }

        .kpi.amber .kpi-icon { color: var(--amber); background: var(--amber-soft); }

        .kpi.red   .kpi-icon { color: var(--red);   background: var(--red-soft); }



        .kpi-value {

            position: relative;

            z-index: 1;

            margin-top: 16px;

            color: #1c332c;

            font-family: Manrope, sans-serif;

            font-size: 38px;

            font-weight: 800;

            line-height: 1;

            letter-spacing: -1.3px;

            font-variant-numeric: tabular-nums;

        }



        .kpi-foot { position: relative; z-index: 1; display: flex; align-items: center; gap: 7px; margin-top: 14px; color: #687a71; font-size: 11.5px; }

        .kpi-foot .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }



        /* ------------------------------ Directory panel ------------------------------ */

        .panel {

            overflow: hidden;

            border: 1px solid var(--line);

            border-radius: 14px;

            background: var(--surface);

            box-shadow: var(--shadow);

        }



        .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 15px; padding: 19px 21px; border-bottom: 1px solid var(--line); }

        .panel-head h3 { margin: 0; font-family: Manrope, sans-serif; font-size: 15px; font-weight: 800; }

        .panel-head p { margin: 5px 0 0; color: var(--muted); font-size: 12px; }



        .count-chip {

            padding: 7px 10px;

            border: 1px solid var(--line);

            border-radius: 8px;

            background: #fafcfb;

            color: #5e6d66;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;

        }



        .toolbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            flex-wrap: wrap;

            gap: 12px;

            padding: 14px 21px;

            border-bottom: 1px solid var(--line);

            background: #fbfcfb;

        }



        .search {

            display: flex;

            align-items: center;

            gap: 9px;

            width: 320px;

            max-width: 100%;

            height: 40px;

            padding: 0 12px;

            border: 1px solid var(--line);

            border-radius: 9px;

            background: #fff;

            color: #8a9791;

        }



        .search:focus-within { border-color: var(--green); box-shadow: 0 0 0 3px rgba(47, 118, 88, .12); }

        .search svg.i { width: 17px; height: 17px; }



        .search input { flex: 1; min-width: 0; height: 100%; border: 0; outline: 0; background: transparent; color: var(--ink); font-size: 13px; }



        .chips { display: flex; flex-wrap: wrap; gap: 7px; }



        .chip {

            padding: 7px 13px;

            border: 1px solid var(--line);

            border-radius: 20px;

            background: #fff;

            color: #5e6d66;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

            transition: background .15s ease, color .15s ease, border-color .15s ease;

        }



        .chip:hover { border-color: #b9cbc1; }

        .chip.is-on { border-color: var(--navy); background: var(--navy); color: #fff; }



        .table-wrap { overflow-x: auto; }



        table { width: 100%; border-collapse: collapse; }



        th {

            padding: 12px 21px;

            background: #f8faf8;

            color: #87938d;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: .9px;

            text-align: left;

            text-transform: uppercase;

            white-space: nowrap;

        }



        td { padding: 14px 21px; border-top: 1px solid #edf0ed; font-size: 13px; vertical-align: middle; white-space: nowrap; }



        tbody tr:hover td { background: #fbfdfb; }



        .school-cell { display: flex; align-items: center; gap: 11px; }



        .school-avatar {

            display: grid;

            place-items: center;

            flex: 0 0 38px;

            width: 38px;

            height: 38px;

            border: 1px solid #dce9df;

            border-radius: 11px;

            background: #eff7f1;

            color: var(--green);

            font-weight: 800;

        }



        .school-name { color: var(--ink); font-weight: 700; }

        .school-sub { margin-top: 3px; color: #9aa59f; font-size: 11px; }



        .code-chip { display: inline-block; padding: 5px 9px; border-radius: 6px; background: #f0f4f8; color: #526d8b; font-size: 11px; font-weight: 800; }



        .badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border: 1px solid transparent; border-radius: 20px; font-size: 11px; font-weight: 800; }

        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        .badge.active   { border-color: #d3e9d9; background: #edf8f0; color: #2d8050; }

        .badge.inactive { border-color: #f2d8d3; background: #fff2ef; color: #b54f43; }

        .badge.other    { border-color: #eee3c7; background: #fff8e8; color: #9a7428; }



        .muted { color: var(--muted); }



        .empty { padding: 44px 20px; text-align: center; white-space: normal; }



        .empty-icon {

            display: grid;

            place-items: center;

            width: 52px;

            height: 52px;

            margin: 0 auto 12px;

            border-radius: 14px;

            background: #eff7f1;

            color: var(--green);

        }



        .empty-icon svg.i { width: 24px; height: 24px; }

        .empty h4 { margin: 0 0 6px; font-family: Manrope, sans-serif; font-size: 15px; }

        .empty p { margin: 0; color: var(--muted); font-size: 13px; }



        .panel-foot {

            display: flex;

            justify-content: space-between;

            gap: 12px;

            padding: 13px 21px;

            border-top: 1px solid var(--line);

            background: #fbfcfb;

            color: var(--muted);

            font-size: 11.5px;

        }



        .panel-foot strong { color: var(--ink); }



        .footer { padding: 22px 20px 26px; color: #99a49e; font-size: 11px; text-align: center; }



        /* =============================== Responsive =============================== */

        @media (max-width: 1180px) {

            .sidebar { flex-basis: 230px; width: 230px; }

            .content { padding: 25px 24px; }

            .topbar { padding: 15px 24px; }

            .kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }

            .status-banner { grid-template-columns: auto minmax(0, 1fr); }

            .banner-stats { grid-column: 1 / -1; }

        }



        @media (max-width: 850px) {

            .layout { display: block; }



            .sidebar { position: static; width: 100%; height: auto; padding: 15px 16px; }

            .brand { padding-bottom: 14px; }

            .nav-label, .security-card, .profile { display: none; }



            .nav { display: flex; gap: 5px; padding-top: 12px; overflow-x: auto; }

            .nav-item { flex: 0 0 auto; min-height: 38px; padding: 0 12px; white-space: nowrap; }

            .nav-item.active { box-shadow: inset 0 -3px var(--orange); }

            .nav-soon { display: none; }



            .sidebar-bottom { display: flex; justify-content: flex-end; margin-top: 8px; padding-top: 0; }

            .logout-form { margin: 0; }

            .logout-btn { width: auto; padding: 8px 10px; }



            .topbar { position: static; min-height: 68px; }

            .status-banner { grid-template-columns: 1fr; gap: 20px; justify-items: start; }

            .banner-stats { width: 100%; }

        }



        @media (max-width: 600px) {

            .topbar { align-items: flex-start; padding: 14px 16px; }

            .topbar h1 { font-size: 17px; }

            .date-chip { display: none; }

            .content { padding: 22px 14px; }



            .welcome { display: block; }

            .welcome-action { margin-top: 16px; }



            .status-banner { padding: 20px; }

            .banner-title { font-size: 18px; }



            .section-heading { align-items: flex-start; flex-direction: column; gap: 4px; }



            .kpis { grid-template-columns: 1fr; gap: 12px; }

            .kpi { min-height: 0; padding: 18px 18px 16px 22px; }

            .kpi-value { margin-top: 12px; font-size: 30px; }



            .panel-head { align-items: flex-start; padding: 16px; }

            .toolbar { padding: 12px 16px; }

            .search { width: 100%; }



            /* table -> cards */

            .table-wrap { padding: 10px; background: #f8faf8; }

            table, tbody, tr, td { display: block; width: 100%; }

            thead { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); }



            tbody tr { margin-bottom: 10px; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; background: #fff; }

            tbody tr:last-child { margin-bottom: 0; }

            tbody tr:hover td { background: transparent; }

            tr[hidden] { display: none; }



            td { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 12px; border-top: 1px solid #edf0ed; text-align: right; white-space: normal; }



            td::before {

                content: attr(data-label);

                color: #87938d;

                font-size: 10px;

                font-weight: 800;

                letter-spacing: .6px;

                text-align: left;

                text-transform: uppercase;

            }



            td.cell-school { display: block; padding: 13px; border-top: 0; background: #fbfcfb; text-align: left; }

            td.cell-school::before { display: none; }



            tr.empty-row td { display: block; padding: 0; border: 0; }

            tr.empty-row td::before { display: none; }



            .panel-foot { flex-direction: column; gap: 4px; padding: 12px 15px; }

        }



        @media (prefers-reduced-motion: reduce) {

            *, *::before, *::after { scroll-behavior: auto !important; transition: none !important; animation: none !important; }

        }

    </style>

</head>

<body>
<div class="layout">
    {{-- Shared Super Admin sidebar --}}
    <aside class="sidebar">

        <div class="brand">

            <div class="brand-mark" aria-hidden="true">S</div>

            <div>

                <h2 class="brand-name">School Central</h2>

                <p class="brand-caption">Super Admin Console</p>

            </div>

        </div>



        <div class="nav-label">Workspace</div>

        <nav class="nav" aria-label="Main navigation">

            @foreach ($workspaceNav as [$label, $icon, $href, $isActive, $isPlanned])

                @if ($isPlanned)

                    <span class="nav-item planned" title="Coming soon" aria-disabled="true">

                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$icon] !!}</svg>

                        {{ $label }}

                        <span class="nav-soon">Planned</span>

                    </span>

                @else

                    <a href="{{ $href }}" class="nav-item {{ $isActive ? 'active' : '' }}" @if($isActive) aria-current="page" @endif>

                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$icon] !!}</svg>

                        {{ $label }}

                    </a>

                @endif

            @endforeach

        </nav>



        @if ($securityUrl)

            <div class="nav-label">Administration</div>

            <nav class="nav" aria-label="Administration navigation">

                <a href="{{ $securityUrl }}"

                   class="nav-item {{ request()->routeIs('super-admin.security') ? 'active' : '' }}"

                   @if(request()->routeIs('super-admin.security')) aria-current="page" @endif>

                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['shield'] !!}</svg>

                    Security settings

                </a>

            </nav>

        @endif



        <div class="sidebar-bottom">

            <div class="security-card">

                <div class="security-title">

                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['shield'] !!}</svg>

                    System workspace

                </div>

                <div class="security-copy">Use this console to oversee registered schools and platform-wide access.</div>

            </div>



            <div class="profile">

                <div class="avatar" aria-hidden="true">{{ $meInitial }}</div>

                <div>

                    <div class="profile-name">{{ $meName }}</div>

                    <div class="profile-role">Super administrator</div>

                </div>

            </div>



            @if ($logoutUrl)

                <form method="POST" action="{{ $logoutUrl }}" class="logout-form">

                    @csrf

                    <button type="submit" class="logout-btn">

                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['logout'] !!}</svg>

                        Sign out securely

                    </button>

                </form>

            @endif

        </div>

    </aside>
    <div class="main">
        <header class="topbar">

            <div>

                <div class="crumb">School Central / @yield('breadcrumb', 'Overview')</div>

                <h1>@yield('page-title', 'Dashboard overview')</h1>

            </div>



            <div class="topbar-right">

                <div class="date-chip">

                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['calendar'] !!}</svg>

                    {{ now()->format('D, d M Y') }}

                </div>

                <div class="role-chip">SUPER ADMIN</div>

            </div>

        </header>
        <main class="content">
            @yield('content')
        </main>
        <footer class="footer">&copy; {{ date('Y') }} School Central &middot; Super Admin Console</footer>
    </div>
</div>
@stack('scripts')
</body>
</html>
