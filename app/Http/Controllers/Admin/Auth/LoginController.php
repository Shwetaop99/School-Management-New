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
<<<<<<< HEAD
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
=======
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $ip  = $request->ip();
        $key = 'login:' . sha1(mb_strtolower(trim($credentials['email'])) . '|' . $ip);

        /*
        |--------------------------------------------------------------------------
        | UPDATE 1: limit wrong tries per email + computer
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
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

<<<<<<< HEAD
        if (!$user) {
            return back()
                ->withErrors([
                    'login' => 'Invalid Login ID/email or password.',
                ])
                ->withInput($request->only('login'));
=======
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
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
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
<<<<<<< HEAD
                ->withInput($request->only('login'));
=======
                ->withInput($request->only('email'));
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        }

        if (!$user->role) {
            return back()
                ->withErrors([
                    'login' => 'No role has been assigned to this account. Please contact the administrator.',
                ])
<<<<<<< HEAD
                ->withInput($request->only('login'));
=======
                ->withInput($request->only('email'));
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        }

        if ($user->role->status !== 'Active') {
            return back()
                ->withErrors([
                    'login' => 'The role assigned to this account is inactive. Please contact the administrator.',
                ])
<<<<<<< HEAD
                ->withInput($request->only('login'));
=======
                ->withInput($request->only('email'));
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        }

        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
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

=======
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
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        $request->session()->forget('two_factor_verified');

        $request->session()->put('user_role', $user->role->name);

<<<<<<< HEAD
        $request->session()->put(
            'user_role',
            $user->role->name
        );

        /*
        |--------------------------------------------------------------------------
        | Continue Existing Two-Factor Flow
        |--------------------------------------------------------------------------
        */
=======
        // UPDATE 3: write it in the activity log
        $this->record('User logged in (password OK, waiting for 2FA)', $user, $ip);
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c

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
<<<<<<< HEAD
}
=======

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
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
