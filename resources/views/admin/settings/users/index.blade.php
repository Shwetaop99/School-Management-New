@extends('layouts.app')



@section('content')



<style>
.users-page {
    --ink:#263b35; --ink-dark:#1d302b; --ink-hover:#456854;
    --text:#34423d; --muted:#78827a; --line:#e5e1d5;
    --paper:#fffefa; --paper-bg:#f7f6f0; --chalk:#f4f0df;
    --amber:#a87543; --green:#3a8060; --red:#bd5148;
    padding:24px; min-height:calc(100vh - 70px);
    color:var(--text); background:var(--paper-bg);
    font-family:"Public Sans",Inter,Arial,sans-serif;
}
.users-page *, .users-page *::before, .users-page *::after { box-sizing:border-box; }
.users-page .page-header {
    display:flex; justify-content:space-between; align-items:center; gap:15px;
    margin-bottom:22px; padding:25px 28px; border:1px solid var(--line);
    border-radius:10px; background:radial-gradient(ellipse at top left,rgba(244,240,223,.7),transparent 65%),var(--paper);
    box-shadow:0 5px 18px rgba(38,59,53,.045);
}
.users-page .page-header-content { display:flex; align-items:center; gap:16px; min-width:0; }
.users-page .page-header-icon {
    display:flex; align-items:center; justify-content:center; flex:0 0 52px;
    width:52px; height:52px; border:1px solid #e6dfce; border-radius:11px;
    color:var(--ink); background:var(--chalk); font-size:21px;
}
.users-page .page-breadcrumb {
    margin-bottom:6px; color:var(--amber); font-size:9px; font-weight:800;
    letter-spacing:1.3px; text-transform:uppercase;
}
.users-page .page-breadcrumb span { color:#b8b09e; padding:0 5px; }
.users-page .page-header h2 {
    margin:0; color:var(--ink); font-family:Georgia,"Times New Roman",serif;
    font-size:28px; font-weight:700;
}
.users-page .page-header p { margin:6px 0 0; color:var(--muted); font-size:13px; }
.users-page .btn-add {
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    padding:11px 18px; border:1px solid var(--ink); border-radius:7px;
    background:var(--ink); color:#fff; font-size:12px; font-weight:700;
    text-decoration:none; transition:.2s ease; white-space:nowrap;
}
.users-page .btn-add:hover { background:var(--ink-hover); border-color:var(--ink-hover); color:#fff; transform:translateY(-2px); }
.users-page .stats-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; margin-bottom:20px; }
.users-page .stat-card {
    display:flex; align-items:center; gap:14px; padding:19px;
    border:1px solid var(--line); border-radius:10px; background:var(--paper);
    box-shadow:0 5px 17px rgba(38,59,53,.04); transition:.2s ease;
}
.users-page .stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(38,59,53,.07); }
.users-page .stat-icon {
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
    width:47px; height:47px; border:1px solid #e6dfce; border-radius:10px;
    background:var(--chalk); color:var(--ink);
}
.users-page .stat-icon svg { width:22px; height:22px; }
.users-page .stat-info span { display:block; margin-bottom:5px; color:var(--muted); font-size:11px; font-weight:700; }
.users-page .stat-info strong { display:block; color:var(--ink); font-family:Georgia,serif; font-size:25px; line-height:1; }
.users-page .filter-card {
    padding:19px; margin-bottom:19px; border:1px solid var(--line);
    border-radius:10px; background:var(--paper); box-shadow:0 5px 17px rgba(38,59,53,.035);
}
.users-page .filter-form { display:grid; grid-template-columns:2fr 1fr 1fr auto; align-items:end; gap:12px; }
.users-page .filter-group label { display:block; margin-bottom:7px; color:var(--ink); font-size:11px; font-weight:800; letter-spacing:.4px; }
.users-page .form-control, .users-page .form-select {
    width:100%; height:41px; padding:0 12px; border:1px solid #deded2;
    border-radius:7px; outline:none; background:#fffefa; color:var(--text); font-size:12px; transition:.2s ease;
}
.users-page .form-control:focus, .users-page .form-select:focus { border-color:var(--green); box-shadow:0 0 0 3px rgba(58,128,96,.10); }
.users-page .btn-filter, .users-page .btn-reset {
    display:inline-flex; align-items:center; justify-content:center; height:41px;
    padding:0 17px; border-radius:7px; font-size:12px; font-weight:700;
    text-decoration:none; cursor:pointer; transition:.2s ease;
}
.users-page .btn-filter { border:1px solid var(--ink); background:var(--ink); color:#fff; }
.users-page .btn-filter:hover { background:var(--ink-hover); }
.users-page .btn-reset { margin-left:5px; border:1px solid #deded2; background:#fffefa; color:#59675d; }
.users-page .btn-reset:hover { border-color:#b7c4b7; background:var(--chalk); color:var(--ink); }
.users-page .table-card {
    overflow:hidden; border:1px solid var(--line); border-radius:10px;
    background:var(--paper); box-shadow:0 5px 17px rgba(38,59,53,.04);
}
.users-page .table-header {
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    padding:18px 21px; border-bottom:1px dashed #d8d1bf;
}
.users-page .table-header h5 { margin:0; color:var(--ink); font-family:Georgia,"Times New Roman",serif; font-size:17px; font-weight:700; }
.users-page .table-header span { color:var(--muted); font-size:11px; font-weight:600; }
.users-page .table-responsive { overflow-x:auto; }
.users-page .users-table { width:100%; min-width:850px; border-collapse:collapse; }
.users-page .users-table th {
    padding:13px 15px; background:#f4f2e9; color:#68766b; font-size:10px;
    font-weight:800; letter-spacing:.7px; text-align:left; text-transform:uppercase; white-space:nowrap;
}
.users-page .users-table td { padding:14px 15px; border-top:1px solid #eeece3; color:var(--text); font-size:12px; vertical-align:middle; }
.users-page .users-table tbody tr { transition:background .18s ease; }
.users-page .users-table tbody tr:hover { background:#faf9f4; }
.users-page .user-info { display:flex; align-items:center; gap:11px; }
.users-page .user-avatar {
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
    width:42px; height:42px; overflow:hidden; border:1px solid #e6dfce;
    border-radius:50%; background:var(--chalk); color:var(--ink); font-size:14px; font-weight:800;
}
.users-page .user-avatar-img { display:block; width:100%; height:100%; border-radius:50%; object-fit:cover; }
.users-page .user-name { color:var(--ink); font-size:12px; font-weight:700; }
.users-page .user-email { margin-top:4px; color:var(--muted); font-size:11px; }
.users-page .login-id { display:inline-block; padding:5px 9px; border:1px solid var(--line); border-radius:5px; background:#faf9f4; color:var(--ink); font-size:11px; font-weight:700; }
.users-page .role-badge { display:inline-block; padding:5px 10px; border:1px solid #e6dfce; border-radius:20px; background:var(--chalk); color:var(--ink); font-size:10px; font-weight:700; }
.users-page .status-badge { display:inline-block; padding:5px 10px; border-radius:20px; font-size:10px; font-weight:800; }
.users-page .status-active { border:1px solid #cfe5d3; background:#edf7ed; color:#2f6b45; }
.users-page .status-inactive { border:1px solid #f0d5ce; background:#fff4ef; color:#a84840; }
.users-page .actions { display:flex; align-items:center; gap:7px; }
.users-page .action-btn {
    display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px;
    border:1px solid transparent; border-radius:7px; cursor:pointer; text-decoration:none; transition:.2s ease;
}
.users-page .action-btn svg { width:15px; height:15px; }
.users-page .view-btn { border-color:#d8e5dd; background:#edf4ef; color:var(--ink); }
.users-page .view-btn:hover { background:#dceae0; }
.users-page .edit-btn { border-color:#e6dfce; background:var(--chalk); color:#8a653d; }
.users-page .edit-btn:hover { background:#eee5cf; }
.users-page .delete-btn { border-color:#f0d5ce; background:#fff4ef; color:var(--red); }
.users-page .delete-btn:hover { background:#fce5df; }
.users-page .empty-state { padding:45px 20px; color:var(--muted); font-size:13px; text-align:center; }
.users-page .pagination-wrap { padding:16px 20px; border-top:1px solid var(--line); }
.users-page .alert { padding:12px 15px; margin-bottom:16px; border-radius:8px; font-size:12px; }
.users-page .alert-success { border:1px solid #cfe5d3; background:#edf7ed; color:#2f6b45; }
.users-page .alert-danger { border:1px solid #f0d5ce; background:#fff4ef; color:#a84840; }
@media(max-width:900px) {
    .users-page .stats-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .users-page .filter-form { grid-template-columns:repeat(2,minmax(0,1fr)); }
}
@media(max-width:600px) {
    .users-page { padding:15px; }
    .users-page .page-header { align-items:flex-start; flex-direction:column; padding:21px; }
    .users-page .page-header h2 { font-size:23px; }
    .users-page .btn-add { width:100%; }
    .users-page .stats-grid, .users-page .filter-form { grid-template-columns:1fr; }
    .users-page .filter-form > div:last-child { display:flex; gap:7px; }
    .users-page .btn-filter, .users-page .btn-reset { flex:1; margin-left:0; }
}
</style>



<div class="users-page">



    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <div class="page-breadcrumb">
                    ADMIN <span>/</span> SETTINGS <span>/</span> USER MANAGEMENT
                </div>
                <h2>User Management</h2>
                <p>Manage user accounts, login credentials, roles and access status.</p>
            </div>
        </div>

        <a href="{{ route('admin.settings.users.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            Add User
        </a>
    </div>

    {{-- Alerts --}}

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



    {{-- Statistics --}}

    <div class="stats-grid">



        <div class="stat-card">

            <div class="stat-icon">

                <svg viewBox="0 0 24 24" fill="none"

                     stroke="currentColor" stroke-width="2">

                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>

                    <circle cx="9" cy="7" r="4"/>

                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>

                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>

                </svg>

            </div>



            <div class="stat-info">

                <span>Total Users</span>

                <strong>{{ $totalUsers }}</strong>

            </div>

        </div>



        <div class="stat-card">

            <div class="stat-icon">

                <svg viewBox="0 0 24 24" fill="none"

                     stroke="currentColor" stroke-width="2">

                    <path d="M20 6L9 17l-5-5"/>

                </svg>

            </div>



            <div class="stat-info">

                <span>Active Users</span>

                <strong>{{ $activeUsers }}</strong>

            </div>

        </div>



        <div class="stat-card">

            <div class="stat-icon">

                <svg viewBox="0 0 24 24" fill="none"

                     stroke="currentColor" stroke-width="2">

                    <path d="M18 6L6 18"/>

                    <path d="M6 6l12 12"/>

                </svg>

            </div>



            <div class="stat-info">

                <span>Inactive Users</span>

                <strong>{{ $inactiveUsers }}</strong>

            </div>

        </div>



    </div>



    {{-- Filters --}}

    <div class="filter-card">



        <form method="GET"

              action="{{ route('admin.settings.users.index') }}"

              class="filter-form">



            <div class="filter-group">

                <label>Search</label>



                <input type="text"

                       name="search"

                       class="form-control"

                       value="{{ request('search') }}"

                       placeholder="Name, Login ID or Email">

            </div>



            <div class="filter-group">

                <label>Role</label>



                <select name="role_id" class="form-select">

                    <option value="">All Roles</option>



                    @foreach($roles as $role)

                        <option value="{{ $role->id }}"

                            {{ request('role_id') == $role->id ? 'selected' : '' }}>

                            {{ $role->display_name }}

                        </option>

                    @endforeach

                </select>

            </div>



            <div class="filter-group">

                <label>Status</label>



                <select name="status" class="form-select">

                    <option value="">All Status</option>



                    <option value="Active"

                        {{ request('status') === 'Active' ? 'selected' : '' }}>

                        Active

                    </option>



                    <option value="Inactive"

                        {{ request('status') === 'Inactive' ? 'selected' : '' }}>

                        Inactive

                    </option>

                </select>

            </div>



            <div>

                <button type="submit" class="btn-filter">

                    Filter

                </button>



                <a href="{{ route('admin.settings.users.index') }}"

                   class="btn-reset">

                    Reset

                </a>

            </div>



        </form>



    </div>



    {{-- Users Table --}}

    <div class="table-card">



        <div class="table-header">

            <h5>All Users</h5>



            <span>

                {{ $users->total() }} user{{ $users->total() == 1 ? '' : 's' }}

            </span>

        </div>



        <div class="table-responsive">



            @if($users->count())



                <table class="users-table">



                    <thead>

                        <tr>

                            <th>User</th>

                            <th>Login ID</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>



                    <tbody>



                        @foreach($users as $user)



                            <tr>



                                <td>

                                    <div class="user-info">



                                        <div class="user-avatar">

    @if($user->profile_photo)

        <img src="{{ $user->profile_photo }}"

             alt="{{ $user->name }}"

             class="user-avatar-img">

    @else

        <span>

            {{ strtoupper(substr($user->name, 0, 1)) }}

        </span>

    @endif

</div>



                                        <div>

                                            <div class="user-name">

                                                {{ $user->name }}

                                            </div>



                                            <div class="user-email">

                                                {{ $user->email }}

                                            </div>

                                        </div>



                                    </div>

                                </td>



                                <td>

                                    <span class="login-id">

                                        {{ $user->login_id }}

                                    </span>

                                </td>



                                <td>

                                    @if($user->role)

                                        <span class="role-badge">

                                            {{ $user->role->display_name }}

                                        </span>

                                    @else

                                        <span style="color:#94a3b8;">

                                            No Role

                                        </span>

                                    @endif

                                </td>



                                <td>



                                    @if($user->status === 'Active')



                                        <span class="status-badge status-active">

                                            Active

                                        </span>



                                    @else



                                        <span class="status-badge status-inactive">

                                            Inactive

                                        </span>



                                    @endif



                                </td>



                                <td>

                                    {{ $user->created_at?->format('d M Y') }}

                                </td>



                                <td>



                                    <div class="actions">



                                        {{-- View --}}

                                        <a href="{{ route('admin.settings.users.show', $user) }}"

                                           class="action-btn view-btn"

                                           title="View">



                                            <svg viewBox="0 0 24 24" fill="none"

                                                 stroke="currentColor"

                                                 stroke-width="2">

                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>

                                                <circle cx="12" cy="12" r="3"/>

                                            </svg>



                                        </a>



                                        {{-- Edit --}}

                                        <a href="{{ route('admin.settings.users.edit', $user) }}"

                                           class="action-btn edit-btn"

                                           title="Edit">



                                            <svg viewBox="0 0 24 24" fill="none"

                                                 stroke="currentColor"

                                                 stroke-width="2">

                                                <path d="M12 20h9"/>

                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>

                                            </svg>



                                        </a>



                                        {{-- Delete --}}

                                        @if($user->id !== auth()->id() && !$user->hasRole('super_admin'))



                                            <form action="{{ route('admin.settings.users.destroy', $user) }}"

                                                  method="POST"

                                                  onsubmit="return confirm('Are you sure you want to delete this user?');">



                                                @csrf

                                                @method('DELETE')



                                                <button type="submit"

                                                        class="action-btn delete-btn"

                                                        title="Delete">



                                                    <svg viewBox="0 0 24 24" fill="none"

                                                         stroke="currentColor"

                                                         stroke-width="2">

                                                        <polyline points="3 6 5 6 21 6"/>

                                                        <path d="M19 6l-1 14H6L5 6"/>

                                                        <path d="M10 11v6"/>

                                                        <path d="M14 11v6"/>

                                                        <path d="M9 6V4h6v2"/>

                                                    </svg>



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

                    No users found.

                </div>



            @endif



        </div>



        @if($users->hasPages())

            <div class="pagination-wrap">

                {{ $users->links() }}

            </div>

        @endif



    </div>



</div>



@endsection