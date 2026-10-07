@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<style>
    .dash{max-width:1500px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* ---------- Hero: school diary ---------- */
    .hero{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(0,1fr);margin-bottom:22px;overflow:hidden;position:relative}
    .hero::before{content:"";position:absolute;left:34px;top:0;bottom:0;width:2px;background:rgba(228,87,46,.35)}
    .hero-left{padding:26px 28px 26px 56px}
    .hero-left h1{font-family:var(--serif);font-size:28px;font-weight:700;color:var(--ink);margin-bottom:6px}
    .hero-left p{color:var(--muted);margin-bottom:16px}
    .thought{font-family:var(--hand);font-size:24px;line-height:1.15;color:var(--accent);padding:8px 0 0}
    .thought small{display:block;font-family:'Public Sans',sans-serif;font-size:12px;color:var(--muted);margin-bottom:2px}
    .hero-btns{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
    .btn-solid,.btn-line{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:8px;font-size:13.5px;font-weight:500}
    .btn-solid{background:var(--ink);color:#fff;border:1px solid var(--ink)}
    .btn-solid:hover{background:var(--ink-2);color:#fff}
    .btn-line{background:#fff;border:1px solid #cfc8b6;color:var(--text)}
    .btn-line:hover{border-color:var(--ink);color:var(--ink)}

    .bell{background:var(--ink);color:#e9efe2;padding:26px 28px;display:flex;flex-direction:column;justify-content:center;gap:10px;
        background-image:radial-gradient(ellipse at 20% 0,rgba(255,255,255,.09),transparent 60%)}
    .bell-label{font-family:var(--hand);font-size:22px;color:var(--chalk);line-height:1}
    .bell-period{font-family:var(--serif);font-size:26px;font-weight:700;color:#fff;line-height:1.15}
    .bell-sub{font-size:13px;color:#c5d0bd}
    .bell-track{height:10px;border-radius:6px;background:rgba(255,255,255,.14);overflow:hidden;margin-top:4px}
    .bell-fill{height:100%;width:0;border-radius:6px;background:repeating-linear-gradient(45deg,var(--chalk) 0 8px,#e0a21f 8px 16px);transition:width .6s ease}
    .bell-times{display:flex;justify-content:space-between;font-size:12px;color:#aebba6}

    /* ---------- KPI: folder tabs ---------- */
    .kpi-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-bottom:22px}
    .kpi{transition:transform .22s ease,box-shadow .22s ease;position:relative;padding:22px 22px 16px;margin-top:10px;border-top:4px solid var(--c);display:flex;flex-direction:column;gap:14px}
    .kpi:hover,.kpi:focus-within{transform:translateY(-8px);box-shadow:0 16px 28px rgba(29,58,51,.16)}
    .kpi-tab{position:absolute;top:-24px;left:16px;padding:3px 12px;background:var(--c);color:#fff;font-size:12px;font-weight:600;border-radius:7px 7px 0 0}
    .kpi-top{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .kpi-value{font-family:var(--serif);font-size:34px;font-weight:700;line-height:1;color:var(--ink)}
    .kpi-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;background:color-mix(in srgb,var(--c) 14%,#fff);color:var(--c)}
    .kpi-link{padding-top:12px;border-top:1px dashed #d8d1bf;font-size:13px;font-weight:500;color:var(--c);display:flex;justify-content:space-between;align-items:center}
    .c-blue{--c:#2b5d8a}.c-amber{--c:#d58a0b}.c-green{--c:#3a9d6b}.c-red{--c:#e4572e}.c-violet{--c:#7a4fb5}

    /* ---------- Panels ---------- */
    .grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px;margin-bottom:22px}
    .panel{overflow:hidden}
    .panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:15px 22px;border-bottom:1px dashed #d8d1bf}
    .panel-head h3{font-family:var(--serif);font-size:17px;font-weight:700;color:var(--ink);margin:0}
    .panel-head span,.panel-head a{font-size:12.5px;color:var(--muted)}
    .panel-body{padding:22px}

    /* Students */
    .gender{display:flex;align-items:center;justify-content:space-around;gap:28px;min-height:220px;flex-wrap:wrap}
    .donut{position:relative;width:176px;height:176px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .donut::after{content:"";position:absolute;width:120px;height:120px;border-radius:50%;background:#fff}
    .donut-total{position:relative;z-index:2;text-align:center}
    .donut-total strong{display:block;font-family:var(--serif);font-size:28px;line-height:1.1;color:var(--ink)}
    .donut-total span{font-size:12px;color:var(--muted)}
    .legend{display:flex;flex-direction:column;gap:18px;min-width:150px}
    .legend-row{display:flex;align-items:center;gap:12px}
    .legend-swatch{width:14px;height:14px;border-radius:4px;flex-shrink:0}
    .legend-row strong{display:block;font-family:var(--serif);font-size:20px;line-height:1.2}
    .legend-row span{font-size:12.5px;color:var(--muted)}

    /* Wall calendar */
    .wall{padding:0}
    .wall-top{position:relative;background:var(--red);color:#fff;text-align:center;padding:16px 10px 12px}
    .wall-top::before,.wall-top::after{content:"";position:absolute;top:-7px;width:14px;height:22px;border-radius:7px;background:#fff;border:2px solid #9a9a9a}
    .wall-top::before{left:22%}.wall-top::after{right:22%}
    .wall-top b{display:block;font-family:var(--serif);font-size:22px;line-height:1.1}
    .wall-top span{font-size:12.5px;opacity:.9}
    .cal-week,.cal-days{display:grid;grid-template-columns:repeat(7,1fr);gap:4px}
    .cal-week{margin-bottom:6px}
    .cal-week div{text-align:center;font-size:12px;font-weight:600;color:var(--muted);padding:4px 0}
    .cal-day{min-height:34px;display:flex;align-items:center;justify-content:center;font-size:13px;color:var(--text)}
    .cal-day.weekend{color:var(--red)}
    .cal-day.empty{visibility:hidden}
    .cal-day.today{font-weight:700;color:var(--red);position:relative}
    .cal-day.today::after{content:"";position:absolute;inset:1px 5px;border:2px solid var(--red);border-radius:50% 46% 52% 48%/48% 52% 46% 50%;transform:rotate(-6deg)}

    /* Overview */
    .overview{display:grid;grid-template-columns:repeat(4,minmax(0,1fr))}
    .overview-item{display:flex;align-items:center;gap:14px;padding:20px 22px;border-right:1px dashed #d8d1bf}
    .overview-item:last-child{border-right:none}
    .overview-item .kpi-icon{width:42px;height:42px;font-size:18px}
    .overview-item strong{display:block;font-family:var(--serif);font-size:22px;line-height:1.2;color:var(--ink)}
    .overview-item span{font-size:12.5px;color:var(--muted)}

    /* Notice board */
    .cork{padding:26px 22px 14px;background-color:#c99560;background-image:radial-gradient(rgba(90,55,20,.28) 1px,transparent 1.5px),radial-gradient(rgba(255,255,255,.18) 1px,transparent 1.5px);background-size:11px 11px,17px 17px;background-position:0 0,5px 7px;min-height:300px;border-top:6px solid var(--wood)}
    .note{position:relative;display:block;padding:22px 18px 14px;margin:0 6px 22px;background:var(--n,#fff3a8);color:#3a3326;box-shadow:2px 4px 8px rgba(60,35,10,.3);transform:rotate(var(--r,-1deg));transition:transform .22s ease,box-shadow .22s ease}
    .note:hover,.note:focus-visible{transform:translateY(-9px) rotate(0) scale(1.03);box-shadow:4px 14px 22px rgba(60,35,10,.38);color:#3a3326;z-index:2}
    .note::before{content:"";position:absolute;top:-8px;left:50%;width:15px;height:15px;margin-left:-8px;border-radius:50%;background:radial-gradient(circle at 35% 30%,#ff8a73,#c4321a);box-shadow:1px 3px 3px rgba(0,0,0,.35)}
    .note strong{display:block;font-family:var(--hand);font-size:25px;line-height:1.05;margin-bottom:4px}
    .note span{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:13px}
    .note:nth-child(4n+1){--n:#fff3a8;--r:-1.2deg}.note:nth-child(4n+2){--n:#ffd6e0;--r:1deg}
    .note:nth-child(4n+3){--n:#cfeaff;--r:-.6deg}.note:nth-child(4n+4){--n:#d4f2d2;--r:1.3deg}
    .cork-empty{text-align:center;color:#4a3217;padding:50px 10px}
    .cork-empty b{display:block;font-family:var(--hand);font-size:28px}

    /* Quick actions */
    .actions{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
    .action{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;min-height:96px;padding:14px;border:1.5px dashed #cfc8b6;border-radius:12px;font-size:13px;font-weight:600;text-align:center;color:var(--text);transition:transform .15s,border-color .15s,background .15s}
    .action i{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--c);background:color-mix(in srgb,var(--c) 14%,#fff)}
    a.action:hover{transform:rotate(-1.5deg);border-style:solid;border-color:var(--c);background:#fffdf6;color:var(--text)}
    .action.disabled{opacity:.5;cursor:not-allowed}


    /* ---------- Dashboard loaders ---------- */
    @property --k{syntax:'<number>';inherits:false;initial-value:1}
    .donut{--k:1;background:conic-gradient(#2b5d8a 0deg calc(var(--m,0deg) * var(--k)),#e0679a calc(var(--m,0deg) * var(--k)) 360deg);animation:sweep 1.2s cubic-bezier(.3,.7,.2,1) .1s both}
    .is-loading .donut{animation:none;--k:0}
    @keyframes sweep{from{--k:0}to{--k:1}}
    .note{animation:pin .45s cubic-bezier(.3,1.4,.5,1) backwards}
    .note:nth-child(2){animation-delay:.12s}.note:nth-child(3){animation-delay:.24s}.note:nth-child(n+4){animation-delay:.36s}
    .is-loading .note{animation:none}
    @keyframes pin{from{opacity:0;transform:translateY(-14px) rotate(var(--r,-1deg))}to{opacity:1;transform:translateY(0) rotate(var(--r,-1deg))}}
    @media (prefers-reduced-motion:reduce){.donut,.note{animation:none}}

    @media (max-width:1200px){.kpi-grid{grid-template-columns:repeat(2,1fr)}.overview{grid-template-columns:repeat(2,1fr)}.overview-item:nth-child(2){border-right:none}.overview-item:nth-child(-n+2){border-bottom:1px dashed #d8d1bf}}
    @media (max-width:900px){.hero{grid-template-columns:1fr}.grid-2{grid-template-columns:1fr}}
    @media (max-width:650px){.kpi-grid{grid-template-columns:1fr}.kpi{margin-top:18px}.actions{grid-template-columns:repeat(2,1fr)}.overview{grid-template-columns:1fr}.overview-item{border-right:none;border-bottom:1px dashed #d8d1bf}.overview-item:last-child{border-bottom:none}.hero-left{padding:22px 20px 22px 44px}.hero::before{left:24px}.hero-left h1{font-size:23px}}

    /* ---------- Skeleton loader ---------- */
    .dash.is-loading{display:none}
    .dash.reveal{animation:dashIn .35s ease}
    @keyframes dashIn{from{opacity:0}to{opacity:1}}
    #dashSkeleton{max-width:1500px;margin:0 auto}
    .sk{background:linear-gradient(90deg,#ece7da 25%,#f7f3ea 50%,#ece7da 75%);background-size:200% 100%;animation:shimmer 1.3s linear infinite;border-radius:8px}
    @keyframes shimmer{from{background-position:200% 0}to{background-position:-200% 0}}
    .sk-card{background:#fff;border:1px solid var(--line);border-radius:10px;padding:22px}
    .sk-line{height:12px;margin-bottom:12px}
    .sk-hero{display:grid;grid-template-columns:1.5fr 1fr;gap:0;margin-bottom:22px;padding:0;overflow:hidden}
    .sk-grid4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-bottom:22px}
    .sk-grid2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px;margin-bottom:22px}
    @media (max-width:1200px){.sk-grid4{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:900px){.sk-hero,.sk-grid2{grid-template-columns:1fr}}
    @media (max-width:650px){.sk-grid4{grid-template-columns:1fr}}
    @media (prefers-reduced-motion:reduce){.sk{animation:none}.dash.reveal{animation:none}}
</style>

@php
    $studentCount = (int) ($students ?? 0);
    $teacherCount = (int) ($teachers ?? 0);
    $classCount   = (int) ($classes ?? 0);
    $noticeCount  = (int) ($notices ?? 0);

    $hour = now()->hour;
    $greeting = $hour >= 5 && $hour < 12 ? 'Good morning'
              : ($hour >= 12 && $hour < 17 ? 'Good afternoon'
              : ($hour >= 17 && $hour < 21 ? 'Good evening' : 'Good night'));

    $thoughts = [
        'Well begun is half done.',
        'Small steps every day lead to big results.',
        'Knowledge grows when it is shared.',
        'Practice makes progress.',
        'Every day is a chance to learn something new.',
        'Curiosity is the first lesson.',
        'Be a little better than yesterday.',
    ];
    $thought = $thoughts[now()->dayOfYear % count($thoughts)];

    $today    = now();
    $firstDay = $today->copy()->startOfMonth()->dayOfWeek;

    $maleStudents   = (int) ($maleStudents ?? 0);
    $femaleStudents = (int) ($femaleStudents ?? 0);
    $genderTotal    = $maleStudents + $femaleStudents;

    if ($genderTotal === 0 && $studentCount > 0) {
        $maleStudents = $studentCount;
        $genderTotal  = $studentCount;
    }

    $malePct   = $genderTotal > 0 ? round(($maleStudents / $genderTotal) * 100) : 0;
    $femalePct = $genderTotal > 0 ? 100 - $malePct : 0;
    $maleDeg   = $malePct * 3.6;

    $recentNotices = $recentNotices ?? collect();
@endphp

<noscript><style>#dashSkeleton{display:none}.dash.is-loading{display:block}</style></noscript>
{{-- Skeleton loader: shown until the page has finished loading --}}
<div id="dashSkeleton" aria-hidden="true">
    <div class="sk-card sk-hero">
        <div style="padding:26px 28px">
            <div class="sk" style="height:30px;width:55%;margin-bottom:14px"></div>
            <div class="sk sk-line" style="width:40%"></div>
            <div class="sk" style="height:22px;width:70%;margin:18px 0 20px"></div>
            <div style="display:flex;gap:10px"><div class="sk" style="height:38px;width:150px"></div><div class="sk" style="height:38px;width:130px"></div></div>
        </div>
        <div style="padding:26px 28px;background:#efe9da">
            <div class="sk" style="height:18px;width:35%;margin-bottom:14px"></div>
            <div class="sk" style="height:28px;width:60%;margin-bottom:14px"></div>
            <div class="sk sk-line" style="width:50%"></div>
            <div class="sk" style="height:10px;margin-top:20px"></div>
        </div>
    </div>

    <div class="sk-grid4">
        @for ($k = 0; $k < 4; $k++)
            <div class="sk-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px">
                    <div class="sk" style="height:34px;width:80px"></div>
                    <div class="sk" style="height:46px;width:46px;border-radius:12px"></div>
                </div>
                <div class="sk sk-line" style="width:55%;margin:0"></div>
            </div>
        @endfor
    </div>

    <div class="sk-grid2">
        <div class="sk-card" style="height:300px">
            <div class="sk sk-line" style="width:35%;height:16px;margin-bottom:26px"></div>
            <div style="display:flex;justify-content:space-around;align-items:center">
                <div class="sk" style="width:170px;height:170px;border-radius:50%"></div>
                <div><div class="sk sk-line" style="width:110px"></div><div class="sk sk-line" style="width:110px"></div></div>
            </div>
        </div>
        <div class="sk-card" style="height:300px">
            <div class="sk" style="height:46px;margin-bottom:18px"></div>
            <div class="sk" style="height:190px"></div>
        </div>
    </div>

    <div class="sk-card" style="height:100px;margin-bottom:22px">
        <div class="sk" style="height:100%"></div>
    </div>

    <div class="sk-grid2">
        <div class="sk-card" style="height:320px"><div class="sk" style="height:100%"></div></div>
        <div class="sk-card" style="height:320px"><div class="sk" style="height:100%"></div></div>
    </div>
</div>

<div class="dash is-loading" id="dashReal">

    {{-- Diary hero + school day --}}
    <section class="paper hero">
        <div class="hero-left">
            <h1>{{ $greeting }}, Admin</h1>
            <p>Here is how your school looks today.</p>
            <div class="thought"><small>Thought for the day</small>“{{ $thought }}”</div>
            <div class="hero-btns">
                <a href="{{ route('admin.attendance.students') }}" class="btn-solid"><i class="bi bi-calendar-check"></i> Mark attendance</a>
                <a href="{{ route('admin.notices.create') }}" class="btn-line"><i class="bi bi-pin-angle"></i> Pin a notice</a>
            </div>
        </div>

        <div class="bell" id="bell">
            <div class="bell-label">School day</div>
            <div class="bell-period" id="bellPeriod">Loading…</div>
            <div class="bell-sub" id="bellSub"></div>
            <div>
                <div class="bell-track"><div class="bell-fill" id="bellFill"></div></div>
                <div class="bell-times"><span>11:00 AM</span><span>5:00 PM</span></div>
            </div>
        </div>
    </section>

    {{-- KPI folders --}}
    <div class="kpi-grid">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Students</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($studentCount) }}</div>
                <div class="kpi-icon"><i class="bi bi-backpack3-fill"></i></div>
            </div>
            <a href="{{ route('admin.students.index') }}" class="kpi-link">View students <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="paper kpi c-amber">
            <span class="kpi-tab">Faculty</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($teacherCount) }}</div>
                <div class="kpi-icon"><i class="bi bi-easel2-fill"></i></div>
            </div>
            <a href="{{ route('admin.teachers.index') }}" class="kpi-link">View faculty <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="paper kpi c-green">
            <span class="kpi-tab">Classes</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($classCount) }}</div>
                <div class="kpi-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
            </div>
            <a href="{{ route('admin.classes.index') }}" class="kpi-link">View classes <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="paper kpi c-red">
            <span class="kpi-tab">Notices</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($noticeCount) }}</div>
                <div class="kpi-icon"><i class="bi bi-pin-angle-fill"></i></div>
            </div>
            <a href="{{ route('admin.notices.index') }}" class="kpi-link">View notices <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    {{-- Students + wall calendar --}}
    <div class="grid-2">
        <section class="paper panel">
            <div class="panel-head"><h3>Student distribution</h3><span>Boys and girls</span></div>
            <div class="panel-body">
                <div class="gender">
                    <div class="donut" style="--m:{{ $maleDeg }}deg;">
                        <div class="donut-total"><strong>{{ number_format($studentCount) }}</strong><span>Students</span></div>
                    </div>
                    <div class="legend">
                        <div class="legend-row">
                            <div class="legend-swatch" style="background:#2b5d8a"></div>
                            <div><strong>{{ number_format($maleStudents) }}</strong><span>Male, {{ $malePct }}%</span></div>
                        </div>
                        <div class="legend-row">
                            <div class="legend-swatch" style="background:#e0679a"></div>
                            <div><strong>{{ number_format($femaleStudents) }}</strong><span>Female, {{ $femalePct }}%</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="paper panel wall">
            <div class="wall-top"><b>{{ $today->format('F') }}</b><span>{{ $today->format('Y') }}</span></div>
            <div class="panel-body">
                <div class="cal-week">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>
                <div class="cal-days">
                    @for ($i = 0; $i < $firstDay; $i++)<div class="cal-day empty"></div>@endfor
                    @for ($day = 1; $day <= $today->daysInMonth; $day++)
                        @php $date = $today->copy()->startOfMonth()->addDays($day - 1); @endphp
                        <div class="cal-day {{ $date->isToday() ? 'today' : '' }} {{ $date->isWeekend() ? 'weekend' : '' }}">{{ $day }}</div>
                    @endfor
                </div>
            </div>
        </section>
    </div>

    {{-- Overview --}}
    <section class="paper panel" style="margin-bottom:22px;">
        <div class="panel-head"><h3>School overview</h3><span>At a glance</span></div>
        <div class="overview">
            <div class="overview-item c-blue">
                <div class="kpi-icon"><i class="bi bi-book-fill"></i></div>
                <div><strong>{{ number_format($books ?? 0) }}</strong><span>Library books</span></div>
            </div>
            <div class="overview-item c-green">
                <div class="kpi-icon"><i class="bi bi-bus-front-fill"></i></div>
                <div><strong>{{ number_format($transport ?? 0) }}</strong><span>Transport records</span></div>
            </div>
            <div class="overview-item c-amber">
                <div class="kpi-icon"><i class="bi bi-trophy-fill"></i></div>
                <div><strong>{{ number_format($events ?? 0) }}</strong><span>School events</span></div>
            </div>
            <div class="overview-item c-violet">
                <div class="kpi-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div><strong>{{ number_format($classes ?? 0) }}</strong><span>Active classes</span></div>
            </div>
        </div>
    </section>

    {{-- Notice board + quick actions --}}
    <div class="grid-2">
        <section class="paper panel">
            <div class="panel-head"><h3>Notice board</h3><a href="{{ route('admin.notices.index') }}">View all</a></div>
            <div class="cork">
                @if ($recentNotices instanceof \Illuminate\Support\Collection && $recentNotices->count() > 0)
                    @foreach ($recentNotices as $notice)
                        <a href="{{ route('admin.notices.show', $notice->id) }}" class="note">
                            <strong>{{ $notice->title ?? 'School notice' }}</strong>
                            <span>{{ $notice->description ?? 'New school announcement available.' }}</span>
                        </a>
                    @endforeach
                @else
                    <div class="cork-empty"><b>The board is empty</b>Pin a notice to share an announcement.</div>
                @endif
            </div>
        </section>

        <section class="paper panel">
            <div class="panel-head"><h3>Quick actions</h3><span>Your desk</span></div>
            <div class="panel-body">
                <div class="actions">
                    <a href="{{ route('admin.students.index') }}" class="action c-blue"><i class="bi bi-person-plus-fill"></i>Add student</a>
                    <a href="{{ route('admin.teachers.index') }}" class="action c-amber"><i class="bi bi-person-workspace"></i>Add faculty</a>
                    <a href="{{ route('admin.attendance.students') }}" class="action c-green"><i class="bi bi-calendar-check-fill"></i>Mark attendance</a>
                    <div class="action c-violet disabled" title="Coming soon"><i class="bi bi-cash-stack"></i>Collect fees</div>
                    <a href="{{ route('admin.notices.create') }}" class="action c-red"><i class="bi bi-pin-angle-fill"></i>Create notice</a>
                    <a href="{{ route('admin.library.books.create') }}" class="action c-blue"><i class="bi bi-book-fill"></i>Add book</a>
                </div>
            </div>
        </section>
    </div>

</div>

@push('scripts')
<script>
/* School day widget: 11:00 AM - 5:00 PM, eight 45-minute periods.
   Change START_HOUR / PERIOD_MIN to match your school. */
(function () {
    const START_HOUR = 11, PERIOD_MIN = 45, PERIODS = 8;
    const total = PERIOD_MIN * PERIODS;

    function update() {
        const now = new Date();
        const mins = now.getHours() * 60 + now.getMinutes() - START_HOUR * 60;
        const day = now.getDay();
        const title = document.getElementById('bellPeriod');
        const sub = document.getElementById('bellSub');
        const fill = document.getElementById('bellFill');
        let pct = 0;

        if (day === 0 || day === 6) {
            title.textContent = 'No classes today';
            sub.textContent = 'It is the weekend. Enjoy the break.';
        } else if (mins < 0) {
            title.textContent = 'School opens soon';
            sub.textContent = 'First bell at 11:00 AM.';
        } else if (mins >= total) {
            pct = 100;
            title.textContent = 'School day is over';
            sub.textContent = 'All periods are complete.';
        } else {
            const p = Math.floor(mins / PERIOD_MIN) + 1;
            const left = PERIOD_MIN - (mins % PERIOD_MIN);
            pct = (mins / total) * 100;
            title.textContent = 'Period ' + p + ' of ' + PERIODS;
            sub.textContent = left + ' min until the next bell.';
        }
        fill.style.width = pct + '%';
    }
    update();
    setInterval(update, 30000);
})();

/* Skeleton loader: reveal the dashboard once the page has loaded
   (shown for at least 600 ms so it never just flashes). */
(function () {
    const started = Date.now();
    function reveal() {
        const wait = Math.max(0, 600 - (Date.now() - started));
        setTimeout(function () {
            const sk = document.getElementById('dashSkeleton');
            const real = document.getElementById('dashReal');
            if (sk) sk.remove();
            if (real) { real.classList.remove('is-loading'); real.classList.add('reveal'); }
        }, wait);
    }
    if (document.readyState === 'complete') reveal();
    else window.addEventListener('load', reveal);
})();

/* Count-up numbers: run once when the skeleton disappears */
(function () {
    const targets = document.querySelectorAll('.kpi-value, .overview-item strong, .legend-row strong, .donut-total strong');
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let done = false;

    function run() {
        if (done) return; done = true;
        targets.forEach(function (el) {
            const end = parseInt(el.textContent.replace(/[^0-9]/g, ''), 10) || 0;
            if (reduce || end === 0) return;
            const t0 = performance.now(), dur = 900;
            (function step(now) {
                const p = Math.min((now - t0) / dur, 1);
                el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3))).toLocaleString();
                if (p < 1) requestAnimationFrame(step);
            })(t0);
        });
    }

    const root = document.documentElement;
    if (!root.classList.contains('is-loading')) { run(); return; }
    new MutationObserver(function (m, obs) {
        if (!root.classList.contains('is-loading')) { obs.disconnect(); run(); }
    }).observe(root, { attributes: true, attributeFilter: ['class'] });
})();
</script>
@endpush

@endsection