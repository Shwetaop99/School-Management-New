@extends('layouts.app')

@section('title', 'Other Staff')
@section('page-title', 'Other Staff')

@section('content')

<style>
    .si{max-width:1500px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* ---------- Header ---------- */
    .si-head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin-bottom:26px;flex-wrap:wrap}
    .si-head h1{font-family:var(--serif);font-size:26px;font-weight:700;color:var(--ink);margin-bottom:4px}
    .si-head p{color:var(--muted);font-size:14px}
    .si-add{display:inline-flex;align-items:center;gap:8px;height:42px;padding:0 18px;border-radius:8px;background:var(--ink);color:#fff;font-size:14px;font-weight:600;transition:background .15s,transform .15s}
    .si-add i{color:var(--chalk)}
    .si-add:hover{background:var(--ink-2);color:#fff;transform:translateY(-1px)}

    .alert-ok,.alert-err{display:flex;align-items:center;gap:10px;margin-bottom:18px;padding:12px 15px;border-radius:9px;font-size:13.5px;font-weight:500}
    .alert-ok{background:#e3f4ea;border:1px solid #bfe3cd;color:#17703b}
    .alert-err{background:#fdecea;border:1px solid #f5c2bb;color:#b3321d}

    /* ---------- KPI folders ---------- */
    .si-kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-bottom:22px}
    .kpi{transition:transform .22s ease,box-shadow .22s ease;position:relative;padding:22px 22px 20px;margin-top:10px;border-top:4px solid var(--c);display:flex;align-items:center;justify-content:space-between;gap:12px}
    .kpi:hover{transform:translateY(-8px);box-shadow:0 16px 28px rgba(29,58,51,.16)}
    .kpi-tab{position:absolute;top:-24px;left:16px;padding:3px 12px;background:var(--c);color:#fff;font-size:12px;font-weight:600;border-radius:7px 7px 0 0}
    .kpi-value{font-family:var(--serif);font-size:34px;font-weight:700;line-height:1;color:var(--ink)}
    .kpi-note{margin-top:6px;font-size:12.5px;color:var(--muted)}
    .kpi-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:21px;flex-shrink:0;background:color-mix(in srgb,var(--c) 14%,#fff);color:var(--c)}
    .c-blue{--c:#2b5d8a}.c-green{--c:#3a9d6b}.c-red{--c:#e4572e}.c-violet{--c:#7a4fb5}

    /* ---------- Card, filters ---------- */
    .si-card{overflow:hidden}
    .si-head2{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:15px 22px;border-bottom:1px dashed #d8d1bf;flex-wrap:wrap}
    .si-head2 h3{font-family:var(--serif);font-size:17px;font-weight:700;color:var(--ink);margin:0}
    .si-head2 small{display:block;margin-top:2px;font-family:'Public Sans',sans-serif;font-size:12.5px;font-weight:400;color:var(--muted)}
    .count-pill{padding:5px 12px;border-radius:999px;background:var(--accent-soft);color:var(--accent);font-size:12px;font-weight:600;white-space:nowrap}

    .si-filters{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(0,1fr) minmax(0,1fr) auto auto;gap:10px;align-items:center;padding:16px 22px;border-bottom:1px dashed #d8d1bf;background:#fffdf8}
    .si-search{position:relative}
    .si-search i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:14px}
    .si-filters input,.si-filters select{width:100%;height:42px;font:inherit;font-size:13px;color:var(--text);background:#fff;border:1px solid var(--line);border-radius:8px;outline:none;transition:border-color .15s,box-shadow .15s}
    .si-filters input{padding:0 12px 0 36px}
    .si-filters select{padding:0 10px}
    .si-filters input:focus,.si-filters select:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(242,182,50,.4)}
    .si-btn,.si-clear{height:42px;padding:0 18px;display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;font:inherit;font-size:13px;font-weight:600;white-space:nowrap;cursor:pointer}
    .si-btn{border:none;background:var(--ink);color:#fff}
    .si-btn:hover{background:var(--ink-2)}
    .si-btn i{color:var(--chalk)}
    .si-clear{border:1px solid var(--line);background:#fff;color:var(--muted)}
    .si-clear:hover{color:var(--ink);border-color:#b9b29c}

    /* ---------- Table ---------- */
    .si-wrap{overflow-x:auto}
    .si-table{width:100%;min-width:900px;border-collapse:collapse}
    .si-table th{padding:12px 22px;background:#faf6ea;border-bottom:1px solid var(--line);color:var(--muted);font-size:12px;font-weight:600;text-align:left;white-space:nowrap}
    .si-table td{padding:14px 22px;border-bottom:1px solid #f0ebdc;color:var(--text);font-size:13px;vertical-align:middle;white-space:nowrap}
    .si-table tbody tr:hover td{background:#fffcf3}
    .si-table tbody tr:last-child td{border-bottom:none}

    .who{display:flex;align-items:center;gap:12px}
    .avatar,.avatar-ph{width:42px;height:42px;border-radius:50%;flex-shrink:0}
    .avatar{object-fit:cover;box-shadow:0 0 0 2px #fff,0 0 0 3px var(--line)}
    .avatar-ph{display:flex;align-items:center;justify-content:center;background:var(--accent-soft);color:var(--accent);font-family:var(--serif);font-weight:700;font-size:16px;box-shadow:0 0 0 2px #fff,0 0 0 3px var(--line)}
    .who-name{font-weight:600;color:var(--ink)}
    .who-id{margin-top:2px;font-size:12px;color:var(--muted)}

    .chip{display:inline-flex;padding:4px 11px;border-radius:6px;background:var(--accent-soft);color:var(--accent);font-size:12px;font-weight:600}
    .badge-st{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:999px;font-size:12px;font-weight:600}
    .badge-st::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}
    .st-active{background:#e3f4ea;color:#17703b}
    .st-inactive{background:#fde8e4;color:#b3321d}
    .muted{color:var(--muted)}

    .acts{display:flex;align-items:center;gap:6px}
    .acts form{display:inline;margin:0}
    .act{width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--line);border-radius:8px;background:#fff;color:var(--muted);font-size:15px;cursor:pointer;transition:all .15s}
    .act:hover{background:var(--accent-soft);border-color:#c5d6ea;color:var(--accent)}
    .act.del:hover{background:#fde8e4;border-color:#f5c2bb;color:#b3321d}

    .si-empty{padding:60px 20px;text-align:center}
    .si-empty i{display:block;font-size:36px;color:#a3b0c2;margin-bottom:10px}
    .si-empty strong{display:block;font-family:var(--serif);font-size:18px;color:var(--ink);margin-bottom:4px}
    .si-empty span{font-size:13px;color:var(--muted)}
    .si-empty .si-add{margin-top:16px}
    .si-pages{padding:14px 22px;border-top:1px dashed #d8d1bf}

    @media (max-width:1250px){
        .si-filters{grid-template-columns:repeat(3,minmax(0,1fr))}
        .si-search{grid-column:1/-1}
        .si-btn,.si-clear{width:100%}
    }
    @media (max-width:1100px){.si-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media (max-width:650px){
        .si-kpis,.si-filters{grid-template-columns:1fr}
        .kpi{margin-top:18px}
        .si-add{width:100%;justify-content:center}
    }
</style>

<div class="si">

    @if (session('success'))
        <div class="alert-ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-err"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="si-head">
        <div>
            <h1>Other staff</h1>
            <p>Manage librarians, accountants, receptionists, drivers and other staff members.</p>
        </div>
        
    </div>

    {{-- Stats --}}
    <div class="si-kpis">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Total</span>
            <div>
                <div class="kpi-value">{{ number_format($totalStaff) }}</div>
                <div class="kpi-note">Total staff</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
        </div>

        <div class="paper kpi c-green">
            <span class="kpi-tab">Active</span>
            <div>
                <div class="kpi-value">{{ number_format($activeStaff) }}</div>
                <div class="kpi-note">Active staff</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-person-check-fill"></i></div>
        </div>

        <div class="paper kpi c-red">
            <span class="kpi-tab">Inactive</span>
            <div>
                <div class="kpi-value">{{ number_format($inactiveStaff) }}</div>
                <div class="kpi-note">Inactive staff</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-person-x-fill"></i></div>
        </div>

        <div class="paper kpi c-violet">
            <span class="kpi-tab">Library</span>
            <div>
                <div class="kpi-value">{{ number_format($librarians) }}</div>
                <div class="kpi-note">Active librarians</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-book-half"></i></div>
        </div>
    </div>

    {{-- Staff list --}}
    <section class="paper si-card">

        <div class="si-head2">
            <h3>Staff members<small>All non-teaching staff registered in the school</small></h3>
            <span class="count-pill">{{ method_exists($staff, 'total') ? $staff->total() : $staff->count() }} records</span>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.other-staff.index') }}" class="si-filters">
            <div class="si-search">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, ID, phone...">
            </div>

            <select name="designation" aria-label="Designation">
                <option value="">All designations</option>
                @foreach ($designations as $designation)
                    <option value="{{ $designation }}" @selected(request('designation') === $designation)>{{ $designation }}</option>
                @endforeach
            </select>

            <select name="status" aria-label="Status">
                <option value="">All status</option>
                <option value="Active" @selected(request('status') === 'Active')>Active</option>
                <option value="Inactive" @selected(request('status') === 'Inactive')>Inactive</option>
            </select>

            <button type="submit" class="si-btn"><i class="bi bi-search"></i> Search</button>

            @if (request()->hasAny(['search','designation','status']))
                <a href="{{ route('admin.other-staff.index') }}" class="si-clear">Clear</a>
            @endif
        </form>

        @if ($staff->count())

            <div class="si-wrap">
                <table class="si-table">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Phone</th>
                            <th>Joining date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($staff as $member)
                            <tr>
                                <td>
                                    <div class="who">
                                        @if ($member->profile_photo)
                                            <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}" class="avatar">
                                        @else
                                            <div class="avatar-ph">{{ strtoupper(mb_substr($member->name, 0, 1)) }}</div>
                                        @endif

                                        <div>
                                            <div class="who-name">{{ $member->name }}</div>
                                            <div class="who-id">{{ $member->staff_id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td><span class="chip">{{ $member->designation }}</span></td>

                                <td>{{ $member->department ?: '—' }}</td>

                                <td>{{ $member->phone ?: '—' }}</td>

                                <td>
                                    {{ $member->joining_date ? \Carbon\Carbon::parse($member->joining_date)->format('d M Y') : '—' }}
                                </td>

                                <td>
                                    <span class="badge-st {{ $member->status === 'Active' ? 'st-active' : 'st-inactive' }}">{{ $member->status }}</span>
                                </td>

                                <td>
                                    <div class="acts">
                                        <a href="{{ route('admin.other-staff.show', $member) }}" class="act" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.other-staff.edit', $member) }}" class="act" title="Edit"><i class="bi bi-pencil"></i></a>

                                        <form action="{{ route('admin.other-staff.destroy', $member) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="act del" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($staff->hasPages())
                <div class="si-pages">{{ $staff->links() }}</div>
            @endif

        @else

            <div class="si-empty">
                <i class="bi bi-people"></i>
                <strong>No staff members found</strong>
                <span>Add your first non-teaching staff member to get started.</span>
                <div><a href="{{ route('admin.other-staff.create') }}" class="si-add"><i class="bi bi-person-plus-fill"></i> Add staff</a></div>
            </div>

        @endif
    </section>

</div>

@endsection