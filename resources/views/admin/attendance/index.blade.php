@extends('layouts.app')

@section('title', 'Faculty Attendance')
@section('page-title', 'Faculty Attendance')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $today = now()->startOfDay();

    $requestedDate  = request('date');
    $requestedMonth = request('month', $month ?? now()->format('Y-m'));

    $selectedDate = $today->copy();

    try {
        if (is_string($requestedDate) && $requestedDate !== '') {
            $selectedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $requestedDate)->startOfDay();
        } elseif (is_string($requestedMonth) && $requestedMonth !== '') {
            $selectedDate = \Carbon\Carbon::createFromFormat('Y-m', $requestedMonth)->startOfMonth();
        }
    } catch (\Throwable $e) {
        $selectedDate = $today->copy();
    }

    $rangeStart = $selectedDate->copy()->startOfWeek();
    $rangeEnd   = $selectedDate->copy()->endOfWeek();
    $rangeLabel = 'Week of ' . $rangeStart->format('d M') . ' – ' . $rangeEnd->format('d M Y');

    $dateKey  = $selectedDate->format('Y-m-d');
    $monthKey = $selectedDate->format('Y-m');

    $isToday = $selectedDate->isSameDay($today);

    $nationalHolidays = [
        '01-26' => 'Republic Day',
        '08-15' => 'Independence Day',
        '10-02' => 'Gandhi Jayanti',
    ];

    $holidayOf = function (\Carbon\Carbon $date) use ($nationalHolidays) {
        if ($date->isSunday()) return 'Sunday';
        return $nationalHolidays[$date->format('m-d')] ?? null;
    };

    $days = [];
    $dayIndex = 0;

    for ($cursor = $rangeStart->copy(); $cursor->lte($rangeEnd); $cursor->addDay()) {
        $date = $cursor->copy();
        $dayIndex++;

        $days[$dayIndex] = [
            'number'  => (int) $date->format('j'),
            'date'    => $date,
            'key'     => $date->format('Y-m-d'),
            'holiday' => $holidayOf($date),
            'future'  => $date->gt($today),
            'selected'=> $date->isSameDay($selectedDate),
            'url'     => request()->url() . '?' . http_build_query([
                'date'       => $date->format('Y-m-d'),
                'month'      => $date->format('Y-m'),
                'teacher_id' => request('teacher_id'),
            ]),
        ];
    }

    $totalFaculty = $teachers->count();
    $totalPresent = 0;
    $totalHalf    = 0;
    $totalAbsent  = 0;

    foreach ($teachers as $teacher) {
        foreach ($days as $meta) {
            if ($meta['holiday'] || $meta['future']) continue;

            $recordKey = $teacher->id . '_' . $meta['key'];
            $status    = $attendanceRecords[$recordKey]->status ?? '';

            if ($status === 'Present')  $totalPresent++;
            if ($status === 'Half Day') $totalHalf++;
            if ($status === 'Absent')   $totalAbsent++;
        }
    }

    $prevWeekUrl = request()->url() . '?' . http_build_query([
        'date'       => $rangeStart->copy()->subWeek()->format('Y-m-d'),
        'month'      => $rangeStart->copy()->subWeek()->format('Y-m'),
        'teacher_id' => request('teacher_id'),
    ]);

    $nextWeekUrl = request()->url() . '?' . http_build_query([
        'date'       => $rangeStart->copy()->addWeek()->format('Y-m-d'),
        'month'      => $rangeStart->copy()->addWeek()->format('Y-m'),
        'teacher_id' => request('teacher_id'),
    ]);

    $todayUrl = request()->url() . '?' . http_build_query([
        'date'       => $today->format('Y-m-d'),
        'month'      => $today->format('Y-m'),
        'teacher_id' => request('teacher_id'),
    ]);

    // Store URL – adjust if your route name is different
    $storeUrl = route('admin.teachers.attendance.store');
@endphp

<style>
    .attendance-page{ max-width:1500px; margin:0 auto; }

    .paper{
        background:#fff;
        border:1px solid var(--line);
        border-radius:10px;
    }

    .btn-solid, .btn-line{
        display:inline-flex; align-items:center; justify-content:center;
        gap:8px; height:40px; padding:0 16px; border-radius:8px;
        border:1px solid transparent; cursor:pointer; font-family:inherit;
        font-size:13px; font-weight:500; line-height:1; text-decoration:none; white-space:nowrap;
    }
    .btn-solid{ color:#fff; background:var(--ink); border-color:var(--ink); }
    .btn-solid:hover{ background:var(--ink-2); color:#fff; }
    .btn-line{ color:var(--text); background:#fff; border-color:#cfc8b6; }
    .btn-line:hover{ color:var(--ink); background:#fffdf6; border-color:var(--ink); }
    .btn-line.is-disabled{ opacity:.46; pointer-events:none; }
    .btn-icon{ width:40px; padding:0; }

    .form-control{
        width:100%; height:40px; padding:0 12px; color:var(--text);
        background:#fff; border:1px solid #cfc8b6; border-radius:8px;
        outline:none; font-family:inherit; font-size:13.5px;
    }
    .form-control:focus{ border-color:var(--ink); box-shadow:0 0 0 3px rgba(29,58,51,.10); }

    .c-blue{--c:#2b5d8a} .c-green{--c:#3a9d6b} .c-red{--c:#e4572e}
    .c-amber{--c:#d58a0b} .c-violet{--c:#7a4fb5}

    /* Header */
    .attendance-hero{
        display:flex; align-items:stretch; overflow:hidden; margin-bottom:21px;
        border-radius:14px; border:1px solid #e8e2d4; background:#fff;
        box-shadow:0 4px 18px rgba(29,58,51,.06);
    }
    .hero-left{
        flex:1 1 auto; display:flex; align-items:flex-start; gap:18px;
        padding:22px 26px 22px 22px; position:relative; background:#fffdf9;
    }
    .hero-left::before{
        content:""; position:absolute; top:0; bottom:0; left:0; width:5px;
        background:#2d6a4f; border-radius:14px 0 0 14px;
    }
    .hero-icon{
        width:48px; height:48px; flex-shrink:0; display:flex; align-items:center;
        justify-content:center; background:#f3ebe0; border-radius:12px;
        color:#8a6a3d; font-size:22px;
    }
    .hero-kicker{ margin:0 0 6px; color:#a67c3d; font-size:11px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; }
    .attendance-title{ margin:0 0 6px; color:#1a2e28; font-family:var(--serif); font-size:26px; font-weight:700; line-height:1.2; }
    .attendance-subtitle{ margin:0; color:#6b6560; font-size:13.5px; line-height:1.45; max-width:420px; }
    .hero-meta{ display:flex; flex-wrap:wrap; gap:8px; margin-top:14px; }
    .meta-chip{
        display:inline-flex; align-items:center; gap:6px; padding:5px 11px;
        color:#2b5d8a; background:color-mix(in srgb, #2b5d8a 10%, #fff);
        border-radius:6px; font-size:11.5px; font-weight:600;
    }
    .hero-right{
        flex:0 0 280px; display:flex; flex-direction:column; justify-content:space-between;
        gap:16px; padding:20px 22px; background:#1e3a32; color:#e8f0ec;
    }
    .hero-right-kicker{ margin:0 0 4px; color:#9ec5b5; font-size:11px; font-weight:700; letter-spacing:.5px; text-transform:uppercase; }
    .hero-right-text{ margin:0; color:#c5d9d0; font-size:13.5px; line-height:1.4; }
    .hero-actions{ display:flex; flex-direction:column; gap:10px; }
    .month-display{
        display:inline-flex; align-items:center; gap:8px; padding:9px 13px;
        color:#e8f0ec; background:rgba(255,255,255,.08); border:1px solid #4a6b62;
        border-radius:8px; font-family:var(--serif); font-size:14px; font-weight:700; white-space:nowrap;
    }

    /* Summary */
    .summary-grid{ display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:18px; margin-bottom:21px; }
    .summary-card{
        position:relative; display:flex; flex-direction:column; gap:13px;
        padding:20px 20px 16px; margin-top:10px; border-top:4px solid var(--c);
        transition:transform .18s ease, box-shadow .18s ease;
    }
    .summary-card:hover{ transform:translateY(-4px); box-shadow:0 13px 24px rgba(29,58,51,.12); }
    .summary-tab{
        position:absolute; top:-24px; left:15px; padding:3px 11px; color:#fff;
        background:var(--c); border-radius:7px 7px 0 0; font-size:11.5px; font-weight:600;
    }
    .summary-top{ display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .summary-value{ color:var(--ink); font-family:var(--serif); font-size:30px; font-weight:700; line-height:1; }
    .summary-icon{
        display:flex; align-items:center; justify-content:center; width:42px; height:42px;
        flex-shrink:0; color:var(--c); background:color-mix(in srgb, var(--c) 14%, #fff);
        border-radius:12px; font-size:18px;
    }
    .summary-label{ padding-top:11px; color:var(--muted); border-top:1px dashed #d8d1bf; font-size:12px; }

    /* Toolbar */
    .attendance-toolbar{
        display:flex; align-items:center; gap:12px; flex-wrap:wrap;
        padding:14px 18px; margin-bottom:16px;
    }
    .toolbar-left{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; flex:1 1 auto; }
    .filter-group{ display:flex; flex-direction:column; gap:4px; min-width:180px; }
    .filter-group label{ font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.3px; }
    .filter-group .form-control{ height:38px; font-size:13px; }
    .week-nav{ display:flex; align-items:center; gap:6px; }
    .selected-week-display{ font-family:var(--serif); font-size:14px; font-weight:700; color:var(--ink); white-space:nowrap; padding:0 8px; }

    /* Register */
    .register-panel{ overflow:hidden; }
    .register-head{
        display:flex; align-items:center; justify-content:space-between; gap:16px;
        flex-wrap:wrap; padding:15px 20px; border-bottom:1px dashed #d8d1bf;
    }
    .register-head h3{ margin:0; color:var(--ink); font-family:var(--serif); font-size:17px; font-weight:700; }
    .attendance-legend{ display:flex; gap:13px; flex-wrap:wrap; color:var(--muted); font-size:11.5px; }
    .attendance-legend span{ display:inline-flex; align-items:center; gap:6px; }

    .register-wrap{ max-height:72vh; overflow:auto; }

    .attendance-table{ width:max-content; min-width:100%; border-collapse:separate; border-spacing:0; }
    .attendance-table th, .attendance-table td{
        padding:0; color:var(--text); background:#fff;
        border-right:1px solid #f6f2e8; border-bottom:1px solid #f0ebe0;
        text-align:center; font-size:12.5px;
    }
    .attendance-table thead th{
        position:sticky; top:0; z-index:3; height:52px; color:var(--muted);
        background:#f8f6f0; border-bottom:1px solid #e6e0d2;
        font-size:10.5px; font-weight:700; letter-spacing:.3px; text-transform:uppercase;
    }
    .attendance-table tbody tr:hover td{ background:#fffdf6; }

    .column-number{ position:sticky; left:0; z-index:2; width:46px; min-width:46px; color:var(--muted); font-weight:600; }
    .column-faculty{
        position:sticky; left:46px; z-index:2; width:230px; min-width:230px;
        padding:10px 14px !important; box-shadow:2px 0 0 #e6e0d2; text-align:left !important;
    }
    .attendance-table thead .column-number, .attendance-table thead .column-faculty{ z-index:5; }
    .attendance-table tbody tr:hover .column-number,
    .attendance-table tbody tr:hover .column-faculty{ background:#fffdf6; }

    .faculty-info{ display:flex; align-items:center; gap:10px; }
    .faculty-avatar{
        display:flex; align-items:center; justify-content:center; width:34px; height:34px;
        flex-shrink:0; color:#7a4fb5; background:color-mix(in srgb, #7a4fb5 14%, #fff);
        border-radius:50%; font-size:12px; font-weight:700;
    }
    .faculty-name{ max-width:145px; overflow:hidden; color:var(--ink); font-size:13px; font-weight:600; line-height:1.25; text-overflow:ellipsis; white-space:nowrap; }
    .faculty-details{ margin-top:2px; color:var(--muted); font-size:10.5px; }

    .column-day{ width:78px; min-width:78px; height:58px; }
    .column-day-selected{ background:color-mix(in srgb, #2b5d8a 7%, #fff) !important; box-shadow:inset 2px 0 0 #2b5d8a, inset -2px 0 0 #2b5d8a; }
    .attendance-table thead .column-day-selected{ color:#fff; background:#2b5d8a !important; }

    .day-heading{
        display:flex; align-items:center; justify-content:center; flex-direction:column;
        gap:1px; width:100%; height:100%; color:inherit; line-height:1.1; text-decoration:none;
    }
    .day-heading b{ color:var(--ink); font-family:var(--serif); font-size:14px; }
    .attendance-table thead .column-day-selected .day-heading b{ color:#fff; }
    .day-heading em{ font-style:normal; font-size:9px; opacity:.82; }

    .attendance-table thead .is-holiday{ background:#fff8e8; }
    .attendance-table thead .is-holiday b{ color:#8a5600; }
    .attendance-table thead .is-future{ opacity:.55; }
    .attendance-table td.is-holiday{ background:#fffcf3; }
    .attendance-table td.is-future{ background:#fafaf7; }

    /* Status button + auto-save feedback */
    .status-btn{
        display:inline-flex; align-items:center; justify-content:center;
        width:34px; height:34px; border-radius:8px; border:1px solid #e0d9c8;
        background:#f8f6f0; color:#c9c2ae; font-size:12px; font-weight:800;
        cursor:pointer; transition:all .15s ease; user-select:none;
    }
    .status-btn:hover{ transform:scale(1.08); box-shadow:0 2px 8px rgba(29,58,51,.12); }
    .status-present{ color:#2b7a52; background:color-mix(in srgb,#3a9d6b 16%,#fff); border-color:#a7d7bb; }
    .status-half{ color:#8a5600; background:color-mix(in srgb,#d58a0b 16%,#fff); border-color:#ecd3a3; }
    .status-absent{ color:#c23d17; background:color-mix(in srgb,#e4572e 16%,#fff); border-color:#f0c5ba; }
    .status-holiday{ color:#8a5600; background:color-mix(in srgb,#d58a0b 16%,#fff); border-color:#ecd3a3; cursor:not-allowed; }
    .status-blank{ color:#c9c2ae; background:#f8f6f0; border-color:#e0d9c8; }

    .cell-sync{
        display:block; min-height:12px; margin-top:3px;
        color:var(--muted); font-size:9px; line-height:1;
    }
    .cell-sync.is-saving{ color:#2b5d8a; }
    .cell-sync.is-saved{ color:#2b7a52; }
    .cell-sync.is-error{ color:#c23d17; }

    .spin{
        display:inline-block; width:10px; height:10px;
        border:2px solid currentColor; border-right-color:transparent;
        border-radius:50%; animation:spin .7s linear infinite;
    }
    @keyframes spin{ to{ transform:rotate(360deg); } }

    .column-total{ width:54px; min-width:54px; font-weight:700; }
    .total-present{ color:#2b7a52; }
    .total-half{ color:#8a5600; }
    .total-absent{ color:#c23d17; }
    .total-percentage{ color:var(--ink); }

    .table-footer{
        display:flex; align-items:center; justify-content:space-between;
        gap:16px; flex-wrap:wrap; padding:14px 20px; border-top:1px dashed #d8d1bf;
    }

    .toast{
        position:fixed; right:22px; bottom:22px; z-index:2000; max-width:350px;
        padding:12px 17px; color:#fff; background:var(--ink); border-radius:10px;
        box-shadow:0 12px 28px rgba(29,58,51,.28); font-size:13px;
        opacity:0; pointer-events:none; transform:translateY(10px);
        transition:opacity .2s ease, transform .2s ease;
    }
    .toast.is-show{ opacity:1; transform:translateY(0); }
    .toast.is-error{ background:#c23d17; }

    .empty-state{ padding:60px 20px; text-align:center; }
    .empty-icon{
        display:flex; align-items:center; justify-content:center; width:60px; height:60px;
        margin:0 auto 14px; color:#2b5d8a; background:color-mix(in srgb,#2b5d8a 12%,#fff);
        border-radius:50%; font-size:25px;
    }
    .empty-state h5{ margin:0 0 5px; color:var(--ink); font-family:var(--serif); font-size:18px; font-weight:700; }
    .empty-state p{ margin:0; color:var(--muted); font-size:13px; }

    @media (max-width:1200px){ .summary-grid{ grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width:900px){
        .attendance-hero{ flex-direction:column; }
        .hero-right{ flex:none; width:100%; }
        .hero-actions{ flex-direction:row; flex-wrap:wrap; }
    }
    @media (max-width:760px){
        .hero-left{ padding:18px 16px 18px 18px; gap:14px; }
        .attendance-title{ font-size:22px; }
        .hero-icon{ width:42px; height:42px; font-size:19px; }
        .column-faculty{ width:180px; min-width:180px; }
        .faculty-name{ max-width:110px; }
    }
    @media (max-width:520px){ .summary-grid{ grid-template-columns:1fr; } }
</style>

<div class="attendance-page">

    {{-- Header --}}
    <section class="paper attendance-hero">
        <div class="hero-left">
            <div class="hero-icon"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="hero-kicker">ATTENDANCE / FACULTY</div>
                <h1 class="attendance-title">Faculty Attendance</h1>
                <p class="attendance-subtitle">Weekly attendance register for teaching staff. Changes save automatically.</p>
                <div class="hero-meta">
                    <span class="meta-chip"><i class="bi bi-people-fill"></i> {{ $totalFaculty }} Faculty</span>
                    <span class="meta-chip"><i class="bi bi-calendar-week"></i> {{ $rangeLabel }}</span>
                </div>
            </div>
        </div>
        <div class="hero-right">
            <div>
                <div class="hero-right-kicker">SCHOOL DIARY</div>
                <p class="hero-right-text">Managing faculty attendance for the week</p>
            </div>
            <div class="hero-actions">
                <div class="month-display">
                    <i class="bi bi-calendar-week"></i>
                    {{ $rangeStart->format('d M') }} – {{ $rangeEnd->format('d M Y') }}
                </div>
            </div>
        </div>
    </section>

    {{-- Summary --}}
    <section class="summary-grid">
        <article class="paper summary-card c-blue">
            <span class="summary-tab">Faculty</span>
            <div class="summary-top">
                <div class="summary-value" id="sumTotal">{{ $totalFaculty }}</div>
                <div class="summary-icon"><i class="bi bi-person-badge-fill"></i></div>
            </div>
            <div class="summary-label">Total faculty members</div>
        </article>
        <article class="paper summary-card c-green">
            <span class="summary-tab">Present</span>
            <div class="summary-top">
                <div class="summary-value" id="sumPresent">{{ $totalPresent }}</div>
                <div class="summary-icon"><i class="bi bi-check-circle-fill"></i></div>
            </div>
            <div class="summary-label">Present this week</div>
        </article>
        <article class="paper summary-card c-amber">
            <span class="summary-tab">Half Day</span>
            <div class="summary-top">
                <div class="summary-value" id="sumHalf">{{ $totalHalf }}</div>
                <div class="summary-icon"><i class="bi bi-clock-history"></i></div>
            </div>
            <div class="summary-label">Half-day this week</div>
        </article>
        <article class="paper summary-card c-red">
            <span class="summary-tab">Absent</span>
            <div class="summary-top">
                <div class="summary-value" id="sumAbsent">{{ $totalAbsent }}</div>
                <div class="summary-icon"><i class="bi bi-x-circle-fill"></i></div>
            </div>
            <div class="summary-label">Absent this week</div>
        </article>
    </section>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.teachers.attendance.index') }}" class="paper attendance-toolbar" id="filterForm">
        <div class="toolbar-left">
            <div class="filter-group">
                <label for="teacher_id">Faculty</label>
                <select name="teacher_id" id="teacher_id" class="form-control">
                    <option value="">All Faculty</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ trim($teacher->first_name . ' ' . $teacher->last_name) }}
                            @if($teacher->teacher_id) — {{ $teacher->teacher_id }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group" style="min-width:150px">
                <label for="date">Week of</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $dateKey }}" max="{{ $today->format('Y-m-d') }}">
            </div>
            <div style="align-self:flex-end">
                <button type="submit" class="btn-solid"><i class="bi bi-funnel-fill"></i> Apply</button>
            </div>
        </div>
        <div class="week-nav">
            <a href="{{ $prevWeekUrl }}" class="btn-line btn-icon" title="Previous week"><i class="bi bi-chevron-left"></i></a>
            <span class="selected-week-display">{{ $rangeLabel }}</span>
            <a href="{{ $nextWeekUrl }}" class="btn-line btn-icon" title="Next week"><i class="bi bi-chevron-right"></i></a>
            <a href="{{ $todayUrl }}" class="btn-line {{ $isToday ? 'is-disabled' : '' }}" style="margin-left:6px">This Week</a>
        </div>
    </form>

    {{-- Register --}}
    @if($teachers->count())
        <section class="paper register-panel">
            <div class="register-head">
                <h3>Attendance Register · {{ $rangeLabel }}</h3>
                <div class="attendance-legend">
                    <span><span class="status-btn status-present" style="width:22px;height:22px;font-size:10px;pointer-events:none">P</span> Present</span>
                    <span><span class="status-btn status-half" style="width:22px;height:22px;font-size:10px;pointer-events:none">H</span> Half Day</span>
                    <span><span class="status-btn status-absent" style="width:22px;height:22px;font-size:10px;pointer-events:none">A</span> Absent</span>
                    <span><span class="status-btn status-holiday" style="width:22px;height:22px;font-size:9px;pointer-events:none">HOL</span> Holiday</span>
                </div>
            </div>

            <div class="register-wrap">
                <table class="attendance-table" id="attendanceTable">
                    <thead>
                        <tr>
                            <th class="column-number">#</th>
                            <th class="column-faculty">Faculty</th>
                            @foreach($days as $meta)
                                <th class="column-day {{ $meta['selected'] ? 'column-day-selected' : '' }} {{ $meta['holiday'] ? 'is-holiday' : '' }} {{ $meta['future'] ? 'is-future' : '' }}">
                                    @if($meta['future'])
                                        <span class="day-heading"><b>{{ $meta['number'] }}</b><em>{{ $meta['date']->format('D') }}</em></span>
                                    @else
                                        <a href="{{ $meta['url'] }}" class="day-heading"><b>{{ $meta['number'] }}</b><em>{{ $meta['date']->format('D') }}</em></a>
                                    @endif
                                </th>
                            @endforeach
                            <th class="column-total">P</th>
                            <th class="column-total">H</th>
                            <th class="column-total">A</th>
                            <th class="column-total">%</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceBody">
                        @foreach($teachers as $index => $teacher)
                            @php
                                $rowPresent = 0; $rowHalf = 0; $rowAbsent = 0;
                                $initials = strtoupper(substr($teacher->first_name ?? 'F', 0, 1) . substr($teacher->last_name ?? '', 0, 1));
                            @endphp
                            <tr data-teacher-id="{{ $teacher->id }}">
                                <td class="column-number">{{ $index + 1 }}</td>
                                <td class="column-faculty">
                                    <div class="faculty-info">
                                        <div class="faculty-avatar">{{ $initials ?: 'F' }}</div>
                                        <div style="min-width:0">
                                            <div class="faculty-name">{{ trim($teacher->first_name . ' ' . $teacher->last_name) }}</div>
                                            <div class="faculty-details">
                                                @if($teacher->teacher_id) ID {{ $teacher->teacher_id }} @endif
                                                @if($teacher->subject) · {{ $teacher->subject }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                @foreach($days as $meta)
                                    @php
                                        $recordKey = $teacher->id . '_' . $meta['key'];
                                        $status = $attendanceRecords[$recordKey]->status ?? '';
                                        if (!$meta['holiday'] && !$meta['future']) {
                                            if ($status === 'Present')  $rowPresent++;
                                            if ($status === 'Half Day') $rowHalf++;
                                            if ($status === 'Absent')   $rowAbsent++;
                                        }
                                    @endphp

                                    @if($meta['holiday'] || $meta['future'])
                                        <td class="column-day {{ $meta['selected'] ? 'column-day-selected' : '' }} {{ $meta['holiday'] ? 'is-holiday' : '' }} {{ $meta['future'] ? 'is-future' : '' }}">
                                            @if($meta['holiday'])
                                                <button type="button" class="status-btn status-holiday" disabled>HOL</button>
                                            @endif
                                        </td>
                                    @else
                                        <td class="column-day {{ $meta['selected'] ? 'column-day-selected' : '' }}"
                                            data-date="{{ $meta['key'] }}"
                                            data-status="{{ $status }}"
                                            data-saved="{{ $status }}">
                                            <button type="button"
                                                    class="status-btn status-{{ $status === 'Present' ? 'present' : ($status === 'Half Day' ? 'half' : ($status === 'Absent' ? 'absent' : 'blank')) }}"
                                                    data-status="{{ $status }}">
                                                @switch($status)
                                                    @case('Present') P @break
                                                    @case('Half Day') H @break
                                                    @case('Absent') A @break
                                                    @default •
                                                @endswitch
                                            </button>
                                            <span class="cell-sync" aria-live="polite"></span>
                                        </td>
                                    @endif
                                @endforeach

                                @php
                                    $marked = $rowPresent + $rowHalf + $rowAbsent;
                                    $points = $rowPresent + ($rowHalf * 0.5);
                                    $pct = $marked > 0 ? round(($points / $marked) * 100, 1) : null;
                                @endphp
                                <td class="column-total total-present">{{ $rowPresent }}</td>
                                <td class="column-total total-half">{{ $rowHalf }}</td>
                                <td class="column-total total-absent">{{ $rowAbsent }}</td>
                                <td class="column-total total-percentage">{{ $pct === null ? '–' : $pct . '%' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="attendance-legend">
                    <span>Click any cell → Present → Half Day → Absent → Blank. Saves automatically.</span>
                </div>
            </div>
        </section>
    @else
        <section class="paper">
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-person-x"></i></div>
                <h5>No Faculty Found</h5>
                <p>No faculty members are available for the selected filters.</p>
            </div>
        </section>
    @endif
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script>
(function () {
    'use strict';

    const STORE_URL = @json($storeUrl);
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const MONTH = @json($monthKey);

    const statuses = ['', 'Present', 'Half Day', 'Absent'];
    const statusClasses = {
        '': 'status-blank',
        'Present': 'status-present',
        'Half Day': 'status-half',
        'Absent': 'status-absent'
    };
    const statusLetters = {
        '': '•',
        'Present': 'P',
        'Half Day': 'H',
        'Absent': 'A'
    };

    const toastEl = document.getElementById('toast');
    let toastTimer = null;

    function showToast(msg, isError = false) {
        if (!toastEl) return;
        toastEl.textContent = msg;
        toastEl.classList.toggle('is-error', isError);
        toastEl.classList.add('is-show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('is-show'), 3500);
    }

    function setSync(cell, state, text = '') {
        const sync = cell.querySelector('.cell-sync');
        if (!sync) return;
        sync.className = 'cell-sync' + (state ? ' is-' + state : '');
        if (state === 'saving') {
            sync.innerHTML = '<span class="spin"></span>';
        } else {
            sync.textContent = text;
        }
        if (state === 'saved') {
            clearTimeout(cell._syncTimer);
            cell._syncTimer = setTimeout(() => setSync(cell, ''), 1400);
        }
    }

    function updateRowSummary(row) {
        let present = 0, half = 0, absent = 0;
        row.querySelectorAll('[data-status]').forEach(cell => {
            const s = cell.dataset.status || '';
            if (s === 'Present') present++;
            if (s === 'Half Day') half++;
            if (s === 'Absent') absent++;
        });
        const marked = present + half + absent;
        const points = present + (half * 0.5);
        const pct = marked > 0 ? ((points / marked) * 100).toFixed(1) : null;

        const totals = row.querySelectorAll('.column-total');
        if (totals[0]) totals[0].textContent = present;
        if (totals[1]) totals[1].textContent = half;
        if (totals[2]) totals[2].textContent = absent;
        if (totals[3]) totals[3].textContent = pct === null ? '–' : pct + '%';
    }

    function refreshSummaryCards() {
        let present = 0, half = 0, absent = 0;
        document.querySelectorAll('#attendanceBody [data-status]').forEach(cell => {
            const s = cell.dataset.status || '';
            if (s === 'Present') present++;
            if (s === 'Half Day') half++;
            if (s === 'Absent') absent++;
        });
        document.getElementById('sumPresent').textContent = present;
        document.getElementById('sumHalf').textContent = half;
        document.getElementById('sumAbsent').textContent = absent;
    }

    function getNextStatus(current) {
        const idx = statuses.indexOf(current);
        return statuses[(idx === -1 ? 0 : idx + 1) % statuses.length];
    }

    function renderButton(btn, status) {
        btn.className = 'status-btn ' + (statusClasses[status] || 'status-blank');
        btn.textContent = statusLetters[status] || '•';
        btn.dataset.status = status;
        btn.title = (status || 'Not Marked') + ' — click to cycle';
    }

    // Queue + auto-save
    const pending = new Map(); // key = teacherId_date

    async function saveAttendance(teacherId, date, status) {
        const key = teacherId + '_' + date;
        pending.set(key, status);

        const formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('attendance_month', MONTH);
        formData.append(`attendance[${teacherId}][${date}]`, status);

        try {
            const res = await fetch(STORE_URL, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok || data.success === false) {
                throw new Error(data.message || 'Could not save attendance');
            }

            return true;
        } catch (err) {
            throw err;
        } finally {
            pending.delete(key);
        }
    }

    // Click handler
    document.getElementById('attendanceBody')?.addEventListener('click', async function (e) {
        const btn = e.target.closest('.status-btn:not(.status-holiday)');
        if (!btn) return;

        const cell = btn.closest('[data-date]');
        if (!cell) return;

        const row = cell.closest('tr');
        const teacherId = row.dataset.teacherId;
        const date = cell.dataset.date;
        const current = cell.dataset.status || '';
        const next = getNextStatus(current);

        // Optimistic UI
        cell.dataset.status = next;
        renderButton(btn, next);
        updateRowSummary(row);
        refreshSummaryCards();
        setSync(cell, 'saving');

        try {
            await saveAttendance(teacherId, date, next);
            cell.dataset.saved = next;
            setSync(cell, 'saved', 'Saved');
        } catch (err) {
            // Rollback
            cell.dataset.status = cell.dataset.saved || '';
            renderButton(btn, cell.dataset.saved || '');
            updateRowSummary(row);
            refreshSummaryCards();
            setSync(cell, 'error', 'Error');
            showToast(err.message || 'Failed to save', true);
        }
    });

    // Auto submit filter form on date change
    document.getElementById('date')?.addEventListener('change', function () {
        this.form.submit();
    });

    // Warn if leaving while saving
    window.addEventListener('beforeunload', function (e) {
        if (pending.size > 0) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
})();
</script>

@endsection