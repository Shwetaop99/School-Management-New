@extends('layouts.app')

@section('title', 'Support & Complaints')
@section('page-title', 'Support & Complaints')

@section('content')

@php
    $priorityClass = function ($priority) {
        return match (strtolower((string) $priority)) {
            'urgent', 'critical', 'high' => 'p-high',
            'medium', 'normal'           => 'p-medium',
            'low'                        => 'p-low',
            default                      => 'p-none',
        };
    };

    $statusClass = function ($status) {
        return match (strtolower((string) $status)) {
            'open', 'new'                => 's-open',
            'in_progress', 'processing'  => 's-progress',
            'resolved', 'closed'         => 's-resolved',
            'pending'                    => 's-pending',
            default                      => 's-default',
        };
    };
@endphp

<div class="tickets-page">

    <div class="tickets-top">
        <div>
            <h2>Support &amp; Complaints</h2>
            <p>View and manage your support requests.</p>
        </div>

        <a href="{{ route('admin.support-tickets.create') }}" class="btn-solid">
            <i class="fas fa-plus"></i>
            Create new
        </a>
    </div>

    @if (session('success'))
        <div class="note-box" role="status">
            <i class="fas fa-circle-check"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <section class="paper panel">
        <div class="panel-head">
            <div>
                <h3>My support tickets</h3>
                <span>All requests submitted from your school</span>
            </div>
            <span class="count-chip">{{ $tickets->total() }} total</span>
        </div>

        <div class="table-wrap">
            <table class="tickets-table">
                <thead>
                    <tr>
                        <th>Ticket no.</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>
                                <span class="ticket-no">{{ $ticket->ticket_number }}</span>
                            </td>

                            <td>
                                <div class="ticket-subject">{{ $ticket->subject }}</div>
                            </td>

                            <td>
                                <span class="category-chip">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                                </span>
                            </td>

                            <td>
                                <span class="priority {{ $priorityClass($ticket->priority) }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>

                            <td>
                                <span class="status-badge {{ $statusClass($ticket->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>

                            <td class="cell-date">
                                {{ $ticket->created_at?->format('d M Y') ?? '—' }}
                            </td>

                            <td>
                                <a href="{{ route('admin.support-tickets.show', $ticket->id) }}"
                                   class="btn-line btn-sm">
                                    View
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="7">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <i class="fas fa-life-ring"></i>
                                    </div>
                                    <h4>No support tickets found</h4>
                                    <p>You haven’t raised any support requests yet.</p>
                                    <a href="{{ route('admin.support-tickets.create') }}" class="btn-solid" style="margin-top:14px;">
                                        <i class="fas fa-plus"></i>
                                        Create new
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tickets->hasPages())
            <div class="panel-foot">
                <span>
                    Showing <strong>{{ $tickets->firstItem() ?? 0 }}</strong>
                    to <strong>{{ $tickets->lastItem() ?? 0 }}</strong>
                    of <strong>{{ $tickets->total() }}</strong> tickets
                </span>
                <div class="pagination-wrap">
                    {{ $tickets->withQueryString()->links('pagination::simple-tailwind') }}
                </div>
            </div>
        @elseif ($tickets->total() > 0)
            <div class="panel-foot">
                <span>
                    Showing <strong>{{ $tickets->total() }}</strong>
                    {{ $tickets->total() === 1 ? 'ticket' : 'tickets' }}
                </span>
            </div>
        @endif
    </section>

</div>

<style>
    .tickets-page {
        --t-ink: var(--ink, #1d3a33);
        --t-ink2: var(--ink-2, #16302a);
        --t-line: var(--line, #d8d1bf);
        --t-muted: var(--muted, #6b7c75);
        --t-text: var(--text, #2c3e3a);
        --t-serif: var(--serif, Georgia, "Times New Roman", serif);
        max-width: 1500px;
        margin: 0 auto;
        color: var(--t-text);
        font-size: 13.5px;
    }

    .tickets-page a { text-decoration: none; }

    .tickets-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .tickets-top h2 {
        margin: 0 0 6px;
        font-family: var(--t-serif);
        font-size: 26px;
        font-weight: 700;
        color: var(--t-ink);
    }

    .tickets-top p {
        margin: 0;
        color: var(--t-muted);
        font-size: 14px;
    }

    .paper {
        background: #fff;
        border: 1px solid var(--t-line);
        border-radius: 10px;
    }

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

    .panel-head span:not(.count-chip) {
        display: block;
        margin-top: 3px;
        font-size: 12px;
        color: var(--t-muted);
    }

    .count-chip {
        padding: 6px 10px;
        border: 1px solid var(--t-line);
        border-radius: 8px;
        background: #faf8f2;
        color: #5e6d66;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

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

    .btn-solid,
    .btn-line {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        transition: background .15s, border-color .15s, color .15s;
    }

    .btn-solid {
        border: 1px solid var(--t-ink);
        background: var(--t-ink);
        color: #fff;
    }

    .btn-solid:hover {
        background: var(--t-ink2);
        border-color: var(--t-ink2);
        color: #fff;
    }

    .btn-line {
        border: 1px solid #cfc8b6;
        background: #fff;
        color: var(--t-text);
    }

    .btn-line:hover {
        border-color: var(--t-ink);
        color: var(--t-ink);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .table-wrap { overflow-x: auto; }

    .tickets-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 780px;
    }

    .tickets-table th {
        padding: 12px 18px;
        background: #f8f6f0;
        color: #87938d;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .6px;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .tickets-table td {
        padding: 14px 18px;
        border-top: 1px solid #efebe1;
        vertical-align: middle;
        font-size: 13.5px;
        color: #3d4a45;
    }

    .tickets-table tbody tr:hover td {
        background: #fbfaf6;
    }

    .ticket-no {
        display: inline-block;
        padding: 4px 9px;
        border-radius: 6px;
        background: #f0f4f8;
        color: #426b8b;
        font-size: 12px;
        font-weight: 800;
        font-family: ui-monospace, monospace;
    }

    .ticket-subject {
        font-weight: 700;
        color: var(--t-ink);
        max-width: 260px;
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
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
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

    .cell-date {
        white-space: nowrap;
        color: var(--t-muted);
        font-variant-numeric: tabular-nums;
    }

    .empty {
        padding: 48px 20px;
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

    .panel-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 12px 18px;
        border-top: 1px solid #efebe1;
        background: #fbfaf6;
        color: var(--t-muted);
        font-size: 12.5px;
    }

    .panel-foot strong {
        color: var(--t-ink);
    }

    .pagination-wrap nav,
    .pagination-wrap .flex {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination-wrap a,
    .pagination-wrap span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 11px;
        border: 1px solid #e6dfce;
        border-radius: 8px;
        background: #fff;
        color: #52665b;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
    }

    .pagination-wrap a:hover {
        background: #f4efdd;
        border-color: #d8d1bf;
        color: var(--t-ink);
    }

    .pagination-wrap span[aria-current="page"],
    .pagination-wrap .active span {
        background: var(--t-ink);
        border-color: var(--t-ink);
        color: #fff;
    }

    .pagination-wrap span[aria-disabled="true"] {
        opacity: .4;
        background: #f8f6f0;
    }

    @media (max-width: 700px) {
        .tickets-top {
            flex-direction: column;
        }

        .tickets-top .btn-solid {
            width: 100%;
        }

        .panel-foot {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

@endsection