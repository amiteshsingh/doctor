<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use App\Support\AdminModules;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $permissions = AdminModules::permissionsFor((int) Auth::id());
            $request->attributes->set('adminModulePermissions', $permissions);
            View::share('adminModulePermissions', $permissions);

            try {
                return $next($request);
            } finally {
                View::share('adminModulePermissions', []);
            }
        }
        return redirect('/login')->with('error', 'Unauthorized Access.');
    }
}
