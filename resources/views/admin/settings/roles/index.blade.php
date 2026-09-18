@extends('layouts.app')

@section('content')

<style>
    .roles-page {
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 15px;
    }

    .page-title h2 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #172033;
    }

    .page-title p {
        margin: 5px 0 0;
        color: #7b8494;
        font-size: 13px;
    }

    .btn-add-role {
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .btn-add-role:hover {
        color: #fff;
        background: #1d4ed8;
    }

    .roles-card {
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(23, 32, 51, 0.05);
        overflow: hidden;
    }

    .roles-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e5eaf2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .roles-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #172033;
    }

    .roles-card-header span {
        font-size: 12px;
        color: #7b8494;
    }

    .roles-table {
        width: 100%;
        border-collapse: collapse;
    }

    .roles-table th {
        background: #f8faff;
        color: #667085;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        padding: 13px 18px;
        border-bottom: 1px solid #e5eaf2;
        text-align: left;
    }

    .roles-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #edf0f5;
        vertical-align: middle;
        font-size: 13px;
        color: #344054;
    }

    .roles-table tbody tr:last-child td {
        border-bottom: none;
    }

    .roles-table tbody tr:hover {
        background: #fbfcff;
    }

    .role-name {
        font-weight: 700;
        color: #172033;
        margin-bottom: 3px;
    }

    .role-description {
        color: #8a93a3;
        font-size: 11px;
        max-width: 320px;
    }

    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 28px;
        padding: 0 8px;
        background: #eef4ff;
        color: #2563eb;
        border-radius: 7px;
        font-weight: 700;
        font-size: 12px;
    }

    .permission-count {
        color: #344054;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-active {
        background: #eaf8f0;
        color: #138a4b;
    }

    .status-inactive {
        background: #f1f3f5;
        color: #667085;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .edit-btn {
        background: #eef4ff;
        color: #2563eb;
    }

    .edit-btn:hover {
        background: #dce8ff;
        color: #1d4ed8;
    }

    .delete-btn {
        background: #fff0f1;
        color: #dc3545;
    }

    .delete-btn:hover {
        background: #ffe0e3;
        color: #c82333;
    }

    .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #7b8494;
    }

    .empty-state i {
        font-size: 36px;
        margin-bottom: 12px;
        color: #aab2c0;
    }

    .empty-state h5 {
        font-size: 15px;
        color: #344054;
        margin-bottom: 5px;
    }

    .empty-state p {
        font-size: 12px;
        margin: 0;
    }

    @media (max-width: 768px) {
        .roles-page {
            padding: 15px;
        }

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .roles-card {
            overflow-x: auto;
        }

        .roles-table {
            min-width: 850px;
        }
    }
</style>


<div class="roles-page">

    {{-- Page Header --}}
    <div class="page-header">

        <div class="page-title">
            <h2>
                <i class="fas fa-user-shield me-2"></i>
                User Roles & Permission
            </h2>

            <p>
                Create roles and control access to school management modules.
            </p>
        </div>

        <a href="{{ route('admin.settings.roles.create') }}"
           class="btn-add-role">

            <i class="fas fa-plus"></i>
            Add Role

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             style="font-size:13px;"
             role="alert">

            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             style="font-size:13px;"
             role="alert">

            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Roles Table --}}
    <div class="roles-card">

        <div class="roles-card-header">

            <h5>
                <i class="fas fa-users-cog me-2"></i>
                All Roles
            </h5>

            <span>
                {{ $roles->count() }} roles
            </span>

        </div>


        @if($roles->count())

            <table class="roles-table">

                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($roles as $role)

                        <tr>

                            {{-- Role --}}
                            <td>

                                <div class="role-name">
                                    {{ $role->display_name }}
                                </div>

                                <div class="role-description">
                                    {{ $role->description ?? 'No description added.' }}
                                </div>

                            </td>


                            {{-- Users --}}
                            <td>

                                <span class="count-badge">
                                    {{ $role->users_count }}
                                </span>

                            </td>


                            {{-- Permissions --}}
                            <td>

                                <span class="permission-count">
                                    {{ $role->permissions->count() }}
                                </span>

                                <span style="font-size:11px;color:#8a93a3;">
                                    permissions
                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($role->status === 'Active')

                                    <span class="status-badge status-active">
                                        Active
                                    </span>

                                @else

                                    <span class="status-badge status-inactive">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="action-buttons">

                                    <a href="{{ route('admin.settings.roles.edit', $role) }}"
                                       class="action-btn edit-btn"
                                       title="Edit Role">

                                        <i class="fas fa-edit"></i>

                                    </a>


                                    @if($role->name !== 'super_admin')

                                        <form action="{{ route('admin.settings.roles.destroy', $role) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this role?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-btn delete-btn"
                                                    title="Delete Role">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-state">

                <i class="fas fa-user-shield"></i>

                <h5>No Roles Found</h5>

                <p>
                    Create your first user role to manage permissions.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection