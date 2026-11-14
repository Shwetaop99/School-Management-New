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
    // Five failed attempts for one email from one IP, then wait 15 minutes.
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 900;

    private const WRONG_LOGIN = 'Invalid email or password.';

    /**
     * Show the school admin login page.
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Authenticate a school admin using their email and password.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $email = mb_strtolower(trim($credentials['email']));
        $ip = $request->ip();
        $key = 'login:' . sha1($email . '|' . $ip);

        // Stop requests while this email/IP combination is rate limited.
        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);

            Log::warning('Login blocked (too many attempts)', [
                'ip' => $ip,
            ]);

            return back()
                ->withErrors([
                    'email' => "Too many login attempts. Please try again in {$minutes} minute(s).",
                ])
                ->withInput($request->only('email'));
        }

        // Look up the account by the email submitted by the login form.
        $user = User::with('role')
            ->where('email', $email)
            ->first();

        // Perform a password-hash operation even when the account does not exist.
        if ($user) {
            $passwordOk = Hash::check($credentials['password'], $user->password);
        } else {
            // Do a hash operation to reduce account-enumeration timing differences.
            Hash::make($credentials['password']);
            $passwordOk = false;
        }

        if (!$user || !$passwordOk) {
            RateLimiter::hit($key, self::LOCK_SECONDS);

            Log::warning('Failed school admin login', [
                'ip' => $ip,
                'email' => mb_substr($email, 0, 100),
            ]);

            return back()
                ->withErrors(['email' => self::WRONG_LOGIN])
                ->withInput($request->only('email'));
        }

        // Check account and role status only after validating the password.
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

        // Super admins must use the separate super admin login page.
        if ($user->role->name === 'super_admin') {
            return back()
                ->withErrors([
                    'email' => 'Please use the Super Admin login page for this account.',
                ])
                ->withInput($request->only('email'));
        }

        // Keep password hashes current when the configured hashing settings change.
        if (Hash::needsRehash($user->password)) {
            $user->forceFill([
                'password' => Hash::make($credentials['password']),
            ])->save();
        }

        // Password is valid: clear the failed-attempt counter and start the session.
        RateLimiter::clear($key);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Require two-factor verification again for every new login.
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

        // Continue the existing two-factor authentication flow.
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
