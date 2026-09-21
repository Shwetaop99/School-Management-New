<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | User must be logged in
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin has full access
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Check requested permission
        |--------------------------------------------------------------------------
        */

        if (!$user->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}