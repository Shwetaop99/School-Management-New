@extends('layouts.app')

@section('title', 'Security Settings')

@section('content')

<style>
    :root {
        --security-ink: #1d3a33;
        --security-ink-light: #2a4a41;
        --security-yellow: #f2b632;
        --security-wood: #8a5a2c;
        --security-red: #e4572e;
        --security-green: #3a9d6b;
        --security-blue: #2b5d8a;
        --security-text: #26302c;
        --security-muted: #6a756f;
        --security-line: #ddd6c4;
        --security-paper: #fbf8f1;
        --security-serif: 'Fraunces', Georgia, serif;
        --security-hand: 'Caveat', 'Comic Sans MS', cursive;
    }

    .security-page {
        min-height: calc(100vh - 120px);
        padding: 34px 30px 45px;
        color: var(--security-text);
        background-color: var(--security-paper);
        background-image:
            repeating-linear-gradient(
                transparent 0 31px,
                rgba(43, 93, 138, .07) 31px 32px
            );
    }

    .security-shell {
        max-width: 1180px;
        margin: 0 auto;
    }

    .security-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
    }

    .security-page-title {
        margin: 0;
        color: var(--security-ink);
        font-family: var(--security-serif);
        font-size: 32px;
        font-weight: 700;
    }

    .security-page-subtitle {
        margin: 5px 0 0;
        color: var(--security-muted);
        font-size: 14px;
    }

    .security-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid #bdb7a7;
        border-radius: 8px;
        background: #fff;
        color: var(--security-ink);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .security-back:hover {
        color: var(--security-ink);
        border-color: var(--security-ink);
        background: #f8f6ef;
    }

    .security-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border-radius: 9px;
        font-size: 13px;
    }

    .security-alert-success {
        color: #17633f;
        background: #edf8f1;
        border: 1px solid #b9e2ca;
    }

    .security-alert-danger {
        color: #a52d1b;
        background: #fdecea;
        border: 1px solid #f5c2bb;
    }

    .security-alert-warning {
        color: #7a5a05;
        background: #fff8df;
        border: 1px solid #eed68c;
    }

    .security-alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .security-card {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: 310px minmax(0, 1fr);
        min-height: 590px;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 18px 42px rgba(29, 58, 51, .14);
    }

    .security-card::before {
        content: "";
        position: absolute;
        z-index: 5;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(
            90deg,
            var(--security-red) 0 25%,
            var(--security-yellow) 25% 50%,
            var(--security-green) 50% 75%,
            var(--security-blue) 75% 100%
        );
    }

    /* Left chalkboard */

    .security-board {
        position: relative;
        padding: 48px 30px 105px;
        overflow: hidden;
        color: #e9efe2;
        background:
            radial-gradient(
                ellipse at 18% 8%,
                rgba(255,255,255,.08),
                transparent 55%
            ),
            radial-gradient(
                ellipse at 85% 90%,
                rgba(255,255,255,.06),
                transparent 50%
            ),
            var(--security-ink);
        border-right: 10px solid var(--security-wood);
        box-shadow: inset -3px 0 6px rgba(0,0,0,.28);
    }

    .security-logo {
        width: 66px;
        height: 66px;
        margin-bottom: 20px;
        overflow: hidden;
        border-radius: 50%;
        background: #26306a;
        box-shadow:
            0 0 0 3px var(--security-ink),
            0 0 0 5px var(--security-yellow);
    }

    .security-logo img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .security-board h2 {
        margin: 0 0 7px;
        color: #fff;
        font-family: var(--security-serif);
        font-size: 27px;
        line-height: 1.15;
    }

    .security-hand {
        margin-bottom: 25px;
        color: var(--security-yellow);
        font-family: var(--security-hand);
        font-size: 23px;
        line-height: 1;
    }

    .security-board-copy {
        margin-bottom: 23px;
        color: #d9e1d0;
        font-size: 13.5px;
        line-height: 1.6;
    }

    .security-board-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .security-board-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        color: #d9e1d0;
        font-size: 13px;
        line-height: 1.45;
    }

    .security-board-list i {
        flex: 0 0 auto;
        margin-top: 2px;
        color: var(--security-yellow);
        font-size: 16px;
    }

    .security-road {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 82px;
        pointer-events: none;
    }

    .security-road::before {
        content: "";
        position: absolute;
        right: 0;
        bottom: 20px;
        left: 0;
        border-top: 2px dashed rgba(233,239,226,.35);
    }

    .security-bus {
        position: absolute;
        bottom: 27px;
        left: 30px;
        width: 142px;
        animation: securityBusDrive 1.7s cubic-bezier(.3,.6,.25,1) .2s both;
    }

    .security-bus svg {
        display: block;
        width: 100%;
        height: auto;
    }

    @keyframes securityBusDrive {
        from { transform: translateX(-380px); }
        to { transform: translateX(0); }
    }

    /* Right content */

    .security-content {
        padding: 42px 46px 40px;
        background: rgba(255,255,255,.96);
    }

    .security-content-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 24px;
    }

    .security-content-title {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .security-main-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        border-radius: 13px;
        background: #f3efe3;
        color: var(--security-ink);
        font-size: 22px;
        box-shadow: 0 0 0 4px rgba(242,182,50,.18);
    }

    .security-content h1 {
        margin: 0 0 3px;
        color: var(--security-ink);
        font-family: var(--security-serif);
        font-size: 27px;
        font-weight: 700;
    }

    .security-content-head p {
        margin: 0;
        color: var(--security-muted);
        font-size: 13px;
    }

    .security-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 20px;
        white-space: nowrap;
        font-size: 12px;
        font-weight: 700;
    }

    .security-status-enabled {
        color: #17633f;
        background: #eaf7ef;
        border: 1px solid #b9e2ca;
    }

    .security-status-disabled {
        color: #a52d1b;
        background: #fdecea;
        border: 1px solid #f5c2bb;
    }

    .security-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .security-panel {
        padding: 20px;
        margin-bottom: 18px;
        border: 1px solid var(--security-line);
        border-radius: 12px;
        background: #fff;
    }

    .security-panel:last-child {
        margin-bottom: 0;
    }

    .security-panel-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0 0 6px;
        color: var(--security-ink);
        font-family: var(--security-serif);
        font-size: 18px;
    }

    .security-panel-title i {
        color: var(--security-blue);
    }

    .security-panel-description {
        margin: 0 0 18px;
        color: var(--security-muted);
        font-size: 13px;
        line-height: 1.55;
    }

    /* Setup layout */

    .security-setup-grid {
        display: grid;
        grid-template-columns: 245px minmax(0, 1fr);
        gap: 24px;
        align-items: center;
    }

    .security-qr-area {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .security-qr {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        border: 1px solid #d9d3c5;
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 5px 14px rgba(29,58,51,.08);
    }

    .security-qr svg {
        display: block;
        width: 190px;
        height: 190px;
    }

    .security-qr-caption {
        margin-top: 9px;
        color: var(--security-muted);
        font-size: 11px;
        text-align: center;
    }

    .security-step {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 18px;
    }

    .security-step-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--security-yellow);
        color: var(--security-ink);
        font-size: 13px;
        font-weight: 800;
    }

    .security-step strong {
        display: block;
        margin-bottom: 3px;
        color: var(--security-ink);
        font-size: 13px;
    }

    .security-step span {
        color: var(--security-muted);
        font-size: 12.5px;
        line-height: 1.5;
    }

    .security-manual-key {
        padding: 11px 13px;
        margin: 14px 0 20px;
        border-radius: 8px;
        background: #f8f6ef;
        border: 1px dashed #cfc6b2;
    }

    .security-manual-key small {
        display: block;
        margin-bottom: 4px;
        color: var(--security-muted);
        font-size: 11px;
    }

    .security-manual-key code {
        display: block;
        color: var(--security-ink);
        word-break: break-all;
        font-size: 12px;
        letter-spacing: .4px;
    }

    /* Forms */

    .security-form-label {
        display: block;
        margin-bottom: 7px;
        color: var(--security-ink);
        font-size: 12.5px;
        font-weight: 700;
    }

    .security-code-wrap {
        position: relative;
        max-width: 360px;
    }

    .security-code-wrap i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: var(--security-muted);
        font-size: 16px;
    }

    .security-code-input {
        width: 100%;
        height: 49px;
        padding: 0 42px;
        border: 1px solid #cfc8b8;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: var(--security-text);
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 7px;
        text-align: center;
        transition: .15s ease;
    }

    .security-code-input:focus {
        border-color: var(--security-ink);
        box-shadow: 0 0 0 3px rgba(242,182,50,.35);
    }

    .security-code-input::placeholder {
        color: #b9b9b3;
        letter-spacing: 6px;
    }

    .security-primary-btn,
    .security-danger-btn {
        min-height: 45px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: .15s ease;
    }

    .security-primary-btn {
        border: 1px solid var(--security-ink);
        background: var(--security-ink);
        color: #fff;
    }

    .security-primary-btn:hover {
        background: var(--security-ink-light);
        color: #fff;
        transform: translateY(-1px);
    }

    .security-primary-btn i {
        color: var(--security-yellow);
    }

    .security-danger-btn {
        border: 1px solid #dfaaa1;
        background: #fff7f5;
        color: #b3321d;
    }

    .security-danger-btn:hover {
        background: #fdecea;
        color: #a52d1b;
    }

    /* Enabled */

    .security-enabled-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        margin-bottom: 18px;
        border-radius: 10px;
        background: #f2faf5;
        border: 1px solid #c5e7d1;
    }

    .security-enabled-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        border-radius: 11px;
        background: #dff3e6;
        color: #198754;
        font-size: 19px;
    }

    .security-enabled-box strong {
        display: block;
        margin-bottom: 4px;
        color: var(--security-ink);
        font-family: var(--security-serif);
        font-size: 17px;
    }

    .security-enabled-box p {
        margin: 0;
        color: var(--security-muted);
        font-size: 12.5px;
        line-height: 1.5;
    }

    .security-danger-panel {
        padding: 18px;
        border-radius: 10px;
        background: #fff8f6;
        border: 1px solid #efc7c0;
    }

    .security-danger-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
        color: #a52d1b;
        font-family: var(--security-serif);
        font-size: 17px;
    }

    .security-danger-description {
        margin: 0 0 16px;
        color: var(--security-muted);
        font-size: 12.5px;
        line-height: 1.5;
    }

    .security-disable-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .security-disable-form .security-code-wrap {
        width: 280px;
    }

    .security-info {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-top: 18px;
    }

    .security-info-item {
        padding: 13px;
        border: 1px solid #e4ded0;
        border-radius: 9px;
        background: #fbfaf6;
    }

    .security-info-item i {
        display: block;
        margin-bottom: 8px;
        color: var(--security-blue);
        font-size: 18px;
    }

    .security-info-item strong {
        display: block;
        margin-bottom: 3px;
        color: var(--security-ink);
        font-size: 12.5px;
    }

    .security-info-item span {
        display: block;
        color: var(--security-muted);
        font-size: 11.5px;
        line-height: 1.45;
    }

    @media (max-width: 980px) {
        .security-card {
            grid-template-columns: 250px minmax(0, 1fr);
        }

        .security-content {
            padding: 36px 30px;
        }

        .security-setup-grid {
            grid-template-columns: 190px minmax(0, 1fr);
            gap: 18px;
        }

        .security-qr svg {
            width: 160px;
            height: 160px;
        }
    }

    @media (max-width: 800px) {
        .security-page {
            padding: 25px 15px 35px;
        }

        .security-page-header {
            align-items: flex-start;
        }

        .security-card {
            display: flex;
            flex-direction: column;
        }

        .security-board {
            min-height: 205px;
            padding: 28px;
            border-right: 0;
            border-bottom: 8px solid var(--security-wood);
        }

        .security-board h2 {
            font-size: 24px;
        }

        .security-board-copy,
        .security-board-list {
            display: none;
        }

        .security-hand {
            margin-bottom: 0;
        }

        .security-road {
            display: none;
        }

        .security-content {
            padding: 30px 24px;
        }
    }

    @media (max-width: 620px) {
        .security-page-header {
            flex-direction: column;
        }

        .security-page-title {
            font-size: 27px;
        }

        .security-content-head {
            flex-direction: column;
        }

        .security-setup-grid {
            grid-template-columns: 1fr;
        }

        .security-qr-area {
            align-items: flex-start;
        }

        .security-info {
            grid-template-columns: 1fr;
        }

        .security-disable-form .security-code-wrap {
            width: 100%;
        }

        .security-primary-btn,
        .security-danger-btn {
            width: 100%;
        }
    }
</style>


<div class="security-page">

    <div class="security-shell">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="security-page-header">

            <div>
                <h1 class="security-page-title">
                    Security Settings
                </h1>

                <p class="security-page-subtitle">
                    Manage your account security and two-factor authentication.
                </p>
            </div>

            <a
                href="{{ route('admin.settings.index') }}"
                class="security-back"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Settings
            </a>

        </div>


        {{-- =====================================================
             FLASH MESSAGES
        ====================================================== --}}

        @if(session('success'))

            <div class="security-alert security-alert-success">
                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('success') }}
                </span>
            </div>

        @endif


        @if(session('error'))

            <div class="security-alert security-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>

                <span>
                    {{ session('error') }}
                </span>
            </div>

        @endif


        @if($errors->any())

            <div class="security-alert security-alert-danger">

                <i class="bi bi-exclamation-circle-fill"></i>

                <div>
                    <strong>Please check the following:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        @endif


        {{-- =====================================================
             MAIN SECURITY CARD
        ====================================================== --}}

        <div class="security-card">

            {{-- =================================================
                 CHALKBOARD
            ================================================== --}}

            <aside class="security-board">

                <div class="security-logo">

                    <img
                        src="{{ asset('images/gurukullogo.png') }}"
                        alt="Gurukul Vidyalaya logo"
                    >

                </div>

                <h2>
                    Gurukul Vidyalaya
                </h2>

                <div class="security-hand">
                    Account security
                </div>

                <p class="security-board-copy">
                    Keep your school management account protected
                    with an additional layer of authentication.
                </p>

                <ul class="security-board-list">

                    <li>
                        <i class="bi bi-shield-check"></i>
                        <span>
                            Protect your account beyond your password.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-phone"></i>
                        <span>
                            Use Google Authenticator or another TOTP app.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-lock-fill"></i>
                        <span>
                            Never share your setup key or verification code.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-clock-history"></i>
                        <span>
                            Your verification code changes automatically.
                        </span>
                    </li>

                </ul>


                {{-- School bus decoration --}}

                <div class="security-road" aria-hidden="true">

                    <div class="security-bus">

                        <svg
                            viewBox="0 0 240 100"
                            xmlns="http://www.w3.org/2000/svg"
                        >

                            <rect
                                x="2"
                                y="4"
                                width="230"
                                height="78"
                                rx="12"
                                fill="#f2b632"
                            />

                            <rect
                                x="2"
                                y="52"
                                width="230"
                                height="8"
                                fill="#e0a21f"
                            />

                            <g
                                fill="#cfe7f3"
                                stroke="#26302c"
                                stroke-width="2.5"
                            >
                                <rect x="16" y="16" width="32" height="26" rx="3"/>
                                <rect x="56" y="16" width="32" height="26" rx="3"/>
                                <rect x="96" y="16" width="32" height="26" rx="3"/>
                                <rect x="136" y="16" width="32" height="26" rx="3"/>
                                <rect x="182" y="14" width="40" height="32" rx="4"/>
                            </g>

                            <text
                                x="16"
                                y="74"
                                font-family="Arial"
                                font-weight="700"
                                font-size="12"
                                fill="#26302c"
                            >
                                SCHOOL BUS
                            </text>

                            <rect
                                x="226"
                                y="56"
                                width="10"
                                height="12"
                                rx="3"
                                fill="#e4572e"
                            />

                            <g fill="#26302c">
                                <circle cx="54" cy="84" r="15"/>
                                <circle cx="180" cy="84" r="15"/>
                            </g>

                            <g fill="#d9d2bf">
                                <circle cx="54" cy="84" r="6"/>
                                <circle cx="180" cy="84" r="6"/>
                            </g>

                        </svg>

                    </div>

                </div>

            </aside>


            {{-- =================================================
                 CONTENT
            ================================================== --}}

            <main class="security-content">

                <div class="security-content-head">

                    <div class="security-content-title">

                        <div class="security-main-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>

                        <div>

                            <h1>
                                Two-Factor Authentication
                            </h1>

                            <p>
                                Add an extra layer of protection to your account.
                            </p>

                        </div>

                    </div>


                    @if($user->two_factor_enabled)

                        <span class="security-status security-status-enabled">
                            <span class="security-status-dot"></span>
                            Enabled
                        </span>

                    @else

                        <span class="security-status security-status-disabled">
                            <span class="security-status-dot"></span>
                            Disabled
                        </span>

                    @endif

                </div>


                {{-- =================================================
                     ENABLED STATE
                ================================================== --}}

                @if($user->two_factor_enabled)

                    <div class="security-enabled-box">

                        <div class="security-enabled-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div>

                            <strong>
                                Your account is secured
                            </strong>

                            <p>
                                Two-factor authentication is enabled.
                                You will need the 6-digit code from your
                                authenticator app when signing in.
                            </p>

                        </div>

                    </div>


                    <div class="security-danger-panel">

                        <div class="security-danger-title">
                            <i class="bi bi-shield-x"></i>
                            Disable Two-Factor Authentication
                        </div>

                        <p class="security-danger-description">
                            Disabling 2FA will reduce the security of your account.
                            Enter the current authenticator code to continue.
                        </p>

                        <form
                            action="{{ route('admin.2fa.disable') }}"
                            method="POST"
                            class="security-disable-form"
                        >

                            @csrf

                            <div>

                                <label
                                    for="disable-code"
                                    class="security-form-label"
                                >
                                    Current authenticator code
                                </label>

                                <div class="security-code-wrap">

                                    <i class="bi bi-key-fill"></i>

                                    <input
                                        type="text"
                                        id="disable-code"
                                        name="code"
                                        class="security-code-input"
                                        inputmode="numeric"
                                        autocomplete="one-time-code"
                                        maxlength="6"
                                        pattern="[0-9]{6}"
                                        placeholder="000000"
                                        required
                                    >

                                </div>

                            </div>

                            <button
                                type="submit"
                                class="security-danger-btn"
                                onclick="return confirmDisable2FA();"
                            >
                                <i class="bi bi-shield-x"></i>
                                Disable 2FA
                            </button>

                        </form>

                    </div>


                    <div class="security-info">

                        <div class="security-info-item">
                            <i class="bi bi-phone-fill"></i>

                            <strong>
                                Authenticator App
                            </strong>

                            <span>
                                Your app generates the codes used during login.
                            </span>
                        </div>

                        <div class="security-info-item">
                            <i class="bi bi-clock-history"></i>

                            <strong>
                                Time-Based Codes
                            </strong>

                            <span>
                                Codes automatically change for better security.
                            </span>
                        </div>

                        <div class="security-info-item">
                            <i class="bi bi-lock-fill"></i>

                            <strong>
                                Keep It Private
                            </strong>

                            <span>
                                Never share your authentication codes.
                            </span>
                        </div>

                    </div>


                {{-- =================================================
                     DISABLED / SETUP STATE
                ================================================== --}}

                @else

                    <div class="security-panel">

                        <h3 class="security-panel-title">
                            <i class="bi bi-shield-plus"></i>
                            Protect your account with 2FA
                        </h3>

                        <p class="security-panel-description">
                            Set up an authenticator app to require a
                            time-based verification code in addition to
                            your password when signing in.
                        </p>


                        @if($secret && $qrCodeUrl)

                            <div class="security-setup-grid">

                                {{-- QR CODE --}}

                                <div class="security-qr-area">

                                    <div class="security-qr">
                                        {!! $qrCodeUrl !!}
                                    </div>

                                    <div class="security-qr-caption">
                                        Scan with your authenticator app
                                    </div>

                                </div>


                                {{-- SETUP INSTRUCTIONS --}}

                                <div>

                                    <div class="security-step">

                                        <div class="security-step-number">
                                            1
                                        </div>

                                        <div>

                                            <strong>
                                                Scan the QR code
                                            </strong>

                                            <span>
                                                Open Google Authenticator,
                                                Microsoft Authenticator, or
                                                another compatible TOTP app
                                                and scan the QR code.
                                            </span>

                                        </div>

                                    </div>


                                    <div class="security-step">

                                        <div class="security-step-number">
                                            2
                                        </div>

                                        <div>

                                            <strong>
                                                Enter the generated code
                                            </strong>

                                            <span>
                                                Your authenticator app will
                                                generate a 6-digit code.
                                                Enter it below to finish setup.
                                            </span>

                                        </div>

                                    </div>


                                    <div class="security-manual-key">

                                        <small>
                                            Can't scan the QR code?
                                            Use this manual setup key.
                                        </small>

                                        <code>
                                            {{ $secret }}
                                        </code>

                                    </div>


                                    <form
                                        action="{{ route('admin.2fa.enable') }}"
                                        method="POST"
                                        id="enableTwoFactorForm"
                                    >

                                        @csrf

                                        <label
                                            for="enable-code"
                                            class="security-form-label"
                                        >
                                            6-digit verification code
                                        </label>

                                        <div class="security-code-wrap">

                                            <i class="bi bi-key-fill"></i>

                                            <input
                                                type="text"
                                                id="enable-code"
                                                name="code"
                                                class="security-code-input"
                                                inputmode="numeric"
                                                autocomplete="one-time-code"
                                                maxlength="6"
                                                pattern="[0-9]{6}"
                                                placeholder="000000"
                                                required
                                                autofocus
                                            >

                                        </div>

                                        <div style="margin-top: 12px;">

                                            <button
                                                type="submit"
                                                class="security-primary-btn"
                                                id="enableTwoFactorButton"
                                            >
                                                <i class="bi bi-shield-check"></i>
                                                Enable 2FA
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        @else

                            <div class="security-alert security-alert-warning" style="margin-bottom:0;">

                                <i class="bi bi-exclamation-triangle-fill"></i>

                                <span>
                                    Two-factor authentication setup information
                                    could not be generated. Please refresh the page
                                    or contact the administrator.
                                </span>

                            </div>

                        @endif

                    </div>


                    <div class="security-info">

                        <div class="security-info-item">
                            <i class="bi bi-qr-code-scan"></i>

                            <strong>
                                Scan & Connect
                            </strong>

                            <span>
                                Scan the QR code with your authenticator app.
                            </span>
                        </div>

                        <div class="security-info-item">
                            <i class="bi bi-clock-history"></i>

                            <strong>
                                Changing Codes
                            </strong>

                            <span>
                                Your authenticator generates a new code automatically.
                            </span>
                        </div>

                        <div class="security-info-item">
                            <i class="bi bi-shield-lock-fill"></i>

                            <strong>
                                Extra Protection
                            </strong>

                            <span>
                                Password and authenticator code work together.
                            </span>
                        </div>

                    </div>

                @endif

            </main>

        </div>

    </div>

</div>


<script>
    /*
    |--------------------------------------------------------------------------
    | Allow digits only in 2FA code fields
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.security-code-input').forEach(function (input) {

        input.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 6);

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Confirm before disabling 2FA
    |--------------------------------------------------------------------------
    */

    function confirmDisable2FA() {

        return confirm(
            'Are you sure you want to disable Two-Factor Authentication? Your account will have less protection.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Prevent accidental repeated Enable submission
    |--------------------------------------------------------------------------
    */

    const enableForm =
        document.getElementById('enableTwoFactorForm');

    const enableButton =
        document.getElementById('enableTwoFactorButton');

    if (enableForm && enableButton) {

        enableForm.addEventListener('submit', function () {

            const code =
                document.getElementById('enable-code');

            if (!code || code.value.length !== 6) {
                return;
            }

            enableButton.disabled = true;

            enableButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verifying...';

        });

    }
</script>

@endsection
