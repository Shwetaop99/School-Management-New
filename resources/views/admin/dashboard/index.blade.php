<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Gurukul Vidyalaya</title>
</head>

<body>

    <h1>Welcome to Gurukul Vidyalaya</h1>

    <p>
        Admin dashboard coming soon.
    </p>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf

        <button type="submit">
            Logout
        </button>
    </form>

</body>

</html>