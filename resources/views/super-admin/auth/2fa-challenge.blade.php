
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Super Admin Login</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f3f5f9;
            font-family: Arial, sans-serif;
            color: #172033;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px #17203312;
        }
        h1 { margin-top: 0; }
        p { color: #64748b; line-height: 1.6; }
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 14px;
            margin: 12px 0 16px;
            font-size: 24px;
            letter-spacing: 8px;
            text-align: center;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 13px;
            background: #2457d6;
            color: white;
            border: 0;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        .error {
            color: #b42318;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Verify your identity</h1>
        <p>Enter the six-digit code from your authenticator app to continue to the Super Admin dashboard.</p>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('super-admin.2fa.verify') }}">
            @csrf
            <label for="code">Authenticator code</label>
            <input
                id="code"
                name="code"
                type="text"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                autocomplete="one-time-code"
                required
                autofocus
            >
            <button type="submit">Verify and Continue</button>
        </form>

        <form method="POST" action="{{ route('super-admin.logout') }}" style="margin-top: 14px;">
            @csrf
            <button type="submit" style="background:#64748b;">Cancel and Log Out</button>
        </form>
    </div>
</body>
</html>
