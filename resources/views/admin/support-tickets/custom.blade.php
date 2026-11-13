@extends('layouts.app')

@section('title', 'Create Custom Query')
@section('page-title', 'Help & Support')

@section('content')

<div class="support-create">

    <div class="support-create__top">
        <a href="{{ route('admin.support-tickets.create') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Help & Support
        </a>

        <h2>Create custom query</h2>
        <p>Describe your issue if you cannot find a suitable predefined option.</p>
    </div>

    @if ($errors->any())
        <div class="support-alert">
            <strong>Please correct the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="paper support-panel">
        <div class="support-panel__head">
            <h3>Your query details</h3>
            <span>Custom ticket</span>
        </div>

        <form method="POST"
              action="{{ route('admin.support-tickets.store') }}"
              class="support-form">
            @csrf

            <div class="form-group">
                <label for="subject">Subject</label>
                <input id="subject"
                       type="text"
                       name="subject"
                       value="{{ old('subject') }}"
                       maxlength="255"
                       placeholder="Enter your issue or query"
                       required
                       class="input @error('subject') input--error @enderror">
                @error('subject')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category"
                            name="category"
                            required
                            class="input @error('category') input--error @enderror">
                        <option value="">Select category</option>
                        <option value="technical" @selected(old('category') === 'technical')>Technical Issues</option>
                        <option value="login" @selected(old('category') === 'login')>Login & Security</option>
                        <option value="billing" @selected(old('category') === 'billing')>Fees & Payments</option>
                        <option value="student_management" @selected(old('category') === 'student_management')>Student Management</option>
                        <option value="backup" @selected(old('category') === 'backup')>Backup & Recovery</option>
                        <option value="general" @selected(old('category') === 'general')>General Queries</option>
                        <option value="complaint" @selected(old('category') === 'complaint')>Complaint</option>
                        <option value="other" @selected(old('category') === 'other')>Other</option>
                    </select>
                    @error('category')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select id="priority"
                            name="priority"
                            required
                            class="input">
                        <option value="low" @selected(old('priority') === 'low')>Low</option>
                        <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                        <option value="high" @selected(old('priority') === 'high')>High</option>
                        <option value="urgent" @selected(old('priority') === 'urgent')>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Describe your issue</label>
                <textarea id="description"
                          name="description"
                          rows="7"
                          placeholder="Explain your problem or query in detail..."
                          required
                          class="input input--textarea @error('description') input--error @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="support-form__actions">
                <a href="{{ route('admin.support-tickets.create') }}" class="btn-line">
                    Cancel
                </a>
                <button type="submit" class="btn-solid">
                    <i class="fas fa-paper-plane"></i>
                    Submit query
                </button>
            </div>
        </form>
    </section>

</div>

<style>
    .support-create {
        max-width: 820px;
        margin: 0 auto;
        --serif: 'Fraunces', Georgia, serif;
        --sans: 'Public Sans', Arial, Helvetica, sans-serif;
        --ink: #1d3a33;
        --ink-2: #2a4a41;
        --text: #26302c;
        --muted: #6a756f;
        --line: #e6e0d2;
        --white: #fff;
        --red: #e4572e;
        font-family: var(--sans);
        color: var(--text);
    }

    .support-create__top {
        margin-bottom: 22px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: var(--muted);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
    }

    .back-link:hover {
        color: var(--ink);
    }

    .support-create__top h2 {
        margin: 0 0 6px;
        font-family: var(--serif);
        font-size: 26px;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.3px;
    }

    .support-create__top p {
        margin: 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .support-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border: 1px solid #f2d8d3;
        border-radius: 10px;
        background: #fff2ef;
        color: #b54f43;
        font-size: 13.5px;
    }

    .support-alert ul {
        margin: 8px 0 0;
        padding-left: 18px;
    }

    .paper {
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: 10px;
    }

    .support-panel__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 22px;
        border-bottom: 1px dashed #d8d1bf;
    }

    .support-panel__head h3 {
        margin: 0;
        font-family: var(--serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
    }

    .support-panel__head span {
        font-size: 12px;
        color: var(--muted);
    }

    .support-form {
        padding: 22px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
    }

    .input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #cfc8b6;
        border-radius: 8px;
        background: #fff;
        color: var(--text);
        font-family: var(--sans);
        font-size: 14px;
        line-height: 1.4;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .input:focus {
        outline: none;
        border-color: var(--ink);
        box-shadow: 0 0 0 3px rgba(29, 58, 51, .1);
    }

    .input--textarea {
        resize: vertical;
        min-height: 140px;
    }

    .input--error {
        border-color: #e8b4ab;
        background: #fffaf9;
    }

    .field-error {
        font-size: 12px;
        color: var(--red);
    }

    .support-form__actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
        padding-top: 18px;
        border-top: 1px dashed #d8d1bf;
    }

    .btn-solid,
    .btn-line {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        font-family: var(--sans);
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background .15s ease, border-color .15s ease, color .15s ease;
    }

    .btn-solid {
        border: 1px solid var(--ink);
        background: var(--ink);
        color: #fff;
    }

    .btn-solid:hover {
        background: var(--ink-2);
        color: #fff;
    }

    .btn-line {
        border: 1px solid #cfc8b6;
        background: #fff;
        color: var(--text);
    }

    .btn-line:hover {
        border-color: var(--ink);
        color: var(--ink);
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .support-form__actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn-solid,
        .btn-line {
            justify-content: center;
        }
    }
</style>

@endsection