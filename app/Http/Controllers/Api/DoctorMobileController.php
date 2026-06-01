<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Doctor;
use App\Models\UserDoctorRoleMembership;
use Str;

class DoctorMobileController extends Controller
{
    // ─── AUTH ────────────────────────────────────────────────────────────────

    /** POST /api/v1/doctor/login */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 401, 'msg' => 'Invalid credentials.'], 401);
        }

        $role = UserRole::where('user_id', $user->id)->first();
        if (!$role || $role->role !== 'doctor') {
            return response()->json(['status' => 403, 'msg' => 'Access denied. Not a doctor account.'], 403);
        }

        // Generate / refresh token
        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        $doctor   = Doctor::where('added_by', $user->id)->first();
        $membership = UserDoctorRoleMembership::where('user_id', $user->id)->first();

        return response()->json([
            'status' => 200,
            'msg'    => 'Login successful.',
            'token'  => $token,
            'user'   => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone_no'      => $user->phone_no,
                'profile_image' => $user->profile_image
                    ? asset('storage/upload/profile_images/' . $user->profile_image)
                    : null,
            ],
            'doctor'      => $doctor,
            'permissions' => [
                'attendance_permission' => $membership ? (bool)$membership->attendance_permission : false,
                'invoice_permission'    => $membership ? (bool)$membership->invoice_permission    : false,
            ],
        ]);
    }

    /** POST /api/v1/doctor/logout */
    public function logout(Request $request)
    {
        $user = $request->auth_user;
        $user->api_token = null;
        $user->save();
        return response()->json(['status' => 200, 'msg' => 'Logged out successfully.']);
    }

    // ─── PROFILE ─────────────────────────────────────────────────────────────

    /** GET /api/v1/doctor/profile */
    public function profile(Request $request)
    {
        $user   = $request->auth_user;
        $doctor = Doctor::with(['availability','educations','languages.language','specializations.specialization','locations'])
                    ->where('added_by', $user->id)->first();

        return response()->json([
            'status' => 200,
            'user'   => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone_no'      => $user->phone_no,
                'gender'        => $user->gender,
                'dob'           => $user->dob,
                'address'       => $user->address,
                'state'         => $user->state,
                'country'       => $user->country,
                'pin_code'      => $user->pin_code,
                'profile_image' => $user->profile_image
                    ? asset('storage/upload/profile_images/' . $user->profile_image)
                    : null,
            ],
            'doctor' => $doctor,
        ]);
    }

    /** POST /api/v1/doctor/profile/update */
    public function updateProfile(Request $request)
    {
        $user = $request->auth_user;
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone_no' => 'nullable|string|max:15',
            'gender'   => 'nullable|string',
            'dob'      => 'nullable|date',
            'address'  => 'nullable|string',
            'state'    => 'nullable|string',
            'country'  => 'nullable|string',
            'pin_code' => 'nullable|string|max:10',
            'password' => 'nullable|min:6',
        ]);

        $user->name     = $request->name;
        $user->phone_no = $request->phone_no;
        $user->gender   = $request->gender;
        $user->dob      = $request->dob;
        $user->address  = $request->address;
        $user->state    = $request->state;
        $user->country  = $request->country;
        $user->pin_code = $request->pin_code;

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

        return response()->json(['status' => 200, 'msg' => 'Profile updated successfully.']);
    }

    // ─── APPOINTMENTS (Prescription Invoice) ─────────────────────────────────

    /** GET /api/v1/doctor/appointments */
    public function appointments(Request $request)
    {
        $user = $request->auth_user;
        $query = DB::table('prescription_invoice')
            ->join('invoice_master', 'prescription_invoice.invoice_master_id', '=', 'invoice_master.id')
            ->join('doctors', 'invoice_master.doctor_id', '=', 'doctors.id')
            ->where('invoice_master.added_by', $user->id)
            ->select(
                'prescription_invoice.*',
                'invoice_master.hospital_clinic_name',
                'invoice_master.consultation_fee',
                'doctors.name as doctor_name',
                'doctors.profile_pic'
            )
            ->orderBy('prescription_invoice.booking_date', 'desc')
            ->orderBy('prescription_invoice.id', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('prescription_invoice.booking_date', $request->date);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('prescription_invoice.patient_name', 'like', "%$s%")
                  ->orWhere('prescription_invoice.patient_phone_no', 'like', "%$s%")
                  ->orWhere('prescription_invoice.invoice_number', 'like', "%$s%");
            });
        }
        if ($request->filled('status')) {
            $query->where('prescription_invoice.status', $request->status);
        }

        $perPage = $request->get('per_page', 20);
        $page    = $request->get('page', 1);
        $total   = $query->count();
        $records = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        return response()->json([
            'status' => 200,
            'data'   => $records,
            'meta'   => ['total' => $total, 'page' => $page, 'per_page' => $perPage],
        ]);
    }

    /** POST /api/v1/doctor/appointments/add */
    public function addAppointment(Request $request)
    {
        $user = $request->auth_user;
        $request->validate([
            'invoice_master_id' => 'required|integer',
            'patient_name'      => 'required|string|max:255',
            'booking_date'      => 'required|date',
            'booking_time'      => 'required|string',
        ]);

        // Verify invoice_master belongs to this doctor
        $master = DB::table('invoice_master')
            ->where('id', $request->invoice_master_id)
            ->where('added_by', $user->id)
            ->first();
        if (!$master) {
            return response()->json(['status' => 403, 'msg' => 'Invalid invoice master.'], 403);
        }

        // Duplicate slot check
        $alreadyBooked = DB::table('prescription_invoice')
            ->where('invoice_master_id', $request->invoice_master_id)
            ->where('booking_date', $request->booking_date)
            ->whereRaw('LOWER(booking_time) = ?', [strtolower($request->booking_time)])
            ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'cancelled'); })
            ->exists();

        if ($alreadyBooked) {
            return response()->json(['status' => 409, 'msg' => 'This slot is already booked.'], 409);
        }

        $id = DB::table('prescription_invoice')->insertGetId([
            'invoice_master_id' => $request->invoice_master_id,
            'invoice_number'    => 'INV-' . now()->format('YmdHis'),
            'patient_name'      => $request->patient_name,
            'patient_address'   => $request->patient_address ?? '',
            'patient_phone_no'  => $request->patient_phone_no ?? '',
            'age'               => $request->age ?? '',
            'gender'            => $request->gender ?? '',
            'booking_date'      => $request->booking_date,
            'booking_time'      => $request->booking_time,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return response()->json(['status' => 200, 'msg' => 'Appointment booked successfully.', 'id' => $id]);
    }

    /** POST /api/v1/doctor/appointments/cancel/{id} */
    public function cancelAppointment(Request $request, $id)
    {
        $user = $request->auth_user;
        $inv  = DB::table('prescription_invoice')
            ->join('invoice_master', 'prescription_invoice.invoice_master_id', '=', 'invoice_master.id')
            ->where('prescription_invoice.id', $id)
            ->where('invoice_master.added_by', $user->id)
            ->select('prescription_invoice.id')
            ->first();

        if (!$inv) {
            return response()->json(['status' => 404, 'msg' => 'Appointment not found.'], 404);
        }

        DB::table('prescription_invoice')->where('id', $id)->update([
            'status'     => 'cancelled',
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 200, 'msg' => 'Appointment cancelled.']);
    }

    /** GET /api/v1/doctor/invoice-masters */
    public function invoiceMasters(Request $request)
    {
        $user    = $request->auth_user;
        $masters = DB::table('invoice_master')
            ->join('doctors', 'invoice_master.doctor_id', '=', 'doctors.id')
            ->where('invoice_master.added_by', $user->id)
            ->select('invoice_master.*', 'doctors.name as doctor_name')
            ->orderBy('invoice_master.id', 'desc')
            ->get();

        return response()->json(['status' => 200, 'data' => $masters]);
    }

    // ─── STAFF ───────────────────────────────────────────────────────────────

    /** GET /api/v1/doctor/staff */
    public function staff(Request $request)
    {
        $user  = $request->auth_user;
        $staff = DB::table('doctor_staff')
            ->where('added_by', $user->id)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json(['status' => 200, 'data' => $staff]);
    }

    /** POST /api/v1/doctor/staff/save */
    public function saveStaff(Request $request)
    {
        $user = $request->auth_user;
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required',
        ]);

        $data = [
            'name'         => $request->name,
            'role'         => $request->role,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'salary'       => $request->salary,
            'joining_date' => $request->joining_date ?: null,
            'status'       => $request->status,
            'updated_at'   => now(),
        ];

        if ($request->filled('id')) {
            DB::table('doctor_staff')
                ->where('id', $request->id)
                ->where('added_by', $user->id)
                ->update($data);
            return response()->json(['status' => 200, 'msg' => 'Staff updated successfully.']);
        }

        $data['added_by']   = $user->id;
        $data['created_at'] = now();
        $id = DB::table('doctor_staff')->insertGetId($data);
        return response()->json(['status' => 200, 'msg' => 'Staff added successfully.', 'id' => $id]);
    }

    /** DELETE /api/v1/doctor/staff/{id} */
    public function deleteStaff(Request $request, $id)
    {
        $user = $request->auth_user;
        DB::table('doctor_staff')->where('id', $id)->where('added_by', $user->id)->delete();
        return response()->json(['status' => 200, 'msg' => 'Staff deleted.']);
    }

    // ─── STAFF ATTENDANCE ────────────────────────────────────────────────────

    /** GET /api/v1/doctor/staff/attendance?date=YYYY-MM-DD */
    public function staffAttendance(Request $request)
    {
        $user  = $request->auth_user;
        $date  = $request->get('date', now()->toDateString());
        $staff = DB::table('doctor_staff')
            ->where('added_by', $user->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $existing = DB::table('doctor_staff_attendance')
            ->where('added_by', $user->id)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('staff_id');

        return response()->json([
            'status'   => 200,
            'date'     => $date,
            'staff'    => $staff,
            'existing' => $existing,
        ]);
    }

    /** POST /api/v1/doctor/staff/attendance/save */
    public function saveAttendance(Request $request)
    {
        $user        = $request->auth_user;
        $date        = $request->date;
        $attendances = $request->attendance ?? [];

        foreach ($attendances as $staffId => $row) {
            DB::table('doctor_staff_attendance')->updateOrInsert(
                ['staff_id' => $staffId, 'attendance_date' => $date],
                [
                    'added_by'   => $user->id,
                    'status'     => $row['status'] ?? 'absent',
                    'check_in'   => !empty($row['check_in'])  ? $row['check_in']  : null,
                    'check_out'  => !empty($row['check_out']) ? $row['check_out'] : null,
                    'note'       => $row['note'] ?? null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return response()->json(['status' => 200, 'msg' => 'Attendance saved successfully.']);
    }

    /** GET /api/v1/doctor/staff/attendance/report?month=YYYY-MM */
    public function attendanceReport(Request $request)
    {
        $user  = $request->auth_user;
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $staff = DB::table('doctor_staff')
            ->where('added_by', $user->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $records = DB::table('doctor_staff_attendance')
            ->where('added_by', $user->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $mon)
            ->get()
            ->groupBy('staff_id');

        return response()->json([
            'status'  => 200,
            'month'   => $month,
            'staff'   => $staff,
            'records' => $records,
        ]);
    }

    // ─── DASHBOARD STATS ─────────────────────────────────────────────────────

    /** GET /api/v1/doctor/dashboard */
    public function dashboard(Request $request)
    {
        $user   = $request->auth_user;
        $today  = now()->toDateString();

        $totalAppointments = DB::table('prescription_invoice')
            ->join('invoice_master', 'prescription_invoice.invoice_master_id', '=', 'invoice_master.id')
            ->where('invoice_master.added_by', $user->id)
            ->count();

        $todayAppointments = DB::table('prescription_invoice')
            ->join('invoice_master', 'prescription_invoice.invoice_master_id', '=', 'invoice_master.id')
            ->where('invoice_master.added_by', $user->id)
            ->whereDate('prescription_invoice.booking_date', $today)
            ->where(function($q) { $q->whereNull('prescription_invoice.status')->orWhere('prescription_invoice.status', '!=', 'cancelled'); })
            ->count();

        $totalStaff = DB::table('doctor_staff')
            ->where('added_by', $user->id)
            ->where('status', 1)
            ->count();

        $doctor = Doctor::where('added_by', $user->id)->first();
        $membership = UserDoctorRoleMembership::where('user_id', $user->id)->first();

        return response()->json([
            'status' => 200,
            'data'   => [
                'total_appointments' => $totalAppointments,
                'today_appointments' => $todayAppointments,
                'total_staff'        => $totalStaff,
                'doctor'             => $doctor,
                'permissions'        => [
                    'attendance_permission' => $membership ? (bool)$membership->attendance_permission : false,
                    'invoice_permission'    => $membership ? (bool)$membership->invoice_permission    : false,
                ],
            ],
        ]);
    }
}
