@extends('super-admin.layouts.app')

@section('title', 'School Reports')
@section('breadcrumb', 'Reports / Schools')
@section('page-title', 'School Reports')

@section('content')
@php
    $totalSchools = (int) ($totalSchools ?? 0);
    $activeSchools = (int) ($activeSchools ?? 0);
    $inactiveSchools = (int) ($inactiveSchools ?? 0);
    $totalUsers = (int) ($totalUsers ?? 0);

    $totalSubscriptions = (int) ($totalSubscriptions ?? 0);
    $activeSubscriptions = (int) ($activeSubscriptions ?? 0);
    $trialSubscriptions = (int) ($trialSubscriptions ?? 0);
    $paidSubscriptions = (int) ($paidSubscriptions ?? 0);

    $activePct = $totalSchools > 0
        ? round(($activeSchools / $totalSchools) * 100)
        : 0;

    $schoolTypes = $schoolTypes ?? collect();
    $educationBoards = $educationBoards ?? collect();
@endphp

<div class="welcome reports-welcome">
    <div>
        <div class="eyebrow">Reports &amp; analytics</div>
        <h2>School Reports</h2>
        <p>Monitor registered schools, user accounts and subscription activity across your platform.</p>
    </div>
</div>

{{-- Consolidated platform overview --}}
<div class="section-heading reports-section-heading">
    <div>
        <h3>Platform overview</h3>
        <p>Key figures for schools, users and subscriptions</p>
    </div>
</div>

<div class="kpis reports-kpis reports-kpis-compact">
    <div class="kpi blue">
        <div class="kpi-top">
            <span class="kpi-label">Total schools</span>
            <span class="kpi-icon" aria-hidden="true">
                <svg class="i" viewBox="0 0 24 24"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($totalSchools) }}</div>
        <div class="kpi-foot">
            <span class="metric-positive">{{ number_format($activeSchools) }} active</span>
            <span class="metric-separator">·</span>
            <span class="metric-negative">{{ number_format($inactiveSchools) }} inactive</span>
        </div>
    </div>

    <div class="kpi amber">
        <div class="kpi-top">
            <span class="kpi-label">School users</span>
            <span class="kpi-icon" aria-hidden="true">
                <svg class="i" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.5 3-6 6.5-6s6.5 2.5 6.5 6M17 11a3 3 0 1 0 0-6M18 14c2.1.5 3.5 2.2 3.5 4.5"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($totalUsers) }}</div>
        <div class="kpi-foot">Accounts linked to schools</div>
    </div>

    <div class="kpi green">
        <div class="kpi-top">
            <span class="kpi-label">Total subscriptions</span>
            <span class="kpi-icon" aria-hidden="true">
                <svg class="i" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($totalSubscriptions) }}</div>
        <div class="kpi-foot">{{ number_format($activeSubscriptions) }} currently active</div>
    </div>

    <div class="kpi red">
        <div class="kpi-top">
            <span class="kpi-label">Subscription plans</span>
            <span class="kpi-icon" aria-hidden="true">
                <svg class="i" viewBox="0 0 24 24"><path d="M4 19V5M4 19h17M8 15l4-4 3 2 5-6"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ number_format($trialSubscriptions + $paidSubscriptions) }}</div>
        <div class="kpi-foot">
            <span class="metric-trial">{{ number_format($trialSubscriptions) }} trial</span>
            <span class="metric-separator">·</span>
            <span class="metric-paid">{{ number_format($paidSubscriptions) }} paid</span>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="section-heading reports-section-heading">
    <div>
        <h3>Platform analytics</h3>
        <p>Compare school status and subscription categories</p>
    </div>
</div>

<div class="reports-charts">
    <section class="panel reports-chart-panel">
        <div class="panel-head">
            <div>
                <h3>School status comparison</h3>
                <p>Number of active and inactive schools</p>
            </div>
        </div>
        <div class="chart-box">
            <canvas id="schoolStatusChart" role="img" aria-label="Bar chart comparing active and inactive schools"></canvas>
            <p class="chart-empty" id="schoolStatusEmpty" hidden>No school data is available to display.</p>
        </div>
    </section>

    <section class="panel reports-chart-panel">
        <div class="panel-head">
            <div>
                <h3>Subscription comparison</h3>
                <p>Number of trial and paid subscriptions</p>
            </div>
        </div>
        <div class="chart-box">
            <canvas id="subscriptionMixChart" role="img" aria-label="Bar chart comparing trial and paid subscriptions"></canvas>
            <p class="chart-empty" id="subscriptionMixEmpty" hidden>No subscription data is available to display.</p>
        </div>
    </section>
</div>

{{-- School directory --}}
<div class="section-heading reports-section-heading reports-directory-heading">
    <div>
        <h3>Registered schools</h3>
        <p>Search, filter and review school records</p>
    </div>
    <span class="count-chip">{{ number_format($schools->total()) }} records</span>
</div>

<div class="report-export-actions">
    <button type="button" class="report-btn report-btn-print"
            onclick="window.open('{{ route('super-admin.reports.schools.print', request()->query()) }}', '_blank')">
        <i class="fas fa-print"></i>
        Print
    </button>

    <a href="{{ route('super-admin.reports.schools.pdf', request()->query()) }}"
       class="report-btn report-btn-pdf">
        <i class="fas fa-file-pdf"></i>
        Download PDF
    </a>

    <a href="{{ route('super-admin.reports.schools.excel', request()->query()) }}"
       class="report-btn report-btn-excel">
        <i class="fas fa-file-excel"></i>
        Export Excel
    </a>
</div>

<section class="panel reports-directory">
    <div class="toolbar reports-toolbar">
        <form method="GET"
              action="{{ route('super-admin.reports.schools') }}"
              class="reports-filters">

            <label class="search reports-search">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-3.5-3.5"/>
                </svg>
                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search school, code or city..."
                       aria-label="Search schools">
            </label>

            <select name="status" class="filter-select" aria-label="Filter by status">
                <option value="">All statuses</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <select name="school_type" class="filter-select" aria-label="Filter by school type">
                <option value="">All types</option>
                @foreach($schoolTypes as $type)
                    <option value="{{ $type }}" @selected(request('school_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>

            <select name="education_board" class="filter-select" aria-label="Filter by education board">
                <option value="">All boards</option>
                @foreach($educationBoards as $board)
                    <option value="{{ $board }}" @selected(request('education_board') === $board)>{{ $board }}</option>
                @endforeach
            </select>

            <label class="date-filter">
                <span>From</span>
                <input type="date" name="from_date" value="{{ request('from_date') }}" aria-label="Registration from date">
            </label>

            <label class="date-filter">
                <span>To</span>
                <input type="date" name="to_date" value="{{ request('to_date') }}" aria-label="Registration to date">
            </label>

            <div class="reports-filter-actions">
                <button type="submit" class="welcome-action">Apply filters</button>
                <a href="{{ route('super-admin.reports.schools') }}" class="chip">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-wrap reports-table-wrap">
        <table class="reports-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>School</th>
                    <th>Code</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th class="text-right">Users</th>
                    <th class="text-right">Active</th>
                    <th class="text-right">Inactive</th>
                    <th>Status</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schools as $school)
                    @php
                        $status = strtolower((string) $school->status);
                        $badge = $status === 'active'
                            ? 'active'
                            : ($status === 'inactive' ? 'inactive' : 'other');
                    @endphp
                    <tr>
                        <td data-label="#"> {{ $schools->firstItem() + $loop->index }}</td>
                        <td data-label="School">
                            <div class="school-cell">
                                <div class="school-avatar">{{ strtoupper(substr($school->name ?? 'S', 0, 1)) }}</div>
                                <span class="school-name">{{ $school->name ?? 'Unnamed school' }}</span>
                            </div>
                        </td>
                        <td data-label="Code">
                            @if($school->school_code)
                                <span class="code-chip">{{ $school->school_code }}</span>
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                        <td data-label="Location">{{ $school->city ?? 'N/A' }}{{ $school->state ? ', '.$school->state : '' }}</td>
                        <td data-label="Type">{{ $school->school_type ?? 'N/A' }}</td>
                        <td data-label="Users" class="text-right">{{ number_format($school->total_users ?? 0) }}</td>
                        <td data-label="Active" class="text-right cell-positive">{{ number_format($school->active_users ?? 0) }}</td>
                        <td data-label="Inactive" class="text-right cell-negative">{{ number_format($school->inactive_users ?? 0) }}</td>
                        <td data-label="Status">
                            <span class="badge {{ $badge }}">{{ $status !== '' ? ucfirst($status) : 'Unknown' }}</span>
                        </td>
                        <td data-label="Registered" class="cell-date">{{ $school->created_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="10">
                            <div class="empty">
                                <div class="empty-icon" aria-hidden="true">⌕</div>
                                <h4>No school records found</h4>
                                <p>Try adjusting your search terms or filters.</p>
                                @if(request()->hasAny(['search', 'status', 'school_type', 'education_board', 'from_date', 'to_date']))
                                    <a href="{{ route('super-admin.reports.schools') }}" class="chip empty-reset">Clear all filters</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel-foot reports-pagination">
@if($schools->hasPages())
            <div class="pagination-wrap">
                {{ $schools->withQueryString()->links('pagination::simple-tailwind') }}
            </div>
        @endif
    </div>
</section>

<style>

    .report-export-actions {
    display: flex;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 10px;
    margin: 0 0 16px;
}

.report-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.report-btn-print {
    background: #eef2f7;
    color: #344054;
    border-color: #e0e5ec;
}

.report-btn-pdf {
    background: #fff0ef;
    color: #c7463d;
    border-color: #f5d5d2;
}

.report-btn-excel {
    background: #eaf7ef;
    color: #23804d;
    border-color: #ccebd7;
}

.report-btn:hover {
    transform: translateY(-1px);
    filter: brightness(.97);
}

@media (max-width: 600px) {
    .report-export-actions {
        justify-content: stretch;
    }

    .report-btn {
        flex: 1 1 100%;
    }
}
    .reports-section-heading {
        margin: 26px 0 14px;
    }

    .reports-section-heading h3,
    .reports-chart-panel .panel-head h3 {
        margin: 0;
    }

    .reports-section-heading p,
    .reports-chart-panel .panel-head p {
        margin: 5px 0 0;
    }

    .reports-kpis {
        margin-bottom: 18px;
    }

    .reports-kpis-compact {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .reports-kpis-compact .kpi-foot {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .metric-positive { color: #23845b; font-weight: 700; }
    .metric-negative { color: #d05a55; font-weight: 700; }
    .metric-trial { color: #b7791f; font-weight: 700; }
    .metric-paid { color: #3975c6; font-weight: 700; }
    .metric-separator { color: #a0a7b0; }

    .reports-subscription-kpis {
        margin-bottom: 10px;
    }

    .reports-charts {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .reports-chart-panel {
        min-width: 0;
    }

    .chart-box {
        position: relative;
        height: 250px;
        padding: 16px 20px 22px;
    }

    .chart-box canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .chart-empty {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        margin: 0;
        padding: 20px;
        color: #7b8491;
        font-size: 13px;
        text-align: center;
    }

    .chart-empty[hidden] {
        display: none;
    }

    .reports-directory-heading {
        align-items: center;
    }

    .reports-directory {
        overflow: hidden;
    }

    .reports-toolbar {
        padding: 18px;
    }

    .reports-filters {
        display: flex;
        align-items: end;
        flex-wrap: wrap;
        gap: 11px;
        width: 100%;
    }

    .reports-search {
        flex: 1 1 230px;
        min-width: 190px;
    }

    .filter-select,
    .date-filter input {
        box-sizing: border-box;
        height: 41px;
        min-width: 125px;
        padding: 0 11px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 9px;
        background: #fff;
        color: var(--ink, #111827);
        font: inherit;
        font-size: 13px;
    }

    .date-filter {
        display: grid;
        gap: 4px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
    }

    .date-filter input {
        min-width: 145px;
    }

    .filter-select:focus,
    .date-filter input:focus,
    .reports-search input:focus {
        outline: none;
        border-color: #8ca99a;
        box-shadow: 0 0 0 3px rgba(71, 120, 94, .12);
    }

    .reports-filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }

    .reports-filter-actions .welcome-action {
        min-height: 40px;
        border: 0;
        cursor: pointer;
        white-space: nowrap;
    }

    .reports-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .reports-table th {
        padding: 13px 14px;
        background: #f8faf9;
        color: #6b7280;
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .045em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .reports-table td {
        padding: 14px;
        border-top: 1px solid var(--line, #edf0ee);
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .reports-table tbody tr:hover {
        background: #fbfdfc;
    }

    .reports-table .text-right {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .school-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 175px;
    }

    .school-avatar {
        display: grid;
        flex: 0 0 35px;
        width: 35px;
        height: 35px;
        place-items: center;
        border-radius: 10px;
        background: #edf3ff;
        color: #4778c7;
        font-size: 13px;
        font-weight: 800;
    }

    .school-name {
        color: #17212b;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .code-chip {
        display: inline-block;
        padding: 5px 8px;
        border: 1px solid #e7ebef;
        border-radius: 7px;
        background: #f8fafc;
        color: #526171;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .cell-positive {
        color: #277347 !important;
        font-weight: 650;
    }

    .cell-negative {
        color: #c34e3e !important;
        font-weight: 650;
    }

    .cell-date {
        color: #6b7280 !important;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .empty {
        padding: 42px 20px;
        text-align: center;
    }

    .empty-icon {
        display: grid;
        width: 42px;
        height: 42px;
        margin: 0 auto 12px;
        place-items: center;
        border-radius: 50%;
        background: #f1f5f3;
        color: #718078;
        font-size: 25px;
    }

    .empty h4 {
        margin: 0 0 6px;
        color: #17212b;
        font-size: 15px;
        font-weight: 750;
    }

    .empty p {
        margin: 0;
        color: #7b8491;
        font-size: 13px;
    }

    .empty-reset {
        display: inline-flex;
        margin-top: 14px;
        text-decoration: none;
    }

    .reports-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding: 16px 18px;
    }

    .results-meta {
        color: #77818d;
        font-size: 12px;
    }

    .results-meta strong {
        color: #273342;
    }

    .pagination-wrap nav,
    .pagination-wrap .flex {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .pagination-wrap a,
    .pagination-wrap span {
        display: inline-flex;
        min-width: 35px;
        height: 35px;
        align-items: center;
        justify-content: center;
        padding: 0 11px;
        border: 1px solid var(--line, #e6ebe7);
        border-radius: 8px;
        background: #fff;
        color: #52665b;
        font-size: 12px;
        font-weight: 650;
        text-decoration: none;
    }

    .pagination-wrap a:hover {
        border-color: #cfe0d5;
        background: #f3faf5;
        color: #2f7658;
    }

    .pagination-wrap span[aria-current="page"],
    .pagination-wrap .active span,
    .pagination-wrap span.current {
        border-color: #172b27;
        background: #172b27;
        color: #fff;
    }

    .pagination-wrap span.disabled,
    .pagination-wrap span[aria-disabled="true"] {
        background: #f8faf8;
        cursor: default;
        opacity: .45;
    }

    @media (max-width: 1100px) {
        .reports-filters__search,
        .reports-search {
            flex-basis: 100%;
        }

        .reports-filter-actions {
            margin-left: 0;
        }
    }

    @media (max-width: 1000px) {
        .reports-kpis-compact {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .reports-charts {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .reports-kpis-compact {
            grid-template-columns: 1fr;
        }

        .reports-toolbar {
            padding: 14px;
        }

        .reports-filters {
            align-items: stretch;
        }

        .reports-search,
        .filter-select,
        .date-filter,
        .date-filter input {
            width: 100%;
            min-width: 0;
        }

        .reports-filter-actions {
            width: 100%;
        }

        .reports-filter-actions .welcome-action {
            flex: 1;
        }

        .reports-table-wrap {
            overflow: visible;
        }

        .reports-table,
        .reports-table tbody {
            display: block;
            width: 100%;
        }

        .reports-table thead {
            display: none;
        }

        .reports-table tbody tr:not(.empty-row) {
            display: block;
            margin: 12px;
            padding: 8px 14px;
            border: 1px solid var(--line, #e8eeea);
            border-radius: 12px;
            background: #fff;
        }

        .reports-table tbody tr:not(.empty-row) td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 9px 0;
            border-top: 1px solid #f0f2f1;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .reports-table tbody tr:not(.empty-row) td:first-child {
            display: none;
        }

        .reports-table tbody tr:not(.empty-row) td:nth-child(2) {
            justify-content: flex-start;
            padding: 4px 0 12px;
            border-top: 0;
            text-align: left;
        }

        .reports-table tbody tr:not(.empty-row) td:nth-child(2)::before {
            display: none;
        }

        .reports-table tbody tr:not(.empty-row) td::before {
            content: attr(data-label);
            flex: 0 0 auto;
            color: #7b8491;
            font-size: 11px;
            font-weight: 750;
            text-align: left;
            text-transform: uppercase;
        }

        .reports-table .text-right {
            text-align: right;
        }

        .school-cell {
            min-width: 0;
        }

        .reports-pagination {
            align-items: flex-start;
            padding: 14px;
        }

        .pagination-wrap {
            max-width: 100%;
            overflow-x: auto;
        }
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        return;
    }

    function createBarChart(canvasId, emptyId, labels, values, colors) {
        const canvas = document.getElementById(canvasId);
        const emptyMessage = document.getElementById(emptyId);

        if (!canvas || !emptyMessage) {
            return;
        }

        const hasData = values.some(function (value) {
            return Number(value) > 0;
        });

        if (!hasData) {
            canvas.hidden = true;
            emptyMessage.hidden = false;
            return;
        }

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 34,
                    maxBarThickness: 42
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' ' + Number(context.raw || 0).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#7b8491',
                            font: { size: 11 }
                        },
                        grid: { color: '#edf1ef', drawBorder: false }
                    },
                    y: {
                        ticks: {
                            color: '#344054',
                            font: { size: 12, weight: '600' }
                        },
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    }

    createBarChart(
        'schoolStatusChart',
        'schoolStatusEmpty',
        ['Active schools', 'Inactive schools'],
        [{{ $activeSchools }}, {{ $inactiveSchools }}],
        ['#16A36A', '#F06B65']
    );

    createBarChart(
        'subscriptionMixChart',
        'subscriptionMixEmpty',
        ['Trial subscriptions', 'Paid subscriptions'],
        [{{ $trialSubscriptions }}, {{ $paidSubscriptions }}],
        ['#F2B544', '#4F83E3']
    );
});
</script>
@endpush
@endsection