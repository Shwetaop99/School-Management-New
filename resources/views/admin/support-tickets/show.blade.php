@extends('layouts.app')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')
@php
    $priorityClass = match (strtolower((string) $ticket->priority)) {
        'urgent', 'critical', 'high' => 'priority-high',
        'medium', 'normal' => 'priority-medium',
        'low' => 'priority-low',
        default => 'priority-default',
    };

    $statusClass = match (strtolower((string) $ticket->status)) {
        'open', 'new' => 'status-open',
        'in_progress', 'processing' => 'status-progress',
        'resolved', 'closed' => 'status-resolved',
        'pending' => 'status-pending',
        default => 'status-default',
    };

    $categoryName = ucwords(str_replace('_', ' ', (string) $ticket->category));
    $replies = $ticket->replies ?? collect();
@endphp

<div class="ticket-page">
    <div class="ticket-page-heading">
        <div>
            <a href="{{ route('support-tickets.index') }}" class="ticket-back-link">
                <i class="fas fa-arrow-left"></i> Back to Help &amp; Support
            </a>
            <div class="ticket-eyebrow"><i class="fas fa-headset"></i> SUPPORT REQUEST</div>
            <h1>Ticket details</h1>
            <p>Ticket <span class="ticket-number">{{ $ticket->ticket_number }}</span></p>
        </div>

        <span class="ticket-status {{ $statusClass }}">
            {{ ucfirst(str_replace('_', ' ', (string) $ticket->status)) }}
        </span>
    </div>

    @if (session('success'))
        <div class="ticket-alert ticket-alert-success" role="status">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="ticket-alert ticket-alert-error" role="alert">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <strong>Your message could not be sent.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="ticket-layout">
        <main class="ticket-main">
            <section class="ticket-panel">
                <header class="ticket-panel-header">
                    <div>
                        <h2><i class="fas fa-clipboard-list"></i> Request information</h2>
                        <p>Details submitted to the Super Admin support team</p>
                    </div>
                    <span class="ticket-priority {{ $priorityClass }}">
                        {{ ucfirst((string) $ticket->priority) }} priority
                    </span>
                </header>

                <div class="ticket-panel-body">
                    <div class="ticket-info-grid">
                        <div class="ticket-info-item ticket-info-wide">
                            <span class="ticket-label">Subject</span>
                            <strong>{{ $ticket->subject }}</strong>
                        </div>
                        <div class="ticket-info-item">
                            <span class="ticket-label">Category</span>
                            <strong>{{ $categoryName }}</strong>
                        </div>
                        <div class="ticket-info-item">
                            <span class="ticket-label">Ticket number</span>
                            <strong class="ticket-mono">{{ $ticket->ticket_number }}</strong>
                        </div>
                        <div class="ticket-info-item">
                            <span class="ticket-label">Submitted on</span>
                            <strong>{{ $ticket->created_at?->format('d M Y, h:i A') ?? '—' }}</strong>
                        </div>
                    </div>

                    <div class="ticket-description">
                        <span class="ticket-label">Description</span>
                        <p>{{ $ticket->description }}</p>
                    </div>
                </div>
            </section>

            <section class="ticket-panel conversation-panel">
                <header class="ticket-panel-header">
                    <div>
                        <h2><i class="fas fa-comments"></i> Conversation with Super Admin</h2>
                        <p>{{ $replies->count() }} {{ $replies->count() === 1 ? 'message' : 'messages' }} in this request</p>
                    </div>
                </header>

                <div class="ticket-panel-body">
                    @forelse ($replies as $reply)
                        @php
                            $isSuperAdmin = $reply->sender_type === 'super_admin';
                        @endphp

                        <article class="ticket-message {{ $isSuperAdmin ? 'message-super-admin' : 'message-school' }}">
                            <div class="message-header">
                                <div class="message-author">
                                    <span class="message-avatar {{ $isSuperAdmin ? 'avatar-super-admin' : 'avatar-school' }}">
                                        {{ $isSuperAdmin ? 'S' : 'A' }}
                                    </span>
                                    <div>
                                        <strong>{{ $isSuperAdmin ? 'Super Admin' : 'School Admin' }}</strong>
                                        <span>{{ $isSuperAdmin ? 'Platform support team' : 'Your school' }}</span>
                                    </div>
                                </div>
                                <time>{{ $reply->created_at?->format('d M Y, h:i A') ?? '' }}</time>
                            </div>
                            <p class="message-content">{{ $reply->message }}</p>
                        </article>
                    @empty
                        <div class="ticket-empty-conversation">
                            <div class="empty-conversation-icon"><i class="fas fa-comments"></i></div>
                            <h3>No messages yet</h3>
                            <p>Send a message below. The Super Admin's response will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="ticket-panel reply-panel">
                <header class="ticket-panel-header">
                    <div>
                        <h2><i class="fas fa-paper-plane"></i> Send a message</h2>
                        <p>Continue the conversation with the Super Admin team</p>
                    </div>
                </header>

                <div class="ticket-panel-body">
                    @if (strtolower((string) $ticket->status) !== 'closed')
                        <form method="POST" action="{{ route('support-tickets.reply', $ticket->id) }}">
                            @csrf

                            <div class="ticket-form-group">
                                <label for="message">Your message</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="5"
                                    minlength="2"
                                    maxlength="10000"
                                    required
                                    placeholder="Type your message here...">{{ old('message') }}</textarea>
                                <small>Keep your message clear and include any details that will help us assist you.</small>
                                @error('message')
                                    <span class="ticket-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="ticket-submit-btn">
                                <i class="fas fa-paper-plane"></i> Send message
                            </button>
                        </form>
                    @else
                        <div class="ticket-closed-note">
                            <i class="fas fa-lock"></i>
                            <div>
                                <strong>This request is closed</strong>
                                <p>You can no longer reply to this ticket. Contact the Super Admin if you need further assistance.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </main>

        <aside class="ticket-sidebar">
            <section class="ticket-side-card">
                <div class="side-card-icon"><i class="fas fa-info-circle"></i></div>
                <h3>Request status</h3>
                <span class="ticket-status {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', (string) $ticket->status)) }}
                </span>
                <p>
                    @if (in_array(strtolower((string) $ticket->status), ['resolved', 'closed'], true))
                        This request has been closed.
                    @elseif (in_array(strtolower((string) $ticket->status), ['in_progress', 'processing'], true))
                        The support team is working on your request.
                    @elseif (strtolower((string) $ticket->status) === 'pending')
                        Your request is awaiting an update.
                    @else
                        Your request has been submitted and is awaiting support.
                    @endif
                </p>
            </section>

            <section class="ticket-side-card ticket-help-card">
                <div class="side-card-icon"><i class="fas fa-headset"></i></div>
                <h3>Need more help?</h3>
                <p>You can return to Help &amp; Support to browse common issues or create another request.</p>
                <a href="{{ route('support-tickets.index') }}" class="ticket-side-link">
                    Help &amp; Support <i class="fas fa-arrow-right"></i>
                </a>
            </section>
        </aside>
    </div>
</div>

<style>
    .ticket-page {
        --tp-ink: var(--ink, #1d3a33);
        --tp-gold: #e9ad25;
        --tp-muted: var(--muted, #718078);
        --tp-line: var(--line, #e7e0d0);
        color: var(--text, #2c3e3a);
        padding: 4px 0 28px;
    }
    .ticket-page * { box-sizing: border-box; }
    .ticket-page a { text-decoration: none; }
    .ticket-page-heading {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 16px; margin-bottom: 20px;
    }
    .ticket-back-link {
        display: inline-flex; align-items: center; gap: 7px;
        color: var(--tp-muted); font-size: 12px; margin-bottom: 14px;
    }
    .ticket-back-link:hover { color: var(--tp-ink); }
    .ticket-eyebrow {
        color: var(--tp-muted); font-size: 10px; font-weight: 800;
        letter-spacing: 1.3px; margin-bottom: 6px;
    }
    .ticket-eyebrow i { color: var(--tp-gold); margin-right: 5px; }
    .ticket-page-heading h1 {
        color: var(--tp-ink); font-family: var(--serif, Georgia, serif);
        font-size: 26px; font-weight: 700; margin: 0 0 5px;
    }
    .ticket-page-heading p { color: var(--tp-muted); font-size: 12px; margin: 0; }
    .ticket-number {
        display: inline-block; padding: 3px 7px; border-radius: 5px;
        background: #f3f0e7; color: var(--tp-ink);
        font: 700 11px ui-monospace, monospace;
    }

    .ticket-alert {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 12px 15px; border-radius: 9px; margin-bottom: 16px; font-size: 12.5px;
    }
    .ticket-alert-success { color: #286342; background: #edf7ef; border: 1px solid #cfe5d3; }
    .ticket-alert-error { color: #923f36; background: #fff1ee; border: 1px solid #f0d1ca; }
    .ticket-alert ul { margin: 5px 0 0; padding-left: 18px; }

    .ticket-layout {
        display: grid; grid-template-columns: minmax(0, 1fr) 255px;
        gap: 18px; align-items: start;
    }
    .ticket-main { min-width: 0; }
    .ticket-panel, .ticket-side-card {
        background: #fff; border: 1px solid var(--tp-line);
        border-radius: 10px; overflow: hidden; margin-bottom: 17px;
    }
    .ticket-panel-header {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; padding: 15px 18px; border-bottom: 1px dashed var(--tp-line);
    }
    .ticket-panel-header h2 {
        color: var(--tp-ink); font-family: var(--serif, Georgia, serif);
        font-size: 16px; font-weight: 700; margin: 0;
    }
    .ticket-panel-header h2 i { color: #b38b31; font-size: 13px; margin-right: 5px; }
    .ticket-panel-header p { color: var(--tp-muted); font-size: 11.5px; margin: 4px 0 0; }
    .ticket-panel-body { padding: 18px; }

    .ticket-status, .ticket-priority {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 9px; border-radius: 20px; font-size: 10.5px;
        font-weight: 800; white-space: nowrap;
    }
    .ticket-status::before, .ticket-priority::before {
        content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor;
    }
    .status-open { color: #2b5d8a; background: #edf3ff; }
    .status-progress { color: #a66b13; background: #fff5df; }
    .status-resolved { color: #2d8050; background: #edf8f0; }
    .status-pending, .status-default { color: #6b756f; background: #f1f0ea; }
    .priority-high { color: #a84840; background: #fff2ee; border: 1px solid #f0d5ce; }
    .priority-medium { color: #8a682b; background: #fbf3df; border: 1px solid #eee0b9; }
    .priority-low { color: #2f6b45; background: #edf7ed; border: 1px solid #cfe5d3; }
    .priority-default { color: #69776f; background: #f4f1e8; border: 1px solid var(--tp-line); }

    .ticket-info-grid {
        display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;
        margin-bottom: 17px;
    }
    .ticket-info-wide { grid-column: 1 / -1; }
    .ticket-info-item { display: flex; flex-direction: column; gap: 5px; min-width: 0; }
    .ticket-label {
        color: var(--tp-muted); font-size: 10px; font-weight: 800;
        letter-spacing: .5px; text-transform: uppercase;
    }
    .ticket-info-item strong { color: var(--tp-ink); font-size: 13px; overflow-wrap: anywhere; }
    .ticket-mono { font-family: ui-monospace, monospace; }
    .ticket-description {
        padding: 14px; border: 1px solid #ebe5d7; border-radius: 8px; background: #fffdf8;
    }
    .ticket-description p {
        color: #40534b; font-size: 12.5px; line-height: 1.7;
        white-space: pre-line; margin: 7px 0 0;
    }

    .ticket-message {
        padding: 14px; border: 1px solid #e9e3d5; border-radius: 9px;
        background: #fffdf8; margin-bottom: 12px;
    }
    .ticket-message:last-child { margin-bottom: 0; }
    .message-super-admin { background: #f4faf5; border-color: #d5e8d9; }
    .message-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 10px; }
    .message-author { display: flex; align-items: center; gap: 9px; }
    .message-avatar {
        display: flex; align-items: center; justify-content: center;
        width: 34px; height: 34px; border-radius: 9px; font-size: 12px; font-weight: 800;
    }
    .avatar-super-admin { color: #2f7658; background: #e1f1e5; }
    .avatar-school { color: #2b5d8a; background: #e8effb; }
    .message-author strong { display: block; color: var(--tp-ink); font-size: 12px; }
    .message-author span:not(.message-avatar) { display: block; color: var(--tp-muted); font-size: 10.5px; margin-top: 2px; }
    .message-header time { color: var(--tp-muted); font-size: 10.5px; white-space: nowrap; }
    .message-content { color: #40534b; font-size: 12.5px; line-height: 1.7; white-space: pre-line; margin: 0; }

    .ticket-empty-conversation { text-align: center; padding: 25px 12px; }
    .empty-conversation-icon {
        display: flex; align-items: center; justify-content: center;
        width: 48px; height: 48px; border-radius: 12px; margin: 0 auto 10px;
        color: var(--tp-ink); background: #f6efd9; font-size: 19px;
    }
    .ticket-empty-conversation h3 { color: var(--tp-ink); font: 700 16px var(--serif, Georgia, serif); margin: 0 0 5px; }
    .ticket-empty-conversation p { color: var(--tp-muted); font-size: 11.5px; margin: 0; }

    .ticket-form-group { display: flex; flex-direction: column; gap: 7px; }
    .ticket-form-group label { color: var(--tp-ink); font-size: 12px; font-weight: 700; }
    .ticket-form-group textarea {
        width: 100%; min-height: 125px; resize: vertical; padding: 11px 12px;
        border: 1px solid #dcd5c5; border-radius: 8px; background: #fff;
        color: #2c3e3a; font: inherit; font-size: 12.5px; outline: none;
    }
    .ticket-form-group textarea:focus { border-color: var(--tp-ink); box-shadow: 0 0 0 3px rgba(29,58,51,.08); }
    .ticket-form-group small { color: var(--tp-muted); font-size: 10.5px; }
    .ticket-field-error { color: #b33d35; font-size: 11px; }
    .ticket-submit-btn {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 14px;
        padding: 10px 15px; border: 1px solid var(--tp-ink); border-radius: 8px;
        background: var(--tp-ink); color: #fff; font-size: 12px; font-weight: 700; cursor: pointer;
    }
    .ticket-submit-btn:hover { background: #284d43; }

    .ticket-sidebar { position: sticky; top: 18px; }
    .ticket-side-card { padding: 17px; }
    .side-card-icon {
        display: flex; align-items: center; justify-content: center;
        width: 38px; height: 38px; border-radius: 10px;
        color: var(--tp-ink); background: #f6efd9; margin-bottom: 11px;
    }
    .ticket-side-card h3 { color: var(--tp-ink); font: 700 15px var(--serif, Georgia, serif); margin: 0 0 9px; }
    .ticket-side-card p { color: var(--tp-muted); font-size: 11.5px; line-height: 1.6; margin: 10px 0 0; }
    .ticket-side-link {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
        color: var(--tp-ink); font-size: 11.5px; font-weight: 800;
        padding-top: 12px; margin-top: 12px; border-top: 1px solid var(--tp-line);
    }
    .ticket-closed-note {
        display: flex; align-items: flex-start; gap: 10px; padding: 13px;
        border: 1px solid #e9dfc6; border-radius: 8px; background: #fbf7eb; color: #75602f;
    }
    .ticket-closed-note > i { margin-top: 2px; }
    .ticket-closed-note strong { display: block; font-size: 12px; }
    .ticket-closed-note p { color: #786e56; font-size: 11px; line-height: 1.5; margin: 4px 0 0; }

    @media (max-width: 900px) {
        .ticket-layout { grid-template-columns: minmax(0, 1fr); }
        .ticket-sidebar { position: static; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    }
    @media (max-width: 600px) {
        .ticket-page-heading { flex-direction: column; }
        .ticket-info-grid { grid-template-columns: 1fr; }
        .ticket-info-wide { grid-column: auto; }
        .ticket-sidebar { grid-template-columns: 1fr; }
        .message-header { flex-direction: column; }
    }
</style>
@endsection
