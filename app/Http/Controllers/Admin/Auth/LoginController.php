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
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 900;
    private const WRONG_LOGIN = 'Invalid Login ID/email or password.';

    /**
     * Show the school admin login page.
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Authenticate using either login ID or email.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $login = trim($credentials['login']);
        $normalizedLogin = mb_strtolower($login);
        $ip = $request->ip();
        $key = 'login:' . sha1($normalizedLogin . '|' . $ip);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);

            Log::warning('School admin login blocked (too many attempts)', [
                'ip' => $ip,
            ]);

            return back()
                ->withErrors([
                    'login' => "Too many login attempts. Please try again in {$minutes} minute(s).",
                ])
                ->withInput($request->only('login'));
        }

        // The form accepts either the user's login ID or email address.
        $user = User::with('role')
            ->where(function ($query) use ($login, $normalizedLogin) {
                $query->where('login_id', $login)
                    ->orWhereRaw('LOWER(email) = ?', [$normalizedLogin]);
            })
            ->first();

        // Do a password-hash operation even if no account matched, reducing
        // timing differences between an unknown login and an incorrect password.
        if ($user) {
            $passwordOk = Hash::check($credentials['password'], $user->password);
        } else {
            Hash::make($credentials['password']);
            $passwordOk = false;
        }

        if (!$user || !$passwordOk) {
            RateLimiter::hit($key, self::LOCK_SECONDS);

            Log::warning('Failed school admin login', [
                'ip' => $ip,
                'login' => mb_substr($login, 0, 100),
            ]);

            return back()
                ->withErrors(['login' => self::WRONG_LOGIN])
                ->withInput($request->only('login'));
        }

        if ($user->status !== 'Active') {
            return back()
                ->withErrors([
                    'login' => 'This account is inactive. Please contact the administrator.',
                ])
                ->withInput($request->only('login'));
        }

        if (!$user->role) {
            return back()
                ->withErrors([
                    'login' => 'No role has been assigned to this account. Please contact the administrator.',
                ])
                ->withInput($request->only('login'));
        }

        if ($user->role->status !== 'Active') {
            return back()
                ->withErrors([
                    'login' => 'The role assigned to this account is inactive. Please contact the administrator.',
                ])
                ->withInput($request->only('login'));
        }

        if ($user->role->name === 'super_admin') {
            return back()
                ->withErrors([
                    'login' => 'Please use the Super Admin login page for this account.',
                ])
                ->withInput($request->only('login'));
        }

        if (Hash::needsRehash($user->password)) {
            $user->forceFill([
                'password' => Hash::make($credentials['password']),
            ])->save();
        }

        RateLimiter::clear($key);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Require two-factor verification again for each new login.
        $request->session()->forget('two_factor_verified');
        $request->session()->put('user_role', $user->role->name);

        try {
            app(\App\Services\AuditLogService::class)->log(
                'login',
                'Authentication',
                'School Admin logged in successfully.'
            );
        } catch (\Throwable $e) {
            Log::warning('Could not write school admin login audit log.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }

        $this->record('User logged in (password OK, waiting for 2FA)', $user, $ip);

        return redirect()->route('admin.2fa.setup');
    }

    /**
     * Log out the authenticated user.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $this->record('User logged out', $user, $request->ip());
        }

        try {
            app(\App\Services\AuditLogService::class)->log(
                'logout',
                'Authentication',
                'School Admin logged out successfully.'
            );
        } catch (\Throwable $e) {
            Log::warning('Could not write school admin logout audit log.', [
                'user_id' => $user?->id,
                'message' => $e->getMessage(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Write to the activity log without breaking authentication.
     */
    private function record(string $message, User $user, ?string $ip): void
    {
        try {
            activity()
                ->causedBy($user)
                ->withProperties(['ip' => $ip])
                ->log($message);
        } catch (\Throwable $e) {
            Log::warning('Could not write authentication activity log.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
