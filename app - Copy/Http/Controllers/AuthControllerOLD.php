<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showSuperadminLogin()
    {
        return view('auth.admin_login'); // Create this Blade file
    }

    public function superadminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'superadmin']))) {
            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or not a superadmin']);
    }
}
