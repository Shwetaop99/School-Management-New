@extends('layouts.app')

@section('title', 'Backup & Recovery | Admin')
@section('page-title', 'Backup & Recovery')

@section('content')

@php
    $formatSize = function ($bytes) {
        $bytes = (float) $bytes;

        return $bytes >= 1048576
            ? number_format($bytes / 1048576, 2) . ' MB'
            : number_format($bytes / 1024, 2) . ' KB';
    };
@endphp

<div class="backup-page">
    <div class="backup-container">

        {{-- PAGE HEADER --}}
        <section class="backup-hero">
            <div class="backup-hero-content">
                <div class="backup-hero-icon"><i class="bi bi-database"></i></div>

                <div class="backup-hero-text">
                    <div class="backup-breadcrumb">
                        SETTINGS <span>/</span> BACKUP &amp; RECOVERY
                    </div>

                    <h2>Backup &amp; Recovery</h2>
                    <p>Manage your school database backups.</p>
                </div>
            </div>

            <div class="backup-hero-actions">
                <form action="{{ route('admin.backup.create') }}"
                      method="POST"
                      onsubmit="return confirm('Create a new backup? This may take a moment.');">
                    @csrf
                    <button type="submit" class="backup-primary-btn">
                        <i class="bi bi-plus-lg"></i>
                        Create Backup
                    </button>
                </form>

                <a href="{{ route('admin.backup.restore') }}" class="backup-secondary-btn">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Restore Backup
                </a>
            </div>
        </section>

        {{-- FLASH ALERTS --}}
        @if(session('success'))
            <div class="backup-alert success" role="alert">
                <span class="alert-icon"><i class="bi bi-check-lg"></i></span>

                <div class="alert-copy">
                    <strong>Success</strong>
                    <p>{{ session('success') }}</p>
                </div>

                <button type="button" class="alert-close" aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="backup-alert error" role="alert">
                <span class="alert-icon"><i class="bi bi-exclamation-lg"></i></span>

                <div class="alert-copy">
                    <strong>Error</strong>
                    <p>{{ session('error') }}</p>
                </div>

                <button type="button" class="alert-close" aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="backup-alert error" role="alert">
                <span class="alert-icon"><i class="bi bi-exclamation-lg"></i></span>

                <div class="alert-copy">
                    <strong>Please check the following</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN CARD --}}
        <section class="backup-card">

            <div class="backup-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon"><i class="bi bi-archive"></i></div>

                    <div>
                        <div class="section-eyebrow">DATA PROTECTION</div>
                        <h3>Available Backups</h3>
                        <p>Download or delete existing database backups.</p>
                    </div>
                </div>

                @if(!$backups->isEmpty())
                    <div class="backup-count">
                        {{ $backups->count() }}
                        {{ \Illuminate\Support\Str::plural('backup', $backups->count()) }}
                    </div>
                @endif
            </div>

            @if($backups->isEmpty())

                <div class="backup-empty">
                    <div class="empty-icon"><i class="bi bi-archive"></i></div>
                    <h4>No backups found</h4>
                    <p>
                        Click <strong>Create Backup</strong> to make your first
                        database backup.
                    </p>
                </div>

            @else

                <div class="backup-table-wrap">
                    <table class="backup-table">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($backups as $backup)
                                <tr>
                                    <td data-label="Filename">
                                        <div class="file-cell">
                                            <span class="file-icon"><i class="bi bi-file-earmark-zip"></i></span>
                                            <span class="file-name">{{ $backup['name'] }}</span>
                                        </div>
                                    </td>

                                    <td data-label="Type">
                                        <span class="type-badge">{{ $backup['type'] }}</span>
                                    </td>

                                    <td data-label="Size" class="muted-cell">
                                        {{ $formatSize($backup['size']) }}
                                    </td>

                                    <td data-label="Created" class="muted-cell">
                                        {{ $backup['date'] }}
                                    </td>

                                    <td data-label="Actions" class="actions-cell">
                                        <div class="row-actions">

                                            <a href="{{ route('admin.backup.download', ['backup' => $backup['name']]) }}"
                                               class="backup-action-btn download-btn"
                                               title="Download backup">
                                                <i class="bi bi-download"></i>
                                                Download
                                            </a>

                                            <form action="{{ route('admin.backup.destroy', ['backup' => $backup['name']]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Permanently delete this backup? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="backup-action-btn delete-btn"
                                                        title="Delete backup">
                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

        </section>

    </div>
</div>

<style>
.backup-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --amber: #a87543;
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
    font-size: 13px;
}

.backup-page *,
.backup-page *::before,
.backup-page *::after {
    box-sizing: border-box;
}

.backup-page a,
.backup-page button {
    transition: color .18s ease, background .18s ease, border-color .18s ease,
                transform .18s ease, box-shadow .18s ease;
}

.backup-page .backup-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
}

.backup-page .backup-hero,
.backup-page .backup-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */
.backup-page .backup-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.backup-page .backup-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.backup-page .backup-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background: radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%), var(--paper);
}

.backup-page .backup-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.backup-page .backup-hero-text { min-width: 0; }

.backup-page .backup-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.backup-page .backup-breadcrumb span { color: #b8b09e; }

.backup-page .backup-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: clamp(23px, 2.2vw, 29px);
    font-weight: 700;
    line-height: 1.2;
}

.backup-page .backup-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.backup-page .backup-hero-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 9px;
    padding: 0 28px;
}

.backup-page .backup-hero-actions form { margin: 0; }

/* BUTTONS */
.backup-page .backup-primary-btn,
.backup-page .backup-secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 16px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.backup-page .backup-primary-btn {
    border: 1px solid var(--ink);
    color: #fff;
    background: var(--ink);
}

.backup-page .backup-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.backup-page .backup-secondary-btn {
    border: 1px solid #d9cfb7;
    color: var(--ink);
    background: #f5f0e2;
}

.backup-page .backup-secondary-btn:hover {
    transform: translateY(-2px);
    border-color: #c6b58f;
    color: var(--ink-dark);
    background: #eee5ce;
}

/* ALERTS */
.backup-page .backup-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
}

.backup-page .backup-alert.success { border: 1px solid #cfe5d3; color: #2f6b45; background: #edf7ed; }
.backup-page .backup-alert.error   { border: 1px solid #f0d5ce; color: #a84840; background: #fff4ef; }

.backup-page .alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.backup-page .backup-alert.success .alert-icon { background: #d8f0dc; }
.backup-page .backup-alert.error .alert-icon   { background: #fbe0d8; }

.backup-page .alert-copy { flex: 1; min-width: 0; overflow-wrap: anywhere; }
.backup-page .alert-copy strong { display: block; margin-bottom: 4px; font-weight: 800; }
.backup-page .alert-copy p { margin: 0; font-weight: 600; line-height: 1.5; }
.backup-page .alert-copy ul { margin: 0; padding-left: 18px; line-height: 1.6; }

.backup-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

.backup-page .alert-close:hover { color: var(--ink); background: rgba(0, 0, 0, .04); }

/* CARD */
.backup-page .backup-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.backup-page .backup-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
}

.backup-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.backup-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 15px;
}

.backup-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.backup-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.backup-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.backup-page .backup-count {
    flex-shrink: 0;
    padding: 6px 12px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* EMPTY */
.backup-page .backup-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 56px 24px;
    text-align: center;
}

.backup-page .empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    margin-bottom: 16px;
    border: 1px solid #e6dfce;
    border-radius: 14px;
    color: #b8b09e;
    background: var(--chalk);
    font-size: 26px;
}

.backup-page .backup-empty h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.backup-page .backup-empty p {
    max-width: 320px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.6;
}

/* TABLE (desktop / tablet) */
.backup-page .backup-table-wrap { overflow-x: auto; }

.backup-page .backup-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.backup-page .backup-table thead th {
    padding: 12px 18px;
    border-bottom: 1px solid #e6dfce;
    color: #68766d;
    background: #faf9f4;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .6px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.backup-page .backup-table thead th.text-end { text-align: right; }

.backup-page .backup-table tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid #f0ece0;
    vertical-align: middle;
}

.backup-page .backup-table tbody tr:last-child td { border-bottom: 0; }
.backup-page .backup-table tbody tr:hover td { background: #faf9f4; }

.backup-page .actions-cell { text-align: right; }

.backup-page .file-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.backup-page .file-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    border: 1px solid #e6dfce;
    border-radius: 8px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 13px;
}

.backup-page .file-name {
    min-width: 0;
    color: var(--ink);
    font-weight: 700;
    overflow-wrap: anywhere;
}

.backup-page .type-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 9px;
    border: 1px solid #e3e2d5;
    border-radius: 20px;
    color: #59675d;
    background: #f5f4eb;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.backup-page .muted-cell {
    color: var(--muted);
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

/* ROW ACTIONS */
.backup-page .row-actions {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px;
}

.backup-page .row-actions form { display: inline-flex; margin: 0; }

.backup-page .backup-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 32px;
    padding: 6px 11px;
    border: 1px solid transparent;
    border-radius: 6px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.backup-page .download-btn { border-color: #cfe5d3; color: #2f6b45; background: #edf7ed; }
.backup-page .download-btn:hover { border-color: #a8d4b4; color: #245c3a; background: #e0f2e6; }

.backup-page .delete-btn { border-color: #f0d5ce; color: #a84840; background: #fff4ef; }
.backup-page .delete-btn:hover { border-color: #e0b8ae; color: #8f3a33; background: #fdecea; }

/* =========================================================
   RESPONSIVE
========================================================= */

/* Tablet: stack the hero */
@media (max-width: 900px) {
    .backup-page .backup-hero { grid-template-columns: 1fr; }

    .backup-page .backup-hero-actions {
        justify-content: flex-start;
        padding: 0 28px 24px 56px;
    }
}

/* Phones: table becomes stacked cards */
@media (max-width: 700px) {
    .backup-page .backup-container { padding: 16px 12px; }

    .backup-page .backup-hero::before { left: 22px; }

    .backup-page .backup-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .backup-page .backup-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .backup-page .backup-hero-text p { font-size: 12px; }

    .backup-page .backup-hero-actions {
        align-items: stretch;
        flex-direction: column;
        padding: 0 16px 20px 42px;
    }

    .backup-page .backup-hero-actions form,
    .backup-page .backup-hero-actions .backup-primary-btn,
    .backup-page .backup-hero-actions .backup-secondary-btn {
        width: 100%;
    }

    .backup-page .backup-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .backup-page .panel-title-wrap h3 { font-size: 16px; }

    /* card layout */
    .backup-page .backup-table-wrap { overflow: visible; padding: 12px; background: #faf9f4; }

    .backup-page .backup-table,
    .backup-page .backup-table tbody,
    .backup-page .backup-table tr,
    .backup-page .backup-table td { display: block; width: 100%; }

    .backup-page .backup-table thead {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
    }

    .backup-page .backup-table tbody tr {
        margin-bottom: 12px;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: var(--paper);
    }

    .backup-page .backup-table tbody tr:last-child { margin-bottom: 0; }
    .backup-page .backup-table tbody tr:hover td { background: transparent; }

    .backup-page .backup-table tbody td {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 11px 14px;
        border-bottom: 1px solid #f0ece0;
        text-align: right;
    }

    .backup-page .backup-table tbody td::before {
        content: attr(data-label);
        flex: 0 0 auto;
        color: #68766d;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .6px;
        text-transform: uppercase;
    }

    /* filename on its own full-width header row */
    .backup-page .backup-table tbody td[data-label="Filename"] {
        display: block;
        padding: 14px;
        background: #f5f4eb;
        text-align: left;
    }

    .backup-page .backup-table tbody td[data-label="Filename"]::before { display: none; }

    /* actions fill the bottom of the card */
    .backup-page .backup-table tbody td.actions-cell {
        display: block;
        padding: 12px 14px;
        border-bottom: 0;
    }

    .backup-page .backup-table tbody td.actions-cell::before { display: none; }

    .backup-page .row-actions {
        display: flex;
        flex-wrap: nowrap;
        width: 100%;
    }

    .backup-page .row-actions > * { flex: 1; }
    .backup-page .row-actions form { display: flex; }

    .backup-page .backup-action-btn {
        width: 100%;
        min-height: 38px;
    }
}

@media (max-width: 400px) {
    .backup-page .panel-title-icon { flex-basis: 34px; width: 34px; height: 34px; }

    .backup-page .row-actions { flex-direction: column; }
}

.backup-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .backup-page *,
    .backup-page *::before,
    .backup-page *::after {
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection