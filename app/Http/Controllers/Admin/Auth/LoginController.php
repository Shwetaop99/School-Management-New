<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        |
        | Users log in using the email address assigned to their account.
        |
        */

        $user = User::with('role')
            ->where('email', $credentials['email'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | User Not Found
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Account Status
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'Active') {
            return back()
                ->withErrors([
                    'email' => 'This account is inactive. Please contact the administrator.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Assigned Role
        |--------------------------------------------------------------------------
        */

        if (!$user->role) {
            return back()
                ->withErrors([
                    'email' => 'No role has been assigned to this account. Please contact the administrator.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Role Status
        |--------------------------------------------------------------------------
        */

        if ($user->role->status !== 'Active') {
            return back()
                ->withErrors([
                    'email' => 'The role assigned to this account is inactive. Please contact the administrator.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Authenticate Email + Password
        |--------------------------------------------------------------------------
        */

        $remember = $request->boolean('remember');

        if (!Auth::attempt(
            [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ],
            $remember
        )) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Reset 2FA Verification
        |--------------------------------------------------------------------------
        */

        $request->session()->forget('two_factor_verified');

        /*
        |--------------------------------------------------------------------------
        | Store User Role
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'user_role',
            $user->role->name
        );

        /*
        |--------------------------------------------------------------------------
        | Continue to 2FA
        |--------------------------------------------------------------------------
        */

        return redirect()->route('admin.2fa.setup');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}