@extends('layouts.app')

@section('title', 'Notices')
@section('page-title', 'Notices')

@section('content')

<style>
    .ni{max-width:1500px;margin:0 auto}

    .ni-head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin-bottom:20px;flex-wrap:wrap}
    .ni-head h1{font-family:var(--serif);font-size:26px;font-weight:700;color:var(--ink);margin-bottom:4px}
    .ni-head p{color:var(--muted);font-size:14px}
    .ni-create{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:8px;background:var(--ink);color:#fff;font-size:14px;font-weight:600;transition:background .15s,transform .15s}
    .ni-create i{color:var(--chalk)}
    .ni-create:hover{background:var(--ink-2);color:#fff;transform:translateY(-1px)}

    .alert-ok,.alert-err{display:flex;align-items:center;gap:10px;margin-bottom:16px;padding:12px 15px;border-radius:9px;font-size:13.5px;font-weight:500}
    .alert-ok{background:#e3f4ea;border:1px solid #bfe3cd;color:#17703b}
    .alert-err{background:#fdecea;border:1px solid #f5c2bb;color:#b3321d}

    /* stats */
    .ni-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:20px}
    .ni-stat{--c:#2b5d8a;display:flex;align-items:center;gap:14px;padding:18px 20px;background:#fff;border:1px solid var(--line);border-top:4px solid var(--c);border-radius:10px;transition:transform .2s,box-shadow .2s}
    .ni-stat:hover{transform:translateY(-4px);box-shadow:0 12px 22px rgba(29,58,51,.12)}
    .ni-stat .ic{width:44px;height:44px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:19px;color:var(--c);background:color-mix(in srgb,var(--c) 14%,#fff);flex-shrink:0}
    .ni-stat strong{display:block;font-family:var(--serif);font-size:28px;line-height:1;color:var(--ink)}
    .ni-stat span{font-size:13px;color:var(--muted)}
    .s-pub{--c:#3a9d6b}.s-draft{--c:#d58a0b}.s-exp{--c:#e4572e}

    /* card */
    .ni-card{background:#fff;border:1px solid var(--line);border-radius:10px;overflow:hidden}

    .ni-filters{display:grid;grid-template-columns:minmax(0,1fr) 170px 150px 150px auto auto;gap:10px;align-items:center;padding:16px 20px;border-bottom:1px dashed #d8d1bf;background:#fffdf8}
    .ni-search{position:relative}
    .ni-search i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:14px}
    .ni-filters input,.ni-filters select{width:100%;height:42px;font:inherit;font-size:13px;color:var(--text);background:#fff;border:1px solid var(--line);border-radius:8px;outline:none;transition:border-color .15s,box-shadow .15s}
    .ni-filters input{padding:0 12px 0 36px}
    .ni-filters select{padding:0 10px}
    .ni-filters input:focus,.ni-filters select:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(242,182,50,.4)}
    .ni-btn,.ni-clear{height:42px;padding:0 18px;display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;font:inherit;font-size:13px;font-weight:600;white-space:nowrap;cursor:pointer}
    .ni-btn{border:none;background:var(--ink);color:#fff}
    .ni-btn:hover{background:var(--ink-2)}
    .ni-clear{border:1px solid var(--line);background:#fff;color:var(--muted)}
    .ni-clear:hover{color:var(--ink);border-color:#b9b29c}

    /* table */
    .ni-wrap{overflow-x:auto}
    .ni-table{width:100%;min-width:900px;border-collapse:collapse}
    .ni-table th{padding:12px 20px;background:#faf6ea;border-bottom:1px solid var(--line);color:var(--muted);font-size:12px;font-weight:600;text-align:left}
    .ni-table td{padding:14px 20px;border-bottom:1px solid #f0ebdc;color:var(--text);font-size:13px;vertical-align:middle}
    .ni-table tbody tr:hover td{background:#fffcf3}
    .ni-table tbody tr:last-child td{border-bottom:none}

    .n-cell{display:flex;align-items:center;gap:12px}
    .n-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:var(--accent-soft);color:var(--accent);font-size:16px;flex-shrink:0}
    .n-name{max-width:300px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;font-weight:600;color:var(--ink)}
    .n-sub{margin-top:2px;font-size:11.5px;color:var(--muted)}

    .badge-cat{display:inline-flex;padding:4px 10px;border-radius:6px;background:var(--accent-soft);color:var(--accent);font-size:12px;font-weight:600}
    .prio{display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600}
    .prio::before{content:"";width:8px;height:8px;border-radius:50%;background:currentColor}
    .prio-normal{color:#6a756f}.prio-important{color:#d58a0b}.prio-urgent{color:#e4572e}

    .badge-st{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:999px;font-size:12px;font-weight:600}
    .badge-st::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}
    .st-published{background:#e3f4ea;color:#17703b}
    .st-draft{background:#fdf0d4;color:#a8680a}
    .st-expired{background:#fde8e4;color:#b3321d}

    .acts{display:flex;align-items:center;gap:6px}
    .acts form{display:inline;margin:0}
    .act{width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--line);border-radius:8px;background:#fff;color:var(--muted);font-size:15px;cursor:pointer;transition:all .15s}
    .act:hover{background:var(--accent-soft);border-color:#c5d6ea;color:var(--accent)}
    .act.wa:hover{background:#e3f4ea;border-color:#bfe3cd;color:#17703b}
    .act.del:hover{background:#fde8e4;border-color:#f5c2bb;color:#b3321d}

    .ni-empty{padding:56px 20px;text-align:center}
    .ni-empty i{display:block;font-size:34px;color:#a3b0c2;margin-bottom:10px}
    .ni-empty strong{display:block;font-family:var(--serif);font-size:17px;color:var(--ink);margin-bottom:4px}
    .ni-empty span{font-size:13px;color:var(--muted)}

    .ni-foot{display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap;padding:14px 20px;border-top:1px dashed #d8d1bf;font-size:12.5px;color:var(--muted)}

    @media (max-width:1200px){
        .ni-filters{grid-template-columns:repeat(3,minmax(0,1fr))}
        .ni-search{grid-column:1/-1}
        .ni-btn,.ni-clear{width:100%}
    }
    @media (max-width:950px){.ni-stats{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:650px){
        .ni-stats,.ni-filters{grid-template-columns:1fr}
        .ni-create{width:100%;justify-content:center}
    }
</style>

<div class="ni">

    @if (session('success'))
        <div class="alert-ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-err"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="ni-head">
        <div>
            <h1>Notice management</h1>
            <p>Manage school announcements, notices and important updates.</p>
        </div>
        <a href="{{ route('admin.notices.create') }}" class="ni-create">
            <i class="bi bi-plus-lg"></i> Create notice
        </a>
    </div>

    {{-- Stats --}}
    <div class="ni-stats">
        <div class="ni-stat paper">
            <div class="ic"><i class="bi bi-journal-text"></i></div>
            <div><strong>{{ $total }}</strong><span>Total notices</span></div>
        </div>
        <div class="ni-stat s-pub paper">
            <div class="ic"><i class="bi bi-check2-circle"></i></div>
            <div><strong>{{ $published }}</strong><span>Published</span></div>
        </div>
        <div class="ni-stat s-draft paper">
            <div class="ic"><i class="bi bi-pencil-square"></i></div>
            <div><strong>{{ $draft }}</strong><span>Drafts</span></div>
        </div>
        <div class="ni-stat s-exp paper">
            <div class="ic"><i class="bi bi-hourglass-bottom"></i></div>
            <div><strong>{{ $expired }}</strong><span>Expired</span></div>
        </div>
    </div>

    {{-- Notices card --}}
    <div class="ni-card paper">

        {{-- Search and filters --}}
        <form method="GET" action="{{ route('admin.notices.index') }}" class="ni-filters">
            <div class="ni-search">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notices...">
            </div>

            <select name="category" aria-label="Category">
                <option value="">All categories</option>
                @foreach (['Academic','Events','Examination','Holiday','General'] as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>

            <select name="status" aria-label="Status">
                <option value="">All status</option>
                @foreach (['Published','Draft','Expired'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>

            <select name="date_filter" aria-label="Date">
                <option value="">All dates</option>
                <option value="today" @selected(request('date_filter') === 'today')>Today</option>
                <option value="week" @selected(request('date_filter') === 'week')>This week</option>
                <option value="month" @selected(request('date_filter') === 'month')>This month</option>
            </select>

            <button type="submit" class="ni-btn"><i class="bi bi-funnel"></i> Filter</button>

            @if (request()->hasAny(['search','category','status','date_filter']))
                <a href="{{ route('admin.notices.index') }}" class="ni-clear">Clear</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="ni-wrap">
            <table class="ni-table">
                <thead>
                    <tr>
                        <th>Notice</th>
                        <th>Category</th>
                        <th>Publish date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($notices as $notice)
                        <tr>
                            <td>
                                <div class="n-cell">
                                    <div class="n-icon"><i class="bi bi-pin-angle-fill"></i></div>
                                    <div>
                                        <div class="n-name">{{ $notice->title }}</div>
                                        <div class="n-sub">Created by Admin</div>
                                    </div>
                                </div>
                            </td>

                            <td><span class="badge-cat">{{ $notice->category }}</span></td>

                            <td>
                                {{ $notice->publish_date ? \Carbon\Carbon::parse($notice->publish_date)->format('d M Y') : '—' }}
                            </td>

                            <td>
                                <span class="prio prio-{{ strtolower($notice->priority) }}">{{ $notice->priority }}</span>
                            </td>

                            <td>
                                <span class="badge-st st-{{ strtolower($notice->status) }}">{{ $notice->status }}</span>
                            </td>

                            <td>
                                <div class="acts">
                                    <a href="{{ route('admin.notices.show', $notice) }}" class="act" title="View"><i class="bi bi-eye"></i></a>

                                    <a href="{{ route('admin.notices.edit', $notice) }}" class="act" title="Edit"><i class="bi bi-pencil"></i></a>

                                    @if ($notice->status === 'Published')
                                        <a href="{{ route('admin.notices.whatsapp', $notice) }}" target="_blank" rel="noopener" class="act wa" title="Share on WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="act del" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ni-empty">
                                    <i class="bi bi-inbox"></i>
                                    <strong>No notices found</strong>
                                    <span>Try changing your search or filters, or create a new notice.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="ni-foot">
            <span>
                Showing {{ $notices->firstItem() ?? 0 }} to {{ $notices->lastItem() ?? 0 }}
                of {{ $notices->total() }} notices
            </span>

            @if ($notices->hasPages())
                <div>{{ $notices->links() }}</div>
            @endif
        </div>
    </div>
</div>

@endsection