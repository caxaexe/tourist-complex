<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Нет доступа');
        }

        return $next($request);
    }
}
