<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MyFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate(['doctor_id' => 'required|integer']);

        $existing = MyFavourite::where('user_id', Auth::id())
            ->where('doctor_id', $request->doctor_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 200, 'action' => 'removed']);
        }

        MyFavourite::create(['user_id' => Auth::id(), 'doctor_id' => $request->doctor_id]);
        return response()->json(['status' => 200, 'action' => 'added']);
    }
}
