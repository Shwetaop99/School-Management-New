@extends('layouts.app')

@section('title', 'Mark Attendance')
@section('page-title', 'Student Attendance')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    /* =========================================================
       Date setup
       ========================================================= */

    $today = now()->startOfDay();

    $requestedDate  = request('date');
    $requestedMonth = request('month', $month ?? null);

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

    if ($selectedDate->gt($today)) {
        $selectedDate = $today->copy();
    }

    $dateKey    = $selectedDate->format('Y-m-d');
    $monthKey   = $selectedDate->format('Y-m');
    $monthStart = $selectedDate->copy()->startOfMonth();

    $isToday  = $selectedDate->isSameDay($today);
    $isFuture = $selectedDate->gt($today);

    /* =========================================================
       View mode
       ========================================================= */

    $viewMode = in_array(request('view'), ['day', 'week', 'month'], true)
        ? request('view')
        : 'day';

    if ($viewMode === 'day') {
        $rangeStart = $selectedDate->copy();
        $rangeEnd   = $selectedDate->copy();
        $rangeLabel = $selectedDate->format('l, d F Y');
    } elseif ($viewMode === 'week') {
        $rangeStart = $selectedDate->copy()->startOfWeek();
        $rangeEnd   = $selectedDate->copy()->endOfWeek();
        $rangeLabel = 'Week of ' . $rangeStart->format('d M') . ' – ' . $rangeEnd->format('d M Y');
    } else {
        $rangeStart = $monthStart->copy();
        $rangeEnd   = $selectedDate->copy()->endOfMonth();
        $rangeLabel = $selectedDate->format('F Y');
    }

    /* =========================================================
       Filters and incoming data
       ========================================================= */

    $academicYearValue = (string) request('academic_year', $academicYear ?? '');
    $classValue        = (string) request('class', $class ?? '');
    $sectionValue      = (string) request('section', $section ?? '');

    $studentCollection = collect($students ?? []);
    $attendanceMap     = $attendanceMap ?? [];

    /* =========================================================
       Holidays
       ========================================================= */

    $nationalHolidays = [
        '01-26' => 'Republic Day',
        '08-15' => 'Independence Day',
        '10-02' => 'Gandhi Jayanti',
    ];

    $holidayOf = function (\Carbon\Carbon $date) use ($nationalHolidays) {
        if ($date->isSunday()) {
            return 'Sunday';
        }

        return $nationalHolidays[$date->format('m-d')] ?? null;
    };

    $holidayName = $holidayOf($selectedDate);
    $isLocked    = (bool) $holidayName || $isFuture;

    /* =========================================================
       Route helpers
       ========================================================= */

    $sheetUrl = request()->url();

    $routeIf = fn (string $name, array $params = []) =>
        \Illuminate\Support\Facades\Route::has($name)
            ? route($name, $params)
            : null;

    $storeUrl = $routeIf('admin.attendance.store')
        ?? $routeIf('admin.attendance.student.store')
        ?? $sheetUrl;

    $backUrl = $routeIf('admin.attendance.student', ['month' => $monthKey])
        ?? $routeIf('admin.attendance.index', ['month' => $monthKey])
        ?? url()->previous();

    $queryBase = array_filter([
        'academic_year' => $academicYearValue,
        'class'         => $classValue,
        'section'       => $sectionValue,
        'view'          => $viewMode,
    ], fn ($value) => $value !== '');

    $urlFor = fn (\Carbon\Carbon $date) =>
        $sheetUrl . '?' . http_build_query(
            $queryBase + [
                'date'  => $date->format('Y-m-d'),
                'month' => $date->format('Y-m'),
            ]
        );

    $prevUrl   = $urlFor($selectedDate->copy()->subDay());
    $nextUrl   = $urlFor($selectedDate->copy()->addDay());
    $todayUrl  = $urlFor($today);
    $canGoNext = $selectedDate->lt($today);

    $viewUrl = fn (string $mode) =>
        $sheetUrl . '?' . http_build_query(
            array_merge($queryBase, [
                'view'  => $mode,
                'date'  => $dateKey,
                'month' => $monthKey,
            ])
        );

    /* =========================================================
       Register day columns
       ========================================================= */

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
            'url'     => $urlFor($date),
        ];
    }

    /* =========================================================
       Student attendance rows and totals
       ========================================================= */

    $rows = [];

    $dayPresent = array_fill(1, count($days), 0);
    $dayAbsent  = array_fill(1, count($days), 0);

    $selectedPresent = 0;
    $selectedAbsent  = 0;

    foreach ($studentCollection as $student) {
        $cells = [];
        $basePresent = 0;
        $baseAbsent  = 0;

        foreach ($days as $index => $meta) {
            $status = strtolower(trim($attendanceMap[$student->id][$meta['key']] ?? ''));
            $status = in_array($status, ['present', 'absent'], true)
                ? $status
                : '';

            $cells[$index] = $status;

            if ($status === 'present') {
                $dayPresent[$index]++;
            }

            if ($status === 'absent') {
                $dayAbsent[$index]++;
            }

            if ($meta['selected']) {
                if ($status === 'present') {
                    $selectedPresent++;
                }

                if ($status === 'absent') {
                    $selectedAbsent++;
                }
            } else {
                if ($status === 'present') {
                    $basePresent++;
                }

                if ($status === 'absent') {
                    $baseAbsent++;
                }
            }
        }

        $fullName = trim(preg_replace(
            '/\s+/',
            ' ',
            ($student->first_name ?? '') . ' ' .
            ($student->middle_name ?? '') . ' ' .
            ($student->last_name ?? '')
        ));

        $initials = strtoupper(
            substr($student->first_name ?? 'S', 0, 1) .
            substr($student->last_name ?? '', 0, 1)
        );

        $rows[] = [
            'student'     => $student,
            'name'        => $fullName ?: 'Student',
            'initials'    => $initials ?: 'S',
            'code'        => $student->student_id ?? $student->id,
            'cells'       => $cells,
            'basePresent' => $basePresent,
            'baseAbsent'  => $baseAbsent,
        ];
    }

    $totalStudents = count($rows);
    $selectedMarked = $selectedPresent + $selectedAbsent;

    $selectedPercentage = $selectedMarked > 0
        ? round(($selectedPresent / $selectedMarked) * 100)
        : null;

    $jsConfig = [
        'storeUrl'     => $storeUrl,
        'date'         => $dateKey,
        'month'        => $monthKey,
        'academicYear' => $academicYearValue,
        'class'        => $classValue,
        'section'      => $sectionValue,
        'locked'       => $isLocked,
    ];
@endphp

<style>
    /* =========================================================
       Shared page styles
       ========================================================= */

    .attendance-page{
        max-width:1500px;
        margin:0 auto;
    }

    .paper{
        background:#fff;
        border:1px solid var(--line);
        border-radius:10px;
    }

    .btn-solid,
    .btn-line{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        height:40px;
        padding:0 16px;
        border-radius:8px;
        border:1px solid transparent;
        cursor:pointer;
        font-family:inherit;
        font-size:13px;
        font-weight:500;
        line-height:1;
        text-decoration:none;
        white-space:nowrap;
    }

    .btn-solid{
        color:#fff;
        background:var(--ink);
        border-color:var(--ink);
    }

    .btn-solid:hover{
        color:#fff;
        background:var(--ink-2);
    }

    .btn-line{
        color:var(--text);
        background:#fff;
        border-color:#cfc8b6;
    }

    .btn-line:hover{
        color:var(--ink);
        background:#fffdf6;
        border-color:var(--ink);
    }

    .btn-line.is-disabled{
        opacity:.46;
        pointer-events:none;
    }

    .btn-icon{
        width:40px;
        padding:0;
    }

    .form-control{
        width:100%;
        height:40px;
        padding:0 12px;
        color:var(--text);
        background:#fff;
        border:1px solid #cfc8b6;
        border-radius:8px;
        outline:none;
        font-family:inherit;
        font-size:13.5px;
    }

    .form-control:focus{
        border-color:var(--ink);
        box-shadow:0 0 0 3px rgba(29,58,51,.10);
    }

    .c-blue{--c:#2b5d8a}
    .c-green{--c:#3a9d6b}
    .c-red{--c:#e4572e}
    .c-amber{--c:#d58a0b}
    .c-violet{--c:#7a4fb5}

    /* =========================================================
       Header (Meal Items style)
       ========================================================= */

    .attendance-hero{
        display:flex;
        align-items:stretch;
        overflow:hidden;
        margin-bottom:21px;
        border-radius:14px;
        border:1px solid #e8e2d4;
        background:#fff;
        box-shadow:0 4px 18px rgba(29,58,51,.06);
    }

    .hero-left{
        flex:1 1 auto;
        display:flex;
        align-items:flex-start;
        gap:18px;
        padding:22px 26px 22px 22px;
        position:relative;
        background:#fffdf9;
    }

    .hero-left::before{
        content:"";
        position:absolute;
        top:0;
        bottom:0;
        left:0;
        width:5px;
        background:#2d6a4f;
        border-radius:14px 0 0 14px;
    }

    .hero-icon{
        width:48px;
        height:48px;
        flex-shrink:0;
        display:flex;
        align-items:center;
        justify-content:center;
        background:#f3ebe0;
        border-radius:12px;
        color:#8a6a3d;
        font-size:22px;
    }

    .hero-kicker{
        margin:0 0 6px;
        color:#a67c3d;
        font-size:11px;
        font-weight:700;
        letter-spacing:.6px;
        text-transform:uppercase;
    }

    .attendance-title{
        margin:0 0 6px;
        color:#1a2e28;
        font-family:var(--serif);
        font-size:26px;
        font-weight:700;
        line-height:1.2;
    }

    .attendance-subtitle{
        margin:0;
        color:#6b6560;
        font-size:13.5px;
        line-height:1.45;
        max-width:420px;
    }

    .hero-meta{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-top:14px;
    }

    .meta-chip{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:5px 11px;
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 10%, #fff);
        border-radius:6px;
        font-size:11.5px;
        font-weight:600;
    }

    .meta-chip.year{
        color:#7a4fb5;
        background:color-mix(in srgb, #7a4fb5 10%, #fff);
    }

    /* Right dark panel */
    .hero-right{
        flex:0 0 280px;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
        gap:16px;
        padding:20px 22px;
        background:#1e3a32;
        color:#e8f0ec;
    }

    .hero-right-kicker{
        margin:0 0 4px;
        color:#9ec5b5;
        font-size:11px;
        font-weight:700;
        letter-spacing:.5px;
        text-transform:uppercase;
    }

    .hero-right-text{
        margin:0;
        color:#c5d9d0;
        font-size:13.5px;
        line-height:1.4;
    }

    .hero-actions{
        display:flex;
        flex-direction:column;
        gap:10px;
    }

    .btn-hero-outline{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        height:38px;
        padding:0 14px;
        color:#e8f0ec;
        background:transparent;
        border:1px solid #4a6b62;
        border-radius:8px;
        font-size:13px;
        font-weight:500;
        text-decoration:none;
        transition:all .18s ease;
    }

    .btn-hero-outline:hover{
        background:rgba(255,255,255,.08);
        border-color:#7a9e92;
        color:#fff;
    }

    .btn-hero-solid{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        height:38px;
        padding:0 14px;
        color:#1e3a32;
        background:#e8d5a8;
        border:1px solid #e8d5a8;
        border-radius:8px;
        font-size:13px;
        font-weight:600;
        text-decoration:none;
        transition:all .18s ease;
    }

    .btn-hero-solid:hover{
        background:#f0e0b8;
        color:#1a2e28;
    }

    .month-display{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:9px 13px;
        color:#e8f0ec;
        background:rgba(255,255,255,.08);
        border:1px solid #4a6b62;
        border-radius:8px;
        font-family:var(--serif);
        font-size:14px;
        font-weight:700;
        white-space:nowrap;
    }

    .lock-badge{
        display:inline-flex;
        align-items:center;
        gap:7px;
        padding:8px 12px;
        color:#f0d090;
        background:rgba(213,138,11,.18);
        border:1px solid rgba(213,138,11,.35);
        border-radius:8px;
        font-size:12px;
        font-weight:600;
        white-space:nowrap;
    }

    /* =========================================================
       Summary cards
       ========================================================= */

    .summary-grid{
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr));
        gap:18px;
        margin-bottom:21px;
    }

    .summary-card{
        position:relative;
        display:flex;
        flex-direction:column;
        gap:13px;
        padding:20px 20px 16px;
        margin-top:10px;
        border-top:4px solid var(--c);
        transition:transform .18s ease, box-shadow .18s ease;
    }

    .summary-card:hover{
        transform:translateY(-4px);
        box-shadow:0 13px 24px rgba(29,58,51,.12);
    }

    .summary-tab{
        position:absolute;
        top:-24px;
        left:15px;
        padding:3px 11px;
        color:#fff;
        background:var(--c);
        border-radius:7px 7px 0 0;
        font-size:11.5px;
        font-weight:600;
    }

    .summary-top{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
    }

    .summary-value{
        color:var(--ink);
        font-family:var(--serif);
        font-size:30px;
        font-weight:700;
        line-height:1;
    }

    .summary-icon{
        display:flex;
        align-items:center;
        justify-content:center;
        width:42px;
        height:42px;
        flex-shrink:0;
        color:var(--c);
        background:color-mix(in srgb, var(--c) 14%, #fff);
        border-radius:12px;
        font-size:18px;
    }

    .summary-label{
        padding-top:11px;
        color:var(--muted);
        border-top:1px dashed #d8d1bf;
        font-size:12px;
    }

    /* =========================================================
       One-line attendance toolbar
       ========================================================= */

    .attendance-toolbar{
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:nowrap;
        width:100%;
        min-height:72px;
        padding:12px 14px;
        margin-bottom:16px;
        overflow-x:auto;
        overflow-y:hidden;
        scrollbar-width:thin;
    }

    .attendance-toolbar > *{
        flex-shrink:0;
    }

    .date-controls{
        display:flex;
        align-items:center;
        gap:7px;
        flex:0 0 auto;
        white-space:nowrap;
    }

    .date-controls .btn-line{
        height:38px;
    }

    .date-controls .btn-icon{
        width:38px;
        padding:0;
    }

    .date-input{
        width:145px;
        height:38px;
        padding:0 10px;
        font-size:13px;
    }

    .today-button{
        min-width:67px;
        padding:0 12px !important;
    }

    .view-switch{
        display:inline-flex;
        flex:0 0 auto;
        overflow:hidden;
        border:1px solid #cfc8b6;
        border-radius:8px;
    }

    .view-switch a{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:58px;
        height:38px;
        padding:0 11px;
        color:var(--text);
        background:#fff;
        text-decoration:none;
        font-size:13px;
        font-weight:500;
        white-space:nowrap;
    }

    .view-switch a + a{
        border-left:1px solid #cfc8b6;
    }

    .view-switch a:hover{
        background:#fffdf6;
    }

    .view-switch a.is-active{
        color:#fff;
        background:var(--ink);
    }

    .selected-date-display{
        display:inline-flex;
        align-items:center;
        gap:7px;
        flex:0 0 auto;
        padding:0 2px;
        color:var(--ink);
        font-family:var(--serif);
        font-size:14px;
        font-weight:700;
        line-height:38px;
        white-space:nowrap;
    }

    .date-tag{
        display:inline-flex;
        align-items:center;
        padding:3px 8px;
        border-radius:999px;
        font-family:'Public Sans', sans-serif;
        font-size:10.5px;
        font-weight:600;
        white-space:nowrap;
    }

    .date-tag.today{
        color:#2b7a52;
        background:color-mix(in srgb, #3a9d6b 14%, #fff);
    }

    .date-tag.past{
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 12%, #fff);
    }

    .date-tag.future{
        color:#7a4fb5;
        background:color-mix(in srgb, #7a4fb5 12%, #fff);
    }

    .date-tag.holiday{
        color:#8a5600;
        background:color-mix(in srgb, #d58a0b 15%, #fff);
    }

    .toolbar-actions{
        display:flex;
        align-items:center;
        gap:8px;
        flex:1 1 auto;
        min-width:0;
        margin-left:auto;
        white-space:nowrap;
    }

    .search-wrap{
        position:relative;
        flex:1 1 180px;
        width:auto;
        min-width:165px;
        max-width:250px;
    }

    .search-wrap i{
        position:absolute;
        top:50%;
        left:11px;
        color:var(--muted);
        pointer-events:none;
        transform:translateY(-50%);
    }

    .search-wrap .form-control{
        width:100%;
        height:38px;
        min-width:0;
        padding-right:9px;
        padding-left:34px;
        font-size:13px;
    }

    #markRemaining{
        height:38px;
        padding:0 13px;
        font-size:12.5px;
    }

    /* =========================================================
       Locked date note
       ========================================================= */

    .lock-note{
        display:flex;
        align-items:center;
        gap:10px;
        padding:13px 16px;
        margin-bottom:16px;
        color:#8a5600;
        background:#fff8e8;
        border:1px dashed #d58a0b;
        border-radius:10px;
        font-size:13px;
    }

    /* =========================================================
       Attendance register
       ========================================================= */

    .register-panel{
        overflow:hidden;
    }

    .register-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:16px;
        flex-wrap:wrap;
        padding:15px 20px;
        border-bottom:1px dashed #d8d1bf;
    }

    .register-head h3{
        margin:0;
        color:var(--ink);
        font-family:var(--serif);
        font-size:17px;
        font-weight:700;
    }

    .attendance-legend{
        display:flex;
        gap:13px;
        flex-wrap:wrap;
        color:var(--muted);
        font-size:11.5px;
    }

    .attendance-legend span{
        display:inline-flex;
        align-items:center;
        gap:6px;
    }

    .register-wrap{
        max-height:72vh;
        overflow:auto;
    }

    .attendance-table{
        width:max-content;
        min-width:100%;
        border-collapse:separate;
        border-spacing:0;
    }

    .attendance-table th,
    .attendance-table td{
        padding:0;
        color:var(--text);
        background:#fff;
        border-right:1px solid #f6f2e8;
        border-bottom:1px solid #f0ebe0;
        text-align:center;
        font-size:12.5px;
    }

    .attendance-table thead th{
        position:sticky;
        top:0;
        z-index:3;
        height:52px;
        color:var(--muted);
        background:#f8f6f0;
        border-bottom:1px solid #e6e0d2;
        font-size:10.5px;
        font-weight:700;
        letter-spacing:.3px;
        text-transform:uppercase;
    }

    .attendance-table tbody tr:hover td{
        background:#fffdf6;
    }

    .column-number{
        position:sticky;
        left:0;
        z-index:2;
        width:46px;
        min-width:46px;
        color:var(--muted);
        font-weight:600;
    }

    .column-student{
        position:sticky;
        left:46px;
        z-index:2;
        width:240px;
        min-width:240px;
        padding:10px 14px !important;
        box-shadow:2px 0 0 #e6e0d2;
        text-align:left !important;
    }

    .attendance-table thead .column-number,
    .attendance-table thead .column-student{
        z-index:5;
    }

    .attendance-table tbody tr:hover .column-number,
    .attendance-table tbody tr:hover .column-student{
        background:#fffdf6;
    }

    .student-info{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .student-avatar{
        display:flex;
        align-items:center;
        justify-content:center;
        width:34px;
        height:34px;
        flex-shrink:0;
        overflow:hidden;
        color:#7a4fb5;
        background:color-mix(in srgb, #7a4fb5 14%, #fff);
        border-radius:50%;
        font-size:12px;
        font-weight:700;
    }

    .student-avatar img{
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .student-name{
        max-width:155px;
        overflow:hidden;
        color:var(--ink);
        font-size:13px;
        font-weight:600;
        line-height:1.25;
        text-overflow:ellipsis;
        white-space:nowrap;
    }

    .student-details{
        margin-top:2px;
        color:var(--muted);
        font-size:10.5px;
    }

    .column-day{
        width:46px;
        min-width:46px;
        height:48px;
    }

    .column-day-selected{
        width:90px;
        min-width:90px;
        background:color-mix(in srgb, #2b5d8a 7%, #fff) !important;
        box-shadow:inset 2px 0 0 #2b5d8a, inset -2px 0 0 #2b5d8a;
    }

    .attendance-table thead .column-day-selected{
        color:#fff;
        background:#2b5d8a !important;
    }

    .day-heading{
        display:flex;
        align-items:center;
        justify-content:center;
        flex-direction:column;
        gap:1px;
        width:100%;
        height:100%;
        color:inherit;
        line-height:1.1;
        text-decoration:none;
    }

    .day-heading b{
        color:var(--ink);
        font-family:var(--serif);
        font-size:14px;
    }

    .attendance-table thead .column-day-selected .day-heading b{
        color:#fff;
    }

    .day-heading em{
        font-style:normal;
        font-size:9px;
        opacity:.82;
    }

    .day-heading:hover b{
        text-decoration:underline;
    }

    .attendance-table thead .is-holiday{
        background:#fff8e8;
    }

    .attendance-table thead .is-holiday b{
        color:#8a5600;
    }

    .attendance-table thead .is-future{
        opacity:.55;
    }

    .attendance-table td.is-holiday{
        background:#fffcf3;
    }

    .attendance-table td.is-future{
        background:#fafaf7;
    }

    .status-symbol{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:25px;
        height:25px;
        border-radius:7px;
        font-size:11px;
        font-weight:700;
    }

    .status-present{
        color:#2b7a52;
        background:color-mix(in srgb, #3a9d6b 16%, #fff);
    }

    .status-absent{
        color:#c23d17;
        background:color-mix(in srgb, #e4572e 16%, #fff);
    }

    .status-holiday{
        color:#8a5600;
        background:color-mix(in srgb, #d58a0b 16%, #fff);
    }

    .status-empty{
        color:#c9c2ae;
    }

    .column-total{
        width:66px;
        min-width:66px;
        font-weight:700;
    }

    .total-present{
        color:#2b7a52;
    }

    .total-absent{
        color:#c23d17;
    }

    .total-percentage{
        color:var(--ink);
    }

    .attendance-table tfoot td{
        position:sticky;
        bottom:0;
        z-index:3;
        height:38px;
        background:#f8f6f0;
        border-top:1px solid #e6e0d2;
        font-size:11px;
        font-weight:700;
    }

    .attendance-table tfoot tr:nth-child(1) td{
        bottom:76px;
    }

    .attendance-table tfoot tr:nth-child(2) td{
        bottom:38px;
    }

    .attendance-table tfoot tr:nth-child(3) td{
        bottom:0;
    }

    .attendance-table tfoot .column-number,
    .attendance-table tfoot .column-student{
        z-index:5;
    }

    .footer-label{
        color:var(--muted);
        font-size:10.5px;
        letter-spacing:.35px;
        text-align:left !important;
        text-transform:uppercase;
    }

    .footer-present{
        color:#2b7a52;
    }

    .footer-absent{
        color:#c23d17;
    }

    .footer-percentage{
        color:var(--ink);
    }

    /* =========================================================
       Compact Present / Absent toggle
       ========================================================= */

    .attendance-toggle{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:5px;
        min-width:66px;
        height:30px;
        padding:3px 5px;
        color:#c23d17;
        background:#fff8f6;
        border:1px solid #f0c5ba;
        border-radius:999px;
        cursor:pointer;
        font-family:inherit;
        transition:background .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .attendance-toggle:hover{
        box-shadow:0 2px 8px rgba(29,58,51,.12);
    }

    .attendance-toggle:focus-visible{
        outline:3px solid rgba(43,93,138,.22);
        outline-offset:2px;
    }

    .attendance-toggle__label{
        width:10px;
        color:currentColor;
        font-size:10px;
        font-weight:800;
        line-height:1;
        opacity:.5;
    }

    .attendance-toggle__track{
        position:relative;
        width:28px;
        height:16px;
        background:#e9b5a8;
        border-radius:999px;
        transition:background .18s ease;
    }

    .attendance-toggle__thumb{
        position:absolute;
        top:2px;
        left:2px;
        display:flex;
        align-items:center;
        justify-content:center;
        width:12px;
        height:12px;
        color:#c23d17;
        background:#fff;
        border-radius:50%;
        box-shadow:0 1px 3px rgba(0,0,0,.20);
        font-size:7px;
        transition:left .18s ease, color .18s ease;
    }

    .attendance-toggle.is-present{
        color:#2b7a52;
        background:#f5fcf7;
        border-color:#a7d7bb;
    }

    .attendance-toggle.is-present .attendance-toggle__track{
        background:#3a9d6b;
    }

    .attendance-toggle.is-present .attendance-toggle__thumb{
        left:14px;
        color:#2b7a52;
    }

    .attendance-toggle.is-present .attendance-toggle__label--present,
    .attendance-toggle.is-absent .attendance-toggle__label--absent{
        opacity:1;
    }

    .cell-sync{
        display:block;
        min-height:10px;
        margin-top:2px;
        color:var(--muted);
        font-size:9px;
        line-height:1;
    }

    .cell-sync.is-saving{
        color:#2b5d8a;
    }

    .cell-sync.is-saved{
        color:#2b7a52;
    }

    .cell-sync.is-error{
        color:#c23d17;
    }

    .spin{
        display:inline-block;
        width:10px;
        height:10px;
        border:2px solid currentColor;
        border-right-color:transparent;
        border-radius:50%;
        animation:attendance-spin .7s linear infinite;
    }

    @keyframes attendance-spin{
        to{
            transform:rotate(360deg);
        }
    }

    .no-match{
        display:none;
        padding:42px 16px;
        color:var(--muted);
        text-align:center;
    }

    .empty-state{
        padding:60px 20px;
        text-align:center;
    }

    .empty-icon{
        display:flex;
        align-items:center;
        justify-content:center;
        width:60px;
        height:60px;
        margin:0 auto 14px;
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 12%, #fff);
        border-radius:50%;
        font-size:25px;
    }

    .empty-state h5{
        margin:0 0 5px;
        color:var(--ink);
        font-family:var(--serif);
        font-size:18px;
        font-weight:700;
    }

    .empty-state p{
        margin:0 0 18px;
        color:var(--muted);
        font-size:13px;
    }

    .toast{
        position:fixed;
        right:22px;
        bottom:22px;
        z-index:2000;
        max-width:350px;
        padding:12px 17px;
        color:#fff;
        background:var(--ink);
        border-radius:10px;
        box-shadow:0 12px 28px rgba(29,58,51,.28);
        font-size:13px;
        opacity:0;
        pointer-events:none;
        transform:translateY(10px);
        transition:opacity .2s ease, transform .2s ease;
    }

    .toast.is-show{
        opacity:1;
        transform:translateY(0);
    }

    .toast.is-error{
        background:#c23d17;
    }

    /* =========================================================
       Responsive layout
       ========================================================= */

    @media (max-width:1200px){
        .summary-grid{
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .selected-date-display{
            font-size:13px;
        }

        .search-wrap{
            min-width:150px;
            max-width:200px;
        }

        #markRemaining{
            padding:0 11px;
            font-size:12px;
        }
    }

    @media (max-width:900px){
        .attendance-hero{
            flex-direction:column;
        }

        .hero-right{
            flex:none;
            width:100%;
        }

        .hero-actions{
            flex-direction:row;
            flex-wrap:wrap;
        }

        .attendance-toolbar{
            align-items:flex-start;
            flex-wrap:wrap;
            min-height:0;
            overflow-x:visible;
        }

        .toolbar-actions{
            width:100%;
            flex:1 0 100%;
            margin-left:0;
        }

        .search-wrap{
            max-width:none;
        }
    }

    @media (max-width:760px){
        .hero-left{
            padding:18px 16px 18px 18px;
            gap:14px;
        }

        .attendance-title{
            font-size:22px;
        }

        .hero-icon{
            width:42px;
            height:42px;
            font-size:19px;
        }

        .column-student{
            width:175px;
            min-width:175px;
        }

        .student-name{
            max-width:94px;
        }
    }

    @media (max-width:520px){
        .summary-grid{
            grid-template-columns:1fr;
        }

        .date-controls{
            width:100%;
            overflow-x:auto;
            padding-bottom:2px;
        }

        .selected-date-display{
            width:100%;
        }

        .toolbar-actions{
            flex-wrap:wrap;
        }

        .search-wrap,
        #markRemaining{
            width:100%;
            max-width:none;
            flex-basis:100%;
        }

        #markRemaining{
            height:40px;
        }
    }

    @media (prefers-reduced-motion:reduce){
        .summary-card,
        .attendance-toggle,
        .attendance-toggle__track,
        .attendance-toggle__thumb{
            transition:none;
        }

        .spin{
            animation:none;
        }
    }
</style>

<div class="attendance-page">

    {{-- =========================================================
       Page header (Meal Items style)
       ========================================================= --}}
    <section class="paper attendance-hero">
        <div class="hero-left">
            <div class="hero-icon">
                <i class="bi bi-calendar2-check"></i>
            </div>

            <div>
                <div class="hero-kicker">ATTENDANCE / REGISTER</div>
                <h1 class="attendance-title">Mark Attendance</h1>
                <p class="attendance-subtitle">
                    Review and update attendance for the selected class and date.
                </p>

                <div class="hero-meta">
                    @if($classValue !== '')
                        <span class="meta-chip">
                            <i class="bi bi-mortarboard-fill"></i>
                            Class {{ $classValue }}
                        </span>
                    @endif

                    @if($sectionValue !== '')
                        <span class="meta-chip">
                            <i class="bi bi-diagram-3-fill"></i>
                            Section {{ $sectionValue }}
                        </span>
                    @endif

                    @if($academicYearValue !== '')
                        <span class="meta-chip year">
                            <i class="bi bi-calendar3"></i>
                            {{ $academicYearValue }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="hero-right">
            <div>
                <div class="hero-right-kicker">SCHOOL DIARY</div>
                <p class="hero-right-text">
                    Managing daily attendance for the class
                </p>
            </div>

            <div class="hero-actions">
                <a href="{{ $backUrl }}" class="btn-hero-outline">
                    <i class="bi bi-arrow-left"></i>
                    All classes
                </a>

                <div class="month-display">
                    <i class="bi bi-calendar3"></i>
                    {{ $selectedDate->format('F Y') }}
                </div>

                @if($isLocked)
                    <span class="lock-badge">
                        <i class="bi bi-lock-fill"></i>
                        Attendance locked
                    </span>
                @endif
            </div>
        </div>
    </section>

    {{-- =========================================================
       Attendance summary
       ========================================================= --}}
    <section class="summary-grid">
        <article class="paper summary-card c-blue">
            <span class="summary-tab">Students</span>

            <div class="summary-top">
                <div class="summary-value" id="sumTotal">{{ $totalStudents }}</div>

                <div class="summary-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>

            <div class="summary-label">Students in this class</div>
        </article>

        <article class="paper summary-card c-green">
            <span class="summary-tab">Present</span>

            <div class="summary-top">
                <div class="summary-value" id="sumPresent">{{ $selectedPresent }}</div>

                <div class="summary-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>

            <div class="summary-label">
                Present on {{ $selectedDate->format('d M') }}
            </div>
        </article>

        <article class="paper summary-card c-red">
            <span class="summary-tab">Absent</span>

            <div class="summary-top">
                <div class="summary-value" id="sumAbsent">{{ $selectedAbsent }}</div>

                <div class="summary-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>

            <div class="summary-label">
                Absent on {{ $selectedDate->format('d M') }}
            </div>
        </article>

        <article class="paper summary-card c-amber">
            <span class="summary-tab">Attendance</span>

            <div class="summary-top">
                <div class="summary-value" id="sumPercentage">
                    {{ $selectedPercentage === null ? '–' : $selectedPercentage . '%' }}
                </div>

                <div class="summary-icon">
                    <i class="bi bi-percent"></i>
                </div>
            </div>

            <div class="summary-label">Attendance rate for this day</div>
        </article>
    </section>

    {{-- =========================================================
       Compact one-line attendance toolbar
       ========================================================= --}}
    <form method="GET" action="{{ $sheetUrl }}" id="dateForm" class="paper attendance-toolbar">
        <input type="hidden" name="academic_year" value="{{ $academicYearValue }}">
        <input type="hidden" name="class" value="{{ $classValue }}">
        <input type="hidden" name="section" value="{{ $sectionValue }}">
        <input type="hidden" name="view" value="{{ $viewMode }}">
        <input type="hidden" name="month" id="monthInput" value="{{ $monthKey }}">

        <div class="date-controls">
            <a
                href="{{ $prevUrl }}"
                class="btn-line btn-icon"
                title="Previous day"
                aria-label="Previous day"
            >
                <i class="bi bi-chevron-left"></i>
            </a>

            <input
                type="date"
                name="date"
                id="dateInput"
                class="form-control date-input"
                value="{{ $dateKey }}"
                max="{{ $today->format('Y-m-d') }}"
                aria-label="Attendance date"
            >

            <a
                href="{{ $canGoNext ? $nextUrl : '#' }}"
                class="btn-line btn-icon {{ $canGoNext ? '' : 'is-disabled' }}"
                title="Next day"
                aria-label="Next day"
                @if(!$canGoNext) aria-disabled="true" tabindex="-1" @endif
            >
                <i class="bi bi-chevron-right"></i>
            </a>

            <a
                href="{{ $todayUrl }}"
                class="btn-line today-button {{ $isToday ? 'is-disabled' : '' }}"
            >
                Today
            </a>
        </div>

        <nav class="view-switch" aria-label="Attendance register view">
            <a href="{{ $viewUrl('day') }}" class="{{ $viewMode === 'day' ? 'is-active' : '' }}">
                Day
            </a>

            <a href="{{ $viewUrl('week') }}" class="{{ $viewMode === 'week' ? 'is-active' : '' }}">
                Week
            </a>

            <a href="{{ $viewUrl('month') }}" class="{{ $viewMode === 'month' ? 'is-active' : '' }}">
                Month
            </a>
        </nav>

        <div class="selected-date-display" aria-label="Selected attendance date">
            <span>{{ $selectedDate->format('D, d M Y') }}</span>

            @if($isToday)
                <span class="date-tag today">Today</span>
            @elseif($isFuture)
                <span class="date-tag future">Upcoming</span>
            @else
                <span class="date-tag past">Past</span>
            @endif

            @if($holidayName)
                <span class="date-tag holiday">{{ $holidayName }}</span>
            @endif
        </div>

        @if($totalStudents > 0)
            <div class="toolbar-actions">
                <div class="search-wrap">
                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        id="studentSearch"
                        class="form-control"
                        placeholder="Search name, ID or roll no."
                        aria-label="Search students"
                    >
                </div>

                @unless($isLocked)
                    <button
                        type="button"
                        class="btn-solid"
                        id="markRemaining"
                        title="Mark every visible student without attendance as present"
                    >
                        <i class="bi bi-check2-all"></i>
                        <span>Mark remaining present</span>
                    </button>
                @endunless
            </div>
        @endif
    </form>

    {{-- =========================================================
       Locked state note
       ========================================================= --}}
    @if($isLocked)
        <div class="lock-note">
            <i class="bi bi-info-circle-fill"></i>

            @if($holidayName)
                {{ $selectedDate->format('d M Y') }} is {{ $holidayName }}. Attendance cannot be marked for this date.
            @else
                Attendance cannot be marked for a future date.
            @endif
        </div>
    @endif

    {{-- =========================================================
       Attendance register
       ========================================================= --}}
    <section class="paper register-panel">
        <div class="register-head">
            <h3>Attendance Register · {{ $rangeLabel }}</h3>

            <div class="attendance-legend">
                <span><span class="status-symbol status-present">P</span> Present</span>
                <span><span class="status-symbol status-absent">A</span> Absent</span>
                <span><span class="status-symbol status-holiday">H</span> Holiday</span>
                <span><span class="status-symbol status-empty">–</span> Not marked</span>
            </div>
        </div>

        @if($totalStudents > 0)
            <div class="register-wrap" id="registerWrap">
                <table class="attendance-table" id="attendanceTable">
                    <thead>
                        <tr>
                            <th class="column-number">#</th>
                            <th class="column-student">Student</th>

                            @foreach($days as $index => $meta)
                                <th
                                    class="column-day
                                        {{ $meta['selected'] ? 'column-day-selected' : '' }}
                                        {{ $meta['holiday'] ? 'is-holiday' : '' }}
                                        {{ $meta['future'] ? 'is-future' : '' }}"
                                    @if($meta['selected']) id="selectedDayHead" @endif
                                >
                                    @if($meta['future'])
                                        <span class="day-heading">
                                            <b>{{ $meta['number'] }}</b>
                                            <em>{{ $meta['date']->format('D') }}</em>
                                        </span>
                                    @else
                                        <a
                                            href="{{ $meta['url'] }}"
                                            class="day-heading"
                                            title="Open attendance for {{ $meta['date']->format('d M Y') }}"
                                        >
                                            <b>{{ $meta['number'] }}</b>
                                            <em>{{ $meta['date']->format('D') }}</em>
                                        </a>
                                    @endif
                                </th>
                            @endforeach

                            <th class="column-total">Present</th>
                            <th class="column-total">Absent</th>
                            <th class="column-total">%</th>
                        </tr>
                    </thead>

                    <tbody id="attendanceBody">
                        @foreach($rows as $rowNumber => $row)
                            @php
                                $student = $row['student'];
                                $selectedStatus = '';

                                foreach ($days as $dayIndex => $meta) {
                                    if ($meta['selected']) {
                                        $selectedStatus = $row['cells'][$dayIndex] ?? '';
                                        break;
                                    }
                                }

                                $totalPresent = $row['basePresent'] + ($selectedStatus === 'present' ? 1 : 0);
                                $totalAbsent  = $row['baseAbsent'] + ($selectedStatus === 'absent' ? 1 : 0);
                                $studentMarked = $totalPresent + $totalAbsent;

                                $studentPercentage = $studentMarked > 0
                                    ? round(($totalPresent / $studentMarked) * 100)
                                    : null;
                            @endphp

                            <tr
                                data-student-id="{{ $student->id }}"
                                data-base-present="{{ $row['basePresent'] }}"
                                data-base-absent="{{ $row['baseAbsent'] }}"
                                data-search="{{ strtolower($row['name'] . ' ' . $row['code'] . ' ' . ($student->roll_number ?? '')) }}"
                            >
                                <td class="column-number">{{ $rowNumber + 1 }}</td>

                                <td class="column-student">
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            @if(!empty($student->profile_image))
                                                <img src="{{ asset('storage/' . $student->profile_image) }}" alt="">
                                            @else
                                                {{ $row['initials'] }}
                                            @endif
                                        </div>

                                        <div style="min-width:0">
                                            <div class="student-name" title="{{ $row['name'] }}">
                                                {{ $row['name'] }}
                                            </div>

                                            <div class="student-details">
                                                @if(!empty($student->roll_number))
                                                    Roll {{ $student->roll_number }} ·
                                                @endif

                                                ID {{ $row['code'] }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                @foreach($days as $dayIndex => $meta)
                                    @php
                                        $status = $row['cells'][$dayIndex] ?? '';
                                    @endphp

                                    @if($meta['selected'] && !$isLocked)
                                        <td
                                            class="column-day column-day-selected selected-attendance-cell"
                                            data-status="{{ $status }}"
                                            data-saved="{{ $status }}"
                                        >
                                            <button
                                                type="button"
                                                class="attendance-toggle {{ $status === 'present' ? 'is-present' : 'is-absent' }}"
                                                role="switch"
                                                aria-checked="{{ $status === 'present' ? 'true' : 'false' }}"
                                                aria-label="Attendance for {{ $row['name'] }} on {{ $selectedDate->format('d M Y') }}"
                                                title="{{ $status === 'present' ? 'Present — click to mark absent' : 'Absent — click to mark present' }}"
                                            >
                                                <span class="attendance-toggle__label attendance-toggle__label--absent">A</span>

                                                <span class="attendance-toggle__track">
                                                    <span class="attendance-toggle__thumb">
                                                        <i class="bi {{ $status === 'present' ? 'bi-check-lg' : 'bi-x-lg' }}"></i>
                                                    </span>
                                                </span>

                                                <span class="attendance-toggle__label attendance-toggle__label--present">P</span>
                                            </button>

                                            <span class="cell-sync" aria-live="polite"></span>
                                        </td>
                                    @else
                                        <td
                                            class="column-day
                                                {{ $meta['selected'] ? 'column-day-selected' : '' }}
                                                {{ $meta['holiday'] ? 'is-holiday' : '' }}
                                                {{ $meta['future'] ? 'is-future' : '' }}"
                                        >
                                            @if($status === 'present')
                                                <span class="status-symbol status-present">P</span>
                                            @elseif($status === 'absent')
                                                <span class="status-symbol status-absent">A</span>
                                            @elseif($meta['holiday'])
                                                <span class="status-symbol status-holiday">H</span>
                                            @elseif(!$meta['future'])
                                                <span class="status-symbol status-empty">–</span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td class="column-total total-present">{{ $totalPresent }}</td>
                                <td class="column-total total-absent">{{ $totalAbsent }}</td>
                                <td class="column-total total-percentage">
                                    {{ $studentPercentage === null ? '–' : $studentPercentage . '%' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td class="column-number"></td>
                            <td class="column-student footer-label">Present per day</td>

                            @foreach($days as $index => $meta)
                                <td
                                    class="column-day {{ $meta['selected'] ? 'column-day-selected' : '' }} footer-present"
                                    @if($meta['selected']) id="footerPresent" @endif
                                >
                                    {{ $meta['future'] || $meta['holiday'] ? '' : $dayPresent[$index] }}
                                </td>
                            @endforeach

                            <td colspan="3"></td>
                        </tr>

                        <tr>
                            <td class="column-number"></td>
                            <td class="column-student footer-label">Absent per day</td>

                            @foreach($days as $index => $meta)
                                <td
                                    class="column-day {{ $meta['selected'] ? 'column-day-selected' : '' }} footer-absent"
                                    @if($meta['selected']) id="footerAbsent" @endif
                                >
                                    {{ $meta['future'] || $meta['holiday'] ? '' : $dayAbsent[$index] }}
                                </td>
                            @endforeach

                            <td colspan="3"></td>
                        </tr>

                        <tr>
                            <td class="column-number"></td>
                            <td class="column-student footer-label">Attendance rate</td>

                            @foreach($days as $index => $meta)
                                @php
                                    $dayMarked = $dayPresent[$index] + $dayAbsent[$index];
                                @endphp

                                <td
                                    class="column-day {{ $meta['selected'] ? 'column-day-selected' : '' }} footer-percentage"
                                    @if($meta['selected']) id="footerPercentage" @endif
                                >
                                    {{
                                        ($meta['future'] || $meta['holiday'] || $dayMarked === 0)
                                            ? ''
                                            : round(($dayPresent[$index] / $dayMarked) * 100) . '%'
                                    }}
                                </td>
                            @endforeach

                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="no-match" id="noMatch">
                No students match your search.
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-people"></i>
                </div>

                <h5>No students found</h5>
                <p>There are no active students in this class and section.</p>

                <a href="{{ $backUrl }}" class="btn-solid">
                    <i class="bi bi-arrow-left"></i>
                    Back to classes
                </a>
            </div>
        @endif
    </section>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

@push('scripts')
<script>
(function () {
    'use strict';

    var CFG = @json($jsConfig);

    var dateForm = document.getElementById('dateForm');
    var dateInput = document.getElementById('dateInput');
    var monthInput = document.getElementById('monthInput');

    var attendanceBody = document.getElementById('attendanceBody');
    var toastElement = document.getElementById('toast');

    var csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfTokenElement ? csrfTokenElement.content : '';

    if (dateInput && dateForm && monthInput) {
        dateInput.addEventListener('change', function () {
            if (!dateInput.value) {
                return;
            }

            monthInput.value = dateInput.value.slice(0, 7);
            dateForm.submit();
        });
    }

    if (!attendanceBody) {
        return;
    }

    var rows = Array.prototype.slice.call(
        attendanceBody.querySelectorAll('tr')
    );

    var pendingRequests = 0;
    var activeRequests = 0;
    var waitingRequests = [];

    var MAX_PARALLEL_REQUESTS = 4;

    function byId(id) {
        return document.getElementById(id);
    }

    function showToast(message, isError) {
        if (!toastElement) {
            return;
        }

        toastElement.textContent = message;
        toastElement.classList.toggle('is-error', !!isError);
        toastElement.classList.add('is-show');

        clearTimeout(showToast.timeout);

        showToast.timeout = setTimeout(function () {
            toastElement.classList.remove('is-show');
        }, 4200);
    }

    function centreSelectedDay() {
        var registerWrap = byId('registerWrap');
        var selectedDayHead = byId('selectedDayHead');

        if (!registerWrap || !selectedDayHead) {
            return;
        }

        registerWrap.scrollLeft = Math.max(
            0,
            selectedDayHead.offsetLeft -
            ((registerWrap.clientWidth - selectedDayHead.offsetWidth) / 2)
        );
    }

    function setSyncMessage(cell, state, message) {
        var syncElement = cell.querySelector('.cell-sync');

        if (!syncElement) {
            return;
        }

        syncElement.className = 'cell-sync' + (state ? ' is-' + state : '');

        if (state === 'saving') {
            syncElement.innerHTML = '<span class="spin"></span>';
        } else if (state === 'saved') {
            syncElement.textContent = 'Saved';
        } else if (state === 'error') {
            syncElement.textContent = message || 'Not saved';
        } else {
            syncElement.textContent = '';
        }

        clearTimeout(cell.syncTimer);

        if (state === 'saved') {
            cell.syncTimer = setTimeout(function () {
                setSyncMessage(cell, '', '');
            }, 1600);
        }
    }

    function refreshStudentTotal(row) {
        var cell = row.querySelector('.selected-attendance-cell');

        if (!cell) {
            return;
        }

        var status = cell.dataset.status || '';

        var basePresent = parseInt(row.dataset.basePresent, 10) || 0;
        var baseAbsent = parseInt(row.dataset.baseAbsent, 10) || 0;

        var present = basePresent + (status === 'present' ? 1 : 0);
        var absent = baseAbsent + (status === 'absent' ? 1 : 0);

        var marked = present + absent;
        var percentage = marked
            ? Math.round((present / marked) * 100) + '%'
            : '–';

        row.querySelector('.total-present').textContent = present;
        row.querySelector('.total-absent').textContent = absent;
        row.querySelector('.total-percentage').textContent = percentage;
    }

    function refreshAttendanceSummary() {
        var present = 0;
        var absent = 0;

        rows.forEach(function (row) {
            var cell = row.querySelector('.selected-attendance-cell');

            if (!cell) {
                return;
            }

            if (cell.dataset.status === 'present') {
                present++;
            }

            if (cell.dataset.status === 'absent') {
                absent++;
            }
        });

        var marked = present + absent;
        var percentage = marked
            ? Math.round((present / marked) * 100) + '%'
            : '–';

        byId('sumPresent').textContent = present;
        byId('sumAbsent').textContent = absent;
        byId('sumPercentage').textContent = percentage;

        var footerPresent = byId('footerPresent');
        var footerAbsent = byId('footerAbsent');
        var footerPercentage = byId('footerPercentage');

        if (footerPresent) {
            footerPresent.textContent = present;
        }

        if (footerAbsent) {
            footerAbsent.textContent = absent;
        }

        if (footerPercentage) {
            footerPercentage.textContent = marked ? percentage : '';
        }
    }

    function renderToggle(cell, status) {
        var toggle = cell.querySelector('.attendance-toggle');

        if (!toggle) {
            return;
        }

        var isPresent = status === 'present';

        toggle.classList.toggle('is-present', isPresent);
        toggle.classList.toggle('is-absent', !isPresent);

        toggle.setAttribute('aria-checked', isPresent ? 'true' : 'false');

        toggle.setAttribute(
            'title',
            isPresent
                ? 'Present — click to mark absent'
                : 'Absent — click to mark present'
        );

        var icon = toggle.querySelector('.attendance-toggle__thumb i');

        if (icon) {
            icon.className = 'bi ' + (isPresent ? 'bi-check-lg' : 'bi-x-lg');
        }
    }

    function setAttendanceStatus(cell, status) {
        cell.dataset.status = status;

        renderToggle(cell, status);
        refreshStudentTotal(cell.closest('tr'));
        refreshAttendanceSummary();
    }

    function acquireRequestSlot() {
        return new Promise(function (resolve) {
            if (activeRequests < MAX_PARALLEL_REQUESTS) {
                activeRequests++;
                resolve();
                return;
            }

            waitingRequests.push(resolve);
        });
    }

    function releaseRequestSlot() {
        activeRequests--;

        var next = waitingRequests.shift();

        if (next) {
            activeRequests++;
            next();
        }
    }

    function saveAttendance(studentId, status) {
        var formData = new FormData();

        formData.append('_token', csrfToken);

        formData.append(
            'attendance[' + studentId + '][' + CFG.date + ']',
            status
        );

        formData.append('attendance_month', CFG.month);
        formData.append('academic_year', CFG.academicYear);
        formData.append('class', CFG.class);
        formData.append('section', CFG.section);

        return fetch(CFG.storeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).then(function (response) {
            return response.json().catch(function () {
                return {};
            }).then(function (data) {
                if (response.ok && data.success !== false) {
                    return;
                }

                var validationError = data.errors
                    ? Object.keys(data.errors).map(function (key) {
                        return data.errors[key][0];
                    })[0]
                    : null;

                var message = validationError || data.message;

                if (!message) {
                    if (response.status === 419) {
                        message = 'Your session has expired. Refresh the page and try again.';
                    } else if (response.status === 404 || response.status === 405) {
                        message = 'Attendance save route was not found.';
                    } else {
                        message = 'Attendance could not be saved. Please try again.';
                    }
                }

                throw new Error(message);
            });
        });
    }

    function queueAttendanceSave(cell) {
        var row = cell.closest('tr');

        pendingRequests++;
        setSyncMessage(cell, 'saving');

        cell.saveChain = (cell.saveChain || Promise.resolve())
            .then(function () {
                var requestedStatus = cell.dataset.status;

                if (requestedStatus === cell.dataset.saved) {
                    return;
                }

                return acquireRequestSlot()
                    .then(function () {
                        return saveAttendance(
                            row.dataset.studentId,
                            requestedStatus
                        )
                        .then(function () {
                            cell.dataset.saved = requestedStatus;
                        })
                        .catch(function (error) {
                            var lastSavedStatus = cell.dataset.saved || '';

                            setAttendanceStatus(cell, lastSavedStatus);
                            setSyncMessage(cell, 'error', 'Not saved');

                            showToast(
                                error.message || 'Unable to save attendance.',
                                true
                            );

                            throw error;
                        })
                        .then(
                            function () {
                                releaseRequestSlot();
                            },
                            function (error) {
                                releaseRequestSlot();
                                throw error;
                            }
                        );
                    });
            })
            .then(
                function () {
                    if (
                        cell.dataset.status === cell.dataset.saved &&
                        cell.querySelector('.cell-sync').classList.contains('is-saving')
                    ) {
                        setSyncMessage(cell, 'saved');
                    }
                },
                function () {
                    /* Error notification has already been shown. */
                }
            )
            .then(function () {
                pendingRequests--;
            });
    }

    function setupSearch() {
        var searchInput = byId('studentSearch');
        var noMatch = byId('noMatch');

        if (!searchInput) {
            return;
        }

        searchInput.addEventListener('input', function () {
            var query = this.value.trim().toLowerCase();
            var visibleCount = 0;

            rows.forEach(function (row) {
                var searchableText = row.dataset.search || '';
                var visible = !query || searchableText.indexOf(query) !== -1;

                row.style.display = visible ? '' : 'none';

                if (visible) {
                    visibleCount++;
                }
            });

            if (noMatch) {
                noMatch.style.display = visibleCount ? 'none' : 'block';
            }
        });
    }

    function setupAttendanceToggles() {
        attendanceBody.addEventListener('click', function (event) {
            if (CFG.locked) {
                return;
            }

            var toggle = event.target.closest('.attendance-toggle');

            if (!toggle) {
                return;
            }

            var cell = toggle.closest('.selected-attendance-cell');

            if (!cell) {
                return;
            }

            var nextStatus = cell.dataset.status === 'present'
                ? 'absent'
                : 'present';

            setAttendanceStatus(cell, nextStatus);
            queueAttendanceSave(cell);
        });
    }

    function setupMarkRemainingPresent() {
        var markRemainingButton = byId('markRemaining');

        if (!markRemainingButton) {
            return;
        }

        markRemainingButton.addEventListener('click', function () {
            var targetRows = rows.filter(function (row) {
                var cell = row.querySelector('.selected-attendance-cell');

                return cell &&
                    !cell.dataset.status &&
                    row.style.display !== 'none';
            });

            if (!targetRows.length) {
                showToast('Every visible student already has an attendance status.');
                return;
            }

            var studentLabel = targetRows.length +
                ' student' +
                (targetRows.length === 1 ? '' : 's');

            if (!window.confirm('Mark ' + studentLabel + ' as present?')) {
                return;
            }

            targetRows.forEach(function (row) {
                var cell = row.querySelector('.selected-attendance-cell');

                setAttendanceStatus(cell, 'present');
                queueAttendanceSave(cell);
            });

            showToast(studentLabel + ' marked present.');
        });
    }

    window.addEventListener('beforeunload', function (event) {
        if (pendingRequests > 0) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    centreSelectedDay();
    rows.forEach(refreshStudentTotal);
    refreshAttendanceSummary();

    setupSearch();
    setupAttendanceToggles();
    setupMarkRemainingPresent();
})();
</script>
@endpush

@endsection