
@extends('layouts.app')

@section('title', 'Restore Backup')

@section('content')
<style>
    .restore-page {
        --restore-green: #123f32;
        --restore-green-light: #1c5946;
        --restore-gold: #c9a44c;
        --restore-bg: #f5f7f6;
    }

    .restore-page .page-header {
        background: linear-gradient(135deg, #123f32, #1c5946);
        border-radius: 14px;
        padding: 26px 30px;
        color: #fff;
        margin-bottom: 25px;
    }

    .restore-page .page-header p {
        color: rgba(255,255,255,.75);
    }

    .restore-page .header-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(255,255,255,.12);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .restore-page .custom-card {
        border: 1px solid #e8ece9;
        border-radius: 13px;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(0,0,0,.045);
        background: #fff;
    }

    .restore-page .custom-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #edf0ee;
        background: #fff;
    }

    .restore-page .custom-card-header h5 {
        color: var(--restore-green);
        font-weight: 700;
        margin: 0;
    }

    .restore-page .custom-card-body {
        padding: 22px;
    }

    .restore-page .section-icon {
        color: var(--restore-gold);
        margin-right: 9px;
    }

    .restore-page .backup-item {
        border: 1px solid #e7ece9;
        border-radius: 11px;
        padding: 18px;
        margin-bottom: 14px;
        transition: .2s ease;
    }

    .restore-page .backup-item:hover {
        border-color: var(--restore-gold);
        box-shadow: 0 4px 12px rgba(0,0,0,.04);
    }

    .restore-page .backup-name {
        color: var(--restore-green);
        font-weight: 700;
        word-break: break-word;
    }

    .restore-page .backup-badge {
        background: #edf5f1;
        color: #176044;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .restore-page .form-control {
        border-radius: 8px;
        padding: 10px 12px;
        border-color: #dfe6e1;
    }

    .restore-page .form-control:focus {
        border-color: var(--restore-gold);
        box-shadow: 0 0 0 3px rgba(201,164,76,.12);
    }

    .restore-page .btn-green {
        background: var(--restore-green);
        border-color: var(--restore-green);
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        padding: 9px 16px;
    }

    .restore-page .btn-green:hover {
        background: var(--restore-green-light);
        color: #fff;
    }

    .restore-page .btn-gold {
        background: var(--restore-gold);
        border-color: var(--restore-gold);
        color: #172d25;
        border-radius: 8px;
        font-weight: 700;
        padding: 9px 16px;
    }

    .restore-page .btn-gold:hover {
        background: #b99238;
        color: #172d25;
    }

    .restore-page .btn-outline-light-custom {
        border: 1px solid rgba(255,255,255,.55);
        color: #fff;
        border-radius: 8px;
    }

    .restore-page .btn-outline-light-custom:hover {
        background: #fff;
        color: var(--restore-green);
    }

    .restore-page .warning-panel {
        background: #fff8e8;
        border: 1px solid #f0dfb7;
        border-left: 4px solid var(--restore-gold);
        border-radius: 9px;
        padding: 16px 18px;
    }

    .restore-page .warning-panel i {
        color: #a77c1e;
    }

    .restore-page .upload-area {
        background: #f8faf9;
        border: 2px dashed #cbd8d0;
        border-radius: 11px;
        padding: 24px;
    }

    .restore-page .upload-icon {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #eaf2ed;
        color: var(--restore-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 12px;
    }

    .restore-page .empty-state {
        padding: 45px 15px;
        text-align: center;
        color: #7b8981;
    }

    .restore-page .empty-state i {
        font-size: 38px;
        color: #bdc9c1;
        margin-bottom: 14px;
    }
</style>

<div class="container-fluid py-4 restore-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>

                <div>
                    <h2 class="fw-bold mb-1">Restore Backup</h2>
                    <p class="mb-0">
                        Recover your school database and uploaded files safely.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.backup.index') }}"
               class="btn btn-outline-light-custom">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Back to Backups
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>
                Please check the following:
            </strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Safety Warning --}}
    <div class="warning-panel mb-4">
        <div class="d-flex align-items-start gap-3">
            <i class="fa-solid fa-shield-halved fa-lg mt-1"></i>
            <div>
                <strong class="d-block mb-1">Important: Before restoring</strong>
                <p class="mb-0 small">
                    Restoring a backup may replace your current database and
                    uploaded files. A safety backup should be created before
                    restoration. Make sure you have the correct backup file
                    and your backup encryption password.
                </p>
            </div>
        </div>
    </div>

    {{-- Existing Backups --}}
    <div class="custom-card mb-4">
        <div class="custom-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5>
                <i class="fa-solid fa-box-archive section-icon"></i>
                Existing Backups
            </h5>

            <span class="badge text-bg-light">
                {{ count($backups) }} Available
            </span>
        </div>

        <div class="custom-card-body">

            @forelse($backups as $backup)
                <div class="backup-item">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div class="flex-grow-1">
                            <div class="backup-name mb-2">
                                <i class="fa-solid fa-file-zipper me-2"></i>
                                {{ $backup['name'] }}
                            </div>

                            <div class="text-muted small">
                                <span class="me-3">
                                    <i class="fa-solid fa-database me-1"></i>
                                    {{ number_format($backup['size'] / 1024, 2) }} KB
                                </span>

                                <span>
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    {{ $backup['date'] }}
                                </span>
                            </div>
                        </div>

                        <span class="backup-badge">
                            <i class="fa-solid fa-shield-halved me-1"></i>
                            ZIP BACKUP
                        </span>
                    </div>

                    <hr class="my-3">

                    <form action="{{ route('admin.backup.restore.apply', ['backup' => $backup['name']]) }}"
                          method="POST"
                          onsubmit="return confirm('WARNING: Restoring this backup may replace your current school database and uploaded files. Have you verified the backup and created a safety copy?');">

                        @csrf

                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    Type RESTORE to confirm
                                </label>

                                <input type="text"
                                       name="confirm_text"
                                       class="form-control"
                                       placeholder="Type RESTORE"
                                       value="{{ old('confirm_text') }}"
                                       required
                                       autocomplete="off">

                                @error('confirm_text')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    Login Password
                                </label>

                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="Enter your login password"
                                       required
                                       autocomplete="current-password">

                                @error('password')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-gold w-100">
                                    <i class="fa-solid fa-rotate-left me-2"></i>
                                    Restore This Backup
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            @empty
                <div class="empty-state">
                    <i class="fa-solid fa-box-open d-block"></i>
                    <h6 class="fw-bold text-dark">No Backups Found</h6>
                    <p class="mb-0">
                        Create a backup first to restore your school data.
                    </p>
                </div>
            @endforelse

        </div>
    </div>

    {{-- Upload Backup --}}
    <div class="custom-card">
        <div class="custom-card-header">
            <h5>
                <i class="fa-solid fa-cloud-arrow-up section-icon"></i>
                Upload a Backup
            </h5>
        </div>

        <div class="custom-card-body">

            <p class="text-muted mb-4">
                Upload a ZIP backup created previously by the School
                Management Backup &amp; Recovery feature.
            </p>

            <form action="{{ route('admin.backup.restore.upload') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="upload-area text-center">

                    <div class="upload-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>

                    <h6 class="fw-bold mb-1">
                        Select Your Backup File
                    </h6>

                    <p class="text-muted small mb-3">
                        Supported format: ZIP archive
                    </p>

                    <div class="text-start mx-auto" style="max-width: 500px;">
                        <label for="backup_file"
                               class="form-label fw-semibold">
                            Backup ZIP file
                        </label>

                        <input type="file"
                               id="backup_file"
                               name="backup_file"
                               class="form-control"
                               accept=".zip,application/zip"
                               required>

                        @error('backup_file')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-green mt-4">
                        <i class="fa-solid fa-shield-halved me-2"></i>
                        Check and Upload Backup
                    </button>

                </div>
            </form>

            <div class="d-flex align-items-start gap-2 mt-3 text-muted small">
                <i class="fa-solid fa-circle-info mt-1"></i>
                <p class="mb-0">
                    Uploading a backup does not automatically restore it.
                    The archive must pass the server's validation checks
                    before it can be used for restoration.
                </p>
            </div>

        </div>
    </div>

</div>
@endsection
