<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

use Illuminate\Support\Facades\RateLimiter;


class SuperAdminTwoFactorController extends Controller
{
    public function challenge()
    {
        $user = Auth::guard('super_admin')->user();

        if (! $user) {
            return redirect()->route('super-admin.login');
        }

        if (! $user->super_admin_two_factor_enabled ||
            ! $user->super_admin_two_factor_secret) {
            return redirect()->route('super-admin.security');
        }

        if (session('super_admin_2fa_verified')) {
            return redirect()->route('super-admin.dashboard');
        }

        return view('super-admin.auth.2fa-challenge');
    }

    public function verify(Request $request)
{
    $user = Auth::guard('super_admin')->user();

    if (! $user) {
        return redirect()->route('super-admin.login');
    }

    $key = 'super-admin-2fa:' . $user->id . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 3)) {
        $seconds = RateLimiter::availableIn($key);

        return redirect()
            ->route('super-admin.2fa.challenge')
            ->withErrors([
                'code' => "Too many failed 2FA attempts. Try again in {$seconds} seconds.",
            ]);
    }

    $validator = \Illuminate\Support\Facades\Validator::make(
        $request->all(),
        ['code' => ['required', 'digits:6']]
    );

    if ($validator->fails()) {
        RateLimiter::hit($key, 900);

        return back()->withErrors([
            'code' => 'Enter a valid six-digit authenticator code.',
        ]);
    }

    if (
        ! $user->super_admin_two_factor_enabled ||
        ! $user->super_admin_two_factor_secret
    ) {
        return redirect()->route('super-admin.security');
    }

    $google2fa = new Google2FA();

    $valid = $google2fa->verifyKey(
        $user->super_admin_two_factor_secret,
        $request->input('code')
    );

    if (! $valid) {
        RateLimiter::hit($key, 900);

        return back()->withErrors([
            'code' => 'Invalid authenticator code. Please try again.',
        ]);
    }

    RateLimiter::clear($key);

    $request->session()->put('super_admin_2fa_verified', true);
    $request->session()->forget('super_admin_2fa_pending');

    return redirect()->route('super-admin.dashboard');
}
}
