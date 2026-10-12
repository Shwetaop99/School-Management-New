
@extends('layouts.app')

@section('title', 'View Timetable')
@section('page-title', 'View Timetable')

@section('content')

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
>

<style>
    .timetable-show-container {
        padding: 10px 0 30px;
    }

    .show-header {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 22px;
        box-shadow: 0 8px 25px rgba(20, 124, 245, 0.15);
    }

    .show-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .show-title {
        margin: 0;
        font-size: 25px;
        font-weight: 800;
    }

    .show-subtitle {
        margin: 6px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fff;
        color: #1769d1;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .back-btn:hover {
        color: #1769d1;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    }

    .details-card {
        background: #fff;
        border: 1px solid #e8eef7;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(30, 70, 110, 0.06);
    }

    .details-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #edf1f7;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .details-card-header i {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eef5ff;
        color: #147cf5;
        font-size: 18px;
    }

    .details-card-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: #1d2b3c;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .detail-item {
        padding: 18px 22px;
        border-bottom: 1px solid #edf1f7;
    }

    .detail-item:nth-child(odd) {
        border-right: 1px solid #edf1f7;
    }

    .detail-label {
        display: block;
        color: #7a8798;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .detail-value {
        color: #243447;
        font-size: 15px;
        font-weight: 700;
    }

    .teacher-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .teacher-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
    }

    .badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .badge-regular {
        background: #eaf4ff;
        color: #147cf5;
    }

    .badge-break {
        background: #fff4df;
        color: #c77a00;
    }

    .badge-lunch {
        background: #e9f8ef;
        color: #198754;
    }

    .badge-activity {
        background: #f1edff;
        color: #6c63ff;
    }

    .action-footer {
        padding: 18px 22px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #fafcff;
        border-top: 1px solid #edf1f7;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-edit {
        background: #eef5ff;
        color: #147cf5;
        border-color: #d9e8ff;
    }

    .btn-edit:hover {
        background: #147cf5;
        color: #fff;
    }

    .btn-back {
        background: #f3f5f8;
        color: #495566;
        border-color: #e1e6ed;
    }

    .btn-back:hover {
        background: #e7ebf0;
        color: #263342;
    }

    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
        }

        .detail-item:nth-child(odd) {
            border-right: none;
        }

        .show-header {
            padding: 20px;
        }

        .show-title {
            font-size: 21px;
        }
    }
</style>

<div class="timetable-show-container">

    {{-- HEADER --}}
    <div class="show-header">
        <div class="show-header-content">

            <div>
                <h1 class="show-title">
                    <i class="bi bi-calendar3"></i>
                    Timetable Details
                </h1>

                <p class="show-subtitle">
                    View complete information about this timetable entry.
                </p>
            </div>

            <a
                href="{{ route('admin.timetable.index') }}"
                class="back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Timetable
            </a>

        </div>
    </div>


    {{-- DETAILS CARD --}}
    <div class="details-card">

        <div class="details-card-header">
            <i class="bi bi-info-circle"></i>

            <h5>
                Timetable Information
            </h5>
        </div>


        <div class="details-grid">

            {{-- Teacher --}}
            <div class="detail-item">

                <span class="detail-label">
                    Teacher
                </span>

                <div class="detail-value teacher-name">

                    <div class="teacher-avatar">
                        @php
                            $teacher = $timetable->teacher;

                            $firstInitial = strtoupper(
                                substr($teacher->first_name ?? 'T', 0, 1)
                            );

                            $lastInitial = strtoupper(
                                substr($teacher->last_name ?? '', 0, 1)
                            );
                        @endphp

                        {{ $firstInitial }}{{ $lastInitial }}
                    </div>

                    <span>
                        {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: 'Not Assigned' }}
                    </span>

                </div>

            </div>


            {{-- Academic Year --}}
            <div class="detail-item">

                <span class="detail-label">
                    Academic Year
                </span>

                <div class="detail-value">
                    {{ $timetable->academic_year ?: '—' }}
                </div>

            </div>


            {{-- Date --}}
            <div class="detail-item">

                <span class="detail-label">
                    Date
                </span>

                <div class="detail-value">

                    @if(!empty($timetable->timetable_date))
                        {{ \Carbon\Carbon::parse($timetable->timetable_date)->format('d M Y') }}
                    @else
                        —
                    @endif

                </div>

            </div>


            {{-- Day --}}
            <div class="detail-item">

                <span class="detail-label">
                    Day
                </span>

                <div class="detail-value">
                    {{ $timetable->day ?: '—' }}
                </div>

            </div>


            {{-- Period --}}
            <div class="detail-item">

                <span class="detail-label">
                    Period Number
                </span>

                <div class="detail-value">

                    @if($timetable->period_number)
                        Period {{ $timetable->period_number }}
                    @else
                        —
                    @endif

                </div>

            </div>


            {{-- Period Type --}}
            <div class="detail-item">

                <span class="detail-label">
                    Period Type
                </span>

                <div class="detail-value">

                    @php
                        $periodType = strtolower($timetable->period_type ?? 'regular');
                    @endphp

                    @if($periodType === 'break')

                        <span class="badge-custom badge-break">
                            <i class="bi bi-pause-circle me-1"></i>
                            Break
                        </span>

                    @elseif($periodType === 'lunch')

                        <span class="badge-custom badge-lunch">
                            <i class="bi bi-cup-hot me-1"></i>
                            Lunch
                        </span>

                    @elseif($periodType === 'activity')

                        <span class="badge-custom badge-activity">
                            <i class="bi bi-stars me-1"></i>
                            Activity
                        </span>

                    @else

                        <span class="badge-custom badge-regular">
                            <i class="bi bi-book me-1"></i>
                            Regular
                        </span>

                    @endif

                </div>

            </div>


            {{-- Class --}}
            <div class="detail-item">

                <span class="detail-label">
                    Class
                </span>

                <div class="detail-value">
                    {{ $timetable->class ?: '—' }}
                </div>

            </div>


            {{-- Section --}}
            <div class="detail-item">

                <span class="detail-label">
                    Section
                </span>

                <div class="detail-value">
                    {{ $timetable->section ?: '—' }}
                </div>

            </div>


            {{-- Subject --}}
            <div class="detail-item">

                <span class="detail-label">
                    Subject
                </span>

                <div class="detail-value">
                    {{ $timetable->subject ?: '—' }}
                </div>

            </div>


            {{-- Subject Type --}}
            <div class="detail-item">

                <span class="detail-label">
                    Subject Type
                </span>

                <div class="detail-value">
                    {{ $timetable->subject_type ?: '—' }}
                </div>

            </div>


            {{-- Lecture Type --}}
            @if(isset($timetable->lecture_type))
                <div class="detail-item">

                    <span class="detail-label">
                        Lecture Type
                    </span>

                    <div class="detail-value">
                        {{ ucfirst($timetable->lecture_type ?: '—') }}
                    </div>

                </div>
            @endif


            {{-- Start Time --}}
            <div class="detail-item">

                <span class="detail-label">
                    Start Time
                </span>

                <div class="detail-value">

                    @if($timetable->start_time)
                        {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }}
                    @else
                        —
                    @endif

                </div>

            </div>


            {{-- End Time --}}
            <div class="detail-item">

                <span class="detail-label">
                    End Time
                </span>

                <div class="detail-value">

                    @if($timetable->end_time)
                        {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                    @else
                        —
                    @endif

                </div>

            </div>


            {{-- Room --}}
            <div class="detail-item">

                <span class="detail-label">
                    Room
                </span>

                <div class="detail-value">
                    {{ $timetable->room ?: 'Not Assigned' }}
                </div>

            </div>

        </div>


        {{-- FOOTER ACTIONS --}}
        <div class="action-footer">

            <a
                href="{{ route('admin.timetable.index') }}"
                class="btn-action btn-back"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a
                href="{{ route('admin.timetable.edit', $timetable->id) }}"
                class="btn-action btn-edit"
            >
                <i class="bi bi-pencil"></i>
                Edit
            </a>

        </div>

    </div>

</div>

@endsection
