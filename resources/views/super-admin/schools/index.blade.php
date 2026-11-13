@extends('super-admin.layouts.app')

@section('title', 'School Registry')
@section('breadcrumb', 'School Registry')
@section('page-title', 'School Registry')

@section('content')

@php
    $search        = trim((string) request('search'));
    $statusFilter  = in_array(request('status'), ['active', 'inactive'], true) ? request('status') : '';
    $hasFilters    = $search !== '' || $statusFilter !== '';

    // Share of all schools (used by the stat cards)
    $activePct   = $totalSchools > 0 ? round(($activeSchools / $totalSchools) * 100) : 0;
    $inactivePct = $totalSchools > 0 ? round(($inactiveSchools / $totalSchools) * 100) : 0;

    // Links that keep the current search while switching status
    $searchOnly = $search !== '' ? ['search' => $search] : [];
    $statusTabs = [
        ['key' => '',         'label' => 'All',      'count' => $totalSchools,    'url' => route('super-admin.schools.index', $searchOnly)],
        ['key' => 'active',   'label' => 'Active',   'count' => $activeSchools,   'url' => route('super-admin.schools.index', $searchOnly + ['status' => 'active'])],
        ['key' => 'inactive', 'label' => 'Inactive', 'count' => $inactiveSchools, 'url' => route('super-admin.schools.index', $searchOnly + ['status' => 'inactive'])],
    ];

    // Pagination window
    $current = $schools->currentPage();
    $last    = $schools->lastPage();
    $from    = max(1, $current - 2);
    $to      = min($last, $current + 2);

    // Avatar colours: the same school always gets the same tone
    $tones = ['blue', 'green', 'amber', 'red', 'violet'];

    // Line icons (24x24, stroke)
    $icons = [
        'school' => '<path d="M3 21h18M5 21V8l7-5 7 5v13"/><path d="M9 21v-6h6v6"/>',
        'check'  => '<path d="M20 6 9 17l-5-5"/>',
        'pause'  => '<circle cx="12" cy="12" r="9"/><path d="M10 9v6M14 9v6"/>',
        'plus'   => '<path d="M12 5v14M5 12h14"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'eye'    => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
        'edit'   => '<path d="M4 20h4L19 9l-4-4L4 16v4Z"/><path d="m13.5 6.5 4 4"/>',
        'power'  => '<path d="M12 3v9"/><path d="M6.4 6.6a8 8 0 1 0 11.2 0"/>',
        'left'   => '<path d="m15 18-6-6 6-6"/>',
        'right'  => '<path d="m9 18 6-6-6-6"/>',
        'copy'   => '<rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V6a2 2 0 0 1 2-2h9"/>',
        'x'      => '<path d="M18 6 6 18M6 6l12 12"/>',
        'filter' => '<path d="M3 5h18l-7 8v6l-4-2v-4L3 5Z"/>',
        'clock'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    ];
@endphp

<style>
    .sr-page{
        --r-green:var(--green,#2f7658);
        --r-green-dark:var(--green-dark,#245c45);
        --r-green-soft:var(--green-light,#e9f5ee);
        --r-orange:var(--orange,#e76b45);
        --r-ink:var(--navy,#172b27);
        --r-text:var(--text,#24332f);
        --r-muted:var(--muted,#75827d);
        --r-line:var(--border,#e6ebe7);
        --r-blue:#4778c7;  --r-blue-soft:#edf3ff;
        --r-amber:#b87918; --r-amber-soft:#fff5df;
        --r-red:#bd5149;   --r-red-soft:#fff0ed;
        --r-violet:#7a4fb5;--r-violet-soft:#f3edfb;
        --r-shadow:0 8px 22px rgba(23,43,39,.055);
        --r-heading:'Manrope','DM Sans',sans-serif;
        color:var(--r-text);
        font-size:14px
    }
    .sr-page *,.sr-page *::before,.sr-page *::after{box-sizing:border-box}
    .sr-page a{color:inherit;text-decoration:none}
    .sr-page h1,.sr-page h2,.sr-page h3,.sr-page p{margin:0}
    .sr-page svg.i{width:18px;height:18px;flex-shrink:0;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
    .sr-page :focus-visible{outline:3px solid rgba(47,118,88,.45);outline-offset:2px}

    /* ============================ Heading ============================ */
    .sr-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin-bottom:8px}
    .sr-eyebrow{display:flex;align-items:center;gap:8px;margin-bottom:9px;color:var(--r-green);font-size:10.5px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase}
    .sr-eyebrow::before{content:"";width:18px;height:2px;border-radius:2px;background:var(--r-orange)}
    .sr-heading h1{font-family:var(--r-heading);font-size:clamp(23px,3vw,30px);font-weight:800;letter-spacing:-.8px}
    .sr-subtitle{max-width:580px;margin-top:8px;color:var(--r-muted);font-size:13px;line-height:1.65}

    /* ============================ Buttons ============================ */
    .sr-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 17px;border:1px solid transparent;border-radius:10px;font-family:inherit;font-size:12.5px;font-weight:700;line-height:1.2;white-space:nowrap;cursor:pointer;transition:background .18s ease,border-color .18s ease,color .18s ease,transform .18s ease,box-shadow .18s ease}
    .sr-btn svg.i{width:16px;height:16px}
    .sr-btn-primary{background:var(--r-green);border-color:var(--r-green);color:#fff;box-shadow:0 6px 14px rgba(47,118,88,.2)}
    .sr-btn-primary:hover{background:var(--r-green-dark);border-color:var(--r-green-dark);color:#fff;transform:translateY(-1px);box-shadow:0 9px 18px rgba(47,118,88,.26)}
    .sr-btn-secondary{background:#fff;border-color:var(--r-line);color:var(--r-text)}
    .sr-btn-secondary:hover{border-color:var(--r-green);color:var(--r-green)}

    /* ============================ Alerts ============================ */
    .sr-alert{display:flex;align-items:flex-start;gap:10px;margin:18px 0 0;padding:13px 16px;border-radius:12px;font-size:13px;line-height:1.6}
    .sr-alert svg.i{margin-top:2px}
    .sr-alert.success{border:1px solid #c8e8d3;background:#e7f5ec;color:#236b43}
    .sr-alert.error{border:1px solid #f1c9c1;background:#fff0ed;color:#a33d31}
    .sr-alert ul{margin:5px 0 0;padding-left:18px}

    /* ======================= Section headings ======================= */
    .sr-section{display:flex;align-items:flex-end;justify-content:space-between;gap:15px;margin:28px 0 14px}
    .sr-section h3{font-family:var(--r-heading);font-size:15px;font-weight:800;letter-spacing:-.3px}
    .sr-section p{margin-top:4px;color:var(--r-muted);font-size:12px}
    .sr-section-note{display:inline-flex;align-items:center;gap:6px;color:var(--r-muted);font-size:12px}
    .sr-section-note svg.i{width:14px;height:14px}

    /* ========================= Stat cards ========================= */
    .sr-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-bottom:26px}

    .sr-stat{--c:var(--r-blue);--soft:var(--r-blue-soft);--glow:rgba(71,120,199,.09);position:relative;min-width:0;overflow:hidden;padding:22px 22px 20px 27px;border:1px solid var(--r-line);border-radius:16px;background:#fff;box-shadow:var(--r-shadow);transition:transform .2s ease,box-shadow .2s ease}
    .sr-stat:hover{transform:translateY(-4px);box-shadow:0 16px 30px rgba(23,43,39,.11)}
    .sr-stat::before{content:"";position:absolute;inset:0 auto 0 0;width:5px;background:var(--c)}
    .sr-stat::after{content:"";position:absolute;right:-42px;bottom:-52px;width:120px;height:120px;border-radius:50%;background:var(--glow);pointer-events:none}
    .sr-stat.active{--c:var(--r-green);--soft:var(--r-green-soft);--glow:rgba(47,118,88,.09)}
    .sr-stat.inactive{--c:var(--r-amber);--soft:var(--r-amber-soft);--glow:rgba(184,121,24,.1)}

    .sr-stat-top{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:10px}
    .sr-stat-label{color:var(--r-muted);font-size:12px;font-weight:700}
    .sr-stat-icon{display:grid;place-items:center;width:44px;height:44px;border-radius:12px;background:var(--soft);color:var(--c)}
    .sr-stat-icon svg.i{width:20px;height:20px}
    .sr-stat-value{position:relative;z-index:1;margin-top:14px;color:#1c332c;font-family:var(--r-heading);font-size:36px;font-weight:800;letter-spacing:-1.2px;line-height:1;font-variant-numeric:tabular-nums}

    .sr-stat-bar{position:relative;z-index:1;height:6px;margin-top:16px;overflow:hidden;border-radius:20px;background:#e9efeb}
    .sr-stat-bar span{display:block;height:100%;border-radius:inherit;background:var(--c)}

    .sr-stat-foot{position:relative;z-index:1;display:flex;align-items:center;gap:7px;margin-top:11px;color:#687a71;font-size:11.5px}
    .sr-stat-foot i{width:6px;height:6px;border-radius:50%;background:var(--c)}

    /* ============================= Panel ============================= */
    .sr-panel{overflow:hidden;border:1px solid var(--r-line);border-radius:16px;background:#fff;box-shadow:var(--r-shadow)}

    .sr-panel-head{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:20px 24px;border-bottom:1px solid var(--r-line)}
    .sr-panel-head h2{font-family:var(--r-heading);font-size:16px;font-weight:800}
    .sr-panel-head p{margin-top:5px;color:var(--r-muted);font-size:12px}

    .sr-count{padding:7px 12px;border:1px solid var(--r-line);border-radius:20px;background:#fafcfb;color:#5e6d66;font-size:11.5px;font-weight:700;white-space:nowrap}
    .sr-count strong{color:var(--r-ink)}

    /* ============================ Toolbar ============================ */
    .sr-toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:14px 24px;border-bottom:1px solid var(--r-line);background:#fbfcfb}

    .sr-tabs{display:flex;flex-wrap:wrap;gap:6px}
    .sr-tab{display:inline-flex;align-items:center;gap:8px;padding:8px 13px;border:1px solid var(--r-line);border-radius:20px;background:#fff;color:#5e6d66;font-size:12px;font-weight:700;transition:border-color .15s ease,background .15s ease,color .15s ease}
    .sr-tab:hover{border-color:#b9cbc1}
    .sr-tab em{min-width:22px;padding:2px 7px;border-radius:20px;background:#eef3f0;color:#5e6d66;font-size:10.5px;font-style:normal;font-weight:800;text-align:center;font-variant-numeric:tabular-nums}
    .sr-tab.is-on{border-color:var(--r-ink);background:var(--r-ink);color:#fff}
    .sr-tab.is-on em{background:rgba(255,255,255,.18);color:#fff}

    .sr-search-form{display:flex;align-items:center;gap:8px;flex:1 1 300px;max-width:460px}
    .sr-search{position:relative;flex:1;min-width:0}
    .sr-search svg.i{position:absolute;left:12px;top:50%;width:17px;height:17px;transform:translateY(-50%);color:#8a9791;pointer-events:none}
    .sr-control{width:100%;height:40px;padding:0 12px 0 38px;border:1px solid var(--r-line);border-radius:10px;background:#fff;color:var(--r-text);font-family:inherit;font-size:13px;outline:0;transition:border-color .15s ease,box-shadow .15s ease}
    .sr-control:focus{border-color:var(--r-green);box-shadow:0 0 0 3px rgba(47,118,88,.12)}

    /* Active filter tags */
    .sr-applied{display:flex;align-items:center;flex-wrap:wrap;gap:8px;padding:11px 24px;border-bottom:1px solid var(--r-line);background:#fff}
    .sr-applied-label{display:inline-flex;align-items:center;gap:6px;color:var(--r-muted);font-size:11px;font-weight:800;letter-spacing:.5px;text-transform:uppercase}
    .sr-applied-label svg.i{width:14px;height:14px;color:var(--r-green)}
    .sr-tag{display:inline-flex;align-items:center;gap:7px;max-width:260px;padding:5px 6px 5px 11px;border:1px solid #e7dfc9;border-radius:20px;background:#faf5e8;color:#765e32;font-size:11.5px;font-weight:700}
    .sr-tag span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .sr-tag a{display:grid;place-items:center;flex:0 0 18px;width:18px;height:18px;border-radius:50%;color:#8a6d36;transition:background .15s ease}
    .sr-tag a:hover{background:rgba(118,94,50,.16)}
    .sr-tag svg.i{width:11px;height:11px;stroke-width:2.4}
    .sr-clear-all{margin-left:auto;color:var(--r-green);font-size:12px;font-weight:700}
    .sr-clear-all:hover{text-decoration:underline!important}

    /* ============================= Table ============================= */
    .sr-table-wrap{width:100%;overflow-x:auto}
    .sr-table{width:100%;min-width:820px;border-collapse:collapse}
    .sr-table th{padding:13px 24px;background:#f8faf8;color:#87938d;font-size:10.5px;font-weight:800;letter-spacing:.8px;text-align:left;text-transform:uppercase;white-space:nowrap}
    .sr-table td{padding:15px 24px;border-top:1px solid #edf1ee;font-size:13px;vertical-align:middle}
    .sr-table tbody tr{transition:background .15s ease}
    .sr-table tbody tr:hover td{background:#fbfdfb}
    .sr-th-actions{text-align:right!important}

    .sr-school{display:flex;align-items:center;gap:13px;min-width:0}
    .sr-avatar{--t:var(--r-blue);--ts:var(--r-blue-soft);display:grid;place-items:center;flex:0 0 42px;width:42px;height:42px;border:1px solid color-mix(in srgb,var(--t) 22%,#fff);border-radius:12px;background:var(--ts);color:var(--t);font-family:var(--r-heading);font-size:16px;font-weight:800}
    .sr-avatar.green{--t:var(--r-green);--ts:var(--r-green-soft)}
    .sr-avatar.amber{--t:var(--r-amber);--ts:var(--r-amber-soft)}
    .sr-avatar.red{--t:var(--r-red);--ts:var(--r-red-soft)}
    .sr-avatar.violet{--t:var(--r-violet);--ts:var(--r-violet-soft)}
    .sr-school-name{max-width:280px;overflow:hidden;color:var(--r-ink);font-size:13.5px;font-weight:700;text-overflow:ellipsis;white-space:nowrap}
    .sr-school-id{margin-top:4px;color:var(--r-muted);font-size:11.5px}

    .sr-code-row{display:inline-flex;align-items:center;gap:6px}
    .sr-code{display:inline-block;padding:6px 10px;border-radius:7px;background:#f0f4f8;color:#526d8b;font-size:11.5px;font-weight:800;letter-spacing:.3px}
    .sr-copy{display:grid;place-items:center;width:28px;height:28px;padding:0;border:1px solid transparent;border-radius:7px;background:transparent;color:#9aa8a1;cursor:pointer;transition:background .15s ease,color .15s ease,border-color .15s ease}
    .sr-copy svg.i{width:14px;height:14px}
    .sr-copy:hover{border-color:var(--r-line);background:#fff;color:var(--r-green)}
    .sr-copy.copied{border-color:#cfe5d3;background:#edf8f0;color:#2d8050}

    .sr-date{color:var(--r-text);font-weight:600}
    .sr-date small{display:block;margin-top:3px;color:var(--r-muted);font-size:11px;font-weight:500}
    .sr-muted{color:var(--r-muted)}

    .sr-status{display:inline-flex;align-items:center;gap:6px;padding:6px 11px;border:1px solid transparent;border-radius:30px;font-size:11px;font-weight:800;text-transform:capitalize;white-space:nowrap}
    .sr-status::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}
    .sr-status.is-active{border-color:#d3e9d9;background:#edf8f0;color:#2d8050}
    .sr-status.is-inactive{border-color:#f0dcc9;background:#fff5eb;color:#a75a32}

    /* Row actions */
    .sr-actions{display:flex;flex-wrap:wrap;align-items:center;justify-content:flex-end;gap:7px}
    .sr-actions form{display:inline-flex;margin:0}
    .sr-action{display:inline-flex;align-items:center;gap:6px;padding:7px 11px;border:1px solid var(--r-line);border-radius:8px;background:#fff;color:var(--r-text);font-family:inherit;font-size:11.5px;font-weight:700;line-height:1.2;white-space:nowrap;cursor:pointer;transition:border-color .15s ease,color .15s ease,background .15s ease,transform .15s ease}
    .sr-action svg.i{width:14px;height:14px}
    .sr-action:hover{border-color:var(--r-green);color:var(--r-green);transform:translateY(-1px)}
    .sr-action.deactivate{color:#a75a32}
    .sr-action.deactivate:hover{border-color:#e6b99a;background:#fff5eb;color:#a75a32}
    .sr-action.activate{border-color:#cfe5d3;background:#edf8f0;color:#2d8050}
    .sr-action.activate:hover{border-color:#a8d4b4;background:#e0f2e6;color:#245c3a}

    /* Empty state */
    .sr-empty{padding:58px 20px;text-align:center}
    .sr-empty-icon{display:grid;place-items:center;width:64px;height:64px;margin:0 auto 16px;border:1px solid #dce9df;border-radius:18px;background:var(--r-green-soft);color:var(--r-green)}
    .sr-empty-icon svg.i{width:28px;height:28px}
    .sr-empty h3{margin-bottom:8px;font-family:var(--r-heading);font-size:16px;font-weight:800}
    .sr-empty p{max-width:380px;margin:0 auto 20px;color:var(--r-muted);font-size:12.5px;line-height:1.7}

    /* Pagination */
    .sr-pager{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:15px;padding:16px 24px;border-top:1px solid var(--r-line);background:#fbfcfb;color:var(--r-muted);font-size:12px}
    .sr-pager strong{color:var(--r-text)}
    .sr-pages{display:flex;flex-wrap:wrap;align-items:center;gap:5px}
    .sr-page-btn{display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border:1px solid var(--r-line);border-radius:9px;background:#fff;color:#5e6d66;font-size:12px;font-weight:700;transition:border-color .15s ease,color .15s ease}
    .sr-page-btn svg.i{width:15px;height:15px}
    a.sr-page-btn:hover{border-color:var(--r-green);color:var(--r-green)}
    .sr-page-btn.current{border-color:var(--r-ink);background:var(--r-ink);color:#fff}
    .sr-page-btn.disabled{background:#f6f8f6;color:#b4bdb8;cursor:not-allowed}
    .sr-gap{padding:0 4px;color:var(--r-muted)}

    /* ============================ Responsive ============================ */
    @media (max-width:980px){
        .sr-stats{gap:12px}
        .sr-stat{padding:18px 16px 16px 21px}
        .sr-stat-value{font-size:30px}
    }

    @media (max-width:760px){
        .sr-heading{flex-direction:column;gap:14px}
        .sr-heading .sr-btn{width:100%}
        .sr-section{align-items:flex-start;flex-direction:column;gap:4px}

        .sr-stats{grid-template-columns:1fr;gap:12px}

        .sr-panel-head{align-items:flex-start;padding:16px}
        .sr-toolbar{padding:12px 16px}
        .sr-tabs{width:100%}
        .sr-tab{flex:1;justify-content:center}
        .sr-search-form{flex-basis:100%;max-width:none}
        .sr-applied{padding:11px 16px}
        .sr-clear-all{margin-left:0}

        /* table -> cards */
        .sr-table-wrap{padding:12px;background:#f8faf8}
        .sr-table{min-width:0}
        .sr-table,.sr-table tbody,.sr-table tr,.sr-table td{display:block;width:100%}
        .sr-table thead{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)}
        .sr-table tbody tr{margin-bottom:12px;overflow:hidden;border:1px solid var(--r-line);border-radius:14px;background:#fff}
        .sr-table tbody tr:last-child{margin-bottom:0}
        .sr-table tbody tr:hover td{background:transparent}
        .sr-table td{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:11px 14px;border-top:1px solid #edf1ee;text-align:right}
        .sr-table td::before{content:attr(data-label);color:#87938d;font-size:10px;font-weight:800;letter-spacing:.6px;text-align:left;text-transform:uppercase}
        .sr-table td.cell-school{display:block;padding:14px;border-top:0;background:#fbfcfb;text-align:left}
        .sr-table td.cell-school::before{display:none}
        .sr-table td.cell-actions{display:block;padding:12px 14px}
        .sr-table td.cell-actions::before{display:none}
        .sr-date small{display:inline;margin:0 0 0 6px}
        .sr-school-name{max-width:none;white-space:normal}
        .sr-actions{justify-content:stretch}
        .sr-actions > *,.sr-actions form{flex:1}
        .sr-actions .sr-action{width:100%;justify-content:center;min-height:38px}

        .sr-pager{flex-direction:column;align-items:flex-start;padding:14px 16px}
    }

    @media (prefers-reduced-motion:reduce){.sr-page *{transition:none!important}}
</style>

<div class="sr-page">

    {{-- ============================ Heading ============================ --}}
    <div class="sr-heading">
        <div>
            <div class="sr-eyebrow">School management</div>
            <h1>School Registry</h1>
            <p class="sr-subtitle">
                Manage registered schools, review their status, and maintain your school directory.
            </p>
        </div>

        <a href="{{ route('super-admin.schools.create') }}" class="sr-btn sr-btn-primary">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['plus'] !!}</svg>
            Register New School
        </a>
    </div>

    {{-- ============================ Messages ============================ --}}
    @if (session('success'))
        <div class="sr-alert success" role="status">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['check'] !!}</svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="sr-alert error" role="alert">
            <div>
                <strong>Please check the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- ============================ Overview ============================ --}}
    <div class="sr-section">
        <div>
            <h3>Overview</h3>
            <p>Current totals across your school network</p>
        </div>
        <span class="sr-section-note">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['clock'] !!}</svg>
            Updated {{ now()->format('h:i A') }}
        </span>
    </div>

    <section class="sr-stats" aria-label="School totals">
        <article class="sr-stat">
            <div class="sr-stat-top">
                <span class="sr-stat-label">Total schools</span>
                <span class="sr-stat-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['school'] !!}</svg></span>
            </div>
            <div class="sr-stat-value">{{ number_format($totalSchools) }}</div>
            <div class="sr-stat-bar" aria-hidden="true"><span style="width:100%"></span></div>
            <div class="sr-stat-foot"><i></i> All registered schools</div>
        </article>

        <article class="sr-stat active">
            <div class="sr-stat-top">
                <span class="sr-stat-label">Active schools</span>
                <span class="sr-stat-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['check'] !!}</svg></span>
            </div>
            <div class="sr-stat-value">{{ number_format($activeSchools) }}</div>
            <div class="sr-stat-bar" role="img" aria-label="{{ $activePct }}% of schools are active"><span style="width:{{ $activePct }}%"></span></div>
            <div class="sr-stat-foot"><i></i> {{ $activePct }}% of all schools</div>
        </article>

        <article class="sr-stat inactive">
            <div class="sr-stat-top">
                <span class="sr-stat-label">Inactive schools</span>
                <span class="sr-stat-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['pause'] !!}</svg></span>
            </div>
            <div class="sr-stat-value">{{ number_format($inactiveSchools) }}</div>
            <div class="sr-stat-bar" role="img" aria-label="{{ $inactivePct }}% of schools are inactive"><span style="width:{{ $inactivePct }}%"></span></div>
            <div class="sr-stat-foot"><i></i> {{ $inactivePct }}% of all schools</div>
        </article>
    </section>

    {{-- ============================ Registry ============================ --}}
    <section class="sr-panel">

        <div class="sr-panel-head">
            <div>
                <h2>Registered schools</h2>
                <p>Search, filter, and manage schools in your system.</p>
            </div>

            <span class="sr-count"><strong>{{ number_format($schools->total()) }}</strong> {{ $schools->total() === 1 ? 'record' : 'records' }}</span>
        </div>

        {{-- Status tabs + search --}}
        <div class="sr-toolbar">
            <nav class="sr-tabs" aria-label="Filter by status">
                @foreach ($statusTabs as $tab)
                    <a href="{{ $tab['url'] }}"
                       class="sr-tab {{ $statusFilter === $tab['key'] ? 'is-on' : '' }}"
                       @if($statusFilter === $tab['key']) aria-current="true" @endif>
                        {{ $tab['label'] }} <em>{{ number_format($tab['count']) }}</em>
                    </a>
                @endforeach
            </nav>

            <form class="sr-search-form" method="GET" action="{{ route('super-admin.schools.index') }}" role="search">
                @if ($statusFilter !== '')
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif

                <label class="sr-search">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['search'] !!}</svg>
                    <input class="sr-control" type="search" name="search" value="{{ $search }}"
                           placeholder="Search school name or code..." aria-label="Search schools" autocomplete="off">
                </label>

                <button type="submit" class="sr-btn sr-btn-primary">Search</button>
            </form>
        </div>

        {{-- Applied filters --}}
        @if ($hasFilters)
            <div class="sr-applied">
                <span class="sr-applied-label">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['filter'] !!}</svg> Filtered by
                </span>

                @if ($search !== '')
                    <span class="sr-tag">
                        <span>Search: “{{ $search }}”</span>
                        <a href="{{ route('super-admin.schools.index', $statusFilter !== '' ? ['status' => $statusFilter] : []) }}"
                           aria-label="Remove search filter">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['x'] !!}</svg>
                        </a>
                    </span>
                @endif

                @if ($statusFilter !== '')
                    <span class="sr-tag">
                        <span>Status: {{ ucfirst($statusFilter) }}</span>
                        <a href="{{ route('super-admin.schools.index', $searchOnly) }}" aria-label="Remove status filter">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['x'] !!}</svg>
                        </a>
                    </span>
                @endif

                <a href="{{ route('super-admin.schools.index') }}" class="sr-clear-all">Clear all</a>
            </div>
        @endif

        @if ($schools->count())

            <div class="sr-table-wrap">
                <table class="sr-table">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>School code</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th class="sr-th-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($schools as $school)
                            @php
                                $isActive = strtolower((string) $school->status) === 'active';
                                $tone     = $tones[abs(crc32((string) $school->name)) % count($tones)];
                            @endphp

                            <tr>
                                <td class="cell-school" data-label="School">
                                    <div class="sr-school">
                                        <div class="sr-avatar {{ $tone }}">{{ strtoupper(substr($school->name ?? 'S', 0, 1)) }}</div>
                                        <div>
                                            <div class="sr-school-name" title="{{ $school->name }}">{{ $school->name }}</div>
                                            <div class="sr-school-id">School ID: #{{ $school->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="School code">
                                    @if ($school->school_code)
                                        <span class="sr-code-row">
                                            <span class="sr-code">{{ $school->school_code }}</span>
                                            <button type="button" class="sr-copy" data-copy="{{ $school->school_code }}"
                                                    title="Copy school code" aria-label="Copy school code {{ $school->school_code }}">
                                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['copy'] !!}</svg>
                                            </button>
                                        </span>
                                    @else
                                        <span class="sr-muted">Not assigned</span>
                                    @endif
                                </td>

                                <td data-label="Registered">
                                    @if ($school->created_at)
                                        <span class="sr-date">
                                            {{ $school->created_at->format('d M Y') }}
                                            <small>{{ $school->created_at->diffForHumans() }}</small>
                                        </span>
                                    @else
                                        <span class="sr-muted">—</span>
                                    @endif
                                </td>

                                <td data-label="Status">
                                    <span class="sr-status {{ $isActive ? 'is-active' : 'is-inactive' }}">
                                        {{ $school->status ?: 'Unknown' }}
                                    </span>
                                </td>

                                <td class="cell-actions" data-label="Actions">
                                    <div class="sr-actions">
                                        <a class="sr-action" href="{{ route('super-admin.schools.show', $school) }}">
                                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['eye'] !!}</svg> View
                                        </a>

                                        <a class="sr-action" href="{{ route('super-admin.schools.edit', $school) }}">
                                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['edit'] !!}</svg> Edit
                                        </a>

                                        <form method="POST" action="{{ route('super-admin.schools.status', $school) }}">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="status" value="{{ $isActive ? 'inactive' : 'active' }}">

                                            <button type="submit"
                                                    class="sr-action {{ $isActive ? 'deactivate' : 'activate' }}"
                                                    onclick="return confirm('Are you sure you want to {{ $isActive ? 'deactivate' : 'activate' }} this school?')">
                                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['power'] !!}</svg>
                                                {{ $isActive ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sr-pager">
                <div>
                    Showing <strong>{{ $schools->firstItem() }}–{{ $schools->lastItem() }}</strong>
                    of <strong>{{ number_format($schools->total()) }}</strong> schools
                </div>

                @if ($schools->hasPages())
                    <nav class="sr-pages" aria-label="Pagination">
                        @if ($schools->onFirstPage())
                            <span class="sr-page-btn disabled" aria-disabled="true"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['left'] !!}</svg></span>
                        @else
                            <a class="sr-page-btn" href="{{ $schools->withQueryString()->previousPageUrl() }}" aria-label="Previous page">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['left'] !!}</svg>
                            </a>
                        @endif

                        @if ($from > 1)
                            <a class="sr-page-btn" href="{{ $schools->withQueryString()->url(1) }}">1</a>
                            @if ($from > 2) <span class="sr-gap">…</span> @endif
                        @endif

                        @for ($page = $from; $page <= $to; $page++)
                            @if ($page === $current)
                                <span class="sr-page-btn current" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="sr-page-btn" href="{{ $schools->withQueryString()->url($page) }}">{{ $page }}</a>
                            @endif
                        @endfor

                        @if ($to < $last)
                            @if ($to < $last - 1) <span class="sr-gap">…</span> @endif
                            <a class="sr-page-btn" href="{{ $schools->withQueryString()->url($last) }}">{{ $last }}</a>
                        @endif

                        @if ($schools->hasMorePages())
                            <a class="sr-page-btn" href="{{ $schools->withQueryString()->nextPageUrl() }}" aria-label="Next page">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['right'] !!}</svg>
                            </a>
                        @else
                            <span class="sr-page-btn disabled" aria-disabled="true"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['right'] !!}</svg></span>
                        @endif
                    </nav>
                @endif
            </div>

        @else

            <div class="sr-empty">
                <div class="sr-empty-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['school'] !!}</svg></div>

                <h3>No schools found</h3>

                <p>
                    @if ($hasFilters)
                        No school matches your current filters. Try a different search or clear the filters.
                    @else
                        Register your first school to start building the directory.
                    @endif
                </p>

                @if ($hasFilters)
                    <a href="{{ route('super-admin.schools.index') }}" class="sr-btn sr-btn-secondary">Clear filters</a>
                @else
                    <a href="{{ route('super-admin.schools.create') }}" class="sr-btn sr-btn-primary">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['plus'] !!}</svg>
                        Register New School
                    </a>
                @endif
            </div>

        @endif
    </section>

</div>

<script>
(function () {
    // Copy a school code to the clipboard and show a short confirmation on the button.
    function copyText(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }

        return new Promise(function (resolve, reject) {
            const field = document.createElement('textarea');
            field.value = text;
            field.setAttribute('readonly', '');
            field.style.position = 'fixed';
            field.style.opacity = '0';
            document.body.appendChild(field);
            field.select();

            try {
                document.execCommand('copy') ? resolve() : reject();
            } catch (error) {
                reject(error);
            } finally {
                document.body.removeChild(field);
            }
        });
    }

    document.querySelectorAll('.sr-copy').forEach(function (button) {
        button.addEventListener('click', function () {
            copyText(button.dataset.copy).then(function () {
                const original = button.innerHTML;

                button.classList.add('copied');
                button.title = 'Copied';
                button.innerHTML = '<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>';

                setTimeout(function () {
                    button.classList.remove('copied');
                    button.title = 'Copy school code';
                    button.innerHTML = original;
                }, 1400);
            }).catch(function () {
                window.prompt('Copy this school code:', button.dataset.copy);
            });
        });
    });
})();
</script>

@endsection