<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Set Up 2FA | Gurukul Vidyalaya</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f6fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #102a56;
        }

        .page-wrapper {
            width: 100%;
            max-width: 1100px;
        }

        .auth-container {
            width: 100%;
            min-height: 680px;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 12px 40px rgba(25, 65, 120, 0.12);
        }

        /* =========================================
           LEFT SIDE
        ========================================= */

        .left-panel {
            width: 50%;
            position: relative;
            overflow: hidden;
            background: linear-gradient(
                145deg,
                #176df5 0%,
                #159ad9 55%,
                #29b7c2 100%
            );
            color: white;
            padding: 42px 38px;
            display: flex;
            flex-direction: column;
        }

        /* Decorative dots */

        .dots {
            position: absolute;
            top: 28px;
            left: 28px;
            width: 80px;
            height: 70px;

            background-image: radial-gradient(
                rgba(255,255,255,0.35) 2px,
                transparent 2px
            );

            background-size: 10px 10px;
            opacity: 0.8;
        }

        /* Decorative circle */

        .blue-circle {
            position: absolute;
            top: -90px;
            right: -80px;

            width: 210px;
            height: 210px;

            border-radius: 50%;
            background: rgba(255,255,255,0.12);
        }

        /* Logo */

        .logo-box {
            width: 120px;
            height: 120px;

            background: #ffffff;
            border-radius: 25px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-top: 28px;
            margin-bottom: 32px;

            position: relative;
            z-index: 5;

            box-shadow: 0 8px 25px rgba(0,0,0,0.10);
        }

        .logo-box img {
            width: 92px;
            height: 92px;
            object-fit: contain;
            border-radius: 10px;
        }

        .left-title {
            position: relative;
            z-index: 5;
            font-size: 31px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .left-subtitle {
            position: relative;
            z-index: 5;

            font-size: 15px;
            line-height: 1.6;

            max-width: 300px;

            color: rgba(255,255,255,0.92);

            margin-bottom: 30px;
        }

        /* Features */

        .features {
            position: relative;
            z-index: 5;

            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 14px;

            font-size: 14px;
            color: #ffffff;
        }

        .check {
            width: 32px;
            height: 32px;

            border: 2px solid rgba(255,255,255,0.95);
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;
            flex-shrink: 0;
        }

        /* Simple school illustration */

        .school-scene {
            position: absolute;
            bottom: 0;
            left: 0;

            width: 100%;
            height: 290px;

            opacity: 0.28;
        }

        .ground {
            position: absolute;
            bottom: 0;
            left: 0;

            width: 100%;
            height: 115px;

            background: rgba(255,255,255,0.20);

            border-radius: 50% 50% 0 0;
        }

        .school {
            position: absolute;

            bottom: 18px;
            left: 17%;

            width: 66%;
            height: 125px;

            background: rgba(255,255,255,0.65);
        }

        .school-roof {
            position: absolute;

            bottom: 143px;
            left: 27%;

            width: 46%;
            height: 75px;

            background: rgba(255,255,255,0.72);

            clip-path: polygon(
                50% 0,
                100% 100%,
                0 100%
            );
        }

        .school-door {
            position: absolute;

            bottom: 18px;
            left: 46%;

            width: 8%;
            height: 72px;

            background: rgba(35,130,220,0.45);
        }

        .school-window {
            position: absolute;

            bottom: 65px;

            width: 38px;
            height: 45px;

            background: rgba(35,130,220,0.38);
        }

        .window-one {
            left: 25%;
        }

        .window-two {
            left: 35%;
        }

        .window-three {
            right: 35%;
        }

        .window-four {
            right: 25%;
        }

        /* =========================================
           RIGHT SIDE
        ========================================= */

        .right-panel {
            width: 50%;
            padding: 48px 58px;

            display: flex;
            flex-direction: column;
            justify-content: center;

            background: #ffffff;
        }

        .right-panel h1 {
            font-size: 29px;
            line-height: 1.2;

            color: #102a56;

            margin-bottom: 9px;
        }

        .intro {
            color: #6c7f9c;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        /* Error */

        .error-box {
            background: #fff0f1;
            border: 1px solid #ffc7cc;

            color: #dc3545;

            padding: 12px 14px;
            border-radius: 7px;

            font-size: 13px;

            margin-bottom: 20px;
        }

        /* QR */

        .qr-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;

            margin: 4px 0 18px;
        }

        .qr-box {
            width: 190px;
            height: 190px;

            border: 1px solid #e0e6ef;
            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #ffffff;

            padding: 12px;

            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .qr-box svg,
        .qr-box img {
            max-width: 100%;
            max-height: 100%;
        }

        /* Secret */

        .secret-title {
            font-size: 12px;
            font-weight: 700;
            color: #102a56;

            margin-bottom: 7px;
        }

        .secret {
            background: #f5f7fa;

            border: 1px solid #e1e6ee;

            border-radius: 7px;

            padding: 11px 13px;

            font-family: monospace;
            font-size: 12px;

            color: #536783;

            word-break: break-all;

            margin-bottom: 18px;
        }

        /* Instructions */

        .instruction {
            color: #687b96;

            font-size: 13px;
            line-height: 1.55;

            margin-bottom: 16px;
        }

        /* OTP */

        .otp-input {
            width: 100%;

            height: 52px;

            border: 1px solid #d6dfeb;
            border-radius: 8px;

            outline: none;

            font-size: 22px;
            font-weight: 600;

            color: #102a56;

            text-align: center;

            letter-spacing: 8px;

            margin-bottom: 14px;

            transition: 0.2s;
        }

        .otp-input:focus {
            border-color: #2377f5;

            box-shadow: 0 0 0 3px rgba(35,119,245,0.10);
        }

        .otp-input::placeholder {
            color: #a9b5c5;
            letter-spacing: 5px;
        }

        /* Button */

        .verify-button {
            width: 100%;

            height: 49px;

            border: none;
            border-radius: 7px;

            background: linear-gradient(
                90deg,
                #2865f3,
                #159dd9
            );

            color: #ffffff;

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;

            box-shadow: 0 6px 15px rgba(40,101,243,0.20);
        }

        .verify-button:hover {
            transform: translateY(-1px);

            box-shadow: 0 8px 18px rgba(40,101,243,0.28);
        }

        /* Footer */

        .security-note {
            text-align: center;

            margin-top: 18px;

            font-size: 11px;

            color: #9aa8ba;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 850px) {

            body {
                padding: 15px;
            }

            .auth-container {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                width: 100%;
            }

            .left-panel {
                min-height: 390px;
                padding: 30px;
            }

            .logo-box {
                width: 90px;
                height: 90px;
                border-radius: 18px;
                margin-bottom: 20px;
            }

            .logo-box img {
                width: 70px;
                height: 70px;
            }

            .left-title {
                font-size: 25px;
            }

            .features {
                gap: 12px;
            }

            .feature {
                font-size: 13px;
            }

            .right-panel {
                padding: 35px 30px;
            }
        }

        @media (max-width: 500px) {

            .left-panel {
                min-height: 350px;
            }

            .right-panel {
                padding: 30px 20px;
            }

            .right-panel h1 {
                font-size: 25px;
            }

            .qr-box {
                width: 170px;
                height: 170px;
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    <div class="auth-container">

        <!-- =====================================
             LEFT PANEL
        ====================================== -->

        <div class="left-panel">

            <div class="dots"></div>

            <div class="blue-circle"></div>

            <!-- Gurukul Vidyalaya Logo -->

            <div class="logo-box">
                <img
                    src="{{ asset('images/gurukullogo.png') }}"
                    alt="Gurukul Vidyalaya Logo"
                >
            </div>

            <h2 class="left-title">
                Gurukul Vidyalaya
            </h2>

            <p class="left-subtitle">
                Secure school management for
                administrators, teachers and staff.
            </p>

            <div class="features">

                <div class="feature">
                    <div class="check">✓</div>
                    <span>Manage students and teachers</span>
                </div>

                <div class="feature">
                    <div class="check">✓</div>
                    <span>Manage attendance and fees</span>
                </div>

                <div class="feature">
                    <div class="check">✓</div>
                    <span>View school results and reports</span>
                </div>

                <div class="feature">
                    <div class="check">✓</div>
                    <span>Protected with two-factor authentication</span>
                </div>

            </div>

            <!-- School illustration -->

            <div class="school-scene">

                <div class="ground"></div>

                <div class="school"></div>

                <div class="school-roof"></div>

                <div class="school-door"></div>

                <div class="school-window window-one"></div>
                <div class="school-window window-two"></div>
                <div class="school-window window-three"></div>
                <div class="school-window window-four"></div>

            </div>

        </div>


        <!-- =====================================
             RIGHT PANEL
        ====================================== -->

        <div class="right-panel">

            <h1>
                Set Up Two-Factor Authentication
            </h1>

            <p class="intro">
                Secure your Gurukul Vidyalaya account by
                connecting it with Google Authenticator.
            </p>


            @if ($errors->any())

                <div class="error-box">
                    {{ $errors->first() }}
                </div>

            @endif


            <p class="instruction">
                Open Google Authenticator on your mobile
                and scan the QR code below.
            </p>


            <!-- QR CODE -->

            <div class="qr-wrapper">

                <div class="qr-box">

                    {!! $qrCodeUrl !!}

                </div>

            </div>


            <p class="instruction">
                Can't scan the QR code? Enter the secret
                key manually in your authenticator app.
            </p>


            <!-- SECRET -->

            <div class="secret-title">
                Manual Setup Key
            </div>

            <div class="secret">
                {{ $secret }}
            </div>


            <p class="instruction">
                After adding the account, enter the
                6-digit verification code generated by
                Google Authenticator.
            </p>


            <!-- VERIFY FORM -->

            <form
                method="POST"
                action="{{ route('admin.2fa.verify') }}"
            >

                @csrf

                <input
                    type="text"
                    name="code"
                    class="otp-input"
                    inputmode="numeric"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    placeholder="000000"
                    required
                >

                <button
                    type="submit"
                    class="verify-button"
                >
                    Verify & Continue →
                </button>

            </form>


            <div class="security-note">
                🔒 Your account is protected with two-factor authentication.
            </div>

        </div>

    </div>

</div>

</body>
</html>