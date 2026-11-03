@php
    $hour = now()->hour;
    $greeting = $hour >= 5 && $hour < 12 ? 'Good morning'
              : ($hour >= 12 && $hour < 17 ? 'Good afternoon'
              : ($hour >= 17 && $hour < 21 ? 'Good evening' : 'Good night'));

    $thoughts = [
        'Well begun is half done.',
        'Small steps every day lead to big results.',
        'Knowledge grows when it is shared.',
        'Practice makes progress.',
        'Every day is a chance to learn something new.',
        'Curiosity is the first lesson.',
        'Be a little better than yesterday.',
    ];
    $thought = $thoughts[now()->dayOfYear % count($thoughts)];

    // Where each portal button goes: always the login page of that portal.
    // If a route name is missing, the plain URL is used, so this page never throws an error.
    // Each login controller then decides what a signed-in person should see next.
    $schoolUrl   = \Illuminate\Support\Facades\Route::has('admin.login')
        ? route('admin.login')
        : url('/login');
    $schoolLabel = 'Continue to School Login';

    $superUrl    = \Illuminate\Support\Facades\Route::has('super-admin.login')
        ? route('super-admin.login')
        : url('/control-panel/login');
    $superLabel  = 'Continue to Super Admin Login';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System | Select Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Libre+Baskerville:wght@400;700&family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #1d3a33;
            --ink-2: #16302a;
            --text: #2c3e3a;
            --muted: #6b7c75;
            --accent: #e4572e;
            --chalk: #e9efe2;
            --line: #d8d1bf;
            --paper: #ffffff;
            --bg: #f7f6f0;
            --serif: 'Libre Baskerville', Georgia, 'Times New Roman', serif;
            --hand: 'Caveat', 'Segoe Print', cursive;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text);
            background: radial-gradient(circle at 10% 8%, #ebe5d5 0, transparent 30%), var(--bg);
            font-family: 'Public Sans', 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
        }

        a { color: inherit; text-decoration: none; }

        .paper { background: var(--paper); border: 1px solid var(--line); border-radius: 10px; }

        /* ---------- Top bar ---------- */
        .topbar {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 15px clamp(18px, 5vw, 64px);
            background: var(--paper);
            border-bottom: 1px solid var(--line);
        }

        .logo {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-bottom: 4px solid var(--accent);
            border-radius: 10px;
            background: var(--ink);
            color: var(--chalk);
            font-family: var(--serif);
            font-size: 22px;
            font-weight: 700;
        }

        .brand-title { margin: 0; color: var(--ink); font-family: var(--serif); font-size: 17px; font-weight: 700; }
        .brand-subtitle { margin: 3px 0 0; color: var(--muted); font-size: 12px; }

        /* ---------- Layout ---------- */
        main { width: 100%; max-width: 1180px; margin: 0 auto; padding: 34px 24px 40px; flex: 1; }

        /* ---------- Hero ---------- */
        .hero {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
            overflow: hidden;
            margin-bottom: 52px;
        }

        .hero::before {
            content: "";
            position: absolute;
            left: 34px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(228, 87, 46, .35);
        }

        .hero-left { padding: 34px 28px 34px 56px; }

        .eyebrow {
            margin-bottom: 8px;
            color: var(--accent);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .hero-left h1 {
            margin: 0 0 8px;
            color: var(--ink);
            font-family: var(--serif);
            font-size: clamp(26px, 3.4vw, 36px);
            font-weight: 700;
            line-height: 1.2;
        }

        .hero-left p { max-width: 520px; margin: 0; color: var(--muted); line-height: 1.75; }

        .bell {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
            padding: 30px 30px 30px 32px;
            color: #e9efe2;
            background: var(--ink);
            background-image: radial-gradient(ellipse at 20% 0, rgba(255, 255, 255, .09), transparent 60%);
        }

        .bell-label { color: var(--chalk); font-family: var(--hand); font-size: 22px; line-height: 1; }

        .bell-thought { color: #fff; font-family: var(--hand); font-size: 30px; font-weight: 700; line-height: 1.15; }

        .bell-date {
            padding-top: 12px;
            border-top: 1px dashed rgba(255, 255, 255, .22);
            color: #aebba6;
            font-size: 12px;
        }

        /* ---------- Portal cards (folder tabs) ---------- */
        .portals { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 30px 22px; }

        .portal {
            --c: #2b5d8a;
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 28px 28px 24px;
            border-top: 4px solid var(--c);
            transition: transform .22s ease, box-shadow .22s ease;
        }

        .portal:hover,
        .portal:focus-within { transform: translateY(-6px); box-shadow: 0 16px 28px rgba(29, 58, 51, .14); }

        .c-blue { --c: #2b5d8a; }
        .c-red { --c: #e4572e; }

        .portal-tab {
            position: absolute;
            top: -28px;
            left: 16px;
            padding: 4px 12px;
            border-radius: 7px 7px 0 0;
            background: var(--c);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
        }

        .portal-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; }

        .portal-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: color-mix(in srgb, var(--c) 14%, #fff);
            color: var(--c);
        }

        .portal-icon svg {
            width: 26px;
            height: 26px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .portal-label {
            padding: 5px 9px;
            border: 1px dashed var(--line);
            border-radius: 6px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .portal h2 { margin: 0 0 10px; color: var(--ink); font-family: var(--serif); font-size: 22px; font-weight: 700; line-height: 1.25; }

        .portal > p { min-height: 46px; margin: 0 0 18px; color: var(--muted); font-size: 13.5px; line-height: 1.7; }

        .features {
            display: grid;
            gap: 12px;
            margin: 0 0 24px;
            padding: 18px 0 0;
            border-top: 1px dashed #d8d1bf;
            list-style: none;
        }

        .features li { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; line-height: 1.5; }

        .check {
            display: inline-grid;
            place-items: center;
            flex-shrink: 0;
            width: 19px;
            height: 19px;
            border-radius: 50%;
            background: color-mix(in srgb, var(--c) 14%, #fff);
            color: var(--c);
            font-size: 11px;
            font-weight: 800;
        }

        .btn-solid {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            margin-top: auto;
            padding: 14px 14px 14px 20px;
            border: 0;
            border-radius: 12px;
            background: var(--c);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .2px;
            box-shadow: 0 8px 18px color-mix(in srgb, var(--c) 38%, transparent),
                        inset 0 -3px 0 rgba(0, 0, 0, .18);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }

        .btn-solid:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
            box-shadow: 0 12px 24px color-mix(in srgb, var(--c) 50%, transparent),
                        inset 0 -3px 0 rgba(0, 0, 0, .18);
            color: #fff;
        }

        .btn-solid:active {
            transform: translateY(1px);
            box-shadow: 0 3px 8px color-mix(in srgb, var(--c) 35%, transparent),
                        inset 0 -1px 0 rgba(0, 0, 0, .18);
        }

        .btn-solid:focus-visible { outline: 3px solid #d58a0b; outline-offset: 3px; }

        .arrow {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #fff;
            color: var(--c);
            font-size: 17px;
            font-weight: 800;
            line-height: 1;
            animation: nudge 2.4s ease-in-out infinite;
        }

        .btn-solid:hover .arrow { animation: none; transform: translateX(3px); }

        @keyframes nudge {
            0%, 70%, 100% { transform: translateX(0); }
            85% { transform: translateX(4px); }
        }

        /* ---------- Security note ---------- */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 34px auto 0;
            max-width: 760px;
            padding: 13px 18px;
            border-style: dashed;
            color: var(--muted);
            font-size: 12.5px;
            line-height: 1.6;
            text-align: center;
        }

        .security-note svg {
            flex-shrink: 0;
            width: 17px;
            height: 17px;
            fill: none;
            stroke: var(--ink);
            stroke-width: 1.8;
        }

        footer {
            padding: 16px 20px;
            border-top: 1px solid var(--line);
            background: var(--paper);
            color: var(--muted);
            font-size: 11.5px;
            text-align: center;
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .bell { padding: 24px 28px 24px 56px; }
        }

        @media (max-width: 720px) {
            main { padding: 24px 16px 32px; }

            .hero { margin-bottom: 46px; }
            .hero::before { left: 24px; }
            .hero-left { padding: 26px 20px 26px 44px; }
            .bell { padding: 22px 20px 22px 44px; }
            .bell-thought { font-size: 26px; }

            .portals { grid-template-columns: 1fr; gap: 40px; max-width: 520px; margin: 0 auto; }
            .portal { padding: 24px 22px 22px; }
            .portal > p { min-height: 0; }

            .security-note { text-align: left; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition: none !important; animation: none !important; }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <div class="logo" aria-hidden="true">S</div>
        <div>
            <h2 class="brand-title">School Management System</h2>
            <p class="brand-subtitle">Education administration portal</p>
        </div>
    </header>

    <main>
        {{-- Diary hero --}}
        <section class="paper hero">
            <div class="hero-left">
                <div class="eyebrow">Welcome to your workspace</div>
                <h1>{{ $greeting }}. Select your portal.</h1>
                <p>
                    Choose the portal that matches your responsibilities.
                    Sign in with your authorized account to continue.
                </p>
            </div>

            <div class="bell">
                <div class="bell-label">Thought for the day</div>
                <div class="bell-thought">&ldquo;{{ $thought }}&rdquo;</div>
                <div class="bell-date">{{ now()->format('l, d F Y') }}</div>
            </div>
        </section>

        {{-- Portals --}}
        <section class="portals" aria-label="Available portals">

            <article class="paper portal c-blue">
                <span class="portal-tab">School</span>

                <div class="portal-top">
                    <div class="portal-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 21h18M5 21V8l7-5 7 5v13"/>
                            <path d="M9 21v-6h6v6M9 10h.01M15 10h.01"/>
                        </svg>
                    </div>
                    <span class="portal-label">School workspace</span>
                </div>

                <h2>School Admin Portal</h2>
                <p>Manage the daily operations of your school from one convenient dashboard.</p>

                <ul class="features">
                    <li><span class="check">✓</span>Students and staff management</li>
                    <li><span class="check">✓</span>Attendance and academic records</li>
                    <li><span class="check">✓</span>Fees, reports and school settings</li>
                </ul>

                <a class="btn-solid" href="{{ $schoolUrl }}">
                    <span>{{ $schoolLabel }}</span>
                    <span class="arrow" aria-hidden="true">→</span>
                </a>
            </article>

            <article class="paper portal c-red">
                <span class="portal-tab">System</span>

                <div class="portal-top">
                    <div class="portal-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="portal-label">System workspace</span>
                </div>

                <h2>Super Admin Portal</h2>
                <p>Manage registered schools, school administrators and system-wide operations.</p>

                <ul class="features">
                    <li><span class="check">✓</span>Register and manage schools</li>
                    <li><span class="check">✓</span>Manage school administrator accounts</li>
                    <li><span class="check">✓</span>Review access and subscription status</li>
                </ul>

                <a class="btn-solid" href="{{ $superUrl }}">
                    <span>{{ $superLabel }}</span>
                    <span class="arrow" aria-hidden="true">→</span>
                </a>
            </article>

        </section>

        <p class="paper security-note">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="5" y="10" width="14" height="11" rx="2"/>
                <path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/>
            </svg>
            Access is restricted to authorized users. Each portal has its own access permissions.
        </p>
    </main>

    <footer>
        &copy; {{ date('Y') }} School Management System
    </footer>
</body>
</html>