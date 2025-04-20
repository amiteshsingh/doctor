<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use Session;
use DB;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    public function dashboard(){
		
		$user = Auth::user();
		$userRole = UserRole::where('user_id', $user->id)->first();
		if (!$user || !$userRole || $userRole->role !== 'doctor') {
			return redirect('/');
		}
		
		return view('doctor.dashboard');
	}
}