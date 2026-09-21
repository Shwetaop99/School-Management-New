@extends('layouts.app')

@section('content')

<style>
    .notice-create-page {
        padding: 10px 5px;
    }

    .notice-form-header {
        margin-bottom: 25px;
    }

    .notice-form-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .notice-form-header p {
        margin: 6px 0 0;
        color: #7b8497;
        font-size: 14px;
    }

    .notice-form-card {
        background: #fff;
        border: 1px solid #edf0f5;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(30, 50, 80, 0.07);
        padding: 28px;
        max-width: 1100px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #202a3b;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #edf0f5;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #354052;
        margin-bottom: 8px;
    }

    .required {
        color: #d32f2f;
    }

    .form-control {
        width: 100%;
        height: 45px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #fafbfc;
        padding: 0 13px;
        outline: none;
        color: #273247;
        font-size: 13px;
        box-sizing: border-box;
        transition: 0.2s;
    }

    textarea.form-control {
        height: 150px;
        padding: 13px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-control:focus {
        border-color: #1976d2;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.08);
    }

    .form-help {
        margin-top: 6px;
        font-size: 11px;
        color: #9099a9;
    }

    .priority-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .priority-option {
        position: relative;
    }

    .priority-option input {
        position: absolute;
        opacity: 0;
    }

    .priority-option label {
        display: block;
        padding: 10px 18px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #fafbfc;
        color: #596579;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: 0.2s;
    }

    .priority-option input:checked + label {
        border-color: #1976d2;
        background: #eaf3ff;
        color: #1976d2;
    }

    .attachment-box {
        border: 1.5px dashed #cfd6e2;
        border-radius: 10px;
        padding: 22px;
        text-align: center;
        background: #fafbfc;
        cursor: pointer;
    }

    .attachment-box svg {
        width: 28px;
        color: #1976d2;
        margin-bottom: 7px;
    }

    .attachment-box strong {
        display: block;
        font-size: 13px;
        color: #354052;
    }

    .attachment-box span {
        display: block;
        margin-top: 5px;
        font-size: 11px;
        color: #9099a9;
    }

    .attachment-box input {
        margin-top: 12px;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid #edf0f5;
    }

    .btn {
        height: 43px;
        padding: 0 20px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
    }

    .btn-cancel {
        background: #f3f5f8;
        color: #596579;
    }

    .btn-cancel:hover {
        background: #e9edf2;
    }

    .btn-save {
        background: #1976d2;
        color: #fff;
        box-shadow: 0 5px 14px rgba(25, 118, 210, 0.20);
    }

    .btn-save:hover {
        background: #1565c0;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .notice-form-card {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="notice-create-page">

    <div class="notice-form-header">
        <h1>Create Notice</h1>
        <p>Create and publish a new school announcement.</p>
    </div>

    <div class="notice-form-card">

        <form method="POST"
      action="{{ route('admin.notices.store') }}"
      enctype="multipart/form-data">

            @csrf

            {{-- Basic Information --}}
            <div class="form-section">

                <div class="form-section-title">
                    Notice Information
                </div>

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label>
                            Notice Title <span class="required">*</span>
                        </label>

                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="Enter notice title">
                    </div>


                    <div class="form-group full-width">
                        <label>
                            Notice Content <span class="required">*</span>
                        </label>

                        <textarea name="content"
                                  class="form-control"
                                  placeholder="Write the notice content here..."></textarea>

                        <div class="form-help">
                            Provide clear and detailed information for students, parents and faculty.
                        </div>
                    </div>


                    <div class="form-group">

                        <label>
                            Category <span class="required">*</span>
                        </label>

                        <select name="category" class="form-control">

                            <option value="General">General</option>
                            <option value="Academic">Academic</option>
                            <option value="Events">Events</option>
                            <option value="Examination">Examination</option>
                            <option value="Holiday">Holiday</option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Status <span class="required">*</span>
                        </label>

                        <select name="status" class="form-control">

                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>

                        </select>

                    </div>

                </div>

            </div>


            {{-- Schedule --}}
            <div class="form-section">

                <div class="form-section-title">
                    Publication Schedule
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Publish Date
                        </label>

                        <input type="date"
                               name="publish_date"
                               class="form-control">

                    </div>


                    <div class="form-group">

                        <label>
                            Expiry Date
                        </label>

                        <input type="date"
                               name="expiry_date"
                               class="form-control">

                    </div>

                </div>

            </div>


            {{-- Priority --}}
            <div class="form-section">

                <div class="form-section-title">
                    Notice Priority
                </div>

                <div class="priority-options">

                    <div class="priority-option">

                        <input type="radio"
                               name="priority"
                               id="priority-normal"
                               value="Normal"
                               checked>

                        <label for="priority-normal">
                            Normal
                        </label>

                    </div>


                    <div class="priority-option">

                        <input type="radio"
                               name="priority"
                               id="priority-important"
                               value="Important">

                        <label for="priority-important">
                            Important
                        </label>

                    </div>


                    <div class="priority-option">

                        <input type="radio"
                               name="priority"
                               id="priority-urgent"
                               value="Urgent">

                        <label for="priority-urgent">
                            Urgent
                        </label>

                    </div>

                </div>

            </div>


            {{-- Attachment --}}
            <div class="form-section">

                <div class="form-section-title">
                    Attachment
                </div>

                <div class="attachment-box">

                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>

                        <polyline points="17 8 12 3 7 8"/>

                        <line x1="12" y1="3"
                              x2="12" y2="15"/>

                    </svg>

                    <strong>
                        Attach a file
                    </strong>

                    <span>
                        PDF, DOC, DOCX, JPG or PNG
                    </span>

                    <input type="file"
                           name="attachment"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                </div>

            </div>


            {{-- Actions --}}
            <div class="form-actions">

                <a href="{{ route('admin.notices.index') }}"
                   class="btn btn-cancel">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-save">
                    Save Notice
                </button>

            </div>

        </form>

    </div>

</div>

@endsection