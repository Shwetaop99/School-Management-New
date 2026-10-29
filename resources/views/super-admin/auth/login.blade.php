
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login | School Management</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: white;
            box-shadow: 0 15px 40px rgba(15, 23, 42, .08);
        }

        .brand {
            margin-bottom: 28px;
            text-align: center;
        }

        .brand-icon {
            display: inline-grid;
            place-items: center;
            width: 52px;
            height: 52px;
            margin-bottom: 12px;
            border-radius: 14px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 25px;
        }

        h1 { margin: 0 0 8px; font-size: 25px; }
        p { color: #64748b; line-height: 1.5; }

        label {
            display: block;
            margin: 18px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #93c5fd;
            border-color: #2563eb;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 18px 0;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            background: #1d4ed8;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover { background: #1e40af; }

        .error {
            margin-top: 6px;
            color: #b91c1c;
            font-size: 13px;
        }

        .notice {
            padding: 12px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 14px;
        }

        .footer {
            margin-top: 22px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <main class="login-card">
        <div class="brand">
            <div class="brand-icon" aria-hidden="true">◆</div>
            <h1>Super Admin</h1>
            <p>Manage schools from one secure portal.</p>
        </div>

        @if ($errors->any())
            <div class="notice" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('super-admin.login.submit') }}">
            @csrf

            <label for="email">Email address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username"
                required
                autofocus
            >

            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                autocomplete="current-password"
                required
            >

            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit">Sign in securely</button>
        </form>

        <p class="footer">
            Restricted access for authorized Super Administrators only.
        </p>
    </main>
</body>
</html>
