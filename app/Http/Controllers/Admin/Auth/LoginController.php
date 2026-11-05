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
     * Handle login using Login ID or email.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $login = trim($credentials['login']);

        /*
        |--------------------------------------------------------------------------
        | Find User By Login ID Or Email
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
            ->where(function ($query) use ($login) {
                $query->where('login_id', $login)
                    ->orWhere('email', $login);
            })
            ->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'login' => 'Invalid Login ID/email or password.',
                ])
                ->withInput($request->only('login'));
        }

        /*
        |--------------------------------------------------------------------------
        | The password is correct. Now check the account.
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Keep Super Admin Login Separate
        |--------------------------------------------------------------------------
        */

        if ($user->role->name === 'super_admin') {
            return back()
                ->withErrors([
                    'login' => 'Please use the Super Admin login page for this account.',
                ])
                ->withInput($request->only('login'));
        }

        /*
|--------------------------------------------------------------------------
| Authenticate User And Password
|--------------------------------------------------------------------------
*/

$remember = $request->boolean('remember');

if (!Auth::attempt([
    'email' => $user->email,
    'password' => $credentials['password'],
], $remember)) {
    return back()
        ->withErrors([
            'login' => 'Invalid Login ID/email or password.',
        ])
        ->withInput($request->only('login'));
}

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Reset Two-Factor Verification
        |--------------------------------------------------------------------------
        */

        $request->session()->forget('two_factor_verified');

        $request->session()->put('user_role', $user->role->name);

        $request->session()->put(
            'user_role',
            $user->role->name
        );

        /*
        |--------------------------------------------------------------------------
        | Continue Existing Two-Factor Flow
        |--------------------------------------------------------------------------
        */

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
}
