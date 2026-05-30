<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $totalUsers      = User::whereHas('role', fn($q) => $q->where('role', 'user'))->count();
        $usersWithToken  = User::whereHas('role', fn($q) => $q->where('role', 'user'))->whereNotNull('fcm_token')->count();
        $users           = User::whereHas('role', fn($q) => $q->where('role', 'user'))->whereNotNull('fcm_token')->get(['id', 'name', 'email']);
        $logs            = DB::table('notification_logs')->orderByDesc('id')->limit(20)->get();

        return view('admin.notification.index', compact('totalUsers', 'usersWithToken', 'users', 'logs'));
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

        // Upload image if provided
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/notifications', $filename, 'public');
            $imageUrl = asset('storage/upload/notifications/' . $filename);
        }

        if ($target === 'all') {
            $users = User::whereHas('role', fn($q) => $q->where('role', 'user'))
                ->whereNotNull('fcm_token')
                ->distinct('fcm_token')
                ->get(['id', 'fcm_token']);
        } else {
            $users = User::where('id', $request->user_id)
                ->whereNotNull('fcm_token')
                ->get(['id', 'fcm_token']);
        }

        $sentTokens = [];
        foreach ($users as $user) {
            if (in_array($user->fcm_token, $sentTokens)) continue;
            $sentTokens[] = $user->fcm_token;

            $ok = FirebaseNotification::send(
                $user->fcm_token,
                $title,
                $message,
                array_filter(['type' => 'broadcast', 'image' => $imageUrl])
            );
            $ok ? $sent++ : $failed++;
        }

        DB::table('notification_logs')->insert([
            'title'        => $title,
            'message'      => $message,
            'target'       => $target,
            'user_id'      => $target === 'specific' ? $request->user_id : null,
            'sent_count'   => $sent,
            'failed_count' => $failed,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.notification.index')
            ->with('success', "Notification sent! ✅ Sent: {$sent} | ❌ Failed: {$failed}");
    }
}
