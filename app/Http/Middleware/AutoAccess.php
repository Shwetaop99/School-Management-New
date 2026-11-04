<?php

namespace App\Http\Middleware;

use App\Support\Access;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $name       = $request->route()?->getName() ?? '';
        $permission = Access::permissionFor($name, $request->method());

        if ($permission === null || Access::allows($request->user(), $permission)) {
            return $next($request);
        }

        abort(403, config('app.debug')
            ? "Missing permission: {$permission}"
            : 'You do not have permission to access this page.');
    }
}