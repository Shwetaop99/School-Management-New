<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | School Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{
            --ink:#1d3a33; --ink-2:#2a4a41; --chalk:#f2b632; --wood:#8a5a2c;
            --red:#e4572e; --green:#3a9d6b; --accent:#2b5d8a;
            --text:#26302c; --muted:#6a756f; --line:#ddd6c4; --bg:#f3f5f9;
            --serif:'Fraunces',Georgia,serif; --hand:'Caveat','Comic Sans MS',cursive;
        }
        body{font-family:'Public Sans',Arial,Helvetica,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px;color:var(--text);
            background-color:#fbf8f1;background-image:repeating-linear-gradient(transparent 0 31px,rgba(43,93,138,.07) 31px 32px)}
        :focus-visible{outline:2px solid var(--chalk);outline-offset:2px}

        .login{position:relative;width:960px;max-width:100%;min-height:560px;display:flex;border-radius:14px;overflow:hidden;background:#fff;box-shadow:0 20px 44px rgba(29,58,51,.16)}
        .login::before{content:"";position:absolute;z-index:10;top:0;left:0;right:0;height:5px;background:linear-gradient(90deg,var(--red) 0 25%,var(--chalk) 25% 50%,var(--green) 50% 75%,var(--accent) 75% 100%)}

        /* ---------- Left: same chalkboard as the dashboard sidebar ---------- */
        .board{position:relative;width:42%;padding:56px 40px 110px;color:#e9efe2;overflow:hidden;
            background:radial-gradient(ellipse at 18% 8%,rgba(255,255,255,.08),transparent 55%),radial-gradient(ellipse at 85% 90%,rgba(255,255,255,.06),transparent 50%),var(--ink);
            border-right:10px solid var(--wood);box-shadow:inset -3px 0 6px rgba(0,0,0,.28)}
        .logo{width:68px;height:68px;border-radius:50%;overflow:hidden;background:#26306a;margin-bottom:22px;box-shadow:0 0 0 3px var(--ink),0 0 0 5px var(--chalk)}
        .logo img{width:100%;height:100%;object-fit:cover;display:block}
        .board h1{font-family:var(--serif);font-size:30px;font-weight:700;line-height:1.15;color:#fff;margin-bottom:8px}
        .board .hand{font-family:var(--hand);font-size:24px;color:var(--chalk);line-height:1;margin-bottom:22px}
        .board ul{list-style:none;display:flex;flex-direction:column;gap:12px}
        .board li{display:flex;align-items:center;gap:11px;font-size:14.5px;color:#d9e1d0}
        .board li i{color:var(--chalk);font-size:15px}

        .road{position:absolute;left:0;right:0;bottom:0;height:90px;pointer-events:none}
        .road::before{content:"";position:absolute;left:0;right:0;bottom:22px;border-top:2px dashed rgba(233,239,226,.35)}
        .bus{position:absolute;bottom:28px;left:34px;width:150px;animation:drive 2.2s cubic-bezier(.3,.6,.25,1) .5s both}
        .bus svg{display:block;width:100%;height:auto}
        @keyframes drive{from{transform:translateX(-500px)}to{transform:none}}

        /* ---------- Right: sign in ---------- */
        .right{width:58%;padding:60px 64px 40px;display:flex;flex-direction:column;justify-content:center}
        .right h2{font-family:var(--serif);font-size:28px;font-weight:700;color:var(--ink);margin-bottom:4px}
        .right .sub{color:var(--muted);font-size:14px;margin-bottom:26px}

        .error-box{display:flex;align-items:center;gap:10px;background:#fdecea;border:1px solid #f5c2bb;color:#b3321d;padding:11px 13px;border-radius:8px;font-size:13px;margin-bottom:18px}

        .field{margin-bottom:16px}
        .field label{display:block;font-size:13px;font-weight:600;color:var(--ink);margin-bottom:6px}
        .input{position:relative}
        .input>i.lead-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:16px}
        .input input{width:100%;height:48px;padding:0 44px;font:inherit;font-size:14.5px;color:var(--text);background:#fff;border:1px solid var(--line);border-radius:8px;outline:none;transition:border-color .15s,box-shadow .15s}
        .input input::placeholder{color:#a3a89f}
        .input input:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(242,182,50,.4)}
        .toggle{position:absolute;right:5px;top:50%;transform:translateY(-50%);width:36px;height:36px;border:none;background:transparent;border-radius:7px;color:var(--muted);font-size:16px;cursor:pointer}
        .toggle:hover{background:#f3efe3;color:var(--ink)}

        .remember{display:flex;align-items:center;gap:8px;margin:2px 0 22px;font-size:13px;color:var(--muted);cursor:pointer}
        .remember input{width:15px;height:15px;accent-color:var(--ink)}

        .btn{width:100%;height:50px;display:flex;align-items:center;justify-content:center;gap:10px;border:none;border-radius:9px;background:var(--ink);color:#fff;font:inherit;font-size:14.5px;font-weight:600;cursor:pointer;transition:background .15s,transform .15s}
        .btn:hover{background:var(--ink-2);transform:translateY(-1px)}
        .btn i{color:var(--chalk)}
        .btn.is-busy{pointer-events:none;opacity:.85}
        .spin{width:15px;height:15px;border:2px solid #fff;border-right-color:transparent;border-radius:50%;animation:spin .7s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}

        .foot{margin-top:26px;text-align:center;font-size:12px;color:#8a938d}

        @media (max-width:860px){
            body{padding:18px}
            .login{max-width:520px;flex-direction:column;min-height:0}
            .board{width:100%;border-right:none;border-bottom:8px solid var(--wood);box-shadow:none;padding:26px 28px;display:flex;align-items:center;gap:16px}
            .board .logo{margin:0;width:56px;height:56px;flex-shrink:0}
            .board h1{font-size:23px;margin:0}
            .board .hand,.board ul,.road{display:none}
            .right{width:100%;padding:34px 28px 28px}
        }
        @media (prefers-reduced-motion:reduce){.bus{animation:none}.spin{animation-duration:1.6s}}
    </style>
</head>

<body>
<div class="login">

    {{-- ===== Chalkboard panel (matches the dashboard sidebar) ===== --}}
    <section class="board">
        <div class="logo"><img src="{{ asset('images/gurukullogo.png') }}" alt="Gurukul Vidyalaya logo"></div>
        <div>
            <h1>Gurukul Vidyalaya</h1>
            <div class="hand">School management</div>
        </div>

        <ul>
            <li><i class="bi bi-check-lg"></i> Students and faculty</li>
            <li><i class="bi bi-check-lg"></i> Attendance and fees</li>
            <li><i class="bi bi-check-lg"></i> Results and reports</li>
        </ul>

        <div class="road" aria-hidden="true">
            <div class="bus">
                <svg viewBox="0 0 240 100" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="4" width="230" height="78" rx="12" fill="#f2b632"/>
                    <rect x="2" y="52" width="230" height="8" fill="#e0a21f"/>
                    <g fill="#cfe7f3" stroke="#26302c" stroke-width="2.5">
                        <rect x="16" y="16" width="32" height="26" rx="3"/><rect x="56" y="16" width="32" height="26" rx="3"/>
                        <rect x="96" y="16" width="32" height="26" rx="3"/><rect x="136" y="16" width="32" height="26" rx="3"/>
                        <rect x="182" y="14" width="40" height="32" rx="4"/>
                    </g>
                    <text x="16" y="74" font-family="Arial" font-weight="700" font-size="12" fill="#26302c">SCHOOL BUS</text>
                    <rect x="226" y="56" width="10" height="12" rx="3" fill="#e4572e"/>
                    <g fill="#26302c"><circle cx="54" cy="84" r="15"/><circle cx="180" cy="84" r="15"/></g>
                    <g fill="#d9d2bf"><circle cx="54" cy="84" r="6"/><circle cx="180" cy="84" r="6"/></g>
                </svg>
            </div>
        </div>
    </section>

    {{-- ===== Sign in ===== --}}
    <section class="right">
        <h2>Welcome back</h2>
        <p class="sub">Sign in to open your school dashboard.</p>

        @if ($errors->any())
            <div class="error-box" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
            @csrf

            <div class="field">
                <label for="email">Email address</label>
                <div class="input">
                    <i class="bi bi-envelope lead-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="admin@example.com" required autocomplete="email" autofocus>
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input">
                    <i class="bi bi-lock lead-icon"></i>
                    <input type="password" id="password" name="password"
                           placeholder="Enter your password" required autocomplete="current-password">
                    <button type="button" class="toggle" id="passwordToggle" aria-label="Show password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                <span>Keep me signed in</span>
            </label>

            <button type="submit" class="btn" id="loginButton">
                <span id="btnText">Sign in</span>
                <i class="bi bi-arrow-right" id="btnArrow"></i>
            </button>
        </form>

        <div class="foot">Authorized staff only. © {{ date('Y') }} Gurukul Vidyalaya</div>
    </section>

</div>

<script>
    const pw = document.getElementById('password');
    const toggle = document.getElementById('passwordToggle');

    toggle.addEventListener('click', function () {
        const show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        toggle.innerHTML = '<i class="bi ' + (show ? 'bi-eye-slash' : 'bi-eye') + '"></i>';
    });

    document.getElementById('loginForm').addEventListener('submit', function () {
        document.getElementById('loginButton').classList.add('is-busy');
        document.getElementById('btnText').textContent = 'Signing in...';
        document.getElementById('btnArrow').outerHTML = '<span class="spin"></span>';
    });

    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;
        const btn = document.getElementById('loginButton');
        btn.classList.remove('is-busy');
        document.getElementById('btnText').textContent = 'Sign in';
        const sp = btn.querySelector('.spin');
        if (sp) sp.outerHTML = '<i class="bi bi-arrow-right" id="btnArrow"></i>';
    });
</script>
</body>
</html>