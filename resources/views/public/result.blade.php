
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Check Result | Student Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --forest: #20583b;
            --forest-dark: #17432d;
            --forest-light: #eaf2ec;
            --cream: #f5f3e9;
            --gold: #c49a45;
            --text: #263b35;
            --muted: #78847d;
            --border: #e3e8df;
        }

        * {
            box-sizing: border-box;
        }

        html {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
    width: 100%;
    min-height: 100vh;
    min-height: 100svh;
    margin: 0;
    padding: 18px;
    display: flex;
    align-items: safe center;
    justify-content: center;
    font-family: "Segoe UI", Arial, sans-serif;
    background:
        radial-gradient(circle at top left, rgba(196,154,69,.12), transparent 35%),
        linear-gradient(135deg, #f5f3e9, #edf2ec);
    color: var(--text);
    overflow-x: hidden;
}

        /* MAIN WRAPPER */

        .result-wrapper {
            width: 100%;
            max-width: 680px;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 38px rgba(31,65,46,.12);
            border: 1px solid rgba(32,88,59,.08);
        }

        /* TOP HEADER */

        .result-info-panel {
            position: relative;
            padding: 22px 28px;
            overflow: hidden;
            background: linear-gradient(135deg, #286044, #17432d);
            color: #fff;
        }

        .result-info-panel::before,
        .result-info-panel::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 50%;
            pointer-events: none;
        }

        .result-info-panel::before {
            width: 250px;
            height: 250px;
            right: -85px;
            top: -175px;
        }

        .result-info-panel::after {
            width: 180px;
            height: 180px;
            right: 120px;
            bottom: -150px;
        }

        .info-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 55px minmax(0, 1fr);
            column-gap: 16px;
            align-items: center;
        }

        .info-emblem {
            width: 55px;
            height: 55px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.08);
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            color: #e5c47d;
            margin: 0;
            grid-row: span 3;
        }

        .info-eyebrow {
            color: #e5c47d;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .info-title {
            font-family: Georgia, serif;
            font-size: 21px;
            line-height: 1.3;
            font-weight: 700;
            margin: 0 0 5px;
            overflow-wrap: anywhere;
        }

        .info-description {
            color: rgba(255,255,255,.78);
            font-size: 11px;
            line-height: 1.6;
            margin: 0;
        }

        /* FORM PANEL */

        .result-form-panel {
            padding: 25px 32px 26px;
        }

        .portal-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--forest);
            background: var(--forest-light);
            border: 1px solid #dce9df;
            padding: 6px 11px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 13px;
        }

        .portal-label i {
            color: var(--gold);
            font-size: 13px;
        }

        .result-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: var(--forest-light);
            color: var(--forest);
            font-size: 22px;
            margin-bottom: 11px;
            border: 1px solid #dce9df;
        }

        .result-title {
            font-family: Georgia, serif;
            color: var(--forest-dark);
            font-size: 25px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .result-subtitle {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        /* DIVIDER */

        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: var(--forest);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .section-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* FORM */

        .form-label {
            color: #344d40;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .input-group-custom {
            position: relative;
            width: 100%;
        }

        .input-group-custom > i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #829087;
            font-size: 15px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            min-width: 0;
            height: 44px;
            border-radius: 8px !important;
            border: 1px solid #dfe6df;
            background: #fbfcfa;
            padding: 9px 12px 9px 39px;
            color: var(--text);
            font-size: 13px;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: var(--forest);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(32,88,59,.08) !important;
        }

        .form-control::placeholder {
            color: #a1aaa4;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-size: 11px;
        }

        /* CAPTCHA */

        .captcha-box {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            margin-bottom: 9px;
        }

        .captcha-code {
            flex: 1;
            min-width: 0;
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: repeating-linear-gradient(
                135deg,
                #f8f9f5,
                #f8f9f5 8px,
                #eaf0e9 8px,
                #eaf0e9 16px
            );
            border: 1px dashed #b8c8ba;
            border-radius: 8px;
            color: var(--forest-dark);
            font-family: "Courier New", monospace;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 4px;
            text-decoration: line-through;
            text-decoration-color: #aab6ac;
            user-select: none;
            overflow-wrap: anywhere;
        }

        .captcha-refresh {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 8px;
            border: 1px solid #dce5dc;
            background: var(--forest-light);
            color: var(--forest);
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .2s ease;
        }

        .captcha-refresh:hover {
            background: var(--forest);
            color: #fff;
        }

        .captcha-refresh:focus-visible,
        .btn-result:focus-visible {
            outline: 3px solid rgba(196,154,69,.45);
            outline-offset: 3px;
        }

        .captcha-help {
            font-size: 10px;
            color: var(--muted);
            margin-top: 6px;
            line-height: 1.5;
        }

        /* BUTTON */

        .btn-result {
            width: 100%;
            min-height: 46px;
            border: none;
            border-radius: 8px;
            background: var(--forest);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .2px;
            margin-top: 5px;
            transition: .2s ease;
            box-shadow: 0 4px 12px rgba(32,88,59,.15);
        }

        .btn-result:hover {
            background: var(--forest-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ALERTS */

        .alert {
            border-radius: 8px;
            font-size: 12px;
            padding: 9px 12px;
            margin-bottom: 13px;
            overflow-wrap: anywhere;
        }

        /* FOOTER */

        .form-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 10px;
            text-align: center;
            line-height: 1.5;
        }

        .form-footer i {
            color: var(--gold);
            flex-shrink: 0;
        }

        /* TABLET */

        @media (min-width: 600px) and (max-width: 991px) {
            body {
                padding: 25px;
            }

            .result-wrapper {
                max-width: 620px;
            }

            .result-info-panel {
                padding: 21px 25px;
            }

            .result-form-panel {
                padding: 24px 30px;
            }
        }

        /* MOBILE */

        @media (max-width: 599px) {
            body {
                padding: 12px;
                align-items: flex-start;
            }

            .result-wrapper {
                width: 100%;
                max-width: 100%;
                border-radius: 13px;
            }

            .result-info-panel {
                padding: 17px 15px;
            }

            .info-content {
                grid-template-columns: 44px minmax(0, 1fr);
                column-gap: 11px;
            }

            .info-emblem {
                width: 44px;
                height: 44px;
                font-size: 20px;
                border-radius: 10px;
            }

            .info-eyebrow {
                font-size: 9px;
                letter-spacing: 1.2px;
            }

            .info-title {
                font-size: 17px;
                line-height: 1.3;
            }

            .info-description {
                font-size: 10px;
                line-height: 1.5;
            }

            .result-form-panel {
                padding: 19px 17px 20px;
            }

            .portal-label {
                font-size: 9px;
                padding: 5px 9px;
            }

            .result-icon {
                width: 40px;
                height: 40px;
                font-size: 19px;
                margin-bottom: 9px;
            }

            .result-title {
                font-size: 22px;
            }

            .result-subtitle {
                font-size: 11px;
                margin-bottom: 15px;
            }

            .section-divider {
                font-size: 10px;
                margin-bottom: 13px;
            }

            .form-label {
                font-size: 11px;
            }

            .form-control {
                height: 43px;
                font-size: 12px;
            }

            .captcha-code {
                min-height: 43px;
                font-size: 18px;
                letter-spacing: 3px;
            }

            .captcha-refresh {
                width: 42px;
                height: 42px;
            }

            .captcha-help {
                font-size: 9px;
            }

            .btn-result {
                min-height: 44px;
                font-size: 12px;
            }

            .form-footer {
                font-size: 9px;
            }
        }

        /* VERY SMALL MOBILE */

        @media (max-width: 360px) {
            body {
                padding: 8px;
            }

            .result-info-panel {
                padding: 14px 11px;
            }

            .info-content {
                grid-template-columns: 38px minmax(0, 1fr);
                column-gap: 9px;
            }

            .info-emblem {
                width: 38px;
                height: 38px;
                font-size: 18px;
            }

            .info-title {
                font-size: 15px;
            }

            .info-description {
                font-size: 9px;
            }

            .result-form-panel {
                padding: 16px 12px 17px;
            }

            .result-title {
                font-size: 20px;
            }

            .captcha-code {
                font-size: 16px;
                letter-spacing: 2px;
            }

            .btn-result {
                font-size: 11px;
            }
        }

        /* SHORT SCREEN / LANDSCAPE */

        @media (max-height: 600px) and (min-width: 600px) {
            body {
                align-items: flex-start;
                padding-top: 15px;
                padding-bottom: 15px;
            }
        }

        /* ACCESSIBILITY */

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }

        /* BETTER FIT AT 100% BROWSER ZOOM */

.result-wrapper {
    max-width: 680px;
    margin: 0 auto;
}

.result-info-panel {
    padding: 16px 24px;
}

.info-emblem {
    width: 48px;
    height: 48px;
    font-size: 22px;
}

.info-title {
    font-size: 19px;
}

.result-form-panel {
    padding: 18px 28px 20px;
}

.result-icon {
    width: 40px;
    height: 40px;
    margin-bottom: 8px;
}

.result-title {
    font-size: 23px;
}

.result-subtitle {
    margin-bottom: 13px;
}

.section-divider {
    margin-bottom: 11px;
}

.form-control {
    height: 41px;
}

.captcha-code {
    min-height: 42px;
}

.captcha-refresh {
    width: 41px;
    height: 41px;
}

.btn-result {
    min-height: 43px;
}

.form-footer {
    margin-top: 11px;
    padding-top: 9px;
}

/* MOBILE */

@media (max-width: 599px) {
    body {
        padding: 10px;
        align-items: flex-start;
    }

    .result-form-panel {
        padding: 17px 15px;
    }

    .result-info-panel {
        padding: 15px;
    }
}

/* SHORT DESKTOP SCREENS */

@media (min-width: 600px) and (max-height: 800px) {
    body {
        align-items: flex-start;
        padding-top: 12px;
        padding-bottom: 12px;
    }
}
    </style>
</head>

<body>

<div class="result-wrapper">

    <!-- TOP GREEN ACADEMIC HEADER -->

    <div class="result-info-panel">

        <div class="info-content">

            <div class="info-emblem">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div class="info-eyebrow">
                Academic Excellence
            </div>

            <h3 class="info-title">
                Your Learning Journey, Your Achievement.
            </h3>

            <p class="info-description">
                Every examination reflects your dedication and progress.
                Access your academic performance through our secure
                student result portal.
            </p>

        </div>

    </div>

    <!-- RESULT FORM -->

    <div class="result-form-panel">

        <div class="portal-label">
            <i class="bi bi-mortarboard-fill"></i>
            Student Portal
        </div>

        <div class="result-icon">
            <i class="bi bi-journal-check"></i>
        </div>

        <h2 class="result-title">
            Check Your Result
        </h2>

        <p class="result-subtitle">
            Welcome to the student result portal. Enter your details below
            to securely access your examination result.
        </p>

        <!-- ERROR MESSAGE -->

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- SUCCESS MESSAGE -->

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="section-divider">
            <span>Student Verification</span>
        </div>

        <!-- RESULT FORM -->

        <form method="POST" action="{{ route('result.public.search') }}">

            @csrf

            <!-- STUDENT ID -->

            <div class="mb-3">

                <label for="student_id" class="form-label">
                    Student ID
                </label>

                <div class="input-group-custom">

                    <i class="bi bi-person-badge"></i>

                    <input
                        type="text"
                        id="student_id"
                        name="student_id"
                        value="{{ old('student_id') }}"
                        class="form-control @error('student_id') is-invalid @enderror"
                        placeholder="Enter your Student ID"
                        autocomplete="off"
                        required
                    >

                </div>

                @error('student_id')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- MOTHER'S NAME -->

            <div class="mb-4">

                <label for="mother_name" class="form-label">
                    Mother's Name
                </label>

                <div class="input-group-custom">

                    <i class="bi bi-person-heart"></i>

                    <input
                        type="text"
                        id="mother_name"
                        name="mother_name"
                        value="{{ old('mother_name') }}"
                        class="form-control @error('mother_name') is-invalid @enderror"
                        placeholder="Enter Mother's Name"
                        autocomplete="off"
                        required
                    >

                </div>

                @error('mother_name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- CAPTCHA -->

            <div class="mb-4">

                <label for="captcha" class="form-label">
                    Security Verification
                </label>

                <div class="captcha-box">

                    <div class="captcha-code" id="captchaCode">
                        {{ $captcha }}
                    </div>

                    <button
                        type="button"
                        class="captcha-refresh"
                        onclick="refreshCaptcha()"
                        title="Refresh CAPTCHA"
                        aria-label="Refresh CAPTCHA"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>

                </div>

                <div class="input-group-custom">

                    <i class="bi bi-shield-lock"></i>

                    <input
                        type="text"
                        id="captcha"
                        name="captcha"
                        class="form-control @error('captcha') is-invalid @enderror"
                        placeholder="Enter CAPTCHA characters"
                        autocomplete="off"
                        maxlength="8"
                        required
                    >

                </div>

                <div class="captcha-help">
                    <i class="bi bi-info-circle me-1"></i>
                    Enter the characters exactly as displayed. CAPTCHA
                    contains letters, numbers and special symbols.
                </div>

                @error('captcha')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- SUBMIT BUTTON -->

            <button type="submit" class="btn btn-result">

                <i class="bi bi-search me-2"></i>
                View Examination Result
                <i class="bi bi-arrow-right ms-2"></i>

            </button>

        </form>

        <!-- FOOTER -->

        <div class="form-footer">
            <i class="bi bi-shield-lock-fill"></i>
            Your information is used for secure student verification.
        </div>

    </div>

</div>

<!-- CAPTCHA REFRESH -->

<script>
    function refreshCaptcha() {

        const captchaCode = document.getElementById('captchaCode');
        const captchaInput = document.getElementById('captcha');

        fetch("{{ route('result.public.refresh-captcha') }}", {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })

        .then(response => {

            if (!response.ok) {
                throw new Error('Unable to refresh CAPTCHA.');
            }

            return response.text();

        })

        .then(raw => {

            // Remove a possible BOM before parsing JSON.
            const data = JSON.parse(raw.replace(/^\uFEFF/, '').trim());

            if (data.captcha) {
                captchaCode.innerText = data.captcha;
                captchaInput.value = '';
            }

        })

        .catch(error => {

            console.error('CAPTCHA refresh failed:', error);

        });

    }
</script>

</body>
</html>
