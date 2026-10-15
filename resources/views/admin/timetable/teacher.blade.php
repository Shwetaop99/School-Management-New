@extends('layouts.app')

@section('title', 'Teacher Timetable')
@section('page-title', 'Teacher Timetable')

@section('content')

<style>
    /* Scoped to .tt so other pages are unaffected. Uses the same theme
       variables as the dashboard (--ink, --line, --serif, --hand, ...). */
    .tt{max-width:1500px;margin:0 auto}
    .tt .paper{background:#fff;border:1px solid var(--line);border-radius:10px}
    .tt *,.tt *::before,.tt *::after{box-sizing:border-box}

    /* ---------- Hero: diary page + live bell ---------- */
    .tt .hero{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(0,1fr);margin-bottom:22px;overflow:hidden;position:relative}
    .tt .hero::before{content:"";position:absolute;left:34px;top:0;bottom:0;width:2px;background:rgba(228,87,46,.35)}
    .tt .hero-left{padding:26px 28px 26px 56px}
    .tt .hero-left h1{font-family:var(--serif);font-size:28px;font-weight:700;color:var(--ink);margin-bottom:6px}
    .tt .hero-left p{color:var(--muted);margin-bottom:12px}
    .tt .thought{font-family:var(--hand);font-size:24px;line-height:1.15;color:var(--accent)}

    .tt .bell{background:var(--ink);color:#e9efe2;padding:26px 28px;display:flex;flex-direction:column;justify-content:center;gap:10px;
        background-image:radial-gradient(ellipse at 20% 0,rgba(255,255,255,.09),transparent 60%)}
    .tt .bell-label{font-family:var(--hand);font-size:22px;color:var(--chalk);line-height:1}
    .tt .bell-period{font-family:var(--serif);font-size:24px;font-weight:700;color:#fff;line-height:1.15}
    .tt .bell-sub{font-size:13px;color:#c5d0bd;min-height:18px}
    .tt .bell-track{height:10px;border-radius:6px;background:rgba(255,255,255,.14);overflow:hidden;margin-top:4px}
    .tt .bell-fill{height:100%;width:0;border-radius:6px;background:repeating-linear-gradient(45deg,var(--chalk) 0 8px,#e0a21f 8px 16px);transition:width .6s ease}
    .tt .bell-times{display:flex;justify-content:space-between;font-size:12px;color:#aebba6}

    /* ---------- Panels ---------- */
    .tt .panel{overflow:hidden;margin-bottom:22px}
    .tt .panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;padding:15px 22px;border-bottom:1px dashed #d8d1bf}
    .tt .panel-head h3{font-family:var(--serif);font-size:17px;font-weight:700;color:var(--ink);margin:0}
    .tt .panel-head span{font-size:12.5px;color:var(--muted)}
    .tt .panel-body{padding:22px}

    .tt .btn-solid,.tt .btn-line{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:9px 16px;border-radius:8px;font-size:13.5px;font-weight:500;cursor:pointer;text-decoration:none;font-family:inherit}
    .tt .btn-solid{background:var(--ink);color:#fff;border:1px solid var(--ink)}
    .tt .btn-solid:hover{background:var(--ink-2);color:#fff}
    .tt .btn-line{background:#fff;border:1px solid #cfc8b6;color:var(--text)}
    .tt .btn-line:hover{border-color:var(--ink);color:var(--ink)}

    /* Teacher select */
    .tt .select-row{display:flex;align-items:flex-end;gap:14px}
    .tt .select-wrap{flex:1;min-width:0}
    .tt .select-wrap label{display:block;margin-bottom:8px;font-size:12.5px;font-weight:600;color:var(--ink)}
    .tt .teacher-select{display:block;width:100%;height:44px;padding:0 14px;border:1.5px dashed #cfc8b6;border-radius:10px;background:#fffdf6;color:var(--text);font-size:14px;outline:none;transition:border-color .15s,background .15s}
    .tt .teacher-select:focus{border-style:solid;border-color:var(--ink);background:#fff}

    /* Teacher folder card */
    .tt .folder{position:relative;margin-top:24px;margin-bottom:22px;padding:22px;border-top:4px solid var(--c);display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap}
    .tt .folder-tab{position:absolute;top:-24px;left:16px;padding:3px 12px;background:var(--c);color:#fff;font-size:12px;font-weight:600;border-radius:7px 7px 0 0}
    .tt .c-blue{--c:#2b5d8a}
    .tt .folder-left{display:flex;align-items:center;gap:15px;min-width:0}
    .tt .avatar{width:54px;height:54px;flex:0 0 54px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-size:24px;font-weight:700;background:color-mix(in srgb,var(--c) 14%,#fff);color:var(--c)}
    .tt .folder h3{font-family:var(--serif);font-size:20px;font-weight:700;color:var(--ink);margin:0 0 4px}
    .tt .folder small{font-size:13px;color:var(--muted)}
    .tt .live-pill{display:inline-flex;align-items:center;gap:8px;padding:7px 13px;border:1px dashed #cfc8b6;border-radius:30px;font-size:12px;font-weight:600;color:#2f7d56;background:#f3faf5;white-space:nowrap}
    .tt .live-dot{width:8px;height:8px;border-radius:50%;background:#3a9d6b;box-shadow:0 0 0 3px rgba(58,157,107,.18)}
    .tt .folder-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center}

    /* Week label */
    .tt .week-label{display:flex;align-items:center;flex-wrap:wrap;gap:8px;font-size:12.5px;color:var(--muted)}
    .tt .week-label strong{color:var(--ink)}
    .tt #live-date-time{color:var(--accent);font-weight:600}

    /* ---------- Timetable ---------- */
    .tt .weekly-card{padding:16px;overflow-x:auto}
    .tt .weekly-table{width:100%;min-width:1050px;border-collapse:separate;border-spacing:0;table-layout:fixed}
    .tt .weekly-table thead th{height:50px;padding:10px 6px;background:var(--ink);color:#e9efe2;text-align:center;font-family:var(--serif);font-size:14px;font-weight:700;border:0}
    .tt .weekly-table thead th:first-child{border-radius:8px 0 0 0}
    .tt .weekly-table thead th:last-child{border-radius:0 8px 0 0}
    .tt .weekly-table thead th.period-heading{width:76px}
    .tt .weekly-table thead th.time-heading{width:112px}
    .tt .weekly-table thead th.today-column{background:var(--accent);color:#fff}
    .tt .weekly-table tbody tr{height:92px}
    .tt .weekly-table tbody td{border:0;border-right:1px dashed #d8d1bf;border-bottom:1px dashed #d8d1bf;vertical-align:middle}
    .tt .weekly-table tbody td:last-child{border-right:0}
    .tt .weekly-table tbody tr:last-child td{border-bottom:0}

    .tt .period-cell,.tt .time-cell{background:#faf7ee;text-align:center}
    .tt .period-number{display:inline-flex;align-items:center;justify-content:center;width:36px;height:32px;border-radius:8px;background:var(--ink);color:#fff;font-family:var(--serif);font-size:13px;font-weight:700}
    .tt .time-cell{padding:8px 4px;font-size:11px;font-weight:600;line-height:1.6;color:var(--muted)}
    .tt .time-from{color:var(--ink)}
    .tt .time-arrow{color:var(--accent)}

    /* Lecture cells: little sticky notes (one note per lecture) */
    .tt .lecture-cell{padding:8px;background:#fff}
    .tt .lecture-note{position:relative;padding:10px 10px 8px;border-left:4px solid var(--n,#d58a0b);border-radius:4px;background:color-mix(in srgb,var(--n,#d58a0b) 10%,#fff);transition:transform .2s ease,box-shadow .2s ease}
    .tt .lecture-note + .lecture-note{margin-top:6px}
    .tt .lecture-cell:hover .lecture-note{transform:translateY(-3px);box-shadow:0 8px 16px rgba(29,58,51,.14)}
    /* Normal lectures are always neutral blue, so green / amber / grey
       are reserved for live status only. */
    .tt .lecture-note{--n:#2b5d8a}
    .tt .lecture-subject{margin-bottom:4px;font-family:var(--serif);font-size:14px;font-weight:700;line-height:1.3;color:var(--ink);overflow-wrap:anywhere}
    .tt .lecture-class{font-size:11.5px;font-weight:600;color:var(--n,#2b5d8a);line-height:1.4}
    .tt .lecture-room,.tt .lecture-time{margin-top:3px;font-size:11px;color:var(--muted)}
    .tt .lecture-type{display:inline-block;margin-top:5px;padding:2px 7px;border:1px dashed #cfc8b6;border-radius:5px;background:#fff;color:var(--muted);font-size:10px;font-weight:600}

    .tt .free-cell{background:repeating-linear-gradient(45deg,#fdfbf5 0 10px,#faf7ee 10px 20px);color:#b3ab98;text-align:center;font-family:var(--hand);font-size:18px}
    .tt .break-cell,.tt .lunch-cell{text-align:center;font-family:var(--hand);font-size:20px}
    .tt .break-cell{background:#fff6dd;color:#a56b05}
    .tt .lunch-cell{background:#f1ebfb;color:#7a4fb5}
    .tt .break-cell.current-break,.tt .lunch-cell.current-break{box-shadow:inset 0 0 0 2px #3a9d6b}

    /* Current / upcoming (applied to the note itself) */
    .tt .lecture-note.current-lecture{--n:#3a9d6b;background:#e9f7ee;box-shadow:0 0 0 2px #3a9d6b}
    .tt .lecture-note.upcoming-lecture{--n:#d58a0b;background:#fff6dd;box-shadow:0 0 0 2px #e0a21f;border-left-style:solid}
    .tt .lecture-note.done-lecture{--n:#d0587f;background:#fdeef3;opacity:.9}
    .tt .lecture-note.done-lecture .lecture-subject{color:#8f3556}
    .tt .current-badge,.tt .upcoming-badge,.tt .done-badge{display:inline-flex;align-items:center;gap:4px;margin-bottom:5px;padding:2px 8px;border-radius:5px;font-size:10px;font-weight:700}
    .tt .current-badge{background:#3a9d6b;color:#fff}
    .tt .upcoming-badge{background:#ffe3a0;color:#6e4a00}
    .tt .done-badge{background:#fbd5e1;color:#8f3556}

    .tt .timetable-legend{display:flex;justify-content:flex-end;align-items:center;flex-wrap:wrap;gap:16px;margin-top:16px}
    .tt .legend-item{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--muted)}
    .tt .legend-box{width:13px;height:13px;border-radius:4px;border:2px solid transparent}
    .tt .legend-now{border-color:#3a9d6b;background:#e9f7ee}
    .tt .legend-upcoming{border-color:#e0a21f;background:#fff6dd}
    .tt .legend-done{border-color:#d0587f;background:#fdeef3}
    .tt .legend-free{border:1px dashed #b3ab98;background:#faf7ee}

    /* Empty states */
    .tt .no-data{padding:54px 20px;text-align:center}
    .tt .no-data-icon{font-size:38px;margin-bottom:8px}
    .tt .no-data h3{font-family:var(--hand);font-size:30px;color:var(--ink);margin:0 0 6px}
    .tt .no-data p{margin:0;color:var(--muted);font-size:14px}

    .tt .print-header,.tt .print-footer{display:none}

    @media (max-width:900px){.tt .hero{grid-template-columns:1fr}}
    @media (max-width:650px){
        .tt .hero-left{padding:22px 20px 22px 44px}.tt .hero::before{left:24px}.tt .hero-left h1{font-size:23px}
        .tt .select-row{flex-direction:column;align-items:stretch}
        .tt .folder-actions{width:100%}.tt .folder-actions .btn-solid,.tt .folder-actions .btn-line{flex:1}
        .tt .live-pill{display:none}
    }
    @media (prefers-reduced-motion:reduce){.tt *{transition:none!important;animation:none!important}}

    /* ---------- Print ---------- */
    @media print{
        @page{size:A4 landscape;margin:8mm}
        body *{visibility:hidden!important}
        .tt,.tt *{visibility:visible!important}
        .tt{position:absolute!important;top:0;left:0;width:100%!important;max-width:none!important;margin:0!important;padding:0!important;background:#fff!important}
        .tt .hero,.tt .filter-panel,.tt .folder,.tt .week-label,.tt .timetable-legend,.tt .no-data{display:none!important}
        .tt .print-header,.tt .print-footer{display:block!important;color:#222!important;text-align:center}
        .tt .print-school-name{margin-bottom:4px;font-size:17px;font-weight:800}
        .tt .print-title{margin-bottom:12px;font-size:12px;font-weight:700}
        .tt .print-details{width:100%;margin-bottom:12px;border-collapse:collapse;font-size:9px;text-align:left}
        .tt .print-details td{padding:5px;border:1px solid #ddd}
        .tt .print-details .label{font-weight:800}
        .tt .weekly-card{width:100%!important;padding:0!important;border:0!important;overflow:visible!important}
        .tt .weekly-table{min-width:0!important;border-collapse:collapse!important}
        .tt .weekly-table thead th{padding:5px 3px!important;border:1px solid #bbb!important;background:#edf3ff!important;color:#243047!important;font-size:8px!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
        .tt .weekly-table tbody tr{height:50px!important;break-inside:avoid}
        .tt .weekly-table tbody td{padding:3px!important;border:1px solid #ccc!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
        .tt .lecture-note{box-shadow:none!important;transform:none!important;background:#fff!important;opacity:1!important}
        .tt .lecture-subject{font-size:8px!important}
        .tt .lecture-class,.tt .time-cell{font-size:7px!important}
        .tt .lecture-room,.tt .lecture-time{font-size:6px!important}
        .tt .current-badge,.tt .upcoming-badge,.tt .done-badge,.tt .lecture-type{display:none!important}
        .tt .print-footer{margin-top:10px;font-size:8px}
    }
</style>

<div class="tt">

    {{-- Diary hero + live "now" panel --}}
    <section class="paper hero">
        <div class="hero-left">
            <h1>Teacher Weekly Timetable</h1>
            <p>View the complete weekly teaching schedule and current lecture status.</p>
            <div class="thought">“A well-planned week is a calm week.”</div>
        </div>

        <div class="bell">
            <div class="bell-label">Right now</div>
            <div class="bell-period" id="bellTitle">
                {{ $selectedTeacher ? 'Checking schedule…' : 'No teacher selected' }}
            </div>
            <div class="bell-sub" id="bellSub">
                {{ $selectedTeacher ? '' : 'Pick a teacher to see their live status.' }}
            </div>
            <div>
                <div class="bell-track"><div class="bell-fill" id="bellFill"></div></div>
                <div class="bell-times"><span id="bellStart">--</span><span id="bellEnd">--</span></div>
            </div>
        </div>
    </section>

    {{-- Teacher select --}}
    <section class="paper panel filter-panel">
        <div class="panel-head"><h3>Choose a teacher</h3><span>Weekly view</span></div>
        <div class="panel-body">
            <form method="GET" action="{{ route('admin.timetable.teacher') }}">
                <div class="select-row">
                    <div class="select-wrap">
                        <label for="teacher_id">Select Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="teacher-select" required>
                            <option value="">Select a teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}@if($teacher->teacher_id) ({{ $teacher->teacher_id }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-solid"><i class="bi bi-calendar-week"></i> View Timetable</button>
                </div>
            </form>
        </div>
    </section>

    @if($selectedTeacher)

        @if($timetables->count())

            @php
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

                /* Normalisers: tolerate "monday", "MON", " Mon " in the day column,
                   and "1" vs 1 vs " 1" in the period column. */
                $dayKey    = fn ($v) => strtolower(substr(trim((string) $v), 0, 3));
                $periodKey = fn ($v) => strtolower(trim((string) $v));
                $typeKey   = fn ($v) => strtolower(trim((string) $v));

                /* Safe time formatter: returns null instead of throwing. */
                $timeOf = function ($t, $format) {
                    if (!$t) { return null; }
                    try { return \Carbon\Carbon::parse($t)->format($format); }
                    catch (\Throwable $e) { return null; }
                };

                $periods = $timetables
                    ->sortBy(fn ($item) => (int) $item->period_number)
                    ->groupBy(fn ($item) => (string) $item->period_number);

                $periodNumbers = $periods->keys()->map(fn ($k) => (string) $k)->sort(function ($a, $b) {
                    if (is_numeric($a) && is_numeric($b)) { return (int) $a <=> (int) $b; }
                    return strcmp($a, $b);
                })->values();

                $firstTimetable = $timetables->first();
                $academicYear   = $firstTimetable->academic_year ?? 'N/A';
            @endphp

            {{-- Teacher folder --}}
            <div class="paper folder c-blue">
                <span class="folder-tab">Faculty</span>
                <div class="folder-left">
                    <div class="avatar">{{ strtoupper(substr($selectedTeacher->first_name, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $selectedTeacher->first_name }} {{ $selectedTeacher->last_name }}</h3>
                        <small>Teacher ID: {{ $selectedTeacher->teacher_id ?? 'N/A' }} &nbsp;•&nbsp; {{ $academicYear }}</small>
                    </div>
                </div>
                <div class="folder-actions">
                    <span class="live-pill"><span class="live-dot"></span> Live Timetable</span>
                    <button type="button" class="btn-line" onclick="shareTeacherTimetable()"><i class="bi bi-share"></i> Share</button>
                    <button type="button" class="btn-solid" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
                </div>
            </div>

            {{-- Print header --}}
            <div class="print-header">
                <div class="print-school-name">Gurukul Vidyalaya</div>
                <div class="print-title">Teacher Weekly Timetable</div>
                <table class="print-details">
                    <tr>
                        <td class="label">Teacher</td>
                        <td>{{ $selectedTeacher->first_name }} {{ $selectedTeacher->last_name }}</td>
                        <td class="label">Teacher ID</td>
                        <td>{{ $selectedTeacher->teacher_id ?? 'N/A' }}</td>
                        <td class="label">Academic Year</td>
                        <td>{{ $academicYear }}</td>
                    </tr>
                </table>
            </div>

            <section class="paper panel">
                <div class="panel-head">
                    <h3>Weekly schedule</h3>
                    <div class="week-label">
                        <strong>{{ $academicYear }}</strong>
                        <span id="live-date-time"></span>
                    </div>
                </div>

                <div class="weekly-card">
                    <table class="weekly-table">
                        <thead>
                            <tr>
                                <th class="period-heading">Period</th>
                                <th class="time-heading">Time</th>
                                @foreach($days as $day)
                                    <th class="day-header" data-day="{{ $day }}">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($periodNumbers as $periodNumber)
                                @php
                                    $periodEntries = $periods[$periodNumber] ?? collect();
                                    $periodTimeEntry = $periodEntries
                                        ->filter(fn ($item) => $timeOf($item->start_time, 'H:i') && $timeOf($item->end_time, 'H:i'))
                                        ->sortBy('start_time')
                                        ->first();
                                @endphp

                                <tr data-period="{{ $periodNumber }}">
                                    <td class="period-cell">
                                        <span class="period-number">{{ is_numeric($periodNumber) ? 'P' . $periodNumber : $periodNumber }}</span>
                                    </td>

                                    <td class="time-cell">
                                        @if($periodTimeEntry)
                                            <div class="time-from">{{ $timeOf($periodTimeEntry->start_time, 'h:i A') }}</div>
                                            <div class="time-arrow">↓</div>
                                            <div class="time-to">{{ $timeOf($periodTimeEntry->end_time, 'h:i A') }}</div>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    @foreach($days as $day)
                                        @php
                                            $entries = $timetables->filter(function ($item) use ($day, $periodNumber, $dayKey, $periodKey) {
                                                return $dayKey($item->day) === $dayKey($day)
                                                    && $periodKey($item->period_number) === $periodKey($periodNumber);
                                            })->values();

                                            $breakEntry = $entries->first(fn ($e) => in_array($typeKey($e->period_type), ['break', 'lunch'], true));
                                            $lectures   = $entries->reject(fn ($e) => in_array($typeKey($e->period_type), ['break', 'lunch'], true))->values();
                                        @endphp

                                        @if($lectures->count())

                                            <td class="lecture-cell" data-day="{{ $day }}" data-period="{{ $periodNumber }}">
                                                @foreach($lectures as $lecture)
                                                    <div class="lecture-note"
                                                         data-kind="lecture"
                                                         data-start="{{ $timeOf($lecture->start_time, 'H:i:s') }}"
                                                         data-end="{{ $timeOf($lecture->end_time, 'H:i:s') }}"
                                                         data-subject="{{ $lecture->subject }}"
                                                         data-class="{{ $lecture->class }}{{ $lecture->section ? ' - ' . $lecture->section : '' }}"
                                                         data-start-display="{{ $timeOf($lecture->start_time, 'h:i A') }}"
                                                         data-end-display="{{ $timeOf($lecture->end_time, 'h:i A') }}">

                                                        <div class="lecture-subject">{{ $lecture->subject }}</div>

                                                        @if($lecture->class)
                                                            <div class="lecture-class">
                                                                {{ $lecture->class }}@if($lecture->section) - Section {{ $lecture->section }}@endif
                                                            </div>
                                                        @endif

                                                        @if($lecture->room)
                                                            <div class="lecture-room">Room: {{ $lecture->room }}</div>
                                                        @endif

                                                        @if($timeOf($lecture->start_time, 'h:i A') && $timeOf($lecture->end_time, 'h:i A'))
                                                            <div class="lecture-time">
                                                                {{ $timeOf($lecture->start_time, 'h:i A') }} - {{ $timeOf($lecture->end_time, 'h:i A') }}
                                                            </div>
                                                        @endif

                                                        @if($lecture->lecture_type)
                                                            <span class="lecture-type">{{ ucfirst($lecture->lecture_type) }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </td>

                                        @elseif($breakEntry)

                                            @php $isLunch = $typeKey($breakEntry->period_type) === 'lunch'; @endphp
                                            <td class="{{ $isLunch ? 'lunch-cell' : 'break-cell' }}"
                                                data-day="{{ $day }}"
                                                data-period="{{ $periodNumber }}"
                                                data-kind="{{ $isLunch ? 'lunch' : 'break' }}"
                                                data-start="{{ $timeOf($breakEntry->start_time, 'H:i:s') }}"
                                                data-end="{{ $timeOf($breakEntry->end_time, 'H:i:s') }}"
                                                data-start-display="{{ $timeOf($breakEntry->start_time, 'h:i A') }}"
                                                data-end-display="{{ $timeOf($breakEntry->end_time, 'h:i A') }}">
                                                {{ $isLunch ? 'Lunch' : 'Break' }}
                                            </td>

                                        @else

                                            <td class="free-cell" data-day="{{ $day }}" data-period="{{ $periodNumber }}">Free</td>

                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="timetable-legend">
                        <div class="legend-item"><span class="legend-box legend-now"></span> Current Lecture</div>
                        <div class="legend-item"><span class="legend-box legend-upcoming"></span> Upcoming</div>
                        <div class="legend-item"><span class="legend-box legend-done"></span> Completed</div>
                        <div class="legend-item"><span class="legend-box legend-free"></span> Free Period</div>
                    </div>

                    <div class="print-footer">
                        Gurukul Vidyalaya • Teacher Weekly Timetable • Generated on {{ now()->format('d-m-Y h:i A') }}
                    </div>
                </div>
            </section>

        @else

            <section class="paper no-data">
                <div class="no-data-icon">📅</div>
                <h3>No Timetable Found</h3>
                <p>This teacher does not have any timetable entries yet.</p>
            </section>

        @endif

    @elseif(request('teacher_id'))

        <section class="paper no-data">
            <div class="no-data-icon">📅</div>
            <h3>Teacher Not Found</h3>
            <p>Please select a valid teacher.</p>
        </section>

    @else

        <section class="paper no-data">
            <div class="no-data-icon">👨‍🏫</div>
            <h3>Select a Teacher</h3>
            <p>Choose a teacher from the dropdown above to view the weekly timetable.</p>
        </section>

    @endif

</div>

<script>
(function () {
    /* The school's timezone. Live status is computed in THIS zone, so it is
       correct even if the viewer's device clock is set to another zone. */
    var SCHOOL_TZ = 'Asia/Kolkata';
    var DAY_NAMES = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    /* Current day name + minutes since midnight in the school's timezone. */
    function schoolNow() {
        var d = new Date();
        try {
            var parts = new Intl.DateTimeFormat('en-US', {
                timeZone: SCHOOL_TZ, weekday: 'long', hourCycle: 'h23',
                hour: 'numeric', minute: 'numeric', second: 'numeric'
            }).formatToParts(d);
            var o = {};
            parts.forEach(function (p) { o[p.type] = p.value; });
            return {
                day: o.weekday,
                minutes: (Number(o.hour) % 24) * 60 + Number(o.minute) + Number(o.second) / 60
            };
        } catch (e) {
            return {
                day: DAY_NAMES[d.getDay()],
                minutes: d.getHours() * 60 + d.getMinutes() + d.getSeconds() / 60
            };
        }
    }

    /* "HH:MM[:SS]" -> minutes since midnight, or null if unusable. */
    function toMinutes(t) {
        if (!t) return null;
        var p = String(t).trim().split(':');
        if (p.length < 2) return null;
        var h = Number(p[0]), m = Number(p[1]), s = p.length >= 3 ? Number(p[2]) : 0;
        if (isNaN(h) || isNaN(m) || isNaN(s)) return null;
        return h * 60 + m + s / 60;
    }

    function setBell(title, sub, pct, start, end) {
        var t = document.getElementById('bellTitle');
        if (!t) return;
        t.textContent = title;
        document.getElementById('bellSub').textContent = sub || '';
        document.getElementById('bellFill').style.width = Math.max(0, Math.min(100, pct)) + '%';
        document.getElementById('bellStart').textContent = start || '--';
        document.getElementById('bellEnd').textContent = end || '--';
    }

    function minsText(n) { return n + (n === 1 ? ' min' : ' min'); }

    /* Clock label (every second) */
    function updateClock() {
        var el = document.getElementById('live-date-time');
        if (!el) return;
        var now = new Date();
        try {
            el.textContent = '• ' +
                now.toLocaleDateString('en-IN', { timeZone: SCHOOL_TZ, weekday: 'long', day: '2-digit', month: 'short', year: 'numeric' }) +
                ' • ' +
                now.toLocaleTimeString('en-IN', { timeZone: SCHOOL_TZ, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
        } catch (e) {
            el.textContent = '• ' + now.toLocaleString('en-IN');
        }
    }

    /* Highlights + "Right now" panel */
    function updateLive() {
        if (!document.querySelector('.weekly-table')) return;

        var now = schoolNow();
        var today = now.day;
        var nowMin = now.minutes;

        /* 1. Reset everything from the previous tick */
        document.querySelectorAll('.lecture-note').forEach(function (n) {
            n.classList.remove('current-lecture', 'upcoming-lecture', 'done-lecture');
            n.querySelectorAll('.current-badge, .upcoming-badge, .done-badge').forEach(function (b) { b.remove(); });
        });
        document.querySelectorAll('.break-cell, .lunch-cell').forEach(function (c) { c.classList.remove('current-break'); });
        document.querySelectorAll('.day-header').forEach(function (h) { h.classList.remove('today-column'); });

        /* 2. Today's column */
        var header = document.querySelector('.day-header[data-day="' + today + '"]');
        if (header) header.classList.add('today-column');

        /* 3. Gather today's timed items (lectures + break/lunch) */
        var lectures = [], breaks = [];

        document.querySelectorAll('.lecture-cell[data-day="' + today + '"] .lecture-note').forEach(function (n) {
            var s = toMinutes(n.getAttribute('data-start'));
            var e = toMinutes(n.getAttribute('data-end'));
            if (s === null || e === null || e <= s) return;
            lectures.push({ el: n, s: s, e: e });
        });

        document.querySelectorAll('.break-cell[data-day="' + today + '"], .lunch-cell[data-day="' + today + '"]').forEach(function (c) {
            var s = toMinutes(c.getAttribute('data-start'));
            var e = toMinutes(c.getAttribute('data-end'));
            if (s === null || e === null || e <= s) return;
            breaks.push({ el: c, s: s, e: e, kind: c.getAttribute('data-kind') });
        });

        lectures.sort(function (a, b) { return a.s - b.s; });

        /* 4. Classify */
        var current = lectures.filter(function (x) { return nowMin >= x.s && nowMin < x.e; });

        var nextStart = Infinity;
        lectures.forEach(function (x) { if (x.s > nowMin && x.s < nextStart) nextStart = x.s; });
        var upcoming = lectures.filter(function (x) { return x.s === nextStart; });

        var curBreak = null;
        breaks.forEach(function (b) { if (nowMin >= b.s && nowMin < b.e) curBreak = b; });

        /* 5. Paint */
        function badge(note, cls, text) {
            var b = document.createElement('span');
            b.className = cls;
            b.textContent = text;
            note.insertBefore(b, note.firstChild);
        }
        lectures.forEach(function (x) {
            if (x.e <= nowMin) { x.el.classList.add('done-lecture'); badge(x.el, 'done-badge', '✓ DONE'); }
        });
        current.forEach(function (x) { x.el.classList.add('current-lecture'); badge(x.el, 'current-badge', '● NOW'); });
        upcoming.forEach(function (x) { x.el.classList.add('upcoming-lecture'); badge(x.el, 'upcoming-badge', 'UPCOMING'); });
        if (curBreak) curBreak.el.classList.add('current-break');

        /* 6. "Right now" panel */
        var nextNote = upcoming.length ? upcoming[0] : null;
        var nextText = nextNote
            ? ' • Next: ' + nextNote.el.getAttribute('data-subject') + ' at ' + nextNote.el.getAttribute('data-start-display')
            : '';

        if (current.length) {
            var c = current[0];
            var left = Math.max(1, Math.ceil(c.e - nowMin));
            var cls = c.el.getAttribute('data-class');
            setBell(c.el.getAttribute('data-subject'),
                (cls ? cls + ' • ' : '') + minsText(left) + ' left' + nextText,
                ((nowMin - c.s) / (c.e - c.s)) * 100,
                c.el.getAttribute('data-start-display'), c.el.getAttribute('data-end-display'));
        } else if (curBreak) {
            var bl = Math.max(1, Math.ceil(curBreak.e - nowMin));
            setBell(curBreak.kind === 'lunch' ? 'Lunch break' : 'Break time',
                minsText(bl) + ' left' + nextText,
                ((nowMin - curBreak.s) / (curBreak.e - curBreak.s)) * 100,
                curBreak.el.getAttribute('data-start-display'), curBreak.el.getAttribute('data-end-display'));
        } else if (nextNote) {
            var until = Math.max(1, Math.ceil(nextNote.s - nowMin));
            setBell('Free until ' + nextNote.el.getAttribute('data-start-display'),
                'Next: ' + nextNote.el.getAttribute('data-subject') + ' in ' + minsText(until),
                0, nextNote.el.getAttribute('data-start-display'), nextNote.el.getAttribute('data-end-display'));
        } else if (lectures.length) {
            setBell('Lectures are over', 'No more lectures scheduled for today.', 100, '', '');
        } else {
            setBell('No lectures today',
                today === 'Sunday' ? 'It is the weekend.' : 'Nothing scheduled on ' + today + '.',
                0, '', '');
        }
    }

    window.shareTeacherTimetable = function () {
        var url = window.location.href;
        if (navigator.share) {
            navigator.share({ title: document.title, url: url }).catch(function () {});
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function () { alert('Timetable link copied.'); });
        } else {
            window.prompt('Copy this link:', url);
        }
    };

    updateClock();
    updateLive();
    setInterval(updateClock, 1000);
    setInterval(updateLive, 10000);
})();
</script>
@endsection