<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\PrescriptionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\FirebaseNotification;

class UserController extends Controller
{
    private function profilePicUrl($filename)
    {
        return $filename ? asset('storage/uploads/profile_images/' . $filename) : null;
    }

    public function appVersion()
    {
        return response()->json([
            'status'       => 200,
            'version_code' => 41,
            'version_name' => '3.9',
            'force_update' => false,
            'message'      => 'Update Is Available!',
            'store_url'    => 'https://play.google.com/store/apps/details?id=com.rogisewa',
        ]);
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
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'api_token'  => Str::random(60),
            'ip_address' => $request->ip(),
        ]);

        UserRole::create(['user_id' => $user->id, 'role' => 'user']);

        return response()->json([
            'status'  => 201,
            'message' => 'Registration successful.',
            'token'   => $user->api_token,
            'user'         => [
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

    public function rescheduleBooking(Request $request)
    {
        $request->validate([
            'booking_id'   => 'required|integer',
            'booking_date' => 'required|date',
            'booking_time' => 'required|string',
        ]);

        $user    = $request->auth_user;
        $booking = PrescriptionInvoice::where('id', $request->booking_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return response()->json(['status' => 404, 'message' => 'Booking not found.']);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['status' => 400, 'message' => 'Cancelled booking cannot be modified.']);
        }

        // Check 1 hour restriction
        $now         = \Carbon\Carbon::now('Asia/Kolkata');
        $bookingDT   = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->booking_time, 'Asia/Kolkata');
        $diffMinutes = $now->diffInMinutes($bookingDT, false);

        if ($diffMinutes <= 60 && $diffMinutes >= 0) {
            return response()->json(['status' => 400, 'message' => 'Booking cannot be modified within 1 hour of appointment time.']);
        }

        if ($diffMinutes < 0) {
            return response()->json(['status' => 400, 'message' => 'Past bookings cannot be modified.']);
        }

        // Check new slot not already booked
        $newTime = $request->booking_time;
        $conflict = PrescriptionInvoice::where('invoice_master_id', $booking->invoice_master_id)
            ->where('booking_date', $request->booking_date)
            ->whereRaw('LOWER(booking_time) = ?', [strtolower($newTime)])
            ->where('id', '!=', $booking->id)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'cancelled'); })
            ->exists();

        if ($conflict) {
            return response()->json(['status' => 409, 'message' => 'This slot is already booked. Please select another time.']);
        }

        $booking->booking_date = $request->booking_date;
        $booking->booking_time = $newTime;
        $booking->save();

        // Notify doctor
        $doctorUser = \App\Models\User::whereHas('role', fn($q) => $q->where('role', 'doctor'))
            ->whereHas('invoiceMasters', fn($q) => $q->where('id', $booking->invoice_master_id))
            ->first();
        if ($doctorUser && $doctorUser->fcm_token) {
            \App\Services\FirebaseNotification::send(
                $doctorUser->fcm_token,
                '🔄 Appointment Rescheduled',
                "{$booking->patient_name} ne appointment {$request->booking_date} ko {$newTime} pe reschedule kar di.",
                ['type' => 'appointment_rescheduled', 'appointment_id' => (string)$booking->id, 'screen' => 'Appointments']
            );
        }

        return response()->json(['status' => 200, 'message' => 'Booking rescheduled successfully.']);
    }

    public function cancelBooking(Request $request)
    {
        $user    = $request->auth_user;
        $booking = PrescriptionInvoice::where('id', $request->booking_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return response()->json(['status' => 404, 'message' => 'Booking not found.']);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['status' => 400, 'message' => 'Already cancelled.']);
        }

        // 1 hour restriction
        $now       = \Carbon\Carbon::now('Asia/Kolkata');
        $bookingDT = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->booking_time, 'Asia/Kolkata');
        $diffMins  = $now->diffInMinutes($bookingDT, false);

        if ($diffMins < 60 && $diffMins >= 0) {
            return response()->json(['status' => 400, 'message' => 'Appointment 1 ghante se kam samay mein hai — cancel nahi ho sakta.']);
        }

        if ($diffMins < 0) {
            return response()->json(['status' => 400, 'message' => 'Past booking cancel nahi ho sakti.']);
        }

        $booking->status     = 'cancelled';
        $booking->updated_at = now();
        $booking->save();

        // Cancel hone pe future reminders delete karo
        DB::table('booking_reminders')->where('invoice_id', $booking->id)->delete();

        // Notify doctor
        $doctorUser = \App\Models\User::whereHas('role', fn($q) => $q->where('role', 'doctor'))
            ->whereHas('invoiceMasters', fn($q) => $q->where('id', $booking->invoice_master_id))
            ->first();
        if ($doctorUser && $doctorUser->fcm_token) {
            \App\Services\FirebaseNotification::send(
                $doctorUser->fcm_token,
                '❌ Appointment Cancelled',
                "{$booking->patient_name} ne {$booking->booking_date} ko {$booking->booking_time} wali appointment cancel kar di.",
                ['type' => 'appointment_cancelled', 'appointment_id' => (string)$booking->id, 'screen' => 'Appointments']
            );
        }

        return response()->json(['status' => 200, 'message' => 'Booking cancelled successfully.']);
    }

    public function notifications(Request $request)
    {
        $user = $request->auth_user;
        $notifications = DB::table('notification_logs')
            ->whereNull('deleted_at')
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('target', 'all');
            })
            ->orderByDesc('created_at')
            ->limit(50)
            ->get(['id','title','message','is_read','created_at']);

        $unread = $notifications->where('is_read', false)->count();

        return response()->json([
            'status'        => 200,
            'notifications' => $notifications,
            'unread_count'  => $unread,
        ]);
    }

    public function markAllRead(Request $request)
    {
        $user = $request->auth_user;
        DB::table('notification_logs')
            ->whereNull('deleted_at')
            ->where('is_read', false)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('target', 'all');
            })
            ->update(['is_read' => true]);
        return response()->json(['status' => 200]);
    }

    public function deleteNotification(Request $request, $id)
    {
        $user = $request->auth_user;
        DB::table('notification_logs')
            ->where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('target', 'all');
            })
            ->update(['deleted_at' => now()]);
        return response()->json(['status' => 200, 'message' => 'Deleted.']);
    }

    public function submitTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
        ]);
        $user = $request->auth_user;
        $ticketId = DB::table('support_tickets')->insertGetId([
            'user_id'    => $user->id,
            'subject'    => $request->subject,
            'message'    => $request->message,
            'status'     => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('support_messages')->insert([
            'ticket_id'  => $ticketId,
            'sender'     => 'user',
            'message'    => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['status' => 201, 'message' => 'Ticket submitted successfully.']);
    }

    public function myTickets(Request $request)
    {
        $user = $request->auth_user;
        $tickets = DB::table('support_tickets')
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get(['id','subject','status','created_at','updated_at']);
        return response()->json(['status' => 200, 'tickets' => $tickets->map(function($t) {
            return [
                'id'         => $t->id,
                'subject'    => $t->subject,
                'status'     => $t->status,
                'created_at' => $t->created_at ? (string)$t->created_at : null,
                'updated_at' => $t->updated_at ? (string)$t->updated_at : null,
            ];
        })]);
    }

    public function ticketMessages(Request $request, $id)
    {
        $user = $request->auth_user;
        $ticket = DB::table('support_tickets')->where('id', $id)->where('user_id', $user->id)->first();
        if (!$ticket) return response()->json(['status' => 404, 'message' => 'Not found.']);
        $messages = DB::table('support_messages')->where('ticket_id', $id)->orderBy('created_at')->get();
        return response()->json(['status' => 200, 'ticket' => $ticket, 'messages' => $messages]);
    }

    public function replyTicket(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        $user = $request->auth_user;
        $ticket = DB::table('support_tickets')->where('id', $id)->where('user_id', $user->id)->first();
        if (!$ticket) return response()->json(['status' => 404]);
        if ($ticket->status === 'closed') return response()->json(['status' => 400, 'message' => 'Ticket closed hai.']);
        DB::table('support_messages')->insert([
            'ticket_id'  => $id,
            'sender'     => 'user',
            'message'    => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('support_tickets')->where('id', $id)->update(['status' => 'open', 'updated_at' => now()]);
        return response()->json(['status' => 200, 'message' => 'Message sent.']);
    }

    public function generateReasoningQuestions(Request $request)
    {
        $request->validate(['level' => 'required|in:easy,medium,hard']);
        $level = $request->level;

        $levelDesc = [
            'easy'   => 'basic level for beginners, simple questions',
            'medium' => 'intermediate level, moderate difficulty',
            'hard'   => 'advanced level for serious government job aspirants (SSC CGL/UPSC)',
        ][$level];

        $prompt = "You are a JSON generator. Generate exactly 10 reasoning questions for Indian government job exam ({$levelDesc}). Topics: Number Series, Analogy, Coding-Decoding, Blood Relations, Syllogism, Direction Sense, Ranking. Output ONLY a raw JSON array. No explanation, no markdown, no extra text before or after. Each explanation must not contain any quotes or special characters. Use this exact format: [{\"question\":\"...\",\"options\":[\"A) ...\",\"B) ...\",\"C) ...\",\"D) ...\"],\"answer\":\"A\",\"explanation\":\"...\",\"topic\":\"...\"}]";

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

        $response = \Illuminate\Support\Facades\Http::timeout(30)->post($url, [
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'temperature'     => 0.2,
                'maxOutputTokens' => 4096,
                'responseMimeType' => 'application/json',
            ],
        ]);

        if (!$response->successful()) {
            return response()->json(['status' => 500, 'message' => 'AI service unavailable. Please try again.'], 500);
        }

        // Try to get already-parsed JSON from response body directly
        // Use raw body string to avoid double JSON decoding issues
        $rawBody = $response->body();
        $body    = json_decode($rawBody, true);
        $text    = data_get($body, 'candidates.0.content.parts.0.text', '');

        if (empty($text)) {
            return response()->json(['status' => 500, 'message' => 'AI service unavailable. Please try again.'], 500);
        }

        // Clean up markdown fences
        $text = preg_replace('/```json\s*/i', '', $text);
        $text = preg_replace('/```\s*/i', '', $text);

        // Extract JSON array between first [ and last ]
        $start = strpos($text, '[');
        $end   = strrpos($text, ']');
        if ($start !== false && $end !== false && $end > $start) {
            $text = substr($text, $start, $end - $start + 1);
        }
        $text = trim($text);

        $questions = json_decode($text, true);

        // Last resort: remove stray control characters and retry
        if (!is_array($questions)) {
            $text      = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
            $questions = json_decode($text, true);
        }

        if (!is_array($questions) || count($questions) === 0) {
            return response()->json(['status' => 500, 'message' => 'Failed to parse questions. Please try again.'], 500);
        }

        return response()->json(['status' => 200, 'questions' => $questions, 'level' => $level]);
    }

    public function updateFcmToken(Request $request)
    {
        $user = $request->auth_user;
        if ($request->filled('fcm_token')) {
            $isNewUser = !$user->fcm_token && $user->created_at->diffInMinutes(now()) <= 10;
            $user->fcm_token = $request->fcm_token;
            $user->save();

            if ($isNewUser) {
                FirebaseNotification::send(
                    $user->fcm_token,
                    "Dear {$user->name} 👋",
                    'RogiSewa mein aapka swagat hai! 🎉 Hum aapki sehat ka dhyan rakhne ke liye hamesha yahan hain.',
                    ['type' => 'welcome', 'screen' => 'Home']
                );
                DB::table('notification_logs')->insert([
                    'user_id'     => $user->id,
                    'title'       => "Dear {$user->name} 👋",
                    'message'     => 'RogiSewa mein aapka swagat hai! 🎉 Hum aapki sehat ka dhyan rakhne ke liye hamesha yahan hain.',
                    'target'      => 'specific',
                    'target_type' => 'user',
                    'sent_count'  => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
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
                    'queue_number'   => $booking->queue_number,
                    'patient_name'   => $booking->patient_name,
                    'age'            => $booking->age,
                    'gender'         => $booking->gender,
                    'booking_date'   => $booking->booking_date,
                    'booking_time'   => $booking->booking_time,
                    'status'         => $booking->status ?? 'active',
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
