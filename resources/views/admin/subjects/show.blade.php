@extends('layouts.app')

@section('title', 'View Subject | Admin')

@section('page-title', 'View Subject')

@section('content')

<style>
    .subject-view-page {
        width: 100%;
    }

    .subject-view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .subject-view-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #26344a;
    }

    .subject-view-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary-custom,
    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
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
        background: #f4f6f9;
        color: #526071;
        border: 1px solid #e1e6ee;
    }

    .btn-secondary-custom:hover {
        background: #e9edf3;
        color: #26344a;
    }

    .subject-view-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 8px;
        overflow: hidden;
    }

    .subject-view-card-header {
        padding: 16px 18px;
        border-bottom: 1px solid #e7edf5;
    }

    .subject-view-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #26344a;
    }

    .subject-details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
    }

    .detail-item {
        padding: 18px;
        border-bottom: 1px solid #edf1f6;
    }

    .detail-item:nth-child(odd) {
        border-right: 1px solid #edf1f6;
    }

    .detail-label {
        display: block;
        margin-bottom: 7px;
        color: #718096;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .detail-value {
        color: #26344a;
        font-size: 14px;
        font-weight: 600;
    }

    .subject-code-value {
        color: #64748b;
        font-weight: 500;
    }

    .class-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        background: #eef5ff;
        color: #1677f0;
        font-size: 11px;
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

    .subject-view-footer {
        padding: 16px 18px;
        background: #fafbfd;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 768px) {
        .subject-view-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions a {
            flex: 1;
        }

        .subject-details {
            grid-template-columns: 1fr;
        }

        .detail-item:nth-child(odd) {
            border-right: none;
        }

        .detail-item {
            border-bottom: 1px solid #edf1f6;
        }

        .subject-view-footer {
            justify-content: stretch;
        }

        .subject-view-footer a {
            flex: 1;
        }
    }
</style>

<div class="subject-view-page">

    <div class="subject-view-header">

        <div>
            <h2>{{ $subject->subject_name }}</h2>
            <p>View complete subject information.</p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.subjects.edit', $subject->id) }}"
                class="btn-primary-custom"
            >
                <span>✎</span>
                Edit Subject
            </a>

            <a
                href="{{ route('admin.subjects.index') }}"
                class="btn-secondary-custom"
            >
                Back
            </a>

        </div>

    </div>

    <div class="subject-view-card">

        <div class="subject-view-card-header">
            <h3>Subject Information</h3>
        </div>

        <div class="subject-details">

            <div class="detail-item">

                <span class="detail-label">
                    Subject Name
                </span>

                <div class="detail-value">
                    {{ $subject->subject_name }}
                </div>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Subject Code
                </span>

                <div class="detail-value subject-code-value">
                    {{ $subject->subject_code ?: '—' }}
                </div>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Class
                </span>

                <div class="detail-value">

                    @if($subject->schoolClass)

                        <span class="class-badge">
                            {{ $subject->schoolClass->class_name }}
                        </span>

                    @else
                        —
                    @endif

                </div>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Section
                </span>

                <div class="detail-value">
                    {{ $subject->schoolClass->section ?? '—' }}
                </div>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Academic Year
                </span>

                <div class="detail-value">
                    {{ $subject->schoolClass->academic_year ?? '—' }}
                </div>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Status
                </span>

                <div class="detail-value">

                    @if($subject->status)

                        <span class="status-badge status-active">
                            Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

        </div>

        <div class="subject-view-footer">

            <a
                href="{{ route('admin.subjects.index') }}"
                class="btn-secondary-custom"
            >
                Back to Subjects
            </a>

            <a
                href="{{ route('admin.subjects.edit', $subject->id) }}"
                class="btn-primary-custom"
            >
                <span>✎</span>
                Edit Subject
            </a>

        </div>

    </div>

</div>

@endsection