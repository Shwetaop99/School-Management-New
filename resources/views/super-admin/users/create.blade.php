@extends('super-admin.layouts.app')

@section('title', 'Create Platform Staff')
@section('breadcrumb', 'Administration / Platform Staff / Create')
@section('page-title', 'Create Platform Staff')

@section('content')

<div class="welcome">
    <div>
        <div class="eyebrow">Administration</div>
        <h2>Create Platform Staff</h2>
        <p>Add a new member to your Super Admin team and assign their platform role.</p>
    </div>

    <a href="{{ route('super-admin.users.index') }}" class="chip">
        ← Back to staff
    </a>
</div>

@if($errors->any())
    <div class="staff-alert staff-alert--error">
        <strong>Please correct these errors:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel staff-create-panel">
    <div class="panel-head">
        <div>
            <h3>Staff account details</h3>
            <p>This account will belong to the Super Admin team. Access is controlled by the assigned platform role.</p>
        </div>
        <span class="count-chip">New account</span>
    </div>

    <form method="POST"
          action="{{ route('super-admin.users.store') }}"
          class="staff-create-form">
        @csrf

        <div class="staff-create__section">
            <div class="staff-create__grid">
                <div class="form-group">
                    <label for="name">Full name</label>
                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Enter full name"
                           required
                           class="input @error('name') input--error @enderror">
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="login_id">Login ID</label>
                    <input id="login_id"
                           type="text"
                           name="login_id"
                           value="{{ old('login_id') }}"
                           placeholder="Enter login ID"
                           required
                           class="input @error('login_id') input--error @enderror">
                    @error('login_id')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group form-group--full">
                    <label for="email">Email address</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter email address"
                           required
                           class="input @error('email') input--error @enderror">
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password"
                           type="password"
                           name="password"
                           placeholder="Minimum 8 characters"
                           required
                           class="input @error('password') input--error @enderror">
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           placeholder="Confirm password"
                           required
                           class="input">
                </div>

                <div class="form-group">
                    <label for="super_admin_role_id">Platform role</label>
                    <select id="super_admin_role_id"
                            name="super_admin_role_id"
                            required
                            class="input @error('super_admin_role_id') input--error @enderror">
                        <option value="">Select platform role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                @selected(old('super_admin_role_id') == $role->id)>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('super_admin_role_id')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Account status</label>
                    <select id="status"
                            name="status"
                            required
                            class="input @error('status') input--error @enderror">
                        <option value="Active" @selected(old('status', 'Active') === 'Active')>
                            Active
                        </option>
                        <option value="Inactive" @selected(old('status') === 'Inactive')>
                            Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="staff-create__note">
            <strong>Platform access:</strong>
            This account will belong to the Super Admin team.
            Its access will be controlled by the assigned platform role.
        </div>

        <div class="staff-create__actions">
            <a href="{{ route('super-admin.users.index') }}" class="chip">
                Cancel
            </a>
            <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                Create Staff Account
            </button>
        </div>
    </form>
</div>

<style>
    .staff-alert {
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13.5px;
        line-height: 1.5;
    }

    .staff-alert--error {
        border: 1px solid #f2d8d3;
        background: #fff2ef;
        color: #b54f43;
    }

    .staff-alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .staff-create-panel {
        max-width: 820px;
    }

    .staff-create__section {
        padding: 24px;
    }

    .staff-create__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group--full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 800;
        color: var(--ink, #111827);
        letter-spacing: .01em;
    }

    .input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 9px;
        background: #fff;
        color: var(--ink, #111827);
        font-size: 14px;
        line-height: 1.4;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .input:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, .18);
    }

    .input--error {
        border-color: #f2d8d3;
        background: #fffaf9;
    }

    .field-error {
        font-size: 12px;
        color: #b54f43;
        line-height: 1.4;
    }

    .staff-create__note {
        margin: 0 24px;
        padding: 14px 16px;
        border: 1px solid #d1fae5;
        border-radius: 10px;
        background: #f0fdf4;
        color: #065f46;
        font-size: 13px;
        line-height: 1.6;
    }

    .staff-create__note strong {
        color: #064e3b;
    }

    .staff-create__actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 20px 24px;
        border-top: 1px solid var(--line, #e5e7eb);
        margin-top: 24px;
        background: #fbfcfb;
    }

    @media (max-width: 640px) {
        .staff-create__grid {
            grid-template-columns: 1fr;
        }

        .staff-create__actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .staff-create__actions .chip,
        .staff-create__actions .welcome-action {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
    }
</style>

@endsection