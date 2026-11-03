<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            abort(403);
        }

        if ($user->role->name === 'super_admin') {
            return $next($request);
        }

        $routeName = $request->route()?->getName() ?? '';

$action = match (true) {
    str_ends_with($routeName, '.create')  => 'create',
    str_ends_with($routeName, '.edit')    => 'edit',
    str_ends_with($routeName, '.destroy') => 'delete',
    default => match ($request->method()) {
        'POST'         => 'create',
        'PUT', 'PATCH' => 'edit',
        'DELETE'       => 'delete',
        default        => 'view',
    },
};

        abort_unless($user->hasPermission("{$module}.{$action}"), 403);

        return $next($request);
    }
}