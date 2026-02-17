<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // если не залогинен — пропускаем (auth middleware отдельно)
        if (!$user) {
            return $next($request);
        }

        // если заблокирован — выходим и на login
        if (property_exists($user, 'is_active') && !$user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Ваш аккаунт деактивирован. Обратитесь к администратору.']);
        }

        return $next($request);
    }
}
