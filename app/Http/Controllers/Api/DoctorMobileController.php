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
use App\Models\PrescriptionInvoice;
use App\Services\FirebaseNotification;
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

    /** GET /api/v1/doctor/test-notification */
    public function testNotification(Request $request)
    {
        $user = $request->auth_user;
        if (!$user->fcm_token) {
            return response()->json(['status' => 400, 'msg' => 'No FCM token found for this user.']);
        }

        // Get access token directly and return any error
        $credPath = storage_path('app/rogisewa-b3189-dfa65691786a.json');
        if (!file_exists($credPath)) {
            return response()->json(['status' => 500, 'msg' => 'Firebase JSON file not found at: ' . $credPath]);
        }

        $creds = json_decode(file_get_contents($credPath), true);
        $now   = time();
        $payload = [
            'iss'   => $creds['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];

        // Build JWT
        $header  = $this->base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $pay     = $this->base64url(json_encode($payload));
        $data    = $header . '.' . $pay;
        openssl_sign($data, $signature, $creds['private_key'], 'SHA256');
        $jwt = $data . '.' . $this->base64url($signature);

        $tokenResp = \Illuminate\Support\Facades\Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        if (!$tokenResp->successful()) {
            return response()->json(['status' => 500, 'msg' => 'Failed to get access token', 'error' => $tokenResp->json()]);
        }

        $accessToken = $tokenResp->json('access_token');
        $projectId   = $creds['project_id'];

        $fcmResp = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
        ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
            'message' => [
                'token'        => $user->fcm_token,
                'notification' => ['title' => 'Test Notification', 'body' => 'Working!'],
            ],
        ]);

        return response()->json([
            'status'     => 200,
            'fcm_status' => $fcmResp->status(),
            'fcm_body'   => $fcmResp->json(),
            'success'    => $fcmResp->successful(),
        ]);
    }

    private function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
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

        // Image only upload
        if ($request->hasFile('profile_image') && !$request->filled('name')) {
            $image    = $request->file('profile_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('upload/profile_images', $filename, 'public');
            $user->profile_image = $filename;
            $user->save();
            return response()->json([
                'status' => 200,
                'msg'    => 'Profile image updated successfully.',
                'profile_image' => asset('storage/upload/profile_images/' . $filename),
            ]);
        }

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

    /** POST /api/v1/doctor/fcm-token */
    public function saveFcmToken(Request $request)
    {
        $user = $request->auth_user;
        $request->validate(['fcm_token' => 'required|string']);
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return response()->json(['status' => 200, 'msg' => 'FCM token saved.']);
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

        // ── Send push notification to doctor ──
        if ($user->fcm_token) {
            \App\Services\FirebaseNotification::send(
                $user->fcm_token,
                '📅 New Appointment Booked',
                "{$request->patient_name} ne {$request->booking_date} ko {$request->booking_time} pe appointment book ki hai.",
                [
                    'type'         => 'new_appointment',
                    'appointment_id' => (string)$id,
                    'patient_name' => $request->patient_name,
                    'booking_date' => $request->booking_date,
                    'booking_time' => $request->booking_time,
                ]
            );
        }

        return response()->json(['status' => 200, 'msg' => 'Appointment booked successfully.', 'id' => $id]);
    }

    /** POST /api/v1/doctor/appointments/cancel/{id} */
    public function cancelAppointment(Request $request, $id)
    {
        $user = $request->auth_user;
        $inv  = PrescriptionInvoice::with('invoiceMaster')
            ->whereHas('invoiceMaster', fn($q) => $q->where('added_by', $user->id))
            ->find($id);

        if (!$inv) {
            return response()->json(['status' => 404, 'msg' => 'Appointment not found.'], 404);
        }

        $inv->status     = 'cancelled';
        $inv->updated_at = now();
        $inv->save();

        // User ko notification bhejo
        if ($inv->user_id) {
            $patient = User::find($inv->user_id);
            if ($patient && $patient->fcm_token) {
                $date = \Carbon\Carbon::parse($inv->booking_date)->format('d M Y');
                FirebaseNotification::send(
                    $patient->fcm_token,
                    '❌ अपॉइंटमेंट रद्द',
                    "{$date} को {$inv->booking_time} बजे की आपकी अपॉइंटमेंट डॉक्टर द्वारा रद्द कर दी गई है। यह स्लॉट अब बुकिंग के लिए उपलब्ध है।",
                    ['type' => 'cancel', 'invoice_id' => (string)$inv->id]
                );
            }
        }

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

    /** POST /api/v1/doctor/invoice-masters/save */
    public function saveInvoiceMaster(Request $request)
    {
        $user = $request->auth_user;
        $request->validate([
            'hospital_clinic_name' => 'required|string|max:255',
            'consultation_fee'     => 'required|numeric',
            'start_time'           => 'required|string',
            'end_time_slot'        => 'required|string',
            'duration_time_slot'   => 'required|integer|min:1',
        ]);

        $doctor = DB::table('doctors')->where('added_by', $user->id)->first();
        if (!$doctor) {
            return response()->json(['status' => 404, 'msg' => 'Doctor profile not found.'], 404);
        }

        $data = [
            'hospital_clinic_name' => $request->hospital_clinic_name,
            'consultation_fee'     => $request->consultation_fee,
            'start_time'           => $request->start_time,
            'end_time_slot'        => $request->end_time_slot,
            'duration_time_slot'   => $request->duration_time_slot,
            'booking_mode'         => $request->booking_mode ?? 'ONLINE',
            'address'              => $request->address ?? '',
            'phone_no'             => $request->phone_no ?? '',
            'email'                => $request->email ?? '',
            'updated_at'           => now(),
            'updated_by'           => $user->id,
        ];

        if ($request->filled('id')) {
            DB::table('invoice_master')
                ->where('id', $request->id)
                ->where('added_by', $user->id)
                ->update($data);
            return response()->json(['status' => 200, 'msg' => 'Clinic updated successfully.']);
        }

        $data['doctor_id']   = $doctor->id;
        $data['added_by']    = $user->id;
        $data['created_at']  = now();
        $id = DB::table('invoice_master')->insertGetId($data);
        return response()->json(['status' => 200, 'msg' => 'Clinic added successfully.', 'id' => $id]);
    }

    /** DELETE /api/v1/doctor/invoice-masters/{id} */
    public function deleteInvoiceMaster(Request $request, $id)
    {
        $user = $request->auth_user;
        DB::table('invoice_master')->where('id', $id)->where('added_by', $user->id)->delete();
        return response()->json(['status' => 200, 'msg' => 'Clinic deleted successfully.']);
    }

    // ─── BOOKED SLOTS ─────────────────────────────────────────────────────────

    /** POST /api/v1/doctor/booked-slots */
    public function bookedSlots(Request $request)
    {
        $user = $request->auth_user;
        $request->validate([
            'invoice_master_id' => 'required|integer',
            'date'              => 'required|date',
        ]);

        // Verify master belongs to this doctor
        $master = DB::table('invoice_master')
            ->where('id', $request->invoice_master_id)
            ->where('added_by', $user->id)
            ->first();

        if (!$master) {
            return response()->json(['status' => 403, 'msg' => 'Invalid clinic selected.'], 403);
        }

        // Get already booked times for this date
        $bookedTimes = DB::table('prescription_invoice')
            ->where('invoice_master_id', $request->invoice_master_id)
            ->where('booking_date', $request->date)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'cancelled'); })
            ->pluck('booking_time')
            ->map(fn($t) => strtolower(trim($t)))
            ->toArray();

        // Generate slots from start_time to end_time with slot_duration interval
        $startTime    = $master->start_time        ?? '09:00:00';
        $endTime      = $master->end_time_slot      ?? '17:00:00';
        $slotDuration = $master->duration_time_slot ?? 15; // minutes

        $slots   = [];
        $current = strtotime($request->date . ' ' . $startTime);
        $end     = strtotime($request->date . ' ' . $endTime);
        $now     = time();

        while ($current < $end) {
            $label     = date('h:i A', $current);
            $isBooked  = in_array(strtolower($label), $bookedTimes);
            $isPast    = $current < $now;

            $slots[] = [
                'label'     => $label,
                'time'      => date('H:i', $current),
                'is_booked' => $isBooked,
                'is_past'   => $isPast,
            ];

            $current += $slotDuration * 60;
        }

        return response()->json(['status' => 200, 'slots' => $slots]);
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
