@extends('layouts.app')

@section('title', 'Scholarship Rules | Admin')
@section('page-title', 'Scholarship Rules')

@section('content')
<div class="scholarship-rules-list">
    <style>
        .scholarship-rules-list {
            --sr-bg: #f7f6f0;
            --sr-paper: #fffefa;
            --sr-ink: #263b35;
            --sr-dark: #1d302b;
            --sr-text: #34423d;
            --sr-muted: #78827a;
            --sr-line: #e5e1d5;
            --sr-sage: #527f70;
            --sr-amber: #a87543;
            color: var(--sr-text);
            padding: 20px;
            border-radius: 18px;
            background: var(--sr-bg);
            font-family: Inter, "Public Sans", sans-serif;
        }

        .scholarship-rules-list * {
            box-sizing: border-box;
        }

        .scholarship-rules-list .sr-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            padding: 28px;
            margin-bottom: 22px;
            border: 1px solid var(--sr-line);
            border-radius: 18px;
            background: linear-gradient(110deg, #fdfbf7 0%, #f8f1e3 62%, #e8dcc6 100%);
        }

        .scholarship-rules-list .sr-eyebrow {
            color: var(--sr-amber);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .scholarship-rules-list .sr-hero h2 {
            color: var(--sr-ink);
            font-family: Georgia, serif;
            font-weight: 700;
            font-size: clamp(24px, 3vw, 34px);
            margin: 0 0 8px;
        }

        .scholarship-rules-list .sr-hero p {
            color: var(--sr-muted);
            margin: 0;
            max-width: 680px;
        }

        .scholarship-rules-list .sr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 16px;
            border-radius: 10px;
            border: 1px solid transparent;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .scholarship-rules-list .sr-btn-primary {
            color: #fffefa;
            background: #2c3e36;
        }

        .scholarship-rules-list .sr-btn-primary:hover {
            color: #fff;
            background: #3a4f45;
        }

        .scholarship-rules-list .sr-btn-edit {
            color: var(--sr-ink);
            background: #f4f0df;
            border-color: #e5ddc9;
        }

        .scholarship-rules-list .sr-btn-edit:hover {
            background: #ebe3ce;
        }

        .scholarship-rules-list .sr-btn-delete {
            color: #a13e35;
            background: #fff5f2;
            border-color: #efd5cf;
        }

        .scholarship-rules-list .sr-btn-delete:hover {
            background: #fbe7e1;
        }

        .scholarship-rules-list .sr-card {
            background: var(--sr-paper);
            border: 1px solid var(--sr-line);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .scholarship-rules-list .sr-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--sr-line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .scholarship-rules-list .sr-card-header h3 {
            color: var(--sr-ink);
            font-family: Georgia, serif;
            font-size: 20px;
            margin: 0;
        }

        .scholarship-rules-list .sr-card-body {
            padding: 22px;
        }

        .scholarship-rules-list .sr-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 12px 0 18px;
        }

        .scholarship-rules-list .sr-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid var(--sr-line);
            background: #f7f6f0;
            color: var(--sr-text);
            font-size: 12px;
            font-weight: 700;
        }

        .scholarship-rules-list .sr-status-active {
            color: #286341;
            background: #edf7ef;
            border-color: #cfe6d3;
        }

        .scholarship-rules-list .sr-status-inactive {
            color: #8a5a32;
            background: #fbf1e4;
            border-color: #ecd9bd;
        }

        .scholarship-rules-list .sr-table-wrap {
            overflow-x: auto;
        }

        .scholarship-rules-list .sr-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 480px;
        }

        .scholarship-rules-list .sr-table th {
            background: #f5f3e9;
            color: var(--sr-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .7px;
            font-weight: 800;
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid var(--sr-line);
        }

        .scholarship-rules-list .sr-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #eeebe2;
            color: var(--sr-text);
            font-size: 13px;
        }

        .scholarship-rules-list .sr-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .scholarship-rules-list .sr-percent {
            color: #1a6b3c;
            font-weight: 800;
            white-space: nowrap;
        }

        .scholarship-rules-list .sr-empty {
            text-align: center;
            padding: 45px 20px;
            color: var(--sr-muted);
        }

        .scholarship-rules-list .sr-empty i {
            display: block;
            font-size: 32px;
            color: #a9b3a8;
            margin-bottom: 12px;
        }

        .scholarship-rules-list .sr-description {
            color: var(--sr-muted);
            font-size: 13px;
            margin: 0;
            white-space: pre-line;
        }

        .scholarship-rules-list .sr-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .scholarship-rules-list .sr-alert {
            border-radius: 10px;
            padding: 13px 16px;
            margin-bottom: 18px;
            border: 1px solid var(--sr-line);
            background: var(--sr-paper);
        }

        .scholarship-rules-list .sr-alert-success {
            color: #286341;
            border-color: #cfe6d3;
            background: #edf7ef;
        }

        .scholarship-rules-list .sr-alert-error {
            color: #a13e35;
            border-color: #efd5cf;
            background: #fff5f2;
        }

        .scholarship-rules-list .sr-pagination {
            padding: 18px 22px;
            border-top: 1px solid var(--sr-line);
        }

        @media (max-width: 640px) {
            .scholarship-rules-list {
                padding: 10px;
            }

            .scholarship-rules-list .sr-hero,
            .scholarship-rules-list .sr-card-body {
                padding: 17px;
            }

            .scholarship-rules-list .sr-actions .sr-btn {
                flex: 1;
            }
        }
    </style>

    @if (session('success'))
        <div class="sr-alert sr-alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="sr-alert sr-alert-error">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="sr-hero">
        <div>
            <div class="sr-eyebrow">Scholarship Management</div>
            <h2>Concession Rules</h2>
            <p>Manage academic-year rules and their saved exam-percentage slabs from one place.</p>
        </div>

        <a href="{{ route('admin.scholarship.merit.concession.create') }}"
           class="sr-btn sr-btn-primary">
            <i class="fas fa-plus"></i> Create Scholarship Rule
        </a>
    </div>

    @forelse ($rules as $rule)
        <section class="sr-card">
            <div class="sr-card-header">
                <h3>
                    <i class="fas fa-graduation-cap me-2"></i>
                    Academic Year: {{ $rule->academic_year ?: 'Not specified' }}
                </h3>

                <span class="sr-chip {{ $rule->status === 'active' ? 'sr-status-active' : 'sr-status-inactive' }}">
                    <i class="fas {{ $rule->status === 'active' ? 'fa-check-circle' : 'fa-pause-circle' }}"></i>
                    {{ ucfirst($rule->status ?? 'inactive') }}
                </span>
            </div>

            <div class="sr-card-body">
                <div class="sr-meta">
                    <span class="sr-chip">
                        <i class="fas fa-layer-group"></i>
                        {{ $rule->slabs->count() }} slab(s)
                    </span>
                    <span class="sr-chip">
                        <i class="fas fa-calendar"></i>
                        Created {{ optional($rule->created_at)->format('d M Y') ?? '—' }}
                    </span>
                </div>

                @if ($rule->description)
                    <p class="sr-description mb-3">{{ $rule->description }}</p>
                @endif

                <div class="sr-table-wrap">
                    <table class="sr-table">
                        <thead>
                            <tr>
                                <th>Slab</th>
                                <th>Exam Percentage Range</th>
                                <th>Scholarship Concession</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rule->slabs as $slab)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ rtrim(rtrim(number_format((float) $slab->minimum_percentage, 2, '.', ''), '0'), '.') }}%
                                        –
                                        @if ($slab->maximum_percentage === null)
                                            Above
                                        @else
                                            {{ rtrim(rtrim(number_format((float) $slab->maximum_percentage, 2, '.', ''), '0'), '.') }}%
                                        @endif
                                    </td>
                                    <td class="sr-percent">
                                        {{ rtrim(rtrim(number_format((float) $slab->scholarship_percentage, 2, '.', ''), '0'), '.') }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No slabs have been saved for this rule yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="sr-actions mt-4">
                    <a href="{{ route('admin.scholarship.merit.concession.rules.edit', $rule) }}"
                       class="sr-btn sr-btn-edit">
                        <i class="fas fa-edit"></i> Edit Rule
                    </a>

                    <form action="{{ route('admin.scholarship.merit.concession.rules.destroy', $rule) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this scholarship rule and all its slabs? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="sr-btn sr-btn-delete">
                            <i class="fas fa-trash"></i> Delete Rule
                        </button>
                    </form>
                </div>
            </div>
        </section>
    @empty
        <div class="sr-card">
            <div class="sr-empty">
                <i class="fas fa-layer-group"></i>
                <h4>No Scholarship Rules Saved Yet</h4>
                <p>Create a rule to start managing configurable scholarship concession slabs.</p>
                <a href="{{ route('admin.scholarship.merit.concession.create') }}"
                   class="sr-btn sr-btn-primary mt-2">
                    <i class="fas fa-plus"></i> Create First Rule
                </a>
            </div>
        </div>
    @endforelse

    @if ($rules->hasPages())
        <div class="sr-card sr-pagination">
            {{ $rules->links() }}
        </div>
    @endif
</div>
@endsection