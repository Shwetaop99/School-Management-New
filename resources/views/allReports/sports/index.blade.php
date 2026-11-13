@extends('layouts.app')

@section('title', 'Sports Reports | Admin')
@section('page-title', 'Sports Reports')

@section('content')
@php
    use Illuminate\Contracts\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Carbon;

    $reportType = $reportType ?? 'overview';
    $isOverview = $reportType === 'overview';

    $totalGames = $totalGames ?? 0;
    $totalAchievements = $totalAchievements ?? 0;
    $totalEquipment = $totalEquipment ?? 0;
    $totalEquipmentUnits = $totalEquipmentUnits ?? 0;
    $availableEquipmentUnits = $availableEquipmentUnits ?? 0;

    $search = $search ?? request('search', '');
    $sportName = $sportName ?? request('sport_name', '');
    $fromDate = $fromDate ?? request('from_date', '');
    $toDate = $toDate ?? request('to_date', '');

    $items = match ($reportType) {
        'games' => $games ?? collect(),
        'achievements' => $achievements ?? collect(),
        'equipment' => $equipment ?? collect(),
        default => collect(),
    };

    $isPaginated = $items instanceof LengthAwarePaginator;
    $reportCount = $isPaginated ? $items->total() : $items->count();
    $startIndex = $isPaginated ? ($items->firstItem() ?? 1) : 1;

    if ($isPaginated) {
        $items->appends(request()->except('page'));
    }

    $titles = [
        'games' => 'Games & Events Report',
        'achievements' => 'Sports Achievements Report',
        'equipment' => 'Sports Equipment Report',
    ];
    $reportTitle = $titles[$reportType] ?? 'Sports Reports';

    $filterAction = match ($reportType) {
        'games' => route('admin.reports.sports.games'),
        'achievements' => route('admin.reports.sports.achievements'),
        'equipment' => route('admin.reports.sports.equipment'),
        default => route('admin.reports.sports'),
    };

    $filteredUnits = $filteredUnits ?? collect($items)->sum('quantity');
    $filteredAvailable = $filteredAvailable ?? collect($items)->sum('available_quantity');

    $dateLabel = ($fromDate || $toDate)
        ? ($fromDate ? Carbon::parse($fromDate)->format('d M Y') : 'Start')
            . ' to ' .
          ($toDate ? Carbon::parse($toDate)->format('d M Y') : 'Today')
        : 'All dates';

    // School identity for print (exact same as Payroll)
    $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::query()->first();
    $schoolName = trim((string) ($schoolSetting->school_name ?? ''));
    $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';
    $schoolLogo = $schoolSetting->logo_url ?? null;
    $schoolAddress = collect([
        $schoolSetting->address ?? null,
        $schoolSetting->city ?? null,
        $schoolSetting->district ?? null,
        $schoolSetting->state ?? null,
        $schoolSetting->pincode ?? null,
    ])->filter(fn ($value) => trim((string) $value) !== '')->implode(', ');
    $schoolPhone = trim((string) ($schoolSetting->phone ?? ''));
    $schoolEmail = trim((string) ($schoolSetting->email ?? ''));
@endphp

<div class="payroll-simple">
    <div class="container-fluid py-4">

        {{-- PRINT ONLY SCHOOL BRANDING (used on individual reports) --}}
        @if (!$isOverview)
            <div class="print-only">
                <div class="print-header">
                    <div class="print-brand">
                        @if (!empty($schoolLogo))
                            <img class="print-school-logo" src="{{ $schoolLogo }}" alt="School Logo">
                        @endif
                        <div class="print-school-identity">
                            <div class="print-school-name">{{ $schoolName }}</div>
                            @if ($schoolAddress !== '')
                                <div class="print-school-contact">{{ $schoolAddress }}</div>
                            @endif
                            @if ($schoolPhone !== '' || $schoolEmail !== '')
                                <div class="print-school-contact">
                                    @if ($schoolPhone !== '') Phone: {{ $schoolPhone }} @endif
                                    @if ($schoolPhone !== '' && $schoolEmail !== '') &nbsp; | &nbsp; @endif
                                    @if ($schoolEmail !== '') Email: {{ $schoolEmail }} @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="print-report-title">
                        <div class="print-brand-name">{{ $reportTitle }}</div>
                        <div class="print-brand-sub">Official Sports Statement</div>
                    </div>
                </div>
                <div class="print-meta">
                    <div class="print-meta-row">
                        <span class="print-label">Records:</span>
                        <span class="print-value">{{ number_format($reportCount) }}</span>
                    </div>
                    <div class="print-meta-row">
                        <span class="print-label">Period:</span>
                        <span class="print-value">{{ $dateLabel }}</span>
                    </div>
                    @if ($reportType !== 'equipment')
                        <div class="print-meta-row">
                            <span class="print-label">Sport:</span>
                            <span class="print-value">{{ $sportName ?: 'All sports' }}</span>
                        </div>
                    @endif
                    @if ($search)
                        <div class="print-meta-row">
                            <span class="print-label">Search:</span>
                            <span class="print-value">{{ $search }}</span>
                        </div>
                    @endif
                    <div class="print-meta-row">
                        <span class="print-label">Generated:</span>
                        <span class="print-value" id="sports-print-date">—</span>
                    </div>
                </div>
                <div class="print-divider"></div>
            </div>
        @endif

        {{-- TOP BAR --}}
        <div class="top-bar">
            @if ($isOverview)
                <a href="{{ route('admin.reports.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Reports
                </a>
            @else
                <a href="{{ route('admin.reports.sports') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Sports Reports
                </a>
            @endif
            <div></div>
        </div>

        {{-- ================= OVERVIEW ================= --}}
        @if ($isOverview)

            <section class="hero-section">
                <div class="hero-left">
                    <div class="hero-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="hero-text">
                        <div class="hero-breadcrumb"></div>
                        <h2>Sports Reports</h2>
                        <p>View, search and print your school's sports records.</p>
                    </div>
                </div>
                <div class="hero-right">
                    <div class="hero-handwritten">Play. Compete. Report.</div>
                    <p>Games, achievements and equipment in one clean place.</p>
                </div>
            </section>

            {{-- AVAILABLE REPORTS --}}
            <section class="report-section">
                <div class="section-heading">
                    <div>
                        <h3>Available Reports</h3>
                        <p>Select a report to view, search and print its records.</p>
                    </div>
                    <span class="report-count">3 Reports</span>
                </div>

                <div class="report-grid">

                    <a href="{{ route('admin.reports.sports.games') }}"
                       class="report-card accent-green"
                       aria-label="Open Games and Events Report">
                        <div class="report-card-top">
                            <span class="report-icon icon-green">
                                <i class="fas fa-futbol"></i>
                            </span>
                            <span class="report-tag tag-green">Events</span>
                        </div>

                        <div class="report-card-body">
                            <strong>Games &amp; Events</strong>
                            <span>Event dates, venues, organizers and statuses</span>
                        </div>

                        <div class="report-stat">
                            <strong>{{ number_format($totalGames) }}</strong>
                            <span>Total records</span>
                        </div>

                        <div class="report-chips">
                            <span>Dates</span>
                            <span>Venues</span>
                            <span>Organizers</span>
                        </div>

                        <div class="report-card-footer">
                            <span class="report-open">Open report</span>
                            <span class="report-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.sports.achievements') }}"
                       class="report-card accent-blue"
                       aria-label="Open Achievements Report">
                        <div class="report-card-top">
                            <span class="report-icon icon-blue">
                                <i class="fas fa-medal"></i>
                            </span>
                            <span class="report-tag tag-blue">Awards</span>
                        </div>

                        <div class="report-card-body">
                            <strong>Achievements</strong>
                            <span>Student awards, competitions and positions</span>
                        </div>

                        <div class="report-stat">
                            <strong>{{ number_format($totalAchievements) }}</strong>
                            <span>Total records</span>
                        </div>

                        <div class="report-chips">
                            <span>Students</span>
                            <span>Competitions</span>
                            <span>Positions</span>
                        </div>

                        <div class="report-card-footer">
                            <span class="report-open">Open report</span>
                            <span class="report-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.sports.equipment') }}"
                       class="report-card accent-gold"
                       aria-label="Open Sports Equipment Report">
                        <div class="report-card-top">
                            <span class="report-icon icon-gold">
                                <i class="fas fa-dumbbell"></i>
                            </span>
                            <span class="report-tag tag-gold">Inventory</span>
                        </div>

                        <div class="report-card-body">
                            <strong>Sports Equipment</strong>
                            <span>Stock quantities, condition, suppliers and locations</span>
                        </div>

                        <div class="report-stat">
                            <strong>{{ number_format($availableEquipmentUnits) }}</strong>
                            <span>Units available</span>
                        </div>

                        <div class="report-chips">
                            <span>Stock</span>
                            <span>Condition</span>
                            <span>Suppliers</span>
                        </div>

                        <div class="report-card-footer">
                            <span class="report-open">Open report</span>
                            <span class="report-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>

                </div>
            </section>

        {{-- ================= INDIVIDUAL REPORTS ================= --}}
        @else

            <section class="hero-section hero-simple">
                <div class="hero-left">
                    <div class="hero-icon">
                        <i class="fas {{ $reportType === 'games' ? 'fa-futbol' : ($reportType === 'achievements' ? 'fa-medal' : 'fa-dumbbell') }}"></i>
                    </div>
                    <div class="hero-text">
                        <h2>{{ $reportTitle }}</h2>
                        <p>Search and filter records before printing your report.</p>
                    </div>
                </div>
                <div class="hero-actions">
                    <span class="hero-count">
                        <strong>{{ number_format($reportCount) }}</strong> matching records
                    </span>
                    <div class="hero-buttons">
                        <button type="button" class="btn-primary" onclick="printSportsReport()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <a href="{{ route('admin.reports.sports') }}" class="btn-secondary">
                            All Reports
                        </a>
                    </div>
                </div>
            </section>

            {{-- FILTERS --}}
            <form method="GET" action="{{ $filterAction }}" class="filters">
                <div class="filter-item">
                    <label>Search</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search records...">
                </div>

                @if ($reportType !== 'equipment')
                    <div class="filter-item">
                        <label>Sport</label>
                        <select name="sport_name">
                            <option value="">All sports</option>
                            @foreach (($sports ?? collect()) as $sport)
                                <option value="{{ $sport }}" @selected($sportName === $sport)>
                                    {{ $sport }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="filter-item">
                    <label>{{ $reportType === 'equipment' ? 'Purchased From' : 'From Date' }}</label>
                    <input type="date" name="from_date" value="{{ $fromDate }}">
                </div>

                <div class="filter-item">
                    <label>{{ $reportType === 'equipment' ? 'Purchased To' : 'To Date' }}</label>
                    <input type="date" name="to_date" value="{{ $toDate }}">
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <a href="{{ $filterAction }}" class="btn-secondary">Reset</a>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="table-wrap">
                <table>
                    @if ($reportType === 'games')
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event / Game</th>
                                <th>Sport</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Organizer</th>
                                <th>Year</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $game)
                                <tr>
                                    <td>{{ $startIndex + $loop->index }}</td>
                                    <td>
                                        <div class="emp-name">{{ data_get($game, 'title') ?: '—' }}</div>
                                    </td>
                                    <td>{{ data_get($game, 'sport_name') ?: '—' }}</td>
                                    <td>{{ data_get($game, 'event_type') ?: '—' }}</td>
                                    <td>
                                        {{ data_get($game, 'event_date') ? Carbon::parse(data_get($game, 'event_date'))->format('d M Y') : '—' }}
                                    </td>
                                    <td>
                                        @if (data_get($game, 'start_time'))
                                            {{ data_get($game, 'start_time') }}
                                            @if (data_get($game, 'end_time'))
                                                – {{ data_get($game, 'end_time') }}
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ data_get($game, 'venue') ?: '—' }}</td>
                                    <td>{{ data_get($game, 'organizer') ?: '—' }}</td>
                                    <td>{{ data_get($game, 'academic_year') ?: '—' }}</td>
                                    <td>
                                        @php
                                            $st = strtolower(trim(data_get($game, 'status') ?? ''));
                                            $statusClass = in_array($st, ['completed', 'finished', 'done'], true) ? 'paid' : (in_array($st, ['cancelled', 'postponed'], true) ? 'unpaid' : '');
                                        @endphp
                                        <span class="status {{ $statusClass }}">
                                            {{ data_get($game, 'status') ?: '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="empty">No games or events found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    @elseif ($reportType === 'achievements')
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Achievement</th>
                                <th>Student</th>
                                <th>Sport</th>
                                <th>Competition</th>
                                <th>Position / Award</th>
                                <th>Date</th>
                                <th>Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $achievement)
                                <tr>
                                    <td>{{ $startIndex + $loop->index }}</td>
                                    <td>
                                        <div class="emp-name">{{ data_get($achievement, 'title') ?: '—' }}</div>
                                    </td>
                                    <td>{{ data_get($achievement, 'student_name') ?: '—' }}</td>
                                    <td>{{ data_get($achievement, 'sport_name') ?: '—' }}</td>
                                    <td>{{ data_get($achievement, 'competition_name') ?: '—' }}</td>
                                    <td>{{ data_get($achievement, 'position') ?: '—' }}</td>
                                    <td>
                                        {{ data_get($achievement, 'achievement_date') ? Carbon::parse(data_get($achievement, 'achievement_date'))->format('d M Y') : '—' }}
                                    </td>
                                    <td>{{ data_get($achievement, 'academic_year') ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty">No achievements found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    @else
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Equipment</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Total</th>
                                <th>Available</th>
                                <th>Condition</th>
                                <th>Status</th>
                                <th>Supplier</th>
                                <th>Location</th>
                                <th>Purchase Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $startIndex + $loop->index }}</td>
                                    <td>
                                        <div class="emp-name">{{ data_get($item, 'equipment_name') ?: '—' }}</div>
                                    </td>
                                    <td>{{ data_get($item, 'category') ?: '—' }}</td>
                                    <td>{{ data_get($item, 'brand') ?: '—' }}</td>
                                    <td>{{ data_get($item, 'model') ?: '—' }}</td>
                                    <td>{{ number_format((int) data_get($item, 'quantity', 0)) }}</td>
                                    <td class="net">{{ number_format((int) data_get($item, 'available_quantity', 0)) }}</td>
                                    <td>{{ data_get($item, 'condition') ?: '—' }}</td>
                                    <td>{{ data_get($item, 'status') ?: '—' }}</td>
                                    <td>{{ data_get($item, 'supplier') ?: '—' }}</td>
                                    <td>{{ data_get($item, 'location') ?: '—' }}</td>
                                    <td>
                                        {{ data_get($item, 'purchase_date') ? Carbon::parse(data_get($item, 'purchase_date'))->format('d M Y') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="empty">No equipment found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    @endif
                </table>
            </div>

            @if ($isPaginated && $reportCount > 0)
                <div class="pagination-bar">
                    <span>
                        Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} records
                    </span>
                    <div>{{ $items->links() }}</div>
                </div>
            @elseif ($reportCount > 0)
                <div class="pagination-bar">
                    <span>Showing {{ $reportCount }} records</span>
                </div>
            @endif

        @endif
    </div>
</div>

<style>
.payroll-simple {
    --bg: #f5f4f0;
    --text: #1e2a25;
    --muted: #5c6b64;
    --border: #d4cfc2;
    --primary: #2c3e36;
    --primary-hover: #3a4f45;
    --net: #d4cfc2;
    --blue: #d4cfc2;
    --gold: #d4cfc2;
    background: var(--bg);
    color: var(--text);
    min-height: calc(100vh - 70px);
    font-family: Inter, "Public Sans", Arial, sans-serif;
}
.payroll-simple .container-fluid {
    max-width: 1400px;
}
/* Top bar */
.payroll-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}
.payroll-simple .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #fff;
    color: var(--text);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
}
.payroll-simple .btn-back:hover {
    background: #f0eee8;
}
.payroll-simple .btn-primary,
.payroll-simple .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    text-decoration: none;
}
.payroll-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}
.payroll-simple .btn-primary:hover {
    background: var(--primary-hover);
    color: #fff;
}
.payroll-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}
.payroll-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}
/* ========== HERO SECTION (Cream / Pearl) ========== */
.payroll-simple .hero-section {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
}
.payroll-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 28px 28px 32px;
    background: #fdfbf7;
}
.payroll-simple .hero-icon {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    background: #f3ead8;
    border: 1px solid #e5d9c0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5c4a32;
    font-size: 22px;
    flex-shrink: 0;
}
.payroll-simple .hero-text .hero-breadcrumb {
    font-size: 11px;
    font-weight: 700;
    color: #9c7b4a;
    letter-spacing: 1px;
    margin-bottom: 6px;
}
.payroll-simple .hero-text h2 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}
.payroll-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 480px;
}
.payroll-simple .hero-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 28px 24px;
    text-align: center;
    background: linear-gradient(145deg, #f8f1e3 0%, #f0e6d2 50%, #e8dcc6 100%);
    border-left: 1px solid #e5d9c4;
}
.payroll-simple .hero-handwritten {
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 22px;
    color: #5c4a32;
    line-height: 1.3;
}
.payroll-simple .hero-right p {
    margin: 0;
    font-size: 13px;
    color: #6b5c45;
    max-width: 260px;
    line-height: 1.6;
}
.payroll-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 6px;
}
.payroll-simple .hero-right .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}
.payroll-simple .hero-right .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}
.payroll-simple .hero-right .btn-secondary {
    background: rgba(255, 252, 245, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}
.payroll-simple .hero-right .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}
/* Filters */
.payroll-simple .filters {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
    padding: 16px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 20px;
}
.payroll-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.payroll-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}
.payroll-simple .filter-item input,
.payroll-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}
.payroll-simple .filter-item input:focus,
.payroll-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}
.payroll-simple .filter-buttons {
    display: flex;
    gap: 8px;
}
/* Summary */
.payroll-simple .summary {
    display: flex;
    flex-wrap: wrap;
    gap: 28px;
    padding: 14px 16px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13px;
}
.payroll-simple .summary span {
    display: block;
    font-size: 11px;
    color: var(--muted);
    margin-bottom: 2px;
    text-transform: uppercase;
}
.payroll-simple .summary strong {
    font-size: 15px;
}
.payroll-simple .summary .net {
    color: var(--net);
}
/* Table */
.payroll-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}
.payroll-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 900px;
}
.payroll-simple th {
    background: #f0eee8;
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 12px 14px;
    text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.payroll-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}
.payroll-simple tbody tr:last-child td {
    border-bottom: none;
}
.payroll-simple tbody tr:hover td {
    background: #faf9f5;
}
.payroll-simple .emp-name {
    font-weight: 600;
}
.payroll-simple .emp-id {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}
.payroll-simple .net {
    color: var(--net);
    font-weight: 700;
}
.payroll-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.payroll-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}
.payroll-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}
.payroll-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}
/* Pagination */
.payroll-simple .pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
    font-size: 13px;
    color: var(--muted);
}

/* ========== INDIVIDUAL REPORT HEADER (simple, full pearl) ========== */
.payroll-simple .hero-section.hero-simple {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    padding: 24px 28px;
    background: #fbf8f1;
    border: 1px solid #e5d9c4;
    border-radius: 10px;
}
.payroll-simple .hero-simple .hero-left {
    padding: 0;
    background: transparent;
    min-width: 0;
}
.payroll-simple .hero-simple .hero-text h2 {
    margin: 0 0 6px;
}
.payroll-simple .hero-simple .hero-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}
.payroll-simple .hero-simple .hero-count {
    font-size: 12.5px;
    color: #6b5c45;
}
.payroll-simple .hero-simple .hero-count strong {
    color: var(--text);
    font-size: 14px;
}
.payroll-simple .hero-simple .hero-buttons {
    margin-top: 0;
    justify-content: flex-end;
}
.payroll-simple .hero-simple .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}
.payroll-simple .hero-simple .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}
.payroll-simple .hero-simple .btn-secondary {
    background: rgba(255, 255, 255, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}
.payroll-simple .hero-simple .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}
@media (max-width: 900px) {
    .payroll-simple .hero-section.hero-simple {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }
    .payroll-simple .hero-simple .hero-actions {
        align-items: flex-start;
    }
    .payroll-simple .hero-simple .hero-buttons {
        justify-content: flex-start;
    }
}
/* Table numbers stay readable (overview accent colours are lighter) */
.payroll-simple td.net {
    color: #1a6b3c;
}

/* ========== AVAILABLE REPORTS (overview) ========== */
.payroll-simple .report-section {
    margin-bottom: 20px;
}
.payroll-simple .section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 14px;
}
.payroll-simple .section-heading h3 {
    margin: 0 0 4px;
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
}
.payroll-simple .section-heading p {
    margin: 0;
    font-size: 12.5px;
    color: var(--muted);
    line-height: 1.5;
}
.payroll-simple .report-count {
    padding: 4px 10px;
    border: 1px solid #e5d9c0;
    border-radius: 20px;
    background: #f3ead8;
    color: #5c4a32;
    font-size: 12px;
    font-weight: 600;
}
.payroll-simple .report-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}
.payroll-simple .report-card {
    --accent: var(--primary);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-width: 0;
    padding: 24px 22px 20px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    color: var(--text);
    text-decoration: none;
    box-sizing: border-box;
    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}
.payroll-simple .report-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent);
}
.payroll-simple .report-card.accent-green { --accent: var(--net); }
.payroll-simple .report-card.accent-blue { --accent: var(--blue); }
.payroll-simple .report-card.accent-gold { --accent: var(--gold); }
.payroll-simple .report-card:hover {
    border-color: var(--accent);
    box-shadow: 0 10px 24px rgba(44, 62, 54, 0.10);
    transform: translateY(-2px);
    color: var(--text);
}
.payroll-simple .report-card:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}
.payroll-simple .report-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.payroll-simple .report-icon {
    width: 54px;
    height: 54px;
    min-width: 54px;
    border-radius: 12px;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.payroll-simple .report-icon.icon-green { background: #e4f3ea; border-color: #bfe0cb; color: var(--net); }
.payroll-simple .report-icon.icon-blue { background: #e6eef4; border-color: #c5d6e3; color: var(--blue); }
.payroll-simple .report-icon.icon-gold { background: #f3ead8; border-color: #e5d9c0; color: #5c4a32; }
.payroll-simple .report-tag {
    padding: 4px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.payroll-simple .report-tag.tag-green { background: #e4f3ea; color: #1a6b3c; }
.payroll-simple .report-tag.tag-blue { background: #e6eef4; color: #34556f; }
.payroll-simple .report-tag.tag-gold { background: #f3ead8; color: #5c4a32; }
.payroll-simple .report-card-body {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}
.payroll-simple .report-card-body strong {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
    color: var(--text);
}
.payroll-simple .report-card-body > span {
    font-size: 13px;
    line-height: 1.65;
    color: var(--muted);
}
.payroll-simple .report-stat {
    display: flex;
    align-items: baseline;
    gap: 8px;
    padding: 10px 12px;
    background: #fdfbf7;
    border: 1px solid #eee9dd;
    border-radius: 8px;
}
.payroll-simple .report-stat strong {
    font-size: 22px;
    font-weight: 700;
    color: var(--accent);
}
.payroll-simple .report-stat span {
    font-size: 11px;
    color: var(--muted);
    text-transform: uppercase;
}
.payroll-simple .report-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.payroll-simple .report-chips span {
    padding: 4px 10px;
    border: 1px solid #e5d9c0;
    border-radius: 20px;
    background: #fdfbf7;
    color: #5c4a32;
    font-size: 11.5px;
    font-weight: 600;
}
.payroll-simple .report-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px solid #eee9dd;
}
.payroll-simple .report-open {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent);
}
.payroll-simple .report-arrow {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0eee8;
    color: var(--accent);
    font-size: 13px;
    transition: background .2s ease, color .2s ease, transform .2s ease;
}
.payroll-simple .report-card:hover .report-arrow {
    background: var(--accent);
    color: #fff;
    transform: translateX(3px);
}
@media (prefers-reduced-motion: reduce) {
    .payroll-simple .report-card,
    .payroll-simple .report-arrow {
        transition: none;
    }
    .payroll-simple .report-card:hover,
    .payroll-simple .report-card:hover .report-arrow {
        transform: none;
    }
}

/* Print only (hidden on screen) */
.payroll-simple .print-only {
    display: none;
}
/* Responsive */
@media (max-width: 1100px) {
    .payroll-simple .report-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 900px) {
    .payroll-simple .hero-section {
        grid-template-columns: 1fr;
    }
    .payroll-simple .hero-right {
        border-left: none;
        border-top: 1px solid #e5d9c4;
    }
}
@media (max-width: 768px) {
    .payroll-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .payroll-simple .filter-item input,
    .payroll-simple .filter-item select {
        width: 100%;
        min-width: 0;
    }
    .payroll-simple .summary {
        gap: 16px;
    }
    .payroll-simple .hero-left {
        padding: 22px 20px;
    }
    .payroll-simple .hero-text h2 {
        font-size: 20px;
    }
    .payroll-simple .report-grid {
        grid-template-columns: 1fr;
    }
    .payroll-simple .report-card {
        padding: 20px 18px 16px;
    }
    .payroll-simple .report-card-body strong {
        font-size: 16px;
    }
}
/* ================= PRINT — Professional Layout ================= */
@media print {
    @page {
        size: A4 landscape;
        margin: 10mm 12mm 12mm 12mm;
    }
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    body * {
        visibility: hidden;
    }
    .payroll-simple,
    .payroll-simple * {
        visibility: visible;
    }
    .payroll-simple {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: #fff !important;
        color: #1a1a1a;
        font-family: "Inter", "Segoe UI", Arial, Helvetica, sans-serif;
        font-size: 10.5px;
        line-height: 1.35;
    }
    /* Hide non-print elements */
    .payroll-simple .top-bar,
    .payroll-simple .hero-section,
    .payroll-simple .filters,
    .payroll-simple .summary,
    .payroll-simple .pagination-bar {
        display: none !important;
    }
    /* ---- Print Header ---- */
    .payroll-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }
    .payroll-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }
    .payroll-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }
    .payroll-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }
    .payroll-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }
    .payroll-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }
    .payroll-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }
    .payroll-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }
    .payroll-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }
    .payroll-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }
    .payroll-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }
    .payroll-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }
    .payroll-simple .print-label {
        color: #666;
        font-weight: 500;
    }
    .payroll-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }
    .payroll-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }
    /* ---- Table Styling for Print ---- */
    .payroll-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }
    .payroll-simple table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }
    .payroll-simple th {
        background: #2c3e36 !important;
        color: #fff !important;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 7px 8px;
        border: 1px solid #1e2a25;
        text-align: left;
        white-space: nowrap;
    }
    .payroll-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }
    .payroll-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }
    .payroll-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }
    .payroll-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }
    .payroll-simple .emp-id {
        font-size: 8.5px;
        color: #555;
        margin-top: 1px;
    }
    .payroll-simple .net {
        color: #1a6b3c !important;
        font-weight: 700;
    }
    .payroll-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .payroll-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }
    .payroll-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }
    .payroll-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }
    .payroll-simple .table-wrap::after {
        content: "This is a system-generated sports report. For official use only.";
        display: block;
        margin-top: 10px;
        font-size: 8.5px;
        color: #777;
        text-align: center;
        border-top: 1px solid #ddd;
        padding-top: 6px;
    }
}
</style>

<script>
(function () {
    function setSportsPrintDate() {
        const el = document.getElementById('sports-print-date');
        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printSportsReport = function () {
        setSportsPrintDate();
        const logo = document.querySelector('.print-school-logo');
        if (logo && typeof logo.decode === 'function') {
            logo.decode().catch(function () {}).finally(function () {
                window.print();
            });
            return;
        }
        window.print();
    };
})();
</script>
@endsection