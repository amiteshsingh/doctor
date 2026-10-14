<?php

namespace App\Http\Middleware;

use App\Support\AdminModules;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminModulePermissionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeName = $request->route()?->getName();

        if ($routeName && str_starts_with($routeName, 'admin.permissions.') && (int) $user->id !== 1) {
            abort(403);
        }

        if ((int) $user->id === 1 || in_array($routeName, ['admin.dashboard', 'admin.edit-profile', 'admin.update-profile'], true)) {
            return $next($request);
        }

        $modules = AdminModules::modulesForRoute($routeName);
        if ($modules === []) {
            return $next($request);
        }

        $permissions = $request->attributes->get('adminModulePermissions', []);
        $ability = AdminModules::abilityForRoute($routeName, $request->method(), $request->path());
        foreach ($modules as $module) {
            if (AdminModules::allows((int) $user->id, $permissions, $module, 'access')
                && ($ability === 'access' || AdminModules::allows((int) $user->id, $permissions, $module, $ability))) {
                return $next($request);
            }
        }

        abort(403);
    }
}
