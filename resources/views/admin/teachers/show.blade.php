@extends('layouts.app')

@section('title', 'Teacher Profile')
@section('page-title', 'Teacher Profile')

@section('content')

<style>
    .profile-page{max-width:1200px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* Hero */
    .profile-hero{
        margin-bottom:22px;
        overflow:hidden;
        position:relative;
    }
    .profile-hero-top{
        height:110px;
        background:var(--ink);
        position:relative;
        background-image:radial-gradient(ellipse at 20% 0,rgba(255,255,255,.09),transparent 60%);
    }
    .profile-hero-body{
        display:grid;
        grid-template-columns:120px 1fr auto;
        gap:22px;
        align-items:center;
        padding:0 28px 24px;
    }
    .profile-avatar-wrap{
        margin-top:-50px;
        width:110px;height:110px;
        border-radius:50%;
        background:#fff;
        display:flex;align-items:center;justify-content:center;
        box-shadow:0 6px 18px rgba(0,0,0,.12);
        position:relative;z-index:2;
    }
    .profile-avatar,.profile-avatar-ph{
        width:96px;height:96px;border-radius:50%;
        object-fit:cover;
    }
    .profile-avatar-ph{
        display:flex;align-items:center;justify-content:center;
        background:color-mix(in srgb,#2b5d8a 14%,#fff);
        color:#2b5d8a;font-size:36px;
    }
    .profile-basic{padding-top:18px}
    .profile-basic h1{
        font-family:var(--serif);
        font-size:24px;font-weight:700;
        color:var(--ink);margin:0 0 4px;
    }
    .profile-basic .sub{
        margin:0 0 12px;
        color:var(--muted);font-size:13px;
    }
    .profile-meta{
        display:flex;flex-wrap:wrap;gap:8px 16px;
    }
    .meta-item{
        display:inline-flex;align-items:center;gap:6px;
        font-size:12.5px;color:var(--muted);
        cursor:pointer;
    }
    .meta-item i{color:#2b5d8a}
    .meta-item:hover{color:var(--ink)}

    .status-badge{
        display:inline-flex;align-items:center;gap:6px;
        padding:6px 12px;border-radius:20px;
        font-size:12px;font-weight:700;
    }
    .status-badge.active{background:color-mix(in srgb,#3a9d6b 12%,#fff);color:#3a9d6b}
    .status-badge.inactive{background:color-mix(in srgb,#e4572e 12%,#fff);color:#e4572e}
    .status-dot{width:6px;height:6px;border-radius:50%;background:currentColor}

    /* Header actions */
    .page-head{
        display:flex;align-items:center;justify-content:space-between;
        gap:16px;margin-bottom:22px;flex-wrap:wrap;
    }
    .page-head h1{
        font-family:var(--serif);
        font-size:24px;font-weight:700;
        color:var(--ink);margin:0 0 4px;
    }
    .page-head p{margin:0;color:var(--muted);font-size:13.5px}
    .head-actions{display:flex;gap:10px;flex-wrap:wrap}

    .btn-solid,.btn-line{
        display:inline-flex;align-items:center;gap:8px;
        padding:9px 16px;border-radius:8px;
        font-size:13.5px;font-weight:500;
        text-decoration:none;cursor:pointer;border:1px solid transparent;
    }
    .btn-solid{background:var(--ink);color:#fff;border-color:var(--ink)}
    .btn-solid:hover{background:var(--ink-2);color:#fff}
    .btn-line{background:#fff;border-color:#cfc8b6;color:var(--text)}
    .btn-line:hover{border-color:var(--ink);color:var(--ink)}
    .btn-danger{
        background:color-mix(in srgb,#e4572e 12%,#fff);
        color:#e4572e;border:1px solid transparent;
        padding:9px 16px;border-radius:8px;
        font-size:13.5px;font-weight:600;cursor:pointer;
    }
    .btn-danger:hover{background:#e4572e;color:#fff}

    /* Info cards */
    .info-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:18px;margin-bottom:22px;
    }
    .info-card{overflow:hidden}
    .info-card.full{grid-column:1 / -1}
    .info-head{
        display:flex;align-items:center;gap:12px;
        padding:14px 20px;
        border-bottom:1px dashed #d8d1bf;
    }
    .info-icon{
        width:36px;height:36px;border-radius:9px;
        display:flex;align-items:center;justify-content:center;
        background:color-mix(in srgb,#2b5d8a 12%,#fff);
        color:#2b5d8a;font-size:15px;
    }
    .info-head h3{
        margin:0;
        font-family:var(--serif);
        font-size:15px;font-weight:700;color:var(--ink);
    }
    .info-head p{margin:2px 0 0;font-size:11.5px;color:var(--muted)}
    .info-body{padding:18px 20px}
    .detail-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:16px 20px;
    }
    .detail.full{grid-column:1 / -1}
    .detail-label{
        display:block;margin-bottom:4px;
        font-size:11px;font-weight:700;
        color:var(--muted);text-transform:uppercase;letter-spacing:.3px;
    }
    .detail-value{
        font-size:13.5px;font-weight:600;color:var(--ink);
        word-break:break-word;
    }
    .detail-value.empty{color:var(--muted);font-weight:400}
    .detail-value i{color:#2b5d8a;margin-right:6px}

    /* Delete card */
    .delete-card{
        border-color:#f0d0c8;
        margin-bottom:22px;
    }
    .delete-body{
        display:flex;align-items:center;justify-content:space-between;
        gap:16px;padding:18px 22px;flex-wrap:wrap;
    }
    .delete-body h4{
        margin:0 0 4px;
        font-family:var(--serif);
        font-size:15px;font-weight:700;color:#e4572e;
    }
    .delete-body p{margin:0;font-size:12.5px;color:var(--muted)}

    /* Toast */
    .copy-toast{
        position:fixed;right:24px;bottom:24px;
        display:flex;align-items:center;gap:8px;
        padding:11px 16px;border-radius:8px;
        background:var(--ink);color:#fff;
        font-size:12.5px;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.18);
        opacity:0;visibility:hidden;
        transform:translateY(12px);
        transition:all .25s ease;z-index:9999;
    }
    .copy-toast.show{opacity:1;visibility:visible;transform:translateY(0)}
    .copy-toast i{color:#7dce9a}

    @media (max-width:900px){
        .profile-hero-body{grid-template-columns:100px 1fr}
        .profile-status{grid-column:2}
        .info-grid{grid-template-columns:1fr}
        .info-card.full{grid-column:auto}
    }
    @media (max-width:650px){
        .profile-hero-body{
            grid-template-columns:1fr;
            justify-items:center;text-align:center;
        }
        .profile-meta{justify-content:center}
        .profile-status{grid-column:auto}
        .detail-grid{grid-template-columns:1fr}
        .page-head{flex-direction:column;align-items:flex-start}
        .head-actions{width:100%}
        .btn-solid,.btn-line{flex:1;justify-content:center}
        .delete-body{flex-direction:column;align-items:flex-start}
        .btn-danger{width:100%}
    }
</style>

<div class="profile-page">

    {{-- Page header --}}
    <div class="page-head">
        <div>
            <h1>Teacher Profile</h1>
            <p>View teacher information and professional details</p>
        </div>
        <div class="head-actions">
            <a href="{{ route('admin.teachers.index') }}" class="btn-line">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn-solid">
                <i class="bi bi-pencil-square"></i> Edit Teacher
            </a>
        </div>
    </div>

    {{-- Profile hero --}}
    <section class="paper profile-hero">
        <div class="profile-hero-top"></div>
        <div class="profile-hero-body">
            <div class="profile-avatar-wrap">
                @if($teacher->profile_image)
                    <img src="{{ $teacher->profile_image }}"
                         alt="{{ $teacher->first_name }}"
                         class="profile-avatar">
                @else
                    <div class="profile-avatar-ph">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif
            </div>

            <div class="profile-basic">
                <h1>{{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
                <p class="sub">{{ $teacher->qualification ?? 'Teacher' }}</p>
                <div class="profile-meta">
                    <span class="meta-item copyable" data-copy="{{ $teacher->teacher_id }}">
                        <i class="bi bi-person-badge"></i>
                        ID: {{ $teacher->teacher_id }}
                    </span>
                    <span class="meta-item copyable" data-copy="{{ $teacher->email }}">
                        <i class="bi bi-envelope"></i>
                        {{ $teacher->email }}
                    </span>
                </div>
            </div>

            <div class="profile-status">
                @if(strtolower($teacher->status ?? '') === 'active')
                    <span class="status-badge active">
                        <span class="status-dot"></span> Active
                    </span>
                @else
                    <span class="status-badge inactive">
                        <span class="status-dot"></span> Inactive
                    </span>
                @endif
            </div>
        </div>
    </section>

    {{-- Info cards --}}
    <div class="info-grid">

        {{-- Personal --}}
        <section class="paper info-card">
            <div class="info-head">
                <div class="info-icon"><i class="bi bi-person-fill"></i></div>
                <div>
                    <h3>Personal Information</h3>
                    <p>Basic personal details</p>
                </div>
            </div>
            <div class="info-body">
                <div class="detail-grid">
                    <div class="detail">
                        <span class="detail-label">First Name</span>
                        <div class="detail-value">{{ $teacher->first_name }}</div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Last Name</span>
                        <div class="detail-value">{{ $teacher->last_name }}</div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Date of Birth</span>
                        <div class="detail-value {{ !$teacher->date_of_birth ? 'empty' : '' }}">
                            {{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : '—' }}
                        </div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Gender</span>
                        <div class="detail-value {{ !$teacher->gender ? 'empty' : '' }}">
                            {{ $teacher->gender ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Contact --}}
        <section class="paper info-card">
            <div class="info-head">
                <div class="info-icon"><i class="bi bi-telephone-fill"></i></div>
                <div>
                    <h3>Contact Information</h3>
                    <p>Contact and address details</p>
                </div>
            </div>
            <div class="info-body">
                <div class="detail-grid">
                    <div class="detail">
                        <span class="detail-label">Email</span>
                        <div class="detail-value">
                            <i class="bi bi-envelope"></i>{{ $teacher->email }}
                        </div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Phone</span>
                        @if($teacher->phone)
                            <div class="detail-value copyable" data-copy="{{ $teacher->phone }}" style="cursor:pointer">
                                <i class="bi bi-telephone"></i>{{ $teacher->phone }}
                            </div>
                        @else
                            <div class="detail-value empty">—</div>
                        @endif
                    </div>
                    <div class="detail full">
                        <span class="detail-label">Address</span>
                        <div class="detail-value {{ !$teacher->address ? 'empty' : '' }}">
                            {{ $teacher->address ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Professional --}}
        <section class="paper info-card full">
            <div class="info-head">
                <div class="info-icon"><i class="bi bi-briefcase-fill"></i></div>
                <div>
                    <h3>Professional Information</h3>
                    <p>Employment and qualification details</p>
                </div>
            </div>
            <div class="info-body">
                <div class="detail-grid">
                    <div class="detail">
                        <span class="detail-label">Teacher ID</span>
                        <div class="detail-value copyable" data-copy="{{ $teacher->teacher_id }}" style="cursor:pointer">
                            <i class="bi bi-person-vcard"></i>{{ $teacher->teacher_id }}
                        </div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Qualification</span>
                        <div class="detail-value {{ !$teacher->qualification ? 'empty' : '' }}">
                            {{ $teacher->qualification ?? '—' }}
                        </div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Subject</span>
                        <div class="detail-value {{ !$teacher->subject ? 'empty' : '' }}">
                            {{ $teacher->subject ?? '—' }}
                        </div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Joining Date</span>
                        <div class="detail-value {{ !$teacher->joining_date ? 'empty' : '' }}">
                            {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : '—' }}
                        </div>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Status</span>
                        <div>
                            @if(strtolower($teacher->status ?? '') === 'active')
                                <span class="status-badge active">
                                    <span class="status-dot"></span> Active
                                </span>
                            @else
                                <span class="status-badge inactive">
                                    <span class="status-dot"></span> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Delete --}}
    <section class="paper delete-card">
        <div class="delete-body">
            <div>
                <h4>Delete Teacher</h4>
                <p>This action will permanently remove this teacher.</p>
            </div>
            <form action="{{ route('admin.teachers.destroy', $teacher) }}"
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="bi bi-trash3"></i> Delete Teacher
                </button>
            </form>
        </div>
    </section>
</div>

{{-- Copy toast --}}
<div class="copy-toast" id="copyToast">
    <i class="bi bi-check-circle-fill"></i>
    <span id="copyToastText">Copied successfully</span>
</div>

<script>
document.querySelectorAll('.copyable').forEach(function (el) {
    el.addEventListener('click', function () {
        const value = this.dataset.copy;
        if (!value) return;

        navigator.clipboard.writeText(value)
            .then(() => showToast('Copied successfully'))
            .catch(() => showToast('Unable to copy'));
    });
});

function showToast(msg) {
    const toast = document.getElementById('copyToast');
    const text = document.getElementById('copyToastText');
    if (!toast || !text) return;
    text.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 1800);
}
</script>

@endsection