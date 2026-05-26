<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\PrescriptionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private function profilePicUrl($filename)
    {
        return $filename ? asset('storage/uploads/profile_images/' . $filename) : null;
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], [
            'email.unique' => 'This email is already registered. Please use a different email or login.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 422,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'api_token' => Str::random(60),
        ]);

        UserRole::create(['user_id' => $user->id, 'role' => 'user']);

        return response()->json([
            'status'  => 201,
            'message' => 'Registration successful.',
            'token'   => $user->api_token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 401, 'message' => 'Invalid email or password.'], 401);
        }

        if ($user->is_delete == 1) {
            return response()->json(['status' => 403, 'message' => 'This account has been deleted. Please contact support.'], 403);
        }

        if ($user->role?->role !== 'user') {
            return response()->json(['status' => 403, 'message' => 'Unauthorized access.'], 403);
        }

        $user->api_token = Str::random(60);
        if ($request->filled('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
        }
        $user->save();

        return response()->json([
            'status'  => 200,
            'message' => 'Login successful.',
            'token'   => $user->api_token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $user = $request->auth_user;
        if ($request->filled('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }
        return response()->json(['status' => 200, 'message' => 'FCM token updated.']);
    }

    public function profile(Request $request)
    {
        $user = $request->auth_user;

        return response()->json([
            'status' => 200,
            'user'   => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'phone_no'    => $user->phone_no,
                'address'     => $user->address,
                'gender'      => $user->gender,
                'dob'         => $user->dob,
                'profile_image' => $this->profilePicUrl($user->profile_image),
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->auth_user;

        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'nullable|email|unique:users,email,' . $user->id,
            'phone_no'    => 'nullable|digits_between:10,15|unique:users,phone_no,' . $user->id,
            'address'     => 'nullable|string|max:255',
            'gender'      => 'nullable|in:Male,Female,Other',
            'dob'         => 'nullable|date',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone_no', 'address', 'gender', 'dob']);

        if ($request->hasFile('profile_image')) {
            $file     = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/profile_images', $filename, 'public');
            $data['profile_image'] = $filename;
        }

        $user->update($data);
        $user->refresh();

        return response()->json([
            'status'  => 200,
            'message' => 'Profile updated successfully.',
            'user'    => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'phone_no'    => $user->phone_no,
                'address'     => $user->address,
                'gender'      => $user->gender,
                'dob'         => $user->dob,
                'profile_image' => $this->profilePicUrl($user->profile_image),
            ],
        ]);
    }

    public function myBookings(Request $request)
    {
        $user = $request->auth_user;

        $bookings = PrescriptionInvoice::with('invoiceMaster.doctor')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id'             => $booking->id,
                    'invoice_number' => $booking->invoice_number,
                    'patient_name'   => $booking->patient_name,
                    'age'            => $booking->age,
                    'gender'         => $booking->gender,
                    'booking_date'   => $booking->booking_date,
                    'booking_time'   => $booking->booking_time,
                    'doctor'         => $booking->invoiceMaster?->doctor
                        ? [
                            'id'   => $booking->invoiceMaster->doctor->id,
                            'name' => $booking->invoiceMaster->doctor->name,
                        ]
                        : null,
                    'clinic' => $booking->invoiceMaster?->hospital_clinic_name,
                    'fee'    => $booking->invoiceMaster?->consultation_fee,
                ];
            });

        return response()->json([
            'status'   => 200,
            'bookings' => $bookings,
        ]);
    }
}
