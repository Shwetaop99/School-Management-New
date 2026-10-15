@extends('layouts.app')

@section('title', 'View Timetable')
@section('page-title', 'View Timetable')

@section('content')

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
>

@php
    $teacher = $timetable->teacher ?? null;

    $teacherName = trim(
        ($teacher->first_name ?? '') . ' ' .
        ($teacher->middle_name ?? '') . ' ' .
        ($teacher->last_name ?? '')
    );

    $teacherName = $teacherName ?: 'Not Assigned';

    $teacherInitials = strtoupper(
        substr($teacher->first_name ?? 'T', 0, 1) .
        substr($teacher->last_name ?? '', 0, 1)
    );

    $teacherInitials = $teacherInitials ?: 'T';

    $periodType = strtolower(trim($timetable->period_type ?? 'regular'));

    $periodStyles = [
        'regular'  => [
            'label' => 'Regular class',
            'class' => 'type-blue',
            'icon'  => 'bi-book-fill',
        ],
        'break'    => [
            'label' => 'Break',
            'class' => 'type-amber',
            'icon'  => 'bi-pause-circle-fill',
        ],
        'lunch'    => [
            'label' => 'Lunch',
            'class' => 'type-green',
            'icon'  => 'bi-cup-hot-fill',
        ],
        'activity' => [
            'label' => 'Activity',
            'class' => 'type-violet',
            'icon'  => 'bi-stars',
        ],
    ];

    $periodMeta = $periodStyles[$periodType] ?? $periodStyles['regular'];

    $dateValue = !empty($timetable->timetable_date)
        ? \Carbon\Carbon::parse($timetable->timetable_date)
        : null;

    $startTime = !empty($timetable->start_time)
        ? \Carbon\Carbon::parse($timetable->start_time)
        : null;

    $endTime = !empty($timetable->end_time)
        ? \Carbon\Carbon::parse($timetable->end_time)
        : null;

    $duration = null;

    if ($startTime && $endTime) {
        $minutes = $startTime->diffInMinutes($endTime);

        if ($minutes >= 60) {
            $hours = intdiv($minutes, 60);
            $remainingMinutes = $minutes % 60;

            $duration = $hours . ' hr' . ($hours > 1 ? 's' : '');

            if ($remainingMinutes > 0) {
                $duration .= ' ' . $remainingMinutes . ' min';
            }
        } else {
            $duration = $minutes . ' min';
        }
    }

    $classLabel = trim(
        'Class ' . ($timetable->class ?: '—') .
        ($timetable->section ? ' · Section ' . $timetable->section : '')
    );
@endphp

<style>
    /* =========================================================
       Page foundation
       ========================================================= */

    .timetable-page{
        max-width:1500px;
        margin:0 auto;
        padding:2px 0 30px;
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
        border:1px solid transparent;
        border-radius:8px;
        cursor:pointer;
        font-family:inherit;
        font-size:13px;
        font-weight:500;
        line-height:1;
        text-decoration:none;
        white-space:nowrap;
        transition:background .16s ease, border-color .16s ease, transform .16s ease;
    }

    .btn-solid{
        color:#fff;
        background:var(--ink);
        border-color:var(--ink);
    }

    .btn-solid:hover{
        color:#fff;
        background:var(--ink-2);
        transform:translateY(-1px);
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

    .c-blue{--c:#2b5d8a}
    .c-green{--c:#3a9d6b}
    .c-red{--c:#e4572e}
    .c-amber{--c:#d58a0b}
    .c-violet{--c:#7a4fb5}

    /* =========================================================
       Diary-style page header
       ========================================================= */

    .timetable-hero{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:20px;
        flex-wrap:wrap;
        position:relative;
        overflow:hidden;
        padding:23px 26px 23px 54px;
        margin-bottom:21px;
    }

    .timetable-hero::before{
        content:"";
        position:absolute;
        top:0;
        bottom:0;
        left:30px;
        width:3px;
        background:rgba(228,87,46,.58);
    }

    .timetable-hero h1{
        margin:0 0 6px;
        color:var(--ink);
        font-family:var(--serif);
        font-size:26px;
        font-weight:700;
    }

    .timetable-hero p{
        margin:0;
        color:var(--muted);
        font-size:13px;
    }

    .hero-labels{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        margin-top:13px;
    }

    .hero-chip{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:5px 10px;
        border-radius:6px;
        font-size:11.5px;
        font-weight:600;
        white-space:nowrap;
    }

    .hero-chip.class-chip{
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 10%, #fff);
    }

    .hero-chip.subject-chip{
        color:#7a4fb5;
        background:color-mix(in srgb, #7a4fb5 10%, #fff);
    }

    .hero-chip.period-chip{
        color:#2b7a52;
        background:color-mix(in srgb, #3a9d6b 12%, #fff);
    }

    .hero-actions{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }

    /* =========================================================
       Folder-style overview cards
       ========================================================= */

    .overview-grid{
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr));
        gap:18px;
        margin-bottom:21px;
    }

    .overview-card{
        position:relative;
        display:flex;
        flex-direction:column;
        gap:13px;
        min-height:126px;
        padding:20px 20px 16px;
        margin-top:10px;
        border-top:4px solid var(--c);
        transition:transform .18s ease, box-shadow .18s ease;
    }

    .overview-card:hover{
        transform:translateY(-4px);
        box-shadow:0 13px 24px rgba(29,58,51,.12);
    }

    .overview-tab{
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

    .overview-top{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
    }

    .overview-value{
        max-width:calc(100% - 54px);
        overflow:hidden;
        color:var(--ink);
        font-family:var(--serif);
        font-size:21px;
        font-weight:700;
        line-height:1.15;
        text-overflow:ellipsis;
        white-space:nowrap;
    }

    .overview-icon{
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

    .overview-note{
        padding-top:11px;
        color:var(--muted);
        border-top:1px dashed #d8d1bf;
        font-size:12px;
    }

    /* =========================================================
       Main information layout
       ========================================================= */

    .content-grid{
        display:grid;
        grid-template-columns:minmax(0, 1.65fr) minmax(300px, .85fr);
        gap:20px;
        align-items:start;
    }

    .panel{
        overflow:hidden;
    }

    .panel-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        padding:15px 20px;
        border-bottom:1px dashed #d8d1bf;
    }

    .panel-heading{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .panel-heading-icon{
        display:flex;
        align-items:center;
        justify-content:center;
        width:36px;
        height:36px;
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 12%, #fff);
        border-radius:10px;
        font-size:17px;
    }

    .panel-head h2{
        margin:0;
        color:var(--ink);
        font-family:var(--serif);
        font-size:17px;
        font-weight:700;
    }

    .panel-head p{
        margin:2px 0 0;
        color:var(--muted);
        font-size:11.5px;
    }

    /* =========================================================
       Details table
       ========================================================= */

    .details-list{
        display:grid;
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .detail-row{
        display:flex;
        align-items:flex-start;
        gap:12px;
        min-height:85px;
        padding:17px 20px;
        border-bottom:1px solid #f0ebe0;
    }

    .detail-row:nth-child(odd){
        border-right:1px solid #f0ebe0;
    }

    .detail-icon{
        display:flex;
        align-items:center;
        justify-content:center;
        width:34px;
        height:34px;
        flex-shrink:0;
        color:var(--c);
        background:color-mix(in srgb, var(--c) 12%, #fff);
        border-radius:10px;
        font-size:15px;
    }

    .detail-content{
        min-width:0;
        padding-top:1px;
    }

    .detail-label{
        display:block;
        margin-bottom:5px;
        color:var(--muted);
        font-size:10.5px;
        font-weight:700;
        letter-spacing:.45px;
        text-transform:uppercase;
    }

    .detail-value{
        overflow:hidden;
        color:var(--ink);
        font-size:13.5px;
        font-weight:600;
        line-height:1.4;
        text-overflow:ellipsis;
    }

    .detail-value.muted-value{
        color:var(--muted);
        font-weight:500;
    }

    .teacher-value{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .teacher-avatar{
        display:flex;
        align-items:center;
        justify-content:center;
        width:32px;
        height:32px;
        flex-shrink:0;
        color:#7a4fb5;
        background:color-mix(in srgb, #7a4fb5 15%, #fff);
        border-radius:50%;
        font-size:11px;
        font-weight:800;
    }

    .type-badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:5px 9px;
        border-radius:6px;
        font-size:11.5px;
        font-weight:700;
        white-space:nowrap;
    }

    .type-blue{
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 12%, #fff);
    }

    .type-amber{
        color:#8a5600;
        background:color-mix(in srgb, #d58a0b 15%, #fff);
    }

    .type-green{
        color:#2b7a52;
        background:color-mix(in srgb, #3a9d6b 13%, #fff);
    }

    .type-violet{
        color:#7a4fb5;
        background:color-mix(in srgb, #7a4fb5 12%, #fff);
    }

    /* =========================================================
       Schedule panel
       ========================================================= */

    .schedule-body{
        padding:20px;
    }

    .time-card{
        position:relative;
        overflow:hidden;
        padding:20px;
        margin-bottom:16px;
        color:#e9efe2;
        background:var(--ink);
        border-radius:10px;
        background-image:radial-gradient(
            ellipse at 20% 0,
            rgba(255,255,255,.09),
            transparent 62%
        );
    }

    .time-card::after{
        content:"";
        position:absolute;
        top:-28px;
        right:-22px;
        width:120px;
        height:120px;
        border:2px solid rgba(255,255,255,.08);
        border-radius:50%;
    }

    .time-card-label{
        position:relative;
        z-index:1;
        margin-bottom:7px;
        color:var(--chalk);
        font-family:var(--hand);
        font-size:20px;
        line-height:1;
    }

    .time-card-value{
        position:relative;
        z-index:1;
        color:#fff;
        font-family:var(--serif);
        font-size:24px;
        font-weight:700;
        line-height:1.2;
    }

    .time-card-note{
        position:relative;
        z-index:1;
        margin-top:7px;
        color:#c5d0bd;
        font-size:12px;
    }

    .timeline{
        position:relative;
        padding-left:18px;
    }

    .timeline::before{
        content:"";
        position:absolute;
        top:8px;
        bottom:8px;
        left:5px;
        width:2px;
        background:#d8d1bf;
    }

    .timeline-item{
        position:relative;
        padding:0 0 17px 16px;
    }

    .timeline-item:last-child{
        padding-bottom:0;
    }

    .timeline-dot{
        position:absolute;
        top:4px;
        left:-18px;
        width:12px;
        height:12px;
        background:var(--c);
        border:3px solid #fff;
        border-radius:50%;
        box-shadow:0 0 0 1px color-mix(in srgb, var(--c) 35%, #fff);
    }

    .timeline-label{
        display:block;
        color:var(--muted);
        font-size:10.5px;
        font-weight:700;
        letter-spacing:.35px;
        text-transform:uppercase;
    }

    .timeline-value{
        display:block;
        margin-top:3px;
        color:var(--ink);
        font-size:13.5px;
        font-weight:600;
    }

    .room-card{
        display:flex;
        align-items:center;
        gap:12px;
        padding:14px;
        margin-top:18px;
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 7%, #fff);
        border:1px dashed color-mix(in srgb, #2b5d8a 38%, #fff);
        border-radius:9px;
    }

    .room-card i{
        display:flex;
        align-items:center;
        justify-content:center;
        width:34px;
        height:34px;
        flex-shrink:0;
        color:#2b5d8a;
        background:color-mix(in srgb, #2b5d8a 14%, #fff);
        border-radius:9px;
        font-size:16px;
    }

    .room-card small{
        display:block;
        margin-bottom:2px;
        color:var(--muted);
        font-size:10.5px;
        font-weight:700;
        letter-spacing:.35px;
        text-transform:uppercase;
    }

    .room-card strong{
        color:var(--ink);
        font-size:13.5px;
    }

    /* =========================================================
       Footer actions
       ========================================================= */

    .panel-actions{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        padding:15px 20px;
        background:#fdfcf8;
        border-top:1px dashed #d8d1bf;
    }

    .panel-actions-note{
        color:var(--muted);
        font-size:12px;
    }

    .action-buttons{
        display:flex;
        align-items:center;
        gap:9px;
    }

    /* =========================================================
       Responsive styles
       ========================================================= */

    @media (max-width:1200px){
        .overview-grid{
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .content-grid{
            grid-template-columns:1fr;
        }
    }

    @media (max-width:720px){
        .timetable-hero{
            padding:20px 18px 20px 43px;
        }

        .timetable-hero::before{
            left:23px;
        }

        .timetable-hero h1{
            font-size:22px;
        }

        .details-list{
            grid-template-columns:1fr;
        }

        .detail-row:nth-child(odd){
            border-right:none;
        }

        .panel-actions{
            align-items:stretch;
            flex-direction:column;
        }

        .action-buttons{
            width:100%;
        }

        .action-buttons .btn-solid,
        .action-buttons .btn-line{
            flex:1;
        }
    }

    @media (max-width:520px){
        .overview-grid{
            grid-template-columns:1fr;
        }

        .hero-actions{
            width:100%;
        }

        .hero-actions > *{
            flex:1;
        }

        .hero-actions .btn-line{
            width:100%;
        }

        .month-display{
            justify-content:center;
        }
    }

    @media (prefers-reduced-motion:reduce){
        .overview-card,
        .btn-solid,
        .btn-line{
            transition:none;
        }
    }
</style>

<div class="timetable-page">

    {{-- =========================================================
       Header
       ========================================================= --}}
    <section class="paper timetable-hero">
        <div>
            <h1>
                <i class="bi bi-calendar3"></i>
                Timetable Details
            </h1>

            <p>
                Review the complete schedule information for this timetable entry.
            </p>

            <div class="hero-labels">
                <span class="hero-chip class-chip">
                    <i class="bi bi-mortarboard-fill"></i>
                    {{ $classLabel }}
                </span>

                @if(!empty($timetable->subject))
                    <span class="hero-chip subject-chip">
                        <i class="bi bi-book-fill"></i>
                        {{ $timetable->subject }}
                    </span>
                @endif

                @if($timetable->period_number)
                    <span class="hero-chip period-chip">
                        <i class="bi bi-clock-fill"></i>
                        Period {{ $timetable->period_number }}
                    </span>
                @endif
            </div>
        </div>

        <div class="hero-actions">
            <a
                href="{{ route('admin.timetable.index') }}"
                class="btn-line"
            >
                <i class="bi bi-arrow-left"></i>
                Back to timetable
            </a>

            <a
                href="{{ route('admin.timetable.edit', $timetable->id) }}"
                class="btn-solid"
            >
                <i class="bi bi-pencil-square"></i>
                Edit entry
            </a>
        </div>
    </section>

    {{-- =========================================================
       Quick overview cards
       ========================================================= --}}
    <section class="overview-grid">
        <article class="paper overview-card c-blue">
            <span class="overview-tab">Schedule date</span>

            <div class="overview-top">
                <div class="overview-value">
                    {{ $dateValue ? $dateValue->format('d M Y') : 'Not set' }}
                </div>

                <div class="overview-icon">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
            </div>

            <div class="overview-note">
                {{ $dateValue ? $dateValue->format('l') : 'No timetable date available' }}
            </div>
        </article>

        <article class="paper overview-card c-green">
            <span class="overview-tab">Class time</span>

            <div class="overview-top">
                <div class="overview-value">
                    {{ $startTime ? $startTime->format('h:i A') : '—' }}
                </div>

                <div class="overview-icon">
                    <i class="bi bi-clock-fill"></i>
                </div>
            </div>

            <div class="overview-note">
                {{ $endTime ? 'Ends at ' . $endTime->format('h:i A') : 'End time not set' }}
            </div>
        </article>

        <article class="paper overview-card c-violet">
            <span class="overview-tab">Subject</span>

            <div class="overview-top">
                <div class="overview-value" title="{{ $timetable->subject ?: 'Not assigned' }}">
                    {{ $timetable->subject ?: 'Not assigned' }}
                </div>

                <div class="overview-icon">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>

            <div class="overview-note">
                {{ $timetable->subject_type ?: 'Subject type not specified' }}
            </div>
        </article>

        <article class="paper overview-card c-amber">
            <span class="overview-tab">Teacher</span>

            <div class="overview-top">
                <div class="overview-value" title="{{ $teacherName }}">
                    {{ $teacherName }}
                </div>

                <div class="overview-icon">
                    <i class="bi bi-person-workspace"></i>
                </div>
            </div>

            <div class="overview-note">
                {{ $timetable->room ? 'Room ' . $timetable->room : 'Room not assigned' }}
            </div>
        </article>
    </section>

    {{-- =========================================================
       Details and schedule
       ========================================================= --}}
    <section class="content-grid">

        {{-- Main timetable information --}}
        <article class="paper panel">
            <div class="panel-head">
                <div class="panel-heading">
                    <span class="panel-heading-icon">
                        <i class="bi bi-info-circle-fill"></i>
                    </span>

                    <div>
                        <h2>Timetable information</h2>
                        <p>Academic, class, subject, and teacher details</p>
                    </div>
                </div>

                <span class="type-badge {{ $periodMeta['class'] }}">
                    <i class="bi {{ $periodMeta['icon'] }}"></i>
                    {{ $periodMeta['label'] }}
                </span>
            </div>

            <div class="details-list">

                {{-- Teacher --}}
                <div class="detail-row c-violet">
                    <span class="detail-icon">
                        <i class="bi bi-person-workspace"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Teacher</span>

                        <div class="detail-value teacher-value">
                            <span class="teacher-avatar">{{ $teacherInitials }}</span>
                            <span>{{ $teacherName }}</span>
                        </div>
                    </div>
                </div>

                {{-- Academic year --}}
                <div class="detail-row c-blue">
                    <span class="detail-icon">
                        <i class="bi bi-calendar-range-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Academic year</span>
                        <div class="detail-value">
                            {{ $timetable->academic_year ?: '—' }}
                        </div>
                    </div>
                </div>

                {{-- Date --}}
                <div class="detail-row c-green">
                    <span class="detail-icon">
                        <i class="bi bi-calendar-event-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Timetable date</span>
                        <div class="detail-value">
                            {{ $dateValue ? $dateValue->format('d F Y') : '—' }}
                        </div>
                    </div>
                </div>

                {{-- Day --}}
                <div class="detail-row c-amber">
                    <span class="detail-icon">
                        <i class="bi bi-sun-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Day</span>
                        <div class="detail-value">
                            {{ $timetable->day ?: ($dateValue ? $dateValue->format('l') : '—') }}
                        </div>
                    </div>
                </div>

                {{-- Class --}}
                <div class="detail-row c-blue">
                    <span class="detail-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Class</span>
                        <div class="detail-value">
                            {{ $timetable->class ?: '—' }}
                        </div>
                    </div>
                </div>

                {{-- Section --}}
                <div class="detail-row c-green">
                    <span class="detail-icon">
                        <i class="bi bi-diagram-3-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Section</span>
                        <div class="detail-value">
                            {{ $timetable->section ?: '—' }}
                        </div>
                    </div>
                </div>

                {{-- Subject --}}
                <div class="detail-row c-violet">
                    <span class="detail-icon">
                        <i class="bi bi-book-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Subject</span>
                        <div class="detail-value">
                            {{ $timetable->subject ?: '—' }}
                        </div>
                    </div>
                </div>

                {{-- Subject type --}}
                <div class="detail-row c-amber">
                    <span class="detail-icon">
                        <i class="bi bi-tags-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Subject type</span>
                        <div class="detail-value">
                            {{ $timetable->subject_type ?: '—' }}
                        </div>
                    </div>
                </div>

                {{-- Lecture type --}}
                <div class="detail-row c-red">
                    <span class="detail-icon">
                        <i class="bi bi-easel2-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Lecture type</span>
                        <div class="detail-value">
                            {{ !empty($timetable->lecture_type)
                                ? ucfirst($timetable->lecture_type)
                                : '—' }}
                        </div>
                    </div>
                </div>

                {{-- Period type --}}
                <div class="detail-row c-blue">
                    <span class="detail-icon">
                        <i class="bi bi-bookmark-star-fill"></i>
                    </span>

                    <div class="detail-content">
                        <span class="detail-label">Period type</span>

                        <div class="detail-value">
                            <span class="type-badge {{ $periodMeta['class'] }}">
                                <i class="bi {{ $periodMeta['icon'] }}"></i>
                                {{ $periodMeta['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-actions">
                <span class="panel-actions-note">
                    Last review this timetable entry before making changes.
                </span>

                <div class="action-buttons">
                    <a
                        href="{{ route('admin.timetable.index') }}"
                        class="btn-line"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                    <a
                        href="{{ route('admin.timetable.edit', $timetable->id) }}"
                        class="btn-solid"
                    >
                        <i class="bi bi-pencil-square"></i>
                        Edit entry
                    </a>
                </div>
            </div>
        </article>

        {{-- Schedule panel --}}
        <aside class="paper panel">
            <div class="panel-head">
                <div class="panel-heading">
                    <span class="panel-heading-icon" style="color:#3a9d6b;background:color-mix(in srgb,#3a9d6b 12%,#fff);">
                        <i class="bi bi-clock-history"></i>
                    </span>

                    <div>
                        <h2>Schedule</h2>
                        <p>Period timing and location</p>
                    </div>
                </div>
            </div>

            <div class="schedule-body">
                <div class="time-card">
                    <div class="time-card-label">
                        {{ $timetable->period_number ? 'Period ' . $timetable->period_number : 'Scheduled session' }}
                    </div>

                    <div class="time-card-value">
                        {{ $startTime ? $startTime->format('h:i A') : '—' }}
                        <span style="opacity:.55;font-weight:400">–</span>
                        {{ $endTime ? $endTime->format('h:i A') : '—' }}
                    </div>

                    <div class="time-card-note">
                        {{ $duration ? 'Duration: ' . $duration : 'Time duration is not available.' }}
                    </div>
                </div>

                <div class="timeline">
                    <div class="timeline-item c-blue">
                        <span class="timeline-dot"></span>
                        <span class="timeline-label">Starts</span>
                        <span class="timeline-value">
                            {{ $startTime ? $startTime->format('h:i A') : 'Not set' }}
                        </span>
                    </div>

                    <div class="timeline-item c-green">
                        <span class="timeline-dot"></span>
                        <span class="timeline-label">Ends</span>
                        <span class="timeline-value">
                            {{ $endTime ? $endTime->format('h:i A') : 'Not set' }}
                        </span>
                    </div>

                    <div class="timeline-item c-violet">
                        <span class="timeline-dot"></span>
                        <span class="timeline-label">Classroom activity</span>
                        <span class="timeline-value">
                            {{ $periodMeta['label'] }}
                        </span>
                    </div>
                </div>

                <div class="room-card">
                    <i class="bi bi-door-open-fill"></i>

                    <div>
                        <small>Assigned room</small>
                        <strong>{{ $timetable->room ?: 'Room not assigned' }}</strong>
                    </div>
                </div>
            </div>
        </aside>
    </section>
</div>

@endsection