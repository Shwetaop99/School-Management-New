<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('super_admin');

        if (! $guard->check()) {
            return redirect()->route('super-admin.login');
        }

        $user = $guard->user();
        $user->loadMissing('role');

        if (
            $user->status !== 'Active' ||
            ! $user->role ||
            $user->role->status !== 'Active' ||
            $user->role->name !== 'super_admin'
        ) {
            $guard->logout();

            $request->session()->forget([
                'super_admin_2fa_verified',
                'super_admin_2fa_pending',
            ]);

            return redirect()->route('super-admin.login')
                ->withErrors([
                    'email' => 'Your account is not authorized for Super Admin access.',
                ]);
        }

        return $next($request);
    }
}
