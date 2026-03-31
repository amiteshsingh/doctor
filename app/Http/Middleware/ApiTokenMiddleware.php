<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => 401, 'message' => 'Token not provided.'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['status' => 401, 'message' => 'Invalid or expired token.'], 401);
        }

        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
