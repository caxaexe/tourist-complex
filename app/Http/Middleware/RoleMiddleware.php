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
            return redirect()->route('login');
        }

        $flat = [];
        foreach ($roles as $r) {
            $parts = array_map('trim', explode(',', $r));
            foreach ($parts as $p) if ($p !== '') $flat[] = $p;
        }

        if (empty($flat)) {
            abort(403);
        }

        if (!$user->hasAnyRole($flat)) {
            abort(403);
        }

        return $next($request);
    }
}
