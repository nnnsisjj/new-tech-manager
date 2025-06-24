<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403);
        }

        if ($role === 'teacher' && !$user->isTeacher()) {
            abort(403);
        }

        if ($role === 'student' && !$user->isStudent()) {
            abort(403);
        }

        return $next($request);
    }
}