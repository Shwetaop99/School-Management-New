@extends('layouts.app')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')

@php
    $priorityClass = match (strtolower((string) $ticket->priority)) {
        'urgent', 'critical', 'high' => 'p-high',
        'medium', 'normal'           => 'p-medium',
        'low'                        => 'p-low',
        default                      => 'p-none',
    };

    $statusClass = match (strtolower((string) $ticket->status)) {
        'open', 'new'               => 's-open',
        'in_progress', 'processing' => 's-progress',
        'resolved', 'closed'        => 's-resolved',
        'pending'                   => 's-pending',
        default                     => 's-default',
    };
@endphp

<div class="ticket-show">

    <div class="ticket-show__top">
        <div>
            <a href="{{ route('admin.support-tickets.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to tickets
            </a>

            <h2>Ticket details</h2>
            <p>
                Ticket
                <span class="ticket-no">{{ $ticket->ticket_number }}</span>
            </p>
        </div>

        <span class="status-badge {{ $statusClass }}">
            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
        </span>
    </div>

    @if (session('success'))
        <div class="note-box" role="status">
            <i class="fas fa-circle-check"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Ticket info --}}
    <section class="paper panel">
        <div class="panel-head">
            <div>
                <h3>Ticket information</h3>
                <span>Submitted details and description</span>
            </div>
            <span class="priority {{ $priorityClass }}">
                {{ ucfirst($ticket->priority) }} priority
            </span>
        </div>

        <div class="panel-body">
            <div class="info-grid">
                <div class="info-item info-item--wide">
                    <label>Subject</label>
                    <div class="info-value">{{ $ticket->subject }}</div>
                </div>

                <div class="info-item">
                    <label>Category</label>
                    <div class="info-value">
                        <span class="category-chip">
                            {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <label>Priority</label>
                    <div class="info-value">
                        <span class="priority {{ $priorityClass }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="description-block">
                <label>Description</label>
                <div class="description-text">{{ $ticket->description }}</div>
            </div>

            <div class="meta-line">
                Submitted on {{ $ticket->created_at?->format('d M Y, h:i A') ?? '—' }}
            </div>
            {{-- Send Reply --}}
<section class="paper panel">
    <div class="panel-head">
        <div>
            <h3>Send a Reply</h3>
            <span>Continue the conversation with Super Admin</span>
        </div>
    </div>

    <div class="panel-body">
        @if($ticket->status !== 'closed')
            <form method="POST"
                  action="{{ route('admin.support-tickets.reply', $ticket->id) }}">

                @csrf

                <div class="form-group">
                    <label for="message">Your Message</label>

                    <textarea
                        name="message"
                        id="message"
                        rows="5"
                        class="form-control"
                        placeholder="Write your message here..."
                        required
                        minlength="2"
                        maxlength="10000">{{ old('message') }}</textarea>

                    @error('message')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-paper-plane"></i>
                    Send Reply
                </button>
            </form>
        @else
            <div class="note-box">
                This request is closed. Contact Super Admin if you need further assistance.
            </div>
        @endif
    </div>
</section>
        </div>
    </section>

    {{-- Conversation --}}
    <section class="paper panel conversation-panel">
        <div class="panel-head">
            <div>
                <h3>Conversation history</h3>
                <span>
                    {{ $ticket->replies->count() }}
                    {{ $ticket->replies->count() === 1 ? 'reply' : 'replies' }}
                </span>
            </div>
        </div>

        <div class="panel-body conversation-body">
            @forelse ($ticket->replies as $reply)
                @php
                    $isAdmin = $reply->sender_type === 'super_admin';
                @endphp

                <article class="reply {{ $isAdmin ? 'reply--admin' : 'reply--school' }}">
                    <div class="reply-head">
                        <div class="reply-author">
                            <span class="reply-avatar {{ $isAdmin ? 'reply-avatar--admin' : 'reply-avatar--school' }}">
                                {{ $isAdmin ? 'S' : 'A' }}
                            </span>
                            <div>
                                <strong>{{ $isAdmin ? 'Super Admin' : 'School Admin' }}</strong>
                                <span class="reply-role">
                                    {{ $isAdmin ? 'Platform support' : 'Your team' }}
                                </span>
                            </div>
                        </div>
                        <time class="reply-time">
                            {{ $reply->created_at?->format('d M Y, h:i A') }}
                        </time>
                    </div>

                    <div class="reply-message">{{ $reply->message }}</div>
                </article>
            @empty
                <div class="empty">
                    <div class="empty-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4>No replies yet</h4>
                    <p>The Super Admin will respond to your ticket here.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>

<style>
    .ticket-show {
        --t-ink: var(--ink, #1d3a33);
        --t-ink2: var(--ink-2, #16302a);
        --t-line: var(--line, #d8d1bf);
        --t-muted: var(--muted, #6b7c75);
        --t-text: var(--text, #2c3e3a);
        --t-serif: var(--serif, Georgia, "Times New Roman", serif);
        max-width: 920px;
        margin: 0 auto;
        color: var(--t-text);
        font-size: 13.5px;
    }

    .ticket-show a { text-decoration: none; }

    .ticket-show__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: var(--t-muted);
        font-size: 13px;
        font-weight: 500;
    }

    .back-link:hover { color: var(--t-ink); }

    .ticket-show__top h2 {
        margin: 0 0 6px;
        font-family: var(--t-serif);
        font-size: 26px;
        font-weight: 700;
        color: var(--t-ink);
    }

    .ticket-show__top p {
        margin: 0;
        color: var(--t-muted);
        font-size: 14px;
    }

    .ticket-no {
        display: inline-block;
        margin-left: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        background: #f0f4f8;
        color: #426b8b;
        font-size: 12px;
        font-weight: 800;
        font-family: ui-monospace, monospace;
    }

    .paper {
        background: #fff;
        border: 1px solid var(--t-line);
        border-radius: 10px;
    }

    .panel { margin-bottom: 20px; }

    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 22px;
        border-bottom: 1px dashed #d8d1bf;
    }

    .panel-head h3 {
        margin: 0;
        font-family: var(--t-serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--t-ink);
    }

    .panel-head span:not(.priority):not(.status-badge) {
        display: block;
        margin-top: 3px;
        font-size: 12px;
        color: var(--t-muted);
    }

    .panel-body { padding: 22px; }

    .note-box {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px;
        padding: 13px 16px;
        border: 1px solid #cfe5d3;
        border-radius: 10px;
        background: #edf7ed;
        color: #2f6b45;
        line-height: 1.6;
    }

    .note-box i { margin-top: 2px; }

    .info-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr;
        gap: 18px;
        margin-bottom: 20px;
    }

    .info-item--wide {
        grid-column: 1 / -1;
    }

    .info-item label,
    .description-block label {
        display: block;
        margin-bottom: 6px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
        color: var(--t-muted);
    }

    .info-value {
        font-size: 14.5px;
        font-weight: 600;
        color: var(--t-ink);
        line-height: 1.45;
    }

    .category-chip {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        background: #f3f1ea;
        color: #5e6d66;
        font-size: 11.5px;
        font-weight: 700;
    }

    .priority {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border: 1px solid transparent;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .priority::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .p-high {
        border-color: #f0d5ce;
        background: #fff4ef;
        color: #a84840;
    }

    .p-medium {
        border-color: #eee0b9;
        background: #fbf3df;
        color: #8a682b;
    }

    .p-low {
        border-color: #cfe5d3;
        background: #edf7ed;
        color: #2f6b45;
    }

    .p-none {
        border-color: var(--t-line);
        background: #f6f3ea;
        color: var(--t-muted);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .s-open {
        background: #edf3ff;
        color: #2b5d8a;
    }

    .s-progress {
        background: #fff5df;
        color: #b87918;
    }

    .s-resolved {
        background: #edf8f0;
        color: #2d8050;
    }

    .s-pending {
        background: #f3f1ea;
        color: #6b7c75;
    }

    .s-default {
        background: #f3f1ea;
        color: #5e6d66;
    }

    .description-block {
        margin-bottom: 18px;
        padding: 16px;
        border: 1px solid #ebe5d4;
        border-radius: 10px;
        background: #fffdf8;
    }

    .description-text {
        margin-top: 6px;
        white-space: pre-line;
        color: var(--t-text);
        font-size: 14px;
        line-height: 1.7;
    }

    .meta-line {
        color: var(--t-muted);
        font-size: 12.5px;
    }

    /* Conversation */
    .conversation-body {
        display: grid;
        gap: 14px;
    }

    .reply {
        padding: 16px;
        border: 1px solid #ebe5d4;
        border-radius: 10px;
        background: #fffdf8;
    }

    .reply--admin {
        border-color: #d3e9d9;
        background: #f6fbf7;
    }

    .reply-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .reply-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .reply-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
    }

    .reply-avatar--admin {
        background: #e9f5ee;
        color: #2f7658;
    }

    .reply-avatar--school {
        background: #edf3ff;
        color: #2b5d8a;
    }

    .reply-author strong {
        display: block;
        font-size: 13.5px;
        color: var(--t-ink);
    }

    .reply-role {
        display: block;
        margin-top: 2px;
        font-size: 11.5px;
        color: var(--t-muted);
    }

    .reply-time {
        font-size: 12px;
        color: var(--t-muted);
        white-space: nowrap;
    }

    .reply-message {
        white-space: pre-line;
        color: var(--t-text);
        font-size: 14px;
        line-height: 1.7;
    }

    .empty {
        padding: 40px 16px;
        text-align: center;
    }

    .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        margin: 0 auto 12px;
        border: 1px solid #e6dfce;
        border-radius: 14px;
        background: #f4efdd;
        color: #b8b09e;
        font-size: 22px;
    }

    .empty h4 {
        margin: 0 0 6px;
        font-family: var(--t-serif);
        font-size: 16px;
        color: var(--t-ink);
    }

    .empty p {
        margin: 0;
        color: var(--t-muted);
        font-size: 13px;
    }

    @media (max-width: 700px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .ticket-show__top {
            flex-direction: column;
        }

        .reply-head {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

@endsection