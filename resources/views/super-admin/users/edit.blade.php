
@extends('super-admin.layouts.app')

@section('title', 'Edit Platform Staff')

@section('content')

<style>
    .staff-page {
        color: #243b32;
    }

    .staff-header {
        margin-bottom: 24px;
    }

    .staff-header h1 {
        font-size: 25px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .staff-header p {
        color: #849188;
        font-size: 14px;
    }

    .staff-card {
        background: #fff;
        border: 1px solid #e5ece7;
        border-radius: 14px;
        padding: 26px;
        max-width: 850px;
    }

    .staff-card h3 {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .staff-form label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #52645a;
        margin-bottom: 7px;
    }

    .staff-form input,
    .staff-form select {
        width: 100%;
        border: 1px solid #dfe8e1;
        border-radius: 8px;
        padding: 11px 12px;
        font-size: 13px;
        margin-bottom: 17px;
        outline: none;
    }

    .staff-form input:focus,
    .staff-form select:focus {
        border-color: #347454;
        box-shadow: 0 0 0 3px rgba(52, 116, 84, .08);
    }

    .staff-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }

    .btn-primary-custom {
        background: #2f7658;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary-custom:hover {
        background: #245d45;
        color: #fff;
    }

    .btn-secondary-custom {
        background: #eef2ef;
        color: #53645a;
        border-radius: 8px;
        padding: 11px 20px;
        font-size: 13px;
        text-decoration: none;
    }

    .field-error {
        color: #c34e3e;
        font-size: 12px;
        margin-top: -12px;
        margin-bottom: 14px;
    }

    .form-note {
        background: #edf6f0;
        border-left: 4px solid #347454;
        border-radius: 7px;
        padding: 13px;
        font-size: 12px;
        color: #426451;
        margin-bottom: 22px;
    }

    @media(max-width: 600px) {
        .staff-card {
            padding: 18px;
        }
    }
</style>

<div class="staff-page">

    <div class="staff-header">
        <h1>Edit Platform Staff</h1>
        <p>Update staff account information and platform access.</p>
    </div>

    <div class="staff-card">

        <h3>Staff Account Details</h3>

        <div class="form-note">
            Changes to the platform role will update the permissions
            available to this staff member.
        </div>

        <form action="{{ route('super-admin.users.update', $user->id) }}"
              method="POST"
              class="staff-form">

            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <label>Full Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           required>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label>Login ID</label>
                    <input type="text"
                           name="login_id"
                           value="{{ old('login_id', $user->login_id) }}"
                           required>
                    @error('login_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <label>Email Address</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required>
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <label>New Password</label>
            <input type="password"
                   name="password"
                   placeholder="Leave blank to keep current password">
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <label>Confirm New Password</label>
            <input type="password"
                   name="password_confirmation"
                   placeholder="Confirm new password">

            <label>Platform Role</label>
            <select name="super_admin_role_id" required>
                <option value="">Select platform role</option>

                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        @selected(
                            old('super_admin_role_id', $user->super_admin_role_id)
                            == $role->id
                        )>
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>

            @error('super_admin_role_id')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <label>Account Status</label>
            <select name="status" required>
                <option value="Active"
                    @selected(old('status', $user->status) === 'Active')>
                    Active
                </option>

                <option value="Inactive"
                    @selected(old('status', $user->status) === 'Inactive')>
                    Inactive
                </option>
            </select>

            @error('status')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <div class="staff-actions">
                <button type="submit" class="btn-primary-custom">
                    Save Changes
                </button>

                <a href="{{ route('super-admin.users.index') }}"
                   class="btn-secondary-custom">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

@endsection
