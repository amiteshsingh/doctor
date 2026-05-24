<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DeleteUserController extends Controller
{
    public function show()
    {
        return view('delete-user');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email.'])->withInput();
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        // Check role = 'user'
        $role = DB::table('user_roles')->where('user_id', $user->id)->value('role');

        if ($role !== 'user') {
            return back()->withErrors(['email' => 'This account cannot be deleted from here.'])->withInput();
        }

        // Mark as deleted
        User::where('id', $user->id)->update(['is_delete' => 1]);

        return redirect()->route('delete.user')->with('success', 'Your account has been successfully deleted.');
    }
}
