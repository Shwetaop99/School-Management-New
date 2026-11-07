@php

    use Illuminate\Support\Facades\Route;



    $me        = auth('super_admin')->user();

    $meName    = $me->name ?? 'Super Admin';

    $meInitial = strtoupper(substr($meName, 0, 1));



    $dashboardUrl = Route::has('super-admin.dashboard')

        ? route('super-admin.dashboard')

        : url()->current();



    $schoolsUrl = Route::has('super-admin.schools.index')

        ? route('super-admin.schools.index')

        : url('/control-panel/schools');



    $subscriptionsUrl = Route::has('super-admin.subscriptions.index')

        ? route('super-admin.subscriptions.index')

        : url('/control-panel/subscriptions');



    $plansUrl = Route::has('super-admin.subscription-plans.index')

        ? route('super-admin.subscription-plans.index')

        : url('/control-panel/subscription-plans');



    $supportUrl = Route::has('super-admin.support-requests.index')

        ? route('super-admin.support-requests.index')

        : url('/control-panel/support-requests');



    $reportsUrl = Route::has('super-admin.reports.schools')

        ? route('super-admin.reports.schools')

        : url('/control-panel/reports/schools');



    $securityUrl = Route::has('super-admin.security')

        ? route('super-admin.security')

        : null;



    $usersUrl = Route::has('super-admin.users.index')

        ? route('super-admin.users.index')

        : url('/control-panel/users');



    $rolesUrl = Route::has('super-admin.roles.index')

        ? route('super-admin.roles.index')

        : url('/control-panel/roles');

        $auditLogsUrl = Route::has('super-admin.audit-logs.index')
    ? route('super-admin.audit-logs.index')
    : url('/control-panel/audit-logs');



    $logoutUrl = Route::has('super-admin.logout')

        ? route('super-admin.logout')

        : null;



    // Unread school-admin replies for sidebar badge

    $unreadSupportCount = 0;

    try {

        if (class_exists(\App\Models\SupportTicketReply::class)) {

            $unreadSupportCount = \App\Models\SupportTicketReply::query()

                ->where('sender_type', 'school_admin')

                ->whereNull('read_at')

                ->count();

        }

    } catch (\Throwable $e) {

        $unreadSupportCount = 0;

    }



    // Simple Font Awesome icon classes

    $icons = [

        'grid'     => 'fa-th-large',

        'school'   => 'fa-school',

        'card'     => 'fa-credit-card',

        'layers'   => 'fa-layer-group',

        'rupee'    => 'fa-indian-rupee-sign',

        'users'    => 'fa-users',

        'activity' => 'fa-chart-line',

        'support'  => 'fa-headset',

        'report'   => 'fa-chart-column',

        'shield'   => 'fa-shield-halved',

        'logout'   => 'fa-right-from-bracket',

        'calendar' => 'fa-calendar-days',

        'search'   => 'fa-magnifying-glass',

        'arrow'    => 'fa-arrow-right',

        'settings' => 'fa-gear',

        'user-cog' => 'fa-user-gear',

        'roles'    => 'fa-user-shield',

    ];



    // label, icon key, href, isActive, isPlanned, badgeCount

    $workspaceNav = [

        ['Dashboard',         'grid',     $dashboardUrl,     request()->routeIs('super-admin.dashboard'),            false, 0],

        ['School management', 'school',   $schoolsUrl,       request()->routeIs('super-admin.schools.*'),             false, 0],

        ['Subscriptions',     'card',     $subscriptionsUrl, request()->routeIs('super-admin.subscriptions.*'),       false, 0],

        ['Manage plans',      'layers',   $plansUrl,         request()->routeIs('super-admin.subscription-plans.*'),  false, 0],

        ['Support Requests',  'support',  $supportUrl,       request()->routeIs('super-admin.support-requests.*'),    false, $unreadSupportCount],

        ['School Reports',    'report',   $reportsUrl,       request()->routeIs('super-admin.reports.*'),             false, 0],



    ];

@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Super Admin') | School Central</title>



    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght\@400;500;600;700&family=Manrope:wght\@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">



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



        * {
    box-sizing: border-box;
}

html,
body {
    width: 100%;
    min-height: 100%;
    margin: 0 !important;
    padding: 0 !important;
}

body {
    background: var(--canvas);
    color: var(--ink);
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    overflow-x: hidden;
}



        a { color: inherit; text-decoration: none; }

        button, input, select, textarea { font: inherit; }



        :focus-visible {

            outline: 3px solid rgba(47, 118, 88, .45);

            outline-offset: 2px;

        }



        .layout {
    display: flex;
    width: 100%;
    min-height: 100vh;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    top: 0;
}



        /* Sidebar */

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



        .brand-name {

            margin: 0;

            color: #fff;

            font-family: Manrope, sans-serif;

            font-size: 15px;

            font-weight: 800;

            letter-spacing: -.3px;

        }



        .brand-caption {

            margin: 4px 0 0;

            color: #9db1a6;

            font-size: 12px;

        }



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



        .nav-item i {

            width: 20px;

            text-align: center;

            font-size: 15px;

            flex-shrink: 0;

        }



        a.nav-item:hover {

            background: rgba(255, 255, 255, .07);

            color: #fff;

        }



        .nav-item.active {

            background: rgba(255, 255, 255, .12);

            color: #fff;

            font-weight: 600;

            box-shadow: inset 3px 0 var(--orange);

        }



        .nav-item.planned {

            color: #8da197;

            cursor: default;

        }



        .nav-count {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 21px;

            height: 21px;

            margin-left: auto;

            padding: 0 6px;

            border-radius: 999px;

            background: #dc3545;

            color: #fff;

            font-size: 11px;

            font-weight: 800;

            line-height: 1;

            font-variant-numeric: tabular-nums;

        }



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



        /* Settings */

        .settings-menu {

            display: block;

            margin-top: 4px;

        }



        .settings-toggle {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            min-height: 43px;

            padding: 0 12px;

            border: 0;

            border-radius: 10px;

            background: transparent;

            color: #c5d4cb;

            font-size: 14px;

            font-weight: 500;

            text-align: left;

            cursor: pointer;

            list-style: none;

        }



        .settings-toggle::-webkit-details-marker { display: none; }



        .settings-toggle i:first-child {

            width: 20px;

            text-align: center;

            font-size: 15px;

        }



        .settings-toggle:hover {

            background: rgba(255, 255, 255, .07);

            color: #fff;

        }



        .settings-menu[open] > .settings-toggle,

        .settings-toggle.is-active {

            background: rgba(255, 255, 255, .12);

            color: #fff;

            font-weight: 600;

            box-shadow: inset 3px 0 var(--orange);

        }



        .settings-chevron {

            margin-left: auto;

            font-size: 12px;

            transition: transform .2s ease;

        }



        .settings-menu[open] .settings-chevron {

            transform: rotate(180deg);

        }



        .settings-submenu {

            display: grid;

            gap: 4px;

            margin: 4px 0 6px 14px;

            padding: 4px 0 4px 12px;

            border-left: 1px solid rgba(255, 255, 255, .16);

        }



        .settings-submenu .nav-item {

            min-height: 39px;

            font-size: 13px;

        }



        .sidebar-bottom { margin-top: auto; padding-top: 18px; }



        .security-card {

            margin: 22px 4px 16px;

            padding: 14px;

            border: 1px solid rgba(255, 255, 255, .12);

            border-radius: 12px;

            background: rgba(255, 255, 255, .045);

        }



        .security-title {

            display: flex;

            align-items: center;

            gap: 8px;

            color: #fff;

            font-size: 13px;

            font-weight: 700;

        }



        .security-copy {

            margin-top: 7px;

            color: #9db1a6;

            font-size: 12px;

            line-height: 1.6;

        }



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



        .profile-name {

            max-width: 150px;

            overflow: hidden;

            color: #fff;

            font-size: 13px;

            font-weight: 700;

            text-overflow: ellipsis;

            white-space: nowrap;

        }



        .profile-role {

            margin-top: 3px;

            color: #9db1a6;

            font-size: 11px;

        }



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



        .logout-btn:hover {

            background: rgba(231, 107, 69, .16);

            color: #fff;

        }



        /* Main */

        .main {

            flex: 1;

            min-width: 0;

            display: flex;

            flex-direction: column;

        }



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



        .crumb {

            margin-bottom: 5px;

            color: var(--muted);

            font-size: 12px;

        }



        .topbar h1 {

            margin: 0;

            font-family: Manrope, sans-serif;

            font-size: 22px;

            font-weight: 800;

            letter-spacing: -.5px;

        }



        .topbar-right {

            display: flex;

            align-items: center;

            gap: 12px;

        }



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



        .role-chip::before {

            content: '';

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #39a56b;

        }



        .content {

            width: 100%;

            max-width: 1500px;

            margin: 0 auto;

            padding: 30px 34px 20px;

        }



        /* Shared page pieces */

        .welcome {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 24px;

            margin-bottom: 24px;

        }



        .eyebrow {

            margin-bottom: 8px;

            color: var(--green);

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 1.4px;

            text-transform: uppercase;

        }



        .welcome h2 {

            margin: 0;

            font-family: Manrope, sans-serif;

            font-size: clamp(26px, 2.5vw, 34px);

            line-height: 1.25;

            letter-spacing: -1px;

        }



        .welcome p {

            max-width: 610px;

            margin: 9px 0 0;

            color: var(--muted);

            font-size: 14px;

            line-height: 1.7;

        }



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

        }



        .welcome-action:hover { background: var(--navy-2); }



        .section-heading {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            gap: 15px;

            margin: 30px 0 15px;

        }



        .section-heading h3 {

            margin: 0;

            font-family: Manrope, sans-serif;

            font-size: 15px;

            font-weight: 800;

        }



        .section-heading p {

            margin: 4px 0 0;

            color: var(--muted);

            font-size: 12px;

        }



        .kpis {

            display: grid;

            grid-template-columns: repeat(4, minmax(0, 1fr));

            gap: 18px;

        }



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

        }



        .kpi::before {

            content: '';

            position: absolute;

            inset: 0 auto 0 0;

            width: 5px;

            background: var(--kpi-accent);

        }



        .kpi.blue  { --kpi-accent: var(--blue); }

        .kpi.green { --kpi-accent: var(--green); }

        .kpi.amber { --kpi-accent: var(--amber); }

        .kpi.red   { --kpi-accent: var(--red); }



        .kpi-top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;

        }



        .kpi-label {

            color: var(--muted);

            font-size: 12px;

            font-weight: 700;

        }



        .kpi-icon {

            display: grid;

            place-items: center;

            width: 44px;

            height: 44px;

            border-radius: 12px;

        }



        .kpi.blue  .kpi-icon { color: var(--blue);  background: var(--blue-soft); }

        .kpi.green .kpi-icon { color: var(--green); background: var(--green-soft); }

        .kpi.amber .kpi-icon { color: var(--amber); background: var(--amber-soft); }

        .kpi.red   .kpi-icon { color: var(--red);   background: var(--red-soft); }



        .kpi-value {

            margin-top: 16px;

            font-family: Manrope, sans-serif;

            font-size: 38px;

            font-weight: 800;

            line-height: 1;

            letter-spacing: -1.3px;

        }



        .kpi-foot {

            display: flex;

            align-items: center;

            gap: 7px;

            margin-top: 14px;

            color: #687a71;

            font-size: 11.5px;

        }



        .kpi-foot .dot {

            width: 6px;

            height: 6px;

            border-radius: 50%;

            background: currentColor;

        }



        .panel {

            overflow: hidden;

            border: 1px solid var(--line);

            border-radius: 14px;

            background: var(--surface);

            box-shadow: var(--shadow);

        }



        .panel-head {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 19px 21px;

            border-bottom: 1px solid var(--line);

        }



        .panel-head h3 {

            margin: 0;

            font-family: Manrope, sans-serif;

            font-size: 15px;

            font-weight: 800;

        }



        .panel-head p {

            margin: 5px 0 0;

            color: var(--muted);

            font-size: 12px;

        }



        .count-chip {

            padding: 7px 10px;

            border: 1px solid var(--line);

            border-radius: 8px;

            background: #fafcfb;

            color: #5e6d66;

            font-size: 11px;

            font-weight: 700;

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



        .search input {

            flex: 1;

            min-width: 0;

            height: 100%;

            border: 0;

            outline: 0;

            background: transparent;

            color: var(--ink);

            font-size: 13px;

        }



        .chip {

            padding: 7px 13px;

            border: 1px solid var(--line);

            border-radius: 20px;

            background: #fff;

            color: #5e6d66;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

        }



        .chip.is-on {

            border-color: var(--navy);

            background: var(--navy);

            color: #fff;

        }



        .table-wrap { overflow-x: auto; }



        table {

            width: 100%;

            border-collapse: collapse;

        }



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



        td {

            padding: 14px 21px;

            border-top: 1px solid #edf0ed;

            font-size: 13px;

            vertical-align: middle;

            white-space: nowrap;

        }



        .code-chip {

            display: inline-block;

            padding: 5px 9px;

            border-radius: 6px;

            background: #f0f4f8;

            color: #526d8b;

            font-size: 11px;

            font-weight: 800;

        }



        .badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 800;

        }



        .badge::before {

            content: '';

            width: 6px;

            height: 6px;

            border-radius: 50%;

            background: currentColor;

        }



        .badge.active {

            background: #edf8f0;

            color: #2d8050;

        }



        .badge.inactive {

            background: #fff2ef;

            color: #b54f43;

        }



        .badge.other {

            background: #fff8e8;

            color: #9a7428;

        }



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



        .footer {

            padding: 22px 20px 26px;

            color: #99a49e;

            font-size: 11px;

            text-align: center;

        }




        /* Dashboard overview components */
        .i {
            display: inline-block;
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex: 0 0 auto;
        }

        .welcome-action {
            min-height: 48px;
            white-space: nowrap;
        }

        .welcome-action .i {
            width: 17px;
            height: 17px;
        }

        .status-banner {
            display: grid;
            grid-template-columns: 104px minmax(220px, 1fr) minmax(230px, .8fr);
            align-items: center;
            gap: 22px;
            padding: 22px 24px;
            margin: 22px 0 28px;
            border: 1px solid #e3ebe5;
            border-radius: 16px;
            background: linear-gradient(115deg, #fff 0%, #f2f8f3 100%);
            box-shadow: var(--shadow);
        }

        .ring {
            --p: 0;
            display: grid;
            place-items: center;
            width: 92px;
            height: 92px;
            border-radius: 50%;
            background: conic-gradient(var(--green) calc(var(--p) * 1%), #e5ece7 0);
            position: relative;
        }

        .ring::before {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: #fff;
        }

        .ring-text {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 1px;
            text-align: center;
        }

        .ring-text strong {
            font: 800 20px/1.1 Manrope, sans-serif;
            color: var(--ink);
        }

        .ring-text span {
            color: var(--muted);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .banner-kicker {
            margin-bottom: 5px;
            color: var(--green);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .banner-title {
            color: var(--ink);
            font: 800 17px/1.35 Manrope, sans-serif;
        }

        .banner-copy {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .banner-stats {
            display: grid;
            gap: 14px;
        }

        .bar-row {
            display: grid;
            gap: 6px;
        }

        .bar-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: #65746c;
            font-size: 11px;
        }

        .bar-meta strong {
            color: var(--ink);
            font-size: 12px;
        }

        .bar-track {
            height: 7px;
            overflow: hidden;
            border-radius: 99px;
            background: #e8eee9;
        }

        .bar-fill {
            height: 100%;
            border-radius: inherit;
            transition: width .25s ease;
        }

        .bar-fill.green { background: var(--green); }
        .bar-fill.amber { background: var(--amber); }

        .section-note {
            color: var(--muted);
            font-size: 11px;
            white-space: nowrap;
        }

        .school-cell {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 180px;
        }

        .school-avatar {
            display: grid;
            place-items: center;
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: var(--green-soft);
            color: var(--green);
            font: 800 14px Manrope, sans-serif;
        }

        .school-name {
            color: var(--ink);
            font-size: 13px;
            font-weight: 700;
        }

        .school-sub {
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
        }

        .muted {
            color: var(--muted);
        }

        .empty-row td {
            padding: 34px 20px;
            white-space: normal;
        }

        .empty {
            display: grid;
            justify-items: center;
            gap: 8px;
            max-width: 360px;
            margin: 0 auto;
            text-align: center;
        }

        .empty-icon {
            display: grid;
            place-items: center;
            width: 46px;
            height: 46px;
            margin-bottom: 3px;
            border-radius: 14px;
            background: #f0f5f1;
            color: var(--green);
        }

        .empty h4 {
            margin: 0;
            color: var(--ink);
            font: 800 14px Manrope, sans-serif;
        }

        .empty p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        [hidden] {
            display: none !important;
        }

        @media (max-width: 1180px) {

            .sidebar { flex-basis: 230px; width: 230px; }

            .content { padding: 25px 24px; }

            .topbar { padding: 15px 24px; }

            .kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        }



        @media (max-width: 1000px) {
            .status-banner {
                grid-template-columns: 82px minmax(0, 1fr);
            }
            .ring {
                width: 78px;
                height: 78px;
            }
            .banner-stats {
                grid-column: 1 / -1;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 850px) {

            .layout { display: block; }

            .sidebar {

                position: static;

                width: 100%;

                height: auto;

                padding: 15px 16px;

            }

            .nav-label, .security-card, .profile, .settings-menu { display: none; }

            .nav {

                display: flex;

                gap: 5px;

                padding-top: 12px;

                overflow-x: auto;

            }

            .nav-item {

                flex: 0 0 auto;

                min-height: 38px;

                white-space: nowrap;

            }

            .sidebar-bottom {

                display: flex;

                justify-content: flex-end;

                margin-top: 8px;

                padding-top: 0;

            }

            .logout-form { margin: 0; }

            .logout-btn { width: auto; }

        }



        @media (max-width: 600px) {
            .date-chip { display: none; }
            .content { padding: 22px 14px; }
            .welcome { display: block; }
            .welcome-action {
                margin-top: 16px;
                white-space: normal;
            }
            .kpis { grid-template-columns: 1fr; }
            .status-banner {
                grid-template-columns: 1fr;
                justify-items: start;
                padding: 18px;
            }
            .banner-stats {
                width: 100%;
                grid-template-columns: 1fr;
            }
            .section-heading { align-items: flex-start; }
            .panel-head, .panel-foot {
                align-items: flex-start;
                flex-direction: column;
            }
            .toolbar { align-items: stretch; }
            .search { width: 100%; }
        }

    </style>

</head>

<body>

<div class="layout">



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

            @foreach ($workspaceNav as $item)

                @php

                    [$label, $icon, $href, $isActive, $isPlanned] = $item;

                    $badgeCount = $item[5] ?? 0;

                @endphp



                @if ($isPlanned)

                    <span class="nav-item planned" title="Coming soon">

                        <i class="fas {{ $icons[$icon] }}"></i>

                        <span>{{ $label }}</span>

                        <span class="nav-soon">Planned</span>

                    </span>

                @else

                    <a href="{{ $href }}"

                       class="nav-item {{ $isActive ? 'active' : '' }}"

                       @if($isActive) aria-current="page" @endif>

                        <i class="fas {{ $icons[$icon] }}"></i>

                        <span>{{ $label }}</span>

                        @if ($badgeCount > 0)

                            <span class="nav-count">{{ $badgeCount > 99 ? '99+' : $badgeCount }}</span>

                        @endif

                    </a>

                @endif

            @endforeach

        </nav>



        <div class="nav-label">Administration</div>

        <nav class="nav" aria-label="Administration navigation">

            @if ($securityUrl)

                <a href="{{ $securityUrl }}"

                   class="nav-item {{ request()->routeIs('super-admin.security') ? 'active' : '' }}">

                    <i class="fas {{ $icons['shield'] }}"></i>

                    Security settings

                </a>

            @endif



            <details class="settings-menu"
    {{ request()->routeIs('super-admin.users.*', 'super-admin.roles.*', 'super-admin.audit-logs.*') ? 'open' : '' }}>

                <summary class="settings-toggle {{ request()->routeIs('super-admin.users.*', 'super-admin.roles.*', 'super-admin.audit-logs.*') ? 'is-active' : '' }}">

                    <i class="fas {{ $icons['settings'] }}"></i>

                    <span>Settings</span>

                    <i class="fas fa-chevron-down settings-chevron"></i>

                </summary>



                <div class="settings-submenu">

                    <a href="{{ $usersUrl }}"

                       class="nav-item {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}">

                        <i class="fas {{ $icons['user-cog'] }}"></i>

                        User Management

                    </a>

                    <a href="{{ $rolesUrl }}"

                       class="nav-item {{ request()->routeIs('super-admin.roles.*') ? 'active' : '' }}">

                        <i class="fas {{ $icons['roles'] }}"></i>

                        Roles &amp; Permissions

                    </a>

                    <a href="{{ $auditLogsUrl }}"
   class="nav-item {{ request()->routeIs('super-admin.audit-logs.*') ? 'active' : '' }}">
    <i class="fas fa-clipboard-list"></i>
    Audit Logs
</a>

                </div>

            </details>

        </nav>



        <div class="sidebar-bottom">

            <div class="security-card">

                <div class="security-title">

                    <i class="fas {{ $icons['shield'] }}"></i>

                    System workspace

                </div>

                <div class="security-copy">

                    Use this console to oversee registered schools and platform-wide access.

                </div>

            </div>



            <div class="profile">

                <div class="avatar">{{ $meInitial }}</div>

                <div>

                    <div class="profile-name">{{ $meName }}</div>

                    <div class="profile-role">Super administrator</div>

                </div>

            </div>



            @if ($logoutUrl)

                <form method="POST" action="{{ $logoutUrl }}" class="logout-form">

                    @csrf

                    <button type="submit" class="logout-btn">

                        <i class="fas {{ $icons['logout'] }}"></i>

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

                    <i class="fas {{ $icons['calendar'] }}"></i>

                    {{ now()->format('D, d M Y') }}

                </div>

                <div class="role-chip">SUPER ADMIN</div>

            </div>

        </header>



        <main class="content">

            @yield('content')

        </main>



        <footer class="footer">

            &copy; {{ date('Y') }} School Central &middot; Super Admin Console

        </footer>

    </div>

</div>



@stack('scripts')

</body>

</html>