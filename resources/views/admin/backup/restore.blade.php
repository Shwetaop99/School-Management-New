
@extends('layouts.app')

@section('title', 'Restore Backup')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Restore Backup</h2>
            <p class="text-muted mb-0">
                Recover your school database and uploaded files from a backup.
            </p>
        </div>

        <a href="{{ route('backup.index') }}"
           class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Backups
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="alert alert-warning d-flex align-items-start gap-2">
        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
        <div>
            <strong>Restore is not enabled yet.</strong>
            <p class="mb-0">
                No school data will be changed from this page. The backup
                will need to pass automatic validation and safety checks
                before restoration can be enabled.
            </p>
        </div>
    </div>

    {{-- Existing server backups --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fa-solid fa-box-archive me-2"></i>
                Existing Backups
            </h5>
        </div>

        <div class="card-body">
            @forelse($backups as $backup)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="fw-semibold">
                                {{ $backup['name'] }}
                            </div>
                            <div class="text-muted small mt-1">
                                {{ number_format($backup['size'] / 1024, 2) }} KB
                                <span class="mx-1">·</span>
                                {{ $backup['date'] }}
                            </div>
                        </div>

                        <span class="badge bg-secondary">
                            ZIP Backup
                        </span>
                    </div>

                    <div class="mt-3">
                        <button type="button"
                                class="btn btn-warning btn-sm"
                                disabled
                                title="Restore will be enabled after safety testing">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Restore This Backup
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="fa-solid fa-box-open fa-2x text-muted mb-3"></i>
                    <p class="text-muted mb-0">
                        No ZIP backups found on this server.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Upload a backup from another computer --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                Upload a Backup
            </h5>
        </div>

        <div class="card-body">
            <p class="text-muted">
                Select a ZIP backup previously created by this school's
                Backup &amp; Recovery feature.
            </p>

            <form action="{{ route('backup.restore.upload') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="backup_file" class="form-label fw-semibold">
                        Backup ZIP file
                    </label>

                    <input type="file"
                           id="backup_file"
                           name="backup_file"
                           class="form-control"
                           accept=".zip,application/zip"
                           required>

                    <div class="form-text">
                        The file will need to pass automatic archive and
                        encryption checks before any restoration.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                    Check Backup
                </button>
            </form>

            

<div class="small text-muted mt-3">
    The backup is checked before saving. Restoration is not enabled yet,
    and no school data will be changed.
</div>

        </div>
    </div>

</div>
@endsection
