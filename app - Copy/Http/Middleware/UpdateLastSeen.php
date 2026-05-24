<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UpdateLastSeen
{
    // Routes jahan last_seen update NAHI hona chahiye
    protected $excludePaths = [
        'login', 'logout', 'register',
        'password/*', 'user/login', 'user/register',
        'choose-login', '/',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Excluded paths pe update mat karo
        foreach ($this->excludePaths as $path) {
            if ($request->is($path)) {
                return $response;
            }
        }

        // Laravel Auth (doctor/admin — Auth::attempt se login)
        if (Auth::check()) {
            DB::table('users')
                ->where('id', Auth::id())
                ->update(['last_seen' => now()]);
        }
        // Session-based fallback — sirf doctor/admin panel routes pe
        elseif (Session::has('user_id') && $request->is('doctor/*', 'admin/*')) {
            DB::table('users')
                ->where('id', Session::get('user_id'))
                ->update(['last_seen' => now()]);
        }

        return $response;
    }
}
