@extends('super-admin.layouts.app')

@section('breadcrumb', 'Security Settings')
@section('page-title', 'Security Settings')

@section('content')
<style>
    .sa-security {
        --ss-ink: #20332e;
        --ss-muted: #788681;
        --ss-green: #347f61;
        --ss-green-dark: #18332d;
        --ss-green-soft: #e8f4ed;
        --ss-coral: #dd3e1a;
        --ss-gold: #b17b19;
        --ss-border: #e3e9e4;
        color: var(--ss-ink);
    }

    .sa-security * { box-sizing: border-box; }
    .sa-security .ss-intro {
        display: flex; justify-content: space-between; align-items: flex-start;
        gap: 22px; margin: 8px 0 26px;
    }
    .sa-security .ss-eyebrow {
        margin: 0 0 10px; color: #267d60; font-size: 11px;
        font-weight: 800; letter-spacing: 1.7px; text-transform: uppercase;
    }
    .sa-security .ss-intro h2 {
        margin: 0; font-size: clamp(25px, 3vw, 35px);
        font-weight: 750; letter-spacing: -1.1px;
    }
    .sa-security .ss-intro p {
        max-width: 680px; margin: 11px 0 0; color: var(--ss-muted);
        font-size: 14px; line-height: 1.8;
    }
    .sa-security .ss-back {
        display: inline-flex; align-items: center; gap: 10px; flex-shrink: 0;
        padding: 13px 16px; border-radius: 10px; background: var(--ss-green-dark);
        color: #fff; font-size: 12px; font-weight: 700; text-decoration: none;
        transition: transform .2s, background .2s;
    }
    .sa-security .ss-back:hover { transform: translateY(-2px); background: #285344; }
    .sa-security .ss-summary {
        display: flex; justify-content: space-between; align-items: center; gap: 20px;
        margin-bottom: 27px; padding: 24px 27px; border: 1px solid #dce9df;
        border-radius: 16px; background: linear-gradient(110deg, #fff 5%, #f0f8f1);
    }
    .sa-security .ss-summary-label {
        margin-bottom: 9px; color: #27805e; font-size: 10px;
        font-weight: 800; letter-spacing: 1.5px;
    }
    .sa-security .ss-summary h3 { margin: 0; font-size: 19px; letter-spacing: -.4px; }
    .sa-security .ss-summary p {
        margin: 8px 0 0; color: var(--ss-muted); font-size: 12px; line-height: 1.7;
    }
    .sa-security .ss-summary-icon {
        display: grid; place-items: center; width: 58px; height: 58px;
        flex-shrink: 0; border-radius: 16px; background: #e2f3e8;
        color: var(--ss-green); font-size: 27px;
    }
    .sa-security .ss-section-title { margin: 0 0 6px; font-size: 17px; font-weight: 750; }
    .sa-security .ss-section-subtitle {
        margin: 0 0 18px; color: var(--ss-muted); font-size: 12px; line-height: 1.7;
    }
    .sa-security .ss-grid {
        display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(260px, .75fr);
        gap: 20px; align-items: start;
    }
    .sa-security .ss-card {
        min-width: 0; overflow: hidden; border: 1px solid var(--ss-border);
        border-radius: 15px; background: #fff; box-shadow: 0 8px 22px #18332d08;
    }
    .sa-security .ss-card-head {
        display: flex; align-items: center; gap: 12px; padding: 19px 21px;
        border-bottom: 1px solid var(--ss-border);
    }
    .sa-security .ss-card-icon {
        display: grid; place-items: center; width: 42px; height: 42px;
        flex-shrink: 0; border-radius: 11px; background: #e6f3eb;
        color: #2b805c; font-size: 20px;
    }
    .sa-security .ss-card-icon.coral { background: #fff0eb; color: var(--ss-coral); }
    .sa-security .ss-card-head h3 { margin: 0; font-size: 14px; }
    .sa-security .ss-card-head p { margin: 5px 0 0; color: var(--ss-muted); font-size: 11px; }
    .sa-security .ss-card-body { padding: 21px; }
    .sa-security .ss-status-row {
        display: flex; justify-content: space-between; align-items: center;
        gap: 10px; margin-bottom: 17px;
    }
    .sa-security .ss-status-label { color: #77857f; font-size: 12px; font-weight: 700; }
    .sa-security .ss-badge {
        display: inline-flex; align-items: center; gap: 7px; padding: 7px 10px;
        border-radius: 30px; font-size: 10px; font-weight: 800; letter-spacing: .45px;
    }
    .sa-security .ss-badge.green { background: #e5f5eb; color: #218257; }
    .sa-security .ss-badge.gold { background: #fff4df; color: #9d6a12; }
    .sa-security .ss-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    .sa-security .ss-message {
        display: flex; gap: 10px; margin: 0 0 17px; padding: 12px 13px;
        border-radius: 9px; font-size: 12px; line-height: 1.7;
    }
    .sa-security .ss-message.success { border: 1px solid #cce9d5; background: #eff9f1; color: #24754d; }
    .sa-security .ss-message.error { border: 1px solid #f5d0ca; background: #fff3f0; color: #a83229; }
    .sa-security .ss-message.info { border: 1px solid #d8e9df; background: #f2f8f3; color: #496e5b; }
    .sa-security .ss-message ul { margin: 0; padding-left: 17px; }
    .sa-security .ss-instructions { display: grid; gap: 12px; margin: 18px 0; }
    .sa-security .ss-instruction {
        display: flex; align-items: flex-start; gap: 10px;
        color: #64736c; font-size: 12px; line-height: 1.7;
    }
    .sa-security .ss-step {
        display: grid; place-items: center; width: 25px; height: 25px;
        flex-shrink: 0; border-radius: 8px; background: #e8f3eb;
        color: #267b55; font-size: 11px; font-weight: 800;
    }
    .sa-security .ss-qr-box {
        padding: 18px; border: 1px dashed #cbded0; border-radius: 13px;
        background: #f7faf6; text-align: center;
    }
    .sa-security .ss-qr {
        display: inline-grid; place-items: center; max-width: 100%;
        padding: 11px; border: 1px solid #e5eae5; border-radius: 10px; background: #fff;
    }
    .sa-security .ss-qr svg { display: block; width: 100%; max-width: 235px; height: auto; }
    .sa-security .ss-qr-caption { margin: 11px 0 0; color: var(--ss-muted); font-size: 11px; line-height: 1.7; }
    .sa-security .ss-field { margin-top: 18px; }
    .sa-security .ss-field label {
        display: block; margin-bottom: 8px; color: #34443d; font-size: 12px; font-weight: 750;
    }
    .sa-security .ss-setup-key {
        display: block; padding: 12px; border: 1px solid var(--ss-border);
        border-radius: 9px; background: #f7f9f6; color: #315543;
        font-family: Consolas, monospace; font-size: 13px; letter-spacing: 1px;
        overflow-wrap: anywhere; user-select: all;
    }
    .sa-security .ss-hint { margin: 7px 0 0; color: var(--ss-muted); font-size: 11px; line-height: 1.7; }
    .sa-security .ss-input {
        width: 100%; min-height: 47px; padding: 11px 13px; border: 1px solid #d4ded7;
        border-radius: 9px; outline: none; background: #fff; color: var(--ss-ink);
        font: inherit; font-size: 17px; letter-spacing: 4px; transition: border-color .2s, box-shadow .2s;
    }
    .sa-security .ss-input:focus { border-color: #39815f; box-shadow: 0 0 0 3px #39815f1a; }
    .sa-security .ss-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; min-height: 46px; margin-top: 16px; padding: 11px 16px;
        border: 0; border-radius: 9px; background: var(--ss-green-dark); color: #fff;
        font: inherit; font-size: 12px; font-weight: 750; cursor: pointer;
        transition: background .2s, transform .2s;
    }
    .sa-security .ss-btn:hover { transform: translateY(-1px); background: #285344; }
    .sa-security .ss-btn.danger { background: var(--ss-coral); }
    .sa-security .ss-btn.danger:hover { background: #c83213; }
    .sa-security .ss-side-body { padding: 20px; }
    .sa-security .ss-side-body > p { margin: 8px 0 18px; color: var(--ss-muted); font-size: 12px; line-height: 1.8; }
    .sa-security .ss-feature { display: flex; gap: 11px; margin: 17px 0; }
    .sa-security .ss-feature-icon {
        display: grid; place-items: center; width: 37px; height: 37px;
        flex-shrink: 0; border-radius: 10px; background: #e8f3eb; color: #2e805c; font-size: 16px;
    }
    .sa-security .ss-feature-icon.coral { background: #fff0eb; color: var(--ss-coral); }
    .sa-security .ss-feature-icon.gold { background: #fff5df; color: var(--ss-gold); }
    .sa-security .ss-feature h4 { margin: 2px 0 5px; font-size: 12px; }
    .sa-security .ss-feature p { margin: 0; color: var(--ss-muted); font-size: 11px; line-height: 1.7; }
    .sa-security .ss-divider { height: 1px; margin: 20px 0; background: var(--ss-border); }
    .sa-security .ss-security-item {
        display: flex; justify-content: space-between; align-items: center;
        gap: 10px; margin: 13px 0; font-size: 11px;
    }
    .sa-security .ss-security-item > span:first-child { color: #788681; }
    @media (max-width: 1050px) {
        .sa-security .ss-grid { grid-template-columns: minmax(0, 1fr); }
    }
    @media (max-width: 700px) {
        .sa-security .ss-intro { display: block; }
        .sa-security .ss-back { margin-top: 15px; }
        .sa-security .ss-summary { padding: 18px; }
        .sa-security .ss-summary h3 { font-size: 16px; }
        .sa-security .ss-summary-icon { width: 48px; height: 48px; }
        .sa-security .ss-card-head, .sa-security .ss-card-body, .sa-security .ss-side-body { padding: 16px; }
    }
</style>

<div class="sa-security">
    <section class="ss-intro">
        <div>
            <div class="ss-eyebrow">Administration / Account protection</div>
            <h2>Protect your system access.</h2>
            <p>
                Manage two-factor authentication for your Super Admin account.
                This authenticator setup is separate from the School Admin QR code.
            </p>
        </div>
        <a class="ss-back" href="{{ route('super-admin.dashboard') }}">
            Back to dashboard <span aria-hidden="true">→</span>
        </a>
    </section>

    <section class="ss-summary">
        <div>
            <div class="ss-summary-label">SECURITY OVERVIEW</div>
            <h3>Keep your administrator account protected</h3>
            <p>Use an authenticator app to add another layer of sign-in security.</p>
        </div>
        <div class="ss-summary-icon" aria-hidden="true">♧</div>
    </section>

    <h2 class="ss-section-title">Account protection</h2>
    <p class="ss-section-subtitle">
        Review your two-factor authentication status and manage your setup.
    </p>

    <div class="ss-grid">
        <section class="ss-card">
            <div class="ss-card-head">
                <div class="ss-card-icon" aria-hidden="true">⚙</div>
                <div>
                    <h3>Two-factor authentication</h3>
                    <p>Authenticator app and verification settings</p>
                </div>
            </div>

            <div class="ss-card-body">
                @if(session('status'))
                    <div class="ss-message success" role="status">
                        <strong>✓</strong>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="ss-message error" role="alert">
                        <strong>!</strong>
                        <div>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if($user->super_admin_two_factor_enabled)
                    <div class="ss-status-row">
                        <span class="ss-status-label">Protection status</span>
                        <span class="ss-badge green"><span class="ss-dot"></span> PROTECTED</span>
                    </div>

                    <div class="ss-message success">
                        <strong>✓</strong>
                        <div>
                            Two-factor authentication is enabled for your Super Admin account.
                            You need a valid authenticator code when signing in.
                        </div>
                    </div>

                    <p class="ss-section-subtitle">
                        To disable protection, enter a current six-digit code from your
                        Super Admin authenticator app. You will need to set it up again
                        before returning to the dashboard.
                    </p>

                    <form method="POST" action="{{ route('super-admin.security.disable') }}">
                        @csrf
                        <div class="ss-field">
                            <label for="disable-code">Current authenticator code</label>
                            <input
                                class="ss-input"
                                id="disable-code"
                                name="code"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                autocomplete="one-time-code"
                                placeholder="000000"
                                required
                            >
                        </div>
                        <button class="ss-btn danger" type="submit">
                            Disable two-factor authentication
                        </button>
                    </form>
                @else
                    <div class="ss-status-row">
                        <span class="ss-status-label">Protection status</span>
                        <span class="ss-badge gold"><span class="ss-dot"></span> SETUP REQUIRED</span>
                    </div>

                    <div class="ss-message info">
                        <strong>i</strong>
                        <div>
                            Two-factor authentication is required for Super Admin access.
                            Complete the setup below to protect this account.
                        </div>
                    </div>

                    <div class="ss-instructions">
                        <div class="ss-instruction">
                            <span class="ss-step">1</span>
                            <span>Open Google Authenticator, Microsoft Authenticator, or another compatible app.</span>
                        </div>
                        <div class="ss-instruction">
                            <span class="ss-step">2</span>
                            <span>Scan this Super Admin QR code or enter the manual setup key in your authenticator app.</span>
                        </div>
                        <div class="ss-instruction">
                            <span class="ss-step">3</span>
                            <span>Enter the current six-digit code to verify and enable two-factor authentication.</span>
                        </div>
                    </div>

                    @if($qrCodeUrl && $secret)
                        <div class="ss-qr-box">
                            <div class="ss-qr">
                                {!! $qrCodeUrl !!}
                            </div>
                            <p class="ss-qr-caption">
                                Scan using your authenticator app. Keep this QR code private.
                            </p>
                        </div>

                        <div class="ss-field">
                            <label for="setup-key">Manual setup key</label>
                            <code class="ss-setup-key" id="setup-key">{{ $secret }}</code>
                            <p class="ss-hint">
                                This key is sensitive. Do not share it or post a screenshot of it publicly.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('super-admin.security.enable') }}">
                            @csrf
                            <div class="ss-field">
                                <label for="enable-code">Six-digit authenticator code</label>
                                <input
                                    class="ss-input"
                                    id="enable-code"
                                    name="code"
                                    type="text"
                                    inputmode="numeric"
                                    pattern="[0-9]{6}"
                                    maxlength="6"
                                    autocomplete="one-time-code"
                                    placeholder="000000"
                                    required
                                >
                                <p class="ss-hint">Enter the current code shown in your authenticator app.</p>
                            </div>
                            <button class="ss-btn" type="submit">✓ Verify and enable protection</button>
                        </form>
                    @else
                        <div class="ss-message error">
                            <strong>!</strong>
                            <div>
                                The QR code is unavailable. Reload the page. If it remains unavailable,
                                check that the security controller returns both
                                <code>$qrCodeUrl</code> and <code>$secret</code>.
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </section>

        <aside class="ss-card">
            <div class="ss-card-head">
                <div class="ss-card-icon coral" aria-hidden="true">♧</div>
                <div>
                    <h3>Security checklist</h3>
                    <p>Recommended account safeguards</p>
                </div>
            </div>

            <div class="ss-side-body">
                <div class="ss-feature">
                    <div class="ss-feature-icon" aria-hidden="true">🔐</div>
                    <div>
                        <h4>Separate QR authentication</h4>
                        <p>The Super Admin uses its dedicated <code>super_admin_two_factor_secret</code> field.</p>
                    </div>
                </div>

                <div class="ss-feature">
                    <div class="ss-feature-icon coral" aria-hidden="true">📱</div>
                    <div>
                        <h4>Authenticator app</h4>
                        <p>Use a compatible app that generates time-based, six-digit verification codes.</p>
                    </div>
                </div>

                <div class="ss-feature">
                    <div class="ss-feature-icon gold" aria-hidden="true">🛡</div>
                    <div>
                        <h4>Protect your setup key</h4>
                        <p>Anyone with your secret key may be able to generate your authentication codes.</p>
                    </div>
                </div>

                <div class="ss-divider"></div>

                <h3>Account status</h3>

                <div class="ss-security-item">
                    <span>Account role</span>
                    <span class="ss-badge green">SUPER ADMIN</span>
                </div>

                <div class="ss-security-item">
                    <span>Two-factor authentication</span>
                    @if($user->super_admin_two_factor_enabled)
                        <span class="ss-badge green">ENABLED</span>
                    @else
                        <span class="ss-badge gold">PENDING</span>
                    @endif
                </div>

                <div class="ss-security-item">
                    <span>Setup type</span>
                    <span>Authenticator app</span>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
