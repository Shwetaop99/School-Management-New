@extends('layouts.app')

@section('content')

<style>
    .notice-view-wrapper {
        padding: 30px;
    }

    .notice-view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .notice-view-header h1 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #172554;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #fff;
        color: #172554;
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #f8fafc;
    }

    .notice-card {
        background: #fff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf1f7;
    }

    .notice-title {
        font-size: 28px;
        font-weight: 700;
        color: #172554;
        margin-bottom: 20px;
    }

    .notice-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 25px;
    }

    .meta-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 14px;
        color: #475569;
    }

    .meta-item strong {
        color: #172554;
    }

    .notice-content {
        padding-top: 25px;
        border-top: 1px solid #e5e7eb;
        color: #475569;
        font-size: 16px;
        line-height: 1.8;
        white-space: pre-line;
    }

    .attachment-box {
        margin-top: 25px;
        padding: 18px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }

    .attachment-box a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .attachment-box a:hover {
        text-decoration: underline;
    }
</style>

<div class="notice-view-wrapper">

    <div class="notice-view-header">
        <h1>View Notice</h1>

        <a href="{{ route('admin.notices.index') }}" class="back-btn">
            ← Back to Notices
        </a>
    </div>

    <div class="notice-card">

        <div class="notice-title">
            {{ $notice->title }}
        </div>

        <div class="notice-meta">

            <div class="meta-item">
                <strong>Category:</strong>
                {{ $notice->category }}
            </div>

            <div class="meta-item">
                <strong>Priority:</strong>
                {{ $notice->priority }}
            </div>

            <div class="meta-item">
                <strong>Status:</strong>
                {{ $notice->status }}
            </div>

            <div class="meta-item">
                <strong>Publish Date:</strong>
                {{ $notice->publish_date ? $notice->publish_date->format('d M Y') : 'Not set' }}
            </div>

            <div class="meta-item">
                <strong>Expiry Date:</strong>
                {{ $notice->expiry_date ? $notice->expiry_date->format('d M Y') : 'No expiry' }}
            </div>

        </div>

        <div class="notice-content">
            {{ $notice->content }}
        </div>

        @if($notice->attachment)
            <div class="attachment-box">
                <strong>Attachment:</strong>

                <a href="{{ asset('storage/' . $notice->attachment) }}"
                   target="_blank">
                    View Attachment
                </a>
            </div>
        @endif

    </div>

</div>

@endsection