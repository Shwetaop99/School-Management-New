
@extends('super-admin.layouts.app')

@section('title', 'Roles & Permissions')

@section('content')

<style>
    .roles-page {
        color: #243b32;
    }

    .roles-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 25px;
    }

    .roles-header h1 {
        font-size: 25px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .roles-header p {
        color: #78877e;
        margin: 0;
        font-size: 14px;
    }

    .roles-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 25px;
    }

    .role-stat {
        background: #fff;
        border: 1px solid #e7eee9;
        border-radius: 13px;
        padding: 20px;
    }

    .role-stat span {
        display: block;
        color: #829087;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .role-stat strong {
        font-size: 27px;
        color: #203b34;
    }

    .roles-card {
        background: #fff;
        border: 1px solid #e7eee9;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 22px;
    }

    .roles-card h3 {
        font-size: 17px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .roles-card .subtitle {
        color: #849188;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .role-item {
        background: #fff;
        border: 1px solid #e5ece7;
        border-radius: 13px;
        padding: 22px;
        margin-bottom: 18px;
    }

    .role-item:last-child {
        margin-bottom: 0;
    }

    .role-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .role-item-header h4 {
        font-size: 17px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .role-item-header p {
        color: #849188;
        font-size: 13px;
        margin: 0;
    }

    .role-tag {
        background: #eaf4ed;
        color: #347454;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .role-tag.system {
        background: #fff1e9;
        color: #d36a3f;
    }

    .permission-module {
        border-top: 1px solid #edf1ee;
        padding: 16px 0;
    }

    .permission-module h5 {
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 13px;
        color: #365c48;
    }

    .permission-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .permission-option {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13px;
        color: #53645a;
        cursor: pointer;
    }

    .permission-option input {
        accent-color: #347454;
        width: 16px;
        height: 16px;
    }

    .role-form label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #52645a;
        margin-bottom: 7px;
    }

    .role-form input,
    .role-form textarea {
        width: 100%;
        border: 1px solid #dfe8e1;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        margin-bottom: 16px;
        outline: none;
    }

    .role-form input:focus,
    .role-form textarea:focus {
        border-color: #347454;
        box-shadow: 0 0 0 3px rgba(52, 116, 84, .08);
    }

    .role-form textarea {
        min-height: 75px;
        resize: vertical;
    }

    .btn-role {
        background: #2f7658;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 17px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-role:hover {
        background: #245d45;
        color: #fff;
    }

    .btn-delete {
        background: #fff0ed;
        color: #c34e3e;
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 12px;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #ffe0da;
    }

    .role-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .alert-message {
        padding: 13px 16px;
        border-radius: 9px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .alert-success {
        background: #eaf7ee;
        color: #276b43;
    }

    .alert-error {
        background: #fff0ed;
        color: #b33c30;
    }

    .text-danger {
        color: #c34e3e;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 12px;
    }

    @media(max-width: 768px) {
        .roles-stats {
            grid-template-columns: 1fr;
        }

        .permission-grid {
            grid-template-columns: 1fr;
        }

        .roles-card,
        .role-item {
            padding: 16px;
        }
    }
</style>

<div class="roles-page">

    {{-- PAGE HEADER --}}
    <div class="roles-header">
        <div>
            <h1>Roles & Permissions</h1>
            <p>Manage platform staff roles and their access permissions.</p>
        </div>
    </div>

    {{-- SUCCESS / ERROR MESSAGES --}}
    @if(session('success'))
        <div class="alert-message alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-message alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-message alert-error">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="roles-stats">
        <div class="role-stat">
            <span>Total Roles</span>
            <strong>{{ $roles->count() }}</strong>
        </div>

        <div class="role-stat">
            <span>Total Permissions</span>
            <strong>{{ $permissions->flatten()->count() }}</strong>
        </div>

        <div class="role-stat">
            <span>System Roles</span>
            <strong>{{ $roles->where('is_system', true)->count() }}</strong>
        </div>
    </div>

    {{-- CREATE NEW ROLE --}}
    <div class="roles-card">
        <h3>Create New Role</h3>
        <p class="subtitle">
            Create a platform staff role and assign its permissions.
        </p>

        <form action="{{ route('super-admin.roles.store') }}"
              method="POST"
              class="role-form">

            @csrf

            <div class="row">
                <div class="col-md-6">
                    <label>Role Name (System Key)</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. finance_manager"
                           required>
                </div>

                <div class="col-md-6">
                    <label>Display Name</label>
                    <input type="text"
                           name="display_name"
                           value="{{ old('display_name') }}"
                           placeholder="e.g. Finance Manager"
                           required>
                </div>
            </div>

            <label>Description</label>
            <textarea name="description"
                      placeholder="Describe the responsibilities of this role">{{ old('description') }}</textarea>

            <h5 class="mb-3">Assign Permissions</h5>

            @foreach($permissions as $module => $modulePermissions)
                <div class="permission-module">
                    <h5>{{ $module ?: 'Other Permissions' }}</h5>

                    <div class="permission-grid">
                        @foreach($modulePermissions as $permission)
                            <label class="permission-option">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       @checked(in_array($permission->id, old('permissions', [])))>

                                {{ $permission->display_name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn-role">
                Create Role
            </button>

        </form>
    </div>

    {{-- EXISTING ROLES --}}
    <div class="roles-card">
        <h3>Existing Platform Roles</h3>
        <p class="subtitle">
            Review roles and update their assigned permissions.
        </p>

        @forelse($roles as $role)

            <div class="role-item">

                <div class="role-item-header">
                    <div>
                        <h4>{{ $role->display_name }}</h4>
                        <p>
                            {{ $role->description ?: 'No description available.' }}
                        </p>
                        <small class="text-muted">
                            {{ $role->permissions->count() }} permissions assigned
                        </small>
                    </div>

                    <div>
                        @if($role->is_system)
                            <span class="role-tag system">SYSTEM ROLE</span>
                        @else
                            <span class="role-tag">CUSTOM ROLE</span>
                        @endif
                    </div>
                </div>

                {{-- UPDATE ROLE FORM --}}
                <form action="{{ route('super-admin.roles.update', $role->id) }}"
                      method="POST"
                      class="role-form">

                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <label>Role Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ $role->name }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label>Display Name</label>
                            <input type="text"
                                   name="display_name"
                                   value="{{ $role->display_name }}"
                                   required>
                        </div>
                    </div>

                    <label>Description</label>
                    <textarea name="description">{{ $role->description }}</textarea>

                    <h5 class="mb-3">Manage Permissions</h5>

                    @foreach($permissions as $module => $modulePermissions)
                        <div class="permission-module">
                            <h5>{{ $module ?: 'Other Permissions' }}</h5>

                            <div class="permission-grid">
                                @foreach($modulePermissions as $permission)
                                    <label class="permission-option">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               @checked($role->permissions->contains('id', $permission->id))>

                                        {{ $permission->display_name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="role-actions">
                        <button type="submit" class="btn-role">
                            Save Changes
                        </button>
                    </div>

                </form>

                {{-- DELETE ROLE --}}
                @if(!$role->is_system)
                    <div class="role-actions">
                        <form action="{{ route('super-admin.roles.destroy', $role->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this role?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-delete">
                                Delete Role
                            </button>
                        </form>
                    </div>
                @else
                    <small class="text-muted">
                        <i class="fas fa-lock"></i>
                        This is a protected system role.
                    </small>
                @endif

            </div>

        @empty

            <p class="text-muted">No platform roles have been created yet.</p>

        @endforelse
    </div>

</div>

@endsection
