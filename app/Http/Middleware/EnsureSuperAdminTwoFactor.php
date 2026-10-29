<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdminTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('super_admin')->user();

        if (! $user) {
            return redirect()->route('super-admin.login');
        }

        if (
    ! $user->super_admin_two_factor_enabled ||
    ! $user->super_admin_two_factor_secret
) {
            return redirect()->route('super-admin.security');
        }

        if (! $request->session()->get('super_admin_2fa_verified')) {
            return redirect()->route('super-admin.2fa.challenge');
        }

        return $next($request);
    }
}
