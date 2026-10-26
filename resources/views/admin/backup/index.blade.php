@extends('layouts.app')

@section('title', 'Backup & Recovery | Admin')
@section('page-title', 'Backup & Recovery')

@section('content')
<div class="backup-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-database"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        SETTINGS <span>/</span> BACKUP &amp; RECOVERY
                    </div>

                    <h2>Backup &amp; Recovery</h2>
                    <p>Manage your school database backups.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <div class="hero-action-group">
                    <form action="{{ route('admin.backup.create') }}"
                          method="POST"
                          onsubmit="return confirm('Create a new backup? This may take a moment.');">
                        @csrf

                        <button type="submit" class="diary-primary-btn">
                            <i class="fas fa-plus"></i>
                            Create Backup
                        </button>
                    </form>

                    <a href="{{ route('admin.backup.restore') }}"
                       class="diary-secondary-btn">
                        <i class="fas fa-rotate-left"></i>
                        Restore Backup
                    </a>
                </div>
            </div>
        </section>

        {{-- FLASH ALERTS --}}
        @if(session('success'))
            <div class="form-success-alert" role="alert">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>

                <div class="success-copy">
                    <strong>Success</strong>
                    <p>{{ session('success') }}</p>
                </div>

                <button type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="error-copy">
                    <strong>Error</strong>
                    <p>{{ session('error') }}</p>
                </div>

                <button type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="error-copy">
                    <strong>Please check the following</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN BACKUP CARD --}}
        <section class="form-card">

            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-box-archive"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">DATA PROTECTION</div>
                        <h3>Available Backups</h3>
                        <p>Download or delete existing database backups.</p>
                    </div>
                </div>

                @if(!$backups->isEmpty())
                    <div class="backup-count">
                        {{ $backups->count() }}
                        {{ Str::plural('backup', $backups->count()) }}
                    </div>
                @endif
            </div>

            <div class="form-body">

                {{-- EMPTY STATE --}}
                @if($backups->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-box-archive"></i>
                        </div>

                        <h4>No backups found</h4>

                        <p>
                            Click <strong>Create Backup</strong> to make your first
                            database backup.
                        </p>
                    </div>

                @else

                    {{-- BACKUP TABLE --}}
                    <div class="table-wrap">
                        <table class="diary-table">
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
                                        {{-- FILENAME --}}
                                        <td>
                                            <div class="file-cell">
                                                <span class="file-icon">
                                                    <i class="fas fa-file-zipper"></i>
                                                </span>

                                                <span class="file-name">
                                                    {{ $backup['name'] }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- BACKUP TYPE --}}
                                        <td>
                                            <span class="type-badge">
                                                {{ $backup['type'] }}
                                            </span>
                                        </td>

                                        {{-- FILE SIZE --}}
                                        <td class="size-cell">
                                            {{ number_format($backup['size'] / 1024, 2) }} KB
                                        </td>

                                        {{-- CREATED DATE --}}
                                        <td class="date-cell">
                                            {{ $backup['date'] }}
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="text-end">
                                            <div class="row-actions">

                                                {{-- DOWNLOAD BACKUP --}}
                                                <a href="{{ route('admin.backup.download', ['backup' => $backup['name']]) }}"
                                                   class="diary-action-btn download-btn"
                                                   title="Download backup">
                                                    <i class="fas fa-download"></i>
                                                    Download
                                                </a>

                                                {{-- DELETE BACKUP --}}
                                                <form action="{{ route('admin.backup.destroy', ['backup' => $backup['name']]) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Permanently delete this backup? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="diary-action-btn delete-btn"
                                                            title="Delete backup">
                                                        <i class="fas fa-trash"></i>
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

            </div>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   BACKUP & RECOVERY PAGE
   Warm cream, paper and forest-green design.
   All selectors are scoped under .backup-form-page.
========================================================= */

.backup-form-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.backup-form-page *,
.backup-form-page *::before,
.backup-form-page *::after {
    box-sizing: border-box;
}

.backup-form-page a,
.backup-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.backup-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

/* HERO */

.backup-form-page .form-hero,
.backup-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

.backup-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.backup-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.backup-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(
            ellipse at top left,
            rgba(244, 240, 223, .65),
            transparent 65%
        ),
        var(--paper);
}

.backup-form-page .form-hero-icon {
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

.backup-form-page .form-hero-text {
    min-width: 0;
}

.backup-form-page .form-breadcrumb {
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

.backup-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.backup-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.backup-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.backup-form-page .form-hero-actions {
    padding: 0 28px;
}

.backup-form-page .hero-action-group {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    flex-wrap: wrap;
}

.backup-form-page .hero-action-group form {
    margin: 0;
}

/* BUTTONS */

.backup-form-page .diary-primary-btn,
.backup-form-page .diary-secondary-btn {
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
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.backup-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.backup-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.backup-form-page .diary-secondary-btn {
    border: 1px solid #d9cfb7;
    color: var(--ink) !important;
    background: #f5f0e2;
}

.backup-form-page .diary-secondary-btn:hover {
    transform: translateY(-2px);
    border-color: #c6b58f;
    color: var(--ink-dark) !important;
    background: #eee5ce;
}

/* ALERTS */

.backup-form-page .form-success-alert,
.backup-form-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
}

.backup-form-page .form-success-alert {
    border: 1px solid #cfe5d3;
    color: #2f6b45;
    background: #edf7ed;
}

.backup-form-page .form-error-alert {
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.backup-form-page .success-icon,
.backup-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.backup-form-page .success-icon {
    background: #d8f0dc;
}

.backup-form-page .error-icon {
    background: #fbe0d8;
}

.backup-form-page .success-copy,
.backup-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.backup-form-page .success-copy strong,
.backup-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.backup-form-page .success-copy p,
.backup-form-page .error-copy p {
    margin: 0;
    font-weight: 600;
    line-height: 1.5;
}

.backup-form-page .alert-close {
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

.backup-form-page .alert-close:hover {
    color: var(--ink);
    background: rgba(0, 0, 0, .04);
}

/* MAIN CARD */

.backup-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.backup-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.backup-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.backup-form-page .panel-title-icon {
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
    font-size: 14px;
}

.backup-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.backup-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.backup-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.backup-form-page .backup-count {
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

.backup-form-page .form-body {
    padding: 0;
}

/* EMPTY STATE */

.backup-form-page .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 56px 24px;
    text-align: center;
}

.backup-form-page .empty-state-icon {
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

.backup-form-page .empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.backup-form-page .empty-state p {
    max-width: 320px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.6;
}

/* TABLE */

.backup-form-page .table-wrap {
    overflow-x: auto;
}

.backup-form-page .diary-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.backup-form-page .diary-table thead th {
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

.backup-form-page .diary-table thead th.text-end {
    text-align: right;
}

.backup-form-page .diary-table tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid #f0ece0;
    color: var(--text);
    vertical-align: middle;
}

.backup-form-page .diary-table tbody tr:last-child td {
    border-bottom: 0;
}

.backup-form-page .diary-table tbody tr:hover td {
    background: #faf9f4;
}

.backup-form-page .file-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.backup-form-page .file-icon {
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
    font-size: 12px;
}

.backup-form-page .file-name {
    color: var(--ink);
    font-weight: 700;
    overflow-wrap: anywhere;
}

.backup-form-page .type-badge {
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

.backup-form-page .size-cell,
.backup-form-page .date-cell {
    color: var(--muted);
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

.backup-form-page .text-end {
    text-align: right;
}

/* ROW ACTIONS */

.backup-form-page .row-actions {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
}

.backup-form-page .diary-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 32px;
    padding: 6px 11px;
    border-radius: 6px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.backup-form-page .download-btn {
    border: 1px solid #cfe5d3;
    color: #2f6b45 !important;
    background: #edf7ed;
}

.backup-form-page .download-btn:hover {
    border-color: #a8d4b4;
    color: #245c3a !important;
    background: #e0f2e6;
}

.backup-form-page .delete-btn {
    border: 1px solid #f0d5ce;
    color: #a84840 !important;
    background: #fff4ef;
}

.backup-form-page .delete-btn:hover {
    border-color: #e0b8ae;
    color: #8f3a33 !important;
    background: #fdecea;
}

/* TABLET */

@media (max-width: 900px) {
    .backup-form-page .form-hero {
        grid-template-columns: 1fr;
    }

    .backup-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }

    .backup-form-page .hero-action-group {
        justify-content: flex-start;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .backup-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .backup-form-page .form-hero::before {
        left: 22px;
    }

    .backup-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .backup-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .backup-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .backup-form-page .form-hero-text p {
        font-size: 12px;
    }

    .backup-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .backup-form-page .hero-action-group {
        align-items: stretch;
        flex-direction: column;
    }

    .backup-form-page .hero-action-group form,
    .backup-form-page .hero-action-group .diary-primary-btn,
    .backup-form-page .hero-action-group .diary-secondary-btn {
        width: 100%;
    }

    .backup-form-page .form-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .backup-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .backup-form-page .diary-table thead th,
    .backup-form-page .diary-table tbody td {
        padding: 12px 14px;
    }

    .backup-form-page .row-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .backup-form-page .diary-action-btn {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .backup-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
}

/* ACCESSIBILITY */

.backup-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .backup-form-page *,
    .backup-form-page *::before,
    .backup-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>
@endsection

