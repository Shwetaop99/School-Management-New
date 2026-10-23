
@extends('layouts.app')

@section('title', 'Restore Backup')

@section('content')
<div class="container-fluid py-4">

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Restore Backup</h2>
            <p class="text-muted mb-0">
                Recover your school database and uploaded files from a backup.
            </p>
        </div>

        <a href="{{ route('admin.backup.index') }}"
           class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Backups
        </a>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Error message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            {{ session('error') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation errors --}}
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

    {{-- Safety notice --}}
    <div class="alert alert-warning d-flex align-items-start gap-2">
        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
        <div>
            <strong>Restore is not enabled yet.</strong>
            <p class="mb-0">
                Restoration requires automatic archive validation, a safety
                backup, and a tested recovery process. No database or uploaded
                files will be changed by the disabled restore controls below.
            </p>
        </div>
    </div>

    {{-- Existing backups --}}
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
                            <div class="fw-semibold text-break">
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
                        <form action="{{ route('admin.backup.restore.apply', ['backup' => $backup['name']]) }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirm('Are you sure you want to restore this backup? Your current school data may be replaced.');">
    @csrf

    <button type="submit"
            class="btn btn-warning btn-sm">
        <i class="fa-solid fa-rotate-left me-1"></i>
        Restore This Backup
    </button>
</form>

                        <span class="text-muted small ms-2">
                            Not available yet
                        </span>
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

    {{-- Upload backup from another computer --}}
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

            <form action="{{ route('admin.backup.restore.upload') }}"
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
                        The uploaded archive must pass ZIP, encryption,
                        metadata, and database-dump checks.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-shield-halved me-1"></i>
                    Check and Upload Backup
                </button>
            </form>

            <div class="small text-muted mt-3">
                Uploading a backup does not restore it. The current upload
                workflow validates and saves the archive; database restoration
                has not yet been implemented.
            </div>
        </div>
    </div>

</div>
@endsection
