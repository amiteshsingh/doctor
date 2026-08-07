<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppUsersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::whereHas('role', fn($q) => $q->where('role', 'user'))
            ->when($search, fn($q) => $q->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone_no', 'like', "%$search%");
            }))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $total      = User::whereHas('role', fn($q) => $q->where('role', 'user'))->count();
        $withToken  = User::whereHas('role', fn($q) => $q->where('role', 'user'))->whereNotNull('fcm_token')->count();
        $activeToday = User::whereHas('role', fn($q) => $q->where('role', 'user'))
            ->whereDate('last_seen', today())->count();

        return view('admin.app_users.index', compact('users', 'total', 'withToken', 'activeToday', 'search'));
    }

    public function destroy($id)
    {
        DB::table('user_roles')->where('user_id', $id)->delete();
        User::where('id', $id)->delete();
        return redirect()->route('admin.app-users.index')->with('success', 'User deleted successfully.');
    }
}
