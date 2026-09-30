
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Check Result</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        body {
            min-height: 100vh;
            background: #f4f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .result-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .result-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e8f1ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            color: #1677f0;
        }

        .result-title {
            text-align: center;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .result-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            min-height: 48px;
            border-radius: 10px;
        }

        .btn-result {
            width: 100%;
            min-height: 48px;
            border-radius: 10px;
            font-weight: 600;
            background: #1677f0;
            border: none;
            color: #fff;
        }

        .btn-result:hover {
            background: #0d63d5;
            color: #fff;
        }


        /* =========================================================
           MIXED CAPTCHA
        ========================================================== */

        .captcha-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .captcha-code {
            flex: 1;
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;

            background:
                repeating-linear-gradient(
                    135deg,
                    #f8f9fa,
                    #f8f9fa 8px,
                    #eef2f7 8px,
                    #eef2f7 16px
                );

            border: 1px solid #d9e0e8;
            border-radius: 10px;

            font-size: 25px;
            font-weight: 700;
            letter-spacing: 5px;

            color: #172033;

            user-select: none;

            font-family:
                "Courier New",
                monospace;

            text-decoration: line-through;
            text-decoration-color: #adb5bd;
        }

        .captcha-refresh {
            width: 48px;
            height: 48px;

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f8f9fa;
            border: 1px solid #d9e0e8;

            color: #1677f0;

            cursor: pointer;

            font-size: 20px;

            transition: all .2s ease;
        }

        .captcha-refresh:hover {
            background: #e8f1ff;
            border-color: #1677f0;
        }

        .captcha-help {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

    </style>

</head>


<body>

<div class="container px-3">

    <div class="result-card">

        {{-- =====================================================
            ICON
        ====================================================== --}}

        <div class="result-icon">

            <i class="bi bi-mortarboard-fill"></i>

        </div>


        {{-- =====================================================
            TITLE
        ====================================================== --}}

        <h3 class="result-title">

            Check Your Result

        </h3>


        <p class="result-subtitle">

            Enter your Student ID and Mother's Name

        </p>


        {{-- =====================================================
            ERROR MESSAGE
        ====================================================== --}}

        @if(session('error'))

            <div class="alert alert-danger">

                <i class="bi bi-exclamation-circle me-1"></i>

                {{ session('error') }}

            </div>

        @endif


        {{-- =====================================================
            SUCCESS MESSAGE
        ====================================================== --}}

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif


        {{-- =====================================================
            RESULT FORM
        ====================================================== --}}

        <form
            method="POST"
            action="{{ route('result.search') }}"
        >

            @csrf


            {{-- =================================================
                STUDENT ID
            ================================================== --}}

            <div class="mb-3">

                <label
                    for="student_id"
                    class="form-label"
                >

                    Student ID

                </label>


                <input
                    type="text"
                    id="student_id"
                    name="student_id"

                    value="{{ old('student_id') }}"

                    class="form-control @error('student_id') is-invalid @enderror"

                    placeholder="Enter Student ID"

                    autocomplete="off"

                    required
                >


                @error('student_id')

                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>

                @enderror

            </div>


            {{-- =================================================
                MOTHER'S NAME
            ================================================== --}}

            <div class="mb-4">

                <label
                    for="mother_name"
                    class="form-label"
                >

                    Mother's Name

                </label>


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


                @error('mother_name')

                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>

                @enderror

            </div>


            {{-- =================================================
                MIXED CAPTCHA
            ================================================== --}}

            <div class="mb-4">

                <label
                    for="captcha"
                    class="form-label"
                >

                    Security Verification

                </label>


                <div class="captcha-box">

                    {{-- CAPTCHA CODE --}}

                    <div
                        class="captcha-code"
                        id="captchaCode"
                    >

                        {{ $captcha }}

                    </div>


                    {{-- REFRESH CAPTCHA --}}

                    <button
                        type="button"
                        class="captcha-refresh"
                        onclick="refreshCaptcha()"
                        title="Refresh CAPTCHA"
                    >

                        <i class="bi bi-arrow-clockwise"></i>

                    </button>

                </div>


                <input
                    type="text"
                    id="captcha"
                    name="captcha"

                    class="form-control @error('captcha') is-invalid @enderror"

                    placeholder="Enter CAPTCHA"

                    autocomplete="off"

                    maxlength="8"

                    required
                >


                <div class="captcha-help">

                    Enter the characters exactly as shown.

                    CAPTCHA contains letters, numbers and special symbols.

                </div>


                @error('captcha')

                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>

                @enderror

            </div>


            {{-- =================================================
                SUBMIT
            ================================================== --}}

            <button
                type="submit"
                class="btn btn-result"
            >

                <i class="bi bi-search me-2"></i>

                View Result

            </button>

        </form>

    </div>

</div>


{{-- =============================================================
    CAPTCHA REFRESH
============================================================= --}}

<script>

    function refreshCaptcha() {

        fetch("{{ route('result.captcha') }}", {
            method: "GET",

            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }

        })

        .then(response => response.json())

        .then(data => {

            if (data.captcha) {

                document.getElementById(
                    'captchaCode'
                ).innerText = data.captcha;

            }

        })

        .catch(error => {

            console.error(
                'CAPTCHA refresh failed:',
                error
            );

        });

    }

</script>

</body>

</html>
