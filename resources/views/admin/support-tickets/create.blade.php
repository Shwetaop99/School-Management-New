@extends('layouts.app')

@section('title', 'Help & Support')
@section('page-title', 'Help & Support')

@section('content')
@php
    $categoryDetails = [
        'technical'          => ['Technical Issues', 'fa-wrench'],
        'login'              => ['Login & Security', 'fa-lock'],
        'billing'            => ['Fees & Payments', 'fa-wallet'],
        'student_management' => ['Student Management', 'fa-user-graduate'],
        'backup'             => ['Backup & Recovery', 'fa-database'],
        'general'            => ['General Queries', 'fa-circle-question'],
        'complaint'          => ['Complaints', 'fa-flag'],
        'other'              => ['Other', 'fa-ellipsis'],
    ];

    $accents = ['blue', 'gold', 'green', 'red', 'purple', 'teal'];
    $groups = collect($templates ?? []);
    $totalCategories = $groups->count();
    $totalIssues = $groups->sum(fn ($items) => $items->count());

    $priorityClass = fn ($priority) => match (strtolower((string) $priority)) {
        'urgent', 'critical', 'high' => 'priority-high',
        'medium', 'normal' => 'priority-medium',
        'low' => 'priority-low',
        default => 'priority-default',
    };
@endphp

<div class="support-page">
    <div class="support-heading">
        <div>
            <div class="support-eyebrow"><i class="fas fa-headset"></i> SCHOOL SUPPORT</div>
            <h1>Help &amp; Support</h1>
            <p>Find an answer to a common issue or send a message to the Super Admin team.</p>
        </div>

        <a href="{{ route('support-tickets.custom') }}" class="support-primary-btn">
            <i class="fas fa-plus"></i> Create new request
        </a>
    </div>

    @if (session('success'))
        <div class="support-alert support-alert-success" role="status">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="support-alert support-alert-error" role="alert">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <strong>Please check the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="support-summary">
        <div class="summary-icon"><i class="fas fa-life-ring"></i></div>
        <div class="summary-copy">
            <strong>We're here to help</strong>
            <span>Choose a predefined issue for a faster request, or create a custom request.</span>
        </div>
        <div class="summary-stats">
            <div><strong>{{ number_format($totalIssues) }}</strong><span>Common issues</span></div>
            <div><strong>{{ number_format($totalCategories) }}</strong><span>Categories</span></div>
        </div>
    </div>

    @if ($totalIssues > 0)
        <section class="support-tools" aria-label="Search and filter support issues">
            <label class="support-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search" id="supportSearch"
                       placeholder="Search issues, e.g. login, fees, backup"
                       autocomplete="off" aria-label="Search support issues">
            </label>

            <div class="support-filters" role="group" aria-label="Filter issues by category">
                <button type="button" class="filter-chip active" data-filter="all">
                    All <span>{{ $totalIssues }}</span>
                </button>
                @foreach ($groups as $category => $categoryTemplates)
                    @php
                        $categoryName = $categoryDetails[$category][0]
                            ?? ucwords(str_replace('_', ' ', $category));
                    @endphp
                    <button type="button" class="filter-chip" data-filter="{{ $category }}">
                        {{ $categoryName }} <span>{{ $categoryTemplates->count() }}</span>
                    </button>
                @endforeach
            </div>
        </section>

        <section class="support-grid" id="supportGrid">
            @foreach ($groups as $category => $categoryTemplates)
                @php
                    [$categoryName, $categoryIcon] = $categoryDetails[$category]
                        ?? [ucwords(str_replace('_', ' ', $category)), 'fa-headset'];
                    $accent = $accents[$loop->index % count($accents)];
                @endphp

                <article class="support-category accent-{{ $accent }}"
                         data-category="{{ $category }}">
                    <header class="category-header">
                        <div class="category-icon"><i class="fas {{ $categoryIcon }}"></i></div>
                        <div>
                            <h2>{{ $categoryName }}</h2>
                            <p>{{ $categoryTemplates->count() }}
                                {{ $categoryTemplates->count() === 1 ? 'common issue' : 'common issues' }}</p>
                        </div>
                    </header>

                    <div class="support-issues">
                        @foreach ($categoryTemplates as $template)
                            <article class="support-issue"
                                     data-search="{{ strtolower($template->title . ' ' . $template->description . ' ' . $categoryName) }}">
                                <div>
                                    <h3>{{ $template->title }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($template->description, 135) }}</p>
                                </div>

                                <footer class="issue-footer">
                                    <span class="priority {{ $priorityClass($template->priority) }}">
                                        {{ ucfirst((string) $template->priority) }} priority
                                    </span>

                                    <a href="{{ route('support-tickets.template', $template->id) }}"
                                       class="support-outline-btn">
                                        Select issue <i class="fas fa-arrow-right"></i>
                                    </a>
                                </footer>
                            </article>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </section>

        <div class="support-empty" id="supportNoResults" hidden>
            <i class="fas fa-magnifying-glass"></i>
            <h3>No matching issues found</h3>
            <p>Try another search term or create a custom support request.</p>
            <a href="{{ route('support-tickets.custom') }}" class="support-primary-btn">
                <i class="fas fa-plus"></i> Create custom request
            </a>
        </div>
    @else
        <div class="support-empty">
            <i class="fas fa-life-ring"></i>
            <h3>No predefined issues available</h3>
            <p>You can still create a custom request and describe the issue in your own words.</p>
            <a href="{{ route('support-tickets.custom') }}" class="support-primary-btn">
                <i class="fas fa-plus"></i> Create custom request
            </a>
        </div>
    @endif

    <div class="support-bottom-cta">
        <div class="cta-icon"><i class="fas fa-comments"></i></div>
        <div class="cta-copy">
            <h2>Need to contact the Super Admin?</h2>
            <p>Send a message or complaint. You can follow the conversation from My Requests.</p>
        </div>
        <a href="{{ route('support-tickets.custom') }}" class="support-primary-btn">
            Create support request <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

<style>
    .support-page {
        --support-ink: var(--ink, #1d3a33);
        --support-gold: #e9ad25;
        --support-muted: #718078;
        --support-line: #e7e0d0;
        color: var(--text, #2c3e3a);
        padding: 4px 0 28px;
    }
    .support-page * { box-sizing: border-box; }
    .support-page a { text-decoration: none; }

    .support-heading {
        display: flex; align-items: center; justify-content: space-between;
        gap: 18px; margin-bottom: 20px;
    }
    .support-eyebrow {
        color: var(--support-muted); font-size: 10px; font-weight: 800;
        letter-spacing: 1.4px; margin-bottom: 7px;
    }
    .support-eyebrow i { color: var(--support-gold); margin-right: 5px; }
    .support-heading h1 {
        color: var(--support-ink); font-family: var(--serif, Georgia, serif);
        font-size: 27px; font-weight: 700; margin: 0 0 5px;
    }
    .support-heading p { color: var(--support-muted); margin: 0; font-size: 13px; }

    .support-primary-btn, .support-outline-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        border-radius: 8px; padding: 10px 15px; font-size: 12.5px;
        font-weight: 700; transition: .18s ease; white-space: nowrap;
    }
    .support-primary-btn {
        color: #fff !important; background: var(--support-ink);
        border: 1px solid var(--support-ink);
    }
    .support-primary-btn:hover {
        color: #fff !important; background: #284d43; transform: translateY(-1px);
    }
    .support-outline-btn {
        color: var(--support-ink); background: #fff; border: 1px solid #d8d0bd;
        padding: 7px 10px; font-size: 11.5px;
    }
    .support-outline-btn:hover { color: var(--support-ink); border-color: var(--support-ink); background: #f8f6ef; }

    .support-alert {
        display: flex; align-items: flex-start; gap: 10px;
        border-radius: 9px; padding: 12px 15px; margin-bottom: 16px; font-size: 13px;
    }
    .support-alert-success { color: #286342; background: #edf7ef; border: 1px solid #cfe5d3; }
    .support-alert-error { color: #923f36; background: #fff1ee; border: 1px solid #f0d1ca; }
    .support-alert ul { margin: 5px 0 0; padding-left: 18px; }

    .support-summary {
        display: flex; align-items: center; gap: 15px; padding: 19px 22px;
        margin-bottom: 18px; border: 1px solid var(--support-line);
        border-left: 4px solid var(--support-gold); border-radius: 10px; background: #fff;
    }
    .summary-icon, .category-icon, .cta-icon {
        display: flex; align-items: center; justify-content: center; flex: 0 0 44px;
        width: 44px; height: 44px; border-radius: 11px; color: var(--support-ink);
        background: #f7edcf; font-size: 18px;
    }
    .summary-copy { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
    .summary-copy strong { color: var(--support-ink); font-family: var(--serif, Georgia, serif); font-size: 17px; }
    .summary-copy span { color: var(--support-muted); font-size: 12.5px; line-height: 1.5; }
    .summary-stats {
        display: flex; gap: 25px; margin-left: auto; padding-left: 22px;
        border-left: 1px solid var(--support-line);
    }
    .summary-stats div { display: flex; flex-direction: column; gap: 2px; }
    .summary-stats strong { color: var(--support-ink); font-family: var(--serif, Georgia, serif); font-size: 22px; }
    .summary-stats span { color: var(--support-muted); font-size: 11px; }

    .support-tools {
        display: flex; flex-direction: column; gap: 13px; padding: 15px;
        margin-bottom: 30px; border: 1px solid var(--support-line);
        border-radius: 10px; background: #fff;
    }
    .support-search { position: relative; display: block; max-width: 430px; width: 100%; }
    .support-search i {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        color: #849087; font-size: 13px;
    }
    .support-search input {
        width: 100%; height: 40px; padding: 0 13px 0 37px;
        border: 1px solid #dcd5c5; border-radius: 8px; background: #fff;
        color: #263b34; font: inherit; font-size: 12.5px; outline: none;
    }
    .support-search input:focus { border-color: var(--support-ink); box-shadow: 0 0 0 3px rgba(29,58,51,.08); }
    .support-filters { display: flex; flex-wrap: wrap; gap: 7px; }
    .filter-chip {
        display: inline-flex; align-items: center; gap: 7px; padding: 7px 11px;
        border: 1px solid var(--support-line); border-radius: 20px; background: #fff;
        color: #617068; font: inherit; font-size: 11.5px; cursor: pointer;
    }
    .filter-chip span { padding: 1px 6px; border-radius: 20px; background: #f1eee5; font-size: 10px; font-weight: 700; }
    .filter-chip.active { color: #fff; background: var(--support-ink); border-color: var(--support-ink); }
    .filter-chip.active span { color: #fff; background: rgba(255,255,255,.2); }

    .support-grid {
        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 28px 18px; align-items: start;
    }
    .support-category {
        --category-accent: #2b5d8a; min-width: 0; padding: 17px;
        border: 1px solid var(--support-line); border-top: 3px solid var(--category-accent);
        border-radius: 10px; background: #fff; box-shadow: 0 3px 12px rgba(29,58,51,.035);
    }
    .accent-blue { --category-accent: #2b5d8a; }
    .accent-gold { --category-accent: #d58a0b; }
    .accent-green { --category-accent: #369568; }
    .accent-red { --category-accent: #df6548; }
    .accent-purple { --category-accent: #8056b5; }
    .accent-teal { --category-accent: #368b91; }
    .category-header {
        display: flex; align-items: center; gap: 12px; padding-bottom: 13px;
        margin-bottom: 12px; border-bottom: 1px dashed var(--support-line);
    }
    .category-icon { color: var(--category-accent); background: color-mix(in srgb, var(--category-accent) 13%, white); }
    .category-header h2 { color: var(--support-ink); font-family: var(--serif, Georgia, serif); font-size: 15px; margin: 0 0 3px; }
    .category-header p { color: var(--support-muted); font-size: 11.5px; margin: 0; }
    .support-issues { display: grid; gap: 9px; }
    .support-issue {
        padding: 12px; border: 1px solid #ebe5d7; border-radius: 8px;
        background: #fffdf8; transition: border-color .15s, background .15s;
    }
    .support-issue:hover { border-color: var(--category-accent); background: #fff; }
    .support-issue h3 { color: var(--support-ink); font-size: 12.5px; font-weight: 700; margin: 0 0 5px; }
    .support-issue p { color: var(--support-muted); font-size: 11.8px; line-height: 1.55; margin: 0 0 11px; }
    .issue-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .priority {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 8px;
        border: 1px solid transparent; border-radius: 20px; font-size: 10px;
        font-weight: 700; white-space: nowrap;
    }
    .priority::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .priority-high { color: #a84840; background: #fff2ee; border-color: #f0d5ce; }
    .priority-medium { color: #8a682b; background: #fbf3df; border-color: #eee0b9; }
    .priority-low { color: #2f6b45; background: #edf7ed; border-color: #cfe5d3; }
    .priority-default { color: #69776f; background: #f4f1e8; border-color: var(--support-line); }

    .support-empty {
        padding: 42px 20px; border: 1px solid var(--support-line);
        border-radius: 10px; background: #fff; text-align: center;
    }
    .support-empty > i { color: #c5ad6b; font-size: 28px; margin-bottom: 12px; }
    .support-empty h3 { color: var(--support-ink); font-family: var(--serif, Georgia, serif); font-size: 18px; margin: 0 0 6px; }
    .support-empty p { color: var(--support-muted); font-size: 12.5px; margin: 0 auto 16px; }

    .support-bottom-cta {
        display: flex; align-items: center; gap: 14px; padding: 18px 20px;
        margin-top: 28px; border: 1px dashed #d8d0bd; border-radius: 10px; background: #fff;
    }
    .cta-icon { background: #f7edcf; }
    .cta-copy { min-width: 0; }
    .cta-copy h2 { color: var(--support-ink); font-family: var(--serif, Georgia, serif); font-size: 15px; margin: 0 0 4px; }
    .cta-copy p { color: var(--support-muted); font-size: 11.8px; margin: 0; line-height: 1.5; }
    .support-bottom-cta .support-primary-btn { margin-left: auto; }
    .support-page [hidden] { display: none !important; }

    @media (max-width: 1150px) { .support-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 760px) {
        .support-heading { align-items: flex-start; flex-direction: column; }
        .support-summary { align-items: flex-start; flex-wrap: wrap; }
        .summary-stats { width: 100%; margin: 8px 0 0; padding: 12px 0 0; border-left: 0; border-top: 1px solid var(--support-line); }
        .support-grid { grid-template-columns: 1fr; gap: 15px; }
        .support-bottom-cta { align-items: flex-start; flex-wrap: wrap; }
        .support-bottom-cta .support-primary-btn { width: 100%; margin-left: 0; }
    }
</style>

@if ($totalIssues > 0)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('.support-page');
    if (!root) return;

    const searchInput = root.querySelector('#supportSearch');
    const filterButtons = Array.from(root.querySelectorAll('.filter-chip'));
    const cards = Array.from(root.querySelectorAll('.support-category'));
    const noResults = root.querySelector('#supportNoResults');
    let activeFilter = 'all';

    function applyFilters() {
        const term = (searchInput?.value || '').trim().toLowerCase();
        let visibleCount = 0;

        cards.forEach(card => {
            const categoryMatches = activeFilter === 'all' || card.dataset.category === activeFilter;
            let visibleIssues = 0;

            card.querySelectorAll('.support-issue').forEach(issue => {
                const matches = categoryMatches && (!term || (issue.dataset.search || '').includes(term));
                issue.hidden = !matches;
                if (matches) visibleIssues++;
            });

            card.hidden = visibleIssues === 0;
            if (visibleIssues > 0) visibleCount++;
        });

        if (noResults) noResults.hidden = visibleCount !== 0;
    }

    searchInput?.addEventListener('input', applyFilters);
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            activeFilter = this.dataset.filter || 'all';
            filterButtons.forEach(item => item.classList.toggle('active', item === this));
            applyFilters();
        });
    });
});
</script>
@endif

@endsection
