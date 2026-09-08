<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Set Up 2FA | Gurukul Vidyalaya</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family: Arial, Helvetica, sans-serif;

            min-height: 100vh;

            background: #eef3f9;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 30px;
        }


        /* =========================================
           MAIN CONTAINER
        ========================================= */

        .login-container {

            width: 1100px;

            max-width: 100%;

            min-height: 650px;

            background: white;

            border-radius: 16px;

            overflow: hidden;

            display: flex;

            box-shadow:
                0 20px 50px rgba(15, 23, 42, 0.15);
        }


        /* =========================================
           LEFT PANEL
        ========================================= */

        .left-panel {

            width: 50%;

            position: relative;

            overflow: hidden;

            padding: 48px 55px;

            color: white;

            background:
                linear-gradient(
                    145deg,
                    #1769ff 0%,
                    #1689ed 52%,
                    #24b7c8 100%
                );
        }


        /* =========================================
           DECORATIVE CIRCLES
        ========================================= */

        .top-circle {

            position: absolute;

            width: 300px;

            height: 300px;

            border-radius: 50%;

            background: rgba(255,255,255,0.10);

            top: -150px;

            right: -100px;
        }


        .bottom-circle {

            position: absolute;

            width: 350px;

            height: 350px;

            border-radius: 50%;

            background: rgba(255,255,255,0.07);

            bottom: -230px;

            left: -150px;
        }


        /* =========================================
           DOT PATTERN
        ========================================= */

        .dots {

            position: absolute;

            top: 38px;

            left: 38px;

            width: 65px;

            height: 55px;

            background-image:
                radial-gradient(
                    rgba(255,255,255,0.35) 2px,
                    transparent 2px
                );

            background-size: 10px 10px;
        }


        /* =========================================
           ICON
        ========================================= */

        .admin-icon {
    width: 76px;
    height: 76px;

    border: 3px solid rgba(255,255,255,0.95);

    border-radius: 17px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-top: 38px;
    margin-bottom: 27px;

    position: relative;
    z-index: 5;

    overflow: hidden;

    background: rgba(255,255,255,0.12);
}

.admin-icon img {
    width: 100%;
    height: 100%;

    object-fit: contain;

    padding: 6px;
}


        /* =========================================
           LEFT CONTENT
        ========================================= */

        .left-panel h1 {

            position: relative;

            z-index: 5;

            font-size: 40px;

            line-height: 1.15;

            margin-bottom: 24px;

            font-weight: 700;
        }


        .description {

            position: relative;

            z-index: 5;

            max-width: 470px;

            font-size: 17px;

            line-height: 1.7;

            color: rgba(255,255,255,0.95);

            margin-bottom: 24px;
        }


        /* =========================================
           DIVIDER
        ========================================= */

        .divider {

            position: relative;

            z-index: 5;

            width: 45px;

            height: 2px;

            background: rgba(255,255,255,0.8);

            margin-bottom: 26px;
        }


        /* =========================================
           FEATURES
        ========================================= */

        .features {

            position: relative;

            z-index: 5;

            list-style: none;

            display: flex;

            flex-direction: column;

            gap: 17px;
        }


        .features li {

            display: flex;

            align-items: center;

            gap: 13px;

            font-size: 15px;

            color: white;
        }


        .check {

            width: 27px;

            height: 27px;

            min-width: 27px;

            border: 2px solid rgba(255,255,255,0.9);

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 15px;
        }


        /* =========================================
           SCHOOL ILLUSTRATION
        ========================================= */

        .school-illustration {

            position: absolute;

            left: 0;

            bottom: 0;

            width: 100%;

            height: 48%;

            z-index: 2;

            opacity: 0.38;

            pointer-events: none;
        }


        .school-illustration svg {

            width: 100%;

            height: 100%;

            display: block;
        }


        /* =========================================
           RIGHT PANEL
        ========================================= */

        .right-panel {

            width: 50%;

            padding: 55px 70px;

            display: flex;

            flex-direction: column;

            justify-content: center;
        }


        .welcome-title {

            color: #172554;

            font-size: 32px;

            margin-bottom: 9px;
        }


        .welcome-text {

            color: #64748b;

            font-size: 15px;

            line-height: 1.6;

            margin-bottom: 22px;
        }


        /* =========================================
           INFO BOX
        ========================================= */

        .info-box {

            display: flex;

            align-items: flex-start;

            gap: 15px;

            padding: 18px 20px;

            border: 1px solid #d6e4ff;

            border-radius: 10px;

            background: #f2f7ff;

            margin-bottom: 22px;
        }


        .info-icon {

            width: 42px;

            height: 42px;

            min-width: 42px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #1769ff;

            color: white;

            font-size: 19px;
        }


        .info-box strong {

            display: block;

            color: #172554;

            font-size: 15px;

            margin-bottom: 5px;
        }


        .info-box span {

            color: #64748b;

            font-size: 13px;

            line-height: 1.6;
        }


        /* =========================================
           QR CODE
        ========================================= */

        .qr-wrapper {

            display: flex;

            justify-content: center;

            align-items: center;

            margin: 4px 0 20px;
        }


        .qr-box {

            width: 210px;

            height: 210px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #ffffff;

            border: 1px solid #dbe3ef;

            border-radius: 10px;

            padding: 8px;
        }


        .qr-box svg {

            width: 190px;

            height: 190px;

            display: block;
        }


        /* =========================================
           SECRET
        ========================================= */

        .secret-label {

            display: block;

            color: #172554;

            font-size: 13px;

            font-weight: 700;

            margin-bottom: 7px;
        }


        .secret {

            background: #f8fafc;

            border: 1px solid #dbe3ef;

            border-radius: 7px;

            padding: 11px 13px;

            color: #475569;

            font-family: monospace;

            font-size: 12px;

            text-align: center;

            word-break: break-all;

            margin-bottom: 18px;
        }


        /* =========================================
           ERROR MESSAGE
        ========================================= */

        .error-box {

            display: flex;

            align-items: center;

            gap: 10px;

            background: #fff1f2;

            border: 1px solid #fecdd3;

            color: #dc2626;

            padding: 13px 15px;

            border-radius: 7px;

            font-size: 13px;

            margin-bottom: 18px;
        }


        /* =========================================
           FORM
        ========================================= */

        .form-group {

            margin-bottom: 18px;
        }


        .form-group label {

            display: block;

            color: #172554;

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 8px;
        }


        .code-input {

            width: 100%;

            height: 60px;

            border: 1px solid #dbe3ef;

            border-radius: 8px;

            text-align: center;

            font-size: 27px;

            letter-spacing: 14px;

            color: #172554;

            outline: none;

            padding-left: 14px;

            transition: 0.2s;
        }


        .code-input::placeholder {

            color: #94a3b8;

            letter-spacing: 14px;
        }


        .code-input:focus {

            border-color: #2477f9;

            box-shadow:
                0 0 0 3px rgba(36,119,249,0.10);
        }


        /* =========================================
           VERIFY BUTTON
        ========================================= */

        .login-button {

            width: 100%;

            height: 54px;

            border: none;

            border-radius: 8px;

            background:
                linear-gradient(
                    90deg,
                    #1769ff,
                    #1da5df
                );

            color: white;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 8px 20px rgba(23,105,255,0.25);

            transition: all 0.2s ease;

            margin-top: 4px;
        }


        .login-button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(23,105,255,0.32);
        }


        .login-button span {

            margin-left: 8px;

            font-size: 18px;
        }


        /* =========================================
           HELP TEXT
        ========================================= */

        .help-text {

            display: flex;

            gap: 10px;

            margin-top: 20px;

            color: #64748b;

            font-size: 12px;

            line-height: 1.6;
        }


        .help-icon {

            min-width: 20px;

            font-size: 15px;

            color: #94a3b8;
        }


        /* =========================================
           FOOTER
        ========================================= */

        .system-name {

            text-align: center;

            margin-top: 20px;

            color: #94a3b8;

            font-size: 12px;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 900px) {

            body {

                padding: 20px;
            }


            .left-panel {

                display: none;
            }


            .right-panel {

                width: 100%;

                padding: 55px 50px;
            }


            .login-container {

                max-width: 550px;
            }
        }


        @media (max-width: 500px) {

            body {

                padding: 10px;
            }


            .right-panel {

                padding: 40px 25px;
            }


            .welcome-title {

                font-size: 27px;
            }


            .code-input {

                font-size: 22px;

                letter-spacing: 9px;
            }


            .qr-box {

                width: 190px;

                height: 190px;
            }


            .qr-box svg {

                width: 170px;

                height: 170px;
            }
        }

    </style>

</head>


<body>


<div class="login-container">


    <!-- =====================================================
         LEFT PANEL
    ====================================================== -->

    <div class="left-panel">


        <div class="top-circle"></div>

        <div class="bottom-circle"></div>

        <div class="dots"></div>


        <div class="admin-icon">
    <img
        src="{{ asset('images/gurukullogo.png') }}"
        alt="Gurukul Vidyalaya Logo"
    >
</div>


        <h1>
            Gurukul Vidyalaya
        </h1>


        <p class="description">

            Secure school management
            with an additional layer of
            two-factor authentication.

        </p>


        <div class="divider"></div>


        <ul class="features">


            <li>

                <span class="check">
                    ✓
                </span>

                Secure administrator access

            </li>


            <li>

                <span class="check">
                    ✓
                </span>

                Google Authenticator support

            </li>


            <li>

                <span class="check">
                    ✓
                </span>

                Time-based security codes

            </li>


            <li>

                <span class="check">
                    ✓
                </span>

                Enhanced account protection

            </li>


        </ul>


        <!-- SCHOOL ILLUSTRATION -->

        <div class="school-illustration">

            <svg
                viewBox="0 0 600 300"
                preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg"
            >

                <!-- Ground -->

                <path
                    d="M0 270
                       Q120 250 220 270
                       T420 265
                       T600 270
                       V300
                       H0Z"
                    fill="white"
                />


                <!-- Main building -->

                <rect
                    x="170"
                    y="125"
                    width="260"
                    height="145"
                    rx="3"
                    fill="white"
                />


                <!-- Roof -->

                <path
                    d="M145 130
                       L300 45
                       L455 130
                       Z"
                    fill="white"
                />


                <!-- Entrance -->

                <rect
                    x="270"
                    y="190"
                    width="60"
                    height="80"
                    fill="#1769ff"
                />


                <!-- Door top -->

                <path
                    d="M270 190
                       Q300 160 330 190
                       Z"
                    fill="#1769ff"
                />


                <!-- Windows -->

                <rect
                    x="200"
                    y="155"
                    width="42"
                    height="42"
                    fill="#1769ff"
                />

                <rect
                    x="358"
                    y="155"
                    width="42"
                    height="42"
                    fill="#1769ff"
                />


                <rect
                    x="200"
                    y="215"
                    width="42"
                    height="35"
                    fill="#1769ff"
                />

                <rect
                    x="358"
                    y="215"
                    width="42"
                    height="35"
                    fill="#1769ff"
                />


                <!-- Clock -->

                <circle
                    cx="300"
                    cy="103"
                    r="24"
                    fill="white"
                />

                <circle
                    cx="300"
                    cy="103"
                    r="20"
                    fill="#1769ff"
                />

                <line
                    x1="300"
                    y1="103"
                    x2="300"
                    y2="91"
                    stroke="white"
                    stroke-width="3"
                />

                <line
                    x1="300"
                    y1="103"
                    x2="311"
                    y2="108"
                    stroke="white"
                    stroke-width="3"
                />


                <!-- Trees -->

                <circle
                    cx="105"
                    cy="205"
                    r="35"
                    fill="white"
                />

                <rect
                    x="98"
                    y="230"
                    width="14"
                    height="40"
                    fill="white"
                />


                <circle
                    cx="495"
                    cy="205"
                    r="35"
                    fill="white"
                />

                <rect
                    x="488"
                    y="230"
                    width="14"
                    height="40"
                    fill="white"
                />


                <!-- Clouds -->

                <circle
                    cx="75"
                    cy="155"
                    r="20"
                    fill="white"
                />

                <circle
                    cx="100"
                    cy="145"
                    r="28"
                    fill="white"
                />

                <circle
                    cx="130"
                    cy="155"
                    r="20"
                    fill="white"
                />


                <circle
                    cx="470"
                    cy="125"
                    r="18"
                    fill="white"
                />

                <circle
                    cx="495"
                    cy="115"
                    r="25"
                    fill="white"
                />

                <circle
                    cx="525"
                    cy="125"
                    r="18"
                    fill="white"
                />

            </svg>

        </div>


    </div>



    <!-- =====================================================
         RIGHT PANEL
    ====================================================== -->

    <div class="right-panel">


        <h2 class="welcome-title">
            Set Up Two-Factor Authentication
        </h2>


        <p class="welcome-text">

            Scan this QR code using
            <strong>Google Authenticator</strong>
            or another authenticator app.

        </p>


        <!-- INFO -->

        <div class="info-box">

            <div class="info-icon">
                🔐
            </div>

            <div>

                <strong>
                    Secure your administrator account
                </strong>

                <span>
                    Scan the QR code below to connect
                    your authenticator app with
                    Gurukul Vidyalaya.
                </span>

            </div>

        </div>


        <!-- QR CODE -->

        <div class="qr-wrapper">

            <div class="qr-box">

                {!! $qrCodeUrl !!}

            </div>

        </div>


        <!-- SECRET -->

        <span class="secret-label">

            Can't scan the QR code?

        </span>


        <div class="secret">

            {{ $secret }}

        </div>


        <!-- ERRORS -->

        @if ($errors->any())

            <div class="error-box">

                <span>!</span>

                {{ $errors->first() }}

            </div>

        @endif


        <!-- VERIFICATION FORM -->

        <form
            method="POST"
            action="{{ route('admin.2fa.verify') }}"
        >

            @csrf


            <div class="form-group">

                <label for="code">

                    Enter the 6-digit code
                    from Google Authenticator

                </label>


                <input
                    id="code"
                    class="code-input"
                    type="text"
                    name="code"
                    inputmode="numeric"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    placeholder="000000"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-button"
            >

                Verify & Continue

                <span>
                    →
                </span>

            </button>


        </form>


        <div class="help-text">

            <div class="help-icon">
                🔒
            </div>

            <div>
                Your authentication code changes
                every 30 seconds. Keep your
                authenticator app secure.
            </div>

        </div>


        <div class="system-name">

            Gurukul Vidyalaya · School Management System

        </div>


    </div>


</div>


</body>

</html>