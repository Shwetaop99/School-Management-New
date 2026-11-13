@extends('layouts.app')

@section('title', 'Generate Bonafide Certificate | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM â€” BONAFIDE CERTIFICATE
    ========================================================= */

    .bonafide-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .bonafide-page *,
    .bonafide-page *::before,
    .bonafide-page *::after {
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

    /* ---------- ALERTS ---------- */
    .scheme-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.5;
    }

    .scheme-alert-success {
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-left: 4px solid #34775a;
        color: #245a43;
    }

    .scheme-alert-danger {
        background: #fff1ed;
        border: 1px solid #e8b8b0;
        border-left: 4px solid #bd5148;
        color: #8a3a34;
    }

    /* ---------- CLASS GRID ---------- */
    .class-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .class-card {
        display: block;
        text-decoration: none;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        padding: 20px 18px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .04);
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }

    .class-card::after {
        content: "";
        position: absolute;
        top: -30px;
        right: -30px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(52, 119, 90, .06) 0%, transparent 70%);
        pointer-events: none;
    }

    .class-card:hover {
        transform: translateY(-3px);
        border-color: #c5d1c2;
        box-shadow: 0 8px 22px rgba(52, 119, 90, .1);
    }

    .class-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        color: #34775a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 14px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .07);
    }

    .class-name {
        color: #263b35;
        font-size: 15px;
        font-weight: 750;
        margin-bottom: 6px;
        letter-spacing: -.2px;
    }

    .class-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #7a857d;
        font-size: 11px;
        font-weight: 600;
        transition: color .18s ease;
    }

    .class-card:hover .class-action {
        color: #34775a;
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

        .class-grid {
            grid-template-columns: repeat(2, 1fr);
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

        .class-grid {
            grid-template-columns: 1fr;
        }

        .class-card {
            padding: 16px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bonafide-page *,
        .bonafide-page *::before,
        .bonafide-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="bonafide-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="bonafide-header">
        <div class="bonafide-header-content">
            <div class="bonafide-breadcrumb">
                <a href="{{ route('admin.students.index') }}">Students</a>
                <i class="bi bi-chevron-right"></i>
                <span>Bonafide Certificate</span>
            </div>

            <div class="bonafide-title-row">
                <div class="bonafide-title-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <h1 class="bonafide-title">Generate Bonafide Certificate</h1>
                    <p class="bonafide-subtitle">
                        Select a class to view students and generate a bonafide certificate.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="scheme-alert scheme-alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="scheme-alert scheme-alert-danger">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- CLASS GRID --}}
    @if($classes->count())

        <div class="class-grid">
            @foreach($classes as $item)
                <a href="{{ route('admin.bonafide-certificate.students', ['class' => $item->class]) }}"
                   class="class-card">

                    <div class="class-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="class-name">
                        Class {{ $item->class }}
                    </div>

                    <div class="class-action">
                        Click to view students
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>
            @endforeach
        </div>

    @else

        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-people"></i>
            </div>
            <h5 class="empty-title">No Classes Found</h5>
            <p class="empty-text">
                There are no active students with a class assigned.
            </p>
        </div>

    @endif

</div>

@endsection
