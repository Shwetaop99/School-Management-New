@extends('layouts.app')

@section('title', 'Student Attendance')
@section('page-title', 'Student Attendance')

@section('content')

<style>
    .att-page{max-width:1500px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* ---------- Hero ---------- */
    .att-hero{
        display:grid;
        grid-template-columns:minmax(0,1.5fr) minmax(0,1fr);
        margin-bottom:22px;
        overflow:hidden;
        position:relative;
    }
    .att-hero::before{
        content:"";
        position:absolute;
        left:34px;top:0;bottom:0;
        width:2px;
        background:rgba(228,87,46,.35);
    }
    .att-hero-left{padding:26px 28px 26px 56px}
    .att-hero-left h1{
        font-family:var(--serif);
        font-size:28px;
        font-weight:700;
        color:var(--ink);
        margin:0 0 6px;
    }
    .att-hero-left p{
        color:var(--muted);
        margin:0 0 16px;
        font-size:14px;
        line-height:1.5;
    }
    .att-hero-meta{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-bottom:18px;
    }
    .meta-chip{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:5px 11px;
        border-radius:6px;
        font-size:12px;
        font-weight:600;
        background:color-mix(in srgb,#2b5d8a 10%,#fff);
        color:#2b5d8a;
    }
    .meta-chip.green{background:color-mix(in srgb,#3a9d6b 12%,#fff);color:#3a9d6b}
    .meta-chip.red{background:color-mix(in srgb,#e4572e 12%,#fff);color:#e4572e}

    .att-hero-right{
        background:var(--ink);
        color:#e9efe2;
        padding:26px 28px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        gap:10px;
        background-image:radial-gradient(ellipse at 20% 0,rgba(255,255,255,.09),transparent 60%);
    }
    .att-hero-right .label{
        font-family:var(--hand);
        font-size:22px;
        color:var(--chalk);
        line-height:1;
    }
    .att-hero-right .month{
        font-family:var(--serif);
        font-size:26px;
        font-weight:700;
        color:#fff;
        line-height:1.15;
    }
    .att-hero-right .sub{
        font-size:13px;
        color:#c5d0bd;
    }

    /* ---------- KPI folder tabs ---------- */
    .kpi-grid{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:18px;
        margin-bottom:22px;
    }
    .kpi{
        transition:transform .22s ease,box-shadow .22s ease;
        position:relative;
        padding:22px 22px 16px;
        margin-top:10px;
        border-top:4px solid var(--c);
        display:flex;
        flex-direction:column;
        gap:14px;
    }
    .kpi:hover{
        transform:translateY(-8px);
        box-shadow:0 16px 28px rgba(29,58,51,.16);
    }
    .kpi-tab{
        position:absolute;
        top:-24px;left:16px;
        padding:3px 12px;
        background:var(--c);
        color:#fff;
        font-size:12px;
        font-weight:600;
        border-radius:7px 7px 0 0;
    }
    .kpi-top{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
    }
    .kpi-value{
        font-family:var(--serif);
        font-size:34px;
        font-weight:700;
        line-height:1;
        color:var(--ink);
    }
    .kpi-icon{
        width:46px;height:46px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:20px;
        flex-shrink:0;
        background:color-mix(in srgb,var(--c) 14%,#fff);
        color:var(--c);
    }
    .kpi-label{
        padding-top:12px;
        border-top:1px dashed #d8d1bf;
        font-size:13px;
        color:var(--muted);
    }
    .c-blue{--c:#2b5d8a}
    .c-amber{--c:#d58a0b}
    .c-green{--c:#3a9d6b}
    .c-red{--c:#e4572e}

    /* ---------- Register panel ---------- */
    .panel{overflow:hidden;margin-bottom:22px}
    .panel-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        padding:15px 22px;
        border-bottom:1px dashed #d8d1bf;
        flex-wrap:wrap;
    }
    .panel-head h3{
        font-family:var(--serif);
        font-size:17px;
        font-weight:700;
        color:var(--ink);
        margin:0;
    }
    .panel-head span{
        font-size:12.5px;
        color:var(--muted);
    }

    /* ---------- Table ---------- */
    .table-wrap{width:100%;overflow-x:auto}
    .att-table{
        width:100%;
        min-width:1100px;
        border-collapse:collapse;
    }
    .att-table thead th{
        padding:13px 16px;
        background:#f8f6f0;
        border-bottom:1px solid #e6e0d2;
        color:var(--muted);
        font-size:11px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.4px;
        white-space:nowrap;
        text-align:left;
    }
    .att-table tbody td{
        padding:14px 16px;
        border-bottom:1px solid #f0ebe0;
        color:var(--text);
        font-size:13px;
        vertical-align:middle;
    }
    .att-table tbody tr:hover td{background:#fffdf6}
    .att-table tbody tr:last-child td{border-bottom:none}

    .class-cell{
        display:flex;
        align-items:center;
        gap:11px;
    }
    .class-icon{
        width:40px;height:40px;
        min-width:40px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:10px;
        background:color-mix(in srgb,#2b5d8a 12%,#fff);
        color:#2b5d8a;
        font-size:17px;
    }
    .class-name{
        font-weight:700;
        color:var(--ink);
        font-size:14px;
    }

    .year-badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:5px 10px;
        border-radius:6px;
        background:color-mix(in srgb,#7a4fb5 10%,#fff);
        color:#7a4fb5;
        font-size:12px;
        font-weight:600;
    }

    .section-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:36px;
        padding:5px 10px;
        border-radius:6px;
        background:color-mix(in srgb,#2b5d8a 10%,#fff);
        color:#2b5d8a;
        font-size:12px;
        font-weight:700;
    }

    .count-cell{
        display:inline-flex;
        align-items:center;
        gap:6px;
        font-weight:700;
        font-size:13px;
    }
    .count-cell i{font-size:15px}
    .count-students{color:var(--ink)}
    .count-present{color:#3a9d6b}
    .count-absent{color:#e4572e}

    .teacher-cell{
        display:flex;
        align-items:center;
        gap:10px;
    }
    .teacher-avatar{
        width:34px;height:34px;
        min-width:34px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:50%;
        background:color-mix(in srgb,#7a4fb5 14%,#fff);
        color:#7a4fb5;
        font-size:11px;
        font-weight:700;
    }
    .teacher-name{
        font-size:12.5px;
        font-weight:600;
        color:var(--ink);
        max-width:160px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
    .teacher-name.muted{
        color:var(--muted);
        font-style:italic;
        font-weight:500;
    }

    .btn-mark{
        display:inline-flex;
        align-items:center;
        gap:7px;
        padding:8px 14px;
        border-radius:8px;
        background:var(--ink);
        color:#fff;
        font-size:12.5px;
        font-weight:600;
        text-decoration:none;
        border:1px solid var(--ink);
        transition:background .15s;
        white-space:nowrap;
    }
    .btn-mark:hover{
        background:var(--ink-2);
        color:#fff;
    }

    /* ---------- Empty ---------- */
    .empty{
        padding:60px 20px;
        text-align:center;
    }
    .empty i{
        font-size:32px;
        color:var(--muted);
        display:block;
        margin-bottom:10px;
    }
    .empty h4{
        font-family:var(--serif);
        font-size:17px;
        font-weight:700;
        color:var(--ink);
        margin:0 0 6px;
    }
    .empty p{
        margin:0;
        color:var(--muted);
        font-size:13px;
    }

    /* ---------- Responsive ---------- */
    @media (max-width:1200px){
        .kpi-grid{grid-template-columns:repeat(2,1fr)}
    }
    @media (max-width:900px){
        .att-hero{grid-template-columns:1fr}
        .att-hero-left{padding:22px 20px 22px 44px}
        .att-hero::before{left:24px}
    }
    @media (max-width:650px){
        .kpi-grid{grid-template-columns:1fr}
        .kpi{margin-top:18px}
        .att-hero-left h1{font-size:23px}
    }
</style>

@php
    $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
@endphp

<div class="att-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}
    <section class="paper att-hero">
        <div class="att-hero-left">
            <h1>Student Attendance</h1>
            <p>
                Manage daily student attendance by class and section.
                View present and absent counts and mark attendance for any class.
            </p>

            <div class="att-hero-meta">
                <span class="meta-chip">
                    <i class="bi bi-layers"></i>
                    {{ $classCards->count() }} Classes
                </span>
                <span class="meta-chip green">
                    <i class="bi bi-check-circle"></i>
                    {{ $totalPresent }} Present
                </span>
                <span class="meta-chip red">
                    <i class="bi bi-x-circle"></i>
                    {{ $totalAbsent }} Absent
                </span>
            </div>
        </div>

        <div class="att-hero-right">
            <div class="label">Attendance month</div>
            <div class="month">{{ $monthLabel }}</div>
            <div class="sub">Select a class below to mark attendance</div>
        </div>
    </section>

    {{-- =====================================================
         KPI CARDS
    ====================================================== --}}
    <div class="kpi-grid">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Classes</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $classCards->count() }}</div>
                <div class="kpi-icon"><i class="bi bi-mortarboard-fill"></i></div>
            </div>
            <div class="kpi-label">Total classes & sections</div>
        </div>

        <div class="paper kpi c-amber">
            <span class="kpi-tab">Students</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($totalStudents) }}</div>
                <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="kpi-label">Total students</div>
        </div>

        <div class="paper kpi c-green">
            <span class="kpi-tab">Present</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($totalPresent) }}</div>
                <div class="kpi-icon"><i class="bi bi-person-check-fill"></i></div>
            </div>
            <div class="kpi-label">Students present today</div>
        </div>

        <div class="paper kpi c-red">
            <span class="kpi-tab">Absent</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($totalAbsent) }}</div>
                <div class="kpi-icon"><i class="bi bi-person-x-fill"></i></div>
            </div>
            <div class="kpi-label">Students absent today</div>
        </div>
    </div>

    {{-- =====================================================
         CLASSES REGISTER
    ====================================================== --}}
    <section class="paper panel">
        <div class="panel-head">
            <h3>Classes & Sections</h3>
            <span>Select a class to mark attendance</span>
        </div>

        @if($classCards->count())
            <div class="table-wrap">
                <table class="att-table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Academic Year</th>
                            <th>Section</th>
                            <th>Students</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Class Teacher</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classCards as $card)
                            @php
                                $teacherName = trim($card->class_teacher ?? 'Not Assigned');
                                $teacherWords = preg_split('/\s+/', $teacherName);
                                $initials = '';
                                foreach (array_slice($teacherWords, 0, 2) as $word) {
                                    if (!empty($word)) {
                                        $initials .= strtoupper(substr($word, 0, 1));
                                    }
                                }
                                if (!$initials) $initials = 'NA';
                            @endphp
                            <tr>
                                <td>
                                    <div class="class-cell">
                                        <div class="class-icon">
                                            <i class="bi bi-mortarboard-fill"></i>
                                        </div>
                                        <span class="class-name">{{ $card->class ?? '-' }}</span>
                                    </div>
                                </td>

                                <td>
                                    <span class="year-badge">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $card->academic_year ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="section-badge">
                                        {{ $card->section ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="count-cell count-students">
                                        <i class="bi bi-people-fill"></i>
                                        {{ $card->student_count ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="count-cell count-present">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $card->present_count ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="count-cell count-absent">
                                        <i class="bi bi-x-circle-fill"></i>
                                        {{ $card->absent_count ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <div class="teacher-cell">
                                        <div class="teacher-avatar">{{ $initials }}</div>
                                        <span class="teacher-name {{ empty($card->class_teacher) ? 'muted' : '' }}">
                                            {{ $teacherName }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <a href="{{ route('admin.attendance.student.sheet', [
                                        'academic_year' => $card->academic_year,
                                        'class'         => $card->class,
                                        'section'       => $card->section,
                                        'month'         => $month
                                    ]) }}" class="btn-mark">
                                        <i class="bi bi-pencil-square"></i>
                                        Mark Attendance
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty">
                <i class="bi bi-mortarboard"></i>
                <h4>No Classes Found</h4>
                <p>No active students or class sections are currently available for attendance.</p>
            </div>
        @endif
    </section>

</div>

@endsection