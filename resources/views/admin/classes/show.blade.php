@extends('layouts.app')

@section('title', 'View Class | Admin')

@section('page-title', 'View Class')

@section('content')

<style>
    .class-view-page {
        width: 100%;
    }

    .class-page-header {
        margin-bottom: 20px;
    }

    .class-page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #26344a;
    }

    .class-page-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .class-view-card {
        max-width: 900px;
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 8px;
        overflow: hidden;
    }

    .class-view-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e7edf5;
    }

    .class-view-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #26344a;
    }

    .class-view-card-body {
        padding: 22px 20px;
    }

    .class-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
        border: 1px solid #e7edf5;
        border-radius: 6px;
        overflow: hidden;
    }

    .class-info-item {
        padding: 16px;
        border-bottom: 1px solid #e7edf5;
    }

    .class-info-item:nth-child(odd) {
        border-right: 1px solid #e7edf5;
    }

    .class-info-item:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .info-label {
        display: block;
        margin-bottom: 6px;
        color: #718096;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .info-value {
        color: #26344a;
        font-size: 14px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-active {
        background: #e8f7ee;
        color: #198754;
    }

    .status-inactive {
        background: #fcebec;
        color: #dc3545;
    }

    .class-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid #edf1f6;
    }

    .btn-primary-custom,
    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .btn-primary-custom {
        background: #1677f0;
        color: #fff;
        border: 1px solid #1677f0;
    }

    .btn-primary-custom:hover {
        background: #0d5fd1;
        border-color: #0d5fd1;
        color: #fff;
    }

    .btn-secondary-custom {
        background: #fff;
        color: #26344a;
        border: 1px solid #dce3ec;
    }

    .btn-secondary-custom:hover {
        background: #f5f8fc;
        color: #26344a;
    }

    @media (max-width: 768px) {
        .class-info-grid {
            grid-template-columns: 1fr;
        }

        .class-info-item:nth-child(odd) {
            border-right: none;
        }

        .class-info-item:nth-last-child(-n+2) {
            border-bottom: 1px solid #e7edf5;
        }

        .class-info-item:last-child {
            border-bottom: none;
        }

        .class-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
        }
    }
</style>

<div class="class-view-page">

    {{-- Page Header --}}
    <div class="class-page-header">
        <h2>View Class</h2>
        <p>View the details of this school class.</p>
    </div>

    {{-- Class Details --}}
    <div class="class-view-card">

        <div class="class-view-card-header">
            <h3>Class Information</h3>
        </div>

        <div class="class-view-card-body">

            <div class="class-info-grid">

                <div class="class-info-item">
                    <span class="info-label">Class Name</span>
                    <span class="info-value">
                        {{ $schoolClass->class_name }}
                    </span>
                </div>

                <div class="class-info-item">
                    <span class="info-label">Section</span>
                    <span class="info-value">
                        {{ $schoolClass->section }}
                    </span>
                </div>

                <div class="class-info-item">
                    <span class="info-label">Academic Year</span>
                    <span class="info-value">
                        {{ $schoolClass->academic_year }}
                    </span>
                </div>

                <div class="class-info-item">
                    <span class="info-label">Status</span>

                    @if($schoolClass->status)
                        <span class="status-badge status-active">
                            Active
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            Inactive
                        </span>
                    @endif
                </div>

                <div class="class-info-item">
                    <span class="info-label">Created At</span>
                    <span class="info-value">
                        {{ $schoolClass->created_at?->format('d M Y, h:i A') }}
                    </span>
                </div>

                <div class="class-info-item">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value">
                        {{ $schoolClass->updated_at?->format('d M Y, h:i A') }}
                    </span>
                </div>

            </div>

            {{-- Actions --}}
            <div class="class-actions">

                <a href="{{ route('admin.classes.edit', $schoolClass->id) }}"
                   class="btn-primary-custom">
                    <i class="fas fa-edit"></i>
                    Edit Class
                </a>

                <a href="{{ route('admin.classes.index') }}"
                   class="btn-secondary-custom">
                    <i class="fas fa-arrow-left"></i>
                    Back to Classes
                </a>

            </div>

        </div>

    </div>

</div>

@endsection