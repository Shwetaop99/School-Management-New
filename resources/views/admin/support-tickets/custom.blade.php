@extends('layouts.app')

@section('title', 'Create Support Request')
@section('page-title', 'Create Support Request')

@section('content')
<div class="support-create-page">
    <div class="support-create-heading">
        <div>
            <a href="{{ route('support-tickets.index') }}" class="support-back-link">
                <i class="fas fa-arrow-left"></i> Back to Help &amp; Support
            </a>
            <div class="support-eyebrow"><i class="fas fa-headset"></i> SUPPORT &amp; COMPLAINTS</div>
            <h1>Create a support request</h1>
            <p>Tell the Super Admin support team what you need help with.</p>
        </div>
        <div class="support-heading-icon">
            <i class="fas fa-comments"></i>
        </div>
    </div>

    @if ($errors->any())
        <div class="support-alert" role="alert">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <strong>Please check the information you entered.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="support-create-layout">
        <main class="support-form-panel">
            <div class="support-panel-heading">
                <div class="support-panel-icon"><i class="fas fa-pen-to-square"></i></div>
                <div>
                    <h2>Request details</h2>
                    <p>Complete the form below and submit it to our support team.</p>
                </div>
            </div>

            <form action="{{ route('support-tickets.store') }}" method="POST" class="support-request-form">
                @csrf

                <div class="support-field">
                    <label for="subject">Subject <span>*</span></label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        value="{{ old('subject') }}"
                        maxlength="255"
                        placeholder="Briefly describe what you need help with"
                        required
                        class="@error('subject') is-invalid @enderror"
                    >
                    @error('subject')
                        <small class="support-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="support-form-grid">
                    <div class="support-field">
                        <label for="category">Category <span>*</span></label>
                        <select id="category" name="category" required class="@error('category') is-invalid @enderror">
                            <option value="">Select a category</option>
                            <option value="technical" @selected(old('category') === 'technical')>Technical Issues</option>
                            <option value="login" @selected(old('category') === 'login')>Login &amp; Security</option>
                            <option value="billing" @selected(old('category') === 'billing')>Fees &amp; Payments</option>
                            <option value="student_management" @selected(old('category') === 'student_management')>Student Management</option>
                            <option value="backup" @selected(old('category') === 'backup')>Backup &amp; Recovery</option>
                            <option value="general" @selected(old('category') === 'general')>General Queries</option>
                            <option value="complaint" @selected(old('category') === 'complaint')>Complaint</option>
                            <option value="other" @selected(old('category') === 'other')>Other</option>
                        </select>
                        @error('category')
                            <small class="support-field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="support-field">
                        <label for="priority">Priority <span>*</span></label>
                        <select id="priority" name="priority" required class="@error('priority') is-invalid @enderror">
                            <option value="low" @selected(old('priority', 'medium') === 'low')>Low</option>
                            <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                            <option value="high" @selected(old('priority') === 'high')>High</option>
                            <option value="urgent" @selected(old('priority') === 'urgent')>Urgent</option>
                        </select>
                        @error('priority')
                            <small class="support-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="support-field">
                    <label for="description">Description <span>*</span></label>
                    <textarea
                        id="description"
                        name="description"
                        rows="7"
                        minlength="10"
                        maxlength="10000"
                        placeholder="Explain the issue in detail. Include what happened and any steps you have already tried."
                        required
                        class="@error('description') is-invalid @enderror"
                    >{{ old('description') }}</textarea>
                    <div class="support-field-hint">Minimum 10 characters. Maximum 10,000 characters.</div>
                    @error('description')
                        <small class="support-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="support-form-actions">
                    <button type="submit" class="support-submit-btn">
                        <i class="fas fa-paper-plane"></i> Submit request
                    </button>
                    <a href="{{ route('support-tickets.index') }}" class="support-cancel-btn">
                        Cancel
                    </a>
                </div>
            </form>
        </main>

        <aside class="support-create-aside">
            <section class="support-tip-card">
                <div class="support-tip-icon"><i class="fas fa-lightbulb"></i></div>
                <h3>Helpful tip</h3>
                <p>Choose the category that best matches your issue and provide clear details. This helps the support team understand your request.</p>
            </section>

            <section class="support-tip-card support-contact-card">
                <div class="support-tip-icon"><i class="fas fa-comments"></i></div>
                <h3>Stay in touch</h3>
                <p>After submitting, you can open your request and continue the conversation with the Super Admin from the ticket page.</p>
                <a href="{{ route('support-tickets.index') }}">
                    View my support requests <i class="fas fa-arrow-right"></i>
                </a>
            </section>
        </aside>
    </div>
</div>

<style>
    .support-create-page {
        --sc-ink: var(--ink, #1d3a33);
        --sc-muted: var(--muted, #718078);
        --sc-line: var(--line, #e7e0d0);
        color: var(--text, #2c3e3a);
        padding: 4px 0 28px;
    }
    .support-create-page * { box-sizing: border-box; }
    .support-create-page a { text-decoration: none; }
    .support-create-heading {
        display: flex; align-items: center; justify-content: space-between;
        gap: 18px; margin-bottom: 20px;
    }
    .support-back-link {
        display: inline-flex; align-items: center; gap: 7px;
        color: var(--sc-muted); font-size: 12px; margin-bottom: 13px;
    }
    .support-back-link:hover { color: var(--sc-ink); }
    .support-eyebrow {
        color: var(--sc-muted); font-size: 10px; font-weight: 800;
        letter-spacing: 1.3px; margin-bottom: 6px;
    }
    .support-eyebrow i { color: #d99c20; margin-right: 5px; }
    .support-create-heading h1 {
        color: var(--sc-ink); font-family: var(--serif, Georgia, serif);
        font-size: 26px; font-weight: 700; margin: 0 0 5px;
    }
    .support-create-heading p { color: var(--sc-muted); font-size: 12.5px; margin: 0; }
    .support-heading-icon {
        display: flex; align-items: center; justify-content: center;
        width: 58px; height: 58px; border-radius: 15px;
        color: var(--sc-ink); background: #f7edcf; font-size: 23px; flex: 0 0 auto;
    }
    .support-alert {
        display: flex; align-items: flex-start; gap: 10px; padding: 12px 15px;
        margin-bottom: 17px; border: 1px solid #f0d1ca; border-radius: 9px;
        background: #fff1ee; color: #923f36; font-size: 12px;
    }
    .support-alert > i { margin-top: 2px; }
    .support-alert ul { margin: 5px 0 0; padding-left: 18px; }

    .support-create-layout {
        display: grid; grid-template-columns: minmax(0, 1fr) 260px;
        gap: 18px; align-items: start;
    }
    .support-form-panel, .support-tip-card {
        background: #fff; border: 1px solid var(--sc-line);
        border-radius: 10px; overflow: hidden;
    }
    .support-panel-heading {
        display: flex; align-items: center; gap: 12px;
        padding: 17px 20px; border-bottom: 1px dashed var(--sc-line);
    }
    .support-panel-icon, .support-tip-icon {
        display: flex; align-items: center; justify-content: center;
        width: 40px; height: 40px; border-radius: 11px;
        background: #f6efd9; color: var(--sc-ink); flex: 0 0 auto;
    }
    .support-panel-heading h2 {
        color: var(--sc-ink); font: 700 17px var(--serif, Georgia, serif); margin: 0;
    }
    .support-panel-heading p { color: var(--sc-muted); font-size: 11.5px; margin: 4px 0 0; }
    .support-request-form { padding: 20px; }
    .support-field { display: flex; flex-direction: column; gap: 7px; margin-bottom: 17px; }
    .support-field label { color: var(--sc-ink); font-size: 12px; font-weight: 700; }
    .support-field label span { color: #c45b42; }
    .support-field input, .support-field select, .support-field textarea {
        width: 100%; min-height: 41px; padding: 10px 12px;
        border: 1px solid #dcd5c5; border-radius: 8px;
        background: #fff; color: #2c3e3a; font: inherit; font-size: 12.5px;
        outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .support-field textarea { min-height: 145px; resize: vertical; line-height: 1.6; }
    .support-field input:focus, .support-field select:focus, .support-field textarea:focus {
        border-color: var(--sc-ink); box-shadow: 0 0 0 3px rgba(29,58,51,.08);
    }
    .support-field .is-invalid { border-color: #c65b50; }
    .support-field-hint { color: var(--sc-muted); font-size: 10.5px; }
    .support-field-error { color: #b33d35; font-size: 11px; }
    .support-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .support-form-actions {
        display: flex; align-items: center; gap: 10px;
        padding-top: 3px; margin-top: 2px;
    }
    .support-submit-btn, .support-cancel-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 39px; padding: 9px 15px; border-radius: 8px;
        font-size: 12px; font-weight: 700; cursor: pointer;
    }
    .support-submit-btn { border: 1px solid var(--sc-ink); background: var(--sc-ink); color: #fff; }
    .support-submit-btn:hover { background: #284d43; }
    .support-cancel-btn { border: 1px solid #dcd5c5; background: #fff; color: #596a62; }
    .support-cancel-btn:hover { background: #f7f5ef; color: var(--sc-ink); }

    .support-create-aside { display: grid; gap: 14px; }
    .support-tip-card { padding: 17px; }
    .support-tip-icon { margin-bottom: 11px; }
    .support-tip-card h3 { color: var(--sc-ink); font: 700 15px var(--serif, Georgia, serif); margin: 0 0 7px; }
    .support-tip-card p { color: var(--sc-muted); font-size: 11.5px; line-height: 1.65; margin: 0; }
    .support-contact-card a {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
        color: var(--sc-ink); font-size: 11px; font-weight: 800;
        padding-top: 12px; margin-top: 12px; border-top: 1px solid var(--sc-line);
    }
    @media (max-width: 900px) {
        .support-create-layout { grid-template-columns: minmax(0, 1fr); }
        .support-create-aside { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 600px) {
        .support-create-heading { align-items: flex-start; }
        .support-heading-icon { width: 46px; height: 46px; font-size: 18px; }
        .support-form-grid, .support-create-aside { grid-template-columns: 1fr; }
        .support-request-form, .support-panel-heading { padding: 15px; }
        .support-form-actions { flex-wrap: wrap; }
    }
</style>
@endsection
