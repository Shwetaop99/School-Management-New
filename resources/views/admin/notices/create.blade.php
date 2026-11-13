@extends('layouts.app')

@section('title', 'Create Notice')
@section('page-title', 'Create Notice')

@section('content')

<style>
    .nf{max-width:960px;margin:0 auto}
    .nf-head{margin-bottom:20px}
    .nf-head h1{font-family:var(--serif);font-size:26px;font-weight:700;color:var(--ink);margin-bottom:4px}
    .nf-head p{color:var(--muted);font-size:14px}

    .nf-card{position:relative;background:#fff;border:1px solid var(--line);border-radius:12px;padding:30px 32px 26px;overflow:hidden}
    .nf-card::before{content:"";position:absolute;top:0;left:0;right:0;height:4px;
        background:linear-gradient(90deg,var(--red) 0 25%,var(--chalk) 25% 50%,var(--green) 50% 75%,var(--accent) 75% 100%)}

    .nf-section{margin-bottom:26px}
    .nf-title{font-family:var(--serif);font-size:16px;font-weight:700;color:var(--ink);margin-bottom:16px;padding-bottom:10px;border-bottom:1px dashed #d8d1bf}
    .nf-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
    .nf-group{display:flex;flex-direction:column}
    .nf-group.full{grid-column:1/-1}
    .nf-group label{font-size:13px;font-weight:600;color:var(--ink);margin-bottom:6px}
    .req{color:#c0392b}

    .nf-input{width:100%;height:46px;padding:0 14px;font:inherit;font-size:14px;color:var(--text);background:#fff;border:1px solid var(--line);border-radius:8px;outline:none;transition:border-color .15s,box-shadow .15s}
    textarea.nf-input{height:160px;padding:12px 14px;resize:vertical;line-height:1.6}
    .nf-input::placeholder{color:#a3a89f}
    .nf-input:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(242,182,50,.4)}
    .nf-input.invalid{border-color:#c0392b}
    .nf-help{margin-top:6px;font-size:12px;color:var(--muted)}
    .nf-error{margin-top:6px;font-size:12.5px;color:#b3321d}

    /* priority chips */
    .chips{display:flex;gap:10px;flex-wrap:wrap}
    .chip{position:relative}
    .chip input{position:absolute;opacity:0}
    .chip label{--c:#2b5d8a;display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border:1.5px solid var(--line);border-radius:999px;background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;transition:all .15s}
    .chip label::before{content:"";width:9px;height:9px;border-radius:50%;background:var(--c)}
    .chip input:checked + label{border-color:var(--c);background:color-mix(in srgb,var(--c) 12%,#fff);color:var(--ink)}
    .chip input:focus-visible + label{outline:2px solid var(--chalk);outline-offset:2px}
    .chip.important label{--c:#d58a0b}.chip.urgent label{--c:#e4572e}

    /* attachment */
    .drop{display:flex;flex-direction:column;align-items:center;gap:4px;padding:24px;text-align:center;border:1.5px dashed #cfc8b6;border-radius:10px;background:#fffdf6;cursor:pointer;transition:border-color .15s,background .15s}
    .drop:hover,.drop:focus-within{border-color:var(--ink);background:#fff9e8}
    .drop i{font-size:26px;color:var(--accent)}
    .drop strong{font-size:14px;color:var(--ink)}
    .drop span{font-size:12px;color:var(--muted)}
    .drop input{position:absolute;width:1px;height:1px;opacity:0}
    .drop .picked{margin-top:6px;font-size:13px;font-weight:600;color:var(--green)}

    .nf-actions{display:flex;justify-content:flex-end;gap:10px;padding-top:20px;border-top:1px dashed #d8d1bf}
    .nf-btn{height:44px;padding:0 22px;display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;border-radius:8px;font:inherit;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;transition:background .15s,transform .15s}
    .nf-cancel{background:#f1ede1;color:var(--muted)}
    .nf-cancel:hover{background:#e8e3d3;color:var(--ink)}
    .nf-save{background:var(--ink);color:#fff}
    .nf-save:hover{background:var(--ink-2);color:#fff;transform:translateY(-1px)}
    .nf-save i{color:var(--chalk)}

    @media (max-width:700px){
        .nf-card{padding:24px 18px 20px}
        .nf-grid{grid-template-columns:1fr}
        .nf-group.full{grid-column:auto}
        .nf-actions{flex-direction:column-reverse}
        .nf-btn{width:100%}
    }
</style>

<div class="nf">

    <div class="nf-head">
        <h1>Create notice</h1>
        <p>Write and publish a new school announcement.</p>
    </div>

    <div class="nf-card">
        <form method="POST" action="{{ route('admin.notices.store') }}" enctype="multipart/form-data" id="noticeForm">
            @csrf

            {{-- Notice information --}}
            <div class="nf-section">
                <div class="nf-title">Notice information</div>

                <div class="nf-grid">
                    <div class="nf-group full">
                        <label for="title">Notice title <span class="req">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="nf-input @error('title') invalid @enderror"
                               placeholder="Enter notice title" required>
                        @error('title')<div class="nf-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="nf-group full">
                        <label for="content">Notice content <span class="req">*</span></label>
                        <textarea id="content" name="content"
                                  class="nf-input @error('content') invalid @enderror"
                                  placeholder="Write the notice content here..." required>{{ old('content') }}</textarea>
                        <div class="nf-help">Give clear details for students, parents and faculty.</div>
                        @error('content')<div class="nf-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="nf-group">
                        <label for="category">Category <span class="req">*</span></label>
                        <select id="category" name="category" class="nf-input">
                            @foreach (['General','Academic','Events','Examination','Holiday'] as $option)
                                <option value="{{ $option }}" @selected(old('category') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="nf-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="nf-group">
                        <label for="status">Status <span class="req">*</span></label>
                        <select id="status" name="status" class="nf-input">
                            @foreach (['Draft','Published'] as $option)
                                <option value="{{ $option }}" @selected(old('status') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="nf-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Schedule --}}
            <div class="nf-section">
                <div class="nf-title">Publication schedule</div>

                <div class="nf-grid">
                    <div class="nf-group">
                        <label for="publish_date">Publish date</label>
                        <input type="date" id="publish_date" name="publish_date" value="{{ old('publish_date') }}" class="nf-input">
                        @error('publish_date')<div class="nf-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="nf-group">
                        <label for="expiry_date">Expiry date</label>
                        <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" class="nf-input">
                        @error('expiry_date')<div class="nf-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Priority --}}
            <div class="nf-section">
                <div class="nf-title">Priority</div>

                @php $priority = old('priority', 'Normal'); @endphp

                <div class="chips" role="radiogroup" aria-label="Notice priority">
                    <div class="chip normal">
                        <input type="radio" name="priority" id="priority-normal" value="Normal" @checked($priority === 'Normal')>
                        <label for="priority-normal">Normal</label>
                    </div>
                    <div class="chip important">
                        <input type="radio" name="priority" id="priority-important" value="Important" @checked($priority === 'Important')>
                        <label for="priority-important">Important</label>
                    </div>
                    <div class="chip urgent">
                        <input type="radio" name="priority" id="priority-urgent" value="Urgent" @checked($priority === 'Urgent')>
                        <label for="priority-urgent">Urgent</label>
                    </div>
                </div>
            </div>

            {{-- Attachment --}}
            <div class="nf-section">
                <div class="nf-title">Attachment</div>

                <label class="drop" for="attachment">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <strong>Attach a file</strong>
                    <span>PDF, DOC, DOCX, JPG or PNG</span>
                    <span class="picked" id="pickedFile"></span>
                    <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </label>
                @error('attachment')<div class="nf-error">{{ $message }}</div>@enderror
            </div>

            {{-- Actions --}}
            <div class="nf-actions">
                <a href="{{ route('admin.notices.index') }}" class="nf-btn nf-cancel">Cancel</a>
                <button type="submit" class="nf-btn nf-save" id="publishNoticeButton">
                    <i class="bi bi-pin-angle-fill"></i> Save notice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show the chosen file name
    document.getElementById('attachment').addEventListener('change', function () {
        document.getElementById('pickedFile').textContent = this.files.length ? this.files[0].name : '';
    });

    // Confirm only when publishing (the layout adds the loading spinner on submit)
    document.getElementById('noticeForm').addEventListener('submit', function (event) {
        if (document.getElementById('status').value !== 'Published') return;

        const ok = confirm('Publish this notice? After it is saved, WhatsApp will open with a pre-filled notice message.');
        if (!ok) event.preventDefault();
    });
</script>

@endsection