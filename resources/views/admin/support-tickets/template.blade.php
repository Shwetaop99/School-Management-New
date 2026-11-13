@extends('layouts.app')

@section('title', 'Review Issue')
@section('page-title', 'Help & Support')

@section('content')

@php
    $priorityClass = match (strtolower((string) old('priority', $template->priority))) {
        'urgent', 'critical', 'high' => 'p-high',
        'medium', 'normal'           => 'p-medium',
        'low'                        => 'p-low',
        default                      => 'p-none',
    };
@endphp

<div class="review-page">

    <div class="review-page__top">
        <a href="{{ route('admin.support-tickets.create') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Help &amp; Support
        </a>

        <h2>Review issue</h2>
        <p>Review the suggested issue and customise it before submitting.</p>
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

    <section class="paper panel">
        <div class="panel-head">
            <div>
                <h3>Issue preview</h3>
                <span>From predefined support template</span>
            </div>
            <span class="priority {{ $priorityClass }}">
                {{ ucfirst(old('priority', $template->priority)) }} priority
            </span>
        </div>

        <form method="POST"
              action="{{ route('admin.support-tickets.store') }}"
              class="review-form">
            @csrf

            <input type="hidden" name="template_id" value="{{ $template->id }}">
            <input type="hidden" name="category" value="{{ $template->category }}">

            <div class="form-group">
                <label for="subject">Issue title</label>
                <input id="subject"
                       type="text"
                       name="subject"
                       value="{{ old('subject', $template->title) }}"
                       maxlength="255"
                       required
                       class="input @error('subject') input--error @enderror">
                @error('subject')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Category</label>
                    <div class="readonly-field">
                        {{ ucwords(str_replace('_', ' ', $template->category)) }}
                    </div>
                    <small class="field-hint">Category is fixed for this template.</small>
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select id="priority"
                            name="priority"
                            required
                            class="input">
                        @foreach (['low', 'medium', 'high', 'urgent'] as $priority)
                            <option value="{{ $priority }}"
                                @selected(old('priority', $template->priority) === $priority)>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Problem description</label>
                <textarea id="description"
                          name="description"
                          rows="7"
                          required
                          class="input input--textarea @error('description') input--error @enderror">{{ old('description', $template->description) }}</textarea>
                @error('description')
                    <span class="field-error">{{ $message }}</span>
                @enderror
                <small class="field-hint">
                    You can edit this suggested description and add more details.
                </small>
            </div>

            <div class="review-form__actions">
                <a href="{{ route('admin.support-tickets.create') }}" class="btn-line">
                    Cancel
                </a>
                <button type="submit" class="btn-solid">
                    <i class="fas fa-paper-plane"></i>
                    Submit issue
                </button>
            </div>
        </form>
    </section>

</div>

<style>
    .review-page {
        --r-ink: var(--ink, #1d3a33);
        --r-ink2: var(--ink-2, #16302a);
        --r-line: var(--line, #d8d1bf);
        --r-muted: var(--muted, #6b7c75);
        --r-text: var(--text, #2c3e3a);
        --r-serif: var(--serif, Georgia, "Times New Roman", serif);
        --r-red: var(--red, #e4572e);
        max-width: 820px;
        margin: 0 auto;
        color: var(--r-text);
        font-size: 13.5px;
    }

    .review-page a { text-decoration: none; }

    .review-page__top {
        margin-bottom: 22px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: var(--r-muted);
        font-size: 13px;
        font-weight: 500;
    }

    .back-link:hover { color: var(--r-ink); }

    .review-page__top h2 {
        margin: 0 0 6px;
        font-family: var(--r-serif);
        font-size: 26px;
        font-weight: 700;
        color: var(--r-ink);
    }

    .review-page__top p {
        margin: 0;
        color: var(--r-muted);
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
        background: #fff;
        border: 1px solid var(--r-line);
        border-radius: 10px;
    }

    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 22px;
        border-bottom: 1px dashed #d8d1bf;
    }

    .panel-head h3 {
        margin: 0;
        font-family: var(--r-serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--r-ink);
    }

    .panel-head span:not(.priority) {
        display: block;
        margin-top: 3px;
        font-size: 12px;
        color: var(--r-muted);
    }

    .review-form {
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
        color: var(--r-ink);
    }

    .input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #cfc8b6;
        border-radius: 8px;
        background: #fff;
        color: var(--r-text);
        font-family: inherit;
        font-size: 14px;
        line-height: 1.4;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .input:focus {
        outline: none;
        border-color: var(--r-ink);
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

    .readonly-field {
        padding: 11px 13px;
        border: 1px solid #e6dfce;
        border-radius: 8px;
        background: #f8f6f0;
        color: var(--r-ink);
        font-size: 14px;
        font-weight: 600;
    }

    .field-error {
        font-size: 12px;
        color: var(--r-red);
    }

    .field-hint {
        font-size: 12px;
        color: var(--r-muted);
        line-height: 1.5;
    }

    .priority {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border: 1px solid transparent;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .priority::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .p-high {
        border-color: #f0d5ce;
        background: #fff4ef;
        color: #a84840;
    }

    .p-medium {
        border-color: #eee0b9;
        background: #fbf3df;
        color: #8a682b;
    }

    .p-low {
        border-color: #cfe5d3;
        background: #edf7ed;
        color: #2f6b45;
    }

    .p-none {
        border-color: var(--r-line);
        background: #f6f3ea;
        color: var(--r-muted);
    }

    .review-form__actions {
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
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s ease, border-color .15s ease, color .15s ease;
    }

    .btn-solid {
        border: 1px solid var(--r-ink);
        background: var(--r-ink);
        color: #fff;
    }

    .btn-solid:hover {
        background: var(--r-ink2);
        color: #fff;
    }

    .btn-line {
        border: 1px solid #cfc8b6;
        background: #fff;
        color: var(--r-text);
    }

    .btn-line:hover {
        border-color: var(--r-ink);
        color: var(--r-ink);
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .review-form__actions {
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