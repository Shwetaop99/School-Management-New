@extends('layouts.app')

@section('title', 'Help & Support')
@section('page-title', 'Help & Support')

@section('content')

@php
    $categoryDetails = [
        'technical'          => ['Technical Issues',   'fas fa-wrench'],
        'login'              => ['Login & Security',   'fas fa-lock'],
        'billing'            => ['Fees & Payments',    'fas fa-wallet'],
        'student_management' => ['Student Management', 'fas fa-user-graduate'],
        'backup'             => ['Backup & Recovery',  'fas fa-database'],
        'general'            => ['General Queries',    'fas fa-circle-question'],
        'complaint'          => ['Complaint',          'fas fa-flag'],
        'other'              => ['Other',              'fas fa-ellipsis'],
    ];

    $accents = ['c-blue', 'c-amber', 'c-green', 'c-red', 'c-violet', 'c-cyan'];

    $totalCategories = count($templates);
    $totalIssues     = collect($templates)->sum(fn ($group) => count($group));

    $priorityClass = function ($priority) {
        return match (strtolower((string) $priority)) {
            'urgent', 'critical', 'high' => 'p-high',
            'medium', 'normal'           => 'p-medium',
            'low'                        => 'p-low',
            default                      => 'p-none',
        };
    };
@endphp

<div class="help-page">

    {{-- Hero --}}
    <section class="paper hero">
        <div class="hero-left">
            <div class="crumb">
                <span>Support</span>
                <i class="fas fa-chevron-right"></i>
                <span>Help &amp; Support</span>
            </div>
            <h1>Help &amp; Support</h1>
            <p>Select a common issue below, or submit your own query if nothing matches.</p>

            <div class="hero-btns">
                <a href="{{ route('admin.support-tickets.custom') }}" class="btn-solid">
                    <i class="fas fa-plus"></i> Create new query
                </a>
            </div>
        </div>

        <div class="bell">
            <div class="bell-label">We're here to help</div>
            <div class="bell-sub">Pick the closest match for faster answers, or describe your problem in your own words.</div>
            <div class="bell-stats">
                <div class="bell-stat">
                    <strong>{{ number_format($totalIssues) }}</strong>
                    <span>Common issues</span>
                </div>
                <div class="bell-stat">
                    <strong>{{ number_format($totalCategories) }}</strong>
                    <span>Categories</span>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="note-box" role="status">
            <i class="fas fa-circle-check"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if ($totalCategories > 0)

        <section class="paper toolbar">
            <label class="search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search"
                       id="issueSearch"
                       placeholder="Search issues, e.g. login, fees, backup"
                       autocomplete="off"
                       aria-label="Search issues">
            </label>

            <div class="chips" role="group" aria-label="Filter by category">
                <button type="button" class="chip is-on" data-category="all">
                    All <em>{{ $totalIssues }}</em>
                </button>

                @foreach ($templates as $category => $categoryTemplates)
                    @php
                        $label = $categoryDetails[$category][0]
                            ?? ucwords(str_replace('_', ' ', $category));
                    @endphp
                    <button type="button" class="chip" data-category="{{ $category }}">
                        {{ $label }} <em>{{ count($categoryTemplates) }}</em>
                    </button>
                @endforeach
            </div>
        </section>

        <section class="grid" id="categoryGrid">
            @foreach ($templates as $category => $categoryTemplates)
                @php
                    [$categoryName, $categoryIcon] = $categoryDetails[$category]
                        ?? [ucwords(str_replace('_', ' ', $category)), 'fas fa-headset'];
                    $accent = $accents[$loop->index % count($accents)];
                @endphp

                <article class="paper card {{ $accent }}" data-category="{{ $category }}">
                    <span class="card-tab">{{ $categoryName }}</span>

                    <div class="card-head">
                        <div class="card-icon"><i class="{{ $categoryIcon }}"></i></div>
                        <div>
                            <h2>{{ $categoryName }}</h2>
                            <small>
                                {{ count($categoryTemplates) }}
                                {{ count($categoryTemplates) === 1 ? 'common issue' : 'common issues' }}
                            </small>
                        </div>
                    </div>

                    <div class="issues">
                        @foreach ($categoryTemplates as $template)
                            <div class="issue"
                                 data-search="{{ strtolower($template->title.' '.$template->description.' '.$categoryName) }}">
                                <h3>{{ $template->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($template->description, 100) }}</p>

                                <div class="issue-foot">
                                    <span class="priority {{ $priorityClass($template->priority) }}">
                                        {{ ucfirst((string) $template->priority) }} priority
                                    </span>

                                    <a href="{{ route('admin.support-tickets.template', $template->id) }}"
                                       class="btn-line btn-sm">
                                        Select issue <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </section>

        <section class="paper empty" id="noMatch" hidden>
            <div class="empty-icon"><i class="fas fa-magnifying-glass"></i></div>
            <h3>No matching issues</h3>
            <p>Try a different word, or describe your problem using the button below.</p>
        </section>

    @else
        <section class="paper empty">
            <div class="empty-icon"><i class="fas fa-life-ring"></i></div>
            <h3>No predefined issues yet</h3>
            <p>No support templates are available. You can still describe your problem yourself.</p>
        </section>
    @endif

    <section class="paper cta">
        <div class="cta-text">
            <div class="cta-icon"><i class="fas fa-comments"></i></div>
            <div>
                <h3>Can't find your issue?</h3>
                <p>Describe your problem or query manually.</p>
            </div>
        </div>

        <a href="{{ route('admin.support-tickets.custom') }}" class="btn-solid">
            <i class="fas fa-plus"></i> Create new
        </a>
    </section>

</div>

<style>
    .help-page {
        --h-ink: var(--ink, #1d3a33);
        --h-ink2: var(--ink-2, #16302a);
        --h-line: var(--line, #d8d1bf);
        --h-muted: var(--muted, #6b7c75);
        --h-text: var(--text, #2c3e3a);
        --h-chalk: var(--chalk, #f4efdd);
        --h-serif: var(--serif, Georgia, "Times New Roman", serif);
        --h-hand: var(--hand, "Caveat", "Segoe Print", cursive);
        max-width: 1500px;
        margin: 0 auto;
        padding-bottom: 28px;
        color: var(--h-text);
        font-size: 13.5px;
    }

    .help-page *,
    .help-page *::before,
    .help-page *::after { box-sizing: border-box; }

    .help-page a { text-decoration: none; }
    .help-page [hidden] { display: none !important; }

    .help-page .paper {
        background: #fff;
        border: 1px solid var(--h-line);
        border-radius: 10px;
    }

    /* Hero */
    .help-page .hero {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
        margin-bottom: 22px;
        overflow: hidden;
    }

    .help-page .hero::before {
        content: "";
        position: absolute;
        left: 34px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(228, 87, 46, .35);
    }

    .help-page .hero-left {
        padding: 28px 28px 28px 56px;
    }

    .help-page .crumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
        color: var(--h-muted);
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .help-page .crumb i {
        font-size: 9px;
        opacity: .7;
    }

    .help-page .hero-left h1 {
        margin: 0 0 6px;
        color: var(--h-ink);
        font-family: var(--h-serif);
        font-size: 28px;
        font-weight: 700;
    }

    .help-page .hero-left p {
        max-width: 520px;
        margin: 0;
        color: var(--h-muted);
        line-height: 1.7;
    }

    .help-page .hero-btns {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .help-page .btn-solid,
    .help-page .btn-line {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 16px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13.5px;
        font-weight: 500;
        line-height: 1.3;
        white-space: nowrap;
        cursor: pointer;
        transition: background .15s, border-color .15s, color .15s, transform .15s;
    }

    .help-page .btn-solid {
        background: var(--h-ink);
        border-color: var(--h-ink);
        color: #fff;
    }

    .help-page .btn-solid:hover {
        background: var(--h-ink2);
        border-color: var(--h-ink2);
        color: #fff;
        transform: translateY(-1px);
    }

    .help-page .btn-line {
        background: #fff;
        border-color: #cfc8b6;
        color: var(--h-text);
    }

    .help-page .btn-line:hover {
        border-color: var(--h-ink);
        color: var(--h-ink);
    }

    .help-page .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .help-page .bell {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
        padding: 26px 28px;
        color: #e9efe2;
        background: var(--h-ink);
        background-image: radial-gradient(ellipse at 20% 0, rgba(255, 255, 255, .09), transparent 60%);
    }

    .help-page .bell-label {
        color: var(--h-chalk);
        font-family: var(--h-hand);
        font-size: 24px;
        line-height: 1.1;
    }

    .help-page .bell-sub {
        color: #c5d0bd;
        font-size: 13px;
        line-height: 1.6;
    }

    .help-page .bell-stats {
        display: flex;
        gap: 26px;
        margin-top: 6px;
        padding-top: 14px;
        border-top: 1px dashed rgba(255, 255, 255, .22);
    }

    .help-page .bell-stat strong {
        display: block;
        color: #fff;
        font-family: var(--h-serif);
        font-size: 26px;
        line-height: 1.1;
    }

    .help-page .bell-stat span {
        color: #aebba6;
        font-size: 12px;
    }

    /* Alert */
    .help-page .note-box {
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

    .help-page .note-box i {
        margin-top: 2px;
        font-size: 15px;
    }

    /* Toolbar */
    .help-page .toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 34px;
        padding: 14px 18px;
    }

    .help-page .search {
        position: relative;
        flex: 1 1 280px;
        max-width: 420px;
    }

    .help-page .search i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--h-muted);
        font-size: 13px;
        pointer-events: none;
    }

    .help-page .search input {
        width: 100%;
        height: 40px;
        padding: 0 12px 0 34px;
        border: 1px solid #d9d3c2;
        border-radius: 8px;
        background: #fff;
        color: var(--h-text);
        font-family: inherit;
        font-size: 13px;
        outline: 0;
        transition: border-color .15s, box-shadow .15s;
    }

    .help-page .search input:focus {
        border-color: var(--h-ink);
        box-shadow: 0 0 0 3px rgba(29, 58, 51, .1);
    }

    .help-page .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .help-page .chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 13px;
        border: 1px solid var(--h-line);
        border-radius: 20px;
        background: #fff;
        color: #5e6d66;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
    }

    .help-page .chip:hover { border-color: #b5ad98; }

    .help-page .chip em {
        padding: 1px 7px;
        border-radius: 20px;
        background: #f1eee3;
        font-size: 10.5px;
        font-style: normal;
        font-weight: 700;
    }

    .help-page .chip.is-on {
        border-color: var(--h-ink);
        background: var(--h-ink);
        color: #fff;
    }

    .help-page .chip.is-on em {
        background: rgba(255, 255, 255, .2);
        color: #fff;
    }

    /* Category cards */
    .help-page .grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 34px 20px;
        align-items: start;
    }

    .help-page .card {
        --c: #2b5d8a;
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        margin-top: 10px;
        padding: 22px 20px 18px;
        border-top: 4px solid var(--c);
        transition: box-shadow .22s ease;
    }

    .help-page .card:hover {
        box-shadow: 0 14px 26px rgba(29, 58, 51, .12);
    }

    .help-page .c-blue { --c: #2b5d8a; }
    .help-page .c-amber { --c: #d58a0b; }
    .help-page .c-green { --c: #3a9d6b; }
    .help-page .c-red { --c: #e4572e; }
    .help-page .c-violet { --c: #7a4fb5; }
    .help-page .c-cyan { --c: #3a8a96; }

    .help-page .card-tab {
        position: absolute;
        top: -28px;
        left: 16px;
        padding: 4px 12px;
        border-radius: 7px 7px 0 0;
        background: var(--c);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .help-page .card-head {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px dashed #d8d1bf;
    }

    .help-page .card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: color-mix(in srgb, var(--c) 14%, #fff);
        color: var(--c);
        font-size: 18px;
    }

    .help-page .card-head h2 {
        margin: 0;
        color: var(--h-ink);
        font-family: var(--h-serif);
        font-size: 16px;
        font-weight: 700;
        line-height: 1.3;
    }

    .help-page .card-head small {
        display: block;
        margin-top: 3px;
        color: var(--h-muted);
        font-size: 12px;
    }

    .help-page .issues {
        display: grid;
        gap: 10px;
    }

    .help-page .issue {
        padding: 14px;
        border: 1px solid #ebe5d4;
        border-radius: 9px;
        background: #fffdf8;
        transition: border-color .15s, background .15s;
    }

    .help-page .issue:hover {
        border-color: var(--c);
        background: #fff;
    }

    .help-page .issue h3 {
        margin: 0 0 6px;
        color: var(--h-ink);
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.4;
    }

    .help-page .issue p {
        margin: 0 0 12px;
        color: var(--h-muted);
        font-size: 12.5px;
        line-height: 1.6;
    }

    .help-page .issue-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .help-page .priority {
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

    .help-page .priority::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .help-page .p-high {
        border-color: #f0d5ce;
        background: #fff4ef;
        color: #a84840;
    }

    .help-page .p-medium {
        border-color: #eee0b9;
        background: #fbf3df;
        color: #8a682b;
    }

    .help-page .p-low {
        border-color: #cfe5d3;
        background: #edf7ed;
        color: #2f6b45;
    }

    .help-page .p-none {
        border-color: var(--h-line);
        background: #f6f3ea;
        color: var(--h-muted);
    }

    /* Empty */
    .help-page .empty {
        padding: 50px 20px;
        text-align: center;
    }

    .help-page .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 14px;
        border: 1px solid #e6dfce;
        border-radius: 16px;
        background: var(--h-chalk);
        color: #b8b09e;
        font-size: 24px;
    }

    .help-page .empty h3 {
        margin: 0 0 6px;
        color: var(--h-ink);
        font-family: var(--h-serif);
        font-size: 18px;
    }

    .help-page .empty p {
        max-width: 400px;
        margin: 0 auto;
        color: var(--h-muted);
        line-height: 1.7;
    }

    /* CTA */
    .help-page .cta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        margin-top: 34px;
        padding: 22px 26px;
        border-style: dashed;
    }

    .help-page .cta-text {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .help-page .cta-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        border: 1px solid #e6dfce;
        border-radius: 12px;
        background: var(--h-chalk);
        color: var(--h-ink);
        font-size: 18px;
    }

    .help-page .cta h3 {
        margin: 0 0 4px;
        color: var(--h-ink);
        font-family: var(--h-serif);
        font-size: 17px;
    }

    .help-page .cta p {
        margin: 0;
        color: var(--h-muted);
    }

    @media (max-width: 1200px) {
        .help-page .grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .help-page .hero {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .help-page .hero::before { left: 24px; }
        .help-page .hero-left { padding: 22px 18px 22px 44px; }
        .help-page .hero-left h1 { font-size: 23px; }
        .help-page .bell { padding: 22px 20px 22px 44px; }
        .help-page .hero-btns .btn-solid { flex: 1; }
        .help-page .toolbar { padding: 14px 16px; }
        .help-page .search { max-width: none; flex-basis: 100%; }
        .help-page .grid { grid-template-columns: 1fr; gap: 38px; }
        .help-page .cta { padding: 20px 18px; }
        .help-page .cta .btn-solid { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .help-page * { transition: none !important; }
    }
</style>

@if ($totalCategories > 0)
<script>
(function () {
    const search = document.getElementById('issueSearch');
    const chips  = Array.from(document.querySelectorAll('.help-page .chip'));
    const cards  = Array.from(document.querySelectorAll('.help-page .card[data-category]'));
    const grid   = document.getElementById('categoryGrid');
    const noMatch = document.getElementById('noMatch');
    let activeCategory = 'all';

    function apply() {
        const term = search.value.trim().toLowerCase();
        let visibleCards = 0;

        cards.forEach(function (card) {
            const inCategory = activeCategory === 'all' || card.dataset.category === activeCategory;
            let visibleIssues = 0;

            card.querySelectorAll('.issue').forEach(function (issue) {
                const show = inCategory && (!term || issue.dataset.search.indexOf(term) !== -1);
                issue.hidden = !show;
                if (show) visibleIssues++;
            });

            card.hidden = visibleIssues === 0;
            if (visibleIssues > 0) visibleCards++;
        });

        grid.hidden = visibleCards === 0;
        noMatch.hidden = visibleCards !== 0;
    }

    search.addEventListener('input', apply);

    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            activeCategory = chip.dataset.category;
            chips.forEach(function (c) { c.classList.toggle('is-on', c === chip); });
            apply();
        });
    });
})();
</script>
@endif

@endsection