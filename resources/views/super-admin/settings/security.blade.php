
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings | Super Admin</title>

    <style>
        :root {
            --green-dark: #18332d;
            --green-deep: #142c27;
            --green: #347f61;
            --green-soft: #e8f4ed;
            --coral: #dd3e1a;
            --gold: #d5a64c;
            --bg: #f5f6f2;
            --white: #ffffff;
            --text: #20332e;
            --muted: #788681;
            --border: #e3e9e4;
            --danger: #c83e35;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
        }

        a { color: inherit; text-decoration: none; }

        .layout {
            display: flex;
            min-height: 100vh;
            padding: 5px;
        }

        /* Dashboard-style sidebar */
        .sidebar {
            width: 298px;
            flex: 0 0 298px;
            display: flex;
            flex-direction: column;
            padding: 24px 19px 17px;
            background: var(--green-dark);
            color: #f7fbf8;
            border-radius: 0 0 0 8px;
        }

        .sidebar-top {
            height: 10px;
            margin: -14px 0 28px;
            border-bottom: 1px solid #ffffff1c;
        }

        .section-label {
            margin: 0 11px 12px;
            color: #9db7ad;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            min-height: 48px;
            padding: 12px 13px;
            border-radius: 11px;
            color: #e2ece7;
            font-size: 14px;
            transition: background .2s;
        }

        .nav-link:hover {
            background: #ffffff10;
        }

        .nav-link.active {
            background: #ffffff19;
            border-left: 4px solid var(--coral);
            padding-left: 9px;
            color: #fff;
            font-weight: 700;
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .nav-badge {
            margin-left: auto;
            padding: 4px 7px;
            border: 1px solid #ffffff26;
            border-radius: 6px;
            color: #a9c5b9;
            font-size: 9px;
        }

        .sidebar-label {
            margin-top: 32px;
        }

        .workspace-note {
            margin: 42px 4px 19px;
            padding: 17px 15px;
            border: 1px solid #ffffff22;
            border-radius: 14px;
            background: #ffffff08;
        }

        .workspace-note strong {
            display: block;
            margin-bottom: 9px;
            font-size: 13px;
        }

        .workspace-note p {
            margin: 0;
            color: #a9c5b9;
            font-size: 12px;
            line-height: 1.8;
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid #ffffff1b;
            padding-top: 17px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 8px 17px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #e7f2ec;
            color: var(--green-dark);
            font-weight: 800;
        }

        .profile-name {
            font-size: 13px;
            font-weight: 800;
        }

        .profile-role {
            margin-top: 5px;
            color: #a8c5b9;
            font-size: 11px;
        }

        .logout-link {
            display: flex;
            gap: 11px;
            align-items: center;
            padding: 12px 10px;
            color: #ff9b82;
            font-size: 13px;
            font-weight: 600;
        }

        /* Main area */
        .main {
            flex: 1;
            min-width: 0;
            padding: 0 35px 32px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            min-height: 94px;
            margin: 0 -35px 31px;
            padding: 20px 39px;
            border-bottom: 1px solid var(--border);
            background: #ffffffd9;
        }

        .breadcrumb {
            margin-bottom: 7px;
            color: #77857f;
            font-size: 12px;
        }

        .breadcrumb strong {
            color: var(--green-dark);
        }

        .page-title {
            margin: 0;
            font-size: 25px;
            font-weight: 750;
            letter-spacing: -.7px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .date-chip, .admin-chip {
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: white;
            color: #66756e;
            font-size: 11px;
            white-space: nowrap;
        }

        .admin-chip {
            border-color: transparent;
            background: var(--green-soft);
            color: #267b59;
            font-weight: 800;
            letter-spacing: .6px;
        }

        .admin-chip span {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-right: 7px;
            border-radius: 50%;
            background: #39a875;
        }

        .intro {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 27px;
        }

        .eyebrow {
            margin-bottom: 12px;
            color: #267d60;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .intro h2 {
            margin: 0;
            font-size: clamp(25px, 3vw, 35px);
            font-weight: 750;
            letter-spacing: -1.2px;
        }

        .intro p {
            max-width: 650px;
            margin: 12px 0 0;
            color: #788681;
            font-size: 14px;
            line-height: 1.8;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            padding: 14px 17px;
            border-radius: 11px;
            background: var(--green-dark);
            color: white;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 5px 14px #18332d19;
            transition: transform .2s;
        }

        .back-button:hover { transform: translateY(-2px); }

        /* Security summary */
        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 25px;
            margin-bottom: 28px;
            padding: 25px 28px;
            border: 1px solid #dce9df;
            border-radius: 17px;
            background: linear-gradient(110deg, #fff 5%, #f0f8f1);
        }

        .summary-label {
            margin-bottom: 10px;
            color: #27805e;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .summary h3 {
            margin: 0;
            font-size: 20px;
            letter-spacing: -.4px;
        }

        .summary p {
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .summary-icon {
            display: grid;
            place-items: center;
            width: 62px;
            height: 62px;
            flex-shrink: 0;
            border-radius: 17px;
            background: #e2f3e8;
            color: var(--green);
            font-size: 29px;
        }

        .section-heading {
            margin: 0 0 7px;
            font-size: 17px;
            font-weight: 750;
        }

        .section-subtitle {
            margin: 0 0 19px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(265px, .75fr);
            gap: 22px;
            align-items: start;
        }

        .card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--white);
            box-shadow: 0 8px 22px #18332d08;
        }

        .card-head {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 21px 23px;
            border-bottom: 1px solid var(--border);
        }

        .card-icon {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            border-radius: 12px;
            background: #e6f3eb;
            color: #2b805c;
            font-size: 21px;
        }

        .card-icon.coral {
            background: #fff0eb;
            color: var(--coral);
        }

        .card-head h3 {
            margin: 0;
            font-size: 15px;
        }

        .card-head p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 11px;
        }

        .card-body { padding: 23px; }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 19px;
        }

        .status-label {
            color: #77857f;
            font-size: 12px;
            font-weight: 700;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .badge.green {
            background: #e5f5eb;
            color: #218257;
        }

        .badge.gold {
            background: #fff4df;
            color: #9d6a12;
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        .message {
            display: flex;
            gap: 10px;
            margin: 0 0 19px;
            padding: 13px 14px;
            border-radius: 10px;
            font-size: 12px;
            line-height: 1.7;
        }

        .message.success {
            border: 1px solid #cce9d5;
            background: #eff9f1;
            color: #24754d;
        }

        .message.error {
            border: 1px solid #f5d0ca;
            background: #fff3f0;
            color: #a83229;
        }

        .message.info {
            border: 1px solid #d8e9df;
            background: #f2f8f3;
            color: #496e5b;
        }

        .message ul {
            margin: 0;
            padding-left: 17px;
        }

        .instructions {
            display: grid;
            gap: 13px;
            margin: 20px 0;
        }

        .instruction {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            color: #64736c;
            font-size: 12px;
            line-height: 1.7;
        }

        .step {
            display: grid;
            place-items: center;
            width: 25px;
            height: 25px;
            flex-shrink: 0;
            border-radius: 8px;
            background: #e8f3eb;
            color: #267b55;
            font-size: 11px;
            font-weight: 800;
        }

        .qr-box {
            padding: 19px;
            border: 1px dashed #cbded0;
            border-radius: 14px;
            background: #f7faf6;
            text-align: center;
        }

        .qr {
            display: inline-grid;
            place-items: center;
            max-width: 100%;
            padding: 12px;
            border: 1px solid #e5eae5;
            border-radius: 11px;
            background: #fff;
        }

        .qr svg {
            display: block;
            width: 100%;
            max-width: 235px;
            height: auto;
        }

        .qr-caption {
            margin: 12px 0 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .field { margin-top: 20px; }

        .field label {
            display: block;
            margin-bottom: 9px;
            color: #34443d;
            font-size: 12px;
            font-weight: 750;
        }

        .setup-key {
            display: block;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #f7f9f6;
            color: #315543;
            font-family: Consolas, monospace;
            font-size: 13px;
            letter-spacing: 1px;
            overflow-wrap: anywhere;
            user-select: all;
        }

        .hint {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .input {
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border: 1px solid #d4ded7;
            border-radius: 9px;
            outline: none;
            background: #fff;
            color: var(--text);
            font-family: inherit;
            font-size: 17px;
            letter-spacing: 4px;
            transition: border-color .2s, box-shadow .2s;
        }

        .input:focus {
            border-color: #39815f;
            box-shadow: 0 0 0 3px #39815f1a;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 47px;
            margin-top: 18px;
            padding: 12px 17px;
            border: 0;
            border-radius: 10px;
            background: var(--green-dark);
            color: white;
            font-family: inherit;
            font-size: 12px;
            font-weight: 750;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .btn:hover {
            transform: translateY(-1px);
            background: #285344;
        }

        .btn-danger {
            background: var(--coral);
        }

        .btn-danger:hover { background: #f4380e; }

        .side-card { padding: 22px; }

        .side-card h3 {
            margin: 0;
            font-size: 15px;
        }

        .side-card > p {
            margin: 9px 0 20px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .feature {
            display: flex;
            gap: 12px;
            margin: 19px 0;
        }

        .feature-icon {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 11px;
            background: #e8f3eb;
            color: #2e805c;
            font-size: 17px;
        }

        .feature-icon.coral {
            background: #fff0eb;
            color: var(--coral);
        }

        .feature-icon.gold {
            background: #fff5df;
            color: #b17b19;
        }

        .feature h4 {
            margin: 2px 0 5px;
            font-size: 12px;
        }

        .feature p {
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .side-divider {
            height: 1px;
            margin: 22px 0;
            background: var(--border);
        }

        .security-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin: 14px 0;
            font-size: 11px;
        }

        .security-item span:first-child {
            color: #788681;
        }

        .footer {
            margin-top: 27px;
            color: #9aa49e;
            font-size: 10px;
            text-align: center;
        }

        @media (max-width: 1100px) {
            .sidebar {
                width: 245px;
                flex-basis: 245px;
            }

            .main { padding-right: 23px; padding-left: 23px; }

            .topbar {
                margin-right: -23px;
                margin-left: -23px;
                padding-right: 25px;
                padding-left: 25px;
            }

            .grid { grid-template-columns: minmax(0, 1fr); }
        }

        @media (max-width: 720px) {
            .layout { display: block; padding: 4px; }

            .sidebar {
                width: 100%;
                padding: 15px;
                border-radius: 8px;
            }

            .sidebar-top, .section-label,
            .workspace-note, .sidebar-footer { display: none; }

            .nav {
                flex-direction: row;
                overflow-x: auto;
                margin-top: 14px;
            }

            .nav-link {
                min-width: max-content;
                min-height: 42px;
                padding: 10px;
            }

            .nav-link.active { padding-left: 7px; }

            .sidebar-label { display: none; }

            .main { padding: 0 13px 25px; }

            .topbar {
                min-height: 78px;
                margin: 0 -13px 24px;
                padding: 15px 17px;
            }

            .page-title { font-size: 21px; }

            .date-chip { display: none; }

            .intro { display: block; }

            .intro h2 { font-size: 27px; }

            .back-button { margin-top: 17px; }

            .summary { padding: 20px; }

            .summary h3 { font-size: 17px; }

            .summary-icon { width: 48px; height: 48px; }

            .card-head, .card-body { padding: 18px; }
        }
    </style>
</head>

<body>
<div class="layout">

    <aside class="sidebar">
        <div class="sidebar-top"></div>

        <div class="section-label">Workspace</div>

        <nav class="nav">
            <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                <span class="nav-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                <span class="nav-icon">⌂</span>
                <span>School management</span>
            </a>

            <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                <span class="nav-icon">▤</span>
                <span>Subscriptions</span>
                <span class="nav-badge">PLANNED</span>
            </a>

            <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                <span class="nav-icon">₹</span>
                <span>Payments</span>
                <span class="nav-badge">PLANNED</span>
            </a>

            <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                <span class="nav-icon">♙</span>
                <span>School accounts</span>
                <span class="nav-badge">PLANNED</span>
            </a>

            <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                <span class="nav-icon">⌁</span>
                <span>System activity</span>
                <span class="nav-badge">PLANNED</span>
            </a>
        </nav>

        <div class="section-label sidebar-label">Administration</div>

        <nav class="nav">
            <a class="nav-link active"
               href="{{ route('super-admin.security') }}"
               aria-current="page">
                <span class="nav-icon">⚙</span>
                <span>Security Settings</span>
            </a>
        </nav>

        <div class="workspace-note">
            <strong>🔒 System workspace</strong>
            <p>
                Use this console to oversee registered schools and platform-wide access.
            </p>
        </div>

        <div class="sidebar-footer">
            <div class="profile">
                <div class="avatar">SA</div>
                <div>
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-role">Super administrator</div>
                </div>
            </div>

            <a class="logout-link"
               href="{{ route('super-admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('super-admin-logout-form').submit();">
                <span>↪</span> Sign out securely
            </a>

            <form id="super-admin-logout-form"
                  method="POST"
                  action="{{ route('super-admin.logout') }}"
                  style="display:none;">
                @csrf
            </form>
        </div>
    </aside>

    <main class="main">

        <header class="topbar">
            <div>
                <div class="breadcrumb">
                    School Central / <strong>Security Settings</strong>
                </div>
                <h1 class="page-title">Security Settings</h1>
            </div>

            <div class="top-actions">
                <div class="date-chip">◷ &nbsp; System security</div>
                <div class="admin-chip"><span></span> SUPER ADMIN</div>
            </div>
        </header>

        <section class="intro">
            <div>
                <div class="eyebrow">Administration / Account protection</div>
                <h2>Protect your system access.</h2>
                <p>
                    Manage two-factor authentication for your Super Admin account.
                    This authenticator setup is separate from the School Admin QR code.
                </p>
            </div>

            <a class="back-button" href="{{ route('super-admin.dashboard') }}">
                Back to dashboard <span>→</span>
            </a>
        </section>

        <section class="summary">
            <div>
                <div class="summary-label">SECURITY OVERVIEW</div>
                <h3>Keep your administrator account protected</h3>
                <p>
                    Use an authenticator app to add another layer of sign-in security.
                </p>
            </div>

            <div class="summary-icon">♧</div>
        </section>

        <h2 class="section-heading">Account protection</h2>
        <p class="section-subtitle">
            Review your two-factor authentication status and manage your setup.
        </p>

        <div class="grid">

            <section class="card">
                <div class="card-head">
                    <div class="card-icon">⚙</div>
                    <div>
                        <h3>Two-factor authentication</h3>
                        <p>Authenticator app and verification settings</p>
                    </div>
                </div>

                <div class="card-body">

                    @if(session('status'))
                        <div class="message success" role="status">
                            <strong>✓</strong>
                            <div>{{ session('status') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="message error" role="alert">
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

                        <div class="status-row">
                            <span class="status-label">Protection status</span>
                            <span class="badge green">
                                <span class="dot"></span> PROTECTED
                            </span>
                        </div>

                        <div class="message success">
                            <strong>✓</strong>
                            <div>
                                Two-factor authentication is enabled for your Super Admin account.
                                You need a valid authenticator code when signing in.
                            </div>
                        </div>

                        <p class="section-subtitle">
                            To disable protection, enter a current six-digit code from your
                            Super Admin authenticator app. You will need to set it up again
                            before returning to the dashboard.
                        </p>

                        <form method="POST"
                              action="{{ route('super-admin.security.disable') }}">
                            @csrf

                            <div class="field">
                                <label for="disable-code">Current authenticator code</label>
                                <input
                                    class="input"
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

                            <button class="btn btn-danger" type="submit">
                                Disable two-factor authentication
                            </button>
                        </form>

                    @else

                        <div class="status-row">
                            <span class="status-label">Protection status</span>
                            <span class="badge gold">
                                <span class="dot"></span> SETUP REQUIRED
                            </span>
                        </div>

                        <div class="message info">
                            <strong>i</strong>
                            <div>
                                Two-factor authentication is required for Super Admin access.
                                Complete the setup below to protect this account.
                            </div>
                        </div>

                        <div class="instructions">
                            <div class="instruction">
                                <span class="step">1</span>
                                <span>Open Google Authenticator, Microsoft Authenticator,
                                    or another compatible app.</span>
                            </div>

                            <div class="instruction">
                                <span class="step">2</span>
                                <span>Scan this Super Admin QR code or enter the manual
                                    setup key in your authenticator app.</span>
                            </div>

                            <div class="instruction">
                                <span class="step">3</span>
                                <span>Enter the current six-digit code to verify and enable
                                    two-factor authentication.</span>
                            </div>
                        </div>

                        @if($qrCodeUrl && $secret)

                            <div class="qr-box">
                                <div class="qr">
                                    {!! $qrCodeUrl !!}
                                </div>
                                <p class="qr-caption">
                                    Scan using your authenticator app. Keep this QR code private.
                                </p>
                            </div>

                            <div class="field">
                                <label for="setup-key">Manual setup key</label>
                                <code class="setup-key" id="setup-key">{{ $secret }}</code>
                                <p class="hint">
                                    This key is sensitive. Do not share it or post a screenshot
                                    of it publicly.
                                </p>
                            </div>

                            <form method="POST"
                                  action="{{ route('super-admin.security.enable') }}">
                                @csrf

                                <div class="field">
                                    <label for="enable-code">Six-digit authenticator code</label>
                                    <input
                                        class="input"
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
                                    <p class="hint">
                                        Enter the current code shown in your authenticator app.
                                    </p>
                                </div>

                                <button class="btn" type="submit">
                                    ✓ Verify and enable protection
                                </button>
                            </form>

                        @else

                            <div class="message error">
                                <strong>!</strong>
                                <div>
                                    The QR code is unavailable. Reload the page. If it remains
                                    unavailable, check that the security controller returns
                                    both <code>$qrCodeUrl</code> and <code>$secret</code>.
                                </div>
                            </div>

                        @endif

                    @endif

                </div>
            </section>

            <aside class="card">
                <div class="card-head">
                    <div class="card-icon coral">♧</div>
                    <div>
                        <h3>Security checklist</h3>
                        <p>Recommended account safeguards</p>
                    </div>
                </div>

                <div class="side-card">
                    <div class="feature">
                        <div class="feature-icon">🔐</div>
                        <div>
                            <h4>Separate QR authentication</h4>
                            <p>
                                The Super Admin uses its dedicated
                                <code>super_admin_two_factor_secret</code> field.
                            </p>
                        </div>
                    </div>

                    <div class="feature">
                        <div class="feature-icon coral">📱</div>
                        <div>
                            <h4>Authenticator app</h4>
                            <p>
                                Use a compatible app that generates time-based,
                                six-digit verification codes.
                            </p>
                        </div>
                    </div>

                    <div class="feature">
                        <div class="feature-icon gold">🛡</div>
                        <div>
                            <h4>Protect your setup key</h4>
                            <p>
                                Anyone with your secret key may be able to generate
                                your authentication codes.
                            </p>
                        </div>
                    </div>

                    <div class="side-divider"></div>

                    <h3>Account status</h3>

                    <div class="security-item">
                        <span>Account role</span>
                        <span class="badge green">SUPER ADMIN</span>
                    </div>

                    <div class="security-item">
                        <span>Two-factor authentication</span>
                        @if($user->super_admin_two_factor_enabled)
                            <span class="badge green">ENABLED</span>
                        @else
                            <span class="badge gold">PENDING</span>
                        @endif
                    </div>

                    <div class="security-item">
                        <span>Setup type</span>
                        <span>Authenticator app</span>
                    </div>
                </div>
            </aside>

        </div>

        <footer class="footer">
            School Central · Super Admin Console · Security &amp; Access Management
        </footer>

    </main>
</div>
</body>
</html>
