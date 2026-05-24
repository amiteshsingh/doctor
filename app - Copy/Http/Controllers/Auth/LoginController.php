<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    protected function redirectTo()
    {
        $user = Auth::user();
        $userRole = UserRole::where('user_id', $user->id)->first();
        // dd($userRole); die;
        if ($userRole) {
            if ($userRole->role === 'admin') {
                return '/admin/dashboard'; // Redirect admin
            } elseif ($userRole->role === 'doctor') {
                return '/doctor/dashboard'; // Redirect doctor
            }
        }
        // return '/home'; // Default redirect for other users
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }


    // Show the login form (if custom login page)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            $user = Auth::user();
            $userRole = UserRole::where('user_id', $user->id)->first();

            // Store user data in session
            session([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $userRole->role
            ]);

            return redirect()->intended($this->redirectTo());
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout function
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

}
