@extends('layouts.app')

@section('title', 'Activity Log | Admin')
@section('page-title', 'Activity Log')

@section('content')

<div class="activity-page">
    <div class="container-fluid py-4">

        <section class="activity-panel">

            {{-- PANEL HEADER --}}
            <div class="activity-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h3>Activity Log</h3>
                        <p>See who added, changed or deleted records, and when.</p>
                    </div>
                </div>

                <div class="activity-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($logs->total()) }}</strong>
                    Entries
                </div>
            </div>

            {{-- FILTERS --}}
            <form method="GET" class="activity-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div>
                        <h4>Filter Activity</h4>
                        <p>Narrow the list by action, record type, user or date.</p>
                    </div>
                </div>

                <div class="activity-filter-grid">

                    <div class="act-field">
                        <label for="fEvent">Action</label>
                        <select id="fEvent" name="event" class="act-input">
                            <option value="">All actions</option>
                            @foreach (['created', 'updated', 'deleted'] as $e)
                                <option value="{{ $e }}" @selected(request('event') === $e)>{{ ucfirst($e) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="act-field">
                        <label for="fModel">Record type</label>
                        <select id="fModel" name="model" class="act-input">
                            <option value="">All records</option>
                            @foreach ($models as $m)
                                <option value="{{ $m }}" @selected(request('model') === $m)>{{ class_basename($m) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="act-field">
                        <label for="fUser">User</label>
                        <select id="fUser" name="user" class="act-input">
                            <option value="">All users</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" @selected((string) request('user') === (string) $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="act-field">
                        <label for="fFrom">From</label>
                        <input id="fFrom" type="date" name="from" value="{{ request('from') }}" class="act-input">
                    </div>

                    <div class="act-field">
                        <label for="fTo">To</label>
                        <input id="fTo" type="date" name="to" value="{{ request('to') }}" class="act-input">
                    </div>

                    <div class="act-actions">
                        <button type="submit" class="act-btn act-btn-apply">
                            <i class="fas fa-search"></i> Apply
                        </button>
                        <a href="{{ route('admin.activity-log.index') }}" class="act-btn act-btn-reset">
                            <i class="fas fa-rotate-left"></i> Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- TABLE --}}
            <div class="activity-table-wrap">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>When</th>
                            <th>Who</th>
                            <th>Action</th>
                            <th>Record</th>
                            <th>Changes (old &rarr; new)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            @php
                                $new  = collect($log->properties['attributes'] ?? []);
                                $old  = collect($log->properties['old'] ?? []);
                                $keys = $old->keys()->merge($new->keys())->unique()
                                    ->reject(fn ($k) => in_array($k, ['updated_at', 'created_at', 'deleted_at']))
                                    ->values();
                                $show = fn ($v) => \Illuminate\Support\Str::limit(
                                    is_array($v) ? json_encode($v) : (is_null($v) ? 'empty' : (string) $v),
                                    40
                                );
                                $who  = $log->causer->name ?? 'System';
                            @endphp
                            <tr>
                                <td class="text-nowrap">
                                    <strong>{{ $log->created_at->format('d M Y') }}</strong>
                                    <span class="act-sub">{{ $log->created_at->format('h:i A') }}</span>
                                </td>

                                <td>
                                    <div class="act-user">
                                        <span class="act-avatar">{{ strtoupper(substr($who, 0, 1)) }}</span>
                                        {{ $who }}
                                    </div>
                                </td>

                                <td>
                                    <span class="act-badge {{ $log->event }}">
                                        <i class="fas fa-circle"></i> {{ ucfirst($log->event) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="act-record">
                                        {{ class_basename($log->subject_type) }}
                                        <small>ID {{ $log->subject_id }}</small>
                                    </div>
                                </td>

                                <td class="act-changes">
                                    @foreach ($keys->take(8) as $k)
                                        <div class="change-row">
                                            <span class="change-key">{{ $k }}</span>
                                            <span class="change-old">{{ $show($old[$k] ?? null) }}</span>
                                            <i class="fas fa-arrow-right"></i>
                                            <span class="change-new">{{ $show($new[$k] ?? null) }}</span>
                                        </div>
                                    @endforeach

                                    @if ($keys->count() > 8)
                                        <div class="act-sub">+ {{ $keys->count() - 8 }} more fields</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="activity-empty">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-clock-rotate-left"></i>
                                        </div>
                                        <h4>No activity found</h4>
                                        <p>Try changing the filters, or make a change in the system and check again.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="activity-footer">
                <div>
                    Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }}
                    of {{ number_format($logs->total()) }}
                </div>
                <div>{{ $logs->links('pagination::bootstrap-5') }}</div>
            </div>

        </section>
    </div>
</div>

<style>
/* ==========================================================
   ACTIVITY LOG - SCHOOL DIARY DESIGN (no hero, no KPI cards)
========================================================== */

.activity-page {
    --ink: #263b35;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', sans-serif;
    font-size: 13px;
}

.activity-page *,
.activity-page *::before,
.activity-page *::after {
    box-sizing: border-box;
}

.activity-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.activity-panel {
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HEADER */

.activity-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.panel-title-icon,
.filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 16px;
}

.panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.activity-record-count {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.activity-record-count i,
.activity-record-count strong {
    color: var(--ink);
}

/* FILTERS */

.activity-filter-panel {
    margin: 0;
    padding: 23px 24px;
    border-bottom: 1px solid var(--line);
    background: #faf9f3;
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.filter-heading-icon {
    color: var(--sage);
    background: #edf2e9;
}

.filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 17px;
    font-weight: 700;
}

.filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.activity-filter-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr)) auto;
    align-items: end;
    gap: 14px;
}

.act-field {
    min-width: 0;
}

.act-field label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.act-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    padding: 0 13px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.act-input:hover {
    border-color: #bdcabb;
}

.act-input:focus {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.act-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.act-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: all .2s ease;
}

.act-btn-apply {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.act-btn-apply:hover {
    transform: translateY(-1px);
    border-color: #426652;
    color: #fff;
    background: #426652;
    box-shadow: 0 4px 10px rgba(38, 59, 53, .12);
}

.act-btn-reset {
    border-color: #deded2;
    color: #5d6b60;
    background: #fffefa;
}

.act-btn-reset:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* TABLE */

.activity-table-wrap {
    overflow-x: auto;
}

.activity-table {
    width: 100%;
    min-width: 860px;
    border-collapse: collapse;
}

.activity-table th {
    padding: 13px 18px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .65px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.activity-table td {
    padding: 15px 18px;
    border-bottom: 1px dashed #e4dfd0;
    font-size: 12px;
    vertical-align: top;
}

.activity-table tbody tr:hover {
    background: #faf9f3;
}

.activity-table tbody tr:last-child td {
    border-bottom: 0;
}

.act-sub {
    display: block;
    margin-top: 2px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 600;
}

.act-user {
    display: flex;
    align-items: center;
    gap: 9px;
    color: var(--ink);
    font-weight: 700;
}

.act-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    color: #ead7aa;
    background: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.act-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.act-badge i {
    font-size: 6px;
}

.act-badge.created {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.act-badge.updated {
    border-color: #d9e2e9;
    color: var(--blue);
    background: #edf4f8;
}

.act-badge.deleted {
    border-color: #eed6cf;
    color: var(--red);
    background: #fff1ed;
}

.act-record {
    color: var(--ink);
    font-weight: 800;
}

.act-record small {
    display: block;
    margin-top: 2px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 600;
}

.act-changes {
    max-width: 440px;
}

.change-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 6px;
    font-size: 11px;
}

.change-row:last-child {
    margin-bottom: 0;
}

.change-key {
    color: var(--ink);
    font-weight: 800;
}

.change-old,
.change-new {
    padding: 2px 8px;
    border-radius: 5px;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.change-old {
    color: #9b5e53;
    background: #fff1ed;
}

.change-new {
    color: #347849;
    background: #edf6e9;
}

.change-row > i {
    color: #9aa396;
    font-size: 9px;
}

/* EMPTY */

.activity-empty {
    padding: 50px 20px;
    text-align: center;
}

.empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    margin: 0 auto 17px;
    border: 1px solid #e3decc;
    border-radius: 18px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 25px;
}

.activity-empty h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 20px;
    font-weight: 700;
}

.activity-empty p {
    max-width: 420px;
    margin: 0 auto;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* FOOTER + PAGINATION */

.activity-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 11px;
}

.activity-page .pagination {
    margin: 0;
    gap: 4px;
}

.activity-page .page-link {
    border: 1px solid #dedfd3;
    border-radius: 8px !important;
    color: var(--ink);
    background: #fffefa;
    font-size: 11px;
    font-weight: 700;
}

.activity-page .page-link:hover {
    background: #f0f3e9;
}

.activity-page .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.activity-page .page-item.disabled .page-link {
    color: #a3a89c;
    background: #f5f4ec;
}

/* RESPONSIVE */

@media (max-width: 1200px) {
    .activity-filter-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .act-actions {
        grid-column: 1 / -1;
    }
}

@media (max-width: 650px) {
    .activity-page .container-fluid {
        padding: 18px 14px !important;
    }

    .activity-panel-header {
        align-items: flex-start;
        padding: 16px;
    }

    .panel-title-icon,
    .filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .panel-title-wrap h3 {
        font-size: 16px;
    }

    .activity-filter-panel {
        padding: 17px 15px;
    }

    .activity-filter-grid {
        grid-template-columns: 1fr;
    }

    .act-actions > * {
        flex: 1;
    }

    .activity-footer {
        flex-direction: column;
        align-items: flex-start;
        padding: 14px 15px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .activity-page *,
    .activity-page *::before,
    .activity-page *::after {
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection