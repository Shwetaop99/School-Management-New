@extends('layouts.app')

@section('title', 'Two-Factor Authentication')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    :root {
        --ink: #1d3a33;
        --ink-2: #2a4a41;
        --chalk: #f2b632;
        --wood: #8a5a2c;
        --red: #e4572e;
        --green: #3a9d6b;
        --accent: #2b5d8a;
        --text: #26302c;
        --muted: #6a756f;
        --line: #ddd6c4;
        --serif: 'Fraunces', Georgia, serif;
        --hand: 'Caveat', 'Comic Sans MS', cursive;
    }

    .twofa-page {
        min-height: calc(100vh - 40px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
        color: var(--text);

        background-color: #fbf8f1;
        background-image:
            repeating-linear-gradient(
                transparent 0 31px,
                rgba(43, 93, 138, .07) 31px 32px
            );
    }

    .twofa-card {
        position: relative;
        width: 960px;
        max-width: 100%;
        min-height: 560px;

        display: flex;

        border-radius: 14px;
        overflow: hidden;

        background: #fff;

        box-shadow:
            0 20px 44px rgba(29, 58, 51, .16);
    }

    .twofa-card::before {
        content: "";
        position: absolute;
        z-index: 10;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;

        background: linear-gradient(
            90deg,
            var(--red) 0 25%,
            var(--chalk) 25% 50%,
            var(--green) 50% 75%,
            var(--accent) 75% 100%
        );
    }

    /* =========================================================
       LEFT CHALKBOARD
    ========================================================= */

    .twofa-board {
        position: relative;
        width: 42%;

        padding: 56px 40px 110px;

        color: #e9efe2;
        overflow: hidden;

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
            var(--ink);

        border-right: 10px solid var(--wood);

        box-shadow:
            inset -3px 0 6px rgba(0,0,0,.28);
    }

    .twofa-logo {
        width: 68px;
        height: 68px;

        border-radius: 50%;
        overflow: hidden;

        background: #26306a;

        margin-bottom: 22px;

        box-shadow:
            0 0 0 3px var(--ink),
            0 0 0 5px var(--chalk);
    }

    .twofa-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .twofa-board h1 {
        font-family: var(--serif);
        font-size: 30px;
        font-weight: 700;
        line-height: 1.15;

        color: #fff;

        margin-bottom: 8px;
    }

    .twofa-board .hand {
        font-family: var(--hand);
        font-size: 24px;

        color: var(--chalk);

        line-height: 1;

        margin-bottom: 26px;
    }

    .security-list {
        list-style: none;

        display: flex;
        flex-direction: column;

        gap: 14px;

        padding: 0;
        margin: 0;
    }

    .security-list li {
        display: flex;
        align-items: flex-start;

        gap: 11px;

        font-size: 14px;
        line-height: 1.5;

        color: #d9e1d0;
    }

    .security-list li i {
        color: var(--chalk);
        font-size: 16px;

        margin-top: 2px;
    }

    /* =========================================================
       SCHOOL BUS
    ========================================================= */

    .twofa-road {
        position: absolute;

        left: 0;
        right: 0;

        bottom: 0;

        height: 90px;

        pointer-events: none;
    }

    .twofa-road::before {
        content: "";

        position: absolute;

        left: 0;
        right: 0;

        bottom: 22px;

        border-top: 2px dashed rgba(233,239,226,.35);
    }

    .twofa-bus {
        position: absolute;

        bottom: 28px;
        left: 34px;

        width: 150px;

        animation:
            twofaDrive
            2.2s
            cubic-bezier(.3,.6,.25,1)
            .5s
            both;
    }

    .twofa-bus svg {
        display: block;

        width: 100%;
        height: auto;
    }

    @keyframes twofaDrive {
        from {
            transform: translateX(-500px);
        }

        to {
            transform: none;
        }
    }

    /* =========================================================
       RIGHT SIDE
    ========================================================= */

    .twofa-right {
        width: 58%;

        padding: 60px 64px 40px;

        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .twofa-right h2 {
        font-family: var(--serif);

        font-size: 28px;
        font-weight: 700;

        color: var(--ink);

        margin-bottom: 5px;
    }

    .twofa-subtitle {
        color: var(--muted);

        font-size: 14px;

        margin-bottom: 28px;
    }

    /* Shield */

    .twofa-shield {
        width: 68px;
        height: 68px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #f3efe3;

        color: var(--ink);

        font-size: 30px;

        margin-bottom: 20px;

        box-shadow:
            0 0 0 5px rgba(242,182,50,.22);
    }

    /* Error */

    .twofa-error {
        display: flex;
        align-items: center;

        gap: 10px;

        background: #fdecea;

        border: 1px solid #f5c2bb;

        color: #b3321d;

        padding: 11px 13px;

        border-radius: 8px;

        font-size: 13px;

        margin-bottom: 18px;
    }

    /* Instructions */

    .twofa-instructions {
        display: flex;

        gap: 12px;

        padding: 15px;

        margin-bottom: 22px;

        background: #f8f6ef;

        border: 1px solid var(--line);

        border-radius: 9px;
    }

    .twofa-instructions i {
        color: var(--accent);

        font-size: 20px;

        margin-top: 1px;
    }

    .twofa-instructions p {
        margin: 0;

        color: var(--muted);

        font-size: 13.5px;

        line-height: 1.55;
    }

    /* OTP */

    .twofa-field {
        margin-bottom: 20px;
    }

    .twofa-field label {
        display: block;

        font-size: 13px;
        font-weight: 600;

        color: var(--ink);

        margin-bottom: 7px;
    }

    .otp-wrapper {
        position: relative;
    }

    .otp-wrapper > i {
        position: absolute;

        left: 15px;
        top: 50%;

        transform: translateY(-50%);

        color: var(--muted);

        font-size: 17px;
    }

    .otp-input {
        width: 100%;

        height: 54px;

        padding: 0 48px;

        font-family: 'Public Sans', Arial, sans-serif;

        font-size: 22px;
        font-weight: 700;

        letter-spacing: 8px;

        color: var(--text);

        text-align: center;

        background: #fff;

        border: 1px solid var(--line);

        border-radius: 8px;

        outline: none;

        transition:
            border-color .15s,
            box-shadow .15s;
    }

    .otp-input::placeholder {
        color: #b4b7b2;
        letter-spacing: 7px;
    }

    .otp-input:focus {
        border-color: var(--ink);

        box-shadow:
            0 0 0 3px rgba(242,182,50,.4);
    }

    /* Verify button */

    .verify-btn {
        width: 100%;

        height: 50px;

        display: flex;
        align-items: center;
        justify-content: center;

        gap: 10px;

        border: none;

        border-radius: 9px;

        background: var(--ink);

        color: #fff;

        font-family: 'Public Sans', Arial, sans-serif;

        font-size: 14.5px;
        font-weight: 600;

        cursor: pointer;

        transition:
            background .15s,
            transform .15s;
    }

    .verify-btn:hover {
        background: var(--ink-2);

        transform: translateY(-1px);
    }

    .verify-btn i {
        color: var(--chalk);
    }

    .verify-btn.is-busy {
        pointer-events: none;
        opacity: .85;
    }

    .twofa-spinner {
        width: 15px;
        height: 15px;

        border: 2px solid #fff;

        border-right-color: transparent;

        border-radius: 50%;

        animation:
            twofaSpin
            .7s
            linear
            infinite;
    }

    @keyframes twofaSpin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Footer */

    .twofa-footer {
        margin-top: 24px;

        text-align: center;

        font-size: 12px;

        color: #8a938d;
    }

    .twofa-footer i {
        color: var(--green);

        margin-right: 4px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 860px) {

        .twofa-page {
            padding: 18px;
        }

        .twofa-card {
            max-width: 520px;

            flex-direction: column;

            min-height: 0;
        }

        .twofa-board {
            width: 100%;

            border-right: none;

            border-bottom: 8px solid var(--wood);

            box-shadow: none;

            padding: 26px 28px;

            display: flex;

            align-items: center;

            gap: 16px;
        }

        .twofa-logo {
            margin: 0;

            width: 56px;
            height: 56px;

            flex-shrink: 0;
        }

        .twofa-board h1 {
            font-size: 23px;

            margin: 0;
        }

        .twofa-board .hand,
        .security-list,
        .twofa-road {
            display: none;
        }

        .twofa-right {
            width: 100%;

            padding: 34px 28px 28px;
        }
    }

    @media (max-width: 480px) {

        .twofa-page {
            padding: 10px;
        }

        .twofa-right {
            padding: 30px 20px 24px;
        }

        .twofa-right h2 {
            font-size: 25px;
        }

        .otp-input {
            font-size: 19px;

            letter-spacing: 5px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .twofa-bus {
            animation: none;
        }

        .twofa-spinner {
            animation-duration: 1.6s;
        }
    }

</style>


<div class="twofa-page">

    <div class="twofa-card">

        {{-- =====================================================
             CHALKBOARD PANEL
        ====================================================== --}}

        <section class="twofa-board">

            <div>

                <div class="twofa-logo">

                    <img
                        src="{{ asset('images/gurukullogo.png') }}"
                        alt="Gurukul Vidyalaya logo"
                    >

                </div>

                <h1>
                    Gurukul Vidyalaya
                </h1>

                <div class="hand">
                    School management
                </div>

                <ul class="security-list">

                    <li>
                        <i class="bi bi-shield-check"></i>

                        <span>
                            Your account is protected with
                            two-factor authentication.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-phone"></i>

                        <span>
                            Use your authenticator app to
                            generate a verification code.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-lock-fill"></i>

                        <span>
                            Never share your verification code
                            with anyone.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-lg"></i>

                        <span>
                            Complete verification to access
                            the school dashboard.
                        </span>
                    </li>

                </ul>

            </div>

            {{-- School bus --}}

            <div class="twofa-road" aria-hidden="true">

                <div class="twofa-bus">

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

        </section>


        {{-- =====================================================
             RIGHT 2FA PANEL
        ====================================================== --}}

        <section class="twofa-right">

            <div class="twofa-shield">

                <i class="bi bi-shield-lock-fill"></i>

            </div>

            <h2>
                Verify your identity
            </h2>

            <p class="twofa-subtitle">
                One more step to securely access your school dashboard.
            </p>


            {{-- Errors --}}

            @if ($errors->any())

                <div class="twofa-error" role="alert">

                    <i class="bi bi-exclamation-circle-fill"></i>

                    <span>
                        {{ $errors->first() }}
                    </span>

                </div>

            @endif


            {{-- Instructions --}}

            <div class="twofa-instructions">

                <i class="bi bi-phone-fill"></i>

                <p>
                    Open your authenticator app and enter the
                    <strong>6-digit verification code</strong>
                    currently shown for your Gurukul Vidyalaya account.
                </p>

            </div>


            {{-- Verification Form --}}

            <form
                method="POST"
                action="{{ route('admin.2fa.verify') }}"
                id="twoFactorForm"
            >

                @csrf

                <div class="twofa-field">

                    <label for="code">
                        Authentication code
                    </label>

                    <div class="otp-wrapper">

                        <i class="bi bi-key-fill"></i>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            class="otp-input"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            placeholder="000000"
                            required
                            autofocus
                        >

                    </div>

                </div>


                <button
                    type="submit"
                    class="verify-btn"
                    id="verifyButton"
                >

                    <span id="verifyText">
                        Verify & Continue
                    </span>

                    <i
                        class="bi bi-arrow-right"
                        id="verifyArrow"
                    ></i>

                </button>

            </form>


            <div class="twofa-footer">

                <i class="bi bi-shield-check"></i>

                Secure two-factor authentication

                &nbsp;•&nbsp;

                Authorized staff only

            </div>

        </section>

    </div>

</div>


<script>

    const twoFactorForm =
        document.getElementById('twoFactorForm');

    const verifyButton =
        document.getElementById('verifyButton');

    const verifyText =
        document.getElementById('verifyText');

    const verifyArrow =
        document.getElementById('verifyArrow');

    const codeInput =
        document.getElementById('code');


    /*
    |--------------------------------------------------------------------------
    | Allow digits only
    |--------------------------------------------------------------------------
    */

    codeInput.addEventListener('input', function () {

        this.value = this.value
            .replace(/\D/g, '')
            .slice(0, 6);

    });


    /*
    |--------------------------------------------------------------------------
    | Submit state
    |--------------------------------------------------------------------------
    */

    twoFactorForm.addEventListener('submit', function () {

        if (codeInput.value.length !== 6) {
            return;
        }

        verifyButton.classList.add('is-busy');

        verifyText.textContent = 'Verifying...';

        verifyArrow.outerHTML =
            '<span class="twofa-spinner"></span>';

    });


    /*
    |--------------------------------------------------------------------------
    | Restore button when navigating back
    |--------------------------------------------------------------------------
    */

    window.addEventListener('pageshow', function (event) {

        if (!event.persisted) {
            return;
        }

        verifyButton.classList.remove('is-busy');

        verifyText.textContent =
            'Verify & Continue';

        const spinner =
            verifyButton.querySelector('.twofa-spinner');

        if (spinner) {

            spinner.outerHTML =
                '<i class="bi bi-arrow-right" id="verifyArrow"></i>';

        }

    });

</script>

@endsection