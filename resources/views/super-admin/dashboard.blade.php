@extends('super-admin.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('breadcrumb', 'Overview')
@section('page-title', 'Dashboard overview')

@php
    // Super Admin details needed by this dashboard section.
    $me = auth('super_admin')->user();
    $meName = $me?->name ?? 'Super Admin';

    // Dashboard icons used in the dashboard content.
    $icons = [
        'arrow' => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'school' => '<path d="M3 10l9-6 9 6-9 6-9-6z"/><path d="M7 12.7V17l5 3 5-3v-4.3"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
        'pause' => '<path d="M8 5h3v14H8zM15 5h3v14h-3z"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'search' => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>',
    ];

    // Keep the existing dashboard statistics below this line.
    $totalSchools = \App\Models\School::count();
    $activeSchools = \App\Models\School::whereRaw('LOWER(status) = ?', ['active'])->count();
    $inactiveSchools = max($totalSchools - $activeSchools, 0);
    $adminCount = \App\Models\User::whereNotNull('school_id')->count();
    $newThisMonth = \App\Models\School::where('created_at', '>=', now()->startOfMonth())->count();
    $recentSchools = \App\Models\School::latest()->take(10)->get();
    $activePct = $totalSchools > 0 ? round(($activeSchools / $totalSchools) * 100) : 0;
    $inactivePct = $totalSchools > 0 ? 100 - $activePct : 0;

    $hour = now()->hour;
    $greeting = $hour >= 5 && $hour < 12 ? 'Good morning'
        : ($hour >= 12 && $hour < 17 ? 'Good afternoon'
        : ($hour >= 17 && $hour < 21 ? 'Good evening' : 'Good night'));
@endphp

@section('content')
{{-- Welcome --}}

            <section class="welcome">

                <div>

                    <div class="eyebrow">Your control centre</div>

                    <h2>{{ $greeting }}, {{ $meName }}.</h2>

                    <p>Monitor your school network, review registration status and keep platform operations organised from one central workspace.</p>

                </div>



                <a href="#schools" class="welcome-action">

                    View registered schools

                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['arrow'] !!}</svg>

                </a>

            </section>



            {{-- Snapshot --}}

            <section class="status-banner" aria-label="Platform status">

                <div class="ring" style="--p: {{ $activePct }}" role="img" aria-label="{{ $activePct }}% of schools are active">

                    <div class="ring-text"><strong>{{ $activePct }}%</strong><span>active</span></div>

                </div>



                <div>

                    <div class="banner-kicker">Platform snapshot</div>

                    <div class="banner-title">Your school network at a glance</div>

                    <div class="banner-copy">

                        {{ number_format($totalSchools) }} {{ $totalSchools === 1 ? 'school is' : 'schools are' }} registered,

                        with {{ number_format($newThisMonth) }} added this month.

                    </div>

                </div>



                <div class="banner-stats">

                    <div class="bar-row">

                        <div class="bar-meta"><span>Active</span><strong>{{ number_format($activeSchools) }}</strong></div>

                        <div class="bar-track"><div class="bar-fill green" style="width: {{ $activePct }}%"></div></div>

                    </div>

                    <div class="bar-row">

                        <div class="bar-meta"><span>Inactive</span><strong>{{ number_format($inactiveSchools) }}</strong></div>

                        <div class="bar-track"><div class="bar-fill amber" style="width: {{ $inactivePct }}%"></div></div>

                    </div>

                </div>

            </section>



            {{-- KPIs --}}

            <div class="section-heading">

                <div><h3>Key metrics</h3><p>Current totals from your school management database</p></div>

                <span class="section-note">Updated {{ now()->format('h:i A') }}</span>

            </div>



            <section class="kpis" aria-label="Key metrics">

                <article class="kpi blue">

                    <div class="kpi-top">

                        <span class="kpi-label">Registered schools</span>

                        <span class="kpi-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['school'] !!}</svg></span>

                    </div>

                    <div class="kpi-value">{{ number_format($totalSchools) }}</div>

                    <div class="kpi-foot"><span class="dot" style="color:var(--blue)"></span> {{ number_format($newThisMonth) }} added this month</div>

                </article>



                <article class="kpi green">

                    <div class="kpi-top">

                        <span class="kpi-label">Active schools</span>

                        <span class="kpi-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['check'] !!}</svg></span>

                    </div>

                    <div class="kpi-value">{{ number_format($activeSchools) }}</div>

                    <div class="kpi-foot"><span class="dot" style="color:var(--green)"></span> {{ $activePct }}% of all schools</div>

                </article>



                <article class="kpi amber">

                    <div class="kpi-top">

                        <span class="kpi-label">Inactive schools</span>

                        <span class="kpi-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['pause'] !!}</svg></span>

                    </div>

                    <div class="kpi-value">{{ number_format($inactiveSchools) }}</div>

                    <div class="kpi-foot"><span class="dot" style="color:var(--amber)"></span> {{ $inactivePct }}% of all schools</div>

                </article>



                <article class="kpi red">

                    <div class="kpi-top">

                        <span class="kpi-label">School-linked users</span>

                        <span class="kpi-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['users'] !!}</svg></span>

                    </div>

                    <div class="kpi-value">{{ number_format($adminCount) }}</div>

                    <div class="kpi-foot"><span class="dot" style="color:var(--red)"></span> Users linked to a school</div>

                </article>

            </section>



            {{-- Directory --}}

            <div class="section-heading">

                <div><h3>School directory</h3><p>Recently registered schools and their current status</p></div>

                <span class="section-note">Latest {{ $recentSchools->count() }} records</span>

            </div>



            <section class="panel" id="schools">

                <div class="panel-head">

                    <div>

                        <h3>Registered schools</h3>

                        <p>Review school identifiers, status and registration date.</p>

                    </div>

                    <span class="count-chip">{{ number_format($totalSchools) }} {{ $totalSchools === 1 ? 'school' : 'schools' }} total</span>

                </div>



                @if ($recentSchools->count())

                    <div class="toolbar">

                        <label class="search">

                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['search'] !!}</svg>

                            <input type="search" id="schoolSearch" placeholder="Search by school name or code" autocomplete="off" aria-label="Search schools">

                        </label>



                        <div class="chips" role="group" aria-label="Filter by status">

                            <button type="button" class="chip is-on" data-filter="all">All</button>

                            <button type="button" class="chip" data-filter="active">Active</button>

                            <button type="button" class="chip" data-filter="inactive">Inactive</button>

                        </div>

                    </div>

                @endif



                <div class="table-wrap">

                    <table id="schoolTable">

                        <thead>

                            <tr><th>School</th><th>School code</th><th>Status</th><th>Registered</th></tr>

                        </thead>

                        <tbody>

                            @forelse ($recentSchools as $school)

                                @php

                                    $status = strtolower((string) $school->status);

                                    $badge  = $status === 'active' ? 'active' : ($status === 'inactive' ? 'inactive' : 'other');

                                @endphp



                                <tr data-status="{{ $badge }}"

                                    data-search="{{ strtolower(($school->name ?? '') . ' ' . ($school->school_code ?? '')) }}">

                                    <td class="cell-school" data-label="School">

                                        <div class="school-cell">

                                            <div class="school-avatar">{{ strtoupper(substr($school->name ?? 'S', 0, 1)) }}</div>

                                            <div>

                                                <div class="school-name">{{ $school->name ?? 'Unnamed school' }}</div>

                                                <div class="school-sub">School account</div>

                                            </div>

                                        </div>

                                    </td>



                                    <td data-label="School code">

                                        @if ($school->school_code)

                                            <span class="code-chip">{{ $school->school_code }}</span>

                                        @else

                                            <span class="muted">Not assigned</span>

                                        @endif

                                    </td>



                                    <td data-label="Status">

                                        <span class="badge {{ $badge }}">{{ $status !== '' ? ucfirst($status) : 'Unknown' }}</span>

                                    </td>



                                    <td data-label="Registered" class="muted">{{ $school->created_at?->format('d M Y') ?? '—' }}</td>

                                </tr>

                            @empty

                                <tr class="empty-row">

                                    <td colspan="4">

                                        <div class="empty">

                                            <div class="empty-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['school'] !!}</svg></div>

                                            <h4>No schools registered yet</h4>

                                            <p>Registered schools will appear here once they are added to the platform.</p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse



                            @if ($recentSchools->count())

                                <tr class="empty-row" id="noMatch" hidden>

                                    <td colspan="4">

                                        <div class="empty">

                                            <div class="empty-icon"><svg class="i" viewBox="0 0 24 24" aria-hidden="true">{!! $icons['search'] !!}</svg></div>

                                            <h4>No matching schools</h4>

                                            <p>Try a different name, code or status filter.</p>

                                        </div>

                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>



                <div class="panel-foot">

                    <span>Showing <strong id="shownCount">{{ $recentSchools->count() }}</strong> of <strong>{{ number_format($totalSchools) }}</strong> schools</span>

                    <span>School Central &middot; Admin overview</span>

                </div>

            </section>
@endsection

@if ($recentSchools->count())
    @push('scripts')
    <script>
(function () {

    const search  = document.getElementById('schoolSearch');

    const chips   = Array.from(document.querySelectorAll('.chip'));

    const rows    = Array.from(document.querySelectorAll('#schoolTable tbody tr[data-status]'));

    const noMatch = document.getElementById('noMatch');

    const shown   = document.getElementById('shownCount');

    let filter    = 'all';



    function apply() {

        const term = search.value.trim().toLowerCase();

        let visible = 0;



        rows.forEach(function (row) {

            const okStatus = filter === 'all' || row.dataset.status === filter;

            const okText   = !term || row.dataset.search.indexOf(term) !== -1;

            const show     = okStatus && okText;



            row.hidden = !show;

            if (show) visible++;

        });



        noMatch.hidden = visible !== 0;

        shown.textContent = visible;

    }



    search.addEventListener('input', apply);



    chips.forEach(function (chip) {

        chip.addEventListener('click', function () {

            filter = chip.dataset.filter;

            chips.forEach(function (c) { c.classList.toggle('is-on', c === chip); });

            apply();

        });

    });

})();
    </script>
    @endpush
@endif
