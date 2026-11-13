
@extends('super-admin.layouts.app')

@section('title', 'Audit Logs')
@section('breadcrumb', 'Settings / Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')

<style>
    .audit-page {
        display: grid;
        gap: 22px;
        color: var(--ink);
    }

    .audit-intro {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 23px 25px;
        border: 1px solid var(--line);
        border-radius: 15px;
        background: linear-gradient(120deg, #fff 65%, #edf5ef);
        box-shadow: var(--shadow);
    }

    .audit-intro-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .audit-intro-icon {
        display: grid;
        place-items: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--green-soft);
        color: var(--green);
        font-size: 20px;
    }

    .audit-intro h2 {
        margin: 0;
        font-family: Manrope, sans-serif;
        font-size: 17px;
        font-weight: 800;
    }

    .audit-intro p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .audit-platform-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 12px;
        border: 1px solid #dcebe0;
        border-radius: 8px;
        background: #f5faf6;
        color: var(--green);
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .audit-panel {
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--surface);
        box-shadow: var(--shadow);
    }

    .audit-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid var(--line);
    }

    .audit-panel-head h3 {
        margin: 0;
        font-family: Manrope, sans-serif;
        font-size: 16px;
        font-weight: 800;
    }

    .audit-panel-head p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 12px;
    }

    .audit-count {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--canvas);
        color: var(--ink);
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .audit-filters {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        padding: 21px 22px;
    }

    .audit-field label {
        display: block;
        margin-bottom: 7px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
    }

    .audit-field input,
    .audit-field select {
        width: 100%;
        height: 41px;
        padding: 0 11px;
        border: 1px solid var(--line);
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: var(--ink);
        font-size: 12px;
    }

    .audit-field input:focus,
    .audit-field select:focus {
        border-color: var(--green);
        box-shadow: 0 0 0 3px rgba(47, 118, 88, .10);
    }

    .audit-filter-actions {
        display: flex;
        align-items: end;
        gap: 9px;
    }

    .audit-apply,
    .audit-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 41px;
        padding: 0 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .audit-apply {
        border: 1px solid var(--green);
        background: var(--green);
        color: #fff;
    }

    .audit-apply:hover {
        background: var(--navy-2);
        color: #fff;
    }

    .audit-reset {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--muted);
    }

    .audit-reset:hover {
        border-color: var(--orange);
        color: var(--orange);
    }

    .audit-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 22px;
        border-bottom: 1px solid var(--line);
    }

    .audit-result-info {
        color: var(--muted);
        font-size: 12px;
    }

    .audit-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .audit-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        text-align: left;
    }

    .audit-table th {
        padding: 13px 16px;
        background: #f7f9f7;
        color: #78877e;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .8px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .audit-table td {
        padding: 15px 16px;
        border-top: 1px solid #edf1ed;
        color: var(--ink);
        font-size: 12px;
        vertical-align: middle;
    }

    .audit-table tbody tr:hover {
        background: #fbfdfb;
    }

    .audit-activity {
        min-width: 210px;
    }

    .audit-description {
        margin-top: 7px;
        color: var(--ink);
        font-size: 12px;
        font-weight: 600;
        line-height: 1.5;
    }

    .audit-subject,
    .audit-secondary {
        display: block;
        margin-top: 4px;
        color: var(--muted);
        font-size: 10px;
    }

    .audit-actor {
        font-weight: 700;
    }

    .audit-role {
        display: inline-flex;
        margin-top: 5px;
        padding: 4px 7px;
        border-radius: 5px;
        background: var(--green-soft);
        color: var(--green);
        font-size: 10px;
        font-weight: 700;
    }

    .audit-role.school {
        background: var(--blue-soft);
        color: var(--blue);
    }

    .audit-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 6px;
        background: #edf3ff;
        color: var(--blue);
        font-size: 10px;
        font-weight: 800;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .audit-action.created {
        background: var(--green-soft);
        color: var(--green);
    }

    .audit-action.updated,
    .audit-action.status_changed {
        background: var(--amber-soft);
        color: var(--amber);
    }

    .audit-action.deleted {
        background: var(--red-soft);
        color: var(--red);
    }

    .audit-action.login {
        background: var(--blue-soft);
        color: var(--blue);
    }

    .audit-action.logout {
        background: #f0edf7;
        color: #7659a8;
    }

    .audit-module {
        display: inline-block;
        padding: 5px 8px;
        border: 1px solid var(--line);
        border-radius: 6px;
        background: #fff;
        color: #52635a;
        font-size: 10px;
        font-weight: 700;
    }

    .audit-school {
        font-weight: 600;
    }

    .audit-ip {
        color: var(--muted);
        font-size: 11px;
        white-space: nowrap;
    }

    .audit-date {
        font-weight: 700;
        white-space: nowrap;
    }

    .audit-details {
        margin-top: 8px;
    }

    .audit-details summary {
        color: var(--green);
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .audit-details pre {
        max-width: 320px;
        margin: 9px 0;
        padding: 11px;
        overflow-x: auto;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--canvas);
        color: var(--ink);
        font-size: 10px;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .audit-empty {
        padding: 55px 20px !important;
        text-align: center;
    }

    .audit-empty-icon {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        margin: 0 auto 13px;
        border-radius: 14px;
        background: var(--green-soft);
        color: var(--green);
        font-size: 19px;
    }

    .audit-empty h4 {
        margin: 0;
        font-family: Manrope, sans-serif;
        font-size: 14px;
        font-weight: 800;
    }

    .audit-empty p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 12px;
    }

    .audit-pagination {
        padding: 17px 22px;
        border-top: 1px solid var(--line);
    }

    .audit-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .audit-pagination svg {
        width: 16px;
        height: 16px;
    }

    .audit-pagination p {
        color: var(--muted);
        font-size: 12px;
    }

    @media(max-width: 1050px) {
        .audit-filters {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width: 650px) {
        .audit-intro {
            align-items: flex-start;
            flex-direction: column;
            padding: 18px;
        }

        .audit-platform-chip {
            margin-left: 64px;
        }

        .audit-panel-head,
        .audit-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .audit-filters {
            grid-template-columns: 1fr;
            padding: 17px;
        }

        .audit-filter-actions {
            width: 100%;
        }

        .audit-apply,
        .audit-reset {
            flex: 1;
        }
    }
</style>

<div class="audit-page">

    {{-- Introduction --}}
    <section class="audit-intro">
        <div class="audit-intro-content">
            <div class="audit-intro-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>

            <div>
                <h2>Platform activity history</h2>
                <p>
                    Review important actions performed by Super Admins and
                    School Admins across the School Central platform.
                </p>
            </div>
        </div>

        <span class="audit-platform-chip">
            <i class="fas fa-shield-halved"></i>
            Platform monitoring
        </span>
    </section>

    {{-- Filters --}}
    <section class="audit-panel">

        <div class="audit-panel-head">
            <div>
                <h3>Filter activity</h3>
                <p>Find specific activities using the available filters.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('super-admin.audit-logs.index') }}">

            <div class="audit-filters">

                <div class="audit-field">
                    <label for="audit-search">Search activity</label>
                    <input
                        type="text"
                        id="audit-search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Description, user ID or IP">
                </div>

                <div class="audit-field">
                    <label for="audit-action">Action</label>
                    <select name="action" id="audit-action">
                        <option value="">All actions</option>

                        @foreach([
                            'created',
                            'updated',
                            'deleted',
                            'login',
                            'logout',
                            'status_changed'
                        ] as $action)
                            <option value="{{ $action }}"
                                @selected(request('action') === $action)>
                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="audit-field">
                    <label for="audit-module">Module</label>
                    <select name="module" id="audit-module">
                        <option value="">All modules</option>

                        @foreach([
                            'users',
                            'schools',
                            'roles',
                            'subscriptions',
                            'payments',
                            'support',
                            'security',
                            'reports'
                        ] as $module)
                            <option value="{{ $module }}"
                                @selected(request('module') === $module)>
                                {{ ucfirst(str_replace('_', ' ', $module)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="audit-field">
                    <label for="audit-actor">Performed by</label>
                    <select name="actor_type" id="audit-actor">
                        <option value="">Everyone</option>
                        <option value="super_admin"
                            @selected(request('actor_type') === 'super_admin')>
                            Super Admin
                        </option>
                        <option value="web"
                            @selected(request('actor_type') === 'web')>
                            School Admin
                        </option>
                    </select>
                </div>

                <div class="audit-field">
                    <label for="audit-school">School ID</label>
                    <input
                        type="number"
                        id="audit-school"
                        name="school_id"
                        min="1"
                        value="{{ request('school_id') }}"
                        placeholder="Enter school ID">
                </div>

                <div class="audit-field">
                    <label for="audit-from">From date</label>
                    <input
                        type="date"
                        id="audit-from"
                        name="from_date"
                        value="{{ request('from_date') }}">
                </div>

                <div class="audit-field">
                    <label for="audit-to">To date</label>
                    <input
                        type="date"
                        id="audit-to"
                        name="to_date"
                        value="{{ request('to_date') }}">
                </div>

                <div class="audit-filter-actions">
                    <button type="submit" class="audit-apply">
                        <i class="fas fa-filter"></i>&nbsp; Apply filters
                    </button>

                    <a href="{{ route('super-admin.audit-logs.index') }}"
                       class="audit-reset">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </section>

    {{-- Activity history --}}
    <section class="audit-panel">

        <div class="audit-panel-head">
            <div>
                <h3>Activity history</h3>
                <p>Latest recorded platform activities.</p>
            </div>

            <span class="audit-count">
                {{ number_format($auditLogs->total()) }}
                &nbsp;{{ $auditLogs->total() === 1 ? 'record' : 'records' }}
            </span>
        </div>

        <div class="audit-toolbar">
            <span class="audit-result-info">
                Showing {{ $auditLogs->firstItem() ?? 0 }}
                to {{ $auditLogs->lastItem() ?? 0 }}
                of {{ number_format($auditLogs->total()) }} activities
            </span>

            <span class="audit-result-info">
                <i class="fas fa-clock"></i>
                Latest activity first
            </span>
        </div>

        <div class="audit-table-wrap">

            <table class="audit-table">

                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Performed by</th>
                        <th>Module</th>
                        <th>School</th>
                        <th>IP address</th>
                        <th>Date & time</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($auditLogs as $log)

                        @php
                            $actionClass = strtolower($log->action);
                            $actorLabel = $log->actor_type === 'super_admin'
                                ? 'Super Admin'
                                : ($log->actor_type === 'web' ? 'School Admin' : 'System');
                        @endphp

                        <tr>

                            {{-- Activity --}}
                            <td class="audit-activity">

                                <span class="audit-action {{ $actionClass }}">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>

                                <div class="audit-description">
                                    {{ $log->description ?: 'Activity recorded' }}
                                </div>

                                @if($log->subject_type || $log->subject_id)
                                    <span class="audit-subject">
                                        {{ class_basename($log->subject_type ?? 'Unknown') }}
                                        #{{ $log->subject_id ?? 'N/A' }}
                                    </span>
                                @endif

                                @if($log->old_values || $log->new_values)
                                    <details class="audit-details">
                                        <summary>
                                            <i class="fas fa-code-branch"></i>
                                            View changes
                                        </summary>

                                        @if($log->old_values)
                                            <strong>Previous values</strong>
                                            <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        @endif

                                        @if($log->new_values)
                                            <strong>New values</strong>
                                            <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        @endif
                                    </details>
                                @endif

                            </td>

                            {{-- Actor --}}
                            <td>
                                <span class="audit-actor">
                                    {{ $log->user?->name ?? 'Unknown User' }}
                                </span>

                                <span class="audit-role {{ $log->actor_type === 'web' ? 'school' : '' }}">
                                    {{ $actorLabel }}
                                </span>

                                <span class="audit-secondary">
                                    User ID: {{ $log->user_id ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Module --}}
                            <td>
                                <span class="audit-module">
                                    {{ ucfirst(str_replace('_', ' ', $log->module)) }}
                                </span>
                            </td>

                            {{-- School --}}
                            <td>
                                <span class="audit-school">
                                    {{ $log->school?->name ?? ($log->school_id ? 'School #' . $log->school_id : 'Platform') }}
                                </span>
                            </td>

                            {{-- IP address --}}
                            <td>
                                <span class="audit-ip">
                                    {{ $log->ip_address ?? '—' }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td>
                                <span class="audit-date">
                                    {{ $log->created_at?->format('d M Y') ?? '—' }}
                                </span>

                                <span class="audit-secondary">
                                    {{ $log->created_at?->format('h:i A') ?? '' }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="audit-empty">

                                <div class="audit-empty-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>

                                <h4>No activity records found</h4>

                                <p>
                                    No activities match your current filters.
                                    Try changing or resetting your filters.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- Pagination --}}
        @if($auditLogs->hasPages())
            <div class="audit-pagination">
                {{ $auditLogs->links() }}
            </div>
        @endif

    </section>

</div>

@endsection
