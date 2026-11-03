<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    // 5 wrong tries for one email from one computer, then wait 15 minutes
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 900;

    private const WRONG_LOGIN = 'Invalid email or password.';

    /**
     * Show admin login page.
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $ip  = $request->ip();
        $key = 'login:' . sha1(mb_strtolower(trim($credentials['email'])) . '|' . $ip);

        /*
        |--------------------------------------------------------------------------
        | UPDATE 1: limit wrong tries per email + computer
        |--------------------------------------------------------------------------
        */

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {

            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);

            Log::warning('Login blocked (too many attempts)', ['ip' => $ip]);

            return back()
                ->withErrors([
                    'email' => "Too many login attempts. Please try again in {$minutes} minute(s).",
                ])
                ->withInput($request->only('email'));
        }

        $user = User::with('role')
            ->where('email', $credentials['email'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | UPDATE 2: check the PASSWORD first.
        | Same message and same speed for "no such email" and "wrong password".
        |--------------------------------------------------------------------------
        */

        if ($user) {
            $passwordOk = Hash::check($credentials['password'], $user->password);
        } else {
            // do the same amount of work so the answer takes the same time
            Hash::make($credentials['password']);
            $passwordOk = false;
        }

        if (!$user || !$passwordOk) {

            RateLimiter::hit($key, self::LOCK_SECONDS);

            Log::warning('Failed login', [
                'ip'    => $ip,
                'email' => mb_substr($credentials['email'], 0, 100),
            ]);

            return back()
                ->withErrors(['email' => self::WRONG_LOGIN])
                ->withInput($request->only('email'));
        }

        /*
        |--------------------------------------------------------------------------
        | The password is correct. Now check the account.
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'Active') {
            return back()
                ->withErrors([
                    'email' => 'This account is inactive. Please contact the administrator.',
                ])
                ->withInput($request->only('email'));
        }

        if (!$user->role) {
            return back()
                ->withErrors([
                    'email' => 'No role has been assigned to this account. Please contact the administrator.',
                ])
                ->withInput($request->only('email'));
        }

        if ($user->role->status !== 'Active') {
            return back()
                ->withErrors([
                    'email' => 'The role assigned to this account is inactive. Please contact the administrator.',
                ])
                ->withInput($request->only('email'));
        }

        /*
        |--------------------------------------------------------------------------
        | Log the user in
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear($key);

        // keep the password hash up to date with the current settings
        if (Hash::needsRehash($user->password)) {
            $user->forceFill(['password' => $credentials['password']])->save();
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // 2FA must be done again for every new login
        $request->session()->forget('two_factor_verified');

        $request->session()->put('user_role', $user->role->name);

        // UPDATE 3: write it in the activity log
        $this->record('User logged in (password OK, waiting for 2FA)', $user, $ip);

        return redirect()->route('admin.2fa.setup');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $this->record('User logged out', $user, $request->ip());
        }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Write to the activity log without ever breaking the login.
     */
    private function record(string $message, User $user, ?string $ip): void
    {
        try {
            activity()
                ->causedBy($user)
                ->withProperties(['ip' => $ip])
                ->log($message);
        } catch (\Throwable $e) {
            // logging must never stop a login
        }
    }
}