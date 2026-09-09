<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | School Management System</title>

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
           MAIN LOGIN CONTAINER
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
           SCHOOL LOGO
        ========================================= */

        .admin-logo {
            width: 96px;
            height: 96px;

            border: 3px solid rgba(255,255,255,0.95);

            border-radius: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-top: 38px;
            margin-bottom: 27px;

            position: relative;
            z-index: 5;

            background: white;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(0,0,0,0.12);
        }

        .admin-logo img {
            width: 100%;
            height: 100%;

            object-fit: contain;

            padding: 8px;

            display: block;
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

            padding: 65px 75px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-title {
            color: #172554;

            font-size: 38px;

            margin-bottom: 9px;
        }

        .welcome-text {
            color: #64748b;

            font-size: 15px;

            margin-bottom: 28px;
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

            margin-bottom: 23px;
        }


        /* =========================================
           FORM
        ========================================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            color: #172554;

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;

            left: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #64748b;

            font-size: 17px;

            z-index: 2;
        }

        .input-wrapper input {
            width: 100%;
            height: 54px;

            border: 1px solid #dbe3ef;

            border-radius: 8px;

            padding: 0 48px;

            font-size: 15px;

            color: #334155;

            outline: none;

            transition: 0.2s;
        }

        .input-wrapper input::placeholder {
            color: #94a3b8;
        }

        .input-wrapper input:focus {
            border-color: #2477f9;

            box-shadow:
                0 0 0 3px rgba(36,119,249,0.10);
        }


        /* =========================================
           PASSWORD TOGGLE
        ========================================= */

        .toggle-password {
            position: absolute;

            right: 15px;
            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            cursor: pointer;

            color: #64748b;

            font-size: 16px;
        }


        /* =========================================
           REMEMBER + FORGOT
        ========================================= */

        .form-options {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 27px;

            font-size: 13px;
        }

        .remember {
            display: flex;

            align-items: center;

            gap: 7px;

            color: #64748b;
        }

        .remember input {
            width: 15px;
            height: 15px;

            accent-color: #1769ff;
        }

        .forgot {
            color: #1769ff;

            text-decoration: none;

            font-weight: 600;
        }

        .forgot:hover {
            text-decoration: underline;
        }


        /* =========================================
           SIGN IN BUTTON
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
           FOOTER
        ========================================= */

        .system-name {
            text-align: center;

            margin-top: 25px;

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
                font-size: 30px;
            }

            .form-options {
                flex-direction: column;

                align-items: flex-start;

                gap: 12px;
            }
        }

    </style>

</head>


<body>

<div class="login-container">


    <!-- ==================================================
         LEFT SIDE
    =================================================== -->

    <div class="left-panel">


        <!-- Decorative elements -->

        <div class="dots"></div>

        <div class="top-circle"></div>

        <div class="bottom-circle"></div>


        <!-- ==================================================
             SCHOOL LOGO
        =================================================== -->

        <div class="admin-logo">

            <img
                src="{{ asset('images/gurukullogo.png') }}"
                alt="Gurukul Vidyalaya Logo"
            >

        </div>


        <!-- Heading -->

        <h1>
            Gurukul Vidyalaya
        </h1>


        <!-- Description -->

        <p class="description">

            Secure login for authorized school administrators only.

            Manage your entire school from one powerful dashboard.

        </p>


        <!-- Divider -->

        <div class="divider"></div>


        <!-- Features -->

        <ul class="features">

            <li>

                <span class="check">
                    ✓
                </span>

                <span>
                    Manage students and teachers
                </span>

            </li>


            <li>

                <span class="check">
                    ✓
                </span>

                <span>
                    Manage attendance and fees
                </span>

            </li>


            <li>

                <span class="check">
                    ✓
                </span>

                <span>
                    View school results and reports
                </span>

            </li>


            <li>

                <span class="check">
                    ✓
                </span>

                <span>
                    Protected with two-factor authentication
                </span>

            </li>

        </ul>


        <!-- ==================================================
             SCHOOL BUILDING SVG
        =================================================== -->

        <div class="school-illustration">

            <svg
                viewBox="0 0 600 300"
                preserveAspectRatio="xMidYMax meet"
                xmlns="http://www.w3.org/2000/svg"
            >


                <!-- Clouds -->

                <g fill="white">

                    <circle
                        cx="75"
                        cy="170"
                        r="28"
                    />

                    <circle
                        cx="105"
                        cy="155"
                        r="40"
                    />

                    <circle
                        cx="140"
                        cy="170"
                        r="27"
                    />

                    <rect
                        x="55"
                        y="170"
                        width="110"
                        height="25"
                        rx="12"
                    />


                    <circle
                        cx="475"
                        cy="130"
                        r="25"
                    />

                    <circle
                        cx="505"
                        cy="115"
                        r="38"
                    />

                    <circle
                        cx="540"
                        cy="130"
                        r="25"
                    />

                    <rect
                        x="455"
                        y="130"
                        width="110"
                        height="25"
                        rx="12"
                    />

                </g>


                <!-- Ground -->

                <path
                    d="M0 275 Q150 245 300 270 Q450 245 600 275 V300 H0Z"
                    fill="#ffffff"
                    opacity="0.28"
                />


                <!-- Main building -->

                <rect
                    x="115"
                    y="155"
                    width="370"
                    height="125"
                    fill="white"
                />


                <!-- Building side wings -->

                <rect
                    x="70"
                    y="180"
                    width="80"
                    height="100"
                    fill="white"
                />

                <rect
                    x="450"
                    y="180"
                    width="80"
                    height="100"
                    fill="white"
                />


                <!-- Main roof -->

                <path
                    d="M80 160 L300 45 L520 160 Z"
                    fill="white"
                />


                <!-- Roof lower line -->

                <rect
                    x="70"
                    y="158"
                    width="460"
                    height="15"
                    fill="white"
                />


                <!-- Clock -->

                <circle
                    cx="300"
                    cy="103"
                    r="28"
                    fill="white"
                    stroke="#1769ff"
                    stroke-width="5"
                />


                <!-- Clock hands -->

                <line
                    x1="300"
                    y1="103"
                    x2="300"
                    y2="86"
                    stroke="#1769ff"
                    stroke-width="4"
                />

                <line
                    x1="300"
                    y1="103"
                    x2="314"
                    y2="110"
                    stroke="#1769ff"
                    stroke-width="4"
                />


                <!-- Columns -->

                <g
                    fill="#1769ff"
                    opacity="0.45"
                >

                    <rect
                        x="145"
                        y="175"
                        width="24"
                        height="105"
                    />

                    <rect
                        x="205"
                        y="175"
                        width="24"
                        height="105"
                    />

                    <rect
                        x="265"
                        y="175"
                        width="24"
                        height="105"
                    />

                    <rect
                        x="325"
                        y="175"
                        width="24"
                        height="105"
                    />

                    <rect
                        x="385"
                        y="175"
                        width="24"
                        height="105"
                    />

                    <rect
                        x="445"
                        y="175"
                        width="24"
                        height="105"
                    />

                </g>


                <!-- Windows -->

                <g
                    fill="#1769ff"
                    opacity="0.45"
                >

                    <rect
                        x="85"
                        y="205"
                        width="35"
                        height="45"
                    />

                    <rect
                        x="130"
                        y="205"
                        width="35"
                        height="45"
                    />

                    <rect
                        x="435"
                        y="205"
                        width="35"
                        height="45"
                    />

                    <rect
                        x="480"
                        y="205"
                        width="35"
                        height="45"
                    />

                </g>


                <!-- Main entrance -->

                <rect
                    x="265"
                    y="205"
                    width="70"
                    height="75"
                    fill="#1769ff"
                    opacity="0.65"
                />


                <!-- Door -->

                <rect
                    x="278"
                    y="225"
                    width="44"
                    height="55"
                    fill="white"
                    opacity="0.4"
                />

                <circle
                    cx="315"
                    cy="253"
                    r="4"
                    fill="#1769ff"
                />


                <!-- Flag pole -->

                <line
                    x1="300"
                    y1="47"
                    x2="300"
                    y2="15"
                    stroke="white"
                    stroke-width="4"
                />


                <!-- Flag -->

                <path
                    d="M300 15 L345 25 L300 36 Z"
                    fill="white"
                />


                <!-- Trees -->

                <g
                    fill="#1769ff"
                    opacity="0.65"
                >

                    <!-- Left tree -->

                    <rect
                        x="42"
                        y="235"
                        width="15"
                        height="45"
                    />

                    <circle
                        cx="50"
                        cy="215"
                        r="30"
                    />

                    <circle
                        cx="30"
                        cy="230"
                        r="23"
                    />

                    <circle
                        cx="70"
                        cy="230"
                        r="23"
                    />


                    <!-- Right tree -->

                    <rect
                        x="543"
                        y="235"
                        width="15"
                        height="45"
                    />

                    <circle
                        cx="550"
                        cy="215"
                        r="30"
                    />

                    <circle
                        cx="530"
                        cy="230"
                        r="23"
                    />

                    <circle
                        cx="570"
                        cy="230"
                        r="23"
                    />

                </g>

            </svg>

        </div>

    </div>



    <!-- ==================================================
         RIGHT SIDE
    =================================================== -->

    <div class="right-panel">


        <h2 class="welcome-title">
            Welcome Back
        </h2>


        <p class="welcome-text">
            Sign in to access your school dashboard.
        </p>


        <!-- Laravel validation errors -->

        @if ($errors->any())

            <div class="error-box">

                <span>
                    !
                </span>

                <span>
                    {{ $errors->first() }}
                </span>

            </div>

        @endif


        <!-- ==================================================
             LOGIN FORM
        =================================================== -->

        <form
            method="POST"
            action="{{ route('admin.login.submit') }}"
        >

            @csrf


            <!-- Email -->

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>


                <div class="input-wrapper">

                    <span class="input-icon">
                        ✉
                    </span>


                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        required
                        autocomplete="email"
                    >

                </div>

            </div>



            <!-- Password -->

            <div class="form-group">

                <label for="password">
                    Password
                </label>


                <div class="input-wrapper">

                    <span class="input-icon">
                        🔒
                    </span>


                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >


                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword()"
                        id="passwordToggle"
                    >
                        👁
                    </button>

                </div>

            </div>



            <!-- Remember / Forgot -->

            <div class="form-options">


                <label class="remember">

                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >

                    <span>
                        Remember me
                    </span>

                </label>


            </div>



            <!-- Login button -->

            <button
                type="submit"
                class="login-button"
            >

                Sign In

                <span>
                    →
                </span>

            </button>

        </form>


        <!-- Footer -->

        <div class="system-name">
            @ Gurukul_Vidyalaya
        </div>

    </div>

</div>



<script>

    function togglePassword() {

        const password =
            document.getElementById('password');

        const button =
            document.getElementById('passwordToggle');


        if (password.type === 'password') {

            password.type = 'text';

            button.textContent = '🙈';

        } else {

            password.type = 'password';

            button.textContent = '👁';

        }

    }

</script>

</body>

</html>