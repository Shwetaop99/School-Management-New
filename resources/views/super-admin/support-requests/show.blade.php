@extends('super-admin.layouts.app')

@section('title', 'Support Request Details')
@section('breadcrumb', 'Support / Requests / Details')
@section('page-title', 'Request Details')

@section('content')

@php
    $priorityBadge = match (strtolower((string) $supportRequest->priority)) {
        'urgent', 'high' => 'inactive',
        'medium'         => 'other',
        'low'            => 'active',
        default          => 'other',
    };

    $statusBadge = match (strtolower((string) $supportRequest->status)) {
        'open'        => 'other',
        'in_progress' => 'other',
        'resolved'    => 'active',
        'closed'      => 'inactive',
        default       => 'other',
    };
@endphp

<div class="welcome">
    <div>
        <div class="eyebrow">Support desk</div>
        <h2>{{ $supportRequest->subject }}</h2>
        <p>
            Ticket
            <span class="code-chip" style="margin-left:6px;">{{ $supportRequest->ticket_number }}</span>
        </p>
    </div>

    <a href="{{ route('super-admin.support-requests.index') }}" class="chip">
        ← Back to requests
    </a>
</div>

@if(session('success'))
    <div class="req-alert req-alert--success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="req-alert req-alert--error">
        <strong>Please correct these errors:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Request info --}}
<div class="panel" style="margin-bottom:20px;">
    <div class="panel-head">
        <div>
            <h3>Request information</h3>
            <p>School details, category and original description</p>
        </div>
        <span class="badge {{ $statusBadge }}">
            {{ ucwords(str_replace('_', ' ', $supportRequest->status)) }}
        </span>
    </div>

    <div class="req-body">
        <div class="req-meta-grid">
            <div class="req-meta">
                <span class="req-meta__label">School</span>
                <span class="req-meta__value">{{ $supportRequest->school->name ?? 'N/A' }}</span>
            </div>
            <div class="req-meta">
                <span class="req-meta__label">Submitted by</span>
                <span class="req-meta__value">{{ $supportRequest->user->name ?? 'N/A' }}</span>
            </div>
            <div class="req-meta">
                <span class="req-meta__label">Category</span>
                <span class="req-meta__value">
                    {{ ucfirst(str_replace('_', ' ', $supportRequest->category)) }}
                </span>
            </div>
            <div class="req-meta">
                <span class="req-meta__label">Priority</span>
                <span class="badge {{ $priorityBadge }}">
                    {{ ucfirst($supportRequest->priority) }}
                </span>
            </div>
            <div class="req-meta">
                <span class="req-meta__label">Submitted</span>
                <span class="req-meta__value">
                    {{ $supportRequest->created_at?->format('d M Y, h:i A') ?? '—' }}
                </span>
            </div>
        </div>

        <div class="req-description">
            <span class="req-meta__label">Original request</span>
            <div class="req-description__text">{{ $supportRequest->description }}</div>
        </div>
    </div>
</div>

{{-- Status --}}
<div class="panel" style="margin-bottom:20px;">
    <div class="panel-head">
        <div>
            <h3>Request status</h3>
            <p>Update the current handling state</p>
        </div>
    </div>

    <div class="req-body">
        <form method="POST"
              action="{{ route('super-admin.support-requests.status.update', $supportRequest->id) }}"
              class="status-form">
            @csrf
            @method('PATCH')

            <label for="status" class="status-form__label">Status</label>

            <select name="status" id="status" class="filter-select status-form__select">
                @foreach([
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed',
                ] as $value => $label)
                    <option value="{{ $value }}" @selected($supportRequest->status === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                Update status
            </button>
        </form>
    </div>
</div>

{{-- Conversation --}}
<div class="panel" style="margin-bottom:20px;">
    <div class="panel-head">
        <div>
            <h3>Conversation</h3>
            <p>
                {{ $supportRequest->replies->count() }}
                {{ $supportRequest->replies->count() === 1 ? 'reply' : 'replies' }}
            </p>
        </div>
    </div>

    <div class="req-body conversation">
        {{-- Original message --}}
        <article class="msg msg--school">
            <div class="msg__head">
                <div class="msg__author">
                    <span class="msg__avatar msg__avatar--school">
                        {{ strtoupper(substr($supportRequest->user->name ?? 'A', 0, 1)) }}
                    </span>
                    <div>
                        <strong>{{ $supportRequest->user->name ?? 'School Admin' }}</strong>
                        <span class="msg__role">School Admin</span>
                    </div>
                </div>
                <time class="msg__time">
                    {{ $supportRequest->created_at?->format('d M Y, h:i A') }}
                </time>
            </div>
            <div class="msg__body">{{ $supportRequest->description }}</div>
        </article>

        @forelse($supportRequest->replies as $reply)
            @php $isAdmin = $reply->sender_type === 'super_admin'; @endphp
            <article class="msg {{ $isAdmin ? 'msg--admin' : 'msg--school' }}">
                <div class="msg__head">
                    <div class="msg__author">
                        <span class="msg__avatar {{ $isAdmin ? 'msg__avatar--admin' : 'msg__avatar--school' }}">
                            {{ $isAdmin ? 'S' : strtoupper(substr($supportRequest->user->name ?? 'A', 0, 1)) }}
                        </span>
                        <div>
                            <strong>{{ $isAdmin ? 'Super Admin' : 'School Admin' }}</strong>
                            <span class="msg__role">
                                {{ $isAdmin ? 'Platform support' : 'School team' }}
                            </span>
                        </div>
                    </div>
                    <time class="msg__time">
                        {{ $reply->created_at?->format('d M Y, h:i A') }}
                    </time>
                </div>
                <div class="msg__body">{{ $reply->message }}</div>
            </article>
        @empty
            <div class="empty-replies">
                No replies yet. Send the first response below.
            </div>
        @endforelse
    </div>
</div>

{{-- Reply --}}
<div class="panel">
    <div class="panel-head">
        <div>
            <h3>Send a reply</h3>
            <p>Respond to the school on this ticket</p>
        </div>
    </div>

    <div class="req-body">
        @if($supportRequest->status !== 'closed')
            <form method="POST"
                  action="{{ route('super-admin.support-requests.reply', $supportRequest->id) }}"
                  class="reply-form">
                @csrf

                <label for="message" class="reply-form__label">Your message</label>
                <textarea
                    name="message"
                    id="message"
                    rows="5"
                    class="reply-form__textarea"
                    placeholder="Write your response to the school..."
                    required
                    minlength="2"
                    maxlength="10000">{{ old('message') }}</textarea>

                <div class="reply-form__actions">
                    <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                        Send reply
                    </button>
                </div>
            </form>
        @else
            <div class="req-alert req-alert--muted">
                This request is closed. Reopen it before sending another reply.
            </div>
        @endif
    </div>
</div>

<style>
    .req-alert {
        padding: 14px 18px;
        margin-bottom: 18px;
        border-radius: 10px;
        font-size: 13.5px;
        line-height: 1.5;
    }

    .req-alert--success {
        border: 1px solid #d3e9d9;
        background: #edf8f0;
        color: #2d8050;
    }

    .req-alert--error {
        border: 1px solid #f2d8d3;
        background: #fff2ef;
        color: #b54f43;
    }

    .req-alert--muted {
        border: 1px solid #e6ebe7;
        background: #f8faf8;
        color: #52665b;
        margin: 0;
    }

    .req-alert ul {
        margin: 8px 0 0;
        padding-left: 18px;
    }

    .req-body {
        padding: 22px 24px;
    }

    .req-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .req-meta__label {
        display: block;
        margin-bottom: 6px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
        color: #75827d;
    }

    .req-meta__value {
        font-size: 14px;
        font-weight: 600;
        color: #24332f;
    }

    .req-description {
        padding: 16px;
        border: 1px solid #e6ebe7;
        border-radius: 10px;
        background: #fbfcfb;
    }

    .req-description__text {
        margin-top: 8px;
        white-space: pre-wrap;
        font-size: 14px;
        line-height: 1.7;
        color: #374151;
    }

    .status-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .status-form__label {
        font-size: 13px;
        font-weight: 700;
        color: #24332f;
    }

    .status-form__select {
        height: 40px;
        min-width: 180px;
        padding: 0 12px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 9px;
        background: #fff;
        font-size: 13.5px;
    }

    .conversation {
        display: grid;
        gap: 14px;
    }

    .msg {
        padding: 16px;
        border: 1px solid #e6ebe7;
        border-radius: 12px;
        background: #fff;
    }

    .msg--admin {
        border-color: #d3e9d9;
        background: #f6fbf7;
        margin-left: 24px;
    }

    .msg--school {
        margin-right: 24px;
    }

    .msg__head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .msg__author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .msg__avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
    }

    .msg__avatar--admin {
        background: #e9f5ee;
        color: #2f7658;
    }

    .msg__avatar--school {
        background: #edf3ff;
        color: #4778c7;
    }

    .msg__author strong {
        display: block;
        font-size: 13.5px;
        color: #24332f;
    }

    .msg__role {
        display: block;
        margin-top: 2px;
        font-size: 11.5px;
        color: #75827d;
    }

    .msg__time {
        font-size: 12px;
        color: #75827d;
        white-space: nowrap;
    }

    .msg__body {
        white-space: pre-wrap;
        font-size: 14px;
        line-height: 1.7;
        color: #374151;
    }

    .empty-replies {
        padding: 24px;
        text-align: center;
        color: #75827d;
        font-size: 13.5px;
        border: 1px dashed #e6ebe7;
        border-radius: 10px;
        background: #fbfcfb;
    }

    .reply-form__label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #24332f;
    }

    .reply-form__textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 10px;
        background: #fff;
        color: #24332f;
        font: inherit;
        font-size: 14px;
        line-height: 1.6;
        resize: vertical;
        min-height: 120px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .reply-form__textarea:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, .18);
    }

    .reply-form__actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 14px;
    }

    @media (max-width: 700px) {
        .msg--admin,
        .msg--school {
            margin-left: 0;
            margin-right: 0;
        }

        .msg__head {
            flex-direction: column;
            gap: 8px;
        }

        .status-form {
            flex-direction: column;
            align-items: stretch;
        }

        .status-form__select {
            width: 100%;
            max-width: none;
        }
    }
</style>

@endsection