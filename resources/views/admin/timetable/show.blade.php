
@extends('layouts.app')

@section('title', 'Timetable Details')
@section('page-title', 'Timetable Details')

@section('content')

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .timetable-show-page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
        min-height: calc(100vh - 80px);
    }

    .show-header {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        border-radius: 18px;
        padding: 26px 30px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        box-shadow: 0 10px 30px rgba(20, 124, 245, .15);
    }

    .show-header h2 {
        margin: 0 0 6px;
        font-size: 25px;
        font-weight: 800;
    }

    .show-header p {
        margin: 0;
        font-size: 13px;
        opacity: .9;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .header-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 11px 17px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .back-btn {
        background: rgba(255, 255, 255, .16);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .25);
    }

    .back-btn:hover {
        background: #fff;
        color: #147cf5;
        transform: translateY(-2px);
    }

    .edit-btn {
        background: #fff;
        color: #6c63ff;
    }

    .edit-btn:hover {
        color: #5048d8;
        transform: translateY(-2px);
    }

    .details-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 17px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        margin-bottom: 20px;
    }

    .details-card-header {
        padding: 20px 23px;
        border-bottom: 1px solid #edf1f6;
    }

    .details-card-header h3 {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 800;
    }

    .details-card-header p {
        margin: 5px 0 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .details-body {
        padding: 23px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .detail-item {
        padding: 16px;
        background: #f8fafc;
        border: 1px solid #edf1f6;
        border-radius: 12px;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .detail-label i {
        color: #147cf5;
        font-size: 13px;
    }

    .detail-value {
        color: #172033;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
    }

    .teacher-box {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
        background: #f8fbff;
        border: 1px solid #e5eefc;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .teacher-avatar {
        width: 52px;
        height: 52px;
        border-radius: 13px;
        background: #e7f0ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .teacher-info h4 {
        margin: 0 0 4px;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .teacher-info p {
        margin: 0;
        color: #94a3b8;
        font-size: 11px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 800;
    }

    .badge-regular {
        background: #eafaf0;
        color: #15803d;
    }

    .badge-break {
        background: #fff7e8;
        color: #b45309;
    }

    .badge-lunch {
        background: #fff1f2;
        color: #be123c;
    }

    .badge-activity {
        background: #f1edff;
        color: #6c63ff;
    }

    .footer-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .footer-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 11px 18px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .footer-back {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .footer-back:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .footer-edit {
        background: #6c63ff;
        color: #fff;
    }

    .footer-edit:hover {
        background: #574fd8;
        color: #fff;
        transform: translateY(-2px);
    }

    @media (max-width: 900px) {
        .details-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 650px) {
        .timetable-show-page {
            padding: 16px;
        }

        .show-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .footer-actions {
            flex-direction: column;
        }

        .footer-btn {
            width: 100%;
        }
    }
</style>

@php

    $hasTeacher = $timetable->teacher !== null;

    $teacherName = $hasTeacher
        ? trim(
            ($timetable->teacher->first_name ?? '') .
            ' ' .
            ($timetable->teacher->last_name ?? '')
        )
        : 'No Teacher Assigned';

    $initials = '';

    if ($hasTeacher) {
        $initials =
            strtoupper(
                substr(
                    $timetable->teacher->first_name ?? '',
                    0,
                    1
                )
            ) .
            strtoupper(
                substr(
                    $timetable->teacher->last_name ?? '',
                    0,
                    1
                )
            );
    }

    $formattedDate = $timetable->timetable_date
        ? \Carbon\Carbon::parse(
            $timetable->timetable_date
        )->format('d M Y')
        : '—';

    $startTime = $timetable->start_time
        ? \Carbon\Carbon::parse(
            $timetable->start_time
        )->format('h:i A')
        : '—';

    $endTime = $timetable->end_time
        ? \Carbon\Carbon::parse(
            $timetable->end_time
        )->format('h:i A')
        : '—';

    $typeClass = match ($timetable->period_type) {

        'Regular' => 'badge-regular',

        'Break' => 'badge-break',

        'Lunch' => 'badge-lunch',

        'Activity' => 'badge-activity',

        default => 'badge-regular',

    };

@endphp


<div class="timetable-show-page">

    {{-- HEADER --}}
    <div class="show-header">

        <div>

            <h2>
                <i class="bi bi-calendar-check me-2"></i>
                Timetable Details
            </h2>

            <p>
                View complete information for this timetable entry.
            </p>

        </div>

        <div class="header-actions">

            <a href="{{ route('admin.timetable.index') }}"
               class="header-btn back-btn">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

            <a href="{{ route('admin.timetable.edit', $timetable->id) }}"
               class="header-btn edit-btn">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

        </div>

    </div>


    {{-- TEACHER INFORMATION --}}
    <div class="details-card">

        <div class="details-card-header">

            <h3>
                Teacher Information
            </h3>

            <p>
                Teacher assigned to this timetable entry.
            </p>

        </div>

        <div class="details-body">

            <div class="teacher-box">

                <div class="teacher-avatar">

                    @if($hasTeacher)
                        {{ $initials }}
                    @else
                        —
                    @endif

                </div>

                <div class="teacher-info">

                    <h4>
                        {{ $teacherName }}
                    </h4>

                    <p>

                        Teacher ID:
                        {{ $hasTeacher
                            ? ($timetable->teacher->teacher_id ?? 'Not Available')
                            : 'Not Assigned'
                        }}

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- TIMETABLE INFORMATION --}}
    <div class="details-card">

        <div class="details-card-header">

            <h3>
                Timetable Information
            </h3>

            <p>
                Schedule, class, subject and period details.
            </p>

        </div>


        <div class="details-body">

            <div class="details-grid">


                {{-- ACADEMIC YEAR --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-calendar3"></i>

                        Academic Year

                    </div>

                    <div class="detail-value">

                        {{ $timetable->academic_year ?: '—' }}

                    </div>

                </div>


                {{-- DATE --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-calendar-date"></i>

                        Date

                    </div>

                    <div class="detail-value">

                        {{ $formattedDate }}

                    </div>

                </div>


                {{-- DAY --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-calendar-week"></i>

                        Day

                    </div>

                    <div class="detail-value">

                        {{ $timetable->day ?: '—' }}

                    </div>

                </div>


                {{-- PERIOD --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-list-ol"></i>

                        Period

                    </div>

                    <div class="detail-value">

                        Period {{ $timetable->period_number ?? '—' }}

                    </div>

                </div>


                {{-- PERIOD TYPE --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-bookmark"></i>

                        Period Type

                    </div>

                    <div class="detail-value">

                        <span class="badge {{ $typeClass }}">

                            {{ $timetable->period_type ?: '—' }}

                        </span>

                    </div>

                </div>


                {{-- CLASS --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-building"></i>

                        Class

                    </div>

                    <div class="detail-value">

                        {{ $timetable->class ?: '—' }}

                    </div>

                </div>


                {{-- SECTION --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-people"></i>

                        Section

                    </div>

                    <div class="detail-value">

                        {{ $timetable->section ?: '—' }}

                    </div>

                </div>


                {{-- SUBJECT --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-book"></i>

                        Subject

                    </div>

                    <div class="detail-value">

                        {{ $timetable->subject ?: '—' }}

                    </div>

                </div>


                {{-- SUBJECT TYPE --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-tag"></i>

                        Subject Type

                    </div>

                    <div class="detail-value">

                        {{ $timetable->subject_type ?: '—' }}

                    </div>

                </div>


                {{-- START TIME --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-clock"></i>

                        Start Time

                    </div>

                    <div class="detail-value">

                        {{ $startTime }}

                    </div>

                </div>


                {{-- END TIME --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-clock-history"></i>

                        End Time

                    </div>

                    <div class="detail-value">

                        {{ $endTime }}

                    </div>

                </div>


                {{-- ROOM --}}
                <div class="detail-item">

                    <div class="detail-label">

                        <i class="bi bi-door-open"></i>

                        Room

                    </div>

                    <div class="detail-value">

                        {{ $timetable->room ?: '—' }}

                    </div>

                </div>


            </div>

        </div>

    </div>


    {{-- FOOTER ACTIONS --}}
    <div class="footer-actions">

        <a href="{{ route('admin.timetable.index') }}"
           class="footer-btn footer-back">

            <i class="bi bi-arrow-left"></i>

            Back to Timetable

        </a>


        <a href="{{ route('admin.timetable.edit', $timetable->id) }}"
           class="footer-btn footer-edit">

            <i class="bi bi-pencil"></i>

            Edit Timetable

        </a>

    </div>

</div>

@endsection
