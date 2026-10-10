@extends('layouts.app')

@section('content')

<style>
    .role-page {
        padding: 24px;
    }

    .page-header {
        margin-bottom: 22px;
    }

    .page-header h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #7b8494;
        font-size: 13px;
    }

    .role-card {
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(23, 32, 51, .05);
        margin-bottom: 20px;
    }

    .role-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e5eaf2;
    }

    .role-card-header h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #172033;
    }

    .role-card-body {
        padding: 20px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #344054;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        font-size: 13px;
        border-color: #d9dee7;
        border-radius: 7px;
        padding: 9px 11px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, .08);
    }

    .permission-module {
        border: 1px solid #e5eaf2;
        border-radius: 9px;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .module-header {
        background: #f8faff;
        padding: 11px 14px;
        border-bottom: 1px solid #e5eaf2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .module-name {
        font-size: 13px;
        font-weight: 700;
        color: #172033;
    }

    .select-all {
        font-size: 11px;
        color: #2563eb;
        cursor: pointer;
        font-weight: 600;
    }

    .permission-list {
        padding: 12px 14px;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 9px;
    }

    .permission-item {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        color: #344054;
        cursor: pointer;
    }

    .permission-item input {
        width: 15px;
        height: 15px;
        accent-color: #2563eb;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 20px;
    }

    .btn-cancel,
    .btn-save {
        border-radius: 7px;
        padding: 9px 16px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-cancel {
        background: #f1f3f5;
        color: #475467;
    }

    .btn-save {
        background: #2563eb;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .text-danger {
        font-size: 11px;
    }

    .protected-note {
        background: #fff8e6;
        border: 1px solid #f4dfaa;
        color: #8a6416;
        border-radius: 7px;
        padding: 9px 12px;
        font-size: 11px;
        margin-top: 10px;
    }

    @media (max-width: 900px) {
        .permission-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .role-page {
            padding: 15px;
        }

        .permission-list {
            grid-template-columns: 1fr;
        }
    }
</style>


<div class="role-page">

    {{-- Header --}}
    <div class="page-header">

        <h2>
            <i class="fas fa-user-shield me-2"></i>
            Edit Role
        </h2>

        <p>
            Update role information and manage its permissions.
        </p>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger" style="font-size:13px;">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form action="{{ route('admin.settings.roles.update', $role) }}"
          method="POST">

        @csrf
        @method('PUT')


        {{-- Role Information --}}
        <div class="role-card">

            <div class="role-card-header">

                <h5>
                    <i class="fas fa-id-badge me-2"></i>
                    Role Information
                </h5>

            </div>

            <div class="role-card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Role Name *
                        </label>

                        <input type="text"
       class="form-control"
       value="{{ $role->name }}"
       readonly>

<small class="text-muted">
    Internal role name cannot be changed.
</small>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Display Name *
                        </label>

                        <input type="text"
                               name="display_name"
                               class="form-control"
                               value="{{ old('display_name', $role->display_name) }}"
                               required>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Describe what this role is responsible for...">{{ old('description', $role->description) }}</textarea>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status *
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="Active"
                                {{ old('status', $role->status) === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ old('status', $role->status) === 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                @if($role->name === 'super_admin')

                    <div class="protected-note">

                        <i class="fas fa-shield-alt me-1"></i>

                        Super Admin is the protected system role.
                        It cannot be deleted.

                    </div>

                @endif

            </div>

        </div>


        {{-- Permissions --}}
        <div class="role-card">

            <div class="role-card-header">

                <h5>
                    <i class="fas fa-key me-2"></i>
                    Assign Permissions
                </h5>

            </div>

            <div class="role-card-body">

                @php
                    $assignedPermissions = $role->permissions
                        ->pluck('id')
                        ->toArray();
                @endphp


                @forelse($permissions as $module => $modulePermissions)

                    <div class="permission-module">

                        <div class="module-header">

                            <span class="module-name">
                                {{ $module }}
                            </span>

                            <span class="select-all"
                                  onclick="toggleModule(this)">

                                Select All

                            </span>

                        </div>


                        <div class="permission-list">

                            @foreach($modulePermissions as $permission)

                                <label class="permission-item">

                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission->id }}"
                                           class="module-permission"

                                           {{ in_array(
                                                $permission->id,
                                                old(
                                                    'permissions',
                                                    $assignedPermissions
                                                )
                                            ) ? 'checked' : '' }}>

                                    <span>
                                        {{ $permission->display_name }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-4">
                        No permissions available.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- Actions --}}
        <div class="form-actions">

            <a href="{{ route('admin.settings.roles.index') }}"
               class="btn-cancel">

                Cancel

            </a>

            <button type="submit"
                    class="btn-save">

                <i class="fas fa-save me-1"></i>
                Update Role

            </button>

        </div>

    </form>

</div>


<script>
    function toggleModule(element) {

        const moduleBox = element.closest('.permission-module');

        const checkboxes = moduleBox.querySelectorAll(
            '.module-permission'
        );

        const allChecked = [...checkboxes].every(
            checkbox => checkbox.checked
        );

        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });

        element.textContent = !allChecked
            ? 'Unselect All'
            : 'Select All';
    }
</script>

@endsection