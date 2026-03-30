<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $role = Auth::user()->role?->role ?? null;
        if ($role !== 'user') {
            Auth::logout();
            return redirect()->route('user.login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
