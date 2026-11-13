@extends('layouts.app')

@section('content')

<style>
    .notice-edit-page {
        padding: 10px 5px;
    }

    .edit-header {
        margin-bottom: 25px;
    }

    .edit-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .edit-header p {
        margin: 6px 0 0;
        color: #7b8497;
        font-size: 14px;
    }

    .edit-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #edf0f5;
        box-shadow: 0 4px 18px rgba(30, 50, 80, 0.07);
        padding: 28px;
        max-width: 1000px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #354052;
    }

    .required {
        color: #d32f2f;
    }

    .form-control,
    .form-select,
    .form-textarea {
        width: 100%;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #fafbfc;
        color: #273247;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
        transition: 0.2s;
    }

    .form-control,
    .form-select {
        height: 44px;
        padding: 0 13px;
    }

    .form-textarea {
        min-height: 180px;
        padding: 13px;
        resize: vertical;
        font-family: inherit;
        line-height: 1.6;
    }

    .form-control:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #1976d2;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.08);
    }

    .current-attachment {
        margin-top: 10px;
        padding: 10px 12px;
        background: #f4f7fb;
        border-radius: 8px;
        font-size: 12px;
        color: #697386;
    }

    .current-attachment a {
        color: #1976d2;
        font-weight: 600;
        text-decoration: none;
    }

    .current-attachment a:hover {
        text-decoration: underline;
    }

    .error-message {
        margin-top: 6px;
        color: #d32f2f;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid #edf0f5;
    }

    .cancel-btn,
    .update-btn {
        height: 44px;
        padding: 0 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .cancel-btn {
        background: #fff;
        color: #697386;
        border: 1px solid #dfe4ec;
    }

    .cancel-btn:hover {
        background: #f5f7fa;
    }

    .update-btn {
        border: none;
        background: #1976d2;
        color: #fff;
        box-shadow: 0 5px 14px rgba(25, 118, 210, 0.20);
    }

    .update-btn:hover {
        background: #1565c0;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .edit-card {
            padding: 20px;
        }

        .form-actions {
            justify-content: stretch;
        }

        .cancel-btn,
        .update-btn {
            flex: 1;
        }
    }
</style>

<div class="notice-edit-page">

    <div class="edit-header">
        <h1>Edit Notice</h1>
        <p>Update the notice information and save your changes.</p>
    </div>

    <div class="edit-card">

        <form method="POST"
              action="{{ route('admin.notices.update', $notice) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-grid">

                {{-- Title --}}
                <div class="form-group full-width">
                    <label for="title">
                        Notice Title <span class="required">*</span>
                    </label>

                    <input type="text"
                           id="title"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $notice->title) }}"
                           placeholder="Enter notice title"
                           required>

                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label for="category">
                        Category <span class="required">*</span>
                    </label>

                    <select id="category"
                            name="category"
                            class="form-select"
                            required>

                        @foreach([
                            'Academic',
                            'Events',
                            'Examination',
                            'Holiday',
                            'General'
                        ] as $category)

                            <option value="{{ $category }}"
                                {{ old('category', $notice->category) === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>

                        @endforeach

                    </select>

                    @error('category')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label for="status">
                        Status <span class="required">*</span>
                    </label>

                    <select id="status"
                            name="status"
                            class="form-select"
                            required>

                        <option value="Draft"
                            {{ old('status', $notice->status) === 'Draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="Published"
                            {{ old('status', $notice->status) === 'Published' ? 'selected' : '' }}>
                            Published
                        </option>

                    </select>

                    @error('status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Publish Date --}}
                <div class="form-group">
                    <label for="publish_date">
                        Publish Date
                    </label>

                    <input type="date"
                           id="publish_date"
                           name="publish_date"
                           class="form-control"
                           value="{{ old('publish_date', $notice->publish_date?->format('Y-m-d')) }}">

                    @error('publish_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Expiry Date --}}
                <div class="form-group">
                    <label for="expiry_date">
                        Expiry Date
                    </label>

                    <input type="date"
                           id="expiry_date"
                           name="expiry_date"
                           class="form-control"
                           value="{{ old('expiry_date', $notice->expiry_date?->format('Y-m-d')) }}">

                    @error('expiry_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Priority --}}
                <div class="form-group">
                    <label for="priority">
                        Priority <span class="required">*</span>
                    </label>

                    <select id="priority"
                            name="priority"
                            class="form-select"
                            required>

                        <option value="Normal"
                            {{ old('priority', $notice->priority) === 'Normal' ? 'selected' : '' }}>
                            Normal
                        </option>

                        <option value="Important"
                            {{ old('priority', $notice->priority) === 'Important' ? 'selected' : '' }}>
                            Important
                        </option>

                        <option value="Urgent"
                            {{ old('priority', $notice->priority) === 'Urgent' ? 'selected' : '' }}>
                            Urgent
                        </option>

                    </select>

                    @error('priority')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Attachment --}}
                <div class="form-group">
                    <label for="attachment">
                        Attachment
                    </label>

                    <input type="file"
                           id="attachment"
                           name="attachment"
                           class="form-control"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                    @if($notice->attachment)
                        <div class="current-attachment">
                            Current attachment:
                            <a href="{{ asset('storage/' . $notice->attachment) }}"
                               target="_blank">
                                View current file
                            </a>
                        </div>
                    @endif

                    @error('attachment')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="form-group full-width">
                    <label for="content">
                        Notice Content <span class="required">*</span>
                    </label>

                    <textarea id="content"
                              name="content"
                              class="form-textarea"
                              placeholder="Write notice content..."
                              required>{{ old('content', $notice->content) }}</textarea>

                    @error('content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">

                <a href="{{ route('admin.notices.index') }}"
                   class="cancel-btn">
                    Cancel
                </a>

                <button type="submit"
                        class="update-btn">
                    Update Notice
                </button>

            </div>

        </form>

    </div>

</div>

@endsection