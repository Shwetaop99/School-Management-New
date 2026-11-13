@extends('layouts.app')

@section('title', 'Teacher Allocations')
@section('page-title', 'Teacher Allocations')

@section('content')

<style>
    .alloc-page{max-width:1500px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* ---------- Hero ---------- */
    .alloc-hero{
        display:grid;
        grid-template-columns:minmax(0,1.5fr) minmax(0,1fr);
        margin-bottom:22px;
        overflow:hidden;
        position:relative;
    }
    .alloc-hero::before{
        content:"";
        position:absolute;
        left:34px;top:0;bottom:0;
        width:2px;
        background:rgba(228,87,46,.35);
    }
    .alloc-hero-left{padding:26px 28px 26px 56px}
    .alloc-hero-left h1{
        font-family:var(--serif);
        font-size:28px;
        font-weight:700;
        color:var(--ink);
        margin:0 0 6px;
    }
    .alloc-hero-left p{
        color:var(--muted);
        margin:0 0 18px;
        font-size:14px;
        line-height:1.5;
    }
    .hero-btns{display:flex;gap:10px;flex-wrap:wrap}
    .btn-solid,.btn-line{
        display:inline-flex;align-items:center;gap:8px;
        padding:9px 16px;border-radius:8px;
        font-size:13.5px;font-weight:500;
        text-decoration:none;
    }
    .btn-solid{background:var(--ink);color:#fff;border:1px solid var(--ink)}
    .btn-solid:hover{background:var(--ink-2);color:#fff}
    .btn-line{background:#fff;border:1px solid #cfc8b6;color:var(--text)}
    .btn-line:hover{border-color:var(--ink);color:var(--ink)}

    .alloc-hero-right{
        background:var(--ink);
        color:#e9efe2;
        padding:26px 28px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        gap:8px;
        background-image:radial-gradient(ellipse at 20% 0,rgba(255,255,255,.09),transparent 60%);
    }
    .alloc-hero-right .label{
        font-family:var(--hand);
        font-size:22px;
        color:var(--chalk);
        line-height:1;
    }
    .alloc-hero-right .value{
        font-family:var(--serif);
        font-size:26px;
        font-weight:700;
        color:#fff;
        line-height:1.15;
    }
    .alloc-hero-right .sub{
        font-size:13px;
        color:#c5d0bd;
    }

    /* ---------- KPI ---------- */
    .kpi-grid{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:18px;
        margin-bottom:22px;
    }
    .kpi{
        transition:transform .22s ease,box-shadow .22s ease;
        position:relative;
        padding:22px 22px 16px;
        margin-top:10px;
        border-top:4px solid var(--c);
        display:flex;
        flex-direction:column;
        gap:14px;
    }
    .kpi:hover{transform:translateY(-8px);box-shadow:0 16px 28px rgba(29,58,51,.16)}
    .kpi-tab{
        position:absolute;top:-24px;left:16px;
        padding:3px 12px;background:var(--c);color:#fff;
        font-size:12px;font-weight:600;border-radius:7px 7px 0 0;
    }
    .kpi-top{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .kpi-value{font-family:var(--serif);font-size:34px;font-weight:700;line-height:1;color:var(--ink)}
    .kpi-icon{
        width:46px;height:46px;border-radius:12px;
        display:flex;align-items:center;justify-content:center;
        font-size:20px;flex-shrink:0;
        background:color-mix(in srgb,var(--c) 14%,#fff);color:var(--c);
    }
    .kpi-label{padding-top:12px;border-top:1px dashed #d8d1bf;font-size:13px;color:var(--muted)}
    .c-blue{--c:#2b5d8a}
    .c-amber{--c:#d58a0b}
    .c-green{--c:#3a9d6b}

    /* ---------- Panel ---------- */
    .panel{overflow:hidden;margin-bottom:22px}
    .panel-head{
        display:flex;justify-content:space-between;align-items:center;
        gap:12px;padding:15px 22px;border-bottom:1px dashed #d8d1bf;flex-wrap:wrap;
    }
    .panel-head h3{font-family:var(--serif);font-size:17px;font-weight:700;color:var(--ink);margin:0}
    .panel-head span{font-size:12.5px;color:var(--muted)}

    /* Table */
    .table-wrap{width:100%;overflow-x:auto}
    .alloc-table{
        width:100%;min-width:900px;
        border-collapse:collapse;
    }
    .alloc-table thead th{
        padding:13px 16px;background:#f8f6f0;
        border-bottom:1px solid #e6e0d2;
        color:var(--muted);font-size:11px;font-weight:700;
        text-transform:uppercase;letter-spacing:.4px;
        white-space:nowrap;text-align:left;
    }
    .alloc-table tbody td{
        padding:14px 16px;border-bottom:1px solid #f0ebe0;
        color:var(--text);font-size:13px;vertical-align:middle;
    }
    .alloc-table tbody tr:hover td{background:#fffdf6}
    .alloc-table tbody tr:last-child td{border-bottom:none}

    .teacher-name{font-weight:700;color:var(--ink)}

    .class-badge{
        display:inline-flex;padding:4px 10px;border-radius:6px;
        background:color-mix(in srgb,#2b5d8a 10%,#fff);
        color:#2b5d8a;font-size:12px;font-weight:700;
    }

    .actions-cell{display:flex;align-items:center;gap:7px}
    .action-btn{
        width:34px;height:34px;
        display:inline-flex;align-items:center;justify-content:center;
        border-radius:8px;border:1px solid transparent;
        font-size:14px;text-decoration:none;cursor:pointer;
        transition:all .15s;
    }
    .action-view{background:color-mix(in srgb,#2b5d8a 12%,#fff);color:#2b5d8a}
    .action-view:hover{background:#2b5d8a;color:#fff}
    .action-edit{background:color-mix(in srgb,#7a4fb5 12%,#fff);color:#7a4fb5}
    .action-edit:hover{background:#7a4fb5;color:#fff}
    .action-delete{background:color-mix(in srgb,#e4572e 12%,#fff);color:#e4572e;border:none}
    .action-delete:hover{background:#e4572e;color:#fff}

    /* Empty */
    .empty{padding:50px 20px;text-align:center}
    .empty i{font-size:28px;color:var(--muted);display:block;margin-bottom:10px}
    .empty h4{font-family:var(--serif);font-size:16px;font-weight:700;color:var(--ink);margin:0 0 6px}
    .empty p{margin:0;color:var(--muted);font-size:13px}

    @media (max-width:1100px){.kpi-grid{grid-template-columns:1fr}}
    @media (max-width:900px){
        .alloc-hero{grid-template-columns:1fr}
        .alloc-hero-left{padding:22px 20px 22px 44px}
        .alloc-hero::before{left:24px}
    }
    @media (max-width:650px){.alloc-hero-left h1{font-size:23px}}
</style>

<div class="alloc-page">

    {{-- Hero --}}
    <section class="paper alloc-hero">
        <div class="alloc-hero-left">
            <h1>Teacher Allocations</h1>
            <p>Manage teachers allocated to classes and sections.</p>
            <div class="hero-btns">
                <a href="{{ route('admin.teachers.assign-class.create') }}" class="btn-solid">
                    <i class="bi bi-person-plus-fill"></i> Allocate Teacher
                </a>
            </div>
        </div>
        <div class="alloc-hero-right">
            <div class="label">Allocations</div>
            <div class="value">{{ $assignments->count() }} Total</div>
            <div class="sub">Teachers assigned to classes</div>
        </div>
    </section>

    {{-- KPI cards --}}
    <div class="kpi-grid">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Allocations</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $assignments->count() }}</div>
                <div class="kpi-icon"><i class="bi bi-diagram-3-fill"></i></div>
            </div>
            <div class="kpi-label">Total allocations</div>
        </div>

        <div class="paper kpi c-amber">
            <span class="kpi-tab">Teachers</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $assignments->unique('teacher_id')->count() }}</div>
                <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="kpi-label">Unique teachers</div>
        </div>

        <div class="paper kpi c-green">
            <span class="kpi-tab">Classes</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $assignments->unique('class_id')->count() }}</div>
                <div class="kpi-icon"><i class="bi bi-mortarboard-fill"></i></div>
            </div>
            <div class="kpi-label">Classes covered</div>
        </div>
    </div>

    {{-- Table --}}
    <section class="paper panel">
        <div class="panel-head">
            <div>
                <h3>Allocation List</h3>
                <span>Teachers assigned to classes & sections</span>
            </div>
        </div>

        <div class="table-wrap">
            <table class="alloc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Teacher</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Academic Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td class="teacher-name">
                                {{ $assignment->first_name }} {{ $assignment->last_name }}
                            </td>

                            <td>
                                <span class="class-badge">{{ $assignment->class_name }}</span>
                            </td>

                            <td>{{ $assignment->section_name }}</td>

                            <td>{{ $assignment->academic_year ?: '—' }}</td>

                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.teachers.show', $assignment->teacher_id) }}"
                                       class="action-btn action-view" title="View Teacher">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.teachers.assign-class.edit', $assignment->id) }}"
                                       class="action-btn action-edit" title="Edit Allocation">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.teachers.assign-class.destroy', $assignment->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this teacher allocation?');">
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
                            <td colspan="6">
                                <div class="empty">
                                    <i class="bi bi-diagram-3"></i>
                                    <h4>No Allocations Found</h4>
                                    <p>No teachers have been allocated to classes yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@endsection