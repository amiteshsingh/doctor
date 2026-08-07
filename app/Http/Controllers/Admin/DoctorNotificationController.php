<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorNotificationController extends Controller
{
    public function index()
    {
        $totalDoctors      = User::whereHas('role', fn($q) => $q->where('role', 'doctor'))->count();
        $doctorsWithToken  = User::whereHas('role', fn($q) => $q->where('role', 'doctor'))->whereNotNull('fcm_token')->count();
        $doctors           = User::whereHas('role', fn($q) => $q->where('role', 'doctor'))->whereNotNull('fcm_token')->get(['id', 'name', 'email']);
        $logs              = DB::table('notification_logs')->where('target_type', 'doctor')->orderByDesc('id')->limit(20)->get();

        return view('admin.notification.doctor', compact('totalDoctors', 'doctorsWithToken', 'doctors', 'logs'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'target'  => 'required|in:all,specific',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $title   = $request->title;
        $message = $request->message;
        $target  = $request->target;
        $sent    = 0;
        $failed  = 0;

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/notifications', $filename, 'public');
            $imageUrl = asset('storage/upload/notifications/' . $filename);
        }

        if ($target === 'all') {
            $users = User::whereHas('role', fn($q) => $q->where('role', 'doctor'))
                ->whereNotNull('fcm_token')
                ->distinct('fcm_token')
                ->get(['id', 'fcm_token']);
        } else {
            $users = User::where('id', $request->user_id)
                ->whereNotNull('fcm_token')
                ->get(['id', 'fcm_token']);
        }

        $sentTokens = [];
        $tokens = [];
        foreach ($users as $user) {
            if (in_array($user->fcm_token, $sentTokens)) continue;
            $sentTokens[] = $user->fcm_token;
            $tokens[]     = $user->fcm_token;
        }

        $result = FirebaseNotification::sendBulk(
            $tokens,
            $title,
            $message,
            array_filter(['type' => 'broadcast', 'image' => $imageUrl])
        );
        $sent   = $result['sent'];
        $failed = $result['failed'];

        DB::table('notification_logs')->insert([
            'title'        => $title,
            'message'      => $message,
            'target'       => $target,
            'target_type'  => 'doctor',
            'user_id'      => $target === 'specific' ? $request->user_id : null,
            'sent_count'   => $sent,
            'failed_count' => $failed,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.doctor.notification.index')
            ->with('success', "Notification sent! ✅ Sent: {$sent} | ❌ Failed: {$failed}");
    }
}
