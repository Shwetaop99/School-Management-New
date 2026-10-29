@extends('super-admin.layouts.app')

@section('title', 'Register School')
@section('breadcrumb', 'School Registry')
@section('page-title', 'Register New School')

@section('content')
<style>
    .registration-page { max-width: 1100px; margin: 0 auto; }
    .registration-intro { color: #718078; margin: 6px 0 24px; font-size: 14px; }
    .registration-card {
        background: #fff; border: 1px solid #e5ebe6; border-radius: 14px;
        margin-bottom: 22px; overflow: hidden;
    }
    .registration-card-header { padding: 20px 24px; border-bottom: 1px solid #e5ebe6; }
    .registration-card-header h2 { font-size: 17px; margin: 0; }
    .registration-card-header p { color: #718078; font-size: 13px; margin: 6px 0 0; }
    .registration-card-body { padding: 24px; }
    .registration-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .registration-field { min-width: 0; }
    .registration-field.full-width { grid-column: 1 / -1; }
    .registration-field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 7px; color: #24332d; }
    .registration-field input, .registration-field select, .registration-field textarea {
        width: 100%; box-sizing: border-box; padding: 11px 12px;
        border: 1px solid #dce5de; border-radius: 8px; background: #fff;
        color: #24332d; font: inherit; font-size: 13px;
    }
    .registration-field input:focus, .registration-field select:focus, .registration-field textarea:focus {
        outline: 2px solid #d2e8da; border-color: #2f7658;
    }
    .registration-field textarea { min-height: 90px; resize: vertical; }
    .registration-field small { display: block; color: #718078; font-size: 12px; margin-top: 6px; }
    .registration-field .field-error { color: #b93838; font-size: 12px; margin-top: 6px; }
    .registration-required { color: #b93838; }
    .registration-note { background: #eaf4ee; color: #2b5e45; padding: 14px 16px; border-radius: 9px; font-size: 13px; line-height: 1.6; margin-top: 18px; }
    .registration-errors { background: #fff0ed; border: 1px solid #f1c9c1; color: #a33d31; border-radius: 9px; padding: 15px 20px; margin-bottom: 20px; }
    .registration-errors ul { margin: 8px 0 0; padding-left: 20px; }
    .registration-actions { display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px; margin-top: 22px; }
    .registration-button { display: inline-flex; align-items: center; justify-content: center; padding: 11px 17px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; }
    .registration-button-primary { background: #2f7658; color: #fff; border: 1px solid #2f7658; }
    .registration-button-secondary { background: #fff; color: #24332d; border: 1px solid #dce5de; }
    @media (max-width: 650px) {
        .registration-grid { grid-template-columns: 1fr; }
        .registration-field.full-width { grid-column: auto; }
        .registration-card-body { padding: 17px; }
        .registration-card-header { padding: 17px; }
    }
</style>

<div class="registration-page">
    <p class="registration-intro">
        Register a school and create its administrator account.
        Fields marked <span class="registration-required">*</span> are required.
    </p>

    @if ($errors->any())
        <div class="registration-errors" role="alert" aria-live="polite">
            <strong>Please correct the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('super-admin.schools.store') }}">
        @csrf

        {{-- SCHOOL DETAILS --}}
        <section class="registration-card">
            <div class="registration-card-header">
                <h2>School Details</h2>
                <p>Basic identification and official contact information.</p>
            </div>
            <div class="registration-card-body">
                <div class="registration-grid">
                    <div class="registration-field">
                        <label for="name">School name <span class="registration-required">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}"
                               maxlength="255" required autofocus autocomplete="organization"
                               aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
                        @error('name') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="school_code">Unique school code <span class="registration-required">*</span></label>
                        <input id="school_code" name="school_code" type="text"
                               value="{{ old('school_code') }}" maxlength="20"
                               pattern="[A-Za-z0-9_-]+" title="Use only letters, numbers, hyphens, and underscores."
                               required autocomplete="off"
                               aria-invalid="{{ $errors->has('school_code') ? 'true' : 'false' }}">
                        <small>Must be unique. Use letters, numbers, hyphens, or underscores.</small>
                        @error('school_code') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="school_type">School type <span class="registration-required">*</span></label>
                        <select id="school_type" name="school_type" required
                                aria-invalid="{{ $errors->has('school_type') ? 'true' : 'false' }}">
                            <option value="">Select school type</option>
                            @foreach (['Pre-Primary', 'Primary', 'Upper Primary', 'Secondary', 'Senior Secondary', 'Other'] as $type)
                                <option value="{{ $type }}" @selected(old('school_type') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('school_type') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="education_board">Education board</label>
                        <input id="education_board" name="education_board" type="text"
                               value="{{ old('education_board') }}" maxlength="100"
                               placeholder="e.g. CBSE, ICSE, State Board">
                        @error('education_board') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="registration_number">Registration / affiliation number</label>
                        <input id="registration_number" name="registration_number" type="text"
                               value="{{ old('registration_number') }}" maxlength="100">
                        @error('registration_number') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="establishment_year">Year established</label>
                        <input id="establishment_year" name="establishment_year" type="number"
                               min="1800" max="{{ date('Y') }}" value="{{ old('establishment_year') }}"
                               inputmode="numeric">
                        @error('establishment_year') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="email">School email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               maxlength="255" autocomplete="email">
                        @error('email') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="phone">School phone <span class="registration-required">*</span></label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                               maxlength="20" pattern="[0-9+() -]{7,20}"
                               title="Enter a phone number using digits, spaces, +, hyphens, or parentheses."
                               required autocomplete="tel">
                        @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field full-width">
                        <label for="website">School website</label>
                        <input id="website" name="website" type="url" value="{{ old('website') }}"
                               maxlength="255" placeholder="https://example.com">
                        @error('website') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- SCHOOL ADDRESS --}}
        <section class="registration-card">
            <div class="registration-card-header">
                <h2>School Address</h2>
                <p>Where the school is located.</p>
            </div>
            <div class="registration-card-body">
                <div class="registration-grid">
                    <div class="registration-field full-width">
                        <label for="address_line">Street address <span class="registration-required">*</span></label>
                        <textarea id="address_line" name="address_line" maxlength="255" required>{{ old('address_line') }}</textarea>
                        @error('address_line') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="city">City / town <span class="registration-required">*</span></label>
                        <input id="city" name="city" type="text" value="{{ old('city') }}"
                               maxlength="100" required autocomplete="address-level2">
                        @error('city') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="district">District</label>
                        <input id="district" name="district" type="text" value="{{ old('district') }}" maxlength="100">
                        @error('district') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="state">State <span class="registration-required">*</span></label>
                        <input id="state" name="state" type="text" value="{{ old('state') }}"
                               maxlength="100" required autocomplete="address-level1">
                        @error('state') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="postal_code">PIN / postal code <span class="registration-required">*</span></label>
                        <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}"
                               maxlength="12" pattern="[A-Za-z0-9 -]{3,12}" required
                               title="Enter a valid postal code." autocomplete="postal-code">
                        @error('postal_code') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="country">Country <span class="registration-required">*</span></label>
                        <input id="country" name="country" type="text" value="{{ old('country', 'India') }}"
                               maxlength="100" required autocomplete="country-name">
                        @error('country') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="principal_name">Principal name <span class="registration-required">*</span></label>
                        <input id="principal_name" name="principal_name" type="text"
                               value="{{ old('principal_name') }}" maxlength="255" required>
                        @error('principal_name') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- SCHOOL ADMINISTRATOR ACCOUNT --}}
        <section class="registration-card">
            <div class="registration-card-header">
                <h2>School Administrator Account</h2>
                <p>Create the login account for this school. The email and login ID must be unique.</p>
            </div>
            <div class="registration-card-body">
                <div class="registration-grid">
                    <div class="registration-field">
                        <label for="admin_name">Administrator full name <span class="registration-required">*</span></label>
                        <input id="admin_name" name="admin_name" type="text" value="{{ old('admin_name') }}"
                               maxlength="255" required autocomplete="name">
                        @error('admin_name') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="admin_email">Administrator email <span class="registration-required">*</span></label>
                        <input id="admin_email" name="admin_email" type="email" value="{{ old('admin_email') }}"
                               maxlength="255" required autocomplete="email">
                        @error('admin_email') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="admin_login_id">Login ID <span class="registration-required">*</span></label>
                        <input id="admin_login_id" name="admin_login_id" type="text"
                               value="{{ old('admin_login_id') }}" minlength="4" maxlength="50"
                               pattern="[A-Za-z0-9_-]+" title="Use only letters, numbers, hyphens, and underscores."
                               required autocomplete="username">
                        <small>Use 4–50 characters: letters, numbers, hyphens, and underscores.</small>
                        @error('admin_login_id') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="admin_phone">Administrator phone <span class="registration-required">*</span></label>
                        <input id="admin_phone" name="admin_phone" type="tel" value="{{ old('admin_phone') }}"
                               maxlength="20" pattern="[0-9+() -]{7,20}"
                               title="Enter a phone number using digits, spaces, +, hyphens, or parentheses."
                               required autocomplete="tel">
                        @error('admin_phone') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="admin_password">Password <span class="registration-required">*</span></label>
                        <input id="admin_password" name="admin_password" type="password"
                               minlength="8" maxlength="128" required autocomplete="new-password">
                        <small>Use at least 8 characters.</small>
                        @error('admin_password') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="registration-field">
                        <label for="admin_password_confirmation">Confirm password <span class="registration-required">*</span></label>
                        <input id="admin_password_confirmation" name="admin_password_confirmation"
                               type="password" minlength="8" maxlength="128" required autocomplete="new-password">
                    </div>
                </div>

                <div class="registration-note">
                    <strong>Account security:</strong> The administrator will be assigned the existing
                    <strong>admin</strong> role and linked to this school. Laravel hashes the password.
                    Share the login credentials securely.
                </div>
            </div>
        </section>

        <div class="registration-actions">
            <a href="{{ route('super-admin.schools.index') }}"
               class="registration-button registration-button-secondary">Cancel</a>
            <button type="submit" class="registration-button registration-button-primary">
                Register School &amp; Administrator
            </button>
        </div>
    </form>
</div>
@endsection
