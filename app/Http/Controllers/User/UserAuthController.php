<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\PrescriptionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function showRegister()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone_no' => 'required|digits_between:10,15|unique:users,phone_no',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
        ]);

        UserRole::create(['user_id' => $user->id, 'role' => 'user']);

        Auth::login($user);

        return redirect()->route('user.profile')->with('success', 'Registration successful!');
    }

    public function showLogin()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid email or password.');
        }

        if ($user->role?->role !== 'user') {
            return back()->with('error', 'Unauthorized access.');
        }

        Auth::login($user, $request->boolean('remember'));

        $redirect = $request->query('redirect');
        $book = $request->query('book');
        $url = $redirect && str_starts_with($redirect, '/') ? $redirect : route('user.profile');
        if ($book) $url .= (str_contains($url, '?') ? '&' : '?') . 'book=1';
        return redirect($url);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login');
    }

    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'address'       => 'nullable|string|max:255',
            'gender'        => 'nullable|in:Male,Female,Other',
            'dob'           => 'nullable|date',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'address', 'gender', 'dob']);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            if (app()->environment('local')) {
                $file->move(public_path('uploads/profile_images'), $filename);
            } else {
                $file->storeAs('uploads/profile_images', $filename, 'public');
            }
            $data['profile_image'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function myBookings()
    {
        $bookings = PrescriptionInvoice::with('invoiceMaster.doctor')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.bookings', compact('bookings'));
    }
}
