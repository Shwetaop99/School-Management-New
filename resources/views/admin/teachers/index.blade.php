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

    /* ================= HERO ================= */

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

    /* ================= BUTTONS ================= */

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 36px;
        padding: 0 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        white-space: nowrap;
        transition: all .15s ease;
        cursor: pointer;
        font-family: inherit;
        border: 1px solid transparent;
        flex-shrink: 0;
    }

    .btn i { font-size: 13px; }

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

    .btn-bulk {
        background: #fff;
        color: #e4572e;
        border: 1px solid #e4572e;
    }
    .btn-bulk:hover {
        background: #e4572e;
        color: #fff;
    }

    .btn-cancel {
        background: #fff;
        color: var(--ink);
        border: 1px solid #cfc8b6;
    }
    .btn-cancel:hover {
        background: #f8f6f0;
    }

    .btn-delete-selected {
        background: #e4572e;
        color: #fff;
        border-color: #e4572e;
    }
    .btn-delete-selected:hover:not(:disabled) {
        background: #c93f1a;
    }
    .btn-delete-selected:disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    /* ================= KPI ================= */

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

    /* ================= ALERTS ================= */

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

    /* ================= PANEL ================= */

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
        flex-wrap: wrap;
    }

    .panel-head-left h3 {
        font-family: var(--serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .panel-head-left span {
        font-size: 12.5px;
        color: var(--muted);
    }

    .panel-toolbar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
        flex: 1 1 auto;
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
        flex: 1 1 220px;
        min-width: 200px;
        max-width: 280px;
    }

    .search-box i { color: var(--muted); font-size: 14px; }

    .search-box input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 13px;
        color: var(--text);
        width: 100%;
    }

    .import-form { margin: 0; }
    .import-form input[type="file"] { display: none; }

    /* ================= DELETE MODE BAR ================= */

    .delete-mode-bar {
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 12px 20px;
        background: #fff8f5;
        border-bottom: 1px solid #f0c6bc;
        flex-wrap: wrap;
    }

    .delete-mode-bar.active {
        display: flex;
    }

    .delete-mode-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        font-size: 13px;
        font-weight: 600;
        color: #a83b27;
    }

    .delete-mode-left .mode-badge {
        background: #e4572e;
        color: #fff;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .selected-count {
        font-weight: 700;
        color: #e4572e;
    }

    .delete-mode-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ================= TABLE ================= */

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

    .teachers-table tbody tr.selected td {
        background: #fff8f5;
    }

    .teachers-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Checkbox column - hidden until delete mode */
    .th-check,
    .td-check {
        width: 48px;
        text-align: center !important;
        padding-left: 12px !important;
        padding-right: 8px !important;
        display: none;
    }

    .delete-mode .th-check,
    .delete-mode .td-check {
        display: table-cell;
    }

    .teachers-table input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #e4572e;
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

    .teacher-avatar { object-fit: cover; }

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
        font-size: 13px;
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
        font-size: 11px;
        font-weight: 700;
    }

    .id-badge {
        background: color-mix(in srgb, #2b5d8a 10%, #fff);
        color: #2b5d8a;
    }

    .subject-badge {
        background: color-mix(in srgb, #7a4fb5 10%, #fff);
        color: #7a4fb5;
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

    .status-badge.active .status-dot   { background: #3a9d6b; }
    .status-badge.inactive .status-dot { background: #e4572e; }

    .actions-cell {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .actions-cell form { margin: 0; }

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

    .delete-mode .action-delete {
        display: none;
    }

    /* ================= EMPTY ================= */

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

    /* ================= PAGINATION ================= */

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

    /* ================= RESPONSIVE ================= */

    @media (max-width: 1100px) {
        .kpi-grid { grid-template-columns: 1fr; }

        .panel-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .panel-toolbar {
            width: 100%;
            justify-content: flex-start;
        }

        .search-box { max-width: none; }
    }

    @media (max-width: 900px) {
        .teachers-hero { grid-template-columns: 1fr; }
        .teachers-hero-left { padding: 22px 20px 22px 44px; }
        .teachers-hero::before { left: 24px; }
    }

    @media (max-width: 640px) {
        .panel-toolbar { flex-direction: column; align-items: stretch; }
        .panel-toolbar .btn,
        .panel-toolbar .import-form,
        .panel-toolbar .import-form .btn { width: 100%; }
        .delete-mode-actions { width: 100%; }
        .delete-mode-actions .btn { flex: 1; }
    }
</style>

<div class="teachers-page">

    {{-- ================= FLASH MESSAGES ================= --}}

    @if(session('success'))
        <div class="success-msg" id="successMsg">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error-msg">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="import-errors">
            <strong>Some Excel rows need correction:</strong>
            <ul>
                @foreach(session('import_errors') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($errors->any())
        <div class="import-errors">
            <strong>Please correct the following:</strong>
            <ul>
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================= HERO ================= --}}

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
                {{ number_format($totalTeachers ?? $teachers->total()) }} Teachers
            </div>
            <div class="sub">Registered in the school</div>
        </div>
    </section>

    {{-- ================= KPI ================= --}}

    <div class="kpi-grid">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Total</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($totalTeachers ?? $teachers->total()) }}</div>
                <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="kpi-label">Total teachers</div>
        </div>

        <div class="paper kpi c-amber">
            <span class="kpi-tab">Active</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($activeTeachers ?? 0) }}</div>
                <div class="kpi-icon"><i class="bi bi-person-check-fill"></i></div>
            </div>
            <div class="kpi-label">Active teachers</div>
        </div>

        <div class="paper kpi c-red">
            <span class="kpi-tab">Inactive</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ number_format($inactiveTeachers ?? 0) }}</div>
                <div class="kpi-icon"><i class="bi bi-person-x-fill"></i></div>
            </div>
            <div class="kpi-label">Inactive teachers</div>
        </div>
    </div>

    {{-- ================= TEACHER LIST ================= --}}

    <section class="paper panel" id="teachersPanel">

        <div class="panel-head">
            <div class="panel-head-left">
                <h3>Teacher List</h3>
                <span>All registered teachers in the school</span>
            </div>

            <div class="panel-toolbar">

                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="teacherSearch" placeholder="Search name, ID, email..." autocomplete="off">
                </div>

                <button type="button" class="btn btn-bulk" id="enterDeleteModeBtn">
                    <i class="bi bi-trash3"></i> Bulk Delete
                </button>

                <a href="{{ route('admin.teachers.export.excel') }}" class="btn btn-excel">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>

                <a href="{{ route('admin.teachers.export.pdf') }}" class="btn btn-pdf">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>

                <a href="{{ route('admin.teachers.import.template') }}" class="btn btn-template">
                    <i class="bi bi-download"></i> Download Template
                </a>

                <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data" class="import-form" id="importTeachersForm">
                    @csrf
                    <input type="file" name="file" id="importFileInput" accept=".xlsx,.xls,.csv" required>
                    <button type="button" class="btn btn-import" id="importTeachersBtn">
                        <i class="bi bi-upload"></i> Import
                    </button>
                </form>

            </div>
        </div>

        {{-- Delete mode bar --}}
        <div class="delete-mode-bar" id="deleteModeBar">
            <div class="delete-mode-left">
                <span class="mode-badge">Delete Mode</span>
                <span>
                    <span class="selected-count" id="selectedCount">0</span> record(s) selected
                </span>
            </div>
            <div class="delete-mode-actions">
                <button type="button" class="btn btn-cancel" id="cancelDeleteModeBtn">Cancel</button>
                <button type="button" class="btn btn-delete-selected" id="deleteSelectedBtn" disabled>
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
            </div>
        </div>

        {{-- Hidden bulk delete form --}}
    <form action="{{ route('admin.teachers.bulk-destroy') }}" method="POST" id="bulkDeleteForm" style="display:none;">
    @csrf
    <div id="bulkIdsContainer"></div>
</form>

        {{-- Table --}}
        <div class="table-wrap">
            <table class="teachers-table" id="teachersTable">
                <thead>
                    <tr>
                        <th class="th-check">
                            <input type="checkbox" id="selectAll" title="Select all on this page">
                        </th>
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
                        <tr data-id="{{ $teacher->id }}">
                            <td class="td-check">
                                <input type="checkbox" class="row-check" value="{{ $teacher->id }}">
                            </td>

                            <td>{{ $teachers->firstItem() + $loop->index }}</td>

                            <td>
                                <div class="teacher-profile">
                                    @if(!empty($teacher->profile_image))
                                        <img src="{{ $teacher->profile_image }}" alt="{{ $teacher->first_name }}" class="teacher-avatar">
                                    @else
                                        <div class="teacher-avatar-ph">
                                            {{ strtoupper(substr($teacher->first_name ?? 'T', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="teacher-name">
                                            {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: '—' }}
                                        </div>
                                        <div class="teacher-gender">{{ $teacher->gender ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td><span class="id-badge">{{ $teacher->teacher_id ?? '—' }}</span></td>
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
                                    <span class="status-badge active"><span class="status-dot"></span> Active</span>
                                @else
                                    <span class="status-badge inactive"><span class="status-dot"></span> Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="action-btn action-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="action-btn action-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">
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

        {{-- Pagination --}}
        @if($teachers->hasPages())
            <div class="pagination-bar">
                <div class="pagination-info">
                    Showing {{ $teachers->firstItem() }} to {{ $teachers->lastItem() }} of {{ $teachers->total() }} teachers
                </div>
                <div>
                    {{ $teachers->links() }}
                </div>
            </div>
        @endif

    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Auto-hide success message ─────────────────────
    const successMsg = document.getElementById('successMsg');
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.transition = 'opacity .4s';
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 400);
        }, 4000);
    }

    // ── Elements ──────────────────────────────────────
    const panel               = document.getElementById('teachersPanel');
    const table               = document.getElementById('teachersTable');
    const searchInput         = document.getElementById('teacherSearch');
    const enterDeleteModeBtn  = document.getElementById('enterDeleteModeBtn');
    const cancelDeleteModeBtn = document.getElementById('cancelDeleteModeBtn');
    const deleteModeBar       = document.getElementById('deleteModeBar');
    const selectAll           = document.getElementById('selectAll');
    const deleteSelectedBtn   = document.getElementById('deleteSelectedBtn');
    const selectedCountEl     = document.getElementById('selectedCount');
    const bulkDeleteForm      = document.getElementById('bulkDeleteForm');
    const bulkIdsContainer    = document.getElementById('bulkIdsContainer');

    // ── Helpers ───────────────────────────────────────
    function getVisibleCheckboxes() {
        return Array.from(document.querySelectorAll('.row-check')).filter(cb => {
            const row = cb.closest('tr');
            return row && row.style.display !== 'none';
        });
    }

    function getSelectedVisible() {
        return getVisibleCheckboxes().filter(cb => cb.checked);
    }

    function updateSelectedCount() {
        const visible = getVisibleCheckboxes();
        const count   = getSelectedVisible().length;

        if (selectedCountEl) selectedCountEl.textContent = count;
        if (deleteSelectedBtn) deleteSelectedBtn.disabled = count === 0;

        if (selectAll) {
            selectAll.checked       = visible.length > 0 && count === visible.length;
            selectAll.indeterminate = count > 0 && count < visible.length;
        }

        document.querySelectorAll('.row-check').forEach(cb => {
            const row = cb.closest('tr');
            if (row) row.classList.toggle('selected', cb.checked);
        });
    }

    // ── Live search ───────────────────────────────────
    if (searchInput && table) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();

            table.querySelectorAll('tbody tr[data-id]').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
            });

            updateSelectedCount();
        });
    }

    // ── Import ────────────────────────────────────────
    const importBtn  = document.getElementById('importTeachersBtn');
    const fileInput  = document.getElementById('importFileInput');
    const importForm = document.getElementById('importTeachersForm');

    if (importBtn && fileInput && importForm) {
        importBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                importBtn.disabled = true;
                importBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Importing...';
                importForm.submit();
            }
        });
    }

    // ── Bulk delete mode ──────────────────────────────
    function enterDeleteMode() {
        panel.classList.add('delete-mode');
        deleteModeBar.classList.add('active');
        enterDeleteModeBtn.style.display = 'none';
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
        if (selectAll) selectAll.checked = false;
        updateSelectedCount();
    }

    function exitDeleteMode() {
        panel.classList.remove('delete-mode');
        deleteModeBar.classList.remove('active');
        enterDeleteModeBtn.style.display = '';
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
        if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
        updateSelectedCount();
    }

    if (enterDeleteModeBtn)  enterDeleteModeBtn.addEventListener('click', enterDeleteMode);
    if (cancelDeleteModeBtn) cancelDeleteModeBtn.addEventListener('click', exitDeleteMode);

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            getVisibleCheckboxes().forEach(cb => cb.checked = selectAll.checked);
            updateSelectedCount();
        });
    }

    document.querySelectorAll('.row-check').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    // ── Submit bulk delete (only the rows you can see and ticked) ──
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', function () {
            const checked = getSelectedVisible();
            if (checked.length === 0) return;

            const count   = checked.length;
            const message = 'You selected ' + count + ' record' + (count > 1 ? 's' : '') + '.\n\n' +
                            'Are you sure you want to delete ' + (count > 1 ? 'these teachers' : 'this teacher') + '?\n' +
                            'This action cannot be undone.';

            if (!confirm(message)) return;

            bulkIdsContainer.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type  = 'hidden';
                input.name  = 'ids[]';
                input.value = cb.value;
                bulkIdsContainer.appendChild(input);
            });

            deleteSelectedBtn.disabled = true;
            deleteSelectedBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Deleting...';

            bulkDeleteForm.submit();
        });
    }

});
</script>

@endsection