@extends('super-admin.layouts.app')

@section('title', 'Support Requests')
@section('breadcrumb', 'Support / Requests')
@section('page-title', 'Support Request Management')

@section('content')

@php
    $priorityClass = function ($priority) {
        return match (strtolower((string) $priority)) {
            'urgent', 'high' => 'inactive',
            'medium'         => 'other',
            'low'            => 'active',
            default          => 'other',
        };
    };

    $statusClass = function ($status) {
        return match (strtolower((string) $status)) {
            'open'        => 'other',
            'in_progress' => 'other',
            'resolved'    => 'active',
            'closed'      => 'inactive',
            default       => 'other',
        };
    };
@endphp

<div class="welcome">
    <div>
        <div class="eyebrow">Support desk</div>
        <h2>Support Request Management</h2>
        <p>Review, filter and track support tickets submitted by schools across the platform.</p>
    </div>
</div>

{{-- KPIs --}}
<div class="kpis" style="margin-bottom:28px;">
    <div class="kpi blue">
        <div class="kpi-top">
            <span class="kpi-label">Total requests</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <path d="M4 4h16v12H5.17L4 17.17V4z"/>
                    <path d="M8 8h8M8 12h5"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($totalRequests) }}</div>
        <div class="kpi-foot"><span class="dot"></span> All submitted tickets</div>
    </div>

    <div class="kpi amber">
        <div class="kpi-top">
            <span class="kpi-label">Open requests</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v4l2.5 2.5"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($openRequests) }}</div>
        <div class="kpi-foot"><span class="dot"></span> Awaiting action</div>
    </div>

    <div class="kpi blue">
        <div class="kpi-top">
            <span class="kpi-label">In progress</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($inProgressRequests) }}</div>
        <div class="kpi-foot"><span class="dot"></span> Currently being handled</div>
    </div>

    <div class="kpi green">
        <div class="kpi-top">
            <span class="kpi-label">Resolved</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($resolvedRequests) }}</div>
        <div class="kpi-foot"><span class="dot"></span> Completed requests</div>
    </div>
</div>

{{-- Directory --}}
<div class="section-heading">
    <div>
        <h3>All support requests</h3>
        <p>Search by request ID, subject or school name</p>
    </div>
    <span class="count-chip">{{ $requests->total() }} records</span>
</div>

<div class="panel">
    <div class="toolbar">
        <form method="GET"
              action="{{ route('super-admin.support-requests.index') }}"
              class="support-filters">

            <label class="search support-filters__search">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-3.5-3.5"/>
                </svg>
                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search ID, subject or school..."
                       aria-label="Search requests">
            </label>

            <select name="status" class="filter-select" aria-label="Status">
                <option value="">All statuses</option>
                <option value="open" @selected(request('status') === 'open')>Open</option>
                <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                <option value="closed" @selected(request('status') === 'closed')>Closed</option>
            </select>

            <select name="priority" class="filter-select" aria-label="Priority">
                <option value="">All priorities</option>
                <option value="low" @selected(request('priority') === 'low')>Low</option>
                <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                <option value="high" @selected(request('priority') === 'high')>High</option>
                <option value="urgent" @selected(request('priority') === 'urgent')>Urgent</option>
            </select>

            <select name="category" class="filter-select" aria-label="Category">
                <option value="">All categories</option>
                <option value="technical" @selected(request('category') === 'technical')>Technical</option>
                <option value="login" @selected(request('category') === 'login')>Login</option>
                <option value="billing" @selected(request('category') === 'billing')>Billing</option>
                <option value="student_management" @selected(request('category') === 'student_management')>Student Management</option>
                <option value="backup" @selected(request('category') === 'backup')>Backup</option>
                <option value="general" @selected(request('category') === 'general')>General</option>
                <option value="complaint" @selected(request('category') === 'complaint')>Complaint</option>
                <option value="other" @selected(request('category') === 'other')>Other</option>
            </select>

            <div class="support-filters__actions">
                <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                    Apply
                </button>
                <a href="{{ route('super-admin.support-requests.index') }}" class="chip">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table class="support-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>School</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $requestItem)
                    <tr>
                        <td>
    <a href="{{ route('super-admin.support-requests.show', $requestItem->id) }}"
       class="code-chip text-decoration-none">
        {{ $requestItem->ticket_number }}
    </a>
</td>

                        <td>
                            <div class="school-cell">
                                <div class="school-avatar">
                                    {{ strtoupper(substr($requestItem->school?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="school-name">
                                    {{ $requestItem->school?->name ?? 'Unknown School' }}
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="subject-cell">
                                {{ \Illuminate\Support\Str::limit($requestItem->subject, 48) }}
                            </div>
                        </td>

                        <td>
                            {{ ucwords(str_replace('_', ' ', $requestItem->category)) }}
                        </td>

                        <td>
                            <span class="badge {{ $priorityClass($requestItem->priority) }}">
                                {{ ucfirst($requestItem->priority) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge {{ $statusClass($requestItem->status) }}">
                                {{ ucwords(str_replace('_', ' ', $requestItem->status)) }}
                            </span>
                        </td>

                        <td class="cell-date">
                            {{ $requestItem->created_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td>
                            @if(\Illuminate\Support\Facades\Route::has('super-admin.support-requests.show'))
                                <a href="{{ route('super-admin.support-requests.show', $requestItem) }}"
                                   class="view-link">
                                    View
                                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="8">
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg class="i" viewBox="0 0 24 24">
                                        <path d="M4 4h16v12H5.17L4 17.17V4z"/>
                                        <path d="M8 8h8M8 12h5"/>
                                    </svg>
                                </div>
                                <h4>No support requests found</h4>
                                <p>Try changing your search or filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($requests->hasPages())
    <div class="panel-foot support-pagination">
        <span>
            Showing <strong>{{ $requests->firstItem() ?? 0 }}</strong>
            to <strong>{{ $requests->lastItem() ?? 0 }}</strong>
            of <strong>{{ $requests->total() }}</strong> requests
        </span>
        <div class="pagination-wrap">
            {{ $requests->withQueryString()->links('pagination::simple-tailwind') }}
        </div>
    </div>
@endif

<style>
    .support-filters {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        width: 100%;
    }

    .support-filters__search {
        flex: 1 1 220px;
        min-width: 180px;
    }

    .filter-select {
        height: 40px;
        padding: 0 12px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 9px;
        background: #fff;
        color: var(--ink, #111827);
        font-size: 13.5px;
        min-width: 130px;
    }

    .filter-select:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, .18);
    }

    .support-filters__actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }

    .support-table th {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: uppercase;
        color: #6b7280;
        white-space: nowrap;
    }

    .support-table td {
        vertical-align: middle;
        font-size: 13.5px;
        color: #374151;
    }

    .school-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .school-avatar {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #edf3ff;
        color: #4778c7;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .school-name {
        font-weight: 700;
        color: #111827;
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .subject-cell {
        max-width: 260px;
        color: #24332f;
        font-weight: 600;
    }

    .cell-date {
        white-space: nowrap;
        color: #6b7280;
        font-variant-numeric: tabular-nums;
    }

    .view-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #2f7658;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .view-link:hover { color: #245d45; }

    .view-link .i {
        width: 14px;
        height: 14px;
    }

    .empty {
        padding: 48px 24px;
        text-align: center;
    }

    .empty h4 {
        margin: 12px 0 6px;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .empty p {
        margin: 0;
        color: #6b7280;
        font-size: 13.5px;
    }

    .support-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
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
        border: 1px solid #e5ebe6;
        border-radius: 8px;
        background: #fff;
        color: #52665b;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s ease, border-color .15s ease, color .15s ease;
    }

    .pagination-wrap a:hover {
        background: #f3faf5;
        border-color: #cfe0d5;
        color: #2f7658;
    }

    .pagination-wrap span[aria-current="page"],
    .pagination-wrap .active span {
        background: #172b27;
        border-color: #172b27;
        color: #fff;
    }

    .pagination-wrap span[aria-disabled="true"],
    .pagination-wrap span.disabled {
        opacity: .4;
        background: #f7f9f7;
        cursor: default;
    }

    @media (max-width: 900px) {
        .support-filters__actions {
            margin-left: 0;
            width: 100%;
        }

        .support-filters__actions .welcome-action,
        .support-filters__actions .chip {
            flex: 1;
            justify-content: center;
            text-align: center;
        }

        .support-pagination {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

@endsection