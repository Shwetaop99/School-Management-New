@extends('layouts.app')

@section('title', 'Select Student - Bonafide Certificate | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM â€” SELECT STUDENT (BONAFIDE)
       LOCKED VERSION
    ========================================================= */

    .bonafide-students-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .bonafide-students-page *,
    .bonafide-students-page *::before,
    .bonafide-students-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .bonafide-header {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 22px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .bonafide-header-content {
        position: relative;
        z-index: 1;
    }

    .bonafide-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .bonafide-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .bonafide-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .bonafide-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .bonafide-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .bonafide-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 12px;
        font-size: 22px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .08);
    }

    .bonafide-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .bonafide-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    /* ---------- STUDENT GRID ---------- */
    .student-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
    }

    .student-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .04);
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }

    .student-card::after {
        content: "";
        position: absolute;
        top: -25px;
        right: -25px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(52, 119, 90, .05) 0%, transparent 70%);
        pointer-events: none;
    }

    .student-card:hover {
        border-color: #c5d1c2;
        box-shadow: 0 8px 22px rgba(52, 119, 90, .1);
        transform: translateY(-2px);
    }

    .student-photo {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fffefa;
        outline: 2px solid #c5dcc8;
        background: #fffefa;
        box-shadow: 0 2px 8px rgba(38, 59, 53, .1);
    }

    .student-placeholder {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 50%;
        background: linear-gradient(145deg, #eaf3e9, #d8e8d8);
        border: 3px solid #fffefa;
        outline: 2px solid #c5dcc8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #34775a;
        box-shadow: 0 2px 8px rgba(38, 59, 53, .08);
    }

    .student-info {
        min-width: 0;
        flex: 1;
    }

    .student-name {
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-id {
        color: #7a857d;
        font-size: 11px;
        margin-bottom: 3px;
        font-weight: 500;
    }

    .student-details {
        color: #5c6b62;
        font-size: 10px;
        font-weight: 600;
    }

    .select-btn {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 9px;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        color: #fffefa;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid #34775a;
        box-shadow: 0 2px 8px rgba(52, 119, 90, .15);
        transition: all .18s ease;
        font-size: 14px;
    }

    .select-btn:hover {
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        color: #fffefa;
        box-shadow: 0 4px 12px rgba(36, 90, 67, .2);
        transform: translateY(-1px);
    }

    /* ---------- EMPTY STATE ---------- */
    .empty-state {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        padding: 56px 24px;
        text-align: center;
        box-shadow: 0 3px 14px rgba(38, 59, 53, .04);
    }

    .empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin-bottom: 16px;
        color: #7a857d;
        background: #f0eee6;
        border: 1px solid #e0ddcf;
        border-radius: 14px;
        font-size: 28px;
    }

    .empty-title {
        margin: 0 0 6px;
        color: #263b35;
        font-size: 15px;
        font-weight: 750;
    }

    .empty-text {
        margin: 0;
        color: #7a857d;
        font-size: 12px;
        line-height: 1.5;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 767.98px) {
        .bonafide-header {
            padding: 18px 16px;
            margin-bottom: 18px;
        }

        .bonafide-title {
            font-size: 19px;
        }

        .bonafide-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .student-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    @media (max-width: 480px) {
        .bonafide-title {
            font-size: 17px;
        }

        .bonafide-subtitle {
            font-size: 10px;
        }

        .bonafide-breadcrumb {
            font-size: 9px;
        }

        .student-card {
            padding: 14px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bonafide-students-page *,
        .bonafide-students-page *::before,
        .bonafide-students-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="bonafide-students-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="bonafide-header">
        <div class="bonafide-header-content">
            <div class="bonafide-breadcrumb">
                <a href="{{ route('admin.bonafide-certificate.classes') }}">Bonafide Certificate</a>
                <i class="bi bi-chevron-right"></i>
                <span>Class {{ $class }}</span>
            </div>

            <div class="bonafide-title-row">
                <div class="bonafide-title-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h1 class="bonafide-title">Class {{ $class }}</h1>
                    <p class="bonafide-subtitle">
                        Select a student to generate a bonafide certificate.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- STUDENT GRID --}}
    @if($students->count())

        <div class="student-grid">
            @foreach($students as $student)
                @php
                    $studentName = trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        ($student->last_name ?? '')
                    );
                @endphp

                <div class="student-card">
                    @if(!empty($student->profile_image))
                        <img src="{{ $student->profile_image }}"
                             alt="{{ $studentName }}"
                             class="student-photo">
                    @else
                        <div class="student-placeholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif

                    <div class="student-info">
                        <div class="student-name">
                            {{ $studentName ?: 'Student' }}
                        </div>

                        <div class="student-id">
                            Student ID: {{ $student->student_id ?? 'â€”' }}
                        </div>

                        <div class="student-details">
                            @if(!empty($student->section))
                                Section {{ $student->section }}
                            @endif

                            @if(!empty($student->roll_number))
                                @if(!empty($student->section))
                                    &nbsp;Â·&nbsp;
                                @endif
                                Roll No. {{ $student->roll_number }}
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('admin.bonafide-certificate.create', ['student_id' => $student->id]) }}"
                       class="select-btn"
                       title="Generate Bonafide Certificate">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>

    @else

        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-person-x"></i>
            </div>
            <h5 class="empty-title">No Students Found</h5>
            <p class="empty-text">
                There are no active students in Class {{ $class }}.
            </p>
        </div>

    @endif

</div>

@endsection
