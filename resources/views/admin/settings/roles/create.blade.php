@extends('layouts.app')

@section('title', 'Add Role | Admin')
@section('page-title', 'Add Role')

@section('content')

<div class="role-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-user-shield"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        SETTINGS <span>/</span> ROLES <span>/</span> ADD ROLE
                    </div>

                    <h2>Add Role</h2>

                    <p>Create a role and assign the permissions required for it.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.settings.roles.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Roles
                </a>
            </div>
        </section>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="error-copy">
                    <strong>Please check the following:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- MAIN FORM CARD --}}
        <section class="form-card">

            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-plus"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">ACCESS CONTROL</div>
                        <h3>Create Role</h3>
                        <p>Define the role name, status and the permissions it should have.</p>
                    </div>
                </div>

                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form action="{{ route('admin.settings.roles.store') }}"
                  method="POST"
                  id="roleForm">
                @csrf

                <div class="form-body">

                    {{-- ROLE INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-id-badge"></i>
                            </div>

                            <div>
                                <h4>Role Information</h4>
                                <span>Basic details of the role.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- ROLE NAME --}}
                            <div class="diary-filter-group">
                                <label for="name">
                                    Role Name <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-tag input-icon"></i>

                                    <input type="text"
                                           name="name"
                                           id="name"
                                           value="{{ old('name') }}"
                                           list="roleNames"
                                           class="diary-control with-icon @error('name') is-invalid @enderror"
                                           placeholder="Select or type a role name"
                                           maxlength="100"
                                           required
                                           autocomplete="off"
                                           @error('name') aria-invalid="true" aria-describedby="name_error" @enderror>
                                </div>

                                <datalist id="roleNames">
                                    @foreach(\App\Models\Role::orderBy('display_name')->get() as $existingRole)
                                        <option value="{{ $existingRole->name }}">
                                            {{ $existingRole->display_name }}
                                        </option>
                                    @endforeach
                                </datalist>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Select an existing name or type a new unique role name.
                                </div>

                                @error('name')
                                    <div class="field-error" id="name_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DISPLAY NAME --}}
                            <div class="diary-filter-group">
                                <label for="display_name">
                                    Display Name <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-signature input-icon"></i>

                                    <input type="text"
                                           name="display_name"
                                           id="display_name"
                                           value="{{ old('display_name') }}"
                                           class="diary-control with-icon @error('display_name') is-invalid @enderror"
                                           placeholder="e.g. Librarian"
                                           maxlength="150"
                                           required
                                           @error('display_name') aria-invalid="true" aria-describedby="display_name_error" @enderror>
                                </div>

                                @error('display_name')
                                    <div class="field-error" id="display_name_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- STATUS --}}
                            <div class="diary-filter-group">
                                <label for="status">
                                    Status <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-toggle-on input-icon"></i>

                                    <select name="status"
                                            id="status"
                                            class="diary-control with-icon @error('status') is-invalid @enderror"
                                            required
                                            @error('status') aria-invalid="true" aria-describedby="status_error" @enderror>
                                        <option value="Active" @selected(old('status', 'Active') === 'Active')>
                                            Active
                                        </option>
                                        <option value="Inactive" @selected(old('status') === 'Inactive')>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                @error('status')
                                    <div class="field-error" id="status_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DESCRIPTION (spans remaining space) --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="description">Description</label>

                                <textarea name="description"
                                          id="description"
                                          rows="3"
                                          maxlength="1000"
                                          class="diary-textarea @error('description') is-invalid @enderror"
                                          placeholder="Describe what this role is responsible for..."
                                          @error('description') aria-invalid="true" aria-describedby="description_error" @enderror>{{ old('description') }}</textarea>

                                <div class="description-footer">
                                    <div class="field-help">
                                        <i class="fas fa-lightbulb"></i>
                                        Optional. Helps other admins understand the purpose of this role.
                                    </div>

                                    <span class="character-count">
                                        <span id="descriptionCount">0</span>/1000
                                    </span>
                                </div>

                                @error('description')
                                    <div class="field-error" id="description_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ASSIGN PERMISSIONS --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-key"></i>
                            </div>

                            <div>
                                <h4>Assign Permissions</h4>
                                <span>Select the modules and actions this role can access.</span>
                            </div>
                        </div>

                        @forelse($permissions as $module => $modulePermissions)

                            <div class="permission-module">

                                <div class="module-header">
                                    <div class="module-title-wrap">
                                        <div class="module-icon">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                        <span class="module-name">{{ $module }}</span>
                                    </div>

                                    <button type="button"
                                            class="select-all-btn"
                                            onclick="toggleModule(this)">
                                        Select All
                                    </button>
                                </div>

                                <div class="permission-list">
                                    @foreach($modulePermissions as $permission)
                                        <label class="permission-item">
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   class="module-permission"
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>

                                            <span class="permission-check"></span>
                                            <span class="permission-label">
                                                {{ $permission->display_name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                            </div>

                        @empty

                            <div class="empty-permissions">
                                <i class="fas fa-lock-open"></i>
                                <p>No permissions available.</p>
                            </div>

                        @endforelse

                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.settings.roles.index') }}"
                       class="diary-reset-btn">
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="diary-primary-btn"
                            id="saveRoleBtn">
                        <i class="fas fa-check"></i>
                        Create Role
                    </button>
                </div>

            </form>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   ROLES — ADD ROLE FORM PAGE
   Same tokens, cards and buttons as Meal Items / School Profile.
   Every selector is scoped under .role-form-page.
========================================================= */

.role-form-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.role-form-page *,
.role-form-page *::before,
.role-form-page *::after {
    box-sizing: border-box;
}

.role-form-page a,
.role-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.role-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.role-form-page .form-hero,
.role-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */

.role-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.role-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.role-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.role-form-page .form-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.role-form-page .form-hero-text {
    min-width: 0;
}

.role-form-page .form-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.role-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.role-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.role-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.role-form-page .form-hero-actions {
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */

.role-form-page .diary-primary-btn,
.role-form-page .diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 16px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.role-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.role-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.role-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.role-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.role-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ERROR ALERT */

.role-form-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #f0d5ce;
    border-radius: 9px;
    color: #a84840;
    background: #fff4ef;
    font-size: 12px;
}

.role-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.role-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.role-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.role-form-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.role-form-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

.role-form-page .alert-close:hover {
    color: var(--ink);
    background: #f8e3dc;
}

/* FORM CARD */

.role-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.role-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.role-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.role-form-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.role-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.role-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.role-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.role-form-page .required-info {
    flex-shrink: 0;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.role-form-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* FORM BODY AND SECTIONS */

.role-form-page .form-body {
    padding: 24px 22px 8px;
}

.role-form-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.role-form-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.role-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.role-form-page .section-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 13px;
}

.role-form-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.role-form-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM GRID AND FIELDS */

.role-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
    gap: 20px 16px;
}

.role-form-page .form-grid-full {
    grid-column: 1 / -1;
}

.role-form-page .diary-filter-group {
    min-width: 0;
}

.role-form-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.role-form-page .required {
    color: var(--red);
}

.role-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.role-form-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
    transition: color .18s ease;
}

.role-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.role-form-page .diary-control,
.role-form-page .diary-textarea {
    display: block;
    width: 100%;
    min-width: 0;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.role-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.role-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.role-form-page select.diary-control {
    cursor: pointer;
}

.role-form-page .diary-control::placeholder,
.role-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.role-form-page .diary-control:hover,
.role-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.role-form-page .diary-control:focus,
.role-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.role-form-page .diary-control.is-invalid,
.role-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.role-form-page .diary-control.is-invalid:focus,
.role-form-page .diary-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
}

.role-form-page .diary-textarea {
    min-height: 90px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

/* HELP AND ERROR TEXT */

.role-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.role-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.role-form-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

.role-form-page .field-error i {
    margin-top: 2px;
}

.role-form-page .description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 9px;
}

.role-form-page .description-footer .field-help {
    margin-top: 0;
}

.role-form-page .character-count {
    flex-shrink: 0;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

/* PERMISSION MODULES */

.role-form-page .permission-module {
    border: 1px solid var(--line);
    border-radius: 9px;
    margin-bottom: 14px;
    overflow: hidden;
    background: #fffefa;
}

.role-form-page .permission-module:last-child {
    margin-bottom: 0;
}

.role-form-page .module-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 16px;
    border-bottom: 1px solid #e6dfce;
    background: var(--chalk);
}

.role-form-page .module-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.role-form-page .module-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 28px;
    width: 28px;
    height: 28px;
    border: 1px solid #e3e2d5;
    border-radius: 7px;
    color: var(--ink);
    background: #fffefa;
    font-size: 11px;
}

.role-form-page .module-name {
    color: var(--ink);
    font-size: 13px;
    font-weight: 700;
}

.role-form-page .select-all-btn {
    flex-shrink: 0;
    padding: 5px 11px;
    border: 1px solid #d8d1bf;
    border-radius: 20px;
    color: var(--ink);
    background: #fffefa;
    font-family: inherit;
    font-size: 10px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}

.role-form-page .select-all-btn:hover {
    border-color: #b7c4b7;
    background: #f5f4eb;
    color: var(--ink-hover);
}

.role-form-page .permission-list {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 8px 12px;
    padding: 14px 16px;
}

.role-form-page .permission-item {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 0;
    padding: 7px 9px;
    border: 1px solid transparent;
    border-radius: 7px;
    cursor: pointer;
    transition: background .15s ease, border-color .15s ease;
}

.role-form-page .permission-item:hover {
    border-color: #e6dfce;
    background: #faf9f4;
}

.role-form-page .permission-item input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.role-form-page .permission-check {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 16px;
    width: 16px;
    height: 16px;
    border: 1.5px solid #c5cbbf;
    border-radius: 4px;
    background: #fffefa;
    transition: all .15s ease;
}

.role-form-page .permission-item input:checked + .permission-check {
    border-color: var(--green);
    background: var(--green);
}

.role-form-page .permission-item input:checked + .permission-check::after {
    content: "";
    width: 5px;
    height: 9px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg) translate(-1px, -1px);
}

.role-form-page .permission-label {
    color: #44534a;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.role-form-page .empty-permissions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 40px 20px;
    border: 1px dashed #d8d1bf;
    border-radius: 9px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 13px;
}

.role-form-page .empty-permissions i {
    font-size: 22px;
    color: #b8b09e;
}

/* ACTION BAR */

.role-form-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 8px;
    padding: 15px 22px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 1100px) {
    .role-form-page .permission-list {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .role-form-page .form-hero {
        grid-template-columns: 1fr;
    }

    .role-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }

    .role-form-page .permission-list {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .role-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .role-form-page .form-hero::before {
        left: 22px;
    }

    .role-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .role-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .role-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .role-form-page .form-hero-text p {
        font-size: 12px;
    }

    .role-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .role-form-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .role-form-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .role-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .role-form-page .required-info {
        display: none;
    }

    .role-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .role-form-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .role-form-page .diary-control,
    .role-form-page .diary-textarea {
        font-size: 16px; /* prevents iOS zoom */
    }

    .role-form-page .permission-list {
        grid-template-columns: 1fr;
    }

    .role-form-page .module-header {
        flex-wrap: wrap;
        gap: 10px;
    }

    .role-form-page .description-footer {
        flex-direction: column;
        align-items: flex-start;
    }

    .role-form-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px 15px;
    }

    .role-form-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .role-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .role-form-page .section-heading {
        align-items: flex-start;
    }
}

/* ACCESSIBILITY */

.role-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.role-form-page .diary-control:focus-visible,
.role-form-page .diary-textarea:focus-visible {
    outline: none;
}

.role-form-page .permission-item input:focus-visible + .permission-check {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
    .role-form-page *,
    .role-form-page *::before,
    .role-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var description = document.getElementById('description');
    var counter = document.getElementById('descriptionCount');
    var form = document.getElementById('roleForm');
    var saveBtn = document.getElementById('saveRoleBtn');

    /* Description character counter */
    if (description && counter) {
        var updateCounter = function () {
            counter.textContent = description.value.length;
        };

        description.addEventListener('input', updateCounter);
        updateCounter();
    }

    /* Prevent double submit */
    if (form && saveBtn) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) {
                return;
            }

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        });
    }
});

function toggleModule(element) {
    var moduleBox = element.closest('.permission-module');
    var checkboxes = moduleBox.querySelectorAll('.module-permission');

    var allChecked = Array.from(checkboxes).every(function (checkbox) {
        return checkbox.checked;
    });

    checkboxes.forEach(function (checkbox) {
        checkbox.checked = !allChecked;
    });

    element.textContent = !allChecked ? 'Unselect All' : 'Select All';
}
</script>

@endsection