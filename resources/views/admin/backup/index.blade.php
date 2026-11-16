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

            <section class="backup-hero" aria-labelledby="backup-page-title">
                <div class="backup-hero-content">
                    <div class="backup-hero-icon" aria-hidden="true">
                        <i class="bi bi-database"></i>
                    </div>

                    <div class="backup-hero-text">
                        <div class="backup-breadcrumb">
                            SETTINGS <span aria-hidden="true">/</span> BACKUP &amp; RECOVERY
                        </div>

                        <h2 id="backup-page-title">Backup &amp; Recovery</h2>
                        <p>Manage your school database backups.</p>
                    </div>
                </div>

                <div class="backup-hero-actions">
                    <form
                        action="{{ route('admin.backup.create') }}"
                        method="POST"
                        onsubmit="return confirm('Create a new backup? This may take a moment.');"
                    >
                        @csrf

                        <button type="submit" class="backup-primary-btn">
                            <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            Create Backup
                        </button>
                    </form>

                    <a href="{{ route('admin.backup.restore') }}" class="backup-secondary-btn">
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                        Restore Backup
                    </a>
                </div>
            </section>

            @if (session('success'))
                <div class="backup-alert success" role="alert">
                    <span class="alert-icon" aria-hidden="true">
                        <i class="bi bi-check-lg"></i>
                    </span>

                    <div class="alert-copy">
                        <strong>Success</strong>
                        <p>{{ session('success') }}</p>
                    </div>

                    <button
                        type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove()"
                    >
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="backup-alert error" role="alert">
                    <span class="alert-icon" aria-hidden="true">
                        <i class="bi bi-exclamation-lg"></i>
                    </span>

                    <div class="alert-copy">
                        <strong>Error</strong>
                        <p>{{ session('error') }}</p>
                    </div>

                    <button
                        type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove()"
                    >
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="backup-alert error" role="alert">
                    <span class="alert-icon" aria-hidden="true">
                        <i class="bi bi-exclamation-lg"></i>
                    </span>

                    <div class="alert-copy">
                        <strong>Please check the following</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <section class="backup-card" aria-labelledby="backups-heading">
                <div class="backup-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon" aria-hidden="true">
                            <i class="bi bi-archive"></i>
                        </div>

                        <div>
                            <div class="section-eyebrow">DATA PROTECTION</div>
                            <h3 id="backups-heading">Available Backups</h3>
                            <p>Download or delete existing database backups.</p>
                        </div>
                    </div>

                    @if (! $backups->isEmpty())
                        <div class="backup-count">
                            {{ $backups->count() }}
                            {{ \Illuminate\Support\Str::plural('backup', $backups->count()) }}
                        </div>
                    @endif
                </div>

                @if ($backups->isEmpty())
                    <div class="backup-empty">
                        <div class="empty-icon" aria-hidden="true">
                            <i class="bi bi-archive"></i>
                        </div>

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
                                    <th scope="col">Filename</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Size</th>
                                    <th scope="col">Created</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($backups as $backup)
                                    <tr>
                                        <td data-label="Filename">
                                            <div class="file-cell">
                                                <span class="file-icon" aria-hidden="true">
                                                    <i class="bi bi-file-earmark-zip"></i>
                                                </span>
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
                                                <a
                                                    href="{{ route('admin.backup.download', ['backup' => $backup['name']]) }}"
                                                    class="backup-action-btn download-btn"
                                                    title="Download backup"
                                                >
                                                    <i class="bi bi-download" aria-hidden="true"></i>
                                                    Download
                                                </a>

                                                <form
                                                    action="{{ route('admin.backup.destroy', ['backup' => $backup['name']]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Permanently delete this backup? This cannot be undone.');"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="backup-action-btn delete-btn"
                                                        title="Delete backup"
                                                    >
                                                        <i class="bi bi-trash" aria-hidden="true"></i>
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

            box-sizing: border-box;
            width: 100%;
            min-width: 0;
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

        .backup-container {
            width: 100%;
            min-width: 0;
            margin: 0;
            padding: 24px 28px;
        }

        .backup-hero,
        .backup-card {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--paper);
        }

        /* Page header */
        .backup-hero {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 5px 18px rgba(38, 59, 53, 0.045);
        }

        .backup-hero::before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 34px;
            z-index: 1;
            width: 2px;
            background: rgba(189, 81, 72, 0.35);
            content: "";
        }

        .backup-hero-content {
            display: flex;
            align-items: center;
            gap: 17px;
            min-width: 0;
            padding: 26px 28px 26px 56px;
            background:
                radial-gradient(ellipse at top left, rgba(244, 240, 223, 0.65), transparent 65%),
                var(--paper);
        }

        .backup-hero-icon,
        .panel-title-icon,
        .empty-icon,
        .file-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--ink);
            background: var(--chalk);
        }

        .backup-hero-icon {
            width: 54px;
            height: 54px;
            border: 1px solid #e6dfce;
            border-radius: 12px;
            font-size: 22px;
        }

        .backup-hero-text {
            min-width: 0;
        }

        .backup-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 7px;
            color: var(--amber);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.3px;
        }

        .backup-breadcrumb span {
            color: #b8b09e;
        }

        .backup-hero-text h2 {
            margin: 0 0 7px;
            color: var(--ink);
            font-family: var(--serif);
            font-size: clamp(23px, 2.2vw, 29px);
            font-weight: 700;
            line-height: 1.2;
        }

        .backup-hero-text p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.75;
        }

        .backup-hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            padding: 0 28px;
        }

        .backup-hero-actions form,
        .row-actions form {
            margin: 0;
        }

        /* Buttons */
        .backup-primary-btn,
        .backup-secondary-btn,
        .backup-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: inherit;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
        }

        .backup-primary-btn,
        .backup-secondary-btn {
            min-height: 41px;
            padding: 10px 16px;
            border-radius: 7px;
            font-size: 12px;
        }

        .backup-primary-btn {
            border: 1px solid var(--ink);
            color: #fff;
            background: var(--ink);
        }

        .backup-primary-btn:hover {
            transform: translateY(-2px);
            border-color: var(--ink-hover);
            color: #fff;
            background: var(--ink-hover);
            box-shadow: 0 5px 13px rgba(29, 48, 43, 0.14);
        }

        .backup-secondary-btn {
            border: 1px solid #d9cfb7;
            color: var(--ink);
            background: #f5f0e2;
        }

        .backup-secondary-btn:hover {
            transform: translateY(-2px);
            border-color: #c6b58f;
            color: var(--ink-dark);
            background: #eee5ce;
        }

        /* Alerts */
        .backup-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            padding: 13px 16px;
            border-radius: 9px;
            font-size: 12px;
        }

        .backup-alert.success {
            border: 1px solid #cfe5d3;
            color: #2f6b45;
            background: #edf7ed;
        }

        .backup-alert.error {
            border: 1px solid #f0d5ce;
            color: #a84840;
            background: #fff4ef;
        }

        .alert-icon {
            display: inline-flex;
            flex: 0 0 27px;
            align-items: center;
            justify-content: center;
            width: 27px;
            height: 27px;
            border-radius: 50%;
        }

        .backup-alert.success .alert-icon {
            background: #d8f0dc;
        }

        .backup-alert.error .alert-icon {
            background: #fbe0d8;
        }

        .alert-copy {
            flex: 1;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .alert-copy strong {
            display: block;
            margin-bottom: 4px;
            font-weight: 800;
        }

        .alert-copy p {
            margin: 0;
            font-weight: 600;
            line-height: 1.5;
        }

        .alert-copy ul {
            margin: 0;
            padding-left: 18px;
            line-height: 1.6;
        }

        .alert-close {
            display: inline-flex;
            flex: 0 0 30px;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            margin-left: auto;
            border: 0;
            border-radius: 6px;
            color: #68746a;
            background: transparent;
            cursor: pointer;
        }

        .alert-close:hover {
            color: var(--ink);
            background: rgba(0, 0, 0, 0.04);
        }

        /* Backups card */
        .backup-card {
            overflow: hidden;
            box-shadow: 0 5px 17px rgba(38, 59, 53, 0.04);
        }

        .backup-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 18px 21px;
            border-bottom: 1px dashed #d8d1bf;
        }

        .panel-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .panel-title-icon {
            width: 40px;
            height: 40px;
            border: 1px solid #e3e2d5;
            border-radius: 9px;
            font-size: 15px;
        }

        .section-eyebrow {
            margin-bottom: 4px;
            color: var(--amber);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .panel-title-wrap h3 {
            margin: 0;
            color: var(--ink);
            font-family: var(--serif);
            font-size: 18px;
            font-weight: 700;
        }

        .panel-title-wrap p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .backup-count {
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

        /* Empty state */
        .backup-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 56px 24px;
            text-align: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            border: 1px solid #e6dfce;
            border-radius: 14px;
            color: #b8b09e;
            font-size: 26px;
        }

        .backup-empty h4 {
            margin: 0 0 8px;
            color: var(--ink);
            font-family: var(--serif);
            font-size: 18px;
            font-weight: 700;
        }

        .backup-empty p {
            max-width: 320px;
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        /* Backups table */
        .backup-table-wrap {
            overflow-x: auto;
        }

        .backup-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .backup-table thead th {
            padding: 12px 18px;
            border-bottom: 1px solid #e6dfce;
            color: #68766d;
            background: #faf9f4;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.6px;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .backup-table thead th.text-end {
            text-align: right;
        }

        .backup-table tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid #f0ece0;
            vertical-align: middle;
        }

        .backup-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .backup-table tbody tr:hover td {
            background: #faf9f4;
        }

        .file-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .file-icon {
            width: 32px;
            height: 32px;
            border: 1px solid #e6dfce;
            border-radius: 8px;
            font-size: 13px;
        }

        .file-name {
            min-width: 0;
            color: var(--ink);
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .type-badge {
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

        .muted-cell {
            color: var(--muted);
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .actions-cell {
            text-align: right;
        }

        .row-actions {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .backup-action-btn {
            min-height: 32px;
            padding: 6px 11px;
            border: 1px solid transparent;
            border-radius: 6px;
            font-size: 11px;
        }

        .download-btn {
            border-color: #cfe5d3;
            color: #2f6b45;
            background: #edf7ed;
        }

        .download-btn:hover {
            border-color: #a8d4b4;
            color: #245c3a;
            background: #e0f2e6;
        }

        .delete-btn {
            border-color: #f0d5ce;
            color: #a84840;
            background: #fff4ef;
        }

        .delete-btn:hover {
            border-color: #e0b8ae;
            color: #8f3a33;
            background: #fdecea;
        }

        .backup-page :focus-visible {
            outline: 3px solid rgba(82, 127, 121, 0.45);
            outline-offset: 3px;
        }

        @media (max-width: 900px) {
            .backup-hero {
                grid-template-columns: 1fr;
            }

            .backup-hero-actions {
                justify-content: flex-start;
                padding: 0 28px 24px 56px;
            }
        }

        @media (max-width: 700px) {
            .backup-container {
                padding: 16px 12px;
            }

            .backup-hero::before {
                left: 22px;
            }

            .backup-hero-content {
                align-items: flex-start;
                gap: 12px;
                padding: 22px 16px 18px 42px;
            }

            .backup-hero-icon {
                flex-basis: 43px;
                width: 43px;
                height: 43px;
                border-radius: 9px;
                font-size: 18px;
            }

            .backup-hero-text p {
                font-size: 12px;
            }

            .backup-hero-actions {
                align-items: stretch;
                flex-direction: column;
                padding: 0 16px 20px 42px;
            }

            .backup-hero-actions form,
            .backup-hero-actions .backup-primary-btn,
            .backup-hero-actions .backup-secondary-btn {
                width: 100%;
            }

            .backup-panel-header {
                align-items: flex-start;
                flex-direction: column;
                padding: 15px;
            }

            .panel-title-wrap h3 {
                font-size: 16px;
            }

            .backup-table-wrap {
                overflow: visible;
                padding: 12px;
                background: #faf9f4;
            }

            .backup-table,
            .backup-table tbody,
            .backup-table tr,
            .backup-table td {
                display: block;
                width: 100%;
            }

            .backup-table thead {
                position: absolute;
                width: 1px;
                height: 1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
            }

            .backup-table tbody tr {
                overflow: hidden;
                margin-bottom: 12px;
                border: 1px solid var(--line);
                border-radius: 10px;
                background: var(--paper);
            }

            .backup-table tbody tr:last-child {
                margin-bottom: 0;
            }

            .backup-table tbody tr:hover td {
                background: transparent;
            }

            .backup-table tbody td {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                padding: 11px 14px;
                border-bottom: 1px solid #f0ece0;
                text-align: right;
            }

            .backup-table tbody td::before {
                flex: 0 0 auto;
                color: #68766d;
                content: attr(data-label);
                font-size: 9px;
                font-weight: 800;
                letter-spacing: 0.6px;
                text-transform: uppercase;
            }

            .backup-table tbody td[data-label="Filename"] {
                display: block;
                padding: 14px;
                background: #f5f4eb;
                text-align: left;
            }

            .backup-table tbody td[data-label="Filename"]::before,
            .backup-table tbody td.actions-cell::before {
                display: none;
            }

            .backup-table tbody td.actions-cell {
                display: block;
                padding: 12px 14px;
                border-bottom: 0;
            }

            .row-actions {
                display: flex;
                flex-wrap: nowrap;
                width: 100%;
            }

            .row-actions > *,
            .row-actions form {
                flex: 1;
            }

            .row-actions form {
                display: flex;
            }

            .backup-action-btn {
                width: 100%;
                min-height: 38px;
            }
        }

        @media (max-width: 400px) {
            .panel-title-icon {
                width: 34px;
                height: 34px;
            }

            .row-actions {
                flex-direction: column;
            }
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
