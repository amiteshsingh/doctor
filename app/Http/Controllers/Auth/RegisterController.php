<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserDoctorRoleMembership;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo = '/doctor/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:doctors,phone_no'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            //'role' => ['required', 'in:admin,doctor'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        \Log::info('Register data: ', ['name'=>$data['name'], 'email'=>$data['email'], 'phone'=>$data['phone'] ?? 'NOT SET']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign role
        UserRole::create([
            'user_id' => $user->id,
            'role' => 'doctor',
        ]);

        // Save in doctors table
        try {
            Doctor::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone_no' => $data['phone'] ?? '',
                'added_by' => $user->id,
                'added_on' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Doctor create failed: ' . $e->getMessage());
        }

        UserDoctorRoleMembership::create([
            'user_id'                        => $user->id,
            'membership_amount'              => 0.00,
            'membership_subscription_date'   => now()->toDateString(),
            'membership_subscription_end_date' => now()->addYear()->toDateString(),
            'attendance_permission'          => 1,
            'invoice_permission'             => 1,
        ]);

        session([
            'user_id'   => $user->id,
            'user_email'=> $user->email,
            'user_role' => 'doctor',
        ]);

        return $user;

    }
}
