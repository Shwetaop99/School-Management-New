@extends('layouts.app')

@section('title', 'Backup & Recovery')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Backup & Recovery</h2>
            <p class="text-muted mb-0">Manage your school database backups.</p>
        </div>

        <form action="{{ route('backup.create') }}" method="POST"
              onsubmit="return confirm('Create a new backup?');">
            @csrf
            <button class="btn btn-success" type="submit">
                <i class="fa-solid fa-database me-2"></i>Create Backup
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Available Backups</h5>
        </div>

        <div class="card-body">
            @if($backups->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-solid fa-box-archive fa-3x text-muted mb-3"></i>
                    <h5>No backups found</h5>
                    <p class="text-muted">Click Create Backup to make your first backup.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                                    <td class="fw-semibold">{{ $backup['name'] }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $backup['type'] }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('backup.download', ['backup' => $backup['name']]) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-download"></i> Download
                                        </a>

                                        <form action="{{ route('backup.destroy', ['backup' => $backup['name']]) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Permanently delete this backup?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
