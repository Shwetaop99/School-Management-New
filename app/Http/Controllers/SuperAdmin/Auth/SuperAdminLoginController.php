<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class SuperAdminLoginController extends Controller
{
    /**
     * Show the Super Admin login page.
     *
     * The login form is ALWAYS shown, even if someone is already signed in.
     * If they are, the page is told who, so it can offer a "continue to dashboard"
     * link as an option instead of forcing a redirect.
     */
    public function showLogin(Request $request)
    {
        $guard = Auth::guard('super_admin');

        return view('super-admin.auth.login', [
            'alreadySignedInAs' => $guard->check() ? $guard->user() : null,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:1024'],
        ]);

        $email = Str::lower(trim($credentials['email']));
        $key   = 'super-admin-login:' . hash('sha256', $email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 3)) {
    $seconds = RateLimiter::availableIn($key);

    return back()
        ->withErrors([
            'email' => "Too many failed login attempts. Try again in {$seconds} seconds.",
        ])
        ->onlyInput('email');
}

        $user = User::with('role')
            ->where('email', $email)
            ->first();

        $valid = $user
            && $user->status === 'Active'
            && $user->role
            && $user->role->status === 'Active'
            && $user->role->name === 'super_admin'
            && Hash::check($credentials['password'], $user->password);

        if (! $valid) {
            RateLimiter::hit($key, 900);

            return back()
                ->withErrors(['email' => 'Invalid Super Admin credentials.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($key);

        Auth::guard('super_admin')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        $request->session()->forget([
            'super_admin_2fa_verified',
            'super_admin_2fa_pending',
        ]);

        if ($user->super_admin_two_factor_enabled && $user->super_admin_two_factor_secret) {
            $request->session()->put('super_admin_2fa_pending', true);

            return redirect()->route('super-admin.2fa.challenge');
        }

        // 2FA is mandatory for Super Admin accounts.
        return redirect()->route('super-admin.security')
            ->with('status', 'Set up two-factor authentication to continue.');
    }

    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();

        $request->session()->forget([
            'super_admin_2fa_verified',
            'super_admin_2fa_pending',
        ]);

        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login');
    }
}