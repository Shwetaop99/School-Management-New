
@extends('layouts.app')

@section('title', 'Edit Class | Admin')

@section('page-title', 'Edit Class')

@section('content')

<div class="class-form-page">
    <div class="class-form-container">

        {{-- PAGE HEADER --}}
        <div class="class-page-header">
            <div class="class-heading-area">
                <div class="class-heading-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>

                <div class="class-heading-copy">
                    <div class="class-breadcrumb">
                        ACADEMIC MANAGEMENT <span>/</span> CLASSES <span>/</span> EDIT CLASS
                    </div>
                    <h2>Edit Class</h2>
                    <p>Update class details, academic year and current status.</p>
                </div>
            </div>

            <a href="{{ route('admin.classes.index') }}" class="class-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Classes</span>
            </a>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="class-alert-error" role="alert">
                <div class="class-alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>

                <div class="class-alert-content">
                    <strong>Please correct the following errors</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="class-form-card">

            {{-- CARD HEADER --}}
            <div class="class-form-card-header">
                <div class="class-section-heading">
                    <div class="class-section-number">01</div>
                    <div class="class-section-icon">
                        <i class="fas fa-school"></i>
                    </div>

                    <div>
                        <h3>Class Information</h3>
                        <p>Review and update the details below.</p>
                    </div>
                </div>

                <span class="class-required-note">
                    <span class="required">*</span> Required fields
                </span>
            </div>

            {{-- CARD BODY --}}
            <div class="class-form-card-body">

                <form
                    action="{{ route('admin.classes.update', $schoolClass->id) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    {{-- ROW 1 --}}
                    <div class="class-form-section">
                        <div class="class-section-label">
                            <span class="class-section-label-icon">
                                <i class="fas fa-layer-group"></i>
                            </span>
                            Basic Details
                        </div>

                        <div class="class-form-grid">

                            {{-- CLASS NAME --}}
                            <div class="class-form-group">
                                <label for="class_name" class="class-form-label">
                                    Class Name <span class="required">*</span>
                                </label>

                                <div class="class-input-wrap">
                                    <i class="fas fa-book-open class-input-icon"></i>
                                    <input
                                        type="text"
                                        id="class_name"
                                        name="class_name"
                                        class="class-form-control"
                                        value="{{ old('class_name', $schoolClass->class_name) }}"
                                        placeholder="Example: Class 1"
                                        required
                                        autocomplete="off"
                                        aria-describedby="class-name-error"
                                    >
                                </div>

                                @error('class_name')
                                    <div class="class-form-error" id="class-name-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- SECTION --}}
                            <div class="class-form-group">
                                <label for="section" class="class-form-label">
                                    Section <span class="required">*</span>
                                </label>

                                <div class="class-input-wrap">
                                    <i class="fas fa-users class-input-icon"></i>
                                    <input
                                        type="text"
                                        id="section"
                                        name="section"
                                        class="class-form-control"
                                        value="{{ old('section', $schoolClass->section) }}"
                                        placeholder="Example: A"
                                        required
                                        autocomplete="off"
                                        aria-describedby="section-error"
                                    >
                                </div>

                                @error('section')
                                    <div class="class-form-error" id="section-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ROW 2 --}}
                    <div class="class-form-section class-form-section-last">
                        <div class="class-section-label">
                            <span class="class-section-label-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            Academic Details
                        </div>

                        <div class="class-form-grid">

                            {{-- ACADEMIC YEAR --}}
                            <div class="class-form-group">
                                <label for="academic_year" class="class-form-label">
                                    Academic Year <span class="required">*</span>
                                </label>

                                <div class="class-input-wrap">
                                    <i class="fas fa-calendar-check class-input-icon"></i>
                                    <input
                                        type="text"
                                        id="academic_year"
                                        name="academic_year"
                                        class="class-form-control"
                                        value="{{ old('academic_year', $schoolClass->academic_year) }}"
                                        placeholder="Example: 2026-2027"
                                        required
                                        autocomplete="off"
                                        aria-describedby="academic-year-error"
                                    >
                                </div>

                                @error('academic_year')
                                    <div class="class-form-error" id="academic-year-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="class-field-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Enter the academic year for this class.
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="class-form-group">
                                <label for="status" class="class-form-label">
                                    Status <span class="required">*</span>
                                </label>

                                <div class="class-input-wrap">
                                    <i class="fas fa-toggle-on class-input-icon"></i>
                                    <select
                                        id="status"
                                        name="status"
                                        class="class-form-control class-form-select"
                                        required
                                        aria-describedby="status-error"
                                    >
                                        <option
                                            value="1"
                                            {{ (string) old('status', $schoolClass->status) === '1' ? 'selected' : '' }}
                                        >
                                            Active
                                        </option>

                                        <option
                                            value="0"
                                            {{ (string) old('status', $schoolClass->status) === '0' ? 'selected' : '' }}
                                        >
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                @error('status')
                                    <div class="class-form-error" id="status-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="class-field-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Choose whether this class is active.
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ACTION BAR --}}
                    <div class="class-form-actions">
                        <div class="class-action-note">
                            <i class="fas fa-shield-alt"></i>
                            Check the details before saving your changes.
                        </div>

                        <div class="class-action-buttons">
                            <a
                                href="{{ route('admin.classes.show', $schoolClass->id) }}"
                                class="class-btn class-btn-secondary"
                            >
                                <i class="fas fa-eye"></i>
                                View Class
                            </a>

                            <a
                                href="{{ route('admin.classes.index') }}"
                                class="class-btn class-btn-light"
                            >
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>

                            <button type="submit" class="class-btn class-btn-primary">
                                <i class="fas fa-save"></i>
                                Update Class
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        {{-- FOOTER NOTE --}}
        <div class="class-page-footer">
            <i class="fas fa-info-circle"></i>
            Changes will be saved to the existing class record.
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM — EDIT CLASS
   Scoped styles: .class-form-page
   ========================================================= */

.class-form-page {
    --class-bg: #f5f3e9;
    --class-paper: #fffefa;
    --class-ink: #263b35;
    --class-green: #34775a;
    --class-green-dark: #245a43;
    --class-green-light: #eaf3e9;
    --class-wood: #a87543;
    --class-cyan: #527f79;
    --class-red: #bd5148;
    --class-border: #e5e1d3;
    --class-muted: #7a8279;

    width: 100%;
    min-width: 0;
    color: var(--class-ink);
    font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI",
        sans-serif;
    font-size: 12px;
    padding: 4px 0 18px;
}

.class-form-page *,
.class-form-page *::before,
.class-form-page *::after {
    box-sizing: border-box;
}

.class-form-container {
    width: 100%;
    max-width: 1180px;
    margin: 0 auto;
}

/* PAGE HEADER */

.class-form-page .class-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 20px;
    padding: 5px 0 5px 15px;
    border-left: 4px solid var(--class-green);
}

.class-form-page .class-heading-area {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.class-form-page .class-heading-icon {
    width: 45px;
    height: 45px;
    flex: 0 0 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d5e5d6;
    border-radius: 10px;
    background: var(--class-green-light);
    color: var(--class-green);
    font-size: 19px;
}

.class-form-page .class-heading-copy {
    min-width: 0;
}

.class-form-page .class-breadcrumb {
    margin-bottom: 5px;
    color: var(--class-muted);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.05px;
    line-height: 1.5;
}

.class-form-page .class-breadcrumb span {
    margin: 0 5px;
    color: var(--class-wood);
}

.class-form-page .class-page-header h2 {
    margin: 0;
    color: var(--class-ink);
    font-size: 22px;
    font-weight: 750;
    line-height: 1.25;
    letter-spacing: -0.45px;
}

.class-form-page .class-page-header p {
    margin: 5px 0 0;
    color: var(--class-muted);
    font-size: 11px;
    line-height: 1.5;
}

.class-form-page .class-back-btn {
    display: inline-flex;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 36px;
    padding: 8px 12px;
    border: 1px solid var(--class-border);
    border-radius: 7px;
    background: var(--class-paper);
    color: var(--class-green-dark);
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    transition: background .18s ease, border-color .18s ease;
}

.class-form-page .class-back-btn:hover {
    border-color: #b9d1bd;
    background: var(--class-green-light);
    color: var(--class-green-dark);
    text-decoration: none;
}

/* VALIDATION ALERT */

.class-form-page .class-alert-error {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 17px;
    padding: 13px 15px;
    border: 1px solid #edc9c4;
    border-radius: 8px;
    background: #fff4f1;
    color: var(--class-red);
}

.class-form-page .class-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    flex: 0 0 29px;
    border-radius: 7px;
    background: #fbe3df;
    font-size: 14px;
}

.class-form-page .class-alert-content {
    min-width: 0;
    padding-top: 2px;
}

.class-form-page .class-alert-content strong {
    display: block;
    margin-bottom: 5px;
    color: #8d3932;
    font-size: 12px;
    font-weight: 750;
}

.class-form-page .class-alert-content ul {
    margin: 0;
    padding-left: 17px;
}

.class-form-page .class-alert-content li {
    margin: 3px 0;
    color: #99463f;
    font-size: 11px;
    line-height: 1.5;
}

/* MAIN FORM CARD */

.class-form-page .class-form-card {
    width: 100%;
    max-width: 950px;
    overflow: hidden;
    border: 1px solid var(--class-border);
    border-radius: 10px;
    background: var(--class-paper);
    box-shadow: 0 3px 12px rgba(53, 65, 49, .045);
}

.class-form-page .class-form-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 19px;
    border-bottom: 1px solid var(--class-border);
    background: #fffdf7;
}

.class-form-page .class-section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.class-form-page .class-section-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    flex: 0 0 29px;
    border: 1px solid #d7e5d6;
    border-radius: 7px;
    background: var(--class-green-light);
    color: var(--class-green-dark);
    font-size: 10px;
    font-weight: 800;
}

.class-form-page .class-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border: 1px solid #e7ddca;
    border-radius: 8px;
    background: #f7efe1;
    color: var(--class-wood);
    font-size: 14px;
}

.class-form-page .class-section-heading h3 {
    margin: 0;
    color: var(--class-ink);
    font-size: 13px;
    font-weight: 750;
    line-height: 1.4;
}

.class-form-page .class-section-heading p {
    margin: 3px 0 0;
    color: var(--class-muted);
    font-size: 10px;
    line-height: 1.4;
}

.class-form-page .class-required-note {
    flex: 0 0 auto;
    color: var(--class-muted);
    font-size: 10px;
}

.class-form-page .required {
    color: var(--class-red);
    font-weight: 800;
}

/* FORM BODY */

.class-form-page .class-form-card-body {
    padding: 21px 20px 18px;
}

.class-form-page .class-form-section {
    margin-bottom: 22px;
    padding-bottom: 21px;
    border-bottom: 1px dashed #e8e2d5;
}

.class-form-page .class-form-section-last {
    margin-bottom: 0;
    padding-bottom: 20px;
    border-bottom: none;
}

.class-form-page .class-section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    color: var(--class-ink);
    font-size: 11px;
    font-weight: 750;
}

.class-form-page .class-section-label-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    border: 1px solid #dce8dc;
    border-radius: 6px;
    background: #f0f5eb;
    color: var(--class-green);
    font-size: 11px;
}

.class-form-page .class-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 19px 22px;
}

.class-form-page .class-form-group {
    min-width: 0;
}

.class-form-page .class-form-label {
    display: block;
    margin-bottom: 7px;
    color: var(--class-ink);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.class-form-page .class-input-wrap {
    position: relative;
    width: 100%;
}

.class-form-page .class-input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 1;
    color: #8b9a89;
    font-size: 12px;
    pointer-events: none;
    transform: translateY(-50%);
}

.class-form-page .class-form-control {
    display: block;
    width: 100%;
    height: 40px;
    margin: 0;
    padding: 0 12px 0 34px;
    border: 1px solid #dedccc;
    border-radius: 7px;
    outline: none;
    background: #fffefa;
    color: var(--class-ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 500;
    line-height: normal;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease,
        background .18s ease;
}

.class-form-page .class-form-control::placeholder {
    color: #a4a79b;
    opacity: 1;
}

.class-form-page .class-form-control:hover {
    border-color: #c9d4c5;
}

.class-form-page .class-form-control:focus {
    border-color: var(--class-green);
    outline: none;
    background: #fffefa;
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .11);
}

.class-form-page .class-form-select {
    padding-right: 32px;
    cursor: pointer;
    appearance: auto;
}

.class-form-page .class-form-control option {
    background: #fffefa;
    color: var(--class-ink);
}

.class-form-page .class-form-error {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    margin-top: 6px;
    color: var(--class-red);
    font-size: 10px;
    line-height: 1.5;
}

.class-form-page .class-form-error i {
    margin-top: 2px;
    flex: 0 0 auto;
}

.class-form-page .class-field-hint {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    margin-top: 7px;
    color: #858b7e;
    font-size: 10px;
    line-height: 1.5;
}

.class-form-page .class-field-hint i {
    margin-top: 2px;
    color: var(--class-cyan);
}

/* ACTION BAR */

.class-form-page .class-form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-top: 3px;
    padding-top: 17px;
    border-top: 1px solid var(--class-border);
}

.class-form-page .class-action-note {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    max-width: 260px;
    color: var(--class-muted);
    font-size: 10px;
    line-height: 1.6;
}

.class-form-page .class-action-note i {
    margin-top: 2px;
    color: var(--class-green);
}

.class-form-page .class-action-buttons {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
}

.class-form-page .class-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 36px;
    padding: 8px 12px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.3;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease,
        color .18s ease, box-shadow .18s ease;
}

.class-form-page .class-btn:focus-visible,
.class-form-page .class-back-btn:focus-visible {
    outline: 2px solid var(--class-cyan);
    outline-offset: 3px;
}

.class-form-page .class-btn-primary {
    border-color: var(--class-green);
    background: var(--class-green);
    color: #fffefa;
    box-shadow: 0 2px 4px rgba(36, 90, 67, .12);
}

.class-form-page .class-btn-primary:hover {
    border-color: var(--class-green-dark);
    background: var(--class-green-dark);
    color: #fffefa;
    box-shadow: 0 3px 7px rgba(36, 90, 67, .15);
}

.class-form-page .class-btn-secondary {
    border-color: #d9e4d7;
    background: var(--class-green-light);
    color: var(--class-green-dark);
}

.class-form-page .class-btn-secondary:hover {
    border-color: #b9d1bd;
    background: #dcebdd;
    color: var(--class-green-dark);
}

.class-form-page .class-btn-light {
    border-color: var(--class-border);
    background: var(--class-paper);
    color: #687168;
}

.class-form-page .class-btn-light:hover {
    border-color: #d3cbb9;
    background: #f5f3e9;
    color: var(--class-ink);
}

/* FOOTER */

.class-form-page .class-page-footer {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 13px;
    color: #81877b;
    font-size: 10px;
    line-height: 1.5;
}

.class-form-page .class-page-footer i {
    color: var(--class-wood);
}

/* RESPONSIVE */

@media (max-width: 991.98px) {
    .class-form-page .class-form-card {
        max-width: 100%;
    }

    .class-form-page .class-form-actions {
        align-items: flex-start;
        flex-direction: column;
    }

    .class-form-page .class-action-note {
        max-width: 100%;
    }

    .class-form-page .class-action-buttons {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 767.98px) {
    .class-form-page {
        padding-top: 2px;
    }

    .class-form-page .class-page-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 13px;
        padding-left: 11px;
    }

    .class-form-page .class-back-btn {
        margin-left: 0;
    }

    .class-form-page .class-page-header h2 {
        font-size: 20px;
    }

    .class-form-page .class-form-card-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px;
    }

    .class-form-page .class-form-card-body {
        padding: 17px 14px 15px;
    }

    .class-form-page .class-form-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
    }

    .class-form-page .class-form-section {
        margin-bottom: 18px;
        padding-bottom: 18px;
    }

    .class-form-page .class-form-section-last {
        margin-bottom: 0;
    }

    .class-form-page .class-form-actions {
        gap: 13px;
    }

    .class-form-page .class-action-buttons {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .class-form-page .class-btn {
        width: 100%;
        white-space: normal;
    }

    .class-form-page .class-btn-primary {
        grid-column: 1 / -1;
        grid-row: 1;
    }
}

@media (max-width: 480px) {
    .class-form-page .class-heading-area {
        align-items: flex-start;
        gap: 9px;
    }

    .class-form-page .class-heading-icon {
        width: 39px;
        height: 39px;
        flex-basis: 39px;
        font-size: 16px;
    }

    .class-form-page .class-breadcrumb {
        font-size: 8px;
        letter-spacing: .65px;
    }

    .class-form-page .class-page-header h2 {
        font-size: 19px;
    }

    .class-form-page .class-page-header p {
        font-size: 10px;
    }

    .class-form-page .class-section-heading {
        gap: 7px;
    }

    .class-form-page .class-section-number {
        width: 25px;
        height: 25px;
        flex-basis: 25px;
    }

    .class-form-page .class-section-icon {
        width: 30px;
        height: 30px;
        flex-basis: 30px;
    }

    .class-form-page .class-form-card-body {
        padding: 14px 11px;
    }

    .class-form-page .class-action-buttons {
        grid-template-columns: minmax(0, 1fr);
    }

    .class-form-page .class-btn-primary {
        grid-column: auto;
        grid-row: auto;
    }

    .class-form-page .class-page-footer {
        align-items: flex-start;
    }
}

@media (prefers-reduced-motion: reduce) {
    .class-form-page *,
    .class-form-page *::before,
    .class-form-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>
@endpush
