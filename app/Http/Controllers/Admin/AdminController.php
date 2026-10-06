<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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


class AdminController extends Controller
{
    use AuthenticatesUsers;


	public function dashboard()
	{
		$user = Auth::user();
		$userRole = UserRole::where('user_id', $user->id)->first();
		
		if (!$user || !$userRole || $userRole->role !== 'admin') {
			return redirect('/');
		}

		$totalDoctors         = DB::table('doctors')->count();
		$totalHospitals       = DB::table('hospitals')->count();
		$totalUsers           = DB::table('users')->join('user_roles','users.id','=','user_roles.user_id')->where('user_roles.role','doctor')->count();
		$totalSpecializations = DB::table('specializations')->count();
		$recentDoctors        = DB::table('doctors')->orderByDesc('id')->limit(5)->get();
		$recentUsers          = DB::table('users')->join('user_roles','users.id','=','user_roles.user_id')->where('user_roles.role','doctor')->orderByDesc('users.id')->limit(5)->get();
		$activeMemberships    = DB::table('user_doctor_role_membership')->where('membership_subscription_end_date','>=',now()->toDateString())->count();
		$onlineDoctors        = DB::table('users')
			->join('user_roles','users.id','=','user_roles.user_id')
			->where('user_roles.role','doctor')
			->where('users.last_seen','>=',now()->subMinutes(5))
			->count();
		$onlineDoctorsList    = DB::table('users')
			->join('user_roles','users.id','=','user_roles.user_id')
			->where('user_roles.role','doctor')
			->where('users.last_seen','>=',now()->subMinutes(5))
			->select('users.id','users.name','users.email','users.profile_image','users.last_seen')
			->orderByDesc('users.last_seen')
			->get();

		$recentAppointments = DB::table('prescription_invoice')
			->join('invoice_master', 'prescription_invoice.invoice_master_id', '=', 'invoice_master.id')
			->join('doctors', 'invoice_master.doctor_id', '=', 'doctors.id')
			->leftJoin('users', 'prescription_invoice.user_id', '=', 'users.id')
			->where('prescription_invoice.created_at', '>=', now()->subDays(2)->startOfDay())
			->select(
				'prescription_invoice.id',
				'prescription_invoice.patient_name',
				'prescription_invoice.patient_phone_no',
				'prescription_invoice.created_at as booked_at',
				'prescription_invoice.status',
				'users.name as user_name',
				'users.email as user_email',
				'doctors.name as doctor_name',
				'invoice_master.hospital_clinic_name'
			)
			->orderByDesc('prescription_invoice.created_at')
			->get();

		return view('admin.dashboard', compact(
			'totalDoctors','totalHospitals','totalUsers',
			'totalSpecializations','recentDoctors','recentUsers',
			'activeMemberships','onlineDoctors','onlineDoctorsList',
			'recentAppointments'
		));
	}

      

    public function editProfile()
    {
        $user = Auth::user();
        return view('admin.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone_no'      => 'nullable|string|max:15',
            'password'      => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name     = $request->name;
        $user->phone_no = $request->phone_no;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $image    = $request->file('profile_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('upload/profile_images', $filename, 'public');
            $user->profile_image = $filename;
        }

        $user->save();

        return redirect()->route('admin.edit-profile')->with('success', 'Profile updated successfully.');
    }

    public function logout(Request $request) {

		#$cur_date = DB::select("select NOW() as date");
		#// print_r($cur_date); //exit();
		#if(session('Role_ID')){
		#	if($request->session()->has('User_ID')){
		#		$loginData['Logout_Time'] = $cur_date[0]->date;
		#		$loginData['Login_Status'] = $request->type;
		#		DB::table("Employee_Logs")->where("Auto_ID", "=",  session('employee_current_id'))->update($loginData);
		#	}
		#}

		$this->guard()->logout();

		$request->session()->invalidate();

		return redirect('/');
	}

}

?>
