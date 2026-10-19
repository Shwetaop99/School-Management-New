@extends('layouts.app')

@section('title', 'All Teachers')
@section('page-title', 'All Teachers')

@section('content')

<style>
    .teachers-page {
        max-width: 1500px;
        margin: 0 auto;
    }

    .paper {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 10px;
    }

    /* ── Hero ─────────────────────────────────────────── */
    .teachers-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
        margin-bottom: 22px;
        overflow: hidden;
        position: relative;
    }

    .teachers-hero::before {
        content: "";
        position: absolute;
        left: 34px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(228, 87, 46, .35);
    }

    .teachers-hero-left {
        padding: 26px 28px 26px 56px;
    }

    .teachers-hero-left h1 {
        font-family: var(--serif);
        font-size: 28px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 6px;
    }

    .teachers-hero-left p {
        color: var(--muted);
        margin: 0 0 20px;
        font-size: 14px;
        line-height: 1.5;
    }

    .hero-btns {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    /* ── Functional buttons ───────────────────────────── */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all .15s ease;
        cursor: pointer;
        font-family: inherit;
        border: 1px solid transparent;
        flex-shrink: 0;
    }

    .btn i {
        font-size: 13px;
    }

    .btn-add {
        background: var(--ink);
        color: #fff;
        border-color: var(--ink);
    }
    .btn-add:hover {
        background: var(--ink-2);
        border-color: var(--ink-2);
        color: #fff;
    }

    .btn-excel {
        background: #217346;
        color: #fff;
        border-color: #217346;
    }
    .btn-excel:hover {
        background: #1a5c38;
        border-color: #1a5c38;
        color: #fff;
    }

    .btn-pdf {
        background: #e4572e;
        color: #fff;
        border-color: #e4572e;
    }
    .btn-pdf:hover {
        background: #c93f1a;
        border-color: #c93f1a;
        color: #fff;
    }

    .btn-template {
        background: #5b7a8c;
        color: #fff;
        border-color: #5b7a8c;
    }
    .btn-template:hover {
        background: #4a6575;
        border-color: #4a6575;
        color: #fff;
    }

    .btn-import {
        background: #2b5d8a;
        color: #fff;
        border-color: #2b5d8a;
    }
    .btn-import:hover {
        background: #1f4669;
        border-color: #1f4669;
        color: #fff;
    }

    .btn-import:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    .teachers-hero-right {
        background: var(--ink);
        color: #e9efe2;
        padding: 26px 28px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        background-image: radial-gradient(ellipse at 20% 0, rgba(255, 255, 255, .09), transparent 60%);
    }

    .teachers-hero-right .label {
        font-family: var(--hand);
        font-size: 22px;
        color: var(--chalk);
        line-height: 1;
    }

    .teachers-hero-right .value {
        font-family: var(--serif);
        font-size: 26px;
        font-weight: 700;
        color: #fff;
        line-height: 1.15;
    }

    .teachers-hero-right .sub {
        font-size: 13px;
        color: #c5d0bd;
    }

    /* ── KPI cards ────────────────────────────────────── */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .kpi {
        transition: transform .22s ease, box-shadow .22s ease;
        position: relative;
        padding: 22px 22px 16px;
        margin-top: 10px;
        border-top: 4px solid var(--c);
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .kpi:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 28px rgba(29, 58, 51, .16);
    }

    .kpi-tab {
        position: absolute;
        top: -24px;
        left: 16px;
        padding: 3px 12px;
        background: var(--c);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        border-radius: 7px 7px 0 0;
    }

    .kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .kpi-value {
        font-family: var(--serif);
        font-size: 34px;
        font-weight: 700;
        line-height: 1;
        color: var(--ink);
    }

    .kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        background: color-mix(in srgb, var(--c) 14%, #fff);
        color: var(--c);
    }

    .kpi-label {
        padding-top: 12px;
        border-top: 1px dashed #d8d1bf;
        font-size: 13px;
        color: var(--muted);
    }

    .c-blue  { --c: #2b5d8a; }
    .c-amber { --c: #d58a0b; }
    .c-red   { --c: #e4572e; }

    /* ── Alerts ───────────────────────────────────────── */
    .success-msg,
    .error-msg,
    .import-errors {
        margin-bottom: 18px;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
    }

    .success-msg {
        border: 1px solid #c5e6d0;
        background: #f0faf3;
        color: #2b7a52;
        font-weight: 600;
    }

    .error-msg,
    .import-errors {
        border: 1px solid #f0c6bc;
        background: #fff6f3;
        color: #a83b27;
    }

    .import-errors ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .import-errors li {
        margin: 4px 0;
        overflow-wrap: anywhere;
    }

    /* ── Panel ────────────────────────────────────────── */
    .panel {
        overflow: hidden;
        margin-bottom: 22px;
    }

    .panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 14px 20px;
        border-bottom: 1px dashed #d8d1bf;
        flex-wrap: nowrap;
    }

    .panel-head-left {
        flex-shrink: 0;
    }

    .panel-head h3 {
        font-family: var(--serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .panel-head span {
        font-size: 12.5px;
        color: var(--muted);
    }

    /* ── Single-line toolbar ──────────────────────────── */
    .panel-toolbar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 2px;
        scrollbar-width: thin;
    }

    .panel-toolbar::-webkit-scrollbar {
        height: 4px;
    }

    .panel-toolbar::-webkit-scrollbar-thumb {
        background: #cfc8b6;
        border-radius: 4px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 12px;
        height: 36px;
        border: 1px solid #cfc8b6;
        border-radius: 8px;
        background: #fff;
        min-width: 200px;
        max-width: 240px;
        flex-shrink: 0;
    }

    .search-box i {
        color: var(--muted);
        font-size: 14px;
    }

    .search-box input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 13px;
        color: var(--text);
        width: 100%;
    }

    /* Hidden file input – only Import button is visible */
    .import-form {
        margin: 0;
        flex-shrink: 0;
    }

    .import-form input[type="file"] {
        display: none;
    }

    /* ── Table ────────────────────────────────────────── */
    .table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .teachers-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
    }

    .teachers-table thead th {
        padding: 13px 16px;
        background: #f8f6f0;
        border-bottom: 1px solid #e6e0d2;
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
        text-align: left;
    }

    .teachers-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0ebe0;
        color: var(--text);
        font-size: 13px;
        vertical-align: middle;
    }

    .teachers-table tbody tr:hover td {
        background: #fffdf6;
    }

    .teachers-table tbody tr:last-child td {
        border-bottom: none;
    }

    .teacher-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .teacher-avatar,
    .teacher-avatar-ph {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .teacher-avatar {
        object-fit: cover;
    }

    .teacher-avatar-ph {
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, #2b5d8a 14%, #fff);
        color: #2b5d8a;
        font-size: 14px;
        font-weight: 700;
    }

    .teacher-name {
        font-weight: 700;
        color: var(--ink);
        font-size: 12px;
    }

    .teacher-gender {
        font-size: 11px;
        color: var(--muted);
        margin-top: 2px;
        text-transform: capitalize;
    }

    .id-badge,
    .subject-badge {
        display: inline-flex;
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
    }

    .id-badge {
        background: color-mix(in srgb, #2b5d8a 10%, #fff);
        color: #2b5d8a;
    }

    .subject-badge {
        background: color-mix(in srgb, #7a4fb5 10%, #fff);
        color: #7a4fb5;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-badge.active {
        background: color-mix(in srgb, #3a9d6b 12%, #fff);
        color: #3a9d6b;
    }

    .status-badge.inactive {
        background: color-mix(in srgb, #e4572e 12%, #fff);
        color: #e4572e;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-badge.active .status-dot  { background: #3a9d6b; }
    .status-badge.inactive .status-dot { background: #e4572e; }

    .actions-cell {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .actions-cell form {
        margin: 0;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid transparent;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
        transition: all .15s;
    }

    .action-view {
        background: color-mix(in srgb, #2b5d8a 12%, #fff);
        color: #2b5d8a;
    }
    .action-view:hover {
        background: #2b5d8a;
        color: #fff;
    }

    .action-edit {
        background: color-mix(in srgb, #7a4fb5 12%, #fff);
        color: #7a4fb5;
    }
    .action-edit:hover {
        background: #7a4fb5;
        color: #fff;
    }

    .action-delete {
        background: color-mix(in srgb, #e4572e 12%, #fff);
        color: #e4572e;
        border: none;
    }
    .action-delete:hover {
        background: #e4572e;
        color: #fff;
    }

    /* ── Empty state ──────────────────────────────────── */
    .empty {
        padding: 55px 20px;
        text-align: center;
    }

    .empty i {
        font-size: 30px;
        color: var(--muted);
        display: block;
        margin-bottom: 10px;
    }

    .empty h4 {
        font-family: var(--serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 6px;
    }

    .empty p {
        margin: 0 0 16px;
        color: var(--muted);
        font-size: 13px;
    }

    /* ── Pagination ───────────────────────────────────── */
    .pagination-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 22px;
        border-top: 1px dashed #d8d1bf;
        flex-wrap: wrap;
    }

    .pagination-info {
        font-size: 12.5px;
        color: var(--muted);
    }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width: 1100px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .teachers-hero {
            grid-template-columns: 1fr;
        }
        .teachers-hero-left {
            padding: 22px 20px 22px 44px;
        }
        .teachers-hero::before {
            left: 24px;
        }

        .panel-head {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .panel-toolbar {
            width: 100%;
        }
    }

    @media (max-width: 650px) {
        .teachers-hero-left h1 {
            font-size: 23px;
        }
    }
</style>

<div class="teachers-page">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="success-msg" role="status">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error-msg" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="import-errors" role="alert">
            <strong>Some Excel rows need correction:</strong>
            <ul>
                @foreach(session('import_errors') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($errors->any())
        <div class="import-errors" role="alert">
            <strong>Please correct the following:</strong>
            <ul>
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Hero – only Add Teacher --}}
    <section class="paper teachers-hero">
        <div class="teachers-hero-left">
            <h1>All Teachers</h1>
            <p>Manage faculty information, subjects and teacher records.</p>

            <div class="hero-btns">
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-add">
                    <i class="bi bi-person-plus-fill"></i> Add Teacher
                </a>
            </div>
        </div>

        <div class="teachers-hero-right">
            <div class="label">Faculty</div>
            <div class="value">
                {{ number_format($totalTeachers ?? $teachers->count()) }} Teachers
            </div>
            <div class="sub">Registered in the school</div>
        </div>
    </section>

    {{-- KPI cards --}}
    <div class="kpi-grid">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Total</span>
            <div class="kpi-top">
                <div class="kpi-value">
                    {{ number_format($totalTeachers ?? $teachers->count()) }}
                </div>
                <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="kpi-label">Total teachers</div>
        </div>

        <div class="paper kpi c-amber">
            <span class="kpi-tab">Active</span>
            <div class="kpi-top">
                <div class="kpi-value">
                    {{ number_format($activeTeachers ?? $teachers->where('status', 'Active')->count()) }}
                </div>
                <div class="kpi-icon"><i class="bi bi-person-check-fill"></i></div>
            </div>
            <div class="kpi-label">Active teachers</div>
        </div>

        <div class="paper kpi c-red">
            <span class="kpi-tab">Inactive</span>
            <div class="kpi-top">
                <div class="kpi-value">
                    {{ number_format($inactiveTeachers ?? $teachers->where('status', 'Inactive')->count()) }}
                </div>
                <div class="kpi-icon"><i class="bi bi-person-x-fill"></i></div>
            </div>
            <div class="kpi-label">Inactive teachers</div>
        </div>
    </div>

    {{-- Teachers list --}}
    <section class="paper panel">
        <div class="panel-head">
            <div class="panel-head-left">
                <h3>Teacher List</h3>
                <span>All registered teachers in the school</span>
            </div>

            {{-- Search + all buttons on ONE line --}}
            <div class="panel-toolbar">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text"
                           id="teacherSearch"
                           placeholder="Search name, ID, email..."
                           autocomplete="off"
                           aria-label="Search teachers">
                </div>

                <a href="{{ route('admin.teachers.export.excel') }}" class="btn btn-excel">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>

                <a href="{{ route('admin.teachers.export.pdf') }}" class="btn btn-pdf">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>

                <a href="{{ route('admin.teachers.import.template') }}" class="btn btn-template">
                    <i class="bi bi-download"></i> Download Template
                </a>

                {{-- Import: hidden file + button opens picker then auto-submits --}}
                <form action="{{ route('admin.teachers.import') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="import-form"
                      id="importTeachersForm">
                    @csrf
                    <input type="file"
                           name="file"
                           id="importFileInput"
                           accept=".xlsx,.xls,.csv"
                           required>

                    <button type="button"
                            class="btn btn-import"
                            id="importTeachersBtn">
                        <i class="bi bi-upload"></i> Import
                    </button>
                </form>
            </div>
        </div>

        <div class="table-wrap">
            <table class="teachers-table" id="teachersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Teacher</th>
                        <th>Teacher ID</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th>Subject</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>
                                {{ method_exists($teachers, 'firstItem') ? $teachers->firstItem() + $loop->index : $loop->iteration }}
                            </td>

                            <td>
                                <div class="teacher-profile">
                                    @if(!empty($teacher->profile_image))
                                        <img src="{{ $teacher->profile_image }}"
                                             alt="{{ $teacher->first_name }} {{ $teacher->last_name }}"
                                             class="teacher-avatar">
                                    @else
                                        <div class="teacher-avatar-ph">
                                            {{ strtoupper(substr($teacher->first_name ?? 'T', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="teacher-name">
                                            {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: '—' }}
                                        </div>
                                        <div class="teacher-gender">
                                            {{ $teacher->gender ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="id-badge">{{ $teacher->teacher_id ?? '—' }}</span>
                            </td>

                            <td>{{ $teacher->email ?? '—' }}</td>
                            <td>{{ $teacher->phone ?? '—' }}</td>
                            <td>{{ $teacher->qualification ?? '—' }}</td>

                            <td>
                                @if(!empty($teacher->subject))
                                    <span class="subject-badge">{{ $teacher->subject }}</span>
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                @if(!empty($teacher->joining_date))
                                    {{ \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                @if(strtolower($teacher->status ?? '') === 'active')
                                    <span class="status-badge active">
                                        <span class="status-dot"></span> Active
                                    </span>
                                @else
                                    <span class="status-badge inactive">
                                        <span class="status-dot"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.teachers.show', $teacher->id) }}"
                                       class="action-btn action-view"
                                       title="View"
                                       aria-label="View teacher">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                       class="action-btn action-edit"
                                       title="Edit"
                                       aria-label="Edit teacher">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.teachers.destroy', $teacher->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="action-btn action-delete"
                                                title="Delete"
                                                aria-label="Delete teacher">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty">
                                    <i class="bi bi-person-workspace"></i>
                                    <h4>No Teachers Found</h4>
                                    <p>No teachers have been added yet.</p>
                                    <a href="{{ route('admin.teachers.create') }}" class="btn btn-add">
                                        <i class="bi bi-person-plus-fill"></i> Add First Teacher
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($teachers, 'hasPages') && $teachers->hasPages())
            <div class="pagination-bar">
                <div class="pagination-info">
                    Showing {{ $teachers->firstItem() }} to {{ $teachers->lastItem() }}
                    of {{ $teachers->total() }} teachers
                </div>
                <div>{{ $teachers->links() }}</div>
            </div>
        @endif
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Live search ────────────────────────────────────
    const searchInput = document.getElementById('teacherSearch');
    const table = document.getElementById('teachersTable');

    if (searchInput && table) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();

            table.querySelectorAll('tbody tr').forEach(function (row) {
                if (row.querySelector('.empty')) return;

                row.style.display = row.textContent.toLowerCase().includes(query)
                    ? ''
                    : 'none';
            });
        });
    }

    // ── Import: click button → open file picker → auto submit ─
    const importBtn  = document.getElementById('importTeachersBtn');
    const fileInput  = document.getElementById('importFileInput');
    const importForm = document.getElementById('importTeachersForm');

    if (importBtn && fileInput && importForm) {
        // Click Import → open file dialog
        importBtn.addEventListener('click', function () {
            fileInput.click();
        });

        // After file is selected → submit form
        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                importBtn.disabled = true;
                importBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Importing...';
                importForm.submit();
            }
        });
    }
});
</script>

@endsection