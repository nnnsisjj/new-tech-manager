<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Проверка аутентификации
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Проверка роли студента
        $user = Auth::user();
        if (!$user->isStudent()) {
            abort(403, 'Доступ только для студентов');
        }

        return $next($request);
    }
}